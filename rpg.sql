-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-11-2024 a las 07:30:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rpg`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `UID` int(11) NOT NULL,
  `AdminLevel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `characterdetails`
--

CREATE TABLE `characterdetails` (
  `CharacterID` varchar(50) NOT NULL,
  `UID` int(11) NOT NULL,
  `Level` int(11) DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `characterdetails`
--

INSERT INTO `characterdetails` (`CharacterID`, `UID`, `Level`, `ItemID`) VALUES
('joselscharacter', 11, 5, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `characterinventory`
--

CREATE TABLE `characterinventory` (
  `CharacterID` varchar(50) NOT NULL,
  `InventoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `characterinventory`
--

INSERT INTO `characterinventory` (`CharacterID`, `InventoryID`) VALUES
('joselscharacter', 111);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory`
--

CREATE TABLE `inventory` (
  `InventoryID` int(11) NOT NULL,
  `Size` varchar(50) DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `inventory`
--

INSERT INTO `inventory` (`InventoryID`, `Size`, `ItemID`) VALUES
(111, '10', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `AttackBonus` int(11) NOT NULL,
  `DefenseBonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `AttackBonus`, `DefenseBonus`) VALUES
(1, 'Sword', 10, 5),
(2, 'Shield', 5, 15),
(3, 'Bow', 8, 4),
(4, 'Axe', 12, 3),
(5, 'Spear', 9, 6),
(6, 'Dagger', 7, 2),
(7, 'Mace', 11, 8),
(8, 'Staff', 6, 10),
(9, 'Crossbow', 10, 4),
(10, 'Halberd', 14, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monsters`
--

CREATE TABLE `monsters` (
  `MonsterID` int(11) NOT NULL,
  `QID` int(11) DEFAULT NULL,
  `Level` int(11) NOT NULL,
  `ItemID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `monsters`
--

INSERT INTO `monsters` (`MonsterID`, `QID`, `Level`, `ItemID`) VALUES
(1, 1, 1, 6),
(2, 2, 5, 7),
(3, 3, 2, 7),
(4, 4, 4, 8),
(5, 5, 6, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playerinfo`
--

CREATE TABLE `playerinfo` (
  `UID` int(11) NOT NULL,
  `completedQID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `playerinfo`
--

INSERT INTO `playerinfo` (`UID`, `completedQID`) VALUES
(11, 1),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quest`
--

CREATE TABLE `quest` (
  `QID` int(11) NOT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `IntroText` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `quest`
--

INSERT INTO `quest` (`QID`, `Location`, `IntroText`) VALUES
(1, 'Forest', 'You step into the dark forest, where ancient trees whisper secrets to each other. Suddenly, the ground trembles, and a monstrous roar pierces the stillness. A ferocious beast emerges from the shadows, its glowing eyes fixed on you. Prepare to fight for your survival!'),
(2, 'Desert', 'The desert’s scorching heat drains your energy, but your instincts scream to stay alert. Out of the shifting sands rises a massive creature, its scales shimmering like the sun. It charges at you with a deafening roar. There’s no turning back now—fight or perish!'),
(3, 'Mountains', 'As you ascend the steep, rocky path, the chilling wind howls like a warning. Out of the mist, a hulking monster with jagged claws and piercing eyes blocks your way. Its growl shakes the ground beneath your feet. Prove your strength, or be cast down!'),
(4, 'Cave', 'You step into the pitch-black cave, the air thick with the stench of decay. Suddenly, you hear a guttural growl that makes your heart race. A massive beast emerges from the shadows, its fangs glinting in the dim light. This creature defends its lair fiercely—prepare to fight!'),
(5, 'River', 'The sound of rushing water fills your ears as you approach the river. But then, a monstrous splash sends waves crashing toward you. From the depths emerges a fearsome aquatic beast, its jaws snapping with deadly intent. Will you conquer the river or fall to its guardian?');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `score`
--

CREATE TABLE `score` (
  `PlayedTime` int(11) NOT NULL,
  `TotalScore` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `score`
--

INSERT INTO `score` (`PlayedTime`, `TotalScore`) VALUES
(50, 1000),
(90, 2000),
(120, 3000),
(150, 4000),
(200, 5000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usercredentials`
--

CREATE TABLE `usercredentials` (
  `UserName` varchar(255) NOT NULL,
  `UserPassword` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usercredentials`
--

INSERT INTO `usercredentials` (`UserName`, `UserPassword`) VALUES
('josels', '$2y$10$6rZYHtx4QoJVZ1kQlg/wH.DIAEYzvKgMg5WJ7XNnTbnqE2BoCclCC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userdetails`
--

CREATE TABLE `userdetails` (
  `UID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `DateCreated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `userdetails`
--

INSERT INTO `userdetails` (`UID`, `Username`, `DateCreated`) VALUES
(11, 'josels', '2024-11-25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`UID`);

--
-- Indices de la tabla `characterdetails`
--
ALTER TABLE `characterdetails`
  ADD PRIMARY KEY (`CharacterID`),
  ADD UNIQUE KEY `CharacterID` (`CharacterID`),
  ADD KEY `UID` (`UID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indices de la tabla `characterinventory`
--
ALTER TABLE `characterinventory`
  ADD PRIMARY KEY (`CharacterID`),
  ADD UNIQUE KEY `InventoryID` (`InventoryID`);

--
-- Indices de la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`InventoryID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indices de la tabla `monsters`
--
ALTER TABLE `monsters`
  ADD PRIMARY KEY (`MonsterID`),
  ADD KEY `QID` (`QID`),
  ADD KEY `fk_monster_item` (`ItemID`);

--
-- Indices de la tabla `playerinfo`
--
ALTER TABLE `playerinfo`
  ADD KEY `fk_completedQID` (`completedQID`);

--
-- Indices de la tabla `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`QID`);

--
-- Indices de la tabla `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`PlayedTime`);

--
-- Indices de la tabla `usercredentials`
--
ALTER TABLE `usercredentials`
  ADD PRIMARY KEY (`UserName`);

--
-- Indices de la tabla `userdetails`
--
ALTER TABLE `userdetails`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `monsters`
--
ALTER TABLE `monsters`
  MODIFY `MonsterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `userdetails`
--
ALTER TABLE `userdetails`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `userdetails` (`UID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `characterdetails`
--
ALTER TABLE `characterdetails`
  ADD CONSTRAINT `characterdetails_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `userdetails` (`UID`) ON DELETE CASCADE,
  ADD CONSTRAINT `characterdetails_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`) ON DELETE SET NULL;

--
-- Filtros para la tabla `characterinventory`
--
ALTER TABLE `characterinventory`
  ADD CONSTRAINT `characterinventory_ibfk_1` FOREIGN KEY (`CharacterID`) REFERENCES `characterdetails` (`CharacterID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`InventoryID`) REFERENCES `characterinventory` (`InventoryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `monsters`
--
ALTER TABLE `monsters`
  ADD CONSTRAINT `fk_monster_item` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`),
  ADD CONSTRAINT `monsters_ibfk_1` FOREIGN KEY (`QID`) REFERENCES `quest` (`QID`);

--
-- Filtros para la tabla `playerinfo`
--
ALTER TABLE `playerinfo`
  ADD CONSTRAINT `fk_completedQID` FOREIGN KEY (`completedQID`) REFERENCES `quest` (`QID`);

--
-- Filtros para la tabla `usercredentials`
--
ALTER TABLE `usercredentials`
  ADD CONSTRAINT `usercredentials_ibfk_1` FOREIGN KEY (`UserName`) REFERENCES `userdetails` (`Username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Create Players Table
CREATE TABLE IF NOT EXISTS Players (
    player_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create CharacterDetails Table
CREATE TABLE IF NOT EXISTS CharacterDetails (
    CharacterID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT NOT NULL, -- Foreign key referencing Players(player_id)
    Level INT NOT NULL,
    FOREIGN KEY (UID) REFERENCES Players(player_id)
);

-- Insert Sample Data
INSERT IGNORE INTO Players (player_id, name) VALUES 
(1, 'Esteban'), 
(2, 'Maria');

INSERT IGNORE INTO CharacterDetails (CharacterID, UID, Level) VALUES
(1, 1, 10),
(2, 2, 5);

SELECT u.Username AS PlayerName, c.Level AS PlayerLevel
FROM userdetails u
JOIN characterdetails c ON u.UID = c.UID;