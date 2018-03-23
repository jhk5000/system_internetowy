-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 11 Sty 2017, 18:32
-- Wersja serwera: 5.5.37
-- Wersja PHP: 5.4.45-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `baza`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `if` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `outer_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `add_date` int(11) NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`if`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `departments`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL,
  `title` text COLLATE utf8_bin NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `send_date` int(11) NOT NULL,
  `read_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `student_subjects`
--

CREATE TABLE IF NOT EXISTS `student_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studies`
--

CREATE TABLE IF NOT EXISTS `studies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `deparment_id` int(11) NOT NULL,
  `add_date` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `studies`
--



-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studies_groups`
--

CREATE TABLE IF NOT EXISTS `studies_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studies_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `add_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `studies_groups`
--


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studies_groups_students`
--

CREATE TABLE IF NOT EXISTS `studies_groups_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `studies_groups_students`
--



-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studies_groups_teachers`
--

CREATE TABLE IF NOT EXISTS `studies_groups_teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `studies_groups_teachers`
--



-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `studies_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `subjects`
--



-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subjects_topics`
--

CREATE TABLE IF NOT EXISTS `subjects_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `add_date` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0',
  `archivised` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `subjects_topics`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subject_topic_edits`
--

CREATE TABLE IF NOT EXISTS `subject_topic_edits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `add_date` int(11) NOT NULL,
  `file_type` varchar(10) COLLATE utf8_bin NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `theses_edits`
--

CREATE TABLE IF NOT EXISTS `theses_edits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `edit_date` int(11) NOT NULL,
  `these_id` int(11) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `file_type` varchar(10) COLLATE utf8_bin NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `theses_topics`
--

CREATE TABLE IF NOT EXISTS `theses_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` text COLLATE utf8_bin NOT NULL,
  `promoter_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `add_date` int(11) NOT NULL,
  `take_date` int(11) NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `by_student` int(11) NOT NULL DEFAULT '0',
  `accepted` int(11) NOT NULL DEFAULT '1',
  `rate` int(11) NOT NULL DEFAULT '0',
  `studies_id` int(11) NOT NULL DEFAULT '1',
  `archivised` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `theses_topics`
--



-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(255) COLLATE utf8_bin NOT NULL,
  `mail` varchar(255) COLLATE utf8_bin NOT NULL,
  `register_date` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `token` varchar(100) COLLATE utf8_bin NOT NULL,
  `accepted` int(11) NOT NULL DEFAULT '0',
  `promoter_id` int(11) NOT NULL DEFAULT '0',
  `info` text COLLATE utf8_bin NOT NULL,
  `subject_ids` varchar(255) COLLATE utf8_bin NOT NULL,
  `indeks` varchar(15) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `lastname`, `mail`, `register_date`, `group_id`, `token`, `accepted`, `promoter_id`, `info`, `subject_ids`, `indeks`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', 'admin', 'admin@gmail.com', 1474192988, 3, 'jijiicdn2ewhtvdwrrkw65fuadl55anjh1xckgyzlsi267m9gg', 0, 0, '', '1', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
