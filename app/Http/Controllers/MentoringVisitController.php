<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMentoringVisitRequest;
use App\Models\MentoringVisit;
use App\Models\School;
use App\Models\User;
use App\Exports\MentoringVisitsExport;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MentoringVisitController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Check if user can view mentoring visits
        if (!in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        $query = MentoringVisit::with(['mentor', 'teacher', 'school']);

        // Filter based on user role
        if ($user->isTeacher()) {
            // Teachers can only see visits where they are the teacher
            $query->where('teacher_id', $user->id);
        } elseif ($user->isMentor()) {
            // Mentors can see their own visits
            $query->where('mentor_id', $user->id);
        }

        // Add search functionality
        if ($request->has('search') && $request->get('search') !== '') {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('observation', 'like', "%{$search}%")
                  ->orWhere('action_plan', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('mentor', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('school', function($sq) use ($search) {
                      $sq->where('school_name', 'like', "%{$search}%");
                  });
            });
        }

        // Add filters
        if ($request->has('school_id') && $request->get('school_id') !== '') {
            $query->where('school_id', $request->get('school_id'));
        }

        if ($request->has('mentor_id') && $request->get('mentor_id') !== '') {
            $query->where('mentor_id', $request->get('mentor_id'));
        }

        if ($request->has('teacher_id') && $request->get('teacher_id') !== '') {
            $query->where('teacher_id', $request->get('teacher_id'));
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('visit_date', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date')) {
            $query->where('visit_date', '<=', $request->get('to_date'));
        }

        $mentoringVisits = $query->orderBy('visit_date', 'desc')->paginate(20);

        return view('mentoring.index', compact('mentoringVisits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check authorization
        if (!in_array($request->user()->role, ['admin', 'mentor'])) {
            abort(403);
        }

        // Get schools for dropdown
        $schools = School::orderBy('school_name')->get();

        // Get teachers for dropdown
        $teachers = User::where('role', 'teacher')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Pre-select values if passed as parameters
        $selectedSchoolId = $request->get('school_id');
        $selectedTeacherId = $request->get('teacher_id');

        return view('mentoring.create', compact('schools', 'teachers', 'selectedSchoolId', 'selectedTeacherId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMentoringVisitRequest $request)
    {
        $validated = $request->validated();

        // Set the mentor_id
        if ($request->user()->isMentor()) {
            $validated['mentor_id'] = $request->user()->id;
        } else {
            // Admin can specify mentor_id if needed
            $validated['mentor_id'] = $validated['mentor_id'] ?? $request->user()->id;
        }

        // Verify the teacher belongs to the selected school
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->school_id !== $validated['school_id']) {
            return back()->withErrors(['teacher_id' => 'The selected teacher does not belong to the selected school.']);
        }

        // Handle photo upload if present
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('mentoring-visits', 'public');
            $validated['photo'] = $path;
        }

        $mentoringVisit = MentoringVisit::create($validated);

        return redirect()->route('mentoring.index')
            ->with('success', 'Mentoring visit recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MentoringVisit $mentoringVisit)
    {
        $user = auth()->user();
        
        // Check if user can view this mentoring visit
        if ($user->isAdmin() || $user->isViewer()) {
            // These roles can view all visits
        } elseif ($user->isMentor() && $mentoringVisit->mentor_id === $user->id) {
            // Mentors can view their own visits
        } elseif ($user->isTeacher() && $mentoringVisit->teacher_id === $user->id) {
            // Teachers can view visits where they are the teacher
        } else {
            abort(403);
        }

        $mentoringVisit->load(['mentor', 'teacher', 'school']);

        return view('mentoring.show', compact('mentoringVisit'));
    }

    /**
     * Export mentoring visits to Excel
     */
    public function export(Request $request)
    {
        $user = $request->user();
        
        // Check if user can view mentoring visits
        if (!in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        return Excel::download(new MentoringVisitsExport($request), 'mentoring_visits_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}