<?php
/**
 * Template Name: Trang Đăng Truyện
 * Dành cho Dịch Giả & Quản Trị Viên
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || (!current_user_can('dich_gia') && !current_user_can('administrator'))) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$all_genres = get_terms(array('taxonomy' => 'the_loai', 'hide_empty' => false));
?>

<div class="container">
    <div class="dashboard-wrapper" style="max-width: 860px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">Kênh Dịch Giả</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Đăng Truyện Mới</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-feather-pointed"></i> Đăng Truyện Mới Vào Hệ Thống
            </h1>
        </div>

        <div id="storyAlert" class="alert-box"></div>

        <form id="formSubmitStory" enctype="multipart/form-data">
            <!-- CENTER 1: THÔNG TIN TRUYỆN -->
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                    1. Thông Tin Cơ Bản Của Truyện
                </h3>

                <div class="form-group">
                    <label class="form-label" for="storyTitle">Tên Truyện (*)</label>
                    <input type="text" id="storyTitle" name="title" class="form-input" placeholder="Ví dụ: Vị Ngọt Mướp Đắng, Sau Khi Chia Tay Em Trở Thành Nữ Thần..." required />
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="storyAuthor">Tác Giả</label>
                        <input type="text" id="storyAuthor" name="author_name" class="form-input" placeholder="Tên tác giả gốc (nếu có)" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="storyTeam">Team (Dịch Giả)</label>
                        <input type="text" id="storyTeam" name="team_name" class="form-input" value="<?php echo esc_attr($current_user->display_name); ?>" placeholder="Tên team dịch" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="storyStatus">Trạng Thái Truyện</label>
                        <select id="storyStatus" name="status" class="form-input">
                            <option value="dang_ra">Đang ra (Tiếp tục dịch)</option>
                            <option value="hoan_thanh">Hoàn thành (Đã Full)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="storyCover">Ảnh Bìa Truyện (Tải lên từ máy tính)</label>
                        <input type="file" id="storyCover" name="cover_image" class="form-input" accept="image/*" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Chọn Thể Loại (Có thể chọn nhiều)</label>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; max-height: 140px; overflow-y: auto; background: var(--bg-card); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                        <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                            <?php foreach ($all_genres as $genre) : ?>
                                <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="checkbox" name="categories[]" value="<?php echo esc_attr($genre->term_id); ?>" />
                                    <?php echo esc_html($genre->name); ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CENTER 2: GIỚI THIỆU (MÔ TẢ) -->
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                    2. Giới Thiệu / Văn Án Truyện
                </h3>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="storyDescription">Nội Dung Giới Thiệu / Văn Án (*)</label>
                    <textarea id="storyDescription" name="description" class="form-input" rows="8" placeholder="Nhập phần tóm tắt văn án hấp dẫn để thu hút độc giả..." required></textarea>
                </div>
            </div>

            <!-- CENTER 3: BUTTONS THÊM & HỦY -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 28px; font-size: 15px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Đăng Truyện
                </button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
