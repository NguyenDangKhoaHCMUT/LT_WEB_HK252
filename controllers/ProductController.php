<?php
class ProductController
{
    public function index()
    {
        require_once 'config/database.php';
        require_once 'models/Product.php';

        $database = new Database();
        $db = $database->getConnection();
        $productModel = new Product($db);

        $keyword = trim($_GET['keyword'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 8;
        $offset = ($page - 1) * $limit;

        $products = $productModel->getAllProducts($keyword, $limit, $offset);
        $totalProducts = $productModel->countProducts($keyword);
        $totalPages = max(1, (int) ceil($totalProducts / $limit));

        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $products = $productModel->getAllProducts($keyword, $limit, $offset);
        }

        $title = "Sản phẩm";
        ob_start();
        require_once 'views/public/product.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function detail($slug = '')
    {
        require_once 'config/database.php';
        require_once 'models/Product.php';

        $slug = trim($slug);
        if ($slug === '') {
            header('Location: /btl/product/index');
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $productModel = new Product($db);

        $product = $productModel->getProductBySlug($slug);
        if (!$product) {
            $_SESSION['flash_msg'] = 'Không tìm thấy sản phẩm.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/product/index');
            exit();
        }

        $related = $productModel->getRelatedProducts($product['category_id'] ?? null, $product['id'] ?? null, 4);

        $title = $product['name'] ?? 'Chi tiết sản phẩm';
        ob_start();
        require_once 'views/public/product_detail.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
