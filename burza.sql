-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 14. dub 2025, 10:26
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
CREATE DATABASE IF NOT EXISTS `burza` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `burza`;

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
-- Struktura tabulky `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `puID` int(11) NOT NULL,
  `cas` int(32) NOT NULL,
  `complete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`id`, `puID`, `cas`, `complete`) VALUES
(1, 56, 1741030190, 1),
(2, 10, 1741111490, 1),
(3, 11, 1741111494, 0),
(4, 15, 1741111497, 0),
(5, 9, 1741111504, 1),
(6, 6, 1741111528, 0),
(7, 12, 1743263513, 1),
(8, 7, 1743663937, 0),
(9, 13, 1743664326, 0);

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
  `poznamky` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `pu`
--

INSERT INTO `pu` (`id`, `id_ucebnice`, `id_prodejce`, `rok_tisku`, `stav`, `cena`, `koupil`, `poznamky`) VALUES
(4, 1, 5, 2015, 6, 409, 4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. sNulla facilisi. Quisque vel felis eu orci tincidunt tristique. Donec convallis sem ut sapien tincidunt, nec aliquam nisi bibendum. Ut non risus vel metus sollicitudin pulvinar non eget ligula. ssssg'),
(6, 2, 5, 2013, 8, 125, 4, 'pěkný stav'),
(7, 2, 5, 2013, 10, 150, 4, 'nová'),
(8, 2, 4, 1900, 4, 1, 0, 'test'),
(9, 2, 4, 1900, 1, 2, 7, 'test2'),
(10, 1, 4, 1900, 1, 1, 7, 'test3'),
(11, 1, 4, 1900, 5, 5, 7, 'test'),
(12, 1, 7, 1900, 1, 2, 4, 'test user'),
(13, 2, 7, 1900, 1, 1, 4, 'test 2'),
(14, 1, 7, 0, 0, 0, 0, 'fdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfgsjdfzgoujisdfgojipsdfgjoisdfgjiosdfjoigdsfgjfdgdfg'),
(15, 1, 4, 1900, 1, 1, 7, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. sNulla facilisi. Quisque vel felis eu orci tincidunt tristique. Donec convallis sem ut sapien tincidunt, nec aliquam nisi bibendum. Ut non risus vel metus sollicitudin pulvinar non eget ligula. ssssL'),
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
(56, 7, 4, 1900, 4, 4, 7, '4'),
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
(83, 14, 4, 2005, 5, 55, 0, '555');

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
(8, 'TEST', 2, 2, 5, 1),
(9, 'Knížka', 3, 2, 5, 1),
(10, 'Knížka', 3, 2, 5, 1),
(11, 'Kniha', 3, 2, 4, 1),
(12, 'MATTIKAA', 1, 1, 1, 1),
(13, 'Bramborovy gulas MC FOTO', 2, 2, 1, 1),
(14, 'matiky', 1, 1, 8, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pro tabulku `pu`
--
ALTER TABLE `pu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT pro tabulku `typ`
--
ALTER TABLE `typ`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `ucebnice`
--
ALTER TABLE `ucebnice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Databáze: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Vypisuji data pro tabulku `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-04-14 08:25:49', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"cs\"}');

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Struktura tabulky `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexy pro tabulku `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexy pro tabulku `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexy pro tabulku `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexy pro tabulku `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexy pro tabulku `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexy pro tabulku `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexy pro tabulku `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexy pro tabulku `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexy pro tabulku `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexy pro tabulku `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexy pro tabulku `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexy pro tabulku `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexy pro tabulku `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexy pro tabulku `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexy pro tabulku `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexy pro tabulku `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexy pro tabulku `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Databáze: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
