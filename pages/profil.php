<?php
session_start();
require_once "../code/connect.php";
require_once "../code/userinfo.php";
$pageTitle = "Profil"; 
require_once "../header.php";

require_once "../code/profile.php";

?>
    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-4 sm:p-8 mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Můj Profil</h1>

        <!-- Osobní informace -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b pb-6 mb-6">
            <div><strong>Jméno:</strong> <?php echo htmlspecialchars($user['jmeno']); ?></div>
            <div><strong>Příjmení:</strong> <?php echo htmlspecialchars($user['prijmeni']); ?></div>
            <div><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
            <div><strong>Uživatelské jméno:</strong> <?php echo htmlspecialchars($user['user']); ?></div>
            <div>
                <strong>Ročník:</strong>
                <?php if ($user['trida_id'] == 0): ?>
                    <form method="POST">
                        <select name="trida_id" class="border rounded p-2">
                            <option value="" disabled selected>Vyberte třídu</option>
                            <option value="1">1. Ročník (1.A)</option>
                            <option value="2">2. Ročník (2.A)</option>
                            <option value="3">3. Ročník (3.A,1.B)</option>
                            <option value="4">4. Ročník (4.A,2.B)</option>
                            <option value="5">5. Ročník (5.A,3.B,1.C)</option>
                            <option value="6">6. Ročník (6.A,4.B,2.C,6.E)</option>
                            <option value="7">7. Ročník (7.A,5.B,3.C,7.E)</option>
                            <option value="8">8. Ročník (8.A,6.B,4.C,8.E)</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Uložit</button>
                    </form>
                <?php else: ?>
                    <?php echo htmlspecialchars($user['trida_id']).". ročník"; ?>
                <?php endif; ?>
            </div>
            <div><strong>ID:</strong> <?php echo htmlspecialchars($userId); ?></div>
        </div>

        <!-- Tabulka učebnic -->
        <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">Prodávané učebnice</h2>
        <div class="overflow-x-auto">
            <table class="min-w-[1000px] sm:min-w-full bg-gray-50 shadow-md rounded-lg text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-4 text-left w-[23%]">Název učebnice</th>
                        <th class="p-4 text-left w-[5%]">Rok tisku</th>
                        <th class="p-4 text-left w-[5%]">Stav</th>
                        <th class="p-4 text-left w-[7%]">Cena</th>
                        <th class="p-4 text-left w-[46%]">Poznámky</th>
                        <th class="p-4 text-left w-[7%]">Stav prodání</th>
                        <th class="p-4 text-left w-[7%]">Upravit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($prodavaneUcebnice->num_rows > 0): ?>
                        <?php while ($ucebnice = $prodavaneUcebnice->fetch_assoc()): ?>
                            <tr class="even:bg-gray-100">
                                <td class="p-4 break-words"><?php echo htmlspecialchars($ucebnice['ucebnice']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['rok_tisku']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['stav']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($ucebnice['cena']); ?> Kč</td>
                                <td class="p-4 break-words"><?php echo htmlspecialchars($ucebnice['poznamky']); ?></td>
                                <td class="p-4">
                                    <a href="koupit.php?puID=<?php echo htmlspecialchars($ucebnice['id']); ?>" class="font-semibold italic <?php echo ($ucebnice['koupil'] != 0) ? 'text-red-600' : 'text-green-600'; ?>">
                                        <?php echo ($ucebnice['koupil'] != 0) ? 'Prodáno' : 'Neprodáno'; ?>
                                    </a>
                                </td>
                                <td class="p-4">
                                    <a href="edit.php?puID=<?php echo htmlspecialchars($ucebnice['id']); ?>" class="button bg-blue-500 text-white px-4 py-3 rounded">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center">Žádné učebnice nenalezeny.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $conn->close(); ?>
</body>
</html>
