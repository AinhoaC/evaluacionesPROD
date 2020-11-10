-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-09-2020 a las 16:00:24
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `evaluacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `idCategoria` int(11) NOT NULL,
  `tipoCategoria` varchar(5) NOT NULL,
  `desCategoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`idCategoria`, `tipoCategoria`, `desCategoria`) VALUES
(1, 'A', 'Visión estartégica de negocio'),
(2, 'B', 'Orientación al cliente'),
(3, 'C', 'Networking'),
(4, 'D', 'Negociación'),
(5, 'E', 'Comunicación'),
(6, 'F', 'Delegación'),
(7, 'G', 'Dirección de equipos'),
(8, 'H', 'Gestión del talento'),
(9, 'I', 'Análisis de problemas y Toma de decisiones'),
(10, 'J', 'Gestion del tiempo y Priorización'),
(11, 'K', 'Adaptación al cambio y Aprendizaje'),
(12, 'L', 'Autocontrol y Equilibrio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `idEmpresa` int(11) NOT NULL,
  `nombreEmpresa` varchar(50) NOT NULL,
  `fechaDesde` date DEFAULT NULL,
  `fechaHasta` date DEFAULT NULL,
  `rutaXLS` varchar(150) DEFAULT NULL,
  `rutaPDF` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`idEmpresa`, `nombreEmpresa`, `fechaDesde`, `fechaHasta`, `rutaXLS`, `rutaPDF`) VALUES
