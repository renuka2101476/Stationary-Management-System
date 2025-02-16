<?php
require_once "dbconnection.php";

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch existing product details
    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $query);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        echo "<script>alert('Product not found!'); window.location.href='viewStock.php';</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $product_name = mysqli_real_escape_string($con, $_POST["product_name"]);
    $price = mysqli_real_escape_string($con, $_POST["price"]);
    $stock_quantity = mysqli_real_escape_string($con, $_POST["stock_quantity"]);

    // Handle image update
    if (!empty($_FILES["image"]["name"])) {
        $image_name = $_FILES["image"]["name"];
        $image_tmp = $_FILES["image"]["tmp_name"];
        $image_folder = "Images/" . basename($image_name);

        // Ensure folder exists
        if (!is_dir("Images")) {
            mkdir("Images", 0777, true);
        }

        if (move_uploaded_file($image_tmp, $image_folder)) {
            // Update query with image
            $query = "UPDATE products SET product_name='$product_name', price='$price', 
                      stock_quantity='$stock_quantity', image='$image_name' WHERE product_id='$product_id'";
        } else {
            echo "<script>alert('Failed to upload image!');</script>";
        }
    } else {
        // Update without changing the image
        $query = "UPDATE products SET product_name='$product_name', price='$price', 
                  stock_quantity='$stock_quantity' WHERE product_id='$product_id'";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Product Updated Successfully'); window.location.href='viewStock.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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
            color: white;
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
            color: black;
        }
        .container {
            width: 450px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #aaa;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .image-preview {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .image-preview img {
            width: 120px;
            height: auto;
            border-radius: 5px;
            border: 2px solid #ddd;
            padding: 5px;
            background: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>Stationery Store - Admin Panel</p>
        <div class="nav">
            <ul>
                <li><a href="adminDashboard.html">Home</a></li>
                <li><a href="adminLogin.php" onclick="logout();">Log Out</a></li>
            </ul>
        </div>
    </div>
    
    <div class="title">
        <h2>Update Product</h2>
    </div>

    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">

            <label>Product Name:</label>
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

            <label>Price (Rs.):</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label>Stock Quantity:</label>
            <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>

            <label>Current Image:</label>
            <div class="image-preview">
                <img src="Images/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
            </div>

            <label>Upload New Image (Optional):</label>
            <input type="file" name="image">

            <input type="submit" value="Update Product">
        </form>
    </div>

</body>
</html>
