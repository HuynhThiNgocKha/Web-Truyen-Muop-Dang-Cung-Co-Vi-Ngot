<?php
/**
 * Template Name: Trang Cài Đặt Đọc Truyện
 * Trang riêng biệt cho phép độc giả tùy biến font chữ, cỡ chữ, khoảng cách đoạn và màu sắc khi đọc truyện
 */
if (!defined('ABSPATH')) exit;

get_header();

$is_logged_in = is_user_logged_in();
$current_user = $is_logged_in ? wp_get_current_user() : null;
$user_id      = $is_logged_in ? $current_user->ID : 0;
$registered   = $is_logged_in ? date('d/m/Y', strtotime($current_user->user_registered)) : '';
$avatar_char  = $is_logged_in ? strtoupper(substr($current_user->display_name, 0, 1)) : '';
$custom_avatar = $is_logged_in ? get_user_meta($user_id, 'muop_user_avatar', true) : '';
$custom_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($custom_avatar) : $custom_avatar;
?>

<div class="container" style="padding-top: 20px; padding-bottom: 40px;">
    <div class="dashboard-wrapper">
        <!-- BREADCRUMBS -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Cài Đặt Đọc Truyện</span>
        </nav>

        <?php if ($is_logged_in) : ?>
            <!-- PROFILE HEADER CARD (DÀNH CHO NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP) -->
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
                                elseif (in_array('dich_gia', (array)$current_user->roles)) echo '<i class="fa-solid fa-feather-pointed"></i> Dịch Giả';
                                else echo '<i class="fa-solid fa-book-open-reader"></i> Độc Giả';
                            ?>
                        </span>
                    </div>
                    <p class="profile-meta-item">
                        <i class="fa-solid fa-envelope"></i> <span><?php echo esc_html($current_user->user_email); ?></span>
                    </p>
                    <p class="profile-meta-item">
                        <i class="fa-solid fa-calendar-check"></i> Ngày tham gia: <strong><?php echo esc_html($registered); ?></strong>
                    </p>
                </div>

                <div class="profile-header-actions">
                    <?php if (current_user_can('administrator')) : ?>
                        <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>" class="btn btn-warning">
                            <i class="fa-solid fa-sliders"></i> Quản Lý Hệ Thống
                        </a>
                    <?php elseif (in_array('dich_gia', (array)$current_user->roles)) : ?>
                        <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-feather-pointed"></i> Kênh Dịch Giả
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- MAIN SETTINGS CONTAINER -->
        <div id="tabReadingSettings" class="profile-form-card" style="margin-top: 24px;">
            <div class="form-section-header">
                <h3><i class="fa-solid fa-sliders"></i> Cài Đặt Giao Diện Đọc Truyện</h3>
                <p>Tùy biến cỡ chữ, kiểu phông, khoảng cách đoạn và màu sắc đọc truyện ưa thích. Cài đặt được lưu tự động và áp dụng cho mọi bộ truyện bạn đọc trên Mướp Đắng.</p>
            </div>

            <div id="profileReadingSettingsAlert"></div>

            <!-- KHUNG XEM TRƯỚC THỜI GIAN THỰC (LIVE PREVIEW) -->
            <div class="reading-preview-wrapper" id="profileReadingPreviewWrapper">
                <div class="preview-header-bar">
                    <span class="preview-badge-tag"><i class="fa-solid fa-eye"></i> Xem Trước Trực Quan</span>
                    <div class="preview-theme-toggles">
                        <button type="button" class="btn-preview-theme active" data-preview-theme="light" title="Xem trên nền sáng"><i class="fa-regular fa-sun"></i> Sáng</button>
                        <button type="button" class="btn-preview-theme" data-preview-theme="dark" title="Xem trên nền tối"><i class="fa-solid fa-moon"></i> Tối</button>
                    </div>
                </div>
                <div class="preview-content-box" id="previewContentBox">
                    <h4 class="preview-title" id="previewTitle">Chương 1: Khởi Đầu Mới</h4>
                    <div class="preview-body-text" id="previewBodyText">
                        <p>Mỗi câu chuyện mở ra một thế giới đầy màu sắc và cảm xúc. Khung xem trước này giúp bạn kiểm tra độ tương phản, kiểu chữ và kích thước hiển thị phù hợp nhất với thị giác của mình.</p>
                        <p>Chúc bạn có những phút giây đọc truyện thật thư thái và trọn vẹn tại Mướp Đắng!</p>
                    </div>
                </div>
            </div>

            <!-- BỘ ĐIỀU KHIỂN CÀI ĐẶT -->
            <div class="profile-settings-controls">
                <!-- 1. Cỡ chữ -->
                <div class="form-setting-group">
                    <label class="form-setting-title"><i class="fa-solid fa-text-height"></i> Cỡ chữ nội dung:</label>
                    <div class="size-adjuster-wrap">
                        <button type="button" class="font-size-btn-sm" id="btnProfileFontDec" title="Giảm cỡ chữ">A-</button>
                        <span class="size-val-display" id="profileSizeDisplay">21px</span>
                        <button type="button" class="font-size-btn-sm" id="btnProfileFontInc" title="Tăng cỡ chữ">A+</button>
                    </div>
                </div>

                <!-- 2. Khoảng cách các đoạn -->
                <div class="form-setting-group">
                    <label class="form-setting-title"><i class="fa-solid fa-arrows-up-down"></i> Khoảng cách các đoạn:</label>
                    <div class="setting-spacing-chips" id="profileSpacingChips">
                        <button type="button" class="spacing-chip-btn" data-spacing="8">Gọn (8px)</button>
                        <button type="button" class="spacing-chip-btn active" data-spacing="14">Vừa (14px)</button>
                        <button type="button" class="spacing-chip-btn" data-spacing="20">Thoáng (20px)</button>
                        <button type="button" class="spacing-chip-btn" data-spacing="26">Rộng (26px)</button>
                    </div>
                    <div class="size-adjuster-wrap" style="margin-top: 8px;">
                        <button type="button" class="font-size-btn-sm" id="btnProfileSpacingDec" title="Giảm khoảng cách đoạn"><i class="fa-solid fa-minus"></i></button>
                        <span class="size-val-display" id="profileSpacingDisplay">14px</span>
                        <button type="button" class="font-size-btn-sm" id="btnProfileSpacingInc" title="Tăng khoảng cách đoạn"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>

                <!-- 3. Kiểu chữ (Font) -->
                <div class="form-setting-group">
                    <label class="form-setting-title"><i class="fa-solid fa-font"></i> Kiểu phông chữ (Font):</label>
                    <div class="setting-font-chips" id="profileFontChips">
                        <button type="button" class="font-chip-btn active" data-font="sans-serif">Sans-serif (Mặc định)</button>
                        <button type="button" class="font-chip-btn" data-font="merriweather">Merriweather (Sách báo)</button>
                        <button type="button" class="font-chip-btn" data-font="be-vietnam">Be Vietnam Pro</button>
                        <button type="button" class="font-chip-btn" data-font="georgia">Georgia (Cổ điển)</button>
                        <button type="button" class="font-chip-btn" data-font="times">Times New Roman</button>
                    </div>
                </div>

                <!-- 4. Màu chữ đọc truyện (5 màu đề xuất + màu tùy chọn) -->
                <div class="form-setting-group">
                    <div class="setting-title-split">
                        <label class="form-setting-title"><i class="fa-solid fa-palette"></i> Màu chữ đọc truyện:</label>
                        <span class="setting-hint-text" id="profileColorModeHint">5 màu đề xuất phổ biến:</span>
                    </div>
                    <div class="setting-colors-palette" id="profileColorsPalette"></div>

                    <div class="custom-color-row" style="margin-top: 10px;">
                        <label for="inputProfileCustomColor" class="custom-color-label">
                            <i class="fa-solid fa-eye-dropper"></i> Tự chọn màu tùy thích:
                        </label>
                        <div class="custom-color-control">
                            <input type="color" id="inputProfileCustomColor" class="custom-color-input" value="#262626" />
                            <span class="custom-color-hex" id="profileCustomColorHex">#262626</span>
                        </div>
                    </div>
                </div>

                <!-- 5. Hàng nút Lưu & Khôi phục -->
                <div class="settings-submit-row" style="margin-top: 24px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary" id="btnSaveProfileReadingSettings">
                        <i class="fa-solid fa-check"></i> Lưu Cài Đặt Đọc Truyện
                    </button>
                    <button type="button" class="btn btn-secondary" id="btnResetProfileReadingSettings">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Khôi Phục Mặc Định
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
