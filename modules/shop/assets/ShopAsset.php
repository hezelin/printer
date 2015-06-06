<?php
/**
 * 商城资源包
 * 封装到模块中
 */

namespace app\modules\shop\assets;

use yii\web\AssetBundle;

class ShopAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/shop.css',  //位于web文件夹而非assets
    ];
    public $js = [
    ];
    public $depends = [
        'app\assets\ZeptoAsset',
    ];
}


