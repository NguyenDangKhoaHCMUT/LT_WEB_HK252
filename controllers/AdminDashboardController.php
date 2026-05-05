<?php
require_once 'models/User.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/Contact.php';
require_once 'middleware/AdminMiddleware.php';

class AdminDashboardController
{
    private $userModel;
    private $productModel;
    private $orderModel;
    private $contactModel;

    public function __construct()
    {
        // Kiểm tra quyền admin
        AdminMiddleware::check();
        
        $database = new Database(); $db = $database->getConnection(); $this->userModel = new User($db);
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
        $this->contactModel = new Contact();
    }

    /**
     * Trang chủ admin - Dashboard
     */
    public function index()
    {
        // Lấy thông tin admin hiện tại
        $admin = AdminMiddleware::getAdminUser();

        // Lấy thống kê
        $stats = [
            'total_users' => $this->userModel->countUsers() ?? 0,
            'total_products' => $this->productModel->countProducts() ?? 0,
            'total_orders' => $this->orderModel->countOrders() ?? 0,
            'total_contacts' => $this->contactModel->countContacts() ?? 0,
            'pending_orders' => $this->orderModel->countOrders('', 'pending') ?? 0,
            'new_contacts' => $this->contactModel->countContacts('new') ?? 0,
        ];

        // Lấy dữ liệu mẫu để hiển thị
        $recent_orders = $this->orderModel->getAllOrders('', '', 5, 0);
        $recent_contacts = $this->contactModel->getAllContacts('new', 5, 0);

        $title = "Admin Dashboard";
        $pageTitle = "Dashboard";
        
        ob_start();
        require_once 'views/admin/index.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    /**
     * Trang placeholder cho Quản lý Sản phẩm
     */
    public function products()
    {
        header('Location: /btl/adminProduct/index');
        exit();
    }

    /**
     * Trang placeholder cho Quản lý Đơn hàng
     */
    public function orders()
    {
        $title = "Quản lý Đơn hàng";
        $pageTitle = "Đơn hàng";
        
        ob_start();
        require_once 'views/admin/orders.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    /**
     * Trang placeholder cho Quản lý Người dùng
     */
    public function users()
    {
        $title = "Quản lý Người dùng";
        $pageTitle = "Người dùng";
        
        ob_start();
        require_once 'views/admin/users.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    /**
     * Trang Quản lý Liên hệ
     */
    public function contacts()
    {
        $title = "Quản lý Liên hệ";
        $pageTitle = "Liên hệ";
        
        // Lấy danh sách liên hệ
        $contacts = $this->contactModel->getAllContacts();
        
        ob_start();
        require_once 'views/admin/contacts.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    /**
     * Trang placeholder cho Quản lý Cài Đặt
     */
    public function settings()
    {
        $title = "Cài đặt";
        $pageTitle = "Cài đặt";
        
        ob_start();
        require_once 'views/admin/settings.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }
}
?>
