<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/product.php';

class ProductController {

    private Product $productModel;

    public function __construct() {

        $db =
            Database::getConnection();

        $this->productModel =
            new Product($db);
    }

    public function index(): array {

        return $this->productModel
                    ->getProducts();
    }

    public function show(
        int $id
    ): array|false {

        return $this->productModel
                    ->getProductDetails($id);
    }

    public function store(
        ?int $categoryId,
        string $name,
        string $description,
        float $price,
        int $stockQuantity,
        ?string $image = null
    ): bool {

        if (trim($name) === '') {

            throw new InvalidArgumentException(
                'Product name is required'
            );
        }

        return $this->productModel
                    ->addProduct(
                        $categoryId,
                        $name,
                        $description,
                        $price,
                        $stockQuantity,
                        $image
                    );
    }

    public function update(
        int $id,
        ?int $categoryId,
        string $name,
        string $description,
        float $price,
        int $stockQuantity,
        ?string $image = null
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid product id is required'
            );
        }

        if (trim($name) === '') {

            throw new InvalidArgumentException(
                'Product name is required'
            );
        }

        return $this->productModel
                    ->updateProduct(
                        $id,
                        $categoryId,
                        $name,
                        $description,
                        $price,
                        $stockQuantity,
                        $image
                    );
    }

    public function delete(
        int $id
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid product id is required'
            );
        }

        return $this->productModel
                    ->deleteProduct($id);
    }
}