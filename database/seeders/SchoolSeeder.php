<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Phnom Penh Primary School',
                'province' => 'Phnom Penh',
                'district' => 'Chamkarmon',
                'commune' => 'Tonle Bassac',
                'cluster' => 'Central',
            ],
            [
                'name' => 'Siem Reap Elementary School',
                'province' => 'Siem Reap',
                'district' => 'Siem Reap',
                'commune' => 'Svay Dangkum',
                'cluster' => 'North',
            ],
            [
                'name' => 'Battambang Primary School',
                'province' => 'Battambang',
                'district' => 'Battambang',
                'commune' => 'Svay Por',
                'cluster' => 'West',
            ],
            [
                'name' => 'Kampong Cham Elementary',
                'province' => 'Kampong Cham',
                'district' => 'Kampong Cham',
                'commune' => 'Kampong Cham',
                'cluster' => 'East',
            ],
            [
                'name' => 'Kandal Province School',
                'province' => 'Kandal',
                'district' => 'Ta Khmau',
                'commune' => 'Ta Khmau',
                'cluster' => 'South',
            ],
        ];

        foreach ($schools as $index => $school) {
            $school['school_name'] = $school['name'];
            $school['school_code'] = strtoupper(substr($school['province'], 0, 2)) . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            School::create($school);
        }
    }
}
