<?php
use yii\helpers\Url;

$this->title = '个人中心';

?>
<style>
    body{ background-color: #fff !important;}
</style>
<div id="user-score-top">
    <div class="top-content">
        <img class="cover" src="<?=substr($model['headimgurl'],0,-1)?>96" />
        <h2 class="top-nickname"><?=$model['nickname']?></h2>
        <span class="top-from"><?=$model['province'],$model['city']?></span>
    </div>

</div>

<div class="aui-content" id="user-score">
    <ul class="aui-list-view aui-grid-view">
        <li class="aui-list-view-cell aui-img aui-col-xs-4">
            <a href="<?= Url::toRoute(['/shop/i/order','id'=>$id]) ?>" class="link-wrap">
                <span class="iconfont icon-order aui-bg-color-1"></span>
                <h2 class="aui-img-body aui-text-default">我的订单</h2>
            </a>
        </li>

        <li class="aui-list-view-cell aui-img aui-col-xs-4">
            <a class="link-wrap" href="<?= Url::toRoute(['/shop/cart/list','id'=>$id]) ?>">
                <span class="iconfont icon-gouwuche aui-bg-color-4"></span>
                <h2 id="code-loading" class="aui-img-body aui-text-default">我的购物车</h2>
            </a>
        </li>

        <li class="aui-list-view-cell aui-img aui-col-xs-4">
            <a class="link-wrap" href="<?= Url::toRoute(['/shop/i/score','id'=>$id]) ?>">
                <span class="iconfont icon-jifen aui-bg-color-3"></span>
                <h2 id="code-loading" class="aui-img-body aui-text-default">我的积分</h2>
            </a>
        </li>
    </ul>
</div>