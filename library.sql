-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2016 at 01:46 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `allowed_books_num`
--

CREATE TABLE IF NOT EXISTS `allowed_books_num` (
  `maxBooks` int(11) NOT NULL,
  PRIMARY KEY (`maxBooks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `allowed_books_num`
--

INSERT INTO `allowed_books_num` (`maxBooks`) VALUES
(2),
(4),
(6);

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  `sectionId` int(11) NOT NULL,
  `bookcaseId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `publisherId` int(11) NOT NULL,
  `releaseYear` int(11) NOT NULL,
  `copies` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `books_actions`
--

CREATE TABLE IF NOT EXISTS `books_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` char(255) COLLATE utf8_bin NOT NULL,
  `userId` int(11) NOT NULL,
  `actionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE IF NOT EXISTS `borrowed_books` (
  `bookId` int(11) NOT NULL,
  `readerId` int(11) NOT NULL,
  `borrowDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `returnDate` datetime DEFAULT NULL,
  `borrowUserId` int(11) NOT NULL,
  `returnUserId` int(11) NOT NULL,
  PRIMARY KEY (`bookId`,`readerId`,`borrowDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`bookId`, `readerId`, `borrowDate`, `returnDate`, `borrowUserId`, `returnUserId`) VALUES
(1, 1, '2016-05-07 22:33:46', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE IF NOT EXISTS `publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `readers`
--

CREATE TABLE IF NOT EXISTS `readers` (
  `id` int(11) NOT NULL,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  `city` char(255) COLLATE utf8_bin NOT NULL,
  `street` char(255) COLLATE utf8_bin NOT NULL,
  `readerType` int(11) NOT NULL,
  `maxBooks` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `readers`
--

INSERT INTO `readers` (`id`, `name`, `city`, `street`, `readerType`, `maxBooks`, `status`) VALUES
(315524694, 'רועי', 'גדשג גדשג', 'דגכד 4', 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `readers_actions`
--

CREATE TABLE IF NOT EXISTS `readers_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `readerId` int(11) NOT NULL,
  `description` char(255) COLLATE utf8_bin NOT NULL,
  `actionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

--
-- Dumping data for table `readers_actions`
--

INSERT INTO `readers_actions` (`id`, `userId`, `readerId`, `description`, `actionDate`) VALUES
(9, 1, 315524694, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-12 02:37:46');

-- --------------------------------------------------------

--
-- Table structure for table `reader_types`
--

CREATE TABLE IF NOT EXISTS `reader_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(255) COLLATE utf8_bin NOT NULL,
  `bookCost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `reader_types`
--

INSERT INTO `reader_types` (`id`, `title`, `bookCost`) VALUES
(1, 'בוגר', '11.00'),
(2, 'ילד', '10.00'),
(3, 'חייל', '8.00'),
(4, 'פנסיונר', '9.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(255) COLLATE utf8_bin NOT NULL,
  `password` char(255) COLLATE utf8_bin NOT NULL,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`) VALUES
(1, 'roy', '123456', 'רועי');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
