<?php

namespace App\Http\Controllers;

class ShowcaseController extends Controller
{
    public function index()
    {
        $features = [
            'user_management' => [
                'icon' => 'users',
                'features' => [
                    'create_users',
                    'edit_users',
                    'delete_users',
                    'assign_roles',
                    'manage_permissions',
                ],
            ],
            'school_management' => [
                'icon' => 'building',
                'features' => [
                    'create_schools',
                    'edit_schools',
                    'delete_schools',
                    'view_school_statistics',
                    'manage_school_staff',
                ],
            ],
            'student_management' => [
                'icon' => 'graduation-cap',
                'features' => [
                    'add_students',
                    'update_profiles',
                    'upload_photos',
                    'manage_attendance',
                    'track_progress',
                ],
            ],
            'assessments' => [
                'icon' => 'clipboard-list',
                'features' => [
                    'baseline_assessment',
                    'midline_assessment',
                    'endline_assessment',
                    'quick_assessment',
                    'custom_assessments',
                ],
            ],
            'mentoring' => [
                'icon' => 'user-check',
                'features' => [
                    'document_visits',
                    'observation_notes',
                    'feedback_forms',
                    'action_plans',
                    'mentor_reports',
                ],
            ],
            'reporting' => [
                'icon' => 'chart-bar',
                'features' => [
                    'comprehensive_reports',
                    'export_data',
                    'analytics_dashboard',
                    'custom_report_builder',
                    'real_time_statistics',
                ],
            ],
            'data_analysis' => [
                'icon' => 'chart-line',
                'features' => [
                    'comparative_analysis',
                    'trend_monitoring',
                    'intervention_planning',
                    'performance_metrics',
                    'predictive_analytics',
                ],
            ],
            'resources' => [
                'icon' => 'book-open',
                'features' => [
                    'teaching_materials',
                    'assessment_tools',
                    'activity_guides',
                    'help_documentation',
                    'training_videos',
                ],
            ],
            'communication' => [
                'icon' => 'comments',
                'features' => [
                    'messaging_system',
                    'announcements',
                    'notifications',
                    'collaboration_tools',
                    'parent_communication',
                ],
            ],
        ];

        return view('showcase', compact('features'));
    }
}
