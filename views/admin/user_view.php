<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Chi tiết Người dùng #<?php echo (int)$user['id']; ?></h1>
    <a href="/btl/adminUser/index" class="btn btn-light border">Quay lại danh sách</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES); ?></p>
                <p><strong>Vai trò:</strong> <?php echo htmlspecialchars($user['role'] ?? '', ENT_QUOTES); ?></p>
                <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($user['status'] ?? '', ENT_QUOTES); ?></p>
                <p><strong>Ngày tạo:</strong> <?php echo htmlspecialchars($user['created_at'] ?? '', ENT_QUOTES); ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <a href="/btl/adminUser/toggleStatus/<?php echo (int)$user['id']; ?>" class="btn btn-warning w-100 mb-2" onclick="return confirm('Xác nhận thay đổi trạng thái?');">Thay đổi trạng thái</a>
                <a href="/btl/adminUser/resetPassword/<?php echo (int)$user['id']; ?>" class="btn btn-secondary w-100 mb-2" onclick="return confirm('Đặt lại mật khẩu về 123456?');">Đặt lại mật khẩu</a>
                <a href="/btl/adminUser/delete/<?php echo (int)$user['id']; ?>" class="btn btn-danger w-100" onclick="return confirm('Xoá người dùng?');">Xoá</a>
            </div>
        </div>
    </div>
</div>
