<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/24
 * Time: 13:38
 */

namespace app\common\model;


use think\Model;

class WithdrawLog extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }
    protected $autoWriteTimestamp = true;

}