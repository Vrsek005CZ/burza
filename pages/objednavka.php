<?php
session_start();
require_once "../code/connect.php";
$pageTitle = "Objednávka"; 
require_once "../header.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST['orderID']) AND isset($_POST['typ']) ) {
    $orderID = $_POST['orderID'];
    $typ = $_POST['typ'];

    $sql = "SELECT 
    orders.id as order_id, orders.puID as puID, orders.complete as complete, pu.id_ucebnice, pu.id as puID,  pu.id_prodejce, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, ucebnice.jmeno as jmeno_ucebnice, ucebnice.id as ucebnice_id, user.user as user_jmeno, user.id as user_id, user.email as mail
    FROM orders
    INNER JOIN pu ON orders.puID = pu.id
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN user ON pu.$typ = user.id
    WHERE orders.id = ?";

    // Příprava a provedení dotazu pro prodej
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderID); // Parametr je typu integer
    $stmt->execute();
    $result = $stmt->get_result();

    $order = $result -> fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderID']) && isset($_POST['confirm'])) {
    $orderID = intval($_POST["orderID"]);

    $sql = "UPDATE orders SET complete = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderID);

    if ($stmt->execute()) {
        echo "Objednávka byla úspěšně potvrzena!";
    } else {
        echo "Chyba při potvrzení objednávky.";
    }

    $stmt->close();
    $conn->close();
} 


?>
        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
            <?php if (isset($result) && $result->num_rows > 0): ?>
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-gray-700 text-center">Detail objednávky</h2>
                    <div class="flex text-gray-800 text-lg w-full bg-gray-50 shadow-md rounded-md">
                        <img src="../foto/ucebnice/<?php echo htmlspecialchars($order['ucebnice_id']); ?>.webp" class="h-[30vh] p-4 border-r">
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
                            <p>
                            <span class="font-semibold">E-mail: </span><?php echo htmlspecialchars($order['mail']); ?>
                            </p>
                            <p>
                                <span class="font-semibold">Stav objednávky:</span>
                                <?php if($order['complete'] == 1): ?>
                                    <span class="text-green-500">Dokončeno</span>
                                <?php else: ?>
                                    <span class="text-yellow-500">Probíhá</span>
                                <?php endif; ?>
                            </p>
                            <br><hr><br>
                            <div class="flex justify-between w-full">
                                <a href="koupit.php?puID=<?php echo htmlspecialchars($order['puID']); ?>" class="w-full px-4 py-2 mr-2 bg-green-500 text-white rounded hover:bg-green-600 transition text-center">přejít na knihu</a>
                                <a href="mailto:<?php echo htmlspecialchars($order['mail']); ?>?subject=Objednávka%20z%20burzy%20učebnic%20číslo%20#<?php echo htmlspecialchars ($order['order_id']); ?>" class="w-full px-4 py-2 mr-2 ml-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-center">napsat e-mail</a>
                                <?php if($order['complete'] == 0): ?>
                                    <button id="objednat" onclick="objednat()" class="w-full px-4 py-2 ml-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-center">potvrdit objednávku</button>
                                    <button id="potvrdit" onclick="potvrdit()" class="hidden w-full px-4 py-2 ml-2 bg-red-500 text-white rounded hover:bg-red-600 transition text-center">potvrdit</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-red-600 font-semibold text-center">Chyba: objednávka nenalezena.</div>
            <?php endif; ?>
        </div>
    </div>

</body>
<script>

function objednat(){
    document.getElementById("objednat").classList.add('hidden');
    document.getElementById("potvrdit").classList.remove('hidden');
}

document.getElementById("potvrdit").addEventListener("click", function () {
    let orderID = <?php echo json_encode(value: $orderID); ?>;

    fetch("objednavka.php", {//odešle požadavek
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },//formát url-endcoded
        body: "confirm=1&orderID=" + orderID 
    })
    .then(response => response.text())
    .then(data => {
        alert("Objednávka úspěšně potvrzena");
        location.reload(); // Obnoví stránku pro zobrazení změn
    })
    .catch(error => console.error("Chyba:", error));
});


</script>
</html>