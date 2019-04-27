<?php
/**
 * 思迅操作类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 8:40
 */

namespace app\common\model;


class SixunOpera
{
    private $sqlserver;
    private $error;
    const OPERA_ID = "1001";
    const POSID = "0002";
    const PAY_WAY = "APP";

    public function __construct()
    {
        vendor('sqlserver.mssql');
        $this->sqlserver = new \mssql();
    }

    /**
     * 测试是否链接成功
     * @return mixed
     */
    function testConn()
    {
        return $this->sqlserver->constatus;
    }

    /**
     * 根据父级id获取子分类
     * @param string $parentId 为空是查询顶级分类
     * @return array
     */
    function getCategory($parentId = "")
    {
        return $this->sqlserver->getarr("select * from t_bd_item_cls where cls_parent='" . $parentId . "'");
    }

    /**
     * 同步产品分类
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function asyncCate()
    {

        $categoryModel = new Goodslibrarycate();
        //查询出所有的不作处理的思讯最顶级分类
        $catelist = $this->getCategory();
        //进行思讯二级、三级分类的处理，思讯二级对应这里一级，思讯三级对应这里二级
        foreach ($catelist as $k => $v) {
            //先是思讯二级分类
            $curcate = $this->getCategory($v['item_clsno']);
            if (count($curcate) > 0) {
                //有思讯二级的情况下----
                foreach ($curcate as $k2 => $v2) {
                    //将思讯二级当作这里的顶级写入
                    $data2['asyncid'] = trim(iconv('GBK', 'UTF-8', $v2['item_clsno']));
                    $data2['name'] = trim(iconv('GBK', 'UTF-8', $v2['item_clsname']));
                    $data2['parentid'] = 0;
                    $data2['pid'] = 0;
                    $exit_cate2 = $categoryModel->where('asyncid', 'eq', $data2['asyncid'])->find();
                    if (!$exit_cate2) {
                        unset($categoryModel['id']);
                        $categoryModel->allowField(true)->isUpdate(false)->save($data2);
                        $cate2Id = $categoryModel->getLastInsID();
                    } else {
                        $cate2Id = $exit_cate2['id'];
                    }
                    //对思讯三级进行判断处理
                    $curcate3 = $this->getCategory($v2['item_clsno']);
                    if (count($curcate3) > 0) {
                        //如果存在思讯三级，作为此处二级写入
                        foreach ($curcate3 as $k3 => $v3) {
                            $data3['asyncid'] = trim(iconv('GBK', 'UTF-8', $v3['item_clsno']));
                            $data3['name'] = trim(iconv('GBK', 'UTF-8', $v3['item_clsname']));
                            $data3['parentid'] = trim(iconv('GBK', 'UTF-8', $v2['item_clsno']));
                            $data3['pid'] = $cate2Id;
                            $exit_cate3 = $categoryModel->where('asyncid', 'eq', $data3['asyncid'])->find();
                            if (!$exit_cate3) {
                                unset($categoryModel['id']);
                                $categoryModel->allowField(true)->isUpdate(false)->save($data3);
                            }
                        }
                    } else {
                        //如果不存在思讯三级，生成一个思讯三级，作为这里的二级写入
                        $data3['asyncid'] = trim(iconv('GBK', 'UTF-8', $v2['item_clsno'])) . '01';
                        $data3['name'] = trim(iconv('GBK', 'UTF-8', $v2['item_clsname']));
                        $data3['parentid'] = trim(iconv('GBK', 'UTF-8', $v2['item_clsno']));
                        $data3['pid'] = $cate2Id;
                        $exit_cate3 = $categoryModel->where('asyncid', 'eq', $data3['asyncid'])->find();
                        if (!$exit_cate3) {
                            unset($categoryModel['id']);
                            $categoryModel->allowField(true)->isUpdate(false)->save($data3);
                        }

                    }
                }
            } else {
                //没有思讯二级的情况下--直接将思讯顶级当作思讯二级写入 并生成思讯三级 ------
                $data2['asyncid'] = trim(iconv('GBK', 'UTF-8', $v['item_clsno'])) . '01';
                $data2['name'] = trim(iconv('GBK', 'UTF-8', $v['item_clsname']));
                $data2['parentid'] = 0;
                $data2['pid'] = 0;
                $exit_cate2 = $categoryModel->where('asyncid', 'eq', $data2['asyncid'])->find();
                if (!$exit_cate2) {
                    unset($categoryModel['id']);
                    $categoryModel->allowField(true)->isUpdate(false)->save($data2);
                    $cate2Id = $categoryModel->getLastInsID();
                } else {
                    $cate2Id = $exit_cate2['id'];
                }
                $data3['asyncid'] = $data2['asyncid'] . '01';
                $data3['name'] = $data2['name'];
                $data3['parentid'] = $data2['asyncid'];
                $data3['pid'] = $cate2Id;
                $exit_cate3 = $categoryModel->where('asyncid', 'eq', $data3['asyncid'])->find();
                if (!$exit_cate3) {
                    unset($categoryModel['id']);
                    $categoryModel->allowField(true)->isUpdate(false)->save($data3);
                }
            }
        }
        return true;
    }

    /**
     * 同步平台商品库
     */
    function asyncGoods()
    {
        $goodsArr = $this->sqlserver->getarr("select * from t_bd_item_info a RIGHT JOIN t_im_branch_stock b ON a.item_no=b.item_no where b.stock_qty>0 and b.branch_no='000001'");
        $goodsItemNos = array();
        //统计所有商品货号
        foreach ($goodsArr as $item_no) {
            $goodsItemNos[] = trim($item_no['item_no']);
        }

        //获取所有组合商品编码
        $itemListTemp = $this->sqlserver->getarr("select comb_item_no from [dbo].[t_bd_item_combsplit] where [item_no] in ('" . join("','", $goodsItemNos) . "') ");
        $combine_no_arr = array();
        foreach ($itemListTemp as $item) {
            $combine_no_arr[] = $item['comb_item_no'];
        }
        $itemList = $this->sqlserver->getarr("select * from [dbo].[t_bd_item_info] where item_no in ('" . join("','", $combine_no_arr) . "') ");
        $goodsArr = array_merge($goodsArr, $itemList);
        //获取所有组合商品编码
        $cateModel = new Goodslibrarycate();
        $cateArr = $cateModel->where('pid', '>', 0)->column('asyncid, id');
        //循环处理添加商品
        foreach ($goodsArr as $item) {
            $item_no = trim(iconv('GBK', 'UTF-8', $item['item_no']));
            $item_clsno = trim(iconv('GBK', 'UTF-8', $item['item_clsno']));// 分类id
            $item_size = trim(iconv('GBK', 'UTF-8', $item['item_size']));//规格
            $unit_no = trim(iconv('GBK', 'UTF-8', $item['unit_no']));//单位
            $combine_sta = trim(iconv('GBK', 'UTF-8', $item['combine_sta']));//是否是组合商品
            if (strlen($item_clsno) == 2) {
                $item_clsno .= '0101';
            }
            if (strlen($item_clsno) == 4) {
                $item_clsno .= '01';
            }
            if (isset($cateArr[$item_clsno])) {
                $goodsModel = new Goodslibrary();
                $res = $goodsModel->where('gno', $item_no)->find();
                $data['gno'] = $item_no;
                $data['cateId'] = $cateArr[$item_clsno];
                $data['name'] = trim(iconv('GBK', 'UTF-8', $item['item_name']));
                $data['cost'] = trim(iconv('GBK', 'UTF-8', $item['sale_price']));
                $data['goodattr'] = $unit_no;
                $data['guige'] = $item_size;
                $data['combine_sta'] = $combine_sta;
                $data['img'] = join(',', getAllFiles(__UPLOAD__ . '/goodsimg/' . $item_no . '/ontop'));
                $instro = getAllFiles(__UPLOAD__ . '/goodsimg/' . $item_no . '/d');
                $instroImg = [];
                foreach ($instro as $ins) {
                    $instroImg[] = "<img src='" . $ins . "' style='width: 100%;'/>";
                }
                $data['instro'] = join('', $instroImg);
                if ($res) {
                    //更新商品信息
                    $goodsModel->isUpdate(true)->save($data, ['id' => $res['id']]);
                } else {
                    $goodsModel->allowField(true)->isUpdate(false)->save($data);
                }
            }
        }
        return true;
    }

