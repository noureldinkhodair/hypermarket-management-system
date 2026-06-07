<?php
session_start();

if (isset($_SESSION['user_id'])) {

    header('Location: index.php');

    exit;
}

$error =
    $_SESSION['error'] ?? '';

$success =
    $_SESSION['success'] ?? '';

unset(
    $_SESSION['error'],
    $_SESSION['success']
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Login - Seoudi Market</title>

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
  font-family:'Cairo', sans-serif;
  background:var(--bg);
}

.login-container{
  display:flex;
  justify-content:center;
  align-items:center;
  height:80vh;
}

.login-box{
  background:white;
  padding:35px;
  width:350px;
  border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.08);
}

.login-box h2{
  text-align:center;
  margin-bottom:20px;
}

.login-box input{
  width:100%;
  padding:12px;
  margin:10px 0;
  border:1px solid #ddd;
  border-radius:8px;
}

.login-btn{
  background:var(--green);
  color:white;
  border:none;
  padding:12px;
  width:100%;
  border-radius:8px;
  cursor:pointer;
}

.extra{
  text-align:center;
  margin-top:15px;
}

.register-btn{
  background:#16a34a;
  color:white;
  border:none;
  padding:8px 16px;
  border-radius:8px;
  cursor:pointer;
  margin-left:8px;
  font-weight:600;
  transition:.3s;
}

.register-btn:hover{
  background:#15803d;
}

.alert{
  padding:10px;
  border-radius:8px;
  margin-bottom:15px;
  text-align:center;
}

.alert-error{
  background:#fee2e2;
  color:#dc2626;
}

.alert-success{
  background:#dcfce7;
  color:#16a34a;
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="login-container">

  <div class="login-box">

    <h2>Login</h2>

    <?php if ($error): ?>

      <div class="alert alert-error">

        <?= htmlspecialchars($error) ?>

      </div>

    <?php endif; ?>



    <?php if ($success): ?>

      <div class="alert alert-success">

        <?= htmlspecialchars($success) ?>

      </div>

    <?php endif; ?>



    <form action="../actions/login.php"
          method="POST"
          autocomplete="off">

      <input
      type="email"
      name="email"
      placeholder="Email"
      required>



      <input
      type="password"
      name="password"
      placeholder="Password"
      required
      minlength="8"
      title="Password must contain at least 8 characters">



      <button type="submit"
              class="login-btn">

        Login

      </button>

    </form>



    <div class="extra">

      Don't have an account?

      <button class="register-btn"
              onclick="window.location.href='register.php'">

        Register

      </button>

    </div>

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

</body>

</html>