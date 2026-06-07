<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (
    !isset($_SESSION['user_id'])
    ||
    $_SESSION['user_role'] !== 'admin'
) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/ContactController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/DeliveryController.php';

$paymentController = new PaymentController();
$deliveryController = new DeliveryController();
$userController = new UserController();
$productController = new ProductController();
$categoryController = new CategoryController();
$orderController = new OrderController();
$contactController = new ContactController();

$users = $userController->index();
$products = $productController->index();
$payments = $paymentController->index();
$deliveries = $deliveryController->index();
$categories = $categoryController->index();
$orders = $orderController->index();
$messages = $contactController->index();

$totalRevenue = 0;

foreach ($orders as $o) {
    $totalRevenue += $o['total_amount'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
rel="stylesheet">

<style>

:root{
--green:#16a34a;
--bg:#f5f7f9;
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

.dashboard-layout{
display:flex;
}

.sidebar{
width:240px;
background:white;
border-right:1px solid #eee;
min-height:100vh;
padding:25px;
}

.sidebar h2{
color:var(--green);
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:12px;
margin:10px 0;
border-radius:10px;
cursor:pointer;
font-weight:600;
}

.sidebar a:hover{
background:var(--green);
color:white;
}

.main{
flex:1;
padding:30px;
}

.page{
display:none;
}

.page.active{
display:block;
}

.cards{
display:grid;
grid-template-columns:
repeat(auto-fit,minmax(220px,1fr));
gap:20px;
}

.card{
background:white;
padding:25px;
border-radius:18px;
box-shadow:0 6px 15px rgba(0,0,0,.05);
}

.card h3{
margin-bottom:10px;
}

.card p{
color:var(--green);
font-size:28px;
font-weight:bold;
}

.table-box{
background:white;
padding:20px;
border-radius:15px;
margin-top:20px;
overflow:auto;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:12px;
border-bottom:1px solid #eee;
}

th{
background:#dcfce7;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:15px;
}

.add-btn,
.edit-btn,
.delete-btn{
border:none;
color:white;
border-radius:8px;
cursor:pointer;
}

.add-btn{
background:#16a34a;
padding:10px 16px;
}

.edit-btn{
background:#22c55e;
padding:7px 14px;
}

.delete-btn{
background:#ef4444;
padding:7px 14px;
}

.actions{
display:flex;
gap:8px;
}

.popup{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,.4);
justify-content:center;
align-items:center;
z-index:9999;
}

.popup-box{
background:white;
padding:25px;
border-radius:15px;
width:450px;
max-height:90vh;
overflow:auto;
}

.popup-box input,
.popup-box textarea,
.popup-box select{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ddd;
border-radius:8px;
}

.popup-actions{
display:flex;
justify-content:flex-end;
gap:10px;
margin-top:15px;
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="dashboard-layout">

<div class="sidebar">

<h2>Admin Panel</h2>

<a onclick="showPage('dashboard')">Dashboard</a>
<a onclick="showPage('users')">Users</a>
<a onclick="showPage('categories')">Categories</a>
<a onclick="showPage('products')">Products</a>
<a onclick="showPage('orders')">Orders</a>
<a onclick="showPage('payments')">Payments</a>
<a onclick="showPage('deliveries')">Deliveries</a>
<a onclick="showPage('messages')">Messages</a>

</div>

<div class="main">


<div class="page active" id="dashboard">

<h1>Dashboard</h1>

<br>

<div class="cards">

<div class="card">
<h3>Total Users</h3>
<p><?= count($users) ?></p>
</div>

<div class="card">
<h3>Total Products</h3>
<p><?= count($products) ?></p>
</div>

<div class="card">
<h3>Total Orders</h3>
<p><?= count($orders) ?></p>
</div>

<div class="card">
<h3>Total Revenue</h3>
<p><?= number_format($totalRevenue,2) ?> EGP</p>
</div>

</div>

</div>


<div class="page" id="users">

<div class="top-bar">

<h1>Users</h1>

<button class="add-btn"
onclick="openAddUser()">

+ Add User

</button>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Role</th>
<th>Actions</th>

</tr>

<?php foreach($users as $u): ?>

<tr>

<td><?= $u['user_id'] ?></td>
<td><?= htmlspecialchars($u['name']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= htmlspecialchars($u['phone']) ?></td>
<td><?= htmlspecialchars($u['role']) ?></td>

<td>

<div class="actions">

<button class="edit-btn"
onclick='openEditUser(
<?= json_encode($u) ?>
)'>

Edit

</button>

<button class="delete-btn"

onclick="
if(confirm('Delete user?'))
{
window.location.href=
'../actions/admin_action.php?type=user&action=delete&id=<?= $u['user_id'] ?>';
}
">

Delete

</button>

</div>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>


<div class="page" id="categories">

<div class="top-bar">

<h1>Categories</h1>

<button class="add-btn"
onclick="openAddCategory()">

+ Add Category

</button>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Actions</th>

</tr>

<?php foreach($categories as $c): ?>

<tr>

<td><?= $c['category_id'] ?></td>

<td><?= htmlspecialchars($c['name']) ?></td>

<td>

<div class="actions">

<button class="edit-btn"
onclick='openEditCategory(
<?= json_encode($c) ?>
)'>

Edit

</button>

<button class="delete-btn"

onclick="
if(confirm('Delete category?'))
{
window.location.href=
'../actions/admin_action.php?type=category&action=delete&id=<?= $c['category_id'] ?>';
}
">

Delete

</button>

</div>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>


<div class="page" id="products">

<div class="top-bar">

<h1>Products</h1>

<button class="add-btn"
onclick="openAddProduct()">

+ Add Product

</button>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
<th>Actions</th>

</tr>

<?php foreach($products as $p): ?>

<tr>

<td><?= $p['product_id'] ?></td>

<td><?= htmlspecialchars($p['name']) ?></td>

<td><?= number_format($p['price'],2) ?> EGP</td>

<td><?= $p['stock_quantity'] ?></td>

<td>

<div class="actions">

<button class="edit-btn"
onclick='openEditProduct(
<?= json_encode($p) ?>
)'>

Edit

</button>

<button class="delete-btn"

onclick="
if(confirm('Delete product?'))
{
window.location.href=
'../actions/admin_action.php?type=product&action=delete&id=<?= $p['product_id'] ?>';
}
">

Delete

</button>

</div>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<!-- ORDERS -->

<div class="page" id="orders">

<div class="top-bar">

<h1>Orders</h1>

<button class="add-btn"
onclick="openAddOrder()">

+ Add Order

</button>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>User</th>
<th>Total</th>
<th>Status</th>
<th>Actions</th>

</tr>

<?php foreach($orders as $o): ?>

<tr>

<td><?= $o['order_id'] ?></td>

<td><?= $o['user_id'] ?></td>

<td><?= number_format($o['total_amount'],2) ?> EGP</td>

<td><?= htmlspecialchars($o['status']) ?></td>

<td>

<div class="actions">

<button class="edit-btn"
onclick='openEditOrder(
<?= json_encode($o) ?>
)'>

