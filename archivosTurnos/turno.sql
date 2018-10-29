-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-10-2018 a las 18:25:19
-- Versión del servidor: 10.1.30-MariaDB
-- Versión de PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistematurnos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id` int(11) NOT NULL,
  `nFolio` varchar(50) NOT NULL,
  `nDocumento` varchar(50) NOT NULL,
  `fechaRecibe` date NOT NULL,
  `fechaDocumento` int(11) NOT NULL,
  `idTipoTurno` int(11) NOT NULL,
  `idAreaRemite` int(11) NOT NULL,
  `idAreaBeneficia` int(11) NOT NULL,
  `idResponsableAtencion` int(11) NOT NULL,
  `instruccionesAten` varchar(500) NOT NULL,
  `documentoRuta` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipoTurno` (`idTipoTurno`),
  ADD KEY `idAreaRemite` (`idAreaRemite`),
  ADD KEY `idAreaBeneficia` (`idAreaBeneficia`),
  ADD KEY `idResponsableAtencion` (`idResponsableAtencion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `turno`
--
ALTER TABLE `turno`
  ADD CONSTRAINT `turno_ibfk_1` FOREIGN KEY (`idTipoTurno`) REFERENCES `tipoturno` (`Id`),
  ADD CONSTRAINT `turno_ibfk_2` FOREIGN KEY (`idAreaRemite`) REFERENCES `arearemite` (`Id`),
  ADD CONSTRAINT `turno_ibfk_3` FOREIGN KEY (`idAreaBeneficia`) REFERENCES `areabeneficiada` (`Id`),
  ADD CONSTRAINT `turno_ibfk_4` FOREIGN KEY (`idResponsableAtencion`) REFERENCES `responsableatencion` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
