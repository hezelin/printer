<?php
use yii\helpers\Url;

$this->title = '查看资料';
use app\assets\HomeAsset;
HomeAsset::register($this);
?>

<div class="aui-content">
    <ul class="aui-list-view aui-in">
        <li class="aui-list-view-cell">
            <a href="<?= url::toRoute(['/user/rent/list','id'=>$id]) ?>" class="aui-arrow-right aui-text-default">
                <span class="iconfont icon-chanpin aui-color aui-size-20"></span>&nbsp;租机方案
            </a>
        </li>
    </ul>
    <ul class="aui-list-view aui-in">
        <li class="aui-list-view-cell">
            <a href="<?= Url::toRoute(['/shop/parts/list','id'=>$id]) ?>" class="aui-arrow-right aui-text-default">
                <span class="iconfont icon-peizhi aui-color aui-size-20"></span>&nbsp;配件查看
            </a>
        </li>
    </ul>

    <ul class="aui-list-view aui-in">
        <li class="aui-list-view-cell">
            <a href="<?= Url::toRoute(['/maintain/help/use','id'=>$id]) ?>" class="aui-arrow-right aui-text-default">
                <span class="iconfont icon-bangzhu aui-color aui-size-20"></span>&nbsp;维修帮助
            </a>
        </li>
    </ul>

</div>