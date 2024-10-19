<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burza";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Připojení selhalo: " . $conn->connect_error);
}
?>