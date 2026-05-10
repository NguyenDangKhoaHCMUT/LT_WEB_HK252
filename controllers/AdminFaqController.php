<?php
require_once 'models/Faq.php';

class AdminFaqController
{
    private $faqModel;

    public function __construct()
    {
        // Chỉ cho phép admin truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['flash_msg'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/");
            exit();
        }
        $this->faqModel = new Faq();
    }

    /**
     * Trang chính - Quản lý FAQ và câu hỏi từ người dùng
     */
    public function index()
    {
        $faqs = $this->faqModel->getAllFaqs();
        $user_questions = $this->faqModel->getAllUserQuestions();
        $pending_count = $this->faqModel->countPendingQuestions();

        $title = "Quản lý Hỏi/Đáp";
        ob_start();
        require_once 'views/admin/faq/index.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    /**
     * Thêm FAQ mới (AJAX POST)
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /btl/adminFaq/index");
            exit();
        }

        $question = trim($_POST['question'] ?? '');
        $answer   = trim($_POST['answer'] ?? '');
        $sort_order  = intval($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            $_SESSION['flash_msg'] = "Câu hỏi và câu trả lời không được để trống!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminFaq/index");
            exit();
        }

        $this->faqModel->createFaq($question, $answer, $sort_order, $is_published);
        $_SESSION['flash_msg'] = "Đã thêm câu hỏi FAQ thành công!";
        $_SESSION['flash_type'] = "success";
        header("Location: /btl/adminFaq/index");
        exit();
    }

    /**
     * Cập nhật FAQ (POST)
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /btl/adminFaq/index");
            exit();
        }

        $id       = intval($_POST['id'] ?? 0);
        $question = trim($_POST['question'] ?? '');
        $answer   = trim($_POST['answer'] ?? '');
        $sort_order  = intval($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if ($id <= 0 || empty($question) || empty($answer)) {
            $_SESSION['flash_msg'] = "Dữ liệu không hợp lệ!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminFaq/index");
            exit();
        }

        $this->faqModel->updateFaq($id, $question, $answer, $sort_order, $is_published);
        $_SESSION['flash_msg'] = "Đã cập nhật FAQ thành công!";
        $_SESSION['flash_type'] = "success";
        header("Location: /btl/adminFaq/index");
        exit();
    }

    /**
     * Xoá FAQ
     */
    public function delete()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->faqModel->deleteFaq($id);
            $_SESSION['flash_msg'] = "Đã xoá câu hỏi FAQ!";
            $_SESSION['flash_type'] = "success";
        }
        header("Location: /btl/adminFaq/index");
        exit();
    }

    /**
     * Lấy dữ liệu một FAQ để chỉnh sửa (AJAX GET)
     */
    public function get()
    {
        header('Content-Type: application/json');
        $id = intval($_GET['id'] ?? 0);
        $faq = $this->faqModel->getFaqById($id);
        if ($faq) {
            echo json_encode(['success' => true, 'data' => $faq]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy FAQ']);
        }
        exit();
    }

    /**
     * Admin trả lời câu hỏi từ người dùng (POST)
     */
    public function answer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /btl/adminFaq/index");
            exit();
        }

        $id     = intval($_POST['id'] ?? 0);
        $answer = trim($_POST['answer'] ?? '');

        if ($id <= 0 || empty($answer)) {
            $_SESSION['flash_msg'] = "Dữ liệu không hợp lệ!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminFaq/index");
            exit();
        }

        $this->faqModel->answerQuestion($id, $answer);
        $_SESSION['flash_msg'] = "Đã trả lời câu hỏi của người dùng!";
        $_SESSION['flash_type'] = "success";
        header("Location: /btl/adminFaq/index#user-questions");
        exit();
    }

    /**
     * Xem chi tiết câu hỏi (FAQ hoặc câu hỏi từ người dùng)
     */
    public function detail()
    {
        $id = intval($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? 'faq'; // 'faq' hoặc 'user'

        if ($type === 'user') {
            $data = $this->faqModel->getQuestionById($id);
            $title = "Chi tiết câu hỏi từ người dùng";
        } else {
            $data = $this->faqModel->getFaqById($id);
            $title = "Chi tiết FAQ";
        }

        if (!$data) {
            $_SESSION['flash_msg'] = "Không tìm thấy nội dung yêu cầu!";
            $_SESSION['flash_type'] = "danger";
            header("Location: /btl/adminFaq/index");
            exit();
        }

        ob_start();
        require_once 'views/admin/faq/detail.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    /**
     * Xoá câu hỏi của người dùng
     */
    public function deleteQuestion()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->faqModel->deleteQuestion($id);
            $_SESSION['flash_msg'] = "Đã xoá câu hỏi!";
            $_SESSION['flash_type'] = "success";
        }
        header("Location: /btl/adminFaq/index#user-questions");
        exit();
    }
}
?>
