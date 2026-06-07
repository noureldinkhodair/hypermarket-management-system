<?php
session_start();

$error   = $_SESSION['error']   ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Contact - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

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

.page-header { padding:40px; }

.container {
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:30px;
  padding:40px;
}

.form-box, .info-box {
  background:white;
  padding:30px;
  border-radius:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.06);
}

.form-box input,
.form-box textarea {
  width:100%;
  padding:12px;
  margin:10px 0;
  border:1px solid #ddd;
  border-radius:8px;
}

.form-box textarea {
  height:120px;
}

.form-box button {
  background:var(--green);
  color:white;
  border:none;
  padding:12px;
  width:100%;
  border-radius:8px;
  cursor:pointer;
}

.alert {
  padding:10px;
  border-radius:8px;
  margin-bottom:15px;
  text-align:center;
}

.alert-error   { background:#fee2e2; color:#dc2626; }
.alert-success { background:#dcfce7; color:#16a34a; }
</style>

</head>

<body>

<div id="navbar"></div>

<div class="page-header">
  <h1>📞 Contact Us</h1>
</div>

<div class="container">

  <div class="form-box">
    <h2>Send Message</h2>

    <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form action="../actions/contact.php" method="POST">

      <input type="text" name="name"    placeholder="Your Name"         required>
      <input type="tel"  name="phone"   placeholder="Your Phone Number">
      <textarea          name="message" placeholder="Your Message"      required></textarea>

      <button type="submit">Send</button>

    </form>
  </div>

  <div class="info-box">
    <h2>Our Info</h2>

    <p>📞 Phone: 16176</p>
    <p>📧 Email: info@seoudi.com</p>
    <p>🕒 Working Hours: 8 AM - 10 PM</p>

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
