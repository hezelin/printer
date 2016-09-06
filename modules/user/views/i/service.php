<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '维修记录';
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
<header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center aui-col-xs-12">
    <div class="aui-pull-center">
        <span class="aui-pull-left">维修记录</span>
        <span class="aui-badge aui-badge-danger aui-extend-circle" style=""><?=count($model)?></span>
    </div>
</header>
<div class="aui-content" id="task-list">
    <?php if( is_array($model) && $model ):?>

        <ul class="aui-list-view">
            <?php foreach($model as $row):?>
                <div class="aui-overflow-hidden aui-border-b">
                    <li class="aui-list-view-cell aui-img">
                        <a href="<?=url::toRoute(['/maintain/fault/detail','id'=>$id,'fault_id'=>$row['id']])?>">
                            <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                            <div class="aui-img-body">
                                <h2 class="aui-ellipsis-1">故障：<?=ConfigBase::getFaultStatus($row['type'])?></h2>
                                <p class="aui-ellipsis-2">描述：<?=$row['desc']?></p>
                                <p class="aui-ellipsis-1">时间：<?=date('m月d日 H:i',$row['add_time'])?></p>
                                <span class="aui-box aui-box-color"><?=ConfigBase::getFixStatus($row['status'])?></span>
                            </div>
                        </a>
                    </li>
                </div>
            <?php endforeach;?>
        </ul>
    <?php else:?>
        <h3 class="blank-text" style="font-size: 16px; padding-top:80px;">暂时没有维修记录</h3>
    <?php endif;?>
</div>