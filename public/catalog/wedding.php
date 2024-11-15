<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evening Dresses</title>
    <link rel="stylesheet" href="../../assets/eveningst.css">
</head>

<body>
<!--шапка-->
<header>
    <div class="header_top">
        <div class="logo"><a href="../main.php"><img src="../../assets/розовая%20лиса%201.png" alt="Logo"></a></div>
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
        <div class="nav_links_access"><a href="accessories.php">ACCESSORIES</a></div>
        <div class="nav_links_eve"><a href="wedding.php">WEDDING DRESSES</a></div>
        <div class="nav_links_eve"><a href="evening.php">EVENING DRESSES</a></div>
        <div class="nav_links_cont"><a href="../contacts.php">CONTACTS</a></div>
    </div>
</header>

<main>
    <!-- Каталог аксессуаров -->
    <?php
    session_start();
    require_once '../../config/config.php'; // Подключение к базе данных

    // Включаем отображение ошибок для отладки
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Запрос на получение данных о платьях типа "свадебное" из базы данных
    $query = $conn->query("SELECT dresses_id, name, style, price, image FROM dresses WHERE dress_type_id = 1");

    if (!$query) {
        die("Ошибка выполнения запроса: " . $conn->error);
    }

    $dresses = $query->fetch_all(MYSQLI_ASSOC);
    ?>

    <!-- Вывод каталога -->
    <div class="catalog">
        <h1>Wedding</h1>
        <?php foreach ($dresses as $dress): ?>
            <div class="catalog_dress">
                <a href="../product/product.php?id=<?= $dress['dresses_id'] ?>"> <!-- Ссылка на страницу товара -->
                    <img src="<?= $dress['image'] ?>" alt="Платье <?= htmlspecialchars($dress['name']) ?>">
                </a>
                <div class="description">
                    <h2><?= htmlspecialchars($dress['name']) ?></h2>
                    <p>Style: <?= htmlspecialchars($dress['style']) ?></p>
                    <p>Price: <?= htmlspecialchars($dress['price']) ?> RUB</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Модальное окно авторизации -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="image"></div>
        <div class="modal-header">
            <img src="../../assets/розовая%20лиса%206.png" alt="Логотип">
        </div>
        <div class="modal-body">
            <form action="../../includes/login.php" method="post">
                <div class="account-question" id="registerQuestion">Don't have an account yet?</div>
                <input type="email" name="email" class="input-field" placeholder="Email">
                <input type="password" name="password" class="input-field" placeholder="Password">
                <div class="password-question" id="passwordQuestion">Forgot your password?</div>
                <button class="submit-btn" type="submit" name="login">Sign in</button>
                
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно регистрации -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="image"></div>
        <div class="modal-header">
            <img src="../../assets/розовая%20лиса%206.png" alt="Логотип">
        </div>
        <div class="modal-body">
            <form action="../../includes/register.php" method="post">
                <input type="email" name="email" class="input-field" placeholder="Email">
                <input type="password" name="password" class="input-field" placeholder="Password">
                <input type="password" name="repeat_password" class="input-field" placeholder="Repeat Password">
                <button class="submit-btn" type="submit" name="register">Sign up</button>
                
            </form>
        </div>
    </div>
</div>
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
<script src="../../scripts/log.js"></script>
</body>
</html>
