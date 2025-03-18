document.addEventListener("DOMContentLoaded", function () {
    let removedImages = [];
    // Použijeme DataTransfer pro manipulaci se soubory u inputu
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

    // Mazání existujících fotek – kliknutím na fotku v sekci "Fotky"
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
});