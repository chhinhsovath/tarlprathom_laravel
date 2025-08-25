<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DatabaseOptimizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only apply optimizations in production or when connecting to remote database
        if ($this->shouldApplyOptimizations()) {
            $this->optimizeDatabaseConnection();
            $this->setupQueryCaching();
            $this->monitorSlowQueries();
        }
    }

    /**
     * Determine if optimizations should be applied
     */
    private function shouldApplyOptimizations(): bool
    {
        $dbHost = config('database.connections.mysql.host');

        return ! in_array($dbHost, ['localhost', '127.0.0.1', '::1']);
    }

    /**
     * Optimize database connection settings
     */
    private function optimizeDatabaseConnection(): void
    {
        // Set MySQL session variables for better performance
        try {
            DB::statement("SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'");
            DB::statement('SET SESSION group_concat_max_len = 1000000');

            // Optimize for remote connections
            if (config('database-optimization.remote_optimizations.enable_query_cache')) {
                DB::statement('SET SESSION query_cache_type = 1');
            }
        } catch (\Exception $e) {
            Log::warning('Could not set MySQL session variables: '.$e->getMessage());
        }
    }

    /**
     * Setup query caching for frequently accessed tables
     */
    private function setupQueryCaching(): void
    {
        $cacheableTables = config('database-optimization.query_optimizations.cacheable_tables', []);
        $cacheDurations = config('database-optimization.query_optimizations.table_cache_duration', []);

        // Listen for queries on cacheable tables
        DB::listen(function ($query) use ($cacheableTables, $cacheDurations) {
            // Check if query is a SELECT on a cacheable table
            if (stripos($query->sql, 'select') === 0) {
                foreach ($cacheableTables as $table) {
                    if (stripos($query->sql, $table) !== false) {
                        // This is a simplified example - in production you'd want more sophisticated caching
                        $cacheKey = 'db_query_'.md5($query->sql.serialize($query->bindings));
                        $duration = $cacheDurations[$table] ?? $cacheDurations['default'] ?? 300;

                        // Note: This is for monitoring purposes
                        // Actual query caching should be implemented at the repository level
                        if (config('database-optimization.monitoring.monitor_queries')) {
                            Log::debug("Cacheable query detected for table: $table", [
                                'sql' => $query->sql,
                                'duration' => $duration,
                                'cache_key' => $cacheKey,
                            ]);
                        }
                        break;
                    }
                }
            }
        });
    }

    /**
     * Monitor and log slow queries
     */
    private function monitorSlowQueries(): void
    {
        $threshold = config('database-optimization.query_optimizations.slow_query_threshold', 1000);

        DB::listen(function ($query) use ($threshold) {
            if ($query->time > $threshold) {
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time.'ms',
                ]);

                // Alert if query is extremely slow
                $alertThreshold = config('database-optimization.monitoring.alert_threshold', 5000);
                if ($query->time > $alertThreshold) {
                    Log::error('CRITICAL: Extremely slow query detected!', [
                        'sql' => $query->sql,
                        'time' => $query->time.'ms',
                        'connection' => $query->connectionName,
                    ]);
                }
            }
        });
    }
}
