<?php
require_once __DIR__ . '/user.php';

class Customer extends User {
    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    public function registerCustomer(string $name, string $email, string $password, ?string $phone = null): bool {
        return $this->register($name, $email, $password, $phone, 'customer');
    }
}
