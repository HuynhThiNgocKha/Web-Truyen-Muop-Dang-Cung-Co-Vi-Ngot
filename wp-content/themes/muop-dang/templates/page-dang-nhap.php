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
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" alt="<?php bloginfo('name'); ?>" style="height: 34px !important; width: auto !important; max-width: 165px !important; object-fit: contain !important; display: block !important;" />
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
                <a href="javascript:void(0)" id="btnOpenForgotPasswordModal" style="color: var(--primary-green); font-weight: 600; text-decoration: none;" title="Khôi phục & đặt lại mật khẩu">Quên mật khẩu?</a>
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

<!-- MODAL QUÊN MẬT KHẨU (XÁC NHẬN TÊN ĐĂNG NHẬP & ĐẶT LẠI MẬT KHẨU MỚI) -->
<div id="modalForgotPassword" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card, #ffffff); width: 100%; max-width: 460px; border-radius: var(--radius-md, 12px); box-shadow: var(--shadow-lg, 0 10px 25px rgba(0,0,0,0.15)); border: 1.5px solid var(--primary-green, #3F7523); overflow: hidden; display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #f0fdf4, var(--bg-card, #ffffff)); border-bottom: 1px solid var(--border-light, #e2e8f0); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #dcfce7; color: var(--primary-green, #3F7523); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-color, #1e293b);">Quên Mật Khẩu</h3>
                    <p style="margin: 2px 0 0; font-size: 12px; color: var(--text-muted, #64748b);">Khôi phục &amp; đặt lại mật khẩu mới cho tài khoản</p>
                </div>
            </div>
            <button type="button" id="btnCloseForgotModal" style="background: none; border: none; font-size: 20px; color: var(--text-muted, #94a3b8); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 22px 20px;">
            <div id="forgotAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <!-- BƯỚC 1: NHẬP TÊN ĐĂNG NHẬP -->
            <form id="formForgotStep1">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="forgotUsername" style="font-weight: 600; font-size: 13.5px; margin-bottom: 6px; display: block;">
                        <i class="fa-solid fa-user" style="color: var(--primary-green, #3F7523);"></i> Tên Đăng Nhập Hoặc Email (*):
                    </label>
                    <input type="text" id="forgotUsername" class="form-input" placeholder="Nhập tên đăng nhập hoặc email tài khoản" required style="width: 100%; height: 42px; font-size: 14px;" />
                    <p style="font-size: 12px; color: var(--text-muted, #64748b); margin: 6px 0 0 0;">
                        <i class="fa-solid fa-circle-info" style="color: var(--primary-green, #3F7523);"></i> Nhập đúng tên đăng nhập tài khoản của bạn để hệ thống cấp quyền đặt lại mật khẩu mới ngay lập tức.
                    </p>
                </div>

                <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary btn-close-forgot-modal" style="padding: 8px 16px; font-size: 13.5px;">Hủy</button>
                    <button type="submit" id="btnCheckForgotUser" class="btn btn-primary" style="padding: 8px 18px; font-size: 13.5px;">
                        <i class="fa-solid fa-arrow-right"></i> Kiểm Tra &amp; Tiếp Tục
                    </button>
                </div>
            </form>

            <!-- BƯỚC 2: NHẬP MẬT KHẨU MỚI (ẨN MẶC ĐỊNH) -->
            <form id="formForgotStep2" style="display: none;">
                <input type="hidden" id="verifiedForgotUsername" value="" />

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-sm, 8px); padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #166534; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-check" style="font-size: 16px; color: #15803d;"></i>
                    <span>Tài khoản: <strong id="displayVerifiedUser" style="color: #15803d;">...</strong>. Hãy đặt mật khẩu mới bên dưới:</span>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" for="forgotNewPassword" style="font-weight: 600; font-size: 13.5px; margin-bottom: 6px; display: block;">
                        <i class="fa-solid fa-lock" style="color: var(--primary-green, #3F7523);"></i> Mật Khẩu Mới (*):
                    </label>
                    <div class="input-with-icon" style="position: relative;">
                        <input type="password" id="forgotNewPassword" class="form-input" placeholder="Tối thiểu 6 ký tự" required minlength="6" style="width: 100%; height: 42px; font-size: 14px;" />
                        <button type="button" class="input-icon-btn toggle-password-visibility" data-target="forgotNewPassword" title="Hiện/ẩn mật khẩu" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted, #94a3b8); padding: 4px;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="forgotConfirmPassword" style="font-weight: 600; font-size: 13.5px; margin-bottom: 6px; display: block;">
                        <i class="fa-solid fa-lock-open" style="color: var(--primary-green, #3F7523);"></i> Xác Nhận Mật Khẩu Mới (*):
                    </label>
                    <div class="input-with-icon" style="position: relative;">
                        <input type="password" id="forgotConfirmPassword" class="form-input" placeholder="Nhập lại mật khẩu mới" required minlength="6" style="width: 100%; height: 42px; font-size: 14px;" />
                        <button type="button" class="input-icon-btn toggle-password-visibility" data-target="forgotConfirmPassword" title="Hiện/ẩn mật khẩu" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted, #94a3b8); padding: 4px;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" id="btnBackToForgotStep1" class="btn btn-outline" style="padding: 8px 14px; font-size: 13.5px;">
                        <i class="fa-solid fa-chevron-left"></i> Quay lại
                    </button>
                    <button type="submit" id="btnSubmitResetPassword" class="btn btn-primary" style="padding: 8px 18px; font-size: 13.5px;">
                        <i class="fa-solid fa-check"></i> Lưu Mật Khẩu Mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
get_footer();
