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

<!-- Modal Xem Ảnh Bill Chuyển Khoản Nhuận Bút -->
<div class="modal-backdrop" id="billPreviewModal" style="display: none;" aria-hidden="true">
    <div class="modal-content" style="max-width: 520px; padding: 20px; text-align: center; border-radius: var(--radius-lg);">
        <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin: 0;">
                <i class="fa-solid fa-receipt"></i> Bằng Chứng Thanh Toán / Bill Chuyển Khoản
            </h3>
            <button type="button" class="btn-close-modal" id="btnCloseBillModal" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div style="margin: 15px 0;">
            <img id="billModalImg" src="" alt="Bill chuyển khoản" style="max-width: 100%; max-height: 70vh; border-radius: var(--radius-md); box-shadow: var(--shadow-md); object-fit: contain;" />
        </div>
        <div style="display: flex; gap: 10px; justify-content: center; margin-top: 15px;">
            <a id="btnDownloadBill" href="" target="_blank" class="btn btn-primary" style="padding: 7px 16px; font-size: 13px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Mở Ảnh Gốc
            </a>
            <button type="button" class="btn btn-secondary" id="btnDismissBillModal" style="padding: 7px 16px; font-size: 13px;">
                Đóng
            </button>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
