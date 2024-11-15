<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Страница не найдена</title>
    <link rel="stylesheet" href="assets/styles.css"> <!-- подключите стили вашего проекта -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f8f8;
            color: #333;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .error-container {
            text-align: center;
        }
        .error-container h1 {
            font-size: 72px;
            color: #e63946;
        }
        .error-container p {
            font-size: 24px;
            margin: 20px 0;
        }
        .error-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            background-color: #457b9d;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .error-container a:hover {
            background-color: #1d3557;
        }
    </style>
</head>
<body>
<div class="error-container">
    <h1>404</h1>
    <p>Упс! Страница не найдена.</p>
    <p>Возможно, она была удалена или перемещена.</p>
    <a href="../public/main.php">На главную</a>
</div>
</body>
</html>
