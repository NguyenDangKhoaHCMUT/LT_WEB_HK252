<?php if (!defined('BASEPATH')) { /* noop */ } ?>
<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">Chi tiết đơn hàng #<?php echo $order['id']; ?></h3>

        <div class="mb-3">
            <strong>Trạng thái:</strong>
            <span class="badge bg-info text-dark"><?php echo htmlspecialchars($statusLabels[$order['status']] ?? $order['status']); ?></span>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h4>Thông tin khách hàng</h4>
                <p class="mb-1"><strong>Tên:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p class="mb-1"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                <p class="mb-1"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address']); ?></p>

                <h4 class="mt-4">Sản phẩm</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $it): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($it['name']); ?></td>
                                <td class="text-center"><?php echo (int)$it['quantity']; ?></td>
                                <td class="text-end"><?php echo number_format($it['unit_price']); ?> đ</td>
                                <td class="text-end"><?php echo number_format($it['unit_price'] * $it['quantity']); ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card bg-light p-3">
                    <h5 class="mb-3">Tổng cộng</h5>
                    <div class="d-flex justify-content-between">
                        <div>Số tiền:</div>
                        <div class="fw-semibold"><?php echo number_format($order['total_amount']); ?> đ</div>
                    </div>
                    <div class="mt-3">
                        <a href="/btl/orders/history" class="btn btn-outline-secondary btn-sm">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
