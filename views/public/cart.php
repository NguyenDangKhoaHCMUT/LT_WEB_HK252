<section class="py-3 py-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Giỏ hàng</h1>
            <p class="text-muted mb-0">Xem lại các sản phẩm trước khi thanh toán.</p>
        </div>
        <a href="/btl/product/index" class="btn btn-light border">Tiếp tục mua sắm</a>
    </div>

    <?php if (empty($cart['items'])): ?>
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-5">
                <i class="fa fa-shopping-cart fa-2x text-muted mb-3"></i>
                <div class="h5 mb-2">Giỏ hàng của bạn trống</div>
                <p class="text-muted mb-3">Hãy thêm sản phẩm để tiếp tục.</p>
                <a href="/btl/product/index" class="btn btn-primary">Xem sản phẩm</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th style="width:100px;">Giá</th>
                                    <th style="width:120px;">Số lượng</th>
                                    <th style="width:140px;" class="text-end">Tổng</th>
                                    <th style="width:80px;" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2 align-items-start">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>" alt="" style="width:56px; height:56px; object-fit:cover; border-radius:6px;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded" style="width:56px; height:56px; display:flex; align-items:center; justify-content:center;"><i class="fa fa-image text-muted"></i></div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($item['name'] ?? '', ENT_QUOTES); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($item['category_name'] ?? '', ENT_QUOTES); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format((int) $item['unit_price'], 0, ',', '.'); ?> đ</td>
                                        <td>
                                            <form method="POST" action="/btl/cart/update" class="d-flex">
                                                <input type="hidden" name="product_id" value="<?php echo (int) $item['product_id']; ?>">
                                                <input type="number" name="quantity" min="1" value="<?php echo (int) $item['quantity']; ?>" class="form-control form-control-sm" style="width:70px;">
                                                <button type="submit" class="btn btn-sm btn-light border ms-1">Cập nhật</button>
                                            </form>
                                        </td>
                                        <td class="text-end fw-semibold"><?php echo number_format((int) ($item['unit_price'] * $item['quantity']), 0, ',', '.'); ?> đ</td>
                                        <td class="text-center">
                                            <form method="POST" action="/btl/cart/remove" style="display:inline;">
                                                <input type="hidden" name="product_id" value="<?php echo (int) $item['product_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa sản phẩm này?');"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-lg-top" style="top:80px;">
                    <div class="card-body">
                        <div class="h5 fw-bold mb-3">Tóm tắt đơn hàng</div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tổng tiền:</span>
                                <span class="fw-semibold"><?php echo number_format((int) ($cart['total_amount'] ?? 0), 0, ',', '.'); ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Số sản phẩm:</span>
                                <span><?php echo (int) ($cart['count'] ?? 0); ?></span>
                            </div>
                        </div>
                        <a href="/btl/checkout" class="btn btn-primary w-100 mb-2">Thanh toán</a>
                        <a href="/btl/product/index" class="btn btn-outline-secondary w-100">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
