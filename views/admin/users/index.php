<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="card-title mb-4">Danh sách Thành viên</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Member</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <div class="btn-group btn-group-sm">
                                    <a href="/btl/adminUser/reset_password/<?= $user['id'] ?>" class="btn btn-warning" title="Reset về 123456" onclick="return confirm('Bạn có chắc muốn reset mật khẩu thành 123456?')"><i class="fa fa-key"></i></a>
                                    <a href="/btl/adminUser/delete/<?= $user['id'] ?>" class="btn btn-danger" title="Xoá thành viên" onclick="return confirm('Xác nhận xoá thành viên này?')"><i class="fa fa-trash"></i></a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">Không thể tác động</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
