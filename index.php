<?php
session_start();
require_once "code/userinfo.php";

require_once "header.php";
getHeader("Hlavní stránka"); 

require_once "code/index_logic.php";
?>

<!-- Filtry pro vyhledávání a filtrování knih -->  
<div class="p-4">
    <input type="text" id="searchInput" placeholder="Hledat učebnici..." class="p-2 border rounded w-full mb-2">

        <select id="categoryFilter" class="p-2 border rounded w-full mb-2">
            <option value="">Všechny kategorie</option>
            <?php
            $kategorie = getKategorie($conn);
            foreach ($kategorie as $cat) {
                echo "<option value='" . htmlspecialchars($cat['nazev']) . "'>" . htmlspecialchars($cat['nazev']) . "</option>";
            }
            ?>
        </select>

        <select id="gradeFilter" class="p-2 border rounded w-full mb-2">
            <option value="">Všechny ročníky</option>
            <?php
            $tridy = getTridy($conn);
            foreach ($tridy as $grade) {
                echo "<option value='" . htmlspecialchars($grade['trida_id']) . "'>" . htmlspecialchars($grade['trida_id']) . ". ročník</option>";
            }
            ?>
        </select>
    </input>
    <div class="inline-flex items-center space-x-1 p-2 border rounded">
        <label for="toggleHeight" class="mr-2 font-semibold">Zobrazit celé texty:</label>
        <button type="button" name="toggleHeight" id="toggleHeight" class="p-2 rounded hover:text-blue-500">
            ◇
        </button>
    </div>
</div>

<div id="booksContainer" class="grid gap-4 p-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">

    <!-- Kniha z SQL -->
    <?php
    $ucebnice = getUcebnice($conn); // Získání dat jako pole
    if (!empty($ucebnice)) { // Kontrola, zda pole není prázdné
        foreach ($ucebnice as $row) { // Iterace přes pole
            echo 
            "<a href='pages/ucebnice.php?knihaID=" . htmlspecialchars($row['id']) . "' class='book bg-gray-200 p-4 rounded-md items-center' data-category='" . htmlspecialchars($row['kategorie_nazev']) . "' data-grade='" . htmlspecialchars($row['trida_id']) . "'>
                <div class='bg-gray-100 rounded-md'>
                    <div class='text-l text-center font-semibold m-1 p-1 h-12 hover:h-full break-words'>"
                        . htmlspecialchars($row['ucebnice_nazev']) . 
                    "</div>
                    <div class='h-50'>
                        <img src='foto/ucebnice/" . htmlspecialchars($row['id']) . ".webp' class='rounded-lg p-1 w-full h-48 object-cover'>
                    </div>
                    <div class='text-s m-1 p-1'>
                        Počet ks: <span class='font-medium'>" . htmlspecialchars($row['pocet_ks']) . "</span><br>
                        Avg cena: <span class='font-medium'>" . number_format($row['avg_cena'], 0, ',', '.') . "</span>
                    </div>
                </div> 
                <div class='text-xs p-1'>"
                    . htmlspecialchars($row['kategorie_nazev']) . "<br>" . htmlspecialchars($row['trida_id']) . ". ročník<br>"
                    . htmlspecialchars($row['typ_nazev']) . 
                "</div>
            </a>";
        }
    } else {
        echo '<p>Žádné učebnice k dispozici.</p>';
    }
    ?>
</div>

<script>
//vše je tady, protože z nějakého důvodu to nefunguje, když se to načítá odjinud
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("toggleHeight");
    if (toggleBtn) {
        toggleBtn.addEventListener("click", toggleFullHeight);
    }
});

function toggleFullHeight() {
    const bookTitles = document.querySelectorAll('.text-l');
    const toggleHeightButton = document.getElementById('toggleHeight');

    toggleHeightButton.classList.toggle('text-blue-600');
    toggleHeightButton.innerHTML = toggleHeightButton.classList.contains('text-blue-600') ? '◆' : '◇';

    bookTitles.forEach(title => {
        title.classList.toggle('h-12');
        title.classList.toggle('h-full');
    });
}

document.getElementById('searchInput').addEventListener('input', filterBooks);
document.getElementById('categoryFilter').addEventListener('change', filterBooks);
document.getElementById('gradeFilter').addEventListener('change', filterBooks);

function filterBooks() {
    // Získání vstupních hodnot
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedCategory = document.getElementById('categoryFilter').value;
    const selectedGrade = document.getElementById('gradeFilter').value;
    const books = document.querySelectorAll('.book'); // Vrátí NodeList obsahující všechny prvky s třídou .book

    books.forEach(book => {
        const bookTitle = book.querySelector('.text-l').textContent.toLowerCase(); // Získá název knihy z prvku s třídou .text-l
        const bookCategory = book.getAttribute('data-category'); // Získá hodnotu atributu data-category z prvku knihy
        const bookGrade = book.getAttribute('data-grade'); // Získá hodnotu atributu data-grade

        if (
            bookTitle.includes(searchText) && // Ověří, zda název knihy obsahuje hledaný text
            (selectedCategory === "" || bookCategory === selectedCategory) && // Když kategorie prázdná, nefiltrujeme
            (selectedGrade === "" || bookGrade === selectedGrade) // Když ročník prázdný, nefiltrujeme
        ) {
            book.style.display = "block";
        } else {
            book.style.display = "none";
        }
    });
}
</script>


</body>
</html>
