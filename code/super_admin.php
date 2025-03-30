<?php
require_once "connect.php";

/**
 * Načte seznam všech tabulek v databázi.
 *
 * @param mysqli $conn Připojení k databázi.
 * @return array Seznam názvů tabulek.
 */
function getTables($conn) {
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    return $tables;
}

/**
 * Načte názvy sloupců z vybrané tabulky.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param string $table Název tabulky.
 * @return array Seznam názvů sloupců.
 */
function getTableColumns($conn, $table) {
    $columns = [];
    $stmt = $conn->prepare("SHOW COLUMNS FROM `$table`");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($col = $result->fetch_assoc()) {
        $columns[] = $col['Field'];
    }
    $stmt->close();
    return $columns;
}

/**
 * Načte všechny řádky z vybrané tabulky.
 *
 * @param mysqli $conn Připojení k databázi.
 * @param string $table Název tabulky.
 * @return array Seznam řádků (každý řádek je asociativní pole).
 */
function getTableRows($conn, $table) {
    $rows = [];
    $stmt = $conn->prepare("SELECT * FROM `$table`");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}

// -------------------------------
// Načtení seznamu všech tabulek v databázi
// -------------------------------
$tables = getTables($conn);

// -------------------------------
// Zpracování výběru aktuálně zvolené tabulky
// -------------------------------
$selected_table = isset($_GET['table']) ? $_GET['table'] : "";
$table_columns = [];
$table_rows = [];

if ($selected_table && in_array($selected_table, $tables)) {
    // Načtení názvů sloupců vybrané tabulky
    $table_columns = getTableColumns($conn, $selected_table);
    
    // Načtení všech řádků z vybrané tabulky
    $table_rows = getTableRows($conn, $selected_table);
}
?>