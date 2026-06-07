<?php
session_start();

require_once __DIR__ . '/../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: ../View/login.php');

    exit;
}

$email =
    trim($_POST['email'] ?? '');

$password =
    $_POST['password'] ?? '';

if ($email === '' || $password === '') {

    $_SESSION['error'] =
        'Please fill all fields.';

    header('Location: ../View/login.php');

    exit;
}

try {

    $controller =
        new UserController();

    $ok =
        $controller->login(
            $email,
            $password
        );

    if ($ok) {

        $userModel =
            $controller->getUserByEmail($email);

        $_SESSION['user_id'] =
            $userModel['user_id'];

        $_SESSION['user_name'] =
            $userModel['name'];

        $_SESSION['user_role'] =
            $userModel['role'];

        header('Location: ../View/index.php');

    } else {

        $_SESSION['error'] =
            'Incorrect email or password.';

        header('Location: ../View/login.php');
    }

} catch (Exception $e) {

    $_SESSION['error'] =
        'Error: ' . $e->getMessage();

    header('Location: ../View/login.php');
}

exit;