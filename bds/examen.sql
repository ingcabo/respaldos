/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.25-MariaDB : Database - examen
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`examen` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `examen`;

/*Table structure for table `cliente` */

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `id` decimal(10,0) DEFAULT NULL,
  `nombreapellido` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cliente` */

insert  into `cliente`(`id`,`nombreapellido`) values (1,'Anny barrios'),(2,'jineska'),(3,'Cezar'),(4,'pedro');

/*Table structure for table `producto` */

DROP TABLE IF EXISTS `producto`;

CREATE TABLE `producto` (
  `id` decimal(10,0) DEFAULT NULL,
  `descripcion` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `producto` */

insert  into `producto`(`id`,`descripcion`) values (1,'Crema Dental'),(2,'Pintura'),(3,'Bateria Carro'),(4,'empanadas'),(5,'pixzzas'),(6,'Cerveza'),(7,'Carbon'),(8,'Jeans');

/*Table structure for table `sucursal` */

DROP TABLE IF EXISTS `sucursal`;

CREATE TABLE `sucursal` (
  `id` decimal(10,0) DEFAULT NULL,
  `nombre` varchar(25) DEFAULT NULL,
  `iddireccion` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sucursal` */

insert  into `sucursal`(`id`,`nombre`,`iddireccion`) values (1,'SucursaL1','Direccion 1'),(2,'SucursaL2','Direccion 2'),(78,'SucursaL3','Direccion 3'),(4,'SucursaL4','Direccion 4');

/*Table structure for table `vendedor` */

DROP TABLE IF EXISTS `vendedor`;

CREATE TABLE `vendedor` (
  `id` decimal(10,0) DEFAULT NULL,
  `nombreapellido` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `vendedor` */

insert  into `vendedor`(`id`,`nombreapellido`) values (1,'DanielVillalobos'),(2,'CarlosRAmirez'),(3,'Toski'),(4,'Moroco');

/*Table structure for table `vendedorporsucursal` */

DROP TABLE IF EXISTS `vendedorporsucursal`;

CREATE TABLE `vendedorporsucursal` (
  `idvendedor` decimal(10,0) DEFAULT NULL,
  `idsucursal` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `vendedorporsucursal` */

insert  into `vendedorporsucursal`(`idvendedor`,`idsucursal`) values (1,1),(1,78),(2,4),(2,78),(4,1),(4,4);

/*Table structure for table `venta` */

DROP TABLE IF EXISTS `venta`;

CREATE TABLE `venta` (
  `id` decimal(10,0) DEFAULT NULL,
  `idvendedor` decimal(10,0) DEFAULT NULL,
  `idcliente` decimal(10,0) DEFAULT NULL,
  `fechahora` datetime DEFAULT NULL,
  `idsucursal` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venta` */

insert  into `venta`(`id`,`idvendedor`,`idcliente`,`fechahora`,`idsucursal`) values (1,1,1,'2017-12-14 06:12:22',1),(2,1,2,'2017-12-13 06:12:22',78),(3,2,3,'2017-12-13 06:12:22',2),(4,2,3,'2017-12-11 06:12:22',4),(5,4,3,'2017-12-14 06:12:22',1),(6,4,4,'2017-12-14 06:12:22',4),(7,2,4,'2017-12-10 06:56:25',78);

/*Table structure for table `ventaitem` */

DROP TABLE IF EXISTS `ventaitem`;

CREATE TABLE `ventaitem` (
  `idventa` decimal(10,0) DEFAULT NULL,
  `iditem` decimal(10,0) DEFAULT NULL,
  `cantidad` decimal(10,0) DEFAULT NULL,
  `idproducto` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ventaitem` */

insert  into `ventaitem`(`idventa`,`iditem`,`cantidad`,`idproducto`) values (1,1,3,1),(1,2,2,2),(2,1,2,4),(2,2,3,6),(3,1,3,1),(3,2,4,3),(4,1,4,3),(4,2,3,5),(6,1,1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
