<?php
class Csrf {
    /**
     * Khởi tạo hoặc lấy token hiện tại từ session
     */
    public static function getToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Chèn một input ẩn chứa CSRF token vào form HTML
     */
    public static function insertHiddenField() {
        $token = self::getToken();
        echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Kiểm tra tính hợp lệ của token gửi lên từ request
     */
    public static function validate($token) {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Thêm CSRF token vào một URL (dùng cho các link xóa, toggle status...)
     */
    public static function addTokenToUrl($url) {
        $token = self::getToken();
        $separator = (strpos($url, '?') === false) ? '?' : '&';
        return $url . $separator . 'csrf_token=' . $token;
    }
}
