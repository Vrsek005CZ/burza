<?php
session_start();
include("connect.php");
            
if (isset($_GET['knihaID'])) {
    $knihaID = $_GET['knihaID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}

$query ="SELECT ucebnice.id, nu.nazev AS ucebnice_nazev, ucebnice.foto, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev
    FROM ucebnice
    INNER JOIN nu ON ucebnice.jmeno_id=nu.id
    INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
    INNER JOIN typ ON ucebnice.typ_id=typ.id
    INNER JOIN pu ON ucebnice.id=pu.id_ucebnice
    WHERE ucebnice.id = $knihaID
    GROUP BY ucebnice.id

";

$result = $conn->query($query); 

/*$query ="SELECT ucebnice.id, nu.nazev AS ucebnice_nazev, ucebnice.foto, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, COUNT(pu.id) AS pocet_ks, ROUND(AVG(pu.cena)) AS avg_cena FROM ucebnice
INNER JOIN nu ON ucebnice.jmeno_id=nu.id
INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
INNER JOIN typ ON ucebnice.typ_id=typ.id
INNER JOIN pu ON ucebnice.id=pu.id_ucebnice
GROUP BY ucebnice.id
";

    $result = $conn->query($query); */



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Kniha</title>
</head>

<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">

    <div class="w-full max-w-5xl">
        <!-- Záhlaví -->
        <div class="flex items-center justify-between bg-white shadow-md p-5 rounded-md">
            <!-- Nadpis -->
            <h1 class="text-3xl font-bold text-center flex-1 text-gray-800">
                <a href="index.php" class="">Online Burza Učebnic</a>
            </h1>
        </div>
        <br> 

        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
            Matematika pro 9.r.ZŠ,1.d.-Odvárko,Kadleček
           <img src="foto/ucebnice/41653.jpg" class="rounded-lg p-1 h-48 object-cover">
            
        </div>

        <br>









        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
            <?php
            $row = $result->fetch_assoc(); 

            echo '<img src="foto/ucebnice/' . htmlspecialchars($row['foto']) . '" class="rounded-lg p-1 h-48 object-cover">';
            

            echo htmlspecialchars($row['ucebnice_nazev']);
            ?>
        </div>


        


        

</body>
</html>