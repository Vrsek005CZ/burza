<?php
session_start();
include("connect.php");
include("userinfo.php");


if (isset($_GET['profileID'])) {
    $profileID = isset($_GET['profileID']) ? intval($_GET['profileID']) : 0;  // proti sql injekci
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}

// Načtení prodávaných učebnic, definovani aliasu
$prodavaneUcebniceQuery = 
"   SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    WHERE pu.id_prodejce = $profileID
";
$prodavaneUcebnice = $conn->query($prodavaneUcebniceQuery);

$profilQuery = 
"   SELECT user.user, user.email, user.jmeno, user.prijmeni, user.trida_id
    FROM user
    WHERE user.id = $profileID
";

$profil = $conn->query($profilQuery);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Uzivatel</title>
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
    
        <?php $row = $profil->fetch_assoc(); ?>
        <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 ">Profil</h1>

            <div class="flex">
                <div class="w-1/5 border-r pr-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Osobní informace</h2>
                    
                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Jméno:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($row['jmeno']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Příjmení:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($row['prijmeni']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Email:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($row['email']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Uživatelské jméno:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($row['user']); ?></p>
                    </div>


                    <div class="mb-4">
                    <span class="font-bold text-gray-600">Ročník:</span>
                    <p class="text-gray-800"><?php echo htmlspecialchars($row['trida_id']); ?>. ročník</p>
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
                                    <td class="p-4">
                                        <a href="koupit.php?puID=<?php echo htmlspecialchars($ucebnice['id']); ?>"  
                                        class="font-semibold italic <?php echo ($ucebnice['koupil'] != 0) ? 'text-red-600' : 'text-green-600'; ?>">
                                            <?php echo ($ucebnice['koupil'] != 0) ? 'Prodáno' : 'Neprodáno'; ?>
                                        </a>
                                    </td>
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