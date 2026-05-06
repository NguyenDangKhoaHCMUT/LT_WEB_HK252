<section class="py-3 py-md-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Thông tin giao hàng</h4>
                    <form method="POST" action="/btl/checkout/place">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label fw-semibold">Tên khách hàng</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Nhập tên đầy đủ" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="0901234567" pattern="0\d{9}" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Địa chỉ giao hàng</label>
                            <textarea id="address" name="address" class="form-control" rows="3" placeholder="Số nhà, đường, quận, thành phố..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Đặt hàng</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-lg-top" style="top:80px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
                    <ul class="list-unstyled">
                        <?php if (!empty($cart['items'])): ?>
                            <?php foreach ($cart['items'] as $item): ?>
                                <li class="d-flex justify-content-between mb-2">
                                    <div>
                                        <div><?php echo htmlspecialchars($item['name'] ?? '', ENT_QUOTES); ?></div>
                                        <small class="text-muted">x<?php echo (int) $item['quantity']; ?></small>
                                    </div>
                                    <span class="fw-semibold text-end"><?php echo number_format((int) ($item['unit_price'] * $item['quantity']), 0, ',', '.'); ?> đ</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <hr>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <strong>Tổng tiền:</strong>
                            <strong class="text-primary fs-5"><?php echo number_format((int) ($cart['total_amount'] ?? 0), 0, ',', '.'); ?> đ</strong>
                        </div>
                    </div>

                    <a href="/btl/cart" class="btn btn-outline-secondary w-100">Quay lại giỏ hàng</a>
                </div>
            </div>
        </div>
    </div>
</section>
