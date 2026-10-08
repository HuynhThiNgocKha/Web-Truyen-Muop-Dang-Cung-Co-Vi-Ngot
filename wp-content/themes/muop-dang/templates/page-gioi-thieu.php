<?php
/**
 * Template Name: Trang Giới Thiệu
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="container">
    <div class="story-detail-wrapper" style="max-width: 900px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Giới Thiệu</span>
        </nav>

        <div style="text-align: center; margin-bottom: 32px;">
            <div class="brand-logo" style="width: 64px; height: 64px; font-size: 32px; margin: 0 auto 16px;">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--primary-green); margin-bottom: 8px;">
                Mướp Đắng Cũng Có Vị Ngọt
            </h1>
            <p style="font-size: 16px; color: var(--text-muted); font-style: italic;">
                "Nơi tâm hồn bạn được thả lỏng cùng với những câu chuyện mà Mướp mang đến cho bạn."
            </p>
        </div>

        <div class="story-summary-box" style="font-size: 15px; line-height: 1.8;">
            <h3 style="color: var(--primary-green); font-size: 18px; margin-bottom: 10px;">
                🌱 Về Chúng Tôi
            </h3>
            <p style="margin-bottom: 16px;">
                Chào mừng bạn đến với <strong>Mướp Đắng Cũng Có Vị Ngọt</strong> – nền tảng đọc truyện Zhihu, ngôn tình, hiện đại, cổ trang chọn lọc với giao diện tươi mát tông màu xanh mướp, xanh bơ thư thái. Chúng tôi tin rằng mỗi câu chuyện, dù có những nốt trầm hay đắng cay như mướp đắng, rồi cũng sẽ kết thúc bằng dư vị ngọt ngào và bình yên.
            </p>

            <h3 style="color: var(--primary-green); font-size: 18px; margin-bottom: 10px;">
                👥 Dành Cho Độc Giả
            </h3>
            <p style="margin-bottom: 16px;">
                - Trải nghiệm đọc truyện mượt mà với chế độ Sáng / Tối dịu mắt.<br>
                - Tự động lưu <strong>Tủ truyện</strong> và <strong>Lịch sử đọc</strong> để bạn không bao giờ bỏ lỡ chương mới.<br>
                - Bình luận, trao đổi và chia sẻ cảm xúc cùng cộng đồng mọt truyện.
            </p>

            <h3 style="color: var(--primary-green); font-size: 18px; margin-bottom: 10px;">
                ✍️ Chính Sách Hợp Tác Dịch Giả
            </h3>
            <p style="margin-bottom: 16px;">
                - <strong>Nhuận bút hấp dẫn:</strong> <strong>8 VNĐ</strong> cho mỗi lượt đọc (view) thực tế trên toàn hệ thống.<br>
                - <strong>Thống kê minh bạch:</strong> Bảng điều khiển riêng theo dõi số lượt xem theo ngày, tuần, tháng, năm và quyết toán nhuận bút định kỳ hàng tháng.<br>
                - Quyền đăng truyện, cập nhật chương và giao lưu trực tiếp cùng bạn đọc.
            </p>

            <h3 style="color: var(--primary-green); font-size: 18px; margin-bottom: 10px;">
                🛒 Tiếp Thị Liên Kết & Ủng Hộ Web
            </h3>
            <p>
                Để duy trì chi phí máy chủ và hệ thống máy chủ vận hành lâu dài, website tích hợp các liên kết mua sắm Shopee và TikTok Shop. Mỗi lượt click ủng hộ của độc giả là nguồn động viên to lớn để Mướp tiếp tục mang đến những câu chuyện hay nhất!
            </p>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="padding: 10px 24px;">
                <i class="fa-solid fa-book-open"></i> Bắt Đầu Đọc Truyện Ngay
            </a>
        </div>
    </div>
</div>

<?php
get_footer();
