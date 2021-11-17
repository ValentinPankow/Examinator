-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Okt 2021 um 22:04
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `examinator`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(31) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `classes`
--

INSERT INTO `classes` (`id`, `name`, `password`) VALUES
(1, '12ITa', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(2, '11ITa', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(3, '10ITa', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `room` varchar(31) DEFAULT NULL,
  `topic` text DEFAULT NULL,
  `other` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `changed_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `lessonFrom` int(11) DEFAULT NULL,
  `lessonTo` int(11) DEFAULT NULL,
  `timeFrom` time DEFAULT NULL,
  `timeTo` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `exams`
--

INSERT INTO `exams` (`id`, `creator_id`, `class_id`, `subject_id`, `date`, `room`, `topic`, `other`, `created_at`, `changed_at`, `lessonFrom`, `lessonTo`, `timeFrom`, `timeTo`) VALUES
(1, 1, 1, 1, '2021-12-03', 'C210', 'Writing about stuff.', NULL, '2021-09-15 12:13:58', NULL, 1, 2, NULL, NULL),
(34, 1, 3, 1, '2021-10-26', 'A203', '<img src=\"https://image.geo.de/30116620/t/do/v4/w1440/r0/-/01-julian-rad-jpg--65230-.jpg\" style=\"width: 750px;\"><br>', 'Testklausur', '2021-10-07 09:39:05', NULL, NULL, NULL, '12:38:00', '14:41:00'),
(42, 1, 3, 2, '2021-10-07', 'Testraum', '<p><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/dQw4w9WgXcQ\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe></p><p><a href=\"https://www.youtube.com/watch?v=dQw4w9WgXcQ\" target=\"_blank\">https://www.youtube.com/watch?v=dQw4w9WgXcQ</a><br></p>', '<p><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/dQw4w9WgXcQ\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe></p><p><a href=\"https://www.youtube.com/watch?v=dQw4w9WgXcQ\" target=\"_blank\">https://www.youtube.com/watch?v=dQw4w9WgXcQ</a>                      \n                    </p>', '2021-10-07 10:48:50', NULL, 8, 10, NULL, NULL),
(43, 1, 2, 3, '2021-10-07', 'B234', '<p><img style=\"width: 750px;\" src=\"https://image.geo.de/30116620/t/do/v4/w1440/r0/-/01-julian-rad-jpg--65230-.jpg\"><br></p>', '<p><img style=\"width: 741.5px;\" src=\"https://image.geo.de/30081082/t/fl/v4/w1440/r0/-/comedy-photo-award-gewinner-gross-01-jpg--38560-.jpg\"><br></p>', '2021-10-07 10:50:11', NULL, NULL, NULL, '12:41:00', '12:52:00'),
(44, 1, 1, 3, '2021-10-07', 'A001', '<p>Thema</p><p><img style=\"width: 750px;\" src=\"https://image.brigitte.de/11491128/t/oi/v2/w1440/r0/-/lustige-tierfotos-eichhoernchen.jpg\"><br></p>', NULL, '2021-10-07 10:50:55', NULL, 10, 11, NULL, NULL),
(45, 1, 3, 2, '2021-10-29', 'Test', NULL, '<p>Sonstige</p><p><img style=\"width: 750px;\" src=\"https://img.br.de/7a426f8f-ba51-497d-aa05-13b6882fe496.jpeg?q=80&amp;rect=6%2C700%2C2057%2C1157&amp;w=1200\"><br></p>', '2021-10-07 10:52:49', NULL, NULL, NULL, '14:12:00', '15:52:00'),
(46, 1, 2, 1, '2021-10-29', '', '<font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\"><span style=\"font-family: &quot;Comic Sans MS&quot;;\">Test</span></font>', '<h3><span style=\"background-color: rgb(0, 0, 255); font-family: Verdana;\"><font color=\"#00ffff\"><b>Sonstiges</b></font></span></h3><table class=\"table table-bordered\"><tbody><tr><td>Hier ist eine Spalte und Zeile</td><td>Hier ist eine Spalte</td><td>&nbsp;Und hier ist auch eine Spalte</td></tr><tr><td>Hier ist eine Zeile</td><td><br></td><td><br></td></tr><tr><td>Hier ist auch eine Zeile</td><td><br></td><td><br></td></tr></tbody></table>', '2021-10-07 10:55:06', NULL, NULL, NULL, '13:50:00', '14:53:00'),
(48, 1, 2, 1, '2021-10-19', '', NULL, NULL, '2021-10-07 11:34:13', NULL, NULL, NULL, '14:34:00', '15:36:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Englisch'),
(2, 'Sport'),
(3, 'PoWi');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(127) NOT NULL,
  `last_name` varchar(127) NOT NULL,
  `email` varchar(127) NOT NULL,
  `password` varchar(255) NOT NULL,
  `session_id` varchar(63) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `is_teacher` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `session_id`, `is_admin`, `is_teacher`) VALUES
(1, 'firstname', 'lastname', 'demo@demo.demo', '$2y$10$/5.1CWmq54TuA7wFeW/SAuHTrN4b.RZEqoaVSKr8wIUjlGMJEkruS', NULL, 0, 1),
(2, 'name', 'name2', 'demo2@demo.demo', '$2y$10$5QkSaXTpTSXIWLXb3.2Mduqxdfcx.sf14P3qBEl4qjsbIdVYW/AEq', NULL, 0, 1),
(3, 'test', 'test', 'demo3@demo.de', '$2y$10$Tj04pbgvXsQpAHdXNTmqee/i.rI1qknFmiiDC2nndIpxjcLHEOjSS', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users_classes`
--

CREATE TABLE `users_classes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_exams_subjects` (`subject_id`),
  ADD KEY `fk_exams_users` (`creator_id`),
  ADD KEY `fk_exams_classes` (`class_id`);

--
-- Indizes für die Tabelle `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indizes für die Tabelle `users_classes`
--
ALTER TABLE `users_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_classes_classes` (`class_id`),
  ADD KEY `fk_users_classes_users` (`user_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT für Tabelle `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `users_classes`
--
ALTER TABLE `users_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `fk_exams_classes` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `fk_exams_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `fk_exams_users` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `users_classes`
--
ALTER TABLE `users_classes`
  ADD CONSTRAINT `fk_users_classes_classes` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `fk_users_classes_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
