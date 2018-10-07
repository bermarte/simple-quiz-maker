-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 30, 2018 at 03:41 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `simple_quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `id` smallint(5) unsigned NOT NULL,
  `answer` varchar(600) NOT NULL,
  `id_questions` smallint(5) unsigned DEFAULT NULL,
  `isRight` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `choices`
--

INSERT INTO `choices` (`id`, `answer`, `id_questions`, `isRight`) VALUES
(1, 'meow', 1, 0),
(2, 'woof', 1, 1),
(3, 'moo', 1, 0),
(4, 'hoo', 1, 0),
(5, '22', 2, 0),
(6, '42', 2, 1),
(7, '38', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` smallint(5) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `coach` varchar(50) NOT NULL,
  `mail` varchar(320) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `title`, `coach`, `mail`) VALUES
(1, 'test', 'anadmin', 'amail');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` smallint(5) unsigned NOT NULL,
  `category` varchar(50) NOT NULL,
  `numQuestions` smallint(5) unsigned NOT NULL,
  `id_document` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `category`, `numQuestions`, `id_document`) VALUES
(1, 'dogs', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `percentage`
--

CREATE TABLE `percentage` (
  `id` smallint(5) unsigned NOT NULL,
  `percent` smallint(5) unsigned DEFAULT NULL,
  `id_titleP` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `percentage`
--

INSERT INTO `percentage` (`id`, `percent`, `id_titleP`) VALUES
(1, 60, 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` smallint(5) unsigned NOT NULL,
  `question` varchar(600) NOT NULL,
  `numAnswers` smallint(5) unsigned NOT NULL,
  `id_group` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `numAnswers`, `id_group`) VALUES
(1, 'what sound makes a dog?', 4, 1),
(2, 'how many teeth has a dog?', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `randomness`
--

CREATE TABLE `randomness` (
  `id` smallint(5) unsigned NOT NULL,
  `isRandom` tinyint(1) DEFAULT NULL,
  `id_titleR` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `randomness`
--

INSERT INTO `randomness` (`id`, `isRandom`, `id_titleR`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'amail');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_questions` (`id_questions`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_document` (`id_document`);

--
-- Indexes for table `percentage`
--
ALTER TABLE `percentage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_titleP` (`id_titleP`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_group` (`id_group`);

--
-- Indexes for table `randomness`
--
ALTER TABLE `randomness`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_titleR` (`id_titleR`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `percentage`
--
ALTER TABLE `percentage`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `randomness`
--
ALTER TABLE `randomness`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `choices`
--
ALTER TABLE `choices`
  ADD CONSTRAINT `choices_ibfk_1` FOREIGN KEY (`id_questions`) REFERENCES `questions` (`id`);

--
-- Constraints for table `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`id_document`) REFERENCES `document` (`id`);

--
-- Constraints for table `percentage`
--
ALTER TABLE `percentage`
  ADD CONSTRAINT `percentage_ibfk_1` FOREIGN KEY (`id_titleP`) REFERENCES `document` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`);

--
-- Constraints for table `randomness`
--
ALTER TABLE `randomness`
  ADD CONSTRAINT `randomness_ibfk_1` FOREIGN KEY (`id_titleR`) REFERENCES `document` (`id`);
