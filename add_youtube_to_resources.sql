-- Add YouTube URL support to resources table
ALTER TABLE `resources` 
ADD COLUMN `youtube_url` VARCHAR(255) NULL AFTER `file_path`,
ADD COLUMN `is_youtube` BOOLEAN DEFAULT FALSE AFTER `youtube_url`;

-- Create table for tracking video views
CREATE TABLE IF NOT EXISTS `resource_views` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NULL,
  `ip_address` varchar(45) NULL,
  `watch_duration` int(11) DEFAULT 0 COMMENT 'Duration in seconds',
  `total_duration` int(11) DEFAULT 0 COMMENT 'Video total duration in seconds',
  `completion_percentage` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_views_resource_id_index` (`resource_id`),
  KEY `resource_views_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;