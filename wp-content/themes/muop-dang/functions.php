<?php
/**
 * Theme Functions & Definitions for Mướp Đắng Cũng Có Vị Ngọt
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MUOP_THEME_VERSION', '2.0.0');
define('MUOP_THEME_DIR', get_template_directory());
define('MUOP_THEME_URI', get_template_directory_uri());

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

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_clicks);
    dbDelta($sql_history);
    dbDelta($sql_bookmarks);

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
    wp_enqueue_style('muop-google-fonts', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap', array(), null);
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

    wp_localize_script('muop-main-script', 'muopConfig', array(
        'ajaxUrl'       => admin_url('admin-ajax.php'),
        'nonce'         => wp_create_nonce('muop_ajax_nonce'),
        'siteUrl'       => home_url(),
        'isLoggedIn'    => is_user_logged_in(),
        'userId'        => $current_user->ID,
        'userRole'      => $effective_role,
        'actualRole'    => $primary_role,
        'previewMode'   => $preview_mode,
        'shopeeUrl'     => get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp'),
        'tiktokUrl'     => get_option('muop_tiktok_url', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ'),
        'salaryRate'    => get_option('muop_salary_rate', 8)
    ));
}
add_action('wp_enqueue_scripts', 'muop_enqueue_scripts');

// 7. Helper Functions
function muop_get_story_views($story_id) {
    return (int) get_post_meta($story_id, '_truyen_views', true);
}

function muop_get_story_status($story_id) {
    $st = get_post_meta($story_id, '_truyen_status', true);
    return ($st === 'hoan_thanh') ? 'Hoàn thành' : 'Đang ra';
}

function muop_get_story_chapters($story_id, $order = 'ASC') {
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
        'post_status'    => array('publish')
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
    $redirect = home_url();
    if (in_array('administrator', $roles)) {
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

    $is_admin = current_user_can('administrator');
    $post_status = $is_admin ? 'publish' : 'pending'; // Translator posts need admin moderation!

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
    if (!empty($categories)) {
        wp_set_object_terms($story_id, $categories, 'the_loai');
    }
    if (!empty($author_name)) {
        wp_set_object_terms($story_id, array($author_name), 'tac_gia');
    }
    if (!empty($team_name)) {
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

    $success_msg = $is_admin 
        ? 'Đăng truyện thành công!' 
        : 'Đăng truyện thành công! Bài đăng đang chờ Quản trị viên duyệt.';

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

    $full_title = 'Chương ' . $chap_num . ': ' . $chap_title;
    $is_admin = current_user_can('administrator');
    $post_status = $is_admin ? 'publish' : 'publish'; // Chapters published or moderation

    $chapter_id = wp_insert_post(array(
        'post_title'   => $full_title,
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

    wp_send_json_success(array(
        'message'    => 'Thêm chương mới thành công!',
        'chapter_id' => $chapter_id,
        'redirect'   => get_permalink($story_id)
    ));
}

// 8.10 Admin Quick Actions
add_action('wp_ajax_muop_admin_action', 'muop_ajax_admin_action_handler');
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
            wp_send_json_success(array('message' => 'Đã duyệt truyện thành công!'));
            break;

        case 'reject_story':
            $story_id = intval($_POST['story_id']);
            wp_update_post(array('ID' => $story_id, 'post_status' => 'draft'));
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
function muop_ajax_switch_preview_mode_handler() {
    check_ajax_referer('muop_ajax_nonce', 'nonce');
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
