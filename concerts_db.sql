-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 29 Ιουν 2018 στις 13:31:32
-- Έκδοση διακομιστή: 5.6.21
-- Έκδοση PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Βάση δεδομένων: `concerts_db`
--
CREATE DATABASE IF NOT EXISTS `concerts_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `concerts_db`;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `concerts`
--

DROP TABLE IF EXISTS `concerts`;
CREATE TABLE IF NOT EXISTS `concerts` (
`concert_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `title` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `concerts`
--

INSERT INTO `concerts` (`concert_id`, `date`, `title`) VALUES
(1, '2018-07-26', 'Tchaikovsky Symphony Orchestra'),
(2, '2018-07-22', 'Alkistis Protopsalti'),
(3, '2018-08-05', 'John Kotsiras'),
(4, '2018-07-15', 'Nana Mouschouri');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `prices`
--

DROP TABLE IF EXISTS `prices`;
CREATE TABLE IF NOT EXISTS `prices` (
`p_id` int(10) unsigned NOT NULL,
  `concert_id` int(10) unsigned NOT NULL,
  `zone_id` int(10) unsigned NOT NULL,
  `price` double unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `prices`
--

INSERT INTO `prices` (`p_id`, `concert_id`, `zone_id`, `price`) VALUES
(1, 1, 1, 28),
(2, 1, 2, 45),
(3, 1, 3, 60),
(4, 1, 4, 75),
(5, 2, 8, 10),
(6, 2, 9, 15),
(7, 3, 5, 10),
(8, 3, 6, 15),
(9, 3, 7, 18),
(10, 4, 1, 20),
(11, 4, 2, 30),
(12, 4, 3, 40),
(13, 4, 4, 48);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
`r_id` int(10) unsigned NOT NULL,
  `uname` varchar(100) NOT NULL,
  `p_id` int(10) unsigned NOT NULL,
  `seat` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `reservations`
--

INSERT INTO `reservations` (`r_id`, `uname`, `p_id`, `seat`) VALUES
(8, 'maridros', 5, 16),
(9, 'maridros', 5, 17),
(10, 'haha', 1, 1),
(11, 'haha', 1, 2),
(12, 'haha', 1, 3),
(13, 'haha', 1, 4),
(14, 'haha', 6, 1),
(15, 'haha', 6, 2),
(17, 'maridros', 13, 3),
(18, 'maridros', 13, 8),
(19, 'maridros', 13, 10),
(20, 'eos', 4, 1),
(21, 'eos', 4, 2),
(22, 'eos', 4, 3),
(30, 'maridros', 8, 1),
(31, 'maridros', 8, 2);

--
-- Δείκτες `reservations`
--
DROP TRIGGER IF EXISTS `reservations_after_insert_trgr`;
DELIMITER //
CREATE TRIGGER `reservations_after_insert_trgr` AFTER INSERT ON `reservations`
 FOR EACH ROW BEGIN
 
 DECLARE amount DOUBLE;

 select price
   into amount 
   from prices
  where prices.p_id = NEW.p_id;
 
 UPDATE users
    SET card = card - amount
  WHERE users.uname = NEW.uname;
 
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `reservations_before_delete_trgr`;
DELIMITER //
CREATE TRIGGER `reservations_before_delete_trgr` BEFORE DELETE ON `reservations`
 FOR EACH ROW BEGIN
 
 DECLARE amount DOUBLE;
 
 SELECT price into amount FROM  prices where prices.p_id = OLD.p_id;

UPDATE users
   SET card = card + amount
 WHERE users.uname = OLD.uname;
 

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `stages`
--

DROP TABLE IF EXISTS `stages`;
CREATE TABLE IF NOT EXISTS `stages` (
`stage_id` int(10) unsigned NOT NULL,
  `stage` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `stages`
--

INSERT INTO `stages` (`stage_id`, `stage`) VALUES
(1, 'Irodio'),
(2, 'Kalimarmaro'),
(3, 'Theatre Alice Vougiouklaki');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `uname` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `card` double unsigned NOT NULL,
  `blocked` int(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`uname`, `pass`, `name`, `surname`, `phone`, `email`, `card`, `blocked`) VALUES
('eos', '81dc9bdb52d04dc20036dbd8313ed055', 'P.', 'Eos', '6969696969', 'p_eos@gmail.com', 7275, 0),
('haha', '4e4d6c332b6fe62a63afe56171fd3725', 'ha', 'ha', '1234567890', 'haha@gmail.com', 13, 0),
('mariad', '60e77b7eeb8ad1ed5df4b9199b5a79bd', 'maria', 'lolo', '1234567878', 'koula@gmail.com', 0, 0),
('maridros', '81dc9bdb52d04dc20036dbd8313ed055', 'maria', 'drosou', '6978088931', 'mariaki_1993@hotmail.com', 154, 0),
('yolo', '81dc9bdb52d04dc20036dbd8313ed055', 'yo', 'lo', '2101234567', 'yolo@yolo.com', 0, 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE IF NOT EXISTS `zones` (
`zone_id` int(10) unsigned NOT NULL,
  `zone` varchar(500) NOT NULL,
  `stage_id` int(10) unsigned NOT NULL,
  `seats` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `zones`
--

INSERT INTO `zones` (`zone_id`, `zone`, `stage_id`, `seats`) VALUES
(1, 'Upper Frieze', 1, 20),
(2, 'Zone C', 1, 30),
(3, 'Zone B', 1, 20),
(4, 'Zone A', 1, 10),
(5, 'Stands', 2, 50),
(6, 'Arena Back', 2, 60),
(7, 'Arena Front', 2, 40),
(8, 'Zone B', 3, 30),
(9, 'Zone A', 3, 20);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `concerts`
--
ALTER TABLE `concerts`
 ADD PRIMARY KEY (`concert_id`);

--
-- Ευρετήρια για πίνακα `prices`
--
ALTER TABLE `prices`
 ADD PRIMARY KEY (`p_id`), ADD KEY `concert_id` (`concert_id`), ADD KEY `zone_id` (`zone_id`);

--
-- Ευρετήρια για πίνακα `reservations`
--
ALTER TABLE `reservations`
 ADD PRIMARY KEY (`r_id`), ADD KEY `uname` (`uname`), ADD KEY `p_id` (`p_id`);

--
-- Ευρετήρια για πίνακα `stages`
--
ALTER TABLE `stages`
 ADD PRIMARY KEY (`stage_id`), ADD KEY `stage_id` (`stage_id`), ADD KEY `stage_id_2` (`stage_id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`uname`);

--
-- Ευρετήρια για πίνακα `zones`
--
ALTER TABLE `zones`
 ADD PRIMARY KEY (`zone_id`), ADD KEY `stage_id` (`stage_id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `concerts`
--
ALTER TABLE `concerts`
MODIFY `concert_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT για πίνακα `prices`
--
ALTER TABLE `prices`
MODIFY `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT για πίνακα `reservations`
--
ALTER TABLE `reservations`
MODIFY `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT για πίνακα `stages`
--
ALTER TABLE `stages`
MODIFY `stage_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT για πίνακα `zones`
--
ALTER TABLE `zones`
MODIFY `zone_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `prices`
--
ALTER TABLE `prices`
ADD CONSTRAINT `prices_ibfk_1` FOREIGN KEY (`concert_id`) REFERENCES `concerts` (`concert_id`),
ADD CONSTRAINT `prices_ibfk_2` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`zone_id`);

--
-- Περιορισμοί για πίνακα `reservations`
--
ALTER TABLE `reservations`
ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`uname`) REFERENCES `users` (`uname`),
ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `prices` (`p_id`);

--
-- Περιορισμοί για πίνακα `zones`
--
ALTER TABLE `zones`
ADD CONSTRAINT `zones_ibfk_1` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`stage_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
