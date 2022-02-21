-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2022 at 04:00 AM
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
('isPrintActive', 'false', 'Untuk mengaktifkan atau menonaktifkan fungsi cetak struk saat melakukan transaksi penjualan', 'teguh.ziliwu', '2022-02-21 09:00:36', NULL, NULL),
('ItemTypeUOMKG', '1/4,1/2,1,2', 'Jenis barang untuk satuan KG', 'Admin', '2021-12-03 23:24:45', NULL, NULL),
('ItemTypeUOMSAK', '10 KG, 30 KG, 40 KG, 50 KG', 'Jenis barang untuk satuan SAK', 'Admin', '2021-12-03 23:25:35', 'Admin', '2021-12-03 23:27:09'),
('PathCCTVRecord', 'file/cctvrecord/', 'Path untuk get cctv record', 'Admin', '2021-12-10 22:40:06', NULL, NULL),
('PathItemPicture', 'file/itempict/', 'Path untuk get item picture', 'Admin', '2021-12-05 22:48:52', 'Admin', '2021-12-05 23:04:39'),
('PathPromotionPicture', 'file/promotionpict/', 'Path untuk get item promotion', 'Admin', '2021-12-12 12:41:26', NULL, NULL),
('PrinterName', 'POS-58-Seriesssssss', 'Nama printer struk', 'teguh.ziliwu', '2022-01-25 21:04:35', 'teguh.ziliwu', '2022-02-21 08:52:18'),
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
(23, 'Pemilik Usaha', 'cctvrecord', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(25, 'Pemilik Usaha', 'form', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(26, 'Pemilik Usaha', 'globalsetting', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(27, 'Pemilik Usaha', 'group', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(28, 'Pemilik Usaha', 'groupaccess', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(30, 'Pemilik Usaha', 'report', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(32, 'Pemilik Usaha', 'stock', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(34, 'Pemilik Usaha', 'user', 'Admin', '2021-12-13 19:54:36', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(41, 'Pemilik Usaha', 'gainloss', 'teguh.ziliwu', '2021-12-15 21:46:52', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(42, 'Kasir', 'sale', 'teguh.ziliwu', '2021-12-17 19:23:44', NULL, NULL),
(43, 'Pemilik Usaha', 'errorlog', 'teguh.ziliwu', '2021-12-30 10:09:53', 'teguh.ziliwu', '2021-12-31 15:10:41'),
(44, 'Pemilik Usaha', 'category', 'teguh.ziliwu', '2021-12-31 15:10:41', NULL, NULL),
(45, 'Pemilik Usaha', 'item', 'teguh.ziliwu', '2021-12-31 15:10:41', NULL, NULL),
(46, 'Pemilik Usaha', 'sale', 'teguh.ziliwu', '2021-12-31 15:10:41', NULL, NULL),
(47, 'Pemilik Usaha', 'uom', 'teguh.ziliwu', '2021-12-31 15:10:41', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `ttransaction`
--

CREATE TABLE `ttransaction` (
  `transactionid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `transactiontype` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `transactiondate` datetime NOT NULL,
  `remark` varchar(2000) CHARACTER SET utf8 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `createddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ttransactiondet`
--

CREATE TABLE `ttransactiondet` (
  `transactionid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `itemcode` varchar(20) CHARACTER SET utf8 NOT NULL,
  `qty` int(11) NOT NULL,
  `purchaseprice` int(11) NOT NULL,
  `discount` varchar(20) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `groupaccessid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
