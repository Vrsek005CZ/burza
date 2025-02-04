<?php
session_start();
include("connect.php");
include("userinfo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pridat'])) {
    $id_ucebnice = intval($_POST['id_ucebnice']);
    $stav = intval($_POST['stav']);
    $rok_tisku = intval($_POST['rok_tisku']);
    $cena = intval($_POST['cena']);
    $poznamky = $conn->real_escape_string($_POST['poznamky']);

    // ID přihlášeného uživatele jako prodejce (přidat kontrolu přihlášení)
    $id_prodejce = $userId; // 4 = výchozí hodnota, pokud není přihlášen

    $sql = "INSERT INTO pu (id_ucebnice, id_prodejce, rok_tisku, stav, cena, koupil, foto_slozka, poznamky) 
            VALUES ($id_ucebnice, $id_prodejce, $rok_tisku, $stav, $cena, 0, '', '$poznamky')";
    
    if ($conn->query($sql)) {
        echo "<script>alert('Učebnice byla úspěšně přidána!'); window.location.href = 'prodat.php';</script>";
    } else {
        echo "<script>alert('Chyba: " . $conn->error . "'); window.location.href = 'prodat.php';</script>";
    }
}

$conn->close();
?>
