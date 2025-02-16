<?php
require_once "dbconnection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $sql = "UPDATE orders SET order_status = '$new_status' WHERE order_id = '$order_id'";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Order status updated successfully!'); window.location.href='adminDashboard.html';</script>";
    } else {
        echo "<script>alert('Error updating status: " . mysqli_error($con) . "');</script>";
    }
}

// Fetch all orders to display in the form
$sql = "SELECT order_id, order_status FROM orders ORDER BY order_id DESC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Order Status</title>
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
            width: 80%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #444;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;

        }
        .colour
        {
            color: black;
        }
        th {
            background: #333;
            color: white;
            text-transform: uppercase;
        }
        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        button:hover {
            background: #218838;
        }
        .status-pending { color: orange; font-weight: bold; }
        .status-processing { color: blue; font-weight: bold; }
        .status-shipped { color: purple; font-weight: bold; }
        .status-delivered { color: green; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="header">
        <p>Stationery Store</p>
        <div class="nav">
            <ul>
                <li><a href="adminDashboard.html">Home</a></li>
                <li class="active"><a href="#">Update Order</a></li>
                <li><a href="adminLogin.php" onclick="logout();">Log Out</a></li>
            </ul>
        </div>
    </div>
    <div class="container">
        <h2>Update Order Status</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Current Status</th>
                <th>Update Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="colour"><?php echo $row['order_id']; ?></td>
                    <td class="status-<?php echo strtolower($row['order_status']); ?>">
                        <?php echo ucfirst($row['order_status']); ?>
                    </td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="order_status">
                                <option value="pending" <?php echo ($row['order_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo ($row['order_status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo ($row['order_status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo ($row['order_status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo ($row['order_status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
