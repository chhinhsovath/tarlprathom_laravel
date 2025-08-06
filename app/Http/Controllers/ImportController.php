<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,coordinator']);
    }

    public function index()
    {
        return view('imports.index');
    }

    public function showSchoolsImport()
    {
        return view('imports.schools');
    }

    public function showUsersImport()
    {
        return view('imports.users');
    }

    public function showStudentsImport()
    {
        return view('imports.students');
    }

    public function importSchools(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $csv = array_map('str_getcsv', file($file));
        $header = array_shift($csv);

        $expectedColumns = ['school_name', 'school_code', 'province', 'district', 'cluster'];
        if (array_diff($expectedColumns, $header)) {
            return back()->withErrors(['file' => 'CSV must contain columns: '.implode(', ', $expectedColumns)]);
        }

        $imported = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($csv as $index => $row) {
                $data = array_combine($header, $row);

                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'school_code' => 'required|string|max:50|unique:schools,school_code',
                    'province' => 'required|string|max:255',
                    'district' => 'required|string|max:255',
                    'cluster' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    $errors[] = 'Row '.($index + 2).': '.implode(', ', $validator->errors()->all());

                    continue;
                }

                School::updateOrCreate(
                    ['school_code' => $data['school_code']],
                    [
                        'name' => $data['name'], // Set the 'name' field for compatibility
                        'name' => $data['name'],
                        'school_code' => $data['school_code'],
                        'province' => $data['province'],
                        'district' => $data['district'],
                        'cluster' => $data['cluster'] ?? null,
                    ]
                );
                $imported++;
            }

            if (empty($errors)) {
                DB::commit();

                return back()->with('success', "Successfully imported {$imported} schools.");
            } else {
                DB::rollback();

                return back()->withErrors(['file' => implode(' | ', $errors)]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['file' => 'Import failed: '.$e->getMessage()]);
        }
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'user_type' => 'required|in:teacher,mentor',
        ]);

        $file = $request->file('file');
        $csv = array_map('str_getcsv', file($file));
        $header = array_shift($csv);

        $expectedColumns = ['name', 'email', 'school_name', 'phone', 'sex'];
        if (array_diff($expectedColumns, $header)) {
            return back()->withErrors(['file' => 'CSV must contain columns: '.implode(', ', $expectedColumns)]);
        }

        $imported = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($csv as $index => $row) {
                $data = array_combine($header, $row);

                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users,email',
                    'name' => 'required|string',
                    'phone' => 'nullable|string|max:20',
                    'sex' => 'nullable|in:male,female',
                ]);

                if ($validator->fails()) {
                    $errors[] = 'Row '.($index + 2).': '.implode(', ', $validator->errors()->all());

                    continue;
                }

                $school = School::where('school_name', $data['name'])->first();
                if (! $school) {
                    $errors[] = 'Row '.($index + 2).": School '{$data['name']}' not found";

                    continue;
                }

                User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password123'), // Default password
                    'role' => $request->user_type,
                    'school_id' => $school->id,
                    'phone' => $data['phone'] ?? null,
                    'sex' => $data['sex'] ?? null,
                    'is_active' => true,
                ]);
                $imported++;
            }

            if (empty($errors)) {
                DB::commit();

                return back()->with('success', "Successfully imported {$imported} {$request->user_type}s.");
            } else {
                DB::rollback();

                return back()->withErrors(['file' => implode(' | ', $errors)]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['file' => 'Import failed: '.$e->getMessage()]);
        }
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $csv = array_map('str_getcsv', file($file));
        $header = array_shift($csv);

        $expectedColumns = ['name', 'sex', 'age', 'class', 'school_name'];
        if (array_diff($expectedColumns, $header)) {
            return back()->withErrors(['file' => 'CSV must contain columns: '.implode(', ', $expectedColumns)]);
        }

        $imported = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($csv as $index => $row) {
                $data = array_combine($header, $row);

                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'sex' => 'required|in:male,female',
                    'age' => 'required|integer|min:3|max:20',
                    'class' => 'required|string|max:255',
                    'name' => 'required|string',
                ]);

                if ($validator->fails()) {
                    $errors[] = 'Row '.($index + 2).': '.implode(', ', $validator->errors()->all());

                    continue;
                }

                $school = School::where('school_name', $data['name'])->first();
                if (! $school) {
                    $errors[] = 'Row '.($index + 2).": School '{$data['name']}' not found";

                    continue;
                }

                Student::create([
                    'name' => $data['name'],
                    'sex' => $data['sex'],
                    'age' => $data['age'],
                    'class' => $data['class'],
                    'school_id' => $school->id,
                ]);
                $imported++;
            }

            if (empty($errors)) {
                DB::commit();

                return back()->with('success', "Successfully imported {$imported} students.");
            } else {
                DB::rollback();

                return back()->withErrors(['file' => implode(' | ', $errors)]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['file' => 'Import failed: '.$e->getMessage()]);
        }
    }

    public function downloadTemplate($type)
    {
        $templates = [
            'schools' => [
                'name' => 'schools_template.csv',
                'headers' => ['school_name', 'school_code', 'province', 'district', 'cluster'],
                'sample' => ['Example School', 'SCH001', 'Phnom Penh', 'Central District', 'Cluster A'],
            ],
            'teachers' => [
                'name' => 'teachers_template.csv',
                'headers' => ['name', 'email', 'school_name', 'phone', 'sex'],
                'sample' => ['John Doe', 'john@example.com', 'Example School', '555-0123', 'male'],
            ],
            'mentors' => [
                'name' => 'mentors_template.csv',
                'headers' => ['name', 'email', 'school_name', 'phone', 'sex'],
                'sample' => ['Jane Smith', 'jane@example.com', 'Example School', '555-0124', 'female'],
            ],
            'students' => [
                'name' => 'students_template.csv',
                'headers' => ['name', 'sex', 'age', 'class', 'school_name'],
                'sample' => ['Alice Johnson', 'female', '10', '5A', 'Example School'],
            ],
        ];

        if (! isset($templates[$type])) {
            abort(404);
        }

        $template = $templates[$type];
        $csv = fopen('php://temp', 'w+');
        fputcsv($csv, $template['headers']);
        fputcsv($csv, $template['sample']);
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$template['name'].'"');
    }
}
