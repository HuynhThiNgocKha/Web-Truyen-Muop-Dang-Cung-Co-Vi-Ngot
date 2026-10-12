<?php
/**
 * Theme Functions & Definitions for Mướp Đắng Cũng Có Vị Ngọt
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MUOP_THEME_VERSION', '2.2.30');
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

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_clicks);
    dbDelta($sql_history);
    dbDelta($sql_bookmarks);
    dbDelta($sql_notifs);
    dbDelta($sql_reports);
    dbDelta($sql_payments);

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
    if (current_user_can('administrator') && in_array($preview_mode, array('doc_gia', 'dich_gia', 'admin'))) {
        $effective_role = ($preview_mode === 'admin') ? 'administrator' : $preview_mode;
    } else {
        $effective_role = $primary_role;
    }

    // Chuẩn hóa ajaxUrl luôn là relative path để không bị chặn bởi Chrome Private Network Access (PNA)
    $ajax_url = '/wp-admin/admin-ajax.php';

    wp_localize_script('muop-main-script', 'muopConfig', array(
        'ajaxUrl'       => $ajax_url,
        'nonce'         => wp_create_nonce('muop_ajax_nonce'),
        'siteUrl'       => home_url(),
        'isLoggedIn'    => is_user_logged_in(),
        'userId'        => $current_user->ID,
        'userRole'      => $effective_role,
        'actualRole'    => $primary_role,
        'previewMode'   => $preview_mode,
        'shopeeUrl'     => get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp'),
        'tiktokUrl'     => get_option('muop_tiktok_url', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ'),
        'salaryRate'    => get_option('muop_salary_rate', 8),
        'readingPrefs'  => is_user_logged_in() ? get_user_meta($current_user->ID, 'muop_reading_preferences', true) : null
    ));
}
add_action('wp_enqueue_scripts', 'muop_enqueue_scripts');

// 7. Helper Functions
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
 * Lấy thời gian cập nhật thực tế chuẩn múi giờ Việt Nam (GMT+7)
 * Tự động đồng bộ giữa thời gian sửa đổi của truyện và thời gian đăng/sửa của chương mới nhất
 *
 * @param int $story_id ID bộ truyện
 * @return array Mảng thông tin: timestamp, formatted (d/m/Y H:i), time_ago, display
 */
function muop_get_story_last_updated($story_id) {
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

    // 1. Timestamp sửa đổi của chính truyện
    $story_mod_ts = (int) get_post_timestamp($story_id, 'modified');
    if (!$story_mod_ts) {
        $story_mod_ts = (int) get_post_timestamp($story_id, 'date');
    }

    // 2. Timestamp của chương mới nhất thuộc truyện
    $latest_chap_ts = 0;
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

    return array(
        'timestamp' => $last_timestamp,
        'formatted' => $formatted,
        'time_ago'  => $time_ago,
        'display'   => $display
    );
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
    return get_posts(array(
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
}

function muop_get_effective_user_role() {
    if (!is_user_logged_in()) {
        return 'guest';
    }
    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $primary_role = !empty($roles) ? $roles[0] : 'guest';
    
    if (current_user_can('administrator')) {
        $preview_mode = isset($_COOKIE['muop_preview_mode']) ? sanitize_text_field($_COOKIE['muop_preview_mode']) : '';
        if ($preview_mode === 'doc_gia') return 'doc_gia';
        if ($preview_mode === 'dich_gia') return 'dich_gia';
        return 'administrator';
    }
    return $primary_role;
}

// 8. AJAX Handlers

// 8.1 Login
add_action('wp_ajax_nopriv_muop_login', 'muop_ajax_login_handler');
add_action('wp_ajax_muop_login', 'muop_ajax_login_handler');
function muop_ajax_login_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    $user_login = sanitize_text_field($_POST['log']);
    $user_pass  = $_POST['pwd'];
    $remember   = !empty($_POST['remember']);

    $creds = array(
        'user_login'    => $user_login,
        'user_password' => $user_pass,
        'remember'      => $remember
    );

    $user = wp_signon($creds, is_ssl());

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

// 8.2 Register (Doc Gia)
add_action('wp_ajax_nopriv_muop_register', 'muop_ajax_register_handler');
add_action('wp_ajax_muop_register', 'muop_ajax_register_handler');
function muop_ajax_register_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
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

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }

    // Set display name and doc_gia role
    wp_update_user(array(
        'ID'           => $user_id,
        'display_name' => $name,
        'nickname'     => $name,
        'role'         => 'doc_gia'
    ));

    // Auto login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

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

    wp_send_json_success(array('views' => $new_views));
}

