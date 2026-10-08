<?php
/**
 * Template Name: Trang Đăng Nhập
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
            <h2>Đăng Nhập Tài Khoản</h2>
            <p>Chào mừng bạn trở lại với Mướp Đắng Cũng Có Vị Ngọt</p>
        </div>

        <div id="loginAlert" class="alert-box"></div>

        <form id="formMuopLogin">
            <div class="form-group">
                <label class="form-label" for="loginEmail">
                    <i class="fa-solid fa-envelope" style="color: var(--primary-green);"></i> Email / Tên Đăng Nhập
                </label>
                <input type="text" id="loginEmail" name="log" class="form-input" placeholder="Nhập email hoặc tên tài khoản" required autofocus />
            </div>

            <div class="form-group">
                <label class="form-label" for="loginPassword">
                    <i class="fa-solid fa-lock" style="color: var(--primary-green);"></i> Mật Khẩu
                </label>
                <div class="input-with-icon">
                    <input type="password" id="loginPassword" name="pwd" class="form-input" placeholder="Nhập mật khẩu" required />
                    <button type="button" class="input-icon-btn toggle-password-visibility" data-target="loginPassword" title="Hiện/ẩn mật khẩu">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; font-size: 13px;">
                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--text-muted);">
                    <input type="checkbox" name="remember" value="1" checked /> Ghi nhớ đăng nhập
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px;">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập
            </button>
        </form>

        <div class="auth-footer-links">
            <span>Bạn chưa có tài khoản?</span> 
            <a href="<?php echo esc_url(home_url('/dang-ky/')); ?>">Đăng ký ngay</a>
        </div>
    </div>
</div>

<?php
get_footer();
