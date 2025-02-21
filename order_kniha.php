<?php
$knihaID = $_GET['knihaID'];


if (isset($_GET['order']) && isset($_GET['sort'])) {
    $order = $_GET['order'];
    $sort = strtoupper($_GET['sort']);

    if ($order == "prodejce"){
        $orderBy = "ORDER BY user.user ". $sort;
    }

    else {
        $orderBy = "ORDER BY pu." . $order . " " . $sort;   
    }

    global $prodavaneUcebniceQuery;
    $prodavaneUcebniceQuery .= "$orderBy";
    $prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);

}

?>