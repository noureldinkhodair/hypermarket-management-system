<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/OrderController.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit;
}

$orderId = (int)($_POST['order_id'] ?? 0);

if ($orderId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order.']);
    exit;
}

try {
    $controller = new OrderController();
    $ok = $controller->cancel($orderId);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Order cancelled.' : 'Failed to cancel.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
