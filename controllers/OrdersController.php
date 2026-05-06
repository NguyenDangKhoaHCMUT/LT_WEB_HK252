<?php
require_once 'config/database.php';
require_once 'models/Order.php';

class OrdersController
{
    private $db;
    private $orderModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_msg'] = 'Vui lòng đăng nhập để xem đơn hàng.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/auth/login');
            exit();
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
    }

    public function history()
    {
        $user_id = (int) $_SESSION['user_id'];
        $orders = $this->orderModel->getOrderHistory($user_id);
        $statusLabels = $this->orderModel->getStatusLabels();

        $title = 'Đơn hàng của tôi';
        ob_start();
        require_once 'views/public/orders_history.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function view($id)
    {
        $id = (int) $id;
        $order = $this->orderModel->getOrderById($id);
        if (!$order || (int)$order['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['flash_msg'] = 'Không tìm thấy đơn hàng.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/orders/history');
            exit();
        }

        $statusLabels = $this->orderModel->getStatusLabels();
        $title = 'Chi tiết đơn hàng';

        ob_start();
        require_once 'views/public/order_view.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}

?>
