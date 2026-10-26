<?php
/**
 * Template Part: Modal Popup Xem Chi Tiết & Quản Lý Lịch Hẹn Giờ Đăng Truyện
 * Dành cho Dịch Giả & Quản Trị Viên khi nhấn vào trạng thái "Đã hẹn giờ"
 */
if (!defined('ABSPATH')) exit;
if (defined('MUOP_MODAL_SCHEDULE_STORY_LOADED')) return;
define('MUOP_MODAL_SCHEDULE_STORY_LOADED', true);
?>

<div id="modalScheduleStoryDetail" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 560px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1.5px solid #8b5cf6; overflow: hidden; display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div class="modal-header-top-box" style="background: linear-gradient(135deg, #f5f3ff, var(--bg-card)); border-bottom: 1px solid #ede9fe; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div class="modal-title-stacked">
                <span class="modal-action-label" style="color: #7c3aed; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: block;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #8b5cf6;"></i> Lịch Phát Hành Truyện
                </span>
                <h3 class="modal-story-title-sub" id="scheduleModalStoryTitle" style="font-size: 16px; font-weight: 700; color: var(--text-main); margin: 3px 0 0 0; line-height: 1.4;">
                    ...
                </h3>
            </div>
            <button type="button" class="btn-close-schedule-modal" onclick="window.muopCloseScheduleModal && window.muopCloseScheduleModal()" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 22px 20px; overflow-y: auto; flex: 1;">
            <div id="scheduleDetailAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <!-- Khung hiển thị thời gian hẹn giờ -->
            <div style="background: linear-gradient(135deg, #f5f3ff, #faf5ff); border: 1px solid #ddd6fe; border-radius: var(--radius-md); padding: 18px; text-align: center; margin-bottom: 18px; box-shadow: inset 0 1px 3px rgba(124, 58, 237, 0.05);">
                <div style="font-size: 12px; color: #6d28d9; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    <i class="fa-solid fa-calendar-check" style="margin-right: 4px;"></i> Thời Gian Được Lên Lịch Đăng (Giờ Việt Nam):
                </div>
                <div id="scheduleModalDatetimeText" style="font-size: 22px; font-weight: 800; color: #5b21b6; margin-bottom: 10px; font-family: system-ui, -apple-system, sans-serif;">
                    --:-- - --/--/----
                </div>
                
                <!-- Đồng hồ đếm ngược -->
                <div id="scheduleModalCountdownText" style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; color: #7c3aed; border: 1px solid #c4b5fd; padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 700; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <i class="fa-solid fa-hourglass-half fa-spin" style="font-size: 11px;"></i> <span>Đang tính thời gian...</span>
                </div>
            </div>

            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin: 0 0 18px 0; background: var(--bg-body); padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-circle-info" style="color: #8b5cf6; margin-right: 4px;"></i> 
                Khi đến thời điểm trên, hệ thống sẽ <strong>tự động chuyển trạng thái truyện sang công khai</strong> tới toàn bộ độc giả và thông báo đến người theo dõi.
            </p>

            <!-- Khung Form Đổi Giờ Hẹn Nhanh (Mặc định ẩn, mở khi bấm Đổi giờ) -->
            <div id="scheduleChangeFormWrap" style="display: none; background: var(--bg-body); padding: 16px 18px; border-radius: var(--radius-sm); border: 1.5px dashed #8b5cf6; margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; font-size: 13.5px; color: #6d28d9; margin-bottom: 8px; display: block;">
                    <i class="fa-solid fa-clock"></i> Chọn ngày & giờ hẹn mới (Chuẩn 24 Giờ Việt Nam):
                </label>

                <!-- Bộ chọn ngày và 24 giờ Việt Nam (Không AM/PM) -->
                <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 8px;">
                    <!-- Ngày -->
                    <div style="flex: 1.2; min-width: 140px;">
                        <input type="date" id="popupSchedDate" class="form-input" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" onchange="window.muopSyncPopupScheduleTime && window.muopSyncPopupScheduleTime()" style="height: 38px; font-size: 13.5px; font-weight: 600;" />
                    </div>

                    <!-- Giờ (24 giờ chuẩn: 00 đến 23) -->
                    <div style="flex: 1; min-width: 110px;">
                        <select id="popupSchedHour" class="form-input" onchange="window.muopSyncPopupScheduleTime && window.muopSyncPopupScheduleTime()" style="height: 38px; font-size: 13.5px; font-weight: 600;">
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

                    <!-- Phút (00 đến 55, bước nhảy 5 phút) -->
                    <div style="flex: 0.9; min-width: 95px;">
                        <select id="popupSchedMinute" class="form-input" onchange="window.muopSyncPopupScheduleTime && window.muopSyncPopupScheduleTime()" style="height: 38px; font-size: 13.5px; font-weight: 600;">
                            <?php for ($m = 0; $m < 60; $m += 5): 
                                $m_str = str_pad($m, 2, '0', STR_PAD_LEFT);
                            ?>
                                <option value="<?php echo $m_str; ?>"><?php echo $m_str . ' phút'; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- Input ẩn lưu giá trị YYYY-MM-DD HH:MM:00 gửi lên Server -->
                <input type="hidden" id="inputQuickChangeScheduleTime" value="" />

                <!-- Text xem trước thời gian hẹn thực tế -->
                <div id="popupSchedPreview" style="font-size: 12.5px; font-weight: 700; color: #6d28d9; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 6px; padding: 6px 12px; margin-bottom: 12px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-clock"></i> <span>Thời gian chọn: --:-- ngày --/--/----</span>
                </div>
                
                <!-- Gợi ý chọn nhanh thời gian -->
                <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center; margin-bottom: 14px;">
                    <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Chọn nhanh:</span>
                    <button type="button" class="btn btn-outline" onclick="window.muopSetPopupQuickSchedule && window.muopSetPopupQuickSchedule(1, null)" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">+1 Giờ</button>
                    <button type="button" class="btn btn-outline" onclick="window.muopSetPopupQuickSchedule && window.muopSetPopupQuickSchedule(3, null)" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">+3 Giờ</button>
                    <button type="button" class="btn btn-outline" onclick="window.muopSetPopupQuickSchedule && window.muopSetPopupQuickSchedule(null, 'tonight')" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">Tối nay (20:00)</button>
                    <button type="button" class="btn btn-outline" onclick="window.muopSetPopupQuickSchedule && window.muopSetPopupQuickSchedule(null, 'tomorrow_morning')" style="padding: 3px 8px; font-size: 11.5px; border-radius: 4px;">Sáng mai (08:00)</button>
                </div>

                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" id="btnCancelToggleChangeSchedule" onclick="document.getElementById('scheduleChangeFormWrap').style.display = 'none';" class="btn btn-secondary" style="padding: 6px 14px; font-size: 12.5px;">Hủy</button>
                    <button type="button" id="btnSubmitChangeScheduleTime" class="btn btn-primary" style="padding: 6px 16px; font-size: 12.5px; background: #7c3aed; border-color: #6d28d9;">
                        <i class="fa-solid fa-check"></i> Lưu Giờ Hẹn Mới
                    </button>
                </div>
            </div>

            <!-- Footer Buttons: Các tác vụ nhanh -->
            <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; align-items: center; border-top: 1px solid var(--border-color); padding-top: 16px;">
                <button type="button" class="btn btn-secondary btn-close-schedule-modal" onclick="window.muopCloseScheduleModal && window.muopCloseScheduleModal()" style="padding: 7px 16px; font-size: 13px;">
                    Đóng
                </button>
                <button type="button" id="btnTriggerToggleChangeSchedule" onclick="var w = document.getElementById('scheduleChangeFormWrap'); if(w) { w.style.display = (w.style.display==='none'?'block':'none'); }" class="btn btn-outline" style="padding: 7px 14px; font-size: 13px; color: #7c3aed; border-color: #c4b5fd;">
                    <i class="fa-solid fa-pen-to-square"></i> Đổi Giờ Hẹn
                </button>
                <button type="button" id="btnPopupCancelScheduleToDraft" class="btn btn-outline" style="padding: 7px 14px; font-size: 13px; color: #b45309; border-color: #fde68a;" title="Hủy hẹn giờ và chuyển truyện về mục Bản Nháp">
                    <i class="fa-solid fa-file-pen"></i> Về Bản Nháp
                </button>
                <button type="button" id="btnPopupPublishScheduledNow" class="btn btn-primary" style="padding: 7px 16px; font-size: 13px;">
                    <i class="fa-solid fa-bolt"></i> Đăng Ngay
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var timerId = null;

    function pad2(n) {
        return String(n).padStart(2, '0');
    }

    // Đồng bộ giá trị Ngày + Giờ + Phút vào input ẩn & preview
    window.muopSyncPopupScheduleTime = function() {
        var dateEl = document.getElementById('popupSchedDate');
        var hourEl = document.getElementById('popupSchedHour');
        var minEl  = document.getElementById('popupSchedMinute');
        var hiddenEl = document.getElementById('inputQuickChangeScheduleTime');
        var prevEl = document.getElementById('popupSchedPreview');

        if (!dateEl || !hourEl || !minEl || !hiddenEl) return;

        var dVal = dateEl.value;
        var hVal = hourEl.value;
        var mVal = minEl.value;

        if (!dVal) return;

        var combined = dVal + ' ' + hVal + ':' + mVal + ':00';
        hiddenEl.value = combined;

        // Định dạng preview theo kiểu Việt Nam: HH:mm ngày dd/mm/yyyy
        var dParts = dVal.split('-');
        if (dParts.length === 3 && prevEl) {
            var dateFmt = dParts[2] + '/' + dParts[1] + '/' + dParts[0];
            prevEl.innerHTML = '<i class="fa-solid fa-clock"></i> <span>Thời gian chọn: <strong>' + hVal + ':' + mVal + ' ngày ' + dateFmt + '</strong> (Giờ Việt Nam)</span>';
        }
    };

    // Chọn nhanh thời gian (+1h, +3h, Tối nay 20h, Sáng mai 8h)
    window.muopSetPopupQuickSchedule = function(hours, preset) {
        var d = new Date();
        if (preset === 'tonight') {
            d.setHours(20, 0, 0, 0);
            if (d.getTime() <= Date.now()) {
                d.setDate(d.getDate() + 1);
            }
        } else if (preset === 'tomorrow_morning') {
            d.setDate(d.getDate() + 1);
            d.setHours(8, 0, 0, 0);
        } else if (hours) {
            d.setHours(d.getHours() + parseInt(hours, 10));
            // Làm tròn phút lên bội số của 5
            var m = Math.ceil(d.getMinutes() / 5) * 5;
            if (m >= 60) {
                d.setHours(d.getHours() + 1);
                m = 0;
            }
            d.setMinutes(m, 0, 0);
        }

        var dateEl = document.getElementById('popupSchedDate');
        var hourEl = document.getElementById('popupSchedHour');
        var minEl  = document.getElementById('popupSchedMinute');

        if (dateEl) {
            dateEl.value = d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate());
        }
        if (hourEl) {
            hourEl.value = pad2(d.getHours());
        }
        if (minEl) {
            var roundedMin = Math.floor(d.getMinutes() / 5) * 5;
            if (roundedMin >= 60) roundedMin = 55;
            minEl.value = pad2(roundedMin);
        }

        window.muopSyncPopupScheduleTime();
    };

    // Hàm đếm ngược
    function startPopupCountdown(timeStr) {
        if (timerId) clearInterval(timerId);
        var countEl = document.getElementById('scheduleModalCountdownText');
        if (!countEl) return;

        if (!timeStr) {
            countEl.innerHTML = '<i class="fa-solid fa-circle-info"></i> Chưa có thời gian cụ thể';
            return;
        }

        function update() {
            var target = new Date(timeStr.replace(/-/g, '/')).getTime();
            var now = new Date().getTime();
            var diff = target - now;

            if (diff <= 0) {
                countEl.innerHTML = '<i class="fa-solid fa-circle-check" style="color:#10b981;"></i> Đã đến giờ phát hành! Hệ thống đang kích hoạt.';
                return;
            }

            var days = Math.floor(diff / (1000 * 60 * 60 * 24));
            var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var mins = Math.floor((diff % (1000 * 60)) / (1000 * 60));
            var secs = Math.floor((diff % (1000 * 60)) / 1000);

            var parts = [];
            if (days > 0) parts.push(days + ' ngày');
            if (hours > 0 || days > 0) parts.push(hours + ' giờ');
            parts.push(mins + ' phút');
            parts.push(secs + ' giây');

            countEl.innerHTML = '<i class="fa-solid fa-hourglass-half fa-spin"></i> Còn ' + parts.join(' ');
        }

        update();
        timerId = setInterval(update, 1000);
    }

    // Hàm mở Popup (Hỗ trợ gọi trực tiếp từ onclick HTML)
    window.muopOpenScheduleModal = function(storyId, storyTitle, schedTime, schedFmt) {
        var modal = document.getElementById('modalScheduleStoryDetail');
        if (!modal) return;

        // Lưu ID vào dataset
        modal.dataset.storyId = storyId || '';
        modal.dataset.scheduledTime = schedTime || '';

        var titleEl = document.getElementById('scheduleModalStoryTitle');
        if (titleEl) titleEl.textContent = storyTitle || 'Bộ truyện';

        if (!schedFmt && schedTime) {
            var c = schedTime.replace('T', ' ').trim().split(' ');
            if (c.length >= 2) {
                var dp = c[0].split('-');
                var tp = c[1].split(':');
                if (dp.length === 3 && tp.length >= 2) {
                    schedFmt = tp[0] + ':' + tp[1] + ' - ' + dp[2] + '/' + dp[1] + '/' + dp[0];
                }
            }
        }
        var dtTextEl = document.getElementById('scheduleModalDatetimeText');
        if (dtTextEl) dtTextEl.textContent = schedFmt || schedTime || 'Chưa lên lịch';

        var alertEl = document.getElementById('scheduleDetailAlert');
        if (alertEl) alertEl.style.display = 'none';

        var formWrap = document.getElementById('scheduleChangeFormWrap');
        var isNewSchedule = !schedTime;
        if (formWrap) {
            formWrap.style.display = isNewSchedule ? 'block' : 'none';
        }

        var btnSubmit = document.getElementById('btnSubmitChangeScheduleTime');
        if (btnSubmit) {
            btnSubmit.innerHTML = isNewSchedule ? '<i class="fa-solid fa-clock"></i> Xác Nhận Hẹn Giờ Công Khai' : '<i class="fa-solid fa-check"></i> Lưu Giờ Hẹn Mới';
        }

        var btnToggleChange = document.getElementById('btnTriggerToggleChangeSchedule');
        var btnCancelDraft = document.getElementById('btnPopupCancelScheduleToDraft');
        var btnPublishNow = document.getElementById('btnPopupPublishScheduledNow');
        if (btnToggleChange) btnToggleChange.style.display = isNewSchedule ? 'none' : 'inline-flex';
        if (btnCancelDraft) btnCancelDraft.style.display = isNewSchedule ? 'none' : 'inline-flex';
        if (btnPublishNow) btnPublishNow.style.display = isNewSchedule ? 'none' : 'inline-flex';

        // Phân tích thời gian hiện tại để nạp vào Date + Hour + Minute
        if (schedTime) {
            var clean = schedTime.replace('T', ' ');
            var parts = clean.split(' ');
            if (parts.length >= 2) {
                var dPart = parts[0];
                var tParts = parts[1].split(':');
                var dateEl = document.getElementById('popupSchedDate');
                var hourEl = document.getElementById('popupSchedHour');
                var minEl  = document.getElementById('popupSchedMinute');
                if (dateEl) dateEl.value = dPart;
                if (hourEl && tParts.length >= 1) hourEl.value = pad2(parseInt(tParts[0], 10));
                if (minEl && tParts.length >= 2) {
                    var mVal = Math.floor(parseInt(tParts[1], 10) / 5) * 5;
                    minEl.value = pad2(mVal);
                }
            }
        } else {
            // Mặc định +3h nếu chưa có
            window.muopSetPopupQuickSchedule(3, null);
        }

        window.muopSyncPopupScheduleTime();
        if (schedTime) {
            startPopupCountdown(schedTime);
        } else {
            var countEl = document.getElementById('scheduleModalCountdownText');
            if (countEl) {
                countEl.innerHTML = '<i class="fa-solid fa-clock"></i> Hãy chọn thời gian bạn muốn công khai bên dưới';
            }
        }

        modal.style.display = 'flex';
    };

    // Hàm đóng Popup
    window.muopCloseScheduleModal = function() {
        var modal = document.getElementById('modalScheduleStoryDetail');
        if (modal) modal.style.display = 'none';
        if (timerId) {
            clearInterval(timerId);
            timerId = null;
        }
    };

    // Click backdrop để đóng
    var modalEl = document.getElementById('modalScheduleStoryDetail');
    if (modalEl) {
        modalEl.addEventListener('click', function(e) {
            if (e.target === this) {
                window.muopCloseScheduleModal();
            }
        });
    }

    var ajaxUrl = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/wp-admin/admin-ajax.php';
    var nonceVal = (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '<?php echo wp_create_nonce("muop_ajax_nonce"); ?>';

    function showModalAlert(msg, isError) {
        var alertEl = document.getElementById('scheduleDetailAlert');
        if (!alertEl) return;
        alertEl.style.display = 'block';
        alertEl.className = isError ? 'alert-box alert-error' : 'alert-box alert-success';
        alertEl.style.background = isError ? '#fef2f2' : '#f0fdf4';
        alertEl.style.color = isError ? '#991b1b' : '#166534';
        alertEl.style.border = isError ? '1px solid #fecaca' : '1px solid #bbf7d0';
        alertEl.style.padding = '10px 14px';
        alertEl.style.borderRadius = '6px';
        alertEl.style.fontSize = '13px';
        alertEl.innerHTML = (isError ? '<i class="fa-solid fa-triangle-exclamation"></i> ' : '<i class="fa-solid fa-circle-check"></i> ') + msg;
    }

    // 1. Submit form đặt lịch / đổi giờ hẹn
    var btnSubmit = document.getElementById('btnSubmitChangeScheduleTime');
    if (btnSubmit) {
        btnSubmit.addEventListener('click', function(e) {
            e.preventDefault();
            var modal = document.getElementById('modalScheduleStoryDetail');
            var storyId = modal ? modal.dataset.storyId : '';
            var timeVal = document.getElementById('inputQuickChangeScheduleTime') ? document.getElementById('inputQuickChangeScheduleTime').value : '';

            if (!storyId) {
                showModalAlert('Lỗi: Không tìm thấy ID truyện.', true);
                return;
            }
            if (!timeVal) {
                showModalAlert('Vui lòng chọn thời gian hẹn giờ công khai.', true);
                return;
            }

            var origHtml = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
            btnSubmit.disabled = true;

            var fd = new FormData();
            fd.append('action', 'muop_schedule_story');
            fd.append('nonce', nonceVal);
            fd.append('story_id', storyId);
            fd.append('scheduled_time', timeVal);

            fetch(ajaxUrl, {
                method: 'POST',
                body: fd
            })
            .then(function(res) { return res.json(); })
            .then(function(res) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = origHtml;
                if (res.success) {
                    showModalAlert(res.data && res.data.message ? res.data.message : 'Đã lên lịch hẹn giờ công khai thành công!', false);
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                } else {
                    showModalAlert(res.data && res.data.message ? res.data.message : 'Có lỗi xảy ra, vui lòng thử lại.', true);
                }
            })
            .catch(function(err) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = origHtml;
                showModalAlert('Lỗi kết nối máy chủ. Vui lòng thử lại.', true);
            });
        });
    }

    // 2. Chuyển về bản nháp
    var btnCancelDraft = document.getElementById('btnPopupCancelScheduleToDraft');
    if (btnCancelDraft) {
        btnCancelDraft.addEventListener('click', function(e) {
            e.preventDefault();
            var modal = document.getElementById('modalScheduleStoryDetail');
            var storyId = modal ? modal.dataset.storyId : '';
            if (!storyId) return;

            if (!confirm('Bạn có chắc chắn muốn hủy lịch hẹn và chuyển bộ truyện này về trạng thái Bản Nháp không?')) {
                return;
            }

            var origHtml = btnCancelDraft.innerHTML;
            btnCancelDraft.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang hủy...';
            btnCancelDraft.disabled = true;

            var fd = new FormData();
            fd.append('action', 'muop_cancel_schedule');
            fd.append('nonce', nonceVal);
            fd.append('story_id', storyId);

            fetch(ajaxUrl, {
                method: 'POST',
                body: fd
            })
            .then(function(res) { return res.json(); })
            .then(function(res) {
                btnCancelDraft.disabled = false;
                btnCancelDraft.innerHTML = origHtml;
                if (res.success) {
                    showModalAlert(res.data && res.data.message ? res.data.message : 'Đã chuyển về bản nháp.', false);
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                } else {
                    showModalAlert(res.data && res.data.message ? res.data.message : 'Có lỗi xảy ra.', true);
                }
            })
            .catch(function() {
                btnCancelDraft.disabled = false;
                btnCancelDraft.innerHTML = origHtml;
                showModalAlert('Lỗi kết nối máy chủ.', true);
            });
        });
    }

    // 3. Đăng ngay lập tức
    var btnPublishNow = document.getElementById('btnPopupPublishScheduledNow');
    if (btnPublishNow) {
        btnPublishNow.addEventListener('click', function(e) {
            e.preventDefault();
            var modal = document.getElementById('modalScheduleStoryDetail');
            var storyId = modal ? modal.dataset.storyId : '';
            if (!storyId) return;

            if (!confirm('Bạn có muốn xuất bản truyện công khai ngay lập tức không?')) {
                return;
            }

            var origHtml = btnPublishNow.innerHTML;
            btnPublishNow.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang đăng...';
            btnPublishNow.disabled = true;

            var fd = new FormData();
            fd.append('action', 'muop_update_story_visibility');
            fd.append('action_type', 'unhide');
            fd.append('nonce', nonceVal);
            fd.append('story_id', storyId);

            fetch(ajaxUrl, {
                method: 'POST',
                body: fd
            })
            .then(function(res) { return res.json(); })
            .then(function(res) {
                btnPublishNow.disabled = false;
                btnPublishNow.innerHTML = origHtml;
                if (res.success) {
                    showModalAlert('Đã xuất bản truyện công khai thành công!', false);
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                } else {
                    showModalAlert(res.data && res.data.message ? res.data.message : 'Có lỗi xảy ra.', true);
                }
            })
            .catch(function() {
                btnPublishNow.disabled = false;
                btnPublishNow.innerHTML = origHtml;
                showModalAlert('Lỗi kết nối máy chủ.', true);
            });
        });
    }

    // 4. Delegated Click Listener cho toàn trang
    document.addEventListener('click', function(e) {
        var btnScheduled = e.target.closest ? e.target.closest('.btn-show-scheduled-popup') : null;
        if (btnScheduled) {
            e.preventDefault();
            var sId = btnScheduled.getAttribute('data-story-id');
            var sTitle = btnScheduled.getAttribute('data-story-title');
            var sTime = btnScheduled.getAttribute('data-scheduled-time') || '';
            var sFmt = btnScheduled.getAttribute('data-scheduled-formatted') || '';
            window.muopOpenScheduleModal(sId, sTitle, sTime, sFmt);
            return;
        }

        var btnDraft = e.target.closest ? e.target.closest('.btn-action-schedule-draft') : null;
        if (btnDraft) {
            e.preventDefault();
            var sId = btnDraft.getAttribute('data-story-id');
            var sTitle = btnDraft.getAttribute('data-story-title');
            window.muopOpenScheduleModal(sId, sTitle, '', '');
            return;
        }
    });
})();
</script>
