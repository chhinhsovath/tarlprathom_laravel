<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MentoringVisitApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Mentoring Visits API
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Mentoring Visits CRUD
    Route::apiResource('mentoring-visits', MentoringVisitApiController::class);
    
    // Additional endpoints
    Route::get('schools', function () {
        return \App\Models\PilotSchool::select('id', 'school_name', 'school_code')
            ->orderBy('school_name')
            ->get();
    });
    
    Route::get('teachers/{schoolId}', function ($schoolId) {
        return \App\Models\User::select('id', 'name', 'email')
            ->where('role', 'teacher')
            ->where('pilot_school_id', $schoolId)
            ->where('is_active', true)
            ->get();
    });
});
