/*
Navicat MySQL Data Transfer

Source Server         : x
Source Server Version : 50640
Source Host           : 0.0.0.0:3306
Source Database       : sw

Target Server Type    : MYSQL
Target Server Version : 50640
File Encoding         : 65001

Date: 2019-05-03 11:59:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for click_count
-- ----------------------------
DROP TABLE IF EXISTS `click_count`;
CREATE TABLE `click_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `count_click` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of click_count
-- ----------------------------
INSERT INTO `click_count` VALUES ('1', '查课表', '0');
INSERT INTO `click_count` VALUES ('2', '查教室', '0');
INSERT INTO `click_count` VALUES ('3', '查成绩', '0');
INSERT INTO `click_count` VALUES ('4', '共享课表', '0');
INSERT INTO `click_count` VALUES ('5', '图书馆', '0');
INSERT INTO `click_count` VALUES ('6', '分享链接', '0');
INSERT INTO `click_count` VALUES ('7', '源码', '0');
INSERT INTO `click_count` VALUES ('8', '更新日志', '0');
INSERT INTO `click_count` VALUES ('9', '关于', '0');
INSERT INTO `click_count` VALUES ('10', '书籍检索', '0');
INSERT INTO `click_count` VALUES ('11', '借阅查询', '0');

-- ----------------------------
-- Table structure for url_share
-- ----------------------------
DROP TABLE IF EXISTS `url_share`;
CREATE TABLE `url_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of url_share
-- ----------------------------
INSERT INTO `url_share` VALUES ('1', '四六级报名', 'http://cet-bm.neea.edu.cn/');
INSERT INTO `url_share` VALUES ('2', '四六级成绩查询', 'http://cet.neea.edu.cn/cet/');
INSERT INTO `url_share` VALUES ('3', '计算机等级考试', 'http://www.sdzk.cn/floadup/ncrebm/ncrebm.htm');
INSERT INTO `url_share` VALUES ('4', '计算机等级考试成绩查询', 'http://cjcx.neea.edu.cn/html1/folder/1508/206-1.htm?sid=300');
INSERT INTO `url_share` VALUES ('5', 'CCF', 'http://cspro.org/');
INSERT INTO `url_share` VALUES ('6', '超星学习通', 'http://i.mooc.chaoxing.com/space/index');
INSERT INTO `url_share` VALUES ('7', '普通话考试成绩查询', 'http://www.cltt.org/studentscore');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `academy` varchar(100) NOT NULL DEFAULT '',
  `use_time` datetime DEFAULT NULL,
  `log_time` datetime DEFAULT NULL,
  `log_times` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for timetable_share
-- ----------------------------
DROP TABLE IF EXISTS `timetable_share`;
CREATE TABLE `timetable_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `week` int(11) NOT NULL,
  `timetable` varchar(5000) NOT NULL,
  `pair_status` int(5) NOT NULL DEFAULT '1',
  `pair_account` varchar(30) DEFAULT NULL,
  `pair_name` varchar(30) DEFAULT NULL,
  `pair_point` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;