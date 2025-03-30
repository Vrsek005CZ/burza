<?php
require_once "../code/connect.php";
require_once "../code/userinfo.php";
require_once "../code/edit_book.php";

require_once "../header.php";
getHeader("Upravit");

// Získání ID učebnice z GET parametru
if (!isset($_GET['puID'])) {
    die("Chybějící ID učebnice.");
}

$puID = intval($_GET['puID']);

// Načtení detailu učebnice
$pu = getBookDetail($conn, $puID);
if (!$pu) {
    die("Učebnice nenalezena.");
}

// Ověření, zda je uživatel vlastníkem učebnice
if ($userId != $pu['prodejce']) {
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
}

// Načtení existujících obrázků
$existingImages = getExistingImages($puID);

// Zpracování POST požadavků
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $cena = intval($_POST['cena']);
        $poznamky = trim($_POST['poznamky']);
        updateBook($conn, $puID, $cena, $poznamky);

        // Odstranění existujících fotek
        if (!empty($_POST['removedImages'])) {
            $removedImages = json_decode($_POST['removedImages'], true);
            if (is_array($removedImages)) {
                foreach ($removedImages as $file) {
                    $filePath = "../foto/pu/$puID/" . basename($file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }

        // Nahrání nových fotek
        if (!empty($_FILES['newFiles']['name'][0])) {
            uploadNewImages($puID, $_FILES['newFiles']);
        }

        ##header("Location: ../pages/profil.php");
        exit;
    }

    if (isset($_POST['delete'])) {
        deleteBook($conn, $puID);
        ##header("Location: ../pages/profil.php");
        exit;
    }
}
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
  <form method="post" enctype="multipart/form-data">
    <div class="hidden sm:block">
      <table class="w-full bg-gray-50 shadow-md rounded-lg">
        <thead class="text-left bg-gray-200">
          <tr>
            <th class="p-4 w-[25%]">Učebnice</th>
            <th class="p-4 w-[8%]">Cena</th>
            <th class="p-4 w-[38%]">Poznámky</th>
            <th class="p-4 w-[15%] text-center">Upravit</th>
            <th class="p-4 w-[15%] text-center">Smazat</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="p-4"><?= htmlspecialchars($pu['nazev']); ?></td>
            <td class="p-1">
              <input type="number" name="cena" class="border p-2 w-full" value="<?= htmlspecialchars($pu['cena']); ?>" required>
            </td>
            <td class="p-1">
              <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"><?= htmlspecialchars($pu['poznamky']); ?></textarea>
            </td>
            <td class="p-1 text-center">
              <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Upravit</button>
            </td>
            <td class="p-1 text-center">
              <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded"
                      onclick="return confirm('Opravdu chcete smazat tuto učebnici?');">Smazat</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Mobilní verze -->
    <div class="block sm:hidden space-y-4">
      <div class="bg-gray-50 p-4 rounded-lg shadow-md">
        <div><strong>Učebnice:</strong><br><?= htmlspecialchars($pu['nazev']); ?></div>
        <div>
          <label class="block font-semibold mt-2">Cena:</label>
          <input type="number" name="cena" class="border p-2 w-full" value="<?= htmlspecialchars($pu['cena']); ?>" required>
        </div>
        <div>
          <label class="block font-semibold mt-2">Poznámky:</label>
          <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"><?= htmlspecialchars($pu['poznamky']); ?></textarea>
        </div>
        <div class="flex justify-between mt-4">
          <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded w-[48%]">Upravit</button>
          <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded w-[48%]"
                  onclick="return confirm('Opravdu chcete smazat tuto učebnici?');">Smazat</button>
        </div>
      </div>
    </div>

    <br>
    <!-- Sekce pro existující fotky -->
    <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
      <div class="text-center font-bold">Fotky</div>
      <hr>
      <div id="preview" class="flex flex-wrap gap-2 mt-2 justify-center">
        <?php foreach ($existingImages as $image): ?>
          <img src="<?= $image ?>" data-file="<?= basename($image) ?>" class="preview-img h-[24vh] object-cover border cursor-pointer hover:opacity-70 rounded-md">
        <?php endforeach; ?>
      </div>
    </div>
    <br>
    <!-- Sekce pro nahrání nových fotek -->
    <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
      <div class="text-center font-bold">Přidat nové fotky</div>
      <hr>
      <input type="file" name="newFiles[]" id="fileInput" accept="image/*" multiple class="p-2 w-full">
      <div id="newPreview" class="flex flex-wrap gap-2 mt-2 justify-center"></div>
    </div>
  </form>
</div>
<script src="../code/upravit_knihu.js"></script>
</body>
</html>
