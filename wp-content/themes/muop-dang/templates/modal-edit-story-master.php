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
                <input type="hidden" name="is_admin_portal" value="<?php echo (is_page('quan-ly-admin') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'quan-ly-admin') !== false)) ? '1' : '0'; ?>" />

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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" for="editStoryDescription" style="margin-bottom: 0;">Giới Thiệu / Văn Án Truyện (*)</label>
                        <button type="button" class="btn btn-outline btn-scan-sensitive btn-sm" data-target="#editStoryDescription" style="padding: 4px 12px; font-size: 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Duyệt Nội Dung
                        </button>
                    </div>
                    <textarea id="editStoryDescription" name="description" class="form-input form-textarea-large" rows="10" style="min-height: 220px; font-size: 14.5px; line-height: 1.6;" required></textarea>
                </div>

                <!-- Tùy Chọn Hẹn Giờ Đăng Truyện Trong Modal Sửa -->
                <div class="schedule-story-card" style="background: var(--bg-body); padding: 14px 16px; border-radius: var(--radius-sm); border: 1.5px dashed #8b5cf6; margin-bottom: 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 0;">
                            <input type="checkbox" id="editToggleScheduleStory" name="enable_schedule" value="1" onchange="muopToggleEditScheduleBox(this.checked)" style="width: 17px; height: 17px; accent-color: #7c3aed; cursor: pointer;" />
                            <span style="font-weight: 700; font-size: 13.5px; color: #6d28d9;">
                                <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Hẹn Giờ / Đổi Giờ Phát Hành
                            </span>
                        </label>
                        <span id="editStoryCurrentScheduleBadge" class="badge" style="display: none; background: #f5f3ff; color: #7c3aed; font-size: 11.5px; border: 1px solid #ddd6fe;"></span>
                    </div>

                    <div id="editScheduleTimeCollapse" style="display: none; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                        <div style="margin-bottom: 10px;">
                            <label class="form-label" style="font-weight: 700; font-size: 13px; color: #6d28d9; margin-bottom: 6px; display: block;">
                                <i class="fa-solid fa-calendar-days"></i> Chọn ngày & giờ hẹn mới (Chuẩn 24 Giờ Việt Nam):
                            </label>

                            <!-- Bộ chọn Ngày và 24 Giờ chuẩn Việt Nam (Không AM/PM) -->
                            <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 8px;">
                                <!-- Ngày -->
                                <div style="flex: 1.2; min-width: 140px;">
                                    <input type="date" id="editStorySchedDate" class="form-input" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" onchange="muopSyncEditStorySchedule()" style="height: 36px; font-size: 13px; font-weight: 600;" />
                                </div>

                                <!-- Giờ (24 giờ: 00 đến 23) -->
                                <div style="flex: 1; min-width: 105px;">
                                    <select id="editStorySchedHour" class="form-input" onchange="muopSyncEditStorySchedule()" style="height: 36px; font-size: 13px; font-weight: 600;">
                                        <?php for ($h = 0; $h < 24; $h++): 
                                            $h_str = str_pad($h, 2, '0', STR_PAD_LEFT);
                                            $suffix = '';
                                            if ($h >= 0 && $h < 6) $suffix = ' (đêm)';
                                            elseif ($h >= 6 && $h < 11) $suffix = ' (sáng)';
                                            elseif ($h >= 11 && $h < 13) $suffix = ' (trưa)';
                                            elseif ($h >= 13 && $h < 18) $suffix = ' (chiều)';
                                            else $suffix = ' (tối)';
                                        ?>
                                            <option value="<?php echo $h_str; ?>"><?php echo $h_str . ' giờ' . $suffix; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <!-- Phút (00 đến 55) -->
                                <div style="flex: 0.9; min-width: 90px;">
                                    <select id="editStorySchedMinute" class="form-input" onchange="muopSyncEditStorySchedule()" style="height: 36px; font-size: 13px; font-weight: 600;">
                                        <?php for ($m = 0; $m < 60; $m += 5): 
                                            $m_str = str_pad($m, 2, '0', STR_PAD_LEFT);
                                        ?>
                                            <option value="<?php echo $m_str; ?>"><?php echo $m_str . ' phút'; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Input ẩn gửi dữ liệu chuẩn YYYY-MM-DD HH:MM:00 về backend -->
                            <input type="hidden" id="editStoryScheduledTime" name="scheduled_time" value="" />

                            <!-- Xem trước thời gian hẹn -->
                            <div id="editStorySchedPreview" style="font-size: 12px; font-weight: 700; color: #6d28d9; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 6px; padding: 5px 10px; margin-bottom: 10px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-clock"></i> <span>Thời gian chọn: --:-- ngày --/--/----</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Gợi ý nhanh:</span>
                            <button type="button" class="btn btn-outline btn-quick-edit-sched" data-hours="1" onclick="muopSetQuickEditSchedule(1, null)" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">+1 Giờ</button>
                            <button type="button" class="btn btn-outline btn-quick-edit-sched" data-hours="3" onclick="muopSetQuickEditSchedule(3, null)" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">+3 Giờ</button>
                            <button type="button" class="btn btn-outline btn-quick-edit-sched" data-preset="tonight" onclick="muopSetQuickEditSchedule(null, 'tonight')" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">Tối nay (20:00)</button>
                            <button type="button" class="btn btn-outline btn-quick-edit-sched" data-preset="tomorrow_morning" onclick="muopSetQuickEditSchedule(null, 'tomorrow_morning')" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">Sáng mai (08:00)</button>
                        </div>
                    </div>
                </div>

                <script>
                function muopPad2Edit(n) { return String(n).padStart(2, '0'); }

                function muopSyncEditStorySchedule() {
                    var dEl = document.getElementById('editStorySchedDate');
                    var hEl = document.getElementById('editStorySchedHour');
                    var mEl = document.getElementById('editStorySchedMinute');
                    var hiddenEl = document.getElementById('editStoryScheduledTime');
                    var prevEl = document.getElementById('editStorySchedPreview');

                    if (!dEl || !hEl || !mEl || !hiddenEl) return;
                    var dVal = dEl.value;
                    var hVal = hEl.value;
                    var mVal = mEl.value;
                    if (!dVal) return;

                    hiddenEl.value = dVal + ' ' + hVal + ':' + mVal + ':00';

                    var dParts = dVal.split('-');
                    if (dParts.length === 3 && prevEl) {
                        var dateFmt = dParts[2] + '/' + dParts[1] + '/' + dParts[0];
                        prevEl.innerHTML = '<i class="fa-solid fa-clock"></i> <span>Thời gian hẹn: <strong>' + hVal + ':' + mVal + ' ngày ' + dateFmt + '</strong> (Giờ Việt Nam)</span>';
                    }
                }

                function muopToggleEditScheduleBox(isChecked) {
                    var collapse = document.getElementById('editScheduleTimeCollapse');
                    if (collapse) {
                        collapse.style.display = isChecked ? 'block' : 'none';
                    }
                    var timeInput = document.getElementById('editStoryScheduledTime');
                    if (isChecked && (!timeInput || !timeInput.value)) {
                        muopSetQuickEditSchedule(3, null);
                    }
                }

                function muopSetQuickEditSchedule(hours, preset) {
                    var d = new Date();
                    if (preset === 'tonight') {
                        d.setHours(20, 0, 0, 0);
                        if (d.getTime() <= Date.now()) d.setDate(d.getDate() + 1);
                    } else if (preset === 'tomorrow_morning') {
                        d.setDate(d.getDate() + 1);
                        d.setHours(8, 0, 0, 0);
                    } else if (hours) {
                        d.setHours(d.getHours() + parseInt(hours, 10));
                        var m = Math.ceil(d.getMinutes() / 5) * 5;
                        if (m >= 60) {
                            d.setHours(d.getHours() + 1);
                            m = 0;
                        }
                        d.setMinutes(m, 0, 0);
                    }

                    var dEl = document.getElementById('editStorySchedDate');
                    var hEl = document.getElementById('editStorySchedHour');
                    var mEl = document.getElementById('editStorySchedMinute');

                    if (dEl) dEl.value = d.getFullYear() + '-' + muopPad2Edit(d.getMonth() + 1) + '-' + muopPad2Edit(d.getDate());
                    if (hEl) hEl.value = muopPad2Edit(d.getHours());
                    if (mEl) {
                        var rM = Math.floor(d.getMinutes() / 5) * 5;
                        if (rM >= 60) rM = 55;
                        mEl.value = muopPad2Edit(rM);
                    }

                    muopSyncEditStorySchedule();

                    var chk = document.getElementById('editToggleScheduleStory');
                    if (chk && !chk.checked) {
                        chk.checked = true;
                        muopToggleEditScheduleBox(true);
                    }
                }
                </script>

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
                <input type="hidden" id="editChapterStoryId" name="story_id" value="" />
                <input type="hidden" id="editChapterId" name="chapter_id" value="" />
                <input type="hidden" id="editChapterIsDraftInput" name="is_draft" value="0" />
                <input type="hidden" name="action" value="muop_update_chapter" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
                <input type="hidden" name="is_admin_portal" value="<?php echo (is_page('quan-ly-admin') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'quan-ly-admin') !== false)) ? '1' : '0'; ?>" />

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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" for="editChapterContent" style="margin-bottom: 0;">Nội Dung Chương (*)</label>
                        <button type="button" class="btn btn-outline btn-scan-sensitive btn-sm" data-target="#editChapterContent" style="padding: 4px 12px; font-size: 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Duyệt Nội Dung
                        </button>
                    </div>
                    <textarea id="editChapterContent" name="content" class="form-input form-textarea-chapter" rows="14" style="min-height: 340px; font-size: 14.5px; line-height: 1.65; padding: 12px 14px;" required placeholder="Nội dung chương truyện..."></textarea>
                    
                    <!-- Thanh hiển thị số từ thời gian thực -->
                    <div class="chapter-word-count-bar" style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; flex-wrap: wrap; gap: 8px;">
                        <span style="font-size: 12.5px; color: var(--text-muted);">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Hệ thống tự động đếm số từ khi bạn chỉnh sửa.
                        </span>
                        <div class="chapter-word-count-badge" style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 5px 14px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; color: var(--text-main); display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <i class="fa-solid fa-pen-nib" style="color: var(--primary-green);"></i>
                            <span>Số từ: <strong id="editChapterContentWordCount" style="color: var(--primary-green); font-size: 14.5px; font-weight: 700;">0</strong> từ</span>
                            <span style="color: var(--border-color); margin: 0 2px;">|</span>
                            <span style="font-size: 12px; color: var(--text-muted);"><span id="editChapterContentCharCount">0</span> ký tự</span>
                        </div>
                    </div>
                <!-- Mục Quản Lý Audio & Voice Clone TTS Khi Chỉnh Sửa Chương -->
                <div class="form-group" style="margin-top: 18px; padding-top: 16px; border-top: 1px dashed var(--border-color); margin-bottom: 18px;">
                    <label class="form-label" style="font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--primary-green); margin-bottom: 6px;">
                        <i class="fa-solid fa-headphones"></i>
                        <span>Audio Chương & Chuyển Giọng Nói (Bắt Chước Giọng Mẫu)</span>
                    </label>

                    <!-- Khung Audio hiện tại của chương (nếu có) -->
                    <div id="editChapterAudioCurrentBox" style="display: none; background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span style="font-size: 13px; font-weight: 700; color: #166534; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-file-audio" style="color: #16a34a;"></i> Audio hiện tại:
                            </span>
                            <audio id="editChapterAudioCurrentEl" controls style="height: 32px; max-width: 280px;"></audio>
                        </div>
                        <button type="button" id="btnDeleteCurrentChapterAudio" class="btn btn-outline btn-xs" style="color: #dc2626; border-color: #fecaca; padding: 4px 10px; font-size: 11.5px;" title="Xóa audio cũ khỏi máy chủ">
                            <i class="fa-solid fa-trash-can"></i> Xóa Audio Này
                        </button>
                    </div>

                    <!-- Khung Chuyển đổi Văn Bản Sang Giọng Nói (Voice Clone TTS) -->
                    <div style="background: var(--bg-card); border: 1.5px dashed #86efac; border-radius: var(--radius-sm); padding: 14px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; flex-wrap: wrap; gap: 6px;">
                            <span style="font-weight: 700; font-size: 13px; color: #166534; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-wand-magic-sparkles" style="color: #16a34a;"></i> Chuyển đổi văn bản thành audio mới (Bắt chước giọng mẫu):
                            </span>
                            <span class="badge" style="background: #16a34a; color: #fff; font-size: 10.5px; padding: 2px 6px; border-radius: 4px;">Voice Clone Siêu Tốc</span>
                        </div>
                        <p style="font-size: 11.5px; color: var(--text-muted); margin: 0 0 10px 0; line-height: 1.5;">
                            Bạn có thể chọn giọng mẫu đã lưu trong kho cá nhân của bạn hoặc tải lên giọng mới (mỗi dịch giả dùng kho giọng riêng biệt của mình, file mới tải lên sẽ tự động lưu để dùng lại).
                        </p>

                        <!-- Lựa chọn 1: Chọn từ Kho Giọng Mẫu Đã Lưu Của Dịch Giả -->
                        <div id="editChapterSavedVoiceGroup" style="margin-bottom: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 12px;">
                            <label style="font-size: 12px; font-weight: 700; color: #166534; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                <i class="fa-solid fa-bookmark" style="color: #16a34a;"></i> 1. Dùng lại giọng mẫu đã lưu của bạn:
                            </label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <select id="editChapterSavedVoiceSelect" class="form-input" style="flex: 1; padding: 5px 8px; font-size: 12px; height: 34px; background: #fff;">
                                    <option value="">-- Đang nạp danh sách giọng mẫu của bạn... --</option>
                                </select>
                                <button type="button" id="btnPreviewSavedVoiceEditModal" class="btn btn-outline btn-xs" style="height: 34px; padding: 0 10px; font-size: 11.5px; border-color: #86efac; color: #16a34a; white-space: nowrap;" title="Nghe thử giọng mẫu này">
                                    <i class="fa-solid fa-play"></i> Nghe thử
                                </button>
                                <button type="button" id="btnDeleteSavedVoiceEditModal" class="btn btn-outline btn-xs" style="height: 34px; padding: 0 8px; font-size: 11.5px; border-color: #fecaca; color: #dc2626;" title="Xóa giọng mẫu này khỏi kho của bạn">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                            <audio id="editSavedVoiceAudioPreview" style="display: none;"></audio>
                        </div>

                        <!-- Lựa chọn 2: Tải lên file giọng mẫu mới -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; align-items: flex-end;">
                            <div>
                                <label class="form-label" for="editChapterRefVoiceFile" style="font-size: 12px; font-weight: 600; margin-bottom: 4px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Hoặc tải lên giọng mẫu mới (.mp3, .wav):
                                </label>
                                <input type="file" id="editChapterRefVoiceFile" accept="audio/*,.mp3,.wav,.m4a" class="form-input" style="padding: 5px 8px; font-size: 12px; height: 36px;" />
                            </div>
                            <div>
                                <button type="button" id="btnConvertTtsInEditChapter" class="btn btn-primary" style="height: 36px; width: 100%; font-size: 12.5px; font-weight: 700; background: #16a34a; border-color: #15803d; display: inline-flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 2px 8px rgba(22, 163, 74, 0.25);">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Bấm Chuyển Đổi Audio Mới
                                </button>
                            </div>
                        </div>

                        <!-- Thẻ kết quả phân tích chất giọng -->
                        <div id="editChapterVoiceAnalysisCard" style="display: none; margin-top: 10px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 8px 12px; font-size: 12px; color: #065f46; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-microphone-lines" style="color: #10b981; font-size: 14px;"></i>
                            <span id="editChapterVoiceAnalysisText">Đang phân tích giọng...</span>
                        </div>

                        <div id="editChapterTtsAlert" class="alert-box" style="display: none; margin-top: 10px; font-size: 12px; padding: 8px 12px;"></div>
                    </div>

                    <!-- Tải trực tiếp file Audio từ máy tính lên web -->
                    <div style="margin-bottom: 4px;">
                        <label class="form-label" for="editChapterUploadAudioDirect" style="font-size: 12px; font-weight: 600; margin-bottom: 4px;">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Hoặc tải trực tiếp file Audio từ máy tính lên web:
                        </label>
                        <input type="file" id="editChapterUploadAudioDirect" name="chapter_audio" class="form-input" accept="audio/*,.mp3,.m4a,.wav,.ogg,.aac" style="padding: 5px 8px; font-size: 12px; height: 36px;" />
                        <small style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 3px;">
                            <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> Khi tải audio mới từ máy lên, file audio mới sẽ chèn lên file cũ và file cũ trên máy chủ sẽ bị xóa luôn.
                        </small>
                    </div>

                    <!-- Inputs ẩn điều khiển audio -->
                    <input type="hidden" id="editChapterAudioUrlHidden" name="chapter_audio_url" value="" />
                    <input type="hidden" id="editChapterDeleteAudioHidden" name="delete_chapter_audio" value="0" />
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

