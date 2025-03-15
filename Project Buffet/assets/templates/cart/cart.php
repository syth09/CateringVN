<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to load the cart items
function loadCart()
{
    $cart = $_SESSION['cart'];
    $totalPrice = 0;

    echo '<div id="cart-items">';
    foreach ($cart as $item) {
        echo '<div class="cart-item">';
        echo '<span>' . htmlspecialchars($item['name']) . '</span>';
        echo '<span>$' . number_format($item['price'], 2) . '</span>';
        echo '</div>';
        $totalPrice += $item['price'];
    }
    echo '</div>';

    echo '<div class="total" id="total-price">Total: $' . number_format($totalPrice, 2) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart</title>
    <link rel="stylesheet" href="./cart.css">
    <style>
        .cart-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="cart-container">
        <h1>Giỏ Hàng</h1>
        <?php loadCart(); ?>
        <!-- Call the PHP function to load the cart -->
    </div>
</body>

</html>