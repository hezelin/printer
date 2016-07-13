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
        'css/home.css',  //位于web文件夹而非assets
    ];
    public $js = [
//        'js/home/button_menu.js'
        'js/Swiper/dist/js/swiper.min.js'
    ];
    public $depends = [
//        'app\assets\IscrollAsset',
//        'app\assets\ZeptoAsset',
    ];
}


