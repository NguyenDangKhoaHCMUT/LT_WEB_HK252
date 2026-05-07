<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm text-center p-3">
            <h5 class="card-title">Ảnh đại diện</h5>
            <div class="mb-4">
                <img src="/btl/<?= !empty($user_data['avatar']) ? $user_data['avatar'] : 'public/images/default-avatar.png' ?>"
                    class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;"
                    alt="Avatar">
                <div>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" form="updateProfileForm"
                        class="d-none" onchange="document.getElementById('updateProfileForm').submit();">
                    <label for="avatarInput" class="btn btn-primary btn-sm px-3" style="cursor: pointer;">
                        <i class="fa fa-upload"></i>
                    </label>
                </div>
            </div>
            <h4 class="mb-1"><?= htmlspecialchars($user_data['fullname']) ?></h4>
            <p class="text-muted mb-0"><small>Tham gia:
                    <?= date('d/m/Y', strtotime($user_data['created_at'])) ?></small></p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Cập nhật thông tin</h5>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form id="updateProfileForm" action="/btl/profile/index" method="POST" enctype="multipart/form-data">
                    <?php Csrf::insertHiddenField(); ?>
                    <input type="hidden" name="update_profile" value="1">

                    <div class="mb-3">
                        <label class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" name="fullname"
                            value="<?= htmlspecialchars($user_data['fullname']) ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="<?= htmlspecialchars($user_data['email']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary float-end">Lưu thông tin</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Đổi mật khẩu</h5>
                <form action="/btl/profile/index" method="POST" onsubmit="return validateChangePassword()">
                    <?php Csrf::insertHiddenField(); ?>
                    <input type="hidden" name="change_password" value="1">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu cũ</label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required
                            minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhập lại mật khẩu mới</label>
                        <input type="password" class="form-control" id="confirm_new_password" required minlength="6">
                        <div class="form-text text-danger mt-1" id="err-change-pwd"></div>
                    </div>
                    <button type="submit" class="btn btn-warning float-end">Đổi mật khẩu</button>
                </form>

                <script>
                    function validateChangePassword() {
                        let np = document.getElementById('new_password').value;
                        let cp = document.getElementById('confirm_new_password').value;
                        if (np !== cp) {
                            document.getElementById('err-change-pwd').innerText = "Mật khẩu xác nhận không khớp.";
                            return false;
                        }
                        document.getElementById('err-change-pwd').innerText = "";
                        return true;
                    }
                </script>
            </div>
        </div>
    </div>
</div>