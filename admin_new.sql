/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50538
Source Host           : 127.0.0.1:3306
Source Database       : admin_new

Target Server Type    : MYSQL
Target Server Version : 50538
File Encoding         : 65001

Date: 2014-11-29 15:32:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_resource
-- ----------------------------
DROP TABLE IF EXISTS `admin_resource`;
CREATE TABLE `admin_resource` (
  `resource_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `title` varchar(100) NOT NULL COMMENT '节点名称',
  `url_directory` varchar(100) NOT NULL DEFAULT '' COMMENT '控制器目录',
  `url_class` varchar(100) NOT NULL COMMENT '控制器类名',
  `url_method` varchar(100) NOT NULL COMMENT '控制器方法',
  `url_param` varchar(255) NOT NULL DEFAULT '' COMMENT '参数，json存储',
  `pid` int(4) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '级别 1一级标题 2二级标题 3菜单 4其他',
  `permission_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1必须控制，0非必须控制',
  `sort` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '排序，越大越靠前',
  `stat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2已冻结 3已删除',
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`resource_id`),
  KEY `idx_pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='资源管理';

-- ----------------------------
-- Records of admin_resource
-- ----------------------------
INSERT INTO `admin_resource` VALUES ('1', '系统设置', '', 'admin', '', '', '0', '1', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('2', '系统设置', '', '', '', '', '1', '2', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('3', '我的信息', '', 'admin', 'index', '', '2', '3', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('4', '管理员列表', '', 'admin', 'lists', '', '2', '3', '1', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('5', '编辑信息', '', 'admin', 'edit', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('6', '编辑信息处理', '', 'admin', 'verify', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('7', '分配角色', '', 'admin', 'dispatcher', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('8', '新增管理员', '', 'admin', 'create', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('9', '资源管理', '', 'resource', 'index', '', '2', '3', '1', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('10', '资源详情', '', 'resource', 'detail', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('11', '编辑资源', '', 'resource', 'edit', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('12', '创建资源', '', 'resource', 'create', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('13', '创建资源处理', '', 'resource', 'verify', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('14', '删除资源', '', 'resource', 'del', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('15', '分配最大权限', '', 'resource', 'dispatcher', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('16', '分配最大权限处理', '', 'resource', 'assign', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('17', '角色管理', '', 'role', 'index', '', '2', '3', '1', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('18', '角色详情', '', 'role', 'detail', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('19', '编辑角色', '', 'role', 'edit', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('20', '创建角色', '', 'role', 'create', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('21', '创建角色处理', '', 'role', 'verify', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('22', '删除角色', '', 'role', 'del', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('23', '权限分配', '', 'role', 'dispatcher', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('24', '删除管理员', '', 'admin', 'del', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');
INSERT INTO `admin_resource` VALUES ('25', '检测用户名', '', 'ajax', 'check_username', '', '2', '4', '0', '0.00', '1', '2014-11-05 10:30:11');

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `role_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  `source_id` int(4) NOT NULL COMMENT '角色创建者',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `stat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态| 1正常 2已冻结 3已删除',
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色管理';

-- ----------------------------
-- Records of admin_role
-- ----------------------------

-- ----------------------------
-- Table structure for admin_role_resource
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_resource`;
CREATE TABLE `admin_role_resource` (
  `role_resource_id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(4) NOT NULL COMMENT '角色ID',
  `resource_id` int(4) NOT NULL COMMENT '资源ID',
  PRIMARY KEY (`role_resource_id`),
  KEY `idx_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色资源关系管理';

-- ----------------------------
-- Records of admin_role_resource
-- ----------------------------

-- ----------------------------
-- Table structure for admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_user`;
CREATE TABLE `admin_role_user` (
  `role_user_id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(4) NOT NULL COMMENT '角色ID',
  `user_id` int(4) NOT NULL COMMENT '用户ID',
  PRIMARY KEY (`role_user_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色关系管理';

-- ----------------------------
-- Records of admin_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for admin_source_resource
-- ----------------------------
DROP TABLE IF EXISTS `admin_source_resource`;
CREATE TABLE `admin_source_resource` (
  `source_resource_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `source_id` int(4) NOT NULL COMMENT '渠道ID',
  `resource_id` int(4) NOT NULL COMMENT '资源ID',
  PRIMARY KEY (`source_resource_id`),
  KEY `idx_source_id` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='渠道最大权限控制';

-- ----------------------------
-- Records of admin_source_resource
-- ----------------------------

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `user_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `salt` varchar(128) NOT NULL COMMENT '密钥',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机',
  `source_id` int(4) NOT NULL COMMENT '来源',
  `department` varchar(100) NOT NULL DEFAULT '' COMMENT '部门',
  `stat` tinyint(1) NOT NULL COMMENT '状态| 1正常 2已冻结 3已删除',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 普通 1 管理员 2超管',
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `last_logined_ts` timestamp NULL DEFAULT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户管理';

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'admin', 'f3c886c6c7fc33b9c373e1d407186c6e', 'efnn', 'Xuhf', '123456@qq.com', '', '0', '', '1', '2', '2013-07-22 11:10:40', '2014-11-29 15:29:08');
