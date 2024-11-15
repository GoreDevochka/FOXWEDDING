<?php
session_start();
require_once '../../config/config.php';

// Проверка роли пользователя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Инициализация переменных
$message = '';

// Получение списка всех свадебных платьев в наличии
$dresses = [];
$query = $conn->query("SELECT * FROM dresses");
if ($query) {
    $dresses = $query->fetch_all(MYSQLI_ASSOC);
}


// Получение списка клиентов, ожидающих примерку свадебного платья
$waiting_clients = [];
$query = $conn->query("SELECT DISTINCT u.user_id, u.email FROM users u JOIN orders o ON u.user_id = o.user_id WHERE o.fitting_required = 1");
if ($query) {
    $waiting_clients = $query->fetch_all(MYSQLI_ASSOC);
}


// Получение списка тех, кто сделал заказ на свадебное платье
$ordered_clients = [];
$query = $conn->query("SELECT DISTINCT u.user_id, u.email FROM orders o JOIN users u ON o.user_id = u.user_id WHERE o.dress_id IS NOT NULL");
if ($query) {
    $ordered_clients = $query->fetch_all(MYSQLI_ASSOC);
}

// Получение статуса примерки для определенного клиента
$fitting_status = [];
if (isset($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);
    $query = $conn->prepare("SELECT o.fitting_required, o.order_status FROM orders o WHERE o.user_id = ?");
    $query->bind_param("i", $client_id);
    $query->execute();
    $result = $query->get_result();
    $fitting_status = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Orders and Dresses Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
<div class="flex flex-no-wrap w-full">
    <!-- Боковая панель -->
    <div class="bg-white w-64 h-screen shadow-md">
        <div class="p-4">
            <h1 class="text-xl font-bold">Админка</h1>
        </div>
        <ul class="mt-5">
            <li><a href="../../public/main.php" class="block p-4 text-red-700 hover:bg-gray-200">На главную</a></li>
            <li><a href="admin.php" class="block p-4 text-gray-700 hover:bg-gray-200">Платья</a></li>
            <li><a href="dress_types.php" class="block p-4 text-gray-700 hover:bg-gray-200">Типы платьев</a></li>
            <li><a href="users.php" class="block p-4 text-gray-700 hover:bg-gray-200">Пользователи</a></li>
            <li><a href="orders.php" class="block p-4 text-gray-700 hover:bg-gray-200">Заказы</a></li>
            <li><a href="zapros.php" class="mt-4 mb-4 block p-4 text-white bg-gradient-to-r from-purple-400 to-blue-500 rounded-lg transition duration-300 hover:bg-gray-700">Запросы</a></li>
            <li><a href="analytics.php" class="block p-4 text-gray-700 hover:bg-gray-200">Аналитика</a></li>
        </ul>

    </div>

    <div class="flex-1 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Panel - Orders and Dresses Management</h1>

        <!-- Вывод сообщений -->
        <?php if ($message): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-5">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Список свадебных платьев -->
        <h2 class="text-xl font-semibold mt-10 mb-3">Свадебные Платья</h2>
        <div class="bg-white p-5 rounded shadow">
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Название</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dresses as $dress): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['dresses_id']); ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['name']); ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['price']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="text-xl font-semibold mt-10 mb-3">Клиенты, Ожидающие Примерку</h2>
        <div class="bg-white p-5 rounded shadow">
            <ul>
                <?php if (count($waiting_clients) > 0): ?>
                    <?php foreach ($waiting_clients as $client): ?>
                        <li><?= htmlspecialchars($client['email']); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Нет клиентов, ожидающих примерку.</li>
                <?php endif; ?>
            </ul>
        </div>


        <!-- Список клиентов, сделавших заказ -->
        <h2 class="text-xl font-semibold mt-10 mb-3">Клиенты, Сделавшие Заказ</h2>
        <div class="bg-white p-5 rounded shadow">
            <ul>
                <?php foreach ($ordered_clients as $client): ?>
                    <li><?= htmlspecialchars($client['email']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Статус примерки для определенного клиента -->
        <h2 class="text-xl font-semibold mt-10 mb-3">Статус Примерки</h2>
        <form method="get" class="bg-white p-5 rounded shadow mb-5">
            <label class="block text-gray-700">ID клиента</label>
            <input type="number" name="client_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            <button type="submit" class="bg-blue-500 text-white mt-4 py-2 px-4 rounded">Проверить Статус</button>
        </form>

        <?php if (isset($fitting_status)): ?>
            <div class="bg-white p-5 rounded shadow">
                <h3 class="text-lg font-semibold">Статусы:</h3>
                <ul>
                    <?php if (!empty($fitting_status)): ?>
                        <?php foreach ($fitting_status as $status): ?>
                            <li>
                                <?= htmlspecialchars($status['fitting_required'] ? 'Требуется примерка' : 'Примерка не требуется'); ?> -
                                Статус заказа: <?= htmlspecialchars($status['order_status']); ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Нет заказов для этого клиента.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
