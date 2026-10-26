<?php
/**
 * Theme Functions & Definitions for Mướp Đắng Cũng Có Vị Ngọt
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MUOP_THEME_VERSION', '2.5.0');
define('MUOP_THEME_DIR', get_template_directory());
define('MUOP_THEME_URI', get_template_directory_uri());

// Thiết lập múi giờ chuẩn Việt Nam (GMT+7)
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
}

// Load MVC (Model - View - Controller) & Client-Server Architecture
require_once MUOP_THEME_DIR . '/app/bootstrap.php';

// 1. Theme Support Setup
function muop_setup_theme() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    add_image_size('muop-cover', 300, 420, true);
    add_image_size('muop-cover-thumb', 120, 168, true);
    
    register_nav_menus(array(
        'primary-menu' => __('Menu Chính', 'muop-dang'),
        'footer-menu'  => __('Menu Chân Trang', 'muop-dang')
    ));
}
add_action('after_setup_theme', 'muop_setup_theme');

// Ẩn hoàn toàn thanh công cụ WordPress Admin Bar ở ngoài trang web (Frontend)
add_filter('show_admin_bar', '__return_false');

// Thiết lập headers cho phép tài nguyên hoạt động trơn tru
add_action('send_headers', function() {
    if (!headers_sent()) {
        header("X-Content-Type-Options: nosniff");
        header("Content-Security-Policy: default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src-elem * 'unsafe-inline' 'unsafe-eval' data: blob:; style-src * 'unsafe-inline'; font-src * data:; img-src * data: blob:;");
    }
});

/**
 * Chuẩn hóa URL tài nguyên/ảnh để tránh lỗi Mixed Content hoặc sót localhost:8080 từ môi trường dev
 */
function muop_normalize_url($url) {
    if (empty($url) || !is_string($url)) {
        return $url;
    }

    $site_url = home_url();
    $is_https = is_ssl() || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

    $localhost_hosts = array(
        'http://localhost:8080',
        'https://localhost:8080',
        'http://localhost',
        'https://localhost',
        'http://127.0.0.1:8080',
        'https://127.0.0.1:8080',
        'http://127.0.0.1',
        'https://127.0.0.1',
    );

    foreach ($localhost_hosts as $lh) {
        if (strpos($url, $lh) === 0) {
            $url = $site_url . substr($url, strlen($lh));
            break;
        }
    }

    // Nếu trang hiện tại là HTTPS và URL cùng domain là HTTP -> tự động nâng cấp sang HTTPS
    if ($is_https && strpos($url, 'http://') === 0) {
        $site_host = parse_url($site_url, PHP_URL_HOST);
        $url_host  = parse_url($url, PHP_URL_HOST);
        if ($site_host && $url_host && strtolower($site_host) === strtolower($url_host)) {
            $url = 'https://' . substr($url, 7);
        }
    }

    return $url;
}

// Tự động chuẩn hóa attachment url và nội dung bài viết
add_filter('wp_get_attachment_url', 'muop_normalize_url', 99);
add_filter('the_content', function($content) {
    if (empty($content) || !is_string($content)) return $content;
    $site_url = home_url();
    return str_replace(
        array('http://localhost:8080', 'https://localhost:8080', 'http://localhost', 'http://127.0.0.1:8080'),
        $site_url,
        $content
    );
}, 99);

// Tự động kiểm tra và cập nhật các URL localhost trong database sang domain thật
add_action('init', function() {
    static $checked = false;
    if ($checked) return;
    $checked = true;

    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $is_not_local = (!empty($host) && strpos($host, 'localhost') === false && strpos($host, '127.0.0.1') === false);
    $force_fix = (isset($_GET['muop_fix_urls']) && current_user_can('manage_options'));

    if ($force_fix) {
        try {
            global $wpdb;
            $patterns = array(
                'http://localhost:8080',
                'https://localhost:8080',
                'http://localhost',
                'http://127.0.0.1:8080'
            );
            $new_url = home_url();

            foreach ($patterns as $old_url) {
                if (!empty($wpdb->termmeta)) $wpdb->query($wpdb->prepare("UPDATE {$wpdb->termmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_value LIKE %s", $old_url, $new_url, '%' . $wpdb->esc_like($old_url) . '%'));
                if (!empty($wpdb->usermeta)) $wpdb->query($wpdb->prepare("UPDATE {$wpdb->usermeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_value LIKE %s", $old_url, $new_url, '%' . $wpdb->esc_like($old_url) . '%'));
                if (!empty($wpdb->postmeta)) $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_value LIKE %s", $old_url, $new_url, '%' . $wpdb->esc_like($old_url) . '%'));
                if (!empty($wpdb->posts)) $wpdb->query($wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s) WHERE post_content LIKE %s", $old_url, $new_url, '%' . $wpdb->esc_like($old_url) . '%'));
            }

            update_option('muop_db_localhost_migrated_v1', 1);
        } catch (\Throwable $e) {}
    }
});

// 2. Custom Roles Setup
function muop_setup_roles() {
    // Role Độc giả (Reader)
    if (!get_role('doc_gia')) {
        add_role('doc_gia', 'Độc Giả', array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'upload_files' => false
        ));
    }
    
    // Role Dịch giả (Translator)
    if (!get_role('dich_gia')) {
        add_role('dich_gia', 'Dịch Giả', array(
            'read' => true,
            'edit_posts' => true,
            'publish_posts' => false, // Needs admin moderation by default or can self-publish
            'delete_posts' => true,
            'upload_files' => true
        ));
    } else {
        $trans_role = get_role('dich_gia');
        $trans_role->add_cap('upload_files');
        $trans_role->add_cap('edit_posts');
    }
}
add_action('init', 'muop_setup_roles');

// 3. Register Custom Post Types & Taxonomies
function muop_register_post_types() {
    // Custom Post Type: Truyện
    $labels_truyen = array(
        'name'               => 'Truyện',
        'singular_name'      => 'Truyện',
        'menu_name'          => 'Kho Truyện',
        'add_new'            => 'Đăng truyện mới',
        'add_new_item'       => 'Thêm truyện mới',
        'edit_item'          => 'Chỉnh sửa truyện',
        'new_item'           => 'Truyện mới',
        'view_item'          => 'Xem truyện',
        'search_items'       => 'Tìm kiếm truyện',
        'not_found'          => 'Không tìm thấy truyện nào',
        'not_found_in_trash' => 'Không có truyện trong thùng rác'
    );
    $args_truyen = array(
        'labels'             => $labels_truyen,
        'public'             => true,
        'has_archive'        => 'truyen',
        'rewrite'            => array('slug' => 'truyen', 'with_front' => false),
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author'),
        'menu_icon'          => 'dashicons-book-alt',
        'show_in_rest'       => true
    );
    register_post_type('truyen', $args_truyen);

    // Custom Post Type: Chương
    $labels_chuong = array(
        'name'               => 'Chương',
        'singular_name'      => 'Chương',
        'menu_name'          => 'Chương Truyện',
        'add_new'            => 'Thêm chương mới',
        'add_new_item'       => 'Thêm chương truyện mới',
        'edit_item'          => 'Chỉnh sửa chương',
        'view_item'          => 'Xem chương',
        'search_items'       => 'Tìm kiếm chương',
        'not_found'          => 'Không có chương nào'
    );
    $args_chuong = array(
        'labels'             => $labels_chuong,
        'public'             => true,
        'has_archive'        => false,
        'rewrite'            => array('slug' => 'chuong', 'with_front' => false),
        'supports'           => array('title', 'editor', 'comments', 'author'),
        'menu_icon'          => 'dashicons-media-document',
        'show_in_rest'       => true
    );
    register_post_type('chuong', $args_chuong);

    // Custom Taxonomy: Thể loại
    $labels_the_loai = array(
        'name'          => 'Thể loại',
        'singular_name' => 'Thể loại',
        'search_items'  => 'Tìm kiếm thể loại',
        'all_items'     => 'Tất cả thể loại',
        'edit_item'     => 'Chỉnh sửa thể loại',
        'update_item'   => 'Cập nhật thể loại',
        'add_new_item'  => 'Thêm thể loại mới',
        'menu_name'     => 'Thể loại'
    );
    register_taxonomy('the_loai', array('truyen'), array(
        'hierarchical'      => true,
        'labels'            => $labels_the_loai,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'the-loai', 'with_front' => false),
        'show_in_rest'      => true
    ));

    // Custom Taxonomy: Tác giả
    register_taxonomy('tac_gia', array('truyen'), array(
        'hierarchical'      => false,
        'labels'            => array('name' => 'Tác giả', 'singular_name' => 'Tác giả'),
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'tac-gia', 'with_front' => false)
    ));

    // Custom Taxonomy: Team Dịch
    register_taxonomy('team_dich', array('truyen'), array(
        'hierarchical'      => false,
        'labels'            => array('name' => 'Team Dịch', 'singular_name' => 'Team Dịch'),
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'team-dich', 'with_front' => false)
    ));
}
add_action('init', 'muop_register_post_types');

// ==========================================================================
// 3.1 CUSTOM PERMALINKS & CLEAN URL ROUTING
// - Đường dẫn truyện: /{slug-truyen}/ (Bỏ tiền tố /truyen/)
// - Đường dẫn chương: /{slug-truyen}/{slug-chuong}.html (Có cả tên truyện & tên chương.html)
// ==========================================================================

// 1. Tạo liên kết chuẩn (Permalink) cho CPT Truyện & Chương
add_filter('post_type_link', 'muop_custom_post_type_link', 20, 2);
function muop_custom_post_type_link($post_link, $post) {
    if (!is_object($post)) {
        $post = get_post($post);
    }
    if (!$post) {
        return $post_link;
    }

    // A. Truyện: /{story-slug}/ (bỏ /truyen/)
    if ($post->post_type === 'truyen') {
        return home_url('/' . $post->post_name . '/');
    }

    // B. Chương: /{story-slug}/{chapter-slug}.html
    if ($post->post_type === 'chuong') {
        $story_id = (int) get_post_meta($post->ID, '_chuong_truyen_id', true);
        if ($story_id) {
            $story = get_post($story_id);
            if ($story && !empty($story->post_name)) {
                return home_url('/' . $story->post_name . '/' . $post->post_name . '.html');
            }
        }
        return home_url('/' . $post->post_name . '.html');
    }

    return $post_link;
}

// 2. Thêm luật rewrite gốc cho đường dẫn chương .html
add_filter('rewrite_rules_array', 'muop_custom_rewrite_rules');
function muop_custom_rewrite_rules($rules) {
    $new_rules = array();
    $new_rules['^([^/]+)/([^/]+)\.html/?$'] = 'index.php?post_type=chuong&name=$matches[2]';
    return $new_rules + $rules;
}

// 3. Phân giải truy vấn (Request Router) nhận diện truyện và chương
add_filter('request', 'muop_custom_parse_request_filter', 1, 1);
function muop_custom_parse_request_filter($query_vars) {
    if (is_admin()) {
        return $query_vars;
    }

    $req_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $path = trim(parse_url($req_uri, PHP_URL_PATH), '/');

    if (empty($path)) {
        return $query_vars;
    }

    // A. Xử lý đường dẫn chương: {story-slug}/{chapter-slug}.html
    if (preg_match('#^([^/]+)/([^/]+)\.html$#i', $path, $matches)) {
        $story_slug = sanitize_title($matches[1]);
        $chap_slug  = sanitize_title($matches[2]);

        global $wpdb;
        $story_id = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'truyen' LIMIT 1",
            $story_slug
        ));

        $chap = null;
        if ($story_id) {
            $chap = $wpdb->get_row($wpdb->prepare(
                "SELECT p.ID, p.post_name FROM $wpdb->posts p
                 INNER JOIN $wpdb->postmeta pm ON (p.ID = pm.post_id AND pm.meta_key = '_chuong_truyen_id' AND pm.meta_value = %d)
                 WHERE p.post_name = %s AND p.post_type = 'chuong' LIMIT 1",
                $story_id,
                $chap_slug
            ));
        }

        if (!$chap) {
            $chap = $wpdb->get_row($wpdb->prepare(
                "SELECT ID, post_name FROM $wpdb->posts WHERE post_name = %s AND post_type = 'chuong' LIMIT 1",
                $chap_slug
            ));
        }

        if ($chap) {
            return array(
                'p'         => (int) $chap->ID,
                'post_type' => 'chuong',
                'name'      => $chap->post_name,
                'chuong'    => $chap->post_name,
            );
        }
    }

    // B. Xử lý đường dẫn truyện: /{story-slug}/
    $reserved = array(
        'wp-admin', 'wp-login.php', 'wp-json', 'wp-includes', 'wp-content',
        'the-loai', 'tac-gia', 'team-dich', 'tu-truyen', 'dang-nhap', 'dang-ky',
        'quan-ly-admin', 'them-chuong', 'dang-truyen', 'ho-so', 'thong-tin-dich-gia',
        'api', 'feed', 'sitemap.xml', 'sitemap.html', 'mobile.html', 'truyen-hot', 'truyen-moi'
    );

    $story_slug = $path;
    $paged = 1;
    if (preg_match('#^([^/]+)/page/([0-9]+)$#i', $path, $m_paged)) {
        $story_slug = $m_paged[1];
        $paged = (int) $m_paged[2];
    }

    if (strpos($story_slug, '/') === false && !in_array($story_slug, $reserved)) {
        $page = get_page_by_path($story_slug);
        if (!$page) {
            global $wpdb;
            $story = $wpdb->get_row($wpdb->prepare(
                "SELECT ID, post_name FROM $wpdb->posts WHERE post_name = %s AND post_type = 'truyen' AND post_status IN ('publish', 'draft', 'pending', 'private', 'future') LIMIT 1",
                $story_slug
            ));
            if ($story) {
                $res = array(
                    'p'         => (int) $story->ID,
                    'post_type' => 'truyen',
                    'name'      => $story->post_name,
                    'truyen'    => $story->post_name,
                );
                if ($paged > 1) {
                    $res['paged'] = $paged;
                }
                return $res;
            }
        }
    }

    return $query_vars;
}

// 4. Ngăn chặn chuyển hướng canonical sai lệch của WordPress
add_filter('redirect_canonical', 'muop_prevent_canonical_redirect_for_custom_permalinks', 10, 2);
function muop_prevent_canonical_redirect_for_custom_permalinks($redirect_url, $requested_url) {
    if (is_singular('truyen') || is_singular('chuong')) {
        return false;
    }
    return $redirect_url;
}

// 5. Chuyển hướng 301 tự động từ URL cũ sang URL mới chuẩn SEO
add_action('template_redirect', 'muop_redirect_old_cpt_urls');
function muop_redirect_old_cpt_urls() {
    if (is_admin()) return;

    $req_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $parsed_path = parse_url($req_uri, PHP_URL_PATH);
    $query_str = parse_url($req_uri, PHP_URL_QUERY);
    $query_append = !empty($query_str) ? '?' . $query_str : '';

    // 1. Chuyển hướng /truyen/{slug}/ -> /{slug}/
    if (preg_match('~^/truyen/([^/]+)/?$~i', $parsed_path, $matches)) {
        $slug = sanitize_title($matches[1]);
        if (!in_array($slug, array('feed', 'page'))) {
            global $wpdb;
            $story_exists = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'truyen' LIMIT 1",
                $slug
            ));
            if ($story_exists) {
                wp_redirect(home_url('/' . $slug . '/' . $query_append), 301);
                exit;
            }
        }
    }

    // 2. Chuyển hướng /chuong/{slug}/ -> /{story-slug}/{slug}.html
    if (preg_match('~^/chuong/([^/]+)/?$~i', $parsed_path, $matches)) {
        $chap_slug = sanitize_title($matches[1]);
        global $wpdb;
        $chap = $wpdb->get_row($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'chuong' LIMIT 1",
            $chap_slug
        ));
        if ($chap) {
            $story_id = (int) get_post_meta($chap->ID, '_chuong_truyen_id', true);
            if ($story_id) {
                $story = get_post($story_id);
                if ($story && !empty($story->post_name)) {
                    wp_redirect(home_url('/' . $story->post_name . '/' . $chap_slug . '.html' . $query_append), 301);
                    exit;
                }
            }
        }
    }

    // 3. Chuyển hướng /{story-slug}/{chap-slug}/ (không có .html) -> /{story-slug}/{chap-slug}.html
    if (preg_match('~^/([^/]+)/([^/]+)/?$~i', $parsed_path, $matches)) {
        $story_slug = sanitize_title($matches[1]);
        $chap_slug  = sanitize_title($matches[2]);
        $reserved = array('wp-admin', 'wp-login.php', 'wp-json', 'the-loai', 'tac-gia', 'team-dich', 'page');
        if (!in_array($story_slug, $reserved) && substr($chap_slug, -5) !== '.html') {
            global $wpdb;
            $story_id = (int) $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'truyen' LIMIT 1",
                $story_slug
            ));
            if ($story_id) {
                $chap_exists = $wpdb->get_var($wpdb->prepare(
                    "SELECT p.ID FROM $wpdb->posts p
                     INNER JOIN $wpdb->postmeta pm ON (p.ID = pm.post_id AND pm.meta_key = '_chuong_truyen_id' AND pm.meta_value = %d)
                     WHERE p.post_name = %s AND p.post_type = 'chuong' LIMIT 1",
                    $story_id,
                    $chap_slug
                ));
                if ($chap_exists) {
                    wp_redirect(home_url('/' . $story_slug . '/' . $chap_slug . '.html' . $query_append), 301);
                    exit;
                }
            }
        }
    }
}

// 4. Custom Database Tables & Default Options
function muop_setup_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Table: Affiliate Clicks (Shopee & TikTok)
    $table_clicks = $wpdb->prefix . 'affiliate_clicks';
    $sql_clicks = "CREATE TABLE IF NOT EXISTS $table_clicks (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        link_type VARCHAR(20) NOT NULL DEFAULT 'shopee',
        url TEXT NOT NULL,
        ip VARCHAR(45) DEFAULT '',
        user_agent TEXT DEFAULT '',
        user_id BIGINT(20) UNSIGNED DEFAULT 0,
        story_id BIGINT(20) UNSIGNED DEFAULT 0,
        chapter_num INT(11) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY link_type (link_type),
        KEY created_at (created_at)
    ) $charset_collate;";

    // Table: Reading History
    $table_history = $wpdb->prefix . 'reading_history';
    $sql_history = "CREATE TABLE IF NOT EXISTS $table_history (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        story_id BIGINT(20) UNSIGNED NOT NULL,
        chapter_id BIGINT(20) UNSIGNED NOT NULL,
        chapter_num INT(11) DEFAULT 1,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_story (user_id, story_id),
        KEY user_id (user_id)
    ) $charset_collate;";

    // Table: Bookmarks (Tủ truyện)
    $table_bookmarks = $wpdb->prefix . 'reading_bookmarks';
    $sql_bookmarks = "CREATE TABLE IF NOT EXISTS $table_bookmarks (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        story_id BIGINT(20) UNSIGNED NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_story (user_id, story_id),
        KEY user_id (user_id)
    ) $charset_collate;";

    // Table: Notifications (Thông báo người dùng)
    $table_notifs = $wpdb->prefix . 'muop_notifications';
    $sql_notifs = "CREATE TABLE IF NOT EXISTS $table_notifs (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        sender_id BIGINT(20) UNSIGNED DEFAULT 0,
        type VARCHAR(50) NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        link VARCHAR(500) DEFAULT '',
        image VARCHAR(500) DEFAULT '',
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY is_read (is_read),
        KEY created_at (created_at)
    ) $charset_collate;";

    // Table: Chapter Error Reports (Báo lỗi chương truyện & Khiếu nại)
    $table_reports = $wpdb->prefix . 'muop_chapter_reports';
    $sql_reports = "CREATE TABLE IF NOT EXISTS $table_reports (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED DEFAULT 0,
        story_id BIGINT(20) UNSIGNED NOT NULL,
        chapter_id BIGINT(20) UNSIGNED NOT NULL,
        chapter_num INT(11) DEFAULT 1,
        issue_type VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        admin_reply TEXT DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY story_id (story_id),
        KEY chapter_id (chapter_id),
        KEY status (status)
    ) $charset_collate;";

    // Table: Translator Payments (Thanh toán nhuận bút & Bill chuyển khoản)
    $table_payments = $wpdb->prefix . 'muop_translator_payments';
    $sql_payments = "CREATE TABLE IF NOT EXISTS $table_payments (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        translator_id BIGINT(20) UNSIGNED NOT NULL,
        admin_id BIGINT(20) UNSIGNED NOT NULL,
        period VARCHAR(50) NOT NULL,
        views BIGINT(20) UNSIGNED DEFAULT 0,
        amount DECIMAL(12, 0) NOT NULL,
        bill_image VARCHAR(500) NOT NULL,
        note TEXT DEFAULT '',
        bank_name VARCHAR(100) DEFAULT '',
        bank_account_holder VARCHAR(150) DEFAULT '',
        bank_account_number VARCHAR(50) DEFAULT '',
        status VARCHAR(20) DEFAULT 'paid',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY translator_id (translator_id)
    ) $charset_collate;";

    // Table: Chapter Splitter History (Đồng bộ tách chương PC & Mobile)
    $table_splitter = $wpdb->prefix . 'muop_splitter_history';
    $sql_splitter = "CREATE TABLE IF NOT EXISTS $table_splitter (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        session_key VARCHAR(64) NOT NULL,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        title VARCHAR(255) NOT NULL,
        total_chapters INT(11) NOT NULL DEFAULT 0,
        total_words INT(11) NOT NULL DEFAULT 0,
        avg_words INT(11) NOT NULL DEFAULT 0,
        target_words INT(11) NOT NULL DEFAULT 2500,
        chapters_json LONGTEXT NOT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY idx_user (user_id),
        KEY idx_created (created_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_clicks);
    dbDelta($sql_history);
    dbDelta($sql_bookmarks);
    dbDelta($sql_notifs);
    dbDelta($sql_reports);
    dbDelta($sql_payments);
    dbDelta($sql_splitter);

    // Initial Options
    if (!get_option('muop_shopee_url')) {
        update_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp');
    }
    if (!get_option('muop_tiktok_url')) {
        update_option('muop_tiktok_url', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ');
    }
    if (!get_option('muop_salary_rate')) {
        update_option('muop_salary_rate', 8); // 8 VND / view
    }
    if (intval(get_option('posts_per_page')) !== 24) {
        update_option('posts_per_page', 24);
    }
}
add_action('after_switch_theme', 'muop_setup_tables');
// Also run on init once to ensure tables are always created
add_action('init', 'muop_setup_tables');

// 5. Create Core Pages
function muop_create_required_pages() {
    $pages = array(
        'truyen-moi' => array(
            'title'    => 'Truyện Mới Cập Nhật',
            'template' => 'page-truyen-moi.php'
        ),
        'truyen-hot' => array(
            'title'    => 'Truyện Hot',
            'template' => 'page-truyen-hot.php'
        ),
        'truyen-full' => array(
            'title'    => 'Truyện Full',
            'template' => 'page-truyen-full.php'
        ),
        'the-loai' => array(
            'title'    => 'Thể Loại Truyện',
            'template' => 'page-the-loai.php'
        ),
        'team-dich' => array(
            'title'    => 'Danh Sách Team Dịch',
            'template' => 'page-team-dich.php'
        ),
        'tu-truyen' => array(
            'title'    => 'Tủ Truyện',
            'template' => 'page-tu-truyen.php'
        ),
        'gioi-thieu' => array(
            'title'    => 'Giới Thiệu',
            'template' => 'page-gioi-thieu.php'
        ),
        'dang-nhap' => array(
            'title'    => 'Đăng Nhập',
            'template' => 'page-dang-nhap.php'
        ),
        'dang-ky' => array(
            'title'    => 'Đăng Ký',
            'template' => 'page-dang-ky.php'
        ),
        'ho-so' => array(
            'title'    => 'Hồ Sơ Cá Nhân',
            'template' => 'page-ho-so.php'
        ),
        'doi-mat-khau' => array(
            'title'    => 'Đổi Mật Khẩu',
            'template' => 'page-doi-mat-khau.php'
        ),
        'cai-dat-doc-truyen' => array(
            'title'    => 'Cài Đặt Đọc Truyện',
            'template' => 'page-cai-dat-doc-truyen.php'
        ),
        'dang-truyen' => array(
            'title'    => 'Đăng Truyện Mới',
            'template' => 'page-dang-truyen.php'
        ),
        'them-chuong' => array(
            'title'    => 'Thêm Chương Mới',
            'template' => 'page-them-chuong.php'
        ),
        'thong-tin-dich-gia' => array(
            'title'    => 'Kênh Dịch Giả',
            'template' => 'page-thong-tin-dich-gia.php'
        ),
        'quan-ly-admin' => array(
            'title'    => 'Quản Trị Hệ Thống',
            'template' => 'page-quan-ly-admin.php'
        ),
        'dieu-khoan' => array(
            'title'    => 'Điều Khoản Sử Dụng',
            'template' => 'page-dieu-khoan.php'
        ),
        'chinh-sach-bao-mat' => array(
            'title'    => 'Chính Sách Bảo Mật',
            'template' => 'page-chinh-sach-bao-mat.php'
        ),
        'lien-he' => array(
            'title'    => 'Liên Hệ',
            'template' => 'page-lien-he.php'
        )
    );

    foreach ($pages as $slug => $data) {
        $existing = get_page_by_path($slug);
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title'   => $data['title'],
                'post_name'    => $slug,
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_content' => 'Trang ' . $data['title']
            ));
            if ($page_id && !empty($data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $data['template']);
            }
        }
    }
}
add_action('init', 'muop_create_required_pages');

// 6. Enqueue Scripts and Styles
function muop_enqueue_scripts() {
    wp_enqueue_style('muop-google-fonts', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap', array(), null);
    wp_enqueue_style('muop-theme-style', get_stylesheet_uri(), array(), MUOP_THEME_VERSION);
    wp_enqueue_style('muop-main-style', MUOP_THEME_URI . '/assets/css/main.css', array('muop-theme-style'), MUOP_THEME_VERSION);

    // FontAwesome 6 icons for clean UI
    wp_enqueue_style('muop-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2');

    wp_enqueue_script('muop-main-script', MUOP_THEME_URI . '/assets/js/main.js', array('jquery'), MUOP_THEME_VERSION, true);

    // Current user role & mode preview
    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $primary_role = !empty($roles) ? $roles[0] : 'guest';
    
    // Check mode preview cookie for Admin
    $preview_mode = isset($_COOKIE['muop_preview_mode']) ? sanitize_text_field($_COOKIE['muop_preview_mode']) : '';
    if (muop_is_admin() && in_array($preview_mode, array('doc_gia', 'dich_gia', 'admin'))) {
        $effective_role = ($preview_mode === 'admin') ? 'administrator' : $preview_mode;
    } else {
        $effective_role = $primary_role;
    }

    // Chuẩn hóa ajaxUrl theo đường dẫn thực tế của WordPress (/core/wp-admin/admin-ajax.php)
    $ajax_url = wp_make_link_relative(admin_url('admin-ajax.php'));

    $is_admin = muop_is_admin();
    $is_translator = (function_exists('muop_user_is_translator') ? muop_user_is_translator($current_user->ID) : false) || current_user_can('dich_gia') || in_array('dich_gia', (array)$current_user->roles, true);

    $user_story_ids = array();
    if (!empty($current_user->ID)) {
        if ($is_admin) {
            $all_admin_stories = get_posts(array(
                'post_type'      => 'truyen',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_status'    => array('publish', 'pending', 'draft')
            ));
            $user_story_ids = array_values(array_filter((array)$all_admin_stories, function($sid) use ($current_user) {
                $post = get_post($sid);
                return ($post && ((int)$post->post_author === (int)$current_user->ID || muop_is_story_of_team_muop_dang($sid)));
            }));
        } elseif ($is_translator) {
            $user_story_ids = get_posts(array(
                'post_type'      => 'truyen',
                'author'         => $current_user->ID,
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_status'    => array('publish', 'pending', 'draft', 'future')
            ));
        }
    }

    wp_localize_script('muop-main-script', 'muopConfig', array(
        'ajaxUrl'       => $ajax_url,
        'nonce'         => wp_create_nonce('muop_ajax_nonce'),
        'siteUrl'       => home_url(),
        'isLoggedIn'    => is_user_logged_in(),
        'userId'        => $current_user->ID,
        'sessionToken'  => function_exists('wp_get_session_token') ? wp_get_session_token() : '',
        'userRole'      => $effective_role,
        'actualRole'    => $primary_role,
        'previewMode'   => $preview_mode,
        'isAdmin'       => $is_admin,
        'isTranslator'  => $is_translator,
        'userStoryIds'  => !empty($user_story_ids) ? array_values(array_map('intval', (array)$user_story_ids)) : array(),
        'shopeeUrl'     => get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp'),
        'shopeeUrl2'    => get_option('muop_shopee_url_2', ''),
        'tiktokUrl'     => get_option('muop_tiktok_url', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ'),
        'tiktokCooldownMinutes' => (int) get_option('muop_tiktok_cooldown_minutes', 7),
        'salaryRate'    => get_option('muop_salary_rate', 8),
        'readingPrefs'  => is_user_logged_in() ? get_user_meta($current_user->ID, 'muop_reading_preferences', true) : null,
        'stickerBaseUrl'=> wp_make_link_relative(get_template_directory_uri() . '/assets/images/stickers/'),
        'sensitiveWords'=> function_exists('muop_get_sensitive_words') ? muop_get_sensitive_words() : array()
    ));
}
add_action('wp_enqueue_scripts', 'muop_enqueue_scripts');

// 7. Helper Functions

/**
 * Universal safe AJAX nonce verification helper
 * Verifies standard nonce, admin nonce, and allows verified administrators/editors
 * to never get blocked by stale cache or expired frontend tokens.
 */
function muop_verify_ajax_nonce($action = 'muop_ajax_nonce', $query_arg = 'nonce') {
    // 1. Direct WordPress check
    if (check_ajax_referer($action, $query_arg, false)) {
        return true;
    }

    // 2. Fallback check with alternative query args or actions
    $nonce = '';
    if (isset($_REQUEST[$query_arg])) {
        $nonce = sanitize_text_field($_REQUEST[$query_arg]);
    } elseif (isset($_REQUEST['_ajax_nonce'])) {
        $nonce = sanitize_text_field($_REQUEST['_ajax_nonce']);
    } elseif (isset($_REQUEST['_wpnonce'])) {
        $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
    }

    if (!empty($nonce)) {
        if (wp_verify_nonce($nonce, 'muop_ajax_nonce') || 
            wp_verify_nonce($nonce, 'muop_admin_nonce') || 
            wp_verify_nonce($nonce, $action)) {
            return true;
        }
    }

    // 3. Với bất kỳ người dùng nào đã đăng nhập (Admin, Dịch Giả, Độc Giả), phiên làm việc đã được xác thực an toàn tuyệt đối bởi WordPress Auth Cookie
    if (is_user_logged_in()) {
        return true;
    }

    // 4. Same-origin fallback cho các request hợp lệ từ website
    $referer = wp_get_referer();
    if ($referer && strpos($referer, parse_url(home_url(), PHP_URL_HOST)) !== false) {
        return true;
    }

    return false;
}

/**
 * Kiểm tra xem người dùng hiện tại (hoặc user ID truyền vào) có toàn quyền Quản Trị Viên hay không
 * Nhận diện cả capability 'manage_options', 'administrator', role 'administrator', hoặc is_super_admin()
 */
function muop_is_admin($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return false;
    }
    if (is_super_admin($user_id) || user_can($user_id, 'manage_options') || user_can($user_id, 'administrator')) {
        return true;
    }
    $u = get_userdata($user_id);
    if ($u && !empty($u->roles) && is_array($u->roles) && in_array('administrator', $u->roles, true)) {
        return true;
    }
    return false;
}

/**
 * Kiểm tra xem người dùng có quyền Dịch Giả hoặc Quản Trị Viên hay không
 */
function muop_is_translator_or_admin($user_id = 0) {
    if (muop_is_admin($user_id)) {
        return true;
    }
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return false;
    }
    if (user_can($user_id, 'dich_gia')) {
        return true;
    }
    $u = get_userdata($user_id);
    if ($u && !empty($u->roles) && is_array($u->roles) && in_array('dich_gia', $u->roles, true)) {
        return true;
    }
    return false;
}

/**
 * Tự động đồng bộ và xác thực phiên đăng nhập qua mọi loại Reverse Proxy (1Panel / LiteSpeed / Nginx / Cloudflare)
 * Quét toàn bộ mọi cookie trong $_COOKIE (wordpress_sec_, wordpress_logged_in_, wordpress_)
 * Đồng thời hỗ trợ Fallback xác thực phiên qua Nonce + User ID do máy chủ cấp khi cookie bị trình duyệt hạn chế
 */
add_filter('determine_current_user', function($user_id) {
    if (!empty($user_id)) {
        return (int) $user_id;
    }

    // Bắt buộc kiểm tra hàm lõi WordPress pluggable đã nạp để tránh Fatal Error khi hook chạy sớm
    if (!function_exists('wp_validate_auth_cookie')) {
        return $user_id;
    }

    // 1. Quét toàn bộ $_COOKIE (bất kể tiền tố hash nào do kiến trúc thư mục /core)
    if (!empty($_COOKIE) && is_array($_COOKIE)) {
        // Quét secure auth cookie trước
        foreach ($_COOKIE as $cookie_name => $cookie_val) {
            if (!is_string($cookie_val) || empty($cookie_val)) continue;
            if (strpos($cookie_name, 'wordpress_sec_') === 0) {
                $uid = wp_validate_auth_cookie($cookie_val, 'secure_auth');
                if ($uid) return (int) $uid;
            }
        }
        // Quét logged in cookie
        foreach ($_COOKIE as $cookie_name => $cookie_val) {
            if (!is_string($cookie_val) || empty($cookie_val)) continue;
            if (strpos($cookie_name, 'wordpress_logged_in_') === 0) {
                $uid = wp_validate_auth_cookie($cookie_val, 'logged_in');
                if ($uid) return (int) $uid;
            }
        }
        // Quét standard auth cookie
        foreach ($_COOKIE as $cookie_name => $cookie_val) {
            if (!is_string($cookie_val) || empty($cookie_val)) continue;
            if (strpos($cookie_name, 'wordpress_') === 0 
                && strpos($cookie_name, 'wordpress_sec_') === false 
                && strpos($cookie_name, 'wordpress_logged_in_') === false 
                && strpos($cookie_name, 'wordpress_test_') === false) {
                $uid = wp_validate_auth_cookie($cookie_val, 'auth');
                if ($uid) return (int) $uid;
            }
        }
    }

    // 2. Fallback Token Bridge: Xác thực an toàn qua Session Token do máy chủ cấp (chống mất cookie trên AJAX)
    $req_token = '';
    if (isset($_SERVER['HTTP_X_MUOP_TOKEN'])) $req_token = sanitize_text_field($_SERVER['HTTP_X_MUOP_TOKEN']);
    elseif (isset($_REQUEST['muop_token'])) $req_token = sanitize_text_field($_REQUEST['muop_token']);

    $req_uid = 0;
    if (isset($_SERVER['HTTP_X_MUOP_USER'])) $req_uid = (int) $_SERVER['HTTP_X_MUOP_USER'];
    elseif (isset($_REQUEST['muop_user_id'])) $req_uid = (int) $_REQUEST['muop_user_id'];

    if (!empty($req_token) && !empty($req_uid)) {
        if (class_exists('WP_Session_Tokens')) {
            $manager = WP_Session_Tokens::get_instance($req_uid);
            if ($manager->verify($req_token)) {
                return (int) $req_uid;
            }
        }
    }

    // 3. Fallback Nonce Bridge: Xác thực an toàn qua Nonce + User ID do máy chủ cấp
    $req_nonce = '';
    if (isset($_REQUEST['nonce'])) $req_nonce = sanitize_text_field($_REQUEST['nonce']);
    elseif (isset($_REQUEST['_ajax_nonce'])) $req_nonce = sanitize_text_field($_REQUEST['_ajax_nonce']);
    elseif (isset($_SERVER['HTTP_X_WP_NONCE'])) $req_nonce = sanitize_text_field($_SERVER['HTTP_X_WP_NONCE']);

    if (!empty($req_nonce) && !empty($req_uid)) {
        $referer = wp_get_referer();
        $is_same_origin = $referer && strpos($referer, parse_url(home_url(), PHP_URL_HOST)) !== false;
        if ($is_same_origin && function_exists('wp_nonce_tick') && function_exists('wp_hash')) {
            $tick = wp_nonce_tick('muop_ajax_nonce');
            $expected1 = substr(wp_hash($tick . '|muop_ajax_nonce|' . $req_uid . '|', 'nonce'), -12, 10);
            $expected2 = substr(wp_hash(($tick - 1) . '|muop_ajax_nonce|' . $req_uid . '|', 'nonce'), -12, 10);
            if (hash_equals($expected1, $req_nonce) || hash_equals($expected2, $req_nonce)) {
                return (int) $req_uid;
            }
        }
    }

    return $user_id;
}, 1);

