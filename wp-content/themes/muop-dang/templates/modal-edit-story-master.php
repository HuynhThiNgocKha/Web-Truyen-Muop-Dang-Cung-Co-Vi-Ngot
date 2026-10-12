<?php
/**
 * Template Part: Tách Biệt 2 Modal Popup Chỉnh Sửa Riêng Biệt:
 * 1. Modal Chỉnh Sửa Thông Tin Truyện (#modalEditStoryInfo)
 * 2. Modal Chỉnh Sửa Nội Dung Chương Truyện (#modalEditChapterContent)
 * Dành cho Dịch Giả & Quản Trị Viên
 */
if (!defined('ABSPATH')) exit;

$all_genres = get_terms(array('taxonomy' => 'the_loai', 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC'));
if (is_wp_error($all_genres)) $all_genres = array();
?>

<!-- =========================================================================
     POPUP 1: CHỈNH SỬA THÔNG TIN TRUYỆN
========================================================================== -->
<div id="modalEditStoryInfo" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 820px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        
        <!-- MODAL HEADER (Dòng 1: Chỉnh Sửa | Dòng 2: Tên truyện) -->
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub" id="modalStoryTitleText">...</h3>
            </div>
            <button type="button" id="btnCloseStoryInfoModal" class="btn-close-modal-story-info" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="editStoryAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>
            
            <form id="formEditStoryMaster" enctype="multipart/form-data">
                <input type="hidden" name="action" value="muop_update_story" />
                <input type="hidden" name="story_id" id="editStoryId" value="" />
                <input type="hidden" name="is_draft" id="editStoryIsDraftInput" value="0" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <!-- Tên Truyện & Trạng Thái (1 hàng dọc trên Mobile) -->
                <div class="modal-form-grid-2-1">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="editStoryTitle">Tên Truyện (*)</label>
                        <input type="text" id="editStoryTitle" name="title" class="form-input" required />
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="editStoryStatus">Trạng Thái</label>
                        <select id="editStoryStatus" name="status" class="form-input">
                            <option value="dang_ra">Đang ra</option>
                            <option value="hoan_thanh">Hoàn thành (Full)</option>
                        </select>
                    </div>
                </div>

                <!-- Tác Giả & Nhóm Dịch (1 hàng dọc trên Mobile) -->
                <div class="modal-form-grid-1-1">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="editStoryAuthor">Tác Giả</label>
                        <input type="text" id="editStoryAuthor" name="author_name" class="form-input" />
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="editStoryTeam">Nhóm Dịch / Người Dịch</label>
                        <input type="text" id="editStoryTeam" name="team_name" class="form-input" />
                    </div>
                </div>

                <!-- Ảnh Bìa Truyện -->
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Ảnh Bìa</label>
                    <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
                        <img id="editStoryCoverPreview" src="" alt="Ảnh bìa" style="width: 65px; height: 85px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-body); display: none;" />
                        <div>
                            <input type="file" id="editStoryCoverFile" name="cover_image" accept="image/*" style="display: none;" />
                            <button type="button" id="btnEditUploadCover" class="btn btn-secondary" style="padding: 7px 14px; font-size: 13px;">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị
                            </button>
                            <div id="editStoryCoverInfo" style="display: none; align-items: center; gap: 6px; margin-top: 6px; font-size: 12.5px;">
                                <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                                <span id="editStoryCoverFileName" style="font-weight: 600;"></span>
                                <span class="badge badge-green" style="font-size: 11px;">Đã chọn ảnh mới</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thể Loại -->
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Chọn Thể Loại</label>
                    <div style="margin-bottom: 8px; position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12px;"></i>
                        <input type="text" id="editInputSearchGenre" class="form-input" placeholder="Nhập tìm thể loại..." style="padding-left: 30px; height: 32px; font-size: 12.5px;" />
                    </div>
                    <div id="editGenresCheckboxList" class="modal-genres-list">
                        <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                            <?php foreach ($all_genres as $genre) : ?>
                                <label class="edit-genre-item" data-name="<?php echo esc_attr(mb_strtolower($genre->name, 'UTF-8')); ?>" style="display: flex; align-items: center; gap: 6px; font-size: 12.5px; cursor: pointer;">
                                    <input type="checkbox" name="categories[]" value="<?php echo esc_attr($genre->term_id); ?>" />
                                    <span><?php echo esc_html($genre->name); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Giới Thiệu / Văn Án -->
                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="editStoryDescription">Giới Thiệu / Văn Án Truyện (*)</label>
                    <textarea id="editStoryDescription" name="description" class="form-input" rows="7" required></textarea>
                </div>

                <!-- Buttons Hủy, Lưu Nháp & Cập Nhật Truyện -->
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary btn-close-modal-story-info">Hủy</button>
                    <button type="button" id="btnSaveDraftStoryAction" class="btn btn-outline" style="padding: 8px 18px; font-size: 13.5px;" title="Lưu thành bản nháp">
                        <i class="fa-solid fa-file-pen"></i> Lưu Vào Bản Nháp
                    </button>
                    <button type="submit" id="btnUpdateStoryAction" class="btn btn-primary" style="padding: 8px 24px; font-size: 13.5px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Truyện
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     POPUP 2: CHỈNH SỬA NỘI DUNG CHƯƠNG TRUYỆN
========================================================================== -->
<div id="modalEditChapterContent" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 820px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        
        <!-- MODAL HEADER (Dòng 1: Chỉnh Sửa | Dòng 2: Tên truyện) -->
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub" id="modalChapterStoryTitleText">...</h3>
            </div>
            <button type="button" id="btnCloseChapterContentModal" class="btn-close-modal-chapter" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="editChapterAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <div id="wrapperSelectEditChapter" style="background: var(--pastel-green); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 16px;">
                <label class="form-label" for="selectEditChapter" style="margin-bottom: 6px; font-weight: 700;">
                    <i class="fa-solid fa-list-ol"></i> Chọn Chương Cần Chỉnh Sửa:
                </label>
                <select id="selectEditChapter" class="form-input" style="height: 38px; font-size: 13.5px;">
                    <option value="">-- Đang tải danh sách chương... --</option>
                </select>
            </div>

            <!-- Thông báo nếu truyện chưa có chương -->
            <div id="editNoChaptersMsg" style="display: none; text-align: center; padding: 30px; background: var(--bg-card); border-radius: var(--radius-sm); border: 1px dashed var(--border-color); color: var(--text-muted);">
                <i class="fa-solid fa-circle-info" style="font-size: 24px; color: var(--primary-green); margin-bottom: 8px; display: block;"></i>
                Truyện này hiện chưa có chương nào. Bạn có thể thêm chương mới ngay bây giờ!
                <div style="margin-top: 12px;">
                    <a id="btnGoAddChapterFromModal" href="<?php echo esc_url(home_url('/them-chuong/')); ?>" class="btn btn-primary" style="padding: 6px 16px; font-size: 13px;">
                        <i class="fa-solid fa-plus"></i> Thêm Chương Mới
                    </a>
                </div>
            </div>

            <!-- Form Chỉnh Sửa Chương (khi đã chọn chương) -->
            <form id="formEditChapterFields" style="display: none;">
                <input type="hidden" id="editChapterId" name="chapter_id" value="" />
                <input type="hidden" id="editChapterIsDraftInput" name="is_draft" value="0" />
                <input type="hidden" name="action" value="muop_update_chapter" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <!-- Số Chương & Tên Chương (1 hàng dọc trên Mobile) -->
                <div class="modal-form-grid-chap">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="editChapterNumber">Số Chương (*)</label>
                        <input type="number" id="editChapterNumber" name="chapter_number" class="form-input" min="1" required />
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="editChapterTitle">Tên Chương (*)</label>
                        <input type="text" id="editChapterTitle" name="chapter_title" class="form-input" required />
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="editChapterContent">Nội Dung Chương (*)</label>
                    <textarea id="editChapterContent" name="content" class="form-input" rows="12" required placeholder="Nội dung chương truyện..."></textarea>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary btn-close-modal-chapter">Hủy</button>
                    <button type="button" id="btnSaveDraftChapterAction" class="btn btn-outline" style="padding: 8px 18px; font-size: 13.5px;" title="Lưu nháp chương">
                        <i class="fa-solid fa-file-pen"></i> Lưu Nháp Chương
                    </button>
                    <button type="submit" id="btnUpdateChapterAction" class="btn btn-primary" style="padding: 8px 24px; font-size: 13.5px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Chương
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================================================
     POPUP XÁC NHẬN XÓA CHƯƠNG TRUYỆN
     ========================================================================== -->
<div id="modalDeleteChapterConfirm" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 440px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 2px solid #fecaca; overflow: hidden; animation: slideUp 0.25s ease;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 58px; height: 58px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: #991b1b; margin-bottom: 8px;">
                Xác Nhận Xóa Chương?
            </h3>
            <p style="font-size: 14px; color: var(--text-main); margin-bottom: 6px; line-height: 1.5;">
                Bạn có chắc chắn muốn xóa <strong id="deleteChapterLabelName" style="color: #dc2626;">Chương này</strong> không?
            </p>
            <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.4;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b;"></i> Hành động này sẽ xóa vĩnh viễn nội dung chương và không thể hoàn tác!
            </p>

            <div id="deleteChapterAlert" class="alert-box alert-error" style="display: none; margin-bottom: 14px; text-align: left; font-size: 13px;"></div>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-secondary btn-cancel-delete-chapter" style="padding: 9px 20px; font-size: 13.5px; border-radius: var(--radius-full);">
                    Hủy Bỏ
                </button>
                <button type="button" id="btnConfirmDeleteChapter" class="btn btn-danger" style="background: #dc2626; border-color: #dc2626; color: #fff; padding: 9px 22px; font-size: 13.5px; border-radius: var(--radius-full); font-weight: 700; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                    <i class="fa-solid fa-trash-can"></i> Xóa Chương
                </button>
            </div>
        </div>
    </div>
</div>

