<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLearningOutcome extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'learning_outcome_id', 'assessment_id', 'teacher_id',
        'mastery_level', 'achievement_score', 'achieved_date', 'attempts_count',
        'first_attempt_date', 'last_attempt_date', 'evidence_artifacts',
        'teacher_observations', 'support_provided', 'requires_remediation',
        'remediation_plan', 'practice_hours', 'practice_activities',
        'improvement_rate', 'learning_path', 'peer_assessment', 'self_assessment',
        'portfolio_included', 'portfolio_link'
    ];

    protected $casts = [
        'achieved_date' => 'date',
        'first_attempt_date' => 'date',
        'last_attempt_date' => 'date',
        'achievement_score' => 'decimal:2',
        'improvement_rate' => 'decimal:2',
        'evidence_artifacts' => 'array',
        'support_provided' => 'array',
        'practice_activities' => 'array',
        'peer_assessment' => 'array',
        'self_assessment' => 'array',
        'requires_remediation' => 'boolean',
        'portfolio_included' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function learningOutcome()
    {
        return $this->belongsTo(LearningOutcome::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function scopeMastered($query)
    {
        return $query->whereIn('mastery_level', ['proficient', 'advanced']);
    }

    public function scopeNeedsSupport($query)
    {
        return $query->whereIn('mastery_level', ['not_attempted', 'emerging']);
    }

    public function scopeRequiringRemediation($query)
    {
        return $query->where('requires_remediation', true);
    }

    public function getIsMasteredAttribute()
    {
        return in_array($this->mastery_level, ['proficient', 'advanced']);
    }

    public function getIsEmergingAttribute()
    {
        return in_array($this->mastery_level, ['emerging', 'developing']);
    }

    public function getDaysToAchieveAttribute()
    {
        if (!$this->achieved_date || !$this->first_attempt_date) {
            return null;
        }
        
        return $this->achieved_date->diffInDays($this->first_attempt_date);
    }

    public function calculateImprovementRate()
    {
        if ($this->attempts_count <= 1) {
            return 0;
        }
        
        $initialScore = 0;
        $currentScore = $this->achievement_score ?? 0;
        
        if ($this->attempts_count > 0) {
            return round((($currentScore - $initialScore) / $this->attempts_count), 2);
        }
        
        return 0;
    }

    public function addEvidence($type, $description, $url = null)
    {
        $artifacts = $this->evidence_artifacts ?? [];
        
        $artifacts[] = [
            'type' => $type,
            'description' => $description,
            'url' => $url,
            'added_at' => now()->toDateTimeString()
        ];
        
        $this->evidence_artifacts = $artifacts;
        $this->save();
    }

    public function addPracticeActivity($activity, $duration, $score = null)
    {
        $activities = $this->practice_activities ?? [];
        
        $activities[] = [
            'activity' => $activity,
            'duration' => $duration,
            'score' => $score,
            'date' => now()->toDateString()
        ];
        
        $this->practice_activities = $activities;
        $this->practice_hours = ($this->practice_hours ?? 0) + ($duration / 60);
        $this->save();
    }
}