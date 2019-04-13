<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-08-01
 * Time: 15:21
 */

namespace app\common\model;


use think\Model;

class ProductAttr extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp = true;

    /**
     * 获取单个商品最低价
     * @param $product_id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPropAttrOne($product_id)
    {
        $prop = $this->where('product_id', $product_id)->field('prop_value_name, price_one, price_comb')->order('price_comb')->find();
        return $prop;

    }

    /**
     * 获取指定商品下所有规格详情
     */
    public function getAllProp()
    {
        $prop_list = $this->alias("a")->join("Product b", "a.product_id=b.id")->field("a.id prop_id, a.product_id, b.name product_name, a.prop_value_name, a.remain, a.price_one, a.price_comb")->select();
        $data = [];
        foreach ($prop_list as $item){
            $temp = [];
            $temp[] = $item["prop_id"];
            $temp[] = $item["product_id"];
            $temp[] = $item["product_name"];
            $temp[] = $item["prop_value_name"];
            $temp[] = $item["remain"];
            $temp[] = $item["price_one"];
            $temp[] = $item["price_comb"];
            $data[] = $temp;
        }
        return $data;
    }
}