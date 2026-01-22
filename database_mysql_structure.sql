-- =====================================================
-- সাতক্ষীরা-২ ভোটার তালিকা - MySQL Database Export
-- Database: metasoft_election
-- Developed by: Mir Javed Jeetu
-- Export Date: 2026-01-22
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+06:00";

-- =====================================================
-- Table: migrations
-- =====================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_21_000001_create_search_types_table', 1),
(5, '2026_01_21_000002_create_upazilas_table', 1),
(6, '2026_01_21_000003_create_unions_table', 1),
(7, '2026_01_21_000004_create_wards_table', 1),
(8, '2026_01_21_000005_create_vote_centers_table', 1),
(9, '2026_01_21_000006_create_area_codes_table', 1),
(10, '2026_01_21_000007_create_admins_table', 1),
(11, '2026_01_21_000008_create_voters_table', 1),
(12, '2026_01_21_000009_create_banners_table', 1),
(13, '2026_01_21_000010_create_breaking_news_table', 1),
(14, '2026_01_21_000011_create_site_settings_table', 1),
(15, '2026_01_22_041848_add_role_to_users_table', 1);

-- =====================================================
-- Table: users
-- =====================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'user',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `phone` VARCHAR(20) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: password_reset_tokens
-- =====================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: sessions
-- =====================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: cache
-- =====================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: cache_locks
-- =====================================================
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: jobs
-- =====================================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: job_batches
-- =====================================================
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: failed_jobs
-- =====================================================
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: search_types
-- =====================================================
DROP TABLE IF EXISTS `search_types`;
CREATE TABLE `search_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name_bn` VARCHAR(255) NOT NULL,
    `name_en` VARCHAR(255) NOT NULL,
    `order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `search_types` (`id`, `name_bn`, `name_en`, `order`, `created_at`, `updated_at`) VALUES
(1, 'নাম', 'Name', 1, '2026-01-21 16:41:28', '2026-01-21 16:41:28'),
(2, 'ভোটার নং', 'Voter ID', 2, '2026-01-21 16:41:28', '2026-01-21 16:41:28'),
(3, 'জন্ম তারিখ', 'Date of Birth', 3, '2026-01-21 16:41:28', '2026-01-21 16:41:28'),
(4, 'পিতা', 'Father Name', 4, '2026-01-21 16:41:28', '2026-01-21 16:41:28'),
(5, 'মাতা', 'Mother Name', 5, '2026-01-21 16:41:28', '2026-01-21 16:41:28'),
(6, 'পেশা', 'Occupation', 6, '2026-01-21 16:41:28', '2026-01-21 16:41:28');

-- =====================================================
-- Table: upazilas
-- =====================================================
DROP TABLE IF EXISTS `upazilas`;
CREATE TABLE `upazilas` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name_bn` VARCHAR(255) NOT NULL,
    `name_en` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: unions
-- =====================================================
DROP TABLE IF EXISTS `unions`;
CREATE TABLE `unions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `upazila_id` BIGINT UNSIGNED NOT NULL,
    `name_bn` VARCHAR(255) NOT NULL,
    `name_en` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `unions_upazila_id_foreign` (`upazila_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: wards
-- =====================================================
DROP TABLE IF EXISTS `wards`;
CREATE TABLE `wards` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name_bn` VARCHAR(255) NOT NULL,
    `name_en` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: vote_centers
-- =====================================================
DROP TABLE IF EXISTS `vote_centers`;
CREATE TABLE `vote_centers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `center_no` VARCHAR(255) NOT NULL,
    `name_bn` VARCHAR(255) NOT NULL,
    `name_en` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: area_codes
-- =====================================================
DROP TABLE IF EXISTS `area_codes`;
CREATE TABLE `area_codes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `upazila_id` BIGINT UNSIGNED NOT NULL,
    `union_id` BIGINT UNSIGNED NOT NULL,
    `ward_id` BIGINT UNSIGNED NOT NULL,
    `vote_center_id` BIGINT UNSIGNED NOT NULL,
    `area_code_no` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `area_codes_upazila_id_foreign` (`upazila_id`),
    KEY `area_codes_union_id_foreign` (`union_id`),
    KEY `area_codes_ward_id_foreign` (`ward_id`),
    KEY `area_codes_vote_center_id_foreign` (`vote_center_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: admins
-- =====================================================
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@satkhira.com', '$2y$12$fuwmO/mofvJIWw0ZlTiPEeDyHUZ4Iqe37q.m2jSY3e6yHj6mCcNeC', 1, NULL, '2026-01-21 16:41:28', '2026-01-21 16:41:28');

-- =====================================================
-- Table: voters (MAIN DATA TABLE - 10,312 records)
-- =====================================================
DROP TABLE IF EXISTS `voters`;
CREATE TABLE `voters` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `serial_no` VARCHAR(50) NULL,
    `upazila` VARCHAR(255) NULL,
    `union` VARCHAR(255) NULL,
    `ward` VARCHAR(100) NULL,
    `area_code` VARCHAR(100) NULL,
    `area_name` VARCHAR(255) NULL,
    `gender` VARCHAR(50) NULL,
    `center_no` VARCHAR(100) NULL,
    `center_name` VARCHAR(255) NULL,
    `name` VARCHAR(255) NULL,
    `voter_id` VARCHAR(100) NULL,
    `father_name` VARCHAR(255) NULL,
    `mother_name` VARCHAR(255) NULL,
    `occupation` VARCHAR(255) NULL,
    `date_of_birth` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `voters_voter_id_index` (`voter_id`),
    KEY `voters_name_index` (`name`(100)),
    KEY `voters_upazila_index` (`upazila`(50)),
    KEY `voters_center_name_index` (`center_name`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: Voter data will be exported in a separate file (voters_data.sql) due to size

-- =====================================================
-- Table: banners
-- =====================================================
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title_bn` VARCHAR(255) NULL,
    `title_en` VARCHAR(255) NULL,
    `subtitle_bn` VARCHAR(255) NULL,
    `subtitle_en` VARCHAR(255) NULL,
    `image` VARCHAR(255) NULL,
    `link` VARCHAR(255) NULL,
    `order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `banners` (`id`, `title_bn`, `title_en`, `subtitle_bn`, `subtitle_en`, `image`, `link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, 'banners/df4NgfKuNd5pu2W2gMYIbpqgf1xkrx2UynzffH6r.jpg', NULL, 1, 1, '2026-01-22 03:42:48', '2026-01-22 04:15:13'),
(2, NULL, NULL, NULL, NULL, 'banners/7OkJyBVUMDsXeACSTQNxhbWFdwqO1XvaiETAVKUq.jpg', NULL, 2, 1, '2026-01-22 04:18:46', '2026-01-22 04:18:46');

-- =====================================================
-- Table: breaking_news
-- =====================================================
DROP TABLE IF EXISTS `breaking_news`;
CREATE TABLE `breaking_news` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `content_bn` TEXT NOT NULL,
    `content_en` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `breaking_news` (`id`, `content_bn`, `content_en`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'ভোটার তথ্য অনুসন্ধান', 'test', 1, 1, '2026-01-22 03:47:13', '2026-01-22 03:47:13');

-- =====================================================
-- Table: site_settings
-- =====================================================
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT NULL,
    `type` VARCHAR(50) NOT NULL DEFAULT 'text',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
