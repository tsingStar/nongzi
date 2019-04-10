<?php
class Config{
    private $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        'mchId'=>'755437000006',
        'key'=>'7daa4babae15ae17eee90c9e',
        'appid'=>'wx53e95af02a13f9b0',
        'version'=>'1.0',
        'sign_type'=>'MD5'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>