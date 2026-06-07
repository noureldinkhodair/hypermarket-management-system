<?php

class Payment {

    protected PDO $conn;



    public function __construct(PDO $db) {

        $this->conn = $db;
    }



    public function createPayment(
        int $orderId,
        string $method,
        float $amount,
        string $status = 'pending'
    ): bool {

        $query =
            "INSERT INTO payment
            (order_id, payment_method, amount, payment_status)
            VALUES
            (:order_id, :payment_method, :amount, :payment_status)";

        $stmt =
            $this->conn->prepare($query);

        return $stmt->execute([

            ':order_id' => $orderId,

            ':payment_method' => $method,

            ':amount' => $amount,

            ':payment_status' => $status
        ]);
    }



    public function updateStatus(
        int $paymentId,
        string $status
    ): bool {

        $stmt =
            $this->conn->prepare(
                "UPDATE payment
                 SET payment_status = :status
                 WHERE payment_id = :id"
            );

        return $stmt->execute([

            ':status' => $status,

            ':id' => $paymentId
        ]);
    }



    public function getPayments(): array {

        $query =
            "SELECT * FROM payment
             ORDER BY payment_id DESC";

        $stmt =
            $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}