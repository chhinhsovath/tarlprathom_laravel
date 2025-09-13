<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherProfileController extends Controller
{
    /**
     * Show the teacher profile setup form
     */
    public function setup()
    {
        $user = auth()->user();
        
        // If profile is already complete, redirect to dashboard
        if ($user->school_id && $user->assigned_subject && $user->holding_classes) {
            return redirect()->route('dashboard');
        }
        
        // Get all schools for dropdown
        $schools = School::orderBy('name')->get();
        
        return view('teacher.profile-setup', compact('user', 'schools'));
    }
    
    /**
     * Update teacher profile with school and subject
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'assigned_subject' => 'required|in:khmer,math,both',
            'holding_classes' => 'required|in:grade_4,grade_5,both',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user = auth()->user();
        
        $user->update([
            'school_id' => $request->school_id,
            'pilot_school_id' => $request->school_id, // Set both for compatibility
            'assigned_subject' => $request->assigned_subject,
            'holding_classes' => $request->holding_classes,
            'phone' => $request->phone,
        ]);
        
        // Get school details for location
        $school = School::find($request->school_id);
        if ($school) {
            $user->update([
                'province' => $school->province,
                'district' => $school->district,
                'commune' => $school->commune,
                'village' => $school->village,
            ]);
        }
        
        return redirect()->route('teacher.students.manage')
            ->with('success', 'Profile updated successfully! Now you can add students to your class.');
    }
    
    /**
     * Show student management page for teachers
     */
    public function manageStudents()
    {
        $user = auth()->user();
        
        // Ensure teacher has completed profile
        if (!$user->school_id || !$user->assigned_subject || !$user->holding_classes) {
            return redirect()->route('teacher.profile.setup')
                ->with('warning', 'Please complete your profile first.');
        }
        
        // Get grades this teacher handles
        $grades = [];
        if ($user->holding_classes === 'grade_4') {
            $grades = [4];
        } elseif ($user->holding_classes === 'grade_5') {
            $grades = [5];
        } else {
            $grades = [4, 5];
        }
        
        // Get students for this teacher's school and grades
        $students = Student::where('school_id', $user->school_id)
            ->whereIn('grade', $grades)
            ->orderBy('grade')
            ->orderBy('name')
            ->get();
        
        return view('teacher.manage-students', compact('user', 'students', 'grades'));
    }
    
    /**
     * Quick add student for teachers
     */
    public function quickAddStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|in:4,5',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:8|max:18',
        ]);
        
        $user = auth()->user();
        
        // Check if teacher can add students for this grade
        $allowedGrades = [];
        if ($user->holding_classes === 'grade_4') {
            $allowedGrades = [4];
        } elseif ($user->holding_classes === 'grade_5') {
            $allowedGrades = [5];
        } else {
            $allowedGrades = [4, 5];
        }
        
        if (!in_array($request->grade, $allowedGrades)) {
            return back()->withErrors(['grade' => 'You cannot add students for this grade.']);
        }
        
        $student = Student::create([
            'name' => $request->name,
            'school_id' => $user->school_id,
            'grade' => $request->grade,
            'gender' => $request->gender,
            'age' => $request->age,
            'added_by' => $user->id,
        ]);
        
        return back()->with('success', 'Student added successfully!');
    }
    
    /**
     * Bulk add students
     */
    public function bulkAddStudents(Request $request)
    {
        $request->validate([
            'grade' => 'required|in:4,5',
            'students' => 'required|string',
        ]);
        
        $user = auth()->user();
        
        // Parse student data (expecting format: "Name, Gender(M/F), Age" per line)
        $lines = explode("\n", $request->students);
        $added = 0;
        $errors = [];
        
        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $parts = array_map('trim', explode(',', $line));
            if (count($parts) !== 3) {
                $errors[] = "Line " . ($index + 1) . ": Invalid format";
                continue;
            }
            
            [$name, $gender, $age] = $parts;
            
            // Convert gender format
            $gender = strtoupper($gender) === 'M' ? 'male' : 'female';
            
            try {
                Student::create([
                    'name' => $name,
                    'school_id' => $user->school_id,
                    'grade' => $request->grade,
                    'gender' => $gender,
                    'age' => (int)$age,
                    'added_by' => $user->id,
                ]);
                $added++;
            } catch (\Exception $e) {
                $errors[] = "Line " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        $message = "$added students added successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
            return back()->with('warning', $message);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Show teacher dashboard with guided workflow
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Check profile completion
        $profileComplete = $user->school_id && $user->assigned_subject && $user->holding_classes;
        
        // Get student count
        $studentCount = 0;
        if ($profileComplete) {
            $grades = [];
            if ($user->holding_classes === 'grade_4') {
                $grades = [4];
            } elseif ($user->holding_classes === 'grade_5') {
                $grades = [5];
            } else {
                $grades = [4, 5];
            }
            
            $studentCount = Student::where('school_id', $user->school_id)
                ->whereIn('grade', $grades)
                ->count();
        }
        
        // Get assessment statistics
        $assessmentStats = DB::table('assessments')
            ->select('assessment_type', 'status', DB::raw('count(*) as count'))
            ->join('students', 'assessments.student_id', '=', 'students.id')
            ->where('students.school_id', $user->school_id)
            ->groupBy('assessment_type', 'status')
            ->get();
        
        // Process stats
        $stats = [
            'baseline' => ['completed' => 0, 'in_progress' => 0, 'not_started' => $studentCount],
            'midline' => ['completed' => 0, 'in_progress' => 0, 'not_started' => $studentCount],
            'endline' => ['completed' => 0, 'in_progress' => 0, 'not_started' => $studentCount],
        ];
        
        foreach ($assessmentStats as $stat) {
            if (isset($stats[$stat->assessment_type])) {
                $stats[$stat->assessment_type][$stat->status] = $stat->count;
                $stats[$stat->assessment_type]['not_started'] = $studentCount - 
                    $stats[$stat->assessment_type]['completed'] - 
                    $stats[$stat->assessment_type]['in_progress'];
            }
        }
        
        return view('teacher.dashboard', compact('user', 'profileComplete', 'studentCount', 'stats'));
    }
}