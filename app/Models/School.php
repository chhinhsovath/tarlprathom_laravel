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
}
