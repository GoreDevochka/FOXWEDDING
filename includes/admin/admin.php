<?php
session_start();
require_once '../../config/config.php';

// Проверка роли пользователя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Инициализация переменных формы
$name = '';
$style = '';
$price = '';
$image = '';
$dress_type_id = ''; // Новый тип платья
$message = '';
$dress_id = null;

// Обработка добавления или обновления платья
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $style = $conn->real_escape_string($_POST['style']);
    $price = floatval($_POST['price']);
    $dress_type_id = $conn->real_escape_string($_POST['dress_type_id']); // Получаем тип платья из формы

    // Обработка изображения
    $image = '';
    if (!empty($_FILES['image_file']['name'])) {
        $target_dir = "../../assets/evenings/";
        $target_file = $target_dir . basename($_FILES['image_file']['name']);
        $uploadOk = 1;

        // Проверка типа файла
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $message = "Только JPG, JPEG, PNG и GIF файлы допустимы.";
            $uploadOk = 0;
        }

        // Проверка ошибок
        if ($_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
            $message = "Ошибка загрузки файла.";
            $uploadOk = 0;
        }

        // Загрузка файла
        if ($uploadOk && move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            $image = $target_file;
        } else {
            $message = "Ошибка загрузки изображения.";
        }
    } elseif (!empty($_POST['image_url'])) {
        $image = $conn->real_escape_string($_POST['image_url']);
    } else {
        $message = "Загрузите изображение или укажите URL.";
    }

    $dress_id = isset($_POST['dress_id']) ? intval($_POST['dress_id']) : null;

    if ($dress_id) {
        // Обновление платья
        $query = $conn->prepare("UPDATE dresses SET name = ?, style = ?, price = ?, image = ?, dress_type_id = ? WHERE dresses_id = ?");
        $query->bind_param("ssdsdi", $name, $style, $price, $image, $dress_type_id, $dress_id);
    } else {
        // Добавление нового платья
        $query = $conn->prepare("INSERT INTO dresses (name, style, price, image, dress_type_id) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("ssdss", $name, $style, $price, $image, $dress_type_id);
    }

    if ($query->execute()) {
        $message = $dress_id ? "Платье обновлено!" : "Платье добавлено!";
        $name = $style = $price = $image = $dress_type_id = '';
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Удаление платья
if (isset($_GET['delete'])) {
    $dress_id = intval($_GET['delete']);
    $query = $conn->prepare("DELETE FROM dresses WHERE dresses_id = ?");
    $query->bind_param("i", $dress_id);
    if ($query->execute()) {
        $message = "Платье удалено!";
    } else {
        $message = "Ошибка: " . $query->error;
    }
}

// Получение списка платьев
$query = $conn->query("SELECT * FROM dresses");
$dresses = $query->fetch_all(MYSQLI_ASSOC);

// Обработка редактирования платья
if (isset($_GET['edit'])) {
    $dress_id = intval($_GET['edit']);
    $query = $conn->prepare("SELECT * FROM dresses WHERE dresses_id = ?");
    $query->bind_param("i", $dress_id);
    $query->execute();
    $result = $query->get_result();
    $dress = $result->fetch_assoc();

    if ($dress) {
        $name = $dress['name'];
        $style = $dress['style'];
        $price = $dress['price'];
        $image = $dress['image'];
        $dress_type_id = $dress['dress_type_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dress Management</title>
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
            <li><a href="admin.php" class="mt-4 mb-4 block p-4 text-white bg-gradient-to-r from-purple-400 to-blue-500 rounded-lg transition duration-300 hover:bg-gray-700">Платья</a></li>
            <li><a href="dress_types.php" class="block p-4 text-gray-700 hover:bg-gray-200">Типы платьев</a></li>
            <li><a href="users.php" class="block p-4 text-gray-700 hover:bg-gray-200">Пользователи</a></li>
            <li><a href="orders.php" class="block p-4 text-gray-700 hover:bg-gray-200">Заказы</a></li>
            <li><a href="zapros.php" class="block p-4 text-blue-700 hover:bg-gray-200">Запросы</a></li>
            <li><a href="analytics.php" class="block p-4 text-purple-700 hover:bg-gray-200">Аналитика</a></li>
        </ul>
    </div>

    <div class="flex-1 p-5">
        <h1 class="text-2xl font-bold mb-5">Admin Panel - Dresses Management</h1>

        <?php if ($message): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-5">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="admin.php" method="post" enctype="multipart/form-data" class="bg-white p-5 rounded shadow mb-5">
            <h2 class="text-xl font-semibold mb-3">Добавить / Редактировать Платье</h2>
            <input type="hidden" name="dress_id" value="<?= htmlspecialchars($dress_id); ?>">

            <div class="mb-4">
                <label class="block text-gray-700">Название платья</label>
                <input type="text" name="name" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($name); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Стиль</label>
                <input type="text" name="style" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($style); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Цена</label>
                <input type="number" name="price" required class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($price); ?>" step="0.01">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Тип платья</label>
                <select name="dress_type_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
                    <option value="1" <?= $dress_type_id == '1' ? 'selected' : ''; ?>>Свадебное</option>
                    <option value="2" <?= $dress_type_id == '2' ? 'selected' : ''; ?>>Вечернее</option>
                    <option value="3" <?= $dress_type_id == '3' ? 'selected' : ''; ?>>Праздничное</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">URL изображения (опционально)</label>
                <input type="text" name="image_url" class="mt-1 block w-full p-2 border border-gray-300 rounded" value="<?= htmlspecialchars($image); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Загрузить изображение (опционально)</label>
                <input type="file" name="image_file" class="mt-1 block w-full p-2 border border-gray-300 rounded">
            </div>

            <button type="submit" name="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Сохранить Платье</button>
        </form>

        <h2 class="text-xl font-semibold mt-10 mb-3">Существующие Платья</h2>
        <div class="bg-white p-5 rounded shadow">
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Название</th>
                    <th class="border border-gray-300 p-2">Стиль</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                    <th class="border border-gray-300 p-2">Тип</th>
                    <th class="border border-gray-300 p-2">Изображение</th>
                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dresses as $dress): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['dresses_id']); ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['name']); ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['style']); ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['price']); ?> ₽</td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($dress['dress_type_id']); ?></td>
                        <td class="border border-gray-300 p-2"><img src="<?= htmlspecialchars($dress['image']); ?>" alt="Dress Image" class="w-20 h-auto"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="admin.php?edit=<?= htmlspecialchars($dress['dresses_id']); ?>" class="text-blue-500">Редактировать</a>
                            <a href="admin.php?delete=<?= htmlspecialchars($dress['dresses_id']); ?>" class="text-red-500" onclick="return confirm('Вы уверены, что хотите удалить это платье?');">Удалить</a>
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
