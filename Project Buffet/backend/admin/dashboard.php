<?php
session_start(); // Bắt đầu session

// Include file functions.php
require_once __DIR__ . '/../../backend/includes/functions.php';

// Kiểm tra đăng nhập và role admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../frontend/login.html');
}

// Sử dụng đường dẫn tuyệt đối
require_once __DIR__ . '/../../backend/includes/db_connection.php'; // Kết nối CSDL

// Phân trang
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Lấy tổng số đơn hàng
$total_orders_query = "SELECT COUNT(*) AS total FROM orders";
$total_orders_stmt = $conn->query($total_orders_query);
$total_orders = $total_orders_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_orders / $items_per_page);

// Lấy danh sách đơn hàng với tên khách hàng từ bảng users và phân trang
$order_query = "
    SELECT orders.*, users.name AS customer_name 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    ORDER BY order_date DESC 
    LIMIT :limit OFFSET :offset
";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$order_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$order_stmt->execute();
$orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách menu
$menu_query = "SELECT * FROM menu";
$menu_stmt = $conn->query($menu_query);
$menu_items = $menu_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy thống kê doanh thu
$revenue_query = "SELECT SUM(total_amount) AS total_revenue FROM orders";
$revenue_stmt = $conn->query($revenue_query);
$total_revenue = $revenue_stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'];

// Lấy thông tin người dùng hiện tại
$user = getUserById($_SESSION['user_id']);

// Hiển thị tên đầy đủ
echo "Welcome, " . htmlspecialchars($user['name']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Sử dụng đường dẫn tương đối -->
    <link rel="stylesheet" href="../../frontend/css/admin_dashboard.css" />
    <!-- Hoặc sử dụng đường dẫn tuyệt đối -->
    <!-- <link rel="stylesheet" href="/frontend/css/admin_dashboard.css" /> -->
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Dashboard - CateringVN</title>
</head>

<body class="bgc">
    <!-- Header -->
    <header>
        <div class="flex items-center">
            <a class="mr-auto logo" href="../../frontend/index.html">CateringVietnam<span>.</span></a>
            <ul class="flex nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Admin Dashboard</h1>

        <!-- Thống kê doanh thu -->
        <section class="stats">
            <div class="stat-card">
                <h2>Total Revenue</h2>
                <p>$<?php echo number_format($total_revenue, 2); ?></p>
            </div>
        </section>

        <!-- Danh sách đơn hàng -->
        <section class="orders">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Phân trang -->
            <div class="pagination">
                <?php if ($total_pages > 1): ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Quản lý Menu -->
        <section class="menu-management">
            <h2>Menu Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Dish Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menu_items)): ?>
                        <?php foreach ($menu_items as $menu_item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($menu_item['dish_name']); ?></td>
                                <td><?php echo htmlspecialchars($menu_item['description']); ?></td>
                                <td>$<?php echo number_format($menu_item['price'], 2); ?></td>
                                <td>
                                    <a href="edit_menu.php?id=<?php echo $menu_item['id']; ?>">Edit</a>
                                    <a href="delete_menu.php?id=<?php echo $menu_item['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No menu items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="add_menu.php" class="btn">Add New Dish</a>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 Catering Vietnam. All rights reserved.</p>
        </div>
    </footer>

    <script src="../../frontend/js/sticky-header.js"></script>
</body>

</html>