-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-08-25 14:10:03
-- 服务器版本: 5.0.91-community-nt
-- PHP 版本: 5.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `wifiadv`
--

-- --------------------------------------------------------

--
-- 表的结构 `wifi_access`
--

CREATE TABLE IF NOT EXISTS `wifi_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) default NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_ad`
--

CREATE TABLE IF NOT EXISTS `wifi_ad` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL COMMENT '上传作者ID',
  `ad_pos` tinyint(4) NOT NULL default '0' COMMENT '广告位置 0首页 ',
  `ad_thumb` varchar(50) default NULL COMMENT '广告缩略图',
  `ad_sort` int(11) default NULL COMMENT '广告排序',
  `title` varchar(255) default NULL,
  `info` text,
  `mode` tinyint(4) default '0' COMMENT '0：图片 1 图文 2 链接',
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_adcount`
--

CREATE TABLE IF NOT EXISTS `wifi_adcount` (
  `id` int(11) NOT NULL auto_increment,
  `shopid` int(11) default NULL,
  `aid` int(11) default NULL COMMENT '广告ID',
  `showup` int(11) default NULL,
  `hit` int(11) default NULL,
  `add_time` varchar(20) default NULL,
  `add_date` varchar(20) default NULL,
  `mode` tinyint(4) default NULL COMMENT '类型  1 商户广告统计  99 运营商投放广告 ',
  `agent` varchar(100) default NULL,
  `ip` varchar(16) default NULL,
  `browser` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_admin`
--

CREATE TABLE IF NOT EXISTS `wifi_admin` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `role` int(11) default '0',
  `note` varchar(255) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default '1' COMMENT '0:停用 1 正常',
  `last_loginip` varchar(255) default NULL,
  `last_logintime` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `wifi_admin`
--

INSERT INTO `wifi_admin` (`id`, `user`, `password`, `role`, `note`, `add_time`, `update_time`, `state`, `last_loginip`, `last_logintime`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 0, NULL, '1389750196', '1408945120', 1, '127.0.0.1', '1408945201');

-- --------------------------------------------------------

--
-- 表的结构 `wifi_agent`
--

CREATE TABLE IF NOT EXISTS `wifi_agent` (
  `id` int(11) NOT NULL auto_increment,
  `account` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `linker` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `info` text,
  `money` decimal(11,2) default '0.00' COMMENT '金额',
  `fee` decimal(11,2) default '0.00' COMMENT '代理费',
  `level` int(11) default NULL COMMENT '等级',
  `province` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `area` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `point_x` varchar(255) default NULL,
  `point_y` varchar(255) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default '1' COMMENT '0:停用 1：启用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `wifi_agent`
--

INSERT INTO `wifi_agent` (`id`, `account`, `password`, `linker`, `phone`, `name`, `info`, `money`, `fee`, `level`, `province`, `city`, `area`, `address`, `point_x`, `point_y`, `add_time`, `update_time`, `state`) VALUES
(1, 'souho', '96e79218965eb72c92a549dd5a330112', '搜虎精品社区', '18888888888', '搜虎精品社区www.souho.net', NULL, '0.00', '888.00', 1, '北京市', '市辖区', '东城区', '', NULL, NULL, '1408944302', '1408944302', 1);

-- --------------------------------------------------------

--
-- 表的结构 `wifi_agentlevel`
--

CREATE TABLE IF NOT EXISTS `wifi_agentlevel` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `note` varchar(255) default NULL,
  `openpay` decimal(10,2) default '0.00' COMMENT '开户金额',
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `wifi_agentlevel`
--

INSERT INTO `wifi_agentlevel` (`id`, `title`, `note`, `openpay`, `add_time`, `update_time`, `state`) VALUES
(1, '至尊代理商', '', '8888.00', '1408944264', '1408944264', 1);

-- --------------------------------------------------------

--
-- 表的结构 `wifi_agentpay`
--

CREATE TABLE IF NOT EXISTS `wifi_agentpay` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) default NULL COMMENT '代理商ID',
  `do` tinyint(4) default NULL COMMENT '模式 0 扣款 1 充值',
  `oldmoney` decimal(10,0) default NULL COMMENT '原金额',
  `nowmoney` decimal(10,0) default NULL COMMENT '当前金额',
  `paymoney` decimal(10,0) default NULL COMMENT '支付金额',
  `desc` varchar(255) default NULL COMMENT '描述信息',
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_agentpushset`
--

CREATE TABLE IF NOT EXISTS `wifi_agentpushset` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '0',
  `pushflag` tinyint(4) default NULL COMMENT '是否启用广告推送',
  `showtime` int(11) default '3',
  `add_time` varchar(20) default NULL,
  `update_time` varchar(20) default NULL,
  PRIMARY KEY  (`id`,`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_arts`
--

CREATE TABLE IF NOT EXISTS `wifi_arts` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `cid` int(11) default NULL,
  `title` varchar(50) default NULL,
  `titlepic` varchar(255) default NULL,
  `desc` varchar(200) default NULL,
  `info` text,
  `topflag` tinyint(4) default '0' COMMENT '0:否 1 是 是否置顶',
  `add_time` varchar(20) default NULL,
  `update_time` varchar(20) default NULL,
  `state` tinyint(4) default '1' COMMENT '0: stop 1:ok',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_authlist`
