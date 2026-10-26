<?php
/**
 * Template Name: Kênh Dịch Giả / Thông Tin Dịch Giả
 * Thống kê: view ngày/tuần/tháng/năm, tính tiền 8đ/view, quản lý truyện, bình luận & trả lời
 */
if (!defined('ABSPATH')) exit;

$current_user = wp_get_current_user();
$is_admin = current_user_can('administrator');
$is_translator = muop_user_is_translator($current_user->ID);

if (!is_user_logged_in() || (!$is_translator && !$is_admin)) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

// Nếu là Admin nhưng không có vai trò Dịch Giả thì chuyển hướng về Bảng Quản Trị Hệ Thống của Admin
if ($is_admin && !$is_translator) {
    wp_redirect(home_url('/quan-ly-admin/'));
    exit;
}

get_header();
?>
<style>
.translator-stories-table th.col-status,
.translator-stories-table th.col-review,
.translator-stories-table td.col-status,
.translator-stories-table td.col-review {
  white-space: nowrap !important;
  text-align: center !important;
}
.translator-stories-table .badge {
  white-space: nowrap !important;
  font-size: 11px !important;
  text-transform: none !important;
  letter-spacing: 0 !important;
  padding: 2.5px 7px !important;
  line-height: 1.25 !important;
  border-radius: 4px !important;
  display: inline-flex !important;
  align-items: center !important;
  gap: 3px !important;
}
</style>
<?php
$user_id      = $current_user->ID;
$registered   = date('d/m/Y', strtotime($current_user->user_registered));
$avatar_char  = strtoupper(substr($current_user->display_name, 0, 1));
$custom_avatar = get_user_meta($user_id, 'muop_user_avatar', true);
$custom_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($custom_avatar) : $custom_avatar;
$user_bio      = get_user_meta($user_id, 'description', true);
$bank_name     = get_user_meta($user_id, '_muop_bank_name', true) ?: '';
$bank_holder   = get_user_meta($user_id, '_muop_bank_account_holder', true) ?: '';
$bank_number   = get_user_meta($user_id, '_muop_bank_account_number', true) ?: '';

global $wpdb;
$table_payments = $wpdb->prefix . 'muop_translator_payments';
$my_payments = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $table_payments WHERE translator_id = %d ORDER BY id DESC",
    $user_id
));

// Stories of this translator
$my_stories = get_posts(array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => array('publish', 'pending', 'draft', 'future'),
    'author'         => $user_id
));

$story_count = count($my_stories);
$story_ids   = wp_list_pluck($my_stories, 'ID');

$count_all     = $story_count;
$count_publish = 0;
$count_pending = 0;
$count_draft   = 0;
$count_future  = 0;
$count_hidden  = 0;
foreach ($my_stories as $st) {
    $st_is_hidden = (bool) get_post_meta($st->ID, '_truyen_is_hidden', true);
    if ($st_is_hidden && $st->post_status === 'draft') $count_hidden++;
    elseif ($st->post_status === 'publish') $count_publish++;
    elseif ($st->post_status === 'pending') $count_pending++;
    elseif ($st->post_status === 'draft') $count_draft++;
    elseif ($st->post_status === 'future') $count_future++;
}

