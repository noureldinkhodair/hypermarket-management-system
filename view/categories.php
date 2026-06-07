<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../controllers/CategoryController.php';

$controller = new CategoryController();

$categories = $controller->index();

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Categories - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

.grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
  padding:40px;
}

.category-card {
  background:white;
  border-radius:15px;
  padding:30px;
  text-align:center;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
  transition:0.3s;
  text-decoration:none;
  color:inherit;
}

.category-card:hover {
  transform:translateY(-6px);
}

.category-card img {
  width:80px;
  height:80px;
  object-fit:cover;
  border-radius:50%;
  margin-bottom:15px;
}

.category-card i {
  font-size:35px;
  color:var(--green);
  background:#dcfce7;
  padding:20px;
  border-radius:50%;
  margin-bottom:15px;
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="page-header">
  <h1>🧩 Categories</h1>
</div>

<div class="grid">

<?php foreach($categories as $category): ?>

<a class="category-card"
   href="products.php?cat=<?php echo $category['category_id']; ?>">

    <?php if(!empty($category['image'])): ?>

        <img
            src="../uploads/<?php echo htmlspecialchars($category['image']); ?>"
            alt="<?php echo htmlspecialchars($category['name']); ?>"
        >

    <?php else: ?>

        <i class="fa-solid fa-layer-group"></i>

    <?php endif; ?>

    <h3>
        <?php echo htmlspecialchars($category['name']); ?>
    </h3>

</a>

<?php endforeach; ?>

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

</body>
</html>