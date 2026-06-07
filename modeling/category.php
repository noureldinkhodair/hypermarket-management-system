<?php
class Category {
    private PDO $conn;

    public int $category_id;
    public string $name;
    public ?string $image;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function getCategories(): array {
        $query = "SELECT category_id, name, image FROM category ORDER BY category_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCategoryById(int $id): array|false {
        $query = "SELECT category_id, name, image FROM category WHERE category_id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function addCategory(string $name, ?string $image = null): bool {
        $query = "INSERT INTO category (name, image) VALUES (:name, :image)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => trim($name),
            ':image' => $image
        ]);
    }

    public function updateCategory(int $id, string $name, ?string $image = null): bool {
        $query = "UPDATE category SET name = :name, image = :image WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':name' => trim($name),
            ':image' => $image
        ]);
    }

    public function deleteCategory(int $id): bool {
        $query = "DELETE FROM category WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
