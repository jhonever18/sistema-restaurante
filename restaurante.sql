-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-02-2026 a las 17:35:56
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
-- Base de datos: `restaurante`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int(11) NOT NULL,
  `categoria_nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `categoria_nombre`) VALUES
(1, 'Entradas'),
(2, 'Pastas'),
(3, 'Bebidas'),
(4, 'Postres'),
(5, 'Pizzas populares'),
(6, 'Pizzas clásicas'),
(7, 'Pizzas especiales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cliente_id` bigint(20) NOT NULL,
  `cli_nombre` varchar(50) NOT NULL,
  `cli_documento` varchar(20) NOT NULL,
  `cli_apellido` varchar(50) DEFAULT NULL,
  `cli_correo` varchar(100) NOT NULL,
  `cli_contrasena` varchar(255) DEFAULT NULL,
  `cli_telefono` varchar(15) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `cli_nombre`, `cli_documento`, `cli_apellido`, `cli_correo`, `cli_contrasena`, `cli_telefono`, `fecha_registro`) VALUES
(65, 'monica', '34253', NULL, 'mafda@gmail.com', NULL, '53262537', '2025-07-11 20:48:12'),
(66, 'ever ', '', 'mosquera', 'ever@gmail.com', '$2y$10$frL9YnoXWJZ1RTbIZfvTx.GC1SI8F0B.RHkSVdaBw6QNBRIS4WifK', '', '2025-07-11 20:55:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `detalle_id` int(11) NOT NULL,
  `factura_id` int(11) DEFAULT NULL,
  `plato_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `esta_id` int(11) NOT NULL,
  `esta_desc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`esta_id`, `esta_desc`) VALUES
(1, 'activo'),
(2, 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedido`
--

CREATE TABLE `estado_pedido` (
  `estped_id` int(11) NOT NULL,
  `estped_desc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_pedido`
--

INSERT INTO `estado_pedido` (`estped_id`, `estped_desc`) VALUES
(4, 'cancelado'),
(2, 'en proceso'),
(3, 'entregado'),
(1, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `factura_id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `factura_desc` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `ing_id` int(11) NOT NULL,
  `esta_id` int(11) DEFAULT NULL,
  `ing_nombre` varchar(30) DEFAULT NULL,
  `ing_desc` varchar(100) DEFAULT NULL,
  `ing_cantidad` decimal(10,2) DEFAULT NULL,
  `unidad_id` int(11) DEFAULT NULL,
  `ing_precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes`
--

INSERT INTO `ingredientes` (`ing_id`, `esta_id`, `ing_nombre`, `ing_desc`, `ing_cantidad`, `unidad_id`, `ing_precio`) VALUES
(25, 1, 'pollo', 'pollo desmechado', 0.03, 1, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `ing_id` int(11) DEFAULT NULL,
  `plato_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `metopago_id` int(11) NOT NULL,
  `metopago_desc` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`metopago_id`, `metopago_desc`) VALUES
(3, 'Daviplata'),
(1, 'Efectivo'),
(2, 'Nequi'),
(4, 'Tarjeta débito/crédito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `pedido_id` int(11) NOT NULL,
  `metopago_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `pedido_fecha` varchar(20) DEFAULT NULL,
  `pedido_valor_pagar` varchar(20) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `estped_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `metopago_id`, `user_id`, `pedido_fecha`, `pedido_valor_pagar`, `cliente_id`, `estped_id`) VALUES
(111, 2, NULL, '2025-07-11 22:48:12', '2.48', 65, 3),
(112, 1, NULL, '2025-07-11 22:55:40', '0.08', 66, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `detalle_id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `plato_nombre` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`detalle_id`, `pedido_id`, `plato_nombre`, `cantidad`, `precio_unitario`) VALUES
(66, 111, 'pizza mexicana', 31, 0.08),
(67, 112, 'pizza mexicana', 1, 0.08);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_platos`
--

CREATE TABLE `pedido_platos` (
  `pedido_id` int(11) NOT NULL,
  `plato_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `plato_id` int(11) NOT NULL,
  `plato_nombre` varchar(100) DEFAULT NULL,
  `plato_desc` varchar(445) DEFAULT NULL,
  `plato_precio` decimal(10,2) DEFAULT NULL,
  `plato_imagen_url` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `es_popular` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`plato_id`, `plato_nombre`, `plato_desc`, `plato_precio`, `plato_imagen_url`, `categoria_id`, `es_popular`) VALUES
(226, 'pizza mexicana', 'pizza con pimento', 0.08, '../uploads/platos/plato_6871771859a69.png', 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plato_ingredientes`
--

CREATE TABLE `plato_ingredientes` (
  `plato_id` int(11) NOT NULL,
  `ing_id` int(11) NOT NULL,
  `cantidad` float NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plato_ingredientes`
--

INSERT INTO `plato_ingredientes` (`plato_id`, `ing_id`, `cantidad`) VALUES
(226, 25, 0.02);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(11) NOT NULL,
  `rol_desc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol_id`, `rol_desc`) VALUES
