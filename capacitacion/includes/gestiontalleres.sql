-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-07-2024 a las 21:30:41
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestiontalleres`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `inscripcion_id` int(11) NOT NULL,
  `taller_id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`inscripcion_id`, `taller_id`, `participante_id`, `fecha_inscripcion`) VALUES
(1, 5, 5, '2024-07-18 14:11:29'),
(2, 1, 2, '2024-07-18 14:11:29'),
(3, 5, 1, '2024-07-18 14:11:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `participante_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participantes`
--

INSERT INTO `participantes` (`participante_id`, `nombre`, `apellido`, `email`, `telefono`) VALUES
(1, 'Ana', 'Gómez', 'ana.gomez@example.com', '1234567890'),
(2, 'Carlos', 'Pérez', 'carlos.perez@example.com', '0987654321'),
(3, 'María', 'López', 'maria.lopez@example.com', '111'),
(4, 'Juan', 'Martínez', 'juan.martinez@example.com', '4445556667'),
(5, 'Lucía', 'Rodríguez', 'lucia.rodriguez@example.com', '7778889990'),
(6, 'Ismael', 'verdesoto', 'verdesotoruben@gmail.com', '0996344075');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `taller_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `ubicacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`taller_id`, `nombre`, `descripcion`, `fecha`, `ubicacion`) VALUES
(1, 'Taller de Fotografía', 'Aprende las técnicas básicas y avanzadas de fotografía', '2024-08-01', 'Sala Aa'),
(2, 'Taller de Programación en Python', 'Introducción y conceptos avanzados de Python', '2024-08-05', 'Sala B'),
(3, 'Taller de Cocina Italiana', 'Explora las recetas tradicionales italianas', '2024-08-10', 'Cocina 1'),
(4, 'Taller de Pintura al Óleo', 'Técnicas y práctica de la pintura al óleo', '2024-08-15', 'Sala C'),
(5, 'Taller de Yoga y Meditación', 'Relájate y encuentra tu equilibrio interior', '2024-08-20', 'Sala D');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`inscripcion_id`),
  ADD KEY `taller_id` (`taller_id`),
  ADD KEY `participante_id` (`participante_id`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`participante_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`taller_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `inscripcion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `participante_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `taller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`taller_id`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`participante_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
