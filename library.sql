-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2016 at 10:21 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `library2`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=14 ;

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
  `releaseYear` int(11) DEFAULT NULL,
  `copies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `books_actions`
--

CREATE TABLE IF NOT EXISTS `books_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `bookId` int(11) DEFAULT NULL,
  `description` char(255) COLLATE utf8_bin NOT NULL,
  `actionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE IF NOT EXISTS `borrowed_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bookId` int(11) NOT NULL,
  `readerId` int(11) NOT NULL,
  `borrowDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `boolReturn` tinyint(1) DEFAULT '0',
  `returnDate` datetime DEFAULT NULL,
  `borrowUserId` int(11) NOT NULL,
  `returnUserId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `readers`
--

CREATE TABLE IF NOT EXISTS `readers` (
  `id` char(9) COLLATE utf8_bin NOT NULL,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  `city` char(255) COLLATE utf8_bin NOT NULL,
  `street` char(255) COLLATE utf8_bin NOT NULL,
  `readerType` int(11) NOT NULL,
  `maxBooks` int(11) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `readers_actions`
--

CREATE TABLE IF NOT EXISTS `readers_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `readerId` int(11) DEFAULT NULL,
  `description` char(255) COLLATE utf8_bin NOT NULL,
  `actionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=38 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=21 ;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `bookcaseAmount`) VALUES
(1, 'פנטזיה', 4),
(2, 'מדע בדיוני', 2),
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
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `type`) VALUES
(3, 'tester', '123456', 'בודק', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` char(255) COLLATE utf8_bin NOT NULL,
  `subject` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `action`, `subject`) VALUES
(1, 'להציג', 'קורא'),
(2, 'ליצור', 'קורא'),
(3, 'לעדכן', 'קורא'),
(4, 'למחוק', 'קורא'),
(5, 'להציג', 'ספר'),
(6, 'ליצור', 'ספר'),
(7, 'לעדכן', 'ספר'),
(8, 'למחוק', 'ספר'),
(9, 'להשאיל', 'ספר'),
(10, 'להציג', 'משתמש'),
(11, 'ליצור', 'משתמש'),
(12, 'לעדכן', 'משתמש'),
(13, 'למחוק', 'משתמש'),
(14, 'לעדכן', 'מדורים'),
(15, 'להציג', 'הרשאות'),
(16, 'לעדכן', 'חוקי השאלה'),
(17, 'לעדכן', 'פרטי משתמש אישי'),
(18, 'להציג', 'טבלת ספרים');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(255) COLLATE utf8_bin NOT NULL,
  `permissions` char(255) COLLATE utf8_bin NOT NULL,
  `hierarchy` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `title`, `permissions`, `hierarchy`) VALUES
(1, 'מנהל', 'all', 1),
(2, 'ספרן בכיר', '1,2,3,4,5,6,7,8,9,10,11,15,17,18', 2),
(3, 'ספרן', '1,2,3,5,6,7,9,10,17,18', 3),
(4, 'קורא', '18', 4);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
