<?php

namespace Database\Seeders;

use App\Models\Geographic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class GeographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('geographic')->truncate();
        
        // Path to the CSV file
        $csvFile = base_path('docs/geographic.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error("Geographic CSV file not found at: {$csvFile}");
            return;
        }
        
        // Read the CSV file
        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0); // First row contains headers
        
        $records = $csv->getRecords();
        $data = [];
        $batchSize = 1000;
        $count = 0;
        
        foreach ($records as $record) {
            // Skip empty rows
            if (empty($record['province_code'])) {
                continue;
            }
            
            $data[] = [
                'id' => $record['id'],
                'province_code' => $record['province_code'] ?: null,
                'province_name_kh' => $record['province_name_kh'] ?: null,
                'province_name_en' => $record['province_name_en'] ?: null,
                'district_code' => $record['district_code'] ?: null,
                'district_name_kh' => $record['district_name_kh'] ?: null,
                'district_name_en' => $record['district_name_en'] ?: null,
                'commune_code' => $record['commune_code'] ?: null,
                'commune_name_kh' => $record['commune_name_kh'] ?: null,
                'commune_name_en' => $record['commune_name_en'] ?: null,
                'village_code' => $record['village_code'] ?: null,
                'village_name_kh' => $record['village_name_kh'] ?: null,
                'village_name_en' => $record['village_name_en'] ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $count++;
            
            // Insert in batches for better performance
            if (count($data) >= $batchSize) {
                Geographic::insert($data);
                $this->command->info("Imported {$count} geographic records...");
                $data = [];
            }
        }
        
        // Insert remaining records
        if (!empty($data)) {
            Geographic::insert($data);
        }
        
        $this->command->info("Successfully imported {$count} geographic records!");
    }
}