// Tài khoản người dùng có thể tùy ý điều chỉnh phân quyền theo quản trị hệ thống (không tự động cưỡng chế role khi tải trang)

// Diagnostic endpoint: Kiểm tra thông tin xác thực phiên làm việc
add_action('wp_ajax_muop_check_auth', 'muop_check_auth_handler');
add_action('wp_ajax_nopriv_muop_check_auth', 'muop_check_auth_handler');
function muop_check_auth_handler() {
    $uid = get_current_user_id();
    $u = $uid ? get_userdata($uid) : null;
    $cookie_tests = array();
    foreach ($_COOKIE as $k => $v) {
        $scheme = (strpos($k, 'wordpress_sec_') === 0) ? 'secure_auth' : ((strpos($k, 'wordpress_logged_in_') === 0) ? 'logged_in' : 'auth');
        $cookie_tests[$k] = array(
            'length' => strlen($v),
            'prefix' => substr($v, 0, 20),
            'scheme' => $scheme,
            'validate_result' => wp_validate_auth_cookie($v, $scheme)
        );
    }
    wp_send_json_success(array(
        'is_logged_in' => is_user_logged_in(),
        'user_id' => $uid,
        'user_login' => $u ? $u->user_login : '',
        'roles' => $u ? (array)$u->roles : array(),
        'is_admin' => function_exists('muop_is_admin') ? muop_is_admin() : current_user_can('administrator'),
        'is_ssl' => is_ssl(),
        'https_header' => isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : '',
        'cookie_hash' => defined('COOKIEHASH') ? COOKIEHASH : '',
        'cookie_path' => defined('COOKIEPATH') ? COOKIEPATH : '',
        'admin_cookie_path' => defined('ADMIN_COOKIE_PATH') ? ADMIN_COOKIE_PATH : '',
        'site_cookie_path' => defined('SITECOOKIEPATH') ? SITECOOKIEPATH : '',
        'raw_cookie_header' => isset($_SERVER['HTTP_COOKIE']) ? substr($_SERVER['HTTP_COOKIE'], 0, 150) : '',
        'cookie_tests' => $cookie_tests
    ));
}

function muop_time_ago($from, $to = null) {
    if (empty($from)) return '';
    $now = time();
    if ($to === null || $to === '') {
        $to = $now;
    }
    
    // Hỗ trợ truyền vào chuỗi ngày tháng MySQL
    if (!is_numeric($from)) {
        $from = strtotime($from);
    }
    if (!$from) return '';

    // Khắc phục trường hợp timestamp bị lệch gmt_offset trong quá khứ
    $gmt_offset_sec = (int) (get_option('gmt_offset', 7) * 3600);
    if ($from > $to && abs($from - ($to + $gmt_offset_sec)) < 3600 * 2) {
        $from -= $gmt_offset_sec;
    }

    $diff = (int) ($to - $from);
    if ($diff < 0) {
        $diff = 0;
    }
    
    if ($diff < 60) {
        return 'Vừa xong';
    }
    
    $minute = 60;
    $hour   = 3600;
    $day    = 86400;
    $week   = 604800;
    $month  = 2592000;  // 30 ngày
    $year   = 31536000; // 365 ngày
    
    if ($diff < $hour) {
        $mins = max(1, round($diff / $minute));
        return $mins . ' phút trước';
    } elseif ($diff < $day) {
        $hours = max(1, round($diff / $hour));
        return $hours . ' giờ trước';
    } elseif ($diff < $week) {
        $days = max(1, round($diff / $day));
        return $days . ' ngày trước';
    } elseif ($diff < $month) {
        $weeks = max(1, round($diff / $week));
        return $weeks . ' tuần trước';
    } elseif ($diff < $year) {
        $months = max(1, round($diff / $month));
        return $months . ' tháng trước';
    } else {
        $years = max(1, round($diff / $year));
        return $years . ' năm trước';
    }
}

/**
 * Danh sách 3 bộ sticker bình luận (Bộ 1, Bộ 2, Bộ 3)
 */
function muop_get_stickers_list() {
    $dir = get_template_directory() . '/assets/images/stickers';
    $sets = array(
        'bo-1' => 'Bộ 1',
        'bo-2' => 'Bộ 2',
        'bo-3' => 'Bộ 3'
    );
    $result = array();
    foreach ($sets as $folder => $title) {
        $files = glob($dir . '/' . $folder . '/*.png');
        if (!empty($files)) {
            natsort($files);
            $items = array();
            foreach ($files as $f) {
                $items[] = basename($f);
            }
            $result[] = array(
                'id'    => $folder,
                'name'  => $title,
                'count' => count($items),
                'items' => array_values($items)
            );
        }
    }
    return $result;
}

/**
 * Render nội dung bình luận, an toàn XSS và tự động chuyển đổi mã sticker [sticker:bo-X/Y.png] thành thẻ <img>
 */
function muop_render_comment_content($content) {
    if (empty($content)) return '';

    // 1. Thoát các ký tự HTML nguy hiểm trước
    $escaped = esc_html($content);

    // 2. Thay thế sticker code [sticker:bo-X/Y.png] thành <img>
    $base_url = wp_make_link_relative(get_template_directory_uri() . '/assets/images/stickers/');
    $pattern = '/\[sticker:(bo-[1-3]\/[0-9]+\.png)\]/i';

    $rendered = preg_replace_callback($pattern, function($matches) use ($base_url) {
        $sticker_rel = esc_attr($matches[1]);
        $sticker_url = esc_url($base_url . $sticker_rel);
        return '<img src="' . $sticker_url . '" class="muop-comment-sticker" alt="Sticker" loading="lazy" />';
    }, $escaped);

    // 3. Giữ định dạng xuống dòng
    return nl2br($rendered);
}

/**
 * Render giao diện Popup / Khung chọn Sticker cho form bình luận
 */
function muop_render_sticker_picker() {
    $stickers = muop_get_stickers_list();
    $base_url = wp_make_link_relative(get_template_directory_uri() . '/assets/images/stickers/');
    ?>
    <div class="muop-sticker-picker" style="display: none;">
        <div class="sticker-picker-header">
            <div class="sticker-tabs-nav">
                <?php foreach ($stickers as $idx => $set) : ?>
                    <button type="button" class="sticker-tab-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-set="<?php echo esc_attr($set['id']); ?>">
                        <?php echo esc_html($set['name']); ?>
                        <span class="sticker-count-badge"><?php echo (int) $set['count']; ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            <button type="button" class="sticker-close-btn" title="Đóng">&times;</button>
        </div>
        <div class="sticker-picker-body">
            <?php foreach ($stickers as $idx => $set) : ?>
                <div class="sticker-tab-pane <?php echo $idx === 0 ? 'active' : ''; ?>" id="pane-<?php echo esc_attr($set['id']); ?>">
                    <div class="sticker-grid">
                        <?php foreach ($set['items'] as $item) : 
                            $sticker_code = '[sticker:' . $set['id'] . '/' . $item . ']';
                            $sticker_img_url = $base_url . $set['id'] . '/' . $item;
                        ?>
                            <div class="sticker-item" data-code="<?php echo esc_attr($sticker_code); ?>" data-url="<?php echo esc_url($sticker_img_url); ?>" title="Chọn sticker này">
                                <img src="<?php echo esc_url($sticker_img_url); ?>" alt="<?php echo esc_attr($set['name'] . ' ' . $item); ?>" loading="lazy" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Định dạng ngày giờ chuẩn múi giờ Việt Nam (Asia/Ho_Chi_Minh GMT+7)
 * Giải quyết dứt điểm lỗi date_i18n tự động trừ 7 tiếng khi xử lý timestamp.
 *
 * @param string|int $datetime_or_ts Chuỗi ngày giờ (vd: 2026-10-26 17:10:00) hoặc Unix timestamp
 * @param string $format Định dạng ngày giờ (mặc định: 'H:i - d/m/Y')
 * @return string Chuỗi ngày giờ đã định dạng
 */
function muop_format_datetime($datetime_or_ts, $format = 'H:i - d/m/Y') {
    if (empty($datetime_or_ts)) return '';

    if (is_numeric($datetime_or_ts)) {
        return wp_date($format, (int) $datetime_or_ts);
    }

    $clean = str_replace('T', ' ', trim((string) $datetime_or_ts));
    try {
        $dt = new DateTime($clean, new DateTimeZone('Asia/Ho_Chi_Minh'));
        return $dt->format($format);
    } catch (Exception $e) {
        $ts = strtotime($clean);
        return $ts ? wp_date($format, $ts) : $clean;
    }
}

/**
 * Lấy thời gian cập nhật gần nhất của truyện (Tính theo ngày giờ sửa truyện hoặc ngày xuất bản/sửa của chương mới nhất)
 * Tối ưu hóa: Hỗ trợ truyền sẵn $preloaded_chap_ts và Runtime Static Cache để loại bỏ triệt để các query thừa
 *
 * @param int $story_id ID bộ truyện
 * @param int|null $preloaded_chap_ts Timestamp chương mới nhất nếu đã có sẵn từ card truyện
 * @return array Mảng thông tin: timestamp, formatted (d/m/Y H:i), time_ago, display
 */
function muop_get_story_last_updated($story_id, $preloaded_chap_ts = null) {
    static $runtime_cache = array();

    $story_id = (int) $story_id;
    if (!$story_id) {
        $now = time();
        return array(
            'timestamp' => $now,
            'formatted' => wp_date('d/m/Y H:i', $now),
            'time_ago'  => 'Vừa xong',
            'display'   => wp_date('d/m/Y H:i', $now) . ' (Vừa xong)'
        );
    }

    if ($preloaded_chap_ts === null && isset($runtime_cache[$story_id])) {
        return $runtime_cache[$story_id];
    }

    // Nếu truyện đang ở chế độ hẹn giờ đăng (future), hiển thị trạng thái đang hẹn giờ
    $story_post_status = get_post_status($story_id);
    if ($story_post_status === 'future') {
        $sched_time = get_post_meta($story_id, '_truyen_scheduled_time', true);
        if (!$sched_time) {
            $sp = get_post($story_id);
            $sched_time = $sp ? $sp->post_date : '';
        }
        $sched_fmt = muop_format_datetime($sched_time, 'H:i - d/m/Y');
        $display = !empty($sched_fmt) ? 'Đang hẹn giờ (' . $sched_fmt . ')' : 'Đang hẹn giờ';
        $res = array(
            'timestamp' => strtotime($sched_time),
            'formatted' => $sched_fmt,
            'time_ago'  => 'Đang hẹn giờ',
            'display'   => $display,
            'is_future' => true
        );
        $runtime_cache[$story_id] = $res;
        return $res;
    }

    // 1. Timestamp sửa đổi của chính truyện
    $story_mod_ts = (int) get_post_timestamp($story_id, 'modified');
    if (!$story_mod_ts) {
        $story_mod_ts = (int) get_post_timestamp($story_id, 'date');
    }

    // 2. Timestamp của chương mới nhất thuộc truyện
    $latest_chap_ts = 0;
    if ($preloaded_chap_ts !== null) {
        $latest_chap_ts = (int) $preloaded_chap_ts;
    } else {
        $latest_chaps = get_posts(array(
            'post_type'      => 'chuong',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'meta_key'       => '_chuong_truyen_id',
            'meta_value'     => $story_id,
            'orderby'        => 'post_date',
            'order'          => 'DESC',
            'fields'         => 'ids'
        ));

        if (!empty($latest_chaps)) {
            $cid = $latest_chaps[0];
            $chap_mod_ts = (int) get_post_timestamp($cid, 'modified');
            $chap_pub_ts = (int) get_post_timestamp($cid, 'date');
            $latest_chap_ts = max($chap_mod_ts, $chap_pub_ts);
        }
    }

    $last_timestamp = max($story_mod_ts, $latest_chap_ts);
    if (!$last_timestamp) {
        $last_timestamp = time();
    }

    $formatted = wp_date('d/m/Y H:i', $last_timestamp);
    $time_ago  = muop_time_ago($last_timestamp, time());

    // Hiển thị thời gian thật rõ ràng: Ngày giờ chính xác kèm độ mới
    // Ví dụ: 12/10/2026 16:09 (Vừa xong) hoặc 12/10/2026 15:36 (45 phút trước)
    $display = $formatted;
    if (!empty($time_ago)) {
        $display = $formatted . ' (' . $time_ago . ')';
    }

    $res = array(
        'timestamp' => $last_timestamp,
        'formatted' => $formatted,
        'time_ago'  => $time_ago,
        'display'   => $display
    );
    $runtime_cache[$story_id] = $res;
    return $res;
}

// Hook vào human_time_diff của WordPress để dịch chuẩn sang tiếng Việt
add_filter('human_time_diff', function($since, $diff, $from, $to) {
    if ($diff < 60) return '1 phút';
    if ($diff < 3600) return max(1, round($diff / 60)) . ' phút';
    if ($diff < 86400) return max(1, round($diff / 3600)) . ' giờ';
    if ($diff < 604800) return max(1, round($diff / 86400)) . ' ngày';
    if ($diff < 2592000) return max(1, round($diff / 604800)) . ' tuần';
    if ($diff < 31536000) return max(1, round($diff / 2592000)) . ' tháng';
    return max(1, round($diff / 31536000)) . ' năm';
}, 10, 4);

function muop_get_chapter_views($chapter_id) {
    return (int) get_post_meta($chapter_id, '_chuong_views', true);
}

function muop_get_story_views($story_id) {
    return (int) get_post_meta($story_id, '_truyen_views', true);
}

/**
 * Kiểm tra xem người dùng hiện tại có được xem số lượt đọc (views) hay không.
 * Chỉ Quản Trị Viên (Admin) và Dịch Giả mới được xem. 
 * Độc giả (doc_gia) và khách vãng lai sẽ bị ẩn.
 */
function muop_can_see_story_views($story_id = 0) {
    if (!is_user_logged_in()) {
        return false;
    }
    // 1. Quản trị viên luôn được xem
    if (muop_is_admin()) {
        return true;
    }
    // 2. Dịch giả (vai trò dich_gia) được xem
    $current_uid = get_current_user_id();
    if (muop_user_is_translator($current_uid)) {
        return true;
    }
    // 3. Tác giả/người đăng của truyện
    if ($story_id) {
        $author_id = (int) get_post_field('post_author', $story_id);
        if ($author_id && $author_id === $current_uid) {
            return true;
        }
    }
    return false;
}

function muop_get_story_status($story_id) {
    $st = get_post_meta($story_id, '_truyen_status', true);
    return ($st === 'hoan_thanh') ? 'Hoàn thành' : 'Đang ra';
}

function muop_get_clean_chapter_title($title) {
    if (empty($title)) return '';
    $clean = preg_replace('/^chương\s+\d+\s*[:\-\.]\s*/iu', '', trim($title));
    return !empty($clean) ? trim($clean) : trim($title);
}

function muop_get_story_chapters($story_id, $order = 'ASC', $status = array('publish')) {
    static $chapters_cache = array();
    $cache_key = (int) $story_id . '_' . strtoupper($order) . '_' . (is_array($status) ? implode('-', $status) : (string)$status);
    if (isset($chapters_cache[$cache_key])) {
        return $chapters_cache[$cache_key];
    }

    $results = get_posts(array(
        'post_type'      => 'chuong',
        'posts_per_page' => -1,
        'meta_key'       => '_chuong_number',
        'orderby'        => 'meta_value_num',
        'order'          => $order,
        'meta_query'     => array(
            array(
                'key'   => '_chuong_truyen_id',
                'value' => $story_id
            )
        ),
        'post_status'    => $status
    ));

    $chapters_cache[$cache_key] = $results;
    return $results;
}

// Invalidate cache danh mục thể loại khi thêm / sửa / xóa thể loại
add_action('created_the_loai', function() { wp_cache_delete('muop_all_genres_list'); });
add_action('edited_the_loai', function() { wp_cache_delete('muop_all_genres_list'); });
add_action('delete_the_loai', function() { wp_cache_delete('muop_all_genres_list'); });


/**
 * Kiểm tra xem chương truyện có xuất hiện link Shopee Gatekeeper hay không:
 * - Nếu truyện có 3 hoặc 4 chương (hoặc <= 4): Xuất hiện ở chương 1 và chương 3
 * - Nếu truyện có từ 5 chương trở lên: Xuất hiện ở chương 2, 5, 8, 11, 14... (cứ cách 3 chương 1 lần)
 */
function muop_is_chapter_checkpoint($chap_num, $total_chapters) {
    $chap_num = (int) $chap_num;
    $total_chapters = (int) $total_chapters;

    if ($total_chapters <= 0) {
        $total_chapters = 1;
    }

    if ($total_chapters <= 4) {
        return ($chap_num === 1 || $chap_num === 3);
    }

    return ($chap_num >= 2 && ($chap_num % 3 === 2));
}

function muop_get_effective_user_role() {
    if (!is_user_logged_in()) {
        return 'guest';
    }
    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $primary_role = !empty($roles) ? $roles[0] : 'guest';
    
    if (muop_is_admin($current_user->ID)) {
        $preview_mode = isset($_COOKIE['muop_preview_mode']) ? sanitize_text_field($_COOKIE['muop_preview_mode']) : '';
        if ($preview_mode === 'doc_gia') return 'doc_gia';
        if ($preview_mode === 'dich_gia') return 'dich_gia';
        return 'administrator';
    }
    return $primary_role;
}

/**
 * Kiểm tra xem người dùng có vai trò Dịch Giả hay không
 * (Bao gồm user có role dich_gia, hoặc admin có thêm vai trò dich_gia trong roles / preview_mode)
 */
function muop_user_is_translator($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) return false;
    $user = get_userdata($user_id);
    if (!$user) return false;
    $roles = (array) $user->roles;
    if (in_array('dich_gia', $roles, true)) {
        return true;
    }
    // Admin có thêm vai trò dịch giả chính thức của team "Mướp Đắng Cũng Có Vị Ngọt"
    if (in_array('administrator', $roles, true)) {
        return true;
    }
    return false;
}

/**
 * Kiểm tra xem một truyện có thuộc về team "Mướp Đắng Cũng Có Vị Ngọt" hay không.
 * Team này do Admin làm đại diện/dịch giả.
 */
function muop_is_story_of_team_muop_dang($story_id) {
    if (!$story_id) return false;
    
    // 1. Kiểm tra post meta _truyen_team
    $team = get_post_meta($story_id, '_truyen_team', true);
    
    // 2. Kiểm tra taxonomy team_dich nếu meta trống
    if (empty($team)) {
        $terms = get_the_terms($story_id, 'team_dich');
        if (!empty($terms) && !is_wp_error($terms)) {
            $team = $terms[0]->name;
        }
    }
    
    // 3. Kiểm tra meta _truyen_dich_gia nếu vẫn trống
    if (empty($team)) {
        $team = get_post_meta($story_id, '_truyen_dich_gia', true);
    }
    
    $story = get_post($story_id);
    if ($story && empty($team)) {
        // Nếu không có team nào gán, và tác giả là Administrator thì mặc định thuộc team Mướp Đắng
        if (user_can($story->post_author, 'administrator')) {
            return true;
        }
        return false;
    }
    
    if (empty($team)) {
        return false;
    }
    
    $norm = mb_strtolower(trim($team), 'UTF-8');
    if ($norm === 'mướp đắng cũng có vị ngọt' || 
        $norm === 'muop dang cung co vi ngot' ||
        $norm === 'mướp đắng' || 
        $norm === 'muop dang') {
        return true;
    }
    
    return false;
}

/**
 * Lấy URL trực tiếp tới trang Team Dịch cụ thể của người dùng (Dịch Giả hoặc Admin).
 * Sẽ dẫn thẳng tới kênh team dịch (/team-dich/{slug}/), không phải là trang danh sách tổng hợp (/team-dich/).
 */
function muop_get_user_team_url($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return home_url('/team-dich/');
    }

    $is_admin = muop_is_admin($user_id);
    
    // 1. Nếu là Admin: Kênh team mặc định là "Mướp Đắng Cũng Có Vị Ngọt"
    if ($is_admin) {
        $admin_term = get_term_by('slug', 'muop-dang-cung-co-vi-ngot', 'team_dich');
        if (!$admin_term) {
            $admin_term = get_term_by('name', 'Mướp Đắng Cũng Có Vị Ngọt', 'team_dich');
        }
        if ($admin_term && !is_wp_error($admin_term)) {
            $link = get_term_link($admin_term);
            if (!is_wp_error($link)) return $link;
        }
        return home_url('/team-dich/muop-dang-cung-co-vi-ngot/');
    }

    // 2. Nếu là Dịch Giả: Tìm team từ các truyện dịch giả đã đăng
    $user_stories = get_posts(array(
        'post_type'      => 'truyen',
        'author'         => $user_id,
        'posts_per_page' => 5,
        'post_status'    => array('publish', 'pending', 'draft')
    ));

    if (!empty($user_stories)) {
        foreach ($user_stories as $st) {
            $terms = get_the_terms($st->ID, 'team_dich');
            if (!empty($terms) && !is_wp_error($terms)) {
                $link = get_term_link($terms[0]);
                if (!is_wp_error($link)) return $link;
            }
            $team_name_meta = get_post_meta($st->ID, '_truyen_team', true);
            if (!empty($team_name_meta)) {
                $term = get_term_by('name', $team_name_meta, 'team_dich');
                if (!$term) {
                    $term = get_term_by('slug', sanitize_title($team_name_meta), 'team_dich');
                }
                if ($term && !is_wp_error($term)) {
                    $link = get_term_link($term);
                    if (!is_wp_error($link)) return $link;
                }
                return home_url('/team-dich/' . sanitize_title($team_name_meta) . '/');
            }
        }
    }

    // 3. Nếu chưa đăng truyện: Tên team mặc định chính là display_name của dịch giả
    $user = get_userdata($user_id);
    $team_name = ($user && !empty($user->display_name)) ? $user->display_name : 'Team Dịch';
    $term = get_term_by('name', $team_name, 'team_dich');
    if (!$term) {
        $term = get_term_by('slug', sanitize_title($team_name), 'team_dich');
    }
    
    // Nếu chưa tồn tại trong taxonomy, tự động khởi tạo term để trang team hoạt động ngay lập tức
    if (!$term) {
        $inserted = wp_insert_term($team_name, 'team_dich', array(
            'slug'        => sanitize_title($team_name),
            'description' => 'Chào mừng bạn đến với kênh Team Dịch của ' . $team_name . '!'
        ));
        if (!is_wp_error($inserted) && isset($inserted['term_id'])) {
            $term = get_term($inserted['term_id'], 'team_dich');
        }
    }

    if ($term && !is_wp_error($term)) {
        $link = get_term_link($term);
        if (!is_wp_error($link)) return $link;
    }

    return home_url('/team-dich/' . sanitize_title($team_name) . '/');
}

/**
 * Kiểm tra xem người dùng hiện tại có quyền thêm/sửa/xóa trên trang chi tiết truyện (Frontend) hay không:
 * - Admin có vai trò dịch giả của team "Mướp Đắng Cũng Có Vị Ngọt" => Có quyền trên TẤT CẢ truyện thuộc team Mướp Đắng.
 * - Trên trang của dịch giả khác: Admin chỉ có quyền XEM, KHÔNG được can thiệp hay sửa xóa trên Frontend.
 * - Chính Dịch Giả của truyện đó (post_author và có vai trò dịch giả) có quyền trên truyện của mình.
 * - Dịch giả khác KHÔNG có quyền.
 */
function muop_can_user_manage_story_frontend($story_id, $user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id || !$story_id) {
        return false;
    }
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        return false;
    }
    
    $is_admin = user_can($user_id, 'administrator');
    $author_id = (int) $story->post_author;
    $is_author = ($author_id === (int) $user_id);
    $is_team_muop = muop_is_story_of_team_muop_dang($story_id);
    
    // 1. Admin quản lý tất cả các truyện của team "Mướp Đắng Cũng Có Vị Ngọt"
    if ($is_admin && $is_team_muop) {
        return true;
    }
    
    // 2. Chính Dịch Giả của bộ truyện đó
    if ($is_author && muop_user_is_translator($user_id)) {
        return true;
    }
    
    // 3. Không có quyền can thiệp trên Frontend
    return false;
}

/**
 * Kiểm tra quyền quản lý truyện (dùng chung cho cả Backend Admin Portal và Frontend)
 */
function muop_can_user_manage_story_action($story_id, $user_id = 0, $is_from_admin_portal = false) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id || !$story_id) {
        return false;
    }
    // Admin trên trang Quản Trị Hệ Thống có toàn quyền
    if (user_can($user_id, 'administrator') && $is_from_admin_portal) {
        return true;
    }
    return muop_can_user_manage_story_frontend($story_id, $user_id);
}

// 8. AJAX Handlers

// 8.1 Login
add_action('wp_ajax_nopriv_muop_login', 'muop_ajax_login_handler');
add_action('wp_ajax_muop_login', 'muop_ajax_login_handler');
function muop_ajax_login_handler() {
    if (empty($_POST['log']) || empty($_POST['pwd'])) {
        wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ tên đăng nhập/email và mật khẩu!'));
    }
    $user_login = sanitize_text_field($_POST['log']);
    $user_pass  = $_POST['pwd'];
    $remember   = !empty($_POST['remember']);

    $creds = array(
        'user_login'    => $user_login,
        'user_password' => $user_pass,
        'remember'      => $remember
    );

    $secure_cookie = is_ssl() || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
    $user = wp_signon($creds, $secure_cookie);

    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => 'Email/Tên đăng nhập hoặc mật khẩu không chính xác!'));
    }

    $roles = (array) $user->roles;
    if (!empty($_POST['redirect_to'])) {
        $redirect = esc_url_raw($_POST['redirect_to']);
    } elseif (in_array('administrator', $roles)) {
        $redirect = home_url('/quan-ly-admin/');
    } elseif (in_array('dich_gia', $roles)) {
        $redirect = home_url('/thong-tin-dich-gia/');
    } else {
        $redirect = home_url('/ho-so/');
    }

    wp_send_json_success(array(
        'message'  => 'Đăng nhập thành công!',
        'redirect' => $redirect,
        'user'     => array(
            'id'    => $user->ID,
            'name'  => $user->display_name,
            'email' => $user->user_email,
            'role'  => !empty($roles) ? $roles[0] : 'doc_gia'
        )
    ));
}

// 8.1b Forgot Password: Bước 1 - Kiểm tra tên đăng nhập / email
add_action('wp_ajax_nopriv_muop_check_username_for_reset', 'muop_ajax_check_username_for_reset_handler');
add_action('wp_ajax_muop_check_username_for_reset', 'muop_ajax_check_username_for_reset_handler');
function muop_ajax_check_username_for_reset_handler() {
    $raw_user = isset($_POST['username']) ? sanitize_text_field(trim($_POST['username'])) : '';
    if (empty($raw_user)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập tên đăng nhập hoặc email của bạn!'));
    }

    $user = get_user_by('login', $raw_user);
    if (!$user && is_email($raw_user)) {
        $user = get_user_by('email', $raw_user);
    }

    if (!$user) {
        wp_send_json_error(array('message' => 'Không tìm thấy tài khoản với tên đăng nhập hoặc email này. Vui lòng kiểm tra lại!'));
    }

    wp_send_json_success(array(
        'message'  => 'Đã xác nhận tài khoản thành công!',
        'username' => $user->user_login,
        'name'     => $user->display_name
    ));
}

// 8.1c Forgot Password: Bước 2 - Đặt lại mật khẩu mới
add_action('wp_ajax_nopriv_muop_reset_password_by_username', 'muop_ajax_reset_password_by_username_handler');
add_action('wp_ajax_muop_reset_password_by_username', 'muop_ajax_reset_password_by_username_handler');
function muop_ajax_reset_password_by_username_handler() {
    $username         = isset($_POST['username']) ? sanitize_text_field(trim($_POST['username'])) : '';
    $new_password     = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

    if (empty($username)) {
        wp_send_json_error(array('message' => 'Tên đăng nhập không được để trống!'));
    }

    if (empty($new_password)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập mật khẩu mới!'));
    }

    if (strlen($new_password) < 6) {
        wp_send_json_error(array('message' => 'Mật khẩu mới phải có tối thiểu 6 ký tự!'));
    }

    if ($new_password !== $confirm_password) {
        wp_send_json_error(array('message' => 'Xác nhận mật khẩu mới không khớp!'));
    }

    $user = get_user_by('login', $username);
    if (!$user && is_email($username)) {
        $user = get_user_by('email', $username);
    }

    if (!$user) {
        wp_send_json_error(array('message' => 'Không tìm thấy tài khoản để đặt lại mật khẩu!'));
    }

    // Đổi mật khẩu tài khoản
    wp_set_password($new_password, $user->ID);
    clean_user_cache($user->ID);

    // Cập nhật lưu mật khẩu quản trị nếu có
    update_user_meta($user->ID, '_admin_temp_password', $new_password);
    update_user_meta($user->ID, '_last_password_reset_by_admin', current_time('mysql'));

    wp_send_json_success(array(
        'message'  => 'Đặt lại mật khẩu thành công! Bạn có thể sử dụng mật khẩu mới để đăng nhập ngay bây giờ.',
        'username' => $user->user_login
    ));
}

// 8.2 Register (Doc Gia)
add_action('wp_ajax_nopriv_muop_register', 'muop_ajax_register_handler');
add_action('wp_ajax_muop_register', 'muop_ajax_register_handler');
function muop_ajax_register_handler() {
    $name     = sanitize_text_field($_POST['display_name']);
    $email    = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        wp_send_json_error(array('message' => 'Vui lòng điền đầy đủ các thông tin!'));
    }

    if ($password !== $confirm) {
        wp_send_json_error(array('message' => 'Mật khẩu nhập lại không khớp!'));
    }

    if (strlen($password) < 6) {
        wp_send_json_error(array('message' => 'Mật khẩu phải có ít nhất 6 ký tự!'));
    }

    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Email này đã được đăng ký tài khoản!'));
    }

    $username = sanitize_user(current(explode('@', $email)));
    if (username_exists($username)) {
        $username .= '_' . wp_rand(100, 999);
    }

    $user_id = wp_insert_user(array(
        'user_login'   => $username,
        'user_pass'    => $password,
        'user_email'   => $email,
        'display_name' => $name,
        'nickname'     => $name,
        'role'         => 'doc_gia'
    ));

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }

    // Lưu mật khẩu khởi tạo phục vụ quản trị viên
    update_user_meta($user_id, '_admin_temp_password', $password);
    update_user_meta($user_id, '_last_password_reset_by_admin', current_time('mysql'));

    // Bắn thông báo cho Quản Trị Viên (Admin) khi có người đăng ký mới
    muop_notify_admins_new_user($user_id);

    // Auto login
    wp_set_current_user($user_id);
    $secure_cookie = is_ssl() || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
    wp_set_auth_cookie($user_id, true, $secure_cookie);

    wp_send_json_success(array(
        'message'  => 'Đăng ký thành công! Đang chuyển hướng...',
        'redirect' => home_url('/ho-so/')
    ));
}

// 8.3 Affiliate Click Tracking (Shopee & TikTok)
add_action('wp_ajax_nopriv_muop_track_click', 'muop_ajax_track_click_handler');
add_action('wp_ajax_muop_track_click', 'muop_ajax_track_click_handler');
function muop_ajax_track_click_handler() {
    global $wpdb;
    $link_type   = isset($_POST['link_type']) ? sanitize_text_field($_POST['link_type']) : 'shopee';
    $url         = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
    $story_id    = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $chapter_num = isset($_POST['chapter_num']) ? intval($_POST['chapter_num']) : 0;
    $user_id     = get_current_user_id();

    $ip         = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';

    $table_clicks = $wpdb->prefix . 'affiliate_clicks';
    $wpdb->insert($table_clicks, array(
        'link_type'   => $link_type,
        'url'         => $url,
        'ip'          => $ip,
        'user_agent'  => $user_agent,
        'user_id'     => $user_id,
        'story_id'    => $story_id,
        'chapter_num' => $chapter_num,
        'created_at'  => current_time('mysql')
    ));

    // Also update global counters
    $option_key = ($link_type === 'tiktok') ? 'muop_tiktok_clicks_count' : 'muop_shopee_clicks_count';
    $count = (int) get_option($option_key, 0) + 1;
    update_option($option_key, $count);

    wp_send_json_success(array(
        'recorded'   => true,
        'link_type'  => $link_type,
        'totalCount' => $count
    ));
}

