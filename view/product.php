<?php
session_start();

require_once __DIR__ . '/../controllers/ProductController.php';

$id      = (int)($_GET['id'] ?? 0);
$product = null;

if ($id > 0) {
    $controller = new ProductController();
    $product    = $controller->show($id);
}

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($product['name']) ?> - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
  --green:#16a34a;
  --bg:#f5f7f9;
}

* {margin:0; padding:0; box-sizing:border-box;}

body {
  font-family:'Cairo', sans-serif;
  background:var(--bg);
}

.container {
  max-width:900px;
  margin:auto;
  padding:40px;
  display:flex;
  flex-direction:column;
  gap:25px;
}

.image-box {
  position:relative;
  background:white;
  padding:20px;
  border-radius:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
}

.image-box img {
  width:100%;
  aspect-ratio:16/9;
  object-fit:cover;
  border-radius:10px;
}

.fav-btn {
  position:absolute;
  top:20px;
  right:20px;
  background:white;
  border:none;
  width:40px;
  height:40px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.fav-btn i {
  color:#ccc;
  font-size:18px;
}

.fav-btn.active i {
  color:#ef4444;
}

.details {
  background:white;
  padding:25px;
  border-radius:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
}

.details h1 {
  margin-bottom:15px;
}

.price {
  color:var(--green);
  font-size:22px;
  font-weight:bold;
  margin:10px 0;
}

.desc {
  color:#555;
  margin:15px 0;
  line-height:1.6;
}

.stock {
  color:#888;
  font-size:14px;
  margin-bottom:15px;
}

.btn {
  background:var(--green);
  color:white;
  border:none;
  padding:12px;
  width:100%;
  border-radius:10px;
  cursor:pointer;
  font-size:16px;
}

@media(max-width:768px){
  .container { padding:20px; }
}
</style>

</head>

<body>

<div id="navbar"></div>

<div class="container">

  <div class="image-box">
    <button class="fav-btn" onclick="addFavorite(<?= $product['product_id'] ?>, this)">
      <i class="fa-regular fa-heart"></i>
    </button>

    <img src="<?= $product['image'] ? htmlspecialchars($product['image']) : 'https://via.placeholder.com/800x450' ?>"
         alt="<?= htmlspecialchars($product['name']) ?>">
  </div>

  <div class="details">
    <h1><?= htmlspecialchars($product['name']) ?></h1>

    <p class="price"><?= number_format($product['price'], 2) ?> EGP</p>

    <p class="stock">In stock: <?= $product['stock_quantity'] ?> units</p>

    <p class="desc"><?= htmlspecialchars($product['description'] ?? 'No description available.') ?></p>

    <button class="btn" onclick="addToCart(<?= $product['product_id'] ?>)">Add to Cart</button>
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
function addToCart(productId) {
  fetch('../actions/cart_add.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + productId + '&quantity=1'
  })
  .then(r => r.json())
  .then(data => { alert(data.message); });
}

function addFavorite(productId, btn) {
  fetch('../actions/favorite_add.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + productId
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      btn.classList.add('active');
      btn.querySelector('i').classList.replace('fa-regular', 'fa-solid');
    }
    alert(data.message);
  });
}
</script>

</body>
</html>
