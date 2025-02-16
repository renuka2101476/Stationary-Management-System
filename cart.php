<?php
session_start();
require_once "dbconnection.php";

if (!isset($_SESSION['username'])) {
    die("You must be logged in to view the cart.");
}

$username = $_SESSION['username'];
$sql = "SELECT id FROM userinfo WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$uid = $user['id'];

$sql = "SELECT cart.pid, cart.quantity, products.product_name, products.price, products.image 
        FROM cart 
        JOIN products ON cart.pid = products.product_id 
        WHERE cart.uid = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

$totalAmount = 0;
$totalItems = 0;
$totalPrice = 0; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: #f4f4f4;
        }
        .header {
            background-color: black;
            padding: 15px;
            height: 80px;
        }
        .header p {
            color: white;
            font-size: 24px;
            float: left;
            margin-left: 20px;
        }
        ul {
            float: right;
            list-style-type: none;
            margin-top: 15px;
        }
        ul li {
            display: inline-block;
            margin-right: 20px;
        }
        ul li a {
            text-decoration: none;
            color: #fff;
            padding: 8px 20px;
            border: 1px solid #fff;
            transition: 0.6s ease;
        }
        ul li a:hover, ul li.active a {
            background-color: #fff;
            color: #000;
        }
        
        /* Cart Styling */
        .cart-container {
            background-color: white;
            padding: 20px;
            margin: 30px auto;
            width: 60%;
            max-width: 1200px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-item-details {
            flex: 1;
            padding-left: 20px;
        }
        .remove-btn {
            padding: 5px 10px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cart-total {
            margin-top: 30px;
            text-align: right;
            font-size: 18px;
        }
        .checkout-btn {
            display: block;
            width: 50%;
            padding: 15px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            margin-left: 250px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="header">
    <p>Stationery Web Portal</p>
    <ul>
        <li><a href="Dashboard.html">Home</a></li>
        <li class="active"><a href="#">My Cart</a></li>
        <li><a href="customerOrderStatus.php">Order Status</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</div>

<div class="cart-container">
    <h2>Cart Details</h2>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productId = $row['pid'];
            $productName = $row['product_name'];
            $productPrice = $row['price'];
            $productImage = $row['image'];
            $quantity = $row['quantity'];
            $productTotal = $productPrice * $quantity;

            $totalItems += $quantity;
            $totalPrice += $productTotal;
            ?>

            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($productImage) ?>" alt="<?= htmlspecialchars($productName) ?>">
                <div class="cart-item-details">
                    <h3><?= htmlspecialchars($productName) ?></h3>
                    <p>Price: ₹<?= number_format($productPrice, 2) ?></p>
                    <p>Quantity: <?= $quantity ?></p>
                    <p>Total: ₹<?= number_format($productTotal, 2) ?></p>
                </div>
                <form method="POST" action="removeCart.php">
                    <input type="hidden" name="product_id" value="<?= $productId ?>">
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </div>

            <?php
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>

    <div class="cart-total">
        <strong>Total Items: <?= $totalItems ?></strong><br>
        <strong>Total Price: ₹<?= number_format($totalPrice, 2) ?></strong>
    </div>

    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
</div>

</div>
</body>
</html>
