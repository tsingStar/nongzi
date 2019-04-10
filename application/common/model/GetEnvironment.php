<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-16
 * Time: 09:03
 */

namespace app\common\model;


class GetEnvironment
{

    const PC = 'pc';

    const SP = 'sp';

    private $_splist = array('iPhone', 'Android', 'iPod', 'iPad', 'Tizen');//设置经常使用的sp终端,暂时常用的sp端就这几种,如果有的话大家也可以增加

    private $environment;

    public function __construct()
    {
        $this->setEnvironment();//通过setEnvironment()方法获取到$terminal变量的值
    }

    /*
     * function environment()
     * 输出终端信息
     * @return string
     */
    public function environment()
    {
        return $this->environment;
    }

    /*
     * function setEnvironment()
     * 获取终端信息
     * @return string
     */
    private function setEnvironment()
    {
        $isSp = self::PC;//如果是PC端，就不需要判断是安卓还是apple了，所以只输出pc就可以
        foreach ($this->_splist as $spname) {
            if (strstr($_SERVER['HTTP_USER_AGENT'], $spname)) {
                $isSp = $spname;
                break;
            }
        }
        return $this->environment = $isSp;
    }


}