Edit

</button>

<button class="delete-btn"

onclick="
if(confirm('Delete order?'))
{
window.location.href=
'../actions/admin_action.php?type=order&action=delete&id=<?= $o['order_id'] ?>';
}
">

Delete

</button>

</div>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<div class="page" id="payments">

<div class="top-bar">

<h1>Payments</h1>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>Order ID</th>
<th>Method</th>
<th>Amount</th>
<th>Status</th>

</tr>

<?php foreach($payments as $pay): ?>

<tr>

<td><?= $pay['payment_id'] ?></td>

<td><?= $pay['order_id'] ?></td>

<td><?= htmlspecialchars($pay['payment_method']) ?></td>

<td><?= number_format($pay['amount'],2) ?> EGP</td>

<td><?= htmlspecialchars($pay['payment_status']) ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<div class="page" id="deliveries">

<div class="top-bar">

<h1>Deliveries</h1>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>Order ID</th>
<th>Address</th>
<th>Method</th>
<th>Status</th>

</tr>

<?php foreach($deliveries as $d): ?>

<tr>

<td><?= $d['delivery_id'] ?></td>

<td><?= $d['order_id'] ?></td>

<td><?= htmlspecialchars($d['address']) ?></td>

<td><?= htmlspecialchars($d['delivery_method']) ?></td>

<td><?= htmlspecialchars($d['delivery_status']) ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<div class="page" id="messages">

<div class="top-bar">

<h1>Messages</h1>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Message</th>
<th>Actions</th>

</tr>

<?php foreach($messages as $m): ?>

<tr>

<td><?= $m['message_id'] ?></td>

<td><?= htmlspecialchars($m['name']) ?></td>

<td><?= htmlspecialchars($m['email']) ?></td>

<td><?= htmlspecialchars($m['message']) ?></td>

<td>

<button class="delete-btn"

onclick="
if(confirm('Delete message?'))
{
window.location.href=
'../actions/admin_action.php?type=message&action=delete&id=<?= $m['message_id'] ?>';
}
">

Delete

</button>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

</div>


<div class="popup" id="popup">

<div class="popup-box">

<h2 id="popupTitle"></h2>

<form id="popupForm"
method="POST">

