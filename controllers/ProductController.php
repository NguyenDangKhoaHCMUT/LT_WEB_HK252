<?php
class ProductController
{
    public function index()
    {
        $title = "Sản phẩm";
        ob_start();
        require_once 'views/public/product.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
