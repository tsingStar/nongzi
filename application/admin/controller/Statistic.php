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
        set_time_limit(0);
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
        $this->assign("list", array_reverse($list));
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
        //产品分类访问，产品详情访问，产品搜索访问、用户登录
        $cate_count = model("CateCollect")->where("days", $date)->sum("nums");
        $product_count = model("CollectProduct")->where("create_time", "gt", strtotime($date))->where("create_time", "lt", strtotime($date) + 86400)->count();
        $keys_count = model("KeysCollect")->where("create_time", "gt", strtotime($date))->where("create_time", "lt", strtotime($date) + 86400)->count();
        $login_count = model("UserLoginLog")->where("create_time", "gt", strtotime($date))->where("create_time", "lt", strtotime($date) + 86400)->count();
        $t = [
            "date" => $date,
            "user_num" => $user_num,
            "android" => $android,
            "ios" => $ios,
            "wapp" => $wapp,
            "cate_count" => $cate_count,
            "product_count" => $product_count,
            "keys_count" => $keys_count,
            "login_count" => $login_count
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
        if (isset($param["telephone"]) && $param["telephone"] != "") {
            $model->where("b.user_name|b.telephone", "like", "%" . $param["telephone"] . "%");
        }
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order("a.create_time desc")->paginate(20, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        $this->assign("server", ["1" => "安卓", "2" => "苹果", "3" => "小程序", "" => "未知"]);
        return $this->fetch();
    }

    public function userLoginDown()
    {
        set_time_limit(0);
        $start_time = input("start_time");
        $end_time = input("end_time");
        $telephone = input("telephone");
        $model = model("UserLoginLog")->alias("a");
        if ($telephone != "") {
            $model->where("b.user_name|b.telephone", "like", "%" . $telephone . "%");
        }
        if ($start_time != "") {
            $model->where("a.create_time", "gt", strtotime($start_time));
        }
        if ($end_time != "") {
            $model->where("a.create_time", "lt", strtotime($end_time) + 86400);
        }
        $server = ["1" => "安卓", "2" => "苹果", "3" => "小程序", "" => "未知"];
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order("a.create_time desc")->select();
//        $table = '<tr><td>用户ID</td><td>用户名</td><td>手机号</td><td>登录设备</td><td>登录时间</td></tr>';
        $header = [
            "用户ID",
            "用户名",
            "手机号",
            "登录设备",
            "登陆时间"
        ];
        $data = [];
        foreach ($list as $item) {
            $type = $server[$item['type']];
            $data[] = [
                $item["user_id"],
                $item["user_name"],
                $item["telephone"],
                $type,
                $item["create_time"]
            ];
//            $table .= '<tr><td>' . $item["user_id"] . '</td><td>' . $item["user_name"] . '</td><td>' . $item["telephone"] . '</td><td>' . $type . '</td><td>' . $item["create_time"] . '</td></tr>';
        }
        echo \Excel::export($header, $data, "登录统计");
        exit;
        $this->assign("table", $table);
        $this->assign("filename", "登录统计.xlsx");
        $this->assign("title", "登录统计");
        return $this->fetch("excel");
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
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("ProductCate b", "a.cate_id=b.id")->join("User c", "a.user_id=c.id", "left")->field("a.*, b.name, c.user_name, c.telephone")->order("days desc")->paginate(20, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    public function cateDown()
    {
        $param = input("get.");
        $model = model("CateCollect")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("ProductCate b", "a.cate_id=b.id")->join("User c", "a.user_id=c.id", "left")->field("a.*, b.name, c.user_name, c.telephone")->order("days desc")->select();
//        $table = '<tr>
//            <th>用户ID</th>
//            <th>日期</th>
//            <th>分类名称</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>点击次数</th></tr>';
        $header = [
            "用户ID",
            "日期",
            "分类名称",
            "用户名",
            "手机号",
            "点击次数"
        ];
        $data = [];
        foreach ($list as $item) {
            $data[] = [
                $item["user_id"],
                $item["days"],
                $item["name"],
                $item["user_name"],
                $item["telephone"],
                $item["nums"]
            ];
//            $table .= '<tr><td>' . $item["user_id"] . '</td><td>' . $item["days"] . '</td><td>' . $item["name"] . '</td><td>' . $item["user_name"] . '</td><td>' . $item["telephone"] . '</td><td>' . $item["nums"] . '</td></tr>';
        }
        echo \Excel::export($header, $data, "分类统计");
        exit;
        $this->assign("table", $table);
        $this->assign("filename", "分类统计.xlsx");
        $this->assign("title", "分类统计");
        return $this->fetch("excel");

    }

    /**
     * 搜索统计
     */
    public function keysCollect()
    {
        $param = input("get.");
        $model = model("KeysCollect")->alias("a");
        if (isset($param['mintime']) && $param["mintime"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["mintime"]));
        }
        if (isset($param['maxtime']) && $param["maxtime"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["maxtime"]) + 86400);
        }
        if (isset($param["user_name"]) && $param["user_name"] != "") {
            $model->whereLike("b.user_name|b.telephone|a.keys", "%" . $param["user_name"] . "%");
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order("a.create_time desc")->paginate(20, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    public function keysDown()
    {
        set_time_limit(0);
        $param = input("get.");
        $model = model("KeysCollect")->alias("a");
        if (isset($param['mintime']) && $param["mintime"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["mintime"]));
        }
        if (isset($param['maxtime']) && $param["maxtime"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["maxtime"]) + 86400);
        }
        if (isset($param["user_name"]) && $param["user_name"] != "") {
            $model->whereLike("b.user_name|b.telephone|a.keys", "%" . $param["user_name"] . "%");
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order("a.create_time desc")->select();
        $table = '<tr>
            <th>用户ID</th>
            <th>日期</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>关键词</th></tr>';
        foreach ($list as $item) {
            $table .= '<tr>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["create_time"] . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td>' . $item["keys"] . '</td>';
        }

        $this->assign("table", $table);
        $this->assign("filename", "产品搜索.xlsx");
        $this->assign("title", "产品搜索");
        return $this->fetch("excel");
    }

    /**
     * 产品详情
     */
    public function productCollect()
    {
        $param = input("get.");
        $model = model("CollectProduct");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("create_time", "lt", strtotime($param["end_time"]) + 86400);
        }

        $list = $model->order("create_time desc")->paginate(15, false, ["query" => $param]);
        $admin = model("Admins")->column("name", "vip_code");
        foreach ($list as $item) {
            $user = model("User")->where("id", $item["user_id"])->find();
            if ($user) {
                $item["user_name"] = $user["user_name"];
                $item["telephone"] = $user["telephone"];
                $item["name"] = isset($admin[$user["vip_code"]]) ? $admin[$user["vip_code"]] : "";
            } else {
                $item["user_name"] = "";
                $item["telephone"] = "";
                $item["name"] = "";
            }
        }
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    public function productDown()
    {
        set_time_limit(0);
        $param = input("get.");
        $model = model("CollectProduct");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("create_time", "lt", strtotime($param["end_time"]) + 86400);
        }

        $list = $model->order("create_time desc")->select();
        $admin = model("Admins")->column("name", "vip_code");
        $table = '<tr>
            <th>日期</th>
            <th>用户名</th>
            <th>用户ID</th>
            <th>手机号</th>
            <th>所属客服</th>
            <th>商品ID</th>
            <th>商品名称</th></tr>';
        foreach ($list as $item) {
            $user = model("User")->where("id", $item["user_id"])->find();
            if ($user) {
                $item["user_name"] = $user["user_name"];
                $item["telephone"] = $user["telephone"];
                $item["name"] = isset($admin[$user["vip_code"]]) ? $admin[$user["vip_code"]] : "";
            } else {
                $item["user_name"] = "";
                $item["telephone"] = "";
                $item["name"] = "";
            }
            $table .= '<tr>
            <td>' . $item["create_time"] . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td>' . $item["name"] . '</td>
            <td>' . $item["product_id"] . '</td>
            <td>' . $item["product_name"] . '</td></tr>';
        }

        $this->assign("table", $table);
        $this->assign("filename", "产品详情访问.xlsx");
        $this->assign("title", "产品详情访问");
        return $this->fetch("excel");
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
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order("a.create_time desc")->paginate(15, false, ["query" => $param]);
        $this->assign("param", $param);
        $this->assign("list", $list);
        return $this->fetch();
    }

    public function supplyDown()
    {
        set_time_limit(0);
        $param = input("get.");
        $model = model("SupplyLog")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order("a.create_time desc")->select();
        $table = '<tr>
            <th>用户ID</th>
            <th>日期</th>
            <th>类型</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>供求id</th></tr>';
        foreach ($list as $item) {
            $type = $item['type'] == 1 ? '求购' : '供应';
            $table .= '<tr>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["create_time"] . '</td>
            <td>' . $type . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td>' . $item["supply_id"] . '</td>';
        }

        $this->assign("table", $table);
        $this->assign("filename", "供求统计.xlsx");
        $this->assign("title", "供求统计");
        return $this->fetch("excel");

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
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order('a.create_time desc')->paginate(15, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    /**
     * 客服下载
     */
    public function kefuDown()
    {
        $param = input("get.");
        $model = model("CustomService")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->field("a.*, b.user_name, b.telephone")->order('a.create_time desc')->select();
        $table = '<tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>联系客服时间</th></tr>';
        foreach ($list as $item) {
            $table .= '<tr>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td>' . $item["create_time"] . '</td>';
        }

        $this->assign("table", $table);
        $this->assign("filename", "客服统计.xlsx");
        $this->assign("title", "客服统计");
        return $this->fetch("excel");
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
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Product c", "a.product_id=c.id")->field('a.*, b.user_name, b.telephone, c.name')->order("a.create_time desc")->paginate(15, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }


    /**
     * 购物车下载
     */
    public function cartDown()
    {
        $param = input("get.");
        $model = model("CollectCart")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Product c", "a.product_id=c.id")->field('a.*, b.user_name, b.telephone, c.name')->order("a.create_time desc")->select();
        $table = '<tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>商品id</th>
            <th>商品名称</th>
            <th>操作时间</th></tr>';
        foreach ($list as $item) {
            $table .= '<tr>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td>' . $item["product_id"] . '</td>
            <td>' . $item["name"] . '</td>
            <td>' . $item["create_time"] . '</td>';
        }

        $this->assign("table", $table);
        $this->assign("filename", "购物车统计.xlsx");
        $this->assign("title", "购物车统计");
        return $this->fetch("excel");
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
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Order c", "a.order_no=c.order_no")->field("a.*, b.user_name, b.telephone, c.id order_id")->order("a.create_time desc")->paginate(15, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    public function payDown()
    {
        $param = input("get.");
        $model = model("CollectPay")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Order c", "a.order_no=c.order_no")->field("a.*, b.user_name, b.telephone, c.id order_id")->order("a.create_time desc")->select();
        $table = '<tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>订单编号</th>
            <th>操作时间</th></tr>';
        foreach ($list as $item) {
            $table .= '<tr>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td style="mso-number-format: \@;">' . $item["order_no"] . '</td>
            <td>' . $item["create_time"] . '</td>';
        }
        $this->assign("table", $table);
        $this->assign("filename", "支付统计.xlsx");
        $this->assign("title", "支付统计");
        return $this->fetch("excel");

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
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Order c", "a.order_no=c.order_no")->field("a.*, b.user_name, b.telephone, c.id order_id")->paginate(15, false, ["query" => $param]);
        $this->assign("list", $list);
        $this->assign("param", $param);
        return $this->fetch();
    }

    /**
     * 支付页面下载
     */
    public function orderDown()
    {
        $param = input("get.");
        $model = model("CollectOrder")->alias("a");
        if (isset($param['start_time']) && $param["start_time"] != "") {
            $model->where("a.create_time", "gt", strtotime($param["start_time"]));
        }
        if (isset($param['end_time']) && $param["end_time"] != "") {
            $model->where("a.create_time", "lt", strtotime($param["end_time"]) + 86400);
        }
        $list = $model->join("User b", "a.user_id=b.id", "left")->join("Order c", "a.order_no=c.order_no")->field("a.*, b.user_name, b.telephone, c.id order_id")->select();
        $table = '<tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>订单编号</th>
            <th>查询次数</th>
            <th>操作时间</th></tr>';
        foreach ($list as $item) {
            $table .= '<tr>
            <td>' . $item["user_id"] . '</td>
            <td>' . $item["user_name"] . '</td>
            <td>' . $item["telephone"] . '</td>
            <td>' . $item["order_no"] . '</td>
            <td>' . $item["nums"] . '</td>
            <td>' . $item["create_time"] . '</td>';
        }

        $this->assign("table", $table);
        $this->assign("filename", "订单页统计.xlsx");
        $this->assign("title", "订单页统计");
        return $this->fetch("excel");
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
        $param = input("get.");
        $this->assign("param", $param);
        $user = model("User")->where("telephone", $telephone)->find();
        if (!$user) {
            echo "<script>alert('用户不存在')</script>";
            exit;
        }
        $this->assign("server", ["1" => "安卓", "2" => "苹果", "3" => "小程序", "" => "未知"]);
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
                $list = $model->where("user_id", $user["id"])->order("create_time desc")->paginate(15, false, ["query" => $param]);
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
                $list = $model->alias("a")->join("ProductCate b", "a.cate_id=b.id")->where("a.user_id", $user["id"])->field("a.*, b.name")->order("days desc")->paginate(15, false, ["query" => $param]);
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
                $list = $model->paginate(15, false, ["query" => $param]);
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
                $list = $model->paginate(15, false, ["query" => $param]);
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
                $list = $model->paginate(15, false, ["query" => $param]);
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
                $list = $model->paginate(15, false, ["query" => $param]);
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
                $list = $model->join("Product c", "a.product_id=c.id")->field('a.*, c.name')->paginate(15, false, ["query" => $param]);
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
                $list = $model->paginate(15, false, ["query" => $param]);
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
                $list = $model->join("Order c", "a.order_no=c.order_no")->field("a.*, c.id order_id")->paginate(15, false, ["query" => $param]);
                $this->assign("list", $list);
                return $this->fetch("userPay");
                break;
            default:
                exit("参数错误");
        }
    }

    public function userDown()
    {
        $telephone = input("telephone");
        $start_time = input("start_time");
        $end_time = input("end_time");
        $type = input("type");
        $param = input("get.");
        $this->assign("param", $param);
        $user = model("User")->where("telephone", $telephone)->find();
        if (!$user) {
            echo "<script>alert('用户不存在')</script>";
            exit;
        }
        $server = ["1" => "安卓", "2" => "苹果", "3" => "小程序", "" => "未知"];
//        $this->assign("server", ["1" => "安卓", "2" => "苹果", "3" => "小程序", "" => "未知"]);
//        $this->assign("user", $user);
        $admin = model("Admins")->where("vip_code", $user["vip_code"])->find();
//        $this->assign("admin", $admin);
        switch ($type) {
            case 1:
                $model = model("UserLoginLog");
                if ($start_time) {
                    $model->where("create_time", "gt", strtotime($start_time));
                }
                if ($end_time) {
                    $model->where("create_time", "lt", strtotime($end_time) + 86400);
                }
                $list = $model->where("user_id", $user["id"])->order("create_time desc")->select();
                $title = "用户登录";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>登录设备</th>
//            <th>登录时间</th></tr>';
                $data = [];
                foreach ($list as $item) {
                    $type = $server[$item['type']];
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $type . '</td>
//            <td>' . $item["create_time"] . '</td>';
                    $data[] = [
                        $user["id"],$user["user_name"],$user["telephone"],$type,$item["create_time"]
                    ];
                }
                $header = ["用户ID", "用户名", "手机号", "登录设备", "登录时间"];

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
                $title = "分类访问统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>分类名称</th>
//            <th>日期</th>
//            <th>访问次数</th></tr>';
                $header = [
                    "用户ID","用户名","手机号","分类名称","日期","访问次数"
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["name"] . '</td>
//            <td>' . $item["days"] . '</td>
//            <td>' . $item["nums"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["name"],
                        $item["days"],
                        $item["nums"]
                    ];
                }
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

                $title = "产品搜索统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>日期</th>
//            <th>关键词</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "日期",
                    "关键词",
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["create_time"] . '</td>
//            <td>' . $item["keys"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["create_time"],
                        $item["keys"]
                    ];
                }
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
                $title = "产品详情统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>访问时间</th>
//            <th>销售客服</th>
//            <th>商品id</th>
//            <th>商品名称</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "访问时间",
                    "销售客服",
                    "商品id",
                    "商品名称",
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["create_time"] . '</td>
//            <td>' . $admin["name"] . '</td>
//            <td>' . $item["product_id"] . '</td>
//            <td>' . $item["product_name"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["create_time"],
                        $admin["name"],
                        $item["product_id"],
                        $item["product_name"]
                    ];
                }
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
                $title = "供求统计";
                $table = '<tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>手机号</th>
            <th>访问时间</th>
            <th>类型</th>
            <th>供求id</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "访问时间",
                    "类型",
                    "供求id"
                ];
                $data = [];
                foreach ($list as $item) {
                    if($item['type'] == 1){
                        $type = "求购";
                    }else{
                        $type = "供应";
                    }
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["create_time"] . '</td>
//            <td>' . $type . '</td>
//            <td>' . $item["supply_id"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["create_time"],
                        $type,
                        $item["supply_id"]
                    ];
                }
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
                $title = "客服统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>访问时间</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "访问时间"
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["create_time"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["create_time"]
                    ];
                }
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

                $title = "购物车统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>加入购物车时间</th>
