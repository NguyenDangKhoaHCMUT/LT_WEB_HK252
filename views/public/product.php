<section class="py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Sản phẩm công nghệ</h1>
            <p class="text-muted mb-0">Khám phá danh sách sản phẩm mới nhất của TechStore.</p>
        </div>
        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
            Tổng: <?php echo (int) ($totalProducts ?? 0); ?> sản phẩm
        </span>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="/btl/product/index" class="row g-2 align-items-center">
                <div class="col-md-8 col-lg-6">
                    <label for="keyword" class="visually-hidden">Từ khóa sản phẩm</label>
                    <input
                        id="keyword"
                        type="text"
                        name="keyword"
                        class="form-control"
                        value="<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES); ?>"
                        placeholder="Tìm theo tên, mô tả hoặc danh mục...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search me-1"></i>Tìm kiếm
                    </button>
                </div>
                <div class="col-auto">
                    <a href="/btl/product/index" class="btn btn-light border">Làm mới</a>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fa fa-box-open fa-2x text-muted mb-3"></i>
                <div class="h5 mb-2">Không tìm thấy sản phẩm phù hợp</div>
                <p class="text-muted mb-0">Vui lòng thử lại với từ khóa khác.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $item): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <article class="card h-100 border-0 shadow-sm">
                        <?php if (!empty($item['image'])): ?>
                            <img
                                src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>"
                                alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>"
                                class="card-img-top"
                                style="height: 210px; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                style="height: 210px;">
                                <i class="fa fa-image text-muted fa-2x"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <p class="text-muted small mb-1">
                                <?php echo htmlspecialchars($item['category_name'] ?? 'Chưa phân loại', ENT_QUOTES); ?>
                            </p>
                            <div class="h6 fw-semibold mb-2 text-truncate" title="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>">
                                <?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>
                            </div>
                            <p class="text-muted small mb-3" style="min-height: 38px;"><?php
                                $descriptionText = trim((string) ($item['description'] ?? ''));
                                if (function_exists('mb_strimwidth')) {
                                    $descriptionText = mb_strimwidth($descriptionText, 0, 80, '...');
                                } elseif (strlen($descriptionText) > 80) {
                                    $descriptionText = substr($descriptionText, 0, 80) . '...';
                                }
                                echo htmlspecialchars($descriptionText, ENT_QUOTES);
                            ?></p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="fw-bold text-primary">
                                    <?php echo number_format((int) ($item['price'] ?? 0), 0, ',', '.'); ?> đ
                                </div>
                                <a href="/btl/product/detail/<?php echo urlencode($item['slug'] ?? ''); ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
            <nav class="mt-4" aria-label="Phân trang sản phẩm">
                <ul class="pagination justify-content-center mb-0 flex-wrap gap-1">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i === (int) ($page ?? 1)) ? 'active' : ''; ?>">
                            <a
                                class="page-link"
                                href="/btl/product/index?keyword=<?php echo urlencode($keyword ?? ''); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>
