<?php
require_once __DIR__ . '/delivery.php';

class StorePickup extends Delivery {
    public function deliverOrder(int $orderId): bool {
        return $this->createDelivery($orderId, 'pickup', null, 'preparing');
    }
}
