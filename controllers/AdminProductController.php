<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'middleware/AdminMiddleware.php';

class AdminProductController
{
    private $db;
    private $productModel;

    public function __construct()
    {
        AdminMiddleware::check();

        $database = new Database();
        $this->db = $database->getConnection();
        $this->productModel = new Product($this->db);
    }

    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getAllProducts($keyword, $limit, $offset);
        $totalProducts = $this->productModel->countProducts($keyword);
        $totalPages = max(1, (int) ceil($totalProducts / $limit));

        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $products = $this->productModel->getAllProducts($keyword, $limit, $offset);
        }

        $title = 'Quản lý Sản phẩm';
        $pageTitle = 'Sản phẩm';

        ob_start();
        require_once 'views/admin/products.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function create()
    {
        $categories = $this->productModel->getCategories();

        $title = 'Thêm Sản phẩm';
        $pageTitle = 'Sản phẩm';
        $formTitle = 'Thêm sản phẩm mới';
        $formAction = '/btl/adminProduct/store';
        $submitText = 'Lưu sản phẩm';
        $product = [
            'name' => '',
            'category_id' => '',
            'description' => '',
            'price' => '',
            'stock' => '',
            'image' => ''
        ];

        ob_start();
        require_once 'views/admin/product_form.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/adminProduct/index');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price = (int) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);

        $errors = $this->validateProductInput($name, $categoryId, $price, $stock);
        $imagePath = $this->handleImageUpload('image');

        if ($imagePath === false) {
            $errors[] = 'File ảnh không hợp lệ. Chỉ chấp nhận jpg, jpeg, png, webp, gif.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_msg'] = implode('<br>', $errors);
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/adminProduct/create');
            exit();
        }

        $this->productModel->category_id = $categoryId;
        $this->productModel->name = $name;
        $this->productModel->slug = $this->slugify($name) . '-' . time();
        $this->productModel->description = $description;
        $this->productModel->image = $imagePath;
        $this->productModel->price = $price;
        $this->productModel->stock = $stock;

        if ($this->productModel->create()) {
            $_SESSION['flash_msg'] = 'Thêm sản phẩm thành công.';
            $_SESSION['flash_type'] = 'success';
            header('Location: /btl/adminProduct/index');
            exit();
        }

        $_SESSION['flash_msg'] = 'Không thể thêm sản phẩm. Vui lòng thử lại.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: /btl/adminProduct/create');
        exit();
    }

    public function edit($id)
    {
        $id = (int) $id;
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $_SESSION['flash_msg'] = 'Không tìm thấy sản phẩm.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/adminProduct/index');
            exit();
        }

        $categories = $this->productModel->getCategories();

        $title = 'Chỉnh sửa Sản phẩm';
        $pageTitle = 'Sản phẩm';
        $formTitle = 'Chỉnh sửa sản phẩm';
        $formAction = '/btl/adminProduct/update/' . $id;
        $submitText = 'Cập nhật';

        ob_start();
        require_once 'views/admin/product_form.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /btl/adminProduct/index');
            exit();
        }

        $id = (int) $id;
        $existingProduct = $this->productModel->getProductById($id);

        if (!$existingProduct) {
            $_SESSION['flash_msg'] = 'Không tìm thấy sản phẩm.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/adminProduct/index');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price = (int) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);

        $errors = $this->validateProductInput($name, $categoryId, $price, $stock);
        $newImagePath = $this->handleImageUpload('image');

        if ($newImagePath === false) {
            $errors[] = 'File ảnh không hợp lệ. Chỉ chấp nhận jpg, jpeg, png, webp, gif.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_msg'] = implode('<br>', $errors);
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/adminProduct/edit/' . $id);
            exit();
        }

        $finalImage = $newImagePath !== null ? $newImagePath : ($existingProduct['image'] ?? null);

        $this->productModel->id = $id;
        $this->productModel->category_id = $categoryId;
        $this->productModel->name = $name;
        $this->productModel->slug = $existingProduct['slug'] ?? ($this->slugify($name) . '-' . time());
        $this->productModel->description = $description;
        $this->productModel->image = $finalImage;
        $this->productModel->price = $price;
        $this->productModel->stock = $stock;

        if ($this->productModel->update()) {
            $_SESSION['flash_msg'] = 'Cập nhật sản phẩm thành công.';
            $_SESSION['flash_type'] = 'success';

            if ($newImagePath !== null && !empty($existingProduct['image'])) {
                $this->deleteImageFile($existingProduct['image']);
            }

            header('Location: /btl/adminProduct/index');
            exit();
        }

        $_SESSION['flash_msg'] = 'Không thể cập nhật sản phẩm. Vui lòng thử lại.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: /btl/adminProduct/edit/' . $id);
        exit();
    }

    public function delete($id)
    {
        $id = (int) $id;
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $_SESSION['flash_msg'] = 'Không tìm thấy sản phẩm để xoá.';
            $_SESSION['flash_type'] = 'danger';
            header('Location: /btl/adminProduct/index');
            exit();
        }

        if ($this->productModel->delete($id)) {
            if (!empty($product['image'])) {
                $this->deleteImageFile($product['image']);
            }

            $_SESSION['flash_msg'] = 'Đã xoá sản phẩm thành công.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_msg'] = 'Không thể xoá sản phẩm.';
            $_SESSION['flash_type'] = 'danger';
        }

        header('Location: /btl/adminProduct/index');
        exit();
    }

    private function validateProductInput($name, $categoryId, $price, $stock)
    {
        $errors = [];

        if ($name === '') {
            $errors[] = 'Tên sản phẩm không được để trống.';
        }

        if ($categoryId <= 0) {
            $errors[] = 'Vui lòng chọn danh mục.';
        }

        if ($price < 0) {
            $errors[] = 'Giá sản phẩm không hợp lệ.';
        }

        if ($stock < 0) {
            $errors[] = 'Số lượng tồn kho không hợp lệ.';
        }

        return $errors;
    }

    private function handleImageUpload($fieldName)
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $extension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($extension, $allowedExtensions, true)) {
            return false;
        }

        $uploadDir = __DIR__ . '/../public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = 'product_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
            return false;
        }

        return '/btl/public/uploads/products/' . $fileName;
    }

    private function deleteImageFile($imagePath)
    {
        $relativePath = str_replace('/btl/', '', $imagePath);
        $fullPath = __DIR__ . '/../' . $relativePath;

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function slugify($value)
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
        $value = trim($value, '-');

        return $value !== '' ? $value : 'product';
    }
}
?>