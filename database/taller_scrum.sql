-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema taller_scrum
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema taller_scrum
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `taller_scrum` DEFAULT CHARACTER SET utf8 ;
USE `taller_scrum` ;

-- -----------------------------------------------------
-- Table `taller_scrum`.`instructor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`instructor` (
  `id_instructor` INT NOT NULL AUTO_INCREMENT,
  `rol_usuario` VARCHAR(45) NOT NULL,
  `correo_instructor` VARCHAR(100) NOT NULL,
  `password_instructor` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id_instructor`),
  UNIQUE INDEX `correo_usuario_UNIQUE` (`correo_instructor` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`cursos` (
  `id_curso` INT NOT NULL AUTO_INCREMENT,
  `nombre_curso` VARCHAR(155) NOT NULL,
  PRIMARY KEY (`id_curso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`aprendices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`aprendices` (
  `id_aprendiz` INT NOT NULL AUTO_INCREMENT,
  `rol_usuario` VARCHAR(45) NOT NULL,
  `correo_aprendiz` VARCHAR(100) NOT NULL,
  `password_aprendiz` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id_aprendiz`),
  UNIQUE INDEX `correo_usuario_UNIQUE` (`correo_aprendiz` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`trabajos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`trabajos` (
  `id_trabajo` INT NOT NULL AUTO_INCREMENT,
  `nombre_trabajo` VARCHAR(155) NOT NULL,
  `fecha_trabajo` DATE NOT NULL,
  `aprendices_id_aprendiz` INT NOT NULL,
  PRIMARY KEY (`id_trabajo`),
  INDEX `fk_trabajos_aprendices1_idx` (`aprendices_id_aprendiz` ASC),
  CONSTRAINT `fk_trabajos_aprendices1`
    FOREIGN KEY (`aprendices_id_aprendiz`)
    REFERENCES `taller_scrum`.`aprendices` (`id_aprendiz`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`notas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`notas` (
  `id_nota` INT NOT NULL AUTO_INCREMENT,
  `calificacion_nota` INT NOT NULL,
  `comentario_nota` VARCHAR(155) NOT NULL,
  `trabajos_id_trabajo` INT NOT NULL,
  `instructor_id_instructor` INT NOT NULL,
  PRIMARY KEY (`id_nota`),
  INDEX `fk_notas_trabajos1_idx` (`trabajos_id_trabajo` ASC),
  INDEX `fk_notas_instructor1_idx` (`instructor_id_instructor` ASC),
  CONSTRAINT `fk_notas_trabajos1`
    FOREIGN KEY (`trabajos_id_trabajo`)
    REFERENCES `taller_scrum`.`trabajos` (`id_trabajo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_notas_instructor1`
    FOREIGN KEY (`instructor_id_instructor`)
    REFERENCES `taller_scrum`.`instructor` (`id_instructor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`instructor_has_cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`instructor_has_cursos` (
  `instructor_id_usuario` INT NOT NULL,
  `cursos_id_curso` INT NOT NULL,
  PRIMARY KEY (`instructor_id_usuario`, `cursos_id_curso`),
  INDEX `fk_instructor_has_cursos_cursos1_idx` (`cursos_id_curso` ASC),
  INDEX `fk_instructor_has_cursos_instructor1_idx` (`instructor_id_usuario` ASC),
  CONSTRAINT `fk_instructor_has_cursos_instructor1`
    FOREIGN KEY (`instructor_id_usuario`)
    REFERENCES `taller_scrum`.`instructor` (`id_instructor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_instructor_has_cursos_cursos1`
    FOREIGN KEY (`cursos_id_curso`)
    REFERENCES `taller_scrum`.`cursos` (`id_curso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`administrador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`administrador` (
  `id_admin` INT NOT NULL AUTO_INCREMENT,
  `rol_usuario` VARCHAR(45) NOT NULL,
  `correo_admin` VARCHAR(100) NOT NULL,
  `password_admin` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE INDEX `correo_usuario_UNIQUE` (`correo_admin` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`fichas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`fichas` (
  `id_ficha` INT NOT NULL AUTO_INCREMENT,
  `nombre_ficha` VARCHAR(45) NOT NULL,
  `aprendices_id_aprendiz` INT NOT NULL,
  PRIMARY KEY (`id_ficha`),
  INDEX `fk_fichas_aprendices1_idx` (`aprendices_id_aprendiz` ASC),
  CONSTRAINT `fk_fichas_aprendices1`
    FOREIGN KEY (`aprendices_id_aprendiz`)
    REFERENCES `taller_scrum`.`aprendices` (`id_aprendiz`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`cursos_has_aprendices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`cursos_has_aprendices` (
  `cursos_id_curso` INT NOT NULL,
  `aprendices_id_aprendiz` INT NOT NULL,
  PRIMARY KEY (`cursos_id_curso`, `aprendices_id_aprendiz`),
  INDEX `fk_cursos_has_aprendices_aprendices1_idx` (`aprendices_id_aprendiz` ASC),
  INDEX `fk_cursos_has_aprendices_cursos1_idx` (`cursos_id_curso` ASC),
  CONSTRAINT `fk_cursos_has_aprendices_cursos1`
    FOREIGN KEY (`cursos_id_curso`)
    REFERENCES `taller_scrum`.`cursos` (`id_curso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cursos_has_aprendices_aprendices1`
    FOREIGN KEY (`aprendices_id_aprendiz`)
    REFERENCES `taller_scrum`.`aprendices` (`id_aprendiz`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taller_scrum`.`instructor_has_fichas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taller_scrum`.`instructor_has_fichas` (
  `instructor_id_instructor` INT NOT NULL,
  `fichas_id_ficha` INT NOT NULL,
  PRIMARY KEY (`instructor_id_instructor`, `fichas_id_ficha`),
  INDEX `fk_instructor_has_fichas_fichas1_idx` (`fichas_id_ficha` ASC),
  INDEX `fk_instructor_has_fichas_instructor1_idx` (`instructor_id_instructor` ASC),
  CONSTRAINT `fk_instructor_has_fichas_instructor1`
    FOREIGN KEY (`instructor_id_instructor`)
    REFERENCES `taller_scrum`.`instructor` (`id_instructor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_instructor_has_fichas_fichas1`
    FOREIGN KEY (`fichas_id_ficha`)
    REFERENCES `taller_scrum`.`fichas` (`id_ficha`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
