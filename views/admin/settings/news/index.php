<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark">Danh sách bài viết</h4>
        <p class="text-muted mb-0">Quản lý bài viết, tìm kiếm theo từ khóa và phân trang dữ liệu.</p>
    </div>
    <a href="/btl/adminNews/create" class="btn btn-primary">
        <i class="fa fa-plus me-2"></i>Thêm bài viết
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="/btl/adminNews/index" class="row g-3 align-items-end">
            <div class="col-md-8 col-lg-9">
                <label for="keyword" class="form-label fw-medium">Từ khóa tìm kiếm</label>
                <input
                    type="text"
                    class="form-control"
                    id="keyword"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword) ?>"
                    placeholder="Nhập tiêu đề, slug hoặc mô tả ngắn...">
            </div>
            <div class="col-md-4 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fa fa-search me-1"></i>Tìm kiếm
                </button>
                <a href="/btl/adminNews/index" class="btn btn-light border flex-fill">Làm mới</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">#</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Ảnh</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Tiêu đề</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Slug</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Trạng thái</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Ngày tạo</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold text-end pe-4" style="font-size: 0.75rem;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa fa-newspaper fs-1 mb-3"></i><br>
                            Không tìm thấy bài viết phù hợp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $stt = ($page - 1) * $perPage + 1; ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-muted"><?= $stt++ ?></td>
                            <td>
                                <?php if (!empty($post['thumbnail'])): ?>
                                    <?php $thumbnailSrc = preg_match('/^https?:\/\//i', $post['thumbnail']) ? $post['thumbnail'] : '/btl/' . ltrim($post['thumbnail'], '/'); ?>
                                    <img
                                        src="<?= htmlspecialchars($thumbnailSrc) ?>"
                                        alt="<?= htmlspecialchars($post['title']) ?>"
                                        style="width: 72px; height: 48px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                                        style="width: 72px; height: 48px;">
                                        <i class="fa fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($post['title']) ?></div>
                                <div class="small text-muted text-truncate" style="max-width: 280px;">
                                    <?= htmlspecialchars($post['summary']) ?>
                                </div>
                            </td>
                            <td>
                                <code><?= htmlspecialchars($post['slug']) ?></code>
                            </td>
                            <td>
                                <?php
                                $statusClass = 'secondary';
                                if ($post['status'] === 'published') {
                                    $statusClass = 'success';
                                } elseif ($post['status'] === 'draft') {
                                    $statusClass = 'warning';
                                } elseif ($post['status'] === 'hidden') {
                                    $statusClass = 'dark';
                                }
                                ?>
                                <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?> px-3 py-2 rounded-1 fw-medium">
                                    <?= htmlspecialchars(ucfirst($post['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-dark"><?= date('d/m/Y', strtotime($post['created_at'])) ?></div>
                                <div class="small text-muted"><?= date('H:i', strtotime($post['created_at'])) ?></div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <?php if ($post['status'] === 'published'): ?>
                                        <a href="/btl/news/<?= rawurlencode($post['slug']) ?>" class="btn btn-sm btn-light border text-primary" title="Xem ngoài trang" target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-light border text-muted" title="Chỉ xem ngoài trang khi bài đã published" disabled>
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="/btl/adminNews/edit/<?= $post['id'] ?>" class="btn btn-sm btn-light border text-warning" title="Sửa bài viết">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a
                                        href="/btl/adminNews/delete/<?= $post['id'] ?>"
                                        class="btn btn-sm btn-light border text-danger"
                                        title="Xóa bài viết"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white border-top d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">
        <span class="text-muted small">
            Tổng cộng <strong class="text-dark"><?= $totalPosts ?></strong> bài viết.
        </span>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Phân trang bài viết">
                <ul class="pagination pagination-sm mb-0">
                    <?php
                    $prevQuery = http_build_query(['keyword' => $keyword, 'page' => max(1, $page - 1)]);
                    $nextQuery = http_build_query(['keyword' => $keyword, 'page' => min($totalPages, $page + 1)]);
                    ?>
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="/btl/adminNews/index?<?= $prevQuery ?>">Trước</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/btl/adminNews/index?<?= http_build_query(['keyword' => $keyword, 'page' => $i]) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="/btl/adminNews/index?<?= $nextQuery ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
