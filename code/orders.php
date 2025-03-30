<?php
require_once "connect.php";

/**
 * Načte seznam prodávaných objednávek (dokončené nebo čekající).
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $userId ID uživatele.
 * @param int $complete Stav objednávky (1 = dokončené, 0 = čekající).
 * @return array Seznam objednávek.
 */
function getProdavaneObjednavky($conn, $userId, $complete) {
    $query = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, 
                     pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, 
                     pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, 
                     user.user as user_jmeno, user.id as user_id
              FROM orders
              INNER JOIN pu ON orders.puID = pu.id
              INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
              INNER JOIN user ON pu.koupil = user.id
              WHERE pu.id_prodejce = ? AND orders.complete = ?
              ORDER BY orders.cas DESC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $complete);
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
 * Načte seznam kupovaných objednávek (dokončené nebo čekající).
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $userId ID uživatele.
 * @param int $complete Stav objednávky (1 = dokončené, 0 = čekající).
 * @return array Seznam objednávek.
 */
function getKupovaneObjednavky($conn, $userId, $complete) {
    $query = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, 
                     pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, 
                     pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, 
                     user.user as user_jmeno, user.id as user_id
              FROM orders
              INNER JOIN pu ON orders.puID = pu.id
              INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
              INNER JOIN user ON pu.id_prodejce = user.id
              WHERE pu.koupil = ? AND orders.complete = ?
              ORDER BY orders.cas DESC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $complete);
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