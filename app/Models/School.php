<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'province',
        'district',
        'cluster',
        'school_name',
        'school_code',
        'baseline_start_date',
        'baseline_end_date',
        'midline_start_date',
        'midline_end_date',
        'endline_start_date',
        'endline_end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'baseline_start_date' => 'date',
        'baseline_end_date' => 'date',
        'midline_start_date' => 'date',
        'midline_end_date' => 'date',
        'endline_start_date' => 'date',
        'endline_end_date' => 'date',
    ];

    /**
     * Get the students for the school.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the mentoring visits for the school.
     */
    public function mentoringVisits()
    {
        return $this->hasMany(MentoringVisit::class);
    }

    /**
     * Get the teachers (users with teacher role) for the school.
     */
    public function teachers()
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    /**
     * Get all users associated with the school.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the classes for the school.
     */
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * Get the mentors assigned to this school (many-to-many relationship).
     */
    public function assignedMentors()
    {
        return $this->belongsToMany(User::class, 'mentor_school', 'school_id', 'user_id')
            ->where('role', 'mentor')
            ->withTimestamps();
    }

    /**
     * Check if an assessment type is currently active for this school.
     *
     * @param  string  $cycle
     * @return bool
     */
    public function isAssessmentPeriodActive($cycle)
    {
        $today = now()->startOfDay();

        switch ($cycle) {
            case 'baseline':
                return $this->baseline_start_date && $this->baseline_end_date
                    && $today->between($this->baseline_start_date, $this->baseline_end_date);
            case 'midline':
                return $this->midline_start_date && $this->midline_end_date
                    && $today->between($this->midline_start_date, $this->midline_end_date);
            case 'endline':
                return $this->endline_start_date && $this->endline_end_date
                    && $today->between($this->endline_start_date, $this->endline_end_date);
            default:
                return false;
        }
    }

    /**
     * Get the assessment period status for a given cycle.
     *
     * @param  string  $cycle
     * @return string 'not_set', 'upcoming', 'active', or 'expired'
     */
    public function getAssessmentPeriodStatus($cycle)
    {
        $today = now()->startOfDay();
        $startDate = null;
        $endDate = null;

        switch ($cycle) {
            case 'baseline':
                $startDate = $this->baseline_start_date;
                $endDate = $this->baseline_end_date;
                break;
            case 'midline':
                $startDate = $this->midline_start_date;
                $endDate = $this->midline_end_date;
                break;
            case 'endline':
                $startDate = $this->endline_start_date;
                $endDate = $this->endline_end_date;
                break;
        }

        if (! $startDate || ! $endDate) {
            return 'not_set';
        }

        if ($today->lt($startDate)) {
            return 'upcoming';
        }

        if ($today->between($startDate, $endDate)) {
            return 'active';
        }

        return 'expired';
    }
}
