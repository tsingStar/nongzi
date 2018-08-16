/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : nongzi

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-16 13:58:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ybt_admins
-- ----------------------------
DROP TABLE IF EXISTS `ybt_admins`;
CREATE TABLE `ybt_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(10) NOT NULL COMMENT '管理员用户名',
  `password` varchar(32) NOT NULL COMMENT '登陆密码',
  `encrypt` varchar(4) NOT NULL COMMENT '加密字符串',
  `name` varchar(10) NOT NULL COMMENT '管理员名称',
  `role_id` varchar(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `login_ip` int(11) NOT NULL COMMENT '最近登陆ip',
  `login_time` int(11) NOT NULL COMMENT '登陆时间',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '账号是否可用',
  `describe` varchar(500) DEFAULT NULL,
  `department_pid` int(11) NOT NULL COMMENT '部门',
  `department_id` int(11) NOT NULL COMMENT '部门id',
  `vip_code` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_admins
-- ----------------------------
INSERT INTO `ybt_admins` VALUES ('1', 'admin', '827ccb0eea8a706c4c34a16891f84e7b', '', '系统管理员', '1', '2130706433', '1534398119', null, '1534398119', '1', '', '0', '0', null);

-- ----------------------------
-- Table structure for ybt_buy_supply
-- ----------------------------
DROP TABLE IF EXISTS `ybt_buy_supply`;
CREATE TABLE `ybt_buy_supply` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '求购列表',
  `user_id` varchar(255) NOT NULL COMMENT '用户id',
  `product_name` varchar(255) NOT NULL,
  `num` int(11) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL COMMENT '描述',
  `remarks` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '类型 1 求购 2 供应',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '是否已处理 0 否 1 已处理',
  `opera_id` int(11) NOT NULL COMMENT '操作员id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='求购，供应';

-- ----------------------------
-- Records of ybt_buy_supply
-- ----------------------------
INSERT INTO `ybt_buy_supply` VALUES ('1', '7', '杀虫剂', '3', '15647384323', '商品描述', '商品备注', '/upload/20180809/f9f5f3aca8d5ffe10bca7811fa2ad28f.png,/upload/20180809/3f8c890d427a6bdda05e3bb719f7d882.png', '1', '1533799142', '1533799142', '1', '0');
INSERT INTO `ybt_buy_supply` VALUES ('2', '10', '12', '12', '184343434343', '请描述一下需要的产品23321', '请填写备注信息2321321321', '/upload/20180810/25ff95336bc167b59aab1ea4e5f9ac69.jpg', '1', '1533867340', '1534338703', '1', '8');
INSERT INTO `ybt_buy_supply` VALUES ('3', '10', '12', '12', '184343434343', '请描述一下需要的产品23321', '请填写备注信息2321321321', '/upload/20180810/56a21ede5f93cd43eba2421e65ab965c.jpg', '1', '1533867346', '1533867346', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('4', '9', '123', '123', '15163949825', '', '', '/upload/20180810/048d3c4639cff3b336c76a8cd3ec29e4.jpg', '1', '1533867669', '1533867669', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('5', '9', '123', '123', '15163949825', '', '', '/upload/20180810/00ef8cb69512d7ffdbce1018533e0027.jpg', '1', '1533867729', '1533867729', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('6', '10', '12321321', '2132321', '1232323213213', '请描述一下需要的产品', '请填写备注信息', '/upload/20180810/3251180fc575b353b90dc9c1916b6420.jpg,/upload/20180810/3f270172fcf0a9934ef7b5cb588642e6.jpg,/upload/20180810/33161e672e23e40d865185cbfea5c6e9.jpg', '1', '1533867879', '1533867879', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('7', '9', '123', '123', '15163949825', '', '', '/upload/20180810/67ae1f9713e616a17cd9879c092e3af2.jpg', '1', '1533867931', '1533867931', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('8', '9', '123', '123', '15163949825', '', '', '/upload/20180810/e57107993def133bc7f56b984da61a8f.jpg', '1', '1533867944', '1533867944', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('9', '9', '123', '123', '15163949825', '', '', '/upload/20180810/fc4c339ef5382ff53bb742a40aaf1256.jpg', '1', '1533869913', '1533869913', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('10', '9', '123', '123', '15163949825', '', '', '/upload/20180810/c291ae60031df9210656287963a7d988.jpg', '2', '1533869959', '1533869959', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('11', '9', '123', '123', '15163949825', '', '', '/upload/20180810/e0536610d31bd9050c0d3feaa278c008.jpg,/upload/20180810/d50aa7ce6a5ff217851f5d6a6ef75619.jpg,/upload/20180810/14c207c77bbc36a6a3ee1e184ffde4f0.jpg', '2', '1533870235', '1533870235', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('12', '9', '123', '123', '15163949825', '', '', '/upload/20180810/2e2c871bc39bb26ddd21bef13a0d9b37.jpg,/upload/20180810/8ac4ba1e689f2a61ccad2baeafbf0597.jpg', '2', '1533870453', '1533870453', '0', '0');
INSERT INTO `ybt_buy_supply` VALUES ('13', '9', '12', '12', '15163949828', '', '', '/upload/20180810/c8c34c8b3388c745dedcba3d75267f89.jpg', '2', '1533870700', '1533870700', '1', '0');

-- ----------------------------
-- Table structure for ybt_department
-- ----------------------------
DROP TABLE IF EXISTS `ybt_department`;
CREATE TABLE `ybt_department` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '顶级分类默认0',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=625 DEFAULT CHARSET=utf8 COMMENT='平台商品分类库';

-- ----------------------------
-- Records of ybt_department
-- ----------------------------
INSERT INTO `ybt_department` VALUES ('613', '植物生长调节剂', '0', '1533797156', '1533797156');
INSERT INTO `ybt_department` VALUES ('614', '杀线虫剂', '0', '1533797175', '1533797175');
INSERT INTO `ybt_department` VALUES ('615', '杀鼠剂', '0', '1533797183', '1533797183');
INSERT INTO `ybt_department` VALUES ('616', '其他', '0', '1533797191', '1533797191');
INSERT INTO `ybt_department` VALUES ('621', '生长类', '613', '1534128012', '1534128012');
INSERT INTO `ybt_department` VALUES ('622', '复合生物菌类', '614', '1534128045', '1534128045');
INSERT INTO `ybt_department` VALUES ('623', '敌鼠钠盐', '615', '1534128087', '1534128087');
INSERT INTO `ybt_department` VALUES ('624', '其他', '616', '1534128114', '1534128114');

-- ----------------------------
-- Table structure for ybt_keywords
-- ----------------------------
DROP TABLE IF EXISTS `ybt_keywords`;
CREATE TABLE `ybt_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='店铺推荐搜索关键字';

-- ----------------------------
-- Records of ybt_keywords
-- ----------------------------
INSERT INTO `ybt_keywords` VALUES ('5', '');

-- ----------------------------
-- Table structure for ybt_menu
-- ----------------------------
DROP TABLE IF EXISTS `ybt_menu`;
CREATE TABLE `ybt_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '节点名称',
  `url` varchar(255) NOT NULL COMMENT '模块',
  `display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0 不显示 1 显示',
  `describe` varchar(255) NOT NULL COMMENT '节点描述',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级目录',
  `level` int(11) NOT NULL COMMENT '目录等级',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10055 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_menu
-- ----------------------------
INSERT INTO `ybt_menu` VALUES ('1', '平台管理', 'admin', '1', '平台管理', '0', '1', '0', '1532942841');
INSERT INTO `ybt_menu` VALUES ('2', '系统管理', 'admin/Role', '1', '目录管理', '1', '2', '0', '1532942929');
INSERT INTO `ybt_menu` VALUES ('3', '角色管理', 'admin/Role/roleList', '1', '', '2', '3', '0', '0');
INSERT INTO `ybt_menu` VALUES ('5', '用户列表', 'admin/Admin/adminList', '1', '', '2', '3', '0', '1533268551');
INSERT INTO `ybt_menu` VALUES ('6', '目录管理', 'admin/Menu', '1', '目录管理', '1', '2', '0', '1520573832');
INSERT INTO `ybt_menu` VALUES ('7', '目录列表', 'admin/Menu/menuList', '1', '', '6', '3', '0', '0');
INSERT INTO `ybt_menu` VALUES ('10019', '商品管理', 'admin/Product', '1', '', '1', '2', '0', '1532566121');
INSERT INTO `ybt_menu` VALUES ('10020', '商品分类', 'admin/Category/index', '1', '平台商品分类管理', '10019', '3', '0', '1521427938');
INSERT INTO `ybt_menu` VALUES ('10050', '推荐搜索关键字', 'admin/Site/keywords', '1', '', '10013', '3', '1533195208', '1533195208');
INSERT INTO `ybt_menu` VALUES ('10013', '平台设置', 'admin/Site/index', '1', '', '1', '2', '0', '1523497124');
INSERT INTO `ybt_menu` VALUES ('10051', '销售订单', 'admin/Order/salerOrderList', '1', '销售人员查看自己名下客户订单', '10027', '3', '1533977110', '1533977110');
INSERT INTO `ybt_menu` VALUES ('10027', '订单管理', 'admin/Order', '1', '', '1', '2', '1522050858', '1533887304');
INSERT INTO `ybt_menu` VALUES ('10024', '商品库', 'admin/Product/index', '1', '', '10019', '3', '1521441329', '1521441329');
INSERT INTO `ybt_menu` VALUES ('10028', '平台全部订单', 'admin/Order/orderList', '1', '', '10027', '3', '1522050909', '1533977133');
INSERT INTO `ybt_menu` VALUES ('10053', '销售反馈', 'admin/FeedBack/saleFeed', '1', '销售查看名下客户反馈', '10047', '3', '1534325697', '1534325697');
INSERT INTO `ybt_menu` VALUES ('10043', '轮播图设置', 'admin/Site/swiperList', '1', '轮播设置', '10013', '3', '1532569813', '1532569813');
INSERT INTO `ybt_menu` VALUES ('10045', '商品属性', 'admin/Product/productProp', '1', '商品属性设置', '10019', '3', '1532919444', '1532919444');
INSERT INTO `ybt_menu` VALUES ('10046', '部门管理', 'admin/Role/department', '1', '部门管理', '2', '3', '1532943038', '1532943038');
INSERT INTO `ybt_menu` VALUES ('10047', '用户反馈管理', 'admin/FeedBack', '1', '', '1', '2', '1532943250', '1533887307');
INSERT INTO `ybt_menu` VALUES ('10048', '我的供应', 'admin/FeedBack/mySupply', '1', '', '10047', '3', '1532955504', '1532955542');
INSERT INTO `ybt_menu` VALUES ('10036', '客户管理', 'admin/Member/', '1', '', '1', '2', '1530583906', '1533887310');
INSERT INTO `ybt_menu` VALUES ('10037', '客户列表', 'admin/Member/index', '1', '', '10036', '3', '1530583969', '1534338827');
INSERT INTO `ybt_menu` VALUES ('10049', '我的订购', 'admin/FeedBack/myBuy', '1', '', '10047', '3', '1532955598', '1532955598');
INSERT INTO `ybt_menu` VALUES ('10040', '用户注册协议', 'admin/Site/register', '1', '', '10013', '3', '1530927355', '1530927355');
INSERT INTO `ybt_menu` VALUES ('10041', '关于我们', 'admin/Site/about_us', '1', '', '10013', '3', '1530929968', '1530929968');
INSERT INTO `ybt_menu` VALUES ('10042', '联系方式', 'admin/Site/contact_us', '1', '', '10013', '3', '1530930948', '1530930948');
INSERT INTO `ybt_menu` VALUES ('10052', '订单退款', 'admin/Order/refundMoney', '1', '客服确定收到退款货物之后，财务打款操作', '10027', '3', '1534234311', '1534234311');
INSERT INTO `ybt_menu` VALUES ('10054', '销售客户', 'admin/Member/saleList', '1', '销售名下自己的客户', '10036', '3', '1534343439', '1534343439');

-- ----------------------------
-- Table structure for ybt_order
-- ----------------------------
DROP TABLE IF EXISTS `ybt_order`;
CREATE TABLE `ybt_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `order_no` varchar(255) NOT NULL COMMENT '订单编号',
  `create_time` int(11) NOT NULL COMMENT '订单生成时间',
  `update_time` int(11) NOT NULL COMMENT '订单更新时间',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `receiver_name` varchar(255) NOT NULL COMMENT '收货人姓名',
  `address` varchar(255) NOT NULL COMMENT '收货地址',
  `receiver_telephone` varchar(255) NOT NULL COMMENT '收货人电话',
  `order_status` int(11) NOT NULL DEFAULT '0' COMMENT '订单状态 0 待支付 1 待发货 2 待收货 3 已完成 4 售后中 5 已关闭',
  `pay_type` int(11) NOT NULL COMMENT '支付方式 1 支付宝 2 微信 3 余额',
  `pay_status` int(11) NOT NULL COMMENT '支付状态 0 未支付 1 已支付',
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '订单是否删除 0 未删除 1 已删除',
  `pay_time` varchar(30) NOT NULL COMMENT '支付时间',
  `send_time` varchar(20) NOT NULL COMMENT '发货时间',
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发货 0 未发货 1 发货',
  `sure_time` varchar(20) NOT NULL COMMENT '收货时间',
  `total_money` decimal(20,2) NOT NULL COMMENT '订单总价',
  `remarks` varchar(200) NOT NULL COMMENT '订单备注',
  `trade_no` varchar(255) NOT NULL COMMENT '支付交易订单号',
  `send_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '物流费用 0',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT '平台交易号',
  `out_transaction_id` varchar(255) DEFAULT NULL COMMENT '微信支付单号，支付宝支付单号',
  `order_money` decimal(20,2) NOT NULL,
  `refund_money` decimal(20,2) DEFAULT '0.00' COMMENT '订单退款金额',
  `is_apply_refund` tinyint(1) DEFAULT '0' COMMENT '是否申请退款 0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='订单';

