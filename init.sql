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

-- Bảng câu hỏi thường gặp (FAQ - do admin quản lý)
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    is_published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng câu hỏi từ người dùng (user gửi, admin trả lời)
CREATE TABLE IF NOT EXISTS user_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question TEXT NOT NULL,
    answer TEXT,
    status ENUM('pending', 'answered') DEFAULT 'pending',
    answered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Dữ liệu mẫu FAQ
INSERT IGNORE INTO faqs (question, answer, sort_order, is_published) VALUES
('TechStore có chính sách đổi trả như thế nào?', 'TechStore áp dụng chính sách đổi trả trong vòng 30 ngày kể từ ngày mua. Sản phẩm phải còn nguyên vẹn, đầy đủ phụ kiện và hóa đơn mua hàng.', 1, 1),
('Tôi có thể mua hàng trả góp không?', 'Có! TechStore hỗ trợ trả góp 0% lãi suất qua thẻ tín dụng các ngân hàng lớn và ví điện tử như MoMo, ZaloPay với kỳ hạn từ 3 đến 24 tháng.', 2, 1),
('Thời gian giao hàng mất bao lâu?', 'Nội thành: 2-4 giờ. Ngoại thành và các tỉnh thành khác: 1-3 ngày làm việc. Miễn phí giao hàng cho đơn từ 2 triệu đồng.', 3, 1),
('Sản phẩm có bảo hành không?', 'Tất cả sản phẩm tại TechStore đều có bảo hành chính hãng từ 12 đến 24 tháng tùy theo hãng và model. Ngoài ra, TechStore cung cấp thêm 6 tháng bảo hành mở rộng miễn phí.', 4, 1);
