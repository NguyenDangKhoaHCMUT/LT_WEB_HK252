<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'TechStore Admin Dashboard' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <base href="/btl/">

    <link rel="icon" type="image/png" href="public/srtdash/assets/images/icon/logo.png">
    <link rel="stylesheet" href="public/srtdash/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/srtdash/assets/css/themify-icons.css">
    <link rel="stylesheet" href="public/srtdash/assets/css/metismenujs.min.css">
    <link rel="stylesheet" href="public/srtdash/assets/css/swiper-bundle.min.css">
    <!-- others css -->
    <link rel="stylesheet" href="public/srtdash/assets/css/typography.css">
    <link rel="stylesheet" href="public/srtdash/assets/css/default-css.css">
    <link rel="stylesheet" href="public/srtdash/assets/css/styles.css">
    <link rel="stylesheet" href="public/srtdash/assets/css/responsive.css">

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Quill Editor Custom Styles */
        .ql-editor { min-height: 150px; font-size: 0.95rem; font-family: 'Inter', sans-serif; }
        .ql-editor.ql-desc1 { min-height: 120px; max-height: 250px; overflow-y: auto; }
        .ql-editor.ql-desc2 { min-height: 200px; max-height: 500px; overflow-y: auto; }
        .quill-wrapper { border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden; }
        .quill-wrapper .ql-toolbar { border: none; border-bottom: 1px solid #dee2e6; background: #f8f9fa; }
        .quill-wrapper .ql-container { border: none; }
        .char-count { font-size: 0.8rem; }
        .char-count.over { color: #dc3545; }

        /* FAQ Admin Styles */
        .faq-tab-btn { border-radius: 10px !important; font-weight: 500; font-size: 0.9rem; padding: 0.55rem 1.2rem; }
        .faq-table th { font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #6c757d; border-bottom: 2px solid #e9ecef; }
        .faq-table td { vertical-align: middle; font-size: 0.92rem; }

        /* List tables: max 5 lines (full text on detail page) */
        .faq-question-text,
        .faq-answer-text,
        .faq-user-question-text,
        .answer-preview {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            line-clamp: 5;
            overflow: hidden;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .faq-question-text { max-width: 320px; font-weight: 500; }
        .faq-answer-text { max-width: 260px; color: #555; }
        .faq-user-question-text { min-width: 180px; max-width: 300px; font-weight: 500; font-size: 0.9rem; }

        .badge-published { background: #d1e7dd; color: #146c43; font-size: 0.75rem; padding: 3px 10px; border-radius: 50px; font-weight: 600; white-space: nowrap; display: inline-block; }
        .badge-hidden { background: #f8d7da; color: #842029; font-size: 0.75rem; padding: 3px 10px; border-radius: 50px; font-weight: 600; white-space: nowrap; display: inline-block; }
        .badge-pending { background: #fff3cd; color: #856404; font-size: 0.75rem; padding: 3px 10px; border-radius: 50px; font-weight: 600; white-space: nowrap; display: inline-block; }
        .badge-answered { background: #d1e7dd; color: #146c43; font-size: 0.75rem; padding: 3px 10px; border-radius: 50px; font-weight: 600; white-space: nowrap; display: inline-block; }
        .user-avatar-sm { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid #e9ecef; }
        .answer-preview {
            background: #f0f5ff;
            border-left: 3px solid #0d6efd;
            border-radius: 0 8px 8px 0;
            padding: 0.6rem 0.85rem;
            font-size: 0.88rem;
            color: #444;
            margin-top: 0;
            min-width: 180px;
            max-width: 300px;
        }
        .pending-dot { width: 8px; height: 8px; background: #ffc107; border-radius: 50%; display: inline-block; margin-right: 5px; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    </style>
</head>

<body>
    <?php $current_uri = $_SERVER['REQUEST_URI'] ?? ''; ?>
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo text-start ps-3">
                    <a href="/btl/admin/index" class="text-white fw-bold fs-4 text-decoration-none">
                        <i class="fa fa-laptop-code text-primary me-2"></i>TechStore
                    </a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="<?= strpos($current_uri, '/adminProduct/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminProduct/index" aria-expanded="true"><i class="fa fa-box-open"></i><span>Quản lý Sản phẩm</span></a>
                            </li>
                            <li class="<?= strpos($current_uri, '/adminOrder/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminOrder/index" aria-expanded="true"><i class="fa fa-cart-shopping"></i><span>Giỏ hàng / Đơn hàng</span></a>
                            </li>
                            <li class="<?= strpos($current_uri, '/adminUser/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminUser/index" aria-expanded="true"><i class="fa fa-users"></i><span>Quản lý Thành viên</span></a>
                            </li>
                            <li class="<?= strpos($current_uri, '/adminNews/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminNews/index" aria-expanded="true"><i class="fa fa-newspaper"></i><span>Quản lý Tin tức</span></a>
                            </li>
                            <li class="<?= strpos($current_uri, '/adminComment/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminComment/index" aria-expanded="true"><i class="fa fa-comments"></i><span>Quản lý Bình luận</span></a>
                            </li>
                            <li class="<?= strpos($current_uri, '/adminFaq/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminFaq/index" aria-expanded="true"><i class="fa fa-question-circle"></i><span>Quản lý Hỏi/Đáp</span></a>
                            </li>
                            <li class="<?= stripos($current_uri, '/admincontact') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminContact/index" aria-expanded="true"><i class="fa fa-envelope"></i><span>Quản lý Liên hệ</span></a>
                            </li>
                            <li class="<?= strpos($current_uri, '/adminSetting/') !== false ? 'mm-active' : '' ?>">
                                <a href="/btl/adminSetting/about" aria-expanded="true"><i class="fa fa-cog"></i><span>Quản lý Nội dung</span></a>
                            </li>
                            <li>
                                <a href="/btl/auth/logout" class="text-danger"><i class="fa fa-sign-out-alt"></i><span>Đăng xuất</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn float-start">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-6 col-sm-4 clearfix">
                        <ul class="notification-area float-end">
                            <li id="full-view"><i class="ti-fullscreen"></i></li>
                            <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- header area end -->
            <div class="main-content-inner pt-4" id="main-content">
                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            let type = "<?= $_SESSION['flash_type'] ?? 'info' ?>";
                            if (type === 'danger') type = 'error';

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
                <!-- Page View Content -->
                <?= $content ?? '' ?>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2026 TechStore. Template by Colorlib.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    
    <!-- Scripts -->
    <script src="public/srtdash/assets/js/bootstrap.bundle.min.js"></script>
    <script src="public/srtdash/assets/js/metismenujs.min.js"></script>
    <script src="public/srtdash/assets/js/scripts.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>