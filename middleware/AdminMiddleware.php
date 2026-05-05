<?php
/**
 * AdminMiddleware - Kiểm tra quyền admin trước khi truy cập admin pages
 */
class AdminMiddleware
{
    /**
     * Kiểm tra xem user hiện tại có phải admin không
     * Nếu không, redirect về trang chủ
     */
    public static function check()
    {
        // Kiểm tra session
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_msg'] = "Vui lòng đăng nhập!";
            $_SESSION['flash_type'] = "warning";
            header("Location: /btl/?controller=Auth&action=login");
            exit();
        }

        // Kiểm tra role admin
        if ($_SESSION['user_role'] !== 'admin') {
            $_SESSION['flash_msg'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/");
            exit();
        }
    }

    /**
     * Lấy thông tin user admin hiện tại
     */
    public static function getAdminUser()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'] ?? '',
            'fullname' => $_SESSION['user_fullname'] ?? 'Admin',
            'role' => $_SESSION['user_role'] ?? 'member',
            'avatar' => $_SESSION['user_avatar'] ?? null
        ];
    }
}
?>