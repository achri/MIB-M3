-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2010 at 11:18 AM
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_m3_master_ooo`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `session_start` int(10) unsigned NOT NULL DEFAULT '0',
  `session_last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `session_ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `session_user_agent` varchar(50) CHARACTER SET latin1 NOT NULL,
  `session_data` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `session_start`, `session_last_activity`, `session_ip_address`, `session_user_agent`, `session_data`) VALUES
('354232f3fa2346761d227fc647c5744e', 1269792037, 1269799174, '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv', 'a:7:{s:11:"client_name";s:4:"DEMO";s:12:"client_image";s:0:"";s:14:"module_program";s:26:"MATERIAL MANAGEMENT MODULE";s:11:"module_type";s:7:"NON PPN";s:14:"module_package";s:11:"Development";s:14:"module_version";s:6:"2.0.0.";s:15:"module_revision";s:3:"343";}'),
('35089ff4216e56b5eb6ad82451b1fdb1', 1269830341, 1269831966, '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv', 'a:13:{s:11:"client_name";s:4:"DEMO";s:12:"client_image";s:0:"";s:14:"module_program";s:26:"MATERIAL MANAGEMENT MODULE";s:11:"module_type";s:7:"NON PPN";s:14:"module_package";s:11:"Development";s:14:"module_version";s:6:"2.0.0.";s:15:"module_revision";s:3:"350";s:9:"usr_login";s:5:"admin";s:12:"login_number";s:1:"0";s:9:"logged_in";s:1:"1";s:6:"usr_id";s:1:"1";s:7:"ucat_id";s:1:"8";s:12:"sess_prmr_no";s:32:"35089ff4216e56b5eb6ad82451b1fdb1";}');

-- --------------------------------------------------------

--
-- Table structure for table `prc_adjustment`
--

CREATE TABLE IF NOT EXISTS `prc_adjustment` (
  `adj_id` int(11) NOT NULL AUTO_INCREMENT,
  `adj_no` text CHARACTER SET latin1 NOT NULL,
  `adj_requestor` int(11) NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `adj_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adj_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_adjustment`
--

INSERT INTO `prc_adjustment` (`adj_id`, `adj_no`, `adj_requestor`, `date_create`, `adj_status`) VALUES
(4, '10/03/ADJ00001', 1, '2010-03-28 03:43:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prc_adjustment_detail`
--

