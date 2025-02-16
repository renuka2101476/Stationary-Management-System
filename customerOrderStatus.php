<?php
session_start();
require_once "dbconnection.php";

if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to view your orders.'); window.location.href='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

// Fetch user ID
$sql = "SELECT id FROM userinfo WHERE username = '$username'";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('User not found.'); window.location.href='Dashboard.html';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);
$uid = $row['id'];

// Fetch orders with payment details
$sql = "SELECT o.order_id, o.product_name, o.quantity, o.total_price,
               p.total_amount, p.payment_method, p.status AS payment_status, p.phone_no, p.address, o.order_status
        FROM orders o
        JOIN processpayment p ON o.payment_id = p.payment_id
        WHERE o.user_id = '$uid'
        ORDER BY o.order_id DESC";


$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        body {
            background: #f4f4f4;
            color: #333;
            padding: 20px;
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
			color:white;
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
            width: 80%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
        }

		.status-processing { color: blue; font-weight: bold; }
.status-shipped { color: purple; font-weight: bold; }
.status-completed { color: green; font-weight: bold; }
.status-cancelled { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="header">
        <p>Stationery Store</p>
        <ul>
            <li><a href="Dashboard.html">Home Page</a></li>
            <li><a href="login.php">Log Out</a></li>
        </ul>
    </div>
    
    <div class="container">
        <h2>Order History</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price (Rs.)</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Delivery Address</th>
                    <th>Phone No.</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo strtoupper($row['payment_method']); ?></td>
                        <td class="<?php echo ($row['payment_status'] == 'pending') ? 'status-pending' : 'status-completed'; ?>">
                            <?php echo ucfirst($row['payment_status']); ?>
                        </td>
                        <td class="<?php 
    $status_class = 'status-cancelled'; // Default class

    if ($row['order_status'] == 'pending') {
        $status_class = 'status-pending';
    } elseif ($row['order_status'] == 'processing') {
        $status_class = 'status-processing';
    } elseif ($row['order_status'] == 'shipped') {
        $status_class = 'status-shipped';
    } elseif ($row['order_status'] == 'delivered') {
        $status_class = 'status-completed';
    }

    echo $status_class; 
?>">
    <?php echo ucfirst($row['order_status']); ?>
</td>


                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo $row['phone_no']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
