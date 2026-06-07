<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../controllers/CategoryController.php';

$categoryController = new CategoryController();

$categories = $categoryController->index();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

.hero {
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:white;
  padding:80px 40px 120px;
}

.hero h1 {
  font-size:42px;
  max-width:500px;
}

.hero p {
  margin-top:15px;
  max-width:500px;
}

.hero button {
  margin-top:20px;
  padding:12px 20px;
  border:none;
  border-radius:10px;
  background:white;
  color:var(--green);
  font-weight:bold;
  cursor:pointer;
}

.section {
  padding:40px;
}

.title {
  font-size:24px;
  margin-bottom:20px;
}

.grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
}

.category {
  background:white;
  border-radius:15px;
  padding:30px;
  text-align:center;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
  transition:.3s;
  cursor:pointer;
}

.category:hover {
  transform:translateY(-5px);
}

.category img {
  width:80px;
  height:80px;
  object-fit:cover;
  border-radius:50%;
  margin-bottom:10px;
}

.category i {
  font-size:30px;
  color:var(--green);
  background:#dcfce7;
  padding:20px;
  border-radius:50%;
  margin-bottom:10px;
}

.offer {
  background:linear-gradient(135deg,#f59e0b,#f97316);
  color:white;
  padding:40px;
  border-radius:20px;
  text-align:center;
  margin:40px;
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="hero">

  <h1>
    Fresh Groceries Delivered to Your Door
  </h1>

  <p>
    Shop from a wide selection of fresh products everyday.
  </p>

  <button onclick="goCategories()">
    Shop Now →
  </button>

</div>

<div class="section">

  <h2 class="title">
    Shop by Category
  </h2>

  <div class="grid">

    <?php foreach($categories as $category): ?>

      <div class="category"
           onclick="goCategory(<?= $category['category_id'] ?>)">

        <?php if(!empty($category['image'])): ?>

          <img
            src="<?= htmlspecialchars($category['image']) ?>"
            alt="<?= htmlspecialchars($category['name']) ?>"
          >

        <?php else: ?>

          <i class="fa-solid fa-layer-group"></i>

        <?php endif; ?>

        <h3>
          <?= htmlspecialchars($category['name']) ?>
        </h3>

      </div>

    <?php endforeach; ?>

  </div>

</div>

<div class="offer">

  <h2>
    🎉 Special Offer!
  </h2>

  <p>
    Get up to 30% off on fresh products
  </p>

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

function goCategories()
{
    window.location.href = "categories.php";
}

function goCategory(categoryId)
{
    window.location.href =
        "products.php?cat=" + categoryId;
}

</script>

</body>
</html>