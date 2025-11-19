-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 01:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taller_scrum`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `rol_usuario` varchar(45) NOT NULL,
  `correo_admin` varchar(100) NOT NULL,
  `password_admin` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`id_admin`, `rol_usuario`, `correo_admin`, `password_admin`) VALUES
(1, 'Administrador', 'admin@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `aprendices`
--

CREATE TABLE `aprendices` (
  `id_aprendiz` int(11) NOT NULL,
  `rol_usuario` varchar(45) NOT NULL,
  `correo_aprendiz` varchar(100) NOT NULL,
  `password_aprendiz` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos_has_aprendices`
--

CREATE TABLE `cursos_has_aprendices` (
  `cursos_id_curso` int(11) NOT NULL,
  `aprendices_id_aprendiz` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fichas`
--

CREATE TABLE `fichas` (
  `id_ficha` int(11) NOT NULL,
  `nombre_ficha` varchar(45) NOT NULL,
  `aprendices_id_aprendiz` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `id_instructor` int(11) NOT NULL,
  `rol_usuario` varchar(45) NOT NULL,
  `correo_instructor` varchar(100) NOT NULL,
  `password_instructor` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor_has_cursos`
--

CREATE TABLE `instructor_has_cursos` (
  `instructor_id_usuario` int(11) NOT NULL,
  `cursos_id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor_has_fichas`
--

CREATE TABLE `instructor_has_fichas` (
  `instructor_id_instructor` int(11) NOT NULL,
  `fichas_id_ficha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notas`
--

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL,
  `calificacion_nota` int(11) NOT NULL,
  `comentario_nota` varchar(155) NOT NULL,
  `trabajos_id_trabajo` int(11) NOT NULL,
  `instructor_id_instructor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trabajos`
--

CREATE TABLE `trabajos` (
  `id_trabajo` int(11) NOT NULL,
  `nombre_trabajo` varchar(155) NOT NULL,
  `fecha_trabajo` date NOT NULL,
  `aprendices_id_aprendiz` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `correo_usuario_UNIQUE` (`correo_admin`);

--
-- Indexes for table `aprendices`
--
ALTER TABLE `aprendices`
  ADD PRIMARY KEY (`id_aprendiz`),
  ADD UNIQUE KEY `correo_usuario_UNIQUE` (`correo_aprendiz`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indexes for table `cursos_has_aprendices`
--
ALTER TABLE `cursos_has_aprendices`
  ADD PRIMARY KEY (`cursos_id_curso`,`aprendices_id_aprendiz`),
  ADD KEY `fk_cursos_has_aprendices_aprendices1_idx` (`aprendices_id_aprendiz`),
  ADD KEY `fk_cursos_has_aprendices_cursos1_idx` (`cursos_id_curso`);

--
-- Indexes for table `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id_ficha`),
  ADD KEY `fk_fichas_aprendices1_idx` (`aprendices_id_aprendiz`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`id_instructor`),
  ADD UNIQUE KEY `correo_usuario_UNIQUE` (`correo_instructor`);

--
-- Indexes for table `instructor_has_cursos`
--
ALTER TABLE `instructor_has_cursos`
  ADD PRIMARY KEY (`instructor_id_usuario`,`cursos_id_curso`),
  ADD KEY `fk_instructor_has_cursos_cursos1_idx` (`cursos_id_curso`),
  ADD KEY `fk_instructor_has_cursos_instructor1_idx` (`instructor_id_usuario`);

--
-- Indexes for table `instructor_has_fichas`
--
ALTER TABLE `instructor_has_fichas`
  ADD PRIMARY KEY (`instructor_id_instructor`,`fichas_id_ficha`),
  ADD KEY `fk_instructor_has_fichas_fichas1_idx` (`fichas_id_ficha`),
  ADD KEY `fk_instructor_has_fichas_instructor1_idx` (`instructor_id_instructor`);

--
-- Indexes for table `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `fk_notas_trabajos1_idx` (`trabajos_id_trabajo`),
  ADD KEY `fk_notas_instructor1_idx` (`instructor_id_instructor`);

--
-- Indexes for table `trabajos`
--
ALTER TABLE `trabajos`
  ADD PRIMARY KEY (`id_trabajo`),
  ADD KEY `fk_trabajos_aprendices1_idx` (`aprendices_id_aprendiz`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aprendices`
--
ALTER TABLE `aprendices`
  MODIFY `id_aprendiz` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id_ficha` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id_instructor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trabajos`
--
ALTER TABLE `trabajos`
  MODIFY `id_trabajo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cursos_has_aprendices`
--
ALTER TABLE `cursos_has_aprendices`
  ADD CONSTRAINT `fk_cursos_has_aprendices_aprendices1` FOREIGN KEY (`aprendices_id_aprendiz`) REFERENCES `aprendices` (`id_aprendiz`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cursos_has_aprendices_cursos1` FOREIGN KEY (`cursos_id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fichas`
--
ALTER TABLE `fichas`
  ADD CONSTRAINT `fk_fichas_aprendices1` FOREIGN KEY (`aprendices_id_aprendiz`) REFERENCES `aprendices` (`id_aprendiz`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `instructor_has_cursos`
--
ALTER TABLE `instructor_has_cursos`
  ADD CONSTRAINT `fk_instructor_has_cursos_cursos1` FOREIGN KEY (`cursos_id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_instructor_has_cursos_instructor1` FOREIGN KEY (`instructor_id_usuario`) REFERENCES `instructor` (`id_instructor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `instructor_has_fichas`
--
ALTER TABLE `instructor_has_fichas`
  ADD CONSTRAINT `fk_instructor_has_fichas_fichas1` FOREIGN KEY (`fichas_id_ficha`) REFERENCES `fichas` (`id_ficha`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_instructor_has_fichas_instructor1` FOREIGN KEY (`instructor_id_instructor`) REFERENCES `instructor` (`id_instructor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `fk_notas_instructor1` FOREIGN KEY (`instructor_id_instructor`) REFERENCES `instructor` (`id_instructor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notas_trabajos1` FOREIGN KEY (`trabajos_id_trabajo`) REFERENCES `trabajos` (`id_trabajo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `trabajos`
--
ALTER TABLE `trabajos`
  ADD CONSTRAINT `fk_trabajos_aprendices1` FOREIGN KEY (`aprendices_id_aprendiz`) REFERENCES `aprendices` (`id_aprendiz`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
