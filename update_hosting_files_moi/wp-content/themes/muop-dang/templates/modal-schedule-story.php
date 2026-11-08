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

                <!-- Bộ chọn ngày và 24 giờ Việt Nam ĐẸP MẮT (Chuẩn Hình 1 & 2) -->
                <div class="custom-sched-picker-row">
                    <!-- 1. Custom Date Picker (Hình 1) -->
                    <div class="custom-sched-picker-col">
                        <input type="hidden" id="popupSchedDate" value="<?php echo date('Y-m-d'); ?>" />
                        <button type="button" class="custom-sched-btn-trigger" id="btnTriggerCustomDate" title="Bấm để chọn ngày phát hành">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-calendar-days" style="color: #8b5cf6;"></i>
                                <span id="displayCustomDateText"><?php echo date('d/m/Y'); ?></span>
                            </span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 11px; opacity: 0.5;"></i>
                        </button>

                        <!-- Popup Lịch Mini Tuyệt Đẹp (Hình 1) -->
                        <div class="custom-sched-dropdown-card" id="cardCustomDatePicker">
                            <div class="custom-cal-header">
                                <button type="button" class="custom-cal-nav-btn" id="btnCalPrevMonth"><i class="fa-solid fa-chevron-left"></i></button>
                                <span class="custom-cal-title" id="calMonthYearTitle">Tháng ...</span>
                                <button type="button" class="custom-cal-nav-btn" id="btnCalNextMonth"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                            <div class="custom-cal-weekdays">
                                <span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span><span>CN</span>
                            </div>
                            <div class="custom-cal-grid" id="calDaysGrid"></div>
                            <div class="custom-cal-footer">
                                <button type="button" class="custom-cal-quick-btn" id="btnCalSelectToday">Hôm nay</button>
                                <button type="button" class="custom-cal-quick-btn" id="btnCalSelectTomorrow">Ngày mai</button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Custom Time Picker (Hình 2: Giờ & Phút) -->
                    <div class="custom-sched-picker-col">
                        <select id="popupSchedHour" style="display: none;">
                            <?php for ($h = 0; $h < 24; $h++): 
                                $h_str = str_pad($h, 2, '0', STR_PAD_LEFT);
                                $suffix = ($h < 6 ? ' (đêm)' : ($h < 11 ? ' (sáng)' : ($h < 13 ? ' (trưa)' : ($h < 18 ? ' (chiều)' : ' (tối)'))));
                            ?>
                                <option value="<?php echo $h_str; ?>"><?php echo $h_str . ' giờ' . $suffix; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select id="popupSchedMinute" style="display: none;">
                            <?php for ($m = 0; $m < 60; $m += 5): 
                                $m_str = str_pad($m, 2, '0', STR_PAD_LEFT);
                            ?>
                                <option value="<?php echo $m_str; ?>"><?php echo $m_str . ' phút'; ?></option>
                            <?php endfor; ?>
                        </select>

                        <button type="button" class="custom-sched-btn-trigger" id="btnTriggerCustomTime" title="Bấm để chọn giờ hẹn">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-clock" style="color: #8b5cf6;"></i>
                                <span id="displayCustomTimeText">12:00 (trưa)</span>
                            </span>
                            <i class="fa-solid fa-chevron-down" style="font-size: 11px; opacity: 0.5;"></i>
                        </button>

                        <!-- Popup Menu Giờ & Phút Sang Trọng (Hình 2) -->
                        <div class="custom-sched-dropdown-card" id="cardCustomTimePicker" style="width: 310px; right: 0; left: auto;">
                            <div class="custom-time-title">
                                <i class="fa-solid fa-sun" style="color: #f59e0b;"></i> Chọn Giờ (24 Giờ Việt Nam)
                            </div>
                            <div class="custom-time-hours-grid" id="timeHoursGrid"></div>

                            <div class="custom-time-title" style="margin-top: 8px;">
                                <i class="fa-solid fa-stopwatch" style="color: #06b6d4;"></i> Chọn Phút
                            </div>
                            <div class="custom-time-mins-grid" id="timeMinsGrid"></div>

                            <div style="display: flex; justify-content: flex-end; padding-top: 6px; border-top: 1px solid #ede9fe;">
                                <button type="button" class="custom-cal-quick-btn" id="btnCloseTimePicker" style="background: #7c3aed; color: #fff; border-color: #6d28d9; padding: 4px 14px;">
                                    Xong
                                </button>
                            </div>
                        </div>
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
                <button type="button" id="btnTriggerToggleChangeSchedule" class="btn btn-outline" style="padding: 7px 14px; font-size: 13px; color: #7c3aed; border-color: #c4b5fd;">
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
            
            // Cập nhật text hiển thị trên 2 nút trigger đẹp mắt (Hình 1 & 2)
            var dispDate = document.getElementById('displayCustomDateText');
            if (dispDate) dispDate.textContent = dateFmt;
            
            var dispTime = document.getElementById('displayCustomTimeText');
            if (dispTime) {
                var hInt = parseInt(hVal, 10);
                var suf = (hInt < 6 ? ' (đêm)' : (hInt < 11 ? ' (sáng)' : (hInt < 13 ? ' (trưa)' : (hInt < 18 ? ' (chiều)' : ' (tối)'))));
                dispTime.textContent = hVal + ':' + mVal + suf;
            }
        }
        if (window.muopUpdateCustomPickerUI) window.muopUpdateCustomPickerUI();
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

    // Hàm chuyển đổi bật/tắt form đổi giờ hẹn
    window.muopToggleScheduleChangeForm = function() {
        var formWrap = document.getElementById('scheduleChangeFormWrap');
        if (!formWrap) return;
        var isHidden = (formWrap.style.display === 'none' || window.getComputedStyle(formWrap).display === 'none');
        if (isHidden) {
            formWrap.style.display = 'block';
            var hiddenEl = document.getElementById('inputQuickChangeScheduleTime');
            var nowTime = Date.now();
            if (!hiddenEl || !hiddenEl.value || (new Date(hiddenEl.value.replace(/-/g, '/')).getTime() <= nowTime)) {
                // Tự động gợi ý +1 giờ nếu thời gian đang ở quá khứ hoặc trống
                window.muopSetPopupQuickSchedule && window.muopSetPopupQuickSchedule(1, null);
            } else {
                window.muopSyncPopupScheduleTime && window.muopSyncPopupScheduleTime();
            }
            try {
                formWrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } catch(e) {}
        } else {
            formWrap.style.display = 'none';
        }
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

        modal.style.setProperty('display', 'flex', 'important');
    };

    // Hàm đóng Popup
    window.muopCloseScheduleModal = function() {
        var modal = document.getElementById('modalScheduleStoryDetail');
        if (modal) modal.style.setProperty('display', 'none', 'important');
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

    // Bật/tắt form đổi giờ hẹn
    var btnToggleSched = document.getElementById('btnTriggerToggleChangeSchedule');
    if (btnToggleSched) {
        btnToggleSched.onclick = function(e) {
            e.preventDefault();
            window.muopToggleScheduleChangeForm();
        };
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

    // =========================================================================
    // CUSTOM DATE & TIME PICKER CONTROLLERS (HÌNH 1 & HÌNH 2)
    // =========================================================================
    var curViewDate = new Date();

    function getSelectedDateObj() {
        var dateEl = document.getElementById('popupSchedDate');
        if (dateEl && dateEl.value) {
            var parts = dateEl.value.split('-');
            if (parts.length === 3) {
                return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
            }
        }
        return new Date();
    }

    function renderCustomCalendar(viewYear, viewMonth) {
        var grid = document.getElementById('calDaysGrid');
        var title = document.getElementById('calMonthYearTitle');
        if (!grid || !title) return;

        title.textContent = 'Tháng ' + (viewMonth + 1) + ' / ' + viewYear;

        var firstDayOfWeek = new Date(viewYear, viewMonth, 1).getDay(); // 0 is Sun, 1 is Mon
        var startOffset = (firstDayOfWeek === 0) ? 6 : (firstDayOfWeek - 1); // Mon is 0

        var daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        var selectedDate = getSelectedDateObj();
        var today = new Date();
        today.setHours(0, 0, 0, 0);

        var html = '';
        for (var i = 0; i < startOffset; i++) {
            html += '<div class="custom-cal-day is-disabled"></div>';
        }

        for (var d = 1; d <= daysInMonth; d++) {
            var thisDate = new Date(viewYear, viewMonth, d);
            thisDate.setHours(0, 0, 0, 0);
            var isPast = (thisDate.getTime() < today.getTime());
            var isToday = (thisDate.getTime() === today.getTime());
            var isSel = (thisDate.getFullYear() === selectedDate.getFullYear() &&
                         thisDate.getMonth() === selectedDate.getMonth() &&
                         thisDate.getDate() === selectedDate.getDate());

            var cls = 'custom-cal-day';
            if (isPast) cls += ' is-disabled';
            if (isToday) cls += ' is-today';
            if (isSel) cls += ' is-selected';

            html += '<div class="' + cls + '" data-year="' + viewYear + '" data-month="' + (viewMonth + 1) + '" data-day="' + d + '" ' + (isPast ? '' : 'onclick="window.muopPickCustomCalDate(' + viewYear + ',' + (viewMonth + 1) + ',' + d + ')"') + '>' + d + '</div>';
        }

        grid.innerHTML = html;
    }

    window.muopPickCustomCalDate = function(y, m, d) {
        var dateEl = document.getElementById('popupSchedDate');
        if (dateEl) {
            dateEl.value = y + '-' + pad2(m) + '-' + pad2(d);
            window.muopSyncPopupScheduleTime();
        }
        var card = document.getElementById('cardCustomDatePicker');
        if (card) card.classList.remove('show');
    };

    function renderCustomTimeGrids() {
        var hGrid = document.getElementById('timeHoursGrid');
        var mGrid = document.getElementById('timeMinsGrid');
        var hourEl = document.getElementById('popupSchedHour');
        var minEl = document.getElementById('popupSchedMinute');
        if (!hGrid || !mGrid || !hourEl || !minEl) return;

        var curH = hourEl.value;
        var curM = minEl.value;

        var hHtml = '';
        for (var h = 0; h < 24; h++) {
            var hStr = pad2(h);
            var isSelH = (hStr === curH);
            hHtml += '<div class="custom-time-hour-pill ' + (isSelH ? 'is-selected' : '') + '" onclick="window.muopPickCustomHour(\'' + hStr + '\')">' + hStr + 'h</div>';
        }
        hGrid.innerHTML = hHtml;

        var mHtml = '';
        for (var m = 0; m < 60; m += 5) {
            var mStr = pad2(m);
            var isSelM = (mStr === curM);
            mHtml += '<div class="custom-time-min-pill ' + (isSelM ? 'is-selected' : '') + '" onclick="window.muopPickCustomMinute(\'' + mStr + '\')">' + mStr + 'p</div>';
        }
        mGrid.innerHTML = mHtml;
    }

    window.muopPickCustomHour = function(hStr) {
        var hourEl = document.getElementById('popupSchedHour');
        if (hourEl) {
            hourEl.value = hStr;
            window.muopSyncPopupScheduleTime();
            renderCustomTimeGrids();
        }
    };

    window.muopPickCustomMinute = function(mStr) {
        var minEl = document.getElementById('popupSchedMinute');
        if (minEl) {
            minEl.value = mStr;
            window.muopSyncPopupScheduleTime();
            renderCustomTimeGrids();
        }
    };

    window.muopUpdateCustomPickerUI = function() {
        var selDate = getSelectedDateObj();
        curViewDate = new Date(selDate.getTime());
        renderCustomCalendar(curViewDate.getFullYear(), curViewDate.getMonth());
        renderCustomTimeGrids();
    };

    // Khởi tạo các sự kiện click cho bộ chọn Custom Date & Time
    document.addEventListener('DOMContentLoaded', function() {
        var btnDate = document.getElementById('btnTriggerCustomDate');
        var cardDate = document.getElementById('cardCustomDatePicker');
        var btnTime = document.getElementById('btnTriggerCustomTime');
        var cardTime = document.getElementById('cardCustomTimePicker');

        if (btnDate && cardDate) {
            btnDate.addEventListener('click', function(e) {
                e.stopPropagation();
                if (cardTime) cardTime.classList.remove('show');
                cardDate.classList.toggle('show');
                if (cardDate.classList.contains('show')) {
                    window.muopUpdateCustomPickerUI();
                }
            });
        }

        if (btnTime && cardTime) {
            btnTime.addEventListener('click', function(e) {
                e.stopPropagation();
                if (cardDate) cardDate.classList.remove('show');
                cardTime.classList.toggle('show');
                if (cardTime.classList.contains('show')) {
                    window.muopUpdateCustomPickerUI();
                }
            });
        }

        var btnPrev = document.getElementById('btnCalPrevMonth');
        var btnNext = document.getElementById('btnCalNextMonth');
        if (btnPrev) {
            btnPrev.addEventListener('click', function(e) {
                e.stopPropagation();
                curViewDate.setMonth(curViewDate.getMonth() - 1);
                renderCustomCalendar(curViewDate.getFullYear(), curViewDate.getMonth());
            });
        }
        if (btnNext) {
            btnNext.addEventListener('click', function(e) {
                e.stopPropagation();
                curViewDate.setMonth(curViewDate.getMonth() + 1);
                renderCustomCalendar(curViewDate.getFullYear(), curViewDate.getMonth());
            });
        }

        var btnToday = document.getElementById('btnCalSelectToday');
        var btnTomorrow = document.getElementById('btnCalSelectTomorrow');
        if (btnToday) {
            btnToday.addEventListener('click', function(e) {
                e.stopPropagation();
                var now = new Date();
                window.muopPickCustomCalDate(now.getFullYear(), now.getMonth() + 1, now.getDate());
            });
        }
        if (btnTomorrow) {
            btnTomorrow.addEventListener('click', function(e) {
                e.stopPropagation();
                var tom = new Date();
                tom.setDate(tom.getDate() + 1);
                window.muopPickCustomCalDate(tom.getFullYear(), tom.getMonth() + 1, tom.getDate());
            });
        }

        var btnCloseTime = document.getElementById('btnCloseTimePicker');
        if (btnCloseTime) {
            btnCloseTime.addEventListener('click', function(e) {
                e.stopPropagation();
                if (cardTime) cardTime.classList.remove('show');
            });
        }

        // Đóng khi click ngoài
        document.addEventListener('click', function(e) {
            if (cardDate && !cardDate.contains(e.target) && (!btnDate || !btnDate.contains(e.target))) {
                cardDate.classList.remove('show');
            }
            if (cardTime && !cardTime.contains(e.target) && (!btnTime || !btnTime.contains(e.target))) {
                cardTime.classList.remove('show');
            }
        });

        // Khởi tạo lần đầu
        window.muopUpdateCustomPickerUI();
    });
})();
</script>
