<?php
require_once 'models/Setting.php';

class AdminSettingController
{
    private $db;
    private $settingModel;

    public function __construct()
    {
        // Chỉ cho phép admin truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['flash_msg'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/");
            exit();
        }
        $this->settingModel = new Setting();
    }

    public function about()
    {
        // Lấy tất cả setting hiện tại
        $settings = $this->settingModel->getAllSettings();

        // Nếu là POST request thì lưu thông tin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý text
            $text_fields = ['about_title', 'about_subtitle', 'about_desc_1', 'about_desc_2'];
            foreach ($text_fields as $field) {
                if (isset($_POST[$field])) {
                    $this->settingModel->updateSetting($field, $_POST[$field]);
                }
            }

            // Xử lý upload ảnh (3 banners)
            $upload_dir = 'public/uploads/about/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_fields = ['about_carousel_1', 'about_carousel_2', 'about_carousel_3'];
            foreach ($image_fields as $field) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES[$field]['tmp_name'];
                    // Tạo tên file duy nhất tránh trùng lặp
                    $filename = time() . '_' . basename($_FILES[$field]['name']);
                    $filepath = "$upload_dir$filename";

                    if (move_uploaded_file($tmp_name, $filepath)) {
                        // Đường dẫn lưu vào DB (Dùng đường dẫn tuyệt đối tĩnh để render)
                        $project_folder = basename(dirname(__DIR__)); // Sẽ trả về 'btl'
                        $db_filepath = "/$project_folder/$filepath";
                        $this->settingModel->updateSetting($field, $db_filepath);
                    }
                }
            }

            $_SESSION['flash_msg'] = "Đã cập nhật nội dung trang Giới thiệu.";
            $_SESSION['flash_type'] = "success";
            header("Location: /btl/adminSetting/about");
            exit();
        }

        $title = "Quản lý Trang Giới Thiệu";
        ob_start();
        require_once 'views/admin/settings/about.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }
}
?>