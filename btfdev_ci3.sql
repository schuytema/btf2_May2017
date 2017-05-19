-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 26, 2016 at 10:36 AM
-- Server version: 5.5.49-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `btfdev_ci3`
--

-- --------------------------------------------------------

--
-- Table structure for table `btf2_chat_messages`
--

CREATE TABLE IF NOT EXISTS `btf2_chat_messages` (
  `PK_Chat_Message_Id` int(11) NOT NULL AUTO_INCREMENT,
  `FK_User_Id` int(11) NOT NULL DEFAULT '0',
  `FK_Project_Id` int(11) NOT NULL DEFAULT '0',
  `Create_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Channel` varchar(100) NOT NULL DEFAULT '#general',
  `Message` text,
  PRIMARY KEY (`PK_Chat_Message_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `btf2_projects`
--

CREATE TABLE IF NOT EXISTS `btf2_projects` (
  `PK_Project_Id` int(11) NOT NULL AUTO_INCREMENT,
  `FK_User_Id` int(11) NOT NULL DEFAULT '0',
  `Create_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Description` text,
  `Status` varchar(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`PK_Project_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `btf2_projects`
--

INSERT INTO `btf2_projects` (`PK_Project_Id`, `FK_User_Id`, `Create_Date`, `Name`, `Description`, `Status`) VALUES
(1, 1, '2016-05-04 15:38:08', 'BTF Lite', 'This project is about making the mobile-aware, lean and mean new Breakthrough Foundry web application.', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `btf2_project_members`
--

CREATE TABLE IF NOT EXISTS `btf2_project_members` (
  `PK_Project_Member_Id` int(11) NOT NULL AUTO_INCREMENT,
  `FK_User_Id` int(11) NOT NULL DEFAULT '0',
  `FK_Project_Id` int(11) NOT NULL DEFAULT '0',
  `Is_Admin` varchar(5) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`PK_Project_Member_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `btf2_project_members`
--

INSERT INTO `btf2_project_members` (`PK_Project_Member_Id`, `FK_User_Id`, `FK_Project_Id`, `Is_Admin`) VALUES
(1, 1, 1, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `btf2_users`
--

CREATE TABLE IF NOT EXISTS `btf2_users` (
  `PK_User_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Create_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `First_Name` varchar(50) NOT NULL DEFAULT '',
  `Last_Name` varchar(50) NOT NULL DEFAULT '',
  `Email` varchar(100) NOT NULL DEFAULT 'none',
  `User_Key` varchar(10) NOT NULL DEFAULT '',
  `Admin_Level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`PK_User_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `btf2_users`
--

INSERT INTO `btf2_users` (`PK_User_Id`, `Create_Date`, `First_Name`, `Last_Name`, `Email`, `User_Key`, `Admin_Level`) VALUES
(1, '2016-05-03 18:19:50', 'Paul', 'Schuytema', 'paul@schuytema.com', '8estAnaf', 99);

-- --------------------------------------------------------

--
-- Table structure for table `btf2_work_records`
--

CREATE TABLE IF NOT EXISTS `btf2_work_records` (
  `PK_Work_Record_Id` int(11) NOT NULL AUTO_INCREMENT,
  `FK_User_Id` int(11) NOT NULL DEFAULT '0',
  `FK_Project_Id` int(11) NOT NULL DEFAULT '0',
  `Create_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Work_Date` varchar(10) NOT NULL,
  `Description` text,
  `Status` varchar(20) NOT NULL DEFAULT 'logged',
  `Unit_Type` varchar(20) NOT NULL DEFAULT 'hours',
  `Unit_Value` int(11) NOT NULL DEFAULT '0',
  `Work_Units` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`PK_Work_Record_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `btf2_work_records`
--

INSERT INTO `btf2_work_records` (`PK_Work_Record_Id`, `FK_User_Id`, `FK_Project_Id`, `Create_Date`, `Work_Date`, `Description`, `Status`, `Unit_Type`, `Unit_Value`, `Work_Units`) VALUES
(1, 1, 1, '2016-05-03 18:13:21', '2016-05-03', 'Set up CI and bootstrap and basic template', 'logged', 'hours', 40, 2),
(2, 1, 1, '2016-05-04 18:14:22', '2016-05-03', 'Got log-in working, projects and started on work records', 'logged', 'hours', 40, 4),
(3, 1, 1, '2016-05-05 16:02:53', '2016-05-05', 'Editing and deleting of work records', 'logged', 'hours', 40, 3),
(4, 1, 1, '2016-05-06 13:43:39', '2016-05-05', 'URL for development server, 2015 & 2016', 'logged', 'cash', 15, 2),
(5, 1, 1, '2016-05-06 13:44:15', '2016-05-06', 'Web hosting of development server, 2015 & 2016', 'approved', 'cash', 5, 24),
(6, 1, 1, '2016-05-16 20:21:25', '2016-05-16', 'Added in Work_Date field', 'logged', 'hours', 40, 1),
(7, 1, 1, '2016-05-26 14:11:13', '2016-05-26', 'Added team list and work sorting', 'logged', 'hours', 40, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
