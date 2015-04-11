<?php
/**
 * 微官网首页资源
 * By：OS
 */

namespace app\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/home/button_menu.css',  //位于web文件夹而非assets
        'css/home/iscroll.css',
        //'css/home/home-default.css',  //动态数据
    ];
    public $js = [
        'js/home/button_menu.js',
        'js/home/iscroll.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',  //引入jquery及yii.js
        //'yii\bootstrap\BootstrapAsset',
    ];
}
