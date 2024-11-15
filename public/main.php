<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>FOX WEDDING</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
<header>
    <div class="logo"><a href="main.php"><img src="../assets/розовая%20лиса%201.png" alt=""></a></div>

    <div class="title">fox wedding</div>
    <div class="cartacc">

        <div class="account-icon" id="loginBtn" style="cursor:pointer;">
            <?php session_start(); // Убедитесь, что сессия запущена ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../includes/profile.php">
                    <img src="../assets/User%20Circle.png" alt="User Account">
                </a>
            <?php else: ?>
                <img src="../assets/User%20Circle.png" alt="Login" onclick="openLoginModal()">
            <?php endif; ?>
        </div>
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
</header>

<div class="nav_links">
    <div class="nav_links_wed"><a href="catalog/wedding.php">wedding dresses</a></div>
    <div class="nav_links_access"><a href="catalog/accessories.php">accessories</a></div>
    <div class="nav_links_eve"><a href="catalog/evening.php">evening dresses</a></div>
    <div class="nav_links_cont"><a href="contacts.php">contacts</a></div>
</div>

<main>
    <div class="block2">
        <div class="block2_shop-text">In the very heart of the city, hidden from the hustle and bustle of noisy streets, the wedding salon "Fox Wedding" is located - a real portal to the world of exquisite Victorian romance. Here, among the lace draperies and the shimmer of antique mirrors, dreams of a fairytale wedding come to life.<br>

            In our salon, every bride will find an outfit that will highlight her individuality and beauty. Experienced consultants, like good fairies, will help you choose the perfect style and complement the look with elegant accessories - vintage brooches, lace gloves or hats with a veil.<br>
            Here dreams of a wedding imbued with the spirit of the Victorian era - solemn, sophisticated, and unforgettable. </div>

        <div class="block2_buttons">
            <button class="button"><a href="#reviews">reviews</a></button>
            <button class="button"><a href="#history">history</a></button>
            <button class="button"><a href="#services">services</a></button>
        </div>
    </div>
    </div>

    <section class="block3">
        <h2>sales</h2>
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
    </section>

    <section class="block4" id="history">
        <h2>store history</h2>

        <section class="block4_text">
            <h3>In a small town where every crossroads held a love story, there was an online wedding dress store called Fox Wedding. Founded by married couple Anna and Mikhail, the store began its history as a small page on a social network where they shared photographs of handmade wedding accessories. <br>

                Over time, thanks to warm reviews and recommendations, “fox wedding” turned into a full-fledged online store. Anna, with a talent for design and a passion for beauty, created unique wedding dresses that reflected the dreams and desires of brides. Mikhail, with his IT and marketing skills, was involved in website development and online business promotion.<br>

                Each dress in "Fox Wedding" was more than just clothes - it was the embodiment of a love story, carefully sewn into every seam. Brides from all over the world found the dress of their dreams in White Veil, and each order was accompanied by a personal letter from Anna and Mikhail, filled with warm wishes and advice.<br>

                Over the years, Fox Wedding has expanded its range, offering not only dresses, but also accessories, shoes and wedding decor. They also began organizing webinars and master classes for brides, helping them prepare for the most important day of their lives.

                The story of The Fox Wedding is a story of how passion and dedication can turn a small idea into a thriving business that brings joy and inspiration to many people.</h3>
        </section>
    </section>

    <section class="block5" id="reviews">
        <h2>reviews</h2>
    </section>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="image"></div>
            <div class="modal-header"><img src="../assets/розовая%20лиса%206.png"></div>
            <div class="modal-body">
                <form action="../includes/login.php" method="post">
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
            <div class="modal-header"><img src="../assets/розовая%20лиса%206.png"></div>
            <div class="modal-body">
                <form action="../includes/register.php" method="post">
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
            <a href=""><img src="../assets/image 122.png"></a>
            <a href=""><img src="../assets/image 123.png"></a>
            <a href=""><img src="../assets/image 124.png"></a>
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
</body>
</html>
