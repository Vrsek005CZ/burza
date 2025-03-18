<?php
// Získání seznamu učebnic
$sqlUcebnice = "SELECT id, jmeno FROM ucebnice";
$resultUcebnice = $conn->query($sqlUcebnice);

// Získání seznamu kategorií
$sqlKategorie = "SELECT id, nazev FROM kategorie";
$resultKategorie = $conn->query($sqlKategorie);

// Získání seznamu typů
$sqlTyp = "SELECT id, nazev FROM typ";
$resultTyp = $conn->query($sqlTyp);
?>