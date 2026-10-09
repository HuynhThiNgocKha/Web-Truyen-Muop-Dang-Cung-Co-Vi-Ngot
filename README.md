# 🥒 MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT — WEBSITE ĐỌC TRUYỆN ZHIHU

Website đọc truyện Zhihu, ngôn tình, hiện đại, cổ trang được xây dựng hoàn chỉnh trên nền tảng **WordPress mã nguồn mở (PHP 8.2 + MySQL)**. 
---

## 🚀 TRUY CẬP WEBSITE

- **Tên miền chính thức:** [https://muopdangcungcovingot.io.vn/](https://muopdangcungcovingot.io.vn/)
- **Đăng nhập:** [https://muopdangcungcovingot.io.vn/dang-nhap/](https://muopdangcungcovingot.io.vn/dang-nhap/)
- **Đăng ký:** [https://muopdangcungcovingot.io.vn/dang-ky/](https://muopdangcungcovingot.io.vn/dang-ky/)
- **Chạy môi trường cục bộ (Local):** [http://localhost:8080/](http://localhost:8080/) (Chạy file `run.bat` trong thư mục gốc)

---

## 👥 TÀI KHOẢN MẪU & PHÂN QUYỀN HỆ THỐNG

| Vai trò | Tên đăng nhập | Mật khẩu | Chức năng nổi bật |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin` | `AdminPassword@2026` | Toàn quyền quản trị, kiểm duyệt truyện, gắn link Shopee/TikTok, xem click, quản lý người dùng, xem lương dịch giả, đổi chế độ xem. |
| **Dịch giả 1 (Translator)** | `dichgia_muop` | `dichgia123` | Đăng truyện, thêm chương, quản lý truyện đã đăng, xem view ngày/tuần/tháng/năm, xem nhuận bút 8đ/view, trả lời bình luận. |
| **Dịch giả 2 (Translator)** | `dichgia_bocute` | `dichgia123` | Đăng truyện, thêm chương, tính lương, quản lý tác phẩm. |
| **Độc giả 1 (Reader)** | `docgia_linh` | `docgia123` | Đọc truyện, lưu tủ truyện, lịch sử đọc, bình luận, bật/tắt giao diện Sáng/Tối. |
| **Độc giả 2 (Reader)** | `docgia_minh` | `docgia123` | Đọc truyện, tủ truyện cá nhân. |

---

## 🌿 CÁC TÍNH NĂNG ĐÃ TRIỂN KHAI THEO YÊU CẦU

### 1. Giao Diện & Trải Nghiệm (Front-End)
- **Header 1 (Cố định):**
  - Logo thương hiệu hình lá mầm xanh mát mắt
  - Tên website: **Mướp Đắng Cũng Có Vị Ngọt**
  - Khung tìm kiếm truyện, tác giả, dịch giả tức thời
  - Icon chuyển đổi chế độ **Sáng / Tối (Dark / Light Mode)** lưu tùy chọn người dùng
  - Khung Đăng nhập / Đăng ký hoặc Dropdown hồ sơ cá nhân khi đã đăng nhập
  - Thanh chọn **Chế độ xem cho Admin** (Xem với tư cách: Admin, Dịch giả, Độc giả).
- **Header 2 (Cố định):**
  - Button **Trang chủ**
  - Dropdown **Thể loại** (Ngôn tình, Ngọt, Ngược, Đam mỹ, Cổ trang, Hiện đại, Tương lai, Xuyên không, Xuyên sách, Zhihu, Trọng sinh, Hài hước...)
  - Button **Truyện mới**
  - Button **Truyện hot**
  - Button **Truyện full**
  - Button **Đăng Truyện** (tự động hiển thị khi đăng nhập bằng tài khoản Dịch giả hoặc Admin)
  - Button **Giới thiệu**
- **Trang chủ (Center):**
  - Banner chào mừng phong cách xanh bơ
  - **Mục Truyện đề cử:** Hiển thị 12 truyện được ban biên tập đề cử
  - **Mục Truyện mới cập nhật:** Hiển thị 12 truyện mới nhất
  - **Mục Truyện hot:** Hiển thị 12 truyện có lượt xem cao nhất
  - **Mục Truyện Full:** Hiển thị 12 truyện đã hoàn thành
- **Footer (Cố định):**
  - Logo, tên web, slogan: *"Mướp Đắng Cũng Có Vị Ngọt – nơi tâm hồn bạn được thả lỏng cùng với những câu truyện mà Mướp mang đến cho bạn"*
  - Bản quyền: `Copyright © 2026 Mướp Đắng Cũng Có Vị Ngọt. All right reserved.`

### 2. Trang Chi Tiết Truyện & Trang Đọc Truyện
- **Trang mô tả truyện:**
  - Đường dẫn Breadcrumbs tương tác
  - Bố cục: Bên trái ảnh bìa truyện, bên phải thông tin: Tên truyện, Tác giả, Team dịch, Thể loại, Trạng thái (Đang ra / Hoàn thành), Số chương, Số lượt xem, Cập nhật gần nhất
  - Các nút: Đọc từ đầu, Đọc mới nhất, Lưu vào tủ truyện, Thêm chương mới
  - Đoạn giới thiệu / văn án truyện
  - Danh sách chương với thời gian cập nhật
  - Khung bình luận và danh sách nhận xét của độc giả
- **Trang đọc chương truyện:**
  - Breadcrumbs dẫn về Truyện và Trang chủ
  - Thanh điều hướng: Chương trước, Chọn nhanh chương trong dropdown, Chương sau
  - Công cụ chỉnh cỡ chữ đọc truyện (A- / A+)
  - **Bảo vệ nội dung (Chống copy):** Vô hiệu hóa chuột phải, chặn sao chép (copy/cut), chặn bôi đen văn bản, chặn các phím tắt `Ctrl+C`, `Ctrl+U`, `Ctrl+S`, `F12`.
  - **Cơ chế Popup Shopee Gatekeeper:**
    - Xuất hiện bắt đầu từ **Chương 2** và định kỳ **cứ cách 3 chương hiện 1 lần** (Chương 2, 5, 8, 11...).
    - Thông báo: *"1. Để giúp Mướp có thể duy trì web lâu dài mọi người click vào link này nha. 2. Link Shopee."*
    - Nội dung chương bị làm mờ, độc giả **bắt buộc phải bấm vào link Shopee** mới mở khóa đọc tiếp.
    - Hệ thống tự động ghi nhận lượt click vào cơ sở dữ liệu (`wp_affiliate_clicks`).
  - **Cơ chế liên kết ẩn:** Kích hoạt mở liên kết affiliate Shopee/TikTok khi độc giả tương tác lần đầu trên trang hoặc khi ấn nút chuyển sang chương kế tiếp.

### 3. Kênh Dịch Giả (Translator Portal)
- Hiển thị hồ sơ: Tên, avatar, email, ngày tham gia
- Thống kê lượt xem truyện: Hôm nay, trong tuần, trong tháng, trong năm
- **Hệ thống tính tiền nhuận bút:** **8 VNĐ cho mỗi 1 lượt xem (view)**
  - Tự động tính số tiền nhận trong tháng hiện tại
  - Xem lại số tiền và lượt xem của tháng trước
  - Tự động làm mới chu kỳ mỗi tháng
- Quản lý danh sách tác phẩm: Trạng thái (Đã duyệt / Chờ duyệt), thêm chương, chỉnh sửa
- Quản lý và phản hồi bình luận của bạn đọc trên truyện của mình.

### 4. Bảng Quản Trị Hệ Thống (Admin Portal)
- 6 thẻ chỉ số KPI tổng quan: Lượt xem tháng, Tổng bộ truyện, Tổng độc giả, Tổng dịch giả, Click Shopee, Click TikTok
- **Quản lý & Kiểm duyệt truyện:** Duyệt bài đăng mới của dịch giả, từ chối, xóa, gán nhãn Đề cử (Ngày / Tuần / Tháng)
- **Quản lý Link Shopee & TikTok:**
  - Tùy chỉnh link Shopee và link TikTok 
  - Thống kê chi tiết số lượt click thực tế và xem nhật ký IP, chương mở khóa
- **Quản lý Người Dùng & Phân Quyền:** Xem danh sách, thêm tài khoản mới, phân quyền (Độc giả, Dịch giả, Quản trị viên), Khóa / Mở khóa tài khoản
- **Quản lý Lương Dịch Giả:** Bảng kê chi tiết từng dịch giả, số view đạt được và tổng số tiền nhuận bút được nhận (nhân với 8đ/view)
- **Quản lý Thể Loại & Bình Luận:** CRUD thể loại, kiểm duyệt bình luận
- **Chuyển chế độ xem giao diện:** Xem tức thời với tư cách Độc Giả, Dịch Giả, Quản Trị Viên.
