<?php
session_start();
include("connect.php");
include("userinfo.php");

if (isset($_GET['puID'])) {
    $puID = $_GET['puID'];
} else {
    echo("Chyba: Nezadali jste ID knihy.");
}


$query = "SELECT ucebnice.id, ucebnice.jmeno AS ucebnice_nazev, kategorie.nazev AS kategorie_nazev, 
           ucebnice.trida_id, typ.nazev AS typ_nazev
    FROM pu
    INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
    INNER JOIN kategorie ON ucebnice.kategorie_id = kategorie.id
    INNER JOIN typ ON ucebnice.typ_id = typ.id
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

    <?php $row = $result->fetch_assoc(); ?>
    <div class="w-full max-w-7xl bg-white shadow-md rounded-md p-8 mx-auto grid gap-4 p-4 grid-cols-4">
        <img src="foto/ucebnice/<?php echo htmlspecialchars($row['id'])?>.jpg" class="rounded-lg p-1 h-48 object-cover justify-self-center bg-gray-300">
        <div class="col-span-3">
            <div class="text-lg font-bold"><?php echo htmlspecialchars($row['ucebnice_nazev']); ?></div>
            <div class="text-sm text-slate-700">
                <div><?php echo htmlspecialchars($row['typ_nazev'])?></div>
                <div><?php echo htmlspecialchars($row['kategorie_nazev'])?></div>
                <div>vhodné pro <?php echo htmlspecialchars($row['trida_id'])?>. ročník</div>
            </div>
        </div>

        <div class='flex flex-wrap col-span-4 gap-1'>
    <?php
    $cesta = "foto/pu/$puID/";
    $files = glob($cesta . "*.jpg"); //vrátí všechny hodnoty v uvedené cestě, které končí na .jpg
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

<div id="okno" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden">
    <button class="absolute top-2 right-2 bg-gray-300 hover:bg-gray-400 p-2 rounded-full shadow" onclick="zavritOkno()">✖</button>
    <div class="relative p-4 flex items-center justify-center max-w-4xl w-full h-[85vh]">

        <button onclick="predchoziObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            ⯇
        </button>

        <div class="relative bg-gray-300 p-4 rounded-lg shadow-lg flex items-center justify-center w-full h-full mx-1">
            <img id="oknoImg" src="foto/ucebnice/1.jpg" class="object-contain w-full h-auto"/>
        </div>

        <button onclick="dalsiObrazek()" class="bg-gray-300 hover:bg-gray-400 text-4xl p-2 rounded-lg shadow-lg flex items-center justify-center w-[3vh] h-full">
            ⯈
        </button>
        
    </div>
</div>



<script>

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


<h1>Dodělat koupit, vypsat informace o danné prodávané učebnici</h1>
</div>

</body>
</html>