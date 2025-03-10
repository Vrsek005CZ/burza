<?php
#VÍM, ŽE TOTO, CO JSEM STOŘIL, JE ABSOLUTNÍ VÝSMĚCH ČEMUKOLIV, CO MÁ SPOLEČNÉHO S POČÍTAČI. JE TO DŮKAZ TOHO, ŽE BUĎ JE BŮH VE SVÉM VESMÍRU NAPROSTO BEZMOČNÝ, ABY COKOLIV UDĚLAL S TÍMTO ZVĚRSTVEM, NEBO ŽE MU JE JEDNO, CO SE V JEHO KRÁLOVSTVÍ DĚJE. KDOKOLIV, KDO BY TOTO VIDĚL, BY VĚDĚL, ŽE JE TO ZVĚRSTVO. ALE FUNGUJE TO :D TAK TŘEBA NĚKOMU DALŠÍMU, KDO SE ZA PÁR LET BUDE V TOMTO KÓDU HRABAT, TAK MU TO DÁ ASPOŇ POVEDEMÍ O TOM, JAKÝ JSEM BYL ČLOVĚK. ČLOVĚK JEDNODUCHÝ A PŘÍMOČARÝ, KTERÝ SI VOLÍ TU NEJKRATŠÍ CESTU, I KDYŽ BY SE NAŠLY MNOHEM LEPŠÍ CESTY A ČLOVĚK, CO MÁ VŠE NA HÁKU.
session_start();
include("connect.php");
include("userinfo.php");
$pageTitle = "Objednávky"; 
include("header.php");

// Příprava dotazu pro prodej
$sql_selling_complete = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.koupil = user.id
WHERE pu.id_prodejce = ? AND orders.complete = 0
ORDER BY orders.cas DESC
";

$sql_selling_pending = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.koupil = user.id
WHERE pu.id_prodejce = ? AND orders.complete = 1
ORDER BY orders.cas DESC
";


// Příprava dotazu pro nákup
$sql_buying_complete = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.id_prodejce = user.id
WHERE pu.koupil = ? AND orders.complete = 0
ORDER BY orders.cas DESC
";

$sql_buying_pending = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.id_prodejce = user.id
WHERE pu.koupil = ? AND orders.complete = 1
ORDER BY orders.cas DESC
";

// Příprava a provedení dotazu pro prodej
$stmt_sell_complete = $conn->prepare($sql_selling_complete);
$stmt_sell_complete->bind_param("i", $userId); // Parametr je typu integer
$stmt_sell_complete->execute();
$resultsell_complete = $stmt_sell_complete->get_result();

$stmt_sell_pending = $conn->prepare($sql_selling_pending);
$stmt_sell_pending->bind_param("i", $userId); // Parametr je typu integer
$stmt_sell_pending->execute();
$resultsell_pending = $stmt_sell_pending->get_result();

// Příprava a provedení dotazu pro nákup
$stmt_buy_complete = $conn->prepare($sql_buying_complete);
$stmt_buy_complete->bind_param("i", $userId); // Parametr je typu integer
$stmt_buy_complete->execute();
$resultbuy_complete = $stmt_buy_complete->get_result();

$stmt_buy_pending = $conn->prepare($sql_buying_pending);
$stmt_buy_pending->bind_param("i", $userId); // Parametr je typu integer
$stmt_buy_pending->execute();
$resultbuy_pending = $stmt_buy_pending->get_result();
?>



    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto"> 
        <div class="flex justify-center mb-5">
            <button onclick="showProdavane()" id ="prodavene_button" class="w-1/2 px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Prodávané</button>
            <button onclick="showKupovane()" id="kupovane_button" class="w-1/2 px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Kupované</button>
        </div><hr><br>
        <!-- Prodávané -->
        <div id = "prodavene_div" class="">
            <?php if ($resultsell_complete->num_rows > 0): ?>
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                    <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Nazev</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Kupuje</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($sell_complete = $resultsell_complete->fetch_assoc()): ?>    
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($sell_complete['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($sell_complete['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($sell_complete['cena']); ?> kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($sell_complete['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($sell_complete['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($sell_complete['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($sell_complete['order_id']); ?>">
                                        <input type="hidden" name="typ" value="koupil">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center text-xl font-bold">Nemáte žádné aktivní objednávky.</div>
            <?php endif; ?><br><hr><br>
            <?php if ($resultsell_pending->num_rows > 0): ?>
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                    <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Nazev</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Kupuje</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($sell_pending = $resultsell_pending->fetch_assoc()): ?>    
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($sell_pending['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($sell_pending['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($sell_pending['cena']); ?> kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($sell_pending['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($sell_pending['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($sell_pending['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($sell_pending['order_id']); ?>">
                                        <input type="hidden" name="typ" value="koupil">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center text-xl font-bold">Nemáte žádnou historii objednávek.</div>
            <?php endif; ?>
        </div>
        <!-- Kupované -->
        <div id = "kupovane_div" class="hidden">
            <?php if ($resultbuy_complete->num_rows > 0): ?>
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                    <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Nazev</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Prodává</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($buy_complete = $resultbuy_complete->fetch_assoc()): ?>    
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($buy_complete['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($buy_complete['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($buy_complete['cena']); ?> kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($buy_complete['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($buy_complete['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($buy_complete['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($buy_complete['order_id']); ?>">
                                        <input type="hidden" name="typ" value="id_prodejce">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center text-xl font-bold">Nemáte žádné aktivní objednávky.</div>
            <?php endif; ?><br><hr><br>
            <?php if ($resultbuy_pending->num_rows > 0): ?>
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                    <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Nazev</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Prodává</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($buy_pending = $resultbuy_pending->fetch_assoc()): ?>    
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($buy_pending['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($buy_pending['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($buy_pending['cena']); ?> kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($buy_pending['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($buy_pending['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($buy_pending['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($buy_pending['order_id']); ?>">
                                        <input type="hidden" name="typ" value="id_prodejce">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center text-xl font-bold">Nemáte žádnou historii objednávek.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
<script>
    const prodavaneDiv = document.getElementById('prodavene_div');
    const kupovaneDiv = document.getElementById('kupovane_div');
    const prodavaneButton = document.getElementById('prodavene_button');
    const kupovaneButton = document.getElementById('kupovane_button');

    showProdavane()
    
    function showProdavane() {
        prodavaneDiv.classList.remove('hidden');
        kupovaneDiv.classList.add('hidden');
        prodavaneButton.classList.remove('hover:bg-blue-600', 'bg-blue-500')
        prodavaneButton.classList.add('cursor-not-allowed', 'bg-gray-400')
        kupovaneButton.classList.remove('cursor-not-allowed', 'bg-gray-400')
        kupovaneButton.classList.add('hover:bg-blue-600', 'bg-blue-500')
    }

    function showKupovane() {
        kupovaneDiv.classList.remove('hidden');
        prodavaneDiv.classList.add('hidden');
        kupovaneButton.classList.remove('hover:bg-blue-600', 'bg-blue-500')
        kupovaneButton.classList.add('cursor-not-allowed', 'bg-gray-400')
        prodavaneButton.classList.remove('cursor-not-allowed', 'bg-gray-400')
        prodavaneButton.classList.add('hover:bg-blue-600', 'bg-blue-500')
    }
</script>
</html>