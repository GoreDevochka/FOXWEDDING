<?php
session_start();
require_once '../../config/config.php';

// Проверка роли пользователя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Инициализация переменных формы
$user_id = '';
$email = '';
$password = '';
$role = '';
$message = '';
$edit_user_id = null;

// Обработка добавления или обновления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Хэширование пароля
    $role = $conn->real_escape_string(trim($_POST['role']));
    $edit_user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

    if ($edit_user_id) {
        // Обновление пользователя
        $query = $conn->prepare("UPDATE users SET email = ?, password = ?, role = ? WHERE user_id = ?");
        $query->bind_param("sssi", $email, $password, $role, $edit_user_id);
    } else {
        // Добавление нового пользователя
        $query = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $query->bind_param("sss", $email, $password, $role);
    }

    // Проверка на успешное выполнение запроса
    if ($query->execute()) {
        $message = $edit_user_id ? "Пользователь обновлен!" : "Пользователь добавлен!";
        // Сброс переменных формы
        $email = $password = $role = '';
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Удаление пользователя
if (isset($_GET['delete'])) {
    $delete_user_id = intval($_GET['delete']);
    $query = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $query->bind_param("i", $delete_user_id);
    if ($query->execute()) {
        $message = "Пользователь удален!";
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Получение списка пользователей
$query = $conn->query("SELECT * FROM users");
$users = $query->fetch_all(MYSQLI_ASSOC);

// Обработка редактирования пользователя
if (isset($_GET['edit'])) {
    $edit_user_id = intval($_GET['edit']);
    $query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $query->bind_param("i", $edit_user_id);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $user_id = $user['user_id'];
        $email = $user['email'];
        $role = $user['role'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Users Management</title>
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
            <li><a href="users.php" class="mt-4 mb-4 block p-4 text-white bg-gradient-to-r from-purple-400 to-blue-500 rounded-lg transition duration-300 hover:bg-gray-700">Пользователи</a></li>
            <li><a href="orders.php" class="block p-4 text-gray-700 hover:bg-gray-200">Заказы</a></li>
            <li><a href="zapros.php" class="block p-4 text-blue-700 hover:bg-gray-200">Запросы</a></li>
            <li><a href="analytics.php" class="block p-4 text-gray-700 hover:bg-gray-200">Аналитика</a></li>
        </ul>

    </div>

    <div class="flex-1 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Panel - Users Management</h1>

        <?php if ($message): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-5">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="users.php" method="post" class="bg-white p-5 rounded shadow mb-5">
            <h2 class="text-xl font-semibold mb-3">Добавить / Редактировать Пользователя</h2>
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">

            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($email); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Пароль</label>
                <input type="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Роль</label>
                <input type="text" name="role" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($role); ?>">
            </div>

            <button type="submit" name="submit" class="bg-blue-500 text-white p-2 rounded">Сохранить пользователя</button>
        </form>

        <h2 class="text-xl font-semibold mb-3">Список Пользователей</h2>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Email</th>
                <th class="border border-gray-300 px-4 py-2">Роль</th>
                <th class="border border-gray-300 px-4 py-2">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['user_id']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['email']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['role']); ?></td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="users.php?edit=<?= htmlspecialchars($user['user_id']); ?>" class="text-blue-500">Редактировать</a>
                        <a href="users.php?delete=<?= htmlspecialchars($user['user_id']); ?>" class="text-red-500 ml-2">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
