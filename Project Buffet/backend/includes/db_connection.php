<?php
// db_connection.php

$host = 'localhost'; // Địa chỉ máy chủ MySQL
$dbname = 'catering_vietnam'; // Tên CSDL
$username = 'root'; // Tên người dùng MySQL
$password = '123456a@'; // Mật khẩu MySQL

try {
    // Kết nối đến CSDL bằng PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Thiết lập chế độ báo lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Thiết lập chế độ mặc định fetch là associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Xử lý lỗi nếu kết nối thất bại
    die("Connection failed: " . $e->getMessage());
}
