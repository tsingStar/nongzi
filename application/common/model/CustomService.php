<?php
/**
 * Created by PhpStorm.
 * User: tsing
 * Date: 2019/1/9
 * Time: 8:33
 */

namespace app\common\model;


use think\Model;

class CustomService extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }
    protected $autoWriteTimestamp = true;

}