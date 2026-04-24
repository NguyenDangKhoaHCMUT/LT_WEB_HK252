<div class="mb-4">
    <h4 class="mb-1 fw-bold text-dark">Quản lý bình luận</h4>
    <p class="text-muted mb-0">Admin có thể xem, ẩn hoặc xóa bình luận của người dùng.</p>
</div>

<div class="card shadow-sm border-0 rounded overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">#</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Người bình luận</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Bài viết</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Nội dung</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Trạng thái</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Ngày tạo</th>
                    <th class="py-3 text-muted text-uppercase fw-semibold text-end pe-4" style="font-size: 0.75rem;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa fa-comments fs-1 mb-3"></i><br>
                            Chưa có bình luận nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $stt = ($page - 1) * $perPage + 1; ?>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-muted"><?= $stt++ ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($comment['author_name']) ?></div>
                                <?php if (!empty($comment['rating'])): ?>
                                    <div class="small text-warning"><?= (int) $comment['rating'] ?>/5 <i class="fa fa-star"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-medium text-dark"><?= htmlspecialchars($comment['post_title']) ?></div>
                                <a href="/btl/news/<?= rawurlencode($comment['post_slug']) ?>" class="small text-primary text-decoration-none" target="_blank">
                                    Xem bài viết
                                </a>
                            </td>
                            <td style="min-width: 280px;">
                                <div class="text-secondary"><?= nl2br(htmlspecialchars($comment['content'])) ?></div>
                            </td>
                            <td>
                                <?php if ($comment['status'] === 'approved'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-1 fw-medium">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-1 fw-medium">Đã ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-dark"><?= date('d/m/Y', strtotime($comment['created_at'])) ?></div>
                                <div class="small text-muted"><?= date('H:i', strtotime($comment['created_at'])) ?></div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a
                                        href="/btl/adminComment/toggle_status/<?= $comment['id'] ?>"
                                        class="btn btn-sm btn-light border text-warning"
                                        title="<?= $comment['status'] === 'hidden' ? 'Hiện bình luận' : 'Ẩn bình luận' ?>"
                                        onclick="return confirm('Bạn có chắc chắn muốn đổi trạng thái bình luận này?')">
                                        <i class="fa <?= $comment['status'] === 'hidden' ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                                    </a>
                                    <a
                                        href="/btl/adminComment/delete/<?= $comment['id'] ?>"
                                        class="btn btn-sm btn-light border text-danger"
                                        title="Xóa bình luận"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
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
            Tổng cộng <strong class="text-dark"><?= $totalComments ?></strong> bình luận.
        </span>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Phan trang binh luan">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="/btl/adminComment/index?page=<?= max(1, $page - 1) ?>">Trước</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/btl/adminComment/index?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="/btl/adminComment/index?page=<?= min($totalPages, $page + 1) ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
