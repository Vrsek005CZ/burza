<?php
require_once "connect.php";
require_once "userinfo.php";

if (isset($_GET['knihaID'])) {
    $knihaID = intval($_GET['knihaID']); // Proti SQL injection
} else {
    echo("Chyba: Nezadali jste ID knihy.");
    exit;
}

if (isset($_GET['updateKoupil'])) {
    $id = intval($_GET['updateKoupil']); // Proti SQL injection

    $conn_update = $conn->prepare("UPDATE pu SET koupil = ? WHERE id = ? AND koupil = 0");
    if ($conn_update) {
        $conn_update->bind_param("ii", $userId, $id); //i = integer, proti injection
        $conn_update->execute();
        
        if ($conn_update->affected_rows > 0) {
            echo "Povedlo se";
        } else {
            echo "Nepodařilo se aktualizovat záznam. Možná již byl zakoupen nebo kniha neexistuje.";
        }
        
        $conn_update->close();
    } else {
        echo "Chyba při přípravě dotazu: " . $conn->error;
    }
    exit; 
}

$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev
    FROM ucebnice
    INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
    INNER JOIN typ ON ucebnice.typ_id=typ.id
    INNER JOIN pu ON ucebnice.id=pu.id_ucebnice
    WHERE ucebnice.id = ?
    GROUP BY ucebnice.id";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $knihaID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    echo "Chyba při přípravě dotazu: " . $conn->error;
    exit;
}

$prodavaneUcebniceQuery = 
"   SELECT ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil, pu.id_prodejce, pu.id, user.user AS prodejce, user.id AS prodejce_id
    FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN user ON pu.id_prodejce = user.id
    WHERE pu.id_ucebnice = ? AND pu.koupil = 0";
$stmt2 = $conn->prepare($prodavaneUcebniceQuery);
if ($stmt2) {
    $stmt2->bind_param("i", $knihaID);
    $stmt2->execute();
    $prodavaneUcebnice = $stmt2->get_result();
    $stmt2->close();
} else {
    echo "Chyba při přípravě dotazu: " . $conn->error;
    exit;
}

require_once "../code/order_kniha.php";

$selfbook = isset($_GET['selfbook']) ? intval($_GET['selfbook']) : 1;

?>