<?php
/**
 * Footer Template for Mướp Đắng Cũng Có Vị Ngọt
 */
if (!defined('ABSPATH')) exit;
?>
</main><!-- .site-main -->

<?php if (!is_page_template('templates/page-quan-ly-admin.php')) : ?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-wrapper footer-brand-wrapper" title="<?php bloginfo('name'); ?>">
                    <div class="brand-text">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo-img" />
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

<!-- POPUP SHOPEE GATEKEEPER MODAL TOÀN TRANG (KHÔNG XUẤT HIỆN CHO ADMIN) -->
<?php if (!muop_is_admin()) : ?>
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
<?php endif; ?>

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
<!-- MODAL DUYỆT & RÀ SOÁT TỪ NHẠY CẢM TRONG TRUYỆN -->
<div class="modal-backdrop" id="modalSensitiveWordReview" style="display: none; z-index: 999999999;" aria-hidden="true">
    <div class="modal-content sensitive-review-modal-content" style="max-width: 720px; width: 95%; max-height: 90vh; display: flex; flex-direction: column; padding: 0; border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.35);">
        <!-- Modal Header -->
        <div class="modal-header" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; background: var(--pastel-green); border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Duyệt & Xử Lý Nội Dung
                </h3>
                <span id="sensitiveCountBadge" class="badge badge-hot" style="font-size: 12px; padding: 3px 8px;">0 từ</span>
            </div>
            <button type="button" class="btn-close-modal" id="btnCloseSensitiveReviewModal" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted); line-height: 1;" aria-label="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Controls Bar: Tabs chuyển đổi Xem Vị Trí Đánh Dấu / Xem Danh Sách -->
        <div style="padding: 10px 20px 0; background: var(--bg-card); border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div class="sensitive-modal-tabs" style="display: flex; gap: 8px;">
                <button type="button" class="sensitive-tab-btn active" id="tabBtnSensitivePreview" data-tab="preview">
                    <i class="fa-solid fa-highlighter"></i> 1. Bảng Đánh Dấu Trong Bài (<span id="sensitiveTabPreviewCount">0</span>)
                </button>
                <button type="button" class="sensitive-tab-btn" id="tabBtnSensitiveTable" data-tab="table">
                    <i class="fa-solid fa-list-check"></i> 2. Danh Sách & Tùy Chỉnh Thay Thế (<span id="sensitiveTabTableCount">0</span>)
                </button>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); padding-bottom: 8px;">
                <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Hệ thống đánh dấu trực quan vị trí vi phạm
            </div>
        </div>

        <!-- Controls Bar: Chọn ký tự thay thế tự động (chỉ hiện khi ở Tab Table) -->
        <div id="sensitiveDelimiterBar" style="padding: 10px 20px; background: var(--bg-card); border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="font-size: 13px; font-weight: 600; color: var(--text-main);">
                    <i class="fa-solid fa-wand-magic-sparkles" style="color: var(--primary-green);"></i> Ký tự phân cách:
                </span>
                <div class="sensitive-delimiter-pills" style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <button type="button" class="delimiter-pill active" data-delim=".">Dấu chấm (.) ví dụ: c.h.ế.t</button>
                    <button type="button" class="delimiter-pill" data-delim="*">Dấu sao (*) ví dụ: c*h*ế*t</button>
                    <button type="button" class="delimiter-pill" data-delim="/">Dấu gạch (/) ví dụ: c/h/ế/t</button>
                    <button type="button" class="delimiter-pill" data-delim="-">Dấu gạch (-) ví dụ: c-h-ế-t</button>
                </div>
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
                <i class="fa-solid fa-pencil"></i> Có thể tự chỉnh sửa từng từ ở bảng dưới
            </div>
        </div>

        <!-- Modal Body (Danh sách các từ nhạy cảm phát hiện được) -->
        <div class="modal-body" style="padding: 16px 20px; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 12px;">
            <!-- Thông báo kết quả sau khi duyệt xong -->
            <div id="sensitiveScanResultNotice" style="display: none; background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-sm); padding: 9px 14px; font-size: 13px; color: #991b1b; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626; font-size: 16px;"></i>
                <span><strong>Đã duyệt xong!</strong> Hệ thống phát hiện <strong id="detectedWordsCountText" style="color: #dc2626;">0</strong> từ nhạy cảm. Bạn có thể xem vị trí đánh dấu trong bài hoặc tự động thay thế bằng ký tự phân cách:</span>
            </div>

            <!-- Message if no words found -->
            <div id="sensitiveNoneFound" style="display: none; text-align: center; padding: 36px 20px; color: var(--text-muted);">
                <i class="fa-solid fa-circle-check" style="font-size: 42px; color: var(--primary-green); display: block; margin-bottom: 12px;"></i>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">Nội dung an toàn, không có từ nhạy cảm!</h4>
                <p style="margin: 0; font-size: 13.5px;">Hệ thống không tìm thấy từ ngữ nhạy cảm, thô tục hay bộ phận sinh dục nào trong văn bản này.</p>
            </div>

            <!-- Tab 1: Table list of found words -->
            <div id="sensitiveTableWrapper" style="overflow-x: auto;">
                <table class="sensitive-review-table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color); text-align: left; background: var(--soft-green);">
                            <th style="padding: 10px 12px; width: 40px; text-align: center;">
                                <input type="checkbox" id="checkAllSensitiveWords" checked title="Chọn tất cả" />
                            </th>
                            <th style="padding: 10px 12px;">Từ phát hiện</th>
                            <th style="padding: 10px 12px; width: 80px; text-align: center;">Số lần</th>
                            <th style="padding: 10px 12px; min-width: 200px;">Từ thay thế (bấm vào để tự sửa)</th>
                            <th style="padding: 10px 12px; width: 110px; text-align: center;">Vị trí</th>
                        </tr>
                    </thead>
                    <tbody id="sensitiveWordsListBody">
                        <!-- Đổ tự động bằng JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Tab 1: Visual Highlighted Context Preview (Bảng Nội Dung Đã Đánh Dấu) -->
            <div id="sensitivePreviewWrapper" style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; font-size: 12.5px; color: var(--text-muted);">
                    <span><i class="fa-solid fa-eye" style="color: var(--primary-green);"></i> Các từ nhạy cảm được tô đỏ trực tiếp trong bảng nội dung. Bấm vào từ để cuộn đến:</span>
                    <span style="font-weight: 600; color: #dc2626;"><i class="fa-solid fa-flag"></i> Tô màu đỏ = Từ vi phạm</span>
                </div>
                <!-- Dải chip nhảy nhanh đến từng từ trong bảng nội dung -->
                <div id="modalSensitiveWordChips" style="display: flex; flex-wrap: wrap; gap: 6px; padding: 4px 0;"></div>
                <div id="sensitiveTextHighlightContainer" style="max-height: 420px; overflow-y: auto; background: var(--bg-body); padding: 18px 20px; border: 1.5px solid var(--border-color); border-radius: var(--radius-md); line-height: 1.85; font-size: 14.5px; white-space: pre-wrap; font-family: inherit; color: var(--text-main);">
                    <!-- Render nội dung có thẻ <mark> highlight bằng JS -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer" style="padding: 14px 20px; background: var(--soft-green); border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <span style="font-size: 12.5px; color: var(--text-muted);">
                <i class="fa-solid fa-circle-info"></i> Bấm <strong>"OK - Thay Thế Toàn Bộ"</strong> để cập nhật trực tiếp vào văn bản.
            </span>
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-secondary" id="btnDismissSensitiveModal" style="padding: 8px 18px; font-size: 13.5px;">
                    Hủy
                </button>
                <button type="button" class="btn btn-primary" id="btnApplySensitiveReplacements" style="padding: 8px 22px; font-size: 13.5px;">
                    <i class="fa-solid fa-check"></i> OK - Thay Thế Toàn Bộ
                </button>
            </div>
        </div>
    </div>
</div>

<?php 
// Include Modal Xem & Quản Lý Lịch Hẹn Giờ Đăng Truyện
$schedule_modal_file = get_template_directory() . '/templates/modal-schedule-story.php';
if (file_exists($schedule_modal_file)) {
    include $schedule_modal_file;
}
?>

<?php wp_footer(); ?>
</body>
</html>
