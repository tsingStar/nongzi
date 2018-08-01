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
    protected $autoWriteTimestamp = true;

    public function formatOne($product)
    {
        $prop_attr = new ProductAttr();
        $prop = $prop_attr->getPropAttrOne($product['id']);
        $data = [
            'id'=>$product['id'],
            'thumb_img'=>__URL__.$product['thumb_img'],
            'name'=>$product['name'],
            'prop_name'=>$prop['prop_value_name'],
            'price_comb'=>$prop['price_comb'],
            'price_one'=>$prop['price_one'],
        ];
        return $data;
    }

    /**
     * 根据分类id获取商品列表
     * @param $cate_id
     * @return array
     */
    public function getListByCateId($cate_id)
    {

        $product = [
            'id'=>10,
            'thumb_img'=>__URL__."/upload/20180730/0f67922f113bff0df803cac8e95a5d7d.png",
            'name'=>'测试商品',
            'prop_name'=>'200克*50瓶/箱',
            'price_comb'=>'260元/箱',
            'price_one'=>'10元/瓶',
        ];
        
        $list = [];
        for($i=0;$i<10;$i++){
            $list[] = $product;
        }
        return $list;

        //TODO 正式上线加载正式数据

//        $list = $this->where('cate_id', $cate_id)->select();
//        $data = [];
//        foreach ($list as $l){
//            $data[] = $this->formatOne($l);
//        }
//        return $data;
    }



}