function koupit(){
    document.getElementById('koupitButton').classList.add('hidden')
    document.getElementById('potvrditButton').classList.remove('hidden')
}


// Funkce pro skrytí tlačítka rezervace, pokud je uživatel prodávající
if (prodejceId === UserId || koupil !== 0) {
    const button = document.getElementById('koupitButton')
    button.classList.remove('bg-green-600', 'hover:bg-green-700', 'transition', 'cursor-pointer');
    button.classList.add('bg-gray-400', 'cursor-not-allowed');
    button.removeAttribute("href");
    button.removeAttribute("onclick");
}

document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
      zavritOkno();
    }
  });

let aktualniIndex = 0;

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