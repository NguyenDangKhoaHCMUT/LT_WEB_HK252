<?php
// Đảm bảo các biến luôn có giá trị mặc định
$faqs ??= [];
$user_questions ??= [];
$pending_count ??= 0;
$faq_page = isset($faq_page) ? (int) $faq_page : 1;
$faq_total = isset($faq_total) ? (int) $faq_total : 0;
$faq_total_pages = isset($faq_total_pages) ? (int) $faq_total_pages : 1;
$faq_row_start = isset($faq_row_start) ? (int) $faq_row_start : 0;
$uq_page = isset($uq_page) ? (int) $uq_page : 1;
$uq_total = isset($uq_total) ? (int) $uq_total : 0;
$uq_total_pages = isset($uq_total_pages) ? (int) $uq_total_pages : 1;
$uq_row_start = isset($uq_row_start) ? (int) $uq_row_start : 0;

$buildAdminFaqListQuery = function (array $overrides) use ($faq_page, $uq_page) {
    $fp = array_key_exists('faq_page', $overrides) ? (int) $overrides['faq_page'] : $faq_page;
    $up = array_key_exists('uq_page', $overrides) ? (int) $overrides['uq_page'] : $uq_page;
    return http_build_query(
        array_filter(
            [
                'faq_page' => $fp > 1 ? $fp : null,
                'uq_page' => $up > 1 ? $up : null,
            ],
            function ($v) {
                return $v !== null;
            },
        ),
    );
};
$_faq_list_qs = $buildAdminFaqListQuery([]);
$_faq_list_qs_suffix = $_faq_list_qs !== '' ? '&' . htmlspecialchars($_faq_list_qs) : '';
?>
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
                <span class="badge bg-secondary ms-1"><?= $faq_total ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link faq-tab-btn" id="userQuestionsTabBtn" data-bs-toggle="pill"
                data-bs-target="#tabUserQuestions">
                <i class="fa fa-comments me-1"></i>Câu hỏi từ người dùng
                <?php if ($pending_count > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?= $pending_count ?></span>
                <?php else: ?>
                    <span class="badge bg-secondary ms-1"><?= $uq_total ?></span>
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
                                            <td class="ps-4 text-muted"><?= $faq_row_start + $i + 1 ?></td>
                                            <td>
                                                <div class="faq-question-text"
                                                    title="<?= htmlspecialchars($faq['question']) ?>">
                                                    <?= nl2br(htmlspecialchars($faq['question'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="faq-answer-text" title="<?= htmlspecialchars($faq['answer']) ?>">
                                                    <?= nl2br(htmlspecialchars($faq['answer'])) ?>
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
                                                <a href="/btl/adminFaq/detail?id=<?= $faq['id'] ?>&type=faq<?= $_faq_list_qs_suffix ?>"
                                                   class="btn btn-sm btn-light me-1 rounded-3" title="Xem chi tiết">
                                                    <i class="fa fa-eye text-info"></i>
                                                </a>
                                                <button class="btn btn-sm btn-light me-1 rounded-3" title="Chỉnh sửa"
                                                    onclick="editFaq(<?= $faq['id'] ?>, <?= htmlspecialchars(json_encode($faq['question']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($faq['answer']), ENT_QUOTES) ?>, <?= $faq['sort_order'] ?>, <?= $faq['is_published'] ?>)">
                                                    <i class="fa fa-pen text-primary"></i>
                                                </button>
                                                <a href="/btl/adminFaq/delete?<?= htmlspecialchars(http_build_query(array_filter(['id' => $faq['id'], 'faq_page' => $faq_page > 1 ? $faq_page : null, 'uq_page' => $uq_page > 1 ? $uq_page : null], function ($v) { return $v !== null; }))) ?>"
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
                        <?php if ($faq_total > 0 && $faq_total_pages > 1): ?>
                            <div class="card-footer bg-white border-0 pt-0 pb-3">
                                <nav aria-label="Phân trang FAQ">
                                    <ul class="pagination pagination-sm justify-content-center flex-wrap mb-1">
                                        <li class="page-item <?= $faq_page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link"
                                                href="/btl/adminFaq/index?<?= htmlspecialchars($buildAdminFaqListQuery(['faq_page' => max(1, $faq_page - 1)])) ?>">Trước</a>
                                        </li>
                                        <?php for ($p = 1; $p <= $faq_total_pages; $p++): ?>
                                            <li class="page-item <?= $p === $faq_page ? 'active' : '' ?>">
                                                <a class="page-link"
                                                    href="/btl/adminFaq/index?<?= htmlspecialchars($buildAdminFaqListQuery(['faq_page' => $p])) ?>"><?= $p ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= $faq_page >= $faq_total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link"
                                                href="/btl/adminFaq/index?<?= htmlspecialchars($buildAdminFaqListQuery(['faq_page' => min($faq_total_pages, $faq_page + 1)])) ?>">Sau</a>
                                        </li>
                                    </ul>
                                    <p class="text-center text-muted small mb-0">Trang <?= $faq_page ?> / <?= $faq_total_pages ?></p>
                                </nav>
                            </div>
                        <?php endif; ?>
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
        <div class="tab-pane fade" id="tabUserQuestions">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <?php if (!empty($user_questions)): ?>
                        <div class="table-responsive">
                            <table class="table faq-table mb-0" style="min-width: 1000px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="min-width: 60px;">#</th>
                                        <th style="min-width: 240px;">NGƯỜI HỎI</th>
                                        <th style="min-width: 200px;">CÂU HỎI</th>
                                        <th style="min-width: 200px;">TRẢ LỜI</th>
                                        <th style="min-width: 130px;">THỜI GIAN</th>
                                        <th style="min-width: 110px;">TRẠNG THÁI</th>
                                        <th style="min-width: 120px;" class="pe-4">THAO TÁC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_questions as $i => $q): ?>
                                        <tr>
                                            <td class="ps-4 text-muted"><?= $uq_row_start + $i + 1 ?></td>
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
                                                        <div class="text-muted" style="font-size:0.78rem;"
                                                            title="<?= htmlspecialchars($q['email'] ?? '') ?>">
                                                            <?php
                                                            $email = $q['email'] ?? '';
                                                            echo htmlspecialchars(mb_strlen($email) > 30 ? mb_substr($email, 0, 27) . '...' : $email);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="faq-user-question-text"
                                                    title="<?= htmlspecialchars($q['question']) ?>">
                                                    <?php if ($q['status'] === 'pending'): ?>
                                                        <span class="pending-dot"></span>
                                                    <?php endif; ?>
                                                    <?= nl2br(htmlspecialchars($q['question'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($q['status'] === 'answered' && !empty($q['answer'])): ?>
                                                    <div class="answer-preview"
                                                        title="<?= htmlspecialchars($q['answer']) ?>">
                                                        <?= nl2br(htmlspecialchars($q['answer'])) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small"><em>Chưa trả lời</em></span>
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
                                                    <span class="badge-pending">Chờ trả lời</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pe-4">
                                                <a href="/btl/adminFaq/detail?id=<?= $q['id'] ?>&type=user<?= $_faq_list_qs_suffix ?>"
                                                   class="btn btn-sm btn-light me-1 rounded-3" title="Xem chi tiết">
                                                    <i class="fa fa-eye text-info"></i>
                                                </a>
                                                <button class="btn btn-sm btn-primary rounded-3 me-1" title="Trả lời"
                                                    onclick="openAnswerModal(<?= $q['id'] ?>, <?= htmlspecialchars(json_encode($q['question']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($q['answer'] ?? ''), ENT_QUOTES) ?>)">
                                                    <i class="fa fa-reply"></i>
                                                </button>
                                                <a href="/btl/adminFaq/deleteQuestion?<?= htmlspecialchars(http_build_query(array_filter(['id' => $q['id'], 'faq_page' => $faq_page > 1 ? $faq_page : null, 'uq_page' => $uq_page > 1 ? $uq_page : null], function ($v) { return $v !== null; }))) ?>"
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
                        <?php if ($uq_total > 0 && $uq_total_pages > 1): ?>
                            <div class="card-footer bg-white border-0 pt-0 pb-3">
                                <nav aria-label="Phân trang câu hỏi người dùng">
                                    <ul class="pagination pagination-sm justify-content-center flex-wrap mb-1">
                                        <li class="page-item <?= $uq_page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link"
                                                href="/btl/adminFaq/index?<?= htmlspecialchars($buildAdminFaqListQuery(['uq_page' => max(1, $uq_page - 1)])) ?>#user-questions">Trước</a>
                                        </li>
                                        <?php for ($p = 1; $p <= $uq_total_pages; $p++): ?>
                                            <li class="page-item <?= $p === $uq_page ? 'active' : '' ?>">
                                                <a class="page-link"
                                                    href="/btl/adminFaq/index?<?= htmlspecialchars($buildAdminFaqListQuery(['uq_page' => $p])) ?>#user-questions"><?= $p ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= $uq_page >= $uq_total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link"
                                                href="/btl/adminFaq/index?<?= htmlspecialchars($buildAdminFaqListQuery(['uq_page' => min($uq_total_pages, $uq_page + 1)])) ?>#user-questions">Sau</a>
                                        </li>
                                    </ul>
                                    <p class="text-center text-muted small mb-0">Trang <?= $uq_page ?> / <?= $uq_total_pages ?></p>
                                </nav>
                            </div>
                        <?php endif; ?>
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


<?php require_once 'views/admin/faq/modals.php'; ?>


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