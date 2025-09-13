<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $welcomeMessage = app()->getLocale() === 'km' 
            ? 'សូមស្វាគមន៍! អ្នកបានចូលប្រព័ន្ធដោយជោគជ័យ។' 
            : 'Welcome! You have successfully logged in.';

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', $welcomeMessage);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $logoutMessage = app()->getLocale() === 'km' 
            ? 'អ្នកបានចាកចេញពីប្រព័ន្ធដោយជោគជ័យ។' 
            : 'You have been successfully logged out.';

        return redirect('/')
            ->with('info', $logoutMessage);
    }
    
    /**
     * Display the quick login view for internal users.
     */
    public function quickLogin(): View
    {
        // Get teachers and mentors only
        $users = User::whereIn('role', ['teacher', 'mentor'])
            ->where('is_active', true)
            ->orderBy('role')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'role_display' => ucfirst($user->role),
                    'school' => $user->school ? $user->school->name : 'No School Assigned',
                ];
            });
        
        // Group users by role
        $groupedUsers = $users->groupBy('role');
        
        return view('auth.quick-login', compact('groupedUsers'));
    }
    
    /**
     * Handle quick login authentication request.
     */
    public function quickLoginStore(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string',
        ]);
        
        $user = User::find($request->user_id);
        
        // Check if user is teacher or mentor
        if (!in_array($user->role, ['teacher', 'mentor'])) {
            return back()->withErrors([
                'user_id' => 'Quick login is only available for teachers and mentors.',
            ])->onlyInput('user_id');
        }
        
        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'user_id' => 'This account is currently inactive.',
            ])->onlyInput('user_id');
        }
        
        // Attempt to authenticate with the provided password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->onlyInput('user_id');
        }
        
        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();
        
        $welcomeMessage = app()->getLocale() === 'km' 
            ? 'សូមស្វាគមន៍! អ្នកបានចូលប្រព័ន្ធដោយជោគជ័យ។' 
            : 'Welcome! You have successfully logged in.';
        
        return redirect()->intended(route('dashboard'))
            ->with('success', $welcomeMessage);
    }
}
