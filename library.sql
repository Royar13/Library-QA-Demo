-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2016 at 10:15 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(6, 'ג''. ר. ר. טולקין'),
(7, 'דאגלס אדמס'),
(8, 'דאג'),
(9, 'דאגב'),
(10, 'גגג'),
(11, 'גדשגשד'),
(12, 'לאה משהו');

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
(1, 'הסיפור שכן נגמר', 1, 1, 12, 3, 2002, 1),
(2, 'שר הטבעות', 6, 1, 2, 0, 12, 1),
(3, 'שר הטבעות: אחוות הטבעת', 6, 1, 3, 0, 12, 0),
(4, 'שר הטבעות: אחוות הטבעת', 6, 1, 2, 0, 12, 0),
(5, 'שר הטבעות: אחוות הטבעת', 6, 1, 5, 0, 12, 0),
(6, 'שר הטבעות: אחוות הטבעת', 6, 1, 6, 0, 12, 0),
(7, 'שר הטבעות: בבב', 6, 1, 6, 0, 12, 0),
(8, 'שר הטבעות: בבב', 6, 1, 6, 1, 12, 0),
(10, 'מדריך הטרמפיסט לגלקסיה', 1, 2, 7, 2, 1900, 2),
(11, 'גגדשגדשג', 2, 5, 8, 3, NULL, 0),
(12, 'גשדגשדג', 1, 1, 9, 3, 0, 0),
(15, 'נסיון מאה', 1, 1, 11, 5, 2002, 1),
(17, 'הספר הטוב בעולם', 2, 2, 7, 7, 1234, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=47 ;

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
(24, 1, 15, 'המשתמש {user} יצר את הספר {book}', '2016-05-23 16:14:29'),
(25, 1, 16, 'המשתמש {user} יצר את הספר {book}', '2016-05-23 16:15:53'),
(26, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 08:54:47'),
(27, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 08:55:15'),
(28, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 09:18:31'),
(29, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 09:18:39'),
(30, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 10:20:11'),
(31, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 10:22:39'),
(32, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 10:24:28'),
(33, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 10:26:05'),
(34, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-24 10:26:15'),
(35, 1, NULL, 'המשתמש {user} מחק את ספר מס'' 16', '2016-05-24 10:54:46'),
(36, 1, NULL, 'המשתמש {user} מחק את הספר ''המסעדה בסוף היקום''', '2016-05-24 10:57:30'),
(37, 1, 1, 'המשתמש {user} עדכן את הספר {book}', '2016-05-25 18:40:35'),
(38, 1, NULL, 'המשתמש {user} מחק את הספר "im null"', '2016-05-27 22:33:43'),
(39, 3, 333333333, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-30 13:06:15'),
(40, 3, 888888888, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-30 13:08:13'),
(41, 3, 888888988, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-30 13:09:11'),
(42, 3, 444444444, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-30 13:11:12'),
(43, 3, 17, 'המשתמש {user} יצר את הספר {book}', '2016-05-30 13:14:04'),
(44, 3, 17, 'המשתמש {user} עדכן את הספר {book}', '2016-05-30 13:14:12'),
(45, 3, 11, 'המשתמש {user} עדכן את הספר {book}', '2016-05-30 21:08:43'),
(46, 3, 11, 'המשתמש {user} עדכן את הספר {book}', '2016-05-30 21:08:46');

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

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `bookId`, `readerId`, `borrowDate`, `boolReturn`, `returnDate`, `borrowUserId`, `returnUserId`) VALUES
(4, 1, 315524695, '2016-03-06 14:32:47', 0, '2016-05-23 23:30:21', 1, 1),
(5, 2, 315524695, '2016-05-16 14:32:47', 1, '2016-05-24 09:49:30', 1, 1),
(6, 1, 222222222, '2016-05-23 08:49:57', 0, NULL, 1, NULL),
(7, 10, 315524695, '2016-05-23 23:30:21', 1, '2016-05-24 09:49:30', 1, 1),
(8, 15, 315524695, '2016-05-24 09:49:30', 1, '2016-05-24 09:50:15', 1, 1),
(9, 2, 111111111, '2016-05-30 23:09:55', 1, '2016-05-30 23:10:05', 3, 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`id`, `name`) VALUES
(1, 'בבב'),
(2, 'כדגכדגכ'),
(3, ''),
(4, 'גגג'),
(5, 'גשדג'),
(6, 'גשדגשדג'),
(7, 'ההוצאה הטובה בעולם');

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

--
-- Dumping data for table `readers`
--

INSERT INTO `readers` (`id`, `name`, `city`, `street`, `readerType`, `maxBooks`, `joinDate`) VALUES
('111111111', 'בדיקה', 'יאשי', 'כגדכ 56', 2, 2, '2016-05-30 23:09:44'),
('315524695', 'משאיל הספרים', 'גדשג', 'גדשג 534', 4, 2, '2016-05-13 17:32:26'),
('333333333', 'אמיר לוין', '', '', 3, 2, '2016-05-30 13:06:15'),
('444444444', 'גדשגש', '', '', 2, 4, '2016-05-30 13:10:41'),
('888888888', 'רועי', 'גדשג גדשג', 'דגכד 4', 1, 2, '2016-05-30 13:08:13'),
('888888988', 'רועי', 'גדשג גדשג', 'דגכד 4', 1, 2, '2016-05-30 13:08:50');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=37 ;

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
(21, 1, 315524699, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-23 08:48:28'),
(22, 1, 121212121, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-23 10:18:13'),
(23, 1, 315524694, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-23 17:35:42'),
(24, 1, 315524695, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-24 08:51:37'),
(25, 1, 222222222, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-24 09:19:20'),
(26, 1, NULL, 'המשתמש {user} מחק את הקורא ''גשדג''', '2016-05-27 19:45:47'),
(27, 1, NULL, 'המשתמש {user} מחק את הקורא ''גשדג''', '2016-05-27 19:52:45'),
(28, 1, NULL, 'המשתמש {user} מחק את הקורא ''גשדג''', '2016-05-27 19:55:30'),
(29, 3, 444444444, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-30 13:10:41'),
(30, 3, 444444444, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-30 13:11:38'),
(31, 3, 1, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-30 13:40:58'),
(32, 3, NULL, 'המשתמש {user} מחק את הקורא "גדשגדש"', '2016-05-30 19:38:48'),
(33, 3, 444444444, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-30 23:09:32'),
(34, 3, 111111111, 'המשתמש {user} יצר את הקורא {reader}', '2016-05-30 23:09:44'),
(35, 3, 111111111, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-30 23:10:11'),
(36, 3, 111111111, 'המשתמש {user} עדכן את הקורא {reader}', '2016-05-30 23:10:18');

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
(1, 'פנטזיה', 9),
(2, 'מדע בדיוני', 2),
(3, 'דרמה', 4),
(4, 'אנציקלופדיות', 1),
(5, 'קומיקס', 1),
(6, 'מתח', 2),
(7, 'ספרי ילדים', 3),
(8, 'בבב', 9),
(9, 'להלה', 3),
(10, 'להלה', 3),
(11, 'גגדשג', 3),
(13, 'גגדשג', 3),
(15, 'גגדשג', 3),
(16, 'המדור החדש', 2),
(17, 'המדור החדש ביותר', 3),
(18, 'יאי', 5),
(20, 'משהו', 3);

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
(3, 'aaaa', '111111', 'עדי', 1),
(7, 'bbbb', '111111', 'גדשגשדגשד', 4),
(8, 'ccc', 'aaaaaa', 'רועי', 1);

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
