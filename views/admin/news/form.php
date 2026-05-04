<?php
$post   = $post ?? [];
$errors = $errors ?? [];
$isEdit = $isEdit ?? false;

$val = fn(string $key, string $default = '') => htmlspecialchars($post[$key] ?? $default);
$err = fn(string $key) => isset($errors[$key])
    ? '<div class="invalid-feedback d-block mt-1">' . htmlspecialchars($errors[$key]) . '</div>'
    : '';

$statusOptions = [
    'draft'     => 'Bản nháp',
    'published' => 'Xuất bản',
    'hidden'    => 'Ẩn',
];
$formAction = $isEdit
    ? '/btl/adminNews/update/' . (int) ($post['id'] ?? 0)
    : '/btl/adminNews/store';
?>

<!-- Quill WYSIWYG CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
    .ql-editor { min-height: 320px; font-size: 1rem; font-family: 'Inter', sans-serif; }
    .ql-toolbar.ql-snow { border-radius: 0.375rem 0.375rem 0 0; border-color: #dee2e6; background: #f8f9fa; }
    .ql-container.ql-snow { border-radius: 0 0 0.375rem 0.375rem; border-color: #dee2e6; }
    .ql-container.ql-snow.is-invalid { border-color: #dc3545; }
    .drop-zone {
        border: 2px dashed #dee2e6; border-radius: 0.5rem;
        padding: 2rem; text-align: center; cursor: pointer;
        transition: all 0.2s; background: #f8f9fa;
    }
    .drop-zone:hover, .drop-zone.dragover { border-color: #0d6efd; background: #eef4ff; }
    .drop-zone img { max-height: 160px; object-fit: contain; border-radius: 0.375rem; }
    .thumb-preview { display: none; flex-direction: column; align-items: center; gap: 0.5rem; }
    .thumb-preview.show { display: flex; }
    .form-section { background: #fff; border: 1px solid #e9ecef; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; }
    .form-section-title { font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #6c757d; letter-spacing: 0.05em; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #f0f0f0; }
    .char-counter { font-size: 0.75rem; color: #adb5bd; text-align: right; margin-top: 2px; }
    /* Upload hint (dùng chung) */
    .upload-hint { background: #f0f7ff; border: 1px solid #bdd9f8; border-radius: 0.5rem; padding: 0.65rem 0.85rem; font-size: 0.8rem; }
    .upload-hint__header { font-weight: 600; color: #1a6bbf; margin-bottom: 0.4rem; }
    .upload-hint__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.3rem; }
    .upload-hint__list li { display: flex; align-items: flex-start; gap: 0.45rem; color: #444; line-height: 1.4; }
    .upload-hint__list li i { margin-top: 0.1rem; flex-shrink: 0; }
    /* Content image hint */
    .content-img-hint { background: #fffbea; border: 1px solid #fde68a; border-radius: 0.5rem; padding: 0.65rem 0.85rem; font-size: 0.8rem; margin-top: 0.6rem; }
    .content-img-hint__header { font-weight: 600; color: #92610a; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem; }
    .content-img-hint__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.3rem; }
    .content-img-hint__list li { display: flex; align-items: flex-start; gap: 0.45rem; color: #555; line-height: 1.4; }
    .content-img-hint__list li i { margin-top: 0.1rem; flex-shrink: 0; }
    .content-img-hint .badge-fmt { display: inline-flex; gap: 0.25rem; flex-wrap: wrap; margin-top: 0.15rem; }
    .content-img-hint .badge-fmt span { background: #fde68a; color: #78510a; font-size: 0.7rem; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 0.25rem; }
</style>

<!-- Page Header -->
<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark"><?= $isEdit ? 'Sửa bài viết' : 'Thêm bài viết mới' ?></h4>
        <p class="text-muted mb-0">
            <?= $isEdit
                ? 'Cập nhật nội dung bài viết <strong>' . $val('title') . '</strong>.'
                : 'Điền đầy đủ thông tin để tạo bài viết mới.' ?>
        </p>
    </div>
    <a href="/btl/adminNews/index" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="fa fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger rounded-3 d-flex align-items-center gap-2 mb-4">
        <i class="fa fa-circle-exclamation"></i>
        <?= htmlspecialchars($errors['general']) ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data" id="news-form" novalidate>

    <div class="row g-4">

        <!-- LEFT COLUMN: Main content -->
        <div class="col-lg-8">

            <!-- Basic Info -->
            <div class="form-section">
                <div class="form-section-title"><i class="fa fa-pen me-2"></i>Thông tin cơ bản</div>

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                        id="title" name="title"
                        value="<?= $val('title') ?>"
                        placeholder="Nhập tiêu đề bài viết..."
                        maxlength="255" required>
                    <div class="char-counter"><span id="title-count"><?= mb_strlen($post['title'] ?? '') ?></span>/255</div>
                    <?= $err('title') ?>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold">Slug (URL)</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted small">/btl/news/</span>
                        <input type="text" class="form-control font-monospace <?= isset($errors['slug']) ? 'is-invalid' : '' ?>"
                            id="slug" name="slug"
                            value="<?= $val('slug') ?>"
                            placeholder="tu-dong-tao-tu-tieu-de">
                    </div>
                    <div class="form-text">Để trống để tự động tạo từ tiêu đề.</div>
                    <?= $err('slug') ?>
                </div>

                <!-- Summary -->
                <div class="mb-0">
                    <label for="summary" class="form-label fw-semibold">Mô tả ngắn <span class="text-danger">*</span></label>
                    <textarea class="form-control <?= isset($errors['summary']) ? 'is-invalid' : '' ?>"
                        id="summary" name="summary"
                        rows="3"
                        placeholder="Tóm tắt nội dung bài viết (hiển thị ở trang danh sách)..."><?= $val('summary') ?></textarea>
                    <?= $err('summary') ?>
                </div>
            </div>

            <!-- Content WYSIWYG -->
            <div class="form-section">
                <div class="form-section-title"><i class="fa fa-align-left me-2"></i>Nội dung bài viết <span class="text-danger">*</span></div>
                <div id="quill-editor" class="<?= isset($errors['content']) ? 'is-invalid' : '' ?>"><?= $post['content'] ?? '' ?></div>
                <textarea name="content" id="content-hidden" class="d-none" required><?= $val('content') ?></textarea>
                <?= $err('content') ?>

                <!-- Content image hint -->
                <div class="content-img-hint">
                    <div class="content-img-hint__header">
                        <i class="fa fa-image"></i> Hướng dẫn chèn ảnh vào nội dung
                    </div>
                    <ul class="content-img-hint__list">
                        <li>
                            <i class="fa fa-check-circle text-success"></i>
                            <span>
                                <strong>Định dạng hỗ trợ:</strong>
                                <span class="badge-fmt">
                                    <span>JPG</span><span>JPEG</span><span>PNG</span><span>GIF</span><span>WEBP</span>
                                </span>
                            </span>
                        </li>
                        <li>
                            <i class="fa fa-triangle-exclamation text-warning"></i>
                            <span>Ảnh được <strong>nhúng trực tiếp</strong> vào nội dung — nên dùng ảnh <strong>dưới 500 KB</strong> để trang tải nhanh.</span>
                        </li>
                        <li>
                            <i class="fa fa-lightbulb text-warning"></i>
                            <span>Nhấn biểu tượng <strong>Image</strong> trên thanh công cụ để chọn ảnh từ máy tính.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Meta & settings -->
        <div class="col-lg-4">

            <!-- Publish Settings -->
            <div class="form-section">
                <div class="form-section-title"><i class="fa fa-sliders me-2"></i>Xuất bản</div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                    <select class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" id="status" name="status">
                        <?php foreach ($statusOptions as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($post['status'] ?? 'draft') === $key ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= $err('status') ?>
                </div>

                <?php if ($isEdit && !empty($post['created_at'])): ?>
                    <div class="small text-muted">
                        <i class="fa fa-clock me-1"></i>
                        Tạo lúc: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary fw-semibold" id="submit-btn">
                        <i class="fa <?= $isEdit ? 'fa-floppy-disk' : 'fa-plus-circle' ?> me-2"></i>
                        <?= $isEdit ? 'Lưu thay đổi' : 'Đăng bài viết' ?>
                    </button>
                    <a href="/btl/adminNews/index" class="btn btn-outline-secondary">Huỷ</a>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="form-section">
                <div class="form-section-title"><i class="fa fa-image me-2"></i>Ảnh thumbnail</div>

                <!-- Drop Zone -->
                <div class="drop-zone" id="drop-zone" title="Kéo & thả ảnh hoặc click để chọn file">
                    <div id="drop-zone-placeholder">
                        <i class="fa fa-cloud-arrow-up text-muted" style="font-size:2rem;"></i>
                        <p class="text-muted small mt-2 mb-0">Kéo & thả ảnh hoặc <strong>click để chọn</strong></p>
                        <p class="text-muted" style="font-size:0.72rem;">JPG, PNG, WEBP, GIF · Tối đa 2MB</p>
                    </div>
                    <div class="thumb-preview" id="thumb-preview">
                        <img id="thumb-img-preview" src="" alt="Preview">
                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" id="remove-thumb">
                            <i class="fa fa-times me-1"></i>Xoá ảnh
                        </button>
                    </div>
                </div>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="d-none">
                <?= $err('thumbnail') ?>

                <!-- OR separator -->
                <div class="d-flex align-items-center gap-2 my-3">
                    <hr class="flex-grow-1 m-0"><span class="text-muted small">hoặc nhập URL</span><hr class="flex-grow-1 m-0">
                </div>

                <!-- URL input -->
                <div>
                    <input type="text" class="form-control form-control-sm" id="thumbnail_url" name="thumbnail_url"
                        value="<?php
                            $thumb = $post['thumbnail'] ?? '';
                            echo htmlspecialchars((str_starts_with($thumb, 'http://') || str_starts_with($thumb, 'https://')) ? $thumb : '');
                        ?>"
                        placeholder="https://example.com/image.jpg">
                    <div class="form-text">Nhập URL ảnh từ internet (nếu không upload file).</div>
                </div>

                <!-- Existing local thumbnail hint (edit mode) -->
                <?php
                $existingThumb = $post['thumbnail'] ?? '';
                $isLocalThumb  = $existingThumb !== '' && !str_starts_with($existingThumb, 'http');
                if ($isEdit && $isLocalThumb): ?>
                    <div class="mt-3">
                        <p class="small text-muted mb-1">Ảnh hiện tại:</p>
                        <img src="/btl/<?= htmlspecialchars($existingThumb) ?>"
                            alt="Thumbnail hiện tại"
                            class="img-fluid rounded-2 border"
                            style="max-height:120px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- SEO -->
            <div class="form-section">
                <div class="form-section-title"><i class="fa fa-magnifying-glass-chart me-2"></i>SEO</div>

                <div class="mb-3">
                    <label for="seo_title" class="form-label fw-semibold small">SEO Title</label>
                    <input type="text" class="form-control form-control-sm <?= isset($errors['seo_title']) ? 'is-invalid' : '' ?>"
                        id="seo_title" name="seo_title"
                        value="<?= $val('seo_title') ?>"
                        placeholder="Mặc định: tiêu đề bài viết"
                        maxlength="255">
                    <div class="char-counter"><span id="seo-title-count"><?= mb_strlen($post['seo_title'] ?? '') ?></span>/255</div>
                    <?= $err('seo_title') ?>
                </div>

                <div class="mb-3">
                    <label for="seo_description" class="form-label fw-semibold small">SEO Description</label>
                    <textarea class="form-control form-control-sm"
                        id="seo_description" name="seo_description"
                        rows="3"
                        placeholder="Mô tả ngắn cho Google (≤160 ký tự)"
                        maxlength="160"><?= $val('seo_description') ?></textarea>
                    <div class="char-counter"><span id="seo-desc-count"><?= mb_strlen($post['seo_description'] ?? '') ?></span>/160</div>
                </div>

                <div class="mb-0">
                    <label for="seo_keywords" class="form-label fw-semibold small">SEO Keywords</label>
                    <input type="text" class="form-control form-control-sm"
                        id="seo_keywords" name="seo_keywords"
                        value="<?= $val('seo_keywords') ?>"
                        placeholder="từ khoá 1, từ khoá 2, ...">
                    <div class="form-text" style="font-size:0.72rem;">Phân cách bằng dấu phẩy.</div>
                </div>
            </div>

        </div><!-- /col-lg-4 -->
    </div><!-- /row -->
</form>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
(function () {
    /* ── Quill WYSIWYG ── */
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Nhập nội dung bài viết...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ align: [] }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    /* Show quick usage hints*/
    applyToolbarHoverHints(quill);

    function applyToolbarHoverHints(editor) {
        var toolbarModule = editor.getModule('toolbar');
        if (!toolbarModule || !toolbarModule.container) return;

        var toolbar = toolbarModule.container;
        var buttonHints = {
            'ql-bold': 'Bôi đậm văn bản đang chọn',
            'ql-italic': 'In nghiêng văn bản đang chọn',
            'ql-underline': 'Gạch chân văn bản đang chọn',
            'ql-strike': 'Gạch ngang văn bản đang chọn',
            'ql-blockquote': 'Chèn khối trích dẫn',
            'ql-code-block': 'Chèn khối mã nguồn',
            'ql-link': 'Chèn hoặc sửa liên kết',
            'ql-image': 'Chèn ảnh từ máy tính',
            'ql-clean': 'Xóa toàn bộ định dạng đã áp dụng'
        };

        Object.keys(buttonHints).forEach(function (className) {
            var btn = toolbar.querySelector('.' + className);
            if (!btn) return;
            btn.setAttribute('title', buttonHints[className]);
            btn.setAttribute('aria-label', buttonHints[className]);
        });

        var pickerHints = [
            { selector: '.ql-header .ql-picker-label', hint: 'Chọn cấp độ tiêu đề (H1, H2...)' },
            { selector: '.ql-color .ql-picker-label', hint: 'Đổi màu chữ' },
            { selector: '.ql-background .ql-picker-label', hint: 'Đổi màu nền chữ' },
            { selector: '.ql-list[value="ordered"]', hint: 'Tạo danh sách đánh số' },
            { selector: '.ql-list[value="bullet"]', hint: 'Tạo danh sách chấm tròn' },
            { selector: '.ql-align .ql-picker-label', hint: 'Căn lề đoạn văn' }
        ];

        pickerHints.forEach(function (item) {
            var el = toolbar.querySelector(item.selector);
            if (!el) return;
            el.setAttribute('title', item.hint);
            el.setAttribute('aria-label', item.hint);
        });
    }

    /* Sync Quill*/
    var form = document.getElementById('news-form');
    var hiddenContent = document.getElementById('content-hidden');
    form.addEventListener('submit', function () {
        hiddenContent.value = quill.root.innerHTML;
        var container = document.getElementById('quill-editor');
        if (quill.getText().trim() === '') {
            container.classList.add('is-invalid');
        }
    });

    /* ── Auto-slug from title ── */
    var titleInput = document.getElementById('title');
    var slugInput  = document.getElementById('slug');
    var userEditedSlug = slugInput.value !== '';

    titleInput.addEventListener('input', function () {
        document.getElementById('title-count').textContent = this.value.length;
        if (!userEditedSlug) {
            slugInput.value = slugify(this.value);
        }
    });
    slugInput.addEventListener('input', function () {
        userEditedSlug = this.value !== '';
    });

    function slugify(text) {
        var map = {
            'à':'a','á':'a','ả':'a','ã':'a','ạ':'a','ă':'a','ắ':'a','ặ':'a','ằ':'a','ẳ':'a','ẵ':'a',
            'â':'a','ấ':'a','ầ':'a','ẩ':'a','ẫ':'a','ậ':'a','đ':'d',
            'è':'e','é':'e','ẻ':'e','ẽ':'e','ẹ':'e','ê':'e','ế':'e','ề':'e','ể':'e','ễ':'e','ệ':'e',
            'ì':'i','í':'i','ỉ':'i','ĩ':'i','ị':'i',
            'ò':'o','ó':'o','ỏ':'o','õ':'o','ọ':'o','ô':'o','ố':'o','ồ':'o','ổ':'o','ỗ':'o','ộ':'o',
            'ơ':'o','ớ':'o','ờ':'o','ở':'o','ỡ':'o','ợ':'o',
            'ù':'u','ú':'u','ủ':'u','ũ':'u','ụ':'u','ư':'u','ứ':'u','ừ':'u','ử':'u','ữ':'u','ự':'u',
            'ỳ':'y','ý':'y','ỷ':'y','ỹ':'y','ỵ':'y'
        };
        return text.toLowerCase()
            .replace(/./g, function(c){ return map[c] || c; })
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    /* ── Char counters ── */
    function bindCounter(inputId, counterId, max) {
        var el = document.getElementById(inputId);
        var ct = document.getElementById(counterId);
        if (!el || !ct) return;
        el.addEventListener('input', function () {
            var len = this.value.length;
            ct.textContent = len;
            ct.style.color = len > max * 0.9 ? '#dc3545' : '#adb5bd';
        });
    }
    bindCounter('seo_title', 'seo-title-count', 255);
    bindCounter('seo_description', 'seo-desc-count', 160);

    /* ── Drag & Drop thumbnail ── */
    var dropZone  = document.getElementById('drop-zone');
    var fileInput = document.getElementById('thumbnail');
    var urlInput  = document.getElementById('thumbnail_url');
    var preview   = document.getElementById('thumb-preview');
    var previewImg = document.getElementById('thumb-img-preview');
    var placeholder = document.getElementById('drop-zone-placeholder');
    var removeBtn = document.getElementById('remove-thumb');

    dropZone.addEventListener('click', function (e) {
        if (e.target === removeBtn || removeBtn.contains(e.target)) return;
        fileInput.click();
    });

    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault(); this.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', function () {
        this.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault(); this.classList.remove('dragover');
        var files = e.dataTransfer.files;
        if (files.length) { fileInput.files = files; handleFileChange(files[0]); }
    });
    fileInput.addEventListener('change', function () {
        if (this.files.length) handleFileChange(this.files[0]);
    });

    function handleFileChange(file) {
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file ảnh (JPG, PNG, WEBP, GIF).'); return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ảnh quá lớn! Vui lòng chọn file dưới 2MB.'); return;
        }
        var reader = new FileReader();
        reader.onload = function (e) { showPreview(e.target.result); };
        reader.readAsDataURL(file);
        urlInput.value = '';
    }

    function showPreview(src) {
        previewImg.src = src;
        placeholder.style.display = 'none';
        preview.classList.add('show');
    }

    removeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        fileInput.value = '';
        previewImg.src  = '';
        preview.classList.remove('show');
        placeholder.style.display = '';
    });

    /* Auto-preview if URL entered */
    urlInput.addEventListener('input', function () {
        var url = this.value.trim();
        if (url.match(/^https?:\/\/.+/i)) {
            showPreview(url);
            fileInput.value = '';
        }
    });

    /* Pre-fill preview if URL already set (edit mode) */
    (function () {
        var url = urlInput.value.trim();
        if (url) showPreview(url);
    }());
}());
</script>
