<?php
session_start();
include("connect.php");
include("userinfo.php");
$pageTitle = "Učebnice"; 
include("header.php");
    
if (isset($_GET['knihaID'])) {
    $knihaID = $_GET['knihaID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}

if (isset($_GET['updateKoupil'])) {
    $id = intval($_GET['updateKoupil']); // Proti SQL injection

    $conn_update = $conn->prepare("UPDATE pu SET koupil = ? WHERE id = ? AND koupil = 0");
    $conn_update->bind_param("ii", $userId, $id); //i = integer, proti injection
    $conn_update->execute();
    
    if ($conn_update->affected_rows > 0) {
        echo "Povedlo se";
    } else {
        echo "Nepodařilo se aktualizovat záznam. Možná již byl zakoupen nebo kniha neexistuje.";
    }
    

    $conn_update->close();
    exit; 
}


$query ="SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev
    FROM ucebnice
    INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
    INNER JOIN typ ON ucebnice.typ_id=typ.id
    INNER JOIN pu ON ucebnice.id=pu.id_ucebnice
    WHERE ucebnice.id = ?
    GROUP BY ucebnice.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $knihaID);
$stmt->execute();
$result = $stmt->get_result();

$prodavaneUcebniceQuery = 
"   SELECT ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil, pu.id_prodejce, pu.id, user.user AS prodejce, user.id AS prodejce_id
    FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN user ON pu.id_prodejce = user.id
    WHERE pu.id_ucebnice = $knihaID AND pu.koupil = 0
";
$prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);


include("order_kniha.php");





$selfbook = isset($_GET['selfbook']) ? intval($_GET['selfbook']) : 1;

