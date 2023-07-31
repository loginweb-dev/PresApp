-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2023 a las 04:43:11
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
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentos_respaldo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `tipo_id` int(11) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `nro_cuenta` double DEFAULT NULL,
  `token_cuenta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clave_cuenta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_tarjeta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin_tarjeta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_domicilio` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_trabajo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_poderes`
--

CREATE TABLE `cliente_poderes` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `inicio` date NOT NULL,
  `final` date NOT NULL,
  `detalle` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `documento` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(23, 4, 'created_at', 'timestamp', 'Created At', 0, 0, 1, 0, 0, 0, '{}', 2),
(24, 4, 'updated_at', 'timestamp', 'Updated', 0, 0, 1, 0, 0, 0, '{}', 3),
(25, 4, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(26, 4, 'nombre_completo', 'text', 'Nombre Completo', 1, 1, 1, 1, 1, 1, '{}', 5),
(27, 4, 'carnet_indentidad', 'text', 'CI', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 10),
(28, 4, 'telefono', 'number', 'Whatsapp', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":1}', 9),
(30, 4, 'foto_principal', 'image', 'Foto Principal', 0, 0, 1, 1, 1, 1, '{}', 19),
(31, 4, 'documentos_respaldo', 'file', 'Documentos Respaldo', 0, 0, 1, 1, 1, 1, '{}', 20),
(32, 5, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(33, 5, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, '{}', 2),
(34, 5, 'updated_at', 'timestamp', 'Updated At', 0, 1, 1, 0, 0, 0, '{}', 3),
(35, 5, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(36, 5, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(37, 4, 'cliente_belongsto_cliente_tipo_relationship', 'relationship', 'Tipo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\ClienteTipo\",\"table\":\"cliente_tipos\",\"type\":\"belongsTo\",\"column\":\"tipo_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 8),
(38, 4, 'tipo_id', 'text', 'Tipo Id', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 12),
(39, 1, 'email_verified_at', 'timestamp', 'Email Verified At', 0, 0, 0, 1, 1, 0, '{}', 6),
(40, 1, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 12),
(41, 6, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(42, 6, 'created_at', 'timestamp', 'Creado', 0, 0, 1, 0, 0, 0, '{}', 2),
(43, 6, 'updated_at', 'timestamp', 'Actualizado', 0, 0, 1, 0, 0, 0, '{}', 3),
(44, 6, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(45, 6, 'cliente_id', 'text', 'Cliente', 0, 0, 1, 1, 1, 1, '{}', 14),
(46, 6, 'tipo_id', 'text', 'Tipo', 0, 0, 1, 1, 1, 1, '{}', 15),
(47, 6, 'user_id', 'text', 'Editor', 0, 0, 0, 0, 0, 0, '{}', 16),
(48, 6, 'cuota', 'number', 'Cuota (CP)', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0\"}', 8),
(49, 6, 'interes', 'hidden', 'Interes (IP)', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0.03\"}', 13),
(50, 6, 'plazo', 'number', 'Plazo (PP)', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0\"}', 12),
(51, 6, 'monto', 'number', 'Monto (MP)', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"default\":\"0\"}', 6),
(52, 6, 'observacion', 'text_area', 'Detalles', 1, 1, 1, 1, 1, 1, '{\"default\":\"pr\\u00e9stamo sin observaciones ni detalles\"}', 18),
(53, 7, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(54, 7, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(55, 7, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(56, 7, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(57, 7, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 5),
(58, 6, 'prestamo_belongsto_cliente_relationship', 'relationship', 'Cliente', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\Cliente\",\"table\":\"clientes\",\"type\":\"belongsTo\",\"column\":\"cliente_id\",\"key\":\"id\",\"label\":\"nombre_completo\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 11),
(59, 6, 'prestamo_belongsto_prestamo_tipo_relationship', 'relationship', 'Tipo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"App\\\\PrestamoTipo\",\"table\":\"prestamo_tipos\",\"type\":\"belongsTo\",\"column\":\"tipo_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 5),
(61, 6, 'mes_inicio', 'date', 'Inicio del plan', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 10),
(62, 8, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(63, 8, 'created_at', 'timestamp', 'Created At', 0, 0, 1, 0, 0, 0, '{}', 2),
(64, 8, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(65, 8, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(66, 8, 'mes', 'text', 'Mes', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 6),
(67, 8, 'nro', 'number', 'Nro', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 5),
(68, 8, 'monto', 'number', 'Monto', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 7),
(69, 8, 'interes', 'number', 'Interes', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 8),
(70, 8, 'capital', 'number', 'Capital', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 9),
(72, 8, 'deuda', 'number', 'Deuda', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 11),
(73, 8, 'pagado', 'select_dropdown', 'Estado', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"},\"default\":\"0\",\"options\":{\"0\":\"En espera\",\"1\":\"Pago completado\",\"2\":\"Pago con mora\",\"3\":\"Refinanziado\"}}', 20),
(74, 8, 'observacion', 'text_area', 'Observacion', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"8\"}}', 19),
(75, 8, 'cuota', 'number', 'Cuota', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 10),
(76, 8, 'prestamo_id', 'number', 'Prestamo', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 18),
(77, 9, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(78, 9, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(79, 9, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(80, 9, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(81, 9, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(82, 6, 'estado_id', 'text', 'Estado', 0, 0, 0, 0, 0, 0, '{\"default\":1}', 17),
(84, 8, 'fecha', 'date', 'Fecha', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 12),
(85, 10, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(86, 10, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(87, 10, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(88, 10, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(89, 10, 'nombre', 'text', 'Nombre', 0, 1, 1, 1, 1, 1, '{}', 5),
(90, 8, 'pasarela_id', 'hidden', 'Pasarela', 0, 0, 1, 1, 1, 1, '{}', 21),
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
(110, 13, 'created_at', 'timestamp', 'Creado', 0, 1, 1, 0, 0, 0, '{}', 2),
(111, 13, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(112, 13, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(113, 13, 'capital_cantidad', 'number', 'Capital Cantidad', 1, 0, 1, 1, 1, 1, '{\"default\":0}', 6),
(114, 13, 'capital_monto', 'number', 'Capital Monto', 1, 0, 1, 1, 1, 1, '{\"default\":0}', 7),
(115, 13, 'pago_cantidad', 'number', 'Pago Cantidad', 1, 0, 1, 1, 1, 1, '{\"default\":0}', 8),
(116, 13, 'pago_monto', 'number', 'Pago Monto', 1, 0, 1, 1, 1, 1, '{\"default\":0}', 9),
(117, 13, 'gasto_cantidad', 'number', 'Gasto Cantidad', 1, 0, 0, 0, 0, 0, '{\"default\":0}', 10),
(118, 13, 'gasto_monto', 'number', 'Gasto Monto', 1, 0, 0, 0, 0, 0, '{\"default\":0}', 11),
(119, 13, 'mes', 'date', 'Mes', 1, 0, 1, 1, 1, 1, '{}', 5),
(120, 13, 'detalles', 'text_area', 'Detalles', 1, 1, 1, 1, 1, 1, '{\"default\":\"sin detalles para el mes\"}', 12),
(121, 6, 'fecha_prestamos', 'date', 'F. Prestamo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 9),
(122, 6, 'documentos', 'file', 'Documentos', 0, 0, 1, 1, 1, 1, '{}', 20),
(123, 7, 'detalle', 'markdown_editor', 'Descripcion', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 9),
(124, 9, 'detalle', 'text_area', 'Detalle', 0, 1, 1, 1, 1, 1, '{}', 6),
(125, 7, 'plazo_minimo', 'number', 'Plazo Minimo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 11),
(126, 7, 'plazo_maximo', 'number', 'Plazo Maximo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 12),
(127, 12, 'fecha', 'date', 'Fecha', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 7),
(128, 13, 'user_id', 'number', 'Editor', 0, 0, 1, 1, 1, 1, '{}', 13),
(129, 7, 'requisitos', 'markdown_editor', 'Requisitos', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 10),
(131, 8, 'fecha_pago', 'text', 'Fecha Pago', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 13),
(132, 8, 'user_id', 'hidden', 'Editor', 0, 0, 1, 1, 1, 1, '{}', 22),
(133, 8, 'mora', 'number', 'Mora', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 15),
(134, 8, 'refin', 'text', 'Refin', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 16),
(135, 4, 'latitude', 'number', 'Latitude', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 17),
(136, 4, 'longitude', 'number', 'Longitude', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 18),
(137, 4, 'nro_cuenta', 'number', 'Nro Cuenta', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 11),
(138, 4, 'token_cuenta', 'text', 'Token Cuenta', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 13),
(139, 4, 'clave_cuenta', 'text', 'Clave Cuenta', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 14),
(140, 4, 'nro_tarjeta', 'text', 'Nro Tarjeta', 0, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 15),
(141, 4, 'pin_tarjeta', 'text', 'Pin Tarjeta', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 16),
(142, 4, 'd_domicilio', 'text', 'D. Domicilio', 0, 0, 1, 1, 1, 1, '{}', 6),
(143, 4, 'd_trabajo', 'text', 'D. Trabajo', 0, 0, 1, 1, 1, 1, '{}', 7),
(144, 14, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(145, 14, 'created_at', 'timestamp', 'Created At', 0, 0, 1, 0, 0, 0, '{}', 2),
(146, 14, 'updated_at', 'timestamp', 'Updated At', 0, 0, 1, 0, 0, 0, '{}', 3),
(147, 14, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 1, 0, 0, 0, '{}', 4),
(148, 14, 'cliente_id', 'hidden', 'Cliente Id', 0, 1, 1, 1, 1, 1, '{}', 5),
(149, 8, 'amort', 'text', 'Amort', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 17),
(150, 14, 'tipo_id', 'number', 'Tipo', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"},\"default\":1}', 16),
(154, 14, 'prestamo_bono_belongsto_cliente_relationship', 'relationship', 'Cliente', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"},\"model\":\"App\\\\Cliente\",\"table\":\"clientes\",\"type\":\"belongsTo\",\"column\":\"cliente_id\",\"key\":\"id\",\"label\":\"nombre_completo\",\"pivot_table\":\"cliente_tipos\",\"pivot\":\"0\",\"taggable\":\"0\"}', 6),
(156, 14, 'f_bono', 'date', 'Fecha del bono', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 7),
(158, 14, 'interes', 'number', 'Interes', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 12),
(159, 14, 'doc_respado', 'file', 'Documento de respado', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 13),
(161, 14, 'estado_id', 'number', 'Estado', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"},\"default\":1}', 15),
(162, 14, 'detalle', 'text_area', 'Detalle', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"12\"},\"default\":\"pr\\u00e9stamo sin observaciones ni detalles\"}', 14),
(163, 15, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(164, 15, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 2),
(165, 15, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3),
(166, 15, 'deleted_at', 'timestamp', 'Deleted At', 0, 0, 0, 0, 0, 0, '{}', 4),
(167, 15, 'cliente_id', 'text', 'Cliente Id', 0, 1, 1, 1, 1, 1, '{}', 5),
(168, 15, 'inicio', 'date', 'Inicio', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 6),
(169, 15, 'final', 'date', 'Final', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 7),
(170, 15, 'cliente_podere_belongsto_cliente_relationship', 'relationship', 'clientes', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"},\"model\":\"\\\\App\\\\Cliente\",\"table\":\"clientes\",\"type\":\"belongsTo\",\"column\":\"cliente_id\",\"key\":\"id\",\"label\":\"nombre_completo\",\"pivot_table\":\"cliente_poderes\",\"pivot\":\"0\",\"taggable\":\"0\"}', 8),
(171, 15, 'detalle', 'text_area', 'Detalle', 1, 0, 1, 1, 1, 1, '{}', 10),
(172, 15, 'documento', 'file', 'Documento', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"6\"}}', 9),
(176, 8, 'p_final', 'text', 'P Final', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 14),
(179, 14, 'user_id', 'text', 'Editor', 0, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"},\"default\":1}', 17),
(180, 14, 'm_bono', 'number', 'Monto del bono', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 8),
(181, 14, 'm_prestamo', 'number', 'Monto del prestamo', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 9),
(182, 14, 'f_prestamo', 'date', 'Fecha del prestamo', 1, 1, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 10),
(183, 14, 'plazo', 'number', 'Plazo', 1, 0, 1, 1, 1, 1, '{\"display\":{\"width\":\"4\"}}', 11),
(184, 14, 'prestamo_bono_belongsto_prestamo_estado_relationship', 'relationship', 'Estado', 0, 1, 0, 0, 0, 0, '{\"model\":\"\\\\App\\\\PrestamoEstado\",\"table\":\"prestamo_estados\",\"type\":\"belongsTo\",\"column\":\"estado_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_poderes\",\"pivot\":\"0\",\"taggable\":\"0\"}', 18),
(185, 14, 'prestamo_bono_belongsto_user_relationship', 'relationship', 'Editor', 0, 1, 0, 0, 0, 0, '{\"model\":\"\\\\App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"cliente_poderes\",\"pivot\":\"0\",\"taggable\":\"0\"}', 19),
(186, 6, 'prestamo_belongsto_user_relationship', 'relationship', 'Editor', 0, 1, 1, 0, 0, 0, '{\"model\":\"\\\\App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"cliente_poderes\",\"pivot\":\"0\",\"taggable\":\"0\"}', 22),
(187, 8, 'prestamo_plane_belongsto_user_relationship', 'relationship', 'users', 0, 1, 1, 0, 0, 0, '{\"model\":\"\\\\App\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"user_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"cliente_poderes\",\"pivot\":\"0\",\"taggable\":\"0\"}', 23),
(188, 6, 'codigo', 'text', 'Codigo', 1, 1, 1, 0, 0, 0, '{\"display\":{\"width\":\"6\"}}', 19),
(189, 1, 'phone', 'number', 'Telefono', 0, 1, 1, 1, 1, 1, '{}', 13),
(190, 6, 'clase', 'select_dropdown', 'Clase', 1, 1, 1, 1, 1, 1, '{\"default\":\"Fijo\",\"options\":{\"Fijo\":\"Fijo\",\"Variable\":\"Variable\"},\"display\":{\"width\":\"6\"}}', 7),
(191, 6, 'prestamo_belongsto_prestamo_estado_relationship', 'relationship', 'Estado', 0, 1, 1, 0, 0, 0, '{\"model\":\"App\\\\PrestamoEstado\",\"table\":\"prestamo_estados\",\"type\":\"belongsTo\",\"column\":\"estado_id\",\"key\":\"id\",\"label\":\"nombre\",\"pivot_table\":\"cliente_poderes\",\"pivot\":\"0\",\"taggable\":\"0\"}', 21);

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
(1, 'users', 'users', 'Usuario', 'Usuarios', 'voyager-helm', 'TCG\\Voyager\\Models\\User', 'TCG\\Voyager\\Policies\\UserPolicy', 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController', NULL, 1, 0, '{\"order_column\":\"updated_at\",\"order_display_column\":\"name\",\"order_direction\":\"desc\",\"default_search_key\":\"name\",\"scope\":null}', '2023-06-17 16:25:09', '2023-07-31 02:27:22'),
(2, 'menus', 'menus', 'Menú', 'Menús', 'voyager-list', 'TCG\\Voyager\\Models\\Menu', NULL, '', '', 1, 0, NULL, '2023-06-17 16:25:09', '2023-06-17 16:25:09'),
(3, 'roles', 'roles', 'Rol', 'Roles', 'voyager-lock', 'TCG\\Voyager\\Models\\Role', NULL, 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController', '', 1, 0, NULL, '2023-06-17 16:25:09', '2023-06-17 16:25:09'),
(4, 'clientes', 'clientes', 'Cliente', 'Clientes', 'voyager-helm', 'App\\Cliente', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":\"nombre_completo\",\"scope\":null}', '2023-06-17 16:40:22', '2023-07-21 20:17:10'),
(5, 'cliente_tipos', 'cliente-tipos', 'Cliente Tipo', 'Cliente Tipos', 'voyager-helm', 'App\\ClienteTipo', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-06-17 16:43:30', '2023-07-17 02:54:19'),
(6, 'prestamos', 'prestamos', 'Prestamo', 'Prestamos', 'voyager-helm', 'App\\Prestamo', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}', '2023-06-17 17:21:32', '2023-07-30 20:10:24'),
(7, 'prestamo_tipos', 'prestamo-tipos', 'Tipo', 'Tipos', 'voyager-helm', 'App\\PrestamoTipo', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-06-17 17:22:17', '2023-07-21 20:09:06'),
(8, 'prestamo_planes', 'prestamo-planes', 'Prestamo Plane', 'Prestamo Planes', 'voyager-helm', 'App\\PrestamoPlane', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":\"id\",\"scope\":null}', '2023-06-18 17:22:44', '2023-07-22 15:22:55'),
(9, 'prestamo_estados', 'prestamo-estados', 'Estado', 'Estados', 'voyager-helm', 'App\\PrestamoEstado', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-06-18 20:33:14', '2023-07-21 20:09:19'),
(10, 'pasarelas', 'pasarelas', 'Pasarela', 'Pasarelas', 'voyager-helm', 'App\\Pasarela', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}', '2023-06-19 16:02:45', '2023-06-19 16:02:45'),
(11, 'gasto_tipos', 'gasto-tipos', 'Gasto Tipo', 'Gasto Tipos', 'voyager-helm', 'App\\GastoTipo', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-06-22 00:06:28', '2023-06-22 00:17:31'),
(12, 'gastos', 'gastos', 'Gasto', 'Gastos', 'voyager-helm', 'App\\Gasto', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":\"detalles\",\"scope\":null}', '2023-06-22 00:07:32', '2023-07-15 04:39:21'),
(13, 'reportes', 'reportes', 'Reporte', 'Reportes', 'voyager-helm', 'App\\Reporte', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}', '2023-06-22 18:08:28', '2023-07-18 21:40:33'),
(14, 'prestamo_bonos', 'prestamo-bonos', 'Bono', 'Bonos', 'voyager-double-right', 'App\\PrestamoBono', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-07-11 20:56:58', '2023-07-18 22:14:32'),
(15, 'cliente_poderes', 'cliente-poderes', 'Cliente Podere', 'Cliente Poderes', 'voyager-helm', 'App\\ClientePodere', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2023-07-14 22:48:53', '2023-07-17 02:56:56');

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
-- Estructura de tabla para la tabla `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `ref` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `refSerialize` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leads`
--

CREATE TABLE `leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', '2023-06-17 16:25:13', '2023-06-17 16:25:13'),
(2, 'whatsapp', '2023-07-21 21:39:02', '2023-07-21 21:39:14');

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
(2, 1, 'Multimedia', '', '_self', 'voyager-images', NULL, 5, 6, '2023-06-17 16:25:16', '2023-07-22 15:30:02', 'voyager.media.index', NULL),
(3, 1, 'Usuarios', '', '_self', 'voyager-helm', '#000000', 5, 2, '2023-06-17 16:25:16', '2023-06-28 20:58:43', 'voyager.users.index', 'null'),
(4, 1, 'Roles', '', '_self', 'voyager-lock', NULL, 5, 1, '2023-06-17 16:25:16', '2023-06-28 20:58:43', 'voyager.roles.index', NULL),
(5, 1, 'Herramientas', '', '_self', 'voyager-tools', NULL, NULL, 5, '2023-06-17 16:25:17', '2023-07-11 21:45:08', NULL, NULL),
(6, 1, 'Menús & Flujos', '', '_self', 'voyager-list', '#000000', 5, 8, '2023-06-17 16:25:18', '2023-07-22 15:30:02', 'voyager.menus.index', 'null'),
(7, 1, 'Base de Datos', '', '_self', 'voyager-data', NULL, 5, 7, '2023-06-17 16:25:18', '2023-07-22 15:30:02', 'voyager.database.index', NULL),
(8, 1, 'Compás', '', '_self', 'voyager-compass', NULL, 5, 3, '2023-06-17 16:25:18', '2023-06-28 20:58:55', 'voyager.compass.index', NULL),
(9, 1, 'BREAD', '', '_self', 'voyager-bread', NULL, 5, 5, '2023-06-17 16:25:19', '2023-07-22 15:30:02', 'voyager.bread.index', NULL),
(10, 1, 'Parametros', '', '_self', 'voyager-settings', '#000000', 5, 4, '2023-06-17 16:25:19', '2023-07-22 15:30:02', 'voyager.settings.index', 'null'),
(11, 1, 'Clientes', '', '_self', 'voyager-person', '#000000', NULL, 3, '2023-06-17 16:40:23', '2023-07-18 20:57:34', 'voyager.clientes.index', 'null'),
(13, 1, 'Mis prestamos', '', '_self', 'voyager-double-right', '#000000', 22, 1, '2023-06-17 17:21:33', '2023-06-28 20:57:18', 'voyager.prestamos.index', 'null'),
(14, 1, 'Tipos', '', '_self', 'voyager-double-right', '#000000', 22, 3, '2023-06-17 17:22:18', '2023-07-11 20:57:29', 'voyager.prestamo-tipos.index', 'null'),
(16, 1, 'Planes', '', '_self', 'voyager-double-right', '#000000', 22, 6, '2023-06-18 17:22:45', '2023-07-11 21:45:08', 'voyager.prestamo-planes.index', 'null'),
(17, 1, 'Estados', '', '_self', 'voyager-double-right', '#000000', 22, 4, '2023-06-18 20:33:15', '2023-07-11 20:57:29', 'voyager.prestamo-estados.index', 'null'),
(18, 1, 'Pasarelas', '', '_self', 'voyager-double-right', '#000000', 22, 5, '2023-06-19 16:02:45', '2023-07-11 20:57:29', 'voyager.pasarelas.index', 'null'),
(20, 1, 'Gastos', '', '_self', 'voyager-pen', '#000000', NULL, 2, '2023-06-22 00:07:32', '2023-07-18 20:57:34', 'voyager.gastos.index', 'null'),
(21, 1, 'Reportes', '', '_self', 'voyager-bar-chart', '#000000', NULL, 4, '2023-06-22 18:08:28', '2023-07-11 21:45:08', 'voyager.reportes.index', 'null'),
(22, 1, 'Prestamos', '', '_self', 'voyager-shop', '#000000', NULL, 1, '2023-06-28 20:20:47', '2023-06-28 20:54:35', NULL, ''),
(23, 1, 'Bonos', '', '_self', 'voyager-double-right', '#000000', 22, 2, '2023-07-11 20:57:00', '2023-07-11 20:57:34', 'voyager.prestamo-bonos.index', 'null'),
(25, 2, 'Flujo - P1: (hola, ole, alo, buenas, alguien)', '', '_self', NULL, '#000000', NULL, 1, '2023-07-21 21:39:31', '2023-07-25 23:18:38', NULL, ''),
(27, 2, '👉 Consultar deuda', '', '_self', NULL, '#000000', 26, 2, '2023-07-21 21:43:50', '2023-07-21 22:47:02', NULL, ''),
(28, 1, 'Chatbot', '/admin/bot-whatsapp', '_self', 'voyager-helm', '#000000', 5, 9, '2023-07-21 21:45:37', '2023-07-22 15:30:09', NULL, ''),
(30, 2, '👉 Nuestros servicios', '', '_self', NULL, '#000000', 26, 1, '2023-07-21 22:45:58', '2023-07-21 22:47:00', NULL, ''),
(31, 2, '🙌 Hola bienvenido, te saluda el chatbot, te puedo ayudar con las opciones:', '', '_self', NULL, '#000000', 25, 1, '2023-07-21 22:47:49', '2023-07-21 22:59:53', NULL, ''),
(32, 2, '1.- Consultar deuda con CI', '', '_self', NULL, '#000000', 25, 2, '2023-07-21 22:48:13', '2023-07-21 23:19:12', NULL, ''),
(33, 2, '2.- Nuestros servicios', '', '_self', NULL, '#000000', 25, 3, '2023-07-21 22:48:23', '2023-07-21 23:18:50', NULL, ''),
(34, 2, '🔍 Buscar y mostrar el  cliente y su deuda', '', '_self', NULL, '#000000', 32, 1, '2023-07-21 22:49:04', '2023-07-21 23:19:52', NULL, ''),
(35, 2, '🚀 Presentar nuestro servicios', '', '_self', NULL, '#000000', 33, 1, '2023-07-21 22:54:12', '2023-07-21 22:55:25', NULL, ''),
(36, 2, '3.- Chatear agente de ventas\'', '', '_self', NULL, '#000000', 25, 4, '2023-07-21 23:18:37', '2023-07-21 23:18:40', NULL, ''),
(37, 2, '👭 Mostrar todos los  agentes de ventas', '', '_self', NULL, '#000000', 36, 1, '2023-07-21 23:21:29', '2023-07-21 23:21:55', NULL, ''),
(39, 2, 'Flujo - P2 (Gracias, hasta luego)', '', '_self', NULL, '#000000', NULL, 6, '2023-07-25 23:18:47', '2023-07-25 23:19:18', NULL, '');

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
(75, 'delete_reportes', 'reportes', '2023-06-22 18:08:28', '2023-06-22 18:08:28'),
(76, 'browse_prestamo_bonos', 'prestamo_bonos', '2023-07-11 20:56:59', '2023-07-11 20:56:59'),
(77, 'read_prestamo_bonos', 'prestamo_bonos', '2023-07-11 20:56:59', '2023-07-11 20:56:59'),
(78, 'edit_prestamo_bonos', 'prestamo_bonos', '2023-07-11 20:56:59', '2023-07-11 20:56:59'),
(79, 'add_prestamo_bonos', 'prestamo_bonos', '2023-07-11 20:56:59', '2023-07-11 20:56:59'),
(80, 'delete_prestamo_bonos', 'prestamo_bonos', '2023-07-11 20:56:59', '2023-07-11 20:56:59'),
(81, 'browse_cliente_poderes', 'cliente_poderes', '2023-07-14 22:48:53', '2023-07-14 22:48:53'),
(82, 'read_cliente_poderes', 'cliente_poderes', '2023-07-14 22:48:53', '2023-07-14 22:48:53'),
(83, 'edit_cliente_poderes', 'cliente_poderes', '2023-07-14 22:48:53', '2023-07-14 22:48:53'),
(84, 'add_cliente_poderes', 'cliente_poderes', '2023-07-14 22:48:53', '2023-07-14 22:48:53'),
(85, 'delete_cliente_poderes', 'cliente_poderes', '2023-07-14 22:48:53', '2023-07-14 22:48:53');

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
(4, 3),
(5, 1),
(5, 3),
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
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1),
(31, 1),
(31, 3),
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
(40, 1),
(41, 1),
(41, 3),
(42, 3),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
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
(57, 3),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(71, 3),
(72, 1),
(72, 3),
(73, 1),
(74, 1),
(74, 3),
(75, 1),
(76, 1),
(76, 3),
(78, 1),
(78, 3),
(79, 1),
(79, 3),
(80, 1),
(81, 1),
(81, 3),
(83, 1),
(83, 3),
(84, 1),
(84, 3),
(85, 1);

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
  `mes_inicio` date NOT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `fecha_prestamos` date DEFAULT NULL,
  `documentos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clase` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_bonos`
--

CREATE TABLE `prestamo_bonos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `f_bono` date NOT NULL,
  `interes` double NOT NULL,
  `doc_respado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `detalle` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `m_bono` double NOT NULL,
  `m_prestamo` double NOT NULL,
  `f_prestamo` date NOT NULL,
  `plazo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2023-06-18 20:34:08', '2023-07-30 01:59:40', NULL, 'EN PROCESO', 'son los préstamos que no tienen deuda y están incompletos'),
