<?php
session_start();
require_once "connect.php";
require_once "userinfo.php";

if (!isset($_GET['puID'])) {
    die("Chybějící ID učebnice.");
}

$puID = intval($_GET['puID']);

// Načteme nabídku z DB
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
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Zpracování POST požadavků
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Aktualizace nabídky (tlačítko "Upravit")
    if (isset($_POST['update'])) {
        $cena = intval($_POST['cena']);
        $poznamky = trim($_POST['poznamky']);

        $updateSql = "UPDATE pu SET cena = ?, poznamky = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("isi", $cena, $poznamky, $puID);
        $updateStmt->execute();
        $updateStmt->close();

        // Odstranění existujících fotek
        if (!empty($_POST['removedImages'])) {
            $removedImages = json_decode($_POST['removedImages'], true);
            if (is_array($removedImages)) {
                foreach ($removedImages as $file) {
                    $filePath = "$dir/" . basename($file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }

        // Zpracování nových fotek – konverze do WebP pomocí Imagick
        if (!empty($_FILES['newFiles']['name'][0])) {
            // Sestavíme absolutní cestu pomocí __DIR__
            $absoluteDir = __DIR__ . "/$dir/";
            if (!is_dir($absoluteDir)) {
                mkdir($absoluteDir, 0777, true);
            }
            foreach ($_FILES['newFiles']['tmp_name'] as $key => $tmpName) {
                if (!empty($tmpName)) {
                    // Název souboru bez přípony
                    $fileName = pathinfo($_FILES['newFiles']['name'][$key], PATHINFO_FILENAME);
                    // Cílová cesta – výsledný soubor bude s příponou .webp
                    $targetFilePath = $absoluteDir . $fileName . ".webp";
                    try {
                        $image = new Imagick($tmpName);
                        $image->setImageFormat('webp');
                        $image->setImageCompressionQuality(80);
                        $image->writeImage($targetFilePath);
                        $image->clear();
                        $image->destroy();
                    } catch (Exception $e) {
                        echo "❌ Chyba při konverzi obrázku: " . $e->getMessage();
                    }
                } else {
                    echo "❌ Soubor neexistuje nebo je prázdný.<br>";
                }
            }
        }
        header("Location: ../pages/profil.php");
        exit;
    }
    
    // Smazání nabídky (tlačítko "Smazat")
    if (isset($_POST['delete'])) {
        array_map('unlink', glob("$dir/*"));
        rmdir($dir);
        $deleteSql = "DELETE FROM pu WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $puID);
        $deleteStmt->execute();
        $deleteStmt->close();

        header("Location: ../pages/profil.php");
        exit;
    }
}
?>
