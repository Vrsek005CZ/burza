<?php
// Zpracování výběru třídy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trida_id'])) {
    $selectedTrida = intval($_POST['trida_id']);
    
    $updateQuery = "UPDATE user SET trida_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    if ($stmt) {
        $stmt->bind_param("ii", $selectedTrida, $userId);
        if ($stmt->execute()) {
            $user['trida_id'] = $selectedTrida; // Aktualizujeme hodnotu i lokálně
            header("Location: profil.php"); // Přesměrování po uložení
            exit;
        } else {
            echo "Chyba při ukládání třídy: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Chyba při přípravě dotazu: " . $conn->error;
    }
}

// Načtení prodávaných učebnic, definování aliasu
$prodavaneUcebniceQuery = 
"   SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    WHERE pu.id_prodejce = ?";
$stmt = $conn->prepare($prodavaneUcebniceQuery);
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $prodavaneUcebnice = $stmt->get_result();
    $stmt->close();
} else {
    echo "Chyba při přípravě dotazu: " . $conn->error;
}
?>