<?php
require_once 'models/Comment.php';

class AdminCommentController
{
    private $db;
    private $commentModel;
    private $perPage = 10;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['flash_msg'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/");
            exit();
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->commentModel = new Comment($this->db);
    }

    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $status  = trim($_GET['status'] ?? '');
        $page    = max(1, (int) ($_GET['page'] ?? 1));

        $allowedStatuses = ['approved', 'hidden', 'pending'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $totalComments = $this->commentModel->countAdminComments($keyword, $status);
        $totalPages    = max(1, (int) ceil($totalComments / $this->perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $comments = $this->commentModel->getAdminComments($page, $this->perPage, $keyword, $status);
        $perPage  = $this->perPage;

        $title = "Quản lý bình luận";
        ob_start();
        require_once 'views/admin/comments/index.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function toggle_status($id)
    {
        $comment = $this->commentModel->getById($id);
        if (!$comment) {
            $_SESSION['flash_msg']  = "Không tìm thấy bình luận.";
            $_SESSION['flash_type'] = "warning";
            header("Location: /btl/adminComment/index");
            exit();
        }

        $newStatus = $comment['status'] === 'hidden' ? 'approved' : 'hidden';

        if ($this->commentModel->updateStatus($id, $newStatus)) {
            $_SESSION['flash_msg']  = $newStatus === 'hidden' ? "Đã ẩn bình luận." : "Đã hiển thị lại bình luận.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg']  = "Không thể cập nhật trạng thái bình luận.";
            $_SESSION['flash_type'] = "danger";
        }

        header("Location: /btl/adminComment/index?" . $this->buildReturnQuery());
        exit();
    }

    public function delete($id)
    {
        $comment = $this->commentModel->getById($id);
        if (!$comment) {
            $_SESSION['flash_msg']  = "Bình luận không tồn tại hoặc đã bị xóa.";
            $_SESSION['flash_type'] = "warning";
            header("Location: /btl/adminComment/index");
            exit();
        }

        if ($this->commentModel->softDelete($id)) {
            $_SESSION['flash_msg']  = "Đã xóa bình luận.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg']  = "Không thể xóa bình luận.";
            $_SESSION['flash_type'] = "danger";
        }

        header("Location: /btl/adminComment/index?" . $this->buildReturnQuery());
        exit();
    }

    private function buildReturnQuery()
    {
        $params = array_filter([
            'keyword' => trim($_GET['keyword'] ?? ''),
            'status'  => trim($_GET['status'] ?? ''),
            'page'    => (int) ($_GET['page'] ?? 1) > 1 ? (int) ($_GET['page'] ?? 1) : null,
        ]);
        return http_build_query($params);
    }
}
