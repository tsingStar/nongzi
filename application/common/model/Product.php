<?php
/**
 * 平台商品库
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 15:41
 */

namespace app\common\model;


use think\Model;

class Product extends Model
{

    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * @param int $cateId
     * 根据分类获取产品
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoods($cateId=0)
    {
        $map = [];
        if($cateId){
            $map['cateId'] = $cateId;
        }
        $list = $this->where($map)->select();
        return $list;
    }


}