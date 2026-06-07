<?php

require_once __DIR__ . '/PaymentStrategy.php';

class CashPaymentStrategy implements PaymentStrategy {

    public function pay(
        float $amount
    ): string {

        return "Cash Payment Selected";
    }
}