<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sit_inMS";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if($conn -> connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>