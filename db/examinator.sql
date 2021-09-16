-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Sep 2021 um 14:40
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
(1, '12ITa', '1234'),
(2, '11ITa', '1234'),
(3, '10ITa', '1234');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `room` varchar(31) NOT NULL,
  `topic` varchar(63) NOT NULL,
  `other` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `changed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `exams`
--

INSERT INTO `exams` (`id`, `creator_id`, `class_id`, `subject_id`, `date`, `room`, `topic`, `other`, `created_at`, `changed_at`) VALUES
(1, 1, 1, 1, '2021-09-15 12:53:23', 'C210', 'Writing about stuff.', '', '2021-09-15 12:13:58', NULL),
(2, 1, 1, 1, '2021-09-16 11:14:54', 'C210', '', '', '2021-09-16 11:14:54', NULL),
(3, 1, 1, 1, '2021-09-16 12:08:07', 'C210', '', 'other informations', '2021-09-16 12:08:07', NULL),
(4, 1, 2, 1, '2021-09-16 12:26:15', 'C207', 'Testtopic', 'Testother', '2021-09-16 12:26:15', NULL);

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
  `is_admin` tinyint(1) NOT NULL,
  `is_teacher` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `is_admin`, `is_teacher`) VALUES
(1, 'firstname', 'lastname', 'demo@demo.demo', '1234', 0, 0),
(2, 'name', 'name2', 'demo2@demo.demo', '1234', 1, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
