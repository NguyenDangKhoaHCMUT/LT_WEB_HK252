-- Khởi tạo Database cho BTL Lập trình Web
CREATE DATABASE IF NOT EXISTS web_btl
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng settings (Lưu trữ cấu hình, nội dung động của website)
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_categories_slug (slug),
    KEY idx_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS post_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    KEY idx_post_categories_post_id (post_id),
    KEY idx_post_categories_category_id (category_id),
    CONSTRAINT fk_post_categories_post
        FOREIGN KEY (post_id) REFERENCES posts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_post_categories_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('about_subtitle', 'Câu chuyện của chúng tôi'),
('about_title', '<span class="text-primary">Sứ mệnh</span> số hóa'),
('about_desc_1', 'Tại <strong>TechStore</strong>, chúng tôi tin rằng công nghệ là chìa khóa để khai mở những giới hạn mới của con người. Được thành lập với khát khao thu hẹp khoảng cách công nghệ, chúng tôi không ngừng nỗ lực mang đến cho khách hàng các thiết bị Smartphone và Laptop tiên tiến nhất hiện nay.'),
('about_desc_2', 'Chúng tôi tâm niệm "Trải nghiệm vượt kỳ vọng". Do đó, ngoài các dòng sản phẩm chất lượng cao với mức giá cạnh tranh, TechStore tự hào cung cấp dịch vụ hậu mãi đẳng cấp, bảo hành minh bạch và tư vấn bằng cả trái tim.'),
('about_carousel_1', 'https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=2070&auto=format&fit=crop'),
('about_carousel_2', 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop'),
('about_carousel_3', 'https://images.unsplash.com/photo-1531297172864-45d6124c9c8c?q=80&w=2070&auto=format&fit=crop');

INSERT IGNORE INTO categories (name, slug, description) VALUES
('Tin công nghệ', 'tin-cong-nghe', 'Các xu hướng mới về công nghệ và thị trường thiết bị số.'),
('Đánh giá', 'danh-gia', 'Đánh giá nhanh sản phẩm và trải nghiệm sử dụng thực tế.'),
('Kinh nghiệm mua sắm', 'kinh-nghiem-mua-sam', 'Mẹo chọn thiết bị phù hợp với nhu cầu và ngân sách.');

INSERT IGNORE INTO posts (
    title,
    slug,
    summary,
    content,
    thumbnail,
    seo_title,
    seo_description,
    seo_keywords,
    status,
    author_id
) VALUES
(
    '5 xu hướng laptop AI đáng chú ý trong năm 2026',
    '5-xu-huong-laptop-ai-2026',
    'Tổng hợp nhanh những thay đổi nổi bật trên các dòng laptop mới: NPU mạnh hơn, pin bền hơn và trải nghiệm làm việc thông minh hơn.',
    '<p>Laptop AI đang đi từ khái niệm quảng bá sang giá trị sử dụng thực tế. Các mẫu máy mới không chỉ nâng cấp CPU và GPU, mà còn bổ sung <strong>NPU</strong> để xử lý các tác vụ như tóm tắt nội dung, dịch trực tiếp, làm mờ nền video và tối ưu hiệu năng theo ngữ cảnh.</p><p>Trong năm 2026, người dùng quan tâm nhiều hơn tới ba yếu tố: thời lượng pin khi chạy tác vụ văn phòng, khả năng chạy ứng dụng AI cục bộ và độ ổn định nhiệt khi làm việc dài giờ. Với nhóm sinh viên và dân văn phòng, đây là giai đoạn rất phù hợp để nâng cấp máy nếu đang dùng thiết bị đã trên 4 năm tuổi.</p><p>Nếu triển khai task tin tức cho môn học, đây cũng là loại nội dung rất dễ trình bày vì vừa có tính cập nhật, vừa thuận tiện gắn thẻ chuyên mục và bình luận người dùng.</p>',
    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=1200&auto=format&fit=crop',
    '5 xu hướng laptop AI đáng chú ý trong năm 2026',
    'Những điểm đáng chú ý trên thị trường laptop AI năm 2026 dành cho người học tập và làm việc.',
    'laptop ai, xu huong cong nghe, laptop 2026',
    'published',
    NULL
),
(
    'Có nên mua smartphone tầm trung để học tập và làm việc?',
    'co-nen-mua-smartphone-tam-trung',
    'Phân tích nhanh các tiêu chí quan trọng khi chọn smartphone tầm trung cho nhu cầu học online, chụp tài liệu và giải trí.',
    '<p>Phân khúc tầm trung vẫn là lựa chọn hợp lý nhất cho đa số người dùng phổ thông. Thay vì chạy theo cấu hình quá cao, bạn nên ưu tiên màn hình đủ sáng, pin tốt, camera chụp văn bản rõ và bộ nhớ tối thiểu 256GB nếu có nhu cầu lưu nhiều tài liệu.</p><p>Ở góc nhìn xây dựng hệ thống blog, bài viết dạng tư vấn mua sắm giúp thể hiện rõ các trường dữ liệu như <em>summary</em>, <em>seo_title</em> và phần bình luận đánh giá của người dùng.</p>',
    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1200&auto=format&fit=crop',
    'Có nên mua smartphone tầm trung để học tập và làm việc?',
    'Tư vấn chọn smartphone tầm trung phù hợp với học tập, làm việc và giải trí.',
    'smartphone tam trung, tu van mua dien thoai, cong nghe',
    'published',
    NULL
),
(
    'Checklist tối thiểu trước khi đăng một bài viết sản phẩm',
    'checklist-toi-thieu-truoc-khi-dang-bai-viet',
    'Một checklist ngắn giúp nhóm kiểm tra nội dung bài viết trước khi xuất bản trên website.',
    '<p>Trước khi chuyển trạng thái bài viết sang <strong>published</strong>, nhóm nên kiểm tra lại tiêu đề, slug, ảnh thumbnail, mô tả tóm tắt và các thẻ SEO cơ bản. Ngoài ra, cần đọc lại phần nội dung để tránh lỗi chính tả và đảm bảo hình ảnh không bị hỏng liên kết.</p><p>Checklist này đặc biệt hữu ích khi làm bài tập lớn vì giúp cả nhóm thống nhất quy trình nhập liệu cho module tin tức.</p>',
    'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=1200&auto=format&fit=crop',
    'Checklist tối thiểu trước khi đăng một bài viết sản phẩm',
    'Danh sách kiểm tra nhanh trước khi xuất bản bài viết trên website.',
    'seo bai viet, checklist dang bai, quan ly noi dung',
    'draft',
    NULL
);

INSERT IGNORE INTO post_categories (post_id, category_id)
SELECT p.id, c.id
FROM posts p
JOIN categories c ON c.slug = 'tin-cong-nghe'
WHERE p.slug = '5-xu-huong-laptop-ai-2026'
  AND NOT EXISTS (
      SELECT 1
      FROM post_categories pc
      WHERE pc.post_id = p.id
        AND pc.category_id = c.id
        AND pc.deleted_at IS NULL
  );

INSERT IGNORE INTO post_categories (post_id, category_id)
SELECT p.id, c.id
FROM posts p
JOIN categories c ON c.slug = 'danh-gia'
WHERE p.slug = '5-xu-huong-laptop-ai-2026'
  AND NOT EXISTS (
      SELECT 1
      FROM post_categories pc
      WHERE pc.post_id = p.id
        AND pc.category_id = c.id
        AND pc.deleted_at IS NULL
  );

INSERT IGNORE INTO post_categories (post_id, category_id)
SELECT p.id, c.id
FROM posts p
JOIN categories c ON c.slug = 'kinh-nghiem-mua-sam'
WHERE p.slug = 'co-nen-mua-smartphone-tam-trung'
  AND NOT EXISTS (
      SELECT 1
      FROM post_categories pc
      WHERE pc.post_id = p.id
        AND pc.category_id = c.id
        AND pc.deleted_at IS NULL
  );

INSERT IGNORE INTO post_categories (post_id, category_id)
SELECT p.id, c.id
FROM posts p
JOIN categories c ON c.slug = 'danh-gia'
WHERE p.slug = 'co-nen-mua-smartphone-tam-trung'
  AND NOT EXISTS (
      SELECT 1
      FROM post_categories pc
      WHERE pc.post_id = p.id
        AND pc.category_id = c.id
        AND pc.deleted_at IS NULL
  );

INSERT IGNORE INTO comments (post_id, user_id, content, rating, status)
SELECT p.id, NULL, 'Bài viết dễ theo dõi, phần tóm tắt và các ý chính khá rõ ràng.', 5, 'approved'
FROM posts p
WHERE p.slug = '5-xu-huong-laptop-ai-2026'
  AND NOT EXISTS (
      SELECT 1
      FROM comments c
      WHERE c.post_id = p.id
        AND c.user_id IS NULL
        AND c.content = 'Bài viết dễ theo dõi, phần tóm tắt và các ý chính khá rõ ràng.'
        AND c.deleted_at IS NULL
  );

INSERT IGNORE INTO comments (post_id, user_id, content, rating, status)
SELECT p.id, NULL, 'Mình thích kiểu bài tư vấn ngắn gọn như thế này, phù hợp để tham khảo trước khi mua.', 4, 'approved'
FROM posts p
WHERE p.slug = 'co-nen-mua-smartphone-tam-trung'
  AND NOT EXISTS (
      SELECT 1
      FROM comments c
      WHERE c.post_id = p.id
        AND c.user_id IS NULL
        AND c.content = 'Mình thích kiểu bài tư vấn ngắn gọn như thế này, phù hợp để tham khảo trước khi mua.'
        AND c.deleted_at IS NULL
  );

INSERT IGNORE INTO post_views (post_id, user_id, viewer_ip)
SELECT p.id, NULL, '127.0.0.1'
FROM posts p
WHERE p.slug = '5-xu-huong-laptop-ai-2026'
  AND NOT EXISTS (
      SELECT 1
      FROM post_views pv
      WHERE pv.post_id = p.id
        AND pv.viewer_ip = '127.0.0.1'
        AND pv.deleted_at IS NULL
  );

INSERT IGNORE INTO post_views (post_id, user_id, viewer_ip)
SELECT p.id, NULL, '127.0.0.2'
FROM posts p
WHERE p.slug = '5-xu-huong-laptop-ai-2026'
  AND NOT EXISTS (
      SELECT 1
      FROM post_views pv
      WHERE pv.post_id = p.id
        AND pv.viewer_ip = '127.0.0.2'
        AND pv.deleted_at IS NULL
  );

INSERT IGNORE INTO post_views (post_id, user_id, viewer_ip)
SELECT p.id, NULL, '127.0.0.3'
FROM posts p
WHERE p.slug = 'co-nen-mua-smartphone-tam-trung'
  AND NOT EXISTS (
      SELECT 1
      FROM post_views pv
      WHERE pv.post_id = p.id
        AND pv.viewer_ip = '127.0.0.3'
        AND pv.deleted_at IS NULL
  );