<div id="popupContent"></div>

<div class="popup-actions">

<button type="button"
class="delete-btn"
onclick="closePopup()">

Cancel

</button>

<button type="submit"
class="add-btn">

Save

</button>

</div>

</form>

</div>

</div>

<script>

function showPage(pageId){

document.querySelectorAll('.page')
.forEach(page => {

page.classList.remove('active');

});

document.getElementById(pageId)
.classList.add('active');

localStorage.setItem(
'activePage',
pageId
);
}

window.onload = function(){

let activePage =
localStorage.getItem('activePage');

if(activePage){

showPage(activePage);
}
};



function closePopup(){

document.getElementById('popup')
.style.display='none';
}




function openAddUser(){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Add User';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=user&action=add';

document.getElementById('popupContent')
.innerHTML=`

<input type="text"
name="name"
placeholder="Name"
required>

<input type="email"
name="email"
placeholder="Email"
required>

<input type="password"
name="password"
placeholder="Password"
required>

<input type="text"
name="phone"
placeholder="Phone">

<select name="role">

<option value="customer">
Customer
</option>

<option value="admin">
Admin
</option>

</select>
`;
}



function openEditUser(user){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Edit User';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=user&action=edit';

document.getElementById('popupContent')
.innerHTML=`

<input type="hidden"
name="user_id"
value="${user.user_id}">

<input type="text"
name="name"
value="${user.name}"
required>

<input type="email"
name="email"
value="${user.email}"
required>

<input type="text"
name="phone"
value="${user.phone}">

<select name="role"
id="edit_role">

<option value="customer">
Customer
</option>

<option value="admin">
Admin
</option>

</select>
`;

document.getElementById('edit_role')
.value=user.role;
}




function openAddCategory(){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Add Category';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=category&action=add';

document.getElementById('popupContent')
.innerHTML=`

<input type="text"
name="name"
placeholder="Category Name"
required>

<input type="text"
name="image"
placeholder="Image">
`;
}



function openEditCategory(category){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Edit Category';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=category&action=edit';

document.getElementById('popupContent')
.innerHTML=`

<input type="hidden"
name="category_id"
value="${category.category_id}">

<input type="text"
name="name"
value="${category.name}"
required>

<input type="text"
name="image"
value="${category.image ?? ''}">
`;
}




function openAddProduct(){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Add Product';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=product&action=add';

document.getElementById('popupContent')
.innerHTML=`

<input type="number"
name="category_id"
placeholder="Category ID"
required>

<input type="text"
name="name"
placeholder="Product Name"
required>

<textarea
name="description"
placeholder="Description"></textarea>

<input type="number"
step="0.01"
name="price"
placeholder="Price"
required>

<input type="number"
name="stock_quantity"
placeholder="Stock"
required>

<input type="text"
name="image"
placeholder="Image">
`;
}



function openEditProduct(product){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Edit Product';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=product&action=edit';

document.getElementById('popupContent')
.innerHTML=`

<input type="hidden"
name="product_id"
value="${product.product_id}">

<input type="number"
name="category_id"
value="${product.category_id}"
required>

<input type="text"
name="name"
value="${product.name}"
required>

<textarea
name="description">${product.description}</textarea>

<input type="number"
step="0.01"
name="price"
value="${product.price}"
required>

<input type="number"
name="stock_quantity"
value="${product.stock_quantity}"
required>

<input type="text"
name="image"
value="${product.image ?? ''}">
`;
}




function openAddOrder(){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Add Order';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=order&action=add';

document.getElementById('popupContent')
.innerHTML=`

<input type="number"
name="user_id"
placeholder="User ID"
required>

<input type="number"
step="0.01"
name="total_amount"
placeholder="Total Amount"
required>

<select name="payment_method">

<option value="Cash">
Cash
</option>

<option value="Visa">
Visa
</option>

</select>

<select name="delivery_method">

<option value="Pickup">
Pickup
</option>

<option value="Home Delivery">
Home Delivery
</option>

</select>
`;
}



function openEditOrder(order){

document.getElementById('popup')
.style.display='flex';

document.getElementById('popupTitle')
.innerText='Edit Order';

document.getElementById('popupForm')
.action='../actions/admin_action.php?type=order&action=edit';

document.getElementById('popupContent')
.innerHTML=`

<input type="hidden"
name="order_id"
value="${order.order_id}">

<input type="text"
name="status"
value="${order.status}"
required>
`;
}

</script>
<div id="footer"></div>

<script>

window.loggedUser = <?= json_encode([

'name' => $_SESSION['user_name'],
'role' => $_SESSION['user_role']

]); ?>;

</script>

<script src="main.js"></script>
</body>
</html>