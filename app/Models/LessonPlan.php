<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'school_id', 'class_id', 'title', 'description', 'subject',
        'grade_level', 'tarl_level', 'planned_date', 'start_time', 'end_time',
        'duration_minutes', 'learning_outcomes', 'success_criteria', 'activities_sequence',
        'teaching_activities_used', 'materials_needed', 'assessment_methods',
        'homework_assigned', 'teacher_reflection', 'execution_status', 'actual_date',
        'students_present', 'students_absent', 'challenges_faced', 'successes_noted',
        'student_engagement_levels', 'objectives_achieved', 'improvements_for_next_time',
        'shared_with_colleagues', 'times_reused', 'effectiveness_rating',
        'reviewed_by', 'reviewed_at', 'review_comments',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'actual_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'learning_outcomes' => 'array',
        'success_criteria' => 'array',
        'activities_sequence' => 'array',
        'teaching_activities_used' => 'array',
        'materials_needed' => 'array',
        'assessment_methods' => 'array',
        'homework_assigned' => 'array',
        'challenges_faced' => 'array',
        'successes_noted' => 'array',
        'student_engagement_levels' => 'array',
        'objectives_achieved' => 'array',
        'shared_with_colleagues' => 'boolean',
        'effectiveness_rating' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function school()
    {
        return $this->belongsTo(PilotSchool::class, 'pilot_school_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function teachingActivities()
    {
        return $this->belongsToMany(TeachingActivity::class, 'lesson_plan_activities');
    }

    public function scopePlanned($query)
    {
        return $query->where('execution_status', 'planned');
    }

    public function scopeCompleted($query)
    {
        return $query->where('execution_status', 'completed');
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('planned_date', $date);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('planned_date', [$startDate, $endDate]);
    }

    public function getIsCompletedAttribute()
    {
        return $this->execution_status === 'completed';
    }

    public function getIsOverdueAttribute()
    {
        return $this->execution_status === 'planned' &&
               $this->planned_date < now()->toDateString();
    }

    public function getDurationInHoursAttribute()
    {
        return round($this->duration_minutes / 60, 1);
    }

    public function getAttendanceRateAttribute()
    {
        if (! $this->students_present || ! $this->students_absent) {
            return null;
        }

        $total = $this->students_present + $this->students_absent;

        return $total > 0 ? round(($this->students_present / $total) * 100, 1) : 0;
    }

    public function markAsCompleted($actualData = [])
    {
        $this->execution_status = 'completed';
        $this->actual_date = $actualData['actual_date'] ?? now()->toDateString();
        $this->students_present = $actualData['students_present'] ?? null;
        $this->students_absent = $actualData['students_absent'] ?? null;
        $this->teacher_reflection = $actualData['teacher_reflection'] ?? '';
        $this->challenges_faced = $actualData['challenges_faced'] ?? [];
        $this->successes_noted = $actualData['successes_noted'] ?? [];
        $this->save();
    }

    public function postpone($newDate, $reason = null)
    {
        $this->execution_status = 'postponed';
        $this->planned_date = $newDate;
        if ($reason) {
            $this->challenges_faced = array_merge($this->challenges_faced ?? [], [$reason]);
        }
        $this->save();
    }

    public function cancel($reason)
    {
        $this->execution_status = 'cancelled';
        $this->challenges_faced = array_merge($this->challenges_faced ?? [], [$reason]);
        $this->save();
    }

    public function shareWithColleagues()
    {
        $this->shared_with_colleagues = true;
        $this->save();
    }

    public function reuse()
    {
        $this->increment('times_reused');
    }

    public function addReview($reviewerId, $rating, $comments = null)
    {
        $this->reviewed_by = $reviewerId;
        $this->reviewed_at = now();
        $this->effectiveness_rating = $rating;
        $this->review_comments = $comments;
        $this->save();
    }

    public function calculateEngagementScore()
    {
        $engagementLevels = $this->student_engagement_levels ?? [];

        if (empty($engagementLevels)) {
            return null;
        }

        $scoreMap = [
            'highly_engaged' => 5,
            'engaged' => 4,
            'moderately_engaged' => 3,
            'low_engagement' => 2,
            'disengaged' => 1,
        ];

        $totalScore = 0;
        $count = 0;

        foreach ($engagementLevels as $level => $studentCount) {
            if (isset($scoreMap[$level])) {
                $totalScore += $scoreMap[$level] * $studentCount;
                $count += $studentCount;
            }
        }

        return $count > 0 ? round($totalScore / $count, 1) : null;
    }

    public function getRecommendedImprovements()
    {
        $improvements = [];

        if ($this->effectiveness_rating && $this->effectiveness_rating < 3) {
            $improvements[] = 'Consider revising lesson structure';
            $improvements[] = 'Review material difficulty level';
        }

        if ($this->attendance_rate && $this->attendance_rate < 80) {
            $improvements[] = 'Investigate attendance issues';
        }

        $engagementScore = $this->calculateEngagementScore();
        if ($engagementScore && $engagementScore < 3.5) {
            $improvements[] = 'Add more interactive activities';
            $improvements[] = 'Vary teaching methods';
        }

        return $improvements;
    }

    public static function getTeacherStatistics($teacherId, $startDate = null, $endDate = null)
    {
        $query = self::where('teacher_id', $teacherId);

        if ($startDate && $endDate) {
            $query->whereBetween('planned_date', [$startDate, $endDate]);
        }

        return [
            'total_planned' => $query->count(),
            'completed' => $query->where('execution_status', 'completed')->count(),
            'completion_rate' => $query->count() > 0 ?
                round(($query->where('execution_status', 'completed')->count() / $query->count()) * 100, 1) : 0,
            'average_rating' => $query->whereNotNull('effectiveness_rating')->avg('effectiveness_rating'),
            'total_shared' => $query->where('shared_with_colleagues', true)->count(),
            'most_used_subject' => $query->groupBy('subject')
                ->selectRaw('subject, count(*) as count')
                ->orderByDesc('count')
                ->first()?->subject,
        ];
    }

    public function getDuplicateForNewDate($newDate)
    {
        $duplicate = $this->replicate();
        $duplicate->planned_date = $newDate;
        $duplicate->execution_status = 'planned';
        $duplicate->actual_date = null;
        $duplicate->teacher_reflection = null;
        $duplicate->challenges_faced = [];
        $duplicate->successes_noted = [];
        $duplicate->student_engagement_levels = [];
        $duplicate->objectives_achieved = [];
        $duplicate->improvements_for_next_time = null;
        $duplicate->reviewed_by = null;
        $duplicate->reviewed_at = null;
        $duplicate->review_comments = null;
        $duplicate->effectiveness_rating = null;

        return $duplicate;
    }
}