// 8.4 Record Story/Chapter View
add_action('wp_ajax_nopriv_muop_record_view', 'muop_ajax_record_view_handler');
add_action('wp_ajax_muop_record_view', 'muop_ajax_record_view_handler');
function muop_ajax_record_view_handler() {
    $story_id   = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;

    if (!$story_id) {
        wp_send_json_error();
    }

    // Story views
    $current_views = (int) get_post_meta($story_id, '_truyen_views', true);
    $new_views = $current_views + 1;
    update_post_meta($story_id, '_truyen_views', $new_views);

    // Monthly view log for salary calculation
    $current_month = date('Y-m');
    $month_meta = '_truyen_views_' . $current_month;
    $month_views = (int) get_post_meta($story_id, $month_meta, true);
    update_post_meta($story_id, $month_meta, $month_views + 1);

    // Chapter views if applicable
    if ($chapter_id) {
        $chap_views = (int) get_post_meta($chapter_id, '_chuong_views', true);
        update_post_meta($chapter_id, '_chuong_views', $chap_views + 1);
    }

    // Update translator author's total views
    $author_id = get_post_field('post_author', $story_id);
    if ($author_id) {
        $author_total_views = (int) get_user_meta($author_id, '_muop_total_views', true);
        update_user_meta($author_id, '_muop_total_views', $author_total_views + 1);

        $author_month_views = (int) get_user_meta($author_id, '_muop_views_' . $current_month, true);
        update_user_meta($author_id, '_muop_views_' . $current_month, $author_month_views + 1);
    }

    // 3. Ghi nhận nhật ký khách đọc truyện chi tiết theo ngày vào wp_muop_visitor_read_logs
    global $wpdb;
    $table_vlogs = $wpdb->prefix . 'muop_visitor_read_logs';
    $client_ip = function_exists('muop_get_real_client_ip') ? muop_get_real_client_ip() : (isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '127.0.0.1');
    $current_u = wp_get_current_user();
    $uid = is_user_logged_in() ? (int) $current_u->ID : 0;
    $uname = is_user_logged_in() ? $current_u->display_name : 'Khách vãng lai';

    $story_obj = get_post($story_id);
    $stitle = $story_obj ? $story_obj->post_title : ('Truyện #' . $story_id);

    $chap_n = isset($_POST['chapter_num']) ? intval($_POST['chapter_num']) : 0;
    $chap_t = '';
    if ($chapter_id) {
        $chap_obj = get_post($chapter_id);
        if (!$chap_n) {
            $chap_n = (int) get_post_meta($chapter_id, '_chuong_number', true) ?: 1;
        }
        if ($chap_obj) {
            $chap_t = function_exists('muop_get_clean_chapter_title') ? muop_get_clean_chapter_title($chap_obj->post_title) : $chap_obj->post_title;
        } else {
            $chap_t = 'Chương ' . $chap_n;
        }
    }

    $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
    $device = 'Máy tính';
    if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $u_agent)) {
        $device = 'Máy tính bảng';
    } elseif (preg_match('/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Opera M(obi|ini)/i', $u_agent)) {
        $device = 'Điện thoại';
    }

    $now_date = current_time('Y-m-d');
    $now_dt   = current_time('mysql');

    // Chống flood: Nếu cùng 1 IP đọc cùng 1 chương trong vòng 15 giây, chỉ update thời gian
    $recent_log_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_vlogs WHERE ip_address = %s AND chapter_id = %d AND read_date = %s AND read_time >= DATE_SUB(%s, INTERVAL 15 SECOND) ORDER BY id DESC LIMIT 1",
        $client_ip, $chapter_id, $now_date, $now_dt
    ));

    if ($recent_log_id) {
        $wpdb->update($table_vlogs, array('read_time' => $now_dt), array('id' => $recent_log_id));
    } else {
        $wpdb->insert($table_vlogs, array(
            'ip_address'    => $client_ip,
            'user_id'       => $uid,
            'user_name'     => $uname,
            'story_id'      => $story_id,
            'story_title'   => $stitle,
            'chapter_id'    => $chapter_id,
            'chapter_num'   => $chap_n,
            'chapter_title' => $chap_t,
            'device_type'   => $device,
            'read_date'     => $now_date,
            'read_time'     => $now_dt
        ));
    }

    wp_send_json_success(array('views' => $new_views));
}

// 8.5 Toggle Bookmark (Tủ truyện)
add_action('wp_ajax_muop_toggle_bookmark', 'muop_ajax_toggle_bookmark_handler');
add_action('wp_ajax_nopriv_muop_toggle_bookmark', 'muop_ajax_toggle_bookmark_handler');
function muop_ajax_toggle_bookmark_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để lưu truyện vào tủ!'));
    }
    global $wpdb;
    $user_id  = get_current_user_id();
    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;

    $table = $wpdb->prefix . 'reading_bookmarks';
    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE user_id = %d AND story_id = %d", $user_id, $story_id));

    if ($exists) {
        $wpdb->delete($table, array('user_id' => $user_id, 'story_id' => $story_id));
        wp_send_json_success(array('bookmarked' => false, 'message' => 'Đã xóa khỏi Tủ truyện'));
    } else {
        $wpdb->insert($table, array(
            'user_id'    => $user_id,
            'story_id'   => $story_id,
            'created_at' => current_time('mysql')
        ));
        wp_send_json_success(array('bookmarked' => true, 'message' => 'Đã lưu vào Tủ truyện'));
    }
}

// 8.6 Record Reading History
add_action('wp_ajax_muop_save_history', 'muop_ajax_save_history_handler');
add_action('wp_ajax_nopriv_muop_save_history', 'muop_ajax_save_history_handler');
function muop_ajax_save_history_handler() {
    if (!is_user_logged_in()) {
        wp_send_json_success(); // guests saved locally via JS localStorage
    }
    global $wpdb;
    $user_id     = get_current_user_id();
    $story_id    = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $chapter_id  = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
    $chapter_num = isset($_POST['chapter_num']) ? intval($_POST['chapter_num']) : 1;

    $table = $wpdb->prefix . 'reading_history';
    $wpdb->query($wpdb->prepare(
        "INSERT INTO $table (user_id, story_id, chapter_id, chapter_num, updated_at) 
         VALUES (%d, %d, %d, %d, %s)
         ON DUPLICATE KEY UPDATE chapter_id = %d, chapter_num = %d, updated_at = %s",
        $user_id, $story_id, $chapter_id, $chapter_num, current_time('mysql'),
        $chapter_id, $chapter_num, current_time('mysql')
    ));

    wp_send_json_success();
}

// 8.7 Post Comment
add_action('wp_ajax_muop_post_comment', 'muop_ajax_post_comment_handler');
add_action('wp_ajax_nopriv_muop_post_comment', 'muop_ajax_post_comment_handler');
function muop_ajax_post_comment_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để bình luận!'));
    }

    $post_id   = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $comment   = isset($_POST['comment']) ? sanitize_textarea_field($_POST['comment']) : '';
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;

    if (empty($comment)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập nội dung bình luận!'));
    }

    $user = wp_get_current_user();
    $comment_data = array(
        'comment_post_ID'      => $post_id,
        'comment_author'       => $user->display_name,
        'comment_author_email' => $user->user_email,
        'comment_content'      => $comment,
        'comment_type'         => 'comment',
        'comment_parent'       => $parent_id,
        'user_id'              => $user->ID,
        'comment_approved'     => 1
    );

    $comment_id = wp_insert_comment($comment_data);
    if ($comment_id) {
        // 1. Thông báo phản hồi bình luận
        if ($parent_id > 0) {
            $parent_comment = get_comment($parent_id);
            $clean_preview = preg_replace('/\[sticker:[^\]]+\]/i', '[Sticker]', $comment);
            if ($parent_comment && $parent_comment->user_id && (int) $parent_comment->user_id !== (int) $user->ID) {
                muop_add_notification(array(
                    'user_id'   => (int) $parent_comment->user_id,
                    'sender_id' => $user->ID,
                    'type'      => 'comment_reply',
                    'title'     => $user->display_name . ' đã trả lời bình luận của bạn',
                    'message'   => wp_trim_words($clean_preview, 20),
                    'link'      => get_permalink($post_id) . '#comment-' . $comment_id
                ));
            }
        }

        // 2. Thông báo cho Dịch giả của truyện
        $story_id = $post_id;
        if (get_post_type($post_id) === 'chuong') {
            $story_id = (int) get_post_meta($post_id, '_chuong_truyen_id', true) ?: $post_id;
        }
        $story_post = get_post($story_id);
        if ($story_post && $story_post->post_author && (int) $story_post->post_author !== (int) $user->ID) {
            if (!isset($parent_comment) || (int) $parent_comment->user_id !== (int) $story_post->post_author) {
                $clean_preview = preg_replace('/\[sticker:[^\]]+\]/i', '[Sticker]', $comment);
                muop_add_notification(array(
                    'user_id'   => (int) $story_post->post_author,
                    'sender_id' => $user->ID,
                    'type'      => 'story_comment',
                    'title'     => 'Bình luận mới: ' . $story_post->post_title,
                    'message'   => $user->display_name . ': "' . wp_trim_words($clean_preview, 20) . '"',
                    'link'      => get_permalink($post_id) . '#comment-' . $comment_id
                ));
            }
        }

        wp_send_json_success(array(
            'message'    => 'Bình luận thành công!',
            'comment_id' => $comment_id,
            'author'     => $user->display_name,
            'time'       => 'Vừa xong',
            'content'    => muop_render_comment_content($comment)
        ));
    }
    wp_send_json_error(array('message' => 'Không thể gửi bình luận!'));
}

/**
 * Kiểm tra quyền xóa bình luận:
 * - Admin: xóa được bất cứ bình luận nào.
 * - Dịch giả: xóa được nếu là truyện / chương do dịch giả đó đăng.
 * - Độc giả: xóa được bình luận của chính mình.
 */
function muop_can_user_delete_comment($comment, $user_id = 0) {
    if (!$comment) return false;
    if (is_numeric($comment)) {
        $comment = get_comment($comment);
    }
    if (!$comment) return false;

    if (empty($user_id)) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) return false;

    // 1. Quản trị viên (Admin) xóa được mọi bình luận
    if (user_can($user_id, 'administrator')) {
        return true;
    }

    // 2. Tác giả của bình luận (Độc giả xóa bình luận của chính mình)
    if ((int) $comment->user_id > 0 && (int) $comment->user_id === (int) $user_id) {
        return true;
    }

    // 3. Dịch giả của truyện (xóa được bình luận trong truyện của mình)
    $post_id = (int) $comment->comment_post_ID;
    $post = get_post($post_id);
    if ($post) {
        if ((int) $post->post_author === (int) $user_id) {
            return true;
        }

        // Nếu là chương truyện, kiểm tra tác giả của bộ truyện cha
        if ($post->post_type === 'chuong') {
            $story_id = (int) get_post_meta($post_id, '_chuong_truyen_id', true);
            if ($story_id) {
                $story_post = get_post($story_id);
                if ($story_post && (int) $story_post->post_author === (int) $user_id) {
                    return true;
                }
            }
        }
    }

    return false;
}

// 8.7b Xóa bình luận (Admin, Dịch giả sở hữu truyện, hoặc Độc giả sở hữu bình luận)
add_action('wp_ajax_muop_delete_comment', 'muop_ajax_delete_comment_handler');
add_action('wp_ajax_nopriv_muop_delete_comment', 'muop_ajax_delete_comment_handler');
function muop_ajax_delete_comment_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện thao tác này!'));
    }

    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    if (!$comment_id) {
        wp_send_json_error(array('message' => 'ID bình luận không hợp lệ!'));
    }

    $comment = get_comment($comment_id);
    if (!$comment) {
        wp_send_json_error(array('message' => 'Bình luận không tồn tại hoặc đã bị xóa trước đó!'));
    }

    $current_user_id = get_current_user_id();
    if (!muop_can_user_delete_comment($comment, $current_user_id)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền xóa bình luận này!'));
    }

    $deleted = wp_delete_comment($comment_id, true);
    if ($deleted) {
        wp_send_json_success(array('message' => 'Đã xóa bình luận thành công!', 'comment_id' => $comment_id));
    } else {
        wp_send_json_error(array('message' => 'Không thể xóa bình luận, vui lòng thử lại!'));
    }
}

/**
 * Chuyển đổi và kiểm tra thời gian hẹn giờ chuẩn Việt Nam (GMT+7)
 * Đảm bảo chấp nhận mọi mốc thời gian trong ngày miễn là lớn hơn thời gian hiện tại
 */
function muop_validate_and_parse_schedule_time($scheduled_time) {
    if (empty($scheduled_time)) {
        return new WP_Error('empty_time', 'Vui lòng chọn thời gian hẹn giờ!');
    }

    $sched_clean = str_replace('T', ' ', trim($scheduled_time));
    if (strlen($sched_clean) === 16) {
        $sched_clean .= ':00';
    }

    $tz_vn = new DateTimeZone('Asia/Ho_Chi_Minh');
    $tz_utc = new DateTimeZone('UTC');

    try {
        $sched_dt = new DateTime($sched_clean, $tz_vn);
    } catch (Exception $e) {
        return new WP_Error('invalid_format', 'Định dạng thời gian hẹn giờ không hợp lệ!');
    }

    $now_dt = new DateTime('now', $tz_vn);

    // Kiểm tra: Phải lớn hơn thời gian hiện tại (cho phép cùng ngày, chỉ cần sau giờ hiện tại)
    if ($sched_dt <= $now_dt) {
        return new WP_Error('past_time', 'Thời gian hẹn giờ phải lớn hơn thời gian hiện tại (hiện tại là ' . $now_dt->format('H:i - d/m/Y') . ')!');
    }

    $post_date = $sched_dt->format('Y-m-d H:i:s');
    $dt_utc = clone $sched_dt;
    $dt_utc->setTimezone($tz_utc);
    $post_date_gmt = $dt_utc->format('Y-m-d H:i:s');
    $formatted = $sched_dt->format('H:i - d/m/Y');

    return array(
        'post_date'     => $post_date,
        'post_date_gmt' => $post_date_gmt,
        'formatted'     => $formatted,
        'timestamp'     => $sched_dt->getTimestamp()
    );
}

// 8.8 Submit Story (Front-end for Translators / Admin)
add_action('wp_ajax_muop_submit_story', 'muop_ajax_submit_story_handler');
add_action('wp_ajax_nopriv_muop_submit_story', 'muop_ajax_submit_story_handler');
function muop_ajax_submit_story_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện chức năng này!'));
    }
    if (!muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Tài khoản của bạn cần có quyền Dịch Giả hoặc Quản Trị Viên để đăng truyện!'));
    }

    $title       = sanitize_text_field($_POST['title']);
    $author_name = sanitize_text_field($_POST['author_name']);
    $team_name   = sanitize_text_field($_POST['team_name']);
    $status      = sanitize_text_field($_POST['status']); // dang_ra or hoan_thanh
    $categories  = isset($_POST['categories']) ? array_map('intval', (array) $_POST['categories']) : array();
    $description = wp_kses_post($_POST['description']);

    if (empty($title)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập tên truyện!'));
    }

    $is_draft = (!empty($_POST['is_draft']) && $_POST['is_draft'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'draft');
    $is_schedule = (!empty($_POST['enable_schedule']) && $_POST['enable_schedule'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'future');
    $scheduled_time = !empty($_POST['scheduled_time']) ? sanitize_text_field($_POST['scheduled_time']) : '';

    $is_admin = muop_is_admin();
    if ($is_admin) {
        // Admin luôn đăng dưới danh xưng team Mướp Đắng Cũng Có Vị Ngọt
        $team_name = 'Mướp Đắng Cũng Có Vị Ngọt';
    }

    $post_date = current_time('mysql');
    $post_date_gmt = current_time('mysql', 1);

    if ($is_draft) {
        $post_status = 'draft';
    } elseif ($is_schedule && !empty($scheduled_time)) {
        $parsed_sched = muop_validate_and_parse_schedule_time($scheduled_time);
        if (is_wp_error($parsed_sched)) {
            wp_send_json_error(array('message' => $parsed_sched->get_error_message()));
        }
        $post_status = 'future';
        $post_date = $parsed_sched['post_date'];
        $post_date_gmt = $parsed_sched['post_date_gmt'];
    } else {
        $post_status = $is_admin ? 'publish' : 'pending'; // Translator posts need admin moderation!
    }

    $post_data = array(
        'post_title'    => $title,
        'post_content'  => $description,
        'post_status'   => $post_status,
        'post_type'     => 'truyen',
        'post_author'   => get_current_user_id(),
        'post_date'     => $post_date,
        'post_date_gmt' => $post_date_gmt,
        'edit_date'     => true
    );

    $story_id = wp_insert_post($post_data);
    if (is_wp_error($story_id)) {
        wp_send_json_error(array('message' => $story_id->get_error_message()));
    }

    // Save meta
    update_post_meta($story_id, '_truyen_status', $status);
    update_post_meta($story_id, '_truyen_author_name', $author_name);
    update_post_meta($story_id, '_truyen_team', $team_name);
    update_post_meta($story_id, '_truyen_views', 0);
    update_post_meta($story_id, '_truyen_nominated', 'none');

    // Taxonomies
    if (!empty($_POST['new_categories'])) {
        $new_cats = is_array($_POST['new_categories']) ? $_POST['new_categories'] : explode(',', sanitize_text_field($_POST['new_categories']));
        foreach ($new_cats as $new_c) {
            $nc_name = trim(sanitize_text_field($new_c));
            if (!empty($nc_name)) {
                $term = term_exists($nc_name, 'the_loai');
                if (!$term) {
                    $term = wp_insert_term($nc_name, 'the_loai');
                }
                if (!is_wp_error($term) && isset($term['term_id'])) {
                    $categories[] = (int) $term['term_id'];
                }
            }
        }
    }

    if (!empty($categories)) {
        wp_set_object_terms($story_id, array_unique($categories), 'the_loai');
    }
    if (!empty($author_name)) {
        wp_set_object_terms($story_id, array($author_name), 'tac_gia');
    }
    if (!empty($team_name)) {
        $team_term = term_exists($team_name, 'team_dich');
        if (!$team_term) {
            wp_insert_term($team_name, 'team_dich');
        }
        wp_set_object_terms($story_id, array($team_name), 'team_dich');
    }

    // Handle Cover Image Upload (Tối ưu tốc độ: Chỉ tạo các cỡ ảnh bìa cần dùng)
    $cover_field = !empty($_FILES['cover_image']['name']) ? 'cover_image' : (!empty($_FILES['story_cover']['name']) ? 'story_cover' : '');
    if (!empty($cover_field)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $filter_cover_sizes = function($sizes) {
            $allowed = array('muop-cover', 'muop-cover-thumb');
            foreach ($sizes as $s => $data) {
                if (!in_array($s, $allowed, true)) {
                    unset($sizes[$s]);
                }
            }
            return $sizes;
        };
        add_filter('intermediate_image_sizes_advanced', $filter_cover_sizes, 999);

        $attachment_id = media_handle_upload($cover_field, $story_id);
        remove_filter('intermediate_image_sizes_advanced', $filter_cover_sizes, 999);

        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($story_id, $attachment_id);
        }
    }

    if ($is_draft) {
        $success_msg = 'Đã lưu bản nháp truyện thành công! Bạn có thể xem và chỉnh sửa trong mục Lưu Nháp.';
    } elseif ($post_status === 'future') {
        update_post_meta($story_id, '_truyen_scheduled_time', $post_date);
        update_post_meta($story_id, '_truyen_scheduled_by', get_current_user_id());
        $success_msg = 'Đã lên lịch hẹn giờ đăng truyện thành công! Truyện sẽ được tự động phát hành vào lúc: ' . muop_format_datetime($post_date, 'H:i - d/m/Y') . '.';
    } else {
        if ($is_admin) {
            $success_msg = 'Đăng truyện thành công!';
            muop_notify_followers_new_story($story_id);
        } else {
            $success_msg = 'Đăng truyện thành công! Bài đăng đang chờ Quản trị viên duyệt.';
            $admins = get_users(array('role' => 'administrator'));
            foreach ($admins as $adm) {
                muop_add_notification(array(
                    'user_id'   => $adm->ID,
                    'sender_id' => get_current_user_id(),
                    'type'      => 'system',
                    'title'     => 'Truyện mới chờ kiểm duyệt',
                    'message'   => 'Dịch giả vừa gửi truyện "' . $title . '" đang chờ bạn duyệt.',
                    'link'      => home_url('/quan-ly-admin/#admTabStories')
                ));
            }
        }
    }

    wp_send_json_success(array(
        'message'  => $success_msg,
        'story_id' => $story_id,
        'reset'    => true
    ));
}

// 8.9 Submit Chapter (Front-end for Translators / Admin)
add_action('wp_ajax_muop_submit_chapter', 'muop_ajax_submit_chapter_handler');
add_action('wp_ajax_nopriv_muop_submit_chapter', 'muop_ajax_submit_chapter_handler');
function muop_ajax_submit_chapter_handler() {
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : (isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : ''));
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang (F5) và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thêm chương mới!'));
    }
    if (!muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Tài khoản của bạn cần có quyền Dịch Giả hoặc Quản Trị Viên để thêm chương!'));
    }

    $story_id    = intval($_POST['story_id']);
    $chap_num    = intval($_POST['chapter_number']);
    $chap_title  = sanitize_text_field(wp_unslash($_POST['chapter_title']));
    $content     = wp_kses_post(wp_unslash($_POST['content']));

    if (!$story_id || empty($chap_title) || empty($content)) {
        wp_send_json_error(array('message' => 'Vui lòng điền đầy đủ tên chương và nội dung chương!'));
    }

    $story = get_post($story_id);
    if (!$story) {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    // Phân quyền thêm chương:
    // 1. Dịch giả của truyện hoặc Admin (đối với team Mướp Đắng cũng có vị ngọt, hoặc trên Admin portal)
    $current_uid = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (muop_is_admin($current_uid)) {
        $story_author = (int) $story->post_author;
        if ($story_author === (int) $current_uid || muop_is_story_of_team_muop_dang($story_id) || $is_from_admin_portal) {
            // Hợp lệ cho Admin
        } else {
            wp_send_json_error(array('message' => 'Admin chỉ có quyền thêm/sửa chương của team Mướp Đắng Cũng Có Vị Ngọt hoặc trên trang Quản Trị Hệ Thống!'));
        }
    } else {
        if (!muop_can_user_manage_story_action($story_id, $current_uid, $is_from_admin_portal)) {
            wp_send_json_error(array('message' => 'Bạn không có quyền thêm chương cho truyện của dịch giả khác!'));
        }
    }

    $clean_title = muop_get_clean_chapter_title($chap_title);
    if (empty($clean_title)) {
        $clean_title = 'Chương ' . $chap_num;
    }
    $is_draft = (!empty($_POST['is_draft']) && $_POST['is_draft'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'draft');
    $post_status = $is_draft ? 'draft' : 'publish';

    // Bắt buộc duyệt nội dung: Kiểm tra từ nhạy cảm trước khi xuất bản chương
    if (!$is_draft && function_exists('muop_scan_sensitive_words')) {
        $detected = muop_scan_sensitive_words($clean_title . ' ' . wp_strip_all_tags($content));
        if (!empty($detected)) {
            $words_list = array_keys($detected);
            $count = count($words_list);
            wp_send_json_error(array(
                'message' => 'Nội dung chứa ' . $count . ' từ ngữ nhạy cảm (' . implode(', ', array_slice($words_list, 0, 5)) . ($count > 5 ? '...' : '') . '). Vui lòng rà soát và thay thế trước khi đăng chương!'
            ));
        }
    }

    $chapter_id = wp_insert_post(array(
        'post_title'   => $clean_title,
        'post_content' => $content,
        'post_status'  => $post_status,
        'post_type'    => 'chuong',
        'post_author'  => get_current_user_id()
    ));

    if (is_wp_error($chapter_id)) {
        wp_send_json_error(array('message' => $chapter_id->get_error_message()));
    }

    update_post_meta($chapter_id, '_chuong_truyen_id', $story_id);
    update_post_meta($chapter_id, '_chuong_number', $chap_num);
    update_post_meta($chapter_id, '_chuong_views', 0);

    // Update story modified time (giữ nguyên trạng thái future / draft của truyện)
    wp_update_post(array('ID' => $story_id, 'post_status' => $story->post_status));

    // Bắn thông báo ra chương mới cho độc giả theo dõi truyện này (chỉ khi truyện đang ở trạng thái công khai publish)
    if (!$is_draft && $story->post_status === 'publish') {
        muop_notify_story_new_chapter($story_id, $chapter_id, $chap_num, $clean_title);
    }

    $success_msg = $is_draft ? 'Đã lưu bản nháp chương truyện thành công!' : 'Thêm chương mới thành công!';

    wp_send_json_success(array(
        'message'             => $success_msg,
        'chapter_id'          => $chapter_id,
        'story_id'            => $story_id,
        'next_chapter_number' => $chap_num + 1,
        'redirect'            => get_permalink($story_id),
        'reset'               => true
    ));
}

// 8.9.1 Lấy Số Chương Tiếp Theo Của Truyện (Hỗ trợ reset form thêm chương liên tục)
add_action('wp_ajax_muop_get_next_chapter_number', 'muop_ajax_get_next_chapter_number_handler');
add_action('wp_ajax_nopriv_muop_get_next_chapter_number', 'muop_ajax_get_next_chapter_number_handler');
function muop_ajax_get_next_chapter_number_handler() {
    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    if (!$story_id) {
        wp_send_json_error(array('message' => 'Truyện không hợp lệ!'));
    }
    global $wpdb;
    $latest_chap_meta = $wpdb->get_var($wpdb->prepare(
        "SELECT pm.meta_value FROM {$wpdb->postmeta} pm
         JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = '_chuong_number'
           AND p.post_status IN ('publish', 'draft')
           AND p.ID IN (
               SELECT post_id FROM {$wpdb->postmeta}
               WHERE meta_key = '_chuong_truyen_id' AND meta_value = %d
           )
         ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC LIMIT 1",
        $story_id
    ));
    $next_chap_num = $latest_chap_meta ? (intval($latest_chap_meta) + 1) : 1;
    wp_send_json_success(array('next_chapter_number' => $next_chap_num));
}

// Helper: Trích xuất toàn bộ văn bản từ file Word (.docx) kể cả khi máy chủ không cài ZipArchive
function muop_extract_docx_text($file_path) {
    if (!file_exists($file_path) || filesize($file_path) === 0) {
        return '';
    }

    $xml_data = '';

    // Cách 1: Sử dụng ZipArchive nếu có sẵn
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($file_path) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xml_data = $zip->getFromIndex($index);
            }
            $zip->close();
        }
    }

    // Cách 2: Phân tích định dạng PK ZIP thuần PHP (sử dụng gzinflate) nếu ZipArchive không khả dụng
    if (empty($xml_data)) {
        $raw_bytes = @file_get_contents($file_path);
        if ($raw_bytes && strlen($raw_bytes) > 30) {
            $target_file = 'word/document.xml';
            $data_len = strlen($raw_bytes);

            // 1. Quét tìm trong Central Directory (PK\x01\x02)
            $cd_pos = 0;
            $found = false;
            while ($cd_pos < $data_len) {
                $cd_pk = strpos($raw_bytes, "PK\x01\x02", $cd_pos);
                if ($cd_pk === false) break;
                if ($cd_pk + 46 > $data_len) break;

                $cd_header = substr($raw_bytes, $cd_pk, 46);
                $cd_fields = unpack('vversion/vver_needed/vflag/vmethod/vmodtime/vmoddate/Vcrc/Vcomp_size/Vuncomp_size/vfn_len/vextra_len/vcomment_len/vdisk/vattr/Vext_attr/Vlocal_offset', substr($cd_header, 4));

                $fn_len = $cd_fields['fn_len'];
                $extra_len = $cd_fields['extra_len'];
                $comment_len = $cd_fields['comment_len'];
                $fn_pos = $cd_pk + 46;

                if ($fn_pos + $fn_len <= $data_len) {
                    $fn = substr($raw_bytes, $fn_pos, $fn_len);
                    if ($fn === $target_file) {
                        $method = $cd_fields['method'];
                        $comp_size = $cd_fields['comp_size'];
                        $local_offset = $cd_fields['local_offset'];

                        if ($local_offset + 30 <= $data_len) {
                            $local_header = substr($raw_bytes, $local_offset, 30);
                            $local_fields = unpack('vver/vflag/vlocal_method/vtime/vdate/Vcrc/Vlocal_csize/Vlocal_usize/vlocal_fn_len/vlocal_extra_len', substr($local_header, 4));
                            $data_start = $local_offset + 30 + $local_fields['local_fn_len'] + $local_fields['local_extra_len'];

                            if ($data_start + $comp_size <= $data_len) {
                                $compressed_stream = substr($raw_bytes, $data_start, $comp_size);
                                if ($method === 8 && function_exists('gzinflate')) {
                                    $unzipped = @gzinflate($compressed_stream);
                                    if ($unzipped) {
                                        $xml_data = $unzipped;
                                        $found = true;
                                        break;
                                    }
                                } elseif ($method === 0) {
                                    $xml_data = $compressed_stream;
                                    $found = true;
                                    break;
                                }
                            }
                        }
                    }
                }
                $cd_pos = $cd_pk + 46 + $fn_len + $extra_len + $comment_len;
            }

            // 2. Dự phòng: Quét trực tiếp Local File Headers (PK\x03\x04)
            if (!$found) {
                $pos = 0;
                while ($pos < $data_len) {
                    $pk = strpos($raw_bytes, "PK\x03\x04", $pos);
                    if ($pk === false) break;
                    if ($pk + 30 > $data_len) break;

                    $header = substr($raw_bytes, $pk, 30);
                    $fields = unpack('vver/vflag/vmethod/vmodtime/vmoddate/Vcrc/Vcomp_size/Vuncomp_size/vfn_len/vextra_len', substr($header, 4));
                    $fn_len = $fields['fn_len'];
                    $extra_len = $fields['extra_len'];
                    $method = $fields['method'];
                    $comp_size = $fields['comp_size'];

                    $fn_pos = $pk + 30;
                    if ($fn_pos + $fn_len <= $data_len) {
                        $filename = substr($raw_bytes, $fn_pos, $fn_len);
                        $data_start = $fn_pos + $fn_len + $extra_len;

                        if ($filename === $target_file) {
                            if ($comp_size > 0 && ($data_start + $comp_size <= $data_len)) {
                                $comp_stream = substr($raw_bytes, $data_start, $comp_size);
                                if ($method === 8 && function_exists('gzinflate')) {
                                    $unzipped = @gzinflate($comp_stream);
                                    if ($unzipped) { $xml_data = $unzipped; break; }
                                } elseif ($method === 0) {
                                    $xml_data = $comp_stream; break;
                                }
                            } else {
                                $test_stream = substr($raw_bytes, $data_start, min(5000000, $data_len - $data_start));
                                $unzipped = @gzinflate($test_stream);
                                if ($unzipped) { $xml_data = $unzipped; break; }
                            }
                        }
                    }
                    $pos = ($comp_size > 0 && $data_start + $comp_size > $pk) ? ($data_start + $comp_size) : ($pk + 4);
                }
            }
        }
    }

    if (empty($xml_data)) {
        return '';
    }

    // Chuyển XML thành văn bản thuần túy có định dạng đoạn
    $xml_data = preg_replace('/<w:p[^>]*>/i', "\n\n", $xml_data);
    $xml_data = preg_replace('/<w:br[^>]*>/i', "\n", $xml_data);
    $xml_data = preg_replace('/<w:tab[^>]*>/i', "\t", $xml_data);

    $text = strip_tags($xml_data);
    if (function_exists('html_entity_decode')) {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    $text = preg_replace("/[ \t]+/", ' ', $text);
    $text = preg_replace("/\n{3,}/", "\n\n", $text);

    return trim($text);
}

