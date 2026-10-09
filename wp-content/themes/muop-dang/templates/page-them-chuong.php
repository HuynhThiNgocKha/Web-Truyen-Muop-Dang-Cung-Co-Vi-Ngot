<?php
/**
 * Template Name: Trang Thêm Chương Mới
 * Dành cho Dịch Giả & Quản Trị Viên
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || (!current_user_can('dich_gia') && !current_user_can('administrator'))) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$selected_story_id = isset($_GET['story_id']) ? intval($_GET['story_id']) : 0;

// Query stories belonging to this translator or all if admin
$story_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => array('publish', 'pending', 'draft')
);
if (!current_user_can('administrator')) {
    $story_args['author'] = $current_user->ID;
}
$user_stories = get_posts($story_args);

// Calculate default next chapter number
$next_chap_num = 1;
if ($selected_story_id) {
    $existing = muop_get_story_chapters($selected_story_id);
    $next_chap_num = count($existing) + 1;
}
?>

<div class="container">
    <div class="dashboard-wrapper" style="max-width: 860px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">Kênh Dịch Giả</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Thêm Chương Mới</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-file-circle-plus"></i> Thêm Chương Truyện Mới
            </h1>
        </div>

        <div id="chapterAlert" class="alert-box"></div>

        <form id="formSubmitChapter">
            <input type="hidden" name="action" value="muop_submit_chapter" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <!-- Chọn Truyện -->
                <div class="form-group">
                    <label class="form-label" for="selectStory">Chọn Bộ Truyện (*)</label>
                    <select id="selectStory" name="story_id" class="form-input" required>
                        <option value="">-- Chọn truyện cần thêm chương --</option>
                        <?php foreach ($user_stories as $st) : ?>
                            <option value="<?php echo esc_attr($st->ID); ?>" <?php selected($st->ID, $selected_story_id); ?>>
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
                    <label class="form-label" for="chapContent">Nội Dung Chương (*)</label>
                    <textarea id="chapContent" name="content" class="form-input" rows="16" placeholder="Dán hoặc nhập toàn bộ nội dung chương truyện vào đây..." required></textarea>
                </div>
            </div>

            <!-- Buttons Thêm & Hủy -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 28px; font-size: 15px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Thêm Chương Này
                </button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
