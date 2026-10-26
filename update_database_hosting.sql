-- ========================================================
-- MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT - BẢN CẬP NHẬT DATABASE HOSTING
-- Ngày tạo: 2026-10-26 15:06:43
-- Áp dụng cho: Cập nhật 24 truyện/trang & Bảng đồng bộ tách chương PC/Mobile
-- Hướng dẫn: Mở cPanel -> phpMyAdmin -> Chọn database -> Tab SQL (hoặc Import) -> Dán & Chạy
-- ========================================================

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- 1. Tạo bảng lưu lịch sử tách chương đồng bộ đa thiết bị
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

-- 2. Cập nhật số lượng truyện trên 1 trang thành 24 truyện
UPDATE `wp_options` SET `option_value` = '24' WHERE `option_name` = 'posts_per_page';
INSERT INTO `wp_options` (`option_name`, `option_value`, `autoload`)
SELECT 'posts_per_page', '24', 'yes'
WHERE NOT EXISTS (SELECT 1 FROM `wp_options` WHERE `option_name` = 'posts_per_page');

