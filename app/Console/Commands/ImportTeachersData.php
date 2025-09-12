<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PilotSchool;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ImportTeachersData extends Command
{
    protected $signature = 'import:teachers {file : Path to CSV file}';
    protected $description = 'Import teachers, mentors, and clusters from CSV file';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Starting teacher data import...');
        
        // Read CSV file
        $data = $this->readCSV($filePath);
        
        if (empty($data)) {
            $this->error('No data found in file');
            return 1;
        }

        DB::beginTransaction();
        
        try {
            $stats = [
                'teachers' => 0,
                'schools' => 0,
                'clusters' => 0,
                'mentors' => 0,
            ];

            // Process each province separately
            $provinces = $this->groupByProvince($data);
            
            foreach ($provinces as $province => $teachers) {
                $this->info("\nProcessing {$province}...");
                
                foreach ($teachers as $row) {
                    // Import cluster
                    $clusterId = $this->importCluster($row['cluster_km'], $row['cluster_en'], $province);
                    if ($clusterId) $stats['clusters']++;
                    
                    // Import mentor
                    $mentorId = $this->importMentor($row['mentor_km'], $row['mentor_en'], $province);
                    if ($mentorId) $stats['mentors']++;
                    
                    // Import/update school
                    $schoolId = $this->importSchool($row['school_km'], $row['school_en'], $province, $clusterId);
                    if ($schoolId) $stats['schools']++;
                    
                    // Import teacher
                    $teacherId = $this->importTeacher($row, $schoolId, $clusterId, $mentorId, $province);
                    if ($teacherId) {
                        $stats['teachers']++;
                        $this->line("  ✓ Imported teacher: {$row['teacher_en']}");
                    }
                }
            }
            
            DB::commit();
            
            $this->info("\n✅ Import completed successfully!");
            $this->table(
                ['Entity', 'Count'],
                [
                    ['Teachers', $stats['teachers']],
                    ['Schools', $stats['schools']],
                    ['Clusters', $stats['clusters']],
                    ['Mentors', $stats['mentors']],
                ]
            );
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            $this->error("Line: " . $e->getLine());
            $this->error("File: " . $e->getFile());
            return 1;
        }
    }
    
    private function readCSV($filePath)
    {
        $data = [];
        $handle = fopen($filePath, 'r');
        
        // Skip header row
        $header = fgetcsv($handle);
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 8) continue; // Skip incomplete rows
            
            $data[] = [
                'no' => $row[0] ?? '',
                'teacher_km' => $row[1] ?? '',
                'teacher_en' => $row[2] ?? '',
                'school_km' => $row[3] ?? '',
                'school_en' => $row[4] ?? '',
                'subject' => $row[5] ?? '',
                'cluster_km' => $this->extractClusterKhmer($row[6] ?? ''),
                'cluster_en' => $this->extractClusterEnglish($row[6] ?? ''),
                'mentor_km' => $row[7] ?? '',
                'mentor_en' => $row[8] ?? '',
            ];
        }
        
        fclose($handle);
        return $data;
    }
    
    private function groupByProvince($data)
    {
        $provinces = [];
        $currentProvince = null;
        
        foreach ($data as $row) {
            // Check if this is a province header
            if (empty($row['teacher_km']) && !empty($row['no'])) {
                $currentProvince = trim($row['no']);
                continue;
            }
            
            if ($currentProvince && !empty($row['teacher_km'])) {
                $provinces[$currentProvince][] = $row;
            }
        }
        
        return $provinces;
    }
    
    private function extractClusterKhmer($clusterString)
    {
        // Extract Khmer part from format: "Battambang (Attached school)" or "Badak (កម្រងបាដាក)"
        if (preg_match('/\((.*?)\)/', $clusterString, $matches)) {
            return $matches[1];
        }
        return $clusterString;
    }
    
    private function extractClusterEnglish($clusterString)
    {
        // Extract English part
        $english = preg_replace('/\(.*?\)/', '', $clusterString);
        return trim($english);
    }
    
    private function importCluster($nameKm, $nameEn, $province)
    {
        if (empty($nameEn)) return null;
        
        $cluster = DB::table('clusters')->updateOrInsert(
            [
                'name_en' => $nameEn,
                'province' => $province,
            ],
            [
                'name_km' => $nameKm,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        return DB::table('clusters')
            ->where('name_en', $nameEn)
            ->where('province', $province)
            ->value('id');
    }
    
    private function importMentor($nameKm, $nameEn, $province)
    {
        if (empty($nameEn)) return null;
        
        // Check if user exists for this mentor
        $email = $this->generateEmail($nameEn, 'mentor');
        
        $userId = DB::table('users')->where('email', $email)->value('id');
        
        if (!$userId) {
            // Create user account for mentor
            $userId = DB::table('users')->insertGetId([
                'name' => $nameEn,
                'email' => $email,
                'password' => Hash::make('admin123'),
                'role' => 'mentor',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        DB::table('mentors')->updateOrInsert(
            [
                'name_en' => $nameEn,
                'province' => $province,
            ],
            [
                'name_km' => $nameKm,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        return DB::table('mentors')
            ->where('name_en', $nameEn)
            ->where('province', $province)
            ->value('id');
    }
    
    private function importSchool($nameKm, $nameEn, $province, $clusterId)
    {
        if (empty($nameEn)) return null;
        
        // Check if school already exists
        $school = PilotSchool::where('school_name', $nameEn)
            ->where('province', $province)
            ->first();
        
        if (!$school) {
            // Create new school
            $school = PilotSchool::create([
                'school_name' => $nameEn,
                'school_name_km' => $nameKm,
                'province' => $province,
                'district' => $province, // You may need to map this properly
                'cluster_id' => $clusterId,
                'school_code' => $this->generateSchoolCode($nameEn, $province),
                'is_active' => true,
            ]);
        } else {
            // Update existing school with cluster
            $school->update([
                'school_name_km' => $nameKm,
                'cluster_id' => $clusterId,
            ]);
        }
        
        return $school->id;
    }
    
    private function importTeacher($row, $schoolId, $clusterId, $mentorId, $province)
    {
        if (empty($row['teacher_en'])) return null;
        
        // Check if teacher exists
        $teacher = DB::table('teachers')
            ->where('name_en', $row['teacher_en'])
            ->where('school_id', $schoolId)
            ->first();
        
        if (!$teacher) {
            // First, create user account for teacher
            $email = $this->generateTeacherEmail($row['teacher_en'], $province);
            $username = $this->generateUsername($row['teacher_en'], $province);
            
            // Check if user already exists
            $userId = DB::table('users')->where('email', $email)->value('id');
            
            if (!$userId) {
                // Create user account with default password
                $userId = DB::table('users')->insertGetId([
                    'name' => $row['teacher_en'],
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('admin123'), // Default password
                    'role' => 'teacher',
                    'is_active' => true,
                    'province' => $province, // Add province to user
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->info("    Created user account: {$username} / {$email} (password: admin123)");
            }
            
            // Create teacher record linked to user
            $teacherId = DB::table('teachers')->insertGetId([
                'name_km' => $row['teacher_km'],
                'name_en' => $row['teacher_en'],
                'subject' => $row['subject'],
                'province' => $province,
                'school_id' => $schoolId,
                'cluster_id' => $clusterId,
                'mentor_id' => $mentorId,
                'user_id' => $userId, // Link to user account
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create teacher-school relationship
            DB::table('teacher_schools')->insert([
                'teacher_id' => $teacherId,
                'school_id' => $schoolId,
                'subject' => $row['subject'],
                'assigned_date' => now(),
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Link user to school (if user_schools table exists)
            if (Schema::hasTable('user_schools')) {
                DB::table('user_schools')->insert([
                    'user_id' => $userId,
                    'school_id' => $schoolId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            return $teacherId;
        } else {
            // Update existing teacher with user account if missing
            if (!$teacher->user_id) {
                $email = $this->generateTeacherEmail($row['teacher_en'], $province);
                $username = $this->generateUsername($row['teacher_en'], $province);
                $userId = DB::table('users')->where('email', $email)->value('id');
                
                if (!$userId) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $row['teacher_en'],
                        'email' => $email,
                        'username' => $username,
                        'password' => Hash::make('admin123'),
                        'role' => 'teacher',
                        'is_active' => true,
                        'province' => $province,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->info("    Created user account for existing teacher: {$username} / {$email}");
                }
                
                DB::table('teachers')
                    ->where('id', $teacher->id)
                    ->update(['user_id' => $userId]);
            }
        }
        
        return $teacher->id;
    }
    
    private function generateTeacherEmail($name, $province)
    {
        // Generate email format: firstname.lastname.province@teacher.tarl.edu.kh
        $email = strtolower(str_replace(' ', '.', $name));
        $email = preg_replace('/[^a-z0-9.]/', '', $email);
        $provinceCode = strtolower(substr(str_replace(' ', '', $province), 0, 3));
        return $email . '.' . $provinceCode . '@teacher.tarl.edu.kh';
    }
    
    private function generateUsername($name, $province)
    {
        // Remove title prefixes first
        $cleanName = $this->removeTitles($name);
        
        // Generate username format: firstname.lastname.province (without @domain)
        $username = strtolower(str_replace(' ', '.', $cleanName));
        $username = preg_replace('/[^a-z0-9.]/', '', $username);
        $provinceCode = strtolower(substr(str_replace(' ', '', $province), 0, 3));
        return $username . '.' . $provinceCode;
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
    
    private function generateEmail($name, $role)
    {
        $email = strtolower(str_replace(' ', '.', $name));
        $email = preg_replace('/[^a-z0-9.]/', '', $email);
        return $email . '@' . $role . '.tarl.edu.kh';
    }
    
    private function generateSchoolCode($schoolName, $province)
    {
        $prefix = strtoupper(substr($province, 0, 3));
        $suffix = strtoupper(substr(str_replace(' ', '', $schoolName), 0, 3));
        $random = rand(100, 999);
        return "{$prefix}_{$suffix}_{$random}";
    }
}