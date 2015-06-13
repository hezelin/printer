<?php
use yii\helpers\Url;

$this->title = '查看资料';
?>

<div id="h-color-list">
    <a class="color-row" style="background-color: #ffcb26" href="<?= Url::toRoute(['rent/list','id'=>$id]) ?>" >
        租机方案
        <b class="icon-go-right"></b>
    </a>
    <a class="color-row" style="background-color: #b4db68" href="<?= Url::toRoute(['/shop/parts/list','id'=>$id]) ?>" >
        配件查看
        <b class="icon-go-right"></b>

    </a>
    <a class="color-row" style="background-color: #87dbce" href="<?= Url::toRoute(['help/service','id'=>$id]) ?>" >
        维修帮助
        <b class="icon-go-right"></b>

    </a>
</div>