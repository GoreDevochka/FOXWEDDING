<?php
session_start();
require_once '../config/config.php';

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Получение информации о пользователе
$query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
if ($query === false) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

$query->bind_param("i", $user_id);
if (!$query->execute()) {
    die("Ошибка выполнения запроса: " . $query->error);
}

$user_data = $query->get_result()->fetch_assoc();

if (!$user_data) {
    die("Пользователь не найден.");
}

// Получение заказов пользователя с новыми полями
$order_query = $conn->prepare("SELECT id, dress_id, quantity, total_cost, order_date, estimated_delivery_date, order_status, fitting_required FROM orders WHERE user_id = ?");
if ($order_query === false) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

$order_query->bind_param("i", $user_id);
if (!$order_query->execute()) {
    die("Ошибка выполнения запроса: " . $order_query->error);
}

$orders_result = $order_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../assets/profile.css">
</head>
<body>
<header>
<div class="header_top">
<button class="button"><a href="../public/main.php">Return to Main Page</a></button>
        <div class="title">fox wedding</div>
        <div class="logout"><a href="logout.php"><img src="../assets/Upload.svg"></a></div>
</header>

<main>
    <h2>Welcome, <?php echo htmlspecialchars($user_data['email']); ?></h2>

    <?php if ($role === 'admin') : ?>
        <h2>Admin Panel</h2>
        <button class="button"><a href="admin/admin.php">Go to Admin Panel</a></button>
    <?php else : ?>
      
    <?php endif; ?>

    <!-- Отображение заказов пользователя -->
    <h2>Your Orders</h2>
    <?php if ($orders_result->num_rows > 0): ?>
        <table>
            <thead>
            <tr>
                <th>Order</th>
                <th>Dress ID</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Order Date</th>
                <th>Estimated Delivery Date</th>
                <th>Status</th>
                <th>Fitting Required</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['dress_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_cost']); ?> ₽</td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td><?php echo htmlspecialchars($order['estimated_delivery_date']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                    <td><?php echo $order['fitting_required'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>
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
</body>
</html>
