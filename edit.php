<?php
session_start();
include("connect.php");
include("userinfo.php");

if (!isset($_GET['puID'])) {
    die("Chybějící ID učebnice.");
}

$puID = intval($_GET['puID']);
$sql = 
    "SELECT pu.cena as cena, pu.poznamky as poznamky, ucebnice.jmeno as nazev, pu.id_prodejce as prodejce
    FROM pu 
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id 
    WHERE pu.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $puID);
$stmt->execute();
$result = $stmt->get_result();
$pu = $result->fetch_assoc();

if ($userId != $pu['prodejce']){
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
}

if (!$pu) {
    die("Učebnice nenalezena.");
}

// Zpracování úpravy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $cena = intval($_POST['cena']);
    $poznamky = trim($_POST['poznamky']);

    $updateSql = "UPDATE pu SET cena = ?, poznamky = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("isi", $cena, $poznamky, $puID);
    $updateStmt->execute();

    echo "<p>Údaje byly úspěšně aktualizovány.</p>";
    header("Location: profil.php"); 
}

// Zpracování smazání
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteSql = "DELETE FROM pu WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $puID);
    $deleteStmt->execute();

    echo "<p>Učebnice byla úspěšně odstraněna.</p>";
    header("Location: profil.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Prodat</title>
</head>

<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">

<div class="w-full max-w-5xl">
    <!-- Záhlaví -->
    <div class="flex items-center justify-between bg-white shadow-md p-5 rounded-md">
        <h1 class="text-3xl font-bold text-center flex-1 text-gray-800">
            <a href="index.php">Online Burza Učebnic</a>
        </h1>
    </div>
    <br>

    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
        <form method="post">
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
                        <td class="p-4">
                            <?php echo htmlspecialchars($pu['nazev']); ?>
                        </td>
                        <td class="p-1">
                            <input type="number" name="cena" class="border p-2 w-full" value="<?php echo htmlspecialchars($pu['cena']); ?>" required>
                        </td>

                        <td class="p-1">
                            <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"><?php echo htmlspecialchars($pu['poznamky']); ?></textarea>
                        </td>
                        <td class="p-1 text-center">
                            <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Upravit</button>
                        </td>
                        <td class="p-1 text-center">
                            <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Opravdu chcete smazat tuto učebnici?');">Smazat</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    
</div>


</body>
</html>
