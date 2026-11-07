<?php
/**
 * Template Name: Trang Thêm Chương Mới
 * Dành cho Dịch Giả & Quản Trị Viên
 */
if (!defined('ABSPATH')) exit;

$current_user  = wp_get_current_user();
$is_admin      = current_user_can('administrator');
$is_translator = muop_user_is_translator($current_user->ID);

$from = isset($_GET['from']) ? sanitize_text_field($_GET['from']) : '';
$from_admin = (isset($_GET['from_admin']) && $_GET['from_admin'] == '1') || ($from === 'admin');

if (empty($from)) {
    if ($from_admin) {
        $from = 'admin';
    } elseif (isset($_SERVER['HTTP_REFERER'])) {
        if (strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false) {
            $from = 'admin';
        } elseif (strpos($_SERVER['HTTP_REFERER'], 'thong-tin-dich-gia') !== false) {
            $from = 'translator';
        } elseif (strpos($_SERVER['HTTP_REFERER'], '/truyen/') !== false) {
            $from = 'story';
        }
    }
}

if (!is_user_logged_in() || (!$is_translator && !$is_admin)) {
    if ($is_admin) {
        wp_redirect(home_url('/quan-ly-admin/'));
        exit;
    }
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$selected_story_id = isset($_GET['story_id']) ? intval($_GET['story_id']) : 0;
$unauthorized_story_error = false;

// Query stories: Dịch giả chỉ được thêm chương vào truyện của mình; Admin quản lý toàn bộ truyện team Mướp Đắng
$story_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => array('publish', 'pending', 'draft', 'future')
);
if ($is_admin && $from_admin) {
    $user_stories = get_posts($story_args);
} elseif ($is_admin) {
    $all_temp_stories = get_posts($story_args);
    $user_stories = array_values(array_filter($all_temp_stories, function($st) use ($current_user) {
        return ((int)$st->post_author === (int)$current_user->ID || muop_is_story_of_team_muop_dang($st->ID));
    }));
} else {
    $story_args['author'] = $current_user->ID;
    $user_stories = get_posts($story_args);
}

// Nếu có chọn story_id từ URL, kiểm tra quyền sở hữu
if ($selected_story_id) {
    $target_story = get_post($selected_story_id);
    $can_manage_selected = false;
    if ($target_story) {
        if ($is_admin) {
            $can_manage_selected = true;
        } elseif ((int)$target_story->post_author === (int)$current_user->ID) {
            $can_manage_selected = true;
        }
    }
    if (!$can_manage_selected) {
        $selected_story_id = 0;
        $unauthorized_story_error = true;
    }
}

// Calculate default next chapter number & selected story info
$next_chap_num = 1;
$selected_story_title = '';
$selected_story_permalink = '';
if ($selected_story_id) {
    $existing = muop_get_story_chapters($selected_story_id);
    $next_chap_num = count($existing) + 1;
    $selected_story_obj = get_post($selected_story_id);
    if ($selected_story_obj) {
        $selected_story_title = $selected_story_obj->post_title;
        $base_perm = get_permalink($selected_story_obj->ID);
        if ($from === 'translator') {
            $selected_story_permalink = add_query_arg('from', 'translator', $base_perm);
        } elseif ($from === 'admin') {
            $selected_story_permalink = add_query_arg('from', 'admin', $base_perm);
        } else {
            $selected_story_permalink = $base_perm;
        }
    }
}
?>

