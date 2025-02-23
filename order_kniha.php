<?php
$knihaID = $_GET['knihaID'];


if (isset($_GET['order']) && isset($_GET['sort'])) {
    $order = $_GET['order'];
    $sort = $_GET['sort'];

    if ($order == "prodejce"){
        $orderBy = "ORDER BY user.user ". strtoupper($sort);
    }

    else {
        $orderBy = "ORDER BY pu." . $order . " " . strtoupper($sort);   
    }

    global $prodavaneUcebniceQuery;
    $prodavaneUcebniceQuery .= "$orderBy";
    $prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);

}

?>