<?php
class Product {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function getProducts(): array {
        $query = "SELECT p.*, c.name AS category_name
                  FROM product p
                  LEFT JOIN category c ON p.category_id = c.category_id
                  ORDER BY p.product_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProductDetails(int $productId): array|false {
        $query = "SELECT p.*, c.name AS category_name
                  FROM product p
                  LEFT JOIN category c ON p.category_id = c.category_id
                  WHERE p.product_id = :id
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $productId]);
        return $stmt->fetch();
    }

    public function addProduct(?int $categoryId, string $name, string $description, float $price, int $stockQuantity, ?string $image = null): bool {
        $query = "INSERT INTO product (category_id, name, description, price, stock_quantity, image)
                  VALUES (:category_id, :name, :description, :price, :stock_quantity, :image)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':category_id' => $categoryId,
            ':name' => trim($name),
            ':description' => trim($description),
            ':price' => $price,
            ':stock_quantity' => $stockQuantity,
            ':image' => $image
        ]);
    }

    public function updateProduct(int $productId, ?int $categoryId, string $name, string $description, float $price, int $stockQuantity, ?string $image = null): bool {
        $query = "UPDATE product
                  SET category_id = :category_id,
                      name = :name,
                      description = :description,
                      price = :price,
                      stock_quantity = :stock_quantity,
                      image = :image
                  WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':product_id' => $productId,
            ':category_id' => $categoryId,
            ':name' => trim($name),
            ':description' => trim($description),
            ':price' => $price,
            ':stock_quantity' => $stockQuantity,
            ':image' => $image
        ]);
    }

    public function deleteProduct(int $productId): bool {
        $query = "DELETE FROM product WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $productId]);
    }

    public function getPrice(int $productId): float {
        $query = "SELECT price FROM product WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $productId]);
        $result = $stmt->fetch();
        return (float)($result['price'] ?? 0);
    }

    public function isInStock(int $productId, int $requestedQuantity): bool {
        $query = "SELECT stock_quantity FROM product WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $productId]);
        $result = $stmt->fetch();
        return $result && (int)$result['stock_quantity'] >= $requestedQuantity;
    }
}
