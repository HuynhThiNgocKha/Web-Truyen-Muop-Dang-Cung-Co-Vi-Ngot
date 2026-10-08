<?php
/**
 * Footer Template for Mướp Đắng Cũng Có Vị Ngọt
 */
if (!defined('ABSPATH')) exit;
?>
</main><!-- .site-main -->

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-wrapper" style="margin-bottom: 8px;" title="<?php bloginfo('name'); ?>">
                    <div class="brand-logo brand-avatar" style="width: 40px; height: 40px;">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png'); ?>" alt="Logo Mướp Đắng" class="brand-icon-img" />
                    </div>
                    <div class="brand-text">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png'); ?>" alt="<?php bloginfo('name'); ?>" class="brand-title-img" style="height: 38px;" />
                    </div>
                </a>
                <p class="footer-slogan">
                    Mướp Đắng Cũng Có Vị Ngọt – nơi tâm hồn bạn được thả lỏng cùng với những câu truyện mà Mướp mang đến cho bạn.
                </p>
            </div>

            <div class="footer-links-col" style="display: flex; gap: 24px; font-size: 13.5px; font-weight: 600;">
                <a href="<?php echo esc_url(home_url('/dieu-khoan/')); ?>">Điều khoản</a>
                <a href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>">Giới thiệu</a>
                <a href="<?php echo esc_url(home_url('/chinh-sach-bao-mat/')); ?>">Chính sách bảo mật</a>
                <a href="<?php echo esc_url(home_url('/lien-he/')); ?>">Liên hệ</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Copyright © 2026 Mướp Đắng Cũng Có Vị Ngọt. All right reserved.</p>
            <p style="color: var(--text-muted); font-size: 11.5px;">Phát triển trên nền tảng WordPress mã nguồn mở • Tông màu xanh bơ & mướp đắng</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
