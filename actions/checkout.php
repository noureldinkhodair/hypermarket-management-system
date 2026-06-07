<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../controllers/CheckoutController.php';

require_once __DIR__ . '/../patterns/strategy/PaymentContext.php';



if (!isset($_SESSION['user_id'])) {

    header('Location: ../View/login.php');

    exit;
}



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: ../View/checkout.php');

    exit;
}



$userId =
    (int)$_SESSION['user_id'];

$paymentMethod =
    $_POST['payment'] ?? '';

$deliveryMethod =
    $_POST['delivery'] ?? '';

$address =
    trim($_POST['address'] ?? '');



if ($paymentMethod === '' || $deliveryMethod === '') {

    $_SESSION['error'] =
        'Please select payment and delivery methods.';

    header('Location: ../View/checkout.php');

    exit;
}



if (
    strtolower($deliveryMethod) === 'home'
    &&
    $address === ''
) {

    $_SESSION['error'] =
        'Please enter your delivery address.';

    header('Location: ../View/checkout.php');

    exit;
}



try {

    $controller =
        new CheckoutController();



    /* STRATEGY PATTERN */

    if ($paymentMethod === 'cash') {

        $strategy =
            new CashPaymentStrategy();

    } elseif ($paymentMethod === 'visa') {

        $strategy =
            new VisaPaymentStrategy();

    } else {

        throw new Exception(
            'Invalid payment method.'
        );
    }



    $paymentContext =
        new PaymentContext();

    $paymentContext->setStrategy(
        $strategy
    );



    $paymentResult =
        $paymentContext->executePayment(0);



    $orderId =
        $controller->placeOrder(
            $userId,
            $paymentMethod,
            $deliveryMethod,
            $address ?: null
        );



    if ($orderId) {

        $_SESSION['success'] =
            'Order placed successfully! Order #'
            . $orderId
            . ' | '
            . $paymentResult;

        header('Location: ../View/orders.php');

    } else {

        $_SESSION['error'] =
            'Failed to place order. Please try again.';

        header('Location: ../View/checkout.php');
    }

} catch (Exception $e) {

    $_SESSION['error'] =
        'Error: ' . $e->getMessage();

    header('Location: ../View/checkout.php');
}

exit;