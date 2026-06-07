<?php

class ContactMessage {

    private $conn;

    public $id;
    public $name;
    public $email;
    public $message;
    public $date;
    public $phone;

    public function __construct($db) {

        $this->conn = $db;
    }

    public function sendMessage(
        $name,
        $email,
        $phone,
        $message
    ) {

        $query = "

            INSERT INTO contact_message
            (name, email, phone, message, date)

            VALUES
            (:name, :email, :phone, :message, NOW())

        ";

        $stmt =
            $this->conn->prepare($query);

        return $stmt->execute([

            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':message' => $message

        ]);
    }

    public function viewAllMessages() {

        $query = "

            SELECT *

            FROM contact_message

            ORDER BY date DESC

        ";

        $stmt =
            $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteMessage(
        int $id
    ): bool {

        $query = "

            DELETE FROM contact_message

            WHERE message_id = :id

        ";

        $stmt =
            $this->conn->prepare($query);

        return $stmt->execute([

            ':id' => $id

        ]);
    }
}