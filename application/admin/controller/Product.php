<?php
/**
 * 商品控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/19
 * Time: 14:06
 */

namespace app\admin\controller;

use app\common\model\ProductAttr;
use think\Exception;
use think\Log;

class Product extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 商品属性
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function productProp()
    {
        $list = model('PropName')->order('id')->select();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 属性添加/编辑
     */
    public function propAdd()
    {
        $prop_id = input('prop_id');
        $prop = model('PropName')->where('id', $prop_id)->find();
        if (request()->isPost()) {
            $data = [];
            $data['prop_name'] = input('prop_name');
            $data['is_show'] = input('is_show');
            if ($prop) {
                $res = $prop->save($data);
            } else {
                if ($prop['prop_name'] == input('prop_name')) {
                    exit_json(-1, '商品属性已存在，请勿重复添加');
                }
                $res = model('PropName')->save($data);
            }
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '操作失败');
            }
        } else {
            $this->assign('item', $prop);
            return $this->fetch();
        }
    }

    /**
     * 更改规格状态
     */
    public function changeProp()
    {
        $prop_id = input('prop_id');
        $is_show = input('is_show');
        $res = model('PropName')->save(['is_show' => $is_show], ['id' => $prop_id]);
        if ($res) {
            exit_json();
        } else {
            exit_json(-1, '操作失败');
        }
    }

    /**
     * 删除属性
     */
    public function delPropName()
    {
        $prop_id = input('prop_id');
        model('PropName')->where('id', $prop_id)->delete();
        model('PropValue')->where('prop_id', $prop_id)->delete();
        exit_json();
    }

    /**
     * 查看属性值
     */
    public function checkPropValue()
    {
        $prop_id = input('prop_id');
        $list = model('PropValue')->where('prop_id', $prop_id)->select();
        $this->assign('prop_id', $prop_id);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 属性值添加
     */
    public function propValueAdd()
    {
        $prop_value_id = input('prop_value_id');
        $prop_value = model('PropValue')->where('id', $prop_value_id)->find();
        if ($prop_value) {
            $prop_id = $prop_value['prop_id'];
        } else {
            $prop_id = input('prop_id');
        }
        $prop_name = model('PropName')->where('id', $prop_id)->value('prop_name');
        $this->assign('prop_name', $prop_name);
        $this->assign('prop_id', $prop_id);
        if (request()->isPost()) {
            $data = [];
            $data['prop_id'] = $prop_id;
            $data['prop_val'] = input('prop_val');
            if ($prop_value) {
                $res = $prop_value->save($data);
            } else {
                if ($prop_value['prop_val'] == input('prop_val')) {
                    exit_json(-1, '商品属性值已存在, 请勿重复添加');
                }
                $res = model('PropValue')->save($data);
            }
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '操作失败');
            }
        } else {
            $this->assign('item', $prop_value);
            return $this->fetch();
        }
    }

    public function index()
    {
        $cateList = model('ProductCate')->field('id, name, parent_id pId')->select();
        $this->assign('cateList', $cateList);
        return $this->fetch();
    }

    /**
     * 根据分类获取商品
     */
    public function plist()
    {
        $cateId = input('cateId');
        $status = input('con');
        $name = input("name");
        $where = [];
        if (input('name')) {
            $where['name'] = ["like", "%$name%"];
        }
        if ($status == 1) {
            $where['is_index'] = 1;
        }
        if ($status == 2) {
            $where['is_hot'] = 1;
        }
        if($status == 5){
            $where["is_up"] = 1;
        }
        if($status == 6){
            $where["is_up"] = 0;
        }
        if ($cateId) {
//            $where['cate_id'] = $cateId;
//            $where['cate_id'] = ['find_in_set', $cateId];
            $goodsList = model('Product')->where("FIND_IN_SET($cateId,cate_id)")->paginate(15, false, ["query"=>request()->param()]);
        } else {
            $goodsList = model('Product')->where($where)->order('ord desc, id desc')->paginate(15, false, ["query"=>request()->param()]);
        }
        foreach ($goodsList as $k => $item) {
            $item["productAttr"] = model("ProductAttr")->where('product_id', $item['id'])->select();
            $num = model('ProductAttr')->where('product_id', $item['id'])->where('remain <= limit_remain')->count();
            if ($num > 0) {
                if($status == 4){
                    unset($goodsList[$k]);
                }
                $item['is_limit'] = 1;
            } else {
                if ($status == 3) {
                    unset($goodsList[$k]);
                }
                $item['is_limit'] = 0;
            }
        }
        $this->assign('goodsList', $goodsList);
        $this->assign("page", $goodsList->render());
        $this->assign("name", $name);
        return $this->fetch();
    }

    /**
     * 设置商品排序
     */
    public function setOrder()
    {
        $pid = input("pid");
        $order = input("order");
        $p = model("Product")->where("id", $pid)->find();
        if($p){
            $p->save(["ord"=>$order]);
        }
        exit_json();
        
    }

    /**
     * 设置规格
     */
    public function setProp()
    {
        $prop_id = input("prop_id");
        $price_comb = input("price_comb");
        $price_one = input("price_one");
        $remain = input("remain");
        $prop = model("ProductAttr")->where("id", $prop_id)->find();
        if($prop){
            $res = $prop->save([
                "price_comb"=>$price_comb,
                "remain"=>$remain,
                'price_one'=>$price_one
            ]);
            if($res){
                exit_json();
            }else{
                exit_json(-1, "保存失败");
            }
        }else{
            exit_json(-1, "保存失败");
        }
    }


    /**
     * 手动添加商品
     */
    public function productAdd()
    {
        if (request()->isAjax()) {

            $data = input('post.');
            if(count($data["cate_ids"])==0){
                exit_json(-1, '商品分类不能为空');
            }
            $data['cate_id'] = implode(",", array_unique(explode(",", implode(",", $data["cate_ids"]))));
            $data['cate_parent_id'] = 0;
            if (!isset($data['prop_attr'])) {
                exit_json(-1, '商品规格未设置');
            }
            if (!isset($data['swiper_img'])) {
                exit_json(-1, '商品展示轮播图不能为空');
            }
            $prop = $data['prop'];
            $prop_attr = $data['prop_attr'];
            $data['swiper_img'] = join(',', $data['swiper_img']);
            model('Product')->startTrans();
            model('ProductPropName')->startTrans();
            model('ProductPropValue')->startTrans();
            model('ProductAttr')->startTrans();
            $res = model('Product')->allowField(true)->save($data);
            if ($res) {
                $product_id = model('Product')->getLastInsID();
                //添加佣金记录
                model("ProductCommissionChangeLog")->save([
                    "admin_id"=>session("admin_id"),
                    "product_id"=>$product_id,
                    "name"=>"商品佣金设置:代理商佣金".$data['agent_commission']."、业务员佣金".$data["salesman_commission"],
                ]);
                //规格属性处理
                $prop_name = [];
                $prop_value = [];
                foreach ($prop as $t) {
                    $tmp = explode(":", $t);
                    $prop_name[] = [
                        "prop_name_id" => explode('|', $tmp[0])[0],
                        "prop_name" => explode('|', $tmp[0])[1],
                        "product_id" => $product_id
                    ];
                    $prop_value[] = [
                        "prop_name_id" => explode('|', $tmp[0])[0],
                        "prop_value_id" => explode('|', $tmp[1])[0],
                        "prop_value" => explode('|', $tmp[1])[1],
                        "product_id" => $product_id
                    ];
                }
                //商品属性格式化处理
                $prop_data = [];
                foreach ($prop_attr as $key => $item) {
                    //属性图片处理
//                    $img_url = uploadImg($key.'[img_url]');
                    $temp = [
                        "product_id" => $product_id,
                        "prop_value_attr" => $key,
                        "prop_value_name" => $item['prop_name'],
                        "remain" => $item['remain'],
                        "limit_remain" => $item['limit_remain'],
                        "price_one" => $item['price_one'],
                        "price_comb" => $item['price_comb'],
                        "gno" => $item['gno'],
//                        "img_url" => $img_url
                    ];
                    $prop_data[] = $temp;
                }
                try {
                    model('ProductPropName')->saveAll(arr_unique($prop_name));
                    model('ProductPropValue')->saveAll($prop_value);
                    model('ProductAttr')->saveAll($prop_data);
                    model('Product')->commit();
                    model('ProductPropName')->commit();
                    model('ProductPropValue')->commit();
                    model('ProductAttr')->commit();
                } catch (\Exception $e) {
                    model('Product')->rollback();
                    model('ProductPropName')->rollback();
                    model('ProductPropValue')->rollback();
                    model('ProductAttr')->rollback();
                    exit_json(-1, '添加失败');
                }
                exit_json();
            } else {
                exit_json(-1, '商品添加失败');
            }
        }
        $list = model('ProductCate')->field('id, name, parent_id')->select();
        $cateTree = getTree($list, 0);
        $prop_arr = model('PropName')->column('prop_name', 'id');
        $prop = [];
        foreach ($prop_arr as $k => $v) {
            $prop["$k"] = model('PropValue')->where('prop_id', $k)->column('prop_val', 'id');
        }
        $this->assign('prop_name', $prop_arr);
        $this->assign('prop_value', $prop);
        $this->assign('cateTree', $cateTree);
        return $this->fetch();
    }

    /**
     * 根据id删除数据
     */
    function delData()
    {
        $ids = input('idstr');
        model('Product')->startTrans();
        model('ProductPropName')->startTrans();
        model('ProductPropValue')->startTrans();
        model('ProductAttr')->startTrans();
        $res = model('Product')->whereIn('id', $ids)->delete();
        $res1 = model('ProductPropName')->whereIn('product_id', $ids)->delete();
        $res2 = model('ProductPropValue')->whereIn('product_id', $ids)->delete();
        $res3 = model('ProductAttr')->whereIn('product_id', $ids)->delete();
        if ($res && $res1 && $res2 && $res3) {
            model('Product')->commit();
            model('ProductPropName')->commit();
            model('ProductPropValue')->commit();
            model('ProductAttr')->commit();
            exit_json();
        } else {
            model('Product')->rollback();
            model('ProductPropName')->rollback();
            model('ProductPropValue')->rollback();
            model('ProductAttr')->rollback();
            exit_json('操作失败');
        }
    }

    /**
     * 保存商品返利比率
     */
    public function saveShipping()
    {
        $pid = input("pid");
        $com = input("com");
        $product = model("Product")->where("id", $pid)->find();
        if(!$product){
            exit_json(-1, "商品不存在");
        }
        $pre_send_fee = $product["send_fee"];
        if($pre_send_fee == $com){
            exit_json();
        }
        if($product->save(["send_fee"=>$com])){
            exit_json();
        }else{
            exit_json(-1, "保存失败");
        }
    }
    /**
     * 保存商品返利比率
     */
    public function saveCommission()
    {
        $type = input("type");
        $pid = input("pid");
        $com = input("com");
        $product = model("Product")->where("id", $pid)->find();
        if(!$product){
            exit_json(-1, "商品不存在");
        }
        $pre_agent_commission = $product["agent_commission"];
        $pre_salesman_commission = $product["salesman_commission"];
        $pre_parttime_commission = $product["parttime_commission"];
        if($type == 1){
            if($pre_agent_commission == $com){
                exit_json();
            }else{
                $res = $product->save(["agent_commission"=>$com]);
                $name = "商品佣金变动:代理商佣金".$pre_agent_commission."变动".$com;
            }
        }elseif ($type == 2){
            if($pre_salesman_commission == $com){
                exit_json();
            }else{
                $res = $product->save(["salesman_commission"=>$com]);
                $name = "商品佣金变动:业务员佣金".$pre_salesman_commission."变动".$com;
            }
        }elseif ($type == 3){
            if($pre_parttime_commission == $com){
                exit_json();
            }else{
                $res = $product->save(["parttime_commission"=>$com]);
                $name = "商品佣金变动:兼职代理佣金".$pre_salesman_commission."变动".$com;
            }
        }else{
            exit_json(-1, "参数错误");
        }
        if($res){
            model("ProductCommissionChangeLog")->save([
                "admin_id"=>session("admin_id"),
                "product_id"=>$pid,
                "name"=>$name,
            ]);
            exit_json();
        }else{
            exit_json(-1, "更新失败");
        }
    }

    /**
     * 编辑商品
     */
    public function productEdit()
    {
        $product = model('Product')->where('id', input('product_id'))->find();
        if (request()->isAjax()) {

            $data = input('post.');
            if(count($data["cate_ids"])==0){
                exit_json(-1, '商品分类不能为空');
            }
            $data['cate_id'] = implode(",", array_unique(explode(",", implode(",", $data["cate_ids"]))));
            $data['cate_parent_id'] = 0;
            $prop = $data['prop'];
            $prop_attr = $data['prop_attr'];
            if (isset($data['swiper_img'])) {
                $data['swiper_img'] = join(',', $data['swiper_img']);
            }
            if (!isset($data['thumb_img'])) {
                unset($data['thumb_img']);
            }
            model('Product')->startTrans();
            model('ProductPropName')->startTrans();
            model('ProductPropValue')->startTrans();
            model('ProductAttr')->startTrans();
            $pre_agent_commission = $product["agent_commission"];
            $pre_salesman_commission = $product["salesman_commission"];
            $res = $product->allowField(true)->save($data);
            if ($res) {
                $product_id = input('product_id');
                if($pre_agent_commission != $data["agent_commission"] || $pre_salesman_commission != $data['salesman_commission']){
                    model("ProductCommissionChangeLog")->save([
                        "admin_id"=>session("admin_id"),
                        "product_id"=>$product_id,
                        "name"=>"商品佣金变动:代理商佣金".$pre_agent_commission."变动".$data["agent_commission"]."、业务员佣金".$pre_salesman_commission."变动".$data["salesman_commission"],
                    ]);
                }
                //规格属性处理
                $prop_name = [];
                $prop_value = [];
                foreach ($prop as $t) {
                    $tmp = explode(":", $t);
                    $prop_name[] = [
                        "prop_name_id" => explode('|', $tmp[0])[0],
                        "prop_name" => explode('|', $tmp[0])[1],
                        "product_id" => $product_id
                    ];
                    $prop_value[] = [
                        "prop_name_id" => explode('|', $tmp[0])[0],
                        "prop_value_id" => explode('|', $tmp[1])[0],
                        "prop_value" => explode('|', $tmp[1])[1],
                        "product_id" => $product_id
                    ];
                }
                //商品属性格式化处理
                $prop_data = [];
                foreach ($prop_attr as $key => $item) {
                    //属性图片处理
//                    $img_url = uploadImg($key.'[img_url]');
                    $temp = [
                        "product_id" => $product_id,
                        "prop_value_attr" => $key,
                        "prop_value_name" => $item['prop_name'],
                        "remain" => $item['remain'],
                        "limit_remain" => $item['limit_remain'],
                        "price_one" => $item['price_one'],
                        "price_comb" => $item['price_comb'],
                        "gno" => $item['gno'],
//                        "img_url" => $img_url
                    ];
                    $prop_data[] = $temp;
                }
                try {
                    model('ProductPropName')->where('product_id', $product_id)->delete();
                    model('ProductPropName')->saveAll(arr_unique($prop_name));
                    model('ProductPropValue')->where('product_id', $product_id)->delete();
                    model('ProductPropValue')->saveAll($prop_value);
                    model('ProductAttr')->where('product_id', $product_id)->delete();
                    model('ProductAttr')->saveAll($prop_data);
                    model('Product')->commit();
                    model('ProductPropName')->commit();
                    model('ProductPropValue')->commit();
                    model('ProductAttr')->commit();
                } catch (\Exception $e) {
                    model('Product')->rollback();
                    model('ProductPropName')->rollback();
                    model('ProductPropValue')->rollback();
                    model('ProductAttr')->rollback();
                    exit_json(-1, $e->getMessage());
                }
                exit_json();
            } else {
                exit_json(-1, '商品添加失败');
            }
        }
        $cate_parent = model('ProductCate')->where('id', $product['cate_parent_id'])->find();
//        $cate = model('ProductCate')->where('id', $product['cate_id'])->find();
        $list = model('ProductCate')->field('id, name, parent_id')->select();
        $cateTree = getTree($list, 0);
        $prop_value = model('ProductPropValue')->where('product_id', $product['id'])->column('prop_value_id');
        $this->assign('prop_val', $prop_value);
//        $this->assign('pid', $cate['id']);
        $this->assign('parent_cate', $cate_parent);
        $product_cate = model("ProductCate")->alias("a")->join("ProductCate b", "a.parent_id=b.id", "left")->where("a.id", "in", $product["cate_id"])->field("a.id, a.name, a.parent_id, b.name parent_name")->select();
        $this->assign("product_cate", $product_cate);
        $this->assign('product', $product);
        $this->assign('cateTree', $cateTree);
        $prop_arr = model('PropName')->column('prop_name', 'id');
        $prop = [];
        foreach ($prop_arr as $k => $v) {
            $prop["$k"] = model('PropValue')->where('prop_id', $k)->column('prop_val', 'id');
        }
        $this->assign('prop_name', $prop_arr);
        $this->assign('prop_value', $prop);
        //获取商品规格属性信息，前端加载显示
        $prop_attr = model('ProductAttr')->where('product_id', $product['id'])->column('*', 'prop_value_attr');
        $this->assign('prop_attr', $prop_attr);
        return $this->fetch();

    }

    /**
     * 设为首页
     */
    public function setIndex()
    {
        $product_id = input('product_id');
        $is_index = input('is_index');
        $pro = model('Product')->where('id', $product_id)->find();
        if ($pro) {
            $r = $pro->save(['is_index' => $is_index]);
            if ($r) {
                exit_json();
            } else {
                exit_json(-1, '设置失败');
            }
        } else {
            exit_json(-1, '商品不存在');
        }

    }

    /**
     * 设为热销
     */
    public function setHot()
    {
        $product_id = input('product_id');
        $is_index = input('is_hot');
        $pro = model('Product')->where('id', $product_id)->find();
        if ($pro) {
            $r = $pro->save(['is_hot' => $is_index]);
            if ($r) {
                exit_json();
            } else {
                exit_json(-1, '设置失败');
            }
        } else {
            exit_json(-1, '商品不存在');
        }

    }
    /**
     * 设为热销
     */
    public function setStatus()
    {
        $product_id = input('product_id');
        $is_index = input('is_hot');
        $name = input("name");
        $pro = model('Product')->where('id', $product_id)->find();
        if ($pro) {
            $r = $pro->save(["$name" => $is_index]);
            if ($r) {
                exit_json();
            } else {
                exit_json(-1, '设置失败');
            }
        } else {
            exit_json(-1, '商品不存在');
        }

    }

    /**
     * 上下架商品
     */
    public function upAndDown()
    {
        $id = input("id");
        $is_up = input("is_up");
        $res = model("Product")->where("id", $id)->find()->save(["is_up"=>$is_up]);
        if($res){
            exit_json();
        }else{
            exit_json(-1, "操作失败");
        }
        
    }

    /**
     * 导出商品
     */
    public function download()
    {

//        商品库的批量修改（产品名称，返利【代理商返利，销售人员返利，兼职人员返利】，运费，首页显示，热销产品，排序，规格[单价，件价，库存量]，分类【可以多分类】，商品上下架）
        $product = new \app\common\model\Product();
        $product_list = $product->getAllProducts();
        $product_header = [
            "产品ID",
            "产品名称",
            "代理商返利(%)",
            "销售人员返利(%)",
            "兼职人员返利(%)",
            "运费",
            "首页显示",
            "热销产品",
            "排序",
            "分类",
            "是否上架"
        ];
        //获取所有商品规格
        $prop = new ProductAttr();
        $prop_list = $prop->getAllProp();
        $prop_header = [
            "规格ID",
            "产品ID",
            "产品名称",
            "规格名称",
            "库存",
            "单价",
            "件价",
        ];
        $header = [
            $product_header,
            $prop_header
        ];
        $data = [
            $product_list,
            $prop_list
        ];
        echo \Excel::export($header, $data, "产品列表", 2, ["产品列表", "规格列表"]);
        exit;
    }

    /**
     * 导入商品列表
     */
    public function loadExcel()
    {
        if(request()->isAjax()){
            $file = request()->file("product_file");
            is_dir(__STATIC__."/product") or mkdir(__STATIC__."/product", "0777");
            if(!$file->checkExt(["xls", "xlsx"])){
                exit_json(-1, "文件类型错误, 请上传标准的excel文档");
            }
            $file_name = md5(time().rand(1000, 9999)).".".pathinfo($file->getInfo("name"), PATHINFO_EXTENSION);
            if($file->move(__STATIC__."/product", $file_name)){
                $file_path = __STATIC__."/product/".$file_name;
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($ext == "xls"){
                    $type = "Excel5";
                }elseif($ext == "xlsx"){
                    $type = "Excel2007";
                }else{
                    exit_json(-1, "文件类型错误, 请上传标准的excel文档");
                }
                //解析数据表格
                $header = [
                    ["A"=>"product_id", "B"=>"product_name", "C"=>"agent_commission", "D"=>"salesman_commission", "E"=>"parttime_commission", "F"=>"send_fee", "G"=>"is_index", "H"=>"is_hot", "I"=>"ord", "J"=>"cate_name", "K"=>"is_up"],
                    ["A"=>"prop_id", "B"=>"product_id", "C"=>"product_name", "D"=>"prop_name", "E"=>"remain", "F"=>"price_one", "G"=>"price_comb"]
                ];
                try{
                    $data = \Excel::parse($file_path, $header, $type);
                    //处理商品数据信息
                    $product_info = $data[0]['data'];
                    $prop_info = $data[1]['data'];
                    foreach ($product_info as $item){
//                        die($item["ord"]);
                        //TODO 处理商品分类信息
                        model("Product")->save([
                            "agent_commission"=>$item["agent_commission"],
                            "salesman_commission"=>$item["salesman_commission"],
                            "is_hot"=>$item["is_hot"]=="是"?1:0,
                            "is_index"=>$item["is_index"]=="是"?1:0,
                            "is_up"=>$item["is_up"]=="是"?1:0,
                            "send_fee"=>$item["send_fee"]?:0,
                            "parttime_commission"=>$item["parttime_commission"]?:0,
                            "ord"=>$item["ord"]?:0,
                            "name"=>$item["product_name"],
                        ], ["id"=>$item["product_id"]]);
                    }
                    foreach($prop_info as $val){
                        model("ProductAttr")->save([
                            "price_one"=>$val["price_one"],
                            "price_comb"=>$val["price_comb"],
                            "remain"=>$val["remain"]
                        ], ["id"=>$val["prop_id"], "product_id"=>$val["product_id"]]);
                    }
                    $code = 1;
                    $msg = "上传更新成功";

                }catch (Exception $e){
                    $code = -1;
                    $msg = $e->getMessage();
                }
                @unlink($file_path);
            }else{
                $code = -1;
                $msg = $file->getError();
            }
            exit_json($code, $msg);
        }
        return $this->fetch();
    }




}