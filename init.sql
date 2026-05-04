-- Khởi tạo Database cho BTL Lập trình Web
CREATE DATABASE IF NOT EXISTS web_btl;
USE web_btl;

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

