<?php

require_once __DIR__ . '/PaymentStrategy.php';

class VisaPaymentStrategy implements PaymentStrategy {

    public function pay(
        float $amount
    ): string {

        return "Visa Payment Selected";
    }
}