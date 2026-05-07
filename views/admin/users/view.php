<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0 fw-bold text-dark">Chi tiết Thành viên</h4>
    <a href="/btl/adminUser/index" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Quay lại</a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0 rounded">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-4">Cập nhật thông tin</h5>

                <form action="/btl/adminUser/update/<?= $member['id'] ?>" method="POST">
                    <?php Csrf::insertHiddenField(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Họ và tên</label>
                        <input type="text" name="fullname" class="form-control"
                            value="<?= htmlspecialchars($member['fullname']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($member['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Ngày tham gia</label>
                        <input type="text" class="form-control"
                            value="<?= date('d/m/Y H:i:s', strtotime($member['created_at'])) ?>" readonly disabled>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Trạng thái</label>
                        <div>
                            <?php if ($member['status'] === 'active'): ?>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-1 fw-medium">Hoạt
                                    động (Active)</span>
                            <?php else: ?>
                                <span
                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-1 fw-medium">Đã
                                    khóa (Locked)</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= Csrf::addTokenToUrl('/btl/adminUser/reset_password/' . $member['id']) ?>" 
                           class="btn btn-warning" 
                           onclick="return confirm('Bạn có chắc muốn reset mật khẩu thành 123456?')">
                           <i class="fa fa-key"></i> Reset mật khẩu
                        </a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save"></i> Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
