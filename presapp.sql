-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-06-2023 a las 08:19:33
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `presapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carnet_indentidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` int(11) NOT NULL,
  `documentos_respaldo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `tipo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre_completo`, `carnet_indentidad`, `telefono`, `documentos_respaldo`, `foto_principal`, `tipo_id`) VALUES
(1, '2023-06-17 16:48:17', '2023-06-17 16:48:17', NULL, 'Luis Flores', '5619016 BN', 72823861, '[]', 'users/default.png', 1),
(2, '2023-06-18 19:19:50', '2023-06-18 19:19:50', NULL, 'Juan Peres', '56190155 BN', 71130523, '[]', 'users/default.png ', 1),
(3, '2023-06-18 19:22:13', '2023-06-18 19:22:13', NULL, 'Manuel Lopez', '56+5445', 874151481, '[]', 'users/default.png ', 1),
(4, '2023-06-18 19:29:13', '2023-06-18 19:29:13', NULL, 'hernan cortez', '4984684', 754684, '[]', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_tipos`
--

CREATE TABLE `cliente_tipos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente_tipos`
--

INSERT INTO `cliente_tipos` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre`) VALUES
(1, '2023-06-17 16:46:51', '2023-06-17 16:46:51', NULL, 'Particular'),
(2, '2023-06-17 16:47:04', '2023-06-17 16:47:04', NULL, 'Magisterio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data_rows`
--

