<?php
session_start();
require_once '../../config/config.php';

// Проверка роли пользователя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Инициализация переменных
$user_id = $dress_id = $quantity = $total_cost = $order_date = $estimated_delivery_date = $order_status = '';
$fitting_required = 0;
$message = '';
$order_id = null;

// Функция для проверки формата даты
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Обработка добавления или обновления заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $user_id = intval($_POST['user_id']);
    $dress_id = intval($_POST['dress_id']);
    $quantity = intval($_POST['quantity']);
    $total_cost = floatval($_POST['total_cost']);
    $order_date = $conn->real_escape_string($_POST['order_date']);
    $estimated_delivery_date = $conn->real_escape_string($_POST['estimated_delivery_date']);
    $order_status = $conn->real_escape_string($_POST['order_status']);
    $fitting_required = isset($_POST['fitting_required']) ? 1 : 0;
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : null;

    // Проверка формата даты
    if (!validateDate($order_date, 'Y-m-d') || !validateDate($estimated_delivery_date, 'Y-m-d')) {
        $message = "Ошибка: Неверный формат даты. Используйте формат ГГГГ-ММ-ДД.";
    } else {
        if ($order_id) {
            // Обновление заказа
            $query = $conn->prepare("UPDATE orders SET user_id = ?, dress_id = ?, quantity = ?, total_cost = ?, order_date = ?, estimated_delivery_date = ?, order_status = ?, fitting_required = ? WHERE id = ?");
            $query->bind_param("iiidsssii", $user_id, $dress_id, $quantity, $total_cost, $order_date, $estimated_delivery_date, $order_status, $fitting_required, $order_id);
        } else {
            // Добавление нового заказа
            $query = $conn->prepare("INSERT INTO orders (user_id, dress_id, quantity, total_cost, order_date, estimated_delivery_date, order_status, fitting_required) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("iiidsssi", $user_id, $dress_id, $quantity, $total_cost, $order_date, $estimated_delivery_date, $order_status, $fitting_required);
        }

        // Выполнение запроса
        if ($query->execute()) {
            $message = $order_id ? "Заказ обновлен!" : "Заказ добавлен!";
            // Сброс переменных формы
            $user_id = $dress_id = $quantity = $total_cost = $order_date = $estimated_delivery_date = $order_status = '';
            $fitting_required = 0;
        } else {
            $message = "Ошибка: " . $query->error; // Вывод ошибки
            echo "<pre>"; // Для отладки
            print_r($query); // Вывод информации о запросе
            echo "</pre>";
        }
    }
}

// Удаление заказа
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    $query = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $query->bind_param("i", $order_id);
    if ($query->execute()) {
        $message = "Заказ удален!";
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Получение списка заказов
$query = $conn->query("SELECT * FROM orders");
$orders = $query->fetch_all(MYSQLI_ASSOC);

// Обработка редактирования заказа
if (isset($_GET['edit'])) {
    $order_id = intval($_GET['edit']);
    $query = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $query->bind_param("i", $order_id);
    $query->execute();
    $result = $query->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        $user_id = $order['user_id'];
        $dress_id = $order['dress_id'];
        $quantity = $order['quantity'];
        $total_cost = $order['total_cost'];
        $order_date = $order['order_date']; // Формат Y-m-d
        $estimated_delivery_date = $order['estimated_delivery_date']; // Формат Y-m-d
        $order_status = $order['order_status'];
        $fitting_required = $order['fitting_required'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Orders Management</title>
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
            <li><a href="orders.php" class="mt-4 mb-4 block p-4 text-white bg-gradient-to-r from-purple-400 to-blue-500 rounded-lg transition duration-300 hover:bg-gray-700">Заказы</a></li>
            <li><a href="zapros.php" class="block p-4 text-blue-700 hover:bg-gray-200">Запросы</a></li>
            <li><a href="analytics.php" class="block p-4 text-gray-700 hover:bg-gray-200">Аналитика</a></li>
        </ul>

    </div>

    <div class="flex-1 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Panel - Orders Management</h1>

        <?php if ($message): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-5">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="orders.php" method="post" class="bg-white p-5 rounded shadow mb-5">
            <h2 class="text-xl font-semibold mb-3">Добавить / Редактировать Заказ</h2>
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id); ?>">

            <div class="mb-4">
                <label class="block text-gray-700">ID пользователя</label>
                <input type="number" name="user_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($user_id); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">ID платья</label>
                <input type="number" name="dress_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($dress_id); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Количество</label>
                <input type="number" name="quantity" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($quantity); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Общая стоимость</label>
                <input type="number" step="0.01" name="total_cost" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($total_cost); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Дата заказа</label>
                <input type="date" name="order_date" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($order_date); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Ожидаемая дата доставки</label>
                <input type="date" name="estimated_delivery_date" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($estimated_delivery_date); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Статус заказа</label>
                <input type="text" name="order_status" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($order_status); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Требуется примерка</label>
                <input type="checkbox" name="fitting_required" class="mt-1" <?= $fitting_required ? 'checked' : ''; ?>>
            </div>

            <button type="submit" name="submit" class="bg-blue-500 text-white p-2 rounded">Сохранить заказ</button>
        </form>

        <h2 class="text-xl font-semibold mb-3">Список Заказов</h2>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">ID пользователя</th>
                <th class="border border-gray-300 px-4 py-2">ID платья</th>
                <th class="border border-gray-300 px-4 py-2">Количество</th>
                <th class="border border-gray-300 px-4 py-2">Общая стоимость</th>
                <th class="border border-gray-300 px-4 py-2">Дата заказа</th>
                <th class="border border-gray-300 px-4 py-2">Ожидаемая дата доставки</th>
                <th class="border border-gray-300 px-4 py-2">Статус заказа</th>
                <th class="border border-gray-300 px-4 py-2">Требуется примерка</th>
                <th class="border border-gray-300 px-4 py-2">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['id']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['user_id']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['dress_id']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['quantity']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['total_cost']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['order_date']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['estimated_delivery_date']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($order['order_status']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= $order['fitting_required'] ? 'Да' : 'Нет'; ?></td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="orders.php?edit=<?= htmlspecialchars($order['id']); ?>" class="text-blue-500">Редактировать</a>
                        <a href="orders.php?delete=<?= htmlspecialchars($order['id']); ?>" class="text-red-500 ml-2">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
