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
        echo "Fixing mentoring visits to use pilot schools only...\n";
        
        $this->redistributeMentoringVisits();
        $this->clearLegacySchoolReferences();
        $this->updateMentoringVisitConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        echo "This migration cannot be reversed as it modifies data structure.\n";
    }

    /**
     * Redistribute mentoring visits across pilot schools
     */
    private function redistributeMentoringVisits(): void
    {
        echo "Redistributing mentoring visits across pilot schools...\n";
        
        // Get all pilot schools
        $pilotSchools = DB::table('pilot_schools')->pluck('id')->toArray();
        
        if (empty($pilotSchools)) {
            echo "No pilot schools found. Cannot redistribute visits.\n";
            return;
        }
        
        // Get all mentoring visits
        $visits = DB::table('mentoring_visits')->orderBy('id')->get();
        
        $visitCount = 0;
        foreach ($visits as $visit) {
            // Distribute visits evenly across pilot schools
            $pilotSchoolId = $pilotSchools[$visitCount % count($pilotSchools)];
            
            // Get pilot school info for updating location fields
            $pilotSchool = DB::table('pilot_schools')->find($pilotSchoolId);
            
            DB::table('mentoring_visits')
                ->where('id', $visit->id)
                ->update([
                    'pilot_school_id' => $pilotSchoolId,
                    'school_id' => null, // Clear legacy reference
                    'province' => $pilotSchool->province,
                ]);
            
            $visitCount++;
        }
        
        echo "Redistributed {$visitCount} mentoring visits across " . count($pilotSchools) . " pilot schools.\n";
    }

    /**
     * Clear legacy school references
     */
    private function clearLegacySchoolReferences(): void
    {
        echo "Clearing legacy school references...\n";
        
        // Update all visits to remove school_id references
        DB::table('mentoring_visits')->update(['school_id' => null]);
        
        // Make school_id nullable and pilot_school_id required
        if (Schema::hasTable('mentoring_visits')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                // Make pilot_school_id non-nullable since all visits should reference pilot schools
                $table->unsignedBigInteger('pilot_school_id')->nullable(false)->change();
            });
        }
        
        echo "Cleared legacy school references.\n";
    }

    /**
     * Update mentoring visit constraints
     */
    private function updateMentoringVisitConstraints(): void
    {
        echo "Updating mentoring visit constraints...\n";
        
        // Add index on pilot_school_id for better performance
        if (Schema::hasTable('mentoring_visits') && !$this->indexExists('mentoring_visits', 'mentoring_visits_pilot_school_id_index')) {
            Schema::table('mentoring_visits', function (Blueprint $table) {
                $table->index('pilot_school_id');
            });
        }
        
        echo "Updated constraints.\n";
    }

    /**
     * Check if an index exists
     */
    private function indexExists($table, $indexName): bool
    {
        if (DB::getDriverName() === 'pgsql') {
            $result = DB::select("
                SELECT indexname 
                FROM pg_indexes 
                WHERE tablename = ? AND indexname = ?
            ", [$table, $indexName]);
            return count($result) > 0;
        } else {
            $indexes = DB::select("SHOW INDEX FROM {$table}");
            foreach ($indexes as $index) {
                if ($index->Key_name === $indexName) {
                    return true;
                }
            }
            return false;
        }
    }
};