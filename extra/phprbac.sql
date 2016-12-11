-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.5092
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for sakila
CREATE DATABASE IF NOT EXISTS `ci_rbac` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ci_rbac`;

-- Dumping structure for table sakila.phprbac_permissions
CREATE TABLE IF NOT EXISTS `phprbac_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` char(64) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table sakila.phprbac_permissions: ~6 rows (approximately)
DELETE FROM `phprbac_permissions`;
/*!40000 ALTER TABLE `phprbac_permissions` DISABLE KEYS */;
INSERT INTO `phprbac_permissions` (`ID`, `Lft`, `Rght`, `Title`, `Description`) VALUES
	(1, 0, 23, 'root', 'root'),
	(10, 7, 14, 'Project Manage', ''),
	(11, 8, 9, 'Create Project', ''),
	(12, 10, 11, 'Update Project', ''),
	(13, 12, 13, 'Cancel Project', ''),
	(14, 15, 22, 'Group Manage', ''),
	(15, 16, 17, 'Add Group', ''),
	(16, 18, 19, 'Modify Group', ''),
	(17, 20, 21, 'Delete Group', '');
/*!40000 ALTER TABLE `phprbac_permissions` ENABLE KEYS */;

-- Dumping structure for table sakila.phprbac_rolepermissions
CREATE TABLE IF NOT EXISTS `phprbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL COMMENT '分配日期',
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table sakila.phprbac_rolepermissions: ~0 rows (approximately)
DELETE FROM `phprbac_rolepermissions`;
/*!40000 ALTER TABLE `phprbac_rolepermissions` DISABLE KEYS */;
INSERT INTO `phprbac_rolepermissions` (`RoleID`, `PermissionID`, `AssignmentDate`) VALUES
	(1, 1, 0),
	(29, 10, 1481366738),
	(29, 14, 1481365258);
/*!40000 ALTER TABLE `phprbac_rolepermissions` ENABLE KEYS */;

-- Dumping structure for table sakila.phprbac_roles
CREATE TABLE IF NOT EXISTS `phprbac_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` varchar(128) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`),
  KEY `Title` (`Title`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table sakila.phprbac_roles: ~5 rows (approximately)
DELETE FROM `phprbac_roles`;
/*!40000 ALTER TABLE `phprbac_roles` DISABLE KEYS */;
INSERT INTO `phprbac_roles` (`ID`, `Lft`, `Rght`, `Title`, `Description`) VALUES
	(1, 0, 61, 'root', 'root'),
	(29, 43, 60, 'Global Admin', ''),
	(30, 44, 49, 'Platform1 Admin', ''),
	(31, 50, 51, 'Platform2 Admin', ''),
	(32, 52, 53, 'Platform3 Admin', ''),
	(33, 54, 55, 'Platform4 Admin', ''),
	(34, 56, 57, 'Platform5 Admin', ''),
	(35, 45, 46, 'Project Admin', ''),
	(36, 47, 48, 'User Manager', ''),
	(37, 58, 59, 'Group Leader', '');
/*!40000 ALTER TABLE `phprbac_roles` ENABLE KEYS */;

-- Dumping structure for table sakila.phprbac_userroles
CREATE TABLE IF NOT EXISTS `phprbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table sakila.phprbac_userroles: ~0 rows (approximately)
DELETE FROM `phprbac_userroles`;
/*!40000 ALTER TABLE `phprbac_userroles` DISABLE KEYS */;
/*!40000 ALTER TABLE `phprbac_userroles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
