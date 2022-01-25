-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2022 at 07:51 AM
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
('ER2021110001', 'Error on line 17 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 11:55:21'),
('ER2021110002', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:07:52'),
('ER2021110003', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:08:00'),
('ER2021110004', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:09:20'),
('ER2021110005', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:09:36'),
('ER2021110006', 'Error on line 15 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:13:49'),
('ER2021110007', 'Error on line 15 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:14:32'),
('ER2021110008', 'Error on line 16 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:14:50'),
('ER2021110009', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:15:29'),
('ER2021110010', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:32:39'),
('ER2021110011', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:32:58'),
('ER2021110012', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:34:10'),
('ER2021110013', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:34:20'),
('ER2021110014', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:34:50'),
('ER2021110015', 'Error on line 18 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:41:53'),
('ER2021110016', 'Error on line 17 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:43:43'),
('ER2021110017', 'Error on line 17 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\loaddata.php: 8: Undefined index: search', 'Group', 'Load Data', 'Admin', '2021-11-28 12:44:15'),
('ER2021110018', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:05:45'),
('ER2021110019', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:05:48'),
('ER2021110020', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:05:49'),
('ER2021110021', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:06:17'),
('ER2021110022', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:39:57'),
('ER2021110023', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:39:59'),
('ER2021110024', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:40:00'),
('ER2021110025', '', 'Form', 'Create-Update', 'Admin', '2021-11-29 21:40:00'),
('ER2021110026', 'Error on line 16 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\fetch_data.php: 8: Undefined index: search', 'Group', 'Fetch Data', 'Admin', '2021-11-29 23:25:42'),
('ER2021110027', 'Error on line 16 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\fetch_data.php: 8: Undefined index: search', 'Group', 'Fetch Data', 'Admin', '2021-11-29 23:25:58'),
('ER2021110028', 'Error on line 16 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\fetch_data.php: 8: Undefined index: search', 'Group', 'Fetch Data', 'Admin', '2021-11-29 23:26:12'),
('ER2021110029', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\fetch_data.php: 8: Undefined variable: Search', 'Group', 'Fetch Data', 'Admin', '2021-11-29 23:26:48'),
('ER2021110030', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\form\\fetch_data.php: Call to a member function execute() on bool', 'Form', 'Fetch Data', 'Admin', '2021-11-30 14:39:09'),
('ER2021120001', 'Error on line 23 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\create_update.php: 8: Undefined property: stdClass::$GroupDesc', 'Group', 'Create-Update', 'Admin', '2021-12-01 13:28:09'),
('ER2021120002', 'Error on line 23 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\create_update.php: 8: Undefined property: stdClass::$GroupDesc', 'Group', 'Create-Update', 'Admin', '2021-12-01 14:58:52'),
('ER2021120003', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\groupaccess\\loaddata.php: Call to a member function bind_param() on bool', 'Group', 'Load Data', 'Admin', '2021-12-01 15:16:20'),
('ER2021120004', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\groupaccess\\loaddata.php: Call to a member function bind_param() on bool', 'Group', 'Load Data', 'Admin', '2021-12-01 15:16:30'),
('ER2021120005', 'Error on line 23 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\create_update.php: 8: Undefined property: stdClass::$GroupDesc', 'Group', 'Create-Update', 'Admin', '2021-12-01 21:41:46'),
('ER2021120006', 'Error on line 23 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\create_update.php: 8: Undefined property: stdClass::$GroupDesc', 'Group', 'Create-Update', 'Admin', '2021-12-01 21:45:09'),
('ER2021120007', 'Error on line 54 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\create_update.php: 2: mysqli_stmt::bind_param(): Number of variables doesn\'t match number of parameters in prepared statement', 'User', 'Create-Update', 'Admin', '2021-12-01 21:53:03'),
('ER2021120008', 'Error on line 54 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\create_update.php: 2: mysqli_stmt::bind_param(): Number of elements in type definition string doesn\'t match number of bind variables', 'User', 'Create-Update', 'Admin', '2021-12-01 21:59:05'),
('ER2021120009', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\delete.php: 8: Undefined property: stdClass::$UserID', 'User', 'Delete', 'Admin', '2021-12-01 22:02:33'),
('ER2021120010', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\delete.php: 8: Undefined property: stdClass::$GroupID', 'User', 'Delete', 'Admin', '2021-12-01 22:06:58'),
('ER2021120011', 'Error on line 46 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\delete.php: 8: Undefined variable: stmt', 'User', 'Delete', 'Admin', '2021-12-01 22:07:23'),
('ER2021120012', 'Error on line 46 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\delete.php: 8: Undefined variable: stmt', 'User', 'Delete', 'Admin', '2021-12-01 22:07:38'),
('ER2021120013', 'Error on line 51 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\delete.php: 8: Undefined variable: stmt', 'User', 'Delete', 'Admin', '2021-12-01 22:08:00'),
('ER2021120014', 'Error on line 66 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\create_update.php: 8: Undefined variable: stmt', 'User', 'Create-Update', 'Admin', '2021-12-01 22:26:34'),
('ER2021120015', 'Error on line 66 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\create_update.php: 8: Undefined variable: stmt', 'User', 'Create-Update', 'Admin', '2021-12-01 22:27:12'),
('ER2021120016', 'Error on line 66 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\create_update.php: 8: Undefined variable: stmt', 'User', 'Create-Update', 'Admin', '2021-12-01 22:27:29'),
('ER2021120017', 'Error on line 66 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\user\\create_update.php: 8: Undefined variable: stmt', 'User', 'Create-Update', 'Admin', '2021-12-01 22:27:44'),
('ER2021120018', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\groupaccess\\authorize.php: 8: Undefined index: GroupID', 'Group Access', 'Authorize', 'Admin', '2021-12-02 19:42:24'),
('ER2021120019', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\groupaccess\\authorize.php: 8: Undefined index: GroupID', 'Group Access', 'Authorize', 'Admin', '2021-12-02 19:42:43'),
('ER2021120020', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\groupaccess\\authorize.php: 8: Undefined index: GroupID', 'Group Access', 'Authorize', 'Admin', '2021-12-02 19:43:08'),
('ER2021120021', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\groupaccess\\authorize.php: 8: Undefined index: GroupID', 'Group Access', 'Authorize', 'Admin', '2021-12-02 19:43:25'),
('ER2021120022', 'Error on line 45 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\form\\fetch_data.php: 8: Undefined index: FormID', 'Form', 'Fetch Data', 'Admin', '2021-12-02 21:10:13'),
('ER2021120023', '', 'Form', 'Create-Update', 'Admin', '2021-12-02 21:52:42'),
('ER2021120024', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\create_update.php: 8: Undefined property: stdClass::$Category', 'Category', 'Create-Update', 'Admin', '2021-12-02 23:04:33'),
('ER2021120025', 'Error on line 29 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\create_update.php: Call to a member function bind_param() on bool', 'Category', 'Create-Update', 'Admin', '2021-12-02 23:05:56'),
('ER2021120026', '', 'Category', 'Create-Update', 'Admin', '2021-12-02 23:09:47'),
('ER2021120027', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\delete.php: 8: Undefined property: stdClass::$GroupID', 'Group', 'Delete', 'Admin', '2021-12-02 23:16:59'),
('ER2021120028', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\delete.php: 8: Undefined property: stdClass::$GroupID', 'Group', 'Delete', 'Admin', '2021-12-02 23:18:08'),
('ER2021120029', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\uom\\create_update.php: 8: Undefined property: stdClass::$CategoryCode', 'UOM', 'Create-Update', 'Admin', '2021-12-03 14:11:10'),
('ER2021120030', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\delete.php: 8: Undefined property: stdClass::$CategoryCode', 'Category', 'Delete', 'Admin', '2021-12-03 14:25:21'),
('ER2021120031', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\globalsetting\\loaddata.php: Call to a member function bind_param() on bool', 'Global Setting', 'Load Data', 'Admin', '2021-12-03 23:12:01'),
('ER2021120032', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\globalsetting\\loaddata.php: Call to a member function bind_param() on bool', 'Global Setting', 'Load Data', 'Admin', '2021-12-03 23:12:04'),
('ER2021120033', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\globalsetting\\create_update.php: 8: Undefined property: stdClass::$SettingID', 'Global Setting', 'Create-Update', 'Admin', '2021-12-03 23:23:29'),
('ER2021120034', 'Error on line 46 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\globalsetting\\fetch_data.php: 8: Undefined variable: stmt', 'Global Setting', 'Fetch Data', 'Admin', '2021-12-04 01:38:03'),
('ER2021120035', 'Error on line 16 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\fetch_data.php: 8: Undefined index: FetchData', 'Category', 'Fetch Data', 'Admin', '2021-12-04 02:52:21'),
('ER2021120036', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\fetch_data.php: 8: Undefined index: SettingID', 'Category', 'Fetch Data', 'Admin', '2021-12-04 02:52:42'),
('ER2021120037', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\fetch_data.php: 8: Undefined index: SettingID', 'Category', 'Fetch Data', 'Admin', '2021-12-04 02:52:47'),
('ER2021120038', 'Error on line 45 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\uom\\fetch_data.php: 8: Undefined variable: stmt', 'UOM', 'Fetch Data', 'Admin', '2021-12-04 02:55:18'),
('ER2021120039', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: Call to a member function bind_param() on bool', 'Group', 'Create-Update', 'Admin', '2021-12-04 04:28:26'),
('ER2021120040', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: Call to a member function bind_param() on bool', 'Item', 'Create-Update', 'Admin', '2021-12-04 04:29:30'),
('ER2021120041', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: Call to a member function bind_param() on bool', 'Item', 'Create-Update', 'Admin', '2021-12-04 04:30:12'),
('ER2021120042', 'Error on line 43 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\loaddata.php: 8: Undefined index: createdby', 'Item', 'Load Data', 'Admin', '2021-12-04 04:39:00'),
('ER2021120043', 'Error on line 43 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\loaddata.php: 8: Undefined index: createdby', 'Item', 'Load Data', 'Admin', '2021-12-04 04:39:01'),
('ER2021120044', 'Error on line 57 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: Call to a member function bind_param() on bool', 'Item', 'Create-Update', 'Admin', '2021-12-04 04:47:43'),
('ER2021120045', 'Error on line 57 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: 2: mysqli_stmt::bind_param(): Number of elements in type definition string doesn\'t match number of bind variables', 'Item', 'Create-Update', 'Admin', '2021-12-04 04:49:52'),
('ER2021120046', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\category\\delete.php: 8: Undefined property: stdClass::$CategoryCode', 'Category', 'Delete', 'Admin', '2021-12-04 14:06:10'),
('ER2021120047', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Class \'PhpOffice\\PhpSpreadsheet\\Reader\\Xlsx\' not found', 'Item', 'Create-Update', 'Admin', '2021-12-04 16:40:29'),
('ER2021120048', 'Error on line 279 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:25:08'),
('ER2021120049', 'Error on line 279 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:25:20'),
('ER2021120050', 'Error on line 279 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:25:59'),
('ER2021120051', 'Error on line 279 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:25:59'),
('ER2021120052', 'Error on line 282 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:27:05'),
('ER2021120053', 'Error on line 282 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:27:05'),
('ER2021120054', 'Error on line 282 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:27:26'),
('ER2021120055', 'Error on line 282 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:27:26'),
('ER2021120056', 'Error on line 282 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:27:36'),
('ER2021120057', 'Error on line 282 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Call to a member function bind_param() on bool', 'Item', 'Import', 'Admin', '2021-12-04 21:27:36'),
('ER2021120058', 'Error on line 289 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: 8: Undefined index: itemcode', 'Item', 'Import', 'Admin', '2021-12-04 21:28:24'),
('ER2021120059', 'Error on line 289 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: 8: Undefined index: itemcode', 'Item', 'Import', 'Admin', '2021-12-04 21:28:24'),
('ER2021120060', 'Error on line 289 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: 8: Undefined index: itemcode', 'Item', 'Import', 'Admin', '2021-12-04 21:28:45'),
('ER2021120061', 'Error on line 289 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: 8: Undefined index: itemcode', 'Item', 'Import', 'Admin', '2021-12-04 21:28:45'),
('ER2021120062', 'Column \'formid\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 21:39:33'),
('ER2021120063', 'Column \'formid\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 21:39:33'),
('ER2021120064', 'Column \'formid\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 21:40:49'),
('ER2021120065', 'Column \'formid\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 21:40:50'),
('ER2021120066', 'Column \'itemdesc\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 22:33:58'),
('ER2021120067', 'Column \'itemdesc\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 22:33:58'),
('ER2021120068', '', 'Item', 'Delete', 'Admin', '2021-12-04 23:10:59'),
('ER2021120069', 'Column \'itemtype\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 23:11:40'),
('ER2021120070', 'Column \'itemtype\' cannot be null', 'Item', 'Import', 'Admin', '2021-12-04 23:11:40'),
('ER2021120071', '', 'Item', 'Delete', 'Admin', '2021-12-04 23:12:30'),
('ER2021120072', '', 'Item', 'Delete', 'Admin', '2021-12-04 23:12:33'),
('ER2021120073', '', 'Item', 'Delete', 'Admin', '2021-12-04 23:19:47'),
('ER2021120074', '', 'Item', 'Delete', 'Admin', '2021-12-04 23:19:50'),
('ER2021120075', '', 'Item', 'Delete', 'Admin', '2021-12-04 23:19:52'),
('ER2021120076', 'Duplicate entry \'1\' for key \'PRIMARY\'', 'Item', 'Import', 'Admin', '2021-12-04 23:23:39'),
('ER2021120077', 'Error on line 388 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Cannot use object of type mysqli_result as array', 'Item', 'Import', 'Admin', '2021-12-04 23:27:29'),
('ER2021120078', 'Error on line 388 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Cannot use object of type mysqli_result as array', 'Item', 'Import', 'Admin', '2021-12-04 23:27:29'),
('ER2021120079', '', 'Item', 'Import', 'Admin', '2021-12-04 23:44:02'),
('ER2021120080', '', 'Item', 'Import', 'Admin', '2021-12-04 23:44:02'),
('ER2021120081', 'Error on line 114 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Cannot pass parameter 2 by reference', 'Item', 'Import', 'Admin', '2021-12-05 00:40:05'),
('ER2021120082', 'Error on line 114 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Cannot pass parameter 2 by reference', 'Item', 'Import', 'Admin', '2021-12-05 00:40:06'),
('ER2021120083', 'Error on line 114 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Cannot pass parameter 2 by reference', 'Item', 'Import', 'Admin', '2021-12-05 00:54:19'),
('ER2021120084', 'Error on line 114 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\import.php: Cannot pass parameter 2 by reference', 'Item', 'Import', 'Admin', '2021-12-05 00:54:19'),
('ER2021120085', 'Error on line 30 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined variable: filename', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:03:09'),
('ER2021120086', 'Error on line 30 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined variable: filename', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:03:09'),
('ER2021120087', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: filename', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:09:37'),
('ER2021120088', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: filename', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:09:37'),
('ER2021120089', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: count(): Parameter must be an array or an object that implements Countable', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:10:03'),
('ER2021120090', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: count(): Parameter must be an array or an object that implements Countable', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:10:03'),
('ER2021120091', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: file', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:10:57'),
('ER2021120092', 'Error on line 32 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: file', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:10:58'),
('ER2021120093', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: Invalid argument supplied for foreach()', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:13:31'),
('ER2021120094', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: Invalid argument supplied for foreach()', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:13:31'),
('ER2021120095', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: count(): Parameter must be an array or an object that implements Countable', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:14:52'),
('ER2021120096', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: count(): Parameter must be an array or an object that implements Countable', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:14:52'),
('ER2021120097', 'Error on line 27 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: pathinfo() expects parameter 1 to be string, array given', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:18:37'),
('ER2021120098', 'Error on line 27 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 2: pathinfo() expects parameter 1 to be string, array given', 'Item', 'Upload Image', 'Admin', '2021-12-05 19:18:37'),
('ER2021120099', 'Error on line 36 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Array to string conversion', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:07:46'),
('ER2021120100', 'Error on line 36 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Array to string conversion', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:07:46'),
('ER2021120101', 'Error on line 39 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined variable: fileExist', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:25:42'),
('ER2021120102', 'Error on line 39 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined variable: fileExist', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:25:42'),
('ER2021120103', 'Error on line 24 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: fileImage', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:45:56'),
('ER2021120104', 'Error on line 64 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: 2: Creating default object from empty value', 'Item', 'Create-Update', 'Admin', '2021-12-05 21:55:43'),
('ER2021120105', 'Error on line 64 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\create_update.php: 2: Creating default object from empty value', 'Item', 'Create-Update', 'Admin', '2021-12-05 21:56:30'),
('ER2021120106', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:56:53'),
('ER2021120107', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 21:58:32'),
('ER2021120108', 'Error on line 25 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:07:11'),
('ER2021120109', 'Error on line 25 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:07:11'),
('ER2021120110', 'Error on line 25 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:08:43'),
('ER2021120111', 'Error on line 25 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:08:43'),
('ER2021120112', 'Error on line 25 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:09:04'),
('ER2021120113', 'Error on line 25 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'ItemCode\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:09:05'),
('ER2021120114', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 22:09:48'),
('ER2021120115', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: Call to a member function bind_param() on bool', 'Item', 'Upload Image', 'Admin', '2021-12-05 22:23:32'),
('ER2021120116', 'Error on line 138 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: Call to a member function bind_param() on bool', 'Item', 'Upload Image', 'Admin', '2021-12-05 23:28:38'),
('ER2021120117', 'Error on line 28 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: fileImage', 'Item', 'Upload Image', 'Admin', '2021-12-05 23:30:46'),
('ER2021120118', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:51:09'),
('ER2021120119', 'Error on line 24 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'pictureItemDeletion\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 23:51:09'),
('ER2021120120', 'Error on line 24 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'pictureItemDeletion\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 23:51:09'),
('ER2021120121', 'Error on line 24 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'pictureItemDeletion\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 23:53:03'),
('ER2021120122', 'Error on line 24 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Trying to get property \'pictureItemDeletion\' of non-object', 'Item', 'Upload Image', 'Admin', '2021-12-05 23:53:03'),
('ER2021120123', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:54:25'),
('ER2021120124', 'SINI', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:54:58'),
('ER2021120125', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:55:14'),
('ER2021120126', '', 'Category', 'Create-Update', 'Admin', '2021-12-05 23:55:51'),
('ER2021120127', '', 'Category', 'Create-Update', 'Admin', '2021-12-05 23:55:54'),
('ER2021120128', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:56:26'),
('ER2021120129', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:57:14'),
('ER2021120130', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:57:14'),
('ER2021120131', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:57:39'),
('ER2021120132', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:57:39'),
('ER2021120133', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:58:07'),
('ER2021120134', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:58:07'),
('ER2021120135', '', 'Item', 'Create-Update', 'Admin', '2021-12-05 23:59:19'),
('ER2021120136', 'Error on line 28 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Array to string conversion', 'Item', 'Upload Image', 'Admin', '2021-12-06 00:07:04'),
('ER2021120137', 'Error on line 28 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Array to string conversion', 'Item', 'Upload Image', 'Admin', '2021-12-06 00:07:04'),
('ER2021120138', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: fileImage', 'Item', 'Upload Image', 'Admin', '2021-12-06 00:11:24'),
('ER2021120139', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: fileImage', 'Item', 'Upload Image', 'Admin', '2021-12-06 00:12:47'),
('ER2021120140', 'Error on line 24 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: 8: Undefined index: hasFileToUpload', 'Item', 'Upload Image', 'Admin', '2021-12-06 00:17:13'),
('ER2021120141', 'Error on line 30 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\uploadimage.php: Cannot use object of type stdClass as array', 'Item', 'Upload Image', 'Admin', '2021-12-06 00:18:31'),
('ER2021120142', 'Error on line 40 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\loaddata.php: 8: Undefined index: SellingPrice', 'Group', 'Load Data', 'Admin', '2021-12-06 15:18:43'),
('ER2021120143', 'Error on line 16 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\loaddata.php: 8: Undefined index: search', 'Item', 'Load Data', 'Admin', '2021-12-06 21:09:23'),
('ER2021120144', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:12:54'),
('ER2021120145', 'Error on line 57 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined variable: Search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:12:57'),
('ER2021120146', 'Error on line 57 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined variable: Search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:12:58'),
('ER2021120147', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:14:42'),
('ER2021120148', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:14:44'),
('ER2021120149', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:14:51'),
('ER2021120150', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:15:13'),
('ER2021120151', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:17:11'),
('ER2021120152', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:17:35'),
('ER2021120153', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:18:36'),
('ER2021120154', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:27:09'),
('ER2021120155', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:27:11'),
('ER2021120156', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:28:16'),
('ER2021120157', 'Error on line 50 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\fetch_data.php: 8: Undefined index: search', 'Item', 'Fetch Data', 'Admin', '2021-12-06 21:28:47'),
('ER2021120158', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: 8: Undefined variable: FormList', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:41:29'),
('ER2021120159', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Cannot pass parameter 3 by reference', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:42:28'),
('ER2021120160', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Cannot pass parameter 3 by reference', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:42:48'),
('ER2021120161', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Cannot pass parameter 3 by reference', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:43:08'),
('ER2021120162', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Cannot pass parameter 3 by reference', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:43:22'),
('ER2021120163', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Cannot pass parameter 3 by reference', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:44:09'),
('ER2021120164', 'Error on line 84 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: 2: mysqli_stmt::bind_param(): Number of elements in type definition string doesn\'t match number of bind variables', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:45:22'),
('ER2021120165', 'Error on line 63 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: 2: A non-numeric value encountered', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:45:44'),
('ER2021120166', 'Error on line 89 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120001\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 20:54:29'),
('ER2021120167', 'Error on line 89 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120001\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 21:01:22'),
('ER2021120168', 'Error on line 89 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120002\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 21:02:40'),
('ER2021120169', 'Error on line 89 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120001\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 21:03:47'),
('ER2021120170', 'Error on line 89 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120001\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 21:05:58'),
('ER2021120171', 'Error on line 89 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120001\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 21:07:09'),
('ER2021120172', 'Error on line 86 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\stock\\stockin.php: Duplicate entry \'TRIN2021120001\' for key \'PRIMARY\'', 'Stock', 'Stock In', 'Admin', '2021-12-07 21:07:57'),
('ER2021120173', 'Error on line 17 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: 8: Undefined index: ItemCode', 'Item', 'Load Data', 'Admin', '2021-12-09 09:44:42'),
('ER2021120174', 'Error on line 17 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: 8: Undefined index: ItemCode', 'Item', 'Load Data', 'Admin', '2021-12-09 09:45:28'),
('ER2021120175', 'Error on line 36 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function execute() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 09:45:41'),
('ER2021120176', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:04:55'),
('ER2021120177', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:06:19'),
('ER2021120178', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:09:57'),
('ER2021120179', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:10:04'),
('ER2021120180', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:13:05'),
('ER2021120181', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:13:05'),
('ER2021120182', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:13:48'),
('ER2021120183', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:13:48'),
('ER2021120184', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:14:01'),
('ER2021120185', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:14:01'),
('ER2021120186', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:14:39'),
('ER2021120187', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:14:39'),
('ER2021120188', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:45:49'),
('ER2021120189', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:45:49'),
('ER2021120190', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:54:57'),
('ER2021120191', 'Error on line 44 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-09 10:54:58'),
('ER2021120192', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: 2: mysqli_stmt::bind_param(): Number of variables doesn\'t match number of parameters in prepared statement', 'Item', 'Load Data', 'Admin', '2021-12-09 11:02:21'),
('ER2021120193', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: 2: mysqli_stmt::bind_param(): Number of variables doesn\'t match number of parameters in prepared statement', 'Item', 'Load Data', 'Admin', '2021-12-09 11:02:21'),
('ER2021120194', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: 2: mysqli_stmt::bind_param(): Number of variables doesn\'t match number of parameters in prepared statement', 'Item', 'Load Data', 'Admin', '2021-12-09 11:03:15'),
('ER2021120195', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: 2: mysqli_stmt::bind_param(): Number of variables doesn\'t match number of parameters in prepared statement', 'Item', 'Load Data', 'Admin', '2021-12-09 11:03:15'),
('ER2021120196', 'Error on line 29 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 2: count(): Parameter must be an array or an object that implements Countable', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 13:53:52'),
('ER2021120197', 'Error on line 34 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined index: fileImage', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 13:54:19'),
('ER2021120198', 'Error on line 35 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined variable: ItemCode', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 13:54:36'),
('ER2021120199', 'Error on line 28 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined variable: edit', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 13:56:57'),
('ER2021120200', 'Column \'cctvrecordname\' cannot be null', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 14:00:21'),
('ER2021120201', 'Error on line 52 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined variable: path_filename_ext', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 14:00:21'),
('ER2021120202', 'Error on line 41 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: Cannot pass parameter 3 by reference', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 14:04:31'),
('ER2021120203', 'Error on line 23 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined index: fileUpload', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 22:02:50'),
('ER2021120204', 'Error on line 23 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined index: fileUpload', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 22:05:01'),
('ER2021120205', 'Error on line 81 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\create_update.php: 8: Undefined variable: stmt', 'CCTV Record', 'Create-Update', 'Admin', '2021-12-10 22:07:50'),
('ER2021120206', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\group\\delete.php: 8: Undefined property: stdClass::$GroupID', 'Group', 'Delete', 'Admin', '2021-12-10 22:23:05'),
('ER2021120207', 'Error on line 22 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\delete.php: 8: Undefined property: stdClass::$RecordID', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:24:58'),
('ER2021120208', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:29:38'),
('ER2021120209', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:34:47'),
('ER2021120210', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:48:22'),
('ER2021120211', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:49:30'),
('ER2021120212', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:50:10'),
('ER2021120213', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:50:10'),
('ER2021120214', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:50:28'),
('ER2021120215', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:50:28'),
('ER2021120216', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 22:52:47'),
('ER2021120217', 'Error on line 38 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\cctvrecord\\delete.php: 8: Undefined variable: RecordName', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 23:03:37'),
('ER2021120218', '', 'CCTV Record', 'Delete', 'Admin', '2021-12-10 23:08:44'),
('ER2021120219', 'Error on line 58 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined variable: details', 'Item', 'Import', 'Admin', '2021-12-11 02:48:26'),
('ER2021120220', 'Error on line 270 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined index: Urutan', 'Sale', 'Import', 'Admin', '2021-12-11 02:49:04'),
('ER2021120221', 'Error on line 271 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined index: Urutan', 'Sale', 'Import', 'Admin', '2021-12-11 02:50:09'),
('ER2021120222', 'Error on line 271 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined index: Urutan', 'Sale', 'Import', 'Admin', '2021-12-11 02:50:09');
INSERT INTO `terrorlog` (`errorid`, `errordesc`, `form`, `module`, `createdby`, `createddate`) VALUES
('ER2021120223', 'Error on line 64 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined index: Urutan', 'Sale', 'Import', 'Admin', '2021-12-11 02:51:04'),
('ER2021120224', 'Error on line 64 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined index: Urutan', 'Sale', 'Import', 'Admin', '2021-12-11 02:51:04'),
('ER2021120225', 'Error on line 64 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: Undefined index: Urutan', 'Sale', 'Import', 'Admin', '2021-12-11 02:51:22'),
('ER2021120226', 'Error on line 101 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\dashboard\\fetch_data.php: 8: Undefined index: TotalTransaction', 'Item', 'Fetch Data', 'Admin', '2021-12-12 00:04:33'),
('ER2021120227', 'Error on line 19 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\globalsetting\\fetch_data.php: 8: Undefined index: SettingID', 'Global Setting', 'Fetch Data', 'Admin', '2021-12-12 11:59:34'),
('ER2021120228', 'Error on line 29 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\globalsetting\\uploadimage.php: 8: Undefined index: OldPictureName', 'Item', 'Upload Image', 'Admin', '2021-12-12 12:01:10'),
('ER2021120229', 'Error on line 21 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\promotion\\fetch_data.php: 8: Undefined index: ItemCode', 'Item', 'Fetch Data', 'Admin', '2021-12-12 18:56:22'),
('ER2021120230', 'Error on line 31 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\promotion\\fetch_data.php: 2: mysqli_stmt::bind_param(): Number of variables doesn\'t match number of parameters in prepared statement', 'Item', 'Fetch Data', 'Admin', '2021-12-12 18:58:39'),
('ER2021120231', 'Error on line 42 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\promotion\\fetch_data.php: Call to a member function bind_param() on bool', 'Item', 'Fetch Data', 'Admin', '2021-12-12 20:10:15'),
('ER2021120232', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:07:29'),
('ER2021120233', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:07:41'),
('ER2021120234', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:09:22'),
('ER2021120235', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:09:22'),
('ER2021120236', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:10:30'),
('ER2021120237', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:10:30'),
('ER2021120238', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:11:25'),
('ER2021120239', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:11:25'),
('ER2021120240', 'Error on line 27 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:12:48'),
('ER2021120241', 'Error on line 27 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:12:48'),
('ER2021120242', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:13:11'),
('ER2021120243', 'Error on line 26 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: Call to a member function bind_param() on bool', 'Item', 'Delete', 'Admin', '2021-12-15 18:13:11'),
('ER2021120244', '', 'Item', 'Delete', 'Admin', '2021-12-15 18:13:38'),
('ER2021120245', 'Error on line 379 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\sale\\import.php: 8: A non well formed numeric value encountered', 'Sale', 'Import', 'Admin', '2021-12-15 21:28:29'),
('ER2021120246', 'Error on line 17 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\pages\\struck.php: 8: Undefined index: search', 'Category', 'Load Data', 'Admin', '2021-12-16 20:57:58'),
('ER2021120247', 'Error on line 71 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\pages\\struck.php: 8: Undefined variable: Data', 'Category', 'Load Data', 'Admin', '2021-12-16 20:58:40'),
('ER2021120248', 'Error on line 74 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\pages\\struck.php: 8: Undefined offset: 5', 'Category', 'Load Data', 'Admin', '2021-12-16 20:59:07'),
('ER2021120249', 'Error on line 74 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\pages\\struck.php: 8: Undefined offset: 5', 'Category', 'Load Data', 'Admin', '2021-12-16 20:59:46'),
('ER2021120250', 'Error on line 74 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\pages\\struck.php: 8: Undefined offset: 5', 'Category', 'Load Data', 'Admin', '2021-12-16 20:59:59'),
('ER2021120251', 'Error on line 51 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\report\\loaddata.php: Call to a member function bind_param() on bool', 'Item', 'Load Data', 'Admin', '2021-12-22 20:24:23'),
('ER2021120252', 'Error on line 33 in C:\\xampp\\htdocs\\Project Teguh\\TugasAkhir\\TokoRetail\\controller\\item\\delete.php: 8: Undefined index: stock', 'Item', 'Delete', 'Admin', '2021-12-30 09:20:57');

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
('ContactAdmin', '+6281371959879', 'Admin contact', 'Admin', '2021-12-12 20:46:39', 'teguh.ziliwu', '2021-12-15 23:09:32'),
('DiscountPercentage', '5%, 10%, 15%', 'Diskon dengan persen', 'teguh.ziliwu', '2021-12-15 18:58:21', 'teguh.ziliwu', '2021-12-15 20:44:09'),
('ItemTypeUOMKG', '1/4,1/2,1,2', 'Jenis barang untuk satuan KG', 'Admin', '2021-12-03 23:24:45', NULL, NULL),
('ItemTypeUOMSAK', '10 KG, 30 KG, 40 KG, 50 KG', 'Jenis barang untuk satuan SAK', 'Admin', '2021-12-03 23:25:35', 'Admin', '2021-12-03 23:27:09'),
('PathCCTVRecord', 'file/cctvrecord/', 'Path untuk get cctv record', 'Admin', '2021-12-10 22:40:06', NULL, NULL),
('PathItemPicture', 'file/itempict/', 'Path untuk get item picture', 'Admin', '2021-12-05 22:48:52', 'Admin', '2021-12-05 23:04:39'),
('PathPromotionPicture', 'file/promotionpict/', 'Path untuk get item promotion', 'Admin', '2021-12-12 12:41:26', NULL, NULL),
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

--
-- Dumping data for table `titem`
--

INSERT INTO `titem` (`itemcode`, `itemname`, `itemdesc`, `categorycode`, `uomcode`, `sellingprice`, `itemtype`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('IC2021120001', 'Proplan Kitten', 'Makanan kucing merk proplan 1/2 KG', 'CC0001', 'UOM0001', 5600, '1/2', 'Admin', '2021-12-29 20:15:15', 'Admin', '2021-12-30 09:49:48'),
('IC2021120002', 'Proplan Kitten', 'Makanan kucing merk proplan 1KG', 'CC0001', 'UOM0001', 110000, '1', 'Admin', '2021-12-30 09:21:58', NULL, NULL),
('IC2021120003', 'Proplan Kitten', 'Makanan kucing merk proplan 2KG', 'CC0001', 'UOM0001', 225000, '2', 'Admin', '2021-12-30 09:21:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `titempicture`
--

CREATE TABLE `titempicture` (
  `itempictureid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `itemcode` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `picturename` varchar(2000) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `titempicture`
--

INSERT INTO `titempicture` (`itempictureid`, `itemcode`, `picturename`) VALUES
('IPID2021120001', 'IC2021120001', 'IC2021120001_proplan_image.jpg'),
('IPID2021120002', 'IC2021120002', 'IC2021120002_myw3schoolsimage.jpg');

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
('IC2021120001', 20, 'Admin', '2021-12-29 20:16:18', NULL, NULL),
('IC2021120002', 15, 'Admin', '2021-12-30 09:24:27', 'kasir', '2021-12-30 09:30:06'),
('IC2021120003', 18, 'Admin', '2021-12-30 09:24:27', 'kasir', '2021-12-30 09:30:06');

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

--
-- Dumping data for table `ttransaction`
--

INSERT INTO `ttransaction` (`transactionid`, `transactiontype`, `transactiondate`, `remark`, `createdby`, `createddate`) VALUES
('TRIN2021120001', 'IN', '2021-12-29 20:16:18', '', 'Admin', '2021-12-29 20:16:18'),
('TRIN2021120002', 'IN', '2021-12-30 09:24:27', 'Catatan stock masuk 30-12-2021', 'Admin', '2021-12-30 09:24:27'),
('TROUT2021120001', 'OUT', '2021-12-30 09:30:06', '', 'kasir', '2021-12-30 09:30:06');

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

--
-- Dumping data for table `ttransactiondet`
--

INSERT INTO `ttransactiondet` (`transactionid`, `itemcode`, `qty`, `purchaseprice`, `discount`) VALUES
('TRIN2021120001', 'IC2021120001', 20, 51000, NULL),
('TRIN2021120002', 'IC2021120002', 20, 105000, NULL),
('TRIN2021120002', 'IC2021120003', 20, 220000, NULL),
('TROUT2021120001', 'IC2021120002', 5, 110000, '27500'),
('TROUT2021120001', 'IC2021120003', 2, 225000, '0');

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
  MODIFY `groupaccessid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
