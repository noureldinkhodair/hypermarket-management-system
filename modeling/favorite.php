<?php
class Favorite {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function addFavorite(int $userId, int $productId): bool {
        $query = "INSERT INTO favorite (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
    }

    public function getFavorites(int $userId): array {
        $query = "SELECT f.favorite_id, p.*
                  FROM favorite f
                  JOIN product p ON f.product_id = p.product_id
                  WHERE f.user_id = :user_id
                  ORDER BY f.favorite_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function removeFavorite(int $favoriteId): bool {
        $stmt = $this->conn->prepare("DELETE FROM favorite WHERE favorite_id = :id");
        return $stmt->execute([':id' => $favoriteId]);
    }
}
