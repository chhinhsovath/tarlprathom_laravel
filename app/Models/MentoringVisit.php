<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentoringVisit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mentor_id',
        'school_id',
        'pilot_school_id',
        'teacher_id',
        'visit_date',
        'observation',
        'score',
        'photo',
        'action_plan',
        'follow_up_required',
        'questionnaire_data',
        'region',
        'cluster',
        'mentor_name',
        'program_type',
        'class_in_session',
        'class_not_in_session_reason',
        'full_session_observed',
        'grade_group',
        'grades_observed',
        'subject_observed',
        'language_levels_observed',
        'numeracy_levels_observed',
        'total_students_enrolled',
        'students_present',
        'students_improved_from_last_week',
        'classes_conducted_before_visit',
        // Delivery questions
        'class_started_on_time',
        'late_start_reason',
        // Classroom questions
        'materials_present',
        'children_grouped_appropriately',
        'students_fully_involved',
        // Teacher questions
        'has_session_plan',
        'no_session_plan_reason',
        'followed_session_plan',
        'no_follow_plan_reason',
        'session_plan_appropriate',
        // Activity overview
        'number_of_activities',
        // Activity 1
        'activity1_name_language',
        'activity1_name_numeracy',
        'activity1_duration',
        'activity1_clear_instructions',
        'activity1_no_clear_instructions_reason',
        'activity1_demonstrated',
        'activity1_followed_process',
        'activity1_not_followed_reason',
        'activity1_students_practice',
        'activity1_small_groups',
        'activity1_individual',
        // Activity 2
        'activity2_name_language',
        'activity2_name_numeracy',
        'activity2_duration',
        'activity2_clear_instructions',
        'activity2_no_clear_instructions_reason',
        'activity2_demonstrated',
        'activity2_followed_process',
        'activity2_not_followed_reason',
        'activity2_students_practice',
        'activity2_small_groups',
        'activity2_individual',
        // Activity 3
        'activity3_name_language',
        'activity3_name_numeracy',
        'activity3_duration',
        'activity3_clear_instructions',
        'activity3_no_clear_instructions_reason',
        'activity3_demonstrated',
        'activity3_students_practice',
        'activity3_small_groups',
        'activity3_individual',
        'feedback_for_teacher',
        'is_locked',
        'locked_by',
        'locked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visit_date' => 'date',
        'score' => 'integer',
        'follow_up_required' => 'boolean',
        'class_in_session' => 'boolean',
        'full_session_observed' => 'boolean',
        'questionnaire_data' => 'array',
        'grades_observed' => 'array',
        'language_levels_observed' => 'array',
        'numeracy_levels_observed' => 'array',
        'materials_present' => 'array',
        'total_students_enrolled' => 'integer',
        'students_present' => 'integer',
        'students_improved_from_last_week' => 'integer',
        'classes_conducted_before_visit' => 'integer',
        'class_started_on_time' => 'boolean',
        'children_grouped_appropriately' => 'boolean',
        'students_fully_involved' => 'boolean',
        'has_session_plan' => 'boolean',
        'followed_session_plan' => 'boolean',
        'session_plan_appropriate' => 'boolean',
        'number_of_activities' => 'integer',
        'activity1_duration' => 'integer',
        'activity1_clear_instructions' => 'boolean',
        'activity1_demonstrated' => 'boolean',
        'activity1_followed_process' => 'boolean',
        'activity2_duration' => 'integer',
        'activity2_clear_instructions' => 'boolean',
        'activity2_demonstrated' => 'boolean',
        'activity2_followed_process' => 'boolean',
        'activity3_duration' => 'integer',
        'activity3_clear_instructions' => 'boolean',
        'activity3_demonstrated' => 'boolean',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    /**
     * Get the mentor (user) who conducted the visit.
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Get the pilot school where the visit took place (primary relationship).
     */
    public function pilotSchool()
    {
        return $this->belongsTo(PilotSchool::class, 'pilot_school_id');
    }

    /**
     * Get the school through pilot school relationship.
     * This provides a consistent way to access school information.
     */
    public function school()
    {
        // Always use pilot school as the primary relationship
        return $this->pilotSchool();
    }

    /**
     * Get the school name from pilot school.
     */
    public function getSchoolNameAttribute()
    {
        if ($this->pilotSchool) {
            return $this->pilotSchool->school_name;
        }

        return 'Unknown School';
    }

    /**
     * Get the teacher who was mentored.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the user who locked this mentoring visit.
     */
    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Get the questionnaire structure
     */
    public static function getQuestionnaireStructure()
    {
        return [
            [
                'group' => 'Visit Details',
                'question' => 'Date of Visit',
                'field' => 'visit_date',
                'type' => 'date',
                'options' => null,
            ],
            [
                'group' => 'Visit Details',
                'question' => 'Region',
                'field' => 'region',
                'type' => 'text',
                'options' => null,
            ],
            [
                'group' => 'Visit Details',
                'question' => 'Name of Mentor',
                'field' => 'mentor_id',
                'type' => 'select',
                'options' => null,
            ],
            [
                'group' => 'Visit Details',
                'question' => 'School Name',
                'field' => 'school_id',
                'type' => 'select',
                'options' => null,
            ],
            [
                'group' => 'Visit Details',
                'question' => 'Program Type',
                'field' => 'program_type',
                'type' => 'text',
                'options' => null,
            ],
            [
                'group' => 'Program Type',
                'question' => 'Is the TaRL class taking place on the day of the visit?',
                'field' => 'class_in_session',
                'type' => 'radio',
                'options' => ['Yes', 'No'],
            ],
            [
                'group' => 'Program Type',
                'question' => 'Why is the TaRL class not taking place?',
                'field' => 'class_not_in_session_reason',
                'type' => 'select',
                'condition' => 'class_in_session:No',
                'options' => [
                    'Teacher is Absent',
                    'Most students are absent',
                    'The students have exams',
                    'The school has declared a holiday',
                    'Others',
                ],
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'Name of Teacher',
                'field' => 'teacher_id',
                'type' => 'select',
                'options' => null,
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'Did you observe the full session?',
                'field' => 'full_session_observed',
                'type' => 'radio',
                'options' => ['Yes', 'No'],
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'Grade Group',
                'field' => 'grade_group',
                'type' => 'select',
                'options' => ['Std. 1-2', 'Std. 3-6'],
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'Grade(s) Observed',
                'field' => 'grades_observed',
                'type' => 'checkbox',
                'options' => ['1', '2', '3', '4', '5', '6'],
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'Subject Observed',
                'field' => 'subject_observed',
                'type' => 'select',
                'options' => ['Language', 'Numeracy'],
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'TaRL Level(s) observed (Language)',
                'field' => 'language_levels_observed',
                'type' => 'checkbox',
                'condition' => 'subject_observed:Language',
                'options' => ['Beginner', 'Letter Level', 'Word', 'Paragraph', 'Story'],
            ],
            [
                'group' => 'Name of Teacher',
                'question' => 'TaRL Level(s) observed (Numeracy)',
                'field' => 'numeracy_levels_observed',
                'type' => 'checkbox',
                'condition' => 'subject_observed:Numeracy',
                'options' => ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'],
            ],
        ];
    }

    /**
     * Get dynamic questionnaire data
     */
    public function getQuestionnaireAttribute()
    {
        return $this->questionnaire_data ?? [];
    }

    /**
     * Set questionnaire data
     */
    public function setQuestionnaireAttribute($value)
    {
        $this->attributes['questionnaire_data'] = json_encode($value);
    }
}
