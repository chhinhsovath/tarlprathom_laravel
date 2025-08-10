<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'assessor_id',
        'cycle',
        'subject',
        'level',
        'score',
        'assessed_at',
        'is_locked',
        'locked_by',
        'locked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assessed_at' => 'date',
        'score' => 'float',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    /**
     * Temporary storage for original data during updates
     */
    protected $originalDataBeforeUpdate;

    /**
     * Get the student that owns the assessment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the assessor (user) who conducted the assessment.
     */
    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    /**
     * Get the user who locked this assessment.
     */
    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Track history when creating a new assessment
        static::created(function ($assessment) {
            $assessment->createHistory('created');
        });

        // Track history before updating an assessment
        static::updating(function ($assessment) {
            // Get the original data before update
            $assessment->originalDataBeforeUpdate = $assessment->getOriginal();
        });

        // Track history after updating an assessment
        static::updated(function ($assessment) {
            $assessment->createHistory('updated', $assessment->originalDataBeforeUpdate ?? null);
        });
    }

    /**
     * Get the history records for this assessment.
     */
    public function histories()
    {
        return $this->hasMany(AssessmentHistory::class);
    }

    /**
     * Create a history record for this assessment.
     */
    protected function createHistory($action, $previousData = null)
    {
        $historyData = [
            'student_id' => $this->student_id,
            'assessment_type' => $this->cycle, // Map cycle to assessment_type
            'subject' => $this->subject,
            'level' => $this->level,
            'score' => $this->score,
            'assessed_at' => $this->assessed_at,
            'assessor_id' => $this->assessor_id ?? Auth::id(),
            'is_locked' => $this->is_locked ?? false,
            'locked_by' => $this->locked_by,
            'locked_at' => $this->locked_at,
        ];

        if ($previousData) {
            $historyData['assessment_data'] = json_encode([
                'action' => $action,
                'previous_data' => [
                    'level' => $previousData['level'] ?? null,
                    'score' => $previousData['score'] ?? null,
                    'assessed_at' => $previousData['assessed_at'] ?? null,
                    'is_locked' => $previousData['is_locked'] ?? false,
                ],
            ]);
        } else {
            $historyData['assessment_data'] = json_encode([
                'action' => $action,
            ]);
        }

        AssessmentHistory::create($historyData);
    }

    /**
     * Get all history for a student's subject and cycle.
     */
    public static function getHistoryForStudent($studentId, $subject, $cycle)
    {
        return AssessmentHistory::where('student_id', $studentId)
            ->where('subject', $subject)
            ->where('assessment_type', $cycle) // Use assessment_type instead of cycle
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
