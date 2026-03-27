<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TechStore Admin' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <base href="/btl/">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f2f7;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #1a202c;
            padding-top: 20px;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .main-content {
            background-color: #f1f2f7;
            min-height: 100vh;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar text-white px-0">
                <h4 class="text-center mb-4 text-primary fw-bold"><i class="fa fa-laptop-code me-2"></i>TechStore</h4>
                <a href="/btl/admin/index"><i class="fa fa-chart-pie me-2"></i> Tổng quan</a>
                <a href="/btl/adminUser/index" class="bg-primary text-white"><i class="fa fa-users me-2"></i> Quản trị
                    Thành viên</a>
                <a href="/btl/adminProduct/index"><i class="fa fa-mobile-alt me-2"></i> Quản lý Sản phẩm</a>
                <a href="/btl/adminNews/index"><i class="fa fa-newspaper me-2"></i> Quản lý Tin tức</a>
                <a href="/btl/adminOrder/index"><i class="fa fa-shopping-cart me-2"></i> Quản lý Đơn hàng</a>
                <hr class="border-secondary mx-3">
                <a href="/btl/"><i class="fa fa-store me-2"></i> Xem trang cửa hàng</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 shadow-sm rounded">
                    <h5 class="mb-0"><?= isset($title) ? $title : 'Dashboard' ?></h5>
                    <div>
                        <span class="me-3"><i class="fa fa-user"></i> Xin chào,
                            <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <a href="/btl/auth/logout" class="btn btn-sm btn-danger">Đăng xuất</a>
                    </div>
                </div>

                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
                        <?= $_SESSION['flash_msg'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                        <?php
                        unset($_SESSION['flash_msg']);
                        unset($_SESSION['flash_type']);
                endif;
                ?>

                <!-- Nội dung riêng của từng page -->
                <?= isset($content) ? $content : '' ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>