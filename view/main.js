window.addEventListener('DOMContentLoaded', () => {

  let fa = document.createElement('link');

  fa.rel = 'stylesheet';

  fa.href =
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css';

  document.head.appendChild(fa);

});

const navbarHTML = `
<div class="navbar">

  <div class="logo" onclick="goHome()" style="cursor:pointer;">
    <div class="logo-box">S</div>
    <span>Seoudi</span>
  </div>

  <div class="nav-links">
    <a onclick="goHome()">Home</a>
    <a onclick="goCategories()">Categories</a>
    <a onclick="goContact()">Contact</a>
  </div>

  <div class="nav-actions">

    <span class="icon" onclick="goSearch()">🔍</span>

    <div class="cart" onclick="goCart()">
      🛒
    </div>

    <div id="authArea"></div>

  </div>

</div>

<!-- LOGOUT POPUP -->

<div id="logoutPopup" class="popup">

  <div class="popup-box">

    <h3>Are you sure?</h3>

    <div class="popup-actions">

      <button class="cancel-btn"
              onclick="closePopup()">

        Cancel

      </button>

      <button class="confirm-btn"
              onclick="confirmLogout()">

        Yes

      </button>

    </div>

  </div>

</div>

<!-- SEARCH POPUP -->

<div id="searchPopup" class="popup">

  <div class="popup-box">

    <h3>Search Product</h3>

    <input type="text"
           id="searchInput"
           placeholder="Search here..."
           class="search-input">

    <div class="popup-actions">

      <button class="cancel-btn"
              onclick="closeSearch()">

        Close

      </button>

      <button class="confirm-btn"
              onclick="searchProduct()">

        Search

      </button>

    </div>

  </div>

</div>

<style>

.navbar {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 40px;
  background:white;
  border-bottom:1px solid #eee;
  position:sticky;
  top:0;
  z-index:1000;
}

.logo {
  display:flex;
  align-items:center;
  gap:10px;
  color:#16a34a;
  font-weight:bold;
  font-size:18px;
}

.logo-box {
  background:#16a34a;
  color:white;
  width:35px;
  height:35px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:8px;
}

.nav-links {
  display:flex;
  gap:30px;
}

.nav-links a {
  text-decoration:none;
  color:#333;
  cursor:pointer;
  position:relative;
}

.nav-links a::after {
  content:'';
  position:absolute;
  width:0;
  height:2px;
  background:#16a34a;
  left:0;
  bottom:-5px;
  transition:0.3s;
}

.nav-links a:hover::after {
  width:100%;
}

.nav-actions {
  display:flex;
  gap:20px;
  align-items:center;
}

.icon {
  cursor:pointer;
}

.cart {
  position:relative;
  cursor:pointer;
}

.login-btn {
  background:#16a34a;
  color:white;
  border:none;
  padding:8px 16px;
  border-radius:8px;
  cursor:pointer;
}

.user-icon {
  width:40px;
  height:40px;
  background:#16a34a;
  color:white;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:bold;
  cursor:pointer;
}

.user-menu {
  position:relative;
}

.dropdown {
  position:absolute;
  top:50px;
  right:0;
  background:white;
  border-radius:10px;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
  width:180px;
  display:none;
  overflow:hidden;
}

.dropdown div {
  padding:12px;
  cursor:pointer;
  transition:.3s;
}

.dropdown div:hover {
  background:#f5f5f5;
}

.popup {
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.4);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:2000;
}

.popup-box {
  background:white;
  padding:25px;
  border-radius:12px;
  text-align:center;
  width:300px;
}

.popup-actions {
  margin-top:20px;
  display:flex;
  justify-content:space-between;
  gap:10px;
}

.cancel-btn {
  background:#ddd;
  border:none;
  padding:8px 15px;
  border-radius:6px;
  cursor:pointer;
  flex:1;
}

.confirm-btn {
  background:#16a34a;
  color:white;
  border:none;
  padding:8px 15px;
  border-radius:6px;
  cursor:pointer;
  flex:1;
}

.search-input{
  width:100%;
  padding:10px;
  border:1px solid #ddd;
  border-radius:8px;
  margin-top:15px;
}

</style>
`;



