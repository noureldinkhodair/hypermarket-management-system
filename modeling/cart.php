<?php

class Cart {

    private PDO $conn;

    public function __construct(PDO $db) {

        $this->conn = $db;
    }

    public function getOrCreateCart(int $userId): int {

        $stmt = $this->conn->prepare(

            "SELECT cart_id
             FROM cart
             WHERE user_id = :user_id
             LIMIT 1"

        );

        $stmt->execute([

            ':user_id' => $userId

        ]);

        $cart = $stmt->fetch();

        if($cart){

            return (int)$cart['cart_id'];
        }

        $stmt = $this->conn->prepare(

            "INSERT INTO cart (user_id)
             VALUES (:user_id)"

        );

        $stmt->execute([

            ':user_id' => $userId

        ]);

        return (int)$this->conn->lastInsertId();
    }

    public function addProduct(
        int $userId,
        int $productId,
        int $quantity = 1
    ): bool {

        $cartId =
            $this->getOrCreateCart($userId);

        $stmt = $this->conn->prepare(

            "SELECT cart_product_id, quantity

             FROM cart_product

             WHERE cart_id = :cart_id
             AND product_id = :product_id

             LIMIT 1"

        );

        $stmt->execute([

            ':cart_id' => $cartId,
            ':product_id' => $productId

        ]);

        $item = $stmt->fetch();

        if($item){

            $stmt = $this->conn->prepare(

                "UPDATE cart_product

                 SET quantity =
                 quantity + :quantity

                 WHERE cart_product_id = :id"

            );

            return $stmt->execute([

                ':quantity' => $quantity,
                ':id' => $item['cart_product_id']

            ]);
        }

        $stmt = $this->conn->prepare(

            "INSERT INTO cart_product
            (cart_id, product_id, quantity)

            VALUES
            (:cart_id, :product_id, :quantity)"

        );

        return $stmt->execute([

            ':cart_id' => $cartId,
            ':product_id' => $productId,
            ':quantity' => $quantity

        ]);
    }

    public function getCartItems(int $userId): array {

        $query = "

            SELECT

                cp.cart_product_id,
                cp.quantity,

                p.product_id,
                p.name,
                p.price,
                p.image,

                (cp.quantity * p.price)
                AS subtotal

            FROM cart c

            JOIN cart_product cp
            ON c.cart_id = cp.cart_id

            JOIN product p
            ON cp.product_id = p.product_id

            WHERE c.user_id = :user_id

            ORDER BY cp.cart_product_id DESC

        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([

            ':user_id' => $userId

        ]);

        return $stmt->fetchAll();
    }

    public function calculateTotal(int $userId): float {

        $query = "

            SELECT
            SUM(cp.quantity * p.price)
            AS total

            FROM cart c

            JOIN cart_product cp
            ON c.cart_id = cp.cart_id

            JOIN product p
            ON cp.product_id = p.product_id

            WHERE c.user_id = :user_id

        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([

            ':user_id' => $userId

        ]);

        $row = $stmt->fetch();

        return (float)($row['total'] ?? 0);
    }

    public function removeProduct(
        int $cartProductId
    ): bool {

        $stmt = $this->conn->prepare(

            "DELETE FROM cart_product

             WHERE cart_product_id = :id"

        );

        return $stmt->execute([

            ':id' => $cartProductId

        ]);
    }
}