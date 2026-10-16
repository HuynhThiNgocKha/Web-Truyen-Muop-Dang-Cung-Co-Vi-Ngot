<?php
/**
 * Admin Popups & Modals Template:
 * 1. Modal Thêm Chương Nhanh (#modalQuickAddChapterToStory)
 * 2. Modal Chi Tiết Truyện Đầy Đủ (#modalStoryFullPreviewDetails - theo phong cách Hình 4)
 * 3. Modal Chỉnh Sửa Người Dùng (#modalEditUserAdmin)
 * 4. Modal Xác Nhận Khóa / Mở Khóa Tài Khoản (#modalConfirmToggleLockUser)
 * 5. Modal Xác Nhận Xóa Người Dùng (#modalConfirmDeleteUser)
 * 6. Modal Chi Tiết Hồ Sơ Người Dùng (#modalUserFullDetailPopup)
 */
if (!defined('ABSPATH')) exit;
?>

<!-- =========================================================================
     1. POPUP THÊM CHƯƠNG NHANH CHO TRUYỆN (#modalQuickAddChapterToStory)
     ========================================================================= -->
<div id="modalQuickAddChapterToStory" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 800px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header-top-box" style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--bg-card);">
            <div>
                <span style="font-size: 12px; font-weight: 700; color: var(--primary-green); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-plus-circle"></i> Thêm Chương Mới
                </span>
                <h3 id="quickAddStoryTitleDisplay" style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 3px 0 0;">...</h3>
            </div>
            <button type="button" class="btn-close-quick-add-chap" style="background: none; border: none; font-size: 22px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="quickAddChapterAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formQuickAddChapter">
                <input type="hidden" id="quickAddStoryId" name="story_id" value="" />
                <input type="hidden" id="quickAddIsDraft" name="is_draft" value="0" />
                <input type="hidden" name="action" value="muop_submit_chapter" />
                <input type="hidden" name="is_admin_portal" value="1" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <div class="modal-form-grid-chap" style="display: grid; grid-template-columns: 140px 1fr; gap: 14px; margin-bottom: 14px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="quickAddChapterNumber" style="font-weight: 700; font-size: 13px;">Số Chương (*)</label>
                        <input type="number" id="quickAddChapterNumber" name="chapter_number" class="form-input" min="1" required />
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="quickAddChapterTitle" style="font-weight: 700; font-size: 13px;">Tên Chương (*)</label>
                        <input type="text" id="quickAddChapterTitle" name="chapter_title" class="form-input" placeholder="VD: Khởi đầu mới..." required />
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="quickAddChapterContent" style="font-weight: 700; font-size: 13px;">Nội Dung Chương (*)</label>
                    <textarea id="quickAddChapterContent" name="content" class="form-input" rows="12" placeholder="Nhập hoặc dán nội dung chương truyện vào đây..." required style="line-height: 1.6; font-size: 14.5px;"></textarea>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; flex-wrap: wrap; gap: 8px;">
                        <span style="font-size: 12.5px; color: var(--text-muted);">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Tự động đếm số từ theo thời gian thực.
                        </span>
                        <div class="chapter-word-count-badge" style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 4px 12px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; color: var(--text-main); display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-pen-nib" style="color: var(--primary-green);"></i>
                            <span>Số từ: <strong id="quickAddWordCount" style="color: var(--primary-green); font-size: 14px;">0</strong> từ</span>
                            <span style="color: var(--border-color); margin: 0 2px;">|</span>
                            <span style="font-size: 12px; color: var(--text-muted);"><span id="quickAddCharCount">0</span> ký tự</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary btn-close-quick-add-chap" style="padding: 8px 18px; font-size: 13.5px;">Hủy Bỏ</button>
                    <button type="button" id="btnQuickAddDraft" class="btn btn-outline" style="padding: 8px 18px; font-size: 13.5px;">
                        <i class="fa-solid fa-file-pen"></i> Lưu Nháp
                    </button>
                    <button type="submit" id="btnQuickAddPublish" class="btn btn-primary" style="padding: 8px 24px; font-size: 13.5px;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Xuất Bản (Đăng Ngay)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     2. POPUP CHI TIẾT TRUYỆN ĐẦY ĐỦ (#modalStoryFullPreviewDetails - HÌNH 4)
     ========================================================================= -->
<div id="modalStoryFullPreviewDetails" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 860px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--bg-card);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="width: 32px; height: 32px; background: var(--pastel-green); color: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                    <i class="fa-solid fa-book-open"></i>
                </span>
                <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">Thông Tin Chi Tiết Truyện</h3>
            </div>
            <button type="button" class="btn-close-story-preview" style="background: none; border: none; font-size: 22px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 24px; overflow-y: auto; flex: 1;">
            <div id="prevStoryLoading" style="text-align: center; padding: 40px; color: var(--text-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 28px; color: var(--primary-green);"></i>
                <div style="margin-top: 10px; font-weight: 600;">Đang nạp chi tiết truyện...</div>
            </div>

            <div id="prevStoryBody" style="display: none;">
                <!-- Header Block: Bìa & Thông Tin Cơ Bản (Theo phong cách Hình 4) -->
                <div class="prev-story-hero" style="display: flex; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                    <div style="width: 140px; flex-shrink: 0;">
                        <img id="prevStoryCover" src="" alt="Bìa truyện" style="width: 100%; aspect-ratio: 3/4; object-fit: cover; border-radius: var(--radius-sm); box-shadow: var(--shadow-md); border: 1px solid var(--border-color); display: block;" />
                    </div>
                    <div style="flex: 1; min-width: 260px;">
                        <h2 id="prevStoryTitle" style="font-size: 18px; font-weight: 800; color: var(--text-main); margin: 0 0 12px; line-height: 1.4; text-transform: uppercase;">...</h2>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px; font-size: 13.5px; margin-bottom: 16px;">
                            <div>
                                <i class="fa-solid fa-people-group" style="color: #8b5cf6; width: 18px;"></i>
                                <strong style="color: var(--text-muted);">Team dịch:</strong>
                                <span id="prevStoryTeam" class="badge" style="background: var(--pastel-green); color: var(--primary-green); font-weight: 700; margin-left: 4px;">...</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-tags" style="color: #0284c7; width: 18px;"></i>
                                <strong style="color: var(--text-muted);">Thể loại:</strong>
                                <span id="prevStoryGenres" class="badge" style="background: var(--pastel-green); color: var(--text-main); font-weight: 600; margin-left: 4px;">...</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-signal" style="color: #f59e0b; width: 18px;"></i>
                                <strong style="color: var(--text-muted);">Trạng thái:</strong>
                                <span id="prevStoryStatusBadge" class="badge badge-full" style="margin-left: 4px;">...</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-book-bookmark" style="color: var(--primary-green); width: 18px;"></i>
                                <strong style="color: var(--text-muted);">Số chương:</strong>
                                <span id="prevStoryChapsCount" style="font-weight: 700; color: var(--text-main); margin-left: 4px;">0</span> chương
                            </div>
                            <div>
                                <i class="fa-solid fa-eye" style="color: #64748b; width: 18px;"></i>
                                <strong style="color: var(--text-muted);">Lượt xem:</strong>
                                <span id="prevStoryViews" style="font-weight: 700; color: var(--text-main); margin-left: 4px;">0</span> lượt
                            </div>
                            <div>
                                <i class="fa-solid fa-calendar-days" style="color: #64748b; width: 18px;"></i>
                                <strong style="color: var(--text-muted);">Cập nhật:</strong>
                                <span id="prevStoryUpdated" style="color: var(--text-muted); margin-left: 4px;">--</span>
                            </div>
                        </div>

                        <!-- Các Nút Hành Động (Đọc Từ Đầu, Đọc Mới Nhất, Xem Trên Web) -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a id="prevBtnReadFirst" href="#" target="_blank" class="btn btn-primary" style="padding: 7px 16px; font-size: 13px;">
                                <i class="fa-solid fa-book-open"></i> Đọc Từ Đầu
                            </a>
                            <a id="prevBtnReadLatest" href="#" target="_blank" class="btn btn-secondary" style="padding: 7px 16px; font-size: 13px;">
                                <i class="fa-solid fa-forward-step"></i> Đọc Mới Nhất
                            </a>
                            <a id="prevBtnViewWeb" href="#" target="_blank" class="btn btn-outline" style="padding: 7px 16px; font-size: 13px;">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Trên Website
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Giới Thiệu Truyện (Hình 4: Tách đoạn rõ ràng) -->
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 15px; font-weight: 800; color: var(--primary-green); margin-bottom: 10px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">
                        <i class="fa-solid fa-quote-left"></i> Giới Thiệu Truyện
                    </h3>
                    <div id="prevStoryDescription" class="prev-description-box" style="background: var(--pastel-green); padding: 16px 20px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); font-size: 14px; line-height: 1.7; color: var(--text-main);">
                        <!-- Formatted paragraphs will appear here -->
                    </div>
                </div>

                <!-- Danh Sách Chương (Hình 4) -->
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; color: var(--text-main); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-list-ol" style="color: var(--primary-green);"></i> Danh Sách Chương (<span id="prevStoryChapsTotal" style="color: var(--primary-green);">0</span>)
                    </h3>
                    <div id="prevStoryChaptersList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 10px; max-height: 320px; overflow-y: auto; padding-right: 4px;">
                        <!-- Chapters cards will appear here -->
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 12px 20px; border-top: 1px solid var(--border-color); background: var(--bg-card); display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary btn-close-story-preview" style="padding: 7px 20px; font-size: 13px;">Đóng Lại</button>
        </div>
    </div>
