<?php
session_start();

require_once __DIR__ . '/../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../View/register.php');
    exit;
}

$name     = trim($_POST['name']     ?? '');
$email    = trim($_POST['email']    ?? '');
$phone    = trim($_POST['phone']    ?? '');
$password = $_POST['password']      ?? '';
$confirm  = $_POST['confirm']       ?? '';

if ($name === '' || $email === '' || $password === '') {
    $_SESSION['error'] = 'Please fill all required fields.';
    header('Location: ../View/register.php');
    exit;
}

if ($password !== $confirm) {
    $_SESSION['error'] = 'Passwords do not match.';
    header('Location: ../View/register.php');
    exit;
}

try {
    $controller = new UserController();
    $ok = $controller->register($name, $email, $password, $phone ?: null);

    if ($ok) {
        $_SESSION['success'] = 'Account created successfully! Please login.';
        header('Location: ../View/login.php');
    } else {
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: ../View/register.php');
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    header('Location: ../View/register.php');
}
exit;
