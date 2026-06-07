<?php
class Orders {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function createOrder(int $userId, float $totalAmount, string $paymentMethod, string $deliveryMethod): int|false {
        $query = "INSERT INTO orders (user_id, total_amount, payment_method, delivery_method, status)
                  VALUES (:user_id, :total_amount, :payment_method, :delivery_method, 'pending')";
        $stmt = $this->conn->prepare($query);
        $ok = $stmt->execute([
            ':user_id' => $userId,
            ':total_amount' => $totalAmount,
            ':payment_method' => $paymentMethod,
            ':delivery_method' => $deliveryMethod
        ]);
        return $ok ? (int)$this->conn->lastInsertId() : false;
    }

    public function addOrderProduct(int $orderId, int $productId, int $quantity, float $price): bool {
        $query = "INSERT INTO order_product (order_id, product_id, quantity, price)
                  VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
            ':price' => $price
        ]);
    }

    public function getOrders(?int $userId = null): array {
        if ($userId) {
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id DESC");
            $stmt->execute([':user_id' => $userId]);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM orders ORDER BY order_id DESC");
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getOrderItems(int $orderId): array {
        $query = "SELECT op.quantity, op.price, p.product_id, p.name, p.image
                  FROM order_product op
                  JOIN product p ON op.product_id = p.product_id
                  WHERE op.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $orderId, string $status): bool {
        $query = "UPDATE orders SET status = :status WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':order_id' => $orderId, ':status' => $status]);
    }

    public function cancelOrder(int $orderId): bool {
        return $this->updateStatus($orderId, 'cancelled');
    }
}
