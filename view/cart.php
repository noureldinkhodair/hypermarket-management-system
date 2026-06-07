<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');

    exit;
}

require_once __DIR__ . '/../controllers/CartController.php';

$controller = new CartController();

$cartItems = $controller->index((int)$_SESSION['user_id']);

$total = $controller->getTotal((int)$_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cart - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

<style>

:root {
  --green:#16a34a;
  --bg:#f5f7f9;
}

* {
  margin:0;
  padding:0;
  box-sizing:border-box;
}

body {
  font-family:'Cairo', sans-serif;
  background:var(--bg);
}

.page-header {
  padding:40px;
}

.cart-container {
  display:grid;
  grid-template-columns:2fr 1fr;
  gap:30px;
  padding:40px;
}

.cart-items,
.summary {
  background:white;
  padding:20px;
  border-radius:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
}

.cart-item {
  display:flex;
  justify-content:space-between;
  align-items:center;
  border-bottom:1px solid #eee;
  padding:15px 0;
}

.cart-item img {
  width:60px;
  height:60px;
  object-fit:cover;
  border-radius:8px;
}

.item-info {
  flex:1;
  padding:0 15px;
}

.remove-btn {
  background:#ef4444;
  color:white;
  border:none;
  padding:6px 12px;
  border-radius:6px;
  cursor:pointer;
}

.checkout-btn {
  background:var(--green);
  color:white;
  border:none;
  padding:12px;
  width:100%;
  border-radius:10px;
  cursor:pointer;
  margin-top:15px;
}

.empty-msg {
  text-align:center;
  color:#999;
  padding:30px;
}

@media(max-width:768px){

  .cart-container{
    grid-template-columns:1fr;
  }

}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="page-header">

  <h1>
    🛒 Your Cart
  </h1>

</div>

<div class="cart-container">

  <div class="cart-items">

    <?php if (empty($cartItems)): ?>

      <p class="empty-msg">
        Your cart is empty.
      </p>

    <?php else: ?>

      <?php foreach ($cartItems as $item): ?>

        <div class="cart-item"
             id="item-<?= $item['cart_product_id'] ?>">

          <?php if ($item['image']): ?>

            <img
              src="<?= htmlspecialchars($item['image']) ?>"
              alt="<?= htmlspecialchars($item['name']) ?>"
            >

          <?php endif; ?>

          <div class="item-info">

            <h4>
              <?= htmlspecialchars($item['name']) ?>
            </h4>

            <p>
              <?= number_format($item['price'], 2) ?>
              EGP
              ×
              <?= $item['quantity'] ?>
            </p>

            <p>

              <strong>
                <?= number_format($item['subtotal'], 2) ?>
                EGP
              </strong>

            </p>

          </div>

          <button class="remove-btn"
                  onclick="removeItem(<?= $item['cart_product_id'] ?>)">

            Remove

          </button>

        </div>

      <?php endforeach; ?>

    <?php endif; ?>

  </div>

  <div class="summary">

    <h2>
      Order Summary
    </h2>

    <p>

      Total:

      <strong id="totalAmount">

        <?= number_format($total, 2) ?>

      </strong>

      EGP

    </p>

    <?php if (!empty($cartItems)): ?>

      <a href="checkout.php">

        <button class="checkout-btn">

          Checkout

        </button>

      </a>

    <?php endif; ?>

  </div>

</div>

<div id="footer"></div>

<script>

window.loggedUser = <?=
isset($_SESSION['user_id'])
? json_encode([

    'name' => $_SESSION['user_name'],
    'role' => $_SESSION['user_role']

])
: 'null';
?>;

</script>

<script src="main.js"></script>

<script>

function removeItem(cartProductId) {

  if (!confirm('Remove this item?')) return;

  fetch('../actions/cart_remove.php', {

    method: 'POST',

    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },

    body: 'cart_product_id=' + cartProductId

  })
  .then(r => r.json())

  .then(data => {

    if (data.success) {

      let item =
        document.getElementById(
          'item-' + cartProductId
        );

      if(item){
        item.remove();
      }

      document
        .getElementById('totalAmount')
        .innerText = data.total;

      if(document.querySelectorAll('.cart-item').length === 0){

        location.reload();
      }

    } else {

      alert(data.message);
    }

  });

}

</script>

</body>
</html>