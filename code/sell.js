
// Přidáme posluchače události 'change' pro input, kdy uživatel vybere soubory
document.getElementById('fotky').addEventListener('change', function(event) {
    // Najdeme element, do kterého budeme vkládat náhledy obrázků
    const preview = document.getElementById('preview');
    // Vyčistíme předchozí náhledy, aby nedocházelo k duplikaci
    preview.innerHTML = "";

    // Převod FileList (event.target.files) na pole pro snadnější manipulaci
    const files = Array.from(event.target.files);

    // Projdeme všechny vybrané soubory
    files.forEach((file, index) => {
        // Kontrola, zda soubor je obrázek (pomocí regex pro různé formáty)
        if (/^image\//.test(file.type)) {
            // Vytvoříme nový FileReader pro načtení souboru
            const reader = new FileReader();

            // Definujeme, co se stane, když bude soubor načten
            reader.onload = function(e) {
                // Vytvoříme nový <img> element
                const img = document.createElement('img');
                // Nastavíme zdroj obrázku na načtená data (data URL)
                img.src = e.target.result;
                // Přidáme CSS třídy pro stylování obrázku (velikost, okraj, kurzor)
                img.classList.add("h-[24vh]", "object-cover", "border", "cursor-pointer", "hover:opacity-70");
                // Přidáme datový atribut pro uložení indexu souboru
                img.setAttribute("data-index", index);

                // Přidáme posluchače události, který umožní odstranění obrázku při kliknutí
                img.addEventListener("click", function() {
                    // Odstraníme soubor z pole files pomocí jeho indexu
                    files.splice(index, 1);

                    // Vytvoříme nový DataTransfer objekt pro sestavení nového FileListu
                    const dt = new DataTransfer();
                    // Přidáme všechny zbývající soubory do DataTransfer objektu
                    files.forEach(file => dt.items.add(file));

                    // Aktualizujeme seznam souborů v inputu tak, aby neobsahoval odstraněný soubor
                    event.target.files = dt.files;

                    // Odstraníme náhled obrázku z DOM (zobrazení)
                    img.remove();
                });

                // Přidáme vytvořený obrázek do preview kontejneru, aby byl viditelný
                preview.appendChild(img);
            };

            // Spustíme asynchronní načítání souboru a převedení na data URL
            reader.readAsDataURL(file);
        }
    });
});


$(document).ready(function() {
    $('#ucebniceSelect').select2({
        placeholder: "Vyberte učebnici...",
        allowClear: true,
        templateResult: function(option) {
            if (!option.id) {
                return option.text;
            }
            if (option.id === 'redirect') {
                return $('<span class="bg-gray-200 text-center block px-2 py-1 text-italic font-semibold">Vložit novou učebnici</span>');
            }
            return option.text;
        }
    });

    // Přesměrování na "nova.php", pokud se vybere poslední možnost
    $('#ucebniceSelect').on('change', function() {
        if ($(this).val() === 'redirect') {
            window.location.href = 'nova.php';
        }
    });
});
