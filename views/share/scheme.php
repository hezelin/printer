<?php
use yii\helpers\Url;

$this->title = '赚钱积分';
?>
<style>
    body{background-color: #FFFFFF !important;}
    .h-dl{
        color: #999;
        padding: 15px;
        font-size: 12px;
    }
    .h-dl dt{line-height: 36px;}
    .h-dl dd{line-height: 30px;}
</style>
<div id="h-color-list">
    <a class="color-row" style="background-color: #ffcb26" href="http://v.xiumi.us/stage/v3/24gw2/1634262" >
        分享租机方案
        <b class="icon-go-right"></b>
    </a>
    <a class="color-row" style="background-color: #b4db68" href="<?= Url::toRoute(['m/initiative','id'=>$id]) ?>" >
        分享打印方案
        <b class="icon-go-right"></b>
    </a>
    <a class="color-row" style="background-color: #87dbce" href="<?= Url::toRoute(['help/service','id'=>$id]) ?>" >
        分享付费维修
        <b class="icon-go-right"></b>
    </a>
</div>

<dl class="h-dl">
    <dt>积分方案</dt>
    <dd>1、分享租机方案，用户成功租机一次性获得1000积分</dd>
    <dd>2、分享打印方案，用户每次打印金额10%积分，并且是永久性哦</dd>
    <dd>3、分享付费维修方案，用户每次维修金额10%积分</dd>
</dl>