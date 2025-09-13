<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Try to get locale from session first
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Fall back to cookie if session doesn't have it
        elseif ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        // Default to Khmer (km) for first-time users
        else {
            $defaultLocale = 'km'; // Force Khmer as default
            App::setLocale($defaultLocale);
            Session::put('locale', $defaultLocale);
        }

        return $next($request);
    }
}
