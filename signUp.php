<?php
    require_once "dbconnection.php";  // Include your DB connection

    // Get form input values
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; // No password hashing here
    $phone_no = $_POST['number']; // Changed the variable name to phone_no

    // Check if email already exists
    $sql = "SELECT * FROM userinfo WHERE email='$email'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        echo "<script>alert('There is an existing account with the entered email!'); window.location='signUp.html';</script>";
        exit();
    }

    // Check if username already exists
    $sql = "SELECT * FROM userinfo WHERE username='$username'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        echo "<script>alert('User Name already taken!'); window.location='signUp.html';</script>";
        exit();
    }

    // Insert new user into database (plain password, no hashing)
    $sql = "INSERT INTO userinfo(username, email, password, phone_no) VALUES ('$username', '$email', '$password', '$phone_no')";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Account created successfully!'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('Error creating account!'); window.location='signUp.html';</script>";
    }
?>



<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Times New Roman;
        }
        body {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url(Images/background.jpg);
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin-bottom: 50px;
        }
        ul {
            float: right;
            list-style-type: none;
            margin-top: 25px;
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
        ul li a:hover {
            background-color: #fff;
            color: #000;
        }
        ul li.active a {
            background-color: #fff;
            color: #000;
        }
        .header {
            background-color: black;
            padding: 10px;
            height: 45px;
        }
        form {
            border: 3px solid #f1f1f1;
            margin-left: 560px;
            margin-right: 580px;
            margin-top: 50px;
            padding: 20px;
            background: rgba(255,255,255,0.1);
            color: white;
        }
        label {
            width: 100px;
            display: inline-block;
            text-align: left;
            font-size: 18px;
        }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            opacity: 0.8;
        }
        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }
        img.avatar {
            width: 40%;
            border-radius: 50%;
        }
        .container {
            padding: 18px;
            color: white;
        }
        a {
            text-decoration: none;
        }
        .title {
            margin-left: 600px;
            margin-top: 50px;
        }
        .title h1 {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="header">
        <p style="color:white; font-size: 30px; float: left; margin-left: 50px;">Stationery Store</p>
        <ul>
            <li><a href="homepage.html">LogIn Page</a></li>
            <li class="active"><a href="#">Home Page</a></li>
            <li><a href="contactus.html">Contact Us</a></li>
        </ul>
    </div>
    <div class="title">
        <h1>Sign Up</h1>
    </div>
    <form action="" method="post">
        <div class="imgcontainer">
            <img src="Images/avatar.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter Username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter Email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter Password" required>

            <label><b>PhoneNo</b></label><br>
			<input type="tel" name="number" id="number" placeholder="Enter Phone No"  required><br>

            <input type="submit" name="submit" value="Register">
        </div>
    </form>
</body>
</html>
