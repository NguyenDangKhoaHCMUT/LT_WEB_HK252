<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TechStore - Thiết bị công nghệ cao cấp' ?></title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6: Use for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/btl/public/css/style.css">
    <base href="/btl/">
</head>

<body>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top tech-navbar shadow-sm">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand fw-bold text-primary fs-3" href="/btl/">
                <i class="fa fa-laptop-code me-2"></i>TechStore
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navTech">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navTech">

                <!-- Menus -->
                <ul class="navbar-nav ms-auto align-items-start align-items-lg-center mt-3 mt-lg-0">

                    <!-- Cart Icon -->
                    <li class="nav-item me-lg-4 mb-3 mb-lg-0 position-relative d-inline-block ms-2 ms-lg-0">
                        <a class="nav-link text-dark p-0 p-lg-2" href="/btl/cart">
                            <i class="fa fa-shopping-cart fs-5"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.65rem;">
                                0
                            </span>
                        </a>
                    </li>

                    <!-- User Account -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item mb-2 mb-lg-0"><a
                                    class="nav-link btn btn-outline-danger btn-sm text-danger me-lg-2 fw-semibold px-3 rounded-pill text-start w-100 d-lg-inline-block" style="width: fit-content;"
                                    href="/btl/adminUser/index">Quản trị</a></li>
                        <?php endif; ?>
                        <li class="nav-item dropdown w-100">
                            <a class="nav-link dropdown-toggle text-dark fw-medium p-2" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fa fa-user-circle fs-5 me-1 text-primary"></i>
                                <?= htmlspecialchars($_SESSION['username']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                <li><a class="dropdown-item py-2" href="/btl/profile/index"><i
                                            class="fa fa-id-card me-2 text-muted"></i> Trang cá nhân</a></li>
                                <li><a class="dropdown-item py-2" href="/btl/orders/history"><i
                                            class="fa fa-box-open me-2 text-muted"></i> Đơn hàng của tôi</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item py-2 text-danger" href="/btl/auth/logout"><i
                                            class="fa fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item mb-2 mb-lg-0 w-100 text-start">
                            <a class="btn btn-outline-primary btn-sm rounded-pill px-3 me-lg-2"
                                href="/btl/auth/login">Đăng nhập</a>
                        </li>
                        <li class="nav-item w-100 text-start">
                            <a class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm"
                                href="/btl/auth/register">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container mt-4 min-vh-100 tech-content">
        <?php
        // Hiển thị flash message (thông báo)
        if (isset($_SESSION['flash_msg'])): ?>
            <div
                class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show shadow-sm border-0">
                <?= $_SESSION['flash_msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php
            unset($_SESSION['flash_msg']);
            unset($_SESSION['flash_type']);
        endif;
        ?>

        <!-- Nội dung view cụ thể sẽ được nhúng vào đây -->
        <?= $content ?? '' ?>
    </div>

    <!-- Footer -->
    <footer class="tech-footer bg-dark text-light pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="fa fa-laptop-code me-2"></i>TechStore</h5>
                    <p class="text-secondary small">Hệ thống bán lẻ thiết bị công nghệ hàng đầu, chuyên cung cấp
                        Smartphone và Laptop chính hãng với giá cả cạnh tranh và dịch vụ bảo hành tận tâm.</p>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6 class="fw-bold mb-3">Sản phẩm</h6>
                    <ul class="list-unstyled text-secondary small tech-footer-links">
                        <li><a href="#">iPhone</a></li>
                        <li><a href="#">Samsung Galaxy</a></li>
                        <li><a href="#">MacBook</a></li>
                        <li><a href="#">Laptop Gaming</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6 class="fw-bold mb-3">Hỗ trợ</h6>
                    <ul class="list-unstyled text-secondary small tech-footer-links">
                        <li><a href="/btl/home/about">Giới thiệu</a></li>
                        <li><a href="/btl/home/contact">Liên hệ</a></li>
                        <li><a href="#">Bảo hành</a></li>
                        <li><a href="#">Hỏi đáp (FAQ)</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Kết nối với chúng tôi</h6>
                    <div class="d-flex gap-3 tech-social-icons">
                        <a href="#" class="text-light fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light fs-4"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-light fs-4"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary mt-2 mb-3">
            <div class="text-center text-secondary small">
                © 2026 TechStore.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/btl/public/js/main.js"></script>
</body>

</html>