<?php
/**
 * @var array $data Dữ liệu câu hỏi/FAQ
 * @var string $type Loại nội dung ('faq' hoặc 'user')
 */
$detail_faq_pg = max(1, (int) ($_GET['faq_page'] ?? 1));
$detail_uq_pg = max(1, (int) ($_GET['uq_page'] ?? 1));
$detail_list_qs = http_build_query(
    array_filter(
        [
            'faq_page' => $detail_faq_pg > 1 ? $detail_faq_pg : null,
            'uq_page' => $detail_uq_pg > 1 ? $detail_uq_pg : null,
        ],
        function ($v) {
            return $v !== null;
        },
    ),
);
$detail_index_suffix = $detail_list_qs !== '' ? '?' . htmlspecialchars($detail_list_qs) : '';
?>
<div class="container-fluid py-4">
    <!-- Breadcrumb & Back button -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="/btl/adminFaq/index<?= $detail_index_suffix ?>"
                            class="text-decoration-none">Quản lý Hỏi /
                            Đáp</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="h3 fw-bold mb-0">
                    <i class="fa fa-circle-info text-primary me-2"></i>Chi tiết
                    <?= $type === 'user' ? 'câu hỏi người dùng' : 'FAQ' ?>
                </h2>
                <a href="/btl/adminFaq/index<?= $detail_index_suffix ?><?= $type === 'user' ? '#user-questions' : '' ?>"
                    class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">Nội dung câu hỏi:</h5>
                    <div class="bg-light rounded-4 p-4 mb-4 border-start border-4 border-primary shadow-sm">
                        <div class="fw-medium text-dark faq-detail-text"><?= nl2br(htmlspecialchars(trim((string) ($data['question'] ?? '')))) ?></div>
                    </div>

                    <h5 class="fw-bold text-dark mb-3">Nội dung trả lời:</h5>
                    <?php if (!empty($data['answer'])): ?>
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border-start border-4 border-primary shadow-sm">
                            <div class="text-dark faq-detail-text"><?= nl2br(htmlspecialchars(trim((string) $data['answer']))) ?></div>
                        </div>
                        <?php if ($type === 'user' && !empty($data['answered_at'])): ?>
                            <div class="mt-2 text-muted small">
                                <i class="fa fa-clock me-1"></i>Đã trả lời lúc:
                                <?= date('d/m/Y H:i', strtotime($data['answered_at'])) ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5 bg-light rounded-4 border-dashed">
                            <i class="fa fa-comment-slash fa-3x mb-3 text-muted opacity-50"></i>
                            <p class="text-muted mb-3">Chưa có nội dung trả lời cho câu hỏi này.</p>
                            <?php if ($type === 'user'): ?>
                                <button class="btn btn-primary rounded-pill px-4"
                                    onclick="openAnswerModalInDetail(<?= $data['id'] ?>, <?= htmlspecialchars(json_encode($data['question']), ENT_QUOTES) ?>)">
                                    <i class="fa fa-reply me-2"></i>Trả lời ngay
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <?php if ($type === 'user'): ?>
                <!-- User Info -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold mb-0">Thông tin người hỏi</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <?php if (!empty($data['avatar'])): ?>
                                <img src="/btl/<?= htmlspecialchars($data['avatar']) ?>"
                                    class="rounded-circle border border-2 border-primary border-opacity-10 me-3"
                                    style="width: 64px; height: 64px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                    style="width: 64px; height: 64px;">
                                    <i class="fa fa-user text-primary fa-2x"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($data['fullname'] ?? 'Ẩn danh') ?></h6>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($data['email'] ?? 'Không có email') ?>
                                </p>
                            </div>
                        </div>
                        <hr class="opacity-10 my-3">
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted small">Thời gian gửi:</span>
                            <span class="fw-medium small"><?= date('d/m/Y H:i', strtotime($data['created_at'])) ?></span>
                        </div>
                        <div class="mb-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Trạng thái:</span>
                            <?php if ($data['status'] === 'answered'): ?>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">Đã
                                    trả lời</span>
                            <?php else: ?>
                                <span
                                    class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-25">Chờ
                                    trả lời</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- FAQ Meta Info -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold mb-0">Thông tin FAQ</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3 d-flex justify-content-between">
                            <span class="text-muted small">Thứ tự hiển thị:</span>
                            <span class="badge bg-light text-dark rounded-pill px-3"><?= $data['sort_order'] ?></span>
                        </div>
                        <div class="mb-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Hiển thị công khai:</span>
                            <?php if ($data['is_published']): ?>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">Có</span>
                            <?php else: ?>
                                <span
                                    class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 border border-secondary border-opacity-25">Không</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold mb-0">Thao tác nhanh</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <?php if ($type === 'user'): ?>
                            <button class="btn btn-primary rounded-3 text-start"
                                onclick="openAnswerModalInDetail(<?= $data['id'] ?>, <?= htmlspecialchars(json_encode($data['question']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($data['answer'] ?? ''), ENT_QUOTES) ?>)">
                                <i
                                    class="fa fa-reply me-2"></i><?= empty($data['answer']) ? 'Trả lời câu hỏi' : 'Chỉnh sửa trả lời' ?>
                            </button>
                            <a href="/btl/adminFaq/deleteQuestion?<?= htmlspecialchars(http_build_query(array_filter(['id' => $data['id'], 'faq_page' => $detail_faq_pg > 1 ? $detail_faq_pg : null, 'uq_page' => $detail_uq_pg > 1 ? $detail_uq_pg : null], function ($v) { return $v !== null; }))) ?>"
                                class="btn btn-light text-danger rounded-3 text-start"
                                onclick="return confirm('Xác nhận xoá câu hỏi này?')">
                                <i class="fa fa-trash me-2"></i>Xoá câu hỏi
                            </a>
                        <?php else: ?>
                            <button class="btn btn-primary rounded-3 text-start"
                                onclick="editFaqInDetail(<?= $data['id'] ?>, <?= htmlspecialchars(json_encode($data['question']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($data['answer']), ENT_QUOTES) ?>, <?= $data['sort_order'] ?>, <?= $data['is_published'] ?>)">
                                <i class="fa fa-pen me-2"></i>Chỉnh sửa FAQ
                            </button>
                            <a href="/btl/adminFaq/delete?<?= htmlspecialchars(http_build_query(array_filter(['id' => $data['id'], 'faq_page' => $detail_faq_pg > 1 ? $detail_faq_pg : null, 'uq_page' => $detail_uq_pg > 1 ? $detail_uq_pg : null], function ($v) { return $v !== null; }))) ?>"
                                class="btn btn-light text-danger rounded-3 text-start"
                                onclick="return confirm('Xác nhận xoá FAQ này?')">
                                <i class="fa fa-trash me-2"></i>Xoá FAQ
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tái sử dụng các modal từ trang index bằng cách gọi các function JS -->
<script>
    function openAnswerModalInDetail(id, question, answer = '') {
        // Nếu chuyển sang trang index thì khó, nhưng ta có thể check nếu modalAnswer có tồn tại trong DOM (trong admin layout thường load view vào content)
        // Tuy nhiên detail view này độc lập, nên ta có thể nhúng Modal Answer vào đây hoặc redirect về index kèm modal.
        // Cách tốt nhất là nhúng lại Modal ở đây nếu cần, nhưng user yêu cầu detail là trang riêng.
        // Ta sẽ nhúng Modal trả lời vào trang detail này luôn để tiện thao tác.
        window.location.href = '/btl/adminFaq/index#user-questions';
        // Vì Modal được định nghĩa trong index.php nên ta không thể gọi trực tiếp từ detail.php trừ khi nhúng lại.
        // Để giữ tính nhất quán, tôi sẽ nhúng thêm Modal vào đây.
    }
</script>

<!-- Nhúng Modal Trả lời & Modal Sửa FAQ vào đây để các nút thao tác nhanh hoạt động -->
<?php
$faq_page = $detail_faq_pg;
$uq_page = $detail_uq_pg;
require_once 'views/admin/faq/modals.php';
?>

<script>
    function openAnswerModalInDetail(id, question, currentAnswer) {
        document.getElementById('answerQuestionId').value = id;
        document.getElementById('answerQuestionText').textContent = question;
        document.getElementById('answerText').value = currentAnswer || '';
        new bootstrap.Modal(document.getElementById('modalAnswer')).show();
    }

    function editFaqInDetail(id, question, answer, sortOrder, isPublished) {
        document.getElementById('editFaqId').value = id;
        document.getElementById('editFaqQuestion').value = question;
        document.getElementById('editFaqAnswer').value = answer;
        document.getElementById('editFaqOrder').value = sortOrder;
        document.getElementById('editPublished').checked = isPublished == 1;
        new bootstrap.Modal(document.getElementById('modalEditFaq')).show();
    }
</script>
