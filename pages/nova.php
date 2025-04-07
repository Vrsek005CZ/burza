<?php
session_start();
require_once "../code/connect.php";
require_once "../code/new.php";
require_once "../header.php";

getHeader("Nová učebnice");

// Načtení dat pomocí funkcí
$resultKategorie = getKategorie($conn);
$resultTyp = getTypy($conn);

// Zpracování formuláře
$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pridat'])) {
    $message = pridatUcebnici($conn, $_POST, $_FILES);
}
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
    <?php if (!empty($message)): ?>
        <div class="bg-green-200 p-3 rounded mb-4">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="sm:flex sm:space-x-4 space-y-4 sm:space-y-0">
            <div class="w-full sm:w-1/2">
                <label class="block font-bold">Název učebnice</label>
                <input type="text" name="nazev_ucebnice" class="border p-2 w-full resize-none" maxlength="256" required>
            </div>
            <div class="w-full sm:w-1/4">
                <label class="block font-bold">Kategorie</label>
                <select name="kategorie_id" class="border p-2 w-full" required>
                    <?php while ($row = $resultKategorie->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nazev']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="w-full sm:w-1/4">
                <label class="block font-bold">Typ</label>
                <select name="typ_id" class="border p-2 w-full" required>
                    <?php while ($row = $resultTyp->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nazev']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="sm:flex sm:space-x-4 space-y-4 sm:space-y-0 mt-4">
            <div class="w-full sm:w-1/4">
                <label class="block font-bold">Určeno pro ročník</label>
                <select name="trida_id" class="border p-2 w-full" required>
                    <?php for ($i = 1; $i <= 8; $i++) { ?>
                        <option value="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button type="submit" name="pridat" class="bg-blue-500 text-white px-4 py-2 rounded">Přidat</button>
        </div>
        
        <br>
        <!-- Sekce pro nahrání nových fotek -->
        <div class="bg-gray-100 shadow-md rounded-md p-4 sm:p-8 mx-auto">
            <div class="text-center font-bold">Sem můžete nahrát fotky. Při použití fotek heif/heif formátu nefunguje náhled</div>
            <input type="file" id="fotky" name="fotky[]" accept="image/*" required class="p-2 w-full"><hr>
            <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>
    </form>
</div>

<script src="../code/new.js"></script>
</body>
</html>
