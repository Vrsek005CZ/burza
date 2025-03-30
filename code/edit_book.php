<?php
require_once "connect.php";
require_once "userinfo.php";

/**
 * Získá detail učebnice podle ID.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $puID ID učebnice.
 * @return array|null Detail učebnice nebo null, pokud není nalezena.
 */
function getBookDetail($conn, $puID) {
    $sql = "SELECT pu.cena AS cena, pu.poznamky AS poznamky, ucebnice.jmeno AS nazev, pu.id_prodejce AS prodejce
            FROM pu 
            INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id 
            WHERE pu.id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $puID);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();
        return $book;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Získá seznam existujících obrázků pro učebnici.
 *
 * @param int $puID ID učebnice.
 * @return array Seznam cest k obrázkům.
 */
function getExistingImages($puID) {
    $dir = "../foto/pu/$puID";
    $existingImages = glob("$dir/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
    return $existingImages;
}

/**
 * Aktualizuje učebnici v databázi.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $puID ID učebnice.
 * @param int $cena Cena učebnice.
 * @param string $poznamky Poznámky k učebnici.
 */
function updateBook($conn, $puID, $cena, $poznamky) {
    $sql = "UPDATE pu SET cena = ?, poznamky = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isi", $cena, $poznamky, $puID);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Chyba při aktualizaci učebnice: " . $conn->error);
    }
}

/**
 * Smaže učebnici z databáze a její obrázky.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $puID ID učebnice.
 */
function deleteBook($conn, $puID) {
    $dir = "../foto/pu/$puID";
    array_map('unlink', glob("$dir/*"));
    rmdir($dir);

    $sql = "DELETE FROM pu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $puID);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Chyba při mazání učebnice: " . $conn->error);
    }
}

/**
 * Nahraje nové obrázky a konvertuje je do formátu WebP pomocí Imagick.
 *
 * @param int $puID ID učebnice.
 * @param array $files Pole souborů.
 */
function uploadNewImages($puID, $files) {
    $dir = realpath("../foto/pu/$puID");
    if (!$dir) {
        if (!mkdir("../foto/pu/$puID", 0777, true)) {
            die("❌ Nelze vytvořit složku: ../foto/pu/$puID");
        }
        $dir = realpath("../foto/pu/$puID");
    }

    echo "✅ Složka existuje nebo byla vytvořena: $dir<br>";

    foreach ($files['tmp_name'] as $key => $tmpName) {
        if (!empty($tmpName) && is_uploaded_file($tmpName)) {
            $fileName = pathinfo($files['name'][$key], PATHINFO_FILENAME);
            $targetFilePath = "$dir/$fileName.webp";

            try {
                $image = new Imagick($tmpName);
                echo "Obrázek načten: " . $files['name'][$key] . "<br>";
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality(80);
                $image->writeImage($targetFilePath);
                echo "✅ Obrázek uložen: $targetFilePath<br>";
                $image->clear();
                $image->destroy();
            } catch (Exception $e) {
                echo "❌ Chyba při zpracování obrázku: " . $e->getMessage() . "<br>";
                echo "Cílová cesta: $targetFilePath<br>";
            }
        }
    }
}

if (!isset($_GET['puID'])) {
    die("Chybějící ID učebnice.");
}

$puID = intval($_GET['puID']);
$pu = getBookDetail($conn, $puID);

if (!$pu) {
    die("Učebnice nenalezena.");
}
if ($userId != $pu['prodejce']) {
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
}

$existingImages = getExistingImages($puID);
?>