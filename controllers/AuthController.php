<?php
require_once 'models/User.php';

class AuthController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login()
    {
        // Nếu đã đăng nhập thì về trang chủ
        if (isset($_SESSION['user_id'])) {
            header("Location: /btl/");
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($this->db);
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($user->login($email, $password)) {
                // Tạo session
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['user_role'] = $user->role;

                $_SESSION['flash_msg'] = "Đăng nhập thành công!";
                $_SESSION['flash_type'] = "success";

                header("Location: /btl/");
                exit();
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
            }
        }

        $title = "Đăng nhập";
        ob_start();
        require_once 'views/public/login.php';
        $content = ob_get_clean();
        require_once 'views/layouts/auth.php';
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /btl/");
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($this->db);
            $user->email = $_POST['email'] ?? '';
            $user->password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($user->password) || empty($user->email) || empty($confirm_password)) {
                $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
            } elseif ($user->password !== $confirm_password) {
                $error = "Mật khẩu xác nhận không khớp.";
            } elseif ($user->isEmailExists()) {
                $error = "Email đã được sử dụng!";
            } else {
                if ($user->register()) {
                    $_SESSION['flash_msg'] = "Đăng ký tải khoản thành công. Hãy đăng nhập!";
                    $_SESSION['flash_type'] = "success";
                    header("Location: /btl/auth/login");
                    exit();
                } else {
                    $error = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.";
                }
            }
        }

        $title = "Đăng ký tài khoản";
        ob_start();
        require_once 'views/public/register.php';
        $content = ob_get_clean();
        require_once 'views/layouts/auth.php';
    }

    public function logout()
    {
        session_destroy();
        session_start();
        $_SESSION['flash_msg'] = "Bạn đã đăng xuất.";
        $_SESSION['flash_type'] = "info";
        header("Location: /btl/");
        exit();
    }
}
