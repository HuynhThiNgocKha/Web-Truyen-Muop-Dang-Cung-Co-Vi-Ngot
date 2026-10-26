-- ========================================================
-- MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT - TOÀN BỘ CƠ SỞ DỮ LIỆU ĐỒNG BỘ
-- Ngày tạo: 2026-10-26 15:06:43
-- Database: webtruyen_zhihu
-- Domain: https://muopdangcungcovingot.io.vn
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_affiliate_clicks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_affiliate_clicks`;
CREATE TABLE `wp_affiliate_clicks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_type` varchar(20) NOT NULL DEFAULT 'shopee',
  `url` text NOT NULL,
  `ip` varchar(45) DEFAULT '',
  `user_agent` text DEFAULT '',
  `user_id` bigint(20) unsigned DEFAULT 0,
  `story_id` bigint(20) unsigned DEFAULT 0,
  `chapter_num` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `link_type` (`link_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_affiliate_clicks`
INSERT INTO `wp_affiliate_clicks` (`id`, `link_type`, `url`, `ip`, `user_agent`, `user_id`, `story_id`, `chapter_num`, `created_at`) VALUES
('1', 'shopee', 'https://s.shopee.vn/4qG9lQO2rp', '127.0.0.1', '', '4', '54', '2', '2026-10-07 12:08:41'),
('2', 'shopee', 'https://s.shopee.vn/4qG9lQO2rp', '::1', 'curl/8.21.0', '0', '1', '2', '2026-10-07 12:12:01'),
('3', 'shopee', 'https://s.shopee.vn/4qG9lQO2rp', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-07 12:22:04'),
('4', 'shopee', 'https://s.shopee.vn/4qG9lQO2rp', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '0', '23', '2', '2026-10-07 12:23:51'),
('5', 'shopee', 'https://s.shopee.vn/4qG9lQO2rp', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '1', '39', '2', '2026-10-07 12:38:06'),
('6', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '1', '30', '2', '2026-10-07 12:44:23'),
('7', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '1', '56', '2', '2026-10-07 12:58:29'),
('8', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-08 11:18:10'),
('9', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-08 11:57:46'),
('10', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '6', '33', '2', '2026-10-08 11:59:56'),
('11', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.160', 'Mozilla/5.0 (Linux; Android 13; V2109 Build/TP1A.220624.014;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/153.0.8010.36 Mobile Safari/537.36 Zalo android/260901903 ZaloTheme/dark ZaloLanguage/vi', '0', '0', '0', '2026-10-08 12:16:05'),
('12', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-09 08:12:37'),
('13', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-10 12:46:50'),
('14', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '72', '2', '2026-10-10 13:47:51'),
('15', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '0', '0', '0', '2026-10-12 04:59:50'),
('16', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.160', 'Mozilla/5.0 (Linux; Android 13; V2109 Build/TP1A.220624.014;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/153.0.8010.57 Mobile Safari/537.36 Zalo android/260901903 ZaloTheme/dark ZaloLanguage/vi', '0', '0', '0', '2026-10-12 05:42:41'),
('17', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.160', 'Mozilla/5.0 (Linux; Android 13; V2109 Build/TP1A.220624.014;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/153.0.8010.57 Mobile Safari/537.36 Zalo android/260901903 ZaloTheme/dark ZaloLanguage/vi', '0', '72', '2', '2026-10-12 05:43:53'),
('18', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-12 06:09:55'),
('19', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '88', '5', '2026-10-12 16:57:14'),
('20', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '5', '2026-10-12 21:43:02'),
('21', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-12 21:55:44'),
('22', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-12 21:56:33'),
('23', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '104', '2', '2026-10-12 21:56:47'),
('24', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '104', '4', '2026-10-12 21:56:57'),
('25', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '72', '2', '2026-10-12 22:01:07'),
('26', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36', '0', '72', '4', '2026-10-12 22:01:23'),
('27', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '72', '4', '2026-10-12 23:14:49'),
('28', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '79', '3', '2026-10-13 01:15:19'),
('29', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '72', '2', '2026-10-13 16:50:19'),
('30', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:22:58'),
('31', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '72', '2', '2026-10-14 18:23:04'),
('32', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:23:11'),
('33', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '72', '3', '2026-10-14 18:23:15'),
('34', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:23:45'),
('35', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '72', '4', '2026-10-14 18:23:48'),
('36', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:24:20'),
('37', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:24:30'),
('38', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:24:44'),
('39', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '2', '2026-10-14 18:24:49'),
('40', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:24:56'),
('41', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '3', '2026-10-14 18:24:59'),
('42', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:25:05'),
('43', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '4', '2026-10-14 18:25:08'),
('44', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-14 18:25:14'),
('45', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '5', '2026-10-14 18:25:45'),
('46', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-14 18:40:37'),
('47', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '104', '2', '2026-10-14 18:40:39'),
('48', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '72', '1', '2026-10-14 19:21:16'),
('49', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '88', '2', '2026-10-14 19:22:38'),
('50', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '5', '2026-10-14 21:37:29');
INSERT INTO `wp_affiliate_clicks` (`id`, `link_type`, `url`, `ip`, `user_agent`, `user_id`, `story_id`, `chapter_num`, `created_at`) VALUES
('51', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '104', '3', '2026-10-14 21:37:46'),
('52', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '111', '1', '2026-10-14 21:38:22'),
('53', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '111', '1', '2026-10-14 21:38:27'),
('54', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '111', '3', '2026-10-14 21:38:33'),
('55', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '111', '4', '2026-10-14 21:38:39'),
('56', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-15 21:36:48'),
('57', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-15 21:37:38'),
('58', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-15 22:07:12'),
('59', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-15 22:07:23'),
('60', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-15 22:08:35'),
('61', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-15 22:26:32'),
('62', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 00:10:47'),
('63', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 00:11:02'),
('64', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 00:18:38'),
('65', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 00:28:31'),
('66', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 20:17:57'),
('67', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 20:34:57'),
('68', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 20:42:19'),
('69', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 20:49:08'),
('70', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:03:04'),
('71', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:11:28'),
('72', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:12:37'),
('73', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:12:47'),
('74', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:15:14'),
('75', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:20:09'),
('76', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:22:15'),
('77', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:23:12'),
('78', 'shopee', 'https://s.shopee.vn/5q8lDzjzRv', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '129', '1', '2026-10-16 21:23:22'),
('79', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:38:45'),
('80', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:39:38'),
('81', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:40:21'),
('82', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:40:32'),
('83', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:42:32'),
('84', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '1', '0', '0', '2026-10-16 21:47:26'),
('85', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-16 21:49:47'),
('86', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-16 21:50:28'),
('87', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:51:03'),
('88', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 21:53:11'),
('89', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-16 22:03:27'),
('90', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '72', '0', '2026-10-16 22:19:55'),
('91', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '104', '0', '2026-10-16 22:20:54'),
('92', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '0', '0', '0', '2026-10-16 22:23:10'),
('93', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '6', '0', '0', '2026-10-16 22:26:02'),
('94', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 22:26:52'),
('95', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '6', '0', '0', '2026-10-16 22:27:17'),
('96', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 22:27:48'),
('97', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '6', '0', '0', '2026-10-16 22:29:46'),
('98', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '6', '0', '0', '2026-10-16 22:30:24'),
('99', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '192.168.1.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', '6', '0', '0', '2026-10-16 22:31:22'),
('100', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 22:31:59');
INSERT INTO `wp_affiliate_clicks` (`id`, `link_type`, `url`, `ip`, `user_agent`, `user_id`, `story_id`, `chapter_num`, `created_at`) VALUES
('101', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-16 22:37:09'),
('102', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-16 22:41:54'),
('103', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-16 22:52:54'),
('104', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '88', '0', '2026-10-17 11:39:59'),
('105', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 11:41:01'),
('106', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 11:41:26'),
('107', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '96', '0', '2026-10-17 11:41:56'),
('108', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '79', '0', '2026-10-17 11:42:30'),
('109', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 11:46:30'),
('110', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:07:54'),
('111', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:11:48'),
('112', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:12:54'),
('113', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:15:05'),
('114', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:25:02'),
('115', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:26:15'),
('116', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:26:52'),
('117', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:35:28'),
('118', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:36:18'),
('119', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:42:46'),
('120', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:52:11'),
('121', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '142', '2', '2026-10-17 12:54:35'),
('122', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:54:46'),
('123', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:55:33'),
('124', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:55:57'),
('125', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:56:28'),
('126', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:56:44'),
('127', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:57:17'),
('128', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 12:57:37'),
('129', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 13:20:49'),
('130', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-17 13:23:38'),
('131', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '1', '0', '0', '2026-10-17 13:28:18'),
('132', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '1', '0', '0', '2026-10-17 13:29:50'),
('133', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 15:00:44'),
('134', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 15:01:11'),
('135', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 15:01:43'),
('136', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 15:02:13'),
('137', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 15:02:52'),
('138', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 15:55:40'),
('139', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-17 22:16:42'),
('140', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-18 21:05:39'),
('141', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-18 21:12:31'),
('142', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-18 21:46:44'),
('143', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-26 13:30:41'),
('144', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-26 14:17:30'),
('145', 'tiktok', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '0', '0', '0', '2026-10-26 14:29:07');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_commentmeta`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_commentmeta`;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_comments`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_comments`;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT 0,
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_links`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_links`;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_muop_chapter_reports`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_muop_chapter_reports`;
CREATE TABLE `wp_muop_chapter_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT 0,
  `story_id` bigint(20) unsigned NOT NULL,
  `chapter_id` bigint(20) unsigned NOT NULL,
  `chapter_num` int(11) DEFAULT 1,
  `issue_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `admin_reply` text DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `story_id` (`story_id`),
  KEY `chapter_id` (`chapter_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_muop_chapter_reports`
INSERT INTO `wp_muop_chapter_reports` (`id`, `user_id`, `story_id`, `chapter_id`, `chapter_num`, `issue_type`, `description`, `status`, `admin_reply`, `created_at`, `updated_at`) VALUES
('1', '8', '72', '73', '1', 'Lỗi khác', 'Chữ bị lỗi', 'resolved', '[{\"sender_id\":1,\"sender_name\":\"Thị Mướp\",\"role\":\"admin\",\"role_label\":\"Ban Quản Trị\",\"message\":\"Đã sửa chương lỗi\",\"created_at\":\"2026-10-13 18:00:32\"}]', '2026-10-12 23:47:22', '2026-10-13 18:00:32');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_muop_notifications`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_muop_notifications`;
CREATE TABLE `wp_muop_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `sender_id` bigint(20) unsigned DEFAULT 0,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(500) DEFAULT '',
  `image` varchar(500) DEFAULT '',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_read` (`is_read`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_muop_notifications`
INSERT INTO `wp_muop_notifications` (`id`, `user_id`, `sender_id`, `type`, `title`, `message`, `link`, `image`, `is_read`, `created_at`) VALUES
('1', '1', '8', 'chapter_report', 'Báo lỗi chương: TỪ NHỎ ĐÃ BỊ SO SÁNH VỚI CHỊ GÁI, KHÔNG NGỜ LẠI ĐƯỢC NAM THẦN THEO ĐUỔI', 'Bơ vừa báo lỗi Chương 1 (Lỗi khác): \"Chữ bị lỗi\"', '/quan-ly-admin/#admTabReports', '', '1', '2026-10-12 23:47:22'),
('2', '1', '8', 'chapter_report', 'Báo lỗi Chương 1 - TỪ NHỎ ĐÃ BỊ SO SÁNH VỚI CHỊ GÁI, KHÔNG NGỜ LẠI ĐƯỢC NAM THẦN THEO ĐUỔI', 'Bơ vừa báo lỗi (Lỗi khác): \"Chữ bị lỗi\"', '/chuong/chuong-1-chuong-1/', '', '1', '2026-10-12 23:47:22'),
('3', '1', '2', 'team_favorite', 'Có người hâm mộ yêu thích team của bạn!', 'Tiểu Mướp Dịch Thuật vừa thêm team dịch \"Mướp Đắng Cũng Có Vị Ngọt\" vào danh sách yêu thích.', 'http://192.168.1.182:8080/team-dich/muop-dang-cung-co-vi-ngot/', '', '1', '2026-10-13 01:27:52'),
('4', '2', '1', 'new_story', 'Dịch giả ra truyện mới!', 'Dịch giả \"Mướp Đắng Cũng Có Vị Ngọt\" vừa phát hành bộ truyện mới: \"CẢ NHÀ CƯNG CHIỀU TÔI NHƯ CÔNG CHÚA, KHÔNG NGỜ TÔI CHỈ LÀ MỘT CON RỐI\". Khám phá ngay!', 'https://muopdangcungcovingot.io.vn/truyen/ca-nha-cung-chieu-toi-nhu-cong-chua-khong-ngo-toi-chi-la-mot-con-roi/', '', '1', '2026-10-13 12:50:19'),
('5', '1', '1', 'system', 'Truyện đã được ẩn khỏi website', 'Truyện \"CẢ NHÀ CƯNG CHIỀU TÔI NHƯ CÔNG CHÚA, KHÔNG NGỜ TÔI CHỈ LÀ MỘT CON RỐI\" đã được chuyển sang trạng thái ẩn (Bản nháp) bởi Quản trị viên.', 'https://muopdangcungcovingot.io.vn/thong-tin-dich-gia/', '', '1', '2026-10-13 13:33:43'),
('6', '1', '1', 'system', 'Truyện đã được hiển thị công khai!', 'Truyện \"CẢ NHÀ CƯNG CHIỀU TÔI NHƯ CÔNG CHÚA, KHÔNG NGỜ TÔI CHỈ LÀ MỘT CON RỐI\" của bạn đã được duyệt và hiển thị công khai trên website.', 'https://muopdangcungcovingot.io.vn/truyen/ca-nha-cung-chieu-toi-nhu-cong-chua-khong-ngo-toi-chi-la-mot-con-roi/', '', '1', '2026-10-13 13:34:54'),
('7', '1', '1', 'system', 'Truyện đã được ẩn khỏi website', 'Truyện \"CẢ NHÀ CƯNG CHIỀU TÔI NHƯ CÔNG CHÚA, KHÔNG NGỜ TÔI CHỈ LÀ MỘT CON RỐI\" đã được chuyển sang trạng thái ẩn (Bản nháp) bởi Quản trị viên.', 'https://muopdangcungcovingot.io.vn/thong-tin-dich-gia/', '', '1', '2026-10-13 13:35:03'),
('8', '1', '1', 'system', 'Truyện đã được hiển thị công khai!', 'Truyện \"CẢ NHÀ CƯNG CHIỀU TÔI NHƯ CÔNG CHÚA, KHÔNG NGỜ TÔI CHỈ LÀ MỘT CON RỐI\" của bạn đã được duyệt và hiển thị công khai trên website.', 'https://muopdangcungcovingot.io.vn/truyen/ca-nha-cung-chieu-toi-nhu-cong-chua-khong-ngo-toi-chi-la-mot-con-roi/', '', '1', '2026-10-13 13:35:09'),
('9', '8', '1', 'report_reply', 'Phản hồi báo lỗi chương: TỪ NHỎ ĐÃ BỊ SO SÁNH VỚI CHỊ GÁI, KHÔNG NGỜ LẠI ĐƯỢC NAM THẦN THEO ĐUỔI', 'Thị Mướp (Ban Quản Trị): \"Đã sửa chương lỗi\"', '#report-1', '', '0', '2026-10-13 18:00:32'),
('11', '2', '1', 'new_story', 'Dịch giả ra truyện mới!', 'Dịch giả \"Mướp Đắng Cũng Có Vị Ngọt\" vừa phát hành bộ truyện mới: \"VỪA CHIA TAY BẠN TRAI GIÀU, TÔI LẠI ĐƯỢC CẬU EM TRAI NGHÈO CƯNG CHIỀU\". Khám phá ngay!', 'https://muopdangcungcovingot.io.vn/vua-chia-tay-ban-trai-giau-toi-lai-duoc-cau-em-trai-ngheo-cung-chieu/', '', '0', '2026-10-16 21:13:17'),
('12', '1', '6', 'story_favorite', 'Có độc giả yêu thích truyện của bạn!', 'Ngọc Kha vừa thêm bộ truyện \"VỪA CHIA TAY BẠN TRAI GIÀU, TÔI LẠI ĐƯỢC CẬU EM TRAI NGHÈO CƯNG CHIỀU\" vào danh sách yêu thích.', 'http://192.168.1.182:8080/vua-chia-tay-ban-trai-giau-toi-lai-duoc-cau-em-trai-ngheo-cung-chieu/', '', '1', '2026-10-16 22:26:38'),
('13', '1', '6', 'story_comment', 'Bình luận mới: VỪA CHIA TAY BẠN TRAI GIÀU, TÔI LẠI ĐƯỢC CẬU EM TRAI NGHÈO CƯNG CHIỀU', 'Ngọc Kha: \"[Sticker]\"', 'http://192.168.1.182:8080/vua-chia-tay-ban-trai-giau-toi-lai-duoc-cau-em-trai-ngheo-cung-chieu/#comment-14', '', '1', '2026-10-16 22:27:38'),
('14', '2', '1', 'new_story', 'Dịch giả ra truyện mới!', 'Dịch giả \"Mướp Đắng Cũng Có Vị Ngọt\" vừa phát hành bộ truyện mới: \"NHẬN NHẦM BẠN TRAI YÊU QUA MẠNG, KHÔNG NGỜ LẠI VỚ ĐƯỢC NAM THẦN\". Khám phá ngay!', 'https://muopdangcungcovingot.io.vn/nhan-nham-ban-trai-yeu-qua-mang-khong-ngo-lai-vo-duoc-nam-than/', '', '0', '2026-10-17 12:12:02'),
('15', '1', '9', 'new_user', 'Người dùng mới đăng ký', 'Thành viên mới \"Sec Test User\" (sec_test_1792335444@test.com) vừa đăng ký tài khoản thành công.', 'https://muopdangcungcovingot.io.vn/quan-ly-admin/?tab=users', '', '0', '2026-10-18 21:57:24'),
('16', '6', '9', 'new_user', 'Người dùng mới đăng ký', 'Thành viên mới \"Sec Test User\" (sec_test_1792335444@test.com) vừa đăng ký tài khoản thành công.', 'https://muopdangcungcovingot.io.vn/quan-ly-admin/?tab=users', '', '0', '2026-10-18 21:57:24'),
('17', '2', '1', 'new_story', 'Dịch giả ra truyện mới!', 'Dịch giả \"Mướp Đắng Cũng Có Vị Ngọt\" vừa phát hành bộ truyện mới: \"NHẬN NHẦM BẠN TRAI YÊU QUA MẠNG, KHÔNG NGỜ LẠI VỚ ĐƯỢC NAM THẦN\". Khám phá ngay!', 'https://muopdangcungcovingot.io.vn/nhan-nham-ban-trai-yeu-qua-mang-khong-ngo-lai-vo-duoc-nam-than/', '', '0', '2026-10-19 20:36:38');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_muop_splitter_history`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_muop_splitter_history`;
CREATE TABLE `wp_muop_splitter_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_key` varchar(64) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `total_chapters` int(11) NOT NULL DEFAULT 0,
  `total_words` int(11) NOT NULL DEFAULT 0,
  `avg_words` int(11) NOT NULL DEFAULT 0,
  `target_words` int(11) NOT NULL DEFAULT 2500,
  `chapters_json` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_muop_translator_payments`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_muop_translator_payments`;
CREATE TABLE `wp_muop_translator_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `translator_id` bigint(20) unsigned NOT NULL,
  `admin_id` bigint(20) unsigned NOT NULL,
  `period` varchar(50) NOT NULL,
  `views` bigint(20) unsigned DEFAULT 0,
  `amount` decimal(12,0) NOT NULL,
  `bill_image` varchar(500) NOT NULL,
  `note` text DEFAULT '',
  `bank_name` varchar(100) DEFAULT '',
  `bank_account_holder` varchar(150) DEFAULT '',
  `bank_account_number` varchar(50) DEFAULT '',
  `status` varchar(20) DEFAULT 'paid',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `translator_id` (`translator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_muop_visitor_read_logs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_muop_visitor_read_logs`;
CREATE TABLE `wp_muop_visitor_read_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `user_id` bigint(20) DEFAULT 0,
  `user_name` varchar(100) DEFAULT 'Khách vãng lai',
  `story_id` bigint(20) NOT NULL,
  `story_title` varchar(255) NOT NULL,
  `chapter_id` bigint(20) NOT NULL,
  `chapter_num` int(11) DEFAULT 1,
  `chapter_title` varchar(255) DEFAULT '',
  `device_type` varchar(50) DEFAULT 'Desktop',
  `read_date` date NOT NULL,
  `read_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_read_date` (`read_date`),
  KEY `idx_ip_date` (`read_date`,`ip_address`),
  KEY `idx_story_id` (`story_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_options`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_options`;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=621 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_options`
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
('1', 'cron', 'a:9:{i:1792425169;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1792455108;a:1:{s:21:\"wp_update_user_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1792457556;a:1:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1792459356;a:1:{s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1792461156;a:1:{s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1792497169;a:2:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:41:\"wp_privacy_personal_data_cleanup_requests\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1792500029;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1792669969;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}s:7:\"version\";i:2;}', 'on'),
('2', 'siteurl', 'https://muopdangcungcovingot.io.vn/core', 'on'),
('3', 'home', 'https://muopdangcungcovingot.io.vn', 'on'),
('4', 'blogname', 'Mướp Đắng Cũng Có Vị Ngọt', 'on'),
('5', 'blogdescription', '', 'on'),
('6', 'users_can_register', '0', 'on'),
('7', 'admin_email', 'admin@muopdang.local', 'on'),
('8', 'start_of_week', '1', 'on'),
('9', 'use_balanceTags', '0', 'on'),
('10', 'use_smilies', '1', 'on'),
('11', 'require_name_email', '1', 'on'),
('12', 'comments_notify', '1', 'on'),
('13', 'posts_per_rss', '10', 'on'),
('14', 'rss_use_excerpt', '0', 'on'),
('15', 'mailserver_url', 'mail.example.com', 'on'),
('16', 'mailserver_login', 'login@example.com', 'on'),
('17', 'mailserver_pass', '', 'on'),
('18', 'mailserver_port', '110', 'on'),
('19', 'default_category', '1', 'on'),
('20', 'default_comment_status', 'open', 'on'),
('21', 'default_ping_status', 'open', 'on'),
('22', 'default_pingback_flag', '1', 'on'),
('23', 'posts_per_page', '24', 'on'),
('24', 'date_format', 'F j, Y', 'on'),
('25', 'time_format', 'g:i a', 'on'),
('26', 'links_updated_date_format', 'F j, Y g:i a', 'on'),
('27', 'comment_moderation', '0', 'on'),
('28', 'moderation_notify', '1', 'on'),
('29', 'permalink_structure', '/%postname%/', 'on'),
('30', 'rewrite_rules', 'a:149:{s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:21:\"^index.php/wp-json/?$\";s:22:\"index.php?rest_route=/\";s:24:\"^index.php/wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:17:\"^wp-sitemap\\.xml$\";s:23:\"index.php?sitemap=index\";s:17:\"^wp-sitemap\\.xsl$\";s:36:\"index.php?sitemap-stylesheet=sitemap\";s:23:\"^wp-sitemap-index\\.xsl$\";s:34:\"index.php?sitemap-stylesheet=index\";s:48:\"^wp-sitemap-([a-z]+?)-([a-z\\d_-]+?)-(\\d+?)\\.xml$\";s:75:\"index.php?sitemap=$matches[1]&sitemap-subtype=$matches[2]&paged=$matches[3]\";s:34:\"^wp-sitemap-([a-z]+?)-(\\d+?)\\.xml$\";s:47:\"index.php?sitemap=$matches[1]&paged=$matches[2]\";s:9:\"truyen/?$\";s:26:\"index.php?post_type=truyen\";s:39:\"truyen/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?post_type=truyen&feed=$matches[1]\";s:34:\"truyen/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?post_type=truyen&feed=$matches[1]\";s:26:\"truyen/page/([0-9]{1,})/?$\";s:44:\"index.php?post_type=truyen&paged=$matches[1]\";s:47:\"category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:42:\"category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:23:\"category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:35:\"category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:17:\"category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:44:\"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:39:\"tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:20:\"tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:32:\"tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:14:\"tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:45:\"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:40:\"type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:21:\"type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:33:\"type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:15:\"type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:34:\"truyen/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:44:\"truyen/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:64:\"truyen/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:59:\"truyen/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:59:\"truyen/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:40:\"truyen/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:23:\"truyen/([^/]+)/embed/?$\";s:39:\"index.php?truyen=$matches[1]&embed=true\";s:27:\"truyen/([^/]+)/trackback/?$\";s:33:\"index.php?truyen=$matches[1]&tb=1\";s:47:\"truyen/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:45:\"index.php?truyen=$matches[1]&feed=$matches[2]\";s:42:\"truyen/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:45:\"index.php?truyen=$matches[1]&feed=$matches[2]\";s:35:\"truyen/([^/]+)/page/?([0-9]{1,})/?$\";s:46:\"index.php?truyen=$matches[1]&paged=$matches[2]\";s:42:\"truyen/([^/]+)/comment-page-([0-9]{1,})/?$\";s:46:\"index.php?truyen=$matches[1]&cpage=$matches[2]\";s:31:\"truyen/([^/]+)(?:/([0-9]+))?/?$\";s:45:\"index.php?truyen=$matches[1]&page=$matches[2]\";s:23:\"truyen/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:33:\"truyen/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:53:\"truyen/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:48:\"truyen/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:48:\"truyen/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:29:\"truyen/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:34:\"chuong/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:44:\"chuong/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:64:\"chuong/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:59:\"chuong/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:59:\"chuong/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:40:\"chuong/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:23:\"chuong/([^/]+)/embed/?$\";s:39:\"index.php?chuong=$matches[1]&embed=true\";s:27:\"chuong/([^/]+)/trackback/?$\";s:33:\"index.php?chuong=$matches[1]&tb=1\";s:35:\"chuong/([^/]+)/page/?([0-9]{1,})/?$\";s:46:\"index.php?chuong=$matches[1]&paged=$matches[2]\";s:42:\"chuong/([^/]+)/comment-page-([0-9]{1,})/?$\";s:46:\"index.php?chuong=$matches[1]&cpage=$matches[2]\";s:31:\"chuong/([^/]+)(?:/([0-9]+))?/?$\";s:45:\"index.php?chuong=$matches[1]&page=$matches[2]\";s:23:\"chuong/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:33:\"chuong/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:53:\"chuong/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:48:\"chuong/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:48:\"chuong/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:29:\"chuong/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:49:\"the-loai/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?the_loai=$matches[1]&feed=$matches[2]\";s:44:\"the-loai/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?the_loai=$matches[1]&feed=$matches[2]\";s:25:\"the-loai/([^/]+)/embed/?$\";s:41:\"index.php?the_loai=$matches[1]&embed=true\";s:37:\"the-loai/([^/]+)/page/?([0-9]{1,})/?$\";s:48:\"index.php?the_loai=$matches[1]&paged=$matches[2]\";s:19:\"the-loai/([^/]+)/?$\";s:30:\"index.php?the_loai=$matches[1]\";s:48:\"tac-gia/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:46:\"index.php?tac_gia=$matches[1]&feed=$matches[2]\";s:43:\"tac-gia/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:46:\"index.php?tac_gia=$matches[1]&feed=$matches[2]\";s:24:\"tac-gia/([^/]+)/embed/?$\";s:40:\"index.php?tac_gia=$matches[1]&embed=true\";s:36:\"tac-gia/([^/]+)/page/?([0-9]{1,})/?$\";s:47:\"index.php?tac_gia=$matches[1]&paged=$matches[2]\";s:18:\"tac-gia/([^/]+)/?$\";s:29:\"index.php?tac_gia=$matches[1]\";s:50:\"team-dich/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:48:\"index.php?team_dich=$matches[1]&feed=$matches[2]\";s:45:\"team-dich/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:48:\"index.php?team_dich=$matches[1]&feed=$matches[2]\";s:26:\"team-dich/([^/]+)/embed/?$\";s:42:\"index.php?team_dich=$matches[1]&embed=true\";s:38:\"team-dich/([^/]+)/page/?([0-9]{1,})/?$\";s:49:\"index.php?team_dich=$matches[1]&paged=$matches[2]\";s:20:\"team-dich/([^/]+)/?$\";s:31:\"index.php?team_dich=$matches[1]\";s:12:\"robots\\.txt$\";s:18:\"index.php?robots=1\";s:13:\"favicon\\.ico$\";s:19:\"index.php?favicon=1\";s:12:\"sitemap\\.xml\";s:23:\"index.php?sitemap=index\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:47:\"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:42:\"author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:23:\"author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:35:\"author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:17:\"author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:69:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:45:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:39:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:56:\"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:51:\"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:32:\"([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:44:\"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:26:\"([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:43:\"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:38:\"([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:19:\"([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:31:\"([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:13:\"([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";s:27:\"[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\"[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\"[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\"[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"([^/]+)/embed/?$\";s:37:\"index.php?name=$matches[1]&embed=true\";s:20:\"([^/]+)/trackback/?$\";s:31:\"index.php?name=$matches[1]&tb=1\";s:40:\"([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:35:\"([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:28:\"([^/]+)/page/?([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&paged=$matches[2]\";s:35:\"([^/]+)/comment-page-([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&cpage=$matches[2]\";s:24:\"([^/]+)(?:/([0-9]+))?/?$\";s:43:\"index.php?name=$matches[1]&page=$matches[2]\";s:16:\"[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:26:\"[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:46:\"[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:22:\"[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";}', 'on'),
('31', 'hack_file', '0', 'on'),
('32', 'blog_charset', 'UTF-8', 'on'),
('33', 'moderation_keys', '', 'off'),
('34', 'active_plugins', 'a:0:{}', 'on'),
('35', 'category_base', '', 'on'),
('36', 'ping_sites', 'https://rpc.pingomatic.com/', 'on'),
('37', 'comment_max_links', '2', 'on'),
('38', 'gmt_offset', '7', 'on'),
('39', 'default_email_category', '1', 'on'),
('40', 'recently_edited', '', 'off'),
('41', 'template', 'muop-dang', 'on'),
('42', 'stylesheet', 'muop-dang', 'on'),
('43', 'comment_registration', '0', 'on'),
('44', 'html_type', 'text/html', 'on'),
('45', 'use_trackback', '0', 'on'),
('46', 'default_role', 'subscriber', 'on'),
('47', 'db_version', '61833', 'on'),
('48', 'uploads_use_yearmonth_folders', '1', 'on'),
('49', 'upload_path', '', 'on'),
('50', 'blog_public', '1', 'on');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
('51', 'default_link_category', '2', 'on'),
('52', 'show_on_front', 'posts', 'on'),
('53', 'tag_base', '', 'on'),
('54', 'show_avatars', '1', 'on'),
('55', 'avatar_rating', 'G', 'on'),
('56', 'upload_url_path', '', 'on'),
('57', 'thumbnail_size_w', '150', 'on'),
('58', 'thumbnail_size_h', '150', 'on'),
('59', 'thumbnail_crop', '1', 'on'),
('60', 'medium_size_w', '300', 'on'),
('61', 'medium_size_h', '300', 'on'),
('62', 'avatar_default', 'mystery', 'on'),
('63', 'large_size_w', '1024', 'on'),
('64', 'large_size_h', '1024', 'on'),
('65', 'image_default_link_type', 'none', 'on'),
('66', 'image_default_size', '', 'on'),
('67', 'image_default_align', '', 'on'),
('68', 'close_comments_for_old_posts', '0', 'on'),
('69', 'close_comments_days_old', '14', 'on'),
('70', 'thread_comments', '1', 'on'),
('71', 'thread_comments_depth', '5', 'on'),
('72', 'page_comments', '0', 'on'),
('73', 'comments_per_page', '50', 'on'),
('74', 'default_comments_page', 'newest', 'on'),
('75', 'comment_order', 'asc', 'on'),
('76', 'sticky_posts', 'a:0:{}', 'on'),
('77', 'widget_categories', 'a:0:{}', 'on'),
('78', 'widget_text', 'a:0:{}', 'on'),
('79', 'widget_rss', 'a:0:{}', 'on'),
('80', 'uninstall_plugins', 'a:0:{}', 'off'),
('81', 'timezone_string', 'Asia/Ho_Chi_Minh', 'on'),
('82', 'page_for_posts', '0', 'on'),
('83', 'page_on_front', '0', 'on'),
('84', 'default_post_format', '0', 'on'),
('85', 'link_manager_enabled', '0', 'on'),
('86', 'finished_splitting_shared_terms', '1', 'on'),
('87', 'site_icon', '0', 'on'),
('88', 'medium_large_size_w', '768', 'on'),
('89', 'medium_large_size_h', '0', 'on'),
('90', 'wp_page_for_privacy_policy', '3', 'on'),
('91', 'show_comments_cookies_opt_in', '1', 'on'),
('92', 'admin_email_lifespan', '1806925956', 'on'),
('93', 'disallowed_keys', '', 'off'),
('94', 'comment_previously_approved', '1', 'on'),
('95', 'auto_plugin_theme_update_emails', 'a:0:{}', 'off'),
('96', 'auto_update_core_dev', 'enabled', 'on'),
('97', 'auto_update_core_minor', 'enabled', 'on'),
('98', 'auto_update_core_major', 'enabled', 'on'),
('99', 'wp_force_deactivated_plugins', 'a:0:{}', 'on'),
('100', 'wp_attachment_pages_enabled', '0', 'on');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
('101', 'wp_notes_notify', '1', 'on'),
('102', 'initial_db_version', '61833', 'on'),
('103', 'wp_user_roles', 'a:7:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:61:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}s:7:\"doc_gia\";a:2:{s:4:\"name\";s:12:\"Độc Giả\";s:12:\"capabilities\";a:4:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"delete_posts\";b:0;s:12:\"upload_files\";b:0;}}s:8:\"dich_gia\";a:2:{s:4:\"name\";s:12:\"Dịch Giả\";s:12:\"capabilities\";a:5:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:1;s:13:\"publish_posts\";b:0;s:12:\"delete_posts\";b:1;s:12:\"upload_files\";b:1;}}}', 'on'),
('104', 'fresh_site', '0', 'off'),
('105', 'user_count', '8', 'off'),
('106', 'widget_block', 'a:6:{i:2;a:1:{s:7:\"content\";s:19:\"<!-- wp:search /-->\";}i:3;a:1:{s:7:\"content\";s:154:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->\";}i:4;a:1:{s:7:\"content\";s:227:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {\"displayAvatar\":false,\"displayDate\":false,\"displayExcerpt\":false} /--></div><!-- /wp:group -->\";}i:5;a:1:{s:7:\"content\";s:146:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->\";}i:6;a:1:{s:7:\"content\";s:150:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->\";}s:12:\"_multiwidget\";i:1;}', 'auto'),
('107', 'sidebars_widgets', 'a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}s:13:\"array_version\";i:3;}', 'auto'),
('108', 'widget_pages', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('109', 'widget_calendar', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('110', 'widget_archives', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('111', 'widget_media_audio', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('112', 'widget_media_image', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('113', 'widget_media_gallery', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('114', 'widget_media_video', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('115', 'widget_meta', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('116', 'widget_search', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('117', 'widget_recent-posts', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('118', 'widget_recent-comments', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('119', 'widget_tag_cloud', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('120', 'widget_nav_menu', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('121', 'widget_custom_html', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'auto'),
('122', '_transient_wp_core_block_css_files', 'a:2:{s:7:\"version\";s:5:\"7.1.3\";s:5:\"files\";a:624:{i:0;s:31:\"accordion-heading/style-rtl.css\";i:1;s:35:\"accordion-heading/style-rtl.min.css\";i:2;s:27:\"accordion-heading/style.css\";i:3;s:31:\"accordion-heading/style.min.css\";i:4;s:28:\"accordion-item/style-rtl.css\";i:5;s:32:\"accordion-item/style-rtl.min.css\";i:6;s:24:\"accordion-item/style.css\";i:7;s:28:\"accordion-item/style.min.css\";i:8;s:29:\"accordion-panel/style-rtl.css\";i:9;s:33:\"accordion-panel/style-rtl.min.css\";i:10;s:25:\"accordion-panel/style.css\";i:11;s:29:\"accordion-panel/style.min.css\";i:12;s:23:\"accordion/style-rtl.css\";i:13;s:27:\"accordion/style-rtl.min.css\";i:14;s:19:\"accordion/style.css\";i:15;s:23:\"accordion/style.min.css\";i:16;s:22:\"archives/style-rtl.css\";i:17;s:26:\"archives/style-rtl.min.css\";i:18;s:18:\"archives/style.css\";i:19;s:22:\"archives/style.min.css\";i:20;s:20:\"audio/editor-rtl.css\";i:21;s:24:\"audio/editor-rtl.min.css\";i:22;s:16:\"audio/editor.css\";i:23;s:20:\"audio/editor.min.css\";i:24;s:19:\"audio/style-rtl.css\";i:25;s:23:\"audio/style-rtl.min.css\";i:26;s:15:\"audio/style.css\";i:27;s:19:\"audio/style.min.css\";i:28;s:19:\"audio/theme-rtl.css\";i:29;s:23:\"audio/theme-rtl.min.css\";i:30;s:15:\"audio/theme.css\";i:31;s:19:\"audio/theme.min.css\";i:32;s:21:\"avatar/editor-rtl.css\";i:33;s:25:\"avatar/editor-rtl.min.css\";i:34;s:17:\"avatar/editor.css\";i:35;s:21:\"avatar/editor.min.css\";i:36;s:20:\"avatar/style-rtl.css\";i:37;s:24:\"avatar/style-rtl.min.css\";i:38;s:16:\"avatar/style.css\";i:39;s:20:\"avatar/style.min.css\";i:40;s:25:\"breadcrumbs/style-rtl.css\";i:41;s:29:\"breadcrumbs/style-rtl.min.css\";i:42;s:21:\"breadcrumbs/style.css\";i:43;s:25:\"breadcrumbs/style.min.css\";i:44;s:21:\"button/editor-rtl.css\";i:45;s:25:\"button/editor-rtl.min.css\";i:46;s:17:\"button/editor.css\";i:47;s:21:\"button/editor.min.css\";i:48;s:20:\"button/style-rtl.css\";i:49;s:24:\"button/style-rtl.min.css\";i:50;s:16:\"button/style.css\";i:51;s:20:\"button/style.min.css\";i:52;s:22:\"buttons/editor-rtl.css\";i:53;s:26:\"buttons/editor-rtl.min.css\";i:54;s:18:\"buttons/editor.css\";i:55;s:22:\"buttons/editor.min.css\";i:56;s:21:\"buttons/style-rtl.css\";i:57;s:25:\"buttons/style-rtl.min.css\";i:58;s:17:\"buttons/style.css\";i:59;s:21:\"buttons/style.min.css\";i:60;s:22:\"calendar/style-rtl.css\";i:61;s:26:\"calendar/style-rtl.min.css\";i:62;s:18:\"calendar/style.css\";i:63;s:22:\"calendar/style.min.css\";i:64;s:25:\"categories/editor-rtl.css\";i:65;s:29:\"categories/editor-rtl.min.css\";i:66;s:21:\"categories/editor.css\";i:67;s:25:\"categories/editor.min.css\";i:68;s:24:\"categories/style-rtl.css\";i:69;s:28:\"categories/style-rtl.min.css\";i:70;s:20:\"categories/style.css\";i:71;s:24:\"categories/style.min.css\";i:72;s:19:\"code/editor-rtl.css\";i:73;s:23:\"code/editor-rtl.min.css\";i:74;s:15:\"code/editor.css\";i:75;s:19:\"code/editor.min.css\";i:76;s:18:\"code/style-rtl.css\";i:77;s:22:\"code/style-rtl.min.css\";i:78;s:14:\"code/style.css\";i:79;s:18:\"code/style.min.css\";i:80;s:18:\"code/theme-rtl.css\";i:81;s:22:\"code/theme-rtl.min.css\";i:82;s:14:\"code/theme.css\";i:83;s:18:\"code/theme.min.css\";i:84;s:22:\"columns/editor-rtl.css\";i:85;s:26:\"columns/editor-rtl.min.css\";i:86;s:18:\"columns/editor.css\";i:87;s:22:\"columns/editor.min.css\";i:88;s:21:\"columns/style-rtl.css\";i:89;s:25:\"columns/style-rtl.min.css\";i:90;s:17:\"columns/style.css\";i:91;s:21:\"columns/style.min.css\";i:92;s:33:\"comment-author-name/style-rtl.css\";i:93;s:37:\"comment-author-name/style-rtl.min.css\";i:94;s:29:\"comment-author-name/style.css\";i:95;s:33:\"comment-author-name/style.min.css\";i:96;s:29:\"comment-content/style-rtl.css\";i:97;s:33:\"comment-content/style-rtl.min.css\";i:98;s:25:\"comment-content/style.css\";i:99;s:29:\"comment-content/style.min.css\";i:100;s:26:\"comment-date/style-rtl.css\";i:101;s:30:\"comment-date/style-rtl.min.css\";i:102;s:22:\"comment-date/style.css\";i:103;s:26:\"comment-date/style.min.css\";i:104;s:31:\"comment-edit-link/style-rtl.css\";i:105;s:35:\"comment-edit-link/style-rtl.min.css\";i:106;s:27:\"comment-edit-link/style.css\";i:107;s:31:\"comment-edit-link/style.min.css\";i:108;s:32:\"comment-reply-link/style-rtl.css\";i:109;s:36:\"comment-reply-link/style-rtl.min.css\";i:110;s:28:\"comment-reply-link/style.css\";i:111;s:32:\"comment-reply-link/style.min.css\";i:112;s:30:\"comment-template/style-rtl.css\";i:113;s:34:\"comment-template/style-rtl.min.css\";i:114;s:26:\"comment-template/style.css\";i:115;s:30:\"comment-template/style.min.css\";i:116;s:42:\"comments-pagination-numbers/editor-rtl.css\";i:117;s:46:\"comments-pagination-numbers/editor-rtl.min.css\";i:118;s:38:\"comments-pagination-numbers/editor.css\";i:119;s:42:\"comments-pagination-numbers/editor.min.css\";i:120;s:34:\"comments-pagination/editor-rtl.css\";i:121;s:38:\"comments-pagination/editor-rtl.min.css\";i:122;s:30:\"comments-pagination/editor.css\";i:123;s:34:\"comments-pagination/editor.min.css\";i:124;s:33:\"comments-pagination/style-rtl.css\";i:125;s:37:\"comments-pagination/style-rtl.min.css\";i:126;s:29:\"comments-pagination/style.css\";i:127;s:33:\"comments-pagination/style.min.css\";i:128;s:29:\"comments-title/editor-rtl.css\";i:129;s:33:\"comments-title/editor-rtl.min.css\";i:130;s:25:\"comments-title/editor.css\";i:131;s:29:\"comments-title/editor.min.css\";i:132;s:23:\"comments/editor-rtl.css\";i:133;s:27:\"comments/editor-rtl.min.css\";i:134;s:19:\"comments/editor.css\";i:135;s:23:\"comments/editor.min.css\";i:136;s:22:\"comments/style-rtl.css\";i:137;s:26:\"comments/style-rtl.min.css\";i:138;s:18:\"comments/style.css\";i:139;s:22:\"comments/style.min.css\";i:140;s:20:\"cover/editor-rtl.css\";i:141;s:24:\"cover/editor-rtl.min.css\";i:142;s:16:\"cover/editor.css\";i:143;s:20:\"cover/editor.min.css\";i:144;s:19:\"cover/style-rtl.css\";i:145;s:23:\"cover/style-rtl.min.css\";i:146;s:15:\"cover/style.css\";i:147;s:19:\"cover/style.min.css\";i:148;s:22:\"details/editor-rtl.css\";i:149;s:26:\"details/editor-rtl.min.css\";i:150;s:18:\"details/editor.css\";i:151;s:22:\"details/editor.min.css\";i:152;s:21:\"details/style-rtl.css\";i:153;s:25:\"details/style-rtl.min.css\";i:154;s:17:\"details/style.css\";i:155;s:21:\"details/style.min.css\";i:156;s:20:\"embed/editor-rtl.css\";i:157;s:24:\"embed/editor-rtl.min.css\";i:158;s:16:\"embed/editor.css\";i:159;s:20:\"embed/editor.min.css\";i:160;s:19:\"embed/style-rtl.css\";i:161;s:23:\"embed/style-rtl.min.css\";i:162;s:15:\"embed/style.css\";i:163;s:19:\"embed/style.min.css\";i:164;s:19:\"embed/theme-rtl.css\";i:165;s:23:\"embed/theme-rtl.min.css\";i:166;s:15:\"embed/theme.css\";i:167;s:19:\"embed/theme.min.css\";i:168;s:19:\"file/editor-rtl.css\";i:169;s:23:\"file/editor-rtl.min.css\";i:170;s:15:\"file/editor.css\";i:171;s:19:\"file/editor.min.css\";i:172;s:18:\"file/style-rtl.css\";i:173;s:22:\"file/style-rtl.min.css\";i:174;s:14:\"file/style.css\";i:175;s:18:\"file/style.min.css\";i:176;s:23:\"footnotes/style-rtl.css\";i:177;s:27:\"footnotes/style-rtl.min.css\";i:178;s:19:\"footnotes/style.css\";i:179;s:23:\"footnotes/style.min.css\";i:180;s:23:\"freeform/editor-rtl.css\";i:181;s:27:\"freeform/editor-rtl.min.css\";i:182;s:19:\"freeform/editor.css\";i:183;s:23:\"freeform/editor.min.css\";i:184;s:22:\"gallery/editor-rtl.css\";i:185;s:26:\"gallery/editor-rtl.min.css\";i:186;s:18:\"gallery/editor.css\";i:187;s:22:\"gallery/editor.min.css\";i:188;s:21:\"gallery/style-rtl.css\";i:189;s:25:\"gallery/style-rtl.min.css\";i:190;s:17:\"gallery/style.css\";i:191;s:21:\"gallery/style.min.css\";i:192;s:21:\"gallery/theme-rtl.css\";i:193;s:25:\"gallery/theme-rtl.min.css\";i:194;s:17:\"gallery/theme.css\";i:195;s:21:\"gallery/theme.min.css\";i:196;s:20:\"group/editor-rtl.css\";i:197;s:24:\"group/editor-rtl.min.css\";i:198;s:16:\"group/editor.css\";i:199;s:20:\"group/editor.min.css\";i:200;s:19:\"group/style-rtl.css\";i:201;s:23:\"group/style-rtl.min.css\";i:202;s:15:\"group/style.css\";i:203;s:19:\"group/style.min.css\";i:204;s:19:\"group/theme-rtl.css\";i:205;s:23:\"group/theme-rtl.min.css\";i:206;s:15:\"group/theme.css\";i:207;s:19:\"group/theme.min.css\";i:208;s:21:\"heading/style-rtl.css\";i:209;s:25:\"heading/style-rtl.min.css\";i:210;s:17:\"heading/style.css\";i:211;s:21:\"heading/style.min.css\";i:212;s:19:\"html/editor-rtl.css\";i:213;s:23:\"html/editor-rtl.min.css\";i:214;s:15:\"html/editor.css\";i:215;s:19:\"html/editor.min.css\";i:216;s:19:\"icon/editor-rtl.css\";i:217;s:23:\"icon/editor-rtl.min.css\";i:218;s:15:\"icon/editor.css\";i:219;s:19:\"icon/editor.min.css\";i:220;s:18:\"icon/style-rtl.css\";i:221;s:22:\"icon/style-rtl.min.css\";i:222;s:14:\"icon/style.css\";i:223;s:18:\"icon/style.min.css\";i:224;s:20:\"image/editor-rtl.css\";i:225;s:24:\"image/editor-rtl.min.css\";i:226;s:16:\"image/editor.css\";i:227;s:20:\"image/editor.min.css\";i:228;s:19:\"image/style-rtl.css\";i:229;s:23:\"image/style-rtl.min.css\";i:230;s:15:\"image/style.css\";i:231;s:19:\"image/style.min.css\";i:232;s:19:\"image/theme-rtl.css\";i:233;s:23:\"image/theme-rtl.min.css\";i:234;s:15:\"image/theme.css\";i:235;s:19:\"image/theme.min.css\";i:236;s:29:\"latest-comments/style-rtl.css\";i:237;s:33:\"latest-comments/style-rtl.min.css\";i:238;s:25:\"latest-comments/style.css\";i:239;s:29:\"latest-comments/style.min.css\";i:240;s:27:\"latest-posts/editor-rtl.css\";i:241;s:31:\"latest-posts/editor-rtl.min.css\";i:242;s:23:\"latest-posts/editor.css\";i:243;s:27:\"latest-posts/editor.min.css\";i:244;s:26:\"latest-posts/style-rtl.css\";i:245;s:30:\"latest-posts/style-rtl.min.css\";i:246;s:22:\"latest-posts/style.css\";i:247;s:26:\"latest-posts/style.min.css\";i:248;s:18:\"list/style-rtl.css\";i:249;s:22:\"list/style-rtl.min.css\";i:250;s:14:\"list/style.css\";i:251;s:18:\"list/style.min.css\";i:252;s:22:\"loginout/style-rtl.css\";i:253;s:26:\"loginout/style-rtl.min.css\";i:254;s:18:\"loginout/style.css\";i:255;s:22:\"loginout/style.min.css\";i:256;s:19:\"math/editor-rtl.css\";i:257;s:23:\"math/editor-rtl.min.css\";i:258;s:15:\"math/editor.css\";i:259;s:19:\"math/editor.min.css\";i:260;s:18:\"math/style-rtl.css\";i:261;s:22:\"math/style-rtl.min.css\";i:262;s:14:\"math/style.css\";i:263;s:18:\"math/style.min.css\";i:264;s:25:\"media-text/editor-rtl.css\";i:265;s:29:\"media-text/editor-rtl.min.css\";i:266;s:21:\"media-text/editor.css\";i:267;s:25:\"media-text/editor.min.css\";i:268;s:24:\"media-text/style-rtl.css\";i:269;s:28:\"media-text/style-rtl.min.css\";i:270;s:20:\"media-text/style.css\";i:271;s:24:\"media-text/style.min.css\";i:272;s:19:\"more/editor-rtl.css\";i:273;s:23:\"more/editor-rtl.min.css\";i:274;s:15:\"more/editor.css\";i:275;s:19:\"more/editor.min.css\";i:276;s:30:\"navigation-link/editor-rtl.css\";i:277;s:34:\"navigation-link/editor-rtl.min.css\";i:278;s:26:\"navigation-link/editor.css\";i:279;s:30:\"navigation-link/editor.min.css\";i:280;s:29:\"navigation-link/style-rtl.css\";i:281;s:33:\"navigation-link/style-rtl.min.css\";i:282;s:25:\"navigation-link/style.css\";i:283;s:29:\"navigation-link/style.min.css\";i:284;s:38:\"navigation-overlay-close/style-rtl.css\";i:285;s:42:\"navigation-overlay-close/style-rtl.min.css\";i:286;s:34:\"navigation-overlay-close/style.css\";i:287;s:38:\"navigation-overlay-close/style.min.css\";i:288;s:33:\"navigation-submenu/editor-rtl.css\";i:289;s:37:\"navigation-submenu/editor-rtl.min.css\";i:290;s:29:\"navigation-submenu/editor.css\";i:291;s:33:\"navigation-submenu/editor.min.css\";i:292;s:25:\"navigation/editor-rtl.css\";i:293;s:29:\"navigation/editor-rtl.min.css\";i:294;s:21:\"navigation/editor.css\";i:295;s:25:\"navigation/editor.min.css\";i:296;s:24:\"navigation/style-rtl.css\";i:297;s:28:\"navigation/style-rtl.min.css\";i:298;s:20:\"navigation/style.css\";i:299;s:24:\"navigation/style.min.css\";i:300;s:23:\"nextpage/editor-rtl.css\";i:301;s:27:\"nextpage/editor-rtl.min.css\";i:302;s:19:\"nextpage/editor.css\";i:303;s:23:\"nextpage/editor.min.css\";i:304;s:24:\"page-list/editor-rtl.css\";i:305;s:28:\"page-list/editor-rtl.min.css\";i:306;s:20:\"page-list/editor.css\";i:307;s:24:\"page-list/editor.min.css\";i:308;s:23:\"page-list/style-rtl.css\";i:309;s:27:\"page-list/style-rtl.min.css\";i:310;s:19:\"page-list/style.css\";i:311;s:23:\"page-list/style.min.css\";i:312;s:24:\"paragraph/editor-rtl.css\";i:313;s:28:\"paragraph/editor-rtl.min.css\";i:314;s:20:\"paragraph/editor.css\";i:315;s:24:\"paragraph/editor.min.css\";i:316;s:23:\"paragraph/style-rtl.css\";i:317;s:27:\"paragraph/style-rtl.min.css\";i:318;s:19:\"paragraph/style.css\";i:319;s:23:\"paragraph/style.min.css\";i:320;s:28:\"playlist-track/style-rtl.css\";i:321;s:32:\"playlist-track/style-rtl.min.css\";i:322;s:24:\"playlist-track/style.css\";i:323;s:28:\"playlist-track/style.min.css\";i:324;s:23:\"playlist/editor-rtl.css\";i:325;s:27:\"playlist/editor-rtl.min.css\";i:326;s:19:\"playlist/editor.css\";i:327;s:23:\"playlist/editor.min.css\";i:328;s:22:\"playlist/style-rtl.css\";i:329;s:26:\"playlist/style-rtl.min.css\";i:330;s:18:\"playlist/style.css\";i:331;s:22:\"playlist/style.min.css\";i:332;s:35:\"post-author-biography/style-rtl.css\";i:333;s:39:\"post-author-biography/style-rtl.min.css\";i:334;s:31:\"post-author-biography/style.css\";i:335;s:35:\"post-author-biography/style.min.css\";i:336;s:30:\"post-author-name/style-rtl.css\";i:337;s:34:\"post-author-name/style-rtl.min.css\";i:338;s:26:\"post-author-name/style.css\";i:339;s:30:\"post-author-name/style.min.css\";i:340;s:26:\"post-author/editor-rtl.css\";i:341;s:30:\"post-author/editor-rtl.min.css\";i:342;s:22:\"post-author/editor.css\";i:343;s:26:\"post-author/editor.min.css\";i:344;s:25:\"post-author/style-rtl.css\";i:345;s:29:\"post-author/style-rtl.min.css\";i:346;s:21:\"post-author/style.css\";i:347;s:25:\"post-author/style.min.css\";i:348;s:33:\"post-comments-count/style-rtl.css\";i:349;s:37:\"post-comments-count/style-rtl.min.css\";i:350;s:29:\"post-comments-count/style.css\";i:351;s:33:\"post-comments-count/style.min.css\";i:352;s:33:\"post-comments-form/editor-rtl.css\";i:353;s:37:\"post-comments-form/editor-rtl.min.css\";i:354;s:29:\"post-comments-form/editor.css\";i:355;s:33:\"post-comments-form/editor.min.css\";i:356;s:32:\"post-comments-form/style-rtl.css\";i:357;s:36:\"post-comments-form/style-rtl.min.css\";i:358;s:28:\"post-comments-form/style.css\";i:359;s:32:\"post-comments-form/style.min.css\";i:360;s:32:\"post-comments-link/style-rtl.css\";i:361;s:36:\"post-comments-link/style-rtl.min.css\";i:362;s:28:\"post-comments-link/style.css\";i:363;s:32:\"post-comments-link/style.min.css\";i:364;s:26:\"post-content/style-rtl.css\";i:365;s:30:\"post-content/style-rtl.min.css\";i:366;s:22:\"post-content/style.css\";i:367;s:26:\"post-content/style.min.css\";i:368;s:23:\"post-date/style-rtl.css\";i:369;s:27:\"post-date/style-rtl.min.css\";i:370;s:19:\"post-date/style.css\";i:371;s:23:\"post-date/style.min.css\";i:372;s:27:\"post-excerpt/editor-rtl.css\";i:373;s:31:\"post-excerpt/editor-rtl.min.css\";i:374;s:23:\"post-excerpt/editor.css\";i:375;s:27:\"post-excerpt/editor.min.css\";i:376;s:26:\"post-excerpt/style-rtl.css\";i:377;s:30:\"post-excerpt/style-rtl.min.css\";i:378;s:22:\"post-excerpt/style.css\";i:379;s:26:\"post-excerpt/style.min.css\";i:380;s:34:\"post-featured-image/editor-rtl.css\";i:381;s:38:\"post-featured-image/editor-rtl.min.css\";i:382;s:30:\"post-featured-image/editor.css\";i:383;s:34:\"post-featured-image/editor.min.css\";i:384;s:33:\"post-featured-image/style-rtl.css\";i:385;s:37:\"post-featured-image/style-rtl.min.css\";i:386;s:29:\"post-featured-image/style.css\";i:387;s:33:\"post-featured-image/style.min.css\";i:388;s:34:\"post-navigation-link/style-rtl.css\";i:389;s:38:\"post-navigation-link/style-rtl.min.css\";i:390;s:30:\"post-navigation-link/style.css\";i:391;s:34:\"post-navigation-link/style.min.css\";i:392;s:27:\"post-template/style-rtl.css\";i:393;s:31:\"post-template/style-rtl.min.css\";i:394;s:23:\"post-template/style.css\";i:395;s:27:\"post-template/style.min.css\";i:396;s:24:\"post-terms/style-rtl.css\";i:397;s:28:\"post-terms/style-rtl.min.css\";i:398;s:20:\"post-terms/style.css\";i:399;s:24:\"post-terms/style.min.css\";i:400;s:31:\"post-time-to-read/style-rtl.css\";i:401;s:35:\"post-time-to-read/style-rtl.min.css\";i:402;s:27:\"post-time-to-read/style.css\";i:403;s:31:\"post-time-to-read/style.min.css\";i:404;s:24:\"post-title/style-rtl.css\";i:405;s:28:\"post-title/style-rtl.min.css\";i:406;s:20:\"post-title/style.css\";i:407;s:24:\"post-title/style.min.css\";i:408;s:26:\"preformatted/style-rtl.css\";i:409;s:30:\"preformatted/style-rtl.min.css\";i:410;s:22:\"preformatted/style.css\";i:411;s:26:\"preformatted/style.min.css\";i:412;s:24:\"pullquote/editor-rtl.css\";i:413;s:28:\"pullquote/editor-rtl.min.css\";i:414;s:20:\"pullquote/editor.css\";i:415;s:24:\"pullquote/editor.min.css\";i:416;s:23:\"pullquote/style-rtl.css\";i:417;s:27:\"pullquote/style-rtl.min.css\";i:418;s:19:\"pullquote/style.css\";i:419;s:23:\"pullquote/style.min.css\";i:420;s:23:\"pullquote/theme-rtl.css\";i:421;s:27:\"pullquote/theme-rtl.min.css\";i:422;s:19:\"pullquote/theme.css\";i:423;s:23:\"pullquote/theme.min.css\";i:424;s:39:\"query-pagination-numbers/editor-rtl.css\";i:425;s:43:\"query-pagination-numbers/editor-rtl.min.css\";i:426;s:35:\"query-pagination-numbers/editor.css\";i:427;s:39:\"query-pagination-numbers/editor.min.css\";i:428;s:31:\"query-pagination/editor-rtl.css\";i:429;s:35:\"query-pagination/editor-rtl.min.css\";i:430;s:27:\"query-pagination/editor.css\";i:431;s:31:\"query-pagination/editor.min.css\";i:432;s:30:\"query-pagination/style-rtl.css\";i:433;s:34:\"query-pagination/style-rtl.min.css\";i:434;s:26:\"query-pagination/style.css\";i:435;s:30:\"query-pagination/style.min.css\";i:436;s:25:\"query-title/style-rtl.css\";i:437;s:29:\"query-title/style-rtl.min.css\";i:438;s:21:\"query-title/style.css\";i:439;s:25:\"query-title/style.min.css\";i:440;s:25:\"query-total/style-rtl.css\";i:441;s:29:\"query-total/style-rtl.min.css\";i:442;s:21:\"query-total/style.css\";i:443;s:25:\"query-total/style.min.css\";i:444;s:20:\"query/editor-rtl.css\";i:445;s:24:\"query/editor-rtl.min.css\";i:446;s:16:\"query/editor.css\";i:447;s:20:\"query/editor.min.css\";i:448;s:19:\"quote/style-rtl.css\";i:449;s:23:\"quote/style-rtl.min.css\";i:450;s:15:\"quote/style.css\";i:451;s:19:\"quote/style.min.css\";i:452;s:19:\"quote/theme-rtl.css\";i:453;s:23:\"quote/theme-rtl.min.css\";i:454;s:15:\"quote/theme.css\";i:455;s:19:\"quote/theme.min.css\";i:456;s:23:\"read-more/style-rtl.css\";i:457;s:27:\"read-more/style-rtl.min.css\";i:458;s:19:\"read-more/style.css\";i:459;s:23:\"read-more/style.min.css\";i:460;s:18:\"rss/editor-rtl.css\";i:461;s:22:\"rss/editor-rtl.min.css\";i:462;s:14:\"rss/editor.css\";i:463;s:18:\"rss/editor.min.css\";i:464;s:17:\"rss/style-rtl.css\";i:465;s:21:\"rss/style-rtl.min.css\";i:466;s:13:\"rss/style.css\";i:467;s:17:\"rss/style.min.css\";i:468;s:21:\"search/editor-rtl.css\";i:469;s:25:\"search/editor-rtl.min.css\";i:470;s:17:\"search/editor.css\";i:471;s:21:\"search/editor.min.css\";i:472;s:20:\"search/style-rtl.css\";i:473;s:24:\"search/style-rtl.min.css\";i:474;s:16:\"search/style.css\";i:475;s:20:\"search/style.min.css\";i:476;s:20:\"search/theme-rtl.css\";i:477;s:24:\"search/theme-rtl.min.css\";i:478;s:16:\"search/theme.css\";i:479;s:20:\"search/theme.min.css\";i:480;s:24:\"separator/editor-rtl.css\";i:481;s:28:\"separator/editor-rtl.min.css\";i:482;s:20:\"separator/editor.css\";i:483;s:24:\"separator/editor.min.css\";i:484;s:23:\"separator/style-rtl.css\";i:485;s:27:\"separator/style-rtl.min.css\";i:486;s:19:\"separator/style.css\";i:487;s:23:\"separator/style.min.css\";i:488;s:23:\"separator/theme-rtl.css\";i:489;s:27:\"separator/theme-rtl.min.css\";i:490;s:19:\"separator/theme.css\";i:491;s:23:\"separator/theme.min.css\";i:492;s:24:\"shortcode/editor-rtl.css\";i:493;s:28:\"shortcode/editor-rtl.min.css\";i:494;s:20:\"shortcode/editor.css\";i:495;s:24:\"shortcode/editor.min.css\";i:496;s:24:\"site-logo/editor-rtl.css\";i:497;s:28:\"site-logo/editor-rtl.min.css\";i:498;s:20:\"site-logo/editor.css\";i:499;s:24:\"site-logo/editor.min.css\";i:500;s:23:\"site-logo/style-rtl.css\";i:501;s:27:\"site-logo/style-rtl.min.css\";i:502;s:19:\"site-logo/style.css\";i:503;s:23:\"site-logo/style.min.css\";i:504;s:27:\"site-tagline/editor-rtl.css\";i:505;s:31:\"site-tagline/editor-rtl.min.css\";i:506;s:23:\"site-tagline/editor.css\";i:507;s:27:\"site-tagline/editor.min.css\";i:508;s:26:\"site-tagline/style-rtl.css\";i:509;s:30:\"site-tagline/style-rtl.min.css\";i:510;s:22:\"site-tagline/style.css\";i:511;s:26:\"site-tagline/style.min.css\";i:512;s:25:\"site-title/editor-rtl.css\";i:513;s:29:\"site-title/editor-rtl.min.css\";i:514;s:21:\"site-title/editor.css\";i:515;s:25:\"site-title/editor.min.css\";i:516;s:24:\"site-title/style-rtl.css\";i:517;s:28:\"site-title/style-rtl.min.css\";i:518;s:20:\"site-title/style.css\";i:519;s:24:\"site-title/style.min.css\";i:520;s:26:\"social-link/editor-rtl.css\";i:521;s:30:\"social-link/editor-rtl.min.css\";i:522;s:22:\"social-link/editor.css\";i:523;s:26:\"social-link/editor.min.css\";i:524;s:27:\"social-links/editor-rtl.css\";i:525;s:31:\"social-links/editor-rtl.min.css\";i:526;s:23:\"social-links/editor.css\";i:527;s:27:\"social-links/editor.min.css\";i:528;s:26:\"social-links/style-rtl.css\";i:529;s:30:\"social-links/style-rtl.min.css\";i:530;s:22:\"social-links/style.css\";i:531;s:26:\"social-links/style.min.css\";i:532;s:21:\"spacer/editor-rtl.css\";i:533;s:25:\"spacer/editor-rtl.min.css\";i:534;s:17:\"spacer/editor.css\";i:535;s:21:\"spacer/editor.min.css\";i:536;s:20:\"spacer/style-rtl.css\";i:537;s:24:\"spacer/style-rtl.min.css\";i:538;s:16:\"spacer/style.css\";i:539;s:20:\"spacer/style.min.css\";i:540;s:23:\"tab-list/editor-rtl.css\";i:541;s:27:\"tab-list/editor-rtl.min.css\";i:542;s:19:\"tab-list/editor.css\";i:543;s:23:\"tab-list/editor.min.css\";i:544;s:22:\"tab-list/style-rtl.css\";i:545;s:26:\"tab-list/style-rtl.min.css\";i:546;s:18:\"tab-list/style.css\";i:547;s:22:\"tab-list/style.min.css\";i:548;s:23:\"tab-panel/style-rtl.css\";i:549;s:27:\"tab-panel/style-rtl.min.css\";i:550;s:19:\"tab-panel/style.css\";i:551;s:23:\"tab-panel/style.min.css\";i:552;s:20:\"table/editor-rtl.css\";i:553;s:24:\"table/editor-rtl.min.css\";i:554;s:16:\"table/editor.css\";i:555;s:20:\"table/editor.min.css\";i:556;s:19:\"table/style-rtl.css\";i:557;s:23:\"table/style-rtl.min.css\";i:558;s:15:\"table/style.css\";i:559;s:19:\"table/style.min.css\";i:560;s:19:\"table/theme-rtl.css\";i:561;s:23:\"table/theme-rtl.min.css\";i:562;s:15:\"table/theme.css\";i:563;s:19:\"table/theme.min.css\";i:564;s:18:\"tabs/style-rtl.css\";i:565;s:22:\"tabs/style-rtl.min.css\";i:566;s:14:\"tabs/style.css\";i:567;s:18:\"tabs/style.min.css\";i:568;s:23:\"tag-cloud/style-rtl.css\";i:569;s:27:\"tag-cloud/style-rtl.min.css\";i:570;s:19:\"tag-cloud/style.css\";i:571;s:23:\"tag-cloud/style.min.css\";i:572;s:28:\"template-part/editor-rtl.css\";i:573;s:32:\"template-part/editor-rtl.min.css\";i:574;s:24:\"template-part/editor.css\";i:575;s:28:\"template-part/editor.min.css\";i:576;s:27:\"template-part/theme-rtl.css\";i:577;s:31:\"template-part/theme-rtl.min.css\";i:578;s:23:\"template-part/theme.css\";i:579;s:27:\"template-part/theme.min.css\";i:580;s:24:\"term-count/style-rtl.css\";i:581;s:28:\"term-count/style-rtl.min.css\";i:582;s:20:\"term-count/style.css\";i:583;s:24:\"term-count/style.min.css\";i:584;s:30:\"term-description/style-rtl.css\";i:585;s:34:\"term-description/style-rtl.min.css\";i:586;s:26:\"term-description/style.css\";i:587;s:30:\"term-description/style.min.css\";i:588;s:23:\"term-name/style-rtl.css\";i:589;s:27:\"term-name/style-rtl.min.css\";i:590;s:19:\"term-name/style.css\";i:591;s:23:\"term-name/style.min.css\";i:592;s:28:\"term-template/editor-rtl.css\";i:593;s:32:\"term-template/editor-rtl.min.css\";i:594;s:24:\"term-template/editor.css\";i:595;s:28:\"term-template/editor.min.css\";i:596;s:27:\"term-template/style-rtl.css\";i:597;s:31:\"term-template/style-rtl.min.css\";i:598;s:23:\"term-template/style.css\";i:599;s:27:\"term-template/style.min.css\";i:600;s:27:\"text-columns/editor-rtl.css\";i:601;s:31:\"text-columns/editor-rtl.min.css\";i:602;s:23:\"text-columns/editor.css\";i:603;s:27:\"text-columns/editor.min.css\";i:604;s:26:\"text-columns/style-rtl.css\";i:605;s:30:\"text-columns/style-rtl.min.css\";i:606;s:22:\"text-columns/style.css\";i:607;s:26:\"text-columns/style.min.css\";i:608;s:19:\"verse/style-rtl.css\";i:609;s:23:\"verse/style-rtl.min.css\";i:610;s:15:\"verse/style.css\";i:611;s:19:\"verse/style.min.css\";i:612;s:20:\"video/editor-rtl.css\";i:613;s:24:\"video/editor-rtl.min.css\";i:614;s:16:\"video/editor.css\";i:615;s:20:\"video/editor.min.css\";i:616;s:19:\"video/style-rtl.css\";i:617;s:23:\"video/style-rtl.min.css\";i:618;s:15:\"video/style.css\";i:619;s:19:\"video/style.min.css\";i:620;s:19:\"video/theme-rtl.css\";i:621;s:23:\"video/theme-rtl.min.css\";i:622;s:15:\"video/theme.css\";i:623;s:19:\"video/theme.min.css\";}}', 'on'),
('128', 'recovery_keys', 'a:0:{}', 'off'),
('129', 'theme_mods_twentytwentyfive', 'a:1:{s:18:\"custom_css_post_id\";i:-1;}', 'auto'),
('130', '_transient_wp_styles_for_blocks', 'a:2:{s:4:\"hash\";s:32:\"94289267f68ccac1fefa45915ab93347\";s:6:\"blocks\";a:9:{s:32:\"832dc2d864d79097d8b8b493ad93453b\";s:0:\"\";s:32:\"45d3e0c4afcbd8cf25cb1ba51abfb3d7\";s:46:\":root :where(.wp-block-icon svg){width: 24px;}\";s:32:\"feca6e996f694be2d29599793228e0d7\";s:0:\"\";s:32:\"5eef131663eddaf830554df656fc2968\";s:324:\":where(.wp-block-gallery.is-layout-flex){gap: var( --wp--style--gallery-gap-default, var( --gallery-block--gutter-size, var( --wp--style--block-gap, 0.5em ) ) );}:where(.wp-block-gallery.is-layout-grid){gap: var( --wp--style--gallery-gap-default, var( --gallery-block--gutter-size, var( --wp--style--block-gap, 0.5em ) ) );}\";s:32:\"c99c05932c6685777ec5b856698fcc7d\";s:118:\":where(.wp-block-latest-posts.is-layout-flex){gap: 1.25em;}:where(.wp-block-latest-posts.is-layout-grid){gap: 1.25em;}\";s:32:\"dec8d648f30b13caec8e61374591787d\";s:120:\":where(.wp-block-post-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-post-template.is-layout-grid){gap: 1.25em;}\";s:32:\"6c35533f7a92cce94808323603db9fc8\";s:120:\":where(.wp-block-term-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-term-template.is-layout-grid){gap: 1.25em;}\";s:32:\"6a0505cd5c78a87ed77570cda43c1132\";s:102:\":where(.wp-block-columns.is-layout-flex){gap: 2em;}:where(.wp-block-columns.is-layout-grid){gap: 2em;}\";s:32:\"25a66f156386551185570f72a9f7d44e\";s:69:\":root :where(.wp-block-pullquote){font-size: 1.5em;line-height: 1.6;}\";}}', 'on'),
('131', 'category_children', 'a:0:{}', 'auto'),
('134', 'muop_shopee_url', 'https://s.shopee.vn/5q8lDzjzRv', 'auto'),
('135', 'muop_tiktok_url', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ', 'auto'),
('136', 'muop_salary_rate', '8', 'auto'),
('137', 'theme_mods_muop-dang', 'a:1:{s:18:\"custom_css_post_id\";i:-1;}', 'auto'),
('150', 'muop_shopee_clicks_count', '93', 'auto'),
('151', 'muop_tiktok_clicks_count', '111', 'auto'),
('155', 'finished_updating_comment_type', '1', 'auto'),
('158', '_site_transient_update_core', 'O:8:\"stdClass\":4:{s:7:\"updates\";a:1:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:6:\"latest\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-7.1.3.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-7.1.3.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-7.1.3-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-7.1.3-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"7.1.3\";s:7:\"version\";s:5:\"7.1.3\";s:11:\"php_version\";s:3:\"7.4\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";}}s:12:\"last_checked\";i:1792423390;s:15:\"version_checked\";s:5:\"7.1.3\";s:12:\"translations\";a:0:{}}', 'off'),
('159', '_site_transient_update_plugins', 'O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1792423392;s:8:\"response\";a:0:{}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:2:{s:19:\"akismet/akismet.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:21:\"w.org/plugins/akismet\";s:4:\"slug\";s:7:\"akismet\";s:6:\"plugin\";s:19:\"akismet/akismet.php\";s:11:\"new_version\";s:5:\"5.7.2\";s:3:\"url\";s:38:\"https://wordpress.org/plugins/akismet/\";s:7:\"package\";s:56:\"https://downloads.wordpress.org/plugin/akismet.5.7.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:60:\"https://ps.w.org/akismet/assets/icon-256x256.png?rev=2818463\";s:2:\"1x\";s:60:\"https://ps.w.org/akismet/assets/icon-128x128.png?rev=2818463\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:63:\"https://ps.w.org/akismet/assets/banner-1544x500.png?rev=2900731\";s:2:\"1x\";s:62:\"https://ps.w.org/akismet/assets/banner-772x250.png?rev=2900731\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.8\";}s:9:\"hello.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:25:\"w.org/plugins/hello-dolly\";s:4:\"slug\";s:11:\"hello-dolly\";s:6:\"plugin\";s:9:\"hello.php\";s:11:\"new_version\";s:5:\"1.7.2\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/hello-dolly/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/hello-dolly.1.7.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-256x256.jpg?rev=2052855\";s:2:\"1x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-128x128.jpg?rev=2052855\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/hello-dolly/assets/banner-1544x500.jpg?rev=2645582\";s:2:\"1x\";s:66:\"https://ps.w.org/hello-dolly/assets/banner-772x250.jpg?rev=2052855\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.6\";}}s:7:\"checked\";a:2:{s:19:\"akismet/akismet.php\";s:5:\"5.7.2\";s:9:\"hello.php\";s:5:\"1.7.2\";}}', 'off'),
('162', '_site_transient_update_themes', 'O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1792423392;s:7:\"checked\";a:4:{s:9:\"muop-dang\";s:6:\"2.2.85\";s:16:\"twentytwentyfive\";s:3:\"1.5\";s:16:\"twentytwentyfour\";s:3:\"1.6\";s:17:\"twentytwentythree\";s:3:\"1.7\";}s:8:\"response\";a:0:{}s:9:\"no_update\";a:3:{s:16:\"twentytwentyfive\";a:6:{s:5:\"theme\";s:16:\"twentytwentyfive\";s:11:\"new_version\";s:3:\"1.5\";s:3:\"url\";s:46:\"https://wordpress.org/themes/twentytwentyfive/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/theme/twentytwentyfive.1.5.zip\";s:8:\"requires\";s:3:\"6.7\";s:12:\"requires_php\";s:3:\"7.2\";}s:16:\"twentytwentyfour\";a:6:{s:5:\"theme\";s:16:\"twentytwentyfour\";s:11:\"new_version\";s:3:\"1.6\";s:3:\"url\";s:46:\"https://wordpress.org/themes/twentytwentyfour/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/theme/twentytwentyfour.1.6.zip\";s:8:\"requires\";s:3:\"6.4\";s:12:\"requires_php\";s:3:\"7.0\";}s:17:\"twentytwentythree\";a:6:{s:5:\"theme\";s:17:\"twentytwentythree\";s:11:\"new_version\";s:3:\"1.7\";s:3:\"url\";s:47:\"https://wordpress.org/themes/twentytwentythree/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/theme/twentytwentythree.1.7.zip\";s:8:\"requires\";s:3:\"6.1\";s:12:\"requires_php\";s:3:\"5.6\";}}s:12:\"translations\";a:0:{}}', 'off'),
('194', '_transient_health-check-site-status-result', '{\"good\":14,\"recommended\":10,\"critical\":2}', 'on'),
('219', 'https_migration_required', '1', 'auto'),
('227', 'can_compress_scripts', '1', 'on'),
('416', 'the_loai_children', 'a:0:{}', 'auto'),
('467', '_site_transient_timeout_php_check_da775d00ae55849f14f81cf79fc50d46', '1792678084', 'off'),
('468', '_site_transient_php_check_da775d00ae55849f14f81cf79fc50d46', 'a:5:{s:19:\"recommended_version\";s:3:\"8.3\";s:15:\"minimum_version\";s:3:\"7.4\";s:12:\"is_supported\";b:0;s:9:\"is_secure\";b:1;s:13:\"is_acceptable\";b:1;}', 'off'),
('472', 'muop_page_about_slogan', 'Góc nhỏ của Mướp, nơi những câu chuyện được cất giữ bằng tất cả sự yêu thích.', 'auto'),
('473', 'muop_page_about', '<p style=\"margin-bottom: 16px; font-size: 15.5px;\">Xin chào mọi người, chào mừng mọi người đã ghé thăm <strong>Mướp Đắng Cũng Có Vị Ngọt</strong> — ngôi nhà nhỏ dành cho những tâm hồn yêu truyện! ♡</p>

<p style=\"margin-bottom: 16px;\">Mướp là một người rất thích đọc truyện, đặc biệt là những câu chuyện Zhihu, tổng tài, tình yêu, ngược tâm, ngược thân, rồi cả những bộ truyện có chút <em>hê hê</em> khiến người ta phải đỏ mặt, ôm gối cười một mình nữa. Đôi khi là một câu chuyện ngọt ngào khiến lòng vui vẻ cả ngày, đôi khi lại là một bộ truyện ngược đến đau lòng, đọc xong chỉ biết thở dài và tự nhủ: “Thôi, đọc thêm một chương nữa rồi ngủ…” Nhưng rồi chẳng biết bằng cách nào, một chương lại thành mười chương. 🥹💗</p>

<p style=\"margin-bottom: 24px;\">Vì yêu thích những câu chuyện ấy, Mướp luôn mong muốn có một góc nhỏ của riêng mình, nơi có thể lưu giữ những bộ truyện mà Mướp yêu thích, những câu chuyện đã từng khiến Mướp rung động, bật cười, tức giận hay rơi nước mắt. Và thế là <strong>Mướp Đắng Cũng Có Vị Ngọt</strong> ra đời!</p>

<h3 style=\"color: var(--primary-green); font-size: 18px; margin: 24px 0 12px; display: flex; align-items: center; gap: 8px;\">🌷 Một chút về ngôi nhà nhỏ này</h3>

<p style=\"margin-bottom: 16px;\">Trang web được chính tay Mướp lên ý tưởng và trực tiếp viết code, từ những dòng lệnh đầu tiên cho đến khi từng trang truyện dần được hoàn thiện. Mướp không phải tạo ra nơi này để làm điều gì quá lớn lao, chỉ đơn giản là muốn có một không gian riêng để đọc truyện, lưu giữ những câu chuyện mình yêu thích và chia sẻ những bản dịch của mình với mọi người.</p>

<p style=\"margin-bottom: 16px;\">Những bộ truyện được Mướp dịch và đăng tải tại đây đều xuất phát từ niềm yêu thích cá nhân. Mướp hy vọng rằng khi ghé thăm nơi này, mọi người sẽ tìm được một câu chuyện hợp gu, gặp được một nhân vật khiến mình rung động, hoặc đơn giản là có thêm một chút niềm vui sau những giờ học tập và làm việc mệt mỏi.</p>

<p style=\"margin-bottom: 24px;\">Mướp Đắng có thể hơi đắng một chút, nhưng Mướp tin rằng trong mỗi câu chuyện đều có một vị ngọt riêng. Có thể là vị ngọt của tình yêu, sự dịu dàng của những lần gặp gỡ, niềm hạnh phúc sau bao sóng gió, hoặc đôi khi là cảm giác thỏa mãn khi nhân vật phản diện cuối cùng cũng phải trả giá. Hihi! 🍬</p>

<h3 style=\"color: var(--primary-green); font-size: 18px; margin: 24px 0 12px; display: flex; align-items: center; gap: 8px;\">💌 Một vài lời nhắn gửi đến các bạn độc giả</h3>

<p style=\"margin-bottom: 14px;\">Mướp rất vui khi mọi người cùng đọc truyện, chia sẻ cảm nhận và trò chuyện với nhau. Tuy nhiên, để ngôi nhà nhỏ này luôn là một nơi dễ chịu, Mướp mong mọi người hãy cùng nhau giữ gìn một chút văn minh và sự tử tế nhé!</p>

<div style=\"background: var(--pastel-green, #f0fdf4); border: 1px solid var(--border-color, #dcfce7); border-radius: var(--radius-md, 8px); padding: 16px 20px; margin-bottom: 20px;\">
    <ul style=\"list-style: none; padding-left: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;\">
        <li style=\"display: flex; align-items: flex-start; gap: 8px;\">
            <span style=\"font-size: 16px; flex-shrink: 0;\">🌼</span>
            <div><strong>Bình luận văn minh:</strong> Hãy sử dụng ngôn từ lịch sự, tránh những lời lẽ khiếm nhã, xúc phạm hoặc công kích người khác.</div>
        </li>
        <li style=\"display: flex; align-items: flex-start; gap: 8px;\">
            <span style=\"font-size: 16px; flex-shrink: 0;\">🌼</span>
            <div><strong>Tôn trọng sự khác biệt:</strong> Mỗi người có một gu đọc truyện và cách cảm nhận riêng. Nếu không đồng tình, chúng mình có thể trao đổi nhẹ nhàng thay vì tranh cãi gay gắt.</div>
        </li>
        <li style=\"display: flex; align-items: flex-start; gap: 8px;\">
            <span style=\"font-size: 16px; flex-shrink: 0;\">🌼</span>
            <div><strong>Không gây tranh cãi không phù hợp:</strong> Hãy để phần bình luận là nơi chia sẻ niềm vui đọc truyện, không phải nơi để công kích, gây hấn hay tạo ra những cuộc tranh cãi không đáng có.</div>
        </li>
        <li style=\"display: flex; align-items: flex-start; gap: 8px;\">
            <span style=\"font-size: 16px; flex-shrink: 0;\">🌼</span>
            <div><strong>Cùng nhau giữ gìn góc nhỏ:</strong> Một lời khen, một chiếc bình luận dễ thương hay một chút cảm nhận chân thành cũng đủ khiến người viết và những độc giả khác vui vẻ hơn rất nhiều rồi đó!</div>
        </li>
    </ul>
</div>

<p style=\"margin-bottom: 24px;\">Mướp hy vọng mọi người sẽ cùng nhau tạo nên một cộng đồng đọc truyện thân thiện, thoải mái và đáng yêu. Chúng mình có thể cùng khóc vì một nam chính tồi tệ, cùng tức giận vì một nữ phụ đáng ghét, cùng hú hét vì một màn phát đường ngọt lịm, nhưng đừng vì thế mà làm tổn thương nhau nhé! 🫶🏻</p>

<h3 style=\"color: var(--primary-green); font-size: 18px; margin: 24px 0 12px; display: flex; align-items: center; gap: 8px;\">📮 Liên hệ với Mướp</h3>

<p style=\"margin-bottom: 12px;\">Nếu mọi người có thắc mắc về trang web, muốn góp ý, báo lỗi hoặc đơn giản là muốn trò chuyện với Mướp, mọi người có thể tìm Mướp qua Facebook:</p>

<div style=\"margin-bottom: 20px;\">
    <p style=\"margin-bottom: 0;\">
        <strong>Facebook:</strong> 
        <a href=\"https://www.facebook.com/profile.php?id=61594940649925&sk=directory_links&locale=vi_VN\" target=\"_blank\" rel=\"noopener noreferrer\" style=\"color: var(--primary-green); font-weight: 600; text-decoration: underline; margin-left: 4px;\">
            <i class=\"fa-brands fa-facebook\"></i> Mướp Đắng Cũng Có Vị Ngọt
        </a>
    </p>
</div>

<p style=\"margin-bottom: 16px;\">Mướp luôn trân trọng những góp ý chân thành và sự ủng hộ của mọi người. Vì đây là một trang web do Mướp tự tay xây dựng và chăm chút, nên mỗi sự động viên của các bạn đều là một nguồn động lực rất lớn để Mướp tiếp tục hoàn thiện ngôi nhà nhỏ này.</p>

<p style=\"margin-bottom: 16px;\">Cuối cùng, cảm ơn mọi người đã ghé thăm <strong>Mướp Đắng Cũng Có Vị Ngọt</strong>. Cảm ơn vì đã dành một chút thời gian cho những câu chuyện mà Mướp yêu thích, và hy vọng rằng từ đây, chúng ta sẽ có thêm thật nhiều kỷ niệm đẹp cùng những trang truyện.</p>

<p style=\"margin-bottom: 24px;\">Chúc mọi người đọc truyện thật vui, gặp được thật nhiều bộ truyện hợp gu và luôn tìm thấy một chút ngọt ngào giữa những ngày bình thường. ♡</p>

<div style=\"margin: 24px 0 20px; font-style: italic;\">
    <p style=\"margin-bottom: 4px;\">Thân thương,</p>
    <p style=\"font-weight: 700; color: var(--primary-green); font-size: 16px; margin: 0;\">— Mướp 🍈💚</p>
</div>

<div style=\"background: rgba(22, 163, 74, 0.08); border-left: 4px solid var(--primary-green); padding: 14px 18px; border-radius: 0 var(--radius-md, 8px) var(--radius-md, 8px) 0; font-style: italic; color: var(--text-main); margin-top: 24px; font-size: 15px;\">
    “Mỗi câu chuyện là một thế giới nhỏ, còn Mướp chỉ muốn gom góp những thế giới mình yêu thương vào một góc bình yên.”
</div>', 'auto'),
('519', 'muop_sensitive_words', 'a:209:{i:0;s:4:\"Dâm\";i:1;s:20:\"không muốn sống\";i:2;s:13:\"muốn chết\";i:3;s:11:\"tìm chết\";i:4;s:10:\"chết đi\";i:5;s:14:\"đi chết đi\";i:6;s:13:\"chết quách\";i:7;s:18:\"chết tiệt mày\";i:8;s:13:\"chết tiệt\";i:9;s:12:\"chết dẫm\";i:10;s:11:\"cái chết\";i:11;s:9:\"tử vong\";i:12;s:8:\"tử thi\";i:13;s:9:\"tự tử\";i:14;s:9:\"tự sát\";i:15;s:9:\"treo cổ\";i:16;s:12:\"nhảy lầu\";i:17;s:10:\"cắt cổ\";i:18;s:13:\"chặt đầu\";i:19;s:12:\"đâm chết\";i:20;s:12:\"bắn chết\";i:21;s:15:\"giết người\";i:22;s:13:\"giết chết\";i:23;s:6:\"giết\";i:24;s:10:\"sát hại\";i:25;s:9:\"mưu sát\";i:26;s:11:\"thảm sát\";i:27;s:10:\"hành hạ\";i:28;s:9:\"tra tấn\";i:29;s:11:\"xác chết\";i:30;s:9:\"thi thể\";i:31;s:10:\"vũng máu\";i:32;s:11:\"chảy máu\";i:33;s:11:\"đẫm máu\";i:34;s:7:\"máu me\";i:35;s:9:\"máu chó\";i:36;s:4:\"máu\";i:37;s:7:\"huyết\";i:38;s:12:\"chém chết\";i:39;s:11:\"đâm chém\";i:40;s:12:\"bắn giết\";i:41;s:13:\"đánh chết\";i:42;s:9:\"bóp cổ\";i:43;s:6:\"chết\";i:44;s:11:\"đệt mợ\";i:45;s:11:\"đệt mẹ\";i:46;s:11:\"đệt cụ\";i:47;s:6:\"đệt\";i:48;s:12:\"đệch mợ\";i:49;s:12:\"đệch mẹ\";i:50;s:7:\"đệch\";i:51;s:8:\"đù má\";i:52;s:9:\"đù mẹ\";i:53;s:4:\"đù\";i:54;s:10:\"đậu má\";i:55;s:11:\"đậu mợ\";i:56;s:14:\"đụ má mày\";i:57;s:9:\"đụ má\";i:58;s:15:\"đụ mẹ mày\";i:59;s:10:\"đụ mẹ\";i:60;s:9:\"đụ bà\";i:61;s:9:\"đụ con\";i:62;s:9:\"đụ cha\";i:63;s:5:\"đụ\";i:64;s:16:\"địt mẹ mày\";i:65;s:15:\"địt con mẹ\";i:66;s:11:\"địt mẹ\";i:67;s:16:\"địt cụ mày\";i:68;s:11:\"địt cụ\";i:69;s:10:\"địt bà\";i:70;s:10:\"địt cha\";i:71;s:6:\"địt\";i:72;s:12:\"con mẹ nó\";i:73;s:8:\"mẹ nó\";i:74;s:7:\"cha nó\";i:75;s:7:\"bà nó\";i:76;s:13:\"bà nội nó\";i:77;s:17:\"con đĩ mẹ nó\";i:78;s:10:\"đào mỏ\";i:79;s:12:\"tiện nhân\";i:80;s:18:\"đồ tiện nhân\";i:81;s:11:\"tiện nữ\";i:82;s:12:\"hồ ly tinh\";i:83;s:14:\"đĩ mẹ mày\";i:84;s:12:\"đĩ điếm\";i:85;s:9:\"đĩ chó\";i:86;s:9:\"đĩ mẹ\";i:87;s:4:\"đĩ\";i:88;s:7:\"điếm\";i:89;s:11:\"con điếm\";i:90;s:8:\"con đĩ\";i:91;s:4:\"phò\";i:92;s:8:\"con phò\";i:93;s:4:\"cave\";i:94;s:8:\"gái bao\";i:95;s:12:\"gái điếm\";i:96;s:5:\"bitch\";i:97;s:15:\"lồn mẹ mày\";i:98;s:5:\"lồn\";i:99;s:9:\"con cặc\";i:100;s:13:\"thằng cặc\";i:101;s:5:\"cặc\";i:102;s:6:\"buồi\";i:103;s:4:\"dái\";i:104;s:9:\"hòn dái\";i:105;s:10:\"lỗ đít\";i:106;s:10:\"lỗ nhị\";i:107;s:10:\"hậu môn\";i:108;s:8:\"lũ chó\";i:109;s:10:\"chó đẻ\";i:110;s:8:\"chó má\";i:111;s:10:\"đồ chó\";i:112;s:12:\"khốn nạn\";i:113;s:14:\"khốn khiếp\";i:114;s:12:\"đồ khốn\";i:115;s:14:\"vô liêm sỉ\";i:116;s:11:\"mẹ kiếp\";i:117;s:10:\"vãi lồn\";i:118;s:10:\"vãi cặc\";i:119;s:10:\"vãi đái\";i:120;s:14:\"vãi chưởng\";i:121;s:3:\"vcl\";i:122;s:3:\"vkl\";i:123;s:2:\"vl\";i:124;s:3:\"đm\";i:125;s:3:\"dcm\";i:126;s:4:\"clgt\";i:127;s:2:\"cl\";i:128;s:2:\"cc\";i:129;s:5:\"đéo\";i:130;s:4:\"dell\";i:131;s:6:\"đách\";i:132;s:7:\"đếch\";i:133;s:9:\"mẹ mày\";i:134;s:9:\"bố mày\";i:135;s:9:\"tiên sư\";i:136;s:8:\"tổ sư\";i:137;s:9:\"bố láo\";i:138;s:11:\"mất dạy\";i:139;s:9:\"súc sinh\";i:140;s:10:\"súc vật\";i:141;s:10:\"cầm thú\";i:142;s:12:\"bại hoại\";i:143;s:22:\"bộ phận sinh dục\";i:144;s:13:\"dương vật\";i:145;s:10:\"âm đạo\";i:146;s:8:\"âm hộ\";i:147;s:9:\"âm vật\";i:148;s:11:\"hột lẹt\";i:149;s:10:\"quy đầu\";i:150;s:14:\"bao quy đầu\";i:151;s:10:\"tinh hoàn\";i:152;s:11:\"tinh trùng\";i:153;s:11:\"tinh dịch\";i:154;s:10:\"thủ dâm\";i:155;s:13:\"tự sướng\";i:156;s:11:\"dâm đãng\";i:157;s:7:\"dâm ô\";i:158;s:10:\"dâm dục\";i:159;s:11:\"dâm loạn\";i:160;s:10:\"làm tình\";i:161;s:10:\"giao cấu\";i:162;s:21:\"quan hệ tình dục\";i:163;s:11:\"tình dục\";i:164;s:21:\"quan hệ xác thịt\";i:165;s:14:\"lên giường\";i:166;s:7:\"ân ái\";i:167;s:12:\"khỏa thân\";i:168;s:12:\"khoả thân\";i:169;s:13:\"ngực trần\";i:170;s:12:\"cởi trần\";i:171;s:11:\"lột đồ\";i:172;s:4:\"nude\";i:173;s:3:\"sex\";i:174;s:6:\"bú cu\";i:175;s:9:\"bú lồn\";i:176;s:12:\"liếm lồn\";i:177;s:9:\"bú mớm\";i:178;s:5:\"nện\";i:179;s:7:\"chịch\";i:180;s:6:\"xoạc\";i:181;s:10:\"bắn tinh\";i:182;s:11:\"hãm hiếp\";i:183;s:15:\"cưỡng hiếp\";i:184;s:11:\"hiếp dâm\";i:185;s:14:\"cưỡng bức\";i:186;s:11:\"bóp ngực\";i:187;s:8:\"bóp vú\";i:188;s:12:\"sờ soạng\";i:189;s:8:\"sờ mó\";i:190;s:12:\"dục vọng\";i:191;s:13:\"phê sướng\";i:192;s:12:\"lên đỉnh\";i:193;s:11:\"khiêu dâm\";i:194;s:7:\"cu bự\";i:195;s:7:\"chim to\";i:196;s:10:\"móc lốp\";i:197;s:11:\"xếp hình\";i:198;s:8:\"móc cua\";i:199;s:12:\"ma túy đá\";i:200;s:7:\"ma túy\";i:201;s:15:\"thuốc phiện\";i:202;s:8:\"cần sa\";i:203;s:6:\"heroin\";i:204;s:13:\"thuốc lắc\";i:205;s:11:\"đập đá\";i:206;s:10:\"ngáo đá\";i:207;s:9:\"hút cỏ\";i:208;s:8:\"ke kẹo\";}', 'auto'),
('523', 'muop_image_opt_stats', 'a:3:{s:11:\"total_files\";i:1;s:17:\"total_bytes_saved\";i:0;s:7:\"history\";a:1:{i:0;a:6:{s:4:\"file\";s:56:\"838403835_122102501955498021_6147343361033468070_n-1.jpg\";s:13:\"original_size\";i:177803;s:10:\"final_size\";i:177803;s:11:\"bytes_saved\";i:0;s:13:\"percent_saved\";d:0;s:4:\"time\";s:19:\"17/10/2026 13:29:51\";}}}', 'auto'),
('530', 'muop_db_localhost_migrated_v1', '1', 'auto'),
('587', '_site_transient_timeout_theme_roots', '1792425192', 'off'),
('588', '_site_transient_theme_roots', 'a:4:{s:9:\"muop-dang\";s:7:\"/themes\";s:16:\"twentytwentyfive\";s:7:\"/themes\";s:16:\"twentytwentyfour\";s:7:\"/themes\";s:17:\"twentytwentythree\";s:7:\"/themes\";}', 'off'),
('595', 'muop_tiktok_cooldown_minutes', '7', 'auto');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
('612', 'muop_shopee_url_2', 'https://s.shopee.vn/2ndLinkTest', 'auto'),
('619', '_site_transient_timeout_wp_theme_files_patterns-2a0907fd462eaf4699ceb5ebc1a3b3a6', '1793003165', 'off'),
('620', '_site_transient_wp_theme_files_patterns-2a0907fd462eaf4699ceb5ebc1a3b3a6', 'a:2:{s:7:\"version\";s:5:\"2.5.0\";s:8:\"patterns\";a:0:{}}', 'off');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_postmeta`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_postmeta`;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=464 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_postmeta`
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('1', '2', '_wp_page_template', 'default'),
('2', '3', '_wp_page_template', 'default'),
('3', '5', '_wp_page_template', 'page-truyen-moi.php'),
('4', '6', '_wp_page_template', 'page-truyen-hot.php'),
('5', '7', '_wp_page_template', 'page-truyen-full.php'),
('6', '8', '_wp_page_template', 'page-the-loai.php'),
('7', '9', '_wp_page_template', 'page-gioi-thieu.php'),
('8', '10', '_wp_page_template', 'page-dang-nhap.php'),
('9', '11', '_wp_page_template', 'page-dang-ky.php'),
('10', '12', '_wp_page_template', 'page-ho-so.php'),
('11', '13', '_wp_page_template', 'page-dang-truyen.php'),
('12', '14', '_wp_page_template', 'page-them-chuong.php'),
('13', '15', '_wp_page_template', 'page-thong-tin-dich-gia.php'),
('14', '16', '_wp_page_template', 'page-quan-ly-admin.php'),
('21', '18', '_chuong_truyen_id', '17'),
('22', '18', '_chuong_number', '1'),
('23', '18', '_chuong_views', '3048'),
('24', '19', '_chuong_truyen_id', '17'),
('25', '19', '_chuong_number', '2'),
('26', '19', '_chuong_views', '3048'),
('27', '20', '_chuong_truyen_id', '17'),
('28', '20', '_chuong_number', '3'),
('29', '20', '_chuong_views', '3048'),
('30', '21', '_chuong_truyen_id', '17'),
('31', '21', '_chuong_number', '4'),
('32', '21', '_chuong_views', '3048'),
('33', '22', '_chuong_truyen_id', '17'),
('34', '22', '_chuong_number', '5'),
('35', '22', '_chuong_views', '3048'),
('42', '24', '_chuong_truyen_id', '23'),
('43', '24', '_chuong_number', '1'),
('44', '24', '_chuong_views', '9634'),
('45', '25', '_chuong_truyen_id', '23'),
('46', '25', '_chuong_number', '2'),
('47', '25', '_chuong_views', '9634'),
('48', '26', '_chuong_truyen_id', '23'),
('49', '26', '_chuong_number', '3'),
('50', '26', '_chuong_views', '9633'),
('57', '28', '_chuong_truyen_id', '27'),
('58', '28', '_chuong_number', '1'),
('59', '28', '_chuong_views', '4900'),
('60', '29', '_chuong_truyen_id', '27'),
('61', '29', '_chuong_number', '2'),
('62', '29', '_chuong_views', '4900'),
('69', '31', '_chuong_truyen_id', '30'),
('70', '31', '_chuong_number', '1'),
('71', '31', '_chuong_views', '17102'),
('72', '32', '_chuong_truyen_id', '30'),
('73', '32', '_chuong_number', '2'),
('74', '32', '_chuong_views', '17102');
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('81', '34', '_chuong_truyen_id', '33'),
('82', '34', '_chuong_number', '1'),
('83', '34', '_chuong_views', '22555'),
('84', '35', '_chuong_truyen_id', '33'),
('85', '35', '_chuong_number', '2'),
('86', '35', '_chuong_views', '22552'),
('93', '37', '_chuong_truyen_id', '36'),
('94', '37', '_chuong_number', '1'),
('95', '37', '_chuong_views', '3700'),
('96', '38', '_chuong_truyen_id', '36'),
('97', '38', '_chuong_number', '2'),
('98', '38', '_chuong_views', '3700'),
('105', '40', '_chuong_truyen_id', '39'),
('106', '40', '_chuong_number', '1'),
('107', '40', '_chuong_views', '9901'),
('108', '41', '_chuong_truyen_id', '39'),
('109', '41', '_chuong_number', '2'),
('110', '41', '_chuong_views', '9901'),
('117', '43', '_chuong_truyen_id', '42'),
('118', '43', '_chuong_number', '1'),
('119', '43', '_chuong_views', '5600'),
('120', '44', '_chuong_truyen_id', '42'),
('121', '44', '_chuong_number', '2'),
('122', '44', '_chuong_views', '5600'),
('129', '46', '_chuong_truyen_id', '45'),
('130', '46', '_chuong_number', '1'),
('131', '46', '_chuong_views', '11200'),
('132', '47', '_chuong_truyen_id', '45'),
('133', '47', '_chuong_number', '2'),
('134', '47', '_chuong_views', '11200'),
('141', '49', '_chuong_truyen_id', '48'),
('142', '49', '_chuong_number', '1'),
('143', '49', '_chuong_views', '8350'),
('144', '50', '_chuong_truyen_id', '48'),
('145', '50', '_chuong_number', '2'),
('146', '50', '_chuong_views', '8350'),
('153', '52', '_chuong_truyen_id', '51'),
('154', '52', '_chuong_number', '1'),
('155', '52', '_chuong_views', '6800'),
('156', '53', '_chuong_truyen_id', '51'),
('157', '53', '_chuong_number', '2'),
('158', '53', '_chuong_views', '6800'),
('165', '55', '_chuong_truyen_id', '54'),
('166', '55', '_chuong_number', '1'),
('167', '55', '_chuong_views', '21501'),
('198', '64', '_chuong_truyen_id', '56'),
('199', '64', '_chuong_number', '1'),
('200', '64', '_chuong_views', '1'),
('201', '65', '_chuong_truyen_id', '56'),
('202', '65', '_chuong_number', '2');
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('203', '65', '_chuong_views', '2'),
('205', '66', '_wp_page_template', 'page-dieu-khoan.php'),
('206', '67', '_wp_page_template', 'page-chinh-sach-bao-mat.php'),
('207', '68', '_wp_page_template', 'page-lien-he.php'),
('221', '72', '_truyen_status', 'hoan_thanh'),
('222', '72', '_truyen_author_name', ''),
('223', '72', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('224', '72', '_truyen_views', '68'),
('225', '72', '_truyen_nominated', 'month'),
('226', '73', '_chuong_truyen_id', '72'),
('227', '73', '_chuong_number', '1'),
('228', '73', '_chuong_views', '27'),
('229', '74', '_chuong_truyen_id', '72'),
('230', '74', '_chuong_number', '2'),
('231', '74', '_chuong_views', '22'),
('232', '75', '_chuong_truyen_id', '72'),
('233', '75', '_chuong_number', '3'),
('234', '75', '_chuong_views', '9'),
('235', '76', '_chuong_truyen_id', '72'),
('236', '76', '_chuong_number', '4'),
('237', '76', '_chuong_views', '10'),
('238', '72', '_truyen_views_2026-10', '68'),
('239', '77', '_wp_page_template', 'page-team-dich.php'),
('240', '78', '_wp_page_template', 'page-tu-truyen.php'),
('241', '79', '_truyen_status', 'hoan_thanh'),
('242', '79', '_truyen_author_name', ''),
('243', '79', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('244', '79', '_truyen_views', '1'),
('245', '79', '_truyen_nominated', 'month'),
('246', '79', '_edit_lock', '1791791017:1'),
('247', '72', '_edit_lock', '1791791072:1'),
('248', '80', '_wp_attached_file', '2026/10/5e37cb52-4d9d-4658-83a4-74ea54f9d5df.png'),
('249', '80', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/5e37cb52-4d9d-4658-83a4-74ea54f9d5df.png\";s:8:\"filesize\";i:2403777;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"5e37cb52-4d9d-4658-83a4-74ea54f9d5df-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:130218;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"5e37cb52-4d9d-4658-83a4-74ea54f9d5df-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1180057;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"5e37cb52-4d9d-4658-83a4-74ea54f9d5df-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:51698;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"5e37cb52-4d9d-4658-83a4-74ea54f9d5df-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1456310;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"5e37cb52-4d9d-4658-83a4-74ea54f9d5df-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:253160;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"5e37cb52-4d9d-4658-83a4-74ea54f9d5df-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:48298;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('250', '79', '_thumbnail_id', '141'),
('251', '81', '_wp_attached_file', '2026/10/4aa25b17-62bf-448f-adc6-8e9a576d753f.png'),
('252', '81', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/4aa25b17-62bf-448f-adc6-8e9a576d753f.png\";s:8:\"filesize\";i:2499309;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"4aa25b17-62bf-448f-adc6-8e9a576d753f-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:138021;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"4aa25b17-62bf-448f-adc6-8e9a576d753f-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1238149;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"4aa25b17-62bf-448f-adc6-8e9a576d753f-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:54794;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"4aa25b17-62bf-448f-adc6-8e9a576d753f-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1526758;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"4aa25b17-62bf-448f-adc6-8e9a576d753f-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:267148;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"4aa25b17-62bf-448f-adc6-8e9a576d753f-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:50647;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('253', '72', '_thumbnail_id', '135'),
('254', '82', '_chuong_truyen_id', '79'),
('255', '82', '_chuong_number', '1'),
('256', '82', '_chuong_views', '0'),
('257', '83', '_chuong_truyen_id', '79'),
('258', '83', '_chuong_number', '2'),
('259', '83', '_chuong_views', '0'),
('260', '84', '_chuong_truyen_id', '79'),
('261', '84', '_chuong_number', '3'),
('262', '84', '_chuong_views', '1'),
('263', '85', '_chuong_truyen_id', '79'),
('264', '85', '_chuong_number', '4'),
('265', '85', '_chuong_views', '0'),
('266', '86', '_chuong_truyen_id', '79');
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('267', '86', '_chuong_number', '5'),
('268', '86', '_chuong_views', '0'),
('269', '87', '_chuong_truyen_id', '79'),
('270', '87', '_chuong_number', '6'),
('271', '87', '_chuong_views', '0'),
('272', '88', '_truyen_status', 'hoan_thanh'),
('273', '88', '_truyen_author_name', ''),
('274', '88', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('275', '88', '_truyen_views', '12'),
('276', '88', '_truyen_nominated', 'month'),
('277', '89', '_chuong_truyen_id', '88'),
('278', '89', '_chuong_number', '1'),
('279', '89', '_chuong_views', '3'),
('280', '90', '_chuong_truyen_id', '88'),
('281', '90', '_chuong_number', '2'),
('282', '90', '_chuong_views', '1'),
('283', '91', '_chuong_truyen_id', '88'),
('284', '91', '_chuong_number', '3'),
('285', '91', '_chuong_views', '2'),
('286', '92', '_chuong_truyen_id', '88'),
('287', '92', '_chuong_number', '4'),
('288', '92', '_chuong_views', '4'),
('289', '93', '_chuong_truyen_id', '88'),
('290', '93', '_chuong_number', '5'),
('291', '93', '_chuong_views', '2'),
('292', '88', '_truyen_views_2026-10', '12'),
('293', '94', '_wp_attached_file', '2026/10/8222e4aa-a30c-4188-a82c-99e9d4b7feb1.png'),
('294', '94', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/8222e4aa-a30c-4188-a82c-99e9d4b7feb1.png\";s:8:\"filesize\";i:2398189;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"8222e4aa-a30c-4188-a82c-99e9d4b7feb1-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:131554;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"8222e4aa-a30c-4188-a82c-99e9d4b7feb1-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1195685;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"8222e4aa-a30c-4188-a82c-99e9d4b7feb1-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:51342;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"8222e4aa-a30c-4188-a82c-99e9d4b7feb1-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1475880;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"8222e4aa-a30c-4188-a82c-99e9d4b7feb1-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:255467;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"8222e4aa-a30c-4188-a82c-99e9d4b7feb1-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:47967;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('295', '88', '_thumbnail_id', '137'),
('298', '96', '_truyen_status', 'hoan_thanh'),
('299', '96', '_truyen_author_name', ''),
('300', '96', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('301', '96', '_truyen_views', '1'),
('302', '96', '_truyen_nominated', 'none'),
('303', '97', '_wp_page_template', 'page-doi-mat-khau.php'),
('304', '98', '_wp_page_template', 'page-cai-dat-doc-truyen.php'),
('305', '99', '_wp_attached_file', '2026/10/cadbd1c7-1ac7-4603-b015-ae365ec69dc3.png'),
('306', '99', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/cadbd1c7-1ac7-4603-b015-ae365ec69dc3.png\";s:8:\"filesize\";i:2992338;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"cadbd1c7-1ac7-4603-b015-ae365ec69dc3-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:157906;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"cadbd1c7-1ac7-4603-b015-ae365ec69dc3-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1487850;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"cadbd1c7-1ac7-4603-b015-ae365ec69dc3-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:60945;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"cadbd1c7-1ac7-4603-b015-ae365ec69dc3-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1835059;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"cadbd1c7-1ac7-4603-b015-ae365ec69dc3-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:311239;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"cadbd1c7-1ac7-4603-b015-ae365ec69dc3-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:56256;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('307', '96', '_thumbnail_id', '140'),
('308', '100', '_chuong_truyen_id', '96'),
('309', '100', '_chuong_number', '1'),
('310', '100', '_chuong_views', '1'),
('311', '101', '_chuong_truyen_id', '96'),
('312', '101', '_chuong_number', '2'),
('313', '101', '_chuong_views', '0'),
('314', '102', '_chuong_truyen_id', '96'),
('315', '102', '_chuong_number', '3'),
('316', '102', '_chuong_views', '0'),
('317', '103', '_chuong_truyen_id', '96'),
('318', '103', '_chuong_number', '4');
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('319', '103', '_chuong_views', '0'),
('320', '96', '_truyen_dich_gia', 'Mướp Đắng Cũng Có Vị Ngọt'),
('321', '88', '_truyen_dich_gia', 'Mướp Đắng Cũng Có Vị Ngọt'),
('322', '79', '_truyen_dich_gia', 'Mướp Đắng Cũng Có Vị Ngọt'),
('323', '72', '_truyen_dich_gia', 'Mướp Đắng Cũng Có Vị Ngọt'),
('324', '104', '_truyen_status', 'hoan_thanh'),
('325', '104', '_truyen_author_name', ''),
('326', '104', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('327', '104', '_truyen_views', '45'),
('328', '104', '_truyen_nominated', 'none'),
('329', '105', '_wp_attached_file', '2026/10/51ea6352-021b-4dbe-8ad7-bfac0f71ffec.png'),
('330', '105', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/51ea6352-021b-4dbe-8ad7-bfac0f71ffec.png\";s:8:\"filesize\";i:2308884;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"51ea6352-021b-4dbe-8ad7-bfac0f71ffec-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:134616;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"51ea6352-021b-4dbe-8ad7-bfac0f71ffec-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1160755;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"51ea6352-021b-4dbe-8ad7-bfac0f71ffec-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:54243;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"51ea6352-021b-4dbe-8ad7-bfac0f71ffec-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1427806;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"51ea6352-021b-4dbe-8ad7-bfac0f71ffec-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:257499;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"51ea6352-021b-4dbe-8ad7-bfac0f71ffec-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:50524;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('331', '104', '_thumbnail_id', '136'),
('332', '106', '_chuong_truyen_id', '104'),
('333', '106', '_chuong_number', '1'),
('334', '106', '_chuong_views', '15'),
('335', '107', '_chuong_truyen_id', '104'),
('336', '107', '_chuong_number', '2'),
('337', '107', '_chuong_views', '9'),
('338', '108', '_chuong_truyen_id', '104'),
('339', '108', '_chuong_number', '3'),
('340', '108', '_chuong_views', '10'),
('341', '109', '_chuong_truyen_id', '104'),
('342', '109', '_chuong_number', '4'),
('343', '109', '_chuong_views', '6'),
('344', '110', '_chuong_truyen_id', '104'),
('345', '110', '_chuong_number', '5'),
('346', '110', '_chuong_views', '5'),
('347', '104', '_truyen_views_2026-10', '45'),
('348', '96', '_truyen_views_2026-10', '1'),
('349', '79', '_truyen_views_2026-10', '1'),
('350', '111', '_truyen_status', 'hoan_thanh'),
('351', '111', '_truyen_author_name', ''),
('352', '111', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('353', '111', '_truyen_views', '6'),
('354', '111', '_truyen_nominated', 'none'),
('355', '112', '_wp_attached_file', '2026/10/b8c752fc-8871-444f-8fd1-15250aa2c927.png'),
('356', '112', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/b8c752fc-8871-444f-8fd1-15250aa2c927.png\";s:8:\"filesize\";i:2300754;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"b8c752fc-8871-444f-8fd1-15250aa2c927-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:133636;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"b8c752fc-8871-444f-8fd1-15250aa2c927-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1199930;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"b8c752fc-8871-444f-8fd1-15250aa2c927-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:55072;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"b8c752fc-8871-444f-8fd1-15250aa2c927-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1475569;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"b8c752fc-8871-444f-8fd1-15250aa2c927-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:262265;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"b8c752fc-8871-444f-8fd1-15250aa2c927-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:49530;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('357', '111', '_thumbnail_id', '139'),
('378', '125', '_chuong_truyen_id', '111'),
('379', '125', '_chuong_number', '1'),
('380', '125', '_chuong_views', '3'),
('381', '126', '_chuong_truyen_id', '111'),
('382', '126', '_chuong_number', '2'),
('383', '126', '_chuong_views', '1'),
('384', '127', '_chuong_truyen_id', '111'),
('385', '127', '_chuong_number', '3'),
('386', '127', '_chuong_views', '1'),
('387', '128', '_chuong_truyen_id', '111'),
('388', '128', '_chuong_number', '4');
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('389', '128', '_chuong_views', '1'),
('390', '111', '_truyen_views_2026-10', '6'),
('391', '111', '_muop_story_favorite_count', '0'),
('392', '111', '_muop_story_followers', 'a:0:{}'),
('393', '129', '_truyen_status', 'hoan_thanh'),
('394', '129', '_truyen_author_name', ''),
('395', '129', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('396', '129', '_truyen_views', '1'),
('397', '129', '_truyen_nominated', 'none'),
('398', '130', '_wp_attached_file', '2026/10/8e612af0-ac62-461e-993f-7fdab32ada67.png'),
('399', '130', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:48:\"2026/10/8e612af0-ac62-461e-993f-7fdab32ada67.png\";s:8:\"filesize\";i:2204081;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:48:\"8e612af0-ac62-461e-993f-7fdab32ada67-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:124088;}s:5:\"large\";a:5:{s:4:\"file\";s:49:\"8e612af0-ac62-461e-993f-7fdab32ada67-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1081822;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:48:\"8e612af0-ac62-461e-993f-7fdab32ada67-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:51078;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:49:\"8e612af0-ac62-461e-993f-7fdab32ada67-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1337449;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:48:\"8e612af0-ac62-461e-993f-7fdab32ada67-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:240459;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:48:\"8e612af0-ac62-461e-993f-7fdab32ada67-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:47134;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('400', '129', '_thumbnail_id', '138'),
('401', '131', '_chuong_truyen_id', '129'),
('402', '131', '_chuong_number', '1'),
('403', '131', '_chuong_views', '1'),
('404', '132', '_chuong_truyen_id', '129'),
('405', '132', '_chuong_number', '2'),
('406', '132', '_chuong_views', '0'),
('407', '133', '_chuong_truyen_id', '129'),
('408', '133', '_chuong_number', '3'),
('409', '133', '_chuong_views', '0'),
('410', '134', '_chuong_truyen_id', '129'),
('411', '134', '_chuong_number', '4'),
('412', '134', '_chuong_views', '0'),
('413', '129', '_truyen_views_2026-10', '1'),
('414', '135', '_wp_attached_file', '2026/10/1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1.jpg'),
('415', '135', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1.jpg\";s:8:\"filesize\";i:774739;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:21280;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:148816;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:9270;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:176801;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:269929;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:403857;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:38889;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8509;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('416', '136', '_wp_attached_file', '2026/10/1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75.jpg'),
('417', '136', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75.jpg\";s:8:\"filesize\";i:688219;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:21399;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:135321;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:9814;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:160052;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:241093;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:357346;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:38668;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8873;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('418', '129', '_muop_story_favorite_count', '1'),
('419', '137', '_wp_attached_file', '2026/10/1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0.jpg'),
('420', '137', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0.jpg\";s:8:\"filesize\";i:744791;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:20156;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:143370;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8521;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:170223;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:259744;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:388184;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:37180;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8007;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('421', '138', '_wp_attached_file', '2026/10/1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c.jpg'),
('422', '138', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c.jpg\";s:8:\"filesize\";i:707204;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:20065;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:132410;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:9082;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:157035;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:238761;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:354980;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:36163;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8433;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('423', '139', '_wp_attached_file', '2026/10/1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a.jpg'),
('424', '139', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a.jpg\";s:8:\"filesize\";i:827666;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:21166;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:147319;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:9482;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:175416;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:266263;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:396932;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:39456;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8671;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('425', '140', '_wp_attached_file', '2026/10/1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69.jpg'),
('426', '140', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69.jpg\";s:8:\"filesize\";i:1131044;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:26156;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:201002;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:11039;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:239777;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:366543;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:547512;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:49945;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:10019;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('427', '141', '_wp_attached_file', '2026/10/1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2.jpg'),
('428', '141', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1707;s:6:\"height\";i:2560;s:4:\"file\";s:98:\"2026/10/1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2.jpg\";s:8:\"filesize\";i:754728;s:5:\"sizes\";a:8:{s:6:\"medium\";a:5:{s:4:\"file\";s:98:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-200x300.jpg\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:20080;}s:5:\"large\";a:5:{s:4:\"file\";s:99:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-683x1024.jpg\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:139420;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:98:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8627;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:99:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-768x1152.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:165799;}s:9:\"1536x1536\";a:5:{s:4:\"file\";s:100:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-1024x1536.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:252942;}s:9:\"2048x2048\";a:5:{s:4:\"file\";s:100:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-1366x2048.jpg\";s:5:\"width\";i:1366;s:6:\"height\";i:2048;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:380506;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:98:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-300x420.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:36738;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:98:\"1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2-120x168.jpg\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:8120;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('429', '142', '_truyen_status', 'hoan_thanh'),
('430', '142', '_truyen_author_name', ''),
('431', '142', '_truyen_team', 'Mướp Đắng Cũng Có Vị Ngọt'),
('432', '142', '_truyen_views', '0'),
('433', '142', '_truyen_nominated', 'none'),
('434', '143', '_wp_attached_file', '2026/10/bia.png'),
('435', '143', '_wp_attachment_metadata', 'a:6:{s:5:\"width\";i:1024;s:6:\"height\";i:1536;s:4:\"file\";s:15:\"2026/10/bia.png\";s:8:\"filesize\";i:3443695;s:5:\"sizes\";a:6:{s:6:\"medium\";a:5:{s:4:\"file\";s:15:\"bia-200x300.png\";s:5:\"width\";i:200;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:128817;}s:5:\"large\";a:5:{s:4:\"file\";s:16:\"bia-683x1024.png\";s:5:\"width\";i:683;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1113296;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:15:\"bia-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:53430;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:16:\"bia-768x1152.png\";s:5:\"width\";i:768;s:6:\"height\";i:1152;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:1373336;}s:10:\"muop-cover\";a:5:{s:4:\"file\";s:15:\"bia-300x420.png\";s:5:\"width\";i:300;s:6:\"height\";i:420;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:249209;}s:16:\"muop-cover-thumb\";a:5:{s:4:\"file\";s:15:\"bia-120x168.png\";s:5:\"width\";i:120;s:6:\"height\";i:168;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:49402;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),
('436', '142', '_thumbnail_id', '143'),
('437', '144', '_chuong_truyen_id', '142'),
('438', '144', '_chuong_number', '1');
INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
('439', '144', '_chuong_views', '0'),
('440', '145', '_chuong_truyen_id', '142'),
('441', '145', '_chuong_number', '2'),
('442', '145', '_chuong_views', '0'),
('443', '146', '_chuong_truyen_id', '142'),
('444', '146', '_chuong_number', '3'),
('445', '146', '_chuong_views', '0'),
('446', '147', '_chuong_truyen_id', '142'),
('447', '147', '_chuong_number', '4'),
('448', '147', '_chuong_views', '0'),
('453', '142', '_wp_old_date', '2026-10-19'),
('463', '129', '_wp_old_date', '2026-10-20');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_posts`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_posts`;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(255) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `type_status_author` (`post_type`,`post_status`,`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_posts`
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
('1', '1', '2026-10-07 18:52:36', '2026-10-07 11:52:36', '<!-- wp:paragraph -->
<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>
<!-- /wp:paragraph -->', 'Hello world!', '', 'publish', 'open', 'open', '', 'hello-world', '', '', '2026-10-07 18:52:36', '2026-10-07 11:52:36', '', '0', 'https://muopdangcungcovingot.io.vn/?p=1', '0', 'post', '', '0'),
('2', '1', '2026-10-07 18:52:36', '2026-10-07 11:52:36', '<!-- wp:paragraph -->
<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class=\"wp-block-quote\">
<!-- wp:paragraph -->
<p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</p>
<!-- /wp:paragraph -->
</blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>...or something like this:</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class=\"wp-block-quote\">
<!-- wp:paragraph -->
<p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p>
<!-- /wp:paragraph -->
</blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>As a new WordPress user, you should go to <a href=\"https://muopdangcungcovingot.io.vn/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>
<!-- /wp:paragraph -->', 'Sample Page', '', 'publish', 'closed', 'open', '', 'sample-page', '', '', '2026-10-07 18:52:36', '2026-10-07 11:52:36', '', '0', 'https://muopdangcungcovingot.io.vn/?page_id=2', '0', 'page', '', '0'),
('3', '1', '2026-10-07 18:52:36', '2026-10-07 11:52:36', '<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Who we are</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Our website address is: https://muopdangcungcovingot.io.vn.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Comments</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Media</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Cookies</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Embedded content from other websites</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Who we share your data with</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you request a password reset, your IP address will be included in the reset email.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">How long we retain your data</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">What rights you have over your data</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class=\"wp-block-heading\">Where your data is sent</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Visitor comments may be checked through an automated spam detection service.</p>
<!-- /wp:paragraph -->
', 'Privacy Policy', '', 'draft', 'closed', 'open', '', 'privacy-policy', '', '', '2026-10-07 18:52:36', '2026-10-07 11:52:36', '', '0', 'https://muopdangcungcovingot.io.vn/?page_id=3', '0', 'page', '', '0'),
('4', '0', '2026-10-07 18:56:02', '2026-10-07 11:56:02', '<!-- wp:page-list /-->', 'Navigation', '', 'publish', 'closed', 'closed', '', 'navigation', '', '', '2026-10-07 18:56:02', '2026-10-07 11:56:02', '', '0', 'https://muopdangcungcovingot.io.vn/navigation/', '0', 'wp_navigation', '', '0'),
('5', '0', '2026-10-07 19:06:54', '2026-10-07 12:06:54', 'Trang Truyện Mới Cập Nhật', 'Truyện Mới Cập Nhật', '', 'publish', 'closed', 'closed', '', 'truyen-moi', '', '', '2026-10-07 19:06:54', '2026-10-07 12:06:54', '', '0', 'https://muopdangcungcovingot.io.vn/truyen-moi/', '0', 'page', '', '0'),
('6', '0', '2026-10-07 19:06:54', '2026-10-07 12:06:54', 'Trang Truyện Hot', 'Truyện Hot', '', 'publish', 'closed', 'closed', '', 'truyen-hot', '', '', '2026-10-07 19:06:54', '2026-10-07 12:06:54', '', '0', 'https://muopdangcungcovingot.io.vn/truyen-hot/', '0', 'page', '', '0'),
('7', '0', '2026-10-07 19:06:54', '2026-10-07 12:06:54', 'Trang Truyện Full', 'Truyện Full', '', 'publish', 'closed', 'closed', '', 'truyen-full', '', '', '2026-10-07 19:06:54', '2026-10-07 12:06:54', '', '0', 'https://muopdangcungcovingot.io.vn/truyen-full/', '0', 'page', '', '0'),
('8', '0', '2026-10-07 19:06:54', '2026-10-07 12:06:54', 'Trang Thể Loại Truyện', 'Thể Loại Truyện', '', 'publish', 'closed', 'closed', '', 'the-loai', '', '', '2026-10-07 19:06:54', '2026-10-07 12:06:54', '', '0', 'https://muopdangcungcovingot.io.vn/the-loai/', '0', 'page', '', '0'),
('9', '0', '2026-10-07 19:06:54', '2026-10-07 12:06:54', 'Trang Giới Thiệu', 'Giới Thiệu', '', 'publish', 'closed', 'closed', '', 'gioi-thieu', '', '', '2026-10-07 19:06:54', '2026-10-07 12:06:54', '', '0', 'https://muopdangcungcovingot.io.vn/gioi-thieu/', '0', 'page', '', '0'),
('10', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Đăng Nhập', 'Đăng Nhập', '', 'publish', 'closed', 'closed', '', 'dang-nhap', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/dang-nhap/', '0', 'page', '', '0'),
('11', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Đăng Ký', 'Đăng Ký', '', 'publish', 'closed', 'closed', '', 'dang-ky', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/dang-ky/', '0', 'page', '', '0'),
('12', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Hồ Sơ Cá Nhân', 'Hồ Sơ Cá Nhân', '', 'publish', 'closed', 'closed', '', 'ho-so', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/ho-so/', '0', 'page', '', '0'),
('13', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Đăng Truyện Mới', 'Đăng Truyện Mới', '', 'publish', 'closed', 'closed', '', 'dang-truyen', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/dang-truyen/', '0', 'page', '', '0'),
('14', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Thêm Chương Mới', 'Thêm Chương Mới', '', 'publish', 'closed', 'closed', '', 'them-chuong', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/them-chuong/', '0', 'page', '', '0'),
('15', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Kênh Dịch Giả', 'Kênh Dịch Giả', '', 'publish', 'closed', 'closed', '', 'thong-tin-dich-gia', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/thong-tin-dich-gia/', '0', 'page', '', '0'),
('16', '0', '2026-10-07 19:06:55', '2026-10-07 12:06:55', 'Trang Quản Trị Hệ Thống', 'Quản Trị Hệ Thống', '', 'publish', 'closed', 'closed', '', 'quan-ly-admin', '', '', '2026-10-07 19:06:55', '2026-10-07 12:06:55', '', '0', 'https://muopdangcungcovingot.io.vn/quan-ly-admin/', '0', 'page', '', '0'),
('18', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Trời mưa rả rích suốt ba ngày ở Kinh Đô.</p><p>Hứa Nhược Ngưng đặt tách trà lài ấm nóng lên bàn trà, ngón tay nhẹ nhàng đẩy tờ giấy thỏa thuận ly hôn đã ký sẵn tên mình sang phía người đàn ông ngồi đối diện.</p><p>\'Lục Cảnh Hoài, chúng ta dừng lại ở đây thôi. Ba năm qua, cảm ơn anh đã chăm sóc.\'</p><p>Người đàn ông mặc âu phục may đo màu xám tro hơi khựng lại. Anh ngẩng đầu lên từ tập tài liệu tài chính, ánh mắt thâm trầm như đáy hồ không một gợn sóng: \'Em chắc chắn? Em biết rõ rời khỏi Lục gia, em sẽ không có được bất cứ thứ gì ngoài số tiền bồi thường.\'</p><p>\'Tôi chắc chắn.\' Nhược Ngưng mỉm cười nhẹ nhõm, nụ cười mà ba năm qua anh chưa từng thấy trên gương mặt cô.</p>', 'Quyết định ký đơn ly hôn', '', 'publish', 'open', 'closed', '', 'chuong-1-quyet-dinh-ky-don-ly-hon', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-quyet-dinh-ky-don-ly-hon/', '0', 'chuong', '', '0'),
('19', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Hai tháng sau khi rời khỏi Lục gia.</p><p>Khách sạn Đế Đô lộng lẫy ánh đèn pha lê. Hôm nay là buổi tiệc thời trang lớn nhất năm của tập đoàn Vạn Tinh. Nhược Ngưng xuất hiện trong chiếc đầm dạ hội satin màu xanh bơ ôm trọn đường cong mềm mại, mái tóc uốn sóng buông lơi quyến rũ.</p><p>Khi cô bước lên bục nhận giải Nhà thiết kế của năm, toàn bộ quan khách dưới khán đài đều sững sờ. Đặc biệt là Lục Cảnh Hoài ở hàng ghế VIP.</p><p>Ly rượu vang trên tay anh suýt chút nữa rơi xuống thảm. Cô gái nhỏ luôn e thẹn ở nhà nấu canh cho anh mỗi tối, hóa ra lại là nhà thiết kế ẩn danh huyền thoại mà giới thời trang quốc tế săn đón bấy lâu!</p>', 'Màn gặp lại bất ngờ tại yến tiệc', '', 'publish', 'open', 'closed', '', 'chuong-2-man-gap-lai-bat-ngo-tai-yen-tiec', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-man-gap-lai-bat-ngo-tai-yen-tiec/', '0', 'chuong', '', '0'),
('20', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>\'Em tránh mặt tôi?\' Giọng nói trầm khàn quen thuộc vang lên sau lưng khi Nhược Ngưng vừa bước ra hành lang đón gió.</p><p>Cô chưa kịp quay lại thì một cánh tay rắn rỏi đã áp sát thành tường, chặn đứng lối đi của cô. Mùi hương gỗ tuyết tùng quen thuộc bao trùm lấy không gian.</p><p>\'Lục tổng, ở đây có rất nhiều phóng viên, xin tự trọng.\' Cô bình tĩnh ngước nhìn anh.</p><p>Lục Cảnh Hoài cúi thấp đầu, hơi thở phả qua vành tai mẫn cảm của cô: \'Tự trọng? Nhược Ngưng, hai tháng qua tôi chưa từng ngủ trọn một đêm nào. Em nói xem, em đã bỏ bùa gì vào căn nhà của tôi?\'</p>', 'Góc tối hành lang và lời thầm thì', '', 'publish', 'open', 'closed', '', 'chuong-3-goc-toi-hanh-lang-va-loi-tham-thi', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3-goc-toi-hanh-lang-va-loi-tham-thi/', '0', 'chuong', '', '0'),
('21', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Nhược Ngưng đẩy nhẹ ngực áo của anh ra: \'Lục tổng đùa sao? Bạch nguyệt quang của anh đã về nước rồi, anh nên dành sự quan tâm này cho cô ấy mới phải.\'</p><p>Lục Cảnh Hoài bật cười cay đắng: \'Bạch nguyệt quang nào chứ? Người tôi luôn tìm kiếm từ 10 năm trước ở trại hè năm đó... chính là người đang đứng trước mặt tôi bây giờ.\'</p><p>Đôi mắt Nhược Ngưng khẽ chớp động. Ký ức về mùa hè năm ấy đột ngột ùa về...</p>', 'Trái tim bắt đầu tan chảy', '', 'publish', 'open', 'closed', '', 'chuong-4-trai-tim-bat-dau-tan-chay', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-4-trai-tim-bat-dau-tan-chay/', '0', 'chuong', '', '0'),
('22', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Mưa lại bắt đầu rơi ngoài ban công.</p><p>Lục Cảnh Hoài nắm lấy bàn tay mảnh khảnh của Nhược Ngưng, áp lên lồng ngực đang đập liên hồi của mình: \'Nếu trước đây tôi khiến em cảm thấy như một trái mướp đắng chát, thì từ hôm nay, tôi sẽ chứng minh cho em thấy... mướp đắng của em có vị ngọt lịm như thế nào.\'</p><p>Cô nhìn vào ánh mắt chân thành chưa từng có của anh, bờ môi khẽ cong lên một nụ cười ấm áp.</p>', 'Lời thề ngọt ngào dưới mưa', '', 'publish', 'open', 'closed', '', 'chuong-5-loi-the-ngot-ngao-duoi-mua', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-5-loi-the-ngot-ngao-duoi-mua/', '0', 'chuong', '', '0'),
('24', '3', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Cảm giác nước biển lạnh buốt tràn vào phổi tan biến trong tích tắc.</p><p>Tôi bừng tỉnh, đập vào mắt là trần nhà màu xanh nhạt quen thuộc của căn phòng ngủ năm 18 tuổi. Trên bàn học, cuốn lịch bàn ghi rõ ngày 15 tháng 5 năm 2018.</p><p>\'Mình... thật sự đã trọng sinh rồi sao?\' Tôi sờ lên gương mặt mịn màng không tì vết của mình, nước mắt tuôn rơi nhưng miệng lại mỉm cười sắc lạnh.</p>', 'Tỉnh lại trong cơn ác mộng', '', 'publish', 'open', 'closed', '', 'chuong-1-tinh-lai-trong-con-ac-mong', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-tinh-lai-trong-con-ac-mong/', '0', 'chuong', '', '0'),
('25', '3', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Buổi chiều hôm ấy, điện thoại tôi rung lên thông báo tin nhắn từ Cố Gia Thành: \'Nhiên Nhiên, em tới kho thể dục tầng 3 giúp anh lấy hồ sơ nhé, anh đang bận họp.\'</p><p>Tin nhắn y hệt như kiếp trước. Lần đó, tôi chạy tới và bị nhốt trong đám cháy, còn anh ta và em gái nuôi Lâm Vi Vi lại cùng nhau ăn kem ở quán cà phê đối diện.</p><p>Tôi khẽ nhếch môi, bấm chuyển tiếp tin nhắn ấy cho chính Lâm Vi Vi kèm lời nhắn: \'Chị bận rồi, em tới giúp anh Gia Thành nhé!\'</p>', 'Kế hoạch phản đòn hoàn hảo', '', 'publish', 'open', 'closed', '', 'chuong-2-ke-hoach-phan-don-hoan-hao', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-ke-hoach-phan-don-hoan-hao/', '0', 'chuong', '', '0'),
('26', '3', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Khi tiếng còi xe cứu hỏa rú vang khắp sân trường, tôi đứng từ ban công tầng 5 nhìn xuống.</p><p>Lâm Vi Vi ho sặc sụa được lính cứu hỏa đưa ra ngoài, chiếc váy hiệu đắt tiền lấm lem bùn đất tro tàn. Cố Gia Thành hớt hải chạy tới, nhìn thấy tôi đứng lành lặn xinh đẹp bên cạnh thì sắc mặt tái nhợt như xác ướp.</p><p>\'Chào Cố thiếu, trò chơi bây giờ mới chỉ bắt đầu thôi!\'</p>', 'Kẻ gieo gió gặt bão', '', 'publish', 'open', 'closed', '', 'chuong-3-ke-gieo-gio-gat-bao', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3-ke-gieo-gio-gat-bao/', '0', 'chuong', '', '0'),
('28', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Giáo sư Cố Thần ngồi đối diện tôi trong quán cà phê sách, áo sơ mi trắng phẳng phiu cài cúc tận cổ.</p><p>\'Mục đích xem mắt của tôi là kết hôn để ổn định gia đình. Nếu cô Chu không phản đối, tuần sau chúng ta có thể đăng ký.\'</p><p>Tôi ngơ ngác nhìn anh: \'Nhanh vậy sao giáo sư Cố?\'</p><p>\'Hiệu quả là tiêu chuẩn số một trong mọi thí nghiệm vật lý của tôi.\' Anh trả lời tỉnh queo.</p>', 'Cuộc xem mắt định mệnh', '', 'publish', 'open', 'closed', '', 'chuong-1-cuoc-xem-mat-dinh-menh', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-cuoc-xem-mat-dinh-menh/', '0', 'chuong', '', '0'),
('29', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Tôi ôm gối định ra sô-pha ngủ để giữ khoảng cách, nhưng chưa kịp bước chân thì cánh cửa phòng ngủ đã bị khóa trái.</p><p>Giáo sư Cố tháo mắt kính gọng vàng, ánh mắt không còn vẻ nghiêm cẩn thường ngày: \'Chu phu nhân, theo luật hôn nhân và quy tắc xác suất, chúng ta nên ngủ chung giường.\'</p>', 'Đêm tân hôn bất ngờ', '', 'publish', 'open', 'closed', '', 'chuong-2-dem-tan-hon-bat-ngo', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-dem-tan-hon-bat-ngo/', '0', 'chuong', '', '0'),
('31', '3', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Vừa mở mắt ra, một nha hoàn đã khóc lóc: \'Tiểu thư, Vương gia sắp hủy hôn rồi, người đừng nhảy giếng nữa!\'</p><p>Tôi ngồi dậy, phủi bụi trên váy: \'Hủy hôn thật à? Thế của hồi môn có trả lại đủ không?\'</p><p>Nha hoàn ngẩn ngơ gật đầu: \'Dạ trả đủ...\'</p><p>\'Tốt lắm! Thu dọn hành lý, chúng ta về quê mua đất xây trang trại!\'</p>', 'Tỉnh dậy ở hậu viện', '', 'publish', 'open', 'closed', '', 'chuong-1-tinh-day-o-hau-vien', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-tinh-day-o-hau-vien/', '0', 'chuong', '', '0'),
('32', '3', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Nửa năm sau, trang trại nuôi cá của tôi nức tiếng cả vùng.</p><p>Một hôm, một thiếu niên tuấn tú tuấn mã xuất hiện trước cổng tre, quỳ một gối trước đầm sen: \'Nàng cho ta làm công nhân chăm cá được không? Không cần lương, chỉ cần một chén cơm và một nụ cười của nàng mỗi ngày.\'</p>', 'Vương gia tới làm tá điền', '', 'publish', 'open', 'closed', '', 'chuong-2-vuong-gia-toi-lam-ta-dien', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-vuong-gia-toi-lam-ta-dien/', '0', 'chuong', '', '0'),
('34', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>\'Bố mẹ, các anh... nếu mọi người đọc được những dòng này, có lẽ tro cốt của con đã được rải ngoài biển khơi rồi.\'</p><p>Tại tang lễ hoang tàn không một vòng hoa, người anh cả run rẩy cầm cuốn sổ tay dính máu mở từng trang. Những bí mật về người em gái ruột luôn cắn răng chịu đựng vì yêu thương gia đình dần hé lộ...</p>', 'Lá thư cuối cùng', '', 'publish', 'open', 'closed', '', 'chuong-1-la-thu-cuoi-cung', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-la-thu-cuoi-cung/', '0', 'chuong', '', '0'),
('35', '2', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '<p>Bà Thẩm ngã quỵ trước di ảnh của con gái ruột, gào khóc thảm thiết: \'Mẹ sai rồi... Uyển Uyển ơi, con về với mẹ đi!\'</p><p>Nhưng đáp lại chỉ là tiếng gió rít qua ô cửa sổ lạnh buốt...</p>', 'Nước mắt muộn màng', '', 'publish', 'open', 'closed', '', 'chuong-2-nuoc-mat-muon-mang', '', '', '2026-10-07 19:08:40', '2026-10-07 12:08:40', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-nuoc-mat-muon-mang/', '0', 'chuong', '', '0'),
('37', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Lạc Phong ngồi co ro trên chiếc ghế gỗ nhỏ xíu, hai cánh tay xăm trổ vụng về cầm chiếc cọ vẽ màu nước.</p><p>Thẩm Dao mỉm cười tiến lại gần, bàn tay mát rượi chạm vào mu bàn tay anh: \'Anh Phong thả lỏng cổ tay ra một chút, vẽ lá mướp đắng thì nét cọ phải mềm mại như thế này nè.\'</p><p>Mặt người đàn ông cao mét tám lăm bỗng đỏ bừng như gấc chín.</p>', 'Lớp học vẽ chiều thứ Bảy', '', 'publish', 'open', 'closed', '', 'chuong-1-lop-hoc-ve-chieu-thu-bay', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-lop-hoc-ve-chieu-thu-bay/', '0', 'chuong', '', '0'),
('38', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Cơn mưa rào mùa hạ bất ngờ đổ xuống con phố nhỏ.</p><p>Lạc Phong bung chiếc ô màu xanh bơ to bản che kín cho Thẩm Dao, mặc cho nửa vai mình ướt đẫm nước mưa: \'Để anh đưa em về, trời tối rồi ngoài đường không an toàn.\'</p>', 'Chiếc ô màu xanh bơ che chở', '', 'publish', 'open', 'closed', '', 'chuong-2-chiec-o-mau-xanh-bo-che-cho', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-chiec-o-mau-xanh-bo-che-cho/', '0', 'chuong', '', '0'),
('40', '2', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>\'Phu nhân, người mau xuống đi, đại nhân sắp bãi triều rồi!\' Tiểu hoàn nha kêu la thất thanh dưới gốc cây đa.</p><p>Vân Lạc đang vắt vẻo trên bờ tường, tay cầm bọc tay nải: \'Không xuống! Ta muốn đi ngắm sông Tiền Đường, ở trong phủ ngột ngạt chết mất!\'</p><p>\'Nàng muốn đi ngắm sông cùng ai?\' Một giọng nói uy nghiêm vang lên từ dưới bóng râm.</p>', 'Bức tường hậu viện cao ba trượng', '', 'publish', 'open', 'closed', '', 'chuong-1-buc-tuong-hau-vien-cao-ba-truong', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-buc-tuong-hau-vien-cao-ba-truong/', '0', 'chuong', '', '0'),
('41', '2', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Vân Lạc trượt chân rơi xuống, nhưng không hề ngã xuống đất mà rơi trọn vào vòng tay vững chãi của Thừa tướng đại nhân.</p><p>\'Lần này nàng trốn phạt chép gia quy mười lần, hay phạt cùng vi phu đi du ngoạn Giang Nam?\' Anh nhướng mày cười.</p>', 'Bắt gọn tại trận', '', 'publish', 'open', 'closed', '', 'chuong-2-bat-gon-tai-tran', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-bat-gon-tai-tran/', '0', 'chuong', '', '0'),
('43', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>\'Em hàng xóm ơi, anh hết tiền mua đồ ăn rồi, cho anh xin bát mì được không?\' Chàng trai gõ cửa nhà tôi với nụ cười tỏa nắng.</p><p>Tôi mềm lòng nấu cho anh bát mì trứng thơm phức. Anh ăn ngon lành như thể đó là sơn hào hải vị.</p>', 'Bát mì trứng cà chua', '', 'publish', 'open', 'closed', '', 'chuong-1-bat-mi-trung-ca-chua', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-bat-mi-trung-ca-chua/', '0', 'chuong', '', '0'),
('44', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Tôi vừa ăn bỏng ngô vừa xem trực tiếp lễ trao giải điện ảnh danh giá.</p><p>\'Và giải thưởng Nam diễn viên chính xuất sắc nhất thuộc về... Giang Trì!\'</p><p>Khi gương mặt người đàn ông xuất hiện trên màn hình, tôi sặc cả ngụm trà sữa: \'Ơ kìa... đó chẳng phải là cái tên sang xin mì tối qua sao?!\'</p>', 'Bí mật bại lộ trên màn hình lớn', '', 'publish', 'open', 'closed', '', 'chuong-2-bi-mat-bai-lo-tren-man-hinh-lon', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-bi-mat-bai-lo-tren-man-hinh-lon/', '0', 'chuong', '', '0'),
('46', '2', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Cậu nhóc trừng mắt nhìn tôi đầy cảnh giác: \'Tôi không ăn đồ của cô đâu!\'</p><p>Tôi đặt đĩa bánh bao kim sa nóng hổi, thơm nức mùi sữa xuống bàn: \'Không ăn thì cô ăn hết nhé, ngon lắm đấy.\'</p><p>Bụng cậu nhóc kêu \'rột rột\' một tiếng rõ to.</p>', 'Cậu nhóc mặt lạnh 5 tuổi', '', 'publish', 'open', 'closed', '', 'chuong-1-cau-nhoc-mat-lanh-5-tuoi', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-cau-nhoc-mat-lanh-5-tuoi/', '0', 'chuong', '', '0'),
('47', '2', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Chưa đầy một tháng, cậu nhóc đã bám chặt lấy chân tôi: \'Mẹ ơi, hôm nay con muốn ăn bánh flan bí đỏ mẹ làm!\'</p><p>Người đàn ông tổng tài quyền lực đứng tựa cửa nhìn hai mẹ con, ánh mắt tràn ngập cưng chiều: \'Thế phần của bố đâu hả hai mẹ con?\'</p>', 'Thu phục nhóc con và ông bố', '', 'publish', 'open', 'closed', '', 'chuong-2-thu-phuc-nhoc-con-va-ong-bo', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-thu-phuc-nhoc-con-va-ong-bo/', '0', 'chuong', '', '0'),
('49', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>\'Xin lỗi An An, Vy Vy đang sốt cao trong bệnh viện, hôn lễ dời lại tuần sau nhé.\'</p><p>Tôi nhìn vào màn hình điện thoại đang sáng, không khóc, cũng không làm loạn. Tôi tháo chiếc nhẫn đính hôn để lên bàn lễ tân khách sạn rồi xách vali bước ra sân bay.</p>', 'Hủy bỏ hôn lễ', '', 'publish', 'open', 'closed', '', 'chuong-1-huy-bo-hon-le', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-huy-bo-hon-le/', '0', 'chuong', '', '0'),
('50', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Khi anh ta quay lại căn hộ chung, tất cả đồ đạc của tôi đã biến mất không còn một dấu vết. Căn phòng trống rỗng và lạnh tanh.</p><p>Lúc này anh ta mới phát hiện, tài khoản mạng xã hội của tôi đã xóa sạch mọi ký ức về anh.</p>', 'Sự biến mất hoàn toàn', '', 'publish', 'open', 'closed', '', 'chuong-2-su-bien-mat-hoan-toan', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-su-bien-mat-hoan-toan/', '0', 'chuong', '', '0'),
('52', '2', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Giữa tiếng còi hụ và khói mù mịt, một cánh tay vững chãi kéo tôi vào khu vực an toàn.</p><p>\'Phóng viên nhỏ, đừng mải quay phim mà quên cả mạng sống của mình chứ!\' Đôi mắt sáng ngời sau chiếc mũ bảo hộ nhìn thẳng vào tôi.</p>', 'Cuộc phỏng vấn giữa khói lửa', '', 'publish', 'open', 'closed', '', 'chuong-1-cuoc-phong-van-giua-khoi-lua', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-cuoc-phong-van-giua-khoi-lua/', '0', 'chuong', '', '0'),
('53', '2', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>Sau chiến dịch cứu nạn thành công, anh xuất hiện trước đài truyền hình với bộ đồng phục thẳng tắp, trên tay cầm bó hoa hướng dương rực rỡ: \'Em đồng ý làm người nhà của lính cứu hỏa không?\'</p>', 'Bông hoa hồng cài áo', '', 'publish', 'open', 'closed', '', 'chuong-2-bong-hoa-hong-cai-ao', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-bong-hoa-hong-cai-ao/', '0', 'chuong', '', '0'),
('55', '3', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '<p>\'Nghe nói tướng quân muốn từ hôn vì chê ta trói gà không chặt?\' Thiếu nữ mặc hồng y giương cung bắn liên tiếp ba phát hồng tâm.</p><p>Thiếu tướng quân trợn tròn mắt, lập tức giấu lá đơn từ hôn ra sau lưng: \'Ai nói bậy thế? Ta về kinh là để xin hoàng thượng định ngày đại hôn!\'</p>', 'Hẹn gặp ở trường bắn', '', 'publish', 'open', 'closed', '', 'chuong-1-hen-gap-o-truong-ban', '', '', '2026-10-07 19:08:41', '2026-10-07 12:08:41', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-hen-gap-o-truong-ban/', '0', 'chuong', '', '0'),
('62', '1', '2026-10-07 19:40:32', '2026-10-07 12:40:32', '{\"version\": 3, \"isGlobalStylesUserThemeJSON\": true }', 'Custom Styles', '', 'publish', 'closed', 'closed', '', 'wp-global-styles-muop-dang', '', '', '2026-10-07 19:40:32', '2026-10-07 12:40:32', '', '0', 'https://muopdangcungcovingot.io.vn/wp-global-styles-muop-dang/', '0', 'wp_global_styles', '', '0'),
('64', '1', '2026-10-07 19:55:13', '2026-10-07 12:55:13', 'Ta Bạch Nhược Nhược là Bạch Phong Thần tôn một trong những vị thần khai sơ củ thần tộc, 1000 năm trước ma giới xâm phạm thần tộc, ta cùng các vị thần khác ra sức chiến đấu trong ngừng nghỉ suốt 180 ngày đêm, cuối cùng cũng dẹp loạn được ma tộc. Trong trận chiến đó không ít vị thần đã ngã xuống, còn ta thần vị tổn thương nghiêm trọng phải bế quang.', 'Mở đầu', '', 'publish', 'open', 'closed', '', 'chuong-1-mo-dau', '', '', '2026-10-07 19:55:13', '2026-10-07 12:55:13', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-mo-dau/', '0', 'chuong', '', '0'),
('65', '1', '2026-10-07 19:58:11', '2026-10-07 12:58:11', '1000 năm sau cũng tức là hôm nay ta chính thức xuất quan. Sau 1000 năm mọi thứ thay đổi rất nhiều, có thêm nhiều thần tiên mới mà ta không nhận ra. Cảnh vật cũng không còn như xưa nữa.', 'Xuất hiện', '', 'publish', 'open', 'closed', '', 'chuong-2-xuat-hien', '', '', '2026-10-07 19:58:11', '2026-10-07 12:58:11', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-xuat-hien/', '0', 'chuong', '', '0'),
('66', '1', '2026-10-07 21:13:59', '2026-10-07 14:13:59', 'Trang Điều Khoản Sử Dụng', 'Điều Khoản Sử Dụng', '', 'publish', 'closed', 'closed', '', 'dieu-khoan', '', '', '2026-10-07 21:13:59', '2026-10-07 14:13:59', '', '0', 'https://muopdangcungcovingot.io.vn/dieu-khoan/', '0', 'page', '', '0'),
('67', '1', '2026-10-07 21:13:59', '2026-10-07 14:13:59', 'Trang Chính Sách Bảo Mật', 'Chính Sách Bảo Mật', '', 'publish', 'closed', 'closed', '', 'chinh-sach-bao-mat', '', '', '2026-10-07 21:13:59', '2026-10-07 14:13:59', '', '0', 'https://muopdangcungcovingot.io.vn/chinh-sach-bao-mat/', '0', 'page', '', '0'),
('68', '1', '2026-10-07 21:13:59', '2026-10-07 14:13:59', 'Trang Liên Hệ', 'Liên Hệ', '', 'publish', 'closed', 'closed', '', 'lien-he', '', '', '2026-10-07 21:13:59', '2026-10-07 14:13:59', '', '0', 'https://muopdangcungcovingot.io.vn/lien-he/', '0', 'page', '', '0'),
('72', '1', '2026-10-09 16:34:14', '2026-10-09 09:34:14', 'Chị tôi vừa xinh đẹp vừa thông minh, lúc nào cũng dễ dàng có được sự yêu thích của người khác.
Tới mức khiến cho một kẻ bình thường như tôi trông chẳng khác nào một con chuột nhỏ nhút nhát.
Bố mẹ nói: \"Mày làm sao so được với chị mày?\"
Trúc mã nói: \"Giai Giai với cậu chẳng giống chị cậu chút nào.\"
Tôi hỏi cậu: \"Thế giống gì?\"
Cậu vừa thò lò mũi xanh vừa nói: \"Giống công chúa và nha hoàn.\"
Cho đến khi tôi gặp Cầm Dịch.
Bố mẹ nắm tay chị tôi, giới thiệu với cả nhà cậu xem con gái mình xuất sắc ra sao.
Tôi đứng một bên lén nhìn đĩa bánh quy trên bàn nhưng cậu lại đi qua tất cả mọi người, ôm chặt lấy tôi vào lòng.
\"Của tôi.\" Cậu nói.
', 'TỪ NHỎ ĐÃ BỊ SO SÁNH VỚI CHỊ GÁI, KHÔNG NGỜ LẠI ĐƯỢC NAM THẦN THEO ĐUỔI', '', 'publish', 'open', 'closed', '', 'tu-nho-da-bi-so-sanh-voi-chi-gai-khong-ngo-lai-duoc-nam-than-theo-duoi', '', '', '2026-10-16 22:20:19', '2026-10-16 15:20:19', '', '0', 'https://muopdangcungcovingot.io.vn/truyen/tu-nho-da-bi-so-sanh-voi-chi-gai-khong-ngo-lai-duoc-nam-than-theo-duoi/', '0', 'truyen', '', '0');
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
('73', '1', '2026-10-09 16:35:00', '2026-10-09 09:35:00', 'Chị tôi vừa xinh đẹp vừa thông minh, lúc nào cũng dễ dàng có được sự yêu thích của người khác.
Tới mức khiến cho một kẻ bình thường như tôi trông chẳng khác nào một con chuột nhỏ nhút nhát.
Bố mẹ nói: \"Mày làm sao so được với chị mày?\"
Trúc mã nói: \"Giai Giai với cậu chẳng giống chị cậu chút nào.\"
Tôi hỏi cậu: \"Thế giống gì?\"
Cậu vừa thò lò mũi xanh vừa nói: \"Giống công chúa và nha hoàn.\"
Cho đến khi tôi gặp Cầm Dịch.
Bố mẹ nắm tay chị tôi, giới thiệu với cả nhà cậu xem con gái mình xuất sắc ra sao.
Tôi đứng một bên lén nhìn đĩa bánh quy trên bàn nhưng cậu lại đi qua tất cả mọi người, ôm chặt lấy tôi vào lòng.
\"Của tôi.\" Cậu nói.
…
Nghê Giai Giai có gương mặt trái xoan, mắt to, da trắng nõn, lông mi dài ngoẵng như hai cây quạt nhỏ.
Bình thường mọi người gặp, đều hỏi mẹ tôi: \"Con nhà chị có phải người mẫu nhí không thế?\"
Mẹ sẽ mím môi cười, bảo: \"Đâu có đâu có.\" Vẻ mặt đầy tự hào.
Lúc này tôi mà gọi: \"Mẹ ơi.\"
Người ta liền kinh ngạc, nhìn thấy một đứa trẻ qua đường mặt đỏ bừng vì lạnh, nước mũi chảy ròng ròng, rồi hỏi: \"Đây cũng là con gái chị à?\"
Khóe miệng mẹ xị xuống, biến thành nét mặt thản nhiên: \"Đúng rồi. Đứa nhỏ.\"
Người đó bèn cười: \"Chắc không phải chất dinh dưỡng trong bụng bị đứa lớn tranh mất rồi đấy chứ?\"
Mẹ cũng cười: \"Thế thì cũng là do đứa lớn có bản lĩnh.\"
Nghê Giai Giai đã cướp mất chất dinh dưỡng của tôi, chỉ một câu nói đùa bâng quơ của người lớn mà đứa trẻ lại khắc ghi trong lòng.
Trong một thời gian dài, tôi nuôi hận đối với Nghê Giai Giai. Thực ra cũng chẳng dài lắm, khoảng hai ba tháng gì đó. Biểu hiện cụ thể là tôi tranh trái cây của chị ấy ăn. Những quả dâu tây đỏ mọng, những quả anh đào bóng lưỡng, cả món sầu riêng mùi vị kỳ lạ nữa.
Mẹ luôn làm sạch sẽ gọn gàng rồi đưa cho Nghê Giai Giai ăn, cả một đĩa to đùng nhưng chỉ thừa lại một đống đồ thừa góc cạnh cho tôi. Tôi ăn hết phần của mình rồi vẫn thèm, bèn lén lút thò tay bốc từ bát của chị ấy.
Vài lần như thế thì bị bố mẹ phát hiện.
Nghê Giai Giai khóc lóc nói: \"Em gái giành trái cây của con.\"
Chị ấy khóc trông như nàng tiên cá nhỏ rơi giọt lệ ngọc trai trong sách cổ tích vậy. Đáng thương lại đáng yêu.
Tôi rướn cổ lên gào: \"Chị ở trong bụng mẹ đã cướp hết dinh dưỡng của em!\"
Chị ấy bảo: \"Chị không có!\"
Tôi vô lý cãi cự: \"Có mà có mà!\"
Tôi càng nói càng kích động, ngũ quan nhăn nhúm méo mó. Được Nghê Giai Giai làm nền trông chẳng khác nào ác quỷ giáng thế, một mụ phù thủy già chua ngoa cay nghiệt đi làm khó công chúa nhỏ khiến mẹ tôi tức giận lấy móc áo quật vào mông tôi: \"Nói nhăng nói cuội cái gì đấy! Đã xấu xí rồi còn ác độc thế hả!\"
Tôi nhảy dựng lên né chiếc móc áo của mẹ, gào khóc xin hàng: \"Con sai rồi con sai rồi.\"
Mẹ chống tay ngang hông bảo: \"Chị mày không nợ mày. Lần sau còn để tao thấy mày giành đồ của chị mày, tao đánh cho nhừ đòn.\"
Nghê Giai Giai không cướp dinh dưỡng của tôi thế tại sao chị ấy lại xinh đẹp thế còn tôi lại trông bình thường như vậy? Tôi nghĩ mãi không ra. Tại sao bố mẹ lại đối xử với chị ấy tốt hơn một chút? Ngẫm nghĩ không thông, tôi đi hỏi người khác.
Dưới sảnh khu chung cư, tôi với Kỷ Thuật ngồi xếp hàng bên nhau, mỗi đứa nửa cây kem que.
\"Cậu có anh trai không?\" Tôi hỏi Kỷ Thuật.
Cậu ấy xì xụp mút kem: \"Có, sao thế?\"
\"Thế mẹ cậu cũng đối xử tốt với anh cậu hơn à?\"
\"\'Tốt hơn\' nghĩa là sao?\" Cậu ấy nhìn tôi.
Tôi ngẫm nghĩ rồi nói: \"Là... lúc gắp thức ăn luôn gắp cho anh cậu, trái cây cũng cho anh cậu nhiều hơn, chỉ đăng ký học thêm cho mỗi anh cậu...\"
Cậu ấy suy nghĩ một lúc rồi lắc đầu: \"Không có. Nhưng mà không đi học thêm chẳng phải là chuyện tốt sao?\"
Hai chúng tôi nhìn nhau: \"Cũng đúng thật.\"
Mẹ đăng ký cho Nghê Giai Giai bao nhiêu là lớp, nào là piano, luyện thần thái, dẫn chương trình rồi linh tinh đủ thứ. Mỗi lần ra ngoài đều phải mặc váy dạ hội nhỏ xinh đẹp, trang điểm lộng lẫy, lịch rảnh rỗi bị nhét đầy ắp. Nhưng tôi thì khác nha, tôi có thể chạy ra ngoài chơi bừa bãi bất cứ lúc nào. Lúc tôi với lũ trẻ trong xóm đắp lâu đài cát thì Nghê Giai Giai đang ở lớp học thêm. Lớp học thêm đau khổ lắm đó.
Nghĩ như thế, Nghê Giai Giai tội nghiệp thật đấy. Lòng tôi lập tức cân bằng trở lại.
Kỷ Thuật hỏi: \"Cậu có chị gái à?\"
Tôi bảo: \"Ừm.\"
Cậu ấy \"ồ\" một tiếng, không hỏi tiếp nữa, chỉ còn lại tiếng xì xụp mút kem của hai đứa.
Tôi đã tìm lại được sự cân bằng vô lý ấy, không còn quấy phá ở nhà nữa, thậm chí còn có chút đồng cảm với Nghê Giai Giai.
Tôi muốn ra ngoài chơi lúc nào thì chơi, lại còn có một đống bạn bè. Hôm đó tôi chơi đến mức quên cả thời gian, đến khi nhận ra thì mặt trời đã lặn mất từ lâu, đèn đường trong khu xóm đã sáng lên.
', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-chuong-1', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-chuong-1/', '0', 'chuong', '', '0'),
('74', '1', '2026-10-09 16:35:18', '2026-10-09 09:35:18', 'Các bạn lần lượt được phụ huynh tìm đến dắt về. Người giận dỗi, người lo lắng, người mắng mỏ, người dịu dàng. Họ đều được bố mẹ dắt về nhà. Kỷ Thuật cũng bị anh trai xách tai lôi về. Anh trai cậu ấy trông khá giống cậu ấy nhưng nhìn đẹp trai hơn Kỷ Thuật.
Trước khi đi Kỷ Thuật còn ngoái đầu dặn tôi: \"Về nhà sớm đi nhé, Tiểu Tiểu.\"
Khu sân chơi náo nhiệt bỗng nhiên yên tĩnh lại, tôi mới nhận ra chỉ còn lại một mình mình. Thật yên tĩnh. Tôi ngước nhìn nhà mình trên cao, đèn phòng khách đã sáng.
Sao bố mẹ không đến tìm tôi nhỉ?
Tôi đá đá hòn đá nhỏ dưới chân. Nếu là Nghê Giai Giai không về nhà... Không có khả năng đó. Mẹ luôn đi cùng Nghê Giai Giai để học đủ loại khóa học.
Thực ra tôi có thể về nhà ngay nhưng không biết đang bướng bỉnh với ai mà cứ nhất quyết không chịu bước đi. Tôi ngồi xổm xuống, vẽ mèo con trên cát, vẽ lâu đài, vẽ công chúa, vẽ nơ bướm... Cả bãi cát biến thành tờ giấy vẽ.
Sắp vẽ kín hết thì những người tan làm đi đi lại lại đều đã về nhà nhưng cũng chẳng có ai đến tìm tôi. Người trong khu xóm ngày càng thưa thớt. Bụng tôi phát ra tiếng cồn cào.
Gió thổi lá cây phát ra tiếng sột soạt giống như con ma đang va quệt lao đao trong lùm cây. Sống lưng tôi thẳng tắp, không dám ngoái đầu, cắm đầu chạy thục mạng. Một hơi lao đến sảnh tòa nhà, đi thang máy, đập cửa rầm rầm như thể có ma đuổi sau lưng.
Ba... Hai... Chỉ một giây nữa thôi là bị bắt đi mất... Tôi sắp khóc đến nơi rồi.
Cửa mở.
Ánh sáng trắng chiếu ra từ trong nhà, con ma lập tức tan biến. Tôi không bị bắt đi. Người mở cửa là mẹ, nhìn thấy tôi lập tức hừ một tiếng: \"Lại đi đâu đấy? Về nhà muộn thế này. Để phần cơm cho mày trong nồi đấy.\"
Tôi chìm đắm trong niềm vui sống sót sau tai nạn do chính mình tự tưởng tượng ra, chỉ biết cười ngơ ngác: \"Cảm ơn mẹ.\"
Tôi vội vàng thay giày vào ăn cơm. Nghê Giai Giai đang xem hoạt hình ở phòng khách, tôi nhanh chóng lùa xong bát cơm, ngồi qua xem cùng chị ấy.
Câu chuyện thám tử nhỏ. Viên hồng ngọc ở nhà ông lão bị trộm mất. Bốn nghi phạm, có một nghi phạm mặt bánh chưng, mắt nhỏ, dáng vẻ lén lút xảo quyệt xuất hiện.
Tôi bảo: \"Nhất định là hắn ta.\"
Nghê Giai Giai liếc tôi một cái, chẳng buồn tiếp lời.
Tôi bảo: \"Chị tin không, chúng ta cá cược đi. Nếu em thắng, chị chia cho em một nửa tiền tiêu vặt.\"
Chị ấy không nói gì, giả vờ kiêu kỳ.
Tôi trừng mắt nhìn chị ấy.
Chị ấy bị tôi làm cho bực mình, \"chậc\" một tiếng: \"Lý do?\"
Tôi bảo: \"Tên đó nhìn qua là biết không phải người tốt!\"
\"Ồ.\" Nghê Giai Giai nói: \"Cậu ta trông khá giống em đấy.\"
Cơn tức giận của tôi bùng lên, định xông vào tay đôi với chị ấy thì mẹ gọi: \"Giai Giai, qua luyện đàn thôi con.\"
Mặt Nghê Giai Giai lập tức xị xuống, uể oải đi qua. Tôi lập tức hả hê trở lại, tiếp tục xem tivi.
Mẹ nói: \"Nghê Tiểu Tiểu, không được xem nữa, chị con phải luyện đàn, đừng làm phiền chị.\"
Tôi \"ồ\" một tiếng, tắt tiếng tivi rồi xem tiếp.
Cái đài truyền hình đáng chết, cứ đến lúc chuẩn bị tiết lộ ai là kẻ xấu thật sự thì lại \"hẹn gặp lại ở tập sau\", lại phải chờ một tuần. Oán niệm của tôi lớn đến mức Kỷ Thuật cũng phát hiện ra.
Hai chúng tôi mỗi đứa ngồi một cái xích đu, đung đung đung đưa.
Cậu ấy bảo: \"Ai lại chọc giận cậu thế?\"
Tôi nổi giận mắng xối xả đài truyền hình.
Kỷ Thuật nói: \"Tớ cũng nghĩ là người đó, người đó trông giống hệt con chuột cống.\"
Hai chúng tôi nhìn nhau, đập tay: \"Bạn tốt!\"
\"Lần trước cậu về, mẹ cậu có mắng cậu không?\"
\"Không.\" Tôi bảo.
Cậu ấy hâm mộ ra mặt: \"Mẹ cậu tốt thật đấy.\"
Tôi không nói gì, cứ cảm thấy có gì đó sai sai. Vừa hay Nghê Giai Giai học xong lớp piano về, mặc chiếc váy xòe màu hồng xinh đẹp, trên tóc kẹp chiếc kẹp lấp lánh. Mẹ dắt tay chị ấy đi về nhà từ đằng xa.
Tôi gào to một tiếng: \"Mẹ ơi! Chị ơi!\"
Mẹ vẫy vẫy tay, hô từ xa: \"Đừng chơi muộn quá nhé.\"
Nghê Giai Giai ngoái đầu nhìn một cái rồi đi xa theo mẹ.
Lúc tôi quay đầu lại thì kem que của Kỷ Thuật đã rơi xuống đất, dính đầy cát.
\"Cậu làm gì thế?\" Kỷ Thuật há hốc mồm, chỉ chỉ lưng Nghê Giai Giai. \"Đó là chị gái cậu à?\"
Tôi bảo: \"Đúng thế.\"
Cậu ấy nhìn Nghê Giai Giai, lại nhìn tôi, rồi lại nhìn Nghê Giai Giai: \"Cậu là con nhặt về đúng không?\"
Tôi tức giận: \"Đồ heo ngốc này!\"
Kỷ Thuật nói: \"Chị cậu giống như công chúa vậy.\"
Thực ra tôi cũng thấy Nghê Giai Giai giống công chúa, nhưng lúc này tôi cực kỳ không muốn hùa theo cậu ấy. Cậu ấy thấy tôi không thèm chấp, lại nói: \"Cậu với chị cậu chẳng giống chị em chút nào. Giống cái gì nhỉ? Giống công chúa và nha hoàn.\"
Làm gì có kiểu bạn bè như thế này chứ! Tôi thúc vào người Kỷ Thuật một cái. Cậu ấy ngã dập mông xuống cát: \"Ớ, tớ nói thật mà...\"
\"Đồ heo chết!\" Tôi vứt lại cậu rồi chạy biến đi. \"Chúng ta tuyệt giao!\"
Tôi tức đến phát khóc, trở về xé nát vụn mấy tấm thẻ nhỏ Kỷ Thuật tặng tôi. Kỷ Thuật là con heo đại ngốc, rõ ràng chúng tôi mới là bạn bè, cho dù anh trai cậu ấy đẹp trai hơn cậu ấy tôi cũng sẽ không bao giờ nói trước mặt cậu ấy.
Mảnh giấy vụn nát rải rác đầy sàn, mẹ nhìn thấy quát tôi: \"Nghê Tiểu Tiểu! Mày lại làm cái gì đấy! Rác rưởi vứt lung tung khắp nơi! Không biết giữ vệ sinh gì cả!\"
Tôi vừa cãi lại vừa nhặt mảnh giấy bỏ vào thùng rác: \"Lần trước chị cũng làm đổ canh ra ngoài! Sao mẹ không mắng chị!\"
\"Đó là do chị con không cố ý.\"
\"Con cũng là không cố ý!\" Tôi kích động quá, nước mắt trào ra: \"Chẳng lẽ chị là không cố ý, còn con là cố tình à!\"
Mẹ chống tay ngang hông, trừng mắt nhìn tôi: \"Mày còn biết cãi rồi đấy! Sao mày không học tập chị mày, ngoan ngoãn hơn mày bao nhiêu. Khóc cái gì mà khóc, thật xấu mặt.\"
Tôi nói: \"Đó là vì bố mẹ chưa bao giờ mắng chị ấy!\"
Mẹ giận quá hóa cười: \"Mày còn quay ngược lại trách bố mẹ đúng không?\"
Mẹ định lấy móc áo, tôi vội vàng chạy vèo về phòng đóng sầm cửa lại. Mẹ không đuổi theo. Một lúc sau, bên ngoài vang lên tiếng đóng cửa, mẹ lại dắt Nghê Giai Giai ra ngoài rồi.
', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-chuong-2', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-chuong-2/', '0', 'chuong', '', '0'),
('75', '1', '2026-10-10 19:58:32', '2026-10-10 12:58:32', 'Tôi lôi chiếc gương nhỏ ra, hận hận nghĩ, ai cũng thích Nghê Giai Giai chỉ vì chị ấy xinh đẹp thôi sao? Tôi cũng đâu đến mức xấu lắm. Trong gương phản chiếu gương mặt đầm đìa nước mắt nước mũi của tôi, méo mó như quả mướp đắng làm chính tôi cũng giật mình, vội vàng ném cái gương sang một bên.
Tôi nhớ tới lần nhìn thấy Nghê Giai Giai rơi nước mắt, đẹp như tiên nữ vậy. Thật là... Mẹ mắc gì sinh Nghê Giai Giai xinh đẹp như thế chứ.
Tôi cảm thấy mình bị cả thế giới tổn thương. Bố mẹ thích Nghê Giai Giai hơn, đến bạn bè của tôi khi nhìn thấy chị ấy cũng thích chị ấy. Tôi quyết định làm một người lạnh lùng để bọn họ phải hối hận hết!
Hôm sau đi học, Kỷ Thuật chào tôi. Tôi giả vờ như không thấy, mắt nhìn thẳng đi qua. Kỷ Thuật lại cứ thích xán lại gần, vẫy vẫy tay: \"Cận thị rồi à?\"
Tôi lạnh lùng: \"Xin cậu đừng nói chuyện với tớ nữa, chúng ta đã tuyệt giao rồi.\"
Cậu ấy cười hì hì: \"Giận thật đấy à? Là tớ không tốt, cậu đừng giận tớ nữa mà.\" Nói rồi, cậu ấy lôi ra một chai sữa đưa cho tôi: \"Cho cậu này, sữa nhập khẩu bác tớ mua đấy, đắt lắm luôn.\"
Tôi hơi thèm thuồng, bình thường ở nhà đồ ngon đều để phần hết cho Nghê Giai Giai. Vừa định cầm lấy, cậu ấy lại móc ra một chai màu hồng vị dâu: \"Cái này cho chị cậu, ngọt lắm chắc chị ấy thích.\"
Bàn tay vươn ra của tôi lập tức thu rụt trở lại. Tôi không nói gì, rảo bước đi vòng qua cậu. Kỷ Thuật ở phía sau gào lên: \"Tiểu Tiểu! Nghê Tiểu Tiểu?\"
Chiều tối ăn cơm, mặt tôi lạnh như tiền. Lạnh lùng ăn cơm, lạnh lùng bê bát về bếp, lạnh lùng ngồi xem hoạt hình. Tuy nhiên chẳng ai thèm để ý.
Sự lạnh lùng của tôi kéo dài cho đến khi chương trình hoạt hình chiếu xong. Thủ phạm thật sự lại chính là ông lão trông hiền lành tốt bụng, không phải tên đàn ông trông gian xảo kia.
Tôi thua cược rồi. Tôi xót xa liếc nhìn Nghê Giai Giai. Chị ấy hừ một tiếng.
Tôi đau ví: \"Thua thì chịu! Em chia một nửa tiền tiêu vặt cho...\"
\"Ai thèm tí tiền đó của em.\" Chị ấy bảo.
Tôi hét lên: \"Mẹ ơi, con muốn tăng tiền tiêu vặt!\"
Mẹ bảo: \"Bằng đó đủ cho mày dùng rồi.\"
\"Tăng lên bằng một nửa của chị cũng không được sao? Cầu xin mẹ đấy.\"
Mẹ lạnh nhạt: \"Chị mày biết đánh piano, biết dẫn chương trình, biết hát, học giỏi. Nghê Tiểu Tiểu, mày biết cái gì?\"
Tôi ngẩn người. Đúng vậy, tôi chẳng biết cái gì cả, chưa từng được học lớp năng khiếu nào.
\"Đó là vì con có được học cái gì đâu...\"
\"Mày nghĩ mày học là mày sẽ biết chắc?\" Nét mặt mẹ sa sầm xuống, không muốn nói nhiều: \"Được rồi, Nghê Giai Giai, đi luyện đàn.\"
Tiếng piano du dương lại cất lên từ phòng riêng của chị ấy kèm theo tiếng mẹ khen \"giỏi quá\". Tôi ngơ ngác nhìn màn hình tivi. Xem hoạt hình vốn là điều làm tôi vui vẻ nhất nhưng bây giờ tôi cảm thấy đặc biệt, đặc biệt buồn.
Tôi trùm chăn khóc, vừa ẩm vừa bí, cuối cùng ngủ thiếp đi lúc nào không hay. Sáng hôm sau tỉnh dậy mắt sưng húp. Tôi ăn vội bữa sáng rồi về phòng.
Có tiếng gõ cửa, mẹ mở cửa.
\"Cháu chào cô ạ! Cháu đến tìm Nghê Tiểu Tiểu.\" Là giọng của Kỷ Thuật.
Vốn dĩ định giận cậu ấy thêm một tuần, không ngờ cậu ấy lại chủ động đến tận nhà xin lỗi. Tôi thở phào, thấy chút an ủi. Nghê Giai Giai chẳng bao giờ có bạn đến nhà tìm chơi cả. Tôi quyết định tha thứ cho Kỷ Thuật. Tôi lấy tay bịt mắt cố làm cho nó bớt sưng.
Nhưng tôi đợi rất lâu không thấy cậu ấy gọi tôi trái lại ngoài phòng khách có người đang trò chuyện vui vẻ. Tôi mở hé cửa lén nhìn ra.
Kỷ Thuật ngồi bên cạnh Nghê Giai Giai, cười nịnh hót vô cùng như đang kể chuyện cười. Nghê Giai Giai cười vui vẻ, Kỷ Thuật lại càng vui hơn. Cái gì vậy chứ! Tôi giận dữ, sẽ không bao giờ tha thứ cho cậu ấy nữa.
Kỷ Thuật nhìn sang giật mình: \"Nghê Tiểu Tiểu, mắt cậu sao lại thành ra thế kia!\"
\"Ưm... bị muỗi đốt.\" Tôi buồn bực.
Nghê Giai Giai cười không khép được miệng: \"Cậu nói xem nó có giống cái tên mặt bánh chưng đó không? Bây giờ mắt nhỏ lại càng giống hơn.\"
Kỷ Thuật hùa theo: \"Giống thật!\"
Tôi phẫn nộ: \"Các người mới giống ấy! Hai người là hai kẻ xấu nhất thế giới, xấu hơn cả heo!\" Tôi đóng sầm cửa lại.
Tôi lại lén lấy gương ra soi, cũng đâu đến mức giống lắm... Cùng lắm thì tên đó giống chuột cống, còn tôi giống chuột nhắt... Chuột nhắt cũng đáng yêu mà. Tôi tự giận rồi lại tự dỗ dành mình xong xuôi.
Lúc Kỷ Thuật về, mẹ bảo tôi tiễn cậu ấy xuống nhà. Kỷ Thuật lén liếc tôi: \"Nghê Tiểu Tiểu...\"
\"Sau này cậu đừng đến đây nữa, tớ không muốn làm bạn với cậu nữa.\"
Kỷ Thuật cũng nổi giận: \"Nghê Giai Giai rõ ràng đẹp hơn cậu, tớ thích làm bạn với chị ấy đấy.\"
Tôi hừ một tiếng: \"Dựa vào bề ngoài mà đánh giá người khác.\"
Kỷ Thuật tức giận: \"Bản thân cậu không phải thế sao! Thấy tên mặt bánh chưng đó xấu là bảo hắn xấu. Cậu mới là kẻ nhìn mặt bắt hình dong. Chắc chắn cậu ghen tị vì chị cậu đẹp hơn cậu chứ gì. Cậu giống như mấy mụ đàn bà ác độc...\"
Kỷ Thuật dùng giọng điệu đùa giỡn để nói ra câu nói này. Tấm cửa kim loại của thang máy phản chiếu khuôn mặt kéo dãn, vặn quẹo của cậu ấy. Tôi mím chặt môi, nước mắt không chảy ra nổi nữa.
Ghen tị sao? Có lẽ là vậy. Nhưng tôi chưa bao giờ nói một câu không tốt nào về Nghê Giai Giai trước mặt bất kỳ ai, Kỷ Thuật biết rõ điều đó.
\"Cậu thật buồn nôn.\" Tôi nói: \"Tớ thật sự rất hối hận vì đã làm bạn với cậu. Tớ sẽ không bao giờ chơi với cậu nữa.\"
Nhận ra tôi nghiêm túc, Kỷ Thuật cuống lên rồi cứng miệng nói: \"Cậu làm như tớ thèm lắm ấy, tớ làm bạn với Nghê Giai Giai!\" Thang máy đóng lại.
Khi tôi vào nhà, mẹ đã gắp một núi thức ăn cho Nghê Giai Giai. Tôi ngồi xuống ăn vội ăn vàng, nịnh hót mẹ nấu ăn ngon. Mẹ vui vẻ gắp thêm sườn cho chị ấy, tôi giả vờ không thấy.
Bố mẹ thích Nghê Giai Giai hơn không sao cả, bạn bè thích chị ấy hơn cũng không sao. Thế giới rộng lớn như vậy, nếu tôi nỗ lực ăn cơm, nhanh chóng lớn lên và rời khỏi đây, nhất định sẽ có người thích tôi hơn một chút.
Tôi không còn nói chuyện với Kỷ Thuật nữa. Cậu ấy cũng không chơi với Nghê Giai Giai vì chị ấy rất bận. Cuộc sống của tôi vẫn bình thản trôi qua.
Chiều thứ Bảy, Nghê Giai Giai hiếm khi không đi luyện đàn. Bố mẹ ăn mặc chỉnh tề, dắt chị ấy đi ăn cơm ở nhà người khác. Mẹ dặn tôi ăn mặc đẹp đẽ, tôi bèn lôi chiếc áo hoa nhỏ tiếc không dám mặc ra, còn dùng đến chiếc kẹp nơ bướm. Tôi soi gương tự khen mình cũng đáng yêu mà.
Bố mẹ dẫn chúng tôi vào một căn biệt thự lớn có khu vườn xinh đẹp. Mẹ dắt Nghê Giai Giai tự hào giới thiệu với người cô xinh đẹp về tài năng piano, dẫn chương trình của chị ấy. Tôi đứng một bên chờ mẹ giới thiệu mình nhưng mẹ lại hỏi sang chuyện con cái nhà cô.
Chẳng ai để ý đến tôi, tôi đành lén nhìn đĩa bánh quy trên bàn.
Lúc này, trên tầng có tiếng bước chân. Đó là lần đầu tiên tôi gặp Cầm Dịch.
', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-chuong-3', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3-chuong-3/', '0', 'chuong', '', '0'),
('76', '1', '2026-10-10 19:58:49', '2026-10-10 12:58:49', 'Trong nhận thức hạn hẹp của tôi, Nghê Giai Giai đã rất đẹp rồi nhưng Cầm Dịch còn đẹp hơn chị ấy. Thiếu niên như món đồ sứ trắng tinh xảo trong tủ trưng bày, đẹp đẽ lại mang theo sự lạnh nhạt không cho phép ai lại gần.
Mẹ lập tức khen ngợi cậu, Nghê Giai Giai cũng mỉm cười với cậu. Tôi đang nỗ lực sửa thói quen nhìn mặt bắt hình dong nên chỉ liếc nhìn một cái rồi lại tập trung vào đĩa bánh quy.
Đột nhiên, một mùi hương thanh mát lạnh lẽo bao bọc lấy tôi. Tôi bị ai đó ôm chặt vào lòng. Tôi kinh ngạc ngẩng đầu, khuôn mặt thiếu niên ở ngay sát trước mặt, đôi mắt xám khói nhạt như nhát mực thủy mạc.
\"Của con.\" Cậu nói.
Mọi người ngơ ngác. Cô xinh đẹp hoảng hốt bảo cậu buông ra, cậu càng ôm chặt hơn: \"... Không buông.\" Mẹ chữa cháy bảo hai đứa có duyên, cô đành bảo Cầm Dịch dắt tôi lên phòng chơi. Cậu nắm tay tôi đi lên, tôi liếc nhìn Nghê Giai Giai định rủ chị ấy nhưng thấy chị ấy mím môi không vui nên tôi im lặng.
Phòng Cầm Dịch có ngập tràn đồ chơi. Cậu dẫn tôi vào rồi im lặng.
Tôi chủ động: \"Tớ tên Nghê Tiểu Tiểu, cậu tên gì?\"
\"Cầm Dịch.\"
Cậu nhìn tôi: \"Đậu Đậu.\"
\"Không phải, là Nghê Tiểu Tiểu.\"
\"Đậu Đậu.\"
Tôi bực mình, mắc gì mới gặp đã đặt biệt danh cho người khác.
\"Đậu Đậu là ai?\"
\"Chuột lang.\"
Tôi bùng nổ cơn giận, tôi giống chuột đến thế sao? \"Nó đâu rồi?\"
\"Ở ngoài sân.\"
\"Đi xem đi.\"
Cầm Dịch rủ mi: \"Không gặp được nữa. Chết rồi.\"
Tôi nghẹn lời. Thấy cậu cô độc, ít nói, chắc không có bạn bè. Thì ra câu \"Của con\" ban nãy là vì cậu thấy tôi giống con chuột lang đã mất của cậu. Tôi mềm lòng: \"Được rồi, thực ra tớ là Đậu Đậu đây, tớ lại đến làm bạn với cậu này.\"
Cầm Dịch nhìn tôi như nhìn kẻ ngốc: \"Đậu Đậu là chuột lang, cậu là người.\"
Tôi xấu hổ: \"Ai bảo cậu gọi tớ thế trước.\"
\"Không được sao? Cậu rất giống nó.\"
Tôi mím môi, thôi kệ cậu, ai bảo cậu vừa mất đi một người bạn chứ.
Tôi với Cầm Dịch chơi xếp hình. Cậu giỏi cực kỳ, xếp ra cả máy bay, tòa nhà. Nghê Giai Giai đi vào, bước chân dồn dập. Tôi rủ chị ấy chơi chung nhưng chị ấy không thèm để ý tôi, chỉ lén liếc Cầm Dịch. Cầm Dịch cũng chẳng buồn nói chuyện với chị ấy, Nghê Giai Giai giậm chân bỏ ra ngoài.
Đến bữa tối, trên bàn toàn món ngon: tôm hùm, cua, sườn chua ngọt. Tôi vừa thò tay gắp cái cánh gà thứ hai thì mẹ vỗ tay tôi trừng mắt: \"Không có phép tắc.\"
Tôi cúi đầu lùa cơm, đột nhiên trong bát tôi có thêm một cái cánh gà, rồi lại thêm một miếng nữa. Cầm Dịch gắp thức ăn cho tôi chất thành một ngọn núi nhỏ: \"Ăn nhiều vào, Đậu Đậu.\"
Tôi chưa kịp xúc động thì biết cậu lại coi tôi là chuột lang mà cho ăn. Nét mặt cô chú kỳ quặc, bố mẹ ngơ ngác, còn Nghê Giai Giai thì sa sầm nét mặt.
Lúc về, cô xinh đẹp xoa đầu tôi dặn: \"Thứ Bảy tuần sau lại sang chơi với Cầm Dịch nhé.\" Tôi gật đầu.
Tôi không kể với bố mẹ, thứ Bảy mẹ bận quản Nghê Giai Giai nên chẳng ngó ngàng tới tôi. Tuần nào tôi cũng sang nhà Cầm Dịch chơi đồ chơi, cậu ngồi đọc sách bên cạnh.
Có một hôm tôi mải chơi quên giờ, dưới nhà tỏa mùi thức ăn thơm phức, cô giữ tôi lại ăn cơm và gọi điện xin phép bố mẹ tôi. Tôi ăn căng cả bụng, cô lái xe đưa tôi về, thấy bố mẹ đứng chờ dưới nhà.
Cô khen tôi đáng yêu, bảo hai đứa rất hợp nhau. Bố mẹ vui vẻ hẳn, bố còn bảo thứ Bảy tuần sau sẽ đưa tôi sang. Tối đó khi chia dâu tây, lần đầu tiên phần của tôi nhiều bằng Nghê Giai Giai. Nghê Giai Giai lườm tôi cháy mắt. Quả dâu tây rất ngọt, tôi đã có được thứ mình muốn nhưng sao trong lòng lại chẳng vui như tưởng tượng?
Thứ Bảy tuần sau, bố đưa tôi đi nhưng trên xe có cả Nghê Giai Giai. Bố bảo chị ấy ít bạn, cho chị ấy sang chơi cùng để kết bạn với Cầm Dịch vì cả hai đều biết đàn piano. Tôi nhìn ra cửa sổ, nghĩ thầm lỡ đâu Cầm Dịch thật sự thích làm bạn với Nghê Giai Giai thì sao...
Đến nơi, tôi vào phòng đồ chơi, Cầm Dịch tự động dịch lại ngồi cạnh tôi. Nghê Giai Giai ngồi xuống bên cạnh cậu, chủ động bắt chuyện bằng giọng ngọt ngào: \"Cậu đang xem gì thế?\"
\"Sách phổ cập khoa học.\"
\"Cùng xem nhé?\"
\"Không thích.\"
\"Cậu biết đánh piano không? Tớ cũng biết nè, cậu thi mấy cấp rồi?\"
\"Chưa thi.\"
\"Cậu biết bài Bi Thương của Beethoven không? Chúng ta đàn chung nhé?\"
\"Không muốn.\"
Nghê Giai Giai sượng sùng. Cầm Dịch đột nhiên quay sang hỏi tôi: \"Cậu biết đánh piano không?\"
Tôi ngẩn ngơ: \"Không biết.\"
Nghê Giai Giai lập tức chớp thời cơ dìm tôi: \"Nó chẳng học cái gì cả, thi cử toàn cẩu thả, mải chơi...\" Chị ấy nhìn Cầm Dịch tìm kiếm sự đồng cảm.
Cầm Dịch chỉ hỏi tôi: \"Thế cậu biết cái gì?\"
\"Nó chẳng biết cái gì hết!\" Nghê Giai Giai cướp lời.
\"Tớ biết chứ!\" Tôi uất hận phản bác: \"Tớ biết tết dây hoa thành bướm, thành sao, tớ biết kể rất nhiều chuyện cổ tích...\"
Nghê Giai Giai cười khinh bỉ.
Cầm Dịch lại nhẹ nhàng nói: \"Giỏi thật. Những cái đó tớ đều không biết.\"
Nghê Giai Giai tức giận bỏ về và từ đó không bao giờ sang nữa còn tôi thì tuần nào cũng sang. Sau này gặp Kỷ Thuật ở khu chung cư, cậu ấy đưa lốc sữa dâu làm hòa và muốn quay lại làm bạn. Tôi nhận ra giá trị sự chân thành của bản thân nên từ chối: \"Tớ không giận nữa nhưng tớ không muốn làm bạn với cậu nữa.\"
Thời gian trôi qua, chúng tôi lớn lên. Nghê Giai Giai ngày càng xinh đẹp, luôn là học sinh giỏi đứng top đầu. Tôi trổ mã cao lên, dù vẫn bình thường nhưng không còn so sánh dâu tây với chị ấy nữa.
Cuối tuần tôi vẫn sang nhà Cầm Dịch, từ đồ chơi chuyển thành cùng nhau làm bài tập. Cầm Dịch lột xác thành \"nam thần như đóa hóa trên núi cao\", cao lớn, đẹp trai xuất chúng. Cậu không gọi tôi là Đậu Đậu nữa mà gọi là \"Tiểu Tiểu\". Mỗi lần thấy cậu vứt thư tình sang một bên, tôi lại trêu chọc cậu. Tôi kể cho cậu nghe chuyện trên trường, cậu lặng lẽ chữa lỗi bài tập cho tôi. Dù làm bạn nhiều năm, ở cự ly gần nhìn ngắm gương mặt như đồ sứ của cậu, tôi vẫn phải thốt lên: \"Trời ơi, cậu đẹp trai thật đấy.\"
Cuối năm, hai gia đình ăn cơm chung. Mẹ lại thao thao bất tuyệt báo cáo thành tích của Nghê Giai Giai, khi biết Cầm Dịch thi điểm cao ngất ngưởng và muốn thi vào Trường Nhất Trung, mẹ lại tâng xốc Nghê Giai Giai lên. Cô xinh đẹp quay sang hỏi tôi, tôi hì hì bảo: \"Xem khả năng tới đâu thì cố hết sức tới đó ạ.\"
Mẹ trừng mắt bảo tôi không có chí hướng nhưng cô lại mỉm cười: \"Tôi thấy Tiểu Tiểu thế này rất tốt, chân phương, lạc quan, làm trẻ con cứ vui vẻ khỏe mạnh là quan trọng nhất.\"
Mẹ bị nghẹn lời.
Sau bữa ăn, tôi ra ngoài hít thở không khí lạnh, Cầm Dịch bước ra đi cùng tôi. Dưới ánh đèn đường, cậu đẹp như nam chính phim thanh xuân.
Nghê Giai Giai cũng chạy ra, mặt mũi đỏ bừng, mắt ngấn nước. Chị ấy nhìn Cầm Dịch hạ quyết tâm: \"Kỳ thi lên cấp ba... tớ nhất định sẽ thi thắng cậu!\" Như một lời thách thức đầy tâm tư thiếu nữ.
Cầm Dịch thản nhiên trả lại duy nhất một từ: \"Ồ.\"
Nghê Giai Giai ấm ức khóc bỏ chạy vào trong, tôi đứng bên cạnh cảm thấy gượng gạo. Cầm Dịch quay sang nhìn tôi, bất ngờ nắm lấy hai bàn tay đang xoa vào nhau của tôi đút vào lòng bàn tay ấm áp của cậu: \"Rõ ràng là rất lạnh.\"
Tôi giật bắn người, nghĩ thầm tên này khai sáng tình cảm chậm hơn cả chuột lang, chắc lại coi mình là Đậu Đậu rồi.
Kỳ thi cấp ba kết thúc. Nghê Giai Giai điểm cao chót vót vào lớp chuyên Trường Nhất Trung. Tôi tra điểm, may mắn vừa đủ điểm sàn cũng đỗ vào Trường Nhất Trung. Mẹ bảo tôi \"ăn may\", còn Nghê Giai Giai thì sa sầm nét mặt.
Lên cấp ba, Nghê Giai Giai là hoa khôi lớp chuyên, Cầm Dịch ở tòa nhà bồi dưỡng học sinh giỏi. Bố mẹ Cầm Dịch thuê xe đưa đón cậu và cho tôi đi nhờ. Lần đầu đi xe, tôi ngồi hàng ghế sau với Cầm Dịch nói chuyện líu lo, Nghê Giai Giai lên sau đành ngồi ghế phụ. Trước khi xuống xe, Cầm Dịch đưa cho tôi túi đồ ăn sáng gồm sữa và bánh mì, Nghê Giai Giai sập cửa xe rầm một cái.
Hôm sau Nghê Giai Giai giành ngồi ghế sau trước, tôi đành ngồi ghế phụ. Cầm Dịch lại rướn người qua ghế đưa cho tôi cà phê và bánh macaron, Nghê Giai Giai lại sập cửa xe mạnh hơn. Ngày thứ ba, chị ấy chủ động ngồi ghế phụ luôn.
Trong trường bắt đầu đồn đại Cầm Dịch và Nghê Giai Giai là \"thanh mai trúc mã\" đi học cùng xe. Vì tôi quá bình thường nên hoàn toàn bị ngó lơ trong lời đồn đoán ấy. Nghê Giai Giai cũng mập mờ không phủ nhận.
Năm lớp 12, áp lực đè nặng, bố mẹ dồn hết tâm trí cho Nghê Giai Giai. Nghê Giai Giai trở nên lo âu, rụng tóc, hay khóc đêm, bố mẹ quây quần dỗ dành chị ấy. Tôi nửa đêm đi vệ sinh nhìn thấy cảnh đó, lòng bình thản như nước. Tôi sớm chấp nhận bố mẹ chia bánh ngọt luôn cho chị ấy phần to nhất rồi.
Kỳ thi đại học kết thúc, Cầm Dịch đã được tuyển thẳng từ trước. Tôi làm bài khá tốt. Bố mẹ đón Nghê Giai Giai ở cổng trường, hớn hở tưởng tượng chuyện chị ấy đỗ thủ khoa. Tối đó tôi đi tiệc liên hoan với lớp.
Tiệc KTV buổi tối, tôi sang phòng lớp Nghê Giai Giai tìm chị ấy vì chị ấy không mang chìa khóa nhà.
Vừa vào phòng, bạn thân của Nghê Giai Giai lập tức sỉ vả tôi trước mặt bao người: \"Cậu chính là Nghê Tiểu Tiểu à? Trông hiền lành mà làm tiểu tam rành rọt thế. Biết chị cậu thích Cầm Dịch còn mặt dày bám lấy người ta, đồ trà xanh!\"
Một nam sinh cũng mắng tôi ghen tị với chị gái, Nghê Giai Giai ngồi ở góc sofa cúi đầu im lặng. Tôi bình tĩnh gọi điện cho Cầm Dịch mở loa ngoài, bảo cậu đến.
Cầm Dịch chạy xộc vào phòng, thở hổn hển. Cậu đính chính ngay trước mặt tất cả mọi người: \"Tôi với Nghê Giai Giai đến một câu cũng chưa từng nói. Yêu nhau bằng ý niệm à? Đi xe chung là vì tiện đường đưa đón Nghê Tiểu Tiểu. Tôi chỉ thân với một mình cậu ấy thôi.\"
Mọi người chuyển sang nhìn Nghê Giai Giai bằng ánh mắt khinh bỉ. Bọn họ phải lí nhí xin lỗi tôi. Tôi lạnh lùng bỏ đi, Cầm Dịch đuổi theo sau.
Đi dưới ánh đèn đường, tôi nhận ra Nghê Giai Giai luôn ghen tị với tôi.
Cầm Dịch đi bên cạnh đột nhiên dừng lại, tai đỏ bừng bừng, lấy hết can đảm nói: \"Tớ thích cậu.\"
Tôi ngẩn ngơ: \"Ồ... cảm ơn cậu. Hơi đột ngột quá, để tớ về tiêu hóa đã.\" Cầm Dịch tủi thân ấm ức gật đầu.
Vừa về đến dưới chung cư, Nghê Giai Giai đứng trong bóng tối bước ra, mắt sưng húp gào lên: \"Mày đắc ý rồi chứ? Chị xinh đẹp, thông minh hơn mày, tại sao trong mắt cậu ấy chỉ có mày? Mày dựa vào cái gì?\"
Tôi nhìn chị ấy vạch rõ quan điểm: \"Thế giới này không quay quanh một mình chị. Không phải cứ đẹp hơn, thông minh hơn là có được tất cả. Thích một người không phải là cuộc thi. Chị đừng coi những thứ em có nghiễm nhiên thuộc về chị.\"
Ngày biết điểm thi đại học, tôi thi được điểm cao hơn Nghê Giai Giai 2 điểm, bố mẹ vô cùng kinh ngạc. Trong tiệc ăn mừng, cô giáo chủ nhiệm khen ngợi tôi hết lời khiến bố mẹ gượng gạo vì bấy lâu nay ngó lơ tôi. Cô giáo dặn tôi: \"Nếu bố mẹ cho không đủ yêu thương, em nhất định phải tự yêu lấy chính mình.\"
Bố mẹ mua tặng hai chị em hai sợi dây chuyền giống nhau để bù đắp, tôi từ chối thẳng thừng vì tôi chưa bao giờ đeo dây chuyền.
Ngày nhập học, tôi chọn một trường đại học rất xa. Mẹ muốn hướng dẫn Nghê Giai Giai xếp đồ nhưng chị ấy gào lên trách mẹ thiên vị tôi. Mẹ sang phòng tôi, thấy tôi đã tự gấp gọn vali từ lâu, ngập ngừng không nói thành lời.
Lên đại học, tôi sống vô cùng rực rỡ, bận rộn làm thêm, tham gia câu lạc bộ, giành học bổng. Tôi và Cầm Dịch học cùng thành phố và chính thức yêu nhau.
Năm thứ hai, bố mẹ Cầm Dịch sang chơi, họ đối xử với tôi cực kỳ ấm áp, tôn trọng và yêu thương. Mẹ tôi thấy ảnh trên mạng bèn gọi điện khóc lóc xin lỗi tôi: \"Mẹ xin lỗi Tiểu Tiểu... Mẹ biết mẹ và bố con đã sai khi ngó lơ con... Con cho mẹ cơ hội sửa sai được không?\"
Lời xin lỗi tôi chờ đợi suốt cả tuổi thơ giờ đây hạ xuống thật nhẹ hẫng. Tôi bình thản đáp: \"Con không tha thứ cho bố mẹ đâu. Bố mẹ nuôi con khôn lớn, nghĩa vụ phụng dưỡng sau này con không thiếu một xu. Con không hận bố mẹ nhưng cũng không cách nào yêu bố mẹ được nữa. Đừng diễn kịch gia đình hòa thuận nữa, con đau khổ mười mấy năm rồi, không muốn đau khổ thêm nữa.\"
Tôi cúp máy.
Cầm Dịch bước ra ban công ôm lấy tôi từ phía sau, mùi hương thanh mát xoa dịu mọi vết thương trong lòng tôi.
Cậu cúi đầu nhìn tôi bằng ánh mắt đong đầy tình yêu: \"Khó chịu lắm à?\"
\"Không, thấy nhẹ nhõm hơn nhiều.\" Tôi mỉm cười nhìn cậu: \"Hồi nhỏ em từng ước sau này lớn lên sẽ gặp được một người thích em hơn Nghê Giai Giai.\"
Ánh mắt Cầm Dịch vô cùng dịu dàng: \"Em làm được rồi, đã có rất nhiều người thích em.\" Cậu hơi mím môi hỏi thêm: \"Thế anh có phải là người đầu tiên không?\"
Tôi cười rạng rỡ kéo dài giọng: \"Tất nhiên... không phải rồi!\"
Cầm Dịch cảnh giác: \"Là ai?\"
Tôi cười đáp: \"Tất nhiên là... Nghê Tiểu Tiểu rồi!\"
(Toàn văn hoàn)
', 'Chương 4', '', 'publish', 'open', 'closed', '', 'chuong-4-chuong-4', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-4-chuong-4/', '0', 'chuong', '', '0'),
('77', '0', '2026-10-10 22:55:54', '2026-10-10 15:55:54', 'Trang Danh Sách Team Dịch', 'Danh Sách Team Dịch', '', 'publish', 'closed', 'closed', '', 'team-dich', '', '', '2026-10-10 22:55:54', '2026-10-10 15:55:54', '', '0', 'https://muopdangcungcovingot.io.vn/team-dich/', '0', 'page', '', '0'),
('78', '0', '2026-10-10 22:55:54', '2026-10-10 15:55:54', 'Trang Tủ Truyện', 'Tủ Truyện', '', 'publish', 'closed', 'closed', '', 'tu-truyen', '', '', '2026-10-10 22:55:54', '2026-10-10 15:55:54', '', '0', 'https://muopdangcungcovingot.io.vn/tu-truyen/', '0', 'page', '', '0'),
('79', '1', '2026-10-12 14:13:02', '2026-10-12 07:13:02', 'Buổi tối sau khi ân ái xong, tôi thiu thiu chìm vào giấc ngủ.
Người chồng vốn không bao giờ thích nói lời ngon ngọt đột nhiên lại buột miệng một câu: \"Vợ ơi, anh yêu em.\"
Tôi mở bừng mắt.
Tông giọng này tôi vô cùng quen thuộc, nó y hệt như những lúc chính tôi làm điều gì thẹn với lòng…
Không khác một chút nào.', 'CHỒNG TÔI ĐỘT NHIÊN TRỞ NÊN KHÁC LẠ, HÓA RA ANH ĐÃ CÓ NGƯỜI KHÁC', '', 'publish', 'open', 'closed', '', 'chong-toi-dot-nhien-tro-nen-khac-la-hoa-ra-anh-da-co-nguoi-khac', '', '', '2026-10-17 11:42:39', '2026-10-17 04:42:39', '', '0', 'https://muopdangcungcovingot.io.vn/truyen/chong-toi-dot-nhien-tro-nen-khac-la-hoa-ra-anh-da-co-nguoi-khac/', '0', 'truyen', '', '0'),
('80', '3', '2026-10-12 15:06:33', '2026-10-12 08:06:33', '', '5e37cb52-4d9d-4658-83a4-74ea54f9d5df', '', 'inherit', 'open', 'closed', '', '5e37cb52-4d9d-4658-83a4-74ea54f9d5df', '', '', '2026-10-12 15:06:33', '2026-10-12 08:06:33', '', '79', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/5e37cb52-4d9d-4658-83a4-74ea54f9d5df.png', '0', 'attachment', 'image/png', '0'),
('81', '2', '2026-10-12 15:36:04', '2026-10-12 08:36:04', '', '4aa25b17-62bf-448f-adc6-8e9a576d753f', '', 'inherit', 'open', 'closed', '', '4aa25b17-62bf-448f-adc6-8e9a576d753f', '', '', '2026-10-12 15:36:04', '2026-10-12 08:36:04', '', '72', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/4aa25b17-62bf-448f-adc6-8e9a576d753f.png', '0', 'attachment', 'image/png', '0'),
('82', '1', '2026-10-12 16:26:53', '2026-10-12 09:26:53', 'Người chồng vốn không bao giờ thích nói lời ngon ngọt đột nhiên lại buột miệng một câu: \"Vợ ơi, anh yêu em.\"
Tôi mở bừng mắt.
Tông giọng này tôi vô cùng quen thuộc, nó y hệt như những lúc chính tôi làm điều gì thẹn với lòng…
Không khác một chút nào.
…
Chồng tôi, Lục Minh Dư, là một người có nhu cầu sinh lý rất lớn.
Thời gian này điều đó lại càng rõ rệt. Không chỉ ngày nào cũng đòi hỏi mà lần nào cũng hành hạ rất lâu. Tôi cứ tưởng anh ta mới chuyển sang bệnh viện mới áp lực lớn, cần sự an ủi về mặt cảm xúc, nên cũng cố gắng đáp ứng.
Nhưng câu nói tối nay...
Đối với một người vốn chẳng bao giờ biết nói lời ngọt ngào như anh ta thì có chút quá bất thường rồi.
Trong màn đêm, tôi quay đầu nhìn người đàn ông đã kết hôn sáu năm bên cạnh.
Không thể phủ nhận, Lục Minh Dư sở hữu một diện mạo rất tốt. Ngũ quan sâu sắc, đường viền hàm dưới hoàn hảo, giữa lông mày và đôi mắt tự mang một vẻ anh ta khí lạnh nhạt, chẳng trách mấy cô y tá nhỏ ở bệnh viện nhìn thấy anh ta đều đỏ mặt.
Lúc này, hơi thở của anh ta đều đặn, đã chìm vào giấc ngủ sâu. Tôi lặng người vài giây, giơ tay cầm lấy điện thoại của anh ta.
Lục Minh Dư là bác sĩ phẫu thuật thẩm mỹ. Ba tháng trước, anh ta vừa nhảy việc từ bệnh viện công sang một cơ sở thẩm mỹ tư nhân, WeChat hầu như toàn là khách hàng nữ.
Ánh sáng màn hình hắt lên mặt tôi, tôi lật xem từng tin nhắn một. Một ảnh đại diện tên là \"Tiểu Oánh\" đã thu hút sự chú ý của tôi.
Trong khung chat chỉ có duy nhất một câu gửi tới lúc hơn 11 giờ đêm nay:
[Ngủ ngoan nha.~]
Tin nhắn hiển thị chưa đọc.
Rõ ràng, đây là một câu đáp lại, những nội dung phía trước đều đã bị xóa sạch. Bấm vào trang cá nhân của cô ta, toàn là bài quảng cáo và các ca phẫu thuật của cơ sở thẩm mỹ mà Lục Minh Dư mới vào làm, không có thêm thông tin nào khác.
Sáng hôm sau, tôi vẫn theo lệ cũ thức dậy lúc 5:45.
Đầu tiên là làm bữa sáng kiểu Tây mà Lục Minh Dư và con gái Ninh Ninh thích ăn, sau đó làm bữa sáng dành cho người tiểu đường cho mẹ chồng, tiếp đến đưa Ninh Ninh đi học, về nhà lại đẩy xe lăn đưa mẹ chồng xuống nhà đi dạo.
Đây là quy trình cố định mỗi buổi sáng của tôi.
Trong thang máy, tôi gặp hai người dì hàng xóm.
Họ liên tục cảm thán:
\"Mẹ Ninh Ninh vừa hiền thục lại chăm chỉ như thế này, cái khu nhà mình ai mà không ngưỡng mộ chứ, chị Quan đúng là có phúc lớn nha!\"
\"Tính nết cô ấy cũng tốt nữa, gặp ai cũng ăn nói nhẹ nhàng, sống chan hòa với tất cả mọi người, mấy năm nay tôi chưa từng thấy cô ấy cáy gắt với ai bao giờ.\"
Tôi mỉm cười dè dặt: \"Dì quá khen rồi ạ.\"
Mẹ chồng híp mắt mỉm cười nắm lấy tay tôi, giọng điệu vừa xúc động vừa tự hào:
\"Cái đứa con dâu này của tôi còn thân thiết hơn cả con trai ruột nữa. Lần trước tôi bị nhồi máu cơ tim, nếu không nhờ nó cố tình đi học cấp cứu từ trước thì cái mạng già này của tôi đã tiêu rồi! Có điều tính nó thật thà quá, trong khu mà có ai ăn hiếp nó thì các chị nhớ giúp đỡ nó chút nhé!\"
\"Chuyện đó là chắc chắn rồi!\"
Buổi chiều, sau khi lái xe đến bệnh viện lấy thuốc cho mẹ chồng xong, tôi đi tới cơ sở thẩm mỹ đó. Đây là lần đầu tiên tôi đến đây.
Trong sảnh lớn xa hoa lộng lẫy, một nhân viên tư vấn đã tiếp đón tôi.
Tôi lấy điện thoại ra cho cô ấy xem ảnh chụp tài khoản WeChat của \"Tiểu Oánh\" chụp hôm qua, mỉm cười hỏi: \"Xin chào, bạn tôi có giới thiệu vị bác sĩ này, cô có thể xem giúp tôi đây là vị nào ở viện mình không?\"
Nhân viên tư vấn nhìn lướt qua, nụ cười lập tức rạng rỡ hẳn lên: \"Khéo quá, chị ấy đang ngồi đằng kia kìa.\"
Tôi nhìn theo hướng ngón tay nhân viên tư vấn chỉ. Một người phụ nữ ăn mặc váy công sở tinh tế đang vừa cười vừa trò chuyện với người khác.
Nhìn từ xa, cô ta trẻ trung xinh đẹp, làn da trắng nõn căng mịn, cử chỉ điệu bộ toát lên vẻ tự tin và thong dong.
Bên tai vang lên giọng nói đắc ý của cô tư vấn: \"Bạn chị giới thiệu không sai đâu, đây là Hạ viện trưởng bên tôi đấy, nhìn trẻ trung đúng không? Chị tuyệt đối không thể ngờ được đâu, chị ấy đã 50 tuổi rồi đấy!\"
Tôi ngẩn người một chút, trợn tròn mắt nhìn nhân viên tư vấn: \"50 tuổi?\"
Năm nay tôi 28 tuổi, Lục Minh Dư 32 tuổi.
Mẹ chồng tháng trước vừa tròn 53 tuổi.
Lục Minh Dư trong chuyện chăn gối có nhu cầu rất lớn, yêu cầu cũng cao.
Đôi khi anh ta bộc bạch cảm thán: \"Khuôn mặt có thẩm mỹ trẻ trung đến mấy thì cũng không che giấu được tuổi tác thực sự của cơ thể, phụ nữ còn trẻ mới là vốn liếng lớn nhất.\"
Tôi rất khó tin anh ta lại có thể xảy ra chuyện gì với một người phụ nữ cùng độ tuổi với mẹ mình.
Xem giờ một chút, đã 4 giờ chiều, đến lúc phải đi đón Ninh Ninh rồi. Tôi chấm dứt sự nghi ngờ vô cớ này, đi về phía bãi đỗ xe.
Điện thoại reo, là mẹ tôi.
Vừa bắt máy, giọng nói chua ngoa gào thét lập tức xộc vào tai: \"Lâm Hiểu, mau chuyển cho tôi năm nghìn tệ ngay, tôi đang cần gấp.\"
\"Mẹ, tháng này con không còn tiền dư nữa rồi.\" Giọng tôi phẳng lặng như mặt hồ mùa đông.
\"Mày ngày nào cũng hầu hạ hai mẹ con nhà nó mà chút tiền này cũng không có?! Dù là ra ngoài làm ô sin cho người ta thì mỗi tháng cũng không chỉ có 5 nghìn tệ chứ! Mày quá thật thà quá vô dụng rồi, đổi lại với chị mày thì tốt biết mấy! Học hỏi sự khôn khéo của chị mày đi!\"
Giọng nói xiên xỏ của chị gái Lâm Lợi vang lên ở bên cạnh: \"Cái này còn cần phải học sao, hai ngón tay đó của em không phải do mẹ chồng em vô tình làm đứt à? Tựa vào cái đó mà tới đòi, mà làm ầm lên chứ! Haizz, đúng là bảo người khờ có phúc khờ, thế mà chị lại không có cái số đó, cái cuộc đời này thật đm bất công!\"
Mẹ tôi lại dùng đủ chiêu vừa đấm vừa xoa một hồi lâu, xác định tôi thực sự không có tiền, mới mắng một câu \"mày đúng là cái thứ quả hồng mềm để người ta nắn bóp\", rồi mới tức giận cúp máy.
Trong lòng tôi không chút gợn sóng, cảnh tượng như thế này đã xảy ra vô số lần rồi.
Khi ngồi vào trong xe, tôi nhìn thấy chiếc SUV màu đen cách đó không xa.
Đây là chiếc xe mới mà Lục Minh Dư vừa mua tháng trước. Anh ta bận công việc, ngoài việc đến cửa hàng 4S lái thử một lần, những việc đàm phán giá cả, lắp đặt phụ kiện phía sau đều do tôi đại diện xử lý. Khi nhân viên bán hàng hướng dẫn cách dùng dữ liệu đám mây của camera hành trình, anh ta đã liên kết với điện thoại của tôi.
Tôi lặng lẽ nhìn chiếc xe vài giây, đeo tai nghe Bluetooth vào, mở ứng dụng màu xanh lá cây đó ra. Sau đó khởi động xe, vừa nghe vừa đi về phía trường học của Ninh Ninh.
Trong tai nghe không có tiếng người, cơ bản đều là tiếng ồn trắng khi lái xe. Đơn điệu nhưng bình yên giống như trái tim tôi vậy.
Dừng đèn đỏ, ánh mắt tôi rơi trên bàn tay đang nắm vô lăng: Ngón giữa và ngón áp út của bàn tay trái rủ xuống mềm ngoặt, cơ bắp hoàn toàn vô lực.
Hồi đó chuyện của tôi và Lục Minh Dư bị bố mẹ chồng phản đối dữ dội. Mẹ chồng tìm tận cửa để dạy dỗ tôi, trong lúc đó tôi bị bà ta đẩy một cái mất thăng bằng, bàn tay bị cuốn vào máy cán bột đang hoạt động. Từ đó về sau tàn tật vĩnh viễn cho đến tận ngày nay.
Mẹ chồng rất áy náy về chuyện này, hễ nhắc tới là vành mắt lại đỏ bừng.
......
Tôi đứng trong đám đông phụ huynh trước cổng trường, khi thấy Ninh Ninh xếp hàng bước ra thì chuẩn bị tháo tai nghe xuống. Trong camera hành trình cũng không có nội dung gì đặc biệt, chẳng qua là mấy cuộc điện thoại Lục Minh Dư gọi khi lái xe, phần còn lại toàn là tiếng ồn khi di chuyển.
Đột nhiên, giọng nói của một người phụ nữ vang lên bất ngờ trong tai nghe.
\"Tiểu Lục, người em không được thoải mái, khu nghỉ dưỡng mình còn đi không?\"
Giọng điệu có sự nũng nịu cố tình nhưng không thể che giấu được sự khàn khàn và dấu vết thời gian.
Tay tôi khựng lại.
Ninh Ninh nhìn thấy tôi, đeo cặp sách vừa nhảy chân sáo vừa chạy về phía tôi. Cùng lúc đó, trong tai nghe truyền đến giọng nói lạnh nhạt mà quen thuộc.
\"Đi chứ, đã hẹn trước rồi mà.\"
Là Lục Minh Dư.
Anh ta khựng lại, hình như khẽ cười một tiếng: \"Khiến em khóc lóc xin tha thì đâu phải chỉ có mỗi một cách.\"
\"Tiểu Lục anh xấu xa thật! Em ngại không dám nghe nữa luôn!\" Giọng người phụ nữ hờn dỗi nhưng lại mang theo sự vui vẻ rõ rệt: \"Nhưng mà mỗi lần nghĩ đến việc anh cũng đối xử với vợ anh như thế, hừ, em lại ghen điên lên được!\"
\"Em sai rồi.\" Lục Minh Dư dùng giọng điệu hư hỏng: \"Cái này là độc quyền của riêng em.\"
\"Mẹ ơi…\"
Ninh Ninh giang hai tay nhỏ xíu âu yếm ôm chầm lấy tôi. Sau đó bé vui mừng hoan hỉ móc từ trong túi ra ba viên kẹo, giơ cao lên: \"Đây là phần thưởng cô giáo cho con, con không ăn đâu, con muốn để dành cho mẹ, bố và bà nội, mỗi người một viên!\"
Tôi mím môi, từ từ ngồi xổm xuống, nở nụ cười hiền từ: \"Ninh Ninh ngoan quá, mẹ cảm ơn Ninh Ninh nhé.\"
Lúc này là tháng Sáu. Thành phố nhỏ ven biển này lại bắt đầu một mùa oi bức dài đằng đẵng.
Nếu nói cuộc đời như một ván bài thì tôi chắc chắn đã bốc phải một quẻ bài cực kỳ, cực kỳ nát.
Bố tôi là dân chơi thôn quê, mẹ tôi là dân chơi ngông nghênh nông nổi. Cả hai bỏ học từ cấp hai, quen nhau trên dây chuyền sản xuất của nhà máy. Mang thai được sáu tháng thì tổ chức một đám cưới sơ sài tại hai gian nhà đất ở quê nhà bố tôi.
Bọn họ sau đó sinh tổng cộng bốn người con. Chị gái Lâm Lợi và tôi, cùng với hai em trai em gái sinh đôi kém tôi sáu tuổi.
Thế giới làm gì có nhiều cổ tích về việc vượt qua giai cấp đến thế, cổng nghèo từ lâu đã khó sinh ra con quý. Những đứa trẻ như chúng tôi cơ bản sẽ lặp lại con đường của bố mẹ: vào nhà máy, kết hôn, sinh con, rồi lại tống con cái vào dây chuyền sản xuất.
Lâm Lợi 17 tuổi bỏ học vào nhà máy làm việc, ở cùng một xưởng làm quen với anh rể sau này là Lý Thành.
Ba năm sau, đến lượt tôi.
Khi tôi dòm ngó thử đề nghị muốn học cấp ba, mẹ tôi đang ở ở trên bàn bài. Bà ấy phát ra tiếng cười to dải và chát chúa, giễu cợt với những người trên bàn bài: \"Nhà chúng ta sắp có sinh viên giỏi rồi nè!\"
Bà ấy thậm chí không thèm nhìn tôi một cái, thậm chí không cho tôi một câu trả lời đàng hoàng.
Tôi vào nhà máy của Lâm Lợi, làm ở dây chuyền sản xuất suốt ba năm.
Một buổi tối nọ, chị ta và Lý Thành dẫn tôi ra ngoài ăn đêm, gán ghép tôi với người đồng hương của Lý Thành tên là Chu Hữu. Chu Hữu là kỹ thuật viên của nhà máy, cao ráo đẹp trai, cư xử chu đáo thấu hiểu, có rất nhiều nữ công nhân thầm thương trộm nhớ anh ấy. Tôi và anh ấy từng ăn cơm vài lần, mỗi lần anh ấy nhìn tôi, đôi mắt như lấp lánh ánh sao, rất sáng. Tôi cũng có thể cảm nhận rõ ràng trái tim mình đập liên hồi.
Tôi biết đó là sự rung động nhưng tôi cũng biết. Ban đầu mẹ tôi và bố tôi, Lâm Lợi và Lý Thành, có lẽ cũng đều như thế này cả.
Tôi dùng sự im lặng để thể hiện lời từ chối.
Mặt Lâm Lợi sa sầm xuống, dùng giọng điệu mắng mỏ tôi như thói quen: \"Lâm Hiểu, mày ngu hay ngốc thế! Mày mà không phải em gái ruột của chị thì Lý Thành có giới thiệu Chu Hữu cho mày không? Chị em mình cùng gả vào một chỗ không tốt sao? Sao mày lại như bùn nhão không trát nổi tường thế hả!\"
Từ nhỏ đến lớn, tôi ở trước mặt chị ta luôn bị đè nén, bị hạ thấp. So với sự cam chịu ngoan ngoãn của tôi, bố mẹ lại thiên vị sự khôn khéo lanh lợi của Lâm Lợi hơn.
Khoảnh khắc đó, chẳng hiểu sao tôi thấy lòng cay đắng, thế là nhìn Lâm Lợi chầm chậm nói: \"Em không muốn sống một cuộc đời giống như chị và mẹ.\"
Lâm Lợi ngẩn người hai giây, một cái tát giáng thẳng vào mặt tôi, sau đó ở giữa quán ăn đêm ồn ào chỉ tay vào mặt tôi mắng chửi xối xả: \"Đồ con đĩ! Mày tưởng mày là ai mà ở đây giở trò trèo cao với chị! Hồi đó nếu không nhờ chị vớt mày dưới ao lên thì mày đã chết đuối từ lâu rồi! Chị đã là chị mày thì có trách nhiệm tát cho mày tỉnh ra!\"
Lý Thành ngậm điếu thuốc, vẻ mặt xem kịch hay. Chu Hữu đứng dậy chắn trước mặt tôi nhưng lại luôn cúi gằm đầu.
Tôi lau vết máu ở góc miệng, không nói một lời quay lưng bỏ đi.
Không lâu sau, Lâm Lợi vác bụng bầu về quê Lý Thành kết hôn.
Tôi xin nghỉ việc ở nhà máy, cầm ba nghìn tệ tích góp cuối cùng, lần đầu tiên tôi bước chân vào một trung tâm thương mại cao cấp, tự mua cho mình một bộ quần áo vừa sang trọng vừa tôn dáng. Sau đó, tôi đến công ty môi giới hôn nhân lớn nhất thành phố này.
Tôi không tự cho mình là ai cả, chỉ là trong lòng đang sục sôi một khao khát mãnh liệt, vô cùng rõ ràng. Tôi không muốn tiếp tục một cuộc đời như thế này nữa, không muốn vài năm sau các em tôi lại lặp lại một cuộc đời như thế này, không muốn con cái tương lai của tôi không thể thoát khỏi một cuộc đời như thế này.
Tôi không có gia cảnh, không có học vấn, không có người chống lưng.
Thứ duy nhất tôi có là khuôn mặt xinh đẹp vượt qua cả gen của bố mẹ và độ tuổi đang thời thanh xuân.
Tôi bắt buộc phải lợi dụng ưu thế duy nhất này đến mức triệt để nhất.
Dựa vào ngoại hình và hình tượng hư cấu, tôi đã gặp hết đối tượng xem mắt chất lượng cao này đến đối tượng khác, cho đến khi gặp được Lục Minh Dư.
Lục Minh Dư hoàn toàn phù hợp với yêu cầu của tôi. Bố là lãnh đạo cơ quan, mẹ là nội trợ, bản thân anh ta là bác sĩ. Gia cảnh khá giả, công việc ổn định, gen tốt.
Trong điều kiện bình thường, tôi chắc chắn không xứng với anh ta, thế là tôi đã sử dụng phương thức không bình thường. Tôi đối chiếu với tiêu chuẩn chọn vợ của Lục Minh Dư, soi từng điều một. Trẻ trung và xinh đẹp là hai thứ duy nhất tôi sở hữu.
Ngoài ra.
Anh ta yêu cầu da trắng, thế là tôi ngày ngủ đêm thức, tuyệt đối không phơi nắng; yêu cầu nấu ăn ngon, tôi ra quán nét thức trắng hai đêm liền, xem video chuyên học các món ăn thành thị và đồ Tây; yêu cầu bằng đại học, tôi làm một cái bằng giả, giả vờ vô tình để anh ta nhìn thấy. Xem mắt không phải là phỏng vấn, anh ta cũng chẳng rảnh đi lên mạng tra cứu thực hư.
Điều cuối cùng, anh ta yêu cầu gia cảnh tương đương.
Về chuyện này, tôi chọn cách thành thật hay nói đúng hơn là sự thành thật nửa thật nửa giả. Dù sao xuất thân gia đình là thứ định sẵn không thể che giấu mãi.
Anh ta quả nhiên do dự, tuy vẫn tiếp tục gặp mặt nhưng có thể thấy rõ ràng là không còn nhiệt tình như trước.
Tôi không hề níu kéo, chủ động đề nghị chia tay, sau đó đổi số điện thoại, biến mất suốt một tháng trời. Trong ký ức của anh ta, tôi đã để lại hình ảnh một đóa hoa bạch liên kiên cường gần bùn mà chẳng hôi tanh mùi bùn.

', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1/', '0', 'chuong', '', '0'),
('83', '1', '2026-10-12 16:28:40', '2026-10-12 09:28:40', 'Vào ngày sinh nhật anh ta, tôi và anh ta \"tình cờ gặp nhau\" bên bờ hồ. Anh ta mừng rỡ ôm chầm lấy tôi, như thể vừa tìm lại được bảo vật đã mất. Vào khoảnh khắc lửa gần rơm sắp bùng cháy, tôi đẩy anh ta ra, sụt sùi khóc nói gia đình anh ta sẽ không đồng ý đâu, chi bằng dừng lại ở đây thôi.
Lục Minh Dư mắt đỏ ngầu gầm lên: \"Anh yêu em! Yêu chính con người em, chứ không phải gia đình em! Cho dù em có là ăn mày anh vẫn yêu em!\"
Đêm hôm đó, khi Lục Minh Dư hết lần này đến lần khác đòi hỏi tôi, tôi không hề cảm thấy hổ xấu, cũng không thấy tủi thân. Tôi chỉ cảm thấy may mắn, may mà một kẻ u tối như tôi vẫn còn có giá trị.
Trên bục giảng ở quê, người thầy giáo làng dùng thứ tiếng Phổ thông ngọng nghịu dạy chúng tôi đạo lý làm người, bảo rằng có làm thì mới có ăn, phải thành thật, phải chính trực, phải làm một người tốt đàng hoàng sống dưới ánh mặt trời.
Nhưng không có bóng tối, thì lấy đâu ra ánh sáng?
Nơi sâu thẳm dưới đáy biển sâu không một gợn ánh sáng vẫn có những sinh vật đang gồng mình sống tiếp.
Tôi xách dưa hấu và thức ăn bước vào nhà.
Mẹ chồng vừa thấy lập tức lo lắng kêu lên: \"Lâm Hiểu, tay con không xách đồ nặng được đâu, mau đặt xuống đi.\"
Ninh Ninh đang vui vẻ đưa kẹo cho bà nội, vừa nghe vậy thì vội vã chạy lại ngồi xổm xuống, dùng miệng liên tục thổi thổi vào tay tôi, giọng nói mềm mại đầy áy náy: \"Mẹ ơi, con quên mất tay mẹ bị thương rồi, mẹ ơi mẹ có đau không ạ, đợi Ninh Ninh lớn lên là có thể giúp mẹ rồi!\"
Tôi xoa đầu nhỏ của Ninh Ninh, trong lòng ngập tràn sự bình yên.
Ninh Ninh từ nhỏ đã ấm áp và ngoan ngoãn. Tôi thường xuyên vì có con bé mà thêm yêu thế giới này.
Mẹ chồng nhìn cảnh tượng ấm áp của hai mẹ con tôi, vành mắt đỏ lên: \"Cũng tại mẹ hồi đó quá bồng bột, nếu biết con là người con dâu tốt như thế này thì kiệu tám người khiêng đi đón con còn không kịp ấy chứ...\"
Tôi mỉm cười: \"Mẹ ơi, đều là chuyện đã qua rồi.\"
Tôi không định nói chuyện Lục Minh Dư ngoại tình cho mẹ chồng biết.
Bốn năm trước, bố chồng bị phát hiện ngoại tình với bạn thân của mẹ chồng nhiều năm, con riêng chỉ kém Lục Minh Dư ba tuổi. Sau khi bố chồng kiên quyết ly hôn, mẹ chồng bị bệnh nặng một trận, từ đó hận xương hận tủy những kẻ đàn ông ngoại tình. Nếu bà ta biết người con trai mà bà ta luôn tự hào cũng đi vào vết xe đổ của người chồng, hơn nữa tuổi tác của đối tượng ngoại tình lại bằng tuổi bà ta, e là không chịu nổi đả kích này.
Còn về phần tôi, ời dối trá đổi lấy lời dối trá, rất công bằng.
Tôi chưa bao giờ là đối tượng được ông trời ưu ái. Những thứ hiện tại có được, cũng chẳng qua là do tôi cưỡng cầu mà có.
Nhưng mấy năm sau khi kết hôn, tôi tự vấn đã làm tốt vai trò của một người vợ chu đáo, một người con dâu hiền thục, một người mẹ dịu dàng, không có lỗi với bất kỳ ai. Bao gồm cả chính bản thân tôi.
Tôi đã học đại học từ xa ngành tài chính kế toán, thi lấy chứng chỉ, nhờ mối quan hệ của bố chồng để vào một công ty công trình làm kế toán bán thời gian. Mỗi tháng đến văn phòng vài ngày, lương 8.000 tệ.
Khi bố mẹ bắt các em tôi nghỉ học, tôi đã quăng tiền ra phản đối, gánh vác toàn bộ chi phí học cấp ba và đại học của chúng.
Con gái Ninh Ninh lớn lên khỏe mạnh, đáng yêu lại tự tin phóng khoáng, không một chút nào giống với tôi thời thơ ấu.
Dù sao đi nữa, thứ tôi muốn cũng đã đạt được rồi. Cái giá phải trả đương nhiên cũng phải chấp nhận nhưng đối với việc cầm bài xấu trong tay, tôi ít nhiều cũng đã có kinh nghiệm.
Biết rằng có đôi khi cần phải liều mạng ù bài, có đôi khi phải chủ động bỏ bài hòa. Còn có đôi khi, là phải tĩnh tâm chờ đợi.
Tối hôm đó khi Lục Minh Dư lại luồn tay vào trong áo tôi, tôi đã đẩy anh ta ra: \"Em đến kỳ rồi.\"
Anh ta suy nghĩ một chút: \"Không phải em còn mười ngày nữa mới đến sao?\"
\"Chắc là do bị cảm uống thuốc nên kinh nguyệt hơi rối loạn một chút.\"
Anh ta nhíu mày vài giây, đè tôi xuống: \"Thế em giúp anh đi.\"
Tôi bịt miệng ho sặc sụa một trận dữ dội: \"Đừng mà chồng, công việc của anh ta bận rộn như thế, nhỡ lây sang cho anh thì không tốt đâu.\"
Anh ta thở dốc mấy hơi thô hển, đành ngậm ngùi đứng dậy đi tắm nước lạnh.
Bệnh tiểu đường của mẹ chồng trở nặng, bác sĩ nói phải cắt bỏ hai ngón chân. Phẫu thuật không lớn nhưng lại đúng lúc Lục Minh Dư nói đi công tác tu nghiệp, thế là ban ngày tôi ở bệnh viện chăm sóc mẹ chồng, buổi tối về nhà với Ninh Ninh.
Mẹ chồng rất áy náy: \"Lâm Hiểu, lại khiến con phải vất vả rồi.\"
Tôi mỉm cười ôn hòa bảo đó là việc nên làm.
Chiều hôm đó, tôi đang ngồi bên giường gọt táo thì mẹ chồng xoa xoa thái dương nói với tôi: \"Mẹ cứ thấy trong bệnh viện lạnh lạnh, làm mẹ đau hết cả đầu, con về nhà một chuyến lấy giúp mẹ cái áo khoác đi.\"
Khi đi xuống dưới lầu, tôi nhớ ra trong cốp xe có một chiếc áo khoác mẹ chồng từng dùng trước đây, thế là lấy chiếc đó đi lên lầu.
Vừa tới cửa phòng bệnh, tôi nghe thấy bên trong truyền ra giọng nói của một người phụ nữ. Tôi tưởng là bạn của mẹ chồng đến thăm bà ta nhưng lại nghe thấy người phụ nữ đó cất tiếng gọi: \"Mẹ!\"
Tôi dừng bước, ngay sau đó, giọng nói thân thiết của mẹ chồng vang lên: \"Tiểu Oánh à, công việc của con bận rộn như thế, sao lại để con phải cất công đến thăm mẹ chứ!\"
Tôi đột nhiên hiểu ra người phụ nữ đó là ai.
Lục Minh Dư cũng ở đó, anh ta thong thả tiếp lời: \"Sau này đều là người một nhà, Hạ Oánh đến thăm mẹ là điều nên làm mà.\"
Giọng mẹ chồng mang vẻ trách móc: \"Minh Dư, thế này là con không đúng rồi! Dù sao Tiểu Oánh cũng đang mang thai, con phải chăm sóc cô ấy chu đáo mới phải, bên mẹ không cần hai vợ chồng con phải bận tâm đâu.\"
\"Mẹ ơi, Minh Dư đối xử với con tốt lắm, lần này chính là vì anh ấy thấy con bị ốm nghén nên mới cố tình đưa con đến khu nghỉ dưỡng tĩnh dưỡng mấy ngày đấy ạ.\" Hạ Oánh mỉm cười đáp lời, giọng nói cố tình tỏ ra dịu dàng nũng nịu.
Trong phòng bệnh là một khung cảnh vui vẻ hòa thuận. Tôi ở ngoài phòng bệnh, quay đầu nhìn ra ô cửa sổ ở cuối hành lang.
Ánh nắng có chút chói mắt, tôi hơi nheo mắt lại.
Hạ Oánh bước ra khỏi phòng bệnh nghe điện thoại, quay lưng về phía tôi đi ra xa.
Bên trong, Lục Minh Dư thở dài một tiếng, hạ thấp giọng xuống chút: \"Dù sao cũng vẫn có chút không lỡ bỏ Lâm Hiểu…\"
Mẹ chồng ngắt lời anh ta: \"Con tranh thủ trước khi ly hôn ăn cho đã ăn cho chán là được chứ gì? Bố con với cái con mụ già đó giờ sống sung sướng nở hoa, con bắt buộc phải làm nên trò trống để đè bẹp cái đứa con riêng đó!\"
\"Mẹ, cái này con biết. Chỉ là nếu Lâm Hiểu ly hôn đi rồi, sợ là không tìm được ai khiến mẹ hài lòng để hầu hạ mẹ.\"
Mẹ chồng hừ nhẹ một tiếng: \"Thế thì làm cho nó không đi nổi. Lâm Hiểu là cái đứa tính nết nhu nhược không có chính kiến, mấy năm nay mẹ đã nắm thóp nó rất tốt rồi, lại thêm nắm giữ Ninh Ninh trong tay, đến lúc đó không chừng nó còn phải van xin làm bảo mẫu miễn phí cho mẹ ấy chứ.\"
Lục Minh Dư im lặng một lúc rồi mở lời: \"Hạ Oánh nói muốn Ninh Ninh đi theo cô ấy, cô ấy mang thai con trai, muốn có một đứa con gái ở bên cạnh nuôi dưỡng về sau nương tựa. Đợi Ninh Ninh lớn hơn chút nữa thì cũng có thể phụ giúp chăm sóc em trai.\"
Mẹ chồng cười: \"Con bảo cô ấy đừng vội, cứ để Ninh Ninh học hỏi theo mẹ nó nhiều vào, con gái làm việc nhà cũng phải bắt đầu học từ từ lúc nhỏ...\"
Phía bên kia, Hạ Oánh hình như đã nghe xong điện thoại.
Tôi quay người đi về phía sau. Một người mẹ dắt theo đứa con khi lướt qua vai tôi, tay cầm bình giữ nhiệt không vững, bình nước bị nghiêng. Tôi lập tức lấy tay đỡ lấy đầu đứa trẻ, nước nóng bỏng giội lên tay tôi bốc khói nghi ngút.
Hai mẹ con giật mình liên tục cảm ơn tôi. Đứa trẻ hỏi tôi: \"Dì ơi, dì có đau không?\"
Tôi mỉm cười lắc đầu. Không tính là đau, còn lâu mới đau bằng ngày hôm đó khi tôi chủ động đút tay mình vào máy cán bột.
Kinh Thánh có câu:
[Vì ai đã có, thì sẽ được cho thêm; còn ai không có, thì ngay cả cái đang có cũng sẽ bị tước mất.]
Tôi từng thầm nghĩ rằng, sự hy sinh hết lòng của mình trong mấy năm qua có lẽ có thể bù đắp lại sự cưỡng cầu và thiếu đàng hoàng trước đây nhưng hóa ra ông trời không tính toán như vậy. Ông trời chỉ thích thêu hoa trên gấm, chứ không chịu đưa than trong ngày tuyết rơi.
Cho nên ông trời không dựa vào được, vẫn phải dựa vào chính mình.
Đây là một thế giới mà kẻ thắng sẽ ăn tất.
...
Mấy ngày tiếp theo gió êm sóng lặng, mọi thứ như thường. Chỉ là buổi tối, tôi không còn cho Lục Minh Dư chạm vào mình thêm một lần nào nữa, lý do hết cái này đến cái khác nhưng đều rất hợp lý.
Sắc mặt anh ta ngày càng trở nên bực bội, cáu kỉnh. Tôi lại ngày càng ngủ ngon hơn.
Cho đến ngày hôm nay. Tôi vừa đón Ninh Ninh về đến nhà thì thấy Lục Minh Dư hôm nay lại đi làm về sớm, mặt mày sa sầm ngồi trên ghế sofa, môi mím chặt thành một đường thẳng. Mẹ chồng ngồi một bên than ngắn thở dài.
Tôi rủ mắt, cúi người nói với Ninh Ninh: \"Mẹ có mua một con búp bê công chúa Elsa, để ở tủ đồ chơi trong phòng con ấy, con tự vào mở ra xem nhé.\"
Ninh Ninh reo lên vui vẻ \"Cảm ơn mẹ\", không chờ nổi liền chạy tót vào phòng mình.
Tôi chầm chậm đứng dậy, vừa quay người lại. Một xấp ảnh vứt thẳng vào mặt tôi, tung bay lả tả rơi đầy trên mặt đất.
\"Lâm Hiểu!\" Lục Minh Dư trừng mắt nhìn tôi, mặt đầy giận dữ: \"Tôi vất vả cực nhọc ra ngoài kiếm tiền nuôi gia đình, cô lại lén lút sau lưng tôi làm ra cái loại chuyện gớm ghiếc này! Thằng gã đó là ai!\"
Mẹ chồng vẻ mặt lo lắng, cất giọng khuyên nhủ: \"Con đừng cuống lên thế, Lâm Hiểu là đứa trẻ ngoan, có thể là có nỗi khổ gì đó. Lâm Hiểu, con giải thích rõ ràng cho tốt là được rồi, đừng sợ, có mẹ chống lưng cho con!\"
Tôi cúi người nhặt vài tấm ảnh dưới đất lên. Đây là góc chụp từ bên ngoài cửa sổ, qua ô cửa sổ, một đôi nam nữ đang ôm nhau thân mật trên giường khách sạn. Người nam quay lưng lại, còn người nữ thì lộ rõ mặt, rõ ràng chính là diện mạo của tôi.
Tôi lấy tay che miệng, thốt lên tiếng kinh hãi: \"Chồng ơi, mấy bức ảnh này ở đâu ra thế, sao bên trong lại có em?\"
\"Cô thừa nhận đó là cô rồi đúng không?\"
Lục Minh Dư nghiến răng nghiến lợi, sắc mặt u uất: \"Lâm Hiểu, cô làm tôi quá thất vọng rồi! Tôi đối xử với cô tốt như thế, cô lại dám cắm sừng tôi, khiến tôi phải chịu loại nhục nhã này!\"
Tôi hoảng hốt giải thích: \"Em không có, em bị oan mà, đây có thể là một người phụ nữ trông giống em, hoặc là có ai đó dùng ảnh AI để cố tình hãm hại em!\"
\"Cô còn ngụy biện! Tôi đã hỏi qua rồi, tuy ảnh in ra không thể xác định có phải AI hay không, nhưng bộ quần áo dưới đất là bộ cô thích mặc nhất đúng không? Còn nữa, ai lại cố tình hãm hại cô chứ, cô nói ra một đối tượng nghi vấn xem, tôi lập tức báo cảnh sát!\"
Tôi ngơ ngác ngẩn ra một lúc, miệng há ra rồi lại khép lại, cuối cùng rụt rè nói: \"Em... em không biết ai hãm hại em. Minh Dư, anh ta báo cảnh sát đi, để cảnh sát đi bắt người chụp ảnh…\"
\"Cô không chê mất mặt chứ tôi chê mất mặt!\" Lục Minh Dư giận dữ ngắt lời tôi: \"Cái lũ này chuyên môn rình ở khách sạn chụp ảnh để tống tiền, muốn bắt thì đã bắt từ lâu rồi! Tôi phải bỏ ra 50 nghìn tệ để mua đứt đống ảnh này là vì nể tình nghĩa vợ chồng, nể mặt Ninh Ninh đấy!\"
Anh ta đá văng chiếc bàn trà cạnh sofa, hầm hầm sập cửa đi ra khỏi nhà. Tôi đứng ngơ ngác tại chỗ, vẻ mặt bất lực và bàng hoàng.
\"Mẹ tin con.\" Mẹ chồng lên tiếng: \"Mẹ tin con tuyệt đối không phải loại người đi ngoại tình.\"
Nước mắt tôi trào ra, như thể chộp được cọc nhọn cứu mạng nhìn mẹ chồng: \"Mẹ ơi, mẹ thật sự tin người phụ nữ trong ảnh không phải là con sao?\"
Nhưng mẹ chồng lại không trả lời trực tiếp câu hỏi này, vài giây sau mới ấp úng nói: \"Con có nhớ hai tháng trước con đi cùng mẹ đến tiệc thọ của dì Trần, vì uống rượu thay mẹ nên bị say, phải sang khách sạn nhỏ bên cạnh thuê phòng ngủ trước không?\"
Tôi ngẩn ngơ gật đầu.
Trên mặt mẹ chồng hiện lên nét kỳ quái: \"Sau đó mẹ và dì Trần đến tìm con thì phát hiện cửa phòng không khóa. Con ngủ say như chết trên giường, quần áo xộc xệch. Minh Dư bảo mấy kẻ xấu đó chuyên rình ở khách sạn tống tiền, liệu có phải họ thấy con là phụ nữ uống say vào thuê phòng nên sinh lòng tà ma...\"
\"Mẹ, ý mẹ là có người thừa lúc con say rượu, đã làm gì con…\"
Mẹ chồng nhìn tôi bằng ánh mắt thương cảm và đau đớn, từ từ gật đầu. Tôi ngẩn ngơ trong giây lát, lộ ra vẻ mặt tuyệt vọng và kinh hoàng.
', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2/', '0', 'chuong', '', '0'),
('84', '1', '2026-10-12 16:29:46', '2026-10-12 09:29:46', 'Lục Minh Dư bỏ nhà đi năm ngày sau thì trở về, đưa ra đề nghị ly hôn.
\"Tôi đã dành năm ngày để bình tĩnh lại và đưa ra một quyết định đau đớn. Tôi không muốn truy cứu gã đàn ông bên ngoài của cô là ai nữa, cũng chẳng buồn biết cô là đã lên kế hoạch từ trước hay là sự cố tình một đêm, cô đã chạm vào giới hạn của tôi, chúng ta chỉ có thể ly hôn.\"
Mẹ chồng can ngăn: \"Minh Dư, nếu thật sự là sự cố ngoài ý muốn thì Lâm Hiểu cũng là nạn nhân mà. Mấy năm nay nó không có công lao thì cũng có khổ lao, con có thể suy nghĩ lại một chút không.\"
\"Tôi có bệnh sạch sẽ.\" Lục Minh Dư lạnh lùng thốt ra vài từ, bước vào phòng làm việc rồi \"Rầm\" một tiếng đóng sầm cửa lại.
Mẹ chồng thở dài thượt, quay sang tôi lau nước mắt: \"Lâm Hiểu, bất luận Minh Dư có tin con hay không, con mãi mãi là đứa con dâu tốt của mẹ.\"
Tôi cúi đầu gọt táo cho Ninh Ninh, không nói thêm một lời nào.
Ngày hôm sau.
Tôi không đưa Ninh Ninh đến trường mầm non, để lại một tờ giấy ghi \"Ra ngoài bình tĩnh lại vài ngày\" rồi dắt Ninh Ninh lên máy bay, bay tới Hải Thành cách xa hàng ngàn dặm.
Em trai và em gái tôi đang học đại học ở đây. Để tiết kiệm tiền cho tôi, kỳ nghỉ hè và nghỉ đông hai đứa đều đi làm thêm, hai năm mới về nhà một lần, chúng tôi đã rất lâu rồi không gặp nhau.
Vừa nhìn thấy hai đứa ánh mắt tôi đã cay xè. Giữa đô thị phồn hoa thời thượng này, hai anh em mặc những chiếc áo phông đơn sơ cũ kỹ, sau lưng vẫn đeo chiếc balo cũ tôi mua cho từ hồi mới vào đại học nhưng cả hai ăn mặc sạch sẽ, tính cách cởi mở, nụ cười rạng rỡ. Chính là dáng vẻ mà tôi đã liều hết sức mình muốn nâng đỡ, chắp cánh cho.
Chúng tôi xúc động và vui mừng ôm chầm lấy nhau, vừa cười vừa rơm rớm nước mắt.
Mấy ngày sau đó, tôi dẫn hai đứa lần đầu tiên đi ăn Haidilao, lần đầu tiên leo lên tháp quan sát, lần đầu tiên đi công viên giải trí, giống như vô số những người bình thường khác ở thành phố này.
Lúc chia tay, em gái nghiêm túc hỏi tôi: \"Chị Hai, Ninh Ninh vẫn chưa nghỉ hè mà, chị đến vào lúc này có phải là có ai bắt nạt chị không?\"
Em trai đang dẫn Ninh Ninh chơi ở bên cạnh cũng đưa ánh mắt trầm ngâm nhìn sang.
Tôi mỉm cười, ôn hòa nói với hai đứa: \"Không có ai bắt nạt chị cả, chị chỉ là nhớ người nhà thôi, cũng muốn cho Ninh Ninh biết rằng bé có cậu nhỏ, có dì nhỏ, bé không phải chỉ có mỗi một mình mẹ.\"
Khi về đến nhà bước vào cửa, mẹ chồng nhìn thấy thì tôi ngẩn người ra một chút, sau đó cất tiếng gọi chất chứa tình cảm chân thành, thậm chí còn có phần tủi thân: \"Lâm Hiểu, con của mẹ, cuối cùng con cũng về rồi!\"
Bà ta dồn dập giải bày với tôi mấy ngày qua bà ta đã sống khổ sở thế nào, cơm ăn không ngon, ngủ không yên giấc, đã mấy ngày rồi không được xuống lầu hít thở không khí trong lành.
Một tiếng sau, Lục Minh Dư cũng vội vã trở về. Anh ta nhìn thấy tôi ánh mắt trong thoáng chốc trở nên phức tạp nhưng ngay lập tức sa sầm mặt xuống: \"Cô nói đi là đi, từ bao giờ lại trở nên xốc nổi, tùy hứng như thế hả!\"
Tôi thản nhiên hỏi ngược lại: \"Anh bỏ nhà đi năm ngày để bình tĩnh, em cũng bỏ nhà đi năm ngày để bình tĩnh, chẳng lẽ thế không công bằng sao?\"
Anh ta hừ lạnh: \"Thế rồi sao? Bình tĩnh thế nào rồi?\"
Tôi im lặng vài giây: \"Em đồng ý ly hôn.\"
Hai mẹ con đồng thời ngẩn ngơ. Bọn họ rõ ràng không lường trước được việc tôi lại đồng ý nhanh ta đến như vậy. Trên khuôn mặt Lục Minh Dư thậm chí còn thoáng hiện vài phần hụt hẫng ẩn giấu.
\"Tuy nhiên, em có một điều kiện.\"
\"Điều kiện gì?\" Mẹ chồng lập tức hỏi, giọng điệu cảnh giác.
Vành mắt tôi từ từ đỏ lên: \"Mấy ngày qua con rất đau khổ, đã suy nghĩ rất nhiều. Mọi chuyện đã phát triển đến bước này, con và Minh Dư chắc chắn không thể quay lại được nữa... Điều con không yên tâm nhất chính là Ninh Ninh. Thế nên con đồng ý ly hôn, điều kiện là tài sản nhà cửa phải chuyển hết sang tên Ninh Ninh.\"
Trong nhà hiện tại có hai căn nhà, đều đứng tên Lục Minh Dư. Một căn là nhà cũ trước khi kết hôn, căn còn lại là căn hộ chung cư cao cấp hiện đang ở. Hồi đó mẹ chồng ép bố chồng phải ra đi tay trắng, yêu cầu bố chồng đưa ra chính là các căn nhà đều chuyển sang tên Lục Minh Dư.
Tôi chỉ là học theo cách cũ mà thôi.
Lục Minh Dư nheo mắt nhìn tôi, biểu cảm của mẹ chồng biến đổi mấy hồi, dường như đang phán đoán mục đích đằng sau câu nói này của tôi. Cả hai người trong phút chốc đều không cất lời.
Một lúc lâu sau, Lục Minh Dư hỏi: \"Thế còn quyền nuôi dưỡng Ninh Ninh thì sao?\"
Tôi mở to mắt, nhìn anh ta bằng vẻ kỳ lạ: \"Ninh Ninh đương nhiên là đi theo em.\"
Lục Minh Dư nhìn xoáy vào tôi vài giây, chậm rãi lên tiếng: \"Nhưng hộ khẩu của cô không ở thành phố này, Ninh Ninh sắp sửa vào tiểu học rồi, quyền nuôi con thuộc về cô sẽ gặp đủ loại bất tiện.\"
Tôi phản ứng gay gắt: \"Ninh Ninh từ nhỏ đã theo em, chúng em không thể tách rời, nếu anh không đồng ý thì em không ly hôn nữa, ngày mai em dắt Ninh Ninh ra ngoài ở!\"
\"Thế không được, cô đi rồi thì tôi tính sao?\" Mẹ chồng thốt ra theo phản xạ.
Lục Minh Dư nhíu mày rất lâu, giọng điệu dịu xuống một chút: \"Thế này đi, vì tương lai của Ninh Ninh, trên danh nghĩa quyền nuôi con thuộc về tôi, cô và Ninh Ninh vẫn ở lại đây, tôi trả tiền chu cấp, mọi thứ của hai người sẽ không có gì thay đổi, tôi sẽ chuyển ra ngoài ở.\"
Tôi ngập ngừng: \"Thế chuyện sang tên nhà...\"
Lục Minh Dư thở dài một tiếng: \"Tuy rằng thủ tục chuyển nhượng hơi rắc rối một chút, nhưng xuất phát điểm của cô cũng là vì con gái, cứ theo ý cô mà làm.\"
Đêm đến, tôi ra bếp rót nước lại nghe thấy tiếng nói chuyện truyền ra từ phòng mẹ chồng.
\"Cả hai căn nhà đều chuyển sang cho Ninh Ninh, không có rủi ro gì đấy chứ?\"
\"Quyền nuôi con đứng tên con, trên thực tế vẫn do con kiểm soát.\"
\"Nhỡ đâu Lâm Hiểu tranh giành quyền nuôi con với con thì sao?\" Giọng mẹ chồng lộ rõ vẻ lo lắng.
Lục Minh Dư khẽ cười một tiếng: \"Con đã hỏi qua luật sư rồi, Lâm Hiểu chỉ làm bán thời gian, không tính là có thu nhập ổn định, cộng thêm tay lại bị tàn tật, cho dù có kiện tới bước cuối cùng thì thẩm phán cũng sẽ tuyên cho con. Mẹ cứ yên tâm đi, con ly hôn ra khỏi nhà, cô ta tiếp tục ở lại chăm sóc mẹ và Ninh Ninh, lại còn biết ơn mẹ đến ghi xương khắc cốt, không có kết quả nào tốt hơn hiện tại nữa đâu.\"
\"Nói thế thì đúng thật!\" Giọng mẹ chồng trở nên tươi tỉnh hẳn lên.
Tôi im lặng không một tiếng động đi về phòng mình, lặng lẽ nằm xuống.
Mẹ tôi cả ngày đắm đuối bên bàn bài. Từ nhỏ tôi đã ở bên cạnh bị sai bảo rót trà, đưa thuốc, lấy đồ, dần dần cũng nhìn rồi học được cách chơi.
Khởi đầu dựa vào may mắn, về sau dựa vào bản lĩnh. Bài nát cũng chưa chắc đã thua, then chốt là xem xé bài thế nào, đánh thế nào, chờ thế nào. Khi thế cờ bất lợi cho mình, thì tĩnh tâm bình khí mà chờ đợi, giữ vững nhịp độ, giữ vững tâm lý. Cho dù trong tay chỉ còn sót lại duy nhất một quân bài chờ ù lẻ, cũng phải chờ cho quân bài đó tự mình lao tới.
Một tuần sau, thủ tục sang tên hoàn tất, tôi cũng đặt bút ký vào tờ thỏa thuận ly hôn. Sau khi làm xong thủ tục đăng ký ly hôn ở Cục Dân chính bước ra, ánh mắt Lục Minh Dư nhìn tôi đã có thêm một chút quyến luyến không lỡ.
Trong khoảng thời gian tiếp theo, anh ta không những ngày nào tan làm cũng về sớm, mà ăn cơm xong còn chủ động giúp rửa bát.
Mẹ chồng giả vờ giả vịt lau nước mắt: \"Giá như hồi đó không xảy ra chuyện ấy thì tốt biết mấy...\"
Tóm lại tất cả mọi người đều rất hài lòng với kết quả này nhưng biểu cảm trên mặt của tất cả mọi người thể hiện ra lại toàn là u buồn và đau khổ.
Dù sao đi nữa, đối với tôi mà nói, bước đầu tiên coi như đã vẽ xong một dấu chấm tròn trịa.
Một tháng thời gian bình tĩnh.
Trong lúc Lục Minh Dư tự cho là đúng bắt đầu tận hưởng khoảng thời gian ấm áp cuối cùng thì tôi lại bắt đầu thỉnh thoảng chạy ra ngoài. Có khi cả một ngày ban ngày không có mặt ở nhà, có khi buổi tối nhận một cuộc điện thoại đột nhiên đi ra ngoài. Tôi thay đổi hoàn toàn phong cách giản dị mộc mạc mấy năm nay, bắt đầu trang điểm làm tóc, mặc những chiếc váy liền thân tôn lên đường cong cơ thể.
Hôm đó, 9 giờ tối tôi mới về, phát hiện Lục Minh Dư mặt mày sa sầm ngồi trên ghế sofa, những ngón tay cầm ly nước siết chặt đến mức trắng bệch.
\"Lâm Hiểu, dạo này cô có phải là hơi quá đà rồi không?\" Anh ta trừng mắt nhìn tôi, giọng nói nén lại sự bực bội.
Tôi vừa thong thả tháo hoa tai, vừa cởi giày cao gót: \"Quá đà chỗ nào cơ?\"
Lục Minh Dư tỏ vẻ nhẫn nhịn: \"Dáng vẻ bây giờ của cô, còn là người vợ hiền mẹ đảm trong mắt mọi người nữa không?\"
\"Vợ hiền mẹ đảm?\" Tôi buồn cười quay đầu nhìn anh ta: \"Anh quên rồi à, một tháng nữa là chúng ta chính thức ly hôn rồi, tôi như thế nào thì liên quan gì đến anh ta chứ?\"
Lục Minh Dư sững người trong giây lát, ánh mắt đọng lại, đột nhiên nhớ ra điều gì đó: \"Chẳng lẽ cô thật sự... Không thể nào!\"
Anh ta tự mình lắc đầu, đứng dậy sải bước đi vào phòng làm việc. Tôi nhìn bóng lưng có phần hoảng hốt của anh ta chỉ thấy vừa mỉa mai vừa buồn cười.
Sống chung bao nhiêu năm qua, tôi quá hiểu Lục Minh Dư rồi. Về bản chất, anh ta là một bông hoa lớn lên trong nhà kính. Từ nhỏ gia cảnh đã khá giả, vẻ ngoài đẹp trai, suốt chặng đường suôn sẻ thuận lợi, muốn cái gì hầu như đều có thể có được. Trắc trở duy nhất anh ta từng chịu, có lẽ chính là việc bố chồng ngoại tình và có thêm một đứa \"em trai\" từ nhỏ đã được coi là thiên tài.
', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3/', '0', 'chuong', '', '0'),
('85', '1', '2026-10-12 16:31:05', '2026-10-12 09:31:05', 'Tôi không biết ban đầu anh ta và Hạ Oánh gian dâm với nhau như thế nào nhưng khi anh ta nhận ra Hạ Oánh có thể mang lại cho anh ta địa vị xã hội và lợi ích vật chất cao hơn, có thể giúp anh ta ngẩng cao đầu trước mặt người bố đã bỏ rơi anh ta và đứa em trai thiên tài kia, thì có lẽ anh ta mới thực sự động lòng.
Dù sao thì, cuộc đời xuôi buồm thuận gió của anh ta không quen bị thay thế, không quen bị mất đi, anh ta muốn chứng minh sự ra đi của người khác là sai lầm.
Mà sự thay đổi đột ngột của tôi lại gián tiếp dẫm vào vết xe đổ đó. Anh ta lại một lần nữa lờ mờ nếm trải cảm giác bị mất đi. Anh ta không quen, và càng không cho phép điều đó.
Từ nhỏ tôi đã không thích nói chuyện nhưng lại giỏi quan sát và phân tích. Rất sớm tôi đã phát hiện ra, những người thường xuyên thắng trên bàn bài không chỉ kỹ thuật đánh bài phải giỏi mà càng phải hiểu tâm lý, phải phán đoán chính xác tính cách và lối đánh của đối thủ.
Có những người thích liều, thế thì mình phải dám theo. Có những người sợ thua, thế thì mình phải dám hù dọa.
Còn Lục Minh Dư, điều anh ta sợ hãi chính là ván bài chưa tàn mà đã có người bỏ cuộc rời đi trước.
Điều khiến tôi bất ngờ là so với Lục Minh Dư, có người lại đưa ra phản ứng sớm hơn.
Thứ Bảy hằng tuần tôi đều làm món cá hấp cho Ninh Ninh, hôm đó mua thức ăn về nhà thì thấy trong phòng khách có thêm một người.
Hạ Oánh.
Khoảng thời gian này, thời gian Lục Minh Dư ở lỳ trong nhà tăng lên rõ rệt, nghe điện thoại cũng chỉ đáp qua loa vài câu, chắc hẳn điều đó khiến cô ta cảm thấy có mối nguy cơ nào đó nên mới tự mình mò tới tận cửa để thăm dò.
Lúc này, cô ta diện một bộ váy áo tinh tế, trang sức trên người lấp lánh, trên bàn trà đặt một chiếc túi xách hiệu xa xỉ đắt tiền.
Ninh Ninh đang bưng một ly nước trà nóng, cẩn thận từng chút một đưa cho cô ta. Cô ta không vội giơ tay ra nhận mà đánh giá Ninh Ninh từ trên xuống dưới, rồi mới khẽ mỉm cười: \"Được rồi, để lên bàn đi.\"
Lục Minh Dư và mẹ chồng ngồi bên cạnh, mỉm cười theo dõi cảnh tượng này.
Khi Ninh Ninh đặt ly nước xuống, không may bị nước bắn ra làm bỏng, đau tới mức đưa hai bàn tay nhỏ lên miệng thổi liên hồi.
Hạ Oánh phát ra tiếng cười kiểu mệnh phụ quý bà: \"Trẻ con còn nhỏ, đúng là phải rèn luyện nhiều hơn mới được.\"
Tôi cúi đầu thay giày, cố tình tạo ra tiếng động.
Mẹ chồng nhìn thấy tôi, mỉm cười gọi tôi lại gần để giới thiệu: \"Lâm Hiểu, đây là Viện trưởng ở cơ quan Minh Dư, cất công đến nhà thăm mẹ đấy, chút nữa con làm thêm vài món, giữ người ta ở lại ăn cơm.\"
Tôi rủ mắt, nở nụ cười, bước qua chủ động bắt tay với Hạ Oánh. Hạ Oánh nghiêng đầu nhìn tôi, ánh mắt là sự đánh giá từ trên cao nhìn xuống, mang theo vẻ tự cao, coi thường, thậm chí còn có vài phần thương hại.
Một lúc lâu sau, cô ta mới chậm rãi giơ tay ra, thong thả mỉm cười bảo: \"Thế thì làm phiền em rồi.\"
Tay tôi đang xách túi thức ăn bỗng nới lỏng ra. Con cá trong túi tuột ra ngoài, nhắm thẳng vào lòng Hạ Oánh mà giãy giụa tưng bừng, nước bắn tung xối xả. Hạ Oánh chật vật đứng dậy né tránh, ly nước trà nóng trên bàn bị xô ngã, tưới thẳng lên bắp chân trần và chiếc túi xách đắt tiền của cô ta.
Cô ta thốt lên tiếng thét chói tai.
Tôi ngẩn người ra như bị đơ một lúc lâu, rồi mới luống cuống tay chân đè con cá lại, liên tục cất lời xin lỗi.
Hạ Oánh đến nhìn cũng chẳng buồn nhìn tôi một cái, ôm lấy bụng trầm giọng ra lệnh: \"Tiểu Lục, đưa em đi bệnh viện kiểm tra một chút.\"
Mẹ chồng vội vã phụ họa: \"Đúng đúng, tuổi tác con không thể so với giới trẻ được, đừng để xảy ra chuyện gì rắc rối nhé.\"
Hạ Oánh nghiến chặt răng, sắc mặt xám xịt đi theo Lục Minh Dư ra ngoài.
Ninh Ninh ngửa khuôn mặt nhỏ nhắn lên hỏi tôi: \"Mẹ ơi, có phải do ly nước của Ninh Ninh đặt không cẩn thận không ạ?\"
Tôi dịu dàng vuốt ve đầu con bé: \"Ninh Ninh giỏi lắm, là do bà thím đó tự mình không cẩn thận thôi, sau này những thứ nguy hiểm như thế này Ninh Ninh đừng đụng vào nhé, con xem bà thím đó có phải bị bỏng rồi không?\"
Ninh Ninh gật đầu ngoan ngoãn: \"Vâng ạ mẹ, con nhớ rồi.\"
Mẹ chồng liếc nhìn tôi một cái, rồi lại nhìn Ninh Ninh, cằn nhằn cãi vã: \"Ninh Ninh không còn nhỏ nữa rồi, nên học làm chút việc nhà đi, Lâm Hiểu, rảnh rỗi con phải dạy nó nhiều vào mới đúng.\"
Lúc tôi và Ninh Ninh ăn cơm trưa, mẹ chồng chống gậy chầm chậm từ trong phòng mò ra: \"Ơ, sao không gọi mẹ?\" Vẻ mặt bà ta ngạc nhiên.
Tôi nhìn mẹ chồng, dịu giọng nói: \"Mẹ ơi, hôm nay con làm món cá sốt chua ngọt, đường huyết của mẹ cao không ăn được nhưng con có cố tình để dành cho mẹ rau xanh và củ cải đấy, mẹ mau lại đây ăn đi ạ.\"
Ngày thứ 15 của thời gian bình tĩnh.
Tôi phát hiện ra Lục Minh Dư lại đang lén lút theo dõi mình.
Buổi tối ăn cơm xong, tôi nhận một cuộc điện thoại, trang điểm tinh tế và khoác lên người chiếc váy liền xinh đẹp chuẩn bị ra ngoài.
Lục Minh Dư hỏi tôi đi đâu, tôi nheo mắt né tránh: \"Thì ra ngoài đi dạo loanh quanh thôi, trước khi Ninh Ninh đi ngủ em sẽ về.\"
Khi anh ta dùng ánh mắt u tối nhìn tôi bước ra khỏi cửa, tôi đã không ngờ được rằng anh ta lại dám lén lút bám theo đằng sau mình.
Nhưng tôi quả thực chỉ là đi dạo thôi mà. Ban đầu tôi đi ra ngoài quả thực là để cho anh ta đoán già đoán non nghi ngờ linh tinh, nhưng khi tôi đi ra ngoài vài lần, mới phát hiện ra đây là việc mà đời tôi vốn dĩ nên làm từ lâu rồi.
Từ nhỏ đến lớn, hình như tôi chưa từng rảnh rỗi vô lo vô nghĩ như thế này bao giờ. Thả lỏng đầu óc không mục đích khiến cho nội tâm tôi trở nên thanh thản.
Tôi thích sự thanh thản.
Lục Minh Dư bám theo sau tôi không xa, rõ ràng là muốn xác nhận xem có phải tôi đang có người đàn ông khác ở bên ngoài hay không. Phiền phức ở chỗ, tôi làm gì có.
Tôi lấy điện thoại ra, giả vờ gọi điện thoại với không khí.
\"Có việc bận đột xuất à?\"
\"Thế chút nữa em tự về nhà vậy.\"
\"Em không sao đâu, dù sao cũng đã ly hôn rồi mà.\"
Tôi tự nói tự trả lời đi về phía trước, đảm bảo cho Lục Minh Dư đi đằng sau nghe thấy được.
Một chiếc xe hơi màu đen đột nhiên dừng lại ngay trước mặt tôi. Cửa xe mở ra, một người đàn ông dáng người cao ráo bước xuống, nghiêng đầu nhìn tôi.
\"Đã lâu không gặp, Lâm Hiểu.\"
Tôi ngẩn ngơ hai giây, chầm chậm mở to mắt: \"Chu Hữu?\"
Anh ấy mỉm cười với tôi: \"Hiếm có thật đấy, em vậy mà vẫn còn nhớ tên anh…\"
Không đợi anh ấy nói xong, tôi bước tới hai bước, nhiệt tình ôm chầm lấy anh ấy. Anh ấy hơi khựng lại.
\"Đã lâu không gặp, Chu Hữu.\" Tôi buông anh ấy ra, ngượng ngùng cất lời: \"Có tiện đưa em đi một đoạn không?\"
Tôi bước lên xe của Chu Hữu.
Tài xế yên lặng lái xe, tôi và Chu Hữu ngồi song song ở hàng ghế sau. Trong tầm mắt, bóng dáng Lục Minh Dư xa dần, tôi quay đầu lại, bắt gặp ánh mắt của Chu Hữu, thấp thoáng vẫn là dáng vẻ tuấn tú đẹp trai năm nào nhưng bộ vest cắt may vừa vặn và kiểu tóc chỉn chu lại khiến anh ấy thêm vài phần trầm ổn và phong thái khí chất.
\"Xem ra bây giờ anh đã công thành danh toại rồi, tốt quá.\"
Tôi chân thành mừng cho anh ấy. Không ai rõ hơn tôi, từ cái dây chuyền sản xuất đó mà bước lên tới đỉnh cao khó khăn đến nhường nào.
\"Cũng tạm ổn, anh có một công ty công nghệ sang năm ngoái vừa lên sàn chứng khoán.\"
Anh ấy thản nhiên nói xong, khẽ mỉm cười: \"Năm đó sau khi em nghỉ việc ở xưởng, anh cũng bỏ đi luôn. Nói ra thì anh còn phải cảm ơn em nữa, câu nói \'không muốn sống một cuộc đời như thế\' của em năm đó đã ép anh phải cố gắng đi đến tận ngày hôm nay. Có điều mấy năm nay bận rộn công việc như con quay, đến tận bây giờ vẫn còn độc thân. Đúng rồi, hình như vừa nãy anh nghe thấy em gọi điện thoại bảo em ly hôn rồi…\"
\"Chúc mừng anh nhé, Chu Hữu.\" Tôi quay đầu, mỉm cười nhìn anh ấy: \"Ngã tư phía trước có thể thả em xuống được không?\"
Buổi tối.
Tôi đang ngủ say, mơ mơ màng màng cảm thấy có người đang cởi quần áo tôi.
Bất chợt mở mắt ra, là Lục Minh Dư, hai mắt anh ta đỏ ngầu, nồng nặc mùi rượu, lấy cơ thể đè chặt lấy tôi.
\"Lâm Hiểu, cô thật sự ngoại tình rồi sao? Thằng gã đó là ai!\"
Tôi dốc hết sức đẩy anh ta ra, anh ta lại đè lên dữ dội hơn.
\"Chúng mày gian dâm với nhau từ bao giờ? Cho nên tôi đề nghị ly hôn là đúng như mong muốn của cô đúng không? Hóa ra tôi mới chính là cái gã đần độn đó đúng không!\"
\"Thằng gã đó giàu lắm sao? Thảo nào ly hôn cô cái gì cũng không thèm lấy, hóa ra là đã tìm được mối ngon khác rồi! Sau này cô thà chết cũng không chịu cho tôi, cũng là vì nó đúng không?!\"
Anh ta càng nói càng kích động, hành động trở nên thô bạo. Tôi nghiến chặt răng, một giáng tát nảy lửa tát thẳng vào mặt anh ta, tiếng tát vang lên giòn tan. Anh ta ngã ngồi xuống đất, ngơ ngác mất một lúc, dùng sức vò nát khuôn mặt mình.
\"Lâm Hiểu, chúng ta chẳng phải đang sống rất tốt sao?\"
\"Sao lại... trở thành nông nỗi như ngày hôm nay chứ?\"
Trong bóng tối, tôi đưa ánh mắt vô cùng lạnh lẽo liếc nhìn anh ta.
Khoảng thời gian tiếp theo Chu Hữu có hẹn tôi vài lần, tôi có lúc đi, có lúc không đi.
Anh ấy mời tôi ăn ở những nhà hàng rất đắt đỏ, đi đến những trung tâm thương mại rất sang trọng, kể cho tôi nghe về chặng đường lập nghiệp gian khổ đơn độc mấy năm qua. Anh ấy bảo, tuy rằng vẫn luôn canh cánh trong lòng về câu nói năm đó của tôi nhưng vẫn vô cùng mừng rỡ khi thấy tôi vẫn là một Lâm Hiểu đơn thuần giản dị trong ký ức.
Tôi lặng lẽ lắng nghe, tuyệt nhiên không nhắc về bản thân.
Mỗi lần tôi ra ngoài, Lục Minh Dư luôn đứng đằng sau dùng ánh mắt phức tạp u uất dõi theo tôi. Đối với những sự nghi ngờ và chỉ trích của anh ta, tôi không thừa nhận, cũng chẳng phủ nhận, cho anh ta đủ khoảng trống để tha hồ tưởng tượng.
', 'Chương 4', '', 'publish', 'open', 'closed', '', 'chuong-4', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-4/', '0', 'chuong', '', '0'),
('86', '1', '2026-10-12 16:32:42', '2026-10-12 09:32:42', 'Ban đêm, tôi nghe thấy tiếng anh ta và mẹ chồng hạ thấp giọng cãi nhau: \"Tiểu Oánh lớn tuổi hơn con nhiều như thế, nếu thật sự gắn bó cả đời với cô ấy con không đành lòng, huống hồ con vẫn còn tình cảm với Lâm Hiểu…\"
\"Thế thì trách ai! Ai bảo ban đầu tự con không quản nổi bản thân con!\"
Giọng mẹ chồng sắc lẹm và đanh thép: \"Chuyện đã đến nước này, con bắt buộc phải tàn nhẫn! Con nuốt trôi cục tức này thì tương lai mới là những ngày tháng nở mày nở mặt! Con không muốn có một đứa con trai của riêng mình sao?\"
\"Mẹ, con phải suy nghĩ lại, để con suy nghĩ lại đã.\"
...
Hai ngày sau, tôi nhận được điện thoại của Hạ Oánh.
Trong quán cà phê vắng người, khi ngồi đối diện với cô ta, tôi mới lần đầu tiên nghiêm túc quan sát cô ta. Nói thế nào nhỉ? Thẩm mỹ quả thực là một thứ tốt. Độ tuổi 50, nhưng trên mặt lại không nhìn thấy một nếp nhăn nào nhưng kỳ lạ ở chỗ, rõ ràng cả khuôn mặt căng mịn đầy đặn, vậy mà tôi vẫn có thể nhìn một cái là đoán ra tuổi thật của cô ta. Giống như một sự đơ cứng bị cưỡng ép cố định lại, trông vừa già nua lại vừa trẻ trung.
\"Thực ra cô đã biết hết rồi, đúng không?\"
Cô ta nghiêng đầu, nhìn thẳng vào mắt tôi. Tôi không trả lời, rủ mắt nhấp một ngụm cà phê.
Cô ta cười, trong nụ cười mang theo vẻ ưu việt và thong dong của kẻ bề trên: \"Đàn ông có đôi khi khá là ngây thơ, tự cho rằng mình nắm thóp được tất cả mọi thứ, nhưng thực tế lại giống như con rối dây cho người ta tùy ý giật dây. Lâm Hiểu, cô đúng là giống tôi thời trẻ đấy.\"
Cô ta nheo mắt, hình như vì câu nói này mà nhớ lại chuyện xưa: \"Ba mươi năm trước, tôi là một cô y tá nhỏ hay xấu hổ, thầm thích một anh bác sĩ trẻ tuổi đẹp trai, tôi lúc đó đương nhiên không xứng với anh ta. Sau này tôi đã sở hữu tất cả mọi thứ, lại một lần nữa gặp lại anh ta. Anh ta hói đầu, bụng phệ, lại gần còn có một mùi người già hôi hám mục nát... Tôi rất thất vọng, thậm chí hận anh ta đã phá hủy ký ức đẹp đẽ của tôi, cho đến khi tôi gặp được Tiểu Lục.\"
\"Tiểu Lục đắp khuôn y hệt anh ta thời trẻ, tôi rất vui, rất biết ơn, tôi đã tìm lại được thanh xuân của mình, ông trời thậm chí còn cho tôi có được một đứa con ở cái tuổi này! Đây không phải duyên trời định thì là cái gì? Tôi nói cho cô những điều này là để cô biết, tôi bất luận thế nào cũng phải kết hợp cùng Tiểu Lục.\"
\"Có điều Tiểu Lục dạo này hơi ngớ ngẩn mê muội, công việc lơ đễnh gây ra vài vụ sự cố y tế nhỏ, vậy mà còn dám nói ra câu từ điên rồ như hai gia đình cùng tồn tại nữa chứ. Không còn cách nào khác, đành phải để tôi đứng ra vậy.\"
Hạ Oánh lấy từ trong chiếc túi bên cạnh ra một tấm thẻ, đẩy sang: \"Ở đây có 200 nghìn tệ, coi như là chút bồi thường tôi dành cho cô.\"
Tôi nhìn tấm thẻ ngân hàng trên bàn, mỉm cười nhẹ nhàng: \"Dì Hạ, tôi không hiểu đây là có ý gì.\"
Sắc mặt Hạ Oánh tối sầm lại: \"Tôi tuy có lớn tuổi một chút thật nhưng xe Ferrari có cũ thì vẫn là Ferrari, chưa tới lượt cô gọi tôi là dì đâu.\"
Tôi chầm chậm gật đầu, \"Cho nên một chiếc Ferrari đã cũ định giá cho bạch nguyệt quang của mình là 200 nghìn tệ sao? Hay là cô nghĩ rằng muốn đuổi tôi đi thì 200 nghìn tệ là đủ rồi?\"
Hạ Oánh nhìn chằm chằm tôi một lúc, lại móc từ trong túi ra tấm thẻ thứ hai: \"Cộng thêm 500 nghìn tệ nữa, đủ chưa?\"
Tôi mỉm cười không nói.
Cô ta cười, cười một cách thật lòng thật ý: \"Tiểu Lục bảo cô là một con mụ nội trợ không có kiến thức không có chính kiến, xem ra cậu ta đúng là rất ngây ngô thật.\"
Cô ta thu lại hai tấm thẻ trên bàn, rồi lấy ra tấm thẻ thứ ba: \"Đây là 2 triệu tệ, coi như phí bồi thường tôi cho cô, đây là giới hạn cuối cùng của tôi rồi, đương nhiên, tiếp theo cô phải đảm bảo giải quyết thủ tục ly hôn thuận lợi với Tiểu Lục.\"
Tôi nhấp một ngụm cà phê, lúc này mới giơ tay cầm lấy tấm thẻ: \"Đây quả thực là một khoản phí bồi thường không tệ, cảm ơn chị Hạ.\"
Khi đứng dậy rời đi, tôi đi được vài bước thì lại quay đầu lại: \"Có một việc thiết nghĩ cần phải đính chính một chút.\"
\"Cái gì?\"
Tôi ôn tồn cất lời: \"Tôi làm việc có giới hạn của mình, sau này sống đến tuổi của cô cũng tuyệt đối không làm cái thứ chuyện tởm lợm như cô, nên phiền cô đừng nói những câu kiểu như tôi giống cô nữa nhé, nghe hơi mất mặt đấy.\"
Mặt Hạ Oánh xám xịt, nét mặt căng cứng.
\"Còn nữa, thực ra cái mặt cô bây giờ ấy, nếp nhăn rõ ràng lắm rồi.\" Tôi dịu dàng nhắc nhở: \"Cô nên đi tiêm thêm Botox đi thôi.\"
Nói xong tôi quay người đi ra ngoài.
Vòng qua bức vách cây cảnh cao lớn ở cửa, tôi nhìn thấy Chu Hữu. Anh ấy ngồi một mình bên cửa sổ, như thể đang đợi người, anh ấy dùng ánh mắt sâu thẳm nhìn tôi.
Bên ngoài cửa vội vã bước vào một người đàn ông, miệng lẩm bẩm \"Chu tổng khiến anh ta đợi lâu rồi\", bước đến ngồi xuống đối diện Chu Hữu.
Tôi dời mắt đi, không biểu cảm bước ra ngoài.
Tôi không nghe điện thoại của Chu Hữu nữa. Anh ấy có đường Dương Quan của anh ấy, tôi có chiếc cầu độc mộc của tôi.
Anh ấy là tân quý công nghệ phấn đấu thành công. Còn tôi, chỉ là một người nội trợ bình thường đang vật lộn vì sinh tồn.
Trải nghiệm của Hạ Oánh đã dạy cho tôi biết, bạch nguyệt quang sau khi gặp lại sẽ chẳng có kết cục tốt đẹp nào.
...
Ba ngày trước khi thời gian bình tĩnh kết thúc, tôi đẩy mẹ chồng đi bệnh viện tái khám vết thương. Khám xong chuẩn bị về nhà thì tôi nhớ ra quên túi xách ở trạm y tá, thế là liền để mẹ chồng ở trước cửa một phòng bệnh rồi quay lại lấy.
Đến khi tôi trở lại lần nữa thì trong hành lang đã nháo nhào thành một mớ hỗn loạn. Mẹ chồng và một người phụ nữ trạc tuổi đang vật lộn túi bụi dưới đất, túm tóc nhau giằng xé, thỉnh thoảng lại vang lên tiếng tát \"bốp bốp\", lẫn lộn với những lời chửi rủa tục tĩu chướng tai.
\"Con đĩ già, trốn chui trốn lủi bấy nhiêu năm, cuối cùng cũng rơi vào tay tao! Hôm nay tao không xé xác mày ra thì tao không mang họ người!\"
\"Mụ già liệt kia! Hồi đó nếu không phải mày đâm chọc gây chuyện thì giữa tao với ông Lục tới lượt mày chen một chân vào chắc!\"
Thực lực hai bên ngang ngửa, vừa chửi vừa vung tay vung chân. Bà tát tôi một cái, tôi vả bà một cú, không một ai có thể gỡ hai người họ ra.
\"Mẹ ơi, mẹ có sao không?\"
Tôi lao tới, thốt lên tiếng kinh hãi, hoảng hốt siết chặt lấy bàn tay giơ lên của mẹ chồng, khiến bà ta không thể tấn công mà cũng chẳng thể phòng thủ, trên mặt liên tiếp dính thêm mấy cái tát rõ đau.
Trận hỗn chiến này cuối cùng đã bị các bác sĩ nghe tin chạy tới cưỡng chế ngăn lại.
Mặt mẹ chồng bầm tím một mảnh sưng vù một chỗ, vệt móng cào rướm máu kéo dài từ trán xuống tận cằm, mái tóc vốn dĩ đã thưa thớt bị giật phăng một mảng lớn, lộ ra mảng da đầu hói lóc.
Bà ta phát ra tiếng ức nghẹn ú ớ không rõ lời, chỉ tay vào người phụ nữ kia cũng thảm hại không kém để mách tôi: \"Đánh nó! Đánh chết con đĩ đó giúp mẹ!\"
Mẹ chồng kể cho tôi biết, lúc ngồi chờ tôi bà ta phát hiện phòng bệnh đó lại chính là phòng bố chồng đang nằm, còn \"con đĩ già\" kia thì đang ở bên trong chăm sóc. Thù mới nợ cũ bùng phát cùng lúc, bà ta chật vật gượng đứng dậy khỏi xe lăn, lao tới là xé xác đối phương.
Bố chồng can ngăn cũng bị mẹ chồng vả cho một cái, huyết áp tăng vọt, giận đến mức xỉu tại chỗ.
Sau khi về nhà, mẹ chồng gọi điện cho Lục Minh Dư tố cáo bi tráng, bộ dạng vừa khóc vừa mắng làm Ninh Ninh sợ hãi, bĩu cái miệng nhỏ không dám cất lời.
Tôi đột nhiên lên tiếng: \"Bà có thể câm miệng lại không?\"
Mẹ chồng ngẩn ra, ngơ ngác quay đầu nhìn tôi: \"Lâm Hiểu, con nói cái gì?\"
Tôi nhìn bà ta, chầm chậm nói: \"Tôi bảo bà câm miệng vào, nghe rõ chưa?\"
Mẹ chồng từ từ trợn tròn đôi mắt bầm sưng lồi ra kia.
Khi Lục Minh Dư vội vã chạy về tới nhà, tôi đã thu dọn xong xuôi hành lý. Trong khoảng thời gian này, mẹ chồng cứ trợn trừng mắt nhìn tôi, biểu cảm hoảng hốt nghi ngờ chưa định.
Tôi từ trong phòng cầm ra một bản tài liệu, đặt trước mặt Lục Minh Dư: \"Thỏa thuận ly hôn tôi đã sửa lại một chút, anh xem xem có đồng ý không.\"
Lục Minh Dư ngơ ngác hỏi: \"Sửa cái gì?\"
\"Quyền nuôi dưỡng Ninh Ninh thuộc về tôi, những cái khác giữ nguyên. Anh đồng ý thì hai ngày nữa hết thời hạn bình tĩnh mình đi lấy giấy đăng ký ly hôn, không đồng ý thì nộp đơn kiện ly hôn lại từ đầu.\"
Lục Minh Dư nghe vậy, nét mặt thả lỏng ngồi xuống ghế sofa.
\"Lâm Hiểu, có lẽ cô không hiểu, quyền nuôi con cái thứ này đâu phải muốn là được. Đương nhiên, nếu cô là vì không muốn ly hôn nên mới nghĩ ra cái cách này thì tôi cũng có thể hiểu được. Thực ra khoảng thời gian này tôi cũng vẫn luôn suy nghĩ về một phương án mà tất cả mọi người đều có thể chấp nhận được…\"
Tôi mỉm cười ngắt lời anh ta: \"Phương án anh nghĩ tới, không phải là tôi một gia đình, Hạ Oánh một gia đình đấy chứ? Anh chắc chắn Hạ Oánh sẽ đồng ý sao?\"
Lục Minh Dư giật bắn mình nhìn tôi, mặt đầy kinh ngạc: \"Cô..\"
Tôi nhẹ nhàng mỉm cười với anh ta: \"Đúng vậy, tôi đều biết hết rồi. Tôi biết Hạ Oánh, Hạ Oánh cũng biết là tôi đã biết, chỉ có hai mẹ con nhà anh là giống như hai kẻ ngốc không hề biết cái gì cả, nói như thế đã đủ hiểu chưa?\"
\"Cô, cô...\"
\"Cô cô cái gì mà cô! Minh Dư, sao anh lại không biết nói năng gì nữa rồi thế này!\" Tôi giễu cợt lườm anh ta.
\"Anh không phải thật sự nghĩ rằng dựng ra vài tấm ảnh vớ vẩn là có thể đổ thừa cho tôi tội ngoại tình đấy chứ? Còn bà nữa…\"
Tôi lại quay sang nhìn người mẹ chồng đã ngây đần ra: \"Bà không phải tưởng giả vờ giả vịt kể một câu chuyện là có thể úp bô phân lên đầu tôi đấy chứ? Hai người quả nhiên là mẹ con ruột, vừa ngu vừa ác.\"
\"Cô, nhưng cô đã đồng ý ly hôn rồi...\" Lục Minh Dư khó khăn mới thốt ra được câu này.
\"Mới bảo là anh ngu đấy, đương nhiên là để thu thập bằng chứng rồi, nếu không tiếp theo sao tôi tranh ta giành quyền nuôi con với anh được?\"
Tôi càng nói càng thấy vui vẻ sảng khoái. Hóa ra việc đường đường chính chính thốt ra những lời xiên xỏ móc mỉa lại khiến con người ta sảng khoái đến thế!
Lục Minh Dư trừng mắt nhìn tôi chằm chằm: \"Cho nên cô đề nghị chuyển nhượng bất động sản sang tên Ninh Ninh trước, đều là chuẩn bị cho ngày hôm nay?\"
\"Không còn cách nào khác, hai người không xứng làm bố và làm bà nội của Ninh Ninh, nên đành để tôi đứng ra uốn nắn lại vậy.\"
Mẹ chồng đột nhiên nhận ra điều gì, cất giọng sắc lẹm xen vào: \"Hôm nay đụng phải hai cái đồ già kia, cũng là do cô cố tình sắp đặt?\"
Tôi thở dài một tiếng: \"Tôi sắp đi rồi, nợ nần giữa chúng ta tổng cộng vẫn phải tính toán chứ. Haizz, chủ yếu là tôi vẫn chưa thể ra tay đánh bề trên, nên đành mượn tay người khác dùng một chút vậy.\"
Khi tôi bước ra khỏi cửa, Lục Minh Dư ở đằng sau u uất cất lời: \"Lâm Hiểu, cô tưởng đánh cược kiện tụng cô thắng được tôi sao?\"
Tôi quay đầu nhìn anh ta, mỉm cười đáp: \"Thế thì phải xem tòa án phán quyết thế nào rồi, tôi là công dân tuân thủ pháp luật, hoàn toàn tôn trọng quyết định của thẩm phán.\"
Cánh cửa lớn khép lại ngăn cách hoàn toàn tiếng gầm rú ở bên trong. Tôi nắm tay Ninh Ninh, tiến về phía trước một cách vô cùng sảng khoái.
Quẻ bài này, cuối cùng cũng chờ ù rồi.
Lục Minh Dư đương nhiên không đồng ý từ bỏ quyền nuôi Ninh Ninh. Thế là ngày thứ hai sau khi hết hạn thời gian bình tĩnh, tôi gửi đơn kiện xin ly hôn.
Hạ Oánh gọi điện cho tôi, giọng nói khó chịu: \"Cô đã hứa với tôi là sẽ giải quyết ly hôn thuận lợi mà.\"
Tôi bất lực thở dài: \"Bây giờ là Lục Minh Dư không chấp nhận đơn thỏa thuận ly hôn, em cũng hết cách rồi, chị Hạ ơi, hay là chị khuyên giúp em một tiếng đi, kiện tụng ly hôn thì đâu có nhanh như thế được.\"
Hạ Oánh bắt đầu lộ bụng rồi, cô ta đương nhiên không muốn mang bụng bự mà vẫn chưa được kết hôn.
Cô ta cười khẩy: \"Cô đã cầm tiền từ chỗ tôi, lại còn muốn lấy đi cả hai căn nhà của cậu ta, Lâm Hiểu, cô có phải là quá tham lam rồi không?\"
Tôi dịu giọng phản bác: \"Lời không thể nói như thế được chứ. Tiền là do chị tự nguyện biếu tặng để bồi thường tinh thần, nhà cửa vốn dĩ cũng nên để lại cho con cái, thế này sao gọi là tham lam được? Đây là bảo vệ quyền lợi hợp pháp của chính mình thôi mà.\"
Trong khoảng thời gian chờ mở phiên tòa, tôi dắt Ninh Ninh chuyển về căn nhà cũ, dù sao thì đây vốn là tài sản đứng tên Ninh Ninh.
Căn nhà cũ ở gần bệnh viện, tôi thường xuyên dắt Ninh Ninh tới thăm bố chồng đang nằm viện. Trên thực tế mấy năm nay, tôi vẫn luôn giữ liên lạc với bố chồng, chỉ là không nói cho hai mẹ con nhà họ Lục biết mà thôi.
', 'Chương 5', '', 'publish', 'open', 'closed', '', 'chuong-5', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-5/', '0', 'chuong', '', '0'),
('87', '1', '2026-10-12 16:33:24', '2026-10-12 09:33:24', 'Hồi đó sau khi chuyện bố chồng ngoại tình bị phát hiện, mẹ chồng liên tục tới cơ quan ông ấy làm ầm ĩ hết lần này đến lần khác, bố chồng vốn dĩ đang chễm chệ ở vị trí lãnh đạo cao cấp đành dứt khoát từ chức ra ngoài lập nghiệp, dựa vào mạng lưới quan hệ và tài nguyên tích lũy nhiều năm mà nhanh chóng sở hữu tài sản hàng trăm triệu, lại còn mua bất động sản bên nước ngoài cho đứa con trai nhỏ đang du học, cả gia đình sống vô cùng vinh hoa phú quý.
Hai mẹ con nhà họ Lục mỗi lần nhắc tới những chuyện này đều hận đến nghiến răng nghiến lợi.
Lục Minh Dư từng vác một câu đối phúng màu trắng tới gửi tặng vào đúng dịp đại thọ của bố chồng, rồi ngẩng cao đầu bỏ đi giữa bầu không khí im phăng phắc của quan khách.
Hai bố con từ đó trở thành kẻ thù không đội trời chung.
Bố chồng lần này nhập viện là do bị phát hiện mắc bệnh ung thư. Ninh Ninh là một đứa trẻ tình cảm và tốt bụng, thấy ông nội yếu ớt nằm trên giường bệnh thì thương ông ấy khóc sụt sùi mãi không ngừng.
Bố chồng giơ tay ra muốn xoa đầu Ninh Ninh. Ninh Ninh thấy ông ấy không với tới, ngoan ngoãn đưa đầu mình lại gần tay ông ấy: \"Ông nội ơi, ông xoa đi ạ, xoa xoa là hết đau luôn đó.\"
Trên khuôn mặt gầy gò khô gầy của bố chồng gượng lên một nụ cười khó khăn: \"Cháu ngoan, Ninh Ninh nhà chúng ta là đứa trẻ ngoan.\"
Tôi ngập ngừng đem chuyện nhà cửa và việc giành quyền giám hộ kể lại cho bố chồng nghe, dù sao thì trước khi sang tên nhà cho Lục Minh Dư thì mấy căn nhà này từng đứng tên bố chồng, coi như là một lời thông báo.
Tròng mắt đục ngầu của bố chồng rọi lên người tôi, như thể lần đầu tiên nghiêm túc quan sát tôi kỹ đến thế. Rất lâu sau, ông ấy thốt ra ba chữ: \"Làm tốt lắm.\"
Khi rời đi, bố chồng hỏi tôi việc ly hôn có cần ông ấy giúp đỡ gì không.
Tôi mỉm cười bảo không cần đâu ạ.
Chẳng bao lâu sau, phiên tòa xét xử ly hôn chính thức mở. Lục Minh Dư sắc mặt u uất ngồi đối diện tôi, ánh mắt nhìn tôi lạnh tựa băng giá. Luật sư của anh ta chứng minh Lục Minh Dư có điều kiện kinh tế tốt hơn tôi, công việc ổn định hơn tôi, đồng thời nộp thêm bằng chứng ngón tay tôi bị tàn tật.
Còn phía bên tôi, không chỉ nộp đầy đủ bằng chứng Lục Minh Dư ngoại tình khiến đối phương mang thai, mà còn giao ra vô số những bức ảnh và nhật ký ghi lại cảnh tôi đi họp phụ huynh cho con, một mình dẫn con đi khám bệnh tiêm phòng.
Đến khi tôi lấy ra hồ sơ bệnh án sinh mổ đồng thời làm phẫu thuật thắt ống dẫn trứng của mình, Lục Minh Dư lộ ra biểu cảm không thể nào tin nổi.
Đúng vậy, khi sinh Ninh Ninh, tôi đã chủ động làm phẫu thuật thắt ống dẫn trứng.
Tôi từ nhỏ lớn lên trong một gia đình đông con, thấu hiểu sâu sắc nỗi đau tuổi thơ do sự đối xử bất bình đẳng của cha mẹ mang lại. Tôi đương nhiên biết mẹ chồng khao khát có một đứa cháu trai đến nhường nào nhưng chuyện đó chả liên quan gì tới tôi.
Tôi tự nói với bản thân, bất luận là con trai hay con gái, tôi chỉ cần duy nhất một đứa. Tôi phải đảm bảo con tôi được lớn lên trong 100% tình yêu thương trọn vẹn.
Trong pháp luật có một điều khoản quy định rõ ràng: Người đã thực hiện phẫu thuật triệt sản hoặc mất khả năng sinh sản được ưu tiên nhận quyền nuôi dưỡng con.
Cuối cùng tòa án tuyên án quyền nuôi Ninh Ninh thuộc về tôi. Lục Minh Dư ngồi đối diện tôi lộ ra sự chán nản thất bại thảm hại.
Mẹ chồng sau khi biết tin này lập tức gọi điện thoại tới chửi rủa tôi.
Dù sao thì đôi chân cũng đã phế, không thể náo loạn như từng làm với bố chồng trước đây nên chỉ có thể dùng những lời lẽ độc địa nhất trong điện thoại để nguyền rủa tôi. Bà ta mắng tôi là con đỉa hút máu chuyên môn đến hút máu nhà bà ta, mắng tôi lòng mề độc địa vậy mà dám lợi dụng chính con ruột của mình để tranh ta giành tài sản.
Bà ta hình như đã hoàn toàn quên mất kẻ lợi dụng đứa trẻ với ý đồ \"hiến tế\" chính là bà ta, kẻ vu khống vấy bẩn tôi nhưng vẫn muốn tôi tiếp tục hầu hạ cũng chính là bà ta. Càng quên đi mất những năm qua tôi đã dốc sức hầu hạ thế nào, thậm chí từng cứu cả cái mạng của bà ta.
Tôi dùng giọng điệu lạnh như băng cảnh cáo: “Nếu biết điều mà sống yên ổn, nể mặt bà là bà nội của Ninh Ninh, tôi sẽ để bà ở lại căn nhà cũ để dưỡng già. Nếu còn cố tình quấy phá hạch sách, thì tôi đành phải với tư cách là người giám hộ của Ninh Ninh mà đuổi bà cút đi, cút về căn nhà cấp bốn 30 mét vuông ở vùng ngoại ô kia của bà.”
Mẹ chồng không phục, lôi kéo Lục Minh Dư đòi tiếp tục đâm đơn kiện tôi nhưng Lục Minh Dư lúc này đã chẳng còn tâm trí đâu mà ngó ngàng tới nữa. Anh ta đột nhiên bị bóc phốt liên tiếp gây ra vài vụ tranh chấp y tế, đối phương giăng băng rôn kéo đàn kéo đống đòi quyền lợi, làm ầm ĩ trên mạng khiến dư luận bùng nổ.
Hạ Oánh với tư cách là người đứng đầu cơ sở, ban đầu còn đứng ra đính chính, mưu đồ dùng luận điệu \"hiệu quả thẩm mỹ mang tính chủ quan\" để lấp liếm cho qua chuyện nhưng ngay sau đó, lại có nhân viên đã nghỉ việc đứng ra tố cáo bằng tên thật, bảo rằng cơ sở của Hạ Oánh khi tiêm Acid Hyaluronic (filler) đã lén lút tráo đổi thuốc, lấy hàng kém chất lượng thay thế hàng tốt, mua giá rẻ bán giá trên trời, kiếm lợi bất chính khổng lồ.
Trong phút chốc lại lôi kéo thêm vô số khách hàng kéo tới tận cửa đòi công lý.
Cơ quan quản lý thị trường trực tiếp hạ lệnh tạm ngừng hoạt động để chỉnh đốn, cơ sở thẩm mỹ buộc phải đóng cửa ngưng kinh doanh, đồng thời phải đối mặt với việc bị truy cứu trách nhiệm hình sự phía sau.
Tôi nhìn bản tin thời sự, trong lòng thầm cảm thán chẳng lẽ ông trời cuối cùng cũng rủ lòng thương hại nên mới đại phát từ bi ra tay giúp tôi, thì nhận được một tin nhắn từ Chu Hữu.
[Em chưa bao giờ nói cho anh nghe về chuyện của mình, hôm đó vô tình nghe thấy cuộc trò chuyện của em, anh mới biết được hoàn cảnh hiện tại của em. Không ai hiểu rõ sự gian nan khi những người như chúng ta muốn trèo lên trên hơn anh, anh cũng không làm gì nhiều cả, chỉ là nương theo đà mà đẩy thêm một tay thôi, hy vọng em đừng trách anh nhiều chuyện.]
Tôi nhìn dòng tin nhắn im lặng rất lâu, rốt cuộc vẫn không nhắn tin lại cho anh ấy.
Sau này, tôi lướt thấy trên mạng một đoạn video quay lén.
Trong phòng làm việc, Lục Minh Dư mặt xám như tro tàn gầm lên với Hạ Oánh: \"Sao cô có thể tráo thuốc được cơ chứ! Cô tráo thuốc sao lại không nói cho tôi biết! Cô tráo thuốc rồi thì làm sao tôi kiểm soát được tầng tiêm và liều lượng tiêm hả! Đều là do cô hại tôi! Cô đm hủy hoại cuộc đời tôi rồi!\"
Anh ta tức giận đến đỉnh điểm, giơ tay lên như muốn đánh người. Hạ Oánh hoảng hốt lùi lại phía sau, đụng vào chiếc ghế đằng sau ngã sõng xoài xuống đất.
Đoạn cuối cùng của video là một tiếng va đập trầm đục lẫn lộn với tiếng thét chói tai ngắn ngủi của Hạ Oánh.
Bố chồng qua đời rồi.
Điều khiến tất cả mọi người không thể ngờ tới là trong di chúc, ông ấy đã để lại phần lớn tài sản cho Ninh Ninh.
Nhưng tôi lại chẳng lấy làm bất ngờ.
Ngay từ khi tôi quyết định để Ninh Ninh giữ liên lạc với ông nội, tôi đã dự trù đến khả năng này.
Trong mạt chược có một thuật ngữ, gọi là \"giữ bài\". Ý muốn nói, khi quân bài trong tay chưa đủ, cục diện chưa rõ ràng, thì tạm thời giữ lại những quân bài lẻ chưa xác định kia, vừa đánh vừa xem xét, với hy vọng về sau có thể ghép thành nhóm để chờ ù bài.
Hai năm trước, đứa con trai nhỏ \"thiên tài\" đi nước ngoài của bố chồng công khai đồng tính, đồng thời thông báo rõ ràng sau này sẽ định cư ở nước ngoài không trở về nữa. Bố chồng bảo cả đời mình tuy rằng phạm phải sai lầm về mặt đạo đức nhưng giác ngộ cán bộ nhiều năm, ông tuyệt đối sẽ không để tài sản của mình lọt ra nước ngoài.
Thế nên, khi hai mẹ con nhà họ Lục và bố chồng tuyệt giao quan hệ hoàn toàn, tôi vẫn dắt Ninh Ninh tới thăm ông ấy. Khi bố chồng bị bệnh nằm viện, tôi bảo Ninh Ninh bóc quýt cho ông ấy ăn, dùng giọng nói nũng nịu kể chuyện cho ông ấy nghe.
Tôi không chỉ là để Ninh Ninh thực hiện đạo hiếu mà còn là giữ lại cho con bé quân bài chốt hạ tiềm năng kia.
Lục Minh Dư khi biết tin về kết quả phân chia tài sản đã đột nhiên tới tìm tôi.
Anh ta gầy đi rất nhiều, một người vốn luôn theo đuổi sự chỉn chu tinh tế, lúc này tóc tai bù xù, quần áo nhăn nhúm.
Tôi tưởng anh ta đến để gây gổ với tôi, nguyền rủa tôi giống như mẹ anh ta, định mắng tôi là con \"đỉa hút máu\" nhà anh ta nhưng anh ta lại không làm thế.
Anh ta không nói gì, đăm đăm nhìn tôi hồi lâu, như thể không hề quen biết tôi, rất lâu sau, anh ta khản giọng cất lời: \"Hạ Oánh bị sảy thai rồi, Ninh Ninh là đứa con duy nhất còn lại của anh. Hy vọng em chăm sóc tốt cho con, nếu để anh biết em cầm tiền của con mà ngược đãi nó, với tư cách là bố đẻ của con, cho dù có chết anh cũng sẽ đòi lại công bằng cho con.\"
Tôi thản nhiên nhìn anh ta, chỉ hỏi duy nhất một câu: \"Lục Minh Dư, anh có thể nói ra được ngày sinh nhật của Ninh Ninh không?\"
Lục Minh Dư đơ người tại chỗ, há há miệng, rốt cuộc một từ cũng không thốt ra nổi.
Tôi liếc nhìn anh ta đầy khinh bỉ, quay người rảo bước rời đi.
Ngoại truyện
Chu Hữu ôm bó hoa xuất hiện trước mặt tôi.
Anh ấy có chút lúng túng nhưng biểu cảm lại vô cùng nghiêm túc và chân thành: \"Lâm Hiểu, anh thích em của quá khứ, cũng hiểu cho em của hiện tại. Anh bây giờ đã có đủ năng lực để mang lại cuộc sống mà em mong muốn, có thể cho anh một cơ hội được không?\"
Tôi đã từ chối anh ấy.
Trên thế giới này không một ai có thể thực sự hiểu được tôi, mà tôi cũng chẳng cần. Còn về thứ gọi là tình yêu, tôi đã từ bỏ nó từ rất sớm rồi. Lần duy nhất trái tim đập rộn ràng nồng nhiệt thời thiếu nữ đã từng bị chính tay tôi bóp nghẹt, tôi của sau này đã quen với sự bình yên.
Tôi không có niềm tin vào bất kỳ ai, bao gồm cả ông trời.
Ông trời chưa từng đối xử tốt với tôi. Vật cực tất phản, nước đầy sẽ tràn. Tôi sợ mình đòi hỏi quá nhiều, ông trời sẽ lật lọng đòi lại.
Dịp Tết.
Tôi dắt Ninh Ninh về thăm quê một chuyến. Em trai đã thi đỗ cao học, em gái đỗ công chức, bố mẹ mở tiệc linh đình, tiếng pháo nổ vang rền khắp xóm làng, cả dòng họ nở mày nở mặt.
Trong bữa tiệc, Lâm Lợi theo thói cũ ngồi bên cạnh mỉa mai châm biếm tôi: \"Còn tưởng số mày tốt thế nào, chẳng phải cũng rơi vào kết cục ly hôn giống chị sao! Cái loại người như chị em mình ấy mà, không vớ được cái số sướng thì cả đời cũng chỉ đến thế thôi, sau này phải trông cậy vào hai đứa em giỏi giang này rồi!\"
Tôi mỉm cười không nói, giàu có không khoe về quê. Tôi không nói cho gia đình biết quá nhiều, chỉ bảo ly hôn rồi, tự mình dắt con sống qua ngày.
Rượu qua ba tuần, hai em tôi nhìn nhau một cái, đột nhiên bước tới trước mặt tôi, cả hai đứa quỳ xuống dập đầu với tôi hai cái.
Mọi người đều ngẩn ngơ sửng sốt.
Bố mẹ và Lâm Lợi nháo nhác kêu lên: \"Mấy đứa dập đầu nhầm người rồi! Có dập đầu thì cũng phải dập đầu với bố mẹ chứ!\"
Hai em tôi trịnh trọng nói với tôi: \"Cảm ơn chị, chị Hai.\"
Bọn nó bảo với tôi, càng đi ra thế giới bên ngoài, mới càng nhận ra vận mệnh của mình đã xảy ra sự thay đổi nghiêng trời lệch đất đến nhường nào.
\"Chị Hai, chị chắc hẳn đã chịu rất nhiều đau khổ rồi đúng không, sau này hãy để cho bọn em gánh vác nhé.\"
Vành mắt tôi đỏ ửng, lần đầu tiên cảm nhận được trong cuộc đời mình có chỗ dựa vững chắc hóa ra lại là cảm giác này.
Hai năm sau, tôi tham dự một hoạt động từ thiện quyên góp khuyến học, vô tình gặp lại người hàng xóm cũ.
Bà ấy tưởng tôi cũng là tình nguyện viên, không chờ nổi liền vội vã buôn chuyện hóng hớt với tôi: \"Bà mẹ chồng cũ của cô bây giờ thảm lắm nha! Con trai bà ta đi làm việc ở tỉnh khác rồi, nghe đâu sống cũng chẳng ra làm sao, thuê một người hộ lý chăm sóc bà ta, bà ta vừa thấy tôi là khóc lóc kể khổ, bảo người hộ lý không bao giờ đưa bà ta xuống lầu đi dạo, đồ ăn cũng toàn làm loa qua chuyện. Đúng rồi, hai cái chân của bà ta bị cắt bỏ rồi, nghe nói là do biến chứng bệnh tiểu đường...\"
Người dẫn chương trình xướng tên tôi.
Trong ánh mắt bàng hoàng kinh ngạc của người hàng xóm, tôi bước lên sân khấu, quyên góp thêm \"Một triệu tệ\" vào quỹ khuyến học.
Giọng nói vang vọng trầm bổng của người dẫn chương trình vang lên: \"Chúng ta hãy cùng gửi lời cảm ơn tới những nhà hảo tâm vô tư này, chính những việc làm thiện nguyện của họ đã thay đổi số phận của hết đứa trẻ này đến đứa trẻ khác, họ chính là những người tốt của xã hội này!\"
Trong tiếng pháo tay ròn rã, tôi đứng dưới ánh đèn sân khấu, vô cùng thanh thản.
Trong lòng tôi hiểu rõ, tôi đương nhiên không tính là người tốt nhưng tôi cũng chẳng cho rằng mình là kẻ xấu.
Thế giới này vốn dĩ đâu phải chỉ có hai màu đen trắng. Giống như khi Trái Đất tự quay quanh Mặt Trời, ngoài ánh sáng và bóng tối vẫn còn một mảng màu xám bao la vô tận.
Đó là vùng đệm ở giữa của thế giới này và đó cũng chính là một phần của cái thế giới hỗn độn này.
(Hoàn)', 'Chương 6', '', 'publish', 'open', 'closed', '', 'chuong-6', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-6/', '0', 'chuong', '', '0'),
('88', '1', '2026-10-12 16:46:08', '2026-10-12 09:46:08', 'Ngủ với vị giáo sư lạnh lùng hai năm, tôi đã chán rồi.
Cơ bụng của anh không còn hấp dẫn tôi nữa, tư thế bất biến mỗi khi \"lâm trận\" lại càng khiến tôi mất sạch hứng thú.
Thế nên khi bạch nguyệt quang của anh đưa cho tôi tấm séc 20 triệu tệ bảo tôi rời đi, tôi đã cảm động đến rơi nước mắt: \"Cô cứ yên tâm, tôi hứa sẽ biến mất một cách triệt để nhất.\"
Sau đó, tôi để lại cho Diệp Bạc Nhu một mảnh giấy ghi \"Kỹ năng của anh tệ quá\" rồi hiên ngang rời đi.
Nhưng không lâu sau, khi tôi đang ôm quả dừa tươi nằm phơi nắng trên bãi biển, Diệp Bạc Nhu lại từ trên trời rơi xuống, giam cầm tôi trong lòng một cách bệnh hoạn, giọng điệu đầy nguy hiểm: \"Hân Hân, anh mới học thêm 120 tư thế mới, chúng ta thử từng cái một nhé?\"
Tôi: \"!\"', 'TÔI TƯỞNG MÌNH CHỈ LÀ THẾ THÂN, NÀO NGỜ LẠI LÀ NGƯỜI DUY NHẤT ANH YÊU', '', 'publish', 'open', 'closed', '', 'toi-tuong-minh-chi-la-the-than-nao-ngo-lai-la-nguoi-duy-nhat-anh-yeu', '', '', '2026-10-17 11:40:12', '2026-10-17 04:40:12', '', '0', 'https://muopdangcungcovingot.io.vn/truyen/toi-tuong-minh-chi-la-the-than-nao-ngo-lai-la-nguoi-duy-nhat-anh-yeu/', '0', 'truyen', '', '0'),
('89', '1', '2026-10-12 16:48:11', '2026-10-12 09:48:11', 'Ngủ với vị giáo sư lạnh lùng hai năm, tôi đã chán rồi.
Cơ bụng của anh không còn hấp dẫn tôi nữa, tư thế bất biến mỗi khi \"lâm trận\" lại càng khiến tôi mất sạch hứng thú.
Thế nên khi bạch nguyệt quang của anh đưa cho tôi tấm séc 20 triệu tệ bảo tôi rời đi, tôi đã cảm động đến rơi nước mắt: \"Cô cứ yên tâm, tôi hứa sẽ biến mất một cách triệt để nhất.\"
Sau đó, tôi để lại cho Diệp Bạc Nhu một mảnh giấy ghi \"Kỹ năng của anh tệ quá\" rồi hiên ngang rời đi.
Nhưng không lâu sau, khi tôi đang ôm quả dừa tươi nằm phơi nắng trên bãi biển, Diệp Bạc Nhu lại từ trên trời rơi xuống, giam cầm tôi trong lòng một cách bệnh hoạn, giọng điệu đầy nguy hiểm: \"Hân Hân, anh mới học thêm 120 tư thế mới, chúng ta thử từng cái một nhé?\"
Tôi: \"!\"
…
Rèm cửa dày cộp che khuất ánh sáng.
Tôi quỳ hai gối trên giường, người đàn ông phía sau đang hôn lên lưng tôi. Động tác mãnh liệt, tôi dần dần không chịu nổi, đưa tay về phía sau nắm lấy cánh tay anh, đứt quãng van xin. Nhưng Diệp Bạc Nhu lại chẳng chịu dừng lại dù chỉ một giây, giọng nói vốn luôn thanh lãnh lúc này đã nhuốm đầy dục vọng: \"Ai bảo em quyến rũ anh trước? Ráng mà chịu đi.\"
Trời đất chứng giám, tôi thực sự không hề quyến rũ anh. Sự thật là thế này…
4 giờ chiều, tập xong bài tập yoga tôi đi tắm nhưng lại quên mang theo khăn tắm. Vào tầm giờ này, thường thì Diệp Bạc Nhu sẽ không có ở nhà, căn phòng ngủ rộng lớn chỉ có một mình tôi. Vì thế, tôi chẳng có chút gánh nặng tâm lý nào, cứ thế ướt sũng, ngẩng cao đầu bước ra khỏi phòng tắm.
Ai mà ngờ được, Diệp Bạc Nhu lại đột nhiên đẩy cửa đi vào.
Khoảnh khắc bốn mắt nhìn nhau, tôi ngượng đến mức da đầu tê dại, hoảng hốt dời ánh mắt đi nơi khác. Cúi đầu làm như đà điểu, bước nhỏ dịch về phía tủ quần áo lấy khăn tắm chuẩn bị lau người.
Giây tiếp theo, vòng eo đã bị người đàn ông ôm lấy từ phía sau. Hơi thở nóng hổi của Diệp Bạc Nhu phả vào bên tai tôi: \"Để anh lau giúp em.\"
Và rồi, mọi chuyện trở nên không thể kiểm soát nổi...
Nhớ lại lúc đó tôi vô cùng hối hận, sao cái tính hay quên của mình lại lớn thế cơ chứ, lại tự tay đưa mình vào miệng cọp.
Với thể lực của Diệp Bạc Nhu, không tới hai tiếng đồng hồ thì anh sẽ không tha cho tôi đâu. Trong lòng tôi thầm chửi anh là đồ cầm thú nhưng ngoài mặt lại không dám làm loạn.
Dù sao thì, anh cũng là kim chủ của tôi.
Tiền viện phí của mẹ và anh trai tôi là do anh trả. Trang sức trên cổ, vòng ngọc trên cổ tay tôi đều do anh mua. Tôi là một con chim hoàng yến trong lồng vô cùng có đạo đức nghề nghiệp, hiểu rất rõ đạo lý ăn của người thì phải nghe lời người, tuyệt đối không làm điều gì khiến kim chủ không vui.
Thế nhưng trong lòng lại cảm thấy tủi thân. Tôi mệt đến mức cánh tay cũng không nâng nổi mà Diệp Bạc Nhu lại chẳng xót xa cho tôi chút nào.
Nếu đổi lại là bạch nguyệt quang của anh, chắc chắn Diệp Bạc Nhu sẽ không lỡ đối xử với cô ấy như vậy đâu nhỉ? Hài, ai bảo tôi chỉ là kẻ thế thân cơ chứ?
Nói ra thì cũng thật vô lý. Cái sự thế thân này của tôi không phải vì gương mặt giống bạch nguyệt quang mà là vì tấm lưng giống. Chính vì thế khi ở trên giường, Diệp Bạc Nhu thích nhất là kiểu từ phía sau.
Anh trút hết tình yêu không có được dành cho bạch nguyệt quang biến thành dục vọng, phát tiết lên người tôi. Lần nào tôi cũng bị hành cho sống dở chết dở, nhưng lại tức mà không dám nói.
Sau khi trận chiến kết thúc, tôi tức giận ngồi trong nhà vệ sinh, nhắn tin than thở với cô bạn thân Liên Tuyết: \"A a a a, đùi trong của tớ sưng lên luôn rồi, Diệp Bạc Nhu đúng là đồ cầm thú!\"
Liên Tuyết trả lời bằng một hàng dấu chấm ba chấm: \"Đúng là kẻ ăn không hết người lần không ra.\"
\"Nếu cậu biết tớ với Tô Mộ yêu nhau một năm rồi mà còn chưa được ăn thịt, đến cơ bụng anh ấy cũng không cho tớ sờ, cậu cũng sẽ thấy tớ mệnh khổ thôi.\"
Cái này... Hình như cậu ấy còn thảm hơn.
Tôi đề nghị: \"Hay là... tớ mua đồ chơi tình dục gửi tặng cậu chơi nhé?\"
Cô ấy đáp: \"Cảm ơn, tớ có rồi.\" Sau đó gửi sang một tấm ảnh. Tôi nhìn qua, chà chà, mẫu mã đúng là vô cùng phong phú.
Tôi và Liên Tuyết đang tám chuyện hăng hái thì cửa nhà vệ sinh đột nhiên bị gõ từ bên ngoài. Giọng người đàn ông thong thả cất lên: \"Anh bảo dì giúp việc hấp củ mài với bí đỏ cho em rồi, còn xào thêm mấy món nữa, chút nữa đói thì nhớ ăn nhé.\"
\"Anh phải ra ngoài một chuyến, tối nay có thể sẽ về rất muộn, em nghỉ ngơi sớm đi, đừng thức đêm cày phim nữa.\"
Diệp Bạc Nhu trông có vẻ cao ngạo lạnh lùng nhưng thực ra lại cực kỳ thích quản đủ thứ chuyện. Ví dụ như không cho tôi thức đêm, không cho uống nước có ga, cũng không cho ăn đồ ăn vặt như mì ăn liền.
Tôi nghiêm túc nghi ngờ rằng bạch nguyệt quang chính vì không chịu nổi tính kiểm soát của anh nên mới đá anh. Nhưng tôi chỉ là một con chim trong lồng, đành phải ngoan ngoãn đáp lời: \"Em biết rồi ạ.\"
Anh rõ ràng rất hài lòng với sự ngoan ngoãn của tôi, nhếch môi cười một tiếng rồi quay người rời đi.
Trước khi ngủ, tôi lại nhận được tin nhắn của Liên Tuyết: [Chị em ơi, chúc mừng cậu, sắp được giải thoát rồi!]
Tôi nghi ngờ nhấn mở tấm ảnh cô ấy gửi, lập tức nhìn thấy Diệp Bạc Nhu cùng một người phụ nữ trẻ tuổi sóng đôi đi vào hội sở. Người phụ nữ đó dáng người thon thả, vòng eo thon gọn không đầy một nắm tay, đôi chân dài miên man.
Hơi thở của tôi chợt khựng lại. Bởi vì tôi nhận ra, tấm lưng của người phụ nữ này giống tôi tới bảy phần.
Không còn nghi ngờ gì nữa, cô ta chính là bạch nguyệt quang của Diệp Bạc Nhu… Hoàng Thời Vũ.
Liên Tuyết kích động vô cùng: \"Hân Hân, đợi Diệp Bạc Nhu gương vỡ lại lành với cô ta, chắc chắn sẽ đá cậu, đến lúc đó cậu sẽ được tự do!\"
Trên lý thuyết mà nói thì tôi đâu có yêu Diệp Bạc Nhu, chỉ vì tiền nên mới làm kẻ được anh bao nuôi. Anh sắp gương vỡ lại lành với Hoàng Thời Vũ, đáng lẽ tôi phải vui mừng mới đúng nhưng chẳng hiểu sao, lồng ngực tôi lại thắt lại, cảm thấy khó thở, ngay cả cơn buồn ngủ cũng tan biến mất.
Mở to mắt nằm đến tận hai giờ sáng thì nghe thấy cửa phòng ngủ bị đẩy ra. Lòng mề rối bời, tạm thời không muốn trò chuyện với Diệp Bạc Nhu, tôi đành nhắm mắt giả vờ ngủ.
Người đàn ông đi tới, cúi người xuống muốn hôn lên trán tôi. Ở khoảng cách gần như thế, tôi ngửi thấy mùi nước hoa xa lạ trên người anh rất rõ ràng.
Nghĩ đến việc anh có thể vừa mới thân mật với bạch nguyệt quang xong, sống mũi tôi bất giác cay cay. Siết chặt tay dưới chăn, tôi giả vờ như vô thức xoay người, né tránh nụ hôn của anh. Anh rõ ràng bị tôi gạt, không tiếp tục động tác nữa mà nhẹ nhàng đi vào phòng tắm.
Chỉ là, sau khi tắm rửa xong nằm xuống, anh vẫn như thường lệ vươn cánh tay dài ra, ôm trọn tôi vào lòng.
Tôi nghĩ mãi không ra, rõ ràng Hoàng Thời Vũ đã trở về, anh còn thân mật với tôi thế này là có ý gì? Chẳng lẽ Hoàng Thời Vũ vẫn chưa đồng ý quay lại với anh? Cho nên, kẻ thế thân là tôi đây vẫn còn chút giá trị sử dụng?
Nghĩ đến đây, cảm giác chua xát trong lồng ngực càng lúc càng dâng trào mạnh mẽ. Tôi không biết bản thân mình bị làm sao nữa. Rõ ràng từ trước đến nay tôi chỉ coi Diệp Bạc Nhu là ông chủ nhưng tại sao anh lại có thể thao túng vui buồn lẫn lộn của tôi thế này?
Năm quen biết Diệp Bạc Nhu, tôi vừa mới tốt nghiệp đại học, có một người bạn trai đã yêu nhau được hai năm.
Lương lúc đó của tôi không cao, lại ngại không dám xin tiền gia đình nữa. Để mua một chiếc máy ảnh cơ làm quà sinh nhật cho bạn trai Lục Khoảnh, mỗi buổi tối tôi đều đến nhà hàng gần trường Đại học A để đánh đàn piano làm thêm.
', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-2', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-2/', '0', 'chuong', '', '0'),
('90', '1', '2026-10-12 16:50:21', '2026-10-12 09:50:21', 'Diệp Bạc Nhu là sếp lớn của một công ty công nghệ, đồng thời cũng là giáo sư thỉnh giảng của Đại học A, anh thường xuyên đến nhà hàng này dùng bữa. Anh có ngoại hình xuất chúng, khí chất cao quý, luôn là tiêu điểm chú ý của mọi người. Thường xuyên có các mỹ nhân tiến tới bắt chuyện nhưng chưa một ai thành công.
Bởi vậy, tôi hoàn toàn không ngờ tới việc Diệp Bạc Nhu lại chủ động xin phương thức liên lạc của mình.
Tôi nghĩ mãi không hiểu vì sao anh lại có hứng thú với tôi nhưng lúc đó tôi không phải độc thân, dĩ nhiên không thể mập mờ với người khác được. Vậy nên tôi đã từ chối anh.
Biểu cảm của anh có chút mất mát trong thoáng chốc nhưng vẫn lịch sự nói một câu: \"Không sao.\" Rồi để lại cho tôi một tấm danh thiếp: \"Sau này nếu em gặp khó khăn gì, có thể liên lạc với anh bất cứ lúc nào, anh sẽ cố gắng giúp đỡ em.\"
Khi ấy tôi hoàn toàn không biết gia thế của anh hiển hách đến nhường nào, tiện tay nhét danh thiếp vào ngăn kéo túi xách rồi chẳng màng tới nữa.
Cho đến khi anh trai tôi đầu tư thất bại, gánh khoản nợ khổng lồ và bị chủ nợ đánh gãy hai chân. Mẹ tôi nghe tin dữ, vì bị kích động mà nhồi máu cơ tim phải nhập viện. Tôi không đào đâu ra tiền viện phí, tìm đến cầu xin Lục Khoảnh giúp đỡ thì lại bắt trọn khoảnh khắc anh ta ngoại tình.
Đối mặt với dáng vẻ nước mắt giàn giụa của tôi, Lục Khoảnh chỉ hờ hững nói: \"Nếu em đã phát hiện rồi thì anh cũng không giấu em nữa.\"
\"Hân Hân, em thực sự rất nhạt nhẽo, Du Duệ biết cách làm anh vui hơn em nhiều.\"
Cuối cùng, anh ta ném cho tôi mười tấm séc màu đỏ: \"Tiền chia tay đấy, sau này đừng đến tìm anh nữa, Du Duệ nhìn thấy em sẽ không vui đâu.\"
Dưới ba cú sốc liên tiếp, cảm xúc của tôi sụp đổ, ngồi bệt trên lề đường khóc nức nở. Trong làn nước mắt mờ ảo, nhìn thấy một chiếc Cullinan chạy qua, tôi chợt nhớ ra chiếc xe mà Diệp Bạc Nhu đi cũng là dòng này. Tôi cuống quít bới tìm tấm danh thiếp anh để lại rồi bấm gọi số điện thoại đó.
Tính từ lần cuối cùng tôi gặp Diệp Bạc Nhu đến nay đã nửa năm trôi qua. Tôi không chắc anh có chịu giúp tôi hay không nhưng tình thế cấp bách, đành phải coi còn nước còn tát.
Nhưng nằm ngoài dự đoán của tôi, Diệp Bạc Nhu nghe tôi trình bày xong chỉ hỏi một câu: \"Em đang ở đâu?\"
Tôi nghẹn ngào nói ra địa chỉ, anh đáp lại ngắn gọn rõ ràng: \"Chờ anh.\"
Và rồi chưa đầy 15 phút sau, tôi đã nhìn thấy anh.
Anh hình như vừa mới tắm xong, tóc mái lơ thơ rủ trước trán, lông mày và đôi mắt sâu thẳm, đẹp trai đến kinh ngạc. Chỉ có điều chân trái đi giày thể thao, chân phải lại đi giày tây, trông dáng vẻ có vài phần buồn cười.
Nhận ra ánh mắt của tôi, anh cúi đầu nhìn hai bàn chân mình, ngẩn ra một lúc rồi bật cười tự giễu: \"Xin lỗi, lúc ra cửa hơi vội nên để em cười chê rồi.\"
Tôi xua tay lia lịa: \"Làm sao em cười anh được chứ? Muộn thế này rồi mà anh vẫn sẵn lòng ra gặp em, em đã cảm kích lắm rồi.\"
Khóe môi anh đọng lại nụ cười: \"Đã hứa giúp em thì dĩ nhiên anh không thể nuốt lời.\"
Diệp Bạc Nhu nhanh chóng mời đội ngũ chuyên gia đến chữa trị cho mẹ tôi. Lại còn một lần trả hết sạch 30 triệu tệ tiền nợ cho anh trai tôi. Tôi dĩ nhiên biết anh giúp tôi vì mục đích gì.
Từ khoảnh khắc bấm số điện thoại của anh, tôi đã đưa ra lựa chọn rồi. Chỉ là tôi không ngờ, lần đầu tiên giữa tôi và Diệp Bạc Nhu lại buồn cười đến thế. Anh vậy mà không chịu nổi quá 3 phút. Tôi nhịn rồi lại nhịn, cuối cùng vẫn không nhịn nổi bật cười thành tiếng.
\"Không được cười.\" Giọng Diệp Bạc Nhu giận dỗi trầm xuống.
Kim chủ là trên hết, tôi hiểu rõ điều đó nên lập tức thu lại nụ cười, chủ động tìm lý do bào chữa cho anh: \"Cái đó... Mặc dù thời gian vừa rồi hơi ngắn một chút, nhưng lực và tốc độ vẫn rất ấn tượng ạ.\"
Tôi vắt óc suy nghĩ để khen anh nhưng mặt Diệp Bạc Nhu lại càng đen hơn. Anh cúi người cắn lấy môi tôi, chặn đứng mọi âm thanh. Trong hai tiếng đồng hồ tiếp theo, anh dùng hành động thực tế để chứng minh cho tôi thấy anh \"ấn tượng\" đến nhường nào.
Người ngoài chắc chắn không thể ngờ được vị Giáo sư Diệp trông thanh cao cấm dục là thế, khi ở trên giường lại hung mãnh như dã thú. Tới khi tôi gần như không chịu đựng nổi nữa, anh mới nhếch môi, đầy hứng thú hỏi: \"Còn cười nữa không?\"
Tôi là thật sự sợ rồi, thở dốc lắc đầu bảo đảm: \"Không cười nữa đâu...\"
Sau ngày hôm đó, tôi chuyển vào căn biệt thự của Diệp Bạc Nhu. Dần dần, tôi mới biết chữ \"Diệp\" trong Diệp Bạc Nhu chính là họ Diệp danh giá nhất ở Bắc Kinh. Chỉ là anh ra ngoài vốn luôn khiêm tốn, cho nên không có mấy ai biết được gia thế thực sự của anh.
Thỉnh thoảng anh sẽ dẫn tôi đi dự tiệc. Đám công tử thế gia đó đều kính cẩn gọi tôi một tiếng chị dâu. Diệp Bạc Nhu không giải thích, tôi cũng cứ thế an tâm nhận cái danh xưng này. Dù sao thì hai chữ \"bao nuôi\" nói ra thật sự chẳng lọt tai chút nào.
Trong số bạn bè của anh, chỉ có Thẩm Tự là coi thường tôi.
Một lần nọ khi xung quanh không có ai, Thẩm Tự chặn tôi lại, mỉa mai: \"Đừng tưởng bám được vào anh Diệp là cô có thể bước chân vào giới thượng lưu.\"
\"Nếu không phải vì lưng cô giống Thời Vũ thì với nhan sắc tầm thường này của cô, làm sao anh Diệp có thể mắt tới cô được?\"
\"Cô cứ chờ mà xem, đợi Thời Vũ trở về, anh Diệp nhất định sẽ đá cô ra khỏi cửa.\"
Như sợ tôi không tin, anh ta còn cố tình tìm ra tấm ảnh chụp chung giữa Diệp Bạc Nhu và Hoàng Thời Vũ cho tôi xem. Chàng trai và cô gái khoác trên mình bộ đồng phục học sinh, sóng đôi đứng trên thảm cỏ, vô cùng đẹp đôi.
Thẩm Tự hừ lạnh: \"Thấy chưa? Thời Vũ đẹp hơn cô nhiều, cô đến một sợi tóc của cô ấy cũng không sánh bằng!\"
\"Đừng có tưởng bở là anh Diệp sẽ yêu cô, mắt nhìn của anh ấy không có tệ đến thế đâu!\"
Ngũ quan của Hoàng Thời Vũ quả thực kiều diễm hơn tôi rất nhiều, tôi không có cách nào phản bác. Chỉ là đột nhiên tôi ngộ ra một điều… Chả trách Diệp Bạc Nhu lại chủ động bắt chuyện với tôi. Chả trách anh không thèm chớp mắt mà lấy ra 30 triệu tệ giúp gia đình tôi trả nợ. Chả trách anh đối với những người phụ nữ khác đều lạnh lùng tới cực điểm nhưng lại duy nhất dịu dàng cưng chiều tôi. Chả trách mỗi lần \"mây mưa\", anh luôn thích ôm tôi từ phía sau.
Thì ra, tôi chỉ là một kẻ thế thân bằng tấm lưng mà thôi.
Nhưng tôi lại không hề buồn rầu, ngược lại còn cảm kích nói với Thẩm Tự: \"Cảm ơn anh đã cho tôi biết sự thật.\" Có thế thì tôi mới có thể đóng tốt vai trò thế thân của mình, phấn đấu làm cho kim chủ đại nhân hài lòng 100%.
Thế nhưng thời thế đổi thay. Tôi của hai năm sau lại vì sự tồn tại của Hoàng Thời Vũ mà cảm thấy chua xót, phiền lòng. Nghĩ tới việc Diệp Bạc Nhu sẽ mỉm cười với cô ta, sẽ ôm hôn cô ta, lồng ngực tôi lại đau như bị kim châm. Tôi chậm chạp nhận ra, hình như bản thân đã động lòng với Diệp Bạc Nhu mất rồi.
Sáng hôm sau, tôi vẫn không tài nào xốc lại tinh thần, trong đầu ngập tràn hình ảnh Diệp Bạc Nhu và Hoàng Thời Vũ ở bên nhau.
Diệp Bạc Nhu nhận ra sự lơ đãng của tôi, khẽ chau mày: \"Sao thế, tâm trạng không tốt à, ai làm em chịu uất ức rồi?\"
Tôi không dám nói là mình đang ghen, càng không dám thổ lộ tình cảm với anh. Làm thế thân thì phải có giác ngộ của thế thân, làm sao có thể không biết lượng sức mình mà mơ tưởng leo lên vị trí chính thất cơ chứ?
Tôi gượng gạo nặn ra một nụ cười: \"Không sao đâu, em chỉ là chưa ngủ đủ giấc thôi.\"
Sắc mặt anh giãn ra đôi chút: \"Vậy thì nghỉ ngơi tiếp đi, anh xin nghỉ giúp em với cấp trên, hôm nay không đi làm nữa, nhé?\"
Tôi lắc đầu: \"Không cần đâu, em đâu có mỏng manh đến thế.\"
Diệp Bạc Nhu không gượng ép nữa, khóe môi khẽ cong lên: \"Vậy được rồi, tan làm anh qua đón em.\"
Trước kia mỗi khi rảnh rỗi, thỉnh thoảng anh cũng tới đón tôi về nhà. Mấy cô đồng nghiệp trong văn phòng rần rần trêu ghẹo: \"Quan Hân ơi, thật sự ngưỡng mộ cậu quá đi, có anh bạn trai vừa đẹp trai, nhiều tiền lại còn chu đáo nữa.\"
Một kẻ được bao nuôi như tôi thì tính là loại bạn trai bạn gái gì cơ chứ? Trong lòng tôi chột dạ vô cùng, chẳng biết đáp lại thế nào, chỉ đành nở nụ cười nhạt làm câu trả lời.
Chỉ có điều hôm nay Diệp Bạc Nhu lại thất hẹn. Anh nói trong điện thoại: \"Hân Hân, bên anh đột nhiên có việc đột xuất cần xử lý.\"
\"Anh bảo tài xế qua đón em rồi, bên ngoài trời lạnh, em cứ ở trong tòa nhà văn phòng đợi chú ấy nhé.\"
Chẳng hiểu vì sao, phản ứng đầu tiên của tôi là chuyện anh nói có liên quan đến Hoàng Thời Vũ và sự thật chứng minh tôi đã không đoán sai.
Sau khi tài xế tới, tôi vừa ngồi vào ghế sau thì nhận được tin nhắn của Thẩm Tự: \"Thấy chưa, Thời Vũ vừa thấy trong người không khỏe là anh Diệp đã vội vội vàng vàng đến thăm rồi.\"
\"Thời Vũ mới là chân ái của anh ấy, cô mà biết điều thì xách đít cút xéo cho nhanh.\"
\"Đừng có như miếng cao dán chó dại mà bám lấy anh Diệp không buông nữa.\"
Thẩm Tự còn gửi kèm một tấm ảnh Diệp Bạc Nhu đang ngồi trong phòng bệnh trò chuyện cùng Hoàng Thời Vũ.
Cơn giận bị nghẹn trong lồng ngực không lên không xuống được, tôi cảm thấy không dễ chịu chút nào. Tôi hạ cửa kính xe xuống, mặc cho gió lạnh tạt vào mặt nhưng vẫn không làm tan đi nỗi buồn đang trào dâng trong lòng.
Đau lòng đến mức tưởng chừng như nghẹt thở, tôi chỉ có thể khởi động tinh thần A Q, tự lừa dối chính mình. Tôi tự nói với bản thân rằng, Diệp Bạc Nhu thực ra chẳng có gì đáng để yêu. Khuôn mặt anh có đẹp trai đến đâu thì tôi cũng đã nhìn suốt ba năm rồi, chán ngấy từ lâu. Cơ bụng của anh tôi cưỡi bấy lâu nay cũng chơi chán rồi. Mấy cái tư thế hoan ái bất biến nghìn năm kia lại càng nhàm chán vô cùng. Tôi chẳng thèm khát chút tình yêu nào của anh cả.
Tôi liên tục dùng những lời này để tự an ủi bản thân, thế nhưng nước mắt vẫn không tự chủ được mà lăn dài xuống má.
Có lẽ do hóng gió lạnh quá lâu, đêm đó tôi phát sốt cao.
', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-2', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-2/', '0', 'chuong', '', '0'),
('91', '1', '2026-10-12 16:52:24', '2026-10-12 09:52:24', 'Trong lúc mê mê mẩn mẩn, một góc chăn bị người ta vén lên. Người đàn ông nằm nghiêng xuống, nụ hôn rơi trên vành tai tôi, đồng thời bàn tay lớn chui vào gấu áo. Nhưng ngay khi chạm vào cơ thể nóng rực, sắc mặt anh đột ngột biến đổi, au đó, nhanh chóng bế tôi rời giường.
Tôi lại ngửi thấy mùi nước hoa nữ xa lạ kia. Cảm giác chua xát lại ập đến, tôi muốn đẩy Diệp Bạc Nhu ra nhưng toàn thân mệt mỏi rã rời, hoàn toàn không dồn nổi chút sức lực nào, đến cả mắt cũng không mở ra nổi.
Tới khi tôi hoàn toàn tỉnh táo lại từ cơn mê man thì đã là 8 giờ sáng hôm sau. Diệp Bạc Nhu đang ngồi ngay bên giường bệnh của tôi, hình như đã thức trắng cả đêm, dưới quầng mắt có vết thâm mờ mờ.
Thấy tôi mở mắt, anh đưa tay sờ lên trán tôi, thở phào một hơi: \"Cuối cùng cũng hạ sốt rồi, nửa đêm em sốt tới 39 độ, thực sự rất đáng sợ.\"
\"Đói chưa? Dì giúp việc vừa mang cháo qua đây, anh bón cho em nhé.\"
Anh dùng thìa múc cháo, kề sát môi thổi nguội rồi đưa tới đút cho tôi. Tôi không có cảm giác thèm ăn, ăn vài miếng là không muốn mở miệng nữa.
Diệp Bạc Nhu lại kiên nhẫn dỗ dành: \"Ngoan, ăn thêm chút nữa đi, bổ sung thêm dinh dưỡng thì mới mau hồi phục.\"
Anh đối xử với tôi thật sự rất dịu dàng, trong một khoảnh khắc nào đó, tôi suýt chút nữa không kiềm chế được mà muốn hỏi anh, liệu anh có từng xao xuyến chút nào vì tôi hay chưa.
Thế nhưng lời vừa tới khóe môi, tôi lại đột nhiên bừng tỉnh. Anh chăm sóc tôi, hy vọng tôi mau khỏe lại, chẳng qua chỉ là muốn tôi sớm có thể đáp ứng chuyện ân ái với anh mà thôi.
Những thứ này đều chẳng liên quan gì tới tình yêu cả. Thứ anh quan tâm chỉ có thể xác của tôi mà thôi.
Nghĩ đến đây, tâm trạng tôi lại lần nữa chùng xuống.
...
Sau khi cùng tôi ăn xong bữa sáng, Diệp Bạc Nhu tới công ty xử lý công việc. Tôi chán chường tựa vào đầu giường chơi game.
Kèm theo tiếng hiệu ứng \"unbelievable\" vang lên, cửa phòng bệnh bị ai đó đẩy ra. Tôi theo bản năng ngẩng đầu lên, khoảnh khắc nhìn thấy Hoàng Thời Vũ, dây thần kinh của tôi lập tức căng cứng.
Tôi không hề biết bản thân vậy mà lại ở chung một bệnh viện với cô ta, càng không ngờ cô ta lại mò tới tìm mình.
Cô ta tới với thái độ không mấy tốt đẹp, ánh mắt nhìn tôi tràn ngập sự coi thường: \"Cố tình làm cho bản thân phát sốt để Bạc Nhu xót cô à?\"
\"Cô Quan đây cũng lắm tâm cơ quá nhỉ?\"
Làm sao tôi có thể lấy sức khỏe của mình ra làm trò đùa cơ chứ? Nhưng Hoàng Thời Vũ nghĩ như vậy, liệu Diệp Bạc Nhu có giống như cô ta, cho rằng tôi đang diễn kịch không?
Tôi thấy cần thiết phải làm rõ: \"Tôi không cố tình làm mình bị bệnh.\"
Hoàng Thời Vũ nhún vai nói: \"Sao cũng được.\"
\"Cô có phải cố tình giả khổ hay không, chẳng quan trọng.\"
\"Bây giờ điều quan trọng nhất là tôi đã về nước rồi.\"
\"Năm xưa nếu không phải tôi bỏ rơi Bạc Nhu để ra nước ngoài thì tôi với anh ấy đã kết hôn từ lâu rồi, cô làm gì có cơ hội tiếp cận anh ấy.\"
Ánh mắt cô ta rơi trên vết hôn nơi xương quai xanh của tôi, đôi mắt hơi nheo lại. Dấu vết này là do Diệp Bạc Nhu để lại hôm kia. Bị Hoàng Thời Vũ nhìn như vậy, tôi thấy hơi lúng túng, cuống quýt kéo chặt cổ áo.
Cô ta cười giễu một tiếng: \"Bạc Nhu là người đàn ông bình thường, anh ấy có nhu cầu sinh lý, tôi hiểu được.\"
\"Dù sao cô cũng sạch sẽ hơn đám phụ nữ bên ngoài, anh ấy coi cô là công cụ giải tỏa dục vọng, tôi không giận.\"
\"Có điều, tôi lại không ngờ anh ấy đối với cô lại thô bạo như thế.\"
\"Trước kia lúc tôi ở bên anh ấy, anh ấy dịu dàng với tôi lắm, chưa bao giờ để lại vết tích gì trên người tôi cả.\"
Tôi càng thêm ngượng ngùng tủi hổ, viền mắt không kìm được mà đỏ ửng. Muốn phản bác nhưng lại chẳng thốt ra nổi một lời. Dù sao thì từng câu từng chữ Hoàng Thời Vũ nói đều là sự thật. Tôi vốn dĩ chỉ là kẻ thế thân, là công cụ.
Thấy tôi sắp khóc, Hoàng Thời Vũ lại hạ mình tỏ ra độ lượng: \"Cô Quan, tôi không có ý coi thường cô.\"
\"Tôi biết hoàn cảnh gia đình cô không tốt, bán thân trả nợ cũng là đường cùng bất đắc dĩ.\"
Cô ta đặt một tấm séc trị giá 20 triệu tệ lên tủ đầu giường, đưa cho tôi hai sự lựa chọn: \"Hôn ước giữa tôi và Bạc Nhu đã được người lớn hai nhà đưa vào chương trình nghị sự trở lại rồi.\"
\"Cô có thể chọn cầm 20 triệu tệ này rời đi nhưng từ nay về sau tuyệt đối không được xuất hiện trước mặt Bạc Nhu nữa.\"
\"Dĩ nhiên, nếu cô muốn tiếp tục được Bạc Nhu bao nuôi bên ngoài, làm kẻ thứ ba không bao giờ bước ra ánh sáng, tôi cũng chẳng có ý kiến gì.\"
\"Dù sao ở trên giường tôi cũng không thể rẻ mạt được như cô, mặc cho người đàn ông coi mình như đồ chơi.\"
\"Có cô thay tôi phục vụ Bạc Nhu, giúp anh ấy giải tỏa ham muốn, tôi còn phải nói với cô một tiếng cảm ơn đấy chứ.\"
Những lời lẽ của cô ta như con dao nhọn hoắt đâm sâu vào người tôi, đúng nghĩa giết người không thấy máu. Tôi bị đâm cho chằng chịt vết thương, mặt không còn một giọt máu.
Tủi thân, đau đớn, ê chề, đủ loại cảm xúc đan xen vào nhau, nước mắt không sao kìm nén được nữa mà tuôn rơi xối xả.
Tôi nghĩ, tôi không thể tiếp tục ở lại bên cạnh Diệp Bạc Nhu được nữa rồi. Cho dù có thích anh đến đâu, tôi cũng tuyệt đối không trầm mê tới mức làm tiểu tam cho anh. Chi bằng nhận lấy 20 triệu tệ rồi âm thầm rời đi. Không có được tình yêu thì có được tiền cũng tốt.
Tôi sụt sịt mũi, siết chặt lấy tấm séc kia: \"Cô cứ yên tâm, tôi hứa sẽ biến mất một cách triệt để nhất.\"
Hoàng Thời Vũ hài lòng nhếch môi: \"Hy vọng cô nói được làm được.\"
Sau khi cô ta rời đi, tôi lập tức đặt một vé máy bay rồi về nhà lấy giấy tờ tùy thân. Thu dọn xong xuôi tất cả, tôi đứng ở cửa phòng ngủ, đảo mắt nhìn quanh một lượt. Căn phòng này, chỗ nào cũng lưu lại vết tích hoan ái giữa tôi và Diệp Bạc Nhu.
Tôi biết mình không nên trách Diệp Bạc Nhu, dù sao thì anh cũng đã trả nợ giúp anh trai tôi, lại còn chữa khỏi bệnh cho mẹ tôi. Cho dù anh xem tôi là kẻ thế thân nhưng trong cuộc sống chưa từng đối xử tệ bạc với tôi. Đưa cho tôi thẻ đen không giới hạn, mua cho tôi đủ loại trang sức đắt tiền. Làm kim chủ tới mức này thì đã là cực kỳ nhân từ rồi.
Thế nhưng, mỗi khi nhớ tới lời Hoàng Thời Vũ nói anh trên giường vô cùng dịu dàng với cô ta, mà chỉ thô bạo với một mình tôi, tôi vẫn không kìm được sự tủi thân trong lòng.
Tôi uất ức rút tờ giấy ghi chú ra, nắn nót viết từng nét: \"Anh biết không? Kỹ năng giường chiếu của anh thật sự rất tệ.\"
\"Xoay đi xoay lại cũng chỉ có đúng một tư thế, anh không chán chứ em thì chán lắm rồi.\"
\"Tạm biệt anh nhé, từ nay về sau em không bao giờ muốn nhìn thấy anh nữa đâu!\"
Tôi dán mảnh giấy ngay đầu giường, bảo đảm anh vừa nhìn là thấy ngay. Sau đó, thẳng thừng quay đầu bước đi.
Trên đường ra sân bay, điện thoại lại nảy ra tin nhắn ngắn của Diệp Bạc Nhu: [Anh vẫn đang bận, đợi họp xong anh sẽ tới bệnh viện thăm em.]
[Buổi tối em muốn ăn gì? Anh sẽ tự tay xuống bếp nấu cho em.]
Tôi rủ mắt nhìn màn hình, tầm mắt dần dần nhòe đi. Giá như Diệp Bạc Nhu thật lòng yêu tôi thì tốt biết mấy. Vậy thì tôi chắc chắn sẽ là người hạnh phúc nhất trên đời này. Chỉ tiếc là, tôi chỉ là một kẻ thế thân.
Tôi không trả lời. Lau khô nước mắt, kéo số điện thoại của anh vào danh sách đen.
Mẹ và anh trai tôi từ năm ngoái đã chuyển tới thành phố Hải Thành. Khí hậu bên đó tốt, có lợi cho sức khỏe của cả hai người. Tôi dĩ nhiên là tới nương tựa vào họ rồi.
Mẹ nhìn thấy tôi thì vui mừng khôn xiết, nghe tôi bảo định ở lại lâu dài, mẹ lại càng mừng rỡ không khép nổi miệng, quải giỏ xách chạy tọt ra chợ mua thức ăn.
Anh trai tôi tuy không phải chống gậy nữa nhưng khi đi lại vẫn còn hơi thọt một chút. Anh ấy pha cho tôi một ly trà hoa nhài rồi tiện miệng hỏi: \"Giáo sư Diệp nhà em bám người như thế, mà lại nỡ để em ở đây lâu dài sao?\"
Bọn họ đều tưởng Diệp Bạc Nhu là bạn trai tôi.
Đầu năm tôi tới Hải Thành ăn Tết, từ ngày thứ năm trở đi Diệp Bạc Nhu đã liên tục gọi điện giục tôi về. Dạo đó anh trai tôi còn cười trêu: \"Diệp Bạc Nhu trông tính tình lạnh lùng thế, sao riêng tư lại bám người dữ vậy?\"
Đêm đó vừa về tới nơi, tôi đã bị Diệp Bạc Nhu lật qua lật lại hành hạ cả đêm. Anh đâu phải bám tôi chứ? Chẳng qua chỉ là nghiện cái thân thể này của tôi mà thôi.
Càng nghĩ càng tức, tôi bĩu môi: \"Anh, em với Diệp Bạc Nhu chia tay rồi, sau này đừng nhắc tới anh ta trước mặt em nữa.\"
Anh tôi ban đầu định truy hỏi nguyên do nhưng thấy sắc mặt tôi không được tốt nên rốt cuộc cũng không hỏi nữa.
...
Chúng tôi ở cách biển không xa.
Ngày hôm sau tôi rảnh rỗi không có việc gì làm, ôm một quả dừa tươi nằm phơi nắng trên chiếc ghế dài ở bãi biển. Một lúc sau, tôi cầm điện thoại lên xem thì thấy anh trai đã gọi cho mình mấy cuộc điện thoại liền.
Tôi vội vàng gọi lại: \"Anh, có chuyện gì gấp hả anh?\"
Anh tôi giải thích: \"Cũng không có gì gấp, chỉ là Diệp Bạc Nhu vừa tới nhà mình.\"
\"Cậu ấy bảo em vô duyên vô cớ kéo cậu ấy vào danh sách đen, cậu ấy còn chưa hiểu chuyện gì đã xảy ra nữa.\"
\"Anh thấy dáng vẻ cậu ấy có vẻ cuống quýt lắm, nên đã nói chỗ em đi cho cậu ấy biết rồi.\"
\"Hân Hân, anh thấy Diệp Bạc Nhu đối xử với em thật lòng rất tốt, hai đứa nếu có hiểu lầm gì thì cứ nói rõ ràng ra là được, đừng có dễ dàng nói chia tay kẻo sau này lại hối hận.\"
Tôi ngốc luôn tại chỗ.
Lý do tôi dám đường đường chính chính tới Hải Thành là vì tôi quá hiểu sự kiêu ngạo cao ngạo của Diệp Bạc Nhu. Tôi đá anh, với tính cách của anh thì cả đời này cũng chẳng bao giờ chủ động đi tìm tôi. Hoàn toàn không ngờ tới việc anh lại sát phạt tìm tới nhanh như thế.
Chẳng lẽ là vì tôi chê bai kỹ năng giường chiếu của anh quá kém, làm tổn thương lòng tự trọng của anh nên trong lúc giận dữ anh cố tình tới đây trừng phạt tôi?
Tiêu tùng rồi. Liệu anh có trói tôi lại, dùng thắt lưng quật vào mông tôi để trút giận không? Nghĩ đến đây, tôi sợ tới mức rùng mình một cái.
Sau khi nhìn thấy bóng dáng thon dài quen thuộc ở cách đó không xa, tôi lại càng hoảng sợ vô cùng. Cúp điện thoại, tôi vắt chân lên cổ chạy bán sống bán chết.
', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-2', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3-2/', '0', 'chuong', '', '0'),
('92', '1', '2026-10-12 16:55:52', '2026-10-12 09:55:52', 'Vì chạy quá vội nên không nhìn thấy hòn đá, tôi bị vấp ngã nhào xuống đất, đầu gối va đập đau điếng. Tới khi tôi bò dậy muốn chạy tiếp thì Diệp Bạc Nhu đã chộp lấy cánh tay tôi, nhíu mày nhìn xuống đầu gối của tôi.
\"Em chạy cái gì chứ? Đường cũng không thèm nhìn nữa.\"
Anh rất ít khi nghiêm túc như thế này trước mặt tôi, tôi lại càng sợ hơn, cố gắng giả vờ bình tĩnh nhắc nhở anh: \"Diệp Bạc Nhu, đánh người là phạm pháp đấy.\"
\"Nếu anh dám đụng vào một ngón tay của tôi, tôi sẽ... tôi sẽ báo cảnh sát!\"
Anh vô cùng khó hiểu: \"Anh đánh em làm gì?\"
Tôi cẩn trọng dò hỏi: \"Chẳng lẽ anh không phải vì nhìn thấy tờ giấy kia nên mới cố tình tới đây báo thù tôi sao?\"
Anh bất lực nhìn tôi: \"Anh đâu có hẹp hòi đến thế.\"
\"Hân Hân, anh chỉ muốn biết rốt cuộc em bị làm sao, tại sao đột nhiên cho anh vào danh sách đen, rồi lại tại sao phải bỏ đi.\"
\"Đừng có dùng mấy cái từ chán rồi để lừa anh, anh không tin đâu.\"
Anh thông minh quá mức rồi.
Tôi nghiến răng, ngả bài nói huỵch tẹt ra luôn: \"Anh sắp kết hôn rồi, tôi không cho anh vào danh sách đen thì lẽ nào lại đi làm tiểu tam cho anh?\"
Anh kinh ngạc đến mức khó tin: \"Em nghe tin đồn thất thiệt đó từ đâu ra thế?\"
\"Anh nói sắp kết hôn từ bao giờ, mà sao có chuyện anh để em làm tiểu tam được chứ?\"
Còn giả vờ nữa à? Tôi vạch mần luôn: \"Còn muốn gạt tôi à? Hoàng Thời Vũ đã nói cho tôi biết tất cả rồi!\"
Lông mày Diệp Bạc Nhu nhíu chặt lại, giọng điệu trầm xuống: \"Cô ta đã nói gì với em?\"
Tôi khai sạch bách: \"Cô ta bảo hai người là thanh mai trúc mã, có hôn ước từ sớm. Lần này cô ta về nước chính là để cùng anh thực hiện hôn ước.\"
\"Lại còn nói, ở trên giường anh dịu dàng với cô ta lắm, chẳng nỡ làm cô ta đau chút nào.\"
Mặt Diệp Bạc Nhu đen kịt lại, cao giọng phản bác: \"Em đừng có nghe cô ta ăn nói xà lơ!\"
\"Anh chưa bao giờ đụng vào Hoàng Thời Vũ cả, lấy đâu ra chuyện dịu dàng với cô ta chứ?\"
Anh chăm chú nhìn tôi sâu sắc, từng câu từng chữ nghiêm túc nói: \"Quan Hân, lần đó với em chính là lần đầu tiên của anh.\"
\"Nếu không thì sao anh có thể không chịu nổi quá 3 phút chứ?\"
Hả? Tôi thật sự bị sốc rớt cả hàm. Lúc đó tôi còn tưởng anh độc thân quá lâu, súng lâu ngày không mài nên bị gỉ sét. Kết quả, anh vậy mà lại là trai tơ?
Tôi không dám tin cho lắm: \"Thật hay giả thế? Anh không phải đang lừa tôi đấy chứ?\"
\"Anh với Hoàng Thời Vũ ở bên nhau lâu như thế, thật sự chưa đụng vào cô ta lần nào?\"
Diệp Bạc Nhu giơ tay giữ chặt lấy vai tôi, một lần nữa nghiêm túc nhấn mạnh: \"Quan Hân, ngoại trừ em ra, anh chưa từng chạm vào bất kỳ người phụ nữ nào khác.\"
\"Anh thừa nhận anh và Hoàng Thời Vũ từng có hôn ước, nhưng đó là do người lớn hai bên đính ước, anh chưa từng thích cô ta.\"
Tôi càng hoang mang hơn: \"Anh không thích cô ta, vậy mắc gì lại tìm kẻ thế thân như tôi?\"
\"Tại sao có thể cùng cô ta ra vào hội sở?\"
\"Rồi còn vì cô ta bị bệnh nhập viện mà vội vội vàng vàng đi thăm, bỏ mặc tôi cho tài xế?\"
Biểu cảm của Diệp Bạc Nhu thậm chí còn ngơ ngác hơn cả tôi: \"Ai nói với em, em là kẻ thế thân?\"
\"Quan Hân, trong mắt anh em là chính em, không phải là bản sao của bất kỳ ai cả.\"
Tôi bĩu bĩu môi: \"Nhưng Thẩm Tự cũng bảo, anh đối tốt với tôi là vì lưng tôi giống Hoàng Thời Vũ.\"
Diệp Bạc Nhu cuối cùng cũng nhận ra giữa chúng tôi có quá nhiều hiểu lầm, thở dài một tiếng, đem mọi chuyện phân tích rành rọt giải thích cho tôi nghe.
Trước khi gặp Quan Hân, Diệp Bạc Nhu chỉ quan tâm tới sự nghiệp, chưa từng để ý tới chuyện tình cảm đôi lứa. Vì thế, khi người lớn yêu cầu anh liên hôn gia tộc, Diệp Bạc Nhu đã không phản đối. Ba năm đính hôn đó, anh và Hoàng Thời Vũ luôn tương kính như tân, giữ đúng lễ nghĩa, chưa từng có hành vi vượt quá giới hạn.
Sau đó, Hoàng Thời Vũ chê anh nhàm chán, nổi loạn đi yêu một ca sĩ nhạc rock rồi chủ động đề nghị hủy hôn với anh. Diệp Bạc Nhu cũng thấy không sao cả, mặc kệ cô ta. Sự ra đi của Hoàng Thời Vũ không hề gây ra bất kỳ ảnh hưởng nào tới cuộc sống của anh. Anh vẫn từng bước từng bước tập trung vào công việc. Đời sống tình cảm hoàn toàn là một tờ giấy trắng.
Cho đến khi anh gặp Quan Hân ở nhà hàng gần Đại học A. Cô luôn mặc một chiếc váy trắng bằng vải xô, ngồi trước cây đàn piano màu đen, những ngón tay linh hoạt nhảy múa, khóe môi đọng lại nụ cười dịu dàng. Mỗi lần nhìn thấy Quan Hân, tâm trạng của Diệp Bạc Nhu đều trở nên rất bình yên. Cho dù công việc kinh doanh của anh có gặp phải rắc rối hóc búa đến đâu, chỉ cần nhìn thấy Quan Hân là mọi phiền não đều thần kỳ tan biến.
Cho nên, dù cho ngày hôm đó Diệp Bạc Nhu không có lịch dạy, anh vẫn sẵn lòng bỏ ra một tiếng đồng hồ lái xe chạy vòng tới nhà hàng dùng bữa, chỉ vì muốn ngắm cô đánh đàn.
Dần dần, anh không còn thỏa mãn với việc chỉ đứng nhìn Quan Hân từ xa nữa. Anh muốn đến gần cô hơn một chút, vì vậy mới lấy hết dũng khí tiến tới xin phương thức liên lạc của cô.
Đó là lần đầu tiên trong đời anh chủ động nhưng lại chuốc lấy thất bại thảm hại. Nghe Quan Hân nói cô đã có bạn trai, Diệp Bạc Nhu không thể kìm nén được cảm giác hụt hẫng nhưng ranh giới đạo đức không cho phép anh làm ra hành vi đập chậu cướp hoa.
Dù vậy anh vẫn dùng chút \"tâm cơ\" để lại cho cô phương thức liên lạc, hy vọng trong tương lai giữa anh và cô có khả năng viết nên câu chuyện.
Đêm nhận được cuộc gọi từ Quan Hân, Diệp Bạc Nhu cuối cùng cũng nếm trải thế nào gọi là tim đập liên hồi như trống dồn. Anh gần như là không chờ nổi nữa mà lao ngay ra cửa đến gặp cô, ngay cả việc xỏ nhầm giày cũng không hề hay biết.
Khi Diệp Bạc Nhu ra tay giúp đỡ Quan Hân, thực ra anh không hề nghĩ tới chuyện bắt cô báo đáp. Anh chỉ muốn có một cái cớ để tiếp cận cô, rồi sau đó mới từ từ phát triển tình cảm.
Nhưng Quan Hân lại hiểu sai ý, cô lại lầm tưởng anh muốn cơ thể của mình. Diệp Bạc Nhu vừa định lên tiếng giải thích thì Quan Hân đã kiễng chân hôn lên môi anh.
Khả năng tự chủ mà Diệp Bạc Nhu luôn tự hào đã hoàn toàn sụp đổ vào khoảnh khắc đó. Anh gần như ngay lập tức ôm chặt lấy cô, đáp lại nụ hôn của cô một cách cuồng nhiệt hơn, cùng cô hòa làm một, quấn quít không rời.
Diệp Bạc Nhu dĩ nhiên biết rõ, Quan Hân lúc này đối với anh chỉ toàn là sự cảm kích. Nhưng không sao cả, anh sẵn lòng chờ đợi. Anh sẽ nỗ lực đối tốt với cô, cho tới ngày cô tự nguyện mở rộng trái tim mình đón nhận anh.
Diệp Bạc Nhu gặp lại Hoàng Thời Vũ hoàn toàn là ngoài ý muốn. Đêm đó, Thẩm Tự gọi anh đi tham gia buổi tụ tập. Anh tới nơi mới biết, thì ra đó lại là tiệc tẩy trần đón Hoàng Thời Vũ trở về.
Cô ta và gã ca sĩ nhạc rock kia đã chia tay, gã lừa tiền cô ta để nuôi bồ nhí, Hoàng Thời Vũ tức giận đá văng gã. Diệp Bạc Nhu chẳng mảy may quan tâm tới đời sống tình cảm của Hoàng Thời Vũ nên không đưa ra ý kiến gì.
Lần gặp gỡ thứ hai là ở bệnh viện. Trên đường Diệp Bạc Nhu tới đón Quan Hân thì xảy ra một vụ va chạm. Có người già vượt đèn đỏ, anh không thắng kịp nên đối phương bị thương. Diệp Bạc Nhu đành phải đưa người già đó vào bệnh viện trước, rồi gọi điện bảo tài xế ở nhà thay anh đi đón Quan Hân.
Ở bệnh viện, anh gặp phải Thẩm Tự. Thẩm Tự bảo Hoàng Thời Vũ bị bệnh dạ dày phải nhập viện. Với tư cách bạn bè, Diệp Bạc Nhu tiện đường ghé qua xem thử một cái. Anh không hề biết Hoàng Thời Vũ có dã tâm khác đối với anh, càng không ngờ Thẩm Tự lại cùng cô ta làm loạn, đến trước mặt Quan Hân đơm đặt chia rẽ.
Sau khi biết rõ sự thật, Diệp Bạc Nhu cực kỳ áy náy, trong lúc anh không hay biết, Quan Hân vậy mà đã phải chịu nhiều uất ức đến thế. Anh phải đòi lại công bằng cho cô, cho nên ngay lập tức bấm số gọi cho Thẩm Tự, giọng nói lạnh gáy: \"Ai cho phép cậu ăn nói bậy bạ với Quan Hân? Cút ngay qua đây xin lỗi cô ấy mau.\"
Thẩm Tự vẫn như gã ngốc, chưa nắm rõ tình hình: \"Anh Diệp, em làm vậy đều là muốn tốt cho anh thôi mà!\"
\"Anh thích Thời Vũ nhiều năm như thế, khó khăn lắm mới đợi được cô ấy chia tay, anh cuối cùng cũng có cơ hội ở bên cô ấy, em dĩ nhiên không thể để Quan Hân phá hỏng hạnh phúc của anh được!\"
Diệp Bạc Nhu tức quá hóa cười: \"Con heo nó còn thông minh hơn cậu đấy.\"
\"Tôi nói thích Hoàng Thời Vũ từ bao giờ?\"
\"Người tôi yêu từ đầu chí cuối chỉ có một mình Quan Hân.\"
Thẩm Tự xịt keo cứng ngắc. Anh ta tự cho mình là bà mối mát tay, kết cục lại hóa thành hề xiếc.
Trong lúc Thẩm Tự còn đang đẫn đờ ngơ ngác, sự kiên nhẫn của Diệp Bạc Nhu đã cạn sạch: \"Tốt nhất cậu mau dắt Hoàng Thời Vũ tới đây xin lỗi ngay, bằng không thì tự gánh lấy hậu quả.\"
Thẩm Tự quá hiểu rõ thủ đoạn giông bão của Diệp Bạc Nhu. Nếu anh ta và Hoàng Thời Vũ không chịu xin lỗi, Diệp Bạc Nhu nhất định sẽ dùng những biện pháp không bình thường để giáng đòn vào việc kinh doanh của hai nhà Thẩm, Hoàng. Đến lúc đó, người lớn hai nhà vì lợi ích chắc chắn sẽ trói trặt hai đứa bọn họ lại đem nộp cho Diệp Bạc Nhu xử lý.
Chính vì thế, sau khi cúp điện thoại, Thẩm Tự lập tức đi tìm Hoàng Thời Vũ.
Hoàng Thời Vũ nghe anh ta tường thuật lại toàn bộ quá trình, tức điên người: \"Thẩm Tự, rốt cuộc anh có bị ngu không hả?\"
\"Nếu không phải anh nói với tôi là Diệp Bạc Nhu còn nặng tình với tôi, thậm chí còn tìm một con hồ ly tinh làm thế thân của tôi thì sao tôi có thể bị anh dắt mũi, vắt óc suy nghĩ tìm cách bắt đầu lại với anh ấy cơ chứ?\"
\"Lại càng không thể chạy tới trước mặt Quan Hân giương oai giễu võ, rồi còn đe dọa bắt cô ta rời đi!\"
\"Hôm qua thành phố Bắc Kinh sấm chớp đùng đùng, sao không đánh chết cái đồ ngu như anh đi cho rồi?\"
Tuy gia thế nhà họ Thẩm kém xa nhà họ Diệp nhưng Thẩm Tự cũng là đại công tử được cưng chiều từ nhỏ tới lớn, làm sao chịu nổi sỉ nhục như thế này. Anh ta tức giận đáp trả ngay: \"Hoàng Thời Vũ, cô lấy cái quyền gì mà chửi tôi?\"
\"Lúc phát hiện gã ca sĩ nhạc rock đó ngoại tình, không phải cô đã hối hận vì bỏ qua hôn ước với anh Diệp để chọn một gã ăn cơm mềm sao?\"
\"Cô năm lần bảy lượt chủ động tìm tôi nghe ngóng chuyện tình cảm của anh Diệp, không phải là đang nhen nhóm ý định quay lại với anh ấy à?\"
\"Bây giờ xảy ra chuyện thì cô lại đùn đẩy hết trách nhiệm lên đầu một mình tôi, cô còn biết xấu hổ nữa không đấy?\"
Hoàng Thời Vũ tức đến mức run cầm cập, tát một cú trời đánh lên má Thẩm Tự: \"Anh dám chửi tôi thêm một câu nữa xem?\"
Thẩm Tự cũng chẳng phải kẻ có tính tình tốt lành gì, dùng sức hất một cái làm cô ta ngã văng ra xa hai mét. Hoàng Thời Vũ đang mang giày cao gót nên xui xẻo bị trật khớp cổ chân, trong cơn giận dữ túm lấy cái gạt tàn ném thẳng vào người Thẩm Tự.
Thời điểm Thẩm Tự và Hoàng Thời Vũ bay tới Hải Thành xin lỗi tôi, cả hai người đều mang thương tích trên mình. Người trước trên trán quấn băng gạc, người sau cổ chân quấn băng nẹp, trông khá giống một cặp hoạn nạn có nhau. Có thể thấy hai người bọn họ đều vô cùng sợ hãi Diệp Bạc Nhu, thái độ xin lỗi cực kỳ thành kính.
Thẩm Tự trực tiếp quỳ thụp xuống trước mặt tôi: \"Chị dâu ơi, em xin lỗi chị.\"
\"Tất cả là tại em có mắt như mù, ngu ngốc như heo, mới làm ra lắm chuyện thất đức như thế.\"
\"Chị có thể giúp em xin anh Diệp nương tay, đừng đụng tới công ty của ba em được không?\"
\"Bằng không ba em chắc chắn sẽ khóa thẻ của em, rồi đày em sang châu Phi mất!\"
Hoàng Thời Vũ không đến mức mặt dày vô liêm xỉ như Thẩm Tự nhưng cũng chân thành thốt ra lời xin lỗi: \"Xin lỗi cô, là do tôi tự đa tình.\"
\"Bạc Nhu chỉ yêu một mình cô, từ đầu đến cuối đều là do tôi tự diễn kịch một mình.\"
\"Hy vọng cô rộng lượng bỏ qua, đừng chấp nhặt với tôi.\"
Tôi chấp nhận lời xin lỗi nhưng sau này không muốn nhìn thấy bọn họ nữa. \"Hai người đi đi, sau này đừng bao giờ xuất hiện trước mặt tôi nữa.\"
Hai người vẫn không dám nhúc nhích, đồng thời phóng ánh mắt về phía Diệp Bạc Nhu.
Diệp Bạc Nhu lạnh lùng quét mắt qua họ: \"Cút! Bắt đầu từ hôm nay, chúng ta không còn là bạn bè nữa.\"
\"Sau này đi trên đường có gặp tôi và Hân Hân thì nhớ đi vòng đường khác.\"
Tới lúc này, hai kẻ kia mới như được đại xá, cuống cuồng bỏ chạy. Dáng vẻ bỏ chạy trối chết kia làm gì còn nửa phần phách lối hống hách như trước đây? Tôi không nhịn được mà bật cười thành tiếng.
Diệp Bạc Nhu áp hai bàn tay nâng niu đôi má tôi: \"Bây giờ vui rồi chứ?\"
Tôi cười: \"Ừm.\"
Anh khẽ chạm môi lên môi tôi: \"Vậy tối nay anh có thể không ngủ sofa nữa được chưa?\"
Căn nhà của anh tôi là căn hộ ba phòng ngủ một phòng khách. Mấy mẹ con ba người chúng tôi mỗi người chiếm một phòng.
', 'Chương 4', '', 'publish', 'open', 'closed', '', 'chuong-4-2', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-4-2/', '0', 'chuong', '', '0'),
('93', '1', '2026-10-12 16:56:38', '2026-10-12 09:56:38', 'Mặc dù ngày hôm đó sau khi Diệp Bạc Nhu đã nói rõ ràng mọi chuyện với tôi thì chúng tôi đã hòa giải nhưng tôi cũng không cho anh vào phòng ngủ, mà bảo anh ra khách sạn ở.
Dù sao thì căn nhà này cách âm không được tốt lắm, mà lần nào anh ở trên giường cũng gây ra tiếng động quá lớn. Tôi đâu dám cho anh thể hiện ở đây, kẻo để mẹ với anh tôi nghe thấy thì ngại chết đi được.
Nhưng Diệp Bạc Nhu lại không muốn ở khách sạn, thế là anh chỉ đành ngậm ngùi ngủ ở ghế sofa. Chiều cao 1m88 của anh nằm trên chiếc sofa đó thực sự có chút ngắn. Đôi chân dài miên man không có chỗ gác, nhìn qua quả thực rất chịu uất ức.
Tôi lại đề nghị lần nữa: \"Anh có thể ra khách sạn ở mà.\"
Anh làm nũng: \"Em đi khách sạn ở cùng anh thì anh mới đi.\"
Tôi từ chối: \"Không chịu đâu.\"
Mặt anh hiện rõ vẻ tủi thân: \"Tại sao chứ?\"
Tôi bĩu môi: \"Lần nào anh cũng làm lâu chết đi được, em mệt lắm.\"
\"Vậy anh hứa lần này một tiếng là kết thúc trận chiến, được không?\" Anh mè nheo nịnh bợ: \"Hân Hân, chúng mình đã bảy ngày rồi chưa làm chuyện đó đấy, em không nhớ anh sao?\"
Đúng là có chút nhớ nhung thật nhưng tôi lại càng tò mò hơn: \"Tại sao lần nào anh cũng thích ôm em từ phía sau? Lần nào cũng dùng chung một tư thế, anh không thấy chán sao?\"
\"Anh tưởng là em thích chứ.\" Anh giải thích: \"Lần đầu tiên của hai đứa mình, anh vốn dĩ định đi vào từ phía trước, là tự em quay người lại đưa lưng về phía anh mà.\"
Hả? Là như vậy sao? Tôi chăm chú lục lọi lại ký ức trong đầu, lúc này mới nhớ ra, đêm đó lúc Diệp Bạc Nhu ôm tôi, mắt anh cứ nhìn chằm chằm tôi không chớp. Tôi bị nhìn đến mức xấu hổ ngượng ngùng, hoàn toàn không dám đối diện với anh nên đành phải quay người đi.
Phá án xong rồi. Đây cũng là một cú hiểu lầm siêu bực mình.
Tháng thứ hai sau khi hòa giải với Diệp Bạc Nhu. Anh siết lấy eo tôi, đặt tôi ngồi lên bàn đảo bếp, khẽ hôn lên vành tai tôi rồi thì thào: \"Bảo bối, hôm nay chúng ta lại thử thêm một tư thế mới nhé, được không em?\"
Anh bây giờ mỗi ngày một chiêu trò mới, có vài tư thế còn vô cùng kỳ quái. Nếu không nhờ tôi kiên trì tập yoga nhiều năm, độ dẻo dai của cơ thể đủ tốt thì chắc gãy xương từ lâu rồi.
Tôi sụp đổ: \"Sao vẫn còn tư thế mới nữa vậy?\"
Anh cười khúc khích: \"Không phải em từng chê anh cổ hủ sao? Anh đã bổ túc thêm rất nhiều kiến thức đấy.\" Nụ hôn của anh dần dần dời xuống dưới: \"120 tư thế, chúng ta có thể lần lượt thử từng cái một.\"
Tôi kinh ngạc: \"Có nhiều loại đến thế cơ à?\"
Khóe giọng anh ngân dài ngập tràn ý cười: \"Em thử rồi chẳng phải sẽ biết sao?\"
Tôi: \"!\"
Hoàn.', 'END', '', 'publish', 'open', 'closed', '', 'chuong-5-2', '', '', '2026-10-13 13:31:34', '2026-10-13 06:31:34', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-5-2/', '0', 'chuong', '', '0'),
('94', '1', '2026-10-12 17:03:24', '2026-10-12 10:03:24', '', '8222e4aa-a30c-4188-a82c-99e9d4b7feb1', '', 'inherit', 'open', 'closed', '', '8222e4aa-a30c-4188-a82c-99e9d4b7feb1', '', '', '2026-10-12 17:03:24', '2026-10-12 10:03:24', '', '88', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/8222e4aa-a30c-4188-a82c-99e9d4b7feb1.png', '0', 'attachment', 'image/png', '0'),
('96', '1', '2026-10-12 20:24:56', '2026-10-12 13:24:56', 'Sau khi chồng tôi vì cứu tôi mà rơi xuống nước, anh ấy được một người đàn ông cao lớn uy vũ bế kiểu công chúa ra ngoài.
Trước mắt tôi đột nhiên xuất hiện màn hình bình luận:
[A a a, cảnh kinh điển lần đầu gặp mặt - Đại Mãnh Công 1 hô hấp nhân tạo cho Tiểu Thụ tới rồi!]
[Nam chính nhìn thấy tiểu thụ ướt sũng suýt nữa không nhịn được, cứ thế \"ba ba ba\" cho đến khi chán mới thôi.]
[Đều tại nữ phụ, làm cho tiểu 0 của chúng ta không còn sạch nữa, nữ phụ sống lỗi xứng đáng cuối cùng chôn thân trong bụng cá.]
[Sau này sạch là được rồi, hì hì hì.]
Đến lúc này tôi mới bừng tỉnh, hóa ra tôi là nữ phụ pháo hôi trong một cuốn tiểu thuyết đam mỹ.
Thấy mông của chồng sắp không giữ nổi, tôi \"thình thịch\" lao lên, giơ tay tát cho người đàn ông kia một phát nổ đom đóm mắt: \"Dám giành đàn ông với bà đây, tìm chết đấy à!\"
', 'TÔI PHÁT HIỆN MÌNH LÀ NỮ PHỤ PHÁO HÔI TRONG TRUYỆN ĐAM MỸ', '', 'publish', 'open', 'closed', '', 'toi-phat-hien-minh-la-nu-phu-phao-hoi-trong-truyen-dam-my', '', '', '2026-10-17 11:42:09', '2026-10-17 04:42:09', '', '0', 'https://muopdangcungcovingot.io.vn/truyen/toi-phat-hien-minh-la-nu-phu-phao-hoi-trong-truyen-dam-my/', '0', 'truyen', '', '0'),
('97', '0', '2026-10-12 20:31:19', '2026-10-12 13:31:19', 'Trang Đổi Mật Khẩu', 'Đổi Mật Khẩu', '', 'publish', 'closed', 'closed', '', 'doi-mat-khau', '', '', '2026-10-12 20:31:19', '2026-10-12 13:31:19', '', '0', 'https://muopdangcungcovingot.io.vn/doi-mat-khau/', '0', 'page', '', '0'),
('98', '0', '2026-10-12 20:31:19', '2026-10-12 13:31:19', 'Trang Cài Đặt Đọc Truyện', 'Cài Đặt Đọc Truyện', '', 'publish', 'closed', 'closed', '', 'cai-dat-doc-truyen', '', '', '2026-10-12 20:31:19', '2026-10-12 13:31:19', '', '0', 'https://muopdangcungcovingot.io.vn/cai-dat-doc-truyen/', '0', 'page', '', '0'),
('99', '1', '2026-10-12 20:31:32', '2026-10-12 13:31:32', '', 'cadbd1c7-1ac7-4603-b015-ae365ec69dc3', '', 'inherit', 'open', 'closed', '', 'cadbd1c7-1ac7-4603-b015-ae365ec69dc3', '', '', '2026-10-12 20:31:32', '2026-10-12 13:31:32', '', '96', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/cadbd1c7-1ac7-4603-b015-ae365ec69dc3.png', '0', 'attachment', 'image/png', '0'),
('100', '1', '2026-10-12 20:33:36', '2026-10-12 13:33:36', 'Sau khi chồng tôi vì cứu tôi mà rơi xuống nước, anh ấy được một người đàn ông cao lớn uy vũ bế kiểu công chúa ra ngoài.
Trước mắt tôi đột nhiên xuất hiện màn hình bình luận:
[A a a, cảnh kinh điển lần đầu gặp mặt - Đại Mãnh Công 1 hô hấp nhân tạo cho Tiểu Thụ tới rồi!]
[Nam chính nhìn thấy tiểu thụ ướt sũng suýt nữa không nhịn được, cứ thế \"ba ba ba\" cho đến khi chán mới thôi.]
[Đều tại nữ phụ, làm cho tiểu 0 của chúng ta không còn sạch nữa, nữ phụ sống lỗi xứng đáng cuối cùng chôn thân trong bụng cá.]
[Sau này sạch là được rồi, hì hì hì.]
Đến lúc này tôi mới bừng tỉnh, hóa ra tôi là nữ phụ pháo hôi trong một cuốn tiểu thuyết đam mỹ.
Thấy mông của chồng sắp không giữ nổi, tôi \"thình thịch\" lao lên, giơ tay tát cho người đàn ông kia một phát nổ đom đóm mắt: \"Dám giành đàn ông với bà đây, tìm chết đấy à!\"
…
Chồng tôi, Cố Duy Trạch, sở hữu nhan sắc vô cùng xinh đẹp.
Làn da trắng sữa căng mịn mọng nước, lúc nào cũng ửng lên ánh hồng nhạt. Chân tay thon dài, cân đối và hoàn toàn không có chút tính công kích nào. Đặc biệt là đôi mắt nai con ngây thơ, lúc nào cũng ướt đẫm long lanh, khơi lên gợn sóng trong lòng người đối diện.
Lần đầu gặp mặt, anh đứng đọc sách dưới cây hoa anh đào trong trường, cánh hoa theo gió nhẹ nhàng đáp xuống vai anh. Khung cảnh đó trông như nam thần vẻ đẹp giáng thế, làm tôi mê tới mức thần hồn điên đảo.
Người ta thường nói ai đứng dưới cây anh đào cũng đẹp nhưng khi cả hai chúng tôi cùng đắm mình dưới cơn mưa hoa anh đào: anh đẹp như diễm quỷ, còn tôi thô kệch như Lý Quỳ.
Cứ như vậy, tôi đã khổ công theo đuổi Cố Duy Trạch rất lâu.
Tôi độc thân từ trong trứng, thật sự không biết làm sao để tán người ta. Bạn cùng phòng bày mưu tính kế cho tôi, bảo tôi hãy phát huy ưu thế của bản thân.
Tôi vò đầu bứt tai suy nghĩ, dung mạo tôi bình thường, đầu óc cũng không coi là thông minh. Ưu thế lớn nhất chính là \"tứ chi phát triển\".
Thế là, khi Cố Duy Trạch học bài ở thư viện, tôi chủ động đến thay giùm bình nước khoáng cho phòng tự học. Khi Cố Duy Trạch ăn cơm ở nhà ăn, tôi – người đang làm thêm ở đây – vung chảo xào rau múa may cuồng nhiệt. Dưới ánh nắng chiếu qua cửa sổ, cơ tay trước của tôi lấp lánh tỏa sáng.
Bạn cùng phòng tôi tuyệt vọng ngã gục: \"Hạ Kình, người ta là khổng tước xòe đuôi khoe sắc, còn cậu là gà trọc đầu làm trò hề. Theo kiểu tán này của cậu, Cố Duy Trạch chưa chắc đã cảm động đâu, nhưng rất có thể sẽ nhận cậu làm đại ca cầm đầu đấy.\"
Mấy lời này làm tôi suýt nữa thì nản lòng. Nhưng không ai ngờ tới, xoay chuyển tình thế, Cố Duy Trạch lại chủ động tỏ tình với tôi.
...
Vẫn là dưới cây hoa anh đào đó, vành tai anh đỏ bừng, mắt chứa chan ý cười, rực rỡ và tươi đẹp như mùa xuân.
\"Hạ Kình.\" Giọng nói trong trẻo của thiếu niên hơi run rẩy vì hồi hộp: \"Cậu có đồng ý ở bên tớ không? Tuy tớ không mạnh mẽ như cậu nhưng tớ cũng sẽ cố gắng bảo vệ cậu!\"
Ánh mắt anh chân thành, đong đầy sự sùng bái và ngưỡng mộ. Giống như từ trên trời rơi xuống một chiếc bánh pizza khổng lồ, sướng đến mức tôi vác anh lên vai, chạy cuồng nhiệt quanh gốc cây ba vòng.
Suy nghĩ kéo về hiện tại.
Mười phút trước, vì cứu tôi bị rơi xuống nước, Cố Duy Trạch đã không chút do dự nhảy xuống biển, hoàn toàn quên mất bản thân không biết bơi. Trong chớp mắt, tôi - người vốn cũng không biết bơi - nhờ có sức trâu bò, hai tay quạt nước điên cuồng, cứng cỏi đập nước trồi lên mặt biển, vật lộn tiến tới để vớt anh.
Ngay khoảnh khắc chạm vào Cố Duy Trạch, bên cạnh có một bóng đen sẫm lao vụt tới, như đã có mưu đồ từ trước, anh ta ôm lấy Cố Duy Trạch bơi về phía bờ. Đến khi tôi cũng dốc sức bơi được vào bờ, bóng người kia đã bế anh theo kiểu công chúa lên bờ.
Đó là một người đàn ông lạ mặt cao hơn tôi và cũng đô con hơn tôi. Cố Duy Trạch tái nhợt dưới sự làm nền của làn da màu đồng cổ của anh ta, trông như một bông hoa mong manh yếu đuối.
Ngay lúc này, trước mắt tôi đột nhiên xuất hiện hàng loạt bình luận chạy:
[Tới rồi tới rồi! Cảnh kinh điển lần đầu gặp mặt, Đại Mãnh Công 1 hô hấp nhân tạo cho tiểu thụ!]
[Công vừa nhìn đã ưng, thấy tiểu thụ ướt sũng suýt nữa không nhịn được, hóng cảnh sau này làm tới chán chê mới thôi!]
[Nữ phụ sao còn chưa chết đuối đi, đầu độc tiểu thụ của chúng ta lâu như vậy, có thể mau cút đi được không?]
[Thôi bỏ đi, sao tiểu thụ trong truyện đam mỹ lại có vợ vậy? Đầu óc tác giả chứa phân à?]
[Hí hí, trai thẳng bị bẻ cong mới càng có cảm giác tương phản đúng không?]
[Đúng thế đúng thế, đều tại nữ phụ, làm cho tiểu 0 của chúng ta không sạch sẽ nữa rồi.]
[Sau này sạch là được rồi, hì hì hì.]
Bộ não bị úng nước của tôi xoay chuyển với tốc độ ánh sáng. Hóa ra, thế giới này là một cuốn tiểu thuyết đam mỹ. Tôi là nữ phụ pháo hôi đáng lẽ ra phải chết đuối. Còn chồng tôi, Cố Duy Trạch, là nam chính tiểu thụ sắp sửa bị nát mông.
Bình luận vẫn tiếp tục chạy.
[Mới nghĩ đến cảnh tiểu thụ lúc nào cũng cứng miệng nói mình là trai thẳng bị nhốt vào phòng tối, bị làm cho hai mắt mất tiêu cự, buộc phải vẫy đuôi xin tha mà thấy tuyệt diệu không gì bằng.]
[666 lầu trên đúng là dân sành ăn rồi.]
[Mau nhìn kìa, tiểu thụ đã mở mắt rồi, anh Tần của mấy người chẳng thèm quan tâm, nhất định phải hôn cho đã mới chịu thôi!]
Thấy gã đàn ông nhắm mắt lại, sắp sửa hôn lên môi Cố Duy Trạch. Chân tôi như giẫm bánh xe phong hỏa, \"thình thịch thình thịch\" lao tới. Một tay kéo Cố Duy Trạch vào lòng, tay kia vung vẩy tát mạnh, nhắm thẳng vào khuôn mặt tràn ngập dục vọng kia mà vả!
\"Cái mỏ thối quắc kia chực ủi đi đâu đấy? Cả cái vuốt bẩn thỉu của mi nữa, nếu không muốn dùng nữa thì bà đây chặt giùm cho! Đừng tưởng bà đây không thấy, nhà ai cứu người mà cái tay cứ sờ sờ mó mó xuống hạ bộ hả?\"
Gã đàn ông bị tôi tát cho ngơ ngác, khuôn mặt lạnh lùng góc cạnh lập tức sưng vù như đầu heo.
Bình luận chạy điên cuồng:
[... Ơ bà chị, chị cầm tinh con chó dại à?]
[Sao cô ta dám ra tay với Tần Thế?! Đó là đại lão tung hoành giới thương trường đấy, khuôn mặt đẹp trai như thế mà bị đánh sưng vù rồi!]
[A a a tôi chịu không nổi rồi, anh công mau băm cô ta thành nhân bánh sủi cảo đem cho chó ăn đi.]
Người đàn ông tên Tần Thế nổi giận đùng đùng, không chút do dự muốn phản công. Khối cơ bắp danh tiếng được bình luận xưng tụng là đã kinh qua mưa bom bão đạn gồng cứng lên, cuốn theo sức mạnh khó lường lao thẳng về phía tôi!
Nếu là người bình thường, e rằng cái đầu đã bị anh ta đánh xoay ngược ra sau rồi.
Nhưng tôi là người phụ nữ mạnh mẽ như chim ưng kiêu hãnh. Từ nhỏ đã coi mấy đứa con trai ăn hiếp mình như pháo ném mà chơi, lớn lên càng tinh thông đối kháng, tản đả, võ thuật... Kỷ lục cao nhất là một hơi đánh gục cả đám đàn ông cơ bắp cuồn cuộn ở võ quán.
Sau nữa, tôi trở thành vận động viên võ thuật đối kháng chuyên nghiệp. \"Sức mạnh tạo nên kỳ tích\" chính là tín ngưỡng của tôi.
Thế là tôi xoay tay vả thêm một phát nữa.
\"Bà đây ghét nhất loại đàn ông đánh phụ nữ, đồ rác rưởi!\"
Tần Thế ngã cái rầm xuống đất.
Màn hình bình luận run rẩy bần bật:
[... Ơ bà chị ơi, hình như là chị ra tay trước mà.]
[Nói là nữ phụ pháo hôi cơ mà? Cái đ*ch mẹ này rõ ràng là Hulk bản nữ mà?]
[Cảnh hô hấp nhân tạo kinh điển đâu rồi? Trả tiền lại đây!]
Lông mi Cố Duy Trạch run nhẹ, đáng thương tội nghiệp rúc vào lòng tôi: \"Vợ ơi, người này... muốn ăn hiếp anh hu hu. May mà có em bảo vệ anh. Vợ là tốt nhất~\"
Tôi ghép nối từ các bình luận để biết được hướng đi đại thể của cuốn sách này.
Trong tiểu thuyết Chưởng Trung Hoa (Bông Hoa Trong Lòng Bàn Tay), Cố Duy Trạch là một đóa hoa kiều diễm đúng nghĩa. Anh đẹp đẽ nhưng yếu đuối, tính cách lại bướng bỉnh không chịu khuất phục.
Để chinh phục anh hoàn toàn, nam chính Tần Thế không ngần ngại biến anh thành đồ chơi của mình, nhốt vào căn hầm tối tăm không thấy ánh mặt trời, tha hồ hành hạ vui đùa. Hết lần này đến lần khác, cho đến khi linh hồn anh bị đập tan hoàn toàn, sụp đổ tuyệt vọng, hèn mọn van xin, hoàn toàn trở thành bông hoa trong lòng bàn tay Tần Thế.
Nói thật, tôi hoàn toàn không thể cảm nhận được cái gọi là “cưỡng chế ái” mà mấy dòng bình luận nhắc tới.
Tôi chỉ muốn dùng đôi bàn tay như kìm sắt của mình vặn nát cái gáo dừa của Tần Thế, ném vào hầm cầu, để anh ta không bao giờ có thể làm hại Cố Duy Trạch nữa.
Tần Thế không phải là tên khốn đầu tiên ra tay với Cố Duy Trạch.
Giống như gánh chịu một lời nguyền, Cố Duy Trạch bẩm sinh rất dễ thu hút những thứ dơ bẩn. Anh trai hàng xóm lớn lên cùng nhau sẽ giả vờ khám sức khỏe, bắt anh cởi sạch quần áo. Hồi đi học, bạn cùng phòng mượn cớ thời gian gấp gáp để chen vào phòng tắm chật hẹp tắm chung với anh. Thậm chí sau khi đi làm, vị sếp nam lạnh lùng cũng lấy cớ cùng nhau tăng ca để đụng chạm mờ ám vào cơ thể anh.
', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-3', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-3/', '0', 'chuong', '', '0'),
('101', '1', '2026-10-12 20:34:55', '2026-10-12 13:34:55', 'Khi Cố Duy Trạch kể cho tôi nghe những chuyện này, thái độ thản nhiên như thể đang kể chuyện của người khác. Nói rất hời hợt, như thể người bị vận xui bám đuổi không phải là anh.
\"Thế anh đã làm gì?\" Tôi hỏi.
Anh ranh mãnh lè lưỡi:
\"Lúc anh hàng xóm bảo anh cởi đồ, anh thừa lúc anh ta không chú ý đã nhét tất vào miệng anh ta, làm anh ta tức đến nhảy dựng.\"
\"Khi bạn cùng phòng chen vào phòng tắm, anh giả vờ vô ý dùng dao cạo râu cạo cho cậu ta một cái chỏm hói Địa Trung Hải.\"
\"Lúc tên sếp xán lại gần, anh ‘vô tình’ bật cuộc họp trực tuyến, cả công ty đều thấy anh ta mặc quần lót màu đỏ uốn éo làm trò làm dáng.\"
Anh từng phiền não vì bản thân dù có tập gym đẩy tạ thế nào cũng không luyện ra được cơ bắp săn chắc có lực. Dù có ba ngày không tắm, trên người vẫn có hương thơm thoang thoảng. Dù giận dữ đến đâu, chất giọng vẫn luôn trong trẻo dịu dàng.
Mật mã mà thế giới này cài đặt cho anh, dường như sinh ra là để phụ thuộc và làm vui lòng kẻ khác, đến cả sự tức giận cũng dịu dàng đáng yêu.
Nhưng tôi biết, Cố Duy Trạch chưa bao giờ là đóa hoa yếu đuối. Anh vừa dũng cảm vừa kiên cường, dù nhận ra sự độc ác bủa giăng khắp nơi trong thế giới hoang đường này, anh vẫn dốc hết sức mình xoay xở giữa một bầy muông thú sài lang.
Từ bờ biển trở về, Cố Duy Trạch đột nhiên trở nên hơi kỳ lạ.
Trước đây anh luôn liến thoắng kể cho tôi nghe những chuyện thú vị xảy ra khi đi làm. Dù là dì bán bánh tráng nướng dưới công ty cho thêm một cái xúc xích, hay cây phát tài sếp cưng xỉu lại bị ai đó lén tưới nước sôi. Nhưng dạo gần đây, anh lại im lặng một cách bất thường.
Buổi tối, Cố Duy Trạch đi tắm. Điện thoại của anh trên bàn đột nhiên phát ra tiếng chuông thông báo liên hồi. Nghĩ tới biểu hiện bất thường dạo này của anh, tôi quả quyết mở điện thoại ra.
Hộp thư thoại dừng lại ở những lời khiêu khích chướng mắt:
[Món mồi mà tôi nhắm tới, chưa từng có ai chạy thoát.]
[Cố tình mặc thế này, còn bảo không phải đang quyến rũ tôi?]
[Đồ lẳng lơ.]
Chuông cửa đột nhiên vang lên. Nhân viên giao hàng đưa tới một chiếc hộp, tôi mở ra, bên trong lại là một món đồ chơi người lớn kích thước cực khủng. Kèm theo một bức ảnh chụp lén từ góc sau.
Cố Duy Trạch đứng trước bục giảng, hình như đang thuyết trình điều gì đó. Ánh đèn trên bục sáng một cách vô lý, nhìn từ phía sau, chiếc áo sơ mi trắng của anh như bị xuyên thấu, trông gần như nửa trần trụi. Đường nét cơ thể mượt mà lộ rõ không sót chút nào.
Mặt sau bức ảnh là một dòng chữ cực kỳ ngông cuồng: [Ngứa thì tự mình giải quyết trước đi, sớm muộn gì cũng có ngày em phải khóc lóc van xin tôi thưởng cho em.]
Chữ ký người gửi là Tần Thế.
Màn hình bình luận lại bắt đầu gào thét:
[Nữ phụ tiện thế không biết? Lại đi lén xem điện thoại của thụ bảo.]
[Ka ka ai mà ngờ được anh Tần trực tiếp thu mua luôn công ty của tiểu thụ, người đàn ông này đúng là bá đạo ngang ngược.]
[Chồng cô sắp thành của người ta rồi kìa~]
[Xui xẻo, con gà chua này lại sắp sửa làm trò rồi.]
Tôi không hề do dự gọi video sang. Tần Thế nhanh chóng bắt máy, giọng điệu ngạo mạn: \"Tưởng em gồng được bao lâu, mới thế đã chịu không nổi rồi à?\"
Khoảnh khắc ánh mắt chạm phải tôi, anh ta sững người: \"Sao lại là cô?\"
Đôi mắt đỏ ngầu nheo lại, tỏa ra hơi thở cực kỳ nguy hiểm: \"Nếu không phải vì vật nhỏ kia van xin tôi, giờ này cô đã bị tôi ném xuống biển làm mồi cho cá rồi. Tôi khuyên cô nên biết điều một chút, tự mình cút đi, còn dám trêu vào tôi nữa thì Ngọc Hoàng Đại Đế cũng không cứu nổi cô đâu!\"
Tôi gật gật đầu, một tay nhấc món đồ chơi nhỏ kia lên, dí sát vào ống kính. Rắc một cái, món đồ chơi làm bằng hợp kim bị tôi bóp nát thành bột mịn.
Trong ánh mắt không thể tin nổi của anh ta, tôi thốt ra từng chữ một: \"Đừng gấp, kẻ tiếp theo, chính là mi đấy.\"
Ngày hôm sau, tôi cải trang thành thợ giao nước, trà trộn vào công ty của Cố Duy Trạch. Tôi vác bình nước khoáng đi như bay, rất nhanh đã tìm tới khu làm việc của anh.
Trong phòng nghỉ có mấy người đang hóng hớt dưa: \"Thấy chưa? Giám đốc Cố lại bị Tần tổng gọi riêng vào văn phòng rồi.\"
\"Sao cô vẫn gọi là Giám đốc Cố thế? Người ta tuần trước đã bị chuyển công tác thành trợ lý của Tần tổng rồi.\"
\"Hả? Cố Duy Trạch không phải đại thần công nghệ sao? Chuyển thành trợ lý chẳng khác nào bị giáng chức?\"
\"Cô thì biết cái gì, làm trợ lý cho Tần tổng là có thêm cực kỳ nhiều cơ hội tiếp xúc cự ly gần với sếp đấy!\"
\"Là cự ly gần hay là cự ly âm hả? Ha ha ha...\"
Mấy kẻ đó hiểu ngầm nhìn nhau rồi cười rộ lên một cách bỉ ổi.
Tôi nhấc bổng bình nước khoáng lên, rút nút ra, nhắm thẳng vào mấy người bọn họ mà xả nước xối xả. Mấy kẻ bị nước dội cho lạnh thấu xương hoảng hốt nhảy dựng lên:
\"Mày là ai thế hả? Có bệnh à!\"
\"Mày có biết bộ vest của tao bao nhiêu tiền không? Bán mày đi cũng không đền nổi đâu.\"
\"Mẹ nó thật là xui xẻo!\"
Tôi choảng một cái quăng bình nước xuống đất: \"Cái miệng thối thế, bình thường chắc chắn không rửa cẩn thận đúng không? Đây là dịch vụ miễn phí, không cần cám ơn.\"
Tôi chộp lấy cà vạt của một đứa, giọng nói dữ tợn: \"Văn phòng Tần Thế ở đâu?\"
Tầng 23.
Khác với khu làm việc ồn ào náo nhiệt bên dưới, nơi đây yên tĩnh và trầm lắng, khắp nơi đều tỏa ra hơi thở riêng tư và nguy hiểm.
Cánh cửa lớn sang trọng của văn phòng Tổng giám đốc đóng chặt, đến cả âm thanh cũng bị cách ly hoàn toàn. Chỉ có tấm biển \"Xin đừng làm phiền\" treo lạnh lẽo ở chính giữa.
Tôi càng đi lại gần, màn hình bình luận lại nhảy ra càng lúc càng nhanh:
[Hóng cảnh văn phòng play cuối cùng cũng tới rồi!]
[Lần đầu tiên đã kích thích thế này, Tần Thế không hổ là vua công, hóng cảnh kinh điển thụ bảo sụp đổ tuyệt vọng mang cảm giác tan vỡ~]
[Tất cả mọi người hãy giữ cho quần lót khô ráo nhé!]
Người đàn ông đeo kính trông như thư ký đi ra ngăn cản tôi: \"Xin lỗi, cô không có lịch hẹn, không thể vào.\"
Không có lịch hẹn? Nó muốn động vào mông chồng bà, đã hẹn trước với bà chưa?
Tôi ấn mạnh tay nắm cửa. Thấy không cản được, gã đeo kính khoanh tay trước ngực, mỉa mai nói: \"Cánh cửa này làm bằng hợp kim có độ chính xác siêu cao, chỉ có thể mở từ bên trong thôi, nếu không sợ trật khớp tay thì cô cứ việc ở đó tốn sức tiếp đi.\"
Nói xong, gã ung dung ngồi một bên xem trò vui.
Lảm nhảm cái quái gì thế? Tôi đột ngột nhấc chân, tích lực.
Bốp!
Chỉ bằng một cú đá, cánh cửa dày nặng đã bị tôi đạp cho lõm một mảng. Tách cà phê trên tay gã đeo kính rơi thẳng xuống đất. Tôi nghiêng người, bồi thêm một cú đá xoay vòng cực mạnh, cánh cửa lớn cuối cùng cũng đổ rầm xuống đất.
Cảnh tượng trong phòng làm tôi suýt nữa thì nổi giận xì khói đầu.
Tần Thế một tay đè Cố Duy Trạch, tay kia đang kéo thắt lưng của anh. Dưới đất vương vải roi da và còng tay, cùng vài thứ đồ chướng mắt khác.
Bình luận lại bắt đầu chạy điên cuồng:
[Sao nữ phụ lại tới nữa rồi? Có phải cô ta gắn rada trên quần tiểu thụ không thế?]
[Tôi phục thật rồi đấy, cánh cửa to đùng thế kia mà bị cô ta đạp đổ? Tôi còn tưởng Diệp Vấn tới nơi chứ.]
[Nam chính ông lú lẫn rồi à, thời gian nói mấy lời nhảm nhí đó thà làm thụ thành bánh su kem từ lâu rồi...]
Tôi không rảnh đâu mà xem bình luận, trong mắt tôi lúc này chỉ có viền mắt đỏ bừng và hàm răng cắn chặt của Cố Duy Trạch.
Tần Thế, mi đã tự tìm đường chết! Cơn giận tự đáy lòng bùng phát: \"Dám dòm ngó mông của chồng bà, chết đi cho rồi!!!\"
\"Cô còn thật sự dám tới.\" Tần Thế lưu luyến đứng dậy, dục vọng vẩn đục trong mắt còn chưa tan hết: \"Lần trước là do tôi không phòng bị, tưởng tôi sợ cô chắc?\"
Anh ta thong thả xắn tay áo lên, để lộ những đường cơ bắp săn chắc, thấp thoáng còn có vài vết sẹo chém đan xem.
Bình luận reo hò:
[Buồn cười ghê, thật sự tưởng vị thế đại lão của anh Tần là nhờ thừa kế mà có à?]
[Để thụ bảo của chúng ta chứng kiến thế nào gọi là đực của các loại đực, công của các loại công.]
[Nữ phụ ơi lần này chị thật sự đá phải tấm thép rồi!]
Tôi không cho anh ta thời gian để làm màu, bước tới một bước, chộp lấy mái tóc kiểu tổng tài bá đạo đã xịt gel keo láng bóng của anh ta, quay anh ta thành một chiếc cối xay gió hình người.
\"Thích nằm trên đúng không? Thử nếm cú đấm móc tuyệt tự tuyệt tôn của bà đây!\"
Tiếng thảm thiết vang vọng khắp cả tòa nhà văn phòng.
[??? Sao màn hình lại đen thui rồi?]
[Cái gì gọi là \"do hình ảnh quá mức bạo lực, tạm thời không tiện hiển thị\"?]
[Tác giả, tác giả mau ra đây nói một câu đi chứ!]
', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-3', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-3/', '0', 'chuong', '', '0'),
('102', '1', '2026-10-12 20:36:07', '2026-10-12 13:36:07', 'Nửa tiếng sau, Tần Thế nằm trên mặt đất trông như một búp bê vải tơi tả, tóc tai rũ rượi, giọng nói khàn đặc, nơi khóe mắt còn vương giọt lệ trong suốt. Bộ vest phẳng phiu vốn phẳng lì không một hạt bụi giờ hệt như cái giẻ lau, bị một sức mạnh ẩn danh nào đó xé rách thành từng mảnh tan tác.
Cả căn phòng càng giống như vừa trải qua một trận cuồng phong bão táp, lấy tôi và Cố Duy Trạch làm tâm, tàn phá càn quét, không chừa lại một mảnh nào.
[Cái tình tiết cứt chó này rốt cuộc là sao thế?]
[Xin đấy, ai có thể dắt con Godzilla nữ này đi giùm không!]
[Tại sao thụ bảo lại dùng ánh mắt ngưỡng mộ lấp lánh nhìn nữ phụ vậy? Chẳng lẽ anh ấy không nên xót xa cho Tần Thế sao?]
[Nói trên mạng chút thì được, chứ ngoài đời thực ai mà chẳng muốn được nữ phụ bảo vệ điên cuồng cơ chứ.]
Tôi quăng Tần Thế sang một bên như quăng rác, vội vàng kiểm tra tình hình của Cố Duy Trạch.
Cổ áo sơ mi trắng bị xé rách vô tội vạ, bờ môi dưới đỏ mọng vì luôn cắn chặt mà đã hơi tụ máu.
\"Vợ ơi.\" Làn sương mờ dâng lên trong đôi mắt mọng nước của anh: \"Sao em lại tới đây?\"
Anh cuống quít cởi giày tất của tôi ra, ngón tay mần mò từng chút một trên bàn chân tôi: \"Đạp cửa dùng lực mạnh thế, chân em có bị đau không?\"
Màn hình bình luận mỉa mai giễu cợt:
[Tiểu thụ ơi cậu thật sự nên đi khám mắt đàng hoàng đi, con nữ Kim Cương này thì làm sao mà có chuyện gì được chứ?]
[Tôi thấy anh công hình như sắp chết rồi kìa...]
[Có người tốt nào nói cho tôi biết, rốt cuộc đây là đam mỹ hay là kiếm hiệp thế?]
Từ trong họng Tần Thế rên ra lời đe dọa khàn đục: \"Đừng tưởng rằng cô đã thắng, thứ tôi muốn có, chưa từng có lúc nào không lấy được. Cô tự ý xông vào khu vực làm việc trọng yếu, hành hung tôi, tôi sẽ thuê luật sư giỏi nhất, khiến cô cả đời này cũng không ra khỏi tù được đâu!\"
Mặt anh ta dữ tợn, ánh mắt hận không thể nghiền nát tôi.
Cố Duy Trạch âm thầm chắn tôi ở phía sau, giọng nói ôn hòa không nhanh không chậm: \"Tần Thế, tôi nghĩ anh sẽ có hứng thú xem thử thứ trong túi quần của tôi đấy.\"
Sau khi nhìn rõ thứ trong tay anh, Tần Thế trợn tròn mắt: \"Mày tính kế tao?\"
Cố Duy Trạch cất đồ lại vào túi: \"Anh mà dám động thêm một chút ý đồ xấu xa nào nữa, tôi dám bảo đảm, đoạn video này ngày mai sẽ xuất hiện trên mạng. Ngoài ra, phiền anh xếp người làm thủ tục nghỉ việc cho tôi, khoản bồi thường nên có không được thiếu một xu.\"
Sau đó nắm tay tôi rời đi. Phía sau lưng, truyền tới những tiếng gầm gừ không phục của Tần Thế.
...
Thấy Cố Duy Trạch móc từ cổ áo ra một chiếc camera giấu kín, tôi mới biết hóa ra tất cả đều là kế hoạch của anh.
\"Anh ta dùng em để uy hiếp anh, đương nhiên anh không thể tha cho anh ta.\"
Người vốn dịu dàng như nước, ánh mắt đột nhiên trở nên lạnh lùng băng giá. Anh phải có được bằng chứng sắt đá trực quan và có sức nặng hơn mới có thể hạ gục Tần Thế. Thế nên anh mới im lặng chấp nhận quyết định chuyển công tác, không chút do dự giả vờ sa vào bẫy của Tần Thế.
Tôi vò rối tóc anh một cách dữ dội: \"Anh ta căn bản đánh không lại em, em đâu có sợ anh ta!\"
\"Nhưng em còn có võ quán, còn có giấc mơ đoạt đai vô địch đai vàng của em nữa.\" Anh hơi rũ mắt xuống: \"Anh không muốn một kẻ tồi tệ như anh ta gây ra bất kỳ phiền phức nào cho em. Vợ ơi.\"
Cố Duy Trạch nhẹ nhàng nắm lấy nắm đấm của tôi, giọng nói mềm mại nhưng chứa đựng sự kiên định không chút nghi ngờ: \"Anh cũng muốn nỗ lực bảo vệ em.\"
Anh nhìn tôi thật chăm chú và nghiêm túc. Ánh mắt dịu dàng như một làn nước hồ khiến tôi chìm đắm không lối thoát.
Động tĩnh của chúng tôi trong văn phòng đã thu hút rất nhiều người.
Trên đường đi ra, gã đeo kính cố gắng giải tán đám đông nhưng dân hóng hớt vẫn nườm nượp chen chúc lao tới.
\"Nghe nói Tần tổng và anh thợ giao nước đánh nhau to vì nam thần Cố đấy?\"
\"Đúng là lam nhan họa thủy, sức hút quá lớn cũng là một cái tội.\"
\"Đừng đùa chứ, một thằng đàn ông toàn thân trắng trẻo mềm mại thơm tho thế, tôi thấy rõ ràng là cố tình quyến rũ người khác.\"
\"Anh Lý hình như anh chảy nước miếng kìa.\"
Cố Duy Trạch mặt không cảm xúc dắt tôi đi về phía thang máy, hoàn toàn phớt lờ những tiếng bàn tán xung quanh.
Có một cô gái mặt tròn lộ vẻ khinh bỉ: \"Sao lại có người phụ nữ thô bạo dã man như thế chứ? Rõ ràng là con đười ươi cái chưa tiến hóa hết.\"
Đám đông hóng hớt xì xầm nhỏ to: \"Xì, đừng nói bậy, tin vỉa hè đấy, nghe đâu đó là vợ của nam thần Cố, dân tập võ đối kháng đấy.\"
\"Thảo nào nam thần tính tình tốt thế, trong nhà có con Bạo Long Chiến Đấu thế kia, ai mà dám cảm xúc không ổn định?\"
\"Tám phần là bị cưỡng ép rồi, tội nghiệp ghê.\"
Cô gái mặt tròn bĩu môi dài tới chân trời, ánh mắt nhìn tôi như nhìn bãi phân trâu làm vẩn đục bông hoa tươi: \"Cứ như người dã man chưa khai hóa ấy, đúng là làm xấu mặt phụ nữ chúng ta!\"
Tôi chẳng thèm chấp nhặt đấu khẩu với một đứa con gái nhỏ. Thế nhưng Cố Duy Trạch - người vừa nãy còn coi lời đồn đại như gió thoảng bên tai - lại đột ngột đứng khựng lại.
\"Lúc vợ tôi ở trên đài thi đấu giành vinh quang về cho tổ quốc, cô chỉ biết ở trong phòng nghỉ mỉa mai xỉa xói đồng nghiệp nào thay bạn trai mới, trang web nào trốn việc khó bị phát hiện hơn. Tôi không nghĩ cô có đủ thẩm quyền và tư cách để đánh giá cô ấy.\"
Anh vốn dĩ luôn rất ôn hòa, chưa bao giờ nặng lời với ai. Đây là lần đầu tiên anh không nể mặt một ai tới mức này.
Cô gái mặt tròn mặt mày đỏ bừng, cắn môi rơm rớm nước mắt: \"Tôi, tôi không có ý đó. Tôi chỉ thấy, cô ta không xứng với anh!\"
Chút dịu dàng cuối cùng trên mặt Cố Duy Trạch cũng biến mất sạch bách. Cả người tỏa ra khí ảm đạm lạnh lẽo.
\"Tôi nhớ văn phong của cô khá tốt đấy.\" Anh chậm rãi cất lời: \"Có phải cô cảm thấy, chỉ có những nhân vật trong mấy truyện H văn nhỏ lấy tôi làm nam chính mà cô viết mới xứng với tôi?\"
Đám đông xôn xao ồ lên. Cô gái mặt tròn lảo đảo sắp ngã, những giọt mồ hôi to như hạt đậu rơi xuống từ trên trán.
\"Anh, sao anh lại biết...\"
Cô ta là một hủ nữ chính hiệu, từ khi nhìn thấy Cố Duy Trạch đã không kìm lòng được mà gán cho anh cái mác \"Tiểu thụ do trời định\". Về sau càng âm thầm lén lút lấy anh làm nhân vật chính, viết rất nhiều H văn ghép đôi vớ vẩn, phát cho mấy đồng nghiệp thân thiết cùng xem. Dù bị phát hiện cũng chẳng sao, dù gì Giám đốc Cố nổi tiếng tính tình hiền lành.
\"Cô sử dụng mạng nội bộ công ty để phát tán những thứ này, toàn bộ lịch sử sử dụng đều đã được đồng bộ lên đám mây rồi, tôi sẽ gửi tài liệu cho cơ quan chức năng nhờ họ giải quyết. Xúc phạm tới vợ tôi, tôi sẽ không nương tay.\"
Cô gái hoàn toàn quỵ ngã ngồi bệt xuống đất.
Trước kia từng có người đoán rằng, có khi nào Cố Duy Trạch mang tính cách thích lấy lòng người khác, kiểu không có vạch ranh giới hay không. Lần này, tất cả mọi người đều đã nhìn thấy rõ ràng ranh giới cuối cùng của Cố Duy Trạch.
Là tôi.
Ban đêm, hai chúng tôi như hai chú chuột hamster rúc chung một chỗ trong chăn.
\"Em thật sự tốt đến thế sao?\" Tôi ranh mãnh hỏi anh.
\"Rất rất rất tốt luôn.\" Anh gật đầu lia lịa: \"Tốt đến mức anh nằm mơ cũng gật gù cười tủm tỉm.\"
Anh nói, dưới cây hoa anh đào không phải là lần đầu tiên anh gặp tôi. Vào một ngày trước đó, có một chú mèo con rơi xuống cống thoát nước. Chú thợ sửa chữa không biết nên đã đậy nắp cống lại, chú mèo con đói bụng kêu lên thảm thiết.
Mọi người xung quanh bối rối không biết làm sao, chỉ có tôi đi ngang qua, một tay nhấc bổng cái nắp cống lên, vớt chú mèo con ướt sũng ra. Dù bị dính bẩn khắp người nhưng cũng chẳng bận tâm. Lúc đó anh đã tưởng rằng mình gặp được thiên thần.
Con nai trong lòng tôi đập liên hồi. Tôi đã nghe qua quá nhiều lời đánh giá: đàn bà hóa nam, nữ hán tử, kẻ dã man... Nhưng kính lọc giữa người với người, chẳng qua là một đôi mắt thiên vị mà thôi.
Dưới ánh trăng sáng vạch ra, đôi mắt mọng nước của anh chớp chớp không rời nhìn tôi, ánh lên sự khao khát nào đó: \"Vợ ơi, hôn hôn...\"
Màn hình bình luận đột nhiên ồn ào lên:
[A a a nữ phụ, cô mà dám đụng vào tiểu thụ, tôi dùng dây cáp sạc thắt cổ cho cô xem!]
[Tác giả nổ sổ hộ khẩu rồi à, OOC cũng phải có giới hạn thôi chứ.]
[Xin thông báo: Vợ chồng nhà người ta thích làm gì thì làm, có 0% quan hệ tới mấy người nhé.]
[Đúng rồi, chúng ta làm thế này trông giống hệt nhân vật phản diện mất não trong sách quá.]
Hiếu thắng trong tôi nổi lên, tôi ôm lấy mặt Cố Duy Trạch đè xuống hôn ngấu nghiến.
Mặc kệ mấy người nói cái gì nhé. Rất tiếc, chị đây hoàn toàn không ngán áp lực.
Cố Duy Trạch nhanh chóng hoàn tất thủ tục nghỉ việc. Công ty mới đã trao cho anh cành cành ô-liu, đãi ngộ còn tốt hơn trước.
', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-3', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3-3/', '0', 'chuong', '', '0'),
('103', '1', '2026-10-12 20:37:19', '2026-10-12 13:37:19', 'Ngày đầu tiên đi làm, Cố Duy Trạch nhắn tin tới, bảo công ty tổ chức tiệc đón mừng anh nên sẽ về nhà muộn một chút. Tôi sảng khoái đồng ý, chú tâm luyện tập ở võ quán không chút xao nhãng.
Màn hình bình luận đã lâu không gặp đột nhiên xuất hiện:
[Ha ha ha, không ngờ tới đúng không, cổ đông lớn của công ty mới vẫn là anh Tần nhà mấy người.]
[Lần này không có nữ phụ, Đại Mãnh Công 1 cuối cùng cũng có thể ngấu nghiến ăn sạch chiếc bánh kem dâu tây thụ bảo này rồi!]
[Đều tại thụ bảo trước đó quá không ngoan, Tần Thế đã hạ lượng thuốc cực mạnh vào trong rượu, bảo đảm ba ngày không xuống nổi giường.]
[Không chỉ thế đâu, anh công tồi còn đặc biệt bật micro trong phòng, để cả công ty đều nghe thấy tiếng thở dốc nũng nịu của thụ bảo.]
[Tôi sẽ thưởng thức từng khung hình một.]
Rắc.
Bọc giáp tập quyền bị tôi vô tình bẻ gãy đôi.
Tôi vừa phóng xe như bay đến hội trường, vừa điên cuồng gọi điện thoại nhưng bên kia chỉ toàn là tiếng máy bận. Đến khi tôi dốc sức tới nơi, chỉ trong chớp mắt đã quật ngã hơn hai mươi gã bảo vệ vai hùm lưng gấu.
Buổi tiệc đã bắt đầu, Cố Duy Trạch không thấy đâu.
Từ micro truyền ra tiếng thở dốc kỳ lạ: \"Đồ lẳng lơ, lần này không chạy thoát được nữa đâu nhỉ. Màn kịch lạt mềm buộc chặt diễn đủ chưa? Xem tôi dạy dỗ em ra sao đây! Ngoan, tự mình cởi quần ra đi.\"
Tôi nương theo thông tin bình luận tìm được căn phòng ẩn giấu, đang tính nhấc chân đạp cửa. Một giọng nói xa lạ cất lên vang vọng khắp hội trường: \"Sếp ơi, sếp đừng có tụt quần tui mà…\"
?
Ai đây?
Phía sau lưng có một đôi tay đột nhiên kéo tôi vào góc khuất.
\"Suỵt, vợ ơi, anh không sao.\" Cố Duy Trạch trông như con cáo nhỏ tinh ranh, đôi mắt màu hạt dẻ lấp lánh: \"Ly rượu đó anh không uống, lén tráo cho anh ta rồi. Thứ thuốc đó có vẻ làm mê muội thần trí, anh ta còn chẳng nhận ra người nữa.\"
Tôi trợn tròn mắt: \"Thế người bên trong kia là ai?\"
Cố Duy Trạch nhún vai: \"Tên bảo vệ mà anh ta gọi tới để phối hợp hạ thuốc anh.\"
Âm thanh bên trong càng lúc càng lớn: \"Eo sao lại to thế này? Hả? Mùi vị hình như cũng thay đổi rồi...\"
Màn hình bình luận thét gào khóc than thảm thiết:
[Nam chính ông bị đục thủy tinh thể hả, cái đ*ch mẹ kia là tên bảo vệ nặng hơn trăm ký của ông đấy.]
[Thôi xong thôi xong, anh công sắp hết sạch sẽ rồi.]
[Khoan đã, Tần Thế ông chổng mông lên làm cái gì đấy?!]
Mọi người trong hội trường nhìn nhau ngơ ngác. Có người nghe ra giọng Tần Thế, lén lút xì xào bàn tán: \"Chủ tịch Tần bình thường vẻ mặt lạnh lùng người lạ chớ gần, không ngờ riêng tư khẩu vị lại nặng thế!\"
\"Người giàu phải chăng ai cũng hiếu kỳ hiếm lạ thế này?\"
...
Tần Thế ở trong phòng hoàn toàn không hề hay biết biến động bên ngoài: \"Đừng giãy giụa nữa, theo tôi, cậu sẽ trở thành người đàn ông hạnh phúc nhất trên đời. Chỉ cần cậu muốn, tôi sẽ cho cậu sở hữu tất cả.\"
Tên bảo vệ mắt sáng rực lên: \"Thật không?\" Nghiến răng một cái: \"Được luôn!\"
Tần Thế đáy mắt đã bị tình dục chiếm trọn, nghe vậy thì mừng rỡ điên cuồng: \"Cuối cùng cũng đợi được em nói đồng ý. Em yên tâm, tôi sẽ hết lòng thương yêu em... Đù mớ cậu làm cái gì đấy!\"
Anh ta bị lật úp cả người lại, nơi yếu ớt nhất hoàn toàn lộ ra ngoài. Sau một hồi giãy giụa điên cuồng: \"Á…\"
Thế giới trở lại yên bình.
Đến khi tôi và Cố Duy Trạch cười muốn đau bụng trở về nhà, bình luận từ lúc nào đột nhiên nghĩ thông suốt:
[Xem Tần Thế truy thê thà xem nhật ký ngọt ngào của nữ phụ và chồng nhỏ còn hơn.]
[Vừa nãy tôi thấy anh rể lén lút giấu một ít thuốc, là muốn dùng với ai đây nhỉ, khó đoán quá đi mất.]
[Yếu ớt nói một câu, đổi góc nhìn mà xem, Tần Thế chẳng phải là kẻ thứ ba xen vào tình cảm vợ chồng nhà người ta sao?]
[Chuẩn đét, các chị em đừng vì đây là đam mỹ mà bỏ qua thuộc tính đạo đức nhé, kẻ thứ ba ai ai cũng có quyền tiêu diệt!]
[Tần Thế mặt dày Tần Thế mặt dày Tần Thế mặt dày.]
[Tôi cũng muốn nói từ lâu rồi, vợ chồng nhỏ song hướng chạy về phía nhau không phải hay hơn cưỡng chế ái gấp vạn lần sao?]
Tôi vươn tay ra, ôm chặt Cố Duy Trạch vào lòng.
\"Vợ ơi?\" Cố Duy Trạch hơi nghiêng đầu.
Tôi hất cằm: \"Nói đi, trong túi giấu cái gì đấy?\"
Khuôn mặt trắng trẻo của anh đột nhiên ửng hồng. Ngập ngừng e ấp hồi lâu, anh mới móc ra một cái lọ nho nhỏ.
\"Vợ ơi~\" Âm điệu cuối nũng nịu, nghe mà lòng tôi ngứa ngáy: \"Thứ thuốc đó anh chừa lại một chút xíu, chúng mình có muốn...\"
Không một chút đắn đo do dự, tôi vác anh lên vai, lao thẳng vào phòng ngủ.
Một lúc sau, bình luận khóc thét:
[Chị và anh rể lại coi chúng tôi là người ngoài rồi!]
[Zhihu, mi đối xử với hội viên Yển Tuyển quý giá thế này đúng không?]
[Bưng bát nhìn quanh lòng bưng bâng phân hoang mang.]
[Cầu xin đấy, bố trí cho miếng ăn đi mà.]
Một năm sau.
Cố Duy Trạch lựa chọn tự mình khởi nghiệp, sự nghiệp dần dần có khởi sắc. Còn tôi nhận được thư mời tham gia giải đấu võ thuật đối kháng tổng hợp XFC mà tôi hằng mong ước. Đây là sự kiện trọng đại mà giới võ thuật mong đợi từ lâu, vì chiếc đai vàng kia, tôi đã chuẩn bị rất lâu.
Trong giai đoạn chia bảng, huấn luyện viên đột nhiên tìm tới tôi: \"Hạ Kình, em rút lui khỏi giải đấu đi. Đối thủ lần này, em đánh không lại đâu.\"
Tôi nhận lấy kết quả chia bảng. Trận đầu tiên, đối thủ của tôi là võ sĩ Karl, người từng ba lần liên tiếp vô địch giải Châu Âu.
Tôi sững người, không phải vì đối phương là võ sĩ vô địch, mà là đ*ch mẹ gã này chẳng phải là đàn ông sao?!
Huấn luyện viên cũng mang khuôn mặt như đớp phải phân: \"Không biết cái gã nhà tài trợ đáng đâm chết băm vằn nào, yêu cầu giải đấu lần này cho phép người chuyển giới tham gia. Tên rùa Karl này, lại đi đăng ký cho mình làm vận động viên nữ. Gã đàn ông râu rậm xồm xàm như thế mà bảo nội tâm gã từ nhỏ vốn là một nàng công chúa.\"
\"Nói như vậy.\" Tôi dò hỏi: \"Gã thậm chí không cần phẫu thuật? Ban tổ chức đã cho phép gã lấy thân phận vận động viên nữ thi đấu rồi sao?!\"
Huấn luyện viên đau đớn gật đầu: \"Nghe nói ban tổ chức ban đầu cũng không đồng ý, nhưng có một nhà tài trợ bí ẩn đã đưa ra điều kiện khiến người ta không thể từ chối.\"
Trong đầu tôi lập tức hiện ra cái tên đã biến mất từ lâu. Màn hình bình luận cũng nhảy ra:
[Thề chết bảo vệ cuộc sống hạnh phúc của chị và anh rể!]
[Mấy người còn nhớ đây là tiểu thuyết đam mỹ không vậy?]
[Tần Thế đúng là yêu quá đà rồi, ông ta đánh cược toàn bộ tài sản ký hợp đồng đối ứng rồi, một khi Karl thua, anh ta sẽ phá sản đấy.]
Phá sản? Tôi ngẩn người một chút, sảng khoái ký tên vào tờ đơn đồng ý.
\"Huấn luyện viên, em tham gia.\"
Cơ hội khiến Tần Thế phá sản đang ở ngay trước mắt, đừng nói đối phương là vua quyền Anh, cho dù là Siêu nhân Ultraman, tôi cũng đánh tuốt.
\"Đúng rồi.\" Tôi dặn dò huấn luyện viên: \"Chuyện này đừng nói cho Cố Duy Trạch biết.\"
Ngày thi đấu, Cố Duy Trạch bị tôi hạ thuốc ngủ, đang ngủ rất ngon lành.
Trong tiếng reo hò dậy sóng như núi lở biển trào, tôi từng bước đi về phía lồng bát giác. Giọng bình luận viên trong sân vận động vang lên chấn động màng nhĩ: \"Vận động viên Hạ Kình đã xuất hiện! Là một vận động viên võ thuật tổng hợp được chú ý hàng đầu ở bảng nữ trong nước, hôm nay cô sẽ nghênh chiến nhà vô địch ba lần giải Châu Âu - Quyền vương Karl! Đối mặt với một đối thủ mạnh mẽ như vậy, hơn nữa giới tính sinh lý lại là nam giới, vận động viên Hạ Kình vẫn lựa chọn dũng cảm đối đầu, bất luận kết quả ra sao, hãy dành tràng pháo tay ủng hộ cho sự dũng cảm của cô ấy vào khoảnh khắc này! Hãy cùng chờ đón kỳ tích xuất hiện.\"
...
Tất cả mọi người đều cho rằng, đây là một trận chiến đã có kết quả không có chút nghi ngờ. Giống như trong cuốn sách này, không một ai tin Tần Thế sẽ thất bại.
Thế nhưng, thua trước một kẻ rác rưởi là chuyện tôi không bao giờ làm.
Bên trong nhà thi đấu tiếng người huyên náo. Tôi và Karl đứng ở hai bên lồng bát giác, tiếng bàn tán trong sân vang lên không ngớt:
\"Chỉ là một trận thi đấu thôi mà, làm sao quan trọng bằng cái mạng chứ?\"
\"Muốn nổi tiếng đến phát điên rồi à? Thích chơi nổi thế?\"
\"Cô gái ơi, chịu thua không mất mặt đâu, còn rừng xanh lo gì không có củi đốt!\"
Karl ném tới ánh mắt coi thường: \"Đừng trách anh trai đây ra tay ác độc, có trách thì trách cô đụng phải người không nên đụng.\"
Tiếng còi của trọng tài vang lên, trận đấu bắt đầu. Karl lao tới như một chiếc xe tăng, một cú đấm nặng ngàn cân xộc thẳng vào mặt tôi.
Đối mặt với đối thủ mạnh mẽ đến thế, tôi không ngông cuồng tới mức nghĩ rằng mình có thể thắng bằng sức mạnh. Linh hoạt xoay xở, chưa chắc đã không có khả năng chiến thắng.
Tôi nghĩ như vậy, cho đến khi cú đấm tiếp theo lao tới, tuy suýt soát né được nhưng phần xương sườn bị gió đấm lướt qua vẫn đau như muốn nổ tung. Mắt tôi tối sầm lại, dựa vào bản năng né tránh những đòn tấn công tiếp theo.
Cho đến khi tới thời gian nghỉ giữa hiệp, trước mắt tôi đã là một mảng đỏ ngầu máu tươi. Màn hình bình luận biến thành vô số tiếng la hét cất lên bên tai:
[Chị ơi cố lên!]
[Sao anh rể lại chạy tới phòng VIP của Tần Thế rồi? Trên tay anh ấy cầm cái gì thế?]
[Đù mớ, anh rể hạ thuốc Tần Thế rồi!]
Tôi nghiêng đầu, nhìn về phía phòng bao VIP. Phía sau bức tường kính, Tần Thế nằm rũ rượi trên ghế sofa. Cố Duy Trạch đã lén tráo thuốc tôi hạ cho anh, đợi khi Tần Thế mò tới, lại hạ thuốc cho anh ta khi anh ta không có phòng bị.
Người đàn ông tự cao tự đại này cuối cùng cũng hoảng loạn: \"Mày muốn làm cái gì?! Xung quanh đây có bao nhiêu con mắt đang nhìn vào đấy, mày dám động vào tao?\"
Cố Duy Trạch thản nhiên bước tới, dùng gậy bóng chày nện mạnh vào xương sườn anh ta!
\"Vết thương mà vợ tôi chịu đựng, tôi muốn anh cũng phải chịu đủ cả.\"
Chưa từng thấy nét mặt tàn nhẫn sắc lẹm này của anh, Tần Thế sợ tới mức vỡ mật đứt gan: \"Mày phát điên rồi à? Tao mà chết mày cũng đừng mong sống yên!\"
Trả lời anh ta là cú vụt tiếp theo xé gió lao tới.
\"Tôi không cản được Hạ Kình nhưng tôi có thể chịu đựng cùng cô ấy. Cô ấy bị thương, tôi chăm sóc cô ấy. Cho dù cô ấy thực sự ngã gục trên đài thi đấu, tôi cũng sẽ giết anh trước, rồi mới tuẫn tiết theo cô ấy.\"
Bình luận vừa truyền hình trực tiếp cho tôi xem, vừa khóc rống lên:
[Tưởng là thụ yếu đuối thuần khiết, hóa ra là chú chó nhỏ bệnh kiều u tối.]
[Anh ấy thật sự là, tôi khóc ngất mất.]
[Tôi mãi mãi giơ cao ngọn cờ cho chị và anh rể!!!]
Tiếng chuông hiệp hai vang lên. Tôi lau sạch dòng máu trên mặt, trong họng như có lửa đốt, trong lồng ngực dung nham cuồn cuộn nhưng đầu óc lại hoàn toàn tỉnh táo, chỉ còn lại khuôn mặt dịu dàng của người đó.
Tôi mỉm cười, một lần nữa bước vào giữa đài đấu.
Trận đấu ngày hôm đó đã được người ta bàn tán xôn xao rất lâu.
Có người cảm thán, người phụ nữ Đông Phương trông có vẻ bình thường kia, làm thế nào mà chống đỡ nổi đòn bão táp này đến đòn bão táp khác của Karl, gánh chịu cơ thể đầm đìa máu tươi, ở hiệp cuối cùng nắm bắt thời cơ, nhân lúc đối thủ đắc ý quên mình mà dùng một cú siết cổ sau hoàn thành màn lội ngược dòng.
Cũng có người kinh ngạc trước người đàn ông bí ẩn bị xe cấp cứu khiêng đi từ phòng VIP. Anh ta vốn dĩ còn giữ một chút ý thức cuối cùng nhưng khi nghe tin Karl bị đánh bại, hai mắt trợn ngược, hoàn toàn sụp đổ, đến cả việc mình bị ai đánh thương cũng chẳng nói rõ ràng được.
Trong phòng bao không có camera, chuyện này trở thành một vụ án treo.
Thứ mọi người ghi nhớ cuối cùng, chỉ có tôi - người đã vượt qua muôn vàn gai góc để đoạt lấy chiếc đai vàng chung cuộc.
Hạ Kình.
Sau khi Tần Thế phá sản, bị kẻ thù quăng vào viện tâm thần. Anh ta sẽ kết thúc cuộc đời làm nam chính của mình ở nơi đó.
Ngay lúc tôi và Cố Duy Trạch tưởng rằng mọi chuyện đã êm đẹp, hệ thống đã tìm đến chúng tôi. Nó nói, chúc mừng chúng tôi đã đạt được thành tựu đảo ngược cốt truyện.
Như một lời thưởng, tôi và Cố Duy Trạch có thể nhận được phần thưởng hậu hĩnh, đồng thời có thể giải trừ các hiệu ứng nam chính đam mỹ và nữ phụ pháo hôi — Sẽ không còn những gã đàn ông thèm khát nhan sắc của anh, cũng sẽ không còn những NPC mất não nhắm vào tôi nữa.
Chúng tôi chính là một đôi vợ chồng bình thường nhưng hạnh phúc, ôm ấp những giấc mơ của riêng mình.
Tôi hỏi câu hỏi luôn vây hãm trong lòng: \"Những dòng bình luận đó rốt cuộc là sao thế?\"
Hệ thống nói, những người bị vùi lấp trong cốt truyện không chỉ có mỗi chúng tôi. Ở rất rất nhiều tiểu thế giới, đều có những người như vậy, có người chịu đủ uất ức trong truyện ngược, có người dốc sức tự bảo vệ mình trong truyện H văn, có người tự lực tự cường trong truyện sủng ngọt.
Những người này sau khi khó khăn phá vỡ cốt truyện, đã lựa chọn dùng thành tựu đạt được để đổi lấy cơ hội giúp những người khác nhìn thấy bình luận. Cơ hội giải cứu vận mệnh nằm ngay trong những dòng bình luận đó.
Tôi và Cố Duy Trạch nhìn nhau mỉm cười: \"Hệ thống, chúng tôi cũng muốn đổi lấy cơ hội nhìn thấy bình luận, nhường lại cho người tiếp theo cần trợ giúp. Nếu được, tốt nhất hãy chia bớt sức mạnh võ thuật của tôi cho cô ấy nữa.\"
Sau khi hệ thống rời đi, màu sắc của các dòng bình luận cũng dần mờ nhạt:
[Chị và anh rể bền lâu hạnh phúc nhé!]
[Chị ơi, những lời không hay em từng nói với chị trước kia, em muốn chân thành xin lỗi, cho dù là nhân vật trên giấy hay người ngoài đời thật, em đều sẽ tôn trọng hết sức!]
[Lần này đi thật rồi nhé~]
Tôi vẫy vẫy tay, như lời từ biệt một giấc mơ.
Ánh mắt của người bên cạnh vẫn lấp lánh như xưa: \"Vợ ơi, hôn môi.\"
Hoàn.', 'Chương 4', '', 'publish', 'open', 'closed', '', 'chuong-4-3', '', '', '2026-10-12 20:52:05', '2026-10-12 13:52:05', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-4-3/', '0', 'chuong', '', '0'),
('104', '1', '2026-10-12 21:19:58', '2026-10-12 14:19:58', 'Sau khi nữ chính xuất hiện, thanh mai trúc mã của tôi vẫn rơi xuống biển mất tích như cũ.
Khi được tìm thấy, anh ta đã mất trí nhớ và yêu người nữ chính đã cứu mình.
Tôi không đành lòng, giấu tất cả mọi người lén lút ép nữ chính rời đi nhưng lại khiến cô ta bị tai nạn xe dẫn đến sảy thai.
Trúc mã tràn ngập oán hận, nói tôi thủ đoạn dơ bẩn, tâm địa độc ác, dù có khôi phục ký ức cũng sẽ không bao giờ thích tôi.
Anh ta thậm chí không từ thủ đoạn trả thù, ép gia đình tôi phá sản.
Anh ta giễu cợt:
“Thiếu mạng đứa con của tôi, thì phải dùng vận mệnh của Hứa thị ra mà trả.”
“Đây là những gì cô xứng đáng nhận được.”
Sau đó phanh xe bị hỏng, cả hai chúng tôi đều bỏ mạng nơi biển sâu.
Tỉnh lại lần nữa, tôi trở về năm thứ ba sau khi trúc mã rơi xuống biển mất tích.
Tôi cuối cùng cũng tìm thấy dấu vết của anh ta, nhưng lại đột nhiên khựng lại.
Trợ lý cầm bức ảnh, cẩn thận hỏi tôi: “Có chuyện gì vậy Hứa tổng, chúng ta có đi tìm nữa không?”
Tôi thu chân lại, nhẹ nhàng lắc đầu: “Không đi nữa.”
', 'KIẾP TRƯỚC TÔI CỨU ANH, KIẾP NÀY TÔI ĐỂ ANH TỰ GÁNH LẤY SỐ PHẬN', '', 'publish', 'open', 'closed', '', 'kiep-truoc-toi-cuu-anh-kiep-nay-toi-de-anh-tu-ganh-lay-so-phan', '', '', '2026-10-16 22:21:05', '2026-10-16 15:21:05', '', '0', 'https://muopdangcungcovingot.io.vn/truyen/kiep-truoc-toi-cuu-anh-kiep-nay-toi-de-anh-tu-ganh-lay-so-phan/', '0', 'truyen', '', '0'),
('105', '1', '2026-10-12 21:21:59', '2026-10-12 14:21:59', '', '51ea6352-021b-4dbe-8ad7-bfac0f71ffec', '', 'inherit', 'open', 'closed', '', '51ea6352-021b-4dbe-8ad7-bfac0f71ffec', '', '', '2026-10-12 21:21:59', '2026-10-12 14:21:59', '', '104', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/51ea6352-021b-4dbe-8ad7-bfac0f71ffec.png', '0', 'attachment', 'image/png', '0'),
('106', '1', '2026-10-12 21:23:23', '2026-10-12 14:23:23', 'Sau khi nữ chính xuất hiện, thanh mai trúc mã của tôi vẫn rơi xuống biển mất tích như cũ.
Khi được tìm thấy, anh ta đã mất trí nhớ và yêu người nữ chính đã cứu mình.
Tôi không đành lòng, giấu tất cả mọi người lén lút ép nữ chính rời đi nhưng lại khiến cô ta bị tai nạn xe dẫn đến sảy thai.
Trúc mã tràn ngập oán hận, nói tôi thủ đoạn dơ bẩn, tâm địa độc ác, dù có khôi phục ký ức cũng sẽ không bao giờ thích tôi.
Anh ta thậm chí không từ thủ đoạn trả thù, ép gia đình tôi phá sản.
Anh ta giễu cợt:
“Thiếu mạng đứa con của tôi, thì phải dùng vận mệnh của Hứa thị ra mà trả.”
“Đây là những gì cô xứng đáng nhận được.”
Sau đó phanh xe bị hỏng, cả hai chúng tôi đều bỏ mạng nơi biển sâu.
Tỉnh lại lần nữa, tôi trở về năm thứ ba sau khi trúc mã rơi xuống biển mất tích.
Tôi cuối cùng cũng tìm thấy dấu vết của anh ta, nhưng lại đột nhiên khựng lại.
Trợ lý cầm bức ảnh, cẩn thận hỏi tôi: “Có chuyện gì vậy Hứa tổng, chúng ta có đi tìm nữa không?”
Tôi thu chân lại, nhẹ nhàng lắc đầu: “Không đi nữa.”
Tôi đã từ bỏ việc tìm kiếm Bùi Việt nhưng theo diễn biến của cốt truyện, một năm sau, anh ta vẫn trở về.
Hội bạn thân trong giới để chúc mừng đã tổ chức cho anh ta một buổi tiệc đón gió vô cùng hoành tráng. Lúc đó tôi vừa đi công tác nước ngoài về, bị bạn bè kéo đến tham dự, còn bí mật nói sẽ cho tôi một bất ngờ.
Rồi khoảnh khắc bước qua cánh cửa, tôi nhìn thấy Bùi Việt đang được mọi người vây quanh ở chính giữa.
Vì mất trí nhớ, toàn thân anh ta tỏa ra sự lạnh lùng và xa cách với những người xung quanh.
Khoảnh khắc bốn mắt nhìn nhau, có người kích động chỉ vào tôi hỏi anh ta: “Bùi Việt, cậu có quên tụi này thì cũng phải nhớ Hứa Thính Lan chứ?”
“Hai người từ nhỏ đã lớn lên bên nhau, còn nói sẽ mãi mãi ở bên nhau cơ mà.”
Tâm điểm trong phòng bao chuyển dời sang người tôi.
Bùi Việt nhìn sang, không nói nhớ, cũng không nói không nhớ, chỉ sau một hồi im lặng ngắn ngủi, anh ta chậm rãi lặp lại: “Hứa Thính Lan.”
Mọi người thấy vậy có lẽ cũng đoán ra kết quả, lần lượt cười lên để hòa hoãn không khí, an ủi tôi: “Thính Lan, đầu Bùi Việt bị thương nên mới quên chúng ta thôi.”
“Nhưng bác sĩ nói sau này vẫn có khả năng khôi phục... Dù thực sự không khôi phục được, thì người bình an trở về cũng là rất may mắn rồi đúng không.”
Những lời này nói thật khéo léo, có lẽ sợ tôi đau lòng. Sau đó như để kéo gần khoảng cách giữa tôi và Bùi Việt, mọi người ồn ào kể cho anh ta nghe về quá khứ của chúng tôi.
“Năm đó cậu rơi xuống biển mất tích, người đau lòng nhất chính là Thính Lan đấy.”
“Cũng chỉ có cô ấy tin chắc cậu còn sống, cứ bốn phương tám hướng đi tìm cậu suốt thôi.”
“Ôi, đúng là thế sự khó lường, nếu không có tai nạn đó, giờ hai người đã kết hôn rồi.”
Họ nói không sai, hôn ước giữa tôi và Bùi Việt đã được định từ nhỏ. Trước khi anh ta rơi xuống biển, chúng tôi cũng đã ấn định ngày cưới. Kết quả không đợi được đám cưới, thứ đợi được lại là việc anh ta âm thầm biến mất giữa biển sâu.
Câu cuối cùng anh ta để lại cho tôi vẫn là: “Hứa Thính Lan, anh rất mong chờ đám cưới của chúng ta.”
Nhiều năm trôi qua, lần nữa nhắc lại chỉ còn là cảm khái.
Tôi nâng ly rượu, cũng đáp lại không khí đoàn tụ sau thời gian dài xa cách mà mọi người tạo ra, mỉm cười nói với Bùi Việt: “Chào mừng anh về nhà.”
Nhưng anh ta nhìn tôi, câu thốt ra lại là: “Tôi trở về không phải để thực hiện hôn ước.”
“Tôi đã có người mình thích rồi.”
Tôi biết.
Một năm trước khi tìm thấy anh ta, tôi đã biết rồi, người anh ta thích là Vu Ương Ương.
Khi đó, sau vô số lần thất vọng, đó là lần duy nhất tôi nhìn thấy một Bùi Việt bằng xương bằng thịt còn sống sót. Nỗi đau đớn kìm nén bấy lâu kết hợp với niềm vui sướng trong phút chốc khiến tôi suýt nữa rơi lệ.
Tôi lao đến nắm lấy cánh tay anh ta, nghẹn ngào gọi một tiếng: “Bùi Việt.”
Nhưng thứ nhận lại là việc anh ta hất tay tôi ra cùng lời chất vấn xa lạ: “Chúng ta quen nhau sao?”
Tôi cố nén xót xa dâng trào trong lòng, kiên nhẫn giải thích với anh ta rằng chúng tôi là thanh mai trúc mã, từ nhỏ đã có hôn ước, ba năm trước anh ta rơi xuống biển trong một buổi tiệc trên du thuyền. Vì sợ anh ta không tin, tôi lấy ra bức ảnh luôn mang theo bên mình, đó là ảnh chụp chung khi anh ta đón sinh nhật cùng tôi.
Tôi cẩn thận khẩn cầu anh ta: “Anh theo em về trước, để bác sĩ kiểm tra cơ thể cho anh, rồi em sẽ từ từ kể cho anh nghe mọi chuyện có được không?”
“Cẳng chân của anh bị thương rồi, cần phải điều trị càng sớm càng tốt.”
So với việc mất trí nhớ, điều tôi bận tâm hơn là cái chân phải hơi khập khiễng vì chấn thương của anh ta.
Nhưng tôi còn chưa nghe được câu trả lời của anh ta thì một cô gái đột nhiên xuất hiện, giọng nói run rẩy hỏi anh ta: “Anh định bỏ rơi em sao?”
Góc váy cô gái bị gió biển thổi tung bay, mái tóc đen tuyền xõa tự do sau lưng, giống như một mỹ nhân ngư bước ra từ lòng biển.
Đây không phải lần đầu tiên tôi đến hòn đảo này, cũng không phải lần đầu tiên tôi gặp cô ta. Ngay từ ngày thứ hai sau khi Bùi Việt rơi xuống biển, tôi đã tìm đến đây, cầm bức ảnh này hỏi thăm từng nhà một. Lúc đó cô ta chắn tôi ở ngoài cửa, liếc nhìn bức ảnh rồi nói: “Chưa từng gặp.”
Sau này tôi mới nhận ra mình bị lừa gạt, ngọn lửa giận dâng lên, không kìm được mà chỉ trích cô ta: “Cô gạt tôi.”
Cô ta nhút nhát lắc đầu, đôi mắt ngấn lệ nhìn về phía Bùi Việt: “Tôi không cố ý đâu, lúc đó anh ta vốn đang bị thương, tôi sợ cô là người xấu muốn hại anh ta thêm lần nữa, xin lỗi...”
Bùi Việt bước tới che chắn cô ta ở phía sau, tiện tay ném bức ảnh xuống trước mặt tôi: “Chỉ là một bức ảnh thôi, tôi dựa vào đâu mà phải tin cô?”
Gió biển rất lớn, tôi không kịp chụp lấy, đành giương mắt nhìn nó bị sóng biển cuốn đi.
Giọng nói dịu dàng an ủi cô ta của Bùi Việt cũng theo gió truyền đến: “Anh sẽ không bỏ rơi em, mãi mãi không bao giờ.”
Cho nên kẻ bị bỏ rơi chỉ có một mình tôi.
', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-4', '', '', '2026-10-12 21:23:23', '2026-10-12 14:23:23', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-1-4/', '0', 'chuong', '', '0'),
('107', '1', '2026-10-12 21:33:04', '2026-10-12 14:33:04', 'Để khuyên anh ta trở về, tôi đành phải ở lại hòn đảo. Mỗi ngày đều tìm cách kể cho anh ta nghe chuyện quá khứ, hy vọng anh ta có thể nhớ lại nhưng Bùi Việt từ đầu đến cuối vẫn thờ ơ.
Thậm chí vì sự bất an của Vu Ương Ương, anh ta càng thêm chán ghét tôi, lạnh giọng cảnh cáo tôi: “Cầm mấy cái câu chuyện cô tự bịa ra đó rồi cút xa ra cho tôi!”
Cho đến nửa tháng sau, tôi lao lên con thuyền đánh cá của anh ta, mất khống chế gào lên chất vấn: “Cho dù anh không quan tâm đến cơ thể của mình, vậy còn ông nội anh thì sao?”
“Anh có biết sau khi hay tin anh rơi xuống biển mất tích, ông vì quá lo lắng mà bệnh tim tái phát không?”
“Bây giờ ông vẫn còn nằm trong phòng cấp cứu ICU, ông vẫn luôn chờ anh về nhà đấy!”
Tay cầm lưới của Bùi Việt khựng lại, ngữ khí có phần nhạt nhẽo: “Chuyện đó thì liên quan gì đến tôi?”
“Tôi chỉ biết, hiện tại tôi chỉ có một người nhà... và là người yêu, đó chính là Ương Ương.”
Đúng lúc có một đợt sóng biển nổi lên, con thuyền nhỏ chao đảo dữ dội. Bùi Việt theo bản năng nắm lấy cổ tay Vu Ương Ương, rồi trố mắt nhìn tôi ngã văng khỏi thuyền, bị sóng biển nuốt chửng.
Nước biển tràn vào, ngũ tạng lục phủ đều đau đớn vô cùng, cộng thêm những ngày qua tâm lực kiệt quệ, tôi đã hôn mê suốt ba ngày.
Lúc tỉnh lại, trợ lý vội vã báo với tôi: “Hứa tổng, Bùi thiếu và... cô gái đó đã đi thuyền rời khỏi đây rồi.”
“Dàn vệ sĩ cô bảo tôi chuẩn bị trước đó đã sẵn sàng, giờ đuổi theo vẫn còn kịp.”
Tôi ngơ ngác nhìn bầu trời xanh thẳm ngoài cửa sổ, nhớ lại cơn ác mộng bị sóng biển cuộn trôi kia.
Do ảnh hưởng từ cha mẹ, từ nhỏ tôi đã biết dùng cả biện pháp mềm mỏng lẫn cứng rắn. Nếu đã không khuyên được Bùi Việt, vậy thì trực tiếp cho vệ sĩ trói anh ta mang về, đó vốn là kế hoạch tôi đã tính sẵn từ trước.
Trong kiếp trước của giấc mơ, tôi quả thực đã làm như vậy. Thậm chí dưới sự phản kháng quyết liệt của Bùi Việt, tôi đã đồng ý đưa cả Vu Ương Ương về cùng.
Đáng tiếc là, anh ta vẫn không kịp gặp mặt ông nội lần cuối.
Bác sĩ nói anh ta mất trí nhớ là do đầu từng chịu va đập mạnh, không thể xác định khi nào mới khôi phục lại ký ức nhưng chân phải của anh ta đã được điều trị, dần dần phục hồi bình thường.
Trong ba năm Bùi Việt mất tích, vị trí người thừa kế Tập đoàn Bùi thị đã rơi vào tay người em trai cùng cha khác mẹ của anh ta. Có thể giành lại được vị trí đó, có lẽ là nhờ tôi vẫn kiên trì giữ vững hôn ước kia, giữ vững vị trí thuộc về anh ta. Cân nhắc lợi hại, ba của anh ta đương nhiên không muốn từ bỏ cơ hội liên minh mạnh mẽ với Hứa thị.
Sau đó tôi đồng hành cùng anh ta làm quen lại với công việc của công ty, ngây thơ nghĩ rằng chúng tôi có thể trở lại như xưa cho đến khi tận mắt nhìn thấy anh ta và Vu Ương Ương ôm hôn nhau.
Tôi buộc phải đối mặt với sự thật mà mình cố tình phớt lờ.
Trên những bức ảnh trợ lý gửi tới, không tấm nào là không phải hình ảnh hai người họ thân mật bên nhau. Cùng nhau ngắm pháo hoa, dạo phố, nắm tay đi dạo...
Tôi như ngược đãi bản thân mà xem hết từng tấm một, sau đó giống như mọi nữ phụ độc ác trong các câu chuyện tình yêu, tôi giấu Bùi Việt, tìm đến Vu Ương Ương, đe dọa cô ta: “Nghe nói ca phẫu thuật bệnh tình của mẹ cô rất thành công, cô cũng không muốn để bà ấy biết con gái mình đang làm tiểu tam đâu nhỉ?”
“Tất nhiên, cô đã cứu Bùi Việt một mạng, chúng tôi rất biết ơn.” Tôi đẩy tấm séc đến trước mặt cô ta, mỉm cười nói: “Ân nhân và tiểu tam, rốt cuộc chọn thân phận nào thì phải xem bản thân cô rồi.”
Vu Ương Ương mặt mày tái nhợt nhìn tôi, đột nhiên cười lên: “Hứa Thính Lan, cô chẳng qua cũng chỉ cậy vào cái hôn ước này mà thôi.”
Đúng, tôi chính là ỷ vào cái hôn ước này đấy.
Tôi và Bùi Việt thanh mai trúc mã, môn đăng hộ đối, từ nhỏ đã nói sẽ mãi mãi bên nhau. Dựa vào đâu mà cô ta có thể đến sau chen ngang? Tôi tin chắc sau khi Bùi Việt khôi phục ký ức sẽ yêu tôi.
Nhưng tôi không ngờ Vu Ương Ương lại mang tấm séc đó đi và gặp tai nạn xe, cô ta bị sảy thai.
Lúc tôi vội vã đến bệnh viện, vừa hay nghe thấy cô ta mắt lệ nhạt nhòa xin lỗi Bùi Việt: “Là em không bảo vệ tốt cho đứa con của chúng ta, Bùi Việt, xin lỗi anh... Em biết anh đã nhớ lại chuyện năm đó chính em là người làm anh bị thương ở đầu dẫn đến mất trí nhớ rồi còn lừa anh...”
“Không tha thứ cho em cũng không sao, đều là lỗi của em, đứa trẻ này coi như là quả báo dành cho em.”
Tôi đứng ngoài cửa, nhất thời không phân biệt được chuyện nào giáng đòn đả kích lớn hơn xuống đầu mình. Là việc Bùi Việt đã sớm khôi phục ký ức hay là việc anh ta và Vu Ương Ương đã có con với nhau. Hoặc là, việc anh ta mất trí nhớ thực chất lại là vì Vu Ương Ương.
Tấm séc ném mạnh vào mặt kéo tư duy tôi trở lại.
Tất cả cảm xúc cuối cùng chỉ hóa thành một tiếng hỏi không lời đáp:  “Bùi Việt?”
Anh ta nhìn sang với ánh mắt tràn ngập hung tợn, ngữ khí giễu cợt chỉ trích tôi: “Cô hài lòng rồi chứ? Vì thứ tình yêu tởm lợm của cô mà đền vào một mạng người!”
“Có phải cô nghĩ rằng tôi khôi phục ký ức rồi thì sẽ yêu cô không? Tôi nói cho cô biết, Hứa Thính Lan, tôi sẽ không bao giờ.”
“Tôi chỉ càng thấy rõ hơn ai hết những thủ đoạn dơ bẩn, tâm địa độc ác của cô mà thôi.”
Một mạng người đã xóa sạch khoảng cách giữa anh ta và Vu Ương Ương, nguyên do mất trí nhớ từ đó về sau không còn truy cứu nữa. Mà tình nghĩa thanh mai trúc mã của chúng tôi cũng hoàn toàn tan vỡ, tôi trở thành kẻ sát nhân hại chết con của anh ta.
Anh ta nói Vu Ương Ương từ nhỏ lớn lên ở hòn đảo, đơn thuần tốt bụng, không giống như tôi lớn lên trong thương trường lừa lọc đấu đá, đầy mưu mô tính toán.
Bùi Việt giễu cợt cười một tiếng: “Cô dựa vào đâu mà nghĩ tôi sẽ thích một loại người như cô?”
Thế nên anh ta liên thủ với đối thủ bày trò gài bẫy Hứa thị, không từ thủ đoạn ép nhà họ Hứa phá sản, gánh khoản nợ khổng lồ. Chỉ vì để hủy bỏ hôn ước nhường đường cho Vu Ương Ương, cũng là để trả thù việc tôi hại chết con của anh ta.
Bùi Việt ôm Vu Ương Ương, dùng dáng vẻ của một kẻ xét xử để chỉ trích tôi: “Mạng người đã thiếu thì luôn phải trả, mạng đứa con của tôi, hãy dùng vận mệnh của Hứa thị ra mà trả.”
“Hứa Thính Lan, đây là những gì cô xứng đáng nhận được.”
Điều này có lẽ giống như kết cục mỹ mãn trong mọi câu chuyện tình yêu, nam nữ chính ân ái hòa thuận, nữ phụ độc ác mất đi tất cả. Cho đến cuối cùng, ngay cả mạng sống cũng mất nốt.
Vì phanh xe bị hỏng, tôi và Bùi Việt cùng bỏ mạng nơi biển sâu.
Mở mắt ra lần nữa, tôi trở về cái ngày mà anh ta và Vu Ương Ương vì muốn thoát khỏi tôi mà đi thuyền bỏ trốn.
Trợ lý bấm vào định vị trên máy tính bảng, thấy tôi đột nhiên khựng lại, cẩn thận hỏi: “Có chuyện gì vậy Hứa tổng, chúng ta có đi tìm nữa không?”
Tôi lắc đầu: “Không đi nữa.”
Năm đó sau khi Bùi Việt rơi xuống biển, đội cứu hộ đã tìm anh ta suốt bảy ngày bảy đêm.
Tất cả mọi người đều tin chắc anh ta đã chết, chỉ có tôi là không từ bỏ, lại đi tìm suốt ba năm trời.
Sau đó tôi vứt bỏ tài liệu, rời khỏi hòn đảo, cho đến tận hôm nay cũng chưa từng nói cho bất kỳ ai biết rằng mình từng tìm thấy anh ta.
Bây giờ gặp lại lần nữa, thực ra cũng không có gì bất ngờ. Dù sao theo tiến trình thời gian, lúc này mẹ của Vu Ương Ương chắc hẳn đã mắc bệnh nặng. Bất luận là nguồn lực y tế hay chi phí phẫu thuật, đều bắt buộc anh ta phải nhớ lại thân phận mà tôi từng kể cho anh ta nghe.
Cho nên Bùi Việt trở về quả thực không phải để thực hiện hôn ước.
Trong phòng bao yên tĩnh vài giây, mọi người nhìn nhau, mới sau đó mới ngộ ra, người anh ta thích là ân nhân cứu mạng Vu Ương Ương.
Sự im lặng ngắn ngủi sau đó lại nhanh chóng khôi phục sự náo nhiệt, có người trêu chọc nói: “Cũng đúng, ơn cứu mạng thì nên lấy thân đền đáp mà.”
“Nói mới thấy cô gái này cũng dũng cảm thật đấy, vớt một gã đàn ông xa lạ về nhà như thế.”
Tôi bình tĩnh nghe mọi người trêu đùa, đột nhiên nhớ lại kiếp trước sau khi nhà tôi phá sản, thực ra Vu Ương Ương từng đến tìm tôi.
Cô ta vẫn mặc chiếc váy trắng dài, mái tóc đen xõa sau lưng, nụ cười rạng rỡ nói với tôi: “Năm đó lúc Bùi Việt dạt vào bờ biển vốn dĩ không hề mất trí nhớ, anh ta còn đến mượn điện thoại của tôi để gọi cho hôn thê của mình.”
“Chỉ là không may, thuyền chở hàng gần đó có hàng hóa rơi xuống, anh ta kéo tôi một cái nên tự mình bị đập trúng, tỉnh lại mới bị mất trí nhớ. Tôi bèn nói với anh ta rằng chính tôi đã cứu anh ta.”
“Ồ đúng rồi, lúc cô cầm bức ảnh đến hỏi tôi, anh ta đang nằm trong nhà tôi đấy.”
Cô ta cười lên, vẫn mang dáng vẻ đơn thuần vô hại.
“Hứa Thính Lan, cô và anh ta là thanh mai trúc mã, lại có hôn ước môn đăng hộ đối, đó là cơ hội ông trời trao cho cô.”
“Vậy thì anh ta bị thương mất trí nhớ, chính là cơ hội ông trời dành cho tôi.”
Mà Bùi Việt, bất luận là trước khi khôi phục ký ức hay sau khi khôi phục ký ức, người anh ta lựa chọn vẫn luôn là Vu Ương Ương.
Giống như lúc này đây, anh ta hơi cau mày bất mãn nói: “Là tôi chủ động muốn ở lại.”
Nghe vậy, người bạn ngẩn ra một chút, mỉm cười giải thích: “Ý cậu chúng tôi đều hiểu, chuyện hôn ước thì cậu không cần lo đâu.”
“Năm đó ai cũng nghĩ cậu không còn nữa, bây giờ cũng đã qua bốn năm rồi.”
Bùi Việt như nhận ra điều gì, vẻ mặt hơi khựng lại, giây tiếp theo, nghe người đó nói: “Hôn ước giữa cậu và Thính Lan đã sớm hủy bỏ rồi.”
Ngay từ khoảnh khắc anh ta bị xác định là đã chết, nó đã bị hủy bỏ, chỉ là bị tôi gượng ép kéo dài thêm ba năm mà thôi. Nhưng khác với kiếp trước, lần này tôi không bắt trói anh ta từ hòn đảo về nữa.
Trong phòng bao vẫn náo nhiệt như cũ.
Tôi nâng ly rượu, đối diện với ánh mắt đột ngột phóng tới của Bùi Việt, mỉm cười nhẹ nhàng, ôn hòa nói với anh ta: “Đúng vậy, hôn ước hủy bỏ rồi.”
“Bùi Việt, anh tự do rồi.”
Vài ngày sau tiệc đón gió, tôi đến Bùi thị bàn chuyện hợp tác.
', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-4', '', '', '2026-10-12 21:33:04', '2026-10-12 14:33:04', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-2-4/', '0', 'chuong', '', '0'),
('108', '1', '2026-10-12 21:34:22', '2026-10-12 14:34:22', 'Vừa bước ra khỏi thang máy đã thấy người em trai cùng cha khác mẹ của anh ta rút lấy xấp tài liệu trong tay anh ta, thong thả nói: “Anh, chân của anh còn chưa lành đâu, hay là đến bệnh viện dưỡng thương đi, việc ở công ty đã có em lo rồi.”
“Nếu ông nội nhìn thấy anh ra nông nỗi này bây giờ, chắc chắn sẽ không dễ chịu đâu, trước khi nhắm mắt ông vẫn còn chờ anh đấy.”
Ngữ khí của cậu ta mang theo sự tiếc nuối nhạt nhòa, lại giấu giếm nụ cười khẽ, từng câu từng chữ nói: “Anh à, anh trở về quá muộn rồi.”
Ánh mắt Bùi Việt rất lạnh, nhếch miệng cười nhạt: “Sao thế? Sợ tôi về cướp mất vị trí của cậu à?”
Em trai anh ta dường như không bận tâm nhún vai, xòe bàn tay ra: “Vị trí ở ngay đây, ai cướp được thì là của người đó.”
Trên thực tế năng lực của hai người quả thực một chín một mười nhưng từ nhỏ đến lớn, Bùi lão gia tử cưng chiều Bùi Việt nhất, lúc ông còn sống, Bùi Việt là người thừa kế không cần bàn cãi.
Nhưng giờ đây ông đã qua đời, Bùi Việt lại mất tích suốt bốn năm. Thời thế đổi thay, mọi thứ đều khó nói trước.
Tôi đứng ngoài cuộc bước qua bên cạnh, ánh mắt lướt qua cái chân phải hơi khập khiễng của Bùi Việt một cách tùy ý.
Kiếp trước sau khi cưỡng ép đưa anh ta về, tôi đã tìm khắp các chuyên gia trong và ngoài nước, bỏ ra rất nhiều tâm sức để chữa lành chân cho anh ta.
Lúc đó bác sĩ nói: “Nếu muộn thêm chút nữa, xương bị hoại tử thì khó chữa lắm.”
Bây giờ anh ta đứng ở đây muộn mất một năm, tôi không biết có tính là quá muộn hay không.
Bàn xong hợp tác lúc đi ra, tôi gặp Bùi Việt ở dưới lầu công ty. Ánh mắt anh ta đăm đăm đặt trên người tôi, rõ ràng là đang đợi tôi.
“Cô đã sớm biết rồi sao?”
Tôi dừng bước, nghiêng đầu liếc nhìn anh ta một cái, vô cùng thản nhiên nở nụ cười: “Đúng vậy, tôi đã sớm biết rồi.”
 “Nhưng tôi từng nói với anh rồi mà, Bùi Việt.”
Một năm trước khi tôi tìm thấy anh ta trên hòn đảo, tôi đã nói rất nhiều lần. Ông nội anh ta nguy kịch, em trai anh ta sẽ cướp đi vị trí vốn thuộc về anh ta, mọi người đều đang chờ anh ta trở về.
Nhưng lúc đó anh ta lại mảy may không bận tâm mà nói với tôi: “Có liên quan gì đến tôi không?”
“Người tôi quan tâm chỉ có một mình Ương Ương mà thôi.”
Năm đó anh ta vì muốn ở bên Vu Ương Ương mà không tin tôi. Bây giờ trở về, cũng chỉ là để cứu mẹ của Vu Ương Ương.
Tôi thấy thật vô lý, không hiểu sao bây giờ anh ta lại dùng ngữ khí trách móc đó để hỏi tôi, đành tốt bụng nhắc nhở anh ta: “Bùi Việt, anh từng nói rồi đấy, anh không quan tâm.”
Trong cuộc đối thị im lặng, anh ta đột nhiên giễu cợt cười một tiếng: “Năm đó trên đảo cô nói tình cảm của chúng ta sâu đậm như thế, bây giờ mới vỏn vẹn một năm ngắn ngủi...”
“Hừ, Hứa Thính Lan, sự yêu thích của cô cũng chỉ đến thế mà thôi.”
Lời vừa dứt, từ cách đó không xa đột nhiên có người gọi một tiếng: “Bùi Việt!”
Là Vu Ương Ương.
Cô ta chạy lon ton đến bên cạnh Bùi Việt, có phần căng thẳng liếc nhìn tôi một cái, sau đó ôm lấy cánh tay Bùi Việt mỉm cười vui vẻ: “Mẹ em tỉnh lại rồi, bà nói rất cảm ơn anh.”
Vẻ mặt Bùi Việt dịu lại, ánh mắt lướt qua tôi, cúi đầu nói với cô ta: “Đây là việc anh nên làm, người nhà của em cũng là người nhà của anh, huống hồ...”
“Năm đó em cũng từng cứu anh.”
Nụ cười của Vu Ương Ương bỗng nhiên khựng lại có phần cứng đờ.
Tôi không có hứng thú xem màn trình diễn tình sâu nghĩa nặng của hai người họ. Trước khi đi chỉ để lại một câu, coi như là lời đáp trả dành cho anh ta: “Thế sự khó lường, lòng người dễ đổi.”
Sự yêu thích của tôi chỉ đến thế mà thôi, vậy thì tôi rất mong chờ sự yêu thích của họ đấy. Mẹ của Vu Ương Ương đã phẫu thuật xong và tỉnh lại, vậy thì không lâu nữa, anh ta cũng sắp khôi phục ký ức rồi.
Về sau mỗi lần gặp Bùi Việt, đều có thể nhìn thấy Vu Ương Ương ở bên cạnh anh ta. Đối mặt với bộ dạng luôn căng thẳng của cô ta, tôi chỉ cảm thấy buồn cười.
Nghe nói chân của Bùi Việt không có bất kỳ chuyển biến tốt nào, sau đó nhà họ Bùi lại tìm đủ loại chuyên gia y tế nhưng đều không đưa ra được phương án điều trị hiệu quả, chỉ đề xuất điều trị bảo tồn. Nếu không có gì bất ngờ, cả đời này đều sẽ như vậy.
Trái lại người em trai của anh ta, dựa vào năng lực xuất sắc đã dần dần thuyết phục được các cổ đông trong công ty, nhận được sự công nhận của tất cả mọi người, từng bước đứng vững chân trong công ty. Thỉnh thoảng trong các buổi tiệc lại nghe thấy tên của Bùi Việt, phần lớn đều là:
“Thằng nhóc nhà họ Bùi đó, đúng là đáng tiếc thật.”
“Năm đó Bùi lão gia tử mỗi khi nhắc đến đứa cháu trai này, trong mắt đều không giấu nổi sự tự hào, không ngờ bây giờ lại thành ra nông nỗi này.”
“Nếu ông ấy còn sống, không biết sẽ đau lòng đến mức nào.”
Mấy lời này nói nhiều rồi, rốt cuộc cũng truyền đến tai Bùi Việt. Nghe nhiều rồi, rồi cũng có lúc để tâm.
Chẳng hạn có một lần một vị trưởng bối trong giới mừng sinh nhật. Người đó có chút thâm giao với ông nội Bùi Việt, khi gặp Bùi Việt ở bữa tiệc, ông ấy âm thầm giấu đi ánh mắt \"tiếc nuối\" trong mắt, vỗ vỗ vai Bùi Việt, mỉm cười nói:
“Trở về là tốt rồi, trở về là tốt rồi.”
“Năm đó người ông nội cậu lo lắng nhất chính là thằng nhóc cậu, giờ cậu về rồi, ông ấy cũng yên tâm rồi.”
Khung cảnh như thế này kiếp trước thực ra cũng từng xuất hiện, cũng là vị trưởng bối này, tán thưởng nói với Bùi Việt:
“Có thể trong thời gian ngắn như vậy đứng vững chân lại ở công ty, không hổ là cháu trai của ông Bùi.”
“Chỉ tiếc là ông ấy không thể tận mắt nhìn thấy.”
Lúc đó Bùi Việt mỉm cười, tuy chưa khôi phục ký ức nhưng điệu bộ thong dong tự tin: “Tuy không kịp gặp mặt ông nội lần cuối, nhưng cũng coi như không phụ sự kỳ vọng của ông rồi.”
Bây giờ đã là tình cảnh hoàn toàn trái ngược.
', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-4', '', '', '2026-10-12 21:34:22', '2026-10-12 14:34:22', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-3-4/', '0', 'chuong', '', '0'),
('109', '1', '2026-10-12 21:35:29', '2026-10-12 14:35:29', 'Sau đó khi tiệc tùng kết thúc, lúc tôi rời đi thì nhìn thấy Bùi Việt im lặng đứng ở ban công. Ánh đèn nhòe ra một quầng sáng dịu nhẹ, anh ta lặng lẽ nhìn đêm tối mênh mang, không biết đang nghĩ gì.
Tất nhiên, những lời bàn tán này không chỉ mình Bùi Việt nghe thấy, Vu Ương Ương cũng nghe thấy.
Cô ta tìm đến tôi là sau khi bữa tiệc này trôi qua không lâu. Lúc đó tôi vừa kết thúc một cuộc họp quan trọng, đi ra thì nghe trợ lý nói có người tìm tôi, còn lấy danh nghĩa của Bùi Việt.
Tôi đẩy cửa phòng khách, nhìn thấy Vu Ương Ương ngồi trên chiếc ghế cạnh cửa sổ, trước mặt đặt một ly cà phê. Ánh nắng chiếu lên mái tóc dài xõa tung của cô ta, vẫn thuần khiết, sạch sẽ như trước, dáng vẻ ngây thơ như không hiểu sự đời.
Tôi bước tới ngồi xuống đối diện cô ta, thong thả nhìn cô ta: “Cô Vu, tôi nghĩ giữa chúng ta chắc là không có gì để nói đâu nhỉ?”
Nếu không phải vì Bùi Việt, tôi và Vu Ương Ương cả đời này cũng sẽ không có bất kỳ mối tương giao nào nhưng bây giờ chúng tôi lại ngồi chung một bàn.
Vu Ương Ương ngẩng đầu nhìn sang, ngữ khí đầy vẻ hiển nhiên: “Tôi đến tìm cô là hy vọng cô đừng gặp Bùi Việt nữa.”
“Nghe nói hôn ước của hai người đã hủy bỏ, nếu đã vậy, gặp lại nhau nữa thì không hợp lý lắm.”
Tay cầm ly cà phê của tôi khựng lại, suýt nữa thì bật cười. Mấy lời tương tự thế này, kiếp trước tôi cũng từng nói với cô ta.
Lúc đó tuy không thích cô ta, nhưng với tư cách là ân nhân cứu mạng của Bùi Việt, tôi đương nhiên cũng tiếp đón cô ta nồng hậu.
Theo yêu cầu của Bùi Việt, sau khi đưa cô ta về, tôi đã đưa ra khoản thù lao đủ để cô ta sống cả đời không phải lo nghĩ nhưng cô ta lại quang minh chính đại nói với tất cả mọi người: “Tôi cứu người chưa từng là để cậy ơn đòi báo đáp.”
“Bất luận ngày hôm đó nhìn thấy là ai, tôi cũng đều sẽ cứu.”
Vì thế cô ta không nhận thù lao, chỉ xin một cơ hội được làm việc tại Bùi thị để mỗi ngày đều có thể gặp Bùi Việt.
Tôi tỉ mỉ đắn đo từ ngữ, nói với cô ta: “Cô Vu ở lại bên cạnh một người đàn ông đã có hôn ước, e là có phần không thích hợp.”
Lúc đó Vu Ương Ương nở nụ cười vô tội: “Hai người có hôn ước mang trên người, tôi lại chỉ làm việc ở đây thôi, có gì mà không thích hợp chứ?”
“Cô Hứa, cô sợ cái gì chứ?”
Sau đó tôi đi tìm Bùi Việt, khéo léo nói với anh ta: “Cô Vu còn trẻ như vậy, để người ta hiểu nhầm đồn đại lời ra tiếng vào dù sao cũng không tốt.”
Anh ta thong thả liếc nhìn sang, giễu cợt mỉm cười: “Cô ấy có ơn cứu mạng tôi, có phải tôi bắt buộc phải đuổi cô ấy đi, không bao giờ gặp lại nữa thì mới vừa lòng cô?”
Hãy nhìn xem, Vu Ương Ương nói tôi cậy vào cái hôn ước môn đăng hộ đối này nhưng bọn họ chẳng phải cũng cậy vào mối quan hệ ơn nghĩa cứu mạng đó sao.
Ly cà phê trong tay dần dần nguội lạnh, tôi mỉm cười hỏi cô ta: “Mọi người đều ở trong cùng một vòng xã hội, đi đi lại lại luôn có lúc chạm mặt, có gì mà không thích hợp?”
Tôi nói đều là sự thật.
Nếu Bùi Việt đã trở về, vậy thì thân phận của anh ta tự nhiên cũng thay đổi. Sau này dù là qua lại trong công việc, hay các buổi tiệc tùng khác nhau, rồi cũng sẽ ngẩng đầu không thấy cúi đầu thấy. Hơn nữa hiện tại cũng không có ai ngăn cản Bùi Việt ở bên cô ta, tôi không biết tại sao cô ta lại đến đây nói mấy lời này.
Phớt lờ ánh mắt bất an của cô ta, tôi thắc mắc: “Cô Vu, cô sợ cái gì chứ?”
Sắc mặt Vu Ương Ương tái nhợt.
Tôi không còn hứng thú tiếp tục thảo luận chủ đề này với cô ta, đứng dậy chuẩn bị rời đi thì đột nhiên nghe thấy cô ta nghiêm giọng chất vấn tôi: “Hứa Thính Lan, cô là cố ý đúng không?”
“Cô cố tình đi gặp Bùi Việt, chẳng qua là muốn anh ta khôi phục ký ức.”
“Có phải cô nghĩ anh ta khôi phục ký ức rồi thì sẽ nhớ ra là mình yêu cô không?”
Bước chân khựng lại.
Tôi bị lời nói của cô ta chọc cười, tiện tay vuốt lại mái tóc ngắn ngang vai, thản nhiên nói với cô ta: “Đúng vậy, không sai, tôi chính là cố ý đấy.”
“Cô…”
Công việc mỗi ngày của tôi rất nhiều, không có thời gian ở đây chấp nhận sự chỉ trích vô lý của cô ta. Trực tiếp xoay người đi ra ngoài, bảo trợ lý tiễn khách nhưng tôi không ngờ trên đường trở về cô ta lại gặp tai nạn xe.
Có lẽ con người khi cực kỳ bất an, luôn khát khao làm điều gì đó để liều mạng giữ lấy cảm giác an toàn.
Ví như kiếp trước tôi giấu tất cả mọi người ép Vu Ương Ương rời đi. Ví như lần này Vu Ương Ương gặp tai nạn xe lại là sau khi vừa gặp tôi xong.
Nghe nói lúc đó Bùi Việt cũng có mặt ở đó, còn kéo Vu Ương Ương một cái, kết quả không kéo ra kịp, cả hai đều bị tông ngã. Máu tươi nhuộm đỏ những chiếc lá rơi. Vu Ương Ương sảy thai, Bùi Việt bị thương không nặng, chỉ có trán bị trầy xước nhẹ.
Lúc tôi nhận được tin tức thì đang ở công ty gấp rút làm dự án, bạn tôi hối hả chạy tới, nhanh chóng kể lại tình hình rồi kéo tôi đến bệnh viện.
Lúc đến phòng bệnh, Bùi Việt đang ngồi trên giường bệnh được mọi người an ủi. Mọi người bàn ra tán vào, có phần than thở.
“Ôi, người không sao là tốt rồi, con cái rồi sẽ lại có thôi.”
“Cũng may là tốc độ xe không nhanh lắm.”
Bùi Việt cau mày nhìn quanh môi trường xung quanh, từ đầu đến cuối không nói một lời, cho đến khi Vu Ương Ương tỉnh lại mắt lệ nhạt nhòa xin lỗi anh ta: “Xin lỗi Bùi Việt, đều tại em không bảo vệ tốt cho đứa con của chúng ta, đều là lỗi của em...”
“Hôm nay em không nên đi gặp cô Hứa...”
Cô ta mặc bộ đồ bệnh nhân, sắc mặt trắng bệch, dáng vẻ như gục ngã đến nơi nhưng Bùi Việt chỉ lạnh lùng nhìn cô ta, từng chữ từng chữ nói: “Đứa con của một kẻ lừa đảo, có cần thiết phải sinh ra không?”
Bùi Việt đã khôi phục ký ức.
Khác với kiếp trước anh ta tính khoản nợ Vu Ương Ương bị tai nạn xe lên đầu tôi, lần này anh ta nhếch miệng cười nhạt, hỏi cô ta: “Cô gặp tai nạn xe, thì có liên quan gì đến việc đi gặp cô ấy?”
Đúng vậy.
Hóa ra anh ta cũng biết Vu Ương Ương gặp tai nạn xe thì liên quan gì đến tôi chứ? Nhưng kiếp trước anh ta cứ nhất quyết phải ném cái tội danh làm chết đứa trẻ lên đầu tôi, luôn phải có một người đứng ra trả giá tương đương cho tai nạn đó.
Kiếp trước là tôi.
Lần này vì trong vụ tai nạn có Bùi Việt, nhà họ Bùi rất nhanh đã tìm được tài xế gây tai nạn nhưng tài xế lại gào toáng lên rằng: “Là do người phụ nữ đó cố tình lao tới mà, tốc độ xe của tôi đã đi rất chậm rồi...”
Bạn tôi thăm dò tin tức xong đến tìm tôi, nói đến đây thì giọng nói nhỏ dần đi.
Cậu ấy quan sát sắc mặt tôi, mới cẩn thận mở lời: “Haiz, thật không ngờ, hóa ra sau khi Bùi Việt rơi xuống biển vốn dĩ không bị mất trí nhớ, chỉ là sau đó tốt bụng cứu cô ta nên mới bị đập trúng.”
“Kết quả cô ta mạo nhận là ân nhân cứu mạng, xoay Bùi Việt như chong chóng, làm hai người bỏ lỡ nhau...”
Tôi nghe ra sự cẩn trọng trong giọng điệu của cậu ấy, có lẽ là vì cậu ấy từng tận mắt nhìn thấy sự đau khổ của tôi. Khoảng thời gian sau khi Bùi Việt rơi xuống biển, tôi thường xuyên giật mình tỉnh giấc khóc thầm giữa đêm khuya, rồi cả đêm cả đêm không sao ngủ được, cả người nhanh chóng gầy rộc đi.
Đi tìm Bùi Việt không mục đích, tự cho rằng anh ta còn sống, chỉ là tôi tự lừa mình lừa người để tìm cho mình một lý do sống tốt mà thôi. Sau này phát hiện anh ta quả thực chưa chết nhưng lại yêu người khác.
Đến nay mới biết, đó là một trò lừa đảo, bạn tôi bất bình giận dữ thốt lên một câu: “Vu Ương Ương đúng là quá đáng đê tiện!”
Tay lật tài liệu của tôi hơi khựng lại, khẽ cười một tiếng: “Đúng là đáng đê tiện thật.”
Nhưng Bùi Việt còn đáng đê tiện hơn.
Kiếp trước sau khi anh ta khôi phục ký ức, người anh ta thích vẫn là Vu Ương Ương.
Lúc bị dồn đến đường cùng không còn lối thoát, tôi không chỉ một lần hỏi anh ta:
“Nếu không phải vì nhà họ Hứa, không phải vì tôi vẫn luôn giữ vững cái hôn ước này, anh nghĩ sau khi trở về còn có vị trí cho anh sao?”
“Còn có đôi chân của anh nữa, là tôi đã tốn bao tâm sức tìm bác sĩ chữa cho.”
“Bùi Việt, năm đó nếu tôi không đi tìm anh, anh còn về được sao?”
Tôi nói đến khản cả giọng nhưng anh ta lạnh lùng nhìn tôi, giễu cợt cười nói: “Cho nên, cô đây là muốn đến cậy ơn đòi đáp đền sao? Hứa Thính Lan.”
“Cái hôn ước mà cô tự cho là giữ vững kia, chỉ trở thành vật cản trở giữa tôi và Ương Ương mà thôi.”
Anh ta nói cho dù không có tôi, anh ta vẫn có thể trở về như cũ, lại lần nữa nắm giữ đại quyền công ty. Sự lừa dối của Vu Ương Ương chẳng hề ảnh hưởng gì, thậm chí sau này sự lừa dối đó còn trở thành kỷ niệm đặc biệt trong tình cảm của họ. Nhưng lúc đó anh ta đã chễm trệ ở vị trí cao, đắc ý vinh hoa, đứng ở vị trí khiến người người ngưỡng mộ, không ai biết nếu tôi không đến đưa anh ta về, thì sẽ có kết quả như thế nào.
Giống như bây giờ bạn tôi cũng không biết, sự lựa chọn của Bùi Việt ở một ngã rẽ khác.
Cậu ấy tức giận bất bình than phiền, nói Vu Ương Ương giống như bị hỏng não vậy, cứ luôn miệng nói: “Rõ ràng không phải như thế này... Không nên thành ra như thế này...”
Cô ta còn khóc lóc thảm thiết van xin Bùi Việt tha thứ, nói với Bùi Việt rằng cô ta thật lòng yêu anh ta.
Bạn tôi tỏ ra không thể hiểu nổi, từng chuyện từng chuyện kể ra: “Đây mà là yêu sao?”
“Cô ta làm Bùi Việt mất đi cơ thể khỏe mạnh, mất đi vị trí người thừa kế, mất đi người ông nội cưng chiều anh ta, còn cả người vị hôn thê thanh mai trúc mã...”
“Bùi Việt sao có thể tha thứ cho cô ta được cơ chứ?”
Tôi gập tài liệu lại, mỉm cười không bày tỏ ý kiến. Quả nhiên con người ta khi lợi ích không bị tổn hại, lúc nào cũng có thể cực kỳ rộng lượng và bao dung, dễ dàng tha thứ cho mọi sai lầm.
Bùi Việt đến tìm tôi là vào ngày thứ hai sau khi anh ta xuất viện.
', 'Chương 4', '', 'publish', 'open', 'closed', '', 'chuong-4-4', '', '', '2026-10-12 21:35:29', '2026-10-12 14:35:29', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-4-4/', '0', 'chuong', '', '0'),
('110', '1', '2026-10-12 21:36:08', '2026-10-12 14:36:08', 'Lúc tôi về nhà, nhìn thấy anh ta đang ngồi trong sân uống trà với mẹ tôi. Mẹ tôi và mẹ anh ta là bạn thân, từ sau khi mẹ anh ta qua đời sớm vì bệnh tật, bà rất quan tâm đến anh ta.
Nói ra thì tôi và Bùi Việt cũng coi như cùng chung cảnh ngộ, ba tôi qua đời trong một tai nạn khi tôi còn nhỏ. Điểm khác biệt là, mẹ tôi không tái giá.
Phụ huynh hai bên không biết chuyện xích mích giữa tôi và Bùi Việt, chỉ nghĩ rằng sự xa cách trước đó là do nhiều năm không gặp, cộng thêm việc anh ta bị mất trí nhớ.
Nhưng bây giờ, anh ta đều đã nhớ lại rồi, mẹ tôi vui mừng nhìn sang tôi, nói: “Thính Lan, tiểu Việt đến tìm con đấy.”
Trong sân trồng một cây lê, đúng vào mùa xuân, hoa lê rơi lả tả xuống, đọng lại trên vai, trên đỉnh đầu Bùi Việt. Rất giống năm đó anh ta dắt tôi lén lút rời khỏi bữa tiệc, chạy vào trong tuyết lớn đắp hai người tuyết thật đẹp.
Dấu chân quấn quít lấy nhau, anh ta bốc một vốc tuyết hất lên không trung, nắm lấy tay tôi hét lớn: “Hứa Thính Lan, chúng ta phải cùng đi, cùng nhau bạc đầu!”
Nhưng sau đó dấu chân bị tuyết mới phủ kín, tuyết phủ đầy đầu rồi cũng từ từ tan chảy.
Tất cả đều biến mất rồi.
Bùi Việt rót một chén trà đẩy đến trước mặt tôi, mỉm cười cực kỳ nhạt nhòa: “Hình như rất rất lâu rồi không cùng cô ngồi lại uống chén trà tử tế.”
Tôi cũng không nhớ rõ lần cuối cùng ngồi bình tĩnh lại với anh ta là khi nào nữa, hình như đã cách cả nửa đời người.
Giữa lông mày Bùi Việt nhuốm vài phần thẫn thờ tiếc nuối, nhắc đến việc năm đó trên hòn đảo đối xử lời tàn tiếng ác với tôi.
“Vẫn luôn nợ cô một lời xin lỗi.”
“Biết thế năm đó mất trí nhớ làm tổn thương cô, thà rằng ngay từ đầu cứ chết luôn ở biển sâu cho xong.”
Mấy lời như vậy lúc tôi tìm thấy anh ta trên hòn đảo, anh ta cũng từng nói với tôi.
“Tôi không phải là Bùi Việt trong miệng cô, càng không phải vị hôn phu của cô.”
“Người cô muốn tìm, biết đâu đã sớm chết ở dưới biển rồi.”
Hoa lê từng bông từng bông rụng xuống, Bùi Việt cười đắng chát:
“Nếu lúc đó tôi đồng ý cùng cô trở về, có phải mọi thứ đã khác rồi không?”
“Cho dù lúc mất trí nhớ tôi có thích Vu Ương Ương, nhưng sớm muộn gì tôi cũng sẽ khôi phục ký ức, nhớ ra người mình yêu là cô.”
Tôi nhẹ nhàng nhấp một ngụm trà, khẳng định chắc chắn:
“Bùi Việt, anh sẽ không đâu.”
Anh sẽ không đồng ý cùng tôi trở về, vì anh không nỡ bỏ Vu Ương Ương. Cũng sẽ không có chuyện sau khi anh khôi phục ký ức lại yêu tôi, bởi vì anh quả thực đã thay lòng đổi dạ rồi.
Bùi Việt không hiểu ý tôi, anh ta hiện lên vài phần thất vọng: “Cô dứt khoát như vậy, năm đó tại sao lại không trực tiếp bắt trói tôi về chứ?”
Tôi nhìn kỹ lông mày và ánh mắt của anh ta, cố gắng tìm kiếm điều gì đó nhưng chẳng có gì cả, cuối cùng thốt ra một câu: “Bởi vì tôi đã ngã xuống biển, hôn mê rất lâu.”
Là năm đó chính mắt anh ta nhìn thấy tôi ngã xuống.
Nước biển đã nhấn chìm toàn bộ tình yêu của tôi dành cho anh ta, thứ trồi lên chỉ còn lại sự hận thù.
Bùi Việt nói tôi thủ đoạn dơ bẩn, tâm địa độc ác. Thực ra không chỉ có vậy, tôi còn giỏi ngụy trang, thích hả hê trên nỗi đau của người khác. Giống như việc hiện tại sở dĩ tôi có thể bình tâm tĩnh khí ngồi ở đây, là vì tôi đã nhìn thấy thảm cảnh hiện tại của anh ta.
So với Bùi Việt hào hoa phong nhã, anh tuấn kiệt xuất, đi đến đâu cũng trở thành tâm điểm của trước kia. Bùi Việt bây giờ, thọt chân, sa sút, hoàn toàn mất đi ý chí chiến đấu, quả thực không còn bất cứ thứ gì đáng để tự hào nữa.
Đôi mắt Bùi Việt ngẩn ngơ, có lẽ đã nhớ lại ngày hôm đó khi sóng biển cuộn dâng, thứ anh ta nắm lấy là tay của Vu Ương Ương.
Tôi liếc nhìn Bùi Việt đang thẫn thờ hồi lâu, đứng dậy chuẩn bị rời đi, nhắc nhở anh ta: “Trà nguội rồi.”
Tôi đã rất lâu rồi không mơ thấy kiếp trước nữa.
Vì Bùi Việt, tôi lại mơ thấy cái ngày mình chết ở kiếp trước.
Thực ra sau khi Hứa thị phá sản, tôi chưa từng từ bỏ. Lúc đó dựa vào một ngọn lửa hận thù dành cho Bùi Việt, tôi nuốt xuống mọi cay đắng định bụng làm lại từ đầu, dù chỉ có một phần vạn hy vọng, tôi cũng phải bò lên để kéo Bùi Việt xuống.
Đáng tiếc là tôi không đợi được đến ngày đó. Lúc đó có một dự án quan trọng gặp trở ngại, tôi tìm đủ mọi cách, cuối cùng xin xỏ đến trước mặt đối tác của Bùi Việt.
Người đó có lẽ là để nịnh bợ Bùi Việt, lại giống như cố tình muốn làm tôi mất mặt. Trên bàn rượu gạt tôi sang một bên rất lâu, rồi bảo tôi làm tài xế đưa Bùi Việt về.
Trên đường Bùi Việt tựa vào ghế ngồi, mang theo vài phần say khướt, giễu cợt hỏi tôi: “Hứa Thính Lan, cô không thực sự nghĩ rằng cô có thể dẫn dắt Hứa thị bò trở lại đấy chứ?”
Xe chạy như bay trên đường bờ biển, tôi nắm chặt vô đằng, ngữ khí bình thản không chút gợn sóng: “Tại sao lại không thể?”
Anh ta không bận tâm nhắm mắt lại, khẽ cười một tiếng: “Vậy tôi chống mắt lên chờ.”
Lời vừa dứt, phía trước khúc ngoặt đột nhiên xuất hiện một chiếc xe tải. Tôi theo bản năng đạp phanh, kết quả phanh xe bị hỏng.Sau đó cả người lẫn xe chúng tôi cùng lao ra khỏi dải phân cách, cùng nhau bỏ mạng nơi biển sâu.
Cảm giác nước biển tràn ngập hơi thở vô cùng đau đớn, tôi mở mắt ra trong bóng tối, nhìn trần nhà thở dốc dồn dập.
Sau đó tôi bận rộn với dự án mới thành lập của công ty, một thời gian dài không gặp lại Bùi Việt nữa. Thỉnh thoảng nghe bạn bè kể lại, Bùi Việt cố gắng thâm nhập vào công ty nhưng nhiều lần gặp trở ngại, những dự án chạm tới được cũng đều là dự án rìa. Sau vài lần như vậy, anh ta trở nên suy sụp chán nản.
Tôi biết em trai Bùi Việt và anh ta vốn không hòa thuận, từ nhỏ đã tranh giành đủ thứ. Biến mất bốn năm, Bùi Việt tự nhiên không còn cơ hội bước chân vào đó nữa.
Vu Ương Ương sau này lại đến quấy rầy Bùi Việt, có một lần trong lúc hai người xô xát không may lăn xuống cầu thang. Chân phải của Bùi Việt chấn thương chồng chất chấn thương, hoàn toàn mất đi khả năng đi lại.
Mẹ tôi kéo tôi cùng đến bệnh viện thăm hỏi.
Lúc Bùi Việt tỉnh lại từ thuốc mê, bà vừa hay đi ra ngoài nghe điện thoại. Tôi ngồi bên cạnh gọt quả cam. Hương thơm lan tỏa trong không khí, giọng nói của Bùi Việt khô khốc khàn khàn, anh ta nói:
“Tôi hình như đã mơ một giấc mơ rất dài rất dài, trong mơ cô ở trên đảo tìm thấy tôi quả thực đã bắt trói tôi về.”
“Nhờ có cô, chân của tôi đã được điều trị, phục hồi bình thường, tôi cũng trở lại công ty, ngồi vào vị trí vốn thuộc về tôi.”
“Sau đó tôi cũng khôi phục ký ức rồi, nhưng mà...”
Có lẽ thấy tôi cứ im lặng không nói gì, Bùi Việt dừng lại, hỏi tôi: “Cô không muốn biết sau đó đã xảy ra chuyện gì sao?”
Tôi cũng không ngẩng đầu lên mà nương theo lời anh ta tiếp tục nói: “Nhưng mà anh vẫn ở bên Vu Ương Ương.”
“Thậm chí còn ép nhà họ Hứa phá sản, gánh khoản nợ khổng lồ.”
Con ngươi Bùi Việt hơi giãn ra:  “Cô…”
Tôi đứng dậy từ trên cao nhìn xuống anh ta, đôi mắt cong cong mỉm cười, không hề giấu giếm lên tiếng: “Đó không phải là mơ, đó là sự thật đấy.”
Rút một tờ giấy ăn, tôi thong thả lau sạch nước cam trên ngón tay, rồi nói tiếp: “Có điều... bây giờ mới là quỹ đạo vốn có.”
“Tôi đã không trói anh về.”
“Đôi chân của anh không được điều trị kịp thời, hỏng rồi, vị trí người thừa kế Bùi thị, anh cũng không thể trở về được nữa.”
“Bùi Việt, đây mới chính là số phận vốn dĩ của anh.”
Trên đường trở về, tôi dựa vào trong xe thút thít mơ màng. Trong mơ hồ hình như lại mơ thấy kiếp trước, phản bội, phá sản, lao xuống biển...
Tôi giật mình tỉnh giấc khỏi cơn mơ, nhìn thấy mẹ tôi đang cúi đầu xem tài liệu, tài xế lái xe vững vàng tiến về phía trước. Nắng xuân ngoài cửa sổ chan hòa, ánh nắng tươi sáng, tất cả đều vừa vặn đúng lúc.
Giống như mọi quỹ đạo chệch hướng cuối cùng rồi cũng sẽ trở về.
Bởi vì số phận tự có sự điều chỉnh.
Hoàn.', 'Chương 5', '', 'publish', 'open', 'closed', '', 'chuong-5-3', '', '', '2026-10-12 21:36:08', '2026-10-12 14:36:08', '', '0', 'https://muopdangcungcovingot.io.vn/chuong/chuong-5-3/', '0', 'chuong', '', '0'),
('111', '1', '2026-10-13 12:50:18', '2026-10-13 05:50:18', 'Tôi là nữ chính của một cuốn truyện đoàn sủng, từ nhỏ đã nhận hết mọi sự yêu thương chiều chuộng.
Người bố vốn nghiêm nghị không mấy khi cười lại cưng chiều gọi tôi là công chúa nhỏ. Cho dù là lúc họp hành ở công ty, ông ta cũng cười hỉ hả để tôi cưỡi trên cổ.
Người ngoài luôn bảo chiều như thế sẽ làm hỏng tôi nhưng bố tôi chỉ cười nhạt một tiếng: \"Chiều hỏng thì có tôi gánh, hơn nữa, con gái chẳng phải là để cưng chiều sao?\"
\"Giai Giai nhà chúng tôi, chỉ cần sống một đời thật xinh đẹp là được.\"
Trái lại là anh trai tôi, một ngày phải học mười hai tiếng, giờ nghỉ còn phải học cưỡi ngựa, đấu kiếm. Chỉ cần có bất kỳ môn nào không xuất sắc, sẽ chuốc lấy một trận mắng mỏ vuốt mặt không kịp.
Anh ta vừa tốt nghiệp đại học lập tức bị phái đến công ty nhà mình làm thực tập sinh, vất vả đi làm. Tôi thì lại thoải mái nằm trên giường ngủ nướng, lên kế hoạch cho chuyến du lịch tiếp theo đi đâu.
Cho đến ngày sinh nhật năm đó, tôi ước một điều: Muốn làm đứa con mà bố yêu thương nhất suốt đời.
Ngày hôm sau, bố tôi đột nhiên đẩy xô cửa phòng tôi, mặt đầy tức giận: \"Một học kỳ trượt hai môn, thành tích của con kém thế này thì sao mà thừa kế công ty!\"
\"Dậy mau, bố sẽ quyên tiền xây tòa nhà để tống con đi du học nước ngoài!\"
', 'CẢ NHÀ CƯNG CHIỀU TÔI NHƯ CÔNG CHÚA, KHÔNG NGỜ TÔI CHỈ LÀ MỘT CON RỐI', '', 'publish', 'open', 'closed', '', 'ca-nha-cung-chieu-toi-nhu-cong-chua-khong-ngo-toi-chi-la-mot-con-roi', '', '', '2026-10-17 11:41:41', '2026-10-17 04:41:41', '', '0', 'https://muopdangcungcovingot.io.vn/truyen/ca-nha-cung-chieu-toi-nhu-cong-chua-khong-ngo-toi-chi-la-mot-con-roi/', '0', 'truyen', '', '0'),
('112', '1', '2026-10-13 12:50:18', '2026-10-13 05:50:18', '', 'b8c752fc-8871-444f-8fd1-15250aa2c927', '', 'inherit', 'open', 'closed', '', 'b8c752fc-8871-444f-8fd1-15250aa2c927', '', '', '2026-10-13 12:50:18', '2026-10-13 05:50:18', '', '111', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/b8c752fc-8871-444f-8fd1-15250aa2c927.png', '0', 'attachment', 'image/png', '0'),
('125', '1', '2026-10-13 16:17:32', '2026-10-13 09:17:32', 'Tôi là nữ chính của một cuốn truyện đoàn sủng, từ nhỏ đã nhận hết mọi sự yêu thương chiều chuộng.
Người bố vốn nghiêm nghị không mấy khi cười lại cưng chiều gọi tôi là công chúa nhỏ. Cho dù là lúc họp hành ở công ty, ông ta cũng cười hỉ hả để tôi cưỡi trên cổ.
Người ngoài luôn bảo chiều như thế sẽ làm hỏng tôi nhưng bố tôi chỉ cười nhạt một tiếng: \"Chiều hỏng thì có tôi gánh, hơn nữa, con gái chẳng phải là để cưng chiều sao?\"
\"Giai Giai nhà chúng tôi, chỉ cần sống một đời thật xinh đẹp là được.\"
Trái lại là anh trai tôi, một ngày phải học mười hai tiếng, giờ nghỉ còn phải học cưỡi ngựa, đấu kiếm. Chỉ cần có bất kỳ môn nào không xuất sắc, sẽ chuốc lấy một trận mắng mỏ vuốt mặt không kịp.
Anh ta vừa tốt nghiệp đại học lập tức bị phái đến công ty nhà mình làm thực tập sinh, vất vả đi làm. Tôi thì lại thoải mái nằm trên giường ngủ nướng, lên kế hoạch cho chuyến du lịch tiếp theo đi đâu.
Cho đến ngày sinh nhật năm đó, tôi ước một điều: Muốn làm đứa con mà bố yêu thương nhất suốt đời.
Ngày hôm sau, bố tôi đột nhiên đẩy xô cửa phòng tôi, mặt đầy tức giận: \"Một học kỳ trượt hai môn, thành tích của con kém thế này thì sao mà thừa kế công ty!\"
\"Dậy mau, bố sẽ quyên tiền xây tòa nhà để tống con đi du học nước ngoài!\"
…
Tôi ngẩn người.
Từ nhỏ đến lớn, tôi chưa bao giờ thấy bố nghiêm khắc, gay gắt như thế, giọng tôi không khỏi run rẩy: \"Bố, con là Giai Giai mà.\"
\"Có phải bố nhầm con với anh trai không? Bố chưa bao giờ hung dữ với con như thế...\"
\"Hơn nữa ban đầu chẳng phải bố đã nói sao, dù có trượt môn hay không, chỉ cần con vui vẻ là được.\"
Từ khi còn nhỏ, bố đã cực kỳ nuông chiều, thả lỏng tôi. Dù là cấp hai trang điểm, hay cấp ba thi toán được điểm hàng đơn vị, ông ta cũng chỉ cười mỉm xoa đầu tôi, bảo: \"Không sao đâu.\"
Chính vì vậy, điểm thi đại học của tồi tệ vô cùng, chỉ miễn cưỡng đỗ vào một trường dân lập hạng ba. Học phí đắt đỏ không nói, triển vọng việc làm lại cực kỳ kém nhưng bố nói, trong nhà đâu cần dựa vào tôi kiếm tiền.
Cuộc đời của tôi, cứ thế nào thoải mái thì sống thế ấy.
Bố mệt mỏi dụi dụi hốc mắt, giọng trầm xuống: \"Trước kia là bố hồ đồ, giờ không thể như thế được nữa.\"
\"Giai Giai, bố không nên để con học cái trường rách này. Thế này đi, bố tìm người kèm IELTS cho con, chúng ta ra nước ngoài du học trường top!\"
\"Hai ngày này đi làm thủ tục thôi học ngay!\"
Tôi càng hoang mang hơn, cả người đơ ra tại chỗ, trong miệng chỉ thốt ra được những âm thanh ớ á.
Mới trải qua một đêm, sao bố lại hoàn toàn biến thành người khác thế này?
Chẳng lẽ bị xuyên không rồi sao?
Nghĩ đến đây, tôi đang định mở miệng thăm dò thì trước mắt đột nhiên lướt qua từng dòng chữ.
[Lão già uống nhầm thuốc à? Sao tự nhiên tốt thế?]
[Theo tôi thấy nữ chính cũng ngu, rõ ràng điều ước đó có thể thành hiện thực, chỉ có một cơ hội duy nhất mà lại đi ước cái gì mà ngày càng xinh đẹp, cạn lời.]
[Lại bắt đầu chỉ trích nữ chính rồi? Nữ chính làm sao mà biết được, khoan dung với con gái một chút không được à?]
Trong khoảnh khắc, da gà nổi hết khắp sống lưng tôi, thậm chí tôi còn run rẩy không chủ động dừng lại được.
Điều ước ngày hôm qua tôi ước...
Hôm qua, anh trai đặc biệt từ nước ngoài bay về, cùng bố đón sinh nhật cho tôi. Trên bánh sinh nhật cắm những cây nến tinh xảo, họ cười đầy cưng chiều, giục tôi mau ước đi.
Một lúc sau, anh trai cười hỏi: \"Giai Giai nhỏ, em ước điều gì thế?\"
\"Hi vọng em sẽ xinh đẹp suốt đời!\"
Thực ra, tôi đã gạt anh ta. Điều ước thực sự tôi ước là làm đứa con mà bố yêu thương nhất suốt đời nhưng bố từ nhỏ đã thiên vị tôi hơn, tôi sợ nói câu này ra sẽ làm anh trai đau lòng.
Nghĩ đến đây, ngực tôi thắt lại từng hồi.
[ĐM, dù sao đi nữa, lão già rốt cuộc cũng có chút lương tâm. Có người giàu nào lại để con mình học trường hạng ba chứ?]
[Lâm Yến Chu mới là cục cưng của lão già, tình yêu thì cho con gái, tiền bạc với tài sản thì cho con trai, nữ chính còn cảm động đến điên đảo.]
[Đúng vậy, sau này lão già phá sản còn bắt nữ chính gánh nợ, nam chính gánh nợ thay cho nữ chính.]
Họ mỗi người một câu, nhanh chóng nói rõ ràng mười mươi.
Tôi là nữ chính đoàn sủng trong một cuốn truyện ngọt sủng. Số rất tốt, nửa đời đầu được bố và anh trai chiều, sau khi bố phá sản lại được chồng chiều, làm phu nhân nhà giàu cả đời, chỉ biết làm sao để tiêu tiền.
Tôi thích đọc tiểu thuyết, thực ra cũng từng đọc nhiều loại \"truyện dòng bình luận\" thế này. Trong số những bình luận đó, hoặc là vô cùng nông cạn, hoặc là mê trai đến cực điểm, sống chỉ để độc ác khắc nghiệt, cố ý dẫn dắt nhân vật chính đi vào đường chết.
Thế nhưng những dòng bình luận trước mắt này, dường như lại không phải như vậy...
[Thực ra, một cuộc đời như thế, chẳng phải cũng rất tốt sao?]
[Tốt cái đéo gì, đây chẳng phải là một con thú cưng đắt tiền sao?]
[Chẳng cần học cái gì, chẳng cần làm cái gì, chẳng biết cái quái gì, mỗi ngày chỉ nghĩ xem làm sao để đẹp đẹp đẹp. Mọi người xung quanh cũng khuyến khích cô ta sống một đời vô tư như thế, cái này khác quái gì nuôi chó?]
[Lâm Yến Chu, lão già, nam chính - ba người này có ai coi nữ chính là một con người thực sự không? Có ai coi cô ấy là đồng loại của mình không?]
[Hừm, nữ chính cũng là con vợ kiều ngốc nghếch.]
Dòng bình luận duy nhất mắng tôi cũng nhanh chóng bị tiếng phản bác đè bẹp.
[Có thể đừng có chỉ trích phụ nữ được không? Nữ chính từ nhỏ tiếp nhận nền giáo dục như thế, cô ấy có quyền lựa chọn không? Cô ấy tự chọn được sao? Cô ấy có con đường thứ hai để đi không?]
[Đạn bọc đường đáng ghét.]
... Đạn bọc đường?
Tôi hít sâu một hơi, cố gắng tự nói với mình rằng không nghiêm trọng đến thế đâu. Họ là những người thân thiết nhất của tôi, chắc chắn là muốn tốt cho tôi... Tôi là con gái, học hành kém một chút cũng quá là bình thường...
\"Giai Giai.\" Giọng của bố đột nhiên kéo suy nghĩ của tôi trở lại.
\"Đơ ra đó làm gì?\" Ông ta nói: \"Mấy ngày nữa đi ăn cơm với bố một bữa, làm quen vài chú vài cô. Đều là người trong giới kinh doanh...\"
\"Bố.\" Tôi đột nhiên ngắt lời ông ta, giọng nói khô khốc vô cùng: \"Con đột nhiên quên mất, sinh nhật năm năm tuổi, bố tặng con món quà gì ấy nhỉ?\"
\"Một đôi giầy sandal cao gót nhỏ.\" Bố suy nghĩ một chút, cười nói: \"Lúc đó con thấy mẹ đi giày cao gót, gào khóc đòi đi bằng được.\"
Trái tim tôi chìm dần xuống, không biết nên cười hay nên khóc. Ông ta không bị xuyên không, đây không phải ai khác mà chính là bố tôi.
Tôi hít sâu một hơi, đang định nói gì đó…
Dưới nhà, cánh cửa lớn bị ai đó đẩy ra. Lâm Yến Chu tươi cười đi vào, lắc lắc chiếc túi to cầm trong tay: \"Giai Giai, mau xem có thích không này?\"
Đó là một chiếc váy dài tinh xảo, lưng xẻ sâu tận đốt sống lưng, ngực thiết kế xẻ V sâu, gần như để lộ nửa bầu ngực tròn trịa. Vòng eo siết rất chặt, tôi cao một mét bảy nặng năm mươi cân, tuyệt đối không liên quan gì đến từ \"béo\" cả.
Thế nhưng giây đầu tiên mặc nó vào, tôi vẫn bị siết đến mức phải hít một hơi sâu.
Tôi nhìn bản thân gần như bán trong suốt trong gương và người anh trai vest gấm giày da bên cạnh, lần đầu tiên cảm nhận được sự khó chịu như có hàng ngàn con kiến bò khắp người.
Rõ ràng trước đây chưa từng như thế... Tại sao lại như vậy?
Thế nhưng Lâm Yến Chu lại cười bảo: \"Đẹp lắm.\"
\"Giai Giai, chiếc váy này rất hợp với em.\" Anh ta nói: \"Lúc đi dạo phố, anh vừa nhìn đã ưng ngay, trong đầu liền hiện ra dáng vẻ em mặc nó.\"
\"Rất đẹp, anh rất thích.\"
\"Tối nay có tiệc.\" Lâm Yến Chu đeo sợi dây chuyền ngọc trai cho tôi: \"Đi cùng anh trai nhé.\"
[Đồ tiện nhân, thích sao không tự mặc đi.]
[Anh trai ruột đàng hoàng nào lại đi mua loại quần áo này cho em gái, tôi thật sự muốn ói.]
[Có phải anh ruột đâu, là con riêng lão già mang về, tác giả cái trend gì cũng muốn đu, cuốn này còn có xu hướng anh em giả.]
[Anh em giả thì anh em giả, không thể tôn trọng nữ chính chút được à?]
Tôi ngẩn người, toàn thân như có dòng điện chạy qua, một cơn lạnh gáy xông lên.
Tôi đã sớm biết Lâm Yến Chu không phải anh ruột mình nhưng chúng tôi sống chung mười mấy năm, không phải anh em ruột còn hơn cả anh em ruột. Hồi nhỏ tôi bị bạn học ăn hiếp, là anh ta dẫn bố xông đến trường bảo vệ tôi.
Mẹ mất sớm, tôi khóc suốt đêm không ngủ được, cũng là Lâm Yến Chu kể chuyện dỗ tôi ngủ.
Người anh trai như thế, sao lại có thứ tâm tư đó với tôi? Sao ông trời có thể để anh ta có tâm tư đó với tôi?
\"Giai Giai.\" Tôi bừng tỉnh, né tránh bàn tay đang muốn xoa đầu mình của Lâm Yến Chu như một phản xạ điều kiện.
Đụng phải ánh mắt hơi kinh ngạc của anh ta, tôi lắc đầu: \"Em không mặc.\"
\"Em cũng không muốn đi.\"
Lâm Yến Chu hơi nhíu mày, hình như muốn nói gì đó nhưng giây tiếp theo, tiếng của anh ta đã bị một tiếng gầm gừ đánh bật.
\"Lâm Yến Chu!\" Bố sải bước đi tới, phủ chiếc chăn đắp lên người tôi, mắng xối xả vào mặt anh ta: \"Mày bị khùng à! Mua cái thứ gì thế này!\"
\"Trên dưới toàn thân có đúng hai mảnh vải rách, còn ra thể thống gì nữa!\"
\"Sau này còn để tao thấy mày mua mấy đồ này cho em gái mày, tao đánh chết mày!\"
\"Bố.\" Lâm Yến Chu ngơ ngác: \"Chẳng phải bố bảo con lúc về mang ít quần áo trang sức cho em sao...\"
\"Lúc đó con còn chụp ảnh cho bố xem, bố bảo được mà...\"
\"Những lời tao nói trước kia, tất cả đều không tính!\"
Bố chém đinh chặt sắt: \"Sau này đừng có mua mấy thứ linh tinh này, em gái mày là người thừa kế của nhà họ Lâm đấy!\"
Lời vừa thốt ra, không chỉ Lâm Yến Chu mà cả bình luận cũng kinh ngạc.
[Dù ông là ai, xin hãy bám chặt lấy xác lão già đó đừng có bước ra.]
[Xin vía bố tôi cũng tính tình đại biến để lại toàn bộ gia sản cho tôi!]
[Vẻ mặt của thằng con kia buồn cười quá, Lâm Yến Chu out!]
\"Bố.\" Lâm Yến Chu khó khăn mở miệng: \"Chẳng phải bố bảo, công ty là của con...\"
\"Tao đã nói rồi, những gì tao từng nói trước kia hủy bỏ hết.\"
\"Nhưng Giai Giai em ấy chẳng biết cái gì cả, sao có thể quản lý tốt công ty?\"
\"Đó không phải việc mày cần bận tâm.\" Bố nói: \"Tao sẽ gửi nó ra nước ngoài học MBA, cũng sẽ thuê người quản lý chuyên nghiệp điều hành công ty.\"
\"Tất nhiên, bố cũng không phải không yêu mày, mày có thể đến công ty chi nhánh làm quản lý mà! Người có năng lực thì làm nhiều việc.\"
Sắc mặt Lâm Yến Chu trong khoảnh khắc cực kỳ xấu xí. Công ty chi nhánh vừa mới bắt đầu, còn đang trong giai đoạn thua lỗ, thậm chí bố còn chẳng thèm bảo cho anh ta cổ phần, chỉ bảo cho anh ta làm quản lý, rất có thể Lâm Yến Chu bận rộn một hồi, cuối cùng lại đi làm culi cho kẻ khác.
[Có cái công ty chi nhánh thì tự lén mà mừng đi, lúc nữ chính đi lấy chồng ngoài nợ nần ra chẳng có cái đéo gì.]
[Ủng hộ người quản lý chuyên nghiệp, thằng con bớt múa may đi, năng lực nát bét, một tay làm sập cả công ty.]
\"Còn nữa, sau này không được gọi em gái là Giai Giai nữa.\" Bố gằn từng chữ: \"Em gái mày với tư cách là con gái của Lâm Chúng Thanh tao, thân phận cao quý, hai ngày nữa tao sẽ dắt nó đi đổi tên, gọi là Lâm Diệu Tổ!\"
[Tập ủng hộ lão già nhất từ trước đến nay!]
[Diệu Tổ là đích tử, lại là đứa con đầu lòng sau khi kết hôn của người mẹ, xưng là quý tử, cực kỳ tôn quý!]
[Thần thiếp nguyện theo phe Lão Già Nương Nương, tuyệt không có tâm đoạt đích gây loạn!]
Vẻ mặt Lâm Yến Chu như thể vừa thấy quỷ, khó khăn lắm mới nhẫn nại đợi được Lâm Chúng Thanh đi khỏi. Anh ta hít sâu một hơi, trầm giọng nói: \"Giai Giai, mấy lời này em đừng để trong lòng.\"
\"Bố già rồi nên hồ đồ, nghĩ ra cái gì làm cái đó, hôm nào anh dẫn bố đi bệnh viện khám xem sao...\"
Trước mặt tôi, anh ta lại biến trở về người anh trai ân cần, ung dung thong thả: \"Nghe lời anh trai nhé, ừm? Em chưa từng học qua các khóa học tương tự, đại học em học lý luận nghệ thuật mà? Chốn thương trường ăn thịt người không nhả xương đâu...\"
Tôi nghĩ, có lẽ tôi nên đồng ý với anh ta. Ít nhất không cần nỗ lực, có thể làm một con ăn bám ăn uống chơi bời cả đời.
Thế nhưng... Không biết sự xốc nổi từ đâu tới khiến tôi lắc đầu.
\"Anh.\" Tôi nói: \"Nếu em cứ không nghe thì sao?\"
Lần đầu tiên trong đời, Lâm Yến Chu cứng đơ quay người rời đi. Tôi nhìn bóng lưng anh ta, trong lòng trào dâng một nỗi chua xót nhè nhẹ.
', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-5', '', '', '2026-10-13 16:17:32', '2026-10-13 09:17:32', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-1-5.html', '0', 'chuong', '', '0'),
('126', '1', '2026-10-13 16:20:16', '2026-10-13 09:20:16', 'Tốc độ làm việc của bố rất nhanh, ngay ngày hôm sau đã dẫn tôi đi làm thủ tục thôi học. Lại qua mấy ngày, gia sư IELTS ông ta tìm đã đến tận nhà, đó là một thanh niên điển trai, mỗi cử chỉ điệu bộ đều mang khí chất thư sinh, ôn hòa nhưng không hề cổ hủ.
\"Cô Lâm.\" Chàng trai cười mắt cong cong: \"Có thể cô không biết, ông Lâm thực ra đã tìm rất nhiều gia sư.\"
\"Ông ấy phỏng vấn từng người một, sàng lọc qua nhiều vòng, tôi là người cuối cùng được giữ lại.\"
\"Ông Lâm còn cố ý kiểm tra khả năng nói và phát âm của tôi, ông ấy bảo khả năng nói của cô yếu, sợ sau này ra nước ngoài giao tiếp với người ta sẽ bị thiệt thòi.\"
Gia sư hình như có chút cảm thán: \"Ông ấy thực sự rất tốt với cô, nói thật, tôi hiếm khi thấy người bố nào tận tâm với con gái như vậy, đặc biệt là cô còn có một...\"
\"Có một cái gì?\"
Thanh niên ôn hòa cười một tiếng: \"Xin lỗi, tôi lỡ lời rồi.\"
Dù anh ấy không nói ra, tôi cũng biết anh ấy muốn nói gì.
Tiếng Anh của tôi xưa nay rất kém, nói lại càng nát bét, thế nhưng Lâm Chúng Thanh lại từng bảo học tiếng Anh vừa khó vừa mệt, thứ phải học thuộc lòng nhiều như thế, con là con gái, sinh hoạt hàng ngày hoàn toàn không dùng đến tiếng Anh, học làm cái gì? Ông ta chỉ hi vọng tôi thư thái vui vẻ.
Nhưng tiếng Anh của Lâm Yến Chu ban đầu cũng chẳng tốt, bố là một người trọng tình trọng nghĩa, cho dù vợ cũ ngoại tình là bên có lỗi, lúc ly hôn ông ta vẫn quyết định ra đi tay trắng, chỉ mang theo con trai. Ban đầu, họ sống rất túng thiếu, điểm thi viết của Lâm Yến Chu rất ưu tú nhưng nói cũng cực kỳ tồi tệ, là kiểu điển hình chỉ biết nghe không biết nói.
Sau khi kết hôn với mẹ, Lâm Chúng Thanh bỏ ra số tiền lớn, thậm chí mời cả người nước ngoài kèm 1-1, cuối cùng luyện cho Lâm Yến Chu một giọng Anh-Anh trôi chảy lịch lãm.
Tại sao? Tại sao sau khi điều ước thành hiện thực, tôi lại có được sự đối đãi như Lâm Yến Chu ngày trước?
Đáp án đã quá rõ ràng.
Bình luận nói đúng, người Lâm Chúng Thanh yêu, hóa ra chưa từng là tôi.
Bình luận một nửa kinh ngạc Lâm Chúng Thanh sao lại tốt thế, một nửa lại bảo...
[ĐM, đây không phải là nam phụ sao?]
[Không ngờ nam phụ xuất hiện sớm như thế?]
[Nam phụ bản tính khá ổn đấy, tuy nhà nghèo nhưng rất biết ơn trả nghĩa, nữ chính giúp đỡ anh ta, anh ta liền yêu nữ chính sống dở chết dở, sau này phất lên luôn muốn báo đáp nữ chính.]
[Nhưng nữ chính không thích anh ta, nên chỉ đành lỡ duyên.]
[Lỡ duyên hay không chẳng phải chỉ là một câu nói của tác giả sao? Thích đu trend sao không đu trend NP luôn đi, còn bảo cái gì mà nam chính là của nữ chính, nam phụ là của độc giả.]
Trái tim tôi khẽ xao động.
Kỷ Trình trước mắt thanh bần, mẹ bệnh nặng, hơn nữa lại vô cùng trọng tình nghĩa. Theo lời bình luận, năng lực của anh ấy đến một trăm Lâm Yến Chu cộng lại cũng không bằng.
Thực ra, tôi không nhất thiết phải trở thành người yêu của anh ấy, đúng không? Tôi có thể trở thành... Nhà đầu tư thiên thần của anh ấy.
Mấy ngày tiếp theo, tôi thăm dò hỏi han, rốt cuộc cũng hỏi ra mẹ của Kỷ Trình nằm viện ở đâu.
Bà bị suy thận giai đoạn cuối, cần gấp ghép thận nhưng họ không những không có tiền thực hiện xét nghiệm tương thích, mà ngay cả viện phí cũng sắp không đóng nổi… tại hệ thống bệnh viện đang nợ mấy chục nghìn tệ, gây quỹ từ thiện, vay mượn họ hàng bạn bè, cái gì cũng thử rồi nhưng vẫn không đủ.
Thậm chí Kỷ Trình còn nghĩ đến chuyện đi vay tín dụng nhưng anh ấy chỉ là một sinh viên chưa tốt nghiệp đại học, hoàn toàn không vay được tiền.
Có người thân cũng khuyên anh ấy, bỏ đi. Bây giờ chạy thận đã vắt kiệt tất cả rồi, cho dù đợi được nguồn thận, họ cũng chẳng trả nổi tiền phẫu thuật ghép thận, càng không cần nói đến chống thải ghép sau phẫu thuật và nhu cầu uống thuốc cả đời sau này.
Mẹ Kỷ Trình đã không thể đợi thêm nữa. Nếu còn không ghép thận, bà sẽ không sống qua nổi năm nay.
Bước ra khỏi phòng bệnh, khi tôi đưa thẻ ngân hàng cho Kỷ Trình, phản ứng đầu tiên của anh ấy lại là đùn đẩy: \"Thế này là có ý gì?\"
\"Cầm lấy đi.\" Tôi nhét chặt thẻ ngân hàng vào lòng bàn tay anh ấy: \"Mấy năm nay tôi tiêu xài hoang phí, số tiền tiết kiệm được không nhiều. Trong thẻ có hai triệu tệ, chắc là đủ rồi. Tôi không có chỗ nào cần dùng đến tiền, anh cần nó hơn tôi.\"
\"Năm mười tuổi mẹ tôi gặp tai nạn giao thông, lúc đó dù có tốn bao nhiêu tiền, trả giá thế nào tôi cũng chấp nhận, chỉ cần cứu được bà.\"
\"Nhưng bà tử vong tại chỗ, ngay cả cơ hội cấp cứu cũng không có.\"
\"Từ đó về sau, tôi không bao giờ muốn chứng kiến bất kỳ cặp mẹ con nào trên đời phải âm dương cách biệt nữa.\"
Dù mười mấy năm đã trôi qua, khi nhắc đến cái chết của mẹ, tôi vẫn khó tránh khỏi nghẹn ngào.
Kỷ Trình hốt hoảng rút khăn giấy ra muốn lau nước mắt cho tôi, nhưng dường như e ngại điều gì, tay anh ấy dừng lại giữa không trung.
\"Xin lỗi.\" Anh ấy nói: \"Tôi không biết... Xin lỗi...\"
Anh ấy cúi đầu: \"Hiện tại tôi làm gia sư, dựng thiết bị mạng cho người ta, một tháng cũng được mười hai nghìn. Hai triệu tôi tính theo lãi suất năm phần trăm thị trường trả cho cô, một năm mười vạn... khoảng chừng phải trả trong hai mươi năm...\"
Tôi ngắt lời anh ấy: \"Anh không để lại tiền ăn cơm à?\"
\"Tôi, tôi...\" Kỷ Trình nhìn mũi giày, dường như rất ngượng ngùng, giọng nhỏ lại: \"Tiền ăn một tháng hai trăm tệ là đủ, tôi không ăn thịt, sẽ cố gắng trả lại cho cô sớm nhất...\"
\"Bây giờ cũng một tháng hai trăm tệ sao?\" Tôi hơi ngạc nhiên: \"Thế làm sao anh cao được thế này?\"
Kỷ Trình mím mím môi, quay đầu nhìn về phía phòng bệnh. Thấu qua khe kính trong suốt trên cửa, anh như nhìn thấy người mẹ đang co quắp trên giường bệnh, gầy gò đến cực điểm, chẳng nặng hơn một nắm xương khô là bao.
\"Mẹ tôi khá cao.\" Anh ấy hơi ngẩn ngơ, lại mỉm cười nhẹ: \"Bà cao một mét bảy mươi lăm, ở thời đó thường được người ta gọi là người mẫu cao kều, còn có người hỏi bảo bà so với hoa hậu Hồng Kông cũng chẳng kém cạnh...\"
Tôi vỗ vỗ vai anh ấy, rất gầy, thậm chí có thể sờ thấy phần xương đòn gồ lên.
\"Không cần gấp gáp trả.\" Tôi cười cười: \"Sau này dạy xong đừng vội về, ở lại nhà tôi ăn cơm.\"
Kỷ Trình cắn môi dưới, giữ im lặng một lúc, rồi nghiêm túc nói: \"Nếu cô có việc gì cần dùng đến tôi, chỉ cần không phải bảo tôi đi giết người, tất cả những việc khác tôi đều lên núi đao xuống biển lửa không từ.\"
Nhìn dáng vẻ nghiêm túc của anh ta, tôi vừa buồn cười vừa dở khóc dở cười: \"Tôi là người thừa kế của nhà họ Lâm, sao có thể bảo anh đi làm mấy chuyện đó chứ?\"
\"... Người thừa kế?\" Kỷ Trình kinh ngạc: \"Là cô sao? Tôi cứ nghĩ sẽ là anh trai cô... Xin lỗi, xem ra ông Lâm thực sự rất yêu cô.\"
\"Đúng vậy.\" Tôi nói: \"Tiếc là anh trai tôi không nghĩ thế, anh ta cảm thấy nhà họ Lâm phải là của anh ta mới đúng.\"
\"Tôi hiểu rồi.\" Kỷ Trình làm một động tác cứa cổ: \"Cô muốn tôi...?\"
Tôi ngẩn ra hai giây, ngay sau đó bật cười lớn, con người này, lại sẵn sàng vì tiền mà đi giết người sao?
\"Anh nghĩ đi đâu đấy!\" Tôi nói: \"Anh tôi dù sao cũng là anh tôi.\"
\"Người nhà với nhau sao có thể làm hại lẫn nhau được chứ?\"
Chỉ là khiến anh ta chịu chút thiệt thòi nhỏ mà thôi...
Trong góc mắt, một bóng người ngoài cửa sổ lướt qua.
\"Sao em lại đi lôi lôi kéo kéo không rõ ràng với một thằng gia sư rách!\"
Từ khi sinh ra đến giờ, tôi chưa từng thấy Lâm Yến Chu có bộ dạng nghiêm khắc tức giận đến thế bao giờ.
\"Một tháng rồi! Mới đầu anh còn tưởng là tình cờ, cho đến khi thấy hai người ngày càng thân thiết... Chiều hôm qua, em lại cùng cậu ta đến bệnh viện thăm mẹ cậu ta!\"
\"Giai Giai, nếu anh trai không phát hiện ra, có phải mấy tháng nữa em sẽ lén trộm sổ hộ khẩu đi đăng ký kết hôn với cậu ta hay không?\"
Anh ta mở điện thoại ra. Trong ảnh, tôi và Kỷ Trình đi lại thân mật, thậm chí tôi còn tự tay chỉnh sửa quần áo cho anh ấy.
\"Giai Giai, sao em có thể hồ đồ đến mức này! Công ty giao vào tay em, chẳng lẽ lại rơi vào tay một kẻ dưới đáy xã hội sao?\"
Lâm Yến Chu thở dài một tiếng: \"Bố, bố đuổi việc thằng gia sư đó đi, loại người tâm địa bất chính này, giữ lại có tác dụng gì?\"
\"Đừng có mà nhắm đến chuyện làm rể ở rể đấy...\"
Câu nói này dường như đã đâm trúng vảy ngược nào đó, sắc mặt Lâm Chúng Thanh lập tức sa sầm như nước.
[Cười chết mất, lão già chẳng phải cũng là ở rể đi lên sao?]
[Chậc chậc, bản thân muốn làm rể ở rể đào mỏ vợ, lại sợ con gái tìm một thằng rể ở rể đào mỏ mình...]
Tôi lập tức ngẩn người. Bố là ở rể? Sao tôi lại không biết?
\"Mày thấy lúc nào, ở đâu?\" Bố lướt lướt xem vài bức ảnh, sắc mặt không rõ vui buồn.
\"Chiều qua lúc hai giờ.\" Lâm Yến Chu nói: \"Con ở dưới bệnh viện thấy một người rất giống Giai Giai, chụp ảnh lại mới xác định là em ấy... bên cạnh còn có Kỷ Trình đi cùng, họ cùng nhau đi gặp mẹ nó.\"
\"Một tháng qua, Giai Giai thường xuyên...\"
\"Mày theo dõi em gái mày?\" Lâm Chúng Thanh lạnh lùng hỏi một câu.
Nhìn nét mặt ngoài dự tính của Lâm Yến Chu, ông ta suy nghĩ một chút rồi lại nói: \"Không đúng, chiều qua hai giờ, mày vẫn còn điểm danh ở công ty... Nhiều ảnh như thế này, mày thuê người theo dõi em gái mày? Theo dõi suốt một tháng?\"
Mặt Lâm Yến Chu lập tức cắt không còn một giọt máu. Anh ta ấp úng: \"Con... con...\"
Bốp!
Lâm Chúng Thanh không chút ngần ngại tát anh ta một cú nảy lửa, u ám nói: \"Mày bị khùng rồi! Sao, thấy tao để em gái mày thừa kế nhà họ Lâm, trong lòng mày có oán hận?\"
\"Làm sao có thể chứ!\"
Lâm Yến Chu ôm mặt: \"Con chỉ là... vì muốn bảo vệ Giai Giai!\" Đầu óc anh ta dường như rốt cuộc cũng xoay chuyển kịp, bắt đầu kiếm cớ.
Mắt tôi đỏ bừng, ngắt lời Lâm Yến Chu: \"Anh, em biết anh không vui...\"
\"Anh lớn hơn em, lại là đàn ông, nhà họ Lâm nên giao cho anh mới đúng.\" Tôi mím môi: \"Thực ra em chẳng tha thiết gì cái công ty hay tài sản cả, em chỉ muốn gia đình mình êm ấm, có thể mãi mãi ở bên nhau...\"
\"Em nhường công ty lại cho anh, anh đừng giận em nữa có được không?\"
\"Nó dám!\" Lâm Yến Chu còn chưa kịp lên tiếng thì lại bị bố ngắt lời: \"Diệu Tổ, con đúng là quá lương thiện rồi.\"
\"Là đàn ông thì đã sao? Con mới là con gái cả của Lâm Chúng Thanh bố! Lâm Yến Chu chẳng qua chỉ là một đứa con thứ mà thôi!\" Ông ta trầm giọng nói: \"Lập con làm người thừa kế là quyết định của bố, nó có ý kiến gì, cứ bảo nó nhằm vào bố đây này!\"
\"Bố...\" Tôi nhéo mạnh bản thân hai cái, nước mắt lã chã rơi như mưa: \"Con và Kỷ Trình không phải như anh nghĩ đâu. Từ sau khi mẹ mất, con không thể chịu đựng nổi cảnh mẹ con sinh ly tử biệt nữa. Kỷ Trình nói mẹ anh ấy bệnh nặng, con chỉ là muốn giúp anh ấy một tay...\"
Nhắc đến mẹ, bố hít sâu một hơi, viền mắt cũng dần đỏ lên.
Lâm Yến Chu há hốc mồm, nhìn cảnh bố con tình thâm của chúng tôi, hoàn toàn ngơ ngác tại chỗ.
', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-5', '', '', '2026-10-13 16:20:16', '2026-10-13 09:20:16', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-2-5.html', '0', 'chuong', '', '0'),
('127', '1', '2026-10-13 16:22:13', '2026-10-13 09:22:13', 'Thực ra, từ lần đầu tiên anh ta thuê người theo dõi tôi, tôi đã phát hiện ra rồi nhưng tôi thuận nước đẩy thuyền, diễn kịch thân mật với Kỷ Trình suốt một tháng trời. Chủ yếu là để Lâm Yến Chu chó cùng bứt giậu, tôi liền có thể nắm lấy điểm anh ta theo dõi tôi mà gậy ông đập lưng ông.
[Lão già chột dạ rồi.]
[Mẹ của nữ chính chính là vì ông ta mà chết, sao ông ta có thể không chột dạ?]
Tôi khựng lại.
Bình luận hình như không nhận ra sự bất thường của tôi, lướt qua điên cuồng:
[Đúng rồi đúng rồi, lão già lúc nào cũng giả vờ mình là người đàn ông trọng tình nghĩa,]
[Mẹ nữ chính bị ông ta lừa kết hôn, tuy nói là ở rể nhưng sau khi kết hôn, mẹ nữ chính đối ngoại còn để ông ta tiếp quản công ty, bồi dưỡng người con rể này.]
[Mẹ nữ chính phát hiện ra bộ mặt thật của ông ta xong liền cãi nhau to một trận, muốn ly hôn, lão già lại bảo mẹ nữ chính là bố cô bây giờ đang bệnh nặng, cô có gan để ông ấy biết cô muốn ly hôn không. Mẹ nữ chính chạy vụt ra ngoài, mới gặp tai nạn giao thông.]
[Đúng vậy đúng vậy, sau khi mẹ chết lão già lại bắt đầu điên cuồng diễn vẻ thâm tình, diễn diễn một hồi chính mình cũng tin luôn, hừm.]
[Ông ngoại nữ chính cũng không chịu nổi cú sốc mất con gái duy nhất mà qua đời... Lão già không mấy năm sau liền đổi tên công ty thành nhà họ Lâm... Người không biết lại còn tưởng là do một tay ông ta gây dựng nên.]
\"Diệu Tổ.\" Giọng Lâm Chúng Thanh run rẩy: \"Con gái ngoan của bố...\"
Tôi hít sâu một hơi, đôi mắt đẫm lệ nhìn ông ta. Lâm Chúng Thanh hận không thể ôm chầm lấy tôi mà khóc một trận, không biết qua bao lâu, ông ta mới miễn cưỡng bình tĩnh lại: \"Yến Chu, mày đi quản lý công ty chi nhánh đi.\"
\"Diệu Tổ, dù sao con cũng đang chuẩn bị đi du học, thời gian này cứ theo bố đến công ty học hỏi, để hội đồng quản trị gặp mặt.\" Ông ta xoa đầu tôi, ánh mắt tràn ngập tình bố con thuần khiết cùng sự tán thưởng: \"Đừng vì mình là con gái mà nhút nhát, Diệu Tổ nhà ta cân cả đấng râu mày!\"
\"Mẹ con ở trên trời có linh thiêng, thấy con vừa lương thiện vừa kiên cường thế này, nhất định sẽ rất ấm lòng!\"
Không. Mẹ thấy kẻ ăn tươi nuốt sống bà lại được kê cao gối ngủ ngon, thấy đứa con gái duy nhất của bà nhận giặc làm bố, làm con sâu ăn bám vô dụng suốt hai mươi hai năm, sao bà có thể ấm lòng được chứ?
Bà có chết cũng không nhắm mắt!
Tôi nhìn gương mặt quen thuộc của bố, nhìn gương mặt từ ái và tuấn tú này, lần đầu tiên cảm thấy lạnh gáy toàn thân rồi lại bùng lên ngọn lửa căm hận rực cháy.
Tôi hận ông ta. Không chỉ hận ông ta, tôi hận Lâm Yến Chu - kẻ vốn dĩ sẽ thừa kế tài sản trong nhà, hận chính bản thân hồ đồ tăm tối. Thậm chí, tôi hận kẻ đã sáng tạo ra vận mệnh của tôi - tác giả của cuốn sách này.
Nếu tôi không có năng lực biến điều ước thành hiện thực, nếu tôi không nhìn thấy những dòng bình luận này, liệu tôi có theo quỹ đạo mà tác giả đã thiết lập sẵn, sống mơ mơ hồ hồ cả đời, cả đời cũng không biết được sự thật về cái chết của mẹ?
Tôi sẽ chìm đắm trong sự ban phát của người bố và anh trai, coi họ là cả bầu trời. Tôi sẽ nắm tay bố, để ông ta trao tôi cho người chồng tương lai. Từ vật sở hữu của phụ quyền, biến thành vật sở hữu của phu quyền.
Nhưng chẳng phải tôi là nữ chính đoàn sủng sao? Tại sao, những kẻ cưng chiều tôi đều là đàn ông?
Tại sao mẹ tôi lại mất sớm, tại sao tôi không có chị em thân thiết, không có bạn nữ thân thương nào, tại sao tôi chỉ nhận được sự đố kỵ cạnh tranh từ phụ nữ, mà lại nhận được sự cưng chiều từ đàn ông? Tất cả những điều này, há chẳng phải rất kỳ lạ sao?
Tôi nghĩ, tôi không bao giờ muốn được cưng chiều nữa. Tôi không cần sự ban ơn từ kẽ tay họ, không cần những lời đường mật êm tai, không cần trang sức lụa là, không cần tình yêu.
Thứ tôi muốn là cái thứ mà Lâm Yến Chu ôm chặt trong lòng không buông, thứ mà chỉ cần tôi chạm vào một chút là anh ta sẽ lập tức xé rách mặt nạ người anh tốt để trở mặt với tôi… thứ đó.
Quyền lực! Quyền lực! Quyền lực!
Thứ tôi muốn chính là quyền lực!
Sau chuyện này, Lâm Yến Chu đã hoàn toàn hiểu được trọng lượng của tôi trong lòng bố. Anh ta lập tức quệt mặt một cái, lại biến thành dáng vẻ người anh trai dịu dàng chu đáo.
Để bù đắp cho tôi, anh ta đặc biệt mang tới một món quà.
\"Xin lỗi em, hôm đó anh trai hơi quá khích.\" Vừa nói, Lâm Yến Chu vừa mở chiếc hộp CL ra, một đôi giày cao gót lấp lánh rực rỡ xuất hiện trước mặt tôi.
Anh ta lại lấy ra một chiếc váy, chiếc váy dài dạ hội đuôi cá thiết kế vô cùng trang nhã, trước sau đều kín đáo, tỏa ra ánh sáng như ánh trăng.
Nhìn vẻ mặt của tôi, Lâm Yến Chu mỉm cười đầy tự tin: \"Anh nhớ là, em thích nhất mấy thứ này.\"
Bàn tay tôi nhẹ nhàng xoa qua chiếc váy, sóng sánh lấp lánh, giống như lớp da mà nàng tiên cá trút ra.
Cả thời thiếu nữ, bố và anh trai không biết mệt mỏi tặng tôi những thứ này, búp bê tinh xảo, váy công chúa đắt tiền, tuổi trẻ của tôi gần như bị lấp đầy bởi những thứ đẹp đẽ hư ảo như bọt nước ấy. Bởi vì họ cảm thấy, tôi đương nhiên phải thích những thứ này, con gái đương nhiên phải thích những thứ này.
Lâu dần, tôi cũng thực sự quên mất bản thân vốn dĩ muốn cái gì.
Tôi muốn một lưỡi kiếm sắc bén.
Tôi vuốt ve chiếc váy, khẽ khàng nói: \"Xin lỗi anh...\"
Mấy chữ phía sau nhẹ hơn cả gió, Lâm Yến Chu không nghe rõ nhưng anh ta không bận tâm. Thần thái lập tức thả lỏng ra, cười nói: \"Được rồi được rồi, những chuyện không vui trước đây gác lại hết nhé. Giai Giai, anh em mình làm gì có mối thù qua đêm?\"
Tôi ngẩng đầu lên, nhìn anh ta.
Xin lỗi anh. Để lật đổ anh, thực sự quá dễ dàng...
Giây tiếp theo, tôi đột nhiên ngã nhào vào lòng Lâm Yến Chu!
Anh ta tay chân luống cuống, lập tức đỡ lấy tôi. Trong chớp mắt, tay tôi kéo xộc xệch cổ áo Lâm Yến Chu, môi tôi để lại một vết son đỏ trên áo anh ta. Lâm Yến Chu trừng lớn mắt, còn chưa kịp nói gì, tôi đã đẩy mạnh anh ta ra! Lảo đảo chạy xông ra ngoài!
\"Bố ơi cứu con!\"
Lâm Yến Chu đuổi theo ra, gọi lớn tên tôi.
Tôi cuống cuồng chạy không nhìn đường, hẫng chân một cái, ngã nhếch nhác xuống đất. Đầu gối đau nhói một trận kịch liệt, nhưng tôi không dám ngừng lại chút nào, gần như là bò lê bò lết chạy đến bên cạnh Lâm Chúng Thanh, quỳ sụp trước mặt ông ta.
\"Bố...\" Tôi khóc đến mức toàn thân run rẩy, gằn từng chữ: \"Anh trai hôn con...\"
Ánh mắt kinh hoảng của Lâm Chúng Thanh đảo qua một lượt trên người tôi rồi dừng lại trên người Lâm Yến Chu đang đuổi theo phía sau.
\"Nói bậy!\" Lâm Yến Chu không thể tin nổi: \"Em bị khùng rồi, em dựa vào đâu mà vu khống anh!\"
\"Lâm Giai Giai, vừa rồi anh còn chưa đụng vào em, là tự em ngã rồi khóc lóc om sòm chạy ra!\"
Vừa nói, anh ta vừa nhìn về phía Lâm Chúng Thanh: \"Bố! Giai Giai là do con nhìn nó lớn lên, sao con có thể có thứ suy nghĩ đó với nó được!\"
\"Chẳng lẽ em lại lấy sự trong sạch của mình ra vu khống anh sao?!\" Tôi ngẩng cổ lên, mắt đong đầy nước mắt.
\"Bố ơi, anh bảo anh thích con từ hồi cấp ba rồi, dù sao nước phù sa không chảy ruộng ngoài...\"
\"Anh còn bảo, bố sẽ không để người khác đi lại con đường cũ của bố, lại đến làm rể ở rể đâu...\"
Với tư cách là đứa con gái nhỏ, đáng lẽ tôi không thể biết những chuyện này. Mà lúc Lâm Chúng Thanh ở rể Lâm Yến Chu đã sáu tuổi, đã biết ghi nhớ sự việc rồi.
Lâm Chúng Thanh cắn chặt môi, thần thái u ám đến cực điểm. Ông ta nhìn về phía Lâm Yến Chu, giọng nói vang lên như sấm sét đùng đùng: \"Mày còn cái gì muốn nói nữa không?\"
Lâm Yến Chu ngã khụy xuống đất, khuôn mặt tuấn tú không một tia máu: \"Con chưa bao giờ nói những lời này! Con không biết nó nghe từ đâu ra!\"
\"Bố.\" Anh ta khẩn cầu: \"Chỉ dựa vào mấy câu của Lâm Giai Giai mà bố đã tin nó sao?\"
\"Nó vu khống con! Bố có thể kiểm tra camera, có thể hỏi người giúp việc!\"
\"Mày coi tao là thằng ngu chắc?!\" Lâm Chúng Thanh không chút khách sáo: \"Mày muốn làm trò đồi bại với em gái mày, chắc chắn đã chuẩn bị kỹ càng. Camera sợ là đã bị mày ngắt kết nối rồi đúng không?\"
\"Mấy ngày nay mày liên tục gièm pha Diệu Tổ, bảo nó không có năng lực không thừa kế nổi nhà họ Lâm. Thấy tao không đồng ý, mày liền muốn hủy hoại nó đúng không?\"
Lâm Yến Chu trợn tròn mắt muốn nứt ra, chỉ một mực kêu oan.
Lâm Chúng Thanh hít sâu một hơi, cũng bình tĩnh lại, thấy sắc mặt anh ta quả thực rất chân thành, ông ta liền nói: \"Mày bảo Diệu Tổ vu khống mày, mày có bằng chứng không?\"
Lâm Yến Chu câm nín. Loại chuyện này, dĩ nhiên không thể có bằng chứng.
Tôi sụt sùi nước mắt nói: \"Anh bảo đối với em tình cảm sâu nặng, tình không kìm nén được, còn cất giữ cẩn thận chiếc váy hồi cấp ba của em nữa...\"
Lời vừa dứt, sắc mặt Lâm Yến Chu đã không thể dùng từ nhợt nhạt để mô tả nữa. Anh ta trừng trừng nhìn tôi, ánh mắt như muốn đốt cháy tôi thành một lỗ hổng, cả người như gã già sắp chết, răng nghiến chặt trần trật.
\"Cho dù có.\" Giọng Lâm Yến Chu như thể rít ra từ kẽ răng: \"Cũng là do mày nhét vào để hãm hại tao...\"
\"Camera tầng của em đâu có hỏng.\" Tôi nói: \"Mấy ngày nay tung tích của em, đi những đâu, xem camera một cái là biết ngay!\"
\"Hơn nữa, em hãm hại anh thì có lợi lộc gì?\" Vừa nói, nước mắt tôi lại trào ra: \"Em chỉ muốn gia đình chúng mình êm ấm...\"
Lâm Chúng Thanh xót xa xoa xoa đầu tôi. Ông ta không nhìn vẻ mặt Lâm Yến Chu thêm lần nào nữa, dứt khoát bước vào phòng.Cuối cùng, dưới đáy tủ quần áo của Lâm Yến Chu, đã tìm thấy một chiếc chân váy đồng phục cấp ba.Trông có vẻ đã nhiều năm rồi, váy bị giặt đến cũ kỹ nhưng không hề ố vàng chút nào, đủ thấy sự nâng niu của chủ nhân.
Trên đó còn thoang thoảng mùi hương nhè nhẹ, là mùi hương gỗ đàn hương trong tủ quần áo của Lâm Yến Chu thấm đượm vào.
Tôi thốt lên kinh ngạc: \"Đây chẳng phải là đồng phục hồi cấp ba con bị mất sao? Sao lại ở đây?\"
Lâm Chúng Thanh đơ ra tại chỗ, bàn tay run rẩy không thể kiểm soát được.
Không biết qua bao lâu, ông ta gầm lên một tiếng, điên cuồng xé rách chiếc váy đồng phục, đập tới tấp vào người Lâm Yến Chu!
\"Thằng nghịch tử, thằng bất hiếu, sao tao lại sinh ra cái loại chó má như mày!\"
\"Thằng súc sinh, Giai Giai là em gái mày đấy!\"
Lâm Yến Chu bị ông ta đánh cho quỳ rạp nửa người xuống đất, vẫn muốn giải thích: \"Bố, bố nghe con nói...\"
\"Cút!\" Lâm Chúng Thanh xô đẩy anh ta dữ dội: \"Cút, cút đi tìm cái con mẹ mày đi! Ông đây không bao giờ muốn nhìn thấy mày nữa!\"
\"Từ nay về sau, mày không còn là con trai tao nữa!\"
Thế thì tốt quá rồi.
Cũng nhờ có bình luận nhắc nhở, tôi mới biết Lâm Yến Chu đã giấu chiếc váy đồng phục của tôi suốt ba năm trời.
Tôi cũng biết, muốn lật đổ Lâm Yến Chu, thực ra có rất nhiều cách, rất nhiều cách đường đường chính chính nhưng thứ tôi muốn là đòn chí mạng một phát chết ngay, khiến anh ta vĩnh viễn không thể lật lại bản cờ.
Nếu cho Lâm Yến Chu dù chỉ một chút cơ hội thở dốc, anh ta cũng sẽ cuộn trào trở lại, đấu với tôi một mất một còn. Đến lúc đó, muốn lật đổ hoàn toàn Lâm Yến Chu sẽ tốn sức hơn nhiều.
Bình luận vốn luôn ủng hộ tôi rốt cuộc cũng xuất hiện bất đồng ý kiến.
[Nữ chính đây là vu cáo rồi... Anh trai đâu có làm mấy trò đó với cô ấy...]
[Nữ chính cũng quá ác rồi, so với trước kia đúng là hai người hoàn toàn khác nhau.]
[Ý gì đây, trước kia nữ chính yếu đuối thì mấy người bảo nữ chính tàn nhẫn lên, giờ nữ chính tàn nhẫn thật rồi lại mắng nữ chính ác??]
[Lạy chúa tôi các anh chị ơi, nữ chính chỉ là làm những việc mà đàn ông hay làm thôi mà!]
[Mấy người xót xa thằng con thì có phúc rồi đấy, tôi có mã nhận tiền của Lâm Yến Chu đây, ai muốn mời nó ăn McDonald\'s không?]
Tôi rủ hàng mi xuống.
Tôi không còn cách nào khác, dù sao thì, nhân từ với kẻ địch chính là tàn nhẫn với bản thân, chẳng phải sao?
', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-5', '', '', '2026-10-13 16:22:13', '2026-10-13 09:22:13', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-3-5.html', '0', 'chuong', '', '0'),
('128', '1', '2026-10-13 16:24:06', '2026-10-13 09:24:06', 'Lâm Yến Chu xám xịt cuốn gói chuyển ra ngoài. Không mấy ngày sau, anh ta đã bị Lâm Chúng Thanh bãi miễn mọi chức vụ. Mười mấy năm tình cha con trong chớp mắt hóa thành mây khói, ông ta thậm chí còn chẳng buồn đi tiễn Lâm Yến Chu mà rầm rộ khẩn trương lo liệu việc đổi tên cho tôi.
Mới đầu định gọi là Diệu Tổ. Bố ưng ý cái tên này nhất, nhưng thầy phong thủy lại bảo tên này đại hung.
Ông ta liên tục đổi mấy cái, Quang Tông, Kế Nghiệp, Thừa Tự, đo ra đều là đại hung, cuối cùng, thầy thở dài nói: \"Lựa chọn tốt nhất là đổi họ cho đứa trẻ trước.\"
\"... Đổi thành họ gì?\"
\"Họ của mẹ nó.\"
Lâm Chúng Thanh như con chó bị giẫm trúng đuôi, lập tức nhảy dựng lên: \"Sao mà thế được! Diệu Tổ là dòng máu nhà họ Lâm tôi, là con gái dòng chính nhà họ Lâm tôi, sau này phải nối dõi tông đường nhà họ Lâm tôi chứ!\"
\"Đổi họ thì chẳng phải thành người ngoài sao!\"
Cuối cùng, ông ta đành phải bỏ ý định đổi tên cho tôi.
Mấy ngày sau, ông ta lại sa thải Kỷ Trình.
\"Bố biết hai đứa trong sạch.\" Ông ta thở ngắn thở dài: \"Nhưng hai đứa đều đang tuổi thanh xuân phơi phới, bố thật sự sợ... Bố không phải chê bai nó, mà là gia cảnh thế này không mang lại chút trợ lực nào cho chúng ta cả, hiểu không?\"
\"Loại đàn ông này chơi bời chút thì được.\" Ông ta bày mưu tính kế cho tôi: \"Sau khi kết hôn, con có thể lại cùng nó... con hiểu mà.\"
Nói xong, ông ta căng thẳng nhìn tôi như thể chỉ sợ tôi phát bệnh lụy tình, sống chết đòi ở bên Kỷ Trình, dù sao năm xưa mẹ tôi cũng đã như vậy.
Tôi mím môi cười lặng lẽ, khẽ nói: \"Tất cả nghe theo bố ạ.\"
Ông ta trút được gánh nặng thở phào, liên tục khen tôi là con gái ngoan.
Sau ngày hôm đó, Lâm Chúng Thanh bắt đầu dắt tôi tham gia vào công việc công ty. Khác với sự kiểm tra soi xét dành cho Lâm Yến Chu, ông ta hết lòng hết sức, không chút giữ lại mà trải đường cho tôi, thậm chí trực tiếp để tôi nhảy dù xuống làm Giám đốc tổng bộ phận.
\"Nếu thực sự học không vào thì cũng không sao.\" Ông ta từ ái bảo: \"Là lỗi của bố, đáng lẽ nên để con học ngành kinh tế từ sớm.\"
\"Chờ thêm vài năm nữa, bố sẽ đi lập một cái quỹ tín thác.\"
Bữa tiệc rượu ly chén cụng nhau rôm rả, Lâm Chúng Thanh giới thiệu tôi với từng người từng người là các đối tác làm ăn. Đối mặt với lời trêu chọc của các chú các cô bảo ông ta cưng chiều con gái nhất, Lâm Chúng Thanh hỉ hả cười: \"Tôi chỉ có đúng một đứa con này, sau này gia nghiệp đều là của nó, không chiều nó thì chiều ai?\"
Lời nói sang sảng đanh thép, những người này hình như cũng biết chuyện Lâm Yến Chu, dồn dập gật đầu.
Có một người cô xoa xoa tóc tôi, đầm ấm nói: \"Rất giống mẹ cháu.\"
Tôi đang định đáp lời, bình luận lại đột nhiên hiện lên:
[Nam chính cũng ở đây!]
[Đúng rồi, cuốn này đáng lẽ là đoạn nam nữ chính gặp nhau lần đầu nè, anh ta yêu nữ chính ngay từ cái nhìn đầu tiên. Sau này nhà họ Lâm phá sản, vội vã chạy đến nhà cầu hôn.]
Nam chính?
Ánh mắt tôi băng qua lớp lớp đám đông, vừa vặn va phải một đôi mắt thâm trầm.
Bốn mắt nhìn nhau, Tấn Trì trong khoảnh khắc ngẩn ngơ. Anh tiện tay nâng ly rượu, thong thả đi về phía chúng tôi.
\"Tiểu Tấn tổng!\" Lâm Chúng Thanh vội vã vồn vã đón chào, cười bảo: \"Ba ngày không gặp, phong thái càng thêm rạng rỡ nha! Tiểu Tấn tổng vừa bước vào nơi này của tôi, thật làm sáng bừng cả gian nhà nhỏ!\"
Tấn Trì hàn huyên vài câu với ông ta, ánh mắt lại đảo về trên người tôi.
Dáng vẻ anh nhã nhặn lịch thiệp nhưng ánh mắt lại cực kỳ mang tính xâm lược. Đây là kiểu ánh mắt khiến người ta vô cùng khó chịu, như sói đã nhắm trúng con mồi.
Tôi hít sâu một hơi, đang định nặn ra một nụ cười thì Lâm Chúng Thanh đã nhích hai bước, chắn chặt trước mặt tôi, cười giới thiệu với Tấn Trì: \"Đây là khuyển tử nhà tôi.\"
\"Khuyển tử?\" Tấn Trì ngẩn ra một chút: \"Lâm Yến Chu chẳng phải...\"
\"Haiz, nhắc đến cái thằng ranh con đó làm gì!\" Lâm Chúng Thanh xua xua tay: \"Diệu Tổ... khụ, Giai Giai là đứa con duy nhất của tôi, quý giá như con ngươi mắt tôi vậy.\"
\"Nó còn nhỏ, mới học đại học, chẳng biết cái gì cả.\" Lâm Chúng Thanh cười nói: \"Mấy ngày nữa, tôi định đưa nó đi du học nước ngoài.\"
\"Du học...?\"
\"Đúng vậy.\" Ông ta có chút cảm thán, nói đùa: \"Học xong thạc sĩ rồi học tiến sĩ, kiểu gì cũng phải giữ lại đến năm ba mươi tuổi mới gả đi...\"
Mắt Tấn Trì đảo một vòng, rất nhanh cũng hiểu ra. Khóe miệng anh nhếch lên một nụ cười rất nhạt, cụng ly với Lâm Chúng Thanh: \"Tôi ở nước ngoài cũng có bạn bè, nếu cô Giai Giai cần giúp đỡ gì, cứ việc đến tìm tôi.\"
Anh trao đổi phương thức liên lạc với tôi, gật đầu rồi rời đi.
[Lão già dạo này đúng là như điên rồi ấy, đối với nữ chính tốt cưng không còn lối thoát.]
[Không lẽ điều ước nữ chính ước thực ra là bảo lão già yêu thương mình thật lòng sao...]
Trên đường về nhà, Lâm Chúng Thanh thở dài một tiếng.
\"Thằng nhóc Tấn Trì đó có ý với con đấy.\" Đôi mắt vừa nãy còn vương hơi men của ông ta trong khoảnh khắc trở nên minh mẫn: \"Thế nhưng bố không muốn con ở bên nó.\"
\"Trèo cao ngã đau mà... Môn đăng hộ đối mới bền. Gia đình tầm trung như chúng ta, tiểu phú là an ổn, thế là tốt rồi.\"
Ông ta nhắm mắt lại, chìm vào giấc ngủ sâu.
Tôi nhìn gương mặt Lâm Chúng Thanh, trong lòng cuộn trào một dòng cảm xúc phức tạp. Không phải cảm động mà là sự căm hận thuần túy, xen lẫn phẫn uất vì bị đùa giỡn trêu ngươi.
Ngày hôm sau, biến cố đột ngột xảy ra!
\"Giai Giai?\" Lâm Chúng Thanh dụi dụi mắt: \"Sao con lại ở nhà, được nghỉ học à? Anh con đâu?\"
Tôi nhất thời chưa phản ứng kịp: \"Lâm Yến Chu bị đuổi đi rồi mà...\"
\"Đuổi đi?!\" Lâm Chúng Thanh đột nhiên cao giọng lên: \"Nó là con trai bố, sao có thể bị đuổi đi, ai đuổi nó đi!\"
Nhìn thần thái vừa xa lạ vừa quen thuộc trước mắt, toàn thân tôi như có dòng điện chạy qua, đột nhiên vỡ lẽ!
Điều ước đó đã hết hiệu lực rồi!
\"Bố, bố nhớ nhầm rồi đúng không?\" Tôi nén sự hoảng loạn trong lòng xuống, cố gắng khiến bản thân trông có vẻ không có gì bất thường: \"Con bảo là anh trai bị phái đi công tác mà, còn là do chính bố cử anh ấy đi đấy.\"
\"... Bố cử nó đi?\" Lâm Chúng Thanh xoa xoa đầu, nét mặt khó chịu: \"Hôm qua uống nhiều rượu quá, đầu đau như búa bổ, chẳng nhớ nổi cái gì cả...\"
\"Giai Giai, anh con đi đâu rồi?\"
Trong lòng tôi xao động, những chuyện xảy ra trong thời gian này, Lâm Chúng Thanh hoàn toàn không có ký ức.
\"Công ty chi nhánh ạ.\"
\"Ồ, công ty chi nhánh.\" Lâm Chúng Thanh không mảy may nghi ngờ: \"Thằng ranh đó đúng là vẫn còn thiếu rèn luyện, có khi là do bố uống rượu xong nhất thời bốc đồng.\"
Ông ta lại lảm nhảm nói thêm vài câu rồi lại chìm vào giấc ngủ.
Tôi cầm lấy điện thoại của ông ta rảo bước ra khỏi phòng, mồ hôi lạnh đã ướt đẫm tấm lưng từ lúc nào.
Làm sao bây giờ, lời giấu giếm của tôi giấu được một lúc chứ không giấu được cả đời, chỉ cần Lâm Yến Chu quay về, tất cả sẽ tiêu tùng! Đến lúc đó tôi không những không thừa kế nổi tài sản gia đình mà rất có thể còn bị đẩy ra ngoài đi kết hôn… Lâm Chúng Thanh thực sự yêu tôi thì cảm thấy gả cho Tấn Trì là trèo cao ngã đau, còn Lâm Chúng Thanh bây giờ thì lại không nghĩ thế!
Tôi không muốn thế này, tôi không phục!
Tôi hít sâu một hơi, ngẩng đầu nhìn lên những dòng bình luận trôi dạt.
\"... Giúp tôi với.\"
Bình luận vừa mới tràn ngập màn hình lập tức khựng lại, hóa thành một khoảng trống lặng ngắt như tờ.
[ĐM, cái tình huống gì thế này.]
[Vừa rồi nữ chính đang nói chuyện với ai thế? Ở đây còn có ai khác sao?]
[Ngu thế, nữ chính là đang nói chuyện với chúng ta đấy!]
[Nữ chính nhìn thấy chúng ta sao?]
\"Đúng vậy.\" Tôi cúi đầu: \"Tôi vẫn luôn nhìn thấy.\"
\"Điều ước năm đó tôi ước, cũng chính là làm đứa con mà bố yêu thương nhất cả đời.\" Tôi chua chát nói: \"Tôi không ngờ điều ước này lại có thời hạn.\"
\"Dựa vào một mình tôi hoàn toàn không có cách nào cả... Tôi không còn người trợ giúp nào khác nữa.\"
Kỷ Trình vẫn còn quá non nớt, còn cầu cứu Tấn Trì thì chẳng khác nào đuổi hổ nuốt sài.
Tôi hạ thấp giọng, lặp lại một lần nữa: \"Hãy giúp tôi đi.\"
Trong chớp mắt, sắc thái bình luận mỗi người một vẻ.
Có kẻ chỉ trích tôi rõ ràng nhìn thấy mà còn vờ như không thấy lừa dối họ, có kẻ bảo tôi quá mức tàn nhẫn, có kẻ khuyên tôi cầm lấy ít tiền rồi cao chạy xa bay. Nhưng cuối cùng, tất cả đều bị đè bẹp trong những tiếng hô hào càng lúc càng mạnh mẽ hơn…
[Điều ước tuy không thể kéo dài nhưng bố cô trước đó đã viết một bản di chúc, nếu ông ta có bất kỳ bất trắc gì, người thừa kế tài sản có và chỉ có một mình cô!]
[Cố lên nhé, tất cả những thứ này vốn dĩ nên là của cô!]
Tâm trí tôi chấn động, chạy xông vào phòng đọc sách, lục ra bản di chúc đó.
Nhắm mắt lại, thở dài một hơi thật dài, thật sâu.
\"Là thế này, bố tôi ngủ dậy một giấc, liền biến thành ra thế này...\" Tôi lau nước mắt: \"Không những không nhận đứa con gái này mà còn đòi đón anh kế tôi về... Nhưng rõ ràng anh kế tôi vì hành vi sàm sỡ đồi bại mà bị bố tôi tự tay đuổi đi mà!\"
\"Bác sĩ bảo, không phải bệnh Alzheimer thì cũng là tâm thần phân liệt.\"
\"Tôi cũng thực sự hết cách rồi, mới đưa ông ấy đến đây nhập viện.\"
Viện trưởng bệnh viện tâm thần vỗ vỗ vai tôi, nét mặt cảm thán: \"Hiểu rồi.\"
\"Người già bây giờ hay thế lắm, cô cũng đừng đau lòng quá, cuộc sống vẫn phải tiếp tục sống thôi.\"
Tôi gật đầu, lại nghẹn ngào: \"Bố tôi tính khí cộc cằn dữ tợn, lúc ông ấy phát điên mấy người cũng chẳng cản nổi. Để tránh làm thương hại người vô tội, các vị cứ xích ông ấy lại đi...\"
Viện trưởng càng cảm động hơn: \"Cô Lâm, cô thật là lương thiện.\"
Cách đó không xa, Lâm Chúng Thanh trừng mắt rách khóe: \"Lâm Giai Giai!\"
\"Cái đồ súc sinh, súc sinh! Sao ông đây lại có đứa con gái như mày!\"
Tôi rảo bước đi lên phía trước, nắm chặt lấy bàn tay đang bị nhân viên y tế khống chế chặt chẽ của ông ta: \"Bố, đợi khi nào bệnh bố khỏi, con nhất định sẽ đón bố ra.\"
\"Tao không có bệnh!\" Ông ta gào thét khản đặc cả giọng: \"Tao không có bệnh! Tất cả những thứ này đều là do mày giở trò gài bẫy tao!\"
\"Con trai tao đâu? Con trai tao đâu rồi!\"
Tôi lại thở dài một tiếng.
Sau khi lấy được di chúc của Lâm Chúng Thanh, tôi dứt khoát đưa ông ta đến bệnh viện tâm thần làm kiểm tra. Dưới sự phối hợp của bình luận, Lâm Chúng Thanh dần dần nhìn thấy chúng, những con số quái dị, lặp đi lặp lại, từng câu từng câu đều là \"Mày đã giết Tạ Thục, mày chính là kẻ sát nhân\"!
Lâm Chúng Thanh tại chỗ ngã nhào từ trên ghế xuống, sợ hãi gào thét dữ dội, điên cuồng cào xé không khí trước mắt. Ngay tại chỗ đã bị chẩn đoán mắc bệnh tâm thần phân liệt, ảo giác thính giác và thị giác nghiêm trọng, đã mất đi năng lực hành vi dân sự cơ bản.
Là một người con gái hiếu thảo, tôi đành phải đưa ông ta vào bệnh viện tâm thần, đẫm lệ tiếp quản công ty. Đồng thời gọi điện thoại cho Kỷ Trình, thông báo anh ấy đến tập đoàn nhà họ Lâm đi làm.
Còn đứa con trai mà Lâm Chúng Thanh khắc mòn khảm sâu nhớ nhung... Lâm Yến Chu sau khi bị đuổi đi đã tìm đến mẹ đẻ, muốn mẹ đẻ chu cấp cho mình mở công ty khởi nghiệp nhưng mẹ đẻ đã sớm có gia đình mới và con cái mới, dứt khoát từ chối anh ta.
Sau đó, Lâm Yến Chu khởi nghiệp vài lần, đều thất bại thảm hại, đền bù lỗ sạch bách. Anh ta đến tìm Lâm Chúng Thanh, bị tôi chặn lại ngoài cửa. Cuối cùng, Lâm Yến Chu lưng gánh một khoản nợ đầm đìa đã biến mất hoàn toàn khỏi Giang Thành.
Tôi nắm chặt tay viện trưởng, mắt đẫm lệ nhòa: \"Dù có phải dùng liệu pháp MECT bao nhiêu lần đi nữa, chỉ cần khiến bố tôi tốt lên, tiền bạc không quan trọng!\"
MECT, chính là liệu pháp sốc điện không co giật.
Viện trưởng trịnh trọng hứa với tôi sẽ chăm sóc tốt cho Lâm Chúng Thanh, cho đến khi bệnh tình ông ta tiến triển tốt.
Bệnh tình của ông ta sẽ chẳng bao giờ tốt lên được đâu. Tôi muốn ông ta cả đời này rục xương ở nơi đây!
Bước chân tôi nhẹ nhàng bước ra khỏi cửa lớn bệnh viện, vừa rút chìa khóa xe mở khóa, vừa trả lời những câu hỏi trên bình luận:
\"Đúng rồi, bây giờ công ty là của tôi rồi.\"
\"Nhưng tôi vẫn chưa thỏa mãn.\"
\"Bước tiếp theo sẽ là ai đây nhỉ...\"
Tôi bật cười, nhìn về phía vòm trời bao la không bờ bến, khẽ nói.
\"Tác giả?\"', 'END', '', 'publish', 'open', 'closed', '', 'end', '', '', '2026-10-13 16:24:06', '2026-10-13 09:24:06', '', '0', 'https://muopdangcungcovingot.io.vn/end.html', '0', 'chuong', '', '0'),
('129', '1', '2026-10-16 21:13:16', '2026-10-16 14:13:16', 'Sau khi ở bên Trần Tích Xuyên, anh ấy luôn rất hào phóng.
Tôi chia sẻ cuộc sống với anh ấy, anh ấy lập tức \"nổ tiền\" cho tôi.
Bạn bè đều nói anh ấy yêu tôi tha thiết, dù sao thì tiền ở đâu, tình yêu ở đó mà.
Cho đến khi tôi vô tình nghe thấy cuộc trò chuyện giữa anh ấy và đám anh em.
\"Anh Xuyên, chưa làm Tán Tài Đồng Tử đủ à? Giờ cô ta chưa biết thân phận của anh mà đã đào mỏ thế rồi, sau này chắc chắn còn quá đáng hơn.\"
Trần Tích Xuyên cúi đầu nhìn điện thoại, giọng điệu rất thản nhiên: \"Dám làm loạn thì chia tay.\"
Tôi không muốn chia tay thế nên lần tiếp theo anh ấy chuyển tiền cho tôi, tôi run rẩy bấm vào nút \"Hoàn trả\".
Phòng bao lập tức nổ tung.
\"Đ.ệ.t, cô ấy không nhận tiền của tôi nữa, tôi không muốn sống nữa rồi!\"
Tôi: ?
Đám anh em của anh ấy: ???', 'VỪA CHIA TAY BẠN TRAI GIÀU, TÔI LẠI ĐƯỢC CẬU EM TRAI NGHÈO CƯNG CHIỀU', '', 'publish', 'open', 'closed', '', 'vua-chia-tay-ban-trai-giau-toi-lai-duoc-cau-em-trai-ngheo-cung-chieu', '', '', '2026-10-19 23:27:47', '2026-10-19 16:27:47', '', '0', 'https://muopdangcungcovingot.io.vn/vua-chia-tay-ban-trai-giau-toi-lai-duoc-cau-em-trai-ngheo-cung-chieu/', '0', 'truyen', '', '0'),
('130', '1', '2026-10-16 21:13:16', '2026-10-16 14:13:16', '', '8e612af0-ac62-461e-993f-7fdab32ada67', '', 'inherit', 'open', 'closed', '', '8e612af0-ac62-461e-993f-7fdab32ada67', '', '', '2026-10-16 21:13:16', '2026-10-16 14:13:16', '', '129', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/8e612af0-ac62-461e-993f-7fdab32ada67.png', '0', 'attachment', 'image/png', '0'),
('131', '1', '2026-10-16 21:20:09', '2026-10-16 14:20:09', 'Sau khi ở bên Trần Tích Xuyên, anh ấy luôn rất hào phóng.

Tôi chia sẻ cuộc sống với anh ấy, anh ấy lập tức \"nổ tiền\" cho tôi.

Bạn bè đều nói anh ấy yêu tôi tha thiết, dù sao thì tiền ở đâu, tình yêu ở đó mà.

Cho đến khi tôi vô tình nghe thấy cuộc trò chuyện giữa anh ấy và đám anh em.

\"Anh Xuyên, chưa làm Tán Tài Đồng Tử đủ à?

Giờ cô ta chưa biết thân phận của anh mà đã đào mỏ thế rồi, sau này chắc chắn còn quá đáng hơn.\"

Trần Tích Xuyên cúi đầu nhìn điện thoại, giọng điệu rất thản nhiên: \"Dám làm loạn thì chia tay.\"

Tôi không muốn chia tay thế nên lần tiếp theo anh ấy chuyển tiền cho tôi, tôi run rẩy bấm vào nút \"Hoàn trả\".

Phòng bao lập tức nổ tung.

\"Đệt, cô ấy không nhận tiền của tôi nữa, tôi không muốn sống nữa rồi!\"

Tôi: ?

Đám anh em của anh ấy: ???

…

Cô bạn thân nói trên người tôi có năng lực tâm linh, quen anh người yêu nào cũng đều tự nguyện chi tiền cho tôi.

Cô ấy vừa thất tình, mặt nhăn nhó như xơ mướp khóc lóc than ngắn thở dài: \"Tớ ăn một miếng kem của anh ta, anh ta bắt tớ chia đôi tiền, cho đến cả 5 tệ tiền khăn giấy ở nhà hàng, tớ dùng nhiều hơn hai tờ, anh ta cũng có da mặt bảo tớ là đồ đào mỏ.\"

\"Cậu biết điều quá đáng nhất là gì không?

Anh ta đi cắt tóc trước khi gặp tớ mà cũng tính lên đầu tớ, cái mái đó dài đến tận mang tai rồi, cos Sadako cũng không hề giả tí nào.

Tớ còn chưa tính với anh ta một lần tớ trang điểm tốn bao nhiêu tiền đấy!\"

\"Hức hức hức, người mang ra ngoài giới thiệu được thì gọi là người yêu cũ, người không mang ra ngoài được thì khác quái gì tiền án tiền sự đâu!\"

Tôi luống cuống vụng về dỗ dành cô ấy, nửa ngày mới nặn ra được một câu: \"Người tiếp theo sẽ tốt hơn thôi.\"

Bạc Nghi thút thút thít thít rúc vào người tôi: \"Tiểu Ngữ, khi nào tớ mới được may mắn như cậu đây.\"

\"Cho tớ cọ cọ tí nào, xin vía may mắn.\"

Mùi nước hoa dễ chịu trên người cô ấy vẩn vương nơi đầu mũi.

Tôi không quen dính sát vào người khác như vậy, ngại đến mức má đỏ bừng, suy nghĩ dần bay xa.

Lịch sử tình trường của tôi rất đơn giản, đến nay mới chỉ quen tổng cộng hai người bạn trai.

Người đầu tiên là tình yêu vườn trường, một cậu ấm đại thiếu gia nổi tiếng trong lớp, gia cảnh giàu có, chẳng hiểu sao lại đột nhiên tỏ tình với tôi.

Tôi bị vây ở giữa đám đông, ngẩng đầu lên liền bắt gặp ánh mắt nóng rực của anh.

Suy đi tính lại, tôi nghĩ chắc là hình phạt của trò Thật hay Thách, sợ người ta xấu hổ nên tôi đã đồng ý thế nhưng không ngờ anh lại nghiêm túc thật.

Một mối tình giống như bị xông nhà cướp đoạt kéo dài cho đến khi tốt nghiệp đại học.

Gia đình bắt anh xuất ngoại du học, anh bắt đầu liên tục ám chỉ không muốn yêu xa, còn khoe khoang bản thân có bao nhiêu tiền.

Tôi mặc định đây là cách nói chia tay khéo léo.

Hôm đó, tôi để lại một câu: \"Chúc anh tiền đồ xán lạn.\"

Sau đó kéo đen toàn bộ phương thức liên lạc của anh.

Vốn tưởng rằng chuyện đến đây là kết thúc nhưng không ngờ mẹ anh lại tìm đến tận cửa, đưa tấm séc 5 triệu tệ bảo tôi rời xa con trai bà.

Hai mẹ con nhà này giống hệt nhau, đứa con thì tôi không nhận tiền của anh, anh bảo tôi không yêu anh.

Bà mẹ thì tôi không nhận tiền của bà, bà lại nghi thần nghi quỷ tưởng tôi muốn nối lại tình xưa, giá tăng từ 5 triệu lên 20 triệu.

Bất lực, tôi đành phải nhận lấy tấm séc dưới ánh mắt van nài của bà, một đêm trở thành phú bà.

Rút kinh nghiệm từ người trước, người bạn trai thứ hai, tôi tìm một nhân viên công sở bình thường.

Điện thoại rung lên điên cuồng, tôi đỡ vững cô bạn thân đang say khướt, một tay mở khóa.

Trong ứng dụng trò chuyện im lìm, chỉ có sau tên của Trần Tích Xuyên là kiên trì nhảy thông báo 99+.

Anh ấy thực sự rất quấn người.

Nghĩ vậy, trên mặt tôi không tự chủ được mà nở nụ cười.

Ở phía trên cùng là mấy viên xúc xắc tôi tùy tay quăng ra.

5421.

Tiếp theo là video quay tay báo cáo lịch trình mà Trần Tích Xuyên gửi qua cùng với một khoản tiền chuyển khoản nhiều hơn bình thường một số 0.

Thấy tôi mãi không bấm nhận, anh ấy bắt đầu cuống lên:

[Sao không nhận?

Anh lại làm sai điều gì sao?]

[Rượu trên bàn không phải do anh uống đâu, chỉ có người đàn ông không có gia đình mới dùng cồn để làm tê liệt bản thân thôi.

Anh không giống mấy con chó đi lang thang đó.]

[Anh có thể về nhà sớm chút không?

Mười giờ rưỡi sao còn lâu thế, thực ra anh với họ chỉ gặp nhau vài lần hồi nhỏ thôi, không tính là bạn thanh mai trúc mã đâu.

Em yêu, anh không cần xã giao với bạn bè đâu.]

Đến phía sau, giọng điệu của anh ấy càng lúc càng chua chát:

[Chẳng lẽ là có ai đang giữ tay em lại rồi sao?

Là do anh không biết điều rồi.]

Cách trút giận của anh ấy rất đơn giản, đó là điên cuồng chuyển tiền cho tôi.

Ngay cả việc báo cáo lịch trình cũng là do anh ấy chủ động đề xuất trước.

Lúc đó lý do Trần Tích Xuyên đưa ra cho tôi là: Cùng nhau đi ăn cơm, đồng nghiệp đều quay đủ loại video báo cáo rồi khoe khoang với anh ấy, chỉ có anh ấy là giống như một kẻ đáng thương không ai quan tâm.

Lúc nói câu này, đáy mắt anh ấy đầy vẻ cô đơn, giọng điệu vỡ vụn, hỏi tôi có phải chỉ muốn chơi đùa với anh ấy, không muốn chịu trách nhiệm hay không.

Tôi vốn mềm lòng nên đã gật đầu đồng ý, chỉ là xúc xắc thì chỉ quăng 4 viên thôi.

Thấy Trần Tích Xuyên hừ hừ hự hự còn muốn làm nũng, ánh mắt tôi đầy vẻ thương xót: \"Anh kiếm tiền không dễ dàng gì, cũng phải giữ lại cho bản thân một ít, đừng gượng ép.\"

Vẻ mặt Trần Tích Xuyên khựng lại, úp úp mở mở nói hai câu, bảo con người của sếp tốt lắm, lúc nào cũng thưởng tiền.

Tôi cảm thán: \"Sếp của anh đúng là người tốt thật.\"

Vẻ mặt Trần Tích Xuyên lại càng khó coi hơn.

Sợ Trần Tích Xuyên thật sự bỏ bạn bè lại để về nhà, tôi gian nan dùng một tay nhắn lại:

[Bạn thân em thất tình, em vừa mới dỗ cậu ấy.]

Nhớ lại lần trước đến công ty Trần Tích Xuyên đưa cơm, dáng vẻ đồng nghiệp của anh ấy né tránh anh ấy như né tà.

Ánh mắt họ nhìn anh ấy lóe lên sự kiêng kỵ, kính sợ.

Tôi cùng Trần Tích Xuyên đi thang máy nhân viên, họ thậm chí còn ùn ùn chạy ra ngoài.

\"Trần...

Thư ký Trần, hai người đi trước, hai người đi trước ạ.\"

Lúc thang máy sắp đóng lại, tôi thậm chí còn nghe thấy họ bàn tán sau lưng Trần Tích Xuyên: \"Phù, Trần...

cũng đáng sợ quá rồi, lần sau thấy anh ta nhất định phải đi vòng đường khác.\"

Bảo sao cứ đến giờ tan làm là Trần Tích Xuyên lại nôn nóng muốn về nhà, hóa ra là vì bị bắt nạt nơi công sở nghiêm trọng đến thế.

Lo lắng anh ấy từ đó mà tự kỉ nên khi nghe nói bạn bè rủ anh ấy ra ngoài ăn uống, tôi hoàn toàn ủng hộ, còn dặn dò anh ấy chơi muộn một chút cũng không sao.

Cuối cùng, dưới sự mặc cả của Trần Tích Xuyên, giờ về mới đổi từ 12 giờ thành 10 giờ rưỡi.

Trần Tích Xuyên trả lời ngay lập tức: [Vất vả cho em yêu rồi, em với bạn thân đi ăn chút gì ngon ngon đi.]

Nói xong, lại chuyển thêm 5 khoản 1314 tệ, đều ghi chú là tự nguyện tặng cho.

Tôi không vội nhận mà đi thanh toán ở quầy bar.

Bạc Nghi gọi 10 trai đẹp phục vụ, lại khui thêm mấy tháp sâm panh.

Sau khi đối chiếu hóa đơn, tôi quét mã thanh toán, còn chưa kịp xem là thẻ nào, nhận diện khuôn mặt đã trực tiếp thanh toán xong.

Đúng lúc này, Bạc Nghi ho hai tiếng, yếu ớt lên tiếng: \"Tớ muốn nôn.\"

Da đầu tôi tê dại, làm gì còn tâm trí đâu mà liếc nhìn màn hình, chỉ biết là mình dùng thẻ phụ.

Sắp xếp cho Bạc Nghi xong thì đã gần 10 giờ.

Tôi vội vàng tắm rửa một cái, xác nhận trên người mình ngoài mùi sữa tắm ra thì không còn mùi nào khác mới thở phào một hơi.

Đang tính vui vẻ xem chút \"thực phẩm tinh thần\" thì nhận được một tin nhắn.

[Chị dâu, anh Xuyên say rồi, chị đến đón anh ấy một chuyến đi.]

Bên dưới là thông tin địa chỉ.

Ban đầu tôi còn hơi nghi ngờ nhưng khi thấy địa chỉ trùng khớp hoàn toàn với vị trí Trần Tích Xuyên gửi cho tôi, sự nghi ngờ lập tức biến mất.

Tôi vội vàng thay quần áo, trên đường đi đặt một phần canh giải rượu.

Đồ ăn ngoài và tôi đến cùng một lúc.

Tôi sờ thử, vẫn còn ấm, ôm bát canh nhỏ, tôi cẩn thận tìm phòng bao.

A888, chính là phòng này!

Cửa phòng khép hờ một khe nhỏ, tôi đang định đẩy cửa vào thì nghe thấy tiếng anh em của Trần Tích Xuyên nói chuyện: \"Anh Xuyên, anh định khi nào thì chia tay thế, cũng sắp được một năm rồi, anh không định nghiêm túc yêu cô ta tiếp đấy chứ?\"

\"Em vừa thấy anh chuyển tiền kìa, nhiều khoản 1314 thế, chậc chậc chậc, đến lúc đó là không đòi lại được đâu đấy.\"

\"Anh Nhiếp sắp về nước rồi, anh Xuyên, anh phải nhanh tay lên chút, đến lúc đó bị anh ấy biết anh chơi bạn gái cũ của anh ấy, dù là để trút giận giùm anh ấy nhưng vẫn hơi ảnh hưởng đến tình cảm anh em.\"

Giọng Trần Tích Xuyên đầy khó chịu: \"Tôi có nhịp điệu riêng của tôi, cần các cậu nhiều lời à?\"

Tiếp đó giọng anh ấy trầm xuống: \"Nói lại thì, cô ấy cũng có lấy tiền của tôi đâu.\"

Mấy người anh em không thể tin nổi: \"Anh không đùa đấy chứ?

Đào mỏ mà còn bắt chuẩn mục tiêu nữa cơ à?

Anh Xuyên, anh nói thật với anh em đi, anh chuyển cho cô ta bao nhiêu mà cô ta không lấy?\"

Trần Tích Xuyên thong thả xoay xoay điện thoại nhẩm tính: \"Mới chưa đến một triệu tệ thôi, chuyển thì cô ấy cũng không tiêu.

Tôi mua cho cô ấy mấy cái túi vài triệu nhưng cô ấy lại tưởng là hàng nhái A nên không dám đeo, tôi mua cho cô ấy đồ cao cấp không dán nhãn hiệu, cô ấy lại ngốc nghếch mặc đến mức thích mê thích mệt.\"

Nói đến cuối cùng, giọng điệu anh ấy trầm xuống, có chút dịu dàng: \"Đồ nhà quê nhỏ.\"

Trong phòng bao trong chốc lát như bị nghẹt thở.

Trong khoảng im lặng chết chóc, có người khó khăn mở lời: \"Anh Xuyên?

Thế này mà còn không tính là đào mỏ sao?\"

\"Anh tặng cô ta một đống đồ thật như thế, đến lúc chia tay không dễ đòi lại đâu.\"

Giọng Trần Tích Xuyên đầy vẻ chê bai, khó chịu: \"Mấy người có thể đừng mất giá thế được không?

Mấy người là kẻ nghèo kiết xác à?

Có chút tiền như thế tặng đi rồi còn muốn đòi lại?\"

Những người khác không cho là thật, cười thành một đám: \"Mấy người thì hiểu cái gì?

Anh Xuyên đây là chưa làm Tán Tài Đồng Tử đủ đấy thôi.

Nhưng mà anh Xuyên à, anh cũng phải cẩn thận đấy, giờ cô ta chưa biết thân phận của anh mà đã đào mỏ thế rồi, sau này chắc chắn còn quá đáng hơn.\"

Trần Tích Xuyên cúi đầu nhìn điện thoại, giọng điệu thản nhiên: \"Dám làm loạn thì chia tay.\"

\"Chia tay còn là chuyện nhỏ đấy, anh Xuyên, em đoán cô ta đang thả dây dài câu cá lớn đấy, đến lúc đó lại khóc lóc bám lấy đòi kết hôn với anh cho xem.\"

Trong phòng bao tràn ngập tiếng cười nói vui vẻ.

Bên ngoài phòng bao, tôi siết chặt lòng bàn tay, mắt cay xè.

Tôi vốn không muốn chia tay.

Dù Trần Tích Xuyên chỉ là giả vờ nhưng ở bên anh ấy quả thực rất vui vẻ.

Nghĩ đến đây, tôi mở khung chat của anh ấy ra, từ đầu đến cuối, bấm \"Hoàn trả\" cho từng khoản chuyển khoản một.

[Sau này đừng chuyển tiền cho em nữa, em chỉ muốn đàng hoàng yêu đương với anh thôi.]

Làm xong tất cả những điều này, tôi thở phào một hơi, đang định âm thầm rời đi thì nghe thấy từ trong phòng bao truyền ra tiếng khóc mất khống chế của Trần Tích Xuyên: \"Đệt, cô ấy không nhận tiền của tôi nữa rồi, tôi không muốn sống nữa đâu!\"

Tôi: ?

Chẳng lẽ Trần Tích Xuyên chỉ là ngoài miệng cứng cỏi?

Bước chân tôi khựng lại, nhen nhóm lên vài phần hy vọng nhưng rồi lại nhanh chóng bị dập tắt.

\"Mẹ nó chứ, đây là nhắm cắn chặt miếng thịt thiên nga này của anh rồi dấy anh Xuyên ơi!

Lấy lùi làm tiến, thân phận thiếu phu nhân nhà họ Trần giá trị hơn mấy khoản chuyển khoản này nhiều!\"

Quả nhiên đám người giàu các người ai cũng như vậy, tôi không lấy tiền thì họ lại nghĩ tôi muốn nhiều hơn.', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-6', '', '', '2026-10-16 21:20:09', '2026-10-16 14:20:09', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-1-6.html', '0', 'chuong', '', '0'),
('132', '1', '2026-10-16 21:21:30', '2026-10-16 14:21:30', 'Tôi ủ rũ cúi đầu, theo bản năng siết chặt điện thoại, còn chẳng chú ý là tay trượt bấm \"thích\" vào bức ảnh cơ bụng của Nhiếp Hàn Sơn.

Tin nhắn của anh nhanh chóng nảy ra: [?]

Tôi thở dài: [Tôi thất tình rồi.]

Nhiếp Hàn Sơn gửi lại một đoạn ghi âm, có lẽ là vừa mới tập thể dục xong, giọng còn hơi thở dốc: [Thất tình lại đi nhắn tin cho bạn trai cũ, Lê Hoài Ngữ, em thèm ăn đòn đấy à?]

Làm gì mà hung dữ thế?

Hơn nữa chẳng phải chính anh bảo sau này thất tình nhất định phải nói cho anh biết sao?

Tôi sụt sịt mũi: [Xin lỗi nhé, không ai dạy tôi là chia tay rồi thì không được làm bạn cả.]

[Tôi kéo đen anh nhé.]

Nhiếp Hàn Sơn gần như trả lời ngay lập tức: [Em dám!]

Tôi bấm nhận cuộc gọi thoại.

\"Em đang ở đâu?\" Bên phía Nhiếp Hàn Sơn truyền đến tiếng sột soạt.

Tôi uể uải đọc ra địa chỉ quán bar.

Anh cười lạnh một tiếng: \"Chơi tới bến nhỉ, lén lút ở sau lưng tôi đến mấy cái nơi này, nhìn qua là biết loại hàng thải không giữ nam đức rồi, rời xa tôi mà em lại quen cái thể loại này đấy à?\"

\"Lê Hoài Ngữ, mắt nhìn người của em kém thật đấy.\"

Thất tình rồi còn bị mắng cho xối xả một trận, tính mắng mỏ ngầm của tôi cũng trỗi dậy.

\"Không cần anh đến nữa.\"

\"Em bảo tôi đến là tôi đến, không bảo tôi đến là tôi không đến à?

Tôi việc gì phải nghe lời em, em đâu phải bạn gái tôi, còn đòi quản tôi chắc.\"

Trong điện thoại truyền đến tiếng xe thể thao khởi động, giọng điệu Nhiếp Hàn Sơn không thể chối cãi: \"Bớt nói nhảm đi, ngoan ngoãn đứng đó chờ tôi.\"

Tôi vốn dĩ là một người hiền lành không có chủ kiến.

Lúc chia tay với Nhiếp Hàn Sơn, chủ động kéo đen anh là tôi đã dùng hết sức bình sinh rồi.

Sau này anh xuất ngoại, dùng vài thủ đoạn để kết bạn lại với tôi.

Từ đó về sau, bắt đầu nhật ký điểm danh hàng ngày.

Ở bữa tiệc, chụp ảnh gửi cho tôi, tất cả mọi người tụ tập nhộn nhịp, chỉ có xung quanh anh là một khoảng chân không, nhìn qua có vẻ rất đáng thương.

[Lê Hoài Ngữ đều tại em cả, tất cả mọi người đều biết tôi là người đàn ông bị em chơi chán rồi bỏ, theo em bốn năm, sau này còn ai cần tôi nữa, cả đời tôi đều bị em hủy hoại rồi.]

Có đôi khi là nửa đêm bên đó, anh vẫn còn trong phòng tắm, chụp ảnh từ dưới lên cho tôi.

Trên tay cầm váy ngủ của tôi, chắc là dùng để lau chân trút giận.

Lúc đó chia tay vội vã, Nhiếp Hàn Sơn chuyển cho tôi một khoản tiền, bảo tôi không cần đến lấy đồ đạc nữa.

Anh nhìn thấy là buồn nôn, vứt hết rồi.

Bây giờ xem ra, hóa ra là bị anh mang ra nước ngoài.

Lúc đó, tôi đang bàn giao công việc cho thực tập sinh mới đến của bộ phận.

Lúc tắt đi không cẩn thận bấm nhầm, bức ảnh mây mưa nóng bỏng lập tức phóng to ngay trước mặt cậu ấy.

Cậu ấy suy ngẫm: \"Hóa ra, chị thích kiểu như thế này.\"

Mặc dù sau đó tôi giải thích là gửi nhầm, cậu ấy cũng chỉ cười với ý tứ sâu xa.

Không nói là tin hay không tin, chỉ là lúc đến hỏi tôi bài học.

Tôi hơi nghiêng mặt là có thể nhìn ngắm toàn bộ phong cảnh từ cổ áo rộng thùng thùng của cậu ấy.

Cứ như vậy, ngày qua ngày, Nhiếp Hàn Sơn đều bảo tôi nợ anh.

Nói nhiều rồi, người hiền lành như tôi cũng tin luôn.

Bây giờ anh ấy phán lệnh rồi, tôi cũng ngơ ngác mặc định đồng ý.

Nhiếp Hàn Sơn đến rất nhanh.

Vốn dĩ tôi tính sẵn là sẽ ngồi ghế sau lại phát hiện anh lái xe thể thao, đành phải ngoan ngoãn chui vào ghế phụ.

Đã lâu lắm rồi chúng tôi không gặp mặt trực tiếp như thế này.

Hôm Nhiếp Hàn Sơn về nước có nhắc bảo tôi đi đón anh, tôi đắn đo nửa ngày thấy không tốt bèn ngập ngừng lên tiếng: \"Tôi đã có bạn trai rồi, tôi báo cáo với anh ấy một tiếng được không?\"

Nhiếp Hàn Sơn tức đến giận cười, về sau không bao giờ nhắc lại chuyện đó nữa.

Chỉ là ảnh cơ bụng trên vòng bạn bè càng ngày càng cập nhật thường xuyên hơn.

Vốn tưởng lâu như vậy không gặp, anh chắc sẽ thay đổi nhiều lắm lại phát hiện anh vẫn giống như hồi còn đi học.

Áo hoodie, tóc hơi xoăn rủ xuống trán.

Thấy chiếc váy ngắn mỏng manh trên người tôi, chân mày anh nhíu chặt: \"Thế nào?

Mới thất tình thôi mà, đến mức đau lòng tới tự đóng băng mình à?

Xem phim ngắn nhiều quá rồi đấy à?

Bao nhiêu năm rồi mà chẳng tiến bộ tí nào.\"

Tôi bĩu môi lại không dám bật lại.

Hồi đó hạ quyết tâm chia tay Nhiếp Hàn Sơn, một nguyên nhân lớn là do cái miệng anh độc địa thật.

Có lẽ là do chưa từng được yêu thương đàng hoàng, tôi thích được người ta cẩn thận dỗ dành, tốt nhất là ngày nào cũng gọi \"bé yêu, em yêu\".

Mà Nhiếp Hàn Sơn chỉ vào những lúc nhất định mới gọi tôi như vậy, giọng điệu giễu cợt: \"Bé yêu, nâng cao lên chút, em làm được mà.\"

Thấy dáng vẻ hờn dỗi không thèm để ý của tôi, Nhiếp Hàn Sơn \"chậc\" một tiếng: \"Khá lắm, em còn thấy tủi thân cơ đấy.\" Nói rồi vươn tay qua nắm lấy tay tôi.

Tôi giật bắn mình, theo bản năng muốn giãy giụa: \"Anh buông tôi ra, bạn...

bạn bè không làm thế này.\"

Nhiếp Hàn Sơn nhận ra điểm bất thường: \"Em chia tay rồi, còn tránh hiềm nghi giùm ai đấy?\"

Thấy tôi chột dạ không lên tiếng, anh nheo mắt lại: \"Hay là nói, Lê Hoài Ngữ, em chưa chia tay dứt khoát?\"

Giọng tôi nhỏ như muỗi kêu: \"Chưa chia.\"

Bạn bè của tôi quả thực rất ít.

Bạc Nghi uống say, lại vừa thất tình, tôi không nỡ làm phiền cô ấy nữa.

Suy đi tính lại mới nghĩ đến Nhiếp Hàn Sơn.

Dù sao lúc chia tay, anh đã bảo đời này sẽ không bao giờ quay lại với kẻ đã đá anh, kết bạn lại chỉ là vì không muốn mất đi một người bạn.

Anh nói thế, tôi liền tin thế.

Lúc tìm anh tôi đắn đo rất lâu, không cẩn thận bấm vào vòng bạn bè.

Để lại sơ hở, giờ hối hận cũng không kịp.

Nhiếp Hàn Sơn tức cười, nắm tay tôi càng thêm lực: \"Thế tôi tính là cái gì?

Kẻ thứ ba không thấy được ánh sáng à?\"

Từ này nặng quá, người hiền lành nghe không nổi.

Tôi hoảng hốt ngẩng đầu, ước gì có thể bịt miệng anh lại: \"Bạn bè, anh là bạn bè của tôi mà.\"

\"Anh đừng nói thế, bị mẹ anh biết là tôi phải trả lại tiền đấy.\"

Nhìn dáng vẻ ngó trước ngó sau cẩn thận từng chút một của tôi, sắc mặt Nhiếp Hàn Sơn càng thêm thối.

Anh dứt khoát nắm lấy tay tôi nhét vào trong áo hoodie.

Đầu ngón tay lạnh giá chạm vào cơ bụng ấm áp, lạnh đến mức khiến anh hít vào một hơi nhẹ.

Chưa đợi tôi giãy giụa, anh đã đánh phủ đầu trước: \"Bạn bè sưởi ấm tay cho nhau em cũng muốn từ chối à?\"

Thấy tôi còn đang phân vân, anh cười khẩy một tiếng: \"Hay là em lại đang tự đa tình, tưởng tôi đang ám chỉ cái gì?\"

\"Đừng tấu hài có được không?

Tôi chỉ sợ sưởi tay cho em làm ảnh hưởng đến việc lái xe thôi, tiện tay thôi mà, quả nhiên gần mực thì đen, yêu đương với trai hoang não cũng hỏng luôn rồi.\"

Tôi trong chốc lát bị xoay mòng mòng, cuối cùng quy kết thành có lẽ ở nước ngoài đều như thế cả, Nhiếp Hàn Sơn lại là người tốt.

Đang áy náy muốn nói lời xin lỗi thì nghe thấy một giọng nói quen thuộc vang lên phía sau: \"Anh Nhiếp?\"

Trong chốc lát sống lưng cứng đờ, tôi rúc đầu vào lồng ngực Nhiếp Hàn Sơn.

Nhiếp Hàn Sơn ngẩn ra trước hành động đột ngột của tôi nhưng rất nhanh đáy mắt tối sầm lại, nhìn chằm chằm đám người ngoài cửa như đang dò xét điều gì.

Khó khăn lắm mới xa nhau lâu ngày gặp lại, lại bị nhìn chằm chằm một cách u ám như vậy, đám người ngơ ngác không hiểu chuyện gì.

Nhiếp Hàn Sơn lên tiếng trước, như tiện miệng hỏi một câu: \"Sao giải tán sớm thế, đây đâu giống tính cách của mấy chú.\"

\"Haiz, còn chẳng phải tại anh Xuyên sao, đòi về nhà với vợ.\"

Nhiếp Hàn Sơn nhướn mày, nhìn về phía Trần Tích Xuyên đang nhíu mày nhìn điện thoại ở bên cạnh: \"Ồ?

Tích Xuyên yêu đương từ khi nào thế, sao không nói với tôi?\"

Có kẻ nhanh miệng đáp lại: \"Cho anh biết thì tiêu đời à.\"

Mặc dù rất nhanh đã nhận ra điều gì đó mà ngậm miệng lại nhưng vẫn bị Nhiếp Hàn Sơn bắt trọn.

\"Sao thế?

Là người tôi quen à?\" Giọng anh mang theo chút ý cười nhưng động tác ôm lấy tôi lại siết chặt.

Tôi hơi đau, không dám cất tiếng, chỉ đành cẩn thận chọc chọc vào eo anh.

Hành động không kịp phòng bị khiến Nhiếp Hàn Sơn hoàn toàn không có chuẩn bị, hừ nhẹ một tiếng.

Âm thanh đột ngột thu hút sự chú ý của tất cả mọi người.

Bầu không khí lập tức trở nên thả lỏng, có người mờ ám huýt sáo một cái: \"Anh Nhiếp, có phải chúng em quấy rầy anh rồi không?\"

Nhiếp Hàn Sơn lười biếng tựa vào lưng ghế, cũng không phủ nhận, chỉ là xoa xoa mái tóc mềm mại của tôi: \"Hơi xấu hổ, đang hờn dỗi với tôi rồi đây này.\"

Đây là đang đuổi người rồi, mọi người nhìn nhau, biết ý cáo từ.

Ngoại trừ Trần Tích Xuyên, ánh mắt nóng rực như gai đâm sau lưng, nhìn chằm chằm vào nốt ruồi nhỏ sau cổ tôi, đột nhiên cất lời mờ ám: \"Anh Nhiếp vẫn chưa buông xuống được à?

Tôi thấy cô này trông hơi giống người từng khiến anh sống dở chết dở trước đây đấy, thế thân à?\"

Lời nói đầy gai nhọn, trong chốc lát, tất cả mọi người đều hít vào một ngụm khí lạnh.

Nhiếp Hàn Sơn cũng sa sầm mặt xuống: \"Tôi lại không biết từ khi nào cậu lại quen thuộc với bạn gái tôi đến mức mới nhìn cái bóng lưng đã dám ăn nói ngông cuồng, đoán bậy đoán bạ đấy.\"

Có người đứng ra hòa giải: \"Anh Xuyên chỉ là trí nhớ tốt thôi, trí nhớ tốt ấy mà, anh Nhiếp tụi em đi trước nhé.\"

Trần Tích Xuyên bị ấn vào ghế sau, hai bên bị kẹp ở giữa phòng thủ nghiêm ngặt, người bạn lúc này mới thở phào một hơi: \"Anh Xuyên, anh Nhiếp cũng đã bước ra rồi, tôi thấy cậu với cô kia cũng sớm cắt đứt cho sạch sẽ đi, đỡ ảnh hưởng tình cảm anh em.\"

\"Đúng đấy anh Xuyên, anh Nhiếp là người trong cuộc còn chẳng để ý nữa là, cậu cũng không cần phải tiếp tục tự làm khổ mình đi trả thù cô ta đâu, vốn dĩ đã không cùng một thế giới rồi, đừng để cô ta ăn được thịt thiên nga thật.\"

Anh ta cười gượng vài tiếng nhưng mãi không nhận được lời hồi đáp.

Sắc mặt Trần Tích Xuyên khó coi đến đáng sợ, trên màn hình điện thoại toàn là tin nhắn anh ấy đơn phương gửi đi.

Điều quan trọng nhất là, lúc anh ấy gửi tin nhắn đi, chiếc điện thoại rõ ràng nhỏ hơn một cỡ trong tay Nhiếp Hàn Sơn đồng thời rung lên một cái.

Suốt chặng đường Nhiếp Hàn Sơn đều rất im lặng.

Trong lòng tôi như đánh trống.

Xe dừng lại, tôi đang định lên tiếng giải thích việc mình và Trần Tích Xuyên yêu đương khi không hề biết mối quan hệ anh em giữa họ thì bị Nhiếp Hàn Sơn phán một câu chặn họng.

Giọng điệu anh ấy khẳng định: \"Lê Hoài Ngữ, quả nhiên em vẫn còn tình cảm với tôi.\"

\"Cái gì?\"

\"Em cố tình yêu đương với anh em của tôi chẳng qua là muốn trả thù tôi đúng không.\"

Đầu óc tôi trống rỗng: \"Tôi không có, anh hiểu lầm rồi.\"

\"Đừng giả vờ nữa, em tưởng tôi không biết sao?

Lúc nãy tay em ấn trên cơ bụng tôi, tay còn dịch xuống dưới hai xăng-ti-mét, còn nhẹ nhàng véo một cái nữa.\"

\"Sao thế?

Đang nghiệm hàng à?\"

Đó là vì phanh gấp, tôi bị giật mình thôi mà.

Hơn nữa, là chính anh ấy chủ động nhét tay tôi vào, bảo bạn bè làm thế là bình thường, sao bây giờ lại đi vu khống người hiền lành thế cơ chứ.

Tôi cuống đến đỏ cả mặt, đang định giải thích thì bị Nhiếp Hàn Sơn một câu chặn đứng: \"Không cãi được đúng không?\"

Anh phân tích một cách có đầu có đuôi: \"Em đúng là người phụ nữ xấu xa, sợ tôi không đồng ý quay lại nên dùng anh em tôi làm tấm lá chắn, thực ra là để tiếp cận tôi dễ dàng hơn.\"

\"Biết tôi trọng tình trọng nghĩa, dù là vì hạnh phúc của cậu ta thì cũng buộc phải giảng hòa với em.\"

\"Em thắng rồi Lê Hoài Ngữ, thà đau ngắn còn hơn đau dài, giờ em gọi điện cho cậu ta chia tay đi, tôi sẽ đồng ý với em.\"', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-6', '', '', '2026-10-16 21:21:30', '2026-10-16 14:21:30', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-2-6.html', '0', 'chuong', '', '0'),
('133', '1', '2026-10-16 21:22:15', '2026-10-16 14:22:15', 'Một chuỗi câu thoại đập tới, tôi hoàn toàn không kịp phản ứng.

Choáng váng cả đầu, chỉ hy vọng có thể nhanh chóng kết thúc tất cả chuyện này, ngơ ngác đáp: \"Chia...

chia tay mà, anh đừng đồng ý với tôi.\"

Vốn dĩ vẫn còn hơi tiếc nuối nhưng tôi chỉ là một người hiền lành muốn sống những ngày tháng bình yên, lỗ tai lại mềm mà.

Tôi nhíu mày, đắn đo gõ chữ.

[Anh ấy bảo em chia tay với anh, em thấy anh ấy nói có lý.]

Gửi xong, nhút nhát nhưng vô cùng thuần thục kéo đen người ta.

Nhiếp Hàn Sơn nhìn thấy cảnh này, thầm tự giễu: \"Cũng chỉ đến thế mà thôi.\"

Nhưng sực nhớ ra câu nói cuối cùng của vợ, muốn cười mà cười không nổi.

Đệt, vợ cũng đâu có cần anh đâu.

Khó khăn lắm mới thoát khỏi một Nhiếp Hàn Sơn hừng hực ép người.

Đầu óc tôi thả trống, luôn cảm thấy mình đã quên mất chuyện gì đó.

Mở cửa ra, nhìn thấy Giang Quát quấn khăn tắm ngang hông, tôi mới nhận ra căn nhà tôi dùng để trốn Trần Tích Xuyên này đã sớm được tôi cho thuê lại rồi.

\"Chị!\" Giang Quát như bất ngờ mừng rỡ, đột ngột tiến lại gần tôi.

Khoảng cách thu hẹp, nửa thân trên Giang Quát không mặc đồ, vì chênh lệch chiều cao nên sóng ngực dạt dào, giống như muốn đem...

đút cho tôi ăn vậy.

Tôi giật mình lùi lại liên tục.

Giang Quát như hoàn toàn không nhận ra có gì không đúng, tủi thân nhìn tôi: \"Chị chẳng phải nói coi em như em trai sao?

Sao lại né tránh em, chỉ vì em từng tỏ tình với chị, nên đến cả việc tiếp cận chị mà em cũng không xứng sao?\"

Tháng trước Giang Quát tỏ tình với tôi, tôi hoàn toàn không ngờ cậu ấy lại thích tôi, vội vàng giải thích là mình đã có bạn trai rồi.

Hơn nữa, tôi chỉ coi cậu ấy như em trai, không có ý định phát triển tình cảm chốn công sở.

Nhưng không ngờ ngay ngày tôi từ chối cậu ấy, Giang Quát lập tức bị sa thải.

Công ty vốn dĩ có đãi ngộ tốt đột nhiên cắt giảm nhân sự mà lại xui xẻo cắt trúng ngay sinh viên mới ra trường như Giang Quát.

Nghĩ đến gia cảnh cậu ấy không tốt, quan hệ với bố mẹ lại căng thẳng, nên khi cậu ấy hỏi tôi có nơi nào thuê nhà không, tôi lỡ miệng nói mình có một căn hộ đang cho thuê.

Thế là Giang Quát chuyển vào căn hộ đơn này, di vật bố mẹ để lại cho tôi.

Tôi bị Giang Quát hỏi đến bối rối, vội vàng giải thích: \"Chị không có ý này, chỉ là không quen thế này thôi.\"

May mà Giang Quát dừng lại đúng lúc, nhường đường.

Vào nhà, nhìn thấy môi trường quen thuộc không hề bị thay đổi một chút nào, tôi nhận ra mình thở phào một hơi, ấp úng giải thích: \"Chị với bạn trai cãi nhau, có lẽ phải về đây ở một thời gian.\"

Nhận ra câu nói này giống như đang đuổi người một cách khéo léo, tôi há miệng muốn chữa cháy nhưng Giang Quát đã mở lời trước: \"Chị có chê em trải đệm ngủ dưới đất trong phòng không?

Hoặc em ra ngoài ở khách sạn cũng được ạ.\"

Tiền của cậu ấy sao đủ ở khách sạn chứ?

Nhớ lại lần vô tình nghe thấy cậu ấy nói chuyện điện thoại với bố mẹ: \"Bố mẹ yên tâm, con dù có ngủ ngoài đường cũng không bao giờ hối hận vì đã không lấy một đồng nào của bố mẹ đâu.\"

Tôi vội vàng nói: \"Được, nằm dưới đất là được rồi.\"

Hoàn toàn bị cậu ấy dắt mũi đi, rốt cuộc không nghĩ tới tại sao không thể trực tiếp ngủ ở ghế sofa.

Giang Quát mỉm cười nhìn tôi: \"Vẫn là chị thương em nhất.\"

Sau đó quấn khăn tắm cúi người xuống trải giường.

Động tác của cậu ấy có biên độ lớn, mấy lần tôi đều lo lắng khăn tắm sẽ bị tuột ra, may mắn thay, cho đến khi trải xong giường vẫn không có chuyện gì xảy ra.

Thấp thoáng có thể thấy Giang Quát cúi đầu nhìn một cái, sau đó tiếc nuối thở dài một hơi.

Chớp mắt, cậu ấy lại mỉm cười: \"Trễ lắm rồi, chúng ta nên ngủ thôi, chị.\"

Tắm rửa xong xuôi, nằm trên chiếc giường nhỏ quen thuộc, tôi lại chẳng có cảm giác buồn ngủ.

Căn hộ cách âm kém, thậm chí có thể nghe thấy tiếng bước chân đi lại ở tầng trên, chưa kể đến tiếng nói chuyện ở tầng dưới.

Nghĩ đến những lời tôi và Nhiếp Hàn Sơn nói lúc nãy, lòng tôi thắt lại.

Trước mặt Nhiếp Hàn Sơn tôi là bạn gái cũ, trước mặt Trần Tích Xuyên cũng sắp thành như thế, họ nắm rõ gốc rễ của tôi, hiểu rõ tôi là người thế nào.

Nhưng Giang Quát thì khác, ánh mắt cậu ấy nhìn tôi tràn đầy sự ỷ lại, có lẽ trong lòng cậu ấy tôi là hình mẫu ghê gớm lắm sao?

Hơn nữa cậu ấy còn gọi tôi là chị, đây là lần đầu tiên tôi được làm chị.

Cậu ấy mà thật sự hiểu nhầm tôi là loại người vì muốn trả thù bạn trai cũ mà ở bên anh em của người đó thì phải làm sao?

Tôi ướm thử mở lời hỏi Giang Quát: \"Giang Quát, em ngủ chưa?\"

\"Chưa ạ, sao thế chị?\"

Tôi lấy hết dũng khí: \"Mấy lời chị nói dưới nhà lúc nãy, có làm phiền em không?\"

Trong bóng tối, ánh mắt Giang Quát thâm trầm trong giây lát: \"Em nghe thấy...\"

Tôi nín thở, đầu óc xoay chuyển liên tục, hơi hối hận vì đã gợi lại chủ đề này thì nghe thấy cậu ấy bẻ lái: \"Đùa chị thôi, em chẳng nghe thấy gì cả, em tắm thích bật nhạc mà.\"

Tôi lập tức thở phào một hơi.

Không còn tâm sự, chìm vào chiếc giường quen thuộc, tôi nhanh chóng chìm vào giấc mộng.

Trong bóng tối, bàn tay buông thõng bên mép giường được nắm lấy, từng chút một cẩn thận thành kính hôn lên.

Giang Quát quỳ bên giường, thành kính như đang quỳ bái thần linh của chính mình.

\"Chị thật là yên tâm về em quá đấy, thế này không tốt đâu.\"

\"Một tin nhắn thôi mà cũng có thể lừa chị đi được...\"

\"Nhưng mà đã chia tay rồi, chị ngoan quá, tha thứ cho chị đấy.\"

Cậu ấy làm gì có thói quen vừa tắm vừa nghe nhạc.

Chỉ là lúc đó đang nghĩ đến chị nhưng làm thế nào cũng không tìm được manh mối.

Ông trời như nghe thấy lời cầu nguyện của cậu ấy, mang chị đến trước mặt cậu ấy.

Khoảnh khắc nghe thấy giọng nói của người yêu, trước mắt Giang Quát nổ tung một quầng pháo hoa lớn, vô cùng sảng khoái.

Được một giấc ngủ thẳng đến khi tự tỉnh, tôi ngửi thấy mùi thơm của thức ăn.

Dụi mắt bước ra ngoài, Giang Quát đang mặc chiếc tạp dề màu hồng hoàn toàn không hợp với cậu ấy, nửa thân trên không mặc gì, chỉ có sợi dây buộc mỏng dính thắt ngang eo, trông như một món quà đang chờ được bóc mở.

Người hiền lành làm gì đã từng thấy cảnh này bao giờ.

Thấy ánh mắt tôi đảo quanh, cậu ấy giải thích một chút: \"Vừa nãy tập thể dục ra mồ hôi làm ướt áo, nếu chị thấy phiền thì...\"

Người ta từ sáng sớm đã chuẩn bị cho mình một bàn thức ăn sáng lớn như vậy, tôi mà còn bảo phiền nữa thì thật quá đáng.

Thế là vội vàng lắc đầu, đánh trống lảng: \"Không sao, để chị xem có món gì ngon nào.\"

Vành tai Giang Quát ửng đỏ, như thể ngại ngùng: \"Tay nghề em không tốt lắm, chỉ làm chút mì Dương Xuân với bánh bao, quẩy các thứ thôi.\"

Tôi ngạc nhiên mở to mắt: \"Mấy cái này đều là em tự làm sao?\"

Trình độ nấu nướng của tôi chỉ ở mức bình thường, cùng lắm là nấu tô mì ăn liền bản sức khỏe, hai người bạn trai cũ quen trước đây càng khỏi phải nói.

Trước đây còn lấy làm lạ cùng là dân làm thuê vất vả như nhau sao Trần Tích Xuyên đến bật cái bếp thôi cũng luống cuống tay chân.

Bây giờ nghĩ lại, có thể làm anh em với Nhiếp Hàn Sơn thì chắc cũng là một cậu ấm thiếu gia, bảo sao lại mười ngón tay không dính nước mùa xuân.

Bữa sáng ngon lành chui vào bụng.

Tôi tự giác đứng dậy định đi rửa bát thì bị Giang Quát ấn ngồi trở lại.

Thấy tôi còn muốn từ chối, lông mi cậu ấy run run, giọng điệu tủi thân: \"Lúc trước chị cũng đâu có lấy nhiều tiền thuê nhà của em đúng không?\"

\"Em chẳng có gì cả, lấy thân báo đáp chị lại không cần, nếu ngay cả việc chăm sóc chị mà cũng không cho làm, em thật sự không biết phải báo đáp chị thế nào nữa.\"

Một tràng từ ngữ khiến tôi ngơ ngác cả người, luôn cảm thấy lời nói của cậu ấy có ẩn ý gì đó, có gì đó không đúng lắm.

Đến khi phản ứng lại thì trước mặt đã là một đĩa hoa quả rửa sạch sẽ, phim truyền hình cũng là bộ tôi đang cày dạo này, thậm chí còn chu đáo mở sẵn SVIP.

Tôi không kiềm được liếc nhìn tấm lưng của Giang Quát, đây chính là cảm giác của một người chồng đảm sao?

Ngày ba bữa được Giang Quát sắp xếp thỏa đáng, tôi chỉ việc làm một con sâu gạo thoải mái.

Ban đầu còn hơi ngại, muốn phụ giúp một tay.

Không ngờ mới rửa được một quả ớt, Giang Quát đã như đối mặt với kẻ thù lớn, nước mắt ngắn nước mắt dài, quỳ gục trước mặt tôi khóc lóc kể về sự lạnh lẽo của gia đình của mình.

\"Em biết, muốn ở nhà chăm vợ dạy con làm người đàn ông nhỏ trong thời đại này bị coi là khác thường, bố mẹ không hiểu em, muốn đoạn tuyệt quan hệ với em, em đều chấp nhận.\"

\"Nhưng mà, chị cũng nghĩ như vậy sao?\"

Tôi không hiểu nổi tại sao rửa một quả ớt lại có thể nâng tầm lên độ cao như thế này.

Cúi mắt, bắt gặp hàng mi dài đẫm nước mắt của cậu ấy và vùng cồng kềnh căng tròn đặc biệt nổi bật bên dưới chiếc tạp dề.

Mặt tôi nóng bừng, đầu óc không thể xoay chuyển được nữa, cuối cùng gian nan thốt ra một câu khô khốc: \"Đàn ông cũng có tự do lựa chọn trở thành người chồng hiền cha tốt mà.\"

Mắt Giang Quát trong chốc lát sáng rực lên, gục mặt vào bụng dưới của tôi, giọng nói nghẹn ngào: \"Em biết ngay là chị hiểu em mà.\"

Tối hôm đó, cậu ấy trèo lên giường tôi bảo là muốn bôi kem dưỡng thể massage cho tôi, trông như một thằng hầu nhỏ mẫn cán.

Những ngày tháng quá đỗi dễ chịu, thậm chí khiến tôi quên mất sự đau khổ khi thất tình.

Cô bạn thân nghe xong lời tôi nói thì vô cùng tán đồng.

\"Quả nhiên chỉ cần đàn ông không đứt đoạn thì cậu hoàn toàn không kịp đau lòng vì người trước.\"

Cô ấy mới tìm được một anh bạn trai cơ ngực khủng, bảo là có gì đau lòng cứ rúc vào một cái, lúc chui ra trên mặt chỉ còn biết cười thôi.

Thật sự hiệu quả thế sao?

Tôi không kiềm được nhìn Giang Quát đang bận rộn chuẩn bị bữa tối.

Phần trên tạp dề nhô lên đầy đặn, eo thắt lại gầy guộc, đường cong chênh lệch cực kỳ khoa trương.

Tôi nuốt nước bọt, rất muốn thử một lần.

Giang Quát đang gói sủi cảo, ngón tay cậu ấy linh hoạt, chỉ vài giây là một chiếc sủi cảo có kiểu dáng tinh tế đã thành hình.

Thấy ánh mắt khát khao của tôi, cậu ấy tưởng tôi muốn thử gói xem sao.

Ánh mắt đầy sự khuyến khích, rất...

rất rộng rãi hào phóng.

Tôi không chắc lắm, nuốt nước bọt: \"Thật sự có thể sao?\"

Tay thì đã rục rịch túm lấy vạt dưới tạp dề của cậu ấy.

Ánh mắt Giang Quát ngơ ngác trong giây lát nhưng rất nhanh đã hiểu ra.

Tưởng nhầm là tôi sợ làm bẩn quần áo nên muốn đeo tạp dề, cậu ấy bèn dang hai tay ra ngoài: \"Tay em có bột mì, đừng làm bẩn quần áo chị, chị đến cởi…\"

Lời còn chưa nói xong, tôi đã không chờ nổi mà rúc đầu vào.

Cô bạn thân không lừa tôi, thật sự rất mềm mại, hạnh phúc quá đi mất.

Chân răng tôi ngứa ngáy, có một ham muốn ăn uống kỳ lạ.

Do dự hồi lâu, thấy Giang Quát không từ chối, tôi cất lời: \"Chị cắn một miếng được không?\"

Cổ Giang Quát đỏ gắt lên, cậu ấy ngoảnh mặt đi, không dám nhìn tôi: \"Sao chị có thể vừa giữ vẻ mặt xấu hổ mà lại nói ra những lời bộc bạch thẳng thắn đến thế cơ chứ, phạm quy quá đấy chị ơi.\"

Thế nghĩa là ngầm đồng ý rồi.

Tôi gục đầu xuống, \"ngoạm\" một cái, đóng dấu chủ quyền.

Lúc Trần Tích Xuyên đến, tôi đang ăn mấy chiếc sủi cảo tròn trịa do Giang Quát nấu.

Cậu ấy khoác tạm chiếc áo ngoài, đứng dậy đi mở cửa.

Trần Tích Xuyên nhìn thoáng qua trang phục lẳng lơ đê tiện của người đàn ông lạ mặt, cùng với vết răng trên ngực cậu ấy, chỉ trong một giây, anh ấy đã khẳng định cất lời: \"Cậu chính là gã trai bao mà cô ấy từng chơi đùa đúng không?\"

\"Cậu có biết cô ấy dùng tiền của ai để nuôi cậu không?

Của tôi đấy.\"

\"Biết điều thì cút ra ngoài, tôi không thèm chấp với cậu.\"

Giang Quát không hề sợ hãi chút nào, thậm chí ánh mắt còn đầy vẻ khinh bỉ nhìn Trần Tích Xuyên một cái.

Lúc quay người lại, giọng nói lại bắt đầu run run: \"Chị ơi, đây chính là bạn trai cũ của chị sao?', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-6', '', '', '2026-10-16 21:22:15', '2026-10-16 14:22:15', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-3-6.html', '0', 'chuong', '', '0'),
('134', '1', '2026-10-16 21:22:57', '2026-10-16 14:22:57', 'Hung dữ quá à, còn bảo em cút ra ngoài nữa.\"

Tôi lập tức bỏ dở miếng sủi cảo không ăn nữa, rất có khí chất anh hùng giải cứu người đàn ông nhà mình, chắn ở trước mặt Giang Quát.

Đang tính bảo vệ cậu ấy, vừa quay sang bắt gặp viền mắt đỏ ngầu của Trần Tích Xuyên, tôi lại tự dưng thấy mình giống như người vợ lén lút làm bậy bên ngoài sau lưng người chồng chính thức, khí thế lập tức lại yếu đi: \"Trần Tích Xuyên, anh, anh có muốn ăn sủi cao không?

Ngon lắm đấy.\"

Trần Tích Xuyên tức giận đến mức cười ra tiếng.

Nhiều ngày sau khi chia tay, sắc mặt tôi hồng hào hơn trước, chẳng có nửa điểm đau lòng vì thất tình.

Trái lại là Trần Tích Xuyên, gầy đi rất nhiều, đáy mắt vằn đầy tia máu, nhìn qua là biết mất ngủ.

Thần thái này, tôi chỉ mới thấy qua trên mặt mẹ của Nhiếp Hàn Sơn.

Trong đầu lóe lên một tia sáng, tôi ướm hỏi: \"Anh đến đây là để cảnh cáo em đừng tiếp tục bám lấy anh nữa đúng không?\"

Sắc mặt Trần Tích Xuyên u ám như muốn nhỏ ra nước.

Tôi run rẩy toàn thân, thầm nghĩ người giàu ai cũng kỳ lạ thật: \"Thế thì 5 triệu tệ đi, không thể nhiều hơn nữa đâu, mọi người giá cả như nhau cả thôi, cạnh tranh không lành mạnh sẽ làm nhiễu loạn thị trường đấy.\"

\"Anh yên tâm, em uy tín lắm, người anh em tốt Nhiếp Hàn Sơn của anh có thể làm chứng cho em.\"

Nghe thấy tên Nhiếp Hàn Sơn, Trần Tích Xuyên nghiến răng nghiến lợi: \"Đừng có nhắc đến cậu ta trước mặt tôi!\"

Anh ấy rút ra một chiếc thẻ đen: \"Không phải em muốn tiền sao?

Đừng nói là 5 triệu, 10 triệu, dù là 100 triệu tôi cũng có thể cho em, chỉ cần em đồng ý với tôi một điều kiện.\"

Quy trình quen thuộc, tôi thở phào một hơi.

\"Được thôi, chúng ta chia tay.\"

\"KẾT HÔN VỚI ANH—\"

Tôi lập tức nhét chiếc thẻ lại vào tay anh ấy, như một đứa trẻ vô cớ: \"Số tiền này em không thể nhận, em không thể đồng ý với anh.\"

Chuyện không làm được sao có thể theo thói hứa hươu hứa vượn được chứ.

Trần Tích Xuyên bị thái độ từ chối không chút hối hận, thậm chí né tránh như né tà của tôi làm cho phát điên: \"Kết hôn với anh lại khiến em khó chấp nhận đến thế sao?

Em thậm chí đến cả việc dỗ dành anh cũng không muốn.\"

\"Hai chúng ta không phải người cùng một thế giới, em muốn tìm một người có thể an ổn sống qua ngày.\"

\"Hơn nữa—\"

Tôi thật thà chất phác, kéo Giang Quát ở phía sau ra: \"Em có bạn trai rồi.\"

Trần Tích Xuyên không thèm ngoảnh đầu lại mà bước đi.

Trước khi đi anh ấy còn lẩm bẩm cái gì mà tuyệt đối không làm kẻ thứ ba cho người ta, tôi mơ đẹp lắm.

Tôi thở phào một hơi nhẹ nhõm, ngoảnh đầu lại thì thấy Giang Quát đang nhìn tôi chằm chằm.

Cậu ấy nắm chặt lấy tay tôi, ánh mắt sáng rực, như chú chó nhỏ cuối cùng cũng được như nguyện: \"Chị ơi, những lời chị vừa nói đều là thật sao?\"

Tôi ngơ ngác, định nhắc nhở cậu ấy đây rõ ràng là chiêu thuật cậu ấy dạy tôi, bảo cậu ấy có thể giả làm bạn trai tôi để Trần Tích Xuyên từ bỏ ý định.

Lời còn chưa kịp thốt ra, Giang Quát đã kích động ôm chầm lấy tôi vào lòng: \"Chị ơi, em thật sự, vui quá đi mất.\"

Tôi giãy giụa cố gắng giải thích nhưng còn chưa nói ra được một từ \"không\", lại bị pha \"rửa mặt bằng ngực\" va đập cho choáng váng.

Cuối cùng tôi ngơ ngác quay mòng mòng, thế nào mà lại có thêm một người bạn trai một cách vô lý như thế.

Đã có hai bài học xương máu trước đó, tôi rất nghiêm túc ngồi xuống nói rõ ràng với Giang Quát: \"Em không phải là đại thiếu gia nhà nào đó giả nghèo để trải nghiệm cuộc sống đấy chứ?\"

Nụ cười trên mặt Giang Quát khựng lại trong giây lát nhưng nhanh chóng lấy lại kiểm soát biểu cảm, tủi thân nhìn tôi: \"Chị ơi, em cũng hy vọng mình có thể gia cảnh hiển hách như bạn trai cũ của chị, nhưng thân thế xuất thân đâu phải do em quyết định được.\"

Nói rồi, cậu ấy tự giễu cười một tiếng: \"Dù sao thì, nếu có năng lực, làm gì có người đàn ông nào lại muốn ăn cơm mềm của người phụ nữ mình thích cơ chứ.\"

Trái tim tôi trong chốc lát mềm xèo ra, trong lòng đầy vẻ áy náy, chủ động ôm lấy cậu ấy dỗ dành: \"Không sao đâu, chị có tiền, chị chỉ muốn nuôi một người như em thôi.\"

Giang Quát ngẩng mặt lên, cảm động không chịu nổi: \"Chị ơi, chị đối tốt với em như thế, em cũng không biết phải báo đáp chị thế nào nữa.\"

Tôi đang tính nói không cần đâu thì nghe thấy Giang Quát thẹn thùng nói: \"Em chẳng có gì cả, cũng chỉ được cái trẻ trung tràn đầy sức sống, nếu chị không chê...\"

...

Làm loạn đến nửa đêm, tôi mệt mỏi rã rời.

Giang Quát giống như chú bê con mới sinh, có thừa sức lực và nhiệt huyết, ao lực đòi nhất định không để tôi bị thiệt, quy đổi tiền thuê nhà theo số lần trả lại cho tôi.

Cuối cùng là vì đồ dự trữ trong nhà dùng hết sạch rồi mới luyến tiếc dừng lại.

Tôi mệt muốn đứt hơi, trong lúc nửa mơ nửa tỉnh, hình như nghe thấy giọng nói của Giang Quát.

\"Con muốn đoạn tuyệt quan hệ bố con với hai người.\"

\"Con không có thương lượng với hai người đâu.\"

\"Nhân lúc còn trẻ hai người mau cày lại tài khoản nhỏ đi.\"

\"Sau này không có việc gì thì đừng liên lạc nữa, đừng ảnh hưởng đến việc con ăn cơm mềm.\"

\"Không nghe không nghe, rùa niệm kinh.\"

\"Bố!

Chính!

Là!

Ghen!

Tị!

Vì!

Con!

Có!

Vợ!

Nuôi!\"

Biệt thự nhà họ Trần.

Trần Tích Xuyên lắc lắc ly rượu, dáng vẻ như nhìn thấu nhân gian, đoạn tuyệt tình ái: \"Anh Nhiếp, Lê Hoài Ngữ đúng là một người phụ nữ tồi tệ.\"

\"Cô ta tưởng mình ngon lắm sao?

Chẳng qua chỉ là hơi đáng yêu một chút, mắt to một chút, ôm vào lòng mềm mại một chút, mùi thơm ngát đặc biệt, nói chuyện khiến người ta muốn hôn cô ta thôi sao?

Ngoài những cái đó ra thì cô ta chẳng có gì đặc biệt cả.\"

\"Cười chết mất, kẻ ngốc mới đi làm kẻ thứ ba cho cô ta nhé.\"

\"Nào, chúc mừng chúng ta thoát khỏi biển khổ.\"

Nhiếp Hàn Sơn như không nhìn thấy động tác muốn nâng ly của anh ấy, thong thả nhấp một ngụm rượu.

Nụ cười trên mặt Trần Tích Xuyên lập tức cứng đờ, nghiến răng nghiến lợi: \"Anh nói đi chứ!

Anh thề đi!

Anh nói kẻ ngốc mới làm kẻ thứ ba cho cô ta đi!\"

Nhiếp Hàn Sơn cười nhìn chó điên, giọng điệu thản nhiên: \"Mẹ tôi đột nhiên gấp gáp lo chuyện hôn sự cho tôi, có một phần công lao của cậu đúng không?\"

\"Làm anh em, lo lắng cho đại sự đời người của cậu, không phải rất bình thường sao?\"

\"Hơn nữa…\" Trần Tích Xuyên nhếch môi, mỉa mai: \"Anh Nhiếp, tuổi tác của anh cũng không còn nhỏ nữa rồi, đàn ông qua tuổi 25 là thành 60 rồi, lực bất tòng tâm rồi.\"

Nhiếp Hàn Sơn không hề lay động: \"Thế sao?

Thế thì có lẽ tôi phải tìm chị dâu cậu kiểm chứng một chút, dù sao cô ấy từng thử qua tuổi 20 của tôi, lúc nào cũng so sánh ra được có điểm nào khác biệt.\"

Ly rượu bị bóp nát vụn, sắc mặt Trần Tích Xuyên khó coi đến đáng sợ: \"Hai người chia tay đã gần hai năm rồi, anh có thể biết điều một chút đừng có suốt ngày đi quấy rầy vợ của người khác được không!\"

Nhiếp Hàn Sơn cũng không giả vờ nữa, nhếch môi khiêu khích: \"Câu này không đến lượt cậu nói với tôi đâu nhỉ?

Tôi nhớ là, kẻ nào đó sớm đã là thì quá khứ rồi.\"

Trần Tích Xuyên vịt chết cứng mỏ: \"Tôi chưa đồng ý chia tay.\"

Nhiếp Hàn Sơn hừ lạnh một tiếng: \"Nếu miệng cứng mà không tính là chia tay, thì cũng nên là cậu quyến rũ vợ của tôi mới đúng.\"

\"Lấy tôi làm cái cớ để tự lừa mình lừa người có phải rất sướng không?

Có phải đều đã nằm trên giường rồi, quần áo cũng cởi rồi, còn phải tự dỗ dành bản thân, chỉ là để giúp tôi trả thù thôi?\"

Ỷ vào sự chênh lệch thông tin, anh thong thả quăng ra đòn chí mạng: \"Sao cậu không nghĩ xem, tại sao tôi vừa về nước cô ấy lập tức đá cậu, sao nào?

Làm thế thân mà cũng làm ra cảm giác vinh dự cho cậu được cơ đấy?

Cần tôi là chính chủ đây phát giải thưởng cho cậu không?

Giải thế thân xuất sắc nhất.\"

Trần Tích Xuyên cũng chẳng phải kẻ ngu, trực tiếp đáp trả: \"Chân ái thì sẽ không tìm thế thân, ba năm nay Tiểu Ngữ chưa từng một lần nhắc đến việc đi tìm anh.

Bảo tôi tự lừa mình lừa người, tôi thấy anh cũng chẳng kém cạnh là mấy đâu.\"

Bị đâm trúng chỗ đau, bộ mặt giả vờ điềm tĩnh của Nhiếp Hàn Sơn cũng vỡ tan.

\"Vợ bạn không được dòm ngó, có loại anh em như cậu đúng là tôi xui xẻo!\"

\"Vợ bạn thì không cần khách sáo, người vợ xinh đẹp như thế dựa vào đâu mà thuộc về anh!

Anh làm người có thể đừng tự cao tự đại như thế được không!\"

Phòng khách rộng lớn trong cuộc ẩu đả bị biến thành một bãi chiến trường hỗn độn.

Trút hết cơn bạo lực, Nhiếp Hàn Sơn mới thong thả nói rõ mục đích đến: \"Tên nhãi Giang Quát đó vì muốn giữ lấy cô ấy, đến cả quan hệ bố con cũng đoạn tuyệt rồi.\"

\"Cậu nghĩ cho kỹ đi, bây giờ không phải lúc nội bộ lục đục đâu, người có danh chính ngôn thuận là kẻ khác cơ.\"

Nhìn thấy nét mặt rõ ràng dao động của Trần Tích Xuyên, nụ cười của anh càng thêm sâu.

Sáng sớm ngủ dậy, tôi phát hiện trước cửa có người đang đứng.

Tay phải của Trần Tích Xuyên băng bó một lớp gạc dày cộp, khóe miệng tím tái trông như một chú chó nhỏ đáng thương bị rơi xuống nước: \"Anh còn cãi nhau với ông già một trận, vô tình bị tai nạn xe, ông ấy cũng khóa thẻ của anh rồi.\"

\"Mấy đứa bạn của anh, nghe nói ông già nổi giận, đứa nào đứa nấy chạy còn nhanh hơn thỏ.\"

Nói đến đây, anh ấy cúi đầu tự giễu cười một tiếng: \"Sau khi chia tay với em, anh làm cái gì cũng không thuận lợi, đây có lẽ là quả báo ông trời dành cho anh.\"

Tôi hơi luống cuống tay chân, lại cảm thấy chiêu này hình như đã gặp ở đâu rồi: \"Anh đừng nói thế, em có thể giúp gì cho anh không?\"

Ánh mắt Trần Tích Xuyên ngập ngừng: \"Thế này không tốt lắm đâu, em trai sẽ để ý đấy.\"

Giang Quát ở trong bếp chặt thức ăn vang lên ken két.

Nghe thấy lời Trần Tích Xuyên nói, tay xách con dao phay bước ra, cậu ấy mỉm cười mà như không cười, nhìn vô cùng đáng sợ: \"Tiểu Trần tổng nói lời gì thế, tôi đâu phải người không hiểu lý lẽ như vậy, bị thương một cánh tay, đây là chuyện lớn đấy.\"

Trần Tích Xuyên như không nghe ra sự mỉa mai trong lời nói của cậu ấy, cười như một nhành hoa nhài thuần khiết: \"Có câu này của em là anh yên tâm ở lại ăn ké cơm rồi.\"

Nhìn thấy hai người họ hòa thuận như vậy, tôi trút được gánh nặng.

Ở lối vào thay giày xong: \"Thế hai người từ từ ăn nhé, tay nghề của Tiểu Quát giỏi lắm, tuyệt đối đừng lãng phí nhé.\"

Sắc mặt Trần Tích Xuyên đột ngột biến đổi: \"Em không ăn cơm ở nhà sao?\"

\"Đúng vậy.\" Tôi chớp chớp mắt: \"Hàn Sơn tổ chức buổi họp mặt bạn học cũ thời đại học đi tắm suối nước nóng, anh ấy đang đợi em ở dưới nhà rồi.\"

\"Đã thỏa thuận là không được mang theo bạn đời, em đang lo Tiểu Quát bị chán, hai người có thể ở cạnh nhau thì tốt quá rồi.\"

Trần Tích Xuyên há miệng muốn đi cùng, cúi đầu lại nhìn thấy cánh tay vì để tỏ ra nghiêm trọng mà cố tình quấn chật nịt ba lớp trong ba lớp ngoài của mình, có miệng mà không thể nói, chỉ đành trố mắt nhìn người bước đi xa.

Giang Quát hừ khẩy một tiếng, rõ ràng đoán ra là ai đã bày mưu cho kẻ ngốc này, đối diện với Trần Tích Xuyên sắc mặt xanh lè mà trợn tròn mắt: \"Tên đần bị người ta xỏ mũi.\"

Trần Tích Xuyên cũng không chịu kém cạnh: \"Kẻ phế vật ngay cả người cũng không giữ nổi!\"

Trên xe, điện thoại của Nhiếp Hàn Sơn rung lên điên cuồng.

Tôi thắc mắc: \"Sao không nghe thế?\"

Anh thản nhiên mỉm cười, che giấu công lao và danh tiếng: \"Không sao, cuộc gọi quấy rầy thôi.\"

Haiz, anh không muốn nói thì tôi cũng đâu có ép.

Hơn nữa, tôi còn rất cảm ơn buổi họp mặt mà anh tổ chức.

Chiếc xe chậm rãi lao về phía trước, lướt qua vô số cảnh đẹp.

Tôi nhớ lại những người ở buổi họp mặt.

Có người bạn cùng phòng luôn không phơi khô được chăn nệm đành phải chen chúc chung một giường với tôi, người chủ nhiệm câu lạc bộ mỗi lần trước kỳ thi đều lén nhét kẹo sô-cô-la cho tôi, còn có anh khóa trên cùng tôi nuôi chung một chú mèo Ragdoll...

Đã lâu không gặp, tình cảm vẫn như xưa, mọi người đều rất quan tâm đến tình hình dạo này của tôi, gặng hỏi xem tôi và Nhiếp Hàn Sơn có phải đã chia tay rồi không.

Nghĩ đến đây, tôi không kiềm được mỉm cười, hôm nay lại là một ngày tuyệt vời rồi.', 'END', '', 'publish', 'open', 'closed', '', 'end-2', '', '', '2026-10-16 21:22:57', '2026-10-16 14:22:57', '', '0', 'https://muopdangcungcovingot.io.vn/end-2.html', '0', 'chuong', '', '0'),
('135', '1', '2026-10-16 22:20:18', '2026-10-16 15:20:18', '', '1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1', '', 'inherit', 'open', 'closed', '', '1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1', '', '', '2026-10-16 22:20:18', '2026-10-16 15:20:18', '', '72', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930071_1852413392663834430_4255545490400638597_88f1cff1572a215a6ff16beeac284de1.jpg', '0', 'attachment', 'image/jpeg', '0');
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
('136', '1', '2026-10-16 22:21:03', '2026-10-16 15:21:03', '', '1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75', '', 'inherit', 'open', 'closed', '', '1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75', '', '', '2026-10-16 22:21:03', '2026-10-16 15:21:03', '', '104', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930059_1852413392663834430_4255545490400638597_d4d7842be6a196163812867864353d75.jpg', '0', 'attachment', 'image/jpeg', '0'),
('137', '1', '2026-10-17 11:40:11', '2026-10-17 04:40:11', '', '1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0', '', 'inherit', 'open', 'closed', '', '1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0', '', '', '2026-10-17 11:40:11', '2026-10-17 04:40:11', '', '88', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930073_1852413392663834430_4255545490400638597_6dc8b85671d2b56a8eeb6d3803a5e6b0.jpg', '0', 'attachment', 'image/jpeg', '0'),
('138', '1', '2026-10-17 11:41:06', '2026-10-17 04:41:06', '', '1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c', '', 'inherit', 'open', 'closed', '', '1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c', '', '', '2026-10-17 11:41:06', '2026-10-17 04:41:06', '', '129', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930069_1852413392663834430_4255545490400638597_ddd6ca0f101df7b9458523421636db9c.jpg', '0', 'attachment', 'image/jpeg', '0'),
('139', '1', '2026-10-17 11:41:39', '2026-10-17 04:41:39', '', '1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a', '', 'inherit', 'open', 'closed', '', '1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a', '', '', '2026-10-17 11:41:39', '2026-10-17 04:41:39', '', '111', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930072_1852413392663834430_4255545490400638597_c6bd7953e2237cbd795b1caf0f66488a.jpg', '0', 'attachment', 'image/jpeg', '0'),
('140', '1', '2026-10-17 11:42:08', '2026-10-17 04:42:08', '', '1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69', '', 'inherit', 'open', 'closed', '', '1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69', '', '', '2026-10-17 11:42:08', '2026-10-17 04:42:08', '', '96', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930070_1852413392663834430_4255545490400638597_834eec41c5b38527a9c135e661f23e69.jpg', '0', 'attachment', 'image/jpeg', '0'),
('141', '1', '2026-10-17 11:42:38', '2026-10-17 04:42:38', '', '1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2', '', 'inherit', 'open', 'closed', '', '1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2', '', '', '2026-10-17 11:42:38', '2026-10-17 04:42:38', '', '79', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/1792163930068_1852413392663834430_4255545490400638597_a2dd40fa5f23313d7f84fa37487475c2.jpg', '0', 'attachment', 'image/jpeg', '0'),
('142', '1', '2026-01-01 12:00:00', '2026-01-01 05:00:00', 'Đối tượng yêu qua mạng gửi cho tôi một tấm ảnh chụp chung phòng ký túc xá, hỏi tôi: [Bảo bối, đoán xem ai là anh?]
Tôi đoán liên tiếp mấy người, đều nhận được đáp án phủ định. Cuối cùng chỉ còn lại một cậu đẹp trai nhất và một người xấu nhất, tôi nghiến răng, ôm hy vọng khoanh một vòng vào gương mặt đẹp trai mê người đó: [Chắc chắn là người này rồi đúng không!? Đẹp trai quá!]
Đối phương im lặng một lúc, đáp: [Bảo bối thông minh thật.]
Không ngờ mình lại yêu qua mạng được một anh chàng đẹp trai như vậy, tôi háo hức muốn gặp mặt nhưng lại bị đối phương ậm ừ từ chối.
Mấy ngày sau, tôi nhìn thấy bản đời thực của gương mặt đẹp trai đó ngay tại trường mình. Tôi hưng phấn chạy lại: \"Chồng ơi! Sao anh lại đến trường em thế?\"
Anh chàng đẹp trai nghe vậy khẽ nhướng mày: \"Chồng ơi? Đang gọi tôi đấy à?\"', 'NHẬN NHẦM BẠN TRAI YÊU QUA MẠNG, KHÔNG NGỜ LẠI VỚ ĐƯỢC NAM THẦN', '', 'publish', 'open', 'closed', '', 'nhan-nham-ban-trai-yeu-qua-mang-khong-ngo-lai-vo-duoc-nam-than', '', '', '2026-10-19 23:06:14', '2026-10-19 16:06:14', '', '0', 'https://muopdangcungcovingot.io.vn/nhan-nham-ban-trai-yeu-qua-mang-khong-ngo-lai-vo-duoc-nam-than/', '0', 'truyen', '', '0'),
('143', '1', '2026-10-17 12:12:00', '2026-10-17 05:12:00', '', 'bìa', '', 'inherit', 'open', 'closed', '', 'bia', '', '', '2026-10-17 12:12:00', '2026-10-17 05:12:00', '', '142', 'https://muopdangcungcovingot.io.vn/wp-content/uploads/2026/10/bia.png', '0', 'attachment', 'image/png', '0'),
('144', '1', '2026-10-17 12:42:47', '2026-10-17 05:42:47', 'Đối tượng yêu qua mạng gửi cho tôi một tấm ảnh chụp chung phòng ký túc xá, hỏi tôi: [Bảo bối, đoán xem ai là anh?]

Tôi đoán liên tiếp mấy người, đều nhận được đáp án phủ định.

Cuối cùng chỉ còn lại một cậu đẹp trai nhất và một người xấu nhất, tôi nghiến răng, ôm hy vọng khoanh một vòng vào gương mặt đẹp trai mê người đó: [Chắc chắn là người này rồi đúng không!?

Đẹp trai quá!]

Đối phương im lặng một lúc, đáp: [Bảo bối thông minh thật.]

Không ngờ mình lại yêu qua mạng được một anh chàng đẹp trai như vậy, tôi háo hức muốn gặp mặt nhưng lại bị đối phương ậm ừ từ chối.

Mấy ngày sau, tôi nhìn thấy bản đời thực của gương mặt đẹp trai đó ngay tại trường mình.

Tôi hưng phấn chạy lại: \"Chồng ơi!

Sao anh lại đến trường em thế?\"

Anh chàng đẹp trai nghe vậy khẽ nhướng mày: \"Chồng ơi?

Đang gọi tôi đấy à?\"

Nghe vậy, tôi ngẩn người một chút, quan sát kỹ gương mặt anh ấy.

Không nhận nhầm người mà, giống hệt bức ảnh hôm qua.

Lẽ nào do ảnh tôi gửi cho anh chỉnh sửa quá đà, dẫn đến việc anh ấy không nhận ra tôi?

Nghĩ vậy tôi hơi ngại ngùng, gãi gãi mũi: \"Em là Niên Đào đây, có phải ảnh chụp khác đời thực một chút không?\"

\"Niên Đào?\" Anh chàng đẹp trai dường như hiểu ra điều gì, xích lại gần ngắm nghía tôi, cuối cùng nhếch môi cười: \"Đối tượng yêu qua mạng à?\"

Tôi thở phào một hơi, hóa ra ảnh sửa xong vẫn có vài phần giống bản thân, rốt cuộc cũng nhận ra rồi.

\"Là em đây.\" Tôi híp mắt cười nhìn anh, chủ động hỏi: \"Sao anh lại ở đây?

Không phải anh ở cơ sở trường bên cạnh sao?\"

Anh chỉ ngón tay về phía tòa nhà giảng đường xa xa: \"Sang đây giao lưu tài liệu, sắp về rồi.\"

Vừa nói xong, phía sau có người gọi anh: \"Trần Hoài!

Đi không?\"

\"Hóa ra tên đầy đủ của anh là Trần Hoài.\"

Chúng tôi quen nhau qua game, tuy yêu qua mạng được một tháng nhưng hai bên vẫn gọi nhau bằng xưng hô thân mật trong game.

Đối tượng yêu qua mạng vừa cao vừa điển trai, tên cũng hay, tôi càng nhìn càng thích, chủ động tiến lên một bước hỏi: \"Dù sao cũng gặp nhau rồi, hay là cùng ăn bữa cơm rồi hẵng về?\"

Trần Hoài chưa kịp trả lời thì bạn cùng phòng của anh đã đi tới, thấy tôi liền ngẩn ra: \"Hoài ca, cô gái này là...?\"

Không chờ Trần Hoài lên tiếng, tôi chủ động khoác lấy tay anh, mỉm cười tự giới thiệu: \"Xin chào, tôi là Niên Đào, là bạn gái của Trần Hoài.\"

Đối phương ngẩn ngơ, kinh ngạc: \"Bạn gái?\"

\"Anh Hoài, cậu quen bạn gái từ bao giờ thế?!\"

Tôi chớp chớp mắt, lườm Trần Hoài một cái: \"Chuyện tụi mình yêu nhau anh không nói cho bạn cùng phòng biết à?\"

Trần Hoài rủ mắt nhìn bàn tay tôi đặt trên cánh tay anh, im lặng vài giây mới đáp: \"Chưa.\"

Tôi véo anh một cái: \"Anh giấu kỹ thật đấy.\"

Bạn cùng phòng vừa chấn động vừa khó hiểu: \"Yêu qua mạng?

Anh Hoài, cậu mà cũng yêu qua mạng sao?

Ký túc xá chúng ta chẳng phải chỉ có mỗi…\"

\"Đi ăn cơm trước đã.\" Trần Hoài ngắt lời bạn, thản nhiên nói: \"Hơi đói rồi.\"

Tôi nhiệt tình giới thiệu: \"Quán cơm rang ở cổng trường em ngon lắm, cùng đi nhé?\"

Anh bạn cùng phòng bị Trần Hoài liếc một cái, lập tức xua tay: \"Bữa nay không làm bóng đèn cản trở hai người đâu, chúc hai người 99 nhé!\"

Tôi ngượng ngùng cười, rồi kéo Trần Hoài đi về phía cổng trường.

Trên đường đi tôi hào hứng nói liên tục, nhưng Trần Hoài lại có vẻ hơi im lặng.

\"Trần Hoài.\" Tôi dừng bước, tủi thân nhìn anh: \"Gặp em anh không vui sao?

Hay là em không giống như kỳ vọng của anh?\"

Trần Hoài ngẩn ra, lắc đầu: \"Không phải.\"

\"Thế sao anh chẳng nói câu nào thế?\" Tôi dán cái mặt nóng vào cái mông lạnh suốt quãng đường rồi, trong lòng cũng thấy khó chịu: \"Anh thật sự không thích em thì cứ nói thẳng, không cần phải sa sầm nét mặt với em như vậy.\"

\"Thật sự không có gì.\" Trần Hoài nghe vậy có chút bất lực, nhìn ánh mắt ấm ức của tôi, anh giơ tay ôm lấy vai tôi, nhẹ nhàng kéo tôi vào lòng: \"Tính tình anh vốn thế này, nghe em nói chuyện anh đã rất vui rồi.\"

Má tôi dán vào cơ ngực anh một cái, cảm giác săn chắc mềm mại đó làm mặt tôi nóng bừng lên, lúc này mới phát hiện giọng anh khàn khàn: \"Nhưng trên mạng anh đâu có thế này, anh nhắn tin chăm lắm, giống như người nói nhiều vậy.\"

Trần Hoài khựng lại, thản nhiên giải thích: \"Gặp em anh hơi căng thẳng, hễ căng thẳng là ít nói.

Xin lỗi nhé.\"

Thấy đôi tai đỏ bừng ẩn hiện của anh, tôi mới miễn cưỡng bớt giận, nửa như trút giận nửa như trêu chọc véo nhẹ vào ngực anh một cái: \"Sau này không được như thế nữa, phải đường đường chính chính!

Nghe rõ chưa!\"

D.á.i tai Trần Hoài càng đỏ hơn, lan sang cả cổ.

Anh nắm lấy bàn tay đang làm loạn của tôi đè lên tim mình, lúc này mới ngoan ngoãn đáp: \"Nghe rõ rồi.\"

Tôi thỏa mãn, nắm tay anh đi vào nhà hàng.

Bữa ăn diễn ra rất vui vẻ, Trần Hoài nói ít nhưng rất biết chăm sóc người khác, múc canh, bóc tôm, gắp xương cá cho tôi, vô cùng tỉ mỉ.

Ăn xong, tôi lại kéo anh đi dạo mua sắm ở khu mới mở gần đó một lúc, mãi đến khi trời sẩm tối mới lưu luyến tiễn anh ra ga xem vé về.

\"Về đến ký túc xá nhớ nhắn tin cho em một tiếng đấy.\" Tôi tựa vào vai Trần Hoài, trong lòng không lỡ xa rời: \"Hôm nào em sang trường anh tìm anh chơi nhé.\"

Vai Trần Hoài cứng đờ nhưng nhanh chóng thả lỏng lại, gật đầu nói: \"Không cần đâu, để anh sang tìm em.

Em là con gái đi lại một mình anh không yên tâm.\"

Anh ấy săn sóc chu đáo như vậy khiến lòng tôi ngọt ngào khôn xiết, nhón chân hôn lên cằm anh một cái: \"Chồng tốt quá~\"

Gương mặt đẹp trai của Trần Hoài lập tức đỏ bừng, theo phản xạ ngoảnh đầu đi: \"Đừng gọi bậy.\"

Thấy dáng vẻ ngây thơ này của anh, tôi không kìm chế được tâm tư muốn trêu chọc: \"Gọi bậy đâu mà gọi, chẳng phải trên mạng anh thích nhất là em gọi anh như thế sao?\"

Bàn tay Trần Hoài đang nắm tay tôi khựng lại, quay người sang nhìn tôi: \"Anh rất thích sao?\"

Tôi cảm thấy câu hỏi này của anh hơi kỳ quặc nhưng vẫn trả lời anh: \"Đúng vậy, anh thích lắm mà.

Lần đầu tiên em gọi như thế, anh còn bảo tim đập vọt lên 130 nữa kìa.\"

Nói rồi tôi cố ý sờ lên ngực anh, gật đầu: \"Ừm, quả nhiên đập rất nhanh.\"

Trần Hoài bắt lấy tay tôi, giọng trầm xuống: \"Sau này đừng gọi như thế nữa.\"

Tôi không hiểu: \"Tại sao?\"

Trần Hoài nhìn tôi, ánh mắt đen thẳm sâu không thấy đáy: \"Nghe không thoải mái.\"

Tôi cảm nhận nhịp tim dưới tay anh, tưởng anh nói nhịp tim nhanh quá làm anh khó chịu, nên gật đầu hiểu chuyện: \"Được rồi, vậy em không gọi thế nữa.

Sau này em đổi thành...

anh nhé?\"

Trần Hoài \"ừm\" một tiếng, nhịp tim vừa dịu lại dường như lại đập nhanh hơn.

Thấy sắp đến giờ lên xe, tôi đi cùng anh đến cổng kiểm soát vé.

Gần đến nơi, Trần Hoài như nhớ ra điều gì, lấy điện thoại ra đưa mã cho tôi: \"Thêm liên lạc đi.\"

Tôi ngẩn ra: \"Cái gì?\"

Trần Hoài rủ mắt giải thích: \"Gần đây trường học thêm vài dự án thực tế, tài khoản trước đó dùng làm tài khoản công việc rồi, anh lại đăng ký một tài khoản cá nhân mới, em thêm tài khoản này nhé.\"

Hóa ra là vậy.

Tôi rút điện thoại ra quét mã QR của anh.

Trần Hoài thông qua kết bạn, như trút được gánh nặng: \"Anh vào ga đây, em về trường đi, đừng đi chậm trễ, về tới ký túc xá thì nhắn tin cho anh.\"

Tôi mất mát gật đầu, ôm anh, trong lòng rất không nỡ.

Trần Hoài chắc nhận ra tôi không lỡ, nét mặt dịu dàng hơn chút, giơ tay kéo tôi vào lòng ôm nhẹ một cái: \"Lần sau lại gặp nhé.\"

Má tôi dán vào vai anh, nhỏ giọng hỏi: \"Chỉ thế thôi sao?\"

Trần Hoài không hiểu: \"Cái gì?\"

Tôi ngước nhìn anh, giọng nhỏ rí: \"Ý em là, không hôn một cái sao?\"

Trần Hoài hơi khựng lại.

Tôi đỏ mặt nhìn anh.

Một lúc sau, anh như thỏa hiệp thở dài một hơi, cúi đầu, đặt một nụ hôn nhẹ lên môi tôi: \"Anh sẽ nhớ em.\"

Mãi cho đến khi về tới ký túc xá, tôi mới cảm thấy trái tim nhảy nhót của mình dịu lại hẳn nhưng nhớ đến nụ hôn đó...

A a a phấn khích quá!

Tôi hít sâu một hơi, nhắn tin cho Trần Hoài: [Em về tới ký túc xá rồi.]

Bên kia nhanh chóng đáp lại một từ [Được], rồi nói: [Tối nay anh phải thức đêm làm bài tập, em ngủ sớm đi nhé.]

Trường bọn họ đúng là bận thật, tôi hiểu cho anh, trả lời biểu cảm OK: [Thế em đi tắm rồi chuẩn bị đi ngủ đây, yêu anh moah moah!]

Trần Hoài không gửi mấy sticker đáng yêu như trước nữa mà chỉ gửi một biểu cảm đậu vàng đỏ mặt.

Rất là cổ điển.

Tôi phì cười, bắt chước anh gửi lại một sticker đậu vàng chu môi.

Yên lặng vài giây, Trần Hoài nhắn lại: [Bảo bối, lần sau gặp lại nhé.]

Tôi phì cười ra tiếng, sao cảm giác ngoài đời anh ấy lại đáng yêu hơn trên mạng thế này.

Tắm xong ra, tôi dọn dẹp một chút rồi lên gi.ư.ờ.ng, đang định nhắn tin chúc Trần Hoài ngủ ngon thì thấy anh ấy gửi tin nhắn tới.

Không phải tài khoản cá nhân mới thêm, mà là tài khoản công việc lúc trước: [Vợ ơi, hôm nay anh mệt quá, phải có vợ ôm ôm mới đỡ được...]

Tôi khựng lại, vô cớ cảm thấy Trần Hoài lúc này hơi mâu thuẫn.

Là do bài tập đè nặng sao?

Cũng có thể, dù sao làm bài tập đau khổ thật.

Thế là tôi dỗ dành: [Mệt thì nghỉ ngơi sớm đi, có gì mai hẵng làm tiếp.]

Bên kia ừm một tiếng, một lúc sau lại bất ngờ hỏi: [Vợ ơi, em thật sự thấy bức ảnh đó của anh rất đẹp trai sao?]

[Em có muốn...

xem thêm mấy tấm ảnh khác không?]

Tôi không hiểu sao anh lại hỏi thế, thật thà đáp: [Tất nhiên rồi.]

[Mấy ảnh khác cho em xem cũng OK nha.]

Bên kia gõ chữ: [Khụ khụ, thực ra anh là kiểu ăn ảnh, bên ngoài xấu hơn trong ảnh một chút.]

[Tấm ảnh đó coi như là ảnh dìm nhẹ thôi, mấy ảnh khác e là không đẹp bằng.]

Trên đầu tôi hiện lên đầy dấu chấm hỏi.

Anh nói thật đấy à?

Đời thực kém chỗ nào chứ?

Rõ ràng đẹp trai muốn c.h.ế.t mà!

Đang thầm oán trách, bên kia đã gửi sang một tấm ảnh.

Tôi theo bản năng bấm vào, con ngươi co rút…

Cái quỷ gì thế này?!

Bức ảnh nhìn qua đã biết là P quá đà, chỉ lờ mờ nhận ra là con người, không chỉ chẳng giống Trần Hoài ngoài đời tẹo nào mà thậm chí còn có vài phần kỳ dị, làm tôi nửa đêm suýt nữa thốt ra tiếng chửi thề!

Thế mà đối phương vẫn hỏi: [Thế nào, có phải kém hơn tấm ảnh trước không?]

Đâu chỉ là kém hơn!

Tôi cảm giác Trần Hoài hoàn toàn không có gu thẩm mỹ, tự P bản thân thành con quái vật thế này!!

Nhưng dù sao cũng mới yêu nhau không lâu, tôi hít sâu một hơi, vẫn quyết định nói lời dễ nghe: [Cũng không kém nhiều đâu, haha.]

Bên kia mới thở phào một hơi, tào lao thêm vài câu rồi mới đi làm bài tập.

Tôi nhìn sticker đáng yêu cuối cùng anh gửi tới cùng tấm ảnh kinh dị kia, khẽ nhíu mày, trong lòng vô cớ dấy lên một cảm giác kỳ lạ.

Nhưng nghĩ đến việc anh phải thức đêm làm bài...

Bỏ đi, mai nói tiếp.

Sáng hôm sau không có tiết, tôi tỉnh dậy đã gần 10 giờ.

Mở điện thoại, đã nhận được tin nhắn chào buổi sáng từ cả hai tài khoản của Trần Hoài.

Tôi dở khóc dở cười, đây là chứng cưỡng chế hay sao mà phải gửi cả hai nick?

Trả lời buổi sáng tốt lành cho cả hai, tôi dậy rửa mặt, quay lại thì thấy nick cá nhân của Trần Hoài gửi tin nhắn thoại: \"Sáng nay không có tiết à?

Gửi thời khóa biểu cho anh xem nào.\"

Tôi ngơ ngác, trực tiếp gọi điện lại: \"Thời khóa biểu em gửi cho anh rồi mà, tuần trước ấy, anh quên rồi à?\"

Trần Hoài khựng lại, giải thích: \"Không quên, nhưng bộ nhớ điện thoại đầy, lỡ tay xóa ảnh mất rồi.\"

\"Được rồi.\" Tôi gửi lại thời khóa biểu cho anh, dặn dò: \"Nên cài làm hình nền đoạn chat đi.\"

Trần Hoài như khẽ cười một tiếng: \"Được rồi bảo bối.\"

Giọng anh trầm ấm lôi cuốn, tôi nghe mà rất thích, nhớ lại lời anh nói tối qua, tôi không kềm được dỗ dành một câu: \"Anh thật sự rất đẹp trai, cả trong ảnh lẫn ngoài đời đều rất đẹp trai, em siêu thích luôn, anh đừng có tự ti nữa.\"

Bên kia im lặng một chút: \"Tự ti?\"', 'Chương 1', '', 'publish', 'open', 'closed', '', 'chuong-1-7', '', '', '2026-10-17 12:42:47', '2026-10-17 05:42:47', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-1-7.html', '0', 'chuong', '', '0'),
('145', '1', '2026-10-17 12:54:03', '2026-10-17 05:54:03', '\"Đúng vậy.\" Tôi bĩu môi: \"Còn bảo không ăn ảnh nữa chứ, gương mặt đó của anh so với ảnh chụp có khác gì đâu, đẹp trai đến mức làm em rung động.\" Chỉ là xin anh đừng P ảnh lung tung nữa, mắc công lại rước thứ không sạch sẽ gì về.

Trần Hoài nghe vậy im lặng một hồi, nhẹ thở dài một hơi: \"Em thích gương mặt của anh đến thế sao?\"

Tôi chớp chớp mắt, chột dạ sửa lời: \"Cũng...

rất thích con người anh nữa.\"

Trần Hoài không biết có tin không, cười khẽ một tiếng, giọng nói trầm ấm nhưng lại mang chút áp bức: \"Tốt nhất là như lời em nói.\"

\"Niên Đào, em thích gương mặt này của anh thì chỉ được thích một mình anh thôi.\"

Tôi chưa từng nghe anh dùng tông giọng này nói chuyện, mang theo vài phần phách lối không lý do lại dường như len lỏi một chút bất an nơm nớp.

Trái tim tôi mềm xèo, ngoan ngoãn hứa với anh: \"Tất nhiên rồi, em chỉ thích một mình anh thôi.\"

Mấy ngày sau đó, tần suất nhắn tin giữa tôi và Trần Hoài chỉ có tăng chứ không giảm, phần lớn dùng tài khoản cá nhân nhắn, tài khoản công việc chỉ gửi chào buổi sáng buổi tối.

Tôi nhắc anh một tiếng, bảo một nick là đủ rồi, cần gì phải dùng hai nick.

Trần Hoài ngẩn ra, khi lên tiếng lại mang giọng điệu như trút được gánh nặng: \"Không có gì, chắc sau này không dùng nick kia nhắn nữa đâu.\"

Tôi chưa kịp hỏi kỹ thì nghe thấy tiếng bạn cùng phòng của anh ở đầu dây bên kia, hình như đang bàn nhau đi đâu chơi sinh nhật.

Tôi chớp mắt, hỏi Trần Hoài: \"Sắp đến sinh nhật anh à?

Thứ mấy thế?\"

Trần Hoài \"ừm\" một tiếng: \"Thứ Sáu, vốn dĩ định sang tìm em ăn sinh nhật cùng nhưng thứ Sáu có dự án kết thúc, e là không qua kịp, nên anh đặt vé Chủ Nhật rồi.\"

Tôi nghe vậy trong lòng đã có tính toán.

Buổi tối anh không qua được, nhưng ban ngày tôi qua được mà!

Trần Hoài như nhìn thấu suy nghĩ của tôi, dặn dò: \"Em đừng sang đây, con gái năm tư đi lại một mình anh không yên tâm.\"

Tôi miệng thì \"vâng vâng\" nhưng tay đã bắt đầu đặt vé.

Tối không rảnh thì ban ngày tôi đến là được chứ gì!

Thế là sáng thứ Sáu tôi trực tiếp xin nghỉ, bắt xe ra ga tàu.

Đến cơ sở trường anh mới hơn 11 giờ, tôi ăn tạm miếng cơm rồi mang quà đến ký túc xá của Trần Hoài.

Trước đây tôi từng đặt đồ ăn ngoài cho Trần Hoài nên biết rõ ký túc xá của anh.

Lúc này chưa đến giờ tan học, dưới lầu không có mấy ai.

Tôi ngồi trên bồn hoa lấy điện thoại định nhắn tin cho anh nhưng chưa kịp gõ chữ thì một bóng râm đã bao trùm lấy tôi: \"Tiểu Đào?\"

Tôi giật mình, ngẩng đầu lên cười: \"Sao anh lại xuống đây?\"

Người đứng trước mặt chính là Trần Hoài, thấy tôi anh khựng lại ngạc nhiên, ngay sau đó mỉm cười nâng tay nắn bóp tay tôi: \"Không nghe lời nhỉ, chẳng phải đã bảo Chủ Nhật anh sang tìm em sao?\"

Tôi cọ cọ vào lòng bàn tay anh, chớp mắt nói: \"Sinh nhật thì phải mừng đúng ngày mới tốt chứ, sinh nhật vui vẻ nha anh.\"

Đáy mắt Trần Hoài đong đầy ý cười, cúi đầu ngứa ngáy hôn lên tóc tôi một cái: \"Cảm ơn bảo bối.\"

Trái tim tôi ngọt ngào, dựa vào bồn hoa nhìn anh: \"Hôm nay anh rảnh thế à?

Sao mới hơn 11 giờ đã ở ký túc xá rồi?\"

\"Về lấy chút tài liệu.\" Trần Hoài một tay xách túi cho tôi, một tay nắm lấy tay tôi: \"Đi ăn cơm nhé?

Giờ này chắc nhà ăn đông người rồi.\"

Tôi gật đầu, theo anh đi về phía nhà ăn nhưng chưa đi được mấy bước, phía sau đột nhiên vang lên một giọng nói ngờ vực: \"Ơ?

Vợ ơi?\"

Hả?

Đang gọi tôi sao?

Tôi ngẩn ra, theo bản năng định ngoảnh lại nhưng Trần Hoài bên cạnh đột nhiên giơ tay, lòng bàn tay đè sau gáy tôi, kéo thẳng tôi ôm trọn vào lòng.

Khoảnh khắc gương mặt tôi vùi vào lồng ngực ấm áp, giọng nói lạnh lẽo u uất của Trần Hoài vang lên: \"Mày gọi ai là vợ?\"

Tôi bị Trần Hoài ôm chặt, không nhìn thấy mặt người phía sau, chỉ nghe thấy tiếng đối phương nghi hoặc: \"Trần Hoài, đây là bạn gái cậu à?\"

Trần Hoài giọng lạnh như băng: \"Tất nhiên.\"

Giọng đối phương càng khó hiểu hơn: \"Nhưng sao tôi cảm thấy...

cô ấy giống bạn gái tôi thế nhỉ?\"

Trần Hoài nghe vậy hừ lạnh một tiếng: \"Bạn gái mày?

Bạn gái nào của mày?

Là cô bạn gái qua mạng chơi game chung, hay là cô em gái mưa nhắn tin thả thính, hay là cô bạn học chung câu lạc bộ?\"

Tôi ở trong lòng anh thầm tặc lưỡi, chao ôi, tra nam chính hiệu đây rồi.

Đối phương ngẩn ngơ, lẩm bẩm: \"Không phải thì thôi, có cần phải vạch trần bóc phốt người ta trước mặt người ngoài thế không.\"

Trần Hoài rõ ràng rất ghét anh ta: \"Cút.\"

Đợi đối phương lầm bầm chửi đổng đi xa, Trần Hoài mới buông tay ra, gãi nhẹ sau cổ tôi: \"Có bị ngợp không?\"

Bất ngờ chui ra từ lồng ngực ấm áp, tôi vẫn còn chút quyến luyến, sờ sờ mặt: \"Không có, tên đó là ai vậy?

Sao anh ta biết tên em?

Anh có vẻ rất ghét anh ta nhỉ?\"

Trần Hoài khựng lại, giải thích: \"Bạn cùng phòng, bạn gái cậu ta hình như tên là Giai Kỳ hay gì đó anh không nhớ rõ.

Bản tính tên này không ra gì, không chơi chung được.\"

Tôi cũng nhận ra rồi, mở miệng ra là gọi linh tinh như thế, đúng là tra nam.

\"Hừ, bỏ đi, không nhắc tới tên đó nữa.\" Trần Hoài nắm chặt tay tôi đi về phía nhà ăn: \"Đói chưa, đưa em đi ăn cơm.\"

Lúc này đúng giờ cao điểm, nhà ăn đông đúc.

Tôi và Trần Hoài còn gặp vài người bạn cùng phòng khác của anh, thế là ngồi chung bàn ăn cơm.

\"Chị dâu xinh đẹp dịu dàng thế này, thảo nào thu phục được anh Hoài của chúng em.\" Trên bàn ăn, bạn cùng phòng trêu chọc: \"Bọn em còn thắc mắc, bình thường anh Hoài im hơi lặng tiếng, sao đùng một cái đã yêu đương rồi?

Giờ nhìn xem, haiz, mê mẩn trai tài gái sắc cũng là chuyện thường tình mà!\"

Tôi bị chọc cười, Trần Hoài thì lạnh nhạt liếc đối phương một cái: \"Bớt lời đi, bạn gái tôi đấy.\"

Anh bạn cùng phòng lập tức giơ tay đầu hàng: \"Được rồi được rồi, của cậu của cậu, tôi đâu có hứng thú giành bạn gái người khác, tôi đâu có ngu như Thường Gia Hoài.\"

Tôi chớp chớp mắt, nghe không hiểu lắm.

Trần Hoài vừa gắp thức ăn cho tôi vừa hạ giọng giải thích: \"Là tên anh ghét lúc nãy đấy, cậu ta từng tán tỉnh bạn gái người khác.\"

Tôi nhăn mặt, cũng ghé sát tai anh thì thầm: \"Không chỉ đào hoa mà còn thích đập chậu cướp hoa, nhân phẩm tên này tệ thật đấy.\"

Bàn tay gắp thức ăn của Trần Hoài khựng lại: \"Đập chậu cướp hoa...\"

Tôi lại ghé sát tai anh nói tiếp: \"Đúng thế, cướp bạn gái người khác gọi là gì chứ?\"

\"Anh ta lại còn quen một lúc nhiều người, chẳng phải lừa đảo tình cảm sao!

Vừa lừa tiền vừa lừa tình, loại người này đáng hận nhất!\"

Trần Hoài máy môi, như muốn nói gì đó nhưng cuối cùng chỉ cứng đờ gật đầu: \"Ừm, quả thực...

rất đáng hận.\"

Tôi cảm thấy tâm trạng anh hôm nay không ổn lắm, ít nhất không vui như lúc mới gặp tôi.

\"Anh không khỏe ở đâu à?\" Tôi sờ trán anh: \"Hay là chiều nay đừng đi học nữa?\"

Trần Hoài lắc đầu: \"Không phải không khỏe, chỉ là nghĩ đến chiều nay phải đi làm đề tài dự án nên thấy phiền thôi.\"

Tôi phì cười, ôm lấy tay anh: \"Không sao đâu, em đợi anh.\"

Nhưng Trần Hoài không muốn tôi phải đợi suông, định đặt phòng khách sạn cho tôi nghỉ ngơi trước.

Tôi lắc đầu: \"Em tự đặt phòng rồi, với lại em hẹn cô bạn thân ở thành phố này rồi, biết chiều anh có tiết nên em hẹn cậu ấy đi dạo phố rồi.\"

Trần Hoài véo má tôi: \"Thế lát nữa làm xong anh đi tìm em.\"

Tôi mỉm cười đồng ý.

Tôi và cô bạn thân đã lâu không gặp, gặp lại rất vui vẻ.

\"Nghe nói cậu với Trần Hoài yêu nhau rồi à?

Đỉnh thật đấy!\" Bạn thân trầm ồ.

Tôi hơi ngại ngùng: \"Mới yêu chưa bao lâu.\"

Bạn thân giơ ngón tay cái: \"Tán đổ đóa hoa trên núi tuyết của khoa Công nghệ thông tin, chị em đỉnh quá!\"

Tôi nhìn chiếc vòng tay đôi của cô ấy, khẽ nhíu mày: \"Cậu cũng có tình hình gì đúng không?\"

Bạn thân hớn hở: \"Cậu em quen qua game, đang trong giai đoạn mập mờ.\"

Chiều hôm đó hai chúng tôi đi mua sắm, đến hơn 5 giờ thì Trần Hoài nhắn tin xin vị trí.

Nghĩ buổi tối không có việc gì, tôi và Trần Hoài dứt khoát mời bạn thân ăn cơm, cảm ơn cô ấy đã đi dạo phố với tôi.

Ăn xong Trần Hoài đi thanh toán, tôi và bạn thân ngồi trong phòng riêng trò chuyện.

\"Đúng rồi, tớ định sắp tới sẽ gặp mặt đối tượng mập mờ, cậu chọn giúp tớ chiếc váy nhé!\"

Bạn thân hớn hở lấy điện thoại ra chia sẻ với tôi, hai đứa đang bàn sôi nổi thì màn hình hiện lên tin nhắn, là đối tượng mập mờ gửi tới.

Cô ấy không coi tôi là người ngoài, trực tiếp mở ra: \"Hì hì, tớ rep tin nhắn cái đã.\"

Tôi gật đầu, theo lịch sự định ngoảnh mặt đi nơi khác nhưng vô tình liếc thấy ảnh đại diện của đối tượng mập mờ, ánh mắt tôi khựng lại.

Ảnh đại diện này...

sao lại giống hệt tài khoản công việc mà Trần Hoài đưa tôi thêm thế này?

Ánh mắt chúng tôi chạm nhau, bạn thân nhìn tôi: \"Sao thế?\"

Tôi trấn tĩnh lại, hỏi cô ấy: \"Đối tượng mập mờ của cậu tên gì?\"

Bạn thân ngẩn ra: \"Tớ chưa hỏi tên thật, tớ toàn gọi biệt danh, chữ cái là H.\"

Trái tim tôi chùng xuống, hỏi tiếp: \"Có thể cho tớ xem avatar và trang cá nhân của bạn bè không?\"

Bạn thân trực tiếp đưa điện thoại qua.

Càng xem tim tôi càng lạnh ngắt, đây hoàn toàn chính là tài khoản công việc đó của Trần Hoài!

Tốt lắm, thảo nào lại phải dùng hai tài khoản, thảo nào dạo này nick này ít nhắn tin cho tôi hẳn, hóa ra là bắt cá hai tay!

\"Niên Đào, có chuyện gì thế?\" Bạn thân thấy sắc mặt tôi khó coi, trong lòng cũng đoán ra điều không hay: \"Không lẽ nào...\"

Tôi hít sâu một hơi, vừa định mở miệng thì cửa phòng riêng bị đẩy ra, Trần Hoài thanh toán xong đi vào: \"Ăn xong chưa?

Có muốn...\"

Lời chưa nói xong, anh chạm phải ánh mắt âm u của tôi, ngẩn ra: \"Niên Đào?\"

Tôi rút điện thoại ra, mở tài khoản công việc kia của anh, đặt cùng điện thoại bạn thân lên bàn, mặt lạnh như tiền: \"Không giải thích một chút sao, đối, tượng, mập, mờ?\"

Không khí bỗng nhiên chìm vào yên lặng.

Trần Hoài nhìn hai chiếc điện thoại với hai tài khoản y hệt nhau, sắc mặt thay đổi.

\"Sao thế, còn phải bịa lý do nữa à?\"

Tôi nhìn chằm chằm vào đôi tai khẽ đỏ của anh, trong lòng đau như cắt, nhưng miệng lại càng chua ngoa: \"Em cứ tưởng trước khi làm ra chuyện thế này, anh đã chuẩn bị sẵn bản nháp rồi cơ đấy.\"

\"Trần Hoài, lừa dối tình cảm người khác vui lắm sao…\"

\"Không phải.\" Trần Hoài đột nhiên lên tiếng, anh nhìn tôi, ánh mắt thâm trầm: \"Anh không lừa dối tình cảm của em, tài khoản này không phải của anh.\"

Nghe vậy tôi ngẩn ra, đầu óc chưa kịp nhảy số: \"Không phải anh?

Thế người yêu qua mạng thả thính em gần một tháng qua là ma chắc?!\"

Trần Hoài chậm rãi thở ra một hơi, cầm điện thoại đi tới, mở WeChat cho tôi xem: \"Anh chỉ có duy nhất một tài khoản này, chính là tài khoản cá nhân kia.\"

\"Anh chỉ có một mình em là bạn gái, anh chưa từng nhắn tin với cô gái nào khác.\"

Tôi ngơ ngác nhìn anh, sự thật dường như hé mở, run run giọng lên tiếng: \"Cho nên...

anh không phải là H mà em quen qua mạng?\"

Trần Hoài không trả lời, hay đúng hơn, anh không dám nhìn vào mắt tôi, liếc sang cô bạn thân cũng đang ngơ ngác của tôi: \"Đối tượng yêu qua mạng của cậu chắc là bạn cùng phòng tớ.

Mặc dù nói xấu sau lưng người khác là không tốt nhưng phẩm hạnh tên này rất tệ, tớ khuyên cậu nên điều tra kỹ xem anh ta có bao nhiêu cô bạn gái rồi hãy quyết định có ở bên hắn hay không.\"

Bạn thân ngẩn người, một lúc sau mới tỉnh táo lại, đứng dậy đi tìm người tính sổ.

Trong phòng riêng chỉ còn lại tôi và Trần Hoài.', 'Chương 2', '', 'publish', 'open', 'closed', '', 'chuong-2-7', '', '', '2026-10-17 12:54:03', '2026-10-17 05:54:03', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-2-7.html', '0', 'chuong', '', '0'),
('146', '1', '2026-10-17 12:56:49', '2026-10-17 05:56:49', 'Tôi bình tĩnh lại, hỏi anh: \"Nói rõ đi, chuyện đã đến nước này rồi, anh còn định giấu em nữa sao?\"

Trần Hoài nhắm mắt lại, hạ giọng giải thích: \"Đối tượng yêu qua mạng thực sự của em là Thường Gia Hoài.

Mặc dù cậu ta yêu qua mạng với rất nhiều người nhưng vì em xinh đẹp nên cậu ta thường xuyên nhắc đến em trong ký túc xá để khoe khoang, anh có ấn tượng với em.\"

\"Lần anh đến trường em rồi nhận nhầm anh, lúc đó Thường Gia Hoài lấy cắp ảnh của anh để đi lừa em.

Ban đầu anh định giải thích rõ ràng nhưng nhìn em ôm lấy anh khóc bảo anh là bạn trai em, anh lại không kìm lòng được muốn...

đâm lao thì phải theo lao.\"

Sau đó càng lún càng sâu, không thể tự thoát ra được.

Giải thích xong anh không nói gì nữa, cũng không ngẩng đầu lên, chỉ đứng dưới ánh đèn, sắc mặt tái nhạt chờ đợi bản án của tôi.

Thực ra có một khoảnh khắc tôi cảm thấy nhẹ nhõm nhưng cảm giác bị lừa dối lại chiếm ưu thế hơn.

Thế là tôi giữ im lặng vài giây, xách túi đứng dậy định đi.

\"Niên Đào!\" Trần Hoài ngẩng đầu lên, muốn tới nắm tay tôi, giọng nói vội vã, mắt đỏ ngầu: \"Anh biết là lỗi của anh, anh rất xin lỗi, nhưng anh không hề muốn lừa dối tình cảm của em, anh thật sự rất…\"

\"Câm miệng.\" Tâm trí tôi rối bời, giọng nói lạnh nhạt: \"Em không muốn ấn tượng về anh càng tồi tệ hơn, nên bây giờ anh giữ im lặng là tốt nhất.\"

Hốc mắt Trần Hoài đỏ bừng.

Tôi không thèm liếc anh một cái, trực tiếp rời khỏi phòng riêng.

Rời khỏi nhà hàng, tôi đi thẳng về khách sạn, gom đồ đạc rồi ra ga tàu, mua chuyến tàu sớm nhất về lại trường.

Qua cổng kiểm soát vé, sắp sửa vào ga, tôi vẫn không kìm được ngoảnh đầu nhìn lại… Quả nhiên, anh đang đứng cách đó không xa, vừa lén lút vừa cẩn thận nhìn sang.

Khoảnh khắc bốn mắt nhìn nhau, tôi thấy mắt anh đỏ lên.

Tâm trạng càng thêm bực bội, tôi không nhìn nữa, dứt khoát quay người lên tàu.

Mấy ngày sau đó tâm trạng tôi đều không tốt, yêu đương mà ra nông nỗi này, vừa phiền phức vừa buồn cười.

Trần Hoài biết tôi bực bội nên không quấy rầy nhiều, chỉ thỉnh thoảng gửi vài tin nhắn, đặt trà sữa, bánh ngọt hay hoa tươi cho tôi.

Tôi nhận thì không nỡ, vứt thì tiếc, thành ra càng thêm bực mình, dứt khoát kéo anh vào danh sách đen.

Lại đến cuối tuần, mấy cô bạn cùng phòng rủ nhau đi dạo phố.

Biết dạo này tâm trạng tôi không tốt nên cố tình kéo tôi đi xả stress.

Suốt đường ăn uống mua sắm, tâm trạng cũng khá hơn chút nhưng không biết có phải ảo giác không, tôi luôn cảm thấy có người đang theo dõi mình.

Cảm giác này lên đến đỉnh điểm khi tôi rời khỏi nhà vệ sinh trên phố thương mại.

Lần này tôi không do dự nữa, đột ngột quay đầu nhìn về phía sau… Bốn mắt nhìn thẳng vào một ánh mắt xa lạ.

Đó là một cậu thanh niên dáng vẻ tuấn tú, nhìn thấy tôi lập tức mỉm cười, phấn khích vẫy tay với tôi.

Tôi hơi hoang mang, ai đây?

Trong lúc tôi còn ngơ ngác, đối phương đã bước nhanh tới trước mặt tôi, giơ tay định vò đầu tôi…

\"Tiểu Đào!

Vợ ơi!

Không ngờ lại gặp em ở đây!!\"

Tôi ngẩn người tại chỗ.

Cái gì cơ???

Chưa kịp định thần, trên vai đột nhiên truyền đến một lực mạnh mẽ, bá đạo và không cần giải thích kéo tôi lui về phía sau!

Giây tiếp theo, giọng nói quen thuộc của Trần Hoài vang lên từ phía sau: \"Cậu gọi ai là vợ?!\"

Cảm nhận được sức nặng trên vai, cả người tôi ngây dại.

Chuyện gì thế này??

Chàng trai trước mặt cũng ngẩn ra, chau mày tức giận nhìn phía sau tôi: \"Cậu là ai chứ?

Tôi với bạn gái tôi liên quan gì đến cậu?

Mau buông tay ra!\"

Trần Hoài cười nhạo một tiếng, siết chặt vai tôi: \"Bạn gái cậu?

Cậu là cái thá gì mà đòi xen vào trước mặt tôi?!\"

Chàng trai tức đến giậm chân: \"Xen vào cái đ.é.o gì!

Bọn tôi yêu nhau gần một tháng rồi!

Tôi là bạn trai chính thức!\"

\"Một tháng?\" Trần Hoài khẽ nheo mắt, không biết nghĩ đến cái gì, nét mặt thay đổi.

\"Tất nhiên rồi!

Một tháng!\" Chàng trai đối diện muốn kéo tôi ra khỏi lòng Trần Hoài, miệng cũng không chịu thua: \"Nhìn biểu cảm này của cậu, cậu mới là kẻ đến sau đúng không!

Đồ thứ ba dơ bẩn dám quyến rũ bạn gái Tiểu Đào của tôi!!\"

Nhưng Trần Hoài nhất quyết không buông, cúi đầu nhìn tôi, mắt đỏ ngầu: \"Tiểu Đào...\"

\"Được rồi!\" Tất cả lửa giận trong tôi bùng phát, trước tiên gạt bàn tay của chàng trai xa lạ trên cổ tay ra rồi lại chui ra khỏi lòng Trần Hoài, vừa tức vừa xấu hổ: \"Cả hai người đều không phải bạn trai tôi, ở đây gào rú cái gì!\"

Trần Hoài mím môi, không lên tiếng.

Chàng trai lại rất ấm ức: \"Tiểu Đào sao em lại nói thế?

Dù hai ta chưa kịp gặp mặt nhưng một tháng trước em đã đồng ý lời tỏ tình của anh rồi mà!\"

Nghe vậy tôi dường như hiểu ra điều gì.

Lúc này bạn cùng phòng bước ra khỏi nhà vệ sinh, thấy cảnh tượng giằng co này liền giật mình: \"Từ Kiều?

Sao cậu lại ở đây?

Đây là...

có chuyện gì thế?\"

Từ Kiều?

Tên này nghe quen quen, hình như là nhân vật phong vân ở trường bên cạnh.

Anh chàng tên Từ Kiều nghiến răng: \"Có chuyện gì mà không nhìn ra à?

Có kẻ muốn cướp bạn gái tôi!\"

Trần Hoài sa sầm mặt: \"Cậu bớt ăn nói xà xẻo đi, cô ấy và cậu chẳng có quan hệ gì hết!\"

\"Bọn tôi yêu nhau một tháng rồi…\"

\"Đủ rồi, hai người câm miệng hết cho tôi!\" Tôi ngắt lời tranh cãi của hai người, nhìn sang Từ Kiều: \"Cậu bảo tôi là đối tượng của cậu, bằng chứng đâu?\"

Từ Kiều lập tức rút điện thoại ra: \"Cái này làm sao giả được?

Bọn mình đã chuyện trò một tháng rồi!\"

Tôi liếc nhìn lịch sử trò chuyện mà cậu ta nói, đến khi nhìn thấy quả avatar quen thuộc đó, tôi giận quá hóa cười.

\"Cậu bảo người này là tôi?\" Tôi chỉ vào avatar hỏi.

Từ Kiều gật đầu: \"Tất nhiên rồi, em còn gửi ảnh cho anh mà, anh không nhìn nhầm đâu!\"

Trần Hoài bên cạnh liếc nhìn một cái rồi bật ra tiếng hừ lạnh, đầy khinh bỉ.

Từ Kiều lập tức bùng nổ: \"Cậu cười cái gì?!\"

Trần Hoài châm biếm: \"Cười cậu ngu, yêu đương qua mạng với một đứa con trai cả tháng trời mà không nhận ra chút nào.\"

Nói tới đây, cô bạn gái mà Từ Kiều quen qua mạng căn bản không phải tôi, mà chính là tài khoản của Thường Gia Hoài!

Tên Thường Gia Hoài này không chỉ lừa con gái, mà còn biến thành con gái để lừa cả con trai!

Giỏi, thật sự quá giỏi!

\"Thảo nào Thường Gia Hoài tháng này tiêu tiền phung phí thế, còn tưởng cậu ta tiêu tiền của bạn gái nào, hóa ra là giả gái đi câu trai tơ.\"

Trần Hoài nhìn Từ Kiều đang ngơ ngác, không chút thương hại, toàn là mỉa mai: \"Người bạn gái trong mộng của cậu là bạn cùng phòng tôi, cậu ta dùng thân phận và ảnh của Niên Đào để yêu đương với cậu, Niên Đào là bạn gái tôi, không phải của cậu!\"

Sắc mặt Từ Kiều đổi từ xanh sang trắng, im lặng hồi lâu mới thốt ra một từ \"Đ.M\" giận dữ đến cực điểm.

\"Có cần tôi cho cậu địa chỉ ký túc xá của cậu ta không?\" Trần Hoài khoanh tay nhìn cậu ta, dáng vẻ của kẻ thắng cuộc: \"Cậu có thể bắt xe qua đó ngay bây giờ.\"

Gân xanh trên trán Từ Kiều giật giật, trừng mắt nhìn Trần Hoài: \"Cậu đắc ý cái gì?

Cho dù tôi bị lừa nhưng Niên Đào vừa bảo rồi, cậu cũng không phải bạn trai chính thức của cô ấy!

Ở đó mà làm màu!\"

Trần Hoài khẽ nheo mắt, quai hàm bướng bỉnh siết chặt.

Từ Kiều hoàn toàn không thèm chấp anh, quay sang nhìn tôi: \"Tiểu Đào, tuy em không có bạn trai chính thức, nhưng em xem anh được không?

Anh...

anh thật sự rất thích em, hay là chúng mình đâm lao theo lao...\"

\"Cậu coi tôi là người c.h.ế.t à!?\" Trần Hoài cuối cùng không nhẫn nại được nữa, đấm một cú về phía Từ Kiều, giận không thể kìm nén: \"Bất luận Niên Đào có bạn trai hay chưa, tôi cũng là người quen cô ấy trước!

Làm kẻ thứ ba cậu cũng phải xếp hàng phía sau!\"

Tôi cũng không ngờ mọi chuyện lại náo loạn đến mức này, chỉ có thể đứng bên cạnh gào: \"Hai người đừng đánh nhau nữa!\"

Cả hai đều cao to vạm vỡ, đánh nhau chẳng ai chiếm được ưu thế, một người rách khóe môi, một người bầm mắt.

Nhưng may mà vẫn còn chút lý trí, biết kẻ thủ ác là Thường Gia Hoài nên không đánh đến mức sống c.h.ế.t.

\"Đưa địa chỉ của cậu ta cho tôi!\" Từ Kiều lau khóe môi: \"Đêm nay tôi đi liền.\"

Trần Hoài đọc địa chỉ cụ thể của ký túc xá.

Từ Kiều ôm mảng bầm tím trên mặt, lại nhìn tôi một cái: \"Em thật sự...\"

Ánh mắt Trần Hoài dữ tợn: \"Cút!\"

Sau khi Từ Kiều đi, chỉ còn lại tôi và Trần Hoài.

Tôi nhìn anh, hỏi: \"Sao anh lại ở đây?\"

Trần Hoài trầm giọng: \"Em kéo anh vào danh sách đen rồi, anh sợ em không tha thứ cho anh nên định đến tìm em giải thích lần nữa.

Vốn dĩ anh đến đây mua hoa cho em, ai ngờ lại đụng phải tên vừa rồi...\" Nói rồi anh mím môi, nhưng lại vô tình đụng phải vết thương ở khóe môi, lập tức nhăn mặt.

Tôi nâng cằm anh lên, quan sát kỹ vết thương: \"Mua ít thuốc bôi vào đi.\"

Trần Hoài lộ ra vẻ mặt đáng thương: \"Anh không biết tiệm thuốc ở đâu.\"

Tôi \"chán chả buồn nói\": \"Anh đặt phòng khách sạn chưa?

Gọi shipper giao tới đi, tiện thể đi tắm rửa xem lại người ngợm dơ hầy thế nào.\"

Trần Hoài rủ mắt: \"Chưa đặt, anh không quen thuộc nơi này.\"

Nhìn dáng vẻ đáng thương này của anh, tôi cũng không nỡ bỏ mặc anh ở đây một mình, chỉ đành đưa anh đến khách sạn gần trường tôi trước: \"Được rồi, tự anh lên phòng đi, em về trường đây.\"

\"Chờ đã.\" Trần Hoài túm lấy vạt áo tôi, nhỏ giọng: \"Anh có chuẩn bị một món quà tạ lỗi cho em, em xem xong rồi hãy đi được không?\"

Xem món quà cũng không tốn mấy phút, tôi gật đầu: \"Được thôi, anh lấy ra cho em xem.\"

Giọng Trần Hoài càng thấp hơn: \"Vào phòng xem đi, ở đây...

không xem được.\"

Dù không nghĩ Trần Hoài có ý đồ xấu gì, nhưng tôi vẫn cẩn thận đề phòng, thầm nhắn tin cho cô bạn thân một tiếng, lúc này mới gật đầu: \"Được thôi.\"

Vào phòng, tôi nhìn Trần Hoài: \"Bây giờ anh lấy cho em xem được chưa?\"

Trần Hoài: \"Chờ anh một chút.\" Nói xong liền xách túi vào nhà vệ sinh, rất nhanh sau đó tiếng nước vang lên.

Ủa chứ, quà gì mà còn phải đi tắm rửa mới lấy ra được?

Trong lòng tôi lập tức cảnh giác, bước tới đứng cạnh cửa, nhỡ có tình huống bất trắc là vọt liền.

Động tác của Trần Hoài rất nhanh, hơn 5 phút sau tiếng nước dừng lại, tiếp đó là tiếng sột soạt kéo khóa túi.

Tôi tò mò ghé sát tai vào cửa định nghe ngóng chút, thì cửa bất ngờ mở ra từ bên trong.

Một làn hơi nước ấm áp xộc thẳng vào mặt.

Tôi nhìn vào bên trong phòng tắm, ngây người tại chỗ.

Mất hồi lâu, tôi mới tìm lại được giọng nói của mình: \"Đâ...

Đây là món quà tạ lỗi anh nói đấy hả?\"

Trước mắt tôi, Trần Hoài mặc một chiếc áo bó sát cơ thể màu đen dành cho nam, vị trí trước ngực khoét một hình trái tim khoe ra vòm ngực nửa kín nửa hở.

Có lẽ do vừa tắm xong nhiệt độ còn cao, làn da và đôi má lộ ra ngoài của anh đều ửng hồng nhạt.

Trần Hoài rủ mắt nhìn tôi, ánh mắt thâm trầm: \"Em thích không?\"

Đây đâu chỉ là thích, tôi bây giờ chỉ muốn lập tức nổi m.á.u sắc dục lột sạch anh ra!!

\"Anh thế này...

là có ý gì?\" Tôi cố sức kìm hãm bàn tay đang rục rịch của mình, cố giữ bình tĩnh: \"Món quà tạ lỗi anh nói, chính là dùng mỹ nam kế quyến rũ em?\"

Vành tai Trần Hoài đỏ bừng: \"Anh không biết em có nguôi giận không, lại còn kéo anh vào danh sách đen, anh chỉ...

chỉ đành giở trò khôn lỏi, đánh đúng vào sở thích của em thôi.\"

Nói rồi anh nhẹ nhàng thở ra một hơi, nắm lấy bàn tay tôi áp lên ngực anh, ngượng ngùng nhưng vô cùng chân thành: \"Niên Đào, anh thật sự rất xin lỗi vì đã giấu em, nhưng việc anh thích em là thật lòng.

Em có thể cho anh một cơ hội theo đuổi em được không?\"

Tôi lập tức khôi phục lại phong thái, nhếch môi cười, lòng bàn tay khẽ vuốt ve n.g.ự.c Trần Hoài, dưới ánh mắt mất mác của đối phương, ngón tay lướt dần xuống, cuối cùng móc vào đỉnh hình trái tim bị khoét trống, nhẹ nhàng kéo về phía mình…', 'Chương 3', '', 'publish', 'open', 'closed', '', 'chuong-3-7', '', '', '2026-10-17 12:56:49', '2026-10-17 05:56:49', '', '0', 'https://muopdangcungcovingot.io.vn/chuong-3-7.html', '0', 'chuong', '', '0'),
('147', '1', '2026-10-17 12:58:49', '2026-10-17 05:58:49', '\"Anh tạ lỗi kiểu này, thành ý có vẻ hơi thiếu đấy.\"

\"Vậy em muốn kiểu tạ lỗi như thế nào?\" Trần Hoài nắm lấy tay tôi, dẫn dắt bàn tay tôi từ từ luồn vào bên trong hình trái tim: \"Thế này sao?\"

Đầu ngón tay tiến sâu vào trong, cho đến khi chạm vào sợi dây kim loại hơi lạnh lẽo, tôi hơi ngẩn ra.

Đây là cái gì?

Thấy vẻ mặt ngơ ngác của tôi, Trần Hoài dường như khẽ cười một tiếng, ra hiệu cho tôi: \"Kéo một cái thử xem?\"

Tôi quấn sợi dây kim loại quanh ngón tay một vòng, đồng thời nghe thấy tiếng leng keng giòn tan vang lên, cũng là lúc Trần Hoài phát ra tiếng hừ nhẹ khàn khàn: \"Nhẹ chút, bảo bối.\"

Tôi thật sự không kìm nổi nữa, vén phần hở hình trái tim nhìn vào bên trong, một chiếc chuông bạc nhỏ xíu đang nhấp nhô trên lồng n.g.ự.c Trần Hoài theo nhịp thở của anh.

Vào lúc này, phần con đã hoàn toàn chiến thắng phần người.

Tôi không giảm lực tay, ngược lại còn quấn sợi dây quanh ngón tay chặt hơn, khống chế Trần Hoài chặt chẽ trong lòng bàn tay, từng bước ép anh đến mép giường.

Theo từng bước chân, ánh mắt Trần Hoài ngày càng thâm trầm, môi anh máy động như muốn nói gì đó, nhưng lại bị tiếng chuông điện thoại đột ngột vang lên ngắt lời.

Tôi rút điện thoại ra liếc nhìn, nhướng mày.

Là Thường Gia Hoài.

Trần Hoài nheo mắt nhìn tôi: \"Em muốn nghe à?\"

Tôi mỉm cười: \"Dù sao cũng là...

bạn trai hờ của em, không nghe thì không hay lắm nhỉ?\"

Nói rồi, tôi bấm nút nghe, tiếng chửi bới điên cuồng lập tức xộc vào tai: \"Niên Đào!

Rốt cuộc cô là cái loại phụ nữ độc ác gì thế?

Cô đã nói gì với Từ Kiều?

Nó đ.m đòi dẫn cảnh sát đến bắt tôi cô có biết không?!\"

\"Còn cả thằng Trần Hoài nữa, rốt cuộc là có chuyện gì?

Hai người bọn chó trai tài gái đ.ĩ này…\"

Không chờ anh ta nói hết, Trần Hoài trực tiếp chộp lấy cổ tay tôi kéo điện thoại sát lại gần môi anh, từng chữ từng câu nói: \"Mày còn dám chửi một câu nữa, tao cam đoan sẽ cho mày ăn đòn thêm một trận.\"

Thường Gia Hoài lập tức ngậm miệng, nửa ngày sau mới rặn ra một từ: \"Hai người tụi mày lén lút gian d.â.m với nhau từ lâu rồi đúng không?

Hai kẻ t.i.ệ.n n.h.â.n, chờ đó cho tao…\"

\"Chờ đến năm nào tháng nào đây?\" Tôi cười khẩy một tiếng: \"Tạm thời không bàn tới việc anh bắt cá nhiều tay lừa dối tình cảm, chỉ riêng việc anh mạo danh tôi đi yêu đương qua mạng tôi đã có thể kiện anh một vố rồi.

Chưa kể tiền anh lừa từ chỗ Từ Kiều nữa, ôi chao, lừa bao nhiêu tiền là đủ cấu thành án hình sự rồi nhỉ?

Anh sắp sửa được vô đồn ngồi rồi đấy?\"

Bên kia lập tức im bặt, vài giây sau bộp một cái cúp điện thoại.

Tôi ném điện thoại sang một bên, nhìn sang Trần Hoài: \"Tiếp tục nhé?\"

Trần Hoài tiện tay vén áo lên trên, sắp sửa làm lộ ra toàn bộ chuông bạc và dây kim loại nhưng động tác của anh bỗng nhiên khựng lại, áo mắc kẹt ở chỗ nách và ngực, chỉ thiếu một chút nữa.

Tôi không hài lòng tặc lưỡi: \"Sao dừng lại rồi?\"

Trần Hoài thở dài một tiếng: \"Sức hút của bạn trai hờ chỉ tới đây thôi.\"

Tôi nghe vậy khẽ nheo mắt, ngón tay qua lớp vải ấn lên bụng anh: \"Đến đây thì sao?\"

Trần Hoài rủ mắt: \"Bạn trai dự bị.\"

Ngón tay tôi tiếp tục đi lên: \"Đến đây?\"

Vành tai Trần Hoài đỏ gay: \"Bạn trai thử việc.\"

Ý cười trong mắt tôi càng sâu, đầu ngón tay tiếp tục nhẹ nhàng di chuyển lên trên, cho đến khi ấn lên làn môi mềm mại của anh: \"Vậy, đến đây thì sao?\"

Trần Hoài ngẩng đầu nhìn tôi, nhỏ giọng nói: \"Bạn trai chính thức.\"

Tôi định thần nhìn vào mắt anh, nhìn thấy sự khao khát cùng tình yêu chân thành vụng dại dồn nén trong đó.

Một lúc sau, tôi cúi đầu hôn lên: \"Đã vượt qua thử thách, bạn trai chính thức.\"', 'END', '', 'publish', 'open', 'closed', '', 'end-3', '', '', '2026-10-17 12:58:49', '2026-10-17 05:58:49', '', '0', 'https://muopdangcungcovingot.io.vn/end-3.html', '0', 'chuong', '', '0');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_reading_bookmarks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_reading_bookmarks`;
CREATE TABLE `wp_reading_bookmarks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `story_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_story` (`user_id`,`story_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_reading_bookmarks`
INSERT INTO `wp_reading_bookmarks` (`id`, `user_id`, `story_id`, `created_at`) VALUES
('1', '4', '54', '2026-10-07 12:08:41'),
('2', '6', '33', '2026-10-08 11:59:04'),
('3', '6', '30', '2026-10-08 14:10:13'),
('4', '6', '17', '2026-10-08 14:11:01');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_reading_history`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_reading_history`;
CREATE TABLE `wp_reading_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `story_id` bigint(20) unsigned NOT NULL,
  `chapter_id` bigint(20) unsigned NOT NULL,
  `chapter_num` int(11) DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_story` (`user_id`,`story_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_reading_history`
INSERT INTO `wp_reading_history` (`id`, `user_id`, `story_id`, `chapter_id`, `chapter_num`, `updated_at`) VALUES
('1', '4', '54', '55', '1', '2026-10-07 12:08:41'),
('2', '1', '39', '41', '2', '2026-10-07 12:37:34'),
('4', '1', '30', '32', '2', '2026-10-07 12:45:42'),
('8', '1', '56', '65', '2', '2026-10-07 12:58:37'),
('11', '6', '33', '34', '1', '2026-10-08 12:00:16'),
('14', '1', '72', '73', '1', '2026-10-14 19:21:29'),
('33', '1', '88', '89', '1', '2026-10-14 19:23:01'),
('41', '1', '104', '106', '1', '2026-10-14 21:37:54'),
('45', '1', '96', '100', '1', '2026-10-12 23:26:46'),
('46', '8', '72', '73', '1', '2026-10-12 23:59:23'),
('51', '1', '79', '84', '3', '2026-10-13 01:15:20'),
('55', '1', '111', '128', '4', '2026-10-14 21:38:41'),
('122', '1', '129', '131', '1', '2026-10-16 21:23:25');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_term_relationships`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_term_relationships`;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_term_relationships`
INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES
('1', '1', '0'),
('62', '15', '0'),
('72', '7', '0'),
('72', '16', '0'),
('79', '2', '0'),
('79', '7', '0'),
('79', '16', '0'),
('79', '17', '0'),
('79', '18', '0'),
('79', '21', '0'),
('79', '32', '0'),
('88', '2', '0'),
('88', '3', '0'),
('88', '7', '0'),
('88', '13', '0'),
('88', '16', '0'),
('88', '20', '0'),
('88', '31', '0'),
('96', '2', '0'),
('96', '3', '0'),
('96', '7', '0'),
('96', '16', '0'),
('96', '20', '0'),
('96', '23', '0'),
('96', '31', '0'),
('96', '36', '0'),
('96', '37', '0'),
('96', '38', '0'),
('104', '2', '0'),
('104', '4', '0'),
('104', '7', '0'),
('104', '12', '0'),
('104', '16', '0'),
('104', '17', '0'),
('104', '18', '0'),
('104', '26', '0'),
('104', '32', '0'),
('111', '7', '0'),
('111', '16', '0'),
('111', '17', '0'),
('111', '18', '0'),
('111', '23', '0'),
('111', '36', '0'),
('111', '39', '0'),
('111', '40', '0'),
('129', '2', '0'),
('129', '3', '0'),
('129', '7', '0'),
('129', '16', '0'),
('129', '20', '0');
INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES
('129', '29', '0'),
('142', '2', '0'),
('142', '3', '0'),
('142', '7', '0'),
('142', '16', '0'),
('142', '20', '0');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_term_taxonomy`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_term_taxonomy`;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_term_taxonomy`
INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
('1', '1', 'category', '', '0', '1'),
('2', '2', 'the_loai', '', '0', '6'),
('3', '3', 'the_loai', '', '0', '4'),
('4', '4', 'the_loai', '', '0', '1'),
('5', '5', 'the_loai', '', '0', '0'),
('6', '6', 'the_loai', '', '0', '0'),
('7', '7', 'the_loai', '', '0', '8'),
('8', '8', 'the_loai', '', '0', '0'),
('9', '9', 'the_loai', '', '0', '0'),
('10', '10', 'the_loai', '', '0', '0'),
('12', '12', 'the_loai', '', '0', '1'),
('13', '13', 'the_loai', '', '0', '1'),
('15', '15', 'wp_theme', '', '0', '1'),
('16', '16', 'team_dich', 'Mướp xin cúi đầu 1 góc 45 độ chào các mom
Chào mừng các mom đến ngôi nhà nhỏ của Mướp
Chúc các mom đọc truyện vui vẻ nhoa', '0', '8'),
('17', '17', 'the_loai', '', '0', '3'),
('18', '18', 'the_loai', '', '0', '3'),
('19', '19', 'the_loai', '', '0', '0'),
('20', '20', 'the_loai', '', '0', '4'),
('21', '21', 'the_loai', '', '0', '1'),
('22', '22', 'the_loai', '', '0', '0'),
('23', '23', 'the_loai', '', '0', '2'),
('24', '24', 'the_loai', '', '0', '0'),
('25', '25', 'the_loai', '', '0', '0'),
('26', '26', 'the_loai', '', '0', '1'),
('27', '27', 'the_loai', '', '0', '0'),
('28', '28', 'the_loai', '', '0', '0'),
('29', '29', 'the_loai', '', '0', '1'),
('30', '30', 'the_loai', '', '0', '0'),
('31', '31', 'the_loai', '', '0', '2'),
('32', '32', 'the_loai', '', '0', '2'),
('33', '33', 'the_loai', '', '0', '0'),
('34', '34', 'the_loai', '', '0', '0'),
('35', '35', 'the_loai', '', '0', '0'),
('36', '36', 'the_loai', '', '0', '2'),
('37', '37', 'the_loai', '', '0', '1'),
('38', '38', 'the_loai', '', '0', '1'),
('39', '39', 'the_loai', '', '0', '1'),
('40', '40', 'the_loai', '', '0', '1'),
('42', '42', 'team_dich', 'Chào mừng bạn đến với kênh Team Dịch của Bơ!', '0', '0'),
('43', '43', 'team_dich', 'Chào mừng bạn đến với kênh Team Dịch của Team Bơ Xanh Xinh Đẹp!', '0', '0'),
('44', '44', 'team_dich', 'Chào mừng bạn đến với kênh Team Dịch của Tiểu Mướp Dịch Thuật!', '0', '0'),
('45', '45', 'team_dich', 'Chào mừng bạn đến với kênh Team Dịch của Thùy Linh Mọt Sách!', '0', '0'),
('46', '46', 'team_dich', 'Chào mừng bạn đến với kênh Team Dịch của Minh Hoàng Zhihu!', '0', '0'),
('47', '47', 'team_dich', 'Chào mừng bạn đến với kênh Team Dịch của test_dichgia!', '0', '0');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_termmeta`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_termmeta`;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_termmeta`
INSERT INTO `wp_termmeta` (`meta_id`, `term_id`, `meta_key`, `meta_value`) VALUES
('1', '16', '_team_favorite_count', '2'),
('2', '16', '_team_bio', 'Mướp xin cúi đầu 1 góc 45 độ chào các mom
Chào mừng các mom đến ngôi nhà nhỏ của Mướp
Chúc các mom đọc truyện vui vẻ nhoa'),
('4', '16', '_team_avatar', 'http://192.168.1.182:8080/wp-content/uploads/2026/10/838403835_122102501955498021_6147343361033468070_n-1.jpg');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_terms`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_terms`;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_terms`
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
('1', 'Uncategorized', 'uncategorized', '0'),
('2', 'Ngôn Tình', 'ngon-tinh', '0'),
('3', 'Ngọt', 'ngot', '0'),
('4', 'Ngược', 'nguoc', '0'),
('5', 'Đam Mỹ', 'dam-my', '0'),
('6', 'Cổ Trang', 'co-trang', '0'),
('7', 'Hiện Đại', 'hien-dai', '0'),
('8', 'Tương Lai', 'tuong-lai', '0'),
('9', 'Xuyên Không', 'xuyen-khong', '0'),
('10', 'Xuyên Sách', 'xuyen-sach', '0'),
('12', 'Trọng Sinh', 'trong-sinh', '0'),
('13', 'Hài Hước', 'hai-huoc', '0'),
('15', 'muop-dang', 'muop-dang', '0'),
('16', 'Mướp Đắng Cũng Có Vị Ngọt', 'muop-dang-cung-co-vi-ngot', '0'),
('17', 'Vả Mặt', 'va-mat', '0'),
('18', 'Nữ Cường', 'nu-cuong', '0'),
('19', 'Chữa Lành', 'chua-lanh', '0'),
('20', 'HE', 'he', '0'),
('21', 'BE', 'be', '0'),
('22', 'SE', 'se', '0'),
('23', 'Bình Luận Cốt Truyện', 'binh-luan-cot-truyen', '0'),
('24', 'Trinh Thám', 'trinh-tham', '0'),
('25', 'Kinh Dị', 'kinh-di', '0'),
('26', 'Trả Thù', 'tra-thu', '0'),
('27', 'Cung Đấu', 'cung-dau', '0'),
('28', 'Thanh Xuân Vườn Trường', 'thanh-xuan-vuon-truong', '0'),
('29', 'Tổng Tài', 'tong-tai', '0'),
('30', 'Showbiz', 'showbiz', '0'),
('31', 'Sủng', 'sung', '0'),
('32', 'Gương Vỡ Không Lành', 'guong-vo-khong-lanh', '0'),
('33', 'Hệ Thống', 'he-thong', '0'),
('34', 'Mạt Thế', 'mat-the', '0'),
('35', 'Linh Dị', 'linh-di', '0'),
('36', 'Thế Giới Trong Sách', 'the-gioi-trong-sach', '0'),
('37', 'Nữ Phụ', 'nu-phu', '0'),
('38', 'Ngụy Đam Mỹ', 'nguy-dam-my', '0'),
('39', 'Gia Đình', 'gia-dinh', '0'),
('40', 'Không CP', 'khong-cp', '0'),
('42', 'Bơ', 'bo', '0'),
('43', 'Team Bơ Xanh Xinh Đẹp', 'team-bo-xanh-xinh-dep', '0'),
('44', 'Tiểu Mướp Dịch Thuật', 'tieu-muop-dich-thuat', '0'),
('45', 'Thùy Linh Mọt Sách', 'thuy-linh-mot-sach', '0'),
('46', 'Minh Hoàng Zhihu', 'minh-hoang-zhihu', '0'),
('47', 'test_dichgia', 'test_dichgia', '0');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_usermeta`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_usermeta`;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_usermeta`
INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES
('1', '1', 'nickname', 'admin'),
('2', '1', 'first_name', ''),
('3', '1', 'last_name', ''),
('4', '1', 'description', ''),
('5', '1', 'rich_editing', 'true'),
('6', '1', 'syntax_highlighting', 'true'),
('7', '1', 'infinite_scrolling', 'true'),
('8', '1', 'comment_shortcuts', 'false'),
('9', '1', 'admin_color', 'modern'),
('10', '1', 'use_ssl', '0'),
('11', '1', 'show_admin_bar_front', 'true'),
('12', '1', 'locale', ''),
('13', '1', 'wp_capabilities', 'a:2:{s:13:\"administrator\";b:1;s:8:\"dich_gia\";b:1;}'),
('14', '1', 'wp_user_level', '10'),
('15', '1', 'dismissed_wp_pointers', ''),
('16', '1', 'show_welcome_panel', '1'),
('17', '2', 'nickname', 'dichgia_muop'),
('18', '2', 'first_name', ''),
('19', '2', 'last_name', ''),
('20', '2', 'description', ''),
('21', '2', 'rich_editing', 'true'),
('22', '2', 'syntax_highlighting', 'true'),
('23', '2', 'infinite_scrolling', 'true'),
('24', '2', 'comment_shortcuts', 'false'),
('25', '2', 'admin_color', 'modern'),
('26', '2', 'use_ssl', '0'),
('27', '2', 'show_admin_bar_front', 'true'),
('28', '2', 'locale', ''),
('29', '2', 'wp_capabilities', 'a:1:{s:8:\"dich_gia\";b:1;}'),
('30', '2', 'wp_user_level', '0'),
('31', '2', 'dismissed_wp_pointers', ''),
('32', '3', 'nickname', 'dichgia_bocute'),
('33', '3', 'first_name', ''),
('34', '3', 'last_name', ''),
('35', '3', 'description', ''),
('36', '3', 'rich_editing', 'true'),
('37', '3', 'syntax_highlighting', 'true'),
('38', '3', 'infinite_scrolling', 'true'),
('39', '3', 'comment_shortcuts', 'false'),
('40', '3', 'admin_color', 'modern'),
('41', '3', 'use_ssl', '0'),
('42', '3', 'show_admin_bar_front', 'true'),
('43', '3', 'locale', ''),
('44', '3', 'wp_capabilities', 'a:1:{s:8:\"dich_gia\";b:1;}'),
('45', '3', 'wp_user_level', '0'),
('46', '3', 'dismissed_wp_pointers', ''),
('47', '4', 'nickname', 'docgia_linh'),
('48', '4', 'first_name', ''),
('49', '4', 'last_name', ''),
('50', '4', 'description', '');
INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES
('51', '4', 'rich_editing', 'true'),
('52', '4', 'syntax_highlighting', 'true'),
('53', '4', 'infinite_scrolling', 'true'),
('54', '4', 'comment_shortcuts', 'false'),
('55', '4', 'admin_color', 'modern'),
('56', '4', 'use_ssl', '0'),
('57', '4', 'show_admin_bar_front', 'true'),
('58', '4', 'locale', ''),
('59', '4', 'wp_capabilities', 'a:1:{s:7:\"doc_gia\";b:1;}'),
('60', '4', 'wp_user_level', '0'),
('61', '4', 'dismissed_wp_pointers', ''),
('62', '5', 'nickname', 'docgia_minh'),
('63', '5', 'first_name', ''),
('64', '5', 'last_name', ''),
('65', '5', 'description', ''),
('66', '5', 'rich_editing', 'true'),
('67', '5', 'syntax_highlighting', 'true'),
('68', '5', 'infinite_scrolling', 'true'),
('69', '5', 'comment_shortcuts', 'false'),
('70', '5', 'admin_color', 'modern'),
('71', '5', 'use_ssl', '0'),
('72', '5', 'show_admin_bar_front', 'true'),
('73', '5', 'locale', ''),
('74', '5', 'wp_capabilities', 'a:1:{s:7:\"doc_gia\";b:1;}'),
('75', '5', 'wp_user_level', '0'),
('76', '5', 'dismissed_wp_pointers', ''),
('77', '1', 'session_tokens', 'a:10:{s:64:\"08090910271feb57c6f28326479637be3c896eefb6a88a2c69424501b302b3a8\";a:4:{s:10:\"expiration\";i:1793033370;s:2:\"ip\";s:13:\"192.168.1.182\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1791823770;}s:64:\"e4bc10f1661f8d15961c2ace15992b44deaac4f4869558430931234020dcc71a\";a:4:{s:10:\"expiration\";i:1793104054;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:135:\"Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1\";s:5:\"login\";i:1791894454;}s:64:\"fe1d900e6202426a90969dada68dee1f6a8c8d7e8ff69644b7f0d3b87e98fa65\";a:4:{s:10:\"expiration\";i:1793110874;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1791901274;}s:64:\"abbb155fe07eb69242833a60ad46fde07649a346291e8da40ca76be3dd5a00e8\";a:4:{s:10:\"expiration\";i:1793125625;s:2:\"ip\";s:13:\"192.168.1.176\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36\";s:5:\"login\";i:1791916025;}s:64:\"5729949c9d0a1d6323fae6a878b1574894d501aa1f8d9a6f660ac998c392d859\";a:4:{s:10:\"expiration\";i:1793374967;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1792165367;}s:64:\"459b0596c0df5a4493d7f78834c3a6d4e8cf0f5a951a05a50304f6a6a877fcf0\";a:4:{s:10:\"expiration\";i:1793427852;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:135:\"Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1\";s:5:\"login\";i:1792218252;}s:64:\"66189e537c58cb3e577002ba562fa361766cbd8bb0eab63299d4afbd12b0fb9d\";a:4:{s:10:\"expiration\";i:1793436942;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1792227342;}s:64:\"1fbf953bf219f3e75276c53cc4ffdaa63316d44a64dabf79754d4840a8eb14d2\";a:4:{s:10:\"expiration\";i:1793459812;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1792250212;}s:64:\"38c144fa3c4fd3fa2aafd728e0b184a573a01adf98369f41e9fd404485744f7a\";a:4:{s:10:\"expiration\";i:1794205856;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1792996256;}s:64:\"b64e88c882b455321ae257eed24b5711cedef24f4367c707d5312c6ca7172812\";a:4:{s:10:\"expiration\";i:1794209356;s:2:\"ip\";s:9:\"127.0.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36\";s:5:\"login\";i:1792999756;}}'),
('78', '2', 'session_tokens', 'a:1:{s:64:\"2c7546fc44a455fd78c5d70cef20691a073879d6240cbf8f9fa0c41ba292775f\";a:4:{s:10:\"expiration\";i:1792584710;s:2:\"ip\";s:3:\"::1\";s:2:\"ua\";s:11:\"curl/8.21.0\";s:5:\"login\";i:1791375110;}}'),
('79', '4', 'session_tokens', 'a:1:{s:64:\"e72fb635c83be341d6e814ca8c116c684eba9ca9e92b993af29c36b2c088110b\";a:4:{s:10:\"expiration\";i:1792584710;s:2:\"ip\";s:3:\"::1\";s:2:\"ua\";s:11:\"curl/8.21.0\";s:5:\"login\";i:1791375110;}}'),
('80', '2', '_muop_total_views', '13'),
('81', '2', '_muop_views_2026-10', '13'),
('82', '3', '_muop_total_views', '7'),
('83', '3', '_muop_views_2026-10', '7'),
('84', '1', '_muop_total_views', '134'),
('85', '1', '_muop_views_2026-10', '134'),
('86', '6', 'nickname', 'Ngockha'),
('87', '6', 'first_name', ''),
('88', '6', 'last_name', ''),
('89', '6', 'description', 'Yêu thích truyện Zhihu ngắn và ngôn tình chữa lành'),
('90', '6', 'rich_editing', 'true'),
('91', '6', 'syntax_highlighting', 'true'),
('92', '6', 'infinite_scrolling', 'true'),
('93', '6', 'comment_shortcuts', 'false'),
('94', '6', 'admin_color', 'modern'),
('95', '6', 'use_ssl', '0'),
('96', '6', 'show_admin_bar_front', 'true'),
('97', '6', 'locale', ''),
('98', '6', 'wp_capabilities', 'a:1:{s:13:\"administrator\";b:1;}'),
('99', '6', 'wp_user_level', '10'),
('100', '6', 'dismissed_wp_pointers', '');
INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES
('101', '6', 'session_tokens', 'a:1:{s:64:\"73e99d906821f1a07bd6c2e69fde163cd24d50414d2cfb23819927d140b010ea\";a:4:{s:10:\"expiration\";i:1793373873;s:2:\"ip\";s:13:\"192.168.1.176\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36\";s:5:\"login\";i:1792164273;}}'),
('102', '6', 'muop_user_avatar', 'https://api.dicebear.com/7.x/fun-emoji/svg?seed=ngockha24052001'),
('103', '1', 'wp_persisted_preferences', 'a:3:{s:4:\"core\";a:1:{s:26:\"isComplementaryAreaVisible\";b:0;}s:14:\"core/edit-post\";a:1:{s:12:\"welcomeGuide\";b:0;}s:9:\"_modified\";s:24:\"2026-10-12T07:43:38.987Z\";}'),
('104', '1', 'muop_user_avatar', 'http://192.168.1.182:8080/wp-content/themes/muop-dang/assets/images/logo-icon.png'),
('105', '1', '_muop_favorite_teams', 'a:1:{i:0;i:16;}'),
('106', '7', 'nickname', 'test_dichgia'),
('107', '7', 'first_name', ''),
('108', '7', 'last_name', ''),
('109', '7', 'description', ''),
('110', '7', 'rich_editing', 'true'),
('111', '7', 'syntax_highlighting', 'true'),
('112', '7', 'infinite_scrolling', 'true'),
('113', '7', 'comment_shortcuts', 'false'),
('114', '7', 'admin_color', 'modern'),
('115', '7', 'use_ssl', '0'),
('116', '7', 'show_admin_bar_front', 'true'),
('117', '7', 'locale', ''),
('118', '7', 'wp_capabilities', 'a:1:{s:8:\"dich_gia\";b:1;}'),
('119', '7', 'wp_user_level', '0'),
('120', '7', 'dismissed_wp_pointers', ''),
('121', '8', 'nickname', 'Bơ'),
('122', '8', 'first_name', ''),
('123', '8', 'last_name', ''),
('124', '8', 'description', ''),
('125', '8', 'rich_editing', 'true'),
('126', '8', 'syntax_highlighting', 'true'),
('127', '8', 'infinite_scrolling', 'true'),
('128', '8', 'comment_shortcuts', 'false'),
('129', '8', 'admin_color', 'modern'),
('130', '8', 'use_ssl', '0'),
('131', '8', 'show_admin_bar_front', 'true'),
('132', '8', 'locale', ''),
('133', '8', 'wp_capabilities', 'a:1:{s:7:\"doc_gia\";b:1;}'),
('134', '8', 'wp_user_level', '0'),
('135', '8', 'dismissed_wp_pointers', ''),
('136', '8', 'session_tokens', 'a:1:{s:64:\"1a2f35fd56cd0bf041e4b936d08a937d6f4e5836acfe286e1f5b9c59e3e336e2\";a:4:{s:10:\"expiration\";i:1791990193;s:2:\"ip\";s:13:\"192.168.1.176\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36\";s:5:\"login\";i:1791817393;}}'),
('137', '2', '_muop_favorite_teams', 'a:1:{i:0;i:16;}'),
('139', '1', '_muop_favorite_stories', 'a:0:{}'),
('140', '1', '_muop_followed_stories', 'a:0:{}'),
('141', '1', '_muop_followed_teams', 'a:0:{}'),
('142', '6', '_muop_favorite_stories', 'a:1:{i:0;i:129;}'),
('143', '3', '_admin_temp_password', 'dichgia123'),
('144', '2', '_admin_temp_password', 'dichgia123'),
('145', '4', '_admin_temp_password', 'docgia123'),
('146', '5', '_admin_temp_password', 'docgia123'),
('147', '8', '_admin_temp_password', 'Ngockha2405'),
('148', '6', '_admin_temp_password', 'Ngockha2405'),
('149', '7', '_admin_temp_password', 'dichgia123'),
('150', '7', '_last_password_reset_by_admin', '2026-10-16 22:38:12'),
('151', '1', '_admin_temp_password', 'Admin@123');
INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES
('152', '1', '_last_password_reset_by_admin', '2026-10-17 13:24:12');

-- --------------------------------------------------------
-- Cấu trúc bảng `wp_users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wp_users`;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Đổ dữ liệu cho bảng `wp_users`
INSERT INTO `wp_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES
('1', 'admin', '$wp$2y$10$diJaLiCy/0OnH9Mm8PBLLu6GNgZ5QjMnqVjZMUdvN2s/adE32J5Ce', 'admin', 'admin@muopdang.local', 'https://muopdangcungcovingot.io.vn', '2026-10-07 11:52:36', '', '0', 'Thị Mướp'),
('2', 'dichgia_muop', '$wp$2y$10$SEFYRNhgyLHfT6VcfbT1sO6DYpubM5UhBM5Aw/tp2DAzvSAZn4Cgq', 'dichgia_muop', 'muop@muopdang.local', '', '2026-10-07 12:08:40', '', '0', 'Tiểu Mướp Dịch Thuật'),
('3', 'dichgia_bocute', '$wp$2y$10$QbLeewY5yg/8I9lEBt1wde.shEWbWzUQbP8rwU/o0POfBtwLKotfm', 'dichgia_bocute', 'bo@muopdang.local', '', '2026-10-07 12:08:40', '', '0', 'Team Bơ Xanh Xinh Đẹp'),
('4', 'docgia_linh', '$wp$2y$10$nv8uMWwFeifUXfYOoQShsuzU2UJ0pDV6U.OghRaaRzQTFsJlhBAaG', 'docgia_linh', 'linh@muopdang.local', '', '2026-10-07 12:08:40', '', '0', 'Thùy Linh Mọt Sách'),
('5', 'docgia_minh', '$wp$2y$10$75ruNFysMKNo48x9o/w0nOkrzvMryCMvV1Hvcf9aMoRIobBLbu5sS', 'docgia_minh', 'minh@muopdang.local', '', '2026-10-07 12:08:40', '', '0', 'Minh Hoàng Zhihu'),
('6', 'ngockha24052001', '$wp$2y$10$D.0t5r0th52GGYlJrm5SHOIrfGw/5QZ6ChCCsRvemSCAxs85fvYmW', 'ngockha24052001', 'ngockha24052001@gmail.com', '', '2026-10-08 11:58:48', '', '0', 'Ngọc Kha'),
('7', 'test_dichgia', '$wp$2y$10$HpkW93NKv/rqS6JRl2Q5segWGSvpED67EWbC5b.9Mahr2IvFiMUry', 'test_dichgia', 'test@test.com', '', '2026-10-12 10:28:23', '', '0', 'test_dichgia'),
('8', 'comotkhonghai59', '$wp$2y$10$PwbmR9dPVl6CkBd1yDpE6eFNvzZ.IXAUZt9zvJeK1ZR9lWtC2VZre', 'comotkhonghai59', 'comotkhonghai59@gmail.com', '', '2026-10-12 15:03:13', '', '0', 'Bơ');

SET FOREIGN_KEY_CHECKS=1;
