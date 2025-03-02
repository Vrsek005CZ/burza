-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 02. bře 2025, 17:47
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `burza`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazev` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazev`) VALUES
(1, 'Matematika'),
(2, 'Anglický jazyk'),
(3, 'Biologie'),
(4, 'Chemie');

-- --------------------------------------------------------

--
-- Struktura tabulky `pu`
--

CREATE TABLE `pu` (
  `id` int(11) NOT NULL,
  `id_ucebnice` int(4) NOT NULL,
  `id_prodejce` int(4) NOT NULL,
  `rok_tisku` int(4) NOT NULL,
  `stav` int(2) NOT NULL,
  `cena` int(8) NOT NULL,
  `koupil` int(4) NOT NULL,
  `cas` int(11) DEFAULT NULL,
  `poznamky` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `pu`
--

INSERT INTO `pu` (`id`, `id_ucebnice`, `id_prodejce`, `rok_tisku`, `stav`, `cena`, `koupil`, `cas`, `poznamky`) VALUES
(4, 1, 5, 2015, 7, 400, 4, 1740759220, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. sNulla facilisi. Quisque vel felis eu orci tincidunt tristique. Donec convallis sem ut sapien tincidunt, nec aliquam nisi bibendum. Ut non risus vel metus sollicitudin pulvinar non eget ligula. ssss\n'),
(5, 2, 4, 2014, 7, 100, 0, NULL, 'pěkný stavvvvv'),
(6, 2, 5, 2013, 8, 125, 0, NULL, 'pěkný stav'),
(7, 2, 5, 2013, 10, 150, 0, NULL, 'nová'),
(8, 2, 4, 1900, 4, 1, 0, NULL, 'test'),
(9, 2, 4, 1900, 1, 2, 0, NULL, 'test2'),
(10, 1, 4, 1900, 1, 1, 0, NULL, 'test3'),
(11, 1, 4, 1900, 5, 5, 0, NULL, 'test'),
(12, 1, 7, 1900, 1, 2, 0, NULL, 'test user'),
(13, 2, 7, 1900, 1, 1, 0, NULL, 'test 2'),
(14, 1, 7, 0, 0, 0, 0, NULL, 'fdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfg'),
(15, 1, 4, 1900, 1, 1, 0, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. sNulla facilisi. Quisque vel felis eu orci tincidunt tristique. Donec convallis sem ut sapien tincidunt, nec aliquam nisi bibendum. Ut non risus vel metus sollicitudin pulvinar non eget ligula. ssssL'),
(16, 1, 4, 2025, 10, 9999, 0, NULL, '554'),
(17, 1, 4, 9999, 5, 5, 0, NULL, '5'),
(18, 1, 0, 1900, 1, 0, 0, NULL, '5555'),
(19, 1, 0, 1900, 1, 0, 0, NULL, '5555'),
(20, 1, 0, 1900, 1, 0, 0, NULL, '5555'),
(21, 1, 4, 1900, 1, 0, 0, NULL, '5555'),
(22, 1, 4, 1900, 1, 0, 0, NULL, '5555'),
(23, 1, 4, 1900, 1, 0, 0, NULL, '5555'),
(24, 1, 4, 1900, 1, 0, 0, NULL, '5555'),
(25, 1, 4, 1900, 1, 0, 0, NULL, '5555'),
(26, 1, 4, 1900, 1, 0, 0, NULL, '5555'),
(27, 1, 4, 1900, 1, 0, 0, NULL, '555'),
(28, 1, 4, 1900, 1, 0, 0, NULL, '555'),
(29, 2, 4, 1900, 5, 4, 0, NULL, 'test'),
(30, 1, 4, 1900, 1, 1, 0, NULL, '41'),
(31, 1, 4, 1900, 1, 1, 0, NULL, 'iojagi'),
(32, 1, 4, 1900, 1, 1, 0, NULL, 'sdasda'),
(33, 1, 4, 1900, 1, 1, 0, NULL, 'sdasda'),
(34, 1, 4, 1900, 1, 1, 0, NULL, 'sdasda'),
(35, 1, 4, 1900, 1, 1, 0, NULL, '1'),
(36, 1, 4, 1900, 1, 1, 0, NULL, '1'),
(37, 1, 4, 1900, 1, 1, 0, NULL, '1'),
(38, 1, 4, 1900, 1, 1, 0, NULL, 's'),
(39, 1, 4, 1900, 1, 1, 0, NULL, 'ssa'),
(40, 1, 4, 1900, 1, 1, 0, NULL, 'ssa'),
(41, 2, 4, 1900, 1, 1, 0, NULL, '4'),
(42, 2, 4, 1900, 1, 1, 0, NULL, 'sdas'),
(43, 2, 4, 1900, 1, 1, 0, NULL, 'sdas'),
(44, 2, 4, 1900, 1, 1, 0, NULL, 'sdas'),
(45, 2, 4, 1900, 1, 1, 0, NULL, '555'),
(46, 2, 4, 2010, 10, 100, 0, NULL, '666'),
(47, 1, 4, 1900, 1, 0, 0, NULL, '1'),
(48, 1, 4, 1900, 1, 0, 0, NULL, '151451'),
(50, 1, 4, 1900, 1, 1, 0, NULL, 'test kniha\r\n'),
(51, 4, 4, 1900, 1, 1, 0, NULL, 'sss'),
(52, 4, 4, 1900, 1, 1, 0, NULL, '1'),
(53, 4, 4, 1901, 3, 2, 0, NULL, '4'),
(54, 4, 4, 1901, 3, 2, 0, NULL, '4'),
(55, 8, 4, 1900, 1, 1, 0, NULL, '1'),
(56, 7, 4, 1900, 4, 4, 0, NULL, '4'),
(57, 8, 4, 1900, 4, 4, 0, NULL, '4'),
(58, 8, 4, 1900, 4, 4, 0, NULL, '4'),
(59, 8, 4, 1900, 4, 4, 0, NULL, ''),
(60, 8, 4, 1900, 4, 4, 0, NULL, ''),
(61, 4, 4, 1900, 7, 277, 0, NULL, 'Mobil'),
(62, 4, 4, 1900, 7, 277, 0, NULL, 'Mobil'),
(63, 4, 4, 1900, 7, 277, 0, NULL, 'Mobil');

-- --------------------------------------------------------

--
-- Struktura tabulky `typ`
--

CREATE TABLE `typ` (
  `id` int(11) NOT NULL,
  `nazev` varchar(32) NOT NULL
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
  `id` int(11) NOT NULL,
  `jmeno` varchar(128) NOT NULL,
  `kategorie_id` int(4) NOT NULL,
  `typ_id` int(2) NOT NULL,
  `trida_id` int(2) NOT NULL,
  `schvaleno` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `ucebnice`
--

INSERT INTO `ucebnice` (`id`, `jmeno`, `kategorie_id`, `typ_id`, `trida_id`, `schvaleno`) VALUES
(1, 'Maturita Solutions (2nd Edition) Upper-Intermediate Student´s Book', 2, 1, 8, 1),
(2, 'Matematika pro 9.r.ZŠ,1.d.-Odvárko,Kadleček', 1, 1, 4, 1),
(3, '0', 1, 1, 1, 1),
(4, 'BIo', 3, 1, 1, 1),
(5, '1', 1, 1, 1, 1),
(6, '5', 1, 1, 1, 1),
(7, 'TEST', 2, 2, 5, 1),
(8, 'TEST', 2, 2, 5, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id` int(8) NOT NULL,
  `user` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `jmeno` varchar(32) NOT NULL,
  `prijmeni` varchar(32) NOT NULL,
  `trida_id` int(2) NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `user`, `email`, `jmeno`, `prijmeni`, `trida_id`, `type`) VALUES
(4, 'vrseka', 'vrseka@gymso.cz', 'Alexander', 'Vršek', 8, 2),
(5, 'alecstage9836', 'alecstage9836@gmail.com', 'Alec', 'Stage', 7, 0),
(6, 'vrsek005cz', 'vrsek005cz@gmail.com', 'Vrsek005CZ', '', 4, 0),
(7, 'rotty55yt', 'rotty55yt@gmail.com', 'Alexander', 'Vršek', 8, 1);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `pu`
--
ALTER TABLE `pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pro tabulku `typ`
--
ALTER TABLE `typ`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `ucebnice`
--
ALTER TABLE `ucebnice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
