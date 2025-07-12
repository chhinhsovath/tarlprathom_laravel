<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin', 'mentor', 'teacher', 'viewer'
        'school_id',
        'phone',
        'is_active',
        'profile_photo',
        'sex',
        'telephone',
        'holding_classes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the school that the user belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the students for the teacher.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    /**
     * Get the classes taught by the teacher.
     */
    public function teachingClasses()
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    /**
     * Get all students through the classes the teacher teaches.
     */
    public function studentsInClasses()
    {
        return $this->hasManyThrough(
            Student::class,
            SchoolClass::class,
            'teacher_id', // Foreign key on classes table
            'class_id',   // Foreign key on students table
            'id',         // Local key on users table
            'id'          // Local key on classes table
        );
    }

    /**
     * Get the assessments conducted by the user.
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'assessor_id');
    }

    /**
     * Get the mentoring visits conducted by the mentor.
     */
    public function mentoringVisitsAsMentor()
    {
        return $this->hasMany(MentoringVisit::class, 'mentor_id');
    }

    /**
     * Get the mentoring visits received as a teacher.
     */
    public function mentoringVisitsAsTeacher()
    {
        return $this->hasMany(MentoringVisit::class, 'teacher_id');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a mentor.
     */
    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a viewer.
     */
    public function isViewer()
    {
        return $this->role === 'viewer';
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include users of a specific role.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
