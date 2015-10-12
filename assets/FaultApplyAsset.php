<?php

namespace app\assets;
use yii\web\AssetBundle;

/*
 * 微信 js sdk 资源
 */
class FaultApplyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = ['js/mobile/apply.js'];
    public $depends = ['app\assets\ZeptoAsset'];
}
