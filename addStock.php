<?php
require_once "dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($con, $_POST["product_name"]);
    $price = mysqli_real_escape_string($con, $_POST["price"]);
    $stock_quantity = mysqli_real_escape_string($con, $_POST["stock_quantity"]);
    
    // Image Upload Handling
    $image_name = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $image_folder = "Images/" . basename($image_name); // Correct folder case

    // Ensure folder exists
    if (!is_dir("Images")) {
        mkdir("Images", 0777, true);
    }

    if (move_uploaded_file($image_tmp, $image_folder)) {
        $query = "INSERT INTO products (product_name, price, stock_quantity, image) 
                  VALUES ('$product_name', '$price', '$stock_quantity', '$image_name')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Product Added Successfully');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image. Check folder permissions!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
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
            color:white;
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
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px gray;
            border-radius: 10px;
        }
        input, button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: darkgreen;
        }
    </style>
</head>
<body>
<div class="header">
        <p>Stationery Store</p>
        <div class="nav">
            <ul>
                <li><a href="adminDashboard.html">Home</a></li>
                <li class="active"><a href="#">View Products</a></li>
                <li><a href="adminLogin.php" onclick="logout();">Log Out</a></li>
            </ul>
        </div>
    </div>
    <div class="title">
        <h1>Add Products </h1>
    <div class="container">
        <h2>Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="number" name="price" placeholder="Price" required>
            <input type="number" name="stock_quantity" placeholder="Stock Quantity" required>
            <input type="file" name="image" required>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
