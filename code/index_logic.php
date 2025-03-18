<?php
$categoryQuery = "SELECT id, nazev FROM kategorie";
$categoryResult = $conn->query($categoryQuery);

$gradeQuery = "SELECT DISTINCT trida_id FROM ucebnice ORDER BY trida_id";
$gradeResult = $conn->query($gradeQuery);

$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, 
COUNT(CASE WHEN pu.koupil = 0 THEN 1 END) AS pocet_ks, 
ROUND(AVG(CASE WHEN pu.koupil = 0 THEN pu.cena END)) AS avg_cena 
FROM ucebnice 
INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id 
INNER JOIN typ ON ucebnice.typ_id=typ.id 
INNER JOIN pu ON ucebnice.id=pu.id_ucebnice 
GROUP BY ucebnice.id
ORDER BY pocet_ks DESC";

$result = $conn->query($query);

?>