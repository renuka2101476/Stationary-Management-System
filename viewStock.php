<?php
    require_once "dbconnection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Stock - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6));
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }
        .header {
            background: black;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header p {
            font-size: 24px;
            margin-left: 20px;
        }
        .nav ul {
            list-style: none;
            display: flex;
        }
        .nav ul li {
            margin: 0 10px;
        }
        .nav ul li a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            border: 1px solid white;
            transition: 0.3s;
        }
        .nav ul li a:hover, .nav ul li.active a {
            background: white;
            color: black;
        }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .product-card {
            width: 300px;
            background: #28231D;
            border: 2px solid black;
            margin: 15px;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            position: relative;
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
        .edit-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #f39c12;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }
        .edit-btn:hover {
            background: #e67e22;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>Stationery Store - Admin Panel</p>
        <div class="nav">
            <ul>
                <li><a href="adminDashboard.html">Home</a></li>
                <li class="active"><a href="#">View Products</a></li>
                <li><a href="adminLogin.php" onclick="logout();">Log Out</a></li>
            </ul>
        </div>
    </div>
    <div class="title">
        <h1>VIEW STOCK</h1>
    </div>
    <div class="container">
        <?php
            $query = "SELECT * FROM products";
            $result = mysqli_query($con, $query);

            if(mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product-card'>";
                    echo "<img src='Images/" . htmlspecialchars($row['image']) . "' alt='Product Image'>";
                    echo "<p><strong>Product ID:</strong> " . htmlspecialchars($row['product_id']) . "</p>";
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($row['product_name']) . "</p>";
                    if ($row['stock_quantity'] > 0) {
                        echo "<p><strong>Stock Quantity:</strong> " . htmlspecialchars($row['stock_quantity']) . "</p>";
                    } else {
                        echo "<p class='out-of-stock'>Out of Stock</p>";
                    }
                    echo "<p><strong>Price:</strong> Rs. " . htmlspecialchars($row['price']) . " per piece</p>";
                    // Attractive "Edit" button added
                    echo "<a href='updateStock.php?id=" . $row['product_id'] . "' class='edit-btn'>Update</a>";
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align:center;'>No products available.</p>";
            }
        ?>
    </div>
    <script>
        function logout() {
            alert('Logged Out Successfully');
        }
    </script>
</body>
</html>
