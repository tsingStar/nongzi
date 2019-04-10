<?php
/**
 * 购物车验证器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.5.22
 * Time: 11:53
 */

namespace app\app\validate;


use think\Validate;

class ShopCartValidate extends Validate
{
    protected $rule = [
        'shop_id'=>'require',
        'user_id'=>'require',
        'good_id'=>'require',
        'num'=>'require|number'
    ];
    protected $message = [
        'shop_id'=>[
            'require'=>'店铺参数错误'
        ],
        'user_id'=>[
            'require'=>'用户参数错误'
        ],
        'good_id'=>[
            'require'=>'商品参数错误'
        ],
        'num'=>[
            'require'=>'数量不能为空',
            'number' =>'数量格式错误'
        ]
    ];

}