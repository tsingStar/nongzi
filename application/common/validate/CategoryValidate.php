<?php
/**
 * 分类验证器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17
 * Time: 9:23
 */

namespace app\common\validate;


use think\Validate;

class CategoryValidate extends Validate
{
    protected $rule = [
        'name'=>'require'
    ];

    protected $message = [
        'name'=>[
            'require'=>'名称不能为空'
        ]
    ];

}