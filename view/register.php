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

<title>Register - Seoudi Market</title>

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

.register-container{
  display:flex;
  justify-content:center;
  align-items:center;
  height:85vh;
}

.register-box{
  background:white;
  padding:35px;
  width:380px;
  border-radius:15px;
  box-shadow:0 6px 20px rgba(0,0,0,0.08);
}

.register-box h2{
  text-align:center;
  margin-bottom:20px;
}

.register-box input{
  width:100%;
  padding:12px;
  margin:10px 0;
  border:1px solid #ddd;
  border-radius:8px;
}

.register-btn{
  background:var(--green);
  color:white;
  border:none;
  padding:12px;
  width:100%;
  border-radius:8px;
  cursor:pointer;
  margin-top:10px;
}

.login-btn-small{
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

.login-btn-small:hover{
  background:#15803d;
}

.extra{
  text-align:center;
  margin-top:15px;
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

<div class="register-container">

  <div class="register-box">

    <h2>Create Account</h2>

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



    <form action="../actions/register.php"
          method="POST"
          autocomplete="off">

      <input
      type="text"
      name="name"
      placeholder="Full Name"
      required
      minlength="3"
      maxlength="50"
      pattern="[A-Za-z\s]+"
      title="Name must contain letters only">



      <input
      type="email"
      name="email"
      placeholder="Email"
      required>



      <input
      type="tel"
      name="phone"
      placeholder="Phone Number"
      pattern="[0-9]{11}"
      maxlength="11"
      required
      title="Phone number must contain 11 digits">



      <input
      type="password"
      name="password"
      placeholder="Password"
      required
      minlength="8"
      pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$"
      title="Password must contain at least 8 characters including letters and numbers">



      <input
      type="password"
      name="confirm"
      placeholder="Confirm Password"
      required
      minlength="8">



      <button type="submit"
              class="register-btn">

        Register

      </button>

    </form>



    <div class="extra">

      Already have an account?

      <button class="login-btn-small"
              onclick="window.location.href='login.php'">

        Login

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

<script>

document.querySelector('input[name="phone"]')
.addEventListener('input', function(){

    this.value =
        this.value.replace(/[^0-9]/g,'');

});



document.querySelector('input[name="name"]')
.addEventListener('input', function(){

    this.value =
        this.value.replace(/[^A-Za-z\s]/g,'');

});

</script>

</body>

</html>