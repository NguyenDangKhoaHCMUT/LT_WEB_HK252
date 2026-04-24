<?php
require_once 'models/Post.php';
require_once 'models/Comment.php';

class NewsController
{
    private $db;
    private $perPage = 5;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index()
    {
        $postModel = new Post($this->db);
        $keyword = $this->normalizeKeyword($_GET['keyword'] ?? '');
        $category = $this->normalizeSlug($_GET['category'] ?? '');
        $sort = $this->normalizeSort($_GET['sort'] ?? 'latest');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $categories = $postModel->getPublishedCategories();

        if (!$this->categoryExists($categories, $category)) {
            $category = '';
        }

        $selectedCategory = $this->findCategoryBySlug($categories, $category);
        $totalPosts = $postModel->countPublishedPosts($keyword, $category);
        $totalPages = max(1, (int) ceil($totalPosts / $this->perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $posts = $postModel->getPublishedPosts($keyword, $page, $this->perPage, $category, $sort);
        $isSearching = $keyword !== '';
        $hasActiveFilter = $isSearching || $category !== '' || $sort !== 'latest';
        $featuredPost = (!$hasActiveFilter && $page === 1) ? ($posts[0] ?? null) : null;

        if ($isSearching) {
            $title = 'Tìm kiếm bài viết: ' . $keyword . ' | TechStore';
            $metaDescription = 'Kết quả tìm kiếm bài viết và tin tức cho từ khoá "' . $keyword . '" tại TechStore.';
        } else {
            $title = 'Tin tức công nghệ và đánh giá sản phẩm | TechStore';
            $metaDescription = 'Cập nhật tin tức công nghệ, bài đánh giá sản phẩm và kinh nghiệm mua sắm mới nhất từ TechStore.';
        }

        $canonicalUrl = $this->buildNewsUrl('', $this->buildListingQueryParams($keyword, $category, $sort, $page));

        ob_start();
        require_once 'views/public/news.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    public function show($slug = '')
    {
        if ($slug === '') {
            header('Location: /btl/news');
            exit();
        }

        if ($this->shouldRedirectToCanonical($slug)) {
            header('Location: ' . $this->buildNewsUrl($slug), true, 301);
            exit();
        }

        $postModel = new Post($this->db);
        $commentModel = new Comment($this->db);
        $post = $postModel->getPublishedPostBySlug($slug);

        if (!$post) {
            $_SESSION['flash_msg'] = 'Bài viết không tồn tại hoặc chưa được xuất bản.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: /btl/news');
            exit();
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['submit_comment'])) {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['flash_msg'] = 'Bạn cần đăng nhập để gửi bình luận.';
                $_SESSION['flash_type'] = 'warning';
                header('Location: /btl/auth/login');
                exit();
            }

            $content = trim($_POST['content'] ?? '');
            $rating = $_POST['rating'] ?? null;

            if ($content === '') {
                $_SESSION['flash_msg'] = 'Vui lòng nhập nội dung bình luận.';
                $_SESSION['flash_type'] = 'warning';
                header('Location: ' . $this->buildRelativeNewsUrl($slug));
                exit();
            }

            if (mb_strlen(strip_tags($content)) > 1000) {
                $_SESSION['flash_msg'] = 'Nội dung bình luận không được vượt quá 1000 ký tự.';
                $_SESSION['flash_type'] = 'warning';
                header('Location: ' . $this->buildRelativeNewsUrl($slug));
                exit();
            }

            if ($rating !== null && $rating !== '' && (!is_numeric($rating) || (int) $rating < 1 || (int) $rating > 5)) {
                $_SESSION['flash_msg'] = 'Đánh giá sao không hợp lệ.';
                $_SESSION['flash_type'] = 'warning';
                header('Location: ' . $this->buildRelativeNewsUrl($slug));
                exit();
            }

            $saved = $commentModel->create(
                $post['id'],
                $_SESSION['user_id'],
                $content,
                $rating,
                'approved'
            );

            if ($saved) {
                $_SESSION['flash_msg'] = 'Bình luận đã được đăng thành công.';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_msg'] = 'Không thể gửi bình luận. Vui lòng kiểm tra lại nội dung.';
                $_SESSION['flash_type'] = 'danger';
            }

            header('Location: ' . $this->buildRelativeNewsUrl($slug));
            exit();
        }

        if (!isset($_SESSION['viewed_posts'])) {
            $_SESSION['viewed_posts'] = [];
        }

        if (!isset($_SESSION['viewed_posts'][$post['id']])) {
            $viewerIp = $_SERVER['REMOTE_ADDR'] ?? null;
            $postModel->recordView($post['id'], $_SESSION['user_id'] ?? null, $viewerIp);
            $_SESSION['viewed_posts'][$post['id']] = true;
            $post['view_count']++;
        }

        $comments = $commentModel->getApprovedByPost($post['id']);
        $relatedPosts = $postModel->getRelatedPosts($post['id']);
        $title = ($post['seo_title'] ?: $post['title']) . ' | TechStore';
        $metaDescription = $this->buildMetaDescription($post['seo_description'] ?: $post['summary'] ?: $post['title']);
        $canonicalUrl = $this->buildNewsUrl($post['slug']);

        ob_start();
        require_once 'views/public/news_detail.php';
        $content = ob_get_clean();
        require_once 'views/layouts/main.php';
    }