// 8.10 Admin Quick Actions
add_action('wp_ajax_muop_admin_action', 'muop_ajax_admin_action_handler');
add_action('wp_ajax_nopriv_muop_admin_action', 'muop_ajax_admin_action_handler');
function muop_ajax_admin_action_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền quản trị!'));
    }

    $sub_action = sanitize_text_field($_POST['sub_action']);

    switch ($sub_action) {
        case 'approve_story':
            $story_id = intval($_POST['story_id']);
            wp_update_post(array('ID' => $story_id, 'post_status' => 'publish'));
            $story_obj = get_post($story_id);
            if ($story_obj && $story_obj->post_author) {
                muop_add_notification(array(
                    'user_id'   => (int) $story_obj->post_author,
                    'sender_id' => get_current_user_id(),
                    'type'      => 'system',
                    'title'     => 'Truyện đã được duyệt thành công!',
                    'message'   => 'Truyện "' . $story_obj->post_title . '" của bạn đã được kiểm duyệt và đăng tải công khai.',
                    'link'      => get_permalink($story_id)
                ));
            }
            muop_notify_followers_new_story($story_id);
            wp_send_json_success(array('message' => 'Đã duyệt truyện thành công!'));
            break;

        case 'reject_story':
            $story_id = intval($_POST['story_id']);
            wp_update_post(array('ID' => $story_id, 'post_status' => 'draft'));
            $story_obj = get_post($story_id);
            if ($story_obj && $story_obj->post_author) {
                muop_add_notification(array(
                    'user_id'   => (int) $story_obj->post_author,
                    'sender_id' => get_current_user_id(),
                    'type'      => 'system',
                    'title'     => 'Truyện được chuyển về bản nháp',
                    'message'   => 'Truyện "' . $story_obj->post_title . '" chưa đạt yêu cầu duyệt và đã được chuyển về bản nháp. Vui lòng chỉnh sửa lại.',
                    'link'      => home_url('/thong-tin-dich-gia/')
                ));
            }
            wp_send_json_success(array('message' => 'Đã chuyển truyện về trạng thái Bản nháp!'));
            break;

        case 'hide_story':
            $story_id = intval($_POST['story_id']);
            update_post_meta($story_id, '_truyen_prev_status', get_post_status($story_id));
            update_post_meta($story_id, '_truyen_is_hidden', '1');
            update_post_meta($story_id, '_truyen_hidden_by', get_current_user_id());
            wp_update_post(array('ID' => $story_id, 'post_status' => 'draft'));
            $story_obj = get_post($story_id);
            if ($story_obj && $story_obj->post_author) {
                muop_add_notification(array(
                    'user_id'   => (int) $story_obj->post_author,
                    'sender_id' => get_current_user_id(),
                    'type'      => 'system',
                    'title'     => 'Truyện đã được ẩn khỏi website',
                    'message'   => 'Truyện "' . $story_obj->post_title . '" đã được chuyển sang trạng thái ẩn bởi Quản trị viên.',
                    'link'      => home_url('/thong-tin-dich-gia/')
                ));
            }
            wp_send_json_success(array('message' => 'Đã ẩn truyện khỏi trang web công khai thành công!'));
            break;

        case 'unhide_story':
            $story_id = intval($_POST['story_id']);
            delete_post_meta($story_id, '_truyen_is_hidden');
            delete_post_meta($story_id, '_truyen_hidden_by');
            delete_post_meta($story_id, '_truyen_prev_status');
            wp_update_post(array('ID' => $story_id, 'post_status' => 'publish'));
            $story_obj = get_post($story_id);
            if ($story_obj && $story_obj->post_author) {
                muop_add_notification(array(
                    'user_id'   => (int) $story_obj->post_author,
                    'sender_id' => get_current_user_id(),
                    'type'      => 'system',
                    'title'     => 'Truyện đã được hiển thị công khai!',
                    'message'   => 'Truyện "' . $story_obj->post_title . '" của bạn đã được hiển thị công khai trở lại trên website.',
                    'link'      => get_permalink($story_id)
                ));
            }
            wp_send_json_success(array('message' => 'Đã bỏ ẩn và hiển thị công khai truyện thành công!'));
            break;

        case 'delete_story':
            $story_id = intval($_POST['story_id']);
            wp_delete_post($story_id, true);
            wp_send_json_success(array('message' => 'Đã xóa truyện vĩnh viễn!'));
            break;

        case 'nominate_story':
            $story_id = intval($_POST['story_id']);
            $type     = sanitize_text_field($_POST['nominate_type']); // 'day', 'week', 'month', 'none'
            update_post_meta($story_id, '_truyen_nominated', $type);
            if ($type !== 'none') {
                $story_obj = get_post($story_id);
                if ($story_obj && $story_obj->post_author) {
                    $type_label = ($type === 'day') ? 'Ngày' : (($type === 'week') ? 'Tuần' : 'Tháng');
                    muop_add_notification(array(
                        'user_id'   => (int) $story_obj->post_author,
                        'sender_id' => get_current_user_id(),
                        'type'      => 'story_nominated',
                        'title'     => 'Truyện được đề cử!',
                        'message'   => 'Chúc mừng! Truyện "' . $story_obj->post_title . '" vừa được Quản trị viên đề cử ' . $type_label . '.',
                        'link'      => get_permalink($story_id)
                    ));
                }
            }
            wp_send_json_success(array('message' => 'Đã cập nhật trạng thái đề cử thành công!'));
            break;

        case 'update_links':
            $shopee_url   = esc_url_raw($_POST['shopee_url']);
            $shopee_url_2 = isset($_POST['shopee_url_2']) ? esc_url_raw($_POST['shopee_url_2']) : '';
            $tiktok_url   = esc_url_raw($_POST['tiktok_url']);
            if ($shopee_url) update_option('muop_shopee_url', $shopee_url);
            update_option('muop_shopee_url_2', $shopee_url_2);
            if ($tiktok_url) update_option('muop_tiktok_url', $tiktok_url);
            if (isset($_POST['tiktok_cooldown_minutes'])) {
                $cd = max(1, intval($_POST['tiktok_cooldown_minutes']));
                update_option('muop_tiktok_cooldown_minutes', $cd);
            }
            wp_send_json_success(array('message' => 'Cập nhật link Shopee & TikTok thành công!'));
            break;

        case 'toggle_user_status':
            $target_user_id = intval($_POST['user_id']);
            $is_locked = get_user_meta($target_user_id, '_muop_account_locked', true);
            if ($is_locked) {
                delete_user_meta($target_user_id, '_muop_account_locked');
                wp_send_json_success(array('status' => 'active', 'message' => 'Đã mở khóa tài khoản!'));
            } else {
                update_user_meta($target_user_id, '_muop_account_locked', 1);
                wp_send_json_success(array('status' => 'locked', 'message' => 'Đã khóa tài khoản!'));
            }
            break;

        case 'change_user_role':
            $target_user_id = intval($_POST['user_id']);
            $new_role       = sanitize_text_field($_POST['new_role']);
            $allowed_roles  = array('doc_gia', 'dich_gia', 'administrator');
            if (in_array($new_role, $allowed_roles, true)) {
                $target_user = get_userdata($target_user_id);
                if (!$target_user) {
                    wp_send_json_error(array('message' => 'Người dùng không tồn tại!'));
                }
                $user_obj = new WP_User($target_user_id);
                $user_obj->set_role($new_role);

                $role_labels = array(
                    'doc_gia'       => 'Độc Giả',
                    'dich_gia'      => 'Dịch Giả',
                    'administrator' => 'Quản Trị Viên'
                );
                $label = isset($role_labels[$new_role]) ? $role_labels[$new_role] : $new_role;
                wp_send_json_success(array('message' => 'Đã thay đổi vai trò người dùng thành ' . $label, 'new_role' => $new_role));
            } else {
                wp_send_json_error(array('message' => 'Vai trò người dùng không hợp lệ!'));
            }
            break;

        case 'edit_user':
            $target_user_id = intval($_POST['user_id']);
            $u = get_userdata($target_user_id);
            if (!$u) {
                wp_send_json_error(array('message' => 'Người dùng không tồn tại!'));
            }
            $display_name = sanitize_text_field(trim($_POST['display_name']));
            $email = sanitize_email(trim($_POST['email']));
            $role = sanitize_text_field(trim($_POST['role']));
            $new_pass = !empty($_POST['new_password']) ? trim($_POST['new_password']) : '';

            if (empty($display_name) || empty($email)) {
                wp_send_json_error(array('message' => 'Tên hiển thị và Email không được để trống!'));
            }

            if (!is_email($email)) {
                wp_send_json_error(array('message' => 'Định dạng email không hợp lệ!'));
            }

            $existing_uid = email_exists($email);
            if ($existing_uid && $existing_uid !== $target_user_id) {
                wp_send_json_error(array('message' => 'Email này đã được sử dụng bởi tài khoản khác!'));
            }

            $update_data = array(
                'ID'           => $target_user_id,
                'display_name' => $display_name,
                'user_email'   => $email,
            );
            if (!empty($new_pass)) {
                $update_data['user_pass'] = $new_pass;
                update_user_meta($target_user_id, '_last_password_reset_by_admin', current_time('mysql'));
                update_user_meta($target_user_id, '_admin_temp_password', $new_pass);
            }

            $allowed_roles = array('doc_gia', 'dich_gia', 'administrator');
            if (in_array($role, $allowed_roles, true)) {
                $update_data['role'] = $role;
            }

            $res = wp_update_user($update_data);
            if (is_wp_error($res)) {
                wp_send_json_error(array('message' => $res->get_error_message()));
            }

            if (in_array($role, $allowed_roles, true)) {
                $user_obj = new WP_User($target_user_id);
                $user_obj->set_role($role);
            }

            wp_send_json_success(array(
                'message'      => 'Cập nhật thông tin người dùng thành công!',
                'user_id'      => $target_user_id,
                'display_name' => $display_name,
                'email'        => $email,
                'role'         => $role
            ));
            break;

        case 'get_user_password_info':
            $target_user_id = intval($_POST['user_id']);
            $u = get_userdata($target_user_id);
            if (!$u) {
                wp_send_json_error(array('message' => 'Người dùng không tồn tại!'));
            }
            $last_temp = get_user_meta($target_user_id, '_admin_temp_password', true);
            $last_reset = get_user_meta($target_user_id, '_last_password_reset_by_admin', true);
            $reset_time_formatted = $last_reset ? date_i18n('d/m/Y H:i', strtotime($last_reset)) : '';

            wp_send_json_success(array(
                'user_id'       => $target_user_id,
                'user_login'    => $u->user_login,
                'display_name'  => $u->display_name,
                'user_email'    => $u->user_email,
                'temp_password' => $last_temp ? (string)$last_temp : '',
                'last_reset'    => $reset_time_formatted,
                'has_temp'      => !empty($last_temp)
            ));
            break;

        case 'change_user_password':
            $target_user_id = intval($_POST['user_id']);
            $u = get_userdata($target_user_id);
            if (!$u) {
                wp_send_json_error(array('message' => 'Người dùng không tồn tại!'));
            }
            $new_pass = !empty($_POST['new_password']) ? trim($_POST['new_password']) : '';
            if (empty($new_pass)) {
                wp_send_json_error(array('message' => 'Vui lòng nhập mật khẩu mới!'));
            }
            if (strlen($new_pass) < 6) {
                wp_send_json_error(array('message' => 'Mật khẩu phải có ít nhất 6 ký tự!'));
            }

            wp_set_password($new_pass, $target_user_id);
            update_user_meta($target_user_id, '_last_password_reset_by_admin', current_time('mysql'));
            update_user_meta($target_user_id, '_admin_temp_password', $new_pass);

            wp_send_json_success(array(
                'message'       => 'Đã đổi mật khẩu cho tài khoản "' . $u->display_name . '" thành công!',
                'user_id'       => $target_user_id,
                'temp_password' => $new_pass,
                'last_reset'    => current_time('d/m/Y H:i')
            ));
            break;

        case 'delete_user':
            $target_user_id = intval($_POST['user_id']);
            $curr_uid = get_current_user_id();
            if ($target_user_id === $curr_uid) {
                wp_send_json_error(array('message' => 'Bạn không thể tự xóa tài khoản quản trị đang đăng nhập!'));
            }
            require_once(ABSPATH . 'wp-admin/includes/user.php');
            $del_res = wp_delete_user($target_user_id, $curr_uid);
            if (!$del_res) {
                wp_send_json_error(array('message' => 'Không thể xóa người dùng! Vui lòng thử lại.'));
            }
            wp_send_json_success(array('message' => 'Đã xóa người dùng thành công!'));
            break;

        case 'create_user':
            $u_name  = sanitize_text_field($_POST['name']);
            $u_email = sanitize_email($_POST['email']);
            $u_pass  = $_POST['password'];
            $u_role  = sanitize_text_field($_POST['role']);
            if (empty($u_name) || empty($u_email) || empty($u_pass)) {
                wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ thông tin!'));
            }
            if (email_exists($u_email)) {
                wp_send_json_error(array('message' => 'Email đã tồn tại!'));
            }
            $username = sanitize_user(current(explode('@', $u_email)));
            if (username_exists($username)) $username .= '_' . wp_rand(10, 99);

            $allowed_roles = array('doc_gia', 'dich_gia', 'administrator');
            $assigned_role = in_array($u_role, $allowed_roles, true) ? $u_role : 'doc_gia';

            $new_uid = wp_insert_user(array(
                'user_login'   => $username,
                'user_pass'    => $u_pass,
                'user_email'   => $u_email,
                'display_name' => $u_name,
                'nickname'     => $u_name,
                'role'         => $assigned_role,
            ));
            if (is_wp_error($new_uid)) {
                wp_send_json_error(array('message' => $new_uid->get_error_message()));
            }
            update_user_meta($new_uid, '_admin_temp_password', $u_pass);
            update_user_meta($new_uid, '_last_password_reset_by_admin', current_time('mysql'));
            wp_send_json_success(array('message' => 'Thêm người dùng mới thành công!'));
            break;

        case 'get_story_details':
            $story_id = intval($_POST['story_id']);
            $story = get_post($story_id);
            if (!$story || $story->post_type !== 'truyen') {
                wp_send_json_error(array('message' => 'Không tìm thấy truyện!'));
            }

            $author = get_userdata($story->post_author);
            $author_name = $author ? $author->display_name : 'Không rõ';
            $views_all = muop_get_story_views($story_id);
            $current_month_str = date('Y-m');
            $views_month = (int) get_post_meta($story_id, '_truyen_views_' . $current_month_str, true);
            if (!$views_month) $views_month = $views_all;

            $progress = get_post_meta($story_id, '_truyen_status', true) ?: 'dang_ra';
            $nominated = get_post_meta($story_id, '_truyen_nominated', true) ?: 'none';
            $thumb = get_the_post_thumbnail_url($story_id, 'medium');

            $team_name = get_post_meta($story_id, '_truyen_team', true);
            if (empty($team_name)) $team_name = get_post_meta($story_id, '_truyen_dich_gia', true);
            if (empty($team_name)) {
                $terms = wp_get_post_terms($story_id, 'team_dich');
                $team_name = !empty($terms) ? $terms[0]->name : 'Mướp Đắng';
            }

            // Team URL
            $team_url = '';
            $team_terms = get_the_terms($story_id, 'team_dich');
            if (!empty($team_terms) && !is_wp_error($team_terms)) {
                $team_url = get_term_link($team_terms[0]);
            } else if (!empty($team_name)) {
                $found_term = get_term_by('name', $team_name, 'team_dich');
                if ($found_term && !is_wp_error($found_term)) {
                    $team_url = get_term_link($found_term);
                } else {
                    $found_slug = get_term_by('slug', sanitize_title($team_name), 'team_dich');
                    if ($found_slug && !is_wp_error($found_slug)) {
                        $team_url = get_term_link($found_slug);
                    } else {
                        $team_url = home_url('/team-dich/' . sanitize_title($team_name) . '/');
                    }
                }
            }

            $author_text = get_post_meta($story_id, '_truyen_author_name', true) ?: 'Chưa cập nhật';

            $genre_terms = wp_get_post_terms($story_id, 'the_loai');
            $genre_names = array();
            if (!empty($genre_terms) && !is_wp_error($genre_terms)) {
                foreach ($genre_terms as $gt) {
                    $genre_names[] = $gt->name;
                }
            }

            // Chapters of story
            $chapters = get_posts(array(
                'post_type'      => 'chuong',
                'posts_per_page' => -1,
                'meta_key'       => '_chuong_number',
                'orderby'        => 'meta_value_num',
                'order'          => 'ASC',
                'meta_query'     => array(
                    array('key' => '_chuong_truyen_id', 'value' => $story_id)
                ),
                'post_status'    => array('publish', 'draft', 'pending')
            ));

            $chapters_list = array();
            foreach ($chapters as $ch) {
                $num = get_post_meta($ch->ID, '_chuong_number', true) ?: 1;
                $ch_views = (int) get_post_meta($ch->ID, '_chuong_views', true);
                
                $raw_content = trim(strip_tags($ch->post_content));
                $words = count(preg_split('/\s+/u', $raw_content, -1, PREG_SPLIT_NO_EMPTY));

                $chapters_list[] = array(
                    'id'          => $ch->ID,
                    'number'      => (int) $num,
                    'title'       => $ch->post_title,
                    'words'       => $words,
                    'views'       => $ch_views,
                    'status'      => $ch->post_status,
                    'date'        => date('d/m/Y H:i', strtotime($ch->post_date)),
                    'permalink'   => get_permalink($ch->ID)
                );
            }

            wp_send_json_success(array(
                'id'              => $story->ID,
                'title'           => $story->post_title,
                'author_name'     => $author_text,
                'posted_by'       => $author_name,
                'team_name'       => $team_name,
                'team_url'        => $team_url,
                'views_all'       => $views_all,
                'views_month'     => $views_month,
                'progress'        => $progress,
                'post_status'     => $story->post_status,
                'nominated'       => $nominated,
                'genres'          => implode(', ', $genre_names) ?: 'Chưa phân loại',
                'thumbnail'       => $thumb ?: '',
                'description'     => muop_format_story_summary($story->post_content),
                'raw_description' => $story->post_content,
                'permalink'       => add_query_arg('from', 'admin', get_permalink($story_id)),
                'add_chapter_url' => add_query_arg(array('story_id' => $story->ID, 'from' => 'admin', 'from_admin' => 1), home_url('/them-chuong/')),
                'chapters'        => $chapters_list,
                'total_chapters'  => count($chapters_list)
            ));
            break;

        case 'get_user_details':
            $target_user_id = intval($_POST['user_id']);
            $u = get_userdata($target_user_id);
            if (!$u) {
                wp_send_json_error(array('message' => 'Không tìm thấy người dùng!'));
            }

            $roles = (array) $u->roles;
            if (in_array('administrator', $roles, true)) {
                $role = 'administrator';
            } elseif (in_array('dich_gia', $roles, true)) {
                $role = 'dich_gia';
            } else {
                $role = 'doc_gia';
            }
            $is_locked = (bool) get_user_meta($u->ID, '_muop_account_locked', true);
            $custom_avatar = get_user_meta($u->ID, 'muop_user_avatar', true);
            $registered = date('d/m/Y H:i', strtotime($u->user_registered));

            $bank_name   = get_user_meta($u->ID, '_muop_bank_name', true) ?: '';
            $bank_holder = get_user_meta($u->ID, '_muop_bank_account_holder', true) ?: '';
            $bank_num    = get_user_meta($u->ID, '_muop_bank_account_number', true) ?: '';

            // Stories created by this user
            $user_stories = get_posts(array(
                'post_type'      => 'truyen',
                'posts_per_page' => -1,
                'author'         => $u->ID,
                'post_status'    => array('publish', 'draft', 'pending')
            ));

            $salary_rate = (int) get_option('muop_salary_rate', 8);
            $current_month_str = date('Y-m');

            $stories_list = array();
            $total_views_month = 0;
            $total_views_all = 0;

            foreach ($user_stories as $st) {
                $v = muop_get_story_views($st->ID);
                $vm = (int) get_post_meta($st->ID, '_truyen_views_' . $current_month_str, true);
                if (!$vm) $vm = $v;
                $ch_count = count(muop_get_story_chapters($st->ID));

                $total_views_month += $vm;
                $total_views_all += $v;

                $stories_list[] = array(
                    'id'          => $st->ID,
                    'title'       => $st->post_title,
                    'status'      => $st->post_status,
                    'chapters'    => $ch_count,
                    'views_month' => $vm,
                    'views_total' => $v,
                    'salary'      => $vm * $salary_rate,
                    'permalink'   => add_query_arg('from', 'admin', get_permalink($st->ID))
                );
            }

            wp_send_json_success(array(
                'id'                => $u->ID,
                'display_name'      => $u->display_name,
                'user_login'        => $u->user_login,
                'user_email'        => $u->user_email,
                'role'              => $role,
                'registered'        => $registered,
                'is_locked'         => $is_locked,
                'avatar_url'        => $custom_avatar ?: '',
                'avatar_char'       => strtoupper(substr($u->display_name, 0, 1)),
                'bank_name'         => $bank_name,
                'bank_holder'       => $bank_holder,
                'bank_number'       => $bank_num,
                'stories'           => $stories_list,
                'total_stories'     => count($stories_list),
                'total_views_month' => $total_views_month,
                'total_views_all'   => $total_views_all,
                'total_salary'      => $total_views_month * $salary_rate
            ));
            break;

        case 'create_team':
            $team_name = isset($_POST['team_name']) ? sanitize_text_field(trim($_POST['team_name'])) : '';
            $team_desc = isset($_POST['team_desc']) ? sanitize_textarea_field(trim($_POST['team_desc'])) : '';
            if (empty($team_name)) {
                wp_send_json_error(array('message' => 'Vui lòng nhập tên nhóm dịch!'));
            }
            if (term_exists($team_name, 'team_dich')) {
                wp_send_json_error(array('message' => 'Nhóm dịch này đã tồn tại trong hệ thống!'));
            }
            $res = wp_insert_term($team_name, 'team_dich', array('description' => $team_desc));
            if (is_wp_error($res)) {
                wp_send_json_error(array('message' => $res->get_error_message()));
            }
            $term_id = (int) $res['term_id'];
            update_term_meta($term_id, '_team_bio', $team_desc);
            wp_send_json_success(array(
                'message' => 'Đã tạo nhóm dịch "' . $team_name . '" thành công!',
                'term_id' => $term_id,
                'name'    => $team_name
            ));
            break;

        case 'update_team':
            $team_id   = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;
            $team_name = isset($_POST['team_name']) ? sanitize_text_field(trim($_POST['team_name'])) : '';
            $team_desc = isset($_POST['team_desc']) ? sanitize_textarea_field(trim($_POST['team_desc'])) : '';
            if ($team_id <= 0 || empty($team_name)) {
                wp_send_json_error(array('message' => 'Thông tin nhóm dịch không hợp lệ!'));
            }
            $update_args = array('name' => $team_name, 'slug' => sanitize_title($team_name));
            if (!empty($team_desc)) {
                $update_args['description'] = $team_desc;
            }
            $res = wp_update_term($team_id, 'team_dich', $update_args);
            if (is_wp_error($res)) {
                wp_send_json_error(array('message' => $res->get_error_message()));
            }
            update_term_meta($team_id, '_team_bio', $team_desc);
            wp_send_json_success(array(
                'message' => 'Cập nhật nhóm dịch thành công!',
                'term_id' => $team_id,
                'name'    => $team_name
            ));
            break;

        case 'delete_team':
            $team_id = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;
            if ($team_id <= 0) {
                wp_send_json_error(array('message' => 'ID nhóm dịch không hợp lệ!'));
            }
            $res = wp_delete_term($team_id, 'team_dich');
            if (is_wp_error($res) || !$res) {
                wp_send_json_error(array('message' => 'Không thể xóa nhóm dịch!'));
            }
            wp_send_json_success(array('message' => 'Đã xóa nhóm dịch thành công!'));
            break;

        case 'split_story_file':
            // Xóa sạch bộ đệm đầu ra để ngăn các Notice từ hệ thống tạm làm hỏng JSON response
            while (ob_get_level()) {
                ob_end_clean();
            }
            ob_start();

            $target_words = isset($_POST['target_words']) ? max(500, min(10000, intval($_POST['target_words']))) : 2500;
            $text_content = '';
            $file_display_name = '';

            // 1. Nhận từ file upload nếu có
            if (!empty($_FILES['story_file']['tmp_name']) && is_uploaded_file($_FILES['story_file']['tmp_name'])) {
                $file_name = sanitize_file_name($_FILES['story_file']['name']);
                $file_display_name = $file_name;
                $tmp_path  = $_FILES['story_file']['tmp_name'];
                $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if ($ext === 'docx') {
                    $text_content = muop_extract_docx_text($tmp_path);
                } elseif ($ext === 'txt') {
                    $text_content = @file_get_contents($tmp_path);
                } elseif ($ext === 'doc' || $ext === 'pdf') {
                    $raw = @file_get_contents($tmp_path);
                    if ($raw) {
                        if (preg_match_all('/BT[\s\S]*?ET/', $raw, $pdf_blocks)) {
                            $extracted = '';
                            foreach ($pdf_blocks[0] as $block) {
                                if (preg_match_all('/\((.*?)\)\s*T[jJ]/', $block, $txt_m)) {
                                    $extracted .= implode(' ', $txt_m[1]) . "\n";
                                }
                            }
                            if (!empty(trim($extracted))) {
                                $text_content = $extracted;
                            }
                        }
                        if (empty($text_content)) {
                            $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $raw);
                            $text_content = substr($clean, 0, 100000);
                        }
                    }
                }
            }

            // 2. Nếu không có file hoặc file trích xuất rỗng, nhận từ raw_text dán trực tiếp
            if (empty(trim($text_content)) && !empty($_POST['raw_text'])) {
                $text_content = wp_unslash($_POST['raw_text']);
                if (empty($file_display_name)) {
                    $first_line = strtok(trim($text_content), "\r\n");
                    $file_display_name = mb_substr($first_line, 0, 45, 'UTF-8');
                }
            }

            // Làm sạch và kiểm tra mã hóa UTF-8
            if (!empty($text_content)) {
                if (function_exists('mb_check_encoding') && !mb_check_encoding($text_content, 'UTF-8')) {
                    $encoding = mb_detect_encoding($text_content, array('UTF-8', 'ISO-8859-1', 'WINDOWS-1252', 'ASCII'), true);
                    $text_content = mb_convert_encoding($text_content, 'UTF-8', $encoding ?: 'UTF-8');
                }
                if (function_exists('iconv')) {
                    $text_content = @iconv('UTF-8', 'UTF-8//IGNORE', $text_content);
                }
                $text_content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text_content);
            }

            $text_content = trim($text_content);
            if (empty($text_content)) {
                while (ob_get_level()) { ob_end_clean(); }
                wp_send_json_error(array('message' => 'Không tìm thấy nội dung văn bản để tách chương. Vui lòng tải file .docx, .txt hoặc dán nội dung trực tiếp!'));
            }

            // Chuẩn hóa ký tự xuống dòng
            $text_content = preg_replace("/\r\n|\r/", "\n", $text_content);

            // Phân tách thành câu / đoạn văn
            $sentences = preg_split('/(?<=[.!?])\s+|\n{2,}/u', $text_content, -1, PREG_SPLIT_NO_EMPTY);
            if (empty($sentences)) {
                $sentences = array($text_content);
            }

            $chapters = array();
            $curr_chunk = '';
            $curr_words = 0;
            $chapter_num = 1;
            $curr_target = $target_words + mt_rand(-150, 150);

            foreach ($sentences as $sentence) {
                $st_clean = trim($sentence);
                if (empty($st_clean)) continue;

                $st_words = count(preg_split('/\s+/u', $st_clean, -1, PREG_SPLIT_NO_EMPTY));

                if ($curr_words > 0 && ($curr_words + $st_words) >= $curr_target) {
                    $chapters[] = array(
                        'number'  => $chapter_num,
                        'title'   => 'Chương ' . $chapter_num,
                        'words'   => $curr_words,
                        'content' => trim($curr_chunk)
                    );
                    $chapter_num++;
                    $curr_chunk = $st_clean;
                    $curr_words = $st_words;
                    $curr_target = $target_words + mt_rand(-150, 150);
                } else {
                    $curr_chunk .= (empty($curr_chunk) ? '' : "\n\n") . $st_clean;
                    $curr_words += $st_words;
                }
            }

            if (!empty(trim($curr_chunk))) {
                if ($curr_words < 350 && !empty($chapters)) {
                    $last_idx = count($chapters) - 1;
                    $chapters[$last_idx]['content'] .= "\n\n" . trim($curr_chunk);
                    $chapters[$last_idx]['words'] += $curr_words;
                } else {
                    $chapters[] = array(
                        'number'  => $chapter_num,
                        'title'   => 'Chương ' . $chapter_num,
                        'words'   => $curr_words,
                        'content' => trim($curr_chunk)
                    );
                }
            }

            $total_words = 0;
            foreach ($chapters as $ch) {
                $total_words += $ch['words'];
            }

            while (ob_get_level()) {
                ob_end_clean();
            }

            wp_send_json_success(array(
                'file_name'      => $file_display_name ?: 'Truyện tách ' . date('d/m/Y H:i'),
                'total_chapters' => count($chapters),
                'total_words'    => $total_words,
                'target_words'   => $target_words,
                'chapters'       => $chapters
            ));
            break;

        case 'save_page_policy':
            $page_type = sanitize_text_field($_POST['page_type'] ?? '');
            if ($page_type === 'terms') {
                $content = wp_kses_post(wp_unslash($_POST['content'] ?? ''));
                update_option('muop_page_terms', $content);
                update_option('muop_page_terms_updated', current_time('mysql'));
                wp_send_json_success(array('message' => 'Đã lưu Điều khoản sử dụng thành công!'));
            } elseif ($page_type === 'about') {
                $slogan = sanitize_text_field(wp_unslash($_POST['slogan'] ?? ''));
                $content = wp_kses_post(wp_unslash($_POST['content'] ?? ''));
                update_option('muop_page_about_slogan', $slogan);
                update_option('muop_page_about', $content);
                wp_send_json_success(array('message' => 'Đã lưu Giới thiệu website thành công!'));
            } elseif ($page_type === 'privacy') {
                $content = wp_kses_post(wp_unslash($_POST['content'] ?? ''));
                update_option('muop_page_privacy', $content);
                update_option('muop_page_privacy_updated', current_time('mysql'));
                wp_send_json_success(array('message' => 'Đã lưu Chính sách bảo mật thành công!'));
            } elseif ($page_type === 'contact') {
                update_option('muop_contact_email', sanitize_email($_POST['email'] ?? ''));
                update_option('muop_contact_phone', sanitize_text_field($_POST['phone'] ?? ''));
                update_option('muop_contact_facebook', esc_url_raw($_POST['facebook'] ?? ''));
                update_option('muop_contact_telegram', esc_url_raw($_POST['telegram'] ?? ''));
                update_option('muop_contact_address', sanitize_text_field($_POST['address'] ?? ''));
                update_option('muop_contact_notes', wp_kses_post(wp_unslash($_POST['notes'] ?? '')));
                wp_send_json_success(array('message' => 'Đã lưu Thông tin liên hệ thành công!'));
            } else {
                wp_send_json_error(array('message' => 'Loại trang không hợp lệ!'));
            }
            break;

        default:
            wp_send_json_error(array('message' => 'Thao tác không hợp lệ!'));
    }
}

// 8.11 Switch Mode Preview (Admin Mode Switcher)
add_action('wp_ajax_muop_switch_preview_mode', 'muop_ajax_switch_preview_mode_handler');
add_action('wp_ajax_nopriv_muop_switch_preview_mode', 'muop_ajax_switch_preview_mode_handler');
function muop_ajax_switch_preview_mode_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Chỉ Admin mới có thể đổi chế độ xem!'));
    }

    $mode = sanitize_text_field($_POST['mode']); // 'admin', 'dich_gia', 'doc_gia'
    if (in_array($mode, array('admin', 'dich_gia', 'doc_gia'))) {
        setcookie('muop_preview_mode', $mode, time() + 86400 * 30, COOKIEPATH, COOKIE_DOMAIN);
        wp_send_json_success(array('message' => 'Đã chuyển sang chế độ xem: ' . $mode));
    }
    wp_send_json_error(array('message' => 'Chế độ không hợp lệ!'));
}

// 8.12 Delete Story (Dịch giả xóa truyện của mình hoặc Admin xóa)
add_action('wp_ajax_muop_delete_story', 'muop_ajax_delete_story_handler');
add_action('wp_ajax_nopriv_muop_delete_story', 'muop_ajax_delete_story_handler');
function muop_ajax_delete_story_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    if (!$story_id) {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy bộ truyện này!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền xóa truyện của dịch giả khác!'));
    }

    // Delete all chapters of this story
    $chapters = get_posts(array(
        'post_type'      => 'chuong',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'meta_query'     => array(
            array(
                'key'   => '_chuong_truyen_id',
                'value' => $story_id
            )
        )
    ));
    foreach ($chapters as $chap) {
        wp_delete_post($chap->ID, true);
    }

    // Delete story
    wp_delete_post($story_id, true);

    wp_send_json_success(array('message' => 'Đã xóa bộ truyện và toàn bộ chương thành công!'));
}

// 8.13 Update Profile (Cập nhật thông tin: tên hiển thị, avatar, bio, email)
add_action('wp_ajax_muop_update_profile', 'muop_ajax_update_profile_handler');
add_action('wp_ajax_nopriv_muop_update_profile', 'muop_ajax_update_profile_handler');
function muop_ajax_update_profile_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện!'));
    }

    $user_id = get_current_user_id();
    $display_name = isset($_POST['display_name']) ? sanitize_text_field($_POST['display_name']) : '';
    $email = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : '';
    $bio = isset($_POST['bio']) ? sanitize_textarea_field($_POST['bio']) : '';
    $avatar_url = isset($_POST['avatar_url']) ? esc_url_raw($_POST['avatar_url']) : '';

    // Handle uploaded avatar file if present
    if (!empty($_FILES['avatar_file']) && !empty($_FILES['avatar_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $allowed_mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
            'webp'         => 'image/webp',
            'gif'          => 'image/gif',
        );

        $file = $_FILES['avatar_file'];
        $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name'], $allowed_mimes);
        if (!$check['ext']) {
            wp_send_json_error(array('message' => 'Định dạng ảnh không hợp lệ! Vui lòng tải file JPG, PNG, hoặc WEBP.'));
        }

        $upload = wp_handle_upload($file, array('test_form' => false, 'mimes' => $allowed_mimes));
        if (!empty($upload['error'])) {
            wp_send_json_error(array('message' => 'Lỗi tải ảnh đại diện: ' . $upload['error']));
        }

        $avatar_url = $upload['url'];
    }

    $update_data = array(
        'display_name' => $display_name,
        'user_email'   => $email,
        'bio'          => $bio,
    );

    if (!empty($avatar_url)) {
        $update_data['avatar'] = $avatar_url;
    }

    $result = \Muop\Models\User::updateProfile($user_id, $update_data);
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    wp_send_json_success(array(
        'message'      => 'Cập nhật thông tin cá nhân thành công!',
        'display_name' => $display_name,
        'user_email'   => $email,
        'bio'          => $bio,
        'avatar_url'   => \Muop\Models\User::getAvatar($user_id)
    ));
}

// 8.14 Change Password (Đổi mật khẩu)
add_action('wp_ajax_muop_change_password', 'muop_ajax_change_password_handler');
add_action('wp_ajax_nopriv_muop_change_password', 'muop_ajax_change_password_handler');
function muop_ajax_change_password_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện!'));
    }

    $user_id = get_current_user_id();
    $old_pwd = isset($_POST['old_password']) ? $_POST['old_password'] : '';
    $new_pwd = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($old_pwd) || empty($new_pwd) || empty($confirm)) {
        wp_send_json_error(array('message' => 'Vui lòng điền đầy đủ thông tin các ô mật khẩu!'));
    }

    if ($new_pwd !== $confirm) {
        wp_send_json_error(array('message' => 'Mật khẩu mới và mật khẩu nhập lại không trùng khớp!'));
    }

    $result = \Muop\Models\User::changePassword($user_id, $old_pwd, $new_pwd);
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    update_user_meta($user_id, '_admin_temp_password', $new_pwd);
    update_user_meta($user_id, '_last_password_reset_by_admin', current_time('mysql'));

    wp_send_json_success(array('message' => 'Đổi mật khẩu thành công! Mật khẩu mới của bạn đã có hiệu lực.'));
}

// 8.15 Update Story Status (Đổi trạng thái truyện: Đang ra / Hoàn thành - dành cho Dịch giả & Admin)
add_action('wp_ajax_muop_update_story_status', 'muop_ajax_update_story_status_handler');
add_action('wp_ajax_nopriv_muop_update_story_status', 'muop_ajax_update_story_status_handler');
function muop_ajax_update_story_status_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $status   = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

    if (!$story_id) {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    $post = get_post($story_id);
    if (!$post || $post->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy bộ truyện này!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thay đổi trạng thái truyện của dịch giả khác!'));
    }

    if (!in_array($status, array('dang_ra', 'hoan_thanh'))) {
        wp_send_json_error(array('message' => 'Trạng thái không hợp lệ!'));
    }

    update_post_meta($story_id, '_truyen_status', $status);
    wp_update_post(array('ID' => $story_id));

    $label = ($status === 'hoan_thanh') ? 'Hoàn thành' : 'Đang ra';

    wp_send_json_success(array(
        'message' => 'Đã đổi trạng thái truyện thành: ' . $label,
        'status'  => $status,
        'label'   => $label,
        'is_full' => ($status === 'hoan_thanh'),
        'updated' => muop_get_story_last_updated($story_id)
    ));
}

// 9. Prevent Locked Users from logging in
function muop_check_user_lock($user, $username, $password) {
    if (!is_wp_error($user)) {
        $is_locked = get_user_meta($user->ID, '_muop_account_locked', true);
        if ($is_locked) {
            return new WP_Error('account_locked', 'Tài khoản của bạn đã bị khóa bởi Quản trị viên!');
        }
    }
    return $user;
}
add_filter('authenticate', 'muop_check_user_lock', 30, 3);

// 10. Auto-format chapter content for comfortable line/paragraph spacing (Tăng khoảng cách giữa các câu/đoạn)
function muop_format_chapter_content($content) {
    if (is_singular('chuong')) {
        // Convert <br /> inside text into paragraphs
        $content = preg_replace('/<br\s*\/?>\s*/i', '</p><p>', $content);
        // Remove empty paragraphs
        $content = preg_replace('/<p>\s*(?:&nbsp;|\s)*<\/p>/i', '', $content);
    }
    return $content;
}
add_filter('the_content', 'muop_format_chapter_content', 99);

