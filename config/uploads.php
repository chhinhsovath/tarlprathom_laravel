<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | These values match the production server limits at plp.moeys.gov.kh
    | Server limits: upload_max_filesize = 20M, post_max_size = 25M
    |
    */

    'max_file_size' => [
        'profile_photo' => 5120, // 5MB in KB for user profile photos
        'student_photo' => 5120, // 5MB in KB for student photos
        'mentoring_photo' => 5120, // 5MB in KB for mentoring visit photos
        'csv_import' => 10240, // 10MB in KB for CSV imports
    ],

    'allowed_mimes' => [
        'images' => ['jpeg', 'png', 'jpg', 'gif'],
        'documents' => ['pdf', 'doc', 'docx'],
        'spreadsheets' => ['csv', 'xls', 'xlsx'],
    ],

    'storage_paths' => [
        'profile_photos' => 'users/photos',
        'student_photos' => 'students/photos',
        'mentoring_photos' => 'mentoring/photos',
        'imports' => 'imports',
        'exports' => 'exports',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Processing
    |--------------------------------------------------------------------------
    */
    'image_dimensions' => [
        'profile_photo' => [
            'width' => 500,
            'height' => 500,
        ],
        'student_photo' => [
            'width' => 400,
            'height' => 400,
        ],
        'mentoring_photo' => [
            'width' => 800,
            'height' => 600,
        ],
    ],

    'image_quality' => 85, // JPEG quality (0-100)
];
