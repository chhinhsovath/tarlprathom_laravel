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
        'student_code',
        'date_of_birth',
        'nationality',
        'ethnicity',
        'religion',
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'guardian_occupation',
        'home_address',
        'home_village',
        'home_commune',
        'home_district',
        'home_province',
        'distance_from_school',
        'transportation_method',
        'has_disability',
        'disability_type',
        'receives_scholarship',
        'scholarship_amount',
        'enrollment_date',
        'enrollment_status',
        'status_change_date',
        'status_change_reason',
        'previous_year_grade',
        'previous_school',
        'attendance_rate',
        'days_absent',
        'health_conditions',
        'last_health_check',
        'height_cm',
        'weight_kg',
        'nutrition_status',
        'receives_meal_support',
        'learning_difficulties',
        'special_needs',
        'teacher_notes',
        'extra_activities',
        'achievements',
        'siblings_count',
        'sibling_position',
        'family_income_level',
        'has_birth_certificate',
        'birth_certificate_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'status_change_date' => 'date',
        'last_health_check' => 'date',
        'has_disability' => 'boolean',
        'receives_scholarship' => 'boolean',
        'receives_meal_support' => 'boolean',
        'has_birth_certificate' => 'boolean',
        'health_conditions' => 'array',
        'learning_difficulties' => 'array',
        'special_needs' => 'array',
        'extra_activities' => 'array',
        'achievements' => 'array',
        'scholarship_amount' => 'decimal:2',
        'distance_from_school' => 'decimal:2',
        'attendance_rate' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'weight_kg' => 'decimal:2',
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

    /**
     * Get the assessment eligibility records for the student.
     */
    public function assessmentEligibility()
    {
        return $this->hasMany(\App\Models\StudentAssessmentEligibility::class);
    }

    /**
     * Check if student is eligible for a specific assessment type.
     */
    public function isEligibleForAssessment($assessmentType)
    {
        if ($assessmentType === 'baseline') {
            // All students are eligible for baseline
            return true;
        }

        $eligibility = $this->assessmentEligibility()
            ->where('assessment_type', $assessmentType)
            ->where('is_eligible', true)
            ->first();

        return $eligibility !== null;
    }

    /**
     * Get attendance records for the student.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get learning outcomes for the student.
     */
    public function learningOutcomes()
    {
        return $this->hasMany(StudentLearningOutcome::class);
    }

    /**
     * Get intervention programs for the student.
     */
    public function interventions()
    {
        return $this->hasMany(StudentIntervention::class);
    }

    /**
     * Get progress tracking records for the student.
     */
    public function progressTracking()
    {
        return $this->hasMany(ProgressTracking::class);
    }

    /**
     * Scope for active students.
     */
    public function scopeActive($query)
    {
        return $query->where('enrollment_status', 'active');
    }

    /**
     * Scope for students with disabilities.
     */
    public function scopeWithDisabilities($query)
    {
        return $query->where('has_disability', true);
    }

    /**
     * Scope for scholarship recipients.
     */
    public function scopeScholarshipRecipients($query)
    {
        return $query->where('receives_scholarship', true);
    }

    /**
     * Calculate BMI.
     */
    public function getBmiAttribute()
    {
        if (!$this->height_cm || !$this->weight_kg) {
            return null;
        }
        
        $heightInMeters = $this->height_cm / 100;
        return round($this->weight_kg / ($heightInMeters * $heightInMeters), 2);
    }

    /**
     * Get age from date of birth.
     */
    public function getCalculatedAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return $this->age;
        }
        
        return $this->date_of_birth->age;
    }

    /**
     * Get current attendance rate.
     */
    public function getCurrentAttendanceRate()
    {
        return AttendanceRecord::calculateAttendanceRate($this->id);
    }

    /**
     * Get current academic performance.
     */
    public function getAcademicPerformance($subject = null)
    {
        $query = $this->assessments()->where('cycle', 'endline');
        
        if ($subject) {
            $query->where('subject', $subject);
        }
        
        return $query->avg('percentage_score') ?? 0;
    }

    /**
     * Check if student needs intervention.
     */
    public function needsIntervention()
    {
        $attendanceRate = $this->getCurrentAttendanceRate();
        $academicPerformance = $this->getAcademicPerformance();
        
        return $attendanceRate < 80 || $academicPerformance < 60;
    }
}
