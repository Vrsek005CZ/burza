<?php
session_start();
require_once "../code/connect.php";
require_once "../code/userinfo.php";

require_once "../header.php";
getHeader("Objednávky"); 


require_once "../code/orders.php";
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto"> 
    <div class="flex justify-center mb-5">
        <button onclick="showProdavane()" id ="prodavene_button" class="w-1/2 px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Prodávané</button>
        <button onclick="showKupovane()" id="kupovane_button" class="w-1/2 px-4 py-2 mx-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Kupované</button>
    </div><hr><br>
    <!-- Prodávané -->
    <div id="prodavene_div" class="">
        <?php if ($resultsell_complete->num_rows > 0): ?>
            <div class="overflow-x-auto">
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
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádné aktivní objednávky.</div>
        <?php endif; ?><br><hr><br>
        <?php if ($resultsell_pending->num_rows > 0): ?>
            <div class="overflow-x-auto">
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
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádnou historii objednávek.</div>
        <?php endif; ?>
    </div>
    <!-- Kupované -->
    <div id="kupovane_div" class="hidden">
        <?php if ($resultbuy_complete->num_rows > 0): ?>
            <div class="overflow-x-auto">
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
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádné aktivní objednávky.</div>
        <?php endif; ?><br><hr><br>
        <?php if ($resultbuy_pending->num_rows > 0): ?>
            <div class="overflow-x-auto">
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
            </div>
        <?php else: ?>
            <div class="text-center text-xl font-bold">Nemáte žádnou historii objednávek.</div>
        <?php endif; ?>
    </div>
</div>

</body>
<script src="../code/orders.js"></script>
</html>