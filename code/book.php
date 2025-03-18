<?php
if (isset($_GET['knihaID'])) {
    $knihaID = $_GET['knihaID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
    exit;
}

if (isset($_GET['updateKoupil'])) {
    $id = intval($_GET['updateKoupil']); // Proti SQL injection

    $conn_update = $conn->prepare("UPDATE pu SET koupil = ? WHERE id = ? AND koupil = 0");
    $conn_update->bind_param("ii", $userId, $id); //i = integer, proti injection
    $conn_update->execute();
    
    if ($conn_update->affected_rows > 0) {
        echo "Povedlo se";
    } else {
        echo "Nepodařilo se aktualizovat záznam. Možná již byl zakoupen nebo kniha neexistuje.";
    }
    
    $conn_update->close();
    exit; 
}

$query ="SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev
    FROM ucebnice
    INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
    INNER JOIN typ ON ucebnice.typ_id=typ.id
    INNER JOIN pu ON ucebnice.id=pu.id_ucebnice
    WHERE ucebnice.id = ?
    GROUP BY ucebnice.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $knihaID);
$stmt->execute();
$result = $stmt->get_result();

$prodavaneUcebniceQuery = 
"   SELECT ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil, pu.id_prodejce, pu.id, user.user AS prodejce, user.id AS prodejce_id
    FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN user ON pu.id_prodejce = user.id
    WHERE pu.id_ucebnice = $knihaID AND pu.koupil = 0
";
$prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);

require_once "../code/order_kniha.php";

$selfbook = isset($_GET['selfbook']) ? intval($_GET['selfbook']) : 1;

?>