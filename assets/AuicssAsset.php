<?php
/**
 * aui css 框架
 */

namespace app\assets;

use yii\web\AssetBundle;

class AuicssAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/aui/css/aui.css',
        'css/home_default.css',
    ];
    public $js = [
//        'js/home/button_menu.js'
    ];
    public $depends = [
//        'app\assets\IscrollAsset',
//        'app\assets\ZeptoAsset',
    ];
}


