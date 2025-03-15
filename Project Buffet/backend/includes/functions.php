<?php
// functions.php

// Hàm kiểm tra đăng nhập
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Hàm kiểm tra role của người dùng
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Hàm chuyển hướng
function redirect($url)
{
    header("Location: $url");
    exit();
}

// Hàm hiển thị thông báo
function displayMessage($message, $type = 'success')
{
    return "<div class='alert alert-$type'>$message</div>";
}

// Hàm lấy thông tin người dùng từ CSDL
function getUserById($userId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id, name, username, email, role FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Hàm kiểm tra xem email hoặc username đã tồn tại chưa
function isUserExists($email, $username)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);
    return $stmt->fetch();
}

// Hàm mã hóa mật khẩu
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

// Hàm kiểm tra mật khẩu
function verifyPassword($password, $hashedPassword)
{
    return password_verify($password, $hashedPassword);
}
