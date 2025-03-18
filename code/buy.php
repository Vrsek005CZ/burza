<?php
session_start();
require_once "connect.php";
require_once "userinfo.php";

if (isset($_GET['puID'])) {
    $puID = $_GET['puID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
    exit;
}

$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, user.user AS prodejce, pu.poznamky, user.id AS prodejce_id, pu.koupil as koupil
    FROM pu
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
    INNER JOIN typ ON ucebnice.typ_id = typ.id
    INNER JOIN user ON pu.id_prodejce = user.id
    WHERE pu.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $puID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Učebnice nenalezena.";
    exit;
}

$cesta = "../foto/pu/$puID/";
$files = glob($cesta . "*.webp"); // vrátí všechny hodnoty v uvedené cestě, které končí na .webp
$files_json = json_encode($files); // poslaní do js

$cesta = "../foto/pu/$puID/";
        $files = glob($cesta . "*.webp"); //vrátí všechny hodnoty v uvedené cestě, které končí na .webp
        $files_json = json_encode($files); // poslaní do js
        
?>