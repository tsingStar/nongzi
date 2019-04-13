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
        //富文本处理
        /*$content = $data['mob_detail'];
        $preg = '/<img.*?src=[\"|\']?\/(.*?)[\"|\']?\s.*?>/i';
        preg_match_all($preg, $content, $data1);
        foreach ($data1[1] as $item){
            $img_url = __URL__.'/'.$item;
            $content = str_replace('/'.$item, $img_url, $content);
        }
        $data['mob_detail'] = $content;*/

        $content = $data['mob_detail'];
        preg_match_all('/<img(.*?)>/', $content, $match);
        $imgs = $match[0];
        //取出img标签的src属性
        foreach($imgs as $k=>$v){
            preg_match('/<img.+src=\"?(.+\.(jpg|jpeg|gif|bmp|bnp|png))\"?.+>/i', $v, $res);
            $img_url = __URL__.$res[1];
            $content = str_replace($res[1], $img_url, $content);
        }
        $data['mob_detail'] = $content;
        //富文本处理结束


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
            $is_show = \model('PropName')->where('id', $k)->value('is_show');
            $prop_data[] = [
                'name'=>$v,
                'is_show'=>$is_show,
                'value'=>\model('ProductPropValue')->where(['product_id'=>$product['id'], 'prop_name_id'=>$k
                ])->field('prop_value_id, prop_value')->select()];
        }
        $data['prop_data'] = $prop_data;
        $prop_value = \model('ProductAttr')->where('product_id', $product['id'])->field('id, product_id, prop_value_attr, prop_value_name, remain, limit_remain, price_one, price_comb, img_url')->select();
        $data['prop_value'] = $prop_value;
        $data['cate_id']=explode(",",$data['cate_id'])[0];
        return $data;
    }

    /**
     * 根据分类id获取商品列表
     * @param $cate_id
     * @return array
     */
    public function getListByCateId($cate_id)
    {
        $list = $this->where("FIND_IN_SET($cate_id, cate_id)")->where("is_up", 1)->order("ord desc")->select();
        $data = [];
        foreach ($list as $l){
            $data[] = $this->formatOne($l);
        }
        return $data;
    }

    /**
     * 获取商品详情
     * @param $product_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function getInfo($product_id)
    {
//        $product = self::get($product_id);
        $product = $this->where("id", $product_id)->where("is_up", 1)->find();
        if(!$product){
            exit_json(-1, '商品不存在');
        }
        return $this->formatOneDetail($product);
    }

    /**
     * 获取所有商品
     */
    public function getAllProducts()
    {
        $product_list = $this->field("id product_id, cate_id, name product_name, agent_commission, salesman_commission, parttime_commission, send_fee, is_index, is_hot, ord, is_up")->select();
        $data = [];
        foreach ($product_list as $item){
            $temp = [];
            $temp[] = $item["product_id"];
            $temp[] = $item["product_name"];
            $temp[] = $item["agent_commission"];
            $temp[] = $item["salesman_commission"];
            $temp[] = $item["parttime_commission"];
            $temp[] = $item["send_fee"];
            $temp[] = $item["is_index"]==1?"是":"否";
            $temp[] = $item["is_hot"]==1?"是":"否";
            $temp[] = $item["ord"];
            //获取分类信息
            $cate_arr = \model("ProductCate")->whereIn("id", $item["cate_id"])->column("name");
            $temp[] = implode(",", $cate_arr);
            $temp[] = $item["is_up"]==1?"是":"否";
            $data[] = $temp;
        }
        return $data;
    }



}