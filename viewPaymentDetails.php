<?php
require_once "dbconnection.php"; 

$query = "SELECT * FROM processpayment ORDER BY payment_id DESC"; 
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
    <title>Payment Records - Admin</title>
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
            color: black;
        }
        .header {
            background: black;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: center;
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
        .status.failed {
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
            <li class="active"><a href="#">💳 View Payments</a></li>
            <li><a href="adminLogin.php" onclick="logout();">🚪 Log Out</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <h2>💳 Payment Records</h2>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>User ID</th>
            <th>Total Amount (Rs.)</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Phone No</th>
            <th>Address</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td>₹<?php echo htmlspecialchars($row['total_amount']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td>
                    <?php 
                        $status = strtolower($row['status']);
                        $status_class = ($status == 'pending') ? "pending" : (($status == 'completed') ? "completed" : "failed");
                    ?>
                    <span class="status <?php echo $status_class; ?>">
                        <?php echo ucfirst($status); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
            </tr>
        <?php } ?>

    </table>
</div>

</body>
</html>
