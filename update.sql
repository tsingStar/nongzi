-- ##添加兼职代理商比率
ALTER TABLE `shop`.`ybt_product`
ADD COLUMN `parttime_commission` varchar(255) NULL AFTER `salesman_commission`;
-- ##订单详情添加佣金比例和优惠金额、运费调整
ALTER TABLE `shop`.`ybt_order_det`
ADD COLUMN `agent_commission` decimal(10, 2) NULL DEFAULT 0 AFTER `create_time`,
ADD COLUMN `salesman_commission` decimal(10, 2) NULL DEFAULT 0 AFTER `agent_commission`,
ADD COLUMN `parttime_commission` decimal(10, 2) NULL DEFAULT 0 AFTER `salesman_commission`,
ADD COLUMN `discount` decimal(10, 2) NULL DEFAULT 0 COMMENT '优惠金额' AFTER `parttime_commission`,
ADD COLUMN `pre_send_fee` decimal(10, 2) NULL DEFAULT 0 COMMENT '原始运费' AFTER `discount`,
ADD COLUMN `send_fee` decimal(10, 2) NULL DEFAULT 0 COMMENT '修改后运费' AFTER `pre_send_fee`,
ADD COLUMN `pre_discount` decimal(10, 2) NULL DEFAULT 0 COMMENT '原始优惠' AFTER `parttime_commission`;
-- ##增加跟单目录处理
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10080, '跟单管理', 'admin/followOrder', 1, '', 1, 2, 1555774484, 1555774484);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10081, '跟单分类', 'admin/followOrder/followCate', 1, '', 10080, 3, 1555774530, 1555774530);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10082, '跟单记录', 'admin/followOrder/followList', 1, '', 10080, 3, 1555774606, 1555774606);
-- ##添加跟单分类
CREATE TABLE `ybt_follow_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '跟单分类名称',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='跟单分类';
-- ##添加跟单记录
CREATE TABLE `ybt_follow_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '跟单客服id',
  `admin_name` varchar(50) NOT NULL COMMENT '跟单客服名称',
  `user_id` int(11) NOT NULL COMMENT '客户id',
  `cate_id` int(11) NOT NULL COMMENT '跟单分类',
  `cate_name` int(255) NOT NULL COMMENT '分类名称',
  `content` varchar(500) NOT NULL COMMENT '跟单内容',
  `result` varchar(1000) NOT NULL COMMENT '跟单记录',
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='跟单记录';
-- ##添加首单佣金
ALTER TABLE `shop`.`ybt_order`
ADD COLUMN `first_money` decimal(12, 2) NULL DEFAULT 0 COMMENT '首单佣金' AFTER `is_trash`;


-- ##添加代理商分类
ALTER TABLE `shop`.`ybt_admins`
ADD COLUMN `agent_cate` tinyint(1) NULL DEFAULT 1 COMMENT '代理商类型  1 代理商 2 兼职代理商' AFTER `able_commission`;
-- ##添加支付设置
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10083, '支付设置', 'admin/Site/paySet', 1, '支付设置', 10013, 3, 1556457350, 1556457350);
-- ##添加线下支付银行卡
CREATE TABLE `ybt_bank_info` (
  `bank_card` varchar(50) NOT NULL COMMENT '银行卡号',
  `bank_name` varchar(30) NOT NULL COMMENT '银行名称',
  `card_user_name` varchar(20) NOT NULL COMMENT '持卡人姓名',
  PRIMARY KEY (`bank_card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='线下支付银行卡信息';
-- ##添加启动图
CREATE TABLE `ybt_login_logo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(300) NOT NULL COMMENT '启动图地址',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `type` varchar(255) NOT NULL COMMENT '类型',
  `good_id` int(11) NOT NULL,
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示 0 否 1 是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ##添加目录
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10084, '内容管理', 'admin/Content', 1, '', 1, 2, 1556459179, 1556459179);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10085, '启动图设置', 'admin/Content/appLogo', 1, '', 10084, 3, 1556459220, 1556459220);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10086, '推送消息', 'admin/Content/tips', 1, '', 10084, 3, 1556522880, 1556522880);
-- INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10087, '新闻列表', 'admin/Content/news', 1, '', 10084, 3, 1556539472, 1556539472);
-- INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10088, '新闻分类', 'admin/Content/newsCate', 1, '', 10084, 3, 1556539932, 1556539932);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10090, '首页广告', 'admin/Content/adv', 1, '', 10084, 3, 1556627501, 1556627501);



-- ##增加消息推送
CREATE TABLE `ybt_tips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '消息标题',
  `cate` varchar(20) NOT NULL COMMENT '消息类型 3 活动 2 客服通知 1 系统通知',
  `msg` varchar(500) NOT NULL,
  `author` varchar(10) NOT NULL,
  `content` text NOT NULL COMMENT '详情信息',
  `nums` int(10) NOT NULL DEFAULT '0' COMMENT '推送数量',
  `send_time` datetime DEFAULT NULL COMMENT '发送时间',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `ybt_user_tips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `tips_id` int(11) NOT NULL COMMENT '消息id',
  `cate` int(11) NOT NULL COMMENT '消息分类 1 系统通知 2 客服 3 活动',
  `title` varchar(255) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL COMMENT '是否已阅读',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4012 DEFAULT CHARSET=utf8;

ALTER TABLE `shop`.`ybt_user`
ADD COLUMN `register_id` varchar(100) NULL COMMENT '极光id' AFTER `is_again`;
ALTER TABLE `shop`.`ybt_user`
ADD COLUMN `register_src` tinyint(1) NULL DEFAULT NULL COMMENT '1 安卓 2 iOS 3 小程序' AFTER `register_id`;
ALTER TABLE `shop`.`ybt_user_add_info`
ADD COLUMN `store_name` varchar(255) NULL COMMENT '店铺名称' AFTER `user_name`;

-- 20190429
-- ##添加新闻
-- CREATE TABLE `ybt_news` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `title` varchar(50) NOT NULL COMMENT '新闻标题',
--   `cate_id` varchar(20) NOT NULL COMMENT '新闻类型',
--   `abstract` varchar(500) NOT NULL,
--   `content` text NOT NULL COMMENT '详情信息',
--   `create_time` int(11) DEFAULT NULL,
--   `update_time` int(11) DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ##新闻分类
-- CREATE TABLE `ybt_news_cate` (
--   `id` int(10) NOT NULL AUTO_INCREMENT,
--   `name` varchar(50) NOT NULL COMMENT '分类名称',
--   `create_time` int(11) NOT NULL,
--   `update_time` int(11) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻分类库';
-- 添加首页广告位
CREATE TABLE `ybt_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='广告';
CREATE TABLE `ybt_base_info` (
  `config` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10091, '基础设置', 'admin/Site/baseInfo', 1, '', 10013, 3, 1556632287, 1556632287);
