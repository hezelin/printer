<?php
use yii\helpers\Url;
$this->title = '维修主页';
?>

<div class="aui-content" id="home-fault">
    <ul class="aui-list-view aui-grid-view">
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['m/initiative','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="aui-iconfont aui-icon-pullup aui-color"></span>
                <h2 class="aui-img-body aui-text-default">主动接单</h2>
                <?php if($num['order']>0):?>
                    <span class="aui-badge aui-badge-danger">43<?=$num['order']?></span>
                <?php endif;?>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['m/task','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="aui-iconfont aui-icon-repair aui-color"></span>
                <h2 class="aui-img-body aui-text-default">维修任务</h2>
                <?php if($num['fault']>0):?>
                    <span class="aui-badge aui-badge-danger"><?=$num['fault']?></span>
                <?php endif;?>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['/shop/parts/my','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="aui-iconfont aui-icon-list aui-color"></span>
                <h2 class="aui-img-body aui-text-default">配件列表</h2>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['m/help','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="aui-iconfont aui-icon-form aui-color"></span>
                <h2 class="aui-img-body aui-text-default">查看资料</h2>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['m/index','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="aui-iconfont aui-icon-rank aui-color"></span>
                <h2 class="aui-img-body aui-text-default">我的业绩</h2>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['m/notice','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="aui-iconfont aui-icon-notice aui-color"></span>
                <h2 class="aui-img-body aui-text-default">最新通知</h2>
                <?php if($num['new']>0):?>
                    <span class="aui-badge aui-badge-danger"><?=$num['new']?></span>
                <?php endif;?>
            </a>
        </li>
    </ul>
</div>
