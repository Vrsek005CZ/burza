<?php
// SEM NEŠAHEJ, NEBO TI URVU PRACKY
function getFooter() {
    ?>

<br>
<footer class="bg-white shadow-md p-5 rounded-md text-gray-800 py-10 max-w-7xl mx-auto">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Sekce o projektu -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">O projektu</h3>
                <p>Vytvořeno pro interní účely<br>
                    <strong>Gymnázium Sokolov a Krajské vzdělávací centrum, příspěvková organizace</strong>.
                </p>
                <p>Pokud něco nefunguje, je to chyba administrátora.</p>
            </div>

            <!-- Sekce kontakt -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Kontakt</h3>
                <p>Autor: <strong>Alexander Vršek</strong> – nejlhorší programátor všech dob.</p>
                <p>Email: <a href="mailto:rotty55yt@gmail.com" class="text-blue-600 hover:underline">rotty55yt@gmail.com</a></p>
            </div>

            <!-- Sekce podpora -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Podpora</h3>
                <p>Podpoř mě, za to, jak hrozný je tohle adminský rozhraní.</p>
                <p><strong>ČÚ: 000000-0102020029/0100</strong></p>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-600">
            <p>„Člověk, který čte, žije tisíce životů, než zemře. Člověk, který nečte, žije jen jeden.“</p>
            <p>— George R.R. Martin</p>
        </div>
    </div>
</footer>

<?php
}
?>