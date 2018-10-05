-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table bungdav.kurir_jobs
CREATE TABLE IF NOT EXISTS `kurir_jobs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TransactionNumber` varchar(100) DEFAULT '0',
  `KurirID` varchar(100) DEFAULT '0',
  `StatusKirim` tinyint(1) DEFAULT '0' COMMENT '0=> ''On Delivery''; 1=> ''Success''; 2=>''Return''; ',
  `Photos` varchar(255) DEFAULT NULL,
  `Notes` text,
  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>''OK'';1=>''Deleted'';',
  `Created_date` timestamp NULL DEFAULT NULL,
  `Created_by` int(11) DEFAULT NULL,
  `Update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bungdav.kurir_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `kurir_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `kurir_jobs` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
