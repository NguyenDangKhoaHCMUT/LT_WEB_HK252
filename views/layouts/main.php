<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'TechStore - Thiết bị công nghệ cao cấp') ?></title>
    <meta name="description"
        content="<?= htmlspecialchars($metaDescription ?? 'TechStore cung cấp tin tức công nghệ, bài đánh giá sản phẩm và thông tin hữu ích cho người dùng yêu công nghệ.') ?>">
    <?php if (!empty($metaKeywords)): ?>
        <meta name="keywords" content="<?= htmlspecialchars($metaKeywords) ?>">
    <?php endif; ?>
    <?php if (!empty($canonicalUrl)): ?>
        <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    <?php endif; ?>

    <!-- Open Graph / Social Sharing -->
    <meta property="og:type"        content="<?= htmlspecialchars($ogType ?? 'website') ?>">
    <meta property="og:title"       content="<?= htmlspecialchars($ogTitle ?? $title ?? 'TechStore') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription ?? $metaDescription ?? '') ?>">
    <?php if (!empty($canonicalUrl)): ?>
        <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <?php endif; ?>
    <?php if (!empty($ogImage)): ?>
        <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
    <?php endif; ?>


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
    <nav class="navbar navbar-expand-lg bg-white sticky-top tech-navbar shadow-sm py-2">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <!-- Toggle Button for Mobile -->
                <button class="navbar-toggler border-0 shadow-none px-2 me-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCenter">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Brand Logo -->
                <a class="navbar-brand fw-bold text-primary fs-4 m-0 p-0" href="/btl/">
                    <i class="fa fa-laptop-code me-1"></i><span class="d-none d-sm-inline">TechStore</span>
                </a>
            </div>

            <!-- Center Menu -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarCenter">
                <?php $current_uri = $_SERVER['REQUEST_URI'] ?? ''; ?>
                <ul class="navbar-nav fw-medium gap-lg-2 mt-3 mt-lg-0 text-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_uri == '/btl/' || strpos($current_uri, '/home/index') !== false ? 'text-primary fw-bold' : '' ?>"
                            href="/btl/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($current_uri, '/product') !== false ? 'text-primary fw-bold' : '' ?>"
                            href="/btl/product/index">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($current_uri, '/news') !== false ? 'text-primary fw-bold' : '' ?>"
                            href="/btl/news">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($current_uri, '/home/contact') !== false ? 'text-primary fw-bold' : '' ?>"
                            href="/btl/home/contact">Liên hệ</a>
                    </li>
                </ul>
            </div>

            <!-- Right side items -->
            <div class="d-flex align-items-center" id="navTech">
                <ul class="navbar-nav align-items-center flex-row m-0 p-0">

                    <!-- User Account -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_role'] !== 'admin'): ?>
                            <!-- Cart Icon -->
                            <li class="nav-item me-3 position-relative">
                                <a class="nav-link text-dark p-2" href="/btl/cart">
                                    <i class="fa fa-shopping-cart fs-5"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.65rem;">
                                        0
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item me-3 d-none d-md-block">
                                <a class="btn btn-outline-danger btn-sm fw-semibold px-3 rounded-pill"
                                    href="/btl/adminUser/index">Quản trị</a>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'admin'): ?>
                            <li class="nav-item dropdown">
                                <!-- Show Avatar -->
                                <a class="nav-link dropdown-toggle text-dark fw-medium p-0 d-flex align-items-center" href="#"
                                    id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <?php if (!empty($_SESSION['avatar'])): ?>
                                        <img src="/btl/<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Avatar"
                                            class="rounded-circle me-1 border"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                    <?php else: ?>
                                        <i class="fa fa-user-circle fs-4 me-1 text-primary"></i>
                                    <?php endif; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3 position-absolute">
                                    <li><a class="dropdown-item py-2" href="/btl/profile/index"><i
                                                class="fa fa-id-card me-2 text-muted"></i> Hồ sơ</a></li>
                                    <?php if ($_SESSION['user_role'] !== 'admin'): ?>
                                        <li><a class="dropdown-item py-2" href="/btl/orders/history"><i
                                                    class="fa fa-box-open me-2 text-muted"></i> Đơn hàng của tôi</a></li>
                                    <?php endif; ?>
                                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                        <li class="d-md-none"><a class="dropdown-item py-2 text-danger fw-bold"
                                                href="/btl/adminUser/index"><i class="fa fa-cogs me-2"></i> Quản trị</a></li>
                                    <?php endif; ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item py-2 text-danger" href="/btl/auth/logout"><i
                                                class="fa fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item ms-md-2">
                            <a class="btn rounded-pill px-4 fw-medium shadow-sm d-flex align-items-center gap-2 tech-login-btn"
                                href="/btl/auth/login" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                                Đăng nhập
                                <i class="fa-regular fa-circle-user fs-5"></i>
                            </a>
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
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let type = "<?= $_SESSION['flash_type'] ?? 'info' ?>";
                    if (type === 'danger') type = 'error'; // Chuyển kiểu của Bootstrap sang SweetAlert

                    Swal.fire({
                        toast: true,
                        position: 'bottom-end',
                        icon: type,
                        title: "<?= $_SESSION['flash_msg'] ?>",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'colored-toast'
                        }
                    });
                });
            </script>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/btl/public/js/main.js"></script>
</body>

</html>
