<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db_connection.php'; // Kết nối CSDL

// Lấy thông tin món ăn cần chỉnh sửa
$menu_id = $_GET['id'] ?? null;
if (!$menu_id) {
    header("Location: dashboard.php");
    exit();
}

$sql = "SELECT * FROM menu WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $menu_id]);
$menu_item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu_item) {
    header("Location: dashboard.php");
    exit();
}

// Xử lý cập nhật món ăn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_menu'])) {
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - CateringVN</title>
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
        <h1>Edit Dish</h1>
        <form method="POST" action="" class="form">
            <div class="inputContainer">
                <input type="text" name="dish_name" class="input" placeholder="Dish Name" value="<?php echo htmlspecialchars($menu_item['dish_name']); ?>" required />
                <label for="" class="label">Dish Name</label>
            </div>
            <div class="inputContainer">
                <textarea name="description" class="input" placeholder="Description" required><?php echo htmlspecialchars($menu_item['description']); ?></textarea>
                <label for="" class="label">Description</label>
            </div>
            <div class="inputContainer">
                <input type="number" name="price" class="input" placeholder="Price" step="0.01" value="<?php echo htmlspecialchars($menu_item['price']); ?>" required />
                <label for="" class="label">Price</label>
            </div>
            <div class="inputContainer">
                <input type="text" name="image" class="input" placeholder="Image URL" value="<?php echo htmlspecialchars($menu_item['image']); ?>" required />
                <label for="" class="label">Image URL</label>
            </div>
            <div>
                <button type="submit" name="edit_menu" class="btn">Update Dish</button>
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