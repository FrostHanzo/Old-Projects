-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2012 at 02:24 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `in3008`
--

-- --------------------------------------------------------

--
-- Table structure for table `in3008_addresses`
--

CREATE TABLE IF NOT EXISTS `in3008_addresses` (
  `addressID` smallint(6) NOT NULL AUTO_INCREMENT,
  `addressName` varchar(100) NOT NULL DEFAULT '',
  `addressLine1` varchar(255) NOT NULL DEFAULT '',
  `addressLine2` varchar(255) DEFAULT NULL,
  `addressLine3` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `State` varchar(255) DEFAULT NULL,
  `postCode` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  PRIMARY KEY (`addressID`),
  UNIQUE KEY `addressID` (`addressID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `in3008_categories`
--

CREATE TABLE IF NOT EXISTS `in3008_categories` (
  `categoryID` smallint(6) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) NOT NULL,
  `categoryDescription` varchar(255) DEFAULT NULL,
  `categoryParent` int(11) DEFAULT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `categoryID` (`categoryID`),
  KEY `categoryName` (`categoryName`,`categoryDescription`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `in3008_categories`
--

INSERT INTO `in3008_categories` (`categoryID`, `categoryName`, `categoryDescription`, `categoryParent`) VALUES
(1, 'Radio Automation Software', 'Complete software solutions for your radio automation needs', 0),
(2, 'Playlist Software', 'Software for creating and managing playlists', 0),
(3, 'Broadcasting Software', 'Solutions for live and Internet Broadcasting', 0);

-- --------------------------------------------------------

--
-- Table structure for table `in3008_orders`
--

CREATE TABLE IF NOT EXISTS `in3008_orders` (
  `orderNumber` smallint(6) NOT NULL AUTO_INCREMENT,
  `orderDate` datetime NOT NULL,
  `orderAmount` smallint(6) NOT NULL,
  `userID` smallint(6) DEFAULT NULL,
  `shippingMethod` varchar(50) DEFAULT NULL,
  `shippingName` varchar(255) NOT NULL,
  `shippingAddress` text NOT NULL,
  `shippingPostCode` varchar(10) NOT NULL,
  `orderStatus` enum('paid','processing','processed') NOT NULL,
  PRIMARY KEY (`orderNumber`),
  UNIQUE KEY `orderNumber` (`orderNumber`),
  KEY `orderNumber_2` (`orderNumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `in3008_orders`
--

INSERT INTO `in3008_orders` (`orderNumber`, `orderDate`, `orderAmount`, `userID`, `shippingMethod`, `shippingName`, `shippingAddress`, `shippingPostCode`, `orderStatus`) VALUES
(1, '2012-04-27 11:53:10', 2399, 2, 'Royal Mail Next Day Delivery', 'Josh Jones', 'ATTN: Department of Audio Production\r\nGlobal Radio LTD\r\nGlobal Radio House\r\nLeicester Square\r\nLondon', 'W1 1TB', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `in3008_paymentcards`
--

CREATE TABLE IF NOT EXISTS `in3008_paymentcards` (
  `cardID` smallint(6) NOT NULL AUTO_INCREMENT,
  `cardType` enum('Visa','Mastercard','AMEX','Eurocard') NOT NULL,
  `cardNumber` int(16) NOT NULL,
  `cardIssuedDate` date NOT NULL,
  `cardExpiryDate` date NOT NULL,
  PRIMARY KEY (`cardID`),
  UNIQUE KEY `cardID` (`cardID`),
  UNIQUE KEY `cardNumber` (`cardNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `in3008_payments`
--

CREATE TABLE IF NOT EXISTS `in3008_payments` (
  `paymentID` smallint(6) NOT NULL AUTO_INCREMENT,
  `paymentDate` datetime NOT NULL,
  `paymentAmount` double NOT NULL,
  `paymentCardID` smallint(6) NOT NULL,
  PRIMARY KEY (`paymentID`),
  UNIQUE KEY `paymentID` (`paymentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `in3008_products`
--

CREATE TABLE IF NOT EXISTS `in3008_products` (
  `productID` smallint(6) NOT NULL AUTO_INCREMENT,
  `productName` varchar(100) NOT NULL,
  `productDescription` text,
  `productPrice` float NOT NULL,
  `productQuantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`productID`),
  UNIQUE KEY `productID` (`productID`),
  KEY `productName` (`productName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `in3008_products`
--

INSERT INTO `in3008_products` (`productID`, `productName`, `productDescription`, `productPrice`, `productQuantity`) VALUES
(1, 'RAS Studio Suite', 'The RAS Studio Suite is the ultimate solution for your radio needs.\r\n<br/>\r\nIt is a complete radio automation system consisting of:\r\n<ul>\r\n<li>Playlist scheduler - allowing you to create professional playlists in minutes</li>\r\n<li>Playout Module - Allowing you to programatically setup the playlists that you have created with the playlist module for it to play them at the preconfigured times</li>\r\n<li>Internet Broadcasting Module - Enabling your radio station to broadcast everything played out by the playout module over the Internet in the most popular audio formats such as MP3, WAVE and AAC+.</li>\r\n</ul>', 999.99, 9998),
(2, 'RAS Scheduler', 'This is Radio Automation System''s flagship playlist software.\r\n<br/>\r\nIt combines 20 years'' research of how radio stations manage their programs and allows programming of complex audio content, advertisements and audio spots with high precision and efficiency.\r\n<br/>\r\n The software is easy and intuitive to use and allows you to create very complex playlists of your content in a few clicks which makes it the perfect choice for radio stations requiring complex playlist rotation and scheduling.\r\n<h3>Integration with other playout systems.</h3>\r\n <br/>\r\nThe RAS Playlist scheduler can be integrated with various third-party playout systems such as SAM Broadcaster, Jazler and Station Studio, so you can be confident that it can easily be integrated into your existing infrastructure.', 200, 10000),
(3, 'RAS Broadcaster', 'The RAS Broadcaster helps you broadcast your audio contents over the Internet. It takes the signal from any audio source including a computer''s soundcard, mixer or basically any audio device that can be plugged into a computer and broadcasts the signal over the Internet using one of the following industry-standard formats:\r\n<ul>\r\n<li>MP3 (8-320 KBPS),</li>\r\n<li>WAVE</li>\r\n<li>AAC+ (16-128 KBPS)</li>\r\n<br/>\r\nThe software has capabilities for recording the contents of what has been broadcasted so you can keep an archive of everything that is being played for your listeners to listen on-demand or for your internal housekeeping.', 399.98, 9999);

-- --------------------------------------------------------

--
-- Table structure for table `in3008_products2categories`
--

CREATE TABLE IF NOT EXISTS `in3008_products2categories` (
  `productID` smallint(6) DEFAULT NULL,
  `categoryID` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `in3008_products2categories`
--

INSERT INTO `in3008_products2categories` (`productID`, `categoryID`) VALUES
(1, 1),
(4, 0),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `in3008_products2images`
--

CREATE TABLE IF NOT EXISTS `in3008_products2images` (
  `productID` smallint(6) DEFAULT NULL,
  `imageName` varchar(255) DEFAULT NULL,
  KEY `imageName` (`imageName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `in3008_products2orders`
--

CREATE TABLE IF NOT EXISTS `in3008_products2orders` (
  `productID` smallint(6) NOT NULL,
  `productQuantity` smallint(6) NOT NULL,
  `orderNumber` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `in3008_products2orders`
--

INSERT INTO `in3008_products2orders` (`productID`, `productQuantity`, `orderNumber`) VALUES
(1, 2, 1),
(3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `in3008_users`
--

CREATE TABLE IF NOT EXISTS `in3008_users` (
  `userID` smallint(6) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL DEFAULT '',
  `password` blob NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('m','f') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `level` enum('administrator','customer') DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userID` (`userID`),
  UNIQUE KEY `email` (`email`),
  KEY `userName` (`userName`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `in3008_users`
--

INSERT INTO `in3008_users` (`userID`, `userName`, `password`, `email`, `gender`, `phone`, `level`) VALUES
(1, 'administrator', 0x34313934643137303665643166343038643565303264363732373737303139663464353338356337363661386336636138616362613331363764333661376239, 'georgijivankin@gmail.com', 'm', '+447930974683', 'administrator'),
(2, 'josh', 0x35343866646239393731646266393164613631323338646562343730613731313435323762313561353834386633643163636633376238356335393161396330, 'josh2012@example.com', 'm', '+447712345678', 'customer'),
(3, 'mary', 0x33376432353663653331393936663761373331346137353261373233653730653266333638316163353965306537313364346333306537646463366533623436, 'mary_pink@example.com', 'f', '+447712345678', 'customer'),
(4, 'james_sanders', 0x64333863666639653865356638326535646630623531303634656637663035323838633761653536336433376466326237373330373064376338356634336432, 'james.sanders@example.com', 'm', '+442012345678', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `in3008_users2addresses`
--

CREATE TABLE IF NOT EXISTS `in3008_users2addresses` (
  `userID` smallint(6) NOT NULL,
  `addressID` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
