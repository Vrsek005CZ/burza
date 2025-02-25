<?php
session_start();
include("connect.php");
include("userinfo.php");

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
        <div class="relative items-center justify-between bg-white shadow-md p-5 rounded-md">
            <!-- Nadpis -->
            <h1 class="text-3xl font-bold text-center flex-1 text-gray-800">Online Burza Učebnic</h1>

            <!-- User Info-->
            <div class="absolute top-6 right-3">
                <!-- Email -->
                <button id="userDropdown" class="text-gray-600 font-semibold focus:outline-none p">
                    <?php 
                    if (isset($_COOKIE['user_info'])) {
                        echo explode('@',htmlspecialchars($email))[0]." ⋮"; 
                    }else {
                        echo "<a href='login.php'>Přihlásit se</a>";
                    }
                        
                    ?>
                </button>

                <!-- Dropdown jen pro přihlášený -->
                <?php if (isset($_COOKIE['user_info'])): ?>
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20">
                        <a href="profil.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                        <a href="prodat.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Prodat knihu</a>
                        <?php if ($user['type'] > 0): ?>
                            <a href="admin.php" class="block px-4 py-2 text-yellow-600 hover:bg-gray-100">Administrace</a>
                        <?php endif; ?>
                        <a href="logout.php" class="block px-4 py-2 text-red-800 hover:bg-gray-100">Odhlásit se</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabulka knih -->
        <div class="grid gap-4 p-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        <!-- Kniha z SQL -->
        <?php

            $query ="SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, COUNT(CASE WHEN pu.koupil = 0 THEN 1 END) AS pocet_ks, ROUND(AVG(CASE WHEN pu.koupil = 0 THEN pu.cena END)) AS avg_cena FROM ucebnice
            INNER JOIN kategorie ON ucebnice.kategorie_id=kategorie.id
            INNER JOIN typ ON ucebnice.typ_id=typ.id
            INNER JOIN pu ON ucebnice.id=pu.id_ucebnice
            GROUP BY ucebnice.id
            ";

            $result = $conn->query($query);           

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $knihaID = $row['id']; 
                    ?>
                    <a href="ucebnice.php?knihaID=<?php echo $knihaID; ?>" class="bg-gray-200 p-4 items-center rounded-md">
                        <div class="bg-gray-100 rounded-md">
                            <div class="text-l text-center font-semibold m-1 p-1 h-12 hover:h-full">
                                <?php echo htmlspecialchars($row['ucebnice_nazev']); ?>
                            </div>
                            <div class="h-50">
                                <img src="foto/ucebnice/<?php echo htmlspecialchars($row['id']); ?>.webp" class="rounded-lg p-1 w-full h-48 object-cover">
                            </div>
                            <div class="text-s m-1 p-1">
                                počet ks: <span class="font-medium"><?php echo htmlspecialchars($row['pocet_ks']); ?></span><br>
                                avg cena: <span class="font-medium"><?php echo number_format($row['avg_cena'], 0, ',', '.'); ?>,-</span>
                            </div>
                        </div>
                        <div class="text-xs p-1">
                            <?php echo htmlspecialchars($row['kategorie_nazev']); ?><br>
                            <?php echo htmlspecialchars($row['trida_id']); ?>. ročník<br>
                            <?php echo htmlspecialchars($row['typ_nazev']); ?><br>
                        </div>
                    </a>
                    <?php
                }
            } else {
                echo '<p>Žádné učebnice k dispozici.</p>';
            }
            ?>
        </div>
        
        
    <h1>Nejde na mobily, chyba v souboru userinfo.ph. Doladit web, jako třeba šipku zpět</h1>

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