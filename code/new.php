<?php
require_once "connect.php";

// Získání seznamu učebnic
$sqlUcebnice = "SELECT id, jmeno FROM ucebnice";
$stmtUcebnice = $conn->prepare($sqlUcebnice);
if ($stmtUcebnice) {
    $stmtUcebnice->execute();
    $resultUcebnice = $stmtUcebnice->get_result();
    $stmtUcebnice->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}

// Získání seznamu kategorií
$sqlKategorie = "SELECT id, nazev FROM kategorie";
$stmtKategorie = $conn->prepare($sqlKategorie);
if ($stmtKategorie) {
    $stmtKategorie->execute();
    $resultKategorie = $stmtKategorie->get_result();
    $stmtKategorie->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}

// Získání seznamu typů
$sqlTyp = "SELECT id, nazev FROM typ";
$stmtTyp = $conn->prepare($sqlTyp);
if ($stmtTyp) {
    $stmtTyp->execute();
    $resultTyp = $stmtTyp->get_result();
    $stmtTyp->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}
?>