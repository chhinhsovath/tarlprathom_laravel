<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'subject', 'grade_level', 'domain',
        'cognitive_level', 'sequence_order', 'parent_outcome_code',
        'assessment_criteria', 'performance_indicators', 'minimum_mastery_score',
        'target_weeks_to_achieve', 'prerequisite_outcomes', 'related_outcomes',
        'teaching_strategies', 'learning_resources', 'is_core_outcome',
        'is_measurable', 'measurement_method', 'difficulty_level', 'is_active',
    ];

    protected $casts = [
        'assessment_criteria' => 'array',
        'performance_indicators' => 'array',
        'prerequisite_outcomes' => 'array',
        'related_outcomes' => 'array',
        'teaching_strategies' => 'array',
        'learning_resources' => 'array',
        'is_core_outcome' => 'boolean',
        'is_measurable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function parentOutcome()
    {
        return $this->belongsTo(self::class, 'parent_outcome_code', 'code');
    }

    public function childOutcomes()
    {
        return $this->hasMany(self::class, 'parent_outcome_code', 'code');
    }

    public function studentOutcomes()
    {
        return $this->hasMany(StudentLearningOutcome::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCore($query)
    {
        return $query->where('is_core_outcome', true);
    }

    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeForGrade($query, $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }

    public function scopeForDomain($query, $domain)
    {
        return $query->where('domain', $domain);
    }

    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parentOutcome;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parentOutcome;
        }

        return implode(' > ', $path);
    }

    public function getStudentMasteryRate()
    {
        $total = $this->studentOutcomes()->count();

        if ($total === 0) {
            return 0;
        }

        $mastered = $this->studentOutcomes()
            ->whereIn('mastery_level', ['proficient', 'advanced'])
            ->count();

        return round(($mastered / $total) * 100, 2);
    }

    public function getAverageAchievementScore()
    {
        return $this->studentOutcomes()->avg('achievement_score') ?? 0;
    }

    public function getAverageAttemptsToMastery()
    {
        return $this->studentOutcomes()
            ->whereIn('mastery_level', ['proficient', 'advanced'])
            ->avg('attempts_count') ?? 0;
    }
}
