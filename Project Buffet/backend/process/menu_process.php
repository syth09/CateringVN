<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db_connection.php'; // Kết nối CSDL

// Xử lý thêm món ăn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu'])) {
    $dishName = $_POST['dish_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Thêm món ăn vào CSDL
    $sql = "INSERT INTO menu (dish_name, description, price, image) VALUES (:dish_name, :description, :price, :image)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'dish_name' => $dishName,
        'description' => $description,
        'price' => $price,
        'image' => $image
    ]);

    // Chuyển hướng về trang dashboard
    header("Location: dashboard.php");
    exit();
}

// Xử lý cập nhật món ăn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_menu'])) {
    $menu_id = $_POST['menu_id'];
    $dishName = $_POST['dish_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Cập nhật món ăn trong CSDL
    $sql = "UPDATE menu SET dish_name = :dish_name, description = :description, price = :price, image = :image WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'dish_name' => $dishName,
        'description' => $description,
        'price' => $price,
        'image' => $image,
        'id' => $menu_id
    ]);

    // Chuyển hướng về trang dashboard
    header("Location: dashboard.php");
    exit();
}

// Xử lý xóa món ın
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $menu_id = $_GET['delete_id'];

    // Xóa món ăn khỏi CSDL
    $sql = "DELETE FROM menu WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $menu_id]);

    // Chuyển hướng về trang dashboard
    header("Location: dashboard.php");
    exit();
}
