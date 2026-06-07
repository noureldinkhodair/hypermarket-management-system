<?php

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/CartController.php';

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Please login first.'
    ]);

    exit;
}

$cartProductId =
    (int)($_POST['cart_product_id'] ?? 0);

if ($cartProductId <= 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid cart item.'
    ]);

    exit;
}

try {

    $controller = new CartController();

    $ok =
      $controller->removeProduct($cartProductId);

    if ($ok) {

        $total =
          $controller->getTotal(
            (int)$_SESSION['user_id']
          );

        echo json_encode([

            'success' => true,

            'message' => 'Removed from cart.',

            'total' => number_format($total, 2)

        ]);

    } else {

        echo json_encode([

            'success' => false,

            'message' => 'Failed to remove.'

        ]);
    }

} catch (Exception $e) {

    echo json_encode([

        'success' => false,

        'message' => $e->getMessage()

    ]);
}