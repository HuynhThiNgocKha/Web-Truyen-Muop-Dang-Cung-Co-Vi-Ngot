<?php
/**
 * Template Name: Trang Thông Tin / Hồ Sơ Cá Nhân
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap/'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$registered   = date('d/m/Y', strtotime($current_user->user_registered));
$avatar_char  = strtoupper(substr($current_user->display_name, 0, 1));
$custom_avatar = get_user_meta($user_id, 'muop_user_avatar', true);
$user_bio     = get_user_meta($user_id, 'description', true);

global $wpdb;
// Get Bookmarked stories
$table_bm = $wpdb->prefix . 'reading_bookmarks';
$bookmarked_ids = $wpdb->get_col($wpdb->prepare("SELECT story_id FROM $table_bm WHERE user_id = %d ORDER BY created_at DESC", $user_id)) ?: array();

// Get Reading History
$table_hist = $wpdb->prefix . 'reading_history';
$history_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_hist WHERE user_id = %d ORDER BY updated_at DESC LIMIT 20", $user_id)) ?: array();

// Preset avatars
$preset_avatars = array(
    get_template_directory_uri() . '/assets/images/logo-icon.png' => 'Bé Mướp Đắng',
    'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($current_user->user_login) . '&backgroundColor=b6e3f4,c0aede,d1d4f9' => 'Robot Đáng Yêu',
    'https://api.dicebear.com/7.x/adventurer/svg?seed=' . urlencode($current_user->user_login) => 'Phiêu Lưu',
    'https://api.dicebear.com/7.x/fun-emoji/svg?seed=' . urlencode($current_user->user_login) => 'Emoji Vui Vẻ',
    'https://api.dicebear.com/7.x/notionists/svg?seed=' . urlencode($current_user->user_login) => 'Phong Cách Vẽ',
);
?>

<div class="container" style="padding-top: 20px; padding-bottom: 40px;">
    <div class="dashboard-wrapper">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Thông Tin Tài Khoản</span>
        </nav>

        <!-- PROFILE HEADER CARD -->
        <div class="profile-header-card">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-display" id="profileHeaderAvatarDisplay">
                    <?php if (!empty($custom_avatar)) : ?>
                        <img src="<?php echo esc_url($custom_avatar); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="profile-avatar-img" />
                    <?php else : ?>
                        <span class="profile-avatar-text"><?php echo esc_html($avatar_char); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-header-info">
                <div class="profile-name-row">
                    <h1 class="profile-display-name" id="profileHeaderDisplayName"><?php echo esc_html($current_user->display_name); ?></h1>
                    <span class="badge badge-green">
                        <?php 
                            if (current_user_can('administrator')) echo '<i class="fa-solid fa-shield-halved"></i> Quản Trị Viên';
                            elseif (in_array('dich_gia', (array)$current_user->roles)) echo '<i class="fa-solid fa-feather-pointed"></i> Dịch Giả';
                            else echo '<i class="fa-solid fa-book-open-reader"></i> Độc Giả';
                        ?>
                    </span>
                </div>
                <p class="profile-meta-item">
                    <i class="fa-solid fa-envelope"></i> <span id="profileHeaderEmail"><?php echo esc_html($current_user->user_email); ?></span>
                </p>
                <p class="profile-meta-item">
                    <i class="fa-solid fa-calendar-check"></i> Ngày tham gia: <strong><?php echo esc_html($registered); ?></strong>
                </p>
                <?php if (!empty($user_bio)) : ?>
                    <p class="profile-bio-text" id="profileHeaderBio">"<?php echo esc_html($user_bio); ?>"</p>
                <?php endif; ?>
            </div>

            <div class="profile-header-actions">
                <?php if (current_user_can('administrator')) : ?>
                    <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>" class="btn btn-warning">
                        <i class="fa-solid fa-sliders"></i> Quản Lý Hệ Thống
                    </a>
                <?php elseif (in_array('dich_gia', (array)$current_user->roles)) : ?>
                    <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>" class="btn btn-primary">
                        <i class="fa-solid fa-feather-pointed"></i> Kênh Dịch Giả
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- TABS NAV -->
        <div class="dash-tabs-nav">
            <button type="button" class="dash-tab-btn active" data-tab="tabProfile">
                <i class="fa-solid fa-user-pen"></i> Thông Tin Cá Nhân
            </button>
            <button type="button" class="dash-tab-btn" data-tab="tabPassword">
                <i class="fa-solid fa-key"></i> Đổi Mật Khẩu
            </button>
            <button type="button" class="dash-tab-btn" data-tab="tabBookmarks">
                <i class="fa-solid fa-bookmark"></i> Tủ Truyện (<?php echo count($bookmarked_ids); ?>)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="tabHistory">
                <i class="fa-solid fa-clock-rotate-left"></i> Lịch Sử Đọc (<?php echo count($history_rows); ?>)
            </button>
            <button type="button" class="dash-tab-btn" data-tab="tabReadingSettings">
                <i class="fa-solid fa-sliders"></i> Cài Đặt Đọc Truyện
            </button>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: CẬP NHẬT THÔNG TIN (TÊN, AVATAR, BIO) -->
        <!-- ========================================== -->
        <div id="tabProfile" class="dash-tab-pane active">
            <div class="profile-form-card">
                <div id="profileAlertContainer"></div>

                <form id="profileUpdateForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="muop_update_profile" />
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                    <!-- Section: Avatar -->
                    <div class="form-section-header">
                        <h3><i class="fa-solid fa-image"></i> Ảnh Đại Diện (Avatar)</h3>
                        <p>Chọn ảnh từ điện thoại/máy tính hoặc dán link ảnh yêu thích</p>
                    </div>

                    <div class="avatar-edit-layout">
                        <!-- Preview Box -->
                        <div class="avatar-preview-box">
                            <div class="avatar-preview-circle" id="avatarPreviewCircle">
                                <?php if (!empty($custom_avatar)) : ?>
                                    <img src="<?php echo esc_url($custom_avatar); ?>" alt="Preview" id="avatarPreviewImg" />
                                <?php else : ?>
                                    <span class="avatar-preview-char" id="avatarPreviewChar"><?php echo esc_html($avatar_char); ?></span>
                                    <img src="" alt="Preview" id="avatarPreviewImg" style="display:none;" />
                                <?php endif; ?>
                            </div>
                            <span class="avatar-preview-label">Xem trước</span>
                        </div>

                        <!-- Upload & Options -->
                        <div class="avatar-options-box">
                            <div class="avatar-actions-row">
                                <input type="file" id="avatarFileInput" name="avatar_file" accept="image/png, image/jpeg, image/webp" style="display: none;" />
                                <button type="button" class="btn btn-secondary" id="btnChooseAvatarFile">
                                    <i class="fa-solid fa-upload"></i> Tải ảnh từ thiết bị
                                </button>
                                <span class="file-chosen-text" id="avatarFileNameDisplay">Chưa chọn tệp (Hỗ trợ JPG, PNG, WEBP)</span>
                            </div>

                            <div style="margin-top: 14px;">
                                <label for="avatarUrlInput" class="form-label" style="font-size: 13px;">Hoặc dán đường dẫn (URL) ảnh avatar online:</label>
                                <input type="url" id="avatarUrlInput" name="avatar_url" class="form-input" placeholder="https://example.com/anh-dai-dien.jpg" value="<?php echo esc_attr($custom_avatar); ?>" />
                            </div>

                            <!-- Preset Avatars -->
                            <div style="margin-top: 14px;">
                                <span class="form-label" style="font-size: 13px; display: block; margin-bottom: 8px;">Hoặc chọn nhanh avatar mẫu:</span>
                                <div class="preset-avatar-grid">
                                    <?php foreach ($preset_avatars as $p_url => $p_label) : ?>
                                        <button type="button" class="preset-avatar-btn <?php echo ($custom_avatar === $p_url) ? 'active' : ''; ?>" data-url="<?php echo esc_url($p_url); ?>" title="<?php echo esc_attr($p_label); ?>">
                                            <img src="<?php echo esc_url($p_url); ?>" alt="<?php echo esc_attr($p_label); ?>" />
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="form-divider" />

                    <!-- Section: Basic Info -->
                    <div class="form-section-header">
                        <h3><i class="fa-solid fa-id-badge"></i> Thông Tin Cơ Bản</h3>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="profileLoginName">Tên đăng nhập (Username):</label>
                            <input type="text" id="profileLoginName" class="form-input" value="<?php echo esc_attr($current_user->user_login); ?>" disabled style="background: var(--bg-body); opacity: 0.8; cursor: not-allowed;" />
                            <small class="form-hint">Tên dùng để đăng nhập hệ thống (cố định).</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="profileDisplayName">Tên hiển thị (Display Name): <span style="color: #e53935;">*</span></label>
                            <input type="text" id="profileDisplayName" name="display_name" class="form-input" value="<?php echo esc_attr($current_user->display_name); ?>" required placeholder="Nhập tên hiển thị của bạn..." />
                            <small class="form-hint">Tên xuất hiện khi bình luận và trên thanh điều hướng.</small>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label class="form-label" for="profileEmail">Địa chỉ Email: <span style="color: #e53935;">*</span></label>
                        <input type="email" id="profileEmail" name="user_email" class="form-input" value="<?php echo esc_attr($current_user->user_email); ?>" required placeholder="email@example.com" />
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label class="form-label" for="profileBio">Giới thiệu bản thân (Bio):</label>
                        <textarea id="profileBio" name="bio" class="form-textarea" rows="3" placeholder="Chia sẻ đôi nét về bạn, sở thích đọc truyện Zhihu, ngôn tình..."><?php echo esc_textarea($user_bio); ?></textarea>
                    </div>

                    <div style="margin-top: 24px; display: flex; align-items: center; gap: 12px;">
                        <button type="submit" class="btn btn-primary" id="btnSubmitProfile" style="padding: 10px 24px; font-size: 14px; font-weight: 700;">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
                        </button>
                        <span id="profileSavingSpinner" style="display: none; color: var(--text-muted); font-size: 13px;">
                            <i class="fa-solid fa-circle-notch fa-spin"></i> Đang lưu...
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: ĐỔI MẬT KHẨU                        -->
        <!-- ========================================== -->
        <div id="tabPassword" class="dash-tab-pane">
            <div class="profile-form-card" style="max-width: 620px;">
                <div id="passwordAlertContainer"></div>

                <div class="form-section-header">
                    <h3><i class="fa-solid fa-lock"></i> Đổi Mật Khẩu Tài Khoản</h3>
                    <p>Để bảo vệ tài khoản, hãy sử dụng mật khẩu mạnh có tối thiểu 6 ký tự</p>
                </div>

                <form id="passwordChangeForm">
                    <input type="hidden" name="action" value="muop_change_password" />
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="oldPasswordInput">Mật khẩu hiện tại: <span style="color: #e53935;">*</span></label>
                        <div class="password-input-wrap">
                            <input type="password" id="oldPasswordInput" name="old_password" class="form-input" required placeholder="Nhập mật khẩu hiện tại của bạn" />
                            <button type="button" class="btn-toggle-pwd" data-target="oldPasswordInput" aria-label="Xem mật khẩu">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="newPasswordInput">Mật khẩu mới: <span style="color: #e53935;">*</span></label>
                        <div class="password-input-wrap">
                            <input type="password" id="newPasswordInput" name="new_password" class="form-input" required minlength="6" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" />
                            <button type="button" class="btn-toggle-pwd" data-target="newPasswordInput" aria-label="Xem mật khẩu">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <small class="form-hint">Mật khẩu phải dài ít nhất 6 ký tự.</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label" for="confirmPasswordInput">Xác nhận mật khẩu mới: <span style="color: #e53935;">*</span></label>
                        <div class="password-input-wrap">
                            <input type="password" id="confirmPasswordInput" name="confirm_password" class="form-input" required minlength="6" placeholder="Nhập lại mật khẩu mới" />
                            <button type="button" class="btn-toggle-pwd" data-target="confirmPasswordInput" aria-label="Xem mật khẩu">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        <button type="submit" class="btn btn-primary" id="btnSubmitPassword" style="padding: 10px 24px; font-size: 14px; font-weight: 700;">
                            <i class="fa-solid fa-key"></i> Cập Nhật Mật Khẩu
                        </button>
                        <span id="passwordSavingSpinner" style="display: none; color: var(--text-muted); font-size: 13px;">
                            <i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: TỦ TRUYỆN ĐÃ LƯU                     -->
        <!-- ========================================== -->
        <div id="tabBookmarks" class="dash-tab-pane">
            <?php if (!empty($bookmarked_ids)) : ?>
                <div class="story-grid">
                    <?php
                    $query_bm = new WP_Query(array(
                        'post_type'      => 'truyen',
                        'post__in'       => $bookmarked_ids,
                        'posts_per_page' => -1,
                        'post_status'    => 'publish'
                    ));
                    while ($query_bm->have_posts()) : $query_bm->the_post();
                        get_template_part('template-parts/story-card');
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php else : ?>
                <div style="text-align: center; padding: 40px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted);">
                    <i class="fa-regular fa-bookmark" style="font-size: 36px; color: var(--avocado-green); margin-bottom: 10px;"></i>
                    <p>Tủ truyện của bạn đang trống. Hãy nhấn "Lưu Vào Tủ Truyện" ở trang mô tả để lưu truyện yêu thích nhé!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: LỊCH SỬ ĐỌC GẦN ĐÂY                 -->
        <!-- ========================================== -->
        <div id="tabHistory" class="dash-tab-pane">
            <?php if (!empty($history_rows)) : ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên Truyện</th>
                                <th>Chương Đã Đọc</th>
                                <th>Thời Gian</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history_rows as $row) : 
                                $story_post = get_post($row->story_id);
                                $chap_post  = get_post($row->chapter_id);
                                if (!$story_post) continue;
                            ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo esc_url(get_permalink($story_post->ID)); ?>" style="font-weight: 700; color: var(--primary-green);">
                                            <?php echo esc_html($story_post->post_title); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $chap_post ? esc_html($chap_post->post_title) : ('Chương ' . esc_html($row->chapter_num)); ?>
                                    </td>
                                    <td style="color: var(--text-muted); font-size: 12px;">
                                        <?php echo date('d/m/Y H:i', strtotime($row->updated_at)); ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url($chap_post ? get_permalink($chap_post->ID) : get_permalink($story_post->ID)); ?>" class="btn btn-primary" style="padding: 4px 12px; font-size: 12px;">
                                            <i class="fa-solid fa-play"></i> Đọc Tiếp
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div style="text-align: center; padding: 40px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted);">
                    <i class="fa-solid fa-book-open" style="font-size: 36px; color: var(--avocado-green); margin-bottom: 10px;"></i>
                    <p>Bạn chưa đọc chương truyện nào. Cùng khám phá truyện hay ngay thôi!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: CÀI ĐẶT ĐỌC TRUYỆN (ÁP DỤNG CHUNG) -->
        <!-- ========================================== -->
        <div id="tabReadingSettings" class="dash-tab-pane">
            <div class="profile-form-card">
                <div class="form-section-header">
                    <h3><i class="fa-solid fa-sliders"></i> Cài Đặt Giao Diện Đọc Truyện</h3>
                    <p>Tùy biến cỡ chữ, kiểu phông và màu sắc đọc truyện ưa thích. Áp dụng chung cho Độc giả, Dịch giả và Quản trị viên trên mọi thiết bị.</p>
                </div>

                <div id="profileReadingSettingsAlert"></div>

                <!-- KHUNG XEM TRƯỚC THỜI GIAN THỰC (LIVE PREVIEW) -->
                <div class="reading-preview-wrapper" id="profileReadingPreviewWrapper">
                    <div class="preview-header-bar">
                        <span class="preview-badge-tag"><i class="fa-solid fa-eye"></i> Xem Trước Trực Quan</span>
                        <div class="preview-theme-toggles">
                            <button type="button" class="btn-preview-theme active" data-preview-theme="light" title="Xem trên nền sáng"><i class="fa-regular fa-sun"></i> Sáng</button>
                            <button type="button" class="btn-preview-theme" data-preview-theme="dark" title="Xem trên nền tối"><i class="fa-solid fa-moon"></i> Tối</button>
                        </div>
                    </div>
                    <div class="preview-content-box" id="previewContentBox">
                        <h4 class="preview-title" id="previewTitle">Chương 1: Khởi Đầu Mới</h4>
                        <div class="preview-body-text" id="previewBodyText">
                            <p>Mỗi câu chuyện mở ra một thế giới đầy màu sắc và cảm xúc. Khung xem trước này giúp bạn kiểm tra độ tương phản, kiểu chữ và kích thước hiển thị phù hợp nhất với thị giác của mình.</p>
                            <p>Chúc bạn có những phút giây đọc truyện thật thư thái và trọn vẹn tại Mướp Đắng!</p>
                        </div>
                    </div>
                </div>

                <!-- BỘ ĐIỀU KHIỂN CÀI ĐẶT -->
                <div class="profile-settings-controls">
                    <!-- 1. Cỡ chữ -->
                    <div class="form-setting-group">
                        <label class="form-setting-title"><i class="fa-solid fa-text-height"></i> Cỡ chữ nội dung:</label>
                        <div class="size-adjuster-wrap">
                            <button type="button" class="font-size-btn-sm" id="btnProfileFontDec" title="Giảm cỡ chữ">A-</button>
                            <span class="size-val-display" id="profileSizeDisplay">21px</span>
                            <button type="button" class="font-size-btn-sm" id="btnProfileFontInc" title="Tăng cỡ chữ">A+</button>
                        </div>
                    </div>

                    <!-- 2. Kiểu chữ (Font) -->
                    <div class="form-setting-group">
                        <label class="form-setting-title"><i class="fa-solid fa-font"></i> Kiểu phông chữ (Font):</label>
                        <div class="setting-font-chips" id="profileFontChips">
                            <button type="button" class="font-chip-btn active" data-font="sans-serif">Sans-serif (Mặc định)</button>
                            <button type="button" class="font-chip-btn" data-font="merriweather">Merriweather (Sách báo)</button>
                            <button type="button" class="font-chip-btn" data-font="be-vietnam">Be Vietnam Pro</button>
                            <button type="button" class="font-chip-btn" data-font="georgia">Georgia (Cổ điển)</button>
                            <button type="button" class="font-chip-btn" data-font="times">Times New Roman</button>
                        </div>
                    </div>

                    <!-- 3. Màu chữ đọc truyện (5 màu đề xuất + màu tùy chọn) -->
                    <div class="form-setting-group">
                        <div class="setting-title-split">
                            <label class="form-setting-title"><i class="fa-solid fa-palette"></i> Màu chữ đọc truyện:</label>
                            <span class="setting-hint-text" id="profileColorModeHint">5 màu đề xuất phổ biến:</span>
                        </div>
                        <div class="setting-colors-palette" id="profileColorsPalette"></div>

                        <div class="custom-color-row" style="margin-top: 10px;">
                            <label for="inputProfileCustomColor" class="custom-color-label">
                                <i class="fa-solid fa-eye-dropper"></i> Tự chọn màu tùy thích:
                            </label>
                            <div class="custom-color-control">
                                <input type="color" id="inputProfileCustomColor" class="custom-color-input" value="#262626" />
                                <span class="custom-color-hex" id="profileCustomColorHex">#262626</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Hàng nút Lưu & Khôi phục -->
                    <div class="settings-submit-row" style="margin-top: 24px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <button type="button" class="btn btn-primary" id="btnSaveProfileReadingSettings">
                            <i class="fa-solid fa-check"></i> Lưu Cài Đặt Đọc Truyện
                        </button>
                        <button type="button" class="btn btn-secondary" id="btnResetProfileReadingSettings">
                            <i class="fa-solid fa-arrow-rotate-left"></i> Khôi Phục Mặc Định
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
