<?php
require_once __DIR__ . '/delivery.php';

class HomeDelivery extends Delivery {
    public function deliverOrder(int $orderId, string $address): bool {
        return $this->createDelivery($orderId, 'home', $address, 'preparing');
    }
}
