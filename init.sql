-- Khởi tạo Database cho BTL Lập trình Web
CREATE DATABASE IF NOT EXISTS web_btl;
USE web_btl;

-- Bảng liên hệ
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    status ENUM('new','read','replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng người dùng (Chỉ chứa bảng liên quan đến phần công việc chung: Đăng nhập, phân quyền, quản lý user)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fullname VARCHAR(100),
    avatar VARCHAR(255),
    role ENUM('admin', 'member') DEFAULT 'member',
    status ENUM('active', 'locked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng settings (Lưu trữ cấu hình, nội dung động của website)
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Dữ liệu khởi tạo mẫu cho trang About
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES 
('about_subtitle', 'Câu chuyện của chúng tôi'),
('about_title', '<span class=\"text-primary\">Sứ mệnh</span> số hóa'),
('about_desc_1', 'Tại <strong>TechStore</strong>, chúng tôi tin rằng công nghệ là chìa khóa để khai mở những giới hạn mới của con người. Được thành lập với khát khao thu hẹp khoảng cách công nghệ, chúng tôi không ngừng nỗ lực mang đến cho khách hàng các thiết bị Smartphone và Laptop tiên tiến nhất hiện nay.'),
('about_desc_2', 'Chúng tôi tâm niệm \"Trải nghiệm vượt kỳ vọng\". Do đó, ngoài các dòng sản phẩm chất lượng cao với mức giá cạnh tranh, TechStore tự hào cung cấp dịch vụ hậu mãi đẳng cấp, bảo hành minh bạch và tư vấn bằng cả trái tim.'),
('about_carousel_1', 'https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=2070&auto=format&fit=crop'),
('about_carousel_2', 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop'),
('about_carousel_3', 'https://images.unsplash.com/photo-1531297172864-45d6124c9c8c?q=80&w=2070&auto=format&fit=crop');