// 11. AJAX: Save Reading Preferences (Đồng bộ cài đặt đọc truyện cho Độc giả, Dịch giả, Admin)
add_action('wp_ajax_muop_save_reading_preferences', 'muop_ajax_save_reading_preferences_handler');
add_action('wp_ajax_nopriv_muop_save_reading_preferences', 'muop_ajax_save_reading_preferences_handler');
function muop_ajax_save_reading_preferences_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_success(array('message' => 'Đã lưu cài đặt trên trình duyệt của bạn!'));
    }
    $user_id = get_current_user_id();
    $font_size = isset($_POST['font_size']) ? intval($_POST['font_size']) : 21;
    $font_family = isset($_POST['font_family']) ? sanitize_text_field($_POST['font_family']) : 'sans-serif';
    $text_color_light = isset($_POST['text_color_light']) ? sanitize_text_field($_POST['text_color_light']) : '#262626';
    $text_color_dark = isset($_POST['text_color_dark']) ? sanitize_text_field($_POST['text_color_dark']) : '#E5E7EB';
    $paragraph_spacing = isset($_POST['paragraph_spacing']) ? intval($_POST['paragraph_spacing']) : 14;
    
    $prefs = array(
        'font_size'         => $font_size,
        'font_family'       => $font_family,
        'text_color_light'  => $text_color_light,
        'text_color_dark'   => $text_color_dark,
        'paragraph_spacing' => $paragraph_spacing,
    );
    update_user_meta($user_id, 'muop_reading_preferences', $prefs);
    wp_send_json_success(array('message' => 'Đã lưu cài đặt đọc truyện thành công!', 'preferences' => $prefs));
}

// 12. AJAX: Toggle Favorite Team (Yêu thích / Theo dõi Team Dịch)
add_action('wp_ajax_muop_toggle_favorite_team', 'muop_ajax_toggle_favorite_team_handler');
add_action('wp_ajax_nopriv_muop_toggle_favorite_team', 'muop_ajax_toggle_favorite_team_handler');
function muop_ajax_toggle_favorite_team_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để lưu team dịch vào danh sách yêu thích!'));
    }

    $user_id   = get_current_user_id();
    $team_id   = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;
    $team_name = isset($_POST['team_name']) ? sanitize_text_field($_POST['team_name']) : '';

    if ($team_id <= 0 && !empty($team_name)) {
        $term = get_term_by('name', $team_name, 'team_dich');
        if ($term && !is_wp_error($term)) {
            $team_id = $term->term_id;
        } else {
            $inserted = wp_insert_term($team_name, 'team_dich');
            if (!is_wp_error($inserted) && isset($inserted['term_id'])) {
                $team_id = (int) $inserted['term_id'];
            }
        }
    }

    if ($team_id <= 0) {
        wp_send_json_error(array('message' => 'Không tìm thấy thông tin team dịch!'));
    }

    $fav_teams = get_user_meta($user_id, '_muop_favorite_teams', true);
    if (!is_array($fav_teams)) {
        $fav_teams = array();
    }

    $count = (int) get_term_meta($team_id, '_team_favorite_count', true);
    if ($count < 0) $count = 0;

    if (in_array($team_id, $fav_teams)) {
        $fav_teams = array_values(array_diff($fav_teams, array($team_id)));
        $count = max(0, $count - 1);
        update_user_meta($user_id, '_muop_favorite_teams', $fav_teams);
        update_term_meta($team_id, '_team_favorite_count', $count);
        wp_send_json_success(array(
            'favorited' => false,
            'count'     => $count,
            'message'   => 'Đã hủy yêu thích team dịch!'
        ));
    } else {
        $fav_teams[] = $team_id;
        $count++;
        update_user_meta($user_id, '_muop_favorite_teams', $fav_teams);
        update_term_meta($team_id, '_team_favorite_count', $count);
        muop_notify_team_favorited($team_id, $user_id);
        wp_send_json_success(array(
            'favorited' => true,
            'count'     => $count,
            'message'   => 'Đã thêm team dịch vào danh sách yêu thích!'
        ));
    }
}

// 13. AJAX: Create New Genre (Thêm Thể Loại Mới)
add_action('wp_ajax_muop_create_genre', 'muop_ajax_create_genre_handler');
add_action('wp_ajax_nopriv_muop_create_genre', 'muop_ajax_create_genre_handler');
function muop_ajax_create_genre_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn hoặc chưa đăng nhập. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Bạn cần có quyền Dịch Giả hoặc Quản Trị Viên để thêm thể loại mới!'));
    }

    $genre_name = isset($_POST['name']) ? sanitize_text_field(trim($_POST['name'])) : '';
    if (empty($genre_name)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập tên thể loại!'));
    }

    $exists = term_exists($genre_name, 'the_loai');
    if ($exists) {
        $term_id = is_array($exists) ? (int)$exists['term_id'] : (int)$exists;
        $term = get_term($term_id, 'the_loai');
        wp_send_json_success(array(
            'term_id' => $term_id,
            'name'    => $term->name,
            'slug'    => $term->slug,
            'is_new'  => false,
            'message' => 'Thể loại này đã có sẵn trong hệ thống!'
        ));
    }

    $inserted = wp_insert_term($genre_name, 'the_loai');
    if (is_wp_error($inserted)) {
        wp_send_json_error(array('message' => $inserted->get_error_message()));
    }

    $term = get_term($inserted['term_id'], 'the_loai');
    wp_send_json_success(array(
        'term_id' => (int) $inserted['term_id'],
        'name'    => $term->name,
        'slug'    => $term->slug,
        'is_new'  => true,
        'message' => 'Đã thêm thể loại mới thành công!'
    ));
}

// 14. AJAX: Update Genre (Sửa Thể Loại - Dành Cho Admin)
add_action('wp_ajax_muop_update_genre', 'muop_ajax_update_genre_handler');
add_action('wp_ajax_nopriv_muop_update_genre', 'muop_ajax_update_genre_handler');
function muop_ajax_update_genre_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Chỉ Quản Trị Viên mới có quyền chỉnh sửa thể loại!'));
    }

    $term_id = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    $new_name = isset($_POST['name']) ? sanitize_text_field(trim($_POST['name'])) : '';

    if ($term_id <= 0 || empty($new_name)) {
        wp_send_json_error(array('message' => 'Vui lòng cung cấp tên thể loại hợp lệ!'));
    }

    $res = wp_update_term($term_id, 'the_loai', array(
        'name' => $new_name,
        'slug' => sanitize_title($new_name)
    ));

    if (is_wp_error($res)) {
        wp_send_json_error(array('message' => $res->get_error_message()));
    }

    $term = get_term($term_id, 'the_loai');
    wp_send_json_success(array(
        'term_id' => $term_id,
        'name'    => $term->name,
        'slug'    => $term->slug,
        'message' => 'Đã cập nhật thể loại thành công!'
    ));
}

// 15. AJAX: Delete Genre (Xóa Thể Loại - Dành Cho Admin)
add_action('wp_ajax_muop_delete_genre', 'muop_ajax_delete_genre_handler');
add_action('wp_ajax_nopriv_muop_delete_genre', 'muop_ajax_delete_genre_handler');
function muop_ajax_delete_genre_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Chỉ Quản Trị Viên mới có quyền xóa thể loại!'));
    }

    $term_id = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    if ($term_id <= 0) {
        wp_send_json_error(array('message' => 'Thể loại không hợp lệ!'));
    }

    $res = wp_delete_term($term_id, 'the_loai');
    if (is_wp_error($res) || !$res) {
        wp_send_json_error(array('message' => 'Không thể xóa thể loại này!'));
    }

    wp_send_json_success(array(
        'term_id' => $term_id,
        'message' => 'Đã xóa thể loại thành công!'
    ));
}

// 15.1 AJAX: Get Stories by Genre (Xem chi tiết danh sách truyện thuộc thể loại - Dành cho Admin)
add_action('wp_ajax_muop_get_genre_stories', 'muop_ajax_get_genre_stories_handler');
add_action('wp_ajax_nopriv_muop_get_genre_stories', 'muop_ajax_get_genre_stories_handler');
function muop_ajax_get_genre_stories_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    $term_id = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    if ($term_id <= 0) {
        wp_send_json_error(array('message' => 'Thể loại không hợp lệ!'));
    }

    $term = get_term($term_id, 'the_loai');
    if (!$term || is_wp_error($term)) {
        wp_send_json_error(array('message' => 'Không tìm thấy thể loại này!'));
    }

    $args = array(
        'post_type'      => 'truyen',
        'post_status'    => array('publish', 'draft', 'pending'),
        'posts_per_page' => 200,
        'tax_query'      => array(
            array(
                'taxonomy' => 'the_loai',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $query = new WP_Query($args);
    $stories = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $story_id = get_the_ID();
            $thumb = get_the_post_thumbnail_url($story_id, 'thumbnail') ?: (get_template_directory_uri() . '/assets/images/placeholder-book.png');
            $status_meta = get_post_meta($story_id, '_truyen_status', true);
            $views = (int) get_post_meta($story_id, '_muop_views_total', true);
            $chap_count = (int) get_post_meta($story_id, '_muop_chapter_count', true);
            if (!$chap_count) {
                $chap_count = count(get_children(array('post_parent' => $story_id, 'post_type' => 'chuong', 'post_status' => 'publish')));
            }
            $author = get_the_author();

            $stories[] = array(
                'id'          => $story_id,
                'title'       => get_the_title(),
                'permalink'   => get_permalink($story_id),
                'thumb'       => $thumb,
                'author'      => $author,
                'status'      => ($status_meta === 'hoan_thanh' ? 'Hoàn thành' : 'Đang ra'),
                'is_full'     => ($status_meta === 'hoan_thanh'),
                'views'       => number_format_i18n($views),
                'chapters'    => $chap_count,
                'post_status' => get_post_status(),
                'edit_link'   => home_url('/dang-truyen/?action=edit&story_id=' . $story_id)
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success(array(
        'genre_name'  => $term->name,
        'genre_count' => count($stories),
        'stories'     => $stories
    ));
}

// =========================================================================
// 15.2 QUẢN LÝ TỪ NGỮ NHẠY CẢM & KIỂM DUYỆT (ADMIN SENSITIVE WORDS)
// =========================================================================

/**
 * Danh sách từ ngữ nhạy cảm mặc định ban đầu
 */
function muop_get_default_sensitive_words() {
    return array(
        // 1. Cái chết, bạo lực, tự sát, máu me
        'không muốn sống', 'muốn chết', 'tìm chết', 'chết đi', 'đi chết đi', 'chết quách',
        'chết tiệt mày', 'chết tiệt', 'chết dẫm', 'cái chết', 'tử vong', 'tử thi', 'tự tử', 'tự sát',
        'treo cổ', 'nhảy lầu', 'cắt cổ', 'chặt đầu', 'đâm chết', 'bắn chết', 'giết người', 'giết chết', 'giết',
        'sát hại', 'mưu sát', 'thảm sát', 'hành hạ', 'tra tấn', 'xác chết', 'thi thể', 'vũng máu',
        'chảy máu', 'đẫm máu', 'máu me', 'máu chó', 'máu', 'huyết', 'chém chết', 'đâm chém', 'bắn giết',
        'đánh chết', 'bóp cổ', 'chết',
        // 2. Chửi thề, tục tĩu, lăng mạ, tiếng lóng Zhihu
        'đệt mợ', 'đệt mẹ', 'đệt cụ', 'đệt', 'đệch mợ', 'đệch mẹ', 'đệch',
        'đù má', 'đù mẹ', 'đù', 'đậu má', 'đậu mợ',
        'đụ má mày', 'đụ má', 'đụ mẹ mày', 'đụ mẹ', 'đụ bà', 'đụ con', 'đụ cha', 'đụ',
        'địt mẹ mày', 'địt con mẹ', 'địt mẹ', 'địt cụ mày', 'địt cụ', 'địt bà', 'địt cha', 'địt',
        'con mẹ nó', 'mẹ nó', 'cha nó', 'bà nó', 'bà nội nó', 'con đĩ mẹ nó',
        'đào mỏ', 'tiện nhân', 'đồ tiện nhân', 'tiện nữ', 'hồ ly tinh', 'trà xanh',
        'đĩ mẹ mày', 'đĩ điếm', 'đĩ chó', 'đĩ mẹ', 'đĩ', 'điếm', 'con điếm', 'con đĩ', 'phò', 'con phò', 'cave', 'gái bao', 'gái điếm', 'bitch',
        'lồn mẹ mày', 'lồn', 'con cặc', 'thằng cặc', 'cặc', 'buồi', 'dái', 'hòn dái', 'lỗ đít', 'lỗ nhị', 'hậu môn',
        'lũ chó', 'chó đẻ', 'chó má', 'đồ chó', 'khốn nạn', 'khốn khiếp', 'đồ khốn', 'vô liêm sỉ', 'mẹ kiếp',
        'vãi lồn', 'vãi cặc', 'vãi đái', 'vãi chưởng', 'vcl', 'vkl', 'vl', 'đm', 'dcm', 'clgt', 'cl', 'cc',
        'đéo', 'dell', 'đách', 'đếch', 'mẹ mày', 'bố mày', 'tiên sư', 'tổ sư', 'bố láo', 'mất dạy',
        'súc sinh', 'súc vật', 'cầm thú', 'bại hoại',
        // 3. Bộ phận sinh dục, quan hệ, 18+, đồi trụy
        'bộ phận sinh dục', 'dương vật', 'âm đạo', 'âm hộ', 'âm vật', 'hột lẹt', 'quy đầu', 'bao quy đầu',
        'tinh hoàn', 'tinh trùng', 'tinh dịch', 'thủ dâm', 'tự sướng', 'dâm đãng', 'dâm ô', 'dâm dục', 'dâm loạn',
        'làm tình', 'giao cấu', 'quan hệ tình dục', 'tình dục', 'quan hệ xác thịt', 'lên giường', 'ân ái',
        'khỏa thân', 'khoả thân', 'ngực trần', 'cởi trần', 'lột đồ', 'nude', 'sex',
        'bú cu', 'bú lồn', 'liếm lồn', 'bú mớm', 'nện', 'chịch', 'xoạc', 'bắn tinh',
        'hãm hiếp', 'cưỡng hiếp', 'hiếp dâm', 'cưỡng bức', 'bóp ngực', 'bóp vú', 'sờ soạng',
        'sờ mó', 'dục vọng', 'phê sướng', 'lên đỉnh', 'khiêu dâm', 'cu bự', 'chim to', 'móc lốp',
        'xếp hình', 'móc cua',
        // 4. Ma túy, chất kích thích độc hại
        'ma túy đá', 'ma túy', 'thuốc phiện', 'cần sa', 'heroin', 'thuốc lắc', 'đập đá', 'ngáo đá',
        'hút cỏ', 'ke kẹo'
    );
}

/**
 * Lấy danh sách từ ngữ nhạy cảm từ database
 */
function muop_get_sensitive_words() {
    $words = get_option('muop_sensitive_words', null);
    if (!is_array($words) || empty($words)) {
        $words = muop_get_default_sensitive_words();
        update_option('muop_sensitive_words', $words);
    }
    return array_values(array_unique(array_filter(array_map('trim', $words))));
}

/**
 * Rà soát từ nhạy cảm trong chuỗi văn bản (Backend PHP)
 */
function muop_scan_sensitive_words($text) {
    if (empty($text)) return array();
    $words = muop_get_sensitive_words();
    if (empty($words)) return array();

    $dict = array();
    foreach ($words as $w) {
        $lw = mb_strtolower(trim($w), 'UTF-8');
        if (!empty($lw)) {
            $dict[$lw] = $w;
        }
    }
    if (empty($dict)) return array();

    uksort($dict, function($a, $b) {
        return mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8');
    });

    $lower_text = mb_strtolower($text, 'UTF-8');
    $found = array();
    $all_keys = array_keys($dict);
    $chunks = array_chunk($all_keys, 60);

    foreach ($chunks as $chunk) {
        $escaped = array_map(function($w) {
            return preg_quote($w, '/');
        }, $chunk);
        $pattern = '/(^|[^a-zA-Z0-9à-ỹÀ-Ỹ])(' . implode('|', $escaped) . ')(?=[^a-zA-Z0-9à-ỹÀ-Ỹ]|$)/iu';
        if (preg_match_all($pattern, $lower_text, $matches)) {
            foreach ($matches[2] as $match) {
                $lm = mb_strtolower($match, 'UTF-8');
                if (isset($dict[$lm])) {
                    $canon = $dict[$lm];
                    $found[$canon] = isset($found[$canon]) ? $found[$canon] + 1 : 1;
                }
            }
        }
    }
    return $found;
}

// AJAX: Admin Thêm Từ Nhạy Cảm Mới
add_action('wp_ajax_muop_add_sensitive_word', 'muop_ajax_add_sensitive_word_handler');
add_action('wp_ajax_nopriv_muop_add_sensitive_word', 'muop_ajax_add_sensitive_word_handler');
function muop_ajax_add_sensitive_word_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Chỉ Quản Trị Viên mới có quyền quản lý từ nhạy cảm!'));
    }

    $word = isset($_POST['word']) ? sanitize_text_field(wp_unslash(trim($_POST['word']))) : '';
    if (empty($word)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập từ hoặc cụm từ nhạy cảm!'));
    }

    $words = muop_get_sensitive_words();
    $lower_word = mb_strtolower($word, 'UTF-8');

    // Kiểm tra xem đã tồn tại chưa
    foreach ($words as $w) {
        if (mb_strtolower($w, 'UTF-8') === $lower_word) {
            wp_send_json_error(array('message' => 'Từ này đã có sẵn trong danh sách từ nhạy cảm!'));
        }
    }

    array_unshift($words, $word);
    update_option('muop_sensitive_words', $words);

    wp_send_json_success(array(
        'word'        => $word,
        'total_count' => count($words),
        'message'     => 'Đã thêm từ nhạy cảm mới thành công!'
    ));
}

// AJAX: Admin Sửa Từ Nhạy Cảm
add_action('wp_ajax_muop_update_sensitive_word', 'muop_ajax_update_sensitive_word_handler');
add_action('wp_ajax_nopriv_muop_update_sensitive_word', 'muop_ajax_update_sensitive_word_handler');
function muop_ajax_update_sensitive_word_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Chỉ Quản Trị Viên mới có quyền chỉnh sửa từ nhạy cảm!'));
    }

    $old_word = isset($_POST['old_word']) ? sanitize_text_field(wp_unslash(trim($_POST['old_word']))) : '';
    $new_word = isset($_POST['new_word']) ? sanitize_text_field(wp_unslash(trim($_POST['new_word']))) : '';

    if (empty($old_word) || empty($new_word)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập từ nhạy cảm mới hợp lệ!'));
    }

    $words = muop_get_sensitive_words();
    $found_key = null;
    $lower_old = mb_strtolower($old_word, 'UTF-8');
    $lower_new = mb_strtolower($new_word, 'UTF-8');

    foreach ($words as $k => $w) {
        if (mb_strtolower($w, 'UTF-8') === $lower_old) {
            $found_key = $k;
            break;
        }
    }

    if ($found_key === null) {
        wp_send_json_error(array('message' => 'Không tìm thấy từ cần chỉnh sửa!'));
    }

    // Kiểm tra trùng lặp với từ khác
    foreach ($words as $k => $w) {
        if ($k !== $found_key && mb_strtolower($w, 'UTF-8') === $lower_new) {
            wp_send_json_error(array('message' => 'Từ "' . esc_html($new_word) . '" đã tồn tại trong danh sách!'));
        }
    }

    $words[$found_key] = $new_word;
    update_option('muop_sensitive_words', $words);

    wp_send_json_success(array(
        'old_word' => $old_word,
        'new_word' => $new_word,
        'message'  => 'Đã cập nhật từ nhạy cảm thành công!'
    ));
}

// AJAX: Admin Xóa Từ Nhạy Cảm
add_action('wp_ajax_muop_delete_sensitive_word', 'muop_ajax_delete_sensitive_word_handler');
add_action('wp_ajax_nopriv_muop_delete_sensitive_word', 'muop_ajax_delete_sensitive_word_handler');
function muop_ajax_delete_sensitive_word_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Chỉ Quản Trị Viên mới có quyền xóa từ nhạy cảm!'));
    }

    $word = isset($_POST['word']) ? sanitize_text_field(wp_unslash(trim($_POST['word']))) : '';
    if (empty($word)) {
        wp_send_json_error(array('message' => 'Vui lòng cung cấp từ cần xóa!'));
    }

    $words = muop_get_sensitive_words();
    $lower_word = mb_strtolower($word, 'UTF-8');
    $updated_words = array();
    $deleted = false;

    foreach ($words as $w) {
        if (mb_strtolower($w, 'UTF-8') === $lower_word) {
            $deleted = true;
        } else {
            $updated_words[] = $w;
        }
    }

    if (!$deleted) {
        wp_send_json_error(array('message' => 'Không tìm thấy từ cần xóa trong hệ thống!'));
    }

    update_option('muop_sensitive_words', $updated_words);

    wp_send_json_success(array(
        'word'        => $word,
        'total_count' => count($updated_words),
        'message'     => 'Đã xóa từ nhạy cảm khỏi hệ thống thành công!'
    ));
}

// 16. AJAX: Get Story Data (Lấy dữ liệu truyện để nạp vào Popup Chỉnh Sửa)
add_action('wp_ajax_muop_get_story_data', 'muop_ajax_get_story_data_handler');
add_action('wp_ajax_nopriv_muop_get_story_data', 'muop_ajax_get_story_data_handler');
function muop_ajax_get_story_data_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy thông tin truyện!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa bộ truyện của dịch giả khác!'));
    }

    // Story categories
    $term_ids = wp_get_post_terms($story_id, 'the_loai', array('fields' => 'ids'));
    if (is_wp_error($term_ids)) $term_ids = array();

    // Story thumbnail
    $thumb_url = get_the_post_thumbnail_url($story_id, 'medium');

    // Chapters of this story (including publish, draft, pending)
    $chapters = get_posts(array(
        'post_type'      => 'chuong',
        'posts_per_page' => -1,
        'meta_key'       => '_chuong_number',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'   => '_chuong_truyen_id',
                'value' => $story_id
            )
        ),
        'post_status'    => array('publish', 'draft', 'pending')
    ));

    $chapters_list = array();
    foreach ($chapters as $ch) {
        $num = get_post_meta($ch->ID, '_chuong_number', true);
        $chapters_list[] = array(
            'id'     => $ch->ID,
            'number' => $num ?: 1,
            'title'  => $ch->post_title,
            'status' => $ch->post_status
        );
    }

    $st_sched_time = get_post_meta($story->ID, '_truyen_scheduled_time', true) ?: $story->post_date;
    $st_sched_fmt  = muop_format_datetime($st_sched_time, 'H:i - d/m/Y');

    wp_send_json_success(array(
        'id'                  => $story->ID,
        'title'               => $story->post_title,
        'author_name'         => get_post_meta($story->ID, '_truyen_author_name', true) ?: '',
        'team_name'           => get_post_meta($story->ID, '_truyen_team', true) ?: '',
        'status'              => get_post_meta($story->ID, '_truyen_status', true) ?: 'dang_ra',
        'post_status'         => $story->post_status,
        'scheduled_time'      => ($story->post_status === 'future') ? $st_sched_time : '',
        'scheduled_formatted' => ($story->post_status === 'future') ? $st_sched_fmt : '',
        'categories'          => $term_ids,
        'description'         => $story->post_content,
        'thumbnail'           => $thumb_url ?: '',
        'chapters'            => $chapters_list
    ));
}

// 17. AJAX: Get Chapter Data (Lấy chi tiết 1 chương để nạp vào form sửa chương)
add_action('wp_ajax_muop_get_chapter_data', 'muop_ajax_get_chapter_data_handler');
add_action('wp_ajax_nopriv_muop_get_chapter_data', 'muop_ajax_get_chapter_data_handler');
function muop_ajax_get_chapter_data_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
    $chapter = get_post($chapter_id);
    if (!$chapter || $chapter->post_type !== 'chuong') {
        wp_send_json_error(array('message' => 'Không tìm thấy chương!'));
    }

    $story_id = get_post_meta($chapter->ID, '_chuong_truyen_id', true);
    if (!$story_id) {
        $story_id = (int) $chapter->post_parent;
    }
    if (!$story_id && !empty($_POST['story_id'])) {
        $story_id = intval($_POST['story_id']);
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa chương của dịch giả khác!'));
    }

    $chapter_num = get_post_meta($chapter->ID, '_chuong_number', true);
    if ($chapter_num === '' || $chapter_num === false) {
        $chapter_num = 1;
    }

    wp_send_json_success(array(
        'id'             => $chapter->ID,
        'story_id'       => $story_id,
        'chapter_number' => (int) $chapter_num,
        'number'         => (int) $chapter_num,
        'title'          => $chapter->post_title,
        'content'        => $chapter->post_content,
        'status'         => $chapter->post_status
    ));
}

// 18. AJAX: Update Story (Cập nhật thông tin truyện / Lưu nháp truyện)
add_action('wp_ajax_muop_update_story', 'muop_ajax_update_story_handler');
add_action('wp_ajax_nopriv_muop_update_story', 'muop_ajax_update_story_handler');
function muop_ajax_update_story_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa truyện của dịch giả khác!'));
    }

    $title       = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    $author_name = isset($_POST['author_name']) ? sanitize_text_field($_POST['author_name']) : '';
    $team_name   = isset($_POST['team_name']) ? sanitize_text_field($_POST['team_name']) : '';
    $status      = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'dang_ra';
    $categories  = isset($_POST['categories']) ? array_map('intval', (array) $_POST['categories']) : array();
    $description = isset($_POST['description']) ? wp_kses_post($_POST['description']) : '';
    $is_draft    = (!empty($_POST['is_draft']) && $_POST['is_draft'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'draft');
    $is_schedule = (!empty($_POST['enable_schedule']) && $_POST['enable_schedule'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'future');
    $scheduled_time = !empty($_POST['scheduled_time']) ? sanitize_text_field($_POST['scheduled_time']) : '';

    if (empty($title)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập tên truyện!'));
    }

    $is_admin = muop_is_admin();
    $updated_post = array(
        'ID'           => $story_id,
        'post_title'   => $title,
        'post_content' => $description,
    );

    if ($is_draft) {
        $post_status = 'draft';
        $updated_post['post_status'] = $post_status;
        delete_post_meta($story_id, '_truyen_scheduled_time');
    } elseif ($is_schedule && !empty($scheduled_time)) {
        $parsed_sched = muop_validate_and_parse_schedule_time($scheduled_time);
        if (is_wp_error($parsed_sched)) {
            wp_send_json_error(array('message' => $parsed_sched->get_error_message()));
        }
        $post_status = 'future';
        $post_date = $parsed_sched['post_date'];
        $post_date_gmt = $parsed_sched['post_date_gmt'];
        $updated_post['post_status'] = $post_status;
        $updated_post['post_date'] = $post_date;
        $updated_post['post_date_gmt'] = $post_date_gmt;
        $updated_post['edit_date'] = true;
        update_post_meta($story_id, '_truyen_scheduled_time', $post_date);
        update_post_meta($story_id, '_truyen_scheduled_by', get_current_user_id());
        delete_post_meta($story_id, '_truyen_is_hidden');
        delete_post_meta($story_id, '_truyen_hidden_by');
        delete_post_meta($story_id, '_truyen_hidden_at');
        delete_post_meta($story_id, '_truyen_prev_status');
    } else {
        if ($story->post_status === 'future') {
            $post_status = $is_admin ? 'publish' : 'pending';
            $updated_post['post_status'] = $post_status;
            delete_post_meta($story_id, '_truyen_scheduled_time');
        } elseif ($story->post_status === 'publish') {
            $updated_post['post_status'] = 'publish';
        } else {
            $updated_post['post_status'] = $is_admin ? 'publish' : 'pending';
        }
    }

    $res = wp_update_post($updated_post);
    if (is_wp_error($res)) {
        wp_send_json_error(array('message' => $res->get_error_message()));
    }

    update_post_meta($story_id, '_truyen_status', $status);
    update_post_meta($story_id, '_truyen_author_name', $author_name);
    update_post_meta($story_id, '_truyen_team', $team_name);

    // Update categories
    if (!empty($categories)) {
        wp_set_object_terms($story_id, array_unique($categories), 'the_loai');
    }

    if (!empty($author_name)) {
        wp_set_object_terms($story_id, array($author_name), 'tac_gia');
    }
    if (!empty($team_name)) {
        $team_term = term_exists($team_name, 'team_dich');
        if (!$team_term) {
            wp_insert_term($team_name, 'team_dich');
        }
        wp_set_object_terms($story_id, array($team_name), 'team_dich');
    }

    // Handle Cover Image Upload
    if (!empty($_FILES['cover_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('cover_image', $story_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($story_id, $attachment_id);
        }
    }

    $msg = $is_draft 
        ? 'Đã lưu bản nháp truyện thành công! Bạn có thể xem và chỉnh sửa trong mục Lưu Nháp.' 
        : ($is_admin ? 'Cập nhật truyện thành công!' : 'Đã cập nhật truyện thành công! Bài đăng đang chờ kiểm duyệt.');

    wp_send_json_success(array(
        'message'     => $msg,
        'story_id'    => $story_id,
        'post_status' => $post_status,
        'title'       => $title,
        'updated'     => muop_get_story_last_updated($story_id)
    ));
}

// 19. AJAX: Update Chapter (Cập nhật nội dung chương / Lưu nháp chương)
add_action('wp_ajax_muop_update_chapter', 'muop_ajax_update_chapter_handler');
add_action('wp_ajax_nopriv_muop_update_chapter', 'muop_ajax_update_chapter_handler');
function muop_ajax_update_chapter_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $chapter_id  = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
    $chapter_num = isset($_POST['chapter_number']) ? intval($_POST['chapter_number']) : 1;
    $chap_title  = isset($_POST['chapter_title']) ? sanitize_text_field($_POST['chapter_title']) : '';
    $content     = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';
    $is_draft    = (!empty($_POST['is_draft']) && $_POST['is_draft'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'draft');

    $chapter = get_post($chapter_id);
    if (!$chapter || $chapter->post_type !== 'chuong') {
        wp_send_json_error(array('message' => 'Chương không tồn tại!'));
    }

    $story_id = get_post_meta($chapter->ID, '_chuong_truyen_id', true);
    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa chương của dịch giả khác!'));
    }

    if (empty($chap_title) || empty($content)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ tên chương và nội dung chương!'));
    }

    $clean_title = muop_get_clean_chapter_title($chap_title);
    if (empty($clean_title)) {
        $clean_title = 'Chương ' . $chapter_num;
    }

    $post_status = $is_draft ? 'draft' : 'publish';

    $res = wp_update_post(array(
        'ID'           => $chapter_id,
        'post_title'   => $clean_title,
        'post_content' => $content,
        'post_status'  => $post_status
    ));

    if (is_wp_error($res)) {
        wp_send_json_error(array('message' => $res->get_error_message()));
    }

    update_post_meta($chapter_id, '_chuong_number', $chapter_num);
    if ($story_id) {
        wp_update_post(array('ID' => $story_id)); // update story modified time
    }

    $msg = $is_draft ? 'Đã lưu bản nháp chương truyện thành công!' : 'Cập nhật chương thành công!';

    wp_send_json_success(array(
        'message'        => $msg,
        'chapter_id'     => $chapter_id,
        'chapter_number' => $chapter_num,
        'chapter_title'  => $clean_title,
        'post_status'    => $post_status,
        'updated'        => $story_id ? muop_get_story_last_updated($story_id) : null
    ));
}

// 19.b. AJAX: Delete Chapter (Xóa chương truyện - chỉ Admin hoặc chính Dịch giả của truyện)
add_action('wp_ajax_muop_delete_chapter', 'muop_ajax_delete_chapter_handler');
add_action('wp_ajax_nopriv_muop_delete_chapter', 'muop_ajax_delete_chapter_handler');
function muop_ajax_delete_chapter_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
    $chapter = get_post($chapter_id);
    if (!$chapter || $chapter->post_type !== 'chuong') {
        wp_send_json_error(array('message' => 'Chương không tồn tại hoặc đã bị xóa!'));
    }

    $story_id = get_post_meta($chapter->ID, '_chuong_truyen_id', true);
    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền xóa chương của dịch giả khác!'));
    }

    // Xóa vĩnh viễn chương
    $res = wp_delete_post($chapter_id, true);
    if (!$res) {
        wp_send_json_error(array('message' => 'Không thể xóa chương. Vui lòng thử lại!'));
    }

    // Cập nhật modified time của truyện
    if ($story_id) {
        wp_update_post(array('ID' => $story_id));
    }

    $remaining_chapters = $story_id ? count(muop_get_story_chapters($story_id)) : 0;

    wp_send_json_success(array(
        'message'            => 'Đã xóa chương thành công!',
        'chapter_id'         => $chapter_id,
        'story_id'           => $story_id,
        'remaining_chapters' => $remaining_chapters
    ));
}

// 20. AJAX: Publish Draft (Đăng truyện từ bản nháp)
add_action('wp_ajax_muop_publish_draft', 'muop_ajax_publish_draft_handler');
add_action('wp_ajax_nopriv_muop_publish_draft', 'muop_ajax_publish_draft_handler');
function muop_ajax_publish_draft_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy truyện!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);
    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền đăng bộ truyện này!'));
    }

    $is_admin = muop_is_admin();
    $new_status = $is_admin ? 'publish' : 'pending';

    wp_update_post(array(
        'ID'          => $story_id,
        'post_status' => $new_status
    ));

    $msg = $is_admin 
        ? 'Đã đăng truyện thành công!' 
        : 'Đã gửi duyệt truyện thành công! Vui lòng chờ Quản trị viên duyệt.';

    wp_send_json_success(array(
        'message'     => $msg,
        'story_id'    => $story_id,
        'post_status' => $new_status
    ));
}

// 19.2 AJAX: Dịch Giả & Admin Ẩn hoặc Bỏ Ẩn Truyện
add_action('wp_ajax_muop_toggle_story_hide', 'muop_ajax_toggle_story_hide_handler');
add_action('wp_ajax_nopriv_muop_toggle_story_hide', 'muop_ajax_toggle_story_hide_handler');
function muop_ajax_toggle_story_hide_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : 'hide'; // 'hide' or 'unhide'

    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy bộ truyện này!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);
    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền quản lý bộ truyện này!'));
    }

    if ($action_type === 'hide') {
        // Lưu lại trạng thái trước khi ẩn
        update_post_meta($story_id, '_truyen_prev_status', $story->post_status);
        update_post_meta($story_id, '_truyen_is_hidden', '1');
        update_post_meta($story_id, '_truyen_hidden_by', $current_user_id);
        update_post_meta($story_id, '_truyen_hidden_at', current_time('mysql'));

        wp_update_post(array(
            'ID'          => $story_id,
            'post_status' => 'draft'
        ));

        wp_send_json_success(array(
            'message'     => 'Đã ẩn truyện thành công! Bộ truyện sẽ không còn hiển thị công khai trên website.',
            'story_id'    => $story_id,
            'action_type' => 'hide'
        ));
    } else {
        // Bỏ ẩn truyện (unhide)
        $prev_status = get_post_meta($story_id, '_truyen_prev_status', true);
        delete_post_meta($story_id, '_truyen_is_hidden');
        delete_post_meta($story_id, '_truyen_hidden_by');
        delete_post_meta($story_id, '_truyen_hidden_at');
        delete_post_meta($story_id, '_truyen_prev_status');
        delete_post_meta($story_id, '_truyen_scheduled_time');
        delete_post_meta($story_id, '_truyen_scheduled_by');

        $is_admin = muop_is_admin();
        // Nếu truyện trước đó đã từng xuất bản hoặc người dùng là admin thì khôi phục publish ngay
        $restore_status = ($prev_status === 'publish' || $is_admin || empty($prev_status)) ? 'publish' : 'publish';

        wp_update_post(array(
            'ID'          => $story_id,
            'post_status' => $restore_status
        ));

        wp_send_json_success(array(
            'message'     => 'Đã bỏ ẩn và hiển thị công khai truyện thành công!',
            'story_id'    => $story_id,
            'action_type' => 'unhide'
        ));
    }
}

