<?php
/**
 * Template Part: Modal Trình Phát Audio Truyện & Modal Quản Lý Audio Từng Chương
 * Dành cho độc giả nghe truyện và dịch giả/admin thêm audio
 */
if (!defined('ABSPATH')) exit;
if (defined('MUOP_MODAL_STORY_AUDIO_LOADED')) return;
define('MUOP_MODAL_STORY_AUDIO_LOADED', true);
?>

<!-- CSS Responsive Cho Modal Quản Lý Audio & Trình Phát Hình 3 -->
<style>
.manage-audio-form-grid {
    display: grid;
    grid-template-columns: 180px 1fr;
    gap: 12px;
    margin-bottom: 12px;
}
.muop-fig3-popup-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 18px 20px;
    margin-bottom: 18px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.05);
}
@media (max-width: 640px) {
    .manage-audio-form-grid {
        grid-template-columns: 1fr !important;
        gap: 10px !important;
    }
    #modalManageStoryAudio .custom-modal-content,
    #modalStoryAudioPlayer .custom-modal-content {
        margin: 8px !important;
        max-height: 96vh !important;
        width: 95% !important;
    }
    .modal-audio-body {
        padding: 12px 14px !important;
    }
    #manageAudioFileInput {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .manage-audio-table-wrap {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .manage-audio-table {
        min-width: 480px !important;
    }
    .muop-fig3-popup-card {
        padding: 14px 12px !important;
    }
}
</style>

