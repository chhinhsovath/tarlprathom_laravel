<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines - Khmer
    |--------------------------------------------------------------------------
    */

    'required' => ':attribute ត្រូវតែបំពេញ។',
    'email' => ':attribute មិនត្រឹមត្រូវទេ។',
    'min' => [
        'string' => ':attribute ត្រូវតែមានយ៉ាងតិច :min តួអក្សរ។',
        'numeric' => ':attribute ត្រូវតែយ៉ាងតិច :min។',
    ],
    'max' => [
        'string' => ':attribute មិនត្រូវលើសពី :max តួអក្សរទេ។',
        'numeric' => ':attribute មិនត្រូវលើសពី :max ទេ។',
    ],
    'confirmed' => 'ការបញ្ជាក់ :attribute មិនត្រូវគ្នាទេ។',
    'unique' => ':attribute ត្រូវបានប្រើរួចហើយ។',
    'exists' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
    'in' => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
    'date' => ':attribute មិនមែនជាកាលបរិច្ឆេទត្រឹមត្រូវទេ។',
    'integer' => ':attribute ត្រូវតែជាលេខគត់។',
    'numeric' => ':attribute ត្រូវតែជាលេខ។',
    'string' => ':attribute ត្រូវតែជាអក្សរ។',
    'array' => ':attribute ត្រូវតែជាបញ្ជី។',
    'required_if' => ':attribute ត្រូវតែបំពេញនៅពេល :other គឺ :value។',
    'required_with' => ':attribute ត្រូវតែបំពេញនៅពេល :values មាន។',
    'required_without' => ':attribute ត្រូវតែបំពេញនៅពេល :values មិនមាន។',
    'nullable' => ':attribute អាចទទេបាន។',
    'boolean' => ':attribute ត្រូវតែជា បាទ/ចាស ឬ ទេ។',
    'between' => [
        'numeric' => ':attribute ត្រូវតែនៅចន្លោះ :min និង :max។',
        'string' => ':attribute ត្រូវតែមានប្រវែងចន្លោះ :min និង :max តួអក្សរ។',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    */
    
    'custom' => [
        'pilot_school_id' => [
            'required' => 'សូមជ្រើសរើសសាលារៀន។',
            'exists' => 'សាលារៀនដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ],
        'teacher_id' => [
            'required' => 'សូមជ្រើសរើសគ្រូបង្រៀន។',
            'required_if' => 'សូមជ្រើសរើសគ្រូបង្រៀននៅពេលថ្នាក់កំពុងបង្រៀន។',
            'exists' => 'គ្រូបង្រៀនដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ],
        'visit_date' => [
            'required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទទស្សនកិច្ច។',
            'date' => 'កាលបរិច្ឆេទទស្សនកិច្ចមិនត្រឹមត្រូវទេ។',
        ],
        'class_in_session' => [
            'required' => 'សូមបញ្ជាក់ថាតើថ្នាក់កំពុងបង្រៀនឬទេ។',
            'in' => 'តម្លៃដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ],
        'full_session_observed' => [
            'required_if' => 'សូមបញ្ជាក់ថាតើអ្នកបានសង្កេតពេញមួយវគ្គឬទេ។',
            'in' => 'តម្លៃដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ],
        'subject_observed' => [
            'required_if' => 'សូមជ្រើសរើសមុខវិជ្ជាដែលបានសង្កេត។',
            'in' => 'មុខវិជ្ជាដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ],
        'grades_observed' => [
            'required_if' => 'សូមជ្រើសរើសថ្នាក់ដែលបានសង្កេត។',
            'array' => 'ថ្នាក់ដែលបានសង្កេតត្រូវតែជាបញ្ជី។',
        ],
        'class_started_on_time' => [
            'required_if' => 'សូមបញ្ជាក់ថាតើថ្នាក់បានចាប់ផ្តើមទាន់ពេលវេលាឬទេ។',
            'in' => 'តម្លៃដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ],
        'num_activities_observed' => [
            'required_if' => 'សូមបញ្ជាក់ចំនួនសកម្មភាពដែលបានសង្កេត។',
            'integer' => 'ចំនួនសកម្មភាពត្រូវតែជាលេខ។',
            'min' => 'ចំនួនសកម្មភាពត្រូវតែយ៉ាងតិច :min។',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'ឈ្មោះ',
        'email' => 'អ៊ីមែល',
        'password' => 'ពាក្យសម្ងាត់',
        'pilot_school_id' => 'សាលារៀន',
        'school_id' => 'សាលារៀន',
        'teacher_id' => 'គ្រូបង្រៀន',
        'visit_date' => 'កាលបរិច្ឆេទទស្សនកិច្ច',
        'class_in_session' => 'ថ្នាក់កំពុងបង្រៀន',
        'full_session_observed' => 'សង្កេតពេញមួយវគ្គ',
        'subject_observed' => 'មុខវិជ្ជាដែលបានសង្កេត',
        'grades_observed' => 'ថ្នាក់ដែលបានសង្កេត',
        'language_levels_observed' => 'កម្រិតភាសាដែលបានសង្កេត',
        'numeracy_levels_observed' => 'កម្រិតគណិតវិទ្យាដែលបានសង្កេត',
        'class_started_on_time' => 'ថ្នាក់ចាប់ផ្តើមទាន់ពេលវេលា',
        'num_activities_observed' => 'ចំនួនសកម្មភាពដែលបានសង្កេត',
        'number_of_activities' => 'ចំនួនសកម្មភាព',
        'region' => 'តំបន់',
        'program_type' => 'ប្រភេទកម្មវិធី',
        'grade_group' => 'ក្រុមថ្នាក់',
        'late_start_reason' => 'មូលហេតុចាប់ផ្តើមយឺត',
        'class_not_in_session_reason' => 'មូលហេតុថ្នាក់មិនកំពុងបង្រៀន',
        'materials_present' => 'សម្ភារៈដែលមាន',
        'children_grouped_appropriately' => 'សិស្សត្រូវបានចាត់ក្រុមត្រឹមត្រូវ',
        'students_fully_involved' => 'សិស្សចូលរួមពេញលេញ',
        'has_session_plan' => 'មានផែនការបង្រៀន',
        'followed_session_plan' => 'អនុវត្តតាមផែនការបង្រៀន',
        'session_plan_appropriate' => 'ផែនការបង្រៀនសមរម្យ',
        'activity1_name_language' => 'ឈ្មោះសកម្មភាពភាសាទី១',
        'activity1_name_numeracy' => 'ឈ្មោះសកម្មភាពគណិតវិទ្យាទី១',
        'activity1_duration' => 'រយៈពេលសកម្មភាពទី១',
        'activity1_clear_instructions' => 'ការណែនាំច្បាស់លាស់សកម្មភាពទី១',
        'activity2_name_language' => 'ឈ្មោះសកម្មភាពភាសាទី២',
        'activity2_name_numeracy' => 'ឈ្មោះសកម្មភាពគណិតវិទ្យាទី២',
        'activity2_duration' => 'រយៈពេលសកម្មភាពទី២',
        'activity2_clear_instructions' => 'ការណែនាំច្បាស់លាស់សកម្មភាពទី២',
        'observation' => 'ការសង្កេត',
        'action_plan' => 'ផែនការសកម្មភាព',
        'score' => 'ពិន្ទុ',
        'follow_up_required' => 'ត្រូវការតាមដាន',
    ],
];