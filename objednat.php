<?php
session_start();
include("connect.php");
include("userinfo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získání hodnoty z POST a validace
    if (isset($_POST['objednat']) && is_numeric($_POST['objednat'])) {
        $puID = intval($_POST['objednat']); // ID objednávky
        $cas = time(); // Unix timestamp pro aktuální čas

        // Pokud $userId není nastaveno nebo platné
        if (!isset($userId) || $userId <= 0) {
            echo "Chyba: Neplatný uživatel.";
            exit;
        }

        // Připravený SQL dotaz pro aktualizaci
        $sql = "UPDATE pu SET koupil = ?, cas = ? WHERE id = ?";

        // Příprava a bindování parametrů
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iii", $userId, $cas, $puID);

            // Spuštění dotazu a kontrola, zda byl úspěšný
            if ($stmt->execute()) {
                echo "Objednávka byla úspěšně aktualizována!";
            } else {
                echo "Chyba při provádění dotazu: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Chyba při přípravě dotazu: " . $conn->error;
        }
    } else {
        echo "Chyba: Neplatný ID objednávky.";
    }
} else {
    echo "Chyba: Neplatná metoda požadavku.";
}

$conn->close(); // Zavření spojení s databází
?>
