<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks for MySQL
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        // Clear existing schools data
        DB::table('schools')->truncate();
        
        // Insert the complete schools data
        DB::table('schools')->insert([
            ['id' => 1, 'school_name' => 'ប.ប.វត្ត', 'school_code' => '2070301611', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ចុ.ងគ.ប', 'name' => 'ប.ប.វត្ត', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'school_name' => 'ប.ចុ.ងគ.ប', 'school_code' => '2070303016', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ចុ.ងគ.ប', 'name' => 'ប.ចុ.ងគ.ប', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'school_name' => 'ប.ប.វិទ្យា', 'school_code' => '2070306036', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ចុ.ងគ.ប', 'name' => 'ប.ប.វិទ្យា', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'school_name' => 'ប.តុខ្នុង', 'school_code' => '2070401007', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ប្រុ.រី', 'name' => 'ប.តុខ្នុង', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'school_name' => 'ប.រាជសិរីរត្ន', 'school_code' => '2070405004', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ប្រុ.រី', 'name' => 'ប.រាជសិរីរត្ន', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'school_name' => 'ប.រាជសិរីរត្ន១', 'school_code' => '2070401034', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ប្រុ.រី', 'name' => 'ប.រាជសិរីរត្ន១', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'school_name' => 'ប.ប.វត្ត', 'school_code' => '2070503013', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ចុ.ប្រាជ.តំ', 'name' => 'ប.ប.វត្ត', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'school_name' => 'ប.ប.ព្រែកង', 'school_code' => '2070500020', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ចុ.ប្រាជ.តំ', 'name' => 'ប.ប.ព្រែកង', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'school_name' => 'ប.ជើងក្បែរ', 'school_code' => '2070508025', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ចុ.ប្រាជ.តំ', 'name' => 'ប.ជើងក្បែរ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'school_name' => 'ប.ព្រ.ដា.ចរ', 'school_code' => '2070203001', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ព្រ.ដា.ចរ', 'name' => 'ប.ព្រ.ដា.ចរ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'school_name' => 'ប.ព្រ.ដា.ចរ', 'school_code' => '2070205008', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ព្រ.ដា.ចរ', 'name' => 'ប.ព្រ.ដា.ចរ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'school_name' => 'ប.សុខ្សាន្តិភាព', 'school_code' => '2070200111', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'ព្រ.ដា.ចរ', 'name' => 'ប.សុខ្សាន្តិភាព', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'school_name' => 'ប.រ.ហួ', 'school_code' => '2070420015', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'រ.ហួ', 'name' => 'ប.រ.ហួ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'school_name' => 'ប.រ.ហួ', 'school_code' => '2070409032', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'រ.ហួ', 'name' => 'ប.រ.ហួ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'school_name' => 'ប.រ.ហួក', 'school_code' => '2070408031', 'province' => 'Battambang', 'district' => 'Rattanakamondol', 'cluster' => 'រ.ហួ', 'name' => 'ប.រ.ហួក', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'school_name' => 'បា.ហាន', 'school_code' => '3070003053', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបាម៉ាន', 'name' => 'បា.ហាន', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'school_name' => 'បា.ហាន', 'school_code' => '3070005036', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបាម៉ាន', 'name' => 'បា.ហាន', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'school_name' => 'ព្រៃ.វែង', 'school_code' => '3070208044', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបាម៉ាន', 'name' => 'ព្រៃ.វែង', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'school_name' => 'ត្រពាំងក្រសាន់', 'school_code' => '3070202055', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបាម៉ាន', 'name' => 'ត្រពាំងក្រសាន់', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'school_name' => 'បា.ហាន', 'school_code' => '3070207008', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងប្រាម៉ាន', 'name' => 'បា.ហាន', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'school_name' => 'បា.ហានពុទ្ធិបាដ', 'school_code' => '3070807027', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងប្រាម៉ាន', 'name' => 'បា.ហានពុទ្ធិបាដ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'school_name' => 'ព្រៃ.វែង', 'school_code' => '3070809028', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងប្រាម៉ាន', 'name' => 'ព្រៃ.វែង', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'school_name' => 'ព្រ.វ.រិន.យុ', 'school_code' => '3070506617', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.រិន.យុ', 'name' => 'ព្រ.វ.រិន.យុ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'school_name' => 'ក្បែរភ្នំ.វៀ', 'school_code' => '3070501014', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.រិន.យុ', 'name' => 'ក្បែរភ្នំ.វៀ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'school_name' => 'បា.រិន.យុ', 'school_code' => '3070508004', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.រិន.យុ', 'name' => 'បា.រិន.យុ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'school_name' => 'បាំងលេង', 'school_code' => '3070191001', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.រិន.យុ', 'name' => 'បាំងលេង', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'school_name' => 'ក្បែរកន្ធុម', 'school_code' => '3070104002', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.រិន.យុ', 'name' => 'ក្បែរកន្ធុម', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'school_name' => 'ព្រ.សែនកេត', 'school_code' => '3070203042', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.សែនកេត', 'name' => 'ព្រ.សែនកេត', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'school_name' => 'ភ្នំទីឃ្លាំង', 'school_code' => '3070601018', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.សែនកេត', 'name' => 'ភ្នំទីឃ្លាំង', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'school_name' => 'ប.វត្ត', 'school_code' => '3070704053', 'province' => 'Kampongcham', 'district' => 'Kampeas', 'cluster' => 'ក្រុងបា.សែនកេត', 'name' => 'ប.វត្ត', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Reset the sequence to match the highest ID
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("SELECT setval('schools_id_seq', 30, true)");
        } elseif (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE schools AUTO_INCREMENT = 31");
        }
        
        // Re-enable foreign key checks for MySQL
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This would restore previous data, but since we're replacing with complete data,
        // we'll just truncate and let other seeders handle restoration if needed
        DB::table('schools')->truncate();
    }
};