    private function buildMetaDescription($text, $maxLength = 160)
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)));

        if ($text === '') {
            return 'Cập nhật tin tức công nghệ, bài đánh giá sản phẩm và kinh nghiệm mua sắm mới nhất từ TechStore.';
        }

        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $maxLength - 3)) . '...';
    }

    private function shouldRedirectToCanonical($slug)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            return false;
        }

        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $legacyPath = '/btl/news/show/' . rawurlencode($slug);

        return strpos($requestUri, $legacyPath) === 0;
    }

    private function buildNewsUrl($slug = '', $queryParams = [])
    {
        return $this->buildAbsoluteUrl($this->buildRelativeNewsUrl($slug, $queryParams));
    }

    private function buildRelativeNewsUrl($slug = '', $queryParams = [])
    {
        $path = '/btl/news';

        if ($slug !== '') {
            $path .= '/' . rawurlencode($slug);
        }

        if (!empty($queryParams)) {
            $queryString = http_build_query($queryParams);
            if ($queryString !== '') {
                $path .= '?' . $queryString;
            }
        }

        return $path;
    }

    private function buildAbsoluteUrl($path)
    {
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $scheme = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host . $path;
    }

    private function buildListingQueryParams($keyword, $category, $sort, $page)
    {
        $params = [];

        if ($keyword !== '') {
            $params['keyword'] = $keyword;
        }

        if ($category !== '') {
            $params['category'] = $category;
        }

        if ($sort !== 'latest') {
            $params['sort'] = $sort;
        }

        if ((int) $page > 1) {
            $params['page'] = (int) $page;
        }

        return $params;
    }

    private function normalizeKeyword($keyword)
    {
        return trim(preg_replace('/\s+/u', ' ', (string) $keyword));
    }

    private function normalizeSlug($slug)
    {
        return trim((string) $slug);
    }

    private function normalizeSort($sort)
    {
        $sort = trim((string) $sort);
        $allowedSorts = ['latest', 'most_viewed', 'most_commented'];

        return in_array($sort, $allowedSorts, true) ? $sort : 'latest';
    }

    private function categoryExists($categories, $slug)
    {
        if ($slug === '') {
            return true;
        }

        foreach ($categories as $category) {
            if (($category['slug'] ?? '') === $slug) {
                return true;
            }
        }

        return false;
    }

    private function findCategoryBySlug($categories, $slug)
    {
        if ($slug === '') {
            return null;
        }

        foreach ($categories as $category) {
            if (($category['slug'] ?? '') === $slug) {
                return $category;
            }
        }

        return null;
    }
}
