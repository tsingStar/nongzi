<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/23
 * Time: 23:38
 */

namespace app\common\model;


use think\Model;

class ProductCommissionChangeLog extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected $autoWriteTimestamp=true;
}