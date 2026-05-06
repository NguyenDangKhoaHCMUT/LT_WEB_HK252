<style>
    /* ===== FAQ Page Styles ===== */
    .faq-hero {
        background: linear-gradient(135deg, #f8f9ff 0%, #eef2ff 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .faq-hero h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.75rem;
    }

    .faq-hero p {
        color: #6c757d;
        font-size: 1.05rem;
        max-width: 560px;
        margin: 0 auto;
    }

    /* Accordion FAQ */
    .faq-accordion .accordion-item {
        border: 1px solid #e9ecef;
        border-radius: 12px !important;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        transition: box-shadow 0.2s;
    }

    .faq-accordion .accordion-item:hover {
        box-shadow: 0 4px 16px rgba(13, 110, 253, 0.1);
    }

    .faq-accordion .accordion-button {
        font-weight: 600;
        font-size: 0.97rem;
        color: #1a1a2e;
        background: #fff;
        padding: 1.1rem 1.4rem;
        border-radius: 12px !important;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        background: #f0f5ff;
        color: #0d6efd;
        box-shadow: none;
    }

    .faq-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .faq-accordion .accordion-body {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.75;
        padding: 1rem 1.4rem 1.2rem;
        background: #fff;
        border-top: 1px solid #f0f0f0;
    }

    .faq-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #e8f0fe;
        color: #0d6efd;
        border-radius: 50%;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-right: 0.85rem;
    }

    /* Section Divider */
    .section-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .section-title-icon {
        width: 36px;
        height: 36px;
        background: #e8f0fe;
        color: #0d6efd;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    /* Ask Question Box */
    .ask-box {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .ask-box textarea {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        resize: vertical;
        font-size: 0.95rem;
        padding: 0.75rem 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .ask-box textarea:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }

    /* My questions */
    .question-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        transition: box-shadow 0.2s;
    }

    .question-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
    }

    .question-card .q-text {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.97rem;
        margin-bottom: 0.5rem;
    }

    .question-card .answer-block {
        background: #f0f5ff;
        border-left: 3px solid #0d6efd;
        border-radius: 0 8px 8px 0;
        padding: 0.75rem 1rem;
        color: #444;
        font-size: 0.93rem;
        line-height: 1.65;
        margin-top: 0.75rem;
    }

    .status-badge-pending {
        background: #fff3cd;
        color: #856404;
        font-size: 0.78rem;
        padding: 2px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .status-badge-answered {
        background: #d1e7dd;
        color: #146c43;
        font-size: 0.78rem;
        padding: 2px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .login-prompt {
        background: linear-gradient(135deg, #f0f5ff 0%, #fff 100%);
        border: 1px dashed #b6ccf7;
        border-radius: 14px;
        padding: 2rem;
        text-align: center;
        color: #6c757d;
    }

    .char-counter {
        font-size: 0.8rem;
        color: #aaa;
        text-align: right;
    }

    .char-counter.over {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .faq-hero {
            padding: 2rem 1.25rem;
        }

        .faq-hero h1 {
            font-size: 1.7rem;
        }
    }
</style>

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
                                    <span class="faq-number"><?= $i + 1 ?></span>
                                    <?= htmlspecialchars($faq['question']) ?>
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
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-inbox fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0">Chưa có câu hỏi thường gặp nào.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Ask a question + My questions -->
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
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Câu hỏi của bạn</label>
                            <textarea class="form-control" name="question" id="questionInput" rows="5"
                                maxlength="1000"
                                placeholder="Nhập câu hỏi của bạn tại đây... (tối đa 1000 ký tự)"></textarea>
                            <div class="char-counter mt-1"><span id="charCount">0</span>/1000</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                            <i class="fa fa-paper-plane me-2"></i>Gửi câu hỏi
                        </button>
                    </form>
                </div>

                <!-- My Questions -->
                <?php if (!empty($my_questions)): ?>
                    <div class="section-title mt-3">
                        <span class="section-title-icon"><i class="fa fa-clock-rotate-left"></i></span>
                        Câu hỏi của tôi
                    </div>
                    <?php foreach ($my_questions as $q): ?>
                        <div class="question-card">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="q-text"><?= htmlspecialchars($q['question']) ?></div>
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
                <?php endif; ?>

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
