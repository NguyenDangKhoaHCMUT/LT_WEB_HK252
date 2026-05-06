<?php
require_once 'models/Contact.php';

class AdminContactController {

    // GET /admincontact/index
    public function index() {
        $title = "Quản lý liên hệ";

        $contact = new Contact();
        $contacts = $contact->getAll();

        ob_start();
        require_once 'views/admin/contacts.php';
        $content = ob_get_clean();

        require_once 'views/layouts/admin.php'; // 👈 dòng quan trọng
    }

    // /admincontact/updateStatus/1/read
    public function updateStatus($id, $status) {
        $contact = new Contact();
        $contact->updateStatus($id, $status);

        header("Location: /btl/admincontact/index");
    }

    // /admincontact/delete/1
    public function delete($id) {
        $contact = new Contact();
        $contact->delete($id);

        header("Location: /btl/admincontact/index");
    }
}