<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AssessmentManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'App\Http\Middleware\AdminMiddleware']);
    }

    public function index(Request $request)
    {
        $query = Assessment::with(['student.school', 'student.teacher'])
            ->join('students', 'assessments.student_id', '=', 'students.id')
            ->join('schools', 'students.school_id', '=', 'schools.id')
            ->join('users', 'students.teacher_id', '=', 'users.id')
            ->select('assessments.*');

        // Apply filters
        if ($request->filled('province')) {
            $query->where('schools.province', $request->province);
        }

        if ($request->filled('district')) {
            $query->where('schools.district', $request->district);
        }

        if ($request->filled('cluster')) {
            $query->where('schools.cluster', $request->cluster);
        }

        if ($request->filled('school_id')) {
            $query->where('schools.id', $request->school_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('students.teacher_id', $request->teacher_id);
        }

        if ($request->filled('cycle')) {
            $query->where('assessments.cycle', $request->cycle);
        }

        if ($request->filled('subject')) {
            $query->where('assessments.subject', $request->subject);
        }

        if ($request->filled('lock_status')) {
            // Check if is_locked column exists
            if (\Schema::hasColumn('assessments', 'is_locked')) {
                $query->where('assessments.is_locked', $request->lock_status === 'locked');
            }
        }

        $assessments = $query->orderBy('assessments.created_at', 'desc')
            ->paginate(50)
            ->appends($request->query());

        // Get filter options
        $provinces = School::distinct()->orderBy('province')->pluck('province');
        $districts = School::when($request->filled('province'), function ($q) use ($request) {
            $q->where('province', $request->province);
        })->distinct()->orderBy('district')->pluck('district');

        $clusters = School::when($request->filled('province'), function ($q) use ($request) {
            $q->where('province', $request->province);
        })->when($request->filled('district'), function ($q) use ($request) {
            $q->where('district', $request->district);
        })->distinct()->orderBy('cluster')->pluck('cluster');

        $schools = School::when($request->filled('province'), function ($q) use ($request) {
            $q->where('province', $request->province);
        })->when($request->filled('district'), function ($q) use ($request) {
            $q->where('district', $request->district);
        })->when($request->filled('cluster'), function ($q) use ($request) {
            $q->where('cluster', $request->cluster);
        })->orderBy('school_name')->get();

        $teachers = User::whereIn('role', ['teacher', 'mentor'])
            ->when($request->filled('school_id'), function ($q) use ($request) {
                $q->where('school_id', $request->school_id);
            })->orderBy('name')->get();

        $hasLockColumns = Schema::hasColumn('assessments', 'is_locked');

        return view('assessment-management.index', compact(
            'assessments',
            'provinces',
            'districts',
            'clusters',
            'schools',
            'teachers',
            'hasLockColumns'
        ));
    }

    public function mentoringVisits(Request $request)
    {
        $query = MentoringVisit::with(['mentor', 'school', 'teacher'])
            ->join('schools', 'mentoring_visits.school_id', '=', 'schools.id')
            ->select('mentoring_visits.*');

        // Apply filters
        if ($request->filled('province')) {
            $query->where('schools.province', $request->province);
        }

        if ($request->filled('district')) {
            $query->where('schools.district', $request->district);
        }

        if ($request->filled('cluster')) {
            $query->where('schools.cluster', $request->cluster);
        }

        if ($request->filled('school_id')) {
            $query->where('schools.id', $request->school_id);
        }

        if ($request->filled('mentor_id')) {
            $query->where('mentoring_visits.mentor_id', $request->mentor_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('mentoring_visits.teacher_id', $request->teacher_id);
        }

        if ($request->filled('lock_status')) {
            // Check if is_locked column exists
            if (\Schema::hasColumn('mentoring_visits', 'is_locked')) {
                $query->where('mentoring_visits.is_locked', $request->lock_status === 'locked');
            }
        }

        $mentoringVisits = $query->orderBy('mentoring_visits.visit_date', 'desc')
            ->paginate(50)
            ->appends($request->query());

        // Get filter options
        $provinces = School::distinct()->orderBy('province')->pluck('province');
        $districts = School::when($request->filled('province'), function ($q) use ($request) {
            $q->where('province', $request->province);
        })->distinct()->orderBy('district')->pluck('district');

        $clusters = School::when($request->filled('province'), function ($q) use ($request) {
            $q->where('province', $request->province);
        })->when($request->filled('district'), function ($q) use ($request) {
            $q->where('district', $request->district);
        })->distinct()->orderBy('cluster')->pluck('cluster');

        $schools = School::when($request->filled('province'), function ($q) use ($request) {
            $q->where('province', $request->province);
        })->when($request->filled('district'), function ($q) use ($request) {
            $q->where('district', $request->district);
        })->when($request->filled('cluster'), function ($q) use ($request) {
            $q->where('cluster', $request->cluster);
        })->orderBy('school_name')->get();

        $mentors = User::where('role', 'mentor')->orderBy('name')->get();
        $teachers = User::whereIn('role', ['teacher', 'mentor'])
            ->when($request->filled('school_id'), function ($q) use ($request) {
                $q->where('school_id', $request->school_id);
            })->orderBy('name')->get();

        $hasLockColumns = Schema::hasColumn('mentoring_visits', 'is_locked');

        return view('assessment-management.mentoring-visits', compact(
            'mentoringVisits',
            'provinces',
            'districts',
            'clusters',
            'schools',
            'mentors',
            'teachers',
            'hasLockColumns'
        ));
    }

    public function lockAssessment(Assessment $assessment)
    {
        if (! $assessment->is_locked) {
            $assessment->update([
                'is_locked' => true,
                'locked_by' => Auth::id(),
                'locked_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', __('Assessment locked successfully'));
    }

    public function unlockAssessment(Assessment $assessment)
    {
        if ($assessment->is_locked) {
            $assessment->update([
                'is_locked' => false,
                'locked_by' => null,
                'locked_at' => null,
            ]);
        }

        return redirect()->back()->with('success', __('Assessment unlocked successfully'));
    }

    public function lockMentoringVisit(MentoringVisit $mentoringVisit)
    {
        if (! $mentoringVisit->is_locked) {
            $mentoringVisit->update([
                'is_locked' => true,
                'locked_by' => Auth::id(),
                'locked_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', __('Mentoring visit locked successfully'));
    }

    public function unlockMentoringVisit(MentoringVisit $mentoringVisit)
    {
        if ($mentoringVisit->is_locked) {
            $mentoringVisit->update([
                'is_locked' => false,
                'locked_by' => null,
                'locked_at' => null,
            ]);
        }

        return redirect()->back()->with('success', __('Mentoring visit unlocked successfully'));
    }

    public function bulkLockAssessments(Request $request)
    {
        $request->validate([
            'assessment_ids' => 'required|array',
            'assessment_ids.*' => 'exists:assessments,id',
        ]);

        Assessment::whereIn('id', $request->assessment_ids)
            ->where('is_locked', false)
            ->update([
                'is_locked' => true,
                'locked_by' => Auth::id(),
                'locked_at' => now(),
            ]);

        return redirect()->back()->with('success', __('Selected assessments locked successfully'));
    }

    public function bulkUnlockAssessments(Request $request)
    {
        $request->validate([
            'assessment_ids' => 'required|array',
            'assessment_ids.*' => 'exists:assessments,id',
        ]);

        Assessment::whereIn('id', $request->assessment_ids)
            ->where('is_locked', true)
            ->update([
                'is_locked' => false,
                'locked_by' => null,
                'locked_at' => null,
            ]);

        return redirect()->back()->with('success', __('Selected assessments unlocked successfully'));
    }

    public function bulkLockMentoringVisits(Request $request)
    {
        $request->validate([
            'mentoring_visit_ids' => 'required|array',
            'mentoring_visit_ids.*' => 'exists:mentoring_visits,id',
        ]);

        MentoringVisit::whereIn('id', $request->mentoring_visit_ids)
            ->where('is_locked', false)
            ->update([
                'is_locked' => true,
                'locked_by' => Auth::id(),
                'locked_at' => now(),
            ]);

        return redirect()->back()->with('success', __('Selected mentoring visits locked successfully'));
    }

    public function bulkUnlockMentoringVisits(Request $request)
    {
        $request->validate([
            'mentoring_visit_ids' => 'required|array',
            'mentoring_visit_ids.*' => 'exists:mentoring_visits,id',
        ]);

        MentoringVisit::whereIn('id', $request->mentoring_visit_ids)
            ->where('is_locked', true)
            ->update([
                'is_locked' => false,
                'locked_by' => null,
                'locked_at' => null,
            ]);

        return redirect()->back()->with('success', __('Selected mentoring visits unlocked successfully'));
    }
}
