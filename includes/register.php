<?php
session_start();
require_once '../config/config.php';

if (isset($_POST['register'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = 'user';  // По умолчанию роль "user"

    // Проверка пароля на длину и наличие специальных символов
    if (strlen($password) < 8 || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        echo "Password must be at least 8 characters long and contain at least one special character.";
    } else {
        // Хеширование пароля после успешной проверки
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Проверка наличия пользователя с таким же email
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            echo "Email already registered.";
        } else {
            // Вставка нового пользователя
            $query = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $query->bind_param("sss", $email, $hashed_password, $role);
            if ($query->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['role'] = $role;
                header('Location: profile.php');
            } else {
                echo "Registration failed. Error: " . $query->error;
            }
        }
    }
}
?>
