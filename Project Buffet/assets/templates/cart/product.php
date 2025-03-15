<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];

    // Add the product to the cart
    $_SESSION['cart'][] = [
        'name' => $productName,
        'price' => $productPrice
    ];

    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle product search
$searchQuery = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchQuery = strtolower(trim($_GET['search']));
}

// Sample product data
$products = [
    [
        'name' => 'Product 1',
        'price' => 10.00,
        'image' => '../products/imgs/Phoebe-stare.png'
    ],
    [
        'name' => 'Product 2',
        'price' => 20.00,
        'image' => '../products/imgs/changli-board.png'
    ],
    [
        'name' => 'Product 3',
        'price' => 30.00,
        'image' => '../products/imgs/Zani-masked.png'
    ],
    [
        'name' => 'Product 4',
        'price' => 40.00,
        'image' => '../products/imgs/changli-board.png'
    ],
    [
        'name' => 'Product 5',
        'price' => 50.00,
        'image' => '../products/imgs/Cheeky-Camellya.png'
    ],
    [
        'name' => 'Product 6',
        'price' => 60.00,
        'image' => '../products/imgs/changli-board.png'
    ]
];

// Filter products based on search query
if (!empty($searchQuery)) {
    $products = array_filter($products, function ($product) use ($searchQuery) {
        return strpos(strtolower($product['name']), $searchQuery) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 120px 30px 30px 30px;
        }

        .box {
            width: 30%;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
            position: relative;
            border-radius: 9px;
        }

        .box img {
            width: 100%;
            height: 333px;
            border-radius: 9px 9px 0 0;
        }

        .box .details {
            padding: 10px;
        }

        .box .details h2 {
            margin: 0;
            font-size: 1.2em;
        }

        .box .details p {
            margin: 5px 0;
            color: #555;
        }

        .box .details .price {
            font-weight: bold;
            color: #000;
        }

        .box .details .add-to-cart {
            position: absolute;
            bottom: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 9px;
            cursor: pointer;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .cart-button,
        .search-button {
            width: 60px;
            height: 50px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8em;
            margin-left: 10px;
        }

        .search-box {
            width: 200px;
            height: 40px;
            padding: 5px;
            font-size: 1em;
        }
    </style>
</head>

<body>
    <div class="header">
        <form method="GET" action="" style="display: flex;">
            <input type="text" class="search-box" name="search" placeholder="Search product..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
        <a href="../products/cart.php" class="cart-button">Giỏ Hàng</a>
    </div>
    <div class="container" id="product-container">
        <?php foreach ($products as $product): ?>
            <div class="box">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <div class="details">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <button type="submit" name="add_to_cart" class="add-to-cart">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>