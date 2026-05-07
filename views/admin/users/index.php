<!-- Header Actions -->
<div class="mb-4">
    <h4 class="mb-0 fw-bold text-dark">Danh sách Thành viên</h4>
</div>

<div class="card shadow-sm border-0 rounded overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted text-uppercase fw-semibold"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">STT</th>
                        <th class="py-3 text-muted text-uppercase fw-semibold"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Họ và tên</th>
                        <th class="py-3 text-muted text-uppercase fw-semibold"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Ngày tạo</th>
                        <th class="py-3 text-muted text-uppercase fw-semibold"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Giờ tạo</th>
                        <th class="py-3 text-muted text-uppercase fw-semibold"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Trạng thái</th>
                        <th class="py-3 text-muted text-uppercase fw-semibold text-end pe-4"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted"><i
                                    class="fa fa-users fs-1 mb-3"></i><br>Chưa có dữ liệu thành viên.</td>
                        </tr>
                    <?php else: ?>
                        <?php $stt = 1;
                        foreach ($users as $user): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-muted"><?= $stt++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark mb-1"><?= htmlspecialchars($user['fullname']) ?></div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium"><?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
                                </td>
                                <td>
                                    <div class="text-muted small"><?= date('H:i:s', strtotime($user['created_at'])) ?></div>
                                </td>
                                <td>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-1 fw-medium">Active</span>
                                    <?php else: ?>
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-1 fw-medium">Locked</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="/btl/adminUser/view/<?= $user['id'] ?>"
                                            class="btn btn-sm btn-light text-primary bg-opacity-50 border shadow-sm"
                                            title="Xem chi tiết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?= Csrf::addTokenToUrl('/btl/adminUser/toggle_status/' . $user['id']) ?>"
                                            class="btn btn-sm btn-light text-warning bg-opacity-50 border shadow-sm"
                                            title="<?= $user['status'] === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn đổi trạng thái tài khoản này?')">
                                            <i class="fa <?= $user['status'] === 'active' ? 'fa-lock' : 'fa-unlock' ?>"></i>
                                        </a>
                                        <a href="<?= Csrf::addTokenToUrl('/btl/adminUser/delete/' . $user['id']) ?>"
                                            class="btn btn-sm btn-light text-danger bg-opacity-50 border shadow-sm"
                                            title="Xoá vĩnh viễn"
                                            onclick="return confirm('Cảnh báo: Xác nhận xoá tài khoản này? Hành động không thể hoàn tác.')">
                                            <i class="fa fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div
        class="card-footer bg-white border-top border-light p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
        <span class="text-muted small fw-medium">Hiển thị tất cả <strong
                class="text-dark"><?= count($users ?? []) ?></strong> thành viên.</span>

        <!-- Pagination Placeholder -->
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0 shadow-sm">
                <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">Sau</a></li>
            </ul>
        </nav>
    </div>
</div>
