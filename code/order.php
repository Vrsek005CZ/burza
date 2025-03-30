<?php
require_once "connect.php";

/**
 * Načte detail objednávky podle ID.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $orderID ID objednávky.
 * @param string $typ Typ uživatele (id_prodejce nebo koupil).
 * @return array|null Detail objednávky nebo null, pokud není nalezena.
 */
function getOrderDetail($conn, $orderID, $typ) {
    $sql = "SELECT 
                orders.id as order_id, orders.puID as puID, orders.complete as complete, 
                pu.id_ucebnice, pu.id as puID, pu.id_prodejce, pu.stav as stav, pu.cena as cena, 
                pu.koupil as kupuje, ucebnice.jmeno as jmeno_ucebnice, ucebnice.id as ucebnice_id, 
                user.user as user_jmeno, user.id as user_id, user.email as mail
            FROM orders
            INNER JOIN pu ON orders.puID = pu.id
            INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
            INNER JOIN user ON pu.$typ = user.id
            WHERE orders.id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        return $order;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Potvrdí objednávku nastavením stavu `complete` na 1.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $orderID ID objednávky.
 * @return bool True, pokud byla objednávka úspěšně potvrzena, jinak false.
 */
function confirmOrder($conn, $orderID) {
    $sql = "UPDATE orders SET complete = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderID);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}
?>