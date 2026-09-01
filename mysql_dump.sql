-- ==============================================================================
-- SISTEM INFORMASI & MONITORING PKL SMK NEGERI 10 MAKASSAR
-- MySQL / MariaDB (phpMyAdmin / cPanel Shared Hosting) Database Dump
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+08:00";

-- 1. Drop Existing Tables
DROP TABLE IF EXISTS `pkl_evaluations`;
DROP TABLE IF EXISTS `pkl_monitorings`;
DROP TABLE IF EXISTS `journals`;
DROP TABLE IF EXISTS `attendances`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `industries`;
DROP TABLE IF EXISTS `teachers`;
DROP TABLE IF EXISTS `majors`;
DROP TABLE IF EXISTS `attendance_settings`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `users`;

-- -----------------------------------------------------------------------------
-- Table: users
-- -----------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: password_reset_tokens
-- -----------------------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: sessions
-- -----------------------------------------------------------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: cache & cache_locks
-- -----------------------------------------------------------------------------
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: jobs, job_batches, failed_jobs
-- -----------------------------------------------------------------------------
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: attendance_settings
-- -----------------------------------------------------------------------------
CREATE TABLE `attendance_settings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `check_in_start` time NOT NULL DEFAULT '06:00:00',
  `check_in_late_time` time NOT NULL DEFAULT '08:00:00',
  `check_out_time` time NOT NULL DEFAULT '16:00:00',
  `check_out_early_time` time NOT NULL DEFAULT '15:30:00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: majors
-- -----------------------------------------------------------------------------
CREATE TABLE `majors` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `majors_name_unique` (`name`),
  UNIQUE KEY `majors_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: teachers
-- -----------------------------------------------------------------------------
CREATE TABLE `teachers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_user_id_unique` (`user_id`),
  UNIQUE KEY `teachers_nip_unique` (`nip`),
  CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: industries
