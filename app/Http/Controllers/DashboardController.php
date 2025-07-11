<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\School;
use App\Models\Student;
use App\Models\MentoringVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get schools for mentors
        $schools = [];
        if ($user->role === 'mentor' || $user->role === 'admin') {
            $schools = School::orderBy('school_name')->get();
        }
        
        return view('dashboard', compact('schools'));
    }
    
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        $user = auth()->user();
        $stats = [];
        
        // Base query
        $studentQuery = Student::query();
        $assessmentQuery = Assessment::query();
        $mentoringQuery = MentoringVisit::query();
        
        // Filter by school for teachers
        if ($user->role === 'teacher') {
            $studentQuery->where('school_id', $user->school_id);
            $assessmentQuery->whereHas('student', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        }
        
        // Filter by selected school for mentors/admins
        if ($request->has('school_id') && $request->school_id) {
            $studentQuery->where('school_id', $request->school_id);
            $assessmentQuery->whereHas('student', function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
            $mentoringQuery->where('school_id', $request->school_id);
        }
        
        $stats['total_students'] = $studentQuery->count();
        $stats['total_assessments'] = $assessmentQuery->count();
        $stats['total_schools'] = School::count();
        $stats['total_mentoring_visits'] = $mentoringQuery->count();
        
        return response()->json($stats);
    }
    
    /**
     * Get overall assessment results data
     */
    public function getOverallResults(Request $request)
    {
        $user = auth()->user();
        $subject = $request->get('subject', 'khmer');
        
        // Define levels based on subject
        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];
        }
        $cycles = ['baseline', 'midline', 'endline'];
        
        // Build base query
        $query = Assessment::where('subject', $subject);
        
        // Filter by school for teachers
        if ($user->role === 'teacher') {
            $query->whereHas('student', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        }
        
        // Filter by selected school for mentors/admins
        if ($request->has('school_id') && $request->school_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }
        
        // Get data for each cycle
        $datasets = [];
        $colors = [
            'Beginner' => '#d32f2f',
            'Letter Reader' => '#f57c00', 
            'Word Level' => '#fbc02d',
            'Paragraph Reader' => '#388e3c',
            'Story Reader' => '#2e7d32',
            '1-Digit' => '#f57c00',
            '2-Digit' => '#fbc02d',
            'Subtraction' => '#388e3c',
            'Division' => '#2e7d32'
        ];
        
        foreach ($levels as $level) {
            $data = [];
            foreach ($cycles as $cycle) {
                $count = (clone $query)
                    ->where('cycle', $cycle)
                    ->where('level', $level)
                    ->count();
                $data[] = $count;
            }
            
            $datasets[] = [
                'label' => __($level),
                'data' => $data,
                'backgroundColor' => $colors[$level],
                'stack' => 'Stack 0'
            ];
        }
        
        // Get totals for table
        $totals = [];
        foreach ($cycles as $cycle) {
            $totals[$cycle] = (clone $query)->where('cycle', $cycle)->count();
        }
        
        return response()->json([
            'chartData' => [
                'labels' => [__('Baseline'), __('Midline'), __('Endline')],
                'datasets' => $datasets
            ],
            'totals' => $totals
        ]);
    }
    
    /**
     * Get results by school (for mentors/admins)
     */
    public function getResultsBySchool(Request $request)
    {
        $user = auth()->user();
        
        // Only mentors and admins can see this
        if (!in_array($user->role, ['mentor', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $subject = $request->get('subject', 'khmer');
        $cycle = $request->get('cycle', 'baseline');
        
        // Define levels based on subject
        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];
        }
        
        // Get schools
        $schools = School::orderBy('school_name')->get();
        $schoolNames = $schools->pluck('school_name')->toArray();
        
        // Prepare datasets
        $datasets = [];
        $colors = [
            'Beginner' => '#d32f2f',
            'Letter Reader' => '#f57c00', 
            'Word Level' => '#fbc02d',
            'Paragraph Reader' => '#388e3c',
            'Story Reader' => '#2e7d32',
            '1-Digit' => '#f57c00',
            '2-Digit' => '#fbc02d',
            'Subtraction' => '#388e3c',
            'Division' => '#2e7d32'
        ];
        
        foreach ($levels as $level) {
            $data = [];
            foreach ($schools as $school) {
                $count = Assessment::where('subject', $subject)
                    ->where('cycle', $cycle)
                    ->where('level', $level)
                    ->whereHas('student', function($q) use ($school) {
                        $q->where('school_id', $school->id);
                    })
                    ->count();
                $data[] = $count;
            }
            
            $datasets[] = [
                'label' => __($level),
                'data' => $data,
                'backgroundColor' => $colors[$level],
                'stack' => 'Stack 0'
            ];
        }
        
        return response()->json([
            'chartData' => [
                'labels' => $schoolNames,
                'datasets' => $datasets
            ]
        ]);
    }
}