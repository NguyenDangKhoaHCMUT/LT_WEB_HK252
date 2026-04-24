<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-1 fw-bold text-dark"><?= $isEdit ? 'Sửa bài viết' : 'Thêm bài viết mới' ?></h4>
        <p class="text-muted mb-0">Nhập thông tin bài viết, validate dữ liệu và cấu hình thumbnail theo nhu cầu.</p>
    </div>
    <a href="/btl/adminNews/index" class="btn btn-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i>Quay lại
    </a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
<?php endif; ?>

<?php
$thumbnailPreview = '';
if (!empty($post['thumbnail'])) {
    $thumbnailPreview = preg_match('/^https?:\/\//i', $post['thumbnail'])
        ? $post['thumbnail']
        : '/btl/' . ltrim($post['thumbnail'], '/');
}
?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form
            method="POST"
            action="/btl/adminNews/<?= $isEdit ? 'update/' . $post['id'] : 'store' ?>"
            enctype="multipart/form-data">

            <input type="hidden" name="current_thumbnail" value="<?= htmlspecialchars($post['thumbnail'] ?? '') ?>">

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-medium">Tiêu đề bài viết</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($post['title']) ?>"
                            required>
                        <?php if (!empty($errors['title'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label fw-medium">Slug</label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-control <?= !empty($errors['slug']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($post['slug']) ?>"
                            placeholder="Để trống sẽ tự sinh từ tiêu đề">
                        <?php if (!empty($errors['slug'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['slug']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="summary" class="form-label fw-medium">Mô tả ngắn</label>
                        <textarea
                            id="summary"
                            name="summary"
                            rows="3"
                            class="form-control <?= !empty($errors['summary']) ? 'is-invalid' : '' ?>"
                            required><?= htmlspecialchars($post['summary']) ?></textarea>
                        <?php if (!empty($errors['summary'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['summary']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-medium">Nội dung bài viết</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="10"
                            class="form-control <?= !empty($errors['content']) ? 'is-invalid' : '' ?>"
                            required><?= htmlspecialchars($post['content']) ?></textarea>
                        <?php if (!empty($errors['content'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['content']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-medium">Trạng thái</label>
                        <select id="status" name="status" class="form-select <?= !empty($errors['status']) ? 'is-invalid' : '' ?>">
                            <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="hidden" <?= $post['status'] === 'hidden' ? 'selected' : '' ?>>Hidden</option>
                        </select>
                        <?php if (!empty($errors['status'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['status']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label fw-medium">Tải ảnh thumbnail</label>
                        <input
                            type="file"
                            id="thumbnail"
                            name="thumbnail"
                            data-thumbnail-file
                            class="form-control <?= !empty($errors['thumbnail']) ? 'is-invalid' : '' ?>"
                            accept=".jpg,.jpeg,.png,.gif,.webp">
                        <?php if (!empty($errors['thumbnail'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['thumbnail']) ?></div>
                        <?php endif; ?>
                        <div class="form-text">Chấp nhận jpg, jpeg, png, gif, webp. Tối đa 2MB. Nếu chọn file thì file sẽ được ưu tiên sử dụng.</div>
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail_url" class="form-label fw-medium">Hoặc nhập URL ảnh</label>
                        <input
                            type="url"
                            id="thumbnail_url"
                            name="thumbnail_url"
                            data-thumbnail-url
                            class="form-control"
                            value="<?= preg_match('/^https?:\/\//i', $post['thumbnail'] ?? '') ? htmlspecialchars($post['thumbnail']) : '' ?>"
                            placeholder="https://example.com/anh-bai-viet.jpg">
                        <div class="form-text">Admin có thể dán link ảnh ngoài nếu muốn dùng ảnh từ nguồn khác.</div>
                    </div>

                    <?php if ($thumbnailPreview !== ''): ?>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Ảnh hiện tại</label>
                            <div class="border rounded p-2 bg-light">
                                <img
                                    src="<?= htmlspecialchars($thumbnailPreview) ?>"
                                    alt="<?= htmlspecialchars($post['title']) ?>"
                                    class="img-fluid rounded">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="seo_title" class="form-label fw-medium">SEO title</label>
                        <input
                            type="text"
                            id="seo_title"
                            name="seo_title"
                            class="form-control <?= !empty($errors['seo_title']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($post['seo_title']) ?>">
                        <?php if (!empty($errors['seo_title'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['seo_title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="seo_description" class="form-label fw-medium">SEO description</label>
                        <textarea id="seo_description" name="seo_description" rows="3" class="form-control"><?= htmlspecialchars($post['seo_description']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="seo_keywords" class="form-label fw-medium">SEO keywords</label>
                        <input
                            type="text"
                            id="seo_keywords"
                            name="seo_keywords"
                            class="form-control"
                            value="<?= htmlspecialchars($post['seo_keywords']) ?>"
                            placeholder="vd: laptop, cong nghe, review">
                    </div>

                    <?php if (!empty($post['created_at'])): ?>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Ngày tạo</label>
                            <input type="text" class="form-control" value="<?= date('d/m/Y H:i:s', strtotime($post['created_at'])) ?>" readonly>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="/btl/adminNews/index" class="btn btn-light border">Hủy</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save me-1"></i><?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var fileInput = document.querySelector('[data-thumbnail-file]');
        var urlInput = document.querySelector('[data-thumbnail-url]');

        if (!fileInput || !urlInput) {
            return;
        }

        function syncThumbnailInputs() {
            var hasFile = fileInput.files && fileInput.files.length > 0;
            var hasUrl = urlInput.value.trim() !== '';

            urlInput.disabled = hasFile;
            fileInput.disabled = hasUrl;
        }

        fileInput.addEventListener('change', syncThumbnailInputs);
        urlInput.addEventListener('input', syncThumbnailInputs);

        syncThumbnailInputs();
    });
</script>
