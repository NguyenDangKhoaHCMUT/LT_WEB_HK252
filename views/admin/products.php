<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Quản lý Sản phẩm</h1>
    <a href="/btl/adminProduct/create" class="btn btn-primary">
        <i class="fa fa-plus me-1"></i> Thêm sản phẩm
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="/btl/adminProduct/index" class="row g-2 align-items-center">
            <div class="col-md-6 col-lg-5">
                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="Tìm theo tên, slug, mô tả, danh mục..."
                    value="<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fa fa-search me-1"></i> Tìm kiếm
                </button>
            </div>
            <div class="col-auto">
                <a href="/btl/adminProduct/index" class="btn btn-light border">Làm mới</a>
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
                        <th style="width: 90px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th class="text-end">Giá</th>
                        <th class="text-center">Tồn kho</th>
                        <th style="width: 160px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Chưa có sản phẩm phù hợp.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $item): ?>
                            <tr>
                                <td>#<?php echo (int) $item['id']; ?></td>
                                <td>
                                    <?php if (!empty($item['image'])): ?>
                                        <img
                                            src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>"
                                            alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>"
                                            style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-light border rounded"
                                            style="width: 56px; height: 56px;">
                                            <i class="fa fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($item['slug'], ENT_QUOTES); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($item['category_name'] ?? 'Chưa phân loại', ENT_QUOTES); ?></td>
                                <td class="text-end fw-semibold text-primary"><?php echo number_format((int) $item['price'], 0, ',', '.'); ?> đ</td>
                                <td class="text-center"><?php echo (int) $item['stock']; ?></td>
                                <td class="text-center">
                                    <a href="/btl/adminProduct/edit/<?php echo (int) $item['id']; ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a
                                        href="/btl/adminProduct/delete/<?php echo (int) $item['id']; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này không?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
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
                    <a class="page-link" href="/btl/adminProduct/index?keyword=<?php echo urlencode($keyword ?? ''); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>