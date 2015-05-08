<?php
/*
 * 微信 js sdk 部件
 */
namespace app\components;

use yii\base\Widget;
use app\assets\WxjssdkAsset;
use app\models\WxJsapi;

class WxjsapiWidget extends Widget{

//    公众号id
    public  $wx_id;

//    是否调试
    public $debug = false;

//    api 列表
    public $apiList = [];

//    wx.ready 内容
    public $jsReady;


    public function init(){
        parent::init();
    }

    public function run(){
        $this->registerScript();
        WxjssdkAsset::register( $this->getView() );
    }


    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {

        $api = new WxJsapi($this->wx_id);

        $script = <<<MODEL_JS
\n
 {$api->jsConfig($this->apiList,$this->debug)}
wx.ready(function () {
    {$this->jsReady}
});\n
MODEL_JS;

        $this->view->registerJs($script,\yii\web\View::POS_END);
    }
}
?>