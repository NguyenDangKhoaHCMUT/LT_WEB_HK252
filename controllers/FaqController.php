<?php
require_once 'models/Faq.php';

class FaqController
{
    private $faqModel;

    public function __construct()
    {
        $this->faqModel = new Faq();
    }

    /**
     * Trang Hỏi/Đáp công khai
     */
    public function index()
    {
        $title = "Hỏi / Đáp";

        // Lấy danh sách FAQ đã published
        $faqs = $this->faqModel->getAllFaqs(true);

        // Lấy câu hỏi của user hiện tại (nếu đã đăng nhập)
        $my_questions = [];
        if (isset($_SESSION['user_id'])) {
            $my_questions = $this->faqModel->getQuestionsByUser($_SESSION['user_id']);
        }

        // Xử lý POST: người dùng gửi câu hỏi mới
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'submit_question') {
                if (!isset($_SESSION['user_id'])) {
                    $_SESSION['flash_msg'] = "Bạn cần đăng nhập để gửi câu hỏi!";
                    $_SESSION['flash_type'] = "warning";
                    header("Location: /btl/faq/index");
                    exit();
                }

                $question_text = trim($_POST['question'] ?? '');
                if (empty($question_text)) {
                    $_SESSION['flash_msg'] = "Nội dung câu hỏi không được để trống!";
                    $_SESSION['flash_type'] = "danger";
                    header("Location: /btl/faq/index");
                    exit();
                }

                if (mb_strlen($question_text) > 1000) {
                    $_SESSION['flash_msg'] = "Câu hỏi không được vượt quá 1000 ký tự!";
                    $_SESSION['flash_type'] = "danger";
                    header("Location: /btl/faq/index");
                    exit();
                }

                $this->faqModel->submitQuestion($_SESSION['user_id'], $question_text);
                $_SESSION['flash_msg'] = "Câu hỏi của bạn đã được gửi. Chúng tôi sẽ phản hồi sớm nhất có thể!";
                $_SESSION['flash_type'] = "success";
                header("Location: /btl/faq/index");
                exit();
            }
        }

        ob_start();
        require_once 'views/public/faq.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }
}
?>
