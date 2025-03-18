document.addEventListener("DOMContentLoaded", function () {
    if (table === "pu") {
        let removedImages = [];
        let dt = new DataTransfer();
        const fileInput = document.querySelector("#fileInput");

        // Při změně inputu přidáme soubory do DataTransfer a vykreslíme jejich náhledy
        fileInput.addEventListener("change", function () {
            for (let i = 0; i < fileInput.files.length; i++) {
                dt.items.add(fileInput.files[i]);
            }
            fileInput.files = dt.files;
            renderNewPreviews();
        });

        function renderNewPreviews() {
            const previewDiv = document.querySelector("#newPreview");
            previewDiv.innerHTML = "";
            Array.from(dt.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("preview-img", "h-[24vh]", "object-cover", "border", "cursor-pointer", "hover:opacity-70");
                    img.dataset.index = index;
                    img.addEventListener("click", function () {
                        removeNewFile(index);
                    });
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeNewFile(removeIndex) {
            let newDt = new DataTransfer();
            Array.from(dt.files).forEach((file, index) => {
                if (index != removeIndex) {
                    newDt.items.add(file);
                }
            });
            dt = newDt;
            fileInput.files = dt.files;
            renderNewPreviews();
        }

        // Odstranění existujících fotek kliknutím na jejich náhled
        document.querySelector("#preview").addEventListener("click", function (event) {
            if (event.target.classList.contains("preview-img")) {
                let fileName = event.target.getAttribute("data-file");
                removedImages.push(fileName);
                event.target.remove();
            }
        });

        // Před odesláním formuláře přidáme skrytý input s JSON-encoded polem odstraněných fotek
        document.querySelector("form").addEventListener("submit", function () {
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "removedImages";
            input.value = JSON.stringify(removedImages);
            this.appendChild(input);
        });
    } else if (table === "ucebnice") {
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
    }
});