<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die("ID товара не задан.");
}

$dress_id = $_GET['id'];
$query = $conn->prepare("SELECT * FROM dresses WHERE dresses_id = ?");
$query->bind_param("i", $dress_id);
$query->execute();
$dress_data = $query->get_result()->fetch_assoc();

if (!$dress_data) {
    die("Платье не найдено.");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dress_data['name']); ?></title>
    <link rel="stylesheet" href="../../assets/dressinfost.css">
</head>
<body>

<header>
    <div class="header_top">
        <div class="logo"><a href="../main.php" id=""><img src="../../assets/розовая лиса 1.png" alt=""></a></div>

        <div class="title">fox wedding</div>
        <div class="cartacc">

            <div class="account-icon" id="loginBtn" style="cursor:pointer;">
                <?php session_start(); // Убедитесь, что сессия запущена ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../../includes/profile.php">
                        <img src="../../assets/User%20Circle.png" alt="User Account">
                    </a>
                <?php else: ?>
                    <img src="../../assets/User%20Circle.png" alt="Login" onclick="openLoginModal()">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="nav_links">
    <div class="nav_links_cont"><a href="../main.php">HOME</a></div>
        <div class="nav_links_access"><a href="../catalog/accessories.php">ACCESSORIES</a></div>
        <div class="nav_links_eve"><a href="../catalog/wedding.php">WEDDING DRESSES</a></div>
        <div class="nav_links_eve"><a href="../catalog/evening.php">EVENING DRESSES</a></div>
        <div class="nav_links_cont"><a href="../contacts.php">CONTACTS</a></div>
    </div>
</header>

<main>
    <div class="product">
        <div class="left-place">
            <img src="<?php echo htmlspecialchars($dress_data['image']); ?>" alt="<?php echo htmlspecialchars($dress_data['name']); ?>" class="main-image" >
        </div>
        <div class="right-place">
            <h2><?php echo htmlspecialchars($dress_data['name']); ?></h2>
            <p>Style: <?php echo htmlspecialchars($dress_data['style']); ?></p>
            <p>Price: <span id="price"><?php echo htmlspecialchars($dress_data['price']); ?></span> RUB</p>
            <label>
                <input type="checkbox" id="fittingCheckbox" onclick="updateTotal()"> Custom fit (+10%)
            </label>
            <p>Quantity:</p>
            <button class="opacity-button" onclick="changeQuantity(-1)">-</button>
            <input type="number" id="quantity" value="1" min="1" onchange="updateTotal()">
            <button class="opacity-button" onclick="changeQuantity(1)">+</button>
            <p>Final cost: <span id="totalPrice"><?php echo htmlspecialchars($dress_data['price']); ?></span> RUB</p>
            <button class="cart-button" id="addToCart" onclick="addToCart(<?php echo $dress_id; ?>)">Add to cart</button>

            <div class="info">
    <div class="collapsible"><img src="../assets/dressimg/image 116.png"> payment </div>
    <div class="content"><h3>Payment after a telephone conversation with our manager. You can discuss all the details of the order.</h3></div>

    <div class="collapsible"><img src="../dressimg/image 117.png"> delivery </div>
    <div class="content"><h3>Delivery is coordinated with our manager. You can choose delivery to your door, pickup, or try on and purchase in our offline salon</h3></div>
  
    <div class="collapsible"><img src="../dressimg/image 118.png"> size info </div>
    <div class="content"><img src="https://cdn.shopify.com/s/files/1/0257/5279/7256/files/custom_resized_ffba0485-0458-46f0-b68d-183f1890d526.jpg?v=1686285447"style="width: 700px;"></div>
  </div>
        </div>
    </div>
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="image"></div>
            <div class="modal-header"><img src="../../assets/розовая%20лиса%206.png"></div>
            <div class="modal-body">
                <form action="../../includes/login.php" method="post">
                    <div class="account-question" id="registerQuestion">Don't have an account yet?</div>
                    <input type="email" name="email" class="input-field" placeholder="email" required>
                    <input type="password" name="password" class="input-field" placeholder="password" required>
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
            <div class="modal-header"><img src="../../assets/розовая%20лиса%206.png"></div>
            <div class="modal-body">
                <form action="../../includes/register.php" method="post">
                    <input type="email" name="email" class="input-field" placeholder="email" required>
                    <input type="password" name="password" class="input-field" placeholder="password" required>
                    <input type="password" name="repeat_password" class="input-field" placeholder="repeate password" required>
                    <button class="submit-btn" type="submit" name="register">sign up</button>
                    
                </form>
            </div>
        </div>
    </div>

</main>
<footer>
    <div class="footer-container" id="contacts">

        <div class="footer-socials">
            <a href=""><img src="../../assets/image 122.png"></a>
            <a href=""><img src="../../assets/image 123.png"></a>
            <a href=""><img src="../../assets/image 124.png"></a>
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
</script>
<script>
  var sizeButtons = document.querySelectorAll('.product .button:not(:first-child)');
  sizeButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      sizeButtons.forEach(function(b) { b.classList.remove('selected'); });
      btn.classList.add('selected');
    });
  });
</script>
<script>
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
<script>
    let price = <?php echo htmlspecialchars($dress_data['price']); ?>;
    let quantityInput = document.getElementById('quantity');
    let totalPrice = document.getElementById('totalPrice');
    let fittingCheckbox = document.getElementById('fittingCheckbox');

    function changeQuantity(amount) {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity + amount >= 1) {
            quantityInput.value = currentQuantity + amount;
            updateTotal();
        }
    }

    function updateTotal() {
        let baseTotal = price * quantityInput.value;
        if (fittingCheckbox.checked) {
            baseTotal *= 1.10;
        }
        totalPrice.innerText = baseTotal.toFixed(2);
    }

    function addToCart(dressId) {
        let quantity = quantityInput.value;
        let totalCost = parseFloat(totalPrice.innerText);
        let fittingRequired = fittingCheckbox.checked ? 1 : 0;

        let deliveryDate = new Date();
        deliveryDate.setDate(deliveryDate.getDate() + (fittingRequired ? 3 : 0));
        let formattedDeliveryDate = deliveryDate.toISOString().split('T')[0];

        let currentDate = new Date();
        let formattedOrderDate = currentDate.toISOString().split('T')[0];

        fetch('../../includes/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                dressId: dressId,
                quantity: quantity,
                totalCost: totalCost,
                fittingRequired: fittingRequired,
                orderDate: formattedOrderDate,
                estimatedDeliveryDate: formattedDeliveryDate
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Товар добавлен в корзину!');
                } else {
                    alert('Произошла ошибка: ' + data.message);
                }
            })
            .catch(error => console.error('Ошибка при отправке данных:', error));
    }
</script>

<script src="../../scripts/log.js"></script>
</body>
</html>
