<?php
class Comment
{
    private $conn;
    private $table_name = "comments";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getApprovedByPost($postId)
    {
        $query = "SELECT
                    c.*,
                    COALESCE(u.fullname, 'Khach') AS author_name,
                    u.avatar
                  FROM " . $this->table_name . " c
                  LEFT JOIN users u ON u.id = c.user_id
                  WHERE c.post_id = :post_id
                    AND c.status = 'approved'
                    AND c.deleted_at IS NULL
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':post_id', (int) $postId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($postId, $userId, $content, $rating = null, $status = 'approved')
    {
        $content = trim(strip_tags($content));
        if ($content === '' || mb_strlen($content) > 1000) {
            return false;
        }

        if ($rating !== null && $rating !== '') {
            $rating = (int) $rating;
            if ($rating < 1 || $rating > 5) {
                $rating = null;
            }
        } else {
            $rating = null;
        }

        $query = "INSERT INTO " . $this->table_name . "
                    SET post_id = :post_id,
                        user_id = :user_id,
                        content = :content,
                        rating = :rating,
                        status = :status";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':post_id', (int) $postId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', (int) $userId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':rating', $rating, $rating !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function countAdminComments($keyword = '', $status = '')
    {
        $keyword = trim($keyword);
        $status  = trim($status);

        $query = "SELECT COUNT(*)
                  FROM " . $this->table_name . " c
                  INNER JOIN posts p ON p.id = c.post_id
                  LEFT JOIN users u ON u.id = c.user_id
                  WHERE c.deleted_at IS NULL
                    AND p.deleted_at IS NULL";

        if ($keyword !== '') {
            $query .= " AND (
                            c.content LIKE :keyword
                            OR p.title LIKE :keyword
                            OR COALESCE(u.fullname, 'Khach') LIKE :keyword
                        )";
        }
        if ($status !== '') {
            $query .= " AND c.status = :status";
        }

        $stmt = $this->conn->prepare($query);
        if ($keyword !== '') {
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        }
        if ($status !== '') {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getAdminComments($page = 1, $perPage = 10, $keyword = '', $status = '')
    {
        $keyword = trim($keyword);
        $status  = trim($status);
        $page    = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset  = ($page - 1) * $perPage;

        $query = "SELECT
                    c.*,
                    p.title AS post_title,
                    p.slug AS post_slug,
                    COALESCE(u.fullname, 'Khach') AS author_name
                  FROM " . $this->table_name . " c
                  INNER JOIN posts p ON p.id = c.post_id
                  LEFT JOIN users u ON u.id = c.user_id
                  WHERE c.deleted_at IS NULL
                    AND p.deleted_at IS NULL";

        if ($keyword !== '') {
            $query .= " AND (
                            c.content LIKE :keyword
                            OR p.title LIKE :keyword
                            OR COALESCE(u.fullname, 'Khach') LIKE :keyword
                        )";
        }
        if ($status !== '') {
            $query .= " AND c.status = :status";
        }

        $query .= " ORDER BY c.created_at DESC
                   LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        if ($keyword !== '') {
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        }
        if ($status !== '') {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE id = :id
                    AND deleted_at IS NULL
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id
                    AND deleted_at IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function softDelete($id)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET deleted_at = CURRENT_TIMESTAMP
                  WHERE id = :id
                    AND deleted_at IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
