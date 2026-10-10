<?php
/**
 * Header Template for Mướp Đắng Cũng Có Vị Ngọt
 */
if (!defined('ABSPATH')) exit;

$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
$user_role    = muop_get_effective_user_role();
$preview_mode = isset($_COOKIE['muop_preview_mode']) ? sanitize_text_field($_COOKIE['muop_preview_mode']) : '';

// Get all genres for desktop and mobile navigation
$genres = get_terms(array(
    'taxonomy'   => 'the_loai',
    'hide_empty' => false,
));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png'); ?>" alt="<?php bloginfo('name'); ?>" class="brand-title-img" />
                    <h1 class="sr-only"><?php bloginfo('name'); ?></h1>
                </div>
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="search-form-wrapper">
                <form role="search" method="get" class="search-box" action="<?php echo esc_url(home_url('/')); ?>">
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

                <!-- Admin Mode Switcher Pill (if Admin) -->
                <?php if (current_user_can('administrator')) : ?>
                    <div class="admin-mode-pill desktop-only" title="Chế độ xem trang của Admin">
                        <i class="fa-solid fa-eye"></i>
                        <select id="adminModeSelect">
                            <option value="admin" <?php selected($preview_mode, 'admin'); ?>>Xem: Admin</option>
                            <option value="dich_gia" <?php selected($preview_mode, 'dich_gia'); ?>>Xem: Dịch Giả</option>
                            <option value="doc_gia" <?php selected($preview_mode, 'doc_gia'); ?>>Xem: Độc Giả</option>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- User Account / Login / Register -->
                <?php if ($is_logged_in) : 
                    $user_custom_avatar = get_user_meta($current_user->ID, 'muop_user_avatar', true);
                ?>
                    <div class="user-menu-wrapper" id="userMenuWrapper">
                        <div class="user-profile-badge" id="userProfileToggle" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" title="Tài khoản cá nhân">
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
                                <i class="fa-solid fa-id-card"></i> <span>Thông tin</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/tu-truyen/')); ?>">
                                <i class="fa-solid fa-bookmark"></i> <span>Tủ truyện của tôi</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/ho-so/#tabPassword')); ?>">
                                <i class="fa-solid fa-key"></i> <span>Đổi mật khẩu</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/ho-so/#tabReadingSettings')); ?>">
                                <i class="fa-solid fa-sliders"></i> <span>Cài đặt đọc truyện</span>
                            </a>

                            <?php if (in_array('dich_gia', (array)$current_user->roles) || current_user_can('administrator')) : ?>
                                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">
                                    <i class="fa-solid fa-feather-pointed"></i> <span>Kênh dịch giả</span>
                                </a>
                                <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>">
                                    <i class="fa-solid fa-circle-plus"></i> <span>Đăng truyện mới</span>
                                </a>
                            <?php endif; ?>

                            <?php if (current_user_can('administrator')) : ?>
                                <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>">
                                    <i class="fa-solid fa-sliders"></i> <span>Quản trị hệ thống</span>
                                </a>
                            <?php endif; ?>

                            <div class="user-dropdown-divider"></div>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="logout-link" style="color: #E53935;">
                                <i class="fa-solid fa-arrow-right-from-bracket" style="color: #E53935;"></i> <span>Đăng xuất</span>
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
                        <i class="fa-solid fa-house"></i> Trang chủ
                    </a>
                </li>

                <!-- Thể loại Dropdown -->
                <li class="has-genre-dropdown">
                    <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="nav-genre-btn" id="navGenreToggle">
                        <i class="fa-solid fa-tags"></i> Thể loại <i class="fa-solid fa-caret-down caret-genre" style="font-size: 11px; margin-left: 3px;"></i>
                    </a>
                    <div class="genre-dropdown-menu" id="genreDropdownMenu">
                        <?php
                        if (!empty($genres) && !is_wp_error($genres)) :
                            foreach ($genres as $genre) :
                        ?>
                            <a href="<?php echo esc_url(get_term_link($genre)); ?>">
                                <?php echo esc_html($genre->name); ?>
                            </a>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Ngôn tình</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Ngọt</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Ngược</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Đam mỹ</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Cổ trang</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Hiện đại</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Tương lai</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Xuyên không</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Xuyên sách</a>
                            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Zhihu</a>
                        <?php endif; ?>
                    </div>
                </li>

                <li class="<?php echo is_page('truyen-moi') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>">
                        <i class="fa-solid fa-clock"></i> Truyện mới
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-hot') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>">
                        <i class="fa-solid fa-fire"></i> Truyện hot
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-full') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-full/')); ?>">
                        <i class="fa-solid fa-circle-check"></i> Truyện full
                    </a>
                </li>
                <li class="<?php echo (is_page('team-dich') || is_tax('team_dich')) ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/team-dich/')); ?>">
                        <i class="fa-solid fa-users"></i> Team Dịch
                    </a>
                </li>
                <li class="<?php echo is_page('tu-truyen') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/tu-truyen/')); ?>">
                        <i class="fa-solid fa-bookmark"></i> Tủ Truyện
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

                <li class="<?php echo is_page('gioi-thieu') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>">
                        <i class="fa-solid fa-circle-info"></i> Giới thiệu
                    </a>
                </li>
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
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png'); ?>" alt="<?php bloginfo('name'); ?>" class="brand-title-img" style="height: 32px;" />
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
                        <span>Trang chủ</span>
                    </a>
                </li>
                <li class="<?php echo is_page('truyen-moi') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="drawer-nav-link">
                        <span>Truyện mới</span>
                    </a>
                </li>

                <!-- Thể loại accordion item chuẩn Hình 2 -->
                <li class="drawer-nav-item has-sub <?php echo (is_tax('the_loai') || is_page('the-loai')) ? 'open' : ''; ?>" id="drawerGenreItem">
                    <div class="drawer-nav-link drawer-accordion-toggle" id="drawerGenreToggle" role="button" tabindex="0">
                        <span>Thể loại</span>
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
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Ngôn tình</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Ngọt</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Ngược</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Đam mỹ</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Cổ trang</a>
                                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="drawer-genre-tag">Hiện đại</a>
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
                <li class="<?php echo (is_page('team-dich') || is_tax('team_dich')) ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/team-dich/')); ?>" class="drawer-nav-link">
                        <span>Team Dịch</span>
                    </a>
                </li>
                <li class="<?php echo is_page('tu-truyen') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/tu-truyen/')); ?>" class="drawer-nav-link">
                        <span>Tủ truyện</span>
                    </a>
                </li>
                <?php if ($user_role === 'dich_gia' || $user_role === 'administrator') : ?>
                    <li>
                        <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="drawer-nav-link" style="color: var(--primary-green); font-weight: 700;">
                            <span>Đăng Truyện</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="<?php echo is_page('gioi-thieu') ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>" class="drawer-nav-link">
                        <span>Giới thiệu</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<main class="site-main">