CREATE TABLE IF NOT EXISTS `prc_adjustment_detail` (
  `adj_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `qty_stock` decimal(20,5) NOT NULL,
  `qty_opname` decimal(20,5) NOT NULL,
  `date_opname` date NOT NULL,
  `description` text NOT NULL,
  `check_opname` varchar(50) NOT NULL,
  `appr_note` text NOT NULL,
  `is_approve` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_adjustment_detail`
--

INSERT INTO `prc_adjustment_detail` (`adj_id`, `pro_id`, `sup_id`, `qty_stock`, `qty_opname`, `date_opname`, `description`, `check_opname`, `appr_note`, `is_approve`) VALUES
(4, 4, 3, 2318.75000, 2318.00000, '2010-03-30', 'Hilang', 'ahrie', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prc_bkbk`
--

CREATE TABLE IF NOT EXISTS `prc_bkbk` (
  `bkbk_id` int(11) NOT NULL AUTO_INCREMENT,
  `bkbk_no` varchar(20) NOT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `bkbk_date` date DEFAULT NULL,
  `bkbk_methode` set('CASH','CEK/GIRO','TRANSFER') DEFAULT 'CASH',
  `bkbk_printStatus` int(11) NOT NULL,
  `bkbk_printUsr` int(11) NOT NULL,
  `bkbk_printDate` date NOT NULL,
  `bkbk_printCountDate` date NOT NULL,
  `bkbk_printCount` int(11) NOT NULL,
  `transfer_biaya` decimal(12,5) DEFAULT NULL,
  `transfer_nomor` varchar(100) DEFAULT NULL,
  `transfer_rekening` varchar(100) DEFAULT NULL,
  `transfer_supplier` varchar(100) DEFAULT NULL,
  `cek_tempo` date DEFAULT NULL,
  `cek_no` varchar(50) DEFAULT NULL,
  `cek_rekening` varchar(100) DEFAULT NULL,
  `memo` mediumtext,
  `post_stat` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bkbk_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_bkbk`
--

INSERT INTO `prc_bkbk` (`bkbk_id`, `bkbk_no`, `sup_id`, `bkbk_date`, `bkbk_methode`, `bkbk_printStatus`, `bkbk_printUsr`, `bkbk_printDate`, `bkbk_printCountDate`, `bkbk_printCount`, `transfer_biaya`, `transfer_nomor`, `transfer_rekening`, `transfer_supplier`, `cek_tempo`, `cek_no`, `cek_rekening`, `memo`, `post_stat`) VALUES
(1, '111111', 3, '2010-03-29', 'CASH', 0, 0, '0000-00-00', '0000-00-00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ayeuna lum lunas T_T', 0),
(2, '222222', 3, '2010-03-28', 'CEK/GIRO', 0, 0, '0000-00-00', '0000-00-00', 0, NULL, NULL, NULL, NULL, '2010-03-31', '123123123', '09991111111', 'LUNAS CUIIII', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prc_bkbk_detail`
--

CREATE TABLE IF NOT EXISTS `prc_bkbk_detail` (
  `bkbk_id` int(11) NOT NULL DEFAULT '0',
  `con_id` int(11) NOT NULL DEFAULT '0',
  `con_dibayar` decimal(20,5) DEFAULT '0.00000',
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `kurs` decimal(8,2) NOT NULL DEFAULT '0.00',
  `ppn_dibayar` decimal(20,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  KEY `bkbk_id` (`bkbk_id`),
  KEY `con_id` (`con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_bkbk_detail`
--

INSERT INTO `prc_bkbk_detail` (`bkbk_id`, `con_id`, `con_dibayar`, `cur_id`, `kurs`, `ppn_dibayar`, `keterangan`) VALUES
(1, 1, 200.80000, 2, 0.00, 20916.00, NULL),
(2, 1, 39.00000, 2, 0.00, 200000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prc_contrabon`
--

CREATE TABLE IF NOT EXISTS `prc_contrabon` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_no` varchar(20) DEFAULT NULL,
  `con_date` date DEFAULT NULL,
  `con_dueDate` date DEFAULT NULL,
  `con_printStat` int(1) NOT NULL DEFAULT '0',
  `con_printDate` date NOT NULL DEFAULT '1978-02-26',
  `con_printCountDate` date NOT NULL DEFAULT '1978-02-26',
  `con_printCount` int(11) NOT NULL,
  `con_printUsr` int(4) NOT NULL DEFAULT '0',
  `con_status` int(1) NOT NULL DEFAULT '0',
  `con_value` decimal(20,5) DEFAULT '0.00000',
  `con_ppn_value` decimal(20,2) NOT NULL DEFAULT '0.00',
  `con_penerima` varchar(255) DEFAULT NULL,
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `con_payVal` decimal(20,5) DEFAULT '0.00000',
  `con_ppn_payVal` decimal(20,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`con_id`),
  UNIQUE KEY `rfq_no` (`con_no`),
  KEY `rfq_status` (`con_status`),
  KEY `po_id` (`po_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_contrabon`
--

INSERT INTO `prc_contrabon` (`con_id`, `con_no`, `con_date`, `con_dueDate`, `con_printStat`, `con_printDate`, `con_printCountDate`, `con_printCount`, `con_printUsr`, `con_status`, `con_value`, `con_ppn_value`, `con_penerima`, `cur_id`, `po_id`, `con_payVal`, `con_ppn_payVal`) VALUES
(1, '10/03/KB0001', '2010-03-27', '2010-04-26', 1, '2010-03-27', '1978-02-26', 0, 1, 1, 239.80000, 220916.00, 'ahrie', 2, 1, 239.80000, 220916.00),
(2, '10/03/KB0002', '2010-03-28', '2010-04-27', 1, '2010-03-28', '1978-02-26', 0, 1, 0, 3099.38326, 0.00, 'ahrie lg', 2, 2, 0.00000, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `prc_good_release`
--

CREATE TABLE IF NOT EXISTS `prc_good_release` (
  `grl_id` int(11) NOT NULL AUTO_INCREMENT,
  `grl_no` varchar(50) DEFAULT NULL,
  `mr_id` int(11) DEFAULT NULL,
  `grl_date` date NOT NULL,
  `grl_status` int(1) NOT NULL DEFAULT '0',
  `grl_printStat` int(1) NOT NULL DEFAULT '0',
  `grl_printDate` date DEFAULT NULL,
  `grl_lastprintDate` date NOT NULL,
  `grl_printUser` int(4) NOT NULL DEFAULT '0',
  `grl_printCounter` int(11) NOT NULL,
  `grl_releaseDate` date DEFAULT NULL,
  `grl_releaseUser` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grl_id`),
  KEY `pro_id` (`grl_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prc_good_release`
--

INSERT INTO `prc_good_release` (`grl_id`, `grl_no`, `mr_id`, `grl_date`, `grl_status`, `grl_printStat`, `grl_printDate`, `grl_lastprintDate`, `grl_printUser`, `grl_printCounter`, `grl_releaseDate`, `grl_releaseUser`) VALUES
(1, '10/03/GRL0003', 1, '2010-03-29', 1, 1, '2010-03-29', '2010-03-29', 1, 1, '2010-03-29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prc_good_release_detail`
--

CREATE TABLE IF NOT EXISTS `prc_good_release_detail` (
  `id_release` int(11) NOT NULL AUTO_INCREMENT,
  `grl_id` int(11) DEFAULT NULL,
  `mr_id` int(4) NOT NULL DEFAULT '0',
  `pro_id` int(11) NOT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `qty` decimal(20,5) DEFAULT '0.00000',
  `qty_release` decimal(20,5) DEFAULT '0.00000',
  `qty_use` decimal(20,5) DEFAULT '0.00000',
  `note` mediumtext,
  `is_closed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_release`),
  KEY `grl_id` (`grl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_good_release_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_good_return`
--

CREATE TABLE IF NOT EXISTS `prc_good_return` (
  `ret_id` int(11) NOT NULL AUTO_INCREMENT,
  `ret_no` varchar(100) DEFAULT NULL,
  `po_id` int(11) NOT NULL,
  `ret_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ret_status` int(1) NOT NULL DEFAULT '0',
  `ret_printStatus` int(1) NOT NULL DEFAULT '0',
  `ret_printUsr` int(4) NOT NULL DEFAULT '0',
  `ret_printDate` date DEFAULT NULL,
  `ret_printCountDate` date NOT NULL DEFAULT '1978-02-26',
  `ret_printCount` int(11) NOT NULL DEFAULT '0',
  `ret_requestor` int(11) NOT NULL,
  `con_id` int(11) NOT NULL DEFAULT '0',
  `post_stat` int(1) NOT NULL DEFAULT '0',
  `approval_note` text NOT NULL,
  PRIMARY KEY (`ret_id`),
  KEY `pro_id` (`ret_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_good_return`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_good_return_detail`
--

CREATE TABLE IF NOT EXISTS `prc_good_return_detail` (
  `ret_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_id` int(11) NOT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `price` decimal(20,5) DEFAULT '0.00000',
  `discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `cur_id` int(2) DEFAULT '0',
  `kurs` int(11) NOT NULL DEFAULT '1',
  `keterangan` text,
  KEY `gr_id` (`ret_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_good_return_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_gr`
--

CREATE TABLE IF NOT EXISTS `prc_gr` (
  `gr_id` int(11) NOT NULL AUTO_INCREMENT,
  `gr_no` varchar(100) DEFAULT NULL,
  `po_id` int(11) NOT NULL,
  `gr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gr_fakturSup` varchar(255) DEFAULT NULL,
  `gr_suratJalan` varchar(100) DEFAULT NULL,
  `gr_suratJalanTgl` date NOT NULL DEFAULT '2007-01-01',
  `gr_namaSupir` varchar(100) DEFAULT NULL,
  `gr_noIdentitas` varchar(100) DEFAULT NULL,
  `gr_noKendaraan` varchar(10) DEFAULT NULL,
  `gr_jenisKendaraan` set('motor','mobil') NOT NULL DEFAULT 'mobil',
  `gr_kendaraanMilik` set('sewa','pribadi') NOT NULL DEFAULT 'pribadi',
  `gr_type` set('rec','ret') NOT NULL DEFAULT 'rec',
  `gr_parent` int(11) NOT NULL DEFAULT '0',
  `gr_status` int(1) NOT NULL DEFAULT '0',
  `kur_status` int(1) NOT NULL,
  `gr_printStatus` int(1) NOT NULL DEFAULT '0',
  `gr_printUsr` int(4) NOT NULL DEFAULT '0',
  `gr_printDate` date DEFAULT NULL,
  `gr_printCountDate` date NOT NULL DEFAULT '1978-02-26',
  `gr_printCount` int(11) NOT NULL DEFAULT '0',
  `con_id` int(11) NOT NULL DEFAULT '0',
  `post_stat` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gr_id`),
  KEY `pro_id` (`gr_no`),
  KEY `gr_parent` (`gr_parent`),
  KEY `gr_type` (`gr_type`),
  KEY `con_id` (`con_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_gr`
--

INSERT INTO `prc_gr` (`gr_id`, `gr_no`, `po_id`, `gr_date`, `gr_fakturSup`, `gr_suratJalan`, `gr_suratJalanTgl`, `gr_namaSupir`, `gr_noIdentitas`, `gr_noKendaraan`, `gr_jenisKendaraan`, `gr_kendaraanMilik`, `gr_type`, `gr_parent`, `gr_status`, `kur_status`, `gr_printStatus`, `gr_printUsr`, `gr_printDate`, `gr_printCountDate`, `gr_printCount`, `con_id`, `post_stat`) VALUES
(1, '10/03/BPB0001', 1, '2010-03-27 15:57:14', '11111', '1111', '2010-03-27', '1111', '1111', '1111', 'motor', 'sewa', 'rec', 0, 1, 1, 1, 102, '2010-03-27', '1978-02-26', 0, 1, 0),
(2, '10/03/BPB0002', 1, '2010-03-27 15:58:23', '22222', '2222', '2010-03-27', '2222', '2222', '2222', 'mobil', 'pribadi', 'rec', 0, 1, 1, 1, 102, '2010-03-27', '1978-02-26', 0, 1, 0),
(3, '10/03/BPB0003', 2, '2010-03-28 00:28:13', '3333', '3333', '2010-03-28', '3333', '3333', '3333', 'motor', 'pribadi', 'rec', 0, 1, 1, 1, 1, '2010-03-28', '1978-02-26', 0, 2, 0),
(4, '10/03/BPB0004', 2, '2010-03-28 01:00:36', '4444', '4444', '2010-03-28', '4444', '4444', '4444', 'motor', 'pribadi', 'rec', 0, 1, 1, 1, 1, '2010-03-28', '1978-02-26', 0, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prc_gr_detail`
--

CREATE TABLE IF NOT EXISTS `prc_gr_detail` (
  `gr_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_id` int(11) NOT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `price` decimal(20,5) DEFAULT '0.00000',
  `discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `cur_id` int(2) DEFAULT '0',
  `kurs` decimal(12,5) NOT NULL DEFAULT '1.00000',
  `keterangan` text,
  KEY `gr_id` (`gr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_gr_detail`
--

INSERT INTO `prc_gr_detail` (`gr_id`, `pro_id`, `qty`, `price`, `discount`, `cur_id`, `kurs`, `keterangan`) VALUES
(1, 4, 3.00000, 40.00000, 10.10, 2, 9000.00000, ''),
(1, 1, 2.00000, 15.00000, 0.00, 2, 9000.00000, ''),
(2, 4, 2.00000, 40.00000, 10.10, 2, 9500.00000, ''),
(2, 1, 2.00000, 15.00000, 0.00, 2, 9500.00000, ''),
(3, 1, 6.00500, 88.05000, 15.50, 2, 9800.00000, ''),
(3, 4, 12.50000, 144.50000, 20.50, 2, 9800.00000, ''),
(4, 1, 4.00000, 88.05000, 15.50, 2, 9200.00000, ''),
(4, 4, 8.00000, 144.50000, 20.50, 2, 9200.00000, '');

-- --------------------------------------------------------

--
-- Table structure for table `prc_gr_detail_history`
--

CREATE TABLE IF NOT EXISTS `prc_gr_detail_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gr_id` int(11) DEFAULT NULL,
  `pro_id` int(11) NOT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `price` decimal(20,5) DEFAULT '0.00000',
  `discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `cur_id` int(2) DEFAULT NULL,
  `kurs` decimal(12,5) NOT NULL DEFAULT '1.00000',
  `usr_id` int(4) NOT NULL DEFAULT '0',
  `document` varchar(11) NOT NULL DEFAULT 'GR',
  `date_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED AUTO_INCREMENT=17 ;

--
-- Dumping data for table `prc_gr_detail_history`
--

INSERT INTO `prc_gr_detail_history` (`id`, `gr_id`, `pro_id`, `qty`, `price`, `discount`, `cur_id`, `kurs`, `usr_id`, `document`, `date_edit`) VALUES
(1, 1, 4, 3.00000, 40.00000, 10.10, 2, 1.00000, 102, 'GR', '2010-03-27 15:57:14'),
(2, 1, 1, 2.00000, 15.00000, 0.00, 2, 1.00000, 102, 'GR', '2010-03-27 15:57:14'),
(3, 2, 4, 2.00000, 40.00000, 10.10, 2, 1.00000, 102, 'GR', '2010-03-27 15:58:23'),
(4, 2, 1, 2.00000, 15.00000, 0.00, 2, 1.00000, 102, 'GR', '2010-03-27 15:58:23'),
(5, 1, 4, 3.00000, 40.00000, 10.10, 2, 9000.00000, 1, 'GR', '2010-03-27 16:03:34'),
(6, 1, 1, 2.00000, 15.00000, 0.00, 2, 9000.00000, 1, 'GR', '2010-03-27 16:03:34'),
(7, 2, 4, 2.00000, 40.00000, 10.10, 2, 9500.00000, 1, 'GR', '2010-03-27 16:04:18'),
(8, 2, 1, 2.00000, 15.00000, 0.00, 2, 9500.00000, 1, 'GR', '2010-03-27 16:04:18'),
(9, 3, 1, 6.00500, 88.05000, 15.50, 2, 1.00000, 1, 'GR', '2010-03-28 00:28:13'),
(10, 3, 4, 12.50000, 144.50000, 20.50, 2, 1.00000, 1, 'GR', '2010-03-28 00:28:13'),
(11, 4, 1, 4.00000, 88.05000, 15.50, 2, 1.00000, 1, 'GR', '2010-03-28 01:00:36'),
(12, 4, 4, 8.00000, 144.50000, 20.50, 2, 1.00000, 1, 'GR', '2010-03-28 01:00:36'),
(13, 3, 1, 6.00500, 88.05000, 15.50, 2, 9800.00000, 1, 'GR', '2010-03-28 01:07:50'),
(14, 3, 4, 12.50000, 144.50000, 20.50, 2, 9800.00000, 1, 'GR', '2010-03-28 01:07:50'),
(15, 4, 1, 4.00000, 88.05000, 15.50, 2, 9200.00000, 1, 'GR', '2010-03-28 01:08:03'),
(16, 4, 4, 8.00000, 144.50000, 20.50, 2, 9200.00000, 1, 'GR', '2010-03-28 01:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `prc_hutang_bulanan`
--

CREATE TABLE IF NOT EXISTS `prc_hutang_bulanan` (
  `bln_pos` int(2) NOT NULL DEFAULT '0',
  `thn_pos` year(4) NOT NULL,
  `sup_id` int(4) NOT NULL,
  `awal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `beli` decimal(12,2) NOT NULL DEFAULT '0.00',
  `bayar` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cur_id` int(2) NOT NULL DEFAULT '1',
  KEY `sup_id` (`sup_id`),
  KEY `bln_pos` (`bln_pos`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_hutang_bulanan`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_inventory`
--

CREATE TABLE IF NOT EXISTS `prc_inventory` (
  `inv_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_id` int(11) NOT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `inv_transDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inv_begin` decimal(20,5) DEFAULT '0.00000',
  `inv_in` decimal(20,5) DEFAULT '0.00000',
  `inv_out` decimal(20,5) DEFAULT '0.00000',
  `inv_end` decimal(20,5) DEFAULT '0.00000',
  `inv_price` decimal(20,5) DEFAULT '0.00000',
  `bal_price` decimal(20,5) DEFAULT '0.00000',
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `inv_document` varchar(50) DEFAULT NULL,
  `date_setup` datetime NOT NULL,
  PRIMARY KEY (`inv_id`),
  KEY `pro_id` (`pro_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=6 ;

--
-- Dumping data for table `prc_inventory`
--

INSERT INTO `prc_inventory` (`inv_id`, `pro_id`, `sup_id`, `inv_transDate`, `inv_begin`, `inv_in`, `inv_out`, `inv_end`, `inv_price`, `bal_price`, `cur_id`, `inv_document`, `date_setup`) VALUES
(1, 4, 3, '2010-03-28 23:53:34', 2000.00000, 18.00000, 318.00000, 2018.00000, 144.50000, 289000.00000, 2, '10/03/GRL0003', '2010-03-27 14:54:53'),
(2, 1, 1, '2010-03-28 23:53:25', 0.00000, 100.00000, 200.00000, 100.00000, 15.00000, 0.00000, 2, '10/03/GRL0003', '2010-03-27 15:31:05'),
(3, 1, 3, '2010-03-28 01:00:59', 123.06150, 49.20000, 0.00000, 172.26150, 88.05000, 15167.62508, 2, '10/03/BPB0004', '0000-00-00 00:00:00'),
(4, 2, 1, '2010-03-29 00:00:00', 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 1, 'SETUP', '2010-03-29 00:26:54'),
(5, 2, 2, '2010-03-29 00:00:00', 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 2, 'SETUP', '2010-03-29 00:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `prc_inventory_history`
--

CREATE TABLE IF NOT EXISTS `prc_inventory_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_id` int(11) NOT NULL DEFAULT '0',
  `pro_id` int(11) NOT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `inv_transDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `inv_begin` decimal(20,5) DEFAULT NULL,
  `inv_in` decimal(20,5) DEFAULT '0.00000',
  `inv_out` decimal(20,5) DEFAULT '0.00000',
  `inv_end` decimal(20,5) DEFAULT NULL,
  `inv_price` decimal(20,5) DEFAULT '0.00000',
  `bal_price` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `inv_document` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pro_id` (`pro_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=18 ;

--
-- Dumping data for table `prc_inventory_history`
--

INSERT INTO `prc_inventory_history` (`id`, `inv_id`, `pro_id`, `sup_id`, `inv_transDate`, `inv_begin`, `inv_in`, `inv_out`, `inv_end`, `inv_price`, `bal_price`, `cur_id`, `inv_document`) VALUES
(1, 1, 4, 3, '2010-03-27 00:00:00', 2000.00000, 0.00000, 0.00000, 2000.00000, 25.00000, 25.00000, 2, 'SETUP'),
(2, 2, 1, 1, '2010-03-27 00:00:00', 200.00000, 0.00000, 0.00000, 200.00000, 15.00000, 15.00000, 2, 'SETUP'),
(3, 1, 4, 3, '2010-03-27 15:58:48', 2000.00000, 37.50000, 0.00000, 2037.50000, 40.00000, 66218.75000, 2, '10/03/BPB0001'),
(4, 3, 1, 3, '2010-03-27 15:58:48', 0.00000, 24.60000, 0.00000, 24.60000, 15.00000, 184.50000, 2, '10/03/BPB0001'),
(5, 1, 4, 3, '2010-03-27 15:58:56', 2037.50000, 25.00000, 0.00000, 2062.50000, 40.00000, 82500.00000, 2, '10/03/BPB0002'),
(6, 3, 1, 3, '2010-03-27 15:58:56', 24.60000, 24.60000, 0.00000, 49.20000, 15.00000, 738.00000, 2, '10/03/BPB0002'),
(7, 3, 1, 3, '2010-03-28 01:00:53', 49.20000, 73.86150, 0.00000, 123.06150, 88.05000, 6340.74379, 2, '10/03/BPB0003'),
(8, 1, 4, 3, '2010-03-28 01:00:53', 2062.50000, 156.25000, 0.00000, 2218.75000, 144.50000, 204679.68750, 2, '10/03/BPB0003'),
(9, 3, 1, 3, '2010-03-28 01:00:59', 123.06150, 49.20000, 0.00000, 172.26150, 88.05000, 15167.62508, 2, '10/03/BPB0004'),
(10, 1, 4, 3, '2010-03-28 01:00:59', 2218.75000, 100.00000, 0.00000, 2318.75000, 144.50000, 335059.37500, 2, '10/03/BPB0004'),
(11, 1, 4, 3, '2010-03-28 03:44:21', 2318.75000, 0.00000, 0.75000, 2318.00000, 0.00000, 0.00000, 0, '10/03/ADJ00001'),
(12, 4, 2, 1, '2010-03-29 00:00:00', 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 1, 'SETUP'),
(13, 5, 2, 2, '2010-03-29 00:00:00', 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 0.00000, 2, 'SETUP'),
(14, 2, 1, 1, '2010-03-28 23:52:09', 200.00000, 0.00000, 200.00000, 0.00000, 15.00000, 0.00000, 0, '10/03/GRL0003'),
(15, 1, 4, 3, '2010-03-28 23:52:09', 2318.00000, 0.00000, 318.00000, 2000.00000, 144.50000, 289000.00000, 0, '10/03/GRL0003'),
(16, 2, 1, 1, '2010-03-28 23:53:25', 0.00000, 100.00000, 0.00000, 100.00000, 0.00000, 0.00000, 0, '10/03/GRL0003'),
(17, 1, 4, 3, '2010-03-28 23:53:34', 2000.00000, 18.00000, 0.00000, 2018.00000, 0.00000, 0.00000, 0, '10/03/GRL0003');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_adjustment_info`
--

CREATE TABLE IF NOT EXISTS `prc_master_adjustment_info` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`info_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_master_adjustment_info`
--

INSERT INTO `prc_master_adjustment_info` (`info_id`, `description`) VALUES
(1, 'Hilang'),
(2, 'Kecurian'),
(3, 'Salah Hitung'),
(4, 'Rusak');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_bank`
--

CREATE TABLE IF NOT EXISTS `prc_master_bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name_singkat` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `bank_name_lengkap` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `rec_creator` int(4) DEFAULT NULL,
  `rec_created` date DEFAULT NULL,
  `rec_edit` tinyint(1) DEFAULT '0',
  `rec_editor` int(4) DEFAULT NULL,
  `rec_edited` date DEFAULT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prc_master_bank`
--

INSERT INTO `prc_master_bank` (`bank_id`, `bank_name_singkat`, `bank_name_lengkap`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'BCA', 'Bank Central Asia', 102, '2010-03-27', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_category`
--

CREATE TABLE IF NOT EXISTS `prc_master_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_code` varchar(100) DEFAULT NULL,
  `cat_parent` int(11) DEFAULT NULL,
  `cat_level` int(4) NOT NULL DEFAULT '0',
  `cat_name` varchar(255) DEFAULT NULL,
  `ct_id` int(2) NOT NULL DEFAULT '0',
  `is_active` int(1) NOT NULL DEFAULT '1',
  `rec_creator` int(4) DEFAULT NULL,
  `rec_created` date DEFAULT NULL,
  `rec_edit` int(1) DEFAULT NULL,
  `rec_editor` int(1) DEFAULT NULL,
  `rec_edited` date DEFAULT NULL,
  `need_realization` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_name` (`cat_name`,`cat_parent`),
  KEY `cat_parent` (`cat_parent`),
  KEY `cat_code` (`cat_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `prc_master_category`
--

INSERT INTO `prc_master_category` (`cat_id`, `cat_code`, `cat_parent`, `cat_level`, `cat_name`, `ct_id`, `is_active`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`, `need_realization`) VALUES
(1, '01', 0, 1, 'ATK', 0, 1, 1, '2010-03-25', NULL, NULL, NULL, 0),
(2, '01.01', 1, 2, 'ALAT TULIS', 0, 1, 1, '2010-03-25', NULL, NULL, NULL, 0),
(3, '01.01.01', 2, 3, 'PENSIL', 0, 1, 1, '2010-03-25', NULL, NULL, NULL, 1),
(4, '01.01.02', 2, 3, 'BALPOIN', 0, 1, 1, '2010-03-25', NULL, NULL, NULL, 1),
(5, '02', 0, 1, 'KONSUMSI', 0, 1, 1, '2010-03-26', 1, 1, '2010-03-26', 0),
(8, '02.01', 5, 2, 'MINUMAN', 0, 1, 1, '2010-03-26', NULL, NULL, NULL, 0),
(9, '02.01.01', 8, 3, 'DINGIN', 0, 1, 1, '2010-03-26', NULL, NULL, NULL, 1),
(10, '02.01.02', 8, 3, 'PANAS', 0, 1, 1, '2010-03-26', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_contact_person`
--

CREATE TABLE IF NOT EXISTS `prc_master_contact_person` (
  `per_id` int(5) NOT NULL AUTO_INCREMENT,
  `per_Fname` varchar(255) NOT NULL,
  `per_Lname` varchar(255) NOT NULL,
  `per_Nickname` varchar(255) DEFAULT NULL,
  `per_address` mediumtext NOT NULL,
  `per_city` int(3) NOT NULL DEFAULT '0',
  `ttl_id` int(2) NOT NULL,
  `dep_id` int(3) NOT NULL,
  `per_phone` varchar(20) DEFAULT NULL,
  `per_handphone` varchar(100) DEFAULT NULL,
  `per_fax` varchar(20) DEFAULT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `rec_creator` int(4) NOT NULL,
  `rec_created` date NOT NULL,
  `rec_edit` tinyint(1) NOT NULL DEFAULT '0',
  `rec_editor` int(4) NOT NULL,
  `rec_edited` date NOT NULL,
  PRIMARY KEY (`per_id`),
  KEY `dep_id` (`dep_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_master_contact_person`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_master_credit_term`
--

CREATE TABLE IF NOT EXISTS `prc_master_credit_term` (
  `term_id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id_name` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `term_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `term_days` int(2) NOT NULL,
  `term_discount` decimal(4,2) NOT NULL,
  `rec_creator` int(4) DEFAULT NULL,
  `rec_created` date DEFAULT NULL,
  `rec_edit` tinyint(1) DEFAULT '0',
  `rec_editor` int(4) DEFAULT NULL,
  `rec_edited` date DEFAULT NULL,
  PRIMARY KEY (`term_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_credit_term`
--

INSERT INTO `prc_master_credit_term` (`term_id`, `term_id_name`, `term_name`, `term_days`, `term_discount`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'COD', 'Cash on delivery', 0, 5.00, 1, '2010-03-25', 0, NULL, NULL),
(2, '30H', '30 Hari', 30, 5.00, 102, '2010-03-27', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_currency`
--

CREATE TABLE IF NOT EXISTS `prc_master_currency` (
  `cur_id` int(2) NOT NULL AUTO_INCREMENT,
  `cur_symbol` varchar(20) DEFAULT NULL,
  `cur_name` varchar(100) DEFAULT NULL,
  `cur_digit` int(11) NOT NULL DEFAULT '2',
  `cur_short` varchar(25) NOT NULL,
  PRIMARY KEY (`cur_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_currency`
--

INSERT INTO `prc_master_currency` (`cur_id`, `cur_symbol`, `cur_name`, `cur_digit`, `cur_short`) VALUES
(1, 'Rp', 'Indonesian Rupiah', 2, 'Rupiah'),
(2, 'US$', 'US Dollar', 3, 'Dollar');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_departemen`
--

CREATE TABLE IF NOT EXISTS `prc_master_departemen` (
  `dep_id` int(11) NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `rec_creator` int(4) NOT NULL,
  `rec_created` date NOT NULL,
  `rec_edit` int(1) NOT NULL DEFAULT '0',
  `rec_editor` int(4) NOT NULL,
  `rec_edited` date NOT NULL,
  PRIMARY KEY (`dep_id`),
  UNIQUE KEY `dep_name` (`dep_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_departemen`
--

INSERT INTO `prc_master_departemen` (`dep_id`, `dep_name`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'IT', 1, '2010-03-25', 1, 1, '2010-03-25'),
(2, 'SALES', 1, '2010-03-27', 0, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_jabatan`
--

CREATE TABLE IF NOT EXISTS `prc_master_jabatan` (
  `jab_id` int(11) NOT NULL AUTO_INCREMENT,
  `jab_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `rec_creator` int(4) NOT NULL,
  `rec_created` date NOT NULL,
  `rec_edit` int(1) NOT NULL DEFAULT '0',
  `rec_editor` int(4) NOT NULL,
  `rec_edited` date NOT NULL,
  PRIMARY KEY (`jab_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_jabatan`
--

INSERT INTO `prc_master_jabatan` (`jab_id`, `jab_name`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'ADMINISTRATOR', 1, '2010-03-25', 0, 0, '0000-00-00'),
(2, 'MARKETING', 1, '2010-03-27', 0, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_kota`
--

CREATE TABLE IF NOT EXISTS `prc_master_kota` (
  `kota_id` int(11) NOT NULL AUTO_INCREMENT,
  `provinsi_id` int(11) NOT NULL,
  `kota_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `code_area` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `rec_creator` int(11) NOT NULL,
  `rec_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rec_edit` int(1) NOT NULL,
  `rec_editor` int(4) NOT NULL,
  `rec_edited` date NOT NULL,
  PRIMARY KEY (`kota_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_kota`
--

INSERT INTO `prc_master_kota` (`kota_id`, `provinsi_id`, `kota_name`, `code_area`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 1, 'BANDUNG', '022', 1, '2010-03-25 00:00:00', 1, 102, '2010-03-27'),
(2, 2, 'OKINAWA', '983', 102, '2010-03-27 00:00:00', 0, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_legality`
--

CREATE TABLE IF NOT EXISTS `prc_master_legality` (
  `legal_id` int(11) NOT NULL AUTO_INCREMENT,
  `legal_name` varchar(25) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`legal_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_master_legality`
--

INSERT INTO `prc_master_legality` (`legal_id`, `legal_name`) VALUES
(1, 'PT'),
(2, 'PD'),
(3, 'CV'),
(4, 'PERORANGAN');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_motivation`
--

CREATE TABLE IF NOT EXISTS `prc_master_motivation` (
  `motiv_id` int(11) NOT NULL AUTO_INCREMENT,
  `motiv_word` mediumtext NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '0',
  `active_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`motiv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=27 ;

--
-- Dumping data for table `prc_master_motivation`
--

INSERT INTO `prc_master_motivation` (`motiv_id`, `motiv_word`, `is_active`, `active_date`) VALUES
(1, 'I am a slow walker, but I never walk backwards.', 1, '2010-03-19 09:37:19'),
(2, 'I do not think much of a man who is not wiser today than he was yesterday.', 1, '2010-03-20 09:08:17'),
(3, 'Whatever you are, be a good one.', 1, '2010-03-22 10:42:47'),
(4, 'I will study and get ready, and perhaps my chance will come.', 1, '2010-03-23 05:32:19'),
(6, 'Let not him who is houseless pull down the house of another, but let him work diligently and build one for himself, thus by example assuring that his own shall be safe from violence when built.', 1, '2010-03-24 10:27:09'),
(7, 'Will springs from the two elements of moral sense and self-interest.', 1, '2010-03-25 15:57:38'),
(8, 'My great concern is not whether you have failed, but whether you are content with your failure.', 1, '2010-03-26 13:02:47'),
(9, 'The way for a young man to rise is to improve himself in every way he can, never suspecting that anybody wishes to hinder him.', 1, '2010-03-27 11:34:13'),
(11, 'I will prepare and some day my chance will come.', 1, '2010-03-28 00:12:17'),
(12, 'I want it said of me by those who knew me best, that I always plucked a thistle and planted a flower where I thought a flower would grow.', 1, '2010-03-28 23:00:45'),
(13, 'I never had a policy; I have just tried to do my very best each and every day.', 1, '2010-03-28 23:55:17'),
(14, 'If there is anything that a man can do well, I say let him do it. Give him a chance.', 1, '2010-03-29 00:05:30'),
(15, 'You cannot escape the responsibility of tomorrow by evading it today.', 0, '2010-03-19 09:37:19'),
(16, 'Nearly all men can stand adversity, but if you want to test a man''s character, give him power.', 0, '2010-03-19 09:37:19'),
(18, 'You can fool some of the people all of the time, and all of the people some of the time, but you can not fool all of the people all of the time.', 0, '2010-03-19 09:37:19'),
(19, 'People are just as happy as they make up their minds to be', 0, '2010-03-19 09:37:19'),
(21, 'I am not bound to win, but I am bound to be true. I am not bound to succeed, but I am bound to live by the light that I have. I must stand with anybody that stands right, and stand with him while he is right, and part with him when he goes wrong.', 0, '2010-03-19 09:37:19'),
(22, 'With malice toward none, with charity for all.', 0, '2010-03-19 09:37:19'),
(23, 'That some should be rich, shows that others may become rich, and, hence, is just encouragement to industry and enterprise.', 0, '2010-03-19 09:37:19'),
(24, 'Always bear in mind, that your own resolution to succeed is more important than any other thing.', 0, '2010-03-19 09:37:19'),
(25, 'Determine that the thing can and shall be done, and then we shall find the way.', 0, '2010-03-19 09:37:19'),
(26, 'I have noticed that folks are generally about as happy as they', 0, '2010-03-19 09:37:19');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_negara`
--

CREATE TABLE IF NOT EXISTS `prc_master_negara` (
  `negara_id` int(11) NOT NULL AUTO_INCREMENT,
  `negara_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `rec_creator` int(4) NOT NULL,
  `rec_created` date NOT NULL,
  `rec_edit` int(1) NOT NULL,
  `rec_editor` int(4) NOT NULL,
  `rec_edited` date NOT NULL,
  PRIMARY KEY (`negara_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_negara`
--

INSERT INTO `prc_master_negara` (`negara_id`, `negara_name`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'INDONESIA', 1, '2010-03-25', 1, 102, '2010-03-27'),
(2, 'JAPAN', 102, '2010-03-27', 0, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_payment`
--

CREATE TABLE IF NOT EXISTS `prc_master_payment` (
  `pay_id` int(2) NOT NULL AUTO_INCREMENT,
  `pay_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_master_payment`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_master_plan_type`
--

CREATE TABLE IF NOT EXISTS `prc_master_plan_type` (
  `plan_id` int(2) NOT NULL AUTO_INCREMENT,
  `plan_name` char(20) NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_master_plan_type`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_master_product`
--

CREATE TABLE IF NOT EXISTS `prc_master_product` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_code` varchar(20) NOT NULL DEFAULT '',
  `pro_name` varchar(200) NOT NULL DEFAULT '',
  `pro_status` set('active','non active') NOT NULL DEFAULT 'non active',
  `pro_type` set('L','I') NOT NULL DEFAULT 'L',
  `pro_lead_time` int(2) NOT NULL DEFAULT '0',
  `pro_is_reorder` int(1) NOT NULL DEFAULT '0',
  `pro_min_reorder` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pro_max_reorder` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pro_max_type` char(1) DEFAULT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `pro_spek` mediumtext,
  `pro_remark` mediumtext,
  `pro_image` varchar(255) DEFAULT NULL,
  `is_stockJoin` int(1) NOT NULL DEFAULT '1',
  `date_setup` datetime NOT NULL,
  `rec_creator` int(4) NOT NULL DEFAULT '0',
  `rec_dateCreated` date NOT NULL DEFAULT '2000-02-26',
  `rec_editor` int(4) NOT NULL DEFAULT '0',
  `rec_dateEdited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pro_id`),
  KEY `cat_id` (`cat_id`),
  KEY `pro_name_2` (`pro_name`),
  KEY `pro_code` (`pro_code`),
  FULLTEXT KEY `pro_name` (`pro_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_master_product`
--

INSERT INTO `prc_master_product` (`pro_id`, `pro_code`, `pro_name`, `pro_status`, `pro_type`, `pro_lead_time`, `pro_is_reorder`, `pro_min_reorder`, `pro_max_reorder`, `pro_max_type`, `cat_id`, `um_id`, `pro_spek`, `pro_remark`, `pro_image`, `is_stockJoin`, `date_setup`, `rec_creator`, `rec_dateCreated`, `rec_editor`, `rec_dateEdited`) VALUES
(1, '01.01.02.001', 'BALPOIN MERAH', 'active', 'L', 0, 0, 0.00, 0.00, '0', 4, 2, '', '', '', 0, '2010-03-27 15:28:06', 0, '2000-02-26', 0, '2010-03-27 15:31:05'),
(2, '01.01.02.002', 'BALPOIN HITAM', 'active', 'L', 0, 0, 0.00, 0.00, '0', 4, 2, '', '', '', 0, '2010-03-29 00:26:32', 0, '2000-02-26', 0, '2010-03-28 23:26:54'),
(3, '01.01.01.001', 'PENSIL 2B', 'non active', 'L', 0, 0, 0.00, 0.00, '0', 3, 2, '', '', '', 1, '0000-00-00 00:00:00', 0, '2000-02-26', 0, '2010-03-27 13:43:04'),
(4, '02.01.01.001', 'KOLLA', 'active', 'L', 0, 1, 10.00, 2000.00, '0', 9, 2, '', '', '37dc570f0a.jpg', 0, '2010-03-27 14:50:13', 0, '2000-02-26', 0, '2010-03-27 14:54:53');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_provinsi`
--

CREATE TABLE IF NOT EXISTS `prc_master_provinsi` (
  `provinsi_id` int(11) NOT NULL AUTO_INCREMENT,
  `negara_id` int(11) NOT NULL,
  `provinsi_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `rec_creator` int(11) NOT NULL,
  `rec_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rec_edit` int(1) NOT NULL,
  `rec_editor` int(4) NOT NULL,
  `rec_edited` date NOT NULL,
  PRIMARY KEY (`provinsi_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_master_provinsi`
--

INSERT INTO `prc_master_provinsi` (`provinsi_id`, `negara_id`, `provinsi_name`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 1, 'JAWA BARAT', 1, '2010-03-25 00:00:00', 1, 102, '2010-03-27'),
(2, 2, 'TOKYO', 102, '2010-03-27 00:00:00', 1, 102, '2010-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_purchase_type`
--

CREATE TABLE IF NOT EXISTS `prc_master_purchase_type` (
  `pty_id` int(2) NOT NULL AUTO_INCREMENT,
  `pty_name` varchar(20) NOT NULL,
  PRIMARY KEY (`pty_id`),
  UNIQUE KEY `pty_name` (`pty_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `prc_master_purchase_type`
--

INSERT INTO `prc_master_purchase_type` (`pty_id`, `pty_name`) VALUES
(1, 'pembelian rutin'),
(2, 'proyek'),
(3, 'aset baru'),
(4, 'aset tambahan'),
(5, 'peningkatan aset'),
(6, 'servis order');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_satuan`
--

CREATE TABLE IF NOT EXISTS `prc_master_satuan` (
  `satuan_id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan_name` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `satuan_format` int(1) NOT NULL DEFAULT '0',
  `rec_creator` int(4) DEFAULT NULL,
  `rec_created` date DEFAULT NULL,
  `rec_edit` tinyint(1) DEFAULT '0',
  `rec_editor` int(4) DEFAULT NULL,
  `rec_edited` date DEFAULT NULL,
  PRIMARY KEY (`satuan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_master_satuan`
--

INSERT INTO `prc_master_satuan` (`satuan_id`, `satuan_name`, `satuan_format`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'PCS', 0, 1, '2010-03-25', 1, 1, '2010-03-25'),
(2, 'PACK', 2, 1, '2010-03-25', 1, 1, '2010-03-25'),
(3, 'DUS', 4, 1, '2010-03-25', 1, 1, '2010-03-25'),
(4, 'METER', 2, 102, '2010-03-27', 1, 102, '2010-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_supplier`
--

CREATE TABLE IF NOT EXISTS `prc_master_supplier` (
  `sup_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_name` varchar(100) NOT NULL DEFAULT '',
  `legal_id` int(2) NOT NULL,
  `sup_npwp` varchar(200) DEFAULT NULL,
  `sup_address` varchar(255) DEFAULT NULL,
  `sup_city` varchar(20) DEFAULT NULL,
  `cnt_id` int(1) NOT NULL DEFAULT '1',
  `sup_phone1` varchar(20) DEFAULT NULL,
  `sup_phone2` varchar(20) DEFAULT NULL,
  `sup_phone3` varchar(20) DEFAULT NULL,
  `sup_fax` varchar(20) DEFAULT NULL,
  `sup_handphone` varchar(20) DEFAULT NULL,
  `sup_email` varchar(100) NOT NULL DEFAULT '',
  `term_id` varchar(10) DEFAULT NULL,
  `sup_status` int(11) NOT NULL DEFAULT '1',
  `deactive_note` text NOT NULL,
  `deactive_req` int(11) NOT NULL DEFAULT '0',
  `deactive_date` date DEFAULT NULL,
  `rec_creator` int(4) DEFAULT NULL,
  `rec_created` date DEFAULT NULL,
  `rec_edit` tinyint(1) DEFAULT '0',
  `rec_editor` int(4) DEFAULT NULL,
  `rec_edited` date DEFAULT NULL,
  PRIMARY KEY (`sup_id`),
  KEY `sup_name` (`sup_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `prc_master_supplier`
--

INSERT INTO `prc_master_supplier` (`sup_id`, `sup_name`, `legal_id`, `sup_npwp`, `sup_address`, `sup_city`, `cnt_id`, `sup_phone1`, `sup_phone2`, `sup_phone3`, `sup_fax`, `sup_handphone`, `sup_email`, `term_id`, `sup_status`, `deactive_note`, `deactive_req`, `deactive_date`, `rec_creator`, `rec_created`, `rec_edit`, `rec_editor`, `rec_edited`) VALUES
(1, 'Angin Ribut', 1, '15.141.222.3-123.412', 'Bandung', '1', 1, '[022] - 0', '[022] - 0', '[022] - 0', '[022] - 0', '0', '0', '1', 1, 'Jah', 1, '2010-03-28', 1, '2010-03-25', 0, NULL, NULL),
(2, 'Gramedia', 1, '22.231.123.2-422.222', 'bandunmg', '1', 1, '[022] - 0', '[022] - 0', '[022] - 0', '[022] - 0', '0', '0', '1', 1, 'Ga bener ni pemasok ui', 102, '2010-03-27', 1, '2010-03-25', 0, NULL, NULL),
(3, 'Orihime', 1, '12.345.678.9-012.345', 'Near Okinawa', '2', 1, '[983] - 4112', '[983] - 12333', '[983] - 3212', '[983] - 13333', '12312', 'asdaa', '2', 1, '', 0, NULL, 1, '2010-03-27', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_supplier_bank_account`
--

CREATE TABLE IF NOT EXISTS `prc_master_supplier_bank_account` (
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `bank_id` int(11) NOT NULL DEFAULT '0',
  `acc_no` varchar(100) NOT NULL,
  PRIMARY KEY (`sup_id`,`bank_id`,`acc_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_master_supplier_bank_account`
--

INSERT INTO `prc_master_supplier_bank_account` (`sup_id`, `bank_id`, `acc_no`) VALUES
(3, 1, '4133331');

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_supplier_category`
--

CREATE TABLE IF NOT EXISTS `prc_master_supplier_category` (
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `cat_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sup_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_master_supplier_category`
--

INSERT INTO `prc_master_supplier_category` (`sup_id`, `cat_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `prc_master_supplier_product`
--

CREATE TABLE IF NOT EXISTS `prc_master_supplier_product` (
  `pro_id` int(11) NOT NULL,
  `sup_id` int(11) NOT NULL,
  `sup_pro_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pro_id`,`sup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `prc_master_supplier_product`
--

INSERT INTO `prc_master_supplier_product` (`pro_id`, `sup_id`, `sup_pro_code`) VALUES
(1, 1, 'A1'),
(1, 2, 'BA1'),
(2, 1, ''),
(2, 2, ''),
(4, 3, '41111');

-- --------------------------------------------------------

--
-- Table structure for table `prc_mr`
--

CREATE TABLE IF NOT EXISTS `prc_mr` (
  `mr_id` int(11) NOT NULL AUTO_INCREMENT,
  `mr_no` varchar(50) DEFAULT NULL,
  `mr_date` date DEFAULT NULL,
  `is_approved` int(1) DEFAULT '0',
  `usr_approve` int(4) NOT NULL DEFAULT '0',
  `mr_lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mr_requestor` int(4) DEFAULT NULL,
  `mr_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mr_id`),
  KEY `is_approved` (`is_approved`),
  KEY `mr_requestor` (`mr_requestor`),
  KEY `mr_date` (`mr_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prc_mr`
--

INSERT INTO `prc_mr` (`mr_id`, `mr_no`, `mr_date`, `is_approved`, `usr_approve`, `mr_lastModified`, `mr_requestor`, `mr_status`) VALUES
(1, '10/03/MR0001', '2010-03-29', 0, 0, '2010-03-29 12:03:38', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prc_mr_category`
--

CREATE TABLE IF NOT EXISTS `prc_mr_category` (
  `mct_id` int(2) NOT NULL AUTO_INCREMENT,
  `mct_name` varchar(200) NOT NULL,
  `cat_code` char(2) NOT NULL,
  PRIMARY KEY (`mct_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_mr_category`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_mr_detail`
--

CREATE TABLE IF NOT EXISTS `prc_mr_detail` (
  `pro_id` int(11) NOT NULL,
  `mr_id` int(11) NOT NULL DEFAULT '0',
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `qty` decimal(20,5) DEFAULT '0.00000',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `delivery_date` date DEFAULT NULL,
  `description` mediumtext,
  `requestStat` int(1) DEFAULT '0',
  `mct_id` int(2) NOT NULL DEFAULT '0',
  `so_id` int(11) NOT NULL DEFAULT '0',
  `grl_id` int(11) NOT NULL,
  `grl_realisasi` decimal(20,5) NOT NULL,
  `grl_description` text NOT NULL,
  `qty_use` decimal(20,5) NOT NULL,
  `note` text NOT NULL,
  `is_closed` int(1) NOT NULL DEFAULT '0',
  `mr_reqTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pro_id`,`mr_id`),
  KEY `requestStat` (`requestStat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_mr_detail`
--

INSERT INTO `prc_mr_detail` (`pro_id`, `mr_id`, `sup_id`, `qty`, `um_id`, `delivery_date`, `description`, `requestStat`, `mct_id`, `so_id`, `grl_id`, `grl_realisasi`, `grl_description`, `qty_use`, `note`, `is_closed`, `mr_reqTime`) VALUES
(1, 1, 1, 200.00000, 2, '2010-03-29', 'Butuh 1', 2, 0, 0, 1, 200.00000, '', 100.00000, 'jah', 1, '2010-03-28 23:04:42'),
(4, 1, 3, 318.00000, 2, '2010-03-29', 'butuh 2', 3, 0, 0, 1, 318.00000, '', 300.00000, '', 1, '2010-03-28 23:04:47');

-- --------------------------------------------------------

--
-- Table structure for table `prc_mr_detail_history`
--

CREATE TABLE IF NOT EXISTS `prc_mr_detail_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_id` int(11) NOT NULL,
  `mr_id` int(11) DEFAULT NULL,
  `sup_id` int(11) NOT NULL DEFAULT '0',
  `qty` decimal(20,5) DEFAULT '0.00000',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `delivery_date` date DEFAULT NULL,
  `description` mediumtext,
  `requestStat` int(1) NOT NULL DEFAULT '0',
  `mr_usr` int(4) DEFAULT '0',
  `mr_usr_note` mediumtext,
  `so_id` int(11) NOT NULL DEFAULT '0',
  `grl_id` int(11) NOT NULL,
  `grl_realisasi` decimal(20,5) NOT NULL,
  `grl_description` text NOT NULL,
  `qty_use` decimal(20,5) NOT NULL,
  `note` text NOT NULL,
  `date_use` date NOT NULL,
  `is_closed` int(1) NOT NULL DEFAULT '0',
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pro_id` (`pro_id`),
  KEY `mr_id` (`mr_id`),
  KEY `requestStat` (`requestStat`),
  KEY `mr_usr` (`mr_usr`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=9 ;

--
-- Dumping data for table `prc_mr_detail_history`
--

INSERT INTO `prc_mr_detail_history` (`id`, `pro_id`, `mr_id`, `sup_id`, `qty`, `um_id`, `delivery_date`, `description`, `requestStat`, `mr_usr`, `mr_usr_note`, `so_id`, `grl_id`, `grl_realisasi`, `grl_description`, `qty_use`, `note`, `date_use`, `is_closed`, `lastupdate`) VALUES
(1, 1, 1, 1, 100.00000, 2, '2010-03-29', 'Butuh 1', 0, 0, NULL, 0, 0, 0.00000, '', 0.00000, '', '0000-00-00', 0, '2010-03-28 23:38:48'),
(2, 4, 1, 3, 318.00000, 2, '2010-03-29', 'butuh 2', 0, 0, NULL, 0, 0, 0.00000, '', 0.00000, '', '0000-00-00', 0, '2010-03-28 23:38:48'),
(3, 1, 1, 0, 200.00000, 2, '2010-03-29', 'Butuh 1', 2, 1, 'ubah', 0, 1, 0.00000, '', 0.00000, '', '0000-00-00', 0, '2010-03-28 23:50:38'),
(4, 4, 1, 0, 318.00000, 2, '2010-03-29', 'butuh 2', 3, 1, 'dgn catat', 0, 1, 0.00000, '', 0.00000, '', '0000-00-00', 0, '2010-03-28 23:50:38'),
(5, 1, 1, 1, 200.00000, 2, NULL, NULL, 0, 1, NULL, 0, 1, 200.00000, '', 0.00000, '', '0000-00-00', 0, '2010-03-28 23:52:09'),
(6, 4, 1, 3, 318.00000, 2, NULL, NULL, 0, 1, NULL, 0, 1, 318.00000, '', 0.00000, '', '0000-00-00', 0, '2010-03-28 23:52:09'),
(7, 1, 1, 0, 0.00000, 0, NULL, NULL, 0, 0, NULL, 0, 1, 0.00000, '', 100.00000, 'jah', '2010-03-24', 0, '2010-03-28 23:52:45'),
(8, 4, 1, 0, 0.00000, 0, NULL, NULL, 0, 0, NULL, 0, 1, 0.00000, '', 300.00000, '', '2010-03-26', 0, '2010-03-28 23:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `prc_mr_type`
--

CREATE TABLE IF NOT EXISTS `prc_mr_type` (
  `mrt_id` int(11) NOT NULL AUTO_INCREMENT,
  `mrt_name` varchar(50) NOT NULL,
  PRIMARY KEY (`mrt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prc_mr_type`
--

INSERT INTO `prc_mr_type` (`mrt_id`, `mrt_name`) VALUES
(1, 'Produksi'),
(2, 'Service Order');

-- --------------------------------------------------------

--
-- Table structure for table `prc_pcv`
--

CREATE TABLE IF NOT EXISTS `prc_pcv` (
  `pcv_id` int(11) NOT NULL AUTO_INCREMENT,
  `pcv_no` varchar(20) NOT NULL,
  `pcv_date` date DEFAULT NULL,
  `pcv_printStat` int(1) NOT NULL DEFAULT '0',
  `pcv_printDate` date DEFAULT NULL,
  `pcv_lastprintDate` date NOT NULL,
  `pcv_printUser` int(4) NOT NULL DEFAULT '0',
  `pcv_printCounter` int(11) NOT NULL,
  `pcv_status` int(1) NOT NULL DEFAULT '0',
  `pcv_request` decimal(20,5) DEFAULT '0.00000',
  `pcv_realisasi` decimal(20,5) DEFAULT '0.00000',
  `pcv_receiveDate` date DEFAULT NULL,
  `pcv_receiveUser` int(4) NOT NULL DEFAULT '0',
  `pcv_closeDate` date DEFAULT NULL,
  `pcv_closeUser` int(4) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `pr_type` set('pr','sr') NOT NULL DEFAULT 'pr',
  PRIMARY KEY (`pcv_id`),
  UNIQUE KEY `pcv_no` (`pcv_no`),
  KEY `pcv_status` (`pcv_status`),
  KEY `pr_id` (`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_pcv`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_pcv_receive`
--

CREATE TABLE IF NOT EXISTS `prc_pcv_receive` (
  `pcv_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `qty` decimal(20,5) DEFAULT NULL,
  `price` decimal(20,5) DEFAULT '0.00000',
  `cur_id` int(2) DEFAULT '0',
  PRIMARY KEY (`pcv_id`,`pro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_pcv_receive`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_po`
--

CREATE TABLE IF NOT EXISTS `prc_po` (
  `po_id` int(11) NOT NULL AUTO_INCREMENT,
  `po_no` varchar(20) DEFAULT NULL,
  `po_date` datetime DEFAULT NULL,
  `sup_id` int(11) NOT NULL,
  `po_printStat` int(1) DEFAULT '0',
  `po_printDate` date NOT NULL DEFAULT '2007-01-01',
  `po_lastprintDate` date NOT NULL,
  `po_printUser` int(4) NOT NULL DEFAULT '0',
  `po_printCounter` int(11) NOT NULL,
  `po_status` int(1) DEFAULT '0',
  `po_closeDate` date DEFAULT NULL,
  `term_id` int(2) NOT NULL DEFAULT '0',
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `po_note` mediumtext,
  `session_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`po_id`),
  UNIQUE KEY `rfq_no` (`po_no`),
  KEY `rfq_status` (`po_status`),
  KEY `po_status` (`po_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=4 ;

--
-- Dumping data for table `prc_po`
--

INSERT INTO `prc_po` (`po_id`, `po_no`, `po_date`, `sup_id`, `po_printStat`, `po_printDate`, `po_lastprintDate`, `po_printUser`, `po_printCounter`, `po_status`, `po_closeDate`, `term_id`, `cur_id`, `po_note`, `session_id`) VALUES
(1, '10/03/PO0001', '2010-03-27 00:00:00', 3, 1, '2010-03-27', '2010-03-27', 1, 1, 1, '2010-03-27', 2, 2, NULL, ''),
(2, '10/03/PO0002', '2010-03-28 00:00:00', 3, 1, '2010-03-28', '2010-03-28', 1, 1, 1, '2010-03-28', 2, 2, NULL, ''),
(3, '10/03/PO0003', '2010-03-28 00:00:00', 1, 1, '2010-03-28', '2010-03-28', 1, 1, 1, '2010-03-29', 1, 2, 'parah ui', '');

-- --------------------------------------------------------

--
-- Table structure for table `prc_po_service`
--

CREATE TABLE IF NOT EXISTS `prc_po_service` (
  `so_id` int(11) NOT NULL AUTO_INCREMENT,
  `so_no` varchar(20) DEFAULT NULL,
  `so_date` datetime DEFAULT NULL,
  `sup_id` int(11) NOT NULL,
  `so_printStat` int(1) DEFAULT '0',
  `so_printDate` date NOT NULL DEFAULT '2007-01-01',
  `so_lastprintDate` date NOT NULL,
  `so_printUser` int(4) NOT NULL DEFAULT '0',
  `so_printCounter` int(11) NOT NULL,
  `so_status` int(1) DEFAULT '0',
  `so_closeDate` date DEFAULT NULL,
  `term_id` int(2) NOT NULL DEFAULT '0',
  `cur_id` int(2) NOT NULL,
  `so_note` mediumtext,
  `session_id` varchar(40) NOT NULL,
  PRIMARY KEY (`so_id`),
  UNIQUE KEY `rfq_service_no` (`so_no`),
  KEY `rfq_service_status` (`so_status`),
  KEY `so_status` (`so_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_po_service`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_pr`
--

CREATE TABLE IF NOT EXISTS `prc_pr` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_no` varchar(50) DEFAULT NULL,
  `pr_date` date NOT NULL,
  `planStat` int(1) NOT NULL DEFAULT '0',
  `is_approved` int(1) NOT NULL DEFAULT '0',
  `pr_lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pr_requestor` int(4) NOT NULL,
  `plan_id` int(2) NOT NULL DEFAULT '1',
  `pr_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pr_id`),
  KEY `plan_id` (`plan_id`),
  KEY `pr_requestor` (`pr_requestor`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prc_pr`
--

INSERT INTO `prc_pr` (`pr_id`, `pr_no`, `pr_date`, `planStat`, `is_approved`, `pr_lastModified`, `pr_requestor`, `plan_id`, `pr_status`) VALUES
(1, '10/03/PR0001', '2010-03-27', 0, 0, '2010-03-27 03:03:54', 1, 1, 1),
(2, '10/03/PR0002', '2010-03-28', 0, 0, '2010-03-28 12:03:06', 1, 1, 1),
(3, '10/03/PR0003', '2010-03-28', 0, 0, '2010-03-28 03:03:25', 1, 1, 1),
(4, '10/03/PR0004', '2010-03-29', 0, 0, '2010-03-29 01:03:07', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prc_pr_detail`
--

CREATE TABLE IF NOT EXISTS `prc_pr_detail` (
  `pro_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `buy_via` set('po/pcv','po','pcv') NOT NULL DEFAULT '',
  `pty_id` int(2) NOT NULL DEFAULT '0',
  `proj_no` varchar(50) DEFAULT NULL,
  `emergencyStat` int(1) NOT NULL DEFAULT '0',
  `qty` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `qty_terima` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `qty_retur` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `delivery_date` date DEFAULT NULL,
  `description` mediumtext,
  `num_supplier` int(2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `price_pre` decimal(20,5) DEFAULT '0.00000',
  `price` decimal(20,5) DEFAULT '0.00000',
  `term` int(2) NOT NULL DEFAULT '0',
  `rfq_delivery_date` date DEFAULT NULL,
  `discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `kurs` decimal(8,5) NOT NULL DEFAULT '0.00000',
  `rfq_id` int(11) NOT NULL DEFAULT '0',
  `requestStat` int(1) NOT NULL DEFAULT '0',
  `rfq_stat` int(1) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `so_id` int(11) NOT NULL DEFAULT '0',
  `pcv_id` int(11) NOT NULL DEFAULT '0',
  `pcv_stat` int(1) NOT NULL DEFAULT '0',
  `pcv_note` mediumtext,
  `auth_no` varchar(10) DEFAULT NULL,
  `auth_note` text,
  `auth_qty` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `pr_appr_note` mediumtext,
  `is_po_fullfill` int(1) NOT NULL DEFAULT '0',
  `pr_reqTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pro_id`,`pr_id`),
  KEY `rfq_id` (`rfq_id`),
  KEY `pcv_id` (`pcv_id`),
  KEY `pcv_stat` (`pcv_stat`),
  KEY `rfq_stat` (`rfq_stat`),
  KEY `requestStat` (`requestStat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_pr_detail`
--

INSERT INTO `prc_pr_detail` (`pro_id`, `pr_id`, `buy_via`, `pty_id`, `proj_no`, `emergencyStat`, `qty`, `qty_terima`, `qty_retur`, `um_id`, `delivery_date`, `description`, `num_supplier`, `sup_id`, `cur_id`, `price_pre`, `price`, `term`, `rfq_delivery_date`, `discount`, `kurs`, `rfq_id`, `requestStat`, `rfq_stat`, `po_id`, `so_id`, `pcv_id`, `pcv_stat`, `pcv_note`, `auth_no`, `auth_note`, `auth_qty`, `pr_appr_note`, `is_po_fullfill`, `pr_reqTime`) VALUES
(4, 1, 'po', 1, NULL, 1, 5.00000, 5.00000, 0.00000, 3, '2010-03-28', 'Import ke gudang 1', 3, 3, 2, 0.00000, 40.00000, 2, '2010-03-28', 10.10, 0.00000, 1, 2, 5, 1, 0, 0, 0, NULL, NULL, NULL, 0.00000, NULL, 1, '2010-03-27 14:55:12'),
(1, 1, 'po', 1, NULL, 0, 4.00000, 4.00000, 0.00000, 3, '2010-03-30', 'Dari gudang 2', 3, 3, 2, 0.00000, 15.00000, 2, '2010-03-28', 0.00, 0.00000, 1, 2, 5, 1, 0, 0, 0, NULL, NULL, NULL, 0.00000, NULL, 1, '2010-03-27 15:31:23'),
(1, 2, 'po', 1, NULL, 0, 10.00500, 10.00500, 0.00000, 3, '2010-03-30', 'riri', 3, 3, 2, 0.00000, 88.05000, 2, '2010-03-28', 15.50, 0.00000, 2, 1, 5, 2, 0, 0, 0, NULL, NULL, NULL, 0.00000, NULL, 1, '2010-03-28 00:12:46'),
(4, 2, 'po', 1, NULL, 0, 20.50000, 20.50000, 0.00000, 3, '2010-03-31', 'ahrie', 3, 3, 2, 0.00000, 144.50000, 2, '2010-03-28', 20.50, 0.00000, 2, 1, 5, 2, 0, 0, 0, NULL, NULL, NULL, 0.00000, NULL, 1, '2010-03-28 00:13:20'),
(1, 3, 'po', 1, NULL, 0, 200.00000, 0.00000, 0.00000, 2, '2010-03-30', 'apa lo liat-liat, gw bt tau. mari kita pergi refreshing', 3, 1, 2, 0.00000, 1800.00000, 1, '2010-03-30', 0.00, 0.00000, 3, 3, 5, 3, 0, 0, 0, NULL, NULL, NULL, 0.00000, 'jangan lupa ya', 0, '2010-03-28 02:25:17'),
(4, 3, 'po', 1, NULL, 0, 150.00000, 0.00000, 0.00000, 3, '2010-03-30', 'Ga tau agh, dah pusing gw di buat nya', NULL, NULL, 0, 0.00000, 0.00000, 0, NULL, 0.00, 0.00000, 0, 5, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0.00000, NULL, 0, '2010-03-28 02:25:21'),
(1, 4, 'po', 1, NULL, 1, 10.00000, 0.00000, 0.00000, 3, '2010-03-29', 'gudang 1', 3, NULL, 0, 0.00000, 0.00000, 0, NULL, 0.00, 0.00000, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0.00000, 'apa an nih', 0, '2010-03-28 23:03:59'),
(4, 4, 'po', 1, NULL, 0, 20.00000, 0.00000, 0.00000, 3, '2010-03-29', 'gudang 2', NULL, NULL, 0, 0.00000, 0.00000, 0, NULL, 0.00, 0.00000, 0, 5, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0.00000, 'Ga jelas looo', 0, '2010-03-28 23:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `prc_pr_detail_history`
--

CREATE TABLE IF NOT EXISTS `prc_pr_detail_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `buy_via` set('po/pcv','po','pcv') NOT NULL DEFAULT 'po',
  `pty_id` int(2) NOT NULL DEFAULT '0',
  `proj_no` varchar(50) DEFAULT NULL,
  `emergencyStat` int(1) NOT NULL DEFAULT '0',
  `qty` decimal(20,5) DEFAULT '0.00000',
  `qty_remain` decimal(20,5) DEFAULT '0.00000',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `delivery_date` date DEFAULT NULL,
  `description` mediumtext,
  `num_supplier` int(2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `price` decimal(20,5) DEFAULT '0.00000',
  `pay_id` int(2) DEFAULT NULL,
  `term` int(2) NOT NULL DEFAULT '0',
  `rfq_delivery_date` date DEFAULT NULL,
  `rfq_id` int(11) NOT NULL DEFAULT '0',
  `requestStat` int(1) NOT NULL DEFAULT '0',
  `rfq_stat` int(1) NOT NULL DEFAULT '0',
  `pr_usr` int(4) DEFAULT '0',
  `pr_appr_note` mediumtext,
  `pr_appr_user` int(11) NOT NULL,
  `rfq_usr` int(4) DEFAULT '0',
  `rfq_usr_note` mediumtext,
  `so_id` int(11) NOT NULL DEFAULT '0',
  `pcv_id` int(11) NOT NULL DEFAULT '0',
  `pcv_stat` tinyint(1) NOT NULL DEFAULT '0',
  `pcv_note` text NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pro_id` (`pro_id`),
  KEY `pr_id` (`pr_id`),
  KEY `rfq_usr` (`rfq_usr`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `prc_pr_detail_history`
--

INSERT INTO `prc_pr_detail_history` (`id`, `pro_id`, `pr_id`, `buy_via`, `pty_id`, `proj_no`, `emergencyStat`, `qty`, `qty_remain`, `um_id`, `delivery_date`, `description`, `num_supplier`, `sup_id`, `cur_id`, `price`, `pay_id`, `term`, `rfq_delivery_date`, `rfq_id`, `requestStat`, `rfq_stat`, `pr_usr`, `pr_appr_note`, `pr_appr_user`, `rfq_usr`, `rfq_usr_note`, `so_id`, `pcv_id`, `pcv_stat`, `pcv_note`, `lastupdate`) VALUES
(1, 4, 1, 'po', 1, NULL, 1, 5.00000, 0.00000, 3, '2010-03-28', 'Import ke gudang 1', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-27 15:39:36'),
(2, 1, 1, 'po', 1, NULL, 0, 4.00000, 0.00000, 3, '2010-03-30', 'Dari gudang 2', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-27 15:39:36'),
(3, 4, 1, 'po', 1, NULL, 0, 5.00000, 0.00000, 3, '2010-03-28', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 1, 0, 0, NULL, 1, 0, NULL, 0, 0, 0, '', '2010-03-27 15:39:54'),
(4, 1, 1, 'po', 1, NULL, 0, 4.00000, 0.00000, 3, '2010-03-30', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 1, 0, 0, NULL, 1, 0, NULL, 0, 0, 0, '', '2010-03-27 15:39:54'),
(5, 1, 2, 'po', 1, NULL, 0, 10.00500, 0.00000, 3, '2010-03-30', 'riri', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-28 00:13:51'),
(6, 4, 2, 'po', 1, NULL, 0, 20.50000, 0.00000, 3, '2010-03-31', 'ahrie', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-28 00:13:51'),
(7, 1, 2, 'po', 1, NULL, 0, 10.00500, 0.00000, 3, '2010-03-30', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 1, 0, 0, NULL, 1, 0, NULL, 0, 0, 0, '', '2010-03-28 00:16:06'),
(8, 4, 2, 'po', 1, NULL, 0, 20.50000, 0.00000, 3, '2010-03-31', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 1, 0, 0, NULL, 1, 0, NULL, 0, 0, 0, '', '2010-03-28 00:16:06'),
(9, 1, 3, 'po', 1, NULL, 0, 200.00000, 0.00000, 2, '2010-03-30', 'apa lo liat-liat, gw bt tau. mari kita pergi refreshing', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-28 02:26:17'),
(10, 4, 3, 'po', 1, NULL, 0, 150.00000, 0.00000, 3, '2010-03-30', 'Ga tau agh, dah pusing gw di buat nya', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-28 02:26:17'),
(11, 1, 3, 'po', 1, NULL, 0, 200.00000, 0.00000, 2, '2010-03-30', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 3, 0, 0, 'jangan lupa ya', 1, 0, NULL, 0, 0, 0, '', '2010-03-28 02:33:25'),
(12, 4, 3, 'po', 1, NULL, 0, 150.00000, 0.00000, 3, '2010-03-30', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 5, 0, 0, 'Ga perlu sekarang ui, lu bisa cari lagi dah egh order lagi', 1, 0, NULL, 0, 0, 0, '', '2010-03-28 02:33:25'),
(13, 1, 4, 'po', 1, NULL, 1, 10.00000, 0.00000, 3, '2010-03-29', 'gudang 1', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-28 23:04:31'),
(14, 4, 4, 'po', 1, NULL, 0, 20.00000, 0.00000, 3, '2010-03-29', 'gudang 2', NULL, NULL, 0, 0.00000, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, 0, NULL, 0, 0, 0, '', '2010-03-28 23:04:31'),
(15, 1, 4, 'po', 1, NULL, 1, 10.00000, 0.00000, 3, '2010-03-29', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 2, 0, 0, 'apa an nih', 1, 0, NULL, 0, 0, 0, '', '2010-03-29 00:40:07'),
(16, 4, 4, 'po', 1, NULL, 0, 20.00000, 0.00000, 3, '2010-03-29', NULL, 3, NULL, 0, 0.00000, NULL, 0, NULL, 0, 5, 0, 0, 'Ga jelas looo', 1, 0, NULL, 0, 0, 0, '', '2010-03-29 00:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `prc_rfq`
--

CREATE TABLE IF NOT EXISTS `prc_rfq` (
  `rfq_id` int(11) NOT NULL AUTO_INCREMENT,
  `rfq_no` varchar(20) NOT NULL,
  `rfq_date` date NOT NULL,
  `rfq_lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfq_printStat` int(1) NOT NULL DEFAULT '0',
  `rfq_printDate` date NOT NULL DEFAULT '2007-01-01',
  `rfq_printCountDate` date NOT NULL DEFAULT '1978-02-26',
  `rfq_printCount` int(11) DEFAULT NULL,
  `rfq_printUsr` int(4) NOT NULL DEFAULT '0',
  `rfq_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rfq_id`),
  UNIQUE KEY `rfq_no` (`rfq_no`),
  KEY `rfq_status` (`rfq_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `prc_rfq`
--

INSERT INTO `prc_rfq` (`rfq_id`, `rfq_no`, `rfq_date`, `rfq_lastModified`, `rfq_printStat`, `rfq_printDate`, `rfq_printCountDate`, `rfq_printCount`, `rfq_printUsr`, `rfq_status`) VALUES
(1, '10/03/RFQ0001', '2010-03-27', '2010-03-27 15:40:25', 1, '2010-03-27', '1978-02-26', NULL, 1, 0),
(2, '10/03/RFQ0002', '2010-03-28', '2010-03-28 00:16:33', 1, '2010-03-28', '1978-02-26', NULL, 1, 0),
(3, '10/03/RFQ0003', '2010-03-28', '2010-03-28 02:33:48', 1, '2010-03-28', '1978-02-26', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prc_rfq_service`
--

CREATE TABLE IF NOT EXISTS `prc_rfq_service` (
  `srfq_id` int(11) NOT NULL AUTO_INCREMENT,
  `srfq_no` varchar(20) NOT NULL,
  `srfq_date` date NOT NULL,
  `srfq_lastModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `srfq_printStat` int(1) NOT NULL DEFAULT '0',
  `srfq_printDate` date NOT NULL DEFAULT '2007-01-01',
  `srfq_printUsr` int(4) NOT NULL DEFAULT '0',
  `srfq_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`srfq_id`),
  UNIQUE KEY `rfq_no` (`srfq_no`),
  KEY `rfq_status` (`srfq_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_rfq_service`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_satuan_produk`
--

CREATE TABLE IF NOT EXISTS `prc_satuan_produk` (
  `pro_id` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL,
  `satuan_unit_id` int(11) NOT NULL,
  `value` decimal(8,5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_satuan_produk`
--

INSERT INTO `prc_satuan_produk` (`pro_id`, `satuan_id`, `satuan_unit_id`, `value`) VALUES
(1, 2, 2, 1.00000),
(1, 2, 3, 12.30000),
(2, 2, 2, 1.00000),
(2, 2, 3, 15.50000),
(3, 2, 2, 1.00000),
(3, 2, 3, 10.00000),
(4, 2, 2, 1.00000),
(4, 2, 3, 12.50000);

-- --------------------------------------------------------

--
-- Table structure for table `prc_so`
--

CREATE TABLE IF NOT EXISTS `prc_so` (
  `so_id` int(11) NOT NULL AUTO_INCREMENT,
  `so_no` varchar(20) DEFAULT NULL,
  `so_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sup_id` int(11) NOT NULL,
  `so_printStat` int(1) NOT NULL DEFAULT '0',
  `so_printDate` date NOT NULL DEFAULT '2007-01-01',
  `so_lastprintDate` date NOT NULL,
  `so_printUser` int(4) NOT NULL DEFAULT '0',
  `so_printCounter` int(11) NOT NULL,
  `so_status` int(1) NOT NULL DEFAULT '0',
  `term_id` int(11) NOT NULL DEFAULT '0',
  `cur_id` int(11) NOT NULL DEFAULT '0',
  `so_cost` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `so_type` set('cash','dp','credit') NOT NULL DEFAULT 'credit',
  `so_due_date` date NOT NULL DEFAULT '1978-02-26',
  `so_note` mediumtext,
  `session_id` varchar(40) NOT NULL,
  PRIMARY KEY (`so_id`),
  UNIQUE KEY `rfq_no` (`so_no`),
  KEY `rfq_status` (`so_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_so`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_sr`
--

CREATE TABLE IF NOT EXISTS `prc_sr` (
  `sr_id` int(11) NOT NULL AUTO_INCREMENT,
  `sr_no` varchar(50) DEFAULT NULL,
  `sr_date` date DEFAULT NULL,
  `is_approved` int(1) NOT NULL DEFAULT '0',
  `sr_lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sr_requestor` int(4) DEFAULT NULL,
  `plan_id` int(2) NOT NULL DEFAULT '1',
  `sr_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sr_id`),
  KEY `is_approved` (`is_approved`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_sr`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_sr_detail`
--

CREATE TABLE IF NOT EXISTS `prc_sr_detail` (
  `pro_id` int(11) NOT NULL,
  `sr_id` int(11) NOT NULL DEFAULT '0',
  `service_cat` set('in','out') NOT NULL DEFAULT 'in',
  `service_type` set('maintain','repair') NOT NULL DEFAULT 'repair',
  `qty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `description` mediumtext,
  `num_supplier` int(2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `pay_id` int(2) DEFAULT NULL,
  `term` int(2) NOT NULL DEFAULT '0',
  `srfq_delivery_date` date DEFAULT NULL,
  `srfq_id` int(11) NOT NULL DEFAULT '0',
  `requestStat` int(1) NOT NULL DEFAULT '0',
  `srfq_stat` int(1) NOT NULL DEFAULT '0',
  `so_id` int(11) NOT NULL DEFAULT '0',
  `auth_no` varchar(10) DEFAULT NULL,
  `is_so_fullfill` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pro_id`,`sr_id`),
  KEY `rfq_id` (`srfq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_sr_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_sr_detail_history`
--

CREATE TABLE IF NOT EXISTS `prc_sr_detail_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_id` int(11) NOT NULL,
  `sr_id` int(11) DEFAULT NULL,
  `service_cat` set('in','out') NOT NULL DEFAULT 'in',
  `service_type` set('maintain','repair') NOT NULL DEFAULT 'repair',
  `qty` decimal(15,5) DEFAULT '0.00000',
  `um_id` int(2) NOT NULL DEFAULT '0',
  `description` mediumtext,
  `num_supplier` int(2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `cur_id` int(2) NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `pay_id` int(2) DEFAULT NULL,
  `term` int(2) NOT NULL DEFAULT '0',
  `srfq_delivery_date` date DEFAULT NULL,
  `srfq_id` int(11) NOT NULL DEFAULT '0',
  `requestStat` int(1) NOT NULL DEFAULT '0',
  `srfq_stat` int(1) NOT NULL DEFAULT '0',
  `sr_usr` int(4) DEFAULT '0',
  `sr_usr_note` mediumtext,
  `srfq_usr` int(4) DEFAULT '0',
  `srfq_usr_note` mediumtext,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pro_id` (`pro_id`),
  KEY `pr_id` (`sr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_sr_detail_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_sys_client`
--

CREATE TABLE IF NOT EXISTS `prc_sys_client` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) NOT NULL,
  `client_legal` varchar(10) NOT NULL,
  `module_program` varchar(50) NOT NULL,
  `module_type` varchar(50) NOT NULL,
  `module_version` varchar(6) NOT NULL,
  `module_revision` int(11) NOT NULL,
  `module_package` varchar(50) NOT NULL DEFAULT 'Development',
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prc_sys_client`
--

INSERT INTO `prc_sys_client` (`client_id`, `client_name`, `client_legal`, `module_program`, `module_type`, `module_version`, `module_revision`, `module_package`, `image`) VALUES
(1, 'DEMO', '', 'MATERIAL MANAGEMENT MODULE', 'NON PPN', '2.0.0.', 343, 'Development', '');

-- --------------------------------------------------------

--
-- Table structure for table `prc_sys_counter`
--

CREATE TABLE IF NOT EXISTS `prc_sys_counter` (
  `thn` year(4) NOT NULL DEFAULT '0000',
  `bln` int(2) NOT NULL DEFAULT '1',
  `pr_no` int(11) NOT NULL DEFAULT '1',
  `mr_no` int(11) NOT NULL DEFAULT '1',
  `rfq_no` int(11) NOT NULL DEFAULT '1',
  `po_no` int(11) NOT NULL DEFAULT '1',
  `rec_no` int(11) NOT NULL DEFAULT '1',
  `ret_no` int(11) NOT NULL DEFAULT '1',
  `grl_no` int(11) NOT NULL DEFAULT '1',
  `sr_no` int(11) NOT NULL DEFAULT '1',
  `srfq_no` int(11) NOT NULL DEFAULT '1',
  `so_no` int(11) NOT NULL DEFAULT '1',
  `pcv_no` int(11) NOT NULL DEFAULT '1',
  `cash_no` int(11) NOT NULL DEFAULT '1',
  `con_no` int(11) NOT NULL DEFAULT '1',
  `adj_no` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_sys_counter`
--

INSERT INTO `prc_sys_counter` (`thn`, `bln`, `pr_no`, `mr_no`, `rfq_no`, `po_no`, `rec_no`, `ret_no`, `grl_no`, `sr_no`, `srfq_no`, `so_no`, `pcv_no`, `cash_no`, `con_no`, `adj_no`) VALUES
(2010, 3, 5, 2, 4, 4, 5, 1, 2, 1, 1, 1, 1, 1, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `prc_sys_menu`
--

CREATE TABLE IF NOT EXISTS `prc_sys_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_parent` int(11) NOT NULL DEFAULT '0',
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_path` mediumtext,
  `menu_icon` varchar(255) NOT NULL,
  `sorter` int(11) NOT NULL,
  `subsorter` int(11) NOT NULL,
  `modulstat` varchar(1) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=125 ;

--
-- Dumping data for table `prc_sys_menu`
--

INSERT INTO `prc_sys_menu` (`menu_id`, `menu_parent`, `menu_name`, `menu_path`, `menu_icon`, `sorter`, `subsorter`, `modulstat`) VALUES
(1, 0, 'Administrator', NULL, 'config.png', 1, 0, '0'),
(2, 1, 'Kategori', 'index.php/mod_master/master_category/index', 'controlpanel.png', 0, 1, '2'),
(3, 1, 'Kelas', 'index.php/mod_master/master_kelas/index', 'controlpanel.png', 0, 2, '2'),
(4, 1, 'Grup', 'index.php/mod_master/master_grup/index', 'controlpanel.png', 0, 3, '2'),
(5, 1, 'Produk', 'index.php/mod_master/master_produk/index', 'controlpanel.png', 0, 4, '2'),
(6, 1, 'Departemen', 'index.php/mod_master/master_departemen/index', 'controlpanel.png', 0, 6, '2'),
(7, 1, 'Jabatan', 'index.php/mod_master/master_jabatan/index', 'controlpanel.png', 0, 7, '2'),
(8, 1, 'User', 'index.php/mod_master/master_user/index', 'controlpanel.png', 0, 8, '2'),
(9, 1, 'Catatan Print', 'index.php/mod_master/master_rpt_note/fckeditorform/index', 'controlpanel.png', 0, 9, '2'),
(10, 0, 'Pengaturan', NULL, 'controlpanel.png', 2, 0, '0'),
(11, 10, 'Satuan', 'index.php/mod_setup/setup_satuan/index', 'controlpanel.png', 0, 1, '2'),
(12, 10, 'Kredit term', 'index.php/mod_setup/setup_term/index', 'controlpanel.png', 0, 2, '2'),
(13, 10, 'Bank', 'index.php/mod_setup/setup_bank/index', 'controlpanel.png', 0, 3, '2'),
(14, 10, 'Lokasi', 'index.php/mod_setup/setup_lokasi/index', 'controlpanel.png', 0, 4, '2'),
(15, 10, 'Pemasok', 'index.php/mod_setup/setup_supplier/index', 'controlpanel.png', 0, 5, '2'),
(16, 10, 'Kontak person', 'index.php/mod_setup/setup_contact/index', 'controlpanel.png', 0, 6, '2'),
(17, 10, 'Aktivasi produk (Stok)', 'index.php/mod_setup/setup_aktivasi/index', 'controlpanel.png', 0, 7, '2'),
(18, 10, 'Aktivasi produk (Harga)', 'index.php/mod_setup/setup_aktivasi_accounting/index', 'controlpanel.png', 0, 8, '2'),
(19, 10, 'Penyesuaian stok', 'index.php/mod_setup/setup_adjustment/index', 'controlpanel.png', 0, 9, '2'),
(20, 10, 'Posting hutang', 'index.php/mod_setup/setup_posting_hutang/index', 'controlpanel.png', 0, 10, '2'),
(21, 0, 'Laporan', NULL, 'license.png', 3, 0, '0'),
(22, 21, 'Cek status permohonan', 'index.php/mod_report/report_stats/index', 'license.png', 0, 1, '2'),
(23, 21, 'Status PO', 'index.php/mod_report/report_po/index', 'license.png', 0, 2, '2'),
(24, 21, 'Kas Kecil', 'index.php/mod_report/report_pcv/index', 'license.png', 0, 3, '2'),
(25, 21, 'Kontra bon', 'index.php/mod_report/report_contrabon/index', 'license.png', 0, 4, '2'),
(26, 21, 'Penerimaan barang', 'index.php/mod_report/report_gr/index', 'license.png', 0, 5, '2'),
(27, 21, 'Penerimaan barang (Summary)', 'index.php/mod_report/report_penerimaan_summary/index', 'license.png', 0, 6, '2'),
(28, 21, 'Pemesanan barang', 'index.php/mod_report/report_pesan/index', 'license.png', 0, 7, '2'),
(29, 21, 'Persediaan barang', 'index.php/mod_report/report_inv/index', 'license.png', 0, 8, '2'),
(30, 21, 'Pemakaian barang', 'index.php/mod_report/report_inv_mr/index', 'license.png', 0, 9, '2'),
(31, 21, 'PO perkategori', 'index.php/mod_report/report_poperkategori/index', 'license.png', 0, 10, '2'),
(32, 21, 'Penelusuran Proses', 'asd', 'license.png', 0, 11, '2'),
(33, 21, 'Hutang Ke Pemasok', 'index.php/mod_report/report_hutang_ke_pemasok/index', 'license.png', 0, 12, '2'),
(34, 21, 'Pembayaran ke Pemasok', 'index.php/mod_report/report_pembayaran_ke_pemasok/index', 'license.png', 0, 13, '2'),
(35, 0, 'Cetak', NULL, 'print.png', 4, 0, '0'),
(121, 35, 'Original', 'index.php/mod_printing/printing_rfq/index', 'print.png', 0, 1, '2'),
(36, 121, 'RFQ', 'index.php/mod_printing/printing_rfq/index', 'print.png', 0, 1, '2'),
(37, 121, 'PO', 'index.php/mod_printing/printing_po/index', 'print.png', 0, 2, '2'),
(38, 121, 'BPB', 'index.php/mod_printing/printing_bpb/index', 'print.png', 0, 3, '2'),
(39, 121, 'Kontra bon', 'index.php/mod_printing/printing_contrabon/index', 'print.png', 0, 4, '2'),
(45, 121, 'Pembayaran', 'index.php/mod_printing/printing_payment/index', 'print.png', 0, 5, '2'),
(40, 121, 'Form keluar barang ', 'index.php/mod_printing/printing_goodrelease/index', 'print.png', 0, 6, '2'),
(41, 121, 'Petty cash', 'index.php/mod_printing/printing_pcv/index', 'print.png', 0, 7, '2'),
(42, 121, 'Retur barang', 'index.php/mod_printing/printing_goodreturn/index', 'print.png', 0, 8, '2'),
(43, 121, 'RFQ service', 'index.php/mod_printing/printing_rfq_service/index', 'print.png', 0, 9, '2'),
(44, 121, 'Service order', 'index.php/mod_printing/printing_so/index', 'print.png', 0, 10, '2'),
(63, 35, 'Duplikasi', NULL, 'print.png', 0, 2, '2'),
(64, 63, 'RFQ', 'index.php/mod_printing/printing_rfq/index/1', 'print.png', 0, 1, '2'),
(65, 63, 'PO', 'index.php/mod_printing/printing_po/index/1', 'print.png', 0, 2, '2'),
(66, 63, 'BPB', 'index.php/mod_printing/printing_bpb/index/1', 'print.png', 0, 3, '2'),
(68, 63, 'Kontra Bon', 'index.php/mod_printing/printing_contrabon/index/1', 'print.png', 0, 4, '2'),
(124, 63, 'Pembayaran', 'index.php/mod_printing/printing_payment/index/1', 'print.png', 0, 5, '2'),
(67, 63, 'Form Keluar Barang', 'index.php/mod_goodrelease_printing/goodrelease/index/1', 'print.png', 0, 6, '2'),
(69, 63, 'Petty Cash', 'index.php/mod_printing/printing_pcv/index/1', 'print.png', 0, 7, '2'),
(71, 63, 'Retur Barang', 'index.php/mod_printing/printing_goodreturn/index/1', 'print.png', 0, 8, '2'),
(122, 63, 'RFQ service', 'index.php/mod_printing/printing_rfq_service/index/1', 'print.png', 0, 9, '2'),
(123, 63, 'Service order', 'index.php/mod_printing/printing_so/index/1', 'print.png', 0, 10, '2'),
(46, 0, 'Persetujuan', NULL, 'mainmenu.png', 5, 0, '0'),
(47, 46, 'PR', 'index.php/mod_approval/appr_pr/index', 'controlpanel.png', 0, 1, '2'),
(48, 46, 'RFQ', 'index.php/mod_approval/appr_rfq/index', 'controlpanel.png', 0, 2, '2'),
(49, 46, 'MR', 'index.php/mod_approval/appr_mr/index', 'controlpanel.png', 0, 3, '2'),
(50, 46, 'Petty cash', 'index.php/mod_approval/appr_pcv/index', 'controlpanel.png', 0, 4, '2'),
(51, 46, 'Penyesuaian stok', 'index.php/mod_approval/appr_adjustment/index', 'controlpanel.png', 0, 5, '2'),
(52, 46, 'Penyesuaian harga', 'index.php/mod_approval/appr_adjustment_po/index', 'controlpanel.png', 0, 6, '2'),
(53, 46, 'Retur Barang', 'index.php/mod_approval/appr_good_return/index', 'controlpanel.png', 0, 7, '2'),
(54, 46, 'Permintaan servis', 'index.php/mod_approval/appr_sr/index', 'controlpanel.png', 0, 8, '2'),
(55, 46, 'RFQ servis final', 'index.php/mod_approval/appr_rfq_service/index', 'controlpanel.png', 0, 9, '2'),
(56, 0, 'Daftar', NULL, 'content.png', 6, 0, '0'),
(57, 56, 'Produk', NULL, 'controlpanel.png', 0, 1, '2'),
(58, 57, 'Statistik harga', 'index.php/mod_list/list_statistik/index', 'controlpanel.png', 0, 2, '2'),
(59, 57, 'PR', 'index.php/mod_list/list_pr/index', 'controlpanel.png', 0, 3, '2'),
(60, 57, 'PO masih buka', 'index.php/mod_list/list_po_buka/index', 'controlpanel.png', 0, 5, '2'),
(61, 57, 'Daftar BPB', 'index.php/mod_list/list_good_receive', 'controlpanel.png', 0, 6, '2'),
(62, 57, 'Daftar BPB Otorisasi', 'index.php/mod_entry/entry_good_receive/index/gr_auth_list', 'controlpanel.png', 0, 7, '2'),
(70, 57, 'RFQ akan di setujui', 'index.php/mod_list/list_rfqtoapp/index', 'controlpanel.png', 0, 4, '2'),
(72, 0, 'Input data', NULL, 'edit.png', 7, 0, '0'),
(73, 72, 'Servis', NULL, 'controlpanel.png', 0, 1, '2'),
(74, 73, 'Cek status barang digudang', 'index.php/mod_service/service_inventory/index', 'controlpanel.png', 0, 2, '2'),
(75, 73, 'Ubah SR ke RFQ servis', 'index.php/mod_service/service_sr_rfq/index', 'controlpanel.png', 0, 3, '2'),
(76, 73, 'Input RFQ servis final', 'index.php/mod_service/service_rfq/index', 'controlpanel.png', 0, 4, '2'),
(77, 72, 'Produk', NULL, 'controlpanel.png', 0, 5, '2'),
(78, 77, 'Akunting', 'Akunting', 'controlpanel.png', 0, 6, '2'),
(79, 78, 'Buat kontra bon', 'index.php/mod_entry/entry_contrabon/index', 'controlpanel.png', 0, 7, '2'),
(80, 78, 'Buat pembayaran', 'index.php/mod_entry/entry_payment/index', 'controlpanel.png', 0, 8, '2'),
(81, 77, 'Gudang', NULL, 'controlpanel.png', 0, 9, '2'),
(82, 81, 'Input BPB', 'index.php/mod_entry/entry_good_receive/index/gr_input', 'controlpanel.png', 0, 10, '2'),
(83, 81, 'Terima barang lewat petty cash', 'index.php/mod_entry/entry_pcv_receive/index', 'controlpanel.png', 0, 11, '2'),
(84, 81, 'Keluarkan barang', 'index.php/mod_entry/entry_goodrelease_realisasi/index', 'controlpanel.png', 0, 12, '2'),
(85, 81, 'Input data pemakaian barang', 'index.php/mod_entry/entry_pakai/index', 'controlpanel.png', 0, 13, '2'),
(86, 81, 'Kembalikan barang sisa pemakaian', 'index.php/mod_entry/entry_retur/index', 'controlpanel.png', 0, 14, '2'),
(87, 77, 'Pembelian', 'Pembelian', 'controlpanel.png', 0, 15, '2'),
(88, 87, 'Ubah PR ke RFQ', 'index.php/mod_entry/entry_pr_rfq/index', 'controlpanel.png', 0, 16, '2'),
(89, 87, 'Input RFQ final', 'index.php/mod_entry/entry_rfq/index', 'controlpanel.png', 0, 17, '2'),
(90, 87, 'Buat otorisasi BPB', 'index.php/mod_entry/entry_good_receive/index/gr_auth', 'controlpanel.png', 0, 18, '2'),
(91, 87, 'Cek BPB kurs', 'index.php/mod_entry/entry_good_receive/index_cbpb/kurs', 'controlpanel.png', 0, 19, '2'),
(92, 87, 'Cek BPB final', 'index.php/mod_entry/entry_good_receive/index_cbpb/final', 'controlpanel.png', 0, 20, '2'),
(93, 87, 'Input harga realisasi pada petty cash', 'index.php/mod_entry/entry_pcv_realisasi/index', 'controlpanel.png', 0, 21, '2'),
(94, 87, 'Kembalikan barang ke pemasok', 'index.php/mod_entry/entry_good_receive/index/good_return', 'controlpanel.png', 0, 22, '2'),
(95, 77, 'User', NULL, 'controlpanel.png', 0, 23, '2'),
(96, 95, 'Cek status barang digudang', 'index.php/mod_entry/entry_inventory/index', 'controlpanel.png', 0, 24, '2'),
(97, 95, 'Input harga perkiraan pada petty cash', 'index.php/mod_entry/entry_pcv_perkiraan/index', 'controlpanel.png', 0, 25, '2'),
(98, 21, 'Daftar pemasok', 'index.php/mod_report/report_daftar_pemasok/index', 'license.png', 0, 16, '2'),
(99, 21, 'Daftar barang', 'index.php/mod_report/report_daftar_barang/index', 'license.png', 0, 17, '2'),
(100, 73, 'Servis final', 'index.php/mod_service/service_complate/index', 'controlpanel.png', 0, 18, '2'),
(101, 72, 'Sales', NULL, 'controlpanel.png', 0, 28, '1'),
(102, 72, 'MD', NULL, 'controlpanel.png', 0, 26, '1'),
(103, 102, 'Mini marker', 'index.php/sales/entry_minimarker', 'controlpanel.png', 0, 27, '1'),
(104, 101, 'Buat Simulasi Harga', 'index.php/sales/entry_simulasi', 'controlpanel.png', 0, 29, '1'),
(105, 101, 'Revisi penawaran Harga', 'index.php/sales/entry_revsimulasi', 'controlpanel.png', 0, 30, '1'),
(106, 101, 'Konversi ke Proforma Invoice', 'index.php/sales/entry_konversi', 'controlpanel.png', 0, 31, '1'),
(107, 56, 'Daftar Surat Simulasi Harga Edit', 'index.php/sales/entry_edit_simulasi/', 'controlpanel.png ', 0, 16, '1'),
(108, 56, 'Daftar Surat Penawaran harga Cetak', 'index.php/sales/daftar_simulasi_print/', 'controlpanel.png', 0, 17, '1'),
(109, 56, 'Daftar Surat Proforma Invoice', 'index.php/sales/daftar_pi/', 'controlpanel.png', 0, 18, '1'),
(110, 46, 'Simulasi Harga', 'index.php/sales/approval_simulasi', 'controlpanel.png', 0, 10, '1'),
(111, 35, 'Surat Penawaran Harga Ke Pembeli', 'index.php/sales/print_simulasi', 'print.png', 0, 11, '1'),
(112, 35, 'Surat Penawaran Harga Detail', 'index.php/sales/print_simulasi_detail', 'print.png', 0, 12, '1'),
(113, 35, 'Surat Proforma Invoice', 'index.php/sales/print_pi', 'print.png', 0, 13, '1'),
(114, 1, 'Buyer', 'index.php/sales/master_buyer', 'controlpanel.png', 0, 10, '1'),
(115, 1, 'Charge', 'index.php/sales/master_charge', 'controlpanel.png', 0, 11, '1'),
(116, 1, 'profit', 'index.php/sales/master_profit', 'controlpanel.png', 0, 12, '1'),
(117, 1, 'template aksesoris', 'index.php/sales/master_tmp_accessories', 'controlpanel.png', 0, 13, '1'),
(118, 32, 'PR', 'index.php/mod_report/report_tracking/index', 'license.png', 0, 1, '2'),
(119, 32, 'MR', 'index.php/mod_report/report_tracking_mr/index', 'license.png', 0, 2, '2'),
(120, 46, 'Non aktif pemasok', 'index.php/mod_approval/appr_deactive_supplier/index', 'controlpanel.png', 0, 16, '2');

-- --------------------------------------------------------

--
-- Table structure for table `prc_sys_printnote`
--

CREATE TABLE IF NOT EXISTS `prc_sys_printnote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `var_name` varchar(25) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prc_sys_printnote`
--


-- --------------------------------------------------------

--
-- Table structure for table `prc_sys_user`
--

CREATE TABLE IF NOT EXISTS `prc_sys_user` (
  `usr_id` int(4) NOT NULL AUTO_INCREMENT,
  `usr_login` varchar(20) NOT NULL DEFAULT '',
  `usr_pwd1` varchar(50) DEFAULT NULL,
  `usr_pwd2` varchar(50) NOT NULL DEFAULT '',
  `usr_name` varchar(100) DEFAULT NULL,
  `usr_image` varchar(100) DEFAULT NULL,
  `dep_id` int(3) DEFAULT '0',
  `ttl_id` int(2) NOT NULL DEFAULT '0',
  `ucat_id` int(1) NOT NULL DEFAULT '1',
  `lastTime_log` datetime DEFAULT NULL,
  `lastIP_log` varchar(50) DEFAULT NULL,
  `newTime_log` datetime DEFAULT NULL,
  `newIP_log` varchar(50) DEFAULT NULL,
  `offTime_log` datetime DEFAULT NULL,
  `offIP_log` varchar(50) DEFAULT NULL,
  `login_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `usr_login` (`usr_login`),
  KEY `usr_name` (`usr_name`),
  KEY `dep_id` (`dep_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 ROW_FORMAT=COMPACT AUTO_INCREMENT=103 ;

--
-- Dumping data for table `prc_sys_user`
--

INSERT INTO `prc_sys_user` (`usr_id`, `usr_login`, `usr_pwd1`, `usr_pwd2`, `usr_name`, `usr_image`, `dep_id`, `ttl_id`, `ucat_id`, `lastTime_log`, `lastIP_log`, `newTime_log`, `newIP_log`, `offTime_log`, `offIP_log`, `login_status`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 'caf1a3dfb505ffed0d024130f58c5cfa', 'Administrator', 'e77b46164b.jpg', 1, 1, 8, '2010-03-29 10:07:21', '127.0.0.1', '2010-03-29 10:07:39', '127.0.0.1', '2010-03-29 11:07:33', '127.0.0.1', 0),
(102, 'ahrie', '202cb962ac59075b964b07152d234b70', 'caf1a3dfb505ffed0d024130f58c5cfa', 'Mr. Achri Kurniadi', 'fe1aa19785.jpg', 1, 1, 1, '2010-03-29 00:54:56', '127.0.0.1', '2010-03-29 00:55:14', '127.0.0.1', '2010-03-29 02:00:01', '127.0.0.1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prc_sys_user_menu`
--

CREATE TABLE IF NOT EXISTS `prc_sys_user_menu` (
  `usr_id` int(4) NOT NULL DEFAULT '0',
  `menu_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usr_id`,`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prc_sys_user_menu`
--

INSERT INTO `prc_sys_user_menu` (`usr_id`, `menu_id`) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 21),
(3, 22),
(3, 23),
(3, 24),
(3, 25),
(3, 26),
(3, 27),
(3, 28),
(3, 29),
(3, 30),
(3, 31),
(3, 32),
(3, 34),
(3, 35),
(3, 36),
(3, 37),
(3, 38),
(3, 41),
(3, 42),
(3, 43),
(3, 44),
(3, 46),
(3, 47),
(3, 48),
(3, 53),
(3, 54),
(3, 55),
(3, 56),
(3, 57),
(3, 58),
(3, 59),
(3, 60),
(3, 61),
(3, 62),
(3, 63),
(3, 64),
(3, 65),
(3, 66),
(3, 69),
(3, 70),
(3, 71),
(3, 72),
(3, 73),
(3, 74),
(3, 75),
(3, 76),
(3, 77),
(3, 81),
(3, 86),
(3, 87),
(3, 88),
(3, 89),
(3, 90),
(3, 91),
(3, 92),
(3, 93),
(3, 94),
(4, 10),
(4, 20),
(4, 21),
(4, 22),
(4, 24),
(4, 25),
(4, 26),
(4, 27),
(4, 32),
(4, 33),
(4, 34),
(4, 35),
(4, 38),
(4, 39),
(4, 44),
(4, 46),
(4, 55),
(4, 56),
(4, 57),
(4, 62),
(4, 63),
(4, 66),
(4, 70),
(4, 71),
(4, 72),
(4, 77),
(4, 78),
(4, 79),
(4, 80),
(5, 10),
(5, 20),
(5, 21),
(5, 24),
(5, 33),
(5, 34),
(5, 35),
(5, 39),
(5, 45),
(5, 56),
(5, 57),
(5, 61),
(5, 62),
(5, 63),
(5, 66),
(5, 68),
(5, 69),
(5, 70),
(5, 71),
(5, 72),
(5, 77),
(5, 78),
(5, 79),
(5, 80),
(6, 21),
(6, 25),
(6, 35),
(6, 39),
(6, 56),
(6, 57),
(6, 63),
(6, 68),
(6, 72),
(6, 77),
(6, 78),
(6, 79),
(9, 21),
(9, 26),
(9, 29),
(9, 30),
(9, 35),
(9, 38),
(9, 40),
(9, 42),
(9, 44),
(9, 46),
(9, 47),
(9, 49),
(9, 56),
(9, 57),
(9, 59),
(9, 60),
(9, 61),
(9, 63),
(9, 66),
(9, 67),
(9, 72),
(9, 73),
(9, 74),
(9, 77),
(9, 81),
(9, 82),
(9, 83),
(9, 84),
(9, 85),
(9, 86),
(9, 95),
(9, 96),
(11, 34),
(11, 39),
(11, 66),
(11, 71),
(11, 86),
(11, 87),
(12, 35),
(12, 40),
(12, 72),
(12, 77),
(12, 95),
(12, 96),
(13, 35),
(13, 40),
(13, 46),
(13, 47),
(13, 49),
(13, 72),
(13, 77),
(13, 95),
(13, 96),
(14, 21),
(14, 26),
(14, 29),
(14, 30),
(14, 35),
(14, 40),
(14, 46),
(14, 47),
(14, 49),
(14, 72),
(14, 77),
(14, 95),
(14, 96),
(15, 21),
(15, 26),
(15, 29),
(15, 30),
(15, 35),
(15, 40),
(15, 46),
(15, 47),
(15, 49),
(15, 56),
(15, 57),
(15, 59),
(15, 72),
(15, 77),
(15, 95),
(15, 96),
(16, 35),
(16, 40),
(16, 46),
(16, 47),
(16, 49),
(16, 72),
(16, 77),
(16, 95),
(16, 96),
(17, 35),
(17, 40),
(17, 72),
(17, 77),
(17, 95),
(17, 96),
(18, 35),
(18, 40),
(18, 72),
(18, 77),
(18, 95),
(18, 96),
(19, 35),
(19, 40),
(19, 46),
(19, 49),
(19, 72),
(19, 77),
(19, 95),
(19, 96),
(21, 35),
(21, 40),
(21, 46),
(21, 47),
(21, 49),
(21, 72),
(21, 77),
(21, 95),
(21, 96),
(22, 35),
(22, 40),
(22, 72),
(22, 77),
(22, 95),
(22, 96),
(23, 35),
(23, 40),
(23, 72),
(23, 77),
(23, 95),
(23, 96),
(24, 35),
(24, 40),
(24, 72),
(24, 77),
(24, 95),
(24, 96),
(25, 34),
(25, 39),
(25, 66),
(25, 71),
(25, 86),
(25, 87),
(26, 35),
(26, 40),
(26, 72),
(26, 77),
(26, 95),
(26, 96),
(27, 35),
(27, 40),
(27, 72),
(27, 77),
(27, 95),
(27, 96),
(28, 35),
(28, 40),
(28, 72),
(28, 77),
(28, 95),
(28, 96),
(29, 35),
(29, 40),
(29, 72),
(29, 77),
(29, 95),
(29, 96),
(30, 35),
(30, 40),
(30, 72),
(30, 77),
(30, 95),
(30, 96),
(31, 35),
(31, 40),
(31, 46),
(31, 47),
(31, 49),
(31, 72),
(31, 77),
(31, 95),
(31, 96),
(32, 35),
(32, 40),
(32, 72),
(32, 77),
(32, 95),
(32, 96),
(33, 35),
(33, 40),
(33, 46),
(33, 47),
(33, 49),
(33, 72),
(33, 77),
(33, 95),
(33, 96),
(36, 21),
(36, 29),
(36, 30),
(36, 35),
(36, 38),
(36, 40),
(36, 46),
(36, 49),
(36, 56),
(36, 57),
(36, 59),
(36, 63),
(36, 67),
(36, 72),
(36, 77),
(36, 81),
(36, 82),
(36, 84),
(36, 86),
(36, 95),
(36, 96),
(101, 1),
(101, 2),
(101, 3),
(101, 4),
(101, 5),
(101, 6),
(101, 7),
(101, 8),
(101, 9),
(101, 10),
(101, 11),
(101, 12),
(101, 13),
(101, 14),
(101, 15),
(101, 16),
(101, 17),
(101, 18),
(101, 19),
(101, 20),
(101, 21),
(101, 22),
(101, 23),
(101, 24),
(101, 25),
(101, 26),
(101, 27),
(101, 28),
(101, 29),
(101, 30),
(101, 31),
(101, 32),
(101, 33),
(101, 34),
(101, 35),
(101, 36),
(101, 37),
(101, 38),
(101, 39),
(101, 40),
(101, 41),
(101, 42),
(101, 43),
(101, 44),
(101, 45),
(101, 46),
(101, 47),
(101, 48),
(101, 49),
(101, 50),
(101, 51),
(101, 52),
(101, 53),
(101, 54),
(101, 55),
(101, 56),
(101, 57),
(101, 58),
(101, 59),
(101, 60),
(101, 61),
(101, 62),
(101, 63),
(101, 64),
(101, 65),
(101, 66),
(101, 67),
(101, 68),
(101, 69),
(101, 70),
(101, 71),
(101, 72),
(101, 73),
(101, 74),
(101, 75),
(101, 76),
(101, 77),
(101, 78),
(101, 79),
(101, 80),
(101, 81),
(101, 82),
(101, 83),
(101, 84),
(101, 85),
(101, 86),
(101, 87),
(101, 88),
(101, 89),
(101, 90),
(101, 91),
(101, 92),
(101, 93),
(101, 94),
(101, 95),
(101, 96),
(101, 97),
(101, 98),
(101, 99),
(101, 100),
(101, 101),
(101, 102),
(101, 103),
(101, 104),
(101, 105),
(101, 106),
(101, 107),
(101, 108),
(101, 109),
(101, 110),
(101, 111),
(101, 112),
(101, 113),
(101, 114),
(101, 115),
(101, 116),
(101, 117),
(101, 118),
(101, 119),
(102, 21),
(102, 22),
(102, 23),
(102, 24),
(102, 25),
(102, 26),
(102, 27),
(102, 28),
(102, 29),
(102, 30),
(102, 31),
(102, 32),
(102, 33),
(102, 34),
(102, 35),
(102, 56),
(102, 57),
(102, 58),
(102, 59),
(102, 60),
(102, 61),
(102, 62),
(102, 63),
(102, 64),
(102, 65),
(102, 66),
(102, 67),
(102, 68),
(102, 69),
(102, 70),
(102, 71),
(102, 72),
(102, 77),
(102, 81),
(102, 82),
(102, 83),
(102, 84),
(102, 85),
(102, 86),
(102, 98),
(102, 99),
(102, 118),
(102, 119),
(102, 122),
(102, 123);
