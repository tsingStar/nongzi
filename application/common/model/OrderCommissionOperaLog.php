<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/24
 * Time: 10:23
 */

namespace app\common\model;


use think\Model;

class OrderCommissionOperaLog extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }
    protected $autoWriteTimestamp = true;

}