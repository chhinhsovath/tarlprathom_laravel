<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Csv\Letter;
use Livewire\Component;
use Livewire\WithFileUploads;

class CsvImport extends Component
{
    use WithFileUploads;

    public $csvFile;

    public $school_id;

    public $preview = [];

    public $headers = [];

    public $mapping = [
        'name' => null,
        'sex' => null,
        'age' => null,
        'class' => null,
    ];

    public $showMapping = false;

    public $importProgress = 0;

    public $importTotal = 0;

    public $importErrors = [];

    protected $rules = [
        'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        'school_id' => 'required|exists:schools,id',
    ];

    public function mount()
    {
        if (auth()->user()->role === 'teacher') {
            $this->school_id = auth()->user()->school_id;
        }
    }

    public function updatedCsvFile()
    {
        $this->validateOnly('csvFile');
        $this->processFile();
    }

    private function processFile()
    {
        try {
            $path = $this->csvFile->getRealPath();
            $csv = Letter::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);

            $this->headers = $csv->getHeader();
            $records = $csv->getRecords();

            $this->preview = [];
            $count = 0;
            foreach ($records as $record) {
                if ($count >= 5) {
                    break;
                }
                $this->preview[] = $record;
                $count++;
            }

            $this->showMapping = true;
            $this->autoMapFields();

        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'Failed to read CSV file: '.$e->getMessage(),
            ]);
        }
    }

    private function autoMapFields()
    {
        $fieldMap = [
            'name' => ['name', 'student_name', 'full_name', 'student'],
            'sex' => ['sex', 'gender', 'student_sex', 'student_gender'],
            'age' => ['age', 'student_age'],
            'class' => ['class', 'grade', 'student_class', 'student_grade'],
        ];

        foreach ($fieldMap as $field => $possibleHeaders) {
            foreach ($this->headers as $header) {
                if (in_array(strtolower($header), $possibleHeaders)) {
                    $this->mapping[$field] = $header;
                    break;
                }
            }
        }
    }

    public function import()
    {
        $this->validate([
            'school_id' => 'required|exists:schools,id',
            'mapping.name' => 'required',
            'mapping.sex' => 'required',
            'mapping.age' => 'required',
            'mapping.class' => 'required',
        ]);

        try {
            $path = $this->csvFile->getRealPath();
            $csv = Letter::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $this->importTotal = iterator_count($csv->getRecords());
            $this->importProgress = 0;
            $this->importErrors = [];

            DB::beginTransaction();

            foreach ($records as $offset => $record) {
                try {
                    $data = [
                        'name' => $record[$this->mapping['name']] ?? '',
                        'sex' => strtolower($record[$this->mapping['sex']] ?? ''),
                        'age' => (int) ($record[$this->mapping['age']] ?? 0),
                        'class' => $record[$this->mapping['class']] ?? '',
                        'school_id' => $this->school_id,
                    ];

                    // Validate individual record
                    $validator = Validator::make($data, [
                        'name' => 'required|string|max:255',
                        'sex' => 'required|in:male,female',
                        'age' => 'required|integer|min:5|max:18',
                        'class' => 'required|string|max:50',
                        'school_id' => 'required|exists:schools,id',
                    ]);

                    if ($validator->fails()) {
                        $this->importErrors[] = 'Row '.($offset + 2).': '.implode(', ', $validator->errors()->all());
                    } else {
                        Student::create($data);
                    }

                    $this->importProgress++;

                } catch (\Exception $e) {
                    $this->importErrors[] = 'Row '.($offset + 2).': '.$e->getMessage();
                }
            }

            DB::commit();

            $successCount = $this->importProgress - count($this->importErrors);

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Import Complete',
                'text' => "Successfully imported {$successCount} students. ".
                         (count($this->importErrors) > 0 ? count($this->importErrors).' errors occurred.' : ''),
            ]);

            if (count($this->importErrors) === 0) {
                $this->reset();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Import Failed',
                'text' => $e->getMessage(),
            ]);
        }
    }

    public function downloadTemplate()
    {
        $headers = ['Name', 'Sex', 'Age', 'Class'];
        $sample = [
            ['Sok Dara', 'male', 10, 'Grade 4'],
            ['Srey Mey', 'female', 11, 'Grade 5'],
            ['Chan Vuthy', 'male', 9, 'Grade 3'],
        ];

        $filename = 'student_import_template.csv';
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, $headers);
        foreach ($sample as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function render()
    {
        return view('livewire.csv-import', [
            'schools' => auth()->user()->role === 'admin' ? School::all() : collect(),
        ]);
    }
}
