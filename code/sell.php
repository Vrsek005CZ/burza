<?php
$current_year = date("Y");
// Získání seznamu učebnic
$sql = "SELECT id, jmeno FROM ucebnice";
$sql = "SELECT ucebnice.id, ucebnice.jmeno, kategorie.nazev AS kategorie 
        FROM ucebnice
        JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
        ORDER BY kategorie.nazev, ucebnice.jmeno"; 
$result = $conn->query($sql);
?>