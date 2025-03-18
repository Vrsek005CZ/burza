<?php
if (!isset($_GET['puID'])) {
    die("Chybějící ID učebnice.");
}

$puID = intval($_GET['puID']);
$sql = "SELECT pu.cena as cena, pu.poznamky as poznamky, ucebnice.jmeno as nazev, pu.id_prodejce as prodejce
        FROM pu 
        INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id 
        WHERE pu.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $puID);
$stmt->execute();
$result = $stmt->get_result();
$pu = $result->fetch_assoc();

if (!$pu) {
    die("Učebnice nenalezena.");
}
if ($userId != $pu['prodejce']) {
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
}

$dir = "../foto/pu/$puID";
// Získáme existující fotky – včetně souborů s příponou .webp
$existingImages = glob("$dir/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
?>