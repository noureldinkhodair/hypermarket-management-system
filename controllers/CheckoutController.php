<?php

require_once __DIR__ . '/../patterns/singleton/Database.php';
require_once __DIR__ . '/../modeling/orders.php';
require_once __DIR__ . '/../modeling/cart.php';
require_once __DIR__ . '/../modeling/payment.php';
require_once __DIR__ . '/../modeling/delivery.php';
require_once __DIR__ . '/../modeling/homedelivery.php';
require_once __DIR__ . '/../modeling/storepickup.php';
require_once __DIR__ . '/../modeling/card payment.php';
require_once __DIR__ . '/../modeling/cash payment.php';

class CheckoutController {

    private Orders $orderModel;

    private Cart $cartModel;

    private PDO $db;

    public function __construct() {

        $this->db = Database::getConnection();

        $this->orderModel =
            new Orders($this->db);

        $this->cartModel =
            new Cart($this->db);
    }

    public function placeOrder(
        int $userId,
        string $paymentMethod,
        string $deliveryMethod,
        ?string $address = null
    ): int|false {

        if ($userId <= 0) {

            throw new InvalidArgumentException(
                'Valid user id is required'
            );
        }

        $total =
            $this->cartModel
                 ->calculateTotal($userId);

        if ($total <= 0) {

            throw new InvalidArgumentException(
                'Cart is empty'
            );
        }

        $orderId =
            $this->orderModel
                 ->createOrder(
                     $userId,
                     $total,
                     $paymentMethod,
                     $deliveryMethod
                 );

        if (!$orderId) {

            return false;
        }

        $cartItems =
            $this->cartModel
                 ->getCartItems($userId);

        foreach ($cartItems as $item) {

            $this->orderModel
                 ->addOrderProduct(

                     $orderId,

                     $item['product_id'],

                     $item['quantity'],

                     $item['price']

                 );

            $stmt = $this->db->prepare(

                "UPDATE product

                 SET stock_quantity =
                 stock_quantity - :quantity

                 WHERE product_id = :product_id"

            );

            $stmt->execute([

                ':quantity' => $item['quantity'],

                ':product_id' => $item['product_id']

            ]);
        }

        if (strtolower($paymentMethod) === 'visa') {

            $cardPayment =
                new CardPayment($this->db);

            $cardPayment->processPayment(
                $orderId,
                $total
            );

        } else {

            $cashPayment =
                new CashPayment($this->db);

            $cashPayment->processPayment(
                $orderId,
                $total
            );
        }

        if (strtolower($deliveryMethod) === 'home') {

            $homeDelivery =
                new HomeDelivery($this->db);

            $homeDelivery->deliverOrder(

                $orderId,

                $address ?? ''

            );

        } else {

            $storePickup =
                new StorePickup($this->db);

            $storePickup->deliverOrder(
                $orderId
            );
        }

        $this->clearCart($userId);

        return $orderId;
    }

    private function clearCart(
        int $userId
    ): void {

        $stmt = $this->db->prepare(

            "SELECT cart_id
             FROM cart
             WHERE user_id = :user_id
             LIMIT 1"

        );

        $stmt->execute([

            ':user_id' => $userId

        ]);

        $cart = $stmt->fetch();

        if(!$cart){

            return;
        }

        $stmt = $this->db->prepare(

            "DELETE FROM cart_product
             WHERE cart_id = :cart_id"

        );

        $stmt->execute([

            ':cart_id' => $cart['cart_id']

        ]);
    }
}