<?php
session_start();
require_once "dbconnection.php"; // Include external DB connection file

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to add items to the cart.");
}

// Fetch the logged-in user's ID
$username = $_SESSION['username'];
$sql = "SELECT id FROM userinfo WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user) {
    die("Error: User not found.");
}
$uid = $user['id'];

// Handle "Add to Cart" action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $pid = $_POST['product_id'];

    // Fetch the product price
    $sql = "SELECT price FROM products WHERE product_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Error: Product not found.");
    }

    $price = $product['price'];

    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE uid = ? AND pid = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $uid, $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE uid = ? AND pid = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $uid, $pid);
    } else {
        // Insert a new product
        $sql = "INSERT INTO cart (uid, pid, quantity, price) VALUES (?, ?, 1, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iid", $uid, $pid, $price);
    }

    if ($stmt->execute()) {
        header("Location: cart.php");
        exit();
    } else {
        echo "Error updating cart.";
    }
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stationery Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('images/background.jpg');
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .header {
            background-color: black;
            padding: 15px;
            height: 70px;
        }
        ul {
            float: right;
            list-style-type: none;
            margin-top: 20px;
        }
        ul li {
            display: inline-block;
        }
        ul li a {
            text-decoration: none;
            color: #fff;
            padding: 5px 20px;
            border: 1px solid #fff;
            transition: 0.6s ease;
        }
        ul li a:hover, ul li.active a {
            background-color: #fff;
            color: #000;
        }
        .title {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.6);
            padding: 10px;
            border-radius: 10px;
            width: 50%;
            margin: 20px auto;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 20px;
        }
        .product {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.4s ease;
        }
        .product:hover img {
            transform: scale(1.1);
        }
        .product h2 {
            font-size: 20px;
            margin-top: 15px;
            color: #333;
        }
        .product p {
            font-size: 16px;
            color: #666;
        }
        .product button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .product button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <p style="color:white;font-size: 30px; float: left; margin-left: 50px;">Stationery Store</p>
        <ul>
            <li><a href="Dashboard.html">Home Page</a></li>
            <li class="active"><a href="#">Shopping Page</a></li>
            <li><a href="cart.php">My Cart</a></li>
            <li><a href="customerOrderStatus.php">Order Status</a></li>
            <li><a href="login.html" onclick="logout();">Log Out</a></li>
        </ul>
        <script type="text/javascript">
            function logout() {
                alert('Logged Out Successfully');
            }
        </script>
    </div>
    <div class="title">
        <h1>SHOPPING PAGE</h1>
    </div>
    <div class="container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <h2><?= htmlspecialchars($row['product_name']) ?></h2>
                <p>Price: ₹<?= number_format($row['price'], 2) ?></p>
                <p>Stock: <?= $row['stock_quantity'] ?></p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
