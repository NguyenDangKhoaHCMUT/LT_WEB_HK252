<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Chi tiết Đơn hàng #<?php echo (int) $order['id']; ?></h1>
    <a href="/btl/adminOrder/index" class="btn btn-light border">Quay lại danh sách</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
            <div class="mb-3">Thông tin người đặt</div>
                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? $order['user_fullname'] ?? '', ENT_QUOTES); ?></p>
                <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone'] ?? '', ENT_QUOTES); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo nl2br(htmlspecialchars($order['address'] ?? '', ENT_QUOTES)); ?></p>

                <hr>
                <div class="mb-3">Sản phẩm</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th style="width:100px;">Đơn giá</th>
                                <th style="width:100px;">Số lượng</th>
                                <th style="width:140px;" class="text-end">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $it): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($it['name'] ?? '', ENT_QUOTES); ?></td>
                                    <td><?php echo number_format((int) $it['unit_price'], 0, ',', '.'); ?> đ</td>
                                    <td><?php echo (int) $it['quantity']; ?></td>
                                    <td class="text-end fw-semibold"><?php echo number_format((int) ($it['unit_price'] * $it['quantity']), 0, ',', '.'); ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end">Tổng cộng:</td>
                                <td class="text-end fw-bold"><?php echo number_format((int) $order['total_amount'], 0, ',', '.'); ?> đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5>Trạng thái đơn hàng</h5>
                <form action="/btl/adminOrder/updateStatus/<?php echo (int) $order['id']; ?>" method="POST">
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <?php foreach ($statusLabels as $k => $v): ?>
                                <option value="<?php echo htmlspecialchars($k); ?>" <?php echo ($k === ($order['status'] ?? '')) ? 'selected' : ''; ?>><?php echo htmlspecialchars($v); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Cập nhật trạng thái</button>
                </form>
                <hr>
                <p class="text-muted small mb-0"><strong>Người tạo:</strong> <?php echo htmlspecialchars($order['user_email'] ?? '', ENT_QUOTES); ?></p>
                <p class="text-muted small"> <strong>Ngày tạo:</strong> <?php echo htmlspecialchars($order['created_at'] ?? '', ENT_QUOTES); ?></p>
            </div>
        </div>
    </div>
</div>