<div class="container">
    <div class="dashboard-wrapper" style="max-width: 860px; margin: 0 auto;">
        <nav class="breadcrumbs" id="themChuongBreadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>

            <?php if ($from === 'admin') : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>">Quản Trị Hệ Thống</a>
            <?php elseif ($from === 'translator') : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">Kênh Dịch Giả</a>
            <?php endif; ?>
            
            <span id="breadcrumbStoryItem" style="<?php echo empty($selected_story_title) ? 'display: none;' : 'display: inline-flex; align-items: center; gap: 6px;'; ?>">
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a id="breadcrumbStoryLink" href="<?php echo esc_url($selected_story_permalink ?: '#'); ?>" style="color: var(--text-main); font-weight: 500;">
                    <span id="breadcrumbStoryText"><?php echo esc_html($selected_story_title); ?></span>
                </a>
            </span>

            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Thêm Chương Mới</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-file-circle-plus"></i> Thêm Chương Truyện Mới
            </h1>
        </div>

        <?php if (!empty($unauthorized_story_error)) : ?>
            <div class="alert-box alert-error" style="display: block; margin-bottom: 16px;">
                <i class="fa-solid fa-circle-exclamation"></i> Bạn không có quyền thêm chương cho truyện của dịch giả khác! Vui lòng chọn bộ truyện do chính bạn sáng tác/dịch.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['added']) && $_GET['added'] == '1') : ?>
            <div class="alert-box alert-success" style="display: block; margin-bottom: 16px;">
                <i class="fa-solid fa-circle-check"></i> Thêm chương mới thành công! Hệ thống đã tự động giữ bộ truyện và nạp số chương tiếp theo, bạn có thể tiếp tục thêm chương mới ngay dưới đây.
            </div>
        <?php endif; ?>

        <div id="chapterAlert" class="alert-box"></div>

        <form id="formSubmitChapter" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="muop_submit_chapter" />
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
            <input type="hidden" name="is_admin_portal" value="<?php echo ($is_admin && $from_admin) ? '1' : '0'; ?>" />
            <div style="background: var(--pastel-green); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 22px;">
                <!-- Chọn Truyện -->
                <div class="form-group">
                    <label class="form-label" for="selectStory">Chọn Bộ Truyện (*)</label>
                    <select id="selectStory" name="story_id" class="form-input" required>
                        <option value="">-- Chọn truyện cần thêm chương --</option>
                        <?php foreach ($user_stories as $st) : 
                            $opt_url = get_permalink($st->ID);
                            if ($from === 'translator') {
                                $opt_url = add_query_arg('from', 'translator', $opt_url);
                            } elseif ($from === 'admin') {
                                $opt_url = add_query_arg('from', 'admin', $opt_url);
                            }
                            $st_tag = '';
                            if ($st->post_status === 'future') {
                                $st_tag = ' (⏰ Đang hẹn giờ)';
                            } elseif ($st->post_status === 'draft') {
                                $st_tag = ' (👁️ Đang ẩn / Bản nháp)';
                            } elseif ($st->post_status === 'pending') {
                                $st_tag = ' (⏳ Chờ duyệt)';
                            }
                        ?>
                            <option value="<?php echo esc_attr($st->ID); ?>" data-url="<?php echo esc_url($opt_url); ?>" <?php selected($st->ID, $selected_story_id); ?>>
                                <?php echo esc_html($st->post_title . $st_tag); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 140px 1fr; gap: 16px;">
                    <!-- Số chương -->
                    <div class="form-group">
                        <label class="form-label" for="chapNumber">Số Chương (*)</label>
                        <input type="number" id="chapNumber" name="chapter_number" class="form-input" value="<?php echo esc_attr($next_chap_num); ?>" min="1" required />
                    </div>

                    <!-- Tên chương -->
                    <div class="form-group">
                        <label class="form-label" for="chapTitle">Tên Chương (*)</label>
                        <input type="text" id="chapTitle" name="chapter_title" class="form-input" placeholder="Ví dụ: Gặp lại người cũ, Lời tỏ tình bất ngờ..." required />
                    </div>
                </div>

                <!-- Nội dung chương -->
                <div class="form-group" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" for="chapContent" style="margin-bottom: 0;">Nội Dung Chương (*)</label>
                        <button type="button" class="btn btn-outline btn-scan-sensitive btn-sm" data-target="#chapContent" style="padding: 5px 14px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;" title="Rà soát và thay thế các từ ngữ nhạy cảm trong chương">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Duyệt Nội Dung
                        </button>
                    </div>
                    <textarea id="chapContent" name="content" class="form-input form-textarea-chapter" rows="16" style="min-height: 380px; font-size: 14.5px; line-height: 1.65; padding: 12px 14px;" placeholder="Dán hoặc nhập toàn bộ nội dung chương truyện vào đây..." required></textarea>
                    
                    <!-- Thanh hiển thị số từ thời gian thực -->
                    <div class="chapter-word-count-bar" style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; flex-wrap: wrap; gap: 8px;">
                        <span style="font-size: 12.5px; color: var(--text-muted);">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Nhập hoặc dán nội dung để hệ thống tự động tính số từ.
                        </span>
                        <div class="chapter-word-count-badge" style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 5px 14px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; color: var(--text-main); display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <i class="fa-solid fa-pen-nib" style="color: var(--primary-green);"></i>
                            <span>Số từ: <strong id="chapContentWordCount" style="color: var(--primary-green); font-size: 14.5px; font-weight: 700;">0</strong> từ</span>
                            <span style="color: var(--border-color); margin: 0 2px;">|</span>
                            <span style="font-size: 12px; color: var(--text-muted);"><span id="chapContentCharCount">0</span> ký tự</span>
                        </div>
                    </div>
                </div>

                <!-- Mục Tải Lên & Tạo Audio Cho Chương (Thu Nhỏ - Nhấn Vào Mới Sổ Ra) -->
                <div class="form-group" style="margin-top: 18px; padding-top: 18px; border-top: 1px dashed var(--border-color); margin-bottom: 0;">
                    <!-- Thanh Tiêu Đề Thu Nhỏ (Accordion Toggle Header) -->
                    <div id="btnToggleChapterAudioSection" role="button" tabindex="0" onclick="window.muopToggleAddChapterAudio && window.muopToggleAddChapterAudio()" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.muopToggleAddChapterAudio&&window.muopToggleAddChapterAudio();}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #ffffff; border: 1.5px solid #86efac; border-radius: var(--radius-sm); cursor: pointer; user-select: none; transition: all 0.25s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: #dcfce7; color: #16a34a; border-radius: 50%;">
                                <i class="fa-solid fa-headphones" style="font-size: 16px;"></i>
                            </span>
                            <div>
                                <span style="font-weight: 700; font-size: 14.5px; color: var(--primary-green);">Tạo Audio Cho Chương</span>
                                <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 500; margin-left: 6px;">Nghe đọc audio</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span id="chapterAudioToggleStatusText" style="font-size: 12.5px; color: var(--text-muted); font-weight: 500;">Bấm để mở</span>
                            <span id="chapterAudioToggleIcon" style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #f0fdf4; color: var(--primary-green); transition: transform 0.3s ease;">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Khối Nội Dung Sổ Ra Khi Bấm (Mặc Định Thu Gọn) -->
                    <div id="chapterAudioCollapsibleBody" style="display: none; margin-top: 14px;">
                        <p style="font-size: 12.5px; color: var(--text-muted); margin: 0 0 12px 0; line-height: 1.5;">
                            Bạn có thể tải lên file âm thanh (.mp3, .m4a, .wav, .ogg, .aac) từ thiết bị hoặc sử dụng công cụ <strong>Tạo Audio</strong> bên dưới để tự động chuyển văn bản thành giọng đọc truyền cảm cho chương truyện.
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px; background: var(--bg-card); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                            <!-- Upload File Từ Máy Tính -->
                            <div>
                                <label class="form-label" for="inputChapterAudioFile" style="font-size: 12.5px; font-weight: 600; margin-bottom: 6px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Chọn file Audio từ thiết bị:
                                </label>
                                <input type="file" id="inputChapterAudioFile" name="chapter_audio" class="form-input" accept="audio/*,.mp3,.m4a,.wav,.ogg,.aac,.flac" style="padding: 6px 10px; font-size: 13px;" onchange="window.muopPreviewChapterAudio && window.muopPreviewChapterAudio(this)" />
                                <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">Hỗ trợ: MP3, M4A, WAV, AAC, OGG, FLAC.</small>
                            </div>

                            <!-- Hoặc Nhập Link Audio -->
                            <div>
                                <label class="form-label" for="inputChapterAudioUrl" style="font-size: 12.5px; font-weight: 600; margin-bottom: 6px;">
                                    <i class="fa-solid fa-link"></i> Hoặc dán đường dẫn link Audio:
                                </label>
                                <input type="url" id="inputChapterAudioUrl" name="chapter_audio_url" class="form-input" placeholder="https://.../audio.mp3" style="padding: 8px 12px; font-size: 13px;" oninput="window.muopPreviewChapterAudioUrl && window.muopPreviewChapterAudioUrl(this.value)" />
                                <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">Link file âm thanh lưu trữ trực tuyến (nếu có).</small>
                            </div>
                        </div>

                        <!-- Chuyển Đổi Văn Bản Sang Giọng Nói (Tạo Audio) -->
                        <div style="margin-top: 14px; background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: var(--radius-sm); padding: 14px 16px;">
                            <!-- Lựa chọn 1: Dùng lại giọng mẫu đã lưu của dịch giả hoặc Giọng Gốc Chuẩn -->
                            <div id="addChapterSavedVoiceGroup" style="margin-bottom: 12px; background: #ffffff; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 12px;">
                                <label style="font-size: 12.5px; font-weight: 700; color: #166534; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                    <i class="fa-solid fa-bookmark" style="color: #16a34a;"></i> 1. Dùng giọng mẫu có sẵn hoặc đã lưu của bạn:
                                </label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <select id="addChapterSavedVoiceSelect" class="form-input" style="flex: 1; padding: 6px 10px; font-size: 12.5px; height: 38px; background: #fff;">
                                        <option value="">-- Đang nạp danh sách giọng mẫu... --</option>
                                    </select>
                                    <button type="button" id="btnPreviewSavedVoiceAddPage" class="btn btn-outline btn-xs" style="height: 38px; padding: 0 12px; font-size: 12px; border-color: #86efac; color: #16a34a; white-space: nowrap;" title="Nghe thử giọng mẫu này">
                                        <i class="fa-solid fa-play"></i> Nghe thử
                                    </button>
                                    <button type="button" id="btnDeleteSavedVoiceAddPage" class="btn btn-outline btn-xs" style="height: 38px; padding: 0 10px; font-size: 12px; border-color: #fecaca; color: #dc2626;" title="Xóa giọng mẫu này khỏi kho cá nhân">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                                <audio id="addSavedVoiceAudioPreview" style="display: none;"></audio>
                            </div>
                            
                            <!-- Lựa chọn 2: Tải lên file giọng mới kèm Nút Phân Tích Giọng -->
                            <div style="margin-bottom: 10px;">
                                <label class="form-label" for="inputTtsReferenceVoice" style="font-size: 12px; font-weight: 600; margin-bottom: 4px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> 2. Hoặc tải lên file giọng mẫu mới (.mp3, .wav):
                                </label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="file" id="inputTtsReferenceVoice" accept="audio/*,.mp3,.wav,.m4a" class="form-input" style="padding: 6px 10px; font-size: 12.5px; height: 38px; flex: 1; box-sizing: border-box;" />
                                    <button type="button" id="btnAnalyzeVoiceChapter" class="btn btn-outline" style="height: 38px; padding: 0 14px; font-size: 12.5px; border-color: #86efac; color: #166534; font-weight: 700; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;" title="Phân tích chất giọng mẫu này trước khi tạo audio">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> Phân Tích Giọng
                                    </button>
                                </div>
                            </div>

                            <!-- Thẻ kết quả phân tích chất giọng -->
                            <div id="addChapterVoiceAnalysisCard" style="display: none; margin-top: 10px; background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; color: #065f46; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-microphone-lines" style="color: #10b981; font-size: 16px;"></i>
                                <span id="addChapterVoiceAnalysisText">Đang phân tích giọng...</span>
                            </div>

                            <!-- Nút Tạo Audio -->
                            <div style="margin-top: 12px;">
                                <button type="button" id="btnTriggerTtsConvert" class="btn btn-primary" style="height: 42px; width: 100%; font-size: 14px; font-weight: 700; background: #16a34a; border-color: #15803d; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 3px 10px rgba(22, 163, 74, 0.25);">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Tạo Audio
                                </button>
                            </div>

                            <div id="ttsConvertStatusBox" class="alert-box" style="display: none; margin-top: 10px; font-size: 12.5px; padding: 8px 12px;"></div>
                        </div>

                        <!-- Audio Preview Player -->
                        <div id="chapterAudioPreviewWrap" style="display: none; margin-top: 14px; padding: 12px 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-sm); align-items: center; gap: 14px; flex-wrap: wrap;">
                            <span style="font-size: 13px; font-weight: 700; color: #166534; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-circle-play" style="color: var(--primary-green);"></i> Nghe thử Audio:
                            </span>
                            <audio id="chapterAudioPreviewPlayer" controls style="height: 36px; max-width: 320px;"></audio>
                            <button type="button" class="btn btn-outline btn-xs" onclick="window.muopClearChapterAudio && window.muopClearChapterAudio()" style="color: #dc2626; border-color: #fecaca; padding: 5px 12px; font-size: 12px;">
                                <i class="fa-solid fa-trash-can"></i> Hủy audio đã chọn
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            // Các hàm preview audio toàn cục
            window.muopPreviewChapterAudio = function(input) {
                var wrap = document.getElementById('chapterAudioPreviewWrap');
                var player = document.getElementById('chapterAudioPreviewPlayer');
                if (!wrap || !player) return;
                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    player.src = URL.createObjectURL(file);
                    wrap.style.display = 'flex';
                }
            };
            window.muopPreviewChapterAudioUrl = function(url) {
                var wrap = document.getElementById('chapterAudioPreviewWrap');
                var player = document.getElementById('chapterAudioPreviewPlayer');
                if (!wrap || !player) return;
                if (url && url.trim().length > 5) {
                    player.src = url.trim();
                    wrap.style.display = 'flex';
                }
            };
            window.muopClearChapterAudio = function() {
                var fileInput = document.getElementById('inputChapterAudioFile');
                var urlInput = document.getElementById('inputChapterAudioUrl');
                var wrap = document.getElementById('chapterAudioPreviewWrap');
                var player = document.getElementById('chapterAudioPreviewPlayer');
                if (fileInput) fileInput.value = '';
                if (urlInput) urlInput.value = '';
                if (player) {
                    player.pause();
                    player.src = '';
                }
                if (wrap) wrap.style.display = 'none';
            };

            // Quản lý Accordion Thu Nhỏ / Sổ Ra & Tính Năng Tạo Audio
            (function() {
                // 1. Accordion Toggle
                var toggleBtn = document.getElementById('btnToggleChapterAudioSection');
                var collBody = document.getElementById('chapterAudioCollapsibleBody');
                var toggleIcon = document.getElementById('chapterAudioToggleIcon');
                var toggleText = document.getElementById('chapterAudioToggleStatusText');

                if (toggleBtn && collBody) {
                    function doToggleAudio() {
                        var isHidden = (collBody.style.display === 'none' || !collBody.style.display || window.getComputedStyle(collBody).display === 'none');
                        if (isHidden) {
                            collBody.style.display = 'block';
                            if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
                            if (toggleText) toggleText.textContent = 'Thu nhỏ lại';
                            toggleBtn.style.background = '#f0fdf4';
                            setTimeout(function() {
                                try {
                                    if (toggleBtn && typeof toggleBtn.scrollIntoView === 'function') {
                                        toggleBtn.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    }
                                } catch(err) {}
                            }, 120);
                        } else {
                            collBody.style.display = 'none';
                            if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
                            if (toggleText) toggleText.textContent = 'Bấm để mở';
                            toggleBtn.style.background = '#ffffff';
                        }
                    }
                    window.muopToggleAddChapterAudio = doToggleAudio;
                    toggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        doToggleAudio();
                    });
                    toggleBtn.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            doToggleAudio();
                        }
                    });
                }

                // 2. Logic Nạp Giọng Mẫu & Tạo Audio
                var btnConvert = document.getElementById('btnTriggerTtsConvert');
                var savedVoiceSelect = document.getElementById('addChapterSavedVoiceSelect');
                var btnPreviewSaved = document.getElementById('btnPreviewSavedVoiceAddPage');
                var btnDeleteSaved = document.getElementById('btnDeleteSavedVoiceAddPage');
                var savedAudioPreview = document.getElementById('addSavedVoiceAudioPreview');
                var analysisCard = document.getElementById('addChapterVoiceAnalysisCard');
                var analysisText = document.getElementById('addChapterVoiceAnalysisText');
                var refFileEl = document.getElementById('inputTtsReferenceVoice');
                var statusBox = document.getElementById('ttsConvertStatusBox');

                var ajaxUrl = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/wp-admin/admin-ajax.php';
                var currentSavedVoices = [];

                function renderSavedVoices(voices, selectId) {
                    currentSavedVoices = voices || [];
                    if (!savedVoiceSelect) return;
                    if (!currentSavedVoices || currentSavedVoices.length === 0) {
                        savedVoiceSelect.innerHTML = '<option value="system_default_voice" data-url="/wp-content/uploads/audio/giong_goc_chuan_he_thong.mp3">Giọng Gốc (Chuẩn Mặc Định - Dùng Chung)</option>';
                        return;
                    }
                    var html = '';
                    currentSavedVoices.forEach(function(v) {
                        var profStr = '';
                        if (v.profile && v.profile.voice_label) profStr = ' • ' + v.profile.voice_label;
                        var isSel = (selectId && selectId === v.id) ? ' selected' : (v.id === 'system_default_voice' ? ' selected' : '');
                        var aUrl = v.audio_url || '';
                        if (v.id === 'system_default_voice' && !aUrl) {
                            aUrl = '/wp-content/uploads/audio/giong_goc_chuan_he_thong.mp3';
                        }
                        html += '<option value="' + v.id + '" data-url="' + aUrl + '"' + isSel + '>' + (v.name || 'Giọng Mẫu') + profStr + '</option>';
                    });
                    savedVoiceSelect.innerHTML = html;
                }

                function fetchSavedVoices(selectId) {
                    fetch(ajaxUrl + '?action=muop_get_my_saved_voices')
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (res && res.success && res.data) {
                            renderSavedVoices(res.data.voices, selectId);
                        } else {
                            renderSavedVoices([], selectId);
                        }
                    })
                    .catch(function() {
                        renderSavedVoices([], selectId);
                    });
                }
                fetchSavedVoices();

                // Nghe thử giọng mẫu
                if (btnPreviewSaved && savedVoiceSelect && savedAudioPreview) {
                    btnPreviewSaved.addEventListener('click', function(e) {
                        e.preventDefault();
                        var opt = savedVoiceSelect.options[savedVoiceSelect.selectedIndex];
                        var url = opt ? opt.getAttribute('data-url') : '';
                        if (!url && opt && opt.value === 'system_default_voice') {
                            url = '/wp-content/uploads/audio/giong_goc_chuan_he_thong.mp3';
                        }
                        if (!url) {
                            alert('Vui lòng chọn một giọng mẫu từ danh sách trước khi nghe thử!');
                            return;
                        }
                        if (savedAudioPreview.src && savedAudioPreview.src.indexOf(url) !== -1 && !savedAudioPreview.paused) {
                            savedAudioPreview.pause();
                            btnPreviewSaved.innerHTML = '<i class="fa-solid fa-play"></i> Nghe thử';
                        } else {
                            savedAudioPreview.src = url;
                            savedAudioPreview.play().catch(function() {});
                            btnPreviewSaved.innerHTML = '<i class="fa-solid fa-pause"></i> Tạm dừng';
                            savedAudioPreview.onended = function() {
                                btnPreviewSaved.innerHTML = '<i class="fa-solid fa-play"></i> Nghe thử';
                            };
                        }
                    });
                }

                // Xóa giọng mẫu khỏi kho của dịch giả
                if (btnDeleteSaved && savedVoiceSelect) {
                    btnDeleteSaved.addEventListener('click', function(e) {
                        e.preventDefault();
                        var vid = savedVoiceSelect.value;
                        if (!vid) {
                            alert('Vui lòng chọn giọng mẫu bạn muốn xóa!');
                            return;
                        }
                        if (vid === 'system_default_voice') {
                            alert('Đây là giọng gốc chuẩn mặc định của hệ thống dùng chung, không thể xóa!');
                            return;
                        }
                        if (!confirm('Bạn có chắc chắn muốn xóa giọng mẫu này khỏi kho cá nhân của bạn?')) return;

                        var delFd = new FormData();
                        delFd.append('action', 'muop_delete_my_saved_voice');
                        delFd.append('voice_id', vid);
                        delFd.append('nonce', (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '');

                        fetch(ajaxUrl, { method: 'POST', body: delFd })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            if (res.success) {
                                fetchSavedVoices();
                            } else {
                                alert(res.data ? res.data.message : 'Không thể xóa giọng mẫu!');
                            }
                        });
                    });
                }

                // Phân Tích Giọng
                var btnAnalyze = document.getElementById('btnAnalyzeVoiceChapter');
                if (btnAnalyze) {
                    btnAnalyze.addEventListener('click', function(e) {
                        e.preventDefault();
                        var file = refFileEl && refFileEl.files && refFileEl.files[0];
                        var savedVid = savedVoiceSelect ? savedVoiceSelect.value : '';

                        if (!file && !savedVid) {
                            alert('Vui lòng chọn 1 giọng mẫu đã lưu hoặc chọn file âm thanh (.mp3, .wav) để phân tích!');
                            return;
                        }

                        if (savedVid === 'system_default_voice' && !file) {
                            if (analysisCard) analysisCard.style.display = 'flex';
                            if (analysisText) {
                                analysisText.innerHTML = '✅ <strong style="color: #15803d;">Đã phân tích xong!</strong> Nhận diện: Giọng Nữ (Hoài My - Chuẩn Ngọt Ngào, Dễ Nghe) — 210Hz. Bấm <strong>"Tạo Audio"</strong> để tạo audio.';
                            }
                            return;
                        }

                        btnAnalyze.disabled = true;
                        btnAnalyze.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang phân tích...';

                        if (analysisCard) analysisCard.style.display = 'flex';
                        if (analysisText) analysisText.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang phân tích âm điệu, màu giọng và cao độ...';

                        var fd = new FormData();
                        fd.append('action', 'muop_analyze_voice_sample');
                        if (savedVid && !file) {
                            fd.append('saved_voice_id', savedVid);
                        } else if (file) {
                            fd.append('reference_voice', file);
                        }
                        fd.append('nonce', (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '');

                        fetch(ajaxUrl, { method: 'POST', body: fd })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            btnAnalyze.disabled = false;
                            btnAnalyze.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Phân Tích Giọng';

                            if (res && res.success && res.data) {
                                var vp = res.data.voice_profile || {};
                                var label = res.data.voice_name || vp.voice_label || 'Giọng Mẫu';
                                if (analysisText) {
                                    analysisText.innerHTML = '✅ <strong style="color: #15803d;">Đã phân tích xong!</strong> Nhận diện: ' + label + ' (Tần số: ' + (vp.pitch_hz || '210') + 'Hz). Bấm <strong>"Tạo Audio"</strong> để tạo audio.';
                                }
                                if (res.data.is_new_saved && res.data.voice_id) {
                                    fetchSavedVoices(res.data.voice_id);
                                }
                            } else {
                                if (analysisText) {
                                    analysisText.innerHTML = '<span style="color: #dc2626;"><i class="fa-solid fa-circle-exclamation"></i> ' + ((res && res.data && res.data.message) || 'Lỗi khi phân tích giọng') + '</span>';
                                }
                            }
                        })
                        .catch(function() {
                            btnAnalyze.disabled = false;
                            btnAnalyze.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Phân Tích Giọng';
                            if (analysisText) {
                                analysisText.innerHTML = '<span style="color: #dc2626;"><i class="fa-solid fa-circle-exclamation"></i> Không thể kết nối máy chủ phân tích giọng.</span>';
                            }
                        });
                    });
                }

                // Khi bấm nút "Tạo Audio"
                if (btnConvert) {
                    btnConvert.addEventListener('click', function(e) {
                        e.preventDefault();
                        var contentEl = document.getElementById('chapContent');
                        var contentText = contentEl ? contentEl.value.trim() : '';
                        if (!contentText || contentText.length < 5) {
                            if (statusBox) {
                                statusBox.style.display = 'block';
                                statusBox.className = 'alert-box alert-error';
                                statusBox.style.background = '#fef2f2';
                                statusBox.style.color = '#991b1b';
                                statusBox.style.border = '1px solid #fecaca';
                                statusBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Vui lòng nhập hoặc dán nội dung văn bản chương truyện ở trên trước khi tạo audio!';
                            }
                            return;
                        }

                        var selectedSavedVoiceId = savedVoiceSelect ? savedVoiceSelect.value : 'system_default_voice';
                        var hasNewFile = (refFileEl && refFileEl.files && refFileEl.files[0]);

                        var storySelect = document.getElementById('selectStory');
                        var storyId = storySelect ? storySelect.value : '<?php echo esc_js($selected_story_id); ?>';

                        var origBtnHtml = btnConvert.innerHTML;
                        btnConvert.disabled = true;
                        btnConvert.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang tạo audio chương...';

                        if (statusBox) {
                            statusBox.style.display = 'block';
                            statusBox.className = 'alert-box alert-info';
                            statusBox.style.background = '#eff6ff';
                            statusBox.style.color = '#1e40af';
                            statusBox.style.border = '1px solid #bfdbfe';
                            statusBox.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang tổng hợp giọng nói Hoài My/Nam Minh truyền cảm, mượt mà... Vui lòng đợi trong giây lát!';
                        }

                        var fd = new FormData();
                        fd.append('action', 'muop_convert_tts_chapter_audio');
                        fd.append('nonce', (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '');
                        fd.append('story_id', storyId || 0);
                        fd.append('chapter_id', 0);
                        fd.append('content', contentText);
                        if (selectedSavedVoiceId && !hasNewFile) {
                            fd.append('saved_voice_id', selectedSavedVoiceId);
                        } else if (hasNewFile) {
                            fd.append('reference_voice', refFileEl.files[0]);
                        }

                        fetch(ajaxUrl, { method: 'POST', body: fd })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            btnConvert.disabled = false;
                            btnConvert.innerHTML = origBtnHtml;

                            if (res && res.success && res.data && res.data.audio_url) {
                                var audioUrl = res.data.audio_url;
                                var urlInput = document.getElementById('inputChapterAudioUrl');
                                if (urlInput) urlInput.value = audioUrl;

                                var previewWrap = document.getElementById('chapterAudioPreviewWrap');
                                var previewPlayer = document.getElementById('chapterAudioPreviewPlayer');
                                if (previewWrap && previewPlayer) {
                                    previewPlayer.src = audioUrl;
                                    previewWrap.style.display = 'flex';
                                }

                                if (res.data.saved_voice && res.data.saved_voice.id) {
                                    fetchSavedVoices(res.data.saved_voice.id);
                                }

                                if (statusBox) {
                                    statusBox.style.display = 'block';
                                    statusBox.className = 'alert-box alert-success';
                                    statusBox.style.background = '#f0fdf4';
                                    statusBox.style.color = '#166534';
                                    statusBox.style.border = '1px solid #bbf7d0';
                                    statusBox.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + (res.data.message || 'Tạo audio chương thành công!') + ' Đường dẫn audio đã được gắn tự động.';
                                }
                            } else {
                                var err = (res && res.data && res.data.message) || (res && res.message) || 'Lỗi khi tạo audio!';
                                if (statusBox) {
                                    statusBox.style.display = 'block';
                                    statusBox.className = 'alert-box alert-error';
                                    statusBox.style.background = '#fef2f2';
                                    statusBox.style.color = '#991b1b';
                                    statusBox.style.border = '1px solid #fecaca';
                                    statusBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + err;
                                }
                            }
                        })
                        .catch(function(err) {
                            btnConvert.disabled = false;
                            btnConvert.innerHTML = origBtnHtml;
                            if (statusBox) {
                                statusBox.style.display = 'block';
                                statusBox.className = 'alert-box alert-error';
                                statusBox.style.background = '#fef2f2';
                                statusBox.style.color = '#991b1b';
                                statusBox.style.border = '1px solid #fecaca';
                                statusBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Không thể kết nối đến máy chủ!';
                            }
                        });
                    });
                }
            })();
            </script>

            <input type="hidden" name="is_draft" id="isChapterDraftInput" value="0" />

            <!-- Buttons Thêm, Lưu Nháp & Hủy -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex-wrap: wrap;">
                <?php
                if ($from === 'admin') {
                    $cancel_url = home_url('/quan-ly-admin/');
                } elseif ($from === 'story' && !empty($selected_story_permalink)) {
                    $cancel_url = $selected_story_permalink;
                } else {
                    $cancel_url = home_url('/thong-tin-dich-gia/');
                }
                ?>
                <a href="<?php echo esc_url($cancel_url); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Hủy
                </a>
                <button type="button" id="btnSaveDraftNewChapter" class="btn btn-outline" style="padding: 10px 22px; font-size: 14.5px;" title="Lưu nháp chương này để đăng sau">
                    <i class="fa-solid fa-file-pen"></i> Lưu Nháp
                </button>
                <button type="submit" id="btnSubmitPublishChapter" class="btn btn-primary" style="padding: 10px 28px; font-size: 15px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Thêm Chương Này
                </button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
