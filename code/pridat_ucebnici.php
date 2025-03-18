<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pridat'])) {
    $nazev_ucebnice = $conn->real_escape_string($_POST['nazev_ucebnice']);
    $kategorie_id = intval($_POST['kategorie_id']);
    $typ_id = intval($_POST['typ_id']);
    $trida_id = intval($_POST['trida_id']);

    $stmt = $conn->prepare("INSERT INTO ucebnice (jmeno, kategorie_id, typ_id, trida_id, schvaleno) VALUES (?, ?, ?, ?, 1)");
    if ($stmt) {
        $stmt->bind_param("siii", $nazev_ucebnice, $kategorie_id, $typ_id, $trida_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $id = $conn->insert_id; // Získání ID nově přidaného záznamu

            // Zpracování nahraných fotek
            if (!empty($_FILES['fotky']) && isset($_FILES['fotky']['tmp_name']) && is_array($_FILES['fotky']['tmp_name'])) {
                foreach ($_FILES['fotky']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name)) { // Kontrola, zda soubor existuje
                        $fileName = $id; // Název souboru bez přípony
                        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/burza/foto/ucebnice/"; // Cesta ke složce
                        $targetFilePath = $targetDir . $fileName . ".webp"; // Výstupní cesta
            
                        try {
                            $image = new Imagick($tmp_name);
                            $image->setImageFormat('webp');  // Nastavení formátu WebP
                            $image->setImageCompressionQuality(90); // Nastavení kvality
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
            }

            echo "Učebnice byla úspěšně přidána!";
        } else {
            echo "Chyba: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Chyba: " . $conn->error;
    }
} else {
    echo "Chyba: Neplatný požadavek.";
}
?>