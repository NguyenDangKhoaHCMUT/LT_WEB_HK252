<?php
require_once 'models/User.php';

class ProfileController
{
    private $db;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /btl/auth/login");
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index()
    {
        $user = new User($this->db);
        $user->id = $_SESSION['user_id'];
        $user_data = $user->getUserById();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
                $_SESSION['flash_msg'] = "Lỗi bảo mật: CSRF Token không hợp lệ.";
                $_SESSION['flash_type'] = "danger";
                header("Location: /btl/profile/index");
                exit();
            }
            // Update Profile
            if (isset($_POST['update_profile'])) {
                $user->fullname = $_POST['fullname'] ?? '';
                $user->email = $_POST['email'] ?? '';

                // Process Avatar Upload
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                    $target_dir = "public/uploads/avatars/";
                    if (!is_dir($target_dir))
                        mkdir($target_dir, 0777, true);

                    $filename = time() . '_' . basename($_FILES["avatar"]["name"]);
                    $target_file = $target_dir . $filename;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                            $user->avatar = $target_file;
                        }
                    }
                }

                if ($user->updateProfile()) {
                    // Xóa avatar cũ nếu có upload avatar mới thành công
                    if (!empty($user->avatar) && !empty($user_data['avatar']) && file_exists($user_data['avatar']) && $user->avatar !== $user_data['avatar']) {
                        unlink($user_data['avatar']);
                    }

                    $_SESSION['flash_msg'] = "Cập nhật thông tin thành công!";
                    $_SESSION['flash_type'] = "success";
                    $user_data = $user->getUserById(); // Refresh data
                    $_SESSION['avatar'] = $user_data['avatar'];

                    // Thực hiện redirect để tránh resubmit form khi F5
                    header("Location: /btl/profile/index");
                    exit();
                } else {
                    // Xóa file avatar mới tải lên nếu updateProfile thất bại để tránh rác
                    if (!empty($user->avatar) && file_exists($user->avatar)) {
                        unlink($user->avatar);
                    }
                }
            }

            // Change Password
            if (isset($_POST['change_password'])) {
                $old_password = $_POST['old_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';

                if (password_verify($old_password, $user_data['password'])) {
                    if (strlen($new_password) >= 6) {
                        if ($user->changePassword($new_password)) {
                            $_SESSION['flash_msg'] = "Mật khẩu đã được thay đổi!";
                            $_SESSION['flash_type'] = "success";

                            // Thực hiện redirect để tránh resubmit form khi F5
                            header("Location: /btl/profile/index");
                            exit();
                        } else {
                            $error = "Không thể đổi mật khẩu.";
                        }
                    } else {
                        $error = "Mật khẩu mới phải có ít nhất 6 ký tự.";
                    }
                } else {
                    $error = "Mật khẩu cũ không đúng.";
                }
            }
        }

        $title = "Thông tin cá nhân";
        ob_start();
        require_once 'views/public/profile.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
