function BookChange() {
    const url = new URL(window.location.href);
    let a = url.searchParams.get('selfbook');
    if (a === null) {
        a = -1;
    } else {
        a = parseInt(a) * -1; // Přepnutí mezi 1 a -1
    }

    url.searchParams.set('selfbook', a);
    window.location.href = url.toString();
}
const urlParams = new URLSearchParams(window.location.search);
const selfbook = urlParams.get('selfbook');

// Pokud je 'selfbook' rovno 1, změníme text tlačítka
if (selfbook == '-1') {
    document.getElementById("bookButton").innerHTML = "◆";
}
else {
    document.getElementById("bookButton").innerHTML = "◇";
}