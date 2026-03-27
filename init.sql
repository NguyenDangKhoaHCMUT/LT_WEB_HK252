-- Khởi tạo Database cho BTL Lập trình Web
CREATE DATABASE IF NOT EXISTS web_btl;
USE web_btl;

-- Bảng người dùng (Chỉ chứa bảng liên quan đến phần công việc chung: Đăng nhập, phân quyền, quản lý user)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fullname VARCHAR(100),
    avatar VARCHAR(255),
    role ENUM('admin', 'member') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
