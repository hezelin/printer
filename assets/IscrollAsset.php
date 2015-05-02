<?php

namespace app\assets;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the base javascript files for the Yii Framework.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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
