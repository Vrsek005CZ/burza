<?php
require_once "../code/is_admin.php";
require_once "../code/connect.php";
require_once "../code/row_edit.php";

$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$table || !$id) {
    die("Neplatný požadavek.");
}

$tables = getTables($conn);
if (!in_array($table, $tables)) {
    die("Neplatná tabulka.");
}

$rowData = getRowById($conn, $table, $id);
if (!$rowData) {
    die("Záznam nenalezen.");
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    // Získání seznamu sloupců tabulky
    $columns = getTableColumns($conn, $table);

    // Filtrování dat z formuláře
    $data = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, $columns) && $key !== 'id') {
            if ($admintype < 2 && $key == "type") {
                continue; // Pokud není admin, přeskoč typ
            }
            $data[$key] = $value;
        }
    }

    // Aktualizace záznamu
    $message = updateRow($conn, $table, $data, $id);

    // Správa fotek pro tabulky ucebnice a pu
    if ($table == "ucebnice") {
        $message .= " " . manageUcebnicePhoto($id, $_FILES, isset($_POST['removePhoto']));
    } elseif ($table == "pu") {
        $removedImages = !empty($_POST['removedImages']) ? json_decode($_POST['removedImages'], true) : [];
        $message .= " " . managePuPhotos($id, $_FILES, $removedImages);
    }

    // Znovunačtení záznamu po aktualizaci
    $rowData = getRowById($conn, $table, $id);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    $message = deleteRow($conn, $table, $id);
    echo "<script>alert('$message'); window.location.href='superadmin.php?table=" . urlencode($table) . "';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Editace záznamu</title>
</head>
<body class="bg-gray-100 p-4 sm:p-10">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-md p-6">
        <!-- Šipka zpět -->
        <a href="superadmin.php?table=<?php echo urlencode($table); ?>" class="inline-flex items-center text-blue-600 hover:underline mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Zpět na seznam
        </a>
        <h1 class="text-2xl font-bold mb-4">Editace záznamu v tabulce: <?php echo htmlspecialchars($table); ?></h1>
        <?php if (isset($message)): ?>
            <div class="bg-green-200 p-3 rounded mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <?php if ($table == "user"): ?>
            <div class="p-3 rounded mb-4">
                <strong>Typ uživatele:</strong><br>
                0 - Běžný uživatel<br>
                1 - Admin<br>
                2 - Superadmin (může přidávat a mazat adminy)<br>
                -1 - Zabanovaný uživatel<br>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <table class="w-full mb-4">
                <?php foreach ($rowData as $column => $value): ?>
                    <tr>
                        <td class="p-2 font-semibold"><?php echo htmlspecialchars($column); ?></td>
                        <td class="p-2">
                        <?php if ($column == "id"): ?>
                            <input type="text" name="<?php echo htmlspecialchars($column); ?>" value="<?php echo htmlspecialchars($value); ?>" readonly class="border p-1 w-full">
                        <?php elseif ($column == "poznamky"): ?>
                            <textarea name="<?php echo htmlspecialchars($column); ?>" rows="10" class="border p-1 w-full"><?php echo htmlspecialchars($value); ?></textarea>
                        <?php elseif ($column == "type"): ?>
                            <?php if ($admintype > 1): ?>
                                <input type="text" name="<?php echo htmlspecialchars($column); ?>" value="<?php echo htmlspecialchars($value); ?>" class="border p-1 w-full">
                            <?php else: ?>
                                <input type="text" name="<?php echo htmlspecialchars($column); ?>" value="<?php echo htmlspecialchars($value); ?>" readonly class="border p-1 w-full">
                            <?php endif; ?>
                        <?php else: ?>
                            <input type="text" name="<?php echo htmlspecialchars($column); ?>" value="<?php echo htmlspecialchars($value); ?>" class="border p-1 w-full">
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php if ($table == "ucebnice"): ?>
            <!-- Sekce pro fotku u tabulky ucebnice -->
            <div class="bg-gray-100 shadow-md rounded-md p-4 sm:p-8 mx-auto mb-4">
                <div class="text-center font-bold">Fotografie</div>
                <hr>
                <div id="preview" class="flex justify-center mt-2">
                    <?php 
                        $photoPath = "../foto/ucebnice/$id.webp";
                        if (file_exists($photoPath)): 
                    ?>
                        <img src="<?= $photoPath ?>" alt="Foto" id="currentPhoto" class="h-1/3 object-cover border cursor-pointer hover:opacity-70">
                    <?php else: ?>
                        <p id="noPhoto" class="text-center">Žádná fotka.</p>
                    <?php endif; ?>
                </div>
                <div class="mt-4">
                    <label class="block font-semibold">Nahrát novou fotku:</label>
                    <input type="file" id="newPhoto" name="newPhoto" accept="image/*" class="border p-1 w-full">
                </div>
            </div>
        <?php elseif ($table == "pu"): ?>
            <!-- Sekce pro existující fotky -->
            <div class="bg-gray-100 shadow-md rounded-md p-4 sm:p-8 mx-auto">
                <div class="text-center font-bold">Fotky</div>
                <hr>
                <div id="preview" class="flex flex-wrap gap-2 mt-2">
                    <?php 
                        $dir = "../foto/pu/$id"; 
                        $existingImages = glob("$dir/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
                        foreach ($existingImages as $image): ?>
                        <img src="<?= $image ?>" data-file="<?= basename($image) ?>" class="preview-img h-[24vh] object-cover border cursor-pointer hover:opacity-70">
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Sekce pro nahrání nových fotek -->
            <div class="bg-gray-100 shadow-md rounded-md p-4 sm:p-8 mx-auto mt-4">
                <div class="text-center font-bold">Přidat nové fotky</div>
                <hr>
                <input type="file" name="newFiles[]" id="fileInput" accept="image/*" multiple class="p-2 w-full">
                <div id="newPreview" class="flex flex-wrap gap-2 mt-2"></div>
            </div><br>
        <?php endif; ?>
        <div class="flex gap-4">
            <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Upravit</button>
            <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Opravdu chcete smazat tento záznam?');">Smazat</button>
        </div>
        </form>
    </div>
<?php if ($table == "pu"): ?>
    <script src="../code/row_edit_pu.js"></script>
<?php elseif ($table == "ucebnice"): ?>
    <script src="../code/row_edit_ucebnice.js"></script>
<?php endif; ?>
</body>

</html>