<?php
/**
 * Template Name: Trang Chính Sách Bảo Mật
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="container">
    <div class="story-detail-wrapper" style="max-width: 900px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Chính Sách Bảo Mật</span>
        </nav>

        <div style="text-align: center; margin-bottom: 28px;">
            <div class="brand-logo" style="width: 56px; height: 56px; font-size: 26px; margin: 0 auto 14px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 style="font-size: 26px; font-weight: 800; color: var(--primary-green); margin-bottom: 8px;">
                Chính Sách Bảo Mật Thông Tin
            </h1>
            <p style="font-size: 14px; color: var(--text-muted);">
                Bảo vệ quyền riêng tư và dữ liệu cá nhân của người dùng tại Mướp Đắng
            </p>
        </div>

        <div class="story-summary-box" style="font-size: 15px; line-height: 1.8;">
            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                1. Thông Tin Chúng Tôi Thu Thập
            </h3>
            <p style="margin-bottom: 16px;">
                Khi bạn đăng ký tài khoản hoặc sử dụng website, chúng tôi có thể thu thập các thông tin sau:<br>
                - <strong>Thông tin tài khoản:</strong> Tên hiển thị, địa chỉ email, mật khẩu (được mã hóa bảo mật chuẩn WordPress MD5/Bcrypt).<br>
                - <strong>Dữ liệu hoạt động đọc:</strong> Lịch sử các chương đã đọc, danh sách truyện trong Tủ truyện, các bình luận bạn đã gửi.<br>
                - <strong>Dữ liệu kỹ thuật:</strong> Địa chỉ IP, loại trình duyệt nhằm mục đích bảo mật chống spam và thống kê mở khóa tiếp thị.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                2. Mục Đích Sử Dụng Dữ Liệu
            </h3>
            <p style="margin-bottom: 16px;">
                - Cung cấp trải nghiệm đọc truyện cá nhân hóa (tiếp tục đọc từ chương đã dừng lại, lưu truyện yêu thích).<br>
                - Hỗ trợ dịch giả tính toán lượt xem và quyết toán nhuận bút minh bạch.<br>
                - Duy trì an ninh, ngăn chặn các hành vi gian lận, phá hoại hệ thống hoặc spam bình luận.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                3. Cam Kết Bảo Mật & Chia Sẻ Thông Tin
            </h3>
            <p style="margin-bottom: 16px;">
                - Chúng tôi <strong>không bao giờ</strong> bán, trao đổi hoặc cho thuê thông tin cá nhân của bạn cho bất kỳ bên thứ ba nào vì mục đích thương mại.<br>
                - Toàn bộ dữ liệu mật khẩu được băm và bảo vệ an toàn trong cơ sở dữ liệu MySQL, không ai (kể cả ban quản trị) có thể xem được mật khẩu dạng văn bản gốc của bạn.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                4. Sử Dụng Cookie & Bộ Nhớ Cục Bộ (LocalStorage)
            </h3>
            <p>
                Website sử dụng Cookie và LocalStorage để lưu trữ tùy chọn giao diện Sáng / Tối, trạng thái đăng nhập và trạng thái mở khóa chương truyện để bạn có trải nghiệm đọc mượt mà nhất.
            </p>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="padding: 10px 24px;">
                <i class="fa-solid fa-house"></i> Về Trang Chủ
            </a>
        </div>
    </div>
</div>

<?php
get_footer();
