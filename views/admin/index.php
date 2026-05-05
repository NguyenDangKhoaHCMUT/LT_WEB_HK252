<h1 class="mb-30">Dashboard</h1>

<!-- Stat Cards Row 1 -->
<div class="row mb-30">
    <div class="col-md-6 col-lg-3 mb-20">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Tổng Người Dùng</div>
            <div class="stat-value"><?php echo $stats['total_users'] ?? 0; ?></div>
            <div class="stat-change">+5% so với tháng trước</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-20">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-label">Sản Phẩm</div>
            <div class="stat-value"><?php echo $stats['total_products'] ?? 0; ?></div>
            <div class="stat-change">+2 sản phẩm mới</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-20">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-label">Đơn Hàng</div>
            <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
            <div class="stat-change"><?php echo $stats['pending_orders'] ?? 0; ?> đơn chờ xử lý</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-20">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-label">Liên Hệ</div>
            <div class="stat-value"><?php echo $stats['total_contacts'] ?? 0; ?></div>
            <div class="stat-change"><?php echo $stats['new_contacts'] ?? 0; ?> liên hệ mới</div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row mb-30">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-20">
                <a href="/btl/adminProduct/create" class="card text-decoration-none" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; transition: all 0.3s;">
                    <div class="text-center">
                        <i class="fas fa-plus-circle" style="font-size: 32px; color: #6366f1; margin-bottom: 10px;"></i>
                        <h6 class="mt-3 mb-0">Thêm Sản Phẩm</h6>
                        <small class="text-muted">Tạo sản phẩm mới</small>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-20">
                <a href="/btl/AdminDashboard/orders" class="card text-decoration-none" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; transition: all 0.3s;">
                    <div class="text-center">
                        <i class="fas fa-clipboard-list" style="font-size: 32px; color: #f59e0b; margin-bottom: 10px;"></i>
                        <h6 class="mt-3 mb-0">Quản Lý Đơn</h6>
                        <small class="text-muted">Xem đơn hàng</small>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-20">
                <a href="/btl/adminUser/index" class="card text-decoration-none" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; transition: all 0.3s;">
                    <div class="text-center">
                        <i class="fas fa-user-cog" style="font-size: 32px; color: #10b981; margin-bottom: 10px;"></i>
                        <h6 class="mt-3 mb-0">Quản Lý Người</h6>
                        <small class="text-muted">Quản lý tài khoản</small>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-20">
                <a href="/btl/admincontact/index" class="card text-decoration-none" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; transition: all 0.3s;">
                    <div class="text-center">
                        <i class="fas fa-envelope-open" style="font-size: 32px; color: #ef4444; margin-bottom: 10px;"></i>
                        <h6 class="mt-3 mb-0">Liên Hệ</h6>
                        <small class="text-muted">Xem thông tin liên hệ</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data -->
<div class="row">
    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Đơn Hàng Gần Đây</h5>
            <?php if (!empty($recent_orders)): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr style="background-color: #f9fafb;">
                                <th>ID</th>
                                <th>Khách</th>
                                <th>Trạng Thái</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] == 'completed' ? 'success' : ($order['status'] == 'pending' ? 'warning' : 'secondary'); ?>">
                                            <?php 
                                                $statuses = ['cart' => 'Giỏ', 'pending' => 'Chờ', 'processing' => 'Xử lý', 'completed' => 'Xong', 'cancelled' => 'Huỷ'];
                                                echo $statuses[$order['status']] ?? $order['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($order['total_amount']); ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Không có đơn hàng nào</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="mb-4"><i class="fas fa-envelope me-2"></i>Liên Hệ Mới</h5>
            <?php if (!empty($recent_contacts)): ?>
                <div class="space-y-3">
                    <?php foreach ($recent_contacts as $contact): ?>
                        <div style="padding: 12px; background-color: #f9fafb; border-radius: 8px; border-left: 3px solid #6366f1;">
                            <div style="display: flex; justify-content: space-between;">
                                <strong><?php echo htmlspecialchars($contact['name'] ?? 'N/A'); ?></strong>
                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($contact['created_at'])); ?></small>
                            </div>
                            <small class="text-muted"><?php echo htmlspecialchars($contact['email'] ?? 'N/A'); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">Không có liên hệ nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .space-y-3 > * {
        margin-bottom: 12px;
    }

    .space-y-3 > *:last-child {
        margin-bottom: 0;
    }
</style>