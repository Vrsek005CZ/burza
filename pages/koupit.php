<?php
require_once "../code/connect.php";
require_once "../code/userinfo.php";
require_once "../code/buy.php";

$pageTitle = "Koupit"; 
require_once "../header.php";
?>

<style>
.bg-gray-100 {
    word-break: break-word;
    overflow-wrap: break-word;
}
</style>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
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
                            <a href="user.php?profileID=<?php echo htmlspecialchars($row['prodejce_id']); ?>" class="text-gray-600 italic">
                                <?php echo htmlspecialchars($row['prodejce']); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center mt-4 sm:mt-0">
                    <button id="koupitButton" onclick="koupit()" class="bg-green-600 text-white font-bold py-3 w-full sm:w-24 rounded-lg shadow-md hover:bg-green-700 transition">
                        Koupit
                    </button>
                    <form id="myForm" action="../code/objednat.php" method="POST" class="w-full sm:w-auto">
                        <button type="submit" name="objednat" value="<?php echo $puID; ?>" id="potvrditButton" class="bg-yellow-600 text-white font-bold py-3 w-full sm:w-24 rounded-lg shadow-md hover:bg-yellow-700 transition hidden">
                            Potvrdit
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="bg-gray-100 shadow-md rounded-md p-2 mt-2"><?php echo htmlspecialchars($row['poznamky'])?></div>
        </div>
    </div>
<?php else: ?>
        <p>Učebnice nenalezena.</p>
    <?php endif; ?>

<br>

    <div class='flex flex-wrap col-span-8 gap-1 bg-gray-200 shadow-md p-5 rounded-md w-full'>
        <?php
        if ($files) {
            foreach ($files as $index => $file) { //oznaci indexi obrazku a projde pro kazdy obrazek
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
            ⯇
        </button>

        <div class="relative bg-gray-300 p-4 rounded-lg shadow-lg flex items-center justify-center w-full h-full mx-1">
            <img id="oknoImg" src="../foto/ucebnice/1.webp" class="object-contain w-full h-auto"/>
        </div>

        <button onclick="dalsiObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            ⯈
        </button>
        
    </div>
</div>

<script>

const UserId = <?php echo $userId; ?>;
const prodejceId = <?php echo htmlspecialchars($row['prodejce_id']); ?>;
const koupil = <?php echo htmlspecialchars($row['koupil']); ?>

let obrazky = <?php echo $files_json; ?>;

</script>
<script src="../code/buy.js"></script>

</div>

</body>
</html>