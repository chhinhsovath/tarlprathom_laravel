<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,coordinator']);
    }

    public function setLanguage($locale)
    {
        if (in_array($locale, ['en', 'km'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);

            return back()->with('success', 'Language changed successfully');
        }

        return back()->with('error', 'Invalid language selection');
    }

    public function index()
    {
        $availableLocales = [
            'en' => 'English',
            'km' => 'ភាសាខ្មែរ (Khmer)',
        ];

        $currentLocale = App::getLocale();

        return view('localization.index', compact('availableLocales', 'currentLocale'));
    }

    public function editTranslations(Request $request)
    {
        $search = is_string($request->get('search')) ? $request->get('search') : '';
        $group = is_string($request->get('group')) ? $request->get('group') : 'all';

        // Get all groups for filter
        $groups = Translation::getGroups();
        if (!in_array('all', $groups)) {
            array_unshift($groups, 'all');
        }

        // Get translations
        $query = Translation::query();

        if (!empty($search)) {
            $query->search($search);
        }

        if ($group !== 'all') {
            $query->where('group', $group);
        }

        $translations = $query->orderBy('group')->orderBy('key')->paginate(50);

        return view('localization.database-edit', compact('translations', 'groups', 'group', 'search'));
    }

    public function updateTranslation(Request $request, Translation $translation)
    {
        $request->validate([
            'km' => 'nullable|string',
            'en' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $translation->update($request->only(['km', 'en', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Translation updated successfully',
        ]);
    }

    public function createTranslation(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:translations,key',
            'km' => 'nullable|string',
            'en' => 'nullable|string',
            'group' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $translation = Translation::create($request->all());

        return redirect()->route('localization.edit')
            ->with('success', 'Translation created successfully');
    }

    public function deleteTranslation(Translation $translation)
    {
        $translation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Translation deleted successfully',
        ]);
    }

    public function toggleTranslation(Translation $translation)
    {
        $translation->is_active = ! $translation->is_active;
        $translation->save();

        return response()->json([
            'success' => true,
            'is_active' => $translation->is_active,
        ]);
    }

    public function exportToFiles()
    {
        // Export translations to language files
        $locales = ['km', 'en'];

        foreach ($locales as $locale) {
            $translations = Translation::where('is_active', true)
                ->whereNotNull($locale)
                ->pluck($locale, 'key')
                ->toArray();

            $json = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $path = resource_path("lang/{$locale}.json");

            File::put($path, $json);
        }

        Translation::clearCache();

        return back()->with('success', 'Translations exported to language files successfully');
    }
}
