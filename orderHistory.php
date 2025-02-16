<?php
require_once "dbconnection.php"; // Include database connection

$query = "SELECT * FROM orders ORDER BY order_id DESC"; // Fetch all orders
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Admin</title>
    <style>
       * {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(0,0,0,0.6));
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }
        .header {
            background: black;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
           
        }
        .header p {
            font-size: 24px;
            font-weight: bold;
        }
        .nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }
        .nav ul li {
            display: inline-block;
        }
        .nav ul li a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: 0.3s ease-in-out;
        }
        .nav ul li a:hover, .nav ul li.active a {
            background: white;
            color: black;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #aaa;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color:black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            color:black;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: black;
            color: white;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .status.pending {
            background: orange;
            color: white;
        }
        .status.completed {
            background: green;
            color: white;
        }
        .status.cancelled {
            background: red;
            color: white;
        }
    </style>
</head>
<body>

<div class="header">
    <p>Stationery Store - Admin Panel</p>
    <div class="nav">
        <ul>
            <li><a href="adminDashboard.html">🏠 Home</a></li>
            <li class="active"><a href="#">📦 View Orders</a></li>
            <li><a href="homepage.html" onclick="logout();">🚪 Log Out</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <h2>📜Order History</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Payment ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price (Rs.)</th>
            <th>Order Status</th>
            <th>Username</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td>₹<?php echo htmlspecialchars($row['total_price']); ?></td>
                <td>
                    <?php 
                        $status = strtolower($row['order_status']);
                        $status_class = ($status == 'pending') ? "pending" : (($status == 'completed') ? "completed" : "cancelled");
                    ?>
                    <span class="status <?php echo $status_class; ?>">
                        <?php echo ucfirst($status); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
            </tr>
        <?php } ?>

    </table>
</div>

</body>
</html>
