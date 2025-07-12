<?php

return [
    'questionnaire' => [
        // Part 1: Basic Visit Details
        'visit_details' => [
            'date_of_visit' => [
                'type' => 'date',
                'required' => true,
            ],
            'region' => [
                'type' => 'text',
                'required' => true,
            ],
            'province' => [
                'type' => 'text',
                'required' => true,
            ],
            'mentor_name' => [
                'type' => 'select',
                'required' => true,
                'source' => 'mentors',
            ],
            'school_name' => [
                'type' => 'select',
                'required' => true,
                'source' => 'schools',
            ],
            'program_type' => [
                'type' => 'text',
                'required' => true,
                'default' => 'TaRL',
            ],
        ],

        // Part 2: Program Type / Class Status
        'program_type_questions' => [
            'class_taking_place' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Is the TaRL class taking place on the day of the visit?',
            ],
            'class_not_taking_place_reason' => [
                'type' => 'select',
                'options' => [
                    'Teacher is Absent',
                    'Most students are absent',
                    'The students have exams',
                    'The school has declared a holiday',
                    'Others',
                ],
                'condition' => 'class_taking_place:No',
                'question' => 'Why is the TaRL class not taking place?',
            ],
        ],

        // Part 3: Teacher and Observation Details
        'teacher_observation' => [
            'teacher_name' => [
                'type' => 'select',
                'required' => true,
                'source' => 'teachers',
                'question' => 'Name of Teacher',
            ],
            'full_session_observed' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Did you observe the full session?',
            ],
            'grade_group' => [
                'type' => 'select',
                'options' => ['Std. 1-2', 'Std. 3-6'],
                'required' => true,
                'question' => 'Grade Group',
            ],
            'grades_observed' => [
                'type' => 'checkbox',
                'options' => ['1', '2', '3', '4', '5', '6'],
                'required' => true,
                'question' => 'Grade(s) Observed',
            ],
            'subject_observed' => [
                'type' => 'select',
                'options' => ['Language', 'Numeracy'],
                'required' => true,
                'question' => 'Subject Observed',
            ],
            'language_levels' => [
                'type' => 'checkbox',
                'options' => [
                    'Beginner',
                    'Letter Level',
                    'Word Level',
                    'Paragraph Reader',
                    'Story Reader',
                ],
                'condition' => 'subject_observed:Language',
                'question' => 'TaRL Level(s) observed (Language)',
            ],
            'numeracy_levels' => [
                'type' => 'checkbox',
                'options' => [
                    'Beginner',
                    '1-Digit',
                    '2-Digit',
                    'Subtraction',
                    'Division',
                ],
                'condition' => 'subject_observed:Numeracy',
                'question' => 'TaRL Level(s) observed (Numeracy)',
            ],
        ],

        // Part 4: Delivery Questions
        'delivery_questions' => [
            'class_started_on_time' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Did the class start on time (i.e. within 5 minutes of the scheduled time)?',
            ],
            'late_start_reason' => [
                'type' => 'select',
                'options' => [
                    'Teacher came late',
                    'Pupils came late',
                    'Others',
                ],
                'condition' => 'class_started_on_time:No',
                'question' => 'If no, then why did the class not start on time?',
            ],
        ],

        // Part 5: Classroom Related Questions
        'classroom_questions' => [
            'materials_present' => [
                'type' => 'checkbox',
                'options' => [
                    'TaRL materials',
                    'Teaching aids',
                    'Student notebooks',
                    'Reading materials',
                    'Math manipulatives',
                    'Flash cards',
                    'Number charts',
                    'Letter charts',
                    'Story books',
                    'Activity sheets',
                ],
                'question' => 'Which materials did you see present in the classroom?',
            ],
            'children_grouped_appropriately' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Were the children grouped appropriately?',
            ],
            'students_fully_involved' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Were the students fully involved in the activities?',
            ],
        ],

        // Part 6: Teacher Related Questions
        'teacher_questions' => [
            'has_session_plan' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Did the teacher have a session plan?',
            ],
            'no_session_plan_reason' => [
                'type' => 'text',
                'condition' => 'has_session_plan:No',
                'question' => 'Why did the teacher not have a session plan?',
            ],
            'followed_session_plan' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Did the teacher follow the session plan?',
                'condition' => 'has_session_plan:Yes',
            ],
            'no_follow_plan_reason' => [
                'type' => 'text',
                'condition' => 'followed_session_plan:No',
                'question' => 'Why did the teacher not follow the session plan?',
            ],
            'session_plan_appropriate' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Was the session plan appropriate for the children\'s learning level?',
                'condition' => 'has_session_plan:Yes',
            ],
        ],

        // Part 7: Activity Related Questions
        'activity_overview' => [
            'number_of_activities' => [
                'type' => 'select',
                'options' => ['1', '2', '3'],
                'required' => true,
                'question' => 'How many activities were conducted?',
            ],
        ],

        // Activity 1 Details
        'activity_1' => [
            'activity1_name_language' => [
                'type' => 'select',
                'options' => [
                    'Letter recognition',
                    'Letter writing',
                    'Word building',
                    'Word reading',
                    'Sentence reading',
                    'Story reading',
                    'Comprehension activities',
                    'Vocabulary games',
                    'Phonics activities',
                    'Others',
                ],
                'condition' => 'subject_observed:Language',
                'question' => 'Which was the first activity conducted? (Language)',
            ],
            'activity1_name_numeracy' => [
                'type' => 'select',
                'options' => [
                    'Number chart reading activity',
                    'Recognition of the numbers with symbol and objects',
                    'Puzzles',
                    'Number Jump',
                    'Basket game',
                    'Clap and Snap',
                    'What next - Count Before / Count After',
                    'Number line - Counting and find the numbers move to left and move to right',
                    'Fine with Nine',
                    'Place value - Bundle and sticks with numbers up to 20',
                    'Making bundles and counting 10, 20, 30, 40, …',
                    'Learning two digit numbers with bundle and sticks',
                    'Addition of single digit numbers with sticks and without any material by using frame and word problems',
                    'Subtraction of single digit numbers with sticks and without any material, by using and word problems',
                    'Word problem of two digit number of addition and subtraction without any material',
                    'Who is my third partner - with numbers 1 to 9',
                ],
                'condition' => 'subject_observed:Numeracy',
                'question' => 'Which was the first activity conducted? (Numeracy)',
            ],
            'activity1_duration' => [
                'type' => 'number',
                'required' => true,
                'question' => 'What was the duration of the first activity? (Mins)',
            ],
            'activity1_clear_instructions' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Did the teacher give clear instructions for the first activity?',
            ],
            'activity1_no_clear_instructions_reason' => [
                'type' => 'text',
                'condition' => 'activity1_clear_instructions:No',
                'question' => 'Why did the teacher not give clear instructions for the first activity?',
            ],
            'activity1_demonstrated' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Did the teacher demonstrate the first activity?',
            ],
            'activity1_students_practice' => [
                'type' => 'radio',
                'options' => ['Yes', 'No', 'Not applicable'],
                'required' => true,
                'question' => 'Did the teacher make a few students practice the first activity in front of the whole class?',
            ],
            'activity1_small_groups' => [
                'type' => 'radio',
                'options' => ['Yes', 'No', 'Not Applicable'],
                'required' => true,
                'question' => 'Did the students perform the first activity in small groups?',
            ],
            'activity1_individual' => [
                'type' => 'radio',
                'options' => ['Yes', 'No', 'Not Applicable'],
                'required' => true,
                'question' => 'Did the students individually perform the first activity?',
            ],
        ],

        // Activity 2 Details
        'activity_2' => [
            'activity2_name_language' => [
                'type' => 'select',
                'options' => [
                    'Letter recognition',
                    'Letter writing',
                    'Word building',
                    'Word reading',
                    'Sentence reading',
                    'Story reading',
                    'Comprehension activities',
                    'Vocabulary games',
                    'Phonics activities',
                    'Others',
                ],
                'condition' => 'subject_observed:Language,number_of_activities:2|3',
                'question' => 'Which was the second activity conducted? (Language)',
            ],
            'activity2_name_numeracy' => [
                'type' => 'select',
                'options' => [
                    'Number chart reading activity',
                    'Recognition of the numbers with symbol and objects',
                    'Puzzles',
                    'Number Jump',
                    'Basket game',
                    'Clap and Snap',
                    'What next - Count Before / Count After',
                    'Number line - Counting and find the numbers move to left and move to right',
                    'Fine with Nine',
                    'Place value - Bundle and sticks with numbers up to 20',
                    'Making bundles and counting 10, 20, 30, 40, …',
                    'Learning two digit numbers with bundle and sticks',
                    'Addition of single digit numbers with sticks and without any material by using frame and word problems',
                    'Subtraction of single digit numbers with sticks and without any material, by using and word problems',
                    'Word problem of two digit number of addition and subtraction without any material',
                    'Who is my third partner - with numbers 1 to 9',
                ],
                'condition' => 'subject_observed:Numeracy,number_of_activities:2|3',
                'question' => 'Which was the second activity conducted? (Numeracy)',
            ],
            'activity2_duration' => [
                'type' => 'number',
                'condition' => 'number_of_activities:2|3',
                'question' => 'What was the duration of the second activity? (Mins)',
            ],
            'activity2_clear_instructions' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:2|3',
                'question' => 'Did the teacher give clear instructions for the second activity?',
            ],
            'activity2_no_clear_instructions_reason' => [
                'type' => 'text',
                'condition' => 'activity2_clear_instructions:No',
                'question' => 'Why did the teacher not give clear instructions for the second activity?',
            ],
            'activity2_demonstrated' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:2|3',
                'question' => 'Did the teacher demonstrate the second activity?',
            ],
            'activity2_students_practice' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:2|3',
                'question' => 'Did the teacher make a few students practice the second activity in front of the whole class?',
            ],
            'activity2_small_groups' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:2|3',
                'question' => 'Did the students perform the second activity in small groups?',
            ],
            'activity2_individual' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:2|3',
                'question' => 'Did the students individually perform the second activity?',
            ],
        ],

        // Activity 3 Details
        'activity_3' => [
            'activity3_name_language' => [
                'type' => 'select',
                'options' => [
                    'Letter recognition',
                    'Letter writing',
                    'Word building',
                    'Word reading',
                    'Sentence reading',
                    'Story reading',
                    'Comprehension activities',
                    'Vocabulary games',
                    'Phonics activities',
                    'Others',
                ],
                'condition' => 'subject_observed:Language,number_of_activities:3',
                'question' => 'Which was the third activity conducted? (Language)',
            ],
            'activity3_name_numeracy' => [
                'type' => 'select',
                'options' => [
                    'Number chart reading activity',
                    'Recognition of the numbers with symbol and objects',
                    'Puzzles',
                    'Number Jump',
                    'Basket game',
                    'Clap and Snap',
                    'What next - Count Before / Count After',
                    'Number line - Counting and find the numbers move to left and move to right',
                    'Fine with Nine',
                    'Place value - Bundle and sticks with numbers up to 20',
                    'Making bundles and counting 10, 20, 30, 40, …',
                    'Learning two digit numbers with bundle and sticks',
                    'Addition of single digit numbers with sticks and without any material by using frame and word problems',
                    'Subtraction of single digit numbers with sticks and without any material, by using and word problems',
                    'Word problem of two digit number of addition and subtraction without any material',
                    'Who is my third partner - with numbers 1 to 9',
                ],
                'condition' => 'subject_observed:Numeracy,number_of_activities:3',
                'question' => 'Which was the third activity conducted? (Numeracy)',
            ],
            'activity3_duration' => [
                'type' => 'number',
                'condition' => 'number_of_activities:3',
                'question' => 'What was the duration of the third activity? (Mins)',
            ],
            'activity3_clear_instructions' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:3',
                'question' => 'Did the teacher give clear instructions for the third activity?',
            ],
            'activity3_no_clear_instructions_reason' => [
                'type' => 'text',
                'condition' => 'activity3_clear_instructions:No',
                'question' => 'Why did the teacher not give clear instructions for the third activity?',
            ],
            'activity3_demonstrated' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'condition' => 'number_of_activities:3',
                'question' => 'Did the teacher demonstrate the activity?',
            ],
            'activity3_students_practice' => [
                'type' => 'radio',
                'options' => ['Yes', 'No', 'Not Applicable'],
                'condition' => 'number_of_activities:3',
                'question' => 'Did the teacher make a few students practice the third activity in front of the whole class?',
            ],
            'activity3_small_groups' => [
                'type' => 'radio',
                'options' => ['Yes', 'No', 'Not Applicable'],
                'condition' => 'number_of_activities:3',
                'question' => 'Did the students perform the third activity in small groups?',
            ],
            'activity3_individual' => [
                'type' => 'radio',
                'options' => ['Yes', 'No', 'Not Applicable'],
                'condition' => 'number_of_activities:3',
                'question' => 'Did the students individually perform the third activity?',
            ],
        ],

        // Additional fields for general observations
        'additional_observations' => [
            'overall_observation' => [
                'type' => 'textarea',
                'required' => false,
                'question' => 'General observations about the visit',
            ],
            'action_plan' => [
                'type' => 'textarea',
                'required' => false,
                'question' => 'Action plan and recommendations',
            ],
            'follow_up_required' => [
                'type' => 'radio',
                'options' => ['Yes', 'No'],
                'required' => true,
                'question' => 'Is follow-up required?',
            ],
            'photo' => [
                'type' => 'file',
                'accept' => 'image/*',
                'required' => false,
                'question' => 'Upload photo from visit',
            ],
        ],
    ],

    // Score calculation logic
    'scoring' => [
        'max_score' => 100,
        'sections' => [
            'class_preparation' => 20,
            'teaching_methodology' => 30,
            'student_engagement' => 25,
            'materials_usage' => 15,
            'overall_effectiveness' => 10,
        ],
    ],
];
