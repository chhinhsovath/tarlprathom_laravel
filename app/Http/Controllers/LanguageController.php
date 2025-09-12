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
            $message = $locale === 'km' ? 'ភាសាត្រូវបានប្តូរទៅខ្មែរដោយជោគជ័យ។' : 'Language changed to English successfully.';
            return redirect()->back()
                ->withCookie(cookie('locale', $locale, 60 * 24 * 30)) // 30 days
                ->with('success', $message);
        }

        return redirect()->back()
            ->with('error', 'Invalid language selected.');
    }
}
