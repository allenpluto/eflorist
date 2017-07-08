-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2017 at 10:52 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eflorist`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entity_category`
--

DROP TABLE IF EXISTS `tbl_entity_category`;
CREATE TABLE IF NOT EXISTS `tbl_entity_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friendly_uri` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alternate_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `enter_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `display_order` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_entity_category`
--

INSERT INTO `tbl_entity_category` (`id`, `friendly_uri`, `name`, `alternate_name`, `description`, `image_id`, `enter_time`, `update_time`, `display_order`) VALUES
(1, 'bouquet', 'Bouquet', 'B', '', 5, '2017-07-02 12:58:44', '2017-07-08 08:52:04', 0),
(2, 'arrangement', 'Arrangement', 'A', '', 6, '2017-07-02 12:58:44', '2017-07-08 08:52:12', 1),
(3, 'new-born', 'New Born', 'N', '', 7, '2017-07-02 12:58:59', '2017-07-08 08:52:14', 2),
(4, 'sympathy', 'Sympathy', 'S', '', 8, '2017-07-02 12:58:59', '2017-07-08 08:52:17', 3),
(5, 'wedding', 'Wedding', 'W', '', 9, '2017-07-02 12:59:18', '2017-07-08 08:52:19', 4),
(6, 'artificial', 'Artificial', 'M', '', 10, '2017-07-02 12:59:18', '2017-07-08 08:52:21', 5),
(7, 'fruit-hamper', 'Fruit Hamper', 'F', '', 11, '2017-07-02 12:59:37', '2017-07-08 08:52:23', 6),
(8, 'gift-ideas', 'Gift Ideas', 'G', '', 12, '2017-07-02 12:59:37', '2017-07-08 08:52:28', 7);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
