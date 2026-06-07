<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/payment.php';

class PaymentController {

    private Payment $paymentModel;



    public function __construct() {

        $db = Database::getConnection();

        $this->paymentModel =
            new Payment($db);
    }



    public function index(): array {

        return $this->paymentModel
                    ->getPayments();
    }
}