<?php
session_start();
require_once "connect.php";
require_once "userinfo.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pridat'])) {
    $id_ucebnice = intval($_POST['id_ucebnice']);
    $stav = intval($_POST['stav']);
    $rok_tisku = intval($_POST['rok_tisku']);
    $cena = intval($_POST['cena']);
    $poznamky = $conn->real_escape_string($_POST['poznamky']);

    // ID přihlášeného uživatele jako prodejce (přidat kontrolu přihlášení)
    $id_prodejce = $userId; 
    $stmt = $conn->prepare("INSERT INTO pu (id_ucebnice, id_prodejce, rok_tisku, stav, cena, koupil, poznamky) 
                            VALUES (?, ?, ?, ?, ?, 0, ?)");
    $stmt->bind_param("iiiiis", $id_ucebnice, $id_prodejce, $rok_tisku, $stav, $cena, $poznamky);
    $stmt->execute();
    $puID = $stmt->insert_id; // Získání ID nově přidaného záznamu
    $stmt->close();

    // Vytvoření složky pro fotky
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/burza/foto/pu/$puID/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Zpracování nahraných fotek
    if (!empty($_FILES['fotky']) && isset($_FILES['fotky']['tmp_name']) && is_array($_FILES['fotky']['tmp_name'])) {
        foreach ($_FILES['fotky']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) { // Kontrola, zda soubor existuje
                $fileName = pathinfo($_FILES['fotky']['name'][$key], PATHINFO_FILENAME); // Název souboru bez přípony
                $targetFilePath = $targetDir . $fileName . ".webp"; // Výstupní cesta

                try {
                    $image = new Imagick($tmp_name);
                    $image->setImageFormat('webp');  // Nastavení formátu WebP
                    $image->setImageCompressionQuality(80); // Nastavení kvality
                    $image->writeImage($targetFilePath); // Uložení obrázku
                    $image->clear();
                    $image->destroy();
                    
                    echo "Obrázek převeden: " . $targetFilePath . "<br>"; // Debug výpis
                } catch (Exception $e) {
                    echo "❌ Chyba při konverzi obrázku: " . $e->getMessage();
                }
            } else {
                echo "❌ Fotka neexistuje nebo jste žádnou nevložili.<br>";
            }
        }
    } else {
        echo "❌ Žádné soubory k nahrání.<br>";
    }

    echo "Učebnice byla úspěšně přidána!";
}
?>