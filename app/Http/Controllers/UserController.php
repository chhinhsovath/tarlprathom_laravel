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
        if ($request->has('is_active') && $request->get('is_active') !== '') {
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
        $schools = School::orderBy('school_name')->get();

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
        $schools = School::orderBy('school_name')->get();

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
            'role' => ['required', 'in:admin,mentor,teacher,viewer'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'sex' => ['nullable', 'in:male,female'],
            'telephone' => ['nullable', 'string', 'max:20'],
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
        $schools = School::orderBy('school_name')->get();

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
            'role' => ['required', 'in:admin,mentor,teacher,viewer'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'sex' => ['nullable', 'in:male,female'],
            'telephone' => ['nullable', 'string', 'max:20'],
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
}