<!-- 1. MODAL TRÌNH PHÁT AUDIO TRUYỆN (DÀNH CHO TẤT CẢ ĐỘC GIẢ - HÌNH 3 MÀU XANH LÁ) -->
<div id="modalStoryAudioPlayer" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.72); z-index: 999998; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 580px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1.5px solid #16a34a; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
        
        <!-- Header màu xanh lá -->
        <div style="background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-headphones"></i>
                </div>
                <div>
                    <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; font-weight: 700; display: block;">
                        Trình Phát Audio Truyện
                    </span>
                    <h3 id="playerModalStoryTitle" style="font-size: 15.5px; font-weight: 700; margin: 2px 0 0 0; color: #fff; line-height: 1.3;">
                        ...
                    </h3>
                </div>
            </div>
            <button type="button" onclick="window.muopCloseAudioPlayerModal && window.muopCloseAudioPlayerModal()" style="background: rgba(255,255,255,0.15); border: none; color: #fff; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 15px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="modal-audio-body" style="padding: 20px; overflow-y: auto; flex: 1;">
            <!-- Thẻ Card Figure 3 Trắng Bo Góc màu Xanh Lá -->
            <div class="muop-fig3-popup-card">
                <div style="font-size: 12.5px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-headphones" style="font-size: 14px;"></i>
                    <span>AUDIO ĐANG PHÁT</span>
                </div>

                <!-- Thẻ audio HTML5 ẩn để điều khiển -->
                <audio id="mainStoryHtmlAudio" preload="metadata" style="display: none;"></audio>

                <!-- Nút to màu xanh lá theo Hình 3 -->
                <button type="button" id="btnAudioPlayerPlayPause" style="width: 100%; height: 52px; border-radius: 12px; background: #16a34a; color: #ffffff; font-size: 15.5px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3); transition: all 0.2s ease;">
                    <i class="fa-solid fa-volume-high" id="playerMainIcon" style="font-size: 18px;"></i>
                    <span id="playerCurrentChapterTitle">Chưa chọn chương audio</span>
                </button>

                <!-- Hàng chuyển tiếp chương (Chương trước / Chương tiếp theo) -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 10px; gap: 8px;">
                    <button type="button" id="btnAudioPlayerPrev" class="btn btn-outline btn-sm" style="border-radius: 8px; font-size: 12px; color: #16a34a; border-color: #86efac; font-weight: 600; padding: 4px 12px;" title="Chương audio trước">
                        <i class="fa-solid fa-backward-step"></i> Chương trước
                    </button>
                    <button type="button" id="btnAudioPlayerNext" class="btn btn-outline btn-sm" style="border-radius: 8px; font-size: 12px; color: #16a34a; border-color: #86efac; font-weight: 600; padding: 4px 12px;" title="Chương audio tiếp theo">
                        Chương tiếp theo <i class="fa-solid fa-forward-step"></i>
                    </button>
                </div>

                <!-- Thanh tua thời gian (Seekbar) -->
                <div style="display: flex; align-items: center; gap: 12px; margin: 12px 0 8px 0;">
                    <span id="audioPlayerCurrentTime" style="font-size: 13px; font-weight: 700; color: #4b5563; min-width: 42px;">00:00</span>
                    <div style="flex: 1; position: relative; display: flex; align-items: center;">
                        <input type="range" id="audioPlayerSeekBar" min="0" max="100" value="0" style="width: 100%; accent-color: #16a34a; cursor: pointer; height: 6px; border-radius: 3px;" />
                    </div>
                    <span id="audioPlayerDuration" style="font-size: 13px; font-weight: 700; color: #4b5563; min-width: 42px; text-align: right;">00:00</span>
                </div>

                <!-- Thanh trạng thái & Tốc độ phát -->
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; margin-top: 4px;">
                    <span id="audioPlayerStatusText" style="font-weight: 700; color: #4b5563;">Sẵn sàng phát</span>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 13px; color: #4b5563; font-weight: 600;">Tốc độ</span>
                        <select id="playerSpeedSelect" style="border: 1px solid #d1d5db; border-radius: 8px; padding: 4px 10px; font-size: 13px; font-weight: 700; background: #fff; color: #111827; cursor: pointer; outline: none;">
                            <option value="0.75">0.75x</option>
                            <option value="1.0" selected>1.0x</option>
                            <option value="1.25">1.25x</option>
                            <option value="1.5">1.5x</option>
                            <option value="1.75">1.75x</option>
                            <option value="2.0">2.0x</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Danh Sách Phát Các Chương Có Audio (Chương 1, 2, 3, 4...) -->
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <h4 style="font-size: 13.5px; font-weight: 700; color: var(--text-color); margin: 0; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-list-ul" style="color: #16a34a;"></i> Danh Sách Chương Có Audio:
                    </h4>
                    <span id="playerAudioCountBadge" class="badge" style="background: #dcfce7; color: #15803d; font-size: 11.5px; padding: 3px 8px; border-radius: var(--radius-full); font-weight: 700;">
                        0 chương
                    </span>
                </div>

                <div id="playerPlaylistContainer" style="display: flex; flex-direction: column; gap: 6px; max-height: 240px; overflow-y: auto; padding-right: 4px;">
                    <!-- Danh sách chương audio được nạp bằng JavaScript -->
                    <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 13px;">
                        <i class="fa-solid fa-spinner fa-spin" style="margin-right: 6px; color: #16a34a;"></i> Đang tải danh sách audio...
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="border-top: 1px solid var(--border-color); padding: 12px 20px; background: var(--bg-body); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px; color: var(--text-muted);">
                <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> Tự động chuyển chương audio tiếp theo (Chương 2, 3, 4...)
            </span>
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.muopCloseAudioPlayerModal && window.muopCloseAudioPlayerModal()">
                Đóng
            </button>
        </div>
    </div>
</div>

