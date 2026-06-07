<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/FavoriteController.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit;
}

$favoriteId = (int)($_POST['favorite_id'] ?? 0);

if ($favoriteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid favorite.']);
    exit;
}

try {
    $controller = new FavoriteController();
    $ok = $controller->destroy($favoriteId);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Removed from favorites.' : 'Failed to remove.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
