<?php
require_once "connect.php";

$current_year = date("Y");

// Získání seznamu učebnic
$sql = "SELECT ucebnice.id, ucebnice.jmeno, kategorie.nazev AS kategorie 
        FROM ucebnice
        JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
        ORDER BY kategorie.nazev, ucebnice.jmeno"; 

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}
?>