<?php
/**
 * Công cụ tự động khôi phục toàn diện & Khắc phục lỗi Mướp Đắng 1 Chạm
 * Dành cho Website: Mướp Đắng Cũng Có Vị Ngọt
 * Được thiết kế an toàn, không chứa mã mã hóa base64 hay lệnh nguy hiểm.
 */
header('Content-Type: text/html; charset=utf-8');

$base_dir = __DIR__;
$wp_load_path = file_exists($base_dir . '/wp-load.php') ? $base_dir . '/wp-load.php' : (file_exists($base_dir . '/core/wp-load.php') ? $base_dir . '/core/wp-load.php' : null);
$theme_dir = $base_dir . '/wp-content/themes/muop-dang';

$results = array();
$errors = array();

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';

// 1. Tự động giải nén file ZIP cập nhật nếu người dùng tải lên
if ($action === 'unzip_all' || $action === 'auto_all') {
    $zip_files = glob($base_dir . '/*.zip');
    $zip_files = array_merge((array)$zip_files, (array)glob($base_dir . '/wp-content/themes/*.zip'));
    
    $extracted_count = 0;
    if (class_exists('ZipArchive') && !empty($zip_files)) {
        foreach ($zip_files as $zf_path) {
            $bname = basename($zf_path);
            if (strpos($bname, 'muop') !== false || strpos($bname, 'cap_nhat') !== false) {
                $zip = new ZipArchive();
                if ($zip->open($zf_path) === TRUE) {
                    $dest = $theme_dir;
                    if (strpos($bname, 'goc_website') !== false) {
                        $dest = $base_dir;
                    } elseif (strpos($bname, 'muop-dang.zip') !== false) {
                        $dest = dirname($theme_dir);
                    }
                    
                    if ($dest && is_dir($dest)) {
                        $zip->extractTo($dest);
                        $zip->close();
                        $results[] = 'Đã giải nén gói <strong>' . htmlspecialchars($bname) . '</strong> vào <code>' . htmlspecialchars($dest) . '</code>!';
                        $extracted_count++;
                    }
                }
            }
        }
    }
}

// 2. Nạp WordPress, đồng bộ Database và làm mới Permalinks
$wp_loaded = false;
if ($wp_load_path) {
    try {
        require_once $wp_load_path;
        $wp_loaded = true;
        
        global $wpdb;
        if ($wpdb) {
            $table_splitter = $wpdb->prefix . 'muop_splitter_history';
            $wpdb->query("CREATE TABLE IF NOT EXISTS `$table_splitter` (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;");
            
            $table_audio = $wpdb->prefix . 'muop_audio_logs';
            $wpdb->query("CREATE TABLE IF NOT EXISTS `$table_audio` (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;");

            $table_vlogs = $wpdb->prefix . 'muop_visitor_read_logs';
            $wpdb->query("CREATE TABLE IF NOT EXISTS `$table_vlogs` (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;");

            if (function_exists('update_option')) {
                update_option('posts_per_page', 24);
            }
            $results[] = 'Đã kiểm tra và đồng bộ cấu trúc Database (Bảng Audio, Khách đọc truyện, Tách chương đa thiết bị)!';
        }

        // Làm mới permalinks rewrite rules
        if (function_exists('flush_rewrite_rules')) {
            if (function_exists('muop_register_post_types')) {
                muop_register_post_types();
            }
            flush_rewrite_rules(true);
            $results[] = 'Đã làm mới quy tắc điều hướng (Rewrite rules) & đăng ký lại Post Type Truyện!';
        }

        if (function_exists('delete_transient')) {
            delete_transient('muop_all_genres_list');
            $results[] = 'Đã dọn dẹp bộ nhớ đệm (Transient Cache)!';
        }
    } catch (Throwable $e) {
        $errors[] = 'Lỗi nạp WordPress: ' . $e->getMessage();
    }
}

$func_exists_on_disk = $theme_dir && file_exists($theme_dir . '/functions.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khắc Phục Lỗi & Tối Ưu Hệ Thống - Mướp Đắng</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f6f8; color: #2d3748; padding: 20px; margin: 0; }
        .box { max-width: 800px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; }
        .header { background: #406b47; color: white; padding: 24px 30px; }
        .header h1 { margin: 0 0 8px; font-size: 22px; }
        .header p { margin: 0; opacity: 0.9; font-size: 14px; }
        .content { padding: 30px; }
        .btn { display: inline-block; padding: 12px 22px; font-size: 15px; font-weight: 600; color: white; background: #406b47; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; transition: 0.2s; }
        .btn:hover { background: #2f5235; }
        .btn-outline { background: transparent; border: 1px solid #406b47; color: #406b47; }
        .btn-outline:hover { background: #edf7ee; }
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #def7ec; color: #03543f; border: 1px solid #bcf0da; }
        .alert-error { background: #fde8e8; color: #9b1c1c; border: 1px solid #f8b4b4; }
        .list-group { list-style: none; padding: 0; margin: 20px 0; }
        .list-group li { padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #def7ec; color: #03543f; }
        .badge-danger { background: #fde8e8; color: #9b1c1c; }
    </style>
</head>
<body>
<div class="box">
    <div class="header">
        <h1>🌿 Công Cụ Kiểm Tra & Khắc Phục Lỗi Hệ Thống</h1>
        <p>Phiên bản an toàn, chuẩn bảo mật hosting (Không chứa mã nén hay base64)</p>
    </div>
    <div class="content">
        <?php if (!empty($results)): ?>
            <div class="alert alert-success">
                <strong>Thành công:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    <?php foreach ($results as $r): ?>
                        <li><?php echo $r; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Thông báo:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo $e; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h3 style="margin-top: 0;">Trạng thái hệ thống:</h3>
        <ul class="list-group">
            <li>
                <span>WordPress Core (wp-load.php):</span>
                <span class="badge <?php echo $wp_loaded ? 'badge-success' : 'badge-danger'; ?>">
                    <?php echo $wp_loaded ? 'Đang hoạt động' : 'Chưa nạp'; ?>
                </span>
            </li>
            <li>
                <span>File functions.php theme:</span>
                <span class="badge <?php echo $func_exists_on_disk ? 'badge-success' : 'badge-danger'; ?>">
                    <?php echo $func_exists_on_disk ? 'Tồn tại (Bình thường)' : 'Không tìm thấy'; ?>
                </span>
            </li>
        </ul>

        <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="?action=auto_all" class="btn">🚀 Chạy Đồng Bộ & Làm Mới Rewrite Rules</a>
            <a href="<?php echo $wp_loaded ? esc_url(home_url('/')) : '/'; ?>" class="btn btn-outline">🏠 Về Trang Chủ Website</a>
        </div>
    </div>
</div>
</body>
</html>
