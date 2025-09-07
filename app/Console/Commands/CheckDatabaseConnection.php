<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\DatabaseHelper;

class CheckDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check {--auto-configure : Automatically configure fallback if primary fails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check database connections and optionally auto-configure fallback';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=====================================');
        $this->info('Database Connection Status Check');
        $this->info('=====================================');
        $this->newLine();
        
        // Get current status
        $status = DatabaseHelper::getDatabaseStatus();
        
        $this->info('Current Configuration:');
        $this->line('Default Connection: ' . $status['current_connection']);
        $this->newLine();
        
        // PostgreSQL Status
        $this->info('PostgreSQL Status:');
        $this->line('  Host: ' . $status['postgresql']['host']);
        $this->line('  Database: ' . $status['postgresql']['database']);
        $this->line('  Configured: ' . ($status['postgresql']['configured'] ? '✓ Yes' : '✗ No'));
        
        if ($status['postgresql']['connected']) {
            $this->info('  Connected: ✓ Yes');
        } else {
            $this->error('  Connected: ✗ No');
        }
        $this->newLine();
        
        // SQLite Status
        $this->info('SQLite Status:');
        $this->line('  File Exists: ' . ($status['sqlite']['file_exists'] ? '✓ Yes' : '✗ No'));
        $this->line('  Configured: ' . ($status['sqlite']['configured'] ? '✓ Yes' : '✗ No'));
        
        if ($status['sqlite']['connected']) {
            $this->info('  Connected: ✓ Yes');
        } else {
            $this->error('  Connected: ✗ No');
        }
        $this->newLine();
        
        // Auto-configure if requested
        if ($this->option('auto-configure')) {
            $this->info('Attempting auto-configuration...');
            $result = DatabaseHelper::autoConfigureDatabase();
            
            if ($result['success']) {
                $this->info('✓ ' . $result['message']);
                $this->info('Now using: ' . $result['connection']);
                
                if (str_contains($result['message'], 'needs migration')) {
                    $this->newLine();
                    $this->warn('Action Required:');
                    $this->line('Run the following command to set up the database:');
                    $this->info('  php artisan migrate --seed');
                }
            } else {
                $this->error('✗ ' . $result['message']);
            }
        } else {
            // Provide recommendations
            $this->info('Recommendations:');
            
            if (!$status['postgresql']['connected'] && !$status['sqlite']['connected']) {
                $this->warn('⚠ No database connection available!');
                $this->line('');
                $this->line('Options:');
                $this->line('1. Fix PostgreSQL connection:');
                $this->line('   - Check network/firewall settings');
                $this->line('   - Verify PostgreSQL server is running');
                $this->line('   - Update .env with correct credentials');
                $this->line('');
                $this->line('2. Use SQLite fallback:');
                $this->line('   Run: php artisan db:check --auto-configure');
                $this->line('');
            } elseif (!$status['postgresql']['connected'] && $status['postgresql']['configured']) {
                $this->warn('⚠ PostgreSQL is configured but not connecting');
                $this->line('Consider using SQLite fallback:');
                $this->line('  php artisan db:check --auto-configure');
            } elseif ($status['postgresql']['connected']) {
                $this->info('✓ Database connection is healthy');
            }
        }
        
        $this->newLine();
        $this->info('=====================================');
        $this->info('Check completed!');
        $this->info('=====================================');
        
        return Command::SUCCESS;
    }
}