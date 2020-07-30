-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2020 a las 01:23:34
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--
CREATE DATABASE IF NOT EXISTS `comanda` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `comanda`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `id_tipo_empleado` int(8) NOT NULL,
  `id_sector` int(8) NOT NULL,
  `estado` varchar(10) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `usuario`, `clave`, `id_tipo_empleado`, `id_sector`, `estado`) VALUES
(1, 'sebastian@aguirre', 'S3b4st14n', 1, 1, 'A'),
(2, 'juan@mozo', '12345678', 2, 2, 'A'),
(4, 'jose@mozo', '12345678', 2, 2, 'S'),
(5, 'juan@bartender', '12345678', 4, 4, 'E'),
(6, 'pedro@cocinero', '12345678', 3, 3, 'A'),
(7, 'mariano@cervecero', '12345678', 5, 5, 'A'),
(8, 'raul@pastelero', '12345678', 6, 6, 'S'),
(9, 'juan@bartender', '12345678', 4, 4, 'A'),
(10, 'carlos@mozo', '12345678', 2, 2, 'A'),
(11, 'micaela@bartender', '12345678', 4, 4, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(8) NOT NULL,
  `codigo_mesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `puntuacion_mesa` int(8) NOT NULL,
  `puntuacion_restaurante` int(8) NOT NULL,
  `puntuacion_mozo` int(8) NOT NULL,
  `puntuacion_cocinero` int(11) NOT NULL,
  `comentario` varchar(66) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `codigo_mesa`, `puntuacion_mesa`, `puntuacion_restaurante`, `puntuacion_mozo`, `puntuacion_cocinero`, `comentario`, `fecha`) VALUES
(1, '1zneg', 5, 5, 5, 5, 'Primer comentario.', '2020-07-29'),
(2, 'h8cs3', 8, 10, 6, 7, 'Muy bueno todo.', '2020-07-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_mesas`
--

CREATE TABLE `estado_mesas` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estado_mesas`
--

INSERT INTO `estado_mesas` (`id`, `descripcion`) VALUES
(1, 'clientes esperando pedido'),
(2, 'clientes comiendo'),
(3, 'clientes pagando'),
(4, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedidos`
--

CREATE TABLE `estado_pedidos` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estado_pedidos`
--

INSERT INTO `estado_pedidos` (`id`, `estado`) VALUES
(1, 'pendiente'),
(2, 'en preparacion'),
(3, 'listo para servir');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `id_mesa` int(10) NOT NULL,
  `total` float NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `id_mesa`, `total`, `fecha`) VALUES
(1, 1, 450, '2020-07-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loggers`
--

CREATE TABLE `loggers` (
  `id` int(11) NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `fecha_ingreso` date NOT NULL DEFAULT current_timestamp(),
  `hora_ingreso` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `loggers`
--

INSERT INTO `loggers` (`id`, `id_empleado`, `fecha_ingreso`, `hora_ingreso`) VALUES
(1, 1, '2020-07-19', '00:58:02'),
(2, 1, '2020-07-19', '01:11:17'),
(3, 2, '2020-07-19', '01:35:04'),
(4, 2, '2020-07-19', '01:35:11'),
(5, 2, '2020-07-19', '13:39:51'),
(6, 2, '2020-07-19', '14:49:36'),
(7, 1, '2020-07-19', '14:56:24'),
(8, 1, '2020-07-19', '21:56:19'),
(9, 2, '2020-07-19', '22:11:33'),
(10, 2, '2020-07-19', '22:57:21'),
(11, 2, '2020-07-19', '23:26:47'),
(12, 1, '2020-07-19', '23:56:23'),
(13, 1, '2020-07-22', '19:09:19'),
(14, 1, '2020-07-22', '19:21:13'),
(15, 1, '2020-07-22', '19:32:19'),
(16, 1, '2020-07-22', '21:06:05'),
(17, 1, '2020-07-22', '21:47:16'),
(18, 2, '2020-07-22', '22:12:54'),
(19, 4, '2020-07-22', '22:13:03'),
(20, 4, '2020-07-22', '22:16:07'),
(21, 2, '2020-07-22', '22:16:13'),
(22, 2, '2020-07-22', '22:16:50'),
(23, 2, '2020-07-22', '22:17:03'),
(24, 2, '2020-07-22', '22:18:19'),
(25, 2, '2020-07-22', '22:19:51'),
(26, 2, '2020-07-22', '22:20:51'),
(27, 2, '2020-07-22', '22:21:02'),
(28, 5, '2020-07-22', '22:22:25'),
(29, 2, '2020-07-22', '22:25:07'),
(30, 1, '2020-07-22', '22:27:43'),
(31, 1, '2020-07-22', '22:50:28'),
(32, 1, '2020-07-22', '22:51:06'),
(33, 2, '2020-07-22', '23:18:00'),
(34, 2, '2020-07-23', '20:09:08'),
(35, 10, '2020-07-23', '20:10:13'),
(36, 1, '2020-07-23', '20:15:52'),
(37, 2, '2020-07-23', '20:39:01'),
(38, 2, '2020-07-23', '21:04:02'),
(39, 2, '2020-07-23', '21:48:07'),
(40, 10, '2020-07-23', '21:48:19'),
(41, 10, '2020-07-23', '21:49:12'),
(42, 10, '2020-07-23', '22:33:19'),
(43, 1, '2020-07-23', '22:56:25'),
(44, 10, '2020-07-23', '23:09:08'),
(45, 10, '2020-07-23', '23:34:17'),
(46, 10, '2020-07-24', '00:27:55'),
(47, 10, '2020-07-24', '21:15:26'),
(48, 10, '2020-07-24', '21:22:32'),
(49, 1, '2020-07-24', '21:22:56'),
(50, 1, '2020-07-25', '01:09:26'),
(51, 1, '2020-07-25', '18:21:12'),
(52, 1, '2020-07-25', '18:41:35'),
(53, 1, '2020-07-29', '23:39:20'),
(54, 10, '2020-07-30', '19:37:01'),
(55, 6, '2020-07-30', '19:38:41'),
(56, 10, '2020-07-30', '19:40:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `id_estado` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `id_estado`) VALUES
(1, '1zneg', 3),
(2, '4dao1', 1),
(3, 'knr5t', 2),
(5, 'h8cs3', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `id` int(11) NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `operacion` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `operaciones`
--

INSERT INTO `operaciones` (`id`, `id_empleado`, `operacion`, `fecha`) VALUES
(1, 2, '/TP-La-Comanda/public/', '2020-07-19'),
(2, 2, '/TP-La-Comanda/public/', '2020-07-19'),
(3, 2, '/TP-La-Comanda/public/', '2020-07-19'),
(4, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-19'),
(5, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-19'),
(6, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-19'),
(7, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-19'),
(8, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-19'),
(9, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-19'),
(10, 1, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-22'),
(11, 1, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-22'),
(12, 1, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-22'),
(13, 1, '/TP-La-Comanda/public/', '2020-07-22'),
(14, 1, '/TP-La-Comanda/public/', '2020-07-22'),
(15, 1, '/TP-La-Comanda/public/', '2020-07-22'),
(16, 1, '/TP-La-Comanda/public/', '2020-07-22'),
(17, 1, '/TP-La-Comanda/public/', '2020-07-22'),
(18, 1, '/TP-La-Comanda/public/', '2020-07-22'),
(19, 2, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-22'),
(20, 5, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-22'),
(21, 5, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-22'),
(22, 2, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-22'),
(23, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(24, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(25, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(26, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(27, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(28, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(29, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(30, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(31, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(32, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-22'),
(33, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-22'),
(34, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-22'),
(35, 2, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-22'),
(36, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(37, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(38, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(39, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(40, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(41, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(42, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(43, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(44, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(45, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(46, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(47, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(48, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(49, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(50, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(51, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(52, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(53, 2, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(54, 2, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(55, 2, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(56, 2, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(57, 2, '/TP-La-Comanda/public/mesa/cargar/', '2020-07-23'),
(58, 2, '/TP-La-Comanda/public/mesa/cargar/', '2020-07-23'),
(59, 2, '/TP-La-Comanda/public/mesa/cargar/', '2020-07-23'),
(60, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(61, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(62, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(63, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(64, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(65, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(66, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(67, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(68, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(69, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(70, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(71, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(72, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(73, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(74, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(75, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(76, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(77, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(78, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(79, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(80, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(81, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(82, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(83, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(84, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(85, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(86, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(87, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-23'),
(88, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(89, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(90, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(91, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(92, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(93, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(94, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(95, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(96, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(97, 10, '/TP-La-Comanda/public/', '2020-07-23'),
(98, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(99, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(100, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(101, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(102, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(103, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(104, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(105, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(106, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(107, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-23'),
(108, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-23'),
(109, 10, '/TP-La-Comanda/public/mesa/cargar/', '2020-07-23'),
(110, 10, '/TP-La-Comanda/public/mesa/cargar/', '2020-07-23'),
(111, 10, '/TP-La-Comanda/public/', '2020-07-24'),
(112, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-24'),
(113, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-24'),
(114, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-24'),
(115, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-24'),
(116, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-24'),
(117, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-24'),
(118, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-24'),
(119, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-24'),
(120, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-24'),
(121, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-24'),
(122, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-24'),
(123, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(124, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(125, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(126, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(127, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(128, 10, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(129, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-24'),
(130, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(131, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(132, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(133, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(134, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(135, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(136, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(137, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(138, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(139, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(140, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(141, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(142, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(143, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(144, 1, '/TP-La-Comanda/public/', '2020-07-25'),
(145, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(146, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(147, 1, '/TP-La-Comanda/public/mesa/estado/comiendo/', '2020-07-25'),
(148, 1, '/TP-La-Comanda/public/mesa/estado/pagando/', '2020-07-25'),
(149, 1, '/TP-La-Comanda/public/mesa/estado/pagando/', '2020-07-25'),
(150, 1, '/TP-La-Comanda/public/mesa/estado/pagando/', '2020-07-25'),
(151, 1, '/TP-La-Comanda/public/mesa/estado/pagando/', '2020-07-25'),
(152, 1, '/TP-La-Comanda/public/mesa/estado/esperando/', '2020-07-25'),
(153, 1, '/TP-La-Comanda/public/mesa/estado/cerrada/', '2020-07-25'),
(154, 1, '/TP-La-Comanda/public/mesa/estado/cerrada/', '2020-07-25'),
(155, 10, '/TP-La-Comanda/public/', '2020-07-25'),
(156, 10, '/TP-La-Comanda/public/', '2020-07-25'),
(157, 10, '/TP-La-Comanda/public/', '2020-07-25'),
(158, 10, '/TP-La-Comanda/public/', '2020-07-30'),
(159, 1, '/TP-La-Comanda/public/pedido/pendientes/', '2020-07-30'),
(160, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-30'),
(161, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-30'),
(162, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-30'),
(163, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-30'),
(164, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-30'),
(165, 10, '/TP-La-Comanda/public/pedido/tomar/', '2020-07-30'),
(166, 1, '/TP-La-Comanda/public/pedido/estados/', '2020-07-30'),
(167, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(168, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(169, 6, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(170, 6, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(171, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(172, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(173, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(174, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(175, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(176, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(177, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(178, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(179, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(180, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(181, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(182, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(183, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(184, 10, '/TP-La-Comanda/public/pedido/servir/', '2020-07-30'),
(185, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-30'),
(186, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-30'),
(187, 10, '/TP-La-Comanda/public/pedido/cancelar/', '2020-07-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_mesa` int(10) NOT NULL,
  `id_producto` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `nombre_cliente` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `id_estado_pedido` int(10) NOT NULL,
  `hora_inicial` time NOT NULL,
  `fecha` date NOT NULL,
  `nombre_foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `hora_entrega_estimada` time NOT NULL,
  `tiempo_estimado` time NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `id_estado_mesa` int(10) DEFAULT NULL,
  `hora_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_mesa`, `id_producto`, `cantidad`, `nombre_cliente`, `codigo`, `id_estado_pedido`, `hora_inicial`, `fecha`, `nombre_foto`, `hora_entrega_estimada`, `tiempo_estimado`, `id_empleado`, `id_estado_mesa`, `hora_entrega`) VALUES
(7, 1, 1, 3, 'Jorge', '06lak', 3, '22:52:00', '2020-07-23', '', '19:46:00', '00:00:10', 10, 3, NULL),
(8, 2, 15, 1, 'Marcos', 'u36ql', 4, '22:58:00', '2020-07-23', '', '23:14:00', '00:00:15', 10, 1, NULL),
(12, 5, 10, 2, 'Jacinto', 'oyvzf', 3, '00:16:00', '2020-07-24', 'oyvzf_5.jpg', '19:46:00', '00:20:00', 10, NULL, NULL),
(13, 5, 10, 2, 'Jacinto', 'uwtmc', 1, '02:35:00', '2020-07-25', 'uwtmc_5.jpg', '00:00:00', '00:00:00', 0, NULL, NULL),
(14, 4, 13, 25, 'Pedro', 'uha54', 1, '02:35:00', '2020-07-25', 'uha54_4.jpg', '00:00:00', '00:00:00', 0, NULL, NULL),
(15, 4, 13, 25, 'Pedro', '9vqpb', 1, '02:36:00', '2020-07-25', '9vqpb_4.jpg', '00:00:00', '00:00:00', 0, NULL, NULL),
(16, 4, 13, 25, 'Pedro', 'o2ew7', 3, '19:12:00', '2020-07-30', '', '19:47:00', '00:20:00', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `id_sector` int(10) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `id_sector`, `nombre`, `precio`) VALUES
(1, 5, 'Miller', 150),
(2, 5, 'Corona', 150),
(3, 5, 'Quilmes', 150),
(4, 5, 'Stella', 150),
(5, 5, 'Imperial', 150),
(6, 5, 'Brahma', 150),
(7, 4, 'Daiquiri', 250),
(8, 4, 'Fernet', 250),
(9, 4, 'Campari', 250),
(10, 4, 'Satanas', 250),
(11, 4, 'Termidor Tinto', 250),
(12, 4, 'Gancia', 250),
(13, 4, 'Espumante', 250),
(14, 3, 'Empanadas', 320),
(15, 3, 'Ravioles', 320),
(16, 4, 'Asado', 250),
(17, 4, 'Ensalada', 250),
(18, 4, 'Carre de Cerdo', 250),
(19, 4, 'Lasagna', 250),
(20, 6, 'Alfajor', 90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id`, `nombre`) VALUES
(1, 'Local'),
(2, 'Salon'),
(3, 'Cocina'),
(4, 'Barra de Tragos y Vinos'),
(5, 'Barra de Cervezas'),
(6, 'Candy Bar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empleados`
--

CREATE TABLE `tipo_empleados` (
  `id` int(11) NOT NULL,
  `tipo_empleado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_empleados`
--

INSERT INTO `tipo_empleados` (`id`, `tipo_empleado`) VALUES
(1, 'socio'),
(2, 'mozo'),
(3, 'cocinero'),
(4, 'bartender'),
(5, 'cervecero'),
(6, 'pastelero');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_mesas`
--
ALTER TABLE `estado_mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `loggers`
--
ALTER TABLE `loggers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_empleados`
--
ALTER TABLE `tipo_empleados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_mesas`
--
ALTER TABLE `estado_mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `loggers`
--
ALTER TABLE `loggers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_empleados`
--
ALTER TABLE `tipo_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
