document.addEventListener("DOMContentLoaded", function() {
    const newPhotoInput = document.getElementById('newPhoto');
    const previewContainer = document.getElementById('preview');
    
    // Zpracování nově vybrané fotky
    newPhotoInput.addEventListener('change', function(event) {
        previewContainer.innerHTML = "";
        const file = event.target.files[0];
        if (file && /^image\//.test(file.type)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add("object-cover", "border", "cursor-pointer", "hover:opacity-70");
                // Kliknutím odstraníme náhled a resetujeme vstup
                img.addEventListener("click", function() {
                    newPhotoInput.value = "";
                    previewContainer.innerHTML = "<p id='noPhoto' class='text-center'>Žádná fotka.</p>";
                });
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = "<p id='noPhoto' class='text-center'>Žádná fotka.</p>";
        }
    });
    
    // Pokud je zobrazena aktuální fotka, kliknutím na ni ji odstraníme a nastavíme příznak pro odstranění
    const currentPhoto = document.getElementById('currentPhoto');
    if (currentPhoto) {
        currentPhoto.addEventListener("click", function() {
            previewContainer.innerHTML = "<p id='noPhoto' class='text-center'>Žádná fotka.</p>";
            // Přidáme skrytý input pro odstranění původní fotky
            let removeInput = document.createElement("input");
            removeInput.type = "hidden";
            removeInput.name = "removePhoto";
            removeInput.value = "1";
            document.querySelector("form").appendChild(removeInput);
        });
    }
});