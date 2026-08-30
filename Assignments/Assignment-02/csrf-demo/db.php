<?php
$host = "localhost";
$db_user = "csrf_user";
$db_pass = "csrf_pass123";
$db_name = "csrf_demo";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
