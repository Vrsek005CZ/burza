<?php
session_start();
require_once "../code/connect.php";
$pageTitle = "Prodat"; 
require_once "../header.php";

require_once "../code/sell.php";

?>


    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

    <!-- jQuery (pokud ještě není) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
        <form method="POST" action="../code/pridat_knihu.php" enctype="multipart/form-data">
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

<script src="../code/sell.js"></script>


</body>
</html>
