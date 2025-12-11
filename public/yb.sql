-- --------------------------------------------------------
-- 主机:                           47.120.57.7
-- 服务器版本:                        5.7.44-log - Source distribution
-- 服务器操作系统:                      Linux
-- HeidiSQL 版本:                  12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 导出  表 yunbaolivesql.cmf_admin_log 结构
CREATE TABLE IF NOT EXISTS `cmf_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL COMMENT '管理员ID',
  `admin` varchar(255) NOT NULL COMMENT '管理员',
  `action` text NOT NULL COMMENT '操作内容',
  `ip` bigint(20) NOT NULL COMMENT 'IP地址',
  `addtime` int(11) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=684 DEFAULT CHARSET=utf8mb4 COMMENT='管理员操作日志';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_admin_menu 结构
CREATE TABLE IF NOT EXISTS `cmf_admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '菜单类型;1:有界面可访问菜单,2:无界面可访问菜单,0:只作为菜单',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;1:显示,0:不显示',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `app` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '应用名',
  `controller` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '控制器名',
  `action` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '操作名称',
  `param` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '额外参数',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `controller` (`controller`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=550 DEFAULT CHARSET=utf8mb4 COMMENT='后台菜单表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_admin_post 结构
CREATE TABLE IF NOT EXISTS `cmf_admin_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `admin_term_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 文章 1 页面',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `title_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Classification name',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `content` text NOT NULL COMMENT '内容',
  `content_en` text NOT NULL COMMENT 'Content',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='后台文章表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_admin_term 结构
CREATE TABLE IF NOT EXISTS `cmf_admin_term` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Classification name',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='后台分类';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_advertiser 结构
CREATE TABLE IF NOT EXISTS `cmf_advertiser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `certification_entity` varchar(255) NOT NULL DEFAULT '' COMMENT '认证主体',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `certification_explain` varchar(255) NOT NULL DEFAULT '' COMMENT '认证说明',
  `qualification_picture_one` varchar(255) NOT NULL DEFAULT '' COMMENT '资质图片1',
  `qualification_picture_two` varchar(255) NOT NULL DEFAULT '' COMMENT '资质图片2',
  `is_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 拒绝 2 通过 0 待审核',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `handlingtime` int(11) NOT NULL DEFAULT '0' COMMENT '处理时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='广告主申请表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_advert_comment 结构
CREATE TABLE IF NOT EXISTS `cmf_advert_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `advertid` int(11) NOT NULL DEFAULT '0' COMMENT '广告ID',
  `audio_url` varchar(255) NOT NULL DEFAULT '' COMMENT '声音',
  `number_likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='广告评论表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_agent 结构
CREATE TABLE IF NOT EXISTS `cmf_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `one_uid` int(11) NOT NULL DEFAULT '0' COMMENT '上级用户id',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='上下级关联表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_agent_code 结构
CREATE TABLE IF NOT EXISTS `cmf_agent_code` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '邀请码',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户邀请码记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_agent_profit 结构
CREATE TABLE IF NOT EXISTS `cmf_agent_profit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `one_profit` decimal(65,2) DEFAULT '0.00' COMMENT '一级收益',
  `two_profit` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='邀请成员总收益记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_agent_profit_recode 结构
CREATE TABLE IF NOT EXISTS `cmf_agent_profit_recode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `total` int(11) DEFAULT '0' COMMENT '消费总数',
  `one_uid` int(11) DEFAULT '0' COMMENT '一级ID',
  `one_profit` decimal(65,2) DEFAULT '0.00' COMMENT '一级收益',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  `two_uid` int(10) NOT NULL DEFAULT '0',
  `two_profit` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='邀请成员收益记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_apply_goods_class 结构
CREATE TABLE IF NOT EXISTS `cmf_apply_goods_class` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goods_classid` varchar(255) NOT NULL COMMENT '商品一级分类ID',
  `reason` text COMMENT '审核说明',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `uptime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 处理中 1 成功 2 失败',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品一级分类申请表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_asset 结构
CREATE TABLE IF NOT EXISTS `cmf_asset` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `file_size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小,单位B',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:可用,0:不可用',
  `download_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `file_key` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件惟一码',
  `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `file_path` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件路径,相对于upload目录,可以为url',
  `file_md5` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件md5值',
  `file_sha1` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `suffix` varchar(10) NOT NULL DEFAULT '' COMMENT '文件后缀名,不包括点',
  `more` text COMMENT '其它详细信息,JSON格式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='资源表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_author_center 结构
CREATE TABLE IF NOT EXISTS `cmf_author_center` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
  `title_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Activity Title',
  `classifyid` int(11) NOT NULL DEFAULT '0' COMMENT '所属分类',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '活动封面',
  `active_start_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `active_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `submission_start_time` int(11) NOT NULL DEFAULT '0' COMMENT '投稿开始时间',
  `submission_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '投稿结束时间',
  `activity_play` varchar(255) NOT NULL DEFAULT '' COMMENT '活动玩法',
  `related_topics` varchar(255) NOT NULL DEFAULT '' COMMENT '关联话题',
  `activity_reward` varchar(255) NOT NULL DEFAULT '' COMMENT '活动奖励',
  `is_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0 不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='创作中心表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_author_center_classify 结构
CREATE TABLE IF NOT EXISTS `cmf_author_center_classify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '创作者分类名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Creator Category Name',
  `is_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0 不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='创作中心分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_author_center_collection 结构
CREATE TABLE IF NOT EXISTS `cmf_author_center_collection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `author_center_id` int(12) NOT NULL DEFAULT '0' COMMENT '创作者活动ID',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '收藏状态 1收藏 0 取消收藏',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='创作中心收藏表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_auth_access 结构
CREATE TABLE IF NOT EXISTS `cmf_auth_access` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类,请加应用前缀,如admin_',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2691 DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_auth_rule 结构
CREATE TABLE IF NOT EXISTS `cmf_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `app` varchar(40) NOT NULL DEFAULT '' COMMENT '规则所属app',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(100) NOT NULL DEFAULT '' COMMENT '额外url参数',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则描述',
  `condition` varchar(200) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `module` (`app`,`status`,`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=550 DEFAULT CHARSET=utf8mb4 COMMENT='权限规则表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_backpack 结构
CREATE TABLE IF NOT EXISTS `cmf_backpack` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `giftid` int(11) NOT NULL DEFAULT '0' COMMENT '礼物ID',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='背包表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_balance_charge_admin 结构
CREATE TABLE IF NOT EXISTS `cmf_balance_charge_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `balance` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='管理员余额充值表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_car 结构
CREATE TABLE IF NOT EXISTS `cmf_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `name_en` varchar(20) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `swf` varchar(255) NOT NULL DEFAULT '' COMMENT '动画链接',
  `swftime` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '动画时长',
  `needcoin` int(20) NOT NULL DEFAULT '0' COMMENT '价格',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分价格',
  `list_order` int(10) NOT NULL DEFAULT '9999' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `words` varchar(255) NOT NULL DEFAULT '' COMMENT '进场话术',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='坐骑表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_car_user 结构
CREATE TABLE IF NOT EXISTS `cmf_car_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `carid` int(11) NOT NULL DEFAULT '0' COMMENT '坐骑ID',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '到期时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='用户关联坐骑表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_cash_account 结构
CREATE TABLE IF NOT EXISTS `cmf_cash_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，1表示支付宝，2表示微信，3表示银行卡',
  `account_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '账号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `id_uid` (`id`,`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='现金提现账号表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_cash_record 结构
CREATE TABLE IF NOT EXISTS `cmf_cash_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `cash_money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户提现金额',
  `cash_take` int(11) NOT NULL DEFAULT '0' COMMENT '平台抽成(%)',
  `votes` int(20) NOT NULL DEFAULT '0' COMMENT '提现映票数',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中，1审核通过，2审核拒绝',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '账号类型',
  `account_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '帐号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `cash_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现类型 0 普通提现 1 视频红包提现',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='现金提现记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_charge_admin 结构
CREATE TABLE IF NOT EXISTS `cmf_charge_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `coin` int(20) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='管理员充值钻石表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_charge_rules 结构
CREATE TABLE IF NOT EXISTS `cmf_charge_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `name_en` varchar(50) NOT NULL DEFAULT '' COMMENT 'Name',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `coin_ios` int(11) NOT NULL DEFAULT '0' COMMENT '苹果钻石数',
  `coin_paypal` int(11) NOT NULL DEFAULT '0' COMMENT 'PayPal支付获得',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '安卓金额',
  `product_id` varchar(50) NOT NULL DEFAULT '' COMMENT '苹果项目ID',
  `give` int(11) NOT NULL DEFAULT '0' COMMENT '赠送钻石数',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='钻石充值规则表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_charge_user 结构
CREATE TABLE IF NOT EXISTS `cmf_charge_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `coin_give` int(11) NOT NULL DEFAULT '0' COMMENT '赠送钻石数',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '商家订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方平台订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付类型',
  `ambient` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付环境',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COMMENT='钻石充值用户记录';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `thumb` text COMMENT '图片地址：多张图片用分号隔开',
  `video_thumb` varchar(255) DEFAULT '' COMMENT '视频封面',
  `href` varchar(255) DEFAULT '' COMMENT '视频地址',
  `voice` varchar(255) DEFAULT '' COMMENT '语音链接',
  `length` int(11) DEFAULT '0' COMMENT '语音时长',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '动态类型：0：纯文字；1：文字+图片；2：文字+视频；3：文字+语音',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1删除（下架）0不下架',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '视频状态 0未审核 1通过 2拒绝',
  `uptime` int(12) DEFAULT '0' COMMENT '审核不通过时间（第一次审核不通过时更改此值，用于判断是否发送极光IM）',
  `xiajia_reason` varchar(255) DEFAULT '' COMMENT '下架原因',
  `lat` varchar(255) DEFAULT '' COMMENT '维度',
  `lng` varchar(255) DEFAULT '' COMMENT '经度',
  `city` varchar(255) DEFAULT '' COMMENT '城市',
  `address` varchar(255) DEFAULT '' COMMENT '详细地理位置',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `fail_reason` varchar(255) DEFAULT '' COMMENT '审核原因',
  `show_val` int(12) NOT NULL DEFAULT '0' COMMENT '曝光值',
  `recommend_val` int(20) DEFAULT '0' COMMENT '推荐值',
  `labelid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='动态表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic_comments 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '评论用户ID',
  `touid` int(10) NOT NULL DEFAULT '0' COMMENT '被评论用户ID',
  `dynamicid` int(10) NOT NULL DEFAULT '0' COMMENT '动态ID',
  `commentid` int(10) NOT NULL DEFAULT '0' COMMENT '评论iD',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '上级评论ID',
  `content` text NOT NULL COMMENT '评论内容',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，0文字1语音',
  `voice` varchar(255) NOT NULL DEFAULT '' COMMENT '语音链接',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '时长',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='动态评论表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic_comments_like 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic_comments_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '点赞用户ID',
  `commentid` int(10) NOT NULL DEFAULT '0' COMMENT '评论ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `touid` int(12) NOT NULL DEFAULT '0' COMMENT '被喜欢的评论者id',
  `dynamicid` int(12) NOT NULL DEFAULT '0' COMMENT '评论所属动态id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='动态评论点赞表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic_label 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name',
  `orderno` int(11) NOT NULL DEFAULT '10000' COMMENT '序号',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT 'Describe',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐 0否  1是',
  `use_nums` int(11) NOT NULL DEFAULT '0' COMMENT '使用次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='动态标签表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic_like 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic_like` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '点赞用户',
  `dynamicid` int(10) NOT NULL DEFAULT '0' COMMENT '动态id',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '点赞时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '动态是否被删除或被拒绝 0被删除或被拒绝 1 正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='动态点赞表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic_report 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '举报用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '被举报用户ID',
  `dynamicid` int(11) NOT NULL DEFAULT '0' COMMENT '动态ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '举报内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0处理中 1已处理  2审核失败',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `dynamic_type` int(10) NOT NULL DEFAULT '0' COMMENT '动态类型：0：纯文字；1：文字+图片‘；2：视频+图片；3：语音+图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='动态举报表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_dynamic_report_classify 结构
CREATE TABLE IF NOT EXISTS `cmf_dynamic_report_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(10) NOT NULL DEFAULT '10000' COMMENT '排序',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '举报类型名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT '举报类型英文名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='动态举报分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_family 结构
CREATE TABLE IF NOT EXISTS `cmf_family` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '家族名称',
  `badge` varchar(255) NOT NULL DEFAULT '' COMMENT '家族图标',
  `apply_pos` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `apply_side` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证背面',
  `briefing` text COMMENT '简介',
  `carded` varchar(255) NOT NULL DEFAULT '' COMMENT '证件号',
  `fullname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申请状态 0未审核 1 审核失败 2 审核通过 3',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '失败原因',
  `disable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用',
  `divide_family` int(11) NOT NULL DEFAULT '0' COMMENT '分成比例',
  `istip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要通知',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='家族表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_family_profit 结构
CREATE TABLE IF NOT EXISTS `cmf_family_profit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '主播ID',
  `familyid` int(11) NOT NULL DEFAULT '0' COMMENT '家族ID',
  `time` varchar(50) NOT NULL DEFAULT '' COMMENT '格式化日期',
  `profit_anthor` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '主播收益',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '总收益',
  `profit` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '家族收益',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='家族收益表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_family_user 结构
CREATE TABLE IF NOT EXISTS `cmf_family_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `familyid` int(11) NOT NULL DEFAULT '0' COMMENT '家族ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '原因',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `signout` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否退出',
  `istip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核后是否需要通知',
  `signout_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '踢出或拒绝理由',
  `signout_istip` tinyint(4) NOT NULL DEFAULT '0' COMMENT '踢出或拒绝是否需要通知',
  `divide_family` int(11) NOT NULL DEFAULT '-1' COMMENT '家族分成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='家族用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_family_user_divide_apply 结构
CREATE TABLE IF NOT EXISTS `cmf_family_user_divide_apply` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
  `familyid` int(11) NOT NULL DEFAULT '0' COMMENT '家族id',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理状态 0 等待审核 1 同意 -1 拒绝',
  `divide` int(11) NOT NULL DEFAULT '0' COMMENT '家族分成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='家族用户申请表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_feedback 结构
CREATE TABLE IF NOT EXISTS `cmf_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `version` varchar(50) NOT NULL DEFAULT '' COMMENT '系统版本号',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '设备',
  `phone` varchar(50) NOT NULL DEFAULT '' COMMENT '联系方式',
  `content` text NOT NULL COMMENT '内容',
  `addtime` int(11) NOT NULL COMMENT '提交时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='反馈表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_game 结构
CREATE TABLE IF NOT EXISTS `cmf_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` tinyint(1) DEFAULT '0' COMMENT '游戏名称',
  `liveuid` int(11) DEFAULT '0' COMMENT '主播ID',
  `bankerid` int(11) DEFAULT '0' COMMENT '庄家ID，0表示平台',
  `stream` varchar(255) DEFAULT '' COMMENT '直播流名',
  `starttime` int(11) DEFAULT '0' COMMENT '本次游戏开始时间',
  `endtime` int(11) DEFAULT '0' COMMENT '游戏结束时间',
  `result` varchar(255) DEFAULT '0' COMMENT '本轮游戏结果',
  `state` tinyint(1) DEFAULT '0' COMMENT '当前游戏状态（0是进行中，1是正常结束，2是 主播关闭 3意外结束）',
  `banker_profit` int(11) DEFAULT '0' COMMENT '庄家收益',
  `banker_card` varchar(50) DEFAULT '' COMMENT '庄家牌型',
  `isintervene` tinyint(1) DEFAULT '0' COMMENT '是否进行系统干预',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='游戏表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_gamerecord 结构
CREATE TABLE IF NOT EXISTS `cmf_gamerecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` tinyint(1) DEFAULT '0' COMMENT '游戏类型',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(11) DEFAULT '0' COMMENT '主播ID',
  `gameid` int(11) DEFAULT '0' COMMENT '游戏ID',
  `coin_1` int(11) DEFAULT '0' COMMENT '1位置下注金额',
  `coin_2` int(11) DEFAULT '0' COMMENT '2位置下注金额',
  `coin_3` int(11) DEFAULT '0' COMMENT '3位置下注金额',
  `coin_4` int(11) DEFAULT '0' COMMENT '4位置下注金额',
  `coin_5` int(11) DEFAULT '0' COMMENT '5位置下注金额',
  `coin_6` int(11) DEFAULT '0' COMMENT '6位置下注金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '处理状态 0 未处理 1 已处理',
  `addtime` int(11) DEFAULT '0' COMMENT '下注时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='游戏记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_getcode_limit_ip 结构
CREATE TABLE IF NOT EXISTS `cmf_getcode_limit_ip` (
  `ip` bigint(20) NOT NULL COMMENT 'ip地址',
  `date` varchar(30) NOT NULL DEFAULT '' COMMENT '时间',
  `times` tinyint(4) NOT NULL DEFAULT '0' COMMENT '次数',
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='获取验证码ip记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_gift 结构
CREATE TABLE IF NOT EXISTS `cmf_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mark` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标识，0普通，1热门，2守护',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型,0普通礼物，1豪华礼物，2贴纸礼物，3手绘礼物',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `giftname` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `giftname_en` varchar(50) NOT NULL DEFAULT '' COMMENT 'Name',
  `needcoin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `gifticon` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `list_order` int(3) NOT NULL DEFAULT '9999' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `swftype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '动画类型，0gif,1svga',
  `swf` varchar(255) NOT NULL DEFAULT '' COMMENT '动画链接',
  `swftime` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '动画时长',
  `isplatgift` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否全站礼物：0：否；1：是',
  `sticker_id` int(11) NOT NULL DEFAULT '0' COMMENT '贴纸ID',
  `vote_ticket` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主播收益',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COMMENT='礼物表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_gift_luck_rate 结构
CREATE TABLE IF NOT EXISTS `cmf_gift_luck_rate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `giftid` int(11) NOT NULL DEFAULT '0' COMMENT '礼物ID',
  `nums` int(10) unsigned NOT NULL COMMENT '数量',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '倍数',
  `rate` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '中奖概率',
  `isall` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否全站，0否1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='礼物中奖概率表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_gift_sort 结构
CREATE TABLE IF NOT EXISTS `cmf_gift_sort` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(20) NOT NULL DEFAULT '' COMMENT '分类名',
  `orderno` int(3) NOT NULL DEFAULT '0' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='礼物分类排序表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_guard 结构
CREATE TABLE IF NOT EXISTS `cmf_guard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '守护名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '守护类型，1普通2尊贵',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `length_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '时长类型，0天，1月，2年',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '时长',
  `length_time` int(11) NOT NULL DEFAULT '0' COMMENT '时长秒数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='守护表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_guard_user 结构
CREATE TABLE IF NOT EXISTS `cmf_guard_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(11) NOT NULL DEFAULT '0' COMMENT '主播ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '守护类型,1普通守护，2尊贵守护',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '到期时间',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `liveuid_index` (`liveuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='守护主播表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_guide 结构
CREATE TABLE IF NOT EXISTS `cmf_guide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片/视频链接',
  `href` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转链接',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `list_order` int(11) NOT NULL DEFAULT '10000' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='引导页表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_hook 结构
CREATE TABLE IF NOT EXISTS `cmf_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '钩子类型(1:系统钩子;2:应用钩子;3:模板钩子;4:后台模板钩子)',
  `once` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否只允许一个插件运行(0:多个;1:一个)',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `hook` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子',
  `app` varchar(15) NOT NULL DEFAULT '' COMMENT '应用名(只有应用钩子才用)',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COMMENT='系统钩子表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_hook_plugin 结构
CREATE TABLE IF NOT EXISTS `cmf_hook_plugin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `hook` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名',
  `plugin` varchar(50) NOT NULL DEFAULT '' COMMENT '插件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='系统钩子插件表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_jackpot 结构
CREATE TABLE IF NOT EXISTS `cmf_jackpot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `total` bigint(20) NOT NULL DEFAULT '0' COMMENT '奖池金额',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '奖池等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='奖池表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_jackpot_level 结构
CREATE TABLE IF NOT EXISTS `cmf_jackpot_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `levelid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `level_up` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '经验下限',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='奖池等级表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_jackpot_rate 结构
CREATE TABLE IF NOT EXISTS `cmf_jackpot_rate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `giftid` int(11) NOT NULL DEFAULT '0' COMMENT '礼物ID',
  `nums` int(10) unsigned NOT NULL COMMENT '数量',
  `rate_jackpot` text COLLATE utf8mb4_unicode_ci COMMENT '奖池概率',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='奖池礼物表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_label 结构
CREATE TABLE IF NOT EXISTS `cmf_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '标签名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'label',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  `colour` varchar(255) NOT NULL DEFAULT '' COMMENT '颜色',
  `colour2` varchar(255) NOT NULL DEFAULT '' COMMENT '尾色',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='标签表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_label_user 结构
CREATE TABLE IF NOT EXISTS `cmf_label_user` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '对方ID',
  `label` varchar(255) NOT NULL DEFAULT '' COMMENT '选择的标签',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  KEY `uid_touid_index` (`uid`,`touid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `touid` (`touid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户标签表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_language 结构
CREATE TABLE IF NOT EXISTS `cmf_language` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language_title` varchar(255) NOT NULL DEFAULT '' COMMENT '国家标题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 隐藏 显示 ',
  `code` text COMMENT '语言代码',
  `list_order` int(11) DEFAULT '9999' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='语言表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_level 结构
CREATE TABLE IF NOT EXISTS `cmf_level` (
  `levelid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `levelname` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
  `level_up` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '经验上限',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '标识',
  `colour` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称颜色',
  `thumb_mark` varchar(255) NOT NULL DEFAULT '' COMMENT '头像角标',
  `bg` varchar(255) NOT NULL DEFAULT '' COMMENT '背景',
  PRIMARY KEY (`id`,`levelid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COMMENT='用户等级表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_level_anchor 结构
CREATE TABLE IF NOT EXISTS `cmf_level_anchor` (
  `levelid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `levelname` varchar(50) DEFAULT '' COMMENT '等级名称',
  `level_up` int(20) unsigned DEFAULT '0' COMMENT '等级上限',
  `addtime` int(11) DEFAULT '0' COMMENT '添加时间',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '标识',
  `thumb_mark` varchar(255) NOT NULL DEFAULT '' COMMENT '头像角标',
  `bg` varchar(255) NOT NULL DEFAULT '' COMMENT '背景',
  PRIMARY KEY (`id`,`levelid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='等级名称表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_liang 结构
CREATE TABLE IF NOT EXISTS `cmf_liang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '靓号',
  `length` int(11) NOT NULL DEFAULT '0' COMMENT '长度',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分价格',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '购买用户ID',
  `buytime` int(11) NOT NULL DEFAULT '0' COMMENT '购买时间',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '靓号销售状态',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '启用状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='用户靓号表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_link 结构
CREATE TABLE IF NOT EXISTS `cmf_link` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:显示;0:不显示',
  `rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '友情链接地址',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接名称',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '友情链接图标',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '友情链接打开方式',
  `rel` varchar(50) NOT NULL DEFAULT '' COMMENT '链接与网站的关系',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='友情链接表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live 结构
CREATE TABLE IF NOT EXISTS `cmf_live` (
  `uid` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `showid` int(12) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `islive` int(1) NOT NULL DEFAULT '0' COMMENT '直播状态',
  `starttime` int(12) NOT NULL DEFAULT '0' COMMENT '开播时间',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `stream` varchar(255) NOT NULL DEFAULT '' COMMENT '流名',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `pull` varchar(255) NOT NULL DEFAULT '' COMMENT '播流地址',
  `lng` varchar(255) NOT NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(255) NOT NULL DEFAULT '' COMMENT '维度',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播类型',
  `type_val` varchar(255) NOT NULL DEFAULT '' COMMENT '类型值',
  `isvideo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否假视频',
  `wy_cid` varchar(255) NOT NULL DEFAULT '' COMMENT '网易房间ID(当不使用网易是默认为空)',
  `goodnum` varchar(255) NOT NULL DEFAULT '0' COMMENT '靓号',
  `anyway` tinyint(1) NOT NULL DEFAULT '1' COMMENT '横竖屏，0表示竖屏，1表示横屏',
  `liveclassid` int(11) NOT NULL DEFAULT '0' COMMENT '直播分类ID',
  `hotvotes` int(11) NOT NULL DEFAULT '0' COMMENT '热门礼物总额',
  `pkuid` int(11) NOT NULL DEFAULT '0' COMMENT 'PK对方ID',
  `pkstream` varchar(255) NOT NULL DEFAULT '' COMMENT 'pk对方的流名',
  `ismic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '连麦开关，0是关，1是开',
  `ishot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热门',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `deviceinfo` varchar(255) NOT NULL DEFAULT '' COMMENT '设备信息',
  `isshop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启店铺',
  `game_action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '游戏类型',
  `banker_coin` bigint(20) DEFAULT '0' COMMENT '庄家余额',
  `isoff` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否断流，0否1是',
  `offtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '断流时间',
  `recommend_time` int(1) NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否付费上热门 0 否 1 是',
  PRIMARY KEY (`uid`),
  KEY `index_islive_starttime` (`islive`,`starttime`) USING BTREE,
  KEY `index_uid_stream` (`uid`,`stream`(191)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='直播表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_ban 结构
CREATE TABLE IF NOT EXISTS `cmf_live_ban` (
  `liveuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主播ID',
  `superid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '超管ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`liveuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='禁播表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_class 结构
CREATE TABLE IF NOT EXISTS `cmf_live_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `des` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT 'Describe',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='直播分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_kick 结构
CREATE TABLE IF NOT EXISTS `cmf_live_kick` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主播ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `actionid` int(11) NOT NULL DEFAULT '0' COMMENT '操作用户ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='直播踢出用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_manager 结构
CREATE TABLE IF NOT EXISTS `cmf_live_manager` (
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(12) NOT NULL DEFAULT '0' COMMENT '主播ID',
  KEY `uid_touid_index` (`liveuid`,`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='直播管理员用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_popular 结构
CREATE TABLE IF NOT EXISTS `cmf_live_popular` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `showid` int(11) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `view_counts` int(11) NOT NULL DEFAULT '0' COMMENT '预计曝光量',
  `view_people_counts` varchar(255) NOT NULL DEFAULT '' COMMENT '预计带来的观众数',
  `price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `return_price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '返回金额',
  `actual_view_counts` int(11) NOT NULL DEFAULT '0' COMMENT '实际曝光量',
  `livetime` int(11) NOT NULL DEFAULT '0' COMMENT '直播时间',
  `liveendtime` int(11) NOT NULL DEFAULT '0' COMMENT '直播结束时间',
  `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式 0 余额 1 支付宝',
  `is_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 未支付 1 已支付',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '完成状态 0 未完成 1 已完成',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='直播上热门表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_popular_rule 结构
CREATE TABLE IF NOT EXISTS `cmf_live_popular_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `coin` varchar(255) NOT NULL DEFAULT '' COMMENT '钻石',
  `list_order` int(10) NOT NULL DEFAULT '9999' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='直播上热门规则表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_record 结构
CREATE TABLE IF NOT EXISTS `cmf_live_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `showid` int(11) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '关播时人数',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `stream` varchar(255) NOT NULL DEFAULT '' COMMENT '流名',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `lng` varchar(255) NOT NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(255) NOT NULL DEFAULT '' COMMENT '纬度',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播类型',
  `type_val` varchar(255) NOT NULL DEFAULT '' COMMENT '类型值',
  `votes` varchar(255) NOT NULL DEFAULT '0' COMMENT '本次直播收益',
  `time` varchar(255) NOT NULL DEFAULT '' COMMENT '格式日期',
  `liveclassid` int(11) NOT NULL DEFAULT '0' COMMENT '直播分类ID',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '回放地址',
  PRIMARY KEY (`id`),
  KEY `index_uid_start` (`uid`,`starttime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COMMENT='直播记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_live_shut 结构
CREATE TABLE IF NOT EXISTS `cmf_live_shut` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主播ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `showid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '禁言类型，0永久',
  `actionid` int(11) NOT NULL DEFAULT '0' COMMENT '操作者ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='直播禁言用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_loginbonus 结构
CREATE TABLE IF NOT EXISTS `cmf_loginbonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` int(10) NOT NULL DEFAULT '0' COMMENT '登录天数',
  `coin` int(10) NOT NULL DEFAULT '0' COMMENT '登录奖励',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='登录记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_music 结构
CREATE TABLE IF NOT EXISTS `cmf_music` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '音乐名称',
  `author` varchar(255) NOT NULL DEFAULT '' COMMENT '演唱者',
  `uploader` int(255) NOT NULL DEFAULT '0' COMMENT '上传者ID',
  `upload_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '上传类型  1管理员上传 2 用户上传',
  `img_url` varchar(255) NOT NULL DEFAULT '' COMMENT '封面地址',
  `length` varchar(255) NOT NULL DEFAULT '' COMMENT '音乐长度',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件地址',
  `use_nums` int(12) NOT NULL DEFAULT '0' COMMENT '被使用次数',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删除 0否 1是',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `classify_id` int(12) NOT NULL DEFAULT '0' COMMENT '音乐分类ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='音乐表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_music_classify 结构
CREATE TABLE IF NOT EXISTS `cmf_music_classify` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `title_en` varchar(255) NOT NULL DEFAULT '' COMMENT '分类英文名称',
  `list_order` int(12) NOT NULL DEFAULT '9999' COMMENT '排序号',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `img_url` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图标地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='音乐分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_music_collection 结构
CREATE TABLE IF NOT EXISTS `cmf_music_collection` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `music_id` int(12) NOT NULL DEFAULT '0' COMMENT '音乐id',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '音乐收藏状态 1收藏 0 取消收藏',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='音乐收藏表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_official 结构
CREATE TABLE IF NOT EXISTS `cmf_official` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `introduction` text COMMENT '简介',
  `content` text COMMENT '内容',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 1 普通类型 2 外部链接',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `push_user` varchar(255) NOT NULL DEFAULT '' COMMENT '推送者',
  `push_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '推送IP',
  `is_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推送 0 否 1 是',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `pushtime` int(11) NOT NULL DEFAULT '0' COMMENT '推送时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='官方通知表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_option 结构
CREATE TABLE IF NOT EXISTS `cmf_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `autoload` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否自动加载;1:自动加载;0:不自动加载',
  `option_name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置名',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '配置值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='全站配置表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_paidprogram 结构
CREATE TABLE IF NOT EXISTS `cmf_paidprogram` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `classid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容简介',
  `personal_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '个人介绍',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '付费内容价格',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文件类型 0 单视频 1 多视频',
  `videos` text NOT NULL COMMENT '视频json串',
  `sale_nums` bigint(20) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核通过 -1 拒绝 0 审核中 1 通过',
  `evaluate_nums` bigint(20) NOT NULL DEFAULT '0' COMMENT '评价总人数',
  `evaluate_total` bigint(20) NOT NULL DEFAULT '0' COMMENT '评价总分',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `id_uid` (`id`,`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='付费项目表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_paidprogram_apply 结构
CREATE TABLE IF NOT EXISTS `cmf_paidprogram_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态 -1 拒绝 0 审核中 1 通过',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `percent` int(11) NOT NULL DEFAULT '0' COMMENT '抽水比例',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='付费项目申请表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_paidprogram_class 结构
CREATE TABLE IF NOT EXISTS `cmf_paidprogram_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT '英文分类名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='付费项目分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_paidprogram_comment 结构
CREATE TABLE IF NOT EXISTS `cmf_paidprogram_comment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` bigint(20) NOT NULL DEFAULT '0' COMMENT '项目发布者ID',
  `object_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '付费项目ID',
  `grade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评价等级',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='付费项目评论表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_paidprogram_order 结构
CREATE TABLE IF NOT EXISTS `cmf_paidprogram_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` bigint(20) NOT NULL DEFAULT '0' COMMENT '付费项目发布者ID',
  `object_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '付费项目ID',
  `type` tinyint(1) NOT NULL COMMENT '支付方式 1 支付宝 2 微信 3 余额',
  `status` tinyint(1) NOT NULL COMMENT '订单状态 0 未支付 1 已支付',
  `orderno` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '三方订单编号',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0 否 1 是（用于删除付费项目）',
  PRIMARY KEY (`id`),
  KEY `uid_objectid_status` (`uid`,`object_id`,`status`) USING BTREE,
  KEY `uid_status` (`uid`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='付费项目订单表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_plugin 结构
CREATE TABLE IF NOT EXISTS `cmf_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '插件类型;1:网站;8:微信',
  `has_admin` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台管理,0:没有;1:有',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:开启;0:禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '插件标识名,英文字母(惟一)',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '插件名称',
  `demo_url` varchar(50) NOT NULL DEFAULT '' COMMENT '演示地址，带协议',
  `hooks` varchar(255) NOT NULL DEFAULT '' COMMENT '实现的钩子;以“,”分隔',
  `author` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '插件作者',
  `author_url` varchar(50) NOT NULL DEFAULT '' COMMENT '作者网站链接',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '插件版本号',
  `description` varchar(255) NOT NULL COMMENT '插件描述',
  `config` text COMMENT '插件配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='插件表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_popular 结构
CREATE TABLE IF NOT EXISTS `cmf_popular` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `release_uid` int(11) NOT NULL DEFAULT '0' COMMENT '发布者用户ID',
  `release_user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '发布者用户名称',
  `videoid` int(11) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `return_price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '返回金额',
  `duration` int(11) NOT NULL DEFAULT '0' COMMENT '时长(小时)',
  `view_counts` int(11) NOT NULL DEFAULT '0' COMMENT '播放量',
  `actual_view_counts` int(11) NOT NULL DEFAULT '0' COMMENT '实际播放量',
  `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式 0 余额 1 支付宝',
  `is_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 未支付 1 已支付',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '完成状态 0 未完成 1 已完成',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='视频上热门表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_portal_category 结构
CREATE TABLE IF NOT EXISTS `cmf_portal_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分类父id',
  `post_count` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分类文章数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:发布,0:不发布',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '分类层级关系路径',
  `seo_title` varchar(100) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `list_tpl` varchar(50) NOT NULL DEFAULT '' COMMENT '分类列表模板',
  `one_tpl` varchar(50) NOT NULL DEFAULT '' COMMENT '分类文章页模板',
  `more` text COMMENT '扩展属性',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='portal应用 文章分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_portal_category_post 结构
CREATE TABLE IF NOT EXISTS `cmf_portal_category_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文章id',
  `category_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:发布;0:不发布',
  PRIMARY KEY (`id`),
  KEY `term_taxonomy_id` (`category_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='portal应用 分类文章对应表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_portal_post 结构
CREATE TABLE IF NOT EXISTS `cmf_portal_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `post_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型,1:文章;2:页面',
  `post_format` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '内容格式;1:html;2:md',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发表者用户id',
  `post_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:已发布;0:未发布;',
  `comment_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '评论状态;1:允许;0:不允许',
  `is_top` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶;1:置顶;0:不置顶',
  `recommended` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐;1:推荐;0:不推荐',
  `post_hits` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '查看数',
  `post_favorites` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `post_like` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `comment_count` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `published_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `post_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'post标题',
  `post_keywords` varchar(150) NOT NULL DEFAULT '' COMMENT 'seo keywords',
  `post_excerpt` varchar(500) NOT NULL DEFAULT '' COMMENT 'post摘要',
  `post_source` varchar(150) NOT NULL DEFAULT '' COMMENT '转载文章的来源',
  `thumbnail` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `post_content` longtext COMMENT '文章内容',
  `post_content_filtered` text COMMENT '处理过的文章内容',
  `more` text COMMENT '扩展属性,如缩略图;格式为json',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '页面类型，0单页面，2关于我们',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`create_time`,`id`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COMMENT='portal应用 文章表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_portal_tag 结构
CREATE TABLE IF NOT EXISTS `cmf_portal_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:发布,0:不发布',
  `recommended` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐;1:推荐;0:不推荐',
  `post_count` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '标签文章数',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标签名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='portal应用 文章标签表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_portal_tag_post 结构
CREATE TABLE IF NOT EXISTS `cmf_portal_tag_post` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '标签 id',
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文章 id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:发布;0:不发布',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='portal应用 标签文章对应表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_pushrecord 结构
CREATE TABLE IF NOT EXISTS `cmf_pushrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `touid` text NOT NULL COMMENT '推送对象',
  `content` text NOT NULL COMMENT '推送内容',
  `adminid` int(11) NOT NULL COMMENT '管理员ID',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员账号',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息类型 0 后台手动发布的系统消息 1 商品消息',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未读 1 已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COMMENT='推送记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_recycle_bin 结构
CREATE TABLE IF NOT EXISTS `cmf_recycle_bin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT '0' COMMENT '删除内容 id',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `table_name` varchar(60) DEFAULT '' COMMENT '删除内容所在表名',
  `name` varchar(255) DEFAULT '' COMMENT '删除内容名称',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT=' 回收站';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_red 结构
CREATE TABLE IF NOT EXISTS `cmf_red` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `showid` int(11) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` int(11) NOT NULL DEFAULT '0' COMMENT '主播ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '红包类型，0平均，1手气',
  `type_grant` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发放类型，0立即，1延迟',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `des` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `effecttime` int(11) NOT NULL DEFAULT '0' COMMENT '生效时间',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0抢中，1抢完',
  `coin_rob` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `nums_rob` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`),
  KEY `liveuid_showid` (`showid`,`liveuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='红包表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_red_record 结构
CREATE TABLE IF NOT EXISTS `cmf_red_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `redid` int(11) NOT NULL DEFAULT '0' COMMENT '红包ID',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `redid` (`redid`) USING BTREE COMMENT '红包ID索引'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='红包记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_report 结构
CREATE TABLE IF NOT EXISTS `cmf_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '对方ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='用户举报表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_report_classify 结构
CREATE TABLE IF NOT EXISTS `cmf_report_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(10) NOT NULL DEFAULT '9999' COMMENT '排序',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '举报类型名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Classification name',
  `is_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 不显示 1 显示',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='用户举报分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_report_user 结构
CREATE TABLE IF NOT EXISTS `cmf_report_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '对方ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '理由',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `classifyid` int(11) NOT NULL DEFAULT '0' COMMENT '类别ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='举报用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_report_user_classify 结构
CREATE TABLE IF NOT EXISTS `cmf_report_user_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(10) NOT NULL DEFAULT '9999' COMMENT '排序',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '举报类型名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'category title',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='举报用户分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_role 结构
CREATE TABLE IF NOT EXISTS `cmf_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父角色ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;0:禁用;1:正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `list_order` float NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_role_user 结构
CREATE TABLE IF NOT EXISTS `cmf_role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色 id',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_route 结构
CREATE TABLE IF NOT EXISTS `cmf_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态;1:启用,0:不启用',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'URL规则类型;1:用户自定义;2:别名添加',
  `full_url` varchar(255) NOT NULL DEFAULT '' COMMENT '完整url',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '实际显示的url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='url路由表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_seller_goods_class 结构
CREATE TABLE IF NOT EXISTS `cmf_seller_goods_class` (
  `uid` bigint(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goods_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品一级分类id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 0 否 1 是'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='卖家一级分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_seller_platform_goods 结构
CREATE TABLE IF NOT EXISTS `cmf_seller_platform_goods` (
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '平台自营商品ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品售卖状态 0 下架 1 上架',
  `issale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否直播间在售 0 否 1 是',
  `live_isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播间是否展示商品简介 0 否 1 是 默认0',
  KEY `uid_goodsid` (`uid`,`goodsid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='卖家自营商品表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_sendcode 结构
CREATE TABLE IF NOT EXISTS `cmf_sendcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '消息类型，1表示短信验证码，2表示邮箱验证码',
  `account` varchar(255) NOT NULL COMMENT '接收账号',
  `content` text NOT NULL COMMENT '消息内容',
  `addtime` int(11) NOT NULL COMMENT '提交时间',
  `send_type` int(11) NOT NULL COMMENT '1 阿里云，2 容联云，3 腾讯云，4 邮箱',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COMMENT='验证码发送记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_address 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `country` varchar(255) NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `country_code` int(11) NOT NULL DEFAULT '86' COMMENT '国家代号',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认地址 0 否 1 是',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='商城地址表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_apply 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_apply` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `des` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `cardno` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人',
  `country_code` int(11) NOT NULL DEFAULT '86' COMMENT '国家代号',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '地区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `service_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '客服电话',
  `receiver` varchar(255) NOT NULL DEFAULT '' COMMENT '退货收货人',
  `receiver_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '退货人联系电话',
  `receiver_province` varchar(255) NOT NULL DEFAULT '' COMMENT '退货人省份',
  `receiver_city` varchar(255) NOT NULL DEFAULT '' COMMENT '退货人市',
  `receiver_area` varchar(255) NOT NULL COMMENT '退货人地区',
  `receiver_address` varchar(255) NOT NULL COMMENT '退货人详细地址',
  `license` varchar(255) NOT NULL DEFAULT '' COMMENT '许可证',
  `certificate` varchar(255) NOT NULL DEFAULT '' COMMENT '营业执照',
  `other` varchar(255) NOT NULL COMMENT '其他证件',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中1通过2拒绝',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '原因',
  `order_percent` int(11) NOT NULL DEFAULT '0' COMMENT '订单分成比例',
  `sale_nums` bigint(11) NOT NULL DEFAULT '0' COMMENT '店铺总销量',
  `quality_points` float(11,1) NOT NULL DEFAULT '0.0' COMMENT '店铺商品质量(商品描述)平均分',
  `service_points` float(11,1) NOT NULL DEFAULT '0.0' COMMENT '店铺服务态度平均分',
  `express_points` float(11,1) NOT NULL DEFAULT '0.0' COMMENT '物流服务平均分',
  `shipment_overdue_num` int(11) NOT NULL DEFAULT '0' COMMENT '店铺逾期发货次数',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商城申请表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_bond 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_bond` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `bond` int(11) NOT NULL DEFAULT '0' COMMENT '保证金',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0已退回1已支付,-1已扣除',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='商城保证金表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_express 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司电话',
  `express_name_en` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司英文名称',
  `express_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司客服电话',
  `express_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司图标',
  `express_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0 否 1 是',
  `express_code` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司对应三方平台的编码',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='商城快递公司表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_goods 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_goods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `one_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品一级分类',
  `two_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品二级分类',
  `three_classid` int(11) NOT NULL DEFAULT '0' COMMENT '商品三级分类',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '商品视频地址',
  `video_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '商品视频封面',
  `thumbs` text NOT NULL COMMENT '封面',
  `content` longtext NOT NULL COMMENT '商品文字内容',
  `pictures` text NOT NULL COMMENT '商品内容图集',
  `specs` longtext NOT NULL COMMENT '商品规格',
  `postage` int(11) NOT NULL DEFAULT '0' COMMENT '邮费',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中-1商家下架1通过-2管理员下架 2拒绝',
  `isrecom` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐，0否1是',
  `sale_nums` int(11) NOT NULL DEFAULT '0' COMMENT '总销量',
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '商品拒绝原因',
  `issale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否在直播间销售 0 否 1 是(针对用户自己发布的商品)',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型 0 站内商品 1 站外商品 2平台自营',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '站外商品原价',
  `present_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '站外商品现价',
  `goods_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '站外商品简介',
  `href` varchar(255) NOT NULL DEFAULT '' COMMENT '站外商品链接',
  `live_isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '直播间是否展示商品简介 0 否 1 是 默认0',
  `low_price` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '站外商品最低价',
  `admin_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '发布自营商品的管理员id',
  `commission` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '主播代卖平台商品的佣金',
  `share_income` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '商品分享后被购买获取佣金',
  PRIMARY KEY (`id`),
  KEY `uid_status` (`uid`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_goods_class 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_goods_class` (
  `gc_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品分类ID',
  `gc_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `gc_name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Classification name',
  `gc_parentid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `gc_one_id` int(11) NOT NULL COMMENT '所属一级分类ID',
  `gc_sort` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类排序号',
  `gc_isshow` tinyint(1) NOT NULL COMMENT '是否展示 0 否 1 是',
  `gc_addtime` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类添加时间',
  `gc_edittime` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类修改时间',
  `gc_grade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品分类等级',
  `gc_icon` varchar(255) NOT NULL COMMENT '商品分类图标',
  PRIMARY KEY (`gc_id`) USING BTREE,
  KEY `list1` (`gc_parentid`,`gc_isshow`) USING BTREE,
  KEY `gc_parentid` (`gc_parentid`) USING BTREE,
  KEY `gc_grade` (`gc_grade`) USING BTREE,
  KEY `list2` (`gc_one_id`,`gc_grade`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_order 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '购买者ID',
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '卖家用户ID',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `spec_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品规格ID',
  `spec_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格名称',
  `spec_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '规格封面',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价（包含邮费）',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者姓名',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者联系电话',
  `country` varchar(255) NOT NULL DEFAULT '' COMMENT '国家',
  `country_code` int(11) NOT NULL DEFAULT '0' COMMENT '国家代号',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者市',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者地区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者详细地址',
  `postage` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `orderno` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 1 支付宝 2 微信 3 余额',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '订单状态  -1 已关闭  0 待付款 1 待发货 2 待收货 3 待评价 4 已评价 5 退款',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '订单添加时间',
  `cancel_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单取消时间',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '订单付款时间',
  `shipment_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单发货时间',
  `receive_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单收货时间',
  `evaluate_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单评价时间',
  `settlement_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单结算时间（款项打给卖家）',
  `is_append_evaluate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可追加评价',
  `order_percent` int(11) NOT NULL DEFAULT '0' COMMENT '订单抽成比例',
  `refund_starttime` int(11) NOT NULL DEFAULT '0' COMMENT '订单发起退款时间',
  `refund_endtime` int(11) NOT NULL DEFAULT '0' COMMENT '订单退款处理结束时间',
  `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款处理结果 -2取消申请 -1 失败 0 处理中 1 成功 ',
  `refund_shop_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款时卖家处理结果 0 未处理 -1 拒绝 1 同意',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `express_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司电话',
  `express_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司图标',
  `express_code` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司对应三方平台的编码',
  `express_number` varchar(255) NOT NULL DEFAULT '' COMMENT '物流单号',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单是否删除 0 否 -1 买家删除 -2 卖家删除 1 买家卖家都删除',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '买家留言内容',
  `commission` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '平台自营商品设置的代售佣金',
  `liveuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '代售平台商品的主播ID',
  `admin_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '发布自营商品的管理员id',
  `shareuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '分享商品的用户ID',
  `share_income` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户购买分享者分享的商品后，分享用户获得的佣金',
  `express_name_en` varchar(255) NOT NULL DEFAULT '0' COMMENT '物流公司英文名称',
  PRIMARY KEY (`id`),
  KEY `id_uid` (`id`,`uid`) USING BTREE,
  KEY `shopuid_status` (`shop_uid`,`status`) USING BTREE,
  KEY `shopuid_status_refundstatus` (`shop_uid`,`status`,`refund_status`) USING BTREE,
  KEY `id_shopuid` (`id`,`shop_uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COMMENT='商品订单表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_order_comments 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_order_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品订单ID',
  `goodsid` bigint(20) NOT NULL COMMENT '商品ID',
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '店铺用户id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '文字内容',
  `thumbs` varchar(255) NOT NULL DEFAULT '' COMMENT '评论图片列表',
  `video_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '视频封面',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频地址',
  `is_anonym` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名 0否 1是',
  `quality_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品描述评分',
  `service_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT '服务态度评分',
  `express_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT '物流速度评分',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_append` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是追评0 否 1 是',
  PRIMARY KEY (`id`),
  KEY `goodsid_isappend` (`goodsid`,`is_append`) USING BTREE,
  KEY `uid_orderid` (`uid`,`orderid`,`is_append`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品订单评论表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_order_message 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_order_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '消息内容',
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '接受消息用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户身份 0买家 1卖家',
  `is_commission` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否订单结算消息 0 否 1 是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='商品订单消息表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_order_refund 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_order_refund` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '买家id',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商家ID',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '退款原因',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '退款说明',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '退款图片（废弃）',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款类型 0 仅退款 1退货退款',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `shop_process_time` int(11) NOT NULL DEFAULT '0' COMMENT '店铺处理时间',
  `shop_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺处理结果 -1 拒绝 0 处理中 1 同意',
  `shop_process_num` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺驳回次数',
  `platform_process_time` int(11) NOT NULL DEFAULT '0' COMMENT '平台处理时间',
  `platform_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '平台处理结果 -1 拒绝 0 处理中 1 同意',
  `admin` varchar(255) NOT NULL DEFAULT '' COMMENT '平台处理账号',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '平台账号ip',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款处理状态 0 处理中 -1 买家已取消 1 已完成 ',
  `is_platform_interpose` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否平台介入 0 否 1 是',
  `system_process_time` int(11) NOT NULL DEFAULT '0' COMMENT '系统自动处理时间',
  `platform_interpose_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '申请平台介入的理由',
  `platform_interpose_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '申请平台介入的详细原因',
  `platform_interpose_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '申请平台介入的图片举证',
  PRIMARY KEY (`id`),
  KEY `uid_orderid` (`uid`,`orderid`) USING BTREE,
  KEY `orderid_shopuid` (`orderid`,`shop_uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='商品退款订单表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_order_refund_list 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_order_refund_list` (
  `orderid` bigint(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理方 1 买家 2 卖家 3 平台 4 系统',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '处理说明',
  `handle_desc` varchar(300) NOT NULL DEFAULT '' COMMENT '处理备注说明',
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家拒绝理由'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品订单退款处理记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_platform_reason 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_platform_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '原因名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='商品问题原因选项表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_points 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_points` (
  `shop_uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '店铺用户ID',
  `evaluate_total` bigint(20) NOT NULL DEFAULT '0' COMMENT '评价总数',
  `quality_points_total` int(11) NOT NULL DEFAULT '0' COMMENT '店铺商品质量(商品描述)总分',
  `service_points_total` int(11) NOT NULL DEFAULT '0' COMMENT '店铺服务态度总分',
  `express_points_total` int(11) NOT NULL DEFAULT '0' COMMENT '物流服务总分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品评分表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_refund_reason 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_refund_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '原因名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='商品退款原因表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_shop_refuse_reason 结构
CREATE TABLE IF NOT EXISTS `cmf_shop_refuse_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '原因名称',
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0不显示 1 显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='商品拒绝原因表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_slide 结构
CREATE TABLE IF NOT EXISTS `cmf_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:显示,0不显示',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片分类',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分类备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='幻灯片表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_slide_item 结构
CREATE TABLE IF NOT EXISTS `cmf_slide_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slide_id` int(11) NOT NULL DEFAULT '0' COMMENT '幻灯片id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:显示;0:隐藏',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '幻灯片名称',
  `image` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片图片',
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片链接',
  `target` varchar(10) NOT NULL DEFAULT '' COMMENT '友情链接打开方式',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '幻灯片描述',
  `content` text CHARACTER SET utf8 COMMENT '幻灯片内容',
  `more` text COMMENT '扩展信息',
  PRIMARY KEY (`id`),
  KEY `slide_id` (`slide_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='幻灯片子项表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_theme 结构
CREATE TABLE IF NOT EXISTS `cmf_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后升级时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '模板状态,1:正在使用;0:未使用',
  `is_compiled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为已编译模板',
  `theme` varchar(20) NOT NULL DEFAULT '' COMMENT '主题目录名，用于主题的维一标识',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '主题名称',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '主题版本号',
  `demo_url` varchar(50) NOT NULL DEFAULT '' COMMENT '演示地址，带协议',
  `thumbnail` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '主题作者',
  `author_url` varchar(50) NOT NULL DEFAULT '' COMMENT '作者网站链接',
  `lang` varchar(10) NOT NULL DEFAULT '' COMMENT '支持语言',
  `keywords` varchar(50) NOT NULL DEFAULT '' COMMENT '主题关键字',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '主题描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='主题表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_theme_file 结构
CREATE TABLE IF NOT EXISTS `cmf_theme_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_public` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否公共的模板文件',
  `list_order` float NOT NULL DEFAULT '10000' COMMENT '排序',
  `theme` varchar(20) NOT NULL DEFAULT '' COMMENT '模板名称',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '模板文件名',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作',
  `file` varchar(50) NOT NULL DEFAULT '' COMMENT '模板文件，相对于模板根目录，如Portal/index.html',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '模板文件描述',
  `more` text COMMENT '模板更多配置,用户自己后台设置的',
  `config_more` text COMMENT '模板更多配置,来源模板的配置文件',
  `draft_more` text COMMENT '模板更多配置,用户临时保存的配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题文件表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_turntable 结构
CREATE TABLE IF NOT EXISTS `cmf_turntable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，0无奖1钻石2礼物',
  `type_val` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型值',
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `rate` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '中奖率',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='转盘表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_turntable_con 结构
CREATE TABLE IF NOT EXISTS `cmf_turntable_con` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '次数',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='转盘次数表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_turntable_log 结构
CREATE TABLE IF NOT EXISTS `cmf_turntable_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `liveuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '主播ID',
  `showid` bigint(20) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  `iswin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否中奖',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户转盘记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_turntable_win 结构
CREATE TABLE IF NOT EXISTS `cmf_turntable_win` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logid` bigint(20) NOT NULL DEFAULT '0' COMMENT '转盘记录ID',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，0无奖1钻石2礼物',
  `type_val` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型值',
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理状态，0未处理1已处理',
  `uptime` bigint(20) NOT NULL DEFAULT '0' COMMENT '处理时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户转盘赢取表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user 结构
CREATE TABLE IF NOT EXISTS `cmf_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '用户类型;1:admin;2:会员',
  `sex` tinyint(2) NOT NULL DEFAULT '0' COMMENT '性别;0:保密,1:男,2:女',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `score` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `coin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '钻石',
  `red_votes` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '红包映票',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常,2:未验证',
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码;cmf_password加密',
  `user_nicename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '用户登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网址',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `bg_img` varchar(255) NOT NULL DEFAULT '' COMMENT '背景图',
  `avatar_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '小头像',
  `signature` varchar(255) NOT NULL DEFAULT '' COMMENT '个性签名',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '中国手机不带国家代码，国际手机号格式为：国家代码-手机号',
  `country_code` varchar(20) NOT NULL DEFAULT '86' COMMENT '国家/地区',
  `more` text COMMENT '扩展属性',
  `consumption` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '消费总额',
  `votes` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '映票余额',
  `votestotal` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '映票总额',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未推荐 1 推荐',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '三方标识',
  `login_type` varchar(20) NOT NULL DEFAULT 'phone' COMMENT '注册方式',
  `iszombie` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启僵尸粉',
  `isrecord` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开起回放',
  `iszombiep` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否僵尸粉',
  `isadvert` tinyint(1) NOT NULL DEFAULT '0' COMMENT '广告视频发布者',
  `isanchor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '设置主播推荐',
  `issuper` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否超管',
  `ishot` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否热门显示',
  `goodnum` varchar(255) NOT NULL DEFAULT '0' COMMENT '当前装备靓号',
  `source` varchar(255) NOT NULL DEFAULT 'pc' COMMENT '注册来源',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '所在地',
  `end_bantime` bigint(20) NOT NULL DEFAULT '0' COMMENT '禁用到期时间',
  `balance` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户商城人民币账户金额',
  `balance_total` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户商城累计收入人民币',
  `balance_consumption` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '用户商城累计消费人民币',
  `recommend_time` int(1) NOT NULL DEFAULT '0' COMMENT '推荐时间',
  PRIMARY KEY (`id`),
  KEY `user_login` (`user_login`) USING BTREE,
  KEY `user_nicename` (`user_nicename`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43614 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_advert 结构
CREATE TABLE IF NOT EXISTS `cmf_user_advert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '广告标题',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '广告封面',
  `video` varchar(255) NOT NULL DEFAULT '' COMMENT '视频',
  `advert_url` varchar(255) NOT NULL DEFAULT '' COMMENT '广告外链',
  `is_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否上架 0 否 1 是',
  `number_likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `number_comment` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `number_share` int(11) NOT NULL DEFAULT '0' COMMENT '分享数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='用户广告表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_advert_comment 结构
CREATE TABLE IF NOT EXISTS `cmf_user_advert_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `audio_url` varchar(255) NOT NULL DEFAULT '' COMMENT '声音',
  `number_likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `useradvertid` int(11) NOT NULL DEFAULT '0' COMMENT '用户广告ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='用户广告评论表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_attention 结构
CREATE TABLE IF NOT EXISTS `cmf_user_attention` (
  `uid` int(12) NOT NULL COMMENT '用户ID',
  `touid` int(12) NOT NULL COMMENT '关注人ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 取消关注 1 关注',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未读 1 已读',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '时间',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  KEY `uid_touid_index` (`uid`,`touid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='关注用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_auth 结构
CREATE TABLE IF NOT EXISTS `cmf_user_auth` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `real_name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '电话',
  `cer_no` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证号',
  `front_view` varchar(255) NOT NULL DEFAULT '' COMMENT '正面',
  `back_view` varchar(255) NOT NULL DEFAULT '' COMMENT '反面',
  `handset_view` varchar(255) NOT NULL DEFAULT '' COMMENT '手持',
  `reason` text COMMENT '审核说明',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `uptime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 处理中 1 成功 2 失败',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户认证表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_balance_cashrecord 结构
CREATE TABLE IF NOT EXISTS `cmf_user_balance_cashrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `orderno` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0审核中，1审核通过，2审核拒绝',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '账号类型 1 支付宝 2 微信 3 银行卡',
  `account_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '帐号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COMMENT='用户余额提现表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_balance_record 结构
CREATE TABLE IF NOT EXISTS `cmf_user_balance_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL COMMENT '用户id',
  `touid` bigint(20) NOT NULL COMMENT '对方用户id',
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '操作的余额数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支类型,0支出1收入',
  `action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支行为 1 买家使用余额付款 2 系统自动结算货款给卖家  3 卖家超时未发货,退款给买家 4 买家发起退款，卖家超时未处理,系统自动退款 5买家发起退款，卖家同意 6 买家发起退款，平台介入后同意 7 用户使用余额购买付费项目  8 付费项目收入 9 代售平台商品佣金',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '对应的订单ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户余额收支表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_banrecord 结构
CREATE TABLE IF NOT EXISTS `cmf_user_banrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ban_reason` varchar(255) DEFAULT '' COMMENT '被禁用原因',
  `ban_long` int(10) DEFAULT '0' COMMENT '用户禁用时长：单位：分钟',
  `uid` int(10) DEFAULT '0' COMMENT '禁用 用户ID',
  `addtime` int(10) DEFAULT '0' COMMENT '提交时间',
  `end_time` int(10) DEFAULT '0' COMMENT '禁用到期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户禁用记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_black 结构
CREATE TABLE IF NOT EXISTS `cmf_user_black` (
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(12) NOT NULL DEFAULT '0' COMMENT '被拉黑人ID',
  KEY `uid_touid_index` (`uid`,`touid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户拉黑表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_coinrecord 结构
CREATE TABLE IF NOT EXISTS `cmf_user_coinrecord` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支类型,0支出1收入',
  `action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支行为，1赠送礼物,2弹幕,3登录奖励,4购买VIP,5购买坐骑,6房间扣费,7计时扣费,8发送红包,9抢红包,10开通守护,11注册奖励,12礼物中奖,13奖池中奖,14缴纳保证金,15退还保证金,16转盘游戏,17转盘中奖,18购买靓号,19游戏下注,20游戏退还,21每日任务奖励,22付费观看视频,23观看60秒视频奖励钻石,24充值vip消费,25短视频上热门,26短视频热门退还金额,27直播上热门消费,28直播热门退还金额',
  `uid` int(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(20) NOT NULL DEFAULT '0' COMMENT '对方ID',
  `giftid` int(20) NOT NULL DEFAULT '0' COMMENT '行为对应ID',
  `giftcount` int(20) NOT NULL DEFAULT '0' COMMENT '数量',
  `totalcoin` int(20) NOT NULL DEFAULT '0' COMMENT '总价',
  `showid` int(12) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `mark` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标识，1表示热门礼物，2表示守护礼物',
  PRIMARY KEY (`id`),
  KEY `action_uid_addtime` (`action`,`uid`,`addtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2523 DEFAULT CHARSET=utf8mb4 COMMENT='用户钻石收支记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_daily_tasks 结构
CREATE TABLE IF NOT EXISTS `cmf_user_daily_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '任务类型 1观看直播, 2观看视频, 3直播奖励, 4打赏奖励, 5分享奖励',
  `target` int(11) NOT NULL DEFAULT '0' COMMENT '目标',
  `schedule` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '当前进度',
  `reward` int(5) NOT NULL DEFAULT '0' COMMENT '奖励钻石数量',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '生成时间',
  `uptime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `state` tinyint(1) DEFAULT '0' COMMENT '状态 0未达成  1可领取  2已领取',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`,`type`) USING BTREE,
  KEY `uid_2` (`uid`) USING BTREE,
  KEY `uid_3` (`uid`,`type`,`addtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='每日任务';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_goods_collect 结构
CREATE TABLE IF NOT EXISTS `cmf_user_goods_collect` (
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `goodsid` int(12) NOT NULL COMMENT '商品id',
  `goodsuid` int(12) NOT NULL COMMENT '商品所有者用户id',
  `addtime` int(12) NOT NULL COMMENT '时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户收藏商品表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_goods_visit 结构
CREATE TABLE IF NOT EXISTS `cmf_user_goods_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` bigint(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `goodsid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `time_format` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览商品记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_pushid 结构
CREATE TABLE IF NOT EXISTS `cmf_user_pushid` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `pushid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户对应极光registration_id',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='推送ID表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_scorerecord 结构
CREATE TABLE IF NOT EXISTS `cmf_user_scorerecord` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支类型,0支出1收入',
  `action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支行为，4购买VIP,5购买坐骑,18购买靓号,21游戏获胜',
  `uid` int(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(20) NOT NULL DEFAULT '0' COMMENT '对方ID',
  `giftid` int(20) NOT NULL DEFAULT '0' COMMENT '行为对应ID',
  `giftcount` int(20) NOT NULL DEFAULT '0' COMMENT '数量',
  `totalcoin` int(20) NOT NULL DEFAULT '0' COMMENT '总价',
  `showid` int(12) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `game_action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '游戏类型',
  PRIMARY KEY (`id`),
  KEY `action_uid_addtime` (`action`,`uid`,`addtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户积分收支记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_sign 结构
CREATE TABLE IF NOT EXISTS `cmf_user_sign` (
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `bonus_day` int(11) NOT NULL DEFAULT '0' COMMENT '登录天数',
  `bonus_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `count_day` int(11) NOT NULL DEFAULT '0' COMMENT '连续登陆天数',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户登录记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_super 结构
CREATE TABLE IF NOT EXISTS `cmf_user_super` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='超管用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_teenager 结构
CREATE TABLE IF NOT EXISTS `cmf_user_teenager` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '青少年模式密码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 关闭 1 开启',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edittime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='青少年密码表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_teenager_time 结构
CREATE TABLE IF NOT EXISTS `cmf_user_teenager_time` (
  `uid` int(11) NOT NULL,
  `length` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='青少年时间表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_token 结构
CREATE TABLE IF NOT EXISTS `cmf_user_token` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT ' 过期时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `token` varchar(64) NOT NULL DEFAULT '' COMMENT 'token',
  `device_type` varchar(10) NOT NULL DEFAULT '' COMMENT '设备类型;mobile,android,iphone,ipad,web,pc,mac,wxapp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COMMENT='用户客户端登录 token 表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_voterecord 结构
CREATE TABLE IF NOT EXISTS `cmf_user_voterecord` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支类型,0支出，1收入',
  `action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收支行为,1收礼物2弹幕3分销收益4家族长收益6房间收费7计时收费10守护11每观看60秒视频奖励',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fromid` bigint(20) NOT NULL DEFAULT '0' COMMENT '来源用户ID',
  `actionid` bigint(20) NOT NULL DEFAULT '0' COMMENT '行为对应ID',
  `nums` bigint(20) NOT NULL DEFAULT '0' COMMENT '数量',
  `total` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `showid` bigint(20) NOT NULL DEFAULT '0' COMMENT '直播标识',
  `votes` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '收益映票',
  `addtime` bigint(20) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `action_uid_addtime` (`action`,`uid`,`addtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=518 DEFAULT CHARSET=utf8mb4 COMMENT='用户映票记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_user_zombie 结构
CREATE TABLE IF NOT EXISTS `cmf_user_zombie` (
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='僵死用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_verification_code 结构
CREATE TABLE IF NOT EXISTS `cmf_verification_code` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当天已经发送成功的次数',
  `send_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后发送成功时间',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证码过期时间',
  `code` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '最后发送成功的验证码',
  `account` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机号或者邮箱',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='手机邮箱数字验证码表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video 结构
CREATE TABLE IF NOT EXISTS `cmf_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `thumb_s` varchar(255) NOT NULL DEFAULT '' COMMENT '封面小图',
  `href` varchar(255) NOT NULL DEFAULT '' COMMENT '视频地址',
  `href_w` varchar(255) NOT NULL DEFAULT '' COMMENT '水印视频',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `views` int(11) NOT NULL DEFAULT '1' COMMENT '浏览数（涉及到推荐排序机制，所以默认为1）',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `steps` int(11) NOT NULL DEFAULT '0' COMMENT '踩总数',
  `shares` int(11) NOT NULL DEFAULT '0' COMMENT '分享数量',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `lat` varchar(255) NOT NULL DEFAULT '' COMMENT '维度',
  `lng` varchar(255) NOT NULL DEFAULT '' COMMENT '经度',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1删除（下架）0不下架',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '视频状态 0未审核 1通过 2拒绝',
  `music_id` int(12) NOT NULL DEFAULT '0' COMMENT '背景音乐ID',
  `xiajia_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '下架原因',
  `nopass_time` int(12) NOT NULL DEFAULT '0' COMMENT '审核不通过时间（第一次审核不通过时更改此值，用于判断是否发送极光IM）',
  `watch_ok` int(12) NOT NULL DEFAULT '1' COMMENT '视频完整看完次数(涉及到推荐排序机制，所以默认为1)',
  `is_ad` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为广告视频 0 否 1 是',
  `ad_endtime` int(12) NOT NULL DEFAULT '0' COMMENT '广告显示到期时间',
  `ad_url` varchar(255) NOT NULL DEFAULT '' COMMENT '广告外链',
  `orderno` int(12) NOT NULL DEFAULT '0' COMMENT '权重值，数字越大越靠前',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '视频绑定类型 0 未绑定 1 商品  2 付费内容',
  `goodsid` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `classid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `anyway` varchar(10) NOT NULL DEFAULT '1.1' COMMENT '横竖屏(封面-高/宽)，大于1表示竖屏,小于1表示横屏',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 管理员 0 用户',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '需要花费的钻石',
  `dynamic_label_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联话题ID',
  `author_center_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联创作者活动ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COMMENT='视频表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_black 结构
CREATE TABLE IF NOT EXISTS `cmf_video_black` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `videoid` int(10) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频拉黑用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_class 结构
CREATE TABLE IF NOT EXISTS `cmf_video_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'CATEGORY TITLE',
  `is_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 显示 0 不显示',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='视频分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_coin 结构
CREATE TABLE IF NOT EXISTS `cmf_video_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `videoid` int(12) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `coin` int(12) NOT NULL DEFAULT '0' COMMENT '付费钻石',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='视频付费钻石表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_collection 结构
CREATE TABLE IF NOT EXISTS `cmf_video_collection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `videoid` int(12) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '收藏状态 1收藏 0 取消收藏',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='视频收藏表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_comments 结构
CREATE TABLE IF NOT EXISTS `cmf_video_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '评论用户ID',
  `touid` int(10) NOT NULL DEFAULT '0' COMMENT '被评论的用户ID',
  `videoid` int(10) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `commentid` int(10) NOT NULL DEFAULT '0' COMMENT '所属评论ID',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '上级评论ID',
  `content` text COMMENT '评论内容',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  `at_info` text COMMENT '评论时被@用户的信息（json串）',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未读 1 已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COMMENT='视频评论表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_comments_at 结构
CREATE TABLE IF NOT EXISTS `cmf_video_comments_at` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `touid` int(12) NOT NULL DEFAULT '0' COMMENT '被@的用户ID',
  `commentid` int(12) NOT NULL DEFAULT '0' COMMENT '所属评论ID',
  `videoid` int(12) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未读 1 已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='视频评论@用户表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_comments_like 结构
CREATE TABLE IF NOT EXISTS `cmf_video_comments_like` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `commentid` int(10) NOT NULL DEFAULT '0' COMMENT '评论ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  `touid` int(12) NOT NULL DEFAULT '0' COMMENT '被喜欢的评论者id',
  `videoid` int(12) NOT NULL DEFAULT '0' COMMENT '评论所属视频id',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未读 1 已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='视频评论点赞表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_gift 结构
CREATE TABLE IF NOT EXISTS `cmf_video_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户id',
  `touid` int(12) NOT NULL DEFAULT '0' COMMENT '对方用户id',
  `videoid` int(12) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `giftid` int(12) NOT NULL DEFAULT '0' COMMENT '礼物ID',
  `number` int(12) NOT NULL DEFAULT '0' COMMENT '数量',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='视频礼物表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_like 结构
CREATE TABLE IF NOT EXISTS `cmf_video_like` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(10) NOT NULL DEFAULT '0' COMMENT '被喜欢用户ID',
  `videoid` int(10) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '视频是否被删除或被拒绝 0被删除或被拒绝 1 正常',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未读 1 已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='视频点赞表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_report 结构
CREATE TABLE IF NOT EXISTS `cmf_video_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '被举报用户ID',
  `videoid` int(11) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0处理中 1已处理  2审核失败',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频举报表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_report_classify 结构
CREATE TABLE IF NOT EXISTS `cmf_video_report_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(10) NOT NULL DEFAULT '9999' COMMENT '排序',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '举报类型名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='视频举报分类表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_step 结构
CREATE TABLE IF NOT EXISTS `cmf_video_step` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `videoid` int(10) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频踩一踩表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_video_view 结构
CREATE TABLE IF NOT EXISTS `cmf_video_view` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `videoid` int(10) NOT NULL DEFAULT '0' COMMENT '视频ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='视频浏览记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_vip 结构
CREATE TABLE IF NOT EXISTS `cmf_vip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `length` int(11) NOT NULL DEFAULT '1' COMMENT '时长（月）',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分价格',
  `list_order` int(11) NOT NULL DEFAULT '9999' COMMENT '序号',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='VIP等级表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_vip_charge_rules 结构
CREATE TABLE IF NOT EXISTS `cmf_vip_charge_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `name_en` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT '充值天数',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='VIP充值规则表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_vip_charge_user 结构
CREATE TABLE IF NOT EXISTS `cmf_vip_charge_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '充值对象ID',
  `user_nicename` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT '充值天数',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '钻石数',
  `orderno` varchar(255) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付类型 0 余额 1 支付宝 2 微信支付 3 苹果支付',
  `ambient` varchar(255) NOT NULL DEFAULT '0' COMMENT '支付环境',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '三方订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0未支付，1已完成',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='VIP充值记录表';

-- 数据导出被取消选择。

-- 导出  表 yunbaolivesql.cmf_vip_user 结构
CREATE TABLE IF NOT EXISTS `cmf_vip_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '到期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43601 DEFAULT CHARSET=utf8mb4 COMMENT='VIP用户表';

-- 数据导出被取消选择。

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
