<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRoleRequest;
use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleBasedAccessControlController extends Controller
{
    use Sortable;

    /**
     * Display the role-based access control dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only admin can access RBAC management
        if (!$user->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        // Get statistics for each role with proper data isolation
        $statistics = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'roles' => [
                'admin' => User::where('role', 'admin')->count(),
                'coordinator' => User::where('role', 'coordinator')->count(),
                'mentor' => User::where('role', 'mentor')->count(),
                'teacher' => User::where('role', 'teacher')->count(),
                'viewer' => User::where('role', 'viewer')->count(),
            ],
            'data_access' => [
                'schools' => PilotSchool::count(),
                'students' => Student::count(),
                'assessments' => Assessment::count(),
                'mentoring_visits' => MentoringVisit::count(),
            ]
        ];

        // Get recent user activities grouped by role
        $recentActivities = User::with(['school', 'assignedPilotSchools'])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        // Get role permissions matrix
        $rolePermissions = $this->getRolePermissionsMatrix();

        return view('rbac.index', compact('statistics', 'recentActivities', 'rolePermissions'));
    }

    /**
     * Display users with role-based filtering
     */
    public function users(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        $query = User::with(['school', 'assignedPilotSchools']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('school', function ($sq) use ($search) {
                        $sq->where('school_name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        // Apply school filter
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->get('school_id'));
        }

        // Apply sorting
        $sortData = $this->applySorting(
            $query,
            $request,
            ['name', 'email', 'role', 'is_active', 'created_at'],
            'created_at',
            'desc'
        );

        $users = $query->paginate(20)->withQueryString();

        // Get filter options
        $schools = PilotSchool::orderBy('school_name')->get();
        $roles = ['admin', 'coordinator', 'mentor', 'teacher', 'viewer'];

        return view('rbac.users', compact('users', 'schools', 'roles') + $sortData);
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        $schools = PilotSchool::orderBy('school_name')->get();
        $roles = ['admin', 'coordinator', 'mentor', 'teacher', 'viewer'];

        return view('rbac.create', compact('schools', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(StoreUserRoleRequest $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $newUser = User::create($validated);

        // If the user is a mentor, assign schools
        if ($newUser->role === 'mentor' && $request->has('assigned_schools')) {
            $newUser->assignedPilotSchools()->sync($request->get('assigned_schools'));
        }

        return redirect()
            ->route('rbac.users')
            ->with('success', __('rbac.User created successfully with :role role.', ['role' => $validated['role']]));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        // Load relationships
        $user->load(['school', 'assignedPilotSchools', 'students', 'mentoringVisitsAsMentor', 'mentoringVisitsAsTeacher']);

        // Get user's data access statistics
        $dataStats = $this->getUserDataStatistics($user);

        // Get user's activity log
        $activities = $this->getUserActivities($user);

        return view('rbac.show', compact('user', 'dataStats', 'activities'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        $schools = PilotSchool::orderBy('school_name')->get();
        $roles = ['admin', 'coordinator', 'mentor', 'teacher', 'viewer'];
        
        // Get currently assigned schools for mentors
        $assignedSchools = $user->role === 'mentor' 
            ? $user->assignedPilotSchools()->pluck('pilot_schools.id')->toArray()
            : [];

        return view('rbac.edit', compact('user', 'schools', 'roles', 'assignedSchools'));
    }

    /**
     * Update the specified user
     */
    public function update(StoreUserRoleRequest $request, User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        $validated = $request->validated();

        // Handle password update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Update mentor school assignments
        if ($user->role === 'mentor' && $request->has('assigned_schools')) {
            $user->assignedPilotSchools()->sync($request->get('assigned_schools'));
        } elseif ($user->role !== 'mentor') {
            // Remove school assignments if user is no longer a mentor
            $user->assignedPilotSchools()->sync([]);
        }

        return redirect()
            ->route('rbac.show', $user)
            ->with('success', __('rbac.User updated successfully.'));
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        // Prevent deleting the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()
                ->route('rbac.users')
                ->with('error', __('rbac.Cannot delete the last admin user.'));
        }

        // Soft delete by setting inactive
        $user->update(['is_active' => false]);

        return redirect()
            ->route('rbac.users')
            ->with('success', __('rbac.User deactivated successfully.'));
    }

    /**
     * Toggle user activation status
     */
    public function toggleStatus(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? __('rbac.activated') : __('rbac.deactivated');
        
        return redirect()
            ->back()
            ->with('success', __('rbac.User :status successfully.', ['status' => $status]));
    }

    /**
     * Display data access matrix for all roles
     */
    public function dataAccess(Request $request)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, __('rbac.Access denied. Admin privileges required.'));
        }

        // Get data access statistics by role
        $dataAccessMatrix = $this->getDataAccessMatrix();

        // Get sample users for each role to demonstrate data isolation
        $sampleUsers = [
            'admin' => User::where('role', 'admin')->with(['school', 'assignedPilotSchools'])->first(),
            'coordinator' => User::where('role', 'coordinator')->with(['school', 'assignedPilotSchools'])->first(),
            'mentor' => User::where('role', 'mentor')->with(['school', 'assignedPilotSchools'])->first(),
            'teacher' => User::where('role', 'teacher')->with(['school', 'assignedPilotSchools'])->first(),
            'viewer' => User::where('role', 'viewer')->with(['school', 'assignedPilotSchools'])->first(),
        ];

        return view('rbac.data-access', compact('dataAccessMatrix', 'sampleUsers'));
    }

    /**
     * Get role permissions matrix
     */
    private function getRolePermissionsMatrix()
    {
        return [
            'admin' => [
                'users' => ['create', 'read', 'update', 'delete'],
                'schools' => ['create', 'read', 'update', 'delete'],
                'students' => ['create', 'read', 'update', 'delete'],
                'assessments' => ['create', 'read', 'update', 'delete'],
                'mentoring_visits' => ['create', 'read', 'update', 'delete'],
                'reports' => ['view_all'],
                'rbac' => ['manage'],
            ],
            'coordinator' => [
                'users' => ['read'],
                'schools' => ['read', 'update'],
                'students' => ['create', 'read', 'update'],
                'assessments' => ['create', 'read', 'update'],
                'mentoring_visits' => ['read'],
                'reports' => ['view_all'],
                'rbac' => [],
            ],
            'mentor' => [
                'users' => ['read_assigned'],
                'schools' => ['read_assigned'],
                'students' => ['read_assigned', 'update_assigned'],
                'assessments' => ['read_assigned'],
                'mentoring_visits' => ['create', 'read_assigned', 'update_own'],
                'reports' => ['view_assigned'],
                'rbac' => [],
            ],
            'teacher' => [
                'users' => ['read_own'],
                'schools' => ['read_own'],
                'students' => ['read_own', 'update_own'],
                'assessments' => ['create_own', 'read_own'],
                'mentoring_visits' => ['read_own'],
                'reports' => ['view_own'],
                'rbac' => [],
            ],
            'viewer' => [
                'users' => [],
                'schools' => ['read'],
                'students' => ['read'],
                'assessments' => ['read'],
                'mentoring_visits' => ['read'],
                'reports' => ['view'],
                'rbac' => [],
            ],
        ];
    }

    /**
     * Get data access matrix showing what each role can access
     */
    private function getDataAccessMatrix()
    {
        $matrix = [];
        
        $roles = ['admin', 'coordinator', 'mentor', 'teacher', 'viewer'];
        
        foreach ($roles as $role) {
            $sampleUser = User::where('role', $role)->first();
            
            if ($sampleUser) {
                $matrix[$role] = [
                    'schools_count' => count($sampleUser->getAccessibleSchoolIds()),
                    'students_count' => $sampleUser->getAccessibleStudents()->count(),
                    'teachers_count' => $role === 'mentor' ? $sampleUser->getAccessibleTeachers()->count() : 0,
                    'can_view_all_schools' => $sampleUser->isAdmin() || $sampleUser->isCoordinator(),
                    'data_isolation_level' => $this->getDataIsolationLevel($role),
                ];
            }
        }
        
        return $matrix;
    }

    /**
     * Get data isolation level description for a role
     */
    private function getDataIsolationLevel($role)
    {
        $levels = [
            'admin' => __('rbac.Full access to all data across all schools and users'),
            'coordinator' => __('rbac.Access to all schools and students, limited user management'),
            'mentor' => __('rbac.Access only to assigned schools, students, and teachers'),
            'teacher' => __('rbac.Access only to own school and assigned students'),
            'viewer' => __('rbac.Read-only access to basic data, no modifications allowed'),
        ];

        return $levels[$role] ?? __('rbac.Unknown role');
    }

    /**
     * Get user's data access statistics
     */
    private function getUserDataStatistics(User $user)
    {
        return [
            'accessible_schools' => count($user->getAccessibleSchoolIds()),
            'accessible_students' => $user->getAccessibleStudents()->count(),
            'own_students' => $user->students()->count(),
            'mentoring_visits_given' => $user->mentoringVisitsAsMentor()->count(),
            'mentoring_visits_received' => $user->mentoringVisitsAsTeacher()->count(),
            'assessments_conducted' => $user->assessments()->count(),
        ];
    }

    /**
     * Get user activity log (simplified version)
     */
    private function getUserActivities(User $user)
    {
        $activities = collect();

        // Recent mentoring visits
        $recentMentoring = $user->mentoringVisitsAsMentor()
            ->with(['teacher', 'school'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentMentoring as $visit) {
            $activities->push([
                'type' => 'mentoring_visit',
                'description' => __('rbac.Conducted mentoring visit at :school', ['school' => $visit->school->school_name ?? 'N/A']),
                'date' => $visit->created_at,
                'icon' => 'users'
            ]);
        }

        // Recent assessments
        $recentAssessments = $user->assessments()
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentAssessments as $assessment) {
            $activities->push([
                'type' => 'assessment',
                'description' => __('rbac.Conducted assessment for :student', ['student' => $assessment->student->name ?? 'N/A']),
                'date' => $assessment->created_at,
                'icon' => 'clipboard'
            ]);
        }

        return $activities->sortByDesc('date')->take(10);
    }
}