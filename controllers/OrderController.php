<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/orders.php';

class OrderController {

    private Orders $orderModel;

    public function __construct() {

        $db =
            Database::getConnection();

        $this->orderModel =
            new Orders($db);
    }

    public function index(
        ?int $userId = null
    ): array {

        return $this->orderModel
                    ->getOrders($userId);
    }

    public function getItems(
        int $orderId
    ): array {

        if ($orderId <= 0) {

            throw new InvalidArgumentException(
                'Valid order id is required'
            );
        }

        return $this->orderModel
                    ->getOrderItems($orderId);
    }

    public function store(
        int $userId,
        float $totalAmount,
        string $paymentMethod,
        string $deliveryMethod
    ): int|false {

        if ($userId <= 0) {

            throw new InvalidArgumentException(
                'Valid user id is required'
            );
        }

        if ($totalAmount <= 0) {

            throw new InvalidArgumentException(
                'Total amount must be greater than zero'
            );
        }

        return $this->orderModel
                    ->createOrder(
                        $userId,
                        $totalAmount,
                        $paymentMethod,
                        $deliveryMethod
                    );
    }

    public function addProduct(
        int $orderId,
        int $productId,
        int $quantity,
        float $price
    ): bool {

        return $this->orderModel
                    ->addOrderProduct(
                        $orderId,
                        $productId,
                        $quantity,
                        $price
                    );
    }

    public function updateStatus(
        int $orderId,
        string $status
    ): bool {

        if ($orderId <= 0) {

            throw new InvalidArgumentException(
                'Valid order id is required'
            );
        }

        return $this->orderModel
                    ->updateStatus(
                        $orderId,
                        $status
                    );
    }

    public function update(
        int $orderId,
        string $status
    ): bool {

        return $this->updateStatus(
            $orderId,
            $status
        );
    }

    public function delete(
        int $orderId
    ): bool {

        if ($orderId <= 0) {

            throw new InvalidArgumentException(
                'Valid order id is required'
            );
        }

        return $this->orderModel
                    ->cancelOrder($orderId);
    }
}