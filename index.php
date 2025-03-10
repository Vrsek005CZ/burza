<?php
session_start();
include("connect.php");
include("userinfo.php");
$pageTitle = "Hlavní stránka"; 
include("header.php");
?>

    <!-- Filtry pro vyhledávání a filtrování knih -->  
    <div class="p-4">
        <input type="text" id="searchInput" placeholder="Hledat učebnici..." class="p-2 border rounded w-full mb-2">

        <select id="categoryFilter" class="p-2 border rounded w-full">
            <option value="">Všechny kategorie</option>
            <?php
            $categoryQuery = "SELECT id, nazev FROM kategorie";
            $categoryResult = $conn->query($categoryQuery);
            while ($cat = $categoryResult->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($cat['nazev']) . "'>" . htmlspecialchars($cat['nazev']) . "</option>";
            }
            ?>
        </select>

        <select id="gradeFilter" class="p-2 border rounded w-full">
            <option value="">Všechny ročníky</option>
            <?php
            $gradeQuery = "SELECT DISTINCT trida_id FROM ucebnice ORDER BY trida_id";
            $gradeResult = $conn->query($gradeQuery);
            while ($grade = $gradeResult->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($grade['trida_id']) . "'>" . htmlspecialchars($grade['trida_id']) . ". ročník</option>";
            }
            ?>
        </select>
    </div>
    
    <div id="booksContainer" class="grid gap-4 p-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">

        <!-- Kniha z SQL -->
        <?php
        $query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, 
        COUNT(CASE WHEN pu.koupil = 0 THEN 1 END) AS pocet_ks, 
        ROUND(AVG(CASE WHEN pu.koupil = 0 THEN pu.cena END)) AS avg_cena 
        FROM ucebnice 
        INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id 
        INNER JOIN typ ON ucebnice.typ_id=typ.id 
        INNER JOIN pu ON ucebnice.id=pu.id_ucebnice 
        GROUP BY ucebnice.id
        ORDER BY pocet_ks DESC";
        
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo 

                "<a href='ucebnice.php?knihaID=" . $row['id'] . "' class='book bg-gray-200 p-4 rounded-md items-center rounded-md' data-category='" . htmlspecialchars($row['kategorie_nazev']) . "' data-grade='" . htmlspecialchars($row['trida_id']) . "'>
                    <div class='bg-gray-100 rounded-md'>
                        <div class='text-l text-center font-semibold m-1 p-1 h-12 hover:h-full'>"
                            . htmlspecialchars($row['ucebnice_nazev']) . 
                        "</div>
                        <div class='h-50'>
                            <img src='foto/ucebnice/" . $row['id'] . ".webp' class='rounded-lg p-1 w-full h-48 object-cover'>
                        </div>
                        <div class='text-s m-1 p-1'>
                            Počet ks: <span class='font-medium'>" . $row['pocet_ks'] . "</span><br>
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
    

    <h1>Nejde spustit jinde nez na serveru > v googlu odkaz na localhost, ale v jinde 10.0.0.13 . Doladit web, jako třeba šipku zpět. Všude pro sql pridat bind params</h1>

</div>

<script>



//funkce pro filtrování knih podle vyhledávání, kategorie a ročníku 

document.getElementById('searchInput').addEventListener('input', filterBooks);
document.getElementById('categoryFilter').addEventListener('change', filterBooks);
document.getElementById('gradeFilter').addEventListener('change', filterBooks);

function filterBooks() {
    //Získání vstupních hodnot
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedCategory = document.getElementById('categoryFilter').value;
    const selectedGrade = document.getElementById('gradeFilter').value;
    const books = document.querySelectorAll('.book'); //vrátí NodeList obsahující všechny prvky s třídou .book

    books.forEach(book => {
        const bookTitle = book.querySelector('.text-l').textContent.toLowerCase(); //Získá název knihy z prvku s třídou .text-l
        const bookCategory = book.getAttribute('data-category'); //Získá hodnotu atributu data-category z prvku knihy
        const bookGrade = book.getAttribute('data-grade'); //Získá hodnotu atributu data-grade

        if (
            bookTitle.includes(searchText) && //Ověří, zda název knihy obsahuje hledaný text
            (selectedCategory === "" || bookCategory === selectedCategory) && //Když kategorie prázdná, nefiltrujeme
            (selectedGrade === "" || bookGrade === selectedGrade) //když ročník prázdný, nefiltrujeme
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
