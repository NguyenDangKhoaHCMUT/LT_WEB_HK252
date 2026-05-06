<?php if (!defined('BASEPATH')) { /* noop */ } ?>
<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">Đơn hàng của tôi</h3>

        <?php if (empty($orders)): ?>
            <div class="alert alert-secondary">Bạn chưa có đơn hàng nào.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($orders as $o): ?>
                    <a href="/btl/orders/view/<?php echo $o['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <strong>#<?php echo $o['id']; ?></strong>
                            <div class="small text-secondary"><?php echo htmlspecialchars($o['customer_name'] ?: ($o['user_fullname'] ?? '')); ?></div>
                            <div class="small text-muted"><?php echo date('d/m/Y H:i', strtotime($o['created_at'] ?? 'now')); ?></div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold"><?php echo number_format($o['total_amount'] ?? 0); ?> đ</div>
                            <div class="small text-muted"><?php echo htmlspecialchars($statusLabels[$o['status']] ?? $o['status']); ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
