<?php
/**
 * Template Name: Trang Đăng Ký
 */
if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    wp_redirect(home_url('/ho-so/'));
    exit;
}

get_header();
?>

<div class="container">
    <div class="auth-page-wrapper">
        <div class="auth-header">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-brand-static" title="<?php bloginfo('name'); ?>" style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; justify-content: center !important; gap: 10px !important; text-decoration: none !important; margin: 0 auto 16px !important;">
                <div class="auth-brand-logo" style="width: 44px !important; height: 44px !important; min-width: 44px !important; border-radius: 50% !important; padding: 2px !important; background: #ffffff !important; border: 2px solid var(--primary-green, #3F7523) !important; box-shadow: 0 2px 8px rgba(63, 117, 35, 0.22) !important; display: flex !important; align-items: center !important; justify-content: center !important; overflow: hidden !important; flex-shrink: 0 !important; box-sizing: border-box !important;">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>" alt="Logo Mướp Đắng" style="width: 100% !important; height: 100% !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;" />
                </div>
                <div class="auth-brand-text" style="display: flex !important; align-items: center !important; flex-shrink: 0 !important;">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" alt="<?php bloginfo('name'); ?>" style="height: 34px !important; width: auto !important; max-width: 165px !important; object-fit: contain !important; display: block !important;" />
                </div>
            </a>
            <h2>Đăng Ký Tài Khoản Độc Giả</h2>
            <p>Tham gia cộng đồng đọc truyện tại Mướp Đắng</p>
        </div>

        <div id="registerAlert" class="alert-box"></div>

        <form id="formMuopRegister">
            <div class="form-group">
                <label class="form-label" for="regDisplayName">
                    <i class="fa-solid fa-user" style="color: var(--primary-green);"></i> Tên Hiển Thị
                </label>
                <input type="text" id="regDisplayName" name="display_name" class="form-input" placeholder="Ví dụ: Tiểu Mướp, Bơ Ngọt..." required />
            </div>

            <div class="form-group">
                <label class="form-label" for="regEmail">
                    <i class="fa-solid fa-envelope" style="color: var(--primary-green);"></i> Địa Chỉ Email
                </label>
                <input type="email" id="regEmail" name="email" class="form-input" placeholder="nhapemail@example.com" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="regPassword">
                    <i class="fa-solid fa-lock" style="color: var(--primary-green);"></i> Mật Khẩu
                </label>
                <div class="input-with-icon">
                    <input type="password" id="regPassword" name="password" class="form-input" placeholder="Ít nhất 6 ký tự" required />
                    <button type="button" class="input-icon-btn toggle-password-visibility" data-target="regPassword" title="Hiện/ẩn mật khẩu">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="regConfirmPassword">
                    <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Nhập Lại Mật Khẩu
                </label>
                <div class="input-with-icon">
                    <input type="password" id="regConfirmPassword" name="confirm_password" class="form-input" placeholder="Nhập lại mật khẩu vừa nhập" required />
                    <button type="button" class="input-icon-btn toggle-password-visibility" data-target="regConfirmPassword" title="Hiện/ẩn mật khẩu">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px; margin-top: 6px;">
                <i class="fa-solid fa-user-plus"></i> Đăng Ký Tài Khoản
            </button>
        </form>

        <div class="auth-footer-links">
            <span>Bạn đã có tài khoản?</span> 
            <a href="<?php echo esc_url(home_url('/dang-nhap/')); ?>">Đăng nhập</a>
        </div>
    </div>
</div>

<?php
get_footer();
