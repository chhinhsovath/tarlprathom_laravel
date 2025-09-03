<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MentoringVisit;
use App\Services\MentoringVisitService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MentoringVisitApiController extends Controller
{
    protected MentoringVisitService $service;

    public function __construct(MentoringVisitService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of mentoring visits
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = MentoringVisit::with(['mentor:id,name', 'teacher:id,name', 'pilotSchool:id,school_name']);

        // Apply role-based filtering
        if ($user && $user->role === 'teacher') {
            $query->where('teacher_id', $user->id);
        } elseif ($user && $user->role === 'mentor') {
            $schoolIds = $user->assignedPilotSchools()->pluck('pilot_schools.id');
            $query->whereIn('pilot_school_id', $schoolIds);
        }

        // Paginate results
        $visits = $query->orderBy('visit_date', 'desc')
                       ->paginate($request->get('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => $visits
        ]);
    }

    /**
     * Store a newly created mentoring visit
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pilot_school_id' => 'required|exists:pilot_schools,id',
            'teacher_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'province' => 'required|string',
            'class_in_session' => 'required|boolean',
        ]);

        try {
            $visit = $this->service->create($validated, $request->user());
            return response()->json([
                'status' => 'success',
                'message' => 'Visit created successfully',
                'data' => $visit
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified mentoring visit
     */
    public function show(string $id): JsonResponse
    {
        $visit = MentoringVisit::with(['mentor', 'teacher', 'pilotSchool'])
                               ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $visit
        ]);
    }

    /**
     * Update the specified mentoring visit
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $visit = MentoringVisit::findOrFail($id);
        
        try {
            $updated = $this->service->update($visit, $request->all(), $request->user());
            return response()->json([
                'status' => 'success',
                'message' => 'Visit updated successfully',
                'data' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified mentoring visit
     */
    public function destroy(string $id): JsonResponse
    {
        $visit = MentoringVisit::findOrFail($id);
        $visit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Visit deleted successfully'
        ]);
    }
}
