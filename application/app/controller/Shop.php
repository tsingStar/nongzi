<?php
/**
 * 店铺控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.5.2
 * Time: 09:50
 */

namespace app\app\controller;


use app\admin\model\Admins;
use think\Controller;
use think\Log;

class Shop extends Controller
{
    private $user_id;

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->user_id = input('user_id') ? input('user_id') : "";
//        Log::error($_POST);
//        Log::error($_FILES);
    }

    /**
     * 获取首页轮播图
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSwiper()
    {
        $list = model('swiper')->field('id, image, ord, product_id')->order('ord')->select();
        $data = [];
        foreach ($list as $l) {
            $t = [];
            $t['id'] = $l['id'];
            $t['image'] = __URL__ . $l['image'];
            $t['ord'] = $l['ord'];
            $t['product_id'] = $l['product_id'];
            $data[] = $t;
        }
        exit_json(1, '请求成功', $data);
    }

    /**
     * 获取首页分类信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCateIndex()
    {
        $res = model('product_cate')->where('is_index', 1)->field('id, name, ord, is_index, image')->order('ord')->limit(8)->select();
        $list = [];
        foreach ($res as $r) {
            $t = $r;
            $t['image'] = __URL__ . $r['image'];
            $list[] = $t;
        }
        exit_json(1, '请求成功', $list);
    }

    /**
     * 获取分类信息及商品信息
     */
    public function getCatesAndProducts()
    {
        $cate = model('ProductCate')->field('id, name, ord, parent_id')->order('ord')->select();
        $list = getTree($cate, 0, 'parent_id');
        $data = [];
        foreach ($list as $l) {
            $temp = [];
            foreach ($l['children'] as $key=>$item) {
                $cate_id = $item['id'];
                $item['children'] = model('Product')->getListByCateId($cate_id);
                if(count($item['children'])>0){
                    $temp[] = $item;
                }
            }
            if(count($temp)>0){
                $l['children'] = $temp;
                $data[] = $l;
            }
        }
        exit_json(1, '请求成功', $data);
    }

    /**
     * 根据分类id获取商品列表
     */
    public function getProductsByCateId()
    {
        $cate_parent_id = input('cate_id');
        $where = [];
        $where['cate_parent_id'] = $cate_parent_id;
        $where["is_up"] = 1;
        $data = $this->productList($where);
        exit_json(1, '请求成功', $data);
    }

    public function productList($where)
    {
        //排序字段
        $sort = input('sort');
        //排序类型
        $is_desc = input('is_desc') ? 'desc' : 'asc';
        $page = input('page') ? input('page') : 0;
        $pageNum = input('pageNum') ? input('pageNum') : 20;
        $offset = $page * $pageNum;
        switch ($sort) {
            case 'price':
                //根据价格排序
                $product_ids = model('Product')->where($where)->column('id');
                $ids = model('ProductAttr')->whereIn('product_id', $product_ids)->group('product_id')->order("price_comb $is_desc")->limit($offset, $pageNum)->column('product_id');
                $products = [];
                foreach ($ids as $pid) {
                    $products[] = model('Product')->whereIn('id', $pid)->find();
                }
                $data = [];
                foreach ($products as $p) {
                    $data[] = model('Product')->formatOne($p);
                }
                break;
            case 'sell':
                $products = model('Product')->where($where)->order("sell_num $is_desc")->limit($offset, $pageNum)->select();
                $data = [];
                foreach ($products as $p) {
                    $data[] = model('Product')->formatOne($p);
                }
                break;
            case 'time':
                $products = model('Product')->where($where)->order("create_time $is_desc")->limit($offset, $pageNum)->select();
                $data = [];
                foreach ($products as $p) {
                    $data[] = model('Product')->formatOne($p);
                }
                break;
            default:
                $products = model('Product')->where($where)->limit($offset, $pageNum)->select();
                $data = [];
                foreach ($products as $p) {
                    $data[] = model('Product')->formatOne($p);
                }
        }
        return $data;

    }

    /**
     * 获取商品详情
     */
    public function getProductInfo()
    {
        $product_id = input('product_id');
        $pro = model('Product')->getInfo($product_id);
        exit_json(1, '请求成功', $pro);
    }

    /**
     * 获取首页推荐商品
     */
    public function getProductIndex()
    {
        $data = model('Product')->alias('a')->join('ProductCate b', 'a.cate_parent_id=b.id')->where('a.is_index', 1)->where("a.is_up", 1)->field('a.*, b.word_image')->order('a.ord desc')->select();
        $temp = [];
        foreach ($data as $item){
            $temp[$item['cate_parent_id']]['image'] = $item['word_image']?__URL__.$item['word_image']:"";
            $temp[$item['cate_parent_id']]['cate_id'] = $item['cate_parent_id'];
            $temp[$item['cate_parent_id']]['cate_name'] = $item['cate_name'];
            $temp[$item['cate_parent_id']]['data'][] = model('Product')->formatOne($item);;
        }

        $list = array_values($temp);
//        foreach ($data as $p) {
//            $list[] = model('Product')->formatOne($p);
//        }
        exit_json(1, '请求成功', $list);
    }

    /**
     * 获取热卖商品
     */
    public function getHotProducts()
    {
        $where['is_hot'] = 1;
        $where["is_up"] = 1;
        $data = $this->productList($where);
        exit_json(1, '请求成功', $data);
    }

    /**
     * 关键字搜索商品
     */
    public function searchByKeywords()
    {
        $keywords = trim(input('keywords'));
        if ($keywords == "") {
            exit_json(-1, '关键字不能为空');
        }
        $where = '1 = 1';
        $where1 = '';
        $where2 = '';
        for ($i = 0; $i < mb_strlen($keywords, 'utf-8'); $i++) {
            $where1 .= " name like '%" . mb_substr($keywords, $i, 1, 'utf-8') . "%'";
            $where2 .= " bname like '%" . mb_substr($keywords, $i, 1, 'utf-8') . "%' ";
            if($i<mb_strlen($keywords, 'utf-8')-1){
                $where1 .= " and";
                $where2 .= " and";
            }
        }
        $where .= " and ($where1) or ($where2)";
        $where .= " and is_up=1";
        $data = $this->productList($where);
        exit_json(1, '请求成功', $data);
    }

    /**
     * 获取推荐搜索
     */
    public function getRecommendWord()
    {
        $key = model('Keywords')->find();
        $keywords = explode(',', $key['keywords']);
        exit_json(1, '请求成功', $keywords);
    }

    /**
     * 获取相关商品
     */
    public function getRelationPro()
    {

        $product_id = input('product_id');

        $pro = model('Product')->where('id', $product_id)->find();
        $cate_id = $pro['cate_id'];
        $pros = model('Product')->where('cate_id', $cate_id)->where("is_up", 1)->order('ord desc')->select();
        $data = [];
        foreach ($pros as $item){
            $data[] = model('Product')->formatOne($item);
        }
        exit_json(1, '请求成功', $data);
    }

    /**
     * 求购/供应
     */
    public function getBuyAndSupply()
    {
        $product_name = input('product_name');
        $num = input('num');
        $telephone = input('telephone');
        $description = input('description');
        $remarks = input('remarks');
        $image_url = input('image_url');
        $type = input('type');
        if(isset($image_url)){
            $image = $image_url;
        }else{
            $img = request()->file('image');
            if($img){
                $img_url = [];
                if(is_array($img)){
                    foreach ($img as $item) {
                        $info = $item->move(__UPLOAD__);
                        $saveName = $info->getSaveName();
                        $path = "/upload/" . $saveName;
                        $img_url[] = $path;
                    }
                }else{
                    $info = $img->move(__UPLOAD__);
                    $saveName = $info->getSaveName();
                    $path = "/upload/" . $saveName;
                    $img_url[] = $path;
                }
                $image = join(',', $img_url);
            }else{
                $image = "";
            }
        }
        $data = [
            'user_id'=>input('user_id'),
            'product_name'=>$product_name,
            'num'=>$num,
            'telephone'=>$telephone,
            'description'=>$description,
            'remarks'=>$remarks,
            'image'=>$image,
            'type'=>$type
        ];
        $res = model('BuySupply')->save($data);
        if($res){
            exit_json(1, '提交成功');
        }else{
            exit_json(-1, '提交失败');
        }
    }

    /**
     * 获取客服电话
     */
    public function getSevicePhone()
    {
        $user_id = input('user_id');
        $phone = model('WebContactUs')->value('telephone');
        if($user_id){
            $user = model('User')->where('id', $user_id)->find();
            $vip_code = $user['vip_code'];
            if($vip_code){
                $admin = new Admins();
                $phone1 = $admin->where('vip_code', $vip_code)->value('telephone');
                if($phone1){
                    $phone = $phone1;
                }
            }
        }
        exit_json(1, '请求成功', ['phone'=>$phone]);

    }


}