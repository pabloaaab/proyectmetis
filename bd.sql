/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.1.33-MariaDB : Database - bdproyectmetis
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`bdproyectmetis` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `bdproyectmetis`;

/*Table structure for table `reporte` */

DROP TABLE IF EXISTS `reporte`;

CREATE TABLE `reporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inc_recibida_p_d_a` int(11) DEFAULT '0',
  `inc_recibida` int(11) DEFAULT '0',
  `inc_recibida_g` int(11) DEFAULT '0',
  `inc_recibida_p_f_d` int(11) DEFAULT '0',
  `inc_dev_p_d_a` int(11) DEFAULT '0',
  `inc_dev_recibida` int(11) DEFAULT '0',
  `inc_dev_g` int(11) DEFAULT '0',
  `inc_dev_p_f_d` int(11) DEFAULT '0',
  `rep_inc_p_d_a` int(11) DEFAULT '0',
  `rep_inc_recibida` int(11) DEFAULT '0',
  `rep_inc_g` int(11) DEFAULT '0',
  `rep_inc_p_f_d` int(11) DEFAULT '0',
  `rep_inc_dev_p_d_a` int(11) DEFAULT '0',
  `rep_inc_dev_recibida` int(11) DEFAULT '0',
  `rep_inc_dev_g` int(11) DEFAULT '0',
  `rep_inc_dev_p_f_d` int(11) DEFAULT '0',
  `fecha_gestion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `reporte` */

insert  into `reporte`(`id`,`inc_recibida_p_d_a`,`inc_recibida`,`inc_recibida_g`,`inc_recibida_p_f_d`,`inc_dev_p_d_a`,`inc_dev_recibida`,`inc_dev_g`,`inc_dev_p_f_d`,`rep_inc_p_d_a`,`rep_inc_recibida`,`rep_inc_g`,`rep_inc_p_f_d`,`rep_inc_dev_p_d_a`,`rep_inc_dev_recibida`,`rep_inc_dev_g`,`rep_inc_dev_p_f_d`,`fecha_gestion`) values 
(27,22,21,10,20,8,9,8,8,8,5,4,5,5,4,5,4,'2019-06-01 19:49:06'),
(28,21,33,33,33,21,12,12,12,5,5,4,3,2,4,3,3,'2019-06-02 00:00:01'),
(29,15,12,12,12,12,2,2,3,4,4,4,4,4,4,4,4,'2019-06-03 00:00:01');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(240) COLLATE utf8_spanish_ci DEFAULT NULL,
  `password` varchar(750) COLLATE utf8_spanish_ci DEFAULT NULL,
  `authKey` varchar(750) COLLATE utf8_spanish_ci DEFAULT NULL,
  `accessToken` varchar(750) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activate` tinyint(1) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `nombrecompleto` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sede` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechacreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `perfil` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`authKey`,`accessToken`,`activate`,`role`,`nombrecompleto`,`sede`,`fechacreacion`,`perfil`) values 
(1,'123','admin@hotmail.com','fs3C.b5vVSGZU','6663129842c454d69a93f4975b4cdb9d140581b034a55c7c49fa8367035c31e9ef5f34b3e98de0c5971202ed4293ca3569c38c96827b96827a5a9b4b3efedfe4b68c26ab2c4c09928929aee88957f1b7a83712522ba7c6dd7504db01dc9abf62848a01ab','7b620f13e7170411f4e77506b8b2c9722fcf74ca849c99cd5660443b5e05be427803cc2db7011132bc1e72a69baecc6c897e6bf2913b56883208c7ced6b3e85f2c1d76920b9d1f3af4b1f3505a0c93aedbc53c65644a78a81d2a68c658d5ad2b1dcc25f6',1,2,'Administrador','Medellin','2019-01-06 22:41:18','Administrador'),
(2,'456','usuario@hotmail.com','fsxaMHkbWl0Gk','cb24d535a60b73e3768308359411e00a531f62f05d54a04dfd674a4d2848a712f343996e6bec60f5703fe06b4a2c0763fe3ea082234aa22197c02f94b792ae43fed5da9a948827559d5e3cf5895cd0b8625c8835f4f3f2a0b9671cf80d2f90b78a618e23','1a6b05a765d75262e9c6b01e12a5d575e1e47f1ebb60cec18ddf27c11d9575f1622cadbfb39c2f6369b0c39602c4d41d895a82022d8a3bd58c33d7bdb7d93f388c9c9c0abb9a2c7a5e9bcd0de1d80866d392df8fcc68bfdd9e2b3ab3a05c3a5bbf556092',1,2,'Usuario','MEDELLIN','2019-01-06 23:48:32','Administrador');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
