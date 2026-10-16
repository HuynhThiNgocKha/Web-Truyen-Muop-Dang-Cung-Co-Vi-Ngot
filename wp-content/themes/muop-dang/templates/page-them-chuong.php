<?php
/**
 * Template Name: Trang Thêm Chương Mới
 * Dành cho Dịch Giả & Quản Trị Viên
 */
if (!defined('ABSPATH')) exit;

$current_user  = wp_get_current_user();
$is_admin      = current_user_can('administrator');
$is_translator = muop_user_is_translator($current_user->ID);

$from = isset($_GET['from']) ? sanitize_text_field($_GET['from']) : '';
$from_admin = (isset($_GET['from_admin']) && $_GET['from_admin'] == '1') || ($from === 'admin');

if (empty($from)) {
    if ($from_admin) {
        $from = 'admin';
    } elseif (isset($_SERVER['HTTP_REFERER'])) {
        if (strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false) {
            $from = 'admin';
        } elseif (strpos($_SERVER['HTTP_REFERER'], 'thong-tin-dich-gia') !== false) {
            $from = 'translator';
        } elseif (strpos($_SERVER['HTTP_REFERER'], '/truyen/') !== false) {
            $from = 'story';
        }
    }
}

if (!is_user_logged_in() || (!$is_translator && !$is_admin)) {
    if ($is_admin) {
        wp_redirect(home_url('/quan-ly-admin/'));
        exit;
    }
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$selected_story_id = isset($_GET['story_id']) ? intval($_GET['story_id']) : 0;
$unauthorized_story_error = false;

// Query stories: Dịch giả chỉ được thêm chương vào truyện của mình; Admin quản lý toàn bộ truyện team Mướp Đắng
$story_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => array('publish', 'pending', 'draft')
);
if ($is_admin && $from_admin) {
    $user_stories = get_posts($story_args);
} elseif ($is_admin) {
    $all_temp_stories = get_posts($story_args);
    $user_stories = array_values(array_filter($all_temp_stories, function($st) use ($current_user) {
        return ((int)$st->post_author === (int)$current_user->ID || muop_is_story_of_team_muop_dang($st->ID));
    }));
} else {
    $story_args['author'] = $current_user->ID;
    $user_stories = get_posts($story_args);
}

// Nếu có chọn story_id từ URL, kiểm tra quyền sở hữu
if ($selected_story_id) {
    $target_story = get_post($selected_story_id);
    $can_manage_selected = false;
    if ($target_story) {
        if ($is_admin) {
            $can_manage_selected = true;
        } elseif ((int)$target_story->post_author === (int)$current_user->ID) {
            $can_manage_selected = true;
        }
    }
    if (!$can_manage_selected) {
        $selected_story_id = 0;
        $unauthorized_story_error = true;
    }
}

// Calculate default next chapter number & selected story info
$next_chap_num = 1;
$selected_story_title = '';
$selected_story_permalink = '';
if ($selected_story_id) {
    $existing = muop_get_story_chapters($selected_story_id);
    $next_chap_num = count($existing) + 1;
    $selected_story_obj = get_post($selected_story_id);
    if ($selected_story_obj) {
        $selected_story_title = $selected_story_obj->post_title;
        $base_perm = get_permalink($selected_story_obj->ID);
        if ($from === 'translator') {
            $selected_story_permalink = add_query_arg('from', 'translator', $base_perm);
        } elseif ($from === 'admin') {
            $selected_story_permalink = add_query_arg('from', 'admin', $base_perm);
        } else {
            $selected_story_permalink = $base_perm;
        }
    }
}
?>

