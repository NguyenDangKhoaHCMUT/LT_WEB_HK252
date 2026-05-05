<?php
require_once 'models/Contact.php';

class ContactController {

    // 👉 HIỂN THỊ TRANG CONTACT
    public function index() {
        $title = "Liên hệ";

        ob_start();
        require_once 'views/public/contact.php';
        $content = ob_get_clean();

        require_once 'views/layouts/main.php';
    }

    // 👉 XỬ LÝ FORM
    public function send() {
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
            die("Missing data");
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            die("Invalid email");
        }

        $contact = new Contact();
        $contact->create(
            $_POST['name'],
            $_POST['email'],
            $_POST['message']
        );

        header("Location: /btl/contact?success=1");
    }
}