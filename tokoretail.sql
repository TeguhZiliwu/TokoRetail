-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2022 at 02:44 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokoretail`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcategory`
--

CREATE TABLE `tcategory` (
  `categorycode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `category` varchar(20) CHARACTER SET utf8 NOT NULL,
  `categorydesc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tcategory`
--

INSERT INTO `tcategory` (`categorycode`, `category`, `categorydesc`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('CC0001', 'Pakan', 'Makanan', '', '2021-12-10 16:41:04', NULL, '2021-12-10 16:41:04'),
('CC0002', 'Alat', 'Alat untuk pertanian dan peternakan', 'Admin', '2021-12-02 23:18:55', NULL, NULL),
('CC0003', 'Pupuk', 'Pupuk', 'Admin', '2021-12-13 19:25:33', NULL, NULL),
('CC0004', 'Obat', 'Obat', 'teguh.ziliwu', '2021-12-16 18:54:31', NULL, NULL),
('CC0005', 'Bibit', 'Bibit', 'teguh.ziliwu', '2021-12-16 18:54:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tcctvrecord`
--

CREATE TABLE `tcctvrecord` (
  `cctvrecordid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `cctvrecordname` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `remark` varchar(2000) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tcctvrecord`
--

INSERT INTO `tcctvrecord` (`cctvrecordid`, `cctvrecordname`, `remark`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('RCID2021120001', 'Animated Circular Navigation Menu using Html CSS & Vanilla Javascript _ Simple Radial Menu.mp4', 'Rekaman CCTV 30-12-2021', 'Admin', '2021-12-30 09:25:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `terrorlog`
--

CREATE TABLE `terrorlog` (
  `errorid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `errordesc` varchar(10000) CHARACTER SET utf8 NOT NULL,
  `form` varchar(50) CHARACTER SET utf8 NOT NULL,
  `module` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terrorlog`
--

INSERT INTO `terrorlog` (`errorid`, `errordesc`, `form`, `module`, `createdby`, `createddate`) VALUES
('ER2022020001', 'Error on line 45 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\gainloss\\fetch_profit_loss.php: 8: Undefined index: TotalOut', 'Gain Loss', 'Fetch Profit Loss', 'Admin', '2022-02-23 09:44:58'),
('ER2022030001', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\retur\\fetch_data.php: Call to a member function bind_param() on bool', 'Retur', 'Fetch Data', 'Admin', '2022-03-12 20:53:12'),
('ER2022030002', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 10:49:56'),
('ER2022030003', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 10:55:12'),
('ER2022030004', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 10:59:44'),
('ER2022030005', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 10:59:49'),
('ER2022030006', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:12'),
('ER2022030007', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:15'),
('ER2022030008', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:15'),
('ER2022030009', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:16'),
('ER2022030010', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:16'),
('ER2022030011', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:16'),
('ER2022030012', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:16'),
('ER2022030013', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:17'),
('ER2022030014', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:17'),
('ER2022030015', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:17'),
('ER2022030016', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:17'),
('ER2022030017', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:17'),
('ER2022030018', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:18'),
('ER2022030019', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:04:18'),
('ER2022030020', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:06:57'),
('ER2022030021', 'Stock untuk Kode Barang 1. tidak tersedia.', 'Sale', 'Sale', 'Admin', '2022-03-15 11:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `tform`
--

CREATE TABLE `tform` (
  `formid` varchar(50) CHARACTER SET utf8 NOT NULL,
  `formname` varchar(100) CHARACTER SET utf8 NOT NULL,
  `formtype` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tform`
--

INSERT INTO `tform` (`formid`, `formname`, `formtype`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('category', 'Kategori Barang', 'Master Data', 'Admin', '2021-12-02 21:53:50', 'Admin', '2021-12-02 22:15:12'),
('cctvrecord', 'CCTV Record', 'Master Data', 'Admin', '2021-12-09 22:01:16', NULL, NULL),
('errorlog', 'Error Log', '', 'Admin', '2021-12-02 23:29:57', NULL, NULL),
('form', 'Form', 'Pengaturan', 'Admin', '2021-11-29 21:04:34', 'Admin', '2021-12-02 21:45:01'),
('gainloss', 'Laba & Rugi', '', 'teguh.ziliwu', '2021-12-15 21:45:48', NULL, NULL),
('globalsetting', 'Global Setting', 'Pengaturan', 'Admin', '2021-12-03 23:05:26', NULL, NULL),
('group', 'Group', 'Pengaturan', 'Admin', '2021-11-29 20:58:23', 'Admin', '2021-11-29 22:32:26'),
('groupaccess', 'Group Akses', 'Pengaturan', 'Admin', '2021-11-29 21:20:46', 'Admin', '2021-12-02 21:45:56'),
('item', 'Barang', 'Master Data', 'Admin', '2021-12-01 15:18:19', 'Admin', '2021-12-02 21:40:38'),
('report', 'Laporan', '', 'Admin', '2021-12-08 00:00:13', NULL, NULL),
('retur', 'Retur Barang', '', 'teguh.ziliwu', '2022-03-10 14:22:28', NULL, NULL),
('sale', 'Penjualan', '', 'Admin', '2021-12-02 21:21:33', NULL, NULL),
('stock', 'Stock', '', 'Admin', '2021-12-05 01:31:41', NULL, NULL),
('uom', 'Satuan Barang', 'Master Data', 'Admin', '2021-12-03 13:47:48', NULL, NULL),
('user', 'User', 'Master Data', 'Admin', '2021-11-29 21:23:38', 'Admin', '2021-11-29 21:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `tglobalsetting`
--

CREATE TABLE `tglobalsetting` (
  `settingid` varchar(50) CHARACTER SET utf8 NOT NULL,
  `settingvalue` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `remark` varchar(2000) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tglobalsetting`
--

INSERT INTO `tglobalsetting` (`settingid`, `settingvalue`, `remark`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('AddressStore', 'Ruko Bunga Raya Blok B No.5 Batam Center', 'Alamat toko', 'teguh.ziliwu', '2022-01-26 19:11:52', NULL, NULL),
('ContactAdmin', '+6281371959879', 'Admin contact', 'Admin', '2021-12-12 20:46:39', 'teguh.ziliwu', '2022-02-21 08:57:22'),
('DiscountPercentage', '5%, 10%, 15%', 'Diskon dengan persen', 'teguh.ziliwu', '2021-12-15 18:58:21', 'teguh.ziliwu', '2021-12-15 20:44:09'),
('isPrintActive', 'false', 'Untuk mengaktifkan atau menonaktifkan fungsi cetak struk saat melakukan transaksi penjualan', 'Admin', '2022-02-24 14:12:04', NULL, NULL),
('ItemTypeUOMKG', '1/4,1/2,1,2', 'Jenis barang untuk satuan KG', 'Admin', '2021-12-03 23:24:45', NULL, NULL),
('ItemTypeUOMSAK', '10 KG, 30 KG, 40 KG, 50 KG', 'Jenis barang untuk satuan SAK', 'Admin', '2021-12-03 23:25:35', 'Admin', '2021-12-03 23:27:09'),
('PathCCTVRecord', 'file/cctvrecord/', 'Path untuk get cctv record', 'Admin', '2021-12-10 22:40:06', NULL, NULL),
('PathItemPicture', 'file/itempict/', 'Path untuk get item picture', 'Admin', '2021-12-05 22:48:52', 'Admin', '2021-12-05 23:04:39'),
('PathPromotionPicture', 'file/promotionpict/', 'Path untuk get item promotion', 'Admin', '2021-12-12 12:41:26', NULL, NULL),
('PrinterName', 'POS-58-Series', 'Nama printer struk', 'teguh.ziliwu', '2022-01-25 21:04:35', 'teguh.ziliwu', '2022-02-21 19:58:01'),
('SlidePromotionPage', 'brush-sale-banner-promotion-ribbon-260nw-1182942766.jpg|6.jpg', 'Setting untuk slide promotion page', 'Admin', '2021-12-12 10:51:11', 'teguh.ziliwu', '2021-12-15 23:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `tgroup`
--

CREATE TABLE `tgroup` (
  `groupid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `groupdesc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tgroup`
--

INSERT INTO `tgroup` (`groupid`, `groupdesc`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('Administrator', 'Hak akses super', 'Admin', '2021-11-28 08:22:44', 'Admin', '2021-11-28 15:01:40'),
('Karyawan', 'Karyawan', 'Admin', '2021-11-28 14:49:21', NULL, NULL),
('Kasir', 'Kasir', 'Admin', '2021-12-13 19:23:54', NULL, NULL),
('Pemilik Usaha', 'Pemilik usaha', 'Admin', '2021-11-28 14:50:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tgroupaccess`
--

CREATE TABLE `tgroupaccess` (
  `groupaccessid` int(11) NOT NULL,
  `groupid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `formid` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tgroupaccess`
--

INSERT INTO `tgroupaccess` (`groupaccessid`, `groupid`, `formid`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
(8, 'Karyawan', 'item', 'Admin', '2021-12-01 15:18:45', NULL, NULL),
(13, 'Administrator', 'item', 'Admin', '2021-12-02 21:26:28', 'teguh.ziliwu', '2021-12-18 19:36:56'),
(14, 'Administrator', 'category', 'Admin', '2021-12-02 21:54:33', 'teguh.ziliwu', '2021-12-18 19:36:56'),
(17, 'Administrator', 'uom', 'Admin', '2021-12-03 13:47:56', 'teguh.ziliwu', '2021-12-18 19:36:56'),
(19, 'Administrator', 'stock', 'Admin', '2021-12-05 01:31:55', 'teguh.ziliwu', '2021-12-18 19:36:56'),
(21, 'Administrator', 'cctvrecord', 'Admin', '2021-12-09 22:01:24', 'teguh.ziliwu', '2021-12-18 19:36:56'),
(23, 'Pemilik Usaha', 'cctvrecord', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(25, 'Pemilik Usaha', 'form', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(26, 'Pemilik Usaha', 'globalsetting', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(27, 'Pemilik Usaha', 'group', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(28, 'Pemilik Usaha', 'groupaccess', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(30, 'Pemilik Usaha', 'report', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(32, 'Pemilik Usaha', 'stock', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(34, 'Pemilik Usaha', 'user', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(41, 'Pemilik Usaha', 'gainloss', 'teguh.ziliwu', '2021-12-15 21:46:52', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(42, 'Kasir', 'sale', 'teguh.ziliwu', '2021-12-17 19:23:44', NULL, NULL),
(43, 'Pemilik Usaha', 'errorlog', 'teguh.ziliwu', '2021-12-30 10:09:53', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(44, 'Pemilik Usaha', 'category', 'teguh.ziliwu', '2021-12-31 15:10:41', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(45, 'Pemilik Usaha', 'item', 'teguh.ziliwu', '2021-12-31 15:10:41', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(46, 'Pemilik Usaha', 'sale', 'teguh.ziliwu', '2021-12-31 15:10:41', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(47, 'Pemilik Usaha', 'uom', 'teguh.ziliwu', '2021-12-31 15:10:41', 'teguh.ziliwu', '2022-03-10 14:23:13'),
(48, 'Pemilik Usaha', 'retur', 'teguh.ziliwu', '2022-03-10 14:23:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `titem`
--

CREATE TABLE `titem` (
  `itemcode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `itemname` varchar(100) CHARACTER SET utf8 NOT NULL,
  `itemdesc` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `categorycode` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `uomcode` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `sellingprice` int(11) NOT NULL,
  `itemtype` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `titem`
--

INSERT INTO `titem` (`itemcode`, `itemname`, `itemdesc`, `categorycode`, `uomcode`, `sellingprice`, `itemtype`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('IC2022020001', 'Proplan Repack 1KG', 'Proplan eceran 1KG', 'CC0001', 'UOM0001', 12500, '1', 'teguh.ziliwu', '2022-02-22 16:00:52', NULL, NULL),
('IC2022030001', 'Serokan Pasir Kucing', 'Serokan pasir untuk kucing\\n', 'CC0002', 'UOM0002', 20000, '', 'teguh.ziliwu', '2022-03-15 11:20:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `titempicture`
--

CREATE TABLE `titempicture` (
  `itempictureid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `itemcode` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `picturename` varchar(2000) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tstock`
--

CREATE TABLE `tstock` (
  `itemcode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `qty` int(11) NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tstock`
--

INSERT INTO `tstock` (`itemcode`, `qty`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('IC2022020001', 95, 'teguh.ziliwu', '2022-03-15 11:21:41', 'teguh.ziliwu', '2022-03-17 14:45:33'),
('IC2022030001', 48, 'teguh.ziliwu', '2022-03-15 11:21:41', 'teguh.ziliwu', '2022-03-17 14:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `ttransaction`
--

CREATE TABLE `ttransaction` (
  `transactionid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `transactiontype` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `transactiondate` datetime NOT NULL,
  `relatedtransaction` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `remark` varchar(2000) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ttransaction`
--

INSERT INTO `ttransaction` (`transactionid`, `transactiontype`, `transactiondate`, `relatedtransaction`, `remark`, `createdby`, `createddate`) VALUES
('TRIN2022030001', 'IN', '2022-03-15 11:21:41', NULL, 'stock in pertama', 'teguh.ziliwu', '2022-03-15 11:21:41'),
('TROUT2022030001', 'OUT', '2022-03-17 14:45:33', NULL, 'Penjualan pertama', 'teguh.ziliwu', '2022-03-17 14:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `ttransactiondet`
--

CREATE TABLE `ttransactiondet` (
  `transactionid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `itemcode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `qty` int(11) NOT NULL,
  `initialprice` int(11) DEFAULT NULL,
  `purchaseprice` int(11) NOT NULL,
  `discount` varchar(20) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ttransactiondet`
--

INSERT INTO `ttransactiondet` (`transactionid`, `itemcode`, `qty`, `initialprice`, `purchaseprice`, `discount`) VALUES
('TRIN2022030001', 'IC2022020001', 100, NULL, 11500, NULL),
('TRIN2022030001', 'IC2022030001', 50, NULL, 15000, NULL),
('TROUT2022030001', 'IC2022020001', 5, 11500, 12500, '6250'),
('TROUT2022030001', 'IC2022030001', 2, 15000, 20000, '0');

-- --------------------------------------------------------

--
-- Table structure for table `ttranstype`
--

CREATE TABLE `ttranstype` (
  `transactiontype` varchar(20) CHARACTER SET utf8 NOT NULL,
  `transactiontypedesc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `inout` varchar(5) CHARACTER SET utf8 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tuom`
--

CREATE TABLE `tuom` (
  `uomcode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `uom` varchar(6) CHARACTER SET utf8 NOT NULL,
  `uomdesc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tuom`
--

INSERT INTO `tuom` (`uomcode`, `uom`, `uomdesc`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('UOM0001', 'KG', 'Satuan kilogram', 'Admin', '2021-12-03 14:14:01', NULL, NULL),
('UOM0002', 'PCS', 'Satuan pieces ', 'Admin', '2021-12-03 14:25:57', NULL, NULL),
('UOM0003', 'SAK', 'Satuan untuk item perkarung', 'Admin', '2021-12-03 23:03:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tuser`
--

CREATE TABLE `tuser` (
  `userid` varchar(50) CHARACTER SET utf8 NOT NULL,
  `fullname` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(200) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `telpno` varchar(20) CHARACTER SET utf8 NOT NULL,
  `groupid` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tuser`
--

INSERT INTO `tuser` (`userid`, `fullname`, `password`, `email`, `telpno`, `groupid`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('Admin', 'Administrator', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', '', '+62 813-7195-9879', 'Administrator', 'Admin', '2021-12-01 21:46:01', 'Admin', '2021-12-01 22:29:09'),
('kasir', 'Kasir', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', '', '+62 081-3719-59879', 'Kasir', 'teguh.ziliwu', '2021-12-17 19:21:20', NULL, NULL),
('teguh.ziliwu', 'Teguh Ziliwu', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'teguh.ziliwu@gmail.com', '+62 813-7195-9879', 'Pemilik Usaha', 'Admin', '2021-12-02 00:14:49', 'Admin', '2021-12-13 19:54:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tcategory`
--
ALTER TABLE `tcategory`
  ADD PRIMARY KEY (`categorycode`);

--
-- Indexes for table `tcctvrecord`
--
ALTER TABLE `tcctvrecord`
  ADD PRIMARY KEY (`cctvrecordid`);

--
-- Indexes for table `terrorlog`
--
ALTER TABLE `terrorlog`
  ADD PRIMARY KEY (`errorid`);

--
-- Indexes for table `tform`
--
ALTER TABLE `tform`
  ADD PRIMARY KEY (`formid`);

--
-- Indexes for table `tglobalsetting`
--
ALTER TABLE `tglobalsetting`
  ADD PRIMARY KEY (`settingid`);

--
-- Indexes for table `tgroup`
--
ALTER TABLE `tgroup`
  ADD PRIMARY KEY (`groupid`);

--
-- Indexes for table `tgroupaccess`
--
ALTER TABLE `tgroupaccess`
  ADD PRIMARY KEY (`groupaccessid`);

--
-- Indexes for table `titem`
--
ALTER TABLE `titem`
  ADD PRIMARY KEY (`itemcode`);

--
-- Indexes for table `titempicture`
--
ALTER TABLE `titempicture`
  ADD PRIMARY KEY (`itempictureid`);

--
-- Indexes for table `tstock`
--
ALTER TABLE `tstock`
  ADD PRIMARY KEY (`itemcode`);

--
-- Indexes for table `ttransaction`
--
ALTER TABLE `ttransaction`
  ADD PRIMARY KEY (`transactionid`);

--
-- Indexes for table `ttransactiondet`
--
ALTER TABLE `ttransactiondet`
  ADD PRIMARY KEY (`transactionid`,`itemcode`);

--
-- Indexes for table `ttranstype`
--
ALTER TABLE `ttranstype`
  ADD PRIMARY KEY (`transactiontype`);

--
-- Indexes for table `tuom`
--
ALTER TABLE `tuom`
  ADD PRIMARY KEY (`uomcode`);

--
-- Indexes for table `tuser`
--
ALTER TABLE `tuser`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tgroupaccess`
--
ALTER TABLE `tgroupaccess`
  MODIFY `groupaccessid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
