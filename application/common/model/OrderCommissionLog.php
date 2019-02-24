<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/21
 * Time: 13:34
 */

namespace app\common\model;


use think\Model;

class OrderCommissionLog extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }
    protected $autoWriteTimestamp=true;

}