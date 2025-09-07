<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Exception;

class DatabaseHelper
{
    /**
     * Test PostgreSQL connection
     */
    public static function testPostgreSQL(): bool
    {
        try {
            Config::set('database.connections.pgsql_test', [
                'driver' => 'pgsql',
                'host' => env('DB_HOST', '157.10.73.52'),
                'port' => env('DB_PORT', '5432'),
                'database' => env('DB_DATABASE', 'tarl_prathom'),
                'username' => env('DB_USERNAME', 'admin'),
                'password' => env('DB_PASSWORD', 'P@ssw0rd'),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]);
            
            DB::connection('pgsql_test')->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Setup SQLite fallback
     */
    public static function setupSQLiteFallback(): bool
    {
        try {
            $dbPath = database_path('database.sqlite');
            
            // Create SQLite file if it doesn't exist
            if (!file_exists($dbPath)) {
                touch($dbPath);
            }
            
            // Update configuration
            Config::set('database.default', 'sqlite');
            Config::set('database.connections.sqlite.database', $dbPath);
            
            // Clear config cache
            Artisan::call('config:clear');
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Automatically configure database based on availability
     */
    public static function autoConfigureDatabase(): array
    {
        $result = [
            'success' => false,
            'connection' => null,
            'message' => ''
        ];
        
        // First try PostgreSQL
        if (self::testPostgreSQL()) {
            $result['success'] = true;
            $result['connection'] = 'pgsql';
            $result['message'] = 'Connected to PostgreSQL database successfully';
        } else {
            // Fallback to SQLite
            if (self::setupSQLiteFallback()) {
                $result['success'] = true;
                $result['connection'] = 'sqlite';
                $result['message'] = 'PostgreSQL unavailable. Using SQLite fallback database';
                
                // Check if SQLite needs migration
                try {
                    $tableCount = DB::connection('sqlite')
                        ->select("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table'")[0]->count;
                    
                    if ($tableCount < 10) {
                        $result['message'] .= '. Database needs migration - run: php artisan migrate --seed';
                    }
                } catch (Exception $e) {
                    // Ignore
                }
            } else {
                $result['message'] = 'Failed to configure any database connection';
            }
        }
        
        return $result;
    }
    
    /**
     * Get current database status
     */
    public static function getDatabaseStatus(): array
    {
        $status = [
            'current_connection' => config('database.default'),
            'postgresql' => [
                'configured' => env('DB_CONNECTION') === 'pgsql',
                'connected' => false,
                'host' => env('DB_HOST', '157.10.73.52'),
                'database' => env('DB_DATABASE', 'tarl_prathom')
            ],
            'sqlite' => [
                'configured' => env('DB_CONNECTION') === 'sqlite',
                'connected' => false,
                'file_exists' => file_exists(database_path('database.sqlite'))
            ]
        ];
        
        // Test PostgreSQL
        $status['postgresql']['connected'] = self::testPostgreSQL();
        
        // Test SQLite
        try {
            if ($status['sqlite']['file_exists']) {
                DB::connection('sqlite')->getPdo();
                $status['sqlite']['connected'] = true;
            }
        } catch (Exception $e) {
            // Not connected
        }
        
        return $status;
    }
}