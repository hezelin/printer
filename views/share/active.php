<?php
use yii\helpers\Url;
$this->title = '最新活动';
use app\assets\HomeAsset;
HomeAsset::register($this);
?>
<style>
    .aui-pull-center{
        /* Safari and Chrome */
        position:absolute;top:50%;left:50%; transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);
    }
    .aui-extend-circle{
        float:left;height:20px; line-height: 19px; padding:0 6px; margin-top:13px; margin-left:5px;
    }
</style>
<header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center; aui-col-xs-12">
    <div class="aui-pull-center">
        <span class="aui-pull-left" style="padding:0;">最新活动</span>
    </div>
</header>
<div>
    <h3 class="blank-text" style="font-size: 16px; padding-top:80px;">暂时没有最新活动</h3>
</div>

