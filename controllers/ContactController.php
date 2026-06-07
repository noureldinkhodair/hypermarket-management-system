<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/contact.php';

class ContactController {

    private ContactMessage $contactModel;

    public function __construct() {

        $db =
            Database::getConnection();

        $this->contactModel =
            new ContactMessage($db);
    }

    public function index(): array {

        return $this->contactModel
                    ->viewAllMessages();
    }

    public function store(
        string $name,
        string $email,
        string $phone,
        string $message
    ): bool {

        if (trim($name) === '') {

            throw new InvalidArgumentException(
                'Name is required'
            );
        }

        if (trim($message) === '') {

            throw new InvalidArgumentException(
                'Message is required'
            );
        }

        return $this->contactModel
                    ->sendMessage(
                        $name,
                        $email,
                        $phone,
                        $message
                    );
    }

    public function delete(
        int $id
    ): bool {

        if ($id <= 0) {

            throw new InvalidArgumentException(
                'Valid message id is required'
            );
        }

        return $this->contactModel
                    ->deleteMessage($id);
    }
}