<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 9:30
 */

namespace app\common\model;


use think\Model;

class Shop extends Model
{
    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    protected $autoWriteTimestamp = true;

    /**
     * 获取商家信息
     * @param $shopId
     * @return array|bool|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getShopInfo($shopId)
    {
        $res = $this->where('id', 'eq', $shopId)->find();
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 验证商家登陆
     * @param $name
     * @param $password
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkShop($name, $password)
    {
        $shop = $this->where([
            'uname|phone'=>$name,
            'password'=>md5($password)
        ])->find();
        return $shop;
    }

    /**
     * 获取可配送时间段
     * @param int $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDispatchTime($shop_id)
    {
        $time = db('shop_dispatch_time')->where('shop_id', $shop_id)->find();
        return [
            'open_time'=>$time['open_time'],
            'end_time'=>$time['close_time']
        ];
    }

    /**
     * 获取店铺营业时间
     * @param $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOpenTime($shop_id)
    {
        $time = db('shop_dispatch_time')->where('shop_id', $shop_id)->find();
        return [
            'open_time'=>$time['open_time'],
            'end_time'=>$time['close_time']
        ];
    }

}