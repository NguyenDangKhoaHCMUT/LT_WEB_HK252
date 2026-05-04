<?php
class Post
{
    private $conn;
    private $table_name = 'posts';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function countPublishedPosts($keyword = '', $categorySlug = '')
    {
        $keyword = trim($keyword);
        $categorySlug = trim($categorySlug);
        $query =
            "SELECT COUNT(*)
                  FROM " .
            $this->table_name .
            " p
                  WHERE p.status = 'published'
                    AND p.deleted_at IS NULL";

        $query .= $this->buildPublishedKeywordCondition($keyword);
        $query .= $this->buildPublishedCategoryCondition($categorySlug);

        $stmt = $this->conn->prepare($query);
        $this->bindPublishedKeywordParams($stmt, $keyword);
        $this->bindPublishedCategoryParams($stmt, $categorySlug);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getPublishedPosts($keyword = '', $page = 1, $perPage = 5, $categorySlug = '', $sort = 'latest')
    {
        $keyword = trim($keyword);
        $categorySlug = trim($categorySlug);
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $query =
            "SELECT
                    p.*,
                    COALESCE(u.fullname, 'Ban bien tap TechStore') AS author_name,
                    (
                        SELECT COUNT(*)
                        FROM comments c
                        WHERE c.post_id = p.id
                        AND c.status = 'approved'
                        AND c.deleted_at IS NULL
                    ) AS comment_count,
                    (
                        SELECT COUNT(*)
                        FROM post_views pv
                        WHERE pv.post_id = p.id
                          AND pv.deleted_at IS NULL
                    ) AS view_count,
                    (
                        SELECT GROUP_CONCAT(cat.name ORDER BY cat.name SEPARATOR '||')
                        FROM post_categories pc
                        INNER JOIN categories cat ON cat.id = pc.category_id
                        WHERE pc.post_id = p.id
                        AND pc.deleted_at IS NULL
                        AND cat.deleted_at IS NULL
                    ) AS category_names
                FROM " .
            $this->table_name .
            " p
                LEFT JOIN users u ON u.id = p.author_id
                WHERE p.status = 'published'
                    AND p.deleted_at IS NULL";

        $query .= $this->buildPublishedKeywordCondition($keyword);
        $query .= $this->buildPublishedCategoryCondition($categorySlug);

        $query .= "
                ORDER BY " . $this->buildPublishedOrderClause($sort) . "
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $this->bindPublishedKeywordParams($stmt, $keyword);
        $this->bindPublishedCategoryParams($stmt, $categorySlug);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = $this->mapPost($row);
        }

        return $posts;
    }

