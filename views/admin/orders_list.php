<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Quản lý Đơn hàng</h1>
    <a href="/btl/adminOrder/index" class="btn btn-light border">Làm mới</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="/btl/adminOrder/index" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo id, tên, điện thoại, email..." value="<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <?php foreach ($statusLabels as $k => $v): ?>
                        <option value="<?php echo htmlspecialchars($k); ?>" <?php echo ($k === ($status ?? '')) ? 'selected' : ''; ?>><?php echo htmlspecialchars($v); ?></option>
                    <?php endforeach; ?>
                </select>
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
                        <th style="width: 90px;">Mã</th>
                        <th>Khách hàng</th>
                        <th>Người đặt</th>
                        <th class="text-end">Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày</th>
                        <th style="width: 160px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có đơn hàng phù hợp.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td>#<?php echo (int) $o['id']; ?></td>
                                <td><?php echo htmlspecialchars($o['user_fullname'] ?? $o['user_email'], ENT_QUOTES); ?><br><small class="text-muted"><?php echo htmlspecialchars($o['user_email'] ?? '', ENT_QUOTES); ?></small></td>
                                <td><?php echo htmlspecialchars($o['customer_name'] ?? '', ENT_QUOTES); ?><br><small><?php echo htmlspecialchars($o['phone'] ?? '', ENT_QUOTES); ?></small></td>
                                <td class="text-end fw-semibold text-primary"><?php echo number_format((int) $o['total_amount'], 0, ',', '.'); ?> đ</td>
                                <td><?php echo htmlspecialchars($statusLabels[$o['status']] ?? $o['status'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($o['created_at'] ?? '', ENT_QUOTES); ?></td>
                                <td class="text-center">
                                    <a href="/btl/adminOrder/view/<?php echo (int) $o['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-eye"></i></a>
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
                <li class="page-item <?php echo ($i === (int) ($page ?? 1)) ? 'active' : ''; ?>">
                    <a class="page-link" href="/btl/adminOrder/index?keyword=<?php echo urlencode($keyword ?? ''); ?>&status=<?php echo urlencode($status ?? ''); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
