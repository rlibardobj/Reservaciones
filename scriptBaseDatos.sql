-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-10-2013 a las 17:52:35
-- Versión del servidor: 5.1.67
-- Versión de PHP: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `reservaciones`
--

CREATE DATABASE reservaciones;
USE reservaciones;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad`
--

CREATE TABLE IF NOT EXISTS `actividad` (
  `id_proforma` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prestamo` tinyint(1) NOT NULL DEFAULT '0',
  `codigo` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_contacto` date NOT NULL,
  `atencion` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cuenta_pago` bigint(20) unsigned NOT NULL,
  `confirmada` tinyint(1) NOT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `cupo` int(10) unsigned NOT NULL DEFAULT '0',
  `inscritos` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_proforma`),
  UNIQUE KEY `id_proforma` (`id_proforma`),
  KEY `cuenta_pago` (`cuenta_pago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_email`
--

CREATE TABLE IF NOT EXISTS `actividad_email` (
  `id_proforma_emails` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proforma` bigint(20) unsigned NOT NULL,
  `email` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_proforma_emails`),
  UNIQUE KEY `id_proforma_emails` (`id_proforma_emails`),
  KEY `proforma` (`proforma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Lista emails de proforma, para envío de notificaciones.' AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_espacio`
--

CREATE TABLE IF NOT EXISTS `actividad_espacio` (
  `id_proforma_espacio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proforma` bigint(20) unsigned NOT NULL,
  `espacio` bigint(20) unsigned NOT NULL,
  `precio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_proforma_espacio`),
  UNIQUE KEY `id_proforma_espacio` (`id_proforma_espacio`),
  KEY `proforma` (`proforma`,`espacio`),
  KEY `proforma_2` (`proforma`),
  KEY `espacio` (`espacio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_espacio_intervalo`
--

CREATE TABLE IF NOT EXISTS `actividad_espacio_intervalo` (
  `id_intervalo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proforma_espacio` bigint(20) unsigned NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `proforma` bigint(20) unsigned NOT NULL,
  `espacio` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_intervalo`),
  UNIQUE KEY `id_intervalo` (`id_intervalo`),
  KEY `proforma_espacio` (`proforma_espacio`),
  KEY `proforma` (`proforma`,`espacio`),
  KEY `espacio` (`espacio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_servicio`
--

CREATE TABLE IF NOT EXISTS `actividad_servicio` (
  `id_proforma_servicio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proforma` bigint(20) unsigned NOT NULL,
  `servicio` bigint(20) unsigned NOT NULL,
  `precio` int(10) unsigned NOT NULL,
  `cantidad` smallint(6) NOT NULL,
  PRIMARY KEY (`id_proforma_servicio`),
  UNIQUE KEY `id_proforma_servicio` (`id_proforma_servicio`),
  KEY `proforma` (`proforma`,`servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_proveedor`
--

CREATE TABLE IF NOT EXISTS `categoria_proveedor` (
  `id_categoria` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_servicio`
--

CREATE TABLE IF NOT EXISTS `categoria_servicio` (
  `id_categoria` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE IF NOT EXISTS `contactos` (
  `Contacto` varchar(100) DEFAULT NULL,
  `Empresa` varchar(100) DEFAULT NULL,
  `Telefono` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Sector` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_pago`
--

CREATE TABLE IF NOT EXISTS `cuenta_pago` (
  `id_cuenta_pago` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_cuenta_pago`),
  UNIQUE KEY `id_cuenta_pago` (`id_cuenta_pago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacio`
--

CREATE TABLE IF NOT EXISTS `espacio` (
  `id_espacio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `capacidad_personas` smallint(5) unsigned NOT NULL,
  `precio_base` int(10) unsigned NOT NULL,
  `modo_alquiler` bigint(20) unsigned NOT NULL,
  `subespacio` bigint(20) unsigned DEFAULT NULL COMMENT 'Determina si es subespacio de otro espacio. Ejemplo: Bromelia 1 es subespacio de Bromelia.',
  `imagen` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_espacio`),
  UNIQUE KEY `id_espacio` (`id_espacio`),
  KEY `modo_alquiler` (`modo_alquiler`),
  KEY `subespacio` (`subespacio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modo_alquiler`
--

CREATE TABLE IF NOT EXISTS `modo_alquiler` (
  `id_modo_alquiler` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_horas` smallint(6) NOT NULL,
  PRIMARY KEY (`id_modo_alquiler`),
  UNIQUE KEY `id_modo_alquiler` (`id_modo_alquiler`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `categoria` bigint(20) unsigned NOT NULL,
  `telefono` varchar(12) COLLATE utf8_spanish_ci DEFAULT NULL,
  `detalles` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`),
  UNIQUE KEY `id_proveedor` (`id_proveedor`),
  KEY `categoria` (`categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE IF NOT EXISTS `servicios` (
  `id_servicio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `precio_base` int(11) NOT NULL,
  `categoría` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_servicio`),
  UNIQUE KEY `id_servicio` (`id_servicio`),
  KEY `categoría` (`categoría`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `contrasenna` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `api_calls` int(10) unsigned NOT NULL,
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=0 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`cuenta_pago`) REFERENCES `cuenta_pago` (`id_cuenta_pago`);

--
-- Filtros para la tabla `actividad_email`
--
ALTER TABLE `actividad_email`
  ADD CONSTRAINT `actividad_email_ibfk_1` FOREIGN KEY (`proforma`) REFERENCES `actividad` (`id_proforma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `actividad_espacio`
--
ALTER TABLE `actividad_espacio`
  ADD CONSTRAINT `actividad_espacio_ibfk_1` FOREIGN KEY (`proforma`) REFERENCES `actividad` (`id_proforma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividad_espacio_ibfk_2` FOREIGN KEY (`espacio`) REFERENCES `espacio` (`id_espacio`);

--
-- Filtros para la tabla `actividad_espacio_intervalo`
--
ALTER TABLE `actividad_espacio_intervalo`
  ADD CONSTRAINT `actividad_espacio_intervalo_ibfk_1` FOREIGN KEY (`proforma_espacio`) REFERENCES `actividad_espacio` (`id_proforma_espacio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividad_espacio_intervalo_ibfk_2` FOREIGN KEY (`proforma`) REFERENCES `actividad` (`id_proforma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividad_espacio_intervalo_ibfk_3` FOREIGN KEY (`espacio`) REFERENCES `espacio` (`id_espacio`);

--
-- Filtros para la tabla `actividad_servicio`
--
ALTER TABLE `actividad_servicio`
  ADD CONSTRAINT `actividad_servicio_ibfk_1` FOREIGN KEY (`proforma`) REFERENCES `actividad` (`id_proforma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `espacio`
--
ALTER TABLE `espacio`
  ADD CONSTRAINT `espacio_ibfk_1` FOREIGN KEY (`modo_alquiler`) REFERENCES `modo_alquiler` (`id_modo_alquiler`),
  ADD CONSTRAINT `espacio_ibfk_2` FOREIGN KEY (`subespacio`) REFERENCES `espacio` (`id_espacio`);

--
-- Filtros para la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categoria_proveedor` (`id_categoria`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`categoría`) REFERENCES `categoria_servicio` (`id_categoria`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
