<?php
session_start();
require_once '../../config/config.php';

// Проверка роли пользователя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Инициализация переменных формы
$type_name = '';
$message = '';
$dress_type_id = null;

// Обработка добавления или обновления типа платья
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $type_name = $conn->real_escape_string($_POST['type_name']);
    $dress_type_id = isset($_POST['dress_type_id']) ? intval($_POST['dress_type_id']) : null;

    if ($dress_type_id) {
        // Обновление типа платья
        $query = $conn->prepare("UPDATE dress_types SET type_name = ? WHERE id = ?");
        $query->bind_param("si", $type_name, $dress_type_id);
    } else {
        // Добавление нового типа платья
        $query = $conn->prepare("INSERT INTO dress_types (type_name) VALUES (?)");
        $query->bind_param("s", $type_name);
    }

    if ($query->execute()) {
        $message = $dress_type_id ? "Тип платья обновлен!" : "Тип платья добавлен!";
        $type_name = '';
        $dress_type_id = null; // Сброс переменной после выполнения
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Удаление типа платья
if (isset($_GET['delete'])) {
    $dress_type_id = intval($_GET['delete']);
    $query = $conn->prepare("DELETE FROM dress_types WHERE id = ?");
    $query->bind_param("i", $dress_type_id);
    if ($query->execute()) {
        $message = "Тип платья удален!";
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Получение списка типов платьев
$query = $conn->query("SELECT * FROM dress_types");
$dress_types = $query->fetch_all(MYSQLI_ASSOC);

// Обработка редактирования типа платья
if (isset($_GET['edit'])) {
    $dress_type_id = intval($_GET['edit']);
    $query = $conn->prepare("SELECT * FROM dress_types WHERE id = ?");
    $query->bind_param("i", $dress_type_id);
    $query->execute();
    $result = $query->get_result();
    $dress_type = $result->fetch_assoc();

    if ($dress_type) {
        $type_name = $dress_type['type_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dress Types Management</title>
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
            <li><a href="dress_types.php" class="mt-4 mb-4 block p-4 text-white bg-gradient-to-r from-purple-400 to-blue-500 rounded-lg transition duration-300 hover:bg-gray-700">Типы платьев</a></li>
            <li><a href="users.php" class="block p-4 text-gray-700 hover:bg-gray-200">Пользователи</a></li>
            <li><a href="orders.php" class="block p-4 text-gray-700 hover:bg-gray-200">Заказы</a></li>
            <li><a href="zapros.php" class="block p-4 text-blue-700 hover:bg-gray-200">Запросы</a></li>
            <li><a href="analytics.php" class="block p-4 text-gray-700 hover:bg-gray-200">Аналитика</a></li>
        </ul>

    </div>

    <div class="flex-1 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Panel - Dress Types Management</h1>

        <?php if ($message): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-5">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="dress_types.php" method="post" class="bg-white p-5 rounded shadow mb-5">
            <h2 class="text-xl font-semibold mb-3">Добавить / Редактировать Тип Платья</h2>
            <input type="hidden" name="dress_type_id" value="<?= htmlspecialchars($dress_type_id); ?>">

            <div class="mb-4">
                <label class="block text-gray-700">Название типа платья</label>
                <input type="text" name="type_name" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($type_name); ?>">
            </div>

            <button type="submit" name="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Сохранить Тип Платья</button>
        </form>

        <h2 class="text-xl font-semibold mt-10 mb-3">Существующие Типы Платьев</h2>
        <div class="bg-white p-5 rounded shadow">
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Название</th>
                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dress_types as $type): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($type['id']); ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($type['type_name']); ?></td>
                        <td class="border border-gray-300 p-2">
                            <a href="dress_types.php?edit=<?= htmlspecialchars($type['id']); ?>" class="text-blue-500">Редактировать</a>
                            <a href="dress_types.php?delete=<?= htmlspecialchars($type['id']); ?>" class="text-red-500" onclick="return confirm('Вы уверены, что хотите удалить этот тип платья?');">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