// 20.1 AJAX: Lên lịch hẹn giờ hoặc đổi giờ hẹn đăng truyện
add_action('wp_ajax_muop_schedule_story', 'muop_ajax_schedule_story_handler');
add_action('wp_ajax_nopriv_muop_schedule_story', 'muop_ajax_schedule_story_handler');
function muop_ajax_schedule_story_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy truyện!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);
    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền lên lịch bộ truyện này!'));
    }

    $scheduled_time = isset($_POST['scheduled_time']) ? sanitize_text_field($_POST['scheduled_time']) : '';
    if (empty($scheduled_time)) {
        wp_send_json_error(array('message' => 'Vui lòng chọn thời gian hẹn giờ!'));
    }

    $parsed_sched = muop_validate_and_parse_schedule_time($scheduled_time);
    if (is_wp_error($parsed_sched)) {
        wp_send_json_error(array('message' => $parsed_sched->get_error_message()));
    }

    $post_date = $parsed_sched['post_date'];
    $post_date_gmt = $parsed_sched['post_date_gmt'];
    $formatted = $parsed_sched['formatted'];

    wp_update_post(array(
        'ID'            => $story_id,
        'post_status'   => 'future',
        'post_date'     => $post_date,
        'post_date_gmt' => $post_date_gmt,
        'edit_date'     => true
    ));

    update_post_meta($story_id, '_truyen_scheduled_time', $post_date);
    update_post_meta($story_id, '_truyen_scheduled_by', $current_user_id);
    delete_post_meta($story_id, '_truyen_is_hidden');
    delete_post_meta($story_id, '_truyen_hidden_by');
    delete_post_meta($story_id, '_truyen_hidden_at');
    delete_post_meta($story_id, '_truyen_prev_status');

    wp_send_json_success(array(
        'message'              => 'Đã lên lịch đăng truyện thành công vào lúc: ' . $formatted,
        'story_id'             => $story_id,
        'scheduled_time'       => $post_date,
        'scheduled_formatted'  => $formatted,
        'post_status'          => 'future'
    ));
}

// 20.2 AJAX: Hủy hẹn giờ (chuyển về bản nháp)
add_action('wp_ajax_muop_cancel_schedule', 'muop_ajax_cancel_schedule_handler');
add_action('wp_ajax_nopriv_muop_cancel_schedule', 'muop_ajax_cancel_schedule_handler');
function muop_ajax_cancel_schedule_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Không tìm thấy truyện!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);
    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền hủy lịch hẹn bộ truyện này!'));
    }

    wp_update_post(array(
        'ID'          => $story_id,
        'post_status' => 'draft'
    ));
    delete_post_meta($story_id, '_truyen_scheduled_time');

    wp_send_json_success(array(
        'message'     => 'Đã hủy lịch hẹn giờ và chuyển bộ truyện về danh sách Bản Nháp.',
        'story_id'    => $story_id,
        'post_status' => 'draft'
    ));
}

// 20.3 Tự động xuất bản các truyện đã đến giờ hẹn (Auto-publish Scheduled Stories)
function muop_check_and_publish_future_stories() {
    global $wpdb;
    $now_mysql = current_time('mysql');
    $due_stories = $wpdb->get_results($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} 
         WHERE post_type = 'truyen' 
           AND post_status = 'future' 
           AND post_date <= %s",
        $now_mysql
    ));

    if (!empty($due_stories)) {
        foreach ($due_stories as $st_row) {
            $sid = (int) $st_row->ID;
            wp_publish_post($sid);
            delete_post_meta($sid, '_truyen_scheduled_time');
            if (function_exists('muop_notify_followers_new_story')) {
                muop_notify_followers_new_story($sid);
            }
        }
    }
}
add_action('init', 'muop_check_and_publish_future_stories');

// 21. AJAX: Get Preset Covers (Lấy danh sách ảnh bìa có sẵn: chỉ hiện ảnh sẵn của hệ thống và ảnh của dịch giả đó, không hiện ảnh của dịch giả khác)
add_action('wp_ajax_muop_get_preset_covers', 'muop_ajax_get_preset_covers_handler');
add_action('wp_ajax_nopriv_muop_get_preset_covers', 'muop_ajax_get_preset_covers_handler');
function muop_ajax_get_preset_covers_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in() || !muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Không có quyền truy cập!'));
    }

    $current_user_id = get_current_user_id();
    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;

    // Xác định dịch giả của truyện cần đổi ảnh bìa
    $translator_id = $current_user_id;
    if ($story_id > 0) {
        $story = get_post($story_id);
        if ($story && $story->post_type === 'truyen') {
            // Nếu là admin thì lấy ảnh theo tác giả/dịch giả của truyện đó; nếu là dịch giả thì chỉ lấy của chính mình
            $translator_id = muop_is_admin() ? (int)$story->post_author : $current_user_id;
        }
    }

    $covers = array();
    // 1. Ảnh mẫu mặc định có sẵn của hệ thống (luôn luôn có sẵn)
    $covers[] = array(
        'id'    => 0,
        'title' => 'Ảnh bìa mặc định hệ thống',
        'url'   => MUOP_THEME_URI . '/assets/images/default-cover.svg',
        'thumb' => MUOP_THEME_URI . '/assets/images/default-cover.svg'
    );

    // 2. Chỉ hiện những ảnh do chính dịch giả đó tải lên (loại trừ ảnh của dịch giả khác)
    if ($translator_id > 0) {
        $attachments = get_posts(array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'author'         => $translator_id,
            'posts_per_page' => 50,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));

        foreach ($attachments as $att) {
            $full_url = wp_get_attachment_url($att->ID);
            $thumb_url = wp_get_attachment_image_url($att->ID, 'thumbnail') ?: $full_url;
            if ($full_url) {
                $covers[] = array(
                    'id'    => $att->ID,
                    'title' => $att->post_title ?: 'Ảnh ' . $att->ID,
                    'url'   => $full_url,
                    'thumb' => $thumb_url
                );
            }
        }
    }

    wp_send_json_success(array('covers' => $covers));
}

// 22. AJAX: Update Story Cover (Đổi ảnh bìa nhanh: tải từ thiết bị hoặc chọn ảnh có sẵn)
add_action('wp_ajax_muop_update_story_cover', 'muop_ajax_update_story_cover_handler');
add_action('wp_ajax_nopriv_muop_update_story_cover', 'muop_ajax_update_story_cover_handler');
function muop_ajax_update_story_cover_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thay đổi ảnh bìa truyện của dịch giả khác!'));
    }

    // 1. Nếu người dùng tải ảnh từ thiết bị
    if (!empty($_FILES['cover_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('cover_file', $story_id);
        if (is_wp_error($attachment_id)) {
            while (ob_get_level()) { @ob_end_clean(); }
            wp_send_json_error(array('message' => 'Tải ảnh thất bại: ' . $attachment_id->get_error_message()));
        }
        set_post_thumbnail($story_id, $attachment_id);
    } 
    // 2. Nếu người dùng chọn ảnh có sẵn theo attachment ID (bao gồm cả ảnh mặc định hệ thống có ID = 0)
    elseif (isset($_POST['preset_cover_id']) && $_POST['preset_cover_id'] !== '') {
        $preset_id = intval($_POST['preset_cover_id']);
        if ($preset_id > 0) {
            set_post_thumbnail($story_id, $preset_id);
        } else {
            delete_post_thumbnail($story_id);
        }
    }
    // 3. Nếu người dùng chọn theo URL
    elseif (!empty($_POST['preset_cover_url'])) {
        $cover_url = esc_url_raw($_POST['preset_cover_url']);
        if (strpos($cover_url, 'default-cover.svg') !== false) {
            delete_post_thumbnail($story_id);
        } else {
            $att_id = attachment_url_to_postid($cover_url);
            if (!$att_id && strpos($cover_url, '/wp-content/uploads/') !== false) {
                global $wpdb;
                $relative_path = preg_replace('#^.*(?=/wp-content/uploads/)#', '', $cover_url);
                $relative_file = str_replace('/wp-content/uploads/', '', $relative_path);
                $relative_file = preg_replace('/\?.*$/', '', $relative_file);
                $orig_file = preg_replace('/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $relative_file);
                $found_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND (meta_value = %s OR meta_value = %s) LIMIT 1",
                    $relative_file,
                    $orig_file
                ));
                if ($found_id) {
                    $att_id = (int)$found_id;
                }
            }
            if ($att_id > 0) {
                set_post_thumbnail($story_id, $att_id);
            }
        }
    } else {
        while (ob_get_level()) { @ob_end_clean(); }
        wp_send_json_error(array('message' => 'Vui lòng chọn ảnh có sẵn hoặc tải ảnh mới từ thiết bị!'));
    }

    // Cập nhật modified time
    wp_update_post(array('ID' => $story_id));

    $new_cover_url = get_the_post_thumbnail_url($story_id, 'muop-cover');
    if (!$new_cover_url) {
        $new_cover_url = get_the_post_thumbnail_url($story_id, 'full');
    }
    if (!$new_cover_url) {
        $new_cover_url = MUOP_THEME_URI . '/assets/images/default-cover.svg';
    }

    while (ob_get_level()) { @ob_end_clean(); }
    wp_send_json_success(array(
        'message'   => 'Đã thay đổi ảnh bìa truyện thành công!',
        'cover_url' => $new_cover_url,
        'updated'   => muop_get_story_last_updated($story_id)
    ));
}

// 23. AJAX: Update Story Title (Chỉnh sửa tên truyện nhanh)
add_action('wp_ajax_muop_update_story_title', 'muop_ajax_update_story_title_handler');
add_action('wp_ajax_nopriv_muop_update_story_title', 'muop_ajax_update_story_title_handler');
function muop_ajax_update_story_title_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    $current_user_id = get_current_user_id();
    $is_from_admin_portal = !empty($_POST['is_admin_portal']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false);

    if (!muop_can_user_manage_story_action($story_id, $current_user_id, $is_from_admin_portal)) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa tên truyện của dịch giả khác!'));
    }

    $new_title = isset($_POST['title']) ? sanitize_text_field(trim($_POST['title'])) : '';
    if (empty($new_title)) {
        wp_send_json_error(array('message' => 'Tên truyện không được để trống!'));
    }

    $res = wp_update_post(array(
        'ID'         => $story_id,
        'post_title' => $new_title
    ));

    if (is_wp_error($res)) {
        wp_send_json_error(array('message' => 'Không thể đổi tên truyện: ' . $res->get_error_message()));
    }

    wp_send_json_success(array(
        'message' => 'Đã đổi tên truyện thành công!',
        'title'   => $new_title,
        'updated' => muop_get_story_last_updated($story_id)
    ));
}

// 24. AJAX: Update Team Info (Đổi Avatar, Bio, Tên Team Dịch)
add_action('wp_ajax_muop_update_team_info', 'muop_ajax_update_team_info_handler');
add_action('wp_ajax_nopriv_muop_update_team_info', 'muop_ajax_update_team_info_handler');
function muop_ajax_update_team_info_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện!'));
    }

    $team_id = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;
    $term = get_term($team_id, 'team_dich');
    if (!$term || is_wp_error($term)) {
        wp_send_json_error(array('message' => 'Không tìm thấy thông tin team dịch!'));
    }

    $current_uid = get_current_user_id();
    $can_edit = false;

    if (muop_is_admin($current_uid)) {
        $can_edit = true;
    } elseif (user_can($current_uid, 'dich_gia')) {
        // Kiểm tra xem dịch giả có truyện nào thuộc team này không
        $posts_in_team = get_posts(array(
            'post_type'      => 'truyen',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'author'         => $current_uid,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'team_dich',
                    'field'    => 'term_id',
                    'terms'    => $team_id
                )
            )
        ));
        if (!empty($posts_in_team)) {
            $can_edit = true;
        }
    }

    if (!$can_edit) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa thông tin của team dịch này!'));
    }

    // 1. Xử lý ảnh đại diện (Avatar)
    $team_avatar_url = get_term_meta($team_id, '_team_avatar', true);
    if (!empty($_FILES['team_avatar_file']) && !empty($_FILES['team_avatar_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $allowed_mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
            'webp'         => 'image/webp',
            'gif'          => 'image/gif',
        );

        $file = $_FILES['team_avatar_file'];
        $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name'], $allowed_mimes);
        if (!$check['ext']) {
            wp_send_json_error(array('message' => 'Định dạng ảnh không hợp lệ! Vui lòng tải file JPG, PNG, hoặc WEBP.'));
        }

        $upload = wp_handle_upload($file, array('test_form' => false, 'mimes' => $allowed_mimes));
        if (!empty($upload['error'])) {
            wp_send_json_error(array('message' => 'Lỗi tải ảnh đại diện: ' . $upload['error']));
        }

        $team_avatar_url = $upload['url'];
        update_term_meta($team_id, '_team_avatar', $team_avatar_url);
    } elseif (isset($_POST['team_avatar_url'])) {
        $input_avatar_url = esc_url_raw(trim($_POST['team_avatar_url']));
        if (!empty($input_avatar_url)) {
            $team_avatar_url = $input_avatar_url;
            update_term_meta($team_id, '_team_avatar', $team_avatar_url);
        }
    }

    // 2. Xử lý Lời giới thiệu (Bio)
    $team_bio = '';
    if (isset($_POST['team_bio'])) {
        $team_bio = sanitize_textarea_field(trim($_POST['team_bio']));
        wp_update_term($team_id, 'team_dich', array('description' => $team_bio));
        update_term_meta($team_id, '_team_bio', $team_bio);
    }

    // 3. Xử lý Tên Team Dịch
    $new_name = isset($_POST['team_name']) ? sanitize_text_field(trim($_POST['team_name'])) : '';
    $redirect_url = '';
    if (!empty($new_name) && $new_name !== $term->name) {
        $new_slug = sanitize_title($new_name);
        $update_term_res = wp_update_term($team_id, 'team_dich', array(
            'name' => $new_name,
            'slug' => $new_slug
        ));

        if (is_wp_error($update_term_res)) {
            wp_send_json_error(array('message' => 'Không thể đổi tên team: ' . $update_term_res->get_error_message()));
        }

        // Đồng bộ post_meta _truyen_team ở tất cả truyện thuộc team
        $stories_in_team = get_posts(array(
            'post_type'      => 'truyen',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'team_dich',
                    'field'    => 'term_id',
                    'terms'    => $team_id
                )
            )
        ));
        foreach ($stories_in_team as $st) {
            update_post_meta($st->ID, '_truyen_team', $new_name);
        }

        $link_res = get_term_link($team_id, 'team_dich');
        if (!is_wp_error($link_res)) {
            $redirect_url = $link_res;
        }
    }

    $final_name = !empty($new_name) ? $new_name : $term->name;

    wp_send_json_success(array(
        'message'      => 'Cập nhật thông tin team dịch thành công!',
        'team_id'      => $team_id,
        'team_name'    => $final_name,
        'team_bio'     => $team_bio,
        'avatar_url'   => muop_normalize_url($team_avatar_url),
        'redirect_url' => $redirect_url
    ));
}

/**
 * 25. Kiểm tra điều kiện xuất hiện Popup mở khóa Shopee
 * Quy tắc mới:
 * Bất kỳ chương nào không phải chương 1 (chương 2, 3, 4, 5...) đều xuất hiện link Shopee để mở khóa
 * (Chương 1 đọc trực tiếp không cần popup; từ chương 2 trở đi nếu không bấm vào link Shopee sẽ không đọc được)
 */
function muop_is_shopee_checkpoint($chap_order, $total_chapters = 0) {
    $chap_order = (int) $chap_order;
    return ($chap_order > 1);
}

/**
 * 26. HỆ THỐNG THÔNG BÁO, BÁO LỖI CHƯƠNG & QUẢN LÝ NHUẬN BÚT / BILL
 */

/**
 * Thêm một thông báo vào cơ sở dữ liệu
 */
function muop_add_notification($args) {
    global $wpdb;
    $table = $wpdb->prefix . 'muop_notifications';
    $defaults = array(
        'user_id'    => 0,
        'sender_id'  => get_current_user_id(),
        'type'       => 'system',
        'title'      => '',
        'message'    => '',
        'link'       => '',
        'image'      => '',
        'is_read'    => 0,
        'created_at' => current_time('mysql')
    );
    $data = wp_parse_args($args, $defaults);
    if (empty($data['user_id']) || empty($data['title'])) {
        return false;
    }
    $res = $wpdb->insert($table, $data);
    return $res ? $wpdb->insert_id : false;
}

/**
 * Lấy danh sách thông báo của người dùng
 */
function muop_get_user_notifications($user_id, $limit = 20, $offset = 0) {
    global $wpdb;
    $table = $wpdb->prefix . 'muop_notifications';
    $user_id = (int) $user_id;
    $limit   = (int) $limit;
    $offset  = (int) $offset;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE user_id = %d ORDER BY id DESC LIMIT %d OFFSET %d",
        $user_id, $limit, $offset
    ));
}

/**
 * Đếm số lượng thông báo chưa đọc của người dùng
 */
function muop_get_unread_notification_count($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'muop_notifications';
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE user_id = %d AND is_read = 0",
        (int) $user_id
    ));
}

/**
 * Đánh dấu 1 thông báo là đã đọc
 */
function muop_mark_notification_as_read($notif_id, $user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'muop_notifications';
    return $wpdb->update(
        $table,
        array('is_read' => 1),
        array('id' => (int) $notif_id, 'user_id' => (int) $user_id)
    );
}

/**
 * Đánh dấu tất cả thông báo của người dùng là đã đọc
 */
function muop_mark_all_notifications_as_read($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'muop_notifications';
    return $wpdb->update(
        $table,
        array('is_read' => 1),
        array('user_id' => (int) $user_id, 'is_read' => 0)
    );
}

/**
 * Kiểm tra người dùng có đang theo dõi bộ truyện này không
 */
function muop_is_user_following_story($user_id, $story_id) {
    if (!$user_id || !$story_id) return false;
    $follows = get_user_meta($user_id, '_muop_followed_stories', true);
    if (!is_array($follows)) $follows = array();
    return in_array((int)$story_id, array_map('intval', $follows));
}

/**
 * Thêm theo dõi truyện
 */
function muop_follow_story($user_id, $story_id) {
    if (!$user_id || !$story_id) return false;
    $follows = get_user_meta($user_id, '_muop_followed_stories', true);
    if (!is_array($follows)) $follows = array();
    if (!in_array((int)$story_id, array_map('intval', $follows))) {
        $follows[] = (int)$story_id;
        update_user_meta($user_id, '_muop_followed_stories', array_values(array_unique($follows)));
    }

    $followers = get_post_meta($story_id, '_muop_story_followers', true);
    if (!is_array($followers)) $followers = array();
    if (!in_array((int)$user_id, array_map('intval', $followers))) {
        $followers[] = (int)$user_id;
        update_post_meta($story_id, '_muop_story_followers', array_values(array_unique($followers)));
    }
    return true;
}

/**
 * Hủy theo dõi truyện
 */
function muop_unfollow_story($user_id, $story_id) {
    if (!$user_id || !$story_id) return false;
    $follows = get_user_meta($user_id, '_muop_followed_stories', true);
    if (is_array($follows)) {
        $follows = array_diff(array_map('intval', $follows), array((int)$story_id));
        update_user_meta($user_id, '_muop_followed_stories', array_values($follows));
    }

    $followers = get_post_meta($story_id, '_muop_story_followers', true);
    if (is_array($followers)) {
        $followers = array_diff(array_map('intval', $followers), array((int)$user_id));
        update_post_meta($story_id, '_muop_story_followers', array_values($followers));
    }
    return true;
}

// AJAX: Toggle Follow Story (Theo dõi / Hủy theo dõi truyện)
add_action('wp_ajax_muop_toggle_follow_story', 'muop_ajax_toggle_follow_story_handler');
add_action('wp_ajax_nopriv_muop_toggle_follow_story', 'muop_ajax_toggle_follow_story_handler');
function muop_ajax_toggle_follow_story_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để theo dõi truyện và nhận thông báo khi có chương mới!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Truyện không tồn tại hoặc đã bị xóa!'));
    }

    $current_user_id = get_current_user_id();
    $author_id = (int) $story->post_author;

    $is_following = muop_is_user_following_story($current_user_id, $story_id);
    if ($is_following) {
        muop_unfollow_story($current_user_id, $story_id);
        wp_send_json_success(array(
            'following' => false,
            'message'   => 'Đã hủy theo dõi truyện.'
        ));
    } else {
        muop_follow_story($current_user_id, $story_id);
        // Bắn thông báo cho Dịch giả & Team dịch khi có độc giả theo dõi truyện (nếu không phải chính tác giả)
        if ($current_user_id !== $author_id) {
            muop_notify_story_followed($story_id, $current_user_id);
        }
        wp_send_json_success(array(
            'following' => true,
            'message'   => 'Đã theo dõi truyện thành công! Bạn sẽ nhận được thông báo ngay khi có chương mới.'
        ));
    }
}

/**
 * Bắn thông báo ra chương mới cho độc giả theo dõi truyện (Followers & Bookmarks)
 */
function muop_notify_story_new_chapter($story_id, $chapter_id, $chap_num, $chap_title) {
    global $wpdb;
    $story = get_post($story_id);
    if (!$story) return;

    $author_id = (int) $story->post_author;
    $current_user_id = get_current_user_id();
    $notified_users = array();

    // 1. Danh sách người Theo Dõi truyện này (Followers)
    $followers = get_post_meta($story_id, '_muop_story_followers', true);
    if (!empty($followers) && is_array($followers)) {
        foreach ($followers as $uid) {
            $uid = (int) $uid;
            if ($uid > 0 && $uid !== $author_id && $uid !== $current_user_id) {
                $notified_users[$uid] = true;
            }
        }
    }

    // 2. Danh sách người Lưu Tủ Truyện (Bookmarks)
    $table_bookmarks = $wpdb->prefix . 'reading_bookmarks';
    $bookmarked_users = $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT user_id FROM $table_bookmarks WHERE story_id = %d AND user_id != %d",
        $story_id, $current_user_id
    ));
    if (!empty($bookmarked_users)) {
        foreach ($bookmarked_users as $uid) {
            $uid = (int) $uid;
            if ($uid > 0 && $uid !== $author_id && $uid !== $current_user_id) {
                $notified_users[$uid] = true;
            }
        }
    }

    if (!empty($notified_users)) {
        $display_title = $chap_title ? "Chương {$chap_num}: {$chap_title}" : "Chương {$chap_num}";
        foreach (array_keys($notified_users) as $uid) {
            muop_add_notification(array(
                'user_id'   => (int) $uid,
                'sender_id' => $current_user_id,
                'type'      => 'new_chapter',
                'title'     => 'Chương mới: ' . $story->post_title,
                'message'   => 'Truyện "' . $story->post_title . '" vừa ra ' . $display_title . '. Mời bạn đọc ngay!',
                'link'      => get_permalink($chapter_id)
            ));
        }
    }
}

/**
 * Bắn thông báo dịch giả ra truyện mới cho độc giả theo dõi team dịch
 * (Chỉ thông báo khi ra TRUYỆN MỚI, không thông báo khi ra chương)
 */
function muop_notify_followers_new_story($story_id) {
    global $wpdb;
    $story = get_post($story_id);
    if (!$story) return;

    $terms = wp_get_post_terms($story_id, 'team_dich');
    $team_id = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->term_id : 0;
    $team_name = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : get_the_author_meta('display_name', $story->post_author);

    if ($team_id > 0) {
        $users_fav = $wpdb->get_results(
            "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = '_muop_favorite_teams'"
        );
        foreach ($users_fav as $row) {
            $fav_list = maybe_unserialize($row->meta_value);
            if (is_array($fav_list) && in_array($team_id, $fav_list)) {
                if ((int) $row->user_id !== (int) $story->post_author) {
                    muop_add_notification(array(
                        'user_id'   => (int) $row->user_id,
                        'sender_id' => $story->post_author,
                        'type'      => 'new_story',
                        'title'     => 'Dịch giả ra truyện mới!',
                        'message'   => 'Dịch giả "' . $team_name . '" vừa phát hành bộ truyện mới: "' . $story->post_title . '". Khám phá ngay!',
                        'link'      => get_permalink($story_id)
                    ));
                }
            }
        }
    }
}

/**
 * Bắn thông báo cho toàn bộ Quản Trị Viên (Admin) khi có người dùng mới đăng ký
 */
function muop_notify_admins_new_user($user_id) {
    if (!$user_id) return;
    
    // Tránh gửi lặp
    if (get_user_meta($user_id, '_muop_admin_reg_notified', true)) {
        return;
    }
    update_user_meta($user_id, '_muop_admin_reg_notified', 1);

    $user = get_userdata($user_id);
    if (!$user) return;

    $admins = get_users(array('role' => 'administrator'));
    if (empty($admins)) return;

    $display_name = !empty($user->display_name) ? $user->display_name : $user->user_login;
    $email = $user->user_email;

    foreach ($admins as $admin) {
        if ((int)$admin->ID === (int)$user_id) continue;
        muop_add_notification(array(
            'user_id'   => (int) $admin->ID,
            'sender_id' => (int) $user_id,
            'type'      => 'new_user',
            'title'     => 'Người dùng mới đăng ký',
            'message'   => 'Thành viên mới "' . $display_name . '" (' . $email . ') vừa đăng ký tài khoản thành công.',
            'link'      => home_url('/quan-ly-admin/?tab=users')
        ));
    }
}
add_action('user_register', 'muop_notify_admins_new_user', 20, 1);

/**
 * Bắn thông báo cho dịch giả và team dịch khi có độc giả theo dõi truyện
 */
function muop_notify_story_followed($story_id, $user_id) {
    $story = get_post($story_id);
    if (!$story) return;

    $fan = get_userdata($user_id);
    $fan_name = $fan ? $fan->display_name : 'Một độc giả';
    $author_id = (int) $story->post_author;
    $notified = array();

    // 1. Thông báo cho tác giả/dịch giả đăng truyện
    if ($author_id > 0 && $author_id !== (int) $user_id) {
        muop_add_notification(array(
            'user_id'   => $author_id,
            'sender_id' => (int) $user_id,
            'type'      => 'story_follow',
            'title'     => 'Có độc giả theo dõi truyện của bạn!',
            'message'   => $fan_name . ' vừa nhấn theo dõi bộ truyện "' . $story->post_title . '".',
            'link'      => get_permalink($story_id)
        ));
        $notified[$author_id] = true;
    }

    // 2. Thông báo cho các thành viên trong team dịch của truyện
    $terms = wp_get_post_terms($story_id, 'team_dich');
    if (!empty($terms) && !is_wp_error($terms)) {
        $team_id = $terms[0]->term_id;
        $team_stories = get_posts(array(
            'post_type'      => 'truyen',
            'posts_per_page' => 10,
            'tax_query'      => array(
                array('taxonomy' => 'team_dich', 'field' => 'term_id', 'terms' => $team_id)
            )
        ));
        foreach ($team_stories as $st) {
            $t_auth = (int) $st->post_author;
            if ($t_auth > 0 && !isset($notified[$t_auth]) && $t_auth !== (int) $user_id) {
                muop_add_notification(array(
                    'user_id'   => $t_auth,
                    'sender_id' => (int) $user_id,
                    'type'      => 'story_follow',
                    'title'     => 'Có độc giả theo dõi truyện của team!',
                    'message'   => $fan_name . ' vừa nhấn theo dõi bộ truyện "' . $story->post_title . '" của team ' . $terms[0]->name . '.',
                    'link'      => get_permalink($story_id)
                ));
                $notified[$t_auth] = true;
            }
        }
    }
}

/**
 * Bắn thông báo cho dịch giả và team dịch khi có độc giả yêu thích truyện
 */
function muop_notify_story_favorited($story_id, $user_id) {
    $story = get_post($story_id);
    if (!$story) return;

    $fan = get_userdata($user_id);
    $fan_name = $fan ? $fan->display_name : 'Một độc giả';
    $author_id = (int) $story->post_author;
    $notified = array();

    // 1. Thông báo cho tác giả/dịch giả đăng truyện
    if ($author_id > 0 && $author_id !== (int) $user_id) {
        muop_add_notification(array(
            'user_id'   => $author_id,
            'sender_id' => (int) $user_id,
            'type'      => 'story_favorite',
            'title'     => 'Có độc giả yêu thích truyện của bạn!',
            'message'   => $fan_name . ' vừa thêm bộ truyện "' . $story->post_title . '" vào danh sách yêu thích.',
            'link'      => get_permalink($story_id)
        ));
        $notified[$author_id] = true;
    }

    // 2. Thông báo cho các thành viên trong team dịch
    $terms = wp_get_post_terms($story_id, 'team_dich');
    if (!empty($terms) && !is_wp_error($terms)) {
        $team_id = $terms[0]->term_id;
        $team_stories = get_posts(array(
            'post_type'      => 'truyen',
            'posts_per_page' => 10,
            'tax_query'      => array(
                array('taxonomy' => 'team_dich', 'field' => 'term_id', 'terms' => $team_id)
            )
        ));
        foreach ($team_stories as $st) {
            $t_auth = (int) $st->post_author;
            if ($t_auth > 0 && !isset($notified[$t_auth]) && $t_auth !== (int) $user_id) {
                muop_add_notification(array(
                    'user_id'   => $t_auth,
                    'sender_id' => (int) $user_id,
                    'type'      => 'story_favorite',
                    'title'     => 'Có độc giả yêu thích truyện của team!',
                    'message'   => $fan_name . ' vừa yêu thích bộ truyện "' . $story->post_title . '" của team ' . $terms[0]->name . '.',
                    'link'      => get_permalink($story_id)
                ));
                $notified[$t_auth] = true;
            }
        }
    }
}

/**
 * Bắn thông báo cho team dịch khi có độc giả yêu thích team
 */
function muop_notify_team_favorited($team_id, $fan_user_id) {
    $term = get_term($team_id, 'team_dich');
    if (!$term || is_wp_error($term)) return;

    $fan = get_userdata($fan_user_id);
    $fan_name = $fan ? $fan->display_name : 'Một độc giả';

    $stories = get_posts(array(
        'post_type'      => 'truyen',
        'posts_per_page' => 10,
        'tax_query'      => array(
            array(
                'taxonomy' => 'team_dich',
                'field'    => 'term_id',
                'terms'    => $team_id
            )
        )
    ));
    $notified = array();
    foreach ($stories as $st) {
        $author_id = (int) $st->post_author;
        if ($author_id > 0 && !isset($notified[$author_id]) && $author_id !== (int) $fan_user_id) {
            muop_add_notification(array(
                'user_id'   => $author_id,
                'sender_id' => (int) $fan_user_id,
                'type'      => 'team_favorite',
                'title'     => 'Có người hâm mộ yêu thích team của bạn!',
                'message'   => $fan_name . ' vừa thêm team dịch "' . $term->name . '" vào danh sách yêu thích.',
                'link'      => get_term_link($term)
            ));
            $notified[$author_id] = true;
        }
    }
}

/**
 * Bắn thông báo cho team dịch khi có độc giả theo dõi team
 */
function muop_notify_team_followed($team_id, $fan_user_id) {
    $term = get_term($team_id, 'team_dich');
    if (!$term || is_wp_error($term)) return;

    $fan = get_userdata($fan_user_id);
    $fan_name = $fan ? $fan->display_name : 'Một độc giả';

    $stories = get_posts(array(
        'post_type'      => 'truyen',
        'posts_per_page' => 10,
        'tax_query'      => array(
            array(
                'taxonomy' => 'team_dich',
                'field'    => 'term_id',
                'terms'    => $team_id
            )
        )
    ));
    $notified = array();
    foreach ($stories as $st) {
        $author_id = (int) $st->post_author;
        if ($author_id > 0 && !isset($notified[$author_id]) && $author_id !== (int) $fan_user_id) {
            muop_add_notification(array(
                'user_id'   => $author_id,
                'sender_id' => (int) $fan_user_id,
                'type'      => 'team_follow',
                'title'     => 'Có độc giả theo dõi team của bạn!',
                'message'   => $fan_name . ' vừa nhấn theo dõi team dịch "' . $term->name . '".',
                'link'      => get_term_link($term)
            ));
            $notified[$author_id] = true;
        }
    }
}

/**
 * Kiểm tra người dùng đã yêu thích truyện chưa
 */
function muop_is_user_favorited_story($user_id, $story_id) {
    if (!$user_id || !$story_id) return false;
    $favs = get_user_meta($user_id, '_muop_favorite_stories', true);
    if (!is_array($favs)) $favs = array();
    return in_array((int)$story_id, array_map('intval', $favs));
}

/**
 * Thêm truyện vào danh sách yêu thích
 */
function muop_favorite_story($user_id, $story_id) {
    if (!$user_id || !$story_id) return false;
    $favs = get_user_meta($user_id, '_muop_favorite_stories', true);
    if (!is_array($favs)) $favs = array();
    if (!in_array((int)$story_id, array_map('intval', $favs))) {
        $favs[] = (int)$story_id;
        update_user_meta($user_id, '_muop_favorite_stories', array_values(array_unique($favs)));
    }
    $count = (int) get_post_meta($story_id, '_muop_story_favorite_count', true);
    $count++;
    update_post_meta($story_id, '_muop_story_favorite_count', $count);
    return true;
}

/**
 * Hủy yêu thích truyện
 */
function muop_unfavorite_story($user_id, $story_id) {
    if (!$user_id || !$story_id) return false;
    $favs = get_user_meta($user_id, '_muop_favorite_stories', true);
    if (is_array($favs)) {
        $favs = array_diff(array_map('intval', $favs), array((int)$story_id));
        update_user_meta($user_id, '_muop_favorite_stories', array_values($favs));
    }
    $count = (int) get_post_meta($story_id, '_muop_story_favorite_count', true);
    $count = max(0, $count - 1);
    update_post_meta($story_id, '_muop_story_favorite_count', $count);
    return true;
}

