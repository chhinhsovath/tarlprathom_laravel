<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        $settings = [
            'app_name' => config('app.name'),
            'app_timezone' => config('app.timezone'),
            'app_locale' => config('app.locale'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_timezone' => ['required', 'string', 'timezone'],
            'app_locale' => ['required', 'string', 'in:en,km'],
            'mail_from_address' => ['nullable', 'email'],
            'mail_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        // In a real application, you would save these to a database or .env file
        // For now, we'll just show a success message
        Cache::put('settings', $validated, now()->addYears(1));

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
