<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="row g-0">
                <div class="col-md-5">
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" class="img-fluid rounded-start" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:100%; min-height:320px;">
                            <i class="fa fa-image fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-7">
                    <div class="card-body d-flex flex-column h-100">
                        <h1 class="h4 fw-bold"><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h1>
                        <p class="text-muted mb-2"><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại', ENT_QUOTES); ?></p>
                        <div class="mb-3 fw-bold text-primary fs-5"><?php echo number_format((int) ($product['price'] ?? 0), 0, ',', '.'); ?> đ</div>

                        <div class="mb-3 text-muted"><?php echo nl2br(htmlspecialchars($product['description'] ?? '', ENT_QUOTES)); ?></div>


                        <form id="addToCartForm" class="mt-auto d-flex gap-2 align-items-center">
                            <input type="hidden" name="product_id" value="<?php echo (int) ($product['id'] ?? 0); ?>">
                            <div class="input-group" style="width:140px;">
                                <input type="number" name="quantity" min="1" value="1" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm vào giỏ</button>
                        </form>

                        <script>
                            document.getElementById('addToCartForm').addEventListener('submit', async function(e) {
                                e.preventDefault();
                                const formData = new FormData(this);
                                try {
                                    const response = await fetch('/btl/cart/add', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    });

                                    if (response.status === 401) {
                                        // User not logged in
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Chưa đăng nhập',
                                            text: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.',
                                            confirmButtonText: 'Đăng nhập',
                                            showCancelButton: true,
                                            cancelButtonText: 'Hủy'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = '/btl/auth/login';
                                            }
                                        });
                                        return;
                                    }

                                    if (response.ok) {
                                        // Update cart badge count
                                        const cartResponse = await fetch('/btl/api/cart-count.php');
                                        if (cartResponse.ok) {
                                            const data = await cartResponse.json();
                                            const badge = document.querySelector('.navbar .badge');
                                            if (badge) {
                                                badge.textContent = data.count || 0;
                                            }
                                        }
                                        // Show success message
                                        Swal.fire({
                                            toast: true,
                                            position: 'bottom-end',
                                            icon: 'success',
                                            title: 'Thêm vào giỏ hàng thành công!',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                    } else {
                                        Swal.fire({
                                            toast: true,
                                            position: 'bottom-end',
                                            icon: 'error',
                                            title: 'Lỗi: không thể thêm vào giỏ',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                    }
                                } catch (error) {
                                    Swal.fire({
                                        toast: true,
                                        position: 'bottom-end',
                                        icon: 'error',
                                        title: 'Lỗi: không thể thêm vào giỏ',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="fw-semibold">Sản phẩm liên quan</div>
                <ul class="list-unstyled mt-3">
                    <?php if (!empty($related)): ?>
                        <?php foreach ($related as $r): ?>
                            <li class="mb-2">
                                <a href="/btl/product/detail/<?php echo urlencode($r['slug'] ?? ''); ?>" class="text-decoration-none"><?php echo htmlspecialchars($r['name'] ?? '', ENT_QUOTES); ?></a>
                                <div class="small text-muted"><?php echo number_format((int)($r['price'] ?? 0), 0, ',', '.'); ?> đ</div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-muted">Không có sản phẩm liên quan.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
