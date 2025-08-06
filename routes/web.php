<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MentoringVisitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Language switching
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Test translation page
Route::get('/test-translation', function () {
    return view('test-translation');
})->middleware(['auth']);

// Debug locale
Route::get('/debug-locale', function () {
    $langPath = app()->langPath();
    $jsonFile = $langPath.'/'.app()->getLocale().'.json';
    $translations = [];
    if (file_exists($jsonFile)) {
        $translations = json_decode(file_get_contents($jsonFile), true);
    }

    // Test if translations are working
    $testKeys = [
        'Bulk Import Students',
        'Instructions',
        'Download Excel Template',
        'Name',
        'Age',
        'Gender',
    ];

    $testResults = [];
    foreach ($testKeys as $key) {
        $testResults[$key] = __($key);
    }

    return [
        'app_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'cookie_locale' => request()->cookie('locale'),
        'config_locale' => config('app.locale'),
        'lang_path' => $langPath,
        'json_file' => $jsonFile,
        'json_exists' => file_exists($jsonFile),
        'translations_count' => count($translations),
        'test_translations' => $testResults,
        'lang_path_real' => realpath($langPath),
        'json_file_real' => realpath($jsonFile),
        'translator_locale' => app('translator')->getLocale(),
    ];
});

// Public routes
Route::get('/', [AssessmentController::class, 'publicResults'])->name('public.assessment-results');
Route::get('/api/assessment-data', [AssessmentController::class, 'getChartData'])->name('api.assessment-data');
Route::get('/resources', [ResourceController::class, 'publicIndex'])->name('resources.public');
Route::get('/resources/{resource}', [ResourceController::class, 'publicShow'])->name('resources.public.show');
Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');
Route::post('/api/resources/{resource}/track-view', [ResourceController::class, 'trackView'])->name('api.resources.track-view');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard API endpoints
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
    Route::get('/api/dashboard/overall-results', [DashboardController::class, 'getOverallResults'])->name('api.dashboard.overall-results');
    Route::get('/api/dashboard/results-by-school', [DashboardController::class, 'getResultsBySchool'])->name('api.dashboard.results-by-school');

    // User API endpoints
    Route::get('/api/users', [UserController::class, 'apiGetUsersByRole'])->name('api.users.by-role');

    // School API endpoints
    Route::get('/api/school/{school}/teachers', [SchoolController::class, 'getTeachers'])->name('api.school.teachers');
    Route::get('/api/geographic/provinces', [SchoolController::class, 'getProvinces'])->name('api.geographic.provinces');
    Route::get('/api/geographic/districts', [SchoolController::class, 'getDistricts'])->name('api.geographic.districts');
    Route::get('/api/geographic/communes', [SchoolController::class, 'getCommunes'])->name('api.geographic.communes');
    Route::get('/api/geographic/villages', [SchoolController::class, 'getVillages'])->name('api.geographic.villages');

    // Student Management Routes
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('/students/{student}/assessment-history', [StudentController::class, 'assessmentHistory'])->name('students.assessment-history');
    Route::get('/students/download-template', [StudentController::class, 'downloadTemplate'])->name('students.download-template');
    Route::get('/students/bulk-import', [StudentController::class, 'bulkImportForm'])->name('students.bulk-import-form');
    Route::post('/students/bulk-import', [StudentController::class, 'bulkImport'])->name('students.bulk-import');
    Route::resource('students', StudentController::class);
    Route::get('/students/import', function () {
        return view('students.import');
    })->name('students.import')->middleware('role:admin,teacher');

    // Classes Management
    Route::resource('classes', ClassController::class);

    // Assessment Routes
    Route::get('/assessments/export', [AssessmentController::class, 'export'])->name('assessments.export');
    Route::get('/assessments/select-students', [AssessmentController::class, 'selectStudents'])->name('assessments.select-students');
    Route::post('/assessments/select-students', [AssessmentController::class, 'updateSelectedStudents'])->name('assessments.update-selected-students');
    Route::resource('assessments', AssessmentController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/api/assessments/save-student', [AssessmentController::class, 'saveStudentAssessment'])->name('api.assessments.save-student');
    Route::post('/api/assessments/submit-all', [AssessmentController::class, 'submitAllAssessments'])->name('api.assessments.submit-all');

    // Mentoring Visit Routes
    Route::get('/mentoring', [MentoringVisitController::class, 'index'])->name('mentoring.index');
    Route::get('/mentoring/export', [MentoringVisitController::class, 'export'])->name('mentoring.export');
    Route::get('/mentoring/create', [MentoringVisitController::class, 'create'])->name('mentoring.create');
    Route::post('/mentoring', [MentoringVisitController::class, 'store'])->name('mentoring.store');
    Route::get('/mentoring/{mentoringVisit}', [MentoringVisitController::class, 'show'])->name('mentoring.show');
    Route::get('/mentoring/{mentoringVisit}/edit', [MentoringVisitController::class, 'edit'])->name('mentoring.edit');
    Route::put('/mentoring/{mentoringVisit}', [MentoringVisitController::class, 'update'])->name('mentoring.update');
    Route::delete('/mentoring/{mentoringVisit}', [MentoringVisitController::class, 'destroy'])->name('mentoring.destroy');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/student-performance', [ReportController::class, 'studentPerformance'])->name('reports.student-performance');
    Route::get('/reports/school-comparison', [ReportController::class, 'schoolComparison'])->name('reports.school-comparison');
    Route::get('/reports/mentoring-impact', [ReportController::class, 'mentoringImpact'])->name('reports.mentoring-impact');
    Route::get('/reports/progress-tracking', [ReportController::class, 'progressTracking'])->name('reports.progress-tracking');
    Route::get('/reports/performance-calculation', [ReportController::class, 'performanceCalculation'])->name('reports.performance-calculation');
    Route::get('/reports/my-students', [ReportController::class, 'myStudents'])->name('reports.my-students');
    Route::get('/reports/class-progress', [ReportController::class, 'classProgress'])->name('reports.class-progress');
    Route::get('/reports/my-mentoring', [ReportController::class, 'myMentoring'])->name('reports.my-mentoring');
    Route::get('/reports/school-visits', [ReportController::class, 'schoolVisits'])->name('reports.school-visits');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // Administration Routes (Admin only)
    Route::get('/administration', [AdministrationController::class, 'index'])->name('administration.index');

    // Admin Routes
    Route::middleware(['auth'])->group(function () {
        // User Management
        Route::get('/users/bulk-import', [UserController::class, 'bulkImportForm'])->name('users.bulk-import-form');
        Route::post('/users/bulk-import', [UserController::class, 'bulkImport'])->name('users.bulk-import');
        Route::get('/users/bulk-import-enhanced', [UserController::class, 'bulkImportEnhancedForm'])->name('users.bulk-import-enhanced-form');
        Route::post('/users/bulk-import-enhanced', [UserController::class, 'bulkImportEnhanced'])->name('users.bulk-import-enhanced');
        Route::get('/users/download-template', [UserController::class, 'downloadTemplate'])->name('users.download-template');
        Route::get('/users/{user}/assign-schools', [UserController::class, 'assignSchools'])->name('users.assign-schools');
        Route::post('/users/{user}/assign-schools', [UserController::class, 'updateAssignedSchools'])->name('users.update-assigned-schools');
        Route::resource('users', UserController::class);

        // School Management
        Route::get('/schools/bulk-import', [SchoolController::class, 'bulkImportForm'])->name('schools.bulk-import-form');
        Route::post('/schools/bulk-import', [SchoolController::class, 'bulkImport'])->name('schools.bulk-import');
        Route::get('/schools/download-template', [SchoolController::class, 'downloadTemplate'])->name('schools.download-template');
        Route::get('/schools/assessment-dates', [SchoolController::class, 'assessmentDates'])->name('schools.assessment-dates');
        Route::post('/schools/update-assessment-dates', [SchoolController::class, 'updateAssessmentDates'])->name('schools.update-assessment-dates');
        Route::resource('schools', SchoolController::class);
        Route::post('/schools/{school}/add-teacher', [SchoolController::class, 'addTeacher'])->name('schools.add-teacher');
        Route::delete('/schools/{school}/remove-teacher', [SchoolController::class, 'removeTeacher'])->name('schools.remove-teacher');
        Route::get('/schools/{school}/search-teachers', [SchoolController::class, 'searchTeachers'])->name('schools.search-teachers');
        Route::post('/schools/{school}/add-mentor', [SchoolController::class, 'addMentor'])->name('schools.add-mentor');
        Route::delete('/schools/{school}/remove-mentor', [SchoolController::class, 'removeMentor'])->name('schools.remove-mentor');
        Route::get('/schools/{school}/search-mentors', [SchoolController::class, 'searchMentors'])->name('schools.search-mentors');

        // Resource Management (Admin only)
        Route::middleware(['auth', 'App\Http\Middleware\AdminMiddleware'])->group(function () {
            Route::resource('admin/resources', ResourceController::class)->names([
                'index' => 'resources.index',
                'create' => 'resources.create',
                'store' => 'resources.store',
                'show' => 'resources.show',
                'edit' => 'resources.edit',
                'update' => 'resources.update',
                'destroy' => 'resources.destroy',
            ]);

            // Assessment Management (Admin only)
            Route::prefix('assessment-management')->name('assessment-management.')->group(function () {
                Route::get('/', [\App\Http\Controllers\AssessmentManagementController::class, 'index'])->name('index');
                Route::get('/mentoring-visits', [\App\Http\Controllers\AssessmentManagementController::class, 'mentoringVisits'])->name('mentoring-visits');

                // Individual lock/unlock actions
                Route::post('/assessments/{assessment}/lock', [\App\Http\Controllers\AssessmentManagementController::class, 'lockAssessment'])->name('lock');
                Route::post('/assessments/{assessment}/unlock', [\App\Http\Controllers\AssessmentManagementController::class, 'unlockAssessment'])->name('unlock');
                Route::post('/mentoring-visits/{mentoringVisit}/lock', [\App\Http\Controllers\AssessmentManagementController::class, 'lockMentoringVisit'])->name('mentoring.lock');
                Route::post('/mentoring-visits/{mentoringVisit}/unlock', [\App\Http\Controllers\AssessmentManagementController::class, 'unlockMentoringVisit'])->name('mentoring.unlock');

                // Bulk actions
                Route::post('/assessments/bulk-lock', [\App\Http\Controllers\AssessmentManagementController::class, 'bulkLockAssessments'])->name('bulk-lock');
                Route::post('/assessments/bulk-unlock', [\App\Http\Controllers\AssessmentManagementController::class, 'bulkUnlockAssessments'])->name('bulk-unlock');
                Route::post('/mentoring-visits/bulk-lock', [\App\Http\Controllers\AssessmentManagementController::class, 'bulkLockMentoringVisits'])->name('mentoring.bulk-lock');
                Route::post('/mentoring-visits/bulk-unlock', [\App\Http\Controllers\AssessmentManagementController::class, 'bulkUnlockMentoringVisits'])->name('mentoring.bulk-unlock');
            });
        });

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Coordinator Workspace Routes (Admin and Coordinator only)
    Route::middleware(['role:admin,coordinator'])->group(function () {
        // Coordinator Workspace
        Route::get('/coordinator', [CoordinatorController::class, 'workspace'])->name('coordinator.workspace');
        Route::get('/coordinator/bulk-import', [CoordinatorController::class, 'bulkImportDashboard'])->name('coordinator.bulk-import');
        Route::get('/coordinator/language-management', [CoordinatorController::class, 'languageManagement'])->name('coordinator.language-management');
        Route::get('/coordinator/system-overview', [CoordinatorController::class, 'systemOverview'])->name('coordinator.system-overview');

        // Bulk Import Routes
        Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');

        // Dedicated import pages
        Route::get('/imports/schools', [ImportController::class, 'showSchoolsImport'])->name('imports.schools.show');
        Route::post('/imports/schools', [ImportController::class, 'importSchools'])->name('imports.schools');

        Route::get('/imports/users', [ImportController::class, 'showUsersImport'])->name('imports.users.show');
        Route::post('/imports/users', [ImportController::class, 'importUsers'])->name('imports.users');

        Route::get('/imports/students', [ImportController::class, 'showStudentsImport'])->name('imports.students.show');
        Route::post('/imports/students', [ImportController::class, 'importStudents'])->name('imports.students');

        Route::get('/imports/template/{type}', [ImportController::class, 'downloadTemplate'])->name('imports.template');

        // Localization Routes
        Route::get('/localization', [LocalizationController::class, 'index'])->name('localization.index');
        Route::get('/localization/set/{locale}', [LocalizationController::class, 'setLanguage'])->name('localization.set');
        Route::get('/localization/edit', [LocalizationController::class, 'editTranslations'])->name('localization.edit');
        Route::put('/localization/{translation}', [LocalizationController::class, 'updateTranslation'])->name('localization.update');
        Route::post('/localization', [LocalizationController::class, 'createTranslation'])->name('localization.create');
        Route::delete('/localization/{translation}', [LocalizationController::class, 'deleteTranslation'])->name('localization.delete');
        Route::patch('/localization/{translation}/toggle', [LocalizationController::class, 'toggleTranslation'])->name('localization.toggle');
        Route::post('/localization/export', [LocalizationController::class, 'exportToFiles'])->name('localization.export');
    });

    // Help & Support
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::get('/about', [HelpController::class, 'about'])->name('about');
});

require __DIR__.'/auth.php';
