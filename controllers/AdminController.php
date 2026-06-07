<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/admin.php';

class AdminController {

    private Admin $adminModel;

    public function __construct() {

        $db =
            Database::getConnection();

        $this->adminModel =
            new Admin($db);
    }

    public function register(
        string $name,
        string $email,
        string $password,
        ?string $phone = null
    ): bool {

        return $this->adminModel
                    ->registerAdmin(
                        $name,
                        $email,
                        $password,
                        $phone
                    );
    }

    public function getAllUsers(): array {

        return $this->adminModel
                    ->getUsers();
    }

    public function getUser(
        int $id
    ): array|false {

        return $this->adminModel
                    ->getUserById($id);
    }

    public function updateUser(
        int $id,
        string $name,
        string $email,
        ?string $phone,
        string $role
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid user id is required'
            );
        }

        return $this->adminModel
                    ->updateUser(
                        $id,
                        $name,
                        $email,
                        $phone,
                        $role
                    );
    }

    public function delete(
        int $id
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid user id is required'
            );
        }

        return $this->adminModel
                    ->deleteUser($id);
    }
}