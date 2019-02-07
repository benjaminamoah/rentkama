-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 10, 2015 at 11:30 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trader`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category`) VALUES
(1, 'accomodation'),
(2, 'events locations'),
(3, 'vehicles and transport services'),
(4, 'events seating'),
(5, 'electronic equipment'),
(6, 'live music services'),
(7, 'catering services'),
(8, 'decorations and interior design services'),
(9, 'other');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_ref` varchar(254) NOT NULL,
  `image` varchar(254) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

--
-- Dumping data for table `images`
--


-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE IF NOT EXISTS `listings` (
  `listing_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `listing` varchar(255) NOT NULL,
  `region` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `details` text,
  `price` int(11) NOT NULL,
  `rate` varchar(6) NOT NULL,
  `telephone` varchar(120) NOT NULL,
  `date_posted` int(11) NOT NULL,
  `images` text,
  `listing_ref` varchar(250) NOT NULL,
  `files` text,
  PRIMARY KEY (`listing_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`listing_id`, `category_id`, `user_id`, `listing`, `region`, `location`, `details`, `price`, `rate`, `telephone`, `date_posted`, `images`, `listing_ref`, `files`) VALUES
(81, 2, 5, 'restaurant at ada', 'Greater Accra', 'ada', 'beautiful scenery', 0, '-- --', '0278 677 572', 1425978776, '', '1425978776_chacy@gmail.com', ''),
(82, 1, 5, '2 bedroom house', 'Ashanti', 'Agobloshie', 'self-contained', 0, '-- --', '0226 822 788', 1425978870, '', '1425978870_chacy@gmail.com', ''),
(76, 1, 5, '2 bedroom apartment', 'Greater Accra', 'Osu', 'with toilet and bath', 400, 'Month', '0546 881 122', 1425449670, '', '1425449670_chacy@gmail.com', ''),
(77, 3, 5, 'Cars for hire', 'Greater Accra', 'tudu', 'with chauffeur', 0, '-- --', '054 7394 182', 1425977896, '', '1425977896_chacy@gmail.com', ''),
(78, 3, 5, 'Cars for hire', 'Greater Accra', 'tudu', 'with chauffeur', 0, '-- --', '054 7394 182', 1425978103, '', '1425978103_chacy@gmail.com', ''),
(79, 4, 5, 'canopies and chairs', 'Greater Accra', 'awoshie', 'all sorts and colors', 0, '-- --', '0278 677 572', 1425978174, '', '1425978174_chacy@gmail.com', ''),
(80, 6, 5, 'live band', 'Greater Accra', 'high street', 'we play for all events', 600, 'Day', '0546 881 122', 1425978705, '', '1425978705_chacy@gmail.com', ''),
(75, 1, 5, '4 bedroom apartment ', 'Greater Accra', 'Asarebotwe', 'with toilet and bath and game room', 1000, 'Month', '0278 677 572', 1425449605, '', '1425449605_chacy@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `about_user` text,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `about_user`, `date_created`) VALUES
(1, 'A Listing', 'utopm7@gmail.com', 'utopm7', '', 1385393618),
(2, 'John Smith', 'utopm4@gmail.com', 'utopm4', '', 0),
(3, 'Agent Tory', 'troy@gmail.com', 'troy', '', 1385483831),
(4, 'Yakim', 'yakim@yahoo.com', 'yakim', '', 1385549770),
(5, 'chacy', 'chacy@gmail.com', 'chacy', '', 1420614760),
(8, 'hopson', 'hopson@gmil.com', 'hopson', '', 1425975599);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
