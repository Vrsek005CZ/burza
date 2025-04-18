<?php
require_once "connect.php";

/**
 * Získá všechny kategorie z databáze.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Pole kategorií.
 */
function getKategorie($conn) {
    $query = "SELECT id, nazev FROM kategorie";
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
 * Získá všechny unikátní třídy z databáze.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Pole tříd.
 */
function getTridy($conn) {
    $query = "SELECT DISTINCT trida_id FROM ucebnice ORDER BY trida_id";
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
 * Získá všechny učebnice z databáze.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Pole učebnic.
 */
function getUcebnice($conn) {
    $query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, 
    COUNT(CASE WHEN pu.koupil = 0 THEN 1 END) AS pocet_ks, 
    ROUND(AVG(CASE WHEN pu.koupil = 0 THEN pu.cena END)) AS avg_cena 
    FROM ucebnice 
    INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id 
    INNER JOIN typ ON ucebnice.typ_id=typ.id 
    INNER JOIN pu ON ucebnice.id=pu.id_ucebnice 
    GROUP BY ucebnice.id
    ORDER BY pocet_ks DESC";

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

?>