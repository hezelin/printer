<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '通知记录';
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
        <span class="aui-pull-left">最新通知</span>
        <span class="aui-badge aui-badge-danger aui-extend-circle" style=""><?=$count?></span>
    </div>
</header>

<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <li class="aui-list-view-cell aui-img">
                        <div class="aui-img-body">
                            <p class="aui-ellipsis-2">描述：<?=$row['text']?></p>
                            <p class="aui-pull-right"><span class="iconfont icon-shijian"></span> <?=date('m月d日 H:i',$row['add_time'])?></p>
                        </div>
                </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="aui-padded-10 aui-text-center" style="font-size: 14px; margin-top:160px;">目前没有任何通知</div>
    <?php endif;?>
</div>