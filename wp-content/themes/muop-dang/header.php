<?php
/**
 * Header Template for Mướp Đắng Cũng Có Vị Ngọt
 */
if (!defined('ABSPATH')) exit;

// 1. Luôn bảo đảm hằng số phiên bản theme tồn tại
if (!defined('MUOP_THEME_VERSION')) {
    define('MUOP_THEME_VERSION', '2.5.0');
}

// 2. Cưỡng chế nạp functions.php cùng thư mục nếu chưa nạp
$functions_file = __DIR__ . '/functions.php';
if (file_exists($functions_file) && !function_exists('muop_setup_theme')) {
    require_once $functions_file;
}

// Fallback an toàn phòng khi functions.php chưa nạp kịp trên hosting
if (!function_exists('muop_get_effective_user_role')) {
    function muop_get_effective_user_role() {
        if (!is_user_logged_in()) return 'guest';
        $user = wp_get_current_user();
        $roles = (array) $user->roles;
        return !empty($roles) ? $roles[0] : 'guest';
    }
}
if (!function_exists('muop_is_admin')) {
    function muop_is_admin($user_id = 0) {
        if (!$user_id) $user_id = get_current_user_id();
        return user_can($user_id, 'manage_options');
    }
}
if (!function_exists('muop_get_unread_notification_count')) {
    function muop_get_unread_notification_count($user_id = 0) {
        return 0;
    }
}
if (!function_exists('muop_user_is_translator')) {
    function muop_user_is_translator($user_id = 0) {
        if (!$user_id) $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        return $user && (in_array('dich_gia', (array)$user->roles) || in_array('administrator', (array)$user->roles));
    }
}
if (!function_exists('muop_format_salary')) {
    function muop_format_salary($val) {
        return number_format((float)$val, 0, ',', '.') . ' đ';
    }
}
if (!function_exists('muop_get_story_views')) {
    function muop_get_story_views($id) {
        return (int) get_post_meta($id, '_truyen_views', true);
    }
}
if (!function_exists('muop_get_story_last_updated')) {
    function muop_get_story_last_updated($id) {
        return array('display' => get_the_modified_date('d/m/Y', $id), 'timestamp' => get_post_modified_time('U', false, $id));
    }
}
if (!function_exists('muop_get_story_chapters')) {
    function muop_get_story_chapters($id, $order = 'ASC') {
        return get_posts(array(
            'post_type' => 'chuong',
            'meta_key' => '_chuong_truyen_id',
            'meta_value' => $id,
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => $order
        ));
    }
}
if (!function_exists('muop_format_datetime')) {
    function muop_format_datetime($str = '') {
        if (empty($str)) return '';
        $ts = is_numeric($str) ? (int)$str : strtotime($str);
        return $ts ? date('H:i - d/m/Y', $ts) : (string)$str;
    }
}
if (!function_exists('muop_can_user_manage_story_frontend')) {
    function muop_can_user_manage_story_frontend($sid, $uid) {
        return false;
    }
}
if (!function_exists('muop_is_user_following_story')) {
    function muop_is_user_following_story($uid, $sid) {
        return false;
    }
}
if (!function_exists('muop_is_user_favorited_story')) {
    function muop_is_user_favorited_story($uid, $sid) {
        return false;
    }
}
if (!function_exists('muop_is_chapter_checkpoint')) {
    function muop_is_chapter_checkpoint($chap_num, $total_chapters) {
        $chap_num = (int)$chap_num;
        $total_chapters = (int)$total_chapters;
        if ($total_chapters <= 4) return ($chap_num === 1 || $chap_num === 3);
        return ($chap_num >= 2 && ($chap_num % 3 === 2));
    }
}

$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
$user_role    = muop_get_effective_user_role();
$preview_mode = isset($_COOKIE['muop_preview_mode']) ? sanitize_text_field($_COOKIE['muop_preview_mode']) : '';

