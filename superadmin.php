<?php
// Spuštění session, což umožňuje správu uživatelských relací
session_start();

// Zahrnutí skriptů pro ověření administrátorských práv a připojení k databázi
include("is_admin.php");
include("connect.php");

// -------------------------------
// Načtení seznamu všech tabulek v databázi
// -------------------------------

// Inicializujeme prázdné pole pro uložení názvů tabulek
$tables = [];

// Provedeme SQL dotaz, který získá seznam tabulek v aktuální databázi
$result = mysqli_query($conn, "SHOW TABLES");

// Iterujeme přes výsledky dotazu a ukládáme název každé tabulky do pole $tables
while ($row = mysqli_fetch_array($result)) {
    $tables[] = $row[0]; // Název tabulky je v prvním sloupci výsledku
}

// -------------------------------
// Zpracování výběru aktuálně zvolené tabulky
// -------------------------------

// Získáme jméno tabulky z GET parametru, pokud je nastaven; jinak nastavíme prázdný řetězec
$selected_table = isset($_GET['table']) ? $_GET['table'] : "";

// Inicializujeme prázdná pole pro sloupce a řádky vybrané tabulky
$table_columns = [];
$table_rows = [];

// Pokud byl vybrán nějaký název tabulky a tento název skutečně existuje v databázi...
if ($selected_table && in_array($selected_table, $tables)) {
    // -------------------------------
    // Načtení názvů sloupců vybrané tabulky
    // -------------------------------
    // Provedeme SQL dotaz, který získá informace o sloupcích z vybrané tabulky
    $col_result = mysqli_query($conn, "SHOW COLUMNS FROM `$selected_table`");
    
    // Iterujeme přes výsledky dotazu a ukládáme název každého sloupce (pole 'Field') do pole $table_columns
    while ($col = mysqli_fetch_assoc($col_result)) {
        $table_columns[] = $col['Field'];
    }
    
    // -------------------------------
    // Načtení všech řádků z vybrané tabulky
    // -------------------------------
    // Provedeme SQL dotaz, který získá všechny záznamy z vybrané tabulky
    $row_result = mysqli_query($conn, "SELECT * FROM `$selected_table`");
    
    // Iterujeme přes každý řádek výsledku a přidáváme ho do pole $table_rows
    while ($row = mysqli_fetch_assoc($row_result)) {
        $table_rows[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <!-- Nastavení viewportu pro responzivní design -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Načtení TailwindCSS přes CDN pro rychlé stylování -->
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Superadmin - Seznam záznamů</title>
</head>
<body class="bg-gray-100 min-h-screen p-10">
  <div class="max-w-7xl mx-auto">
    <!-- ========================================= -->
    <!-- Hlavička stránky -->
    <!-- ========================================= -->
    <div class="bg-white shadow-md rounded-md p-5 mb-5">
      <h1 class="text-3xl font-bold text-yellow-600 text-center">
        Online Burza Učebnic - Superadmin
      </h1>
    </div>
    
    <!-- ========================================= -->
    <!-- Formulář pro výběr tabulky -->
    <!-- ========================================= -->
    <div class="bg-white shadow-md rounded-md p-5 mb-5">
      <!-- Formulář odesílá GET požadavek na tento soubor (superadmin.php) -->
      <form method="GET" action="superadmin.php">
        <!-- Popisek výběru tabulky -->
        <label for="table" class="block mb-2 font-semibold">Vyberte tabulku:</label>
        <!-- Výběrový prvek, který obsahuje seznam všech tabulek -->
        <select name="table" id="table" class="border p-2 rounded w-full">
          <?php foreach ($tables as $tbl): ?>
            <!-- Každá možnost obsahuje název tabulky, který je ošetřen funkcí htmlspecialchars -->
            <option value="<?php echo htmlspecialchars($tbl); ?>" <?php if($tbl == $selected_table) echo 'selected'; ?>>
              <?php echo htmlspecialchars($tbl); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <!-- Tlačítko pro odeslání formuláře, čímž se načtou data z vybrané tabulky -->
        <button type="submit" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded">
          Načíst data
        </button>
      </form>
    </div>
    
    <!-- ========================================= -->
    <!-- Zobrazení seznamu záznamů z vybrané tabulky -->
    <!-- ========================================= -->
    <?php if ($selected_table && in_array($selected_table, $tables)): ?>
      <div class="bg-white shadow-md rounded-md p-5">
        <!-- Nadpis s názvem aktuálně zvolené tabulky -->
        <h2 class="text-2xl font-semibold mb-4">
          Data z tabulky: <?php echo htmlspecialchars($selected_table); ?>
        </h2>
        <!-- Kontejner pro tabulku, který zaručuje, že nebude přesahovat rodičovský element -->
        <div class="w-full" style="max-width: 100%;">
          <!-- Samotná HTML tabulka bez pevně stanovené šířky, takže se šířku přizpůsobí obsahu -->
          <table class="table-auto border-collapse w-full" style="max-width: 100%;">
            <thead>
              <tr>
                <!-- Generování záhlaví tabulky podle názvů sloupců -->
                <?php foreach ($table_columns as $column): ?>
                  <th class="border px-4 py-2 bg-gray-200">
                    <?php echo htmlspecialchars($column); ?>
                  </th>
                <?php endforeach; ?>
                <!-- Dodatečný sloupec pro akce (například editace záznamu) -->
                <th class="border px-4 py-2 bg-gray-200">Akce</th>
              </tr>
            </thead>
            <tbody>
              <!-- Iterace přes všechny řádky získané z databáze -->
              <?php foreach ($table_rows as $row): ?>
                <tr>
                  <!-- Iterace přes všechny sloupce daného řádku -->
                  <?php foreach ($table_columns as $column): ?>
                    <td class="border px-4 py-2">
                      <!-- Vnitřní div s maximální šířkou. Pokud je text příliš dlouhý, bude zalomen -->
                      <div style="max-width: 200px; white-space: normal; word-wrap: break-word;">
                        <?php echo htmlspecialchars($row[$column]); ?>
                      </div>
                    </td>
                  <?php endforeach; ?>
                  <!-- Sloupec s akcemi, zde konkrétně odkaz na editaci záznamu -->
                  <td class="border px-4 py-2 justify-center text-center">
                    <!-- Odkaz vede na stránku edit_row.php a předává GET parametry: jméno tabulky a ID záznamu -->
                    <a href="edit_row.php?table=<?php echo urlencode($selected_table); ?>&id=<?php echo urlencode($row['id']); ?>"
                      class="bg-green-500 text-white px-4 py-2 rounded">
                      Edit
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
