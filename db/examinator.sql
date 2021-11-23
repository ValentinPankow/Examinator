-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 23. Nov 2021 um 19:42
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
  `name` varchar(63) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `classes`
--

INSERT INTO `classes` (`id`, `name`, `password`) VALUES
(1, '12ITa', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(2, '11ITa', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(3, '10ITa', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(4, '10ITb', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(5, '11ITd', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(6, '12ITb', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(7, '10ITc', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(8, '11ITc', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(9, '12ITc', '$2y$10$6RspxXZvM4M.s3CIJIZ7q.8uW/kCvRlEdBhZAEqwrJxghoicmifI6'),
(14, '1234', '$2y$10$DODnyrMBKPaRhdpURF7B9O2Zf2c5/grBkz7B4tbLJAKPSZLCjcYs6');

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
  `topic` longblob DEFAULT NULL,
  `other` longblob DEFAULT NULL,
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
(1, 1, 1, 1, '2021-12-03', 'C210', 0x57726974696e672061626f75742073747566662e, NULL, '2021-09-15 10:13:58', NULL, 1, 2, NULL, NULL),
(34, 1, 3, 1, '2021-10-26', 'A203', 0x3c696d67207372633d2268747470733a2f2f696d6167652e67656f2e64652f33303131363632302f742f646f2f76342f77313434302f72302f2d2f30312d6a756c69616e2d7261642d6a70672d2d36353233302d2e6a706722207374796c653d2277696474683a2037353070783b223e3c62723e, 0x546573746b6c6175737572, '2021-10-07 07:39:05', NULL, NULL, NULL, '12:38:00', '14:41:00'),
(42, 1, 3, 2, '2021-10-07', 'Testraum', 0x3c703e3c696672616d65206672616d65626f726465723d223022207372633d222f2f7777772e796f75747562652e636f6d2f656d6265642f6451773477395767586351222077696474683d2236343022206865696768743d223336302220636c6173733d226e6f74652d766964656f2d636c6970223e3c2f696672616d653e3c2f703e3c703e3c6120687265663d2268747470733a2f2f7777772e796f75747562652e636f6d2f77617463683f763d645177347739576758635122207461726765743d225f626c616e6b223e68747470733a2f2f7777772e796f75747562652e636f6d2f77617463683f763d64517734773957675863513c2f613e3c62723e3c2f703e, 0x3c703e3c696672616d65206672616d65626f726465723d223022207372633d222f2f7777772e796f75747562652e636f6d2f656d6265642f6451773477395767586351222077696474683d2236343022206865696768743d223336302220636c6173733d226e6f74652d766964656f2d636c6970223e3c2f696672616d653e3c2f703e3c703e3c6120687265663d2268747470733a2f2f7777772e796f75747562652e636f6d2f77617463683f763d645177347739576758635122207461726765743d225f626c616e6b223e68747470733a2f2f7777772e796f75747562652e636f6d2f77617463683f763d64517734773957675863513c2f613e202020202020202020202020202020202020202020200a20202020202020202020202020202020202020203c2f703e, '2021-10-07 08:48:50', NULL, 8, 10, NULL, NULL),
(43, 1, 2, 3, '2021-10-07', 'B234', 0x3c703e3c696d67207374796c653d2277696474683a2037353070783b22207372633d2268747470733a2f2f696d6167652e67656f2e64652f33303131363632302f742f646f2f76342f77313434302f72302f2d2f30312d6a756c69616e2d7261642d6a70672d2d36353233302d2e6a7067223e3c62723e3c2f703e, 0x3c703e3c696d67207374796c653d2277696474683a203734312e3570783b22207372633d2268747470733a2f2f696d6167652e67656f2e64652f33303038313038322f742f666c2f76342f77313434302f72302f2d2f636f6d6564792d70686f746f2d61776172642d676577696e6e65722d67726f73732d30312d6a70672d2d33383536302d2e6a7067223e3c62723e3c2f703e, '2021-10-07 08:50:11', NULL, NULL, NULL, '12:41:00', '12:52:00'),
(44, 1, 1, 1, '2021-10-07', 'A001', 0x3c703e5468656d613c2f703e3c703e3c696d67207374796c653d2277696474683a2037353070783b22207372633d2268747470733a2f2f696d6167652e62726967697474652e64652f31313439313132382f742f6f692f76322f77313434302f72302f2d2f6c7573746967652d74696572666f746f732d65696368686f65726e6368656e2e6a7067223e3c62723e3c2f703e, NULL, '2021-10-07 08:50:55', '2021-11-22 23:47:55', 10, 11, NULL, NULL),
(45, 1, 3, 2, '2021-10-29', 'Test', NULL, 0x3c703e536f6e73746967653c2f703e3c703e3c696d67207374796c653d2277696474683a2037353070783b22207372633d2268747470733a2f2f696d672e62722e64652f37613432366638662d626135312d343937642d616130352d3133623638383266653439362e6a7065673f713d383026616d703b726563743d36253243373030253243323035372532433131353726616d703b773d31323030223e3c62723e3c2f703e, '2021-10-07 08:52:49', NULL, NULL, NULL, '14:12:00', '15:52:00'),
(46, 1, 2, 1, '2021-10-29', '', 0x3c666f6e7420636f6c6f723d222330303030303022207374796c653d226261636b67726f756e642d636f6c6f723a20726762283235352c203235352c2030293b223e3c7370616e207374796c653d22666f6e742d66616d696c793a202671756f743b436f6d69632053616e73204d532671756f743b3b223e546573743c2f7370616e3e3c2f666f6e743e, 0x3c68333e3c7370616e207374796c653d226261636b67726f756e642d636f6c6f723a2072676228302c20302c20323535293b20666f6e742d66616d696c793a2056657264616e613b223e3c666f6e7420636f6c6f723d2223303066666666223e3c623e536f6e7374696765733c2f623e3c2f666f6e743e3c2f7370616e3e3c2f68333e3c7461626c6520636c6173733d227461626c65207461626c652d626f726465726564223e3c74626f64793e3c74723e3c74643e48696572206973742065696e65205370616c746520756e64205a65696c653c2f74643e3c74643e48696572206973742065696e65205370616c74653c2f74643e3c74643e266e6273703b556e6420686965722069737420617563682065696e65205370616c74653c2f74643e3c2f74723e3c74723e3c74643e48696572206973742065696e65205a65696c653c2f74643e3c74643e3c62723e3c2f74643e3c74643e3c62723e3c2f74643e3c2f74723e3c74723e3c74643e486965722069737420617563682065696e65205a65696c653c2f74643e3c74643e3c62723e3c2f74643e3c74643e3c62723e3c2f74643e3c2f74723e3c2f74626f64793e3c2f7461626c653e, '2021-10-07 08:55:06', NULL, NULL, NULL, '13:50:00', '14:53:00'),
(48, 1, 2, 1, '2021-10-19', '', NULL, NULL, '2021-10-07 09:34:13', NULL, NULL, NULL, '14:34:00', '15:36:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Englisch'),
(2, 'Sport'),
(3, 'PoWi'),
(5, 'Deutsch'),
(10, 'NaWi'),
(11, 'Biologie'),
(12, 'Chemie'),
(14, 'Geschichte');

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
(1, 'Valentin', 'Pankow', 'vp@vp.demo', '$2y$10$VXQk.ZOGBDiAsHzt8BvsZeTbkh80CNifkLiJEWIisN5nt.WJRv0lu', '58vtavhp7u9em07nch7sacgjgh', 1, 1),
(2, 'name', 'name2', 'demo2@demo.demo', '$2y$10$5QkSaXTpTSXIWLXb3.2Mduqxdfcx.sf14P3qBEl4qjsbIdVYW/AEq', NULL, 0, 1),
(3, 'test', 'testt', 'demo3@demo.de', '$2y$10$Tj04pbgvXsQpAHdXNTmqee/i.rI1qknFmiiDC2nndIpxjcLHEOjSS', 'eiplbhcvkvanf1rj7350c82rrg', 1, 1),
(4, 'Test', 'Test1', 'test@email.de', '$2a$12$vdHjpp/4efDx4mgU.FngAeLktChqxpn7Dh5Q8a4GLONFZXLd.EdcW', 'u51tkpjoiljgg6jidlb66p2rc2', 0, 1),
(5, 'Daniel', 'Heymanns', 'daniel.heymanns@ymail.com', '$2y$10$nEFDMbbpqnzEGhLovJz7t.Nykdy0ll/VufJd/clgNAgtMOetlXiLu', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user_favorites`
--

INSERT INTO `user_favorites` (`id`, `user_id`, `class_id`, `subject_id`) VALUES
(61, 1, NULL, 1),
(63, 1, NULL, 2),
(62, 1, NULL, 3),
(58, 1, 1, NULL),
(55, 1, 3, NULL),
(59, 1, 4, NULL),
(57, 1, 5, NULL),
(56, 1, 7, NULL),
(60, 1, 8, NULL),
(40, 3, 3, NULL),
(41, 3, 4, NULL),
(42, 3, 7, NULL),
(43, 3, 14, NULL);

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
-- Indizes für die Tabelle `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`class_id`,`subject_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT für Tabelle `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

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
-- Constraints der Tabelle `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `user_favorites_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
