<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Страница товара</title>
<link rel="stylesheet" href="../assets/dressinfost.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

</head>
<body>
    <!--шапка-->
    <header>
        <div class="header_top">
            <div class="logo"><a href="main.php"><img src="../assets/розовая%20лиса%201.png" alt=""></a></div>

            <div class="title">fox wedding</div>
            <div class="cartacc">
                <!--КОРЗИНА-->
                <div class="cart-icon" id="cartIcon" style="cursor:pointer;"><img src="../assets/Bag.png" alt=""></div>

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
      <div class="nav_links_cont"><a href="main.php">home</a></div>
          <div class="nav_links_access"><a href="catalog/accessories.php">accessories</a></div>
      
          <div class="nav_links_eve"><a href="catalog/wedding.php">wedding dresses</a></div>
    
          <div class="nav_links_eve"><a href="catalog/evening.php">evening dresses</a></div>
                          
          <div class="nav_links_cont"><a href="contacts.php">contacts</a></div>
      </div>
    </header> 
<main>        
<div class="product">
  <div class="left-place">
    <form action="add_to_favorites.php" method="post">
        <input type="hidden" name="product_id" value="1">
        <button type="submit" name="add_to_favorites" class="like-btn" data-product-id="1">
            <i class="fas fa-heart"></i>
        </button>
    </form>

    <div id="notification-container"></div>

    <img src="../assets/dressss.png" alt="Основное изображение товара" class="main-image">
    <div class="thumbnails">
        <!-- Слайдер с дополнительными фото товара -->
        <img src="../assets/dressss.png" alt="Дополнительное фото 1" class="thumbnail">
        <img src="../assets/dressss.png" alt="Дополнительное фото 1" class="thumbnail">
        <img src="../assets/dressss.png" alt="Дополнительное фото 2" class="thumbnail">
        <img src="../assets/dressss.png" alt="Дополнительное фото 3" class="thumbnail">
    </div>
</div>

<div class="right-place">
  
 <script>
  const likeBtns = document.querySelectorAll('.like-btn');
const notificationContainer = document.getElementById('notification-container');

likeBtns.forEach((btn) => {
  btn.addEventListener('click', (e) => {
    const productId = btn.dataset.productId;
    const isLiked = btn.classList.contains('liked');

    if (isLiked) {
      // Удалить товар из избранного
      btn.classList.remove('liked');
      btn.style.color = '';
      console.log(`Product ${productId} removed from favorites`);
    } else {
      // Добавить товар в избранное
      btn.classList.add('liked');
      btn.style.color = 'red';
      console.log(`Product ${productId} added to favorites`);

      // Add notification message
      const notification = document.createElement('div');
      notification.textContent = 'Added to favorites';
      notification.className = 'notification';
      notificationContainer.appendChild(notification);

      // Remove notification after 2 seconds
      setTimeout(() => {
        notificationContainer.removeChild(notification);
      }, 2000);
    }
  });
});
 </script>
<div class="productId" id="ProductId"><h2>Chic Doll Gothic Dress</h2></div>
<div class="productId" id="ProductPrice"><h2>100500 $</h2></div>
<div class="buttons">
    <div class="opacity">
        <h3>opacity</h3>
        <button class="opacity-button">-</button>
        <span>1</span>
        <button class="opacity-button">+</button>
    </div>
    <div class="size">  
        <h3>size</h3>
        <button class="size-button" id="btn">S</button>
        <button class="size-button" id="btn">M</button>
        <button class="size-button" id="btn">L</button>
    </div>

    <form action="add_to_cart.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <button type="submit" name="add_to_cart" class="cart-button"><h4>add to cart</h4></button>
    </form>
</div>


  <div class="info">
    <div class="collapsible"><img src=".."> payment <img src="../assets/dressimg/image 119.png"></div>
    <div class="content">dd</div>
  
    <div class="collapsible"><img src="../assets/dressimg/image 117.png"> delivery <img src="../assets/dressimg/image 119.png"></div>
    <div class="content">ddd</div>
  
    <div class="collapsible"><img src="../assets/dressimg/image 118.png"> size info <img src="../assets/dressimg/image 119.png"></div>
    <div class="content">ddd</div>
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
<div class="slider">
            <div class="slide active">
                <img src="../assets/54fafeea-d6a3-452a-9724-5b385b3f104b%201.png" alt="Image 1" style="width:100%">
            </div>
            <div class="slide">
                <img src="../assets/auto_background_remover_bot%201.png" alt="Image 2" style="width:100%">
            </div>
            <div class="slide">
                <img src="../assets/ce596d1b-9e15-4674-92d2-2ca485ff0419%201.png" alt="Image 3" style="width:100%">
            </div>
            <button class="arrow prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="arrow next" onclick="changeSlide(1)">&#10095;</button>
        </div>
</main>

<footer>
  <div class="footer-container" id="contacts"> 
      
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
// JavaScript скрипт
document.addEventListener('DOMContentLoaded', function() {
  // Функционал слайдера
  var thumbnails = document.querySelectorAll('.thumbnail');
  var mainImage = document.querySelector('.main-image');
  thumbnails.forEach(function(thumbnail) {
    thumbnail.addEventListener('click', function() {
      mainImage.src = this.src;
    });
  });

  // Функционал кнопок увеличения и уменьшения количества товара
  var quantity = 1;
  var quantityDisplay = document.querySelector('.product span');
  document.querySelector('.product').addEventListener('click', function(e) {
    if (e.target.textContent === '+') {
      quantity++;
    } else if (e.target.textContent === '-' && quantity > 1) {
      quantity--;
    }
    quantityDisplay.textContent = quantity;
  });

  // Функционал выбора размера товара
  var sizeButtons = document.querySelectorAll('.product .button:not(:first-child)');
  sizeButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      sizeButtons.forEach(function(b) { b.classList.remove('selected'); });
      btn.classList.add('selected');
    });
  });
});

// Добавляем JavaScript для функционала раскрытия информации
document.addEventListener('DOMContentLoaded', function() {
  var coll = document.getElementsByClassName("collapsible");
  for (var i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var content = this.nextElementSibling;
      if (content.style.display === "block") {
        content.style.display = "none";
      } else {
        content.style.display = "block";
      }
    });
  }
});

</script>
<script src="cart.js"></script>
<script src="../scripts/log.js"></script>
</body>
</html>
