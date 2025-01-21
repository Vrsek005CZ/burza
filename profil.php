<?php
session_start();
include("connect.php");
include("userinfo.php");

// Zpracování výběru třídy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trida_id'])) {
    $selectedTrida = intval($_POST['trida_id']);
    
    $updateQuery = "UPDATE user SET trida_id=$selectedTrida WHERE id=$userId";
    if ($conn->query($updateQuery) === TRUE) {
        $user['trida_id'] = $selectedTrida; // Aktualizujeme hodnotu i lokálně
        header("Location: profil.php"); // Přesměrování po uložení
        exit;
    } else {
        echo "Chyba při ukládání třídy: " . $conn->error;
    }
}

// Načtení tříd pro výběr
$tridyQuery = "SELECT trida_id FROM user";
$tridyResult = $conn->query($tridyQuery);

// Načtení prodávaných učebnic, definovani aliasu
$prodavaneUcebniceQuery = 
"   SELECT ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    WHERE pu.id_prodejce = {$user['id']}
";
$prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profil</title>
</head>

<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">

    <div class="w-full max-w-5xl">
        <!-- Záhlaví -->
        <div class="flex items-center justify-between bg-white shadow-md p-5 rounded-md">
            <!-- Nadpis -->
            <h1 class="text-3xl font-bold text-center flex-1 text-gray-800">
                <a href="index.php" class="">Online Burza Učebnic</a>
            </h1>
        </div>
        <br>
    

        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 ">Můj Profil</h1>

            <div class="flex">
                <div class="w-1/5 border-r pr-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Osobní informace</h2>
                    
                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Jméno:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($user['jmeno']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Příjmení:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($user['prijmeni']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Email:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Uživatelské jméno:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($user['user']); ?></p>
                    </div>


                    <div class="mb-4">
        <span class="font-bold text-gray-600">Ročník:</span>
        <?php if ($user['trida_id'] == 0): ?>
            <!-- Formulář pro výběr třídy -->
            <form method="POST">
                <select name="trida_id" class="border rounded p-2">
                    <option value="" disabled selected>Vyberte třídu</option>
                    <option value="1" <?php if ($user['trida_id'] == 1) echo "selected"; ?>>1. Ročník (1.A)</option>
                    <option value="2" <?php if ($user['trida_id'] == 2) echo "selected"; ?>>2. Ročník (2.A)</option>
                    <option value="3" <?php if ($user['trida_id'] == 3) echo "selected"; ?>>3. Ročník (3.A,1.B)</option>
                    <option value="4" <?php if ($user['trida_id'] == 3) echo "selected"; ?>>4. Ročník (4.A,2.B)</option>
                    <option value="5" <?php if ($user['trida_id'] == 3) echo "selected"; ?>>5. Ročník (5.A,3.B,1.C)</option>
                    <option value="6" <?php if ($user['trida_id'] == 3) echo "selected"; ?>>6. Ročník (6.A,4.B,2.C,6.E)</option>
                    <option value="7" <?php if ($user['trida_id'] == 3) echo "selected"; ?>>7. Ročník (7.A,5.B,3.C,7.E)</option>
                    <option value="8" <?php if ($user['trida_id'] == 3) echo "selected"; ?>>8. Ročník (8.A,6.B,4.C,8.E)</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Uložit</button>
            </form>
        <?php else: ?>
            <!-- Zobrazení ID třídy, pokud je nastavena -->
            <p class="text-gray-800"><?php echo htmlspecialchars($user['trida_id']).".ročník"; ?></p>
        <?php endif; ?>
    </div>
                </div>

                <div class="w-full lg:w-2/3 mx-auto">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Prodávané učebnice</h2>
                    <table class="w-full bg-gray-50 shadow-md rounded-lg">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-4 text-left">Název učebnice</th>
                                <th class="p-4 text-left">Rok tisku</th>
                                <th class="p-4 text-left">Stav</th>
                                <th class="p-4 text-left">Cena</th>
                                <th class="p-4 text-left">Poznámky</th>
                                <th class="p-4 text-left">Stav prodání</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($ucebnice = $prodavaneUcebnice->fetch_assoc()): ?>
                                <tr>
                                    <td class="p-4"><?php echo htmlspecialchars($ucebnice['ucebnice']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($ucebnice['rok_tisku']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($ucebnice['stav']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($ucebnice['cena']); ?> Kč</td>
                                    <td class="p-4"><?php echo htmlspecialchars($ucebnice['poznamky']); ?></td>
                                    <?php if ($ucebnice['koupil'] != 0): ?>
                                        <td class="p-4 text-red-600 font-semibold">Prodáno</td>
                                        <?php else: ?>
                                            <td class="p-4 text-green-600 font-semibold">Neprodáno</td>  
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php $conn->close(); ?>
</body>
</html>