    public function getPublishedCategories()
    {
        $query = "SELECT
                    cat.name,
                    cat.slug,
                    COUNT(DISTINCT p.id) AS post_count
                FROM categories cat
                INNER JOIN post_categories pc ON pc.category_id = cat.id
                    AND pc.deleted_at IS NULL
                INNER JOIN posts p ON p.id = pc.post_id
                    AND p.status = 'published'
                    AND p.deleted_at IS NULL
                WHERE cat.deleted_at IS NULL
                GROUP BY cat.id, cat.name, cat.slug
                ORDER BY post_count DESC, cat.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublishedPostBySlug($slug)
    {
        $query =
            "SELECT
                    p.*,
                    COALESCE(u.fullname, 'Ban bien tap TechStore') AS author_name,
                    (
                        SELECT COUNT(*)
                        FROM comments c
                        WHERE c.post_id = p.id
                        AND c.status = 'approved'
                        AND c.deleted_at IS NULL
                    ) AS comment_count,
                    (
                        SELECT COUNT(*)
                        FROM post_views pv
                        WHERE pv.post_id = p.id
                        AND pv.deleted_at IS NULL
                    ) AS view_count,
                    (
                        SELECT GROUP_CONCAT(cat.name ORDER BY cat.name SEPARATOR '||')
                        FROM post_categories pc
                        INNER JOIN categories cat ON cat.id = pc.category_id
                        WHERE pc.post_id = p.id
                        AND pc.deleted_at IS NULL
                        AND cat.deleted_at IS NULL
                    ) AS category_names
                FROM " .
            $this->table_name .
            " p
                LEFT JOIN users u ON u.id = p.author_id
                WHERE p.slug = :slug
                    AND p.status = 'published'
                    AND p.deleted_at IS NULL
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapPost($row) : null;
    }

    public function getRelatedPosts($postId, $limit = 3)
    {
        $limit = max(1, (int) $limit);

        $query =
            "SELECT
                    p.id,
                    p.title,
                    p.slug,
                    p.summary,
                    p.thumbnail,
                    p.created_at
                FROM " .
            $this->table_name .
            " p
                WHERE p.status = 'published'
                    AND p.deleted_at IS NULL
                    AND p.id != :post_id
                ORDER BY p.created_at DESC
                LIMIT " .
            $limit;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recordView($postId, $userId = null, $viewerIp = null)
    {
        $query = "INSERT INTO post_views
                    SET post_id = :post_id,
                        user_id = :user_id,
                        viewer_ip = :viewer_ip";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':post_id', (int) $postId, PDO::PARAM_INT);
        $stmt->bindValue(
            ':user_id',
            $userId ? (int) $userId : null,
            $userId ? PDO::PARAM_INT : PDO::PARAM_NULL,
        );
        $stmt->bindValue(
            ':viewer_ip',
            $viewerIp ?: null,
            $viewerIp ? PDO::PARAM_STR : PDO::PARAM_NULL,
        );

        return $stmt->execute();
    }

    public function countAdminPosts($keyword = '')
    {
        $keyword = trim($keyword);
        $query =
            "SELECT COUNT(*)
                FROM " .
            $this->table_name .
            "
                WHERE deleted_at IS NULL";

        if ($keyword !== '') {
            $query .= " AND (
                            title LIKE :keyword
                            OR slug LIKE :keyword
                            OR summary LIKE :keyword
                        )";
        }

        $stmt = $this->conn->prepare($query);
        if ($keyword !== '') {
            $searchValue = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $searchValue, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAdminPosts($keyword = '', $page = 1, $perPage = 5)
    {
        $keyword = trim($keyword);
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $query =
            "SELECT
                    p.*,
                    COALESCE(u.fullname, 'Chua gan tac gia') AS author_name
                FROM " .
            $this->table_name .
            " p
                LEFT JOIN users u ON u.id = p.author_id
                WHERE p.deleted_at IS NULL";

        if ($keyword !== '') {
            $query .= " AND (
                            p.title LIKE :keyword
                            OR p.slug LIKE :keyword
                            OR p.summary LIKE :keyword
                        )";
        }

        $query .= " ORDER BY p.created_at DESC
                    LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        if ($keyword !== '') {
            $searchValue = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $searchValue, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($id)
    {
        $query =
            "SELECT *
                FROM " .
            $this->table_name .
            "
                WHERE id = :id
                    AND deleted_at IS NULL
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function slugExists($slug, $excludeId = null)
    {
        $query =
            "SELECT id
                FROM " .
            $this->table_name .
            "
                WHERE slug = :slug
                    AND deleted_at IS NULL";

        if ($excludeId !== null) {
            $query .= ' AND id != :exclude_id';
        }

        $query .= ' LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);

        if ($excludeId !== null) {
            $stmt->bindValue(':exclude_id', (int) $excludeId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function create($data)
    {
        $query =
            'INSERT INTO ' .
            $this->table_name .
            "
                    SET title = :title,
                        slug = :slug,
                        summary = :summary,
                        content = :content,
                        thumbnail = :thumbnail,
                        seo_title = :seo_title,
                        seo_description = :seo_description,
                        seo_keywords = :seo_keywords,
                        status = :status,
                        author_id = :author_id";

        $stmt = $this->conn->prepare($query);
        if ($this->bindPostData($stmt, $data) && $stmt->execute()) {
            return (int) $this->conn->lastInsertId();
        }
        return 0;
    }

    public function update($id, $data)
    {
        $query =
            'UPDATE ' .
            $this->table_name .
            "
                SET title = :title,
                slug = :slug,
                summary = :summary,
                content = :content,
                thumbnail = :thumbnail,
                seo_title = :seo_title,
                seo_description = :seo_description,
                seo_keywords = :seo_keywords,
                status = :status,
                author_id = :author_id,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
                AND deleted_at IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);

        return $this->bindPostData($stmt, $data) && $stmt->execute();
    }

    public function softDelete($id)
    {
        $query =
            'UPDATE ' .
            $this->table_name .
            "
                SET deleted_at = CURRENT_TIMESTAMP
                WHERE id = :id
                AND deleted_at IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function bindPostData($stmt, $data)
    {
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':slug', $data['slug'], PDO::PARAM_STR);
        $stmt->bindValue(':summary', $data['summary'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data['content'], PDO::PARAM_STR);
        $stmt->bindValue(
            ':thumbnail',
            $data['thumbnail'] ?: null,
            $data['thumbnail'] ? PDO::PARAM_STR : PDO::PARAM_NULL,
        );
        $stmt->bindValue(
            ':seo_title',
            $data['seo_title'] ?: null,
            $data['seo_title'] ? PDO::PARAM_STR : PDO::PARAM_NULL,
        );
        $stmt->bindValue(
            ':seo_description',
            $data['seo_description'] ?: null,
            $data['seo_description'] ? PDO::PARAM_STR : PDO::PARAM_NULL,
        );
        $stmt->bindValue(
            ':seo_keywords',
            $data['seo_keywords'] ?: null,
            $data['seo_keywords'] ? PDO::PARAM_STR : PDO::PARAM_NULL,
        );
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindValue(
            ':author_id',
            !empty($data['author_id']) ? (int) $data['author_id'] : null,
            !empty($data['author_id']) ? PDO::PARAM_INT : PDO::PARAM_NULL,
        );

        return true;
    }

    private function mapPost($row)
    {
        $row['categories'] = [];
        if (!empty($row['category_names'])) {
            $row['categories'] = explode('||', $row['category_names']);
        }

        unset($row['category_names']);
        return $row;
    }

    private function buildPublishedKeywordCondition($keyword)
    {
        if ($keyword === '') {
            return '';
        }

        return " AND (
                    p.title LIKE :keyword_title
                    OR p.slug LIKE :keyword_slug
                    OR p.summary LIKE :keyword_summary
                    OR p.content LIKE :keyword_content
                    OR p.seo_keywords LIKE :keyword_seo
                    OR EXISTS (
                        SELECT 1
                        FROM post_categories pc
                        INNER JOIN categories cat ON cat.id = pc.category_id
                        WHERE pc.post_id = p.id
                            AND pc.deleted_at IS NULL
                            AND cat.deleted_at IS NULL
                            AND cat.name LIKE :keyword_category
                    )
                )";
    }

    private function buildPublishedCategoryCondition($categorySlug)
    {
        if ($categorySlug === '') {
            return '';
        }

        return " AND EXISTS (
                    SELECT 1
                    FROM post_categories pc_filter
                    INNER JOIN categories cat_filter ON cat_filter.id = pc_filter.category_id
                    WHERE pc_filter.post_id = p.id
                        AND pc_filter.deleted_at IS NULL
                        AND cat_filter.deleted_at IS NULL
                        AND cat_filter.slug = :category_slug
                )";
    }

    private function bindPublishedKeywordParams($stmt, $keyword)
    {
        if ($keyword === '') {
            return;
        }

        $searchValue = '%' . $keyword . '%';
        $stmt->bindValue(':keyword_title', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_slug', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_summary', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_content', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_seo', $searchValue, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_category', $searchValue, PDO::PARAM_STR);
    }

    private function bindPublishedCategoryParams($stmt, $categorySlug)
    {
        if ($categorySlug === '') {
            return;
        }

        $stmt->bindValue(':category_slug', $categorySlug, PDO::PARAM_STR);
    }

    public function getCategoryIdsForPost($postId)
    {
        $stmt = $this->conn->prepare(
            "SELECT category_id
             FROM post_categories
             WHERE post_id = :post_id
               AND deleted_at IS NULL"
        );
        $stmt->bindValue(':post_id', (int) $postId, PDO::PARAM_INT);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'category_id');
    }

    public function syncCategories($postId, array $categoryIds)
    {
        $postId = (int) $postId;
        $del = $this->conn->prepare(
            "DELETE FROM post_categories WHERE post_id = :post_id"
        );
        $del->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $del->execute();

        if (empty($categoryIds)) {
            return true;
        }

        $ins = $this->conn->prepare(
            "INSERT INTO post_categories (post_id, category_id) VALUES (:post_id, :category_id)"
        );
        foreach ($categoryIds as $catId) {
            $catId = (int) $catId;
            if ($catId <= 0) continue;
            $ins->bindValue(':post_id', $postId, PDO::PARAM_INT);
            $ins->bindValue(':category_id', $catId, PDO::PARAM_INT);
            $ins->execute();
        }
        return true;
    }

    private function buildPublishedOrderClause($sort)
    {
        switch ($sort) {
            case 'most_viewed':
                return 'view_count DESC, p.created_at DESC';
            case 'most_commented':
                return 'comment_count DESC, p.created_at DESC';
            case 'latest':
            default:
                return 'p.created_at DESC';
        }
    }
}
