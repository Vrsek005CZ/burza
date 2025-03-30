<?php
session_start();
require_once "../code/connect.php";
require_once "../code/order.php";
require_once "../header.php";

getHeader("Objednávka");

// Zpracování POST požadavku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['orderID']) && isset($_POST['typ'])) {
        $orderID = intval($_POST['orderID']);
        $typ = $_POST['typ'];
        $order = getOrderDetail($conn, $orderID, $typ);
    }

    if (isset($_POST['orderID']) && isset($_POST['confirm'])) {
        $orderID = intval($_POST['orderID']);
        if (confirmOrder($conn, $orderID)) {
            echo "<script>alert('Objednávka byla úspěšně potvrzena!');</script>";
        } else {
            echo "<script>alert('Chyba při potvrzení objednávky.');</script>";
        }
    }
}
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
    <?php if (isset($order)): ?>
        <div class="space-y-4">
            <h2 class="text-xl font-bold text-gray-700 text-center">Detail objednávky</h2>
            <div class="flex flex-col sm:flex-row text-gray-800 text-lg w-full bg-gray-50 shadow-md rounded-md">
                <img src="../foto/ucebnice/<?php echo htmlspecialchars($order['ucebnice_id']); ?>.webp" class="sm:h-[30vh] w-auto p-4 border-b sm:border-b-0 sm:border-r">
                <div class="flex-col p-4 w-full">
                    <p><span class="font-semibold">ID objednávky:</span> <?php echo htmlspecialchars($order['order_id']); ?></p>
                    <p><span class="font-semibold">Učebnice:</span> <?php echo htmlspecialchars($order['jmeno_ucebnice']); ?></p>
                    <p><span class="font-semibold">Cena:</span> <?php echo htmlspecialchars($order['cena']); ?> Kč</p>
                    <p><span class="font-semibold">Stav:</span> <?php echo htmlspecialchars($order['stav']); ?>/10</p>
                    <p>
                        <span class="font-semibold">
                            <?php if ($typ == "id_prodejce"): ?>
                                Prodejce:
                            <?php else: ?>
                                Kupující:
                            <?php endif; ?>
                        </span>
                        <a href="user.php?profileID=<?php echo htmlspecialchars($order['user_id']); ?>" 
                        class="text-blue-500 hover:underline">
                            <?php echo htmlspecialchars($order['user_jmeno']); ?>
                        </a>
                    </p>
                    <p><span class="font-semibold">E-mail: </span><?php echo htmlspecialchars($order['mail']); ?></p>
                    <p>
                        <span class="font-semibold">Stav objednávky:</span>
                        <?php if ($order['complete'] == 1): ?>
                            <span class="text-green-500">Dokončeno</span>
                        <?php else: ?>
                            <span class="text-yellow-500">Probíhá</span>
                        <?php endif; ?>
                    </p>
                    <br><hr><br>
                    <div class="flex flex-col sm:flex-row justify-between w-full">
                        <a href="koupit.php?puID=<?php echo htmlspecialchars($order['puID']); ?>" class="w-full sm:w-auto px-4 py-2 mr-0 sm:mr-2 mb-2 sm:mb-0 bg-green-500 text-white rounded hover:bg-green-600 transition text-center">Přejít na knihu</a>
                        <a href="mailto:<?php echo htmlspecialchars($order['mail']); ?>?subject=Objednávka%20z%20burzy%20učebnic%20číslo%20#<?php echo htmlspecialchars($order['order_id']); ?>" class="w-full sm:w-auto px-4 py-2 mr-0 sm:mr-2 ml-0 sm:ml-2 mb-2 sm:mb-0 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-center">Napsat e-mail</a>
                        <?php if ($order['complete'] == 0): ?>
                            <form method="POST" class="w-full sm:w-auto">
                                <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                <input type="hidden" name="confirm" value="1">
                                <button type="submit" class="w-full sm:w-auto px-4 py-2 ml-0 sm:ml-2 mb-2 sm:mb-0 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-center">Potvrdit objednávku</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-red-600 font-semibold text-center">Chyba: objednávka nenalezena.</div>
    <?php endif; ?>
</div>
</body>
</html>