<?php
class Config{
    private $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        'mchId'=>'101540987026',
        'key'=>'951fd091554885dfb1dd973882671e72',
        'appid'=>'wx53e95af02a13f9b0',
        'version'=>'1.0',
        'sign_type'=>'MD5'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>