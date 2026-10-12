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
            <input type="hidden" name="action" value="muop_submit_story" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
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
                        <label class="form-label" for="storyStatus">Trạng Thái</label>
                        <select id="storyStatus" name="status" class="form-input">
                            <option value="dang_ra">Đang ra (Tiếp tục dịch)</option>
                            <option value="hoan_thanh">Hoàn thành (Đã Full)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="storyCover">Ảnh Bìa</label>
                        <input type="file" id="storyCover" name="cover_image" accept="image/*" style="display: none;" />
                        <div class="custom-cover-upload-area" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <button type="button" id="btnUploadCover" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px; border-radius: var(--radius-sm); display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị
                            </button>
                            <div id="coverUploadInfo" style="display: none; align-items: center; gap: 8px; font-size: 13px; background: var(--bg-card); padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                                <span id="coverFileName" style="font-weight: 600; color: var(--text-main); max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                                <span class="badge badge-green" style="font-size: 11px;">Đã tải lên</span>
                                <button type="button" id="btnRemoveCover" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 2px 4px; font-size: 14px; margin-left: 2px;" title="Xóa ảnh này">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label class="form-label" style="margin-bottom: 0;">Chọn Thể Loại (Có thể chọn nhiều)</label>
                        <button type="button" id="btnToggleAddNewGenre" class="btn btn-secondary" style="padding: 5px 12px; font-size: 12.5px; border-radius: 6px;">
                            <i class="fa-solid fa-plus"></i> Thêm Thể Loại Mới
                        </button>
                    </div>

                    <!-- Hộp thêm thể loại mới trực tiếp (chỉ xuất hiện phần nhập, ẩn khi không cần nữa chỉ hiện button) -->
                    <div id="boxAddNewGenre" style="display: none; margin-bottom: 12px; padding: 10px 14px; background: var(--bg-card); border: 1.5px solid var(--primary-green); border-radius: var(--radius-sm); box-shadow: var(--shadow-sm);">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="inputNewGenreName" class="form-input" placeholder="Nhập tên thể loại mới (vd: Trinh thám, Kinh dị...)" style="flex: 1; padding: 6px 12px; font-size: 13px; height: 34px;" />
                            <button type="button" id="btnConfirmAddGenre" class="btn btn-primary" style="padding: 6px 14px; font-size: 12.5px; height: 34px; white-space: nowrap;">
                                <i class="fa-solid fa-check"></i> Lưu Thể Loại
                            </button>
                            <button type="button" id="btnCancelAddGenre" class="btn btn-outline" style="padding: 6px 10px; font-size: 12.5px; height: 34px;" title="Hủy / Đóng">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div id="addGenreStatus" style="font-size: 12px; margin-top: 4px; display: none;"></div>
                    </div>

                    <!-- Ô tìm kiếm thể loại -->
                    <div style="margin-bottom: 10px; position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12.5px;"></i>
                        <input type="text" id="inputSearchGenre" class="form-input" placeholder="Nhập tìm thể loại..." style="padding-left: 32px; padding-top: 6px; padding-bottom: 6px; font-size: 13px; height: 34px; border-radius: var(--radius-sm);" />
                    </div>

                    <div id="genresCheckboxList" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; max-height: 180px; overflow-y: auto; background: var(--bg-card); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                        <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                            <?php foreach ($all_genres as $genre) : ?>
                                <label class="genre-checkbox-item" data-name="<?php echo esc_attr(mb_strtolower($genre->name, 'UTF-8')); ?>" style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="checkbox" name="categories[]" value="<?php echo esc_attr($genre->term_id); ?>" />
                                    <span class="genre-checkbox-name"><?php echo esc_html($genre->name); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div id="noGenreFoundMsg" style="display: none; font-size: 12.5px; color: var(--text-muted); padding: 8px 12px; text-align: center; background: var(--bg-card); border-radius: var(--radius-sm); margin-top: 4px;">
                        Không tìm thấy thể loại phù hợp.
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
