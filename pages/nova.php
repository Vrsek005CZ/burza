<?php
session_start();
require_once "../code/connect.php";

$pageTitle = "Nová učebnice"; 
getHeader("Nová učebnice"); 

require_once "../header.php";
require_once "../code/new.php";
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
    <form method="POST" action="../code/pridat_ucebnici.php" enctype="multipart/form-data">
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
            <input type="file" id="fotky" name="fotky[]" accept="image/*" required class="p-2 w-full"><hr>
            <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>
    </form>
</div>

<script src="../code/new.js"></script>
</body>
</html>
