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

        // SEO
        $title = !empty($keyword)
            ? 'Tìm kiếm: ' . htmlspecialchars($keyword, ENT_QUOTES) . ' | Sản phẩm TechStore'
            : 'Sản phẩm công nghệ chính hãng | TechStore';
        $metaDescription = !empty($keyword)
            ? 'Kết quả tìm kiếm sản phẩm cho "' . htmlspecialchars($keyword, ENT_QUOTES) . '" tại TechStore.'
            : 'Khám phá bộ sưu tập smartphone, laptop và phụ kiện công nghệ chính hãng với giá tốt nhất tại TechStore.';
        $metaKeywords = 'sản phẩm công nghệ, smartphone, laptop, phụ kiện, TechStore';
        $canonicalUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/btl/product/index' . ($page > 1 ? '?page=' . $page : '');
        $ogTitle = $title;
        $ogDescription = $metaDescription;

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

        // SEO
        $productName = htmlspecialchars($product['name'] ?? 'Chi tiết sản phẩm', ENT_QUOTES);
        $categoryName = htmlspecialchars($product['category_name'] ?? '', ENT_QUOTES);
        $title = $productName . ($categoryName ? ' - ' . $categoryName : '') . ' | TechStore';
        $metaDescription = mb_substr(strip_tags($product['description'] ?? ''), 0, 160) ?: 'Mua ' . $productName . ' chính hãng, giá tốt tại TechStore.';
        $metaKeywords = implode(', ', array_filter([$productName, $categoryName, 'mua online', 'chính hãng', 'TechStore']));
        $canonicalUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/btl/product/detail/' . urlencode($product['slug'] ?? '');
        $ogTitle = $title;
        $ogDescription = $metaDescription;
        $ogImage = !empty($product['image']) ? $product['image'] : null;
        $ogType = 'product';

        ob_start();
        require_once 'views/public/product_detail.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
