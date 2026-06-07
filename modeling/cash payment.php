<?php
require_once __DIR__ . '/payment.php';

class CashPayment extends Payment {
    public function processPayment(int $orderId, float $amount): bool {
        return $this->createPayment($orderId, 'cash', $amount, 'pending');
    }
}
