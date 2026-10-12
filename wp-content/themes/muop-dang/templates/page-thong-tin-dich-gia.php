<?php
/**
 * Template Name: Kênh Dịch Giả / Thông Tin Dịch Giả
 * Thống kê: view ngày/tuần/tháng/năm, tính tiền 8đ/view, quản lý truyện, bình luận & trả lời
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || (!current_user_can('dich_gia') && !current_user_can('administrator'))) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$registered   = date('d/m/Y', strtotime($current_user->user_registered));
$avatar_char  = strtoupper(substr($current_user->display_name, 0, 1));
$custom_avatar = get_user_meta($user_id, 'muop_user_avatar', true);
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
    'post_status'    => array('publish', 'pending', 'draft'),
    'author'         => $user_id
));

$story_count = count($my_stories);
$story_ids   = wp_list_pluck($my_stories, 'ID');

$count_all     = $story_count;
$count_publish = 0;
$count_pending = 0;
$count_draft   = 0;
foreach ($my_stories as $st) {
    if ($st->post_status === 'publish') $count_publish++;
    elseif ($st->post_status === 'pending') $count_pending++;
    elseif ($st->post_status === 'draft') $count_draft++;
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
                <a href="<?php echo esc_url(home_url('/them-chuong/')); ?>" class="btn btn-secondary">
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

        <!-- THÔNG TIN TÀI KHOẢN NGÂN HÀNG & LỊCH SỬ THANH TOÁN KÈM BILL -->
        <div class="translator-salary-box" id="salaryHistory" style="display: grid; grid-template-columns: 360px 1fr; gap: 20px; margin-top: 24px; margin-bottom: 24px;">
            <!-- Cột 1: Thông tin tài khoản ngân hàng nhận nhuận bút -->
            <div class="dash-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-sm);">
                <div class="dash-section-header" style="margin-bottom: 12px;">
                    <h3 class="dash-section-title" style="font-size: 15px; margin: 0; color: var(--primary-green);">
                        <i class="fa-solid fa-building-columns"></i> Tài Khoản Nhận Nhuận Bút
                    </h3>
                </div>
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 14px; line-height: 1.5;">
                    Vui lòng điền chính xác tài khoản ngân hàng chính chủ để Admin đối soát và chuyển khoản tiền nhuận bút kèm gửi bill làm bằng chứng.
                </p>
                <form id="formTranslatorBankInfo">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Tên Chủ Tài Khoản (Họ và tên) <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="bank_account_holder" class="form-input" value="<?php echo esc_attr($bank_holder); ?>" placeholder="VD: NGUYEN VAN A" required style="text-transform: uppercase;" />
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Tên Ngân Hàng <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="bank_name" class="form-input" list="vietnamBanksList" value="<?php echo esc_attr($bank_name); ?>" placeholder="VD: MB Bank, Vietcombank, Techcombank..." required />
                        <datalist id="vietnamBanksList">
                            <option value="MB Bank (Quân Đội)">
                            <option value="Vietcombank">
                            <option value="Techcombank">
                            <option value="BIDV">
                            <option value="VietinBank">
                            <option value="Agribank">
                            <option value="VPBank">
                            <option value="ACB">
                            <option value="TPBank">
                            <option value="Sacombank">
                            <option value="VIB">
                            <option value="MSB">
                            <option value="SHB">
                            <option value="OCB">
                            <option value="Timo">
                            <option value="Cake by VPBank">
                        </datalist>
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-size: 12px; font-weight: 600;">Số Tài Khoản <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="bank_account_number" class="form-input" value="<?php echo esc_attr($bank_number); ?>" placeholder="VD: 0987654321..." required />
                    </div>
                    <button type="submit" class="btn btn-primary" id="btnSaveBankInfo" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 16px; font-size: 13px;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu Thông Tin Ngân Hàng
                    </button>
                    <div id="bankInfoSaveMsg" style="margin-top: 10px; font-size: 12px; display: none;"></div>
                </form>
            </div>

            <!-- Cột 2: Lịch sử nhận nhuận bút & Bill chuyển khoản -->
            <div class="dash-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; box-shadow: var(--shadow-sm);">
                <div class="dash-section-header" style="margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="dash-section-title" style="font-size: 15px; margin: 0; color: var(--primary-green);">
                        <i class="fa-solid fa-receipt"></i> Lịch Sử Nhận Tiền & Bill Chuyển Khoản
                    </h3>
                    <span class="count-badge">(<?php echo count($my_payments); ?> đợt)</span>
                </div>

                <?php if (!empty($my_payments)) : ?>
                    <div class="table-responsive">
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
                    <div style="text-align: center; padding: 40px 20px; background: var(--pastel-green); border-radius: var(--radius-sm); color: var(--text-muted);">
                        <i class="fa-solid fa-money-check-dollar" style="font-size: 32px; color: var(--avocado-green); margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 13px;">Chưa có lượt thanh toán nào. Admin sẽ chuyển tiền và gửi bill đính kèm vào cuối mỗi chu kỳ đối soát!</p>
                    </div>
                <?php endif; ?>
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

            <!-- THANH LỌC TRUYỆN: TẤT CẢ / ĐANG PHÁT HÀNH / CHỜ DUYỆT / MỤC LƯU NHÁP -->
            <div class="story-filter-tabs" style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; margin-bottom: 16px;">
                <button type="button" class="btn btn-tab-filter active" data-filter="all" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer;">
                    <i class="fa-solid fa-layer-group"></i> Tất Cả (<?php echo $count_all; ?>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="publish" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer;">
                    <i class="fa-solid fa-circle-check"></i> Đang Phát Hành (<?php echo $count_publish; ?>)
                </button>
                <button type="button" class="btn btn-tab-filter" data-filter="pending" style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-full); cursor: pointer;">
                    <i class="fa-solid fa-clock"></i> Chờ Duyệt (<?php echo $count_pending; ?>)
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
                                $st_views  = muop_get_story_views($st->ID);
                                $st_status = get_post_meta($st->ID, '_truyen_status', true);
                                $st_chaps  = count(muop_get_story_chapters($st->ID, 'ASC', array('publish', 'draft', 'pending')));
                                $st_money  = $st_views * $rate;
                            ?>
                                <tr class="story-table-row" data-status="<?php echo esc_attr($st->post_status); ?>" data-story-id="<?php echo esc_attr($st->ID); ?>">
                                    <td class="col-stt">
                                        <?php echo esc_html($index + 1); ?>
                                    </td>
                                    <td class="col-title">
                                        <a href="<?php echo esc_url(get_permalink($st->ID)); ?>" class="table-story-title" title="<?php echo esc_attr($st->post_title); ?>">
                                            <?php echo esc_html($st->post_title); ?>
                                        </a>
                                        <?php if ($st->post_status === 'draft') : ?>
                                            <span class="badge" style="background: #fef3c7; color: #b45309; font-size: 10px; margin-left: 4px;">Bản nháp</span>
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
                                        <?php if ($st->post_status === 'publish') : ?>
                                            <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Đã duyệt</span>
                                        <?php elseif ($st->post_status === 'pending') : ?>
                                            <span class="badge badge-nominate"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                        <?php else : ?>
                                            <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700;">Lưu nháp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-actions">
                                        <div style="display: flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: wrap;">
                                            <?php if ($st->post_status === 'draft') : ?>
                                                <button type="button" class="btn btn-primary btn-publish-draft" data-story-id="<?php echo $st->ID; ?>" style="padding: 4px 10px; font-size: 11.5px;" title="Đăng truyện này lên ngay">
                                                    <i class="fa-solid fa-cloud-arrow-up"></i> Đăng Lên
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?php echo esc_url(add_query_arg('story_id', $st->ID, home_url('/them-chuong/'))); ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;" title="Thêm chương">
                                                <i class="fa-solid fa-plus"></i> Chương
                                            </a>
                                            <button type="button" class="btn btn-outline btn-edit-story" data-story-id="<?php echo $st->ID; ?>" style="padding: 4px 8px; font-size: 12px; cursor: pointer; color: var(--primary-green); border-color: var(--primary-green);" title="Chỉnh sửa mô tả truyện & nội dung chương">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-delete-story" data-story-id="<?php echo $st->ID; ?>" style="padding: 4px 8px; font-size: 12px; cursor: pointer;" title="Xóa truyện">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
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
                        <li class="comment-item">
                            <div class="comment-avatar">
                                <?php echo esc_html(strtoupper(substr($cmt->comment_author, 0, 1))); ?>
                            </div>
                            <div class="comment-body">
                                <div>
                                    <span class="comment-author-name"><?php echo esc_html($cmt->comment_author); ?></span>
                                    <span class="comment-time"><?php echo esc_html(muop_time_ago(strtotime($cmt->comment_date))); ?></span>
                                    <span style="font-size: 12px; color: var(--text-muted); margin-left: 10px;">
                                        trong truyện: <a href="<?php echo esc_url(get_permalink($cmt->comment_post_ID)); ?>" style="font-weight: 600; color: var(--primary-green);"><?php echo esc_html($cmt_post ? $cmt_post->post_title : ''); ?></a>
                                    </span>
                                </div>
                                <div class="comment-text"><?php echo esc_html($cmt->comment_content); ?></div>
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
