<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/9
 * Time: 16:11
 */

namespace app\common\model;


use think\Model;

class CollectPay extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp = true;
}