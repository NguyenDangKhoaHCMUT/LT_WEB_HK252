<div class="about-page">

    <!-- Tầm nhìn và sứ mệnh: Carousel trái + About 1 phải -->
    <div class="row align-items-center mb-4 g-5 pb-2">
        <div class="col-lg-6 position-relative">
            <div id="aboutImageCarousel" class="carousel slide shadow rounded-4 overflow-hidden"
                data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#aboutImageCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#aboutImageCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#aboutImageCarousel" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="3000">
                        <img src="<?= htmlspecialchars($settings['about_carousel_1'] ?? 'https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=2070&auto=format&fit=crop') ?>"
                            class="d-block w-100"
                            style="object-fit: cover; height: 100%; min-height: 400px; max-height: 450px;"
                            alt="Image 1">
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <img src="<?= htmlspecialchars($settings['about_carousel_2'] ?? 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop') ?>"
                            class="d-block w-100"
                            style="object-fit: cover; height: 100%; min-height: 400px; max-height: 450px;"
                            alt="Image 2">
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <img src="<?= htmlspecialchars($settings['about_carousel_3'] ?? 'https://images.unsplash.com/photo-1531297172864-45d6124c9c8c?q=80&w=2070&auto=format&fit=crop') ?>"
                            class="d-block w-100"
                            style="object-fit: cover; height: 100%; min-height: 400px; max-height: 450px;"
                            alt="Image 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#aboutImageCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#aboutImageCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
            <!-- Deco element -->
            <div class="position-absolute bg-primary rounded-4"
                style="width: 100px; height: 100px; bottom: -20px; left: -20px; z-index: -1; opacity: 0.2;"></div>
        </div>
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <span
                    class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill fw-semibold"><?= htmlspecialchars($settings['about_subtitle'] ?? 'Câu chuyện của chúng tôi') ?></span>
                <h2 class="fw-bold text-dark mb-4">
                    <?= $settings['about_title'] ?? '<span class="text-primary">Sứ mệnh</span> số hóa' ?></h2>
                <p class="text-secondary fs-6 lh-lg mb-0">
                    <?= $settings['about_desc_1'] ?? 'Tại <strong>TechStore</strong>, chúng tôi tin rằng công nghệ là chìa khóa để khai mở những giới hạn mới của con người. Được thành lập với khát khao thu hẹp khoảng cách công nghệ, chúng tôi không ngừng nỗ lực mang đến cho khách hàng các thiết bị Smartphone và Laptop tiên tiến nhất hiện nay.' ?>
                </p>
            </div>
        </div>
    </div>

    <!-- About 2: Full-width bên dưới -->
    <?php if (!empty($settings['about_desc_2'])): ?>
        <div class="row mb-5">
            <div class="col-12">
                <p class="text-secondary fs-6 lh-lg mb-0"><?= $settings['about_desc_2'] ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Giá trị cốt lõi -->
    <div class="text-center mb-5 pt-4">
        <h2 class="fw-bold mb-5">Vì sao chọn TechStore?</h2>
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center rounded-4 tech-feature-card">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex justify-content-center align-items-center mb-4 mx-auto transition"
                        style="width: 80px; height: 80px;">
                        <i class="fa fa-medal fs-1"></i>
                    </div>
                    <h5 class="fw-bold mt-2">Chất lượng điểm 10</h5>
                    <p class="text-secondary mb-0 mt-3">Cam kết tất cả thiết bị đều là hàng chính hãng từ các thương
                        hiệu uy tín toàn cầu như Apple, Samsung, Asus.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center rounded-4 tech-feature-card">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex justify-content-center align-items-center mb-4 mx-auto transition"
                        style="width: 80px; height: 80px;">
                        <i class="fa fa-bolt fs-1"></i>
                    </div>
                    <h5 class="fw-bold mt-2">Giao hàng thần tốc</h5>
                    <p class="text-secondary mb-0 mt-3">Sở hữu ngay trong vòng 2h tại nội thành với đội ngũ giao nhận
                        được đào tạo chuyên môn và thân thiện.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center rounded-4 tech-feature-card">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex justify-content-center align-items-center mb-4 mx-auto transition"
                        style="width: 80px; height: 80px;">
                        <i class="fa fa-shield-heart fs-1"></i>
                    </div>
                    <h5 class="fw-bold mt-2">Hậu mãi trọn đời</h5>
                    <p class="text-secondary mb-0 mt-3">Chính sách 1-đổi-1 trong vòng 30 ngày. Hỗ trợ phần mềm, vệ sinh
                        thiết bị hoàn toàn miễn phí trọn đời.</p>
                </div>
            </div>
        </div>
    </div>
</div>