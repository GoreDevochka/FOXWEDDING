<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Подключение запросов к БД для сбора аналитики аналогично предыдущим примерам
$totalRevenueQuery = $conn->query("SELECT SUM(total_cost) as revenue FROM orders");
$totalRevenue = ($totalRevenueQuery && $totalRevenueQuery->num_rows > 0) ? $totalRevenueQuery->fetch_assoc()['revenue'] : 0;

$averageOrderValueQuery = $conn->query("SELECT AVG(total_cost) as avg_order_value FROM orders");
$averageOrderValue = ($averageOrderValueQuery && $averageOrderValueQuery->num_rows > 0) ? $averageOrderValueQuery->fetch_assoc()['avg_order_value'] : 0;

$topSellingQuery = $conn->query("
    SELECT d.name, COUNT(o.dress_id) AS sales_count, SUM(o.total_cost) AS revenue
    FROM orders o
    JOIN dresses d ON o.dress_id = d.dresses_id
    GROUP BY o.dress_id
    ORDER BY sales_count DESC
    LIMIT 5
");
$topSelling = ($topSellingQuery) ? $topSellingQuery->fetch_all(MYSQLI_ASSOC) : [];

$ordersByDayQuery = $conn->query("
    SELECT order_date, COUNT(*) as total_orders, SUM(total_cost) as revenue
    FROM orders
    GROUP BY order_date
    ORDER BY total_orders DESC
    LIMIT 5
");
$ordersByDay = ($ordersByDayQuery) ? $ordersByDayQuery->fetch_all(MYSQLI_ASSOC) : [];

$categoryStatsQuery = $conn->query("
    SELECT dt.type_name, COUNT(o.dress_id) AS dresses_sold, SUM(o.total_cost) AS total_revenue
    FROM orders o
    JOIN dresses d ON o.dress_id = d.dresses_id
    JOIN dress_types dt ON d.dress_type_id = dt.id
    GROUP BY dt.type_name
    ORDER BY dresses_sold DESC
");
$categoryStats = ($categoryStatsQuery) ? $categoryStatsQuery->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex flex-col md:flex-row">

<!-- Sidebar для навигации -->
<div class="bg-white w-full md:w-64 h-auto md:h-screen shadow-md">
    <div class="p-4">
        <h1 class="text-xl font-bold">Админка</h1>
    </div>
    <ul class="mt-5">
        <li><a href="../../public/main.php" class="block p-4 text-red-700 hover:bg-gray-200">На главную</a></li>
        <li><a href="admin.php" class="block p-4 text-gray-700 hover:bg-gray-200">Платья</a></li>
        <li><a href="dress_types.php" class="block p-4 text-gray-700 hover:bg-gray-200">Типы платьев</a></li>
        <li><a href="users.php" class="block p-4 text-gray-700 hover:bg-gray-200">Пользователи</a></li>
        <li><a href="orders.php" class="block p-4 text-gray-700 hover:bg-gray-200">Заказы</a></li>
        <li><a href="zapros.php" class="block p-4 text-blue-700 hover:bg-gray-200">Запросы</a></li>
        <li><a href="analytics.php" class="mt-4 mb-4 block p-4 text-white bg-gradient-to-r from-purple-400 to-blue-500 rounded-lg transition duration-300 hover:bg-gray-700">Аналитика</a></li>
    </ul>
</div>

<!-- Основное содержимое -->
<div class="flex-1 p-5">
    <h1 class="text-2xl font-bold mb-5">Аналитика - Dashboard</h1>

    <!-- Градиентные карточки с метриками -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div class="bg-gradient-to-r from-blue-500 to-purple-500 p-5 rounded shadow text-white">
            <h2 class="text-xl font-semibold">Общая выручка</h2>
            <p class="text-3xl font-bold"><?= number_format($totalRevenue, 2); ?> руб.</p>
        </div>
        <div class="bg-gradient-to-r from-green-400 to-teal-500 p-5 rounded shadow text-white">
            <h2 class="text-xl font-semibold">Средний чек</h2>
            <p class="text-3xl font-bold"><?= number_format($averageOrderValue, 2); ?> руб.</p>
        </div>
    </div>

    <!-- Диаграммы с адаптивной шириной -->
    <div class="bg-white p-5 rounded shadow mb-5 w-full">
        <h2 class="text-xl font-semibold mb-3">Продажи по категориям</h2>
        <div class="flex justify-center">
            <canvas id="categoryChart" class="w-full max-w-md"></canvas>
        </div>
    </div>

    <div class="bg-white p-5 rounded shadow mb-5 w-full">
        <h2 class="text-xl font-semibold mb-3">Самые продаваемые товары</h2>
        <div class="flex justify-center">
            <canvas id="topSellingChart" class="w-full max-w-md"></canvas>
        </div>
    </div>
</div>

<script>
    // Продажи по категориям - кольцевая диаграмма
    const categoryData = {
        labels: <?= json_encode(array_column($categoryStats, 'type_name')); ?>,
        datasets: [{
            label: 'Продажи по категориям (в рублях)',
            data: <?= json_encode(array_column($categoryStats, 'total_revenue')); ?>,
            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236'],
            hoverOffset: 4
        }]
    };

    const categoryConfig = {
        type: 'doughnut',
        data: categoryData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

    new Chart(document.getElementById('categoryChart'), categoryConfig);

    // Самые продаваемые товары - столбчатая диаграмма
    const topSellingData = {
        labels: <?= json_encode(array_column($topSelling, 'name')); ?>,
        datasets: [{
            label: 'Количество продаж',
            data: <?= json_encode(array_column($topSelling, 'sales_count')); ?>,
            backgroundColor: '#ff6384',
            hoverBackgroundColor: '#ff6384',
            borderColor: '#ff6384',
            borderWidth: 1
        }]
    };

    const topSellingConfig = {
        type: 'bar',
        data: topSellingData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutBounce'
            }
        }
    };


    new Chart(document.getElementById('topSellingChart'), topSellingConfig);
</script>
</body>
</html>