$all_genres = get_terms(array('taxonomy' => 'the_loai', 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC'));
if (is_wp_error($all_genres)) $all_genres = array();

// Calculate Views
$total_views = 0;
$current_month_str = date('Y-m');
$last_month_str    = date('Y-m', strtotime('-1 month'));

$views_this_month = 0;
$views_last_month = 0;

foreach ($my_stories as $st) {
    $v = (int) get_post_meta($st->ID, '_truyen_views', true);
    $total_views += $v;

    $vm_this = (int) get_post_meta($st->ID, '_truyen_views_' . $current_month_str, true);
    $views_this_month += $vm_this;

    $vm_last = (int) get_post_meta($st->ID, '_truyen_views_' . $last_month_str, true);
    $views_last_month += $vm_last;
}

// Fallback logic if views_this_month wasn't tracked separately yet
if ($views_this_month === 0 && $total_views > 0) {
    $views_this_month = $total_views;
}

// 8 VND per view calculation
$rate = (int) get_option('muop_salary_rate', 8);
$salary_this_month = $views_this_month * $rate;
$salary_last_month = $views_last_month * $rate;

// Estimated Today & Week views
$views_today = round($views_this_month / max(1, (int)date('j')));
$views_week  = min($views_this_month, $views_today * 7);
$views_year  = $total_views;

// Reader comments on this translator's stories
$comments_on_stories = array();
if (!empty($story_ids)) {
    $comments_on_stories = get_comments(array(
        'post__in' => $story_ids,
        'status'   => 'approve',
        'number'   => 30
    ));
}
?>

<div class="container">
    <div class="dashboard-wrapper">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Kênh Dịch Giả</span>
        </nav>

        <!-- PROFILE HEADER CARD (ĐỒNG NHẤT VỚI TRANG HỒ SƠ ẢNH 2) -->
        <div class="profile-header-card">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-display">
                    <?php if (!empty($custom_avatar)) : ?>
                        <img src="<?php echo esc_url($custom_avatar); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="profile-avatar-img" />
                    <?php else : ?>
                        <span class="profile-avatar-text"><?php echo esc_html($avatar_char); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-header-info">
                <div class="profile-name-row">
                    <h1 class="profile-display-name"><?php echo esc_html($current_user->display_name); ?></h1>
                    <span class="badge badge-green">
                        <?php 
                            if (current_user_can('administrator')) echo '<i class="fa-solid fa-shield-halved"></i> Quản Trị Viên';
                            elseif (in_array('dich_gia', (array)$current_user->roles)) echo '<i class="fa-solid fa-feather-pointed"></i> Dịch Giả Chính Thức';
                            else echo '<i class="fa-solid fa-feather-pointed"></i> Dịch Giả Chính Thức';
                        ?>
                    </span>
                </div>
                <p class="profile-meta-item">
                    <i class="fa-solid fa-envelope"></i> <?php echo esc_html($current_user->user_email); ?>
                </p>
                <p class="profile-meta-item">
                    <i class="fa-solid fa-calendar-check"></i> Ngày tham gia: <strong><?php echo esc_html($registered); ?></strong>
                </p>
                <?php if (!empty($user_bio)) : ?>
                    <p class="profile-bio-text">"<?php echo esc_html($user_bio); ?>"</p>
                <?php endif; ?>
            </div>

            <div class="profile-header-actions">
                <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-plus-circle"></i> Đăng Truyện Mới
                </a>
                <a href="<?php echo esc_url(add_query_arg('from', 'translator', home_url('/them-chuong/'))); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-file-circle-plus"></i> Thêm Chương Mới
                </a>
            </div>
        </div>

        <!-- SALARY & REVENUE HIGHLIGHT (8Đ / VIEW) -->
        <div class="salary-highlight-card">
            <div class="salary-main-col">
                <span class="salary-label">
                    <i class="fa-solid fa-coins"></i> Tiền Nhuận Bút Tháng Hiện Tại (<?php echo date('m/Y'); ?>)
                </span>
                <div class="salary-amount">
                    <?php echo number_format($salary_this_month); ?> <span class="salary-unit">VNĐ</span>
                </div>
                <div class="salary-subtext">
                    Định mức: <strong><?php echo $rate; ?>đ</strong> / 1 lượt xem • Đạt <strong><?php echo number_format($views_this_month); ?></strong> views trong tháng này
                </div>
            </div>

            <div class="salary-prev-col">
                <span class="salary-prev-label">
                    <i class="fa-solid fa-calendar-days"></i> Nhuận Bút Tháng Trước (<?php echo date('m/Y', strtotime('-1 month')); ?>):
                </span>
                <strong class="salary-prev-amount">
                    <?php echo number_format($salary_last_month); ?> VNĐ
                </strong>
                <span class="salary-prev-status">
                    <i class="fa-solid fa-circle-check"></i> Đã quyết toán
                </span>
            </div>
        </div>

        <!-- THANH TIỆN ÍCH NẰM TRÊN ẢNH 3: NÚT LỊCH SỬ NHẬN TIỀN & XUẤT THÔNG TIN NGÂN HÀNG (NẾU CÓ) -->
        <div class="translator-top-action-bar">
            <!-- Nút Lịch Sử Nhận Tiền & Bill Chuyển Khoản (Ảnh 2 thành 1 button nằm trên Ảnh 3) -->
            <button type="button" class="btn btn-secondary btn-salary-history-pill" id="btnOpenSalaryHistoryModal">
                <i class="fa-solid fa-receipt"></i> Lịch Sử Nhận Tiền & Bill Chuyển Khoản (<?php echo count($my_payments); ?> đợt)
            </button>

            <!-- Xuất thông tin tài khoản ngân hàng đã nhập (Nếu đã có thì xuất lên, nếu không có thì không hiện) -->
            <?php if (!empty($bank_holder) && !empty($bank_number)) : ?>
                <div class="translator-bank-info-pill">
                    <i class="fa-solid fa-building-columns" style="color: var(--primary-green); font-size: 15px;"></i>
                    <span class="bank-pill-text">
                        Tài khoản nhận tiền: <strong><?php echo esc_html($bank_holder); ?></strong> • <?php echo esc_html($bank_name); ?> (STK: <strong style="color: var(--primary-green);"><?php echo esc_html($bank_number); ?></strong>)
                    </span>
                    <a href="<?php echo esc_url(home_url('/ho-so/#bankAccountSection')); ?>" class="btn-edit-bank-pill" title="Chỉnh sửa thông tin tài khoản trong trang Thông tin">
                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- KPI VIEW STATS GRID (HÔM NAY, TUẦN, THÁNG, NĂM) -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-calendar-day"></i></div>
                <div>
                    <div class="kpi-value"><?php echo number_format($views_today); ?></div>
                    <div class="kpi-label">Lượt Xem Hôm Nay</div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-calendar-week"></i></div>
                <div>
                    <div class="kpi-value"><?php echo number_format($views_week); ?></div>
                    <div class="kpi-label">Lượt Xem Tuần Này</div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-calendar"></i></div>
                <div>
                    <div class="kpi-value"><?php echo number_format($views_this_month); ?></div>
                    <div class="kpi-label">Lượt Xem Tháng Này</div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-book"></i></div>
                <div>
                    <div class="kpi-value"><?php echo number_format($story_count); ?></div>
                    <div class="kpi-label">Tổng Truyện Đã Đăng</div>
                </div>
            </div>
        </div>

        <!-- MODAL POPUP LỊCH SỬ NHẬN TIỀN & BILL CHUYỂN KHOẢN (ẢNH 2 - MỞ KHI BẤM NÚT NẰM TRÊN ẢNH 3) -->
        <div class="modal-backdrop" id="modalSalaryHistory" style="display: none;" aria-hidden="true">
            <div class="modal-content" style="max-width: 760px; width: 100%; padding: 22px; border-radius: var(--radius-lg);">
                <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    <h3 style="font-size: 16.5px; font-weight: 700; color: var(--primary-green); margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-receipt"></i> Lịch Sử Nhận Tiền & Bill Chuyển Khoản
                        <span class="count-badge" style="font-size: 12.5px; font-weight: 600;">(<?php echo count($my_payments); ?> đợt)</span>
                    </h3>
                    <button type="button" class="btn-close-modal" id="btnCloseSalaryHistoryModal" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);" aria-label="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <?php if (!empty($my_payments)) : ?>
                    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                        <table class="data-table" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>Kỳ Quyết Toán</th>
                                    <th>Lượt Xem</th>
                                    <th>Số Tiền Nhận</th>
                                    <th>Trạng Thái</th>
                                    <th>Thời Gian</th>
                                    <th>Hóa Đơn / Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_payments as $pay) : ?>
                                    <tr>
                                        <td><strong>Tháng <?php echo esc_html($pay->period); ?></strong></td>
                                        <td><i class="fa-solid fa-eye"></i> <?php echo number_format($pay->views); ?></td>
                                        <td style="color: #2E7D32; font-weight: 800; font-size: 14px;"><?php echo number_format($pay->amount); ?> VNĐ</td>
                                        <td>
                                            <span class="badge badge-full"><i class="fa-solid fa-check-circle"></i> Đã thanh toán</span>
                                        </td>
                                        <td style="font-size: 12px; color: var(--text-muted);"><?php echo date('d/m/Y H:i', strtotime($pay->created_at)); ?></td>
                                        <td>
                                            <?php if (!empty($pay->bill_image)) : ?>
                                                <button type="button" class="btn btn-secondary btn-sm btn-view-bill" data-bill-url="<?php echo esc_url($pay->bill_image); ?>" style="padding: 4px 10px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 5px;">
                                                    <i class="fa-solid fa-receipt"></i> Xem Bill
                                                </button>
                                            <?php else : ?>
                                                <span style="color: var(--text-muted); font-size: 11px;">Không có bill</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <!-- ĐÚNG GIAO DIỆN Ở ẢNH 2 KHI CHƯA CÓ LƯỢT THANH TOÁN NÀO -->
                    <div style="text-align: center; padding: 40px 20px; background: var(--pastel-green); border-radius: var(--radius-sm); color: var(--text-muted); margin: 10px 0;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 32px; background: #689f38; color: #fff; border-radius: 4px; font-size: 18px; font-weight: 800; margin-bottom: 12px;">
                            $=
                        </div>
                        <p style="margin: 0; font-size: 13.5px; color: #374151;">Chưa có lượt thanh toán nào. Admin sẽ chuyển tiền và gửi bill đính kèm vào cuối mỗi chu kỳ đối soát!</p>
                    </div>
                <?php endif; ?>

                <div style="display: flex; justify-content: flex-end; margin-top: 18px;">
                    <button type="button" class="btn btn-secondary" id="btnDismissSalaryHistoryModal" style="padding: 7px 18px; font-size: 13px;">
                        Đóng
                    </button>
                </div>
            </div>
        </div>

        <!-- PHẦN 1: QUẢN LÝ TRUYỆN CỦA TÔI -->
        <div class="translator-section-block">
            <div class="dash-section-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <h2 class="dash-section-title" style="margin-bottom: 0;">
                    <i class="fa-solid fa-book-bookmark"></i> Quản Lý Truyện Của Tôi 
                    <span class="count-badge">(<span id="badgeTotalStoryCount"><?php echo $story_count; ?></span>)</span>
                </h2>
                <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-plus-circle"></i> Đăng Truyện Mới
                </a>
            </div>

            <!-- THANH LỌC TRUYỆN: TẤT CẢ / ĐANG PHÁT HÀNH / ĐANG HẸN GIỜ / CHỜ DUYỆT / ĐÃ ẨN / MỤC LƯU NHÁP -->
            <div class="story-filter-tabs" style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; margin-bottom: 16px;">
                <button type="button" class="btn btn-tab-filter active" data-filter="all" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer;">
                    <i class="fa-solid fa-layer-group"></i> Tất Cả (<?php echo $count_all; ?>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="publish" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer;">
                    <i class="fa-solid fa-circle-check"></i> Đang Phát Hành (<?php echo $count_publish; ?>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="future" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer; color: #7c3aed; border-color: #ddd6fe;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Đang Hẹn Giờ (<span id="badgeFutureCount"><?php echo $count_future; ?></span>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="pending" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer;">
                    <i class="fa-solid fa-clock"></i> Chờ Duyệt (<?php echo $count_pending; ?>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="hidden" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer; color: #d97706; border-color: #fde68a;">
                    <i class="fa-solid fa-eye-slash"></i> Đã Ẩn (<span id="badgeHiddenCount"><?php echo $count_hidden; ?></span>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="draft" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer; color: #b45309; border-color: #fde68a;">
                    <i class="fa-solid fa-file-pen"></i> Mục Lưu Nháp (<span id="badgeDraftCount"><?php echo $count_draft; ?></span>)
                </button>
            </div>

            <div class="table-scroll-hint">
                <i class="fa-solid fa-arrows-left-right"></i> Vuốt ngang để xem đầy đủ các cột
            </div>

            <?php if (!empty($my_stories)) : ?>
                <div class="table-responsive">
                    <table class="data-table translator-stories-table">
                        <thead>
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-title">Tên Truyện</th>
                                <th class="col-status">Trạng Thái</th>
                                <th class="col-chaps">Số Chương</th>
                                <th class="col-views">Lượt Xem</th>
                                <th class="col-salary">Nhuận Bút (8đ)</th>
                                <th class="col-review">Kiểm Duyệt</th>
                                <th class="col-actions">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_stories as $index => $st) : 
                                $st_views     = muop_get_story_views($st->ID);
                                $st_status    = get_post_meta($st->ID, '_truyen_status', true);
                                $st_chaps     = count(muop_get_story_chapters($st->ID, 'ASC', array('publish', 'draft', 'pending', 'future')));
                                $st_money     = $st_views * $rate;
                                $st_is_hidden = (bool) get_post_meta($st->ID, '_truyen_is_hidden', true);
                                $row_filter_status = ($st_is_hidden && $st->post_status === 'draft') ? 'hidden' : $st->post_status;
                            ?>
                                <tr class="story-table-row" data-status="<?php echo esc_attr($row_filter_status); ?>" data-story-id="<?php echo esc_attr($st->ID); ?>">
                                    <td class="col-stt">
                                        <?php echo esc_html($index + 1); ?>
                                    </td>
                                    <td class="col-title">
                                        <a href="<?php echo esc_url(add_query_arg('from', 'translator', get_permalink($st->ID))); ?>" class="table-story-title" title="<?php echo esc_attr($st->post_title); ?>">
                                            <?php echo esc_html($st->post_title); ?>
                                        </a>
                                        <?php if ($st_is_hidden && $st->post_status === 'draft') : ?>
                                            <span class="badge" style="background: #fffbeb; color: #d97706; font-size: 10px; margin-left: 4px; border: 1px solid #fde68a;"><i class="fa-solid fa-eye-slash"></i> Đã ẩn</span>
                                        <?php elseif ($st->post_status === 'draft') : ?>
                                            <span class="badge" style="background: #fef3c7; color: #b45309; font-size: 10px; margin-left: 4px;">Bản nháp</span>
                                        <?php elseif ($st->post_status === 'future') : ?>
                                            <span class="badge" style="background: #f5f3ff; color: #7c3aed; font-size: 10px; margin-left: 4px; border: 1px solid #ddd6fe;">Hẹn giờ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-status">
                                        <span class="badge <?php echo ($st_status === 'hoan_thanh') ? 'badge-full' : 'badge-green'; ?> badge-editable btn-table-change-status" data-story-id="<?php echo $st->ID; ?>" data-current-status="<?php echo esc_attr($st_status ?: 'dang_ra'); ?>" title="Nhấn để đổi trạng thái truyện (Đang ra / Hoàn thành)">
                                            <i class="fa-solid <?php echo ($st_status === 'hoan_thanh') ? 'fa-check' : 'fa-arrows-rotate'; ?>"></i>
                                            <span class="table-status-text"><?php echo ($st_status === 'hoan_thanh') ? 'Full' : 'Đang ra'; ?></span>
                                            <i class="fa-solid fa-pen" style="font-size: 9px; opacity: 0.7; margin-left: 2px;"></i>
                                        </span>
                                    </td>
                                    <td class="col-chaps"><strong><?php echo $st_chaps; ?></strong> chương</td>
                                    <td class="col-views"><i class="fa-solid fa-eye"></i> <?php echo number_format($st_views); ?></td>
                                    <td class="col-salary" style="color: #2E7D32; font-weight: 700;"><?php echo number_format($st_money); ?> đ</td>
                                    <td class="col-review">
                                        <?php if ($st_is_hidden && $st->post_status === 'draft') : ?>
                                            <span class="badge" style="background: #fffbeb; color: #d97706; font-weight: 700; border: 1px solid #fde68a;"><i class="fa-solid fa-eye-slash"></i> Đã ẩn</span>
                                        <?php elseif ($st->post_status === 'publish') : ?>
                                            <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Đã duyệt</span>
                                        <?php elseif ($st->post_status === 'future') : 
                                            $st_sched_time = get_post_meta($st->ID, '_truyen_scheduled_time', true) ?: $st->post_date;
                                            $st_sched_fmt  = muop_format_datetime($st_sched_time, 'H:i - d/m/Y');
                                        ?>
                                            <span class="badge badge-scheduled btn-show-scheduled-popup" 
                                                  data-story-id="<?php echo $st->ID; ?>" 
                                                  data-story-title="<?php echo esc_attr($st->post_title); ?>" 
                                                  data-scheduled-time="<?php echo esc_attr($st_sched_time); ?>" 
                                                  data-scheduled-formatted="<?php echo esc_attr($st_sched_fmt); ?>" 
                                                  onclick="if(window.muopOpenScheduleModal){window.muopOpenScheduleModal(<?php echo $st->ID; ?>, '<?php echo esc_js($st->post_title); ?>', '<?php echo esc_js($st_sched_time); ?>', '<?php echo esc_js($st_sched_fmt); ?>'); return false;}"
                                                  style="cursor: pointer;"
                                                  title="Nhấn để xem thời gian đã lên lịch">
                                                <i class="fa-solid fa-clock-rotate-left"></i> Đã hẹn giờ
                                            </span>
                                        <?php elseif ($st->post_status === 'pending') : ?>
                                            <span class="badge badge-nominate"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                        <?php else : ?>
                                            <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700;">Lưu nháp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-actions">
                                        <div class="story-actions-dropdown">
                                            <button type="button" class="btn-story-dropdown-trigger" title="Tác vụ truyện" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="story-dropdown-menu">
                                                <?php if ($st_is_hidden && $st->post_status === 'draft') : ?>
                                                    <!-- Truyện đang ở trạng thái ẩn -> Hiện truyện -->
                                                    <button type="button" class="story-dropdown-item btn-translator-toggle-hide" data-action="unhide" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                        <i class="fa-solid fa-eye" style="color: var(--primary-green);"></i> Hiện truyện
                                                    </button>
                                                    <button type="button" class="story-dropdown-item btn-action-schedule-draft" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                        <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Hẹn giờ công khai
                                                    </button>
                                                <?php elseif ($st->post_status === 'draft') : ?>
                                                    <button type="button" class="story-dropdown-item btn-publish-draft" data-story-id="<?php echo $st->ID; ?>">
                                                        <i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary-green);"></i> Đăng truyện lên
                                                    </button>
                                                    <button type="button" class="story-dropdown-item btn-action-schedule-draft" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                        <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Hẹn giờ công khai
                                                    </button>
                                                <?php elseif ($st->post_status === 'future') : 
                                                    $st_sched_time = get_post_meta($st->ID, '_truyen_scheduled_time', true) ?: $st->post_date;
                                                    $st_sched_fmt  = muop_format_datetime($st_sched_time, 'H:i - d/m/Y');
                                                ?>
                                                    <button type="button" class="story-dropdown-item btn-show-scheduled-popup" 
                                                            data-story-id="<?php echo $st->ID; ?>" 
                                                            data-story-title="<?php echo esc_attr($st->post_title); ?>" 
                                                            data-scheduled-time="<?php echo esc_attr($st_sched_time); ?>" 
                                                            data-scheduled-formatted="<?php echo esc_attr($st_sched_fmt); ?>"
                                                            onclick="if(window.muopOpenScheduleModal){window.muopOpenScheduleModal(<?php echo $st->ID; ?>, '<?php echo esc_js($st->post_title); ?>', '<?php echo esc_js($st_sched_time); ?>', '<?php echo esc_js($st_sched_fmt); ?>'); return false;}">
                                                        <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Xem giờ đã hẹn
                                                    </button>
                                                    <button type="button" class="story-dropdown-item btn-publish-draft" data-story-id="<?php echo $st->ID; ?>">
                                                        <i class="fa-solid fa-bolt" style="color: #2E7D32;"></i> Đăng ngay bây giờ
                                                    </button>
                                                    <button type="button" class="story-dropdown-item btn-cancel-schedule-action" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                        <i class="fa-solid fa-file-pen" style="color: #b45309;"></i> Hủy hẹn (Về bản nháp)
                                                    </button>
                                                <?php endif; ?>

                                                <button type="button" class="story-dropdown-item btn-action-edit-story-desc" data-story-id="<?php echo $st->ID; ?>">
                                                    <i class="fa-solid fa-pen-to-square" style="color: var(--primary-green);"></i> Chỉnh sửa mô tả truyện
                                                </button>

                                                <button type="button" class="story-dropdown-item btn-action-edit-story-chaps" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                    <i class="fa-solid fa-file-pen" style="color: #2563eb;"></i> Chỉnh sửa chương
                                                </button>

                                                <a href="<?php echo esc_url(add_query_arg(array('story_id' => $st->ID, 'from' => 'translator'), home_url('/them-chuong/'))); ?>" class="story-dropdown-item">
                                                    <i class="fa-solid fa-plus" style="color: #16a34a;"></i> Thêm mới chương
                                                </a>

                                                <?php if ($st->post_status === 'publish') : ?>
                                                    <!-- Dịch giả có thể ẩn truyện đang phát hành của mình -->
                                                    <button type="button" class="story-dropdown-item btn-translator-toggle-hide" data-action="hide" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                        <i class="fa-solid fa-eye-slash" style="color: #d97706;"></i> Ẩn truyện
                                                    </button>
                                                <?php endif; ?>

                                                <div class="story-dropdown-divider"></div>

                                                <button type="button" class="story-dropdown-item item-danger btn-delete-story" data-story-id="<?php echo $st->ID; ?>">
                                                    <i class="fa-solid fa-trash-can" style="color: #dc2626;"></i> Xóa truyện
                                                </button>

                                                <button type="button" class="story-dropdown-item item-danger btn-action-open-delete-chaps" data-story-id="<?php echo $st->ID; ?>" data-story-title="<?php echo esc_attr($st->post_title); ?>">
                                                    <i class="fa-solid fa-rectangle-xmark" style="color: #dc2626;"></i> Xóa chương truyện
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="noStoriesFilterMsg" style="display: none; text-align: center; padding: 30px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted); margin-top: 10px;">
                    Không có bộ truyện nào trong mục này.
                </div>
            <?php else : ?>
                <div style="text-align: center; padding: 40px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted); margin-bottom: 24px;">
                    <i class="fa-solid fa-feather" style="font-size: 36px; color: var(--avocado-green); margin-bottom: 10px;"></i>
                    <p>Bạn chưa đăng bộ truyện nào. Hãy bắt đầu ngay để kiếm nhuận bút 8đ cho mỗi lượt đọc nhé!</p>
                    <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="btn btn-primary" style="margin-top: 14px;">
                        <i class="fa-solid fa-plus-circle"></i> Đăng Truyện Đầu Tiên
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- MODAL POPUP MASTER CHỈNH SỬA TRUYỆN & CHƯƠNG -->
        <?php get_template_part('templates/modal-edit-story-master'); ?>
        <?php get_template_part('templates/modal-schedule-story'); ?>
        </div>

        <!-- PHẦN 2: BÌNH LUẬN TỪ ĐỘC GIẢ (ĐƯA XUỐNG PHÍA DƯỚI) -->
        <div class="translator-section-block" style="margin-top: 36px;">
            <div class="dash-section-header">
                <h2 class="dash-section-title">
                    <i class="fa-solid fa-comments"></i> Bình Luận Từ Độc Giả 
                    <span class="count-badge">(<?php echo count($comments_on_stories); ?>)</span>
                </h2>
            </div>

            <?php if (!empty($comments_on_stories)) : ?>
                <ul class="comment-list-ul">
                    <?php foreach ($comments_on_stories as $cmt) : 
                        $cmt_post = get_post($cmt->comment_post_ID);
                    ?>
                        <li class="comment-item" id="comment-<?php echo $cmt->comment_ID; ?>">
                            <div class="comment-avatar">
                                <?php echo esc_html(strtoupper(substr($cmt->comment_author, 0, 1))); ?>
                            </div>
                            <div class="comment-body">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <div>
                                        <span class="comment-author-name"><?php echo esc_html($cmt->comment_author); ?></span>
                                        <span class="comment-time"><?php echo esc_html(muop_time_ago(strtotime($cmt->comment_date))); ?></span>
                                        <span style="font-size: 12px; color: var(--text-muted); margin-left: 10px;">
                                            trong truyện: <a href="<?php echo esc_url(get_permalink($cmt->comment_post_ID)); ?>" style="font-weight: 600; color: var(--primary-green);"><?php echo esc_html($cmt_post ? $cmt_post->post_title : ''); ?></a>
                                        </span>
                                    </div>
                                    <?php if (muop_can_user_delete_comment($cmt)) : ?>
                                        <button type="button" class="btn-delete-comment" data-comment-id="<?php echo (int) $cmt->comment_ID; ?>" title="Xóa bình luận">
                                            <i class="fa-solid fa-trash-can"></i> Xóa
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="comment-text"><?php echo muop_render_comment_content($cmt->comment_content); ?></div>
                                <div style="margin-top: 8px;">
                                    <a href="<?php echo esc_url(get_permalink($cmt->comment_post_ID)); ?>#comments" class="btn btn-secondary" style="padding: 3px 10px; font-size: 11.5px;">
                                        <i class="fa-solid fa-reply"></i> Trả lời độc giả
                                    </a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <div style="text-align: center; padding: 40px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted);">
                    <i class="fa-solid fa-comments" style="font-size: 36px; color: var(--avocado-green); margin-bottom: 10px;"></i>
                    <p>Chưa có bình luận nào trên các bộ truyện của bạn.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();
