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

    $sql = "INSERT INTO pu (id_ucebnice, id_prodejce, rok_tisku, stav, cena, koupil, poznamky) 
            VALUES ($id_ucebnice, $id_prodejce, $rok_tisku, $stav, $cena, 0, '$poznamky')";
    
    if ($conn->query($sql) === TRUE) {
        $puID = $conn->insert_id; // Získání ID nově přidaného záznamu

        // Vytvoření složky pro fotky
        $targetDir = "foto/pu/$puID/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Zpracování nahraných fotek
        if (!empty($_FILES['fotky']) && isset($_FILES['fotky']['tmp_name']) && is_array($_FILES['fotky']['tmp_name'])) {
            foreach ($_FILES['fotky']['tmp_name'] as $key => $tmp_name) {
                if (!empty($tmp_name)) { // Kontrola, zda soubor existuje
                    $fileName = pathinfo($_FILES['fotky']['name'][$key], PATHINFO_FILENAME); // Název souboru bez přípony
                    $targetDir = "E:/Other/XAMPP/htdocs/burza/foto/pu/$puID/"; // Cesta ke složce
                    $targetFilePath = $targetDir . $fileName . ".webp"; // Výstupní cesta
        
                    // 🔹 Ověření, že složka existuje, jinak ji vytvoříme
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
        
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
                    echo "❌ Soubor neexistuje nebo je prázdný.<br>";
                }
            }
        } else {
            echo "❌ Žádné soubory k nahrání.<br>";
        }
        

        echo "Učebnice byla úspěšně přidána!";
    } else {
        echo "Chyba: " . $conn->error;
    }
}
?>