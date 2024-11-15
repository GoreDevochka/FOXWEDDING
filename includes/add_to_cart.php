<?php
session_start();
require_once '../config/config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$dress_id = $data['dressId'] ?? null;
$quantity = $data['quantity'] ?? null;
$totalCost = $data['totalCost'] ?? null;
$fittingRequired = $data['fittingRequired'] ?? 0;
$orderDate = $data['orderDate'] ?? null;
$estimatedDeliveryDate = $data['estimatedDeliveryDate'] ?? null;

if (!$dress_id || !$quantity || !$totalCost || !$orderDate || !$estimatedDeliveryDate) {
    echo json_encode(['success' => false, 'message' => 'Недостаточно данных для добавления в корзину.']);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("INSERT INTO orders (user_id, dress_id, quantity, total_cost, order_date, estimated_delivery_date, order_status, fitting_required) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");
if (!$query) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подготовки запроса: ' . $conn->error]);
    exit();
}

$fittingRequired = $fittingRequired ? 1 : 0;
$query->bind_param("iiisssi", $user_id, $dress_id, $quantity, $totalCost, $orderDate, $estimatedDeliveryDate, $fittingRequired);
$result = $query->execute();

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Товар добавлен в корзину!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка добавления в корзину: ' . $conn->error]);
}
?>
