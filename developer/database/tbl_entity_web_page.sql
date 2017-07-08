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
-- Table structure for table `tbl_entity_web_page`
--

DROP TABLE IF EXISTS `tbl_entity_web_page`;
CREATE TABLE IF NOT EXISTS `tbl_entity_web_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friendly_uri` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alternate_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `enter_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `meta_keywords` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'meta keywords',
  `page_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'content title, h1 tag by default',
  `page_content` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'content text, with html tags',
  `extra_field` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_entity_web_page`
--

INSERT INTO `tbl_entity_web_page` (`id`, `friendly_uri`, `name`, `alternate_name`, `description`, `image_id`, `enter_time`, `update_time`, `meta_keywords`, `page_title`, `page_content`, `extra_field`, `parent_id`, `order`) VALUES
(1, '', '', 'Home', 'Southcity Florist is the only independent brick and mortar florist in Wagga Wagga.  We deliver locally and worldwide with no relay middleman fees.  Welcome to visit our website for a great range of products offered, or contact us for further inquiry.', 0, '2017-07-02 12:22:08', '2017-07-03 12:49:02', 'florist, Wagga Wagga, flower, flowers, gift, gifts, balloon, balloons, Base Hospital, Calvary Hospital, online, silk flowers, wedding, baby, sympathy, condolence, get well, delivery, local, overseas, daily', 'Your Local and Online Florist in Wagga Wagga', '<div style="color: #ff0000;padding-right:1em;">\n<p>Our principal florist Annie Lok, has over 20 years of local and international experience in the industry, can help you with everything you need in celebration with flowers.</p>\n<p>Our flowers arrive in the shortest time possible from farms, with minimum cold storage to alleviate heat-sensitivity and guarantee freshness of our products.</p>\n<p>We are the only independent florist in Wagga Wagga, no middleman fee to send flowers out of town, but quality guaranteed.</p>\n<p>Welcome to talk to us, we will listen to you and share our ideas.</p></div>', '\n{"home_slide":[1,2,3,4],"page_content_2":"<div style=\\"font-size: 18px;\\">\\r\\n<div><span style=\\"font-size: 120%; color: #993300;\\"><strong style=\\"line-height: 1.5;\\">Southcity Florist (shop)<\\/strong><\\/span><\\/div>\\r\\n<div><strong style=\\"line-height: 1.5;\\">Kiosk 2, Southcity Shopping Centre,<\\/strong><\\/div>\\r\\n<div><strong>\\r\\nGlenfield Park, Wagga Wagga 2650<br \\/>\\r\\nTel: (02) 6931 4562<\\/strong><\\/div>\\r\\n<br \\/>\\r\\n<div><span style=\\"font-size: 100%; color: #993300;\\"><strong>Flowers on Chaston (workshop)<\\/strong><\\/span><\\/div>\\r\\n<div><strong><span>50 Chaston Street, Wagga Wagga 2650<\\/span><\\/strong><\\/div>\\r\\n<div><strong><span>Tel: (02)&nbsp;<span>6971 3819<\\/span><\\/span><\\/strong><\\/div>\\r\\n<div><strong>\\r\\n<br \\/>Shop Trading Hours<br \\/>\\r\\nMon to Fri: 0900 &ndash; 1800<\\/strong><\\/div>\\r\\n<div><strong>Sat: 0900 - 1600<br \\/>\\r\\nSun: 0900 &ndash; 1300<\\/strong><\\/div>\\r\\n<div><strong>&nbsp;<\\/strong><\\/div>\\r\\n<div><span style=\\"font-size: 80%;\\">ABN: 33 160 165 776\\r\\n<\\/span><\\/div>\\r\\n<\\/div>"}', 0, 0),
(2, 'news-promotion', 'News & Promotion', '', 'Our latest company news and special offers', 2, '2017-07-02 12:22:12', '2017-07-03 12:49:02', 'florist, Wagga Wagga, flower, flowers, gift, gifts, balloon, balloons, Base Hospital, Calvary Hospital, online, silk flowers, wedding, baby, sympathy, condolence, get well, delivery, local, overseas, daily.', 'Company News & Special Offers', '<p>&nbsp;</p>\n<h2>Winter Break</h2>\n<p>&nbsp;</p>\n<p>We will be closed </p>\n<p>25 June - 3 July</p>\n<p>for our much needed break this year.</p>\n<p>&nbsp;</p>\n<p>Wish all are keeping warm,</p>\n<p>and welcome to come in for a little catch up&nbsp;</p>\n<p>at our low season :)</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p><strong style="color: #111111; font-size: 1.17em; letter-spacing: 1px; line-height: 1.5;">Read more on Facebook</strong></p>\n<p><strong><span><a href="http://www.facebook.com/efloristwagga"><img title="Link Us on Fackbook" src="image/join-eflorist-wagga-on-facebook.png" alt="" /></a></span></strong></p>\n<p><strong><span>also our news, Florist''s Tips, </span></strong></p>\n<p><strong><span>and Promotional Specials....</span></strong></p>\n<p>&nbsp;</p>\n<p><span>We are here 24/7,</span></p>\n<p><span>only too happy to hear from you!</span></p>', '', 0, 0),
(3, 'make-payment', 'Make Payment', '', 'Here listed the details of different payment method we accepted.', 0, '2017-07-02 12:22:15', '2017-07-03 12:49:02', 'florist, Wagga Wagga, flower, flowers, gift, gifts, balloon, balloons, Base Hospital, Calvary Hospital, online, silk flowers, wedding, baby, sympathy, condolence, get well, delivery, local, overseas, daily.', 'Make Payment for your Order', '<div class="O">\n<div><strong><span style="text-decoration: underline;">Direct Deposit\n</span></strong></div>\n<div>Westpac&nbsp; BSB: 032769&nbsp; A/C: 486552 or</div>\n<div>Hume&nbsp; BSB: 640000&nbsp; A/C: 58757259</div>\n<div>Name:&nbsp; Southcity Florist</div>\n<div>&nbsp;</div>\n<div><strong>\n</strong></div>\n<div><strong><span style="text-decoration: underline;">Cards\n</span></strong></div>\n<div>Visa / Master / EFTPOS</div>\n<div>&nbsp;</div>\n<div><strong>\n</strong></div>\n<div><strong><span style="text-decoration: underline;">Company Cheques\n</span></strong></div>\n<div>Please make payable to &lsquo;Southcity Florist&rsquo;\n</div>\n<div>with contact name and number at back.</div>\n<div>&nbsp;</div>\n<div><strong>\n</strong></div>\n<div>* Please fax or email deposit slip\n</div>\n<div>if payment is made directly to bank account.</div>\n<div><strong><br /></strong></div>\n<div><strong>\n</strong></div>\n<div><strong>** Corporate credit account available, \n</strong></div>\n<div><strong>please ask our friendly staff. </strong><strong>&nbsp;</strong></div>\n</div>', '', 0, 0),
(4, 'enquiry', 'Enquiry', '', 'Ask any question you have by filling in the form', 0, '2017-07-02 12:22:18', '2017-07-07 13:00:51', 'florist, Wagga Wagga, flower, flowers, gift, gifts, balloon, balloons, Base Hospital, Calvary Hospital, online, silk flowers, wedding, baby, sympathy, condolence, get well, delivery, local, overseas, daily.', 'Enquiry - All questions welcomed', '<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span style="font-size: 140%; color: #993300;"><strong>Southcity Florist</strong></span></p>\r\n<div><strong>Kiosk 2, Southcity Shopping Centre,</strong></div>\r\n<div><strong>Glenfield Park, Wagga Wagga 2650<br /></strong></div>\r\n<div></div>\r\n<div><strong>Tel: (02) 6931 4562</strong></div>\r\n<p>(phone line 24/7 operating)</p>\r\n<p>&nbsp;</p>\r\n<p>Email: <a href="undefined/">flower@efloristwagga.com.au</a>\r\n</p>\r\n<p>Website: <a href="http://www.efloristwagga.com.au">www.efloristwagga.com.au</a> </p>\r\n<p>&nbsp;</p>', '', 0, 0),
(5, 'product', 'Product', '', '', 0, '2017-07-03 12:47:28', '2017-07-04 13:19:08', '', '', '', '', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
