##添加兼职代理商比率
ALTER TABLE `shop`.`ybt_product`
ADD COLUMN `parttime_commission` varchar(255) NULL AFTER `salesman_commission`;
##订单详情添加佣金比例和优惠金额、运费调整
ALTER TABLE `shop`.`ybt_order_det`
ADD COLUMN `agent_commission` decimal(10, 2) NULL DEFAULT 0 AFTER `create_time`,
ADD COLUMN `salesman_commission` decimal(10, 2) NULL DEFAULT 0 AFTER `agent_commission`,
ADD COLUMN `parttime_commission` decimal(10, 2) NULL DEFAULT 0 AFTER `salesman_commission`,
ADD COLUMN `discount` decimal(10, 2) NULL DEFAULT 0 COMMENT '优惠金额' AFTER `parttime_commission`,
ADD COLUMN `pre_send_fee` decimal(10, 2) NULL DEFAULT 0 COMMENT '原始运费' AFTER `discount`,
ADD COLUMN `send_fee` decimal(10, 2) NULL DEFAULT 0 COMMENT '修改后运费' AFTER `pre_send_fee`,
ADD COLUMN `pre_discount` decimal(10, 2) NULL DEFAULT 0 COMMENT '原始优惠' AFTER `parttime_commission`;
##增加跟单目录处理
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10080, '跟单管理', 'admin/followOrder', 1, '', 1, 2, 1555774484, 1555774484);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10081, '跟单分类', 'admin/followOrder/followCate', 1, '', 10080, 3, 1555774530, 1555774530);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10082, '跟单记录', 'admin/followOrder/followList', 1, '', 10080, 3, 1555774606, 1555774606);
##添加跟单分类
CREATE TABLE `ybt_follow_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '跟单分类名称',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='跟单分类';
##添加跟单记录
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
##添加首单佣金
ALTER TABLE `shop`.`ybt_order`
ADD COLUMN `first_money` decimal(12, 2) NULL DEFAULT 0 COMMENT '首单佣金' AFTER `is_trash`;
##添加代理商分类
ALTER TABLE `shop`.`ybt_admins`
ADD COLUMN `agent_cate` tinyint(1) NULL DEFAULT 1 COMMENT '代理商类型  1 代理商 2 兼职代理商' AFTER `able_commission`;
##添加vip_code 索引
ALTER TABLE `shop`.`ybt_admins`
DROP INDEX `vip_code`,
ADD UNIQUE INDEX `vip_code`(`vip_code`) USING HASH;
##添加支付设置
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10083, '支付设置', 'admin/Site/paySet', 1, '支付设置', 10013, 3, 1556457350, 1556457350);
##添加线下支付银行卡
CREATE TABLE `ybt_bank_info` (
  `bank_card` varchar(50) NOT NULL COMMENT '银行卡号',
  `bank_name` varchar(30) NOT NULL COMMENT '银行名称',
  `card_user_name` varchar(20) NOT NULL COMMENT '持卡人姓名',
  PRIMARY KEY (`bank_card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='线下支付银行卡信息';
##添加启动图
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
##添加目录
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10084, '内容管理', 'admin/Content', 1, '', 1, 2, 1556459179, 1556459179);
INSERT INTO `shop`.`ybt_menu`(`id`, `name`, `url`, `display`, `describe`, `parent_id`, `level`, `create_time`, `update_time`) VALUES (10085, '启动图设置', 'admin/Content/appLogo', 1, '', 10084, 3, 1556459220, 1556459220);
