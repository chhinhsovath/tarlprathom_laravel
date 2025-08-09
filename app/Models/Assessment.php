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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Temporarily disable history tracking until the table structure is fixed
        // The assessment_histories table doesn't match the expected structure
        return;

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
     * Get the student that the assessment belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the history records for this assessment.
     */
    public function histories()
    {
        return $this->hasMany(AssessmentHistory::class);
    }

    /**
     * Get the user who locked this assessment.
     */
    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Create a history record for this assessment.
     */
    protected function createHistory($action, $previousData = null)
    {
        $historyData = [
            'student_id' => $this->student_id,
            'assessment_id' => $this->id,
            'cycle' => $this->cycle,
            'subject' => $this->subject,
            'level' => $this->level,
            'score' => $this->score,
            'assessed_at' => $this->assessed_at,
            'updated_by' => Auth::id(),
            'action' => $action,
        ];

        if ($previousData) {
            $historyData['previous_data'] = [
                'level' => $previousData['level'] ?? null,
                'score' => $previousData['score'] ?? null,
                'assessed_at' => $previousData['assessed_at'] ?? null,
            ];
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
            ->where('cycle', $cycle)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
