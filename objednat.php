<?php
session_start();
include("connect.php");
include("userinfo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['objednat']) && is_numeric($_POST['objednat'])) {
        $puID = intval($_POST['objednat']);
        $cas = time();

        if (!isset($userId) || $userId <= 0) {
            echo "Chyba: Neplatný uživatel.";
            exit;
        }

        // Spustíme transakci
        $conn->begin_transaction();

        // První dotaz - aktualizace tabulky `pu`
        $sql = "UPDATE pu SET koupil = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ii", $userId, $puID);
            if (!$stmt->execute()) {
                $conn->rollback();
                echo "Chyba při aktualizaci objednávky: " . $stmt->error;
                exit;
            }
            $stmt->close();
        } else {
            echo "Chyba při přípravě prvního dotazu: " . $conn->error;
            exit;
        }

        // Druhý dotaz - vložení do tabulky `orders`
        $sql_orders = "INSERT INTO orders (puID, cas) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql_orders)) {
            $stmt->bind_param("ii", $puID, $cas);
            if (!$stmt->execute()) {
                $conn->rollback();
                echo "Chyba při vkládání do orders: " . $stmt->error;
                exit;
            }
            $stmt->close();
        } else {
            echo "Chyba při přípravě druhého dotazu: " . $conn->error;
            exit;
        }

        // Pokud vše proběhlo v pořádku, potvrdíme změny
        $conn->commit();
        echo "Objednávka byla úspěšně zpracována!";
    } else {
        echo "Chyba: Neplatné ID objednávky.";
    }
} else {
    echo "Chyba: Neplatná metoda požadavku.";
}

$conn->close();
?>