?>

        <?php $row = $result->fetch_assoc(); ?>
        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto grid gap-4 p-4 grid-cols-4">
          <img src="foto/ucebnice/<?php echo htmlspecialchars($row['id'])?>.webp" class="rounded-lg p-1 w-48 object-cover justify-self-center bg-gray-300">
          <div class="col-span-3">
              <div class="text-lg font-bold"><?php echo htmlspecialchars($row['ucebnice_nazev']); ?></div>
              <div class="text-sm text-slate-700">
                  <div><?php echo htmlspecialchars($row['typ_nazev'])?></div>
                  <div><?php echo htmlspecialchars($row['kategorie_nazev'])?></div>
                  <div>vhodné pro <?php echo htmlspecialchars($row['trida_id'])?>. ročník</div>
            </div>
        </div>
        <div class="col-span-4"> <br>
            <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">Prodávané učebnice</h2>
            <table class="w-full bg-gray-50 shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-4 w-[7%]">Stav</th>
                        <th class="p-4 w-[7%]">Rok tisku</th>
                        <th class="p-4 w-[10%]">Cena</th>
                        <th class="p-4 w-[8%]">Prodejce</th>
                        <th class="p-4 w-[49%]">Poznámky</th>
                        <th class="p-4 w-[19%]">Koupit</th>
                    </tr>
                    <tr class="bg-gray-300 text-left h-[5px] text-gray-600">
                        <th class="p-2 w-[7%]">
                            &nbsp;&nbsp;<a href="?knihaID=<?php echo $knihaID; ?>&order=stav&sort=asc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'stav' && $sort == 'asc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠷</a>&nbsp;
                            <a href="?knihaID=<?php echo $knihaID; ?>&order=stav&sort=desc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'stav' && $sort == 'desc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠵</a>
                        </th>

                        <th class="p-2 w-[7%]">
                            &nbsp;&nbsp;<a href="?knihaID=<?php echo $knihaID; ?>&order=rok_tisku&sort=asc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'rok_tisku' && $sort == 'asc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠷</a>&nbsp;
                            <a href="?knihaID=<?php echo $knihaID; ?>&order=rok_tisku&sort=desc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'rok_tisku' && $sort == 'desc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠵</a>
                        </th>

                        <th class="p-2 w-[10%]">
                            &nbsp;&nbsp;<a href="?knihaID=<?php echo $knihaID; ?>&order=cena&sort=asc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'cena' && $sort == 'asc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠷</a>&nbsp;
                            <a href="?knihaID=<?php echo $knihaID; ?>&order=cena&sort=desc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'cena' && $sort == 'desc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠵</a>
                        </th>

                        <th class="p-2 w-[8%]">
                            &nbsp;&nbsp;<a href="?knihaID=<?php echo $knihaID; ?>&order=prodejce&sort=asc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'prodejce' && $sort == 'asc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠷</a>&nbsp;
                            <a href="?knihaID=<?php echo $knihaID; ?>&order=prodejce&sort=desc&selfbook=<?php echo $selfbook; ?>" 
                                class="<?php echo ($order == 'prodejce' && $sort == 'desc') ? 'text-blue-600' : ''; ?> hover:text-blue-500">🠵</a>
                        </th>

                        <th class="p-2 w-[49%]">
                            <div class="text-right">Zobrazit vlastní knihy:</div>
                        </th>
                        
                        <th class="p-2 w-[19%] ">
                            &nbsp;&nbsp;<button onclick="BookChange()" id ="bookButton" class="hover:cursor-pointer <?php echo ($selfbook !== 1) ? 'text-blue-600' : ''; ?> hover:text-blue-500">◇</button>&nbsp;
                        </th>
                    </tr>

                </thead>
                <tbody>
                    <?php while ($ucebnice = $prodavaneUcebnice->fetch_assoc()): ?>
                        <tr class="border-t-2">
                            <td class="p-4"><?php echo htmlspecialchars($ucebnice['stav']); ?>/10</td>
                            <td class="p-4"><?php echo htmlspecialchars($ucebnice['rok_tisku']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($ucebnice['cena']); ?> Kč</td>
                            <td class="p-4">
                                <a href="user.php?profileID=<?php echo htmlspecialchars($ucebnice['prodejce_id']); ?>" 
                                    class="text-gray-600 italic">
                                    <?php echo htmlspecialchars($ucebnice['prodejce']); ?>
                                </a>
                            </td>
                            <td class="p-4 break-words" style="word-break: break-word;"><?php echo htmlspecialchars($ucebnice['poznamky']); ?></td>
                            <td class="p-4 text-green-600 font-semibold">
                                <a id="viewHref-<?php echo htmlspecialchars($ucebnice['id']); ?>" 
                                    href="koupit.php?puID=<?php echo htmlspecialchars($ucebnice['id']); ?>" 
                                    class="p-3 bg-green-100 hover:bg-green-200 rounded-md w-[75%] text-center block">
                                    Koupit
                                </a>

                                <script>
                                    var UserId = <?php echo $userId; ?>;
                                    var prodejceId = <?php echo htmlspecialchars($ucebnice['id_prodejce']); ?>;
                                    var koupil = <?php echo htmlspecialchars($ucebnice['koupil']); ?>;
                                    var id = <?php echo htmlspecialchars($ucebnice['id']); ?>;
                                    // Funkce pro skrytí tlačítka rezervace, pokud je uživatel prodávající
                                    if (prodejceId === UserId) {
                                        document.getElementById(`viewHref-${id}`).classList.remove('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                                        document.getElementById(`viewHref-${id}`).classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                                    }
                                    if (koupil > 0) {
                                        document.getElementById(`viewHref-${id}`).classList.remove('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                                        document.getElementById(`viewHref-${id}`).classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                                    }
                                </script>
                            </td>  
                            </td>  
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        </div>
        
<script>

function BookChange() {
    const url = new URL(window.location.href);
    let a = url.searchParams.get('selfbook');
    if (a === null) {
        a = -1;
    } else {
        a = parseInt(a) * -1; // Přepnutí mezi 1 a -1
    }

    url.searchParams.set('selfbook', a);
    window.location.href = url.toString();
}
const urlParams = new URLSearchParams(window.location.search);
const selfbook = urlParams.get('selfbook');

// Pokud je 'selfbook' rovno 1, změníme text tlačítka
if (selfbook == '-1') {
    document.getElementById("bookButton").innerHTML = "◆";
}
else {
    document.getElementById("bookButton").innerHTML = "◇";
}


</script>        

</body>
</html>