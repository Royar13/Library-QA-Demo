-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2016 at 10:50 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(6, 'ג''. ר. ר. טולקין');

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
  `copies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `sectionId`, `bookcaseId`, `authorId`, `publisherId`, `releaseYear`, `copies`) VALUES
(1, 'אאא', 2, 3, 1, 0, 2002, 2),
(2, 'שר הטבעות', 6, 1, 2, 0, 12, 0),
(3, 'שר הטבעות: אחוות הטבעת', 6, 1, 3, 0, 12, 0),
(4, 'שר הטבעות: אחוות הטבעת', 6, 1, 2, 0, 12, 0),
(5, 'שר הטבעות: אחוות הטבעת', 6, 1, 5, 0, 12, 0),
(6, 'שר הטבעות: אחוות הטבעת', 6, 1, 6, 0, 12, 0),
(7, 'שר הטבעות: בבב', 6, 1, 6, 0, 12, 0),
(8, 'שר הטבעות: בבב', 6, 1, 6, 1, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `books_actions`
--

CREATE TABLE IF NOT EXISTS `books_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `bookId` int(11) NOT NULL,
  `description` char(255) COLLATE utf8_bin NOT NULL,
  `actionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dumping data for table `books_actions`
--

INSERT INTO `books_actions` (`id`, `userId`, `bookId`, `description`, `actionDate`) VALUES
(1, 1, 7, 'המשתמש {user} יצר את הספר {book}', '2016-05-14 19:35:11'),
(2, 1, 8, 'המשתמש {user} יצר את הספר {book}', '2016-05-14 19:39:42');

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
-- Table structure for table `borrow_rules`
--

CREATE TABLE IF NOT EXISTS `borrow_rules` (
  `borrowDays` int(11) NOT NULL,
  `dailyFine` decimal(10,2) NOT NULL,
  PRIMARY KEY (`borrowDays`,`dailyFine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `borrow_rules`
--

INSERT INTO `borrow_rules` (`borrowDays`, `dailyFine`) VALUES
(30, '2.00');

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE IF NOT EXISTS `publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`id`, `name`) VALUES
(1, 'בבב');

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
  `joinDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `readers`
--

INSERT INTO `readers` (`id`, `name`, `city`, `street`, `readerType`, `maxBooks`, `joinDate`) VALUES
(123456789, 'רועי', 'מודיעין', 'יוסף 43', 3, 6, '2016-05-13 17:32:26'),
(222222222, 'גדשג', 'רמת גן', 'בגין 4', 2, 4, '2016-05-14 19:17:05'),
(315524695, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524696, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524697, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524698, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524699, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524700, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-11 17:32:26');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Dumping data for table `readers_actions`
--

INSERT INTO `readers_actions` (`id`, `userId`, `readerId`, `description`, `actionDate`) VALUES
(9, 1, 315524694, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-12 02:37:46'),
(10, 1, 315524694, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-13 04:03:50'),
(11, 1, 222222222, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-14 19:17:05');

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
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  `bookcaseAmount` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `bookcaseAmount`) VALUES
(1, 'מדע בדיוני', 2),
(2, 'פנטזיה', 3),
(3, 'דרמה', 4),
(4, 'אנציקלופדיות', 1),
(5, 'קומיקס', 1),
(6, 'מתח', 2),
(7, 'ספרי ילדים', 3);

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
