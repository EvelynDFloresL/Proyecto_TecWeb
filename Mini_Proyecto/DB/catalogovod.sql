-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2023 a las 03:57:45
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `catalogovod`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido`
--

CREATE TABLE `contenido` (
  `id_contenido` bigint(20) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `region` varchar(10) NOT NULL,
  `genero` varchar(20) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `duracion` time DEFAULT NULL,
  `eliminado` tinyint(4) NOT NULL,
  `id_cuenta` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contenido`
--

INSERT INTO `contenido` (`id_contenido`, `tipo`, `region`, `genero`, `titulo`, `duracion`, `eliminado`, `id_cuenta`) VALUES
(1, 'pelicula', 'MEX', 'Ciencia Ficcion', 'Blade Runner', '01:40:00', 0, 1),
(2, 'pelicula', 'MEX', 'Ciencia Ficcion', 'Indiana Jones', '02:06:00', 0, 1),
(3, 'pelicula', 'MEX', 'Romance', 'Amor entre dos extremos', '02:04:00', 0, 1),
(4, 'pelicula', 'MEX', 'Romance', 'La La Land', '01:42:00', 0, 1),
(5, 'serie', 'MEX', 'Animacion', 'Hora de aventura', '00:40:00', 0, 1),
(6, 'serie', 'MEX', 'Animacion', 'Gravity Falls', '00:40:00', 0, 1),
(7, 'serie', 'MEX', 'Suspenso', 'Stranger Things', '00:45:00', 0, 1),
(8, 'serie', 'MEX', 'Suspenso', 'El juego del calamar', '00:50:00', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id_cuenta` bigint(20) NOT NULL,
  `correo` varchar(25) NOT NULL,
  `eliminado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`id_cuenta`, `correo`, `eliminado`) VALUES
(1, 'evelyn.floresl@gmail.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` bigint(20) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `idioma` varchar(10) NOT NULL,
  `eliminado` tinyint(4) NOT NULL,
  `id_cuenta` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `usuario`, `idioma`, `eliminado`, `id_cuenta`) VALUES
(1, 'Evelyn Flores', 'Español', 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contenido`
--
ALTER TABLE `contenido`
  ADD PRIMARY KEY (`id_contenido`),
  ADD KEY `fk_id_cuenta` (`id_cuenta`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id_cuenta`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id_perfil`),
  ADD KEY `fk_id_cuentaContenido` (`id_cuenta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contenido`
--
ALTER TABLE `contenido`
  MODIFY `id_contenido` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id_cuenta` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD CONSTRAINT `fk_id_cuentaContenido` FOREIGN KEY (`id_cuenta`) REFERENCES `cuenta` (`id_cuenta`),
  ADD CONSTRAINT `fk_id_cuentaPerfiles` FOREIGN KEY (`id_cuenta`) REFERENCES `cuenta` (`id_cuenta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
