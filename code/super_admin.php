<?php
require_once "connect.php";

// -------------------------------
// Načtení seznamu všech tabulek v databázi
// -------------------------------

// Inicializujeme prázdné pole pro uložení názvů tabulek
$tables = [];

// Provedeme SQL dotaz, který získá seznam tabulek v aktuální databázi
$result = $conn->query("SHOW TABLES");

// Iterujeme přes výsledky dotazu a ukládáme název každé tabulky do pole $tables
while ($row = $result->fetch_array()) {
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
    $col_result = $conn->prepare("SHOW COLUMNS FROM `$selected_table`");
    $col_result->execute();
    $col_result = $col_result->get_result();
    
    // Iterujeme přes výsledky dotazu a ukládáme název každého sloupce (pole 'Field') do pole $table_columns
    while ($col = $col_result->fetch_assoc()) {
        $table_columns[] = $col['Field'];
    }
    
    // -------------------------------
    // Načtení všech řádků z vybrané tabulky
    // -------------------------------
    // Provedeme SQL dotaz, který získá všechny záznamy z vybrané tabulky
    $row_result = $conn->prepare("SELECT * FROM `$selected_table`");
    $row_result->execute();
    $row_result = $row_result->get_result();
    
    // Iterujeme přes každý řádek výsledku a přidáváme ho do pole $table_rows
    while ($row = $row_result->fetch_assoc()) {
        $table_rows[] = $row;
    }
}
?>