<?php
$keyword = $keyword ?? '';
$sort = $sort ?? 'latest';
$featuredPost = $featuredPost ?? null;

$isSearching = !empty($keyword);
$hasFilters = $isSearching || (($sort ?? 'latest') !== 'latest');
$posts = $posts ?? [];
$hasFeaturedPost = !empty($featuredPost);
$listingPosts = $hasFeaturedPost ? array_slice($posts, 1) : $posts;

$totalPosts = $totalPosts ?? count($posts);
$totalPages = isset($totalPages) ? (int) $totalPages : ($totalPosts > 0 ? 1 : 0);
$page = isset($page) ? (int) $page : 1;

$sortLabelMap = [
    'latest' => 'Mới nhất',
    'most_viewed' => 'Xem nhiều',
    'most_commented' => 'Bình luận nhiều',
];
$sortLabel = $sortLabelMap[$sort ?? 'latest'] ?? 'Mới nhất';
$listingQuery = [];

if (!empty($keyword)) {
    $listingQuery['keyword'] = $keyword;
}

if (($sort ?? 'latest') !== 'latest') {
    $listingQuery['sort'] = $sort ?? 'latest';
}

$buildPageQuery = function ($targetPage) use ($listingQuery) {
    return http_build_query(array_merge($listingQuery, ['page' => $targetPage]));
};

$showPagination = $totalPages >= 1 && ($hasFeaturedPost || !empty($listingPosts));
$heroTitle = $isSearching ? 'Kết quả tìm kiếm bài viết' : 'Tin tức công nghệ và đánh giá sản phẩm';
$sectionTitle = $hasFilters ? 'Kết quả' : 'Bài viết mới nhất';
?>

