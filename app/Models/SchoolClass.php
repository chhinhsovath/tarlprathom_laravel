<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'grade',
        'grade_level',
        'school_id',
        'pilot_school_id',
        'teacher_id',
        'section',
        'academic_year',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'grade' => 'integer',
    ];

    /**
     * Get the school that owns the class.
     */
    public function school()
    {
        return $this->belongsTo(PilotSchool::class, 'pilot_school_id');
    }

    /**
     * Get the teacher assigned to this class.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the students in this class.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Scope a query to only include active classes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include classes for a specific school.
     */
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('pilot_school_id', $schoolId);
    }

    /**
     * Scope a query to only include classes for a specific teacher.
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Get the grade_level attribute (alias for grade).
     *
     * @return int|null
     */
    public function getGradeLevelAttribute()
    {
        return $this->grade;
    }

    /**
     * Set the grade_level attribute (alias for grade).
     *
     * @param  int  $value
     * @return void
     */
    public function setGradeLevelAttribute($value)
    {
        $this->attributes['grade'] = $value;
    }

    /**
     * Get the full name with grade level.
     */
    public function getFullNameAttribute()
    {
        return "Grade {$this->grade} - {$this->name}";
    }
}