//            <th>商品名称</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "加入购物车时间",
                    "商品名称",
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["create_time"] . '</td>
//            <td>' . $item["name"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["create_time"],
                        $item["name"]
                    ];
                }
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
                $title = "订单访问统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>访问时间</th>
//            <th>订单编号</th>
//            <th>访问次数</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "访问时间",
                    "订单编号",
                    "访问次数",
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["day"] . '</td>
//            <td style="mso-number-format: \@">' . $item["order_no"] . '</td>
//            <td>' . $item["nums"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["day"],
                        $item["order_no"],
                        $item["nums"]
                    ];
                }
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
                $title = "支付统计";
//                $table = '<tr>
//            <th>用户ID</th>
//            <th>用户名</th>
//            <th>手机号</th>
//            <th>访问时间</th>
//            <th>订单编号</th></tr>';
                $header = [
                    "用户ID",
                    "用户名",
                    "手机号",
                    "访问时间",
                    "订单编号",
                ];
                $data = [];
                foreach ($list as $item) {
//                    $table .= '<tr>
//            <td>' . $user["id"] . '</td>
//            <td>' . $user["user_name"] . '</td>
//            <td>' . $user["telephone"] . '</td>
//            <td>' . $item["create_time"] . '</td>
//            <td style="mso-number-format: \@">' . $item["order_no"] . '</td>';
                    $data[] = [
                        $user["id"],
                        $user["user_name"],
                        $user["telephone"],
                        $item["create_time"],
                        $item["order_no"],
                    ];
                }
                break;
            default:
                exit("参数错误");
        }
        echo \Excel::export($header, $data, $title);
        exit;
        $this->assign("table", $table);
        $this->assign("filename", $title . ".xlsx");
        $this->assign("title", $title);
        return $this->fetch("excel");

    }

    public function supplyDetail()
    {
        $id = input("supply_id");
        $item = model('BuySupply')->alias('a')->join('User b', 'a.user_id=b.id', 'left')->where('a.id', $id)->field('a.*, b.user_name')->find();
        $this->assign("item", $item);
        return $this->fetch();

    }

    /**
     * 用户行为分析下载
     */
    public function collectExcel()
    {
        set_time_limit(0);
        $start = input("start");
        $end = input("end");
        if ($start == "") {
            $start = "2019-01-01";
        }
        if ($end == "") {
            $end = date("Y-m-d");
        }
        $telephone = input("telephone");
        if ($telephone) {

        } else {
            $data = [];
            $start_time = strtotime($start);
            $end_time = strtotime($end) + 86400;
            while ($start_time < $end_time) {
                $login_nums = model("UserLoginLog")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $cate_nums = model("CateCollect")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->sum("nums");
                $product_nums = model("CollectProduct")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $keywords_nums = model("KeysCollect")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $supply_nums = model("SupplyLog")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $custom_nums = model("CustomService")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $cart_nums = model("CollectCart")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $order_nums = model("CollectOrder")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->sum("nums");
                $pay_nums = model("CollectPay")->where("create_time", "gt", $start_time)->where("create_time", "lt", $start_time + 86400)->count();
                $data[date("Y-m-d", $start_time)] = [
                    "login_nums" => $login_nums,
                    "cate_nums" => $cate_nums,
                    "product_nums" => $product_nums,
                    "keywords_nums" => $keywords_nums,
                    "supply_nums" => $supply_nums,
                    "custom_nums" => $custom_nums,
                    "cart_nums" => $cart_nums,
                    "order_nums" => $order_nums,
                    "pay_nums" => $pay_nums
                ];
                $start_time += 86400;
            }
//            $table = "<tr><td>日期</td><td>登录次数</td><td>分类访问次数</td><td>产品详情访问次数</td><td>产品搜索次数</td><td>供求访问次数</td><td>客服访问次数</td><td>购物车访问次数</td><td>订单页访问</td><td>支付页访问</td></tr>";
            $header = [
                "日期",
                "登录次数",
                "分类访问次数",
                "产品详情访问次数",
                "产品搜索次数",
                "供求访问次数",
                "客服访问次数",
                "购物车访问次数",
                "订单页访问",
                "支付页访问",
            ];
            $data1 = [];
            foreach ($data as $key => $val) {
//                $table .= "<tr><td>$key</td><td>" . $val['login_nums'] . "</td><td>" . $val['cate_nums'] . "</td><td>" . $val['product_nums'] . "</td><td>" . $val['keywords_nums'] . "</td><td>" . $val['supply_nums'] . "</td><td>" . $val['custom_nums'] . "</td><td>" . $val['cart_nums'] . "</td><td>" . $val['order_nums'] . "</td><td>" . $val['pay_nums'] . "</td></tr>";
                $data1[] = [
                    $key,
                    $val['login_nums'],
                    $val['cate_nums'],
                    $val['product_nums'],
                    $val['keywords_nums'],
                    $val['supply_nums'],
                    $val['custom_nums'],
                    $val['cart_nums'],
                    $val['order_nums'],
                    $val['pay_nums'],
                ];
            }
        }
        $title = "用户行为分析下载";
        echo \Excel::export($header, $data1, $title);
        exit;
        $filename = md5(time()) . ".xlsx";
        $this->assign("table", $table);
        $this->assign("title", $title);
        $this->assign("filename", $filename);
        return $this->fetch("excel");
    }

}