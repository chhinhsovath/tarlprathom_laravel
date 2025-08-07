<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_code', 'activity_name', 'description', 'subject', 'grade_level',
        'tarl_level', 'activity_type', 'duration_minutes', 'learning_objectives',
        'materials_required', 'preparation_steps', 'implementation_steps',
        'assessment_strategies', 'differentiation_strategies', 'extension_activities',
        'keywords', 'difficulty_level', 'requires_technology', 'technology_requirements',
        'indoor_activity', 'space_requirements', 'minimum_students', 'maximum_students',
        'skills_developed', 'effectiveness_rating', 'usage_count', 'created_by',
        'is_approved', 'approved_by', 'approved_at', 'is_active'
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'materials_required' => 'array',
        'preparation_steps' => 'array',
        'implementation_steps' => 'array',
        'assessment_strategies' => 'array',
        'differentiation_strategies' => 'array',
        'extension_activities' => 'array',
        'keywords' => 'array',
        'technology_requirements' => 'array',
        'space_requirements' => 'array',
        'skills_developed' => 'array',
        'effectiveness_rating' => 'decimal:2',
        'requires_technology' => 'boolean',
        'indoor_activity' => 'boolean',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'approved_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function lessonPlans()
    {
        return $this->belongsToMany(LessonPlan::class, 'lesson_plan_activities');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeForGrade($query, $gradeLevel)
    {
        return $query->where('grade_level', 'like', '%' . $gradeLevel . '%');
    }

    public function scopeForLevel($query, $level)
    {
        return $query->where('tarl_level', $level);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function getDurationInHoursAttribute()
    {
        return round($this->duration_minutes / 60, 1);
    }

    public function getGradeLevelsArrayAttribute()
    {
        return explode(',', $this->grade_level);
    }

    public function isApplicableForGrade($grade)
    {
        return in_array($grade, $this->grade_levels_array);
    }

    public function updateEffectivenessRating($rating)
    {
        if ($this->effectiveness_rating) {
            $this->effectiveness_rating = ($this->effectiveness_rating + $rating) / 2;
        } else {
            $this->effectiveness_rating = $rating;
        }
        $this->save();
    }

    public function getPopularityScore()
    {
        $usageScore = min($this->usage_count / 100, 1) * 40;
        $ratingScore = ($this->effectiveness_rating / 5) * 60;
        
        return round($usageScore + $ratingScore, 1);
    }

    public static function getRecommendedForStudent($studentId, $subject = null, $limit = 5)
    {
        $query = self::active()->approved();
        
        if ($subject) {
            $query->forSubject($subject);
        }
        
        return $query->orderByDesc('effectiveness_rating')
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();
    }

    public static function searchActivities($searchTerm)
    {
        return self::active()->approved()
            ->where(function ($query) use ($searchTerm) {
                $query->where('activity_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereJsonContains('keywords', $searchTerm);
            })
            ->get();
    }

    public function getRequiredMaterialsList()
    {
        return implode(', ', $this->materials_required ?? []);
    }

    public function getSkillsList()
    {
        return implode(', ', $this->skills_developed ?? []);
    }
}