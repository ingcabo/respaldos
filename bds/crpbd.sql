/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.25-MariaDB : Database - crpbd
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`crpbd` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `crpbd`;

/*Table structure for table `exchangerequest` */

DROP TABLE IF EXISTS `exchangerequest`;

CREATE TABLE `exchangerequest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(120) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `method` varchar(120) DEFAULT NULL,
  `parameters` varchar(120) DEFAULT NULL,
  `idexchange` int(11) NOT NULL,
  `all` varchar(120) DEFAULT NULL,
  `example` varchar(255) DEFAULT NULL,
  `payment` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`idexchange`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `exchangerequest` */

insert  into `exchangerequest`(`id`,`description`,`url`,`method`,`parameters`,`idexchange`,`all`,`example`,`payment`,`active`) values (2,'The ticker is a high level overview of the state of the market. It shows you the current best bid and ask, as well as th','/pubticker/','get','simbolo',1,NULL,'https://api.bitfinex.com/v1/pubticker/:symbol',0,1);

/*Table structure for table `exchanges` */

DROP TABLE IF EXISTS `exchanges`;

CREATE TABLE `exchanges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `url` varchar(20) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`name`,`symbol`,`url`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `exchanges` */

insert  into `exchanges`(`id`,`name`,`symbol`,`url`,`info`) values (1,'bitfinex','bf','https://api.bitfinex','Parameters');

/*Table structure for table `volumen` */

DROP TABLE IF EXISTS `volumen`;

CREATE TABLE `volumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(50) DEFAULT NULL,
  `volumen` varchar(120) DEFAULT NULL,
  `openvalue` varchar(120) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `pair` varchar(60) DEFAULT NULL,
  `closevalue` varchar(120) DEFAULT NULL,
  `price` varchar(120) DEFAULT NULL,
  `sellvolumen` varchar(120) DEFAULT NULL,
  `buyvolumen` varchar(120) DEFAULT NULL,
  `cantsell` varchar(120) DEFAULT NULL,
  `cantbuy` varchar(120) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `volumen` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
