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
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $cena = intval($_POST['cena']);
        $poznamky = trim($_POST['poznamky']);

        // Kontrola, zda cena není negativní
        if ($cena < 0) {
            $message = "❌ Cena nemůže být záporná.";
        } else {
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

            $message = "✅ Změny byly úspěšně uloženy.";
        }
    }

    if (isset($_POST['delete'])) {
        deleteBook($conn, $puID);
        $message = "✅ Učebnice byla úspěšně smazána.";
        echo "<script>alert('$message'); window.location.href='profil.php';</script>";
        exit;
    }
}
?>

<div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
  <!-- Zobrazení zprávy -->
  <?php if (!empty($message)): ?>
    <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <!-- Šipka zpět -->
  <a href="profil.php" class="inline-flex items-center text-blue-600 hover:underline mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Zpět na profil
  </a>
  <form method="post" enctype="multipart/form-data">
    <div class="overflow-x-auto">
      <table class="w-full bg-gray-50 shadow-md rounded-lg">
        <thead class="hidden sm:table-header-group bg-gray-200">
          <tr>
            <th class="p-4 w-[25%]">Učebnice</th>
            <th class="p-4 w-[8%]">Cena</th>
            <th class="p-4 w-[38%]">Poznámky</th>
            <th class="p-4 w-[15%] text-center">Upravit</th>
            <th class="p-4 w-[15%] text-center">Smazat</th>
          </tr>
        </thead>
        <tbody>
          <tr class="sm:table-row flex flex-col sm:flex-row sm:items-center sm:justify-between border-b sm:border-none">
            <td class="p-4 sm:table-cell flex flex-col sm:flex-none">
              <span class="block sm:hidden font-bold">Učebnice:</span>
              <?= htmlspecialchars($pu['nazev']); ?>
            </td>
            <td class="p-1 sm:table-cell flex flex-col sm:flex-none">
              <span class="block sm:hidden font-bold">Cena:</span>
              <input type="number" name="cena" class="border p-2 w-full" value="<?= htmlspecialchars($pu['cena']); ?>" required>
            </td>
            <td class="p-1 sm:table-cell flex flex-col sm:flex-none">
              <span class="block sm:hidden font-bold">Poznámky:</span>
              <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"><?= htmlspecialchars($pu['poznamky']); ?></textarea>
            </td>
            <td class="p-1 sm:table-cell flex flex-col sm:flex-none text-center">
              <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Upravit</button>
            </td>
            <td class="p-1 sm:table-cell flex flex-col sm:flex-none text-center">
              <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded"
                      onclick="return confirm('Opravdu chcete smazat tuto učebnici?');">Smazat</button>
            </td>
          </tr>
        </tbody>
      </table>
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