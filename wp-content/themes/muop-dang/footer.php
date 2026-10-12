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
                <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-wrapper footer-brand-wrapper" title="<?php bloginfo('name'); ?>">
                    <div class="brand-text">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . MUOP_THEME_VERSION); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo-img" />
                    </div>
                </a>
                <p class="footer-slogan">
                    Mướp Đắng Cũng Có Vị Ngọt – nơi tâm hồn bạn được thả lỏng cùng với những câu truyện mà Mướp mang đến cho bạn.
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Copyright © 2026 Mướp Đắng Cũng Có Vị Ngọt. All right reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
