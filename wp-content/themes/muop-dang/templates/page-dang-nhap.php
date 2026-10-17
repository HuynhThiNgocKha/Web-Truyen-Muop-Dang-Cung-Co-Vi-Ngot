<?php
/**
 * Template Name: Trang Đăng Nhập
 */
if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    $redirect_url = !empty($_GET['redirect_to']) ? esc_url_raw($_GET['redirect_to']) : home_url('/ho-so/');
    wp_redirect($redirect_url);
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
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . MUOP_THEME_VERSION); ?>" alt="<?php bloginfo('name'); ?>" style="height: 34px !important; width: auto !important; max-width: 165px !important; object-fit: contain !important; display: block !important;" />
                </div>
            </a>
            <h2>Đăng Nhập Tài Khoản</h2>
            <p>Chào mừng bạn trở lại với Mướp Đắng Cũng Có Vị Ngọt</p>
        </div>

        <div id="loginAlert" class="alert-box"></div>

        <form id="formMuopLogin">
            <?php if (!empty($_GET['redirect_to'])) : ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($_GET['redirect_to']); ?>" />
            <?php endif; ?>
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
