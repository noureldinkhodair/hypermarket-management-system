<?php

class User {

    protected PDO $conn;

    public int $user_id;

    public string $name;

    public string $email;

    public string $phone;

    public string $role;

    public function __construct(PDO $db) {

        $this->conn = $db;
    }

    public function register(
        string $name,
        string $email,
        string $password,
        ?string $phone = null,
        string $role = 'customer'
    ): bool {

        $query = "

            INSERT INTO user
            (name, email, password, phone, role)

            VALUES
            (:name, :email, :password, :phone, :role)

        ";

        $stmt = $this->conn->prepare($query);

        $ok = $stmt->execute([

            ':name' => trim($name),
            ':email' => trim($email),
            ':password' => $password,
            ':phone' => $phone,
            ':role' => $role

        ]);

        if(!$ok){

            return false;
        }

        $userId =
            (int)$this->conn->lastInsertId();

        $cartStmt = $this->conn->prepare(

            "INSERT INTO cart (user_id)
             VALUES (:user_id)"

        );

        $cartStmt->execute([

            ':user_id' => $userId

        ]);

        return true;
    }

    public function login(
        string $email,
        string $password
    ): bool {

        $query = "

            SELECT *
            FROM user
            WHERE email = :email
            LIMIT 1

        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([

            ':email' => trim($email)

        ]);

        $user = $stmt->fetch();

        if(
            $user
            &&
            $password === $user['password']
        ){

            $this->user_id =
                (int)$user['user_id'];

            $this->name =
                $user['name'];

            $this->email =
                $user['email'];

            $this->phone =
                $user['phone'] ?? '';

            $this->role =
                $user['role'];

            return true;
        }

        return false;
    }

    public function getUsers(): array {

        $query = "

            SELECT
            user_id,
            name,
            email,
            phone,
            role

            FROM user

            ORDER BY user_id DESC

        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getUserById(
        int $id
    ): array|false {

        $query = "

            SELECT
            user_id,
            name,
            email,
            phone,
            role

            FROM user

            WHERE user_id = :id

            LIMIT 1

        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([

            ':id' => $id

        ]);

        return $stmt->fetch();
    }

    public function updateUser(
        int $id,
        string $name,
        string $email,
        ?string $phone,
        string $role = 'customer'
    ): bool {

        $query = "

            UPDATE user

            SET

            name = :name,
            email = :email,
            phone = :phone,
            role = :role

            WHERE user_id = :id

        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([

            ':id' => $id,
            ':name' => trim($name),
            ':email' => trim($email),
            ':phone' => $phone,
            ':role' => $role

        ]);
    }

    public function deleteUser(
        int $id
    ): bool {

        $query = "

            DELETE FROM user
            WHERE user_id = :id

        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([

            ':id' => $id

        ]);
    }

    public function cancelOrder(
        int $orderId
    ): bool {

        $query = "

            UPDATE orders

            SET status = 'cancelled'

            WHERE order_id = :order_id

        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([

            ':order_id' => $orderId

        ]);
    }

    public function logout(): void {

        if(
            session_status()
            === PHP_SESSION_NONE
        ){

            session_start();
        }

        session_unset();

        session_destroy();
    }
}