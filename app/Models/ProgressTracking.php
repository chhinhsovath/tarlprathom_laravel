<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressTracking extends Model
{
    use HasFactory;

    protected $table = 'progress_tracking';

    protected $fillable = [
        'student_id', 'school_id', 'teacher_id', 'academic_year', 'term',
        'week_number', 'week_start_date', 'week_end_date', 'subject',
        'starting_level', 'current_level', 'level_changed', 'new_level',
        'level_change_date', 'activities_completed', 'activities_attempted',
        'completion_rate', 'accuracy_rate', 'practice_minutes', 'skills_practiced',
        'skills_mastered', 'areas_of_difficulty', 'engagement_score', 'effort_score',
        'homework_completed', 'participated_actively', 'peer_support_given',
        'peer_support_received', 'teacher_observations', 'breakthrough_moments',
        'intervention_notes', 'parent_communication', 'parent_feedback',
        'weekly_improvement', 'cumulative_progress', 'next_week_focus',
        'recommended_activities',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'level_change_date' => 'date',
        'level_changed' => 'boolean',
        'completion_rate' => 'decimal:2',
        'accuracy_rate' => 'decimal:2',
        'engagement_score' => 'decimal:2',
        'effort_score' => 'decimal:2',
        'homework_completed' => 'boolean',
        'participated_actively' => 'boolean',
        'parent_communication' => 'boolean',
        'weekly_improvement' => 'decimal:2',
        'cumulative_progress' => 'decimal:2',
        'skills_practiced' => 'array',
        'skills_mastered' => 'array',
        'areas_of_difficulty' => 'array',
        'breakthrough_moments' => 'array',
        'intervention_notes' => 'array',
        'recommended_activities' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'sclAutoID');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function scopeForAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeForTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeForWeek($query, $weekNumber)
    {
        return $query->where('week_number', $weekNumber);
    }

    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeWithLevelProgress($query)
    {
        return $query->where('level_changed', true);
    }

    public function getProgressStatusAttribute()
    {
        if ($this->weekly_improvement > 10) {
            return 'excellent';
        } elseif ($this->weekly_improvement > 5) {
            return 'good';
        } elseif ($this->weekly_improvement > 0) {
            return 'satisfactory';
        } elseif ($this->weekly_improvement === 0) {
            return 'no_change';
        } else {
            return 'needs_attention';
        }
    }

    public function getEngagementLevelAttribute()
    {
        $score = $this->engagement_score ?? 0;

        if ($score >= 4.5) {
            return 'highly_engaged';
        } elseif ($score >= 3.5) {
            return 'engaged';
        } elseif ($score >= 2.5) {
            return 'moderately_engaged';
        } else {
            return 'low_engagement';
        }
    }

    public function calculateWeeklyImprovement()
    {
        $previousWeek = self::where('student_id', $this->student_id)
            ->where('academic_year', $this->academic_year)
            ->where('term', $this->term)
            ->where('subject', $this->subject)
            ->where('week_number', $this->week_number - 1)
            ->first();

        if (! $previousWeek) {
            return 0;
        }

        $currentScore = ($this->completion_rate ?? 0) * 0.4 +
                       ($this->accuracy_rate ?? 0) * 0.6;

        $previousScore = ($previousWeek->completion_rate ?? 0) * 0.4 +
                        ($previousWeek->accuracy_rate ?? 0) * 0.6;

        return round($currentScore - $previousScore, 2);
    }

    public function calculateCumulativeProgress()
    {
        $firstWeek = self::where('student_id', $this->student_id)
            ->where('academic_year', $this->academic_year)
            ->where('term', $this->term)
            ->where('subject', $this->subject)
            ->where('week_number', 1)
            ->first();

        if (! $firstWeek) {
            return 0;
        }

        $levelProgress = $this->calculateLevelProgress($firstWeek->starting_level, $this->current_level);
        $scoreProgress = (($this->accuracy_rate ?? 0) - ($firstWeek->accuracy_rate ?? 0));

        return round(($levelProgress * 0.7 + $scoreProgress * 0.3), 2);
    }

    private function calculateLevelProgress($startLevel, $currentLevel)
    {
        $levels = [
            'beginner' => 1,
            'letter' => 2,
            'word' => 3,
            'paragraph' => 4,
            'story' => 5,
        ];

        $start = $levels[strtolower($startLevel)] ?? 1;
        $current = $levels[strtolower($currentLevel)] ?? 1;

        return ($current - $start) * 20;
    }

    public static function generateWeeklyReport($studentId, $weekNumber, $academicYear, $term)
    {
        $records = self::where('student_id', $studentId)
            ->where('week_number', $weekNumber)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->get();

        return [
            'student_id' => $studentId,
            'week' => $weekNumber,
            'subjects' => $records->map(function ($record) {
                return [
                    'subject' => $record->subject,
                    'current_level' => $record->current_level,
                    'progress' => $record->weekly_improvement,
                    'engagement' => $record->engagement_level,
                    'areas_of_strength' => $record->skills_mastered,
                    'areas_for_improvement' => $record->areas_of_difficulty,
                ];
            }),
            'overall_engagement' => $records->avg('engagement_score'),
            'overall_progress' => $records->avg('weekly_improvement'),
        ];
    }
}
