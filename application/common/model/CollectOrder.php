<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/10
 * Time: 16:09
 */

namespace app\common\model;


use think\Model;

class CollectOrder extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp = true;
}