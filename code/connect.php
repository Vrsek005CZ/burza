<?php
$servername = "gymso-online-db";
$username = "vrseka";
$password = "Mdz8x!M0orB.07Ic";
$dbname = "vrseka_burza";

// Vytvoření připojení k databázi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
  die("Připojení selhalo: " . $conn->connect_error);
}
?>