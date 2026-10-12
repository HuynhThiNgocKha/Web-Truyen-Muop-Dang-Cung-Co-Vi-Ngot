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
        <div id="modalEditStoryMaster" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
            <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 820px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
                
                <!-- MODAL HEADER -->
                <div style="padding: 16px 20px; background: var(--pastel-green); border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
                    <div>
                        <h3 style="margin: 0; font-size: 16.5px; font-weight: 700; color: var(--primary-green); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa: <span id="modalStoryTitleText" style="color: var(--text-main); font-weight: 600;">...</span>
                        </h3>
                        <p style="margin: 2px 0 0 0; font-size: 12.5px; color: var(--text-muted);">
                            Chọn chỉnh sửa thông tin mô tả truyện hoặc nội dung các chương truyện
                        </p>
                    </div>
                    <button type="button" id="btnCloseStoryMasterModal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- TABS SWITCHER TRONG MODAL -->
                <div style="display: flex; border-bottom: 1px solid var(--border-light); background: var(--bg-card); padding: 0 16px; flex-shrink: 0;">
                    <button type="button" class="modal-tab-nav active" data-target="paneEditStoryInfo" style="padding: 12px 18px; border: none; background: none; font-weight: 700; font-size: 13.5px; color: var(--primary-green); border-bottom: 3px solid var(--primary-green); cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-book"></i> 1. Chỉnh Sửa Thông Tin Truyện
                    </button>
                    <button type="button" class="modal-tab-nav" data-target="paneEditChapterContent" style="padding: 12px 18px; border: none; background: none; font-weight: 600; font-size: 13.5px; color: var(--text-muted); border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-file-lines"></i> 2. Chỉnh Sửa Nội Dung Chương Truyện
                    </button>
                </div>

                <div style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- TAB PANE 1: CHỈNH SỬA THÔNG TIN TRUYỆN -->
                    <div id="paneEditStoryInfo" class="modal-tab-pane active">
                        <div id="editStoryAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>
                        
                        <form id="formEditStoryMaster" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="muop_update_story" />
                            <input type="hidden" name="story_id" id="editStoryId" value="" />
                            <input type="hidden" name="is_draft" id="editStoryIsDraftInput" value="0" />
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="editStoryTitle">Tên Truyện (*)</label>
                                    <input type="text" id="editStoryTitle" name="title" class="form-input" required />
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="editStoryStatus">Trạng Thái</label>
                                    <select id="editStoryStatus" name="status" class="form-input">
                                        <option value="dang_ra">Đang ra</option>
                                        <option value="hoan_thanh">Hoàn thành (Full)</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="editStoryAuthor">Tác Giả</label>
                                    <input type="text" id="editStoryAuthor" name="author_name" class="form-input" />
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="editStoryTeam">Nhóm Dịch / Người Dịch</label>
                                    <input type="text" id="editStoryTeam" name="team_name" class="form-input" />
                                </div>
                            </div>

                            <!-- Ảnh Bìa Truyện -->
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label">Ảnh Bìa</label>
                                <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
                                    <img id="editStoryCoverPreview" src="" alt="Ảnh bìa" style="width: 65px; height: 85px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-body); display: none;" />
                                    <div>
                                        <input type="file" id="editStoryCoverFile" name="cover_image" accept="image/*" style="display: none;" />
                                        <button type="button" id="btnEditUploadCover" class="btn btn-secondary" style="padding: 7px 14px; font-size: 13px;">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị
                                        </button>
                                        <div id="editStoryCoverInfo" style="display: none; align-items: center; gap: 6px; margin-top: 6px; font-size: 12.5px;">
                                            <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                                            <span id="editStoryCoverFileName" style="font-weight: 600;"></span>
                                            <span class="badge badge-green" style="font-size: 11px;">Đã chọn ảnh mới</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thể Loại -->
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label">Chọn Thể Loại</label>
                                <div style="margin-bottom: 8px; position: relative;">
                                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12px;"></i>
                                    <input type="text" id="editInputSearchGenre" class="form-input" placeholder="Nhập tìm thể loại..." style="padding-left: 30px; height: 32px; font-size: 12.5px;" />
                                </div>
                                <div id="editGenresCheckboxList" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; max-height: 140px; overflow-y: auto; background: var(--bg-card); padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                    <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                                        <?php foreach ($all_genres as $genre) : ?>
                                            <label class="edit-genre-item" data-name="<?php echo esc_attr(mb_strtolower($genre->name, 'UTF-8')); ?>" style="display: flex; align-items: center; gap: 6px; font-size: 12.5px; cursor: pointer;">
                                                <input type="checkbox" name="categories[]" value="<?php echo esc_attr($genre->term_id); ?>" />
                                                <span><?php echo esc_html($genre->name); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Giới Thiệu / Văn Án -->
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label class="form-label" for="editStoryDescription">Giới Thiệu / Văn Án Truyện (*)</label>
                                <textarea id="editStoryDescription" name="description" class="form-input" rows="7" required></textarea>
                            </div>

                            <!-- Buttons Hủy, Lưu Nháp & Cập Nhật Truyện -->
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary btn-close-modal-action">Hủy</button>
                                <button type="button" id="btnSaveDraftStoryAction" class="btn btn-outline" style="padding: 8px 18px; font-size: 13.5px;" title="Lưu thành bản nháp">
                                    <i class="fa-solid fa-file-pen"></i> Lưu Vào Bản Nháp
                                </button>
                                <button type="submit" id="btnUpdateStoryAction" class="btn btn-primary" style="padding: 8px 24px; font-size: 13.5px;">
                                    <i class="fa-solid fa-check"></i> Cập Nhật Truyện
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB PANE 2: CHỈNH SỬA NỘI DUNG CHƯƠNG TRUYỆN -->
                    <div id="paneEditChapterContent" class="modal-tab-pane" style="display: none;">
                        <div id="editChapterAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

                        <div style="background: var(--pastel-green); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 16px;">
                            <label class="form-label" for="selectEditChapter" style="margin-bottom: 6px; font-weight: 700;">
                                <i class="fa-solid fa-list-ol"></i> Chọn Chương Cần Chỉnh Sửa:
                            </label>
                            <select id="selectEditChapter" class="form-input" style="height: 38px; font-size: 13.5px;">
                                <option value="">-- Đang tải danh sách chương... --</option>
                            </select>
                        </div>

                        <!-- Thông báo nếu truyện chưa có chương -->
                        <div id="editNoChaptersMsg" style="display: none; text-align: center; padding: 30px; background: var(--bg-card); border-radius: var(--radius-sm); border: 1px dashed var(--border-color); color: var(--text-muted);">
                            <i class="fa-solid fa-circle-info" style="font-size: 24px; color: var(--primary-green); margin-bottom: 8px; display: block;"></i>
                            Truyện này hiện chưa có chương nào. Bạn có thể thêm chương mới ngay bây giờ!
                            <div style="margin-top: 12px;">
                                <a id="btnGoAddChapterFromModal" href="<?php echo esc_url(home_url('/them-chuong/')); ?>" class="btn btn-primary" style="padding: 6px 16px; font-size: 13px;">
                                    <i class="fa-solid fa-plus"></i> Thêm Chương Mới
                                </a>
                            </div>
                        </div>

                        <!-- Form Chỉnh Sửa Chương (khi đã chọn chương) -->
                        <form id="formEditChapterFields" style="display: none;">
                            <input type="hidden" id="editChapterId" name="chapter_id" value="" />
                            <input type="hidden" id="editChapterIsDraftInput" name="is_draft" value="0" />
                            <input type="hidden" name="action" value="muop_update_chapter" />
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                            <div style="display: grid; grid-template-columns: 140px 1fr; gap: 14px; margin-bottom: 14px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="editChapterNumber">Số Chương (*)</label>
                                    <input type="number" id="editChapterNumber" name="chapter_number" class="form-input" min="1" required />
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="editChapterTitle">Tên Chương (*)</label>
                                    <input type="text" id="editChapterTitle" name="chapter_title" class="form-input" required />
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label class="form-label" for="editChapterContent">Nội Dung Chương (*)</label>
                                <textarea id="editChapterContent" name="content" class="form-input" rows="12" required placeholder="Nội dung chương truyện..."></textarea>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary btn-close-modal-action">Hủy</button>
                                <button type="button" id="btnSaveDraftChapterAction" class="btn btn-outline" style="padding: 8px 18px; font-size: 13.5px;" title="Lưu nháp chương">
                                    <i class="fa-solid fa-file-pen"></i> Lưu Nháp Chương
                                </button>
                                <button type="submit" id="btnUpdateChapterAction" class="btn btn-primary" style="padding: 8px 24px; font-size: 13.5px;">
                                    <i class="fa-solid fa-check"></i> Cập Nhật Chương
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
