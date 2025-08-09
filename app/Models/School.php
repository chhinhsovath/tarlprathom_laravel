<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_tarl_schools';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'sclAutoID';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sclName',
        'sclCode',
        'sclCluster',
        'sclClusterName',
        'sclCommune',
        'sclDistrict',
        'sclProvince',
        'sclZone',
        'sclOrder',
        'sclStatus',
        'sclImage',
        'sclZoneName',
        'sclProvinceName',
        'sclDistrictName',
        'total_students',
        'total_teachers',
        'total_teachers_female',
        'total_students_female',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the id attribute (alias for sclAutoID).
     */
    public function getIdAttribute()
    {
        return $this->sclAutoID;
    }

    /**
     * Get the name attribute (alias for sclName).
     */
    public function getNameAttribute()
    {
        return $this->sclName;
    }

    /**
     * Set the name attribute (alias for sclName).
     */
    public function setNameAttribute($value)
    {
        $this->attributes['sclName'] = $value;
    }

    /**
     * Get the school_code attribute (alias for sclCode).
     */
    public function getSchoolCodeAttribute()
    {
        return $this->sclCode;
    }

    /**
     * Set the school_code attribute (alias for sclCode).
     */
    public function setSchoolCodeAttribute($value)
    {
        $this->attributes['sclCode'] = $value;
    }

    /**
     * Get the province attribute (alias for sclProvinceName).
     */
    public function getProvinceAttribute()
    {
        // If province name is empty, try to get it from Geographic table using province ID
        if (empty($this->sclProvinceName) && !empty($this->sclProvince)) {
            $geographic = Geographic::where('province_code', $this->sclProvince)
                ->whereNull('district_code')
                ->first();
            if ($geographic) {
                return $geographic->province_name_en;
            }
        }
        return $this->sclProvinceName;
    }

    /**
     * Set the province attribute (alias for sclProvinceName).
     */
    public function setProvinceAttribute($value)
    {
        $this->attributes['sclProvinceName'] = $value;
    }

    /**
     * Get the district attribute (alias for sclDistrictName).
     */
    public function getDistrictAttribute()
    {
        // If district name is empty, try to get it from Geographic table using district ID
        if (empty($this->sclDistrictName) && !empty($this->sclDistrict)) {
            $geographic = Geographic::where('district_code', $this->sclDistrict)
                ->whereNull('commune_code')
                ->first();
            if ($geographic) {
                return $geographic->district_name_en;
            }
        }
        return $this->sclDistrictName;
    }

    /**
     * Set the district attribute (alias for sclDistrictName).
     */
    public function setDistrictAttribute($value)
    {
        $this->attributes['sclDistrictName'] = $value;
    }

    /**
     * Get the cluster attribute (alias for sclClusterName).
     */
    public function getClusterAttribute()
    {
        return $this->sclClusterName ?? $this->sclCluster;
    }

    /**
     * Set the cluster attribute (alias for sclClusterName).
     */
    public function setClusterAttribute($value)
    {
        $this->attributes['sclClusterName'] = $value;
    }

    /**
     * Get the students for the school.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'school_id', 'sclAutoID');
    }

    /**
     * Get the mentoring visits for the school.
     */
    public function mentoringVisits()
    {
        return $this->hasMany(MentoringVisit::class, 'school_id', 'sclAutoID');
    }

    /**
     * Get the teachers (users with teacher role) for the school.
     */
    public function teachers()
    {
        return $this->hasMany(User::class, 'school_id', 'sclAutoID')->where('role', 'teacher');
    }

    /**
     * Get all users associated with the school.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'school_id', 'sclAutoID');
    }

    /**
     * Get the classes for the school.
     */
    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'school_id', 'sclAutoID');
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
     * Assessment period methods - these fields don't exist in tbl_tarl_schools
     * You may need to create a separate table for assessment dates or add these columns
     */
    
    /**
     * Check if an assessment type is currently active for this school.
     *
     * @param  string  $cycle
     * @return bool
     */
    public function isAssessmentPeriodActive($cycle)
    {
        // For now, return true to allow all assessments
        // You can implement this later with a separate assessment_dates table
        return true;
    }

    /**
     * Get the assessment period status for a given cycle.
     *
     * @param  string  $cycle
     * @return string 'not_set', 'upcoming', 'active', or 'expired'
     */
    public function getAssessmentPeriodStatus($cycle)
    {
        // For now, return 'active' to allow all assessments
        // You can implement this later with a separate assessment_dates table
        return 'active';
    }

    /**
     * Accessor for baseline_start_date (temporary - returns null)
     */
    public function getBaselineStartDateAttribute()
    {
        return null;
    }

    /**
     * Accessor for baseline_end_date (temporary - returns null)
     */
    public function getBaselineEndDateAttribute()
    {
        return null;
    }

    /**
     * Accessor for midline_start_date (temporary - returns null)
     */
    public function getMidlineStartDateAttribute()
    {
        return null;
    }

    /**
     * Accessor for midline_end_date (temporary - returns null)
     */
    public function getMidlineEndDateAttribute()
    {
        return null;
    }

    /**
     * Accessor for endline_start_date (temporary - returns null)
     */
    public function getEndlineStartDateAttribute()
    {
        return null;
    }

    /**
     * Accessor for endline_end_date (temporary - returns null)
     */
    public function getEndlineEndDateAttribute()
    {
        return null;
    }
}