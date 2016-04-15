<?php
use yii\helpers\Url;
$this->title = '赚取积分';
?>

<style>
    body{ background-color: #fff !important;}
</style>
<div class="aui-padded-10 aui-bg-default"><p class="aui-border-left aui-p-l">我的积分</p></div>

<div id="user-score-top">
    <h1 class="my-score"><?=$totalScore?></h1>
</div>

<div class="aui-content" id="user-score">
    <ul class="aui-list-view aui-grid-view">
        <li class="aui-list-view-cell aui-img aui-col-xs-4">
            <a href="<?=Url::toRoute(['mp','id'=>$id])?>" class="link-wrap">
                <span class="iconfont icon-fenxiang aui-bg-color-3"></span>
                <h2 class="aui-img-body aui-text-default">分享公众号</h2>
            </a>
        </li>

        <li class="aui-list-view-cell aui-img aui-col-xs-4">
            <a class="link-wrap" href="<?=Url::toRoute(['rent','id'=>$id])?>">
                <span class="iconfont icon-yaoqing aui-bg-color-6"></span>
                <h2 id="code-loading" class="aui-img-body aui-text-default">邀请朋友租机</h2>
            </a>
        </li>

        <li class="aui-list-view-cell aui-img aui-col-xs-4">
            <a class="link-wrap" href="<?=Url::toRoute(['mall','id'=>$id])?>">
                <span class="iconfont icon-weibiaoti47 aui-bg-color-1"></span>
                <h2 id="code-loading" class="aui-img-body aui-text-default">分享办公耗材</h2>
            </a>
        </li>
    </ul>
</div>