<!-- =========================================================================
     POPUP 4: QUẢN LÝ DANH SÁCH XÓA CHƯƠNG TRUYỆN (#modalManageDeleteChapters)
========================================================================== -->
<div id="modalManageDeleteChapters" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 620px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label" style="background: #fee2e2; color: #dc2626; border-color: #fecaca;">
                    <i class="fa-solid fa-rectangle-xmark"></i> Xóa Chương Truyện
                </span>
                <h3 class="modal-story-title-sub" id="modalDeleteChaptersStoryTitle">...</h3>
            </div>
            <button type="button" id="btnCloseManageDeleteChaptersModal" class="btn-close-modal-delete-chapters" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #92400e; display: flex; align-items: flex-start; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 16px; margin-top: 1px; color: #d97706;"></i>
                <div>
                    Chọn chương bạn muốn xóa bên dưới. <strong>Lưu ý:</strong> Hành động xóa chương là vĩnh viễn và không thể khôi phục!
                </div>
            </div>

            <div id="deleteChaptersLoading" style="text-align: center; padding: 30px; color: var(--text-muted); display: none;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: var(--primary-green); margin-bottom: 8px; display: block;"></i>
                Đang tải danh sách chương...
            </div>

            <div id="deleteChaptersEmpty" style="text-align: center; padding: 30px; background: var(--pastel-green); border-radius: var(--radius-sm); color: var(--text-muted); display: none;">
                <i class="fa-solid fa-folder-open" style="font-size: 24px; color: var(--primary-green); margin-bottom: 8px; display: block;"></i>
                Truyện này hiện chưa có chương nào để xóa.
            </div>

            <div id="deleteChaptersList" style="display: flex; flex-direction: column; gap: 8px; max-height: 400px; overflow-y: auto; padding-right: 4px;">
                <!-- Danh sách chương nạp động qua AJAX -->
            </div>
        </div>
        <div style="padding: 12px 20px; background: var(--bg-body); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary btn-close-modal-delete-chapters" style="padding: 6px 18px; font-size: 13px;">
                Đóng
            </button>
        </div>
    </div>
</div>
