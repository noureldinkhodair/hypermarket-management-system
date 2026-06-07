<?php
require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/favorite.php';

class FavoriteController {
    private Favorite $favoriteModel;

    public function __construct() {
        $db = Database::getConnection();
        $this->favoriteModel = new Favorite($db);
    }

    public function index(int $userId): array {
        return $this->favoriteModel->getFavorites($userId);
    }

    public function store(int $userId, int $productId): bool {
        if ($userId <= 0) {
            throw new InvalidArgumentException('Valid user id is required');
        }
        if ($productId <= 0) {
            throw new InvalidArgumentException('Valid product id is required');
        }
        return $this->favoriteModel->addFavorite($userId, $productId);
    }

    public function destroy(int $favoriteId): bool {
        if ($favoriteId <= 0) {
            throw new InvalidArgumentException('Valid favorite id is required');
        }
        return $this->favoriteModel->removeFavorite($favoriteId);
    }
}
