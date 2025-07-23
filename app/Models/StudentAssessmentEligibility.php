<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssessmentEligibility extends Model
{
    use HasFactory;

    protected $table = 'student_assessment_eligibility';

    protected $fillable = [
        'student_id',
        'assessment_type',
        'selected_by',
        'is_eligible',
        'notes',
    ];

    protected $casts = [
        'is_eligible' => 'boolean',
    ];

    /**
     * Get the student for this eligibility record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who selected this student.
     */
    public function selector()
    {
        return $this->belongsTo(User::class, 'selected_by');
    }
}