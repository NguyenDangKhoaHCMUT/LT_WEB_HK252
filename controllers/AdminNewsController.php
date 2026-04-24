<?php
require_once 'models/Post.php';

class AdminNewsController
{
    private $db;
    private $postModel;
    private $perPage = 5;

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
        $this->postModel = new Post($this->db);
    }

    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $totalPosts = $this->postModel->countAdminPosts($keyword);
        $totalPages = max(1, (int) ceil($totalPosts / $this->perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $posts = $this->postModel->getAdminPosts($keyword, $page, $this->perPage);

        $title = "Quản lý bài viết";
        ob_start();
        require_once 'views/admin/news/index.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function create()
    {
        $post = $this->getEmptyPostData();
        $errors = [];
        $isEdit = false;

        $title = "Thêm bài viết";
        ob_start();
        require_once 'views/admin/news/form.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /btl/adminNews/create");
            exit();
        }

        $post = $this->collectPostInput();
        $errors = $this->validatePostData($post, null);
        $hasUploadedThumbnail = !empty($_FILES['thumbnail']['name'] ?? '');
        $thumbnailUrl = trim($_POST['thumbnail_url'] ?? '');

        if ($hasUploadedThumbnail && $thumbnailUrl !== '') {
            $errors['thumbnail'] = "Chỉ chọn một nguồn ảnh: tải file hoặc nhập URL.";
        }

        if (empty($errors['thumbnail']) && $hasUploadedThumbnail) {
            $uploadResult = $this->uploadThumbnail($_FILES['thumbnail']);
            if (!empty($uploadResult['error'])) {
                $errors['thumbnail'] = $uploadResult['error'];
            } else {
                $post['thumbnail'] = $uploadResult['path'];
            }
        } elseif (empty($errors['thumbnail'])) {
            $this->applyThumbnailFromForm($post, $errors);
        }

        if (!empty($errors)) {
            $isEdit = false;
            $title = "Thêm bài viết";
            ob_start();
            require_once 'views/admin/news/form.php';
            $content = ob_get_clean();
            require_once 'views/layouts/admin.php';
            return;
        }

        $post['author_id'] = $_SESSION['user_id'];

        if ($this->postModel->create($post)) {
            $_SESSION['flash_msg'] = "Thêm bài viết thành công.";
            $_SESSION['flash_type'] = "success";
            header("Location: /btl/adminNews/index");
            exit();
        }

        $this->deleteLocalThumbnail($post['thumbnail']);
        $errors['general'] = "Không thể lưu bài viết vào cơ sở dữ liệu.";
        $isEdit = false;
        $title = "Thêm bài viết";
        ob_start();
        require_once 'views/admin/news/form.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function edit($id)
    {
        $post = $this->postModel->getPostById($id);
        if (!$post) {
            $_SESSION['flash_msg'] = "Không tìm thấy bài viết.";
            $_SESSION['flash_type'] = "warning";
            header("Location: /btl/adminNews/index");
            exit();
        }

        $errors = [];
        $isEdit = true;
        $title = "Sửa bài viết";
        ob_start();
        require_once 'views/admin/news/form.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /btl/adminNews/index");
            exit();
        }

        $existingPost = $this->postModel->getPostById($id);
        if (!$existingPost) {
            $_SESSION['flash_msg'] = "Không tìm thấy bài viết cần cập nhật.";
            $_SESSION['flash_type'] = "warning";
            header("Location: /btl/adminNews/index");
            exit();
        }

        $post = $this->collectPostInput();
        $post['thumbnail'] = $existingPost['thumbnail'];

        $errors = $this->validatePostData($post, $id);
        $hasUploadedThumbnail = !empty($_FILES['thumbnail']['name'] ?? '');
        $thumbnailUrl = trim($_POST['thumbnail_url'] ?? '');

        if ($hasUploadedThumbnail && $thumbnailUrl !== '') {
            $errors['thumbnail'] = "Chỉ chọn một nguồn ảnh: tải file hoặc nhập URL.";
        }

        if (empty($errors['thumbnail']) && $hasUploadedThumbnail) {
            $uploadResult = $this->uploadThumbnail($_FILES['thumbnail']);
            if (!empty($uploadResult['error'])) {
                $errors['thumbnail'] = $uploadResult['error'];
            } else {
                $post['thumbnail'] = $uploadResult['path'];
            }
        } elseif (empty($errors['thumbnail'])) {
            $this->applyThumbnailFromForm($post, $errors, $existingPost['thumbnail']);
        }

        if (!empty($errors)) {
            $post['id'] = $id;
            $post['created_at'] = $existingPost['created_at'];
            $isEdit = true;
            $title = "Sửa bài viết";
            ob_start();
            require_once 'views/admin/news/form.php';
            $content = ob_get_clean();
            require_once 'views/layouts/admin.php';
            return;
        }

        $post['author_id'] = $existingPost['author_id'] ?: $_SESSION['user_id'];

        if ($this->postModel->update($id, $post)) {
            if ($post['thumbnail'] !== $existingPost['thumbnail']) {
                $this->deleteLocalThumbnail($existingPost['thumbnail']);
            }

            $_SESSION['flash_msg'] = "Cập nhật bài viết thành công.";
            $_SESSION['flash_type'] = "success";
            header("Location: /btl/adminNews/index");
            exit();
        }

        if ($post['thumbnail'] !== $existingPost['thumbnail']) {
            $this->deleteLocalThumbnail($post['thumbnail']);
            $post['thumbnail'] = $existingPost['thumbnail'];
        }

        $errors['general'] = "Không thể cập nhật bài viết.";
        $post['id'] = $id;
        $post['created_at'] = $existingPost['created_at'];
        $isEdit = true;
        $title = "Sửa bài viết";
        ob_start();
        require_once 'views/admin/news/form.php';
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function delete($id)
    {
        $post = $this->postModel->getPostById($id);
        if (!$post) {
            $_SESSION['flash_msg'] = "Bài viết không tồn tại hoặc đã bị xóa.";
            $_SESSION['flash_type'] = "warning";
            header("Location: /btl/adminNews/index");
            exit();
        }

        if ($this->postModel->softDelete($id)) {
            $_SESSION['flash_msg'] = "Đã xóa bài viết.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_msg'] = "Không thể xóa bài viết.";
            $_SESSION['flash_type'] = "danger";
        }

        header("Location: /btl/adminNews/index");
        exit();
    }

    private function collectPostInput()
    {
        return [
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'summary' => trim($_POST['summary'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'thumbnail' => '',
            'seo_title' => trim($_POST['seo_title'] ?? ''),
            'seo_description' => trim($_POST['seo_description'] ?? ''),
            'seo_keywords' => trim($_POST['seo_keywords'] ?? ''),
            'status' => trim($_POST['status'] ?? 'draft'),
            'author_id' => $_SESSION['user_id']
        ];
    }

    private function validatePostData(&$post, $excludeId = null)
    {
        $errors = [];
        $allowedStatus = ['draft', 'published', 'hidden'];

        if ($post['title'] === '') {
            $errors['title'] = "Vui lòng nhập tiêu đề bài viết.";
        } elseif (mb_strlen($post['title']) > 255) {
            $errors['title'] = "Tiêu đề không được vượt quá 255 ký tự.";
        }

        if ($post['slug'] === '') {
            $post['slug'] = $this->slugify($post['title']);
        } else {
            $post['slug'] = $this->slugify($post['slug']);
        }

        if ($post['slug'] === '') {
            $errors['slug'] = "Slug không hợp lệ. Hãy nhập lại tiêu đề hoặc slug.";
        } elseif ($this->postModel->slugExists($post['slug'], $excludeId)) {
            $errors['slug'] = "Slug đã tồn tại. Vui lòng chọn slug khác.";
        }

        if ($post['summary'] === '') {
            $errors['summary'] = "Vui lòng nhập mô tả ngắn.";
        }

        if ($post['content'] === '') {
            $errors['content'] = "Vui lòng nhập nội dung bài viết.";
        }

        if (!in_array($post['status'], $allowedStatus, true)) {
            $errors['status'] = "Trạng thái bài viết không hợp lệ.";
        }

        if ($post['seo_title'] !== '' && mb_strlen($post['seo_title']) > 255) {
            $errors['seo_title'] = "SEO title không được vượt quá 255 ký tự.";
        }

        return $errors;
    }

    private function uploadThumbnail($file)
    {
        $result = [
            'path' => '',
            'error' => ''
        ];

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $result['error'] = "Tải ảnh thumbnail thất bại.";
            return $result;
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            $result['error'] = "Ảnh thumbnail không được lớn hơn 2MB.";
            return $result;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowedExtensions, true)) {
            $result['error'] = "Chỉ chấp nhận file ảnh jpg, jpeg, png, gif hoặc webp.";
            return $result;
        }

        if (@getimagesize($file['tmp_name']) === false) {
            $result['error'] = "File upload không phải là ảnh hợp lệ.";
            return $result;
        }

        $uploadDir = 'public/uploads/posts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $result['error'] = "Không thể lưu ảnh thumbnail lên server.";
            return $result;
        }

        $result['path'] = $targetPath;
        return $result;
    }

    private function deleteLocalThumbnail($thumbnailPath)
    {
        if (!$thumbnailPath) {
            return;
        }

        $normalizedPath = str_replace('\\', '/', $thumbnailPath);
        if (strpos($normalizedPath, 'public/uploads/posts/') !== 0) {
            return;
        }

        if (file_exists($thumbnailPath)) {
            unlink($thumbnailPath);
        }
    }

    private function applyThumbnailFromForm(&$post, &$errors, $fallbackThumbnail = '')
    {
        $thumbnailUrl = trim($_POST['thumbnail_url'] ?? '');

        if ($thumbnailUrl === '') {
            $post['thumbnail'] = $fallbackThumbnail;
            return;
        }

        if (!$this->isValidThumbnailUrl($thumbnailUrl)) {
            $errors['thumbnail'] = "Link ảnh không hợp lệ. Vui lòng dùng URL bắt đầu bằng http:// hoặc https://.";
            return;
        }

        $post['thumbnail'] = $thumbnailUrl;
    }

    private function isValidThumbnailUrl($url)
    {
        if (!preg_match('/^https?:\/\//i', $url)) {
            return false;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    private function slugify($text)
    {
        $text = trim(mb_strtolower($text));

        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        }

        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim($text, '-');

        return $text;
    }

    private function getEmptyPostData()
    {
        return [
            'id' => '',
            'title' => '',
            'slug' => '',
            'summary' => '',
            'content' => '',
            'thumbnail' => '',
            'seo_title' => '',
            'seo_description' => '',
            'seo_keywords' => '',
            'status' => 'draft',
            'created_at' => ''
        ];
    }
}
?>
