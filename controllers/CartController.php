<?php
require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/cart.php';

class CartController {
    private Cart $cartModel;

    public function __construct() {
        $db = Database::getConnection();
        $this->cartModel = new Cart($db);
    }

    public function index(int $userId): array {
        return $this->cartModel->getCartItems($userId);
    }

    public function getTotal(int $userId): float {
        return $this->cartModel->calculateTotal($userId);
    }

    public function addProduct(int $userId, int $productId, int $quantity = 1): bool {
        if ($userId <= 0) {
            throw new InvalidArgumentException('Valid user id is required');
        }
        if ($productId <= 0) {
            throw new InvalidArgumentException('Valid product id is required');
        }
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }
        return $this->cartModel->addProduct($userId, $productId, $quantity);
    }

    public function removeProduct(int $cartProductId): bool {
        if ($cartProductId <= 0) {
            throw new InvalidArgumentException('Valid cart product id is required');
        }
        return $this->cartModel->removeProduct($cartProductId);
    }
}