-- -----------------------------------------------------------------------------
CREATE TABLE `industries` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `industries_user_id_unique` (`user_id`),
  CONSTRAINT `industries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: students
-- -----------------------------------------------------------------------------
CREATE TABLE `students` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `major_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_user_id_unique` (`user_id`),
  UNIQUE KEY `students_nisn_unique` (`nisn`),
  KEY `students_major_id_foreign` (`major_id`),
  KEY `students_teacher_id_foreign` (`teacher_id`),
  KEY `students_industry_id_foreign` (`industry_id`),
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: attendances
-- -----------------------------------------------------------------------------
CREATE TABLE `attendances` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_in_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_photo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `check_out_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_photo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_student_id_date_unique` (`student_id`,`date`),
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: journals
-- -----------------------------------------------------------------------------
CREATE TABLE `journals` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `activity_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verification_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `journals_student_id_foreign` (`student_id`),
  KEY `journals_verified_by_foreign` (`verified_by`),
  CONSTRAINT `journals_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journals_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: pkl_monitorings
-- -----------------------------------------------------------------------------
CREATE TABLE `pkl_monitorings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `industry_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `obstacles` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recommendations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pkl_monitorings_teacher_id_foreign` (`teacher_id`),
  KEY `pkl_monitorings_industry_id_foreign` (`industry_id`),
  KEY `pkl_monitorings_student_id_foreign` (`student_id`),
  CONSTRAINT `pkl_monitorings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pkl_monitorings_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pkl_monitorings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: pkl_evaluations
-- -----------------------------------------------------------------------------
CREATE TABLE `pkl_evaluations` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `industry_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluator_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aspect_attitude` decimal(5,2) NOT NULL DEFAULT 0.00,
  `aspect_technical` decimal(5,2) NOT NULL DEFAULT 0.00,
  `aspect_managerial` decimal(5,2) NOT NULL DEFAULT 0.00,
  `aspect_report` decimal(5,2) NOT NULL DEFAULT 0.00,
  `aspect_presentation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `final_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `predicate` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkl_evaluations_student_id_unique` (`student_id`),
  KEY `pkl_evaluations_industry_id_foreign` (`industry_id`),
  KEY `pkl_evaluations_evaluator_user_id_foreign` (`evaluator_user_id`),
  CONSTRAINT `pkl_evaluations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pkl_evaluations_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pkl_evaluations_evaluator_user_id_foreign` FOREIGN KEY (`evaluator_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- INITIAL SEED DATA
-- Default Password: password123
-- ==============================================================================

-- 1. Attendance Settings
INSERT INTO `attendance_settings` (`id`, `check_in_start`, `check_in_late_time`, `check_out_time`, `check_out_early_time`, `created_at`, `updated_at`) VALUES
('a1b2c3d4-e5f6-7890-abcd-111111111111', '06:00:00', '08:00:00', '16:00:00', '15:30:00', NOW(), NOW());

-- 2. Users (Password: password123)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('b1111111-1111-1111-1111-111111111111', 'Admin SMKN 10 Makassar', 'admin', 'admin@smkn10makassar.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'admin', NOW(), NOW()),
('b2222222-2222-2222-2222-222222222222', 'Drs. Budi Santoso, M.Pd.', 'guru_budi', 'budi@smkn10makassar.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'guru', NOW(), NOW()),
('b3333333-3333-3333-3333-333333333333', 'PT Telkom Indonesia (Makassar)', 'pt_telkom', 'hrd@telkom-makassar.co.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'industri', NOW(), NOW()),
('b4444444-4444-4444-4444-444444444444', 'Andi Pratama', 'siswa_andi', 'andi@siswa.smkn10.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'siswa', NOW(), NOW()),
('b5555555-5555-5555-5555-555555555555', 'Siti Nurhaliza', 'siswa_siti', 'siti@siswa.smkn10.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'siswa', NOW(), NOW());

-- 3. Majors
INSERT INTO `majors` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
('d1111111-1111-1111-1111-111111111111', 'Rekayasa Perangkat Lunak', 'RPL', NOW(), NOW()),
('d2222222-2222-2222-2222-222222222222', 'Teknik Komputer & Jaringan', 'TKJ', NOW(), NOW());

-- 4. Teachers
INSERT INTO `teachers` (`id`, `user_id`, `nip`, `name`, `phone`, `created_at`, `updated_at`) VALUES
('c2222222-2222-2222-2222-222222222222', 'b2222222-2222-2222-2222-222222222222', '197508122000031001', 'Drs. Budi Santoso, M.Pd.', '081234567890', NOW(), NOW());

-- 5. Industries
INSERT INTO `industries` (`id`, `user_id`, `name`, `address`, `contact_person`, `phone`, `created_at`, `updated_at`) VALUES
('c3333333-3333-3333-3333-333333333333', 'b3333333-3333-3333-3333-333333333333', 'PT Telkom Indonesia Witel Makassar', 'Jl. AP Pettarani No. 2, Makassar', 'Rahmat Hidayat, S.T.', '085299887766', NOW(), NOW());

-- 6. Students
INSERT INTO `students` (`id`, `user_id`, `nisn`, `name`, `class_name`, `major_id`, `teacher_id`, `industry_id`, `phone`, `created_at`, `updated_at`) VALUES
('c4444444-4444-4444-4444-444444444444', 'b4444444-4444-4444-4444-444444444444', '0061234567', 'Andi Pratama', 'XII RPL 1', 'd1111111-1111-1111-1111-111111111111', 'c2222222-2222-2222-2222-222222222222', 'c3333333-3333-3333-3333-333333333333', '081344556677', NOW(), NOW()),
('c5555555-5555-5555-5555-555555555555', 'b5555555-5555-5555-5555-555555555555', '0067654321', 'Siti Nurhaliza', 'XII TKJ 2', 'd2222222-2222-2222-2222-222222222222', 'c2222222-2222-2222-2222-222222222222', 'c3333333-3333-3333-3333-333333333333', '081399001122', NOW(), NOW());

-- 7. Sample Attendance Record
INSERT INTO `attendances` (`id`, `student_id`, `date`, `check_in_time`, `check_in_status`, `check_in_notes`, `check_out_time`, `check_out_status`, `check_out_notes`, `location`, `created_at`, `updated_at`) VALUES
('e1111111-1111-1111-1111-111111111111', 'c4444444-4444-4444-4444-444444444444', CURDATE(), '07:45:00', 'Tepat Waktu', 'Tiba di kantor mitra tepat waktu', '16:05:00', 'Tepat Waktu', 'Pekerjaan magang hari ini selesai', '-5.147665, 119.432731', NOW(), NOW());

-- 8. Sample Journal Record
INSERT INTO `journals` (`id`, `student_id`, `date`, `activity_title`, `activity_description`, `status`, `verification_notes`, `verified_at`, `verified_by`, `created_at`, `updated_at`) VALUES
('f1111111-1111-1111-1111-111111111111', 'c4444444-4444-4444-4444-444444444444', CURDATE(), 'Implementasi Modul Otentikasi dan Dashboard', 'Mengembangkan antarmuka monitoring presensi dan integrasi API kamera WebRTC bersama tim IT industri.', 'approved', 'Pekerjaan sangat baik dan sesuai target.', NOW(), 'b3333333-3333-3333-3333-333333333333', NOW(), NOW());

-- 9. Sample Monitoring Record
INSERT INTO `pkl_monitorings` (`id`, `teacher_id`, `industry_id`, `student_id`, `visit_date`, `notes`, `obstacles`, `recommendations`, `created_at`, `updated_at`) VALUES
('g1111111-1111-1111-1111-111111111111', 'c2222222-2222-2222-2222-222222222222', 'c3333333-3333-3333-3333-333333333333', 'c4444444-4444-4444-4444-444444444444', CURDATE(), 'Monitoring berkala dan koordinasi dengan instruktur industri mengenai perkembangan kompetensi siswa.', 'Tidak ada kendala berarti', 'Pertahankan performa dan kedisiplinan kerja siswa.', NOW(), NOW());

-- 10. Sample Evaluation Record
INSERT INTO `pkl_evaluations` (`id`, `student_id`, `industry_id`, `evaluator_user_id`, `aspect_attitude`, `aspect_technical`, `aspect_managerial`, `aspect_report`, `aspect_presentation`, `final_score`, `predicate`, `notes`, `created_at`, `updated_at`) VALUES
('h1111111-1111-1111-1111-111111111111', 'c4444444-4444-4444-4444-444444444444', 'c3333333-3333-3333-3333-333333333333', 'b3333333-3333-3333-3333-333333333333', 95.00, 92.00, 90.00, 94.00, 96.00, 93.40, 'A', 'Siswa memiliki etos kerja dan kemampuan teknis yang luar biasa.', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
