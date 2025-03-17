<?php
session_start();
require_once "../code/connect.php";
$pageTitle = "Nová učebnice"; 
require_once "../header.php";

// Získání seznamu učebnic
$sqlUcebnice = "SELECT id, jmeno FROM ucebnice";
$resultUcebnice = $conn->query($sqlUcebnice);

// Získání seznamu kategorií
$sqlKategorie = "SELECT id, nazev FROM kategorie";
$resultKategorie = $conn->query($sqlKategorie);

// Získání seznamu typů
$sqlTyp = "SELECT id, nazev FROM typ";
$resultTyp = $conn->query($sqlTyp);

?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
    <form method="POST" action="../code/pridat_ucebnici.php" enctype="multipart/form-data">
        <table class="w-full bg-gray-50 shadow-md rounded-lg">
            <thead class="text-left bg-gray-200">
                <tr>
                    <th class="p-4 w-[25%]">Název učebnice</th>
                    <th class="p-4 w-[15%]">Kategorie</th>
                    <th class="p-4 w-[15%]">Typ</th>
                    <th class="p-4 w-[15%]">Určeno pro ročník</th>
                    <th class="p-4 w-[15%] text-center">Vystavit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-4">
                        <input type="text" name="nazev_ucebnice" class="border p-2 w-full resize-none" maxlength="256"></input>
                    </td>
                    <td class="p-4">
                        <select name="kategorie_id" class="border p-2 w-full" required>
                            <?php while ($row = $resultKategorie->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo htmlspecialchars($row['nazev']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="p-4">
                        <select name="typ_id" class="border p-2 w-full" required>
                            <?php while ($row = $resultTyp->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo htmlspecialchars($row['nazev']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="p-4">
                        <select name="trida_id" class="border p-2 w-full" required>
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                                <option value="<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="p-4 text-center">
                        <button type="submit" name="pridat" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Přidat
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
            <div class="text-center font-bold">Sem můžete nahrát fotky. Při použití fotek heif/heif formátu nefunguje náhled</div>
            <input type="file" id="fotky" name="fotky[]" accept="image/*" required class="p-2 w-full"><hr>
            <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>
    </form>
</div>
</div>

</body>

<script>
document.getElementById('fotky').addEventListener('change', function(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = ""; // Vyčistíme předchozí náhled

    const file = event.target.files[0]; // Získáme první soubor
    if (file && /^image\//.test(file.type)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add("h-[50vh]", "object-cover", "text-center", "border", "cursor-pointer", "hover:opacity-70");
            
            img.addEventListener("click", function() {
                event.target.value = ""; // Resetujeme input
                preview.innerHTML = ""; // Odstraníme náhled
            });
            
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>
</html>
