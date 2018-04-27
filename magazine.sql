-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 04 月 10 日 15:08
-- 服务器版本: 5.0.51
-- PHP 版本: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `pm_articles`
--

-- --------------------------------------------------------

--
-- 表的结构 `pm_users`
--
DROP TABLE IF EXISTS `think_article`;
CREATE TABLE IF NOT EXISTS `think_article` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` char(30) collate utf8_bin NOT NULL,
  `designer` char(30) collate utf8_bin NOT NULL,
  `preview` char(100) collate utf8_bin NOT NULL,
  `material` char(100) collate utf8_bin NOT NULL,
  `engineer` char(30) collate utf8_bin NOT NULL,
  `remark` char(200) collate utf8_bin NOT NULL,
  `cmsid` char(30) collate utf8_bin NOT NULL,
  `issueid` char(30) collate utf8_bin NOT NULL,
  `columnname` char(30) collate utf8_bin NOT NULL,
  `magazinename` char(30) collate utf8_bin NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `status` mediumint(8) unsigned NOT NULL,
  `create_time` char(30) collate utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `think_issue`;
CREATE TABLE IF NOT EXISTS `think_issue` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` char(30) collate utf8_bin NOT NULL,
  `magazine` char(30) collate utf8_bin NOT NULL,
  `device` char(30) collate utf8_bin NOT NULL,
  `issueid` char(30) collate utf8_bin NOT NULL,
  `status` mediumint(8) unsigned NOT NULL,
  `create_time` char(30) collate utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
