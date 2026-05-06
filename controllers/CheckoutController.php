<?php
require_once 'config/database.php';
require_once 'models/Order.php';

class CheckoutController
{
    private $db;
    private $orderModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_msg'] = 'Vui lòng đăng nhập để thanh toán.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/auth/login');
            exit();
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
    }

    public function index()
    {
        $cart = $this->orderModel->getCart($_SESSION['user_id']);

        if (empty($cart['items'])) {
            $_SESSION['flash_msg'] = 'Giỏ hàng trống. Vui lòng thêm sản phẩm.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/cart');
            exit();
        }

        $title = 'Thanh toán';
        ob_start();
        require_once 'views/public/checkout.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function place()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/checkout');
            exit();
        }

        $customer_name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        $errors = [];

        if ($customer_name === '') {
            $errors[] = 'Tên khách hàng không được để trống.';
        }

        if ($phone === '') {
            $errors[] = 'Số điện thoại không được để trống.';
        } elseif (!preg_match('/^0\d{9}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và có 10 chữ số).';
        }

        if ($address === '') {
            $errors[] = 'Địa chỉ không được để trống.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_msg'] = implode('<br>', $errors);
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/checkout');
            exit();
        }

        $orderData = [
            'customer_name' => $customer_name,
            'phone' => $phone,
            'address' => $address
        ];

        if ($this->orderModel->placeOrder($_SESSION['user_id'], $orderData)) {
            $_SESSION['flash_msg'] = 'Đặt hàng thành công! Chúng tôi sẽ liên hệ sớm.';
            $_SESSION['flash_type'] = 'success';
            header('Location: /btl/orders/history');
            exit();
        }

        $_SESSION['flash_msg'] = 'Không thể đặt hàng. Vui lòng thử lại.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: /btl/checkout');
        exit();
    }
}
?>
