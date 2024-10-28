<?php
session_start();
//include("connect.php");


if (isset($_COOKIE['user_info'])) {
    $userInfoData = json_decode($_COOKIE['user_info'], true);
    $userInfoDataArray = json_decode($userInfoData, true);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Burza</title>
</head>
<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">

    <div class="w-full max-w-5xl">
        <!-- Záhlaví -->
        <div class="flex items-center justify-between bg-white shadow-md p-5 rounded-md">
            <!-- Nadpis -->
            <h1 class="text-3xl font-bold text-center flex-1 text-gray-800">Online Burza Učebnic</h1>

            <!-- User Info-->
            <div class="relative">
                <!-- Email -->
                <button id="userDropdown" class="text-gray-600 font-semibold focus:outline-none">
                    <?php 
                    if (isset($_COOKIE['user_info'])) {
                        echo explode('@',htmlspecialchars($userInfoDataArray['email']))[0]; 
                    }else {
                        echo "<a href='login.php'>Přihlásit se</a>";
                    }
                        
                    ?>
                </button>

                <!-- Dropdown jen pro přihlášený -->
                <?php if (isset($_COOKIE['user_info'])): ?>
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20">
                        <a href="profil.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                        <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Odhlásit se</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Aktivovat dropdown
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userDropdown.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Schovat při kliknutí vedle
        document.addEventListener('click', (event) => {
            if (!userDropdown.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>


</body>
</html>
