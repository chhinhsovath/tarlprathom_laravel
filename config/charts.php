<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chart Color Configurations
    |--------------------------------------------------------------------------
    |
    | Define standardized colors for charts across the application.
    | This ensures consistency and makes it easy to maintain the color scheme.
    |
    */

    'colors' => [
        // Progress Status Colors
        'progress' => [
            'improved' => '#22c55e',    // Green
            'maintained' => '#fbbf24',  // Yellow
            'declined' => '#ef4444',    // Red
        ],

        // Assessment Cycle Colors
        'cycles' => [
            'baseline' => '#fb923c',    // Orange
            'midline' => '#c2410c',     // Dark Orange
            'endline' => '#2563eb',     // Blue
        ],

        // TaRL Level Colors (for distribution charts)
        'tarl_levels' => [
            '#ef4444', // Red - Beginner
            '#f59e0b', // Amber - Letter/1-Digit
            '#eab308', // Yellow - Word/2-Digit
            '#84cc16', // Lime - Paragraph/Subtraction
            '#22c55e', // Green - Story/Division
            '#10b981', // Emerald - Comp. 1/Word Problem
            '#14b8a6', // Teal - Comp. 2
        ],

        // General Chart Colors (for multi-series charts)
        'general' => [
            '#3b82f6', // Blue
            '#ef4444', // Red
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#8b5cf6', // Violet
            '#06b6d4', // Cyan
            '#84cc16', // Lime
            '#f97316', // Orange
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Chart Default Settings
    |--------------------------------------------------------------------------
    |
    | Default configuration for chart appearance and behavior.
    |
    */

    'defaults' => [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'borderWidth' => 2,
        'borderColor' => '#ffffff',
        'font' => [
            'family' => 'Inter, system-ui, sans-serif',
            'size' => 12,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CSS Color Classes
    |--------------------------------------------------------------------------
    |
    | Corresponding Tailwind CSS classes for UI elements that match chart colors.
    |
    */

    'css_classes' => [
        'progress' => [
            'improved' => [
                'bg' => 'bg-green-50',
                'border' => 'border-green-200',
                'text' => 'text-green-800',
                'text_light' => 'text-green-600',
                'text_lighter' => 'text-green-500',
                'text_lightest' => 'text-green-400',
            ],
            'maintained' => [
                'bg' => 'bg-yellow-50',
                'border' => 'border-yellow-200',
                'text' => 'text-yellow-800',
                'text_light' => 'text-yellow-600',
                'text_lighter' => 'text-yellow-500',
                'text_lightest' => 'text-yellow-400',
            ],
            'declined' => [
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
                'text' => 'text-red-800',
                'text_light' => 'text-red-600',
                'text_lighter' => 'text-red-500',
                'text_lightest' => 'text-red-400',
            ],
        ],
    ],
];
