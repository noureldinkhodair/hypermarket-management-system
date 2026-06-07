<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/FavoriteController.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit;
}

$userId    = (int)$_SESSION['user_id'];
$productId = (int)($_POST['product_id'] ?? 0);

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

try {
    $controller = new FavoriteController();
    $ok = $controller->store($userId, $productId);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Added to favorites!' : 'Already in favorites.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
