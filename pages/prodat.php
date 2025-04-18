<?php
session_start();
require_once "../code/connect.php";
require_once "../code/sell.php";
require_once "../header.php";

getHeader("Prodat");

// Načtení seznamu učebnic a aktuálního roku
$ucebniceSeznam = getUcebniceSeznam($conn);
$currentYear = getCurrentYear();

// Zpracování formuláře
$message = '';
$puID = null;
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pridat'])) {
    $result = pridatKnihu($conn, $_POST, $_FILES, $userId);
    $message = $result['msg'];
    if (isset($result['error']) && $result['error'] === false) {
        $puID = $result['puID'];
    }
}
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

<!-- jQuery (pokud ještě není) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
    <?php if (!empty($message)): ?>
        <div class="flex items-center gap-4 <?php echo isset($result['error']) && $result['error'] ? 'bg-red-200' : 'bg-green-200'; ?> p-3 rounded mb-4">
            <span><?php echo htmlspecialchars($message); ?></span>
            <?php if (!empty($puID)): ?>
                <a href="/burza/pages/koupit.php?puID=<?php echo urlencode($puID); ?>" 
                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Přejít na učebnici
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="sm:flex sm:space-x-4 space-y-4 sm:space-y-0">
            <div class="w-full sm:w-1/2">
                <label class="block font-bold">Učebnice</label>
                <select id="ucebniceSelect" name="id_ucebnice" class="border p-2 w-full select2" required>
                    <?php 
                    $currentCategory = null;
                    foreach ($ucebniceSeznam as $row) { 
                        if ($currentCategory !== $row['kategorie']) {
                            if ($currentCategory !== null) {
                                echo "</optgroup>";
                            }
                            echo "<optgroup label='" . htmlspecialchars($row['kategorie']) . "'>";
                            $currentCategory = $row['kategorie'];
                        }
                    ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['jmeno']); ?>
                        </option>
                    <?php } ?>
                    </optgroup>
                    <option value="redirect" class="bg-gray-200 text-center">Vložit novou učebnici</option>
                </select>
            </div>
            <div class="w-full sm:w-1/4">
                <label class="block font-bold">Stav</label>
                <input type="number" name="stav" min="1" max="10" class="border p-2 w-full" required>
            </div>
            <div class="w-full sm:w-1/4">
                <label class="block font-bold">Rok tisku</label>
                <input type="number" name="rok_tisku" min="1900" max="<?php echo $currentYear; ?>" class="border p-2 w-full" required>
            </div>
        </div>
        
        <div class="sm:flex sm:space-x-4 space-y-4 sm:space-y-0 mt-4">
            <div class="w-full sm:w-1/4">
                <label class="block font-bold">Cena</label>
                <input type="number" name="cena" min="0" class="border p-2 w-full" required>
            </div>
            <div class="w-full sm:w-3/4">
                <label class="block font-bold">Poznámky</label>
                <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"></textarea>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button type="submit" name="pridat" class="bg-blue-500 text-white px-4 py-2 rounded">Přidat</button>
        </div>
        
        <br>
        <!-- Sekce pro nahrání nových fotek -->
        <div class="bg-gray-100 shadow-md rounded-md p-4 sm:p-8 mx-auto">
            <div class="text-center font-bold">Sem můžete nahrát fotky. Při použití fotek heif/heif formátu nefunguje náhled</div>
            <input type="file" id="fotky" name="fotky[]" accept="image/*" multiple class="p-2 w-full"><hr>
            <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#ucebniceSelect').select2();
    });
</script>

<script src="../code/sell.js"></script>

</body>
</html>
