<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db_connection.php'; // Kết nối CSDL

// Xử lý xóa món ăn
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $menu_id = $_GET['id'];

    // Xóa món ăn khỏi CSDL
    $sql = "DELETE FROM menu WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $menu_id]);

    // Chuyển hướng về trang dashboard
    header("Location: dashboard.php");
    exit();
}
