<?php

class Delivery {

    protected PDO $conn;



    public function __construct(PDO $db) {

        $this->conn = $db;
    }



    public function createDelivery(
        int $orderId,
        string $method,
        ?string $address = null,
        string $status = 'preparing'
    ): bool {

        $query =
            "INSERT INTO delivery
            (order_id, delivery_method, address, delivery_status)
            VALUES
            (:order_id, :delivery_method, :address, :delivery_status)";

        $stmt =
            $this->conn->prepare($query);

        return $stmt->execute([

            ':order_id' => $orderId,

            ':delivery_method' => $method,

            ':address' => $address,

            ':delivery_status' => $status
        ]);
    }



    public function updateStatus(
        int $deliveryId,
        string $status
    ): bool {

        $stmt =
            $this->conn->prepare(
                "UPDATE delivery
                 SET delivery_status = :status
                 WHERE delivery_id = :id"
            );

        return $stmt->execute([

            ':status' => $status,

            ':id' => $deliveryId
        ]);
    }



    public function getDeliveryByOrder(
        int $orderId
    ): array|false {

        $stmt =
            $this->conn->prepare(
                "SELECT * FROM delivery
                 WHERE order_id = :order_id
                 LIMIT 1"
            );

        $stmt->execute([

            ':order_id' => $orderId
        ]);

        return $stmt->fetch();
    }



    public function getDeliveries(): array {

        $query =
            "SELECT * FROM delivery
             ORDER BY delivery_id DESC";

        $stmt =
            $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}