-- 1.- Creamos la Base de Datos
create database tarea8 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Seleccionamos la base de datos "proyectoexamen"
use tarea8;


-- 2.- Creamos las tablas
-- 2.1.1.- Tabla tienda


-- 2.1.2 .- Tabla familia
create table if not exists familias(
cod varchar(6) primary key,
nombre varchar(200) not null
);


-- 2.1.3.- Tabla producto
create table if not exists productos(
id int auto_increment primary key,
nombre varchar(200) not null,
nombre_corto varchar(50) unique not null,
descripcion text null,
pvp decimal(10, 2) not null,
familia varchar(6) not null,
constraint fk_prod_fam foreign key(familia) references familias(cod) on update
cascade on delete cascade
);


-- 3.- Creamos un usuario
-- create user gestor@'localhost' identified by "secreto";

-- 4.- Le damos permiso en la base de datos "proyectoexamen"
grant all on tarea8.* to gestor@'localhost';