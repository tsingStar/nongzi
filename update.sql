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
ADD COLUMN `send_fee` decimal(10, 2) NULL DEFAULT 0 COMMENT '修改后运费' AFTER `pre_send_fee`;
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