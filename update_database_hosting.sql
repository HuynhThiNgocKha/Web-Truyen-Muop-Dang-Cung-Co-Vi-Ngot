-- ========================================================
-- MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT - BẢN CẬP NHẬT DATABASE TOÀN DIỆN CHO HOSTING
-- Cập nhật từ trước đến nay: Online Realtime, Audio TTS, VietQR Donate, Affiliate, Tách Chương, Khách đọc
-- Hướng dẫn: Mở cPanel / DirectAdmin -> phpMyAdmin -> Chọn database -> Tab SQL -> Dán toàn bộ nội dung & Bấm Thực Hiện (Go)
-- ========================================================

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- 1. Bảng theo dõi người dùng đang truy cập trực tiếp theo thời gian thực (Online Real-time)
CREATE TABLE IF NOT EXISTS `wp_muop_online_visitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` varchar(64) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT 0,
  `user_name` varchar(100) DEFAULT 'Khách vãng lai',
  `page_url` varchar(255) NOT NULL DEFAULT '',
  `page_title` varchar(255) NOT NULL DEFAULT '',
  `device_type` varchar(50) DEFAULT 'Máy tính',
  `last_active` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_session` (`session_key`),
  KEY `idx_last_active` (`last_active`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 2. Bảng ghi nhận nhật ký lượt nghe Audio
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

-- 3. Bảng ghi nhận nhật ký khách đọc truyện theo ngày
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

-- 4. Bảng lưu lịch sử tách chương đồng bộ đa thiết bị
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

-- 5. Bảng ghi nhận lượt click Affiliate Shopee & TikTok
CREATE TABLE IF NOT EXISTS `wp_affiliate_clicks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_type` varchar(20) NOT NULL DEFAULT 'shopee',
  `url` text NOT NULL,
  `ip` varchar(45) DEFAULT '',
  `user_agent` text,
  `user_id` bigint(20) unsigned DEFAULT 0,
  `story_id` bigint(20) unsigned DEFAULT 0,
  `chapter_num` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_story` (`story_id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_link_type` (`link_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 6. Bảng lịch sử đọc truyện của người dùng
CREATE TABLE IF NOT EXISTS `wp_reading_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `story_id` bigint(20) unsigned NOT NULL,
  `chapter_id` bigint(20) unsigned NOT NULL,
  `chapter_num` int(11) DEFAULT 1,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_story` (`user_id`, `story_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 7. Bảng Tủ Truyện (Bookmarks)
CREATE TABLE IF NOT EXISTS `wp_reading_bookmarks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `story_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_story` (`user_id`, `story_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 8. Bảng thông báo người dùng (Notifications)
CREATE TABLE IF NOT EXISTS `wp_muop_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `sender_id` bigint(20) unsigned DEFAULT 0,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(500) DEFAULT '',
  `image` varchar(500) DEFAULT '',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_read` (`user_id`, `is_read`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 9. Bảng nạp tiền / giao dịch (Payments)
CREATE TABLE IF NOT EXISTS `wp_muop_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `transaction_code` varchar(100) DEFAULT '',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 10. Bảng báo cáo lỗi (Reports)
CREATE TABLE IF NOT EXISTS `wp_muop_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `story_id` bigint(20) unsigned NOT NULL,
  `chapter_id` bigint(20) unsigned DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT 0,
  `reason` varchar(255) NOT NULL,
  `details` text DEFAULT '',
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_story` (`story_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 11. Cập nhật thiết lập 24 truyện/trang
UPDATE `wp_options` SET `option_value` = '24' WHERE `option_name` = 'posts_per_page';
INSERT INTO `wp_options` (`option_name`, `option_value`, `autoload`)
SELECT 'posts_per_page', '24', 'yes'
WHERE NOT EXISTS (SELECT 1 FROM `wp_options` WHERE `option_name` = 'posts_per_page');

-- 12. Bảng Thùng Rác hệ thống (Lưu trữ an toàn 15 ngày, tự động xóa vĩnh viễn)
CREATE TABLE IF NOT EXISTS `wp_muop_trash` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_type` varchar(50) NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `item_name` varchar(255) NOT NULL,
  `item_path` text NULL,
  `trash_path` text NULL,
  `item_size` bigint(20) unsigned NOT NULL DEFAULT 0,
  `item_meta` longtext NULL,
  `deleted_by` bigint(20) unsigned NOT NULL DEFAULT 0,
  `deleted_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'trashed',
  PRIMARY KEY (`id`),
  KEY `item_type` (`item_type`),
  KEY `expires_at` (`expires_at`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
