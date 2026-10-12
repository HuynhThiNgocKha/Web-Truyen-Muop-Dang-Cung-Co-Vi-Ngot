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

<!-- POPUP SHOPEE GATEKEEPER MODAL TOÀN TRANG -->
<?php $global_shopee_url = get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp'); ?>
<div id="shopeeGatekeeperModal" class="shopee-gatekeeper-overlay" style="display: none;" aria-hidden="true">
    <div class="shopee-modal-card">
        <button type="button" class="btn-close-shopee-modal" id="btnCloseShopeeModal" title="Đóng" aria-label="Đóng">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="shopee-modal-icon">
            <i class="fa-solid fa-gift"></i>
        </div>
        <h3 class="shopee-modal-title">Ủng Hộ Mướp Đắng Nhé! 🌱</h3>
        
        <div class="shopee-modal-msg">
            <p style="margin-bottom: 8px;"><strong>Để giúp Mướp có thể duy trì web lâu dài mọi người click vào link này nha.</strong></p>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Bạn cần nhấn mở link Shopee dưới đây để hệ thống tự động mở khóa chương truyện này cho bạn đọc tiếp nhé!</p>
        </div>

        <div class="shopee-modal-btn-wrap">
            <a href="<?php echo esc_url($global_shopee_url); ?>" id="btnShopeeUnlock" class="btn btn-shopee" target="_blank" rel="noopener noreferrer">
                <i class="fa-solid fa-bag-shopping"></i> Nhấn Vào Đây Để Mở Khóa Đọc Tiếp
            </a>
        </div>

        <p class="shopee-modal-note">
            <i class="fa-solid fa-circle-check" style="color: var(--primary-green);"></i> Chỉ cần 1 lượt click ủng hộ, chương truyện sẽ được mở khóa ngay lập tức!
        </p>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
