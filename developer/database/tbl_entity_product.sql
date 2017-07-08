-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2017 at 10:50 AM
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
-- Table structure for table `tbl_entity_product`
--

DROP TABLE IF EXISTS `tbl_entity_product`;
CREATE TABLE IF NOT EXISTS `tbl_entity_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friendly_uri` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alternate_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `enter_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `price` decimal(6,2) NOT NULL DEFAULT '0.00',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `display_order` int(11) NOT NULL DEFAULT '-1',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

--
-- Dumping data for table `tbl_entity_product`
--

INSERT INTO `tbl_entity_product` (`id`, `friendly_uri`, `name`, `alternate_name`, `description`, `image_id`, `enter_time`, `update_time`, `price`, `category_id`, `display_order`, `active`) VALUES
(3, 'b633', 'B633', '', 'Single long stem rose in box', 13, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '30.00', 1, 0, 1),
(4, 'b344', 'B344', '', 'Long stem red roses in box:  12 stems $105, 18 stems $145, 24 stems $165 (deluxe box).', 14, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '105.00', 1, 1, 1),
(5, 'b557', 'B557', '', 'Long stem red roses with accessory flowers: 5 stems $55, 6 stems $65, 10 stems $100, 12 stems $110, 20 stems $180.', 15, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '55.00', 1, 2, 1),
(6, 'b647', 'B647', '', 'Long stem roses with greens: 6 stems $55, 10 stems $85, 12 stems $95, 20 stems $150.', 16, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '100.00', 1, 3, 1),
(7, 'b738', 'B738', '', 'Long stem red roses and accessory flowers (round bouquet):  10 stems $120, 12 stems $135, 20 stems $210.', 17, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '135.00', 1, 4, 1),
(8, 'b729', 'B729', '', 'Colombian XL roses, from $150.00', 18, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '250.00', 1, 5, 1),
(9, 'b731', 'B731', '', 'Soft colour compact bouquet, from $40', 19, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '65.00', 1, 6, 1),
(10, 'b730', 'B730', '', 'Bouquet in green & white with oriental lilies, from $55', 20, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '60.00', 1, 7, 1),
(11, 'b723', 'B723', '', 'Seasonal flowers in soft colours with disbud chrysanthemums.  From $60', 21, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '70.00', 1, 8, 1),
(12, 'b722', 'B722', '', 'Seasonal flowers in bright colours, from $35', 22, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '60.00', 1, 9, 1),
(13, 'b572', 'B572', '', 'Bouquet in shares of pink to purple, from $50', 23, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '60.00', 1, 10, 1),
(14, 'b569', 'B569', '', 'Asiatic lilies, stocks, carnations, alstroemerias, chrysies, etc.', 24, '2017-07-07 13:58:34', '2017-07-07 13:58:34', '50.00', 1, 11, 1),
(15, 'b721', 'B721', '', 'Seasonal flowers in Spring colours, from $40', 25, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '75.00', 1, 12, 0),
(16, 'b624', 'B624', '', 'Mix orchids bouquet, from $65', 26, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '70.00', 1, 13, 1),
(17, 'b326', 'B326', '', 'Asiatic lilies, gerberas, chrysies, alstroemerias, statice, etc.', 27, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '50.00', 1, 14, 1),
(18, 'b736', 'B736', '', 'Tulips bouquet, single colour from $60, mix-colour from $80', 28, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '0.00', 1, 15, 0),
(19, 'b696', 'B696', '', 'Carnation bouquet with fillers, from $55', 29, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '60.00', 1, 16, 1),
(20, 'b737', 'B737', '', 'Gerberra bouquet with fillers, from $60', 30, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '80.00', 1, 17, 1),
(21, 'b732', 'B732', '', 'Oriental lilies bouquet, pink or white, from $40', 31, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '40.00', 1, 18, 1),
(22, 'b559', 'B559', '', 'Tulips with greens', 32, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '60.00', 1, 19, 1),
(23, 'b733', 'B733', '', 'Large oriental lilies bouquet, pick or white, from $100', 33, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '110.00', 1, 20, 0),
(24, 'b486', 'B486', '', 'Oriental lilies, irises, alstroemerias, leucadendron, chrysies, statice, etc.', 34, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '60.00', 1, 21, 1),
(25, 'b487', 'B487', '', 'Mix bouquet with oriental lilies, from $50', 35, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '65.00', 1, 22, 0),
(26, 'b374', 'B374', '', 'Mix bouquet with vanda orchids, from $70', 36, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '85.00', 1, 23, 1),
(27, 'b485', 'B485', '', 'Oriental lilies, disbuds, alstroemerias, chrysies, etc.', 37, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '60.00', 1, 24, 1),
(28, 'b735', 'B735', '', 'Mix bouquet with roses, from $100', 38, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '135.00', 1, 25, 0),
(29, 'b734', 'B734', '', 'Large bouquet with oriental lilies and orchids, from $100', 39, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '120.00', 1, 26, 1),
(30, 'b561', 'B561', '', 'Mix flowers with red roses, from $70', 40, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '75.00', 1, 27, 1),
(31, 'b705', 'B705', '', 'Mix bouquet with Australian natives and wild flowers, from $50', 41, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '75.00', 1, 28, 0),
(32, 'b370', 'B370', '', 'Seasonal deluxe bouquet, from $120', 42, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '130.00', 1, 29, 1),
(33, 'b488', 'B488', '', 'Oriental lilies, roses, alstroemerias, leucadendron, chrysies, etc.', 43, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '80.00', 1, 30, 1),
(34, 'b575', 'B575', '', 'Oriental lilies, stocks, irises, carnations, chrysies, alstroemerias, etc.', 44, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '70.00', 1, 31, 0),
(35, 'b560', 'B560', '', 'Gerberas (18) with greens', 45, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '90.00', 1, 32, 0),
(36, 'b256', 'B256', '', 'Oriental lilies, roses, chrysies, statice, leafy spurge, etc.', 46, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '130.00', 1, 33, 0),
(37, 'b594', 'B594', '', 'Asiatic lilies, roses, gerberas, alstroemerias, statice, gypsophila, Xmas decorations, etc.', 47, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '50.00', 1, 34, 0),
(38, 'b595', 'B595', '', 'Asiatic lilies, roses, gerberas, gypsophila, Xmas decorations, etc.', 48, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '65.00', 1, 35, 0),
(39, 'b703', 'B703', '', '0', 49, '2017-07-07 13:58:34', '2017-07-07 14:04:29', '75.00', 1, 36, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