// Get all genres for desktop and mobile navigation (cached for speed)
$genres = wp_cache_get('muop_all_genres_list');
if ($genres === false) {
    $genres = get_terms(array(
        'taxonomy'   => 'the_loai',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC'
    ));
    wp_cache_set('muop_all_genres_list', $genres, '', 3600);
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
      // Suppress unhandled third-party browser extension errors (e.g. content.js)
      window.addEventListener('error', function(e) {
        if (e && e.filename && (e.filename.indexOf('content.js') !== -1 || e.filename.indexOf('extension') !== -1)) {
          e.preventDefault && e.preventDefault();
          e.stopImmediatePropagation && e.stopImmediatePropagation();
          return true;
        }
      }, true);
      window.addEventListener('unhandledrejection', function(e) {
        if (e && e.reason && e.reason.stack && (e.reason.stack.indexOf('content.js') !== -1 || e.reason.stack.indexOf('extension') !== -1)) {
          e.preventDefault && e.preventDefault();
        }
      });
    </script>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>">
    <?php
    // Cấu hình Open Graph & Social Share Meta Tags (Facebook, Zalo, Twitter, Telegram)
    $og_site_name = get_bloginfo('name');
    $og_type = 'website';
    $og_title = wp_get_document_title();
    $og_desc = get_bloginfo('description');
    $og_url = home_url(add_query_arg(array(), $GLOBALS['wp']->request ?? ''));
    $og_image = get_template_directory_uri() . '/assets/images/logo-icon.png';
    $og_image_w = 600;
    $og_image_h = 600;
    $og_image_alt = $og_site_name;

    if (is_singular('truyen')) {
        $st_id = get_the_ID();
        $og_type = 'book';
        $og_title = get_the_title($st_id) . ' — ' . $og_site_name;
        $raw_desc = get_post_field('post_content', $st_id);
        if (!empty($raw_desc)) {
            $og_desc = wp_trim_words(wp_strip_all_tags($raw_desc), 40, '...');
        }
        $og_url = get_permalink($st_id);

        $thumb_id = get_post_thumbnail_id($st_id);
        if ($thumb_id) {
            $thumb_data = wp_get_attachment_image_src($thumb_id, 'full');
            if ($thumb_data) {
                $og_image = $thumb_data[0];
                $og_image_w = $thumb_data[1] ?: 600;
                $og_image_h = $thumb_data[2] ?: 800;
            }
        }
        $og_image_alt = 'Ảnh bìa truyện ' . get_the_title($st_id);
    } elseif (is_singular('chuong')) {
        $ch_id = get_the_ID();
        $st_id = (int) get_post_meta($ch_id, '_chuong_story_id', true);
        if (!$st_id) {
            $st_id = (int) wp_get_post_parent_id($ch_id);
        }
        $og_type = 'article';
        $st_title = $st_id ? get_the_title($st_id) : '';
        $og_title = get_the_title($ch_id) . ($st_title ? ' — ' . $st_title : '') . ' | ' . $og_site_name;
        $raw_desc = get_post_field('post_content', $ch_id);
        if (!empty($raw_desc)) {
            $og_desc = wp_trim_words(wp_strip_all_tags($raw_desc), 40, '...');
        }
        $og_url = get_permalink($ch_id);

        if ($st_id) {
            $thumb_id = get_post_thumbnail_id($st_id);
            if ($thumb_id) {
                $thumb_data = wp_get_attachment_image_src($thumb_id, 'full');
                if ($thumb_data) {
                    $og_image = $thumb_data[0];
                    $og_image_w = $thumb_data[1] ?: 600;
                    $og_image_h = $thumb_data[2] ?: 800;
                }
            }
            $og_image_alt = 'Ảnh bìa truyện ' . $st_title;
        }
    } elseif (is_tax('team_dich')) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            $og_title = 'Team dịch ' . $term->name . ' — ' . $og_site_name;
            if (!empty($term->description)) {
                $og_desc = wp_trim_words(wp_strip_all_tags($term->description), 35, '...');
            }
            $og_url = get_term_link($term);
            $team_avatar_id = (int) get_term_meta($term->term_id, '_team_avatar_id', true);
            if ($team_avatar_id) {
                $avatar_url = wp_get_attachment_url($team_avatar_id);
                if ($avatar_url) $og_image = $avatar_url;
            }
        }
    }
    ?>
    <!-- Open Graph Meta Tags -->
    <meta property="og:site_name" content="<?php echo esc_attr($og_site_name); ?>">
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
    <meta property="og:title" content="<?php echo esc_attr($og_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($og_desc); ?>">
    <meta property="og:url" content="<?php echo esc_url($og_url); ?>">
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:secure_url" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:width" content="<?php echo esc_attr($og_image_w); ?>">
    <meta property="og:image:height" content="<?php echo esc_attr($og_image_h); ?>">
    <meta property="og:image:alt" content="<?php echo esc_attr($og_image_alt); ?>">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($og_desc); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<noscript>
    <div style="position: sticky; top: 0; left: 0; right: 0; z-index: 999999; background: #dc2626; color: #ffffff; padding: 12px 18px; text-align: center; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> Trình duyệt của bạn đang tắt JavaScript. Vui lòng bật lại JavaScript trong Cài đặt trình duyệt (Chrome / Safari) để sử dụng trang web!
    </div>
