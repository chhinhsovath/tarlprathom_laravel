<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanTeacherUsernames extends Command
{
    protected $signature = 'teachers:clean-usernames';
    protected $description = 'Clean up teacher usernames by removing title prefixes (Mr., Ms., etc.)';

    public function handle()
    {
        $this->info('Cleaning up teacher usernames...');
        
        // Get all teacher users
        $teachers = User::where('role', 'teacher')->get();
        
        $this->info("Processing {$teachers->count()} teacher accounts");
        
        $progressBar = $this->output->createProgressBar($teachers->count());
        $cleaned = 0;
        
        foreach ($teachers as $teacher) {
            $originalUsername = $teacher->username;
            $cleanUsername = $this->cleanUsername($teacher->name, $teacher->province);
            
            if ($originalUsername !== $cleanUsername) {
                // Check if clean username already exists
                $existing = User::where('username', $cleanUsername)->where('id', '!=', $teacher->id)->first();
                
                if (!$existing) {
                    DB::table('users')->where('id', $teacher->id)->update(['username' => $cleanUsername]);
                    $this->line("\n✅ {$teacher->name}: {$originalUsername} → {$cleanUsername}");
                    $cleaned++;
                } else {
                    // Add number suffix if clean username exists
                    $counter = 1;
                    $baseUsername = $cleanUsername;
                    while (User::where('username', $cleanUsername)->where('id', '!=', $teacher->id)->exists()) {
                        $cleanUsername = $baseUsername . $counter;
                        $counter++;
                    }
                    DB::table('users')->where('id', $teacher->id)->update(['username' => $cleanUsername]);
                    $this->line("\n✅ {$teacher->name}: {$originalUsername} → {$cleanUsername} (with suffix)");
                    $cleaned++;
                }
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Cleaned {$cleaned} usernames");
        
        // Show examples
        $this->info('Examples of cleaned usernames:');
        $this->info('================================');
        
        $examples = User::where('role', 'teacher')
            ->whereIn('province', ['Battambang', 'Kampong Cham'])
            ->limit(10)
            ->get();
            
        foreach ($examples as $teacher) {
            $this->line("Name: {$teacher->name} → Username: {$teacher->username}");
        }
        
        return 0;
    }
    
    private function cleanUsername($name, $province)
    {
        // Remove title prefixes
        $cleanName = $this->removeTitles($name);
        
        // Generate username from clean name
        $username = strtolower(str_replace(' ', '.', $cleanName));
        $username = preg_replace('/[^a-z0-9.]/', '', $username);
        
        // Add province code
        if ($province) {
            $provinceCode = strtolower(substr(str_replace(' ', '', $province), 0, 3));
            $username = $username . '.' . $provinceCode;
        }
        
        return $username;
    }
    
    private function removeTitles($name)
    {
        $titles = [
            'Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Prof.',
            'Mr', 'Ms', 'Mrs', 'Dr', 'Prof',
            'Mss.', 'Miss.', 'Miss'
        ];
        
        // Remove titles from beginning
        foreach ($titles as $title) {
            if (stripos($name, $title . ' ') === 0) {
                $name = trim(substr($name, strlen($title)));
            }
        }
        
        // Clean up extra spaces
        $name = preg_replace('/\s+/', ' ', trim($name));
        
        return $name;
    }
}