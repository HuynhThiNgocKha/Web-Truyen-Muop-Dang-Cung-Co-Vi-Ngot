# 📋 TẬP HỢP TẤT CẢ CÁC ĐƯỜNG DẪN (URL SITEMAP & ROUTES)
### Website: MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT
* **Tên miền chính thức (Production):** `https://muopdangcungcovingot.io.vn`
* **Môi trường chạy cục bộ (Localhost):** `http://localhost:8080`
* **Môi trường di động mạng nội bộ (LAN):** `http://192.168.1.182:8080`

---

## 📌 MỤC LỤC
1. [Trang Công Khai & Độc Giả (Public Pages)](#1-trang-công-khai--độc-giả-public-pages)
2. [Đường Dẫn Thể Loại Truyện (Genres / Taxonomy)](#2-đường-dẫn-thể-loại-truyện-genres--taxonomy)
3. [Phân Hệ Tài Khoản Người Dùng (Auth & Profile)](#3-phân-hệ-tài-khoản-người-dùng-auth--profile)
4. [Phân Hệ Kênh Dịch Giả (Creator / Translator Portal)](#4-phân-hệ-kênh-dịch-giả-creator--translator-portal)
5. [Phân Hệ Quản Trị Hệ Thống (Admin Portal & Core)](#5-phân-hệ-quản-trị-hệ-thống-admin-portal--core)
6. [Danh Sách Các Bộ Truyện Mẫu (Stories & Chapters)](#6-danh-sách-các-bộ-truyện-mẫu-stories--chapters)
7. [Hệ Thống API & AJAX Endpoints (Developer / Backend)](#7-hệ-thống-api--ajax-endpoints-developer--backend)
8. [Tiện Ích Kết Nối Di Động & QR](#8-tiện-ích-kết-nối-di-động--qr)

---

## 1. Trang Công Khai & Độc Giả (Public Pages)

| Tên Trang | Đường dẫn tương đối | Đường dẫn chính thức (Live URL) | Đường dẫn Localhost | Ghi chú |
| :--- | :--- | :--- | :--- | :--- |
| **Trang chủ** | `/` | [https://muopdangcungcovingot.io.vn/](https://muopdangcungcovingot.io.vn/) | [http://localhost:8080/](http://localhost:8080/) | Banner, Truyện đề cử, Truyện mới, Truyện hot |
| **Truyện Mới Cập Nhật** | `/truyen-moi/` | [https://muopdangcungcovingot.io.vn/truyen-moi/](https://muopdangcungcovingot.io.vn/truyen-moi/) | [http://localhost:8080/truyen-moi/](http://localhost:8080/truyen-moi/) | Danh sách truyện mới nhất |
| **Truyện Hot / Xem Nhiều** | `/truyen-hot/` | [https://muopdangcungcovingot.io.vn/truyen-hot/](https://muopdangcungcovingot.io.vn/truyen-hot/) | [http://localhost:8080/truyen-hot/](http://localhost:8080/truyen-hot/) | Bảng xếp hạng lượt đọc cao nhất |
| **Truyện Full** | `/truyen-full/` | [https://muopdangcungcovingot.io.vn/truyen-full/](https://muopdangcungcovingot.io.vn/truyen-full/) | [http://localhost:8080/truyen-full/](http://localhost:8080/truyen-full/) | Các bộ truyện đã hoàn thành |
| **Tất Cả Thể Loại** | `/the-loai/` | [https://muopdangcungcovingot.io.vn/the-loai/](https://muopdangcungcovingot.io.vn/the-loai/) | [http://localhost:8080/the-loai/](http://localhost:8080/the-loai/) | Lưới chọn thể loại trực quan |
| **Giới Thiệu Website** | `/gioi-thieu/` | [https://muopdangcungcovingot.io.vn/gioi-thieu/](https://muopdangcungcovingot.io.vn/gioi-thieu/) | [http://localhost:8080/gioi-thieu/](http://localhost:8080/gioi-thieu/) | Về Mướp Đắng Cũng Có Vị Ngọt |
| **Điều Khoản Sử Dụng** | `/dieu-khoan/` | [https://muopdangcungcovingot.io.vn/dieu-khoan/](https://muopdangcungcovingot.io.vn/dieu-khoan/) | [http://localhost:8080/dieu-khoan/](http://localhost:8080/dieu-khoan/) | Quy định & quyền sở hữu trí tuệ |
| **Chính Sách Bảo Mật** | `/chinh-sach-bao-mat/` | [https://muopdangcungcovingot.io.vn/chinh-sach-bao-mat/](https://muopdangcungcovingot.io.vn/chinh-sach-bao-mat/) | [http://localhost:8080/chinh-sach-bao-mat/](http://localhost:8080/chinh-sach-bao-mat/) | Bảo mật thông tin người dùng |
| **Liên Hệ** | `/lien-he/` | [https://muopdangcungcovingot.io.vn/lien-he/](https://muopdangcungcovingot.io.vn/lien-he/) | [http://localhost:8080/lien-he/](http://localhost:8080/lien-he/) | Thông tin hỗ trợ & kênh liên hệ |

---

## 2. Đường Dẫn Thể Loại Truyện (Genres / Taxonomy)

| Tên Thể Loại | Đường dẫn tương đối | Đường dẫn chính thức (Live URL) | Đường dẫn Localhost |
| :--- | :--- | :--- | :--- |
| **Ngôn tình** | `/the-loai/ngon-tinh/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/ngon-tinh/) | [Xem Thể Loại](http://localhost:8080/the-loai/ngon-tinh/) |
| **Ngọt** | `/the-loai/ngot/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/ngot/) | [Xem Thể Loại](http://localhost:8080/the-loai/ngot/) |
| **Ngược** | `/the-loai/nguoc/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/nguoc/) | [Xem Thể Loại](http://localhost:8080/the-loai/nguoc/) |
| **Đam mỹ** | `/the-loai/dam-my/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/dam-my/) | [Xem Thể Loại](http://localhost:8080/the-loai/dam-my/) |
| **Cổ trang** | `/the-loai/co-trang/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/co-trang/) | [Xem Thể Loại](http://localhost:8080/the-loai/co-trang/) |
| **Hiện đại** | `/the-loai/hien-dai/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/hien-dai/) | [Xem Thể Loại](http://localhost:8080/the-loai/hien-dai/) |
| **Tương lai** | `/the-loai/tuong-lai/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/tuong-lai/) | [Xem Thể Loại](http://localhost:8080/the-loai/tuong-lai/) |
| **Xuyên không** | `/the-loai/xuyen-khong/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/xuyen-khong/) | [Xem Thể Loại](http://localhost:8080/the-loai/xuyen-khong/) |
| **Xuyên sách** | `/the-loai/xuyen-sach/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/xuyen-sach/) | [Xem Thể Loại](http://localhost:8080/the-loai/xuyen-sach/) |
| **Zhihu** | `/the-loai/zhihu/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/zhihu/) | [Xem Thể Loại](http://localhost:8080/the-loai/zhihu/) |
| **Trọng sinh** | `/the-loai/trong-sinh/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/trong-sinh/) | [Xem Thể Loại](http://localhost:8080/the-loai/trong-sinh/) |
| **Hài hước** | `/the-loai/hai-huoc/` | [Xem Thể Loại](https://muopdangcungcovingot.io.vn/the-loai/hai-huoc/) | [Xem Thể Loại](http://localhost:8080/the-loai/hai-huoc/) |

---

## 3. Phân Hệ Tài Khoản Người Dùng (Auth & Profile)

| Chức năng | Đường dẫn tương đối | Đường dẫn chính thức (Live URL) | Đường dẫn Localhost | Ghi chú |
| :--- | :--- | :--- | :--- | :--- |
| **Đăng nhập** | `/dang-nhap/` | [https://muopdangcungcovingot.io.vn/dang-nhap/](https://muopdangcungcovingot.io.vn/dang-nhap/) | [http://localhost:8080/dang-nhap/](http://localhost:8080/dang-nhap/) | Form đăng nhập AJAX có nút xem mật khẩu |
| **Đăng ký tài khoản** | `/dang-ky/` | [https://muopdangcungcovingot.io.vn/dang-ky/](https://muopdangcungcovingot.io.vn/dang-ky/) | [http://localhost:8080/dang-ky/](http://localhost:8080/dang-ky/) | Đăng ký độc giả mới |
| **Thông tin tài khoản (Hồ sơ)** | `/ho-so/` | [https://muopdangcungcovingot.io.vn/ho-so/](https://muopdangcungcovingot.io.vn/ho-so/) | [http://localhost:8080/ho-so/](http://localhost:8080/ho-so/) | Tab 1: Cập nhật tên, ảnh đại diện, bio, email |
| **Đổi mật khẩu** | `/ho-so/#tabPassword` | [https://muopdangcungcovingot.io.vn/ho-so/#tabPassword](https://muopdangcungcovingot.io.vn/ho-so/#tabPassword) | [http://localhost:8080/ho-so/#tabPassword](http://localhost:8080/ho-so/#tabPassword) | Tab 2: Đổi mật khẩu có nút bật mắt xem |
| **Tủ truyện đã lưu** | `/ho-so/#tabBookmarks` | [https://muopdangcungcovingot.io.vn/ho-so/#tabBookmarks](https://muopdangcungcovingot.io.vn/ho-so/#tabBookmarks) | [http://localhost:8080/ho-so/#tabBookmarks](http://localhost:8080/ho-so/#tabBookmarks) | Tab 3: Danh sách truyện bạn đã bookmark |
| **Lịch sử đọc** | `/ho-so/#tabHistory` | [https://muopdangcungcovingot.io.vn/ho-so/#tabHistory](https://muopdangcungcovingot.io.vn/ho-so/#tabHistory) | [http://localhost:8080/ho-so/#tabHistory](http://localhost:8080/ho-so/#tabHistory) | Tab 4: Các chương đọc gần đây |
| **Đăng xuất (Logout)** | `/core/wp-login.php?action=logout` | [Đăng Xuất](https://muopdangcungcovingot.io.vn/core/wp-login.php?action=logout) | [Đăng Xuất](http://localhost:8080/core/wp-login.php?action=logout) | Thoát phiên làm việc |

---

## 4. Phân Hệ Kênh Dịch Giả (Creator / Translator Portal)

| Chức năng | Đường dẫn tương đối | Đường dẫn chính thức (Live URL) | Đường dẫn Localhost | Quyền truy cập |
| :--- | :--- | :--- | :--- | :--- |
| **Kênh Dịch Giả** | `/thong-tin-dich-gia/` | [https://muopdangcungcovingot.io.vn/thong-tin-dich-gia/](https://muopdangcungcovingot.io.vn/thong-tin-dich-gia/) | [http://localhost:8080/thong-tin-dich-gia/](http://localhost:8080/thong-tin-dich-gia/) | Dịch Giả / Admin |
| **Đăng Truyện Mới** | `/dang-truyen/` | [https://muopdangcungcovingot.io.vn/dang-truyen/](https://muopdangcungcovingot.io.vn/dang-truyen/) | [http://localhost:8080/dang-truyen/](http://localhost:8080/dang-truyen/) | Dịch Giả / Admin |
| **Thêm Chương Mới** | `/them-chuong/` | [https://muopdangcungcovingot.io.vn/them-chuong/](https://muopdangcungcovingot.io.vn/them-chuong/) | [http://localhost:8080/them-chuong/](http://localhost:8080/them-chuong/) | Dịch Giả / Admin (Kèm `?story_id=...`) |

---

## 5. Phân Hệ Quản Trị Hệ Thống (Admin Portal & Core)

| Chức năng | Đường dẫn tương đối | Đường dẫn chính thức (Live URL) | Đường dẫn Localhost | Quyền truy cập |
| :--- | :--- | :--- | :--- | :--- |
| **Quản Trị Hệ Thống (Frontend)** | `/quan-ly-admin/` | [https://muopdangcungcovingot.io.vn/quan-ly-admin/](https://muopdangcungcovingot.io.vn/quan-ly-admin/) | [http://localhost:8080/quan-ly-admin/](http://localhost:8080/quan-ly-admin/) | Quản Trị Viên (Admin) |
| **WordPress Core Admin** | `/core/wp-admin/` | [https://muopdangcungcovingot.io.vn/core/wp-admin/](https://muopdangcungcovingot.io.vn/core/wp-admin/) | [http://localhost:8080/core/wp-admin/](http://localhost:8080/core/wp-admin/) | Quản Trị Viên (Admin) |
| **WordPress Core Login** | `/core/wp-login.php` | [https://muopdangcungcovingot.io.vn/core/wp-login.php](https://muopdangcungcovingot.io.vn/core/wp-login.php) | [http://localhost:8080/core/wp-login.php](http://localhost:8080/core/wp-login.php) | Tất cả tài khoản |

---

## 6. Danh Sách Các Bộ Truyện Mẫu (Stories & Chapters)

| Tên Bộ Truyện | Đường dẫn trang chi tiết truyện | Chương 1 mẫu |
| :--- | :--- | :--- |
| **Mướp Đắng Cũng Có Vị Ngọt: Sau Ly Hôn Tôi Trở Thành Ánh Trăng Sáng Của Tổng Tài** | `/truyen/muop-dang-cung-co-vi-ngot-sau-ly-hon-toi-tro-thanh-anh-trang-sang-cua-tong-tai/` | `/chuong/chuong-1-mo-dau/` |
| **Trọng Sinh Về Năm 18 Tuổi: Tôi Không Làm Kẻ Ngốc Nữa** | `/truyen/trong-sinh-ve-nam-18-tuoi-toi-khong-lam-ke-ngoc-nua/` | `/chuong/chuong-1-tro-ve-ngay-thi-tot-nghiep/` |
| **Cưới Trước Yêu Sau: Giáo Sư Nghiêm Túc Ban Ngày Ban Đêm Lại Rất Quấn Người** | `/truyen/cuoi-truoc-yeu-sau-giao-su-nghiem-tuc-ban-ngay-ban-dem-lai-rat-quan-nguoi/` | `/chuong/chuong-1-cuoc-hon-nhan-bat-dac-di/` |
| **Xuyên Sách Thành Nữ Phụ Pháo Hôi: Tôi Quyết Định Nằm Yên Nuôi Cá** | `/truyen/xuyen-sach-thanh-nu-phu-phao-hoi-toi-quyet-dinh-nam-yen-nuoi-ca/` | `/chuong/chuong-1-thuc-tinh-trong-nha-kho/` |
| **Sau Khi Tôi Chết, Toàn Thể Nhà Họ Thẩm Hối Hận Không Kịp** | `/truyen/sau-khi-toi-chet-toan-the-nha-ho-tham-hoi-han-khong-kip/` | `/chuong/chuong-1-tang-le-duoi-con-mua-tam-ta/` |
| **Vị Ngọt Sau Mưa: Đại Ca Giang Hồ Và Cô Giáo Dạy Vẽ** | `/truyen/vi-ngot-sau-mua-dai-ca-giang-ho-va-co-giao-day-ve/` | `/chuong/chuong-1-con-mua-rao-bat-chot/` |
| **Thừa Tướng Đại Nhân, Phu Nhân Lại Bỏ Trốn Rồi!** | `/truyen/thua-tuong-dai-nhan-phu-nhan-lai-bo-tron-roi/` | `/chuong/chuong-1-lat-noc-nha-tuong-phu/` |
| **Ảnh Đế Nhà Bên Thích Giả Nghèo** | `/truyen/anh-de-nha-ben-thich-gia-ngheo/` | `/chuong/chuong-1-hang-xom-ky-la/` |
| **Xuyên Thành Mẹ Kế Của Nhân Vật Phản Diện Nhỏ** | `/truyen/xuyen-thanh-me-ke-cua-nhan-vat-phan-dien-nho/` | `/chuong/chuong-1-hai-dua-tre-lam-lem/` |
| **Đoạn Tuyệt: Tôi Không Còn Là Nữ Phụ Trong Câu Chuyện Của Anh** | `/truyen/doan-tuyet-toi-khong-con-la-nu-phu-trong-cau-chuyen-cua-anh/` | `/chuong/chuong-1-tin-nhan-chia-tay/` |
| **Ngọt Ngào Trong Tầm Mắt: Đội Trưởng Đội Cứu Hỏa Rất Chiều Vợ** | `/truyen/ngot-ngao-trong-tam-mat-doi-truong-doi-cuu-hoa-rat-chieu-vo/` | `/chuong/chuong-1-cuoc-phong-van-giua-khoi-lua/` |
| **Thiếu Tướng Quân Hôm Nay Cũng Muốn Từ Hôn** | `/truyen/thieu-tuong-quan-hom-nay-cung-muon-tu-hon/` | `/chuong/chuong-1-hen-gap-o-truong-ban/` |
| **Vị Thần Gió** | `/truyen/vi-than-gio/` | `/chuong/chuong-1-mo-dau/` |

---

## 7. Hệ Thống API & AJAX Endpoints (Developer / Backend)

### URL Gốc: `/core/wp-admin/admin-ajax.php` (Phương thức: `POST`)

| Tên Action (`action`) | Chức năng chi tiết | Quyền hạn |
| :--- | :--- | :--- |
| `muop_login` | Đăng nhập tài khoản | Khách |
| `muop_register` | Đăng ký thành viên độc giả | Khách |
| `muop_toggle_bookmark` | Đánh dấu / Hủy lưu truyện vào tủ truyện cá nhân | Đã đăng nhập |
| `muop_save_history` | Tự động ghi nhận tiến độ và vị trí chương vừa đọc | Mọi người dùng |
| `muop_post_comment` | Gửi bình luận và phản hồi vào chương truyện | Đã đăng nhập |
| `muop_submit_story` | Dịch giả đăng tải tác phẩm mới kèm ảnh bìa | Dịch Giả / Admin |
| `muop_submit_chapter` | Dịch giả thêm chương mới vào truyện | Dịch Giả / Admin |
| `muop_update_profile` | Cập nhật thông tin độc giả (Tên hiển thị, avatar file/url, bio, email) | Đã đăng nhập |
| `muop_change_password` | Đổi mật khẩu tài khoản bảo mật | Đã đăng nhập |
| `muop_admin_action` | Thao tác Admin: duyệt truyện, từ chối, gán nhãn đề cử, cập nhật affiliate | Quản Trị Viên |
| `muop_delete_story` | Xóa vĩnh viễn bộ truyện và toàn bộ chương của truyện | Tác giả / Admin |
| `muop_switch_preview_mode` | Đổi chế độ xem trước cho Admin (Admin / Dịch giả / Độc giả) | Quản Trị Viên |
| `muop_track_affiliate_click` | Ghi nhận lượt nhấp liên kết tiếp thị Shopee & TikTok ngầm | Tự động |

### WordPress REST API:
* **Gốc REST API:** `https://muopdangcungcovingot.io.vn/wp-json/`
* **Custom Route API:** `https://muopdangcungcovingot.io.vn/wp-json/muop/v1/`

---

## 8. Tiện Ích Kết Nối Di Động & QR

| Tiện ích | Đường dẫn tương đối | Mô tả |
| :--- | :--- | :--- |
| **Trang Quét Mã QR Di Động** | `/tools/mobile.html` hoặc `/mobile.html` | Hiển thị mã QR động theo IP Wi-Fi để điện thoại quét và truy cập tức thì |
| **Sitemap Trực Quan (HTML)** | `/tools/sitemap.html` | Bảng điều hướng trực quan có thể bấm trực tiếp để duyệt nhanh mọi trang |