</div>

<!-- =========================================================================
     3. POPUP CHỈNH SỬA THÔNG TIN USER (#modalEditUserAdmin)
     ========================================================================= -->
<div id="modalEditUserAdmin" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 520px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; animation: slideUp 0.25s ease;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--bg-card);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="width: 32px; height: 32px; background: var(--pastel-green); color: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-user-pen"></i>
                </span>
                <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">Sửa Thông Tin Người Dùng</h3>
            </div>
            <button type="button" class="btn-close-edit-user" style="background: none; border: none; font-size: 22px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="formAdminEditUser" style="padding: 20px;">
            <input type="hidden" id="editUserId" name="user_id" value="" />
            <input type="hidden" name="action" value="muop_admin_action" />
            <input type="hidden" name="sub_action" value="edit_user" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

            <div id="editUserAlert" class="alert-box alert-error" style="display: none; margin-bottom: 14px; font-size: 13px;"></div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" for="editUserLogin" style="font-weight: 700; font-size: 13px;">Username (Tên đăng nhập):</label>
                <input type="text" id="editUserLogin" class="form-input" readonly disabled style="background: var(--pastel-green); font-family: monospace; font-weight: 700;" />
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" for="editUserDisplayName" style="font-weight: 700; font-size: 13px;">Tên Hiển Thị (*):</label>
                <input type="text" id="editUserDisplayName" name="display_name" class="form-input" required />
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" for="editUserEmail" style="font-weight: 700; font-size: 13px;">Địa Chỉ Email (*):</label>
                <input type="email" id="editUserEmail" name="email" class="form-input" required />
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" for="editUserRole" style="font-weight: 700; font-size: 13px;">Vai Trò (Role):</label>
                <select id="editUserRole" name="role" class="form-input">
                    <option value="doc_gia">Độc Giả</option>
                    <option value="dich_gia">Dịch Giả</option>
                    <option value="administrator">Quản Trị Viên</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                    <label class="form-label" for="editUserPassword" style="font-weight: 700; font-size: 13px; margin: 0;">Mật Khẩu Mới (Tùy chọn):</label>
                    <button type="button" id="btnGenerateEditUserPass" class="btn btn-xs btn-outline" style="font-size: 11.5px; padding: 2px 9px; border-color: #f59e0b; color: #d97706; font-weight: 600;">
                        <i class="fa-solid fa-dice"></i> Tạo ngẫu nhiên
                    </button>
                </div>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="editUserPassword" name="new_password" class="form-input" placeholder="Để trống nếu không muốn đổi mật khẩu" autocomplete="new-password" style="font-family: monospace; font-size: 13.5px; font-weight: 700; padding-right: 76px;" />
                    <div style="position: absolute; right: 6px; display: flex; align-items: center; gap: 4px;">
                        <button type="button" id="btnToggleViewEditUserPass" class="btn btn-xs" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px 6px;" title="Xem / Ẩn mật khẩu">
                            <i class="fa-solid fa-eye" id="iconToggleEditPass"></i>
                        </button>
                        <button type="button" id="btnCopyEditUserPass" class="btn btn-xs" style="background: none; border: none; color: var(--primary-green); cursor: pointer; padding: 4px 6px;" title="Sao chép mật khẩu">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>
                <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px; display: block;">Chỉ nhập khi bạn muốn thiết lập lại mật khẩu cho người dùng này. Bấm icon con mắt 👁️ để xem/ẩn.</span>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary btn-close-edit-user" style="padding: 8px 18px; font-size: 13.5px;">Hủy Bỏ</button>
                <button type="submit" id="btnSubmitEditUser" class="btn btn-primary" style="padding: 8px 22px; font-size: 13.5px;">
                    <i class="fa-solid fa-check"></i> Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     4. POPUP XÁC NHẬN KHÓA / MỞ KHÓA TÀI KHOẢN (#modalConfirmToggleLockUser)
     ========================================================================= -->
<div id="modalConfirmToggleLockUser" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 440px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 2px solid #fed7aa; overflow: hidden; animation: slideUp 0.25s ease;">
        <div style="padding: 24px; text-align: center;">
            <div id="lockIconBox" style="width: 58px; height: 58px; background: #ffedd5; color: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                <i class="fa-solid fa-lock" id="iconLockModal"></i>
            </div>
            <h3 id="titleLockModal" style="font-size: 18px; font-weight: 800; color: #9a3412; margin-bottom: 8px;">
                Xác Nhận Khóa Tài Khoản?
            </h3>
            <p id="descLockModal" style="font-size: 14px; color: var(--text-main); margin-bottom: 20px; line-height: 1.5;">
                Bạn có chắc chắn muốn thực hiện thao tác này cho tài khoản <strong id="lockTargetUserName" style="color: #ea580c;">...</strong> không?
            </p>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-secondary btn-cancel-toggle-lock" style="padding: 9px 20px; font-size: 13.5px; border-radius: var(--radius-full);">
                    Hủy Bỏ
                </button>
                <button type="button" id="btnConfirmToggleLockAction" class="btn btn-primary" style="padding: 9px 24px; font-size: 13.5px; border-radius: var(--radius-full); font-weight: 700;">
                    <i class="fa-solid fa-check"></i> Xác Nhận
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     5. POPUP XÁC NHẬN XÓA TÀI KHOẢN (#modalConfirmDeleteUser)
     ========================================================================= -->
<div id="modalConfirmDeleteUser" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 440px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 2px solid #fecaca; overflow: hidden; animation: slideUp 0.25s ease;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 58px; height: 58px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: #991b1b; margin-bottom: 8px;">
                Xác Nhận Xóa Tài Khoản?
            </h3>
            <p style="font-size: 14px; color: var(--text-main); margin-bottom: 6px; line-height: 1.5;">
                Bạn có chắc chắn muốn xóa tài khoản <strong id="deleteUserTargetName" style="color: #dc2626;">...</strong> không?
            </p>
            <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.4;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b;"></i> Hành động này sẽ xóa vĩnh viễn tài khoản người dùng và không thể hoàn tác!
            </p>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-secondary btn-cancel-delete-user" style="padding: 9px 20px; font-size: 13.5px; border-radius: var(--radius-full);">
                    Hủy Bỏ
                </button>
                <button type="button" id="btnConfirmDeleteUserAction" class="btn btn-danger" style="background: #dc2626; border-color: #dc2626; color: #fff; padding: 9px 24px; font-size: 13.5px; border-radius: var(--radius-full); font-weight: 700; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                    <i class="fa-solid fa-trash-can"></i> Xóa Vĩnh Viễn
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     6. POPUP CHI TIẾT HỒ SƠ NGƯỜI DÙNG (#modalUserFullDetailPopup)
     ========================================================================= -->
<div id="modalUserFullDetailPopup" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 760px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--bg-card);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="width: 32px; height: 32px; background: var(--pastel-green); color: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fa-solid fa-id-card"></i>
                </span>
                <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">Hồ Sơ Chi Tiết Người Dùng</h3>
            </div>
            <button type="button" class="btn-close-user-detail-popup" style="background: none; border: none; font-size: 22px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 24px; overflow-y: auto; flex: 1;">
            <div id="popupUserLoading" style="text-align: center; padding: 40px; color: var(--text-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 28px; color: var(--primary-green);"></i>
                <div style="margin-top: 10px; font-weight: 600;">Đang tải thông tin hồ sơ...</div>
            </div>

            <div id="popupUserContentBox" style="display: none;">
                <!-- Header Card: Avatar + Họ tên + Badges -->
                <div style="display: flex; gap: 20px; align-items: center; background: var(--pastel-green); padding: 18px 20px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 20px; flex-wrap: wrap;">
                    <div id="popupUserAvatarWrap" style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary-green); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800; overflow: hidden; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                        <!-- Image or initial -->
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                            <h2 id="popupUserDisplayName" style="font-size: 18px; font-weight: 800; color: var(--text-main); margin: 0;">...</h2>
                            <span id="popupUserRoleBadge" class="badge badge-primary">...</span>
                            <span id="popupUserStatusBadge" class="badge badge-full">...</span>
                        </div>
                        <div style="font-size: 13.5px; color: var(--text-muted);">
                            <span><i class="fa-solid fa-at"></i> <span id="popupUserLogin">--</span></span>
                            <span style="margin: 0 8px;">•</span>
                            <span>ID: <strong id="popupUserIdText" style="color: #0284c7;">#--</strong></span>
                            <span style="margin: 0 8px;">•</span>
                            <span><i class="fa-regular fa-envelope"></i> <span id="popupUserEmail">--</span></span>
                        </div>
                    </div>
                </div>

                <!-- Thống kê hoạt động (3 hộp chỉ số) -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px;">
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 14px; border-radius: var(--radius-sm); text-align: center;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Truyện Đã Đăng</div>
                        <div id="popupUserStoriesCount" style="font-size: 22px; font-weight: 800; color: var(--primary-green); margin-top: 4px;">0</div>
                    </div>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 14px; border-radius: var(--radius-sm); text-align: center;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Tổng Lượt Xem</div>
                        <div id="popupUserViewsCount" style="font-size: 22px; font-weight: 800; color: #0284c7; margin-top: 4px;">0</div>
                    </div>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 14px; border-radius: var(--radius-sm); text-align: center;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Lương Tháng Này</div>
                        <div id="popupUserSalaryCount" style="font-size: 22px; font-weight: 800; color: #16a34a; margin-top: 4px;">0 VNĐ</div>
                    </div>
                </div>

                <!-- Thông tin chi tiết bổ sung & Ngân hàng -->
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin: 0 0 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-building-columns" style="color: #16a34a;"></i> Thông Tin Ngân Hàng (Nhận Lương Dịch Giả)
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 13px;">
                        <div><strong>Ngân hàng:</strong> <span id="popupUserBankName" style="color: var(--text-main);">--</span></div>
                        <div><strong>Chủ tài khoản:</strong> <span id="popupUserBankHolder" style="color: var(--text-main); text-transform: uppercase;">--</span></div>
                        <div><strong>Số tài khoản:</strong> <span id="popupUserBankNumber" style="font-family: monospace; font-weight: 700; color: #0284c7;">--</span></div>
                    </div>
                </div>

                <!-- Danh sách truyện đã đăng -->
                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin: 0 0 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-book" style="color: var(--primary-green);"></i> Các Truyện Đã Đăng (<span id="popupUserStoriesTotal">0</span>)
                    </h4>
                    <div id="popupUserStoriesList" style="max-height: 220px; overflow-y: auto;">
                        <!-- Stories list rendered here -->
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 12px 20px; border-top: 1px solid var(--border-color); background: var(--bg-card); display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary btn-close-user-detail-popup" style="padding: 7px 20px; font-size: 13px;">Đóng</button>
        </div>
    </div>
</div>

<!-- =========================================================================
     7. POPUP CHI TIẾT THỂ LOẠI - DANH SÁCH TRUYỆN THUỘC THỂ LOẠI (#admModalGenreDetail)
     ========================================================================= -->
<div id="admModalGenreDetail" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 840px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 88vh; display: flex; flex-direction: column;">
        <div class="modal-header-top-box" style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--pastel-green);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 8px; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span>Thể loại:</span>
                        <span id="admGenreDetailName" style="color: var(--primary-green);">...</span>
                        <span id="admGenreDetailBadge" class="badge badge-green" style="font-size: 11px; padding: 2px 8px;">0 truyện</span>
                    </h3>
                    <p style="margin: 2px 0 0; font-size: 12px; color: var(--text-muted);">
                        Danh sách các truyện thuộc thể loại này trên hệ thống
                    </p>
                </div>
            </div>
            <button type="button" class="btn-close-genre-detail" id="btnCloseGenreDetailModalX" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 14px 20px; border-bottom: 1px solid var(--border-light); background: var(--bg-card); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div style="position: relative; flex: 1; min-width: 220px;">
                <input type="text" id="admGenreDetailSearch" class="form-input" placeholder="Tìm nhanh truyện trong thể loại này..." style="width: 100%; height: 36px; font-size: 13px; padding-left: 32px;" />
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 11px; top: 11px; color: var(--text-muted); font-size: 13px;"></i>
            </div>
            <div style="font-size: 12.5px; color: var(--text-muted);">
                Hiển thị: <strong id="admGenreDetailShownCount" style="color: var(--primary-green);">0</strong> truyện
            </div>
        </div>

        <div style="padding: 0 20px 20px; overflow-y: auto; flex: 1; position: relative;">
            <div id="admGenreDetailLoading" style="padding: 40px 20px; text-align: center; color: var(--primary-green); font-size: 14px; font-weight: 600;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                Đang tải danh sách truyện...
            </div>

            <div id="admGenreDetailEmpty" style="display: none; padding: 40px 20px; text-align: center; color: var(--text-muted); font-size: 13.5px;">
                <i class="fa-solid fa-book-open" style="font-size: 28px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                Hiện chưa có truyện nào thuộc thể loại này!
            </div>

            <div id="admGenreDetailTableWrapper" style="display: none; margin-top: 14px;">
                <div class="table-responsive" style="border-radius: var(--radius-sm); border: 1px solid var(--border-color); overflow-x: auto;">
                    <table class="db-data-table" style="width: 100%; font-size: 13px;">
                        <thead>
                            <tr style="background: var(--bg-body); border-bottom: 1px solid var(--border-color);">
                                <th style="width: 45px; text-align: center; padding: 10px 6px;">STT</th>
                                <th style="width: 55px; text-align: center; padding: 10px 6px;">Bìa</th>
                                <th style="padding: 10px 12px; text-align: left;">Tên Truyện</th>
                                <th style="padding: 10px 12px; text-align: left;">Dịch Giả / Tác Giả</th>
                                <th style="width: 90px; text-align: center; padding: 10px 8px;">Số Chương</th>
                                <th style="width: 90px; text-align: center; padding: 10px 8px;">Lượt Đọc</th>
                                <th style="width: 110px; text-align: center; padding: 10px 8px;">Trạng Thái</th>
                                <th style="width: 95px; text-align: center; padding: 10px 8px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="admGenreDetailTableBody">
                            <!-- Rows rendered via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="padding: 12px 20px; border-top: 1px solid var(--border-color); background: var(--bg-card); display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary btn-close-genre-detail" style="padding: 7px 20px; font-size: 13px;">Đóng</button>
        </div>
    </div>
</div>

<!-- =========================================================================
     7. POPUP SỬA & XEM MẬT KHẨU NGƯỜI DÙNG (#modalUserPasswordAdmin)
     ========================================================================= -->
<div id="modalUserPasswordAdmin" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 500px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; animation: slideUp 0.25s ease;">
        <!-- Header -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--bg-card);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="width: 36px; height: 36px; background: #fef3c7; color: #d97706; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-solid fa-key"></i>
                </span>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0;">Sửa & Xem Mật Khẩu Người Dùng</h3>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Cấp lại hoặc kiểm tra mật khẩu tài khoản thành viên</div>
                </div>
            </div>
            <button type="button" class="btn-close-user-pass" style="background: none; border: none; font-size: 22px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px;">
            <!-- User Card Mini -->
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px 14px; background: var(--pastel-green); border-radius: var(--radius-sm); border: 1px solid rgba(34, 197, 94, 0.2); margin-bottom: 18px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--primary-green); color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 18px;" id="passUserAvatarChar">
                    U
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 700; font-size: 14.5px; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" id="passUserDisplayName">...</div>
                    <div style="font-size: 12px; color: var(--text-muted); display: flex; gap: 10px; margin-top: 2px; flex-wrap: wrap;">
                        <span><i class="fa-solid fa-at"></i> <span id="passUserLogin">--</span></span>
                        <span><i class="fa-solid fa-envelope"></i> <span id="passUserEmail">--</span></span>
                    </div>
                </div>
            </div>

            <!-- Loading indicator -->
            <div id="passModalLoading" style="display: none; text-align: center; padding: 16px; color: var(--primary-green); font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-spinner fa-spin"></i> Đang tải thông tin mật khẩu...
            </div>

            <!-- Box 1: Xem mật khẩu đã cấp gần nhất -->
            <div id="boxExistingPasswordInfo" style="margin-bottom: 18px; padding: 14px; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: var(--radius-sm);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 12.5px; font-weight: 700; color: #7e22ce;">
                        <i class="fa-solid fa-shield-halved"></i> Mật Khẩu Admin Cấp Gần Nhất:
                    </span>
                    <span style="font-size: 11px; color: #a855f7;" id="passLastResetTime">--</span>
                </div>
                <div id="passExistingAvailable" style="display: flex; align-items: center; gap: 8px;">
                    <div style="position: relative; flex: 1;">
                        <input type="password" id="inputExistingPassDisplay" class="form-input" readonly style="font-family: monospace; font-weight: 700; font-size: 14px; padding-right: 40px; background: #fff;" value="" />
                        <button type="button" id="btnToggleExistingPass" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6b7280; cursor: pointer; padding: 4px;" title="Xem / Ẩn mật khẩu">
                            <i class="fa-solid fa-eye" id="iconToggleExistingPass"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline" id="btnCopyExistingPass" style="border-color: #c084fc; color: #7e22ce; padding: 6px 12px; font-size: 12.5px; font-weight: 600;" title="Sao chép mật khẩu">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
                <div id="passExistingNotSet" style="display: none; font-size: 12px; color: #6b7280; line-height: 1.5;">
                    <i class="fa-solid fa-circle-info" style="color: #3b82f6;"></i> Tài khoản này dùng mật khẩu do người dùng tự đặt khi đăng ký (được WordPress mã hóa bảo mật 1 chiều). Bạn có thể đổi mật khẩu mới bên dưới và xem trực tiếp.
                </div>
            </div>

            <!-- Box 2: Đổi / Sửa mật khẩu mới -->
            <form id="formAdminChangeUserPass">
                <input type="hidden" id="changePassUserId" name="user_id" value="" />
                <input type="hidden" name="action" value="muop_admin_action" />
                <input type="hidden" name="sub_action" value="change_user_password" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <div id="changePassAlert" class="alert-box" style="display: none; margin-bottom: 12px; font-size: 13px;"></div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <label class="form-label" for="inputNewUserPass" style="font-weight: 700; font-size: 13px; margin: 0;">
                            Mật Khẩu Mới Cần Đổi (*):
                        </label>
                        <button type="button" id="btnGenerateRandomPass" class="btn btn-xs btn-outline" style="font-size: 11.5px; padding: 3px 10px; border-color: #f59e0b; color: #d97706; font-weight: 600;">
                            <i class="fa-solid fa-dice"></i> Tạo ngẫu nhiên
                        </button>
                    </div>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="text" id="inputNewUserPass" name="new_password" class="form-input" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)..." autocomplete="off" style="font-family: monospace; font-size: 14px; font-weight: 700; padding-right: 80px;" required />
                        <div style="position: absolute; right: 6px; display: flex; align-items: center; gap: 4px;">
                            <button type="button" id="btnToggleNewPass" class="btn btn-xs" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px 6px;" title="Xem / Ẩn mật khẩu">
                                <i class="fa-solid fa-eye-slash" id="iconToggleNewPass"></i>
                            </button>
                            <button type="button" id="btnCopyNewPass" class="btn btn-xs" style="background: none; border: none; color: var(--primary-green); cursor: pointer; padding: 4px 6px;" title="Sao chép mật khẩu">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px; display: block;">
                        💡 Bấm icon con mắt 👁️ để xem/ẩn mật khẩu hoặc bấm <strong>Copy</strong> để gửi nhanh cho người dùng.
                    </span>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary btn-close-user-pass" style="padding: 8px 18px; font-size: 13.5px;">Hủy Bỏ</button>
                    <button type="submit" id="btnSubmitChangeUserPass" class="btn btn-primary" style="padding: 8px 22px; font-size: 13.5px; font-weight: 700;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu Mật Khẩu Mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

