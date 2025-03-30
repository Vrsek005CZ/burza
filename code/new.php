<?php
require_once "connect.php";

/**
 * Získá seznam učebnic.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return mysqli_result Výsledek dotazu.
 */
function getUcebnice($conn) {
    $sql = "SELECT id, jmeno FROM ucebnice";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá seznam kategorií.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return mysqli_result Výsledek dotazu.
 */
function getKategorie($conn) {
    $sql = "SELECT id, nazev FROM kategorie";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá seznam typů.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return mysqli_result Výsledek dotazu.
 */
function getTypy($conn) {
    $sql = "SELECT id, nazev FROM typ";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}
?>