<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/9
 * Time: 16:41
 */

namespace app\admin\controller;


class Statistic extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 基础统计
     */
    public function baseCount()
    {
        $start_time = input("mintime");
        $end_time = input("maxtime");

        $param = input("get.");
        $list = [];
        if (!$start_time && !$end_time) {
            $t = $this->getLaunchData(date("Y-m-d"));
            $list[] = $t;
        } else {
            if (!$start_time) {
                $start_time = date("2019-01-01");
            }
            if ($end_time) {
                if (strtotime($end_time) > time()) {
                    $end_time = date("Y-m-d");
                }
            }
            $secs = (strtotime($end_time) - strtotime($start_time) + 86400) / 86400;
            for ($i = 0; $i < $secs; $i++) {
                $day = date("Y-m-d", strtotime($start_time) + $i * 86400);
                $t = $this->getLaunchData($day);
                $list[] = $t;
            }

        }
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    public function getLaunchData($date)
    {
        //新增用户统计
        $user = model("User");
        $launch = model("LaunchData");
        $user_num = $user->where("create_time", "gt", strtotime($date))->where("create_time", "lt", strtotime($date) + 86400)->count();
        $android = $launch->where("type", 1)->where("launch_date", $date)->count();
        $ios = $launch->where("type", 2)->where("launch_date", $date)->count();
        $wapp = $launch->where("type", 3)->where("launch_date", $date)->count();
        $t = [
            "date" => $date,
            "user_num" => $user_num,
            "android" => $android,
            "ios" => $ios,
            "wapp" => $wapp
        ];
        return $t;

    }

    /**
     * 登录统计
     */
    public function userLogin()
    {
        $param = input("get.");
        $model = model("UserLoginLog")->alias("a");
        if (isset($param["user_name"]) && $param["user_name"] != "") {
            $model->where("b.user_name", "like", "%" . $param["user_name"] . "%")->whereOr("b.telephone", "%" . $param["user_name"] . "%");
        }
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->select();
        $this->assign("list", $list);
        $this->assign("param", $param);
        $this->assign("server", ["1" => "安卓", "2" => "苹果", "3" => "小程序", ""=>"未知"]);
        return $this->fetch();
    }

    /**
     * 产品分类统计
     */
    public function collectCate()
    {
        $param = input("get.");
        $model = model("CateCollect")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("ProductCate b", "a.cate_id=b.id")->join("User c", "a.user_id=c.id", "left")->field("a.*, b.name, c.user_name, c.telephone")->order("days desc")->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 搜索统计
     */
    public function keysCollect()
    {
        $param = input("get.");
        $model = model("KeysCollect")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 产品详情
     */
    public function productCollect()
    {
        $param = input("get.");
        $model = model("CollectProduct")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (!isset($param['start_time'])) {
        	$param['start_time'] = date("Y-m-d");
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        if (!isset($param['end_time'])) {
        	$param['end_time'] = date("Y-m-d");
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Admins c", "b.vip_code=c.vip_code", "left")->field("a.*, b.user_name, b.telephone, c.name")->select();
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    /**
     * 供求统计
     */
    public function supplyCollect()
    {
        $param = input("get.");
        $model = model("SupplyLog")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 客服统计
     */
    public function kefuCollect()
    {
        $param = input("get.");
        $model = model("CustomService")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 购物车统计
     */
    public function cartCollect()
    {
        $param = input("get.");
        $model = model("CollectCart")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Product c", "a.product_id=c.id")->field('a.*, b.user_name, b.telephone, c.name')->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 支付页面统计
     */
    public function payCollect()
    {
        $param = input("get.");
        $model = model("CollectPay")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Order c", "a.order_no=c.order_no")->field("a.*, b.user_name, b.telephone, c.id order_id")->select();
        $this->assign("list", $list);
        return $this->fetch();
    }
    /**
     * 支付页面统计
     */
    public function orderCollect()
    {
        $param = input("get.");
        $model = model("CollectOrder")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["start_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Order c", "a.order_no=c.order_no")->field("a.*, b.user_name, b.telephone, c.id order_id")->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * 用户分析统计
     */
    public function collect()
    {
        //统计行为事件 登录，产品列表访问， 产品详情访问， 产品搜索， 供求访问，客服页面访问， 购物车访问，订单页面访问， 支付页面访问
        $param = input("get.");
        $list = [];
        $user_login = model("UserLoginLog");
        $cate_collect = model("CateCollect");
        $product_collect = model("CollectProduct");
        $keywords = model("KeysCollect");
        $supply = model("SupplyLog");
        $custom_service = model("CustomService");
        $cart_collect = model("CollectCart");
        $order_collect = model("CollectOrder");
        $pay_collect = model("CollectPay");
        if (isset($param["telephone"]) && $param["telephone"] != '') {
            //有用户信息，精准查找用户基本行为
            $user = model("User")->where("telephone", $param["telephone"])->find();
            if (!$user) {
                echo "<script>layer.alert('用户不存在')</script>";
                exit;
            }
            $this->assign("user", $user);
            $user_id = $user["id"];
            $user_login->where("user_id", $user_id);
            $cate_collect->where("user_id", $user_id);
            $product_collect->where("user_id", $user_id);
            $keywords->where("user_id", $user_id);
            $supply->where("user_id", $user_id);
            $custom_service->where("user_id", $user_id);
            $cart_collect->where("user_id", $user_id);
            $order_collect->where("user_id", $user_id);
            $pay_collect->where("user_id", $user_id);
        }
        //平台行为基本统计
        if (isset($param["mintime"]) && $param["mintime"] != '') {
            $user_login->where("create_time", "gt", strtotime($param['mintime']));
            $cate_collect->where("create_time", "gt", strtotime($param['mintime']));
            $product_collect->where("create_time", "gt", strtotime($param['mintime']));
            $keywords->where("create_time", "gt", strtotime($param['mintime']));
            $supply->where("create_time", "gt", strtotime($param['mintime']));
            $custom_service->where("create_time", "gt", strtotime($param['mintime']));
            $cart_collect->where("create_time", "gt", strtotime($param['mintime']));
            $order_collect->where("create_time", "gt", strtotime($param['mintime']));
            $pay_collect->where("create_time", "gt", strtotime($param['mintime']));
        }
        if (isset($param["maxtime"]) && $param['maxtime'] != '') {
            $user_login->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $cate_collect->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $product_collect->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $keywords->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $supply->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $custom_service->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $cart_collect->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $order_collect->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
            $pay_collect->where("create_time", "lt", strtotime($param['maxtime']) + 86400);
        }
        $login_nums = $user_login->count();
        $cate_nums = $cate_collect->sum("nums");
        $product_nums = $product_collect->count();
        $keywords_nums = $keywords->count();
        $supply_nums = $supply->count();
        $custom_nums = $custom_service->count();
        $cart_nums = $cart_collect->count();
        $order_nums = $order_collect->sum("nums");
        $pay_nums = $pay_collect->count();
        $this->assign("item", [
            "login_nums" => $login_nums,
            "cate_nums" => $cate_nums,
            "product_nums" => $product_nums,
            "keywords_nums" => $keywords_nums,
            "supply_nums" => $supply_nums,
            "custom_nums" => $custom_nums,
            "cart_nums" => $cart_nums,
            "order_nums" => $order_nums,
            "pay_nums" => $pay_nums
        ]);
        $this->assign("param", $param);
        return $this->fetch();
    }

    /**
     * 查看详情
     */
    public function checkDetail()
    {
        $telephone = input("telephone");
        $start_time = input("start_time");
        $end_time = input("end_time");
        $type = input("type");
        $user = model("User")->where("telephone", $telephone)->find();
        if (!$user) {
            echo "<script>alert('用户不存在')</script>";
            exit;
        }
        $this->assign("server", ["1" => "安卓", "2" => "苹果", "3" => "小程序"]);
        $this->assign("user", $user);
        $admin = model("Admins")->where("vip_code", $user["vip_code"])->find();
        $this->assign("admin", $admin);
        switch ($type) {
            case 1:
                $model = model("UserLoginLog");
                if ($start_time) {
                    $model->where("create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("create_time", "lt", strtotime($end_time) + 86400);
                }
                $list = $model->where("user_id", $user["id"])->field("count(id) login_num, type")->group("type")->select();
                $this->assign("list", $list);
                return $this->fetch("userLoginLog");
                break;
            case 2:
                $model = model("CateCollect");
                if ($start_time) {
                    $model->where("a.create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("a.create_time", "lt", strtotime($end_time) + 86400);
                }
                $list = $model->alias("a")->join("ProductCate b", "a.cate_id=b.id")->where("a.user_id", $user["id"])->field("a.*, b.name")->order("days desc")->select();
                $this->assign("list", $list);
                return $this->fetch("userCate");
                break;
            case 4:
                $model = model("KeysCollect");
                $model->where("user_id", $user["id"]);
                if ($start_time) {
                    $model->where("create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("create_time", "lt", strtotime($end_time) + 86400);
                }
                $model->order("create_time desc");
                $list = $model->select();
                $this->assign("list", $list);
                return $this->fetch("userKeys");
                break;
            case 3:
                $model = model("CollectProduct");
                $model->where("user_id", $user["id"]);
                if ($start_time) {
                    $model->where("create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("create_time", "lt", strtotime($end_time) + 86400);
                }
                $model->order("create_time desc");
                $list = $model->select();
                $this->assign("list", $list);
                return $this->fetch("userProduct");
                break;

            case 5:
                $model = model("SupplyLog");
                $model->where("user_id", $user["id"]);
                if ($start_time) {
                    $model->where("create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("create_time", "lt", strtotime($end_time) + 86400);
                }
                $model->order("create_time desc");
                $list = $model->select();
                $this->assign("list", $list);
                return $this->fetch("userSupply");
                break;
            case 6:
                $model = model("CustomService");
                $model->where("user_id", $user["id"]);
                if ($start_time) {
                    $model->where("create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("create_time", "lt", strtotime($end_time) + 86400);
                }
                $model->order("create_time desc");
                $list = $model->select();
                $this->assign("list", $list);
                return $this->fetch("userCustom");
                break;
            case 7:
                $model = model("CollectCart")->alias("a");
                $model->where("a.user_id", $user["id"]);
                if ($start_time) {
                    $model->where("a.create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("a.create_time", "lt", strtotime($end_time) + 86400);
                }
                $model->order("a.create_time desc");
                $list = $model->join("Product c", "a.product_id=c.id")->field('a.*, c.name')->select();
                $this->assign("list", $list);
                return $this->fetch("userCart");
                break;
            case 8:
                $model = model("CollectOrder")->alias("a");
                $model->where("a.user_id", $user["id"]);
                if ($start_time) {
                    $model->where("a.create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("a.create_time", "lt", strtotime($end_time) + 86400);
                }
                $model->join("Order c", "a.order_no=c.order_no")->field("a.*, c.id order_id");
                $model->order("a.create_time desc");
                $list = $model->select();
                $this->assign("list", $list);
                return $this->fetch("userOrder");
                break;
            case 9:
                $model = model("CollectPay")->alias("a");
                $model->where("a.user_id", $user["id"]);
                if ($start_time) {
                    $model->where("a.create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("a.create_time", "lt", strtotime($end_time) + 86400);
                }
                $list = $model->join("Order c", "a.order_no=c.order_no")->field("a.*, c.id order_id")->select();
                $this->assign("list", $list);
                return $this->fetch("userPay");
                break;
            default:
                exit("参数错误");
        }
    }

    public function supplyDetail()
    {
        $id = input("supply_id");
        $item = model('BuySupply')->alias('a')->join('User b', 'a.user_id=b.id', 'left')->where('a.id', $id)->field('a.*, b.user_name')->find();
        $this->assign("item", $item);
        return $this->fetch();

    }

}