    /**
     * 同步实体店商品至店铺
     * @param $shopNo 店铺编号
     * @param $shop_id 店铺id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function asyncGoodsToShop($shopNo, $shop_id)
    {
        if (!self::checkShopExist($shopNo)) {
            $this->error = '实体店铺不存在';
            return false;
        }

        $goodsArr = $this->sqlserver->getarr("select a.*,b.stock_qty from t_bd_item_info a RIGHT JOIN t_im_branch_stock b ON a.item_no=b.item_no where b.stock_qty>0 and b.branch_no='$shopNo'");
        $goodsItemNos = array();
        //统计所有商品货号
        foreach ($goodsArr as $item_no) {
            $goodsItemNos[] = trim($item_no['item_no']);
        }

        model('goods')->save(['is_live'=>0, 'count'=>0], ['gno'=>['not in', $goodsItemNos], 'shop_id'=>$shop_id, 'combine_sta'=>['neq', 1]]);

        //获取所有组合商品编码
        $itemListTemp = $this->sqlserver->getarr("select comb_item_no from [dbo].[t_bd_item_combsplit] where [item_no] in ('" . join("','", $goodsItemNos) . "') ");
        $combine_no_arr = array();
        foreach ($itemListTemp as $item) {
            $combine_no_arr[] = $item['comb_item_no'];
        }
        $itemList = $this->sqlserver->getarr("select * from [dbo].[t_bd_item_info] where item_no in ('" . join("','", $combine_no_arr) . "') ");
        $goodsArr = array_merge($goodsArr, $itemList);
        //获取分店商品价格
        $branch_price = $this->sqlserver->getarr("select item_no,sale_price from t_pc_branch_price where branch_no='$shopNo'");
        $bprice = array();
        foreach ($branch_price as $bp) {
            $gno = trim($bp['item_no']);
            $bprice["$gno"] = $bp['sale_price'];
        }
        //获取分店商品价格
        //获取店铺所选商品分类
        $cateModel = new ShopCate();
        $cateArr = $cateModel->where(['parent_id' => ['>', 0], 'shop_id' => $shop_id])->column('orignal_id, id');
        //循环处理添加商品
        foreach ($goodsArr as $item) {
            $item_no = trim(iconv('GBK', 'UTF-8', $item['item_no']));
            $item_clsno = trim(iconv('GBK', 'UTF-8', $item['item_clsno']));// 分类id
            $item_size = trim(iconv('GBK', 'UTF-8', $item['item_size']));//规格
            $unit_no = trim(iconv('GBK', 'UTF-8', $item['unit_no']));//单位
            $combine_sta = trim(iconv('GBK', 'UTF-8', $item['combine_sta']));//是否是组合商品
            $bulk_package = intval(iconv('GBK', 'UTF-8', $item['num3']));
            if (strlen($item_clsno) == 2) {
                $item_clsno .= '0101';
            }
            if (strlen($item_clsno) == 4) {
                $item_clsno .= '01';
            }
            if (isset($cateArr[$item_clsno])) {
                $goodsModel = new Goods();
                $res = $goodsModel->where(['gno' => $item_no, 'shop_id' => $shop_id])->find();
                $data = [];
                $data['gno'] = $item_no;
                $data['cate_id'] = $cateArr[$item_clsno];
                $data['name'] = trim(mb_convert_encoding($item['item_name'], 'UTF-8', 'GBK'));
//                $data['name'] = trim(iconv('GBK', 'UTF-8', $item['item_name']));
                $data['sale_price'] = trim(iconv('GBK', 'UTF-8', $item['sale_price']));
                //重新格式化活动价格
                $discount = model('shop')->where('id', $shop_id)->value('discount');
                $data['active_price'] = round($data['sale_price'] * $discount, 2);
                //结束
                $data['goodattr'] = $unit_no;
                $data['guige'] = $item_size;
                $data['combine_sta'] = $combine_sta;
                $data['shop_id'] = $shop_id;
                $data['bulk_package'] = $bulk_package == 1 ? 1 : 0;
                if ($combine_sta) {
                    $data['count'] = 0;
                } else {
                    $data['count'] = $item['stock_qty'];
                }
                if ($bulk_package == 1) {
                    if ($bprice["$item_no"]) {
                        $data['bcost'] = $bprice["$item_no"];
                    }
                } else {
                    $data['bcost'] = 0;
                }
                //重新格式化商品价格
                if ($bprice["$item_no"]) {
                    $data['sale_price'] = $bprice["$item_no"];
                    $data['active_price'] = round($bprice["$item_no"] * $discount, 2);
                }
                $data['img'] = join(',', getAllFiles(__UPLOAD__ . '/goodsimg/' . $item_no . '/ontop'));
                $instro = getAllFiles(__UPLOAD__ . '/goodsimg/' . $item_no . '/d');
                $instroImg = [];
                foreach ($instro as $ins) {
                    $instroImg[] = "<img src='" . $ins . "' style='width: 100%;'/>";
                }
                $data['instro'] = join('', $instroImg);
                if ($res) {
                    if ($bulk_package == 1) {
                        $props = model('goods_prop')->where(['good_id' => $res['id'], 'num' => ['gt', 0]])->select();
                        if (!$props) {
                            $data['is_live'] = 0;
                        } else {
                            $data['is_live'] = 1;
                        }
                    } else {
                        if ($combine_sta == 1) {
                            if ($res['count'] > 0) {
                                $data['count'] = $res['count'];
                                $data['is_live'] = 1;
                            } else {
                                $data['is_live'] = 0;
                            }
                        } else {
                            $data['is_live'] = 1;
                        }
                    }

                    if ($res['active_id'] != 0) {
                        unset($data['active_price']);
                    }

                    //更新商品信息
                    $goodsModel->isUpdate(true)->save($data, ['id' => $res['id']]);
                } else {
                    if ($bulk_package == 1) {
                        $data['is_live'] = 0;
                    } else {
                        if ($combine_sta == 1) {
                            $data['is_live'] = 0;
                        } else {
                            $data['is_live'] = 1;
                        }
                    }
                    $goodsModel->allowField(true)->isUpdate(false)->save($data);
                }
            }
        }
        return true;
    }

    /**
     * 检验店铺实体店是否存在
     * @param $shopNo
     * @return bool
     */
    public function checkShopExist($shopNo)
    {
        $is_exist = $this->sqlserver->select_one("select * from t_bd_branch_info where branch_no='$shopNo'");
        if ($is_exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据手机号获取会员列表
     * @param $telephone
     * @return bool|mixed
     */
    public function getVipInfo($telephone)
    {
        if (!$telephone) {
            $this->error = "参数错误";
            return [];
        }

        $vipList = $this->sqlserver->getarr("select * from t_rm_vip_info where mobile='" . $telephone . "' or vip_tel='" . $telephone . "'");
        $vipArr = [];
        if (count($vipList) > 0) {
            foreach ($vipList as $item) {
                $vipArr[] = [
                    'card_id' => $item['card_id'],
                    'card_no' => $item['card_flowno'] ? $item['card_flowno'] : "",
                    'remain_cost' => $this->money_decode($item['residual_amt']),
                    'remain_score' => $item['acc_num'],
                    'card_name' => iconv('GBK', 'UTF-8', $item['vip_name']) ? iconv('GBK', 'UTF-8', $item['vip_name']) : ""
                ];
            }
        }
        return $vipArr;
    }

    /**
     * 根据会员卡号获取卡内信息
     * @param $card_id
     * @return array|false|null
     */
    public function getCardInfo($card_id)
    {
        //card_status 当前卡状态  3 作废 0 正常
        $card = $this->sqlserver->select_one("select * from t_rm_vip_info where card_id='" . $card_id . "'");
        return $this->formatCard($card);
    }

    /**
     * 根据cardId 获取卡内信息
     * @param $card
     * @return mixed
     */
    public function formatCard($card)
    {
        if (!$card) {
            return false;
        }
        $card['vip_name'] = iconv('GBK', 'UTF-8', $card['vip_name']);
        $card['cost'] = $this->money_decode($card['residual_amt']);
        return $card;

    }

    /**
     * 写入会员
     * @param $telephone
     * @param $card_id
     * @param $card_no
     * @return bool
     */
    public function addVip($user, $card_no, $card_id)
    {
        $addtime = date('Y-m-d', time());
        $endtime = date('Y', time()) + 1 . '-' . date('m-d');//一年后日期
        $cost = $this->money_encode($user['cost']);
        if ($user['phone'] && $card_id && $card_no) {
            $res = $this->sqlserver->query("INSERT INTO t_rm_vip_info (card_id, card_flowno, card_type,vip_name,card_status,oper_id,oper_date,vip_start_date,vip_end_date,use_num,acc_num,mobile,residual_amt) 
	VALUES('$card_id','$card_no',0,'" . iconv('UTF-8', 'GBK', $user['username']) . "',0,'1001','$addtime','$addtime','$endtime',999999,0,'" . $user['phone'] . "','" . $cost . "')");
            if ($res !== false) {
                return true;
            } else {
                $this->error = '写入失败';
                return false;
            }
        } else {
            $this->error = '参数错误';
            return false;
        }
    }


    //余额同步到思讯
    function set_residual_amt($cost, $card_id)
    {
        $cost = $this->money_encode($cost);
        $this->sqlserver->query("UPDATE t_rm_vip_info SET residual_amt='" . $cost . "' WHERE card_id='" . $card_id . "'");
    }
    //增加累计消费
    /*function set_consum_amt($cost){
        if($this->exit_phone()){
            $back = $this->mssql->query("UPDATE t_rm_vip_info SET consum_amt=consum_amt+'".$cost."' where mobile='".$this->member['phone']."'");
            if($back){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }*/

    //同步积分到思讯
    function set_core($allScore, $card_id)
    {
        $back = $this->sqlserver->query("update t_rm_vip_consume set vip_acc_amount= $allScore where card_id='$card_id'");
        if ($back) {
            return true;
        } else {
            return false;
        }
    }

    //解密思讯余额
    function money_decode($yuanstr)
    {
        $len = strlen($yuanstr);
        $m = '';
        for ($i = 0; $i < $len; $i++) {
            $m = $m . chr(ord($yuanstr[$len - $i - 1]) + (4 - $i * 2));
        }
        return round($m / 10000, 2);
    }

    //加密思讯余额
    function money_encode($money)
    {
        $mstr = number_format($money * 10000, 2);
        $mstr = str_replace(",", "", $mstr);
        $len = strlen($mstr);
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str = chr(ord($mstr[$i]) - (4 - $i * 2)) . $str;
        }
        return $str;
    }

    /**
     * 检验会员卡是否正常可用
     * @param int $user_id 用户id
     * @return bool
     */
    public function checkCardUsable($user_id)
    {
        $userModel = new User();
        $card_id = $userModel->getCard($user_id);
        $card = $this->getCardInfo($card_id);
        if ($card['card_status'] != 0) {

            return false;
        }
        return true;

    }

    /**
     *
     * 写进订单及相关操作
     * @param $orderInfo
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function writeOrder($orderInfo)
    {
        $branch_no = model('shop')->where('id', $orderInfo['shop_id'])->value('fendian');
        if (!$branch_no) {
            return false;
        }
        $good_list = $orderInfo['order_det'];
        $flow_no = '9999' . date('Ymd') . rand(1000, 9999);
        $flowno_rand = '9999' . date('Ymd') . rand(1000, 9999);
        $order_total = 0;
        $card_id = model('User')->where('id', $orderInfo['user_id'])->value('card_id');
        $sale_man = '9999';
        $counter_no = "0001";
        $date = date('Y-m-d');
        foreach ($good_list as $key => $good) {
            //写入销售流水
            $flow_id = $key + 1;
            $gno = $good['gno'];
            $sql_getPrice = "select * from t_pc_branch_price where item_no='$gno' and branch_no='$branch_no'";
            $sql_g = "select * from t_bd_item_info where item_no='$gno'";
            $g = $this->sqlserver->select_one($sql_g);
            $good_price = $this->sqlserver->select_one($sql_getPrice);
            $source_price = $good_price['sale_price'];
            $sale_price = $good_price['sale_price'];
            $sale_qnty = $good['num'];
            $sale_money = sprintf("%.2f", $sale_qnty * $sale_price);
            $sell_way = "A";
            $oper_id = self::OPERA_ID;
            $oper_date = $this->getMillSec();
            $posid = self::POSID;
            $is_jxc = "1";
            $real_date = $this->getMillSec();
            $shift_no = '0';

            //称重商品处理
            $prop = db('goods_prop')->where(['good_id' => $good['good_id'], 'id' => $good['good_prop']])->find();
            if ($prop['prop_no'] && strpos($prop['prop_no'], '22') !== false) {
                $sale_money = (double)substr($prop['prop_no'], -6) / 1000;
                $sale_qnty = round($sale_money / $sale_price, 4);
            }
            $in_price = round($good_price['price'] * $sale_qnty, 4);

            $sql_saleflow = "insert into t_rm_saleflow (flow_id, flow_no, branch_no, item_no, source_price, sale_price, sale_qnty, sale_money, sell_way, oper_id, sale_man, counter_no, oper_date, in_price, posid,is_jxc, real_date, flowno_rand, shift_no) values (" . $flow_id . ", '" . $flow_no . "', '" . $branch_no . "', '" . $gno . "', " . $source_price . ", " . $sale_price . ", " . $sale_qnty . ", " . $sale_money . ", '" . $sell_way . "', '" . $oper_id . "', '" . $sale_man . "', '" . $counter_no . "', '" . $oper_date . "', " . $in_price . ", '" . $posid . "', '" . $is_jxc . "', '" . $real_date . "', '" . $flowno_rand . "', '" . $shift_no . "')";
            $this->sqlserver->query($sql_saleflow); //添加

            //修改库存
//            if ($g['combine_sta'] == 1) {
//                $sql_combine = "select * from t_bd_item_combsplit where comb_item_no='" . $gno . "'";
//                $comb = $this->sqlserver->select_one($sql_combine);
//                $split_no = $comb['item_no'];
//                $split_num = $comb['item_qty'];
//                $sql_stock = "update t_im_branch_stock set stock_qty=stock_qty-$split_num*$sale_qnty where item_no='" . $split_no . "' and branch_no='" . $branch_no . "'";
//            } else {
//                $sql_stock = "update t_im_branch_stock set stock_qty=stock_qty-$sale_qnty where item_no='" . $gno . "' and branch_no='" . $branch_no . "'";
//            }
//            $this->sqlserver->query($sql_stock);

            //更改单品日销售记录
            /*
            $day_sum = $this->sqlserver->select_one("select * from t_rm_daysum where item_no='" . $gno . "' and oper_date='" . $date . "' and branch_no='" . $branch_no . "'");
            if ($day_sum['flow_id'] > 0) {
                $sql_daysum = "update t_rm_daysum set sale_qty=sale_qty+$sale_qnty, sale_amt=sale_amt+$sale_money, cost_price=cost_price+$in_price where item_no='" . $gno . "' and oper_date='" . $date . "' and branch_no='" . $branch_no . "'";
            } else {
                $sql_daysum = "insert into t_rm_daysum (item_no, oper_date, branch_no, sale_price, sale_qty, sale_amt, cost_price, supcust_no) values ('" . $gno . "', '" . $date . "', '" . $branch_no . "', " . $sale_price . ", " . $sale_qnty . ", " . $sale_money . ", " . $in_price . ", '" . $g['main_supcust'] . "')";
            }
            $this->sqlserver->query($sql_daysum);*/

            $order_total += $sale_money;

        }

        //增加支付记录
//        if($orderInfo['pay_type'] == '3'){
//            $sql_payflow = "insert into t_rm_payflow (flow_id, flow_no, sale_amount, branch_no, pay_way, sell_way, card_no, coin_no, coin_rate, pay_amount, oper_date, oper_id, counter_no, sale_man, posid, is_jxc, real_date, flowno_rand) values (1, '" . $flow_no . "', " . $order_total . ", '" . $branch_no . "', 'SAV', 'A', '" . $card_id . "', 'RMB', 1.0000, " . $order_total . ", '" . $this->getMillSec() . "', '" . self::OPERA_ID . "', '" . $counter_no . "', '" . $sale_man . "', '" . self::POSID . "', '1', '" . $this->getMillSec() . "', '" . $flowno_rand . "')";
//        }else{
        $sql_payflow = "insert into t_rm_payflow (flow_id, flow_no, sale_amount, branch_no, pay_way, sell_way, vip_no, coin_no, coin_rate, pay_amount, oper_date, oper_id, counter_no, sale_man, posid, is_jxc, real_date, flowno_rand) values (1, '" . $flow_no . "', " . $order_total . ", '" . $branch_no . "', '" . config('sixun_pay')[$orderInfo['pay_type']] . "', 'A', '" . $card_id . "', 'RMB', 1.0000, " . $order_total . ", '" . $this->getMillSec() . "', '" . self::OPERA_ID . "', '" . $counter_no . "', '" . $sale_man . "', '" . self::POSID . "', '1', '" . $this->getMillSec() . "', '" . $flowno_rand . "')";
//        }
        $this->sqlserver->query($sql_payflow);

        //增加会员支付
        /*$sql_card_paylist = "insert into t_rm_card_paylist (flow_no, card_type, card_id, amount, pay_time, branch_no, vip_acc, real_date) values ('" . $flow_no . "', '1', '" . $card_id . "', " . $order_total . ", '" . $this->getMillSec() . "', '" . $branch_no . "', " . floor($order_total) . ", '" . $this->getMillSec() . "')";
        $this->sqlserver->query($sql_card_paylist);

        //统计日收银记录
        $casher_daysum = $this->sqlserver->select_one("select * from t_rm_casher_daysum where oper_date='$date' and casher_no='" . self::OPERA_ID . "' and branch_no='$branch_no' and pay_way='" . self::PAY_WAY . "'");
        if ($casher_daysum) {
            $sql_casher_daysum = "update t_rm_casher_daysum set real_amt = real_amt+$order_total where oper_date='$date' and casher_no='" . self::OPERA_ID . "' and branch_no='$branch_no' and pay_way='" . self::PAY_WAY . "'";
        } else {
            $sql_casher_daysum = "insert into t_rm_casher_daysum (oper_date, casher_no, branch_no, real_amt, sale_way, pay_way, coin_no) values ('" . $date . "', '" . self::OPERA_ID . "', '" . $branch_no . "', " . $order_total . ", 'A', '" . self::PAY_WAY . "', 'RMB')";
        }
        $this->sqlserver->query($sql_casher_daysum);

        //统计日销售额统计
        $counter_daysum = $this->sqlserver->select_one("select * from t_rm_counter_daysum where oper_date='$date' and branch_no='$branch_no' and counter_no='$counter_no'");
        if ($counter_daysum) {
            $sql_counter_daysum = "update t_rm_counter_daysum set sale_amt = sale_amt+$order_total where oper_date='$date' and branch_no='$branch_no' and counter_no='$counter_no'";
        } else {
            $sql_counter_daysum = "insert into t_rm_counter_daysum (oper_date, branch_no, counter_no, sale_amt) values ('" . $date . "', '" . $branch_no . "', '" . $counter_no . "', " . $order_total . ")";
        }
        $this->sqlserver->query($sql_counter_daysum);*/

        //TODO 系统自动处理
        //会员消费记录 t_rm_vip_consume

        //余额消费更改会员余额 t_rm_vip_info
//        $user = $this->sqlserver->select_one("select * from t_rm_vip_info where card_id='$card_id'");
//        $residual_amt = $this->money_decode($user['residual_amt']);
//        if ($orderInfo['pay_type'] == 3) {
//            $residual_amt -= $orderInfo['real_cost'];
//        }
//        $residual_amt = $this->money_encode($residual_amt);
//        $sql_vip_info = "update t_rm_vip_info set residual_amt='$residual_amt' where card_id='$card_id'";
//        $this->sqlserver->query($sql_vip_info);
        return true;
    }

