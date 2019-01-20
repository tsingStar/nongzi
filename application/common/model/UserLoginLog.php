<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/7
 * Time: 20:51
 */

namespace app\common\model;


use think\Model;

class UserLoginLog extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp = true;
}