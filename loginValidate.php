<?php
require_once "dbconnection.php";

if (isset($_POST['submit'])) {
    $username = $_POST['uname'];
    $password = $_POST['psw'];

    if ($username == "admin" && $password == "admin") {
        echo "<script>alert('LogIn Successful')</script>";
        echo "<script>window.location='adminChoice.html'</script>";
        exit();
    }

    // Check if username exists
    $sql = "SELECT * FROM userinfo WHERE username = '$username'";
    $res = mysqli_query($con, $sql);

    if (!$res) {
        die("Query Error: " . mysqli_error($con));  // Debugging
    }

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        // Direct string comparison (No Hashing)
        if ($password === $row['password']) {
            session_start();
            $_SESSION['username'] = $username;
            echo "<script>alert('LogIn Successful')</script>";
            echo "<script>window.location='Dashboard.html'</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password!')</script>";
            echo "<script>window.location='homepage.html'</script>";
        }
    } else {
        echo "<script>alert('User not found!')</script>";
        echo "<script>window.location='homepage.html'</script>";
    }
}
?>
