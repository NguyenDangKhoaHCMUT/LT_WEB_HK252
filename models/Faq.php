<?php
require_once 'config/Database.php';

class Faq
{
    private $conn;
    private $table_faqs = "faqs";
    private $table_questions = "user_questions";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ========== FAQ (Admin quản lý) ==========

    /**
     * Lấy tất cả FAQ (có thể lọc theo published)
     */
    public function getAllFaqs($only_published = false)
    {
        $query = "SELECT * FROM " . $this->table_faqs;
        if ($only_published) {
            $query .= " WHERE is_published = 1";
        }
        $query .= " ORDER BY sort_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute()) return [];
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countPublishedFaqs()
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_faqs . " WHERE is_published = 1";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute()) {
            return 0;
        }
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    /**
     * Published FAQs for public listing (paginated)
     */
    public function getPublishedFaqsPaginated($page = 1, $perPage = 8)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $query = "SELECT * FROM " . $this->table_faqs . "
                  WHERE is_published = 1
                  ORDER BY sort_order ASC, id ASC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return [];
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countAllFaqs()
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_faqs;
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute()) {
            return 0;
        }
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    /**
     * All FAQs for admin (paginated)
     */
    public function getFaqsPaginated($page = 1, $perPage = 10)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $query = "SELECT * FROM " . $this->table_faqs . "
                  ORDER BY sort_order ASC, id ASC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return [];
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Lấy một FAQ theo ID
     */
    public function getFaqById($id)
    {
        $query = "SELECT * FROM " . $this->table_faqs . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm mới FAQ
     */
    public function createFaq($question, $answer, $sort_order = 0, $is_published = 1)
    {
        $query = "INSERT INTO " . $this->table_faqs . " (question, answer, sort_order, is_published) VALUES (:question, :answer, :sort_order, :is_published)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':sort_order', $sort_order, PDO::PARAM_INT);
        $stmt->bindParam(':is_published', $is_published, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Cập nhật FAQ
     */
    public function updateFaq($id, $question, $answer, $sort_order = 0, $is_published = 1)
    {
        $query = "UPDATE " . $this->table_faqs . " SET question = :question, answer = :answer, sort_order = :sort_order, is_published = :is_published WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':sort_order', $sort_order, PDO::PARAM_INT);
        $stmt->bindParam(':is_published', $is_published, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Xoá FAQ
     */
    public function deleteFaq($id)
    {
        $query = "DELETE FROM " . $this->table_faqs . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ========== User Questions (Câu hỏi từ người dùng) ==========

    /**
     * Lấy tất cả câu hỏi từ người dùng
     */
    public function getAllUserQuestions($status = null)
    {
        $query = "SELECT uq.*, u.fullname, u.email, u.avatar 
                  FROM " . $this->table_questions . " uq
                  LEFT JOIN users u ON uq.user_id = u.id";
        if ($status !== null) {
            $query .= " WHERE uq.status = :status";
        }
        $query .= " ORDER BY uq.created_at DESC";
        $stmt = $this->conn->prepare($query);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        if (!$stmt->execute()) return [];
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countUserQuestions($status = null)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_questions . " uq";
        if ($status !== null) {
            $query .= " WHERE uq.status = :status";
        }
        $stmt = $this->conn->prepare($query);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        if (!$stmt->execute()) {
            return 0;
        }
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    /**
     * User questions for admin (paginated)
     */
    public function getUserQuestionsPaginated($page = 1, $perPage = 10, $status = null)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $query = "SELECT uq.*, u.fullname, u.email, u.avatar 
                  FROM " . $this->table_questions . " uq
                  LEFT JOIN users u ON uq.user_id = u.id";
        if ($status !== null) {
            $query .= " WHERE uq.status = :status";
        }
        $query .= " ORDER BY uq.created_at DESC
                    LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return [];
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Lấy câu hỏi theo ID
     */
    public function getQuestionById($id)
    {
        $query = "SELECT uq.*, u.fullname, u.email, u.avatar 
                  FROM " . $this->table_questions . " uq
                  LEFT JOIN users u ON uq.user_id = u.id
                  WHERE uq.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy câu hỏi của một user
     */
    public function getQuestionsByUser($user_id)
    {
        $query = "SELECT * FROM " . $this->table_questions . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countQuestionsByUser($user_id)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_questions . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', (int) $user_id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return 0;
        }
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function getQuestionsByUserPaginated($user_id, $page = 1, $perPage = 5)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $query = "SELECT * FROM " . $this->table_questions . "
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', (int) $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return [];
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Người dùng gửi câu hỏi
     */
    public function submitQuestion($user_id, $question_text)
    {
        $query = "INSERT INTO " . $this->table_questions . " (user_id, question, status) VALUES (:user_id, :question, 'pending')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':question', $question_text);
        return $stmt->execute();
    }

    /**
     * Admin trả lời câu hỏi
     */
    public function answerQuestion($id, $answer)
    {
        $query = "UPDATE " . $this->table_questions . " SET answer = :answer, status = 'answered', answered_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':answer', $answer);
        return $stmt->execute();
    }

    /**
     * Xoá câu hỏi của người dùng
     */
    public function deleteQuestion($id)
    {
        $query = "DELETE FROM " . $this->table_questions . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Đếm số câu hỏi chưa trả lời
     */
    public function countPendingQuestions()
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_questions . " WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute()) return 0;
        return (int)($stmt->fetchColumn() ?: 0);
    }
}
?>
