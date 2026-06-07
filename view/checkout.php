<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');

    exit;
}

require_once __DIR__ . '/../controllers/CartController.php';

$controller = new CartController();

$total = $controller->getTotal(
    (int)$_SESSION['user_id']
);

$error   = $_SESSION['error']   ?? '';
$success = $_SESSION['success'] ?? '';

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

<title>Checkout - Seoudi Market</title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

:root{
  --green:#16a34a;
  --light:#22c55e;
  --bg:#f5f7f9;
  --dark:#111827;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
}

body{
  font-family:'Cairo',sans-serif;
  background:var(--bg);
  color:#222;
}

.page-header{
  padding:50px 40px 20px;
}

.page-header h1{
  font-size:36px;
  color:var(--dark);
}

.page-header p{
  color:#777;
  margin-top:8px;
}

.checkout-wrapper{
  display:grid;
  grid-template-columns:2fr 1fr;
  gap:30px;
  padding:20px 40px 60px;
  align-items:start;
}

.checkout-left{
  display:flex;
  flex-direction:column;
  gap:25px;
}

.card{
  background:white;
  border-radius:20px;
  padding:28px;
  box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.card-title{
  display:flex;
  align-items:center;
  gap:12px;
  margin-bottom:25px;
}

.card-title i{
  width:42px;
  height:42px;
  background:#dcfce7;
  color:var(--green);
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:18px;
}

.card-title h2{
  font-size:22px;
}

.option{
  display:flex;
  align-items:center;
  gap:12px;
  padding:16px;
  border:1px solid #eee;
  border-radius:14px;
  margin-bottom:15px;
  transition:.3s;
  cursor:pointer;
}

.option:hover{
  border-color:var(--green);
  background:#f9fffb;
}

.option input{
  accent-color:var(--green);
  transform:scale(1.2);
}

.option-content{
  display:flex;
  flex-direction:column;
}

.option-content span{
  font-weight:600;
}

.option-content small{
  color:#777;
}

.dynamic-input{
  width:100%;
  padding:14px;
  border:1px solid #ddd;
  border-radius:12px;
  outline:none;
  transition:.3s;
}

.dynamic-input:focus{
  border-color:#16a34a;
}

.summary-card{
  position:sticky;
  top:100px;
}

.summary-top{
  border-bottom:1px solid #eee;
  padding-bottom:20px;
  margin-bottom:20px;
}

.summary-row{
  display:flex;
  justify-content:space-between;
  margin-bottom:14px;
  color:#555;
}

.total{
  font-size:24px;
  font-weight:bold;
  color:var(--green);
}

.checkout-btn{
  width:100%;
  border:none;
  background:linear-gradient(
    135deg,
    var(--green),
    var(--light)
  );
  color:white;
  padding:15px;
  border-radius:14px;
  font-size:16px;
  font-weight:bold;
  cursor:pointer;
  transition:.3s;
  margin-top:20px;
}

.checkout-btn:hover{
  transform:translateY(-2px);
}

.secure{
  margin-top:18px;
  text-align:center;
  color:#777;
  font-size:14px;
}

.alert{
  margin:0 40px 20px;
  padding:14px;
  border-radius:12px;
  font-weight:600;
}

.alert-error{
  background:#fee2e2;
  color:#dc2626;
}

.alert-success{
  background:#dcfce7;
  color:#16a34a;
}

@media(max-width:900px){

  .checkout-wrapper{
    grid-template-columns:1fr;
  }

  .summary-card{
    position:static;
  }
}

</style>

</head>

<body>

<div id="navbar"></div>

<div class="page-header">

  <h1>
    Checkout
  </h1>

  <p>
    Complete your order securely
  </p>

</div>

<?php if($error): ?>

  <div class="alert alert-error">

    <?= htmlspecialchars($error) ?>

  </div>

<?php endif; ?>

<?php if($success): ?>

  <div class="alert alert-success">

    <?= htmlspecialchars($success) ?>

  </div>

<?php endif; ?>

<form action="../actions/checkout.php"
      method="POST"
      autocomplete="off">

<div class="checkout-wrapper">

  <div class="checkout-left">

<div class="card">

  <div class="card-title">

    <i class="fa-solid fa-truck"></i>

    <h2>
      Delivery Method
    </h2>

  </div>

  <label class="option">

    <input type="radio"
           name="delivery"
           value="pickup"
           onclick="toggleDelivery()"
           required>

    <div class="option-content">

      <span>
        Store Pickup
      </span>

      <small>
        Pick up from nearest branch
      </small>

    </div>

  </label>

<div id="pickupBox"
     style="display:none; margin-top:15px;">

  <input
  type="text"
  name="pickup_name"
  placeholder="Full Name"
  class="dynamic-input"
  pattern="[A-Za-z\s]+"
  title="Name must contain letters only"
  minlength="3">

  <input
  type="tel"
  name="pickup_phone"
  placeholder="Phone Number"
  class="dynamic-input"
  pattern="[0-9]{11}"
  maxlength="11"
  title="Phone number must contain 11 digits"
  style="margin-top:12px;">

  <input
  type="date"
  name="pickup_date"
  class="dynamic-input"
  style="margin-top:12px;">

</div>

<label class="option">

    <input type="radio"
           name="delivery"
           value="home"
           onclick="toggleDelivery()">

    <div class="option-content">

      <span>
        Home Delivery
      </span>

      <small>
        Delivered directly to your address
      </small>

    </div>

</label>

<div id="addressBox"
     style="display:none; margin-top:15px;">

  <input
  type="text"
  name="full_name"
  placeholder="Full Name"
  class="dynamic-input"
  pattern="[A-Za-z\s]+"
  title="Name must contain letters only"
  minlength="3">

  <input
  type="tel"
  name="phone"
  placeholder="Phone Number"
  class="dynamic-input"
  pattern="[0-9]{11}"
  maxlength="11"
  title="Phone number must contain 11 digits"
  style="margin-top:12px;">

  <input
  type="text"
  name="address"
  placeholder="Enter your address"
  class="dynamic-input"
  minlength="5"
  style="margin-top:12px;">

</div>

</div>

<div class="card">

<div class="card-title">

<i class="fa-solid fa-credit-card"></i>

<h2>
Payment Method
</h2>

</div>

<label class="option">

<input type="radio"
       name="payment"
       value="cash"
       onclick="togglePayment()"
       required>

<div class="option-content">

<span>
Cash on Delivery
</span>

<small>
Pay when order arrives
</small>

</div>

</label>

<label class="option">

<input type="radio"
       name="payment"
       value="visa"
       onclick="togglePayment()">

<div class="option-content">

<span>
Visa / MasterCard
</span>

<small>
Secure online payment
</small>

</div>

</label>

<div id="visaBox"
     style="display:none; margin-top:20px;">

  <input
  type="text"
  name="card_name"
  placeholder="Card Holder Name"
  class="dynamic-input"
  pattern="[A-Za-z\s]+"
  title="Name must contain letters only">

  <input
  type="text"
  id="cardNumber"
  name="card_number"
  placeholder="1234 5678 9012 3456"
  maxlength="19"
  pattern="[0-9\s]{19}"
  class="dynamic-input"
  style="margin-top:12px;">

  <div style="
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-top:12px;
  ">

    <input
    type="month"
    name="expiry_date"
    class="dynamic-input">

    <input
    type="text"
    id="cvv"
    name="cvv"
    placeholder="CVV"
    maxlength="3"
    pattern="[0-9]{3}"
    class="dynamic-input">

  </div>

</div>

</div>

</div>

<div class="card summary-card">

<div class="summary-top">

<h2>
Order Summary
</h2>

</div>

<div class="summary-row">

<span>
Subtotal
</span>

<span>

<?= number_format($total,2) ?>

EGP

</span>

</div>

<div class="summary-row">

<span>
Delivery
</span>

<span>
Free
</span>

</div>

<div class="summary-row total">

<span>
Total
</span>

<span>

<?= number_format($total,2) ?>

EGP

</span>

</div>

<button type="submit"
        class="checkout-btn">

Confirm Order

</button>

<div class="secure">

<i class="fa-solid fa-lock"></i>

Secure Checkout

</div>

</div>

</div>

</form>

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

function toggleDelivery(){

  let delivery =
    document.querySelector(
      'input[name="delivery"]:checked'
    )?.value;

  let addressBox =
    document.getElementById('addressBox');

  let pickupBox =
    document.getElementById('pickupBox');

  if(delivery === 'home'){

    addressBox.style.display = 'block';

    pickupBox.style.display = 'none';

  }

  else if(delivery === 'pickup'){

    pickupBox.style.display = 'block';

    addressBox.style.display = 'none';
  }
}



function togglePayment(){

  let payment =
    document.querySelector(
      'input[name="payment"]:checked'
    )?.value;

  let visaBox =
    document.getElementById('visaBox');

  if(payment === 'visa'){

    visaBox.style.display = 'block';

  }

  else{

    visaBox.style.display = 'none';
  }
}

</script>

<script>

document.querySelectorAll('input[type="tel"]')
.forEach(input => {

    input.addEventListener('input', function(){

        this.value =
            this.value.replace(/[^0-9]/g,'');

    });

});



document.querySelectorAll(
'input[name="full_name"], input[name="pickup_name"], input[name="card_name"]'
)
.forEach(input => {

    input.addEventListener('input', function(){

        this.value =
            this.value.replace(/[^A-Za-z\s]/g,'');

    });

});



const cardInput =
document.getElementById('cardNumber');

if(cardInput){

cardInput.addEventListener('input', function(e){

let value =
e.target.value
.replace(/\D/g,'');

value =
value.substring(0,16);

let parts =
value.match(/.{1,4}/g);

if(parts){

e.target.value =
parts.join(' ');

}

else{

e.target.value = '';
}

});

}



const cvvInput =
document.getElementById('cvv');

if(cvvInput){

cvvInput.addEventListener('input', function(e){

e.target.value =
e.target.value
.replace(/\D/g,'')
.substring(0,3);

});

}

</script>
<script>

document.querySelector('form')
.addEventListener('submit', function(e){

    let delivery =
        document.querySelector(
            'input[name="delivery"]:checked'
        )?.value;

    let payment =
        document.querySelector(
            'input[name="payment"]:checked'
        )?.value;



    if(delivery === 'home'){

        let fullName =
            document.querySelector(
                'input[name="full_name"]'
            ).value.trim();

        let phone =
            document.querySelector(
                'input[name="phone"]'
            ).value.trim();

        let address =
            document.querySelector(
                'input[name="address"]'
            ).value.trim();



        if(
            fullName === '' ||
            phone === '' ||
            address === ''
        ){

            alert(
                'Please complete home delivery information.'
            );

            e.preventDefault();

            return;
        }
    }



    if(delivery === 'pickup'){

        let pickupName =
            document.querySelector(
                'input[name="pickup_name"]'
            ).value.trim();

        let pickupPhone =
            document.querySelector(
                'input[name="pickup_phone"]'
            ).value.trim();

        let pickupDate =
            document.querySelector(
                'input[name="pickup_date"]'
            ).value.trim();



        if(
            pickupName === '' ||
            pickupPhone === '' ||
            pickupDate === ''
        ){

            alert(
                'Please complete pickup information.'
            );

            e.preventDefault();

            return;
        }
    }



    if(payment === 'visa'){

        let cardName =
            document.querySelector(
                'input[name="card_name"]'
            ).value.trim();

        let cardNumber =
            document.querySelector(
                'input[name="card_number"]'
            ).value.trim();

        let expiryDate =
            document.querySelector(
                'input[name="expiry_date"]'
            ).value.trim();

        let cvv =
            document.querySelector(
                'input[name="cvv"]'
            ).value.trim();



        if(
            cardName === '' ||
            cardNumber === '' ||
            expiryDate === '' ||
            cvv === ''
        ){

            alert(
                'Please complete visa payment information.'
            );

            e.preventDefault();

            return;
        }
    }

});

</script>
</body>

</html>