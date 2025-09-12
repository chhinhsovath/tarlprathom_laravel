<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddUsernameToTeachers extends Command
{
    protected $signature = 'teachers:add-usernames';
    protected $description = 'Add usernames to existing teacher accounts';

    public function handle()
    {
        $this->info('Adding usernames to existing teacher accounts...');
        
        // Get all teacher users without usernames
        $teachers = User::where('role', 'teacher')
            ->whereNull('username')
            ->get();
        
        $this->info("Found {$teachers->count()} teachers without usernames");
        
        $progressBar = $this->output->createProgressBar($teachers->count());
        
        foreach ($teachers as $teacher) {
            $username = $this->generateUsernameFromEmail($teacher->email);
            
            // Check if username already exists
            $existing = User::where('username', $username)->first();
            
            if (!$existing) {
                $result = DB::table('users')->where('id', $teacher->id)->update(['username' => $username]);
                if ($result) {
                    $this->line("\n✅ Updated {$teacher->name}: {$username}");
                } else {
                    $this->error("\n❌ Failed to update {$teacher->name}");
                }
            } else {
                // If username exists, add a number suffix
                $counter = 1;
                $originalUsername = $username;
                while (User::where('username', $username)->exists()) {
                    $username = $originalUsername . $counter;
                    $counter++;
                }
                $result = DB::table('users')->where('id', $teacher->id)->update(['username' => $username]);
                if ($result) {
                    $this->line("\n✅ Updated {$teacher->name}: {$username} (with suffix)");
                } else {
                    $this->error("\n❌ Failed to update {$teacher->name}");
                }
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Display sample credentials
        $this->info('Sample short login credentials:');
        $this->info('================================');
        
        $sampleTeachers = User::where('role', 'teacher')
            ->whereNotNull('username')
            ->limit(5)
            ->get();
            
        foreach ($sampleTeachers as $teacher) {
            $this->line("Name: {$teacher->name}");
            $this->line("  Username: {$teacher->username}");
            $this->line("  Email: {$teacher->email}");
            $this->line("  Password: admin123");
            $this->line("  Province: {$teacher->province}");
            $this->newLine();
        }
        
        $this->info('✅ All teacher accounts now have usernames!');
        $this->info('Teachers can login with either:');
        $this->info('- Short username: koe.kimsou.bat');
        $this->info('- Full email: koe.kimsou.bat@teacher.tarl.edu.kh');
        
        return 0;
    }
    
    private function generateUsernameFromEmail($email)
    {
        // Extract username part from email (before @)
        $parts = explode('@', $email);
        return $parts[0];
    }
}