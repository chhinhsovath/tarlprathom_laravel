<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sex',
        'gender',
        'grade',
        'age',
        'class',
        'school_id',
        'teacher_id',
        'class_id',
        'photo',
    ];

    /**
     * Get the school that the student belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the teacher that the student belongs to.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the class that the student belongs to.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the assessments for the student.
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
