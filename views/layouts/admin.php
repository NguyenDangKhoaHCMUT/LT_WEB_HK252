<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TechStore Admin Dashboard' ?></title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <base href="/btl/">

    <style>
        :root {
            --admin-bg: #f5f6f8;
            --sidebar-bg: #1e1e2d;
            --sidebar-color: #a2a3b7;
            --sidebar-hover-bg: #1b1b28;
            --sidebar-hover-color: #ffffff;
            --sidebar-active-bg: #3699ff;
            --sidebar-active-color: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--admin-bg);
            color: #3f4254;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            background-color: var(--sidebar-bg);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0 0 28px 0 rgba(82, 63, 105, 0.08);
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }
        }

        .sidebar-brand {
            height: 65px;
            display: flex;
            align-items: center;
            padding: 0 25px;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            background-color: #1a1a27;
        }

        .sidebar-menu {
            padding: 20px 0;
            list-style: none;
            margin: 0;
            overflow-y: auto;
            height: calc(100vh - 65px);
        }

        .sidebar-menu li {
            padding: 0 15px;
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            color: var(--sidebar-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .sidebar-menu a i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar-menu a:hover {
            color: var(--sidebar-hover-color);
            background-color: var(--sidebar-hover-bg);
        }

        .sidebar-menu a.active {
            color: var(--sidebar-active-color);
            background-color: var(--sidebar-active-bg);
        }

        /* Main Content Styling */
        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 991.98px) {
            .admin-main {
                margin-left: 0;
            }
        }

        @media (min-width: 992px) {
            body.sidebar-collapsed .admin-sidebar {
                transform: translateX(-100%);
            }

            body.sidebar-collapsed .admin-main {
                margin-left: 0;
            }
        }

        /* Header Styling */
        .admin-header {
            height: 65px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            padding: 0 25px;
            box-shadow: 0 0 40px 0 rgba(82, 63, 105, 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .header-title {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 600;
            color: #181c32;
        }

        /* Content Area */
        .admin-content {
            padding: 25px;
            flex-grow: 1;
        }

        /* Utilities */
        .btn-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 8px;
        }

        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1035;
            display: none;
        }

        .sidebar-backdrop.show {
            display: block;
        }
    </style>
</head>

<body>
    <?php $current_uri = $_SERVER['REQUEST_URI']; ?>

    <!-- Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <a href="/btl/admin/index" class="sidebar-brand">
            <i class="fa fa-laptop-code text-primary me-2"></i>TechStore
        </a>
        <ul class="sidebar-menu">
            <li class="nav-small-cap text-muted text-uppercase fw-bold mb-2 mt-2 px-3" style="font-size: 0.75rem;">Menu
                chính</li>
            <li><a href="/btl/admin/index"
                    class="<?= strpos($current_uri, '/admin/index') !== false ? 'active' : '' ?>"><i
                        class="fa fa-chart-pie"></i> Tổng quan</a></li>
            <li><a href="/btl/adminUser/index"
                    class="<?= strpos($current_uri, '/adminUser/') !== false ? 'active' : '' ?>"><i
                        class="fa fa-users"></i> Quản lý Thành viên</a></li>
            <li><a href="/btl/adminProduct/index"
                    class="<?= strpos($current_uri, '/adminProduct/') !== false ? 'active' : '' ?>"><i
                        class="fa fa-mobile-alt"></i> Quản lý Sản phẩm</a></li>
            <li><a href="/btl/adminNews/index"
                    class="<?= strpos($current_uri, '/adminNews/') !== false ? 'active' : '' ?>"><i
                        class="fa fa-newspaper"></i> Quản lý Tin tức</a></li>
            <li><a href="/btl/adminOrder/index"
                    class="<?= strpos($current_uri, '/adminOrder/') !== false ? 'active' : '' ?>"><i
                        class="fa fa-shopping-cart"></i> Quản lý Đơn hàng</a></li>
            <li><a href="/btl/admincontact/index"
                class="<?= strpos($current_uri, '/admincontact/') !== false ? 'active' : '' ?>">
                <i class="fa fa-envelope"></i> Quản lý Liên hệ
            </a></li>

            <li class="nav-small-cap text-muted text-uppercase fw-bold mb-2 mt-4 px-3" style="font-size: 0.75rem;">Hệ
                thống</li>
            <li><a href="/btl/adminSetting/about"
                    class="<?= strpos($current_uri, '/adminSetting/') !== false ? 'active' : '' ?>"><i
                        class="fa fa-cog"></i> Quản lý Nội dung</a></li>
            <li><a href="/btl/auth/logout" class="text-danger"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </div>

    <!-- Backdrop for mobile -->
    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-light btn-icon me-3" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="fa fa-bars fs-5"></i>
                </button>
                <h1 class="header-title d-none d-sm-block"><?= isset($title) ? $title : 'Dashboard' ?></h1>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="admin-content">
            <!-- Breadcrumb (Optional but looks nice) -->
            <nav aria-label="breadcrumb" class="mb-4 d-sm-none">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active fw-medium text-dark" aria-current="page">
                        <?= isset($title) ? $title : 'Dashboard' ?>
                    </li>
                </ol>
            </nav>

            <?php if (isset($_SESSION['flash_msg'])): ?>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar Toggle Logic for Mobile and PC
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function () {
                    if (window.innerWidth < 992) {
                        // Logic cho Mobile
                        sidebar.classList.add('show');
                        backdrop.classList.add('show');
                    } else {
                        // Logic cho PC
                        document.body.classList.toggle('sidebar-collapsed');
                    }
                });

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>
</body>

</html>