(1, 'Empresa prueba1', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `idEvaluacion` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `idPersona` int(11) NOT NULL,
  `nombrePers` varchar(50) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `idEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`idPersona`, `nombrePers`, `rol`, `username`, `password`, `email`, `idEmpresa`) VALUES
(1, 'Persona Pureba1', 'Rol Prueba', 'usuprueba', '1234', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `idPregunta` int(11) NOT NULL,
  `categoria` varchar(5) DEFAULT NULL,
  `descripcion` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`idPregunta`, `categoria`, `descripcion`) VALUES
(1, 'A.1', 'Conoce bien los objetivos y prioridades de la empresa.'),
(2, 'B.1', 'Conoce las diferentes tipológias de cliente y se adapta a sus características.'),
(3, 'C.1', 'Muestra capacidad para intuir los contactos que aportan valor a la empresa.'),
(4, 'D.1', 'Planifica adecuadamente los procesos de negociación.'),
(5, 'E.1', 'Sabe escuchar a su interlocutor sin interrumpirle.'),
(6, 'F.1', 'Conoce bien las capacidades de cada miembro del equipo.'),
(7, 'G.1', 'Orienta al equipo hacia la consecución de objetivos.'),
(8, 'H.1', 'Dedica tiempo a desarrollar el potencial de cada uno de sus colaboradores.'),
(9, 'I.1', 'Muestra capacidad para intuir y anticiparse a los problemas.'),
(10, 'J.1', 'Sabe distinguir lo urgente de lo importante.'),
(11, 'K.1', 'Tiene capacidad para incorporar nuevos procedimientos.'),
(12, 'L.1', 'Mantiene la serenidad en situaciones de tensiÃ³n.'),
(13, 'A.4', 'Se anticipa a los riesgos del negocio.'),
(14, 'B.4', 'Sabe gestionar adecuadamente las reclamaciones o quejas de sus clientes.'),
(15, 'C.4', 'Dedica tiempo a cultivar y fortalecer contactos profesionales.'),
(16, 'D.4', 'Sabe cerrar una negociaciÃ³n en el momento oportuno.'),
(17, 'E.4', 'Maneja el lenguaje no verbal con naturalidad: gestos, pausas, etc.'),
(18, 'F.4', 'Realiza un buen seguimiento de las tareas delegadas.'),
(19, 'G.4', 'Sabe motivar e ilusionar a los miembros del equipo frente a las dificultades.'),
(20, 'H.4', 'Se preocupa de la formaciÃ³n y crecimiento de sus colaboradores.'),
(21, 'I.4', 'Toma decisiones en el momento oportuno.'),
(22, 'J.4', 'Es puntual en las citas y con sus compromisos.'),
(23, 'K.4', 'Se rehace con rapidez cuando sufre una contrariedad.'),
(24, 'L.4', 'Sabe rectificar despuÃ©s de un error.'),
(25, 'A.3', 'Analiza el entorno para aprovechar las oportunidades de negocio.'),
(26, 'B.3', 'Tiene capacidad para fidelizar a sus clientes.'),
(27, 'C.3', 'Tiene capacidad para mantener una red de contactos extensa.'),
(28, 'D.3', 'Tiene capacidad para hacerse cargo de las necesidades de su interlocutor.'),
(29, 'E.3', 'Comunica de forma clara e inequÃ­vioca.'),
(30, 'F.3', 'Sigue un buen mÃ©todo para delegar.'),
(31, 'G.3', 'Logra el consenso e integraciÃ³n con todos los miembros del equipo.'),
(32, 'H.3', 'Motiva al desarrollo a sus colaboradores.'),
(33, 'I.3', 'Llega a la raÃ­z o nÃºcleo del problema.'),
(34, 'J.3', 'Se deja llevar por los ladrones de tiempo.'),
(35, 'K.3', 'Dedica tiempo al aprendizaje y a su propia mejora.'),
(36, 'L.3', 'Es concilidor y ecuÃ¡nime en las situaciones de conflicto.'),
(37, 'A.2', 'Se mantiene al dÃ­a sobre las mejores prÃ¡cticas del mercado y del sector.'),
(38, 'B.2', 'Sabe ganarse la confianza con sus clientes.'),
(39, 'C.2', 'Tiene una sociabilidad espontÃ¡nea que le facilita establecer contacto de forma rÃ¡pida.'),
(40, 'D.2', 'Muestra una actitud abierta y positiva en los procesos de negociaciÃ³n.'),
(41, 'E.2', 'Se hace cargo de la situaciÃ³n de su interlocutor.'),
(42, 'F.2', 'Dedica tiempo y esfuerzo a delegar todo lo que puede.'),
(43, 'G.2', 'Sabe dirigir a personas que muestran un perfil diferente.'),
(44, 'H.2', 'Conoce los intereses y motivaciones de sus colaboradores.'),
(45, 'I.2', 'Es capaz de analizar una situaciÃ³n desde diferentes perspectivas.'),
(46, 'J.2', 'Dedica su mayor esfuerzo a lo importante.'),
(47, 'K.2', 'Se muestra abierto frente a las nuevas sirtuaciones o circunstancias.'),
(48, 'L.2', 'Muestra equilibrio en situaciones difÃ­ciles.'),
(49, 'A.5', 'Conoce la cadena de valor de la empresa y cÃ³mo mejorarla.'),
(50, 'B.5', 'Se adelanta a las necesidades de sus clientes.'),
(51, 'C.5', 'Es capaz de rentabilizar su red de contactos.'),
(52, 'D.5', 'Logra sus objetivos sin daÃ±ar la relaciÃ³n con la otra parte negociadora.'),
(53, 'E.5', 'Expresa sus ideas sin heriri a su interlocutor.'),
(54, 'F.5', 'Reta a su equipo para que crezcan en sus capacidades y responsabilidades.'),
(55, 'G.5', 'Impulsa la colaboraciÃ³n entre todos los miembros del equipo.'),
(56, 'H.5', 'Tiene habilidad para proponer planes de mejora claros y sencillos.'),
(57, 'I.5', 'Hace un seguimiento adecuado de los acuerdos adoptados.'),
(58, 'J.5', 'Realiza un planificaciÃ³n realista y exigente de sus metas.'),
(59, 'K.5', 'Es persistente en sus metas y objetivos.'),
(60, 'L.5', 'Genera un clima positivo en su entorno.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`idEmpresa`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`idEvaluacion`),
  ADD KEY `idPersona` (`idPersona`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`idPersona`),
  ADD KEY `idEmpresa` (`idEmpresa`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`idPregunta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `idEmpresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `idEvaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `idPersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `idPregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`idEmpresa`) REFERENCES `empresas` (`idEmpresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
