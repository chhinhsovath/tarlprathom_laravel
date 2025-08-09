/*
 Navicat Premium Dump SQL

 Source Server         : DO_TARL
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42-0ubuntu0.24.10.1)
 Source Host           : 137.184.109.21:3306
 Source Schema         : tarlprathom_laravel

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42-0ubuntu0.24.10.1)
 File Encoding         : 65001

 Date: 15/07/2025 10:34:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for assessments
-- ----------------------------
DROP TABLE IF EXISTS `assessments`;
CREATE TABLE `assessments` (
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
  KEY `assessments_student_id_foreign` (`student_id`),
  CONSTRAINT `assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of assessments
-- ----------------------------
BEGIN;
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (1, 1, 'midline', 'khmer', 'Word', 37, '2025-07-03', '2025-07-11 08:54:32', '2025-07-11 08:54:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (2, 1, 'midline', 'math', '2-Digit', 70, '2025-07-07', '2025-07-11 08:54:32', '2025-07-11 08:54:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (3, 2, 'endline', 'khmer', 'Letter', 34, '2025-06-19', '2025-07-11 08:54:33', '2025-07-11 08:54:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (4, 2, 'baseline', 'math', 'Beginner', 35, '2025-06-30', '2025-07-11 08:54:33', '2025-07-11 08:54:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (5, 3, 'baseline', 'khmer', 'Word', 9, '2025-06-14', '2025-07-11 08:54:34', '2025-07-11 08:54:34');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (6, 3, 'baseline', 'math', '2-Digit', 83, '2025-06-20', '2025-07-11 08:54:35', '2025-07-11 08:54:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (7, 4, 'midline', 'khmer', 'Letter', 75, '2025-06-13', '2025-07-11 08:54:35', '2025-07-11 08:54:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (8, 4, 'endline', 'math', 'Division', 58, '2025-06-22', '2025-07-11 08:54:36', '2025-07-11 08:54:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (9, 5, 'baseline', 'khmer', 'Letter', 91, '2025-07-11', '2025-07-11 08:54:36', '2025-07-11 08:54:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (10, 5, 'baseline', 'math', '1-Digit', 41, '2025-06-15', '2025-07-11 08:54:37', '2025-07-11 08:54:37');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (11, 6, 'endline', 'khmer', 'Letter', 12, '2025-07-05', '2025-07-11 08:54:38', '2025-07-11 08:54:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (12, 6, 'midline', 'math', 'Beginner', 83, '2025-06-17', '2025-07-11 08:54:38', '2025-07-11 08:54:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (13, 7, 'midline', 'khmer', 'Story', 13, '2025-07-06', '2025-07-11 08:54:39', '2025-07-11 08:54:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (14, 7, 'baseline', 'math', 'Beginner', 59, '2025-06-25', '2025-07-11 08:54:39', '2025-07-11 08:54:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (15, 8, 'endline', 'khmer', 'Letter', 65, '2025-06-23', '2025-07-11 08:54:40', '2025-07-11 08:54:40');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (16, 8, 'endline', 'math', '1-Digit', 26, '2025-06-13', '2025-07-11 08:54:41', '2025-07-11 08:54:41');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (17, 9, 'baseline', 'khmer', 'Story', 27, '2025-07-03', '2025-07-11 08:54:41', '2025-07-11 08:54:41');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (18, 9, 'midline', 'math', '2-Digit', 38, '2025-06-19', '2025-07-11 08:54:42', '2025-07-11 08:54:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (19, 10, 'endline', 'khmer', 'Word', 16, '2025-06-22', '2025-07-11 08:54:42', '2025-07-11 08:54:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (20, 10, 'baseline', 'math', 'Division', 16, '2025-06-18', '2025-07-11 08:54:43', '2025-07-11 08:54:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (21, 11, 'midline', 'khmer', 'Paragraph', 83, '2025-06-17', '2025-07-11 08:54:44', '2025-07-11 08:54:44');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (22, 11, 'baseline', 'math', '2-Digit', 87, '2025-07-05', '2025-07-11 08:54:44', '2025-07-11 08:54:44');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (23, 12, 'endline', 'khmer', 'Beginner', 0, '2025-07-01', '2025-07-11 08:54:45', '2025-07-11 08:54:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (24, 12, 'baseline', 'math', 'Beginner', 96, '2025-07-07', '2025-07-11 08:54:45', '2025-07-11 08:54:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (25, 13, 'baseline', 'khmer', 'Letter', 56, '2025-06-12', '2025-07-11 08:54:46', '2025-07-11 08:54:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (26, 13, 'midline', 'math', '1-Digit', 75, '2025-06-18', '2025-07-11 08:54:47', '2025-07-11 08:54:47');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (27, 14, 'endline', 'khmer', 'Word', 55, '2025-07-05', '2025-07-11 08:54:47', '2025-07-11 08:54:47');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (28, 14, 'baseline', 'math', 'Beginner', 70, '2025-07-01', '2025-07-11 08:54:48', '2025-07-11 08:54:48');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (29, 15, 'baseline', 'khmer', 'Story', 87, '2025-07-06', '2025-07-11 08:54:48', '2025-07-11 08:54:48');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (30, 15, 'midline', 'math', 'Subtraction', 91, '2025-07-02', '2025-07-11 08:54:49', '2025-07-11 08:54:49');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (31, 16, 'baseline', 'khmer', 'Beginner', 3, '2025-06-21', '2025-07-11 08:54:50', '2025-07-11 08:54:50');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (32, 16, 'baseline', 'math', 'Division', 88, '2025-07-09', '2025-07-11 08:54:50', '2025-07-11 08:54:50');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (33, 17, 'baseline', 'khmer', 'Word', 61, '2025-07-03', '2025-07-11 08:54:51', '2025-07-11 08:54:51');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (34, 17, 'midline', 'math', '2-Digit', 74, '2025-06-19', '2025-07-11 08:54:51', '2025-07-11 08:54:51');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (35, 18, 'endline', 'khmer', 'Paragraph', 17, '2025-07-11', '2025-07-11 08:54:52', '2025-07-11 08:54:52');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (36, 18, 'baseline', 'math', '2-Digit', 0, '2025-06-11', '2025-07-11 08:54:53', '2025-07-11 08:54:53');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (37, 19, 'endline', 'khmer', 'Letter', 7, '2025-07-04', '2025-07-11 08:54:53', '2025-07-11 08:54:53');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (38, 19, 'endline', 'math', '2-Digit', 23, '2025-06-15', '2025-07-11 08:54:54', '2025-07-11 08:54:54');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (39, 20, 'baseline', 'khmer', 'Paragraph', 92, '2025-06-26', '2025-07-11 08:54:54', '2025-07-11 08:54:54');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (40, 20, 'baseline', 'math', 'Beginner', 98, '2025-06-25', '2025-07-11 08:54:55', '2025-07-11 08:54:55');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (41, 21, 'midline', 'khmer', 'Story', 96, '2025-07-02', '2025-07-11 08:54:56', '2025-07-11 08:54:56');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (42, 21, 'baseline', 'math', 'Division', 66, '2025-06-16', '2025-07-11 08:54:56', '2025-07-11 08:54:56');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (43, 22, 'midline', 'khmer', 'Story', 79, '2025-06-14', '2025-07-11 08:54:57', '2025-07-11 08:54:57');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (44, 22, 'midline', 'math', '2-Digit', 93, '2025-06-14', '2025-07-11 08:54:58', '2025-07-11 08:54:58');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (45, 23, 'baseline', 'khmer', 'Story', 81, '2025-06-14', '2025-07-11 08:54:58', '2025-07-11 08:54:58');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (46, 23, 'endline', 'math', 'Beginner', 85, '2025-07-04', '2025-07-11 08:54:59', '2025-07-11 08:54:59');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (47, 24, 'baseline', 'khmer', 'Paragraph', 61, '2025-06-20', '2025-07-11 08:54:59', '2025-07-11 08:54:59');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (48, 24, 'baseline', 'math', '1-Digit', 99, '2025-06-18', '2025-07-11 08:55:00', '2025-07-11 08:55:00');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (49, 25, 'midline', 'khmer', 'Word', 17, '2025-06-11', '2025-07-11 08:55:01', '2025-07-11 08:55:01');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (50, 25, 'midline', 'math', 'Division', 82, '2025-06-26', '2025-07-11 08:55:01', '2025-07-11 08:55:01');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (51, 26, 'midline', 'khmer', 'Beginner', 53, '2025-06-26', '2025-07-11 08:55:02', '2025-07-11 08:55:02');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (52, 26, 'baseline', 'math', '2-Digit', 51, '2025-07-05', '2025-07-11 08:55:02', '2025-07-11 08:55:02');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (53, 27, 'baseline', 'khmer', 'Letter', 99, '2025-06-17', '2025-07-11 08:55:03', '2025-07-11 08:55:03');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (54, 27, 'baseline', 'math', 'Beginner', 44, '2025-07-11', '2025-07-11 08:55:04', '2025-07-11 08:55:04');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (55, 28, 'endline', 'khmer', 'Letter', 61, '2025-06-13', '2025-07-11 08:55:04', '2025-07-11 08:55:04');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (56, 28, 'endline', 'math', 'Beginner', 5, '2025-06-23', '2025-07-11 08:55:05', '2025-07-11 08:55:05');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (57, 29, 'endline', 'khmer', 'Word', 3, '2025-06-25', '2025-07-11 08:55:05', '2025-07-11 08:55:05');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (58, 29, 'midline', 'math', 'Subtraction', 14, '2025-06-27', '2025-07-11 08:55:06', '2025-07-11 08:55:06');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (59, 30, 'endline', 'khmer', 'Letter', 62, '2025-07-03', '2025-07-11 08:55:07', '2025-07-11 08:55:07');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (60, 30, 'midline', 'math', 'Division', 95, '2025-06-28', '2025-07-11 08:55:07', '2025-07-11 08:55:07');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (61, 31, 'endline', 'math', 'Beginner', 66, '2025-07-09', '2025-07-11 09:09:19', '2025-07-11 09:09:19');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (62, 32, 'endline', 'math', '1-Digit', 72, '2025-06-15', '2025-07-11 09:09:19', '2025-07-11 09:09:19');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (63, 33, 'midline', 'math', '2-Digit', 73, '2025-07-03', '2025-07-11 09:09:20', '2025-07-11 09:09:20');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (64, 34, 'baseline', 'math', 'Subtraction', 52, '2025-06-15', '2025-07-11 09:09:20', '2025-07-11 09:09:20');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (65, 35, 'baseline', 'math', 'Division', 24, '2025-06-20', '2025-07-11 09:09:21', '2025-07-11 09:09:21');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (66, 36, 'baseline', 'math', 'Beginner', 28, '2025-06-21', '2025-07-11 09:09:22', '2025-07-11 09:09:22');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (67, 37, 'baseline', 'math', '1-Digit', 90, '2025-06-26', '2025-07-11 09:09:22', '2025-07-11 09:09:22');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (68, 38, 'midline', 'math', '2-Digit', 90, '2025-06-15', '2025-07-11 09:09:23', '2025-07-11 09:09:23');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (69, 39, 'baseline', 'math', 'Subtraction', 38, '2025-06-25', '2025-07-11 09:09:23', '2025-07-11 09:09:23');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (70, 40, 'endline', 'math', 'Division', 55, '2025-06-12', '2025-07-11 09:09:24', '2025-07-11 09:09:24');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (71, 41, 'endline', 'math', 'Beginner', 95, '2025-06-29', '2025-07-11 09:09:25', '2025-07-11 09:09:25');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (72, 42, 'baseline', 'math', '1-Digit', 54, '2025-06-25', '2025-07-11 09:09:25', '2025-07-11 09:09:25');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (73, 43, 'midline', 'math', '2-Digit', 51, '2025-06-20', '2025-07-11 09:09:26', '2025-07-11 09:09:26');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (74, 44, 'baseline', 'math', 'Subtraction', 85, '2025-07-09', '2025-07-11 09:09:26', '2025-07-11 09:09:26');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (75, 45, 'endline', 'math', 'Division', 22, '2025-07-08', '2025-07-11 09:09:27', '2025-07-11 09:09:27');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (76, 46, 'midline', 'math', 'Beginner', 69, '2025-07-02', '2025-07-11 09:09:28', '2025-07-11 09:09:28');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (77, 47, 'baseline', 'math', '1-Digit', 55, '2025-06-23', '2025-07-11 09:09:28', '2025-07-11 09:09:28');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (78, 48, 'baseline', 'math', '2-Digit', 43, '2025-07-02', '2025-07-11 09:09:29', '2025-07-11 09:09:29');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (79, 49, 'endline', 'math', 'Subtraction', 29, '2025-06-17', '2025-07-11 09:09:29', '2025-07-11 09:09:29');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (80, 50, 'endline', 'math', 'Division', 29, '2025-06-17', '2025-07-11 09:09:30', '2025-07-11 09:09:30');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (81, 51, 'baseline', 'math', 'Beginner', 53, '2025-06-29', '2025-07-11 09:09:30', '2025-07-11 09:09:30');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (82, 52, 'midline', 'math', '1-Digit', 57, '2025-06-20', '2025-07-11 09:09:31', '2025-07-11 09:09:31');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (83, 53, 'midline', 'math', '2-Digit', 71, '2025-07-10', '2025-07-11 09:09:32', '2025-07-11 09:09:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (84, 54, 'endline', 'math', 'Subtraction', 51, '2025-07-01', '2025-07-11 09:09:32', '2025-07-11 09:09:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (85, 55, 'midline', 'math', 'Division', 73, '2025-06-25', '2025-07-11 09:09:33', '2025-07-11 09:09:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (86, 56, 'midline', 'math', 'Beginner', 64, '2025-07-10', '2025-07-11 09:09:33', '2025-07-11 09:09:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (87, 57, 'midline', 'math', '1-Digit', 93, '2025-06-27', '2025-07-11 09:09:34', '2025-07-11 09:09:34');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (88, 58, 'baseline', 'math', '2-Digit', 19, '2025-06-27', '2025-07-11 09:09:35', '2025-07-11 09:09:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (89, 59, 'endline', 'math', 'Subtraction', 12, '2025-07-03', '2025-07-11 09:09:35', '2025-07-11 09:09:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (90, 60, 'midline', 'math', 'Division', 31, '2025-07-09', '2025-07-11 09:09:36', '2025-07-11 09:09:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (91, 61, 'midline', 'math', 'Beginner', 22, '2025-06-22', '2025-07-11 09:09:36', '2025-07-11 09:09:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (92, 62, 'endline', 'math', '1-Digit', 37, '2025-07-07', '2025-07-11 09:09:37', '2025-07-11 09:09:37');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (93, 63, 'baseline', 'math', '2-Digit', 5, '2025-06-15', '2025-07-11 09:09:38', '2025-07-11 09:09:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (94, 64, 'endline', 'math', 'Subtraction', 45, '2025-07-07', '2025-07-11 09:09:38', '2025-07-11 09:09:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (95, 65, 'baseline', 'math', 'Division', 76, '2025-07-08', '2025-07-11 09:09:39', '2025-07-11 09:09:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (96, 66, 'endline', 'math', 'Beginner', 46, '2025-07-01', '2025-07-11 09:09:39', '2025-07-11 09:09:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (97, 67, 'endline', 'math', '1-Digit', 74, '2025-06-22', '2025-07-11 09:09:40', '2025-07-11 09:09:40');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (98, 68, 'endline', 'math', '2-Digit', 16, '2025-07-02', '2025-07-11 09:09:40', '2025-07-11 09:09:40');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (99, 69, 'endline', 'math', 'Subtraction', 85, '2025-07-08', '2025-07-11 09:09:41', '2025-07-11 09:09:41');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (100, 70, 'endline', 'math', 'Division', 48, '2025-06-28', '2025-07-11 09:09:42', '2025-07-11 09:09:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (101, 71, 'baseline', 'math', 'Beginner', 97, '2025-06-18', '2025-07-11 09:09:42', '2025-07-11 09:09:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (102, 72, 'endline', 'math', '1-Digit', 17, '2025-07-11', '2025-07-11 09:09:43', '2025-07-11 09:09:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (103, 73, 'endline', 'math', '2-Digit', 89, '2025-07-10', '2025-07-11 09:09:43', '2025-07-11 09:09:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (104, 74, 'baseline', 'math', 'Subtraction', 88, '2025-06-21', '2025-07-11 09:09:44', '2025-07-11 09:09:44');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (105, 75, 'baseline', 'math', 'Division', 68, '2025-07-03', '2025-07-11 09:09:45', '2025-07-11 09:09:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (106, 76, 'midline', 'math', 'Beginner', 8, '2025-06-30', '2025-07-11 09:09:45', '2025-07-11 09:09:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (107, 77, 'baseline', 'math', '1-Digit', 72, '2025-07-09', '2025-07-11 09:09:46', '2025-07-11 09:09:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (108, 78, 'endline', 'math', '2-Digit', 67, '2025-06-21', '2025-07-11 09:09:46', '2025-07-11 09:09:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (109, 79, 'baseline', 'math', 'Subtraction', 34, '2025-06-24', '2025-07-11 09:09:47', '2025-07-11 09:09:47');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (110, 80, 'midline', 'math', 'Division', 81, '2025-07-02', '2025-07-11 09:09:48', '2025-07-11 09:09:48');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (111, 1, 'baseline', 'khmer', 'Beginner', 57, '2025-07-05', '2025-07-11 10:08:51', '2025-07-11 10:08:51');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (112, 1, 'baseline', 'math', 'Division', 100, '2025-06-29', '2025-07-11 10:08:52', '2025-07-11 10:08:52');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (113, 2, 'baseline', 'khmer', 'Letter', 88, '2025-06-19', '2025-07-11 10:08:52', '2025-07-11 10:08:52');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (114, 2, 'baseline', 'math', 'Beginner', 94, '2025-06-17', '2025-07-11 10:08:53', '2025-07-11 10:08:53');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (115, 3, 'baseline', 'khmer', 'Word', 14, '2025-06-17', '2025-07-11 10:08:54', '2025-07-11 10:08:54');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (116, 3, 'baseline', 'math', 'Word Problem', 67, '2025-06-21', '2025-07-11 10:08:54', '2025-07-11 10:08:54');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (117, 4, 'baseline', 'khmer', 'Paragraph', 77, '2025-06-14', '2025-07-11 10:08:55', '2025-07-11 10:08:55');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (118, 4, 'baseline', 'math', 'Division', 65, '2025-06-21', '2025-07-11 10:08:55', '2025-07-11 10:08:55');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (119, 5, 'baseline', 'khmer', 'Story', 11, '2025-06-14', '2025-07-11 10:08:56', '2025-07-11 10:08:56');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (120, 5, 'baseline', 'math', 'Word Problem', 16, '2025-06-19', '2025-07-11 10:08:57', '2025-07-11 10:08:57');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (121, 6, 'baseline', 'khmer', 'Comp. 1', 58, '2025-07-03', '2025-07-11 10:08:57', '2025-07-11 10:08:57');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (122, 6, 'baseline', 'math', 'Division', 1, '2025-06-22', '2025-07-11 10:08:58', '2025-07-11 10:08:58');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (123, 7, 'baseline', 'khmer', 'Comp. 2', 81, '2025-07-06', '2025-07-11 10:08:58', '2025-07-11 10:08:58');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (124, 7, 'baseline', 'math', 'Beginner', 70, '2025-06-23', '2025-07-11 10:08:59', '2025-07-11 10:08:59');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (125, 8, 'baseline', 'khmer', 'Beginner', 47, '2025-07-11', '2025-07-11 10:09:00', '2025-07-11 15:34:31');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (126, 8, 'baseline', 'math', 'Beginner', 93, '2025-06-27', '2025-07-11 10:09:00', '2025-07-11 10:09:00');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (127, 9, 'baseline', 'khmer', 'Letter', 22, '2025-06-13', '2025-07-11 10:09:01', '2025-07-11 10:09:01');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (128, 9, 'baseline', 'math', 'Word Problem', 7, '2025-06-14', '2025-07-11 10:09:01', '2025-07-11 10:09:01');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (129, 10, 'baseline', 'khmer', 'Word', 49, '2025-07-09', '2025-07-11 10:09:02', '2025-07-11 10:09:02');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (130, 10, 'baseline', 'math', '2-Digit', 57, '2025-07-05', '2025-07-11 10:09:03', '2025-07-11 10:09:03');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (131, 11, 'baseline', 'khmer', 'Paragraph', 42, '2025-06-26', '2025-07-11 10:09:03', '2025-07-11 10:09:03');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (132, 11, 'baseline', 'math', 'Subtraction', 71, '2025-06-18', '2025-07-11 10:09:04', '2025-07-11 10:09:04');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (133, 12, 'baseline', 'khmer', 'Story', 45, '2025-07-07', '2025-07-11 10:09:04', '2025-07-11 10:09:04');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (134, 12, 'baseline', 'math', '2-Digit', 61, '2025-07-10', '2025-07-11 10:09:05', '2025-07-11 10:09:05');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (135, 13, 'baseline', 'khmer', 'Comp. 1', 27, '2025-07-03', '2025-07-11 10:09:06', '2025-07-11 10:09:06');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (136, 13, 'baseline', 'math', 'Division', 87, '2025-06-15', '2025-07-11 10:09:06', '2025-07-11 10:09:06');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (137, 14, 'baseline', 'khmer', 'Comp. 2', 41, '2025-06-11', '2025-07-11 10:09:07', '2025-07-11 10:09:07');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (138, 14, 'baseline', 'math', 'Beginner', 53, '2025-07-01', '2025-07-11 10:09:07', '2025-07-11 10:09:07');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (139, 15, 'baseline', 'khmer', 'Beginner', 54, '2025-07-09', '2025-07-11 10:09:08', '2025-07-11 10:09:08');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (140, 15, 'baseline', 'math', '1-Digit', 98, '2025-07-09', '2025-07-11 10:09:09', '2025-07-11 10:09:09');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (141, 16, 'baseline', 'khmer', 'Letter', 91, '2025-06-15', '2025-07-11 10:09:09', '2025-07-11 10:09:09');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (142, 16, 'baseline', 'math', '2-Digit', 6, '2025-07-01', '2025-07-11 10:09:10', '2025-07-11 10:09:10');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (143, 17, 'baseline', 'khmer', 'Word', 52, '2025-06-20', '2025-07-11 10:09:11', '2025-07-11 10:09:11');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (144, 17, 'baseline', 'math', 'Division', 79, '2025-07-07', '2025-07-11 10:09:11', '2025-07-11 10:09:11');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (145, 18, 'baseline', 'khmer', 'Paragraph', 13, '2025-06-15', '2025-07-11 10:09:12', '2025-07-11 10:09:12');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (146, 18, 'baseline', 'math', 'Word Problem', 10, '2025-07-04', '2025-07-11 10:09:13', '2025-07-11 10:09:13');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (147, 19, 'baseline', 'khmer', 'Story', 27, '2025-07-07', '2025-07-11 10:09:13', '2025-07-11 10:09:13');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (148, 19, 'baseline', 'math', 'Subtraction', 73, '2025-06-20', '2025-07-11 10:09:14', '2025-07-11 10:09:14');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (149, 20, 'baseline', 'khmer', 'Comp. 1', 11, '2025-06-23', '2025-07-11 10:09:15', '2025-07-11 10:09:15');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (150, 20, 'baseline', 'math', 'Word Problem', 24, '2025-06-27', '2025-07-11 10:09:15', '2025-07-11 10:09:15');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (151, 21, 'baseline', 'khmer', 'Comp. 2', 11, '2025-06-12', '2025-07-11 10:09:16', '2025-07-11 10:09:16');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (152, 21, 'baseline', 'math', 'Subtraction', 18, '2025-07-05', '2025-07-11 10:09:16', '2025-07-11 10:09:16');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (153, 22, 'baseline', 'khmer', 'Beginner', 6, '2025-06-16', '2025-07-11 10:09:17', '2025-07-11 10:09:17');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (154, 22, 'baseline', 'math', 'Beginner', 23, '2025-06-14', '2025-07-11 10:09:18', '2025-07-11 10:09:18');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (155, 23, 'baseline', 'khmer', 'Letter', 13, '2025-06-29', '2025-07-11 10:09:18', '2025-07-11 10:09:18');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (156, 23, 'baseline', 'math', 'Word Problem', 83, '2025-06-14', '2025-07-11 10:09:19', '2025-07-11 10:09:19');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (157, 24, 'baseline', 'khmer', 'Word', 45, '2025-06-13', '2025-07-11 10:09:19', '2025-07-11 10:09:19');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (158, 24, 'baseline', 'math', '1-Digit', 30, '2025-07-02', '2025-07-11 10:09:20', '2025-07-11 10:09:20');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (159, 25, 'baseline', 'khmer', 'Paragraph', 28, '2025-06-22', '2025-07-11 10:09:21', '2025-07-11 10:09:21');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (160, 25, 'baseline', 'math', 'Word Problem', 64, '2025-07-10', '2025-07-11 10:09:21', '2025-07-11 10:09:21');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (161, 26, 'baseline', 'khmer', 'Story', 74, '2025-07-04', '2025-07-11 10:09:22', '2025-07-11 10:09:22');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (162, 26, 'baseline', 'math', 'Division', 32, '2025-06-22', '2025-07-11 10:09:23', '2025-07-11 10:09:23');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (163, 27, 'baseline', 'khmer', 'Comp. 1', 51, '2025-06-30', '2025-07-11 10:09:23', '2025-07-11 10:09:23');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (164, 27, 'baseline', 'math', 'Beginner', 5, '2025-07-02', '2025-07-11 10:09:24', '2025-07-11 10:09:24');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (165, 28, 'baseline', 'khmer', 'Comp. 2', 78, '2025-06-30', '2025-07-11 10:09:24', '2025-07-11 10:09:24');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (166, 28, 'baseline', 'math', 'Subtraction', 55, '2025-06-21', '2025-07-11 10:09:25', '2025-07-11 10:09:25');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (167, 29, 'baseline', 'khmer', 'Beginner', 29, '2025-07-10', '2025-07-11 10:09:26', '2025-07-11 10:09:26');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (168, 29, 'baseline', 'math', 'Word Problem', 90, '2025-06-12', '2025-07-11 10:09:26', '2025-07-11 10:09:26');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (169, 30, 'baseline', 'khmer', 'Letter', 89, '2025-06-25', '2025-07-11 10:09:27', '2025-07-11 10:09:27');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (170, 30, 'baseline', 'math', 'Word Problem', 62, '2025-06-21', '2025-07-11 10:09:27', '2025-07-11 10:09:27');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (171, 31, 'baseline', 'khmer', 'Word', 6, '2025-06-29', '2025-07-11 10:09:28', '2025-07-11 10:09:28');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (172, 31, 'baseline', 'math', 'Subtraction', 42, '2025-06-11', '2025-07-11 10:09:29', '2025-07-11 10:09:29');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (173, 32, 'baseline', 'khmer', 'Paragraph', 23, '2025-06-24', '2025-07-11 10:09:29', '2025-07-11 10:09:29');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (174, 32, 'baseline', 'math', 'Subtraction', 56, '2025-06-26', '2025-07-11 10:09:30', '2025-07-11 10:09:30');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (175, 33, 'baseline', 'khmer', 'Story', 17, '2025-06-21', '2025-07-11 10:09:31', '2025-07-11 10:09:31');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (176, 33, 'baseline', 'math', 'Subtraction', 83, '2025-06-22', '2025-07-11 10:09:31', '2025-07-11 10:09:31');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (177, 34, 'baseline', 'khmer', 'Comp. 1', 18, '2025-06-17', '2025-07-11 10:09:32', '2025-07-11 10:09:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (178, 34, 'baseline', 'math', '2-Digit', 83, '2025-07-10', '2025-07-11 10:09:32', '2025-07-11 10:09:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (179, 35, 'baseline', 'khmer', 'Comp. 2', 21, '2025-06-27', '2025-07-11 10:09:33', '2025-07-11 10:09:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (180, 35, 'baseline', 'math', 'Division', 57, '2025-07-05', '2025-07-11 10:09:34', '2025-07-11 10:09:34');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (181, 36, 'baseline', 'khmer', 'Beginner', 95, '2025-07-10', '2025-07-11 10:09:34', '2025-07-11 10:09:34');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (182, 36, 'baseline', 'math', 'Subtraction', 21, '2025-07-05', '2025-07-11 10:09:35', '2025-07-11 10:09:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (183, 37, 'baseline', 'khmer', 'Letter', 58, '2025-06-12', '2025-07-11 10:09:35', '2025-07-11 10:09:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (184, 37, 'baseline', 'math', 'Beginner', 93, '2025-07-03', '2025-07-11 10:09:36', '2025-07-11 10:09:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (185, 38, 'baseline', 'khmer', 'Word', 23, '2025-06-24', '2025-07-11 10:09:37', '2025-07-11 10:09:37');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (186, 38, 'baseline', 'math', 'Subtraction', 18, '2025-06-16', '2025-07-11 10:09:37', '2025-07-11 10:09:37');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (187, 39, 'baseline', 'khmer', 'Paragraph', 13, '2025-07-04', '2025-07-11 10:09:38', '2025-07-11 10:09:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (188, 39, 'baseline', 'math', 'Subtraction', 85, '2025-06-14', '2025-07-11 10:09:38', '2025-07-11 10:09:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (189, 40, 'baseline', 'khmer', 'Story', 77, '2025-06-27', '2025-07-11 10:09:39', '2025-07-11 10:09:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (190, 40, 'baseline', 'math', 'Word Problem', 48, '2025-06-30', '2025-07-11 10:09:40', '2025-07-11 10:09:40');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (191, 41, 'baseline', 'khmer', 'Comp. 1', 49, '2025-06-14', '2025-07-11 10:09:40', '2025-07-11 10:09:40');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (192, 41, 'baseline', 'math', 'Word Problem', 32, '2025-07-09', '2025-07-11 10:09:41', '2025-07-11 10:09:41');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (193, 42, 'baseline', 'khmer', 'Comp. 2', 90, '2025-06-15', '2025-07-11 10:09:42', '2025-07-11 10:09:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (194, 42, 'baseline', 'math', 'Word Problem', 87, '2025-07-09', '2025-07-11 10:09:42', '2025-07-11 10:09:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (195, 43, 'baseline', 'khmer', 'Beginner', 38, '2025-06-24', '2025-07-11 10:09:43', '2025-07-11 10:09:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (196, 43, 'baseline', 'math', 'Division', 35, '2025-06-18', '2025-07-11 10:09:43', '2025-07-11 10:09:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (197, 44, 'baseline', 'khmer', 'Letter', 38, '2025-06-28', '2025-07-11 10:09:44', '2025-07-11 10:09:44');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (198, 44, 'baseline', 'math', 'Word Problem', 87, '2025-07-08', '2025-07-11 10:09:45', '2025-07-11 10:09:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (199, 45, 'baseline', 'khmer', 'Word', 90, '2025-07-07', '2025-07-11 10:09:45', '2025-07-11 10:09:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (200, 45, 'baseline', 'math', 'Division', 85, '2025-07-05', '2025-07-11 10:09:46', '2025-07-11 10:09:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (201, 46, 'baseline', 'khmer', 'Paragraph', 58, '2025-07-06', '2025-07-11 10:09:46', '2025-07-11 10:09:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (202, 46, 'baseline', 'math', 'Word Problem', 35, '2025-07-05', '2025-07-11 10:09:47', '2025-07-11 10:09:47');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (203, 47, 'baseline', 'khmer', 'Story', 12, '2025-06-29', '2025-07-11 10:09:48', '2025-07-11 10:09:48');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (204, 47, 'baseline', 'math', 'Word Problem', 33, '2025-06-24', '2025-07-11 10:09:48', '2025-07-11 10:09:48');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (205, 48, 'baseline', 'khmer', 'Comp. 1', 15, '2025-06-11', '2025-07-11 10:09:49', '2025-07-11 10:09:49');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (206, 48, 'baseline', 'math', 'Subtraction', 57, '2025-07-07', '2025-07-11 10:09:49', '2025-07-11 10:09:49');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (207, 49, 'baseline', 'khmer', 'Comp. 2', 77, '2025-06-30', '2025-07-11 10:09:50', '2025-07-11 10:09:50');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (208, 49, 'baseline', 'math', '2-Digit', 69, '2025-06-20', '2025-07-11 10:09:51', '2025-07-11 10:09:51');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (209, 50, 'baseline', 'khmer', 'Beginner', 29, '2025-06-13', '2025-07-11 10:09:51', '2025-07-11 10:09:51');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (210, 50, 'baseline', 'math', 'Subtraction', 10, '2025-06-15', '2025-07-11 10:09:52', '2025-07-11 10:09:52');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (211, 51, 'baseline', 'khmer', 'Letter', 18, '2025-07-11', '2025-07-11 10:09:53', '2025-07-11 15:34:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (212, 51, 'baseline', 'math', 'Word Problem', 28, '2025-06-17', '2025-07-11 10:09:53', '2025-07-11 10:09:53');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (213, 52, 'baseline', 'khmer', 'Word', 72, '2025-07-09', '2025-07-11 10:09:54', '2025-07-11 10:09:54');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (214, 52, 'baseline', 'math', 'Word Problem', 54, '2025-06-18', '2025-07-11 10:09:54', '2025-07-11 10:09:54');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (215, 53, 'baseline', 'khmer', 'Paragraph', 84, '2025-07-07', '2025-07-11 10:09:55', '2025-07-11 10:09:55');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (216, 53, 'baseline', 'math', 'Subtraction', 29, '2025-07-07', '2025-07-11 10:09:56', '2025-07-11 10:09:56');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (217, 54, 'baseline', 'khmer', 'Story', 67, '2025-06-19', '2025-07-11 10:09:56', '2025-07-11 10:09:56');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (218, 54, 'baseline', 'math', '1-Digit', 3, '2025-06-17', '2025-07-11 10:09:57', '2025-07-11 10:09:57');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (219, 55, 'baseline', 'khmer', 'Comp. 1', 99, '2025-06-20', '2025-07-11 10:09:57', '2025-07-11 10:09:57');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (220, 55, 'baseline', 'math', 'Word Problem', 55, '2025-06-24', '2025-07-11 10:09:58', '2025-07-11 10:09:58');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (221, 56, 'baseline', 'khmer', 'Comp. 2', 70, '2025-06-14', '2025-07-11 10:09:59', '2025-07-11 10:09:59');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (222, 56, 'baseline', 'math', 'Word Problem', 92, '2025-07-08', '2025-07-11 10:09:59', '2025-07-11 10:09:59');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (223, 57, 'baseline', 'khmer', 'Beginner', 77, '2025-06-22', '2025-07-11 10:10:00', '2025-07-11 10:10:00');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (224, 57, 'baseline', 'math', '1-Digit', 53, '2025-07-03', '2025-07-11 10:10:01', '2025-07-11 10:10:01');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (225, 58, 'baseline', 'khmer', 'Letter', 34, '2025-07-08', '2025-07-11 10:10:01', '2025-07-11 10:10:01');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (226, 58, 'baseline', 'math', 'Subtraction', 31, '2025-07-05', '2025-07-11 10:10:02', '2025-07-11 10:10:02');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (227, 59, 'baseline', 'khmer', 'Word', 82, '2025-07-07', '2025-07-11 10:10:02', '2025-07-11 10:10:02');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (228, 59, 'baseline', 'math', 'Word Problem', 72, '2025-06-21', '2025-07-11 10:10:03', '2025-07-11 10:10:03');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (229, 60, 'baseline', 'khmer', 'Paragraph', 60, '2025-06-28', '2025-07-11 10:10:04', '2025-07-11 10:10:04');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (230, 60, 'baseline', 'math', 'Subtraction', 5, '2025-06-24', '2025-07-11 10:10:04', '2025-07-11 10:10:04');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (231, 61, 'baseline', 'khmer', 'Story', 9, '2025-06-21', '2025-07-11 10:10:05', '2025-07-11 10:10:05');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (232, 61, 'baseline', 'math', 'Division', 100, '2025-07-09', '2025-07-11 10:10:05', '2025-07-11 10:10:05');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (233, 62, 'baseline', 'khmer', 'Comp. 1', 26, '2025-07-05', '2025-07-11 10:10:06', '2025-07-11 10:10:06');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (234, 62, 'baseline', 'math', 'Word Problem', 87, '2025-06-19', '2025-07-11 10:10:07', '2025-07-11 10:10:07');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (235, 63, 'baseline', 'khmer', 'Comp. 2', 50, '2025-07-02', '2025-07-11 10:10:07', '2025-07-11 10:10:07');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (236, 63, 'baseline', 'math', '1-Digit', 12, '2025-06-11', '2025-07-11 10:10:08', '2025-07-11 10:10:08');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (237, 64, 'baseline', 'khmer', 'Beginner', 100, '2025-06-17', '2025-07-11 10:10:09', '2025-07-11 10:10:09');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (238, 64, 'baseline', 'math', 'Division', 85, '2025-06-19', '2025-07-11 10:10:09', '2025-07-11 10:10:09');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (239, 65, 'baseline', 'khmer', 'Letter', 48, '2025-06-12', '2025-07-11 10:10:10', '2025-07-11 10:10:10');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (240, 65, 'baseline', 'math', 'Subtraction', 25, '2025-07-06', '2025-07-11 10:10:10', '2025-07-11 10:10:10');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (241, 66, 'baseline', 'khmer', 'Word', 42, '2025-06-22', '2025-07-11 10:10:11', '2025-07-11 10:10:11');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (242, 66, 'baseline', 'math', 'Word Problem', 57, '2025-06-21', '2025-07-11 10:10:12', '2025-07-11 10:10:12');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (243, 67, 'baseline', 'khmer', 'Paragraph', 53, '2025-06-19', '2025-07-11 10:10:12', '2025-07-11 10:10:12');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (244, 67, 'baseline', 'math', '1-Digit', 30, '2025-07-03', '2025-07-11 10:10:13', '2025-07-11 10:10:13');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (245, 68, 'baseline', 'khmer', 'Story', 25, '2025-07-04', '2025-07-11 10:10:14', '2025-07-11 10:10:14');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (246, 68, 'baseline', 'math', 'Word Problem', 99, '2025-06-20', '2025-07-11 10:10:14', '2025-07-11 10:10:14');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (247, 69, 'baseline', 'khmer', 'Comp. 1', 61, '2025-07-01', '2025-07-11 10:10:15', '2025-07-11 10:10:15');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (248, 69, 'baseline', 'math', 'Word Problem', 25, '2025-06-28', '2025-07-11 10:10:15', '2025-07-11 10:10:15');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (249, 70, 'baseline', 'khmer', 'Comp. 2', 71, '2025-06-21', '2025-07-11 10:10:16', '2025-07-11 10:10:16');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (250, 70, 'baseline', 'math', 'Subtraction', 33, '2025-06-26', '2025-07-11 10:10:17', '2025-07-11 10:10:17');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (251, 71, 'baseline', 'khmer', 'Beginner', 88, '2025-06-26', '2025-07-11 10:10:17', '2025-07-11 10:10:17');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (252, 71, 'baseline', 'math', 'Division', 21, '2025-06-20', '2025-07-11 10:10:18', '2025-07-11 10:10:18');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (253, 72, 'baseline', 'khmer', 'Letter', 93, '2025-06-12', '2025-07-11 10:10:19', '2025-07-11 10:10:19');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (254, 72, 'baseline', 'math', 'Subtraction', 43, '2025-06-26', '2025-07-11 10:10:19', '2025-07-11 10:10:19');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (255, 73, 'baseline', 'khmer', 'Word', 73, '2025-06-16', '2025-07-11 10:10:20', '2025-07-11 10:10:20');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (256, 73, 'baseline', 'math', 'Subtraction', 97, '2025-06-28', '2025-07-11 10:10:20', '2025-07-11 10:10:20');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (257, 74, 'baseline', 'khmer', 'Paragraph', 21, '2025-06-21', '2025-07-11 10:10:21', '2025-07-11 10:10:21');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (258, 74, 'baseline', 'math', 'Division', 43, '2025-06-13', '2025-07-11 10:10:22', '2025-07-11 10:10:22');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (259, 75, 'baseline', 'khmer', 'Story', 20, '2025-06-28', '2025-07-11 10:10:22', '2025-07-11 10:10:22');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (260, 75, 'baseline', 'math', '1-Digit', 13, '2025-06-26', '2025-07-11 10:10:23', '2025-07-11 10:10:23');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (261, 76, 'baseline', 'khmer', 'Comp. 1', 9, '2025-06-14', '2025-07-11 10:10:24', '2025-07-11 10:10:24');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (262, 76, 'baseline', 'math', 'Division', 79, '2025-07-01', '2025-07-11 10:10:24', '2025-07-11 10:10:24');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (263, 77, 'baseline', 'khmer', 'Comp. 2', 24, '2025-06-12', '2025-07-11 10:10:25', '2025-07-11 10:10:25');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (264, 77, 'baseline', 'math', 'Word Problem', 28, '2025-06-26', '2025-07-11 10:10:25', '2025-07-11 10:10:25');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (265, 78, 'baseline', 'khmer', 'Beginner', 62, '2025-07-05', '2025-07-11 10:10:26', '2025-07-11 10:10:26');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (266, 78, 'baseline', 'math', 'Word Problem', 52, '2025-06-21', '2025-07-11 10:10:27', '2025-07-11 10:10:27');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (267, 79, 'baseline', 'khmer', 'Letter', 75, '2025-07-06', '2025-07-11 10:10:27', '2025-07-11 10:10:27');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (268, 79, 'baseline', 'math', 'Word Problem', 56, '2025-06-28', '2025-07-11 10:10:28', '2025-07-11 10:10:28');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (269, 80, 'baseline', 'khmer', 'Word', 36, '2025-07-10', '2025-07-11 10:10:28', '2025-07-11 10:10:28');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (270, 80, 'baseline', 'math', 'Word Problem', 51, '2025-06-26', '2025-07-11 10:10:29', '2025-07-11 10:10:29');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (271, 81, 'baseline', 'khmer', 'Paragraph', 53, '2025-06-14', '2025-07-11 10:10:30', '2025-07-11 10:10:30');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (272, 81, 'baseline', 'math', 'Subtraction', 83, '2025-07-01', '2025-07-11 10:10:30', '2025-07-11 10:10:30');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (273, 82, 'baseline', 'khmer', 'Story', 7, '2025-06-25', '2025-07-11 10:10:31', '2025-07-11 10:10:31');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (274, 82, 'baseline', 'math', '2-Digit', 90, '2025-06-17', '2025-07-11 10:10:31', '2025-07-11 10:10:31');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (275, 83, 'baseline', 'khmer', 'Comp. 1', 92, '2025-06-16', '2025-07-11 10:10:32', '2025-07-11 10:10:32');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (276, 83, 'baseline', 'math', '1-Digit', 94, '2025-06-26', '2025-07-11 10:10:33', '2025-07-11 10:10:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (277, 84, 'baseline', 'khmer', 'Comp. 2', 11, '2025-06-20', '2025-07-11 10:10:33', '2025-07-11 10:10:33');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (278, 84, 'baseline', 'math', '1-Digit', 56, '2025-06-29', '2025-07-11 10:10:34', '2025-07-11 10:10:34');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (279, 85, 'baseline', 'khmer', 'Beginner', 78, '2025-07-05', '2025-07-11 10:10:35', '2025-07-11 10:10:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (280, 85, 'baseline', 'math', 'Beginner', 22, '2025-06-13', '2025-07-11 10:10:35', '2025-07-11 10:10:35');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (281, 86, 'baseline', 'khmer', 'Letter', 20, '2025-07-03', '2025-07-11 10:10:36', '2025-07-11 10:10:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (282, 86, 'baseline', 'math', 'Subtraction', 100, '2025-07-03', '2025-07-11 10:10:36', '2025-07-11 10:10:36');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (283, 87, 'baseline', 'khmer', 'Word', 11, '2025-06-29', '2025-07-11 10:10:37', '2025-07-11 10:10:37');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (284, 87, 'baseline', 'math', 'Division', 30, '2025-06-27', '2025-07-11 10:10:38', '2025-07-11 10:10:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (285, 88, 'baseline', 'khmer', 'Paragraph', 33, '2025-07-04', '2025-07-11 10:10:38', '2025-07-11 10:10:38');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (286, 88, 'baseline', 'math', 'Word Problem', 8, '2025-06-19', '2025-07-11 10:10:39', '2025-07-11 10:10:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (287, 89, 'baseline', 'khmer', 'Story', 9, '2025-07-08', '2025-07-11 10:10:39', '2025-07-11 10:10:39');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (288, 89, 'baseline', 'math', '1-Digit', 12, '2025-06-21', '2025-07-11 10:10:40', '2025-07-11 10:10:40');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (289, 90, 'baseline', 'khmer', 'Comp. 1', 62, '2025-06-22', '2025-07-11 10:10:41', '2025-07-11 10:10:41');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (290, 90, 'baseline', 'math', 'Word Problem', 75, '2025-07-09', '2025-07-11 10:10:41', '2025-07-11 10:10:41');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (291, 91, 'baseline', 'khmer', 'Comp. 2', 91, '2025-06-21', '2025-07-11 10:10:42', '2025-07-11 10:10:42');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (292, 91, 'baseline', 'math', 'Division', 35, '2025-07-05', '2025-07-11 10:10:43', '2025-07-11 10:10:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (293, 92, 'baseline', 'khmer', 'Beginner', 35, '2025-07-04', '2025-07-11 10:10:43', '2025-07-11 10:10:43');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (294, 92, 'baseline', 'math', '1-Digit', 73, '2025-06-11', '2025-07-11 10:10:44', '2025-07-11 10:10:44');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (295, 93, 'baseline', 'khmer', 'Letter', 28, '2025-07-06', '2025-07-11 10:10:44', '2025-07-11 10:10:44');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (296, 93, 'baseline', 'math', 'Word Problem', 90, '2025-06-27', '2025-07-11 10:10:45', '2025-07-11 10:10:45');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (297, 94, 'baseline', 'khmer', 'Word', 36, '2025-06-23', '2025-07-11 10:10:46', '2025-07-11 10:10:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (298, 94, 'baseline', 'math', 'Word Problem', 46, '2025-06-16', '2025-07-11 10:10:46', '2025-07-11 10:10:46');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (299, 95, 'baseline', 'khmer', 'Paragraph', 99, '2025-06-19', '2025-07-11 10:10:47', '2025-07-11 10:10:47');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (300, 95, 'baseline', 'math', '2-Digit', 33, '2025-07-02', '2025-07-11 10:10:47', '2025-07-11 10:10:47');
INSERT INTO `assessments` (`id`, `student_id`, `cycle`, `subject`, `level`, `score`, `assessed_at`, `created_at`, `updated_at`) VALUES (301, 118, 'baseline', 'khmer', 'Comp. 1', 0, '2025-07-11', '2025-07-11 15:34:24', '2025-07-11 15:34:24');
COMMIT;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for classes
-- ----------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_level` int NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `idx_school_teacher` (`school_id`,`teacher_id`),
  KEY `idx_school_grade` (`school_id`,`grade_level`),
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of classes
-- ----------------------------
BEGIN;
INSERT INTO `classes` (`id`, `name`, `grade_level`, `school_id`, `teacher_id`, `academic_year`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Class 1', 1, 12, NULL, '2025-2026', 1, '2025-07-12 00:00:03', '2025-07-12 00:00:03');
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
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

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
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

-- ----------------------------
-- Records of job_batches
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
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

-- ----------------------------
-- Records of jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for mentoring_visits
-- ----------------------------
DROP TABLE IF EXISTS `mentoring_visits`;
CREATE TABLE `mentoring_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mentor_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `visit_date` date NOT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `score` int DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `questionnaire_data` json DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_in_session` tinyint(1) DEFAULT NULL,
  `class_not_in_session_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_session_observed` tinyint(1) DEFAULT NULL,
  `grade_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grades_observed` json DEFAULT NULL,
  `subject_observed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_levels_observed` json DEFAULT NULL,
  `numeracy_levels_observed` json DEFAULT NULL,
  `action_plan` text COLLATE utf8mb4_unicode_ci,
  `follow_up_required` tinyint(1) DEFAULT '0',
  `class_started_on_time` tinyint(1) DEFAULT NULL,
  `late_start_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materials_present` json DEFAULT NULL,
  `children_grouped_appropriately` tinyint(1) DEFAULT NULL,
  `students_fully_involved` tinyint(1) DEFAULT NULL,
  `has_session_plan` tinyint(1) DEFAULT NULL,
  `no_session_plan_reason` text COLLATE utf8mb4_unicode_ci,
  `followed_session_plan` tinyint(1) DEFAULT NULL,
  `no_follow_plan_reason` text COLLATE utf8mb4_unicode_ci,
  `session_plan_appropriate` tinyint(1) DEFAULT NULL,
  `number_of_activities` int DEFAULT NULL,
  `activity1_name_language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity1_name_numeracy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity1_duration` int DEFAULT NULL,
  `activity1_clear_instructions` tinyint(1) DEFAULT NULL,
  `activity1_no_clear_instructions_reason` text COLLATE utf8mb4_unicode_ci,
  `activity1_demonstrated` tinyint(1) DEFAULT NULL,
  `activity1_students_practice` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity1_small_groups` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity1_individual` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity2_name_language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity2_name_numeracy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity2_duration` int DEFAULT NULL,
  `activity2_clear_instructions` tinyint(1) DEFAULT NULL,
  `activity2_no_clear_instructions_reason` text COLLATE utf8mb4_unicode_ci,
  `activity2_demonstrated` tinyint(1) DEFAULT NULL,
  `activity2_students_practice` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity2_small_groups` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity2_individual` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity3_name_language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity3_name_numeracy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity3_duration` int DEFAULT NULL,
  `activity3_clear_instructions` tinyint(1) DEFAULT NULL,
  `activity3_no_clear_instructions_reason` text COLLATE utf8mb4_unicode_ci,
  `activity3_demonstrated` tinyint(1) DEFAULT NULL,
  `activity3_students_practice` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity3_small_groups` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity3_individual` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mentoring_visits_mentor_id_foreign` (`mentor_id`),
  KEY `mentoring_visits_school_id_foreign` (`school_id`),
  KEY `mentoring_visits_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `mentoring_visits_mentor_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mentoring_visits_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mentoring_visits_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of mentoring_visits
-- ----------------------------
BEGIN;
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (1, 3, 6, 4, '2025-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:09', '2025-07-11 08:56:09');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (2, 2, 1, 5, '2025-06-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:10', '2025-07-11 08:56:10');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (3, 3, 6, 5, '2025-06-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:10', '2025-07-11 08:56:10');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (4, 3, 6, 5, '2025-06-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:11', '2025-07-11 08:56:11');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (5, 3, 4, 4, '2025-07-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:12', '2025-07-11 08:56:12');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (6, 3, 1, 5, '2025-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:12', '2025-07-11 08:56:12');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (7, 2, 5, 5, '2025-07-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:13', '2025-07-11 08:56:13');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (8, 2, 1, 4, '2025-06-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:13', '2025-07-11 08:56:13');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (9, 3, 1, 5, '2025-07-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:14', '2025-07-11 08:56:14');
INSERT INTO `mentoring_visits` (`id`, `mentor_id`, `school_id`, `teacher_id`, `visit_date`, `observation`, `score`, `photo`, `questionnaire_data`, `region`, `province`, `program_type`, `class_in_session`, `class_not_in_session_reason`, `full_session_observed`, `grade_group`, `grades_observed`, `subject_observed`, `language_levels_observed`, `numeracy_levels_observed`, `action_plan`, `follow_up_required`, `class_started_on_time`, `late_start_reason`, `materials_present`, `children_grouped_appropriately`, `students_fully_involved`, `has_session_plan`, `no_session_plan_reason`, `followed_session_plan`, `no_follow_plan_reason`, `session_plan_appropriate`, `number_of_activities`, `activity1_name_language`, `activity1_name_numeracy`, `activity1_duration`, `activity1_clear_instructions`, `activity1_no_clear_instructions_reason`, `activity1_demonstrated`, `activity1_students_practice`, `activity1_small_groups`, `activity1_individual`, `activity2_name_language`, `activity2_name_numeracy`, `activity2_duration`, `activity2_clear_instructions`, `activity2_no_clear_instructions_reason`, `activity2_demonstrated`, `activity2_students_practice`, `activity2_small_groups`, `activity2_individual`, `activity3_name_language`, `activity3_name_numeracy`, `activity3_duration`, `activity3_clear_instructions`, `activity3_no_clear_instructions_reason`, `activity3_demonstrated`, `activity3_students_practice`, `activity3_small_groups`, `activity3_individual`, `created_at`, `updated_at`) VALUES (10, 3, 5, 4, '2025-06-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 08:56:14', '2025-07-11 08:56:14');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2025_07_11_073200_create_schools_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2025_07_11_073201_update_users_table_add_role', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2025_07_11_073211_create_students_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2025_07_11_073216_create_assessments_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2025_07_11_073222_create_mentoring_visits_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2025_07_11_073911_add_is_active_to_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11, '2025_07_11_100151_add_gender_to_students_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12, '2025_07_11_105307_update_schools_table_structure', 3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13, '2025_07_11_164710_create_classes_table', 3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14, '2025_07_11_164741_add_teacher_and_class_to_students_table', 3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15, '2025_07_12_011905_update_mentoring_visits_for_questionnaire', 4);
COMMIT;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for schools
-- ----------------------------
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cluster` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schools_school_code_unique` (`school_code`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of schools
-- ----------------------------
BEGIN;
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (1, 'Battambang', 'Rattanakmondol', 'បឹងខ្ទុម', 'ផ្លូវមាស', '2070301014', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (2, 'Battambang', 'Rattanakmondol', 'បឹងខ្ទុម', 'បឹងខ្ទុម', '2070303016', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (3, 'Battambang', 'Rattanakmondol', 'បឹងខ្ទុម', 'អូរដា', '2070306030', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (4, 'Battambang', 'Rattanakmondol', 'ត្រែង', 'ត្រែង', '2070401007', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (5, 'Battambang', 'Rattanakmondol', 'ត្រែង', 'មាសពិទូគីឡូ៣៨', '2070405004', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (6, 'Battambang', 'Rattanakmondol', 'ត្រែង', 'សុវត្ថិភាពកុម៉ាតស៊ុ', '2070401044', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (7, 'Battambang', 'Rattanakmondol', 'បាដាក', 'បាដាក', '2070503013', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (8, 'Battambang', 'Rattanakmondol', 'បាដាក', 'ពេជ្រចង្វា', '2070502020', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (9, 'Battambang', 'Rattanakmondol', 'បាដាក', 'នាងពួន', '2070508029', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (10, 'Battambang', 'Rattanakmondol', 'ព្រៃអំពរ', 'ស៊ិនតុ', '2070203001', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (11, 'Battambang', 'Rattanakmondol', 'ព្រៃអំពរ', 'ព្រៃអំពរ', '2070205008', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (12, 'Battambang', 'Rattanakmondol', 'ព្រៃអំពរ', 'កណ្តាលស្ទឹង', '2070206011', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (13, 'Battambang', 'Rattanakmondol', 'ភ្ជាវ', 'ភ្ជាវ', '2070402015', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (14, 'Battambang', 'Rattanakmondol', 'ភ្ជាវ', 'ស្វាយស', '2070409032', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (15, 'Battambang', 'Rattanakmondol', 'ភ្ជាវ', 'តាគ្រក់', '2070408031', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (16, 'Kampongcham', 'Kgmeas', 'កម្រងស្តៅ', 'ស្ដៅលើ', '3071003033', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (17, 'Kampongcham', 'Kgmeas', 'កម្រងស្តៅ', 'ស្ដៅ', '3071005034', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (18, 'Kampongcham', 'Kgmeas', 'កម្រងស្តៅ', 'វត្តចាស់', '3070208044', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (19, 'Kampongcham', 'Kgmeas', 'កម្រងស្តៅ', 'ព្រែកត្រឡោក', '3070202039', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (20, 'Kampongcham', 'Kgmeas', 'កម្រងព្រែកលីវ', 'ខ្ចៅ', '3070307008', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (21, 'Kampongcham', 'Kgmeas', 'កម្រងព្រែកលីវ', 'ស្វាយស្រណោះ', '3070807027', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (22, 'Kampongcham', 'Kgmeas', 'កម្រងព្រែកលីវ', 'រការអារ', '3070809028', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (23, 'Kampongcham', 'Kgmeas', 'កម្រងព្រែកកុយ', 'អូរស្វាយ', '3070506017', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (24, 'Kampongcham', 'Kgmeas', 'កម្រងព្រែកកុយ', 'ព្រែកកុយ', '3070501014', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (25, 'Kampongcham', 'Kgmeas', 'កម្រងអង្គរបាន', 'សាខា២', '3070108004', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (26, 'Kampongcham', 'Kgmeas', 'កម្រងអង្គរបាន', 'អន្ទង់ស', '3070101001', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (27, 'Kampongcham', 'Kgmeas', 'កម្រងអង្គរបាន', 'សាខា១', '3070104002', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (28, 'Kampongcham', 'Kgmeas', 'កម្រងរាយប៉ាយ', 'ទួលវិហារ', '3070703042', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (29, 'Kampongcham', 'Kgmeas', 'កម្រងរាយប៉ាយ', 'រាយប៉ាយ', '3070601018', NULL, NULL);
INSERT INTO `schools` (`id`, `province`, `district`, `cluster`, `school_name`, `school_code`, `created_at`, `updated_at`) VALUES (30, 'Kampongcham', 'Kgmeas', 'កម្រងរាយប៉ាយ', 'ទួលបី', '3070704023', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
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

-- ----------------------------
-- Records of sessions
-- ----------------------------
BEGIN;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('Kb3KHOoSKAVdjzkFy5XXfFNK2HKFacM3w3NfmRSe', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Avast/137.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNUdST09FMVdPRmgza3I5MGQwVXpsaXRGVktJR0xIQ1hYMURNalRycyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvYXNzZXNzbWVudHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoibG9jYWxlIjtzOjI6ImttIjt9', 1752245196);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('pUuQiQTBti2udTFei5Atilfeguk4gqExmQUMhcnJ', NULL, '127.0.0.1', 'curl/8.7.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVVKNVdxdXpqZGFxMmxYWGs5M0JNUXF4VUR2a2JrMWhHQW52VHZNUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752235518);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('RxBHiPUrrPt7qeUIdxZ7aXFwAdDq2Nt8QWgu6ddQ', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Avast/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHdTQVYxQTFuUkx6bk5VM2NwT3BEazhKUXNETUdIQXo1NUgzOXhweSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1752232846);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('vkcZjZnd0sYV8YMHkdsqRGi5Y7TOUwE64CDZkYyb', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Avast/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWnpmRm5UOThyMXB1T0g5V2N2MW9zRnp2dG1oWXVsbFZpNkpVWU1yQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1752236660);
COMMIT;

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` int DEFAULT NULL,
  `sex` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `idx_teacher_class` (`teacher_id`,`class_id`),
  KEY `idx_school_teacher` (`school_id`,`teacher_id`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of students
-- ----------------------------
BEGIN;
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (1, 'Chan Sam', NULL, 1, 'female', 8, 'Grade 1', 1, NULL, NULL, NULL, '2025-07-11 08:25:10', '2025-07-11 08:25:10');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (2, 'Samnang Sam', NULL, 6, 'male', 6, 'Grade 6', 1, NULL, NULL, NULL, '2025-07-11 08:25:11', '2025-07-11 08:25:11');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (3, 'Vanna Ung', NULL, 3, 'female', 12, 'Grade 3', 1, NULL, NULL, NULL, '2025-07-11 08:25:11', '2025-07-11 08:25:11');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (4, 'Dara Ly', NULL, 6, 'male', 10, 'Grade 6', 1, NULL, NULL, NULL, '2025-07-11 08:25:12', '2025-07-11 08:25:12');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (5, 'Chan Lim', NULL, 1, 'male', 7, 'Grade 1', 1, NULL, NULL, NULL, '2025-07-11 08:25:12', '2025-07-11 08:25:12');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (6, 'Chan Chhun', NULL, 1, 'male', 11, 'Grade 1', 1, NULL, NULL, NULL, '2025-07-11 08:25:13', '2025-07-11 08:25:13');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (7, 'Sovann Lim', NULL, 1, 'female', 9, 'Grade 1', 1, NULL, NULL, NULL, '2025-07-11 08:25:14', '2025-07-11 08:25:14');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (8, 'Bopha Keo', 'male', 1, 'female', 6, 'Grade 1', 1, NULL, NULL, NULL, '2025-07-11 08:25:14', '2025-07-11 15:34:30');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (9, 'Vanna Keo', NULL, 4, 'male', 10, 'Grade 4', 1, NULL, NULL, NULL, '2025-07-11 08:25:15', '2025-07-11 08:25:15');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (10, 'Rachana Touch', NULL, 5, 'female', 6, 'Grade 5', 1, NULL, NULL, NULL, '2025-07-11 08:25:15', '2025-07-11 08:25:15');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (11, 'Sophea Ouk', NULL, 1, 'female', 12, 'Grade 1', 1, NULL, NULL, NULL, '2025-07-11 08:25:16', '2025-07-11 08:25:16');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (12, 'Bopha Ung', NULL, 3, 'male', 7, 'Grade 3', 1, NULL, NULL, NULL, '2025-07-11 08:25:17', '2025-07-11 08:25:17');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (13, 'Kosal Ly', NULL, 3, 'male', 12, 'Grade 3', 1, NULL, NULL, NULL, '2025-07-11 08:25:17', '2025-07-11 08:25:17');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (14, 'Srey Sor', NULL, 6, 'male', 9, 'Grade 6', 1, NULL, NULL, NULL, '2025-07-11 08:25:18', '2025-07-11 08:25:18');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (15, 'Kosal Kim', NULL, 3, 'female', 8, 'Grade 3', 1, NULL, NULL, NULL, '2025-07-11 08:25:18', '2025-07-11 08:25:18');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (16, 'Samnang Sor', NULL, 5, 'male', 8, 'Grade 5', 1, NULL, NULL, NULL, '2025-07-11 08:25:19', '2025-07-11 08:25:19');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (17, 'Kosal Chea', NULL, 6, 'female', 6, 'Grade 6', 1, NULL, NULL, NULL, '2025-07-11 08:25:20', '2025-07-11 08:25:20');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (18, 'Phalla Touch', NULL, 4, 'male', 10, 'Grade 4', 1, NULL, NULL, NULL, '2025-07-11 08:25:20', '2025-07-11 08:25:20');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (19, 'Pisey Chea', NULL, 3, 'female', 6, 'Grade 3', 1, NULL, NULL, NULL, '2025-07-11 08:25:21', '2025-07-11 08:25:21');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (20, 'Kosal Chhun', NULL, 6, 'female', 12, 'Grade 6', 1, NULL, NULL, NULL, '2025-07-11 08:25:21', '2025-07-11 08:25:21');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (21, 'Pisey Sor', NULL, 4, 'female', 10, 'Grade 4', 2, NULL, NULL, NULL, '2025-07-11 08:25:22', '2025-07-11 08:25:22');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (22, 'Sovann Keo', NULL, 6, 'male', 11, 'Grade 6', 2, NULL, NULL, NULL, '2025-07-11 08:25:22', '2025-07-11 08:25:22');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (23, 'Srey Heng', NULL, 1, 'male', 6, 'Grade 1', 2, NULL, NULL, NULL, '2025-07-11 08:25:23', '2025-07-11 08:25:23');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (24, 'Sophea Chhun', NULL, 5, 'female', 8, 'Grade 5', 2, NULL, NULL, NULL, '2025-07-11 08:25:24', '2025-07-11 08:25:24');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (25, 'Dara Touch', NULL, 3, 'female', 8, 'Grade 3', 2, NULL, NULL, NULL, '2025-07-11 08:25:24', '2025-07-11 08:25:24');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (26, 'Dara Ly', NULL, 2, 'female', 11, 'Grade 2', 2, NULL, NULL, NULL, '2025-07-11 08:25:25', '2025-07-11 08:25:25');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (27, 'Samnang Ouk', NULL, 3, 'female', 12, 'Grade 3', 2, NULL, NULL, NULL, '2025-07-11 08:25:26', '2025-07-11 08:25:26');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (28, 'Samnang Mao', NULL, 2, 'female', 9, 'Grade 2', 2, NULL, NULL, NULL, '2025-07-11 08:25:26', '2025-07-11 08:25:26');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (29, 'Kosal Keo', NULL, 4, 'male', 7, 'Grade 4', 2, NULL, NULL, NULL, '2025-07-11 08:25:27', '2025-07-11 08:25:27');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (30, 'Kunthea Mao', NULL, 2, 'male', 7, 'Grade 2', 2, NULL, NULL, NULL, '2025-07-11 08:25:27', '2025-07-11 08:25:27');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (31, 'Phalla Lim', NULL, 1, 'male', 11, 'Grade 1', 2, NULL, NULL, NULL, '2025-07-11 08:25:28', '2025-07-11 08:25:28');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (32, 'Sok Chhun', NULL, 3, 'female', 9, 'Grade 3', 2, NULL, NULL, NULL, '2025-07-11 08:25:29', '2025-07-11 08:25:29');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (33, 'Visal Seng', NULL, 6, 'female', 9, 'Grade 6', 2, NULL, NULL, NULL, '2025-07-11 08:25:29', '2025-07-11 08:25:29');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (34, 'Sok Ouk', NULL, 1, 'male', 8, 'Grade 1', 2, NULL, NULL, NULL, '2025-07-11 08:25:30', '2025-07-11 08:25:30');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (35, 'Samnang Heng', NULL, 6, 'female', 12, 'Grade 6', 2, NULL, NULL, NULL, '2025-07-11 08:25:30', '2025-07-11 08:25:30');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (36, 'Chan Kim', NULL, 5, 'male', 12, 'Grade 5', 2, NULL, NULL, NULL, '2025-07-11 08:25:31', '2025-07-11 08:25:31');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (37, 'Visal Mao', NULL, 4, 'male', 11, 'Grade 4', 2, NULL, NULL, NULL, '2025-07-11 08:25:32', '2025-07-11 08:25:32');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (38, 'Phalla Sor', NULL, 5, 'female', 7, 'Grade 5', 2, NULL, NULL, NULL, '2025-07-11 08:25:32', '2025-07-11 08:25:32');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (39, 'Chan Keo', NULL, 6, 'male', 8, 'Grade 6', 2, NULL, NULL, NULL, '2025-07-11 08:25:33', '2025-07-11 08:25:33');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (40, 'Dara Lim', NULL, 2, 'female', 11, 'Grade 2', 2, NULL, NULL, NULL, '2025-07-11 08:25:33', '2025-07-11 08:25:33');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (41, 'Dara Chea', NULL, 6, 'male', 10, 'Grade 6', 3, NULL, NULL, NULL, '2025-07-11 08:25:34', '2025-07-11 08:25:34');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (42, 'Sophea Sor', NULL, 3, 'male', 12, 'Grade 3', 3, NULL, NULL, NULL, '2025-07-11 08:25:35', '2025-07-11 08:25:35');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (43, 'Vanna Touch', NULL, 3, 'female', 8, 'Grade 3', 3, NULL, NULL, NULL, '2025-07-11 08:25:36', '2025-07-11 08:25:36');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (44, 'Pisey Chea', NULL, 5, 'female', 7, 'Grade 5', 3, NULL, NULL, NULL, '2025-07-11 08:25:36', '2025-07-11 08:25:36');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (45, 'Pisey Ung', NULL, 6, 'male', 9, 'Grade 6', 3, NULL, NULL, NULL, '2025-07-11 08:25:37', '2025-07-11 08:25:37');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (46, 'Phalla Pich', NULL, 1, 'female', 10, 'Grade 1', 3, NULL, NULL, NULL, '2025-07-11 08:25:37', '2025-07-11 08:25:37');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (47, 'Kunthea Keo', NULL, 3, 'female', 11, 'Grade 3', 3, NULL, NULL, NULL, '2025-07-11 08:25:38', '2025-07-11 08:25:38');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (48, 'Dara Chhun', NULL, 3, 'female', 12, 'Grade 3', 3, NULL, NULL, NULL, '2025-07-11 08:25:39', '2025-07-11 08:25:39');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (49, 'Dara Chea', NULL, 4, 'female', 10, 'Grade 4', 3, NULL, NULL, NULL, '2025-07-11 08:25:39', '2025-07-11 08:25:39');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (50, 'Dara Ouk', NULL, 5, 'female', 11, 'Grade 5', 3, NULL, NULL, NULL, '2025-07-11 08:25:40', '2025-07-11 08:25:40');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (51, 'Bopha Keo', 'male', 6, 'female', 9, 'Grade 6', 3, NULL, NULL, NULL, '2025-07-11 08:25:40', '2025-07-11 15:34:32');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (52, 'Samnang Chea', NULL, 5, 'male', 6, 'Grade 5', 3, NULL, NULL, NULL, '2025-07-11 08:25:41', '2025-07-11 08:25:41');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (53, 'Kunthea Sam', NULL, 1, 'female', 12, 'Grade 1', 3, NULL, NULL, NULL, '2025-07-11 08:25:42', '2025-07-11 08:25:42');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (54, 'Sok Seng', NULL, 3, 'female', 11, 'Grade 3', 3, NULL, NULL, NULL, '2025-07-11 08:25:42', '2025-07-11 08:25:42');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (55, 'Visal Ouk', NULL, 6, 'female', 12, 'Grade 6', 3, NULL, NULL, NULL, '2025-07-11 08:25:43', '2025-07-11 08:25:43');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (56, 'Phalla Sam', NULL, 4, 'male', 11, 'Grade 4', 3, NULL, NULL, NULL, '2025-07-11 08:25:43', '2025-07-11 08:25:43');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (57, 'Vanna Seng', NULL, 4, 'female', 11, 'Grade 4', 3, NULL, NULL, NULL, '2025-07-11 08:25:44', '2025-07-11 08:25:44');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (58, 'Samnang Mao', NULL, 1, 'female', 9, 'Grade 1', 3, NULL, NULL, NULL, '2025-07-11 08:25:45', '2025-07-11 08:25:45');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (59, 'Pisey Keo', NULL, 2, 'male', 9, 'Grade 2', 3, NULL, NULL, NULL, '2025-07-11 08:25:45', '2025-07-11 08:25:45');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (60, 'Bopha Seng', NULL, 3, 'male', 8, 'Grade 3', 3, NULL, NULL, NULL, '2025-07-11 08:25:46', '2025-07-11 08:25:46');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (61, 'Kosal Heng', NULL, 4, 'male', 7, 'Grade 4', 4, NULL, NULL, NULL, '2025-07-11 08:25:46', '2025-07-11 08:25:46');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (62, 'Dara Chhun', NULL, 2, 'female', 12, 'Grade 2', 4, NULL, NULL, NULL, '2025-07-11 08:25:47', '2025-07-11 08:25:47');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (63, 'Rachana Kim', NULL, 4, 'female', 8, 'Grade 4', 4, NULL, NULL, NULL, '2025-07-11 08:25:48', '2025-07-11 08:25:48');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (64, 'Sok Keo', NULL, 1, 'female', 9, 'Grade 1', 4, NULL, NULL, NULL, '2025-07-11 08:25:48', '2025-07-11 08:25:48');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (65, 'Kunthea Ly', NULL, 4, 'female', 7, 'Grade 4', 4, NULL, NULL, NULL, '2025-07-11 08:25:49', '2025-07-11 08:25:49');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (66, 'Sovann Pich', NULL, 2, 'male', 11, 'Grade 2', 4, NULL, NULL, NULL, '2025-07-11 08:25:49', '2025-07-11 08:25:49');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (67, 'Sovann Keo', NULL, 4, 'male', 9, 'Grade 4', 4, NULL, NULL, NULL, '2025-07-11 08:25:50', '2025-07-11 08:25:50');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (68, 'Kunthea Touch', NULL, 1, 'male', 9, 'Grade 1', 4, NULL, NULL, NULL, '2025-07-11 08:25:50', '2025-07-11 08:25:50');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (69, 'Sok Mao', NULL, 1, 'male', 11, 'Grade 1', 4, NULL, NULL, NULL, '2025-07-11 08:25:51', '2025-07-11 08:25:51');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (70, 'Rachana Chea', NULL, 2, 'male', 8, 'Grade 2', 4, NULL, NULL, NULL, '2025-07-11 08:25:52', '2025-07-11 08:25:52');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (71, 'Sok Ly', NULL, 6, 'male', 9, 'Grade 6', 4, NULL, NULL, NULL, '2025-07-11 08:25:52', '2025-07-11 08:25:52');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (72, 'Rachana Mao', NULL, 4, 'female', 10, 'Grade 4', 4, NULL, NULL, NULL, '2025-07-11 08:25:53', '2025-07-11 08:25:53');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (73, 'Kunthea Lim', NULL, 1, 'male', 10, 'Grade 1', 4, NULL, NULL, NULL, '2025-07-11 08:25:53', '2025-07-11 08:25:53');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (74, 'Rachana Ung', NULL, 6, 'female', 12, 'Grade 6', 4, NULL, NULL, NULL, '2025-07-11 08:25:54', '2025-07-11 08:25:54');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (75, 'Sok Sor', NULL, 6, 'female', 9, 'Grade 6', 4, NULL, NULL, NULL, '2025-07-11 08:25:55', '2025-07-11 08:25:55');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (76, 'Srey Lim', NULL, 5, 'male', 7, 'Grade 5', 4, NULL, NULL, NULL, '2025-07-11 08:25:55', '2025-07-11 08:25:55');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (77, 'Samnang Touch', NULL, 6, 'female', 6, 'Grade 6', 4, NULL, NULL, NULL, '2025-07-11 08:25:56', '2025-07-11 08:25:56');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (78, 'Sovann Sam', NULL, 5, 'female', 9, 'Grade 5', 4, NULL, NULL, NULL, '2025-07-11 08:25:57', '2025-07-11 08:25:57');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (79, 'Kosal Kim', NULL, 2, 'female', 10, 'Grade 2', 4, NULL, NULL, NULL, '2025-07-11 08:25:57', '2025-07-11 08:25:57');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (80, 'Samnang Ung', NULL, 5, 'male', 7, 'Grade 5', 4, NULL, NULL, NULL, '2025-07-11 08:25:58', '2025-07-11 08:25:58');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (81, 'Visal Ung', NULL, 6, 'female', 12, 'Grade 6', 5, NULL, NULL, NULL, '2025-07-11 08:25:58', '2025-07-11 08:25:58');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (82, 'Phalla Heng', NULL, 2, 'female', 7, 'Grade 2', 5, NULL, NULL, NULL, '2025-07-11 08:25:59', '2025-07-11 08:25:59');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (83, 'Kosal Touch', NULL, 2, 'male', 12, 'Grade 2', 5, NULL, NULL, NULL, '2025-07-11 08:25:59', '2025-07-11 08:25:59');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (84, 'Sovann Lim', NULL, 3, 'female', 12, 'Grade 3', 5, NULL, NULL, NULL, '2025-07-11 08:26:00', '2025-07-11 08:26:00');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (85, 'Samnang Sor', NULL, 3, 'male', 11, 'Grade 3', 5, NULL, NULL, NULL, '2025-07-11 08:26:01', '2025-07-11 08:26:01');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (86, 'Kosal Ly', NULL, 6, 'male', 9, 'Grade 6', 5, NULL, NULL, NULL, '2025-07-11 08:26:01', '2025-07-11 08:26:01');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (87, 'Kunthea Ung', NULL, 2, 'female', 6, 'Grade 2', 5, NULL, NULL, NULL, '2025-07-11 08:26:02', '2025-07-11 08:26:02');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (88, 'Samnang Seng', NULL, 2, 'male', 12, 'Grade 2', 5, NULL, NULL, NULL, '2025-07-11 08:26:02', '2025-07-11 08:26:02');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (89, 'Sophea Pich', NULL, 3, 'female', 7, 'Grade 3', 5, NULL, NULL, NULL, '2025-07-11 08:26:03', '2025-07-11 08:26:03');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (90, 'Sovann Ung', NULL, 4, 'male', 8, 'Grade 4', 5, NULL, NULL, NULL, '2025-07-11 08:26:04', '2025-07-11 08:26:04');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (91, 'Chan Mao', NULL, 2, 'male', 11, 'Grade 2', 5, NULL, NULL, NULL, '2025-07-11 08:26:04', '2025-07-11 08:26:04');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (92, 'Bopha Ouk', NULL, 5, 'male', 10, 'Grade 5', 5, NULL, NULL, NULL, '2025-07-11 08:26:05', '2025-07-11 08:26:05');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (93, 'Sovann Pich', NULL, 5, 'male', 6, 'Grade 5', 5, NULL, NULL, NULL, '2025-07-11 08:26:05', '2025-07-11 08:26:05');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (94, 'Phalla Heng', NULL, 4, 'male', 12, 'Grade 4', 5, NULL, NULL, NULL, '2025-07-11 08:26:06', '2025-07-11 08:26:06');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (95, 'Phalla Chhun', NULL, 1, 'male', 12, 'Grade 1', 5, NULL, NULL, NULL, '2025-07-11 08:26:07', '2025-07-11 08:26:07');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (96, 'Sophea Keo', NULL, 4, 'male', 11, 'Grade 4', 5, NULL, NULL, NULL, '2025-07-11 08:26:07', '2025-07-11 08:26:07');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (97, 'Dara Ouk', NULL, 1, 'male', 10, 'Grade 1', 5, NULL, NULL, NULL, '2025-07-11 08:26:08', '2025-07-11 08:26:08');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (98, 'Srey Mao', NULL, 6, 'female', 11, 'Grade 6', 5, NULL, NULL, NULL, '2025-07-11 08:26:08', '2025-07-11 08:26:08');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (99, 'Kosal Lim', NULL, 1, 'male', 12, 'Grade 1', 5, NULL, NULL, NULL, '2025-07-11 08:26:09', '2025-07-11 08:26:09');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (100, 'Vanna Ung', NULL, 6, 'male', 10, 'Grade 6', 5, NULL, NULL, NULL, '2025-07-11 08:26:09', '2025-07-11 08:26:09');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (101, 'Rachana Lim', NULL, 4, 'male', 9, 'Grade 4', 6, NULL, NULL, NULL, '2025-07-11 08:26:10', '2025-07-11 08:26:10');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (102, 'Sovann Touch', NULL, 6, 'female', 6, 'Grade 6', 6, NULL, NULL, NULL, '2025-07-11 08:26:11', '2025-07-11 08:26:11');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (103, 'Dara Keo', NULL, 1, 'female', 11, 'Grade 1', 6, NULL, NULL, NULL, '2025-07-11 08:26:11', '2025-07-11 08:26:11');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (104, 'Dara Touch', NULL, 3, 'male', 11, 'Grade 3', 6, NULL, NULL, NULL, '2025-07-11 08:26:12', '2025-07-11 08:26:12');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (105, 'Sok Pich', NULL, 1, 'female', 7, 'Grade 1', 6, NULL, NULL, NULL, '2025-07-11 08:26:12', '2025-07-11 08:26:12');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (106, 'Vanna Ung', NULL, 4, 'male', 9, 'Grade 4', 6, NULL, NULL, NULL, '2025-07-11 08:26:13', '2025-07-11 08:26:13');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (107, 'Rachana Pich', NULL, 3, 'male', 11, 'Grade 3', 6, NULL, NULL, NULL, '2025-07-11 08:26:14', '2025-07-11 08:26:14');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (108, 'Sok Heng', NULL, 3, 'male', 7, 'Grade 3', 6, NULL, NULL, NULL, '2025-07-11 08:26:14', '2025-07-11 08:26:14');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (109, 'Sok Pich', NULL, 4, 'female', 12, 'Grade 4', 6, NULL, NULL, NULL, '2025-07-11 08:26:15', '2025-07-11 08:26:15');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (110, 'Samnang Keo', NULL, 4, 'female', 6, 'Grade 4', 6, NULL, NULL, NULL, '2025-07-11 08:26:15', '2025-07-11 08:26:15');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (111, 'Srey Ly', NULL, 5, 'female', 9, 'Grade 5', 6, NULL, NULL, NULL, '2025-07-11 08:26:16', '2025-07-11 08:26:16');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (112, 'Rachana Keo', NULL, 3, 'male', 11, 'Grade 3', 6, NULL, NULL, NULL, '2025-07-11 08:26:17', '2025-07-11 08:26:17');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (113, 'Sophea Ly', NULL, 2, 'male', 11, 'Grade 2', 6, NULL, NULL, NULL, '2025-07-11 08:26:17', '2025-07-11 08:26:17');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (114, 'Rachana Ouk', NULL, 5, 'male', 11, 'Grade 5', 6, NULL, NULL, NULL, '2025-07-11 08:26:18', '2025-07-11 08:26:18');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (115, 'Samnang Ouk', NULL, 2, 'female', 12, 'Grade 2', 6, NULL, NULL, NULL, '2025-07-11 08:26:18', '2025-07-11 08:26:18');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (116, 'Pisey Ouk', NULL, 2, 'male', 10, 'Grade 2', 6, NULL, NULL, NULL, '2025-07-11 08:26:19', '2025-07-11 08:26:19');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (117, 'Pisey Touch', NULL, 6, 'male', 10, 'Grade 6', 6, NULL, NULL, NULL, '2025-07-11 08:26:20', '2025-07-11 08:26:20');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (118, 'Bopha Chea', 'male', 2, 'female', 10, 'Grade 2', 6, NULL, 1, 'students/photos/RnRoNgcSpG9PPbDpr72oyuShZR0WAUetq1U6iAJZ.jpg', '2025-07-11 08:26:20', '2025-07-12 10:49:44');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (119, 'Rachana Keo', NULL, 6, 'male', 7, 'Grade 6', 6, NULL, NULL, NULL, '2025-07-11 08:26:21', '2025-07-11 08:26:21');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (120, 'Phalla Sam', NULL, 4, 'male', 9, 'Grade 4', 6, NULL, NULL, NULL, '2025-07-11 08:26:21', '2025-07-11 08:26:21');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (121, 'សុភា', NULL, 3, 'male', 9, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:55', '2025-07-11 10:02:55');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (122, 'សុខា', NULL, 3, 'female', 10, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:55', '2025-07-11 10:02:55');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (123, 'ចាន់ធី', NULL, 3, 'male', 8, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:56', '2025-07-11 10:02:56');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (124, 'សុភាព', NULL, 3, 'female', 12, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:57', '2025-07-11 10:02:57');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (125, 'វិសាល', NULL, 3, 'male', 9, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:57', '2025-07-11 10:02:57');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (126, 'សោភា', NULL, 3, 'female', 11, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:58', '2025-07-11 10:02:58');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (127, 'ពិសិដ្ឋ', NULL, 3, 'male', 10, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:58', '2025-07-11 10:02:58');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (128, 'មាលា', NULL, 3, 'female', 10, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:59', '2025-07-11 10:02:59');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (129, 'សុវណ្ណ', NULL, 3, 'male', 10, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:02:59', '2025-07-11 10:02:59');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (130, 'រតនា', NULL, 3, 'female', 10, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:03:00', '2025-07-11 10:03:00');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (131, 'សម្បត្តិ', NULL, 3, 'male', 11, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:03:01', '2025-07-11 10:03:01');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (132, 'សុជាតា', NULL, 3, 'female', 9, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:03:01', '2025-07-11 10:03:01');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (133, 'វិជ្ជា', NULL, 3, 'male', 12, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:03:02', '2025-07-11 10:03:02');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (134, 'ចន្ទ្រា', NULL, 3, 'female', 8, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:03:03', '2025-07-11 10:03:03');
INSERT INTO `students` (`id`, `name`, `gender`, `grade`, `sex`, `age`, `class`, `school_id`, `teacher_id`, `class_id`, `photo`, `created_at`, `updated_at`) VALUES (135, 'សុរិយា', NULL, 3, 'male', 9, '3A', 1, NULL, NULL, NULL, '2025-07-11 10:03:03', '2025-07-11 10:03:03');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holding_classes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','mentor','teacher','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'teacher',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_school_id_foreign` (`school_id`),
  CONSTRAINT `users_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `sex`, `phone`, `telephone`, `holding_classes`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`, `is_active`) VALUES (1, 'Admin User', 'admin@tarlconnect.com', 'users/photos/6EZklT0BI3SHyCoQAIEIPdSbeZbXhIzfYSTvhpiE.jpg', 'female', '077806680', '077806680', 'Grade 3', 'admin', NULL, '$2y$12$DMcECaypa0LEtKiYZIKqfuYQf/v7uTS8UFNrQ4m6/1O/IZI9426Ce', '5khGjBlUqIviAGEhse9SQuFCRDqP0Eq1aTZT8A8R7XKmwIP1psgHXg9oAIA0', '2025-07-11 08:25:04', '2025-07-12 11:50:17', 12, 1);
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `sex`, `phone`, `telephone`, `holding_classes`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`, `is_active`) VALUES (2, 'Mentor One', 'mentor1@tarlconnect.com', NULL, NULL, NULL, NULL, NULL, 'mentor', NULL, '$2y$12$QcdWLmCU/sNtAnlpdANN4.xBaq2ulG8mFvopbpE3Jgo1MkGchIol2', 'g9hTyrdjNaGppSiUWBYh5avi2QgiDZNctw5PUQ6Q5je6aX0dyPc4zukLustj', '2025-07-11 08:25:05', '2025-07-11 08:25:05', NULL, 1);
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `sex`, `phone`, `telephone`, `holding_classes`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`, `is_active`) VALUES (3, 'Mentor Two', 'mentor2@tarlconnect.com', NULL, NULL, NULL, NULL, NULL, 'mentor', NULL, '$2y$12$gCoWk1DU/by/2D1rz78e0eNXqiNx68xfawzuZuFdSZ3BpXFuq2LZS', NULL, '2025-07-11 08:25:06', '2025-07-11 08:25:06', NULL, 1);
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `sex`, `phone`, `telephone`, `holding_classes`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`, `is_active`) VALUES (4, 'Teacher One', 'teacher1@tarlconnect.com', NULL, NULL, NULL, NULL, NULL, 'teacher', NULL, '$2y$12$ZpwA2unylLvEvUkPO86yVup6O.lSZVxV8cGIrOAeZrC9fjK21hJGa', NULL, '2025-07-11 08:25:07', '2025-07-11 08:25:07', 6, 1);
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `sex`, `phone`, `telephone`, `holding_classes`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`, `is_active`) VALUES (5, 'Teacher Two', 'teacher2@tarlconnect.com', NULL, NULL, NULL, NULL, NULL, 'teacher', NULL, '$2y$12$/LfUdR8ZWxPsnr1ntP9VEe0K8RcHu80qKa.n6u.7Nh592ph7S6vDi', NULL, '2025-07-11 08:25:08', '2025-07-11 08:25:08', 6, 1);
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `sex`, `phone`, `telephone`, `holding_classes`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`, `is_active`) VALUES (6, 'MoEYS Viewer', 'viewer@moeys.gov.kh', NULL, NULL, NULL, NULL, NULL, 'viewer', NULL, '$2y$12$R0ZsV62hAY/cr/JAKzk0AOaROHwscdei5zlssUev.SKMIQsk/IexW', NULL, '2025-07-11 08:25:08', '2025-07-11 08:25:08', NULL, 1);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
