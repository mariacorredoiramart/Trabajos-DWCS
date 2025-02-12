CREATE DATABASE prueba;
USE prueba;

-- Creación de la tabla de usuarios
CREATE TABLE usuarios (
    usuario VARCHAR(20) PRIMARY KEY,   
    contraseña VARCHAR(100)                                     
);

-- Creación de la tabla familias
CREATE TABLE familias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

-- Creación de la tabla productos (necesita familias)
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(150) NOT NULL,
  nombre_corto VARCHAR(45) NOT NULL,
  precio DOUBLE NOT NULL,
  descripcion LONGTEXT NOT NULL,
  familia INT NOT NULL,
  CONSTRAINT familia_fk FOREIGN KEY (familia) REFERENCES familias(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creación de la tabla tiendas
CREATE TABLE tiendas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  telefono VARCHAR(100) NOT NULL
);

-- Creación de la tabla stock (después de tiendas y productos)
CREATE TABLE stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tienda INT,
  producto INT,
  unidades INT,
  CONSTRAINT tienda_FK FOREIGN KEY (tienda) REFERENCES tiendas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT producto_FK FOREIGN KEY (producto) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creación de la tabla votos (después de productos y usuarios)
CREATE TABLE votos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cantidad INT DEFAULT 0,
  idPr INT NOT NULL,
  idUs VARCHAR(20) NOT NULL,
  CONSTRAINT fk_votos_usu FOREIGN KEY (idUs) REFERENCES usuarios(usuario) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_votos_pro FOREIGN KEY (idPr) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insertar usuarios
INSERT INTO usuarios VALUES('usuario1', SHA2('pusuario1', 256));
INSERT INTO usuarios VALUES('usuario2', SHA2('pusuario2', 256));
INSERT INTO usuarios VALUES('usuario3', SHA2('pusuario3', 256));
INSERT INTO usuarios VALUES('usuario4', SHA2('pusuario4', 256));

-- Otorgar permisos a gestor
GRANT ALL PRIVILEGES ON prueba.* TO 'gestor'@'localhost';
