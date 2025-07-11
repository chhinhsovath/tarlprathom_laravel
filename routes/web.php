<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\MentoringVisitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

// Language switching
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Test locale
Route::get('/test-locale', function() {
    return view('test-locale');
});

// Public routes
Route::get('/', [AssessmentController::class, 'publicResults'])->name('public.assessment-results');
Route::get('/api/assessment-data', [AssessmentController::class, 'getChartData'])->name('api.assessment-data');

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
    
    // Student Management Routes
    Route::resource('students', StudentController::class);
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('/students/import', function() {
        return view('students.import');
    })->name('students.import')->middleware('role:admin,teacher');
    
    // Assessment Routes
    Route::resource('assessments', AssessmentController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/assessments/export', [AssessmentController::class, 'export'])->name('assessments.export');
    Route::post('/api/assessments/save-student', [AssessmentController::class, 'saveStudentAssessment'])->name('api.assessments.save-student');
    Route::post('/api/assessments/submit-all', [AssessmentController::class, 'submitAllAssessments'])->name('api.assessments.submit-all');
    
    // Mentoring Visit Routes
    Route::get('/mentoring', [MentoringVisitController::class, 'index'])->name('mentoring.index');
    Route::get('/mentoring/export', [MentoringVisitController::class, 'export'])->name('mentoring.export');
    Route::get('/mentoring/create', [MentoringVisitController::class, 'create'])->name('mentoring.create');
    Route::post('/mentoring', [MentoringVisitController::class, 'store'])->name('mentoring.store');
    Route::get('/mentoring/{mentoringVisit}', [MentoringVisitController::class, 'show'])->name('mentoring.show');
    
    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/student-performance', [ReportController::class, 'studentPerformance'])->name('reports.student-performance');
    Route::get('/reports/school-comparison', [ReportController::class, 'schoolComparison'])->name('reports.school-comparison');
    Route::get('/reports/mentoring-impact', [ReportController::class, 'mentoringImpact'])->name('reports.mentoring-impact');
    Route::get('/reports/progress-tracking', [ReportController::class, 'progressTracking'])->name('reports.progress-tracking');
    Route::get('/reports/my-students', [ReportController::class, 'myStudents'])->name('reports.my-students');
    Route::get('/reports/class-progress', [ReportController::class, 'classProgress'])->name('reports.class-progress');
    Route::get('/reports/my-mentoring', [ReportController::class, 'myMentoring'])->name('reports.my-mentoring');
    Route::get('/reports/school-visits', [ReportController::class, 'schoolVisits'])->name('reports.school-visits');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
