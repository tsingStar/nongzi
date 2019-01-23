<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/21
 * Time: 13:44
 */

namespace app\common\model;


use think\Model;

class UserAddInfo extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp= true;
}