# 🖥️ TechStore – Web Bán Thiết Bị Công Nghệ

Ứng dụng web bán lẻ Smartphone và Laptop xây dựng bằng **PHP thuần** (không framework), áp dụng mô hình **MVC tùy chỉnh**, với giao diện Bootstrap 5 hiện đại.

---

## 📋 Yêu Cầu Hệ Thống

| Thành phần | Phiên bản tối thiểu |
|---|---|
| PHP | 7.4+ (khuyến nghị 8.x) |
| MySQL / MariaDB | 5.7+ / 10.3+ |
| Apache | 2.4+ (có mod_rewrite) |
| XAMPP | 8.x (khuyến nghị) |

---

## 🚀 Hướng Dẫn Cài Đặt

### Bước 1: Cài đặt XAMPP

1. Tải XAMPP tại [https://www.apachefriends.org](https://www.apachefriends.org)
2. Cài đặt và khởi động **Apache** và **MySQL** trong XAMPP Control Panel

### Bước 2: Clone / Copy dự án

Đặt toàn bộ thư mục dự án vào thư mục gốc của XAMPP:

```
C:\xampp\htdocs\btl\
```

Cấu trúc thư mục sau khi đặt đúng:

```
C:\xampp\htdocs\btl\
├── config/
│   └── database.php        # Cấu hình kết nối CSDL
├── controllers/            # Các Controller (MVC)
├── models/                 # Các Model (MVC)
├── views/                  # Các View (MVC)
│   ├── layouts/            # Layout chung (main, admin)
│   ├── public/             # View cho người dùng
│   └── admin/              # View cho quản trị viên
├── public/
│   ├── css/                # File CSS tùy chỉnh
│   └── uploads/            # Thư mục lưu file upload (avatar, ảnh about)
├── index.php               # Điểm vào chính của ứng dụng
├── init.sql                # Script khởi tạo CSDL
└── .htaccess               # Cấu hình URL Rewrite
```

### Bước 3: Tạo cơ sở dữ liệu

**Cách 1: Dùng phpMyAdmin (khuyến nghị)**

1. Mở trình duyệt, truy cập: `http://localhost/phpmyadmin`
2. Nhấn **New** để tạo database mới (hoặc bỏ qua, script sẽ tự tạo)
3. Chọn tab **SQL**, dán toàn bộ nội dung file `init.sql` vào ô lệnh
4. Nhấn **Go** để thực thi

**Cách 2: Dùng command line**

```bash
mysql -u root -p < C:\xampp\htdocs\btl\init.sql
```

> Script `init.sql` sẽ tự động:
> - Tạo database `web_btl`
> - Tạo bảng `users` và `settings`
> - Chèn dữ liệu mẫu cho trang About

### Bước 4: Cấu hình kết nối CSDL

Mở file `config/database.php` và kiểm tra thông tin kết nối:

```php
private $host     = "localhost";   // Host MySQL
private $db_name  = "web_btl";     // Tên database
private $username = "root";        // Username MySQL
private $password = "";            // Password MySQL (mặc định XAMPP để trống)
```

> ⚠️ Nếu bạn đã đặt mật khẩu cho MySQL, hãy cập nhật `$password` tương ứng.

### Bước 5: Tạo thư mục upload

Tạo thủ công các thư mục sau để lưu file ảnh tải lên (các thư mục này bị bỏ qua bởi Git):

```
public/uploads/avatars/
public/uploads/about/
```

Hoặc chạy lệnh trong PowerShell:

```powershell
mkdir C:\xampp\htdocs\btl\public\uploads\avatars
mkdir C:\xampp\htdocs\btl\public\uploads\about
```

### Bước 6: Kích hoạt mod_rewrite cho Apache

Đảm bảo `mod_rewrite` đã được bật trong Apache:

1. Mở file `C:\xampp\apache\conf\httpd.conf`
2. Tìm dòng `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Bỏ dấu `#` ở đầu dòng (nếu có)
4. Tìm đoạn `<Directory "C:/xampp/htdocs">` và đảm bảo có:
   ```
   AllowOverride All
   ```
5. Khởi động lại Apache trong XAMPP Control Panel

### Bước 7: Truy cập ứng dụng

Mở trình duyệt và truy cập:

```
http://localhost/btl/
```

---

## 🔑 Tài Khoản Mặc Định

Ứng dụng không tạo sẵn tài khoản trong `init.sql`. Thực hiện theo các bước sau:

1. Truy cập trang **Đăng ký**: `http://localhost/btl/auth/register`
2. Tạo tài khoản thành viên đầu tiên
3. Để cấp quyền Admin, vào phpMyAdmin → bảng `users` → sửa trường `role` từ `member` thành `admin`

---

## 🗂️ Cấu Trúc MVC

Ứng dụng sử dụng mô hình MVC tùy chỉnh, routing được xử lý qua `.htaccess` và `index.php`:

```
URL: /btl/{controller}/{action}/{params}
```

| URL | Controller | Action |
|---|---|---|
| `/btl/` | HomeController | index |
| `/btl/auth/login` | AuthController | login |
| `/btl/auth/register` | AuthController | register |
| `/btl/profile/index` | ProfileController | index |
| `/btl/adminUser/index` | AdminUserController | index |
| `/btl/adminSetting/index` | AdminSettingController | index |
| `/btl/product/index` | ProductController | index |
| `/btl/news/index` | NewsController | index |

---

## 🛠️ Công Nghệ Sử Dụng

### Backend
- **PHP** (thuần, không framework) – xử lý logic MVC
- **MySQL / PDO** – kết nối và truy vấn cơ sở dữ liệu
- **Apache mod_rewrite** – URL thân thiện

### Frontend
- **Bootstrap 5.3** – layout và component UI
- **Font Awesome 6** – icon
- **Google Fonts (Inter)** – typography
- **SweetAlert2** – thông báo toast hiện đại
- **Quill.js** – trình soạn thảo WYSIWYG (trang admin)

---

## 📌 Lưu Ý

- Dự án được thiết kế chạy dưới thư mục con `/btl/` trên localhost. Nếu muốn đặt ở thư mục khác, cần cập nhật lại các đường dẫn `/btl/` trong file layout (`views/layouts/main.php`, `views/layouts/admin.php`).
- Thư mục `public/uploads/avatars/` và `public/uploads/about/` bị loại khỏi Git (`.gitignore`) nên phải tạo thủ công sau khi clone.
- Ứng dụng sử dụng **PHP Sessions** để xác thực người dùng. Đảm bảo PHP session đang hoạt động bình thường trên XAMPP.

---

## 📞 Hỗ Trợ

Nếu gặp lỗi trong quá trình cài đặt, hãy kiểm tra:
1. Apache và MySQL đã được khởi động trong XAMPP
2. `mod_rewrite` đã được bật
3. Thông tin database trong `config/database.php` chính xác
4. Đường dẫn thư mục dự án là `C:\xampp\htdocs\btl\`
