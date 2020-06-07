-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Apr 2020 um 15:24
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `druckerverwaltungstool`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `addon`
--

CREATE TABLE `addon` (
  `ID` int(11) NOT NULL,
  `name` char(180) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `contactID` int(11) DEFAULT NULL,
  `printerID` int(11) DEFAULT NULL,
  `typ` varchar(180) DEFAULT NULL,
  `min_quantity` int(11) DEFAULT NULL,
  `customID` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contact`
--

CREATE TABLE `contact` (
  `ID` int(11) NOT NULL,
  `name` char(180) DEFAULT NULL,
  `adress` char(180) DEFAULT NULL,
  `phonenumber` char(180) DEFAULT NULL,
  `website` char(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `contact`
--

INSERT INTO `contact` (`ID`, `name`, `adress`, `phonenumber`, `website`) VALUES
(1, 'Offits', 'BahnhofstraÃŸe 38, 94469 Deggendorf', '0991 2979232', 'https://offits.net');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dc`
--

CREATE TABLE `dc` (
  `DC_server` varchar(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `dc`
--

INSERT INTO `dc` (`DC_server`) VALUES
('192.166.248.24');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `location`
--

CREATE TABLE `location` (
  `ID` int(11) NOT NULL,
  `name` char(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `location`
--

INSERT INTO `location` (`ID`, `name`) VALUES
(1, 'giesserei'),
(2, 'einkauf'),
(3, 'Verpackung (Rechts)'),
(4, 'Verpackung (Links)'),
(5, 'Druckerinsel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `vDate` datetime DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `contactID` int(11) DEFAULT NULL,
  `order_serie` int(11) DEFAULT NULL,
  `tonerID` int(11) DEFAULT NULL,
  `addonID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `printer`
--

CREATE TABLE `printer` (
  `ID` int(11) NOT NULL,
  `name` char(180) DEFAULT NULL,
  `ip` char(180) DEFAULT NULL,
  `customID` double DEFAULT NULL,
  `locationID` int(11) DEFAULT NULL,
  `contactID` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `printer`
--

INSERT INTO `printer` (`ID`, `name`, `ip`, `customID`, `locationID`, `contactID`, `active`) VALUES
(2, 'PRN011', '192.166.251.49', 3003919018, 4, 1, NULL),
(3, 'PRN021', '192.166.248.102', 18023620002, 3, 1, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `toner`
--

CREATE TABLE `toner` (
  `ID` int(11) NOT NULL,
  `name` char(180) DEFAULT NULL,
  `color` char(180) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `customID` double DEFAULT NULL,
  `min_quantity` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `vChange` tinyint(1) DEFAULT NULL,
  `printerID` int(11) DEFAULT NULL,
  `contactID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `toner`
--

INSERT INTO `toner` (`ID`, `name`, `color`, `quantity`, `customID`, `min_quantity`, `level`, `vChange`, `printerID`, `contactID`) VALUES
(1, '821259', 'black', 0, 0, 1, 20, 0, 2, 1),
(2, '821262', 'cyan', 0, 0, 1, 70, 0, 2, 1),
(3, '821261', 'magenta', 0, 0, 1, 100, 0, 2, 1),
(4, '821260', 'yellow', 0, 0, 1, 20, 0, 2, 1),
(5, '821259', 'black', 1, 0, 0, 70, 0, NULL, 1),
(6, '821262', 'cyan', 0, 0, 0, 30, 0, NULL, 1),
(7, '821261', 'magenta', 0, 0, 0, 80, 0, NULL, 1),
(8, '821260', 'yellow', 0, 0, 0, 30, 0, NULL, 1),
(9, 'TN513', 'black', 0, 0, 1, 76, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `name` char(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`ID`, `name`) VALUES
(1, 'administrator');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `addon`
--
ALTER TABLE `addon`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `contactID` (`contactID`),
  ADD KEY `printerID` (`printerID`);

--
-- Indizes für die Tabelle `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `contactID` (`contactID`),
  ADD KEY `tonerID` (`tonerID`),
  ADD KEY `addonID` (`addonID`);

--
-- Indizes für die Tabelle `printer`
--
ALTER TABLE `printer`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `locationID` (`locationID`),
  ADD KEY `contactID` (`contactID`);

--
-- Indizes für die Tabelle `toner`
--
ALTER TABLE `toner`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `printerID` (`printerID`),
  ADD KEY `contactID` (`contactID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `addon`
--
ALTER TABLE `addon`
  ADD CONSTRAINT `addon_ibfk_1` FOREIGN KEY (`contactID`) REFERENCES `contact` (`ID`),
  ADD CONSTRAINT `addon_ibfk_2` FOREIGN KEY (`printerID`) REFERENCES `printer` (`ID`);

--
-- Constraints der Tabelle `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`contactID`) REFERENCES `contact` (`ID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`tonerID`) REFERENCES `toner` (`ID`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`addonID`) REFERENCES `addon` (`ID`);

--
-- Constraints der Tabelle `printer`
--
ALTER TABLE `printer`
  ADD CONSTRAINT `printer_ibfk_1` FOREIGN KEY (`locationID`) REFERENCES `location` (`ID`),
  ADD CONSTRAINT `printer_ibfk_2` FOREIGN KEY (`contactID`) REFERENCES `contact` (`ID`);

--
-- Constraints der Tabelle `toner`
--
ALTER TABLE `toner`
  ADD CONSTRAINT `toner_ibfk_1` FOREIGN KEY (`printerID`) REFERENCES `printer` (`ID`),
  ADD CONSTRAINT `toner_ibfk_2` FOREIGN KEY (`contactID`) REFERENCES `contact` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
