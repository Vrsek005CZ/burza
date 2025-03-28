<?php
require_once "connect.php";

/**
 * Načte informace o profilu uživatele.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $profileID ID profilu uživatele.
 * @return array Informace o profilu.
 */
function getProfil($conn, $profileID) {
    $query = "SELECT user.user, user.email, user.jmeno, user.prijmeni, user.trida_id
              FROM user
              WHERE user.id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $profileID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Načte seznam prodávaných učebnic uživatele.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $profileID ID profilu uživatele.
 * @return array Seznam prodávaných učebnic.
 */
function getProdavaneUcebnice($conn, $profileID) {
    $query = "SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil 
              FROM pu
              JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
              WHERE pu.id_prodejce = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $profileID);
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