// 8.5 Toggle Bookmark (Tủ truyện)
add_action('wp_ajax_muop_toggle_bookmark', 'muop_ajax_toggle_bookmark_handler');
add_action('wp_ajax_nopriv_muop_toggle_bookmark', 'muop_ajax_toggle_bookmark_handler');
function muop_ajax_toggle_bookmark_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
            if ($parent_comment && $parent_comment->user_id && (int) $parent_comment->user_id !== (int) $user->ID) {
                muop_add_notification(array(
                    'user_id'   => (int) $parent_comment->user_id,
                    'sender_id' => $user->ID,
                    'type'      => 'comment_reply',
                    'title'     => $user->display_name . ' đã trả lời bình luận của bạn',
                    'message'   => wp_trim_words($comment, 20),
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
                muop_add_notification(array(
                    'user_id'   => (int) $story_post->post_author,
                    'sender_id' => $user->ID,
                    'type'      => 'story_comment',
                    'title'     => 'Bình luận mới: ' . $story_post->post_title,
                    'message'   => $user->display_name . ': "' . wp_trim_words($comment, 20) . '"',
                    'link'      => get_permalink($post_id) . '#comment-' . $comment_id
                ));
            }
        }

        wp_send_json_success(array(
            'message'    => 'Bình luận thành công!',
            'comment_id' => $comment_id,
            'author'     => $user->display_name,
            'time'       => 'Vừa xong',
            'content'    => esc_html($comment)
        ));
    }
    wp_send_json_error(array('message' => 'Không thể gửi bình luận!'));
}

// 8.8 Submit Story (Front-end for Translators / Admin)
add_action('wp_ajax_muop_submit_story', 'muop_ajax_submit_story_handler');
add_action('wp_ajax_nopriv_muop_submit_story', 'muop_ajax_submit_story_handler');
function muop_ajax_submit_story_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện chức năng này!'));
    }
    if (!current_user_can('dich_gia') && !current_user_can('administrator')) {
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
    $is_admin = current_user_can('administrator');
    if ($is_draft) {
        $post_status = 'draft';
    } else {
        $post_status = $is_admin ? 'publish' : 'pending'; // Translator posts need admin moderation!
    }

    $post_data = array(
        'post_title'   => $title,
        'post_content' => $description,
        'post_status'  => $post_status,
        'post_type'    => 'truyen',
        'post_author'  => get_current_user_id()
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

    if ($is_draft) {
        $success_msg = 'Đã lưu bản nháp truyện thành công! Bạn có thể xem và chỉnh sửa trong mục Lưu Nháp.';
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
        'redirect' => current_user_can('administrator') ? home_url('/quan-ly-admin/') : home_url('/thong-tin-dich-gia/')
    ));
}

// 8.9 Submit Chapter (Front-end for Translators / Admin)
add_action('wp_ajax_muop_submit_chapter', 'muop_ajax_submit_chapter_handler');
add_action('wp_ajax_nopriv_muop_submit_chapter', 'muop_ajax_submit_chapter_handler');
function muop_ajax_submit_chapter_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thêm chương mới!'));
    }
    if (!current_user_can('dich_gia') && !current_user_can('administrator')) {
        wp_send_json_error(array('message' => 'Tài khoản của bạn cần có quyền Dịch Giả hoặc Quản Trị Viên để thêm chương!'));
    }

    $story_id    = intval($_POST['story_id']);
    $chap_num    = intval($_POST['chapter_number']);
    $chap_title  = sanitize_text_field($_POST['chapter_title']);
    $content     = wp_kses_post($_POST['content']);

    if (!$story_id || empty($chap_title) || empty($content)) {
        wp_send_json_error(array('message' => 'Vui lòng điền đầy đủ tên chương và nội dung chương!'));
    }

    $story = get_post($story_id);
    if (!$story) {
        wp_send_json_error(array('message' => 'Truyện không tồn tại!'));
    }

    // Translators can only add chapters to their own stories unless admin
    if (!current_user_can('administrator') && $story->post_author != get_current_user_id()) {
        wp_send_json_error(array('message' => 'Bạn chỉ có thể thêm chương vào truyện của mình!'));
    }

    $clean_title = muop_get_clean_chapter_title($chap_title);
    if (empty($clean_title)) {
        $clean_title = 'Chương ' . $chap_num;
    }
    $is_draft = (!empty($_POST['is_draft']) && $_POST['is_draft'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'draft');
    $post_status = $is_draft ? 'draft' : 'publish';

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

    // Update story modified time
    wp_update_post(array('ID' => $story_id));

    // Bắn thông báo ra chương mới cho độc giả theo dõi truyện này
    if (!$is_draft) {
        muop_notify_story_new_chapter($story_id, $chapter_id, $chap_num, $clean_title);
    }

    $success_msg = $is_draft ? 'Đã lưu bản nháp chương truyện thành công!' : 'Thêm chương mới thành công!';

    wp_send_json_success(array(
        'message'    => $success_msg,
        'chapter_id' => $chapter_id,
        'redirect'   => get_permalink($story_id)
    ));
}

