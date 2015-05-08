<?php

namespace app\assets;
use yii\web\AssetBundle;

/*
 * 微信 js sdk 资源
 */
class WxjssdkAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = ['http://res.wx.qq.com/open/js/jweixin-1.0.0.js'];
    public $depends = [];
}
