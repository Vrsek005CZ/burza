<?php
require_once "connect.php";

// Kontrola, zda byl zadán profileID
if (isset($_GET['profileID'])) {
    $profileID = intval($_GET['profileID']); // Proti SQL injection
} else {
    die("Chyba: Nezadali jste ID profilu.");
}

// Načtení prodávaných učebnic, definování aliasu
$prodavaneUcebniceStmt = $conn->prepare(
    "SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil 
    FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    WHERE pu.id_prodejce = ?"
);
if ($prodavaneUcebniceStmt) {
    $prodavaneUcebniceStmt->bind_param("i", $profileID);
    $prodavaneUcebniceStmt->execute();
    $prodavaneUcebnice = $prodavaneUcebniceStmt->get_result();
    $prodavaneUcebniceStmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}

// Načtení informací o profilu
$profilStmt = $conn->prepare(
    "SELECT user.user, user.email, user.jmeno, user.prijmeni, user.trida_id
    FROM user
    WHERE user.id = ?"
);
if ($profilStmt) {
    $profilStmt->bind_param("i", $profileID);
    $profilStmt->execute();
    $profil = $profilStmt->get_result();
    $profilStmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}
?>