<!-- 2. MODAL QUẢN LÝ & THÊM AUDIO CHO TRUYỆN (DÀNH CHO DỊCH GIẢ & ADMIN - TỐI ƯU MOBILE) -->
<div id="modalManageStoryAudio" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.72); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 680px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1.5px solid #16a34a; overflow: hidden; display: flex; flex-direction: column; max-height: 92vh;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-file-audio"></i>
                </div>
                <div>
                    <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; font-weight: 700; display: block;">
                        Kênh Dịch Giả & Quản Trị
                    </span>
                    <h3 id="manageAudioModalStoryTitle" style="font-size: 16px; font-weight: 700; margin: 2px 0 0 0; color: #fff; line-height: 1.3;">
                        Thêm & Quản Lý Audio Truyện
                    </h3>
                </div>
            </div>
            <button type="button" onclick="window.muopCloseManageAudioModal && window.muopCloseManageAudioModal()" style="background: rgba(255,255,255,0.15); border: none; color: #fff; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 15px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="modal-audio-body" style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="manageAudioAlert" class="alert-box" style="display: none; margin-bottom: 16px;"></div>

            <!-- Khung Thêm / Cập Nhật Audio Nhanh Theo Chương (Tự động 1 cột trên Mobile) -->
            <div style="background: var(--pastel-green, #f0fdf4); border: 1.5px solid var(--border-color, #bbf7d0); border-radius: var(--radius-md); padding: 16px 18px; margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 700; color: var(--primary-green, #166534); margin: 0 0 10px 0; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Tải Lên / Cập Nhật Audio Cho Chương:
                </h4>

                <form id="formQuickUploadChapterAudio" enctype="multipart/form-data">
                    <input type="hidden" id="manageAudioStoryId" value="" />
                    
                    <div class="manage-audio-form-grid">
                        <div>
                            <label class="form-label" for="manageAudioSelectChapter" style="font-size: 12.5px; font-weight: 700;">Chọn Chương (*):</label>
                            <select id="manageAudioSelectChapter" class="form-input" style="height: 38px; font-size: 13px; width: 100%; box-sizing: border-box;" required>
                                <option value="">-- Chọn chương --</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label" for="manageAudioFileInput" style="font-size: 12.5px; font-weight: 700;">Chọn File Audio (.mp3, .m4a, .wav):</label>
                            <input type="file" id="manageAudioFileInput" name="audio_file" class="form-input" accept="audio/*,.mp3,.m4a,.wav,.ogg,.aac" style="padding: 6px 10px; font-size: 12.5px; height: 38px; width: 100%; box-sizing: border-box;" />
                        </div>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label class="form-label" for="manageAudioUrlInput" style="font-size: 12px; font-weight: 600; color: var(--text-muted);">Hoặc dán link Audio trực tiếp:</label>
                        <input type="url" id="manageAudioUrlInput" class="form-input" placeholder="https://example.com/audio/chuong-1.mp3" style="padding: 7px 12px; font-size: 12.5px;" />
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="submit" id="btnSubmitSaveChapterAudio" class="btn btn-primary" style="padding: 7px 18px; font-size: 13px; background: #16a34a; border-color: #15803d;">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu Audio Chương Này
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bảng Danh Sách Toàn Bộ Chương & Trạng Thái Audio (Cuộn ngang êm ái trên Mobile) -->
            <div>
                <h4 style="font-size: 13.5px; font-weight: 700; color: var(--text-color); margin: 0 0 10px 0; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-table-list" style="color: #16a34a;"></i> Trạng Thái Audio Từng Chương:</span>
                    <span id="manageAudioStatsSummary" style="font-size: 12px; color: var(--text-muted); font-weight: normal;">Đang nạp...</span>
                </h4>

                <div class="table-responsive manage-audio-table-wrap" style="max-height: 280px; overflow-y: auto; overflow-x: auto; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                    <table class="data-table manage-audio-table" style="width: 100%; margin: 0; font-size: 12.5px;">
                        <thead style="position: sticky; top: 0; background: var(--bg-body); z-index: 2;">
                            <tr>
                                <th style="width: 70px; text-align: center;">Chương</th>
                                <th>Tên Chương</th>
                                <th style="width: 130px; text-align: center;">Audio</th>
                                <th style="width: 75px; text-align: center;">Lượt nghe</th>
                                <th style="width: 140px; text-align: center;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="manageAudioTableBody">
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 25px; color: var(--text-muted);">
                                    <i class="fa-solid fa-spinner fa-spin" style="color: #16a34a;"></i> Đang nạp danh sách chương...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="border-top: 1px solid var(--border-color); padding: 12px 20px; background: var(--bg-body); display: flex; justify-content: flex-end; align-items: center;">
            <button type="button" class="btn btn-secondary" onclick="window.muopCloseManageAudioModal && window.muopCloseManageAudioModal()" style="padding: 7px 18px; font-size: 13px;">
                Đóng
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    var currentAudioList = [];
    var currentPlayingIndex = -1;
    var currentStoryId = 0;
    var currentStoryTitle = '';
    var speeds = [1.0, 1.25, 1.5, 2.0, 0.75];
    var currentSpeedIndex = 0;

    var playerModal = document.getElementById('modalStoryAudioPlayer');
    var manageModal = document.getElementById('modalManageStoryAudio');
    var audioElement = document.getElementById('mainStoryHtmlAudio');
    var seekBar = document.getElementById('audioPlayerSeekBar');
    var curTimeEl = document.getElementById('audioPlayerCurrentTime');
    var durTimeEl = document.getElementById('audioPlayerDuration');
    var btnPlayPause = document.getElementById('btnAudioPlayerPlayPause');
    var btnSpeed = document.getElementById('btnAudioPlayerSpeed');
    var btnPrev = document.getElementById('btnAudioPlayerPrev');
    var btnNext = document.getElementById('btnAudioPlayerNext');

    var ajaxUrl = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/wp-admin/admin-ajax.php';
    var nonceVal = (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '';

    function formatTime(seconds) {
        if (isNaN(seconds) || seconds < 0) return '00:00';
        var mins = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);
        return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }

    // Ghi nhận lượt nghe vào CSDL
    function logAudioListen(storyId, chapterId, chapterNum) {
        if (!storyId || !chapterId) return;
        var fd = new FormData();
        fd.append('action', 'muop_record_audio_listen');
        fd.append('story_id', storyId);
        fd.append('chapter_id', chapterId);
        fd.append('chapter_num', chapterNum || 1);
        fetch(ajaxUrl, { method: 'POST', body: fd }).catch(function(){});
    }

    // =========================================================================
    // 1. TRÌNH PHÁT AUDIO PLAYER (HÌNH 3 MÀU XANH LÁ)
    // =========================================================================
    var speedSelectEl = document.getElementById('playerSpeedSelect');
    var statusTextEl = document.getElementById('audioPlayerStatusText');
    var mainIconEl = document.getElementById('playerMainIcon');

    function playTrackIndex(idx) {
        if (!currentAudioList || !currentAudioList[idx]) return;
        currentPlayingIndex = idx;
        var track = currentAudioList[idx];

        var titleEl = document.getElementById('playerCurrentChapterTitle');
        if (titleEl) titleEl.textContent = 'Chương ' + track.chapter_num + ': ' + track.title;
        if (statusTextEl) statusTextEl.textContent = 'Đang phát...';

        if (audioElement) {
            audioElement.src = track.audio_url;
            var curRate = speedSelectEl ? parseFloat(speedSelectEl.value) : 1.0;
            audioElement.playbackRate = curRate || 1.0;
            audioElement.play().then(function() {
                if (mainIconEl) mainIconEl.className = 'fa-solid fa-pause';
                if (btnPlayPause) btnPlayPause.style.background = '#15803d';
                logAudioListen(currentStoryId, track.id, track.chapter_num);
            }).catch(function(e) {
                if (mainIconEl) mainIconEl.className = 'fa-solid fa-volume-high';
                if (btnPlayPause) btnPlayPause.style.background = '#16a34a';
            });
        }

        updateNavButtons();
        renderPlayerPlaylist();
    }

    function updateNavButtons() {
        if (btnPrev) {
            btnPrev.disabled = (currentPlayingIndex <= 0);
            btnPrev.style.opacity = (currentPlayingIndex <= 0) ? '0.5' : '1';
        }
        if (btnNext) {
            btnNext.disabled = (currentPlayingIndex + 1 >= currentAudioList.length);
            btnNext.style.opacity = (currentPlayingIndex + 1 >= currentAudioList.length) ? '0.5' : '1';
        }
    }

    function renderPlayerPlaylist() {
        var container = document.getElementById('playerPlaylistContainer');
        if (!container) return;

        if (!currentAudioList || !currentAudioList.length) {
            container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 13px;"><i class="fa-solid fa-circle-info"></i> Bộ truyện này hiện chưa có chương nào được tải audio. Dịch giả sẽ sớm cập nhật!</div>';
            return;
        }

        var html = '';
        currentAudioList.forEach(function(item, idx) {
            var isCurrent = (idx === currentPlayingIndex);
            var itemBg = isCurrent ? '#dcfce7' : 'var(--bg-card)';
            var itemBorder = isCurrent ? '1.5px solid #16a34a' : '1px solid var(--border-color)';
            var titleColor = isCurrent ? '#15803d' : 'var(--text-color)';
            var icon = isCurrent ? '<i class="fa-solid fa-volume-high" style="color: #16a34a;"></i>' : '<i class="fa-regular fa-circle-play" style="color: var(--text-muted);"></i>';

            html += '<div onclick="window.muopPlayAudioIndex(' + idx + ')" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: ' + itemBg + '; border: ' + itemBorder + '; border-radius: var(--radius-sm); cursor: pointer; transition: all 0.15s ease;">' +
                '<div style="display: flex; align-items: center; gap: 10px; overflow: hidden;">' +
                    '<span style="font-size: 15px;">' + icon + '</span>' +
                    '<div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
                        '<strong style="color: ' + titleColor + '; font-size: 13px;">Chương ' + item.chapter_num + ':</strong> ' +
                        '<span style="color: var(--text-muted); font-size: 12.5px;">' + (item.title || '') + '</span>' +
                    '</div>' +
                '</div>' +
                '<div style="display: flex; align-items: center; gap: 8px;">' +
                    (item.audio_views > 0 ? '<span style="font-size: 11px; color: var(--text-muted); white-space: nowrap;"><i class="fa-solid fa-headphones" style="color: #16a34a;"></i> ' + Number(item.audio_views).toLocaleString() + '</span>' : '') +
                    (isCurrent ? '<span class="badge" style="background: #16a34a; color: #fff; font-size: 10.5px; padding: 2px 7px; border-radius: 4px; font-weight: 700;">Đang phát</span>' : '<span style="font-size: 11.5px; color: #16a34a; font-weight: 600;">Phát <i class="fa-solid fa-play" style="font-size: 10px;"></i></span>') +
                '</div>' +
            '</div>';
        });

        container.innerHTML = html;
    }

    window.muopPlayAudioIndex = function(idx) {
        playTrackIndex(idx);
    };

    // Mở Modal Trình Phát Audio
    window.muopOpenStoryAudioPlayer = function(storyId, storyTitle, autoPlayChapterId) {
        currentStoryId = storyId || currentStoryId;
        currentStoryTitle = storyTitle || currentStoryTitle;
        currentPlayingIndex = -1;

        if (playerModal) {
            var stEl = document.getElementById('playerModalStoryTitle');
            if (stEl) stEl.textContent = currentStoryTitle || 'Bộ truyện';
            playerModal.style.display = 'flex';
        }

        var fd = new FormData();
        fd.append('action', 'muop_get_story_audio_list');
        fd.append('story_id', currentStoryId);

        fetch(ajaxUrl, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.data) {
                    currentAudioList = res.data.audio_chapters || [];
                    var countBadge = document.getElementById('playerAudioCountBadge');
                    if (countBadge) countBadge.textContent = currentAudioList.length + ' chương';

                    if (currentAudioList.length > 0) {
                        var targetIdx = 0;
                        if (autoPlayChapterId) {
                            var found = currentAudioList.findIndex(function(it) { return it.id == autoPlayChapterId; });
                            if (found !== -1) targetIdx = found;
                        }
                        playTrackIndex(targetIdx);
                    } else {
                        var curTitle = document.getElementById('playerCurrentChapterTitle');
                        if (curTitle) curTitle.textContent = 'Chưa có chương nào có audio';
                        renderPlayerPlaylist();
                    }
                } else {
                    currentAudioList = [];
                    renderPlayerPlaylist();
                }
            })
            .catch(function() {
                currentAudioList = [];
                renderPlayerPlaylist();
            });
    };

    window.muopCloseAudioPlayerModal = function() {
        if (playerModal) playerModal.style.display = 'none';
        if (audioElement) audioElement.pause();
    };

    // Sự kiện Audio Element
    if (audioElement) {
        audioElement.addEventListener('timeupdate', function() {
            if (audioElement.duration) {
                var progress = (audioElement.currentTime / audioElement.duration) * 100;
                if (seekBar) seekBar.value = progress;
                if (curTimeEl) curTimeEl.textContent = formatTime(audioElement.currentTime);
                if (durTimeEl) durTimeEl.textContent = formatTime(audioElement.duration);
            }
        });

        audioElement.addEventListener('ended', function() {
            // Tự động chuyển tiếp sang chương kế tiếp (Chương 2, 3, 4...)
            if (currentPlayingIndex + 1 < currentAudioList.length) {
                if (statusTextEl) statusTextEl.textContent = 'Đang chuyển tiếp sang chương kế tiếp...';
                setTimeout(function() {
                    playTrackIndex(currentPlayingIndex + 1);
                }, 800);
            } else {
                if (mainIconEl) mainIconEl.className = 'fa-solid fa-rotate-right';
                if (statusTextEl) statusTextEl.textContent = 'Đã nghe hết tất cả chương!';
                if (btnPlayPause) btnPlayPause.style.background = '#16a34a';
            }
        });

        audioElement.addEventListener('play', function() {
            if (mainIconEl) mainIconEl.className = 'fa-solid fa-pause';
            if (statusTextEl) statusTextEl.textContent = 'Đang phát...';
            if (btnPlayPause) btnPlayPause.style.background = '#15803d';
        });

        audioElement.addEventListener('pause', function() {
            if (mainIconEl) mainIconEl.className = 'fa-solid fa-volume-high';
            if (statusTextEl) statusTextEl.textContent = 'Đã tạm dừng';
            if (btnPlayPause) btnPlayPause.style.background = '#16a34a';
        });
    }

    if (seekBar && audioElement) {
        seekBar.addEventListener('input', function() {
            if (audioElement.duration) {
                audioElement.currentTime = (seekBar.value / 100) * audioElement.duration;
            }
        });
    }

    if (btnPlayPause && audioElement) {
        btnPlayPause.addEventListener('click', function() {
            if (audioElement.paused) {
                if (!audioElement.src && currentAudioList.length > 0) {
                    playTrackIndex(0);
                } else {
                    audioElement.play();
                }
            } else {
                audioElement.pause();
            }
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', function() {
            if (currentPlayingIndex > 0) {
                playTrackIndex(currentPlayingIndex - 1);
            }
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', function() {
            if (currentPlayingIndex + 1 < currentAudioList.length) {
                playTrackIndex(currentPlayingIndex + 1);
            }
        });
    }

    if (speedSelectEl && audioElement) {
        speedSelectEl.addEventListener('change', function() {
            var sp = parseFloat(this.value) || 1.0;
            audioElement.playbackRate = sp;
        });
    }

    // =========================================================================
    // 2. MODAL QUẢN LÝ AUDIO CHO DỊCH GIẢ & ADMIN
    // =========================================================================
    window.muopOpenManageAudioModal = function(storyId, storyTitle) {
        currentStoryId = storyId || currentStoryId;
        var modal = document.getElementById('modalManageStoryAudio');
        if (!modal) return;

        var sidEl = document.getElementById('manageAudioStoryId');
        if (sidEl) sidEl.value = currentStoryId;
        var stEl = document.getElementById('manageAudioModalStoryTitle');
        if (stEl) stEl.textContent = storyTitle ? ('Quản Lý Audio: ' + storyTitle) : 'Thêm & Quản Lý Audio';
        var alertEl = document.getElementById('manageAudioAlert');
        if (alertEl) alertEl.style.display = 'none';

        loadManageAudioChapters(currentStoryId);
        modal.style.display = 'flex';
    };

    window.muopCloseManageAudioModal = function() {
        if (manageModal) manageModal.style.display = 'none';
    };

    function loadManageAudioChapters(storyId) {
        var tbody = document.getElementById('manageAudioTableBody');
        var select = document.getElementById('manageAudioSelectChapter');
        if (tbody) tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 25px; color: var(--text-muted);"><i class="fa-solid fa-spinner fa-spin"></i> Đang nạp danh sách chương...</td></tr>';
        if (select) select.innerHTML = '<option value="">-- Chọn chương --</option>';

        var fd = new FormData();
        fd.append('action', 'muop_get_story_audio_list');
        fd.append('story_id', storyId);

        fetch(ajaxUrl, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.data) {
                    var allChaps = res.data.all_chapters || [];
                    var audioCount = res.data.audio_count || 0;
                    var summaryEl = document.getElementById('manageAudioStatsSummary');
                    if (summaryEl) summaryEl.textContent = audioCount + ' / ' + allChaps.length + ' chương đã có audio';

                    if (!allChaps.length) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: var(--text-muted);">Bộ truyện này hiện chưa có chương nào. Vui lòng thêm chương trước!</td></tr>';
                        return;
                    }

                    var rows = '';
                    var opts = '<option value="">-- Chọn chương cần thêm/sửa audio --</option>';

                    allChaps.forEach(function(ch) {
                        opts += '<option value="' + ch.id + '">Chương ' + ch.chapter_num + ': ' + ch.title + (ch.has_audio ? ' (Đã có audio)' : '') + '</option>';

                        var badge = ch.has_audio 
                            ? '<span class="badge" style="background:#e0f2fe; color:#0284c7; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Đã có audio</span>' 
                            : '<span class="badge" style="background:#f1f5f9; color:#64748b;"><i class="fa-solid fa-circle-xmark"></i> Chưa có</span>';

                        var playBtn = ch.has_audio 
                            ? '<button type="button" class="btn btn-outline btn-xs" onclick="window.muopOpenStoryAudioPlayer(' + storyId + ', \'' + (currentStoryTitle || '').replace(/'/g, "\\'") + '\', ' + ch.id + ')" style="padding: 2px 7px; color:#0284c7; border-color:#bae6fd;" title="Nghe thử"><i class="fa-solid fa-play"></i> Nghe</button>' 
                            : '';

                        var delBtn = ch.has_audio 
                            ? '<button type="button" class="btn btn-outline btn-xs" onclick="window.muopDeleteChapterAudio(' + storyId + ', ' + ch.id + ')" style="padding: 2px 7px; color:#dc2626; border-color:#fecaca;" title="Xóa audio"><i class="fa-solid fa-trash-can"></i> Xóa</button>' 
                            : '';

                        var selectBtn = '<button type="button" class="btn btn-outline btn-xs" onclick="window.muopSelectChapterForAudio(' + ch.id + ')" style="padding: 2px 7px;" title="Chọn chương này để thêm/đổi audio"><i class="fa-solid fa-upload"></i> Nạp</button>';

                        rows += '<tr>' +
                            '<td style="text-align: center; font-weight: 700;">' + ch.chapter_num + '</td>' +
                            '<td>' +
                                '<div style="font-weight: 600; color: var(--text-color);">' + ch.title + '</div>' +
                                (ch.audio_url ? '<div style="font-size: 11px; color: var(--text-muted); font-family: monospace; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 250px;">' + ch.audio_url + '</div>' : '') +
                            '</td>' +
                            '<td style="text-align: center;">' + badge + '</td>' +
                            '<td style="text-align: center; font-weight: 600; color: #0284c7;">' + Number(ch.audio_views || 0).toLocaleString() + '</td>' +
                            '<td style="text-align: center;"><div style="display: inline-flex; gap: 4px;">' + playBtn + selectBtn + delBtn + '</div></td>' +
                        '</tr>';
                    });

                    tbody.innerHTML = rows;
                    select.innerHTML = opts;
                }
            });
    }

    window.muopSelectChapterForAudio = function(chapterId) {
        var select = document.getElementById('manageAudioSelectChapter');
        if (select) {
            select.value = chapterId;
            select.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            select.focus();
        }
    };

    window.muopDeleteChapterAudio = function(storyId, chapterId) {
        if (!confirm('Bạn có chắc chắn muốn xóa file audio của chương này không?')) return;
        var fd = new FormData();
        fd.append('action', 'muop_save_chapter_audio');
        fd.append('action_type', 'delete');
        fd.append('story_id', storyId);
        fd.append('chapter_id', chapterId);
        fd.append('nonce', nonceVal);

        fetch(ajaxUrl, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    loadManageAudioChapters(storyId);
                } else {
                    alert(res.data && res.data.message ? res.data.message : 'Có lỗi khi xóa audio!');
                }
            });
    };

    // Submit form thêm/sửa audio chương
    var formAudio = document.getElementById('formQuickUploadChapterAudio');
    if (formAudio) {
        formAudio.addEventListener('submit', function(e) {
            e.preventDefault();
            var storyId = document.getElementById('manageAudioStoryId').value;
            var chapterId = document.getElementById('manageAudioSelectChapter').value;
            var fileInput = document.getElementById('manageAudioFileInput');
            var urlInput = document.getElementById('manageAudioUrlInput');
            var btn = document.getElementById('btnSubmitSaveChapterAudio');

            if (!chapterId) {
                alert('Vui lòng chọn chương cần thêm/sửa audio!');
                return;
            }
            if ((!fileInput.files || !fileInput.files[0]) && (!urlInput.value || !urlInput.value.trim())) {
                alert('Vui lòng chọn file audio từ máy hoặc dán link audio!');
                return;
            }

            var origHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang tải lên...';

            var fd = new FormData();
            fd.append('action', 'muop_save_chapter_audio');
            fd.append('story_id', storyId);
            fd.append('chapter_id', chapterId);
            fd.append('nonce', nonceVal);
            if (fileInput.files && fileInput.files[0]) {
                fd.append('audio_file', fileInput.files[0]);
            }
            if (urlInput.value) {
                fd.append('audio_url', urlInput.value.trim());
            }

            fetch(ajaxUrl, { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                    if (res.success) {
                        fileInput.value = '';
                        urlInput.value = '';
                        loadManageAudioChapters(storyId);
                        var alertEl = document.getElementById('manageAudioAlert');
                        if (alertEl) {
                            alertEl.style.display = 'block';
                            alertEl.className = 'alert-box alert-success';
                            alertEl.style.background = '#f0fdf4';
                            alertEl.style.color = '#166534';
                            alertEl.style.border = '1px solid #bbf7d0';
                            alertEl.style.padding = '10px 14px';
                            alertEl.style.borderRadius = '6px';
                            alertEl.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + (res.data && res.data.message ? res.data.message : 'Đã cập nhật audio thành công!');
                        }
                    } else {
                        alert(res.data && res.data.message ? res.data.message : 'Có lỗi khi lưu audio!');
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                    alert('Lỗi kết nối máy chủ!');
                });
        });
    }

    // Backdrop click đóng modal
    [playerModal, manageModal].forEach(function(m) {
        if (m) {
            m.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (this === playerModal) window.muopCloseAudioPlayerModal();
                    if (this === manageModal) window.muopCloseManageAudioModal();
                }
            });
        }
    });

    // Delegated click listener cho các nút mở Audio
    document.addEventListener('click', function(e) {
        var btnPlay = e.target.closest ? e.target.closest('#btnListenStoryAudio, .btn-listen-story-audio') : null;
        if (btnPlay) {
            e.preventDefault();
            var sid = btnPlay.getAttribute('data-story-id') || currentStoryId;
            var stitle = btnPlay.getAttribute('data-story-title') || document.title;
            window.muopOpenStoryAudioPlayer(sid, stitle);
            return;
        }

        var btnManage = e.target.closest ? e.target.closest('#btnManageStoryAudio, .btn-manage-story-audio') : null;
        if (btnManage) {
            e.preventDefault();
            var msid = btnManage.getAttribute('data-story-id') || currentStoryId;
            var mstitle = btnManage.getAttribute('data-story-title') || document.title;
            window.muopOpenManageAudioModal(msid, mstitle);
            return;
        }
    });
})();
</script>
