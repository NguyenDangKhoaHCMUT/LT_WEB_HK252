<?php
require_once 'models/User.php';

class AdminUserController
{
    private $db;

    public function __construct()
    {
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

    public function index()
    {
        $userModel = new User($this->db);
        $users = $userModel->getAllUsers();

        $title = "Quản lý Người Dùng";
        ob_start();
        require_once 'views/admin/users/index.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function toggle_status($id)
    {
        if (!Csrf::validate($_GET['csrf_token'] ?? '')) {
            $_SESSION['flash_msg'] = "Lỗi bảo mật: CSRF Token không hợp lệ.";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminUser/index");
            exit();
        }
        $userModel = new User($this->db);
        if ($userModel->toggleStatus($id)) {
            $_SESSION['flash_msg'] = "Đã cập nhật trạng thái tài khoản.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg'] = "Có lỗi xảy ra khi cập nhật trạng thái.";
            $_SESSION['flash_type'] = "danger";
        }
        header("Location: /btl/adminUser/index");
        exit();
    }

    public function view($id)
    {
        $userModel = new User($this->db);
        $userModel->id = $id;
        $member = $userModel->getUserById();

        if (!$member || $member['role'] === 'admin') {
            $_SESSION['flash_msg'] = "Không tìm thấy thành viên hoặc bạn không có quyền.";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminUser/index");
            exit();
        }

        $title = "Chi tiết Thành viên";
        ob_start();
        require_once 'views/admin/users/view.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
                $_SESSION['flash_msg'] = "Lỗi bảo mật: CSRF Token không hợp lệ.";
                $_SESSION['flash_type'] = "danger";
                header("Location: /btl/adminUser/view/" . $id);
                exit();
            }
            $userModel = new User($this->db);
            $userModel->id = $id;

            $existing_member = $userModel->getUserById();
            if (!$existing_member || $existing_member['role'] === 'admin') {
                header("Location: /btl/adminUser/index");
                exit();
            }

            $userModel->fullname = $_POST['fullname'] ?? '';
            $userModel->email = $_POST['email'] ?? '';
            $userModel->avatar = $existing_member['avatar'];

            if ($userModel->updateProfile()) {
                $_SESSION['flash_msg'] = "Cập nhật thông tin thành công.";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_msg'] = "Cập nhật thất bại, email có thể đã tồn tại.";
                $_SESSION['flash_type'] = "danger";
            }
        }
        header("Location: /btl/adminUser/view/" . $id);
        exit();
    }

    public function reset_password($id)
    {
        if (!Csrf::validate($_GET['csrf_token'] ?? '')) {
            $_SESSION['flash_msg'] = "Lỗi bảo mật: CSRF Token không hợp lệ.";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminUser/index");
            exit();
        }
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

    public function delete($id)
    {
        if (!Csrf::validate($_GET['csrf_token'] ?? '')) {
            $_SESSION['flash_msg'] = "Lỗi bảo mật: CSRF Token không hợp lệ.";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminUser/index");
            exit();
        }
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