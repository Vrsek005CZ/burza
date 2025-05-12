<?php
require_once "connect.php";

/**
 * Načte seznam učebnic a jejich kategorií.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Seznam učebnic s kategoriemi.
 */
function getUcebniceSeznam($conn) {
    $query = "SELECT ucebnice.id, ucebnice.jmeno, kategorie.nazev AS kategorie 
              FROM ucebnice
              JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
              ORDER BY kategorie.nazev, ucebnice.jmeno";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá aktuální rok.
 *
 * @return int Aktuální rok.
 */
function getCurrentYear() {
    return date("Y");
}

/**
 * Přidá novou knihu do databáze a zpracuje nahrané fotky.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param array $data Data z formuláře.
 * @param array $files Nahrané soubory.
 * @param int $userId ID přihlášeného uživatele.
 * @return array Pole se zprávou a ID nové učebnice.
 */
function pridatKnihu($conn, $data, $files, $userId) {
    $id_ucebnice = intval($data['id_ucebnice']);
    $stav = intval($data['stav']);
    $rok_tisku = intval($data['rok_tisku']);
    $cena = intval($data['cena']);
    $poznamky = $conn->real_escape_string($data['poznamky']);

    // Přidání knihy do tabulky `pu`
    $stmt = $conn->prepare("INSERT INTO pu (id_ucebnice, id_prodejce, rok_tisku, stav, cena, koupil, poznamky) 
                            VALUES (?, ?, ?, ?, ?, 0, ?)");
    if ($stmt) {
        $stmt->bind_param("iiiiis", $id_ucebnice, $userId, $rok_tisku, $stav, $cena, $poznamky);
        $stmt->execute();
        $puID = $stmt->insert_id; // Získání ID nově přidaného záznamu
        $stmt->close();
    } else {
        return [
            "msg" => "❌ Chyba při přípravě dotazu: " . $conn->error,
            "error" => true
        ];
    }


    // Vytvoření složky pro fotky
    $targetDir = realpath("../foto/pu/$puID");
    if (!$targetDir) {
        if (!mkdir("../foto/pu/$puID", 0777, true)) {
            die("❌ Nelze vytvořit složku: ../foto/pu/$puID");
        }
        $targetDir = realpath("../foto/pu/$puID");
    }
    $targetDir .= '/'; // Ujistíme se, že cesta končí lomítkem

    // Zpracování nahraných fotek
    if (!empty($files['fotky']) && isset($files['fotky']['tmp_name']) && is_array($files['fotky']['tmp_name'])) {
        foreach ($files['fotky']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) { // Kontrola, zda soubor existuje
                $fileName = pathinfo($files['fotky']['name'][$key], PATHINFO_FILENAME); // Název souboru bez přípony
                $targetFilePath = $targetDir . $fileName . ".webp"; // Výstupní cesta

                try {
                    $image = new Imagick($tmp_name);
                    // Automatická orientace podle EXIF
                    if ($image->getImageOrientation() != Imagick::ORIENTATION_UNDEFINED) {
                        $image->autoOrient();
                    }
                    $image->setImageFormat('webp');  // Nastavení formátu WebP
                    $image->setImageCompressionQuality(2); // Nastavení kvality
                    $image->writeImage($targetFilePath); // Uložení obrázku
                    $image->clear();
                    $image->destroy();
                } catch (Exception $e) {
                    return [
                        "msg" => "❌ Chyba při konverzi obrázku: " . $e->getMessage(),
                        "error" => true
                    ];
                }
            }
        }
    }

    // Vracíme pole se zprávou a ID nové učebnice
    return [
        "msg" => "Učebnice byla úspěšně přidána!",
        "puID" => $puID,
        "error" => false
    ];
}
?>