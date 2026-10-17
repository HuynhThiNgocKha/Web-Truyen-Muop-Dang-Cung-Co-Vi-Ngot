<?php
/**
 * Template Name: Trang Đổi Mật Khẩu
 * Trang chuyên biệt cho phép người dùng đổi mật khẩu tài khoản
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap/?redirect_to=' . urlencode(home_url('/doi-mat-khau/'))));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$registered   = date('d/m/Y', strtotime($current_user->user_registered));
$avatar_char  = strtoupper(substr($current_user->display_name, 0, 1));
$custom_avatar = get_user_meta($user_id, 'muop_user_avatar', true);
$custom_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($custom_avatar) : $custom_avatar;
$user_bio     = get_user_meta($user_id, 'description', true);
?>

<div class="container" style="padding-top: 20px; padding-bottom: 40px;">
    <div class="dashboard-wrapper">
        <!-- BREADCRUMBS -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Đổi Mật Khẩu</span>
        </nav>

        <!-- PROFILE HEADER CARD -->
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

        <!-- MAIN FORM: ĐỔI MẬT KHẨU -->
        <div class="profile-form-card" style="max-width: 640px; margin: 24px auto 0;">
            <div id="passwordAlertContainer"></div>

            <div class="form-section-header">
                <h3><i class="fa-solid fa-lock"></i> Đổi Mật Khẩu Tài Khoản</h3>
                <p>Để bảo vệ an toàn cho tài khoản, hãy sử dụng mật khẩu mạnh có tối thiểu 6 ký tự kết hợp chữ và số</p>
            </div>

            <form id="passwordChangeForm">
                <input type="hidden" name="action" value="muop_change_password" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="oldPasswordInput">Mật khẩu hiện tại: <span style="color: #e53935;">*</span></label>
                    <div class="password-input-wrap">
                        <input type="password" id="oldPasswordInput" name="old_password" class="form-input" required placeholder="Nhập mật khẩu hiện tại của bạn" />
                        <button type="button" class="btn-toggle-pwd" data-target="oldPasswordInput" aria-label="Xem mật khẩu">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="newPasswordInput">Mật khẩu mới: <span style="color: #e53935;">*</span></label>
                    <div class="password-input-wrap">
                        <input type="password" id="newPasswordInput" name="new_password" class="form-input" required minlength="6" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" />
                        <button type="button" class="btn-toggle-pwd" data-target="newPasswordInput" aria-label="Xem mật khẩu">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-hint">Mật khẩu phải dài ít nhất 6 ký tự.</small>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" for="confirmPasswordInput">Xác nhận mật khẩu mới: <span style="color: #e53935;">*</span></label>
                    <div class="password-input-wrap">
                        <input type="password" id="confirmPasswordInput" name="confirm_password" class="form-input" required minlength="6" placeholder="Nhập lại mật khẩu mới" />
                        <button type="button" class="btn-toggle-pwd" data-target="confirmPasswordInput" aria-label="Xem mật khẩu">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="submit" class="btn btn-primary" id="btnSubmitPassword" style="padding: 10px 24px; font-size: 14px; font-weight: 700;">
                        <i class="fa-solid fa-key"></i> Cập Nhật Mật Khẩu
                    </button>
                    <span id="passwordSavingSpinner" style="display: none; color: var(--text-muted); font-size: 13px;">
                        <i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
get_footer();
