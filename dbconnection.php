<?php
$con = mysqli_connect('localhost', 'root', '', 'softwaredb');

if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable error reporting
?>
