<?php
session_start();

// Include các file cần thiết
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db_connection.php';

// Kiểm tra đăng nhập và role admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../../frontend/login.html');
}

// Xử lý phân trang
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Lấy tổng số đơn hàng
$total_orders_query = "SELECT COUNT(*) AS total FROM orders";
$total_orders_stmt = $conn->query($total_orders_query);
$total_orders = $total_orders_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_orders / $items_per_page);

// Lấy danh sách đơn hàng với thông tin khách hàng
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../frontend/css/admin_dashboard.css" />
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Orders Management - CateringVN</title>
</head>

<body class="bgc">
    <!-- Header -->
    <header>
        <div class="flex items-center">
            <a class="mr-auto logo" href="../../frontend/index.html">CateringVietnam<span>.</span></a>
            <ul class="flex nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Orders Management</h1>

        <!-- Danh sách đơn hàng -->
        <section class="orders">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                <td>
                                    <a href="order_details.php?id=<?php echo $order['id']; ?>">View</a>
                                    <a href="update_order.php?id=<?php echo $order['id']; ?>">Update</a>
                                    <a href="delete_order.php?id=<?php echo $order['id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No orders found.</td>
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