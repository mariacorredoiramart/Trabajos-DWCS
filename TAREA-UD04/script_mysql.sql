DROP DATABASE IF EXISTS proyectomariacorredoira;
CREATE DATABASE proyectomariacorredoira;

use proyectomariacorredoira;

CREATE TABLE `proyectomariacorredoira`.`familias` (
  `id` INT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `familias` (`id`, `nombre`) VALUES ('1', 'Cámaras digitales');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('2', 'Consolas');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('3', 'Equipos multifución');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('4', 'Impresoras');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('5', 'Libros electrónicos');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('6', 'Memoria flash');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('7', 'Netbooks');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('8', 'Ordenadores');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('9', 'Ordenadores portátiles');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('10', 'Reproductores MP3');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('11', 'Routers');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('12', 'Sistemas de alimentación ininterrumpida');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('13', 'Software');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('14', 'Televisores');
INSERT INTO `familias` (`id`, `nombre`) VALUES ('15', 'Videocámaras');

CREATE TABLE `proyectomariacorredoira`.`productos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_completo` VARCHAR(150) NOT NULL,
  `nombre_corto` VARCHAR(45) NOT NULL,
  `descripcion` LONGTEXT NOT NULL,
  `precio` DOUBLE NOT NULL,
  `familia` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `familia_fk_idx` (`familia` ASC),
  CONSTRAINT `familia_fk`
    FOREIGN KEY (`familia`)
    REFERENCES `proyectomariacorredoira`.`familias` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
