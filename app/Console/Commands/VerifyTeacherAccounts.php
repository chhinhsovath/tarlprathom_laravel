<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;
use App\Models\User;
use App\Models\PilotSchool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class VerifyTeacherAccounts extends Command
{
    protected $signature = 'verify:teachers {--fix : Fix missing accounts and relationships}';
    protected $description = 'Verify all teachers have user accounts and proper relationships';

    public function handle()
    {
        $this->info('Verifying Teacher Accounts and Relationships...');
        $this->info('================================================');
        
        $fix = $this->option('fix');
        $issues = [];
        
        // Get all teachers
        $teachers = Teacher::with(['user', 'school', 'cluster', 'mentor'])->get();
        
        $this->info("\nTotal Teachers: " . $teachers->count());
        
        // Check each teacher
        $progressBar = $this->output->createProgressBar($teachers->count());
        
        foreach ($teachers as $teacher) {
            $teacherIssues = [];
            
            // Check 1: User Account
            if (!$teacher->user_id || !$teacher->user) {
                $teacherIssues[] = 'No user account';
                
                if ($fix) {
                    $this->createUserAccount($teacher);
                }
            } else {
                // Verify user details
                $user = $teacher->user;
                
                if ($user->role !== 'teacher') {
                    $teacherIssues[] = "Wrong role: {$user->role}";
                    if ($fix) {
                        $user->update(['role' => 'teacher']);
                    }
                }
                
                if ($user->province !== $teacher->province) {
                    $teacherIssues[] = "Province mismatch: User({$user->province}) vs Teacher({$teacher->province})";
                    if ($fix) {
                        $user->update(['province' => $teacher->province]);
                    }
                }
                
                if (!$user->is_active) {
                    $teacherIssues[] = "Account inactive";
                    if ($fix) {
                        $user->update(['is_active' => true]);
                    }
                }
            }
            
            // Check 2: School Relationship
            if (!$teacher->school_id || !$teacher->school) {
                $teacherIssues[] = 'No school assigned';
            } else {
                // Verify school is in correct province
                if ($teacher->school->province !== $teacher->province) {
                    $teacherIssues[] = "School province mismatch";
                }
                
                // Check teacher_schools pivot table
                $pivotExists = DB::table('teacher_schools')
                    ->where('teacher_id', $teacher->id)
                    ->where('school_id', $teacher->school_id)
                    ->exists();
                    
                if (!$pivotExists) {
                    $teacherIssues[] = 'Missing teacher_schools relationship';
                    if ($fix) {
                        DB::table('teacher_schools')->insert([
                            'teacher_id' => $teacher->id,
                            'school_id' => $teacher->school_id,
                            'subject' => $teacher->subject,
                            'assigned_date' => now(),
                            'is_primary' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            // Check 3: Cluster Relationship
            if (!$teacher->cluster_id || !$teacher->cluster) {
                $teacherIssues[] = 'No cluster assigned';
            }
            
            // Check 4: Mentor Relationship
            if (!$teacher->mentor_id || !$teacher->mentor) {
                $teacherIssues[] = 'No mentor assigned';
            }
            
            if (!empty($teacherIssues)) {
                $issues[$teacher->name_en] = $teacherIssues;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Display Results
        if (empty($issues)) {
            $this->info('✅ All teachers have proper accounts and relationships!');
        } else {
            $this->warn('Issues found with ' . count($issues) . ' teachers:');
            $this->newLine();
            
            foreach ($issues as $teacherName => $teacherIssues) {
                $this->error("❌ $teacherName:");
                foreach ($teacherIssues as $issue) {
                    $this->line("   - $issue");
                }
            }
            
            if (!$fix) {
                $this->newLine();
                $this->info('Run with --fix option to automatically fix these issues:');
                $this->info('php artisan verify:teachers --fix');
            }
        }
        
        // Summary Statistics
        $this->newLine();
        $this->info('Summary Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Teachers', Teacher::count()],
                ['Teachers with User Accounts', Teacher::whereNotNull('user_id')->count()],
                ['Active Teacher Accounts', User::where('role', 'teacher')->where('is_active', true)->count()],
                ['Teachers in Battambang', Teacher::where('province', 'Battambang')->count()],
                ['Teachers in Kampong Cham', Teacher::where('province', 'Kampong Cham')->count()],
                ['Khmer Teachers', Teacher::where('subject', 'Khmer')->count()],
                ['Math Teachers', Teacher::where('subject', 'Maths')->count()],
            ]
        );
        
        // Display Sample Login Credentials
        $this->newLine();
        $this->info('Sample Teacher Login Credentials:');
        $this->info('================================');
        
        $sampleTeachers = Teacher::with('user')->limit(5)->get();
        foreach ($sampleTeachers as $teacher) {
            if ($teacher->user) {
                $this->line("Teacher: {$teacher->name_en}");
                $this->line("  Email: {$teacher->user->email}");
                $this->line("  Password: admin123");
                $this->line("  Province: {$teacher->province}");
                $this->line("  School: " . ($teacher->school ? $teacher->school->school_name : 'N/A'));
                $this->newLine();
            }
        }
        
        return 0;
    }
    
    private function createUserAccount($teacher)
    {
        $email = $this->generateTeacherEmail($teacher->name_en, $teacher->province);
        
        // Check if user already exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $user = User::create([
                'name' => $teacher->name_en,
                'email' => $email,
                'password' => Hash::make('admin123'),
                'role' => 'teacher',
                'is_active' => true,
                'province' => $teacher->province,
            ]);
            
            $this->info("Created user account for {$teacher->name_en}: {$email}");
        }
        
        // Update teacher with user_id
        $teacher->update(['user_id' => $user->id]);
        
        // Link user to school if table exists
        if (Schema::hasTable('user_schools') && $teacher->school_id) {
            DB::table('user_schools')->insertOrIgnore([
                'user_id' => $user->id,
                'school_id' => $teacher->school_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function generateTeacherEmail($name, $province)
    {
        $email = strtolower(str_replace(' ', '.', $name));
        $email = preg_replace('/[^a-z0-9.]/', '', $email);
        $provinceCode = strtolower(substr(str_replace(' ', '', $province), 0, 3));
        return $email . '.' . $provinceCode . '@teacher.tarl.edu.kh';
    }
}