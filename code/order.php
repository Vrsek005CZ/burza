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

function cancelOrder($conn, $orderID) {
    // Zahájení transakce
    $conn->begin_transaction();

    try {
        // Získání puID z tabulky orders
        $query = "SELECT puID FROM orders WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Chyba při přípravě dotazu pro získání objednávky: " . $conn->error);
        }
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if (!$order) {
            throw new Exception("Objednávka nenalezena.");
        }

        $puID = $order['puID'];

        // Odstranění záznamu z tabulky orders
        $deleteQuery = "DELETE FROM orders WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        if (!$deleteStmt) {
            throw new Exception("Chyba při přípravě dotazu pro odstranění objednávky: " . $conn->error);
        }
        $deleteStmt->bind_param("i", $orderID);
        $deleteStmt->execute();
        $deleteStmt->close();

        // Aktualizace hodnoty pu.koupil na 0
        $updateQuery = "UPDATE pu SET koupil = 0 WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if (!$updateStmt) {
            throw new Exception("Chyba při přípravě dotazu pro aktualizaci učebnice: " . $conn->error);
        }
        $updateStmt->bind_param("i", $puID);
        $updateStmt->execute();
        $updateStmt->close();

        // Potvrzení transakce
        $conn->commit();
        return ["success" => true, "msg" => "Objednávka byla úspěšně zrušena."];
    } catch (Exception $e) {
        // Zrušení transakce při chybě
        $conn->rollback();
        return ["success" => false, "msg" => $e->getMessage()];
    }
}

?>