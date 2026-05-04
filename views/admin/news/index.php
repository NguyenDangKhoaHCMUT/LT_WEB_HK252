<?php
$keyword = $keyword ?? '';
$page = $page ?? 1;
$totalPosts = $totalPosts ?? 0;
$totalPages = $totalPages ?? 1;
$posts = $posts ?? [];
?>

<!-- Page Header -->
<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark">Quản lý Tin tức</h4>
        <p class="text-muted mb-0">Xem, tìm kiếm, thêm, sửa và xoá các bài viết trên website.</p>
    </div>
    <a href="/btl/adminNews/create" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold px-4 rounded-3">
        <i class="fa fa-plus-circle"></i> Thêm bài viết
    </a>
</div>

<!-- Search & Stats Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/btl/adminNews/index" class="row g-2 align-items-center">
            <div class="col-12 col-sm-8 col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control border-start-0 bg-light"
                        name="keyword"
                        id="admin-news-search"
                        value="<?= htmlspecialchars($keyword) ?>"
                        placeholder="Tìm kiếm theo tiêu đề, slug hoặc mô tả ngắn..."
                        autocomplete="off">
                    <?php if ($keyword !== ''): ?>
                        <a href="/btl/adminNews/index" class="btn btn-outline-secondary" title="Xoá tìm kiếm">
                            <i class="fa fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
            <div class="fw-bold fs-3 text-primary"><?= (int) $totalPosts ?></div>
            <div class="text-muted small"><?= $keyword !== '' ? 'Kết quả tìm kiếm' : 'Tổng bài viết' ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
            <div class="fw-bold fs-3 text-success"><?= count(array_filter($posts, fn($p) => $p['status'] === 'published')) ?></div>
            <div class="text-muted small">Đã xuất bản</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
            <div class="fw-bold fs-3 text-warning"><?= count(array_filter($posts, fn($p) => $p['status'] === 'draft')) ?></div>
            <div class="text-muted small">Bản nháp</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
            <div class="fw-bold fs-3 text-secondary"><?= count(array_filter($posts, fn($p) => $p['status'] === 'hidden')) ?></div>
            <div class="text-muted small">Đã ẩn</div>
        </div>
    </div>
</div>

<!-- Posts Table -->
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background: #f8f9fb;">
                    <th class="ps-4 py-3 text-muted fw-semibold" style="font-size:0.75rem; text-transform:uppercase; width:50px;">#</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:0.75rem; text-transform:uppercase; min-width:280px;">Bài viết</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:0.75rem; text-transform:uppercase;">Tác giả</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:0.75rem; text-transform:uppercase;">Trạng thái</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:0.75rem; text-transform:uppercase;">Ngày tạo</th>
                    <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size:0.75rem; text-transform:uppercase;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-3">
                                <i class="fa fa-newspaper text-muted" style="font-size: 2.5rem; opacity: 0.4;"></i>
                                <p class="text-muted mt-3 mb-2 fw-medium">
                                    <?= $keyword !== '' ? 'Không tìm thấy bài viết phù hợp.' : 'Chưa có bài viết nào.' ?>
                                </p>
                                <?php if ($keyword !== ''): ?>
                                    <a href="/btl/adminNews/index" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
                                <?php else: ?>
                                    <a href="/btl/adminNews/create" class="btn btn-sm btn-primary">Thêm bài viết đầu tiên</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $stt = ($page - 1) * 5 + 1; ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="ps-4 text-muted fw-medium"><?= $stt++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Thumbnail -->
                                    <?php if (!empty($post['thumbnail'])): ?>
                                        <img
                                            src="<?= htmlspecialchars($post['thumbnail']) ?>"
                                            alt="<?= htmlspecialchars($post['title']) ?>"
                                            class="rounded-2 object-fit-cover flex-shrink-0"
                                            style="width:60px; height:45px; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="rounded-2 bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:60px; height:45px;">
                                            <i class="fa fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="overflow-hidden">
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 280px;" title="<?= htmlspecialchars($post['title']) ?>">
                                            <?= htmlspecialchars($post['title']) ?>
                                        </div>
                                        <div class="small text-muted text-truncate font-monospace" style="max-width: 280px;">
                                            <?= htmlspecialchars($post['slug']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="small text-dark"><?= htmlspecialchars($post['author_name']) ?></span>
                            </td>
                            <td>
                                <?php
                                $statusMap = [
                                    'published' => ['label' => 'Xuất bản', 'class' => 'bg-success bg-opacity-10 text-success border-success'],
                                    'draft'     => ['label' => 'Nháp',    'class' => 'bg-warning bg-opacity-10 text-warning border-warning'],
                                    'hidden'    => ['label' => 'Ẩn',      'class' => 'bg-secondary bg-opacity-10 text-secondary border-secondary'],
                                ];
                                $s = $statusMap[$post['status']] ?? $statusMap['draft'];
                                ?>
                                <span class="badge border px-3 py-2 fw-medium rounded-2 <?= $s['class'] ?>" style="font-size:0.75rem;">
                                    <?= $s['label'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark"><?= date('d/m/Y', strtotime($post['created_at'])) ?></div>
                                <div class="small text-muted"><?= date('H:i', strtotime($post['created_at'])) ?></div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <?php if ($post['status'] === 'published'): ?>
                                        <a href="/btl/news/<?= rawurlencode($post['slug']) ?>"
                                            target="_blank"
                                            class="btn btn-sm btn-light border text-info"
                                            title="Xem bài viết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="/btl/adminNews/edit/<?= (int) $post['id'] ?>"
                                        class="btn btn-sm btn-light border text-primary"
                                        title="Sửa bài viết">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="/btl/adminNews/delete/<?= (int) $post['id'] ?>"
                                        class="btn btn-sm btn-light border text-danger"
                                        title="Xoá bài viết"
                                        onclick="return confirm('Bạn có chắc chắn muốn xoá bài viết «<?= htmlspecialchars(addslashes($post['title'])) ?>»?')">
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

    <!-- Footer: total + pagination -->
    <div class="card-footer bg-white border-top d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 py-3 px-4">
        <span class="text-muted small">
            <?php if ($keyword !== ''): ?>
                Tìm thấy <strong class="text-dark"><?= (int) $totalPosts ?></strong> bài viết
                cho từ khoá <strong class="text-dark">"<?= htmlspecialchars($keyword) ?>"</strong>.
            <?php else: ?>
                Tổng cộng <strong class="text-dark"><?= (int) $totalPosts ?></strong> bài viết.
            <?php endif; ?>
        </span>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Phân trang bài viết">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="/btl/adminNews/index?<?= http_build_query(['keyword' => $keyword, 'page' => max(1, $page - 1)]) ?>">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                    <?php
                    $range = 2;
                    $start = max(1, $page - $range);
                    $end   = min($totalPages, $page + $range);
                    if ($start > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/btl/adminNews/index?<?= http_build_query(['keyword' => $keyword, 'page' => 1]) ?>">1</a>
                        </li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/btl/adminNews/index?<?= http_build_query(['keyword' => $keyword, 'page' => $i]) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($end < $totalPages): ?>
                        <?php if ($end < $totalPages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="/btl/adminNews/index?<?= http_build_query(['keyword' => $keyword, 'page' => $totalPages]) ?>"><?= $totalPages ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="/btl/adminNews/index?<?= http_build_query(['keyword' => $keyword, 'page' => min($totalPages, $page + 1)]) ?>">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