(1, 'administrador'),
(2, 'cajero'),
(3, 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_id`
--

CREATE TABLE `tipo_id` (
  `ti_id` int(11) NOT NULL,
  `ti_desc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_id`
--

INSERT INTO `tipo_id` (`ti_id`, `ti_desc`) VALUES
(1, 'cedula ciudadana'),
(3, 'cedula ciudadana dig'),
(5, 'cedula extranjera'),
(4, 'pasaporte'),
(2, 'tarjeta de identidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `unidad_id` int(11) NOT NULL,
  `unidad_nombre` varchar(20) NOT NULL,
  `unidad_abreviacion` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidades_medida`
--

INSERT INTO `unidades_medida` (`unidad_id`, `unidad_nombre`, `unidad_abreviacion`) VALUES
(1, 'Kilogramos', 'kg'),
(2, 'Gramos', 'g'),
(3, 'Litros', 'L'),
(4, 'Mililitros', 'ml'),
(5, 'Unidades', 'und');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `user_id` bigint(20) NOT NULL,
  `ti_desc` varchar(20) DEFAULT NULL,
  `user_nombre` varchar(20) DEFAULT NULL,
  `user_apellido` varchar(20) DEFAULT NULL,
  `user_correo` varchar(30) DEFAULT NULL,
  `user_contrasena` varchar(255) DEFAULT NULL,
  `user_telefono` varchar(10) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `user_foto` varchar(255) DEFAULT NULL,
  `esta_id` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`user_id`, `ti_desc`, `user_nombre`, `user_apellido`, `user_correo`, `user_contrasena`, `user_telefono`, `rol_id`, `user_foto`, `esta_id`, `fecha_registro`) VALUES
(12345, 'cedula ciudadana', 'maria', 'lucumi', 'maria@gmail.com', 'mary', '31155658', 2, '1752267112_JJJ_s_Pizzas-removebg-preview.png', 1, '2025-07-11 15:44:30'),
(8954451, 'cedula ciudadana', 'Administrador', 'jjj\'s', 'admin@gmail.com', '123', '310536', 1, '1771601560_Imagen_de_WhatsApp_2025-07-03_a_las_10.08.06_b1c40acf-removebg-preview.png', 1, '2025-07-08 03:37:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `plato_id` (`plato_id`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`esta_id`),
  ADD UNIQUE KEY `esta_desc` (`esta_desc`);

--
-- Indices de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  ADD PRIMARY KEY (`estped_id`),
  ADD UNIQUE KEY `estped_desc` (`estped_desc`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`factura_id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`ing_id`),
  ADD KEY `esta_id` (`esta_id`),
  ADD KEY `fk_unidad_id` (`unidad_id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `ing_id` (`ing_id`),
  ADD KEY `plato_id` (`plato_id`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`metopago_id`),
  ADD UNIQUE KEY `metopago_desc` (`metopago_desc`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedido_id`),
  ADD KEY `metopago_id` (`metopago_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_pedidos_cliente` (`cliente_id`),
  ADD KEY `fk_estado_pedido` (`estped_id`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `pedido_platos`
--
ALTER TABLE `pedido_platos`
  ADD PRIMARY KEY (`pedido_id`,`plato_id`),
  ADD KEY `plato_id` (`plato_id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`plato_id`),
  ADD KEY `fk_categoria_id` (`categoria_id`);

--
-- Indices de la tabla `plato_ingredientes`
--
ALTER TABLE `plato_ingredientes`
  ADD PRIMARY KEY (`plato_id`,`ing_id`),
  ADD KEY `fk_ingrediente` (`ing_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`),
  ADD UNIQUE KEY `rol_desc` (`rol_desc`);

--
-- Indices de la tabla `tipo_id`
--
ALTER TABLE `tipo_id`
  ADD PRIMARY KEY (`ti_id`),
  ADD UNIQUE KEY `ti_desc` (`ti_desc`);

--
-- Indices de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`unidad_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_correo` (`user_correo`),
  ADD UNIQUE KEY `user_telefono` (`user_telefono`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `esta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `estped_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `factura_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `ing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `metopago_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `plato_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipo_id`
--
ALTER TABLE `tipo_id`
  MODIFY `ti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `unidad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`factura_id`),
  ADD CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`plato_id`) REFERENCES `platos` (`plato_id`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`pedido_id`);

--
-- Filtros para la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `fk_unidad_id` FOREIGN KEY (`unidad_id`) REFERENCES `unidades_medida` (`unidad_id`),
  ADD CONSTRAINT `ingredientes_ibfk_1` FOREIGN KEY (`esta_id`) REFERENCES `estado` (`esta_id`),
  ADD CONSTRAINT `ingredientes_ibfk_2` FOREIGN KEY (`unidad_id`) REFERENCES `unidades_medida` (`unidad_id`);

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`ing_id`) REFERENCES `ingredientes` (`ing_id`),
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`plato_id`) REFERENCES `platos` (`plato_id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_estado_pedido` FOREIGN KEY (`estped_id`) REFERENCES `estado_pedido` (`estped_id`),
  ADD CONSTRAINT `fk_pedidos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidos_metodopago` FOREIGN KEY (`metopago_id`) REFERENCES `metodo_pago` (`metopago_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`pedido_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_platos`
--
ALTER TABLE `pedido_platos`
  ADD CONSTRAINT `pedido_platos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`pedido_id`),
  ADD CONSTRAINT `pedido_platos_ibfk_2` FOREIGN KEY (`plato_id`) REFERENCES `platos` (`plato_id`);

--
-- Filtros para la tabla `platos`
--
ALTER TABLE `platos`
  ADD CONSTRAINT `fk_categoria_id` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`categoria_id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `plato_ingredientes`
--
ALTER TABLE `plato_ingredientes`
  ADD CONSTRAINT `fk_ingrediente` FOREIGN KEY (`ing_id`) REFERENCES `ingredientes` (`ing_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_plato` FOREIGN KEY (`plato_id`) REFERENCES `platos` (`plato_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
