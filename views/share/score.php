<?php
use yii\helpers\Url;

$this->title = '赚钱积分';
?>

<div id="h-color-list">
    <a class="color-row" style="background-color: #ffcb26" href="<?= Url::toRoute(['rent/list','id'=>$id]) ?>" >
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