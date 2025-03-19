<?php
require_once "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderID']) && isset($_POST['typ'])) {
    $orderID = intval($_POST['orderID']);
    $typ = $_POST['typ'];

    $sql = "SELECT 
    orders.id as order_id, orders.puID as puID, orders.complete as complete, pu.id_ucebnice, pu.id as puID, pu.id_prodejce, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, ucebnice.jmeno as jmeno_ucebnice, ucebnice.id as ucebnice_id, user.user as user_jmeno, user.id as user_id, user.email as mail
    FROM orders
    INNER JOIN pu ON orders.puID = pu.id
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN user ON pu.$typ = user.id
    WHERE orders.id = ?";

    // Příprava a provedení dotazu pro prodej
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderID); // Parametr je typu integer
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderID']) && isset($_POST['confirm'])) {
    $orderID = intval($_POST["orderID"]);

    $sql = "UPDATE orders SET complete = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderID);
        if ($stmt->execute()) {
            echo "Objednávka byla úspěšně potvrzena!";
        } else {
            echo "Chyba při potvrzení objednávky.";
        }
        $stmt->close();
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }

    $conn->close();
}
?>