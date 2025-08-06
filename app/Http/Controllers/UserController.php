<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $query = User::with('school');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filter by school
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->get('school_id'));
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Add sorting
        $sortField = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        // Validate sort field
        $allowedSorts = ['name', 'email', 'role', 'created_at'];
        if (! in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        // Validate sort order
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $users = $query->orderBy($sortField, $sortOrder)->paginate(20)->withQueryString();
        $schools = School::orderBy('name')->get();

        return view('users.index', compact('users', 'schools', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $schools = School::orderBy('name')->get();

        return view('users.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,mentor,teacher,viewer,coordinator'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'province' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'commune' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'sex' => ['nullable', 'in:male,female'],
            'holding_classes' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $path = $photo->store('users/photos', 'public');
            $validated['profile_photo'] = $path;
        }

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $user->load('school');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $schools = School::orderBy('name')->get();

        return view('users.edit', compact('user', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,mentor,teacher,viewer,coordinator'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'province' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'commune' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'sex' => ['nullable', 'in:male,female'],
            'holding_classes' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $photo = $request->file('profile_photo');
            $path = $photo->store('users/photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', __('You cannot delete your own account.'));
        }

        // Delete photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('User deleted successfully.'));
    }

    /**
     * Show the bulk import form.
     */
    public function bulkImportForm()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        return view('users.bulk-import');
    }

    /**
     * Process bulk import of users.
     */
    public function bulkImport(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $request->validate([
            'users' => 'required|array',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|max:255',
            'users.*.password' => 'required|string|min:8',
            'users.*.role' => 'required|in:admin,teacher,mentor,viewer,coordinator',
            'users.*.school_id' => 'nullable|exists:schools,id',
            'users.*.phone_number' => 'nullable|string|max:20',
        ]);

        $imported = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->users as $index => $userData) {
            try {
                // Check if email already exists
                if (User::where('email', $userData['email'])->exists()) {
                    $failed++;
                    $errors[] = 'Row '.($index + 1).": Email {$userData['email']} already exists";

                    continue;
                }

                // Validate school requirement for non-admin roles
                if ($userData['role'] !== 'admin' && empty($userData['school_id'])) {
                    $failed++;
                    $errors[] = 'Row '.($index + 1).": School is required for {$userData['role']} role";

                    continue;
                }

                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                    'school_id' => $userData['school_id'] ?? null,
                    'phone' => $userData['phone_number'] ?? null,
                    'is_active' => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = 'Row '.($index + 1).': '.$e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} users.";
        if ($failed > 0) {
            $message .= " {$failed} users failed to import.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'failed' => $failed,
            'errors' => $errors,
        ]);
    }

    /**
     * API endpoint to get users by role
     */
    public function apiGetUsersByRole(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $role = $request->get('role', 'teacher');
        $users = User::where('role', $role)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }

    /**
     * Show enhanced bulk import form.
     */
    public function bulkImportEnhancedForm()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        return view('users.bulk-import-enhanced');
    }

    /**
     * Process enhanced bulk import with all fields.
     */
    public function bulkImportEnhanced(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'users' => 'required|array',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|max:255',
            'users.*.password' => 'required|string|min:8',
            'users.*.role' => 'required|in:admin,teacher,mentor,viewer,coordinator',
            'users.*.school_id' => 'nullable|exists:schools,id',
            'users.*.sex' => 'nullable|in:male,female',
            'users.*.phone' => 'nullable|string|max:255',
            'users.*.holding_classes' => 'nullable|string|max:255',
            'users.*.is_active' => 'nullable|boolean',
        ]);

        $imported = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->users as $index => $userData) {
            try {
                // Check if email already exists
                if (User::where('email', $userData['email'])->exists()) {
                    $failed++;
                    $errors[] = 'Row '.($index + 1).": Email {$userData['email']} already exists";

                    continue;
                }

                // Validate school requirement for non-admin roles
                if (in_array($userData['role'], ['teacher', 'mentor']) && empty($userData['school_id'])) {
                    $failed++;
                    $errors[] = 'Row '.($index + 1).": School is required for {$userData['role']} role";

                    continue;
                }

                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                    'school_id' => $userData['school_id'] ?? null,
                    'sex' => $userData['sex'] ?? null,
                    'phone' => $userData['phone'] ?? null,
                    'holding_classes' => $userData['holding_classes'] ?? null,
                    'is_active' => $userData['is_active'] ?? true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = 'Row '.($index + 1).': '.$e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} users.";
        if ($failed > 0) {
            $message .= " {$failed} users failed to import.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'failed' => $failed,
            'errors' => $errors,
        ]);
    }

    /**
     * Download Excel template for bulk import.
     */
    public function downloadTemplate()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $headers = [
            'Name',
            'Email',
            'Password',
            'Role',
            'School',
            'Sex',
            'Phone',
            'Holding Classes',
            'Is Active',
        ];

        $schools = School::orderBy('name')->pluck('name')->toArray();

        // Sample data
        $sampleData = [
            [
                'John Doe',
                'john.doe@example.com',
                'password123',
                'teacher',
                $schools[0] ?? 'School Name',
                'male',
                '012345678',
                'Grade 1-3',
                'yes',
            ],
            [
                'Jane Smith',
                'jane.smith@example.com',
                'password123',
                'mentor',
                $schools[1] ?? 'Another School',
                'female',
                '098765432',
                '',
                'yes',
            ],
            [
                'Admin User',
                'admin@example.com',
                'adminpass123',
                'admin',
                '',
                'male',
                '011223344',
                '',
                'yes',
            ],
        ];

        $callback = function () use ($headers, $sampleData) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, $headers);

            // Sample data
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="users_import_template.csv"',
        ]);
    }

    /**
     * Show the form for assigning schools to a mentor.
     */
    public function assignSchools(User $user)
    {
        // Only admin can assign schools to mentors
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Ensure the user is a mentor
        if ($user->role !== 'mentor') {
            return redirect()->route('users.index')
                ->with('error', __('Schools can only be assigned to mentors.'));
        }

        $schools = School::orderBy('name')->get();
        $assignedSchoolIds = $user->assignedSchools->pluck('id')->toArray();

        return view('users.assign-schools', compact('user', 'schools', 'assignedSchoolIds'));
    }

    /**
     * Update the schools assigned to a mentor.
     */
    public function updateAssignedSchools(Request $request, User $user)
    {
        // Only admin can assign schools to mentors
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Ensure the user is a mentor
        if ($user->role !== 'mentor') {
            return redirect()->route('users.index')
                ->with('error', __('Schools can only be assigned to mentors.'));
        }

        $request->validate([
            'schools' => 'array',
            'schools.*' => 'exists:schools,id',
        ]);

        // Sync the schools (this will remove unselected schools and add new ones)
        $user->assignedSchools()->sync($request->schools ?? []);

        return redirect()->route('users.show', $user)
            ->with('success', __('Schools assigned successfully.'));
    }
}
