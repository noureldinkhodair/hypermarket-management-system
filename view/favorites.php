<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../controllers/FavoriteController.php';

$controller = new FavoriteController();
$favorites  = $controller->index((int)$_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Favorites - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --green:#16a34a;
  --light:#22c55e;
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

.header {
  padding:40px;
}

.header h1 {
  font-size:28px;
}

.grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
  padding:40px;
}

.product {
  background:white;
  border-radius:15px;
  padding:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
  transition:.3s;
}

.product:hover {
  transform:translateY(-5px);
}

.product img {
  width:100%;
  height:150px;
  object-fit:cover;
  border-radius:10px;
}

.product h3 {
  margin:10px 0;
  font-size:16px;
}

.price {
  color:var(--green);
  font-weight:bold;
}

.actions {
  display:flex;
  gap:10px;
  margin-top:10px;
}

.btn {
  flex:1;
  background:var(--green);
  color:white;
  border:none;
  padding:10px;
  border-radius:8px;
  cursor:pointer;
}

.remove {
  background:#ef4444;
}

.empty-msg {
  text-align:center;
  color:#999;
  padding:40px;
}
</style>

</head>

<body>

<div id="navbar"></div>

<div class="header">
  <h1>💚 Your Favorites</h1>
</div>

<?php if (empty($favorites)): ?>
  <p class="empty-msg">No favorites yet. Browse products and add some!</p>
<?php else: ?>
  <div class="grid">
    <?php foreach ($favorites as $fav): ?>
      <div class="product" id="fav-<?= $fav['favorite_id'] ?>">
        <img src="<?= $fav['image'] ? htmlspecialchars($fav['image']) : 'https://via.placeholder.com/200' ?>"
             alt="<?= htmlspecialchars($fav['name']) ?>">

        <h3><?= htmlspecialchars($fav['name']) ?></h3>
        <p class="price"><?= number_format($fav['price'], 2) ?> EGP</p>

        <div class="actions">
          <button class="btn" onclick="addToCart(<?= $fav['product_id'] ?>)">Add to Cart</button>
          <button class="btn remove" onclick="removeFavorite(<?= $fav['favorite_id'] ?>)">Remove</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
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
function removeFavorite(favoriteId) {
  if (!confirm('Remove from favorites?')) return;

  fetch('../actions/favorite_remove.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'favorite_id=' + favoriteId
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('fav-' + favoriteId).remove();
    } else {
      alert(data.message);
    }
  });
}

function addToCart(productId) {
  fetch('../actions/cart_add.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + productId + '&quantity=1'
  })
  .then(r => r.json())
  .then(data => {
    alert(data.message);
  });
}
</script>

</body>
</html>
