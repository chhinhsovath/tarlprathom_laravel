<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Check if column exists
    $columns = DB::select('SHOW COLUMNS FROM mentor_school');
    $columnExists = false;

    foreach ($columns as $column) {
        if ($column->Field === 'pilot_school_id') {
            $columnExists = true;
            break;
        }
    }

    if (! $columnExists) {
        // Add the column
        DB::statement('ALTER TABLE mentor_school ADD COLUMN pilot_school_id BIGINT UNSIGNED NULL AFTER school_id');

        // Add foreign key constraint
        DB::statement('ALTER TABLE mentor_school ADD CONSTRAINT mentor_school_pilot_school_id_foreign 
                      FOREIGN KEY (pilot_school_id) REFERENCES pilot_schools(id) ON DELETE CASCADE');

        // Add index
        DB::statement('ALTER TABLE mentor_school ADD INDEX mentor_school_pilot_school_id_index (pilot_school_id)');

        echo "✅ Column 'pilot_school_id' added successfully to mentor_school table\n";
    } else {
        echo "✅ Column 'pilot_school_id' already exists in mentor_school table\n";
    }

    // Show final table structure
    echo "\nTable structure:\n";
    $columns = DB::select('SHOW COLUMNS FROM mentor_school');
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
