<?php
/**
 * Template Name: Trang Đăng Truyện
 * Dành cho Dịch Giả & Quản Trị Viên
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || !(function_exists('muop_is_translator_or_admin') ? muop_is_translator_or_admin() : (current_user_can('dich_gia') || current_user_can('administrator')))) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$all_genres = get_terms(array('taxonomy' => 'the_loai', 'hide_empty' => false));
?>

<div class="container">
    <div class="dashboard-wrapper" style="max-width: 860px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">Kênh Dịch Giả</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Đăng Truyện Mới</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-feather-pointed"></i> Đăng Truyện Mới Vào Hệ Thống
            </h1>
        </div>

        <div id="storyAlert" class="alert-box"></div>

        <form id="formSubmitStory" enctype="multipart/form-data">
            <input type="hidden" name="action" value="muop_submit_story" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
            <input type="hidden" name="muop_user_id" value="<?php echo esc_attr($current_user->ID); ?>" />
            <!-- CENTER 1: THÔNG TIN TRUYỆN -->
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                    1. Thông Tin Cơ Bản Của Truyện
                </h3>

                <div class="form-group">
                    <label class="form-label" for="storyTitle">Tên Truyện (*)</label>
                    <input type="text" id="storyTitle" name="title" class="form-input" placeholder="Ví dụ: Vị Ngọt Mướp Đắng, Sau Khi Chia Tay Em Trở Thành Nữ Thần..." required />
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="storyAuthor">Tác Giả</label>
                        <input type="text" id="storyAuthor" name="author_name" class="form-input" placeholder="Tên tác giả gốc (nếu có)" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="storyTeam">Team (Dịch Giả)</label>
                        <input type="text" id="storyTeam" name="team_name" class="form-input" value="<?php echo esc_attr(current_user_can('administrator') ? 'Mướp Đắng Cũng Có Vị Ngọt' : $current_user->display_name); ?>" placeholder="Tên team dịch" <?php echo current_user_can('administrator') ? 'readonly style="background: rgba(0,0,0,0.04); cursor: not-allowed;" title="Admin luôn đăng với vai trò team Mướp Đắng Cũng Có Vị Ngọt"' : ''; ?> />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="storyStatus">Trạng Thái</label>
                        <select id="storyStatus" name="status" class="form-input">
                            <option value="dang_ra">Đang ra (Tiếp tục dịch)</option>
                            <option value="hoan_thanh">Hoàn thành (Đã Full)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="storyCover">Ảnh Bìa</label>
                        <input type="file" id="storyCover" name="cover_image" accept="image/*" style="display: none;" />
                        <div class="custom-cover-upload-area" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <button type="button" id="btnUploadCover" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px; border-radius: var(--radius-sm); display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị
                            </button>
                            <div id="coverUploadInfo" style="display: none; align-items: center; gap: 8px; font-size: 13px; background: var(--bg-card); padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                                <span id="coverFileName" style="font-weight: 600; color: var(--text-main); max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                                <span class="badge badge-green" style="font-size: 11px;">Đã tải lên</span>
                                <button type="button" id="btnRemoveCover" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 2px 4px; font-size: 14px; margin-left: 2px;" title="Xóa ảnh này">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label class="form-label" style="margin-bottom: 0;">Chọn Thể Loại (Có thể chọn nhiều)</label>
                        <button type="button" id="btnToggleAddNewGenre" class="btn btn-secondary" style="padding: 5px 12px; font-size: 12.5px; border-radius: 6px;">
                            <i class="fa-solid fa-plus"></i> Thêm Thể Loại Mới
                        </button>
                    </div>

                    <!-- Hộp thêm thể loại mới trực tiếp (chỉ xuất hiện phần nhập, ẩn khi không cần nữa chỉ hiện button) -->
                    <div id="boxAddNewGenre" style="display: none; margin-bottom: 12px; padding: 10px 14px; background: var(--bg-card); border: 1.5px solid var(--primary-green); border-radius: var(--radius-sm); box-shadow: var(--shadow-sm);">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="inputNewGenreName" class="form-input" placeholder="Nhập tên thể loại mới (vd: Trinh thám, Kinh dị...)" style="flex: 1; padding: 6px 12px; font-size: 13px; height: 34px;" />
                            <button type="button" id="btnConfirmAddGenre" class="btn btn-primary" style="padding: 6px 14px; font-size: 12.5px; height: 34px; white-space: nowrap;">
                                <i class="fa-solid fa-check"></i> Lưu Thể Loại
                            </button>
                            <button type="button" id="btnCancelAddGenre" class="btn btn-outline" style="padding: 6px 10px; font-size: 12.5px; height: 34px;" title="Hủy / Đóng">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div id="addGenreStatus" style="font-size: 12px; margin-top: 4px; display: none;"></div>
                    </div>

                    <!-- Ô tìm kiếm thể loại -->
                    <div style="margin-bottom: 10px; position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12.5px;"></i>
                        <input type="text" id="inputSearchGenre" class="form-input" placeholder="Nhập tìm thể loại..." style="padding-left: 32px; padding-top: 6px; padding-bottom: 6px; font-size: 13px; height: 34px; border-radius: var(--radius-sm);" />
                    </div>

                    <div id="genresCheckboxList" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; max-height: 180px; overflow-y: auto; background: var(--bg-card); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                        <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                            <?php foreach ($all_genres as $genre) : ?>
                                <label class="genre-checkbox-item" data-name="<?php echo esc_attr(mb_strtolower($genre->name, 'UTF-8')); ?>" style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="checkbox" name="categories[]" value="<?php echo esc_attr($genre->term_id); ?>" />
                                    <span class="genre-checkbox-name"><?php echo esc_html($genre->name); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div id="noGenreFoundMsg" style="display: none; font-size: 12.5px; color: var(--text-muted); padding: 8px 12px; text-align: center; background: var(--bg-card); border-radius: var(--radius-sm); margin-top: 4px;">
                        Không tìm thấy thể loại phù hợp.
                    </div>
                </div>
            </div>

            <!-- CENTER 2: GIỚI THIỆU (MÔ TẢ) -->
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary-green); margin-bottom: 16px;">
                    2. Giới Thiệu / Văn Án Truyện
                </h3>

                <div class="form-group" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" for="storyDescription" style="margin-bottom: 0;">Nội Dung Giới Thiệu / Văn Án (*)</label>
                        <button type="button" class="btn btn-outline btn-scan-sensitive btn-sm" data-target="#storyDescription" style="padding: 5px 14px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;" title="Rà soát và thay thế các từ ngữ nhạy cảm trong văn án">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Duyệt Nội Dung
                        </button>
                    </div>
                    <textarea id="storyDescription" name="description" class="form-input form-textarea-large" rows="12" style="min-height: 240px; font-size: 15px; line-height: 1.7; padding: 14px 16px;" placeholder="Nhập phần tóm tắt văn án hấp dẫn để thu hút độc giả..." required></textarea>
                </div>
            </div>

            <!-- CENTER 2.5: TÙY CHỌN HẸN GIỜ ĐĂNG TRUYỆN -->
            <div class="schedule-story-card" style="background: var(--bg-card); padding: 18px 20px; border-radius: var(--radius-md); border: 1.5px dashed #8b5cf6; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 0;">
                        <input type="checkbox" id="toggleScheduleStory" name="enable_schedule" value="1" onchange="muopToggleScheduleBox(this.checked)" style="width: 18px; height: 18px; accent-color: #7c3aed; cursor: pointer;" />
                        <span style="font-weight: 700; font-size: 14.5px; color: #6d28d9;">
                            <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Hẹn Giờ Đăng Truyện
                        </span>
                    </label>
                    <span class="badge" style="background: #f5f3ff; color: #7c3aed; font-size: 12px; border: 1px solid #ddd6fe;">
                        Tự động phát hành theo lịch
                    </span>
                </div>

                <div id="scheduleTimeCollapse" style="display: none; margin-top: 14px; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                    <div style="margin-bottom: 12px;">
                        <label class="form-label" style="font-weight: 700; font-size: 13.5px; color: #6d28d9; margin-bottom: 8px; display: block;">
                            <i class="fa-solid fa-calendar-days"></i> Chọn ngày & giờ đăng truyện (*):
                        </label>
                        
                        <!-- Bộ chọn Ngày và 24 Giờ chuẩn Việt Nam (Không AM/PM) -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 8px;">
                            <!-- Ngày -->
                            <div style="flex: 1.2; min-width: 140px;">
                                <input type="date" id="storySchedDate" class="form-input" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" onchange="muopSyncDangTruyenSchedule()" style="height: 38px; font-size: 13.5px; font-weight: 600;" />
                            </div>

                            <!-- Giờ (24 giờ: 00 đến 23) -->
                            <div style="flex: 1; min-width: 110px;">
                                <select id="storySchedHour" class="form-input" onchange="muopSyncDangTruyenSchedule()" style="height: 38px; font-size: 13.5px; font-weight: 600;">
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
                            <div style="flex: 0.9; min-width: 95px;">
                                <select id="storySchedMinute" class="form-input" onchange="muopSyncDangTruyenSchedule()" style="height: 38px; font-size: 13.5px; font-weight: 600;">
                                    <?php for ($m = 0; $m < 60; $m += 5): 
                                        $m_str = str_pad($m, 2, '0', STR_PAD_LEFT);
                                    ?>
                                        <option value="<?php echo $m_str; ?>"><?php echo $m_str . ' phút'; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Input ẩn gửi dữ liệu chuẩn YYYY-MM-DD HH:MM:00 về backend -->
                        <input type="hidden" id="storyScheduledTime" name="scheduled_time" value="" />

                        <!-- Xem trước thời gian hẹn -->
                        <div id="storySchedPreview" style="font-size: 12.5px; font-weight: 700; color: #6d28d9; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 6px; padding: 6px 12px; margin-bottom: 12px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-clock"></i> <span>Thời gian chọn: --:-- ngày --/--/----</span>
                        </div>
                    </div>

                    <!-- Gợi ý chọn nhanh thời gian -->
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                        <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 600;">Gợi ý nhanh:</span>
                        <button type="button" class="btn btn-outline btn-quick-sched" data-hours="1" onclick="muopSetQuickSchedule(1, null)" style="padding: 4px 10px; font-size: 12px; border-radius: 6px;">+1 Giờ</button>
                        <button type="button" class="btn btn-outline btn-quick-sched" data-hours="3" onclick="muopSetQuickSchedule(3, null)" style="padding: 4px 10px; font-size: 12px; border-radius: 6px;">+3 Giờ</button>
                        <button type="button" class="btn btn-outline btn-quick-sched" data-preset="tonight" onclick="muopSetQuickSchedule(null, 'tonight')" style="padding: 4px 10px; font-size: 12px; border-radius: 6px;">Tối nay (20:00)</button>
                        <button type="button" class="btn btn-outline btn-quick-sched" data-preset="tomorrow_morning" onclick="muopSetQuickSchedule(null, 'tomorrow_morning')" style="padding: 4px 10px; font-size: 12px; border-radius: 6px;">Sáng mai (08:00)</button>
                    </div>

                    <p style="font-size: 12.5px; color: var(--text-muted); margin: 10px 0 0 0;">
                        <i class="fa-solid fa-circle-info" style="color: #8b5cf6;"></i> Truyện sẽ được lưu ở trạng thái <strong>Đã hẹn giờ</strong> và sẽ tự động chuyển thành công khai đúng mốc thời gian bạn đã chọn.
                    </p>
                </div>
            </div>

            <script>
            function muopPad2(n) { return String(n).padStart(2, '0'); }

            function muopSyncDangTruyenSchedule() {
                var dEl = document.getElementById('storySchedDate');
                var hEl = document.getElementById('storySchedHour');
                var mEl = document.getElementById('storySchedMinute');
                var hiddenEl = document.getElementById('storyScheduledTime');
                var prevEl = document.getElementById('storySchedPreview');

                if (!dEl || !hEl || !mEl || !hiddenEl) return;
                var dVal = dEl.value;
                var hVal = hEl.value;
                var mVal = mEl.value;
                if (!dVal) return;

                hiddenEl.value = dVal + ' ' + hVal + ':' + mVal + ':00';

                var dParts = dVal.split('-');
                if (dParts.length === 3 && prevEl) {
                    var dateFmt = dParts[2] + '/' + dParts[1] + '/' + dParts[0];
                    prevEl.innerHTML = '<i class="fa-solid fa-clock"></i> <span>Dự kiến đăng: <strong>' + hVal + ':' + mVal + ' ngày ' + dateFmt + '</strong> (Giờ Việt Nam)</span>';
                }
            }

            function muopToggleScheduleBox(isChecked) {
                var collapse = document.getElementById('scheduleTimeCollapse');
                if (collapse) {
                    collapse.style.display = isChecked ? 'block' : 'none';
                }
                var btnText = document.getElementById('btnSubmitStoryText');
                if (btnText) {
                    btnText.textContent = isChecked ? 'Lên Lịch Đăng' : 'Đăng Truyện';
                }
                var btnIcon = document.getElementById('btnSubmitStoryIcon');
                if (btnIcon) {
                    if (isChecked) {
                        btnIcon.classList.remove('fa-cloud-arrow-up');
                        btnIcon.classList.add('fa-clock');
                    } else {
                        btnIcon.classList.remove('fa-clock');
                        btnIcon.classList.add('fa-cloud-arrow-up');
                    }
                }
                var timeInput = document.getElementById('storyScheduledTime');
                if (isChecked && (!timeInput || !timeInput.value)) {
                    muopSetQuickSchedule(3, null);
                }
            }

            function muopSetQuickSchedule(hours, preset) {
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

                var dEl = document.getElementById('storySchedDate');
                var hEl = document.getElementById('storySchedHour');
                var mEl = document.getElementById('storySchedMinute');

                if (dEl) dEl.value = d.getFullYear() + '-' + muopPad2(d.getMonth() + 1) + '-' + muopPad2(d.getDate());
                if (hEl) hEl.value = muopPad2(d.getHours());
                if (mEl) {
                    var rM = Math.floor(d.getMinutes() / 5) * 5;
                    if (rM >= 60) rM = 55;
                    mEl.value = muopPad2(rM);
                }

                muopSyncDangTruyenSchedule();

                var chk = document.getElementById('toggleScheduleStory');
                if (chk && !chk.checked) {
                    chk.checked = true;
                    muopToggleScheduleBox(true);
                }
            }
            </script>

            <input type="hidden" name="is_draft" id="isStoryDraftInput" value="0" />

            <!-- CENTER 3: BUTTONS THÊM, LƯU NHÁP & HỦY -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex-wrap: wrap;">
                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Hủy
                </a>
                <button type="button" id="btnSaveDraftNewStory" class="btn btn-outline" style="padding: 10px 22px; font-size: 14.5px;" title="Lưu thành bản nháp để hoàn thiện và đăng sau">
                    <i class="fa-solid fa-file-pen"></i> Lưu Nháp
                </button>
                <button type="submit" id="btnSubmitPublishStory" class="btn btn-primary" style="padding: 10px 28px; font-size: 15px;">
                    <i class="fa-solid fa-cloud-arrow-up" id="btnSubmitStoryIcon"></i> <span id="btnSubmitStoryText">Đăng Truyện</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
