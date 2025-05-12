<?php
require_once "code/userinfo.php";
$BASE_URL = "https://gymso.online/-/vrseka/burza/";

function getHeader($pageTitle) {
    global $email, $user, $BASE_URL;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title><?php echo htmlspecialchars($pageTitle); ?></title>
        <style>
        .break-words {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        </style>
    </head>
    <body class="bg-gray-100 h-screen flex items-start justify-center pt-10">
    <div class="w-full max-w-5xl">
        <!-- Záhlaví -->
        <div class="relative items-center justify-between bg-white shadow-md p-5 rounded-md">
            <!-- Nadpis -->
            <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 hover:underline"><a href="<?php echo ($BASE_URL . "index.php") ?>">Online Burza Učebnic</a></h1>
            <!-- User Info-->
            <div>
                <!-- Email -->
                <button id="userDropdown" class="content-right text-right bg-gray-100 rounded-lg mx-1 px-2 sm:px-4 py-1 sm:justify absolute top-6 right-3 text-gray-600 font-semibold focus:outline-none hover:bg-gray-200">
                    <div class="flex items-center sm:space-x-2">
                        <div class="hidden sm:block text-right"><?php echo isset($_COOKIE['user_info']) ? explode('@', htmlspecialchars($email))[0] : "<a href='" . $BASE_URL . "login.php'>Přihlásit se</a>"; ?></div>
                        <div class="flex-grow text-center">⋮</div>
                    </div>
                </button>

                <!-- Dropdown jen pro přihlášený -->
                <?php if (isset($_COOKIE['user_info'])): ?>
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                        <a href="<?php echo $BASE_URL . "pages/profil.php"; ?>" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                        <a href="<?php echo $BASE_URL . "pages/objednavky.php"; ?>" class="block px-4 py-2 hover:bg-gray-100">Objednávky</a>
                        <a href="<?php echo $BASE_URL . "pages/prodat.php"; ?>" class="block px-4 py-2 hover:bg-gray-100">Prodat knihu</a>
                        <a href="<?php echo $BASE_URL . "NapovedaBurzyUcebnic.pdf"; ?>" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">Nápověda</a>
                        <a href="<?php echo $BASE_URL . "DokumentaceBurzyUcebnic.pdf"; ?>" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">Dokumentace</a>
                        <?php if (isset($user['type']) && $user['type'] > 0): ?>
                            <a href="<?php echo $BASE_URL . "pages/superadmin.php"; ?>" class="block px-4 py-2 text-yellow-600 hover:bg-gray-100">Administrace</a>
                        <?php endif; ?>
                        <a href="<?php echo $BASE_URL . "code/logout.php"; ?>" class="block px-4 py-2 text-red-800 hover:bg-gray-100">Odhlásit se</a>
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
    <?php
}
?>
