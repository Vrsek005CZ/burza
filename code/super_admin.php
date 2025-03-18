<?php
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