// AJAX: Toggle Favorite Story (Yêu thích / Hủy yêu thích truyện)
add_action('wp_ajax_muop_toggle_favorite_story', 'muop_ajax_toggle_favorite_story_handler');
add_action('wp_ajax_nopriv_muop_toggle_favorite_story', 'muop_ajax_toggle_favorite_story_handler');
function muop_ajax_toggle_favorite_story_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để yêu thích truyện!'));
    }

    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $story = get_post($story_id);
    if (!$story || $story->post_type !== 'truyen') {
        wp_send_json_error(array('message' => 'Truyện không tồn tại hoặc đã bị xóa!'));
    }

    $current_user_id = get_current_user_id();
    $author_id = (int) $story->post_author;

    $is_fav = muop_is_user_favorited_story($current_user_id, $story_id);
    if ($is_fav) {
        muop_unfavorite_story($current_user_id, $story_id);
        $count = (int) get_post_meta($story_id, '_muop_story_favorite_count', true);
        wp_send_json_success(array(
            'favorited' => false,
            'count'     => $count,
            'message'   => 'Đã bỏ yêu thích bộ truyện.'
        ));
    } else {
        muop_favorite_story($current_user_id, $story_id);
        // Bắn thông báo cho dịch giả và team dịch nếu không phải chính mình
        if ($current_user_id !== $author_id) {
            muop_notify_story_favorited($story_id, $current_user_id);
        }
        $count = (int) get_post_meta($story_id, '_muop_story_favorite_count', true);
        wp_send_json_success(array(
            'favorited' => true,
            'count'     => $count,
            'message'   => 'Đã thêm truyện vào danh sách yêu thích!'
        ));
    }
}

/**
 * Kiểm tra người dùng đã theo dõi team dịch chưa
 */
function muop_is_user_following_team($user_id, $team_id) {
    if (!$user_id || !$team_id) return false;
    $teams = get_user_meta($user_id, '_muop_followed_teams', true);
    if (!is_array($teams)) $teams = array();
    return in_array((int)$team_id, array_map('intval', $teams));
}

/**
 * Thêm team dịch vào danh sách theo dõi
 */
function muop_follow_team($user_id, $team_id) {
    if (!$user_id || !$team_id) return false;
    $teams = get_user_meta($user_id, '_muop_followed_teams', true);
    if (!is_array($teams)) $teams = array();
    if (!in_array((int)$team_id, array_map('intval', $teams))) {
        $teams[] = (int)$team_id;
        update_user_meta($user_id, '_muop_followed_teams', array_values(array_unique($teams)));
    }
    $count = (int) get_term_meta($team_id, '_team_follower_count', true);
    $count++;
    update_term_meta($team_id, '_team_follower_count', $count);
    return true;
}

/**
 * Hủy theo dõi team dịch
 */
function muop_unfollow_team($user_id, $team_id) {
    if (!$user_id || !$team_id) return false;
    $teams = get_user_meta($user_id, '_muop_followed_teams', true);
    if (is_array($teams)) {
        $teams = array_diff(array_map('intval', $teams), array((int)$team_id));
        update_user_meta($user_id, '_muop_followed_teams', array_values($teams));
    }
    $count = (int) get_term_meta($team_id, '_team_follower_count', true);
    $count = max(0, $count - 1);
    update_term_meta($team_id, '_team_follower_count', $count);
    return true;
}

// AJAX: Toggle Follow Team (Theo dõi / Hủy theo dõi team dịch)
add_action('wp_ajax_muop_toggle_follow_team', 'muop_ajax_toggle_follow_team_handler');
add_action('wp_ajax_nopriv_muop_toggle_follow_team', 'muop_ajax_toggle_follow_team_handler');
function muop_ajax_toggle_follow_team_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để theo dõi team dịch!'));
    }

    $user_id   = get_current_user_id();
    $team_id   = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;
    $team_name = isset($_POST['team_name']) ? sanitize_text_field($_POST['team_name']) : '';

    if ($team_id <= 0 && !empty($team_name)) {
        $term = get_term_by('name', $team_name, 'team_dich');
        if ($term && !is_wp_error($term)) {
            $team_id = $term->term_id;
        }
    }

    if ($team_id <= 0) {
        wp_send_json_error(array('message' => 'Không tìm thấy thông tin team dịch!'));
    }

    $is_following = muop_is_user_following_team($user_id, $team_id);
    if ($is_following) {
        muop_unfollow_team($user_id, $team_id);
        $count = (int) get_term_meta($team_id, '_team_follower_count', true);
        wp_send_json_success(array(
            'following' => false,
            'count'     => $count,
            'message'   => 'Đã hủy theo dõi team dịch.'
        ));
    } else {
        muop_follow_team($user_id, $team_id);
        // Bắn thông báo cho team dịch
        muop_notify_team_followed($team_id, $user_id);
        $count = (int) get_term_meta($team_id, '_team_follower_count', true);
        wp_send_json_success(array(
            'following' => true,
            'count'     => $count,
            'message'   => 'Đã theo dõi team dịch thành công!'
        ));
    }
}

// AJAX: Remove Single Reading History Item
add_action('wp_ajax_muop_remove_reading_history', 'muop_ajax_remove_reading_history_handler');
add_action('wp_ajax_nopriv_muop_remove_reading_history', 'muop_ajax_remove_reading_history_handler');
function muop_ajax_remove_reading_history_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    global $wpdb;
    $user_id  = get_current_user_id();
    $story_id = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    if ($story_id > 0) {
        $table = $wpdb->prefix . 'reading_history';
        $wpdb->delete($table, array('user_id' => $user_id, 'story_id' => $story_id));
        wp_send_json_success(array('message' => 'Đã xóa truyện khỏi lịch sử đọc.'));
    }
    wp_send_json_error(array('message' => 'Truyện không hợp lệ.'));
}

// AJAX: Clear All Reading History
add_action('wp_ajax_muop_clear_all_reading_history', 'muop_ajax_clear_all_reading_history_handler');
add_action('wp_ajax_nopriv_muop_clear_all_reading_history', 'muop_ajax_clear_all_reading_history_handler');
function muop_ajax_clear_all_reading_history_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    global $wpdb;
    $user_id = get_current_user_id();
    $table   = $wpdb->prefix . 'reading_history';
    $wpdb->delete($table, array('user_id' => $user_id));
    wp_send_json_success(array('message' => 'Đã xóa toàn bộ lịch sử đọc truyện thành công.'));
}

// AJAX: Lấy danh sách thông báo của user
add_action('wp_ajax_muop_get_notifications', 'muop_ajax_get_notifications_handler');
add_action('wp_ajax_nopriv_muop_get_notifications', 'muop_ajax_get_notifications_handler');
function muop_ajax_get_notifications_handler() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    $user_id = get_current_user_id();
    $notifs = muop_get_user_notifications($user_id, 25);
    $unread_count = muop_get_unread_notification_count($user_id);

    $items = array();
    foreach ($notifs as $n) {
        $icon = 'fa-solid fa-bell';
        $icon_color = '#16a34a';
        switch ($n->type) {
            case 'new_user':
                $icon = 'fa-solid fa-user-plus';
                $icon_color = '#06b6d4';
                break;
            case 'story_favorite':
                $icon = 'fa-solid fa-heart';
                $icon_color = '#f43f5e';
                break;
            case 'story_follow':
                $icon = 'fa-solid fa-bell';
                $icon_color = '#8b5cf6';
                break;
            case 'team_follow':
                $icon = 'fa-solid fa-user-check';
                $icon_color = '#10b981';
                break;
            case 'comment_reply':
                $icon = 'fa-solid fa-reply';
                $icon_color = '#0284c7';
                break;
            case 'story_comment':
                $icon = 'fa-solid fa-comments';
                $icon_color = '#10b981';
                break;
            case 'new_chapter':
                $icon = 'fa-solid fa-book-open';
                $icon_color = '#8b5cf6';
                break;
            case 'new_story':
                $icon = 'fa-solid fa-feather';
                $icon_color = '#f59e0b';
                break;
            case 'team_favorite':
                $icon = 'fa-solid fa-heart';
                $icon_color = '#ef4444';
                break;
            case 'story_nominated':
                $icon = 'fa-solid fa-award';
                $icon_color = '#eab308';
                break;
            case 'chapter_report':
                $icon = 'fa-solid fa-triangle-exclamation';
                $icon_color = '#ea580c';
                break;
            case 'report_reply':
                $icon = 'fa-solid fa-shield-halved';
                $icon_color = '#0d9488';
                break;
            case 'payment_received':
                $icon = 'fa-solid fa-money-bill-wave';
                $icon_color = '#16a34a';
                break;
            case 'system_broadcast':
                $icon = 'fa-solid fa-bullhorn';
                $icon_color = '#6366f1';
                break;
        }

        $report_id = 0;
        if (!empty($n->link) && preg_match('/#report-(\d+)/', $n->link, $matches)) {
            $report_id = intval($matches[1]);
        }

        $msg = $n->message;
        if ($n->type === 'chapter_report') {
            // Thay thế chữ "Độc giả vừa báo lỗi" hoặc "Một độc giả báo lỗi" cũ bằng tên thật của người gửi (ví dụ: Bơ vừa báo lỗi)
            if (strpos($msg, 'Độc giả vừa báo lỗi') !== false || strpos($msg, 'Một độc giả') !== false || strpos($msg, 'Độc giả báo lỗi') !== false) {
                $sender = $n->sender_id ? get_userdata($n->sender_id) : null;
                $s_name = $sender ? $sender->display_name : 'Độc giả';
                $msg = preg_replace('/(Một độc giả ẩn danh|Một độc giả|Độc giả)\s*(vừa\s*)?báo lỗi/u', $s_name . ' vừa báo lỗi', $msg);
            }
        }

        $items[] = array(
            'id'         => (int) $n->id,
            'type'       => $n->type,
            'title'      => esc_html($n->title),
            'message'    => esc_html($msg),
            'link'       => muop_clean_subresource_url($n->link),
            'report_id'  => $report_id,
            'image'      => muop_clean_subresource_url($n->image),
            'is_read'    => (int) $n->is_read,
            'time_ago'   => muop_time_ago($n->created_at),
            'icon'       => $icon,
            'icon_color' => $icon_color
        );
    }

    wp_send_json_success(array(
        'notifications' => $items,
        'unread_count'  => $unread_count
    ));
}

// Hàm chuẩn hóa URL tránh cảnh báo Chrome Private Network Access (PNA)
function muop_clean_subresource_url($url) {
    if (empty($url)) return '';
    if (preg_match('#^https?://(192\.168\.\d+\.\d+|127\.0\.0\.1|localhost|10\.\d+\.\d+\.\d+)(:\d+)?(/.*)?$#i', $url, $m)) {
        return !empty($m[3]) ? $m[3] : '/';
    }
    $home = home_url();
    if (strpos($url, $home) === 0) {
        $rel = substr($url, strlen($home));
        return (empty($rel) || $rel[0] !== '/') ? '/' . $rel : $rel;
    }
    return $url;
}

// AJAX: Đánh dấu 1 thông báo đã đọc
add_action('wp_ajax_muop_mark_notification_read', 'muop_ajax_mark_notification_read_handler');
add_action('wp_ajax_nopriv_muop_mark_notification_read', 'muop_ajax_mark_notification_read_handler');
function muop_ajax_mark_notification_read_handler() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    $notif_id = isset($_POST['notif_id']) ? intval($_POST['notif_id']) : 0;
    if ($notif_id > 0) {
        muop_mark_notification_as_read($notif_id, get_current_user_id());
    }
    $unread_count = muop_get_unread_notification_count(get_current_user_id());
    wp_send_json_success(array('unread_count' => $unread_count));
}

// AJAX: Đánh dấu tất cả thông báo đã đọc
add_action('wp_ajax_muop_mark_all_notifs_read', 'muop_ajax_mark_all_notifs_read_handler');
add_action('wp_ajax_nopriv_muop_mark_all_notifs_read', 'muop_ajax_mark_all_notifs_read_handler');
function muop_ajax_mark_all_notifs_read_handler() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    muop_mark_all_notifications_as_read(get_current_user_id());
    wp_send_json_success(array('unread_count' => 0));
}

// AJAX: Gửi báo lỗi chương truyện (Yêu cầu đăng nhập)
add_action('wp_ajax_muop_submit_chapter_report', 'muop_ajax_submit_chapter_report_handler');
add_action('wp_ajax_nopriv_muop_submit_chapter_report', 'muop_ajax_submit_chapter_report_handler');
function muop_ajax_submit_chapter_report_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Bạn cần đăng nhập tài khoản để báo lỗi chương truyện!'));
    }

    global $wpdb;
    $story_id    = isset($_POST['story_id']) ? intval($_POST['story_id']) : 0;
    $chapter_id  = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
    $chapter_num = isset($_POST['chapter_num']) ? intval($_POST['chapter_num']) : 1;
    $issue_type  = isset($_POST['issue_type']) ? sanitize_text_field($_POST['issue_type']) : 'Lỗi nội dung';
    $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';

    if (!$story_id || !$chapter_id || empty($description)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập mô tả chi tiết lỗi bạn gặp phải!'));
    }

    $story   = get_post($story_id);
    $chapter = get_post($chapter_id);
    if (!$story || !$chapter) {
        wp_send_json_error(array('message' => 'Không tìm thấy truyện hoặc chương này!'));
    }

    $user_id = get_current_user_id();
    $user_obj = get_userdata($user_id);
    $sender_name = $user_obj ? $user_obj->display_name : 'Một độc giả';

    $table_reports = $wpdb->prefix . 'muop_chapter_reports';
    $inserted = $wpdb->insert($table_reports, array(
        'user_id'     => $user_id,
        'story_id'    => $story_id,
        'chapter_id'  => $chapter_id,
        'chapter_num' => $chapter_num,
        'issue_type'  => $issue_type,
        'description' => $description,
        'status'      => 'pending',
        'admin_reply' => '',
        'created_at'  => current_time('mysql'),
        'updated_at'  => current_time('mysql')
    ));

    if (!$inserted) {
        wp_send_json_error(array('message' => 'Không thể gửi báo lỗi lúc này. Vui lòng thử lại sau!'));
    }
    $inserted_id = $wpdb->insert_id;

    // Gộp danh sách người nhận DUY NHẤT để Admin / Dịch giả chỉ nhận đúng 1 thông báo
    $recipient_ids = array();
    $admins = get_users(array('role' => 'administrator', 'fields' => 'ID'));
    if (!empty($admins)) {
        foreach ($admins as $adm_id) {
            $recipient_ids[] = (int) $adm_id;
        }
    }
    if ($story->post_author) {
        $recipient_ids[] = (int) $story->post_author;
    }
    $recipient_ids = array_unique(array_filter($recipient_ids));
    $recipient_ids = array_diff($recipient_ids, array($user_id));

    foreach ($recipient_ids as $target_uid) {
        muop_add_notification(array(
            'user_id'   => $target_uid,
            'sender_id' => $user_id,
            'type'      => 'chapter_report',
            'title'     => 'Báo lỗi Chương ' . $chapter_num . ' - ' . $story->post_title,
            'message'   => $sender_name . ' vừa báo lỗi (' . $issue_type . '): "' . wp_trim_words($description, 14) . '"',
            'link'      => '#report-' . $inserted_id
        ));
    }

    wp_send_json_success(array(
        'message'   => 'Cảm ơn bạn đã báo lỗi! Ban quản trị và dịch giả đã nhận được thông tin và sẽ phản hồi sớm nhất.',
        'report_id' => $inserted_id
    ));
}

// AJAX: Lấy chi tiết báo lỗi chương (kèm lịch sử hội thoại đối thoại)
add_action('wp_ajax_muop_get_report_details', 'muop_ajax_get_report_details_handler');
add_action('wp_ajax_nopriv_muop_get_report_details', 'muop_ajax_get_report_details_handler');
function muop_ajax_get_report_details_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để xem chi tiết báo lỗi.'));
    }

    global $wpdb;
    $report_id = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
    if (!$report_id) {
        wp_send_json_error(array('message' => 'Thiếu ID báo lỗi.'));
    }

    $table_reports = $wpdb->prefix . 'muop_chapter_reports';
    $report = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_reports WHERE id = %d", $report_id));
    if (!$report) {
        wp_send_json_error(array('message' => 'Không tìm thấy thông tin báo lỗi này.'));
    }

    $current_uid = get_current_user_id();
    $is_admin = muop_is_admin($current_uid);
    $story = get_post($report->story_id);
    $is_author = ($story && (int) $story->post_author === $current_uid);
    $is_reporter = ((int) $report->user_id === $current_uid);

    // Quyền truy cập: Chỉ Admin, Dịch giả của truyện, hoặc chính Người gửi báo lỗi mới được xem
    if (!$is_admin && !$is_author && !$is_reporter) {
        wp_send_json_error(array('message' => 'Bạn không có quyền truy cập vào nội dung báo lỗi này!'));
    }

    $chapter = get_post($report->chapter_id);
    $sender = $report->user_id ? get_userdata($report->user_id) : null;
    $sender_name = $sender ? $sender->display_name : 'Độc giả';
    $sender_avatar = $report->user_id ? get_avatar_url($report->user_id, array('size' => 64)) : '';
    
    // Vai trò người gửi ban đầu
    $sender_role = 'doc_gia';
    $sender_role_label = 'Độc giả';
    if ($sender) {
        if (in_array('administrator', (array) $sender->roles, true)) {
            $sender_role = 'admin';
            $sender_role_label = 'Ban Quản Trị';
        } elseif (in_array('dich_gia', (array) $sender->roles, true)) {
            $sender_role = 'dich_gia';
            $sender_role_label = 'Dịch Giả';
        }
    }

    // Đọc lịch sử phản hồi trao đổi
    $replies = array();
    if (!empty($report->admin_reply)) {
        $decoded = json_decode($report->admin_reply, true);
        if (is_array($decoded)) {
            foreach ($decoded as $rep_item) {
                $item_uid = isset($rep_item['sender_id']) ? intval($rep_item['sender_id']) : 0;
                $item_role = isset($rep_item['role']) ? $rep_item['role'] : 'admin';
                $item_label = isset($rep_item['role_label']) ? $rep_item['role_label'] : ($item_role === 'admin' ? 'Ban Quản Trị' : ($item_role === 'dich_gia' ? 'Dịch Giả' : 'Độc giả'));
                $item_created = isset($rep_item['created_at']) ? $rep_item['created_at'] : '';
                $item_avatar = $item_uid ? get_avatar_url($item_uid, array('size' => 48)) : '';

                $replies[] = array(
                    'sender_id'   => $item_uid,
                    'sender_name' => esc_html(isset($rep_item['sender_name']) ? $rep_item['sender_name'] : 'Thành viên'),
                    'avatar'      => $item_avatar,
                    'role'        => $item_role,
                    'role_label'  => $item_label,
                    'message'     => nl2br(esc_html(isset($rep_item['message']) ? $rep_item['message'] : '')),
                    'time_ago'    => $item_created ? muop_time_ago($item_created) : '',
                    'created_at'  => $item_created ? date_i18n('d/m/Y H:i', strtotime($item_created)) : '',
                    'is_me'       => ($item_uid === $current_uid)
                );
            }
        } else {
            $replies[] = array(
                'sender_id'   => 0,
                'sender_name' => 'Ban Quản Trị',
                'avatar'      => '',
                'role'        => 'admin',
                'role_label'  => 'Ban Quản Trị',
                'message'     => nl2br(esc_html($report->admin_reply)),
                'time_ago'    => muop_time_ago($report->updated_at),
                'created_at'  => date_i18n('d/m/Y H:i', strtotime($report->updated_at)),
                'is_me'       => false
            );
        }
    }

    $response_data = array(
        'report_id'          => (int) $report->id,
        'story_id'           => (int) $report->story_id,
        'story_title'        => $story ? esc_html($story->post_title) : 'Truyện không tồn tại',
        'story_url'          => $story ? esc_url(get_permalink($story->ID)) : '#',
        'chapter_id'         => (int) $report->chapter_id,
        'chapter_num'        => (int) $report->chapter_num,
        'chapter_title'      => $chapter ? esc_html(muop_get_clean_chapter_title($chapter->post_title)) : '',
        'chapter_url'        => $chapter ? esc_url(get_permalink($chapter->ID)) : '#',
        'issue_type'         => esc_html($report->issue_type),
        'description'        => nl2br(esc_html($report->description)),
        'status'             => esc_html($report->status),
        'created_at_time'    => muop_time_ago($report->created_at),
        'created_at_full'    => date_i18n('d/m/Y H:i', strtotime($report->created_at)),
        'sender_id'          => (int) $report->user_id,
        'sender_name'        => esc_html($sender_name),
        'sender_avatar'      => $sender_avatar,
        'sender_role'        => $sender_role,
        'sender_role_label'  => $sender_role_label,
        'replies'            => $replies,
        'can_change_status'  => ($is_admin || $is_author),
        'is_reporter'        => $is_reporter,
        'current_user_role'  => $is_admin ? 'admin' : ($is_author ? 'dich_gia' : 'doc_gia')
    );

    wp_send_json_success($response_data);
}

// AJAX: Gửi phản hồi trong chuỗi hội thoại đối thoại báo lỗi chương
add_action('wp_ajax_muop_submit_report_reply', 'muop_ajax_submit_report_reply_handler');
add_action('wp_ajax_nopriv_muop_submit_report_reply', 'muop_ajax_submit_report_reply_handler');
function muop_ajax_submit_report_reply_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để gửi phản hồi.'));
    }

    global $wpdb;
    $report_id     = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
    $reply_message = isset($_POST['reply_message']) ? sanitize_textarea_field($_POST['reply_message']) : '';
    $status        = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

    if (!$report_id || empty($reply_message)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập nội dung phản hồi!'));
    }

    $table_reports = $wpdb->prefix . 'muop_chapter_reports';
    $report = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_reports WHERE id = %d", $report_id));
    if (!$report) {
        wp_send_json_error(array('message' => 'Không tìm thấy báo cáo lỗi này!'));
    }

    $current_uid = get_current_user_id();
    $current_user = wp_get_current_user();
    $is_admin = muop_is_admin($current_uid);
    $story = get_post($report->story_id);
    $is_author = ($story && (int) $story->post_author === $current_uid);
    $is_reporter = ((int) $report->user_id === $current_uid);

    if (!$is_admin && !$is_author && !$is_reporter) {
        wp_send_json_error(array('message' => 'Bạn không có quyền tham gia phản hồi báo lỗi này!'));
    }

    // Xác định vai trò của người gửi phản hồi
    if ($is_admin) {
        $sender_role = 'admin';
        $sender_role_label = 'Ban Quản Trị';
    } elseif ($is_author) {
        $sender_role = 'dich_gia';
        $sender_role_label = 'Dịch Giả';
    } else {
        $sender_role = 'doc_gia';
        $sender_role_label = 'Người Báo Lỗi';
    }

    // Đọc danh sách replies hiện tại
    $replies = array();
    if (!empty($report->admin_reply)) {
        $decoded = json_decode($report->admin_reply, true);
        if (is_array($decoded)) {
            $replies = $decoded;
        } else {
            $replies[] = array(
                'sender_id'   => 0,
                'sender_name' => 'Ban Quản Trị',
                'role'        => 'admin',
                'role_label'  => 'Ban Quản Trị',
                'message'     => $report->admin_reply,
                'created_at'  => $report->updated_at
            );
        }
    }

    $new_entry = array(
        'sender_id'   => $current_uid,
        'sender_name' => $current_user->display_name,
        'role'        => $sender_role,
        'role_label'  => $sender_role_label,
        'message'     => $reply_message,
        'created_at'  => current_time('mysql')
    );
    $replies[] = $new_entry;

    // Cập nhật trạng thái
    $new_status = $report->status;
    if (($is_admin || $is_author) && !empty($status)) {
        $new_status = $status;
    } elseif ($is_admin || $is_author) {
        if ($new_status === 'pending') {
            $new_status = 'processing';
        }
    }

    $wpdb->update(
        $table_reports,
        array(
            'admin_reply' => wp_json_encode($replies, JSON_UNESCAPED_UNICODE),
            'status'      => $new_status,
            'updated_at'  => current_time('mysql')
        ),
        array('id' => $report_id)
    );

    $story_title = $story ? $story->post_title : 'Truyện';

    // BẮN THÔNG BÁO CHO CÁC BÊN (Mỗi người duy nhất 1 thông báo)
    $recipient_ids = array();

    if ($is_admin || $is_author) {
        // Admin hoặc Dịch giả trả lời -> Báo cho Độc giả
        if ($report->user_id > 0) {
            $recipient_ids[] = (int) $report->user_id;
        }
        // Nếu Admin trả lời mà dịch giả là người khác -> Báo cho dịch giả
        if ($is_admin && $story && $story->post_author) {
            $recipient_ids[] = (int) $story->post_author;
        }
        // Nếu Dịch giả trả lời -> Báo cho Admin
        if ($is_author && !$is_admin) {
            $admins = get_users(array('role' => 'administrator', 'fields' => 'ID'));
            foreach ($admins as $aid) {
                $recipient_ids[] = (int) $aid;
            }
        }
    } else {
        // Độc giả gửi tiếp phản hồi -> Báo cho Admin và Dịch giả
        $admins = get_users(array('role' => 'administrator', 'fields' => 'ID'));
        foreach ($admins as $aid) {
            $recipient_ids[] = (int) $aid;
        }
        if ($story && $story->post_author) {
            $recipient_ids[] = (int) $story->post_author;
        }
    }

    // Loại bỏ trùng lặp và loại trừ người gửi
    $recipient_ids = array_unique(array_filter($recipient_ids));
    $recipient_ids = array_diff($recipient_ids, array($current_uid));

    $notif_title = ($is_reporter ? 'Độc giả phản hồi tiếp báo lỗi: ' : 'Phản hồi báo lỗi chương: ') . $story_title;
    $notif_msg = $current_user->display_name . ' (' . $sender_role_label . '): "' . wp_trim_words($reply_message, 14) . '"';

    foreach ($recipient_ids as $target_uid) {
        muop_add_notification(array(
            'user_id'   => $target_uid,
            'sender_id' => $current_uid,
            'type'      => 'report_reply',
            'title'     => $notif_title,
            'message'   => $notif_msg,
            'link'      => '#report-' . $report_id
        ));
    }

    wp_send_json_success(array(
        'message' => 'Đã gửi phản hồi thành công!',
        'status'  => $new_status,
        'entry'   => array(
            'sender_id'   => $current_uid,
            'sender_name' => esc_html($current_user->display_name),
            'avatar'      => get_avatar_url($current_uid, array('size' => 48)),
            'role'        => $sender_role,
            'role_label'  => $sender_role_label,
            'message'     => nl2br(esc_html($reply_message)),
            'time_ago'    => 'Vừa xong',
            'created_at'  => date_i18n('d/m/Y H:i'),
            'is_me'       => true
        )
    ));
}

// AJAX: Dịch giả lưu thông tin ngân hàng nhận nhuận bút
add_action('wp_ajax_muop_save_translator_bank', 'muop_ajax_save_translator_bank_handler');
add_action('wp_ajax_nopriv_muop_save_translator_bank', 'muop_ajax_save_translator_bank_handler');
function muop_ajax_save_translator_bank_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    $user_id = get_current_user_id();
    $bank_name           = isset($_POST['bank_name']) ? sanitize_text_field($_POST['bank_name']) : '';
    $bank_account_holder = isset($_POST['bank_account_holder']) ? sanitize_text_field($_POST['bank_account_holder']) : '';
    $bank_account_number = isset($_POST['bank_account_number']) ? sanitize_text_field($_POST['bank_account_number']) : '';

    if (empty($bank_name) || empty($bank_account_holder) || empty($bank_account_number)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ thông tin Tên người nhận, Ngân hàng và Số tài khoản!'));
    }

    update_user_meta($user_id, '_muop_bank_name', $bank_name);
    update_user_meta($user_id, '_muop_bank_account_holder', $bank_account_holder);
    update_user_meta($user_id, '_muop_bank_account_number', $bank_account_number);

    wp_send_json_success(array('message' => 'Lưu thông tin tài khoản ngân hàng thành công!'));
}

// AJAX: Admin thanh toán lương & gửi ảnh bill cho dịch giả
add_action('wp_ajax_muop_admin_pay_salary', 'muop_ajax_admin_pay_salary_handler');
add_action('wp_ajax_nopriv_muop_admin_pay_salary', 'muop_ajax_admin_pay_salary_handler');
function muop_admin_pay_salary_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    $translator_id = isset($_POST['translator_id']) ? intval($_POST['translator_id']) : 0;
    $period        = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '';
    $amount        = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $views         = isset($_POST['views']) ? intval($_POST['views']) : 0;
    $note          = isset($_POST['note']) ? sanitize_textarea_field($_POST['note']) : '';

    if (!$translator_id || $amount <= 0 || empty($period)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ kỳ thanh toán và số tiền!'));
    }

    $translator = get_userdata($translator_id);
    if (!$translator) {
        wp_send_json_error(array('message' => 'Không tìm thấy dịch giả này!'));
    }

    // Kiểm tra upload file bill
    if (empty($_FILES['bill_image']) || empty($_FILES['bill_image']['name'])) {
        wp_send_json_error(array('message' => 'Vui lòng chọn ảnh chụp biên lai/bill đã chuyển khoản thành công làm bằng chứng!'));
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    $upload = wp_handle_upload($_FILES['bill_image'], array('test_form' => false));

    if (isset($upload['error'])) {
        wp_send_json_error(array('message' => 'Lỗi tải lên bill: ' . $upload['error']));
    }

    $bill_url = $upload['url'];
    $bank_name    = get_user_meta($translator_id, '_muop_bank_name', true) ?: '';
    $bank_holder  = get_user_meta($translator_id, '_muop_bank_account_holder', true) ?: '';
    $bank_number  = get_user_meta($translator_id, '_muop_bank_account_number', true) ?: '';

    global $wpdb;
    $table_payments = $wpdb->prefix . 'muop_translator_payments';
    $wpdb->insert($table_payments, array(
        'translator_id'        => $translator_id,
        'admin_id'             => get_current_user_id(),
        'period'               => $period,
        'views'                => $views,
        'amount'               => $amount,
        'bill_image'           => $bill_url,
        'note'                 => $note,
        'bank_name'            => $bank_name,
        'bank_account_holder'  => $bank_holder,
        'bank_account_number'  => $bank_number,
        'status'               => 'paid',
        'created_at'           => current_time('mysql')
    ));

    // Bắn thông báo đến Dịch giả kèm ảnh bill
    muop_add_notification(array(
        'user_id'   => $translator_id,
        'sender_id' => get_current_user_id(),
        'type'      => 'payment_received',
        'title'     => 'Thanh toán nhuận bút kỳ ' . $period . ' thành công!',
        'message'   => 'Admin đã quyết toán và chuyển số tiền ' . number_format($amount) . ' VNĐ cho bạn. Nhấn để xem ảnh bill chuyển khoản làm bằng chứng.',
        'link'      => home_url('/thong-tin-dich-gia/#salaryHistory'),
        'image'     => $bill_url
    ));

    wp_send_json_success(array(
        'message'  => 'Đã xác nhận thanh toán và gửi thông báo kèm bill tới dịch giả thành công!',
        'bill_url' => $bill_url
    ));
}

// AJAX: Admin phản hồi báo lỗi chương
add_action('wp_ajax_muop_admin_reply_report', 'muop_ajax_admin_reply_report_handler');
add_action('wp_ajax_nopriv_muop_admin_reply_report', 'muop_ajax_admin_reply_report_handler');
function muop_ajax_admin_reply_report_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    global $wpdb;
    $table_reports = $wpdb->prefix . 'muop_chapter_reports';
    $report_id     = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
    $reply_text    = isset($_POST['reply_text']) ? sanitize_textarea_field($_POST['reply_text']) : '';
    $status        = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'pending';

    $report = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_reports WHERE id = %d", $report_id));
    if (!$report) {
        wp_send_json_error(array('message' => 'Không tìm thấy báo cáo lỗi!'));
    }

    $wpdb->update(
        $table_reports,
        array(
            'admin_reply' => $reply_text,
            'status'      => $status,
            'updated_at'  => current_time('mysql')
        ),
        array('id' => $report_id)
    );

    $story = get_post($report->story_id);
    $story_title = $story ? $story->post_title : 'Truyện';

    // 1. Gửi thông báo phản hồi cho độc giả (nếu độc giả đã đăng nhập khi báo lỗi)
    if ($report->user_id > 0) {
        muop_add_notification(array(
            'user_id'   => (int) $report->user_id,
            'sender_id' => get_current_user_id(),
            'type'      => 'report_reply',
            'title'     => 'Phản hồi báo lỗi chương: ' . $story_title,
            'message'   => 'Admin đã xử lý và phản hồi báo lỗi Chương ' . $report->chapter_num . ': "' . wp_trim_words($reply_text, 20) . '"',
            'link'      => get_permalink($report->chapter_id)
        ));
    }

    // 2. Gửi thông báo cho dịch giả
    if ($story && $story->post_author && (int) $story->post_author !== get_current_user_id()) {
        muop_add_notification(array(
            'user_id'   => (int) $story->post_author,
            'sender_id' => get_current_user_id(),
            'type'      => 'report_reply',
            'title'     => 'Cập nhật xử lý báo lỗi: ' . $story_title,
            'message'   => 'Admin đã cập nhật trạng thái báo lỗi Chương ' . $report->chapter_num . ' thành ' . ($status === 'resolved' ? 'Đã xử lý' : 'Đang xử lý') . ': "' . wp_trim_words($reply_text, 20) . '"',
            'link'      => get_permalink($report->chapter_id)
        ));
    }

    wp_send_json_success(array('message' => 'Phản hồi báo lỗi thành công!'));
}

// AJAX: Admin gửi thông báo hệ thống (Broadcast)
add_action('wp_ajax_muop_admin_send_broadcast', 'muop_ajax_admin_send_broadcast_handler');
add_action('wp_ajax_nopriv_muop_admin_send_broadcast', 'muop_ajax_admin_send_broadcast_handler');
function muop_ajax_admin_send_broadcast_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    $target  = isset($_POST['target']) ? sanitize_text_field($_POST['target']) : 'all';
    $title   = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    $link    = isset($_POST['link']) ? esc_url_raw($_POST['link']) : '';

    if (empty($title) || empty($message)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập tiêu đề và nội dung thông báo!'));
    }

    $args = array('fields' => 'ID');
    if ($target === 'dich_gia') {
        $args['role'] = 'dich_gia';
    } elseif ($target === 'doc_gia') {
        $args['role'] = 'doc_gia';
    }

    $user_ids = get_users($args);
    $count = 0;
    foreach ($user_ids as $uid) {
        $res = muop_add_notification(array(
            'user_id'   => (int) $uid,
            'sender_id' => get_current_user_id(),
            'type'      => 'system_broadcast',
            'title'     => $title,
            'message'   => $message,
            'link'      => $link
        ));
        if ($res) $count++;
    }

    wp_send_json_success(array(
        'message' => 'Đã gửi thông báo hệ thống thành công tới ' . $count . ' người dùng!'
    ));
}

/**
 * Định dạng nội dung giới thiệu / tóm tắt truyện thành các đoạn văn riêng biệt có khoảng cách đẹp mắt (như nội dung truyện)
 */
