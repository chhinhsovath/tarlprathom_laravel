<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        if (in_array($locale, ['en', 'km'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);

            // Also set a cookie as a fallback
            return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 30)); // 30 days
        }

        return redirect()->back();
    }
}
