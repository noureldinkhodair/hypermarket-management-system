<?php

require_once __DIR__ . '/CashPayment.php';

require_once __DIR__ . '/VisaPayment.php';

class PaymentContext {

    private PaymentStrategy $strategy;



    public function setStrategy(
        PaymentStrategy $strategy
    ): void {

        $this->strategy = $strategy;
    }



    public function executePayment(
        float $amount
    ): string {

        return $this->strategy
                    ->pay($amount);
    }
}