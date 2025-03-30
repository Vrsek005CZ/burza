<?php
session_start();
require_once "connect.php";
require_once "userinfo.php";

/**
 * Získá detail učebnice podle ID.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $puID ID učebnice.
 * @return array|null Detail učebnice nebo null, pokud není nalezena.
 */
function getBookDetail($conn, $puID) {
    $query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, 
                     ucebnice.trida_id, typ.nazev AS typ_nazev, pu.rok_tisku, pu.stav, pu.cena, 
                     pu.poznamky, user.user AS prodejce, user.id AS prodejce_id, pu.koupil as koupil
              FROM pu
              INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
              INNER JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
              INNER JOIN typ ON ucebnice.typ_id = typ.id
              INNER JOIN user ON pu.id_prodejce = user.id
              WHERE pu.id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $puID);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();
        return $book;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá seznam obrázků pro učebnici.
 *
 * @param int $puID ID učebnice.
 * @return array Seznam cest k obrázkům.
 */
function getBookImages($puID) {
    $cesta = "../foto/pu/$puID/";
    $files = glob($cesta . "*.webp"); // Vrátí všechny soubory s příponou .webp
    return $files;
}

if (isset($_GET['puID'])) {
    $puID = intval($_GET['puID']); // Proti SQL injection
} else {
    echo("Chyba: Nezadali jste ID knihy.");
    exit;
}

$row = getBookDetail($conn, $puID);

if (!$row) {
    echo "Učebnice nenalezena.";
    exit;
}

$files = getBookImages($puID);
$files_json = json_encode($files); // poslaní do js
?>