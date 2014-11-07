/*
Navicat MySQL Data Transfer

Source Server         : link
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : tasktracking

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2014-10-30 15:43:31
*/
DROP DATABASE if exists tasktracking;

CREATE DATABASE tasktracking character set utf8;

USE tasktracking;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for staff_inf
-- ----------------------------
DROP TABLE IF EXISTS `staff_inf`;
CREATE TABLE `staff_inf` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` varchar(255) NOT NULL,
  `usedname` varchar(255) DEFAULT NULL,
  `weight` int(2) NOT NULL DEFAULT '0',
  `height` int(2) NOT NULL DEFAULT '0',
  `nation` varchar(255) NOT NULL DEFAULT '',
  `nativeplace` varchar(255) NOT NULL DEFAULT '',
  `marital` tinyint(1) NOT NULL DEFAULT '0',
  `politics` varchar(255) DEFAULT NULL,
  `health` varchar(255) NOT NULL DEFAULT '',
  `bloodtype` varchar(255) NOT NULL DEFAULT '',
  `IDCard` varchar(255) NOT NULL DEFAULT '',
  `accounttype` tinyint(1) NOT NULL DEFAULT '0',
  `accountaddress` varchar(255) NOT NULL DEFAULT '',
  `educationBackground` varchar(255) NOT NULL DEFAULT '',
  `degree` varchar(255) DEFAULT NULL,
  `seconddegree` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `secondmajor` varchar(255) DEFAULT NULL,
  `graduateschool` varchar(255) NOT NULL DEFAULT '',
  `graduatetime` varchar(255) NOT NULL DEFAULT '2014-10-28',
  `foreignlanguage` varchar(255) DEFAULT NULL,
  `computerability` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `familyaddress` varchar(255) NOT NULL DEFAULT '',
  `cellphone` varchar(255) NOT NULL DEFAULT '',
  `familymember` longtext,
  `emergencycontact` varchar(255) DEFAULT NULL,
  `educationexperience` longtext NOT NULL,
  `workexperience` longtext,
  `awards` longtext,
  `speciality` longtext,
  `trainexperience` longtext,
  `selfassessment` longtext,
  `remark` longtext,
  `telphone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `staffID` FOREIGN KEY (`id`) REFERENCES `tt_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staff_inf
-- ----------------------------
INSERT INTO `staff_inf` VALUES ('0', 'admin', '-1', '', null, '0', '0', '', '', '-1', null, '', '', '', '-1', '', '', null, null, null, null, '', '2014-10-28', null, null, null, '', '', '', null, null, '', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for tt_daily_report
-- ----------------------------
DROP TABLE IF EXISTS `tt_daily_report`;
CREATE TABLE `tt_daily_report` (
  `daily_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `daily_date` date NOT NULL,
  `daily_project` varchar(255) NOT NULL,
  `daily_roles` varchar(255) NOT NULL,
  `daily_hours` int(11) NOT NULL,
  `daily_task` varchar(255) NOT NULL,
  `daily_description` longtext NOT NULL,
  `daily_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`daily_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tt_daily_report
-- ----------------------------
INSERT INTO `tt_daily_report` VALUES ('125', '0', '2014-10-08', 'fasdf ', 'fads ', '4', 'fsadf ', 'fasd fdsa ', '0');

-- ----------------------------
-- Table structure for tt_month_report
-- ----------------------------
DROP TABLE IF EXISTS `tt_month_report`;
CREATE TABLE `tt_month_report` (
  `month_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `month_date` date NOT NULL,
  `month_task` varchar(255) NOT NULL,
  `month_description` longtext NOT NULL,
  `month_hours` int(11) NOT NULL,
  `verifed_hours` int(11) NOT NULL,
  `month_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`month_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tt_month_report
-- ----------------------------

-- ----------------------------
-- Table structure for tt_user
-- ----------------------------
DROP TABLE IF EXISTS `tt_user`;
CREATE TABLE `tt_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_pwd` varchar(100) NOT NULL,
  `user_alias` varchar(100) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `user_department` varchar(100) NOT NULL,
  `is_leader` int(1) NOT NULL DEFAULT '0',
  `is_admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`user_alias`),
  UNIQUE KEY `uq_key` (`user_alias`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tt_user
-- ----------------------------
INSERT INTO `tt_user` VALUES ('0', '21232F297A57A5A743894A0E4A801FC3', 'admin', '0', 'dev', '1', '1');