    /**
     * 获取带毫秒时间
     */
    public function getMillSec()
    {
        $mill = (int)(explode(" ", microtime())[0] * 1000);
        return date('Y-m-d H:i:s') . "." . $mill;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 同步会员基本信息
     */
    public function asyncVip($user)
    {
        $birthday = (date('Y') - $user['age']) . '-' . $user['birthday'] . ' 00:00:00.000';
        $sql = "UPDATE t_rm_vip_info SET vip_name='" . iconv('UTF-8', 'GBK', $user['username']) . "', birthday='" . $birthday . "' WHERE card_id='" . $user['card_id'] . "'";
        $this->sqlserver->query($sql);
    }

    /**
     * 获取积分兑换商品
     */
    public function getGift()
    {
        $sql = 'select a.*, b.item_name, b.sale_price from t_rm_vip_good a left join t_bd_item_info b on a.vg_no=b.item_no';
        return $this->sqlserver->getarr($sql);
    }

    public function setConsume($card_id, $min_num)
    {
        $sql = "update t_rm_vip_consume set vip_minus_total =vip_minus_total +$min_num where card_id='$card_id'";
        $this->sqlserver->query($sql);
    }

    /**
     * 获取组合商品子商品
     */
    public function getChildGood($combineNo)
    {
        return $this->sqlserver->select_one("select * from t_bd_item_combsplit where comb_item_no='" . $combineNo . "'");
    }

}