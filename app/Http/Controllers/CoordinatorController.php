<?php

namespace App\Http\Controllers;

use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,coordinator']);
    }

    public function workspace()
    {
        // Get coordinator-relevant statistics only
        $stats = [
            'total_schools' => PilotSchool::count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_mentors' => User::where('role', 'mentor')->count(),
            'total_coordinators' => User::where('role', 'coordinator')->count(),
            'total_users' => User::whereIn('role', ['teacher', 'mentor', 'coordinator'])->count(),
            'system_languages' => $this->getSystemLanguages(),
            'import_stats' => $this->getImportStatistics(),
        ];

        return view('coordinator.workspace', compact('stats'));
    }

    private function getImportStatistics()
    {
        return [
            'schools_this_month' => PilotSchool::where('created_at', '>=', now()->subMonth())->count(),
            'users_this_month' => User::whereIn('role', ['teacher', 'mentor'])->where('created_at', '>=', now()->subMonth())->count(),
            'schools_today' => PilotSchool::whereDate('created_at', today())->count(),
            'users_today' => User::whereIn('role', ['teacher', 'mentor'])->whereDate('created_at', today())->count(),
        ];
    }

    private function getSystemLanguages()
    {
        $available = [
            'en' => 'English',
            'km' => 'ភាសាខ្មែរ (Khmer)',
        ];
        $current = App::getLocale();

        return [
            'current' => $current,
            'current_name' => $available[$current] ?? 'Unknown',
            'available' => $available,
        ];
    }

    public function bulkImportDashboard()
    {
        $stats = [
            'schools_count' => PilotSchool::count(),
            'students_count' => Student::count(),
            'teachers_count' => User::where('role', 'teacher')->count(),
            'mentors_count' => User::where('role', 'mentor')->count(),
        ];

        return view('coordinator.bulk-import', compact('stats'));
    }

    public function languageManagement()
    {
        $availableLocales = [
            'en' => 'English',
            'km' => 'ភាសាខ្មែរ (Khmer)',
        ];

        $currentLocale = App::getLocale();

        // Get translation file statistics
        $translationStats = [];
        foreach ($availableLocales as $locale => $name) {
            $langPath = resource_path("lang/{$locale}");
            $fileCount = 0;
            $keyCount = 0;

            if (is_dir($langPath)) {
                $files = glob($langPath.'/*.php');
                $fileCount = count($files);

                foreach ($files as $file) {
                    $translations = include $file;
                    if (is_array($translations)) {
                        $keyCount += count($translations, COUNT_RECURSIVE);
                    }
                }
            }

            $translationStats[$locale] = [
                'name' => $name,
                'files' => $fileCount,
                'keys' => $keyCount,
            ];
        }

        return view('coordinator.language-management', compact('availableLocales', 'currentLocale', 'translationStats'));
    }

    public function systemOverview()
    {
        $overview = [
            'database' => [
                'schools' => PilotSchool::count(),
                'students' => Student::count(),
                'teachers' => User::where('role', 'teacher')->count(),
                'mentors' => User::where('role', 'mentor')->count(),
                'coordinators' => User::where('role', 'coordinator')->count(),
                'admins' => User::where('role', 'admin')->count(),
            ],
            'recent_activity' => [
                'new_schools_this_week' => PilotSchool::where('created_at', '>=', now()->subWeek())->count(),
                'new_students_this_week' => Student::where('created_at', '>=', now()->subWeek())->count(),
                'new_users_this_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            ],
            'system_info' => [
                'current_language' => App::getLocale(),
                'total_languages' => 2,
                'database_size' => $this->getDatabaseSize(),
            ],
        ];

        return view('coordinator.system-overview', compact('overview'));
    }

    private function getDatabaseSize()
    {
        try {
            $result = DB::select("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()");

            return $result[0]->size_mb ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}
