<?php
#VÍM, ŽE TOTO, CO JSEM STOŘIL, JE ABSOLUTNÍ VÝSMĚCH ČEMUKOLIV, CO MÁ SPOLEČNÉHO S POČÍTAČI. JE TO DŮKAZ TOHO, ŽE BUĎ JE BŮH VE SVÉM VESMÍRU NAPROSTO BEZMOČNÝ, ABY COKOLIV UDĚLAL S TÍMTO ZVĚRSTVEM, NEBO ŽE MU JE JEDNO, CO SE V JEHO KRÁLOVSTVÍ DĚJE. KDOKOLIV, KDO BY TOTO VIDĚL, BY VĚDĚL, ŽE JE TO ZVĚRSTVO. ALE FUNGUJE TO :D TAK TŘEBA NĚKOMU DALŠÍMU, KDO SE ZA PÁR LET BUDE V TOMTO KÓDU HRABAT, TAK MU TO DÁ ASPOŇ POVEDEMÍ O TOM, JAKÝ JSEM BYL ČLOVĚK. ČLOVĚK JEDNODUCHÝ A PŘÍMOČARÝ, KTERÝ SI VOLÍ TU NEJKRATŠÍ CESTU, I KDYŽ BY SE NAŠLY MNOHEM LEPŠÍ CESTY A ČLOVĚK, CO MÁ VŠE NA HÁKU.


// Příprava dotazu pro prodej
$sql_selling_complete = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.koupil = user.id
WHERE pu.id_prodejce = ? AND orders.complete = 0
ORDER BY orders.cas DESC
";

$sql_selling_pending = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.koupil = user.id
WHERE pu.id_prodejce = ? AND orders.complete = 1
ORDER BY orders.cas DESC
";


// Příprava dotazu pro nákup
$sql_buying_complete = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.id_prodejce = user.id
WHERE pu.koupil = ? AND orders.complete = 0
ORDER BY orders.cas DESC
";

$sql_buying_pending = "SELECT orders.id as order_id, orders.puID, orders.cas, orders.complete as complete, pu.id_ucebnice, pu.id_prodejce, pu.rok_tisku, pu.stav as stav, pu.cena as cena, pu.koupil as kupuje, pu.poznamky, ucebnice.jmeno as jmeno_ucebnice, user.user as user_jmeno, user.id as user_id
FROM orders
INNER JOIN pu ON orders.puID = pu.id
INNER JOIN ucebnice ON pu.id_ucebnice = ucebnice.id
INNER JOIN user ON pu.id_prodejce = user.id
WHERE pu.koupil = ? AND orders.complete = 1
ORDER BY orders.cas DESC
";

// Příprava a provedení dotazu pro prodej
$stmt_sell_complete = $conn->prepare($sql_selling_complete);
$stmt_sell_complete->bind_param("i", $userId); // Parametr je typu integer
$stmt_sell_complete->execute();
$resultsell_complete = $stmt_sell_complete->get_result();

$stmt_sell_pending = $conn->prepare($sql_selling_pending);
$stmt_sell_pending->bind_param("i", $userId); // Parametr je typu integer
$stmt_sell_pending->execute();
$resultsell_pending = $stmt_sell_pending->get_result();

// Příprava a provedení dotazu pro nákup
$stmt_buy_complete = $conn->prepare($sql_buying_complete);
$stmt_buy_complete->bind_param("i", $userId); // Parametr je typu integer
$stmt_buy_complete->execute();
$resultbuy_complete = $stmt_buy_complete->get_result();

$stmt_buy_pending = $conn->prepare($sql_buying_pending);
$stmt_buy_pending->bind_param("i", $userId); // Parametr je typu integer
$stmt_buy_pending->execute();
$resultbuy_pending = $stmt_buy_pending->get_result();

?>