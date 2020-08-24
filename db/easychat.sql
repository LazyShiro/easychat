/*
 Navicat Premium Data Transfer

 Source Server         : 测试ECS
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : localhost:3306
 Source Schema         : easychat

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 22/08/2020 23:15:21
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for chat_friend
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend`;
CREATE TABLE `chat_friend`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `groupid` int(11) NOT NULL DEFAULT 0 COMMENT '好友分组id',
  `friendid` int(11) NOT NULL DEFAULT 0 COMMENT '朋友id',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0申请 1接受 2拒绝 3删除 4拉黑',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `groupid`(`groupid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_friend_group
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend_group`;
CREATE TABLE `chat_friend_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '我的好友' COMMENT '分组名称',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型 0系统 1自定',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 0删除 1正常',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友分组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_friend_group_log
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend_group_log`;
CREATE TABLE `chat_friend_group_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `groupid` int(11) NOT NULL DEFAULT 0 COMMENT '分组id',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型 0删除 1创建 2修改',
  `data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '附加信息',
  `createtime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `groupid`(`groupid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友分组日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_friend_log
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend_log`;
CREATE TABLE `chat_friend_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `friendid` int(11) NOT NULL DEFAULT 0 COMMENT '好友id',
  `groupid` int(11) NOT NULL DEFAULT 0 COMMENT '好友分组id',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '类型 0请求 1接受 2拒绝 3删除 4拉黑',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '好友验证消息',
  `read` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0未读 1已读',
  `createtime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `friendid`(`friendid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友关系日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_member
-- ----------------------------
DROP TABLE IF EXISTS `chat_member`;
CREATE TABLE `chat_member`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '账号',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `salt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码盐',
  `avatar` int(11) NOT NULL DEFAULT 0 COMMENT '头像',
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '我注册了一个沙雕网站，我有点慌' COMMENT '个性签名',
  `gender` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别 0未知 1男 2女',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '账号状态 0禁止 1正常',
  `fettle` tinyint(1) NOT NULL DEFAULT 1 COMMENT '在线状态 0离线 1在线 2隐身 3忙碌 4离开',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `username`(`username`) USING BTREE,
  INDEX `gender`(`gender`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_member_log
-- ----------------------------
DROP TABLE IF EXISTS `chat_member_log`;
CREATE TABLE `chat_member_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT 1 COMMENT '类型 0禁止 1注册 2更改昵称 3更改密码 4更换头像 5更换个签 6更换性别 7更换在线状态',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '附加数据',
  `createtime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_record
-- ----------------------------
DROP TABLE IF EXISTS `chat_record`;
CREATE TABLE `chat_record`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `roomid` int(11) NOT NULL DEFAULT 0 COMMENT '房间id',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '发送者id',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 0撤回 1正常 2删除',
  `read` tinyint(1) NOT NULL DEFAULT 0 COMMENT '查看状态 0未读 1已读',
  `createtime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `deletetime` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `roomid`(`roomid`) USING BTREE,
  INDEX `receiverid`(`uid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '聊天记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_room
-- ----------------------------
DROP TABLE IF EXISTS `chat_room`;
CREATE TABLE `chat_room`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '房间号',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '创建者uid',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '房间名称',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '房间描述',
  `membermax` int(11) NOT NULL DEFAULT 1 COMMENT '房间最大人数限制 1为不限制',
  `membercount` int(11) NOT NULL DEFAULT 1 COMMENT '房间当前人数',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型 0私聊 1群聊',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 0删除 1正常 2异常',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `number`(`number`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '房间表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_room_log
-- ----------------------------
DROP TABLE IF EXISTS `chat_room_log`;
CREATE TABLE `chat_room_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `roomid` int(11) NOT NULL DEFAULT 0 COMMENT '房间id',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '操作人id',
  `type` tinyint(2) NOT NULL DEFAULT 1 COMMENT '类型 0删除 1正常 2异常',
  `createtime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `roomid`(`roomid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '房间日志表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
