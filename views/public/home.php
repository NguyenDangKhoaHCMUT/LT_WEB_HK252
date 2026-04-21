<!-- HERO -->
<section class="py-5 text-center bg-light">
    <div class="container">
        <h1 class="display-4 fw-bold text-primary">
            <?= $settings['about_title'] ?? 'TechStore' ?>
        </h1>
        <p class="lead text-secondary mt-3">
            <?= $settings['about_desc_1'] ?? 'Cửa hàng công nghệ hàng đầu' ?>
        </p>
        <a href="/btl/product" class="btn btn-primary btn-lg mt-3">
            Xem sản phẩm
        </a>
    </div>
</section>

<!-- ABOUT -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Về chúng tôi</h2>
        <p class="text-muted">
            <?= $settings['about_desc_2'] ?? 'Chúng tôi cung cấp sản phẩm chất lượng cao với giá tốt.' ?>
        </p>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">

            <div class="col-md-4">
                <i class="fa fa-bolt fa-2x text-primary mb-3"></i>
                <h5>Nhanh chóng</h5>
                <p>Giao hàng toàn quốc trong 24h</p>
            </div>

            <div class="col-md-4">
                <i class="fa fa-shield-alt fa-2x text-primary mb-3"></i>
                <h5>Uy tín</h5>
                <p>Bảo hành chính hãng 100%</p>
            </div>

            <div class="col-md-4">
                <i class="fa fa-headset fa-2x text-primary mb-3"></i>
                <h5>Hỗ trợ</h5>
                <p>Hỗ trợ khách hàng 24/7</p>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Sẵn sàng mua sắm?</h2>
        <a href="/btl/contact" class="btn btn-outline-primary btn-lg">
            Liên hệ ngay
        </a>
    </div>
</section>