-- ----------------------------
-- Records of ybt_order
-- ----------------------------
INSERT INTO `ybt_order` VALUES ('9', '201808061518257952805', '1533539905', '1534321097', '7', '大镰刀', '山东省淄博市淄川区', '15898493432', '4', '0', '0', '0', '', '', '0', '2018-08-13 10:45', '19864.00', '降低分', '', '0.00', '', null, '19864.00', '18664.00', '1');
INSERT INTO `ybt_order` VALUES ('10', '201808061522139001454', '1533540133', '1534143184', '7', '大镰刀', '山东省淄博市淄川区', '15898493432', '2', '0', '0', '0', '', '2018-08-13 14:53', '1', '', '11665.00', '降低分', '', '0.00', '', null, '11665.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('11', '201808070940264708848', '1533606026', '1534149179', '7', '大镰刀', '山东省淄博市淄川区', '15898493432', '0', '0', '0', '0', '', '', '0', '', '1700.00', '降低分', '', '50.00', '', null, '1750.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('12', '201808071348472345676', '1533620927', '1533799906', '9', '12w', '北京北京东城区1233', '15523562589', '5', '0', '0', '0', '', '', '0', '', '5055.00', '测试', '', '60.00', '', null, '5115.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('13', '201808071619216627183', '1533629961', '1533629961', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '123.00', '', '', '0.00', '', null, '123.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('14', '201808071619303665599', '1533629970', '1533629970', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '123.00', '', '', '0.00', '', null, '123.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('15', '201808071619342431557', '1533629974', '1533802815', '9', '12w', '北京北京东城区1233', '15523562589', '5', '0', '0', '0', '', '', '0', '', '123.00', '', '', '0.00', '', null, '123.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('16', '201808090955386876455', '1533779738', '1533779738', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '4900.00', '', '', '0.00', '', null, '4900.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('17', '201808090959279357051', '1533779967', '1533799937', '9', '12w', '北京北京东城区1233', '15523562589', '5', '0', '0', '0', '', '', '0', '', '123.00', '', '', '0.00', null, null, '123.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('18', '201808091211558708286', '1533787915', '1533799921', '9', '12w', '北京北京东城区1233', '15523562589', '5', '0', '0', '0', '', '', '0', '', '123.00', '', '', '0.00', null, null, '123.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('19', '201808091524363058466', '1533799476', '1533799476', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '0', '0', '0', '0', '', '', '0', '', '2980.00', '请填写备注信息', '', '40.00', null, null, '3020.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('20', '201808091532308853969', '1533799950', '1533954898', '9', '12w', '北京北京东城区1233', '15523562589', '5', '0', '0', '0', '', '', '0', '', '123.00', '', '', '0.00', null, null, '123.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('21', '201808130942405628067', '1534124560', '1534124560', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '2523.00', '', '', '0.00', null, null, '2523.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('22', '201808130942569196838', '1534124576', '1534124576', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '234.00', '', '', '0.00', null, null, '234.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('23', '201808130943289805781', '1534124608', '1534124608', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '1569.00', '', '', '0.00', null, null, '1569.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('24', '201808130944049959061', '1534124645', '1534124645', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '860.00', '', '', '40.00', null, null, '900.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('25', '201808130944124882469', '1534124652', '1534124652', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '1070.00', '', '', '40.00', null, null, '1110.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('26', '201808131005314434469', '1534125931', '1534125931', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '220.00', '', '', '0.00', null, null, '220.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('27', '201808131555421165258', '1534146942', '1534146942', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '5.50', '', '', '0.00', null, null, '5.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('28', '201808131556052871781', '1534146965', '1534146965', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '5.00', '', '', '0.00', null, null, '5.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('29', '201808131616501333736', '1534148210', '1534148210', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '0', '0', '0', '0', '', '', '0', '', '10.50', '请填写备注信息', '', '0.00', null, null, '10.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('30', '201808131618073091312', '1534148287', '1534148287', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '1', '1', '1', '0', '2018-08-13 17:24', '', '0', '', '5.00', '请填写备注信息', '', '0.00', null, null, '5.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('31', '201808131618219545987', '1534148301', '1534148301', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '1', '1', '1', '0', '2018-08-13 17:24', '', '0', '', '5.50', '请填写备注信息', '', '0.00', null, null, '5.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('32', '201808131637143186447', '1534149434', '1534149434', '9', '12w', '北京北京东城区1233', '15523562589', '1', '1', '1', '0', '2018-08-13 17:24', '', '0', '', '5.00', '', '', '0.00', null, null, '5.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('33', '201808151132417436096', '1534303961', '1534303961', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '0', '0', '0', '0', '', '', '0', '', '5.50', '请填写备注信息', '', '0.00', null, null, '5.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('34', '201808151135368115133', '1534304136', '1534304136', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '2', '1', '1', '0', '2018-08-13 17:24', '', '0', '', '5.50', '请填写备注信息', '', '0.00', null, null, '5.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('35', '201808151137326223757', '1534304252', '1534304252', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '2', '1', '1', '0', '2018-08-13 17:24', '', '0', '', '5.50', '请填写备注信息', '', '0.00', null, null, '5.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('36', '201808151141306267061', '1534304490', '1534320239', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '3', '1', '1', '0', '2018-08-13 17:24', '', '0', '2018-08-15 16:03', '5.50', '请填写备注信息', '', '0.00', null, null, '5.50', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('37', '201808151142391581753', '1534304559', '1534304559', '9', '12w', '北京北京东城区1233', '15523562589', '0', '0', '0', '0', '', '', '0', '', '5.00', '', '', '0.00', null, null, '5.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('38', '201808151150001795791', '1534305000', '1534305000', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '0', '0', '0', '0', '', '', '0', '', '5.00', '请填写备注信息', '', '0.00', null, null, '5.00', '0.00', '0');
INSERT INTO `ybt_order` VALUES ('39', '201808151514545497792', '1534317294', '1534317294', '10', 'Mengdefeng', 'Yigaoshangjie', '18669608086', '0', '0', '0', '0', '', '', '0', '', '5.00', '请填写备注信息', '', '0.00', null, null, '5.00', '0.00', '0');

-- ----------------------------
-- Table structure for ybt_order_det
-- ----------------------------
DROP TABLE IF EXISTS `ybt_order_det`;
CREATE TABLE `ybt_order_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单商品详情id',
  `order_no` varchar(255) NOT NULL COMMENT '订单编号',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `product_id` int(11) NOT NULL,
  `prop_value_attr` varchar(255) NOT NULL DEFAULT '0' COMMENT '商品规格id 单规格默认0',
  `price` decimal(10,2) NOT NULL COMMENT '商品原价',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `thumb_img` varchar(255) NOT NULL COMMENT '图片地址',
  `prop_name` varchar(255) NOT NULL COMMENT '规格名称',
  `is_send` tinyint(4) NOT NULL DEFAULT '0' COMMENT '商品状态 0 代发货 1已发货',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_order_det
-- ----------------------------
INSERT INTO `ybt_order_det` VALUES ('3', '201808061518257952805', '7', '3468', '2|6|12', '2333.00', '8', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '200毫升*50瓶|2017年|无码|', '0', '1533539905');
INSERT INTO `ybt_order_det` VALUES ('4', '201808061518257952805', '7', '3468', '1|6|13', '200.00', '6', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '10毫升*100瓶|2017年|割标|', '0', '1533539905');
INSERT INTO `ybt_order_det` VALUES ('5', '201808061522139001454', '7', '3468', '2|6|12', '2333.00', '5', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '200毫升*50瓶|2017年|无码|', '0', '1533540133');
INSERT INTO `ybt_order_det` VALUES ('6', '201808070940264708848', '7', '3471', '1|6|12', '340.00', '5', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '10毫升*100瓶|2017年|无码', '0', '1533606026');
INSERT INTO `ybt_order_det` VALUES ('7', '201808071348472345676', '9', '3468', '1|6|12', '200.00', '1', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '10毫升*100瓶|2017年|无码|', '0', '1533620927');
INSERT INTO `ybt_order_det` VALUES ('8', '201808071348472345676', '9', '3469', '1|6', '123.00', '5', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533620927');
INSERT INTO `ybt_order_det` VALUES ('9', '201808071348472345676', '9', '3469', '2|6', '1212.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '200毫升*50瓶|2017年|', '0', '1533620927');
INSERT INTO `ybt_order_det` VALUES ('10', '201808071348472345676', '9', '3469', '2|7', '1234.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '200毫升*50瓶|2018年|', '0', '1533620927');
INSERT INTO `ybt_order_det` VALUES ('11', '201808071348472345676', '9', '3471', '2|6|12', '520.00', '3', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '200毫升*50瓶|2017年|无码', '0', '1533620927');
INSERT INTO `ybt_order_det` VALUES ('12', '201808071348472345676', '9', '3469', '1|7', '234.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2018年|', '0', '1533620927');
INSERT INTO `ybt_order_det` VALUES ('13', '201808071619216627183', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533629961');
INSERT INTO `ybt_order_det` VALUES ('14', '201808071619303665599', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533629970');
INSERT INTO `ybt_order_det` VALUES ('15', '201808071619342431557', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533629974');
INSERT INTO `ybt_order_det` VALUES ('16', '201808090955386876455', '9', '3468', '2|6|12', '2400.00', '1', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '200毫升*50瓶|2017年|无码', '0', '1533779738');
INSERT INTO `ybt_order_det` VALUES ('17', '201808090955386876455', '9', '3468', '2|6|13', '2500.00', '1', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '200毫升*50瓶|2017年|割标', '0', '1533779738');
INSERT INTO `ybt_order_det` VALUES ('18', '201808090959279357051', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533779967');
INSERT INTO `ybt_order_det` VALUES ('19', '201808091211558708286', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533787915');
INSERT INTO `ybt_order_det` VALUES ('20', '201808091524363058466', '10', '3471', '1|6|12', '340.00', '2', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '10毫升*100瓶|2017年|无码', '0', '1533799476');
INSERT INTO `ybt_order_det` VALUES ('21', '201808091524363058466', '10', '3468', '1|6|13', '2300.00', '1', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '10毫升*100瓶|2017年|割标', '0', '1533799476');
INSERT INTO `ybt_order_det` VALUES ('22', '201808091532308853969', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1533799950');
INSERT INTO `ybt_order_det` VALUES ('23', '201808130942405628067', '9', '3468', '2|6|12', '2400.00', '1', '杀菌灵', 'http://tsing.s1.natapp.cc/upload/20180801/537e1916bd925bfded0e03f8fae07ce8.jpg', '200毫升*50瓶|2017年|无码', '0', '1534124560');
INSERT INTO `ybt_order_det` VALUES ('24', '201808130942405628067', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1534124560');
INSERT INTO `ybt_order_det` VALUES ('25', '201808130942569196838', '9', '3469', '1|7', '234.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2018年|', '0', '1534124576');
INSERT INTO `ybt_order_det` VALUES ('26', '201808130943289805781', '9', '3469', '2|6', '1212.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '200毫升*50瓶|2017年|', '0', '1534124609');
INSERT INTO `ybt_order_det` VALUES ('27', '201808130943289805781', '9', '3469', '1|6', '123.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2017年|', '0', '1534124609');
INSERT INTO `ybt_order_det` VALUES ('28', '201808130943289805781', '9', '3469', '1|7', '234.00', '1', '页面肥料', 'http://tsing.s1.natapp.cc/upload/20180801/60539ddab494c8ab25e0a73a0de633ed.png', '10毫升*100瓶|2018年|', '0', '1534124609');
INSERT INTO `ybt_order_det` VALUES ('29', '201808130944049959061', '9', '3471', '1|6|12', '340.00', '1', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '10毫升*100瓶|2017年|无码', '0', '1534124645');
INSERT INTO `ybt_order_det` VALUES ('30', '201808130944049959061', '9', '3471', '2|6|12', '520.00', '1', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '200毫升*50瓶|2017年|无码', '0', '1534124645');
INSERT INTO `ybt_order_det` VALUES ('31', '201808130944124882469', '9', '3471', '3|6|12', '420.00', '1', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '150毫升*20瓶|2017年|无码', '0', '1534124652');
INSERT INTO `ybt_order_det` VALUES ('32', '201808130944124882469', '9', '3471', '3|7|12', '650.00', '1', '杀菌剂22', 'http://tsing.s1.natapp.cc/upload/20180801/a9b2d30f29a7aa8c8fb6897a97c74683.png', '150毫升*20瓶|2018年|无码', '0', '1534124652');
INSERT INTO `ybt_order_det` VALUES ('33', '201808131005314434469', '9', '3472', '1|6|12', '220.00', '1', '除虫及', 'http://tsing.s1.natapp.cc/upload/20180802/c56a09edd2de7653b620d266aee1bd40.png', '10毫升*100瓶|2017年|无码', '0', '1534125931');
INSERT INTO `ybt_order_det` VALUES ('34', '201808131555421165258', '9', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534146942');
INSERT INTO `ybt_order_det` VALUES ('35', '201808131556052871781', '9', '3476', '15', '5.00', '1', '康宽', 'http://tsing.s1.natapp.cc/upload/20180813/f5e284871badc3cb317a0e6fdc38eda4.png', '5毫升*800袋', '0', '1534146965');
INSERT INTO `ybt_order_det` VALUES ('36', '201808131616501333736', '10', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534148210');
INSERT INTO `ybt_order_det` VALUES ('37', '201808131616501333736', '10', '3476', '15', '5.00', '1', '康宽', 'http://tsing.s1.natapp.cc/upload/20180813/f5e284871badc3cb317a0e6fdc38eda4.png', '5毫升*800袋', '0', '1534148210');
INSERT INTO `ybt_order_det` VALUES ('38', '201808131618073091312', '10', '3476', '15', '5.00', '1', '康宽', 'http://tsing.s1.natapp.cc/upload/20180813/f5e284871badc3cb317a0e6fdc38eda4.png', '5毫升*800袋', '0', '1534148287');
INSERT INTO `ybt_order_det` VALUES ('39', '201808131618219545987', '10', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534148302');
INSERT INTO `ybt_order_det` VALUES ('40', '201808131637143186447', '9', '3476', '15', '5.00', '1', '康宽', 'http://tsing.s1.natapp.cc/upload/20180813/f5e284871badc3cb317a0e6fdc38eda4.png', '5毫升*800袋', '0', '1534149434');
INSERT INTO `ybt_order_det` VALUES ('41', '201808151132417436096', '10', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534303961');
INSERT INTO `ybt_order_det` VALUES ('42', '201808151135368115133', '10', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534304136');
INSERT INTO `ybt_order_det` VALUES ('43', '201808151137326223757', '10', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534304252');
INSERT INTO `ybt_order_det` VALUES ('44', '201808151141306267061', '10', '3475', '15', '5.50', '1', '康宽 ', 'http://tsing.s1.natapp.cc/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '5毫升*800袋', '0', '1534304490');
INSERT INTO `ybt_order_det` VALUES ('45', '201808151142391581753', '9', '3477', '19', '5.00', '1', '普尊', 'http://tsing.s1.natapp.cc/upload/20180813/7471d48237c04883480e01d34b443e0d.png', '15克*400袋', '0', '1534304559');
INSERT INTO `ybt_order_det` VALUES ('46', '201808151150001795791', '10', '3476', '15', '5.00', '1', '康宽', 'http://tsing.s1.natapp.cc/upload/20180813/f5e284871badc3cb317a0e6fdc38eda4.png', '5毫升*800袋', '0', '1534305000');
INSERT INTO `ybt_order_det` VALUES ('47', '201808151514545497792', '10', '3477', '19', '5.00', '1', '普尊', 'http://tsing.s1.natapp.cc/upload/20180813/7471d48237c04883480e01d34b443e0d.png', '15克*400袋', '0', '1534317294');

-- ----------------------------
-- Table structure for ybt_order_refund
-- ----------------------------
DROP TABLE IF EXISTS `ybt_order_refund`;
CREATE TABLE `ybt_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '退款id',
  `refund_type` tinyint(4) NOT NULL COMMENT '退款类型',
  `order_no` varchar(255) NOT NULL COMMENT '订单编号',
  `refund_detail` text NOT NULL COMMENT '退款详情',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '退款申请状态 0 已申请 1 处理中 2 处理完成',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `refund_address` varchar(255) NOT NULL COMMENT '退货地址',
  `refund_name` varchar(255) NOT NULL,
  `refund_telephone` varchar(255) NOT NULL,
  `send_no` varchar(255) NOT NULL COMMENT '快递单号',
  `receive_info` varchar(500) NOT NULL COMMENT '收款信息',
  `is_get` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否收到货 0 未 1 是',
  `image` varchar(255) NOT NULL COMMENT '退款凭证',
  `pay_time` datetime NOT NULL COMMENT '退款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_order_refund
-- ----------------------------
INSERT INTO `ybt_order_refund` VALUES ('1', '2', '201808061518257952805', '3', '2', '1534232546', '1534321097', '山东省淄博市大镰刀农业', '窦盈盈', '19989898989', '4523423523424', '张三，支付宝3894343243', '1', '/upload/20180815/07c73dd29b3009951bbc12ba6776517f.png', '2018-08-15 00:00:00');

-- ----------------------------
-- Table structure for ybt_product
-- ----------------------------
DROP TABLE IF EXISTS `ybt_product`;
CREATE TABLE `ybt_product` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `cate_id` int(11) NOT NULL COMMENT '分类id',
  `cate_parent_id` int(11) NOT NULL COMMENT '分类父级id',
  `name` varchar(100) NOT NULL COMMENT '商品名称',
  `bname` varchar(255) NOT NULL COMMENT '商品扩充名称',
  `ord` varchar(255) NOT NULL COMMENT '排序序号',
  `is_index` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否首页显示  0 否 1 是',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是热卖商品 0 否 1 是',
  `web_title` varchar(255) DEFAULT NULL COMMENT '页面标题',
  `web_keywords` varchar(255) DEFAULT NULL COMMENT '页面关键字',
  `web_description` varchar(255) DEFAULT NULL COMMENT '页面描述',
  `load_id` varchar(255) NOT NULL COMMENT '农药登记证号',
  `component` varchar(255) NOT NULL COMMENT '农药成分',
  `enterprise` varchar(255) NOT NULL COMMENT '生产企业',
  `effect_ratio` varchar(255) NOT NULL COMMENT '有效成分含量',
  `produce_code` varchar(255) NOT NULL COMMENT '生产许可证',
  `dosage_form` varchar(255) NOT NULL COMMENT '剂型',
  `toxicity` varchar(255) NOT NULL COMMENT '毒性',
  `brand` varchar(255) NOT NULL COMMENT '品牌',
  `standard_number` varchar(255) NOT NULL COMMENT '农药产品标准证号',
  `prevent` varchar(255) NOT NULL COMMENT '防治对象',
  `cate_name` varchar(255) NOT NULL COMMENT '农药类型',
  `is_send` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否包邮 0否1是',
  `thumb_img` varchar(255) NOT NULL COMMENT '展示缩略图 1张',
  `swiper_img` text NOT NULL COMMENT '轮播展示图  最多5张',
  `web_detail` text NOT NULL COMMENT 'pc端详情',
  `mob_detail` text NOT NULL COMMENT '移动端详情',
  `send_fee` decimal(10,2) NOT NULL DEFAULT '20.00' COMMENT '运费 默认20元/件',
  `limit_num` int(11) NOT NULL DEFAULT '0' COMMENT '限购数量  0不限制',
  `sell_num` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3479 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_product
-- ----------------------------
INSERT INTO `ybt_product` VALUES ('3475', '617', '609', '康宽 ', '杀虫剂', '0', '1', '0', '5毫升康宽', '杀虫剂 康宽 杜邦', '', 'PD20100677', '氯虫苯甲酰胺', '美国富美实公司', '200克/升', 'HNP31060-A8016', '5ml', '微毒', '杜邦', 'Q/GHFQ83-2014', '稻纵卷叶螟 二化螟 玉米螟', '杀虫剂', '0', '/upload/20180813/65a39fea92e682bc967309255ffc6709.png', '/upload/20180813/f6af0893b5ccb4473a705c27022de9ee.png', '<p><strong>产品性能</strong></p><p>本品为酰胺类新型内吸杀虫剂，具有独特的作用机理，胃毒为主，兼具触杀，对鳞翅目害虫。兼具高渗透性、高传导性、高化学稳定性、高杀虫活性和导致害虫立即停止取食等作用特点。<br/>作用机理：通过激活昆虫鱼尼丁（肌肉）受体。过度释放细胞内钙库中的钙离子，导致昆虫瘫痪死亡,对鳞翅目害虫的幼虫活性高，杀虫谱广，持效性好。根据目前的试验结果对靶标害虫的活性比其它产品高出10－100倍.并且可以导致某些鳞翅目昆虫交配过程紊乱，研究证明其能降低多种夜蛾科害虫的产卵率。</p><p><br/></p><p><strong>用量用法</strong></p><table><tbody><tr class=\"firstRow\"><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>作物(或范围)&nbsp;&nbsp; <br/></strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>防治对象</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>制剂用药量</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>使用方法</strong></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">菜用大豆</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">豆荚螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6-12毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾<br/></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">甘蔗</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">小地老虎</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.7-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉花</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉铃虫</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.67-13.3毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">水稻</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">大螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">8.3-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">3-5毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr></tbody></table><p>&nbsp;</p><p><strong>注意事项</strong></p><p>（1）、由于该农药具有较强的渗透性，药剂能穿过茎部表皮细胞层进入木质部，从而沿木质部传导至未施药的其它部位。因此在田间作业中，用弥雾或细喷雾喷雾效果更好。但当气温高、田间蒸发量大时，应选择早上10点以前，下午4点以后用药，这样不仅可以减少用药液量，也可以更好的增加作物的受药液量和渗透性，有利提高防治效果。<br/>（2）、为避免该农药抗药性的产生，一季作物或一种害虫宜使用2-3次，每次间隔时间在15天以上。<br/>（3）、该农药在我国登记时还有不同的剂型、含量及适用作物，用户在不同的作物上应选用该农药的不同含量<br/>&nbsp;1.杀虫谱广<br/>&nbsp; 氯虫苯甲酰胺是一个全新的化学结构，对水稻，棉花，蔬菜，果树等多种作物上的咀嚼式口器害虫有。如水稻上的稻纵卷叶螟，二化螟，三化螟，大螟；棉花上的棉铃虫，斜纹叶蛾，甜菜夜蛾；蔬菜上的小菜蛾，菜青虫；果树上的梨小食心虫，桃小食心虫等多种害虫。</p><p>2.毒性极低<br/>&nbsp; 氯虫苯甲酰胺颠覆了杀虫剂有毒的概念，毒性比食盐更低，被称为“牛奶式的杀虫剂”，氯虫苯甲酰胺对哺乳动物，鱼类，鸟类毒性极低，几乎无影响。</p><p>3.具有强渗透性和快速传导特性<br/>&nbsp; 氯虫苯甲酰胺具有强渗透性和快速传导特性，药液喷在叶片表面，能迅速渗透进叶片内，落入水中的药液可以通过植物根部吸收进入植物的木质部，从而由木质部至下向上传导至作物各个部位。</p><p>4.氯虫苯甲酰胺长，一次施药，可长达20天.</p><p><br/></p><p><strong>中毒急救</strong></p><p>误吸入，应将病人立即移至空气流通处。皮肤接触，用清水清洗至少15分钟。眼睛接触，立即用大量清水清洗，还可向医生咨询。误服，如有不适，携此标签请医生诊治，对症治疗。</p><p><br/></p><p><strong>包装储存</strong></p><p>包装件应贮存在通风、干燥的库房中。贮运时，严防潮湿和日晒，不得与食物、种子、饲料混放混运。避免与皮肤、眼睛接触，防止由口鼻吸入。避免儿童接触，并加锁保存。</p><p>&nbsp;</p>', '<p><strong>产品性能</strong></p><p>本品为酰胺类新型内吸杀虫剂，具有独特的作用机理，胃毒为主，兼具触杀，对鳞翅目害虫。兼具高渗透性、高传导性、高化学稳定性、高杀虫活性和导致害虫立即停止取食等作用特点。<br/>作用机理：通过激活昆虫鱼尼丁（肌肉）受体。过度释放细胞内钙库中的钙离子，导致昆虫瘫痪死亡,对鳞翅目害虫的幼虫活性高，杀虫谱广，持效性好。根据目前的试验结果对靶标害虫的活性比其它产品高出10－100倍.并且可以导致某些鳞翅目昆虫交配过程紊乱，研究证明其能降低多种夜蛾科害虫的产卵率。</p><p><br/></p><p><strong>用量用法</strong></p><table><tbody><tr class=\"firstRow\"><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>作物(或范围)&nbsp;&nbsp; <br/></strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>防治对象</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>制剂用药量</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>使用方法</strong></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">菜用大豆</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">豆荚螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6-12毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾<br/></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">甘蔗</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">小地老虎</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.7-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉花</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉铃虫</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.67-13.3毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">水稻</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">大螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">8.3-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">3-5毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr></tbody></table><p>&nbsp;</p><p><strong>注意事项</strong></p><p>（1）、由于该农药具有较强的渗透性，药剂能穿过茎部表皮细胞层进入木质部，从而沿木质部传导至未施药的其它部位。因此在田间作业中，用弥雾或细喷雾喷雾效果更好。但当气温高、田间蒸发量大时，应选择早上10点以前，下午4点以后用药，这样不仅可以减少用药液量，也可以更好的增加作物的受药液量和渗透性，有利提高防治效果。<br/>（2）、为避免该农药抗药性的产生，一季作物或一种害虫宜使用2-3次，每次间隔时间在15天以上。<br/>（3）、该农药在我国登记时还有不同的剂型、含量及适用作物，用户在不同的作物上应选用该农药的不同含量<br/>&nbsp;1.杀虫谱广<br/>&nbsp; 氯虫苯甲酰胺是一个全新的化学结构，对水稻，棉花，蔬菜，果树等多种作物上的咀嚼式口器害虫有。如水稻上的稻纵卷叶螟，二化螟，三化螟，大螟；棉花上的棉铃虫，斜纹叶蛾，甜菜夜蛾；蔬菜上的小菜蛾，菜青虫；果树上的梨小食心虫，桃小食心虫等多种害虫。</p><p>2.毒性极低<br/>&nbsp; 氯虫苯甲酰胺颠覆了杀虫剂有毒的概念，毒性比食盐更低，被称为“牛奶式的杀虫剂”，氯虫苯甲酰胺对哺乳动物，鱼类，鸟类毒性极低，几乎无影响。</p><p>3.具有强渗透性和快速传导特性<br/>&nbsp; 氯虫苯甲酰胺具有强渗透性和快速传导特性，药液喷在叶片表面，能迅速渗透进叶片内，落入水中的药液可以通过植物根部吸收进入植物的木质部，从而由木质部至下向上传导至作物各个部位。</p><p>4.氯虫苯甲酰胺长，一次施药，可长达20天.</p><p><br/></p><p><strong>中毒急救</strong></p><p>误吸入，应将病人立即移至空气流通处。皮肤接触，用清水清洗至少15分钟。眼睛接触，立即用大量清水清洗，还可向医生咨询。误服，如有不适，携此标签请医生诊治，对症治疗。</p><p><br/></p><p><strong>包装储存</strong></p><p>包装件应贮存在通风、干燥的库房中。贮运时，严防潮湿和日晒，不得与食物、种子、饲料混放混运。避免与皮肤、眼睛接触，防止由口鼻吸入。避免儿童接触，并加锁保存。</p>', '0.00', '0', '0', '1534123790', '1534389405');
INSERT INTO `ybt_product` VALUES ('3476', '617', '609', '康宽', '杀虫剂', '0', '1', '0', '5毫升 康宽 杀虫剂', '5毫升 康宽 杀虫剂', '本品为酰胺类新型内吸杀虫剂，具有独特的作用机理，胃毒为主，兼具触杀，对鳞翅目害虫。兼具高渗透性、高传导性、高化学稳定性、高杀虫活性和导致害虫立即停止取食等作用特点。', 'PD20100677', '氯虫苯甲酰胺', '美国富美实公司', '200克/升', 'HNP31060-A8016', '5ml', '微毒', '杜邦', 'Q/GHFQ83-2014', '稻纵卷叶螟 二化螟 玉米螟', '杀虫剂', '0', '/upload/20180813/f5e284871badc3cb317a0e6fdc38eda4.png', '/upload/20180813/5599a32e0d1332553371426b4c1cdf03.png,/upload/20180813/54a865fd37425ace1c975e373db1e982.png', '<p><strong>产品性能</strong></p><p>本品为酰胺类新型内吸杀虫剂，具有独特的作用机理，胃毒为主，兼具触杀，对鳞翅目害虫。兼具高渗透性、高传导性、高化学稳定性、高杀虫活性和导致害虫立即停止取食等作用特点。<br/>作用机理：通过激活昆虫鱼尼丁（肌肉）受体。过度释放细胞内钙库中的钙离子，导致昆虫瘫痪死亡,对鳞翅目害虫的幼虫活性高，杀虫谱广，持效性好。根据目前的试验结果对靶标害虫的活性比其它产品高出10－100倍.并且可以导致某些鳞翅目昆虫交配过程紊乱，研究证明其能降低多种夜蛾科害虫的产卵率。</p><p><br/></p><p><strong>用量用法</strong></p><table><tbody><tr class=\"firstRow\"><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>作物(或范围)&nbsp;&nbsp; <br/></strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>防治对象</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>制剂用药量</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>使用方法</strong></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">菜用大豆</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">豆荚螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6-12毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾<br/></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">甘蔗</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">小地老虎</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.7-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉花</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉铃虫</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.67-13.3毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">水稻</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">大螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">8.3-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">3-5毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr></tbody></table><p>&nbsp;</p><p><strong>注意事项</strong></p><p>（1）、由于该农药具有较强的渗透性，药剂能穿过茎部表皮细胞层进入木质部，从而沿木质部传导至未施药的其它部位。因此在田间作业中，用弥雾或细喷雾喷雾效果更好。但当气温高、田间蒸发量大时，应选择早上10点以前，下午4点以后用药，这样不仅可以减少用药液量，也可以更好的增加作物的受药液量和渗透性，有利提高防治效果。<br/>（2）、为避免该农药抗药性的产生，一季作物或一种害虫宜使用2-3次，每次间隔时间在15天以上。<br/>（3）、该农药在我国登记时还有不同的剂型、含量及适用作物，用户在不同的作物上应选用该农药的不同含量<br/>&nbsp;1.杀虫谱广<br/>&nbsp; 氯虫苯甲酰胺是一个全新的化学结构，对水稻，棉花，蔬菜，果树等多种作物上的咀嚼式口器害虫有。如水稻上的稻纵卷叶螟，二化螟，三化螟，大螟；棉花上的棉铃虫，斜纹叶蛾，甜菜夜蛾；蔬菜上的小菜蛾，菜青虫；果树上的梨小食心虫，桃小食心虫等多种害虫。</p><p>2.毒性极低<br/>&nbsp; 氯虫苯甲酰胺颠覆了杀虫剂有毒的概念，毒性比食盐更低，被称为“牛奶式的杀虫剂”，氯虫苯甲酰胺对哺乳动物，鱼类，鸟类毒性极低，几乎无影响。</p><p>3.具有强渗透性和快速传导特性<br/>&nbsp; 氯虫苯甲酰胺具有强渗透性和快速传导特性，药液喷在叶片表面，能迅速渗透进叶片内，落入水中的药液可以通过植物根部吸收进入植物的木质部，从而由木质部至下向上传导至作物各个部位。</p><p>4.氯虫苯甲酰胺长，一次施药，可长达20天.</p><p><br/></p><p><strong>中毒急救</strong></p><p>误吸入，应将病人立即移至空气流通处。皮肤接触，用清水清洗至少15分钟。眼睛接触，立即用大量清水清洗，还可向医生咨询。误服，如有不适，携此标签请医生诊治，对症治疗。</p><p><br/></p><p><strong>包装储存</strong></p><p>包装件应贮存在通风、干燥的库房中。贮运时，严防潮湿和日晒，不得与食物、种子、饲料混放混运。避免与皮肤、眼睛接触，防止由口鼻吸入。避免儿童接触，并加锁保存。</p><p>&nbsp;</p>', '<p><strong>产品性能</strong></p><p>本品为酰胺类新型内吸杀虫剂，具有独特的作用机理，胃毒为主，兼具触杀，对鳞翅目害虫。兼具高渗透性、高传导性、高化学稳定性、高杀虫活性和导致害虫立即停止取食等作用特点。<br/>作用机理：通过激活昆虫鱼尼丁（肌肉）受体。过度释放细胞内钙库中的钙离子，导致昆虫瘫痪死亡,对鳞翅目害虫的幼虫活性高，杀虫谱广，持效性好。根据目前的试验结果对靶标害虫的活性比其它产品高出10－100倍.并且可以导致某些鳞翅目昆虫交配过程紊乱，研究证明其能降低多种夜蛾科害虫的产卵率。</p><p><br/></p><p><strong>用量用法</strong></p><table><tbody><tr class=\"firstRow\"><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>作物(或范围)&nbsp;&nbsp; <br/></strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>防治对象</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>制剂用药量</strong></td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\"><strong>使用方法</strong></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">菜用大豆</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">豆荚螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6-12毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾<br/></td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">甘蔗</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">小地老虎</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.7-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉花</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">棉铃虫</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">6.67-13.3毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">水稻</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">大螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">8.3-10毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr><tr><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">玉米螟</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">3-5毫升/亩</td><td style=\"-ms-word-break: break-all;\" width=\"287\" valign=\"top\">喷雾</td></tr></tbody></table><p>&nbsp;</p><p><strong>注意事项</strong></p><p>（1）、由于该农药具有较强的渗透性，药剂能穿过茎部表皮细胞层进入木质部，从而沿木质部传导至未施药的其它部位。因此在田间作业中，用弥雾或细喷雾喷雾效果更好。但当气温高、田间蒸发量大时，应选择早上10点以前，下午4点以后用药，这样不仅可以减少用药液量，也可以更好的增加作物的受药液量和渗透性，有利提高防治效果。<br/>（2）、为避免该农药抗药性的产生，一季作物或一种害虫宜使用2-3次，每次间隔时间在15天以上。<br/>（3）、该农药在我国登记时还有不同的剂型、含量及适用作物，用户在不同的作物上应选用该农药的不同含量<br/>&nbsp;1.杀虫谱广<br/>&nbsp; 氯虫苯甲酰胺是一个全新的化学结构，对水稻，棉花，蔬菜，果树等多种作物上的咀嚼式口器害虫有。如水稻上的稻纵卷叶螟，二化螟，三化螟，大螟；棉花上的棉铃虫，斜纹叶蛾，甜菜夜蛾；蔬菜上的小菜蛾，菜青虫；果树上的梨小食心虫，桃小食心虫等多种害虫。</p><p>2.毒性极低<br/>&nbsp; 氯虫苯甲酰胺颠覆了杀虫剂有毒的概念，毒性比食盐更低，被称为“牛奶式的杀虫剂”，氯虫苯甲酰胺对哺乳动物，鱼类，鸟类毒性极低，几乎无影响。</p><p>3.具有强渗透性和快速传导特性<br/>&nbsp; 氯虫苯甲酰胺具有强渗透性和快速传导特性，药液喷在叶片表面，能迅速渗透进叶片内，落入水中的药液可以通过植物根部吸收进入植物的木质部，从而由木质部至下向上传导至作物各个部位。</p><p>4.氯虫苯甲酰胺长，一次施药，可长达20天.</p><p><br/></p><p><strong>中毒急救</strong></p><p>误吸入，应将病人立即移至空气流通处。皮肤接触，用清水清洗至少15分钟。眼睛接触，立即用大量清水清洗，还可向医生咨询。误服，如有不适，携此标签请医生诊治，对症治疗。</p><p><br/></p><p><strong>包装储存</strong></p><p>包装件应贮存在通风、干燥的库房中。贮运时，严防潮湿和日晒，不得与食物、种子、饲料混放混运。避免与皮肤、眼睛接触，防止由口鼻吸入。避免儿童接触，并加锁保存。</p><p>&nbsp;</p>', '0.00', '0', '0', '1534124567', '1534390766');
INSERT INTO `ybt_product` VALUES ('3477', '617', '609', '普尊', '氯虫苯甲酰胺 杀虫剂', '0', '1', '1', '普尊 15毫升 氯虫苯甲酰胺 杀虫剂', '普尊 氯虫苯甲酰胺 杀虫剂', '本产品为酰胺类新型内吸杀虫剂，胃毒为主，兼具触杀。害虫摄入后数分钟内即停止取食。按照推荐的技术使用，可有效防治甘蓝小菜蛾、甜菜夜蛾，花椰菜斜纹夜蛾，西瓜甜菜夜蛾、棉铃虫，辣椒甜菜夜蛾、棉铃虫和豇豆豆荚螟。', 'PD20110172', '氯虫苯甲酰胺', '美国杜邦公司', '6%', 'HNP31064-A8137  ', '15毫升', '低毒', '杜邦', 'Q/GHNE 41-2013', '斜纹夜蛾 小菜蛾 甜菜夜', '杀虫剂', '0', '/upload/20180813/7471d48237c04883480e01d34b443e0d.png', '/upload/20180813/d7adfadabab8b6f4ad20c93083c22198.png', '<p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">产品性能</span></span></strong></p><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\"><span style=\"font-family: 宋体;\">本产品为酰胺类新型内吸杀虫剂，胃毒为主，兼具触杀。害虫摄入后数分钟内即停止取食。按照推荐的技术使用，可有效防治甘蓝小菜蛾、甜菜夜蛾，花椰菜斜纹夜蛾，西瓜甜菜夜蛾、棉铃虫，辣椒甜菜夜蛾、棉铃虫和豇豆豆荚螟。</span></span></p><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">用法用量</span></span></strong><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><table width=\"568\" cellspacing=\"0\"><tbody><tr class=\"firstRow\" style=\"height: 35px;\"><td style=\"padding: 0px 7px; border: 1px solid windowtext; border-image: none; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">作物<span style=\"font-family: Calibri;\">(</span><span style=\"font-family: 宋体;\">或范围</span><span style=\"font-family: Calibri;\">) </span></span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">防治对象</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">制剂用药量 </span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">使用方法</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甘蓝</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甜菜夜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-55<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甘蓝</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">小菜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">45-54<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩 喷雾</span></span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">花椰菜</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">斜纹夜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">辣椒</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">&nbsp;</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">棉铃虫</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">辣椒</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甜菜夜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">西瓜</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">棉铃虫</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">45-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr></tbody></table><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><p><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">注意事项</span></span></strong></p><ol class=\" list-paddingleft-2\" style=\"list-style-type: decimal;\"><li><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">药液不要污染饮用水源。</span> 2<span style=\"font-family: 宋体;\">、本品对家蚕有毒，蚕室和桑园附近禁用。在有可能出现非靶标生物的区域小心使用。 </span><span style=\"font-family: Calibri;\">3</span><span style=\"font-family: 宋体;\">、包装物用后建议清洗三遍后，送指定地点回收，进行无害化处理。用过的容器妥善处理，不可做他用，也不可随意丢弃。 </span><span style=\"font-family: Calibri;\">4</span><span style=\"font-family: 宋体;\">、本品为</span><span style=\"font-family: Calibri;\">28</span><span style=\"font-family: 宋体;\">族杀虫剂，为更好地避免抗性的产生，一季作物，建议使用本品不得超过</span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">次。在靶标害虫危害的当代，若使用且能连续使用</span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">次。但在靶标害虫的下一代，推荐与不同作用机理即非</span><span style=\"font-family: Calibri;\">28</span><span style=\"font-family: 宋体;\">族化合物轮换使用。 </span><span style=\"font-family: Calibri;\">5</span><span style=\"font-family: 宋体;\">、禁止在河塘等水体内清洗施药用具。 </span><span style=\"font-family: Calibri;\">6</span><span style=\"font-family: 宋体;\">、开花植物花期禁用，施药期间应密切关注对附近蜂群的影响。 </span><span style=\"font-family: Calibri;\">7</span><span style=\"font-family: 宋体;\">、水产养殖区、河塘等水域附近禁用。 </span><span style=\"font-family: Calibri;\">8</span><span style=\"font-family: 宋体;\">、孕妇及哺乳期妇女应避免接触。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">中毒急救</span></span></strong></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">误吸入，如有不适可向医生咨询。皮肤接触，用清水清洗干净。眼睛接触，立即用大量清水清洗至少</span>15<span style=\"font-family: 宋体;\">分钟，还可向医生咨询。误服，如有不适，立即携此标签送医对症治疗。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">储存运输</span></span></strong></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">包装件应贮存在通风、干燥、阴凉、防雨的库房中。贮运时，严防潮湿和日晒，不得与食物、种子、饲料混放运输。避免与皮肤、眼睛接触，防止由口鼻吸入。避免儿童、无关人员及动物接触，并加锁保存。</span></span></p></li></ol><p><br/></p>', '<p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\"></span></span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">产品性能</span></span></strong></p><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\"><span style=\"font-family: 宋体;\">本产品为酰胺类新型内吸杀虫剂，胃毒为主，兼具触杀。害虫摄入后数分钟内即停止取食。按照推荐的技术使用，可有效防治甘蓝小菜蛾、甜菜夜蛾，花椰菜斜纹夜蛾，西瓜甜菜夜蛾、棉铃虫，辣椒甜菜夜蛾、棉铃虫和豇豆豆荚螟。</span></span></p><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">用法用量</span></span></strong><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><table width=\"568\" cellspacing=\"0\"><tbody><tr class=\"firstRow\" style=\"height: 35px;\"><td style=\"padding: 0px 7px; border: 1px solid windowtext; border-image: none; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">作物<span style=\"font-family: Calibri;\">(</span><span style=\"font-family: 宋体;\">或范围</span><span style=\"font-family: Calibri;\">) </span></span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">防治对象</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">制剂用药量 </span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">使用方法</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甘蓝</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甜菜夜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-55<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甘蓝</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">小菜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">45-54<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩 喷雾</span></span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">花椰菜</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">斜纹夜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">辣椒</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">&nbsp;</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">棉铃虫</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">辣椒</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">甜菜夜蛾</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">30-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">西瓜</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">棉铃虫</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">45-60<span style=\"font-family: 宋体;\">毫升</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr></tbody></table><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><p><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">注意事项</span></span></strong></p><ol class=\" list-paddingleft-2\" style=\"list-style-type: decimal;\"><li><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">药液不要污染饮用水源。</span> 2<span style=\"font-family: 宋体;\">、本品对家蚕有毒，蚕室和桑园附近禁用。在有可能出现非靶标生物的区域小心使用。 </span><span style=\"font-family: Calibri;\">3</span><span style=\"font-family: 宋体;\">、包装物用后建议清洗三遍后，送指定地点回收，进行无害化处理。用过的容器妥善处理，不可做他用，也不可随意丢弃。 </span><span style=\"font-family: Calibri;\">4</span><span style=\"font-family: 宋体;\">、本品为</span><span style=\"font-family: Calibri;\">28</span><span style=\"font-family: 宋体;\">族杀虫剂，为更好地避免抗性的产生，一季作物，建议使用本品不得超过</span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">次。在靶标害虫危害的当代，若使用且能连续使用</span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">次。但在靶标害虫的下一代，推荐与不同作用机理即非</span><span style=\"font-family: Calibri;\">28</span><span style=\"font-family: 宋体;\">族化合物轮换使用。 </span><span style=\"font-family: Calibri;\">5</span><span style=\"font-family: 宋体;\">、禁止在河塘等水体内清洗施药用具。 </span><span style=\"font-family: Calibri;\">6</span><span style=\"font-family: 宋体;\">、开花植物花期禁用，施药期间应密切关注对附近蜂群的影响。 </span><span style=\"font-family: Calibri;\">7</span><span style=\"font-family: 宋体;\">、水产养殖区、河塘等水域附近禁用。 </span><span style=\"font-family: Calibri;\">8</span><span style=\"font-family: 宋体;\">、孕妇及哺乳期妇女应避免接触。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">中毒急救</span></span></strong></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">误吸入，如有不适可向医生咨询。皮肤接触，用清水清洗干净。眼睛接触，立即用大量清水清洗至少</span>15<span style=\"font-family: 宋体;\">分钟，还可向医生咨询。误服，如有不适，立即携此标签送医对症治疗。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">储存运输</span></span></strong></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">包装件应贮存在通风、干燥、阴凉、防雨的库房中。贮运时，严防潮湿和日晒，不得与食物、种子、饲料混放运输。避免与皮肤、眼睛接触，防止由口鼻吸入。避免儿童、无关人员及动物接触，并加锁保存。</span></span></p></li></ol><p><br/></p>', '0.00', '0', '0', '1534131200', '1534390808');
INSERT INTO `ybt_product` VALUES ('3478', '619', '611', '阿泰灵', '氨基寡糖素+极细链格孢激活蛋白  杀菌剂', '0', '1', '1', '中保 阿泰灵 氨基寡糖素+极细链格孢激活蛋白 杀菌剂', '中保 阿泰灵 氨基寡糖素+极细链格孢激活蛋白 杀菌剂', '本品对番茄病毒病、烟草病毒病有较好防治效果。', 'PD20171725', '氨基寡糖素+极细链格孢激活蛋白', '中国农科院植保所廊坊农药中试厂', '6%', 'HNP13101-D5121	', '15毫升', '低毒', '中保', 'Q/ZBL 075-2012', '番茄病毒病、烟草病毒病…', '杀菌剂', '0', '/upload/20180813/1326a6ef0aee89113ae9de77a59724a8.png', '/upload/20180813/e74fed50d5ad65a80b32f2f19334469e.png,/upload/20180813/8c71b095d69422ddbcbdcad0a230fca1.png', '<p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">产品性能</span></span></strong></p><p style=\"margin: 5px 0px;\"><span style=\"font-family: 宋体; font-size: 16px;\"><span style=\"font-family: 宋体;\">本品对番茄病毒病、烟草病毒病有较好防治效果。</span></span></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">用法用量</span></span></strong><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><table width=\"568\" cellspacing=\"0\"><tbody><tr class=\"firstRow\" style=\"height: 35px;\"><td style=\"padding: 0px 7px; border: 1px solid windowtext; border-image: none; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">作物<span style=\"font-family: Calibri;\">(</span><span style=\"font-family: 宋体;\">或范围</span><span style=\"font-family: Calibri;\">) </span></span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">防治对象</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">制剂用药量 </span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">使用方法</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">番茄</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">病毒病</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">75-100<span style=\"font-family: 宋体;\">克</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">烟草</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">病毒病</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">75-100<span style=\"font-family: 宋体;\">克</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr></tbody></table><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><p><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">注意事项</span></span></strong></p><ol class=\" list-paddingleft-2\" style=\"list-style-type: decimal;\"><li><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">不得与碱性农药等物质混用，以免降低药效。</span>2<span style=\"font-family: 宋体;\">、本品使用时应穿戴防护服和手套，避免吸入药液。施药期间不可吃东西和饮水。施药后应及时洗手和洗脸及暴露部位皮肤。禁止在河塘等水体中清洗施药器具。</span><span style=\"font-family: Calibri;\">3</span><span style=\"font-family: 宋体;\">、孕妇和哺乳期妇女不得接触本品。 </span><span style=\"font-family: Calibri;\">4</span><span style=\"font-family: 宋体;\">、用过的包装物应妥善处理，不可做他用，也不可随意丢弃。</span><span style=\"font-family: Calibri;\">5</span><span style=\"font-family: 宋体;\">、使用中有任何不良反应请及时携标签就医。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">中毒急救</span></span></strong></p></li><li><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">皮肤污染或药液溅入眼睛，立即用大量清水冲洗至少</span>15<span style=\"font-family: 宋体;\">分钟。 </span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">、不慎吸入，立即将吸入者转移到空气新鲜及安静处，病情严重者请医生对症治疗。</span><span style=\"font-family: Calibri;\">3</span><span style=\"font-family: 宋体;\">、误服中毒，立即携此标签就医，无特效解毒剂，对症治疗。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">储存运输</span></span></strong></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">1<span style=\"font-family: 宋体;\">、本品应贮存在干燥、阴凉、通风、防雨处，远离火源或热源。 </span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">、置于儿童触及不到之处，并加锁。勿与食品、饮料、饲料、粮食等其他商品同贮同运。</span></span></p></li></ol><p><br/></p>', '<p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">产品性能</span></span></strong></p><p style=\"margin: 5px 0px;\"><span style=\"font-family: 宋体; font-size: 16px;\"><span style=\"font-family: 宋体;\">本品对番茄病毒病、烟草病毒病有较好防治效果。</span></span></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">用法用量</span></span></strong><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><table width=\"568\" cellspacing=\"0\"><tbody><tr class=\"firstRow\" style=\"height: 35px;\"><td style=\"padding: 0px 7px; border: 1px solid windowtext; border-image: none; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">作物<span style=\"font-family: Calibri;\">(</span><span style=\"font-family: 宋体;\">或范围</span><span style=\"font-family: Calibri;\">) </span></span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">防治对象</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">制剂用药量 </span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td><td style=\"border-width: 1px 1px 1px 0px; border-style: solid solid solid none; border-color: windowtext windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"top\"><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\">使用方法</span></strong></p><p style=\"margin: 5px 0px;\"><strong><span style=\"font-family: Calibri; font-size: 16px; font-weight: bold;\">&nbsp;</span></strong></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">番茄</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">病毒病</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">75-100<span style=\"font-family: 宋体;\">克</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr><tr><td style=\"border-width: 0px 1px 1px; border-style: none solid solid; border-color: rgb(0, 0, 0) windowtext windowtext; padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">烟草</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">病毒病</span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">75-100<span style=\"font-family: 宋体;\">克</span><span style=\"font-family: Calibri;\">/</span><span style=\"font-family: 宋体;\">亩</span></span></p></td><td style=\"border-width: 0px 1px 1px 0px; border-style: none solid solid none; border-color: rgb(0, 0, 0) windowtext windowtext rgb(0, 0, 0); padding: 0px 7px; background-color: transparent;\" width=\"142\" valign=\"center\"><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">喷雾</span></p><p style=\"margin: 5px 0px; text-align: center;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p></td></tr></tbody></table><p style=\"margin: 5px 0px;\"><span style=\"font-family: Calibri; font-size: 16px;\">&nbsp;</span></p><p><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">注意事项</span></span></strong></p><ol class=\" list-paddingleft-2\" style=\"list-style-type: decimal;\"><li><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">不得与碱性农药等物质混用，以免降低药效。</span>2<span style=\"font-family: 宋体;\">、本品使用时应穿戴防护服和手套，避免吸入药液。施药期间不可吃东西和饮水。施药后应及时洗手和洗脸及暴露部位皮肤。禁止在河塘等水体中清洗施药器具。</span><span style=\"font-family: Calibri;\">3</span><span style=\"font-family: 宋体;\">、孕妇和哺乳期妇女不得接触本品。 </span><span style=\"font-family: Calibri;\">4</span><span style=\"font-family: 宋体;\">、用过的包装物应妥善处理，不可做他用，也不可随意丢弃。</span><span style=\"font-family: Calibri;\">5</span><span style=\"font-family: 宋体;\">、使用中有任何不良反应请及时携标签就医。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">中毒急救</span></span></strong></p></li><li><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\"><span style=\"font-family: 宋体;\">皮肤污染或药液溅入眼睛，立即用大量清水冲洗至少</span>15<span style=\"font-family: 宋体;\">分钟。 </span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">、不慎吸入，立即将吸入者转移到空气新鲜及安静处，病情严重者请医生对症治疗。</span><span style=\"font-family: Calibri;\">3</span><span style=\"font-family: 宋体;\">、误服中毒，立即携此标签就医，无特效解毒剂，对症治疗。</span></span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">&nbsp;</span></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><strong><span style=\"font-family: 宋体; font-size: 16px; font-weight: bold;\"><span style=\"font-family: 宋体;\">储存运输</span></span></strong></p><p style=\"color: rgb(0, 0, 0); font-family: Calibri; font-size: 14px; font-style: normal; font-weight: normal; margin-top: 0px; margin-bottom: 0px;\"><span style=\"font-family: 宋体; font-size: 16px; font-weight: normal;\">1<span style=\"font-family: 宋体;\">、本品应贮存在干燥、阴凉、通风、防雨处，远离火源或热源。 </span><span style=\"font-family: Calibri;\">2</span><span style=\"font-family: 宋体;\">、置于儿童触及不到之处，并加锁。勿与食品、饮料、饲料、粮食等其他商品同贮同运。</span></span></p></li></ol><p><br/></p>', '0.00', '0', '0', '1534139640', '1534390807');

-- ----------------------------
-- Table structure for ybt_product_attr
-- ----------------------------
DROP TABLE IF EXISTS `ybt_product_attr`;
CREATE TABLE `ybt_product_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品规格id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `prop_value_attr` varchar(255) NOT NULL COMMENT '商品属性值id字符串',
  `prop_value_name` varchar(255) NOT NULL COMMENT '商品属性值字符串',
  `remain` int(11) NOT NULL COMMENT '商品库存',
  `limit_remain` int(11) NOT NULL COMMENT '库存预警',
  `price_one` decimal(10,2) NOT NULL COMMENT '商品单价',
  `price_comb` decimal(10,2) NOT NULL COMMENT '商品件价',
  `gno` varchar(255) DEFAULT NULL COMMENT '商品外部编码',
  `img_url` varchar(255) NOT NULL COMMENT '图片url',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='商品属性规格';

-- ----------------------------
-- Records of ybt_product_attr
-- ----------------------------
INSERT INTO `ybt_product_attr` VALUES ('37', '3477', '19', '15克*400袋', '100', '10', '5.00', '5.00', '0', '', '1534147741', '1534147741');
INSERT INTO `ybt_product_attr` VALUES ('38', '3475', '15', '5毫升*800袋', '10', '20', '5.50', '5.50', '0', '', '1534147760', '1534147760');
INSERT INTO `ybt_product_attr` VALUES ('39', '3476', '15', '5毫升*800袋', '100', '10', '5.00', '5.00', '0', '', '1534147790', '1534147790');
INSERT INTO `ybt_product_attr` VALUES ('40', '3478', '18', '15毫升*400袋', '100', '10', '2.50', '2.50', '0', '', '1534147814', '1534147814');

-- ----------------------------
-- Table structure for ybt_product_cate
-- ----------------------------
DROP TABLE IF EXISTS `ybt_product_cate`;
CREATE TABLE `ybt_product_cate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `ord` int(3) NOT NULL DEFAULT '50' COMMENT '分类排序',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '顶级分类默认0',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否首页显示分类  0 不显示 1 显示',
  `image` varchar(255) NOT NULL COMMENT '分类图标',
  `word_image` varchar(255) NOT NULL COMMENT '文字图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=625 DEFAULT CHARSET=utf8 COMMENT='平台商品分类库';

-- ----------------------------
-- Records of ybt_product_cate
-- ----------------------------
INSERT INTO `ybt_product_cate` VALUES ('609', '杀虫剂', '1', '0', '1533797108', '1533797108', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('610', '杀螨剂', '2', '0', '1533797127', '1533797127', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('611', '杀菌剂', '3', '0', '1533797136', '1533797136', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('612', '除草剂', '4', '0', '1533797147', '1533797147', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('613', '植物生长调节剂', '5', '0', '1533797156', '1533797156', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('614', '杀线虫剂', '6', '0', '1533797175', '1533797175', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('615', '杀鼠剂', '7', '0', '1533797183', '1533797183', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('616', '其他', '8', '0', '1533797191', '1533797191', '1', '', '');
INSERT INTO `ybt_product_cate` VALUES ('617', '有机磷类', '1', '609', '1534127154', '1534127154', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('618', ' 唑螨酯', '1', '610', '1534127914', '1534127914', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('619', '苯并咪唑类', '1', '611', '1534127957', '1534127957', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('620', '酰胺类', '1', '612', '1534127985', '1534127985', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('621', '生长类', '1', '613', '1534128012', '1534128012', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('622', '复合生物菌类', '1', '614', '1534128045', '1534128045', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('623', '敌鼠钠盐', '1', '615', '1534128087', '1534128087', '0', '', '');
INSERT INTO `ybt_product_cate` VALUES ('624', '其他', '1', '616', '1534128114', '1534128114', '0', '', '');

-- ----------------------------
-- Table structure for ybt_product_prop_name
-- ----------------------------
DROP TABLE IF EXISTS `ybt_product_prop_name`;
CREATE TABLE `ybt_product_prop_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品属性id',
  `prop_name_id` int(11) NOT NULL COMMENT '商品属性id',
  `prop_name` varchar(255) NOT NULL COMMENT '商品属性',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_product_prop_name
-- ----------------------------
INSERT INTO `ybt_product_prop_name` VALUES ('65', '5', '规格', '3477', '1534147741', '1534147741');
INSERT INTO `ybt_product_prop_name` VALUES ('66', '5', '规格', '3475', '1534147760', '1534147760');
INSERT INTO `ybt_product_prop_name` VALUES ('67', '5', '规格', '3476', '1534147790', '1534147790');
INSERT INTO `ybt_product_prop_name` VALUES ('68', '5', '规格', '3478', '1534147814', '1534147814');

-- ----------------------------
-- Table structure for ybt_product_prop_value
-- ----------------------------
DROP TABLE IF EXISTS `ybt_product_prop_value`;
CREATE TABLE `ybt_product_prop_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_name_id` int(11) NOT NULL COMMENT '商品属性id',
  `prop_value_id` int(11) NOT NULL COMMENT '商品属性值id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `prop_value` varchar(255) DEFAULT NULL COMMENT '商品属性值',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_product_prop_value
-- ----------------------------
INSERT INTO `ybt_product_prop_value` VALUES ('67', '5', '19', '3477', '15克*400袋', '1534147741', '1534147741');
INSERT INTO `ybt_product_prop_value` VALUES ('68', '5', '15', '3475', '5毫升*800袋', '1534147760', '1534147760');
INSERT INTO `ybt_product_prop_value` VALUES ('69', '5', '15', '3476', '5毫升*800袋', '1534147790', '1534147790');
INSERT INTO `ybt_product_prop_value` VALUES ('70', '5', '18', '3478', '15毫升*400袋', '1534147814', '1534147814');

-- ----------------------------
-- Table structure for ybt_prop_name
-- ----------------------------
DROP TABLE IF EXISTS `ybt_prop_name`;
CREATE TABLE `ybt_prop_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '特征量id',
  `prop_name` varchar(255) NOT NULL COMMENT '特征量',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_prop_name
-- ----------------------------
INSERT INTO `ybt_prop_name` VALUES ('5', '规格', '1', '1533797352', '1533797352');
INSERT INTO `ybt_prop_name` VALUES ('6', '是否带码', '0', '1533797374', '1533797374');

-- ----------------------------
-- Table structure for ybt_prop_value
-- ----------------------------
DROP TABLE IF EXISTS `ybt_prop_value`;
CREATE TABLE `ybt_prop_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '特征值id',
  `prop_id` int(11) NOT NULL COMMENT '特征值id',
  `prop_val` varchar(255) NOT NULL COMMENT '特征值',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_prop_value
-- ----------------------------
INSERT INTO `ybt_prop_value` VALUES ('15', '5', '5毫升*800袋', '1534121815', '1534121815');
INSERT INTO `ybt_prop_value` VALUES ('16', '5', '3克*800袋', '1534121830', '1534121830');
INSERT INTO `ybt_prop_value` VALUES ('17', '5', '15毫升*200袋', '1534128512', '1534128512');
INSERT INTO `ybt_prop_value` VALUES ('18', '5', '15毫升*400袋', '1534129345', '1534129345');
INSERT INTO `ybt_prop_value` VALUES ('19', '5', '15克*400袋', '1534129425', '1534129425');
INSERT INTO `ybt_prop_value` VALUES ('20', '5', '25公斤', '1534129496', '1534129496');
INSERT INTO `ybt_prop_value` VALUES ('21', '5', '30毫升+6*55克*16套', '1534129575', '1534129575');
INSERT INTO `ybt_prop_value` VALUES ('22', '5', '100毫升*50瓶', '1534129591', '1534129591');
INSERT INTO `ybt_prop_value` VALUES ('23', '5', '1升*12瓶', '1534129608', '1534129608');
INSERT INTO `ybt_prop_value` VALUES ('24', '5', '200克*50瓶', '1534129622', '1534129622');

-- ----------------------------
-- Table structure for ybt_role
-- ----------------------------
DROP TABLE IF EXISTS `ybt_role`;
CREATE TABLE `ybt_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
  `listorder` int(11) DEFAULT '0' COMMENT '角色排序',
  `describe` varchar(500) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `node_id` text NOT NULL COMMENT '可访问节点',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_role
-- ----------------------------
INSERT INTO `ybt_role` VALUES ('1', '超级管理员', '1', null, '1520558349', '1520558349', 'all');

-- ----------------------------
-- Table structure for ybt_shop_cart
-- ----------------------------
DROP TABLE IF EXISTS `ybt_shop_cart`;
CREATE TABLE `ybt_shop_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `prop_value_attr` varchar(255) NOT NULL DEFAULT '0' COMMENT '商品属性id值数组',
  `create_time` int(11) NOT NULL COMMENT '加入购物车时间',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=632 DEFAULT CHARSET=utf8 COMMENT='购物车表';

-- ----------------------------
-- Records of ybt_shop_cart
-- ----------------------------
INSERT INTO `ybt_shop_cart` VALUES ('594', '7', '3471', '1|7|12', '1533266037', '5', '1533266037');

-- ----------------------------
-- Table structure for ybt_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `ybt_sms_log`;
CREATE TABLE `ybt_sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '短信发送日志id',
  `telephone` varchar(255) NOT NULL COMMENT '手机号',
  `code` varchar(6) NOT NULL COMMENT '验证码',
  `create_time` int(11) NOT NULL COMMENT '发送时间',
  `update_time` int(11) NOT NULL COMMENT '使用时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '验证码是否使用 0 未使用 1 已使用',
  `type` int(11) NOT NULL COMMENT '验证码类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='短信发送日志';

-- ----------------------------
-- Records of ybt_sms_log
-- ----------------------------
INSERT INTO `ybt_sms_log` VALUES ('6', '18669608086', '880853', '1532933481', '1532933481', '0', '1');
INSERT INTO `ybt_sms_log` VALUES ('2', '15554832010', '609381', '1532529041', '1532531046', '1', '1');
INSERT INTO `ybt_sms_log` VALUES ('4', '15163949828', '128811', '1532745475', '1532745493', '1', '1');
INSERT INTO `ybt_sms_log` VALUES ('5', '15554832010', '497320', '1532912489', '1532912739', '1', '2');
INSERT INTO `ybt_sms_log` VALUES ('7', '18669608086', '120956', '1532997984', '1532997984', '0', '1');
INSERT INTO `ybt_sms_log` VALUES ('8', '18669608086', '881127', '1532998123', '1532998138', '1', '1');
INSERT INTO `ybt_sms_log` VALUES ('9', '15163949828', '497265', '1533628507', '1533628507', '0', '3');
INSERT INTO `ybt_sms_log` VALUES ('10', '18953305050', '804251', '1534233595', '1534233595', '0', '1');
INSERT INTO `ybt_sms_log` VALUES ('11', '18953305050', '521682', '1534233703', '1534233703', '0', '1');

-- ----------------------------
-- Table structure for ybt_swiper
-- ----------------------------
DROP TABLE IF EXISTS `ybt_swiper`;
CREATE TABLE `ybt_swiper` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL COMMENT '图片地址',
  `target_url` varchar(255) DEFAULT NULL COMMENT '目标url',
  `ord` int(11) NOT NULL DEFAULT '0' COMMENT '排序编号',
  `content` text COMMENT '轮c播详情',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='首页轮播图';

-- ----------------------------
-- Records of ybt_swiper
-- ----------------------------

-- ----------------------------
-- Table structure for ybt_user
-- ----------------------------
DROP TABLE IF EXISTS `ybt_user`;
CREATE TABLE `ybt_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_name` varchar(255) NOT NULL COMMENT '用户昵称',
  `telephone` varchar(255) NOT NULL COMMENT '手机号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `head_img` varchar(255) NOT NULL COMMENT '用户头像',
  `create_time` int(11) DEFAULT NULL COMMENT '会员注册时间',
  `vip_code` varchar(255) NOT NULL COMMENT '会员邀请码',
  `open_id` varchar(255) NOT NULL COMMENT '开放平台id',
  `lock_mob` varchar(11) DEFAULT NULL COMMENT '绑定手机号',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='会员列表';

-- ----------------------------
-- Records of ybt_user
-- ----------------------------
INSERT INTO `ybt_user` VALUES ('7', '青青', '15554832010', 'e10adc3949ba59abbe56e057f20f883e', '/upload/headImg/8f14e45fceea167a5a36dedd4bea2543.png?time=1532998690', '1532531046', '5b70d62fb51c', '', null, '1533004146');
INSERT INTO `ybt_user` VALUES ('9', '魔镜', '15163949828', 'e10adc3949ba59abbe56e057f20f883e', '/upload/headImg/45c48cce2e2d7fbdea1afc51c7c6ad26.jpg?time=1533021986', '1532745493', '5b70d62fb51c6', '', null, '1534343354');
INSERT INTO `ybt_user` VALUES ('10', 'Xiaoguangmang', '18669608086', 'e10adc3949ba59abbe56e057f20f883e', '/upload/headImg/d3d9446802a44259755d38e6d163e820.jpg?time=1533084564', '1532998138', '5b70d62fb51c6', '', null, '1533084564');

-- ----------------------------
-- Table structure for ybt_user_address
-- ----------------------------
DROP TABLE IF EXISTS `ybt_user_address`;
CREATE TABLE `ybt_user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员id',
  `shop_id` int(11) NOT NULL COMMENT '店铺id',
  `province` varchar(255) NOT NULL COMMENT '省份',
  `city` varchar(255) DEFAULT NULL COMMENT '城市',
  `county` varchar(255) DEFAULT NULL COMMENT '县/区',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `user_name` varchar(255) NOT NULL COMMENT '收货人姓名',
  `user_telephone` varchar(255) NOT NULL COMMENT '收货人电话',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否是默认地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_user_address
-- ----------------------------
INSERT INTO `ybt_user_address` VALUES ('40', '7', '0', '山东省', '淄博市', '张店区', '金石晶城', '小豆', '15558636214', '1');
INSERT INTO `ybt_user_address` VALUES ('41', '7', '0', '山东省', '淄博市', '张店区', '金石晶城', '小豆', '15558636214', '0');
INSERT INTO `ybt_user_address` VALUES ('42', '7', '0', '山东省', '淄博市', '张店区', '金石晶城', '小豆', '15558636214', '0');
INSERT INTO `ybt_user_address` VALUES ('45', '9', '0', '北京', '北京', '东城区', '1233', '12w', '15523562589', '1');
INSERT INTO `ybt_user_address` VALUES ('44', '9', '0', '黑龙江', '大兴安岭地区', '漠河县', '4535856', '26yy', '13791566265', '0');
INSERT INTO `ybt_user_address` VALUES ('46', '10', '0', '山东省', '临沂市', '兰山区', 'Yigaoshangjie', 'Mengdefeng', '18669608086', '1');

-- ----------------------------
-- Table structure for ybt_web_about_us
-- ----------------------------
DROP TABLE IF EXISTS `ybt_web_about_us`;
CREATE TABLE `ybt_web_about_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '注册协议内容',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户注册协议';

-- ----------------------------
-- Records of ybt_web_about_us
-- ----------------------------
INSERT INTO `ybt_web_about_us` VALUES ('2', '', '1533886391', '1534390822');

-- ----------------------------
-- Table structure for ybt_web_contact_us
-- ----------------------------
DROP TABLE IF EXISTS `ybt_web_contact_us`;
CREATE TABLE `ybt_web_contact_us` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '平台联系电话',
  `telephone` varchar(12) NOT NULL COMMENT '客服电话',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_web_contact_us
-- ----------------------------
INSERT INTO `ybt_web_contact_us` VALUES ('1', '15344349089', '1533887199', '1533887206');

-- ----------------------------
-- Table structure for ybt_web_login_log
-- ----------------------------
DROP TABLE IF EXISTS `ybt_web_login_log`;
CREATE TABLE `ybt_web_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL COMMENT '模块名',
  `controller` varchar(255) NOT NULL COMMENT '控制器',
  `action` varchar(255) NOT NULL COMMENT '方法',
  `data` text NOT NULL COMMENT '请求参数',
  `create_time` int(11) NOT NULL COMMENT '生成时间',
  `login_ip` varchar(255) NOT NULL COMMENT '登陆ip',
  `admin_id` int(11) DEFAULT NULL COMMENT '用户操作id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_web_login_log
-- ----------------------------
INSERT INTO `ybt_web_login_log` VALUES ('50', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1532657739', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('51', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1532913104', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('52', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533024073', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('53', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"1234556\"}', '1533195095', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('54', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533195103', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('55', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533609730', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('56', 'admin', 'Pub', 'login', '[]', '1533611393', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('57', 'admin', 'Pub', 'login', '[]', '1533611407', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('58', 'admin', 'Pub', 'login', '[]', '1533611552', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('59', 'admin', 'Pub', 'login', '[]', '1533612633', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('60', 'admin', 'Pub', 'login', '[]', '1533612757', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('61', 'admin', 'Pub', 'login', '[]', '1533612782', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('62', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533612891', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('63', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533632263', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('64', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533633492', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('65', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533690166', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('66', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533860338', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('67', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533969136', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('68', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1533972499', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('69', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1533972502', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('70', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1533972517', '2130706433', '1');
INSERT INTO `ybt_web_login_log` VALUES ('71', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1534118837', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('72', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534118841', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('73', 'admin', 'Pub', 'login', '{\"name\":\"kefu\",\"password\":\"123456\"}', '1534121591', '2130706433', '1');
INSERT INTO `ybt_web_login_log` VALUES ('74', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1534127166', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('75', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534127169', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('76', 'admin', 'Pub', 'login', '{\"name\":\"kefu\",\"password\":\"123456\"}', '1534127378', '2130706433', '1');
INSERT INTO `ybt_web_login_log` VALUES ('77', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534127462', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('78', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1534234237', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('79', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123455\"}', '1534234240', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('80', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534234244', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('81', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1534314968', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('82', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534314972', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('83', 'admin', 'Pub', 'login', '{\"name\":\"kefu\",\"password\":\"123456\"}', '1534325833', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('84', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534334959', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('85', 'admin', 'Pub', 'login', '{\"name\":\"kefu\",\"password\":\"123456\"}', '1534335321', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('86', 'admin', 'Pub', 'login', '{\"name\":\"admin1\",\"password\":\"123456\"}', '1534344316', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('87', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"123456\"}', '1534380078', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('88', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534380081', '2130706433', null);
INSERT INTO `ybt_web_login_log` VALUES ('89', 'admin', 'Pub', 'login', '{\"name\":\"kefu\",\"password\":\"123456\"}', '1534381320', '2130706433', '1');
INSERT INTO `ybt_web_login_log` VALUES ('90', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534389301', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('91', 'admin', 'Pub', 'login', '{\"name\":\"kefu\",\"password\":\"123456\"}', '1534391210', '2130706433', '1');
INSERT INTO `ybt_web_login_log` VALUES ('92', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534391539', '2130706433', '8');
INSERT INTO `ybt_web_login_log` VALUES ('93', 'admin', 'Pub', 'login', '{\"name\":\"admin\",\"password\":\"12345\"}', '1534398119', '2130706433', null);

-- ----------------------------
-- Table structure for ybt_web_opera_log
-- ----------------------------
DROP TABLE IF EXISTS `ybt_web_opera_log`;
CREATE TABLE `ybt_web_opera_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL COMMENT '模块名',
  `controller` varchar(255) NOT NULL COMMENT '控制器',
  `action` varchar(255) NOT NULL COMMENT '方法',
  `data` text NOT NULL COMMENT '请求参数',
  `create_time` int(11) NOT NULL COMMENT '生成时间',
  `login_ip` varchar(255) NOT NULL COMMENT '登陆ip',
  `admin_id` int(11) DEFAULT NULL COMMENT '用户操作id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=241 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ybt_web_opera_log
-- ----------------------------
INSERT INTO `ybt_web_opera_log` VALUES ('168', 'admin', 'Menu', 'menuEdit', '{\"level\":\"2\",\"parent_id\":\"1\",\"name\":\"\\u5546\\u54c1\\u7ba1\\u7406\",\"url\":\"admin\\/Product\",\"display\":\"1\",\"describe\":\"\",\"id\":\"10019\"}', '1532566121', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('169', 'admin', 'Menu', 'menuEdit', '{\"level\":\"2\",\"parent_id\":\"1\",\"name\":\"\\u8ba2\\u5355\\u7ba1\\u7406\",\"url\":\"admin\\/Order\",\"display\":\"1\",\"describe\":\"\",\"id\":\"10027\"}', '1532566148', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('170', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10013\",\"name\":\"\\u8f6e\\u64ad\\u56fe\\u8bbe\\u7f6e\",\"url\":\"admin\\/Site\\/swiperList\",\"display\":\"1\",\"describe\":\"\\u8f6e\\u64ad\\u8bbe\\u7f6e\"}', '1532569813', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('171', 'admin', 'Category', 'categoryAdd', '{\"pid\":\"\",\"name\":\"\\u9664\\u8349\\u5242\",\"orderid\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/c8f9d0954c4c20da2ef1929a2b3b8593.png\"}', '1532677990', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('172', 'admin', 'Category', 'categoryAdd', '{\"pid\":\"\",\"name\":\"\\u9664\\u866b\\u5242\",\"orderid\":\"10\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/ca1106f6ef8f1466a31bcb54d74e847b.png\"}', '1532679508', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('173', 'admin', 'Category', 'categoryEdit', '{\"id\":\"571\",\"name\":\"\\u9664\\u866b\\u5242\",\"ord\":\"50\"}', '1532679517', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('174', 'admin', 'Category', 'categoryAdd', '{\"pid\":\"570\",\"name\":\"\\u65b0\\u70df\\u78b1\\u7c7b\\u6740\\u866b\\u5242\",\"ord\":\"20\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/9d070303b7ba8fb1b55f535327958f1e.png\"}', '1532680510', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('175', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"570\",\"name\":\"\\u65e0\\u724c\\u5b50\\u7684\\u9664\\u866b\\u5242\",\"ord\":\"60\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/097793886df884692af1790a3954d44c.png\"}', '1532680678', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('176', 'admin', 'Category', 'categoryEdit', '{\"id\":\"573\",\"name\":\"\\u65e0\\u724c\\u5b50\\u7684\\u9664\\u866b\\u5242\",\"ord\":\"60\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/16e35f8d9ed3c1a7bb1a613bd0893ca9.png\"}', '1532680716', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('177', 'admin', 'Category', 'delCate', '{\"ids\":\"572\"}', '1532680733', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('178', 'admin', 'Category', 'delCate', '{\"ids\":\"572\"}', '1532680921', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('179', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"570\",\"name\":\"\\u90a3\\u4e2a\\u6740\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/726e32d2370f0733058aef6923bb34ba.png\"}', '1532680944', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('180', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"570\",\"name\":\"\\u8fd9\\u4e2a\\u6740\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180727\\/ecc7215a969b1c92b0153d4770e76f68.png\"}', '1532680955', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('181', 'admin', 'Category', 'delCate', '{\"ids\":\"570\"}', '1532680998', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('182', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"571\",\"name\":\"\\u4e00\\u53f7\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1532706253', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('183', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"571\",\"name\":\"\\u4e8c\\u53f7\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1532706267', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('184', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"571\",\"name\":\"\\u4e09\\u53f7\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1532706276', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('185', 'admin', 'Category', 'delCate', '{\"ids\":\"571\"}', '1532709393', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('186', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u6740\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/f7227f69d9d8f63d57ca6ca2a1ab05b2.png\"}', '1532709422', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('187', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/65eafdc07d1f2fac8341c33728c008de.png\"}', '1532709444', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('188', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u9664\\u8349\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/49b6692b58445527fe27061c5d667c69.png\"}', '1532709688', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('189', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u6740\\u83cc\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/d4aab6cc1c91794587709d8980d6c144.png\"}', '1532709707', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('190', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u6740\\u87a8\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/5e643047620ff08a5d1521c601b5c269.png\"}', '1532709723', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('191', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u8c03\\u8282\\u5242\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/999850828634b369591e5c21104762fb.png\"}', '1532709752', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('192', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u53f6\\u9762\\u80a5\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/99c3e3228bd8c0f0db8edc9d6cb7dcbd.png\"}', '1532709772', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('193', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u6709\\u673a\\u80a5\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180728\\/75d69fb5a490804cdae29b59d607f627.png\"}', '1532709789', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('194', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"580\",\"name\":\"\\u65b0\\u70df\\u78b1\\u7c7b\\u6740\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1532709852', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('195', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"580\",\"name\":\"\\u7b2c\\u4e8c\\u79cd\",\"ord\":\"50\",\"file\":\"\"}', '1532709864', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('196', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"580\",\"name\":\"\\u7b2c\\u4e09\\u79cd\",\"ord\":\"50\",\"file\":\"\"}', '1532709870', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('197', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"581\",\"name\":\"\\u7b2c\\u4e00\\u79cd\",\"ord\":\"50\",\"file\":\"\"}', '1532709883', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('198', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"581\",\"name\":\"\\u7b2c\\u4e8c\\u79cd\",\"ord\":\"50\",\"file\":\"\"}', '1532709894', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('199', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"581\",\"name\":\"\\u7b2c\\u4e09\\u79cd\",\"ord\":\"50\",\"file\":\"\"}', '1532709900', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('200', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10019\",\"name\":\"\\u5546\\u54c1\\u5c5e\\u6027\",\"url\":\"admin\\/Product\\/ProductProp\",\"display\":\"1\",\"describe\":\"\\u5546\\u54c1\\u5c5e\\u6027\\u8bbe\\u7f6e\"}', '1532917625', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('201', 'admin', 'Menu', 'menuEdit', '{\"level\":\"3\",\"parent_id\":\"10019\",\"name\":\"\\u5546\\u54c1\\u5c5e\\u6027\",\"url\":\"admin\\/Product\\/productProp\",\"display\":\"1\",\"describe\":\"\\u5546\\u54c1\\u5c5e\\u6027\\u8bbe\\u7f6e\",\"id\":\"10044\"}', '1532919388', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('202', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10019\",\"name\":\"\\u5546\\u54c1\\u5c5e\\u6027\",\"url\":\"admin\\/Product\\/productProp\",\"display\":\"1\",\"describe\":\"\\u5546\\u54c1\\u5c5e\\u6027\\u8bbe\\u7f6e\"}', '1532919444', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('203', 'admin', 'Menu', 'menuEdit', '{\"level\":\"1\",\"parent_id\":\"0\",\"name\":\"\\u5e73\\u53f0\\u7ba1\\u7406\",\"url\":\"admin\",\"display\":\"1\",\"describe\":\"\\u5e73\\u53f0\\u7ba1\\u7406\",\"id\":\"1\"}', '1532942841', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('204', 'admin', 'Menu', 'menuEdit', '{\"level\":\"2\",\"parent_id\":\"1\",\"name\":\"\\u7cfb\\u7edf\\u7ba1\\u7406\",\"url\":\"admin\\/Role\",\"display\":\"1\",\"describe\":\"\\u76ee\\u5f55\\u7ba1\\u7406\",\"id\":\"2\"}', '1532942929', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('205', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"2\",\"name\":\"\\u90e8\\u95e8\\u7ba1\\u7406\",\"url\":\"admin\\/Role\\/department\",\"display\":\"1\",\"describe\":\"\\u90e8\\u95e8\\u7ba1\\u7406\"}', '1532943038', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('206', 'admin', 'Menu', 'menuEdit', '{\"level\":\"2\",\"parent_id\":\"1\",\"name\":\"\\u5ba2\\u6237\\u7ba1\\u7406\",\"url\":\"admin\\/Member\\/\",\"display\":\"1\",\"describe\":\"\",\"id\":\"10036\"}', '1532943187', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('207', 'admin', 'Menu', 'menuAdd', '{\"level\":\"2\",\"parent_id\":\"1\",\"name\":\"\\u7528\\u6237\\u53cd\\u9988\\u7ba1\\u7406\",\"url\":\"admin\\/FeedBack\",\"display\":\"1\",\"describe\":\"\"}', '1532943250', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('208', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10047\",\"name\":\"\\u6211\\u7684\\u4f9b\\u5e94\",\"url\":\"admin\\/Feed\",\"display\":\"1\",\"describe\":\"\"}', '1532955504', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('209', 'admin', 'Menu', 'menuEdit', '{\"level\":\"3\",\"parent_id\":\"10047\",\"name\":\"\\u6211\\u7684\\u4f9b\\u5e94\",\"url\":\"admin\\/FeedBack\\/mySupply\",\"display\":\"1\",\"describe\":\"\",\"id\":\"10048\"}', '1532955542', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('210', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10047\",\"name\":\"\\u6211\\u7684\\u8ba2\\u8d2d\",\"url\":\"admin\\/FeedBack\\/myBuy\",\"display\":\"1\",\"describe\":\"\"}', '1532955598', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('211', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10013\",\"name\":\"\\u63a8\\u8350\\u641c\\u7d22\\u5173\\u952e\\u5b57\",\"url\":\"admin\\/Site\\/keywords\",\"display\":\"1\",\"describe\":\"\"}', '1533195208', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('212', 'admin', 'Menu', 'menuEdit', '{\"level\":\"3\",\"parent_id\":\"2\",\"name\":\"\\u7528\\u6237\\u5217\\u8868\",\"url\":\"admin\\/Admin\\/adminList\",\"display\":\"1\",\"describe\":\"\",\"id\":\"5\"}', '1533268551', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('213', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u65b0\\u519c\\u836f\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180807\\/8a2a5707016154bb37ad9dbf78968349.png\"}', '1533610601', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('214', 'admin', 'Category', 'categoryEdit', '{\"id\":\"580\",\"name\":\"\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1533865417', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('215', 'admin', 'Category', 'categoryEdit', '{\"id\":\"580\",\"name\":\"\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1533865505', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('216', 'admin', 'Category', 'categoryEdit', '{\"id\":\"580\",\"name\":\"\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\"}', '1533865581', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('217', 'admin', 'Category', 'categoryAdd', '{\"parent_id\":\"\",\"name\":\"\\u5176\\u4ed6\",\"ord\":\"50\",\"file\":\"\"}', '1533865920', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('218', 'admin', 'Category', 'categoryEdit', '{\"id\":\"609\",\"name\":\"\\u5176\\u4ed6\",\"ord\":\"50\",\"file\":\"\",\"word_image\":\"\\/upload\\/20180810\\/3c5dfb7496b56ad742a91d56b07a82ea.png\"}', '1533866111', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('219', 'admin', 'Category', 'categoryEdit', '{\"id\":\"609\",\"name\":\"\\u5176\\u4ed6\",\"ord\":\"50\",\"file\":\"\",\"image\":\"\\/upload\\/20180810\\/6adeb5a4d469b84438ee98f342d024a1.png\"}', '1533866187', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('220', 'admin', 'Category', 'categoryEdit', '{\"id\":\"580\",\"name\":\"\\u9664\\u866b\\u5242\",\"ord\":\"50\",\"file\":\"\",\"word_image\":\"\\/upload\\/20180810\\/9072fe136f9940a639217d74fbc90e07.png\"}', '1533866200', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('221', 'admin', 'Category', 'delCate', '{\"ids\":\"609\"}', '1533866208', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('222', 'admin', 'Role', 'departmentEdit', '{\"id\":\"588\",\"name\":\"\\u4e1a\\u52a1\\u90e8\"}', '1533882499', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('223', 'admin', 'Role', 'departmentAdd', '{\"parent_id\":\"580\",\"name\":\"\\u884c\\u653f\\u90e8\"}', '1533882520', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('224', 'admin', 'Category', 'delCate', '{\"ids\":\"589\"}', '1533882527', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('225', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10027\",\"name\":\"\\u9500\\u552e\\u8ba2\\u5355\",\"url\":\"admin\\/Order\\/salerOrderList\",\"display\":\"1\",\"describe\":\"\\u9500\\u552e\\u4eba\\u5458\\u67e5\\u770b\\u81ea\\u5df1\\u540d\\u4e0b\\u5ba2\\u6237\\u8ba2\\u5355\"}', '1533977110', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('226', 'admin', 'Menu', 'menuEdit', '{\"level\":\"3\",\"parent_id\":\"10027\",\"name\":\"\\u5e73\\u53f0\\u5168\\u90e8\\u8ba2\\u5355\",\"url\":\"admin\\/Order\\/orderList\",\"display\":\"1\",\"describe\":\"\",\"id\":\"10028\"}', '1533977133', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('227', 'admin', 'Role', 'roleAdd', '{\"role_name\":\"\\u5ba2\\u670d\",\"describe\":\"\",\"0\":\"\",\"4\":\"\",\"node_id\":[\"1,10027,10051\"]}', '1534121570', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('228', 'admin', 'Category', 'delCate', '{\"ids\":\"587\"}', '1534127192', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('229', 'admin', 'Category', 'delCate', '{\"ids\":\"588\"}', '1534127194', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('230', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10027\",\"name\":\"\\u8ba2\\u5355\\u9000\\u6b3e\",\"url\":\"admin\\/Order\\/refundMoney\",\"display\":\"1\",\"describe\":\"\\u5ba2\\u670d\\u786e\\u5b9a\\u6536\\u5230\\u9000\\u6b3e\\u8d27\\u7269\\u4e4b\\u540e\\uff0c\\u8d22\\u52a1\\u6253\\u6b3e\\u64cd\\u4f5c\"}', '1534234310', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('231', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10047\",\"name\":\"\\u9500\\u552e\\u53cd\\u9988\",\"url\":\"admin\\/FeedBack\\/saleFeed\",\"display\":\"1\",\"describe\":\"\\u9500\\u552e\\u67e5\\u770b\\u540d\\u4e0b\\u5ba2\\u6237\\u53cd\\u9988\"}', '1534325697', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('232', 'admin', 'Role', 'roleEdit', '{\"role_name\":\"\\u5ba2\\u670d\",\"describe\":\"\",\"0\":\"\",\"4\":\"\",\"node_id\":[\"1,10027,10051\",\"1,10047,10053\"],\"5\":\"\",\"id\":\"11\"}', '1534325854', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('233', 'admin', 'Menu', 'menuEdit', '{\"level\":\"3\",\"parent_id\":\"10036\",\"name\":\"\\u5ba2\\u6237\\u5217\\u8868\",\"url\":\"admin\\/Member\\/index\",\"display\":\"1\",\"describe\":\"\",\"id\":\"10037\"}', '1534338827', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('234', 'admin', 'Menu', 'menuAdd', '{\"level\":\"3\",\"parent_id\":\"10036\",\"name\":\"\\u9500\\u552e\\u5ba2\\u6237\",\"url\":\"admin\\/Member\\/saleList\",\"display\":\"1\",\"describe\":\"\\u9500\\u552e\\u540d\\u4e0b\\u81ea\\u5df1\\u7684\\u5ba2\\u6237\"}', '1534343439', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('235', 'admin', 'Role', 'roleEdit', '{\"role_name\":\"\\u5ba2\\u670d\",\"describe\":\"\",\"0\":\"\",\"4\":\"\",\"node_id\":[\"1,10027,10051\",\"1,10047,10053\",\"1,10036,10054\"],\"5\":\"\",\"6\":\"\",\"id\":\"11\"}', '1534343862', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('236', 'admin', 'Role', 'roleDel', '{\"roleId\":\"5\"}', '1534391615', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('237', 'admin', 'Role', 'roleDel', '{\"roleId\":\"9\"}', '1534391617', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('238', 'admin', 'Role', 'roleDel', '{\"roleId\":\"10\"}', '1534391619', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('239', 'admin', 'Role', 'roleDel', '{\"roleId\":\"8\"}', '1534391621', '2130706433', '1');
INSERT INTO `ybt_web_opera_log` VALUES ('240', 'admin', 'Role', 'roleDel', '{\"roleId\":\"11\"}', '1534391624', '2130706433', '1');

-- ----------------------------
-- Table structure for ybt_web_register
-- ----------------------------
DROP TABLE IF EXISTS `ybt_web_register`;
CREATE TABLE `ybt_web_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '注册协议内容',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户注册协议';

-- ----------------------------
-- Records of ybt_web_register
-- ----------------------------
INSERT INTO `ybt_web_register` VALUES ('4', '', '1533885837', '1534390831');
