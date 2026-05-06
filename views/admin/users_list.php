<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Quản lý Người dùng</h1>
    <a href="/btl/adminUser/index" class="btn btn-light border">Làm mới</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="/btl/adminUser/index" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc email" value="<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search me-1"></i> Tìm</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th style="width: 220px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có người dùng phù hợp.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#<?php echo (int)$u['id']; ?></td>
                                <td><?php echo htmlspecialchars($u['fullname'] ?? '', ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($u['email'] ?? '', ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($u['role'] ?? '', ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($u['status'] ?? '', ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($u['created_at'] ?? '', ENT_QUOTES); ?></td>
                                <td class="text-center">
                                    <a href="/btl/adminUser/view/<?php echo (int)$u['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-eye"></i></a>
                                    <a href="/btl/adminUser/toggleStatus/<?php echo (int)$u['id']; ?>" class="btn btn-sm btn-outline-warning me-1" onclick="return confirm('Xác nhận thay đổi trạng thái người dùng?');">Trạng thái</a>
                                    <a href="/btl/adminUser/resetPassword/<?php echo (int)$u['id']; ?>" class="btn btn-sm btn-outline-secondary me-1" onclick="return confirm('Đặt lại mật khẩu về 123456?');">Reset PW</a>
                                    <a href="/btl/adminUser/delete/<?php echo (int)$u['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá người dùng?');">Xoá</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (($totalPages ?? 1) > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center mb-0">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i === (int)($page ?? 1)) ? 'active' : ''; ?>">
                    <a class="page-link" href="/btl/adminUser/index?keyword=<?php echo urlencode($keyword ?? ''); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
