<?php
require_once "code/userinfo.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Burza'; ?></title>
</head>
<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">
<div class="w-full max-w-5xl">
    <!-- Záhlaví -->
    <div class="relative items-center justify-between bg-white shadow-md p-5 rounded-md">
        <!-- Nadpis -->
        <h1 class="text-3xl font-bold text-center text-gray-800"><a href="/burza/index.php">Online Burza Učebnic</a></h1>
        <!-- User Info-->
        <div class="absolute top-6 right-3">
            <!-- Email -->
            <button id="userDropdown" class="text-gray-600 font-semibold focus:outline-none">
                <?php echo isset($_COOKIE['user_info']) ? explode('@',htmlspecialchars($email))[0]." ⋮" : "<a href='login.php'>Přihlásit se</a>"; ?>
            </button>

            <!-- Dropdown jen pro přihlášený -->
            <?php if (isset($_COOKIE['user_info'])): ?>
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                    <a href="/burza/pages/profil.php" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                    <a href="/burza/pages/objednavky.php" class="block px-4 py-2 hover:bg-gray-100">Objednávky</a>
                    <a href="/burza/pages/prodat.php" class="block px-4 py-2 hover:bg-gray-100">Prodat knihu</a>
                    <?php if ($user['type'] > 0): ?>
                        <a href="/burza/pages/superadmin.php" class="block px-4 py-2 text-yellow-600 hover:bg-gray-100">Administrace</a>
                    <?php endif; ?>
                    <a href="/burza/pages/logout.php" class="block px-4 py-2 text-red-800 hover:bg-gray-100">Odhlásit se</a>
                </div>
            <?php endif; ?>
        </div>
    </div><br>

    <script>
        // Aktivovat dropdown
        document.getElementById('userDropdown').addEventListener('click', function () {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
        document.addEventListener('click', function (event) {
            if (!document.getElementById('userDropdown').contains(event.target)) {
                // Schovat při kliknutí vedle
                document.getElementById('dropdownMenu').classList.add('hidden');
            }
        });
    </script>