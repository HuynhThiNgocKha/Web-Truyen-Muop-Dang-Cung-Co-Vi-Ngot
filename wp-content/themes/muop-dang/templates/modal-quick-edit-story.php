<?php
/**
 * Template Part: Modal Thay Đổi Ảnh Bìa Nhanh & Đổi Tên Truyện Nhanh
 * Dành cho Admin và Dịch Giả trên trang chi tiết truyện (single-truyen.php)
 */
if (!defined('ABSPATH')) exit;
?>

<!-- 1. MODAL THAY ĐỔI ẢNH BÌA TRUYỆN -->
<div id="modalChangeCoverDirect" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 580px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
        
        <!-- Header (Dòng 1: Chỉnh Sửa | Dòng 2: Thay Đổi Ảnh Bìa Truyện) -->
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-camera"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub" id="quickCoverModalStoryTitle">Thay Đổi Ảnh Bìa Truyện</h3>
            </div>
            <button type="button" class="btn-close-quick-modal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="changeCoverAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formChangeCoverDirect" enctype="multipart/form-data">
                <input type="hidden" name="action" value="muop_update_story_cover" />
                <input type="hidden" name="story_id" id="quickCoverStoryId" value="" />
                <input type="hidden" name="preset_cover_id" id="inputSelectedPresetCoverId" value="" />
                <input type="hidden" name="preset_cover_url" id="inputSelectedPresetCoverUrl" value="" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
                <input type="file" name="cover_file" id="inputDirectCoverFile" accept="image/*" style="display: none;" />

                <!-- Khung xem trước ảnh bìa -->
                <div style="display: flex; gap: 16px; align-items: center; background: var(--pastel-green); padding: 14px; border-radius: var(--radius-sm); margin-bottom: 18px; border: 1px solid var(--border-light);">
                    <div style="width: 80px; height: 110px; flex-shrink: 0; border-radius: 6px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: #eee;">
                        <img id="directCoverPreviewImg" src="" alt="Xem trước ảnh bìa" style="width: 100%; height: 100%; object-fit: cover;" />
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 13.5px; margin-bottom: 4px; color: var(--text-main);">Xem trước ảnh bìa</div>
                        <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px 0; line-height: 1.4;">
                            Bạn có thể tải ảnh mới từ thiết bị hoặc chọn một ảnh bìa có sẵn từ thư viện ảnh bên dưới.
                        </p>
                        <div id="directCoverSelectedStatus" style="display: none; align-items: center; gap: 6px; font-size: 12px;">
                            <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                            <span id="directCoverFileName" style="font-weight: 600; color: #2E7D32;"></span>
                            <span class="badge badge-green" style="font-size: 10.5px;">Đã chọn</span>
                        </div>
                    </div>
                </div>

                <!-- Tải ảnh từ thiết bị -->
                <div style="margin-bottom: 18px;">
                    <label class="form-label" style="font-weight: 700; margin-bottom: 8px;">1. Tải ảnh từ thiết bị:</label>
                    <button type="button" id="btnTriggerDirectUpload" class="btn btn-secondary" style="width: 100%; justify-content: center; padding: 10px; font-size: 13px;">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị
                    </button>
                </div>

                <!-- Chọn ảnh có sẵn trong thư viện -->
                <div>
                    <label class="form-label" style="font-weight: 700; margin-bottom: 8px;">2. Hoặc chọn ảnh có sẵn:</label>
                    <div id="directPresetCoversList" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; max-height: 190px; overflow-y: auto; padding: 10px; background: var(--bg-body); border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <div style="grid-column: 1 / -1; text-align: center; font-size: 12px; color: var(--text-muted); padding: 15px;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Đang nạp danh sách ảnh...
                        </div>
                    </div>
                </div>

                <!-- Footer buttons -->
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <button type="button" class="btn btn-secondary btn-close-quick-modal">Hủy</button>
                    <button type="submit" id="btnSubmitDirectCover" class="btn btn-primary" style="padding: 8px 22px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Ảnh Bìa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. MODAL CHỈNH SỬA TÊN TRUYỆN NHANH -->
<div id="modalEditTitleDirect" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 480px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden;">
        
        <!-- Header (Dòng 1: Chỉnh Sửa | Dòng 2: Đổi Tên Truyện) -->
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub" id="quickTitleModalStoryTitle">Đổi Tên Truyện</h3>
            </div>
            <button type="button" class="btn-close-quick-modal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 20px;">
            <div id="editTitleAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formEditTitleDirect">
                <input type="hidden" name="action" value="muop_update_story_title" />
                <input type="hidden" name="story_id" id="quickTitleStoryId" value="" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="inputQuickStoryTitle" style="font-weight: 700;">Tên Truyện Mới (*)</label>
                    <input type="text" id="inputQuickStoryTitle" name="title" class="form-input" style="height: 42px; font-size: 14px;" required placeholder="Nhập tên truyện..." />
                </div>

                <!-- Footer buttons -->
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary btn-close-quick-modal">Hủy</button>
                    <button type="submit" id="btnSubmitQuickTitle" class="btn btn-primary" style="padding: 8px 22px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Tên Truyện
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
