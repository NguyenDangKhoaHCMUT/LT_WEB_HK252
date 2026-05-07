<h3 class="fw-bold mb-2">Đăng Ký Tài Khoản</h3>
<p class="text-muted mb-4 pb-2">Tạo tài khoản mới để trải nghiệm mua sắm tuyệt vời.</p>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger shadow-sm">
        <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- Bắt lỗi validation input bằng Javascript theo yêu cầu -->
<form action="/btl/auth/register" method="POST" id="registerForm" onsubmit="return validateRegister()">
    <?php Csrf::insertHiddenField(); ?>
    
    <div class="mb-3">
        <label for="fullname" class="form-label fw-semibold text-dark">Họ và Tên <span class="text-danger">*</span></label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="fa fa-user"></i></span>
            <input type="text" class="form-control bg-light border-start-0 ps-0" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="fa fa-envelope"></i></span>
            <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" placeholder="Nhập địa chỉ email" required>
        </div>
        <div class="form-text text-danger ps-2 mt-1" id="err-email"></div>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label fw-semibold text-dark">Mật khẩu <span class="text-danger">*</span></label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="fa fa-lock"></i></span>
            <input type="password" class="form-control bg-light border-start-0 ps-0" id="password" name="password" placeholder="Từ 6 ký tự trở lên" required>
        </div>
        <div class="form-text text-danger ps-2 mt-1" id="err-password"></div>
    </div>
    
    <div class="mb-3">
        <label for="confirm_password" class="form-label fw-semibold text-dark">Xác nhận mật khẩu <span class="text-danger">*</span></label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="fa fa-check-circle"></i></span>
            <input type="password" class="form-control bg-light border-start-0 ps-0" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
        </div>
        <div class="form-text text-danger ps-2 mt-1" id="err-confirm-password"></div>
    </div>
    
    <div class="d-grid mt-4 pt-2">
        <button type="submit" class="btn btn-primary py-2 fw-bold shadow">
            Tạo Tài Khoản
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <p class="text-muted mb-3">Đã có tài khoản? <a href="/btl/auth/login" class="text-primary text-decoration-none fw-bold ms-1">Đăng nhập ngay</a></p>
    <a href="/btl/" class="text-decoration-none d-inline-flex align-items-center text-secondary hover-primary transition">
        <i class="fa fa-arrow-left me-2"></i>Về trang chủ
    </a>
</div>

<script>
    function validateRegister() {
        let password = document.getElementById('password').value;
        let confirm_password = document.getElementById('confirm_password').value;
        let valid = true;

        if (password.length < 6) {
            document.getElementById('err-password').innerText = "Mật khẩu phải chứa ít nhất 6 ký tự.";
            valid = false;
        } else {
            document.getElementById('err-password').innerText = "";
        }

        if (password !== confirm_password) {
            document.getElementById('err-confirm-password').innerText = "Mật khẩu xác nhận không khớp mác.";
            valid = false;
        } else {
            document.getElementById('err-confirm-password').innerText = "";
        }

        return valid;
    }
</script>