<?php
session_start();
require_once "../code/connect.php";
require_once "../code/userinfo.php";
require_once "../code/orders.php";
require_once "../header.php";

getHeader("Objednávky");

// Načtení objednávek
$prodavaneDokoncene = getProdavaneObjednavky($conn, $userId, 1);
$prodavaneCekajici = getProdavaneObjednavky($conn, $userId, 0);
$kupovaneDokoncene = getKupovaneObjednavky($conn, $userId, 1);
$kupovaneCekajici = getKupovaneObjednavky($conn, $userId, 0);
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto"> 
    <div class="flex justify-center mb-5">
        <button onclick="showProdavane()" id="prodavene_button" class="w-1/2 px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Prodávané</button>
        <button onclick="showKupovane()" id="kupovane_button" class="w-1/2 px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Kupované</button>
    </div>
    <hr><br>

    <!-- Prodávané -->
    <div id="prodavene_div">
        <?php if (count($prodavaneCekajici) > 0): ?>
            <h2 class="text-lg font-bold mb-4">Čekající objednávky</h2>
            <div class="overflow-x-auto">
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Název</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Kupuje</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prodavaneCekajici as $order): ?>
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['cena']); ?> Kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($order['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($order['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($order['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                        <input type="hidden" name="typ" value="koupil">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádné čekající objednávky.</div>
        <?php endif; ?>

        <?php if (count($prodavaneDokoncene) > 0): ?>
            <h2 class="text-lg font-bold mb-4">Dokončené objednávky</h2>
            <div class="overflow-x-auto">
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Název</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Kupuje</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prodavaneDokoncene as $order): ?>
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['cena']); ?> Kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($order['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($order['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($order['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                        <input type="hidden" name="typ" value="koupil">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádné aktivní objednávky.</div>
        <?php endif; ?>
    </div>

    <!-- Kupované -->
    <div id="kupovane_div" class="hidden">
        <?php if (count($kupovaneCekajici) > 0): ?>
            <h2 class="text-lg font-bold mb-4">Čekající objednávky</h2>
            <div class="overflow-x-auto">
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Název</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Prodává</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kupovaneCekajici as $order): ?>
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['cena']); ?> Kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($order['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($order['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($order['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                        <input type="hidden" name="typ" value="id_prodejce">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádné čekající objednávky.</div>
        <?php endif; ?>

        <?php if (count($kupovaneDokoncene) > 0): ?>
            <h2 class="text-lg font-bold mb-4">Dokončené objednávky</h2>
            <div class="overflow-x-auto">
                <table class="w-full bg-gray-50 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-4 w-[7%]">ID</th>
                            <th class="p-4 w-[55%]">Název</th>
                            <th class="p-4 w-[7%]">Cena</th>
                            <th class="p-4 w-[7%]">Stav</th>
                            <th class="p-4 w-[15%]">Prodává</th>
                            <th class="p-4 w-[9%]">Podrobnosti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kupovaneDokoncene as $order): ?>
                            <tr>
                                <td class="p-4"><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['jmeno_ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($order['cena']); ?> Kč</td>
                                <td class="p-4"><?php echo htmlspecialchars($order['stav']); ?>/10</td>
                                <td class="p-4">
                                    <a href="user.php?profileID=<?php echo htmlspecialchars($order['user_id']); ?>" class="text-gray-600 italic">
                                        <?php echo htmlspecialchars($order['user_jmeno']); ?>
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="objednavka.php">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                        <input type="hidden" name="typ" value="id_prodejce">
                                        <button type="submit" class="px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                            Přejít
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádné aktivní objednávky.</div>
        <?php endif; ?>
    </div>
</div>

<script src="../code/orders.js"></script>
</body>
</html>