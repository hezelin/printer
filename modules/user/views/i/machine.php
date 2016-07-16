<?php
use yii\helpers\Url;
$this->title = '我的机器';
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
    <?php if($model || $project):?>
        <header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center; aui-col-xs-12">
            <div class="aui-pull-center">
                <span class="aui-pull-left">我的机器</span>
                <span class="aui-badge aui-badge-danger aui-extend-circle"><?=count($model)+count($project)?></span>
            </div>
        </header>
        <ul class="aui-list-view">
            <?php foreach($project as $row):?>
                <div class=" aui-overflow-hidden">
                    <li class="aui-list-view-cell aui-img" style="margin-top:10px;">
                        <a class="aui-arrow-right" href="<?=url::toRoute(['/user/rent/detail','id'=>$id,'project_id'=>$row['id']])?>">
                            <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                            <div class="aui-img-body">
                                <h2 class="aui-ellipsis-1"><?=$row['brand_name'],$row['model']?></h2>
                                <p class="aui-ellipsis-1 aui-text-default">
                                    月租 :
                                    <span class="aui-red-color">
                                        <span style="font-size:12px;">¥</span>
                                        <span style="font-size: 16px;"><?= $row['lowest_expense']?></span>
                                    </span>
                                </p>
                                <span class="aui-box aui-box-color" style="padding-left:5px;padding-right:5px;">租借申请中...</span>
                            </div>
                        </a>
                    </li>
                </div>
            <?php endforeach;?>

            <?php foreach($model as $row):?>
                <div class=" aui-overflow-hidden">
                    <li class="aui-list-view-cell aui-img" style="margin-top:10px;">
                        <a class="aui-arrow-right" href="<?=url::toRoute(['/user/rent/machine-detail','id'=>$id,'rent_id'=>$row['rent_id'],'from'=>'machine'])?>">
                            <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                            <div class="aui-img-body">
                                <h2 class="aui-ellipsis-1"><?=$row['brand_name'],$row['model_name']?></h2>
                                <p class="aui-ellipsis-1 aui-text-default">
                                    月租 : <span class="aui-red-color">¥<?= $row['monthly_rent']?></span>
                                </p>
                                <p class="aui-ellipsis-1 aui-text-default">
                                    到期 : <span class="aui-color-000"><?=date('Y年m月d',$row['due_time'])?></span>
                                </p>
                                <p class="aui-ellipsis-1 aui-text-default">
                                    维修次数 : <span class="aui-color-000"><?=$row['maintain_count']?></span>
                                </p>
                            </div>
                        </a>
                        <p class="aui-ellipsis-1" style="margin-left: 95px;">
                            <a class="aui-box aui-box-color aui-box-sm" href="<?=Url::toRoute(['/user/i/service','id'=>$id,'mid'=>$row['id']])?>">维修记录</a>
                            <a class="aui-box aui-box-color aui-box-sm" href="<?=url::toRoute(['/maintain/fault/apply','id'=>$id,'mid'=>$row['id']])?>">维修申请</a>
                        </p>
                    </li>
                </div>
            <?php endforeach;?>
        </ul>
        <div class="aui-fixed-bottom" style="height:44px; line-height: 44px;">
            <a class="aui-text-info" style=" font-size: 16px;" href="<?=url::toRoute(['/user/rent/list','id'=>$id])?>">
                租借机器
            </a>
        </div>
    <?php else:?>
        <header class="aui-bar aui-bar-nav aui-bar-color aui-border-b aui-text-info aui-text-center; aui-col-xs-12">
            <div class="aui-pull-center" >
                <span class="aui-pull-left" style="padding:0;">我的机器</span>
            </div>
        </header>
        <div class="blank-text" style="font-size: 16px; padding-top:80px;">您还没有租借的机器</div>
        <a href="<?= url::toRoute(['/user/rent/list','id'=>$id])?>" class="aui-btn my-btn-block">租借机器</a>
    <?php endif;?>
</div>