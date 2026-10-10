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

// Stories of this translator
$my_stories = get_posts(array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => array('publish', 'pending', 'draft'),
    'author'         => $user_id
));

$story_count = count($my_stories);
$story_ids   = wp_list_pluck($my_stories, 'ID');

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

        <!-- PROFILE HEADER -->
        <div style="display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green); padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-green), var(--avocado-green)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; box-shadow: var(--shadow-sm);">
                    <?php echo esc_html($avatar_char); ?>
                </div>

                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0;"><?php echo esc_html($current_user->display_name); ?></h1>
                        <span class="badge badge-green">
                            <i class="fa-solid fa-feather-pointed"></i> Dịch Giả Chính Thức
                        </span>
                    </div>
                    <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 4px;">
                        <i class="fa-solid fa-envelope" style="margin-right: 4px;"></i> <?php echo esc_html($current_user->user_email); ?>
                    </p>
                    <p style="font-size: 12.5px; color: var(--text-muted);">
                        <i class="fa-solid fa-calendar-check" style="margin-right: 4px;"></i> Ngày tham gia: <strong><?php echo esc_html($registered); ?></strong>
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
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
            <div>
                <span style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-coins" style="color: #F57F17;"></i> Tiền Nhuận Bút Tháng Hiện Tại (<?php echo date('m/Y'); ?>)
                </span>
                <div class="salary-amount">
                    <?php echo number_format($salary_this_month); ?> <span style="font-size: 18px; font-weight: 600;">VNĐ</span>
                </div>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                    Định mức: <strong><?php echo $rate; ?>đ</strong> / 1 lượt xem • Đạt <strong><?php echo number_format($views_this_month); ?></strong> views trong tháng này
                </div>
            </div>

            <div style="text-align: right; border-left: 1px solid var(--border-color); padding-left: 28px;">
                <span style="font-size: 12.5px; color: var(--text-muted); display: block; margin-bottom: 4px;">
                    <i class="fa-solid fa-calendar-days"></i> Nhuận Bút Tháng Trước (<?php echo date('m/Y', strtotime('-1 month')); ?>):
                </span>
                <strong style="font-size: 20px; color: var(--primary-green);">
                    <?php echo number_format($salary_last_month); ?> VNĐ
                </strong>
                <span style="display: block; font-size: 11.5px; color: #2E7D32;">
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

        <!-- TABS NAV -->
        <div class="dash-tabs-nav">
            <button type="button" class="dash-tab-btn active" data-tab="tabMyStories">
                <i class="fa-solid fa-book-bookmark"></i> Quản Lý Truyện Của Tôi (<?php echo $story_count; ?>)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="tabComments">
                <i class="fa-solid fa-comments"></i> Bình Luận Từ Độc Giả (<?php echo count($comments_on_stories); ?>)
            </button>
        </div>

        <!-- TAB 1: QUẢN LÝ TRUYỆN ĐÃ ĐĂNG -->
        <div id="tabMyStories" class="dash-tab-pane active">
            <?php if (!empty($my_stories)) : ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên Truyện</th>
                                <th>Trạng Thái</th>
                                <th>Số Chương</th>
                                <th>Lượt Xem</th>
                                <th>Nhuận Bút (8đ)</th>
                                <th>Kiểm Duyệt</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_stories as $st) : 
                                $st_views  = muop_get_story_views($st->ID);
                                $st_status = get_post_meta($st->ID, '_truyen_status', true);
                                $st_chaps  = count(muop_get_story_chapters($st->ID));
                                $st_money  = $st_views * $rate;
                            ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo esc_url(get_permalink($st->ID)); ?>" style="font-weight: 700; color: var(--primary-green);">
                                            <?php echo esc_html($st->post_title); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo ($st_status === 'hoan_thanh') ? 'badge-full' : 'badge-green'; ?> badge-editable btn-table-change-status" data-story-id="<?php echo $st->ID; ?>" data-current-status="<?php echo esc_attr($st_status ?: 'dang_ra'); ?>" title="Nhấn để đổi trạng thái truyện (Đang ra / Hoàn thành)">
                                            <i class="fa-solid <?php echo ($st_status === 'hoan_thanh') ? 'fa-check' : 'fa-arrows-rotate'; ?>"></i>
                                            <span class="table-status-text"><?php echo ($st_status === 'hoan_thanh') ? 'Full' : 'Đang ra'; ?></span>
                                            <i class="fa-solid fa-pen" style="font-size: 9px; opacity: 0.7; margin-left: 2px;"></i>
                                        </span>
                                    </td>
                                    <td><strong><?php echo $st_chaps; ?></strong> chương</td>
                                    <td><i class="fa-solid fa-eye"></i> <?php echo number_format($st_views); ?></td>
                                    <td style="color: #2E7D32; font-weight: 700;"><?php echo number_format($st_money); ?> đ</td>
                                    <td>
                                        <?php if ($st->post_status === 'publish') : ?>
                                            <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Đã duyệt</span>
                                        <?php elseif ($st->post_status === 'pending') : ?>
                                            <span class="badge badge-nominate"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                        <?php else : ?>
                                            <span class="badge badge-hot">Bản nháp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px; align-items: center;">
                                            <a href="<?php echo esc_url(add_query_arg('story_id', $st->ID, home_url('/them-chuong/'))); ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;" title="Thêm chương">
                                                <i class="fa-solid fa-plus"></i> Chương
                                            </a>
                                            <a href="<?php echo esc_url(get_edit_post_link($st->ID)); ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;" title="Sửa truyện">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
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
            <?php else : ?>
                <div style="text-align: center; padding: 40px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted);">
                    <i class="fa-solid fa-feather" style="font-size: 36px; color: var(--avocado-green); margin-bottom: 10px;"></i>
                    <p>Bạn chưa đăng bộ truyện nào. Hãy bắt đầu ngay để kiếm nhuận bút 8đ cho mỗi lượt đọc nhé!</p>
                    <a href="<?php echo esc_url(home_url('/dang-truyen/')); ?>" class="btn btn-primary" style="margin-top: 14px;">
                        <i class="fa-solid fa-plus-circle"></i> Đăng Truyện Đầu Tiên
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- TAB 2: BÌNH LUẬN TỪ ĐỘC GIẢ & TRẢ LỜI -->
        <div id="tabComments" class="dash-tab-pane">
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
                                    <span class="comment-time"><?php echo esc_html(human_time_diff(strtotime($cmt->comment_date), current_time('timestamp'))) . ' trước'; ?></span>
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
