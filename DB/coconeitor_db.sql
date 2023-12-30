-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-12-2023 a las 05:32:40
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `coconeitor_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_db`
--

CREATE TABLE `historial_db` (
  `idHist` int(11) NOT NULL,
  `userHist` varchar(40) NOT NULL,
  `pointsHist` int(11) NOT NULL,
  `gamesHist` int(11) NOT NULL,
  `gamesPlayedHist` int(11) NOT NULL,
  `dateHist` varchar(50) NOT NULL,
  `gameMode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `match_db`
--

CREATE TABLE `match_db` (
  `idMatch` int(11) NOT NULL,
  `numTeams` int(11) NOT NULL DEFAULT 2,
  `numUsers` int(11) NOT NULL,
  `rulesMode` enum('0','1') NOT NULL DEFAULT '1',
  `numGames` int(11) NOT NULL DEFAULT 0,
  `teamOne` text NOT NULL,
  `date` datetime NOT NULL,
  `token` varchar(255) NOT NULL DEFAULT '0',
  `gameMode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_db`
--

CREATE TABLE `users_db` (
  `idUser` int(11) NOT NULL,
  `idUserMatch` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `numGamesUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `historial_db`
--
ALTER TABLE `historial_db`
  ADD PRIMARY KEY (`idHist`);

--
-- Indices de la tabla `match_db`
--
ALTER TABLE `match_db`
  ADD PRIMARY KEY (`idMatch`);

--
-- Indices de la tabla `users_db`
--
ALTER TABLE `users_db`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `historial_db`
--
ALTER TABLE `historial_db`
  MODIFY `idHist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `match_db`
--
ALTER TABLE `match_db`
  MODIFY `idMatch` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `users_db`
--
ALTER TABLE `users_db`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=400;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