// 8.10 Admin Quick Actions
add_action('wp_ajax_muop_admin_action', 'muop_ajax_admin_action_handler');
add_action('wp_ajax_nopriv_muop_admin_action', 'muop_ajax_admin_action_handler');
function muop_ajax_admin_action_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!current_user_can('administrator')) {
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
            $shopee_url = esc_url_raw($_POST['shopee_url']);
            $tiktok_url = esc_url_raw($_POST['tiktok_url']);
            if ($shopee_url) update_option('muop_shopee_url', $shopee_url);
            if ($tiktok_url) update_option('muop_tiktok_url', $tiktok_url);
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
            if (in_array($new_role, array('doc_gia', 'dich_gia', 'administrator'))) {
                $u = new WP_User($target_user_id);
                $u->set_role($new_role);
                wp_send_json_success(array('message' => 'Đã thay đổi vai trò người dùng thành ' . $new_role));
            }
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
            $new_uid = wp_create_user($username, $u_pass, $u_email);
            if (is_wp_error($new_uid)) {
                wp_send_json_error(array('message' => $new_uid->get_error_message()));
            }
            $u = new WP_User($new_new = $new_uid);
            $u->set_role($u_role);
            wp_update_user(array('ID' => $new_uid, 'display_name' => $u_name));
            wp_send_json_success(array('message' => 'Thêm người dùng mới thành công!'));
            break;

        default:
            wp_send_json_error(array('message' => 'Thao tác không hợp lệ!'));
    }
}

// 8.11 Switch Mode Preview (Admin Mode Switcher)
add_action('wp_ajax_muop_switch_preview_mode', 'muop_ajax_switch_preview_mode_handler');
add_action('wp_ajax_nopriv_muop_switch_preview_mode', 'muop_ajax_switch_preview_mode_handler');
function muop_ajax_switch_preview_mode_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!current_user_can('administrator')) {
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
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!current_user_can('administrator') && $story->post_author != $current_user_id) {
        wp_send_json_error(array('message' => 'Bạn chỉ có quyền xóa truyện do chính mình đăng!'));
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
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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

    wp_send_json_success(array('message' => 'Đổi mật khẩu thành công! Mật khẩu mới của bạn đã có hiệu lực.'));
}

// 8.15 Update Story Status (Đổi trạng thái truyện: Đang ra / Hoàn thành - dành cho Dịch giả & Admin)
add_action('wp_ajax_muop_update_story_status', 'muop_ajax_update_story_status_handler');
add_action('wp_ajax_nopriv_muop_update_story_status', 'muop_ajax_update_story_status_handler');
function muop_ajax_update_story_status_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    $is_admin = current_user_can('administrator');
    $is_author = ((int)$post->post_author === $current_user_id);

    if (!$is_admin && !$is_author) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thay đổi trạng thái của bộ truyện này!'));
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
    check_ajax_referer('muop_ajax_nonce', 'nonce');
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
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
function muop_ajax_create_genre_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập để thực hiện chức năng này!'));
    }
    if (!current_user_can('dich_gia') && !current_user_can('administrator')) {
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
function muop_ajax_update_genre_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!current_user_can('administrator')) {
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
function muop_ajax_delete_genre_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!'));
    }
    if (!current_user_can('administrator')) {
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

// 16. AJAX: Get Story Data (Lấy dữ liệu truyện để nạp vào Popup Chỉnh Sửa)
add_action('wp_ajax_muop_get_story_data', 'muop_ajax_get_story_data_handler');
function muop_ajax_get_story_data_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && $story->post_author != $current_user_id) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa bộ truyện này!'));
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

    wp_send_json_success(array(
        'id'          => $story->ID,
        'title'       => $story->post_title,
        'author_name' => get_post_meta($story->ID, '_truyen_author_name', true) ?: '',
        'team_name'   => get_post_meta($story->ID, '_truyen_team', true) ?: '',
        'status'      => get_post_meta($story->ID, '_truyen_status', true) ?: 'dang_ra',
        'post_status' => $story->post_status,
        'categories'  => $term_ids,
        'description' => $story->post_content,
        'thumbnail'   => $thumb_url ?: '',
        'chapters'    => $chapters_list
    ));
}

