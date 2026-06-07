<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../controllers/OrderController.php';

$controller = new OrderController();

$userId = (int)$_SESSION['user_id'];

$orders = $controller->index($userId);

$success = $_SESSION['success'] ?? '';

unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Orders - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
      rel="stylesheet">

<style>

:root{
  --green:#16a34a;
  --bg:#f5f7f9;
  --red:#ef4444;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
}

body{
  font-family:'Cairo',sans-serif;
  background:var(--bg);
}

.header{
  padding:40px;
}

.header h1{
  font-size:32px;
}

.order{
  background:white;
  margin:20px 40px;
  padding:25px;
  border-radius:18px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
  transition:.3s;
  border:1px solid transparent;
}

.order:hover{
  transform:translateY(-3px);
  border-color:#dcfce7;
}

.order-top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:20px;
}

.status{
  padding:6px 14px;
  border-radius:20px;
  font-size:14px;
  font-weight:bold;
}

.status.pending{
  background:#fef3c7;
  color:#d97706;
}

.status.processing{
  background:#dbeafe;
  color:#2563eb;
}

.status.delivered{
  background:#dcfce7;
  color:#16a34a;
}

.status.cancelled{
  background:#fee2e2;
  color:#dc2626;
}

.details p{
  margin:8px 0;
  color:#555;
}

.products{
  margin-top:20px;
  padding-top:15px;
  border-top:1px solid #eee;
}

.products h4{
  margin-bottom:10px;
}

.product{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 0;
  border-bottom:1px solid #f3f3f3;
}

.cancel-btn{
  margin-top:18px;
  background:var(--red);
  color:white;
  border:none;
  padding:10px 16px;
  border-radius:10px;
  cursor:pointer;
  transition:.3s;
}

.cancel-btn:hover{
  opacity:.9;
}

.empty-msg{
  text-align:center;
  color:#999;
  padding:50px;
  font-size:18px;
}

.alert-success{
  background:#dcfce7;
  color:#16a34a;
  padding:12px;
  border-radius:10px;
  margin:20px 40px;
  text-align:center;
  font-weight:600;
}

@media(max-width:768px){

  .order{
    margin:20px;
  }

  .order-top{
    flex-direction:column;
    align-items:flex-start;
    gap:10px;
  }

  .product{
    flex-direction:column;
    align-items:flex-start;
    gap:5px;
  }
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="header">

  <h1>
    📦 My Orders
  </h1>

</div>

<?php if ($success): ?>

  <div class="alert-success">

    <?= htmlspecialchars($success) ?>

  </div>

<?php endif; ?>

<?php if (empty($orders)): ?>

  <p class="empty-msg">

    You have no orders yet.

  </p>

<?php else: ?>

  <?php foreach ($orders as $order): ?>

    <?php
      $items =
        $controller->getItems(
          (int)$order['order_id']
        );
    ?>

    <div class="order"
         id="order-<?= $order['order_id'] ?>">

      <div class="order-top">

        <h3>

          Order #<?= $order['order_id'] ?>

        </h3>

        <span class="status <?= strtolower($order['status']) ?>">

          <?= ucfirst($order['status']) ?>

        </span>

      </div>

      <div class="details">

        <p>

          <strong>Total:</strong>

          <?= number_format($order['total_amount'],2) ?>

          EGP

        </p>

        <p>

          <strong>Delivery:</strong>

          <?= ucfirst($order['delivery_method']) ?>

        </p>

        <p>

          <strong>Payment:</strong>

          <?= ucfirst($order['payment_method']) ?>

        </p>

        <p>

          <strong>Date:</strong>

          <?= date('d M Y - h:i A', strtotime($order['created_at'])) ?>

        </p>

      </div>

      <?php if (!empty($items)): ?>

        <div class="products">

          <h4>
            Products
          </h4>

          <?php foreach ($items as $item): ?>

            <div class="product">

              <span>

                <?= htmlspecialchars($item['name']) ?>

                ×

                <?= $item['quantity'] ?>

              </span>

              <span>

                <?= number_format(
                  $item['price'] * $item['quantity'],
                  2
                ) ?>

                EGP

              </span>

            </div>

          <?php endforeach; ?>

        </div>

      <?php endif; ?>

      <?php if (
        $order['status'] !== 'cancelled'
        &&
        $order['status'] !== 'delivered'
      ): ?>

        <button class="cancel-btn"
                onclick="cancelOrder(<?= $order['order_id'] ?>)">

          Cancel Order

        </button>

      <?php endif; ?>

    </div>

  <?php endforeach; ?>

<?php endif; ?>

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

function cancelOrder(orderId){

  if(
    !confirm(
      'Are you sure you want to cancel this order?'
    )
  ) return;

  fetch('../actions/order_cancel.php', {

    method:'POST',

    headers:{
      'Content-Type':
      'application/x-www-form-urlencoded'
    },

    body:'order_id=' + orderId

  })

  .then(r => r.json())

  .then(data => {

    if(data.success){

      location.reload();

    } else {

      alert(data.message);
    }

  });

}

</script>

</body>
</html>