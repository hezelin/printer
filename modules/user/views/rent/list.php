<?php
use yii\helpers\Url;
$this->title = '机器列表';
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

<div class="aui-content" id="task-list">
    <?php if($model):?>
        <header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center; aui-col-xs-12">
            <div class="aui-pull-center">
                <span class="aui-pull-left">租赁方案列表</span>
                <span class="aui-badge aui-badge-danger aui-extend-circle" style=""><?=count($model)?></span>
            </div>
        </header>
        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <div class="aui-overflow-hidden">
                    <li class="aui-list-view-cell aui-img" style="margin-top:10px;">
                        <a href="<?=url::toRoute(['/user/rent/detail','id'=>$id,'project_id'=>$row['id']])?>">
                            <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                            <div class="aui-img-body aui-arrow-right">
                                <h2 class="aui-ellipsis-1"><?=$row['brand_name'],$row['model_name']?></h2>
                                <p class="aui-ellipsis-1 aui-text-default">
                                    月租 : <span class="aui-red-color">¥<?= $row['lowest_expense']?></span>
                                </p>
                                <p class="aui-ellipsis-1 aui-color-000">
                                    黑白: <span class="aui-red-color"><?= \app\models\config\Tool::schemePrice($row['black_white'])?></span>
                                </p>
                                <?php if($row['colours']):?>
                                <p class="aui-ellipsis-1 aui-color-000">
                                    彩色: <span class="aui-red-color"><?= \app\models\config\Tool::schemePrice($row['colours'])?></span>
                                </p>
                                <?php endif;?>
                            </div>
                        </a>
                    </li>
                </div>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span> 还没有租赁方案</div>
    <?php endif;?>
</div>