<section class="py-2 py-lg-4 news-live-root" data-news-live-search-root data-news-url="/btl/news">
    <div class="row align-items-end g-4 mb-3 news-hero-block">
        <div class="col-lg-8">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">Blog / Tin tức TechStore</span>
            <h1 class="display-6 fw-bold mb-2"><?= htmlspecialchars($heroTitle) ?></h1>
            <p class="text-secondary mb-0 news-search-meta">
                <?php if ($isSearching): ?>
                    Tìm thấy <strong><?= (int) $totalPosts ?></strong> bài viết cho từ khóa
                    <strong>"<?= htmlspecialchars($keyword) ?>"</strong>.
                <?php else: ?>
                    Cập nhật tin công nghệ, review thiết bị và kinh nghiệm mua sắm phù hợp cho khách hàng của TechStore.
                <?php endif; ?>
            </p>
        </div>
        
    </div>

    <div class="card border-0 shadow-sm mb-5 news-search-card">
        <div class="card-body">
            <form method="GET" action="/btl/news" class="row g-3 align-items-center news-search-form" data-news-search-form>
                <div class="col-lg-6">
                    <label for="keyword" class="form-label fw-semibold">Từ khóa</label>
                    <input
                        type="text"
                        class="form-control"
                        id="keyword"
                        name="keyword"
                        data-news-search-input
                        value="<?= htmlspecialchars($keyword) ?>"
                        autocomplete="off"
                        placeholder="Nhập tiêu đề, mô tả, nội dung hoặc từ khóa công nghệ...">
                </div>
                <div class="col-lg-3">
                    <label for="sort" class="form-label fw-semibold">Sắp xếp</label>
                    <select class="form-select" id="sort" name="sort" data-news-search-filter>
                        <option value="latest" <?= ($sort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="most_viewed" <?= ($sort ?? '') === 'most_viewed' ? 'selected' : '' ?>>Xem nhiều</option>
                        <option value="most_commented" <?= ($sort ?? '') === 'most_commented' ? 'selected' : '' ?>>Bình luận nhiều</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <div class="news-hero-summary">
                        <div class="news-hero-stat">
                            <span class="news-hero-stat-value"><?= (int) $totalPosts ?></span>
                            <span class="news-hero-stat-label"><?= $isSearching ? 'Kết quả' : 'Bài viết' ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="d-flex flex-wrap align-items-center gap-2 news-filter-pills">
                        <?php if (!empty($keyword)): ?>
                            <span class="news-filter-pill">
                                <i class="fa-solid fa-hashtag"></i>
                                <?= htmlspecialchars($keyword) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($featuredPost)): ?>
        <div class="card border-0 shadow-sm overflow-hidden mb-5 news-featured-card">
            <div class="row g-0">
                <div class="col-lg-6">
                    <img
                        src="<?= htmlspecialchars($featuredPost['thumbnail'] ?: 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop') ?>"
                        class="w-100 h-100 news-cover-image"
                        alt="<?= htmlspecialchars($featuredPost['title']) ?>"
                        loading="eager">
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-4 p-xl-5">
                        <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2 mb-3">Bài nổi bật tuần này</span>
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($featuredPost['title']) ?></h2>
                        <p class="text-secondary mb-4"><?= htmlspecialchars($featuredPost['summary']) ?></p>

                        <div class="news-meta-list mb-4">
                            <span class="news-meta-item">
                                <i class="fa-regular fa-user"></i>
                                <?= htmlspecialchars($featuredPost['author_name']) ?>
                            </span>
                            <span class="news-meta-item">
                                <i class="fa-regular fa-calendar"></i>
                                <?= date('d/m/Y', strtotime($featuredPost['created_at'])) ?>
                            </span>
                            <span class="news-meta-item">
                                <i class="fa-regular fa-eye"></i>
                                <?= (int) $featuredPost['view_count'] ?> lượt xem
                            </span>
                            <span class="news-meta-item">
                                <i class="fa-regular fa-comments"></i>
                                <?= (int) $featuredPost['comment_count'] ?> bình luận
                            </span>
                        </div>

                        <a href="/btl/news/<?= rawurlencode($featuredPost['slug']) ?>" class="btn btn-primary news-action-btn px-4">
                            Đọc bài viết
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($listingPosts)): ?>
        <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-4 news-section-heading">
            <div>
                <h2 class="h3 fw-bold mb-1"><?= htmlspecialchars($sectionTitle) ?></h2>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4 news-post-grid">
        <?php if (!empty($listingPosts)): ?>
            <?php foreach ($listingPosts as $post): ?>
                <div class="col-lg-6">
                    <article class="card h-100 border-0 shadow-sm news-post-card">
                        <div class="news-card-image-wrap">
                            <img
                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E"
                                data-src="<?= htmlspecialchars($post['thumbnail'] ?: 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop') ?>"
                                class="card-img-top news-card-image lazy-img"
                                alt="<?= htmlspecialchars($post['title']) ?>"
                                loading="lazy">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="h4 fw-bold mb-3"><?= htmlspecialchars($post['title']) ?></h3>
                            <p class="text-secondary flex-grow-1"><?= htmlspecialchars($post['summary']) ?></p>

                            <div class="news-meta-list mb-4">
                                <span class="news-meta-item">
                                    <i class="fa-regular fa-user"></i>
                                    <?= htmlspecialchars($post['author_name']) ?>
                                </span>
                                <span class="news-meta-item">
                                    <i class="fa-regular fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                </span>
                                <span class="news-meta-item">
                                    <i class="fa-regular fa-eye"></i>
                                    <?= (int) $post['view_count'] ?> lượt xem
                                </span>
                                <span class="news-meta-item">
                                    <i class="fa-regular fa-comments"></i>
                                    <?= (int) $post['comment_count'] ?> bình luận
                                </span>
                            </div>

                            <a href="/btl/news/<?= rawurlencode($post['slug']) ?>" class="btn btn-outline-primary news-action-btn align-self-start px-4">
                                Xem chi tiết
                            </a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        <?php elseif (!$featuredPost): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <i class="fa-regular fa-newspaper text-primary fs-1 mb-3"></i>
                        <?php if ($isSearching || $hasFilters): ?>
                            <h3 class="fw-bold">Không tìm thấy bài viết phù hợp</h3>
                            <p class="text-secondary mb-4">
                                Hãy thử lại với từ khóa khác hoặc trở về danh sách tất cả bài viết.
                            </p>
                            <a href="/btl/news" class="btn btn-outline-primary news-action-btn px-4">Xem toàn bộ bài viết</a>
                        <?php else: ?>
                            <h3 class="fw-bold">Chưa có bài viết nào được xuất bản</h3>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($showPagination): ?>
        <nav aria-label="Phân trang bài viết" class="mt-5">
            <ul class="pagination justify-content-center news-pagination">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="/btl/news?<?= $buildPageQuery(max(1, $page - 1)) ?>">
                        <i class="fa-solid fa-angle-left me-2"></i>Trước
                    </a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="/btl/news?<?= $buildPageQuery($i) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="/btl/news?<?= $buildPageQuery(min($totalPages, $page + 1)) ?>">
                        Sau<i class="fa-solid fa-angle-right ms-2"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</section>
