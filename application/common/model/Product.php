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
     * 格式化单个商品详情
     * @param $product
     * @return mixed
     */
    public function formatOneDetail($product)
    {
        $data = $product;
        $data['thumb_img'] = __URL__.$data['thumb_img'];
        $sw = explode(',', $data['swiper_img']);
        $swiper_img = [];
        foreach ($sw as $item) {
            $swiper_img[] = __URL__.$item;
        }
        $data['swiper_img'] = $swiper_img;
        $prop = \model('ProductPropName')->where('product_id', $product['id'])->column('prop_name', 'prop_name_id');
        $prop_data = [];
        foreach ($prop as $k=>$v){
            $prop_data[$v] = \model('ProductPropValue')->where(['product_id'=>$product['id'], 'prop_name_id'=>$k])->field('prop_value_id, prop_value')->select();
        }
        $data['prop_data'] = $prop_data;
        $prop_value = \model('ProductAttr')->where('product_id', $product['id'])->field('id, product_id, prop_value_attr, prop_value_name, remain, limit_remain, price_one, price_comb, img_url')->select();
        $data['prop_value'] = $prop_value;
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

    /**
     * 获取商品详情
     * @param $product_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function getInfo($product_id)
    {
        $product = self::get($product_id);
        return $this->formatOneDetail($product);
    }



}