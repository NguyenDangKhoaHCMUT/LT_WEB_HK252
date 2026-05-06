<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Xác thực - TechStore' ?></title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6: Sử dụng cho icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/btl/public/css/style.css">
    <base href="/btl/">
</head>

<body class="bg-white">

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <!-- Cột trái: Ảnh minh họa chỉ hiển thị trên màn hình >= md (màn hình SP) -->
            <div class="col-md-6 col-lg-7 d-none d-md-block bg-dark position-relative overflow-hidden">
                <!-- Bạn có thể thay thế ảnh background ở đây -->
                <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80"
                    alt="TechStore Background" class="position-absolute w-100 h-100"
                    style="object-fit: cover; object-position: center; opacity: 0.8;">
                <div class="position-absolute w-100 h-100"
                    style="background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2));"></div>

                <div class="position-absolute top-50 start-0 translate-middle-y text-white px-5 ms-md-4 ms-lg-5"
                    style="max-width: 600px;">
                    <a href="/btl/" class="text-decoration-none text-white d-inline-block mb-4">
                        <i class="fa fa-laptop-code fs-1 d-block mb-2"></i>
                        <h2 class="fw-bold m-0 fs-3">TechStore</h2>
                    </a>
                    <h1 class="display-4 fw-bold mb-4">Nền tảng mua sắm công nghệ số 1</h1>
                    <p class="lead fw-light text-light mb-0">Chúng tôi cung cấp các thiết bị công nghệ hiện đại, chất
                        lượng cao với dịch vụ bảo hành tận tâm và uy tín nhất.</p>
                </div>
            </div>

            <!-- Cột phải: Form Content -->
            <div class="col-md-6 col-lg-5 d-flex flex-column justify-content-center px-4 py-5 px-sm-5 bg-white">
                <div class="w-100 mx-auto" style="max-width: 420px;">

                    <!-- Logo cho mobile -->
                    <div class="text-center mb-5 d-md-none">
                        <a href="/btl/"
                            class="text-decoration-none text-primary fs-2 fw-bold d-inline-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                style="width: 40px; height: 40px;">
                                <i class="fa fa-laptop-code fs-5"></i>
                            </div>
                            TechStore
                        </a>
                    </div>

                    <!-- Hiển thị flash message (thông báo) -->
                    <?php if (isset($_SESSION['flash_msg'])): ?>
                        <div
                            class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show shadow-sm border-0 bg-<?= $_SESSION['flash_type'] ?? 'info' ?> bg-opacity-10 text-<?= $_SESSION['flash_type'] ?? 'info' ?>">
                            <i class="fa fa-info-circle me-2"></i><?= $_SESSION['flash_msg'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php
                        unset($_SESSION['flash_msg']);
                        unset($_SESSION['flash_type']);
                    endif; ?>

                    <!-- Nội dung view cụ thể (login/register) sẽ được nhúng vào đây -->
                    <?= $content ?? '' ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
