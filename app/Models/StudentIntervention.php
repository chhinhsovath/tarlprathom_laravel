<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIntervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'intervention_program_id', 'referred_by', 'assigned_to',
        'enrollment_date', 'start_date', 'end_date', 'status', 'referral_reason',
        'baseline_data', 'progress_data', 'outcome_data', 'sessions_attended',
        'sessions_total', 'attendance_rate', 'progress_score', 'goal_achieved',
        'exit_reason', 'exit_date', 'post_intervention_plan', 'parent_consent',
        'parent_consent_date', 'parent_involvement', 'accommodations', 'modifications',
        'teacher_feedback', 'student_feedback', 'parent_feedback',
        'requires_follow_up', 'follow_up_date',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'exit_date' => 'date',
        'parent_consent_date' => 'date',
        'follow_up_date' => 'date',
        'baseline_data' => 'array',
        'progress_data' => 'array',
        'outcome_data' => 'array',
        'parent_involvement' => 'array',
        'accommodations' => 'array',
        'modifications' => 'array',
        'attendance_rate' => 'decimal:2',
        'progress_score' => 'decimal:2',
        'goal_achieved' => 'boolean',
        'parent_consent' => 'boolean',
        'requires_follow_up' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function interventionProgram()
    {
        return $this->belongsTo(InterventionProgram::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['enrolled', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'graduated']);
    }

    public function scopeNeedsFollowUp($query)
    {
        return $query->where('requires_follow_up', true)
            ->where('follow_up_date', '<=', now()->addDays(7));
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->status, ['enrolled', 'in_progress']);
    }

    public function getIsCompletedAttribute()
    {
        return in_array($this->status, ['completed', 'graduated']);
    }

    public function getDurationInWeeksAttribute()
    {
        if (! $this->start_date || ! $this->end_date) {
            return null;
        }

        return $this->start_date->diffInWeeks($this->end_date);
    }

    public function calculateAttendanceRate()
    {
        if (! $this->sessions_total || $this->sessions_total === 0) {
            return 0;
        }

        return round(($this->sessions_attended / $this->sessions_total) * 100, 2);
    }

    public function updateProgress($sessionNumber, $data)
    {
        $progressData = $this->progress_data ?? [];

        $progressData[] = [
            'session' => $sessionNumber,
            'date' => now()->toDateString(),
            'data' => $data,
            'recorded_by' => auth()->id(),
        ];

        $this->progress_data = $progressData;
        $this->sessions_attended = count($progressData);
        $this->attendance_rate = $this->calculateAttendanceRate();

        $this->save();
    }

    public function recordOutcome($data)
    {
        $this->outcome_data = array_merge($this->outcome_data ?? [], [
            'final_assessment' => $data,
            'recorded_at' => now()->toDateTimeString(),
            'recorded_by' => auth()->id(),
        ]);

        if (isset($data['goal_achieved'])) {
            $this->goal_achieved = $data['goal_achieved'];
        }

        $this->save();
    }

    public function graduate($reason = null)
    {
        $this->status = 'graduated';
        $this->exit_date = now();
        $this->exit_reason = $reason ?? 'Successfully completed program objectives';
        $this->save();
    }

    public function discontinue($reason)
    {
        $this->status = 'discontinued';
        $this->exit_date = now();
        $this->exit_reason = $reason;
        $this->save();
    }
}
