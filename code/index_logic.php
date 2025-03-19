<?php
require_once "connect.php";

// Dotaz na kategorie
$categoryQuery = "SELECT id, nazev FROM kategorie";
$categoryStmt = $conn->prepare($categoryQuery);
if ($categoryStmt) {
    $categoryStmt->execute();
    $categoryResult = $categoryStmt->get_result();
    $categoryStmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}

// Dotaz na třídy
$gradeQuery = "SELECT DISTINCT trida_id FROM ucebnice ORDER BY trida_id";
$gradeStmt = $conn->prepare($gradeQuery);
if ($gradeStmt) {
    $gradeStmt->execute();
    $gradeResult = $gradeStmt->get_result();
    $gradeStmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}

// Hlavní dotaz na učebnice
$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, 
COUNT(CASE WHEN pu.koupil = 0 THEN 1 END) AS pocet_ks, 
ROUND(AVG(CASE WHEN pu.koupil = 0 THEN pu.cena END)) AS avg_cena 
FROM ucebnice 
INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id 
INNER JOIN typ ON ucebnice.typ_id=typ.id 
INNER JOIN pu ON ucebnice.id=pu.id_ucebnice 
GROUP BY ucebnice.id
ORDER BY pocet_ks DESC";
$mainStmt = $conn->prepare($query);
if ($mainStmt) {
    $mainStmt->execute();
    $result = $mainStmt->get_result();
    $mainStmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}
?>