<?php
/**
 * Template Name: Trang Điều Khoản Sử Dụng
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="container">
    <div class="story-detail-wrapper" style="max-width: 900px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Điều Khoản Sử Dụng</span>
        </nav>

        <div style="text-align: center; margin-bottom: 28px;">
            <div class="brand-logo" style="width: 56px; height: 56px; font-size: 26px; margin: 0 auto 14px;">
                <i class="fa-solid fa-file-contract"></i>
            </div>
            <h1 style="font-size: 26px; font-weight: 800; color: var(--primary-green); margin-bottom: 8px;">
                Điều Khoản Sử Dụng Dịch Vụ
            </h1>
            <p style="font-size: 14px; color: var(--text-muted);">
                Cập nhật lần cuối: Năm 2026 • Website Mướp Đắng Cũng Có Vị Ngọt
            </p>
        </div>

        <div class="story-summary-box" style="font-size: 15px; line-height: 1.8;">
            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                1. Chấp Thuận Điều Khoản
            </h3>
            <p style="margin-bottom: 16px;">
                Bằng việc truy cập, đọc truyện hoặc đăng ký tài khoản tại <strong>Mướp Đắng Cũng Có Vị Ngọt</strong>, bạn đồng ý tuân thủ toàn bộ các quy định và điều khoản được nêu dưới đây. Nếu không đồng ý với bất kỳ phần nào, vui lòng ngưng sử dụng dịch vụ của website.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                2. Quyền Sở Hữu Trí Tuệ & Bản Quyền Nội Dung
            </h3>
            <p style="margin-bottom: 16px;">
                - Mọi bản dịch, tổng hợp và biên tập truyện trên hệ thống thuộc quyền sở hữu của các dịch giả và ban quản trị <em>Mướp Đắng</em>.<br>
                - Nghiêm cấm mọi hành vi sao chép tự động (cào dữ liệu), đăng tải lại nội dung truyện lên các trang web, diễn đàn khác mà không có sự đồng ý bằng văn bản của dịch giả hoặc ban quản trị.<br>
                - Website áp dụng cơ chế kỹ thuật chống sao chép để bảo vệ công sức lao động trí tuệ của đội ngũ dịch thuật.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                3. Trách Nhiệm Của Độc Giả & Dịch Giả
            </h3>
            <p style="margin-bottom: 16px;">
                - <strong>Độc giả:</strong> Tôn trọng cộng đồng, không bình luận khiếm nhã, thô tục, xúc phạm tác giả, dịch giả hay người đọc khác; không phát tán link độc hại hoặc spam quảng cáo.<br>
                - <strong>Dịch giả:</strong> Cam kết chất lượng bản dịch văn minh, không đăng tải nội dung vi phạm thuần phong mỹ tục, pháp luật hiện hành; tuân thủ chính sách kiểm duyệt bài đăng trước khi phát hành công khai.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                4. Cơ Chế Tiếp Thị Liên Kết (Affiliate) & Mở Khóa Nội Dung
            </h3>
            <p style="margin-bottom: 16px;">
                - Nhằm duy trì chi phí máy chủ và vận hành miễn phí cho bạn đọc, website áp dụng cơ chế hiển thị liên kết Shopee/TikTok để độc giả bấm ủng hộ khi đọc truyện (định kỳ mỗi 3 chương).<br>
                - Việc độc giả nhấn vào link tiếp thị không làm phát sinh bất kỳ khoản phí ngoài ý muốn nào cho người đọc.
            </p>

            <h3 style="color: var(--primary-green); font-size: 17px; margin-bottom: 10px;">
                5. Xử Lý Vi Phạm & Sửa Đổi Điều Khoản
            </h3>
            <p>
                Ban quản trị có toàn quyền khóa tài khoản, xóa bình luận hoặc hạn chế quyền truy cập đối với các trường hợp vi phạm mà không cần báo trước. Chúng tôi có quyền cập nhật điều khoản này bất cứ lúc nào để phù hợp với sự phát triển của hệ thống.
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