CREATE TABLE `data_rows` (
  `id` int(10) UNSIGNED NOT NULL,
  `data_type_id` int(10) UNSIGNED NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `browse` tinyint(1) NOT NULL DEFAULT 1,
  `read` tinyint(1) NOT NULL DEFAULT 1,
  `edit` tinyint(1) NOT NULL DEFAULT 1,
  `add` tinyint(1) NOT NULL DEFAULT 1,
  `delete` tinyint(1) NOT NULL DEFAULT 1,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `data_rows`
--

INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES
(1, 1, 'id', 'number', 'ID', 1, 1, 1, 0, 0, 0, '{}', 1),
(2, 1, 'name', 'text', 'Nombre', 1, 1, 1, 1, 1, 1, '{}', 2),
(3, 1, 'email', 'text', 'Correo Electrónico', 1, 1, 1, 1, 1, 1, '{}', 3),
(4, 1, 'password', 'password', 'Constraseña', 1, 0, 0, 1, 1, 0, '{}', 4),
(5, 1, 'remember_token', 'text', 'Token de Recuerdo', 0, 0, 0, 0, 0, 0, '{}', 5),
(6, 1, 'created_at', 'timestamp', 'Creado', 0, 0, 0, 0, 0, 0, '{}', 6),
(7, 1, 'updated_at', 'timestamp', 'Actualizado', 0, 1, 1, 0, 0, 0, '{}', 7),
(8, 1, 'avatar', 'image', 'Avatar', 0, 1, 1, 1, 1, 1, '{}', 8),
(9, 1, 'user_belongsto_role_relationship', 'relationship', 'Rol', 0, 1, 1, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":\"0\",\"taggable\":\"0\"}', 10),
(10, 1, 'user_belongstomany_role_relationship', 'relationship', 'Roles', 0, 0, 0, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}', 11),
(11, 1, 'settings', 'hidden', 'Settings', 0, 0, 0, 0, 0, 0, '{}', 12),
(12, 2, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(13, 2, 'name', 'text', 'Nombre', 1, 1, 1, 1, 1, 1, NULL, 2),
(14, 2, 'created_at', 'timestamp', 'Creado', 0, 0, 0, 0, 0, 0, NULL, 3),
(15, 2, 'updated_at', 'timestamp', 'Actualizado', 0, 0, 0, 0, 0, 0, NULL, 4),
(16, 3, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(17, 3, 'name', 'text', 'Nombre', 1, 1, 1, 1, 1, 1, NULL, 2),
(18, 3, 'created_at', 'timestamp', 'Creado', 0, 0, 0, 0, 0, 0, NULL, 3),
(19, 3, 'updated_at', 'timestamp', 'Actualizado', 0, 0, 0, 0, 0, 0, NULL, 4),
(20, 3, 'display_name', 'text', 'Nombre a Mostrar', 1, 1, 1, 1, 1, 1, NULL, 5),
(21, 1, 'role_id', 'text', 'Rol', 0, 1, 1, 1, 1, 1, '{}', 9),
(22, 4, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(23, 4, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, '{}', 2),
(24, 4, 'updated_at', 'timestamp', 'Updated', 0, 1, 1, 0, 0, 0, '{}', 3),
(25, 4, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(26, 4, 'nombre_completo', 'text', 'Nombre Completo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 5),
(27, 4, 'carnet_indentidad', 'text', 'Carnet Indentidad', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 7),
(28, 4, 'telefono', 'number', 'Telefono', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 6),
(30, 4, 'foto_principal', 'image', 'Foto Principal', 0, 0, 1, 1, 1, 1, '{\"default\":\"users\\/default.png\"}', 9),
(31, 4, 'documentos_respaldo', 'file', 'Documentos Respaldo', 0, 0, 1, 1, 1, 1, '{}', 10),
(32, 5, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(33, 5, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, '{}', 2),
(34, 5, 'updated_at', 'timestamp', 'Updated At', 0, 1, 1, 0, 0, 0, '{}', 3),
(35, 5, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(36, 5, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(37, 4, 'cliente_belongsto_cliente_tipo_relationship', 'relationship', 'Tipo Cliente', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\ClienteTipo\",\"table\":\"cliente_tipos\",\"type\":\"belongsTo\",\"column\":\"tipo_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 8),
(38, 4, 'tipo_id', 'text', 'Tipo Id', 0, 1, 1, 1, 1, 1, '{}', 11),
(39, 1, 'email_verified_at', 'timestamp', 'Email Verified At', 0, 0, 0, 1, 1, 0, '{}', 6),
(40, 1, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 12),
(41, 6, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(42, 6, 'created_at', 'timestamp', 'Creado', 0, 0, 1, 0, 0, 0, '{}', 2),
(43, 6, 'updated_at', 'timestamp', 'Actualizado', 0, 0, 1, 0, 0, 0, '{}', 3),
(44, 6, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(45, 6, 'cliente_id', 'text', 'Cliente Id', 0, 1, 1, 1, 1, 1, '{}', 13),
(46, 6, 'tipo_id', 'text', 'Tipo Id', 0, 1, 1, 1, 1, 1, '{}', 14),
(47, 6, 'user_id', 'text', 'User Id', 0, 0, 0, 0, 0, 0, '{}', 15),
(48, 6, 'cuota', 'number', 'Cuota Mensual', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0\"}', 10),
(49, 6, 'interes', 'number', 'Interes', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0.03\"}', 12),
(50, 6, 'plazo', 'number', 'Plazo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0\"}', 7),
(51, 6, 'monto', 'number', 'Monto', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0\"}', 8),
(52, 6, 'observacion', 'text_area', 'Detalles', 1, 1, 1, 1, 1, 1, '{\"default\":\"pr\\u00e9stamo sin observaciones ni detalles\"}', 17),
(53, 7, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(54, 7, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(55, 7, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(56, 7, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(57, 7, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 5),
(58, 6, 'prestamo_belongsto_cliente_relationship', 'relationship', 'Cliente', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Cliente\",\"table\":\"clientes\",\"type\":\"belongsTo\",\"column\":\"cliente_id\",\"key\":\"id\",\"label\":\"nombre_completo\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 11),
(59, 6, 'prestamo_belongsto_prestamo_tipo_relationship', 'relationship', 'Tipo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\PrestamoTipo\",\"table\":\"prestamo_tipos\",\"type\":\"belongsTo\",\"column\":\"tipo_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 5),
(61, 6, 'mes_inicio', 'date', 'Fecha Inicio', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 6),
(62, 8, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(63, 8, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, '{}', 2),
(64, 8, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(65, 8, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(66, 8, 'mes', 'text', 'Mes', 0, 1, 1, 1, 1, 1, '{}', 5),
(67, 8, 'nro', 'text', 'Nro', 0, 1, 1, 1, 1, 1, '{}', 6),
(68, 8, 'monto', 'text', 'Monto', 0, 1, 1, 1, 1, 1, '{}', 7),
(69, 8, 'interes', 'text', 'Interes', 0, 1, 1, 1, 1, 1, '{}', 8),
(70, 8, 'capital', 'text', 'Capital', 0, 1, 1, 1, 1, 1, '{}', 9),
(72, 8, 'deuda', 'text', 'Deuda', 0, 1, 1, 1, 1, 1, '{}', 11),
(73, 8, 'pagado', 'checkbox', 'Pagado', 0, 1, 1, 1, 1, 1, '{}', 12),
(74, 8, 'observacion', 'text_area', 'Observacion', 0, 0, 1, 1, 1, 1, '{}', 13),
(75, 8, 'cuota', 'text', 'Cuota', 0, 1, 1, 1, 1, 1, '{}', 10),
(76, 8, 'prestamo_id', 'text', 'Prestamo Id', 0, 1, 1, 1, 1, 1, '{}', 14),
(77, 9, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(78, 9, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(79, 9, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(80, 9, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(81, 9, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(82, 6, 'estado_id', 'text', 'Estado Id', 0, 0, 0, 0, 0, 0, '{\"default\":1}', 16),
(84, 8, 'fecha', 'date', 'Fecha', 0, 1, 1, 1, 1, 1, '{}', 15),
(85, 10, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(86, 10, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(87, 10, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(88, 10, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(89, 10, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(90, 8, 'pasarela_id', 'text', 'Pasarela Id', 0, 1, 1, 1, 1, 1, '{}', 16),
(91, 7, 'monto_interes', 'number', 'Monto Interes', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0.03\"}', 6),
(92, 7, 'monto_minimo', 'number', 'Monto Minimo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"1000\"}', 7),
(93, 7, 'monto_maximo', 'number', 'Monto Maximo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"100000\"}', 8),
(94, 12, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(95, 12, 'created_at', 'timestamp', 'Created At', 0, 0, 1, 0, 0, 0, '{}', 2),
(96, 12, 'updated_at', 'timestamp', 'Updated At', 0, 0, 1, 0, 0, 0, '{}', 3),
(97, 12, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(98, 12, 'user_id', 'number', 'Editor', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 11),
(99, 12, 'tipo_id', 'text', 'Tipo Id', 0, 1, 1, 1, 1, 1, '{}', 5),
(100, 12, 'monto', 'number', 'Monto', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":0}', 8),
(101, 12, 'detalles', 'text_area', 'Detalles', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 10),
(102, 12, 'documentos', 'file', 'Documentos', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 9),
(103, 11, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(104, 11, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(105, 11, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(106, 11, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(107, 11, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(108, 12, 'gasto_belongsto_gasto_tipo_relationship', 'relationship', 'Tipo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\GastoTipo\",\"table\":\"gasto_tipos\",\"type\":\"belongsTo\",\"column\":\"tipo_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 6),
(109, 13, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(110, 13, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(111, 13, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(112, 13, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(113, 13, 'capital_cantidad', 'number', 'Capital Cantidad', 1, 1, 1, 1, 1, 1, '{\"default\":0}', 6),
(114, 13, 'capital_monto', 'number', 'Capital Monto', 1, 1, 1, 1, 1, 1, '{\"default\":0}', 7),
(115, 13, 'pago_cantidad', 'number', 'Pago Cantidad', 1, 1, 1, 1, 1, 1, '{\"default\":0}', 8),
(116, 13, 'pago_monto', 'number', 'Pago Monto', 1, 1, 1, 1, 1, 1, '{\"default\":0}', 9),
(117, 13, 'gasto_cantidad', 'number', 'Gasto Cantidad', 1, 1, 1, 1, 1, 1, '{\"default\":0}', 10),
(118, 13, 'gasto_monto', 'number', 'Gasto Monto', 1, 1, 1, 1, 1, 1, '{\"default\":0}', 11),
(119, 13, 'mes', 'date', 'Mes', 1, 1, 1, 1, 1, 1, '{}', 5),
(120, 13, 'detalles', 'text_area', 'Detalles', 1, 1, 1, 1, 1, 1, '{\"default\":\"sin detalles para el mes\"}', 12),
(121, 6, 'fecha_prestamos', 'date', 'Fecha Prestamo', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 9),
(122, 6, 'documentos', 'file', 'Documentos', 0, 0, 1, 1, 1, 1, '{}', 18),
(123, 7, 'detalle', 'text_area', 'Detalle', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 9),
(124, 9, 'detalle', 'text_area', 'Detalle', 0, 1, 1, 1, 1, 1, '{}', 6),
(125, 7, 'plazo_minimo', 'number', 'Plazo Minimo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 10),
(126, 7, 'plazo_maximo', 'number', 'Plazo Maximo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 11),
(127, 12, 'fecha', 'date', 'Fecha', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 7),
(128, 13, 'user_id', 'number', 'Editor', 0, 0, 1, 1, 1, 1, '{}', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data_types`
--

CREATE TABLE `data_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT 0,
  `server_side` tinyint(4) NOT NULL DEFAULT 0,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `data_types`
--

INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES
(1, 'users', 'users', 'Usuario', 'Usuarios', 'voyager-helm', 'TCG\\Voyager\\Models\\User', 'TCG\\Voyager\\Policies\\UserPolicy', 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController', NULL, 1, 1, '{\"order_column\":\"updated_at\",\"order_display_column\":\"name\",\"order_direction\":\"desc\",\"default_search_key\":\"name\",\"scope\":null}', '2023-06-17 16:25:09', '2023-06-17 17:01:27'),
(2, 'menus', 'menus', 'Menú', 'Menús', 'voyager-list', 'TCG\\Voyager\\Models\\Menu', NULL, '', '', 1, 0, NULL, '2023-06-17 16:25:09', '2023-06-17 16:25:09'),
(3, 'roles', 'roles', 'Rol', 'Roles', 'voyager-lock', 'TCG\\Voyager\\Models\\Role', NULL, 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController', '', 1, 0, NULL, '2023-06-17 16:25:09', '2023-06-17 16:25:09'),
(4, 'clientes', 'clientes', 'Cliente', 'Clientes', 'voyager-helm', 'App\\Cliente', NULL, NULL, NULL, 1, 1, '{\"order_column\":\"updated_at\",\"order_display_column\":\"nombre_completo\",\"order_direction\":\"desc\",\"default_search_key\":\"nombre_completo\",\"scope\":null}', '2023-06-17 16:40:22', '2023-06-21 22:17:40'),
(5, 'cliente_tipos', 'cliente-tipos', 'Cliente Tipo', 'Cliente Tipos', 'voyager-helm', 'App\\ClienteTipo', NULL, NULL, NULL, 1, 0, '{\"order_column\":\"updated_at\",\"order_display_column\":\"nombre\",\"order_direction\":\"desc\",\"default_search_key\":\"nombre\",\"scope\":null}', '2023-06-17 16:43:30', '2023-06-17 17:24:21'),
(6, 'prestamos', 'prestamos', 'Prestamo', 'Prestamos', 'voyager-helm', 'App\\Prestamo', NULL, NULL, NULL, 1, 1, '{\"order_column\":\"updated_at\",\"order_display_column\":\"observacion\",\"order_direction\":\"desc\",\"default_search_key\":\"observacion\",\"scope\":null}', '2023-06-17 17:21:32', '2023-06-28 06:11:17'),
(7, 'prestamo_tipos', 'prestamo-tipos', 'Prestamo Tipo', 'Prestamo Tipos', 'voyager-helm', 'App\\PrestamoTipo', NULL, NULL, NULL, 1, 0, '{\"order_column\":\"updated_at\",\"order_display_column\":\"nombre\",\"order_direction\":\"desc\",\"default_search_key\":\"nombre\",\"scope\":null}', '2023-06-17 17:22:17', '2023-06-27 16:07:33'),
(8, 'prestamo_planes', 'prestamo-planes', 'Prestamo Plane', 'Prestamo Planes', 'voyager-helm', 'App\\PrestamoPlane', NULL, NULL, NULL, 1, 1, '{\"order_column\":\"nro\",\"order_display_column\":\"nro\",\"order_direction\":\"asc\",\"default_search_key\":\"mes\",\"scope\":null}', '2023-06-18 17:22:44', '2023-06-19 18:16:20'),
(9, 'prestamo_estados', 'prestamo-estados', 'Prestamo Estado', 'Prestamo Estados', 'voyager-helm', 'App\\PrestamoEstado', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-06-18 20:33:14', '2023-06-27 15:36:52'),
(10, 'pasarelas', 'pasarelas', 'Pasarela', 'Pasarelas', 'voyager-helm', 'App\\Pasarela', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(11, 'gasto_tipos', 'gasto-tipos', 'Gasto Tipo', 'Gasto Tipos', 'voyager-helm', 'App\\GastoTipo', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-06-22 00:06:28', '2023-06-22 00:17:31'),
(12, 'gastos', 'gastos', 'Gasto', 'Gastos', 'voyager-helm', 'App\\Gasto', NULL, NULL, NULL, 1, 1, '{\"order_column\":\"created_at\",\"order_display_column\":\"detalles\",\"order_direction\":\"desc\",\"default_search_key\":\"detalles\",\"scope\":null}', '2023-06-22 00:07:32', '2023-06-28 04:36:24'),
(13, 'reportes', 'reportes', 'Reporte', 'Reportes', 'voyager-helm', 'App\\Reporte', NULL, NULL, NULL, 1, 1, '{\"order_column\":\"created_at\",\"order_display_column\":\"mes\",\"order_direction\":\"desc\",\"default_search_key\":\"mes\",\"scope\":null}', '2023-06-22 18:08:28', '2023-06-28 05:31:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `monto` double NOT NULL DEFAULT 0,
  `detalles` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentos` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `created_at`, `updated_at`, `deleted_at`, `user_id`, `tipo_id`, `monto`, `detalles`, `documentos`, `fecha`) VALUES
(1, '2023-06-28 06:16:43', '2023-06-28 06:16:43', NULL, 2, 1, 600, 'pago de alquiler', '[]', '2023-01-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto_tipos`
--

CREATE TABLE `gasto_tipos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gasto_tipos`
--

INSERT INTO `gasto_tipos` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre`) VALUES
(1, '2023-06-22 13:41:37', '2023-06-22 13:41:37', NULL, 'Servicios Basicos'),
(2, '2023-06-22 13:41:54', '2023-06-22 13:41:54', NULL, 'Transporte'),
(3, '2023-06-22 13:42:03', '2023-06-22 13:42:03', NULL, 'Salarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2023-06-17 16:25:13', '2023-06-17 16:25:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES
(2, 1, 'Multimedia', '', '_self', 'voyager-images', NULL, 5, 2, '2023-06-17 16:25:16', '2023-06-18 17:23:03', 'voyager.media.index', NULL),
(3, 1, 'Usuarios', '', '_self', 'voyager-helm', '#000000', NULL, 4, '2023-06-17 16:25:16', '2023-06-28 05:58:16', 'voyager.users.index', 'null'),
(4, 1, 'Roles', '', '_self', 'voyager-lock', NULL, 5, 3, '2023-06-17 16:25:16', '2023-06-18 17:23:03', 'voyager.roles.index', NULL),
(5, 1, 'Herramientas', '', '_self', 'voyager-tools', NULL, NULL, 6, '2023-06-17 16:25:17', '2023-06-28 05:58:16', NULL, NULL),
(6, 1, 'Diseñador de Menús', '', '_self', 'voyager-list', NULL, 5, 4, '2023-06-17 16:25:18', '2023-06-18 17:23:03', 'voyager.menus.index', NULL),
(7, 1, 'Base de Datos', '', '_self', 'voyager-data', NULL, 5, 5, '2023-06-17 16:25:18', '2023-06-18 17:23:03', 'voyager.database.index', NULL),
(8, 1, 'Compás', '', '_self', 'voyager-compass', NULL, 5, 6, '2023-06-17 16:25:18', '2023-06-18 17:23:03', 'voyager.compass.index', NULL),
(9, 1, 'BREAD', '', '_self', 'voyager-bread', NULL, 5, 7, '2023-06-17 16:25:19', '2023-06-18 17:23:03', 'voyager.bread.index', NULL),
(10, 1, 'Parámetros', '', '_self', 'voyager-settings', NULL, 5, 1, '2023-06-17 16:25:19', '2023-06-18 17:23:02', 'voyager.settings.index', NULL),
(11, 1, 'Clientes', '', '_self', 'voyager-helm', '#000000', NULL, 2, '2023-06-17 16:40:23', '2023-06-17 17:22:30', 'voyager.clientes.index', 'null'),
(12, 1, 'Cliente Tipos', '', '_self', 'voyager-helm', '#000000', 5, 8, '2023-06-17 16:43:30', '2023-06-18 20:33:42', 'voyager.cliente-tipos.index', 'null'),
(13, 1, 'Prestamos', '', '_self', 'voyager-helm', NULL, NULL, 1, '2023-06-17 17:21:33', '2023-06-17 17:22:30', 'voyager.prestamos.index', NULL),
(14, 1, 'Prestamo Tipos', '', '_self', 'voyager-helm', NULL, 5, 11, '2023-06-17 17:22:18', '2023-06-18 20:33:40', 'voyager.prestamo-tipos.index', NULL),
(16, 1, 'Prestamo Planes', '', '_self', 'voyager-helm', NULL, 5, 9, '2023-06-18 17:22:45', '2023-06-18 20:33:42', 'voyager.prestamo-planes.index', NULL),
(17, 1, 'Prestamo Estados', '', '_self', 'voyager-helm', NULL, 5, 10, '2023-06-18 20:33:15', '2023-06-18 20:33:42', 'voyager.prestamo-estados.index', NULL),
(18, 1, 'Pasarelas', '', '_self', 'voyager-helm', NULL, 5, 13, '2023-06-19 16:02:45', '2023-06-28 05:58:16', 'voyager.pasarelas.index', NULL),
(19, 1, 'Gasto Tipos', '', '_self', 'voyager-helm', '#000000', 5, 12, '2023-06-22 00:06:28', '2023-06-28 05:58:16', 'voyager.gasto-tipos.index', 'null'),
(20, 1, 'Gastos', '', '_self', 'voyager-helm', '#000000', NULL, 3, '2023-06-22 00:07:32', '2023-06-22 00:08:37', 'voyager.gastos.index', 'null'),
(21, 1, 'Reportes', '', '_self', 'voyager-helm', '#000000', NULL, 5, '2023-06-22 18:08:28', '2023-06-28 05:58:16', 'voyager.reportes.index', 'null');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_01_01_000000_add_voyager_user_fields', 1),
(4, '2016_01_01_000000_create_data_types_table', 1),
(5, '2016_05_19_173453_create_menu_table', 1),
(6, '2016_10_21_190000_create_roles_table', 1),
(7, '2016_10_21_190000_create_settings_table', 1),
(8, '2016_11_30_135954_create_permission_table', 1),
(9, '2016_11_30_141208_create_permission_role_table', 1),
(10, '2016_12_26_201236_data_types__add__server_side', 1),
(11, '2017_01_13_000000_add_route_to_menu_items_table', 1),
(12, '2017_01_14_005015_create_translations_table', 1),
(13, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 1),
(14, '2017_03_06_000000_add_controller_to_data_types_table', 1),
(15, '2017_04_21_000000_add_order_to_data_rows_table', 1),
(16, '2017_07_05_210000_add_policyname_to_data_types_table', 1),
(17, '2017_08_05_000000_add_group_to_settings_table', 1),
(18, '2017_11_26_013050_add_user_role_relationship', 1),
(19, '2017_11_26_015000_create_user_roles_table', 1),
(20, '2018_03_11_000000_add_user_settings', 1),
(21, '2018_03_14_000000_add_details_to_data_types_table', 1),
(22, '2018_03_16_000000_make_settings_value_nullable', 1),
(23, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasarelas`
--

CREATE TABLE `pasarelas` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pasarelas`
--

INSERT INTO `pasarelas` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre`) VALUES
(1, '2023-06-19 16:10:09', '2023-06-19 16:10:38', NULL, 'Debito automatico'),
(2, '2023-06-19 16:10:32', '2023-06-19 16:10:32', NULL, 'Efectivo en oficinas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES
(1, 'browse_admin', NULL, '2023-06-17 16:25:20', '2023-06-17 16:25:20'),
(2, 'browse_bread', NULL, '2023-06-17 16:25:20', '2023-06-17 16:25:20'),
(3, 'browse_database', NULL, '2023-06-17 16:25:20', '2023-06-17 16:25:20'),
(4, 'browse_media', NULL, '2023-06-17 16:25:21', '2023-06-17 16:25:21'),
(5, 'browse_compass', NULL, '2023-06-17 16:25:21', '2023-06-17 16:25:21'),
(6, 'browse_menus', 'menus', '2023-06-17 16:25:21', '2023-06-17 16:25:21'),
(7, 'read_menus', 'menus', '2023-06-17 16:25:21', '2023-06-17 16:25:21'),
(8, 'edit_menus', 'menus', '2023-06-17 16:25:21', '2023-06-17 16:25:21'),
(9, 'add_menus', 'menus', '2023-06-17 16:25:21', '2023-06-17 16:25:21'),
(10, 'delete_menus', 'menus', '2023-06-17 16:25:22', '2023-06-17 16:25:22'),
(11, 'browse_roles', 'roles', '2023-06-17 16:25:22', '2023-06-17 16:25:22'),
(12, 'read_roles', 'roles', '2023-06-17 16:25:22', '2023-06-17 16:25:22'),
(13, 'edit_roles', 'roles', '2023-06-17 16:25:22', '2023-06-17 16:25:22'),
(14, 'add_roles', 'roles', '2023-06-17 16:25:22', '2023-06-17 16:25:22'),
(15, 'delete_roles', 'roles', '2023-06-17 16:25:23', '2023-06-17 16:25:23'),
(16, 'browse_users', 'users', '2023-06-17 16:25:23', '2023-06-17 16:25:23'),
(17, 'read_users', 'users', '2023-06-17 16:25:24', '2023-06-17 16:25:24'),
(18, 'edit_users', 'users', '2023-06-17 16:25:25', '2023-06-17 16:25:25'),
(19, 'add_users', 'users', '2023-06-17 16:25:25', '2023-06-17 16:25:25'),
(20, 'delete_users', 'users', '2023-06-17 16:25:26', '2023-06-17 16:25:26'),
(21, 'browse_settings', 'settings', '2023-06-17 16:25:27', '2023-06-17 16:25:27'),
(22, 'read_settings', 'settings', '2023-06-17 16:25:27', '2023-06-17 16:25:27'),
(23, 'edit_settings', 'settings', '2023-06-17 16:25:27', '2023-06-17 16:25:27'),
(24, 'add_settings', 'settings', '2023-06-17 16:25:28', '2023-06-17 16:25:28'),
(25, 'delete_settings', 'settings', '2023-06-17 16:25:29', '2023-06-17 16:25:29'),
(26, 'browse_clientes', 'clientes', '2023-06-17 16:40:23', '2023-06-17 16:40:23'),
(27, 'read_clientes', 'clientes', '2023-06-17 16:40:23', '2023-06-17 16:40:23'),
(28, 'edit_clientes', 'clientes', '2023-06-17 16:40:23', '2023-06-17 16:40:23'),
(29, 'add_clientes', 'clientes', '2023-06-17 16:40:23', '2023-06-17 16:40:23'),
(30, 'delete_clientes', 'clientes', '2023-06-17 16:40:23', '2023-06-17 16:40:23'),
(31, 'browse_cliente_tipos', 'cliente_tipos', '2023-06-17 16:43:30', '2023-06-17 16:43:30'),
(32, 'read_cliente_tipos', 'cliente_tipos', '2023-06-17 16:43:30', '2023-06-17 16:43:30'),
(33, 'edit_cliente_tipos', 'cliente_tipos', '2023-06-17 16:43:30', '2023-06-17 16:43:30'),
(34, 'add_cliente_tipos', 'cliente_tipos', '2023-06-17 16:43:30', '2023-06-17 16:43:30'),
(35, 'delete_cliente_tipos', 'cliente_tipos', '2023-06-17 16:43:30', '2023-06-17 16:43:30'),
(36, 'browse_prestamos', 'prestamos', '2023-06-17 17:21:33', '2023-06-17 17:21:33'),
(37, 'read_prestamos', 'prestamos', '2023-06-17 17:21:33', '2023-06-17 17:21:33'),
(38, 'edit_prestamos', 'prestamos', '2023-06-17 17:21:33', '2023-06-17 17:21:33'),
(39, 'add_prestamos', 'prestamos', '2023-06-17 17:21:33', '2023-06-17 17:21:33'),
(40, 'delete_prestamos', 'prestamos', '2023-06-17 17:21:33', '2023-06-17 17:21:33'),
(41, 'browse_prestamo_tipos', 'prestamo_tipos', '2023-06-17 17:22:17', '2023-06-17 17:22:17'),
(42, 'read_prestamo_tipos', 'prestamo_tipos', '2023-06-17 17:22:18', '2023-06-17 17:22:18'),
(43, 'edit_prestamo_tipos', 'prestamo_tipos', '2023-06-17 17:22:18', '2023-06-17 17:22:18'),
(44, 'add_prestamo_tipos', 'prestamo_tipos', '2023-06-17 17:22:18', '2023-06-17 17:22:18'),
(45, 'delete_prestamo_tipos', 'prestamo_tipos', '2023-06-17 17:22:18', '2023-06-17 17:22:18'),
(46, 'browse_prestamo_planes', 'prestamo_planes', '2023-06-18 17:22:45', '2023-06-18 17:22:45'),
(47, 'read_prestamo_planes', 'prestamo_planes', '2023-06-18 17:22:45', '2023-06-18 17:22:45'),
(48, 'edit_prestamo_planes', 'prestamo_planes', '2023-06-18 17:22:45', '2023-06-18 17:22:45'),
(49, 'add_prestamo_planes', 'prestamo_planes', '2023-06-18 17:22:45', '2023-06-18 17:22:45'),
(50, 'delete_prestamo_planes', 'prestamo_planes', '2023-06-18 17:22:45', '2023-06-18 17:22:45'),
(51, 'browse_prestamo_estados', 'prestamo_estados', '2023-06-18 20:33:15', '2023-06-18 20:33:15'),
(52, 'read_prestamo_estados', 'prestamo_estados', '2023-06-18 20:33:15', '2023-06-18 20:33:15'),
(53, 'edit_prestamo_estados', 'prestamo_estados', '2023-06-18 20:33:15', '2023-06-18 20:33:15'),
(54, 'add_prestamo_estados', 'prestamo_estados', '2023-06-18 20:33:15', '2023-06-18 20:33:15'),
(55, 'delete_prestamo_estados', 'prestamo_estados', '2023-06-18 20:33:15', '2023-06-18 20:33:15'),
(56, 'browse_pasarelas', 'pasarelas', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(57, 'read_pasarelas', 'pasarelas', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(58, 'edit_pasarelas', 'pasarelas', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(59, 'add_pasarelas', 'pasarelas', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(60, 'delete_pasarelas', 'pasarelas', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(61, 'browse_gasto_tipos', 'gasto_tipos', '2023-06-22 00:06:28', '2023-06-22 00:06:28'),
(62, 'read_gasto_tipos', 'gasto_tipos', '2023-06-22 00:06:28', '2023-06-22 00:06:28'),
(63, 'edit_gasto_tipos', 'gasto_tipos', '2023-06-22 00:06:28', '2023-06-22 00:06:28'),
(64, 'add_gasto_tipos', 'gasto_tipos', '2023-06-22 00:06:28', '2023-06-22 00:06:28'),
(65, 'delete_gasto_tipos', 'gasto_tipos', '2023-06-22 00:06:28', '2023-06-22 00:06:28'),
(66, 'browse_gastos', 'gastos', '2023-06-22 00:07:32', '2023-06-22 00:07:32'),
(67, 'read_gastos', 'gastos', '2023-06-22 00:07:32', '2023-06-22 00:07:32'),
(68, 'edit_gastos', 'gastos', '2023-06-22 00:07:32', '2023-06-22 00:07:32'),
(69, 'add_gastos', 'gastos', '2023-06-22 00:07:32', '2023-06-22 00:07:32'),
(70, 'delete_gastos', 'gastos', '2023-06-22 00:07:32', '2023-06-22 00:07:32'),
(71, 'browse_reportes', 'reportes', '2023-06-22 18:08:28', '2023-06-22 18:08:28'),
(72, 'read_reportes', 'reportes', '2023-06-22 18:08:28', '2023-06-22 18:08:28'),
(73, 'edit_reportes', 'reportes', '2023-06-22 18:08:28', '2023-06-22 18:08:28'),
(74, 'add_reportes', 'reportes', '2023-06-22 18:08:28', '2023-06-22 18:08:28'),
(75, 'delete_reportes', 'reportes', '2023-06-22 18:08:28', '2023-06-22 18:08:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(26, 3),
(27, 1),
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1),
(31, 1),
(31, 3),
(32, 1),
(32, 3),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(36, 3),
(37, 1),
(37, 3),
(39, 1),
(39, 3),
(41, 1),
(41, 3),
(42, 3),
(43, 1),
(44, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(51, 3),
(52, 1),
(52, 3),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(56, 3),
(57, 1),
(57, 3),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(61, 3),
(62, 1),
(62, 3),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(66, 3),
(67, 1),
(68, 1),
(68, 3),
(69, 1),
(69, 3),
(70, 1),
(71, 1),
(71, 3),
(72, 1),
(72, 3),
(73, 1),
(74, 1),
(74, 3),
(75, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cuota` double NOT NULL,
  `interes` double NOT NULL,
  `plazo` int(11) NOT NULL,
  `monto` double NOT NULL,
  `observacion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `mes_inicio` date DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `fecha_prestamos` date DEFAULT NULL,
  `documentos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `created_at`, `updated_at`, `deleted_at`, `cliente_id`, `tipo_id`, `user_id`, `cuota`, `interes`, `plazo`, `monto`, `observacion`, `mes_inicio`, `estado_id`, `fecha_prestamos`, `documentos`) VALUES
(1, '2023-06-28 06:08:05', '2023-06-28 06:08:19', NULL, 3, 1, 2, 793.33, 0.03, 12, 7000, 'préstamo sin observaciones ni detalles', '2023-01-09', 2, '2023-01-03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_estados`
--

CREATE TABLE `prestamo_estados` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detalle` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamo_estados`
--

INSERT INTO `prestamo_estados` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre`, `detalle`) VALUES
(1, '2023-06-18 20:34:08', '2023-06-27 15:37:10', NULL, 'ACTIVO', 'son los préstamos que no tienen deuda y están incompletos'),
(2, '2023-06-18 20:34:39', '2023-06-27 15:37:31', NULL, 'EN MORA', 'son préstamos con deudas.'),
(3, '2023-06-18 20:34:58', '2023-06-27 15:37:40', NULL, 'REFINANCIADO', 'son préstamos editados y actualizados según las reglas  del agente de ventas'),
(4, '2023-06-18 20:35:03', '2023-06-27 15:37:47', NULL, 'COMPLETADO', 'son los préstamos que completan el plan de pago.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_planes`
--

CREATE TABLE `prestamo_planes` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `mes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `interes` double DEFAULT NULL,
  `capital` double DEFAULT NULL,
  `cuota` double DEFAULT NULL,
  `deuda` double DEFAULT NULL,
  `pagado` binary(1) DEFAULT NULL,
  `observacion` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prestamo_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `pasarela_id` int(11) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamo_planes`
--

INSERT INTO `prestamo_planes` (`id`, `created_at`, `updated_at`, `deleted_at`, `mes`, `nro`, `monto`, `interes`, `capital`, `cuota`, `deuda`, `pagado`, `observacion`, `prestamo_id`, `fecha`, `pasarela_id`, `fecha_pago`, `user_id`) VALUES
(1, '2023-06-28 06:08:06', '2023-06-28 06:15:53', NULL, 'enero-23', 1, 7000, 210, 583.33, 793.33, 6416.67, 0x31, 'Sin observación', 1, '2023-01-09', 1, '2023-01-28', 2),
(2, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'febrero-23', 2, 6416.67, 210, 583.33, 793.33, 5833.34, 0x30, NULL, 1, '2023-02-09', NULL, NULL, NULL),
(3, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'marzo-23', 3, 5833.34, 210, 583.33, 793.33, 5250.01, 0x30, NULL, 1, '2023-03-09', NULL, NULL, NULL),
(4, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'abril-23', 4, 5250.01, 210, 583.33, 793.33, 4666.68, 0x30, NULL, 1, '2023-04-09', NULL, NULL, NULL),
(5, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'mayo-23', 5, 4666.68, 210, 583.33, 793.33, 4083.35, 0x30, NULL, 1, '2023-05-09', NULL, NULL, NULL),
(6, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'junio-23', 6, 4083.35, 210, 583.33, 793.33, 3500.02, 0x30, NULL, 1, '2023-06-09', NULL, NULL, NULL),
(7, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'julio-23', 7, 3500.02, 210, 583.33, 793.33, 2916.69, 0x30, NULL, 1, '2023-07-09', NULL, NULL, NULL),
(8, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'agosto-23', 8, 2916.69, 210, 583.33, 793.33, 2333.36, 0x30, NULL, 1, '2023-08-09', NULL, NULL, NULL),
(9, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'septiembre-23', 9, 2333.36, 210, 583.33, 793.33, 1750.03, 0x30, NULL, 1, '2023-09-09', NULL, NULL, NULL),
(10, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'octubre-23', 10, 1750.03, 210, 583.33, 793.33, 1166.7, 0x30, NULL, 1, '2023-10-09', NULL, NULL, NULL),
(11, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'noviembre-23', 11, 1166.7, 210, 583.33, 793.33, 583.37, 0x30, NULL, 1, '2023-11-09', NULL, NULL, NULL),
(12, '2023-06-28 06:08:06', '2023-06-28 06:08:06', NULL, 'diciembre-23', 12, 583.37, 210, 583.33, 793.37, 0, 0x30, NULL, 1, '2023-12-09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_tipos`
--

CREATE TABLE `prestamo_tipos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monto_interes` double DEFAULT NULL,
  `monto_minimo` double DEFAULT NULL,
  `monto_maximo` double DEFAULT NULL,
  `detalle` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plazo_minimo` int(11) DEFAULT NULL,
  `plazo_maximo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamo_tipos`
--

INSERT INTO `prestamo_tipos` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre`, `monto_interes`, `monto_minimo`, `monto_maximo`, `detalle`, `plazo_minimo`, `plazo_maximo`) VALUES
(1, '2023-06-17 17:24:54', '2023-06-27 16:08:54', NULL, 'Prestamo  al 3%', 0.03, 3000, 30000, 'Es un tipo de préstamo donde la cuota, el capital y el interés son montos fijos.', 6, 24),
(2, '2023-06-17 17:25:06', '2023-06-27 16:09:04', NULL, 'Prestamo  al 5%', 0.05, 5000, 50000, 'Es un tipo de préstamo donde solo los pagos son fijos y el  interés y el capital son variables', 9, 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `capital_cantidad` int(11) NOT NULL,
  `capital_monto` double NOT NULL,
  `pago_cantidad` int(11) NOT NULL,
  `pago_monto` double NOT NULL,
  `gasto_cantidad` int(11) NOT NULL,
  `gasto_monto` double NOT NULL,
  `mes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador', '2023-06-17 16:25:19', '2023-06-17 16:25:19'),
(2, 'user', 'Usuario Normal', '2023-06-17 16:25:19', '2023-06-17 16:25:19'),
(3, 'ventas', 'Ventas o Agente', '2023-06-17 16:52:43', '2023-06-17 16:52:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES
(1, 'site.title', 'Título del sitio', 'PresApp', '', 'text', 1, 'Site'),
(2, 'site.description', 'Descripción del sitio', 'Aplicativo para gestionar negocios de préstamos y créditos financieros', '', 'text', 2, 'Site'),
(3, 'site.logo', 'Logo del sitio', '', '', 'image', 3, 'Site'),
(4, 'site.google_analytics_tracking_id', 'ID de rastreo de Google Analytics', NULL, '', 'text', 4, 'Site'),
(5, 'admin.bg_image', 'Imagen de fondo del administrador', '', '', 'image', 5, 'Admin'),
(6, 'admin.title', 'Título del administrador', 'PresApp', '', 'text', 1, 'Admin'),
(7, 'admin.description', 'Descripción del administrador', 'Aplicativo para gestionar negocios de prestamos', '', 'text', 2, 'Admin'),
(8, 'admin.loader', 'Imagen de carga del administrador', '', '', 'image', 3, 'Admin'),
(9, 'admin.icon_image', 'Ícono del administrador', '', '', 'image', 4, 'Admin'),
(10, 'admin.google_analytics_client_id', 'ID de Cliente para Google Analytics (usado para el tablero de administrador)', NULL, '', 'text', 1, 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `translations`
--

CREATE TABLE `translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) UNSIGNED NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `remember_token`, `settings`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'admin', 'admin@admin.com', 'users/default.png', NULL, '$2y$10$PYDJlmniUdo9unXtrO4Rae/K1deMD62pNS3NoHB7mnvfUYfgWR09W', 'aa7uSccOcRm0TVHS5QDGnOLWHgJ9jeeRiRE53n4mTEGppwY6hoE9FvlLShq8', NULL, '2023-06-17 16:27:11', '2023-06-17 16:27:12', NULL),
(2, 3, 'Agente 1', 'agente@agente.com', 'users/default.png', NULL, '$2y$10$o8Zbf7OO0Co0k5bYJzHFFuRum/RqGVoypM7emY/Ts/IyejOFaksH2', '8HZ6kkDecqv3YPqVIdlAq9ZhkTSO1YTSue9QR09KCFoKkfAJw46KOfUdMF2B', '{\"locale\":\"es\"}', '2023-06-17 16:53:31', '2023-06-17 16:53:31', NULL),
(3, 3, 'paul.muiba', 'paul.muiba@ventas.com', 'users/default.png', NULL, '$2y$10$FH5LiMEr2Poy0HziWoxDCe92tmHZt0w1yjcO.cCUsIZAN6TjrqRlK', NULL, '{\"locale\":\"es\"}', '2023-06-28 06:01:06', '2023-06-28 06:01:06', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente_tipos`
--
ALTER TABLE `cliente_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `data_rows`
--
ALTER TABLE `data_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_rows_data_type_id_foreign` (`data_type_id`);

--
-- Indices de la tabla `data_types`
--
ALTER TABLE `data_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_types_name_unique` (`name`),
  ADD UNIQUE KEY `data_types_slug_unique` (`slug`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gasto_tipos`
--
ALTER TABLE `gasto_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_name_unique` (`name`);

--
-- Indices de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pasarelas`
--
ALTER TABLE `pasarelas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_key_index` (`key`);

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamo_estados`
--
ALTER TABLE `prestamo_estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamo_planes`
--
ALTER TABLE `prestamo_planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamo_tipos`
--
ALTER TABLE `prestamo_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indices de la tabla `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_roles_user_id_index` (`user_id`),
  ADD KEY `user_roles_role_id_index` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cliente_tipos`
--
ALTER TABLE `cliente_tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `data_rows`
--
ALTER TABLE `data_rows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `data_types`
--
ALTER TABLE `data_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `gasto_tipos`
--
ALTER TABLE `gasto_tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pasarelas`
--
ALTER TABLE `pasarelas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prestamo_estados`
--
ALTER TABLE `prestamo_estados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamo_planes`
--
ALTER TABLE `prestamo_planes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `prestamo_tipos`
--
ALTER TABLE `prestamo_tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `data_rows`
--
ALTER TABLE `data_rows`
  ADD CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
