<?php
class HomeController
{
    public function index()
    {
        // Đặt biến cần truyền ra view
        $title = "Trang chủ";

        // Nạp view vào trong layout
        ob_start();
        require_once 'views/public/home.php';
        $content = ob_get_clean();

        require_once 'views/layouts/main.php';
    }
}
?>