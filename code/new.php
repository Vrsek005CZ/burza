<?php
require_once "connect.php";

/**
 * Získá seznam učebnic.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return mysqli_result Výsledek dotazu.
 */
function getUcebnice($conn) {
    $sql = "SELECT id, jmeno FROM ucebnice";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá seznam kategorií.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return mysqli_result Výsledek dotazu.
 */
function getKategorie($conn) {
    $sql = "SELECT id, nazev FROM kategorie";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá seznam typů.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return mysqli_result Výsledek dotazu.
 */
function getTypy($conn) {
    $sql = "SELECT id, nazev FROM typ";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Přidá novou učebnici do databáze a zpracuje nahrané fotky.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param array $data Data z formuláře.
 * @param array $files Nahrané soubory.
 * @return string Zpráva o výsledku operace.
 */
function pridatUcebnici($conn, $data, $files) {
    $nazev_ucebnice = $conn->real_escape_string($data['nazev_ucebnice']);
    $kategorie_id = intval($data['kategorie_id']);
    $typ_id = intval($data['typ_id']);
    $trida_id = intval($data['trida_id']);

    $stmt = $conn->prepare("INSERT INTO ucebnice (jmeno, kategorie_id, typ_id, trida_id, schvaleno) VALUES (?, ?, ?, ?, 1)");
    if ($stmt) {
        $stmt->bind_param("siii", $nazev_ucebnice, $kategorie_id, $typ_id, $trida_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $id = $conn->insert_id; // Získání ID nově přidaného záznamu

            // Zpracování nahraných fotek
            if (!empty($files['fotky']) && isset($files['fotky']['tmp_name']) && is_array($files['fotky']['tmp_name'])) {
                foreach ($files['fotky']['tmp_name'] as $key => $tmp_name) {
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
                        } catch (Exception $e) {
                            return "❌ Chyba při konverzi obrázku: " . $e->getMessage();
                        }
                    }
                }
            }

            return "Učebnice byla úspěšně přidána!";
        } else {
            return "Chyba: " . $stmt->error;
        }

        $stmt->close();
    } else {
        return "Chyba: " . $conn->error;
    }
}
?> 