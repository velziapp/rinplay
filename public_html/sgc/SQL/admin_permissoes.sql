# MySQL-Front 3.2  (Build 6.11)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES 'latin1' */;

# Host: fenix    Database: db_admin
# ------------------------------------------------------
# Server version 5.1.48-community

#
# Table structure for table admin_permissoes
#

CREATE TABLE `rp_admin_permissoes` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_admin` int(11) NOT NULL DEFAULT '0',
  `codigo_modulo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

#
# Dumping data for table admin_permissoes
#

INSERT INTO `rp_admin_permissoes` VALUES (20,4,13);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
