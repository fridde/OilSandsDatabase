-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306

-- Generation Time: Oct 30, 2013 at 01:25 AM
-- Server version: 5.5.33
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `a5399891_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `osdb_buttons`
--

DROP TABLE IF EXISTS `osdb_buttons`;
CREATE TABLE `osdb_buttons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ButtonName` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ButtonContent` text COLLATE utf8_unicode_ci,
  `Description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_compilations`
--

DROP TABLE IF EXISTS `osdb_compilations`;
CREATE TABLE `osdb_compilations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Source_Id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=293 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_data`
--

DROP TABLE IF EXISTS `osdb_data`;
CREATE TABLE `osdb_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Source_Id` tinytext  COLLATE utf8_unicode_ci,
  `Date` tinytext  COLLATE utf8_unicode_ci,
  `Value` tinytext  COLLATE utf8_unicode_ci,
  `Year` tinytext  COLLATE utf8_unicode_ci,
  `Product` tinytext  COLLATE utf8_unicode_ci,
  `Project` tinytext  COLLATE utf8_unicode_ci,
  `Scenario` tinytext  COLLATE utf8_unicode_ci,
  `Status` tinytext  COLLATE utf8_unicode_ci,
  `Company` tinytext  COLLATE utf8_unicode_ci,
  `Phase` tinytext  COLLATE utf8_unicode_ci,
  `Formations` tinytext  COLLATE utf8_unicode_ci,
  `Initial_Budget` tinytext  COLLATE utf8_unicode_ci,
  `Capacity` tinytext  COLLATE utf8_unicode_ci,
  `Project_Life` tinytext  COLLATE utf8_unicode_ci,
  `Technology` tinytext  COLLATE utf8_unicode_ci,
  `Transport` tinytext  COLLATE utf8_unicode_ci,
  `EIA_Approval` tinytext  COLLATE utf8_unicode_ci,
  `Actual_Application` tinytext  COLLATE utf8_unicode_ci,
  `Expected_Approval` tinytext  COLLATE utf8_unicode_ci,
  `Regulatory_Approval` tinytext  COLLATE utf8_unicode_ci,
  `Construction_Start` tinytext  COLLATE utf8_unicode_ci,
  `First_Steam` tinytext  COLLATE utf8_unicode_ci,
  `Production_Start` tinytext  COLLATE utf8_unicode_ci,
  `EPCM` tinytext  COLLATE utf8_unicode_ci,
  `Comment` tinytext  COLLATE utf8_unicode_ci,
  `Time_Accuracy` tinytext  COLLATE utf8_unicode_ci,
  `Month` tinytext  COLLATE utf8_unicode_ci,
  `Plan_Id` tinytext COLLATE utf8_unicode_ci,
  `Company_ID` tinytext  COLLATE utf8_unicode_ci,
  `Company_Name` tinytext  COLLATE utf8_unicode_ci,
  `Combined_Name` tinytext  COLLATE utf8_unicode_ci,
  `Attributed_Project` tinytext  COLLATE utf8_unicode_ci,
  `Stage` tinytext  COLLATE utf8_unicode_ci,
  `Costs` tinytext  COLLATE utf8_unicode_ci,
  `AEUB_Status` tinytext  COLLATE utf8_unicode_ci,
  `Startup_Date` tinytext  COLLATE utf8_unicode_ci,
  `Production` tinytext  COLLATE utf8_unicode_ci,
  `Cumulative_Production` tinytext  COLLATE utf8_unicode_ci,
  `Upgrader` tinytext  COLLATE utf8_unicode_ci,
  `Description` tinytext  COLLATE utf8_unicode_ci,
  `Unit` tinytext  COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7987 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_errors`
--

DROP TABLE IF EXISTS `osdb_errors`;
CREATE TABLE `osdb_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Main_Id` int(11) NOT NULL,
  `Compilation_Id` int(11) NOT NULL,
  `Date` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Error` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ErrorPercentage` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Day` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7685 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_headers`
--

DROP TABLE IF EXISTS `osdb_headers`;
CREATE TABLE `osdb_headers` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `Name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=170 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_ranking`
--

DROP TABLE IF EXISTS `osdb_ranking`;
CREATE TABLE `osdb_ranking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Main_Id` int(11) DEFAULT NULL,
  `Compilation_1` int(11) NOT NULL,
  `Compilation_2` int(11) NOT NULL,
  `Day` int(11) NOT NULL,
  `Mean_Differential` float NOT NULL,
  `ErrorStatistic` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_sources`
--

DROP TABLE IF EXISTS `osdb_sources`;
CREATE TABLE `osdb_sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SourceName` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ShortName` tinytext COLLATE utf8_unicode_ci,
  `SourceUrl` tinytext COLLATE utf8_unicode_ci,
  `Institution` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `PublicationDate` date NOT NULL,
  `Prognosis` tinyint(1) NOT NULL,
  `Reported` tinyint(1) NOT NULL,
  `TimeAccuracy` int(11) DEFAULT NULL,
  `Product` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Unit` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `RawData` longtext COLLATE utf8_unicode_ci NOT NULL,
  `SemiTidyData` mediumtext COLLATE utf8_unicode_ci,
  `SemiTidyDataRecent` mediumtext COLLATE utf8_unicode_ci,
  `Archived` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_synonyms`
--

DROP TABLE IF EXISTS `osdb_synonyms`;
CREATE TABLE `osdb_synonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Synonym` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Replacement` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `Type` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_tags`
--

DROP TABLE IF EXISTS `osdb_tags`;
CREATE TABLE `osdb_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text COLLATE utf8_unicode_ci NOT NULL,
  `Compilation_Id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdb_working`
--

DROP TABLE IF EXISTS `osdb_working`;
CREATE TABLE `osdb_working` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Compilation_Id` int(11) NOT NULL,
  `Source_Id` int(11) DEFAULT NULL,
  `Date` tinytext COLLATE utf8_unicode_ci,
  `Value` tinytext COLLATE utf8_unicode_ci,
  `Product` tinytext COLLATE utf8_unicode_ci,
  `Project` tinytext COLLATE utf8_unicode_ci,
  `Scenario` tinytext COLLATE utf8_unicode_ci,
  `Status` tinytext COLLATE utf8_unicode_ci,
  `Company` tinytext COLLATE utf8_unicode_ci,
  `Time_Accuracy` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=806126 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
