-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2016 at 02:50 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(6, 'ג''. ר. ר. טולקין'),
(7, 'דאגלס אדמס'),
(8, 'דאג'),
(9, 'דאגב'),
(10, 'גגג'),
(11, 'שש');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=18 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `sectionId`, `bookcaseId`, `authorId`, `publisherId`, `releaseYear`, `copies`) VALUES
(1, 'הסיפור שאינו נגמר', 6, 2, 10, 3, 10, 4),
(2, 'שר הטבעות', 6, 1, 2, 0, 12, 1),
(3, 'שר הטבעות: אחוות הטבעת', 6, 1, 3, 0, 12, 0),
(4, 'שר הטבעות: אחוות הטבעת', 6, 1, 2, 0, 12, 0),
(5, 'שר הטבעות: אחוות הטבעת', 6, 1, 5, 0, 12, 0),
(6, 'שר הטבעות: אחוות הטבעת', 6, 1, 6, 0, 12, 0),
(7, 'שר הטבעות: בבב', 6, 1, 6, 0, 12, 0),
(8, 'שר הטבעות: בבב', 6, 1, 6, 1, 12, 0),
(9, 'im null', 1, 1, 1, 1, NULL, 0),
(10, 'מדריך הטרמפיסט לגלקסיה', 1, 2, 7, 2, 1900, 2),
(11, 'גגדשגדשג', 2, 1, 8, 3, 0, 0),
(12, 'גשדגשדג', 1, 1, 9, 3, 0, 0),
(13, 'המסעדה בסוף היקום', 1, 1, 7, 3, 0, 0),
(14, 'גגג', 2, 3, 11, 3, 0, 0),
(15, 'מדריך הטרמפיסט לגלקסיה', 1, 1, 7, 3, 1979, 5),
(16, 'מדריך הטרמפיסט לגלקסיה', 1, 1, 7, 3, 1979, 5),
(17, 'מדריך הטרמפיסט לגלקסיה', 1, 1, 7, 3, 1979, 5);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=30 ;

--
-- Dumping data for table `books_actions`
--

INSERT INTO `books_actions` (`id`, `userId`, `bookId`, `description`, `actionDate`) VALUES
(1, 1, 7, 'המשתמש {user} יצר את הספר {book}', '2016-05-14 19:35:11'),
(2, 1, 8, 'המשתמש {user} יצר את הספר {book}', '2016-05-14 19:39:42'),
(3, 1, 10, 'המשתמש {user} יצר את הספר {book}', '2016-05-15 10:09:24'),
(4, 1, 11, 'המשתמש {user} יצר את הספר {book}', '2016-05-15 10:11:35'),
(5, 1, 12, 'המשתמש {user} יצר את הספר {book}', '2016-05-15 10:21:45'),
(6, 1, 13, 'המשתמש {user} יצר את הספר {book}', '2016-05-15 10:23:14'),
(7, 1, 14, 'המשתמש {user} יצר את הספר {book}', '2016-05-15 12:19:26'),
(8, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 12:22:55'),
(9, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:37:46'),
(10, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:37:51'),
(11, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:38:22'),
(12, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:38:31'),
(13, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:45:18'),
(14, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:56:16'),
(15, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:56:40'),
(16, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:57:16'),
(17, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:57:40'),
(18, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:57:53'),
(19, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:58:00'),
(20, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:58:42'),
(21, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-15 16:59:16'),
(22, 1, 11, 'המשתמש {user} עדכן את הספר {book}', '2016-05-16 09:32:06'),
(23, 1, 13, 'המשתמש {user} עדכן את הספר {book}', '2016-05-16 16:16:45'),
(24, 1, 11, 'המשתמש {user} עדכן את הספר {book}', '2016-05-17 22:28:24'),
(25, 1, 14, 'המשתמש {user} יצר את הספר {book}', '2016-05-17 23:28:05'),
(26, 1, 15, 'המשתמש {user} יצר את הספר {book}', '2016-05-18 00:13:16'),
(27, 1, 16, 'המשתמש {user} יצר את הספר {book}', '2016-05-18 00:36:21'),
(28, 1, 17, 'המשתמש {user} יצר את הספר {book}', '2016-05-18 00:37:41'),
(29, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-18 00:53:33');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `bookId`, `readerId`, `borrowDate`, `boolReturn`, `returnDate`, `borrowUserId`, `returnUserId`) VALUES
(4, 1, 315524695, '2016-03-14 14:32:47', 1, '2016-05-17 20:09:06', 1, 1),
(5, 2, 315524695, '2016-05-16 14:32:47', 1, '2016-05-17 20:39:08', 1, 1),
(6, 1, 315524695, '2016-05-17 20:39:08', 1, '2016-05-17 20:41:18', 1, 1),
(7, 2, 315524695, '2016-05-17 22:03:45', 1, '2016-05-17 22:22:25', 1, 1),
(8, 2, 315524696, '2016-05-17 22:27:44', 1, '2016-05-17 22:27:56', 1, 1);

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
(20, '2.00');

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE IF NOT EXISTS `publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`id`, `name`) VALUES
(1, 'בבב'),
(2, 'כדגכדגכ'),
(3, ''),
(4, 'גגג');

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
(315524695, 'משאיל הספרים', 'גדשג', 'גדשג 534', 4, 2, '2016-03-01 17:32:26'),
(315524696, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524697, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524698, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-13 17:32:26'),
(315524699, 'גשדג', 'גדשג', 'בבב 4', 4, 4, '2016-05-13 17:32:26'),
(315524700, 'גשדג', 'גדשג', 'גדשג 534', 1, 4, '2016-05-11 17:32:26'),
(333333333, 'רקר', '', '', 2, 2, '2016-05-17 23:28:28'),
(777777777, 'גדשגדשג', '', '', 4, 6, '2016-05-17 23:40:03'),
(999999999, 'בבבבב', '', '', 1, 2, '2016-05-17 23:33:03');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=25 ;

--
-- Dumping data for table `readers_actions`
--

INSERT INTO `readers_actions` (`id`, `userId`, `readerId`, `description`, `actionDate`) VALUES
(9, 1, 315524694, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-12 02:37:46'),
(10, 1, 315524694, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-13 04:03:50'),
(11, 1, 222222222, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-14 19:17:05'),
(12, 1, 123456789, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 02:19:19'),
(13, 1, 315524699, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 08:47:40'),
(14, 1, 315524699, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 08:52:26'),
(15, 1, 315524699, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 08:52:35'),
(16, 1, 315524695, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 08:53:50'),
(17, 1, 315524695, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 09:09:08'),
(18, 1, 315524695, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 09:29:59'),
(19, 1, 315524695, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 09:30:06'),
(20, 1, 222222222, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-16 16:21:59'),
(21, 1, 315524699, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-17 22:10:27'),
(22, 1, 333333333, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-17 23:28:28'),
(23, 1, 999999999, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-17 23:33:03'),
(24, 1, 777777777, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-17 23:40:03');

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
