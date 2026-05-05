<?php
// Đảm bảo các biến luôn có giá trị mặc định
$faqs ??= [];
$user_questions ??= [];
$pending_count ??= 0;
?>
<style>
    .faq-tab-btn {
        border-radius: 10px !important;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.55rem 1.2rem;
    }

    .faq-table th {
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
    }

    .faq-table td {
        vertical-align: middle;
        font-size: 0.92rem;
    }

    .faq-question-text {
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
    }

    .faq-answer-text {
        max-width: 260px;
        color: #555;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-published {
        background: #d1e7dd;
        color: #146c43;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .badge-hidden {
        background: #f8d7da;
        color: #842029;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .badge-answered {
        background: #d1e7dd;
        color: #146c43;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .user-avatar-sm {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .answer-preview {
        background: #f0f5ff;
        border-left: 3px solid #0d6efd;
        border-radius: 0 8px 8px 0;
        padding: 0.6rem 0.85rem;
        font-size: 0.88rem;
        color: #444;
        margin-top: 0.4rem;
        max-width: 340px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pending-dot {
        width: 8px;
        height: 8px;
        background: #ffc107;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }
</style>

<div class="container-fluid py-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Quản lý Hỏi / Đáp</h2>
                <p class="text-muted small mb-0">Quản lý câu hỏi thường gặp và phản hồi câu hỏi từ người dùng</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/btl/faq/index" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="fa fa-eye me-1"></i>Xem trang
                </a>
                <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#modalAddFaq">
                    <i class="fa fa-plus me-1"></i>Thêm FAQ
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4 gap-2" id="faqTabs">
        <li class="nav-item">
            <button class="nav-link faq-tab-btn active" data-bs-toggle="pill" data-bs-target="#tabFaqs">
                <i class="fa fa-list-ul me-1"></i>Câu hỏi thường gặp
                <span class="badge bg-secondary ms-1"><?= count($faqs) ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link faq-tab-btn" id="userQuestionsTabBtn" data-bs-toggle="pill"
                data-bs-target="#tabUserQuestions">
                <i class="fa fa-comments me-1"></i>Câu hỏi từ người dùng
                <?php if ($pending_count > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?= $pending_count ?></span>
                <?php else: ?>
                    <span class="badge bg-secondary ms-1"><?= count($user_questions) ?></span>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    <div class="tab-content">

        <!-- ========= Tab 1: FAQ List ========= -->
        <div class="tab-pane fade show active" id="tabFaqs">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <?php if (!empty($faqs)): ?>
                        <div class="table-responsive">
                            <table class="table faq-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width:40px">#</th>
                                        <th>Câu hỏi</th>
                                        <th>Câu trả lời</th>
                                        <th style="width:80px">Thứ tự</th>
                                        <th style="width:100px">Trạng thái</th>
                                        <th style="width:110px" class="pe-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($faqs as $i => $faq): ?>
                                        <tr>
                                            <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                                            <td>
                                                <div class="faq-question-text"
                                                    title="<?= htmlspecialchars($faq['question']) ?>">
                                                    <?= htmlspecialchars($faq['question']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="faq-answer-text" title="<?= htmlspecialchars($faq['answer']) ?>">
                                                    <?= htmlspecialchars($faq['answer']) ?>
                                                </div>
                                            </td>
                                            <td class="text-center text-muted"><?= $faq['sort_order'] ?></td>
                                            <td>
                                                <?php if ($faq['is_published']): ?>
                                                    <span class="badge-published">Hiển thị</span>
                                                <?php else: ?>
                                                    <span class="badge-hidden">Ẩn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pe-4">
                                                <button class="btn btn-sm btn-light me-1 rounded-3" title="Chỉnh sửa"
                                                    onclick="editFaq(<?= $faq['id'] ?>, <?= htmlspecialchars(json_encode($faq['question']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($faq['answer']), ENT_QUOTES) ?>, <?= $faq['sort_order'] ?>, <?= $faq['is_published'] ?>)">
                                                    <i class="fa fa-pen text-primary"></i>
                                                </button>
                                                <a href="/btl/adminFaq/delete?id=<?= $faq['id'] ?>"
                                                    class="btn btn-sm btn-light rounded-3" title="Xoá"
                                                    onclick="return confirm('Xác nhận xoá câu hỏi này?')">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa fa-inbox fa-3x mb-3 opacity-40"></i>
                            <p class="mb-2">Chưa có câu hỏi FAQ nào.</p>
                            <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#modalAddFaq">
                                <i class="fa fa-plus me-1"></i>Thêm FAQ đầu tiên
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ========= Tab 2: User Questions ========= -->
        <div class="tab-pane fade" id="tabUserQuestions" id="user-questions">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <?php if (!empty($user_questions)): ?>
                        <div class="table-responsive">
                            <table class="table faq-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width:50px">#</th>
                                        <th style="width:180px">Người hỏi</th>
                                        <th>Câu hỏi / Trả lời</th>
                                        <th style="width:120px">Thời gian</th>
                                        <th style="width:100px">Trạng thái</th>
                                        <th style="width:120px" class="pe-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_questions as $i => $q): ?>
                                        <tr>
                                            <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if (!empty($q['avatar'])): ?>
                                                        <img src="/btl/<?= htmlspecialchars($q['avatar']) ?>" class="user-avatar-sm"
                                                            alt="avatar">
                                                    <?php else: ?>
                                                        <div
                                                            class="user-avatar-sm bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                                            <i class="fa fa-user text-primary" style="font-size:0.85rem;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-semibold" style="font-size:0.88rem;">
                                                            <?= htmlspecialchars($q['fullname'] ?? 'Ẩn danh') ?>
                                                        </div>
                                                        <div class="text-muted" style="font-size:0.78rem;">
                                                            <?= htmlspecialchars($q['email'] ?? '') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="font-weight:500;font-size:0.9rem;max-width:320px;">
                                                    <?php if ($q['status'] === 'pending'): ?>
                                                        <span class="pending-dot"></span>
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($q['question']) ?>
                                                </div>
                                                <?php if ($q['status'] === 'answered' && !empty($q['answer'])): ?>
                                                    <div class="answer-preview" title="<?= htmlspecialchars($q['answer']) ?>">
                                                        <i
                                                            class="fa fa-reply text-primary me-1"></i><?= htmlspecialchars($q['answer']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted" style="font-size:0.82rem;">
                                                <?= date('d/m/Y', strtotime($q['created_at'])) ?><br>
                                                <span class="text-muted"><?= date('H:i', strtotime($q['created_at'])) ?></span>
                                            </td>
                                            <td>
                                                <?php if ($q['status'] === 'answered'): ?>
                                                    <span class="badge-answered">Đã trả lời</span>
                                                <?php else: ?>
                                                    <span class="badge-pending">Chờ duyệt</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pe-4">
                                                <button class="btn btn-sm btn-primary rounded-3 me-1" title="Trả lời"
                                                    onclick="openAnswerModal(<?= $q['id'] ?>, <?= htmlspecialchars(json_encode($q['question']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($q['answer'] ?? ''), ENT_QUOTES) ?>)">
                                                    <i class="fa fa-reply"></i>
                                                </button>
                                                <a href="/btl/adminFaq/deleteQuestion?id=<?= $q['id'] ?>"
                                                    class="btn btn-sm btn-light rounded-3" title="Xoá"
                                                    onclick="return confirm('Xác nhận xoá câu hỏi này?')">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa fa-comments fa-3x mb-3 opacity-40"></i>
                            <p class="mb-0">Chưa có câu hỏi nào từ người dùng.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- end tab-content -->
</div>


<!-- ===== Modal: Thêm FAQ ===== -->
<div class="modal fade" id="modalAddFaq" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="/btl/adminFaq/create">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm câu hỏi FAQ mới</h5>
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
                <input type="hidden" name="id" id="editFaqId">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Chỉnh sửa câu hỏi FAQ</h5>
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
                <input type="hidden" name="id" id="answerQuestionId">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fa fa-reply text-primary me-2"></i>Trả lời câu hỏi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Câu hỏi từ người dùng:</label>
                        <div class="bg-light rounded-3 p-3 text-dark fw-medium" id="answerQuestionText"
                            style="font-size:0.95rem;"></div>
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


<script>
    // Mở modal chỉnh sửa FAQ
    function editFaq(id, question, answer, sortOrder, isPublished) {
        document.getElementById('editFaqId').value = id;
        document.getElementById('editFaqQuestion').value = question;
        document.getElementById('editFaqAnswer').value = answer;
        document.getElementById('editFaqOrder').value = sortOrder;
        document.getElementById('editPublished').checked = isPublished == 1;
        new bootstrap.Modal(document.getElementById('modalEditFaq')).show();
    }

    // Mở modal trả lời câu hỏi user
    function openAnswerModal(id, question, currentAnswer) {
        document.getElementById('answerQuestionId').value = id;
        document.getElementById('answerQuestionText').textContent = question;
        document.getElementById('answerText').value = currentAnswer || '';
        // Switch to user questions tab
        new bootstrap.Modal(document.getElementById('modalAnswer')).show();
    }

    // Nếu URL có #user-questions thì active tab 2
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash === '#user-questions') {
            document.getElementById('userQuestionsTabBtn').click();
        }
    });
</script>