<?php
require_once __DIR__ . '/user.php';

class Admin extends User {
    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    public function registerAdmin(string $name, string $email, string $password, ?string $phone = null): bool {
        return $this->register($name, $email, $password, $phone, 'admin');
    }
}
