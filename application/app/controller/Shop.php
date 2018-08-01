<?php
/**
 * 店铺控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.5.2
 * Time: 09:50
 */

namespace app\app\controller;


use app\common\model\Goods;
use app\common\model\ShopCate;
use think\Controller;
use think\Log;

class Shop extends Controller
{
    private $user_id;
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->user_id = input('user_id')?input('user_id'):"";
    }

    /**
     * 获取首页轮播图
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSwiper()
    {
        $list = model('swiper')->field('id, image, ord')->order('ord desc')->select();
        $data = [];
        foreach ($list as $l){
            $t = [];
            $t['id'] = $l['id'];
            $t['image'] = __URL__.$l['image'];
            $t['ord'] = $l['ord'];
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
        $res = model('product_cate')->where('is_index', 1)->field('id, name, ord, is_index, image')->order('ord desc')->limit(7)->select();
        $list = [];
        foreach ($res as $r) {
            $t = $r;
            $t['image'] = __URL__.$r['image'];
            $list[] = $t;
        }
        exit_json(1, '请求成功', $list);
    }

    /**
     * 获取分类信息及商品信息
     */
    public function getCatesAndProducts()
    {

        $cate = model('ProductCate')->field('id, name, ord, parent_id')->order('ord desc')->select();
        $list = getTree($cate, 0, 'parent_id');
        foreach ($list as $l){
            foreach ($l['children'] as $item) {
                $cate_id = $item['id'];
                $item['children'] = model('Product')->getListByCateId($cate_id);
            }
        }
        exit_json(1, '请求成功', $list);
    }

    /**
     * 根据分类id获取商品列表
     */
    public function getProductsByCateId()
    {
        //排序字段
        $sort = input('sort');
        //排序类型
        $is_desc = input('is_desc')?'desc':'asc';
        $cate_parent_id = input('cate_id');
        $page = input('page')?input('page'):0;
        $pageNum = input('pageNum')?input('pageNum'):20;
        $offset = $page*$pageNum;
        switch ($sort){
            case 'price':
                //根据价格排序
                $product_ids = model('Product')->where('cate_parent_id', $cate_parent_id)->column('id');
                $ids = model('ProductAttr')->whereIn('product_id', $product_ids)->group('product_id')->order("price_comb $is_desc")->limit($offset, $pageNum)->column('product_id');
                $products = [];
                foreach ($ids as $pid){
                    $products[] = model('Product')->whereIn('id', $pid)->find();
                }
                $data = [];
                foreach ($products as $p){
                    $data[] = model('Product')->formatOne($p);
                }
                break;
            case 'sell':
                $products = model('Product')->where('cate_parent_id', $cate_parent_id)->order("sell_num $is_desc")->select();
                $data = [];
                foreach ($products as $p){
                    $data[] = model('Product')->formatOne($p);
                }
                break;
            case 'time':
                $products = model('Product')->where('cate_parent_id', $cate_parent_id)->order("create_time $is_desc")->select();
                $data = [];
                foreach ($products as $p){
                    $data[] = model('Product')->formatOne($p);
                }
                break;
            default:
                $products = model('Product')->where('cate_parent_id', $cate_parent_id)->select();
                $data = [];
                foreach ($products as $p){
                    $data[] = model('Product')->formatOne($p);
                }
        }
        exit_json(1, '请求成功', $data);
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


    //TODO 未处理
    /**
     * 获取首页推荐商品
     */
    public function getGoodsIndex()
    {
        $shop_id = input('post.shop_id');
        $page = input('page');
        $page_num = input('page_num');
        $r = $this->checkShop($shop_id);
        if (!is_bool($r)) {
            exit_json(-1, $r);
        }
        $goodModel = new Goods();
        $extra = [
            'is_recommend' => 1,
            'is_live' => 1,
            'active_id'=>0
        ];
        $goodsList = $goodModel->getGoodsList($shop_id, $extra, $page, $page_num);
        exit_json(1, '请求成功', $goodsList);
    }

    /**
     * 获取商品信息
     */
    public function getGoodInfo()
    {
        $good_id = input('good_id');
        if (!$good_id) {
            exit_json(-1, '参数错误');
        }
        $good = model('goods')->where('id', $good_id)->find();
        $goodModel = new Goods();
        if ($good['id']) {
            $goodInfo = $goodModel->formatOne($good);
            $relationGoods = $goodModel->getRelationGoods($good['id']);
            $c = model('userCollection')->where(['user_id' => USER_ID, 'good_id' => $good_id])->find();
            if ($c['id'] > 0) {
                $is_collection = 1;
            } else {
                $is_collection = 0;
            }
            $data = [
                'goodInfo' => $goodInfo,
                'relationGoods' => $relationGoods,
                'is_collection' => $is_collection
            ];
            exit_json(1, '获取商品信息成功', $data);
        } else {
            exit_json(-1, '商品不存在');
        }
    }

    /**
     * 获取全部分类
     */
    public function getAllCate()
    {
        $shop_id = input('shop_id');
        $cates = model('shop_cate')->field('id, parent_id, name')->where('shop_id', $shop_id)->select();
        if (!$cates) {
            $cate_arr = [];
        } else {
            $cate_arr = getTree($cates, 0, 'parent_id');
        }
        exit_json(1, '请求成功', $cate_arr);
    }

    /**
     * 根据一级分类获取二级分类
     */
    public function getCateByPid()
    {
        $cate_pid = input('cate_pid');
        $cate_arr = model('shop_cate')->field('id, name')->where('parent_id', $cate_pid)->select();
        exit_json(1, '请求成功', $cate_arr);
    }

    /**
     * 根据二级分类id获取商品列表
     */
    public function getGoodsByCateId()
    {
        $cate_id = input('cate_id');
        $shop_id = input('shop_id');
        $page = input('page');
        $page_num = input('page_num');
        $goodModel = new Goods();
        $extra = [
            'cate_id' => $cate_id,
            'shop_id' => $shop_id,
            'is_live' => 1,
            'active_id'=>0
        ];
        $goodList = $goodModel->getGoodsList($shop_id, $extra, $page, $page_num);
        exit_json(1, '请求成功', $goodList);
    }

    /**
     * 添加收藏
     */
    public function setCollection()
    {
        $type = input('type');
        $good_id = input('good_id');
        $good = model('goods')->where('id', $good_id)->find();
        if (!$good['id'] > 0) {
            exit_json(-1, '商品信息错误');
        }
        $data = [
            'user_id' => USER_ID,
            'good_id' => $good_id,
            'shop_id' => $good['shop_id'],
            'cate_id' => $good['cate_id']
        ];
        if ($type == 1) {
            if (model('userCollection')->where($data)->find()) {
                exit_json();
            }
            $res = model('userCollection')->save($data);
        } else {
            $res = model('userCollection')->where($data)->delete();
        }
        if ($res) {
            exit_json();
        } else {
            exit_json(-1, '操作失败');
        }
    }


    /**
     * 关键字搜索商品
     */
    public function searchByKeywords()
    {
        $keywords = trim(input('keywords'));
        $page = input('page') ? input('page') : 1;
        $pageNum = input('pageNum') ? input('pageNum') : 10;
        if ($keywords == "") {
            exit_json(-1, '关键字不能为空');
        }
        $shop_id = input('shop_id');
        $where = 'shop_id=' . $shop_id . ' and is_live=1';
        for ($i = 0; $i < mb_strlen($keywords, 'utf-8'); $i++) {
            $where .= " and name like '%" . mb_substr($keywords, $i, 1, 'utf-8') . "%' ";
        }
        $keyModel = model('keywords');
        $is_exist = $keyModel->where(['keywords' => $keywords, 'user_id' => USER_ID])->find();
        if ($is_exist) {
            $keyModel->save(['is_del' => 0], ['keywords' => $keywords, 'user_id' => USER_ID]);
        } else {
            $keyModel->save([
                'keywords' => $keywords,
                'user_id' => USER_ID
            ]);
        }
        $offset = ($page - 1) * $pageNum;
        $goods = model('goods');
        $goodList = $goods->where($where)->limit($offset, $pageNum)->select();
        exit_json(1, '请求成功', $goods->goodsFormat($goodList));
    }

    /**
     * 获取热门搜索关键字
     */
    public function getHotKeyWords()
    {
        $hotKey = model('Keywords');
        $keywords = $hotKey->hotKeys();
        exit_json(1, '请求成功', $keywords);
    }

    /**
     * 获取搜索历史记录
     */
    public function getSearchHistory()
    {
        $keyword = model('keywords');
        $keywords = $keyword->where(['user_id' => USER_ID, 'is_del' => 0])->column('keywords');
        exit_json(1, '请求成功', $keywords);
    }

    /**
     * 删除搜索记录
     */
    public function delHistory()
    {
        $keyword = model('keywords');
        $keywords = $keyword->save(['is_del' => 1], ['user_id' => USER_ID]);
        exit_json();
    }

    /**
     * 获取店铺推荐搜索
     */
    public function getRecommendWord()
    {
        $shop_id = input('shop_id');
        $key = db('shop_keywords')->where('shop_id', $shop_id)->value('keywords');
        exit_json(1, '请求成功', $key ? explode(",", $key) : []);
    }


}