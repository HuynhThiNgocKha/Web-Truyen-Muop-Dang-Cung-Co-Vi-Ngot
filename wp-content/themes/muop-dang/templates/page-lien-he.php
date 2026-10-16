<?php
/**
 * Template Name: Trang Liên Hệ
 */
if (!defined('ABSPATH')) exit;

$msg_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $c_name  = sanitize_text_field($_POST['name'] ?? '');
    $c_email = sanitize_email($_POST['email'] ?? '');
    $c_subj  = sanitize_text_field($_POST['subject'] ?? '');
    $c_msg   = sanitize_textarea_field($_POST['message'] ?? '');

    if (!empty($c_name) && !empty($c_email) && !empty($c_msg)) {
        // Save as WordPress comment or option feedback
        global $wpdb;
        $table_feedback = $wpdb->prefix . 'comments';
        wp_insert_comment(array(
            'comment_post_ID'      => 0,
            'comment_author'       => $c_name,
            'comment_author_email' => $c_email,
            'comment_content'      => "[LIÊN HỆ/GÓP Ý - {$c_subj}]: " . $c_msg,
            'comment_type'         => 'feedback',
            'comment_approved'     => 1
        ));
        $msg_sent = true;
    }
}

get_header();
?>

<div class="container">
    <div class="story-detail-wrapper" style="max-width: 900px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Liên Hệ</span>
        </nav>

        <div style="text-align: center; margin-bottom: 28px;">
            <div class="brand-logo" style="width: 56px; height: 56px; font-size: 26px; margin: 0 auto 14px;">
                <i class="fa-solid fa-headset"></i>
            </div>
            <h1 style="font-size: 26px; font-weight: 800; color: var(--primary-green); margin-bottom: 8px;">
                Liên Hệ & Hỗ Trợ Độc Giả / Dịch Giả
            </h1>
            <p style="font-size: 14px; color: var(--text-muted);">
                Chúng tôi luôn sẵn sàng lắng nghe mọi ý kiến đóng góp, thắc mắc và yêu cầu hợp tác của bạn!
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <!-- Left: Thông tin liên hệ -->
            <?php 
            $c_email    = get_option('muop_contact_email', 'admin@muopdang.local');
            $c_phone    = get_option('muop_contact_phone', '');
            $c_facebook = get_option('muop_contact_facebook', '');
            $c_telegram = get_option('muop_contact_telegram', '');
            $c_address  = get_option('muop_contact_address', '');
            $c_notes    = get_option('muop_contact_notes', 'Trong vòng 24 giờ tất cả các ngày trong tuần.');
            ?>
            <div style="background: var(--pastel-green); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 16px;">
                    <i class="fa-solid fa-circle-info"></i> Thông Tin Ban Quản Trị
                </h3>

                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 14px;">
                    <?php if (!empty($c_email)) : ?>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <strong style="display: block;">Hòm thư hỗ trợ:</strong>
                            <a href="mailto:<?php echo esc_attr($c_email); ?>" style="color: var(--primary-green); font-weight: 600;"><?php echo esc_html($c_email); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($c_phone)) : ?>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <strong style="display: block;">Hotline / Zalo hỗ trợ:</strong>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $c_phone)); ?>" style="color: var(--primary-green); font-weight: 600;"><?php echo esc_html($c_phone); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($c_facebook)) : ?>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-brands fa-facebook"></i>
                        </div>
                        <div>
                            <strong style="display: block;">Fanpage Facebook:</strong>
                            <a href="<?php echo esc_url($c_facebook); ?>" target="_blank" rel="noopener noreferrer" style="color: var(--primary-green); font-weight: 600;">Ghé thăm Fanpage Mướp Đắng <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($c_telegram)) : ?>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-brands fa-telegram"></i>
                        </div>
                        <div>
                            <strong style="display: block;">Kênh Telegram:</strong>
                            <a href="<?php echo esc_url($c_telegram); ?>" target="_blank" rel="noopener noreferrer" style="color: var(--primary-green); font-weight: 600;">Nhắn tin qua Telegram <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($c_address)) : ?>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <strong style="display: block;">Trụ sở / Địa chỉ:</strong>
                            <span style="color: var(--text-color);"><?php echo esc_html($c_address); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--soft-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <strong style="display: block;">Thời gian phản hồi & Làm việc:</strong>
                            <span style="color: var(--text-muted);"><?php echo esc_html($c_notes); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form liên hệ -->
            <div style="background: var(--bg-card); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 16px;">
                    <i class="fa-solid fa-paper-plane"></i> Gửi Tin Nhắn Cho Mướp
                </h3>

                <?php if ($msg_sent) : ?>
                    <div class="alert-box alert-success" style="display: block;">
                        <i class="fa-solid fa-circle-check"></i> Cảm ơn bạn! Tin nhắn đã được gửi đến ban quản trị thành công.
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 13px;">Họ và Tên (*)</label>
                        <input type="text" name="name" class="form-input" placeholder="Tên của bạn" required />
                    </div>

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 13px;">Địa Chỉ Email (*)</label>
                        <input type="email" name="email" class="form-input" placeholder="email@example.com" required />
                    </div>

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-size: 13px;">Tiêu Đề</label>
                        <input type="text" name="subject" class="form-input" placeholder="Chủ đề góp ý / báo lỗi / hợp tác" />
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-size: 13px;">Nội Dung (*)</label>
                        <textarea name="message" class="form-input" rows="4" placeholder="Nội dung cần liên hệ..." required></textarea>
                    </div>

                    <button type="submit" name="contact_submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fa-solid fa-paper-plane"></i> Gửi Tin Nhắn
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
