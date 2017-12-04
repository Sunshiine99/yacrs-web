-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Dec 04, 2017 at 03:45 PM
-- Server version: 5.7.19
-- PHP Version: 7.0.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yacrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_questions`
--

CREATE TABLE `yacrs_questions` (
  `questionID` int(11) NOT NULL,
  `question` varchar(80) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `created` bigint(20) NOT NULL,
  `lastUpdate` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_questionsMcqChoices`
--

CREATE TABLE `yacrs_questionsMcqChoices` (
  `ID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `choice` varchar(80) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_questionTypes`
--

CREATE TABLE `yacrs_questionTypes` (
  `ID` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `yacrs_questionTypes`
--

INSERT INTO `yacrs_questionTypes` (`ID`, `name`) VALUES
(1, 'mcq'),
(2, 'text'),
(3, 'textlong');

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_response`
--

CREATE TABLE `yacrs_response` (
  `ID` int(11) NOT NULL,
  `sessionQuestionID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_responseMcq`
--

CREATE TABLE `yacrs_responseMcq` (
  `ID` int(11) NOT NULL,
  `sessionQuestionID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `choiceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_sessionAlias`
--

CREATE TABLE `yacrs_sessionAlias` (
  `ID` int(11) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `sessionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_sessionQuestions`
--

CREATE TABLE `yacrs_sessionQuestions` (
  `ID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_sessions`
--

CREATE TABLE `yacrs_sessions` (
  `sessionID` int(11) NOT NULL,
  `ownerID` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `courseID` varchar(20) NOT NULL,
  `allowGuests` tinyint(1) NOT NULL,
  `onSessionList` tinyint(1) NOT NULL,
  `questionControlMode` int(11) NOT NULL,
  `defaultTimeLimit` int(11) NOT NULL,
  `allowModifyAnswer` tinyint(1) NOT NULL,
  `allowQuestionReview` tinyint(1) NOT NULL,
  `classDiscussionEnabled` tinyint(1) NOT NULL,
  `created` bigint(20) NOT NULL DEFAULT '0',
  `lastUpdate` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_sessionsAdditionalUsers`
--

CREATE TABLE `yacrs_sessionsAdditionalUsers` (
  `ID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yacrs_user`
--

CREATE TABLE `yacrs_user` (
  `userID` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `isSessionCreatorOverride` tinyint(1) DEFAULT NULL,
  `isAdminOverride` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `yacrs_questions`
--
ALTER TABLE `yacrs_questions`
  ADD PRIMARY KEY (`questionID`),
  ADD KEY `yacrs_questions_type` (`type`);

--
-- Indexes for table `yacrs_questionsMcqChoices`
--
ALTER TABLE `yacrs_questionsMcqChoices`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Structure yacrs_questionsMcqChoice_questionID` (`questionID`);

--
-- Indexes for table `yacrs_questionTypes`
--
ALTER TABLE `yacrs_questionTypes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `yacrs_response`
--
ALTER TABLE `yacrs_response`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `yacrs_responseMcq`
--
ALTER TABLE `yacrs_responseMcq`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `yacrs_sessionAlias`
--
ALTER TABLE `yacrs_sessionAlias`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD KEY `yacrs_sessionAlias_sessionID` (`sessionID`);

--
-- Indexes for table `yacrs_sessionQuestions`
--
ALTER TABLE `yacrs_sessionQuestions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `yacrs_sessionQuestions_sessionID` (`sessionID`),
  ADD KEY `yacrs_sessionQuestions_questionID` (`questionID`);

--
-- Indexes for table `yacrs_sessions`
--
ALTER TABLE `yacrs_sessions`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `yacrs_sessions_ownerID` (`ownerID`);

--
-- Indexes for table `yacrs_sessionsAdditionalUsers`
--
ALTER TABLE `yacrs_sessionsAdditionalUsers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `yacrs_sessionsAdditionalUsers_sessionID` (`sessionID`),
  ADD KEY `yacrs_sessionsAdditionalUsers_userID` (`userID`);

--
-- Indexes for table `yacrs_user`
--
ALTER TABLE `yacrs_user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `yacrs_questions`
--
ALTER TABLE `yacrs_questions`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_questionsMcqChoices`
--
ALTER TABLE `yacrs_questionsMcqChoices`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_questionTypes`
--
ALTER TABLE `yacrs_questionTypes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `yacrs_response`
--
ALTER TABLE `yacrs_response`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_responseMcq`
--
ALTER TABLE `yacrs_responseMcq`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_sessionAlias`
--
ALTER TABLE `yacrs_sessionAlias`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_sessionQuestions`
--
ALTER TABLE `yacrs_sessionQuestions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_sessions`
--
ALTER TABLE `yacrs_sessions`
  MODIFY `sessionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_sessionsAdditionalUsers`
--
ALTER TABLE `yacrs_sessionsAdditionalUsers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `yacrs_user`
--
ALTER TABLE `yacrs_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `yacrs_questions`
--
ALTER TABLE `yacrs_questions`
  ADD CONSTRAINT `yacrs_questions_type` FOREIGN KEY (`type`) REFERENCES `yacrs_questionTypes` (`ID`);

--
-- Constraints for table `yacrs_questionsMcqChoices`
--
ALTER TABLE `yacrs_questionsMcqChoices`
  ADD CONSTRAINT `Structure yacrs_questionsMcqChoice_questionID` FOREIGN KEY (`questionID`) REFERENCES `yacrs_questions` (`questionID`);

--
-- Constraints for table `yacrs_sessionAlias`
--
ALTER TABLE `yacrs_sessionAlias`
  ADD CONSTRAINT `yacrs_sessionAlias_sessionID` FOREIGN KEY (`sessionID`) REFERENCES `yacrs_sessions` (`sessionID`);

--
-- Constraints for table `yacrs_sessionQuestions`
--
ALTER TABLE `yacrs_sessionQuestions`
  ADD CONSTRAINT `yacrs_sessionQuestions_questionID` FOREIGN KEY (`questionID`) REFERENCES `yacrs_questions` (`questionID`),
  ADD CONSTRAINT `yacrs_sessionQuestions_sessionID` FOREIGN KEY (`sessionID`) REFERENCES `yacrs_sessions` (`sessionID`);

--
-- Constraints for table `yacrs_sessions`
--
ALTER TABLE `yacrs_sessions`
  ADD CONSTRAINT `yacrs_sessions_ownerID` FOREIGN KEY (`ownerID`) REFERENCES `yacrs_user` (`userID`);

--
-- Constraints for table `yacrs_sessionsAdditionalUsers`
--
ALTER TABLE `yacrs_sessionsAdditionalUsers`
  ADD CONSTRAINT `yacrs_sessionsAdditionalUsers_sessionID` FOREIGN KEY (`sessionID`) REFERENCES `yacrs_sessions` (`sessionID`),
  ADD CONSTRAINT `yacrs_sessionsAdditionalUsers_userID` FOREIGN KEY (`userID`) REFERENCES `yacrs_user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
