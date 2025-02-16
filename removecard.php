<?php
session_start();

// Check if product_id is provided and exists in the session cart
if (isset($_POST['product_id']) && isset($_SESSION['cart'][$_POST['product_id']])) {
    $product_id = $_POST['product_id'];

    // Connect to the database
    require_once "dbconnection.php"; // Include your database connection

    // Get the user ID from the session
    $username = $_SESSION['username']; // Access the username from the session

    // Get the user ID from the userinfo table based on the username
    $sql = "SELECT id FROM userinfo WHERE username = '$username'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Query Error: " . mysqli_error($con));  // Debugging
    }

    $row = mysqli_fetch_assoc($result);
    $uid = $row['id']; // Get the user ID associated with the username

    // Debugging: Check if UID and product_id are correct
    // echo "User ID: " . $uid . "<br>";
    // echo "Product ID to be removed: " . $product_id . "<br>";

    // Remove the item from the database (considering the correct schema)
    $sql = "DELETE FROM cart WHERE uid = '$uid' AND pid = '$product_id'";

    if (mysqli_query($con, $sql)) {
        // Debugging: Confirm database removal
        // echo "Product removed from database.<br>";

        // If the database removal was successful, remove the item from the session cart
        unset($_SESSION['cart'][$product_id]);

        // Debugging: Confirm session removal
        // echo "Product removed from session.<br>";

        // Redirect to the cart page after removal
        header("Location: cart.php");
        exit();
    } else {
        // Debugging: Show error message
        echo "Error removing item from cart: " . mysqli_error($con);
    }
} else {
    // If no valid product_id or item not found in cart, redirect back to the cart page
    echo "No valid product_id found.<br>";
    header("Location: cart.php");
    exit();
}
?>
