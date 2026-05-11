<?php
$faq_page = isset($faq_page) ? (int) $faq_page : 1;
$faq_total = isset($faq_total) ? (int) $faq_total : 0;
$faq_total_pages = isset($faq_total_pages) ? (int) $faq_total_pages : 1;
$faq_row_start = isset($faq_row_start) ? (int) $faq_row_start : 0;
$my_page = isset($my_page) ? (int) $my_page : 1;
$my_total = isset($my_total) ? (int) $my_total : 0;
$my_total_pages = isset($my_total_pages) ? (int) $my_total_pages : 1;
$my_row_start = isset($my_row_start) ? (int) $my_row_start : 0;
$faqs = $faqs ?? [];
$my_questions = $my_questions ?? [];

$buildPublicFaqQuery = function (array $overrides) use ($faq_page, $my_page) {
    $fp = array_key_exists('faq_page', $overrides) ? (int) $overrides['faq_page'] : $faq_page;
    $mp = array_key_exists('my_page', $overrides) ? (int) $overrides['my_page'] : $my_page;
    return http_build_query(
        array_filter(
            [
                'faq_page' => $fp > 1 ? $fp : null,
                'my_page' => $mp > 1 ? $mp : null,
            ],
            function ($v) {
                return $v !== null;
            },
        ),
    );
};
?>
<div class="faq-page py-2">

    <!-- Hero -->
    <div class="faq-hero">
        <div class="section-title-icon mx-auto mb-3" style="width:52px;height:52px;font-size:1.4rem;">
            <i class="fa fa-comments"></i>
        </div>
        <h1>Trung tâm Hỏi / Đáp</h1>
        <p>Tìm câu trả lời cho những thắc mắc thường gặp hoặc gửi câu hỏi của bạn đến đội ngũ TechStore.</p>
    </div>

    <div class="row g-4">

        <!-- Left: FAQ Accordion -->
        <div class="col-lg-7">
            <div class="section-title">
                <span class="section-title-icon"><i class="fa fa-lightbulb"></i></span>
                Câu hỏi thường gặp
            </div>

            <?php if (!empty($faqs)): ?>
                <div class="accordion faq-accordion" id="faqAccordion">
                    <?php foreach ($faqs as $i => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHead<?= $faq['id'] ?>">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqCollapse<?= $faq['id'] ?>"
                                    aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                                    <span class="faq-number"><?= $faq_row_start + $i + 1 ?></span>
                                    <?= nl2br(htmlspecialchars($faq['question'])) ?>
                                </button>
                            </h2>
                            <div id="faqCollapse<?= $faq['id'] ?>"
                                class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($faq_total > 0 && $faq_total_pages > 1): ?>
                    <nav class="mt-3 pt-2" aria-label="Phân trang câu hỏi thường gặp">
                        <ul class="pagination pagination-sm justify-content-center flex-wrap mb-0">
                            <li class="page-item <?= $faq_page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link rounded-pill px-3"
                                    href="/btl/faq/index?<?= htmlspecialchars($buildPublicFaqQuery(['faq_page' => max(1, $faq_page - 1)])) ?>">Trước</a>
                            </li>
                            <?php for ($p = 1; $p <= $faq_total_pages; $p++): ?>
                                <li class="page-item <?= $p === $faq_page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="/btl/faq/index?<?= htmlspecialchars($buildPublicFaqQuery(['faq_page' => $p])) ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $faq_page >= $faq_total_pages ? 'disabled' : '' ?>">
                                <a class="page-link rounded-pill px-3"
                                    href="/btl/faq/index?<?= htmlspecialchars($buildPublicFaqQuery(['faq_page' => min($faq_total_pages, $faq_page + 1)])) ?>">Sau</a>
                            </li>
                        </ul>
                        <p class="text-center text-muted small mb-0 mt-2">
                            Trang <?= $faq_page ?> / <?= $faq_total_pages ?> — <?= $faq_total ?> câu hỏi
                        </p>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-inbox fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0">Chưa có câu hỏi thường gặp nào.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Ask a question -->
        <div class="col-lg-5">

            <!-- Ask Box -->
            <div class="section-title">
                <span class="section-title-icon"><i class="fa fa-paper-plane"></i></span>
                Gửi câu hỏi cho chúng tôi
            </div>

            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] !== 'admin'): ?>
                <div class="ask-box mb-4">
                    <form method="POST" action="/btl/faq/index" id="askForm">
                        <input type="hidden" name="action" value="submit_question">
                        <input type="hidden" name="faq_page" value="<?= (int) $faq_page ?>">
                        <input type="hidden" name="my_page" value="<?= (int) $my_page ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Câu hỏi của bạn</label>
                            <textarea class="form-control" name="question" id="questionInput" rows="5" maxlength="1000"
                                placeholder="Nhập câu hỏi của bạn tại đây... (tối đa 1000 ký tự)"></textarea>
                            <div class="char-counter mt-1"><span id="charCount">0</span>/1000</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                            <i class="fa fa-paper-plane me-2"></i>Gửi câu hỏi
                        </button>
                    </form>
                </div>

            <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="login-prompt">
                    <i class="fa fa-shield-halved fa-2x mb-2 text-primary opacity-75"></i>
                    <p class="mb-2 fw-semibold">Bạn đang đăng nhập với tư cách Quản trị viên.</p>
                    <a href="/btl/adminFaq/index" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="fa fa-cog me-1"></i>Đến trang quản lý Hỏi/Đáp
                    </a>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <i class="fa fa-user-circle fa-2x mb-2 text-primary opacity-75"></i>
                    <p class="mb-1 fw-semibold">Đăng nhập để gửi câu hỏi</p>
                    <p class="small mb-3">Bạn cần có tài khoản để gửi câu hỏi và nhận phản hồi từ chúng tôi.</p>
                    <a href="/btl/auth/login" class="btn btn-primary btn-sm rounded-pill px-4">
                        <i class="fa fa-sign-in-alt me-1"></i>Đăng nhập ngay
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] !== 'admin' && $my_total > 0): ?>
        <div class="row g-4 faq-my-questions-section" id="faq-my-questions">
            <div class="col-12">
                <div class="section-title">
                    <span class="section-title-icon"><i class="fa fa-clock-rotate-left"></i></span>
                    Câu hỏi của tôi
                </div>
                <?php foreach ($my_questions as $q): ?>
                    <div class="question-card">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="q-text"><?= nl2br(htmlspecialchars($q['question'])) ?></div>
                            <?php if ($q['status'] === 'answered'): ?>
                                <span class="status-badge-answered ms-2 flex-shrink-0">Đã trả lời</span>
                            <?php else: ?>
                                <span class="status-badge-pending ms-2 flex-shrink-0">Chờ trả lời</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted">
                            <i class="fa fa-calendar-alt me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($q['created_at'])) ?>
                        </small>
                        <?php if ($q['status'] === 'answered' && !empty($q['answer'])): ?>
                            <div class="answer-block">
                                <small class="d-block text-primary fw-semibold mb-1">
                                    <i class="fa fa-headset me-1"></i>TechStore phản hồi:
                                </small>
                                <?= nl2br(htmlspecialchars($q['answer'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if ($my_total_pages > 1): ?>
                    <nav class="mt-3" aria-label="Phân trang câu hỏi của tôi">
                        <ul class="pagination pagination-sm justify-content-center flex-wrap mb-0">
                            <li class="page-item <?= $my_page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link rounded-pill px-3"
                                    href="/btl/faq/index?<?= htmlspecialchars($buildPublicFaqQuery(['my_page' => max(1, $my_page - 1)])) ?>#faq-my-questions">Trước</a>
                            </li>
                            <?php for ($p = 1; $p <= $my_total_pages; $p++): ?>
                                <li class="page-item <?= $p === $my_page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="/btl/faq/index?<?= htmlspecialchars($buildPublicFaqQuery(['my_page' => $p])) ?>#faq-my-questions"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $my_page >= $my_total_pages ? 'disabled' : '' ?>">
                                <a class="page-link rounded-pill px-3"
                                    href="/btl/faq/index?<?= htmlspecialchars($buildPublicFaqQuery(['my_page' => min($my_total_pages, $my_page + 1)])) ?>#faq-my-questions">Sau</a>
                            </li>
                        </ul>
                        <p class="text-center text-muted small mb-0 mt-2">
                            Trang <?= $my_page ?> / <?= $my_total_pages ?> — <?= $my_total ?> câu hỏi đã gửi
                        </p>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Char counter for question textarea
    const questionInput = document.getElementById('questionInput');
    const charCount = document.getElementById('charCount');
    const charCounter = document.querySelector('.char-counter');

    if (questionInput && charCount) {
        questionInput.addEventListener('input', function () {
            const len = this.value.length;
            charCount.textContent = len;
            if (charCounter) {
                charCounter.classList.toggle('over', len >= 1000);
            }
        });
    }
</script>
