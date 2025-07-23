CREATE TABLE IF NOT EXISTS `resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL COMMENT 'video, pdf, word, excel, powerpoint',
  `mime_type` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;