<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTeacherProfile
{
    /**
     * Handle an incoming request.
     * Check if teacher has completed their profile (school and subject assignment)
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Only check for teachers
        if ($user && $user->role === 'teacher') {
            // Check if teacher has school and subject assigned
            if (!$user->school_id || !$user->assigned_subject || !$user->holding_classes) {
                // Allow access to profile update routes
                if (!$request->routeIs('teacher.profile.*') && 
                    !$request->routeIs('api.schools.*') &&
                    !$request->routeIs('logout')) {
                    return redirect()->route('teacher.profile.setup')
                        ->with('warning', 'Please complete your profile setup first.');
                }
            }
        }
        
        return $next($request);
    }
}