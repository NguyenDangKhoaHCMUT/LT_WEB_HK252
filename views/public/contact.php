<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="fw-bold text-primary mb-3 text-center">Liên hệ</h2>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Gửi liên hệ thành công!</div>
        <?php endif; ?>

        <form method="POST" action="/btl/contact/send">
            
            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nội dung</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Gửi liên hệ
            </button>
        </form>
    </div>
</div>