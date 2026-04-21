<h2>Quản lý liên hệ</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Tên</th>
    <th>Email</th>
    <th>Nội dung</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach($contacts as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['name'] ?></td>
    <td><?= $c['email'] ?></td>
    <td><?= $c['message'] ?></td>
    <td><?= $c['status'] ?></td>
    <td>
        <a href="/btl/admincontact/updateStatus/<?= $c['id'] ?>/read">Read</a> |
        <a href="/btl/admincontact/updateStatus/<?= $c['id'] ?>/replied">Replied</a> |
        <a href="/btl/admincontact/delete/<?= $c['id'] ?>" onclick="return confirm('Xóa?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>