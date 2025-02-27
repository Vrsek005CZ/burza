<?php
include ("userinfo.php");

$knihaID = $_GET['knihaID'];

global $prodavaneUcebniceQuery;

if (isset($_GET['selfbook'])){
    $selfbook = $_GET['selfbook'];

}else {
    $selfbook = 1;
}
if ($selfbook == 1){
    $selfbooksql = " AND pu.id_prodejce != $userId ";
    $prodavaneUcebniceQuery .= "$selfbooksql";
}
    




if (isset($_GET['order']) && isset($_GET['sort'])) {
    $order = $_GET['order'];
    $sort = $_GET['sort'];

    if ($order == "prodejce"){
        $orderBy = "ORDER BY user.user ". strtoupper($sort);
    }

    else {
        $orderBy = "ORDER BY pu." . $order . " " . strtoupper($sort);   
    }

    $prodavaneUcebniceQuery .= "$orderBy";
}
$prodavaneUcebnice = $conn->query(query: $prodavaneUcebniceQuery);


?>