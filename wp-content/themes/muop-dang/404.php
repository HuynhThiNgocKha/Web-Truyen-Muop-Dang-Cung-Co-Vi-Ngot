<?php
/**
 * The template for displaying 404 pages (not found)
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="container" style="padding: 80px 16px; text-align: center; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 550px; background: var(--bg-card, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 16px; padding: 48px 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <div style="font-size: 72px; color: var(--primary-green, #406b47); margin-bottom: 16px; font-weight: 800; line-height: 1;">
            404
        </div>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-main, #2d3748); margin-bottom: 12px;">
            Không Tìm Thấy Trang Hoặc Truyện Này!
        </h1>
        <p style="color: var(--text-muted, #718096); font-size: 15px; line-height: 1.6; margin-bottom: 28px;">
            Đường dẫn bạn vừa truy cập có thể đã được thay đổi, chưa được cập nhật liên kết tĩnh (Permalinks), hoặc nội dung đang được cập nhật lại.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 8px; text-decoration: none;">
                <i class="fa-solid fa-house"></i> Về Trang Chủ
            </a>
            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 8px; text-decoration: none;">
                <i class="fa-solid fa-list-ul"></i> Khám Phá Thể Loại
            </a>
        </div>
    </div>
</div>

<?php
get_footer();