const footerHTML = `
<div class="footer">

  <div class="footer-container">

    <div>

      <h3>Seoudi Market</h3>

      <p>
        Fresh groceries everyday at your doorstep.
      </p>

    </div>

    <div>

      <h3>Quick Links</h3>

      <a onclick="goHome()">
        Home
      </a>

      <a onclick="goCategories()">
        Categories
      </a>

      <a onclick="goContact()">
        Contact
      </a>

    </div>

    <div>

      <h3>Contact</h3>

      <p>Email: info@seoudi.com</p>

      <p>Phone: 16176</p>

      <!-- SOCIAL ICONS -->

      <div class="social-icons">

        <a href="https://www.facebook.com/share/18jee3uVkj/?mibextid=wwXIfr"
           target="_blank">

          <i class="fa-brands fa-facebook-f"></i>

        </a>

        <a href="https://www.instagram.com/seoudisupermarket?igsh=NWZxbjZoOHdqYTF1"
           target="_blank">

          <i class="fa-brands fa-instagram"></i>

        </a>

      </div>

      <!-- STORE BUTTONS -->

      <div class="store-buttons">

        <a href="https://play.google.com/store/apps/details?id=com.seoudi.app&pcampaignid=web_share"
           target="_blank"
           class="store-btn">

          <i class="fa-brands fa-google-play"></i>

          Google Play

        </a>

        <a href="https://apps.apple.com/eg/app/seoudi-supermarket/id1615641660"
           target="_blank"
           class="store-btn">

          <i class="fa-brands fa-apple"></i>

          App Store

        </a>

      </div>

    </div>

  </div>

  <div class="footer-bottom">

    ©️ 2026 Seoudi Market - All Rights Reserved

  </div>

</div>

<style>

.footer {
  background:#111;
  color:#ccc;
  padding:40px;
  margin-top:60px;
}

.footer-container {
  display:grid;
  grid-template-columns:
    repeat(auto-fit,minmax(200px,1fr));
  gap:30px;
}

.footer h3 {
  color:white;
  margin-bottom:10px;
}

.footer a {
  display:block;
  color:#ccc;
  text-decoration:none;
  margin:6px 0;
  cursor:pointer;
  transition:0.3s;
}

.footer a:hover {
  color:#16a34a;
  padding-left:5px;
}

.footer-bottom {
  text-align:center;
  margin-top:25px;
  font-size:13px;
  border-top:1px solid #333;
  padding-top:15px;
}

/* SOCIAL ICONS */

.social-icons{
  display:flex;
  gap:12px;
  margin-top:15px;
}

.social-icons a{
  width:40px;
  height:40px;
  border-radius:50%;
  background:#222;
  display:flex;
  align-items:center;
  justify-content:center;
  color:white;
  font-size:16px;
  transition:.3s;
}

.social-icons a:hover{
  background:#16a34a;
  transform:translateY(-3px);
  padding-left:0 !important;
}

/* STORE BUTTONS */

.store-buttons{
  display:flex;
  flex-direction:column;
  gap:10px;
  margin-top:18px;
}

.store-btn{
  background:#222;
  color:white !important;
  padding:10px 14px;
  border-radius:10px;
  text-decoration:none;
  display:flex !important;
  align-items:center;
  gap:10px;
  width:fit-content;
  transition:.3s;
}

.store-btn:hover{
  background:#16a34a;
  padding-left:14px !important;
}

</style>
`;



document.getElementById("navbar").innerHTML =
  navbarHTML;

document.getElementById("footer").innerHTML =
  footerHTML;



function renderAuth(){

  let user =
    window.loggedUser || null;

  let authArea =
    document.getElementById("authArea");

  if(!authArea) return;

  if(user){

    let firstLetter =
      user.name.charAt(0).toUpperCase();

    authArea.innerHTML = `

      <div class="user-menu">

        <div class="user-icon"
             onclick="toggleMenu()">

          ${firstLetter}

        </div>

        <div class="dropdown"
             id="dropdownMenu">

          <div onclick="goFavorites()">
            💚 Favorites
          </div>

          <div onclick="goOrders()">
            📦 Orders
          </div>

          ${
            user.role === 'admin'

            ?

            `
              <div onclick="goDashboard()">
                📊 Dashboard
              </div>
            `

            :

            ''
          }

          <div onclick="openPopup()">
            🚪 Logout
          </div>

        </div>

      </div>

    `;

  } else {

    authArea.innerHTML = `

      <button class="login-btn"
              onclick="goLogin()">

        Login

      </button>

    `;
  }
}



function toggleMenu(){

  let menu =
    document.getElementById("dropdownMenu");

  if(menu){

    menu.style.display =

      menu.style.display === "block"

      ?

      "none"

      :

      "block";
  }
}



document.addEventListener("click", function(e){

  let menu =
    document.getElementById("dropdownMenu");

  let icon =
    document.querySelector(".user-icon");

  if(
      menu
      &&
      icon
      &&
      !icon.contains(e.target)
    ){

      menu.style.display = "none";
  }
});



function openPopup(){

  document.getElementById("logoutPopup")
          .style.display = "flex";
}



function closePopup(){

  document.getElementById("logoutPopup")
          .style.display = "none";
}



function confirmLogout(){

  window.location.href =
    "../actions/logout.php";
}



function goSearch(){

  document.getElementById("searchPopup")
          .style.display = "flex";
}



function closeSearch(){

  document.getElementById("searchPopup")
          .style.display = "none";
}



function searchProduct(){

  let value =

    document.getElementById("searchInput")
            .value
            .trim();

  if(value !== ""){

    window.location.href =

      "products.php?search=" +

      encodeURIComponent(value);
  }
}



renderAuth();



function goHome(){

  window.location.href =
    "index.php";
}

function goCategories(){

  window.location.href =
    "categories.php";
}

function goCart(){

  window.location.href =
    "cart.php";
}

function goLogin(){

  window.location.href =
    "login.php";
}

function goContact(){

  window.location.href =
    "contact.php";
}

function goFavorites(){

  window.location.href =
    "favorites.php";
}

function goOrders(){

  window.location.href =
    "orders.php";
}

function goDashboard(){

  window.location.href =
    "dashboard.php";
}