<div class="container">
    <div class="dashboard-wrapper" style="max-width: 860px; margin: 0 auto;">
        <nav class="breadcrumbs" id="themChuongBreadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>

            <?php if ($from === 'admin') : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>">Quản Trị Hệ Thống</a>
            <?php elseif ($from === 'translator') : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">Kênh Dịch Giả</a>
            <?php endif; ?>
            
            <span id="breadcrumbStoryItem" style="<?php echo empty($selected_story_title) ? 'display: none;' : 'display: inline-flex; align-items: center; gap: 6px;'; ?>">
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a id="breadcrumbStoryLink" href="<?php echo esc_url($selected_story_permalink ?: '#'); ?>" style="color: var(--text-main); font-weight: 500;">
                    <span id="breadcrumbStoryText"><?php echo esc_html($selected_story_title); ?></span>
                </a>
            </span>

            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Thêm Chương Mới</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-file-circle-plus"></i> Thêm Chương Truyện Mới
            </h1>
        </div>

        <?php if (!empty($unauthorized_story_error)) : ?>
            <div class="alert-box alert-error" style="display: block; margin-bottom: 16px;">
                <i class="fa-solid fa-circle-exclamation"></i> Bạn không có quyền thêm chương cho truyện của dịch giả khác! Vui lòng chọn bộ truyện do chính bạn sáng tác/dịch.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['added']) && $_GET['added'] == '1') : ?>
            <div class="alert-box alert-success" style="display: block; margin-bottom: 16px;">
                <i class="fa-solid fa-circle-check"></i> Thêm chương mới thành công! Hệ thống đã tự động giữ bộ truyện và nạp số chương tiếp theo, bạn có thể tiếp tục thêm chương mới ngay dưới đây.
            </div>
        <?php endif; ?>

        <div id="chapterAlert" class="alert-box"></div>

        <form id="formSubmitChapter" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="muop_submit_chapter" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
            <input type="hidden" name="is_admin_portal" value="<?php echo ($is_admin && $from_admin) ? '1' : '0'; ?>" />
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <!-- Chọn Truyện -->
                <div class="form-group">
                    <label class="form-label" for="selectStory">Chọn Bộ Truyện (*)</label>
                    <select id="selectStory" name="story_id" class="form-input" required>
                        <option value="">-- Chọn truyện cần thêm chương --</option>
                        <?php foreach ($user_stories as $st) : 
                            $opt_url = get_permalink($st->ID);
                            if ($from === 'translator') {
                                $opt_url = add_query_arg('from', 'translator', $opt_url);
                            } elseif ($from === 'admin') {
                                $opt_url = add_query_arg('from', 'admin', $opt_url);
                            }
                        ?>
                            <option value="<?php echo esc_attr($st->ID); ?>" data-url="<?php echo esc_url($opt_url); ?>" <?php selected($st->ID, $selected_story_id); ?>>
                                <?php echo esc_html($st->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 140px 1fr; gap: 16px;">
                    <!-- Số chương -->
                    <div class="form-group">
                        <label class="form-label" for="chapNumber">Số Chương (*)</label>
                        <input type="number" id="chapNumber" name="chapter_number" class="form-input" value="<?php echo esc_attr($next_chap_num); ?>" min="1" required />
                    </div>

                    <!-- Tên chương -->
                    <div class="form-group">
                        <label class="form-label" for="chapTitle">Tên Chương (*)</label>
                        <input type="text" id="chapTitle" name="chapter_title" class="form-input" placeholder="Ví dụ: Gặp lại người cũ, Lời tỏ tình bất ngờ..." required />
                    </div>
                </div>

                <!-- Nội dung chương -->
                <div class="form-group" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" for="chapContent" style="margin-bottom: 0;">Nội Dung Chương (*)</label>
                        <button type="button" class="btn btn-outline btn-scan-sensitive btn-sm" data-target="#chapContent" style="padding: 5px 14px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;" title="Rà soát và thay thế các từ ngữ nhạy cảm trong chương">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Duyệt Nội Dung
                        </button>
                    </div>
                    <textarea id="chapContent" name="content" class="form-input form-textarea-chapter" rows="16" style="min-height: 380px; font-size: 14.5px; line-height: 1.65; padding: 12px 14px;" placeholder="Dán hoặc nhập toàn bộ nội dung chương truyện vào đây..." required></textarea>
                    
                    <!-- Thanh hiển thị số từ thời gian thực -->
                    <div class="chapter-word-count-bar" style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; flex-wrap: wrap; gap: 8px;">
                        <span style="font-size: 12.5px; color: var(--text-muted);">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Nhập hoặc dán nội dung để hệ thống tự động tính số từ.
                        </span>
                        <div class="chapter-word-count-badge" style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 5px 14px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; color: var(--text-main); display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <i class="fa-solid fa-pen-nib" style="color: var(--primary-green);"></i>
                            <span>Số từ: <strong id="chapContentWordCount" style="color: var(--primary-green); font-size: 14.5px; font-weight: 700;">0</strong> từ</span>
                            <span style="color: var(--border-color); margin: 0 2px;">|</span>
                            <span style="font-size: 12px; color: var(--text-muted);"><span id="chapContentCharCount">0</span> ký tự</span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="is_draft" id="isChapterDraftInput" value="0" />

            <!-- Buttons Thêm, Lưu Nháp & Hủy -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex-wrap: wrap;">
                <?php
                if ($from === 'admin') {
                    $cancel_url = home_url('/quan-ly-admin/');
                } elseif ($from === 'story' && !empty($selected_story_permalink)) {
                    $cancel_url = $selected_story_permalink;
                } else {
                    $cancel_url = home_url('/thong-tin-dich-gia/');
                }
                ?>
                <a href="<?php echo esc_url($cancel_url); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Hủy
                </a>
                <button type="button" id="btnSaveDraftNewChapter" class="btn btn-outline" style="padding: 10px 22px; font-size: 14.5px;" title="Lưu nháp chương này để đăng sau">
                    <i class="fa-solid fa-file-pen"></i> Lưu Nháp
                </button>
                <button type="submit" id="btnSubmitPublishChapter" class="btn btn-primary" style="padding: 10px 28px; font-size: 15px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Thêm Chương Này
                </button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
