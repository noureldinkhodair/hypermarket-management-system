<?php
session_start();

require_once __DIR__ . '/../controllers/ContactController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../View/contact.php');
    exit;
}

$name    = trim($_POST['name']    ?? '');
$phone   = trim($_POST['phone']   ?? '');
$message = trim($_POST['message'] ?? '');
$email   = $_SESSION['user_email'] ?? 'guest@seoudi.com';

if ($name === '' || $message === '') {
    $_SESSION['error'] = 'Name and message are required.';
    header('Location: ../View/contact.php');
    exit;
}

try {
    $controller = new ContactController();
    $ok = $controller->store($name, $email, $phone, $message);

    if ($ok) {
        $_SESSION['success'] = 'Message sent successfully! We will contact you soon.';
    } else {
        $_SESSION['error'] = 'Failed to send message. Please try again.';
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
}

header('Location: ../View/contact.php');
exit;
