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
            <div class="brand-logo" style="width: 50px; height: 50px; font-size: 24px; margin: 0 auto 14px;">
                <i class="fa-solid fa-leaf"></i>
            </div>
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
