<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burza";

// Vytvoření připojení k databázi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
  die("Připojení selhalo: " . $conn->connect_error);
}
?>