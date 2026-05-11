<?php
$modal_faq_page = isset($faq_page) ? (int) $faq_page : 1;
$modal_uq_page = isset($uq_page) ? (int) $uq_page : 1;
?>
<!-- ===== Modal: Thêm FAQ ===== -->
<div class="modal fade" id="modalAddFaq" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="/btl/adminFaq/create">
                <input type="hidden" name="faq_page" value="<?= $modal_faq_page ?>">
                <input type="hidden" name="uq_page" value="<?= $modal_uq_page ?>">
                <div class="modal-header border-0 pb-0">
                    <h3 class="h5 modal-title fw-bold">Thêm câu hỏi FAQ mới</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Câu hỏi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="question" required
                            placeholder="Nhập câu hỏi thường gặp...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Câu trả lời <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="answer" rows="5" required
                            placeholder="Nhập nội dung trả lời..."></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                            <input type="number" class="form-control" name="sort_order" value="0" min="0">
                            <small class="text-muted">Số nhỏ hơn hiển thị trước</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_published" id="addPublished"
                                    checked>
                                <label class="form-check-label fw-semibold" for="addPublished">Hiển thị công
                                    khai</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="fa fa-plus me-1"></i>Thêm FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ===== Modal: Sửa FAQ ===== -->
<div class="modal fade" id="modalEditFaq" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="/btl/adminFaq/update">
                <input type="hidden" name="faq_page" value="<?= $modal_faq_page ?>">
                <input type="hidden" name="uq_page" value="<?= $modal_uq_page ?>">
                <input type="hidden" name="id" id="editFaqId">
                <div class="modal-header border-0 pb-0">
                    <h3 class="h5 modal-title fw-bold">Chỉnh sửa câu hỏi FAQ</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Câu hỏi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="question" id="editFaqQuestion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Câu trả lời <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="answer" id="editFaqAnswer" rows="5" required></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                            <input type="number" class="form-control" name="sort_order" id="editFaqOrder" value="0"
                                min="0">
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_published" id="editPublished">
                                <label class="form-check-label fw-semibold" for="editPublished">Hiển thị công
                                    khai</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="fa fa-save me-1"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ===== Modal: Trả lời câu hỏi của người dùng ===== -->
<div class="modal fade" id="modalAnswer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="/btl/adminFaq/answer">
                <input type="hidden" name="faq_page" value="<?= $modal_faq_page ?>">
                <input type="hidden" name="uq_page" value="<?= $modal_uq_page ?>">
                <input type="hidden" name="id" id="answerQuestionId">
                <div class="modal-header border-0 pb-0">
                    <h3 class="h5 modal-title fw-bold"><i class="fa fa-reply text-primary me-2"></i>Trả lời câu hỏi</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Câu hỏi từ người dùng:</label>
                        <div class="bg-light rounded-3 p-3 text-dark fw-medium" id="answerQuestionText"
                            style="font-size:0.95rem; white-space: pre-wrap;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nội dung trả lời <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" name="answer" id="answerText" rows="5" required
                            placeholder="Nhập câu trả lời của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="fa fa-paper-plane me-1"></i>Gửi trả lời
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
