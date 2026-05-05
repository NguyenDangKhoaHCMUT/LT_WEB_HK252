<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'middleware/AdminMiddleware.php';

class AdminOrderController
{
    private $db;
    private $orderModel;

    public function __construct()
    {
        AdminMiddleware::check();

        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
    }

    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getAllOrders($keyword, $status, $limit, $offset);
        $totalOrders = $this->orderModel->countOrders($keyword, $status);
        $totalPages = max(1, (int) ceil($totalOrders / $limit));

        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $orders = $this->orderModel->getAllOrders($keyword, $status, $limit, $offset);
        }

        $statusLabels = $this->orderModel->getStatusLabels();

        $title = 'Quản lý Đơn hàng';
        $pageTitle = 'Đơn hàng';

        ob_start();
        require_once 'views/admin/orders_list.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function view($id)
    {
        $id = (int) $id;
        $order = $this->orderModel->getOrderById($id);

        if (!$order) {
            $_SESSION['flash_msg'] = 'Không tìm thấy đơn hàng.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/adminOrder/index');
            exit();
        }

        $statusLabels = $this->orderModel->getStatusLabels();

        $title = 'Chi tiết Đơn hàng';
        $pageTitle = 'Đơn hàng';

        ob_start();
        require_once 'views/admin/order_view.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/adminOrder/index');
            exit();
        }

        $id = (int) $id;
        $newStatus = trim($_POST['status'] ?? '');

        if ($this->orderModel->updateStatus($id, $newStatus)) {
            $_SESSION['flash_msg'] = 'Cập nhật trạng thái đơn hàng thành công.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_msg'] = 'Không thể cập nhật trạng thái. Vui lòng thử lại.';
            $_SESSION['flash_type'] = 'danger';
        }

        header('Location: /btl/adminOrder/view/' . $id);
        exit();
    }
}
?>
