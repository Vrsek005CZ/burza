<?php
session_start();
include("connect.php");
include("userinfo.php");
$pageTitle = "Uživatel"; 
include("header.php");


if (isset($_GET['profileID'])) {
    $profileID = isset($_GET['profileID']) ? intval($_GET['profileID']) : 0;  // proti sql injekci
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}

// Načtení prodávaných učebnic, definovani aliasu
$prodavaneUcebniceStmt = $conn->prepare(
    "SELECT pu.id, ucebnice.jmeno AS ucebnice, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, pu.koupil 
    FROM pu
    JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    WHERE pu.id_prodejce = ?"
);
$prodavaneUcebniceStmt->bind_param("i", $profileID);
$prodavaneUcebniceStmt->execute();
$prodavaneUcebnice = $prodavaneUcebniceStmt->get_result();

$profilStmt = $conn->prepare(
    "SELECT user.user, user.email, user.jmeno, user.prijmeni, user.trida_id
    FROM user
    WHERE user.id = ?"
);
$profilStmt->bind_param("i", $profileID);
$profilStmt->execute();
$profil = $profilStmt->get_result();



?>
    <style>
        td {
            word-break: break-word;
            overflow-wrap: break-word;
        }
    </style>
    
        <?php $row = $profil->fetch_assoc(); ?>
        <div class="w-full bg-white shadow-md rounded-md p-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Osobní informace</h2>
            <div class="flex flex-wrap justify-between">
                <div class="w-full md:w-1/2 pr-4">
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
                </div>

                <div class="w-full md:w-1/2 pl-4">
                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Uživatelské jméno:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($row['user']); ?></p>
                    </div>

                    <div class="mb-4">
                        <span class="font-bold text-gray-600">Ročník:</span>
                        <p class="text-gray-800"><?php echo htmlspecialchars($row['trida_id']); ?>. ročník</p>
                    </div>
                </div>
            </div>
            <div class="w-full mx-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Prodávané učebnice</h2>
            <table class="w-full bg-gray-50 shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-4 text-left w-[25%]">Název učebnice</th>
                        <th class="p-4 text-left w-[4%]">Rok tisku</th>
                        <th class="p-4 text-left w-[4%]">Stav</th>
                        <th class="p-4 text-left w-[11%]">Cena</th>
                        <th class="p-4 text-left w-[40%]">Poznámky</th>
                        <th class="p-4 text-left w-[16%]">Stav prodání</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ucebnice = $prodavaneUcebnice->fetch_assoc()): ?>
                        <tr>
                            <td class="p-4 break-words"><?php echo htmlspecialchars($ucebnice['ucebnice']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($ucebnice['rok_tisku']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($ucebnice['stav']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($ucebnice['cena']); ?> Kč</td>
                            <td class="p-4 break-words"><?php echo htmlspecialchars($ucebnice['poznamky']); ?></td>
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