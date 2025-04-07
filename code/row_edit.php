<?php
session_start();
require_once "is_admin.php";
require_once "connect.php";

/**
 * Získá seznam všech tabulek v databázi.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Seznam tabulek.
 */
function getTables($conn) {
    $tables = [];
    $result = mysqli_query($conn, "SHOW TABLES");
    while ($row = mysqli_fetch_array($result)) {
        $tables[] = $row[0];
    }
    return $tables;
}

/**
 * Získá záznam z tabulky podle ID.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param string $table Název tabulky.
 * @param int $id ID záznamu.
 * @return array|null Data záznamu nebo null, pokud neexistuje.
 */
function getRowById($conn, $table, $id) {
    $query = "SELECT * FROM `$table` WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Aktualizuje záznam v tabulce.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param string $table Název tabulky.
 * @param array $data Data k aktualizaci.
 * @param int $id ID záznamu.
 * @return string Zpráva o výsledku operace.
 */
function updateRow($conn, $table, $data, $id) {
    $updateQuery = "UPDATE `$table` SET ";
    $params = [];
    $types = '';

    foreach ($data as $column => $value) {
        if ($column !== 'id') {
            $updateQuery .= "`$column` = ?, ";
            $params[] = $value;
            $types .= 's';
        }
    }

    $updateQuery = rtrim($updateQuery, ', ') . " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';

    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return "Záznam byl úspěšně aktualizován.";
    } else {
        return "Nebyly provedeny žádné změny.";
    }
}

/**
 * Získá seznam sloupců tabulky.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param string $table Název tabulky.
 * @return array Seznam sloupců tabulky.
 */
function getTableColumns($conn, $table) {
    $columns = [];
    $query = "SHOW COLUMNS FROM `$table`";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

/**
 * Spravuje fotky pro tabulku `ucebnice`.
 *
 * @param int $id ID záznamu.
 * @param array $files Nahrané soubory.
 * @param bool $removePhoto Indikátor, zda odstranit existující fotku.
 * @return string Zpráva o výsledku operace.
 */
function manageUcebnicePhoto($id, $files, $removePhoto) {
    $photoPath = "../foto/ucebnice/$id.webp";

    // Odstranění existující fotky
    if ($removePhoto && file_exists($photoPath)) {
        unlink($photoPath);
    }

    // Nahrání nové fotky
    if (isset($files['newPhoto']) && $files['newPhoto']['error'] == UPLOAD_ERR_OK) {
        $tmpName = $files['newPhoto']['tmp_name'];
        try {
            $image = new Imagick($tmpName);
            $image->setImageFormat('webp');
            $image->setImageCompressionQuality(90);
            $image->writeImage($photoPath);
            $image->clear();
            $image->destroy();
            return "Nová fotka byla úspěšně nahrána.";
        } catch (Exception $e) {
            move_uploaded_file($tmpName, $photoPath);
            return "Fotka byla nahrána bez konverze.";
        }
    }

    return "Žádná změna fotky.";
}

/**
 * Spravuje fotky pro tabulku `pu`.
 *
 * @param int $id ID záznamu.
 * @param array $files Nahrané soubory.
 * @param array $removedImages Seznam fotek k odstranění.
 * @return string Zpráva o výsledku operace.
 */
function managePuPhotos($id, $files, $removedImages) {
    $dir = "../foto/pu/$id";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Odstranění vybraných fotek
    if (!empty($removedImages)) {
        foreach ($removedImages as $filename) {
            $filePath = "$dir/" . basename($filename);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // Nahrání nových fotek
    if (!empty($files['newFiles']['name'][0])) {
        foreach ($files['newFiles']['tmp_name'] as $key => $tmpName) {
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
                    move_uploaded_file($tmpName, $targetPath);
                }
            }
        }
    }

    return "Fotky byly úspěšně spravovány.";
}
