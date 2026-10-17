<?php
/**
 * Template Name: Trang Thông Tin / Hồ Sơ Cá Nhân
 * Trang chuyên biệt hiển thị và cập nhật thông tin tài khoản người dùng
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
$custom_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($custom_avatar) : $custom_avatar;
$user_bio     = get_user_meta($user_id, 'description', true);

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

        <!-- FORM CẬP NHẬT THÔNG TIN CÁ NHÂN -->
        <div class="profile-form-card" style="margin-top: 24px;">
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

        <?php if (in_array('dich_gia', (array)$current_user->roles) || current_user_can('administrator')) : 
            $bank_name    = get_user_meta($user_id, '_muop_bank_name', true) ?: '';
            $bank_holder  = get_user_meta($user_id, '_muop_bank_account_holder', true) ?: '';
            $bank_number  = get_user_meta($user_id, '_muop_bank_account_number', true) ?: '';
        ?>
        <!-- THÔNG TIN TÀI KHOẢN NGÂN HÀNG NHẬN NHUẬN BÚT (DÀNH CHO DỊCH GIẢ - ẢNH 1) -->
        <div class="profile-form-card" id="bankAccountSection" style="margin-top: 24px;">
            <div class="form-section-header">
                <h3><i class="fa-solid fa-building-columns"></i> Tài Khoản Nhận Nhuận Bút</h3>
                <p>Vui lòng điền chính xác tài khoản ngân hàng chính chủ để Admin đối soát và chuyển khoản tiền nhuận bút kèm gửi bill làm bằng chứng.</p>
            </div>

            <form id="formTranslatorBankInfo">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="bankAccountHolder">Tên Chủ Tài Khoản (Họ và tên): <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="bankAccountHolder" name="bank_account_holder" class="form-input" value="<?php echo esc_attr($bank_holder); ?>" placeholder="VD: NGUYEN VAN A" required style="text-transform: uppercase;" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="bankNameInput">Tên Ngân Hàng: <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="bankNameInput" name="bank_name" class="form-input" list="vietnamBanksList" value="<?php echo esc_attr($bank_name); ?>" placeholder="VD: MB Bank, Vietcombank, Techcombank..." required />
                        <datalist id="vietnamBanksList">
                            <option value="MB Bank (Quân Đội)">
                            <option value="Vietcombank">
                            <option value="Techcombank">
                            <option value="BIDV">
                            <option value="VietinBank">
                            <option value="Agribank">
                            <option value="VPBank">
                            <option value="ACB">
                            <option value="TPBank">
                            <option value="Sacombank">
                            <option value="VIB">
                            <option value="MSB">
                            <option value="SHB">
                            <option value="OCB">
                            <option value="Timo">
                            <option value="Cake by VPBank">
                        </datalist>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 14px;">
                    <label class="form-label" for="bankAccountNumber">Số Tài Khoản: <span style="color: #dc2626;">*</span></label>
                    <input type="text" id="bankAccountNumber" name="bank_account_number" class="form-input" value="<?php echo esc_attr($bank_number); ?>" placeholder="VD: 0987654321..." required />
                </div>

                <div style="margin-top: 20px; display: flex; align-items: center; gap: 12px;">
                    <button type="submit" class="btn btn-primary" id="btnSaveBankInfo" style="padding: 10px 24px; font-size: 14px; font-weight: 700;">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu Thông Tin Ngân Hàng
                    </button>
                    <span id="bankInfoSaveMsg" style="font-size: 13px; display: none;"></span>
                </div>
            </form>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php
get_footer();
