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
                            <th>Dịch Giả / Tác Giả</th>
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
                        ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url(get_permalink($st->ID)); ?>" style="font-weight: 700; color: var(--primary-green);">
                                        <?php echo esc_html($st->post_title); ?>
                                    </a>
                                </td>
                                <td><?php echo esc_html($st_author); ?></td>
                                <td>
                                    <?php if ($st->post_status === 'publish') : ?>
                                        <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Đã duyệt</span>
                                    <?php elseif ($st->post_status === 'pending') : ?>
                                        <span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                    <?php else : ?>
                                        <span class="badge badge-nominate">Bản nháp</span>
                                    <?php endif; ?>
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
                            <th>Số Truyện Đã Đăng</th>
                            <th>Lượt Xem Tháng Này</th>
                            <th>Lương Tháng Này (8đ)</th>
                            <th>Lương Tháng Trước</th>
                            <th>Trạng Thái Quyết Toán</th>
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
                        ?>
                            <tr>
                                <td><strong><?php echo esc_html($tr->display_name); ?></strong></td>
                                <td><?php echo esc_html($tr->user_email); ?></td>
                                <td><?php echo $tr_count; ?> truyện</td>
                                <td><i class="fa-solid fa-eye"></i> <?php echo number_format($tr_views_month); ?></td>
                                <td style="font-size: 15px; font-weight: 800; color: #2E7D32;">
                                    <?php echo number_format($sal_cur); ?> VNĐ
                                </td>
                                <td style="color: var(--text-muted); font-weight: 600;">
                                    <?php echo number_format($sal_last); ?> VNĐ
                                </td>
                                <td>
                                    <span class="badge badge-full"><i class="fa-solid fa-check"></i> Sẵn sàng thanh toán</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 5: QUẢN LÝ THỂ LOẠI -->
        <div id="admTabGenres" class="dash-tab-pane">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--primary-green);">
                    Danh Sách Thể Loại Truyện (<?php echo count($all_genres); ?>)
                </h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                <?php foreach ($all_genres as $g) : ?>
                    <div style="background: var(--pastel-green); padding: 12px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-weight: 700; color: var(--text-main);"><?php echo esc_html($g->name); ?></span>
                        <span class="badge badge-green"><?php echo $g->count; ?> truyện</span>
                    </div>
                <?php endforeach; ?>
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
                                <span class="comment-time"><?php echo esc_html(human_time_diff(strtotime($cmt->comment_date), current_time('timestamp'))) . ' trước'; ?></span>
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
