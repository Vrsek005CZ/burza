<?php
require_once "connect.php";

/**
 * Načte seznam učebnic a jejich kategorií.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Seznam učebnic s kategoriemi.
 */
function getUcebniceSeznam($conn) {
    $query = "SELECT ucebnice.id, ucebnice.jmeno, kategorie.nazev AS kategorie 
              FROM ucebnice
              JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
              ORDER BY kategorie.nazev, ucebnice.jmeno";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá aktuální rok.
 *
 * @return int Aktuální rok.
 */
function getCurrentYear() {
    return date("Y");
}
?>