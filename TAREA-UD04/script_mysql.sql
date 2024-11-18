CREATE DATABASE proyecto;

CREATE TABLE `proyecto`.`familias` (
  `id` INT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('1', 'Cámaras digitales');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('2', 'Consolas');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('3', 'Equipos multifución');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('4', 'Impresoras');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('5', 'Libros electrónicos');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('6', 'Memoria flash');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('7', 'Netbooks');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('8', 'Ordenadores');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('9', 'Ordenadores portátiles');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('10', 'Reproductores MP3');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('11', 'Routers');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('12', 'Sistemas de alimentación ininterrumpida');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('13', 'Software');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('14', 'Televisores');
INSERT INTO `proyecto`.`familias` (`id`, `nombre`) VALUES ('15', 'Videocámaras');

CREATE TABLE `proyecto`.`productos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_completo` VARCHAR(150) NOT NULL,
  `nombre_corto` VARCHAR(45) NOT NULL,
  `precio` DOUBLE NOT NULL,
  `descripcion` LONGTEXT NOT NULL,
  `familia` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `familia_fk_idx` (`familia` ASC),
  CONSTRAINT `familia_fk`
    FOREIGN KEY (`familia`)
    REFERENCES `proyecto`.`familias` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
