<?php
session_start();
include("connect.php");
include("userinfo.php");

if (isset($_GET['puID'])) {
    $puID = $_GET['puID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}


$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, 
           ucebnice.trida_id, typ.nazev AS typ_nazev
    FROM pu
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
    INNER JOIN typ ON ucebnice.typ_id = typ.id
    WHERE pu.id = $puID
";


$result = $conn->query($query); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Koupit</title>
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

    <?php $row = $result->fetch_assoc(); ?>
    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto grid gap-4 p-4 grid-cols-4">
        <img src="foto/ucebnice/<?php echo htmlspecialchars($row['id'])?>.jpg" class="rounded-lg p-1 h-48 object-cover justify-self-center bg-gray-300">
        <div class="col-span-3">
            <div class="text-lg font-bold"><?php echo htmlspecialchars($row['ucebnice_nazev']); ?></div>
            <div class="text-sm text-slate-700">
                <div><?php echo htmlspecialchars($row['typ_nazev'])?></div>
                <div><?php echo htmlspecialchars($row['kategorie_nazev'])?></div>
                <div>vhodné pro <?php echo htmlspecialchars($row['trida_id'])?>. ročník</div>
            </div>
        </div>
    <div class='flex gap-1 border border-red-500 '>
    <?php
    $cesta = "foto/pu/$puID/";

    $files = glob($cesta . "*.jpg");

    if ($files) {
        foreach ($files as $file) {
            echo "<img src='$file' class='h-48 aspect-auto border border-blue-500' />";
        }
    } else {
        echo "Nejsou k dispozici žádné fotky.";
    }
    ?>
    </div>
    </div>

<h1>Dodělat obrázky, možnost rozkliknout a zvětšit obrázky, koupit, vypsat informace o danné prodávané učebnici</h1>
</div>

</body>
</html>