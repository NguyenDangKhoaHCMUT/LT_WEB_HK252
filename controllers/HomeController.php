<?php
class HomeController
{
    public function index()
    {
        $title = "Trang chủ";
        ob_start();
        require_once 'views/public/home.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function about()
    {
        $title = "Giới thiệu";
        ob_start();
        require_once 'views/public/about.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function contact()
    {
        $title = "Liên hệ";
        ob_start();
        require_once 'views/public/contact.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function faq()
    {
        $title = "Hỏi/đáp";
        ob_start();
        require_once 'views/public/faq.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
?>