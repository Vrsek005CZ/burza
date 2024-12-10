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

$prodavaneUcebniceQuery = 
"   SELECT nu.nazev AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil, pu.id FROM pu
    JOIN nu ON pu.id_ucebnice = nu.id
    WHERE pu.id_ucebnice = $knihaID && pu.koupil = 0
";
$prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function potvrdit(id) {
            document.getElementById(`reserveButton-${id}`).classList.add('hidden');
            document.getElementById(`confirmButton-${id}`).classList.remove('hidden');
        }

        function rezervovat(id) {
            document.getElementById(`confirmButton-${id}`).classList.add('hidden');
            document.getElementById(`messageButton-${id}`).classList.remove('hidden');
        }

    </script>
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

        <?php $row = $result->fetch_assoc(); ?>
        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto grid gap-4 p-4 grid-cols-4">
          <img src="foto/ucebnice/<?php echo htmlspecialchars($row['foto'])?>" class="rounded-lg p-1 h-48 object-cover justify-self-center">
          <div class="col-span-3">
              <div class="text-lg font-bold"><?php echo htmlspecialchars($row['ucebnice_nazev']); ?></div>
              <div class="text-sm text-slate-700">
                  <div><?php echo htmlspecialchars($row['typ_nazev'])?></div>
                  <div><?php echo htmlspecialchars($row['kategorie_nazev'])?></div>
                  <div>vhodné pro <?php echo htmlspecialchars($row['trida_id'])?>. ročník</div>
            </div>
            <div> <br>
                <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">Prodávané učebnice</h2>
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-4 text-left">Stav</th>
                            <th class="p-4 text-left">Rok tisku</th>
                            <th class="p-4 text-left">Cena</th>
                            <th class="p-4 text-left">Poznámky</th>
                            <th class="p-4 text-left">Koupit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ucebnice = $prodavaneUcebnice->fetch_assoc()): ?>
                            <tr class="border-t-2">
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['stav']); ?>/10</td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['rok_tisku']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['cena']); ?> Kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['poznamky']); ?></td>
                                <td class="p-4 text-green-600 font-semibold">
                                    <button id="reserveButton-<?php echo htmlspecialchars($ucebnice['id']); ?>" onclick="potvrdit(<?php echo htmlspecialchars($ucebnice['id']); ?>)" class="p-3 bg-green-100 hover:bg-green-200 rounded-md w-29">Rezervovat</button>
                                    <button id="confirmButton-<?php echo htmlspecialchars($ucebnice['id']); ?>" onclick="rezervovat(<?php echo htmlspecialchars($ucebnice['id']); ?>)" class="p-3 px-6 bg-green-100 hidden hover:bg-green-200 rounded-md w-29">Potvrdit</button>
                                    <button id="messageButton-<?php echo htmlspecialchars($ucebnice['id']); ?>" onclick="message(<?php echo htmlspecialchars($ucebnice['id']); ?>)" class="p-3 px-6 bg-red-100 hidden text-red-600 hover:bg-red-200 rounded-md w-29">Hotovo</button>
                                </td>  
                                </td>  
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        


        


        

</body>
</html>