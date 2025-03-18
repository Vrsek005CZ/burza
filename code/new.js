document.getElementById('fotky').addEventListener('change', function(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = ""; // Vyčistíme předchozí náhled

    const file = event.target.files[0]; // Získáme první soubor
    if (file && /^image\//.test(file.type)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add("h-[50vh]", "object-cover", "text-center", "border", "cursor-pointer", "hover:opacity-70");
            
            img.addEventListener("click", function() {
                event.target.value = ""; // Resetujeme input
                preview.innerHTML = ""; // Odstraníme náhled
            });
            
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});