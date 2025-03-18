<?php
session_start();
require_once "code/userinfo.php";
$pageTitle = "Hlavní stránka"; 
require_once "header.php";

require_once "code/index_logic.php";
//get_header("HlAVNÍ STRÁNKA");
?>

<!-- Filtry pro vyhledávání a filtrování knih -->  
<div class="p-4">
    <input type="text" id="searchInput" placeholder="Hledat učebnici..." class="p-2 border rounded w-full mb-2">

    <select id="categoryFilter" class="p-2 border rounded w-full">
        <option value="">Všechny kategorie</option>
        <?php
        while ($cat = $categoryResult->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($cat['nazev']) . "'>" . htmlspecialchars($cat['nazev']) . "</option>";
        }
        ?>
    </select>

    <select id="gradeFilter" class="p-2 border rounded w-full">
        <option value="">Všechny ročníky</option>
        <?php
        while ($grade = $gradeResult->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($grade['trida_id']) . "'>" . htmlspecialchars($grade['trida_id']) . ". ročník</option>";
        }
        ?>
    </select>
</div>

<div id="booksContainer" class="grid gap-4 p-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">

    <!-- Kniha z SQL -->
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo 

            "<a href='pages/ucebnice.php?knihaID=" . $row['id'] . "' class='book bg-gray-200 p-4 rounded-md items-center rounded-md' data-category='" . htmlspecialchars($row['kategorie_nazev']) . "' data-grade='" . htmlspecialchars($row['trida_id']) . "'>
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

<script src="code/index.js"></script>


</body>
</html>
