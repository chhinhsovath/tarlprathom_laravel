<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSchools extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schools:import {--file=docs/schools_en_cleaned.json : The JSON file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import schools from cleaned JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonFile = base_path($this->option('file'));

        if (!file_exists($jsonFile)) {
            $this->error("File not found: $jsonFile");
            return Command::FAILURE;
        }

        $this->info("Reading JSON file: $jsonFile");
        $jsonData = file_get_contents($jsonFile);
        $schools = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Error parsing JSON: " . json_last_error_msg());
            return Command::FAILURE;
        }

        $this->info("Found " . count($schools) . " schools to import");

        // Check if table exists
        try {
            $tableExists = DB::table('pilot_schools')->limit(1)->exists();
        } catch (\Exception $e) {
            $this->error("Table 'pilot_schools' does not exist!");
            $this->error("Please run migrations first: php artisan migrate");
            return Command::FAILURE;
        }

        if ($this->confirm('Do you want to clear existing data before importing?', true)) {
            DB::table('pilot_schools')->delete();
            $this->info('Cleared existing pilot_schools data');
        }

        $this->info('Starting import...');
        $bar = $this->output->createProgressBar(count($schools));
        $bar->start();

        DB::beginTransaction();

        try {
            $imported = 0;
            $errors = [];
            $batch = [];
            $batchSize = 50;

            foreach ($schools as $index => $school) {
                try {
                    $record = [
                        'code' => substr($school['code'] ?? '', 0, 20),
                        'province_name' => substr($school['province_name'] ?? '', 0, 100),
                        'district_name' => substr($school['district_name'] ?? '', 0, 100),
                        'commune_name' => substr($school['commune_name'] ?? '', 0, 100),
                        'village_name' => substr($school['village_name'] ?? '', 0, 100),
                        'name' => substr($school['name'] ?? '', 0, 255),
                        'location' => substr($school['location'] ?? '', 0, 50),
                        'clsname' => substr($school['clsname'] ?? '', 0, 100),
                        'corsat' => substr($school['corsat'] ?? '', 0, 50),
                        'attprime' => substr($school['attprime'] ?? '', 0, 50),
                        'shifts' => substr($school['shifts'] ?? '', 0, 50),
                        'tcht' => min(9999, max(0, intval($school['tcht'] ?? 0))),
                        'tchf' => min(9999, max(0, intval($school['tchf'] ?? 0))),
                        'ntcht' => min(9999, max(0, intval($school['ntcht'] ?? 0))),
                        'ntchf' => min(9999, max(0, intval($school['ntchf'] ?? 0))),
                        'comteach' => min(9999, max(0, intval($school['comteach'] ?? 0))),
                        'nummonks' => min(9999, max(0, intval($school['nummonks'] ?? 0))),
                        'pawct' => min(9999, max(0, intval($school['pawct'] ?? 0))),
                        'pawcf' => min(9999, max(0, intval($school['pawcf'] ?? 0))),
                        'gst2sht' => min(9999, max(0, intval($school['gst2sht'] ?? 0))),
                        'gst2shf' => min(9999, max(0, intval($school['gst2shf'] ?? 0))),
                        'g1t' => min(9999, max(0, intval($school['g1t'] ?? 0))),
                        'g1f' => min(9999, max(0, intval($school['g1f'] ?? 0))),
                        'g2t' => min(9999, max(0, intval($school['g2t'] ?? 0))),
                        'g2f' => min(9999, max(0, intval($school['g2f'] ?? 0))),
                        'g3t' => min(9999, max(0, intval($school['g3t'] ?? 0))),
                        'g3f' => min(9999, max(0, intval($school['g3f'] ?? 0))),
                        'g4t' => min(9999, max(0, intval($school['g4t'] ?? 0))),
                        'g4f' => min(9999, max(0, intval($school['g4f'] ?? 0))),
                        'g5t' => min(9999, max(0, intval($school['g5t'] ?? 0))),
                        'g5f' => min(9999, max(0, intval($school['g5f'] ?? 0))),
                        'g6t' => min(9999, max(0, intval($school['g6t'] ?? 0))),
                        'g6f' => min(9999, max(0, intval($school['g6f'] ?? 0))),
                        'total' => min(99999, max(0, intval($school['total'] ?? 0))),
                        'female' => min(99999, max(0, intval($school['female'] ?? 0))),
                        'rept' => min(9999, max(0, intval($school['rept'] ?? 0))),
                        'repf' => min(9999, max(0, intval($school['repf'] ?? 0))),
                        'priroom' => min(999, max(0, intval($school['priroom'] ?? 0))),
                        'classes' => min(999, max(0, intval($school['classes'] ?? 0))),
                        'classg1' => min(99, max(0, intval($school['classg1'] ?? 0))),
                        'classg2' => min(99, max(0, intval($school['classg2'] ?? 0))),
                        'classg3' => min(99, max(0, intval($school['classg3'] ?? 0))),
                        'classg4' => min(99, max(0, intval($school['classg4'] ?? 0))),
                        'classg5' => min(99, max(0, intval($school['classg5'] ?? 0))),
                        'classg6' => min(99, max(0, intval($school['classg6'] ?? 0))),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $batch[] = $record;

                    if (count($batch) >= $batchSize) {
                        DB::table('pilot_schools')->insert($batch);
                        $imported += count($batch);
                        $batch = [];
                    }

                    $bar->advance();

                } catch (\Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'school_code' => $school['code'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Insert remaining records
            if (!empty($batch)) {
                DB::table('pilot_schools')->insert($batch);
                $imported += count($batch);
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);

            $this->info("✅ Import Summary:");
            $this->info("Successfully imported: $imported schools");
            
            if (!empty($errors)) {
                $this->warn("Errors encountered: " . count($errors));
                $errorLog = storage_path('logs/school_import_errors_' . date('Y-m-d_H-i-s') . '.json');
                file_put_contents($errorLog, json_encode($errors, JSON_PRETTY_PRINT));
                $this->warn("Error log saved to: $errorLog");
            }

            // Verify the import
            $count = DB::table('pilot_schools')->count();
            $this->info("Total records in database: $count");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Fatal error during import: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}