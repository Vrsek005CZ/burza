<?php
session_start();
include("connect.php");
$current_year = date("Y");
// Získání seznamu učebnic
$sql = "SELECT id, jmeno FROM ucebnice";
$result = $conn->query($sql);
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
        <form method="POST" action="pridat_ucebnici.php">
            <table class="w-full bg-gray-50 shadow-md rounded-lg">
                <thead class="text-left bg-gray-200">
                    <tr>
                        <th class="p-4 w-[25%]">Učebnice</th>
                        <th class="p-4 w-[8%]">Stav</th>
                        <th class="p-4 w-[8%]">Rok tisku</th>
                        <th class="p-4 w-[8%]">Cena</th>
                        <th class="p-4 w-[38%]">Poznámky</th>
                        <th class="p-4 w-[15%] text-center">Vystavit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-4">
                            <select name="id_ucebnice" class="border p-2 w-full" required>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['jmeno']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                        <td class="p-1">
                            <input type="number" name="stav" min="1" max="10" class="border p-2 w-full" required>
                        </td>
                        <td class="p-1">
                            <input type="number" name="rok_tisku" min="1900" max="<?php echo $current_year; ?>" class="border p-2 w-full" required>
                        </td>
                        <td class="p-1">
                            <input type="number" name="cena" min="0" class="border p-2 w-full" required>
                        </td>
                        <td class="p-1">
                            <textarea name="poznamky" class="border p-2 w-full h-40 resize-none" maxlength="256"></textarea>
                        </td>
                        <td class="p-1 text-center">
                            <button type="submit" name="pridat" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Přidat
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

</body>
</html>
