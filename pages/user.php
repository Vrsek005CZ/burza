<?php
session_start();
require_once "../code/connect.php";
require_once "../code/userinfo.php";

require_once "../header.php";
getHeader("Uživatel"); 

require_once "../code/user_php.php";

// Kontrola, zda byl zadán profileID
if (isset($_GET['profileID'])) {
    $profileID = intval($_GET['profileID']); // Proti SQL injection
} else {
    die("Chyba: Nezadali jste ID profilu.");
}

// Načtení dat pomocí funkcí
$profil = getProfil($conn, $profileID);
$prodavaneUcebnice = getProdavaneUcebnice($conn, $profileID);

// Rozdělení prodaných a neprodaných učebnic
$neprodaneUcebnice = array_filter($prodavaneUcebnice, fn($ucebnice) => $ucebnice['koupil'] == 0);
$prodaneUcebnice = array_filter($prodavaneUcebnice, fn($ucebnice) => $ucebnice['koupil'] != 0);
?>

<div class="w-full bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Osobní informace</h2>
    <div class="flex flex-wrap justify-between">
        <div class="w-full md:w-1/2 sm:pr-4">
            <div class="mb-4">
                <span class="font-bold text-gray-600">Jméno:</span>
                <p class="text-gray-800"><?php echo htmlspecialchars($profil['jmeno']); ?></p>
            </div>

            <div class="mb-4">
                <span class="font-bold text-gray-600">Příjmení:</span>
                <p class="text-gray-800"><?php echo htmlspecialchars($profil['prijmeni']); ?></p>
            </div>

            <div class="mb-4">
                <span class="font-bold text-gray-600">Email:</span>
                <p class="text-gray-800"><?php echo htmlspecialchars($profil['email']); ?></p>
            </div>
        </div>

        <div class="w-full md:w-1/2 sm:pl-4">
            <div class="mb-4">
                <span class="font-bold text-gray-600">Uživatelské jméno:</span>
                <p class="text-gray-800"><?php echo htmlspecialchars($profil['user']); ?></p>
            </div>

            <div class="mb-4">
                <span class="font-bold text-gray-600">Ročník:</span>
                <p class="text-gray-800"><?php echo htmlspecialchars($profil['trida_id']); ?>. ročník</p>
            </div>
        </div>
    </div>
    <div class="w-full mx-auto mt-4">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Prodávané učebnice</h2>
        <div class="overflow-x-auto mb-8">
            <table class="min-w-[1000px] sm:min-w-full bg-gray-50 shadow-md rounded-lg text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-4 text-left w-[29%]">Název učebnice</th>
                        <th class="p-4 text-left w-[10%]">Rok tisku</th>
                        <th class="p-4 text-left w-[10%]">Stav</th>
                        <th class="p-4 text-left w-[15%]">Cena</th>
                        <th class="p-4 text-left w-[24%]">Poznámky</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($neprodaneUcebnice) > 0): ?>
                        <?php foreach ($neprodaneUcebnice as $ucebnice): ?>
                            <tr>
                                <td class="p-4 break-words">
                                    <a href="koupit.php?puID=<?php echo htmlspecialchars($ucebnice['id']); ?>" class="text-blue-600 hover:underline">
                                        <?php echo htmlspecialchars($ucebnice['ucebnice']); ?>
                                    </a>
                                </td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['rok_tisku']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['stav']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['cena']); ?> Kč</td>
                                <td class="p-4 break-words"><?php echo htmlspecialchars($ucebnice['poznamky']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-4 text-center">Žádné neprodané učebnice nenalezeny.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>