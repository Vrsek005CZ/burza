<?php
//dochází mi nápady, jak pojmenovávat soubory
if (isset($_GET['profileID'])) {
    $profileID = isset($_GET['profileID']) ? intval($_GET['profileID']) : 0;  // proti sql injekci
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}

// Načtení prodávaných učebnic, definovani aliasu
$prodavaneUcebniceStmt = $conn->prepare(
    "SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil 
    FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    WHERE pu.id_prodejce = ?"
);
$prodavaneUcebniceStmt->bind_param("i", $profileID);
$prodavaneUcebniceStmt->execute();
$prodavaneUcebnice = $prodavaneUcebniceStmt->get_result();

$profilStmt = $conn->prepare(
    "SELECT user.user, user.email, user.jmeno, user.prijmeni, user.trida_id
    FROM user
    WHERE user.id = ?"
);
$profilStmt->bind_param("i", $profileID);
$profilStmt->execute();
$profil = $profilStmt->get_result();

?>