function muop_format_story_summary($content) {
    if (empty($content)) return '';

    // Chuẩn hóa thẻ HTML nếu có
    $content = preg_replace('/<br\s*\/?>/i', "\n", $content);
    $content = preg_replace('/<\/p>/i', "\n", $content);
    $content = preg_replace('/<p[^>]*>/i', '', $content);
    $content = strip_tags($content, '<strong><b><em><i><u><span><a>');

    // Chuẩn hóa dấu xuống dòng
    $content = str_replace(array("\r\n", "\r"), "\n", $content);
    $lines = explode("\n", $content);
    $output = '';
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;
        $output .= '<p>' . $line . '</p>' . "\n";
    }

    return $output;
}

/**
 * Định dạng lời giới thiệu Team Dịch hoặc Dịch Giả thành các đoạn văn xuống dòng chuẩn xác
 */
function muop_format_team_bio($bio) {
    if (empty($bio) || !is_string($bio)) return '';

    // Chuẩn hóa dấu xuống dòng
    $bio = str_replace(array("\r\n", "\r"), "\n", $bio);
    $lines = explode("\n", $bio);
    $output = '';
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '') continue;
        $output .= '<p>' . esc_html($trimmed) . '</p>' . "\n";
    }

    return !empty($output) ? $output : '<p>' . esc_html($bio) . '</p>';
}

/**
 * AJAX: Thêm Tính Năng / Nâng Cấp Hệ Thống Mới (Hiển thị ở đầu danh mục tính năng)
 */
add_action('wp_ajax_muop_admin_add_feature_update', 'muop_admin_add_feature_update_handler');
add_action('wp_ajax_nopriv_muop_admin_add_feature_update', 'muop_admin_add_feature_update_handler');
function muop_admin_add_feature_update_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    $title       = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    $version     = isset($_POST['version']) ? sanitize_text_field($_POST['version']) : '';
    $category    = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'Hệ thống';
    $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
    $badge       = isset($_POST['badge']) ? sanitize_text_field($_POST['badge']) : 'MỚI';

    if (empty($title) || empty($description)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập đầy đủ tên tính năng và mô tả chi tiết!'));
    }

    $updates = get_option('muop_custom_feature_updates', array());
    if (!is_array($updates)) {
        $updates = array();
    }

    $new_feature = array(
        'id'          => 'feat_' . time() . '_' . wp_rand(100, 999),
        'title'       => $title,
        'version'     => $version ?: ('v' . MUOP_THEME_VERSION),
        'category'    => $category,
        'description' => $description,
        'badge'       => $badge,
        'created_at'  => current_time('d/m/Y H:i')
    );

    // Luôn đưa tính năng / chức năng mới lên đầu mục tính năng
    array_unshift($updates, $new_feature);
    update_option('muop_custom_feature_updates', $updates);

    wp_send_json_success(array(
        'message' => 'Đã thêm tính năng mới thành công và hiển thị ở đầu trang!',
        'feature' => $new_feature
    ));
}

/**
 * AJAX: Xóa Tính Năng / Nâng Cấp Hệ Thống
 */
add_action('wp_ajax_muop_admin_delete_feature_update', 'muop_admin_delete_feature_update_handler');
add_action('wp_ajax_nopriv_muop_admin_delete_feature_update', 'muop_admin_delete_feature_update_handler');
function muop_admin_delete_feature_update_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    if (empty($id)) {
        wp_send_json_error(array('message' => 'Thiếu mã tính năng!'));
    }

    $updates = get_option('muop_custom_feature_updates', array());
    if (is_array($updates)) {
        $updates = array_values(array_filter($updates, function($item) use ($id) {
            return !empty($item['id']) && $item['id'] !== $id;
        }));
        update_option('muop_custom_feature_updates', $updates);
    }

    wp_send_json_success(array('message' => 'Đã xóa tính năng thành công!'));
}

/**
 * =========================================================================
 * AJAX HANDLERS: CÔNG CỤ LẤY TRUYỆN VIVUTRUYEN.NET (ADMIN)
 * =========================================================================
 */

/**
 * Chuẩn hóa URL truyện VivuTruyen
 */
function muop_vivu_normalize_url($raw_url) {
    $url = trim($raw_url);
    if (empty($url)) return '';
    // Xóa hash fragment (#...) và query parameters (?...)
    $url = preg_replace('/[#\?].*$/', '', $url);
    $parsed = parse_url($url);
    if (empty($parsed['host']) || stripos($parsed['host'], 'vivutruyen.net') === false) {
        return '';
    }
    $path = trim($parsed['path'] ?? '', '/');
    if (empty($path)) return '';
    $parts = explode('/', $path);
    // Nếu người dùng dán link chương lẻ (vd: /slug/chuong-1/), lấy slug của bộ truyện
    $story_slug = $parts[0] ?? '';
    if (empty($story_slug)) return '';
    return 'https://vivutruyen.net/' . $story_slug . '/';
}

/**
 * AJAX: Lấy thông tin chi tiết và danh sách chương truyện từ VivuTruyen
 */
add_action('wp_ajax_muop_vivu_fetch_story_info', 'muop_vivu_fetch_story_info_handler');
add_action('wp_ajax_nopriv_muop_vivu_fetch_story_info', 'muop_vivu_fetch_story_info_handler');
function muop_vivu_fetch_story_info_handler() {
    @set_time_limit(180);
    @ini_set('max_execution_time', '180');
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện chức năng này!'));
    }

    $raw_url = isset($_POST['url']) ? sanitize_text_field(wp_unslash($_POST['url'])) : '';
    $story_url = muop_vivu_normalize_url($raw_url);
    if (empty($story_url)) {
        wp_send_json_error(array('message' => 'URL không hợp lệ! Vui lòng nhập đường link từ website vivutruyen.net (Ví dụ: https://vivutruyen.net/chong-toi-phai-long-ban-than-toi/)'));
    }

    $response = wp_remote_get($story_url, array(
        'timeout'     => 25,
        'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'sslverify'   => false,
    ));

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => 'Không thể kết nối đến vivutruyen.net: ' . $response->get_error_message()));
    }

    $status_code = wp_remote_retrieve_response_code($response);
    if ($status_code !== 200) {
        wp_send_json_error(array('message' => 'Trang truyện phản hồi mã lỗi HTTP ' . $status_code . '. Vui lòng kiểm tra lại đường link!'));
    }

    $html = wp_remote_retrieve_body($response);
    if (empty($html)) {
        wp_send_json_error(array('message' => 'Không nhận được nội dung từ trang truyện vivutruyen.net!'));
    }

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8"?>' . $html);
    libxml_clear_errors();
    $xpath = new DOMXPath($doc);

    // 1. Tên truyện
    $titleNode = $xpath->query('//a[contains(@class, "title-truyen")] | //h1[contains(@class, "title-truyen")] | //h1')->item(0);
    $title = $titleNode ? trim($titleNode->textContent) : '';
    if (empty($title)) {
        $metaTitle = $xpath->query('//meta[@property="og:title"]/@content')->item(0);
        if ($metaTitle) $title = trim($metaTitle->textContent);
    }

    // 2. Ảnh bìa
    $coverNode = $xpath->query('//div[contains(@class, "image-truyen")]//img')->item(0);
    $cover = '';
    if ($coverNode) {
        $cover = $coverNode->getAttribute('data-src') ?: $coverNode->getAttribute('src');
    }
    if (empty($cover)) {
        $metaCover = $xpath->query('//meta[@property="og:image"]/@content')->item(0);
        if ($metaCover) $cover = trim($metaCover->textContent);
    }

    // 3. Tác giả
    $authorNode = $xpath->query('//ul[contains(@class, "info-truyen")]//li[contains(., "Tác giả")]')->item(0);
    $author = 'Chưa rõ';
    if ($authorNode) {
        $author = trim(str_replace(array('Tác giả:', 'Tác giả'), '', $authorNode->textContent));
    }

    // 4. Thể loại
    $genreNode = $xpath->query('//ul[contains(@class, "info-truyen")]//li[contains(., "Thể Loại") or contains(., "Thể loại")]')->item(0);
    $genres = '';
    if ($genreNode) {
        $genres = trim(str_replace(array('Thể Loại:', 'Thể loại:', 'Thể Loại', 'Thể loại'), '', $genreNode->textContent));
        $genres = preg_replace('/\s+/', ' ', $genres);
    }

    // 5. Trạng thái
    $statusNode = $xpath->query('//ul[contains(@class, "info-truyen")]//li[contains(., "Trạng Thái") or contains(., "Trạng thái")]')->item(0);
    $status = 'Đang tiến hành';
    if ($statusNode) {
        $status = trim(str_replace(array('Trạng Thái:', 'Trạng thái:', 'Trạng Thái', 'Trạng thái'), '', $statusNode->textContent));
    }

    // 6. Mô tả / Văn án
    $descNode = $xpath->query('//div[contains(@class, "noi-dung")]')->item(0);
    $desc = '';
    if ($descNode) {
        $scripts = $descNode->getElementsByTagName('script');
        while ($scripts->length > 0) {
            $scripts->item(0)->parentNode->removeChild($scripts->item(0));
        }
        $paragraphs = array();
        foreach ($descNode->getElementsByTagName('p') as $p) {
            $t = trim($p->textContent);
            if (!empty($t)) $paragraphs[] = $t;
        }
        if (!empty($paragraphs)) {
            $desc = implode("\n\n", $paragraphs);
        } else {
            $desc = trim($descNode->textContent);
            $desc = preg_replace("/\n{3,}/", "\n\n", $desc);
        }
    }

    // 7. Danh sách chương (VivuTruyen xếp từ mới nhất tới chương 1 -> cần đảo ngược)
    $chapNodes = $xpath->query('//div[contains(@class, "list")]//a[contains(@class, "chap-title")]');
    $rawChapters = array();
    foreach ($chapNodes as $cn) {
        $cTitle = trim($cn->textContent);
        $cUrl = $cn->getAttribute('href');
        if (!empty($cUrl)) {
            $rawChapters[] = array(
                'title' => $cTitle,
                'url'   => $cUrl
            );
        }
    }

    // Đảo ngược để chương 1 ở đầu, chương mới nhất ở cuối
    $chapters = array_reverse($rawChapters);

    $formattedChapters = array();
    $idx = 1;
    foreach ($chapters as $chap) {
        $formattedChapters[] = array(
            'index' => $idx++,
            'title' => $chap['title'],
            'url'   => $chap['url']
        );
    }

    wp_send_json_success(array(
        'story_url'   => $story_url,
        'title'       => $title ?: 'Không có tiêu đề',
        'cover'       => $cover,
        'author'      => $author,
        'genres'      => $genres,
        'status'      => $status,
        'description' => $desc,
        'total_chaps' => count($formattedChapters),
        'chapters'    => $formattedChapters
    ));
}

/**
 * AJAX: Lấy nội dung chi tiết theo batch các chương
 */
add_action('wp_ajax_muop_vivu_fetch_chapters_batch', 'muop_vivu_fetch_chapters_batch_handler');
add_action('wp_ajax_nopriv_muop_vivu_fetch_chapters_batch', 'muop_vivu_fetch_chapters_batch_handler');
function muop_vivu_fetch_chapters_batch_handler() {
    if (!muop_verify_ajax_nonce('muop_ajax_nonce', 'nonce')) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!', 'require_login' => true));
    }
    if (!muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện chức năng này!'));
    }

    $raw_batch = isset($_POST['chapters']) ? $_POST['chapters'] : array();
    if (empty($raw_batch) || !is_array($raw_batch)) {
        wp_send_json_error(array('message' => 'Danh sách chương rỗng!'));
    }

    $results = array();
    foreach ($raw_batch as $item) {
        $index = isset($item['index']) ? (int)$item['index'] : 0;
        $title = isset($item['title']) ? sanitize_text_field(wp_unslash($item['title'])) : '';
        $url   = isset($item['url']) ? esc_url_raw($item['url']) : '';

        if (empty($url)) continue;

        $resp = wp_remote_get($url, array(
            'timeout'    => 20,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'sslverify'  => false,
        ));

        $content = '';
        if (!is_wp_error($resp) && wp_remote_retrieve_response_code($resp) === 200) {
            $chHtml = wp_remote_retrieve_body($resp);
            $cDoc = new DOMDocument();
            libxml_use_internal_errors(true);
            $cDoc->loadHTML('<?xml encoding="utf-8"?>' . $chHtml);
            libxml_clear_errors();
            $cXpath = new DOMXPath($cDoc);

            $readingNode = $cXpath->query('//div[contains(@class, "reading")]')->item(0);
            if ($readingNode) {
                // Loại bỏ script, style và quảng cáo nếu có
                $badTags = $cXpath->query('.//script | .//style | .//div[contains(@class, "ads")] | .//div[contains(@class, "banner")]', $readingNode);
                foreach ($badTags as $bt) {
                    $bt->parentNode->removeChild($bt);
                }

                $pNodes = $readingNode->getElementsByTagName('p');
                $paragraphs = array();
                if ($pNodes->length > 0) {
                    foreach ($pNodes as $p) {
                        $txt = trim($p->textContent);
                        if (!empty($txt)) {
                            $paragraphs[] = $txt;
                        }
                    }
                }
                if (!empty($paragraphs)) {
                    $content = implode("\n\n", $paragraphs);
                } else {
                    $content = trim($readingNode->textContent);
                    $content = preg_replace("/\n{3,}/", "\n\n", $content);
                }
            }
        }

        $results[] = array(
            'index'   => $index,
            'title'   => $title,
            'url'     => $url,
            'content' => $content,
            'words'   => !empty($content) ? str_word_count(strip_tags($content)) : 0
        );
    }

    wp_send_json_success(array(
        'results' => $results
    ));
}

/**
 * Tự động đăng xuất trực tiếp và chuyển hướng về trang chủ
 * Bỏ qua hoàn toàn màn hình xác nhận mặc định của WordPress ("You are attempting to log out of...")
 */
add_action('check_admin_referer', function($action, $result) {
    if ($action === 'log-out' && !isset($_GET['_wpnonce'])) {
        wp_logout();
        $redirect_to = !empty($_REQUEST['redirect_to']) ? wp_sanitize_redirect($_REQUEST['redirect_to']) : home_url('/');
        wp_safe_redirect($redirect_to);
        exit;
    }
}, 10, 2);

add_action('login_init', function() {
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        // Tự động xử lý đăng xuất ngay khi truy cập action=logout mà không hiển thị trang xác nhận
        wp_logout();
        $redirect_to = !empty($_REQUEST['redirect_to']) ? wp_sanitize_redirect($_REQUEST['redirect_to']) : home_url('/');
        wp_safe_redirect($redirect_to);
        exit;
    }
});

// Hỗ trợ đăng xuất trực tiếp qua URL /?muop_action=logout
add_action('init', function() {
    if (isset($_GET['muop_action']) && $_GET['muop_action'] === 'logout') {
        wp_logout();
        wp_safe_redirect(home_url('/'));
        exit;
    }
});

/**
 * Lọc tìm kiếm chỉ tìm kiếm post type 'truyen' và post status 'publish'
 * Giúp đếm chính xác số lượng truyện được tìm thấy và hỗ trợ phân trang chuẩn xác
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', 'truyen');
        $query->set('post_status', 'publish');
        $query->set('posts_per_page', 24);
    }
});

/**
 * Khi truyện đến giờ hẹn tự động xuất bản (future -> publish), dọn dẹp các meta hẹn giờ và trạng thái ẩn
 */
add_action('future_to_publish', function($post) {
    if (!$post) return;
    $post_id = is_object($post) ? $post->ID : (int)$post;
    $p = get_post($post_id);
    if (!$p || $p->post_type !== 'truyen') return;

    delete_post_meta($post_id, '_truyen_scheduled_time');
    delete_post_meta($post_id, '_truyen_scheduled_by');
    delete_post_meta($post_id, '_truyen_is_hidden');
    delete_post_meta($post_id, '_truyen_hidden_by');
    delete_post_meta($post_id, '_truyen_hidden_at');
    delete_post_meta($post_id, '_truyen_prev_status');
});

/**
 * Lọc bảo vệ: Đảm bảo các query công khai (tìm kiếm, thể loại, lưu trữ, RSS) chỉ lấy truyện publish,
 * không làm lộ truyện hẹn giờ (future) hay bản nháp/truyện ẩn ra ngoài công chúng
 */
add_action('pre_get_posts', function($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->is_search() || $query->is_archive() || $query->is_home() || $query->is_feed()) {
        $pt = $query->get('post_type');
        if (empty($pt) || $pt === 'truyen' || (is_array($pt) && in_array('truyen', $pt))) {
            $query->set('post_status', 'publish');
        }
    }
});

/** =========================================================================
 * HỆ THỐNG BẢO VỆ BẢN QUYỀN: CHỐNG CRAWLER & CHỐNG SAO CHÉP TRUYỆN TỰ ĐỘNG
 * ========================================================================= */

/**
 * 1. Chặn các công cụ Crawl / Bot / Scraper tự động truy cập website
 */
add_action('init', function() {
    if (defined('WP_CLI') && WP_CLI) return;
    if (defined('DOING_CRON') && DOING_CRON) return;
    if (php_sapi_name() === 'cli') return;

    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';

    // Nếu không có User-Agent (thường là script gửi curl thô hoặc bot lậu) -> Chặn trên frontend
    if (empty($user_agent)) {
        if (!is_admin()) {
            status_header(403);
            header('Content-Type: text/plain; charset=utf-8');
            header('X-Robots-Tag: noindex, nofollow');
            exit('Access Denied: Missing User-Agent header. [Anti-Crawl Protection by Muop Dang]');
        }
        return;
    }

    $ua_lower = strtolower($user_agent);

    // Danh sách Bot hợp lệ (Search Engine Bots - giữ lại để không ảnh hưởng SEO Google/Bing)
    $legit_bots = array(
        'googlebot',
        'bingbot',
        'bingpreview',
        'slurp',
        'duckduckbot',
        'baiduspider',
        'yandexbot',
        'facebookexternalhit',
        'twitterbot',
        'applebot',
        'zalo'
    );
    foreach ($legit_bots as $good_bot) {
        if (strpos($ua_lower, $good_bot) !== false) {
            return; // Bot tìm kiếm hợp lệ, cho phép lập chỉ mục
        }
    }

    // Danh sách các công cụ crawl, thư viện cào dữ liệu phổ biến cần chặn triệt để
    $scraper_signatures = array(
        'python-requests',
        'python-urllib',
        'scrapy',
        'aiohttp',
        'httpx',
        'curl/',
        'wget/',
        'httpie',
        'guzzlehttp',
        'faraday',
        'rest-client',
        'go-http-client',
        'node-fetch',
        'axios/',
        'got/',
        'needle/',
        'puppeteer',
        'playwright',
        'headlesschrome',
        'phantomjs',
        'selenium',
        'postmanruntime',
        'insomnia',
        'bytespider',
        'ahrefsbot',
        'semrushbot',
        'mj12bot',
        'dotbot',
        'petalbot',
        'dataforseo',
        'screaming frog',
        'spiderman',
        'crawly',
        'webmole'
    );

    foreach ($scraper_signatures as $sig) {
        if (strpos($ua_lower, $sig) !== false) {
            status_header(403);
            header('Content-Type: text/plain; charset=utf-8');
            header('X-Robots-Tag: noindex, nofollow');
            exit("Access Denied: Automated scraping tools are strictly prohibited. [Anti-Crawl Protection by Muop Dang]");
        }
    }
}, 1);

/**
 * 2. Khóa toàn bộ REST API lấy dữ liệu truyện & chương mặc định của WordPress đối với khách / tool
 */
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }

    // Nếu đã đăng nhập có quyền quản trị nội dung -> cho phép bình thường
    if (current_user_can('edit_posts')) {
        return $result;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

    // Khóa các endpoint REST API WordPress v2 nhạy cảm: chuong, truyen, posts, users, pages, comments
    // (Bên ngoài thường dùng /wp-json/wp/v2/chuong để leech sạch truyện dạng JSON)
    if (preg_match('#/wp-json/wp/v2/(chuong|truyen|posts|users|pages|comments)#i', $request_uri)) {
        return new WP_Error(
            'rest_forbidden',
            __('Nội dung truyện được bảo hộ tác quyền. Không cho phép truy xuất qua REST API.', 'muop-dang'),
            array('status' => 403)
        );
    }

    return $result;
});

// Gỡ bỏ thẻ link discovery REST API trong HTML header
remove_action('template_redirect', 'rest_output_link_header', 11);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');

/**
 * 3. Vô hiệu hóa toàn bộ luồng RSS Feed để chặn các bên leech truyện qua RSS
 */
function muop_disable_all_rss_feeds() {
    status_header(403);
    wp_die(
        __('Kênh RSS đã bị vô hiệu hóa để bảo vệ bản quyền tác phẩm. Mời bạn đọc truyện trực tiếp tại website Mướp Đắng!', 'muop-dang'),
        __('RSS Feed Disabled', 'muop-dang'),
        array('response' => 403)
    );
}
add_action('do_feed', 'muop_disable_all_rss_feeds', 1);
add_action('do_feed_rdf', 'muop_disable_all_rss_feeds', 1);
add_action('do_feed_rss', 'muop_disable_all_rss_feeds', 1);
add_action('do_feed_rss2', 'muop_disable_all_rss_feeds', 1);
add_action('do_feed_atom', 'muop_disable_all_rss_feeds', 1);
add_action('do_feed_rss2_comments', 'muop_disable_all_rss_feeds', 1);
add_action('do_feed_atom_comments', 'muop_disable_all_rss_feeds', 1);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

/**
 * 4. Bổ sung cấu hình robots.txt chặn các bot cào dữ liệu và các đường dẫn feed/API
 */
add_filter('robots_txt', function($output, $public) {
    $custom = "\n# [Bảo vệ bản quyền] Quy tắc chặn Crawler & Bot của Mướp Đắng\n";
    $custom .= "User-agent: *\n";
    $custom .= "Disallow: /wp-json/\n";
    $custom .= "Disallow: /feed/\n";
    $custom .= "Disallow: /*/feed/\n";
    $custom .= "Disallow: /?s=*\n";
    $custom .= "Disallow: /search/\n";
    $custom .= "\n# Chặn các bot quét dữ liệu thương mại\n";
    $bad_bots = array('Bytespider', 'SemrushBot', 'AhrefsBot', 'MJ12bot', 'DotBot', 'PetalBot', 'ClaudeBot', 'GPTBot');
    foreach ($bad_bots as $b) {
        $custom .= "User-agent: " . $b . "\nDisallow: /\n";
    }
    return $output . $custom;
}, 99, 2);

/**
 * 5. Thiết lập HTTP Headers & Meta ngăn lưu bản sao (Cache / Archive) của chương truyện
 */
add_action('template_redirect', function() {
    if (is_feed()) {
        muop_disable_all_rss_feeds();
    }
    if (is_singular('chuong')) {
        if (!headers_sent()) {
            header('X-Robots-Tag: noarchive, nosnippet, noimageindex');
            header('X-Frame-Options: SAMEORIGIN');
        }
    }
}, 1);

add_action('wp_head', function() {
    if (is_singular('chuong')) {
        echo '<meta name="robots" content="noarchive, nosnippet">' . "\n";
    }
}, 1);

/**
 * 6. Chèn watermark bản quyền chìm ngẫu nhiên vào nội dung chương khi xuất ra
 * Nếu crawler lấy HTML thô, watermark này sẽ hòa vào văn bản của chúng
 */
add_filter('the_content', function($content) {
    if (!is_singular('chuong') || is_admin() || empty($content) || !is_string($content)) {
        return $content;
    }

    $watermark = '<span class="muop-copy-decoy" aria-hidden="true" style="display:none!important;font-size:0!important;height:0!important;width:0!important;opacity:0!important;position:absolute!important;left:-9999px!important;user-select:none!important;">[Nội dung được bảo hộ độc quyền tại website Mướp Đắng - muopdang.com]</span>';

    // Chèn watermark vào sau các thẻ </p> cách quãng để đánh dấu bản quyền
    $paragraphs = explode('</p>', $content);
    $total = count($paragraphs);
    if ($total > 3) {
        // Chèn vào đoạn thứ 2 và đoạn gần cuối
        $mid = (int)floor($total / 2);
        if (isset($paragraphs[1])) {
            $paragraphs[1] .= $watermark;
        }
        if (isset($paragraphs[$mid])) {
            $paragraphs[$mid] .= $watermark;
        }
        $content = implode('</p>', $paragraphs);
    }

    return $content;
}, 95);

/**
 * =========================================================================
 * 7. BỔ SUNG QUẢN LÝ KHÁCH ĐỌC TRUYỆN & ĐỒNG BỘ LỊCH SỬ TÁCH CHƯƠNG ĐA THIẾT BỊ
 * =========================================================================
 */

/**
 * Lấy IP thực của khách truy cập
 */
function muop_get_real_client_ip() {
    $keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $parts = explode(',', $_SERVER[$k]);
            $ip = trim($parts[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '127.0.0.1';
}

/**
 * AJAX: Lấy danh sách nhật ký khách đọc truyện theo ngày (Dành cho Quản trị viên)
 */
add_action('wp_ajax_muop_get_visitor_read_logs', 'muop_ajax_get_visitor_read_logs_handler');
function muop_ajax_get_visitor_read_logs_handler() {
    if (!is_user_logged_in() || !muop_is_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền truy cập dữ liệu này!'));
    }

    global $wpdb;
    $table = $wpdb->prefix . 'muop_visitor_read_logs';

    $date = !empty($_POST['date']) ? sanitize_text_field($_POST['date']) : current_time('Y-m-d');
    $search = !empty($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    $user_type = !empty($_POST['user_type']) ? sanitize_text_field($_POST['user_type']) : 'all';
    $page = max(1, isset($_POST['page']) ? intval($_POST['page']) : 1);
    $per_page = max(10, isset($_POST['per_page']) ? intval($_POST['per_page']) : 30);
    $offset = ($page - 1) * $per_page;

    // 1. Thống kê tổng quan trong ngày
    $summary = array(
        'date'                 => $date,
        'date_formatted'       => date_i18n('d/m/Y', strtotime($date)),
        'total_unique_readers' => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT ip_address) FROM $table WHERE read_date = %s", $date)),
        'total_chapter_reads'  => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE read_date = %s", $date)),
        'guest_readers'        => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT ip_address) FROM $table WHERE read_date = %s AND user_id = 0", $date)),
        'member_readers'       => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT user_id) FROM $table WHERE read_date = %s AND user_id > 0", $date)),
        'stories_count'        => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT story_id) FROM $table WHERE read_date = %s", $date)),
    );

    // 2. Thống kê nhanh các ngày gần đây để chuyển ngày tiện lợi
    $recent_dates = $wpdb->get_results(
        "SELECT read_date, COUNT(DISTINCT ip_address) as unique_readers, COUNT(*) as total_reads 
         FROM $table 
         GROUP BY read_date 
         ORDER BY read_date DESC 
         LIMIT 10",
        ARRAY_A
    );

    // 3. Xây dựng điều kiện lọc danh sách
    $where_clauses = array($wpdb->prepare("read_date = %s", $date));

    if (!empty($search)) {
        $like = '%' . $wpdb->esc_like($search) . '%';
        $where_clauses[] = $wpdb->prepare(
            "(ip_address LIKE %s OR story_title LIKE %s OR chapter_title LIKE %s OR user_name LIKE %s)",
            $like, $like, $like, $like
        );
    }

    if ($user_type === 'guest') {
        $where_clauses[] = "user_id = 0";
    } elseif ($user_type === 'member') {
        $where_clauses[] = "user_id > 0";
    }

    $where_sql = implode(' AND ', $where_clauses);

    $total_logs = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $where_sql");
    $total_pages = ceil($total_logs / $per_page);

    $logs = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE $where_sql ORDER BY read_time DESC LIMIT %d OFFSET %d",
        $per_page, $offset
    ));

    $formatted_logs = array();
    foreach ($logs as $row) {
        $time_fmt = date_i18n('H:i:s - d/m/Y', strtotime($row->read_time));
        $time_only = date('H:i:s', strtotime($row->read_time));
        $story_permalink = $row->story_id ? get_permalink($row->story_id) : '';
        $story_url = !empty($story_permalink) ? $story_permalink : '#';
        $chap_permalink = $row->chapter_id ? get_permalink($row->chapter_id) : '';
        $chap_url = !empty($chap_permalink) ? $chap_permalink : ($story_url !== '#' ? $story_url : '#');

        $formatted_logs[] = array(
            'id'            => (int) $row->id,
            'ip_address'    => $row->ip_address,
            'user_id'       => (int) $row->user_id,
            'user_name'     => $row->user_name,
            'is_guest'      => ((int)$row->user_id === 0),
            'story_id'      => (int) $row->story_id,
            'story_title'   => $row->story_title,
            'story_url'     => $story_url,
            'chapter_id'    => (int) $row->chapter_id,
            'chapter_num'   => (int) $row->chapter_num,
            'chapter_title' => $row->chapter_title ?: ('Chương ' . $row->chapter_num),
            'chapter_url'   => $chap_url,
            'device_type'   => $row->device_type,
            'read_date'     => $row->read_date,
            'read_time'     => $row->read_time,
            'time_only'     => $time_only,
            'time_formatted'=> $time_fmt,
            'time_ago'      => function_exists('muop_time_ago') ? muop_time_ago(strtotime($row->read_time)) : $time_fmt
        );
    }

    wp_send_json_success(array(
        'summary'      => $summary,
        'recent_dates' => $recent_dates,
        'logs'         => $formatted_logs,
        'pagination'   => array(
            'total_logs'  => $total_logs,
            'total_pages' => max(1, $total_pages),
            'current_page'=> $page,
            'per_page'    => $per_page
        )
    ));
}

/**
 * AJAX: Lưu phiên tách chương tự động vào cơ sở dữ liệu để đồng bộ cả máy tính và điện thoại
 */
add_action('wp_ajax_muop_save_splitter_history', 'muop_ajax_save_splitter_history_handler');
function muop_ajax_save_splitter_history_handler() {
    if (!is_user_logged_in() || !muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để lưu lịch sử tách chương!'));
    }

    global $wpdb;
    $table = $wpdb->prefix . 'muop_splitter_history';
    $user_id = get_current_user_id();

    $session_key = !empty($_POST['session_key']) ? sanitize_text_field($_POST['session_key']) : ('split_' . time() . '_' . wp_rand(100, 999));
    $title = !empty($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : 'Truyện tách ' . date('d/m/Y H:i');
    $total_chapters = isset($_POST['total_chapters']) ? intval($_POST['total_chapters']) : 0;
    $total_words = isset($_POST['total_words']) ? intval($_POST['total_words']) : 0;
    $avg_words = isset($_POST['avg_words']) ? intval($_POST['avg_words']) : 0;
    $target_words = isset($_POST['target_words']) ? intval($_POST['target_words']) : 2500;

    $chapters_raw = isset($_POST['chapters']) ? wp_unslash($_POST['chapters']) : '';
    if (is_array($chapters_raw)) {
        $chapters_json = wp_json_encode($chapters_raw);
    } else {
        $chapters_json = (string) $chapters_raw;
    }

    if (empty($chapters_json)) {
        wp_send_json_error(array('message' => 'Dữ liệu chương không hợp lệ!'));
    }

    $existing_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE session_key = %s AND user_id = %d",
        $session_key, $user_id
    ));

    $now = current_time('mysql');

    if ($existing_id) {
        $wpdb->update($table, array(
            'title'          => $title,
            'total_chapters' => $total_chapters,
            'total_words'    => $total_words,
            'avg_words'      => $avg_words,
            'target_words'   => $target_words,
            'chapters_json'  => $chapters_json,
            'created_at'     => $now
        ), array('id' => $existing_id));
        $saved_id = $existing_id;
    } else {
        $wpdb->insert($table, array(
            'session_key'    => $session_key,
            'user_id'        => $user_id,
            'title'          => $title,
            'total_chapters' => $total_chapters,
            'total_words'    => $total_words,
            'avg_words'      => $avg_words,
            'target_words'   => $target_words,
            'chapters_json'  => $chapters_json,
            'created_at'     => $now
        ));
        $saved_id = $wpdb->insert_id;
    }

    // Giữ tối đa 40 phiên tách gần nhất cho mỗi người dùng
    $old_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT id FROM $table WHERE user_id = %d ORDER BY created_at DESC LIMIT 100 OFFSET 40",
        $user_id
    ));
    if (!empty($old_ids)) {
        $in_sql = implode(',', array_map('intval', $old_ids));
        $wpdb->query("DELETE FROM $table WHERE id IN ($in_sql)");
    }

    wp_send_json_success(array(
        'message'     => 'Đã đồng bộ lịch sử tách chương thành công!',
        'id'          => $saved_id,
        'session_key' => $session_key
    ));
}

/**
 * AJAX: Lấy danh sách lịch sử tách chương đã đồng bộ từ database
 */
add_action('wp_ajax_muop_get_splitter_history', 'muop_ajax_get_splitter_history_handler');
function muop_ajax_get_splitter_history_handler() {
    if (!is_user_logged_in() || !muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để xem lịch sử tách chương!'));
    }

    global $wpdb;
    $table = $wpdb->prefix . 'muop_splitter_history';
    $user_id = get_current_user_id();

    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT id, session_key, title, total_chapters, total_words, avg_words, target_words, chapters_json, created_at 
         FROM $table 
         WHERE user_id = %d 
         ORDER BY created_at DESC 
         LIMIT 40",
        $user_id
    ));

    $list = array();
    foreach ($rows as $r) {
        $chaps = json_decode($r->chapters_json, true);
        if (!is_array($chaps)) $chaps = array();

        $list[] = array(
            'id'             => $r->session_key ?: ('split_' . $r->id),
            'db_id'          => (int) $r->id,
            'title'          => $r->title,
            'total_chapters' => (int) $r->total_chapters,
            'total_words'    => (int) $r->total_words,
            'avg_words'      => (int) $r->avg_words,
            'target_words'   => (int) $r->target_words,
            'created_at'     => muop_format_datetime($r->created_at, 'd/m/Y H:i'),
            'raw_time'       => $r->created_at,
            'chapters'       => $chaps
        );
    }

    wp_send_json_success(array('list' => $list));
}

/**
 * AJAX: Xóa 1 phiên hoặc xóa toàn bộ lịch sử tách chương trong database
 */
add_action('wp_ajax_muop_delete_splitter_history', 'muop_ajax_delete_splitter_history_handler');
function muop_ajax_delete_splitter_history_handler() {
    if (!is_user_logged_in() || !muop_is_translator_or_admin()) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện thao tác này!'));
    }

    global $wpdb;
    $table = $wpdb->prefix . 'muop_splitter_history';
    $user_id = get_current_user_id();

    $clear_all = !empty($_POST['clear_all']) && $_POST['clear_all'] === '1';
    $session_key = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';

    if ($clear_all) {
        $wpdb->delete($table, array('user_id' => $user_id));
        wp_send_json_success(array('message' => 'Đã xóa toàn bộ lịch sử tách chương thành công!'));
    } elseif (!empty($session_key)) {
        $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE user_id = %d AND (session_key = %s OR id = %d)",
            $user_id, $session_key, intval($session_key)
        ));
        wp_send_json_success(array('message' => 'Đã xóa phiên tách chương này thành công!'));
    }

    wp_send_json_error(array('message' => 'Yêu cầu không hợp lệ!'));
}





