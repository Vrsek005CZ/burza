<?php
require_once "../code/row_edit.php";
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
            <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
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
    <script>
        const table = "<?php echo $table; ?>";
    </script>
    <script src="../code/row_row.js"></script>
</body>
</html>
