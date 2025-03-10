<?php

include("connect.php");
include("userinfo.php");
include("upravit_knihu.php");
$pageTitle = "Úprava učebnice"; 
include("header.php");

if (!isset($_GET['puID'])) {
    die("Chybějící ID učebnice.");
}

$puID = intval($_GET['puID']);
$sql = "SELECT pu.cena as cena, pu.poznamky as poznamky, ucebnice.jmeno as nazev, pu.id_prodejce as prodejce
        FROM pu 
        INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id 
        WHERE pu.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $puID);
$stmt->execute();
$result = $stmt->get_result();
$pu = $result->fetch_assoc();

if (!$pu) {
    die("Učebnice nenalezena.");
}
if ($userId != $pu['prodejce']) {
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
}

$dir = "foto/pu/$puID";
// Získáme existující fotky – včetně souborů s příponou .webp
$existingImages = glob("$dir/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
?>
    
    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
      <form method="post" enctype="multipart/form-data" action="upravit_knihu.php?puID=<?= $puID ?>">
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
        <br>
        <!-- Sekce pro existující fotky -->
        <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
          <div class="text-center font-bold">Fotky</div>
          <hr>
          <div id="preview" class="flex flex-wrap gap-2 mt-2">
            <?php foreach ($existingImages as $image): ?>
              <img src="<?= $image ?>" data-file="<?= basename($image) ?>" class="preview-img h-[24vh] object-cover border cursor-pointer hover:opacity-70">
            <?php endforeach; ?>
          </div>
        </div>
        <br>
        <!-- Sekce pro nahrání nových fotek -->
        <div class="bg-gray-100 shadow-md rounded-md p-8 mx-auto">
          <div class="text-center font-bold">Přidat nové fotky</div>
          <hr>
          <input type="file" name="newFiles[]" id="fileInput" accept="image/*" multiple class="p-2 w-full">
          <div id="newPreview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>
      </form>
    </div>
  </div>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
      let removedImages = [];
      // Použijeme DataTransfer pro manipulaci se soubory u inputu
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

      // Mazání existujících fotek – kliknutím na fotku v sekci "Fotky"
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
</body>
</html>
