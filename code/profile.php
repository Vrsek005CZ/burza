<?php
require_once "connect.php";

/**
 * Aktualizuje třídu uživatele.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $userId ID uživatele.
 * @param int $tridaId ID třídy.
 * @return bool True, pokud byla třída úspěšně aktualizována, jinak false.
 */
function updateUserTrida($conn, $userId, $tridaId) {
    $query = "UPDATE user SET trida_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $tridaId, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

/**
 * Zpracuje požadavek na nastavení třídy uživatele.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $userId ID uživatele.
 * @return void
 */
function handleTridaUpdate($conn, $userId) {
    $tridaId = intval($_POST['trida_id']);
    if (updateUserTrida($conn, $userId, $tridaId)) {
        header("Location: profil.php"); // Přesměrování po úspěšném uložení
        exit;
    } else {
        echo "Chyba při ukládání třídy.";
    }
}

/**
 * Načte seznam prodávaných učebnic uživatele.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $userId ID uživatele.
 * @return array Seznam prodávaných učebnic.
 */
function getProdavaneUcebnice($conn, $userId) {
    $query = "SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil
              FROM pu
              JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
              WHERE pu.id_prodejce = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }
    return [];
}

// Načtení prodávaných učebnic, definování aliasu
$prodavaneUcebnice = getProdavaneUcebnice($conn, $userId);
?>