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

<!-- POPUP MODAL THÔNG BÁO TOÀN TRANG (CĂN GIỮA MÀN HÌNH CÓ NỀN MỜ) -->
<?php if (is_user_logged_in()) : 
    $notif_unread_count_global = muop_get_unread_notification_count(get_current_user_id());
?>
<div class="modal-backdrop notif-modal-backdrop" id="notifModalBackdrop" style="display: none;" aria-hidden="true">
    <div class="modal-content notif-modal-card" id="notifDropdownPanel">
        <div class="notif-header">
            <div class="notif-header-title">
                <i class="fa-solid fa-bell"></i> <span>Thông Báo</span>
                <span class="notif-unread-count-pill" id="notifUnreadCountPill"><?php echo $notif_unread_count_global; ?> chưa đọc</span>
            </div>
            <div class="notif-header-actions" style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="notif-mark-all-btn" id="notifMarkAllReadBtn" title="Đánh dấu tất cả đã đọc">
                    <i class="fa-solid fa-check-double"></i> Đã đọc tất cả
                </button>
                <button type="button" class="btn-close-modal" id="btnCloseNotifModal" aria-label="Đóng" title="Đóng thông báo" style="background: none; border: none; outline: none; box-shadow: none;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <div class="notif-body" id="notifListContainer">
            <div class="notif-loading"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải thông báo...</div>
        </div>
    </div>
</div>
<?php endif; ?>

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

<!-- MODAL CHI TIẾT & ĐỐI THOẠI BÁO LỖI CHƯƠNG (HỖ TRỢ ĐỘC GIẢ, DỊCH GIẢ, ADMIN) -->
<div class="modal-backdrop" id="reportDialogueModal" style="display: none;" aria-hidden="true">
    <div class="modal-content report-dialogue-modal-content" style="max-width: 620px; width: 95%; max-height: 90vh; display: flex; flex-direction: column; padding: 0; border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.3);">
        <!-- Modal Header -->
        <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; background: var(--pastel-green); border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626;"></i> Chi Tiết Báo Lỗi Chương
                </h3>
                <span id="repDlgStatusBadge" class="rep-status-badge badge-pending">Chờ xử lý</span>
            </div>
            <button type="button" class="btn-close-modal" id="btnCloseReportDialogueModal" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted); line-height: 1;" aria-label="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Body (Cuộn nội dung) -->
        <div class="modal-body" style="padding: 16px 20px; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 16px;">
            <!-- Loading Spinner -->
            <div id="repDlgLoading" style="text-align: center; padding: 30px; color: var(--text-muted); font-size: 14px;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: var(--primary-green); display: block; margin-bottom: 10px;"></i>
                Đang tải thông tin báo lỗi chương...
            </div>

            <!-- Main Content Container -->
            <div id="repDlgMainContent" style="display: none; display: flex; flex-direction: column; gap: 16px;">
                <!-- Thẻ tóm tắt thông tin báo lỗi ban đầu -->
                <div class="rep-initial-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; box-shadow: var(--shadow-sm);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px dashed var(--border-light);">
                        <div>
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-bottom: 3px;">
                                <i class="fa-solid fa-book-bookmark" style="color: var(--primary-green);"></i>
                                <a id="repDlgStoryLink" href="#" target="_blank" style="color: var(--primary-green); text-decoration: none;">---</a>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                <span id="repDlgChapterNum" style="font-weight: 600; color: var(--text-main);">Chương ---</span>
                                <span id="repDlgChapterTitle" style="margin-left: 4px;">---</span>
                            </div>
                        </div>
                        <div id="repDlgCreatedAt" style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 5px;">
                            <i class="fa-regular fa-clock"></i> ---
                        </div>
                    </div>

                    <!-- Người báo lỗi & Loại lỗi -->
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img id="repDlgSenderAvatar" src="" alt="Avatar" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);" />
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <strong id="repDlgSenderName" style="font-size: 13.5px; color: var(--text-main);">---</strong>
                                    <span id="repDlgSenderRole" class="rep-role-tag role-doc-gia">Độc giả</span>
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted);">Người báo lỗi</div>
                            </div>
                        </div>
                        <div>
                            <span class="rep-issue-tag" id="repDlgIssueType">
                                <i class="fa-solid fa-triangle-exclamation"></i> ---
                            </span>
                        </div>
                    </div>

                    <!-- Mô tả chi tiết -->
                    <div style="background: var(--soft-green); padding: 12px; border-radius: var(--radius-sm); border-left: 3px solid #dc2626; font-size: 13px; line-height: 1.6;">
                        <div style="font-weight: 600; color: #dc2626; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-circle-info"></i> Mô tả lỗi chi tiết:
                        </div>
                        <div id="repDlgDescription" style="color: var(--text-main); word-break: break-word;">---</div>
                    </div>
                </div>

                <!-- Danh sách chuỗi đối thoại / Phản hồi qua lại -->
                <div class="rep-thread-container" style="display: flex; flex-direction: column; gap: 10px;">
                    <div style="font-size: 13.5px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="fa-regular fa-comments" style="color: var(--primary-green);"></i> Lịch sử trao đổi phản hồi (<span id="repDlgReplyCount">0</span>)</span>
                    </div>

                    <div id="repDlgMessagesList" style="display: flex; flex-direction: column; gap: 12px; max-height: 260px; overflow-y: auto; padding: 4px 2px;">
                        <!-- Các tin nhắn đối thoại được đổ bằng JavaScript -->
                    </div>
                </div>

                <!-- Form gửi phản hồi đối thoại -->
                <form id="formSubmitReportDialogueReply" style="margin-top: 4px; border-top: 1px solid var(--border-light); padding-top: 14px;">
                    <input type="hidden" name="report_id" id="repDlgHiddenReportId" value="" />

                    <!-- Ô chọn đổi trạng thái (Chỉ hiện cho Admin hoặc Dịch giả của truyện) -->
                    <div id="repDlgStatusControl" style="display: none; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <label for="repDlgStatusSelect" style="font-size: 12.5px; font-weight: 600; color: var(--text-main); white-space: nowrap;">
                            <i class="fa-solid fa-arrows-rotate"></i> Cập nhật trạng thái:
                        </label>
                        <select name="status" id="repDlgStatusSelect" class="form-input" style="padding: 5px 10px; font-size: 12.5px; border-radius: 6px; border: 1px solid var(--border-color);">
                            <option value="pending">Chờ xử lý</option>
                            <option value="processing">Đang xử lý / Đang sửa</option>
                            <option value="resolved">Đã giải quyết / Đã sửa xong</option>
                            <option value="rejected">Không phải lỗi / Đóng</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <textarea name="reply_message" id="repDlgReplyTextarea" class="form-input" rows="3" style="width: 100%; font-size: 13px; line-height: 1.5; padding: 10px; resize: vertical; box-sizing: border-box; border-radius: var(--radius-sm);" placeholder="Nhập nội dung phản hồi trao đổi..." required></textarea>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                        <span id="repDlgUserNotice" style="font-size: 12px; color: var(--text-muted); font-style: italic;">
                            <i class="fa-solid fa-lock"></i> Mọi phản hồi sẽ được gửi thông báo ngay tới các bên liên quan.
                        </span>
                        <div style="display: flex; gap: 10px;">
                            <button type="button" class="btn-report-cancel-pill" id="btnCancelReportDialogueModal">
                                Đóng
                            </button>
                            <button type="submit" class="btn-report-submit-pill" id="btnSubmitReportDialogueReply">
                                <i class="fa-solid fa-paper-plane"></i> <span id="repDlgBtnSubmitText">Gửi Phản Hồi</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
