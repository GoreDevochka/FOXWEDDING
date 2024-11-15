<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>accessories</title>
<link rel="stylesheet" href="../assets/contacts.css">
</head>

<body>
<!--шапка-->
<header>
  <div class="header_top">
  <div class="logo"><a href="main.php"><img src="../assets/розовая%20лиса%201.png" alt=""></a></div>

  <div class="title">fox wedding</div>
<div class="cartacc">
  <div class="account-icon" id="loginBtn" style="cursor:pointer;"><img src="../assets/User%20Circle.png" alt=""></div>
</div>
<!-- The cart modal container -->
<div id="cartModal" class="cart-modal">
  <span class="close-button">&times;</span>
  <!-- Tab icons container -->
  <div class="tab-icons">
    <div class="tab-icon" onclick="openTab('cart')"><img src="../assets/image%2081.png" alt=""></div>
    <div class="tab-icon" onclick="openTab('orders')"><img src="../assets/image%20126.png" alt=""></div>
    <div class="tab-icon" onclick="openTab('favorites')"><img src="../assets/image%2080.png" alt=""></div>
  </div>

  <!-- Tab assets containers -->
  <div id="cart">
    <!-- Cart tab assets -->
  </div>
  <div id="orders">
    <!-- Orders tab assets -->
  </div>
  <div id="favorites">
    <!-- Favorites tab assets -->
  </div>
</div>

</div>
</div>
<div class="nav_links">
  
<div class="nav_links_cont"><a href="../main.php">HOME</a></div>
        <div class="nav_links_access"><a href="../catalog/accessories.php">ACCESSORIES</a></div>
        <div class="nav_links_eve"><a href="../wedding.php">WEDDING DRESSES</a></div>
        <div class="nav_links_eve"><a href="../evening.php">EVENING DRESSES</a></div>
        <div class="nav_links_cont"><a href="../contacts.php">CONTACTS</a></div>
    </div>
  <div class="lace"><img src="../assets/lace.png"></div>
</header> 
  <main>
<!--платья-->

<div class="about">
  <h1>contacts</h1>
  
  <div class="text">
    <h2>Our website provides services for storage, sewing, cutting and delivery of dresses. <br> All this is available for ordering by phone, recording via Telegram or in person in the showroom. <h2>
      <h1> +7 (333) 333-33-33</h1>
    </div>
  </div>


 <!--авторег-->
 <div id="loginModal" class="modal">
  <div class="modal-content">
      <span class="close">&times;</span>
      <div class="image">
      </div>
      <div class="modal-header"><img src="../assets/розовая%20лиса%206.png"></div>
      <div class="modal-body">
          <form action="login.php" method="post">
              <div class="account-question" id="registerQuestion">Don't have an account yet?</div>
              <input type="email" name="email" class="input-field" placeholder="email">
              <input type="password" name="password" class="input-field" placeholder="password">
              <div class="password-question" id="passwordQuestion">Forgot your password?</div>
              <button class="submit-btn" type="submit" name="login">sign in</button>
              
          </form>
      </div>
  </div>
</div>


<div id="registerModal" class="modal">
  <div class="modal-content">
      <span class="close">&times;</span>
      <div class="image"></div>
      <div class="modal-header"><img src="../assets/розовая%20лиса%206.png"></div>
      <div class="modal-body">
          <form action="register.php" method="post">
              <input type="email" name="email" class="input-field" placeholder="email">
              <input type="password" name="password" class="input-field" placeholder="password">
              <input type="password" name="repeat_password" class="input-field" placeholder="repeate password">
              <button class="submit-btn" type="submit" name="register">sign up</button>
              
          </form>
      </div>
  </div>
</div>
</main>
<!--футер-->
<footer>
    <div class="footer-container"> 
        
        <div class="footer-socials">
        <a href=""><img src="../assets/image%20122.png"></a>
        <a href=""><img src="../assets/image%20123.png"></a>
        <a href=""><img src="../assets/image%20124.png"></a>
      </div>
      <div class="links">
            <a href="">delivery</a> <br>
            <a href="">services</a> <br>
            <a href="">privacy policy</a> <br>
            <a href="">new collections</a> <br>
        </div>
      <div class="maps">
        <div class="maps_map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.294478658571!2d-74.01494532353642!3d40.71153343764359!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a1967eef443%3A0xb54945159b88779b!2zMTgwIEdyZWVud2ljaCBTdCwgTmV3IFlvcmssIE5ZIDEwMDA2LCDQodCo0JA!5e0!3m2!1sru!2sru!4v1718031054237!5m2!1sru!2sru" width="400" height="150" style="border-radius: 5px;;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="footer-directions">
          <button class="button"><a href=""> how to get where?</a></button>
      </div>
    </div>
        
     
      
    </div>
  </footer>
  <script>
    function onSubmit(token) {
      document.getElementById("demo-form").submit();
    }
  </script>
<script>
              var slideIndex = 0;
              showSlides(slideIndex);
              
              function changeSlide(n) {
                showSlides(slideIndex += n);
              }
              
              function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("slide");
                if (n > slides.length - 1) slideIndex = 0;
                if (n < 0) slideIndex = slides.length - 1;
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slides[slideIndex].style.display = "block";
              }
              </script>
      <script src="../scripts/log.js"></script>
      <script src="cart.js"></script>
</body>
</html>
