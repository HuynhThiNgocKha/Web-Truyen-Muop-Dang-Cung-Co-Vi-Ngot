<?php
/**
 * Template Name: Quản Trị Hệ Thống (Admin Portal)
 * Dành riêng cho Quản Trị Viên (Admin)
 * Bao gồm: Thống kê, duyệt truyện, đề cử, Shopee/TikTok clicks & URLs, quản lý độc giả/dịch giả, lương dịch giả, thể loại, mode switcher
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || !current_user_can('administrator')) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

global $wpdb;

// 1. SYSTEM KPI STATS
// Total Stories
$total_stories = wp_count_posts('truyen')->publish + wp_count_posts('truyen')->pending;

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
$all_published_stories = get_posts(array('post_type' => 'truyen', 'posts_per_page' => -1, 'post_status' => array('publish', 'pending')));
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

// All Users
$all_users = get_users(array('number' => 50, 'orderby' => 'registered', 'order' => 'DESC'));

// All Translators for Salary Breakdown
$translators = get_users(array('role' => 'dich_gia'));
if (empty($translators)) {
    // If no distinct translators, include admins who published stories
    $translators = get_users(array('role__in' => array('dich_gia', 'administrator')));
}

// All Genres
$all_genres = get_terms(array('taxonomy' => 'the_loai', 'hide_empty' => false));

// Chapter Error Reports (Báo lỗi chương truyện & khiếu nại)
$table_reports = $wpdb->prefix . 'muop_chapter_reports';
$all_reports = $wpdb->get_results("SELECT * FROM $table_reports ORDER BY id DESC LIMIT 50");
$pending_reports_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_reports WHERE status = 'pending'");
?>

<div class="container">
    <div class="dashboard-wrapper">
        <nav class="breadcrumbs admin-breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span class="breadcrumb-current" style="color: var(--primary-green); font-weight: 600;">Trung Tâm Quản Trị Hệ Thống (Admin Portal)</span>
        </nav>

        <!-- TOP BAR: TITLE & VIEW MODE SWITCHER -->
        <div class="admin-topbar">
            <div class="admin-topbar-info">
                <h1 class="admin-topbar-title">
                    <i class="fa-solid fa-sliders"></i> Bảng Điều Khiển Quản Trị Hệ Thống
                </h1>
                <p class="admin-topbar-desc">
                    Quản lý toàn bộ nội dung, người dùng, liên kết tiếp thị Shopee/TikTok và lương dịch giả
                </p>
            </div>

            <!-- Chuyển chế độ xem giao diện -->
            <div class="admin-viewmode-box">
                <label for="adminModeSelectMain" class="admin-viewmode-label">
                    <i class="fa-solid fa-masks-theater"></i> Chế độ xem:
                </label>
                <select id="adminModeSelectMain" class="form-input admin-viewmode-select" onchange="jQuery('#adminModeSelect').val(this.value).trigger('change');">
                    <option value="admin" selected>Quản Trị Viên (Admin)</option>
                    <option value="dich_gia">Dịch Giả (Translator)</option>
                    <option value="doc_gia">Độc Giả (Reader)</option>
                </select>
            </div>
        </div>

        <!-- 6 KPI COUNTERS -->
        <div class="kpi-grid admin-kpi-grid">
            <div class="kpi-card" style="padding: 14px;">
                <div class="kpi-icon" style="width: 40px; height: 40px; font-size: 16px;"><i class="fa-solid fa-eye"></i></div>
                <div>
                    <div class="kpi-value" style="font-size: 18px;"><?php echo number_format($total_month_views); ?></div>
                    <div class="kpi-label">Lượt Xem Tháng</div>
                </div>
            </div>

            <div class="kpi-card" style="padding: 14px;">
                <div class="kpi-icon" style="width: 40px; height: 40px; font-size: 16px;"><i class="fa-solid fa-book"></i></div>
                <div>
                    <div class="kpi-value" style="font-size: 18px;"><?php echo number_format($total_stories); ?></div>
                    <div class="kpi-label">Tổng Bộ Truyện</div>
                </div>
            </div>

            <div class="kpi-card" style="padding: 14px;">
                <div class="kpi-icon" style="width: 40px; height: 40px; font-size: 16px;"><i class="fa-solid fa-users"></i></div>
                <div>
                    <div class="kpi-value" style="font-size: 18px;"><?php echo number_format($total_readers); ?></div>
                    <div class="kpi-label">Tổng Độc Giả</div>
                </div>
            </div>

            <div class="kpi-card" style="padding: 14px;">
                <div class="kpi-icon" style="width: 40px; height: 40px; font-size: 16px;"><i class="fa-solid fa-feather-pointed"></i></div>
                <div>
                    <div class="kpi-value" style="font-size: 18px;"><?php echo number_format($total_translators); ?></div>
                    <div class="kpi-label">Tổng Dịch Giả</div>
                </div>
            </div>

            <div class="kpi-card" style="padding: 14px; background: #FFF3E0; border-color: #FFE082;">
                <div class="kpi-icon" style="width: 40px; height: 40px; font-size: 16px; background: #EE4D2D;"><i class="fa-solid fa-bag-shopping"></i></div>
                <div>
                    <div class="kpi-value" style="font-size: 18px; color: #D84315;"><?php echo number_format($shopee_clicks); ?></div>
                    <div class="kpi-label" style="color: #E65100;">Click Shopee</div>
                </div>
            </div>

            <div class="kpi-card" style="padding: 14px; background: #EDE7F6; border-color: #D1C4E9;">
                <div class="kpi-icon" style="width: 40px; height: 40px; font-size: 16px; background: #212121;"><i class="fa-brands fa-tiktok"></i></div>
                <div>
                    <div class="kpi-value" style="font-size: 18px; color: #4A148C;"><?php echo number_format($tiktok_clicks); ?></div>
                    <div class="kpi-label" style="color: #4A148C;">Click TikTok</div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD TABS -->
        <div class="dash-tabs-nav">
            <button type="button" class="dash-tab-btn active" data-tab="admTabStories">
                <i class="fa-solid fa-book"></i> Quản Lý Truyện & Kiểm Duyệt (<?php echo count($pending_stories); ?> chờ)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabAffiliate">
                <i class="fa-solid fa-link"></i> Link Shopee & TikTok (<?php echo ($shopee_clicks + $tiktok_clicks); ?> clicks)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabUsers">
                <i class="fa-solid fa-user-group"></i> Quản Lý Người Dùng & Phân Quyền
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabSalaries">
                <i class="fa-solid fa-hand-holding-dollar"></i> Tiền Lương Dịch Giả (8đ/view)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabGenres">
                <i class="fa-solid fa-tags"></i> Thể Loại
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabComments">
                <i class="fa-solid fa-comments"></i> Bình Luận & Phản Hồi
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabReports">
                <i class="fa-solid fa-triangle-exclamation"></i> Báo Lỗi Chương (<?php echo $pending_reports_count; ?>)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="admTabBroadcast">
                <i class="fa-solid fa-bullhorn"></i> Thông Báo Hệ Thống
            </button>
        </div>

        <!-- TAB 1: QUẢN LÝ TRUYỆN & KIỂM DUYỆT -->
        <div id="admTabStories" class="dash-tab-pane active">
            <div class="admin-tab-header">
                <h3 class="admin-tab-title">
                    Danh Sách Truyện & Kiểm Duyệt
                </h3>
                <div class="admin-tab-actions">
                    <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="btn btn-primary admin-btn-new-story">
                        <i class="fa-solid fa-plus-circle"></i> Đăng Truyện Mới
                    </a>
                </div>
            </div>

            <!-- Pending Review Notice -->
            <?php if (!empty($pending_stories)) : ?>
                <div style="background: #FFF8E1; border: 1px solid #FFE082; padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                    <span style="color: #F57F17; font-weight: 700; font-size: 13px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Có <?php echo count($pending_stories); ?> bài đăng mới từ dịch giả đang chờ bạn kiểm duyệt!
                    </span>
                </div>
            <?php endif; ?>

            <!-- Desktop Table View -->
            <div class="table-responsive desktop-only">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tên Truyện</th>
                            <th>Dịch Giả</th>
                            <th>Trạng Thái</th>
                            <th>Số Chương</th>
                            <th>Lượt Xem</th>
                            <th>Đề Cử</th>
                            <th>Hành Động Kiểm Duyệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_published_stories as $st) : 
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
                                $st_team = 'Mướp Đắng Cũng Có Vị Ngọt';
                            }
                        ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url(get_permalink($st->ID)); ?>" style="font-weight: 700; color: var(--primary-green);">
                                        <?php echo esc_html($st->post_title); ?>
                                    </a>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--text-main); display: block;"><?php echo esc_html($st_team); ?></span>
                                    <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 2px;"><i class="fa-solid fa-user-pen"></i> Đăng bởi: <?php echo esc_html($st_author); ?></span>
                                </td>
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-start;">
                                        <?php if ($st->post_status === 'publish') : ?>
                                            <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Đã duyệt</span>
                                        <?php elseif ($st->post_status === 'pending') : ?>
                                            <span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                        <?php else : ?>
                                            <span class="badge badge-nominate">Bản nháp</span>
                                        <?php endif; ?>

                                        <?php $st_progress = get_post_meta($st->ID, '_truyen_status', true); ?>
                                        <span class="badge <?php echo ($st_progress === 'hoan_thanh') ? 'badge-full' : 'badge-green'; ?> badge-editable btn-table-change-status" data-story-id="<?php echo $st->ID; ?>" data-current-status="<?php echo esc_attr($st_progress ?: 'dang_ra'); ?>" title="Nhấn để đổi trạng thái truyện (Đang ra / Hoàn thành)">
                                            <i class="fa-solid <?php echo ($st_progress === 'hoan_thanh') ? 'fa-check' : 'fa-arrows-rotate'; ?>"></i>
                                            <span class="table-status-text"><?php echo ($st_progress === 'hoan_thanh') ? 'Full' : 'Đang ra'; ?></span>
                                            <i class="fa-solid fa-pen" style="font-size: 8px; opacity: 0.7; margin-left: 2px;"></i>
                                        </span>
                                    </div>
                                </td>
                                <td><strong><?php echo $st_chaps; ?></strong> chương</td>
                                <td><i class="fa-solid fa-eye"></i> <?php echo number_format($st_views); ?></td>
                                <td>
                                    <select class="form-input btn-admin-action-nominate" style="padding: 2px 6px; font-size: 11.5px; width: auto;" onchange="jQuery(this).trigger('change_nominate', [<?php echo $st->ID; ?>, this.value]);">
                                        <option value="none" <?php selected($st_nominate, 'none'); ?>>Không đề cử</option>
                                        <option value="day" <?php selected($st_nominate, 'day'); ?>>Đề cử Ngày</option>
                                        <option value="week" <?php selected($st_nominate, 'week'); ?>>Đề cử Tuần</option>
                                        <option value="month" <?php selected($st_nominate, 'month'); ?>>Đề cử Tháng</option>
                                    </select>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 4px;">
                                        <?php if ($st->post_status !== 'publish') : ?>
                                            <button type="button" class="btn btn-primary btn-admin-action" data-action="approve_story" data-story-id="<?php echo $st->ID; ?>" style="padding: 3px 8px; font-size: 11px;" title="Duyệt bài">
                                                <i class="fa-solid fa-check"></i> Duyệt
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?php echo esc_url(add_query_arg('story_id', $st->ID, home_url('/them-chuong/'))); ?>" class="btn btn-secondary" style="padding: 3px 8px; font-size: 11px;" title="Thêm chương">
                                            + Chương
                                        </a>
                                        <button type="button" class="btn btn-danger btn-admin-action" data-action="delete_story" data-story-id="<?php echo $st->ID; ?>" style="padding: 3px 8px; font-size: 11px;" title="Xóa truyện">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="admin-mobile-story-list mobile-only">
                <?php foreach ($all_published_stories as $st) : 
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
                        $st_team = 'Mướp Đắng Cũng Có Vị Ngọt';
                    }
                ?>
                    <div class="admin-story-card">
                        <div class="admin-story-card-top">
                            <a href="<?php echo esc_url(get_permalink($st->ID)); ?>" class="admin-story-card-title">
                                <?php echo esc_html($st->post_title); ?>
                            </a>
                            <div class="admin-story-card-status">
                                <?php if ($st->post_status === 'publish') : ?>
                                    <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Đã duyệt</span>
                                <?php elseif ($st->post_status === 'pending') : ?>
                                    <span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                <?php else : ?>
                                    <span class="badge badge-nominate">Bản nháp</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="admin-story-card-meta">
                            <span><i class="fa-solid fa-users"></i> <?php echo esc_html($st_team); ?></span>
                            <span>•</span>
                            <span><i class="fa-solid fa-user-pen"></i> <?php echo esc_html($st_author); ?></span>
                            <span>•</span>
                            <span><i class="fa-solid fa-book-open"></i> <strong><?php echo $st_chaps; ?></strong> c</span>
                            <span>•</span>
                            <span><i class="fa-solid fa-eye"></i> <?php echo number_format($st_views); ?></span>
                        </div>

                        <div class="admin-story-card-bottom">
                            <div class="admin-card-nominate">
                                <span class="nominate-tag">Đề cử:</span>
                                <select class="form-input btn-admin-action-nominate" onchange="jQuery(this).trigger('change_nominate', [<?php echo $st->ID; ?>, this.value]);">
                                    <option value="none" <?php selected($st_nominate, 'none'); ?>>Không</option>
                                    <option value="day" <?php selected($st_nominate, 'day'); ?>>Ngày</option>
                                    <option value="week" <?php selected($st_nominate, 'week'); ?>>Tuần</option>
                                    <option value="month" <?php selected($st_nominate, 'month'); ?>>Tháng</option>
                                </select>
                            </div>
                            <div class="admin-card-actions">
                                <?php if ($st->post_status !== 'publish') : ?>
                                    <button type="button" class="btn btn-primary btn-sm btn-admin-action" data-action="approve_story" data-story-id="<?php echo $st->ID; ?>" title="Duyệt bài">
                                        <i class="fa-solid fa-check"></i> Duyệt
                                    </button>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(add_query_arg('story_id', $st->ID, home_url('/them-chuong/'))); ?>" class="btn btn-secondary btn-sm" title="Thêm chương">
                                    + Chương
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-admin-action" data-action="delete_story" data-story-id="<?php echo $st->ID; ?>" title="Xóa truyện">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TAB 2: LINK SHOPEE & TIKTOK + THỐNG KÊ CLICKS -->
        <div id="admTabAffiliate" class="dash-tab-pane">
            <div id="affiliateAlert" class="alert-box"></div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
                <!-- Form Cấu hình Link -->
                <div style="background: var(--pastel-green); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                        <i class="fa-solid fa-gear"></i> Cài Đặt Liên Kết Tiếp Thị (Affiliate Links)
                    </h3>

                    <form id="formUpdateAffiliateLinks">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-bag-shopping" style="color: #EE4D2D;"></i> Link Shopee (Bắt buộc click từ chương 2)
                            </label>
                            <input type="url" name="shopee_url" class="form-input" value="<?php echo esc_url($current_shopee_url); ?>" required />
                            <small style="color: var(--text-muted); font-size: 11.5px; display: block; margin-top: 4px;">
                                Độc giả phải click link này để mở khóa đọc tiếp ở Chương 2, Chương 5, Chương 8...
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-brands fa-tiktok" style="color: #212121;"></i> Link TikTok Shop
                            </label>
                            <input type="url" name="tiktok_url" class="form-input" value="<?php echo esc_url($current_tiktok_url); ?>" required />
                            <small style="color: var(--text-muted); font-size: 11.5px; display: block; margin-top: 4px;">
                                Kích hoạt chuyển hướng nền khi tương tác.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 8px;">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu Cấu Hình Link
                        </button>
                    </form>
                </div>

                <!-- Báo cáo tổng click -->
                <div style="background: var(--bg-card); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                        <i class="fa-solid fa-chart-pie"></i> Tổng Số Lượt Click Vào Link
                    </h3>

                    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                        <div style="flex: 1; background: #FFF3E0; border: 1px solid #FFE082; padding: 18px; border-radius: var(--radius-md); text-align: center;">
                            <i class="fa-solid fa-bag-shopping" style="font-size: 28px; color: #EE4D2D; margin-bottom: 8px;"></i>
                            <div style="font-size: 26px; font-weight: 800; color: #D84315;"><?php echo number_format($shopee_clicks); ?></div>
                            <span style="font-size: 12.5px; color: #E65100; font-weight: 600;">Lượt Click Shopee</span>
                        </div>

                        <div style="flex: 1; background: #EDE7F6; border: 1px solid #D1C4E9; padding: 18px; border-radius: var(--radius-md); text-align: center;">
                            <i class="fa-brands fa-tiktok" style="font-size: 28px; color: #212121; margin-bottom: 8px;"></i>
                            <div style="font-size: 26px; font-weight: 800; color: #4A148C;"><?php echo number_format($tiktok_clicks); ?></div>
                            <span style="font-size: 12.5px; color: #4A148C; font-weight: 600;">Lượt Click TikTok</span>
                        </div>
                    </div>

                    <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.6;">
                        <i class="fa-solid fa-circle-info"></i> Hệ thống tự động ghi nhận IP, trình duyệt và chương truyện khi độc giả click mở khóa Shopee hoặc tương tác trên trang.
                    </p>
                </div>
            </div>

            <!-- Bảng Nhật ký Clicks Gần Nhất -->
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 12px;">
                <i class="fa-solid fa-list-check"></i> Nhật Ký Lượt Click Gần Đây Nhất
            </h3>
            <div class="table-responsive">
                <table class="data-table">
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
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 12px;">
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
        </div>

        <!-- TAB 3: QUẢN LÝ NGƯỜI DÙNG & PHÂN QUYỀN -->
        <div id="admTabUsers" class="dash-tab-pane">
            <!-- Thêm Người Dùng Form Modal / Inline -->
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 14px;">
                    <i class="fa-solid fa-user-plus"></i> Thêm Người Dùng Mới Vào Hệ Thống
                </h3>
                <form id="formAdminCreateUser" style="display: grid; grid-template-columns: repeat(4, 1fr) 140px; gap: 12px; align-items: flex-end;">
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label">Tên Hiển Thị</label>
                        <input type="text" name="name" class="form-input" placeholder="Họ tên" required />
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="email@example.com" required />
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label">Mật Khẩu</label>
                        <input type="text" name="password" class="form-input" placeholder="Mật khẩu" required />
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label">Vai Trò</label>
                        <select name="role" class="form-input">
                            <option value="doc_gia">Độc Giả</option>
                            <option value="dich_gia">Dịch Giả</option>
                            <option value="administrator">Quản Trị Viên</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 40px;">
                        <i class="fa-solid fa-plus"></i> Thêm
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tên Người Dùng</th>
                            <th>Email</th>
                            <th>Vai Trò (Role)</th>
                            <th>Ngày Đăng Ký</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_users as $u) : 
                            $roles = (array) $u->roles;
                            $r = !empty($roles) ? $roles[0] : 'doc_gia';
                            $is_locked = (bool) get_user_meta($u->ID, '_muop_account_locked', true);
                        ?>
                            <tr>
                                <td><strong><?php echo esc_html($u->display_name); ?></strong></td>
                                <td><?php echo esc_html($u->user_email); ?></td>
                                <td>
                                    <select class="form-input" style="padding: 2px 8px; font-size: 12px; width: auto;" onchange="jQuery(this).trigger('change_role', [<?php echo $u->ID; ?>, this.value]);">
                                        <option value="doc_gia" <?php selected($r, 'doc_gia'); ?>>Độc Giả</option>
                                        <option value="dich_gia" <?php selected($r, 'dich_gia'); ?>>Dịch Giả</option>
                                        <option value="administrator" <?php selected($r, 'administrator'); ?>>Quản Trị Viên</option>
                                    </select>
                                </td>
                                <td style="font-size: 12px; color: var(--text-muted);"><?php echo date('d/m/Y', strtotime($u->user_registered)); ?></td>
                                <td>
                                    <?php if ($is_locked) : ?>
                                        <span class="badge badge-hot"><i class="fa-solid fa-lock"></i> Đã khóa</span>
                                    <?php else : ?>
                                        <span class="badge badge-full"><i class="fa-solid fa-unlock"></i> Hoạt động</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u->ID != get_current_user_id()) : ?>
                                        <button type="button" class="btn <?php echo $is_locked ? 'btn-primary' : 'btn-danger'; ?> btn-admin-action" data-action="toggle_user_status" data-user-id="<?php echo $u->ID; ?>" style="padding: 3px 10px; font-size: 11.5px;">
                                            <?php echo $is_locked ? 'Mở Khóa' : 'Khóa'; ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: TIỀN LƯƠNG DỊCH GIẢ (8Đ / VIEW) -->
        <div id="admTabSalaries" class="dash-tab-pane">
            <?php $rate = (int) get_option('muop_salary_rate', 8); ?>
            <div style="background: linear-gradient(135deg, #E8F5E9, #DCEDC8); padding: 18px 24px; border-radius: var(--radius-md); margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 17px; font-weight: 800; color: var(--primary-green); margin: 0;">
                        <i class="fa-solid fa-calculator"></i> Bảng Kê Quyết Toán Nhuận Bút Dịch Giả
                    </h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                        Định mức tính thưởng: <strong><?php echo $rate; ?>đ</strong> / 1 lượt xem (view) • Chu kỳ quyết toán: Hàng tháng
                    </p>
                </div>
                <div style="font-size: 14px; font-weight: 700; color: var(--primary-green); background: #fff; padding: 8px 16px; border-radius: var(--radius-full); box-shadow: var(--shadow-sm);">
                    Tháng hiện tại: <?php echo date('m/Y'); ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Dịch Giả</th>
                            <th>Email</th>
                            <th>Số Truyện</th>
                            <th>Lượt Xem Tháng Này</th>
                            <th>Nhuận Bút Tháng Này</th>
                            <th>Thông Tin Ngân Hàng</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($translators as $tr) : 
                            $tr_stories = get_posts(array('post_type' => 'truyen', 'posts_per_page' => -1, 'author' => $tr->ID));
                            $tr_count = count($tr_stories);
                            
                            $tr_views_month = 0;
                            $tr_views_last  = 0;
                            foreach ($tr_stories as $ts) {
                                $vm = (int) get_post_meta($ts->ID, '_truyen_views_' . $current_month_str, true);
                                $v_all = (int) get_post_meta($ts->ID, '_truyen_views', true);
                                $tr_views_month += ($vm ?: $v_all);
                                $tr_views_last  += (int) get_post_meta($ts->ID, '_truyen_views_' . date('Y-m', strtotime('-1 month')), true);
                            }

                            $sal_cur  = $tr_views_month * $rate;
                            $sal_last = $tr_views_last * $rate;

                            $tr_bank_name   = get_user_meta($tr->ID, '_muop_bank_name', true);
                            $tr_bank_holder = get_user_meta($tr->ID, '_muop_bank_account_holder', true);
                            $tr_bank_num    = get_user_meta($tr->ID, '_muop_bank_account_number', true);
                            $tr_bank_str    = $tr_bank_num ? ($tr_bank_name . ' - ' . $tr_bank_num . ' (' . $tr_bank_holder . ')') : '';
                        ?>
                            <tr>
                                <td><strong><?php echo esc_html($tr->display_name); ?></strong></td>
                                <td><?php echo esc_html($tr->user_email); ?></td>
                                <td><?php echo $tr_count; ?> truyện</td>
                                <td><i class="fa-solid fa-eye"></i> <?php echo number_format($tr_views_month); ?></td>
                                <td style="font-size: 15px; font-weight: 800; color: #2E7D32;">
                                    <?php echo number_format($sal_cur); ?> VNĐ
                                </td>
                                <td style="font-size: 12px;">
                                    <?php if (!empty($tr_bank_num)) : ?>
                                        <strong><?php echo esc_html($tr_bank_name); ?></strong><br>
                                        <span style="font-family: monospace; font-size: 12.5px;"><?php echo esc_html($tr_bank_num); ?></span><br>
                                        <span style="color: var(--text-muted);"><?php echo esc_html($tr_bank_holder); ?></span>
                                    <?php else : ?>
                                        <span style="color: #ea580c; font-size: 11.5px;"><i class="fa-solid fa-triangle-exclamation"></i> Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-full"><i class="fa-solid fa-check"></i> Sẵn sàng thanh toán</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-open-pay-modal" 
                                            data-translator-id="<?php echo $tr->ID; ?>" 
                                            data-translator-name="<?php echo esc_attr($tr->display_name); ?>" 
                                            data-amount="<?php echo $sal_cur; ?>" 
                                            data-views="<?php echo $tr_views_month; ?>" 
                                            data-period="<?php echo date('m/Y'); ?>" 
                                            data-bank="<?php echo esc_attr($tr_bank_str); ?>" 
                                            style="padding: 4px 10px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa-solid fa-receipt"></i> Thanh Toán & Gửi Bill
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Lịch sử các lần chuyển khoản nhuận bút kèm bill đã thanh toán -->
            <div style="margin-top: 32px;">
                <h4 style="font-size: 15px; font-weight: 700; color: var(--primary-green); margin-bottom: 12px;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Lịch Sử Quyết Toán & Bill Đã Thanh Toán Gần Đây
                </h4>
                <?php
                $table_payments = $wpdb->prefix . 'muop_translator_payments';
                $recent_payments = $wpdb->get_results("SELECT * FROM $table_payments ORDER BY id DESC LIMIT 20");
                ?>
                <div class="table-responsive">
                    <table class="data-table" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Kỳ Thanh Toán</th>
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
                                                <button type="button" class="btn btn-secondary btn-sm btn-view-bill" data-bill-url="<?php echo esc_url($rp->bill_image); ?>" style="padding: 3px 9px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
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
        </div>

        <!-- TAB 5: QUẢN LÝ THỂ LOẠI -->
        <div id="admTabGenres" class="dash-tab-pane">
            <div class="admin-tab-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                <div>
                    <h3 class="admin-tab-title" style="margin-bottom: 4px;">
                        <i class="fa-solid fa-tags"></i> Quản Lý Thể Loại Truyện (<span id="admGenreTotalCount"><?php echo count($all_genres); ?></span>)
                    </h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                        Thêm mới, tìm kiếm, chỉnh sửa tên và xóa thể loại trong hệ thống
                    </p>
                </div>
                <div class="admin-tab-actions" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <button type="button" id="admBtnToggleAddGenre" class="btn btn-primary" style="font-size: 13px; padding: 7px 16px; border-radius: var(--radius-sm); display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-plus"></i> Thêm Thể Loại Mới
                    </button>
                </div>
            </div>

            <!-- Hộp Thêm Thể Loại Mới Cho Admin (toggle mở/đóng) -->
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

            <!-- Thanh Tìm Kiếm Thể Loại -->
            <div style="margin-bottom: 18px; position: relative; max-width: 400px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px;"></i>
                <input type="text" id="admSearchGenre" class="form-input" placeholder="Tìm kiếm thể loại..." style="padding-left: 36px; height: 38px; font-size: 13.5px; border-radius: var(--radius-sm);" />
            </div>

            <!-- Danh Sách Thẻ Thể Loại -->
            <div id="admGenresGrid" class="adm-genres-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;">
                <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                    <?php foreach ($all_genres as $g) : ?>
                        <div class="adm-genre-card" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr(mb_strtolower($g->name, 'UTF-8')); ?>" style="background: var(--bg-card); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: var(--shadow-sm); transition: transform 0.15s ease, box-shadow 0.15s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <span class="adm-genre-title" style="font-weight: 700; color: var(--text-main); font-size: 14px; word-break: break-word;"><?php echo esc_html($g->name); ?></span>
                                <span class="badge badge-green" style="font-size: 11px; white-space: nowrap;"><?php echo (int)$g->count; ?> truyện</span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px; padding-top: 8px; border-top: 1px dashed var(--border-light);">
                                <button type="button" class="btn btn-outline btn-xs adm-btn-edit-genre" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr($g->name); ?>" title="Sửa tên thể loại" style="padding: 4px 10px; font-size: 12px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-pen-to-square"></i> Sửa
                                </button>
                                <button type="button" class="btn btn-outline btn-xs adm-btn-delete-genre" data-id="<?php echo esc_attr($g->term_id); ?>" data-name="<?php echo esc_attr($g->name); ?>" title="Xóa thể loại này" style="padding: 4px 10px; font-size: 12px; border-radius: 4px; color: #ef4444; border-color: #fca5a5; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-trash-can"></i> Xóa
                                </button>
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
                            <button type="button" id="admBtnCancelEditModal" class="btn btn-secondary" style="padding: 7px 14px; font-size: 13px;">
                                Hủy bỏ
                            </button>
                            <button type="button" id="admBtnSaveEditGenre" class="btn btn-primary" style="padding: 7px 18px; font-size: 13px;">
                                <i class="fa-solid fa-check"></i> Lưu Thay Đổi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 6: BÌNH LUẬN & PHẢN HỒI -->
        <div id="admTabComments" class="dash-tab-pane">
            <h3 style="font-size: 17px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                Tất Cả Bình Luận & Đánh Giá Gần Nhất
            </h3>
            <ul class="comment-list-ul">
                <?php
                $recent_all_comments = get_comments(array('number' => 20, 'status' => 'approve'));
                if (!empty($recent_all_comments)) :
                    foreach ($recent_all_comments as $cmt) :
                        $cmt_p = get_post($cmt->comment_post_ID);
                ?>
                    <li class="comment-item">
                        <div class="comment-avatar">
                            <?php echo esc_html(strtoupper(substr($cmt->comment_author, 0, 1))); ?>
                        </div>
                        <div class="comment-body">
                            <div>
                                <span class="comment-author-name"><?php echo esc_html($cmt->comment_author); ?></span>
                                <span class="comment-time"><?php echo esc_html(muop_time_ago(strtotime($cmt->comment_date))); ?></span>
                                <span style="font-size: 12px; color: var(--text-muted); margin-left: 8px;">
                                    trong bài: <strong><?php echo esc_html($cmt_p ? $cmt_p->post_title : ''); ?></strong>
                                </span>
                            </div>
                            <div class="comment-text"><?php echo esc_html($cmt->comment_content); ?></div>
                        </div>
                    </li>
                <?php 
                    endforeach;
                else :
                    echo '<p style="color: var(--text-muted); padding: 20px;">Chưa có bình luận nào.</p>';
                endif;
                ?>
            </ul>
        </div>

        <!-- TAB 7: BÁO LỖI CHƯƠNG & KHIẾU NẠI -->
        <div id="admTabReports" class="dash-tab-pane">
            <div class="admin-tab-header" style="margin-bottom: 20px;">
                <h3 class="admin-tab-title">
                    <i class="fa-solid fa-triangle-exclamation"></i> Báo Lỗi Chương Truyện & Khiếu Nại (<?php echo count($all_reports); ?>)
                </h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                    Danh sách lỗi do độc giả báo về. Bạn có thể phản hồi và cập nhật trạng thái xử lý.
                </p>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người Báo Lỗi</th>
                            <th>Truyện & Chương</th>
                            <th>Loại Lỗi</th>
                            <th>Nội Dung Mô Tả</th>
                            <th>Thời Gian</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
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
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm btn-open-reply-report" 
                                                data-report-id="<?php echo $rep->id; ?>" 
                                                data-story="<?php echo esc_attr($rep_story ? $rep_story->post_title : ''); ?>" 
                                                data-chapter-num="<?php echo $rep->chapter_num; ?>" 
                                                data-reply="<?php echo esc_attr($rep->admin_reply); ?>" 
                                                data-status="<?php echo esc_attr($rep->status); ?>" 
                                                style="padding: 4px 10px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;">
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
        </div>

        <!-- TAB 8: GỬI THÔNG BÁO HỆ THỐNG -->
        <div id="admTabBroadcast" class="dash-tab-pane">
            <div class="admin-tab-header" style="margin-bottom: 20px;">
                <h3 class="admin-tab-title">
                    <i class="fa-solid fa-bullhorn"></i> Phát Thông Báo Hệ Thống (Broadcast)
                </h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                    Gửi thông báo tới toàn bộ người dùng, hoặc riêng nhóm dịch giả / độc giả trên hệ thống.
                </p>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; max-width: 650px; box-shadow: var(--shadow-sm);">
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

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="font-weight: 600;">Liên Kết Kèm Theo (Tùy chọn)</label>
                        <input type="url" name="link" class="form-input" placeholder="https://..." style="width: 100%;" />
                    </div>

                    <button type="submit" class="btn btn-primary" id="btnSubmitBroadcast" style="padding: 10px 22px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-paper-plane"></i> Gửi Thông Báo Ngay
                    </button>
                    <div id="broadcastStatusMsg" style="margin-top: 12px; font-size: 13px; display: none;"></div>
                </form>
            </div>
        </div>

        <!-- MODAL ADMIN THANH TOÁN LƯƠNG & GỬI BILL CHUYỂN KHOẢN -->
        <div id="admModalPaySalary" class="modal-backdrop" style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; padding: 16px;">
            <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 480px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; padding: 0;">
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
                    <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
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
                        <span style="font-size: 11.5px; color: var(--text-muted); display: block; margin-top: 4px;">Tải lên ảnh chụp màn hình bill chuyển khoản thành công làm bằng chứng gửi dịch giả</span>
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

        <!-- MODAL ADMIN PHẢN HỒI BÁO LỖI CHƯƠNG -->
        <div id="admModalReplyReport" class="modal-backdrop" style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; padding: 16px;">
            <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 460px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; padding: 0;">
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
                    <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
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
                        <textarea name="reply_text" id="replyTextarea" class="form-input" rows="4" placeholder="Nhập phản hồi gửi độc giả và dịch giả (VD: Cảm ơn bạn, lỗi đã được sửa lại...)" required style="width: 100%;"></textarea>
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
    </div>
</div>

<script>
// Inline JS binding for custom admin actions in this tab
jQuery(document).ready(function($) {
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
        alert(res.data ? res.data.message : 'Đã cập nhật đề cử!');
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
        alert(res.data ? res.data.message : 'Đã đổi quyền người dùng!');
      }
    });
  });

  // Create user form
  $('#formAdminCreateUser').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: muopConfig.ajaxUrl,
      type: 'POST',
      data: $(this).serialize() + '&action=muop_admin_action&sub_action=create_user&nonce=' + muopConfig.nonce,
      success: function(res) {
        if (res.success) {
          alert(res.data.message);
          window.location.reload();
        } else {
          alert(res.data.message || 'Lỗi thêm người dùng!');
        }
      }
    });
  });
});
</script>

<?php
get_footer();
