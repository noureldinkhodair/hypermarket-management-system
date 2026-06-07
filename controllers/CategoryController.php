<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/category.php';

class CategoryController {

    private Category $categoryModel;

    public function __construct() {

        $db =
            Database::getConnection();

        $this->categoryModel =
            new Category($db);
    }

    public function index(): array {

        return $this->categoryModel
                    ->getCategories();
    }

    public function show(
        int $id
    ): array|false {

        return $this->categoryModel
                    ->getCategoryById($id);
    }

    public function store(
        string $name,
        ?string $image = null
    ): bool {

        if (trim($name) === '') {

            throw new InvalidArgumentException(
                'Category name is required'
            );
        }

        return $this->categoryModel
                    ->addCategory(
                        $name,
                        $image
                    );
    }

    public function update(
        int $id,
        string $name,
        ?string $image = null
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid category id is required'
            );
        }

        if (trim($name) === '') {

            throw new InvalidArgumentException(
                'Category name is required'
            );
        }

        return $this->categoryModel
                    ->updateCategory(
                        $id,
                        $name,
                        $image
                    );
    }

    public function delete(
        int $id
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid category id is required'
            );
        }

        return $this->categoryModel
                    ->deleteCategory($id);
    }
}