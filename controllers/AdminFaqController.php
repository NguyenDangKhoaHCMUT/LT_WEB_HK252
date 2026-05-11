<?php
require_once 'models/Faq.php';

class AdminFaqController
{
    private $faqModel;
    private $faqPerPage = 10;
    private $userQuestionsPerPage = 10;

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
        $faq_page = max(1, (int) ($_GET['faq_page'] ?? 1));
        $faq_total = $this->faqModel->countAllFaqs();
        $faq_total_pages = max(1, (int) ceil($faq_total / $this->faqPerPage));
        if ($faq_page > $faq_total_pages) {
            $faq_page = $faq_total_pages;
        }
        $faqs = $faq_total > 0
            ? $this->faqModel->getFaqsPaginated($faq_page, $this->faqPerPage)
            : [];
        $faq_row_start = ($faq_page - 1) * $this->faqPerPage;

        $uq_page = max(1, (int) ($_GET['uq_page'] ?? 1));
        $uq_total = $this->faqModel->countUserQuestions(null);
        $uq_total_pages = max(1, (int) ceil($uq_total / $this->userQuestionsPerPage));
        if ($uq_page > $uq_total_pages) {
            $uq_page = $uq_total_pages;
        }
        $user_questions = $uq_total > 0
            ? $this->faqModel->getUserQuestionsPaginated($uq_page, $this->userQuestionsPerPage, null)
            : [];
        $uq_row_start = ($uq_page - 1) * $this->userQuestionsPerPage;

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
            $this->redirectToFaqAdminIndex();
        }

        $question = trim($_POST['question'] ?? '');
        $answer   = trim($_POST['answer'] ?? '');
        $sort_order  = intval($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            $_SESSION['flash_msg'] = "Câu hỏi và câu trả lời không được để trống!";
            $_SESSION['flash_type'] = "danger";
            $this->redirectToFaqAdminIndex();
        }

        $this->faqModel->createFaq($question, $answer, $sort_order, $is_published);
        $_SESSION['flash_msg'] = "Đã thêm câu hỏi FAQ thành công!";
        $_SESSION['flash_type'] = "success";
        $this->redirectToFaqAdminIndex();
    }

    /**
     * Cập nhật FAQ (POST)
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToFaqAdminIndex();
        }

        $id       = intval($_POST['id'] ?? 0);
        $question = trim($_POST['question'] ?? '');
        $answer   = trim($_POST['answer'] ?? '');
        $sort_order  = intval($_POST['sort_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if ($id <= 0 || empty($question) || empty($answer)) {
            $_SESSION['flash_msg'] = "Dữ liệu không hợp lệ!";
            $_SESSION['flash_type'] = "danger";
            $this->redirectToFaqAdminIndex();
        }

        $this->faqModel->updateFaq($id, $question, $answer, $sort_order, $is_published);
        $_SESSION['flash_msg'] = "Đã cập nhật FAQ thành công!";
        $_SESSION['flash_type'] = "success";
        $this->redirectToFaqAdminIndex();
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
        $this->redirectToFaqAdminIndex();
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
            $this->redirectToFaqAdminIndex('#user-questions');
        }

        $id     = intval($_POST['id'] ?? 0);
        $answer = trim($_POST['answer'] ?? '');

        if ($id <= 0 || empty($answer)) {
            $_SESSION['flash_msg'] = "Dữ liệu không hợp lệ!";
            $_SESSION['flash_type'] = "danger";
            $this->redirectToFaqAdminIndex('#user-questions');
        }

        $this->faqModel->answerQuestion($id, $answer);
        $_SESSION['flash_msg'] = "Đã trả lời câu hỏi của người dùng!";
        $_SESSION['flash_type'] = "success";
        $this->redirectToFaqAdminIndex('#user-questions');
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
        $this->redirectToFaqAdminIndex('#user-questions');
    }

    private function redirectToFaqAdminIndex(string $hash = '')
    {
        $fp = max(1, (int) ($_POST['faq_page'] ?? $_GET['faq_page'] ?? 1));
        $up = max(1, (int) ($_POST['uq_page'] ?? $_GET['uq_page'] ?? 1));
        $q = http_build_query(
            array_filter(
                [
                    'faq_page' => $fp > 1 ? $fp : null,
                    'uq_page' => $up > 1 ? $up : null,
                ],
                function ($v) {
                    return $v !== null;
                },
            ),
        );
        header('Location: /btl/adminFaq/index' . ($q !== '' ? '?' . $q : '') . $hash);
        exit();
    }
}
?>