</noscript>

<?php if (!is_page_template('templates/page-quan-ly-admin.php')) : ?>
<header class="site-header">
    <!-- HEADER 1 (CỐ ĐỊNH) -->
    <div class="header-top">
        <div class="container header-top-inner">
            <!-- Mobile Menu Hamburger Button -->
            <button type="button" class="mobile-menu-btn" id="mobileMenuToggle" aria-label="Mở menu điều hướng">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Logo & Web Name -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-wrapper" title="<?php bloginfo('name'); ?>">
                <div class="brand-logo brand-avatar">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>" alt="Logo Mướp Đắng" class="brand-icon-img" />
                </div>
                <div class="brand-text">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" alt="<?php bloginfo('name'); ?>" class="brand-title-img" />
                    <h1 class="sr-only"><?php bloginfo('name'); ?></h1>
                </div>
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="search-form-wrapper">
                <form role="search" method="get" class="search-box" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="hidden" name="post_type" value="truyen" />
                    <input type="search" placeholder="Tìm kiếm tên truyện, tác giả, dịch giả..." value="<?php echo get_search_query(); ?>" name="s" required autocomplete="off" />
                    <button type="submit" title="Tìm kiếm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <!-- Controls: Dark/Light Mode & Auth / Profile -->
            <div class="header-actions">
                <!-- Theme Toggle Button -->
                <button type="button" class="theme-toggle-btn" title="Chuyển chế độ Sáng / Tối">
                    <i class="fa-solid fa-moon"></i>
                </button>


                <!-- Chuông Thông Báo (Chỉ hiển thị khi đã đăng nhập theo yêu cầu Hình 1) -->
                <?php if ($is_logged_in) : 
                    $unread_notifs_count = muop_get_unread_notification_count($current_user->ID);
                ?>
                    <div class="notif-wrapper" id="notifWrapper">
                        <button type="button" class="notif-bell-btn" id="notifToggleBtn" title="Thông báo" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            <span class="notif-badge" id="notifBadgeCount" style="<?php echo ($unread_notifs_count > 0) ? '' : 'display: none;'; ?>">
                                <?php echo ($unread_notifs_count > 99) ? '99+' : $unread_notifs_count; ?>
                            </span>
                        </button>

                        <!-- Panel Dropdown Thông Báo -->
                        <div class="notif-dropdown-panel" id="notifDropdownPanel" aria-hidden="true">
                            <div class="notif-header">
                                <div class="notif-header-title">
                                    <i class="fa-solid fa-bell"></i> <span>Thông Báo</span>
                                    <span class="notif-unread-count-pill" id="notifUnreadCountPill"><?php echo $unread_notifs_count; ?> chưa đọc</span>
                                </div>
                                <button type="button" class="notif-mark-all-btn" id="notifMarkAllReadBtn" title="Đánh dấu tất cả đã đọc">
                                    <i class="fa-solid fa-check-double"></i> Đã đọc tất cả
                                </button>
                            </div>
                            <div class="notif-body" id="notifListContainer">
                                <div class="notif-loading"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải thông báo...</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- User Account / Login / Register -->
                <?php if ($is_logged_in) : 
                    $user_custom_avatar = get_user_meta($current_user->ID, 'muop_user_avatar', true);
                    $user_custom_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($user_custom_avatar) : $user_custom_avatar;
                ?>
                    <div class="user-menu-wrapper" id="userMenuWrapper">
                        <div class="user-profile-badge" id="userProfileToggle" role="button" tabindex="0" onclick="" aria-haspopup="true" aria-expanded="false" title="Tài khoản cá nhân">
                            <div class="user-avatar-mini">
                                <?php if (!empty($user_custom_avatar)) : ?>
                                    <img src="<?php echo esc_url($user_custom_avatar); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="user-avatar-mini-img" />
                                <?php else : ?>
                                    <?php echo esc_html(strtoupper(substr($current_user->display_name, 0, 1))); ?>
                                <?php endif; ?>
                            </div>
                            <span class="user-name-text"><?php echo esc_html($current_user->display_name); ?></span>
                            <i class="fa-solid fa-chevron-down caret-icon" style="font-size: 10px; color: var(--text-muted); transition: transform 0.2s;"></i>
                        </div>
                        <div class="user-dropdown-panel" id="userDropdownPanel">
                            <div class="user-dropdown-header">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 2px;">
                                    <div class="user-avatar-mini" style="width: 32px; height: 32px;">
                                        <?php if (!empty($user_custom_avatar)) : ?>
                                            <img src="<?php echo esc_url($user_custom_avatar); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="user-avatar-mini-img" />
                                        <?php else : ?>
                                            <?php echo esc_html(strtoupper(substr($current_user->display_name, 0, 1))); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong class="user-dropdown-name" style="display: block; line-height: 1.2;"><?php echo esc_html($current_user->display_name); ?></strong>
                                        <span class="user-dropdown-role">
                                            <?php 
                                                if (current_user_can('administrator')) echo 'Quản Trị Viên';
                                                elseif (in_array('dich_gia', (array)$current_user->roles)) echo 'Dịch Giả';
                                                else echo 'Độc Giả';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="<?php echo esc_url(home_url('/ho-so/')); ?>">
                                <i class="fa-solid fa-id-card"></i> <span>Thông Tin</span>
                            </a>

                            <?php if (muop_user_is_translator($current_user->ID)) : ?>
                                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">
                                    <i class="fa-solid fa-feather-pointed"></i> <span>Kênh Dịch Giả</span>
                                </a>
                            <?php endif; ?>

                            <?php if (muop_user_is_translator($current_user->ID) || current_user_can('administrator')) : ?>
                                <a href="<?php echo esc_url(muop_get_user_team_url($current_user->ID)); ?>">
                                    <i class="fa-solid fa-users"></i> <span>Kênh Team Dịch</span>
                                </a>
                            <?php endif; ?>

                            <?php if (current_user_can('administrator')) : ?>
                                <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>">
                                    <i class="fa-solid fa-sliders"></i> <span>Quản Trị Hệ Thống</span>
                                </a>
                            <?php endif; ?>

                            <a href="<?php echo esc_url(home_url('/doi-mat-khau/')); ?>">
                                <i class="fa-solid fa-key"></i> <span>Đổi Mật Khẩu</span>
                            </a>

                            <a href="<?php echo esc_url(home_url('/cai-dat-doc-truyen/')); ?>">
                                <i class="fa-solid fa-sliders"></i> <span>Cài Đặt Đọc Truyện</span>
                            </a>

                            <?php if (muop_user_is_translator($current_user->ID) || current_user_can('administrator')) : ?>
                                <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>">
                                    <i class="fa-solid fa-circle-plus"></i> <span>Đăng Truyện Mới</span>
                                </a>
                            <?php endif; ?>

                            <div class="user-dropdown-divider"></div>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="logout-link" style="color: #E53935;">
                                <i class="fa-solid fa-arrow-right-from-bracket" style="color: #E53935;"></i> <span>Đăng Xuất</span>
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="auth-nav">
                        <a href="<?php echo esc_url(home_url('/dang-nhap/')); ?>" class="btn btn-secondary auth-login-btn">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i> <span class="auth-btn-label">Đăng Nhập</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/dang-ky/')); ?>" class="btn btn-primary auth-register-btn">
                            <i class="fa-solid fa-user-plus"></i> <span class="auth-btn-label">Đăng Ký</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- HÀNG 2 TRÊN ĐIỆN THOẠI: TÌM KIẾM RIÊNG BIỆT (TRÁNH CHE KHUẤT TÊN) -->
        <div class="mobile-search-row">
            <div class="container mobile-search-container">
                <form role="search" method="get" class="mobile-search-bar" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="hidden" name="post_type" value="truyen" />
                    <i class="fa-solid fa-magnifying-glass search-leading-icon"></i>
                    <input type="search" placeholder="Tìm kiếm tên truyện, tác giả, dịch giả..." value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                    <button type="submit" class="mobile-search-submit-btn" aria-label="Tìm kiếm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- HEADER 2 (NAVIGATION CỐ ĐỊNH TRÊN DESKTOP) -->
    <div class="header-bottom">
        <div class="container nav-bar-inner">
            <ul class="nav-links">
                <li class="<?php echo is_front_page() ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <i class="fa-solid fa-house"></i> Trang Chủ
                    </a>
                </li>

                <!-- Thể loại Dropdown -->
                <li class="has-genre-dropdown">
                    <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="nav-genre-btn" id="navGenreToggle">
                        <i class="fa-solid fa-tags"></i> Thể Loại <i class="fa-solid fa-caret-down caret-genre" style="font-size: 11px; margin-left: 3px;"></i>
                    </a>
                    <div class="genre-dropdown-menu" id="genreDropdownMenu">
                        <?php
                        if (!empty($genres) && !is_wp_error($genres)) :
                            foreach ($genres as $genre) :
                                $is_curr_genre = (is_tax('the_loai', $genre->term_id) || (isset($_GET['the_loai']) && $_GET['the_loai'] === $genre->slug));
                        ?>
                            <a href="<?php echo esc_url(get_term_link($genre)); ?>" class="<?php echo $is_curr_genre ? 'is-active' : ''; ?>" title="<?php echo esc_attr($genre->name); ?>">
                                <?php echo esc_html($genre->name); ?>
                            </a>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Ngôn Tình</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Ngọt</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Ngược</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Đam Mỹ</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Cổ Trang</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Hiện Đại</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Tương Lai</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Xuyên Không</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Xuyên Sách</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Zhihu</a>
                        <?php endif; ?>
                    </div>
                </li>

                <li class="<?php echo is_page('truyen-moi') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>">
                        <i class="fa-solid fa-clock"></i> Truyện Mới
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-hot') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>">
                        <i class="fa-solid fa-fire"></i> Truyện Hot
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-full') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-full/')); ?>">
                        <i class="fa-solid fa-circle-check"></i> Truyện Full
                    </a>
                </li>
                <?php if ($is_logged_in) : ?>
                    <li class="<?php echo (is_page('team-dich') || is_tax('team_dich')) ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url(home_url('/team-dich/')); ?>">
                            <i class="fa-solid fa-users"></i> Team Dịch
                        </a>
                    </li>
                    <li class="has-tu-truyen-dropdown <?php echo is_page('tu-truyen') ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url(home_url('/tu-truyen/')); ?>" class="nav-tu-truyen-btn" id="navTuTruyenToggle">
                            <i class="fa-solid fa-bookmark"></i> Tủ Truyện <i class="fa-solid fa-caret-down caret-tu-truyen" style="font-size: 11px; margin-left: 3px;"></i>
                        </a>
                        <div class="tu-truyen-dropdown-menu" id="tuTruyenDropdownMenu">
                            <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=saved')); ?>">
                                <i class="fa-solid fa-bookmark" style="color: var(--primary-green);"></i> Truyện Đã Lưu
                            </a>
                            <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=favorites')); ?>">
                                <i class="fa-solid fa-heart" style="color: #f43f5e;"></i> Truyện Yêu Thích
                            </a>
                            <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=following')); ?>">
                                <i class="fa-solid fa-bell" style="color: #8b5cf6;"></i> Truyện Đang Theo Dõi
                            </a>
                            <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=teams_favorite')); ?>">
                                <i class="fa-solid fa-users-viewfinder" style="color: #ec4899;"></i> Team Dịch Đã Yêu Thích
                            </a>
                            <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=teams_following')); ?>">
                                <i class="fa-solid fa-user-check" style="color: #10b981;"></i> Team Dịch Đang Theo Dõi
                            </a>
                            <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=history')); ?>">
                                <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7;"></i> Truyện Đã Đọc
                            </a>
                        </div>
                    </li>
                <?php endif; ?>

                <li class="<?php echo is_page('gioi-thieu') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>">
                        <i class="fa-solid fa-circle-info"></i> Giới Thiệu
                    </a>
                </li>

                <!-- Translator / Admin button: Đăng Truyện -->
                <?php if ($user_role === 'dich_gia' || $user_role === 'administrator') : ?>
                    <li>
                        <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="nav-btn-publish">
                            <i class="fa-solid fa-plus"></i> Đăng Truyện
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<!-- Mobile Offcanvas Drawer Overlay & Sidebar -->
<div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
<aside class="mobile-nav-drawer" id="mobileNavDrawer" aria-label="Mobile Navigation">
    <div class="drawer-header">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="drawer-brand" title="<?php bloginfo('name'); ?>">
            <div class="brand-logo brand-avatar" style="width: 36px; height: 36px;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>" alt="Logo Mướp Đắng" class="brand-icon-img" />
            </div>
            <div class="brand-text">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" alt="<?php bloginfo('name'); ?>" class="brand-title-img" style="height: 32px;" />
            </div>
        </a>
        <button type="button" class="drawer-close-btn" id="drawerCloseBtn" aria-label="Đóng menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="drawer-body">
        <nav class="drawer-nav">
            <ul class="drawer-nav-list">
                <li class="<?php echo is_front_page() ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="drawer-nav-link">
                        <span>Trang Chủ</span>
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-moi') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="drawer-nav-link">
                        <span>Truyện Mới</span>
                    </a>
                </li>

                <!-- Thể loại accordion item chuẩn Hình 2 -->
                <li class="drawer-nav-item has-sub <?php echo (is_tax('the_loai') || is_page('the-loai')) ? 'open' : ''; ?>" id="drawerGenreItem">
                    <div class="drawer-nav-link drawer-accordion-toggle" id="drawerGenreToggle" role="button" tabindex="0">
                        <span>Thể Loại</span>
                        <i class="fa-solid fa-chevron-down drawer-caret"></i>
                    </div>
                    <div class="drawer-sub-menu" id="drawerGenreSubMenu">
                        <div class="drawer-genre-grid">
                            <?php
                            if (!empty($genres) && !is_wp_error($genres)) :
                                foreach ($genres as $genre) :
                            ?>
                                <a href="<?php echo esc_url(get_term_link($genre)); ?>" class="drawer-genre-tag"><?php echo esc_html($genre->name); ?></a>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Ngôn Tình</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Ngọt</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Ngược</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Đam Mỹ</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Cổ Trang</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Hiện Đại</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Zhihu</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>

                <li class="<?php echo is_page('truyen-full') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-full/')); ?>" class="drawer-nav-link">
                        <span>Truyện Full</span>
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-hot') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="drawer-nav-link">
                        <span>Truyện Hot</span>
                    </a>
                </li>
                <?php if ($is_logged_in) : ?>
                    <li class="<?php echo (is_page('team-dich') || is_tax('team_dich')) ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url(home_url('/team-dich/')); ?>" class="drawer-nav-link">
                            <span>Team Dịch</span>
                        </a>
                    </li>
                    <li class="drawer-nav-item has-sub <?php echo is_page('tu-truyen') ? 'open' : ''; ?>" id="drawerTuTruyenItem">
                        <div class="drawer-nav-link drawer-accordion-toggle" id="drawerTuTruyenToggle" role="button" tabindex="0">
                            <span>Tủ Truyện</span>
                            <i class="fa-solid fa-chevron-down drawer-caret"></i>
                        </div>
                        <div class="drawer-sub-menu" id="drawerTuTruyenSubMenu">
                            <ul class="drawer-sub-list" style="list-style: none; padding: 4px 0 6px 14px; margin: 0; display: flex; flex-direction: column; gap: 2px;">
                                <li>
                                    <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=saved')); ?>" class="drawer-tu-link">
                                        <i class="fa-solid fa-bookmark" style="color: var(--primary-green);"></i>
                                        <span>Truyện Đã Lưu</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=favorites')); ?>" class="drawer-tu-link">
                                        <i class="fa-solid fa-heart" style="color: #f43f5e;"></i>
                                        <span>Truyện Yêu Thích</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=following')); ?>" class="drawer-tu-link">
                                        <i class="fa-solid fa-bell" style="color: #8b5cf6;"></i>
                                        <span>Truyện Đang Theo Dõi</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=teams_favorite')); ?>" class="drawer-tu-link">
                                        <i class="fa-solid fa-users-viewfinder" style="color: #ec4899;"></i>
                                        <span>Team Dịch Đã Yêu Thích</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=teams_following')); ?>" class="drawer-tu-link">
                                        <i class="fa-solid fa-user-check" style="color: #10b981;"></i>
                                        <span>Team Dịch Đang Theo Dõi</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/tu-truyen/?tab=history')); ?>" class="drawer-tu-link">
                                        <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7;"></i>
                                        <span>Truyện Đã Đọc</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if ($user_role === 'dich_gia' || $user_role === 'administrator') : ?>
                    <li>
                        <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="drawer-nav-link" style="color: var(--primary-green); font-weight: 700;">
                            <span>Đăng Truyện</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="<?php echo is_page('gioi-thieu') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>" class="drawer-nav-link">
                        <span>Giới Thiệu</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<?php endif; ?>

<main class="site-main<?php echo is_page_template('templates/page-quan-ly-admin.php') ? ' site-main-admin' : ''; ?>">
