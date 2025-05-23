<?php
require_once "../code/connect.php";
require_once "../code/userinfo.php";
require_once "../code/buy.php";
require_once "../code/orders.php";

require_once "../header.php";
getHeader("Koupit");

// Získání ID učebnice z GET parametru
if (isset($_GET['puID'])) {
    $puID = intval($_GET['puID']); // Proti SQL injection
} else {
    echo("Chyba: Nezadali jste ID knihy.");
    exit;
}

// Zpracování objednávky
$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['objednat'])) {
    $puID = intval($_POST['objednat']);
    $message = processOrder($conn, $puID, $userId);
}

// Načtení detailu učebnice
$row = getBookDetail($conn, $puID);
if (!$row) {
    echo "Učebnice nenalezena.";
    exit;
}

// Načtení obrázků
$files = getBookImages($puID);
$files_json = json_encode($files); // Poslání do JS
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
    <!-- Šipka zpět -->
    <a href="ucebnice.php?knihaID=<?php echo htmlspecialchars($row['id']); ?>" class="inline-flex items-center text-blue-600 hover:underline mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Zpět na učebnici
    </a>
    <?php if (!empty($message)): ?>
        <div class="bg-green-200 p-3 rounded sm:mb-4 sm:flex sm:items-center sm:justify-between text-center sm:text-left">
            <span class="block sm:inline mb-4 sm:mb-0"><?php echo htmlspecialchars($message); ?></span>
            <?php if ($message === "Objednávka byla úspěšně zpracována!"): ?>
                <?php 
                $orders = getKupovaneObjednavky($conn, $userId, 0);
                $order = getOrderByPuID($orders, $puID);
                ?>
                <form method="POST" action="objednavka.php" class="sm:ml-4">
                    <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                    <input type="hidden" name="typ" value="id_prodejce">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Přejít na objednávku
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($row): ?>
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="ucebnice.php?knihaID=<?php echo htmlspecialchars($row['id'])?>" class="">
            <img src="../foto/ucebnice/<?php echo htmlspecialchars($row['id'])?>.webp" class="rounded-lg p-1 w-full sm:w-56 object-cover justify-self-center bg-gray-300">
        </a>
        <div class="flex flex-col w-full">
            <div class="text-lg font-bold text-center sm:text-left"><?php echo htmlspecialchars($row['ucebnice_nazev']); ?></div>
            <div class="text-sm flex flex-col sm:flex-row">
                <div class="flex flex-col flex-grow gap-2">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-32 flex-auto text-slate-700"><?php echo htmlspecialchars($row['typ_nazev'])?></div>
                        <div class="w-full sm:w-32 flex-auto"><?php echo htmlspecialchars($row['rok_tisku'])?></div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-32 flex-auto text-slate-700"><?php echo htmlspecialchars($row['kategorie_nazev'])?></div>
                        <div class="w-full sm:w-32 flex-auto"><?php echo htmlspecialchars($row['stav'])?>/10</div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-32 flex-auto text-slate-700">vhodné pro <?php echo htmlspecialchars($row['trida_id'])?>. ročník</div>
                        <div class="w-full sm:w-32 flex-auto">
                            <a href="user.php?profileID=<?php echo htmlspecialchars($row['prodejce_id']); ?>" class="text-blue-600 hover:underline">
                                <?php echo htmlspecialchars($row['prodejce']); ?>
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-32 flex-auto text-slate-700 font-bold">Cena:</div>
                        <div class="w-full sm:w-32 flex-auto font-bold text-green-600"><?php echo htmlspecialchars($row['cena']); ?> Kč</div>
                    </div>
                </div>
                <div class="items-center mt-4 sm:mt-0">
                    <?php if ($row['koupil']): ?>
                        <button disabled class="bg-gray-400 text-white font-bold py-3 w-full sm:w-24 rounded-lg shadow-md">
                            Prodáno
                        </button>
                    <?php elseif ($row['prodejce_id'] == $userId): ?>
                        <button disabled class="bg-gray-400 text-white font-bold py-3 w-full sm:w-24 rounded-lg shadow-md">
                            Vaše
                        </button>
                    <?php else: ?>
                        <button id="koupitButton" onclick="koupit()" class="bg-green-600 text-white font-bold py-3 w-full sm:w-24 rounded-lg shadow-md hover:bg-green-700 transition">
                        Koupit
                        </button>
                        <form id="myForm" action="" method="POST" class="w-full sm:w-24">
                            <button type="submit" name="objednat" value="<?php echo $puID; ?>" id="potvrditButton" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 w-full sm:w-24 rounded-lg shadow-md hover:bg-yellow-700 transition hidden">
                                Objednat
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <div class="bg-gray-100 shadow-md rounded-md p-2 mt-2 break-words"><?php echo htmlspecialchars($row['poznamky'])?></div>
        </div>
    </div>
    <?php else: ?>
        <p>Učebnice nenalezena.</p>
    <?php endif; ?>

