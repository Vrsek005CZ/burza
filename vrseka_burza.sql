-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Počítač: gymso-online-db
-- Vytvořeno: Pon 28. dub 2025, 21:41
-- Verze serveru: 9.3.0
-- Verze PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `vrseka_burza`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int NOT NULL,
  `nazev` varchar(32) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazev`) VALUES
(1, 'Matematika'),
(2, 'Anglický jazyk'),
(3, 'BiologieHA'),
(4, 'Chemie');

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `puID` int NOT NULL,
  `cas` int NOT NULL,
  `complete` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`id`, `puID`, `cas`, `complete`) VALUES
(75, 10, 1745415551, 0),
(76, 97, 1745415816, 0),
(78, 9, 1745564092, 0),
(79, 8, 1745564134, 0),
(80, 4, 1745606049, 1),
(81, 6, 1745606235, 1),
(82, 7, 1745652682, 0),
(84, 109, 1745682781, 0),
(85, 110, 1745683632, 1),
(86, 11, 1745684903, 1),
(87, 104, 1745874724, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pu`
--

CREATE TABLE `pu` (
  `id` int NOT NULL,
  `id_ucebnice` int NOT NULL,
  `id_prodejce` int NOT NULL,
  `rok_tisku` int NOT NULL,
  `stav` int NOT NULL,
  `cena` int NOT NULL,
  `koupil` int NOT NULL,
  `poznamky` varchar(256) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `pu`
--

INSERT INTO `pu` (`id`, `id_ucebnice`, `id_prodejce`, `rok_tisku`, `stav`, `cena`, `koupil`, `poznamky`) VALUES
(4, 1, 5, 2015, 6, 409, 9, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. sNulla facilisi. Quisque vel felis eu orci tincidunt tristique. Donec convallis sem ut sapien tincidunt, nec aliquam nisi bibendum. Ut non risus vel metus sollicitudin pulvinar non eget ligula. ssssg'),
(6, 2, 5, 2020, 10, 1250, 9, 'pěkný stavxx'),
(7, 2, 5, 2013, 10, 150, 9, 'nová'),
(8, 2, 4, 1900, 4, 10, 15, 'testssxxx'),
(9, 2, 4, 1900, 1, 4, 15, 'testš'),
(10, 1, 4, 1900, 1, 1, 6, 'test3'),
(11, 1, 4, 1900, 5, 5, 13, 'testssx231'),
(12, 1, 7, 2012, 8, 500, 0, 'skoro nová, ohlé rohy'),
(13, 2, 7, 1900, 1, 1, 0, 'test 2'),
(14, 1, 7, 2014, 7, 450, 0, 'znatelně opotřebovaná, ohlé rohy, popsaná tužkou'),
(15, 1, 4, 1900, 1, 1, 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. sNulla facilisi. Quisque vel felis eu orci tincidunt tristique. Donec convallis sem ut sapien tincidunt, nec aliquam nisi bibendum. Ut non risus vel metus sollicitudin pulvinar non eget ligula. ssssL'),
(16, 1, 4, 2025, 10, 9999, 0, '554'),
(17, 1, 4, 9999, 5, 5, 0, '5'),
(18, 1, 0, 1900, 1, 0, 0, '5555'),
(19, 1, 0, 1900, 1, 0, 0, '5555'),
(20, 1, 0, 1900, 1, 0, 0, '5555'),
(21, 1, 4, 1900, 1, 0, 0, '5555'),
(22, 1, 4, 1900, 1, 0, 0, '5555'),
(23, 1, 4, 1900, 1, 0, 0, '5555'),
(24, 1, 4, 1900, 1, 0, 0, '5555'),
(25, 1, 4, 1900, 1, 0, 0, '5555'),
(26, 1, 4, 1900, 1, 0, 0, '5555'),
(27, 1, 4, 1900, 1, 0, 0, '555'),
(28, 1, 4, 1900, 1, 0, 0, '555'),
(29, 2, 4, 1900, 5, 4, 0, 'test'),
(30, 1, 4, 1900, 1, 1, 0, '41'),
(31, 1, 4, 1900, 1, 1, 0, 'iojagi'),
(32, 1, 4, 1900, 1, 1, 0, 'sdasda'),
(33, 1, 4, 1900, 1, 1, 0, 'sdasda'),
(34, 1, 4, 1900, 1, 1, 0, 'sdasda'),
(35, 1, 4, 1900, 1, 1, 0, '1'),
(36, 1, 4, 1900, 1, 1, 0, '1'),
(37, 1, 4, 1900, 1, 1, 0, '1'),
(38, 1, 4, 1900, 1, 1, 0, 's'),
(39, 1, 4, 1900, 1, 1, 0, 'ssa'),
(40, 1, 4, 1900, 1, 1, 0, 'ssa'),
(41, 2, 4, 1900, 1, 1, 0, '4'),
(42, 2, 4, 1900, 1, 1, 0, 'sdas'),
(43, 2, 4, 1900, 1, 1, 0, 'sdas'),
(44, 2, 4, 1900, 1, 1, 0, 'sdas'),
(45, 2, 4, 1900, 1, 1, 0, '555'),
(46, 2, 4, 2010, 10, 100, 0, '666'),
(47, 1, 4, 1900, 1, 0, 0, '1'),
(48, 1, 4, 1900, 1, 0, 0, '151451'),
(50, 1, 4, 1900, 1, 1, 0, 'test kniha\r\n'),
(51, 4, 4, 1900, 1, 1, 0, 'sss'),
(52, 4, 4, 1900, 1, 1, 0, '1'),
(53, 4, 4, 1901, 3, 2, 0, '4'),
(54, 4, 4, 1901, 3, 2, 0, '4'),
(55, 8, 4, 1900, 1, 1, 0, '1'),
(56, 7, 4, 1900, 4, 4, 0, '4'),
(57, 8, 4, 1900, 4, 4, 0, '4'),
(58, 8, 4, 1900, 4, 4, 0, '4'),
(59, 8, 4, 1900, 4, 4, 0, ''),
(60, 8, 4, 1900, 4, 4, 0, ''),
(61, 4, 4, 1900, 7, 277, 0, 'Mobil'),
(62, 4, 4, 1900, 7, 277, 0, 'Mobil'),
(63, 4, 4, 1900, 7, 277, 0, 'Mobil'),
(64, 6, 4, 2000, 5, 111, 0, '444'),
(65, 9, 4, 2010, 10, 1010, 0, '100100'),
(66, 10, 4, 2004, 4, 444, 0, '444'),
(67, 12, 4, 2004, 4, 444, 0, '444'),
(68, 12, 4, 2025, 10, 222, 0, '222'),
(69, 1, 4, 2022, 7, 2022, 0, '2022'),
(70, 1, 4, 2006, 6, 666, 0, '666'),
(71, 1, 4, 2005, 5, 555, 0, '555'),
(72, 1, 4, 2004, 4, 444, 0, '444'),
(73, 13, 4, 2005, 5, 2005, 0, '555'),
(74, 13, 4, 2004, 4, 44, 0, 'fot otest'),
(75, 13, 4, 2005, 5, 555, 0, '55'),
(76, 13, 4, 2005, 5, 222, 0, 'sss'),
(77, 13, 4, 2005, 5, 222, 0, 'ě'),
(78, 13, 4, 2002, 5, 222, 0, '222'),
(79, 13, 4, 2005, 5, 222, 0, '222'),
(80, 13, 4, 2002, 5, 22, 0, '22'),
(81, 14, 4, 2015, 9, 152, 0, 'opeak '),
(82, 14, 4, 2005, 6, 666, 0, '66'),
(83, 14, 4, 2005, 5, 55, 0, '555'),
(84, 1, 4, 2005, 5, 555, 0, '5555ř'),
(85, 1, 4, 2005, 10, 55, 0, '555'),
(86, 1, 4, 2022, 3, 222, 0, '222'),
(87, 1, 4, 2022, 3, 222, 0, '222'),
(88, 1, 4, 2022, 5, 222, 0, '22'),
(89, 1, 4, 2022, 5, 222, 0, '22'),
(90, 1, 4, 2009, 10, 222, 0, '222'),
(91, 1, 4, 2002, 2, 22, 0, '222'),
(92, 1, 4, 2002, 5, 222, 0, '222'),
(93, 1, 4, 1994, 8, 138, 0, 'skibidi ohio rizzler'),
(94, 1, 7, 2022, 9, 550, 0, 'skoro nový, téměř bez známek použití'),
(95, 1, 7, 2015, 5, 300, 0, 'Velmi opotřebená, popsaná tužkou, potrhané desky'),
(96, 1, 7, 2022, 10, 600, 0, 'úplně nova, nepoužita'),
(97, 19, 6, 2005, 10, 315, 4, 'TEST ONLINE'),
(98, 19, 6, 2005, 10, 315, 0, 'TEST ONLINE'),
(99, 19, 6, 2005, 10, 315, 0, 'TEST ONLINE'),
(100, 19, 4, 2010, 10, 110, 0, 'foto'),
(101, 19, 4, 2010, 10, 110, 0, 'foto'),
(102, 19, 4, 2010, 10, 110, 0, 'foto'),
(103, 19, 4, 2010, 10, 110, 0, 'foto'),
(104, 1, 13, 2013, 6, 410, 4, 'těžké známky opotřebení, ohlé rohy, natržené stránky, popsaná tužkou i propiskou'),
(105, 20, 15, 2020, 2, 40, 0, ''),
(106, 20, 15, 2000, 5, 100000, 0, ''),
(107, 1, 9, 2024, 1, 100000, 0, ''),
(108, 19, 4, 2010, 10, 100, 0, '2156'),
(109, 1, 13, 20014, 8, 400, 6, 'velmi dobrý stav, nepopsaná, lehce ohlé rohy'),
(110, 1, 13, 2010, 3, 250, 7, 'hrozný stav'),
(111, 1, 4, 2010, 10, 400, 0, 'Test mobil'),
(112, 21, 5, 2010, 10, 300, 0, 'Test'),
(113, 21, 5, 2010, 10, 300, 0, 'Ud'),
(114, 21, 5, 2010, 10, 300, 0, 'Jsjd'),
(115, 21, 5, 2010, 10, 300, 0, 'Jsjdx'),
(116, 1, 13, 2010, 10, 333, 0, 'res'),
(117, 1, 13, 2010, 10, 333, 0, 'res'),
(118, 1, 13, 2010, 10, 434, 0, 'sda'),
(119, 1, 13, 1900, 5, 555, 0, '555'),
(120, 1, 13, 1900, 5, 555, 0, '555fo'),
(121, 19, 13, 2010, 10, 300, 0, '200'),
(122, 19, 13, 2010, 10, 300, 0, '200'),
(123, 19, 13, 2010, 10, 300, 0, '200'),
(124, 19, 13, 2005, 10, 110, 0, '000'),
(125, 19, 13, 2001, 10, 100, 0, '100'),
(126, 19, 13, 2001, 10, 100, 0, '1000'),
(127, 19, 5, 2010, 10, 100, 0, 'Čau '),
(128, 1, 5, 2014, 7, 400, 0, 'Hezka'),
(129, 1, 5, 2011, 8, 300, 0, 'Pekna'),
(130, 1, 5, 2015, 10, 500, 0, 'Velmi pekna'),
(131, 1, 5, 2015, 10, 100, 0, 'Test'),
(132, 1, 5, 2010, 10, 105, 0, 'Test'),
(133, 1, 5, 2010, 10, 100, 0, 'Test 25'),
(134, 1, 5, 2010, 10, 100, 0, 'Test 1'),
(135, 1, 5, 2010, 10, 100, 0, 'Test 5'),
(136, 1, 5, 2010, 10, 100, 0, 'Test foto'),
(137, 1, 5, 2010, 10, 100, 0, 'Tets'),
(138, 1, 4, 2010, 10, 100, 0, 'Autoor'),
(139, 24, 4, 2010, 10, 100, 0, 'Test sell'),
(140, 24, 4, 2010, 10, 100, 0, 'Test edit'),
(141, 24, 4, 2010, 10, 100, 0, 'Test admin'),
(142, 26, 4, 2010, 10, 100, 0, '100'),
(143, 29, 4, 2010, 10, 100, 0, '100');

-- --------------------------------------------------------

--
-- Struktura tabulky `typ`
--

CREATE TABLE `typ` (
  `id` int NOT NULL,
  `nazev` varchar(32) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `typ`
--

INSERT INTO `typ` (`id`, `nazev`) VALUES
(1, 'Učebnice'),
(2, 'Pracovní sešit'),
(3, 'Kniha');

-- --------------------------------------------------------

--
-- Struktura tabulky `ucebnice`
--

CREATE TABLE `ucebnice` (
  `id` int NOT NULL,
  `jmeno` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `kategorie_id` int NOT NULL,
  `typ_id` int NOT NULL,
  `trida_id` int NOT NULL,
  `schvaleno` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `ucebnice`
--

INSERT INTO `ucebnice` (`id`, `jmeno`, `kategorie_id`, `typ_id`, `trida_id`, `schvaleno`) VALUES
(1, 'Maturita Solutions (2nd Edition) Upper-Intermediate Student´s Book', 2, 1, 8, 1),
(2, 'Matematika pro 9.r.ZŠ,1.d.-Odvárko,Kadleček', 1, 1, 4, 1),
(19, 'TEST', 3, 1, 1, 1),
(20, 'Sbírka úloh matematiky pro střední školy', 1, 1, 5, 1),
(21, 'Kniha', 1, 1, 1, 1),
(22, 'Chemie', 4, 1, 1, 1),
(23, 'Chemie', 4, 1, 1, 1),
(24, 'Test foto', 3, 1, 1, 1),
(25, 'test fv', 1, 1, 1, 1),
(26, 'test fv', 1, 1, 1, 1),
(27, 'test ff', 4, 1, 1, 1),
(28, 'testttt', 1, 1, 1, 1),
(29, 'TEST FINAl', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `user` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `jmeno` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `prijmeni` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `trida_id` int NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `user`, `email`, `jmeno`, `prijmeni`, `trida_id`, `type`) VALUES
(4, 'vrseka', 'vrseka@gymso.cz', 'Alexander', 'Vršek', 8, 3),
(5, 'alecstage9836', 'alecstage9836@gmail.com', 'Alec', 'Stage', 7, 0),
(6, 'vrsek005cz', 'vrsek005cz@gmail.com', 'Vrsek005CZ', '', 4, 0),
(7, 'rotty55yt', 'rotty55yt@gmail.com', 'Alexander', 'Vršek', 8, 1),
(9, 'lukas.marek', 'lukas.marek@gymso.cz', 'Lukáš', 'Marek', 8, 2),
(13, 'vrsekalexander', 'vrsekalexander@gmail.com', 'Alexander', 'Vršek', 3, 0),
(15, 'psvacha', 'psvacha@gymso.cz', 'Pavel', 'Švácha', 5, 2);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `pu`
--
ALTER TABLE `pu`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `typ`
--
ALTER TABLE `typ`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `ucebnice`
--
ALTER TABLE `ucebnice`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT pro tabulku `pu`
--
ALTER TABLE `pu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT pro tabulku `typ`
--
ALTER TABLE `typ`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `ucebnice`
--
ALTER TABLE `ucebnice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
