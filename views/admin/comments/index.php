<?php
$keyword = $keyword ?? '';
$status = $status ?? '';
$page = $page ?? 1;
$totalComments = $totalComments ?? 0;
$totalPages = $totalPages ?? 1;
$comments = $comments ?? [];
$perPage = $perPage ?? 10;

$statusLabelMap = [
    '' => 'Tất cả trạng thái',
    'approved' => 'Hiển thị',
    'hidden' => 'Đã ẩn',
    'pending' => 'Chờ trả lời',
];

$buildQuery = fn(array $extra) => http_build_query(array_merge(
    array_filter(['keyword' => $keyword, 'status' => $status]),
    $extra
));
?>

<!-- Page Header -->
<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 mb-1 fw-bold text-dark">Quản lý Bình luận</h2>
        <p class="text-muted mb-0">Xem, tìm kiếm, ẩn/hiện hoặc xoá bình luận của người dùng.</p>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/btl/adminComment/index" class="row g-2 align-items-center">

            <!-- Keyword -->
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 bg-light" name="keyword"
                        id="comment-search-input" value="<?= htmlspecialchars($keyword) ?>"
                        placeholder="Tìm theo nội dung, bài viết, tên người bình luận..." autocomplete="off">
                    <?php if ($keyword !== ''): ?>
                        <a href="/btl/adminComment/index?<?= $buildQuery(['keyword' => '']) ?>"
                            class="btn btn-outline-secondary" title="Xoá từ khoá">
                            <i class="fa fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-12 col-md-3">
                <select class="form-select" name="status" id="comment-status-filter" onchange="this.form.submit()">
                    <?php foreach ($statusLabelMap as $val => $label): ?>
                        <option value="<?= $val ?>" <?= $status === $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit -->
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa fa-search me-2"></i>Lọc
                </button>
            </div>

            <!-- Reset -->
            <?php if ($keyword !== '' || $status !== ''): ?>
                <div class="col-12 col-md-1">
                    <a href="/btl/adminComment/index" class="btn btn-outline-secondary w-100" title="Đặt lại">
                        <i class="fa fa-rotate-left"></i>
                    </a>
                </div>
            <?php endif; ?>

        </form>
    </div>
</div>

