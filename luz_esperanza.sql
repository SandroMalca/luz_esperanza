-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-01-2025 a las 19:10:38
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
-- Base de datos: `soledent`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso`
--

CREATE TABLE `egreso` (
  `id` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `egreso` decimal(10,2) NOT NULL,
  `descripcion` varchar(750) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exploracion_fisica`
--

CREATE TABLE `exploracion_fisica` (
  `id` int(10) NOT NULL,
  `id_historia` int(10) NOT NULL,
  `id_tratamiento` int(10) NOT NULL,
  `presion_arterial` decimal(10,2) NOT NULL,
  `pulso` decimal(10,2) NOT NULL,
  `temperatura` decimal(10,2) NOT NULL,
  `frec_respiratoria` decimal(10,2) NOT NULL,
  `exa_general` varchar(750) NOT NULL,
  `odontoesto` varchar(750) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `exploracion_fisica`
--

INSERT INTO `exploracion_fisica` (`id`, `id_historia`, `id_tratamiento`, `presion_arterial`, `pulso`, `temperatura`, `frec_respiratoria`, `exa_general`, `odontoesto`) VALUES
(1, 1, 0, 0.00, 0.00, 0.00, 0.00, '', ''),
(2, 2, 0, 0.00, 0.00, 0.00, 0.00, '', ''),
(3, 3, 0, 0.00, 0.00, 0.00, 0.00, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia`
--

CREATE TABLE `historia` (
  `id` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `id_cliente` int(10) NOT NULL,
  `plantratamiento` varchar(750) NOT NULL,
  `motivo` varchar(750) NOT NULL,
  `tiempo` varchar(750) NOT NULL,
  `signos` varchar(750) NOT NULL,
  `pronostico` varchar(750) NOT NULL,
  `recomendacion` varchar(750) NOT NULL,
  `diagnostico` varchar(750) NOT NULL,
  `fec_creacion` date NOT NULL,
  `antec_familiar` varchar(750) NOT NULL,
  `antec_personal` varchar(750) NOT NULL,
  `odontograma` varchar(1500) NOT NULL,
  `presupuesto` decimal(10,2) NOT NULL,
  `id_doctor` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `historia`
--

INSERT INTO `historia` (`id`, `fecha`, `id_cliente`, `plantratamiento`, `motivo`, `tiempo`, `signos`, `pronostico`, `recomendacion`, `diagnostico`, `fec_creacion`, `antec_familiar`, `antec_personal`, `odontograma`, `presupuesto`, `id_doctor`) VALUES
(1, '0000-00-00', 5, 'PUENTE DE 5 PIEZAS IVOCRON', 'EDENTULA PARCIAL', '', '', '', '', '', '2020-10-05', '', '', '', 700.00, 0),
(2, '2020-10-07', 60, '', 'DOLOR DE DIENTE 2.5', '', '', '', '', '', '2020-10-07', '', '', '', 250.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista`
--

CREATE TABLE `lista` (
  `id` int(10) NOT NULL,
  `nombre` varchar(1500) NOT NULL,
  `descripcion` varchar(1500) NOT NULL,
  `cantidad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `lista`
--

INSERT INTO `lista` (`id`, `nombre`, `descripcion`, `cantidad`) VALUES
(2, 'COMPRAS EMANCIPACIÓN OCTUBRE (1)', 'ANESTESIA NORMAL (5 CAJAS)\r\nANESTESIA ESPECIAL (1CAJA)\r\nSUCTORES DE ENDONDONCIA (2)\r\nPORTA FÉRULA (10)\r\nPASTA PROFILÁCTICA\r\nMULTICEM\r\nDYCAL\r\nAGUJAS CORTAS\r\nMICROBRUSH \r\nESCOBILLAS PROFILACTICAS\r\nACRILICO #62\r\n', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logueo`
--

CREATE TABLE `logueo` (
  `id` int(10) NOT NULL,
  `user` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `acceso` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `logueo`
--

INSERT INTO `logueo` (`id`, `user`, `password`, `acceso`) VALUES
(1, 'superadmin', '4465UsB', 'Superadmin'),
(2, 'admin', '19391945', 'Admin'),
(3, 'asistente', 'NoM1215', 'Asistente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `id` int(10) NOT NULL,
  `fec_ven` date NOT NULL,
  `cantidad` int(10) NOT NULL,
  `usado` int(10) NOT NULL,
  `lote` varchar(150) NOT NULL,
  `id_producto` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `lote`
--

INSERT INTO `lote` (`id`, `fec_ven`, `cantidad`, `usado`, `lote`, `id_producto`) VALUES
(1, '2025-01-07', 20, 0, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `odontograma`
--

CREATE TABLE `odontograma` (
  `id` int(10) NOT NULL,
  `id_historia` int(10) NOT NULL,
  `dato` varchar(150) NOT NULL,
  `a8` varchar(150) NOT NULL,
  `a7` varchar(150) NOT NULL,
  `a6` varchar(150) NOT NULL,
  `a5` varchar(150) NOT NULL,
  `a4` varchar(150) NOT NULL,
  `a3` varchar(150) NOT NULL,
  `a2` varchar(150) NOT NULL,
  `a1` varchar(150) NOT NULL,
  `b1` varchar(150) NOT NULL,
  `b2` varchar(150) NOT NULL,
  `b3` varchar(150) NOT NULL,
  `b4` varchar(150) NOT NULL,
  `b5` varchar(150) NOT NULL,
  `b6` varchar(150) NOT NULL,
  `b7` varchar(150) NOT NULL,
  `b8` varchar(150) NOT NULL,
  `d8` varchar(150) NOT NULL,
  `d7` varchar(150) NOT NULL,
  `d6` varchar(150) NOT NULL,
  `d5` varchar(150) NOT NULL,
  `d4` varchar(150) NOT NULL,
  `d3` varchar(150) NOT NULL,
  `d2` varchar(150) NOT NULL,
  `d1` varchar(150) NOT NULL,
  `c1` varchar(150) NOT NULL,
  `c2` varchar(150) NOT NULL,
  `c3` varchar(150) NOT NULL,
  `c4` varchar(150) NOT NULL,
  `c5` varchar(150) NOT NULL,
  `c6` varchar(150) NOT NULL,
  `c7` varchar(150) NOT NULL,
  `c8` varchar(150) NOT NULL,
  `e5` varchar(150) NOT NULL,
  `e4` varchar(150) NOT NULL,
  `e3` varchar(150) NOT NULL,
  `e2` varchar(150) NOT NULL,
  `e1` varchar(150) NOT NULL,
  `f1` varchar(150) NOT NULL,
  `f2` varchar(150) NOT NULL,
  `f3` varchar(150) NOT NULL,
  `f4` varchar(150) NOT NULL,
  `f5` varchar(150) NOT NULL,
  `h5` varchar(150) NOT NULL,
  `h4` varchar(150) NOT NULL,
  `h3` varchar(150) NOT NULL,
  `h2` varchar(150) NOT NULL,
  `h1` varchar(150) NOT NULL,
  `g1` varchar(150) NOT NULL,
  `g2` varchar(150) NOT NULL,
  `g3` varchar(150) NOT NULL,
  `g4` varchar(150) NOT NULL,
  `g5` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `efectivo` decimal(10,2) NOT NULL,
  `tarjeta` decimal(10,2) NOT NULL,
  `descripcion` varchar(750) NOT NULL,
  `id_cliente` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id`, `fecha`, `efectivo`, `tarjeta`, `descripcion`, `id_cliente`) VALUES
(5, '2020-10-05', 50.00, 0.00, 'REVISIÓN DE CICATRIZACIÓN DE ENCÍA (DRA SOLEDAD) (S/50)', 5),
(62, '2020-10-07', 100.00, 0.00, 'APERTURA CAMERAL (2.5) Y PBM 1/2 (VER HISTORIA) (S/100 A CUENTA) (DR. ANDREE)', 60),
(150, '2020-10-12', 100.00, 0.00, 'TOMA DE MODELO (DOTORA SOLEDAD)S/100', 5),
(231, '2020-10-16', 50.00, 0.00, 'PRUEVA DE ENFILADO (DOCTORA SOLEDAD)S/50', 5),
(306, '2020-10-19', 50.00, 0.00, 'TOMA DE MODELO (DRA SOLEDAD) S/ 50', 5),
(418, '2020-10-24', 300.00, 0.00, 'SEMENTACION DE PUENTE DE 5 PIEZAS ( DRA SOLEDAD) S/ 300', 5),
(1500, '2020-12-14', 50.00, 0.00, 'UNA CURACION  PZ 2,6 (DOCTORA SOLEDAD) S/50', 5),
(1549, '2020-12-16', 0.00, 0.00, 'IMPRESION PARA REPARACION DRA. SOLEDAD', 5),
(1618, '2020-12-19', 230.00, 0.00, 'UNA CARRILLA Y ENTREGA DE REPARACION(DOCTORA SOLEDAD)/ 230', 5),
(3213, '2021-03-18', 100.00, 0.00, 'ABONO DE PUENTE DE IVOCRON ( DRA SOLEDAD) S/ 100', 5),
(3259, '2021-03-20', 0.00, 0.00, 'TOMA DE MODELO PRIMARIO ( DRA SOLEDAD) ', 5),
(3307, '2021-03-24', 50.00, 0.00, 'PRUEBA DE MORDIDA(DR, SOLEDAD)', 5),
(3366, '2021-03-27', 100.00, 0.00, 'CEMENTACION DE PUENTE(DR, SOLEDAD)', 5),
(3398, '2021-03-30', 30.00, 0.00, 'PAGO LO QUE RESTABA', 5),
(12916, '2022-08-04', 0.00, 300.00, 'APERTURA PZ. 2.5 (DR. KAREN)', 60),
(13227, '2022-08-19', 80.00, 0.00, 'TERMINO DE ENDO CON RESINA (DR. KAREN)', 60),
(15601, '2022-12-10', 50.00, 0.00, 'CURACION 2.3 ( DRA RUTH) S/ 50', 5),
(20212, '2023-08-17', 50.00, 0.00, 'CURACION 4.5 ( DRA RUTH) S/ 50', 5),
(21169, '2023-10-05', 50.00, 0.00, 'CURACION 2.3 ( DRA SOLEDAD) S/ 50', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `ape1` varchar(150) NOT NULL,
  `ape2` varchar(150) DEFAULT NULL,
  `nrodoc` varchar(50) DEFAULT NULL,
  `id_tipodoc` int(10) DEFAULT NULL,
  `fec_nac` date DEFAULT NULL,
  `telef` varchar(12) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `id_tipopersona` int(10) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id`, `nombre`, `ape1`, `ape2`, `nrodoc`, `id_tipodoc`, `fec_nac`, `telef`, `correo`, `direccion`, `id_tipopersona`, `fecha`) VALUES
(5, 'ISABEL', 'PAZ', 'QUICHCA', '06559067', 1, '0000-00-00', '995647478', '', '', 2, '2020-10-05'),
(60, 'MIGUEL', 'QUINTANA ', 'VASQUEZ', '09783119', 1, '0000-00-00', '943540966', '', 'MZ E LT14 VIV. 06 DE DICIEMBRE ATE.', 2, '2020-10-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id` int(10) NOT NULL,
  `horario` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id`, `horario`) VALUES
(1, 'Mañana'),
(2, 'Tarde'),
(3, 'Noche');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `horario` int(10) DEFAULT NULL,
  FOREIGN KEY (`horario`) REFERENCES `horario`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `precio`, `horario`) VALUES
(1, 'Coctel de vida', 100.00, NULL),
(2, 'agua destilada', 40.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_doc`
--

CREATE TABLE `tipo_doc` (
  `id` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipo_doc`
--

INSERT INTO `tipo_doc` (`id`, `nombre`) VALUES
(1, 'DNI'),
(2, 'Carnet de extranjería');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_persona`
--

CREATE TABLE `tipo_persona` (
  `id` int(10) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipo_persona`
--

INSERT INTO `tipo_persona` (`id`, `nombre`) VALUES
(1, 'doctor'),
(2, 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamiento`
--

CREATE TABLE `tratamiento` (
  `id` int(10) NOT NULL,
  `id_historia` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `tratamiento` varchar(750) NOT NULL,
  `cd` int(10) NOT NULL,
  `prox_cita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tratamiento`
--

INSERT INTO `tratamiento` (`id`, `id_historia`, `fecha`, `tratamiento`, `cd`, `prox_cita`) VALUES
(1, 2, '2020-10-07', 'APERTURA CAMERAL PIEZA 2.5 / PBM HASTA LIMA MAESTRA 15-20-25 / CV: 19 PIXI LT:18 MM / CP : 18 PIXI LT : 17 MM. COLOCADO BOLITA DE ALGODON Y EUGENOLATO', 0, '2020-10-12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `egreso`
--
ALTER TABLE `egreso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `exploracion_fisica`
--
ALTER TABLE `exploracion_fisica`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historia`
--
ALTER TABLE `historia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lista`
--
ALTER TABLE `lista`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logueo`
--
ALTER TABLE `logueo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `odontograma`
--
ALTER TABLE `odontograma`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_doc`
--
ALTER TABLE `tipo_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tratamiento`
--
ALTER TABLE `tratamiento`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `egreso`
--
ALTER TABLE `egreso`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `exploracion_fisica`
--
ALTER TABLE `exploracion_fisica`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historia`
--
ALTER TABLE `historia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `lista`
--
ALTER TABLE `lista`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `logueo`
--
ALTER TABLE `logueo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `odontograma`
--
ALTER TABLE `odontograma`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29885;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7981;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`