--

CREATE TABLE IF NOT EXISTS `wifi_authlist` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) default NULL,
  `uid` int(11) default NULL,
  `shopid` int(11) default NULL,
  `routeid` int(11) default NULL,
  `mode` tinyint(4) default NULL COMMENT '认证模式',
  `mac` varchar(50) default NULL,
  `add_date` varchar(50) default NULL COMMENT '日期',
  `pingcount` int(4) default '0' COMMENT '检测链接次数',
  `login_time` varchar(50) default NULL COMMENT '登录时间',
  `login_ip` varchar(20) default NULL,
  `browser` varchar(50) default NULL COMMENT '浏览器',
  `agent` varchar(100) default NULL COMMENT '机器消息',
  `over_time` varchar(50) NOT NULL COMMENT '允许在线时长',
  `update_time` varchar(50) default NULL,
  `last_time` varchar(50) default NULL COMMENT '最后在线时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_authtpl`
--

CREATE TABLE IF NOT EXISTS `wifi_authtpl` (
  `id` int(4) NOT NULL,
  `tpname` varchar(10) default NULL COMMENT '模板名称',
  `keyname` varchar(30) default NULL COMMENT '关键字名称',
  `info` varchar(100) default NULL,
  `pic` varchar(50) default NULL,
  `shopid` int(11) default '0' COMMENT '关联商户ID',
  `state` tinyint(1) default NULL,
  `group` smallint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wifi_authtpl`
--

INSERT INTO `wifi_authtpl` (`id`, `tpname`, `keyname`, `info`, `pic`, `shopid`, `state`, `group`) VALUES
(1001, '默认模板', 'default', NULL, 'default.jpg', NULL, 1, 1),
(1002, '默认模板2', 'wifiadv', NULL, 'wifiadv.jpg', NULL, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `wifi_buttonset`
--

CREATE TABLE IF NOT EXISTS `wifi_buttonset` (
  `id` int(11) NOT NULL auto_increment,
  `shopid` int(11) default NULL,
  `keyname` varchar(50) default NULL COMMENT '关键字',
  `eflag` tinyint(1) default '0' COMMENT '启用标志 1:启用',
  `icopath` varchar(100) default NULL COMMENT '图标路径',
  `fontcolor` varchar(10) default NULL COMMENT '字体颜色',
  `bgcolor` varchar(10) default NULL COMMENT '背景色',
  `extstyle` varchar(200) default NULL COMMENT '扩展css',
  `sort` int(3) default NULL COMMENT '按钮排序',
  `add_time` varchar(15) default NULL,
  `update_time` varchar(15) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_comment`
--

CREATE TABLE IF NOT EXISTS `wifi_comment` (
  `id` int(11) NOT NULL auto_increment,
  `shopid` int(11) default NULL,
  `routeid` int(11) default NULL,
  `user` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  `content` varchar(300) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_day`
--

CREATE TABLE IF NOT EXISTS `wifi_day` (
  `id` int(11) NOT NULL,
  `tname` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wifi_day`
--

INSERT INTO `wifi_day` (`id`, `tname`) VALUES
(0, '00'),
(1, '01'),
(2, '02'),
(3, '03'),
(4, '04'),
(5, '05'),
(6, '06'),
(7, '07'),
(8, '08'),
(9, '09'),
(10, '10'),
(11, '11'),
(12, '12'),
(13, '13'),
(14, '14'),
(15, '15'),
(16, '16'),
(17, '17'),
(18, '18'),
(19, '19'),
(20, '20'),
(21, '21'),
(22, '22'),
(23, '23'),
(24, '24'),
(25, '25'),
(26, '26'),
(27, '27'),
(28, '28'),
(29, '29'),
(30, '30'),
(31, '31');

-- --------------------------------------------------------

--
-- 表的结构 `wifi_hours`
--

CREATE TABLE IF NOT EXISTS `wifi_hours` (
  `t` varchar(5) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wifi_hours`
--

INSERT INTO `wifi_hours` (`t`) VALUES
('01'),
('02'),
('03'),
('04'),
('05'),
('06'),
('07'),
('08'),
('09'),
('10'),
('11'),
('12'),
('13'),
('14'),
('15'),
('16'),
('17'),
('18'),
('19'),
('20'),
('21'),
('22'),
('23'),
('24');

-- --------------------------------------------------------

--
-- 表的结构 `wifi_join`
--

CREATE TABLE IF NOT EXISTS `wifi_join` (
  `id` int(11) NOT NULL auto_increment,
  `server` varchar(500) default NULL,
  `host` varchar(50) default NULL,
  `ip` varchar(50) default NULL,
  `version` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_member`
--

CREATE TABLE IF NOT EXISTS `wifi_member` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(255) default NULL COMMENT '帐号',
  `password` varchar(255) default NULL COMMENT '密码',
  `mode` varchar(10) default NULL COMMENT '认证模式 根据认证表ID 注册认证，手机认证，qq认证，微博认证等',
  `shopid` int(11) default NULL COMMENT '帐号ID',
  `routeid` int(11) default NULL COMMENT '路由ID',
  `token` varchar(100) default NULL COMMENT '使用口令',
  `phone` varchar(255) default NULL,
  `qq` varchar(255) default NULL,
  `mac` varchar(255) default NULL COMMENT 'mac地址',
  `login_time` varchar(30) default NULL COMMENT '路由登录时间',
  `login_count` int(11) default '0' COMMENT '路由登录次数',
  `login_ip` varchar(50) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `browser` varchar(255) default NULL,
  `online_time` varchar(255) default NULL COMMENT '在线有效期',
  `add_date` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `index_token` USING BTREE (`token`),
  KEY `index_shop` USING BTREE (`shopid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_month`
--

CREATE TABLE IF NOT EXISTS `wifi_month` (
  `id` int(11) NOT NULL,
  `mon` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wifi_month`
--

INSERT INTO `wifi_month` (`id`, `mon`) VALUES
(1, '01'),
(2, '02'),
(3, '03'),
(4, '04'),
(5, '05'),
(6, '06'),
(7, '07'),
(8, '08'),
(9, '09'),
(10, '10'),
(11, '11'),
(12, '12');

-- --------------------------------------------------------

--
-- 表的结构 `wifi_nav`
--

CREATE TABLE IF NOT EXISTS `wifi_nav` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) default '0' COMMENT '父ID',
  `title` varchar(255) default NULL COMMENT '栏目名称',
  `mode` tinyint(4) default NULL COMMENT '0:单页 1:列表 ',
  `config` varchar(255) default NULL COMMENT '配置json',
  `img` varchar(255) default NULL COMMENT '图片信息',
  `sort` int(11) default '0' COMMENT '排序',
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default '1' COMMENT '0:停用 1:启用',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_news`
--

CREATE TABLE IF NOT EXISTS `wifi_news` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` varchar(100) default NULL,
  `desc` varchar(200) default NULL,
  `title` varchar(50) default NULL,
  `info` text,
  `mode` varchar(10) default NULL,
  `add_time` varchar(20) default NULL,
  `update_time` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_node`
--

CREATE TABLE IF NOT EXISTS `wifi_node` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) default NULL,
  `status` tinyint(1) default '0',
  `remark` varchar(255) default NULL,
  `sort` smallint(6) unsigned default NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `bmenu` tinyint(4) default '0',
  `single` tinyint(4) default '0' COMMENT '是否还有子节点 0:否 1 是',
  `ico` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_notice`
--

CREATE TABLE IF NOT EXISTS `wifi_notice` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) default NULL,
  `info` text,
  `add_time` varchar(15) default NULL,
  `update_time` varchar(15) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_pushadv`
--

CREATE TABLE IF NOT EXISTS `wifi_pushadv` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) default NULL,
  `mode` tinyint(4) default '0' COMMENT '投放路径 ',
  `pic` varchar(255) default NULL COMMENT '广告存放路径',
  `info` varchar(200) default NULL COMMENT '备注',
  `sort` int(11) default '0' COMMENT '排序',
  `showcount` int(11) default '0' COMMENT '展示次数',
  `add_time` varchar(20) default NULL,
  `startdate` varchar(20) default NULL,
  `enddate` varchar(20) default NULL,
  `update_time` varchar(20) default NULL,
  `state` tinyint(4) default '1' COMMENT '0:停止 1 正常',
  `aid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_role`
--

CREATE TABLE IF NOT EXISTS `wifi_role` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) default NULL,
  `status` tinyint(1) unsigned default NULL,
  `remark` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_role_user`
--

CREATE TABLE IF NOT EXISTS `wifi_role_user` (
  `role_id` mediumint(9) unsigned default NULL,
  `user_id` char(32) default NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_routemap`
--

CREATE TABLE IF NOT EXISTS `wifi_routemap` (
  `id` int(11) NOT NULL auto_increment,
  `shopid` int(11) NOT NULL COMMENT '帐号ID',
  `routename` varchar(255) default NULL COMMENT '路由名称',
  `gw_address` varchar(255) default NULL,
  `gw_port` varchar(255) default NULL,
  `gw_id` varchar(255) default NULL COMMENT '网关ID',
  `sortid` int(11) default '0',
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `sys_uptime` varchar(255) default NULL COMMENT '路由时间',
  `sys_memfree` varchar(255) default NULL,
  `wifidog_uptime` varchar(255) default NULL COMMENT '路由跟踪时间',
  `sys_load` varchar(255) default NULL,
  `last_heartbeat_ip` varchar(255) default NULL COMMENT '心跳包IP',
  `last_heartbeat_time` varchar(255) default NULL COMMENT '心跳时间',
  `user_agent` varchar(255) default NULL COMMENT '消息头',
  `notes` varchar(255) default NULL COMMENT '备注信息',
  `state` tinyint(4) default '1' COMMENT '0:停用 1：启用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_shop`
--

CREATE TABLE IF NOT EXISTS `wifi_shop` (
  `id` int(11) NOT NULL auto_increment,
  `account` varchar(255) NOT NULL COMMENT '管理帐号',
  `password` varchar(255) NOT NULL,
  `shopname` varchar(255) default NULL COMMENT '门店名称',
  `pid` int(11) default '0' COMMENT '代理商ID',
  `mode` tinyint(4) default '0' COMMENT '0:注册用户 1：代理商添加 ',
  `logo` varchar(255) default NULL,
  `linker` varchar(255) default NULL,
  `phone` varchar(255) default NULL COMMENT '联系电话',
  `info` text COMMENT '门店描述',
  `address` varchar(255) default NULL,
  `point_x` varchar(255) default NULL,
  `point_y` varchar(255) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default '1' COMMENT '0:停用 1：启用',
  `tpl_id` int(11) default NULL,
  `tpl_path` varchar(255) default NULL COMMENT '模板地址',
  `trade` varchar(255) default NULL COMMENT '行业类型 #区分',
  `province` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `area` varchar(255) default NULL,
  `maxcount` int(255) default '0' COMMENT '认证使用次数',
  `end_time` varchar(255) default NULL COMMENT '帐号有效期',
  `shopgroup` varchar(255) default NULL COMMENT '商圈',
  `shoplevel` varchar(255) default NULL COMMENT '店铺消费水平 多组 用 #区分',
  `routetype` tinyint(4) default '0' COMMENT '0:单路由 1 多路由',
  `authmode` varchar(255) default NULL COMMENT '认证模式',
  `authaction` tinyint(4) default NULL,
  `jumpurl` varchar(255) default NULL,
  `linkflag` tinyint(2) default '0' COMMENT '0:限制注册上限  1 不限制使用',
  `timelimit` int(11) default '0',
  `sh` int(4) default '0' COMMENT '开始时段',
  `eh` int(4) default '0' COMMENT '结束时段',
  `countflag` tinyint(1) NOT NULL default '0',
  `countmax` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `wifi_shop`
--

INSERT INTO `wifi_shop` (`id`, `account`, `password`, `shopname`, `pid`, `mode`, `logo`, `linker`, `phone`, `info`, `address`, `point_x`, `point_y`, `add_time`, `update_time`, `state`, `tpl_id`, `tpl_path`, `trade`, `province`, `city`, `area`, `maxcount`, `end_time`, `shopgroup`, `shoplevel`, `routetype`, `authmode`, `authaction`, `jumpurl`, `linkflag`, `timelimit`, `sh`, `eh`, `countflag`, `countmax`) VALUES
(1, 'xc', '202cb962ac59075b964b07152d234b70', '协成智慧无线营销系统v2.0', 1, NULL, NULL, '搜虎精品社区', '18888888888', NULL, '利通北街125#', NULL, NULL, '1395301285', '1408943769', 1, 1001, 'default', '#餐饮##咖啡厅#', '山东', '青岛市', '市南区', 60000, '1395301285', NULL, '#小资##中高档##高档##奢华#', 0, '#0##3={"user":"\\u798f\\u6e05app","pwd":"22586"}#', 1, 'http://www.baidu.com', 1, 600, 8, 17, 1, 2);

-- --------------------------------------------------------

--
-- 表的结构 `wifi_sms`
--

CREATE TABLE IF NOT EXISTS `wifi_sms` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL COMMENT '帐号ID',
  `mode` tinyint(4) default '0' COMMENT '0:马上发送 1：定时发送',
  `phone` varchar(255) default NULL,
  `info` varchar(255) default NULL,
  `lens` int(11) default NULL COMMENT '字数',
  `unit` int(11) default NULL COMMENT '短信条数',
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `send_time` varchar(255) default NULL COMMENT '发送时间',
  `ready_time` varchar(255) default NULL COMMENT '预发送时间',
  `state` tinyint(4) default '0' COMMENT '0:为发送 1 已发送 2 发送失败  ',
  `lostcount` int(11) default NULL COMMENT '失败次数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_smscfg`
--

CREATE TABLE IF NOT EXISTS `wifi_smscfg` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `user` varchar(50) default NULL,
  `password` varchar(50) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(4) default '1' COMMENT '0:停用 1 启用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_tpl`
--

CREATE TABLE IF NOT EXISTS `wifi_tpl` (
  `id` int(11) NOT NULL auto_increment,
  `tpl_name` varchar(255) default NULL,
  `tpl_path` varchar(255) default NULL,
  `group` varchar(10) default NULL,
  `state` tinyint(4) default '1' COMMENT '0：停用 1 使用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_tradead`
--

CREATE TABLE IF NOT EXISTS `wifi_tradead` (
  `id` int(11) NOT NULL auto_increment,
  `shopid` int(11) NOT NULL COMMENT '商户ID',
  `mode` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_treenode`
--

CREATE TABLE IF NOT EXISTS `wifi_treenode` (
  `id` int(11) NOT NULL,
  `title` varchar(50) default NULL COMMENT '名称',
  `g` varchar(50) NOT NULL default '' COMMENT '分组名称',
  `m` varchar(50) NOT NULL default '' COMMENT '模块名称',
  `a` varchar(50) NOT NULL default '' COMMENT 'action 名称',
  `ico` varchar(50) default NULL COMMENT '图标',
  `pid` int(11) default NULL COMMENT '0',
  `level` tinyint(4) default '1' COMMENT '层级 1,2,3',
  `menuflag` tinyint(4) default '1' COMMENT '0: 否 1 是',
  `sort` int(11) default '0' COMMENT '排序',
  `single` tinyint(4) default '1' COMMENT '是否单节点 0:否 1 是',
  `remark` varchar(255) default NULL,
  `status` tinyint(4) default '1' COMMENT '0 停用 1 启用',
  PRIMARY KEY  (`id`,`a`,`m`,`g`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wifi_treenode`
--

INSERT INTO `wifi_treenode` (`id`, `title`, `g`, `m`, `a`, `ico`, `pid`, `level`, `menuflag`, `sort`, `single`, `remark`, `status`) VALUES
(1, 'WIFI管理', 'wifiadmin', 'Index', 'index', NULL, 0, 1, 0, 0, 1, NULL, 1),
(2, '首页', 'wifiadmin', 'index', 'index', 'icon-home', 1, 2, 1, 0, 1, NULL, 1),
(3, '密码修改', 'wifiadmin', 'index', 'pwd', '1', 2, 3, 0, 0, 1, NULL, 1),
(100, '系统管理', 'wifiadmin', 'System', 'index', 'icon-asterisk', 1, 2, 1, 0, 0, NULL, 1),
(101, '角色管理', 'wifiadmin', 'system', 'role', NULL, 100, 2, 1, 0, 1, NULL, 1),
(102, '添加角色', 'wifiadmin', 'system', 'addrole', NULL, 100, 2, 1, 0, 1, NULL, 1),
(103, '编辑角色', 'wifiadmin', 'system', 'editrole', NULL, 100, 3, 0, 0, 1, NULL, 1),
(104, '角色权限', 'wifiadmin', 'system', 'roleaccess', NULL, 100, 3, 0, 0, 1, NULL, 1),
(105, '用户管理', 'wifiadmin', 'system', 'userlist', NULL, 100, 2, 1, 0, 1, NULL, 1),
(106, '添加用户', 'wifiadmin', 'system', 'adduser', NULL, 100, 3, 1, 0, 1, NULL, 1),
(107, '编辑用户', 'wifiadmin', 'system', 'edituser', NULL, 100, 3, 1, 0, 1, NULL, 1),
(200, '网站管理', 'wifiadmin', '', '', 'icon-cogs', 1, 1, 1, 0, 0, NULL, 1),
(201, '网站设置', 'wifiadmin', 'system', 'index', NULL, 200, 3, 1, 0, 1, NULL, 1),
(202, '参数设置', 'wifiadmin', 'System', 'setting', NULL, 200, 1, 1, 0, 1, NULL, 1),
(300, '商户管理', 'wifiadmin', 'Shop', 'index', 'icon-group', 1, 1, 1, 0, 0, NULL, 1),
(301, '商户列表', 'wifiadmin', 'Shop', 'index', NULL, 300, 2, 1, 0, 1, NULL, 1),
(302, '添加商户', 'wifiadmin', 'shop', 'addshop', NULL, 300, 3, 1, 0, 1, NULL, 1),
(303, '编辑商户', 'wifiadmin', 'shop', 'editshop', NULL, 300, 1, 0, 0, 1, NULL, 1),
(400, '代理商管理', 'wifiadmin', 'agent', '', 'icon-user-md', 1, 2, 1, 0, 0, NULL, 1),
(401, '代理商等级', 'wifiadmin', 'agent', 'level', NULL, 400, 3, 1, 0, 1, NULL, 1),
(402, '代理商列表', 'wifiadmin', 'agent', 'index', NULL, 400, 3, 1, 2, 1, NULL, 1),
(403, '添加代理商', 'wifiadmin', 'agent', 'add', NULL, 400, 3, 1, 3, 1, NULL, 1),
(404, '编辑代理商', 'wifiadmin', 'agent', 'edit', NULL, 400, 3, 0, 4, 1, NULL, 1),
(405, '添加等级', 'wifiadmin', 'agent', 'addlevel', NULL, 400, 3, 1, 1, 1, NULL, 1),
(406, '删除代理商', 'wifiadmin', 'agent', 'del', NULL, 400, 3, 0, 5, 1, NULL, 1),
(407, '扣款记录', 'wifiadmin', 'agent', 'paylist', NULL, 400, 3, 1, 7, 1, NULL, 1),
(408, '帐号金额调整', 'wifiadmin', 'agent', 'dopay', NULL, 400, 3, 0, 0, 1, NULL, 1),
(500, '广告管理', 'wifiadmin', 'ad', 'index', 'icon-cloud', 1, 1, 1, 0, 0, NULL, 1),
(501, '广告列表', 'wifiadmin', 'ad', 'index', NULL, 500, 1, 1, 0, 1, NULL, 1),
(502, '编辑广告', 'wifiadmin', 'ad', 'editad', NULL, 500, 3, 0, 0, 1, NULL, 1),
(504, '删除广告', 'wifiadmin', 'ad', 'delad', NULL, 500, 3, 0, 0, 1, NULL, 1),
(505, '广告统计', 'wifiadmin', 'ad', 'rpt', NULL, 500, 1, 1, 0, 1, NULL, 1),
(600, '统计信息', 'wifiadmin', 'report', '', 'icon-bar-chart', 1, 2, 1, 0, 0, NULL, 1),
(601, '注册用户', 'wifiadmin', 'report', 'user', NULL, 600, 3, 1, 0, 1, NULL, 1),
(602, '上网记录', 'wifiadmin', 'report', 'online', '', 600, 3, 1, 0, 1, '', 1),
(603, '用户统计报表', 'wifiadmin', 'report', 'userchart', NULL, 600, 3, 1, 0, 1, NULL, 1),
(604, '上网统计报表', 'wifiadmin', 'report', 'authchart', NULL, 600, 3, 1, 0, 1, NULL, 1),
(605, '在线路由统计', 'wifiadmin', 'report', 'routechart', NULL, 600, 3, 1, 0, 1, NULL, 1),
(700, '信息管理', 'wifiadmin', 'notice', '', 'icon-bullhorn', 1, 1, 1, 0, 0, '', 1),
(701, '系统消息', 'wifiadmin', 'notice', 'index', '', 700, 3, 1, 0, 1, '', 1),
(702, '添加系统消息', 'wifiadmin', 'notice', 'add', '', 700, 3, 1, 2, 1, '', 1),
(703, '删除系统消息', 'wifiadmin', 'notice', 'del', '', 700, 3, 0, 3, 1, '', 1),
(704, '编辑系统消息', 'wifiadmin', 'notice', 'edit', '', 700, 3, 0, 4, 1, '', 1),
(705, '新闻管理', 'wifiadmin', 'news', 'index', '', 700, 3, 1, 4, 1, '', 1),
(706, '添加新闻', 'wifiadmin', 'news', 'add', '', 700, 3, 1, 5, 1, '', 1),
(800, '广告推送', 'wifiadmin', 'pushadv', 'index', 'icon-facetime-video', 1, 1, 1, 3, 0, NULL, 1),
(801, '推送设置', 'wifiadmin', 'pushadv', 'set', NULL, 800, 3, 1, 0, 1, NULL, 1),
(802, '推送广告管理', 'wifiadmin', 'pushadv', 'index', NULL, 800, 3, 1, 0, 1, NULL, 1),
(803, '添加推送广告', 'wifiadmin', 'pushadv', 'add', NULL, 800, 3, 1, 3, 1, NULL, 1),
(804, '编辑推送广告', 'wifiadmin', 'pushadv', 'edit', NULL, 800, 3, 0, 0, 1, NULL, 1),
(805, '删除推送广告', 'wifiadmin', 'pushadv', 'del', NULL, 800, 1, 0, 4, 1, NULL, 1),
(806, '广告推送统计', 'wifiadmin', 'Pushadv', 'rpt', NULL, 800, 3, 1, 5, 1, NULL, 1),
(108, '删除用户', 'wifiadmin', 'system', 'deluser', NULL, 100, 3, 0, 0, 1, NULL, 1),
(109, '删除角色', 'wifiadmin', 'System', 'delrole', NULL, 100, 3, 0, 0, 1, NULL, 1),
(606, '导出用户资料', 'wifiadmin', 'report', 'downuser', NULL, 600, 3, 0, 10, 1, NULL, 1),
(900, '路由器管理', 'wifiadmin', 'route', 'index', 'icon-sitemap', 1, 1, 1, 0, 0, '', 1),
(901, '路由列表', 'wifiadmin', 'route', 'index', '', 900, 3, 1, 0, 1, '', 1),
(307, '删除路由', 'wifiadmin', 'shop', 'delroute', NULL, 300, 3, 0, 0, 1, NULL, 1),
(903, '编辑路由', 'wifiadmin', 'route', 'edit', '', 900, 3, 0, 0, 1, '', 1),
(904, '删除路由', 'wifiadmin', 'route', 'del', '', 900, 3, 0, 0, 1, '', 1),
(304, '路由管理', 'wifiadmin', 'shop', 'routelist', NULL, 300, 1, 0, 0, 1, NULL, 1),
(305, '路由编辑', 'wifiadmin', 'shop', 'editroute', NULL, 300, 3, 0, 0, 1, NULL, 1),
(306, '添加路由', 'wifiadmin', 'shop', 'addroute', NULL, 300, 3, 0, 0, 1, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `wifi_wap`
--

CREATE TABLE IF NOT EXISTS `wifi_wap` (
  `uid` int(11) NOT NULL,
  `home_tpl` int(11) default NULL,
  `home_tpl_path` varchar(255) default NULL,
  `list_tpl` int(11) default NULL,
  `list_tpl_path` varchar(255) default NULL,
  `info_tpl` int(11) default NULL,
  `info_tpl_path` varchar(255) default NULL,
  `state` smallint(6) default NULL,
  `shopname` varchar(255) default NULL,
  `tel` varchar(20) default NULL,
  `point_x` varchar(20) default NULL,
  `point_y` varchar(20) default NULL,
  `pic` varchar(255) default NULL,
  `keyword` varchar(255) default NULL,
  `desc` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `add_time` varchar(50) default NULL,
  `update_time` varchar(50) default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_wapcatelog`
--

CREATE TABLE IF NOT EXISTS `wifi_wapcatelog` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `mode` varchar(20) default 'art' COMMENT '栏目类别',
  `modematch` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `info` varchar(255) default NULL,
  `icopic` varchar(255) default NULL,
  `titlepic` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `sort` int(11) default NULL,
  `tel` varchar(50) default NULL,
  `point_x` varchar(20) default NULL,
  `point_y` varchar(20) default NULL,
  `add_time` varchar(255) default NULL,
  `update_time` varchar(255) default NULL,
  `state` tinyint(2) default '1' COMMENT '0:停用 1 使用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wifi_waptpl`
--

CREATE TABLE IF NOT EXISTS `wifi_waptpl` (
  `id` int(11) NOT NULL auto_increment,
  `group` smallint(6) default NULL COMMENT '0:首页 1 列表 2 图文',
  `title` varchar(255) default NULL,
  `info` varchar(255) default NULL,
  `pic` varchar(255) default NULL,
  `tplpath` varchar(255) default NULL,
  `sort` int(11) default '0',
  `isdefault` smallint(6) default '0' COMMENT '1 默认模板 0 不是默认',
  `state` smallint(6) default '1' COMMENT '0: 停用 1 启用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- 转存表中的数据 `wifi_waptpl`
--

INSERT INTO `wifi_waptpl` (`id`, `group`, `title`, `info`, `pic`, `tplpath`, `sort`, `isdefault`, `state`) VALUES
(2, 1, '默认模板', NULL, 'list1.png', 'list_t1', 0, 1, 1),
(6, 2, '科技模板', NULL, 'news_tech.png', 'info_tech', 1, 0, 1),
(8, 1, '默认模板3', NULL, 'list2.png', 'list_t2', 0, 0, 1),
(9, 0, '默认模板4', NULL, 'cate1.png', 'home_t3', 3, 0, 1),
(11, 0, '酒店主题模板', NULL, 'home_hotel.png', 'home_hotel', 0, 0, 1),
(12, 1, '酒店主题模板', NULL, 'list_hotel.png', 'list_hotel', 0, 0, 1),
(13, 2, '酒店主题模板', NULL, 'info_hotel.png', 'info_hotel', 0, 0, 1),
(15, 1, '房产主题模板', NULL, 'list_house.png', 'list_house', 0, 0, 1),
(16, 2, '房产主题', NULL, 'info_house.png', 'info_house', 0, 0, 1),
(17, 0, '主题模板10', NULL, 'home10.png', 'home_t10', 0, 0, 1),
(19, 0, '酒吧模板', NULL, 'home_s.png', 'home_bar', 0, 0, 1),
(20, 3, '默认模板1', NULL, 'shop_home_t1.png', 'home_t1', 0, 0, 1),
(21, 3, '默认模板2', NULL, NULL, 'index', 0, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
