<?php

namespace Database\Seeders;

use App\Imports\PilotSchoolsImport;
use App\Models\PilotSchool;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PilotSchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $excelFilePath = base_path('docs/pilot_school.xlsx');

        // Check if the Excel file exists
        if (!file_exists($excelFilePath)) {
            $this->command->error("Excel file not found at: {$excelFilePath}");
            return;
        }

        $this->command->info("Starting pilot schools import from Excel file...");

        try {
            // Clear existing data
            $this->command->info("Clearing existing pilot schools data...");
            PilotSchool::truncate();

            // Import from Excel
            $this->command->info("Importing data from Excel...");
            
            // Use a closure to handle the import with more control
            Excel::import(new class implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                public function collection($rows)
                {
                    foreach ($rows as $row) {
                        // Convert row to array and handle different possible column names
                        $rowData = $row->toArray();
                        
                        // Log the first few rows to understand the structure
                        if ($rows->search($row) < 3) {
                            Log::info("Row " . ($rows->search($row) + 1) . " data:", $rowData);
                        }

                        // Try different possible column mappings
                        $province = $rowData['province'] ?? $rowData['ខេត្ត'] ?? $rowData['ខេត្តទីរុង'] ?? '';
                        $district = $rowData['district'] ?? $rowData['ស្រុក'] ?? $rowData['ស្រុកខណ្ឌ'] ?? '';
                        $cluster = $rowData['cluster'] ?? $rowData['កម្រង'] ?? $rowData['កម្រងសាលា'] ?? '';
                        $schoolName = $rowData['school_name'] ?? $rowData['សាលារៀន'] ?? $rowData['ឈ្មោះសាលារៀន'] ?? $rowData['ឈ_មោះសាលារៀន'] ?? '';
                        $schoolCode = $rowData['school_code'] ?? $rowData['លេខកូដសាលារៀន'] ?? $rowData['កូដ'] ?? $rowData['លេខកូដ'] ?? '';

                        // Skip empty rows
                        if (empty($province) || empty($district) || empty($schoolName) || empty($schoolCode)) {
                            continue;
                        }

                        // Create the pilot school record
                        PilotSchool::create([
                            'province' => trim($province),
                            'district' => trim($district),
                            'cluster' => trim($cluster),
                            'school_name' => trim($schoolName),
                            'school_code' => trim($schoolCode),
                        ]);
                    }
                }
            }, $excelFilePath);

            $count = PilotSchool::count();
            $this->command->info("Successfully imported {$count} pilot schools from Excel file.");

            // Display some sample records
            $this->command->info("Sample imported records:");
            $samples = PilotSchool::limit(5)->get();
            foreach ($samples as $school) {
                $this->command->line("- {$school->school_name} ({$school->school_code}) - {$school->province}, {$school->district}");
            }

        } catch (\Exception $e) {
            $this->command->error("Error importing pilot schools: " . $e->getMessage());
            Log::error("Pilot schools import error", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Fallback: if import fails, we can keep the existing migration data
            $this->command->info("Import failed. Checking if migration data exists...");
            if (PilotSchool::count() == 0) {
                $this->command->info("No data found. You may need to run the migration first.");
            }
        }
    }
}