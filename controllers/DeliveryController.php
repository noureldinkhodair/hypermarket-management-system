<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/delivery.php';

class DeliveryController {

    private Delivery $deliveryModel;



    public function __construct() {

        $db = Database::getConnection();

        $this->deliveryModel =
            new Delivery($db);
    }



    public function index(): array {

        return $this->deliveryModel
                    ->getDeliveries();
    }
}