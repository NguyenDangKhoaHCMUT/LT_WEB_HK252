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
                            <textarea class="form-control" name="question" id="questionInput" rows="5" maxlength="1000"
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
