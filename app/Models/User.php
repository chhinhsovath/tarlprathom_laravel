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
        'role', // 'admin', 'coordinator', 'mentor', 'teacher', 'viewer'
        'school_id',
        'province',
        'district',
        'commune',
        'village',
        'phone',
        'is_active',
        'profile_photo',
        'sex',
        'holding_classes',
        'assigned_subject',
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
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get the pilot school that the user belongs to.
     */
    public function pilotSchool()
    {
        return $this->belongsTo(PilotSchool::class, 'school_id');
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
     * Get the schools assigned to this mentor (many-to-many relationship).
     */
    public function assignedSchools()
    {
        return $this->belongsToMany(PilotSchool::class, 'mentor_school', 'user_id', 'school_id')
            ->withTimestamps();
    }

    /**
     * Get the pilot schools assigned to this mentor (many-to-many relationship).
     */
    public function assignedPilotSchools()
    {
        return $this->belongsToMany(PilotSchool::class, 'mentor_school', 'user_id', 'school_id')
            ->withTimestamps();
    }

    /**
     * Get accessible school codes for the user (for pilot schools).
     */
    public function getAccessibleSchoolCodes()
    {
        if ($this->isAdmin()) {
            // Admin can access all pilot schools
            return \App\Models\PilotSchool::pluck('school_code')->toArray();
        }

        if ($this->role === 'mentor') {
            // Get assigned school codes from pilot schools
            return $this->assignedPilotSchools()->pluck('school_code')->toArray();
        }

        if ($this->role === 'teacher' && $this->school_id) {
            // Teacher can only access their own school
            $school = PilotSchool::find($this->school_id);

            return $school ? [$school->school_code] : [];
        }

        return [];
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
     * Check if user is a coordinator.
     */
    public function isCoordinator()
    {
        return $this->role === 'coordinator';
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

    /**
     * Get all school IDs that this user can access.
     * For admins and coordinators, returns all schools. For mentors, returns assigned schools.
     */
    public function getAccessibleSchoolIds()
    {
        if ($this->isAdmin() || $this->isCoordinator()) {
            return PilotSchool::pluck('id')->toArray();
        }

        if ($this->isMentor()) {
            return $this->assignedPilotSchools()->pluck('pilot_schools.id')->toArray();
        }

        // Teachers only see their own school
        if ($this->isTeacher() && $this->school_id) {
            return [$this->school_id];
        }

        return [];
    }

    /**
     * Get all students that this user can access.
     * For mentors, returns students from assigned schools.
     */
    public function getAccessibleStudents()
    {
        $schoolIds = $this->getAccessibleSchoolIds();

        if (empty($schoolIds)) {
            return Student::whereRaw('1 = 0'); // Return empty query
        }

        return Student::whereIn('school_id', $schoolIds);
    }

    /**
     * Get all teachers that this mentor can access.
     * For mentors, returns teachers from assigned schools.
     */
    public function getAccessibleTeachers()
    {
        $schoolIds = $this->getAccessibleSchoolIds();

        if (empty($schoolIds)) {
            return User::whereRaw('1 = 0'); // Return empty query
        }

        return User::where('role', 'teacher')->whereIn('school_id', $schoolIds);
    }

    /**
     * Check if user can access a specific school.
     */
    public function canAccessSchool($schoolId)
    {
        return in_array($schoolId, $this->getAccessibleSchoolIds());
    }

    /**
     * Check if user can access a specific student.
     */
    public function canAccessStudent($studentId)
    {
        $student = Student::find($studentId);

        return $student && $this->canAccessSchool($student->school_id);
    }
}
