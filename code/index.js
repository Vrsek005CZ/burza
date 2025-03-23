// Funkce pro filtrování knih podle vyhledávání, kategorie a ročníku 

document.getElementById('searchInput').addEventListener('input', filterBooks);
document.getElementById('categoryFilter').addEventListener('change', filterBooks);
document.getElementById('gradeFilter').addEventListener('change', filterBooks);

function filterBooks() {
    // Získání vstupních hodnot
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedCategory = document.getElementById('categoryFilter').value;
    const selectedGrade = document.getElementById('gradeFilter').value;
    const books = document.querySelectorAll('.book'); // Vrátí NodeList obsahující všechny prvky s třídou .book

    books.forEach(book => {
        const bookTitle = book.querySelector('.text-l').textContent.toLowerCase(); // Získá název knihy z prvku s třídou .text-l
        const bookCategory = book.getAttribute('data-category'); // Získá hodnotu atributu data-category z prvku knihy
        const bookGrade = book.getAttribute('data-grade'); // Získá hodnotu atributu data-grade

        if (
            bookTitle.includes(searchText) && // Ověří, zda název knihy obsahuje hledaný text
            (selectedCategory === "" || bookCategory === selectedCategory) && // Když kategorie prázdná, nefiltrujeme
            (selectedGrade === "" || bookGrade === selectedGrade) // Když ročník prázdný, nefiltrujeme
        ) {
            book.style.display = "block";
        } else {
            book.style.display = "none";
        }
    });
}

// Funkce pro přepínání výšky prvku
document.addEventListener("DOMContentLoaded", function () {
    const bookTitles = document.querySelectorAll('.text-l');

    document.getElementById('toggleHeight').addEventListener('click', function () {
        bookTitles.forEach(title => {
            title.classList.toggle('h-12');
            title.classList.toggle('h-full');
        });
    });
});


