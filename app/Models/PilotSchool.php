<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotSchool extends Model
{
    use HasFactory;

    protected $table = 'pilot_schools';

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

    protected $casts = [
        'baseline_start_date' => 'date',
        'baseline_end_date' => 'date',
        'midline_start_date' => 'date',
        'midline_end_date' => 'date',
        'endline_start_date' => 'date',
        'endline_end_date' => 'date',
    ];

    /**
     * Relationships
     */

    /**
     * Get all users (teachers) associated with this school.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'pilot_school_id');
    }

    /**
     * Get all teachers associated with this school.
     */
    public function teachers()
    {
        return $this->hasMany(\App\Models\User::class, 'pilot_school_id')->where('role', 'teacher');
    }

    /**
     * Get all students in this school.
     */
    public function students()
    {
        return $this->hasMany(\App\Models\Student::class, 'pilot_school_id');
    }

    /**
     * Get all classes in this school.
     */
    public function classes()
    {
        return $this->hasMany(\App\Models\SchoolClass::class, 'pilot_school_id');
    }

    /**
     * Get the mentors assigned to this school (many-to-many relationship).
     */
    public function assignedMentors()
    {
        return $this->belongsToMany(\App\Models\User::class, 'mentor_school', 'school_id', 'user_id')
            ->where('role', 'mentor')
            ->withTimestamps();
    }

    /**
     * Get all mentoring visits for this school.
     */
    public function mentoringVisits()
    {
        return $this->hasMany(\App\Models\MentoringVisit::class, 'pilot_school_id');
    }

    /**
     * Get all assessments for students in this school.
     */
    public function assessments()
    {
        return $this->hasManyThrough(\App\Models\Assessment::class, \App\Models\Student::class, 'pilot_school_id', 'student_id');
    }

    /**
     * Get unique provinces from pilot schools
     */
    public static function getProvinces()
    {
        return self::select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');
    }

    /**
     * Get unique districts from pilot schools
     */
    public static function getDistricts($province = null)
    {
        $query = self::select('district')->distinct();

        if ($province) {
            $query->where('province', $province);
        }

        return $query->orderBy('district')->pluck('district');
    }

    /**
     * Get unique clusters from pilot schools
     */
    public static function getClusters($province = null, $district = null)
    {
        $query = self::select('cluster')->distinct();

        if ($province) {
            $query->where('province', $province);
        }

        if ($district) {
            $query->where('district', $district);
        }

        return $query->orderBy('cluster')->pluck('cluster');
    }

    /**
     * Get schools by filters
     */
    public static function getSchoolsByFilters($province = null, $district = null, $cluster = null)
    {
        $query = self::query();

        if ($province) {
            $query->where('province', $province);
        }

        if ($district) {
            $query->where('district', $district);
        }

        if ($cluster) {
            $query->where('cluster', $cluster);
        }

        return $query->orderBy('school_name')->get();
    }

    /**
     * Find school by code
     */
    public static function findByCode($schoolCode)
    {
        return self::where('school_code', $schoolCode)->first();
    }

    /**
     * Map to old School model fields for compatibility
     */
    public function getSclAutoIDAttribute()
    {
        return $this->id;
    }

    public function getSclNameAttribute()
    {
        return $this->school_name;
    }

    public function getSclCodeAttribute()
    {
        return $this->school_code;
    }

    public function getSclProvinceNameAttribute()
    {
        return $this->province;
    }

    public function getSclDistrictNameAttribute()
    {
        return $this->district;
    }

    public function getSclClusterNameAttribute()
    {
        return $this->cluster;
    }

    /**
     * Check if assessment period is active for a given cycle
     */
    public function isAssessmentPeriodActive($cycle)
    {
        $now = now();
        
        switch ($cycle) {
            case 'baseline':
                return $this->baseline_start_date && $this->baseline_end_date &&
                       $now->between($this->baseline_start_date, $this->baseline_end_date);
            case 'midline':
                return $this->midline_start_date && $this->midline_end_date &&
                       $now->between($this->midline_start_date, $this->midline_end_date);
            case 'endline':
                return $this->endline_start_date && $this->endline_end_date &&
                       $now->between($this->endline_start_date, $this->endline_end_date);
            default:
                return false;
        }
    }

    /**
     * Get assessment period status for a given cycle
     */
    public function getAssessmentPeriodStatus($cycle)
    {
        $now = now();
        
        switch ($cycle) {
            case 'baseline':
                if (!$this->baseline_start_date || !$this->baseline_end_date) {
                    return 'not_set';
                }
                if ($now->lt($this->baseline_start_date)) {
                    return 'upcoming';
                }
                if ($now->gt($this->baseline_end_date)) {
                    return 'ended';
                }
                return 'active';
                
            case 'midline':
                if (!$this->midline_start_date || !$this->midline_end_date) {
                    return 'not_set';
                }
                if ($now->lt($this->midline_start_date)) {
                    return 'upcoming';
                }
                if ($now->gt($this->midline_end_date)) {
                    return 'ended';
                }
                return 'active';
                
            case 'endline':
                if (!$this->endline_start_date || !$this->endline_end_date) {
                    return 'not_set';
                }
                if ($now->lt($this->endline_start_date)) {
                    return 'upcoming';
                }
                if ($now->gt($this->endline_end_date)) {
                    return 'ended';
                }
                return 'active';
                
            default:
                return 'not_set';
        }
    }
}
