<?php
require_once "userinfo.php";

$knihaID = intval($_GET['knihaID']);

global $prodavaneUcebniceQuery;

if (isset($_GET['selfbook'])) {
    $selfbook = intval($_GET['selfbook']);
} else {
    $selfbook = 1;
}

if ($selfbook == 1) {
    $selfbooksql = " AND pu.id_prodejce != ?";
    $prodavaneUcebniceQuery .= $selfbooksql;
}

if (isset($_GET['order']) && isset($_GET['sort'])) {
    $order = $_GET['order'];
    $sort = $_GET['sort'];

    if ($order == "prodejce") {
        $orderBy = "ORDER BY user.user " . strtoupper($sort);
    } else {
        $orderBy = "ORDER BY pu." . $order . " " . strtoupper($sort);
    }

    $prodavaneUcebniceQuery .= " " . $orderBy;
}

$stmt = $conn->prepare($prodavaneUcebniceQuery);
if ($stmt) {
    if ($selfbook == 1) {
        $stmt->bind_param("ii", $knihaID, $userId);
    } else {
        $stmt->bind_param("i", $knihaID);
    }
    $stmt->execute();
    $prodavaneUcebnice = $stmt->get_result();
    $stmt->close();
} else {
    die("Chyba při přípravě dotazu: " . $conn->error);
}
?>