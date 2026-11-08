<?php
/**
 * Template Part: Modal Donate Team Dịch
 * - Dành cho Dịch Giả: Popup cài đặt/cập nhật thông tin nhận donate (Mã QR, Ngân hàng, STK, Tên người nhận) có nút Thêm / Cập nhật
 * - Dành cho Độc Giả: Hiển thị duy nhất thẻ VietQR chuẩn Hình 3 kèm logo web ở chính giữa tâm mã
 */
if (!defined('ABSPATH')) exit;
if (defined('MUOP_MODAL_DONATE_TEAM_LOADED')) return;
define('MUOP_MODAL_DONATE_TEAM_LOADED', true);
?>

<!-- MODAL DONATE TEAM DỊCH -->
<div id="modalDonateTeam" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.78); z-index: 999999; align-items: center; justify-content: center; padding: 14px; backdrop-filter: blur(4px);">
    <div class="custom-modal-content" style="background: #ffffff; width: 100%; max-width: 440px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); border: 1.5px solid #fde68a; overflow: hidden; display: flex; flex-direction: column; max-height: 94vh; animation: slideUp 0.25s ease;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border-bottom: 1px solid #fde68a; padding: 13px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h3 id="donateModalMainTitle" style="font-size: 16px; font-weight: 800; color: #92400e; margin: 0; line-height: 1.3;">
                    Donate
                </h3>
                <span id="donateModalTeamName" style="font-size: 12.5px; color: #b45309; font-weight: 600; display: block; margin-top: 2px;">
                    ...
                </span>
            </div>
            <button type="button" onclick="window.muopCloseDonateModal && window.muopCloseDonateModal()" style="background: #ffffff; border: 1px solid #fde68a; color: #78350f; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; transition: all 0.2s ease;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Thanh Tabs Dành Cho Dịch Giả / Admin (Chỉ hiện khi có quyền quản lý) -->
        <div id="donateModalManageTabs" style="display: none; background: #fafaf9; border-bottom: 1px solid #f2f2f2; padding: 6px 14px; gap: 8px;">
            <button type="button" id="btnTabDonateEdit" style="flex: 1; padding: 6px 12px; font-size: 12px; font-weight: 700; border-radius: 8px; border: 1px solid #f59e0b; background: #fffbeb; color: #b45309; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease;">
                <i class="fa-solid fa-pen-to-square"></i> <span>Chỉnh Sửa Thông Tin</span>
            </button>
            <button type="button" id="btnTabDonatePreview" style="flex: 1; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease;">
                <i class="fa-solid fa-eye"></i> <span>Xem Mã QR (Độc Giả Thấy)</span>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 16px 18px; overflow-y: auto; flex: 1;">
            
            <!-- Trạng thái: Đang tải thông tin -->
            <div id="donateModalLoading" style="padding: 30px 10px; color: #64748b; font-size: 13.5px; text-align: center;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #f59e0b; margin-bottom: 8px; display: block;"></i>
                Đang nạp thông tin Donate...
            </div>

            <!-- TAB 1: FORM CẤU HÌNH DÀNH CHO DỊCH GIẢ (Thêm hoặc Cập nhật) -->
            <div id="donateTranslatorEditBox" style="display: none;">
                <div id="donateModalEditAlert" class="alert-box" style="display: none; margin-bottom: 12px; font-size: 12.5px; padding: 8px 12px;"></div>

                <form id="formDonateModalConfig" enctype="multipart/form-data">
                    <input type="hidden" id="modalDonateTeamIdHidden" name="team_id" value="0" />
                    <input type="hidden" id="modalDonateAuthorIdHidden" name="author_id" value="0" />
                    
                    <div style="margin-bottom: 10px;">
                        <label class="form-label" for="modalDonateBankCode" style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px; display: block;">
                            <i class="fa-solid fa-building-columns" style="color: #f59e0b;"></i> Chọn Ngân Hàng: <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="modalDonateBankCode" name="bank_code" class="form-input" style="width: 100%; height: 38px; font-size: 13px; border-radius: 8px;" required>
                            <option value="">-- Đang nạp danh sách ngân hàng... --</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label class="form-label" for="modalDonateAccNum" style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px; display: block;">
                            <i class="fa-solid fa-credit-card" style="color: #0284c7;"></i> Số Tài Khoản: <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="modalDonateAccNum" name="account_number" class="form-input" placeholder="VD: 0987654321..." style="width: 100%; height: 38px; font-size: 13px; font-weight: 700; color: #0369a1; border-radius: 8px;" required />
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label class="form-label" for="modalDonateAccName" style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px; display: block;">
                            <i class="fa-solid fa-user-check" style="color: #16a34a;"></i> Tên Người Nhận (Chủ Tài Khoản): <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="modalDonateAccName" name="account_name" class="form-input" placeholder="VD: NGUYEN VAN A" style="width: 100%; height: 38px; font-size: 13px; font-weight: 700; text-transform: uppercase; border-radius: 8px;" required />
                    </div>

                    <!-- Ảnh mã QR tùy chọn & Thumbnail đã có -->
                    <div style="margin-bottom: 14px; background: #fafafa; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 10px 12px;">
                        <label class="form-label" for="modalDonateQrFile" style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">
                            <i class="fa-solid fa-qrcode"></i> Tải Ảnh Mã QR Riêng (Tùy Chọn):
                        </label>
                        <input type="file" id="modalDonateQrFile" name="donate_qr_file" class="form-input" accept="image/png, image/jpeg, image/webp" style="width: 100%; height: 36px; font-size: 12px; padding: 3px 6px; border-radius: 6px;" />
                        <div style="font-size: 11px; color: #64748b; line-height: 1.4; margin-top: 5px;">
                            <i class="fa-solid fa-circle-info" style="color: #f59e0b;"></i> Nếu bạn không tải ảnh lên, hệ thống sẽ <strong>tự động tạo mã VietQR chuẩn ngân hàng và chèn logo web ở chính giữa tâm mã</strong>.
                        </div>

                        <!-- Khung xem trước mã QR hiện tại nếu đã lưu -->
                        <div id="modalDonateExistingQrBox" style="display: none; align-items: center; justify-content: space-between; margin-top: 8px; padding-top: 8px; border-top: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <img id="modalDonateExistingQrThumb" src="" alt="Mã QR" style="width: 38px; height: 38px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;" />
                                <span style="font-size: 11.5px; color: #166534; font-weight: 600;"><i class="fa-solid fa-check"></i> Đã có mã QR hoạt động</span>
                            </div>
                            <button type="button" id="btnDeleteDonateQrModal" class="btn btn-outline btn-xs" style="color: #dc2626; border-color: #fca5a5; font-size: 11px; padding: 2px 8px; border-radius: 6px;" title="Xóa mã QR này">
                                <i class="fa-solid fa-trash-can"></i> Xóa mã
                            </button>
                        </div>
                    </div>

                    <!-- Hàng Nút: Thêm / Cập Nhật -->
                    <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                        <button type="submit" id="btnSubmitDonateModal" class="btn btn-primary" style="padding: 7px 18px; font-size: 13px; font-weight: 700; background: linear-gradient(135deg, #d97706, #b45309); border: none; border-radius: 8px;">
                            <i class="fa-solid fa-floppy-disk"></i> <span>Cập Nhật</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: GIAO DIỆN CHUẨN ĐỘC GIẢ (CHẾ ĐỘ XEM THẺ VIETQR NHƯ HÌNH 3) -->
            <div id="donateReaderViewBox" style="display: none; text-align: center;">
                
                <!-- CHỈ HIỂN THỊ KHUNG TRONG CHỨA MÃ VIETQR CHUẨN (ĐÃ BỎ PHẦN THỪA BÊN NGOÀI) -->
                <div id="donateInnerQrCard" style="width: 100%; max-width: 320px; margin: 0 auto; border-radius: 16px; overflow: hidden; background: #fff; position: relative; border: 1.5px solid #2563eb; padding: 4px; box-sizing: border-box; box-shadow: 0 4px 18px rgba(37, 99, 235, 0.12); cursor: pointer;" title="Bấm vào mã QR để sao chép Số Tài Khoản">
                    <img id="donateModalQrImg" src="" alt="Mã QR Chuyển Khoản VietQR" style="width: 100%; height: auto; display: block; border-radius: 12px;" />
                    <!-- Che dòng chữ Số tiền: 0đ theo yêu cầu -->
                    <div style="position: absolute; bottom: 3%; left: 50%; transform: translateX(-50%); width: 50%; height: 4.8%; background: #ffffff; pointer-events: none;"></div>
                </div>

                <!-- Nút sao chép STK nhanh tiện lợi -->
                <div style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <button type="button" id="btnCopyDonateAccNum" class="btn btn-outline btn-xs" style="padding: 5px 16px; font-size: 12px; border-radius: 20px; border-color: #0284c7; color: #0284c7; font-weight: 700; background: #f0f9ff; cursor: pointer; transition: all 0.2s ease;" title="Sao chép số tài khoản">
                        <i class="fa-solid fa-copy"></i> Sao chép STK
                    </button>
                    <!-- Giữ các thẻ span ẩn để không phá vỡ JS nạp dữ liệu -->
                    <span id="donateModalAccName" style="display: none;"></span>
                    <span id="donateModalAccNum" style="display: none;"></span>
                    <span id="donateModalBankName" style="display: none;"></span>
                </div>

                <!-- Lời cảm ơn dễ thương -->
                <div style="margin-top: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 7px 12px; font-size: 12px; color: #166534; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
                    <span>Cảm ơn các bạn đã gửi gắm yêu thương và tiếp sức cho Team!</span>
                </div>
            </div>

            <!-- CHƯA CÓ MÃ QR (Dành Cho Độc Giả) -->
            <div id="donateModalNoQrBox" style="display: none; padding: 24px 10px; text-align: center;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px;">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <h4 style="font-size: 14.5px; font-weight: 700; color: #1e293b; margin: 0 0 6px 0;">Team Dịch Chưa Cài Đặt Mã QR Donate</h4>
                <p style="font-size: 12.5px; color: #64748b; line-height: 1.5; margin: 0 0 16px 0;">
                    Team dịch hiện tại chưa thiết lập thông tin nhận donate. Các bạn có thể ủng hộ team bằng cách <strong>Theo Dõi</strong> và <strong>Thả Tim</strong> các tác phẩm của team nhé!
                </p>
                <div id="donateModalTranslatorSetupHint" style="display: none; margin-bottom: 12px;">
                    <button type="button" id="btnSwitchToEditFromEmpty" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #d97706, #b45309); border: none; padding: 6px 16px; border-radius: 8px;">
                        <i class="fa-solid fa-plus"></i> Thiết Lập Thông Tin Donate Ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var modal = document.getElementById('modalDonateTeam');
    var loading = document.getElementById('donateModalLoading');
    var editBox = document.getElementById('donateTranslatorEditBox');
    var readerBox = document.getElementById('donateReaderViewBox');
    var noQrBox = document.getElementById('donateModalNoQrBox');
    var tabsWrap = document.getElementById('donateModalManageTabs');
    var btnTabEdit = document.getElementById('btnTabDonateEdit');
    var btnTabPreview = document.getElementById('btnTabDonatePreview');
    var teamNameEl = document.getElementById('donateModalTeamName');
    var mainTitleEl = document.getElementById('donateModalMainTitle');
    var editAlert = document.getElementById('donateModalEditAlert');

    var qrImg = document.getElementById('donateModalQrImg');
    var accNameEl = document.getElementById('donateModalAccName');
    var accNumEl = document.getElementById('donateModalAccNum');
    var bankNameEl = document.getElementById('donateModalBankName');
    var btnCopy = document.getElementById('btnCopyDonateAccNum');

    // Form inputs
    var inputTeamId = document.getElementById('modalDonateTeamIdHidden');
    var inputAuthorId = document.getElementById('modalDonateAuthorIdHidden');
    var selectBank = document.getElementById('modalDonateBankCode');
    var inputAccNum = document.getElementById('modalDonateAccNum');
    var inputAccName = document.getElementById('modalDonateAccName');
    var inputQrFile = document.getElementById('modalDonateQrFile');
    var btnSubmit = document.getElementById('btnSubmitDonateModal');
    var existingQrBox = document.getElementById('modalDonateExistingQrBox');
    var existingQrThumb = document.getElementById('modalDonateExistingQrThumb');
    var btnDeleteQr = document.getElementById('btnDeleteDonateQrModal');
    var formConfig = document.getElementById('formDonateModalConfig');
    var setupHint = document.getElementById('donateModalTranslatorSetupHint');
    var btnSwitchEmpty = document.getElementById('btnSwitchToEditFromEmpty');

    var currentAccNum = '';
    var currentDonateData = null;

    function renderBankOptions(banksList, selectedCode) {
        if (!selectBank || !banksList) return;
        var html = '<option value="">-- Chọn ngân hàng --</option>';
        for (var code in banksList) {
            if (banksList.hasOwnProperty(code)) {
                var b = banksList[code];
                var sel = (code === selectedCode) ? ' selected' : '';
                html += '<option value="' + code + '"' + sel + '>' + (b.name || code) + ' (' + code + ')</option>';
            }
        }
        selectBank.innerHTML = html;
    }

    function switchView(tab) {
        if (tab === 'edit') {
            if (btnTabEdit) {
                btnTabEdit.style.background = '#fffbeb';
                btnTabEdit.style.color = '#b45309';
                btnTabEdit.style.borderColor = '#f59e0b';
            }
            if (btnTabPreview) {
                btnTabPreview.style.background = '#ffffff';
                btnTabPreview.style.color = '#64748b';
                btnTabPreview.style.borderColor = '#e2e8f0';
            }
            if (editBox) editBox.style.display = 'block';
            if (readerBox) readerBox.style.display = 'none';
            if (noQrBox) noQrBox.style.display = 'none';
        } else {
            if (btnTabPreview) {
                btnTabPreview.style.background = '#fffbeb';
                btnTabPreview.style.color = '#b45309';
                btnTabPreview.style.borderColor = '#f59e0b';
            }
            if (btnTabEdit) {
                btnTabEdit.style.background = '#ffffff';
                btnTabEdit.style.color = '#64748b';
                btnTabEdit.style.borderColor = '#e2e8f0';
            }
            if (editBox) editBox.style.display = 'none';
            if (currentDonateData && currentDonateData.has_donate && currentDonateData.qr_url) {
                if (readerBox) readerBox.style.display = 'block';
                if (noQrBox) noQrBox.style.display = 'none';
            } else {
                if (readerBox) readerBox.style.display = 'none';
                if (noQrBox) noQrBox.style.display = 'block';
            }
        }
    }

    if (btnTabEdit) btnTabEdit.addEventListener('click', function() { switchView('edit'); });
    if (btnTabPreview) btnTabPreview.addEventListener('click', function() { switchView('preview'); });
    if (btnSwitchEmpty) btnSwitchEmpty.addEventListener('click', function() { switchView('edit'); });

    window.muopOpenDonateModal = function(teamId, teamName, authorId, storyId) {
        if (!modal) modal = document.getElementById('modalDonateTeam');
        if (!modal) return;

        if (teamNameEl) teamNameEl.textContent = teamName ? ('Team: ' + teamName) : 'Ủng hộ tác phẩm';
        if (mainTitleEl) mainTitleEl.textContent = 'Donate';
        if (loading) loading.style.display = 'block';
        if (editBox) editBox.style.display = 'none';
        if (readerBox) readerBox.style.display = 'none';
        if (noQrBox) noQrBox.style.display = 'none';
        if (tabsWrap) tabsWrap.style.display = 'none';
        if (editAlert) editAlert.style.display = 'none';

        if (inputTeamId) inputTeamId.value = teamId || 0;
        if (inputAuthorId) inputAuthorId.value = authorId || 0;

        modal.style.setProperty('display', 'flex', 'important');

        var ajaxUrl = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/wp-admin/admin-ajax.php';
        var fd = new FormData();
        fd.append('action', 'muop_get_team_donate_info');
        if (teamId) fd.append('team_id', teamId);
        if (authorId) fd.append('author_id', authorId);
        if (storyId) fd.append('story_id', storyId);
        if (teamName) fd.append('team_name', teamName);

        fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function(r) { return r.text(); })
            .then(function(rawText) {
                var res = null;
                try {
                    var cleanText = rawText.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
                    res = JSON.parse(cleanText);
                } catch(e) {
                    try {
                        var sIdx = rawText.indexOf('{');
                        var eIdx = rawText.lastIndexOf('}');
                        if (sIdx !== -1 && eIdx !== -1) {
                            res = JSON.parse(rawText.substring(sIdx, eIdx + 1));
                        }
                    } catch(e2) {}
                }
                if (res && res.success && res.data && res.data.team_name && teamNameEl) {
                    teamNameEl.textContent = 'Team: ' + res.data.team_name;
                }
                if (loading) loading.style.display = 'none';
                if (res && res.success && res.data) {
                    var d = res.data;
                    currentDonateData = d;
                    currentAccNum = d.account_number || '';

                    // Cập nhật thẻ độc giả (VietQR card chuẩn Hình 3)
                    if (qrImg) qrImg.src = d.qr_url || '';
                    if (accNameEl) accNameEl.textContent = d.account_name || '';
                    if (accNumEl) accNumEl.textContent = d.account_number || '';
                    if (bankNameEl) bankNameEl.textContent = d.bank_name || d.bank_code || '';

                    // Cập nhật form dịch giả
                    if (d.banks_list) {
                        renderBankOptions(d.banks_list, d.bank_code);
                    }
                    if (inputAccNum) inputAccNum.value = d.account_number || '';
                    if (inputAccName) inputAccName.value = d.account_name || '';

                    // Nút submit: Thêm hay Cập nhật
                    if (btnSubmit) {
                        if (d.has_donate) {
                            btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> <span>Cập Nhật</span>';
                        } else {
                            btnSubmit.innerHTML = '<i class="fa-solid fa-plus"></i> <span>Thêm</span>';
                        }
                    }

                    // Thumbnail QR hiện tại
                    if (existingQrBox && existingQrThumb) {
                        if (d.has_donate && d.qr_url) {
                            existingQrThumb.src = d.qr_url;
                            existingQrBox.style.display = 'flex';
                        } else {
                            existingQrBox.style.display = 'none';
                        }
                    }

                    if (d.can_manage) {
                        // DỊCH GIẢ / ADMIN: Có quyền quản lý -> Hiển thị Tabs và form Cấu hình
                        if (tabsWrap) tabsWrap.style.display = 'flex';
                        if (setupHint) setupHint.style.display = 'block';
                        
                        // Mặc định: nếu đã có QR thì mở Xem Trước (để dịch giả chiêm ngưỡng), nếu chưa thì mở Form Sửa
                        if (d.has_donate) {
                            switchView('preview');
                        } else {
                            switchView('edit');
                        }
                    } else {
                        // ĐỘC GIẢ THÔNG THƯỜNG: Chỉ xem thẻ VietQR (hoặc thông báo nếu chưa có)
                        if (tabsWrap) tabsWrap.style.display = 'none';
                        if (setupHint) setupHint.style.display = 'none';
                        if (editBox) editBox.style.display = 'none';

                        if (d.has_donate && d.qr_url) {
                            if (readerBox) readerBox.style.display = 'block';
                            if (noQrBox) noQrBox.style.display = 'none';
                        } else {
                            if (readerBox) readerBox.style.display = 'none';
                            if (noQrBox) noQrBox.style.display = 'block';
                        }
                    }
                } else {
                    if (noQrBox) noQrBox.style.display = 'block';
                }
            })
            .catch(function() {
                if (loading) loading.style.display = 'none';
                if (noQrBox) noQrBox.style.display = 'block';
            });
    };

    window.muopCloseDonateModal = function() {
        if (modal) modal.style.display = 'none';
    };

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                window.muopCloseDonateModal();
            }
        });
    }

    // Bấm vào khung QR hoặc nút Chép để sao chép STK
    var innerQrCard = document.getElementById('donateInnerQrCard');
    if (innerQrCard) {
        innerQrCard.addEventListener('click', function() {
            if (btnCopy) btnCopy.click();
        });
    }

    if (btnCopy) {
        btnCopy.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!currentAccNum) return;
            navigator.clipboard.writeText(currentAccNum).then(function() {
                btnCopy.innerHTML = '<i class="fa-solid fa-check"></i> Đã sao chép STK!';
                btnCopy.style.borderColor = '#86efac';
                btnCopy.style.color = '#166534';
                btnCopy.style.background = '#f0fdf4';
                if (typeof window.muopShowToast === 'function') {
                    window.muopShowToast('Đã sao chép số tài khoản: ' + currentAccNum, 'success');
                }
                setTimeout(function() {
                    btnCopy.innerHTML = '<i class="fa-solid fa-copy"></i> Sao chép STK';
                    btnCopy.style.borderColor = '#0284c7';
                    btnCopy.style.color = '#0284c7';
                    btnCopy.style.background = '#f0f9ff';
                }, 2200);
            }).catch(function() {
                prompt('Số tài khoản chuyển khoản:', currentAccNum);
            });
        });
    }

    // Submit lưu form cấu hình donate của dịch giả
    if (formConfig) {
        formConfig.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!btnSubmit) return;

            var origHtml = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...';
            btnSubmit.disabled = true;

            if (editAlert) editAlert.style.display = 'none';

            var fd = new FormData(formConfig);
            fd.append('action', 'muop_save_team_donate_info');
            fd.append('nonce', (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '');

            var ajaxUrl = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = origHtml;

                    if (res && res.success && res.data) {
                        var d = res.data;
                        currentAccNum = d.account_number || '';
                        
                        if (currentDonateData) {
                            currentDonateData.has_donate = true;
                            currentDonateData.qr_url = d.qr_url;
                            currentDonateData.bank_code = d.bank_code;
                            currentDonateData.bank_name = d.bank_name;
                            currentDonateData.account_number = d.account_number;
                            currentDonateData.account_name = d.account_name;
                        }

                        // Cập nhật thẻ độc giả
                        if (qrImg) qrImg.src = d.qr_url;
                        if (accNameEl) accNameEl.textContent = d.account_name || '';
                        if (accNumEl) accNumEl.textContent = d.account_number || '';
                        if (bankNameEl) bankNameEl.textContent = d.bank_name || d.bank_code || '';

                        // Cập nhật nút thành "Cập Nhật"
                        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> <span>Cập Nhật</span>';

                        // Thumbnail
                        if (existingQrBox && existingQrThumb) {
                            existingQrThumb.src = d.qr_url;
                            existingQrBox.style.display = 'flex';
                        }

                        if (editAlert) {
                            editAlert.style.display = 'block';
                            editAlert.className = 'alert-box alert-success';
                            editAlert.style.background = '#f0fdf4';
                            editAlert.style.color = '#166534';
                            editAlert.style.border = '1px solid #bbf7d0';
                            editAlert.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + (d.message || 'Lưu thông tin thành công!');
                        }

                        // Tự động chuyển sang xem trước sau 1.2s để dịch giả thấy thẻ VietQR
                        setTimeout(function() {
                            switchView('preview');
                        }, 1200);
                    } else {
                        if (editAlert) {
                            editAlert.style.display = 'block';
                            editAlert.className = 'alert-box alert-error';
                            editAlert.style.background = '#fef2f2';
                            editAlert.style.color = '#991b1b';
                            editAlert.style.border = '1px solid #fecaca';
                            editAlert.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + ((res && res.data && res.data.message) || 'Có lỗi xảy ra khi lưu.');
                        }
                    }
                })
                .catch(function() {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = origHtml;
                    if (editAlert) {
                        editAlert.style.display = 'block';
                        editAlert.className = 'alert-box alert-error';
                        editAlert.style.background = '#fef2f2';
                        editAlert.style.color = '#991b1b';
                        editAlert.style.border = '1px solid #fecaca';
                        editAlert.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Lỗi kết nối đến máy chủ!';
                    }
                });
        });
    }

    // Xóa mã QR
    if (btnDeleteQr) {
        btnDeleteQr.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Bạn có chắc chắn muốn xóa mã QR Donate này không?')) return;

            var fd = new FormData();
            fd.append('action', 'muop_save_team_donate_info');
            fd.append('delete_qr', '1');
            fd.append('team_id', (inputTeamId ? inputTeamId.value : 0));
            fd.append('author_id', (inputAuthorId ? inputAuthorId.value : 0));
            fd.append('nonce', (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '');

            var ajaxUrl = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res && res.success) {
                        if (currentDonateData) {
                            currentDonateData.has_donate = false;
                            currentDonateData.qr_url = '';
                        }
                        if (existingQrBox) existingQrBox.style.display = 'none';
                        if (inputQrFile) inputQrFile.value = '';
                        if (btnSubmit) btnSubmit.innerHTML = '<i class="fa-solid fa-plus"></i> <span>Thêm</span>';
                        if (editAlert) {
                            editAlert.style.display = 'block';
                            editAlert.className = 'alert-box alert-success';
                            editAlert.style.background = '#f0fdf4';
                            editAlert.style.color = '#166534';
                            editAlert.style.border = '1px solid #bbf7d0';
                            editAlert.innerHTML = '<i class="fa-solid fa-circle-check"></i> Đã xóa mã QR thành công!';
                        }
                    } else {
                        alert(res.data ? res.data.message : 'Không thể xóa mã QR.');
                    }
                });
        });
    }

    // Lắng nghe sự kiện click mở modal donate trên toàn hệ thống (cả trang team và trang chi tiết truyện)
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-donate-team, #btnOpenDonateTeamModal, .btn-donate-story, #btnDonateStoryDetail, [data-action="open-donate-modal"]');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            var teamId = parseInt(btn.getAttribute('data-team-id'), 10) || 0;
            var teamName = btn.getAttribute('data-team-name') || '';
            var authorId = parseInt(btn.getAttribute('data-author-id'), 10) || 0;
            var storyId = parseInt(btn.getAttribute('data-story-id'), 10) || 0;
            if (typeof window.muopOpenDonateModal === 'function') {
                window.muopOpenDonateModal(teamId, teamName, authorId, storyId);
            } else {
                var m = document.getElementById('modalDonateTeam');
                if (m) m.style.setProperty('display', 'flex', 'important');
            }
        }
    });
})();
</script>
