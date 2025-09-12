<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
