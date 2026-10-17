<?php
/**
 * Template Name: Quản Trị Hệ Thống (Admin Portal) - Database Management Style
 * Dành riêng cho Quản Trị Viên (Admin)
 * Giao diện phân chia 2 cột: Cột trái (Logo + Menu chức năng), Cột phải (Tài khoản Admin, Toolbar tìm kiếm/thao tác, Bảng danh sách phân cấp CSDL, Footer)
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || (!current_user_can('administrator') && !current_user_can('manage_options') && !(function_exists('muop_is_admin') && muop_is_admin()))) {
    wp_redirect(home_url('/dang-nhap/?redirect_to=' . urlencode(home_url('/quan-ly-admin/'))));
    exit;
}

// Tắt hoàn toàn WordPress Admin Bar để không bị chiếm khoảng trống 32px ở đỉnh màn hình
show_admin_bar(false);

get_header();
?>
<style>
  html, body {
    margin: 0 !important;
    padding: 0 !important;
    margin-top: 0 !important;
    padding-top: 0 !important;
    background-color: var(--bg-body, #f3f6f3) !important;
  }
  #wpadminbar {
    display: none !important;
    height: 0 !important;
  }
  .site-header, .site-footer {
    display: none !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  .site-main, .site-main-admin {
    padding: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    flex: 1 1 auto;
    min-height: 100vh;
  }
  .admin-db-container {
    margin: 0 !important;
    padding: 0 !important;
  }
</style>
<?php

global $wpdb;

// 1. SYSTEM KPI STATS
$total_stories = wp_count_posts('truyen')->publish + wp_count_posts('truyen')->pending + wp_count_posts('truyen')->draft;

// Users count by role
$user_counts = count_users();
$total_readers     = isset($user_counts['avail_roles']['doc_gia']) ? $user_counts['avail_roles']['doc_gia'] : 0;
$total_translators = isset($user_counts['avail_roles']['dich_gia']) ? $user_counts['avail_roles']['dich_gia'] : 0;
$total_admins      = isset($user_counts['avail_roles']['administrator']) ? $user_counts['avail_roles']['administrator'] : 1;

// Affiliate Click Stats
$shopee_clicks = (int) get_option('muop_shopee_clicks_count', 0);
$tiktok_clicks = (int) get_option('muop_tiktok_clicks_count', 0);

// Total Month Views
$current_month_str = date('Y-m');
$all_published_stories = get_posts(array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => array('publish', 'pending', 'draft')
));

$total_month_views = 0;
$total_views_all = 0;
foreach ($all_published_stories as $st) {
    $v = (int) get_post_meta($st->ID, '_truyen_views', true);
    $total_views_all += $v;
    $vm = (int) get_post_meta($st->ID, '_truyen_views_' . $current_month_str, true);
    $total_month_views += ($vm ?: $v);
}

// Current URLs
$current_shopee_url = get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp');
$current_tiktok_url = get_option('muop_tiktok_url', 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ');
$salary_rate        = (int) get_option('muop_salary_rate', 8);

// Affiliate Recent Clicks
$table_clicks = $wpdb->prefix . 'affiliate_clicks';
$recent_clicks = $wpdb->get_results("SELECT * FROM $table_clicks ORDER BY id DESC LIMIT 20");

// Pending Moderation Stories
$pending_stories = get_posts(array(
    'post_type'      => 'truyen',
    'post_status'    => 'pending',
    'posts_per_page' => -1
));

// All Users (up to 200 users)
$all_users = get_users(array('number' => 200, 'orderby' => 'registered', 'order' => 'DESC'));

// All Translators for Salary Breakdown
$translators = get_users(array('role' => 'dich_gia'));
if (empty($translators)) {
    $translators = get_users(array('role__in' => array('dich_gia', 'administrator')));
}

// All Genres
$all_genres = get_terms(array('taxonomy' => 'the_loai', 'hide_empty' => false));
if (is_wp_error($all_genres)) $all_genres = array();

// All Sensitive Words
$all_sensitive_words = function_exists('muop_get_sensitive_words') ? muop_get_sensitive_words() : array();

// All Teams
$all_teams = get_terms(array('taxonomy' => 'team_dich', 'hide_empty' => false));
if (is_wp_error($all_teams)) $all_teams = array();

// Precalculate team stats
$teams_data = array();
foreach ($all_teams as $tm) {
    $team_stories = get_posts(array(
        'post_type' => 'truyen',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'team_dich',
                'field' => 'term_id',
                'terms' => $tm->term_id
            )
        )
    ));
    $tm_views = 0;
    $tm_chaps = 0;
    foreach ($team_stories as $ts) {
        $tm_views += (int) get_post_meta($ts->ID, '_truyen_views', true);
        $tm_chaps += count(muop_get_story_chapters($ts->ID));
    }
    $teams_data[] = array(
        'term_id' => $tm->term_id,
        'name' => $tm->name,
        'description' => $tm->description,
        'stories_count' => count($team_stories),
        'chapters_count' => $tm_chaps,
        'views' => $tm_views
    );
}
usort($teams_data, function($a, $b) {
    return $b['views'] - $a['views'];
});

// Top 10 Read Stories
$top_stories_sorted = $all_published_stories;
usort($top_stories_sorted, function($a, $b) {
    $va = (int) get_post_meta($a->ID, '_truyen_views', true);
    $vb = (int) get_post_meta($b->ID, '_truyen_views', true);
    return $vb - $va;
});
$top_10_stories = array_slice($top_stories_sorted, 0, 10);

// Translators analytics breakdown
$translator_stats = array();
foreach ($all_published_stories as $st) {
    $aid = $st->post_author;
    $aname = get_the_author_meta('display_name', $aid);
    if (!isset($translator_stats[$aid])) {
        $translator_stats[$aid] = array(
            'id' => $aid,
            'name' => $aname,
            'stories_count' => 0,
            'chapters_count' => 0,
            'total_views' => 0,
            'month_views' => 0,
        );
    }
    $translator_stats[$aid]['stories_count']++;
    $chaps = count(muop_get_story_chapters($st->ID));
    $translator_stats[$aid]['chapters_count'] += $chaps;
    $v = (int) get_post_meta($st->ID, '_truyen_views', true);
    $translator_stats[$aid]['total_views'] += $v;
    $vm = (int) get_post_meta($st->ID, '_truyen_views_' . $current_month_str, true);
    $translator_stats[$aid]['month_views'] += ($vm ?: $v);
}
uasort($translator_stats, function($a, $b) {
    return $b['total_views'] - $a['total_views'];
});

// Chapter Error Reports
$table_reports = $wpdb->prefix . 'muop_chapter_reports';
$all_reports = $wpdb->get_results("SELECT * FROM $table_reports ORDER BY id DESC LIMIT 50");
$pending_reports_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_reports WHERE status = 'pending'");

// Current logged in admin
$curr_admin = wp_get_current_user();
$admin_avatar_custom = get_user_meta($curr_admin->ID, 'muop_user_avatar', true);
$admin_avatar_custom = function_exists('muop_normalize_url') ? muop_normalize_url($admin_avatar_custom) : $admin_avatar_custom;
$preview_mode = isset($_COOKIE['muop_preview_mode']) ? sanitize_text_field($_COOKIE['muop_preview_mode']) : 'admin';
?>

<div class="admin-db-container" id="content">
    <div class="admin-db-layout">

        <!-- =========================================================================
             CỘT TRÁI: LOGO + TÊN & MENU CÁC CHỨC NĂNG (SIDEBAR)
             ========================================================================= -->
        <aside class="admin-db-sidebar" id="adminDbSidebar">
            <!-- 1. LOGO + TÊN HỆ THỐNG (HÌNH 2: CÓ NÚT ĐÓNG MENU TRÊN MOBILE) -->
            <div class="admin-db-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="admin-db-brand-link" title="Về trang chủ">
                    <div class="admin-db-brand-avatar">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>" alt="Logo Mướp Đắng" class="admin-db-logo-img" />
                    </div>
                    <div class="admin-db-brand-text">
                        <div class="admin-db-brand-title">MƯỚP ĐẮNG</div>
                        <div class="admin-db-brand-subtitle">Quản Trị Cơ Sở Dữ Liệu</div>
                    </div>
                </a>
                <button type="button" class="btn-close-admin-sidebar" id="btnCloseAdminSidebar" title="Đóng menu" aria-label="Đóng menu">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- 2. MENU CÁC CHỨC NĂNG (VERTICAL NAVIGATION) -->
            <div class="admin-db-nav-heading">
                <span>DANH MỤC QUẢN TRỊ</span>
            </div>

            <nav class="admin-db-nav">
                <!-- 1. Thống kê & Phân tích (Lên đầu theo yêu cầu Hình 2) -->
                <button type="button" class="dash-tab-btn admin-nav-item active" data-tab="admTabAnalytics" id="navItemAnalytics">
                    <span class="nav-item-icon"><i class="fa-solid fa-chart-line" style="color: #0284c7;"></i></span>
                    <span class="nav-item-label">Thống kê & Phân tích</span>
                </button>

                <!-- 2. Quản lý truyện -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabStories" id="navItemStories">
                    <span class="nav-item-icon"><i class="fa-solid fa-book"></i></span>
                    <span class="nav-item-label">Quản lý truyện</span>
                </button>

                <!-- 3. Quản lý team dịch -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabTeams" id="navItemTeams">
                    <span class="nav-item-icon"><i class="fa-solid fa-people-group" style="color: #8b5cf6;"></i></span>
                    <span class="nav-item-label">Quản lý team dịch</span>
                </button>

                <!-- 4. Quản lý user -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabUsers" id="navItemUsers">
                    <span class="nav-item-icon"><i class="fa-solid fa-users"></i></span>
                    <span class="nav-item-label">Quản lý user</span>
                </button>

                <!-- 5. Thể loại truyện -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabGenres" id="navItemGenres">
                    <span class="nav-item-icon"><i class="fa-solid fa-tags"></i></span>
                    <span class="nav-item-label">Thể loại truyện</span>
                </button>

                <!-- 5.1 Quản lý từ nhạy cảm (Mới) -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabSensitiveWords" id="navItemSensitiveWords">
                    <span class="nav-item-icon"><i class="fa-solid fa-shield-halved" style="color: #ef4444;"></i></span>
                    <span class="nav-item-label">Từ nhạy cảm</span>
                </button>

                <!-- 6. Tách truyện tự động (Đã sắp xếp xuống dưới Thể loại truyện theo Hình 4) -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabSplitter" id="navItemSplitter">
                    <span class="nav-item-icon"><i class="fa-solid fa-scissors" style="color: #f59e0b;"></i></span>
                    <span class="nav-item-label">Tách truyện tự động</span>
                </button>

                <!-- 6.1 Công cụ lấy crawl tự động (Mới) -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabVivuCrawler" id="navItemVivuCrawler">
                    <span class="nav-item-icon"><i class="fa-solid fa-cloud-arrow-down" style="color: #0284c7;"></i></span>
                    <span class="nav-item-label">Công cụ lấy crawl tự động</span>
                </button>

                <!-- 7. Bình luận & Đánh giá -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabComments" id="navItemComments">
                    <span class="nav-item-icon"><i class="fa-solid fa-comments"></i></span>
                    <span class="nav-item-label">Bình luận & Đánh giá</span>
                </button>

                <!-- 8. Tiền lương dịch giả -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabSalaries" id="navItemSalaries">
                    <span class="nav-item-icon"><i class="fa-solid fa-hand-holding-dollar" style="color: #16a34a;"></i></span>
                    <span class="nav-item-label">Tiền lương dịch giả</span>
                </button>

                <!-- 9. Link Shopee & TikTok -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabAffiliate" id="navItemAffiliate">
                    <span class="nav-item-icon"><i class="fa-solid fa-bag-shopping" style="color: #ea580c;"></i></span>
                    <span class="nav-item-label">Link Shopee & TikTok</span>
                </button>

                <!-- 10. Báo lỗi chương -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabReports" id="navItemReports">
                    <span class="nav-item-icon"><i class="fa-solid fa-triangle-exclamation" style="color: #dc2626;"></i></span>
                    <span class="nav-item-label">Báo lỗi chương</span>
                </button>

                <!-- 11. Thông báo hệ thống -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabBroadcast" id="navItemBroadcast">
                    <span class="nav-item-icon"><i class="fa-solid fa-bullhorn" style="color: #2563eb;"></i></span>
                    <span class="nav-item-label">Thông báo hệ thống</span>
                </button>

                <!-- 12. Tính năng website & Cập nhật mới -->
                <button type="button" class="dash-tab-btn admin-nav-item" data-tab="admTabFeatures" id="navItemFeatures">
                    <span class="nav-item-icon"><i class="fa-solid fa-wand-magic-sparkles" style="color: #8b5cf6;"></i></span>
                    <span class="nav-item-label">Tính năng & Cập nhật</span>
                    <span class="badge badge-green" style="font-size: 10px; padding: 2px 6px; margin-left: auto;">Mới</span>
                </button>
            </nav>
        </aside>

        <!-- Lớp phủ mờ (Backdrop) khi mở menu trên điện thoại -->
        <div id="adminSidebarBackdrop" class="admin-sidebar-backdrop"></div>

        <!-- =========================================================================
             CỘT PHẢI: KHU VỰC THAO TÁC CHÍNH (MAIN WORKSPACE)
             ========================================================================= -->
        <section class="admin-db-main" id="main">

            <!-- 1. TOP BAR: TÀI KHOẢN ADMIN & NÚT SÁNG TỐI (HÌNH 3: CĂN CHỈNH MOBILE & DROPDOWN MENU) -->
            <header class="admin-db-topbar">
                <div class="admin-db-topbar-left">
                    <!-- Hamburger button for Mobile Sidebar -->
                    <button type="button" class="btn-db-sidebar-toggle mobile-only" id="btnAdminDbSidebarToggle" aria-label="Mở Menu Chức Năng">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!-- Breadcrumbs CSDL (Tối ưu hiển thị mobile) -->
                    <div class="admin-db-breadcrumbs">
                        <span class="db-crumb-root desktop-only"><i class="fa-solid fa-database"></i> Quản Trị Hệ Thống</span>
                        <i class="fa-solid fa-angle-right db-crumb-sep desktop-only"></i>
                        <span class="db-crumb-current" id="admActiveTabLabel">Thống kê & Phân tích</span>
                    </div>
                </div>

                <div class="admin-db-topbar-right">
                    <!-- Nút Chuyển Đổi Sáng / Tối (Hình 3) -->
                    <button type="button" class="theme-toggle-btn admin-theme-toggle" aria-label="Đổi giao diện sáng tối" title="Đổi giao diện Sáng / Tối">
                        <i class="fa-solid fa-moon"></i>
                    </button>

                    <!-- Tài khoản Admin Card & Menu Dropdown (Hình 3: Trang chủ web, Đăng xuất) -->
                    <div class="admin-user-dropdown-wrap" id="adminUserDropdownWrap" style="position: relative;">
                        <div class="admin-db-account-card" id="btnAdminAccountDropdownTrigger" role="button" tabindex="0" title="Tùy chọn tài khoản" style="cursor: pointer; user-select: none;">
                            <div class="admin-db-account-avatar">
                                <?php if ($admin_avatar_custom) : ?>
                                    <img src="<?php echo esc_url($admin_avatar_custom); ?>" alt="Avatar Admin" />
                                <?php else : ?>
                                    <span><?php echo esc_html(strtoupper(substr($curr_admin->display_name, 0, 1))); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="admin-db-account-meta desktop-only">
                                <div class="admin-db-account-name"><?php echo esc_html($curr_admin->display_name); ?></div>
                                <div class="admin-db-account-badge">
                                    <i class="fa-solid fa-shield-halved"></i> Quản Trị Viên
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down admin-account-caret" style="font-size: 11px; margin-left: 4px; color: var(--text-muted); transition: transform 0.2s ease;"></i>
                        </div>

                        <!-- Dropdown Menu: Trang chủ web & Đăng xuất (Hình 3) -->
                        <div class="admin-user-menu-dropdown" id="adminUserMenuDropdown">
                            <div class="admin-user-dropdown-header">
                                <div style="font-weight: 700; font-size: 13.5px; color: var(--text-main);"><?php echo esc_html($curr_admin->display_name); ?></div>
                                <div style="font-size: 11.5px; color: var(--primary-green); font-weight: 600; margin-top: 2px;">
                                    <i class="fa-solid fa-shield-halved"></i> Quản Trị Viên
                                </div>
                            </div>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="admin-user-menu-item">
                                <i class="fa-solid fa-house" style="color: var(--primary-green);"></i>
                                <span>Trang chủ web</span>
                            </a>
                            <div class="admin-user-dropdown-divider"></div>
                            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="admin-user-menu-item item-danger">
                                <i class="fa-solid fa-right-from-bracket" style="color: #dc2626;"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- 2. TOOLBAR: HIỂN THỊ CÁC PHẦN NHẬP, THAO TÁC, HOẶC TÌM KIẾM -->
            <div class="admin-db-toolbar" id="adminDbToolbar">
                <!-- Toolbar cho Quản lý truyện -->
                <div class="admin-toolbar-group" id="toolbarGroupStories" style="display: none;">
                    <div class="toolbar-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="admStorySearchInput" class="form-input" placeholder="Tìm kiếm tên truyện, tác giả, nhóm dịch..." />
                    </div>
                    <div class="toolbar-filter-box">
                        <select id="admStoryQuickFilter" class="form-input" style="min-width: 260px; font-weight: 600; color: var(--primary-green); cursor: pointer;" title="Lọc danh sách truyện nhanh">
                            <option value="all">⚡ Tất cả truyện (<?php echo count($all_published_stories); ?>)</option>
                            <option value="hot">🔥 Nhiều lượt xem nhất (Truyện hot)</option>
                            <option value="newest">✨ Đăng mới nhất (Truyện mới)</option>
                            <option value="full">✅ Đã hoàn thành (Truyện full)</option>
                            <option value="ongoing">⏳ Chưa full (Đang ra)</option>
                            <option value="nominated">⭐ Được đề cử</option>
                            <option value="not_nominated">⚪ Không được đề cử</option>
                            <option value="visible">🌐 Đang hiển thị</option>
                            <option value="hidden">👁️‍🗨️ Đã ẩn</option>
                        </select>
                    </div>
                    <div class="toolbar-actions">
                        <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus-circle"></i> Đăng Truyện Mới
                        </a>
                    </div>
                </div>

                <!-- Toolbar cho Quản lý User -->
                <div class="admin-toolbar-group" id="toolbarGroupUsers" style="display: none;">
                    <div class="toolbar-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="admUserSearchInput" class="form-input" placeholder="Tìm kiếm theo tên hiển thị, username, email..." />
                    </div>
                    <div class="toolbar-filter-box">
                        <select id="admUserRoleFilter" class="form-input">
                            <option value="">Tất cả vai trò</option>
                            <option value="doc_gia">Độc Giả</option>
                            <option value="dich_gia">Dịch Giả</option>
                            <option value="administrator">Quản Trị Viên</option>
                        </select>
                    </div>
                    <div class="toolbar-actions">
                        <button type="button" class="btn btn-primary btn-sm" id="btnToggleCreateUserBox">
                            <i class="fa-solid fa-user-plus"></i> Thêm Người Dùng
                        </button>
                    </div>
                </div>

                <!-- Toolbar cho Thể Loại -->
                <div class="admin-toolbar-group" id="toolbarGroupGenres" style="display: none;">
                    <div class="toolbar-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="admGenreSearchInput" class="form-input" placeholder="Tìm kiếm nhanh tên thể loại..." />
                    </div>
                    <div class="toolbar-actions">
                        <button type="button" class="btn btn-primary btn-sm" id="admBtnToggleAddGenre">
                            <i class="fa-solid fa-plus"></i> Thêm Thể Loại Mới
                        </button>
                    </div>
                </div>

                <!-- Toolbar cho Quản lý Team Dịch -->
                <div class="admin-toolbar-group" id="toolbarGroupTeams" style="display: none;">
                    <div class="toolbar-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="admTeamSearchInput" class="form-input" placeholder="Tìm kiếm nhanh nhóm dịch..." />
                    </div>
                    <div class="toolbar-actions">
                        <button type="button" class="btn btn-primary btn-sm" id="btnToggleCreateTeamBox">
                            <i class="fa-solid fa-plus"></i> Tạo Nhóm Dịch Mới
                        </button>
                    </div>
                </div>

                <!-- Toolbar cho Tách Truyện Tự Động -->
                <div class="admin-toolbar-group" id="toolbarGroupSplitter" style="display: none;">
                    <div class="toolbar-generic-status">
                        <i class="fa-solid fa-scissors" style="color: var(--primary-green);"></i>
                        <span>Công cụ tự động cắt và tách file truyện thành các chương nhỏ ~2,500 từ chuẩn xác dấu câu.</span>
                    </div>
                </div>

                <!-- Toolbar cho Công cụ lấy crawl tự động -->
                <div class="admin-toolbar-group" id="toolbarGroupVivuCrawler" style="display: none;">
                    <div class="toolbar-generic-status">
                        <i class="fa-solid fa-cloud-arrow-down" style="color: #0284c7;"></i>
                        <span>Công cụ lấy crawl tự động từ vivutruyen.net, trích xuất đầy đủ thông tin, văn án và các chương.</span>
                    </div>
                </div>

                <!-- Toolbar cho Thống Kê & Phân Tích (Mặc định hiển thị tab đầu tiên) -->
                <div class="admin-toolbar-group" id="toolbarGroupAnalytics">
                    <div class="toolbar-generic-status">
                        <i class="fa-solid fa-chart-line" style="color: var(--primary-green);"></i>
                        <span>Báo cáo thống kê toàn diện lượt đọc web và phân tích hiệu suất theo từng dịch giả, nhóm dịch.</span>
                    </div>
                </div>

                <!-- Toolbar Chung cho các tab khác -->
                <div class="admin-toolbar-group" id="toolbarGroupGeneric" style="display: none;">
                    <div class="toolbar-generic-status">
                        <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i>
                        <span id="admGenericToolbarText">Quản lý và cập nhật cấu hình hệ thống thời gian thực.</span>
                    </div>
                </div>
            </div>

            <!-- 3. PHẦN HIỂN THỊ LÀ CÁC BẢNG DANH SÁCH & MASTER-DETAIL VIEWS -->
            <div class="admin-db-content">

                <!-- =================================================================
                     TAB: QUẢN LÝ TRUYỆN (MASTER LIST + HIERARCHICAL DETAIL VIEW)
                     ================================================================= -->
                <section id="admTabStories" class="dash-tab-pane">

                    <!-- STATE 1: BẢNG DANH SÁCH TẤT CẢ CÁC TRUYỆN (MASTER LIST) -->
                    <div id="admStoryMasterView" class="adm-subview-panel">
                        <!-- Thông báo truyện chờ duyệt -->
                        <?php if (!empty($pending_stories)) : ?>
                            <div class="admin-db-alert alert-pending-stories">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Hiện có <strong><?php echo count($pending_stories); ?></strong> bộ truyện mới từ dịch giả đang chờ bạn kiểm duyệt và kích hoạt!</span>
                            </div>
                        <?php endif; ?>

                        <?php
                        $adm_count_all = count($all_published_stories);
                        $adm_count_full = 0;
                        $adm_count_ongoing = 0;
                        $adm_count_nominated = 0;
                        $adm_count_not_nominated = 0;
                        $adm_count_visible = 0;
                        $adm_count_hidden = 0;

                        foreach ($all_published_stories as $st_cnt) {
                            $p_prog = get_post_meta($st_cnt->ID, '_truyen_status', true) ?: 'dang_ra';
                            if ($p_prog === 'hoan_thanh') $adm_count_full++;
                            else $adm_count_ongoing++;

                            $p_nom = get_post_meta($st_cnt->ID, '_truyen_nominated', true) ?: 'none';
                            if ($p_nom && $p_nom !== 'none') $adm_count_nominated++;
                            else $adm_count_not_nominated++;

                            if ($st_cnt->post_status === 'publish') $adm_count_visible++;
                            else $adm_count_hidden++;
                        }
                        ?>

                        <!-- Thanh Lọc Truyện Nhanh (Pills Bar) -->
                        <div class="adm-story-filter-bar">
                            <div class="adm-story-filter-pills" id="admStoryFilterPills">
                                <button type="button" class="btn-story-filter-pill active" data-filter="all">
                                    <i class="fa-solid fa-layer-group"></i> Tất cả (<?php echo $adm_count_all; ?>)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="hot" title="Lọc truyện nhiều lượt xem nhất">
                                    <i class="fa-solid fa-fire" style="color: #ef4444;"></i> Nhiều view nhất (Hot)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="newest" title="Lọc truyện được đăng mới nhất">
                                    <i class="fa-solid fa-sparkles" style="color: #3b82f6;"></i> Mới nhất
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="full" title="Truyện đã hoàn thành (Full)">
                                    <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Đã full (<?php echo $adm_count_full; ?>)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="ongoing" title="Truyện chưa hoàn thành (Đang ra)">
                                    <i class="fa-solid fa-clock-rotate-left" style="color: #f59e0b;"></i> Chưa full (<?php echo $adm_count_ongoing; ?>)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="nominated" title="Truyện được đề cử">
                                    <i class="fa-solid fa-star" style="color: #eab308;"></i> Được đề cử (<?php echo $adm_count_nominated; ?>)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="not_nominated" title="Truyện không được đề cử">
                                    <i class="fa-regular fa-star" style="color: #94a3b8;"></i> Không đề cử (<?php echo $adm_count_not_nominated; ?>)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="visible" title="Truyện đang hiển thị công khai">
                                    <i class="fa-solid fa-eye" style="color: #059669;"></i> Đang hiển thị (<?php echo $adm_count_visible; ?>)
                                </button>
                                <button type="button" class="btn-story-filter-pill" data-filter="hidden" title="Truyện đã ẩn hoặc bản nháp">
                                    <i class="fa-solid fa-eye-slash" style="color: #dc2626;"></i> Đã ẩn (<?php echo $adm_count_hidden; ?>)
                                </button>
                            </div>
                            <div class="adm-filter-stats">
                                Hiển thị: <strong id="admStoryVisibleCount" style="color: var(--primary-green); font-size: 14px;"><?php echo $adm_count_all; ?></strong> / <?php echo $adm_count_all; ?> truyện
                            </div>
                        </div>

                        <!-- Bảng Dữ Liệu Tất Cả Các Truyện (Hình 5: STT, ID, tách Team & Người đăng, bỏ icon chương & chi tiết) -->
                        <div class="table-responsive db-table-container">
                            <table class="data-table db-data-table" id="tableAdminStories">
                                <thead>
                                    <tr>
                                        <th style="width: 45px; text-align: center;">STT</th>
                                        <th style="width: 65px; text-align: center;">ID</th>
                                        <th style="width: 48px; text-align: center;">Bìa</th>
                                        <th>Tên Truyện</th>
                                        <th>Team Dịch</th>
                                        <th>Người Đăng</th>
                                        <th>Trạng Thái</th>
                                        <th>Số Chương</th>
                                        <th>Lượt Xem</th>
                                        <th>Đề Cử</th>
                                        <th style="width: 130px; text-align: center;">Hành Động CSDL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $stt = 1;
                                    foreach ($all_published_stories as $st) : 
                                        $st_author   = get_the_author_meta('display_name', $st->post_author);
                                        $st_views    = muop_get_story_views($st->ID);
                                        $st_nominate = get_post_meta($st->ID, '_truyen_nominated', true) ?: 'none';
                                        $st_chaps    = count(muop_get_story_chapters($st->ID));
                                        $st_team     = get_post_meta($st->ID, '_truyen_team', true);
                                        if (empty($st_team)) {
                                            $st_team = get_post_meta($st->ID, '_truyen_dich_gia', true);
                                        }
                                        if (empty($st_team)) {
                                            $team_terms = wp_get_post_terms($st->ID, 'team_dich');
                                            $st_team = !empty($team_terms) ? $team_terms[0]->name : '';
                                        }
                                        if (empty($st_team)) {
                                            $st_team = 'Mướp Đắng';
                                        }

                                        // URL trang team dịch
                                        $st_team_url = '';
                                        $team_terms = get_the_terms($st->ID, 'team_dich');
                                        if (!empty($team_terms) && !is_wp_error($team_terms)) {
                                            $st_team_url = get_term_link($team_terms[0]);
                                        } else if (!empty($st_team)) {
                                            $found_term = get_term_by('name', $st_team, 'team_dich');
                                            if ($found_term && !is_wp_error($found_term)) {
                                                $st_team_url = get_term_link($found_term);
                                            } else {
                                                $found_slug = get_term_by('slug', sanitize_title($st_team), 'team_dich');
                                                if ($found_slug && !is_wp_error($found_slug)) {
                                                    $st_team_url = get_term_link($found_slug);
                                                } else {
                                                    $st_team_url = home_url('/team-dich/' . sanitize_title($st_team) . '/');
                                                }
                                            }
                                        }

                                        $st_thumb = get_the_post_thumbnail_url($st->ID, 'thumbnail');
                                        $st_progress = get_post_meta($st->ID, '_truyen_status', true) ?: 'dang_ra';
                                    ?>
                                        <tr class="adm-story-row" 
                                            data-story-id="<?php echo $st->ID; ?>"
                                            data-title="<?php echo esc_attr(mb_strtolower($st->post_title, 'UTF-8')); ?>"
                                            data-team="<?php echo esc_attr(mb_strtolower($st_team, 'UTF-8')); ?>"
                                            data-author="<?php echo esc_attr(mb_strtolower($st_author, 'UTF-8')); ?>"
                                            data-status="<?php echo esc_attr($st->post_status); ?>"
                                            data-progress="<?php echo esc_attr($st_progress); ?>"
                                            data-views="<?php echo (int) $st_views; ?>"
                                            data-date="<?php echo strtotime($st->post_date); ?>"
                                            data-nominate="<?php echo esc_attr($st_nominate); ?>"
                                            data-index="<?php echo $stt; ?>">
                                            
                                            <!-- Cột STT (Hình 5) -->
                                            <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo $stt++; ?></td>

                                            <!-- Cột ID (Hình 5) -->
                                            <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0284c7;">#<?php echo $st->ID; ?></td>

                                            <!-- Cột Bìa -->
                                            <td style="text-align: center;">
                                                <div class="db-thumb-box">
                                                    <?php if ($st_thumb) : ?>
                                                        <img src="<?php echo esc_url($st_thumb); ?>" alt="Bìa" class="db-thumb-img" />
                                                    <?php else : ?>
                                                        <div class="db-thumb-placeholder"><i class="fa-solid fa-book"></i></div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <!-- Cột Tên Truyện (Click để xem chi tiết CSDL) -->
                                            <td>
                                                <div class="db-story-title-cell">
                                                    <a href="javascript:void(0)" class="db-story-clickable btn-open-story-details" data-story-id="<?php echo $st->ID; ?>" title="Xem chi tiết phân cấp của truyện">
                                                        <?php echo esc_html($st->post_title); ?>
                                                    </a>
                                                </div>
                                            </td>

                                            <!-- Cột Team Dịch (Hình 5: Có liên kết với trang của team dịch đó) -->
                                            <td>
                                                <div class="db-team-name">
                                                    <?php if (!empty($st_team_url) && !is_wp_error($st_team_url)) : ?>
                                                        <a href="<?php echo esc_url($st_team_url); ?>" target="_blank" class="db-team-link" title="Xem trang team dịch: <?php echo esc_attr($st_team); ?>">
                                                            <i class="fa-solid fa-users" style="font-size: 11px; margin-right: 4px; color: #8b5cf6;"></i><span><?php echo esc_html($st_team); ?></span>
                                                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px; opacity: 0.6; margin-left: 3px;"></i>
                                                        </a>
                                                    <?php else : ?>
                                                        <span style="color: var(--text-color);"><i class="fa-solid fa-users" style="font-size: 11px; margin-right: 4px; color: #8b5cf6;"></i><?php echo esc_html($st_team); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <!-- Cột Người Đăng (Hình 5) -->
                                            <td>
                                                <div class="db-author-name"><i class="fa-solid fa-user-pen" style="color: #64748b; margin-right: 4px; font-size: 11px;"></i> <?php echo esc_html($st_author); ?></div>
                                            </td>

                                            <!-- Cột Trạng Thái -->
                                            <td>
                                                <div class="db-status-badges">
                                                    <?php if ($st->post_status === 'publish') : ?>
                                                        <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Hiển thị</span>
                                                    <?php elseif ($st->post_status === 'pending') : ?>
                                                        <span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                                    <?php else : ?>
                                                        <span class="badge" style="background: #fee2e2; color: #dc2626;"><i class="fa-solid fa-eye-slash"></i> Đang ẩn</span>
                                                    <?php endif; ?>

                                                    <span class="badge <?php echo ($st_progress === 'hoan_thanh') ? 'badge-full' : 'badge-green'; ?> btn-table-change-status" data-story-id="<?php echo $st->ID; ?>" data-current-status="<?php echo esc_attr($st_progress); ?>" title="Đổi trạng thái Đang ra / Full">
                                                        <i class="fa-solid <?php echo ($st_progress === 'hoan_thanh') ? 'fa-check' : 'fa-arrows-rotate'; ?>"></i>
                                                        <span class="table-status-text"><?php echo ($st_progress === 'hoan_thanh') ? 'Full' : 'Đang ra'; ?></span>
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Cột Số Chương (Hình 5: Bỏ icon) -->
                                            <td>
                                                <button type="button" class="db-btn-chapter-count btn-open-story-details" data-story-id="<?php echo $st->ID; ?>" title="Xem danh sách các chương">
                                                    <strong><?php echo $st_chaps; ?></strong> chương
                                                </button>
                                            </td>

                                            <!-- Cột Lượt Xem -->
                                            <td>
                                                <span class="db-views-count"><i class="fa-solid fa-eye"></i> <?php echo number_format($st_views); ?></span>
                                            </td>

                                            <!-- Cột Đề Cử -->
                                            <td>
                                                <select class="form-input btn-admin-action-nominate db-nominate-select" onchange="jQuery(this).trigger('change_nominate', [<?php echo $st->ID; ?>, this.value]);">
                                                    <option value="none" <?php selected($st_nominate, 'none'); ?>>Không</option>
                                                    <option value="day" <?php selected($st_nominate, 'day'); ?>>Đề cử Ngày</option>
                                                    <option value="week" <?php selected($st_nominate, 'week'); ?>>Đề cử Tuần</option>
                                                    <option value="month" <?php selected($st_nominate, 'month'); ?>>Đề cử Tháng</option>
                                                </select>
                                            </td>

                                            <!-- Cột Hành Động (Hình 1: Dấu 3 chấm dọc với 7 chức năng popup) -->
                                            <td style="text-align: center;">
                                                <div class="db-row-actions" style="justify-content: center;">
                                                    <div class="story-actions-dropdown">
                                                        <button type="button" class="btn-story-dropdown-trigger" title="Tác vụ truyện" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>" data-post-status="<?php echo esc_attr($st->post_status); ?>">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </button>
                                                        <div class="story-dropdown-menu">
                                                            <!-- 1. Thêm (chỉ hiện popup ở đây) -->
                                                            <button type="button" class="story-dropdown-item btn-action-quick-add-chapter" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                                <i class="fa-solid fa-plus" style="color: #16a34a;"></i> Thêm chương mới
                                                            </button>

                                                            <!-- 2. Sửa mô tả (hiện popup) -->
                                                            <button type="button" class="story-dropdown-item btn-action-edit-story-desc" data-story-id="<?php echo $st->ID; ?>">
                                                                <i class="fa-solid fa-pen-to-square" style="color: var(--primary-green);"></i> Sửa mô tả truyện
                                                            </button>

                                                            <!-- 3. Sửa nội dung (hiện popup) -->
                                                            <button type="button" class="story-dropdown-item btn-action-edit-story-chaps" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                                <i class="fa-solid fa-file-pen" style="color: #2563eb;"></i> Sửa nội dung chương
                                                            </button>

                                                            <!-- 4. Xóa chương -->
                                                            <button type="button" class="story-dropdown-item item-danger btn-action-open-delete-chaps" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                                <i class="fa-solid fa-rectangle-xmark" style="color: #dc2626;"></i> Xóa chương
                                                            </button>

                                                            <!-- 5. Xóa truyện -->
                                                            <button type="button" class="story-dropdown-item item-danger btn-admin-action" data-action="delete_story" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                                <i class="fa-solid fa-trash-can" style="color: #dc2626;"></i> Xóa truyện
                                                            </button>

                                                            <!-- 6. Ẩn truyện / Hiện truyện -->
                                                            <?php if ($st->post_status === 'publish') : ?>
                                                                <button type="button" class="story-dropdown-item btn-admin-action btn-toggle-story-visibility" data-action="hide_story" data-story-id="<?php echo $st->ID; ?>">
                                                                    <i class="fa-solid fa-eye-slash" style="color: #d97706;"></i> Ẩn truyện
                                                                </button>
                                                            <?php else : ?>
                                                                <button type="button" class="story-dropdown-item btn-admin-action btn-toggle-story-visibility" data-action="unhide_story" data-story-id="<?php echo $st->ID; ?>">
                                                                    <i class="fa-solid fa-eye" style="color: var(--primary-green);"></i> Hiện truyện
                                                                </button>
                                                            <?php endif; ?>

                                                            <div class="story-dropdown-divider" style="margin: 4px 0; border-top: 1px solid var(--border-color);"></div>

                                                            <!-- 7. Chi tiết (phần chi tiết thì hiện popup có các thông tin như hình 4) -->
                                                            <button type="button" class="story-dropdown-item btn-action-story-full-preview" data-story-id="<?php echo $st->ID; ?>">
                                                                <i class="fa-solid fa-circle-info" style="color: #0284c7;"></i> Chi tiết truyện
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- STATE 2: HIỂN THỊ CHI TIẾT CỤ THỂ CỦA TRUYỆN ĐƯỢC CHỌN (DETAIL HIERARCHICAL VIEW) -->
                    <div id="admStoryDetailView" class="adm-subview-panel" style="display: none;">
                        <!-- Thanh Điều Hướng Quay Lại -->
                        <div class="adm-detail-navigation">
                            <button type="button" class="btn btn-secondary btn-sm" id="btnBackToStoryMaster">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách tất cả truyện
                            </button>
                            <div class="adm-detail-nav-title">
                                <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Đang xem chi tiết phân cấp CSDL bộ truyện
                            </div>
                        </div>

                        <!-- Card Tổng Quan Chi Tiết Bộ Truyện -->
                        <div class="db-detail-header-card" id="storyDetailHeaderCard">
                            <div class="db-detail-cover-box">
                                <img id="storyDetailCoverImg" src="" alt="Bìa truyện" class="db-detail-cover" />
                            </div>
                            <div class="db-detail-info-box">
                                <div class="db-detail-title-row">
                                    <h2 class="db-detail-title" id="storyDetailTitle">...</h2>
                                    <div class="db-detail-badges" id="storyDetailBadges">
                                        <!-- Badges will be populated dynamically -->
                                    </div>
                                </div>

                                <div class="db-detail-meta-grid">
                                    <div><i class="fa-solid fa-pen-nib"></i> <strong>Tác giả:</strong> <span id="storyDetailAuthor">--</span></div>
                                    <div><i class="fa-solid fa-users"></i> <strong>Nhóm dịch:</strong> <span id="storyDetailTeam">--</span></div>
                                    <div><i class="fa-solid fa-user-pen"></i> <strong>Người đăng:</strong> <span id="storyDetailPostedBy">--</span></div>
                                    <div><i class="fa-solid fa-tags"></i> <strong>Thể loại:</strong> <span id="storyDetailGenres">--</span></div>
                                    <div><i class="fa-solid fa-eye"></i> <strong>Tổng views:</strong> <span id="storyDetailViewsAll">0</span></div>
                                    <div><i class="fa-solid fa-chart-line"></i> <strong>Views tháng:</strong> <span id="storyDetailViewsMonth">0</span></div>
                                </div>

                                <!-- Mô tả truyện thu gọn/mở rộng -->
                                <div class="db-detail-desc-box">
                                    <div class="db-detail-desc-title"><i class="fa-solid fa-align-left"></i> Giới thiệu / Văn án:</div>
                                    <div class="db-detail-desc-text" id="storyDetailDesc">...</div>
                                </div>

                                <!-- Thanh Tác Vụ Của Bộ Truyện -->
                                <div class="db-detail-actions-row">
                                    <button type="button" class="btn btn-primary btn-sm btn-action-edit-story-desc" id="btnDetailEditStoryDesc" data-story-id="">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa Thông Tin Truyện
                                    </button>
                                    <a href="#" class="btn btn-secondary btn-sm" id="btnDetailAddChapterLink">
                                        <i class="fa-solid fa-plus"></i> Thêm Chương Mới
                                    </a>
                                    <button type="button" class="btn btn-outline btn-sm" id="btnDetailToggleHideStory">
                                        <i class="fa-solid fa-eye-slash"></i> Ẩn Truyện
                                    </button>
                                    <a href="#" target="_blank" class="btn btn-secondary btn-sm" id="btnDetailViewPublicLink">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Trên Website
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- BẢNG PHÂN CẤP CHI TIẾT CÁC CHƯƠNG THUỘC TRUYỆN NÀY (CHILD SUB-TABLE) -->
                        <div class="db-subtable-wrapper">
                            <div class="db-subtable-header">
                                <div class="db-subtable-title">
                                    <i class="fa-solid fa-list-check"></i>
                                    <span>Danh Sách Các Chương Thuộc Bộ Truyện (<strong id="storyDetailChaptersCount">0</strong> chương)</span>
                                </div>
                                <div class="db-subtable-actions">
                                    <input type="text" id="admChapterSearchInput" class="form-input" placeholder="Tìm kiếm chương..." style="height: 32px; font-size: 12.5px; width: 220px;" />
                                    <a href="#" id="btnSubtableAddChapter" class="btn btn-primary btn-xs">
                                        <i class="fa-solid fa-plus"></i> Thêm Chương Mới
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive db-table-container">
                                <table class="data-table db-data-table" id="tableStoryDetailChapters">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px; text-align: center;">Chương #</th>
                                            <th>Tiêu Đề Chương</th>
                                            <th style="width: 120px;">Số Từ</th>
                                            <th style="width: 120px;">Trạng Thái</th>
                                            <th style="width: 130px;">Ngày Đăng</th>
                                            <th style="width: 100px;">Lượt Xem</th>
                                            <th style="width: 160px; text-align: center;">Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="storyDetailChaptersTbody">
                                        <!-- Chapters loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB: THỐNG KÊ SỐ LIỆU & PHÂN TÍCH LƯỢT ĐỌC (HÌNH 1 & HÌNH 4)
                     ================================================================= -->
                <section id="admTabAnalytics" class="dash-tab-pane active">
                    <div class="adm-subview-panel">
                        <!-- 1. COMPACT KPI COUNTER STRIP (CHUYỂN TỪ TRÊN XUỐNG ĐÂY THEO HÌNH 4) -->
                        <div class="admin-db-kpi-strip" style="margin-bottom: 24px;">
                            <div class="db-kpi-chip">
                                <span class="db-kpi-icon"><i class="fa-solid fa-book"></i></span>
                                <span class="db-kpi-num"><?php echo number_format($total_stories); ?></span>
                                <span class="db-kpi-lbl">Truyện</span>
                            </div>
                            <div class="db-kpi-chip">
                                <span class="db-kpi-icon"><i class="fa-solid fa-users"></i></span>
                                <span class="db-kpi-num"><?php echo number_format($total_readers); ?></span>
                                <span class="db-kpi-lbl">Độc Giả</span>
                            </div>
                            <div class="db-kpi-chip">
                                <span class="db-kpi-icon"><i class="fa-solid fa-feather-pointed"></i></span>
                                <span class="db-kpi-num"><?php echo number_format($total_translators); ?></span>
                                <span class="db-kpi-lbl">Dịch Giả</span>
                            </div>
                            <div class="db-kpi-chip">
                                <span class="db-kpi-icon"><i class="fa-solid fa-eye"></i></span>
                                <span class="db-kpi-num"><?php echo number_format($total_month_views); ?></span>
                                <span class="db-kpi-lbl">Views Tháng</span>
                            </div>
                            <div class="db-kpi-chip" style="background: #FFF3E0; border-color: #FFE082;">
                                <span class="db-kpi-icon" style="color: #EE4D2D;"><i class="fa-solid fa-bag-shopping"></i></span>
                                <span class="db-kpi-num" style="color: #D84315;"><?php echo number_format($shopee_clicks); ?></span>
                                <span class="db-kpi-lbl" style="color: #D84315;">Shopee</span>
                            </div>
                            <div class="db-kpi-chip" style="background: #EDE7F6; border-color: #D1C4E9;">
                                <span class="db-kpi-icon" style="color: #4A148C;"><i class="fa-brands fa-tiktok"></i></span>
                                <span class="db-kpi-num" style="color: #4A148C;"><?php echo number_format($tiktok_clicks); ?></span>
                                <span class="db-kpi-lbl" style="color: #4A148C;">TikTok</span>
                            </div>
                        </div>

                        <!-- 2. PHÂN TÍCH TỔNG QUAN TĂNG TRƯỞNG & LƯỢT ĐỌC TOÀN TRANG -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
                            <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">Tổng Lượt Xem Toàn Trang</div>
                                <div style="font-size: 24px; font-weight: 800; color: #0284c7;"><?php echo number_format($total_views_all); ?></div>
                                <div style="font-size: 12px; color: #16a34a; margin-top: 4px;"><i class="fa-solid fa-chart-line"></i> Tích lũy toàn bộ truyện</div>
                            </div>
                            <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">Lượt Xem Tháng Hiện Tại</div>
                                <div style="font-size: 24px; font-weight: 800; color: var(--primary-green);"><?php echo number_format($total_month_views); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><i class="fa-solid fa-calendar"></i> Tháng <?php echo date('m/Y'); ?></div>
                            </div>
                            <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">Ước Tính Lương Dịch Giả</div>
                                <div style="font-size: 24px; font-weight: 800; color: #16a34a;"><?php echo number_format($total_month_views * $salary_rate); ?> <span style="font-size: 14px; font-weight: 600;">đ</span></div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Định mức: <?php echo $salary_rate; ?>đ / view</div>
                            </div>
                            <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">Bình Quân Lượt Xem / Truyện</div>
                                <div style="font-size: 24px; font-weight: 800; color: #f59e0b;"><?php echo $total_stories > 0 ? number_format(round($total_views_all / $total_stories)) : 0; ?></div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Hiệu suất lưu lượng truyện</div>
                            </div>
                        </div>

                        <!-- 3. TOP 10 TRUYỆN ĐƯỢC ĐỌC NHIỀU NHẤT TRÊN WEB -->
                        <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-trophy" style="color: #eab308;"></i> Top 10 Truyện Được Đọc Nhiều Nhất Toàn Web
                                </h3>
                                <span style="font-size: 12px; color: var(--text-muted);">Sắp xếp theo tổng lượt đọc thực tế</span>
                            </div>
                            <div class="table-responsive db-table-container">
                                <table class="data-table db-data-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px; text-align: center;">Hạng</th>
                                            <th>Tên Truyện</th>
                                            <th>Team Dịch</th>
                                            <th>Người Đăng</th>
                                            <th style="width: 100px; text-align: center;">Số Chương</th>
                                            <th style="width: 140px; text-align: center;">Tổng Lượt Xem</th>
                                            <th style="width: 120px; text-align: center;">Tỷ Lệ Traffic</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $rank = 1;
                                        foreach ($top_10_stories as $st_top) :
                                            $top_views = (int) get_post_meta($st_top->ID, '_truyen_views', true);
                                            $top_chaps = count(muop_get_story_chapters($st_top->ID));
                                            $top_author = get_the_author_meta('display_name', $st_top->post_author);
                                            $top_team = get_post_meta($st_top->ID, '_truyen_team', true);
                                            if (empty($top_team)) {
                                                $top_terms = wp_get_post_terms($st_top->ID, 'team_dich');
                                                $top_team = !empty($top_terms) ? $top_terms[0]->name : 'Mướp Đắng';
                                            }
                                            $traffic_pct = $total_views_all > 0 ? round(($top_views / $total_views_all) * 100, 1) : 0;
                                        ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <?php if ($rank === 1) : ?>
                                                        <span style="background: #fef08a; color: #854d0e; padding: 3px 8px; border-radius: 6px; font-weight: 800; font-size: 12px;"><i class="fa-solid fa-crown"></i> 1</span>
                                                    <?php elseif ($rank === 2) : ?>
                                                        <span style="background: #e2e8f0; color: #334155; padding: 3px 8px; border-radius: 6px; font-weight: 800; font-size: 12px;">2</span>
                                                    <?php elseif ($rank === 3) : ?>
                                                        <span style="background: #fed7aa; color: #9a3412; padding: 3px 8px; border-radius: 6px; font-weight: 800; font-size: 12px;">3</span>
                                                    <?php else : ?>
                                                        <span style="font-weight: 700; color: #64748b;"><?php echo $rank; ?></span>
                                                    <?php endif; $rank++; ?>
                                                </td>
                                                <td>
                                                    <strong><a href="javascript:void(0)" class="btn-open-story-details" data-story-id="<?php echo $st_top->ID; ?>" style="color: var(--primary-green); text-decoration: none;"><?php echo esc_html($st_top->post_title); ?></a></strong>
                                                </td>
                                                <td><?php echo esc_html($top_team); ?></td>
                                                <td><?php echo esc_html($top_author); ?></td>
                                                <td style="text-align: center;"><strong><?php echo $top_chaps; ?></strong> chương</td>
                                                <td style="text-align: center; font-weight: 700; color: #0284c7;"><i class="fa-solid fa-eye"></i> <?php echo number_format($top_views); ?></td>
                                                <td style="text-align: center;">
                                                    <div style="font-size: 12px; font-weight: 700;"><?php echo $traffic_pct; ?>%</div>
                                                    <div style="width: 100%; height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 3px;">
                                                        <div style="width: <?php echo min(100, $traffic_pct * 3); ?>%; height: 100%; background: var(--primary-green);"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 4. PHÂN TÍCH LƯỢT ĐỌC THEO TỪNG DỊCH GIẢ & TEAM DỊCH -->
                        <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-sm);">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-chart-pie" style="color: #6366f1;"></i> Phân Tích Lượt Đọc Theo Từng Dịch Giả & Người Đăng
                                </h3>
                                <span style="font-size: 12px; color: var(--text-muted);">Đánh giá hiệu suất và thù lao thực nhận</span>
                            </div>
                            <div class="table-responsive db-table-container">
                                <table class="data-table db-data-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px; text-align: center;">STT</th>
                                            <th>Dịch Giả / Người Đăng</th>
                                            <th style="width: 110px; text-align: center;">Số Truyện</th>
                                            <th style="width: 110px; text-align: center;">Số Chương</th>
                                            <th style="width: 130px; text-align: center;">Tổng Lượt Đọc</th>
                                            <th style="width: 130px; text-align: center;">Views Tháng</th>
                                            <th style="width: 150px; text-align: center;">Thù Lao Tháng (VNĐ)</th>
                                            <th style="width: 120px; text-align: center;">Tỷ Lệ Đóng Góp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $tr_stt = 1;
                                        foreach ($translator_stats as $ts) : 
                                            $contrib_pct = $total_views_all > 0 ? round(($ts['total_views'] / $total_views_all) * 100, 1) : 0;
                                            $salary_est = $ts['month_views'] * $salary_rate;
                                        ?>
                                            <tr>
                                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo $tr_stt++; ?></td>
                                                <td>
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--pastel-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                                            <?php echo esc_html(strtoupper(substr($ts['name'], 0, 1))); ?>
                                                        </div>
                                                        <strong><?php echo esc_html($ts['name']); ?></strong>
                                                    </div>
                                                </td>
                                                <td style="text-align: center;"><strong><?php echo $ts['stories_count']; ?></strong> bộ</td>
                                                <td style="text-align: center;"><strong><?php echo $ts['chapters_count']; ?></strong> chương</td>
                                                <td style="text-align: center; font-weight: 700; color: #0284c7;"><i class="fa-solid fa-eye"></i> <?php echo number_format($ts['total_views']); ?></td>
                                                <td style="text-align: center; font-weight: 700; color: var(--primary-green);"><?php echo number_format($ts['month_views']); ?></td>
                                                <td style="text-align: center; font-weight: 700; color: #16a34a;"><?php echo number_format($salary_est); ?> đ</td>
                                                <td style="text-align: center;">
                                                    <div style="font-size: 12px; font-weight: 700;"><?php echo $contrib_pct; ?>%</div>
                                                    <div style="width: 100%; height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 3px;">
                                                        <div style="width: <?php echo min(100, $contrib_pct * 2); ?>%; height: 100%; background: #6366f1;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB: QUẢN LÝ TEAM DỊCH (HÌNH 1)
                     ================================================================= -->
                <section id="admTabTeams" class="dash-tab-pane">
                    <div class="adm-subview-panel">
                        <!-- Box Form Thêm Nhóm Dịch Mới (toggle) -->
                        <div id="boxAdminCreateTeam" style="display: none; background: var(--pastel-green); padding: 18px; border-radius: var(--radius-md); border: 1.5px solid var(--primary-green); margin-bottom: 20px; box-shadow: var(--shadow-sm);">
                            <div style="font-weight: 700; font-size: 14px; color: var(--primary-green); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fa-solid fa-people-group"></i> Thêm Nhóm Dịch Mới</span>
                                <button type="button" id="btnCloseCreateTeamBox" style="background: none; border: none; font-size: 16px; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form id="formAdminCreateTeam" style="display: grid; grid-template-columns: 1fr 2fr 130px; gap: 12px; align-items: flex-end;">
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Tên Nhóm Dịch *</label>
                                    <input type="text" name="team_name" class="form-input" placeholder="Ví dụ: Cung Đấu Team" required />
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Giới Thiệu / Mô Tả</label>
                                    <input type="text" name="team_desc" class="form-input" placeholder="Mô tả ngắn gọn về nhóm dịch..." />
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btnSubmitCreateTeam" style="width: 100%; height: 38px;">
                                        <i class="fa-solid fa-check"></i> Tạo Nhóm
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Box Form Chỉnh Sửa Nhóm Dịch -->
                        <div id="boxAdminEditTeam" style="display: none; background: #eff6ff; padding: 18px; border-radius: var(--radius-md); border: 1.5px solid #3b82f6; margin-bottom: 20px; box-shadow: var(--shadow-sm);">
                            <div style="font-weight: 700; font-size: 14px; color: #1d4ed8; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa Thông Tin Nhóm Dịch</span>
                                <button type="button" id="btnCloseEditTeamBox" style="background: none; border: none; font-size: 16px; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form id="formAdminEditTeam" style="display: grid; grid-template-columns: 1fr 2fr 130px; gap: 12px; align-items: flex-end;">
                                <input type="hidden" name="team_id" id="editTeamId" value="" />
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Tên Nhóm Dịch *</label>
                                    <input type="text" name="team_name" id="editTeamName" class="form-input" required />
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Giới Thiệu / Mô Tả</label>
                                    <input type="text" name="team_desc" id="editTeamDesc" class="form-input" />
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btnSubmitEditTeam" style="width: 100%; height: 38px;">
                                        <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Table Danh Sách Team Dịch -->
                        <div class="table-responsive db-table-container">
                            <table class="data-table db-data-table" id="tableAdminTeams">
                                <thead>
                                    <tr>
                                        <th style="width: 45px; text-align: center;">STT</th>
                                        <th style="width: 65px; text-align: center;">ID</th>
                                        <th>Tên Nhóm Dịch</th>
                                        <th>Mô Tả / Giới Thiệu</th>
                                        <th style="width: 130px; text-align: center;">Số Truyện</th>
                                        <th style="width: 130px; text-align: center;">Tổng Lượt Xem</th>
                                        <th style="width: 140px; text-align: center;">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $t_stt = 1;
                                    if (empty($teams_data)) : ?>
                                        <tr><td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">Chưa có nhóm dịch nào. Hãy bấm "Tạo Nhóm Dịch Mới" để thêm!</td></tr>
                                    <?php else :
                                        foreach ($teams_data as $tm) : ?>
                                            <tr class="adm-team-row" data-team-name="<?php echo esc_attr(mb_strtolower($tm['name'], 'UTF-8')); ?>">
                                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo $t_stt++; ?></td>
                                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #8b5cf6;">#<?php echo $tm['term_id']; ?></td>
                                                <td>
                                                    <strong style="color: var(--text-color); font-size: 14px;"><i class="fa-solid fa-people-group" style="color: #8b5cf6; margin-right: 6px;"></i> <?php echo esc_html($tm['name']); ?></strong>
                                                </td>
                                                <td>
                                                    <span style="font-size: 12.5px; color: var(--text-muted);"><?php echo esc_html($tm['description'] ?: 'Chưa có mô tả'); ?></span>
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge badge-green"><strong><?php echo $tm['stories_count']; ?></strong> bộ truyện</span>
                                                </td>
                                                <td style="text-align: center;">
                                                    <span style="font-weight: 700; color: #0284c7;"><i class="fa-solid fa-eye"></i> <?php echo number_format($tm['views']); ?></span>
                                                </td>
                                                <td style="text-align: center;">
                                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                                        <button type="button" class="btn btn-outline btn-xs btn-edit-team-trigger" 
                                                            data-team-id="<?php echo $tm['term_id']; ?>" 
                                                            data-team-name="<?php echo esc_attr($tm['name']); ?>" 
                                                            data-team-desc="<?php echo esc_attr($tm['description']); ?>" 
                                                            title="Sửa thông tin nhóm">
                                                            Sửa
                                                        </button>
                                                        <button type="button" class="btn btn-outline btn-xs btn-delete-team-trigger" 
                                                            data-team-id="<?php echo $tm['term_id']; ?>" 
                                                            data-team-name="<?php echo esc_attr($tm['name']); ?>" 
                                                            style="color: #dc2626; border-color: #fca5a5;" 
                                                            title="Xóa nhóm dịch này">
                                                            Xóa
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB: TÁCH TRUYỆN TỰ ĐỘNG (HÌNH 1)
                     ================================================================= -->
                <section id="admTabSplitter" class="dash-tab-pane">
                    <div class="adm-subview-panel">
                        <div class="db-splitter-container" style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px;">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                    <i class="fa-solid fa-scissors"></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-color);">Công Cụ Tự Động Tách Truyện Thành Các Chương Nhỏ</h3>
                                    <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-muted);">
                                        Hệ thống tự động phân tích và chia truyện thành các chương ~2,500 từ. <strong>Ngắt chuẩn xác ở dấu chấm cuối câu hoặc ngắt đoạn</strong>, không bao giờ bị đứt nửa chừng. Hỗ trợ sao chép từng chương hoặc tải file về máy.
                                    </p>
                                </div>
                            </div>

                            <form id="formChapterSplitter" enctype="multipart/form-data">
                                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                                    <!-- 1. Upload File Box (Toàn chiều rộng) -->
                                    <div style="border: 2px dashed #cbd5e1; border-radius: var(--radius-md); padding: 16px 18px; text-align: center; background: #f8fafc; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; transition: all 0.2s ease;" id="splitterDropzone">
                                        <input type="file" id="splitterFileInput" name="story_file" accept=".txt,.doc,.docx,.pdf" style="display: none;" />
                                        <div style="font-size: 28px; color: #94a3b8; margin-bottom: 6px;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                        <div style="font-weight: 600; font-size: 13px; color: var(--text-color); margin-bottom: 3px;" id="splitterFileNameDisplay">Kéo thả file truyện hoặc bấm vào đây để chọn file</div>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Hỗ trợ: <strong>.txt, .docx, .doc, .pdf</strong> (tối đa 20MB)</div>
                                        <button type="button" class="btn btn-secondary btn-xs" id="btnChooseSplitFile">
                                            <i class="fa-solid fa-folder-open"></i> Chọn file từ máy
                                        </button>
                                    </div>

                                    <!-- 2. Direct Textarea Paste (Cho phần dán nội dung xuống phía dưới theo Hình 2) -->
                                    <div style="width: 100%;">
                                        <label class="form-label" style="font-weight: 600; font-size: 13px; margin-bottom: 6px; display: block;">
                                            <i class="fa-solid fa-paste"></i> Hoặc dán trực tiếp nội dung truyện vào đây:
                                        </label>
                                        <textarea id="splitterRawText" name="raw_text" class="form-input" rows="5" placeholder="Dán toàn bộ văn bản truyện dài vào đây nếu bạn không muốn tải file lên..." style="width: 100%; resize: vertical; font-family: inherit; font-size: 13px; line-height: 1.6;"></textarea>
                                    </div>
                                </div>

                                <!-- 3. Options & Target Words (Cho phần random xuống phía dưới theo Hình 2) -->
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-top: 14px; border-top: 1px solid var(--border-color); flex-wrap: wrap;">
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                            <label for="splitterTargetWords" style="font-size: 13px; font-weight: 600; color: var(--text-color); white-space: nowrap;">
                                                <i class="fa-solid fa-sliders"></i> Số từ mục tiêu / chương:
                                            </label>
                                            <input type="number" id="splitterTargetWords" name="target_words" class="form-input" value="2500" min="500" max="15000" step="100" style="width: 110px; font-weight: 700; text-align: center;" />
                                        </div>
                                        <!-- Chú thích tự động random xuống phía dưới dòng chọn từ -->
                                        <div style="font-size: 12px; color: var(--text-muted); line-height: 1.5;">
                                            <i class="fa-solid fa-circle-info" style="color: var(--primary-green); margin-right: 4px;"></i> (Hệ thống tự động random ±150 từ và ngắt chuẩn tại dấu chấm câu kết thúc)
                                        </div>
                                    </div>

                                    <div style="margin-top: 4px;">
                                        <button type="submit" class="btn btn-primary" id="btnSubmitSplit" style="padding: 10px 24px; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                            <i class="fa-solid fa-scissors"></i> Bắt Đầu Tách Chương Tự Động
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Kết Quả Tách Chương (Initially Hidden) -->
                        <div id="splitterResultWrapper" style="display: none;">
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 18px; box-shadow: var(--shadow-sm); flex-wrap: wrap; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div>
                                        <span style="font-size: 12px; color: var(--text-muted); display: block;">Tổng số chương tách được:</span>
                                        <strong id="resTotalChapters" style="font-size: 20px; color: var(--primary-green);">0</strong>
                                    </div>
                                    <div style="width: 1px; height: 32px; background: var(--border-color);"></div>
                                    <div>
                                        <span style="font-size: 12px; color: var(--text-muted); display: block;">Tổng số từ phân tích:</span>
                                        <strong id="resTotalWords" style="font-size: 20px; color: #0284c7;">0</strong>
                                    </div>
                                    <div style="width: 1px; height: 32px; background: var(--border-color);"></div>
                                    <div>
                                        <span style="font-size: 12px; color: var(--text-muted); display: block;">Trung bình từ / chương:</span>
                                        <strong id="resAvgWords" style="font-size: 20px; color: #d97706;">0</strong>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnCopyAllChapters">
                                        <i class="fa-solid fa-copy"></i> Sao Chép Toàn Bộ
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" id="btnDownloadAllChapters">
                                        <i class="fa-solid fa-file-arrow-down"></i> Tải Về Toàn Bộ (.txt)
                                    </button>
                                    <button type="button" class="btn btn-outline btn-sm" id="btnResetSplitterWorkspace" style="border-color: #cbd5e1; color: var(--text-color);" title="Làm mới form để tách file truyện mới">
                                        <i class="fa-solid fa-rotate-right"></i> Tách Nội Dung Mới
                                    </button>
                                </div>
                            </div>

                            <!-- List Chapters Grid/Cards -->
                            <div id="splitterChaptersList" style="display: flex; flex-direction: column; gap: 14px;">
                                <!-- Dynamically populated -->
                            </div>
                        </div>

                        <!-- Khu Vực Lịch Sử Tách Chương -->
                        <div class="db-splitter-history-card" style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px 24px; box-shadow: var(--shadow-sm); margin-top: 24px;" id="splitterHistorySection">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 34px; height: 34px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color);">Lịch Sử Tách Chương</h4>
                                        <p style="margin: 2px 0 0; font-size: 12px; color: var(--text-muted);">Các phiên tách truyện gần nhất được lưu tự động trên trình duyệt. Khi tải lại trang hoặc quay lại, form sẽ luôn trống để bạn tách truyện mới.</p>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-secondary btn-xs" id="btnClearSplitterHistory" style="color: #ef4444; border-color: #fecaca; background: #fff5f5;">
                                        <i class="fa-solid fa-trash-can"></i> Xóa toàn bộ lịch sử
                                    </button>
                                </div>
                            </div>

                            <!-- Bảng danh sách lịch sử -->
                            <div class="table-responsive db-table-container" style="overflow-x: auto; box-shadow: none; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                                <table class="data-table" style="width: 100%; min-width: 820px; font-size: 13px;" id="tableSplitterHistory">
                                    <thead>
                                        <tr>
                                            <th style="width: 45px; text-align: center;">STT</th>
                                            <th style="min-width: 220px;">Tên File / Tiêu Đề</th>
                                            <th style="text-align: center; width: 105px;">Số Chương</th>
                                            <th style="text-align: center; width: 110px;">Tổng Số Từ</th>
                                            <th style="text-align: center; width: 110px;">TB Từ/Chương</th>
                                            <th style="text-align: center; width: 140px;">Thời Gian</th>
                                            <th style="text-align: center; width: 220px;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="splitterHistoryListBody">
                                        <!-- Dynamically rendered via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="splitterHistoryEmpty" style="display: none; padding: 28px; text-align: center; color: var(--text-muted); font-size: 13.5px;">
                                <i class="fa-solid fa-inbox" style="font-size: 28px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                Chưa có lịch sử tách chương nào được lưu.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB: LẤY TRUYỆN TỰ ĐỘNG VIVUTRUYEN.NET (MỚI)
                     ================================================================= -->
                <section id="admTabVivuCrawler" class="dash-tab-pane">
                    <div class="adm-subview-panel">
                        <!-- 1. Box Giới thiệu & Nhập link truyện -->
                        <div class="db-splitter-container" style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 16.5px; font-weight: 700; color: var(--text-color);">Công Cụ Lấy Crawl Tự Động (VivuTruyen.net)</h3>
                                    <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                                        Tự động thu thập thông tin truyện (Tên truyện, Tác giả, Thể loại, Trạng thái, Ảnh bìa, Văn án) và toàn bộ các chương từ website <code>vivutruyen.net</code>. Cho phép tải file hoàn chỉnh về máy (<strong>.txt</strong>), sao chép toàn bộ hoặc chuyển thẳng sang công cụ <strong>Tách truyện tự động</strong>.
                                    </p>
                                </div>
                            </div>

                            <form id="formVivuCrawler" onsubmit="return false;">
                                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 18px;">
                                    <label for="vivuStoryUrl" style="font-weight: 600; font-size: 13.5px; color: var(--text-color); display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-link" style="color: #0284c7;"></i> Nhập link truyện trên VivuTruyen.net:
                                    </label>
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                        <input type="url" id="vivuStoryUrl" class="form-input" placeholder="Ví dụ: https://vivutruyen.net/chong-toi-phai-long-ban-than-toi/#google_vignette" style="flex: 1; min-width: 280px; font-size: 13.5px; padding: 10px 14px;" required />
                                        <button type="button" class="btn btn-primary" id="btnVivuFetchInfo" style="padding: 10px 22px; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; background: #0284c7; border-color: #0284c7;">
                                            <i class="fa-solid fa-magnifying-glass"></i> 1. Phân Tích & Lấy Thông Tin
                                        </button>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-circle-info" style="color: #0284c7;"></i>
                                        <span>Hệ thống tự động lọc bỏ quảng cáo, tự động chuẩn hóa URL nếu dán link có chứa <code>#google_vignette</code> hoặc link chương lẻ.</span>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- 2. Box Preview Thông Tin Truyện & Danh Sách Chương -->
                        <div id="vivuStoryPreviewBox" style="display: none; background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 14px; margin-bottom: 18px;">
                                <h4 style="margin: 0; font-size: 15.5px; font-weight: 700; color: var(--text-color); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-book-bookmark" style="color: #0284c7;"></i> Thông Tin Bộ Truyện Đã Nhận Diện
                                </h4>
                                <span class="badge" id="vivuBadgeTotalChaps" style="background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 12.5px; padding: 4px 10px; border-radius: 6px;">
                                    0 Chương
                                </span>
                            </div>

                            <!-- Chi tiết truyện: Bìa + Thông tin -->
                            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                                <div style="width: 140px; flex-shrink: 0; text-align: center;">
                                    <img id="vivuCoverImg" src="" alt="Bìa truyện" style="width: 100%; height: 195px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: var(--shadow-sm); background: #f1f5f9;" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'140\' height=\'195\'><rect fill=\'%23e2e8f0\' width=\'100%25\' height=\'100%25\'/><text fill=\'%2394a3b8\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' font-size=\'14\'>Không có ảnh</text></svg>';" />
                                </div>
                                <div style="flex: 1; min-width: 260px; display: flex; flex-direction: column; gap: 8px;">
                                    <h3 id="vivuTitleDisplay" style="margin: 0; font-size: 18px; font-weight: 800; color: var(--text-color); line-height: 1.4;">Tên Truyện</h3>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; font-size: 13px; margin: 4px 0;">
                                        <span class="badge" style="background: #f1f5f9; color: #334155; padding: 5px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                            <i class="fa-solid fa-pen-nib" style="color: #64748b; margin-right: 4px;"></i> Tác giả: <strong id="vivuAuthorDisplay">...</strong>
                                        </span>
                                        <span class="badge" style="background: #f1f5f9; color: #334155; padding: 5px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                            <i class="fa-solid fa-tags" style="color: #64748b; margin-right: 4px;"></i> Thể loại: <strong id="vivuGenresDisplay">...</strong>
                                        </span>
                                        <span class="badge" id="vivuStatusBadge" style="background: #ecfdf5; color: #047857; padding: 5px 10px; border-radius: 6px; border: 1px solid #a7f3d0;">
                                            <i class="fa-solid fa-circle-check" style="margin-right: 4px;"></i> Trạng thái: <strong id="vivuStatusDisplay">...</strong>
                                        </span>
                                    </div>
                                    <div style="margin-top: 6px;">
                                        <label style="font-size: 12.5px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 4px;">Văn án / Mô tả truyện:</label>
                                        <div id="vivuDescDisplay" style="max-height: 110px; overflow-y: auto; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; font-size: 13px; line-height: 1.6; color: #334155; white-space: pre-line;">...</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Danh sách chương & Bộ chọn tải -->
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; cursor: pointer; user-select: none;">
                                            <input type="checkbox" id="vivuSelectAllChaps" checked style="width: 16px; height: 16px; accent-color: #0284c7; cursor: pointer;" />
                                            <span>Chọn tất cả chương</span>
                                        </label>
                                        <span id="vivuSelectedChapsSummary" style="font-size: 12.5px; color: var(--text-muted);">
                                            (Đã chọn: <strong id="vivuSelectedCount" style="color: #0284c7;">0</strong> / <strong id="vivuTotalCount">0</strong> chương)
                                        </span>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <label for="vivuBatchSize" style="font-size: 12.5px; color: var(--text-muted); white-space: nowrap;">Tốc độ tải:</label>
                                        <select id="vivuBatchSize" class="form-input" style="font-size: 12.5px; padding: 4px 10px; width: 140px;">
                                            <option value="2" selected>2 chương/lần</option>
                                            <option value="3">3 chương/lần (nhanh)</option>
                                            <option value="1">1 chương/lần (ổn định)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Khung cuộn danh sách chương -->
                                <div id="vivuChaptersListContainer" style="max-height: 220px; overflow-y: auto; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px;">
                                    <div id="vivuChaptersGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 6px;">
                                        <!-- Danh sách từng chương do JavaScript render -->
                                    </div>
                                </div>
                            </div>

                            <!-- Nút bắt đầu tải chương -->
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                <button type="button" class="btn btn-secondary btn-sm" id="btnVivuCancelPreview">
                                    <i class="fa-solid fa-xmark"></i> Hủy Bỏ
                                </button>
                                <button type="button" class="btn btn-primary" id="btnVivuStartCrawl" style="padding: 10px 26px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; background: #0284c7; border-color: #0284c7;">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> 2. Bắt Đầu Tải Toàn Bộ Nội Dung Chương
                                </button>
                            </div>
                        </div>

                        <!-- 3. Thanh tiến trình tải thời gian thực -->
                        <div id="vivuProgressBox" style="display: none; background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; color: var(--text-color);">
                                    <i class="fa-solid fa-circle-notch fa-spin" style="color: #0284c7;"></i>
                                    <span id="vivuProgressStatusText">Đang bắt đầu tải dữ liệu...</span>
                                </div>
                                <span id="vivuProgressPercentText" style="font-weight: 800; font-size: 15px; color: #0284c7;">0%</span>
                            </div>

                            <div style="background: #e2e8f0; border-radius: 10px; height: 16px; overflow: hidden; width: 100%; margin-bottom: 8px;">
                                <div id="vivuProgressBar" style="height: 100%; width: 0%; background: linear-gradient(90deg, #0284c7, #38bdf8); border-radius: 10px; transition: width 0.25s ease;"></div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px; color: var(--text-muted);">
                                <span id="vivuProgressDetail">Đang chuẩn bị gửi request...</span>
                                <button type="button" class="btn btn-secondary btn-xs" id="btnVivuStopCrawl" style="font-size: 11.5px; padding: 2px 8px;">
                                    <i class="fa-solid fa-pause"></i> Tạm dừng tải
                                </button>
                            </div>
                        </div>

                        <!-- 4. Box Kết Quả & Thao Tác Xuất File / Chuyển Tách Truyện -->
                        <div id="vivuResultBox" style="display: none; background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <div>
                                        <h3 style="margin: 0; font-size: 16.5px; font-weight: 700; color: #15803d;" id="vivuResultTitle">
                                            Đã Thu Thập Hoàn Tất Toàn Bộ Truyện!
                                        </h3>
                                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--text-muted);" id="vivuResultStats">
                                            Đã tải thành công 0/0 chương. Tổng cộng 0 từ.
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <!-- Nút 1: Tải File .txt -->
                                    <button type="button" class="btn btn-primary" id="btnVivuDownloadTxt" style="padding: 10px 20px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; background: #16a34a; border-color: #16a34a; color: #ffffff;">
                                        <i class="fa-solid fa-file-arrow-down"></i> Tải File Văn Bản (.txt)
                                    </button>
                                    <!-- Nút 2: Chuyển Sang Tách Truyện Tự Động -->
                                    <button type="button" class="btn btn-primary" id="btnVivuSendToSplitter" style="padding: 10px 20px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; background: #f59e0b; border-color: #d97706; color: #ffffff;">
                                        <i class="fa-solid fa-scissors"></i> Chuyển Sang Tách Truyện Tự Động
                                    </button>
                                    <!-- Nút 3: Sao Chép Toàn Bộ -->
                                    <button type="button" class="btn btn-secondary" id="btnVivuCopyAll" style="padding: 10px 18px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-copy"></i> Sao Chép Toàn Bộ
                                    </button>
                                </div>
                            </div>

                            <!-- Khung xem trước nội dung file hoàn chỉnh -->
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label for="vivuPreviewTextarea" style="font-weight: 700; font-size: 13px; color: var(--text-color); margin: 0;">
                                        <i class="fa-solid fa-file-lines" style="color: #0284c7; margin-right: 4px;"></i> Xem Trước Toàn Bộ Nội Dung File:
                                    </label>
                                    <span style="font-size: 12px; color: var(--text-muted);" id="vivuFileSizeEstimate">Độ dài: ~0 ký tự</span>
                                </div>
                                <textarea id="vivuPreviewTextarea" class="form-input" rows="12" readonly style="width: 100%; font-family: 'Consolas', 'Monaco', monospace; font-size: 12.5px; line-height: 1.6; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px;"></textarea>
                            </div>
                        </div>

                        <!-- 5. Bảng Lịch Sử Lấy Truyện Vivu (Lưu cục bộ) -->
                        <div class="db-splitter-container" style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px 24px; box-shadow: var(--shadow-sm);">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                                <h3 style="margin: 0; font-size: 15.5px; font-weight: 700; color: var(--text-color); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7;"></i> Lịch Sử Lấy Crawl Tự Động Gần Đây
                                </h3>
                                <button type="button" class="btn btn-secondary btn-xs" id="btnClearVivuHistory">
                                    <i class="fa-solid fa-trash-can"></i> Xóa tất cả lịch sử
                                </button>
                            </div>

                            <div class="table-responsive db-table-container" style="overflow-x: auto; box-shadow: none; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                                <table class="data-table" style="width: 100%; min-width: 820px; font-size: 13px;" id="tableVivuHistory">
                                    <thead>
                                        <tr>
                                            <th style="width: 45px; text-align: center;">STT</th>
                                            <th style="min-width: 220px;">Tên Truyện</th>
                                            <th style="min-width: 120px;">Tác Giả</th>
                                            <th style="text-align: center; width: 100px;">Số Chương</th>
                                            <th style="text-align: center; width: 110px;">Tổng Từ</th>
                                            <th style="text-align: center; width: 140px;">Thời Gian Lấy</th>
                                            <th style="text-align: center; width: 220px;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vivuHistoryListBody">
                                        <!-- Dynamically rendered via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="vivuHistoryEmpty" style="display: none; padding: 28px; text-align: center; color: var(--text-muted); font-size: 13.5px;">
                                <i class="fa-solid fa-inbox" style="font-size: 28px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                Chưa có lịch sử lấy truyện nào được lưu.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 2: QUẢN LÝ NGƯỜI DÙNG (MASTER LIST + HIERARCHICAL DETAIL VIEW)
                     ================================================================= -->
                <section id="admTabUsers" class="dash-tab-pane">

                    <!-- STATE 1: BẢNG DANH SÁCH TẤT CẢ USER (MASTER LIST) -->
                    <div id="admUserMasterView" class="adm-subview-panel">
                        <!-- Box Form Thêm Người Dùng Mới (toggle ẩn/hiện) -->
                        <div id="boxAdminCreateUser" style="display: none; background: var(--pastel-green); padding: 18px; border-radius: var(--radius-md); border: 1.5px solid var(--primary-green); margin-bottom: 20px; box-shadow: var(--shadow-sm);">
                            <div style="font-weight: 700; font-size: 14px; color: var(--primary-green); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fa-solid fa-user-plus"></i> Thêm Tài Khoản Người Dùng Mới</span>
                                <button type="button" id="btnCloseCreateUserBox" style="background: none; border: none; font-size: 16px; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form id="formAdminCreateUser" style="display: grid; grid-template-columns: repeat(4, 1fr) 130px; gap: 12px; align-items: flex-end;">
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Tên Hiển Thị</label>
                                    <input type="text" name="name" class="form-input" placeholder="Họ và tên" required />
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Email</label>
                                    <input type="email" name="email" class="form-input" placeholder="user@example.com" required />
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Mật Khẩu</label>
                                    <input type="text" name="password" class="form-input" placeholder="Mật khẩu tạo mới" required />
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label" style="font-size: 12px;">Vai Trò</label>
                                    <select name="role" class="form-input">
                                        <option value="doc_gia">Độc Giả</option>
                                        <option value="dich_gia">Dịch Giả</option>
                                        <option value="administrator">Quản Trị Viên</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" style="height: 38px;">
                                    <i class="fa-solid fa-plus"></i> Lưu User
                                </button>
                            </form>
                        </div>

                        <!-- Bảng Dữ Liệu Tất Cả Người Dùng (Căn chỉnh chuẩn đẹp, có nút kéo qua lại) -->
                        <div class="db-table-scroll-wrapper" style="position: relative;">
                            <div class="table-responsive db-table-container">
                                <table class="data-table db-data-table" id="tableAdminUsers">
                                    <thead>
                                        <tr>
                                            <th style="width: 45px; text-align: center;">STT</th>
                                            <th style="width: 55px; text-align: center;">ID</th>
                                            <th style="width: 50px; text-align: center;">Avatar</th>
                                            <th style="min-width: 150px; text-align: left;">Tên Hiển Thị</th>
                                            <th style="min-width: 125px; text-align: left;">Username</th>
                                            <th style="min-width: 175px; text-align: left;">Email</th>
                                            <th style="min-width: 170px; text-align: center;">Mật Khẩu</th>
                                            <th style="min-width: 130px; text-align: center;">Vai Trò</th>
                                            <th style="min-width: 110px; text-align: center;">Ngày Đăng Ký</th>
                                            <th style="min-width: 125px; text-align: center;">Trạng Thái</th>
                                            <th style="width: 65px; text-align: center;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $u_stt = 1;
                                        foreach ($all_users as $u) : 
                                            $roles = (array) $u->roles;
                                            $r = !empty($roles) ? $roles[0] : 'doc_gia';
                                            $is_locked = (bool) get_user_meta($u->ID, '_muop_account_locked', true);
                                            $u_avatar = get_user_meta($u->ID, 'muop_user_avatar', true);
                                            $u_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($u_avatar) : $u_avatar;
                                        ?>
                                            <tr class="adm-user-row"
                                                id="adminUserRow_<?php echo $u->ID; ?>"
                                                data-user-id="<?php echo $u->ID; ?>"
                                                data-name="<?php echo esc_attr(mb_strtolower($u->display_name, 'UTF-8')); ?>"
                                                data-username="<?php echo esc_attr(mb_strtolower($u->user_login, 'UTF-8')); ?>"
                                                data-email="<?php echo esc_attr(mb_strtolower($u->user_email, 'UTF-8')); ?>"
                                                data-role="<?php echo esc_attr($r); ?>"
                                                data-status="<?php echo $is_locked ? 'locked' : 'active'; ?>">
                                                
                                                <!-- Cột STT -->
                                                <td style="text-align: center; color: var(--text-muted); font-weight: 600;">
                                                    <?php echo $u_stt++; ?>
                                                </td>

                                                <!-- Cột ID -->
                                                <td style="text-align: center; font-weight: 700; color: #0284c7;">
                                                    #<?php echo $u->ID; ?>
                                                </td>

                                                <!-- Cột Avatar -->
                                                <td style="text-align: center;">
                                                    <div class="db-user-avatar-small">
                                                        <?php if ($u_avatar) : ?>
                                                            <img src="<?php echo esc_url($u_avatar); ?>" alt="Avatar" />
                                                        <?php else : ?>
                                                            <span><?php echo esc_html(strtoupper(substr($u->display_name, 0, 1))); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>

                                                <!-- Cột Tên Hiển Thị -->
                                                <td>
                                                    <strong class="col-user-displayname" style="color: var(--text-main); font-weight: 700; white-space: nowrap;">
                                                        <?php echo esc_html($u->display_name); ?>
                                                    </strong>
                                                </td>

                                                <!-- Cột Username -->
                                                <td>
                                                    <code class="col-user-login" style="background: var(--pastel-green); color: var(--primary-green); padding: 3px 8px; border-radius: 4px; font-size: 13px; font-weight: 600; white-space: nowrap;">@<?php echo esc_html($u->user_login); ?></code>
                                                </td>

                                                <!-- Cột Email -->
                                                <td>
                                                    <span class="db-user-email col-user-email" style="white-space: nowrap;"><?php echo esc_html($u->user_email); ?></span>
                                                </td>

                                                <!-- Cột Mật Khẩu (MỚI) -->
                                                <td class="col-user-password-cell" id="userPassCell_<?php echo $u->ID; ?>" style="text-align: center; white-space: nowrap;">
                                                    <?php 
                                                    $u_pass_saved = get_user_meta($u->ID, '_admin_temp_password', true);
                                                    if (!empty($u_pass_saved)) : ?>
                                                        <div class="user-pass-inline-wrap">
                                                            <input type="password" readonly value="<?php echo esc_attr($u_pass_saved); ?>" class="input-user-pass-inline" />
                                                            <button type="button" class="btn-toggle-inline-pass" title="Hiện / Ẩn mật khẩu">
                                                                <i class="fa-regular fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn-copy-inline-pass" data-pass="<?php echo esc_attr($u_pass_saved); ?>" title="Sao chép mật khẩu">
                                                                <i class="fa-regular fa-copy"></i>
                                                            </button>
                                                            <button type="button" class="btn-inline-change-pass btn-action-manage-user-pass" 
                                                                    data-user-id="<?php echo $u->ID; ?>"
                                                                    data-user-login="<?php echo esc_attr($u->user_login); ?>"
                                                                    data-display-name="<?php echo esc_attr($u->display_name); ?>"
                                                                    data-email="<?php echo esc_attr($u->user_email); ?>"
                                                                    title="Đổi mật khẩu tài khoản này">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </button>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="user-pass-inline-wrap">
                                                            <input type="password" readonly value="••••••••" class="input-user-pass-inline" style="opacity: 0.6;" />
                                                            <button type="button" class="btn-inline-change-pass btn-action-manage-user-pass" 
                                                                    data-user-id="<?php echo $u->ID; ?>"
                                                                    data-user-login="<?php echo esc_attr($u->user_login); ?>"
                                                                    data-display-name="<?php echo esc_attr($u->display_name); ?>"
                                                                    data-email="<?php echo esc_attr($u->user_email); ?>"
                                                                    title="Bấm để đặt mật khẩu cho tài khoản này">
                                                                <i class="fa-solid fa-key" style="color: #f59e0b;"></i>
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Cột Vai Trò Inline Selector -->
                                                <td style="text-align: center; white-space: nowrap;">
                                                    <select class="form-input db-role-select col-user-role-select" onchange="jQuery(this).trigger('change_role', [<?php echo $u->ID; ?>, this.value]);">
                                                        <option value="doc_gia" <?php selected($r, 'doc_gia'); ?>>Độc Giả</option>
                                                        <option value="dich_gia" <?php selected($r, 'dich_gia'); ?>>Dịch Giả</option>
                                                        <option value="administrator" <?php selected($r, 'administrator'); ?>>Quản Trị Viên</option>
                                                    </select>
                                                </td>

                                                <!-- Cột Ngày Đăng Ký -->
                                                <td style="text-align: center; white-space: nowrap;">
                                                    <span class="db-registered-date"><?php echo date('d/m/Y', strtotime($u->user_registered)); ?></span>
                                                </td>

                                                <!-- Cột Trạng Thái -->
                                                <td class="col-user-status-cell" style="text-align: center; white-space: nowrap;">
                                                    <?php if ($is_locked) : ?>
                                                        <span class="user-status-badge status-badge-locked"><i class="fa-solid fa-lock"></i> Đã khóa</span>
                                                    <?php else : ?>
                                                        <span class="user-status-badge status-badge-active"><i class="fa-solid fa-circle-check"></i> Hoạt động</span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Cột Hành Động (Dấu 3 chấm dọc: Sửa, Xóa, Khóa, Chi tiết) -->
                                                <td style="text-align: center; white-space: nowrap;">
                                                <div class="story-actions-dropdown user-actions-dropdown">
                                                    <button type="button" class="btn-story-dropdown-trigger btn-user-dropdown-trigger" title="Tác vụ người dùng" data-user-id="<?php echo $u->ID; ?>">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <div class="story-dropdown-menu">
                                                        <!-- 1. Sửa -->
                                                        <button type="button" class="story-dropdown-item btn-action-edit-user"
                                                            data-user-id="<?php echo $u->ID; ?>"
                                                            data-user-login="<?php echo esc_attr($u->user_login); ?>"
                                                            data-display-name="<?php echo esc_attr($u->display_name); ?>"
                                                            data-email="<?php echo esc_attr($u->user_email); ?>"
                                                            data-role="<?php echo esc_attr($r); ?>">
                                                            <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Sửa
                                                        </button>

                                                        <!-- 2. Sửa, xem mật khẩu (MỚI) -->
                                                        <button type="button" class="story-dropdown-item btn-action-manage-user-pass"
                                                            data-user-id="<?php echo $u->ID; ?>"
                                                            data-user-login="<?php echo esc_attr($u->user_login); ?>"
                                                            data-display-name="<?php echo esc_attr($u->display_name); ?>"
                                                            data-email="<?php echo esc_attr($u->user_email); ?>">
                                                            <i class="fa-solid fa-key" style="color: #f59e0b;"></i> Sửa, xem mật khẩu
                                                        </button>

                                                        <?php if ($u->ID != get_current_user_id()) : ?>
                                                            <!-- 2. Xóa -->
                                                            <button type="button" class="story-dropdown-item item-danger btn-action-confirm-delete-user"
                                                                data-user-id="<?php echo $u->ID; ?>"
                                                                data-name="<?php echo esc_attr($u->display_name); ?>">
                                                                <i class="fa-solid fa-trash-can" style="color: #dc2626;"></i> Xóa
                                                            </button>

                                                            <!-- 3. Khóa / Mở khóa tài khoản (Xác nhận trước khi thực hiện) -->
                                                            <button type="button" class="story-dropdown-item btn-action-confirm-toggle-lock"
                                                                data-user-id="<?php echo $u->ID; ?>"
                                                                data-name="<?php echo esc_attr($u->display_name); ?>"
                                                                data-locked="<?php echo $is_locked ? '1' : '0'; ?>">
                                                                <i class="fa-solid <?php echo $is_locked ? 'fa-unlock' : 'fa-lock'; ?>" style="color: #ea580c;"></i>
                                                                <span class="user-lock-toggle-label"><?php echo $is_locked ? 'Mở khóa tài khoản' : 'Khóa tài khoản'; ?></span>
                                                            </button>
                                                        <?php endif; ?>

                                                        <div class="story-dropdown-divider" style="margin: 4px 0; border-top: 1px solid var(--border-color);"></div>

                                                        <!-- 4. Chi tiết (hiển thị popup có tất cả thông tin của user) -->
                                                        <button type="button" class="story-dropdown-item btn-action-user-full-details"
                                                            data-user-id="<?php echo $u->ID; ?>">
                                                            <i class="fa-solid fa-id-card" style="color: var(--primary-green);"></i> Chi tiết
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>

                    <!-- STATE 2: HIỂN THỊ CHI TIẾT CỤ THỂ CỦA USER ĐƯỢC CHỌN (DETAIL HIERARCHICAL VIEW) -->
                    <div id="admUserDetailView" class="adm-subview-panel" style="display: none;">
                        <!-- Thanh Điều Hướng Quay Lại -->
                        <div class="adm-detail-navigation">
                            <button type="button" class="btn btn-secondary btn-sm" id="btnBackToUserMaster">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách tất cả người dùng
                            </button>
                            <div class="adm-detail-nav-title">
                                <i class="fa-solid fa-id-card-clip" style="color: var(--primary-green);"></i> Đang xem chi tiết thông tin tài khoản người dùng
                            </div>
                        </div>

                        <!-- Card Thông Tin Chi Tiết Hồ Sơ User -->
                        <div class="db-detail-header-card" id="userDetailHeaderCard">
                            <div class="db-user-avatar-large" id="userDetailAvatarBox">
                                <span id="userDetailAvatarChar">U</span>
                            </div>
                            <div class="db-detail-info-box">
                                <div class="db-detail-title-row">
                                    <h2 class="db-detail-title" id="userDetailDisplayName">...</h2>
                                    <div class="db-detail-badges" id="userDetailBadges">
                                        <!-- User badges -->
                                    </div>
                                </div>

                                <div class="db-detail-meta-grid">
                                    <div><i class="fa-solid fa-at"></i> <strong>Username:</strong> <span id="userDetailLogin">--</span></div>
                                    <div><i class="fa-solid fa-envelope"></i> <strong>Email:</strong> <span id="userDetailEmail">--</span></div>
                                    <div><i class="fa-solid fa-fingerprint"></i> <strong>ID:</strong> <span id="userDetailId">--</span></div>
                                    <div><i class="fa-solid fa-calendar-days"></i> <strong>Đăng ký:</strong> <span id="userDetailRegistered">--</span></div>
                                    <div><i class="fa-solid fa-book"></i> <strong>Số bộ truyện:</strong> <span id="userDetailTotalStories">0</span></div>
                                    <div><i class="fa-solid fa-eye"></i> <strong>Lượt xem tháng:</strong> <span id="userDetailViewsMonth">0</span></div>
                                </div>

                                <!-- Box Thông Tin Tài Khoản Ngân Hàng (Nếu Có) -->
                                <div class="db-bank-info-box" id="userDetailBankBox">
                                    <div class="db-bank-title"><i class="fa-solid fa-building-columns"></i> Thông Tin Ngân Hàng Nhận Nhuận Bút:</div>
                                    <div class="db-bank-details" id="userDetailBankText">Chưa cập nhật thông tin ngân hàng</div>
                                </div>

                                <!-- Thanh Hành Động Thao Tác Với User -->
                                <div class="db-detail-actions-row">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <label style="font-size: 12.5px; font-weight: 600;">Đổi quyền:</label>
                                        <select id="selectDetailUserRole" class="form-input" style="height: 32px; font-size: 12.5px; width: 140px;">
                                            <option value="doc_gia">Độc Giả</option>
                                            <option value="dich_gia">Dịch Giả</option>
                                            <option value="administrator">Quản Trị Viên</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-outline btn-sm" id="btnDetailToggleLockUser" data-user-id="">
                                        <i class="fa-solid fa-lock"></i> Khóa Tài Khoản
                                    </button>
                                    <button type="button" class="btn btn-outline btn-sm btn-action-manage-user-pass" id="btnDetailChangeUserPass"
                                        data-user-id=""
                                        data-user-login=""
                                        data-display-name=""
                                        data-email=""
                                        style="border-color: #f59e0b; color: #d97706;">
                                        <i class="fa-solid fa-key"></i> Sửa, xem mật khẩu
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- BẢNG PHÂN CẤP CHI TIẾT TRUYỆN ĐÃ ĐĂNG CỦA USER NÀY (CHILD SUB-TABLE) -->
                        <div class="db-subtable-wrapper">
                            <div class="db-subtable-header">
                                <div class="db-subtable-title">
                                    <i class="fa-solid fa-book-bookmark"></i>
                                    <span>Danh Sách Truyện Do Dịch Giả / Người Dùng Này Đăng Tải (<strong id="userDetailStoriesCount">0</strong> bộ)</span>
                                </div>
                            </div>

                            <div class="table-responsive db-table-container">
                                <table class="data-table db-data-table" id="tableUserDetailStories">
                                    <thead>
                                        <tr>
                                            <th>Tên Truyện</th>
                                            <th style="width: 120px;">Trạng Thái</th>
                                            <th style="width: 110px;">Số Chương</th>
                                            <th style="width: 130px;">Views Tháng</th>
                                            <th style="width: 130px;">Tổng Views</th>
                                            <th style="width: 150px;">Nhuận Bút Tạm Tính</th>
                                            <th style="width: 140px; text-align: center;">Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userDetailStoriesTbody">
                                        <!-- Loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 3: LINK SHOPEE & TIKTOK + THỐNG KÊ CLICKS
                     ================================================================= -->
                <section id="admTabAffiliate" class="dash-tab-pane">
                    <div id="affiliateAlert" class="alert-box"></div>

                    <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 24px;">
                        <!-- Form Cấu hình Link -->
                        <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <h3 style="font-size: 15px; font-weight: 700; color: var(--primary-green); margin-bottom: 14px;">
                                <i class="fa-solid fa-gear"></i> Cài Đặt Liên Kết Tiếp Thị (Affiliate Links)
                            </h3>

                            <form id="formUpdateAffiliateLinks">
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 12.5px;">
                                        <i class="fa-solid fa-bag-shopping" style="color: #EE4D2D;"></i> Link Shopee (Bắt buộc click từ chương 2)
                                    </label>
                                    <input type="url" name="shopee_url" class="form-input" value="<?php echo esc_url($current_shopee_url); ?>" required />
                                    <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 3px;">
                                        Độc giả phải click link này để mở khóa đọc tiếp ở Chương 2, 5, 8...
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size: 12.5px;">
                                        <i class="fa-brands fa-tiktok" style="color: #212121;"></i> Link TikTok Shop
                                    </label>
                                    <input type="url" name="tiktok_url" class="form-input" value="<?php echo esc_url($current_tiktok_url); ?>" required />
                                    <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 3px;">
                                        Kích hoạt chuyển hướng nền khi tương tác.
                                    </small>
                                </div>

                                <button type="submit" class="btn btn-primary" style="margin-top: 4px; padding: 7px 16px; font-size: 13px;">
                                    <i class="fa-solid fa-floppy-disk"></i> Lưu Cấu Hình Link
                                </button>
                            </form>
                        </div>

                        <!-- Báo cáo tổng click -->
                        <div style="background: var(--bg-card); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
                            <h3 style="font-size: 15px; font-weight: 700; color: var(--primary-green); margin-bottom: 14px;">
                                <i class="fa-solid fa-chart-pie"></i> Tổng Số Lượt Click Vào Link
                            </h3>

                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="flex: 1; background: #FFF3E0; border: 1px solid #FFE082; padding: 14px; border-radius: var(--radius-md); text-align: center;">
                                    <i class="fa-solid fa-bag-shopping" style="font-size: 24px; color: #EE4D2D; margin-bottom: 6px;"></i>
                                    <div style="font-size: 22px; font-weight: 800; color: #D84315;"><?php echo number_format($shopee_clicks); ?></div>
                                    <span style="font-size: 12px; color: #E65100; font-weight: 600;">Click Shopee</span>
                                </div>

                                <div style="flex: 1; background: #EDE7F6; border: 1px solid #D1C4E9; padding: 14px; border-radius: var(--radius-md); text-align: center;">
                                    <i class="fa-brands fa-tiktok" style="font-size: 24px; color: #212121; margin-bottom: 6px;"></i>
                                    <div style="font-size: 22px; font-weight: 800; color: #4A148C;"><?php echo number_format($tiktok_clicks); ?></div>
                                    <span style="font-size: 12px; color: #4A148C; font-weight: 600;">Click TikTok</span>
                                </div>
                            </div>

                            <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0;">
                                <i class="fa-solid fa-circle-info"></i> Hệ thống tự động ghi nhận địa chỉ IP, trình duyệt và chương truyện khi độc giả click mở khóa.
                            </p>
                        </div>
                    </div>

                    <!-- Bảng Nhật ký Clicks Gần Nhất -->
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--primary-green); margin-bottom: 12px;">
                        <i class="fa-solid fa-list-check"></i> Nhật Ký Lượt Click Gần Đây Nhất
                    </h3>
                    <div class="table-responsive db-table-container">
                        <table class="data-table db-data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nền Tảng</th>
                                    <th>Chương Mở Khóa</th>
                                    <th>Địa Chỉ IP</th>
                                    <th>Thời Gian</th>
                                    <th>Liên Kết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_clicks)) : ?>
                                    <?php foreach ($recent_clicks as $clk) : ?>
                                        <tr>
                                            <td>#<?php echo $clk->id; ?></td>
                                            <td>
                                                <?php if ($clk->link_type === 'shopee') : ?>
                                                    <span class="badge" style="background:#FFF3E0; color:#D84315;"><i class="fa-solid fa-bag-shopping"></i> Shopee</span>
                                                <?php else : ?>
                                                    <span class="badge" style="background:#EDE7F6; color:#4A148C;"><i class="fa-brands fa-tiktok"></i> TikTok</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>Chương <?php echo $clk->chapter_num ?: 'Chung'; ?></td>
                                            <td style="font-family: monospace; font-size: 12px;"><?php echo esc_html($clk->ip ?: '127.0.0.1'); ?></td>
                                            <td style="font-size: 12px; color: var(--text-muted);"><?php echo date('d/m/Y H:i:s', strtotime($clk->created_at)); ?></td>
                                            <td style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 12px;">
                                                <a href="<?php echo esc_url($clk->url); ?>" target="_blank" style="color: var(--primary-green);"><?php echo esc_html($clk->url); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">Chưa có lượt click nào được ghi nhận.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 4: TIỀN LƯƠNG DỊCH GIẢ (8Đ / VIEW)
                     ================================================================= -->
                <section id="admTabSalaries" class="dash-tab-pane">
                    <?php $rate = (int) get_option('muop_salary_rate', 8); ?>
                    <div style="background: linear-gradient(135deg, #E8F5E9, #DCEDC8); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h3 style="font-size: 16px; font-weight: 800; color: var(--primary-green); margin: 0;">
                                <i class="fa-solid fa-calculator"></i> Bảng Kê Quyết Toán Nhuận Bút Dịch Giả
                            </h3>
                            <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 3px; margin-bottom: 0;">
                                Định mức tính thưởng: <strong><?php echo $rate; ?>đ</strong> / 1 lượt xem (view) • Chu kỳ quyết toán: Hàng tháng
                            </p>
                        </div>
                        <div style="font-size: 13.5px; font-weight: 700; color: var(--primary-green); background: #fff; padding: 6px 16px; border-radius: var(--radius-full); box-shadow: var(--shadow-sm);">
                            Kỳ hiện tại: Tháng <?php echo date('m/Y'); ?>
                        </div>
                    </div>

                    <div class="table-responsive db-table-container">
                        <table class="data-table db-data-table">
                            <thead>
                                <tr>
                                    <th>Dịch Giả</th>
                                    <th>Email</th>
                                    <th>Số Truyện</th>
                                    <th>Lượt Xem Tháng</th>
                                    <th>Nhuận Bút Dự Kiến</th>
                                    <th>Thông Tin Ngân Hàng</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 170px; text-align: center;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($translators as $tr) : 
                                    $tr_stories = get_posts(array('post_type' => 'truyen', 'posts_per_page' => -1, 'author' => $tr->ID));
                                    $tr_count = count($tr_stories);
                                    
                                    $tr_views_month = 0;
                                    foreach ($tr_stories as $ts) {
                                        $vm = (int) get_post_meta($ts->ID, '_truyen_views_' . $current_month_str, true);
                                        $v_all = (int) get_post_meta($ts->ID, '_truyen_views', true);
                                        $tr_views_month += ($vm ?: $v_all);
                                    }

                                    $sal_cur = $tr_views_month * $rate;

                                    $tr_bank_name   = get_user_meta($tr->ID, '_muop_bank_name', true);
                                    $tr_bank_holder = get_user_meta($tr->ID, '_muop_bank_account_holder', true);
                                    $tr_bank_num    = get_user_meta($tr->ID, '_muop_bank_account_number', true);
                                    $tr_bank_str    = $tr_bank_num ? ($tr_bank_name . ' - ' . $tr_bank_num . ' (' . $tr_bank_holder . ')') : '';
                                ?>
                                    <tr>
                                        <td><strong><?php echo esc_html($tr->display_name); ?></strong></td>
                                        <td><?php echo esc_html($tr->user_email); ?></td>
                                        <td><strong><?php echo $tr_count; ?></strong> bộ</td>
                                        <td><i class="fa-solid fa-eye"></i> <?php echo number_format($tr_views_month); ?></td>
                                        <td style="font-size: 14.5px; font-weight: 800; color: #2E7D32;">
                                            <?php echo number_format($sal_cur); ?> VNĐ
                                        </td>
                                        <td style="font-size: 12px;">
                                            <?php if (!empty($tr_bank_num)) : ?>
                                                <strong><?php echo esc_html($tr_bank_name); ?></strong><br>
                                                <span style="font-family: monospace; font-size: 12px;"><?php echo esc_html($tr_bank_num); ?></span><br>
                                                <span style="color: var(--text-muted);"><?php echo esc_html($tr_bank_holder); ?></span>
                                            <?php else : ?>
                                                <span style="color: #ea580c; font-size: 11.5px;"><i class="fa-solid fa-triangle-exclamation"></i> Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-full"><i class="fa-solid fa-check"></i> Sẵn sàng thanh toán</span>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-primary btn-xs btn-open-pay-modal" 
                                                    data-translator-id="<?php echo $tr->ID; ?>" 
                                                    data-translator-name="<?php echo esc_attr($tr->display_name); ?>" 
                                                    data-amount="<?php echo $sal_cur; ?>" 
                                                    data-views="<?php echo $tr_views_month; ?>" 
                                                    data-period="<?php echo date('m/Y'); ?>" 
                                                    data-bank="<?php echo esc_attr($tr_bank_str); ?>">
                                                <i class="fa-solid fa-receipt"></i> Thanh Toán & Bill
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Lịch sử quyết toán -->
                    <div style="margin-top: 28px;">
                        <h4 style="font-size: 14.5px; font-weight: 700; color: var(--primary-green); margin-bottom: 12px;">
                            <i class="fa-solid fa-clock-rotate-left"></i> Lịch Sử Quyết Toán & Bill Đã Thanh Toán Gần Đây
                        </h4>
                        <?php
                        $table_payments = $wpdb->prefix . 'muop_translator_payments';
                        $recent_payments = $wpdb->get_results("SELECT * FROM $table_payments ORDER BY id DESC LIMIT 20");
                        ?>
                        <div class="table-responsive db-table-container">
                            <table class="data-table db-data-table" style="font-size: 12.5px;">
                                <thead>
                                    <tr>
                                        <th>Kỳ</th>
                                        <th>Dịch Giả</th>
                                        <th>Lượt Xem</th>
                                        <th>Số Tiền</th>
                                        <th>Ngân Hàng</th>
                                        <th>Thời Gian</th>
                                        <th>Bill Chuyển Khoản</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_payments)) : ?>
                                        <?php foreach ($recent_payments as $rp) : 
                                            $rp_tr = get_userdata($rp->translator_id);
                                        ?>
                                            <tr>
                                                <td><strong>Tháng <?php echo esc_html($rp->period); ?></strong></td>
                                                <td><strong><?php echo esc_html($rp_tr ? $rp_tr->display_name : 'ID #' . $rp->translator_id); ?></strong></td>
                                                <td><i class="fa-solid fa-eye"></i> <?php echo number_format($rp->views); ?></td>
                                                <td style="color: #2E7D32; font-weight: 800;"><?php echo number_format($rp->amount); ?> VNĐ</td>
                                                <td style="font-size: 12px;"><?php echo esc_html($rp->bank_name . ' - ' . $rp->bank_account_number); ?></td>
                                                <td style="font-size: 12px; color: var(--text-muted);"><?php echo date('d/m/Y H:i', strtotime($rp->created_at)); ?></td>
                                                <td>
                                                    <?php if (!empty($rp->bill_image)) : ?>
                                                        <button type="button" class="btn btn-secondary btn-xs btn-view-bill" data-bill-url="<?php echo esc_url($rp->bill_image); ?>">
                                                            <i class="fa-solid fa-receipt"></i> Xem Bill
                                                        </button>
                                                    <?php else : ?>
                                                        <span style="color: var(--text-muted); font-size: 11px;">Không có</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 20px; color: var(--text-muted);">Chưa có lịch sử thanh toán nào được ghi nhận.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 5: QUẢN LÝ THỂ LOẠI
                     ================================================================= -->
                <section id="admTabGenres" class="dash-tab-pane">
                    <!-- Hộp Thêm Thể Loại Mới Cho Admin -->
                    <div id="admBoxAddGenre" style="display: none; margin-bottom: 20px; padding: 16px; background: var(--bg-card); border: 1.5px solid var(--primary-green); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                        <div style="font-weight: 700; font-size: 13.5px; color: var(--primary-green); margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-folder-plus"></i> Thêm Thể Loại Mới Vào Hệ Thống
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <input type="text" id="admInputNewGenre" class="form-input" placeholder="Nhập tên thể loại mới (vd: Trinh thám, Kinh dị, Xuyên không...)" style="flex: 1; min-width: 240px; height: 38px; font-size: 13.5px;" />
                            <button type="button" id="admBtnConfirmAddGenre" class="btn btn-primary" style="height: 38px; padding: 0 18px; font-size: 13px; white-space: nowrap;">
                                <i class="fa-solid fa-check"></i> Lưu Thể Loại
                            </button>
                            <button type="button" id="admBtnCancelAddGenre" class="btn btn-secondary" style="height: 38px; padding: 0 14px; font-size: 13px;">
                                Hủy
                            </button>
                        </div>
                        <div id="admAddGenreStatus" style="font-size: 12.5px; margin-top: 8px; display: none;"></div>
                    </div>

                    <!-- Danh Sách Thẻ Thể Loại (ẢNH 1: Dấu 3 chấm dọc - Sửa, Xóa, Chi tiết) -->
                    <div id="admGenresGrid" class="adm-genres-grid">
                        <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                            <?php foreach ($all_genres as $g) : ?>
                                <div class="adm-genre-card" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr(mb_strtolower($g->name, 'UTF-8')); ?>" style="background: var(--bg-card); padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: var(--shadow-sm); position: relative;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                        <span class="adm-genre-title" style="font-weight: 700; color: var(--text-main); font-size: 14.5px; word-break: break-word;"><?php echo esc_html($g->name); ?></span>
                                        <span class="badge badge-green" style="font-size: 11px; white-space: nowrap;"><?php echo (int)$g->count; ?> truyện</span>
                                    </div>
                                    <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 8px; border-top: 1px dashed var(--border-light);">
                                        <div class="story-actions-dropdown genre-actions-dropdown">
                                            <button type="button" class="btn-story-dropdown-trigger btn-genre-dropdown-trigger" title="Tác vụ thể loại" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr($g->name); ?>" data-count="<?php echo (int)$g->count; ?>">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="story-dropdown-menu">
                                                <button type="button" class="story-dropdown-item adm-btn-edit-genre" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr($g->name); ?>">
                                                    <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Sửa
                                                </button>
                                                <button type="button" class="story-dropdown-item adm-btn-delete-genre" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr($g->name); ?>" style="color: #ef4444;">
                                                    <i class="fa-solid fa-trash-can"></i> Xóa
                                                </button>
                                                <button type="button" class="story-dropdown-item adm-btn-detail-genre" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr($g->name); ?>" data-count="<?php echo (int)$g->count; ?>">
                                                    <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Chi tiết
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Thông báo khi không tìm thấy kết quả -->
                    <div id="admNoGenreFound" style="display: none; padding: 24px; text-align: center; color: var(--text-muted); font-size: 14px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px dashed var(--border-color); margin-top: 10px;">
                        <i class="fa-solid fa-circle-question" style="font-size: 20px; margin-bottom: 6px; display: block;"></i>
                        Không tìm thấy thể loại nào phù hợp với từ khóa!
                    </div>

                    <!-- Modal Sửa Tên Thể Loại -->
                    <div id="admModalEditGenre" class="adm-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; align-items: center; justify-content: center; padding: 16px;">
                        <div style="background: var(--bg-card); width: 100%; max-width: 420px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden;">
                            <div style="padding: 14px 18px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
                                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Sửa Tên Thể Loại
                                </h4>
                                <button type="button" id="admBtnCloseEditModal" style="background: none; border: none; font-size: 16px; color: var(--text-muted); cursor: pointer;" title="Đóng">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div style="padding: 18px;">
                                <input type="hidden" id="admEditGenreId" value="" />
                                <div class="form-group" style="margin-bottom: 14px;">
                                    <label class="form-label" for="admEditGenreName" style="font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tên thể loại:</label>
                                    <input type="text" id="admEditGenreName" class="form-input" style="width: 100%; height: 38px; font-size: 13.5px;" />
                                </div>
                                <div id="admEditGenreStatus" style="font-size: 12.5px; margin-bottom: 12px; display: none;"></div>
                                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                    <button type="button" id="admBtnCancelEditModal" class="btn btn-secondary" style="padding: 7px 14px; font-size: 13px;">Hủy bỏ</button>
                                    <button type="button" id="admBtnSaveEditGenre" class="btn btn-primary" style="padding: 7px 18px; font-size: 13px;">
                                        <i class="fa-solid fa-check"></i> Lưu Thay Đổi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 5.1: QUẢN LÝ TỪ NHẠY CẢM & KIỂM DUYỆT (THIẾT KẾ GIỐNG HÌNH 2)
                     ================================================================= -->
                <section id="admTabSensitiveWords" class="dash-tab-pane">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <h3 style="font-size: 17px; font-weight: 750; color: var(--primary-green); margin: 0; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-shield-halved"></i> Danh Sách Từ Ngữ Nhạy Cảm & Kiểm Duyệt
                            </h3>
                            <span class="badge badge-hot" id="admSensitiveWordsTotalBadge" style="font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                                <?php echo count($all_sensitive_words); ?> từ
                            </span>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <div style="position: relative; width: 220px;">
                                <input type="text" id="admSearchSensitiveWord" class="form-input" placeholder="Tìm kiếm từ nhạy cảm..." style="height: 36px; font-size: 13px; padding-left: 32px; width: 100%; border-radius: 6px;" />
                                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 13px; color: var(--text-muted); pointer-events: none;"></i>
                            </div>
                            <button type="button" id="admBtnToggleAddWord" class="btn btn-primary" style="height: 36px; padding: 0 16px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-plus"></i> Thêm Từ Mới
                            </button>
                        </div>
                    </div>

                    <!-- Hộp Thêm Từ Mới Cho Admin -->
                    <div id="admBoxAddWord" style="display: none; margin-bottom: 20px; padding: 16px; background: var(--bg-card); border: 1.5px solid var(--primary-green); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                        <div style="font-weight: 700; font-size: 13.5px; color: var(--primary-green); margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-shield-plus"></i> Thêm Từ Ngữ Nhạy Cảm Mới Vào Hệ Thống
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <input type="text" id="admInputNewWord" class="form-input" placeholder="Nhập từ hoặc cụm từ nhạy cảm mới (vd: chửi thề, 18+, bạo lực...)" style="flex: 1; min-width: 240px; height: 38px; font-size: 13.5px;" />
                            <button type="button" id="admBtnConfirmAddWord" class="btn btn-primary" style="height: 38px; padding: 0 18px; font-size: 13px; white-space: nowrap;">
                                <i class="fa-solid fa-check"></i> Lưu Từ Mới
                            </button>
                            <button type="button" id="admBtnCancelAddWord" class="btn btn-secondary" style="height: 38px; padding: 0 14px; font-size: 13px;">
                                Hủy
                            </button>
                        </div>
                        <div id="admAddWordStatus" style="font-size: 12.5px; margin-top: 8px; display: none;"></div>
                    </div>

                    <!-- Danh Sách Thẻ Từ Nhạy Cảm - THIẾT KẾ GIỐNG HỆT HÌNH 2 -->
                    <div id="admSensitiveWordsGrid" class="adm-genres-grid">
                        <?php if (!empty($all_sensitive_words)) : ?>
                            <?php foreach ($all_sensitive_words as $w) : ?>
                                <div class="adm-genre-card adm-word-card" data-word="<?php echo esc_attr(mb_strtolower($w, 'UTF-8')); ?>" style="background: var(--bg-card); padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: var(--shadow-sm); position: relative;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                        <span class="adm-genre-title adm-word-title" style="font-weight: 700; color: var(--text-main); font-size: 14.5px; word-break: break-word;"><?php echo esc_html($w); ?></span>
                                        <span class="badge badge-hot" style="font-size: 11px; white-space: nowrap; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;">Từ cấm</span>
                                    </div>
                                    <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 8px; border-top: 1px dashed var(--border-light);">
                                        <div class="story-actions-dropdown genre-actions-dropdown">
                                            <button type="button" class="btn-story-dropdown-trigger btn-genre-dropdown-trigger" title="Tác vụ từ nhạy cảm" data-word="<?php echo esc_attr($w); ?>">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="story-dropdown-menu">
                                                <button type="button" class="story-dropdown-item adm-btn-edit-word" data-word="<?php echo esc_attr($w); ?>">
                                                    <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Sửa
                                                </button>
                                                <button type="button" class="story-dropdown-item adm-btn-delete-word" data-word="<?php echo esc_attr($w); ?>" style="color: #ef4444;">
                                                    <i class="fa-solid fa-trash-can"></i> Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Thông báo khi không tìm thấy kết quả -->
                    <div id="admNoWordFound" style="display: none; padding: 24px; text-align: center; color: var(--text-muted); font-size: 14px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px dashed var(--border-color); margin-top: 10px;">
                        <i class="fa-solid fa-circle-question" style="font-size: 20px; margin-bottom: 6px; display: block;"></i>
                        Không tìm thấy từ nhạy cảm nào phù hợp với từ khóa!
                    </div>

                    <!-- Modal Sửa Từ Nhạy Cảm -->
                    <div id="admModalEditWord" class="adm-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; align-items: center; justify-content: center; padding: 16px;">
                        <div style="background: var(--bg-card); width: 100%; max-width: 420px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden;">
                            <div style="padding: 14px 18px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
                                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Sửa Từ Nhạy Cảm
                                </h4>
                                <button type="button" id="admBtnCloseEditWordModal" style="background: none; border: none; font-size: 16px; color: var(--text-muted); cursor: pointer;" title="Đóng">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div style="padding: 18px;">
                                <input type="hidden" id="admEditOldWord" value="" />
                                <div class="form-group" style="margin-bottom: 14px;">
                                    <label class="form-label" for="admEditWordInput" style="font-size: 13px; font-weight: 600; margin-bottom: 6px;">Từ nhạy cảm:</label>
                                    <input type="text" id="admEditWordInput" class="form-input" style="width: 100%; height: 38px; font-size: 13.5px;" />
                                </div>
                                <div id="admEditWordStatus" style="font-size: 12.5px; margin-bottom: 12px; display: none;"></div>
                                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                    <button type="button" id="admBtnCancelEditWordModal" class="btn btn-secondary" style="padding: 7px 14px; font-size: 13px;">Hủy bỏ</button>
                                    <button type="button" id="admBtnSaveEditWord" class="btn btn-primary" style="padding: 7px 18px; font-size: 13px;">
                                        <i class="fa-solid fa-check"></i> Lưu Thay Đổi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 6: BÌNH LUẬN & ĐÁNH GIÁ
                     ================================================================= -->
                <section id="admTabComments" class="dash-tab-pane">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                        <i class="fa-solid fa-comments"></i> Bình Luận & Đánh Giá Mới Nhất
                    </h3>
                    <ul class="comment-list-ul">
                        <?php
                        $recent_all_comments = get_comments(array('number' => 20, 'status' => 'approve'));
                        if (!empty($recent_all_comments)) :
                            foreach ($recent_all_comments as $cmt) :
                                $cmt_p = get_post($cmt->comment_post_ID);
                        ?>
                            <li class="comment-item" id="admin-comment-<?php echo $cmt->comment_ID; ?>">
                                <div class="comment-avatar">
                                    <?php echo esc_html(strtoupper(substr($cmt->comment_author, 0, 1))); ?>
                                </div>
                                <div class="comment-body">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                        <div>
                                            <span class="comment-author-name"><?php echo esc_html($cmt->comment_author); ?></span>
                                            <span class="comment-time"><?php echo esc_html(muop_time_ago(strtotime($cmt->comment_date))); ?></span>
                                            <span style="font-size: 12px; color: var(--text-muted); margin-left: 8px;">
                                                trong bài: <strong><?php echo esc_html($cmt_p ? $cmt_p->post_title : ''); ?></strong>
                                            </span>
                                        </div>
                                        <button type="button" class="btn-delete-comment" data-comment-id="<?php echo (int) $cmt->comment_ID; ?>" title="Xóa bình luận này">
                                            <i class="fa-solid fa-trash-can"></i> Xóa
                                        </button>
                                    </div>
                                    <div class="comment-text"><?php echo muop_render_comment_content($cmt->comment_content); ?></div>
                                </div>
                            </li>
                        <?php 
                            endforeach;
                        else :
                            echo '<p style="color: var(--text-muted); padding: 20px;">Chưa có bình luận nào.</p>';
                        endif;
                        ?>
                    </ul>
                </section>

                <!-- =================================================================
                     TAB 7: BÁO LỖI CHƯƠNG & KHIẾU NẠI
                     ================================================================= -->
                <section id="admTabReports" class="dash-tab-pane">
                    <div style="margin-bottom: 16px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 4px;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Báo Lỗi Chương Truyện & Khiếu Nại (<?php echo count($all_reports); ?>)
                        </h3>
                        <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">
                            Danh sách lỗi do độc giả báo về. Bạn có thể phản hồi và cập nhật trạng thái xử lý trực tiếp.
                        </p>
                    </div>

                    <div class="table-responsive db-table-container">
                        <table class="data-table db-data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người Báo Lỗi</th>
                                    <th>Truyện & Chương</th>
                                    <th>Loại Lỗi</th>
                                    <th>Nội Dung Mô Tả</th>
                                    <th>Thời Gian</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 130px; text-align: center;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($all_reports)) : ?>
                                    <?php foreach ($all_reports as $rep) : 
                                        $rep_story = get_post($rep->story_id);
                                        $rep_user = $rep->user_id ? get_userdata($rep->user_id) : null;
                                        $rep_user_name = $rep_user ? $rep_user->display_name : 'Độc giả ẩn danh';
                                    ?>
                                        <tr>
                                            <td>#<?php echo $rep->id; ?></td>
                                            <td><strong><?php echo esc_html($rep_user_name); ?></strong></td>
                                            <td>
                                                <a href="<?php echo esc_url(get_permalink($rep->chapter_id)); ?>" target="_blank" style="color: var(--primary-green); font-weight: 600;">
                                                    <?php echo esc_html($rep_story ? $rep_story->post_title : 'Truyện'); ?> - Chương <?php echo esc_html($rep->chapter_num); ?>
                                                </a>
                                            </td>
                                            <td><span class="badge" style="background:#FEE2E2; color:#DC2626;"><?php echo esc_html($rep->issue_type); ?></span></td>
                                            <td style="max-width: 250px; font-size: 12.5px;">
                                                <?php echo esc_html($rep->description); ?>
                                                <?php if (!empty($rep->admin_reply)) : ?>
                                                    <div style="margin-top: 4px; padding: 4px 8px; background: #E8F5E9; border-radius: 4px; color: #2E7D32; font-size: 11.5px;">
                                                        <strong>Phản hồi:</strong> <?php echo esc_html($rep->admin_reply); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td style="font-size: 12px; color: var(--text-muted);"><?php echo date('d/m/Y H:i', strtotime($rep->created_at)); ?></td>
                                            <td>
                                                <?php if ($rep->status === 'resolved') : ?>
                                                    <span class="badge badge-full"><i class="fa-solid fa-check"></i> Đã xử lý</span>
                                                <?php else : ?>
                                                    <span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ xử lý</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-primary btn-xs btn-open-reply-report" 
                                                        data-report-id="<?php echo $rep->id; ?>" 
                                                        data-story="<?php echo esc_attr($rep_story ? $rep_story->post_title : ''); ?>" 
                                                        data-chapter-num="<?php echo $rep->chapter_num; ?>" 
                                                        data-reply="<?php echo esc_attr($rep->admin_reply); ?>" 
                                                        data-status="<?php echo esc_attr($rep->status); ?>">
                                                    <i class="fa-solid fa-reply"></i> Phản Hồi
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 25px; color: var(--text-muted);">Hiện không có báo lỗi chương nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- =================================================================
                     TAB 8: PHÁT THÔNG BÁO HỆ THỐNG
                     ================================================================= -->
                <section id="admTabBroadcast" class="dash-tab-pane">
                    <div style="margin-bottom: 16px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 4px;">
                            <i class="fa-solid fa-bullhorn"></i> Phát Thông Báo Hệ Thống (Broadcast)
                        </h3>
                        <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">
                            Gửi thông báo tới toàn bộ người dùng, hoặc riêng nhóm dịch giả / độc giả trên hệ thống.
                        </p>
                    </div>

                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 22px; max-width: 650px; box-shadow: var(--shadow-sm);">
                        <form id="formAdminBroadcast">
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" style="font-weight: 600;">Gửi Đến Đối Tượng <span style="color: #dc2626;">*</span></label>
                                <select name="target" class="form-input" style="width: 100%;">
                                    <option value="all">Tất Cả Người Dùng (Độc Giả & Dịch Giả)</option>
                                    <option value="dich_gia">Chỉ Nhóm Dịch Giả (Translators)</option>
                                    <option value="doc_gia">Chỉ Nhóm Độc Giả (Readers)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" style="font-weight: 600;">Tiêu Đề Thông Báo <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="title" class="form-input" placeholder="VD: Thông báo cập nhật hệ thống / Sự kiện mới..." required style="width: 100%;" />
                            </div>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" style="font-weight: 600;">Nội Dung Chi Tiết <span style="color: #dc2626;">*</span></label>
                                <textarea name="message" class="form-input" rows="4" placeholder="Nhập nội dung thông báo gửi đến người dùng..." required style="width: 100%;"></textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label class="form-label" style="font-weight: 600;">Liên Kết Kèm Theo (Tùy chọn)</label>
                                <input type="url" name="link" class="form-input" placeholder="https://..." style="width: 100%;" />
                            </div>

                            <button type="submit" class="btn btn-primary" id="btnSubmitBroadcast" style="padding: 9px 22px; font-size: 13.5px;">
                                <i class="fa-solid fa-paper-plane"></i> Gửi Thông Báo Ngay
                            </button>
                            <div id="broadcastStatusMsg" style="margin-top: 12px; font-size: 13px; display: none;"></div>
                        </form>
                    </div>
                </section>

                <!-- =================================================================
                TAB 12: TỔNG HỢP TÍNH NĂNG & NHẬT KÝ CẬP NHẬT MỚI (CHỨC NĂNG MỚI ĐẦU MỤC)
                ================================================================= -->
                <section id="admTabFeatures" class="dash-tab-pane">
                    <div style="background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                        
                        <!-- Header & Nút Thêm Tính Năng Mới -->
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: var(--text-color); display: flex; align-items: center; gap: 8px;">
                                        Tổng Hợp Tính Năng & Nhật Ký Phát Triển Website
                                        <span class="badge badge-green" style="font-size: 11px;">v<?php echo MUOP_THEME_VERSION; ?></span>
                                    </h3>
                                    <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-muted);">
                                        Liệt kê đầy đủ mọi tính năng, chức năng của website. Mọi cập nhật hay chức năng mới luôn được tự động hiển thị ở đầu trang.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm" id="btnToggleAddFeatureBox" style="display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-plus"></i> Thêm Tính Năng / Nâng Cấp Mới
                                </button>
                            </div>
                        </div>

                        <!-- Form Thêm Tính Năng Mới (Mặc định ẩn, bấm nút để mở) -->
                        <div id="boxAdminAddFeature" style="display: none; background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1.5px solid var(--primary-green); margin-bottom: 24px; box-shadow: var(--shadow-sm);">
                            <div style="font-weight: 700; font-size: 14px; color: var(--primary-green); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fa-solid fa-bullhorn"></i> Ghi Nhận Tính Năng / Nâng Cấp Hệ Thống Mới</span>
                                <button type="button" id="btnCloseAddFeatureBox" style="background: none; border: none; font-size: 16px; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form id="formAdminAddFeature">
                                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                    <div>
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Tên Tính Năng / Chức Năng</label>
                                        <input type="text" name="title" class="form-input" placeholder="Ví dụ: Tự động gửi thông báo khi có chương mới" required />
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Phiên Bản</label>
                                        <input type="text" name="version" class="form-input" value="v<?php echo MUOP_THEME_VERSION; ?>" placeholder="v2.2.47" />
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Phân Hệ</label>
                                        <select name="category" class="form-input">
                                            <option value="Hệ Thống & Tự Động">Hệ Thống & Tự Động</option>
                                            <option value="Độc Giả (Reader)">Độc Giả (Reader)</option>
                                            <option value="Dịch Giả & Tác Giả">Dịch Giả & Tác Giả</option>
                                            <option value="Quản Trị Admin">Quản Trị Admin</option>
                                            <option value="Kiếm Tiền & Shopee">Kiếm Tiền & Shopee</option>
                                            <option value="Giao Diện & Mobile">Giao Diện & Mobile</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Huy Hiệu (Badge)</label>
                                        <select name="badge" class="form-input">
                                            <option value="MỚI">MỚI (New)</option>
                                            <option value="VỪA CẬP NHẬT">Vừa Cập Nhật</option>
                                            <option value="NÂNG CẤP">Nâng Cấp</option>
                                            <option value="HOT">Nổi Bật (Hot)</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label class="form-label" style="font-size: 12px; font-weight: 600;">Mô Tả Chi Tiết Về Tính Năng / Cách Hoạt Động</label>
                                    <textarea name="description" class="form-input" rows="3" placeholder="Mô tả tóm tắt tính năng mới, lợi ích đem lại cho người dùng hoặc cách sử dụng..." style="width: 100%; font-family: inherit; font-size: 13px;" required></textarea>
                                </div>
                                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnCancelAddFeature">Hủy</button>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btnSaveAddFeature">
                                        <i class="fa-solid fa-floppy-disk"></i> Lưu & Đưa Lên Đầu Mục Tính Năng
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- =============================================================
                             BỘ ĐỘNG CƠ TỐI ƯU HÓA HÌNH ẢNH TỰ ĐỘNG (AUTO IMAGE OPTIMIZER)
                             ============================================================= -->
                        <?php
                        $opt_stats = get_option('muop_image_opt_stats', array(
                            'total_files'       => 0,
                            'total_bytes_saved' => 0,
                            'history'           => array()
                        ));
                        $opt_mb_saved = round($opt_stats['total_bytes_saved'] / (1024 * 1024), 2);
                        ?>
                        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px solid #10b981; border-radius: var(--radius-md); padding: 20px; margin-bottom: 26px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 14px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);">
                                        <i class="fa-solid fa-file-image"></i>
                                    </div>
                                    <div>
                                        <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #065f46; display: flex; align-items: center; gap: 8px;">
                                            Động Cơ Tự Động Tối Ưu & Nén Hình Ảnh (Auto Image Optimizer)
                                            <span class="badge" style="background: #059669; color: #fff; font-size: 11px;">Hoạt Động 100%</span>
                                        </h4>
                                        <p style="margin: 3px 0 0; font-size: 13px; color: #047857;">
                                            Tự động nén chất lượng, xoay ảnh EXIF, xóa metadata GPS và sinh tệp WebP siêu nhẹ mỗi khi người dùng tải ảnh lên.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-sm" id="btnScanOptimizeLibrary" style="background: #059669; border-color: #059669; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-bolt"></i> Quét & Nén Thư Viện Ảnh Cũ
                                    </button>
                                </div>
                            </div>
                            
                            <!-- 4 Stats Boxes -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
                                <div style="background: #fff; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 14px;">
                                    <span style="font-size: 11.5px; color: #64748b; font-weight: 600; display: block;">Ảnh Đã Tự Động Tối Ưu</span>
                                    <span id="optStatFiles" style="font-size: 18px; font-weight: 800; color: #065f46;"><?php echo number_format($opt_stats['total_files']); ?> ảnh</span>
                                </div>
                                <div style="background: #fff; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 14px;">
                                    <span style="font-size: 11.5px; color: #64748b; font-weight: 600; display: block;">Dung Lượng Tiết Kiệm</span>
                                    <span id="optStatBytes" style="font-size: 18px; font-weight: 800; color: #059669;"><?php echo $opt_mb_saved; ?> MB</span>
                                </div>
                                <div style="background: #fff; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 14px;">
                                    <span style="font-size: 11.5px; color: #64748b; font-weight: 600; display: block;">Tự Sinh Định Dạng WebP</span>
                                    <span style="font-size: 13.5px; font-weight: 700; color: #0284c7;"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Sẵn Sàng (WebP 82%)</span>
                                </div>
                                <div style="background: #fff; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 14px;">
                                    <span style="font-size: 11.5px; color: #64748b; font-weight: 600; display: block;">Xoay Chiều & Xóa GPS</span>
                                    <span style="font-size: 13.5px; font-weight: 700; color: #7c3aed;"><i class="fa-solid fa-shield-halved" style="color: #7c3aed;"></i> Tự Động EXIF Clean</span>
                                </div>
                            </div>
                            
                            <div id="scanOptimizeProgressBox" style="display: none; background: #fff; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 14px; margin-top: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12.5px;">
                                    <span id="scanProgressMsg" style="font-weight: 600; color: #065f46;"><i class="fa-solid fa-spinner fa-spin"></i> Đang quét và tối ưu các tệp ảnh...</span>
                                </div>
                            </div>
                        </div>

                        <!-- =============================================================
                             1. ĐẦU MỤC: TÍNH NĂNG & NÂNG CẤP MỚI NHẤT (HIỂN THỊ Ở ĐẦU TRANG)
                             ============================================================= -->
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 2px solid var(--pastel-green); padding-bottom: 8px;">
                                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-fire" style="color: #ef4444;"></i> TÍNH NĂNG & CẬP NHẬT MỚI NHẤT
                                </h4>
                                <span style="font-size: 12px; color: var(--text-muted);">Tự động ưu tiên xếp ở vị trí đầu tiên</span>
                            </div>

                            <div id="admNewFeaturesContainer" style="display: flex; flex-direction: column; gap: 12px;">
                                <?php
                                $custom_features = get_option('muop_custom_feature_updates', array());
                                if (!empty($custom_features) && is_array($custom_features)) :
                                    foreach ($custom_features as $cf) :
                                ?>
                                    <div class="adm-feature-card is-custom-feature" data-id="<?php echo esc_attr($cf['id']); ?>" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px; letter-spacing: 0.5px;"><?php echo esc_html($cf['badge'] ?: 'MỚI'); ?></span>
                                                <span class="badge badge-approved" style="font-size: 11px;"><?php echo esc_html($cf['version']); ?></span>
                                                <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;"><?php echo esc_html($cf['category']); ?></span>
                                                <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);"><?php echo esc_html($cf['title']); ?></h5>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> <?php echo esc_html($cf['created_at']); ?></span>
                                                <button type="button" class="btn btn-secondary btn-xs btn-delete-custom-feature" data-id="<?php echo esc_attr($cf['id']); ?>" style="color: #ef4444; border-color: #fecaca; background: #fff5f5;" title="Xóa cập nhật này">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                            <?php echo nl2br(esc_html($cf['description'])); ?>
                                        </p>
                                    </div>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>

                                <!-- Danh sách các tính năng vừa mới nâng cấp của hệ thống -->
                                <!-- v2.2.78: Tự Động Nén & Tối Ưu Hóa Hình Ảnh Toàn Diện -->
                                <div class="adm-feature-card" style="background: #f0fdf4; border: 1.5px solid #10b981; border-radius: var(--radius-md); padding: 18px 20px; display: flex; flex-direction: column; gap: 10px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px; background: #ef4444; color: #fff;">MỚI</span>
                                            <span class="badge badge-approved" style="font-size: 11px; background: #10b981; color: #fff;">v2.2.78</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Hiệu Năng & Tối Ưu Media</span>
                                            <h5 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color);">Động Cơ Tự Động Tối Ưu & Nén Hình Ảnh Mỗi Khi Tải Lên (Auto Image Optimizer)</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #059669; font-weight: 600;"><i class="fa-regular fa-clock"></i> Mới cập nhật</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.65;">
                                        Tự động xử lý, nén dung lượng và tối ưu hóa 100% hình ảnh ngay khi được tải lên website (Ảnh bìa truyện mới & chỉnh sửa, ảnh đại diện Avatar người dùng / nhóm dịch, ảnh hóa đơn chuyển khoản lương, ảnh thư viện Media).
                                        Hệ thống vận hành với <strong>4 lớp tối ưu thông minh</strong>:
                                        (1) <em>Giới hạn kích thước tối đa</em> (Max 1600x2400px, thu nhỏ tỷ lệ mượt mà);
                                        (2) <em>Xoay ảnh đúng chiều tự động</em> dựa trên thông tin EXIF chụp từ điện thoại iPhone/Android;
                                        (3) <em>Xóa bỏ hoàn toàn EXIF rác & tọa độ GPS</em> bảo vệ quyền riêng tư và tiết kiệm hàng trăm KB;
                                        (4) <em>Nén ảnh chất lượng cao</em> (Progressive JPEG 82%, PNG tối ưu, tự động sinh bản WebP đồng hành siêu nhẹ giảm tới 70-95% dung lượng).
                                        Đồng thời hỗ trợ <em>Nén ảnh nhanh phía trình duyệt (Client-side Canvas)</em> giúp người dùng tải ảnh lên trong chớp mắt mà không tốn dung lượng 4G/Wifi.
                                    </p>
                                </div>
                                <!-- v2.2.77: Kiểm Duyệt & Đánh Dấu Từ Nhạy Cảm Realtime -->
                                <div class="adm-feature-card" style="background: #f0fdf4; border: 1.5px solid #22c55e; border-radius: var(--radius-md); padding: 18px 20px; display: flex; flex-direction: column; gap: 10px; box-shadow: 0 2px 8px rgba(34, 197, 94, 0.08);">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px; background: #ef4444; color: #fff;">MỚI</span>
                                            <span class="badge badge-approved" style="font-size: 11px; background: #22c55e; color: #fff;">v2.2.77</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Kiểm Duyệt & AI Moderation</span>
                                            <h5 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color);">Hệ Thống Kiểm Duyệt & Đánh Dấu Từ Nhạy Cảm Trực Quan (Real-time Highlighter)</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #16a34a; font-weight: 600;"><i class="fa-regular fa-clock"></i> Mới cập nhật</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.65;">
                                        Tự động quét và phát hiện các từ nhạy cảm (18+, thô tục, bạo lực, ma túy, vi phạm chính sách) ngay khi soạn thảo hoặc tải lên chương truyện. Tích hợp bảng xem trước toàn văn <strong>đánh dấu trực tiếp (Inline Highlighted Box)</strong> với viền đỏ phát sáng và đánh số thứ tự vi phạm rõ ràng kèm thanh chip từ khóa thông minh hỗ trợ cuộn mượt (smooth scroll) & nhấp nháy phát sáng đúng vị trí. Đặc biệt, trang bị nút 1-chạm <strong>« ⚡ Sửa Hết Bằng Dấu (.) »</strong> giúp tự động mã hóa an toàn toàn bộ bài viết chỉ trong một tích tắc mà không cần sửa thủ công từng từ.
                                    </p>
                                </div>

                                <!-- v2.2.77: Bắt Buộc Duyệt Nội Dung Trước Khi Đăng Chương -->
                                <div class="adm-feature-card" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: var(--radius-md); padding: 18px 20px; display: flex; flex-direction: column; gap: 10px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px;">MỚI</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.77</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Bảo Mật Nội Dung CMS</span>
                                            <h5 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color);">Cơ Chế Bắt Buộc Duyệt Nội Dung An Toàn Trước Khi Đăng Chương (Approval Flow)</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Mới cập nhật</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.65;">
                                        Quy trình đăng tải chương mới được nâng cấp với cơ chế chốt chặn bảo mật 2 lớp nghiêm ngặt (Client JS & Backend PHP): Khi người dùng bấm <strong>"Thêm Chương Này"</strong> hoặc <strong>"Cập Nhật Chương"</strong>, hệ thống sẽ tự động rà soát toàn bộ nội dung. Nếu phát hiện vi phạm, hệ thống lập tức tạm dừng quá trình xuất bản và bật bảng soát lỗi trực quan để người đăng chỉnh sửa. Chỉ khi toàn bộ nội dung đã hoàn toàn sạch sẽ, hợp lệ thì chương mới được phép lưu vào cơ sở dữ liệu.
                                    </p>
                                </div>

                                <!-- v2.2.77: Quản Lý Danh Sách Từ Nhạy Cảm Trong Admin Portal -->
                                <div class="adm-feature-card" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: var(--radius-md); padding: 18px 20px; display: flex; flex-direction: column; gap: 10px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px;">MỚI</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.77</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Quản Trị Admin</span>
                                            <h5 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text-color);">Giao Diện Quản Lý Danh Sách Từ Nhạy Cảm Dành Riêng Cho Quản Trị Viên</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Mới cập nhật</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.65;">
                                        Bổ sung phân hệ <strong>« Từ nhạy cảm »</strong> chuyên nghiệp ngay trên thanh điều hướng Admin Portal. Giao diện thiết kế dạng thẻ Card hiện đại chuẩn UX với huy hiệu "Từ cấm", phân loại theo nhóm (18+, Thô tục, Bạo lực, Khác), cùng menu nút 3 chấm dọc <code>⋮</code> cho phép chỉnh sửa hoặc xóa nhanh chóng. Tích hợp thanh tìm kiếm thời gian thực (Real-time Filter) và lưu trữ dữ liệu động trực tiếp trong Database, đồng bộ tức thì với bộ máy quét kiểm duyệt của website.
                                    </p>
                                </div>

                                <!-- v2.2.70: Vivu Crawler Tự Động Hóa -->
                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">NÂNG CẤP</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.70</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Tự Động Hóa Crawler</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Công Cụ Cào Truyện Tự Động Toàn Diện Vivu Crawler</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Gần đây</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Bộ công cụ cào dữ liệu truyện thông minh Vivu Crawler: Hỗ trợ cào truyện theo link nhanh chóng (cả chế độ Cào nhanh AJAX và Cào ngầm Server-side), tự động bóc tách tiêu đề, tác giả, ảnh bìa, tóm tắt và toàn bộ danh sách chương kèm bộ lọc loại bỏ rác, watermark và quảng cáo tự động.
                                    </p>
                                </div>

                                <!-- v2.2.65: Quản Lý Thể Loại Nâng Cao & Modal Danh Sách -->
                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">NÂNG CẤP</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.65</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Danh Mục & Dữ Liệu</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Quản Lý Thể Loại Nâng Cao Kèm Modal Xem Chi Tiết Toàn Bộ Truyện</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Gần đây</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Nâng cấp phân hệ Quản lý thể loại truyện với đầy đủ thao tác Thêm / Sửa / Xóa danh mục kèm slug tự động. Tích hợp Popup Modal xem nhanh danh sách toàn bộ truyện trực thuộc từng thể loại với đầy đủ thống kê số chương, lượt xem và trạng thái truyện.
                                    </p>
                                </div>

                                <!-- v2.2.60: Báo Lỗi Chương & Phản Hồi Trực Tuyến -->
                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">CẢI TIẾN</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.60</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Tương Tác & Chăm Sóc Độc Giả</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Hệ Thống Tiếp Nhận Báo Lỗi Chương Truyện & Hộp Thoại Phản Hồi Trực Tiếp</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Gần đây</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Phân hệ tiếp nhận và xử lý báo lỗi chương truyện trực tuyến: Cho phép độc giả báo các lỗi như chính tả, chương rỗng, lỗi hiển thị ảnh... Quản trị viên có thể theo dõi bảng danh sách chi tiết, nhập lời phản hồi gửi lại độc giả và đánh dấu trạng thái Đã xử lý / Chờ xử lý.
                                    </p>
                                </div>

                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">ĐÃ RA MẮT</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.47</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Giao Diện & Di Động</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Bộ Điều Khiển Kéo & Cuộn Ngang Cho Mọi Bảng Dữ Liệu (Scroll Controls)</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Trước đó</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Tích hợp nút bấm mũi tên <strong>&lt;</strong> và <strong>&gt;</strong> kèm hiệu ứng kéo chuột mượt mà cho mọi bảng dữ liệu (Quản lý truyện, Người dùng, Thể loại, Lịch sử tách chương, Doanh thu...). Tự động nhận diện khi nội dung bị tràn trên điện thoại hoặc khi thu nhỏ cửa sổ trình duyệt, giúp thao tác duyệt dữ liệu cực kỳ thuận tiện.
                                    </p>
                                </div>

                                <div class="adm-feature-card" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px;">MỚI</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.47</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Tiện Ích Admin</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Nút Bấm Nổi Cuộn Nhanh Lên Đầu Trang (Back To Top Button)</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Hôm nay</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Nút bấm hình tròn thanh lịch nổi ở góc dưới bên phải màn hình trên toàn bộ trang quản trị Admin. Tự động xuất hiện mượt mà khi cuộn trang xuống và cho phép quay về đầu trang ngay lập tức chỉ với 1 cú click.
                                    </p>
                                </div>

                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">VỪA CẬP NHẬT</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.46</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Tự Động Hóa</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Công Cụ Tự Động Tách Truyện Thành Các Chương Nhỏ Chuẩn Xác</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Gần đây</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Tự động phân tách truyện thành từng chương mục tiêu ~2,500 từ (random ±150 từ), ngắt chuẩn xác tại dấu chấm cuối câu hoặc ngắt đoạn mà không bị cụt câu. Hỗ trợ tải trực tiếp tệp Word (.docx, .doc), .pdf, .txt hoặc dán văn bản và lưu trữ bảng lịch sử tách chương với khả năng xem lại / tải file về máy.
                                    </p>
                                </div>

                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">VỪA CẬP NHẬT</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.45</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Nội Dung CMS</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Quản Trị Động 4 Trang Nội Dung Chính Sách & Thông Tin Website</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Gần đây</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Cho phép Quản Trị Viên chỉnh sửa trực tiếp nội dung Điều khoản dịch vụ, Giới thiệu website, Chính sách bảo mật và Thông tin liên hệ ngay trên Admin Portal, dữ liệu được đồng bộ hóa tức thì sang các trang tĩnh ngoài giao diện người dùng.
                                    </p>
                                </div>

                                <div class="adm-feature-card" style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span class="badge badge-green" style="font-size: 11px; font-weight: 700; padding: 3px 8px;">VỪA CẬP NHẬT</span>
                                            <span class="badge badge-approved" style="font-size: 11px;">v2.2.44</span>
                                            <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">Bảo Mật & Thao Tác</span>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Menu 3 Chấm Dọc Tinh Gọn & Modal Chi Tiết Đa Năng</h5>
                                        </div>
                                        <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> Gần đây</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                                        Chuyển đổi toàn bộ nút hành động cồng kềnh sang menu 3 chấm dọc nhỏ gọn cho Thể loại truyện, Người dùng và Quản lý truyện. Tích hợp popup chi tiết, xác nhận an toàn trước khi khóa/mở khóa tài khoản, đổi trạng thái và xóa nội dung.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- =============================================================
                             2. DANH MỤC TỔNG HỢP TOÀN BỘ CHỨC NĂNG HỆ THỐNG THEO PHÂN HỆ
                             ============================================================= -->
                        <div>
                            <div style="margin-bottom: 16px; border-bottom: 2px solid var(--pastel-green); padding-bottom: 8px;">
                                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-layer-group"></i> TỔNG HỢP TẤT CẢ TÍNH NĂNG & CHỨC NĂNG HỆ THỐNG
                                </h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
                                
                                <!-- Card 1: Phân Hệ Độc Giả -->
                                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(59, 130, 246, 0.1); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa-solid fa-book-open-reader"></i>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Phân Hệ Độc Giả (Reader)</h5>
                                            <span style="font-size: 11.5px; color: var(--text-muted);">Trải nghiệm đọc truyện hoàn hảo</span>
                                        </div>
                                    </div>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                                        <li>Đọc truyện Zhihu đa chương mượt mà, lưu lịch sử đọc.</li>
                                        <li>Chế độ Giao diện Sáng / Tối (Dark / Light Mode) bảo vệ mắt.</li>
                                        <li>Bình luận và chấm điểm đánh giá số sao cho truyện.</li>
                                        <li>Bảo vệ bản quyền: Khóa bôi đen, chống chuột phải, chặn phím tắt F12.</li>
                                        <li>Báo lỗi chương truyện trực tuyến tới Quản trị viên.</li>
                                        <li>Mục tìm kiếm truyện thông minh theo từ khóa và thể loại.</li>
                                    </ul>
                                </div>

                                <!-- Card 2: Phân Hệ Dịch Giả & Tác Giả -->
                                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(16, 185, 129, 0.1); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa-solid fa-pen-fancy"></i>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Phân Hệ Dịch Giả (Creators)</h5>
                                            <span style="font-size: 11.5px; color: var(--text-muted);">Sáng tạo nội dung & nhận nhuận bút</span>
                                        </div>
                                    </div>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                                        <li>Cổng đăng truyện & tạo chương mới chuẩn SEO dễ dàng.</li>
                                        <li>Hệ thống tính tiền lương tự động 8đ / lượt đọc view thực tế.</li>
                                        <li>Trang hồ sơ dịch giả và cập nhật tài khoản ngân hàng nhận tiền.</li>
                                        <li>Quản lý thông tin nhóm dịch (Team dịch) trực thuộc.</li>
                                        <li>Nhận thông báo thanh toán lương kèm ảnh bill chuyển khoản.</li>
                                    </ul>
                                </div>

                                <!-- Card 3: Phân Hệ Quản Trị Hệ Thống -->
                                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(139, 92, 246, 0.1); color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa-solid fa-screwdriver-wrench"></i>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Quản Trị Hệ Thống (Admin CMS)</h5>
                                            <span style="font-size: 11.5px; color: var(--text-muted);">Điều hành và kiểm soát toàn diện</span>
                                        </div>
                                    </div>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                                        <li>Báo cáo Thống kê & Phân tích tổng thể lượt đọc và người dùng.</li>
                                        <li>Quản lý truyện: Duyệt truyện, đổi trạng thái, sửa mô tả, ẩn truyện.</li>
                                        <li>Quản lý người dùng: Phân quyền, Khóa/Mở khóa tài khoản an toàn.</li>
                                        <li>Quản lý Thể loại truyện kèm popup xem truyện trực thuộc.</li>
                                        <li>Quản lý danh sách Từ nhạy cảm & thiết lập bộ từ cấm động.</li>
                                        <li>Duyệt chi lương dịch giả có tải ảnh bill chuyển khoản xác nhận.</li>
                                        <li>Phát thông báo hệ thống và xử lý báo lỗi chương truyện.</li>
                                    </ul>
                                </div>

                                <!-- Card 4: Tự Động Hóa & Tiện Ích -->
                                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(245, 158, 11, 0.1); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa-solid fa-gears"></i>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Tự Động Hóa (Automation & Tools)</h5>
                                            <span style="font-size: 11.5px; color: var(--text-muted);">Công cụ hỗ trợ năng suất cao</span>
                                        </div>
                                    </div>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                                        <li>Công cụ cào truyện tự động thông minh Vivu Crawler đa luồng.</li>
                                        <li>Kiểm duyệt từ nhạy cảm Real-time & Sửa nhanh 1-chạm bằng dấu (.).</li>
                                        <li>Cơ chế bắt buộc duyệt nội dung an toàn trước khi đăng chương.</li>
                                        <li>Tự động tách truyện thành các chương từ file Word/PDF/Text.</li>
                                        <li>Thuật toán random ±150 từ ngắt chuẩn tại dấu câu kết thúc.</li>
                                        <li>Lưu lịch sử tách chương với khả năng xem lại và tải file txt.</li>
                                        <li>Bộ điều khiển cuộn ngang bảng dữ liệu trên điện thoại.</li>
                                        <li>Nút bấm nổi cuộn nhanh lên đầu trang (Back to top).</li>
                                    </ul>
                                </div>

                                <!-- Card 5: Tiếp Thị Liên Kết & Kiếm Tiền -->
                                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(239, 68, 68, 0.1); color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa-solid fa-sack-dollar"></i>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Tiếp Thị & Doanh Thu (Affiliate)</h5>
                                            <span style="font-size: 11.5px; color: var(--text-muted);">Mô hình hóa dòng doanh thu</span>
                                        </div>
                                    </div>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                                        <li>Cấu hình liên kết tiếp thị Shopee & TikTok Shop.</li>
                                        <li>Cơ chế bắt buộc độc giả click link để mở khóa ở Chương 2, 5, 8...</li>
                                        <li>Ghi nhận địa chỉ IP, trình duyệt và chương khi độc giả click.</li>
                                        <li>Thống kê tổng lượt click và nhật ký tương tác thời gian thực.</li>
                                    </ul>
                                </div>

                                <!-- Card 6: Bảo Mật & Nền Tảng -->
                                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(14, 165, 233, 0.1); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">Bảo Mật & Hạ Tầng (Security)</h5>
                                            <span style="font-size: 11.5px; color: var(--text-muted);">Hiệu năng cao & vận hành an toàn</span>
                                        </div>
                                    </div>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                                        <li>Thiết kế tương thích Responsive chuẩn Mobile-First 100%.</li>
                                        <li>Xác thực mã Nonce và phân quyền vai trò cho mọi AJAX REST API.</li>
                                        <li>Xử lý làm sạch và kiểm tra dữ liệu đầu vào chống SQLi & XSS.</li>
                                        <li>Tối ưu hóa tài nguyên tĩnh (CSS, JS, Fonts) với bộ đệm versioning.</li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                    </div>
                </section>

            </div>

            <!-- 4. FOOTER CỦA MAIN WORKSPACE THEO ĐÚNG ẢNH 2 -->
            <footer class="admin-db-footer">
                <div class="admin-db-footer-left">
                    <span>© <?php echo date('Y'); ?> <strong>Mướp Đắng Web Novel</strong></span>
                    <span class="admin-db-footer-divider">•</span>
                    <span>Hệ Thống Quản Trị Cơ Sở Dữ Liệu CMS</span>
                </div>
                <div class="admin-db-footer-right">
                    <span class="db-status-dot"></span>
                    <span class="db-status-text">Hệ thống vận hành ổn định</span>
                    <span class="admin-db-footer-divider">•</span>
                    <span class="db-version-badge">v<?php echo MUOP_THEME_VERSION; ?></span>
                </div>
            </footer>
        </section>
    </div>
</div>

<!-- NÚT BẤM CUỘN LÊN ĐẦU TRANG (BACK TO TOP) CHO TOÀN BỘ TRANG QUẢN TRỊ ADMIN (HÌNH 4/5) -->
<button type="button" id="adminBackToTop" class="admin-back-to-top" title="Cuộn lên đầu trang" aria-label="Cuộn lên đầu trang">
    <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- =========================================================================
     MODAL POPUP 1: ADMIN THANH TOÁN LƯƠNG & GỬI BILL CHUYỂN KHOẢN
     ========================================================================= -->
<div id="admModalPaySalary" class="modal-backdrop" style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; padding: 16px;">
    <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 480px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; padding: 0;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
            <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-money-bill-transfer"></i> Quyết Toán & Tải Lên Bill Nhuận Bút
            </h4>
            <button type="button" class="btn-close-modal" id="btnClosePayModal" style="background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="formAdminPaySalary" style="padding: 20px;">
            <input type="hidden" name="translator_id" id="payTranslatorId" value="" />
            <input type="hidden" name="views" id="payViews" value="0" />
            
            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Dịch Giả Nhận Tiền:</label>
                <input type="text" id="payTranslatorName" class="form-input" readonly style="background: var(--bg-body); font-weight: 700;" />
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Thông Tin Ngân Hàng Nhận:</label>
                <input type="text" id="payBankInfo" class="form-input" readonly style="background: var(--bg-body); font-size: 12.5px;" />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Kỳ Thanh Toán: <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="period" id="payPeriod" class="form-input" required />
                </div>
                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Số Tiền (VNĐ): <span style="color: #dc2626;">*</span></label>
                    <input type="number" name="amount" id="payAmount" class="form-input" required style="font-weight: 700; color: #2E7D32;" />
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Ảnh Biên Lai / Bill Đã Chuyển Khoản: <span style="color: #dc2626;">*</span></label>
                <input type="file" name="bill_image" id="payBillFile" accept="image/*" class="form-input" required style="padding: 6px;" />
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Ghi Chú Cho Dịch Giả (Tùy chọn):</label>
                <textarea name="note" class="form-input" rows="2" placeholder="VD: Đã chuyển khoản qua MB Bank lúc 14h..."></textarea>
            </div>

            <div id="payStatusMsg" style="font-size: 12.5px; margin-bottom: 12px; display: none;"></div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" id="btnCancelPayModal" style="padding: 8px 16px; font-size: 13px;">Hủy Bỏ</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitPaySalary" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-paper-plane"></i> Xác Nhận Thanh Toán
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL POPUP 2: ADMIN PHẢN HỒI BÁO LỖI CHƯƠNG
     ========================================================================= -->
<div id="admModalReplyReport" class="modal-backdrop" style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; padding: 16px;">
    <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 460px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; padding: 0;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
            <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-reply"></i> Phản Hồi Báo Lỗi Chương
            </h4>
            <button type="button" class="btn-close-modal" id="btnCloseReplyModal" style="background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="formAdminReplyReport" style="padding: 20px;">
            <input type="hidden" name="report_id" id="replyReportId" value="" />
            
            <div style="background: var(--pastel-green); padding: 10px 14px; border-radius: var(--radius-sm); margin-bottom: 14px; font-size: 12.5px;">
                Đang phản hồi cho: <strong id="replyStoryName"></strong>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Nội Dung Phản Hồi: <span style="color: #dc2626;">*</span></label>
                <textarea name="reply_text" id="replyTextarea" class="form-input" rows="4" placeholder="Nhập phản hồi gửi độc giả và dịch giả..." required style="width: 100%;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Trạng Thái Xử Lý:</label>
                <select name="status" id="replyStatusSelect" class="form-input" style="width: 100%;">
                    <option value="resolved">Đã Xử Lý Xong (Resolved)</option>
                    <option value="pending">Đang Kiểm Tra (Pending)</option>
                </select>
            </div>

            <div id="replyStatusMsg" style="font-size: 12.5px; margin-bottom: 12px; display: none;"></div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" id="btnCancelReplyModal" style="padding: 8px 16px; font-size: 13px;">Đóng</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitReplyReport" style="padding: 8px 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-paper-plane"></i> Gửi Phản Hồi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Inline JS for Admin Portal interactions
jQuery(document).ready(function($) {
  // Đảm bảo muopConfig luôn đầy đủ ajaxUrl, nonce và userId bảo mật
  window.muopConfig = window.muopConfig || {};
  if (!window.muopConfig.ajaxUrl) {
    window.muopConfig.ajaxUrl = '<?php echo esc_js(wp_make_link_relative(admin_url("admin-ajax.php"))); ?>';
  }
  if (!window.muopConfig.nonce) {
    window.muopConfig.nonce = '<?php echo esc_js(wp_create_nonce("muop_ajax_nonce")); ?>';
  }
  if (!window.muopConfig.userId) {
    window.muopConfig.userId = '<?php echo esc_js(get_current_user_id()); ?>';
  }
  if (!window.muopConfig.sessionToken) {
    window.muopConfig.sessionToken = '<?php echo esc_js(function_exists("wp_get_session_token") ? wp_get_session_token() : ""); ?>';
  }

  // Bắt buộc gửi cookie xác thực phiên quản trị và header qua tất cả request AJAX
  $.ajaxSetup({
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function(xhr) {
      if (window.muopConfig && window.muopConfig.userId) {
        xhr.setRequestHeader('X-Muop-User', window.muopConfig.userId);
      }
      if (window.muopConfig && window.muopConfig.sessionToken) {
        xhr.setRequestHeader('X-Muop-Token', window.muopConfig.sessionToken);
      }
      if (window.muopConfig && window.muopConfig.nonce) {
        xhr.setRequestHeader('X-WP-Nonce', window.muopConfig.nonce);
      }
    }
  });

  // Safe helper xử lý toàn bộ các mã lỗi HTTP và phản hồi máy chủ
  function handleAjaxError(xhr, status, error, defaultMsg) {
    if (typeof window.handleAjaxError === 'function') {
      return window.handleAjaxError(xhr, status, error, defaultMsg);
    }
    try {
      if (xhr && xhr.responseJSON && xhr.responseJSON.data) {
        if (xhr.responseJSON.data.require_login) {
          setTimeout(function() {
            window.location.href = '<?php echo esc_js(home_url("/dang-nhap/?redirect_to=" . urlencode(home_url("/quan-ly-admin/")))); ?>';
          }, 1500);
          return 'Phiên làm việc đã hết hạn. Đang chuyển hướng bạn đến trang đăng nhập...';
        }
        if (xhr.responseJSON.data.message) {
          return xhr.responseJSON.data.message;
        }
      }
      if (xhr && xhr.responseText) {
        const raw = xhr.responseText.trim();
        if (raw === '-1' || raw === '0') {
          return 'Phiên làm việc đã hết hạn hoặc chưa đăng nhập. Vui lòng tải lại trang (F5)!';
        }
        const s = raw.indexOf('{');
        const e = raw.lastIndexOf('}');
        if (s !== -1 && e !== -1) {
          const parsed = JSON.parse(raw.substring(s, e + 1));
          if (parsed.data && parsed.data.message) return parsed.data.message;
          if (parsed.message) return parsed.message;
        }
      }
    } catch(e) {}
    if (xhr && xhr.status === 403) return 'Yêu cầu bị từ chối (403 Forbidden). Vui lòng tải lại trang!';
    if (xhr && xhr.status === 404) return 'Không tìm thấy máy chủ xử lý (404 Not Found).';
    if (xhr && (xhr.status === 504 || status === 'timeout')) return 'Máy chủ phản hồi quá thời gian quy định (Timeout).';
    if (xhr && xhr.status === 500) return 'Máy chủ gặp sự cố xử lý (500 Internal Error).';
    if (error && typeof error === 'string' && error.trim() && error.toLowerCase() !== 'error') {
      return 'Lỗi kết nối máy chủ: ' + error.trim();
    }
    return defaultMsg || 'Không thể kết nối với máy chủ. Vui lòng kiểm tra lại đường truyền mạng!';
  }
  // 1. UNIVERSAL TAB SWITCHING (FIX HÌNH 1: ĐẢM BẢO CHUYỂN TAB 100%)
  function switchAdminTab(tabId) {
    if (!tabId) return;
    const cleanId = tabId.replace(/^#/, '');
    const $btn = $('.dash-tab-btn[data-tab="' + cleanId + '"]');
    const $pane = $('#' + cleanId);
    if ($btn.length && $pane.length) {
      $('.dash-tab-btn').removeClass('active');
      $('.dash-tab-pane').removeClass('active');
      $btn.addClass('active');
      $pane.addClass('active');

      const tabLabelMap = {
        'admTabStories': 'Quản lý truyện',
        'admTabAnalytics': 'Thống kê & Phân tích lượt đọc',
        'admTabTeams': 'Quản lý team dịch',
        'admTabSplitter': 'Tách truyện tự động',
        'admTabVivuCrawler': 'Công cụ lấy crawl tự động',
        'admTabUsers': 'Quản lý user',
        'admTabAffiliate': 'Link Shopee & TikTok',
        'admTabSalaries': 'Tiền lương dịch giả',
        'admTabGenres': 'Thể loại truyện',
        'admTabSensitiveWords': 'Quản lý từ nhạy cảm',
        'admTabComments': 'Bình luận & Đánh giá',
        'admTabReports': 'Báo lỗi chương',
        'admTabBroadcast': 'Thông báo hệ thống',
        'admTabFeatures': 'Tính năng & Cập nhật hệ thống'
      };

      if (tabLabelMap[cleanId]) {
        $('#admActiveTabLabel').text(tabLabelMap[cleanId]);
      }

      // Sync toolbar groups
      $('.admin-toolbar-group').hide();
      if (cleanId === 'admTabStories') {
        $('#toolbarGroupStories').show();
      } else if (cleanId === 'admTabUsers') {
        $('#toolbarGroupUsers').show();
      } else if (cleanId === 'admTabGenres') {
        $('#toolbarGroupGenres').show();
      } else if (cleanId === 'admTabTeams') {
        $('#toolbarGroupTeams').show();
      } else if (cleanId === 'admTabSplitter') {
        $('#toolbarGroupSplitter').show();
      } else if (cleanId === 'admTabVivuCrawler') {
        $('#toolbarGroupVivuCrawler').show();
      } else if (cleanId === 'admTabAnalytics') {
        $('#toolbarGroupAnalytics').show();
      } else {
        $('#toolbarGroupGeneric').show();
      }

      closeAdminSidebar();
    }
  }

  // Mobile Sidebar Drawer Management (Hình 2: Dấu đóng menu và lớp phủ)
  function openAdminSidebar() {
    $('#adminDbSidebar').addClass('mobile-open');
    $('#adminSidebarBackdrop').addClass('active');
    $('body').addClass('sidebar-modal-open');
  }

  function closeAdminSidebar() {
    $('#adminDbSidebar').removeClass('mobile-open');
    $('#adminSidebarBackdrop').removeClass('active');
    $('body').removeClass('sidebar-modal-open');
  }

  $(document).on('click', '.dash-tab-btn[data-tab]', function(e) {
    e.preventDefault();
    const tabId = $(this).attr('data-tab');
    if (!tabId) return;
    switchAdminTab(tabId);
    if (history.replaceState) {
      history.replaceState(null, null, '#' + tabId);
    } else {
      window.location.hash = '#' + tabId;
    }
  });

  if (window.location.hash) {
    switchAdminTab(window.location.hash);
  }
  $(window).on('hashchange', function() {
    if (window.location.hash) {
      switchAdminTab(window.location.hash);
    }
  });

  // Mở/Đóng mobile sidebar
  $('#btnAdminDbSidebarToggle').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    if ($('#adminDbSidebar').hasClass('mobile-open')) {
      closeAdminSidebar();
    } else {
      openAdminSidebar();
    }
  });

  $('#btnCloseAdminSidebar, #adminSidebarBackdrop').on('click', function(e) {
    e.preventDefault();
    closeAdminSidebar();
  });

  // Toggle create user box
  $('#btnToggleCreateUserBox, #btnCloseCreateUserBox').on('click', function() {
    $('#boxAdminCreateUser').slideToggle(200);
  });

  // 2. TEAM MANAGEMENT HANDLERS (HÌNH 1)
  $('#btnToggleCreateTeamBox, #btnCloseCreateTeamBox').on('click', function() {
    $('#boxAdminCreateTeam').slideToggle(200);
  });

  $('#formAdminCreateTeam').on('submit', function(e) {
    e.preventDefault();
    const $btn = $('#btnSubmitCreateTeam');
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang tạo...');
    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: $(this).serialize() + '&action=muop_admin_action&sub_action=create_team&nonce=' + muopConfig.nonce,
      success: function(res) {
        $btn.prop('disabled', false).html(originalText);
        if (res.success) {
          muopAlert(res.data ? res.data.message : 'Tạo nhóm dịch thành công!', 'success', function() {
            window.location.reload();
          });
        } else {
          muopAlert(res.data ? res.data.message : 'Lỗi tạo nhóm dịch!', 'error');
        }
      },
      error: function(xhr, status, error) {
        $btn.prop('disabled', false).html(originalText);
        muopAlert(handleAjaxError(xhr, status, error, 'Lỗi kết nối máy chủ khi tạo nhóm!'), 'error');
      }
    });
  });

  $(document).on('click', '.btn-edit-team-trigger', function() {
    const teamId = $(this).data('team-id');
    const teamName = $(this).data('team-name');
    const teamDesc = $(this).data('team-desc');
    $('#editTeamId').val(teamId);
    $('#editTeamName').val(teamName);
    $('#editTeamDesc').val(teamDesc);
    $('#boxAdminEditTeam').slideDown(200);
    $('html, body').animate({ scrollTop: $('#boxAdminEditTeam').offset().top - 100 }, 300);
  });

  $('#btnCloseEditTeamBox').on('click', function() {
    $('#boxAdminEditTeam').slideUp(200);
  });

  $('#formAdminEditTeam').on('submit', function(e) {
    e.preventDefault();
    const $btn = $('#btnSubmitEditTeam');
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: $(this).serialize() + '&action=muop_admin_action&sub_action=update_team&nonce=' + muopConfig.nonce,
      success: function(res) {
        $btn.prop('disabled', false).html(originalText);
        if (res.success) {
          muopAlert(res.data ? res.data.message : 'Cập nhật nhóm dịch thành công!', 'success', function() {
            window.location.reload();
          });
        } else {
          muopAlert(res.data ? res.data.message : 'Lỗi cập nhật nhóm!', 'error');
        }
      },
      error: function(xhr, status, error) {
        $btn.prop('disabled', false).html(originalText);
        muopAlert(handleAjaxError(xhr, status, error, 'Lỗi kết nối máy chủ khi tạo nhóm!'), 'error');
      }
    });
  });

  $(document).on('click', '.btn-delete-team-trigger', function() {
    const teamId = $(this).data('team-id');
    const teamName = $(this).data('team-name') || '';
    const safeName = $('<div>').text(teamName).html();

    muopConfirm(
      'Bạn có chắc chắn muốn xóa nhóm dịch <strong style="color: #dc2626;">"' + safeName + '"</strong> không?<br><span style="font-size: 13px; color: var(--text-muted); display: block; margin-top: 6px;"><i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b;"></i> Hành động này sẽ xóa nhóm dịch khỏi hệ thống và không thể hoàn tác!</span>',
      function() {
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'delete_team',
            team_id: teamId,
            nonce: muopConfig.nonce
          },
          success: function(res) {
            if (res.success) {
              muopAlert('Đã xóa nhóm dịch thành công!', 'success', function() {
                window.location.reload();
              });
            } else {
              muopAlert(res.data ? res.data.message : 'Lỗi xóa nhóm dịch!', 'error');
            }
          },
          error: function(xhr, status, error) {
            muopAlert(handleAjaxError(xhr, status, error, 'Lỗi kết nối máy chủ khi xóa nhóm!'), 'error');
          }
        });
      },
      null,
      {
        title: 'Xóa Nhóm Dịch?',
        confirmText: 'Xóa Vĩnh Viễn',
        cancelText: 'Hủy Bỏ',
        isDanger: true
      }
    );
  });

  $('#admTeamSearchInput').on('keyup input', function() {
    const q = $(this).val().toLowerCase().trim();
    $('.adm-team-row').each(function() {
      const name = $(this).data('team-name') || '';
      $(this).toggle(!q || name.indexOf(q) !== -1);
    });
  });

  // 3. CHAPTER SPLITTER HANDLERS (HÌNH 1 & LỊCH SỬ TÁCH CHƯƠNG)
  const SPLITTER_HISTORY_KEY = 'muop_splitter_history';

  // Drag & drop file vào Dropzone
  $('#splitterDropzone').on('click', function(e) {
    if (e.target.id === 'splitterFileInput') return;
    $('#splitterFileInput').trigger('click');
  });

  $('#splitterDropzone').on('dragover dragenter', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).css({ borderColor: 'var(--primary-green)', background: '#f0fdf4' });
  });

  $('#splitterDropzone').on('dragleave drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).css({ borderColor: '#cbd5e1', background: '#f8fafc' });
  });

  $('#splitterDropzone').on('drop', function(e) {
    if (e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
      const droppedFile = e.originalEvent.dataTransfer.files[0];
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(droppedFile);
      document.getElementById('splitterFileInput').files = dataTransfer.files;
      $('#splitterFileInput').trigger('change');
    }
  });

  $('#splitterFileInput').on('change', function() {
    if (this.files && this.files[0]) {
      const f = this.files[0];
      const ext = f.name.split('.').pop().toLowerCase();
      let icon = 'fa-file-lines';
      let iconColor = 'var(--primary-green)';
      if (ext === 'docx' || ext === 'doc') {
        icon = 'fa-file-word';
        iconColor = '#2563eb';
      } else if (ext === 'pdf') {
        icon = 'fa-file-pdf';
        iconColor = '#dc2626';
      }
      $('#splitterFileNameDisplay').html('<i class="fa-solid ' + icon + '" style="color: ' + iconColor + ';"></i> <strong>' + f.name + '</strong> (' + Math.round(f.size / 1024) + ' KB)');
    } else {
      $('#splitterFileNameDisplay').html('Kéo thả file truyện hoặc bấm vào đây để chọn file');
    }
  });

  // Thuật toán tách chương client-side dự phòng
  function clientSideSplitStory(textContent, targetWords) {
    targetWords = targetWords || 2500;
    textContent = (textContent || '').replace(/\r\n|\r/g, '\n').trim();
    if (!textContent) return null;

    const sentences = textContent.split(/(?<=[.!?])\s+|\n{2,}/g).filter(function(s) { return s.trim().length > 0; });
    if (!sentences.length) {
      sentences.push(textContent);
    }

    const chapters = [];
    let currChunk = '';
    let currWords = 0;
    let chapterNum = 1;
    let currTarget = targetWords + Math.floor(Math.random() * 301) - 150;

    for (let i = 0; i < sentences.length; i++) {
      const s = sentences[i].trim();
      if (!s) continue;
      const wordsInS = (s.match(/\S+/g) || []).length;

      if (currWords > 0 && (currWords + wordsInS) >= currTarget) {
        chapters.push({
          number: chapterNum,
          title: 'Chương ' + chapterNum,
          words: currWords,
          content: currChunk.trim()
        });
        chapterNum++;
        currChunk = s;
        currWords = wordsInS;
        currTarget = targetWords + Math.floor(Math.random() * 301) - 150;
      } else {
        currChunk += (currChunk ? '\n\n' : '') + s;
        currWords += wordsInS;
      }
    }

    if (currChunk.trim()) {
      if (currWords < 350 && chapters.length > 0) {
        const last = chapters[chapters.length - 1];
        last.content += '\n\n' + currChunk.trim();
        last.words += currWords;
      } else {
        chapters.push({
          number: chapterNum,
          title: 'Chương ' + chapterNum,
          words: currWords,
          content: currChunk.trim()
        });
      }
    }

    let totalWords = 0;
    chapters.forEach(function(c) { totalWords += c.words; });
    return {
      total_chapters: chapters.length,
      total_words: totalWords,
      target_words: targetWords,
      chapters: chapters
    };
  }

  let currentSplitChapters = [];

  // ==========================================
  // QUẢN LÝ LỊCH SỬ TÁCH CHƯƠNG (LOCALSTORAGE)
  // ==========================================
  function getSplitterHistory() {
    try {
      const raw = localStorage.getItem(SPLITTER_HISTORY_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch(e) {
      console.warn('Lỗi đọc lịch sử tách chương:', e);
      return [];
    }
  }

  function saveSplitterHistoryList(list) {
    try {
      localStorage.setItem(SPLITTER_HISTORY_KEY, JSON.stringify(list));
    } catch(e) {
      console.warn('Lỗi lưu lịch sử tách chương:', e);
    }
  }

  function addSplitterHistory(item) {
    const list = getSplitterHistory();
    // Đưa bản ghi mới lên đầu danh sách, giữ tối đa 40 bản ghi
    list.unshift(item);
    if (list.length > 40) {
      list.length = 40;
    }
    saveSplitterHistoryList(list);
    renderSplitterHistoryTable();
  }

  function renderSplitterHistoryTable() {
    const list = getSplitterHistory();
    const $tbody = $('#splitterHistoryListBody');
    const $empty = $('#splitterHistoryEmpty');
    const $tableWrapper = $('#tableSplitterHistory').closest('.table-responsive');

    if (!list || !list.length) {
      $tbody.empty();
      $tableWrapper.hide();
      $empty.show();
      return;
    }

    $empty.hide();
    $tableWrapper.show();

    let html = '';
    list.forEach(function(item, idx) {
      let icon = 'fa-file-lines';
      let iconColor = 'var(--primary-green)';
      const t = (item.title || '').toLowerCase();
      if (t.endsWith('.docx') || t.endsWith('.doc')) {
        icon = 'fa-file-word';
        iconColor = '#2563eb';
      } else if (t.endsWith('.pdf')) {
        icon = 'fa-file-pdf';
        iconColor = '#dc2626';
      }

      html += '<tr data-history-id="' + item.id + '">';
      html += '  <td style="text-align: center; font-weight: 600; color: #64748b;">' + (idx + 1) + '</td>';
      html += '  <td style="min-width: 220px; max-width: 340px;">';
      html += '    <div style="display: flex; align-items: flex-start; gap: 8px; font-weight: 600; color: var(--text-color);">';
      html += '      <i class="fa-solid ' + icon + '" style="color: ' + iconColor + '; font-size: 15px; margin-top: 2px; flex-shrink: 0;"></i>';
      html += '      <span class="splitter-history-filename" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; word-break: break-word; line-height: 1.45;" title="' + $('<div>').text(item.title).html() + '">' + $('<div>').text(item.title).html() + '</span>';
      html += '    </div>';
      html += '  </td>';
      html += '  <td style="text-align: center;"><span class="badge badge-green" style="font-weight: 700; font-size: 11.5px;">' + (item.total_chapters || 0) + ' chương</span></td>';
      html += '  <td style="text-align: center; font-weight: 700; color: #0284c7;">' + Number(item.total_words || 0).toLocaleString() + '</td>';
      html += '  <td style="text-align: center; font-weight: 700; color: #d97706;">' + Number(item.avg_words || 0).toLocaleString() + '</td>';
      html += '  <td style="text-align: center; font-size: 12px; color: var(--text-muted);"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i>' + (item.created_at || '') + '</td>';
      html += '  <td style="text-align: center;">';
      html += '    <div style="display: inline-flex; gap: 6px; align-items: center;">';
      html += '      <button type="button" class="btn btn-secondary btn-xs btn-review-history-split" data-id="' + item.id + '" title="Xem lại các chương đã tách"><i class="fa-solid fa-eye"></i> Xem lại</button>';
      html += '      <button type="button" class="btn btn-primary btn-xs btn-download-history-split" data-id="' + item.id + '" title="Tải toàn bộ file .txt về máy"><i class="fa-solid fa-download"></i> Tải về</button>';
      html += '      <button type="button" class="btn btn-danger btn-xs btn-delete-history-split" data-id="' + item.id + '" title="Xóa khỏi lịch sử" style="padding: 4px 8px;"><i class="fa-solid fa-trash-can"></i></button>';
      html += '    </div>';
      html += '  </td>';
      html += '</tr>';
    });

    $tbody.html(html);
  }

  // Làm mới form để sẵn sàng tách nội dung mới
  function resetSplitterForm() {
    $('#formChapterSplitter')[0].reset();
    $('#splitterFileInput').val('');
    $('#splitterFileNameDisplay').html('Kéo thả file truyện hoặc bấm vào đây để chọn file');
    $('#splitterRawText').val('');
    $('#splitterResultWrapper').hide();
    currentSplitChapters = [];
  }

  // Khởi tạo hiển thị kết quả tách chương
  function renderSplitterResults(resData, saveToHistory) {
    currentSplitChapters = resData.chapters || [];
    $('#resTotalChapters').text(resData.total_chapters || currentSplitChapters.length);
    $('#resTotalWords').text(Number(resData.total_words || 0).toLocaleString());
    const avg = currentSplitChapters.length ? Math.round((resData.total_words || 0) / currentSplitChapters.length) : 0;
    $('#resAvgWords').text(avg.toLocaleString());

    // Render danh sách thẻ chương
    let html = '';
    currentSplitChapters.forEach(function(chap, idx) {
      html += '<div class="split-chapter-card" style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px 20px; box-shadow: var(--shadow-sm);">';
      html += '  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">';
      html += '    <div style="display: flex; align-items: center; gap: 10px;">';
      html += '      <span style="background: var(--pastel-green); color: var(--primary-green); font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: var(--radius-sm);"><i class="fa-solid fa-bookmark"></i> ' + (chap.title || ('Chương ' + chap.number)) + '</span>';
      html += '      <span style="font-size: 12.5px; color: var(--text-muted);"><i class="fa-solid fa-font"></i> <strong>' + Number(chap.words).toLocaleString() + '</strong> từ</span>';
      html += '    </div>';
      html += '    <div style="display: flex; gap: 8px;">';
      html += '      <button type="button" class="btn btn-secondary btn-xs btn-copy-split-chap" data-index="' + idx + '"><i class="fa-solid fa-copy"></i> Sao chép</button>';
      html += '      <button type="button" class="btn btn-primary btn-xs btn-download-split-chap" data-index="' + idx + '"><i class="fa-solid fa-download"></i> Tải về (.txt)</button>';
      html += '    </div>';
      html += '  </div>';
      html += '  <textarea class="form-input" rows="5" readonly style="width: 100%; font-size: 13px; line-height: 1.6; background: #f8fafc; font-family: inherit; resize: vertical;">' + $('<div>').text(chap.content).html() + '</textarea>';
      html += '</div>';
    });

    $('#splitterChaptersList').html(html);
    $('#splitterResultWrapper').slideDown(300);
    $('html, body').animate({ scrollTop: $('#splitterResultWrapper').offset().top - 80 }, 400);

    // Lưu vào lịch sử nếu đây là phiên vừa tách mới
    if (saveToHistory !== false) {
      const now = new Date();
      const dateStr = now.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
      const itemTitle = resData.file_name || (resData.chapters && resData.chapters[0] ? resData.chapters[0].title : 'Truyện tách ' + dateStr);

      addSplitterHistory({
        id: 'split_' + Date.now(),
        title: itemTitle,
        total_chapters: resData.total_chapters || currentSplitChapters.length,
        total_words: resData.total_words || 0,
        avg_words: avg,
        target_words: resData.target_words || 2500,
        created_at: dateStr,
        chapters: currentSplitChapters
      });
      muopAlert('Đã tách thành công ' + currentSplitChapters.length + ' chương và lưu vào lịch sử!', 'success');
    }
  }

  function executeSplitAjax(formData, textForFallback, targetWords, fileObj) {
    const $btn = $('#btnSubmitSplit');
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang phân tích và tách chương...');

    function doFallback() {
      if (textForFallback && textForFallback.trim()) {
        const fallbackRes = clientSideSplitStory(textForFallback, targetWords);
        if (fallbackRes && fallbackRes.chapters && fallbackRes.chapters.length) {
          fallbackRes.file_name = fileObj ? fileObj.name : 'Nội dung dán trực tiếp';
          renderSplitterResults(fallbackRes, true);
          return true;
        }
      }
      return false;
    }

    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'text',
      success: function(rawRes) {
        $btn.prop('disabled', false).html(originalText);
        let res = null;
        try {
          if (typeof rawRes === 'object') {
            res = rawRes;
          } else {
            const start = rawRes.indexOf('{');
            const end = rawRes.lastIndexOf('}');
            if (start !== -1 && end !== -1) {
              res = JSON.parse(rawRes.substring(start, end + 1));
            }
          }
        } catch(e) {
          console.warn('JSON parse error from server response:', e);
        }

        if (res && res.success && res.data) {
          if (!res.data.file_name && fileObj) {
            res.data.file_name = fileObj.name;
          }
          renderSplitterResults(res.data, true);
        } else if (res && !res.success && res.data && res.data.message) {
          if (!doFallback()) {
            muopAlert(res.data.message, 'error');
          }
        } else {
          if (!doFallback()) {
            muopAlert('Không thể phân tích dữ liệu trả về từ máy chủ!', 'error');
          }
        }
      },
      error: function(xhr) {
        $btn.prop('disabled', false).html(originalText);
        let res = null;
        try {
          const raw = xhr.responseText || '';
          const start = raw.indexOf('{');
          const end = raw.lastIndexOf('}');
          if (start !== -1 && end !== -1) {
            res = JSON.parse(raw.substring(start, end + 1));
          }
        } catch(e) {}

        if (res && res.success && res.data) {
          if (!res.data.file_name && fileObj) {
            res.data.file_name = fileObj.name;
          }
          renderSplitterResults(res.data, true);
          return;
        }

        if (!doFallback()) {
          muopAlert('Lỗi kết nối máy chủ khi xử lý file!', 'error');
        }
      }
    });
  }

  // Submit form tách truyện
  $('#formChapterSplitter').on('submit', function(e) {
    e.preventDefault();
    const fileInput = document.getElementById('splitterFileInput');
    let rawText = $('#splitterRawText').val().trim();
    const targetWords = parseInt($('#splitterTargetWords').val()) || 2500;
    const file = (fileInput.files && fileInput.files[0]) ? fileInput.files[0] : null;

    if (!file && !rawText) {
      muopAlert('Vui lòng chọn 1 file (.docx, .doc, .txt, .pdf) hoặc dán nội dung văn bản vào khung!', 'warning');
      return;
    }

    // Nếu chọn file .txt, đọc trực tiếp bằng FileReader để gửi nội dung
    if (file && (file.name.toLowerCase().endsWith('.txt') || file.type === 'text/plain')) {
      const reader = new FileReader();
      reader.onload = function(evt) {
        const fileContent = evt.target.result;
        const textToUse = rawText ? (fileContent + "\n\n" + rawText) : fileContent;
        const formData = new FormData();
        formData.append('action', 'muop_admin_action');
        formData.append('sub_action', 'split_story_file');
        formData.append('nonce', muopConfig.nonce);
        formData.append('target_words', targetWords);
        formData.append('raw_text', textToUse);
        formData.append('story_file', file);

        executeSplitAjax(formData, textToUse, targetWords, file);
      };
      reader.onerror = function() {
        const formData = new FormData();
        formData.append('action', 'muop_admin_action');
        formData.append('sub_action', 'split_story_file');
        formData.append('nonce', muopConfig.nonce);
        formData.append('target_words', targetWords);
        formData.append('story_file', file);
        if (rawText) formData.append('raw_text', rawText);
        executeSplitAjax(formData, rawText, targetWords, file);
      };
      reader.readAsText(file, 'UTF-8');
      return;
    }

    // Các định dạng file khác (.docx, .doc, .pdf)
    const formData = new FormData();
    formData.append('action', 'muop_admin_action');
    formData.append('sub_action', 'split_story_file');
    formData.append('nonce', muopConfig.nonce);
    formData.append('target_words', targetWords);
    if (file) {
      formData.append('story_file', file);
    }
    if (rawText) {
      formData.append('raw_text', rawText);
    }

    executeSplitAjax(formData, rawText, targetWords, file);
  });

  // Nút Tách Nội Dung Mới / Làm mới workspace
  $('#btnResetSplitterWorkspace').on('click', function(e) {
    e.preventDefault();
    resetSplitterForm();
    $('html, body').animate({ scrollTop: $('#admTabSplitter').offset().top - 80 }, 300);
    muopAlert('Đã làm mới form, sẵn sàng để bạn tải file hoặc dán nội dung mới!', 'info');
  });

  // Xem lại phiên tách từ lịch sử
  $(document).on('click', '.btn-review-history-split', function() {
    const id = $(this).data('id');
    const list = getSplitterHistory();
    const item = list.find(function(it) { return it.id === id; });
    if (!item || !item.chapters) {
      muopAlert('Không tìm thấy dữ liệu của phiên tách này!', 'warning');
      return;
    }
    renderSplitterResults(item, false);
    muopAlert('Đang xem lại kết quả: "' + item.title + '" (' + item.created_at + ')', 'info');
  });

  // Tải file .txt gộp từ lịch sử
  $(document).on('click', '.btn-download-history-split', function() {
    const id = $(this).data('id');
    const list = getSplitterHistory();
    const item = list.find(function(it) { return it.id === id; });
    if (!item || !item.chapters || !item.chapters.length) {
      muopAlert('Phiên tách này không có chương nào để tải về!', 'warning');
      return;
    }

    let allText = '';
    item.chapters.forEach(function(ch) {
      allText += '=== ' + ch.title + ' === (' + ch.words + " từ)\n\n" + ch.content + "\n\n\n";
    });

    const blob = new Blob([allText], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    const cleanName = (item.title || 'Truyen_Tach').replace(/[^\w\d\-_.]+/gi, '_');
    a.download = cleanName.endsWith('.txt') ? cleanName : (cleanName + '.txt');
    if (document.body) {
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    URL.revokeObjectURL(url);
  });

  // Xóa 1 phiên trong lịch sử
  $(document).on('click', '.btn-delete-history-split', function() {
    const id = $(this).data('id');
    muopConfirm('Bạn có chắc chắn muốn xóa phiên tách này khỏi lịch sử không?', function() {
      let list = getSplitterHistory();
      list = list.filter(function(it) { return it.id !== id; });
      saveSplitterHistoryList(list);
      renderSplitterHistoryTable();
      muopAlert('Đã xóa phiên tách khỏi lịch sử!', 'success');
    }, null, { isDanger: true, confirmText: 'Xóa' });
  });

  // Xóa toàn bộ lịch sử tách chương
  $('#btnClearSplitterHistory').on('click', function(e) {
    e.preventDefault();
    muopConfirm('Bạn có chắc chắn muốn xóa sạch toàn bộ lịch sử tách chương không? Hành động này không thể hoàn tác.', function() {
      try {
        localStorage.removeItem(SPLITTER_HISTORY_KEY);
      } catch(e) {}
      renderSplitterHistoryTable();
      muopAlert('Đã xóa sạch toàn bộ lịch sử tách chương!', 'success');
    }, null, { isDanger: true, confirmText: 'Xóa Tất Cả' });
  });

  // Copy 1 chương riêng lẻ
  $(document).on('click', '.btn-copy-split-chap', function() {
    const idx = $(this).data('index');
    const chap = currentSplitChapters[idx];
    if (!chap) return;
    navigator.clipboard.writeText(chap.content).then(function() {
      muopAlert('Đã sao chép nội dung ' + (chap.title || ('Chương ' + chap.number)) + ' vào bộ nhớ tạm!', 'success');
    });
  });

  // Tải về 1 chương riêng lẻ dạng .txt
  $(document).on('click', '.btn-download-split-chap', function() {
    const idx = $(this).data('index');
    const chap = currentSplitChapters[idx];
    if (!chap) return;
    const blob = new Blob([chap.title + "\n\n" + chap.content], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = (chap.title || ('Chuong_' + chap.number)).replace(/\s+/g, '_') + '.txt';
    if (document.body) {
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    URL.revokeObjectURL(url);
  });

  // Sao chép toàn bộ các chương
  $('#btnCopyAllChapters').on('click', function() {
    if (!currentSplitChapters.length) return;
    let allText = '';
    currentSplitChapters.forEach(function(ch) {
      allText += '=== ' + ch.title + ' === (' + ch.words + " từ)\n\n" + ch.content + "\n\n\n";
    });
    navigator.clipboard.writeText(allText).then(function() {
      muopAlert('Đã sao chép toàn bộ ' + currentSplitChapters.length + ' chương vào bộ nhớ tạm!', 'success');
    });
  });

  // Tải về toàn bộ các chương gộp chung file .txt
  $('#btnDownloadAllChapters').on('click', function() {
    if (!currentSplitChapters.length) return;
    let allText = '';
    currentSplitChapters.forEach(function(ch) {
      allText += '=== ' + ch.title + " ===\n\n" + ch.content + "\n\n\n";
    });
    const blob = new Blob([allText], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'Toan_Bo_Chuong_Da_Tach.txt';
    if (document.body) {
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    URL.revokeObjectURL(url);
  });

  // Tự động reset form trống khi tải/reload trang, đồng thời tải bảng lịch sử
  resetSplitterForm();
  renderSplitterHistoryTable();

  // =========================================================================
  // CÔNG CỤ LẤY TRUYỆN TỰ ĐỘNG TỪ VIVUTRUYEN.NET (ADMIN CONTROLLER)
  // =========================================================================
  const VIVU_HISTORY_KEY = 'muop_vivu_crawler_history';
  let currentVivuStory = null;
  let currentVivuResults = [];
  let isVivuCrawling = false;
  let shouldStopVivu = false;

  function getVivuHistory() {
    try {
      const data = localStorage.getItem(VIVU_HISTORY_KEY);
      return data ? JSON.parse(data) : [];
    } catch(e) {
      return [];
    }
  }

  function saveVivuHistoryList(list) {
    try {
      localStorage.setItem(VIVU_HISTORY_KEY, JSON.stringify(list.slice(0, 30)));
    } catch(e) {}
  }

  function renderVivuHistoryTable() {
    const list = getVivuHistory();
    const $tbody = $('#vivuHistoryListBody');
    const $empty = $('#vivuHistoryEmpty');

    if (!list.length) {
      $tbody.html('');
      $empty.show();
      return;
    }

    $empty.hide();
    let html = '';
    list.forEach(function(item, idx) {
      html += '<tr>';
      html += '<td style="text-align: center; font-weight: 600;">' + (idx + 1) + '</td>';
      html += '<td><strong style="color: var(--text-color);">' + $('<div>').text(item.title).html() + '</strong><br><small style="color: var(--text-muted);">' + $('<div>').text(item.url || '').html() + '</small></td>';
      html += '<td>' + $('<div>').text(item.author || 'Chưa rõ').html() + '</td>';
      html += '<td style="text-align: center;"><span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700;">' + (item.total_chapters || 0) + ' ch</span></td>';
      html += '<td style="text-align: center; font-weight: 600;">' + Number(item.total_words || 0).toLocaleString() + ' từ</td>';
      html += '<td style="text-align: center; color: var(--text-muted); font-size: 12px;">' + (item.created_at || '') + '</td>';
      html += '<td style="text-align: center; white-space: nowrap;">';
      html += '  <button type="button" class="btn btn-secondary btn-xs btn-load-vivu-history" data-id="' + item.id + '" title="Xem lại" style="margin-right: 4px;"><i class="fa-solid fa-eye"></i> Xem</button>';
      html += '  <button type="button" class="btn btn-success btn-xs btn-download-vivu-history" data-id="' + item.id + '" title="Tải file .txt" style="margin-right: 4px;"><i class="fa-solid fa-download"></i> Tải .txt</button>';
      html += '  <button type="button" class="btn btn-danger btn-xs btn-delete-vivu-history" data-id="' + item.id + '" title="Xóa"><i class="fa-solid fa-trash-can"></i></button>';
      html += '</td>';
      html += '</tr>';
    });

    $tbody.html(html);
  }

  // 1. Phân tích & Lấy thông tin truyện
  $('#btnVivuFetchInfo').on('click', function(e) {
    e.preventDefault();
    const rawUrl = $('#vivuStoryUrl').val().trim();
    if (!rawUrl) {
      muopAlert('Vui lòng nhập đường dẫn truyện trên VivuTruyen.net!', 'warning');
      $('#vivuStoryUrl').focus();
      return;
    }

    const $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin"></i> Đang phân tích...');

    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: {
        action: 'muop_vivu_fetch_story_info',
        url: rawUrl,
        nonce: muopConfig.nonce
      },
      success: function(res) {
        $btn.prop('disabled', false).html('<i class="fa-solid fa-magnifying-glass"></i> 1. Phân Tích & Lấy Thông Tin');
        if (!res.success) {
          muopAlert(res.data ? res.data.message : 'Không thể lấy thông tin truyện!', 'error');
          return;
        }

        currentVivuStory = res.data;
        renderVivuStoryPreview(currentVivuStory);
        muopAlert('Đã tìm thấy truyện: "' + currentVivuStory.title + '" với ' + currentVivuStory.total_chaps + ' chương!', 'success');
      },
      timeout: 60000,
      error: function(xhr, status, error) {
        $btn.prop('disabled', false).html('<i class="fa-solid fa-magnifying-glass"></i> 1. Phân Tích & Lấy Thông Tin');
        muopAlert(handleAjaxError(xhr, status, error, 'Không thể kết nối đến máy chủ để lấy thông tin truyện!'), 'error');
      }
    });
  });

  // Render Story Preview Box
  function renderVivuStoryPreview(data) {
    $('#vivuTitleDisplay').text(data.title || 'Không có tiêu đề');
    $('#vivuAuthorDisplay').text(data.author || 'Chưa rõ');
    $('#vivuGenresDisplay').text(data.genres || 'Đang cập nhật');
    $('#vivuStatusDisplay').text(data.status || 'Đang tiến hành');
    $('#vivuBadgeTotalChaps').text(data.total_chaps + ' Chương');
    $('#vivuDescDisplay').text(data.description || 'Chưa có tóm tắt văn án.');
    if (data.cover) {
      $('#vivuCoverImg').attr('src', data.cover);
    } else {
      $('#vivuCoverImg').attr('src', '');
    }

    // Render Chapters Checkboxes Grid
    const $grid = $('#vivuChaptersGrid');
    $grid.html('');

    (data.chapters || []).forEach(function(chap) {
      const itemHtml = '<label style="display: flex; align-items: center; gap: 8px; font-size: 13px; padding: 5px 8px; border-radius: 4px; background: #f8fafc; border: 1px solid #e2e8f0; cursor: pointer; user-select: none;">' +
        '<input type="checkbox" class="vivu-chap-checkbox" value="' + chap.index + '" data-url="' + $('<div>').text(chap.url).html() + '" data-title="' + $('<div>').text(chap.title).html() + '" checked style="accent-color: #0284c7; width: 15px; height: 15px;" />' +
        '<span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' + $('<div>').text(chap.title).html() + '">' +
        '<strong>#' + chap.index + '</strong> ' + $('<div>').text(chap.title).html() +
        '</span>' +
      '</label>';
      $grid.append(itemHtml);
    });

    updateVivuSelectedSummary();

    $('#vivuStoryPreviewBox').slideDown(300);
    $('#vivuProgressBox').hide();
    $('#vivuResultBox').hide();

    $('html, body').animate({ scrollTop: $('#vivuStoryPreviewBox').offset().top - 80 }, 300);
  }

  // Update selected count summary
  function updateVivuSelectedSummary() {
    const total = $('.vivu-chap-checkbox').length;
    const selected = $('.vivu-chap-checkbox:checked').length;
    $('#vivuSelectedCount').text(selected);
    $('#vivuTotalCount').text(total);
    $('#vivuSelectAllChaps').prop('checked', selected > 0 && selected === total);
  }

  // Toggle Select All
  $('#vivuSelectAllChaps').on('change', function() {
    const checked = $(this).is(':checked');
    $('.vivu-chap-checkbox').prop('checked', checked);
    updateVivuSelectedSummary();
  });

  // Individual checkbox change
  $(document).on('change', '.vivu-chap-checkbox', function() {
    updateVivuSelectedSummary();
  });

  // Cancel Preview
  $('#btnVivuCancelPreview').on('click', function() {
    $('#vivuStoryPreviewBox').slideUp(250);
  });

  // Stop Crawling
  $('#btnVivuStopCrawl').on('click', function() {
    shouldStopVivu = true;
    $(this).prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang dừng...');
  });

  // 2. Bắt đầu tải toàn bộ nội dung chương
  $('#btnVivuStartCrawl').on('click', function() {
    if (!currentVivuStory) return;

    const selectedChaps = [];
    $('.vivu-chap-checkbox:checked').each(function() {
      selectedChaps.push({
        index: parseInt($(this).val(), 10),
        title: $(this).data('title'),
        url: $(this).data('url')
      });
    });

    if (!selectedChaps.length) {
      muopAlert('Vui lòng chọn ít nhất 1 chương để tải!', 'warning');
      return;
    }

    const batchSize = parseInt($('#vivuBatchSize').val(), 10) || 2;
    currentVivuResults = [];
    isVivuCrawling = true;
    shouldStopVivu = false;

    $('#btnVivuStopCrawl').prop('disabled', false).html('<i class="fa-solid fa-pause"></i> Tạm dừng tải');
    $('#vivuProgressBox').slideDown(250);
    $('#vivuResultBox').hide();

    // Reset Progress Bar
    $('#vivuProgressBar').css('width', '0%');
    $('#vivuProgressPercentText').text('0%');
    $('#vivuProgressStatusText').text('Đang chuẩn bị tải ' + selectedChaps.length + ' chương...');
    $('#vivuProgressDetail').text('Đã tải 0/' + selectedChaps.length + ' chương (0%)');

    $('html, body').animate({ scrollTop: $('#vivuProgressBox').offset().top - 80 }, 300);

    // Queue processor
    let currentIndex = 0;

    function processNextBatch() {
      if (shouldStopVivu) {
        isVivuCrawling = false;
        $('#vivuProgressStatusText').text('Đã tạm dừng tải theo yêu cầu.');
        muopAlert('Đã tạm dừng quá trình lấy truyện!', 'info');
        if (currentVivuResults.length > 0) {
          finalizeVivuResults(selectedChaps.length);
        }
        return;
      }

      if (currentIndex >= selectedChaps.length) {
        // Complete!
        isVivuCrawling = false;
        finalizeVivuResults(selectedChaps.length);
        return;
      }

      const batch = selectedChaps.slice(currentIndex, currentIndex + batchSize);
      const batchNames = batch.map(function(c) { return '#' + c.index; }).join(', ');
      $('#vivuProgressStatusText').html('Đang tải chương: <strong>' + batchNames + '</strong>...');

      $.ajax({
        url: muopConfig.ajaxUrl,
        type: 'POST',
        data: {
          action: 'muop_vivu_fetch_chapters_batch',
          chapters: batch,
          nonce: muopConfig.nonce
        },
        success: function(res) {
          if (res.success && res.data && res.data.results) {
            res.data.results.forEach(function(item) {
              currentVivuResults.push(item);
            });
          }

          currentIndex += batch.length;
          const pct = Math.min(100, Math.round((currentIndex / selectedChaps.length) * 100));
          $('#vivuProgressBar').css('width', pct + '%');
          $('#vivuProgressPercentText').text(pct + '%');
          $('#vivuProgressDetail').text('Đã tải thành công ' + Math.min(currentIndex, selectedChaps.length) + '/' + selectedChaps.length + ' chương (' + pct + '%)');

          // Small delay (200ms) to avoid overwhelming remote server
          setTimeout(processNextBatch, 200);
        },
        error: function(xhr, status, error) {
          console.warn('Lỗi tải batch vivu:', error);
          currentIndex += batch.length;
          setTimeout(processNextBatch, 300);
        }
      });
    }

    processNextBatch();
  });

  // Tạo định dạng văn bản file hoàn chỉnh
  function buildVivuCompleteText(story, results) {
    let out = '';
    out += '================================================================================\n';
    out += 'THÔNG TIN TRUYỆN: ' + (story.title || '').toUpperCase() + '\n';
    out += '================================================================================\n';
    out += 'Tác giả: ' + (story.author || 'Chưa rõ') + '\n';
    out += 'Thể loại: ' + (story.genres || 'Đang cập nhật') + '\n';
    out += 'Trạng thái: ' + (story.status || 'Đang tiến hành') + '\n';
    out += 'Nguồn gốc: ' + (story.story_url || '') + '\n';
    out += 'Tổng số chương thu thập: ' + results.length + '\n';
    out += 'Thời gian lấy dữ liệu: ' + (new Date()).toLocaleString('vi-VN') + '\n';
    out += '================================================================================\n\n';

    if (story.description) {
      out += '--------------------------------------------------------------------------------\n';
      out += 'VĂN ÁN / MÔ TẢ TRUYỆN\n';
      out += '--------------------------------------------------------------------------------\n';
      out += story.description.trim() + '\n\n';
    }

    out += '================================================================================\n';
    out += 'NỘI DUNG CHI TIẾT CÁC CHƯƠNG\n';
    out += '================================================================================\n\n';

    // Sort results by index ascending
    results.sort(function(a, b) { return a.index - b.index; });

    results.forEach(function(chap) {
      out += '--------------------------------------------------------------------------------\n';
      out += (chap.title || ('Chương ' + chap.index)).toUpperCase() + '\n';
      out += '--------------------------------------------------------------------------------\n\n';
      out += (chap.content || '').trim() + '\n\n\n';
    });

    return out;
  }

  // Kết thúc quá trình crawl và hiển thị kết quả
  function finalizeVivuResults(expectedTotal) {
    $('#vivuProgressBox').slideUp(200);

    let totalWords = 0;
    currentVivuResults.forEach(function(r) { totalWords += (r.words || 0); });

    const completeText = buildVivuCompleteText(currentVivuStory, currentVivuResults);
    $('#vivuPreviewTextarea').val(completeText);
    $('#vivuFileSizeEstimate').text('Độ dài: ~' + Number(completeText.length).toLocaleString() + ' ký tự (' + Number(totalWords).toLocaleString() + ' từ)');

    $('#vivuResultTitle').text('Đã Thu Thập Hoàn Tất Toàn Bộ Truyện!');
    $('#vivuResultStats').text('Đã tải thành công ' + currentVivuResults.length + '/' + expectedTotal + ' chương. Tổng cộng ' + Number(totalWords).toLocaleString() + ' từ.');

    $('#vivuResultBox').slideDown(300);
    $('html, body').animate({ scrollTop: $('#vivuResultBox').offset().top - 80 }, 300);

    // Save to Local History
    const historyItem = {
      id: 'vivu_' + Date.now(),
      title: currentVivuStory.title,
      url: currentVivuStory.story_url,
      author: currentVivuStory.author,
      genres: currentVivuStory.genres,
      total_chapters: currentVivuResults.length,
      total_words: totalWords,
      created_at: (new Date()).toLocaleString('vi-VN'),
      full_text: completeText
    };

    let list = getVivuHistory();
    list.unshift(historyItem);
    saveVivuHistoryList(list);
    renderVivuHistoryTable();

    muopAlert('Thu thập thành công ' + currentVivuResults.length + ' chương! Bạn có thể Tải file .txt hoặc Chuyển sang Tách truyện ngay bây giờ.', 'success');
  }

  // Nút 1: Tải File .txt
  $('#btnVivuDownloadTxt').on('click', function() {
    const text = $('#vivuPreviewTextarea').val();
    if (!text) return;
    const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    const safeTitle = (currentVivuStory && currentVivuStory.title ? currentVivuStory.title : 'Truyen_Vivu')
      .replace(/[^a-zA-Z0-9\u00C0-\u1EF9]+/g, '_')
      .replace(/^_+|_+$/g, '');
    a.download = safeTitle + '_Full.txt';
    if (document.body) {
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    URL.revokeObjectURL(url);
    muopAlert('Đang tải file "' + a.download + '" về máy của bạn...', 'success');
  });

  // Nút 2: Chuyển Sang Tách Truyện Tự Động
  $('#btnVivuSendToSplitter').on('click', function() {
    const text = $('#vivuPreviewTextarea').val();
    if (!text) return;

    // Chuyển sang tab admTabSplitter
    switchAdminTab('admTabSplitter');

    // Nạp sẵn toàn bộ văn bản vào khung textarea tách truyện
    $('#splitterRawText').val(text);

    // Cuộn tới vị trí khung dán truyện
    setTimeout(function() {
      $('html, body').animate({ scrollTop: $('#splitterRawText').offset().top - 120 }, 300);
      muopAlert('Đã nạp toàn bộ nội dung truyện vào công cụ Tách truyện tự động!', 'success');
    }, 200);
  });

  // Nút 3: Sao Chép Toàn Bộ
  $('#btnVivuCopyAll').on('click', function() {
    const text = $('#vivuPreviewTextarea').val();
    if (!text) return;
    navigator.clipboard.writeText(text).then(function() {
      muopAlert('Đã sao chép toàn bộ nội dung truyện vào bộ nhớ tạm (Clipboard)!', 'success');
    }).catch(function() {
      $('#vivuPreviewTextarea').select();
      document.execCommand('copy');
      muopAlert('Đã sao chép vào bộ nhớ tạm!', 'success');
    });
  });

  // Xem lại 1 truyện từ lịch sử
  $(document).on('click', '.btn-load-vivu-history', function() {
    const id = $(this).data('id');
    const list = getVivuHistory();
    const item = list.find(function(it) { return it.id === id; });
    if (!item || !item.full_text) {
      muopAlert('Không tìm thấy nội dung truyện!', 'error');
      return;
    }

    currentVivuStory = {
      title: item.title,
      author: item.author,
      genres: item.genres,
      story_url: item.url
    };

    $('#vivuPreviewTextarea').val(item.full_text);
    $('#vivuResultTitle').text('Xem Lại Truyện: ' + item.title);
    $('#vivuResultStats').text('Đã lưu ' + item.total_chapters + ' chương. Tổng cộng ' + Number(item.total_words || 0).toLocaleString() + ' từ.');
    $('#vivuFileSizeEstimate').text('Độ dài: ~' + Number(item.full_text.length).toLocaleString() + ' ký tự');

    $('#vivuStoryPreviewBox').hide();
    $('#vivuProgressBox').hide();
    $('#vivuResultBox').slideDown(300);
    $('html, body').animate({ scrollTop: $('#vivuResultBox').offset().top - 80 }, 300);
  });

  // Tải file .txt từ 1 item lịch sử
  $(document).on('click', '.btn-download-vivu-history', function() {
    const id = $(this).data('id');
    const list = getVivuHistory();
    const item = list.find(function(it) { return it.id === id; });
    if (!item || !item.full_text) return;

    const blob = new Blob([item.full_text], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    const safeTitle = (item.title || 'Truyen_Vivu')
      .replace(/[^a-zA-Z0-9\u00C0-\u1EF9]+/g, '_')
      .replace(/^_+|_+$/g, '');
    a.download = safeTitle + '_Full.txt';
    if (document.body) {
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    URL.revokeObjectURL(url);
    muopAlert('Đang tải file "' + a.download + '" về máy...', 'success');
  });

  // Xóa 1 item lịch sử
  $(document).on('click', '.btn-delete-vivu-history', function() {
    const id = $(this).data('id');
    muopConfirm('Bạn có chắc chắn muốn xóa bản ghi này khỏi lịch sử lấy truyện?', function() {
      let list = getVivuHistory();
      list = list.filter(function(it) { return it.id !== id; });
      saveVivuHistoryList(list);
      renderVivuHistoryTable();
      muopAlert('Đã xóa bản ghi khỏi lịch sử!', 'success');
    }, null, { isDanger: true, confirmText: 'Xóa' });
  });

  // Xóa toàn bộ lịch sử
  $('#btnClearVivuHistory').on('click', function(e) {
    e.preventDefault();
    muopConfirm('Bạn có chắc chắn muốn xóa sạch toàn bộ lịch sử lấy crawl tự động không?', function() {
      try {
        localStorage.removeItem(VIVU_HISTORY_KEY);
      } catch(e) {}
      renderVivuHistoryTable();
      muopAlert('Đã xóa toàn bộ lịch sử lấy crawl tự động!', 'success');
    }, null, { isDanger: true, confirmText: 'Xóa Tất Cả' });
  });

  // Khởi tạo bảng lịch sử ban đầu
  renderVivuHistoryTable();

  // Nominate change
  $(document).on('change_nominate', function(e, storyId, val) {
    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: {
        action: 'muop_admin_action',
        sub_action: 'nominate_story',
        story_id: storyId,
        nominate_type: val,
        nonce: muopConfig.nonce
      },
      success: function(res) {
        $('#tableAdminStories .adm-story-row[data-story-id="' + storyId + '"]').data('nominate', val).attr('data-nominate', val);
        muopAlert(res.data ? res.data.message : 'Đã cập nhật đề cử!', 'success');
      },
      error: function(xhr, status, error) {
        muopAlert(handleAjaxError(xhr, status, error, 'Lỗi cập nhật đề cử!'), 'error');
      }
    });
  });

  // Role change
  $(document).on('change_role', function(e, userId, newRole) {
    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: {
        action: 'muop_admin_action',
        sub_action: 'change_user_role',
        user_id: userId,
        new_role: newRole,
        nonce: muopConfig.nonce
      },
      success: function(res) {
        muopAlert(res.data ? res.data.message : 'Đã đổi quyền người dùng!', 'success');
      },
      error: function(xhr, status, error) {
        muopAlert(handleAjaxError(xhr, status, error, 'Lỗi đổi quyền người dùng!'), 'error');
      }
    });
  });

  // Create user form submit
  $('#formAdminCreateUser').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: $(this).serialize() + '&action=muop_admin_action&sub_action=create_user&nonce=' + muopConfig.nonce,
      success: function(res) {
        if (res.success) {
          muopAlert(res.data ? res.data.message : 'Thêm người dùng thành công!', 'success', function() {
            window.location.reload();
          });
        } else {
          muopAlert(res.data ? res.data.message : 'Lỗi thêm người dùng!', 'error');
        }
      },
      error: function(xhr, status, error) {
        muopAlert(handleAjaxError(xhr, status, error, 'Lỗi kết nối khi thêm người dùng!'), 'error');
      }
    });
  });

  // =========================================================================
  // 12. QUẢN LÝ TÍNH NĂNG & NÂNG CẤP HỆ THỐNG MỚI (HIỂN THỊ ĐẦU TRANG)
  // =========================================================================
  $('#btnToggleAddFeatureBox').on('click', function() {
    $('#boxAdminAddFeature').slideToggle(200);
  });
  $('#btnCloseAddFeatureBox, #btnCancelAddFeature').on('click', function() {
    $('#boxAdminAddFeature').slideUp(200);
  });

  $('#formAdminAddFeature').on('submit', function(e) {
    e.preventDefault();
    const $btn = $('#btnSaveAddFeature');
    const orig = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');

    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: $(this).serialize() + '&action=muop_admin_add_feature_update&nonce=' + muopConfig.nonce,
      success: function(res) {
        $btn.prop('disabled', false).html(orig);
        if (res.success && res.data && res.data.feature) {
          const cf = res.data.feature;
          const html = `
            <div class="adm-feature-card is-custom-feature" data-id="${cf.id}" style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: var(--radius-md); padding: 16px 18px; display: flex; flex-direction: column; gap: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); animation: fadeIn 0.3s ease;">
              <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                  <span class="badge badge-hot" style="font-size: 11px; font-weight: 800; padding: 3px 8px;">${cf.badge || 'MỚI'}</span>
                  <span class="badge badge-approved" style="font-size: 11px;">${cf.version}</span>
                  <span style="font-size: 12px; color: var(--text-muted); background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 4px;">${cf.category}</span>
                  <h5 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--text-color);">${$('<div>').text(cf.title).html()}</h5>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 11.5px; color: #94a3b8;"><i class="fa-regular fa-clock"></i> ${cf.created_at}</span>
                  <button type="button" class="btn btn-secondary btn-xs btn-delete-custom-feature" data-id="${cf.id}" style="color: #ef4444; border-color: #fecaca; background: #fff5f5;" title="Xóa cập nhật này">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>
              </div>
              <p style="margin: 0; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                ${$('<div>').text(cf.description).html().replace(/\n/g, '<br>')}
              </p>
            </div>
          `;
          $('#admNewFeaturesContainer').prepend(html);
          $('#formAdminAddFeature')[0].reset();
          $('#boxAdminAddFeature').slideUp(200);
          muopAlert(res.data.message || 'Đã thêm tính năng mới thành công và hiển thị ở đầu trang!', 'success');
        } else {
          muopAlert(res.data ? res.data.message : 'Lỗi thêm tính năng mới!', 'error');
        }
      },
      error: function(xhr, status, error) {
        $btn.prop('disabled', false).html(orig);
        muopAlert(handleAjaxError(xhr, status, error, 'Lỗi kết nối máy chủ khi lưu tính năng!'), 'error');
      }
    });
  });

  $(document).on('click', '.btn-delete-custom-feature', function() {
    const featId = $(this).data('id');
    const $card = $(this).closest('.adm-feature-card');
    if (!featId) return;

    muopConfirm(
      'Bạn có chắc chắn muốn xóa bản ghi tính năng này?',
      function() {
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_admin_delete_feature_update',
            id: featId,
            nonce: muopConfig.nonce
          },
          success: function(res) {
            if (res.success) {
              $card.slideUp(200, function() { $(this).remove(); });
              muopAlert('Đã xóa tính năng thành công!', 'success');
            } else {
              muopAlert(res.data ? res.data.message : 'Lỗi xóa tính năng!', 'error');
            }
          },
          error: function(xhr, status, error) {
            muopAlert(handleAjaxError(xhr, status, error, 'Lỗi kết nối máy chủ khi xóa nhóm!'), 'error');
          }
        });
      },
      null,
      {
        title: 'Xác Nhận Xóa',
        confirmText: 'Xóa Bản Ghi',
        cancelText: 'Hủy Bỏ',
        isDanger: true
      }
    );
  });

  // =========================================================================
  // 17. NÚT BẤM NỔI CUỘN LÊN ĐẦU TRANG (BACK TO TOP) CHO TOÀN BỘ ADMIN (HÌNH 4/5)
  // =========================================================================
  const $adminBackToTop = $('#adminBackToTop');
  if ($adminBackToTop.length) {
    const handleScrollTopBtn = function() {
      const topOffset = $(window).scrollTop() || $('html').scrollTop() || $('.admin-db-main').scrollTop() || 0;
      if (topOffset > 180) {
        $adminBackToTop.addClass('is-visible');
      } else {
        $adminBackToTop.removeClass('is-visible');
      }
    };

    $(window).on('scroll', handleScrollTopBtn);
    $('.admin-db-main').on('scroll', handleScrollTopBtn);

    $adminBackToTop.on('click', function(e) {
      e.preventDefault();
      $('html, body, .admin-db-main').animate({ scrollTop: 0 }, 350);
    });
  }

  // =========================================================================
  // 18. BỘ ĐIỀU KHIỂN KÉO QUA LẠI / CUỘN NGANG CHO TẤT CẢ CÁC BẢNG DỮ LIỆU
  // =========================================================================
  function initTableScrollControls() {
    $('.db-table-container, .table-responsive').each(function() {
      const $container = $(this);
      const containerEl = this;

      const $table = $container.find('table');
      if (!$table.length) return;

      // Đảm bảo container nằm trong wrapper có position: relative
      let $wrapper = $container.closest('.db-table-scroll-wrapper');
      if (!$wrapper.length) {
        $container.wrap('<div class="db-table-scroll-wrapper" style="position: relative; width: 100%;"></div>');
        $wrapper = $container.closest('.db-table-scroll-wrapper');
      }

      // Tạo thanh điều khiển trên đầu bảng (Top Toolbar Controller)
      let $controller = $wrapper.find('> .db-table-scroll-controller');
      if (!$controller.length) {
        $controller = $(`
          <div class="db-table-scroll-controller">
            <div class="db-table-scroll-hint">
              <i class="fa-solid fa-arrows-left-right"></i>
              <span>Kéo bảng hoặc bấm nút để xem đủ các cột 👉</span>
            </div>
            <div class="db-table-scroll-btns">
              <button type="button" class="btn-table-scroll-nav btn-table-scroll-left" title="Cuộn sang trái (◀)" aria-label="Cuộn sang trái">
                <i class="fa-solid fa-arrow-left"></i> <span>Trái</span>
              </button>
              <button type="button" class="btn-table-scroll-nav btn-table-scroll-right" title="Cuộn sang phải (▶)" aria-label="Cuộn sang phải">
                <span>Phải</span> <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>
        `);
        $container.before($controller);
      }

      // Tạo 2 nút bấm nổi hai bên mép bảng (Floating Side Buttons)
      let $btnFloatLeft = $wrapper.find('> .db-table-floating-left');
      let $btnFloatRight = $wrapper.find('> .db-table-floating-right');
      if (!$btnFloatLeft.length) {
        $btnFloatLeft = $(`
          <button type="button" class="db-table-floating-btn db-table-floating-left" title="Cuộn bảng sang trái (◀)" aria-label="Cuộn sang trái">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
        `);
        $wrapper.append($btnFloatLeft);
      }
      if (!$btnFloatRight.length) {
        $btnFloatRight = $(`
          <button type="button" class="db-table-floating-btn db-table-floating-right" title="Cuộn bảng sang phải (▶)" aria-label="Cuộn sang phải">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        `);
        $wrapper.append($btnFloatRight);
      }

      const $btnLeft = $controller.find('.btn-table-scroll-left');
      const $btnRight = $controller.find('.btn-table-scroll-right');

      function updateNavBtns() {
        if (!containerEl.offsetParent && containerEl.clientWidth === 0) return; // Tab đang ẩn

        const scrollLeft = containerEl.scrollLeft;
        const maxScroll = containerEl.scrollWidth - containerEl.clientWidth;
        const isOverflow = maxScroll > 6;

        if (isOverflow) {
          $controller.show();
          $btnLeft.prop('disabled', scrollLeft <= 4);
          $btnRight.prop('disabled', scrollLeft >= maxScroll - 4);

          // Nút nổi bên trái
          if (scrollLeft > 8) {
            $btnFloatLeft.addClass('is-visible');
          } else {
            $btnFloatLeft.removeClass('is-visible');
          }

          // Nút nổi bên phải
          if (scrollLeft < maxScroll - 8) {
            $btnFloatRight.addClass('is-visible');
          } else {
            $btnFloatRight.removeClass('is-visible');
          }
        } else {
          $controller.hide();
          $btnFloatLeft.removeClass('is-visible');
          $btnFloatRight.removeClass('is-visible');
        }
      }

      // Gán sự kiện kiểm tra cuộn để kích hoạt từ bên ngoài
      $container.off('check-table-scroll').on('check-table-scroll', updateNavBtns);

      // Gán sự kiện click (chỉ 1 lần)
      if (!$container.data('scroll-events-bound')) {
        $container.data('scroll-events-bound', true);

        // Click nút Trái
        $btnLeft.add($btnFloatLeft).on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          const step = Math.min(320, containerEl.clientWidth * 0.75);
          containerEl.scrollBy({ left: -step, behavior: 'smooth' });
          setTimeout(updateNavBtns, 300);
        });

        // Click nút Phải
        $btnRight.add($btnFloatRight).on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          const step = Math.min(320, containerEl.clientWidth * 0.75);
          containerEl.scrollBy({ left: step, behavior: 'smooth' });
          setTimeout(updateNavBtns, 300);
        });

        $container.on('scroll', updateNavBtns);

        // Cho phép kéo chuột qua lại (Drag to scroll) trên máy tính
        let isDown = false;
        let startX;
        let scrollLeftPos;

        $container.addClass('is-draggable');
        $container.on('mousedown', function(e) {
          if ($(e.target).closest('button, a, input, select, textarea, .dropdown-menu, .story-dropdown-menu').length) return;
          isDown = true;
          $container.addClass('is-dragging');
          startX = e.pageX - containerEl.offsetLeft;
          scrollLeftPos = containerEl.scrollLeft;
        });

        $(document).on('mouseup', function() {
          if (isDown) {
            isDown = false;
            $container.removeClass('is-dragging');
          }
        });

        $container.on('mouseleave', function() {
          if (isDown) {
            isDown = false;
            $container.removeClass('is-dragging');
          }
        });

        $container.on('mousemove', function(e) {
          if (!isDown) return;
          e.preventDefault();
          const x = e.pageX - containerEl.offsetLeft;
          const walk = (x - startX) * 1.5;
          containerEl.scrollLeft = scrollLeftPos - walk;
        });
      }

      updateNavBtns();
    });
  }

  setTimeout(initTableScrollControls, 100);
  setTimeout(initTableScrollControls, 400);

  $(window).on('resize', function() {
    initTableScrollControls();
    $('.db-table-container, .table-responsive').trigger('check-table-scroll');
  });

  const originalSwitchAdminTab = switchAdminTab;
  switchAdminTab = function(tabId) {
    originalSwitchAdminTab(tabId);
    setTimeout(function() {
      initTableScrollControls();
      $('.db-table-container, .table-responsive').trigger('check-table-scroll');
    }, 80);
    setTimeout(function() {
      $('.db-table-container, .table-responsive').trigger('check-table-scroll');
    }, 280);
  };
});
</script>

<?php
get_template_part('templates/modal-edit-story-master');
get_template_part('templates/modal-admin-popups');
get_footer();
