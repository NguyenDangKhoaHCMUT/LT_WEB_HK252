<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'models/Product.php';

class CartController
{
    private $db;
    private $orderModel;
    private $productModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            // Detect AJAX requests and return JSON instead of redirect
            $isAjax = (
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            );

            if ($isAjax) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Vui lòng đăng nhập để sử dụng giỏ hàng.', 'login_url' => '/btl/auth/login']);
                exit();
            }

            $_SESSION['flash_msg'] = 'Vui lòng đăng nhập để sử dụng giỏ hàng.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/auth/login');
            exit();
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
        $this->productModel = new Product($this->db);
    }

    public function index()
    {
        $cart = $this->orderModel->getCart($_SESSION['user_id']);

        $title = 'Giỏ hàng';
        ob_start();
        require_once 'views/public/cart.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/product/index');
            exit();
        }

        $product_id = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($product_id <= 0) {
            $_SESSION['flash_msg'] = 'Sản phẩm không hợp lệ.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/product/index');
            exit();
        }

        $product = $this->productModel->getProductById($product_id);
        if (!$product) {
            $_SESSION['flash_msg'] = 'Không tìm thấy sản phẩm.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/product/index');
            exit();
        }

        if ($this->orderModel->addToCart($_SESSION['user_id'], $product_id, $quantity)) {
            $_SESSION['flash_msg'] = 'Đã thêm ' . htmlspecialchars($product['name'], ENT_QUOTES) . ' vào giỏ hàng.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_msg'] = 'Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.';
            $_SESSION['flash_type'] = 'danger';
        }

        header('Location: /btl/cart');
        exit();
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/cart');
            exit();
        }

        $product_id = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($product_id <= 0) {
            $_SESSION['flash_msg'] = 'Sản phẩm không hợp lệ.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/cart');
            exit();
        }

        if ($this->orderModel->updateCartItem($_SESSION['user_id'], $product_id, $quantity)) {
            $_SESSION['flash_msg'] = 'Cập nhật giỏ hàng thành công.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_msg'] = 'Không thể cập nhật giỏ hàng. Vui lòng thử lại.';
            $_SESSION['flash_type'] = 'danger';
        }

        header('Location: /btl/cart');
        exit();
    }

    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/cart');
            exit();
        }

        $product_id = (int) ($_POST['product_id'] ?? 0);

        if ($product_id <= 0) {
            $_SESSION['flash_msg'] = 'Sản phẩm không hợp lệ.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/cart');
            exit();
        }

        if ($this->orderModel->removeCartItem($_SESSION['user_id'], $product_id)) {
            $_SESSION['flash_msg'] = 'Đã xóa sản phẩm khỏi giỏ hàng.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_msg'] = 'Không thể xóa sản phẩm. Vui lòng thử lại.';
            $_SESSION['flash_type'] = 'danger';
        }

        header('Location: /btl/cart');
        exit();
    }
}
?>
