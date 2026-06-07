<?php
require_once __DIR__ . '/payment.php';

class CardPayment extends Payment {

    public function processPayment(
        int $orderId,
        float $amount
    ): bool {

        return $this->createPayment(
            $orderId,
            'visa',
            $amount,
            'paid'
        );
    }
}