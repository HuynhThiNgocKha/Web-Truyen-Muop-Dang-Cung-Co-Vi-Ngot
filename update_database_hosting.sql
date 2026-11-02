-- ========================================================
-- MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT - BẢN CẬP NHẬT DATABASE HOSTING
-- Ngày cập nhật: 2026-11-02
-- Áp dụng cho: Tính năng Audio, Quản lý khách đọc, Thống kê Affiliate & Lịch sử tách chương
-- Hướng dẫn: Mở cPanel -> phpMyAdmin -> Chọn database hosting -> Tab SQL (hoặc Import) -> Dán & Chạy
-- ========================================================

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- 1. Tạo bảng ghi nhận nhật ký lượt nghe Audio (Mới)
CREATE TABLE IF NOT EXISTS `wp_muop_audio_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `story_id` bigint(20) unsigned NOT NULL,
  `story_title` varchar(255) NOT NULL,
  `chapter_id` bigint(20) unsigned NOT NULL,
  `chapter_num` int(11) DEFAULT 1,
  `chapter_title` varchar(255) DEFAULT '',
  `user_id` bigint(20) unsigned DEFAULT 0,
  `user_name` varchar(100) DEFAULT 'Khách vãng lai',
  `ip_address` varchar(45) DEFAULT '',
  `device_type` varchar(50) DEFAULT 'Máy tính',
  `listen_date` date NOT NULL,
  `listen_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_story` (`story_id`),
  KEY `idx_chapter` (`chapter_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_date` (`listen_date`),
  KEY `idx_time` (`listen_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 2. Tạo bảng ghi nhận nhật ký khách đọc truyện theo ngày (Mới)
CREATE TABLE IF NOT EXISTS `wp_muop_visitor_read_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT 0,
  `user_name` varchar(100) DEFAULT 'Khách vãng lai',
  `story_id` bigint(20) unsigned NOT NULL,
  `story_title` varchar(255) NOT NULL,
  `chapter_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `chapter_num` int(11) DEFAULT 0,
  `chapter_title` varchar(255) DEFAULT '',
  `device_type` varchar(50) DEFAULT 'Máy tính',
  `read_date` date NOT NULL,
  `read_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip_address`),
  KEY `idx_date` (`read_date`),
  KEY `idx_story` (`story_id`),
  KEY `idx_chapter` (`chapter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 3. Tạo bảng lưu lịch sử tách chương đồng bộ đa thiết bị
CREATE TABLE IF NOT EXISTS `wp_muop_splitter_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` varchar(64) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `total_chapters` int(11) NOT NULL DEFAULT '0',
  `total_words` int(11) NOT NULL DEFAULT '0',
  `avg_words` int(11) NOT NULL DEFAULT '0',
  `target_words` int(11) NOT NULL DEFAULT '2500',
  `chapters_json` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 4. Bảng ghi nhận lượt click Affiliate Shopee & TikTok
CREATE TABLE IF NOT EXISTS `wp_affiliate_clicks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_type` varchar(20) NOT NULL,
  `url` text NOT NULL,
  `story_id` bigint(20) unsigned DEFAULT 0,
  `chapter_num` int(11) DEFAULT 0,
  `ip` varchar(45) DEFAULT '',
  `user_agent` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_story` (`story_id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_link_type` (`link_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 5. Cập nhật thiết lập 24 truyện/trang
UPDATE `wp_options` SET `option_value` = '24' WHERE `option_name` = 'posts_per_page';
INSERT INTO `wp_options` (`option_name`, `option_value`, `autoload`)
SELECT 'posts_per_page', '24', 'yes'
WHERE NOT EXISTS (SELECT 1 FROM `wp_options` WHERE `option_name` = 'posts_per_page');
