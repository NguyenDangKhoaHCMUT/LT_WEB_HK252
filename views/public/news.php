<?php
$isSearching = !empty($keyword);
$hasFilters = $isSearching || !empty($category) || (($sort ?? 'latest') !== 'latest');
$listingPosts = $featuredPost ? array_slice($posts, 1) : $posts;
$selectedCategoryName = $selectedCategory['name'] ?? 'Tất cả danh mục';
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

if (!empty($category)) {
    $listingQuery['category'] = $category;
}

if (($sort ?? 'latest') !== 'latest') {
    $listingQuery['sort'] = $sort;
}

$buildPageQuery = function ($targetPage) use ($listingQuery) {
    return http_build_query(array_merge($listingQuery, ['page' => $targetPage]));
};

$showPagination = $totalPages >= 1 && ($featuredPost || !empty($listingPosts));
$heroTitle = $isSearching ? 'Kết quả tìm kiếm bài viết' : 'Tin tức công nghệ và đánh giá sản phẩm';
$sectionTitle = $hasFilters ? 'Bài viết phù hợp với bộ lọc' : 'Bài viết mới nhất';
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
                <?php elseif ($hasFilters): ?>
                    Khám phá các bài viết theo danh mục <strong><?= htmlspecialchars($selectedCategoryName) ?></strong>
                    và cách sắp xếp <strong><?= htmlspecialchars($sortLabel) ?></strong>.
                <?php else: ?>
                    Cập nhật tin công nghệ, review thiết bị và kinh nghiệm mua sắm phù hợp cho khách hàng của TechStore.
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-4">
            <div class="news-hero-summary">
                <div class="news-hero-stat">
                    <span class="news-hero-stat-value"><?= (int) $totalPosts ?></span>
                    <span class="news-hero-stat-label"><?= $isSearching ? 'Kết quả' : 'Bài viết' ?></span>
                </div>
                <div class="news-hero-stat">
                    <span class="news-hero-stat-value"><?= count($categories) ?></span>
                    <span class="news-hero-stat-label">Danh mục</span>
                </div>
                <div class="news-hero-stat">
                    <span class="news-hero-stat-value"><?= htmlspecialchars($sortLabel) ?></span>
                    <span class="news-hero-stat-label">Hiển thị</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-5 news-search-card">
        <div class="card-body">
            <form method="GET" action="/btl/news" class="row g-3 align-items-end news-search-form" data-news-search-form>
                <div class="col-lg-5">
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
                    <label for="category" class="form-label fw-semibold">Danh mục</label>
                    <select class="form-select" id="category" name="category" data-news-search-filter>
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $item): ?>
                            <option value="<?= htmlspecialchars($item['slug']) ?>" <?= ($category ?? '') === $item['slug'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item['name']) ?> (<?= (int) $item['post_count'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="sort" class="form-label fw-semibold">Sắp xếp</label>
                    <select class="form-select" id="sort" name="sort" data-news-search-filter>
                        <option value="latest" <?= ($sort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="most_viewed" <?= ($sort ?? '') === 'most_viewed' ? 'selected' : '' ?>>Xem nhiều</option>
                        <option value="most_commented" <?= ($sort ?? '') === 'most_commented' ? 'selected' : '' ?>>Bình luận nhiều</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary news-action-btn">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Tìm kiếm
                        </button>
                        <button type="button" class="btn btn-outline-secondary news-action-btn" data-news-search-clear>
                            <i class="fa-solid fa-rotate-left me-2"></i>Đặt lại
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center gap-2 news-filter-pills">
                        <span class="news-filter-pill">
                            <i class="fa-regular fa-folder-open"></i>
                            <?= htmlspecialchars($selectedCategoryName) ?>
                        </span>
                        <span class="news-filter-pill">
                            <i class="fa-solid fa-arrow-down-wide-short"></i>
                            <?= htmlspecialchars($sortLabel) ?>
                        </span>
                        <?php if (!empty($keyword)): ?>
                            <span class="news-filter-pill">
                                <i class="fa-solid fa-hashtag"></i>
                                <?= htmlspecialchars($keyword) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="small text-secondary mt-2 news-search-status" data-news-search-status>
                        Gõ từ khóa hoặc thay đổi bộ lọc để cập nhật danh sách bài viết.
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($featuredPost): ?>
        <div class="card border-0 shadow-sm overflow-hidden mb-5 news-featured-card">
            <div class="row g-0">
                <div class="col-lg-6">
                    <img
                        src="<?= htmlspecialchars($featuredPost['thumbnail'] ?: 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop') ?>"
                        class="w-100 h-100 news-cover-image"
                        alt="<?= htmlspecialchars($featuredPost['title']) ?>">
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-4 p-xl-5">
                        <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2 mb-3">Bài nổi bật tuần này</span>
                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($featuredPost['title']) ?></h2>
                        <p class="text-secondary mb-4"><?= htmlspecialchars($featuredPost['summary']) ?></p>

                        <?php if (!empty($featuredPost['categories'])): ?>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <?php foreach ($featuredPost['categories'] as $item): ?>
                                    <span class="badge text-bg-light border"><?= htmlspecialchars($item) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

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
                <p class="text-uppercase fw-semibold text-primary small mb-2">Chuyên mục bài viết</p>
                <h2 class="h3 fw-bold mb-1"><?= htmlspecialchars($sectionTitle) ?></h2>
                <p class="text-secondary mb-0">
                    Tổng hợp review sản phẩm, tin công nghệ mới và kinh nghiệm chọn mua thiết bị dành cho khách hàng TechStore.
                </p>
            </div>
            <div class="news-section-count">
                <span><?= (int) $totalPosts ?></span> bài viết
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4 news-post-grid">
        <?php if (!empty($listingPosts)): ?>
            <?php foreach ($listingPosts as $post): ?>
                <div class="col-lg-6">
                    <article class="card h-100 border-0 shadow-sm news-post-card">
                        <img
                            src="<?= htmlspecialchars($post['thumbnail'] ?: 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop') ?>"
                            class="card-img-top news-card-image"
                            alt="<?= htmlspecialchars($post['title']) ?>">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <?php foreach ($post['categories'] as $item): ?>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($item) ?></span>
                                <?php endforeach; ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <?= htmlspecialchars($post['status']) ?>
                                </span>
                            </div>

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
                                Hãy thử lại với từ khóa khác, đổi danh mục hoặc trở về danh sách tất cả bài viết.
                            </p>
                            <a href="/btl/news" class="btn btn-outline-primary news-action-btn px-4">Xem toàn bộ bài viết</a>
                        <?php else: ?>
                            <h3 class="fw-bold">Chưa có bài viết nào được xuất bản</h3>
                            <!-- <p class="text-secondary mb-0">
                                Admin có thể tạo bài viết mới từ trang quản trị để nội dung được hiển thị tại đây.
                            </p> -->
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
