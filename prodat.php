<?php
session_start();
include("connect.php");
$current_year = date("Y");
$pageTitle = "Prodat"; 
include("header.php");
// Získání seznamu učebnic
$sql = "SELECT id, jmeno FROM ucebnice";
$sql = "SELECT ucebnice.id, ucebnice.jmeno, kategorie.nazev AS kategorie 
        FROM ucebnice
        JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
        ORDER BY kategorie.nazev, ucebnice.jmeno"; 
$result = $conn->query($sql);
?>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

    <!-- jQuery (pokud ještě není) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
        <form method="POST" action="pridat_knihu.php" enctype="multipart/form-data">
            <table class="w-full bg-gray-50 shadow-md rounded-lg">
                <thead class="text-left bg-gray-200">
                    <tr>
                        <th class="p-4 w-[28%]">Učebnice</th>
                        <th class="p-4 w-[8%]">Stav</th>
                        <th class="p-4 w-[8%]">Rok tisku</th>
                        <th class="p-4 w-[8%]">Cena</th>
                        <th class="p-4 w-[35%]">Poznámky</th>
                        <th class="p-4 w-[15%] text-center">Vystavit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-4">
                        <select id="ucebniceSelect" name="id_ucebnice" class="border p-2 w-full select2" required>
                            <?php 
                            $currentCategory = null;
                            while ($row = $result->fetch_assoc()) { 
                                // Pokud se kategorie změnila, zavři předchozí optgroup a otevři nový
                                if ($currentCategory !== $row['kategorie']) {
                                    if ($currentCategory !== null) {
                                        echo "</optgroup>"; // Zavřít předchozí skupinu
                                    }
                                    echo "<optgroup label='" . htmlspecialchars($row['kategorie']) . "'>"; // Otevřít novou skupinu
                                    $currentCategory = $row['kategorie'];
                                }
                            ?>
                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo htmlspecialchars($row['jmeno']); ?>
                                </option>
                            <?php } ?>
                            </optgroup> <!-- Zavře poslední otevřenou kategorii -->
                            
                            <option value="redirect" class="bg-gray-200 text-center">Vložit novou učebnici</option>
                        </select>
                        </td>
                        <td class="p-1">
                            <input type="number" name="stav" min="1" max="10" class="border p-2 w-full" required>
                        </td>
                        <td class="p-1">
                            <input type="number" name="rok_tisku" min="1900" max="<?php echo $current_year; ?>" class="border p-2 w-full" required>
                        </td>
                        <td class="p-1">
                            <input type="number" name="cena" min="0" class="border p-2 w-full" required>
                        </td>
                        <td class="p-1">
                            <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"></textarea>
                        </td>
                        <td class="p-1 text-center">
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
                <input type="file" id="fotky" name="fotky[]" accept="image/*" multiple class="p-2 w-full"><hr>
                <div id="preview" class="flex flex-wrap gap-2 mt-2"></div>
            </div>
        </form>
    </div>
    <h1>otaci fotky na mobilu</h1>

</div>

<script>
// Přidáme posluchače události 'change' pro input, kdy uživatel vybere soubory
document.getElementById('fotky').addEventListener('change', function(event) {
    // Najdeme element, do kterého budeme vkládat náhledy obrázků
    const preview = document.getElementById('preview');
    // Vyčistíme předchozí náhledy, aby nedocházelo k duplikaci
    preview.innerHTML = "";

    // Převod FileList (event.target.files) na pole pro snadnější manipulaci
    const files = Array.from(event.target.files);

    // Projdeme všechny vybrané soubory
    files.forEach((file, index) => {
        // Kontrola, zda soubor je obrázek (pomocí regex pro různé formáty)
        if (/^image\//.test(file.type)) {
            // Vytvoříme nový FileReader pro načtení souboru
            const reader = new FileReader();

            // Definujeme, co se stane, když bude soubor načten
            reader.onload = function(e) {
                // Vytvoříme nový <img> element
                const img = document.createElement('img');
                // Nastavíme zdroj obrázku na načtená data (data URL)
                img.src = e.target.result;
                // Přidáme CSS třídy pro stylování obrázku (velikost, okraj, kurzor)
                img.classList.add("h-[24vh]", "object-cover", "border", "cursor-pointer", "hover:opacity-70");
                // Přidáme datový atribut pro uložení indexu souboru
                img.setAttribute("data-index", index);

                // Přidáme posluchače události, který umožní odstranění obrázku při kliknutí
                img.addEventListener("click", function() {
                    // Odstraníme soubor z pole files pomocí jeho indexu
                    files.splice(index, 1);

                    // Vytvoříme nový DataTransfer objekt pro sestavení nového FileListu
                    const dt = new DataTransfer();
                    // Přidáme všechny zbývající soubory do DataTransfer objektu
                    files.forEach(file => dt.items.add(file));

                    // Aktualizujeme seznam souborů v inputu tak, aby neobsahoval odstraněný soubor
                    event.target.files = dt.files;

                    // Odstraníme náhled obrázku z DOM (zobrazení)
                    img.remove();
                });

                // Přidáme vytvořený obrázek do preview kontejneru, aby byl viditelný
                preview.appendChild(img);
            };

            // Spustíme asynchronní načítání souboru a převedení na data URL
            reader.readAsDataURL(file);
        }
    });
});


$(document).ready(function() {
    $('#ucebniceSelect').select2({
        placeholder: "Vyberte učebnici...",
        allowClear: true,
        templateResult: function(option) {
            if (!option.id) {
                return option.text;
            }
            if (option.id === 'redirect') {
                return $('<span class="bg-gray-200 text-center block px-2 py-1 text-italic font-semibold">Vložit novou učebnici</span>');
            }
            return option.text;
        }
    });

    // Přesměrování na "nova.php", pokud se vybere poslední možnost
    $('#ucebniceSelect').on('change', function() {
        if ($(this).val() === 'redirect') {
            window.location.href = 'nova.php';
        }
    });
});


</script>


</body>
</html>