(4, '2023-06-18 20:35:03', '2023-06-27 15:37:47', NULL, 'COMPLETADO', 'son los préstamos que completan el plan de pago.'),
(5, '2023-07-14 18:50:25', '2023-07-14 18:50:49', NULL, 'EN JUCIO O CONGELADO', 'En este estado el interés mensual deja de cobrarse');

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
  `pagado` int(11) DEFAULT NULL,
  `observacion` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prestamo_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `pasarela_id` int(11) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mora` double DEFAULT 0,
  `refin` double DEFAULT 0,
  `amort` double DEFAULT 0,
  `p_final` double DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `plazo_maximo` int(11) DEFAULT NULL,
  `requisitos` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamo_tipos`
--

INSERT INTO `prestamo_tipos` (`id`, `created_at`, `updated_at`, `deleted_at`, `nombre`, `monto_interes`, `monto_minimo`, `monto_maximo`, `detalle`, `plazo_minimo`, `plazo_maximo`, `requisitos`) VALUES
(1, '2023-06-17 17:24:54', '2023-07-03 03:54:58', NULL, 'Prestamo  al 3%', 0.03, 3000, 30000, '* Es un tipo de préstamo donde la cuota, el capital y el interés son montos fijos.\r\nLa couta mensual sale del calculo de el plazo y moto a prestar.\r\n* **CP = (MP+IP)/PP**', 6, 24, '1. Fotocopia de CI\r\n2. Ultima boleta de pago'),
(2, '2023-06-17 17:25:06', '2023-07-28 14:29:30', NULL, 'Prestamo  al 4%', 0.04, 5000, 50000, '* Es un tipo de préstamo donde solo los pagos (CP) son fijos, el  interés y el capital son variables.', 9, 36, '1. Fotocopia de CI \r\n2. Ultima boleta de pago'),
(3, '2023-07-20 19:18:01', '2023-07-30 21:03:59', NULL, 'Préstamo al 5%', 0.05, 1000, 50000, 'Sólo excepciones\r\nEs un tipo de préstamo donde solo los pagos (CP) son fijos, el interés y el capital son variables.', 6, 36, '* Boleta de pago\r\n* fotocopia de C.I\r\n* Garantia de inmueble \r\n* Inscripcion a derechos reales \r\n* Contrato de prestamo');

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
  `gasto_cantidad` int(11) DEFAULT NULL,
  `gasto_monto` double DEFAULT NULL,
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
(2, 'site.description', 'Descripción del sitio', 'Sistema de administración PresApp v1.0', '', 'text', 2, 'Site'),
(3, 'site.logo', 'Logo del sitio', '', '', 'image', 3, 'Site'),
(4, 'site.google_analytics_tracking_id', 'ID de rastreo de Google Analytics', NULL, '', 'text', 4, 'Site'),
(5, 'admin.bg_image', 'Imagen de fondo del administrador', '', '', 'image', 5, 'Admin'),
(6, 'admin.title', 'Título del administrador', 'PresApp', '', 'text', 1, 'Admin'),
(7, 'admin.description', 'Descripción del administrador', 'Aplicativo para gestionar negocios de prestamos', '', 'text', 2, 'Admin'),
(8, 'admin.loader', 'Imagen de carga del administrador', '', '', 'image', 3, 'Admin'),
(9, 'admin.icon_image', 'Ícono del administrador', '', '', 'image', 4, 'Admin'),
(10, 'admin.google_analytics_client_id', 'ID de Cliente para Google Analytics (usado para el tablero de administrador)', NULL, '', 'text', 1, 'Admin'),
(12, 'prestamos.redondear', 'Redondear', 'rmx', '{\n    \"default\" : \"nor\",\n    \"options\" : {\n        \"nor\": \"No redondear\",\n        \"rmx\": \"Redondear al miximo proximo\",\n        \"rmi\": \"Redondear al minmo proximo\"\n    }\n}', 'select_dropdown', 7, 'Prestamos'),
(16, 'chatbot.nombre', 'Nombre', 'LIZA', NULL, 'text', 8, 'Chatbot'),
(17, 'chatbot.bienvenida', 'Bienvenida', '🙌 Hola bienvenid@ a mi negocio en whatsapp, te puedo ayudar con las opciones de:', NULL, 'text_area', 9, 'Chatbot');

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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `remember_token`, `settings`, `created_at`, `updated_at`, `deleted_at`, `phone`) VALUES
(5, 1, 'Damaris Nova Parada', 'damarisnovaparada@gmail.com', 'users/default.png', NULL, '$2y$10$OtWqkQguG.jNnV69BwWocOen85soRqxVe8nxn03EWbQOrCcbGgPiO', NULL, '{\"locale\":\"es\"}', '2023-07-07 01:09:02', '2023-07-20 18:31:17', NULL, NULL),
(6, 3, 'Carlos Mendoz', 'carlos.mendoza@ventas.com', 'users/default.png', NULL, '$2y$10$SVze2Oq2mo37s48efdM/m.qpwjR4joyXgqY..9sB8cDi6/r6jEoiW', 'YqhP98xxYyrapIfqEF0YuAtwnwCxfNhdlmntIAjrnhVg72XsvlQurt6ahJby', '{\"locale\":\"es\"}', '2023-07-07 01:09:38', '2023-07-20 16:50:46', NULL, NULL),
(7, 1, 'super', 'super@super.com', 'users/default.png', NULL, '$2y$10$zeIVoXEZWSq2g9yRDVyai.AenQmzZhZ7FU02MfKddSJirtFVHXdxG', 'vih2lJvV9SmtwTs4W8QqqLOdGm48hNuaKKWXuJEjM15JCi61UAaMEM0ms4l4', NULL, '2023-07-20 18:56:08', '2023-07-20 18:56:08', NULL, NULL),
(8, 3, 'test001', 'test001@test.com', 'users/default.png', NULL, '$2y$10$2FrJzl7dV8nFKfwSNR9QGuK5NPPBkZsSwKL6Jf3mCJib9druwgG4y', 'DCQuoOuMe9Ke5X1qoH4vdKzKDODO9kNW497RPX9OHMBVvWGIHRZaKZwjiXYE', '{\"locale\":\"es\"}', '2023-07-20 18:58:18', '2023-07-31 02:30:15', NULL, '59171130523');

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
-- Indices de la tabla `cliente_poderes`
--
ALTER TABLE `cliente_poderes`
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
-- Indices de la tabla `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `leads`
--
ALTER TABLE `leads`
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
-- Indices de la tabla `prestamo_bonos`
--
ALTER TABLE `prestamo_bonos`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente_poderes`
--
ALTER TABLE `cliente_poderes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente_tipos`
--
ALTER TABLE `cliente_tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `data_rows`
--
ALTER TABLE `data_rows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT de la tabla `data_types`
--
ALTER TABLE `data_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gasto_tipos`
--
ALTER TABLE `gasto_tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamo_bonos`
--
ALTER TABLE `prestamo_bonos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamo_estados`
--
ALTER TABLE `prestamo_estados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `prestamo_planes`
--
ALTER TABLE `prestamo_planes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamo_tipos`
--
ALTER TABLE `prestamo_tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
