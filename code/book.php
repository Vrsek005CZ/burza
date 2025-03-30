<?php
require_once "connect.php";
require_once "userinfo.php";

/**
 * Načte informace o konkrétní knize.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $knihaID ID knihy.
 * @return array|null Informace o knize nebo null, pokud nebyla nalezena.
 */
function getKnihaInfo($conn, $knihaID) {
    $query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev
              FROM ucebnice
              INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
              INNER JOIN typ ON ucebnice.typ_id=typ.id
              WHERE ucebnice.id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $knihaID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data ?: null; // Vrátí null, pokud není nalezeno
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

/**
 * Načte seznam prodávaných učebnic pro konkrétní knihu s možností řazení.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param int $knihaID ID knihy.
 * @param string|null $order Sloupec, podle kterého se má řadit.
 * @param string|null $sort Směr řazení (asc nebo desc).
 * @param int $selfbook Zda vyloučit vlastní knihy (1 = ano, 0 = ne).
 * @return array Seznam prodávaných učebnic.
 */
function getProdavaneUcebnice($conn, $knihaID, $order = null, $sort = 'asc', $selfbook = 1, $userId) {
    // Základní SQL dotaz
    $query = "SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil, pu.id_prodejce, user.user AS prodejce, user.id AS prodejce_id
              FROM pu
              JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
              INNER JOIN user ON pu.id_prodejce = user.id
              WHERE pu.id_ucebnice = ? AND pu.koupil = 0 ";

    // Přidání podmínky pro vyloučení vlastních knih
    if ($selfbook == 1) {
        $query .= " AND pu.id_prodejce != ?";
    }

    // Přidání řazení
    $allowedColumns = ['stav', 'rok_tisku', 'cena', 'prodejce'];
    if ($order && in_array($order, $allowedColumns)) {
        $sort = strtolower($sort) === 'desc' ? 'DESC' : 'ASC';
        if ($order === 'prodejce') {
            $query .= " ORDER BY user.user $sort";
        } else {
            $query .= " ORDER BY pu.$order $sort";
        }
    }


    $stmt = $conn->prepare($query);
    if ($stmt) {
        // Přiřazení parametrů
        if ($selfbook == 1) {
            $stmt->bind_param("ii", $knihaID, $userId);
        } else {
            $stmt->bind_param("i", $knihaID);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $data;
    } else {
        die("Chyba při přípravě dotazu: " . $conn->error);
    }
}

if (isset($_GET['knihaID'])) {
    $knihaID = intval($_GET['knihaID']); // Proti SQL injection
} else {
    echo("Chyba: Nezadali jste ID knihy.");
    exit;
}

if (isset($_GET['updateKoupil'])) {
    $id = intval($_GET['updateKoupil']); // Proti SQL injection

    $conn_update = $conn->prepare("UPDATE pu SET koupil = ? WHERE id = ? AND koupil = 0");
    if ($conn_update) {
        $conn_update->bind_param("ii", $userId, $id); //i = integer, proti injection
        $conn_update->execute();
        
        if ($conn_update->affected_rows > 0) {
            echo "Povedlo se";
        } else {
            echo "Nepodařilo se aktualizovat záznam. Možná již byl zakoupen nebo kniha neexistuje.";
        }
        
        $conn_update->close();
    } else {
        echo "Chyba při přípravě dotazu: " . $conn->error;
    }
    exit; 
}

$knihaInfo = getKnihaInfo($conn, $knihaID);
if (!$knihaInfo) {
    echo "Kniha nebyla nalezena.";
    exit;
}

$selfbook = isset($_GET['selfbook']) ? intval($_GET['selfbook']) : 1;
$order = isset($_GET['order']) ? $_GET['order'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc';


?>