<br>

    <div class='flex flex-wrap col-span-8 gap-1 bg-gray-200 shadow-md p-5 rounded-md w-full'>
        <?php
        if ($files) {
            foreach ($files as $index => $file) { // Projde každý obrázek
                echo "<img src='$file' class='h-48 aspect-auto cursor-pointer' onclick='otevritOkno($index)' />";
            }
        } else {
            echo "Nejsou k dispozici žádné fotky.";
        }
        ?>
    </div>
</div>

<div id="okno" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden">
    <button class="absolute top-2 right-2 bg-gray-300 hover:bg-gray-400 p-2 rounded-full shadow" onclick="zavritOkno()">✖</button>
    <div class="relative p-4 flex items-center justify-center max-w-4xl w-full h-[85vh]">

        <button onclick="predchoziObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            ←
        </button>

        <div class="relative bg-gray-300 p-4 rounded-lg shadow-lg flex items-center justify-center w-full h-full mx-1">
            <img id="oknoImg" src="../foto/ucebnice/1.webp" class="object-contain w-full h-auto"/>
        </div>

        <button onclick="dalsiObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            →
        </button>
        
    </div>
</div>

<script>
const UserId = <?php echo $userId; ?>;
const prodejceId = <?php echo htmlspecialchars($row['prodejce_id']); ?>;
const koupil = <?php echo htmlspecialchars($row['koupil']); ?>;
let obrazky = <?php echo $files_json; ?>;

function koupit(){
    document.getElementById('koupitButton').classList.add('hidden')
    document.getElementById('potvrditButton').classList.remove('hidden')
}


// Funkce pro skrytí tlačítka rezervace, pokud je uživatel prodávající
if (prodejceId === UserId || koupil !== 0) {
    const button = document.getElementById('koupitButton')
    button.classList.remove('bg-green-600', 'hover:bg-green-700', 'transition', 'cursor-pointer');
    button.classList.add('bg-gray-400', 'cursor-not-allowed');
    button.removeAttribute("href");
    button.removeAttribute("onclick");
}

document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
      zavritOkno();
    }
  });

let aktualniIndex = 0;

function otevritOkno(index) {
    aktualniIndex = index;
    document.getElementById("oknoImg").src = obrazky[aktualniIndex];
    document.getElementById("okno").classList.remove("hidden");
    nactiObrazek();
}

function zavritOkno() {
    document.getElementById("okno").classList.add("hidden");
}

function dalsiObrazek() {
    if (aktualniIndex < obrazky.length - 1) {
        aktualniIndex++;
    } else {
        aktualniIndex = 0;
    }
    velikostObrazku(aktualniIndex)
    document.getElementById("oknoImg").src = obrazky[aktualniIndex];
    nactiObrazek();
}

function predchoziObrazek() {
    if (aktualniIndex > 0) {
        aktualniIndex--;
    } else {
        aktualniIndex = obrazky.length - 1;
    }
    velikostObrazku(aktualniIndex)
    document.getElementById("oknoImg").src = obrazky[aktualniIndex];
    nactiObrazek();
}

function nactiObrazek() {
    let oknoImg = document.getElementById("oknoImg");
    oknoImg.src = obrazky[aktualniIndex];

    let img = new Image();
    img.src = obrazky[aktualniIndex];
    img.onload = function () {
        velikostObrazku(img);
    };
}

function velikostObrazku(img){
    let oknoImg = document.getElementById("oknoImg");

    if (img.height > img.width) {
        oknoImg.classList.remove("w-full");
        oknoImg.classList.add("h-full");
    } else {
        oknoImg.classList.remove("h-full");
        oknoImg.classList.add("w-full");
    }
}

</script>


<?php
// Zápatí stránku
require_once "../footer.php"; 
getFooter();
?>

</body>
</html>