<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/user.php';

class UserController {

    private User $userModel;

    public function __construct() {

        $db =
            Database::getConnection();

        $this->userModel =
            new User($db);
    }

    public function index(): array {

        return $this->userModel
                    ->getUsers();
    }

    public function register(
        string $name,
        string $email,
        string $password,
        ?string $phone = null,
        string $role = 'customer'
    ): bool {

        return $this->userModel
                    ->register(
                        $name,
                        $email,
                        $password,
                        $phone,
                        $role
                    );
    }

    public function login(
        string $email,
        string $password
    ): bool {

        return $this->userModel
                    ->login(
                        $email,
                        $password
                    );
    }

    public function getUserByEmail(
        string $email
    ): array|false {

        $db =
            Database::getConnection();

        $stmt =
            $db->prepare(

                "SELECT
                 user_id,
                 name,
                 email,
                 phone,
                 role

                 FROM user

                 WHERE email = :email

                 LIMIT 1"

            );

        $stmt->execute([

            ':email' => trim($email)

        ]);

        return $stmt->fetch();
    }

    public function show(
        int $id
    ): array|false {

        return $this->userModel
                    ->getUserById($id);
    }

    public function update(
        int $id,
        string $name,
        string $email,
        ?string $phone,
        string $role = 'customer'
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid user id is required'
            );
        }

        return $this->userModel
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

        return $this->userModel
                    ->deleteUser($id);
    }
}