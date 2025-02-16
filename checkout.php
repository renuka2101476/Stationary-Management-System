<?php
session_start();
require_once "dbconnection.php";

if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to proceed with the payment.'); window.location.href='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

// Fetch user ID and phone number
$sql = "SELECT id, phone_no FROM userinfo WHERE username = '$username'";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Error fetching user details.'); window.location.href='Dashboard.html';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);
$uid = $row['id'];
$phone_no = $row['phone_no'];

// Fetch cart items
$sql = "SELECT p.product_id, p.product_name, p.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.pid = p.product_id 
        WHERE c.uid = '$uid'";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Your cart is empty. Please add items before proceeding to payment.'); window.location.href='Dashboard.html';</script>";
    exit();
}

$totalAmount = 0;
$cartItems = [];

while ($row = mysqli_fetch_assoc($result)) {
    $productTotal = $row['quantity'] * $row['price'];
    $totalAmount += $productTotal;
    $cartItems[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['pay']) || empty($_POST['fname']) || empty($_POST['address'])) {
        echo "<script>alert('Please enter valid delivery details and select a payment method.');</script>";
    } else {
        $payment_method = mysqli_real_escape_string($con, $_POST['pay']);
        $full_name = mysqli_real_escape_string($con, $_POST['fname']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $status = "completed";

        // Store payment details
        $sql = "INSERT INTO processpayment (user_id, Name,total_amount, payment_method, status, phone_no, address) 
                VALUES ('$uid','$full_name', '$totalAmount', '$payment_method', '$status', '$phone_no', '$address')";
        
        if (!mysqli_query($con, $sql)) {
            echo "<script>alert('Error processing payment: " . mysqli_error($con) . "');</script>";
            exit();
        }

        $payment_id = mysqli_insert_id($con);

        // Store order details
        foreach ($cartItems as $item) {
            $product_name = mysqli_real_escape_string($con, $item['product_name']);
            $quantity = $item['quantity'];
            $total_price = $item['quantity'] * $item['price'];
            $order_status = "pending";
           

            $sql = "INSERT INTO orders (user_id, payment_id, product_name, quantity, total_price, username,order_status) 
                    VALUES ('$uid', '$payment_id','$product_name', '$quantity','$total_price', '$username','$order_status')";

            if (!mysqli_query($con, $sql)) {
                echo "<script>alert('Error placing order: " . mysqli_error($con) . "');</script>";
                exit();
            }
        }

        // Clear cart after order is placed
        $sql = "DELETE FROM cart WHERE uid = '$uid'";
        if (!mysqli_query($con, $sql)) {
            echo "<script>alert('Error clearing the cart: " . mysqli_error($con) . "');</script>";
            exit();
        }

        echo "<script>alert('Order placed successfully! '); window.location.href='Dashboard.html';</script>";
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            box-sizing: border-box;
        }
        body {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('Images/background3.jpg');
            height: 100vh;
            background-size: cover;
            background-position: center;
            color: white;
        }
        .header {
            background-color: black;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header p {
            font-size: 30px;
        }
        .header ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .header ul li {
            display: inline;
        }
        .header ul li a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border: 1px solid white;
            transition: 0.3s;
        }
        .header ul li a:hover, .header ul li.active a {
            background-color: white;
            color: black;
        }
        .container {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: center;
            padding: 20px;
            margin: 50px auto;
            width: 80%;
            gap: 30px;
        }
        .cart-items, .payment-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            width: 45%;
            font-size: 19px;
        }
        .cart-item {
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid white;
        }
        .total {
            font-size: 25px;
            font-weight: bold;
            margin-top: 20px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
        button {
            margin-top: 20px;
            padding: 15px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            border-radius: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>Stationery Store</p>
        <ul>
            <li><a href="Dashboard.html">Home Page</a></li>
            <li><a href="contactUs.html">Contact Us</a></li>
            <li class="active"><a href="#">Checkout</a></li>
            <li><a href="login.php">Log Out</a></li>
        </ul>
    </div>
    
    <div class="container">
        <div class="cart-items">
            <h2>Your Products</h2>
            <?php
            if (!empty($cartItems)) {
                foreach ($cartItems as $item) {
                    echo "<div class='cart-item'>";
                    echo "<p>Product: " . htmlspecialchars($item['product_name']) . "</p>";
                    echo "<p>Price: Rs. " . number_format($item['price'], 2) . "</p>";
                    echo "<p>Quantity: " . $item['quantity'] . "</p>";
                    
                    echo "</div>";
                }
                echo "<p class='total'>Total Amount: Rs. " . number_format($totalAmount, 2) . "</p>";
            } else {
                echo "<p>Your cart is empty.</p>";
            }
            ?>
        </div>
        
        <div class="payment-form">
            <h2>Payment & Delivery Details</h2>
            <form action="checkout.php" method="POST">
                <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
                <label for="fname">Name</label>
                <input type="text" name="fname" id="fname" required>
                
                <label for="address">Delivery Address</label>
                <input type="text" name="address" id="address" required>
                
                <label for="pay">Payment Method</label>
                <select name="pay" id="pay" required>
                    <option value="">Select Payment Method</option>
                    <option value="UPI">UPI</option>
                    <option value="CASH">Cash on Delivery</option>
                </select>
                
                <button type="submit">Place Order</button>
            </form>
        </div>
    </div>
</body>
</html>
