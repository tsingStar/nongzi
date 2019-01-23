<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/23
 * Time: 13:43
 */

namespace app\common\model;


use think\Model;

class CustomOperaLog extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp = true;

}