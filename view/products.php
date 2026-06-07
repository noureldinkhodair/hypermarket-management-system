<?php
session_start();

require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';

$productController  = new ProductController();
$categoryController = new CategoryController();

$allProducts =
    $productController->index();

$allCategories =
    $categoryController->index();

$cat =
    (int)($_GET['cat'] ?? 0);

$search =
    trim($_GET['search'] ?? '');



if($search !== ''){

    $filtered = array_filter(

        $allProducts,

        function($p) use ($search){

            return stripos(

                $p['name'],
                $search

            ) !== false;

        }

    );

} elseif ($cat > 0) {

    $filtered = array_filter(

        $allProducts,

        function($p) use ($cat) {

            return (int)$p['category_id']
                   === $cat;

        }

    );

} else {

    $filtered = $allProducts;
}

$filtered = array_values($filtered);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Products - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

.header {
  padding:30px 40px;
}

.filter {
  padding:0 40px;
  margin-bottom:20px;
}

.filter select {
  padding:8px 12px;
  border:1px solid #ddd;
  border-radius:8px;
}

.grid {
  display:grid;
  grid-template-columns:
    repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
  padding:40px;
}

.product {
  position:relative;
  background:white;
  border-radius:15px;
  padding:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
  cursor:pointer;
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
}

.price {
  color:var(--green);
  font-weight:bold;
}

.btn {
  margin-top:10px;
  background:var(--green);
  color:white;
  border:none;
  padding:10px;
  width:100%;
  border-radius:8px;
  cursor:pointer;
}

.fav-btn {
  position:absolute;
  top:10px;
  right:10px;
  background:white;
  border:none;
  width:35px;
  height:35px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
}

.fav-btn i {
  color:#ccc;
}

.fav-btn.active i {
  color:red;
}

.empty-msg {
  text-align:center;
  color:#999;
  padding:40px;
  grid-column:1/-1;
}

.search-title{
  padding:0 40px;
  color:#666;
  margin-top:-10px;
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="header">

  <h1>
    Products
  </h1>

</div>

<?php if($search !== ''): ?>

  <div class="search-title">

    Search results for:

    <strong>
      <?= htmlspecialchars($search) ?>
    </strong>

  </div>

<?php endif; ?>

<div class="filter">

  <select onchange="filterByCategory(this.value)">

    <option value="">
      All Categories
    </option>

    <?php foreach ($allCategories as $category): ?>

      <option
        value="<?= $category['category_id'] ?>"
        <?= $category['category_id'] == $cat
            ? 'selected'
            : '' ?>
      >

        <?= htmlspecialchars($category['name']) ?>

      </option>

    <?php endforeach; ?>

  </select>

</div>

<div class="grid">

  <?php if (empty($filtered)): ?>

    <p class="empty-msg">

      No products found.

    </p>

  <?php else: ?>

    <?php foreach ($filtered as $p): ?>

      <div class="product"
           onclick="goProduct(<?= $p['product_id'] ?>)">

        <button class="fav-btn"
                onclick="event.stopPropagation();
                         addFavorite(
                         <?= $p['product_id'] ?>,
                         this)">

          <i class="fa-regular fa-heart"></i>

        </button>

        <img
          src="<?= $p['image']
            ? htmlspecialchars($p['image'])
            : 'https://via.placeholder.com/200' ?>"

          alt="<?= htmlspecialchars($p['name']) ?>"
        >

        <h3>

          <?= htmlspecialchars($p['name']) ?>

        </h3>

        <p class="price">

          <?= number_format($p['price'], 2) ?>

          EGP

        </p>

        <?php if ($p['stock_quantity'] > 0): ?>

  <button class="btn"
          onclick="event.stopPropagation();
                   addToCart(
                   <?= $p['product_id'] ?>)">

    Add to Cart

  </button>

<?php else: ?>

  <button class="btn"
          style="
            background:#9ca3af;
            cursor:not-allowed;
          "
          disabled>

    Out of Stock

  </button>

<?php endif; ?>

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

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

function goProduct(id)
{
    window.location.href =
      'product.php?id=' + id;
}

function filterByCategory(cat)
{
    window.location.href =
      'products.php?cat='
      + encodeURIComponent(cat);
}

function addToCart(productId)
{
    fetch('../actions/cart_add.php', {

        method: 'POST',

        headers: {
            'Content-Type':
            'application/x-www-form-urlencoded'
        },

        body:
          'product_id='
          + productId
          + '&quantity=1'

    })

    .then(r => r.json())

    .then(data => {

        alert(data.message);

    });
}

function addFavorite(productId, btn)
{
    fetch('../actions/favorite_add.php', {

        method: 'POST',

        headers: {
            'Content-Type':
            'application/x-www-form-urlencoded'
        },

        body:
          'product_id=' + productId

    })

    .then(r => r.json())

    .then(data => {

        if (data.success)
        {
            btn.classList.add('active');

            btn.querySelector('i')
               .classList.replace(
                  'fa-regular',
                  'fa-solid'
               );
        }

        alert(data.message);

    });
}

</script>

</body>
</html>