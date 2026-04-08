<?php
class NewsController
{
    public function index()
    {
        $title = "Tin tức";
        ob_start();
        require_once 'views/public/news.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
