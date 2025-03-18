<?php
session_start();
require_once "is_admin.php";
require_once "connect.php";

// Kontrola povinných parametrů
$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$table || !$id) {
    die("Neplatný požadavek.");
}

// Ověříme, že tabulka existuje
$tables = [];
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    $tables[] = $row[0];
}
if (!in_array($table, $tables)) {
    die("Neplatná tabulka.");
}

// Načtení záznamu
$query = "SELECT * FROM `$table` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$rowData = $result->fetch_assoc();
if (!$rowData) {
    die("Záznam nenalezen.");
}

// Zpracování formuláře pro update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    // Aktualizace textových hodnot (všech sloupců kromě id)
    $updateQuery = "UPDATE `$table` SET ";
    $params = [];
    $types = '';

    foreach ($rowData as $column => $value) {
        if ($column !== 'id') {
            $updateQuery .= "$column = ?, ";
            $params[] = $_POST[$column];
            $types .= 's';
        }
    }

    $updateQuery = rtrim($updateQuery, ', ') . " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "Záznam byl úspěšně aktualizován.";
    } else {
        $message = "Nebyly provedeny žádné změny.";
    }

    // Správa fotek pro tabulky ucebnice a pu
    if ($table == "ucebnice") {
        // Cesta ke stávající fotce
        $photoPath = "../foto/ucebnice/$id.webp";
    
        // Pokud je zatrženo odstranění fotky, smažeme ji, pokud existuje
        if (isset($_POST['removePhoto'])) {
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
    
        // Zpracování nahrané nové fotky
        if (isset($_FILES['newPhoto']) && $_FILES['newPhoto']['error'] == UPLOAD_ERR_OK) {
            $tmpName = $_FILES['newPhoto']['tmp_name'];
            try {
                // Pokusíme se o konverzi pomocí Imagick do formátu WebP
                $image = new Imagick($tmpName);
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality(90);
                $image->writeImage($photoPath);
                $image->clear();
                $image->destroy();
            } catch (Exception $e) {
                // V případě chyby provedeme klasické přesunutí souboru
                move_uploaded_file($tmpName, $photoPath);
            }
        }
        
    } elseif ($table == "pu") {
        $dir = "../foto/pu/$id";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        // Odstranění vybraných fotek
        if (!empty($_POST['removedImages'])) {
            $removedImages = json_decode($_POST['removedImages'], true);
            if (is_array($removedImages)) {
                foreach ($removedImages as $filename) {
                    $filePath = "$dir/" . basename($filename);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        // Zpracování nahraných nových fotek
        if (!empty($_FILES['newFiles']['name'][0])) {
            foreach ($_FILES['newFiles']['tmp_name'] as $key => $tmpName) {
                if (!empty($tmpName)) {
                    $newName = uniqid() . ".webp";
                    $targetPath = "$dir/$newName";
                    try {
                        $image = new Imagick($tmpName);
                        $image->setImageFormat('webp');
                        $image->setImageCompressionQuality(80);
                        $image->writeImage($targetPath);
                        $image->clear();
                        $image->destroy();
                    } catch (Exception $e) {
                        // Pokud konverze selže, provedeme klasické přesunutí souboru
                        move_uploaded_file($tmpName, $targetPath);
                    }
                }
            }
        }
    }
    // Znovunačtení záznamu po aktualizaci
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rowData = $result->fetch_assoc();
}
?>