// 17. AJAX: Get Chapter Data (Lấy chi tiết 1 chương để nạp vào form sửa chương)
add_action('wp_ajax_muop_get_chapter_data', 'muop_ajax_get_chapter_data_handler');
function muop_ajax_get_chapter_data_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    $story = get_post($story_id);
    $current_user_id = get_current_user_id();
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && ($chapter->post_author != $current_user_id && (!$story || $story->post_author != $current_user_id))) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa chương này!'));
    }

    wp_send_json_success(array(
        'id'             => $chapter->ID,
        'story_id'       => $story_id,
        'chapter_number' => get_post_meta($chapter->ID, '_chuong_number', true) ?: 1,
        'title'          => $chapter->post_title,
        'content'        => $chapter->post_content,
        'status'         => $chapter->post_status
    ));
}

// 18. AJAX: Update Story (Cập nhật thông tin truyện / Lưu nháp truyện)
add_action('wp_ajax_muop_update_story', 'muop_ajax_update_story_handler');
function muop_ajax_update_story_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && $story->post_author != $current_user_id) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa truyện này!'));
    }

    $title       = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    $author_name = isset($_POST['author_name']) ? sanitize_text_field($_POST['author_name']) : '';
    $team_name   = isset($_POST['team_name']) ? sanitize_text_field($_POST['team_name']) : '';
    $status      = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'dang_ra';
    $categories  = isset($_POST['categories']) ? array_map('intval', (array) $_POST['categories']) : array();
    $description = isset($_POST['description']) ? wp_kses_post($_POST['description']) : '';
    $is_draft    = (!empty($_POST['is_draft']) && $_POST['is_draft'] === '1') || (isset($_POST['post_status']) && $_POST['post_status'] === 'draft');

    if (empty($title)) {
        wp_send_json_error(array('message' => 'Vui lòng nhập tên truyện!'));
    }

    $is_admin = current_user_can('administrator');
    if ($is_draft) {
        $post_status = 'draft';
    } else {
        $post_status = $is_admin ? 'publish' : 'pending';
    }

    $updated_post = array(
        'ID'           => $story_id,
        'post_title'   => $title,
        'post_content' => $description,
        'post_status'  => $post_status
    );

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
function muop_ajax_update_chapter_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    $story = get_post($story_id);
    $current_user_id = get_current_user_id();
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && ($chapter->post_author != $current_user_id && (!$story || $story->post_author != $current_user_id))) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa chương này!'));
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
function muop_ajax_delete_chapter_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    $story = get_post($story_id);
    $current_user_id = get_current_user_id();

    // Kiểm tra quyền: Phải là Admin hoặc chính Dịch giả của chương/truyện
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && 
        ($chapter->post_author != $current_user_id && (!$story || $story->post_author != $current_user_id))) {
        wp_send_json_error(array('message' => 'Bạn không có quyền xóa chương này!'));
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
function muop_ajax_publish_draft_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!current_user_can('administrator') && $story->post_author != $current_user_id) {
        wp_send_json_error(array('message' => 'Bạn không có quyền đăng bộ truyện này!'));
    }

    $is_admin = current_user_can('administrator');
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

