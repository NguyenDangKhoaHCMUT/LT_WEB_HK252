<?php $post = $post ?? []; ?>
<?php $comments = $comments ?? []; ?>
<div class="py-2 py-lg-4">
    <div class="mb-4">
        <a href="/btl/news" class="text-decoration-none small text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách tin tức
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <img src="<?= htmlspecialchars($post['thumbnail'] ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop') ?>"
                    class="w-100 news-detail-image" alt="<?= htmlspecialchars($post['title']) ?>">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="fw-bold mb-3"><?= htmlspecialchars($post['title']) ?></h1>
                    <p class="lead text-secondary"><?= htmlspecialchars($post['summary']) ?></p>

                    <div class="d-flex flex-wrap gap-3 text-secondary small border-top border-bottom py-3 mb-4">
                        <span><i
                                class="fa-regular fa-user me-1"></i><?= htmlspecialchars($post['author_name']) ?></span>
                        <span><i
                                class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                        <span><i class="fa-regular fa-eye me-1"></i><?= (int) $post['view_count'] ?> lượt xem</span>
                        <span><i class="fa-regular fa-comments me-1"></i><?= (int) $post['comment_count'] ?> bình
                            luận</span>
                    </div>

                    <div class="news-content">
                        <?= $post['content'] ?>
                    </div>
                </div>
            </div>

            <section class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <div>
                            <h2 class="h4 fw-bold mb-1">Bình luận</h2>
                            <p class="text-secondary mb-0">Các bình luận đã được duyệt sẽ hiển thị tại đây.</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            <?= count($comments) ?> bình luận
                        </span>
                    </div>

                    <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')): ?>
                        <form method="POST" class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-semibold">Nội dung bình luận</label>
                                    <textarea class="form-control" id="content" name="content" rows="4"
                                        placeholder="Chia sẻ cảm nhận của bạn về bài viết này..." maxlength="1000"
                                        required></textarea>
                                    <div class="form-text">Tối đa 1000 ký tự. Nội dung sẽ được hiển thị an toàn.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="rating" class="form-label fw-semibold">Đánh giá sao (không bắt buộc)</label>
                                    <select class="form-select" id="rating" name="rating">
                                        <option value="">Không chấm sao</option>
                                        <option value="5">5 sao</option>
                                        <option value="4">4 sao</option>
                                        <option value="3">3 sao</option>
                                        <option value="2">2 sao</option>
                                        <option value="1">1 sao</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <small class="text-secondary">Chỉ thành viên đã đăng nhập mới có thể bình luận.</small>
                                    <button type="submit" name="submit_comment" class="btn btn-primary rounded-pill px-4">
                                        Gửi bình luận
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <div
                            class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                            <div>
                                <strong>Muốn tham gia thảo luận?</strong>
                                <div class="text-secondary small">Hãy đăng nhập để gửi bình luận cho bài viết này.</div>
                            </div>
                            <a href="/btl/auth/login" class="btn btn-outline-primary rounded-pill px-4">Đăng nhập</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning border mb-4">
                            <i class="fa-solid fa-circle-info me-2"></i> Tài khoản Quản trị viên chỉ có thể xem, không thể gửi bình luận.
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($comments)): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($comments as $comment): ?>
                                <div class="border rounded-4 p-3 p-lg-4">
                                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if (!empty($comment['avatar'])): ?>
                                                <img src="/btl/<?= htmlspecialchars($comment['avatar']) ?>"
                                                    alt="<?= htmlspecialchars($comment['author_name']) ?>"
                                                    class="rounded-circle news-comment-avatar">
                                            <?php else: ?>
                                                <div
                                                    class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center news-comment-avatar">
                                                    <i class="fa-regular fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($comment['author_name']) ?></div>
                                                <div class="small text-secondary">
                                                    <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($comment['rating'])): ?>
                                            <div class="text-warning small">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fa<?= $i <= (int) $comment['rating'] ? 's' : 'r' ?> fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-secondary">
                                        <?= nl2br(htmlspecialchars($comment['content'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fa-regular fa-message text-primary fs-1 mb-3"></i>
                            <h3 class="h5 fw-bold">Chưa có bình luận nào</h3>
                            <p class="text-secondary mb-0">Hãy trở thành người đầu tiên để lại ý kiến cho bài viết này.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Bài viết liên quan</h2>
                    <?php if (!empty($relatedPosts)): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($relatedPosts as $relatedPost): ?>
                                <a href="/btl/news/<?= rawurlencode($relatedPost['slug']) ?>" class="text-decoration-none">
                                    <div class="border rounded-4 p-3 news-related-item">
                                        <div class="fw-semibold text-dark mb-2"><?= htmlspecialchars($relatedPost['title']) ?>
                                        </div>
                                        <div class="small text-secondary mb-2"><?= htmlspecialchars($relatedPost['summary']) ?>
                                        </div>
                                        <div class="small text-primary">
                                            <?= date('d/m/Y', strtotime($relatedPost['created_at'])) ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-secondary mb-0">Chưa có thêm bài viết liên quan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
