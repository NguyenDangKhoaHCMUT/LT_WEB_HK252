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
('about_title', '<span class="text-primary">Sứ mệnh</span> số hóa'),
('about_desc_1', 'Tại <strong>TechStore</strong>, chúng tôi tin rằng công nghệ là chìa khóa để khai mở những giới hạn mới của con người. Được thành lập với khát khao thu hẹp khoảng cách công nghệ, chúng tôi không ngừng nỗ lực mang đến cho khách hàng các thiết bị Smartphone và Laptop tiên tiến nhất hiện nay.'),
('about_desc_2', 'Chúng tôi tâm niệm "Trải nghiệm vượt kỳ vọng". Do đó, ngoài các dòng sản phẩm chất lượng cao với mức giá cạnh tranh, TechStore tự hào cung cấp dịch vụ hậu mãi đẳng cấp, bảo hành minh bạch và tư vấn bằng cả trái tim.'),
('about_carousel_1', 'https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=2070&auto=format&fit=crop'),
('about_carousel_2', 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop'),
('about_carousel_3', 'https://images.unsplash.com/photo-1531297172864-45d6124c9c8c?q=80&w=2070&auto=format&fit=crop');

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    summary TEXT,
    content LONGTEXT NOT NULL,
    thumbnail VARCHAR(255),
    seo_title VARCHAR(255),
    seo_description TEXT,
    seo_keywords VARCHAR(255),
    status ENUM('draft', 'published', 'hidden') DEFAULT 'draft',
    author_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_posts_slug (slug),
    KEY idx_posts_title (title),
    KEY idx_posts_status (status),
    KEY idx_posts_author_id (author_id),
    CONSTRAINT fk_posts_author
        FOREIGN KEY (author_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NULL,
    content TEXT NOT NULL,
    rating TINYINT UNSIGNED NULL,
    status ENUM('pending', 'approved', 'hidden') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    KEY idx_comments_post_id (post_id),
    KEY idx_comments_user_id (user_id),
    KEY idx_comments_status (status),
    KEY idx_comments_post_status (post_id, status),
    CONSTRAINT fk_comments_post
        FOREIGN KEY (post_id) REFERENCES posts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS post_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NULL,
    viewer_ip VARCHAR(45) NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    KEY idx_post_views_post_id (post_id),
    KEY idx_post_views_user_id (user_id),
    CONSTRAINT fk_post_views_post
        FOREIGN KEY (post_id) REFERENCES posts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_post_views_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS comment_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NULL,
    reason VARCHAR(255) NOT NULL,
    status ENUM('pending', 'reviewed', 'dismissed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    KEY idx_comment_reports_comment_id (comment_id),
    KEY idx_comment_reports_user_id (user_id),
    CONSTRAINT fk_comment_reports_comment
        FOREIGN KEY (comment_id) REFERENCES comments(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_comment_reports_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

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

-- Tạo bảng categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    price INT NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tạo bảng orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('cart', 'pending', 'processing', 'completed', 'cancelled') DEFAULT 'cart',
    total_amount INT DEFAULT 0,
    customer_name VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_order_product (order_id, product_id)
);

-- Dữ liệu mẫu cho categories
INSERT IGNORE INTO categories (id, name, created_at) VALUES
(1, 'Smartphone', CURRENT_TIMESTAMP),
(2, 'Laptop', CURRENT_TIMESTAMP);

-- Dữ liệu mẫu cho products
INSERT IGNORE INTO products (id, category_id, name, slug, description, image, price, stock, created_at, updated_at) VALUES
(1, 1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'iPhone 15 Pro Max với khung titan, chip A17 Pro và camera zoom quang học 5x, phù hợp cho nhu cầu cao cấp.', 'https://images.unsplash.com/photo-1709178295038-acbeec786fcf?q=80&w=1527&auto=format&fit=crop', 32990000, 12, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 1, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Galaxy S24 Ultra sở hữu màn hình lớn, camera 200MP và S Pen cho trải nghiệm làm việc linh hoạt.', 'https://images.unsplash.com/photo-1706832608032-61cced969d6a?q=80&w=774&auto=format&fit=crop', 31990000, 10, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 2, 'MacBook Air M3', 'macbook-air-m3', 'MacBook Air M3 mỏng nhẹ, pin lâu và hiệu năng ổn định cho học tập lẫn công việc văn phòng.', 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=1200&auto=format&fit=crop', 28990000, 8, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 2, 'ASUS ROG Strix G16', 'asus-rog-strix-g16', 'Laptop gaming hiệu năng cao, phù hợp cho game thủ và người dùng cần cấu hình mạnh.', 'https://images.unsplash.com/photo-1771014846919-3a1cf73aeea1?w=500&auto=format&fit=crop', 35990000, 6, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(5, 1, 'Xiaomi 15', 'xiaomi-15', 'Xiaomi 15 trang bị ống kính Leica Summilux, chip Snapdragon 8 Elite và pin 5500mAh, mang đến trải nghiệm nhiếp ảnh chuyên nghiệp trên smartphone.', 'https://images.unsplash.com/photo-1702451462735-3f0ba7679641?q=80&w=774&auto=format&fit=crop', 24990000, 15, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(6, 1, 'Google Pixel 9 Pro', 'google-pixel-9-pro', 'Google Pixel 9 Pro với AI tích hợp sâu, camera Tensor G4 cho khả năng chụp ảnh ban đêm tuyệt vời và 7 năm cập nhật phần mềm.', 'https://images.unsplash.com/photo-1727132528094-117c9dceb047?w=500&auto=format&fit=crop', 26490000, 9, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(7, 2, 'Dell XPS 16', 'dell-xps-16', 'Dell XPS 16 với màn hình OLED 16 inch, Intel Core Ultra 9 và thiết kế viền siêu mỏng, lý tưởng cho nhà sáng tạo nội dung.', 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&auto=format&fit=crop', 42990000, 5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(8, 2, 'HP EliteBook 840 G11', 'hp-elitebook-840-g11', 'HP EliteBook 840 G11 với Intel Core Ultra 7, màn hình 14 inch chống chói và bảo mật HP Wolf Security, lựa chọn hoàn hảo cho doanh nghiệp.', 'https://images.unsplash.com/photo-1663354027456-ce6a7e07d212?q=80&w=774&auto=format&fit=crop', 38490000, 7, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Dữ liệu mẫu FAQ
INSERT IGNORE INTO faqs (question, answer, sort_order, is_published) VALUES
('TechStore có chính sách đổi trả như thế nào?', 'TechStore áp dụng chính sách đổi trả trong vòng 30 ngày kể từ ngày mua. Sản phẩm phải còn nguyên vẹn, đầy đủ phụ kiện và hóa đơn mua hàng.', 1, 1),
('Tôi có thể mua hàng trả góp không?', 'Có! TechStore hỗ trợ trả góp 0% lãi suất qua thẻ tín dụng các ngân hàng lớn và ví điện tử như MoMo, ZaloPay với kỳ hạn từ 3 đến 24 tháng.', 2, 1),
('Thời gian giao hàng mất bao lâu?', 'Nội thành: 2-4 giờ. Ngoại thành và các tỉnh thành khác: 1-3 ngày làm việc. Miễn phí giao hàng cho đơn từ 2 triệu đồng.', 3, 1),
('Sản phẩm có bảo hành không?', 'Tất cả sản phẩm tại TechStore đều có bảo hành chính hãng từ 12 đến 24 tháng tùy theo hãng và model. Ngoài ra, TechStore cung cấp thêm 6 tháng bảo hành mở rộng miễn phí.', 4, 1);