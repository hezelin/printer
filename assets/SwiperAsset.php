<?php

namespace app\assets;
use yii\web\AssetBundle;


class SwiperAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/swiper-2.1.min.js'
    ];
    public $depends = [];
}
