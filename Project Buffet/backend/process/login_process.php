<?php
session_start();
require_once 'includes/db_connection.php'; // Kết nối CSDL

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin người dùng vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Chuyển hướng dựa trên vai trò
        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.html");
        }
        exit();
    } else {
        // Đăng nhập thất bại
        $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
        header("Location: login.php");
        exit();
    }
}
