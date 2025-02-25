<?php
session_start();
include("connect.php");
$current_year = date("Y");
// Získání seznamu učebnic
$sql = "SELECT id, jmeno FROM ucebnice";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Prodat</title>
</head>

<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">

<div class="w-full max-w-5xl">
    <!-- Záhlaví -->
    <div class="flex items-center justify-between bg-white shadow-md p-5 rounded-md">
        <h1 class="text-3xl font-bold text-center flex-1 text-gray-800">
            <a href="index.php">Online Burza Učebnic</a>
        </h1>
    </div>
    <br>

    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
        <form method="POST" action="pridat_knihu.php" enctype="multipart/form-data">
            <table class="w-full bg-gray-50 shadow-md rounded-lg">
                <thead class="text-left bg-gray-200">
                    <tr>
                        <th class="p-4 w-[25%]">Učebnice</th>
                        <th class="p-4 w-[8%]">Stav</th>
                        <th class="p-4 w-[8%]">Rok tisku</th>
                        <th class="p-4 w-[8%]">Cena</th>
                        <th class="p-4 w-[38%]">Poznámky</th>
                        <th class="p-4 w-[15%] text-center">Vystavit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-4">
                            <select id = "ucebniceSelect" name="id_ucebnice" class="border p-2 w-full" required>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['jmeno']); ?>
                                    </option>
                                <?php } ?>
                                <option value="redirect" class="bg-gray-200 text-center">---- Vložit novou učebnici ----</option>
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
    <h1>nezobrazuje nahled .heic, upravit echos</h1>

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


document.getElementById("ucebniceSelect").addEventListener("change", function() {
    if (this.value === "redirect") {
        window.location.href = "nova.php";
    }
})

</script>


</body>
</html>
