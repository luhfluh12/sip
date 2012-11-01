-- MySQL dump 10.13  Distrib 5.1.61, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: sipdev
-- ------------------------------------------------------
-- Server version	5.1.61-0ubuntu0.10.10.1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `AuthAssignment`
--

DROP TABLE IF EXISTS `AuthAssignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AuthItem`
--

DROP TABLE IF EXISTS `AuthItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AuthItemChild`
--

DROP TABLE IF EXISTS `AuthItemChild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `absences`
--

DROP TABLE IF EXISTS `absences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `absences` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `student` mediumint(8) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `subject` smallint(3) unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  `authorized` tinyint(1) unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  KEY `subject` (`subject`),
  CONSTRAINT `absences_ibfk_2` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `absences_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `absencesHistory`
--

DROP TABLE IF EXISTS `absencesHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `absencesHistory` (
  `student` mediumint(8) unsigned NOT NULL,
  `year` smallint(4) unsigned NOT NULL,
  `semester` tinyint(1) unsigned NOT NULL,
  `auth` smallint(3) unsigned NOT NULL,
  `unauth` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`student`,`year`,`semester`),
  KEY `student` (`student`),
  KEY `year` (`year`),
  CONSTRAINT `absencesHistory_ibfk_1` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `absencesHistory_ibfk_2` FOREIGN KEY (`year`) REFERENCES `schoolyear` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accountRevisions`
--

DROP TABLE IF EXISTS `accountRevisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accountRevisions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` mediumint(8) unsigned NOT NULL,
  `action` varchar(10) CHARACTER SET ascii NOT NULL,
  `oldvalue` varchar(254) NOT NULL,
  `useragent` text NOT NULL,
  `ip` char(15) CHARACTER SET ascii NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  CONSTRAINT `accountRevisions_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `phone` char(16) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `password` char(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `activation` char(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `security_question` tinyint(2) unsigned NOT NULL,
  `security_answer` char(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `sms_hour1` tinyint(2) unsigned NOT NULL DEFAULT '9',
  `sms_hour2` tinyint(2) unsigned NOT NULL DEFAULT '16',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL,
  `registered` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  KEY `email` (`email`),
  KEY `activation` (`activation`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `authorizations`
--

DROP TABLE IF EXISTS `authorizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authorizations` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` mediumint(8) unsigned NOT NULL,
  `action` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `value` int(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  CONSTRAINT `authorizations_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `averages`
--

DROP TABLE IF EXISTS `averages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `averages` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `student` mediumint(8) unsigned NOT NULL,
  `subject` smallint(3) unsigned NOT NULL,
  `year` smallint(4) unsigned NOT NULL,
  `sem1` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `sem2` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `exam` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `final` float(2,1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  KEY `subject` (`subject`),
  KEY `year` (`year`),
  CONSTRAINT `averages_ibfk_1` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `averages_ibfk_2` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `averages_ibfk_3` FOREIGN KEY (`year`) REFERENCES `schoolyear` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `breaks`
--

DROP TABLE IF EXISTS `breaks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `breaks` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chart`
--

DROP TABLE IF EXISTS `chart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chart` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `subject` smallint(3) unsigned NOT NULL,
  `student` mediumint(8) unsigned NOT NULL,
  `average` float unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject`),
  KEY `student` (`student`),
  CONSTRAINT `chart_ibfk_1` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chart_ibfk_2` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `school` smallint(3) unsigned NOT NULL,
  `teacher` mediumint(8) unsigned NOT NULL,
  `grade` tinyint(2) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  `profile` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `school` (`school`),
  KEY `teacher` (`teacher`),
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`school`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`teacher`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `classes_subjects_assign`
--

DROP TABLE IF EXISTS `classes_subjects_assign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes_subjects_assign` (
  `class` smallint(5) unsigned NOT NULL,
  `subject` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`class`,`subject`),
  KEY `subject` (`subject`),
  CONSTRAINT `classes_subjects_assign_ibfk_1` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `classes_subjects_assign_ibfk_2` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hcategories`
--

DROP TABLE IF EXISTS `hcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hcategories` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hposts`
--

DROP TABLE IF EXISTS `hposts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hposts` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category` tinyint(2) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `update` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  CONSTRAINT `hposts_ibfk_1` FOREIGN KEY (`category`) REFERENCES `hcategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marks` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `student` mediumint(8) unsigned NOT NULL,
  `subject` smallint(3) unsigned NOT NULL,
  `mark` tinyint(2) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject`),
  KEY `student` (`student`),
  CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parents` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `related` varchar(50) NOT NULL,
  `adress` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `class` smallint(5) unsigned NOT NULL,
  `hour` tinyint(2) unsigned NOT NULL,
  `subject` smallint(3) unsigned NOT NULL,
  `weekday` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`class`,`weekday`,`hour`),
  KEY `subject` (`subject`),
  KEY `class` (`class`),
  CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schools` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schoolyear`
--

DROP TABLE IF EXISTS `schoolyear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schoolyear` (
  `id` smallint(4) unsigned NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `change` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `start` (`start`),
  KEY `change` (`change`),
  KEY `end` (`end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `securityQuestions`
--

DROP TABLE IF EXISTS `securityQuestions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `securityQuestions` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` mediumint(8) unsigned NOT NULL,
  `message` varchar(140) NOT NULL,
  `hour1` tinyint(3) NOT NULL DEFAULT '-1',
  `hour2` tinyint(3) NOT NULL DEFAULT '-1',
  `status` tinyint(1) unsigned NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  `charge` float unsigned NOT NULL DEFAULT '0',
  `to` char(14) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  CONSTRAINT `sms_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics`
--

DROP TABLE IF EXISTS `statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `class` smallint(5) unsigned NOT NULL,
  `semester` tinyint(1) unsigned NOT NULL,
  `year` smallint(4) unsigned NOT NULL,
  `key` varchar(40) CHARACTER SET ascii NOT NULL,
  `value` text CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `CYS` (`class`,`year`,`semester`),
  KEY `class` (`class`),
  CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `class` smallint(5) unsigned NOT NULL,
  `school` smallint(3) unsigned NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `school` (`school`),
  KEY `parent` (`parent`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `students_ibfk_2` FOREIGN KEY (`school`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `students_ibfk_3` FOREIGN KEY (`parent`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `school` smallint(3) unsigned NOT NULL DEFAULT '0',
  `class` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `school` (`school`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `warnings`
--

DROP TABLE IF EXISTS `warnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warnings` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `student` mediumint(8) unsigned NOT NULL,
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) unsigned NOT NULL,
  `json` text COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  CONSTRAINT `warnings_ibfk_1` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-11-01  2:08:54
