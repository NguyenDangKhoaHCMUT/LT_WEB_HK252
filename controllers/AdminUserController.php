<?php
require_once 'models/User.php';

class AdminUserController {
    private $db;

    public function __construct() {
        // Chỉ cho phép admin truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['flash_msg'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/");
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        $userModel = new User($this->db);
        $users = $userModel->getAllUsers();

        $title = "Quản lý Người Dùng";
        ob_start();
        require_once 'views/admin/users/index.php';
        $content = ob_get_clean();
        
        require_once 'views/layouts/admin.php';
    }

    public function reset_password($id) {
        $userModel = new User($this->db);
        if ($userModel->resetPassword($id)) {
            $_SESSION['flash_msg'] = "Reset mật khẩu thành '123456' thành công.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg'] = "Có lỗi xảy ra khi reset mật khẩu.";
            $_SESSION['flash_type'] = "danger";
        }
        header("Location: /btl/adminUser/index");
        exit();
    }

    public function delete($id) {
        $userModel = new User($this->db);
        if ($userModel->deleteUser($id)) {
            $_SESSION['flash_msg'] = "Xoá người dùng thành công.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg'] = "Không thể xoá admin hoặc có lỗi xảy ra.";
            $_SESSION['flash_type'] = "danger";
        }
        header("Location: /btl/adminUser/index");
        exit();
    }
}
?>
