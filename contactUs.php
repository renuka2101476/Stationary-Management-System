<?php
require_once "dbconnection.php";  // Correctly include the connection file

// Define variables and initialize with empty values
$name = $email = $phone = $suggestion = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $suggestion = mysqli_real_escape_string($con, $_POST['suggestion']);

    // Insert data into the database
    $sql = "INSERT INTO contactus (name, phoneno, email, sugcom) 
            VALUES ('$name', '$phone', '$email', '$suggestion')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Thank you for contacting us!');</script>";
        // Redirect to the same page after successful submission
        echo "<script>window.location = 'contactus.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Century Gothic', sans-serif;
        }

        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('Images/background3.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            height: 100vh;
            overflow-x: hidden;
        }

        .header {
            background-color: black;
            padding: 10px;
            height: 80px;
        }

        ul {
            float: right;
            list-style-type: none;
            margin-top: 25px;
        }

        ul li {
            display: inline-block;
            margin-right: 25px;
            font-size: 18px;
        }

        ul li a {
            text-decoration: none;
            color: #fff;
            padding: 5px 20px;
            border: 1px solid #fff;
            transition: 0.6s ease;
            font-family: Times New Roman;
        }

        ul li a:hover {
            background-color: #fff;
            color: #000;
        }

        ul li.active a {
            background-color: #fff;
            color: #000;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 120px;
            padding-bottom: 20px;
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 40%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .form-container:hover {
            transform: scale(1.03);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            color: #fff;
        }

        .form-container label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #fff;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .contact-info div {
            text-align: center;
            margin: 0 15px;
        }

        .contact-info img {
            width: 40px;
        }

        .contact-info p {
            font-size: 16px;
            margin-top: 10px;
            color: #fff;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 40px;
        }

        .footer p {
            color: #fff;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .form-container {
                width: 80%;
            }

            .contact-info {
                flex-direction: column;
            }

            .contact-info div {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <p style="color:white;font-size: 30px; float: left;margin-left: 50px;font-family: Times New Roman; padding-top: 15px;">Stationery Store</p>
        <ul>
         <li> <a href="Dashboard.html">Homepage</a></li>
        

            <li class="active"><a href="#">Contact Us</a></li>
            
        </ul>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Suggestions / Complaints</h2>
            <form method="post" action="">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" placeholder="Your Name" required>

                <label for="phone">Phone No:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Your Phone Number" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="user@domain.com" required>

                <label for="suggestion">Suggestions/Complaints:</label>
                <textarea id="suggestion" name="suggestion" rows="4" placeholder="Your Suggestions or Complaints" required><?php echo $suggestion; ?></textarea>

                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>

    <!-- Contact Information Section -->
    <div class="contact-info">
        <div class="address">
            <img src="Images/locationicon.png" alt="Location Icon">
            <p>Stationery Store, XYZ Street, City</p>
        </div>
        <div class="phone">
            <img src="Images/phoneicon.png" alt="Phone Icon">
            <p>+91 1020304050</p>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Stationery Store</p>
    </div>

</body>
</html>
