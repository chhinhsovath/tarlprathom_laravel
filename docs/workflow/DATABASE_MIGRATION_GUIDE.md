# Database Migration Guide - Tarlprathom Laravel

## 1. Migration Best Practices

### Naming Conventions

```bash
# Creating migrations
php artisan make:migration create_students_table
php artisan make:migration add_gender_to_students_table
php artisan make:migration modify_grade_column_in_students_table
```

### Migration Structure

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('grade');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('school_id');
            $table->index('grade');
            $table->index(['school_id', 'grade']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
```

## 2. Migration Workflow

### Development Workflow

```bash
# 1. Create migration
php artisan make:migration add_score_to_mentoring_visits_table

# 2. Edit migration file
# 3. Run migration locally
php artisan migrate

# 4. Test the changes
# 5. If changes needed, rollback
php artisan migrate:rollback

# 6. Edit and re-run
php artisan migrate
```

### Safe Production Deployment

```bash
# 1. Backup database first
mysqldump -u root -p tarlprathom > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Check migration status
php artisan migrate:status

# 3. Dry run (Laravel 8+)
php artisan migrate --pretend

# 4. Run migrations
php artisan migrate --force

# 5. Verify
php artisan migrate:status
```

## 3. Schema Version Control

### Track Schema Changes

Create `database/schema/` directory for schema snapshots:

```bash
# Export current schema
mysqldump -u root -p --no-data tarlprathom > database/schema/schema_$(date +%Y%m%d).sql

# Track in git
git add database/schema/schema_*.sql
git commit -m "Schema snapshot $(date +%Y-%m-%d)"
```

### Migration History Documentation

Create `database/MIGRATION_HISTORY.md`:

```markdown
# Migration History

## 2024-01-15
- Added `score` column to mentoring_visits table
- Added index on `visit_date` for performance
- Migration: `2024_01_15_000001_add_score_to_mentoring_visits_table.php`

## 2024-01-10
- Created assessments table
- Added foreign key constraints
- Migration: `2024_01_10_000001_create_assessments_table.php`
```

## 4. Database Seeding Strategy

### Create Seeders

```php
// database/seeders/SchoolSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        $schools = [
            ['school_name' => 'Phnom Penh Primary School', 'address' => 'Phnom Penh'],
            ['school_name' => 'Siem Reap Elementary', 'address' => 'Siem Reap'],
            ['school_name' => 'Battambang School', 'address' => 'Battambang'],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
```

### Environment-Specific Seeding

```php
// database/seeders/DatabaseSeeder.php
public function run()
{
    $this->call([
        SchoolSeeder::class,
        UserSeeder::class,
    ]);

    if (app()->environment('local', 'staging')) {
        $this->call([
            TestStudentSeeder::class,
            TestAssessmentSeeder::class,
        ]);
    }
}
```

## 5. Migration Safety Checks

### Pre-Migration Checklist

Create `database/migrations/migration_checklist.md`:

```markdown
# Migration Checklist

Before creating a migration:
- [ ] Is this change backward compatible?
- [ ] Will this lock tables for a long time?
- [ ] Are there foreign key constraints to consider?
- [ ] Do we need to migrate existing data?
- [ ] Is there a rollback plan?

Before running in production:
- [ ] Backed up the database?
- [ ] Tested on staging environment?
- [ ] Checked for long-running queries?
- [ ] Scheduled during low-traffic time?
- [ ] Notified team members?
```

### Safe Column Modifications

```php
// Safe: Adding nullable column
Schema::table('students', function (Blueprint $table) {
    $table->string('middle_name')->nullable()->after('name');
});

// Risky: Changing column type (needs data migration)
Schema::table('students', function (Blueprint $table) {
    // First, add new column
    $table->integer('grade_new')->nullable();
});

// Migrate data
DB::statement('UPDATE students SET grade_new = CAST(grade AS UNSIGNED)');

// Then drop old and rename
Schema::table('students', function (Blueprint $table) {
    $table->dropColumn('grade');
    $table->renameColumn('grade_new', 'grade');
});
```

## 6. Database Backup Script

Create `scripts/backup_database.sh`:

```bash
#!/bin/bash

# Configuration
DB_NAME="tarlprathom"
DB_USER="root"
BACKUP_DIR="/backups/mysql"
RETENTION_DAYS=30

# Create backup directory if not exists
mkdir -p $BACKUP_DIR

# Create backup
BACKUP_FILE="$BACKUP_DIR/${DB_NAME}_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u $DB_USER -p $DB_NAME > $BACKUP_FILE

# Compress
gzip $BACKUP_FILE

echo "Backup created: ${BACKUP_FILE}.gz"

# Clean old backups
find $BACKUP_DIR -name "${DB_NAME}_*.sql.gz" -mtime +$RETENTION_DAYS -delete
echo "Old backups cleaned"

# Sync to cloud (optional)
# aws s3 sync $BACKUP_DIR s3://your-backup-bucket/mysql/
```

## 7. Migration Monitoring

### Add Migration Logging

```php
// Create app/Listeners/LogMigrations.php
<?php

namespace App\Listeners;

use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Log;

class LogMigrations
{
    public function handle($event)
    {
        if ($event instanceof MigrationsStarted) {
            Log::info('Migrations started', [
                'user' => get_current_user(),
                'timestamp' => now()
            ]);
        }
        
        if ($event instanceof MigrationsEnded) {
            Log::info('Migrations completed', [
                'user' => get_current_user(),
                'timestamp' => now()
            ]);
        }
    }
}
```

## 8. Database Health Checks

Create `app/Console/Commands/CheckDatabaseHealth.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabaseHealth extends Command
{
    protected $signature = 'db:health';
    protected $description = 'Check database health and consistency';

    public function handle()
    {
        $this->info('Checking database health...');

        // Check table sizes
        $tables = DB::select("
            SELECT 
                table_name,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
                table_rows
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
            ORDER BY (data_length + index_length) DESC
        ");

        $this->table(['Table', 'Size (MB)', 'Rows'], array_map(function($table) {
            return [
                $table->table_name,
                $table->size_mb,
                number_format($table->table_rows)
            ];
        }, $tables));

        // Check for missing indexes
        $this->checkMissingIndexes();

        // Check foreign key constraints
        $this->checkForeignKeys();

        $this->info('Database health check completed!');
    }

    private function checkMissingIndexes()
    {
        // Add your index checking logic
        $this->info('Checking indexes...');
    }

    private function checkForeignKeys()
    {
        $orphans = DB::select("
            SELECT 'assessments' as table_name, COUNT(*) as count
            FROM assessments a
            LEFT JOIN students s ON a.student_id = s.id
            WHERE s.id IS NULL
        ");

        if ($orphans[0]->count > 0) {
            $this->error("Found {$orphans[0]->count} orphaned records in assessments table!");
        }
    }
}
```

## 9. Emergency Rollback Plan

### Quick Rollback Script

Create `scripts/emergency_rollback.sh`:

```bash
#!/bin/bash

echo "EMERGENCY DATABASE ROLLBACK"
echo "=========================="

# Get latest backup
LATEST_BACKUP=$(ls -t /backups/mysql/tarlprathom_*.sql.gz | head -1)

if [ -z "$LATEST_BACKUP" ]; then
    echo "ERROR: No backup found!"
    exit 1
fi

echo "Latest backup: $LATEST_BACKUP"
echo "This will DESTROY current database. Continue? (yes/no)"
read CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Rollback cancelled."
    exit 0
fi

# Restore
gunzip < $LATEST_BACKUP | mysql -u root -p tarlprathom

echo "Database restored from $LATEST_BACKUP"

# Run migrations to sync
php artisan migrate:status
```

## 10. Migration Documentation Template

For each major migration, create documentation:

```markdown
# Migration: Add Multi-Language Support

**Date:** 2024-01-20
**Author:** Developer Name
**Migration Files:** 
- 2024_01_20_000001_add_language_to_users_table.php
- 2024_01_20_000002_create_translations_table.php

## Purpose
Add support for Khmer/English language switching

## Changes
1. Added `preferred_language` to users table
2. Created translations table for dynamic content
3. Added indexes for performance

## Rollback Plan
```sql
ALTER TABLE users DROP COLUMN preferred_language;
DROP TABLE IF EXISTS translations;
```

## Testing
- [ ] User can switch languages
- [ ] Translations load correctly
- [ ] No performance degradation

## Notes
- Migration takes ~30 seconds on production data
- No downtime required
```