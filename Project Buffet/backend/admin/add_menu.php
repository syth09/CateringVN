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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu - CateringVN</title>
    <link rel="stylesheet" href="../frontend/css/dashboard.css" />
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body class="bgc">
    <!-- Header -->
    <header>
        <div>
            <div class="flex items-center">
                <a class="mr-auto logo" href="index.html">CateringVietnam<span>.</span></a>
                <ul class="flex nav">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Add New Dish</h1>
        <form method="POST" action="" class="form">
            <div class="inputContainer">
                <input type="text" name="dish_name" class="input" placeholder="Dish Name" required />
                <label for="" class="label">Dish Name</label>
            </div>
            <div class="inputContainer">
                <textarea name="description" class="input" placeholder="Description" required></textarea>
                <label for="" class="label">Description</label>
            </div>
            <div class="inputContainer">
                <input type="number" name="price" class="input" placeholder="Price" step="0.01" required />
                <label for="" class="label">Price</label>
            </div>
            <div class="inputContainer">
                <input type="text" name="image" class="input" placeholder="Image URL" required />
                <label for="" class="label">Image URL</label>
            </div>
            <div>
                <button type="submit" name="add_menu" class="btn">Add Dish</button>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 Catering Vietnam. All rights reserved.</p>
        </div>
    </footer>

    <script src="../frontend/js/form-validation.js"></script>
</body>

</html>