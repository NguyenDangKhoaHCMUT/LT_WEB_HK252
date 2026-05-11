<?php
require_once 'models/Faq.php';

class FaqController
{
    private $faqModel;
    private $faqPerPage = 8;
    private $myQuestionsPerPage = 5;

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

        // Xử lý POST: người dùng gửi câu hỏi mới
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'submit_question') {
                if (!isset($_SESSION['user_id'])) {
                    $_SESSION['flash_msg'] = "Bạn cần đăng nhập để gửi câu hỏi!";
                    $_SESSION['flash_type'] = "warning";
                    $this->redirectToFaqIndex();
                }

                $question_text = trim($_POST['question'] ?? '');
                if (empty($question_text)) {
                    $_SESSION['flash_msg'] = "Nội dung câu hỏi không được để trống!";
                    $_SESSION['flash_type'] = "danger";
                    $this->redirectToFaqIndex();
                }

                if (mb_strlen($question_text) > 1000) {
                    $_SESSION['flash_msg'] = "Câu hỏi không được vượt quá 1000 ký tự!";
                    $_SESSION['flash_type'] = "danger";
                    $this->redirectToFaqIndex();
                }

                $this->faqModel->submitQuestion($_SESSION['user_id'], $question_text);
                $_SESSION['flash_msg'] = "Câu hỏi của bạn đã được gửi. Chúng tôi sẽ phản hồi sớm nhất có thể!";
                $_SESSION['flash_type'] = "success";
                // Show newest question on first page of "my questions"
                $this->redirectToFaqIndex(['my_page' => null], '#faq-my-questions');
            }
        }

        $faq_page = max(1, (int) ($_GET['faq_page'] ?? 1));
        $faq_total = $this->faqModel->countPublishedFaqs();
        $faq_total_pages = max(1, (int) ceil($faq_total / $this->faqPerPage));
        if ($faq_page > $faq_total_pages) {
            $faq_page = $faq_total_pages;
        }
        $faqs = $faq_total > 0
            ? $this->faqModel->getPublishedFaqsPaginated($faq_page, $this->faqPerPage)
            : [];
        $faq_row_start = ($faq_page - 1) * $this->faqPerPage;

        $my_page = 1;
        $my_total = 0;
        $my_total_pages = 1;
        $my_questions = [];
        $my_row_start = 0;

        if (isset($_SESSION['user_id'])) {
            $my_page = max(1, (int) ($_GET['my_page'] ?? 1));
            $my_total = $this->faqModel->countQuestionsByUser($_SESSION['user_id']);
            $my_total_pages = max(1, (int) ceil($my_total / $this->myQuestionsPerPage));
            if ($my_page > $my_total_pages) {
                $my_page = $my_total_pages;
            }
            $my_questions = $my_total > 0
                ? $this->faqModel->getQuestionsByUserPaginated(
                    $_SESSION['user_id'],
                    $my_page,
                    $this->myQuestionsPerPage,
                )
                : [];
            $my_row_start = ($my_page - 1) * $this->myQuestionsPerPage;
        }

        ob_start();
        require_once 'views/public/faq.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    /**
     * @param array<string, int|null> $overrides Query params (use null to omit)
     */
    private function redirectToFaqIndex(array $overrides = [], string $hash = '')
    {
        $fp = max(1, (int) ($_POST['faq_page'] ?? $_GET['faq_page'] ?? 1));
        $mp = max(1, (int) ($_POST['my_page'] ?? $_GET['my_page'] ?? 1));

        if (array_key_exists('faq_page', $overrides)) {
            $fp = $overrides['faq_page'] === null ? 1 : max(1, (int) $overrides['faq_page']);
        }
        if (array_key_exists('my_page', $overrides)) {
            $mp = $overrides['my_page'] === null ? 1 : max(1, (int) $overrides['my_page']);
        }

        $q = http_build_query(
            array_filter(
                [
                    'faq_page' => $fp > 1 ? $fp : null,
                    'my_page' => $mp > 1 ? $mp : null,
                ],
                function ($v) {
                    return $v !== null;
                },
            ),
        );
        header('Location: /btl/faq/index' . ($q !== '' ? '?' . $q : '') . $hash);
        exit();
    }
}
