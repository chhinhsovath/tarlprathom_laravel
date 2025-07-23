<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assessment_id',
        'cycle',
        'subject',
        'level',
        'score',
        'assessed_at',
        'updated_by',
        'action',
        'previous_data',
    ];

    protected $casts = [
        'assessed_at' => 'date',
        'previous_data' => 'array',
    ];

    /**
     * Get the student that owns the assessment history.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the current assessment record.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the user who made the update.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to get history for a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope to get history for a specific subject.
     */
    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope to get history for a specific cycle.
     */
    public function scopeForCycle($query, $cycle)
    {
        return $query->where('cycle', $cycle);
    }

    /**
     * Get formatted level change.
     */
    public function getLevelChangeAttribute()
    {
        if (! $this->previous_data || ! isset($this->previous_data['level'])) {
            return null;
        }

        $previousLevel = $this->previous_data['level'];
        $currentLevel = $this->level;

        if ($previousLevel === $currentLevel) {
            return 'No change';
        }

        return $previousLevel.' → '.$currentLevel;
    }

    /**
     * Get formatted score change.
     */
    public function getScoreChangeAttribute()
    {
        if (! $this->previous_data || ! isset($this->previous_data['score'])) {
            return null;
        }

        $previousScore = $this->previous_data['score'];
        $currentScore = $this->score;

        $change = $currentScore - $previousScore;

        if ($change > 0) {
            return '+'.$change;
        } elseif ($change < 0) {
            return $change;
        } else {
            return '0';
        }
    }
}
