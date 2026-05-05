<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0"><?php echo $formTitle; ?></h1>
    <a href="/btl/adminProduct/index" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên sản phẩm</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($product['name'] ?? '', ENT_QUOTES); ?>"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Danh mục</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?php echo (int) $category['id']; ?>"
                                <?php echo ((int) ($product['category_id'] ?? 0) === (int) $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Giá (VNĐ)</label>
                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        min="0"
                        value="<?php echo (int) ($product['price'] ?? 0); ?>"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tồn kho</label>
                    <input
                        type="number"
                        name="stock"
                        class="form-control"
                        min="0"
                        value="<?php echo (int) ($product['stock'] ?? 0); ?>"
                        required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" rows="5" class="form-control"><?php echo htmlspecialchars($product['description'] ?? '', ENT_QUOTES); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Hình ảnh sản phẩm</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-text">Định dạng cho phép: jpg, jpeg, png, webp, gif.</div>
                </div>

                <?php if (!empty($product['image'])): ?>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Ảnh hiện tại</label>
                        <div>
                            <img
                                src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>"
                                alt="Ảnh sản phẩm"
                                style="width: 140px; height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6;">
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> <?php echo $submitText; ?>
                </button>
                <a href="/btl/adminProduct/index" class="btn btn-light border">Huỷ</a>
            </div>
        </form>
    </div>
</div>
