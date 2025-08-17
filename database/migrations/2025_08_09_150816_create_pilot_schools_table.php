<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pilot_schools', function (Blueprint $table) {
            $table->id();
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('cluster', 100);
            $table->string('school_name', 255);
            $table->string('school_code', 20)->unique();
            $table->timestamps();

            // Indexes for fast lookups
            $table->index('province');
            $table->index('district');
            $table->index('cluster');
            $table->index('school_code');
            $table->index(['province', 'district']);
            $table->index(['province', 'district', 'cluster']);
        });

        // Insert the pilot schools data
        DB::table('pilot_schools')->insert([
            // Battambang schools
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'បឹងខ្ទុម', 'school_name' => 'ផ្លូវមាស', 'school_code' => '2070301014', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'បឹងខ្ទុម', 'school_name' => 'បឹងខ្ទុម', 'school_code' => '2070303016', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'បឹងខ្ទុម', 'school_name' => 'អូរដា', 'school_code' => '2070306030', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ត្រែង', 'school_name' => 'ត្រែង', 'school_code' => '2070401007', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ត្រែង', 'school_name' => 'មាសពិទូគីឡូ៣៨', 'school_code' => '2070405004', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ត្រែង', 'school_name' => 'សុវត្ថិភាពកុម៉ាតស៊ុ', 'school_code' => '2070401044', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'បាដាក', 'school_name' => 'បាដាក', 'school_code' => '2070503013', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'បាដាក', 'school_name' => 'ពេជ្រចង្វា', 'school_code' => '2070502020', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'បាដាក', 'school_name' => 'នាងពួន', 'school_code' => '2070508029', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ព្រៃអំពរ', 'school_name' => 'ស៊ិនតុ', 'school_code' => '2070203001', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ព្រៃអំពរ', 'school_name' => 'ព្រៃអំពរ', 'school_code' => '2070205008', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ព្រៃអំពរ', 'school_name' => 'កណ្តាលស្ទឹង', 'school_code' => '2070206011', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ភ្ជាវ', 'school_name' => 'ភ្ជាវ', 'school_code' => '2070402015', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ភ្ជាវ', 'school_name' => 'ស្វាយស', 'school_code' => '2070409032', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Battambang', 'district' => 'Rattanakmondol', 'cluster' => 'ភ្ជាវ', 'school_name' => 'តាគ្រក់', 'school_code' => '2070408031', 'created_at' => now(), 'updated_at' => now()],

            // Kampong Cham schools
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងស្តៅ', 'school_name' => 'ស្ដៅលើ', 'school_code' => '3071003033', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងស្តៅ', 'school_name' => 'ស្ដៅ', 'school_code' => '3071005034', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងស្តៅ', 'school_name' => 'វត្តចាស់', 'school_code' => '3070208044', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងស្តៅ', 'school_name' => 'ព្រែកត្រឡោក', 'school_code' => '3070202039', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងព្រែកលីវ', 'school_name' => 'ខ្ចៅ', 'school_code' => '3070307008', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងព្រែកលីវ', 'school_name' => 'ស្វាយស្រណោះ', 'school_code' => '3070807027', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងព្រែកលីវ', 'school_name' => 'រការអារ', 'school_code' => '3070809028', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងព្រែកកុយ', 'school_name' => 'អូរស្វាយ', 'school_code' => '3070506017', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងព្រែកកុយ', 'school_name' => 'ព្រែកកុយ', 'school_code' => '3070501014', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងអង្គរបាន', 'school_name' => 'សាខា២', 'school_code' => '3070108004', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងអង្គរបាន', 'school_name' => 'អន្ទង់ស', 'school_code' => '3070101001', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងអង្គរបាន', 'school_name' => 'សាខា១', 'school_code' => '3070104002', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងរាយប៉ាយ', 'school_name' => 'ទួលវិហារ', 'school_code' => '3070703042', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងរាយប៉ាយ', 'school_name' => 'រាយប៉ាយ', 'school_code' => '3070601018', 'created_at' => now(), 'updated_at' => now()],
            ['province' => 'Kampongcham', 'district' => 'Kgmeas', 'cluster' => 'កម្រងរាយប៉ាយ', 'school_name' => 'ទួលបី', 'school_code' => '3070704023', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilot_schools');
    }
};
