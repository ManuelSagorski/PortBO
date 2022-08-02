-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 02. Aug 2022 um 14:19
-- Server-Version: 10.5.16-MariaDB-1:10.5.16+maria~focal-log
-- PHP-Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `d037fc3b`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_agency`
--

CREATE TABLE `port_bo_agency` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `short` varchar(10) NOT NULL,
  `ts_erf` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_agencyPortInfo`
--

CREATE TABLE `port_bo_agencyPortInfo` (
  `id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `port_id` int(11) NOT NULL,
  `info` text NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_company`
--

CREATE TABLE `port_bo_company` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `port_id` int(11) NOT NULL,
  `info` text NOT NULL,
  `mtLink` varchar(250) NOT NULL,
  `pmLink` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_dry`
--

CREATE TABLE `port_bo_dry` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `imo` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_invitation`
--

CREATE TABLE `port_bo_invitation` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `invitation_key` varchar(200) NOT NULL,
  `invitation_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_language`
--

CREATE TABLE `port_bo_language` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_log`
--

CREATE TABLE `port_bo_log` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ts_erf` datetime NOT NULL DEFAULT current_timestamp(),
  `logLevel` varchar(10) NOT NULL,
  `component` varchar(100) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_port`
--

CREATE TABLE `port_bo_port` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `short` varchar(5) NOT NULL,
  `mtLink` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_projects`
--

CREATE TABLE `port_bo_projects` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `short` varchar(50) NOT NULL,
  `contact_details_separated` tinyint(1) NOT NULL DEFAULT 0,
  `mod_forecast` tinyint(1) NOT NULL DEFAULT 0,
  `mod_planning` tinyint(1) NOT NULL DEFAULT 0,
  `mod_planning_project` varchar(50) NOT NULL,
  `mod_externLinks` tinyint(1) NOT NULL DEFAULT 1,
  `mod_foreignPort` tinyint(1) DEFAULT 0,
  `mod_contactDetails` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_scedule`
--

CREATE TABLE `port_bo_scedule` (
  `id` int(11) NOT NULL,
  `arriving` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `leaving` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(200) NOT NULL,
  `imo` varchar(7) NOT NULL,
  `company` varchar(500) NOT NULL,
  `agency` varchar(500) NOT NULL,
  `port_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_settingsExternLinks`
--

CREATE TABLE `port_bo_settingsExternLinks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_telegram`
--

CREATE TABLE `port_bo_telegram` (
  `id` int(11) NOT NULL,
  `direction` varchar(10) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `telegram_id` int(11) NOT NULL,
  `user_id_bo` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `ts` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_user`
--

CREATE TABLE `port_bo_user` (
  `id` int(11) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0,
  `project_id` int(11) NOT NULL,
  `foreign_port` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `secret` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `password_code` varchar(255) NOT NULL,
  `password_code_time` timestamp NULL DEFAULT NULL,
  `telegram_id` varchar(250) NOT NULL,
  `telegram_code` varchar(9) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `planning_id` int(11) NOT NULL,
  `dataprotection` datetime DEFAULT NULL,
  `default_language` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_userToLanguage`
--

CREATE TABLE `port_bo_userToLanguage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_userToPort`
--

CREATE TABLE `port_bo_userToPort` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `port_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_vessel`
--

CREATE TABLE `port_bo_vessel` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `IMO` varchar(7) NOT NULL,
  `MMSI` varchar(9) NOT NULL,
  `ENI` varchar(8) DEFAULT NULL,
  `typ` varchar(20) NOT NULL,
  `itf` varchar(500) NOT NULL,
  `language` varchar(500) NOT NULL,
  `email` varchar(200) NOT NULL,
  `ts_erf` datetime NOT NULL DEFAULT current_timestamp(),
  `languages_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_vesselContact`
--

CREATE TABLE `port_bo_vesselContact` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `vess_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_type` varchar(30) NOT NULL,
  `contact_name` varchar(200) NOT NULL,
  `contact_user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `agent_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `port_id` int(11) NOT NULL,
  `planned` tinyint(1) NOT NULL DEFAULT 0,
  `month_next` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_vesselContactDetails`
--

CREATE TABLE `port_bo_vesselContactDetails` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `vessel_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `detail` varchar(200) NOT NULL,
  `info` text DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `invalid` tinyint(1) NOT NULL,
  `supposed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_vesselContactRequest`
--

CREATE TABLE `port_bo_vesselContactRequest` (
  `id` int(11) NOT NULL,
  `vessel_id` int(11) NOT NULL,
  `requesting_group_id` int(11) NOT NULL,
  `requested_group_id` int(11) NOT NULL,
  `request_key` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_vesselInfo`
--

CREATE TABLE `port_bo_vesselInfo` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `vess_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ts_erf` date NOT NULL DEFAULT current_timestamp(),
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_bo_vesselToLanguage`
--

CREATE TABLE `port_bo_vesselToLanguage` (
  `id` int(11) NOT NULL,
  `vessel_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `master` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `port_vessel_alert`
--

CREATE TABLE `port_vessel_alert` (
  `id` int(11) NOT NULL,
  `vess_name` varchar(100) NOT NULL,
  `vess_mail` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `port_bo_agency`
--
ALTER TABLE `port_bo_agency`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_agencyPortInfo`
--
ALTER TABLE `port_bo_agencyPortInfo`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_company`
--
ALTER TABLE `port_bo_company`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_dry`
--
ALTER TABLE `port_bo_dry`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_invitation`
--
ALTER TABLE `port_bo_invitation`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_language`
--
ALTER TABLE `port_bo_language`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_log`
--
ALTER TABLE `port_bo_log`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_port`
--
ALTER TABLE `port_bo_port`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_projects`
--
ALTER TABLE `port_bo_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_scedule`
--
ALTER TABLE `port_bo_scedule`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_settingsExternLinks`
--
ALTER TABLE `port_bo_settingsExternLinks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_telegram`
--
ALTER TABLE `port_bo_telegram`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_user`
--
ALTER TABLE `port_bo_user`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_userToLanguage`
--
ALTER TABLE `port_bo_userToLanguage`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_userToPort`
--
ALTER TABLE `port_bo_userToPort`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_vessel`
--
ALTER TABLE `port_bo_vessel`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_vesselContact`
--
ALTER TABLE `port_bo_vesselContact`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_vesselContactDetails`
--
ALTER TABLE `port_bo_vesselContactDetails`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_vesselContactRequest`
--
ALTER TABLE `port_bo_vesselContactRequest`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_vesselInfo`
--
ALTER TABLE `port_bo_vesselInfo`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_bo_vesselToLanguage`
--
ALTER TABLE `port_bo_vesselToLanguage`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `port_vessel_alert`
--
ALTER TABLE `port_vessel_alert`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `port_bo_agency`
--
ALTER TABLE `port_bo_agency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_agencyPortInfo`
--
ALTER TABLE `port_bo_agencyPortInfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_company`
--
ALTER TABLE `port_bo_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_dry`
--
ALTER TABLE `port_bo_dry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_invitation`
--
ALTER TABLE `port_bo_invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_language`
--
ALTER TABLE `port_bo_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_log`
--
ALTER TABLE `port_bo_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_port`
--
ALTER TABLE `port_bo_port`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_projects`
--
ALTER TABLE `port_bo_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_scedule`
--
ALTER TABLE `port_bo_scedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_settingsExternLinks`
--
ALTER TABLE `port_bo_settingsExternLinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_telegram`
--
ALTER TABLE `port_bo_telegram`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_user`
--
ALTER TABLE `port_bo_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_userToLanguage`
--
ALTER TABLE `port_bo_userToLanguage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_userToPort`
--
ALTER TABLE `port_bo_userToPort`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_vessel`
--
ALTER TABLE `port_bo_vessel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_vesselContact`
--
ALTER TABLE `port_bo_vesselContact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_vesselContactDetails`
--
ALTER TABLE `port_bo_vesselContactDetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_vesselContactRequest`
--
ALTER TABLE `port_bo_vesselContactRequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_vesselInfo`
--
ALTER TABLE `port_bo_vesselInfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_bo_vesselToLanguage`
--
ALTER TABLE `port_bo_vesselToLanguage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `port_vessel_alert`
--
ALTER TABLE `port_vessel_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
