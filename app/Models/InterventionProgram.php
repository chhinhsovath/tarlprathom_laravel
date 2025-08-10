<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name', 'description', 'type', 'intensity', 'target_criteria',
        'objectives', 'duration_weeks', 'sessions_per_week', 'minutes_per_session',
        'delivery_method', 'materials_needed', 'success_metrics', 'success_threshold',
        'implementation_steps', 'created_by', 'is_active', 'start_date', 'end_date',
    ];

    protected $casts = [
        'target_criteria' => 'array',
        'objectives' => 'array',
        'materials_needed' => 'array',
        'success_metrics' => 'array',
        'implementation_steps' => 'array',
        'success_threshold' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studentInterventions()
    {
        return $this->hasMany(StudentIntervention::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByIntensity($query, $intensity)
    {
        return $query->where('intensity', $intensity);
    }

    public function getTotalSessionsAttribute()
    {
        if (! $this->duration_weeks || ! $this->sessions_per_week) {
            return null;
        }

        return $this->duration_weeks * $this->sessions_per_week;
    }

    public function getTotalHoursAttribute()
    {
        if (! $this->total_sessions || ! $this->minutes_per_session) {
            return null;
        }

        return round($this->total_sessions * $this->minutes_per_session / 60, 1);
    }

    public function getActiveStudentsCount()
    {
        return $this->studentInterventions()
            ->whereIn('status', ['enrolled', 'in_progress'])
            ->count();
    }

    public function getSuccessRate()
    {
        $completed = $this->studentInterventions()
            ->whereIn('status', ['completed', 'graduated'])
            ->count();

        $total = $this->studentInterventions()
            ->whereIn('status', ['completed', 'graduated', 'discontinued'])
            ->count();

        if ($total === 0) {
            return 0;
        }

        return round(($completed / $total) * 100, 2);
    }

    public function getAverageProgressScore()
    {
        return $this->studentInterventions()
            ->whereNotNull('progress_score')
            ->avg('progress_score') ?? 0;
    }

    public function isEligibleStudent($student)
    {
        if (! $this->target_criteria) {
            return true;
        }

        foreach ($this->target_criteria as $criterion) {
            switch ($criterion['type']) {
                case 'grade_level':
                    if ($student->grade !== $criterion['value']) {
                        return false;
                    }
                    break;
                case 'assessment_score':
                    $score = $student->assessments()
                        ->where('subject', $criterion['subject'])
                        ->where('cycle', $criterion['cycle'])
                        ->value('score');

                    if (! $this->evaluateCriterion($score, $criterion['operator'], $criterion['value'])) {
                        return false;
                    }
                    break;
                case 'attendance_rate':
                    $rate = AttendanceRecord::calculateAttendanceRate($student->id);

                    if (! $this->evaluateCriterion($rate, $criterion['operator'], $criterion['value'])) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    private function evaluateCriterion($value, $operator, $threshold)
    {
        switch ($operator) {
            case '<':
                return $value < $threshold;
            case '<=':
                return $value <= $threshold;
            case '>':
                return $value > $threshold;
            case '>=':
                return $value >= $threshold;
            case '=':
                return $value == $threshold;
            default:
                return false;
        }
    }
}
