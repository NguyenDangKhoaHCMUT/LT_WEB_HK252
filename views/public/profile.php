<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm text-center p-3">
            <h5 class="card-title">Ảnh đại diện</h5>
            <div class="mb-3">
                <img src="/btl/<?= !empty($user_data['avatar']) ? $user_data['avatar'] : 'public/images/default-avatar.png' ?>" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;" alt="Avatar">
            </div>
            <h4><?= htmlspecialchars($user_data['fullname'] ?: $user_data['username']) ?></h4>
            <p class="text-muted mb-1">@<?= htmlspecialchars($user_data['username']) ?> (<?= ucfirst($user_data['role']) ?>)</p>
            <p class="text-muted"><small>Tham gia: <?= date('d/m/Y', strtotime($user_data['created_at'])) ?></small></p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Cập nhật thông tin</h5>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="/btl/profile/index" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($user_data['fullname']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user_data['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tải ảnh đại diện mới</label>
                        <input type="file" class="form-control" name="avatar" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Đổi mật khẩu</h5>
                <form action="/btl/profile/index" method="POST">
                    <input type="hidden" name="change_password" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu cũ</label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="new_password" required minlength="6">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
