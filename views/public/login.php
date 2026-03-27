<h3 class="fw-bold mb-2">Đăng Nhập</h3>
<p class="text-muted mb-4 pb-2">Chào mừng bạn quay lại! Vui lòng điền thông tin để tiếp tục.</p>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger shadow-sm">
        <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="/btl/auth/login" method="POST">
    <div class="mb-3">
        <label for="email" class="form-label fw-semibold text-dark">Địa chỉ Email</label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="fa fa-envelope"></i></span>
            <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" placeholder="example@gmail.com" required>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label fw-semibold text-dark d-flex justify-content-between">
            <span>Mật khẩu</span>
            <!-- Bỏ comment khi làm tính năng quên mật khẩu:
            <a href="/btl/auth/forgot" class="text-primary text-decoration-none small fw-medium">Quên mật khẩu?</a> -->
        </label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="fa fa-lock"></i></span>
            <input type="password" class="form-control bg-light border-start-0 ps-0" id="password" name="password" placeholder="••••••••" required>
        </div>
    </div>
    
    <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary py-2 fw-bold shadow">
            Đăng Nhập <i class="fa fa-arrow-right ms-2 fs-6"></i>
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <p class="text-muted mb-3">Bạn chưa có tài khoản? <a href="/btl/auth/register" class="text-primary text-decoration-none fw-bold ms-1">Đăng ký ngay</a></p>
    <a href="/btl/" class="text-decoration-none d-inline-flex align-items-center text-secondary hover-primary transition">
        <i class="fa fa-arrow-left me-2"></i>Về trang chủ
    </a>
</div>