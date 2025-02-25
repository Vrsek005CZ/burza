<?php
session_start();
include("connect.php");
include("userinfo.php");

if (isset($_GET['puID'])) {
    $puID = $_GET['puID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}


$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, ucebnice.trida_id, typ.nazev AS typ_nazev, pu.rok_tisku, pu.stav, pu.cena, pu.poznamky, user.user AS prodejce, pu.poznamky, user.id AS prodejce_id
    FROM pu
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
    INNER JOIN typ ON ucebnice.typ_id = typ.id
    INNER JOIN user ON pu.id_prodejce = user.id
    WHERE pu.id = $puID
";


$result = $conn->query($query); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Koupit</title>
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
    <?php $row = $result->fetch_assoc(); ?>
    <div class="flex gap-4">
        <a href="ucebnice.php?knihaID=<?php echo htmlspecialchars($row['id'])?>" class="">
            <img src="foto/ucebnice/<?php echo htmlspecialchars($row['id'])?>.webp" class="rounded-lg p-1 w-56 object-cover justify-self-center bg-gray-300">
        </a>
        <div class="flex flex-col w-full">
            <div class="text-lg font-bold"><?php echo htmlspecialchars($row['ucebnice_nazev']); ?></div>
            <div class="text-sm flex">
                <div class="flex flex-col flex-grow gap-2">
                    <div class="flex gap-4">
                        <div class="w-32 flex-auto text-slate-700"><?php echo htmlspecialchars($row['typ_nazev'])?></div>
                        <div class="w-32 flex-auto"><?php echo htmlspecialchars($row['rok_tisku'])?></div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-auto w-32 text-slate-700"><?php echo htmlspecialchars($row['kategorie_nazev'])?></div>
                        <div class="flex-auto w-32"><?php echo htmlspecialchars($row['stav'])?>/10</div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-auto w-32 text-slate-700">vhodné pro <?php echo htmlspecialchars($row['trida_id'])?>. ročník</div>
                        <div class="flex-auto w-32">
                            <a href="user.php?profileID=<?php echo htmlspecialchars($row['prodejce_id']); ?>" class="text-gray-600 italic">
                                <?php echo htmlspecialchars($row['prodejce']); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="koupit.php?knihaID=<?php echo htmlspecialchars($row['id']) ?>" id="koupitButton" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-blue-700 transition">
                        Koupit
                    </a>
                </div>
            </div>
            <br>
            <div class="bg-gray-100 shadow-md rounded-md p-2 mt-2"><?php echo htmlspecialchars($row['poznamky'])?></div>
        </div>
    </div>


<br>

    <div class='flex flex-wrap col-span-8 gap-1 bg-gray-200 shadow-md p-5 rounded-md w-full'>
        <?php
        $cesta = "foto/pu/$puID/";
        $files = glob($cesta . "*.webp"); //vrátí všechny hodnoty v uvedené cestě, které končí na .webp
        $files_json = json_encode($files); // poslaní do js

        if ($files) {
            foreach ($files as $index => $file) { //oznaci indexi obrazku a projde pro kazdy obrazek
                echo "<img src='$file' class='h-48 aspect-auto cursor-pointer' onclick='otevritOkno($index)' />";
            }
        } else {
            echo "Nejsou k dispozici žádné fotky.";
        }
        ?>
    </div>
</div>

<div id="okno" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden">
    <button class="absolute top-2 right-2 bg-gray-300 hover:bg-gray-400 p-2 rounded-full shadow" onclick="zavritOkno()">✖</button>
    <div class="relative p-4 flex items-center justify-center max-w-4xl w-full h-[85vh]">

        <button onclick="predchoziObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            ⯇
        </button>

        <div class="relative bg-gray-300 p-4 rounded-lg shadow-lg flex items-center justify-center w-full h-full mx-1">
            <img id="oknoImg" src="foto/ucebnice/1.webp" class="object-contain w-full h-auto"/>
        </div>

        <button onclick="dalsiObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            ⯈
        </button>
        
    </div>
</div>

<script>
    var UserId = <?php echo $userId; ?>;
    var prodejceId = <?php echo htmlspecialchars($row['prodejce_id']); ?>;
    // Funkce pro skrytí tlačítka rezervace, pokud je uživatel prodávající
    if (prodejceId === UserId) {
        var button = document.getElementById('koupitButton')
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'transition', 'cursor-pointer');
        button.classList.add('bg-gray-400', 'cursor-not-allowed');
        button.removeAttribute("href");
    }


document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
      zavritOkno();
    }
  });

let aktualniIndex = 0;
let obrazky = <?php echo $files_json; ?>;

function otevritOkno(index) {
    aktualniIndex = index;
    document.getElementById("oknoImg").src = obrazky[aktualniIndex];
    document.getElementById("okno").classList.remove("hidden");
    nactiObrazek();
}

function zavritOkno() {
    document.getElementById("okno").classList.add("hidden");
}

function dalsiObrazek() {
    if (aktualniIndex < obrazky.length - 1) {
        aktualniIndex++;
    } else {
        aktualniIndex = 0;
    }
    velikostObrazku(aktualniIndex)
    document.getElementById("oknoImg").src = obrazky[aktualniIndex];
    nactiObrazek();
}

function predchoziObrazek() {
    if (aktualniIndex > 0) {
        aktualniIndex--;
    } else {
        aktualniIndex = obrazky.length - 1;
    }
    velikostObrazku(aktualniIndex)
    document.getElementById("oknoImg").src = obrazky[aktualniIndex];
    nactiObrazek();
}

function nactiObrazek() {
    let oknoImg = document.getElementById("oknoImg");
    oknoImg.src = obrazky[aktualniIndex];

    let img = new Image();
    img.src = obrazky[aktualniIndex];
    img.onload = function () {
        velikostObrazku(img);
    };
}

function velikostObrazku(img){
    let oknoImg = document.getElementById("oknoImg");

    if (img.height > img.width) {
        oknoImg.classList.remove("w-full");
        oknoImg.classList.add("h-full");
    } else {
        oknoImg.classList.remove("h-full");
        oknoImg.classList.add("w-full");
    }
}

</script>

<div class="text-xl">KOUPIT</div>
</div>

</body>
</html>