<!-- Active Filter Pills -->
<?php if ($keyword !== '' || $status !== ''): ?>
    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
        <span class="text-muted small fw-medium">Đang lọc:</span>
        <?php if ($keyword !== ''): ?>
            <span
                class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                <i class="fa fa-hashtag me-1"></i><?= htmlspecialchars($keyword) ?>
                <a href="/btl/adminComment/index?<?= $buildQuery(['keyword' => '']) ?>"
                    class="text-primary ms-2 text-decoration-none">×</a>
            </span>
        <?php endif; ?>
        <?php if ($status !== ''): ?>
            <span
                class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2">
                <i class="fa fa-filter me-1"></i><?= htmlspecialchars($statusLabelMap[$status] ?? $status) ?>
                <a href="/btl/adminComment/index?<?= $buildQuery(['status' => '']) ?>"
                    class="text-secondary ms-2 text-decoration-none">×</a>
            </span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fb;">
                <tr>
                    <th class="ps-4 py-3 text-muted fw-semibold"
                        style="font-size:.75rem;text-transform:uppercase;width:44px;">#</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;">Người
                        bình luận</th>
                    <th class="py-3 text-muted fw-semibold"
                        style="font-size:.75rem;text-transform:uppercase;min-width:180px;">Bài viết</th>
                    <th class="py-3 text-muted fw-semibold"
                        style="font-size:.75rem;text-transform:uppercase;min-width:260px;">Nội dung</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;">Trạng
                        thái</th>
                    <th class="py-3 text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;">Ngày tạo
                    </th>
                    <th class="py-3 text-muted fw-semibold text-end pe-4"
                        style="font-size:.75rem;text-transform:uppercase;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-3">
                                <i class="fa fa-comments text-muted" style="font-size:2.5rem;opacity:.4;"></i>
                                <p class="text-muted mt-3 mb-2">
                                    <?= ($keyword !== '' || $status !== '') ? 'Không tìm thấy bình luận phù hợp.' : 'Chưa có bình luận nào.' ?>
                                </p>
                                <?php if ($keyword !== '' || $status !== ''): ?>
                                    <a href="/btl/adminComment/index" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $stt = ($page - 1) * $perPage + 1; ?>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td class="ps-4 text-muted fw-medium"><?= $stt++ ?></td>

                            <!-- Author -->
                            <td>
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($comment['author_name']) ?></div>
                                <?php if (!empty($comment['rating'])): ?>
                                    <div class="small text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa<?= $i <= (int) $comment['rating'] ? 's' : 'r' ?> fa-star"
                                                style="font-size:.7rem;"></i>
                                        <?php endfor; ?>
                                        <span class="text-muted">(<?= (int) $comment['rating'] ?>/5)</span>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Post -->
                            <td>
                                <div class="fw-medium text-dark text-truncate" style="max-width:200px;"
                                    title="<?= htmlspecialchars($comment['post_title']) ?>">
                                    <?= htmlspecialchars($comment['post_title']) ?>
                                </div>
                                <a href="/btl/news/<?= rawurlencode($comment['post_slug']) ?>"
                                    class="small text-primary text-decoration-none" target="_blank">
                                    <i class="fa fa-arrow-up-right-from-square me-1" style="font-size:.65rem;"></i>Xem bài viết
                                </a>
                            </td>

                            <!-- Content -->
                            <td>
                                <div class="text-secondary small" style="max-width:300px;">
                                    <?php
                                    $preview = mb_substr(strip_tags($comment['content']), 0, 120);
                                    echo htmlspecialchars($preview) . (mb_strlen($comment['content']) > 120 ? '…' : '');
                                    ?>
                                </div>
                            </td>

                            <!-- Status -->
                            <td>
                                <?php
                                $badgeMap = [
                                    'approved' => ['Hiển thị', 'bg-success bg-opacity-10 text-success border-success'],
                                    'hidden' => ['Đã ẩn', 'bg-secondary bg-opacity-10 text-secondary border-secondary'],
                                    'pending' => ['Chờ trả lời', 'bg-warning bg-opacity-10 text-warning border-warning'],
                                ];
                                [$badgeLabel, $badgeClass] = $badgeMap[$comment['status']] ?? ['?', ''];
                                ?>
                                <span class="badge border px-3 py-2 fw-medium rounded-2 <?= $badgeClass ?>"
                                    style="font-size:.75rem;">
                                    <?= $badgeLabel ?>
                                </span>
                            </td>

                            <!-- Date -->
                            <td>
                                <div class="small text-dark"><?= date('d/m/Y', strtotime($comment['created_at'])) ?></div>
                                <div class="small text-muted"><?= date('H:i', strtotime($comment['created_at'])) ?></div>
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="/btl/adminComment/toggle_status/<?= (int) $comment['id'] ?>?<?= $buildQuery(['page' => $page]) ?>"
                                        class="btn btn-sm btn-light border <?= $comment['status'] === 'hidden' ? 'text-success' : 'text-warning' ?>"
                                        title="<?= $comment['status'] === 'hidden' ? 'Hiện bình luận' : 'Ẩn bình luận' ?>"
                                        onclick="return confirm('Bạn có chắc muốn đổi trạng thái bình luận này?')">
                                        <i class="fa <?= $comment['status'] === 'hidden' ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                                    </a>
                                    <a href="/btl/adminComment/delete/<?= (int) $comment['id'] ?>?<?= $buildQuery(['page' => $page]) ?>"
                                        class="btn btn-sm btn-light border text-danger" title="Xoá bình luận"
                                        onclick="return confirm('Bạn có chắc chắn muốn xoá bình luận này?')">
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

    <!-- Footer -->
    <div
        class="card-footer bg-white border-top d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 py-3 px-4">
        <span class="text-muted small">
            <?php if ($keyword !== '' || $status !== ''): ?>
                Tìm thấy <strong class="text-dark"><?= (int) $totalComments ?></strong> bình luận phù hợp.
            <?php else: ?>
                Tổng cộng <strong class="text-dark"><?= (int) $totalComments ?></strong> bình luận.
            <?php endif; ?>
        </span>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Phân trang bình luận">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="/btl/adminComment/index?<?= $buildQuery(['page' => max(1, $page - 1)]) ?>">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                    <?php
                    $range = 2;
                    $start = max(1, $page - $range);
                    $end = min($totalPages, $page + $range);
                    if ($start > 1): ?>
                        <li class="page-item"><a class="page-link"
                                href="/btl/adminComment/index?<?= $buildQuery(['page' => 1]) ?>">1</a></li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                    <?php endif; ?>
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/btl/adminComment/index?<?= $buildQuery(['page' => $i]) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($end < $totalPages): ?>
                        <?php if ($end < $totalPages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                        <li class="page-item"><a class="page-link"
                                href="/btl/adminComment/index?<?= $buildQuery(['page' => $totalPages]) ?>"><?= $totalPages ?></a>
                        </li>
                    <?php endif; ?>
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="/btl/adminComment/index?<?= $buildQuery(['page' => min($totalPages, $page + 1)]) ?>">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>