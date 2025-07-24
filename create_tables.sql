-- Create all required tables for TaRL Laravel system
-- Based on migration files analysis

-- Create migrations table first
CREATE TABLE IF NOT EXISTS `migrations` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `batch` int NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create cache table
CREATE TABLE IF NOT EXISTS `cache` (
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create jobs table
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `attempts` tinyint unsigned NOT NULL,
    `reserved_at` int unsigned DEFAULT NULL,
    `available_at` int unsigned NOT NULL,
    `created_at` int unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_jobs` int NOT NULL,
    `pending_jobs` int NOT NULL,
    `failed_jobs` int NOT NULL,
    `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `options` mediumtext COLLATE utf8mb4_unicode_ci,
    `cancelled_at` int DEFAULT NULL,
    `created_at` int NOT NULL,
    `finished_at` int DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `role` enum('admin','teacher','mentor','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'teacher',
    `school_id` bigint unsigned DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `address` text COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_school_id_foreign` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create schools table
CREATE TABLE IF NOT EXISTS `schools` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `district` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cluster` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `school_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `baseline_start_date` date DEFAULT NULL,
    `baseline_end_date` date DEFAULT NULL,
    `midline_start_date` date DEFAULT NULL,
    `midline_end_date` date DEFAULT NULL,
    `endline_start_date` date DEFAULT NULL,
    `endline_end_date` date DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `schools_school_code_unique` (`school_code`),
    KEY `schools_baseline_start_date_baseline_end_date_index` (`baseline_start_date`,`baseline_end_date`),
    KEY `schools_midline_start_date_midline_end_date_index` (`midline_start_date`,`midline_end_date`),
    KEY `schools_endline_start_date_endline_end_date_index` (`endline_start_date`,`endline_end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create classes table
CREATE TABLE IF NOT EXISTS `classes` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `grade_level` int NOT NULL,
    `school_id` bigint unsigned NOT NULL,
    `teacher_id` bigint unsigned DEFAULT NULL,
    `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2024-2025',
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `classes_school_id_foreign` (`school_id`),
    KEY `classes_teacher_id_foreign` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create students table
CREATE TABLE IF NOT EXISTS `students` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `sex` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
    `age` int NOT NULL,
    `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `school_id` bigint unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `teacher_id` bigint unsigned DEFAULT NULL,
    `class_id` bigint unsigned DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `students_school_id_foreign` (`school_id`),
    KEY `students_teacher_id_foreign` (`teacher_id`),
    KEY `students_class_id_foreign` (`class_id`),
    KEY `students_teacher_id_class_id_index` (`teacher_id`,`class_id`),
    KEY `students_school_id_teacher_id_index` (`school_id`,`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create assessments table
CREATE TABLE IF NOT EXISTS `assessments` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `student_id` bigint unsigned NOT NULL,
    `cycle` enum('baseline','midline','endline') COLLATE utf8mb4_unicode_ci NOT NULL,
    `subject` enum('math','khmer') COLLATE utf8mb4_unicode_ci NOT NULL,
    `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `score` double DEFAULT NULL,
    `assessed_at` date NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `assessments_student_id_foreign` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create mentoring_visits table
CREATE TABLE IF NOT EXISTS `mentoring_visits` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `school_id` bigint unsigned NOT NULL,
    `mentor_id` bigint unsigned NOT NULL,
    `visit_date` date NOT NULL,
    `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `findings` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `recommendations` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `follow_up_required` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `q1_teaching_observed` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `q2_students_engaged` enum('high','medium','low') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `q3_teaching_materials` enum('yes','no','partial') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `q4_grouping_strategy` enum('yes','no','needs_improvement') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `q5_assessment_conducted` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `q6_data_recording` enum('complete','partial','none') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `q7_challenges_faced` text COLLATE utf8mb4_unicode_ci,
    `q8_support_needed` text COLLATE utf8mb4_unicode_ci,
    `q9_additional_notes` text COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`id`),
    KEY `mentoring_visits_school_id_foreign` (`school_id`),
    KEY `mentoring_visits_mentor_id_foreign` (`mentor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create sessions table
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_id` bigint unsigned DEFAULT NULL,
    `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `user_agent` text COLLATE utf8mb4_unicode_ci,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `last_activity` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create assessment_histories table
CREATE TABLE IF NOT EXISTS `assessment_histories` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `student_id` bigint unsigned NOT NULL,
    `assessment_id` bigint unsigned DEFAULT NULL,
    `cycle` enum('baseline','midline','endline') COLLATE utf8mb4_unicode_ci NOT NULL,
    `subject` enum('math','khmer') COLLATE utf8mb4_unicode_ci NOT NULL,
    `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `score` double DEFAULT NULL,
    `assessed_at` date NOT NULL,
    `updated_by` bigint unsigned DEFAULT NULL,
    `action` enum('created','updated') COLLATE utf8mb4_unicode_ci NOT NULL,
    `previous_data` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `assessment_histories_student_id_foreign` (`student_id`),
    KEY `assessment_histories_assessment_id_foreign` (`assessment_id`),
    KEY `assessment_histories_updated_by_foreign` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create resources table
CREATE TABLE IF NOT EXISTS `resources` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci,
    `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `file_size` bigint unsigned NOT NULL,
    `category` enum('teaching_materials','assessment_tools','training_resources','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
    `is_public` tinyint(1) NOT NULL DEFAULT '0',
    `uploaded_by` bigint unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `resources_uploaded_by_foreign` (`uploaded_by`),
    KEY `resources_category_index` (`category`),
    KEY `resources_is_public_index` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create student_assessment_eligibility table
CREATE TABLE IF NOT EXISTS `student_assessment_eligibility` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `student_id` bigint unsigned NOT NULL,
    `assessment_type` enum('midline','endline') COLLATE utf8mb4_unicode_ci NOT NULL,
    `selected_by` bigint unsigned NOT NULL,
    `is_eligible` tinyint(1) NOT NULL DEFAULT '1',
    `notes` text COLLATE utf8mb4_unicode_ci,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `student_assessment_eligibility_student_id_assessment_type_unique` (`student_id`,`assessment_type`),
    KEY `student_assessment_eligibility_selected_by_foreign` (`selected_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create mentor_school table
CREATE TABLE IF NOT EXISTS `mentor_school` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `school_id` bigint unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `mentor_school_user_id_school_id_unique` (`user_id`,`school_id`),
    KEY `mentor_school_school_id_foreign` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraints
ALTER TABLE `users` ADD CONSTRAINT `users_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL;
ALTER TABLE `classes` ADD CONSTRAINT `classes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;
ALTER TABLE `classes` ADD CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `students` ADD CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;
ALTER TABLE `students` ADD CONSTRAINT `students_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `students` ADD CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;
ALTER TABLE `assessments` ADD CONSTRAINT `assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
ALTER TABLE `mentoring_visits` ADD CONSTRAINT `mentoring_visits_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;
ALTER TABLE `mentoring_visits` ADD CONSTRAINT `mentoring_visits_mentor_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `assessment_histories` ADD CONSTRAINT `assessment_histories_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
ALTER TABLE `assessment_histories` ADD CONSTRAINT `assessment_histories_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE SET NULL;
ALTER TABLE `assessment_histories` ADD CONSTRAINT `assessment_histories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `resources` ADD CONSTRAINT `resources_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `student_assessment_eligibility` ADD CONSTRAINT `student_assessment_eligibility_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
ALTER TABLE `student_assessment_eligibility` ADD CONSTRAINT `student_assessment_eligibility_selected_by_foreign` FOREIGN KEY (`selected_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `mentor_school` ADD CONSTRAINT `mentor_school_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `mentor_school` ADD CONSTRAINT `mentor_school_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;