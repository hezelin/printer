<?php

namespace app\assets;
use yii\web\AssetBundle;


class IscrollAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/iscroll5.min.js'
    ];
    public $depends = [];
}
