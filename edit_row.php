<?php
session_start();
include("is_admin.php");
include("connect.php");

// Kontrola povinných parametrů
if (!isset($_GET['table']) || !isset($_GET['id'])) {
    die("Chybějící parametry.");
}
$table = $_GET['table'];
$id = intval($_GET['id']);

// Ověříme, že tabulka existuje
$tables = [];
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    $tables[] = $row[0];
}
if (!in_array($table, $tables)) {
    die("Neplatná tabulka.");
}

// Načtení záznamu
$sql = "SELECT * FROM `$table` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$rowData = $result->fetch_assoc();
if (!$rowData) {
    die("Záznam nenalezen.");
}

// Zpracování formuláře pro update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Aktualizace textových hodnot (všech sloupců kromě id)
    $updateParts = [];
    $params = [];
    $types = "";
    foreach ($rowData as $column => $value) {
        if ($column == "id") continue;
        if (isset($_POST[$column])) {
            $newValue = $_POST[$column];
            $updateParts[] = "`$column` = ?";
            $params[] = $newValue;
            $types .= "s"; // použijeme řetězec jako typ (lze případně doladit)
        }
    }
    if (!empty($updateParts)) {
        $sqlUpdate = "UPDATE `$table` SET " . implode(", ", $updateParts) . " WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $types .= "i";
        $params[] = $id;
        $stmtUpdate->bind_param($types, ...$params);
        $stmtUpdate->execute();
        $message = "Záznam byl aktualizován.";
    }

    // Správa fotek pro tabulky ucebnice a pu
    if ($table == "ucebnice") {
        // Cesta ke stávající fotce
        $photoPath = "foto/ucebnice/$id.webp";
    
        // Pokud je zatrženo odstranění fotky, smažeme ji, pokud existuje
        if (isset($_POST['removePhoto'])) {
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
    
        // Zpracování nahrané nové fotky
        if (isset($_FILES['newPhoto']) && $_FILES['newPhoto']['error'] == UPLOAD_ERR_OK) {
            $tmpName = $_FILES['newPhoto']['tmp_name'];
            try {
                // Pokusíme se o konverzi pomocí Imagick do formátu WebP
                $image = new Imagick($tmpName);
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality(90);
                $image->writeImage($photoPath);
                $image->clear();
                $image->destroy();
            } catch (Exception $e) {
                // V případě chyby provedeme klasické přesunutí souboru
                move_uploaded_file($tmpName, $photoPath);
            }
        }
        
    } elseif ($table == "pu") {
        $dir = "foto/pu/$id";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        // Odstranění vybraných fotek
        if (!empty($_POST['removedImages'])) {
            $removedImages = json_decode($_POST['removedImages'], true);
            if (is_array($removedImages)) {
                foreach ($removedImages as $filename) {
                    $filePath = "$dir/" . basename($filename);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        // Zpracování nahraných nových fotek
        if (!empty($_FILES['newFiles']['name'][0])) {
            foreach ($_FILES['newFiles']['tmp_name'] as $key => $tmpName) {
                if (!empty($tmpName)) {
                    $newName = uniqid() . ".webp";
                    $targetPath = "$dir/$newName";
                    try {
                        $image = new Imagick($tmpName);
                        $image->setImageFormat('webp');
                        $image->setImageCompressionQuality(80);
                        $image->writeImage($targetPath);
                        $image->clear();
                        $image->destroy();
                    } catch (Exception $e) {
                        // Pokud konverze selže, provedeme klasické přesunutí souboru
                        move_uploaded_file($tmpName, $targetPath);
                    }
                }
            }
        }
    }
    // Znovunačtení záznamu po aktualizaci
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rowData = $result->fetch_assoc();
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
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-md p-6">
        <h1 class="text-2xl font-bold mb-4">Editace záznamu v tabulce: <?php echo htmlspecialchars($table); ?></h1>
        <?php if (isset($message)): ?>
            <div class="bg-green-200 p-3 rounded mb-4">
                <?php echo htmlspecialchars($message); ?>
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
                        <?php else: ?>
                            <input type="text" name="<?php echo htmlspecialchars($column); ?>" value="<?php echo htmlspecialchars($value); ?>" class="border p-1 w-full">
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php if ($table == "ucebnice"): ?>
            <!-- Sekce pro fotku u tabulky ucebnice -->
            <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto mb-4">
                <div class="text-center font-bold">Fotografie</div>
                <hr>
                <div id="preview" class="flex justify-center mt-2">
                    <?php 
                        $photoPath = "foto/ucebnice/$id.webp";
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
            <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
                <div class="text-center font-bold">Fotky</div>
                <hr>
                <div id="preview" class="flex flex-wrap gap-2 mt-2">
                    <?php 
                        $dir = "foto/pu/$id"; 
                        $existingImages = glob("$dir/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
                        foreach ($existingImages as $image): ?>
                        <img src="<?= $image ?>" data-file="<?= basename($image) ?>" class="preview-img h-[24vh] object-cover border cursor-pointer hover:opacity-70">
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Sekce pro nahrání nových fotek -->
            <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto mt-4">
                <div class="text-center font-bold">Přidat nové fotky</div>
                <hr>
                <input type="file" name="newFiles[]" id="fileInput" accept="image/*" multiple class="p-2 w-full">
                <div id="newPreview" class="flex flex-wrap gap-2 mt-2"></div>
            </div><br>
        <?php endif; ?>
        <div class="flex gap-4">
            <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Upravit</button>
            <a href="superadmin.php?table=<?php echo urlencode($table); ?>" class="bg-gray-500 text-white px-4 py-2 rounded">Zpět</a>
        </div>
        </form>
    </div>
<?php if ($table == "pu"): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let removedImages = [];
            let dt = new DataTransfer();
            const fileInput = document.querySelector("#fileInput");

            // Při změně inputu přidáme soubory do DataTransfer a vykreslíme jejich náhledy
            fileInput.addEventListener("change", function () {
                for (let i = 0; i < fileInput.files.length; i++) {
                    dt.items.add(fileInput.files[i]);
                }
                fileInput.files = dt.files;
                renderNewPreviews();
            });

            function renderNewPreviews() {
                const previewDiv = document.querySelector("#newPreview");
                previewDiv.innerHTML = "";
                Array.from(dt.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("preview-img", "h-[24vh]", "object-cover", "border", "cursor-pointer", "hover:opacity-70");
                        img.dataset.index = index;
                        img.addEventListener("click", function () {
                            removeNewFile(index);
                        });
                        previewDiv.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function removeNewFile(removeIndex) {
                let newDt = new DataTransfer();
                Array.from(dt.files).forEach((file, index) => {
                    if (index != removeIndex) {
                        newDt.items.add(file);
                    }
                });
                dt = newDt;
                fileInput.files = dt.files;
                renderNewPreviews();
            }

            // Odstranění existujících fotek kliknutím na jejich náhled
            document.querySelector("#preview").addEventListener("click", function (event) {
                if (event.target.classList.contains("preview-img")) {
                    let fileName = event.target.getAttribute("data-file");
                    removedImages.push(fileName);
                    event.target.remove();
                }
            });

            // Před odesláním formuláře přidáme skrytý input s JSON-encoded polem odstraněných fotek
            document.querySelector("form").addEventListener("submit", function () {
                let input = document.createElement("input");
                input.type = "hidden";
                input.name = "removedImages";
                input.value = JSON.stringify(removedImages);
                this.appendChild(input);
            });
        });
    </script>


<?php elseif ($table == "ucebnice"): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const newPhotoInput = document.getElementById('newPhoto');
            const previewContainer = document.getElementById('preview');
            
            // Zpracování nově vybrané fotky
            newPhotoInput.addEventListener('change', function(event) {
                previewContainer.innerHTML = "";
                const file = event.target.files[0];
                if (file && /^image\//.test(file.type)) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add("object-cover", "border", "cursor-pointer", "hover:opacity-70");
                        // Kliknutím odstraníme náhled a resetujeme vstup
                        img.addEventListener("click", function() {
                            newPhotoInput.value = "";
                            previewContainer.innerHTML = "<p id='noPhoto' class='text-center'>Žádná fotka.</p>";
                        });
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML = "<p id='noPhoto' class='text-center'>Žádná fotka.</p>";
                }
            });
            
            // Pokud je zobrazena aktuální fotka, kliknutím na ni ji odstraníme a nastavíme příznak pro odstranění
            const currentPhoto = document.getElementById('currentPhoto');
            if (currentPhoto) {
                currentPhoto.addEventListener("click", function() {
                    previewContainer.innerHTML = "<p id='noPhoto' class='text-center'>Žádná fotka.</p>";
                    // Přidáme skrytý input pro odstranění původní fotky
                    let removeInput = document.createElement("input");
                    removeInput.type = "hidden";
                    removeInput.name = "removePhoto";
                    removeInput.value = "1";
                    document.querySelector("form").appendChild(removeInput);
                });
            }
        });

    </script>

<?php endif; ?>
</body>
</html>
