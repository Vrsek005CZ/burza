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
    <form method="post" class="inline-flex items-center space-x-1 p-2 border rounded bg-gray-100">
        <label for="toggleHeight" class="mr-2 font-semibold">Zobrazit celé texty:</label>
        <button type="submit" name="toggleHeight" id="toggleHeight" class="p-2 rounded hover:text-blue-500 <?php echo isset($_SESSION['fullHeight']) && $_SESSION['fullHeight'] ? 'text-blue-600' : 'text-black'; ?>">
            <?php echo isset($_SESSION['fullHeight']) && $_SESSION['fullHeight'] ? '◆' : '◇'; ?>
        </button>
    </form>
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
                    <div class='text-l text-center font-semibold m-1 p-1 $fullHeightClass hover:h-full break-words'>"
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

<h1>Nejde spustit jinde nez na serveru > v googlu odkaz na localhost, ale v jinde 10.0.0.13 . Doladit web, jako třeba šipku zpět. Všude pro sql pridat bind params</h1>


<script src="code/index.js"></script>

</body>
</html>
