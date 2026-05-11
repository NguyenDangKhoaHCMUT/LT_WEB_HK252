<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Quản lý nội dung trang Giới thiệu</h2>
            <a href="/btl/home/about" target="_blank" class="btn btn-outline-primary">
                <i class="fa fa-eye me-2"></i>Xem trang thực tế
            </a>
        </div>
    </div>


    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="/btl/adminSetting/about" method="POST" enctype="multipart/form-data" id="settingsForm">

                <h3 class="h5 fw-bold text-primary mb-4 border-bottom pb-2">1. Văn bản Giới thiệu</h3>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tiêu đề chính</label>
                        <input type="text" class="form-control" name="about_title"
                            value="<?= htmlspecialchars($settings['about_title'] ?? '<span class="text-primary">Sứ mệnh</span> số hóa') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tiêu đề phụ</label>
                        <input type="text" class="form-control" name="about_subtitle"
                            value="<?= htmlspecialchars($settings['about_subtitle'] ?? 'Câu chuyện của chúng tôi') ?>">
                    </div>

                    <!-- Desc 1: Rich Text với Quill -->
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">
                            Nội dung đoạn 1
                            <span class="text-muted fw-normal">(hiển thị bên phải carousel — giới hạn 500 ký tự)</span>
                        </label>
                        <!-- Hidden input gửi lên server -->
                        <textarea name="about_desc_1" id="desc1_input"
                            class="d-none"><?= $settings['about_desc_1'] ?? '' ?></textarea>
                        <!-- Quill editor container -->
                        <div class="quill-wrapper">
                            <div id="quill_desc1"><?= $settings['about_desc_1'] ?? '' ?></div>
                        </div>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted char-count"><span id="desc1Count">0</span>/500</small>
                        </div>
                    </div>

                    <!-- Desc 2: Rich Text với Quill -->
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">
                            Nội dung đoạn 2
                            <span class="text-muted fw-normal">(hiển thị full-width bên dưới — giới hạn 3000 ký
                                tự)</span>
                        </label>
                        <textarea name="about_desc_2" id="desc2_input"
                            class="d-none"><?= $settings['about_desc_2'] ?? '' ?></textarea>
                        <div class="quill-wrapper">
                            <div id="quill_desc2"><?= $settings['about_desc_2'] ?? '' ?></div>
                        </div>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted char-count"><span id="desc2Count">0</span>/3000</small>
                        </div>
                    </div>
                </div>

                <h3 class="h5 fw-bold text-primary mb-4 border-bottom pb-2">2. Hình ảnh Slide (Carousel)</h3>
                <p class="text-muted mb-4">Bạn hãy tải lên ảnh mới từ máy tính, nền tảng sẽ tự động thay thế ảnh hiển
                    thị ngoài carousel.</p>

                <div class="row mb-4">
                    <!-- Banner 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="border rounded-3 p-3 bg-light">
                            <label class="form-label fw-bold">Banner 1</label>
                            <div class="mb-3">
                                <?php $img1 = $settings['about_carousel_1'] ?? ''; ?>
                                <img src="<?= $img1 ? $img1 : 'https://via.placeholder.com/600x400?text=No+Image' ?>"
                                    alt="Banner 1" class="img-fluid rounded border mb-2 object-fit-cover"
                                    style="height: 150px; width: 100%;">
                            </div>
                            <input type="file" class="form-control form-control-sm mb-2" name="about_carousel_1"
                                accept="image/*">
                        </div>
                    </div>

                    <!-- Banner 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="border rounded-3 p-3 bg-light">
                            <label class="form-label fw-bold">Banner 2</label>
                            <div class="mb-3">
                                <?php $img2 = $settings['about_carousel_2'] ?? ''; ?>
                                <img src="<?= $img2 ? $img2 : 'https://via.placeholder.com/600x400?text=No+Image' ?>"
                                    alt="Banner 2" class="img-fluid rounded border mb-2 object-fit-cover"
                                    style="height: 150px; width: 100%;">
                            </div>
                            <input type="file" class="form-control form-control-sm mb-2" name="about_carousel_2"
                                accept="image/*">
                        </div>
                    </div>

                    <!-- Banner 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="border rounded-3 p-3 bg-light">
                            <label class="form-label fw-bold">Banner 3</label>
                            <div class="mb-3">
                                <?php $img3 = $settings['about_carousel_3'] ?? ''; ?>
                                <img src="<?= $img3 ? $img3 : 'https://via.placeholder.com/600x400?text=No+Image' ?>"
                                    alt="Banner 3" class="img-fluid rounded border mb-2 object-fit-cover"
                                    style="height: 150px; width: 100%;">
                            </div>
                            <input type="file" class="form-control form-control-sm mb-2" name="about_carousel_3"
                                accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">
                        <i class="fa fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toolbarOptions = [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['clean']
        ];

        // Khởi tạo Quill cho desc1
        const quill1 = new Quill('#quill_desc1', {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: 'Nhập nội dung đoạn 1...'
        });

        // Khởi tạo Quill cho desc2
        const quill2 = new Quill('#quill_desc2', {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: 'Nhập nội dung đoạn 2...'
        });

        // Hàm đếm ký tự (text thuần, không đếm HTML tags)
        function updateCounter(quill, counterId, limit) {
            const text = quill.getText().trim();
            const len = text.length;
            const el = document.getElementById(counterId);
            if (el) {
                el.textContent = len;
                el.closest('.char-count').classList.toggle('over', len > limit);
            }
        }

        // Cập nhật counter ban đầu
        updateCounter(quill1, 'desc1Count', 500);
        updateCounter(quill2, 'desc2Count', 3000);

        // Lắng nghe thay đổi
        quill1.on('text-change', () => updateCounter(quill1, 'desc1Count', 500));
        quill2.on('text-change', () => updateCounter(quill2, 'desc2Count', 3000));

        // Khi submit form: copy HTML từ Quill vào hidden textarea
        document.getElementById('settingsForm').addEventListener('submit', () => {
            document.getElementById('desc1_input').value = quill1.root.innerHTML;
            document.getElementById('desc2_input').value = quill2.root.innerHTML;
        });
    });
</script>