// 21. AJAX: Get Preset Covers (Lấy danh sách ảnh bìa có sẵn: chỉ hiện ảnh sẵn của hệ thống và ảnh của dịch giả đó, không hiện ảnh của dịch giả khác)
add_action('wp_ajax_muop_get_preset_covers', 'muop_ajax_get_preset_covers_handler');
function muop_ajax_get_preset_covers_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!is_user_logged_in() || (!current_user_can('administrator') && !current_user_can('dich_gia'))) {
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
            $translator_id = current_user_can('administrator') ? (int)$story->post_author : $current_user_id;
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
function muop_ajax_update_story_cover_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && $story->post_author != $current_user_id) {
        wp_send_json_error(array('message' => 'Bạn không có quyền thay đổi ảnh bìa truyện này!'));
    }

    // 1. Nếu người dùng tải ảnh từ thiết bị
    if (!empty($_FILES['cover_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('cover_file', $story_id);
        if (is_wp_error($attachment_id)) {
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
        $att_id = attachment_url_to_postid($cover_url);
        if ($att_id > 0) {
            set_post_thumbnail($story_id, $att_id);
        }
    } else {
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

    wp_send_json_success(array(
        'message'   => 'Đã thay đổi ảnh bìa truyện thành công!',
        'cover_url' => $new_cover_url,
        'updated'   => muop_get_story_last_updated($story_id)
    ));
}

// 23. AJAX: Update Story Title (Chỉnh sửa tên truyện nhanh)
add_action('wp_ajax_muop_update_story_title', 'muop_ajax_update_story_title_handler');
function muop_ajax_update_story_title_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    if (!current_user_can('administrator') && !current_user_can('dich_gia') && $story->post_author != $current_user_id) {
        wp_send_json_error(array('message' => 'Bạn không có quyền chỉnh sửa tên truyện này!'));
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
function muop_ajax_update_team_info_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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

    if (current_user_can('administrator')) {
        $can_edit = true;
    } elseif (current_user_can('dich_gia')) {
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
        'avatar_url'   => $team_avatar_url,
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
 * Bắn thông báo ra chương mới cho độc giả theo dõi truyện (Bookmark)
 */
function muop_notify_story_new_chapter($story_id, $chapter_id, $chap_num, $chap_title) {
    global $wpdb;
    $story = get_post($story_id);
    if (!$story) return;

    $table_bookmarks = $wpdb->prefix . 'reading_bookmarks';
    $bookmarked_users = $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT user_id FROM $table_bookmarks WHERE story_id = %d AND user_id != %d",
        $story_id, get_current_user_id()
    ));

    if (!empty($bookmarked_users)) {
        foreach ($bookmarked_users as $uid) {
            muop_add_notification(array(
                'user_id'   => (int) $uid,
                'sender_id' => get_current_user_id(),
                'type'      => 'new_chapter',
                'title'     => 'Chương mới: ' . $story->post_title,
                'message'   => 'Chương ' . $chap_num . ' (' . $chap_title . ') vừa được phát hành.',
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
 * Bắn thông báo cho team dịch khi có độc giả yêu thích team
 */
function muop_notify_team_favorited($team_id, $fan_user_id) {
    $term = get_term($team_id, 'team_dich');
    if (!$term || is_wp_error($term)) return;

    $stories = get_posts(array(
        'post_type'      => 'truyen',
        'posts_per_page' => 1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'team_dich',
                'field'    => 'term_id',
                'terms'    => $team_id
            )
        )
    ));
    if (!empty($stories) && (int) $stories[0]->post_author !== (int) $fan_user_id) {
        $fan = get_userdata($fan_user_id);
        $fan_name = $fan ? $fan->display_name : 'Một độc giả';
        muop_add_notification(array(
            'user_id'   => (int) $stories[0]->post_author,
            'sender_id' => (int) $fan_user_id,
            'type'      => 'team_favorite',
            'title'     => 'Có người hâm mộ yêu thích team của bạn!',
            'message'   => $fan_name . ' vừa thêm team dịch "' . $term->name . '" vào danh sách yêu thích.',
            'link'      => get_term_link($term)
        ));
    }
}

// AJAX: Lấy danh sách thông báo của user
add_action('wp_ajax_muop_get_notifications', 'muop_ajax_get_notifications_handler');
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
function muop_ajax_mark_all_notifs_read_handler() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Vui lòng đăng nhập.'));
    }
    muop_mark_all_notifications_as_read(get_current_user_id());
    wp_send_json_success(array('unread_count' => 0));
}

// AJAX: Gửi báo lỗi chương truyện (Yêu cầu đăng nhập)
add_action('wp_ajax_muop_submit_chapter_report', 'muop_ajax_submit_chapter_report_handler');
function muop_ajax_submit_chapter_report_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
function muop_ajax_get_report_details_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    $is_admin = current_user_can('administrator');
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
function muop_ajax_submit_report_reply_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
    $is_admin = current_user_can('administrator');
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
function muop_ajax_save_translator_bank_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
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
function muop_admin_pay_salary_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!current_user_can('administrator')) {
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
function muop_ajax_admin_reply_report_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!current_user_can('administrator')) {
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
function muop_ajax_admin_send_broadcast_handler() {
    if (!check_ajax_referer('muop_ajax_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Phiên làm việc đã hết hạn.'));
    }
    if (!current_user_can('administrator')) {
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
